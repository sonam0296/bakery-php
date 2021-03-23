<?php include_once('../inc_pages.php'); ?>
<?php header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");

$query_rsAnalytics = "SELECT * FROM analytics WHERE id='1'";
$rsAnalytics = DB::getInstance()->prepare($query_rsAnalytics);
$rsAnalytics->execute();
$row_rsAnalytics = $rsAnalytics->fetch(PDO::FETCH_ASSOC);
$totalRows_rsAnalytics = $rsAnalytics->rowCount();
DB::close();	

require_once ('analytics/GoogleAnalyticsServiceHandler.php');

$client_email = '280346278427-compute@developer.gserviceaccount.com';
$key_file = ('analytics/keys.p12');

$ga_handler = new GoogleAnalyticsServiceHandler($client_email,$key_file);
$ga_handler->set_profile_id('ga:143110365');

$anoInicial = 2010;
$meses = array("","Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
$meses2 = array("","Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");

if($_POST['op']=="geral"){
	
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	 
	// Busca os pageviews e visitas (total do mês passado)
	$num_visitas=0;
	$p_new_visits=0;
	$p_other_visits=0;
	
	$visitas=0;
	$visitantes=0;
	$pageviews=0;
	$newvisits=0;
	
	$flot_data = "";
	$flot_data2 = "";
	$flot_data_java = "[";
	$flot_data2_java = "[";
	$flot_data_visits = "[";
	$flot_data_views = "[";
	
	
	//SET DATES INTERVAL
	$ga_handler->set_analytics_start_date($inicio);
	$ga_handler->set_analytics_end_date($fim);
	
	//SET DIMENSIONS
	$dimensions='ga:date';
	$ga_handler->set_dimensions($dimensions);
	
	$metrics='ga:visits, ga:visitors, ga:pageviews, ga:newvisits';
	$ga_handler->set_metrics($metrics);
	
	$sort='ga:date';
	$ga_handler->set_sort($sort);
	
	/*$filter='ga:country==Portugal';
	$ga_handler->set_filter($filter);*/
	
	//RUN
	$data = $ga_handler->get_analytics();
	
	
	// Busca os pageviews e visitas de cada dia do último mês
	$count=0;
	foreach ($data->rows as $dados) {
			$visitas+=$dados[1];
			$visitantes+=$dados[2];
			$pageviews+=$dados[3];
			$newvisits+=$dados[4];
			
			$data=date("Y-m-d", strtotime($dados[0]));
			$mes=$meses2[date("n", strtotime($dados[0]))];
			$dia=date("d", strtotime($dados[0]));
			
			$string=$dia." ".$mes;
			
			$flot_data[$count]=strtotime($dados[0]);
			$flot_data2[$count]=$string;
			
			$flot_data_java .= "".strtotime($dados[0]).",";
			$flot_data2_java .= "'".$string."',";
			
			$flot_data_visits .= "[".strtotime($dados[0]).",".$dados[1]."],";
			$flot_data_views .= "[".strtotime($dados[0]).",".$dados[3]."],";
			
			$count ++;
	}
	$flot_data_java .= "]";
	$flot_data2_java .= "]";
	$flot_data_java = str_replace(",]", "]", $flot_data_java); 
	$flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
	
	$flot_data_visits .= "]";
	$flot_data_views .= "]";
	
	$flot_data_visits = str_replace("],]", "]]", $flot_data_visits); 
	$flot_data_views = str_replace("],]", "]]", $flot_data_views); 
	
	$num_visitas=$visitas;
	$p_new_visits=($newvisits/$num_visitas)*100;
	$p_other_visits=(($num_visitas-$newvisits)/$num_visitas)*100;
	
	$num_dias=count($flot_data);
	$max_dias=10;
	$per_dia=ceil($num_dias/$max_dias);
	
	$array_label="[";
	for($i=0; $i<=$num_dias; $i=$i+$per_dia){
			$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
	}
	$array_label .= "]";
	$array_label = str_replace("],]", "]]", $array_label); 
	
	
	
	
	//SET DATES INTERVAL
	$ga_handler->set_analytics_start_date($inicio);
	$ga_handler->set_analytics_end_date($fim);
	
	//SET DIMENSIONS
	$dimensions='ga:medium, ga:campaign';
	$ga_handler->set_dimensions($dimensions);
	
	$metrics='ga:visits';
	$ga_handler->set_metrics($metrics);
	
	$sort='ga:visits';
	$ga_handler->set_sort($sort);
	
	/*$filter='ga:country==Portugal';
	$ga_handler->set_filter($filter);*/
	
	//RUN
	$data = $ga_handler->get_analytics();
	
	$direto=0;
	$organico=0;
	$referencia=0;
	$campanhas=0;
	$adwords=0;
	
	foreach ($data->rows as $dados) {
		if($dados[0]=="(none)") $direto+=$dados[2];
		if($dados[0]=="organic") $organico+=$dados[2];
		if($dados[0]=="referral") $referencia+=$dados[2];
		if($dados[1]!="(not set)" && $dados[0]!="ppc" && $dados[0]!="cpc") $campanhas+=$dados[2];
		if($dados[0]=="ppc" || $dados[0]=="cpc") $adwords+=$dados[2];
	}
	
	$num_visitas=$visitas;
	$p_direto=($direto/$num_visitas)*100;
	$p_organico=($organico/$num_visitas)*100;
	$p_referencia=($referencia/$num_visitas)*100;
	$p_campanhas=($campanhas/$num_visitas)*100;
	$p_adwords=($adwords/$num_visitas)*100;
	
	
	?>
<style>
#chart_7 > div > div > a{
	display:none !important;
} 
</style>

<div class="row printable_div">
	<div class="row" style="width: 100%;">
        <div class="col-lg-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-green-haze"></i>
                        <span class="caption-subject bold uppercase font-green-haze"> <?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?></span>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                	<div class="row" style="width: 100%;">
                    	<div class="col-md-4">
                        	<table border="0" cellspacing="0" cellpadding="0" width="100%">
                             <tr>
                                <td height="100" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td align="left" class="dados_gerais"><strong><?php echo number_format($visitas, 0, "", " ");?></strong> <?php echo $RecursosCons->RecursosCons['opt_graph_2']; ?></td>
                                  </tr>
                                      <tr>
                                        <td align="left" class="dados_gerais"><strong><?php echo number_format($visitantes, 0, "", " ");?></strong> <?php echo $RecursosCons->RecursosCons['opt_graph_3']; ?></td>
                                  </tr>
                                      <tr>
                                        <td align="left" class="dados_gerais"><strong><?php echo number_format($pageviews, 0, "", " ");?></strong> <?php echo $RecursosCons->RecursosCons['opt_graph_4']; ?></td>
                                  </tr>
                                  <tr>
                                    <td align="left" class="dados_gerais"><strong><?php echo number_format($newvisits, 0, "", " ");?></strong> <?php echo $RecursosCons->RecursosCons['opt_graph_5']; ?></td>
                                  </tr>
                                    </table>
                                </td>
                            </tr>
                          </table>
                        </div>
                        <div class="col-md-8">
                        	<div id="chart_7" class="chart" style="height: 250px;">
                    		</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="width: 100%;">
        <div class="col-lg-12">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td align="left">
                        <select name="tipo2" id="tipo2" class="form-control select2me" style="width:300px;" onchange="est_visitas_carrega3(this.value, '<?php echo $inicio;?>', '<?php echo $fim;?>');">
                            <option value="graph_1"><?php echo $RecursosCons->RecursosCons['opt_graph_1']; ?></option>
                            <option value="graph_2"><?php echo $RecursosCons->RecursosCons['opt_graph_2']; ?></option>
                            <option value="graph_3"><?php echo $RecursosCons->RecursosCons['opt_graph_3']; ?></option>
                            <option value="graph_4"><?php echo $RecursosCons->RecursosCons['opt_graph_4']; ?></option>
                            <option value="graph_5"><?php echo $RecursosCons->RecursosCons['opt_graph_5']; ?></option>
                            <option value="graph_6"><?php echo $RecursosCons->RecursosCons['opt_graph_6']; ?></option>                            	
                        </select>
                    </td>
                </tr>
            </table>
            <div id="graph_div">
                
            </div>
            <div id="graph_loading" style="display:none"><table width="150" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="250" align="center"><table width="150" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="80" align="center"><img src="../../imgs/loading.gif" width="30" height="30" /></td>
                  </tr>
                </table></td>
              </tr>
            </table>
            </div>  
        </div>
    </div>
</div>

<?php 
}

if($_POST['op']=="geral2"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
          
	
	//SET DATES INTERVAL
	$ga_handler->set_analytics_start_date($inicio);
	$ga_handler->set_analytics_end_date($fim);
	
	//SET DIMENSIONS
	$dimensions='ga:medium, ga:campaign';
	$ga_handler->set_dimensions($dimensions);
	
	$metrics='ga:visits';
	$ga_handler->set_metrics($metrics);
	
	$sort='ga:visits';
	$ga_handler->set_sort($sort);
	
	
	//RUN
	$data = $ga_handler->get_analytics();
	
	// Busca os pageviews e visitas de cada dia do último mês
	$count=0;
	$direto=0;
	$organico=0;
	$referencia=0;
	$campanhas=0;
	$adwords=0;
	
	foreach ($data->rows as $dados) {
		if($dados[0]=="(none)")$direto+=$dados[2];
		if($dados[0]=="organic") $organico+=$dados[2];
		if($dados[0]=="referral") $referencia+=$dados[2];
		if($dados[1]!="(not set)" && $dados[0]!="ppc" && $dados[0]!="cpc") $campanhas+=$dados[2];
		if($dados[0]=="ppc" || $dados[0]=="cpc") $adwords+=$dados[2];
	}
	
	$graf = '[{
		"tipo": "'.$RecursosCons->RecursosCons['graf_tipo1'].'",
        "value": '.$direto.'
	}, {"tipo": "'.$RecursosCons->RecursosCons['graf_tipo2'].'",
        "value": '.$referencia.'
	}, {"tipo": "'.$RecursosCons->RecursosCons['graf_tipo3'].'",
        "value": '.$organico.'
	}, {"tipo": "'.$RecursosCons->RecursosCons['graf_tipo4'].'",
        "value": '.$campanhas.'
	}, {"tipo": "'.$RecursosCons->RecursosCons['graf_tipo5'].'",
        "value": '.$adwords.'
	}]';
	
	echo $graf;

}

if($_POST['op']=="graph_1"){
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	$flot_data = "";
	$flot_data2 = "";
	$flot_data_java = "[";
	$flot_data2_java = "[";
	$flot_data_visits = "[";
	$flot_data_views = "[";
	
	
	//SET DATES INTERVAL
	$ga_handler->set_analytics_start_date($inicio);
	$ga_handler->set_analytics_end_date($fim);
	
	//SET DIMENSIONS
	$dimensions='ga:date';
	$ga_handler->set_dimensions($dimensions);
	
	$metrics='ga:visits, ga:pageviews';
	$ga_handler->set_metrics($metrics);
	
	$sort='ga:date';
	$ga_handler->set_sort($sort);
	
	/*$filter='ga:country==Portugal';
	$ga_handler->set_filter($filter);*/
	
	//RUN
	$data = $ga_handler->get_analytics();
	
	
	$count=0;
	foreach ($data->rows as $dados) {
			$data=date("Y-m-d", strtotime($dados[0]));
			$mes=$meses2[date("n", strtotime($dados[0]))];
			$dia=date("d", strtotime($dados[0]));
			
			$string=$dia." ".$mes;
			
			$flot_data[$count]=strtotime($dados[0]);
			$flot_data2[$count]=$string;
			
			$flot_data_java .= "".strtotime($dados[0]).",";
			$flot_data2_java .= "'".$string."',";
			
			$flot_data_visits .= "[".strtotime($dados[0]).",".$dados[1]."],";
			$flot_data_views .= "[".strtotime($dados[0]).",".$dados[2]."],";
			
			$count ++;
	}
	$flot_data_java .= "]";
	$flot_data2_java .= "]";
	$flot_data_java = str_replace(",]", "]", $flot_data_java); 
	$flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
	
	$flot_data_visits .= "]";
	$flot_data_views .= "]";
	
	$flot_data_visits = str_replace("],]", "]]", $flot_data_visits); 
	$flot_data_views = str_replace("],]", "]]", $flot_data_views); 
	
	$num_dias=count($flot_data);
	$max_dias=10;
	$per_dia=ceil($num_dias/$max_dias);
	
	$array_label="[";
	for($i=0; $i<=$num_dias; $i=$i+$per_dia){
			$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
	}
	$array_label .= "]";
	$array_label = str_replace("],]", "]]", $array_label); 
	
	?>
    
    <div class="portlet box red">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse">
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div id="chart_2" class="chart">
            </div>
        </div>
    </div>
    
<script type="text/javascript">
$(document).ready(function() {
	var info1 = '<?php echo $RecursosCons->RecursosCons['visitas']; ?>';
	var info2 = '<?php echo $RecursosCons->RecursosCons['vizualizacoes']; ?>';
	var visits = <?php echo $flot_data_visits; ?>;        
	var views = <?php echo $flot_data_views; ?>;
	var array_label = <?php echo $array_label; ?>;
	var array_label1 = <?php echo $flot_data_java; ?>;
	var array_label2 = <?php echo $flot_data2_java; ?>;

	var plot = $.plot($("#chart_2"), [{
		data: visits,
		label: info1,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	},{
		data: views,
		label: info2,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	}], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.05
					}, {
						opacity: 0.01
					}]
				}
			},
			points: {
				show: true,
				radius: 3,
				lineWidth: 1
			},
			shadowSize: 2
		},
		grid: {
			hoverable: true,
			clickable: true,
			tickColor: "#eee",
			borderColor: "#eee",
			borderWidth: 1
		},
		
		xaxis: {
			ticks: array_label,
			tickDecimals: 0,
			tickColor: "#eee",
		},
		yaxis: {
			ticks: 11,
			tickDecimals: 0,
			tickColor: "#eee",
		}
	});
   

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				var cenas=array_label1.indexOf(Math.round(x));
				showTooltip(item.pageX, item.pageY, " Dia " + array_label2[cenas] + ": " + formatMoney(Math.round(y), 0,' ',',') +" "+ item.series.label);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});
});
</script>
<?php
}

if($_POST['op']=="graph_2"){
	
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	
	?>
        <?php
            
			$flot_data = "";
			$flot_data2 = "";
			$flot_data_java = "[";
			$flot_data2_java = "[";
			$flot_data_visits = "[";
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:date';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits';
			$ga_handler->set_metrics($metrics);
			
			$sort='ga:date';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $count=0;
			foreach ($data->rows as $dados) {				
					$data=date("Y-m-d", strtotime($dados[0]));
					$mes=$meses2[date("n", strtotime($dados[0]))];
					$dia=date("d", strtotime($dados[0]));
					
					$string=$dia." ".$mes;
					
					$flot_data[$count]=strtotime($dados[0]);
					$flot_data2[$count]=$string;
					
					$flot_data_java .= "".strtotime($dados[0]).",";
					$flot_data2_java .= "'".$string."',";
					
					$flot_data_visits .= "[".strtotime($dados[0]).",".$dados[1]."],";
					
					$count ++;
            }
            $flot_data_java .= "]";
            $flot_data2_java .= "]";
            $flot_data_java = str_replace(",]", "]", $flot_data_java); 
            $flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
			
            $flot_data_visits .= "]";
            
            $flot_data_visits = str_replace("],]", "]]", $flot_data_visits);
			
			$num_dias=count($flot_data);
			$max_dias=10;
			$per_dia=ceil($num_dias/$max_dias);
			
			$array_label="[";
			for($i=0; $i<=$num_dias; $i=$i+$per_dia){
					$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
			}
            $array_label .= "]";
            $array_label = str_replace("],]", "]]", $array_label); 
        ?>
        
       
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_2" class="chart">
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var info3 = '<?php echo $RecursosCons->RecursosCons['visitas']; ?>';
	var visits = <?php echo $flot_data_visits; ?>;
	var array_label = <?php echo $array_label; ?>;
	var array_label1 = <?php echo $flot_data_java; ?>;
	var array_label2 = <?php echo $flot_data2_java; ?>;

	var plot = $.plot($("#chart_2"), [{
		data: visits,
		label: info3,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	}], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.05
					}, {
						opacity: 0.01
					}]
				}
			},
			points: {
				show: true,
				radius: 3,
				lineWidth: 1
			},
			shadowSize: 2
		},
		grid: {
			hoverable: true,
			clickable: true,
			tickColor: "#eee",
			borderColor: "#eee",
			borderWidth: 1
		},
		
		xaxis: {
			ticks: array_label,
			tickDecimals: 0,
			tickColor: "#eee",
		},
		yaxis: {
			ticks: 11,
			tickDecimals: 0,
			tickColor: "#eee",
		}
	});
   

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				var cenas=array_label1.indexOf(Math.round(x));
				showTooltip(item.pageX, item.pageY, " Dia " + array_label2[cenas] + ": " + formatMoney(Math.round(y), 0,' ',',') +" "+ item.series.label);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});
});
</script>
<?php 
}

if($_POST['op']=="graph_3"){
	
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	
	?>
        <?php
            
			$flot_data = "";
			$flot_data2 = "";
			$flot_data_java = "[";
			$flot_data2_java = "[";
			$flot_data_visits = "[";
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:date';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visitors';
			$ga_handler->set_metrics($metrics);
			
			$sort='ga:date';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $count=0;
			foreach ($data->rows as $dados) {
					$data=date("Y-m-d", strtotime($dados[0]));
					$mes=$meses2[date("n", strtotime($dados[0]))];
					$dia=date("d", strtotime($dados[0]));
					
					$string=$dia." ".$mes;
					
					$flot_data[$count]=strtotime($dados[0]);
					$flot_data2[$count]=$string;
					
					$flot_data_java .= "".strtotime($dados[0]).",";
					$flot_data2_java .= "'".$string."',";
					
					$flot_data_visits .= "[".strtotime($dados[0]).",".$dados[1]."],";
					
					$count ++;
            }
            $flot_data_java .= "]";
            $flot_data2_java .= "]";
            $flot_data_java = str_replace(",]", "]", $flot_data_java); 
            $flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
			
            $flot_data_visits .= "]";
            
            $flot_data_visits = str_replace("],]", "]]", $flot_data_visits);
			
			$num_dias=count($flot_data);
			$max_dias=10;
			$per_dia=ceil($num_dias/$max_dias);
			
			$array_label="[";
			for($i=0; $i<=$num_dias; $i=$i+$per_dia){
					$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
			}
            $array_label .= "]";
            $array_label = str_replace("],]", "]]", $array_label); 
        ?>
        
       
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_2" class="chart">
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var info4 = '<?php echo $RecursosCons->RecursosCons['opt_graph_3']; ?>';
	var visits = <?php echo $flot_data_visits; ?>;
	var array_label = <?php echo $array_label; ?>;
	var array_label1 = <?php echo $flot_data_java; ?>;
	var array_label2 = <?php echo $flot_data2_java; ?>;

	var plot = $.plot($("#chart_2"), [{
		data: visits,
		label: "Visitantes Únicos",
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	}], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.05
					}, {
						opacity: 0.01
					}]
				}
			},
			points: {
				show: true,
				radius: 3,
				lineWidth: 1
			},
			shadowSize: 2
		},
		grid: {
			hoverable: true,
			clickable: true,
			tickColor: "#eee",
			borderColor: "#eee",
			borderWidth: 1
		},
		
		xaxis: {
			ticks: array_label,
			tickDecimals: 0,
			tickColor: "#eee",
		},
		yaxis: {
			ticks: 11,
			tickDecimals: 0,
			tickColor: "#eee",
		}
	});
   

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				var cenas=array_label1.indexOf(Math.round(x));
				showTooltip(item.pageX, item.pageY, " Dia " + array_label2[cenas] + ": " + formatMoney(Math.round(y), 0,' ',',') +" "+ item.series.label);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});
});
</script>
<?php 
}

if($_POST['op']=="graph_4"){
	
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	
	?>
        <?php
            
			$flot_data = "";
			$flot_data2 = "";
			$flot_data_java = "[";
			$flot_data2_java = "[";
			$flot_data_visits = "[";
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:date';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:pageViews';
			$ga_handler->set_metrics($metrics);
			
			$sort='ga:date';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $count=0;
			foreach ($data->rows as $dados) {
					$data=date("Y-m-d", strtotime($dados[0]));
					$mes=$meses2[date("n", strtotime($dados[0]))];
					$dia=date("d", strtotime($dados[0]));
					
					$string=$dia." ".$mes;
					
					$flot_data[$count]=strtotime($dados[0]);
					$flot_data2[$count]=$string;
					
					$flot_data_java .= "".strtotime($dados[0]).",";
					$flot_data2_java .= "'".$string."',";
					
					$flot_data_visits .= "[".strtotime($dados[0]).",".$dados[1]."],";
					
					$count ++;
            }
            $flot_data_java .= "]";
            $flot_data2_java .= "]";
            $flot_data_java = str_replace(",]", "]", $flot_data_java); 
            $flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
			
            $flot_data_visits .= "]";
            
            $flot_data_visits = str_replace("],]", "]]", $flot_data_visits);
			
			$num_dias=count($flot_data);
			$max_dias=10;
			$per_dia=ceil($num_dias/$max_dias);
			
			$array_label="[";
			for($i=0; $i<=$num_dias; $i=$i+$per_dia){
					$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
			}
            $array_label .= "]";
            $array_label = str_replace("],]", "]]", $array_label); 
        ?>
        
       
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_2" class="chart">
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var info5 = '<?php echo $RecursosCons->RecursosCons['opt_graph_4']; ?>';
	var visits = <?php echo $flot_data_visits; ?>;
	var array_label = <?php echo $array_label; ?>;
	var array_label1 = <?php echo $flot_data_java; ?>;
	var array_label2 = <?php echo $flot_data2_java; ?>;

	var plot = $.plot($("#chart_2"), [{
		data: visits,
		label: info5,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	}], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.05
					}, {
						opacity: 0.01
					}]
				}
			},
			points: {
				show: true,
				radius: 3,
				lineWidth: 1
			},
			shadowSize: 2
		},
		grid: {
			hoverable: true,
			clickable: true,
			tickColor: "#eee",
			borderColor: "#eee",
			borderWidth: 1
		},
		
		xaxis: {
			ticks: array_label,
			tickDecimals: 0,
			tickColor: "#eee",
		},
		yaxis: {
			ticks: 11,
			tickDecimals: 0,
			tickColor: "#eee",
		}
	});
   

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				var cenas=array_label1.indexOf(Math.round(x));
				showTooltip(item.pageX, item.pageY, " Dia " + array_label2[cenas] + ": " + formatMoney(Math.round(y), 0,' ',',') +" "+ item.series.label);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});
});
</script>
<?php 
}

if($_POST['op']=="graph_5"){
	
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	
	?>
        <?php
            
			$flot_data = "";
			$flot_data2 = "";
			$flot_data_java = "[";
			$flot_data2_java = "[";
			$flot_data_visits = "[";
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:date';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:newVisits';
			$ga_handler->set_metrics($metrics);
			
			$sort='ga:date';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $count=0;
			foreach ($data->rows as $dados) {				
					$data=date("Y-m-d", strtotime($dados[0]));
					$mes=$meses2[date("n", strtotime($dados[0]))];
					$dia=date("d", strtotime($dados[0]));
					
					$string=$dia." ".$mes;
					
					$flot_data[$count]=strtotime($dados[0]);
					$flot_data2[$count]=$string;
					
					$flot_data_java .= "".strtotime($dados[0]).",";
					$flot_data2_java .= "'".$string."',";
					
					$flot_data_visits .= "[".strtotime($dados[0]).",".$dados[1]."],";
					
					$count ++;
            }
            $flot_data_java .= "]";
            $flot_data2_java .= "]";
            $flot_data_java = str_replace(",]", "]", $flot_data_java); 
            $flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
			
            $flot_data_visits .= "]";
            
            $flot_data_visits = str_replace("],]", "]]", $flot_data_visits);
			
			$num_dias=count($flot_data);
			$max_dias=10;
			$per_dia=ceil($num_dias/$max_dias);
			
			$array_label="[";
			for($i=0; $i<=$num_dias; $i=$i+$per_dia){
					$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
			}
            $array_label .= "]";
            $array_label = str_replace("],]", "]]", $array_label); 
        ?>
        
       
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_2" class="chart">
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var info6 = '<?php echo $RecursosCons->RecursosCons['opt_graph_5']; ?>';
	var visits = <?php echo $flot_data_visits; ?>;
	var array_label = <?php echo $array_label; ?>;
	var array_label1 = <?php echo $flot_data_java; ?>;
	var array_label2 = <?php echo $flot_data2_java; ?>;

	var plot = $.plot($("#chart_2"), [{
		data: visits,
		label: info6,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	}], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.05
					}, {
						opacity: 0.01
					}]
				}
			},
			points: {
				show: true,
				radius: 3,
				lineWidth: 1
			},
			shadowSize: 2
		},
		grid: {
			hoverable: true,
			clickable: true,
			tickColor: "#eee",
			borderColor: "#eee",
			borderWidth: 1
		},
		
		
		xaxis: {
			ticks: array_label,
			tickDecimals: 0,
			tickColor: "#eee",
		},
		yaxis: {
			ticks: 11,
			tickDecimals: 0,
			tickColor: "#eee",
		}
	});
   

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				var cenas=array_label1.indexOf(Math.round(x));
				showTooltip(item.pageX, item.pageY, " Dia " + array_label2[cenas] + ": " + formatMoney(Math.round(y), 0,' ',',') +" "+ item.series.label);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});
});
</script>
<?php 
}

if($_POST['op']=="graph_6"){
	
	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	
	
	?>
        <?php
            
			$flot_data = "";
			$flot_data2 = "";
			$flot_data_java = "[";
			$flot_data2_java = "[";
			$flot_data_direto = "[";
			$flot_data_organico = "[";
			$flot_data_referencia = "[";
			$flot_data_campanha = "[";
			$flot_data_adwords = "[";
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:date, ga:medium, ga:campaign';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits';
			$ga_handler->set_metrics($metrics);
			
			$sort='ga:date';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $count=0;
			$dia=0;
			$visitas_adwords=0;
			foreach ($data->rows as $dados) {					
					
					if($dia!=strtotime($dados[0])){
						
						if($dia!=0){
							$flot_data_adwords .= "[".$dia.",".$visitas_adwords."],";
							$flot_data_referencia .= "[".$dia.",".$visitas_referencia."],";
							$flot_data_campanha .= "[".$dia.",".$visitas_campanha."],";
						}
						$visitas_adwords=0;
						$visitas_referencia=0;
						$visitas_campanha=0;
					
						$data=date("Y-m-d", strtotime($dados[0]));
						$mes=$meses2[date("n", strtotime($dados[0]))];
						$dia=date("d", strtotime($dados[0]));
						
						$string=$dia." ".$mes;
						
						$flot_data[$count]=strtotime($dados[0]);
						$flot_data2[$count]=$string;
						
						$flot_data_java .= "".strtotime($dados[0]).",";
						$flot_data2_java .= "'".$string."',";
						
						$count ++;
						$dia=strtotime($dados[0]);
					}
					
						if($dados[1]=="organic"){
							$flot_data_organico .= "[".strtotime($dados[0]).",".$dados[3]."],";
						}elseif($dados[1]=="referral"){
							//$flot_data_referencia .= "[".strtotime($dados[0]).",".$dados[3]."],";
							$visitas_referencia+=$dados[3];
						}elseif($dados[1]=="ppc" || $dados[1]=="cpc"){
							//$flot_data_adwords .= "[".strtotime($dados[0]).",".$dados[3]."],";
							$visitas_adwords+=$dados[3];
						}elseif($dados[2]!="(not set)" && $dados[1]!="ppc" && $dados[1]!="cpc"){
							//$flot_data_campanha .= "[".strtotime($dados[0]).",".$dados[3]."],";
							$visitas_campanha+=$dados[3];
						}else{
							$flot_data_direto .= "[".strtotime($dados[0]).",".$dados[3]."],";	
						}
            }
			
			
			if($dia!=0){
				$flot_data_adwords .= "[".$dia.",".$visitas_adwords."],";
				$flot_data_referencia .= "[".$dia.",".$visitas_referencia."],";
				$flot_data_campanha .= "[".$dia.",".$visitas_campanha."],";
			}
			
            $flot_data_java .= "]";
            $flot_data2_java .= "]";
            $flot_data_java = str_replace(",]", "]", $flot_data_java); 
            $flot_data2_java = str_replace(",]", "]", $flot_data2_java); 
			
            $flot_data_direto .= "]";
            $flot_data_direto = str_replace("],]", "]]", $flot_data_direto);
            $flot_data_organico .= "]";
            $flot_data_organico = str_replace("],]", "]]", $flot_data_organico);
            $flot_data_referencia .= "]";
            $flot_data_referencia = str_replace("],]", "]]", $flot_data_referencia);
            $flot_data_adwords .= "]";
            $flot_data_adwords = str_replace("],]", "]]", $flot_data_adwords);
            $flot_data_campanha .= "]";
            $flot_data_campanha = str_replace("],]", "]]", $flot_data_campanha);
			
			$num_dias=count($flot_data);
			$max_dias=10;
			$per_dia=ceil($num_dias/$max_dias);
			
			$array_label="[";
			for($i=0; $i<$num_dias; $i=$i+$per_dia){
					$array_label .= "[".$flot_data[$i].",'".$flot_data2[$i]."'],";					
			}
            $array_label .= "]";
            $array_label = str_replace("],]", "]]", $array_label); 
        ?>
        
       
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_2" class="chart">
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var label_direito = '<?php echo $RecursosCons->RecursosCons['graf_tipo1']; ?>';
	var label_referencia = '<?php echo $RecursosCons->RecursosCons['graf_tipo2']; ?>';
	var label_organico = '<?php echo $RecursosCons->RecursosCons['graf_tipo3']; ?>';
	var label_campanhas = '<?php echo $RecursosCons->RecursosCons['graf_tipo4']; ?>';
	var label_pago = '<?php echo $RecursosCons->RecursosCons['graf_tipo5']; ?>';
	var direto = <?php echo $flot_data_direto; ?>;
	var organico = <?php echo $flot_data_organico; ?>;
	var referencia = <?php echo $flot_data_referencia; ?>;
	var adwords = <?php echo $flot_data_adwords; ?>;
	var campanha = <?php echo $flot_data_campanha; ?>;
	var array_label = <?php echo $array_label; ?>;
	var array_label1 = <?php echo $flot_data_java; ?>;
	var array_label2 = <?php echo $flot_data2_java; ?>;
	
	var plot = $.plot($("#chart_2"), [{
		data: direto,
		label: label_direito,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	},{
		data: referencia,
		label: label_referencia,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	},{
		data: organico,
		label: label_organico,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	},{
		data: campanha,
		label: label_campanhas,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	},{
		data: adwords,
		label: label_pago,
		lines: {
			lineWidth: 1,
		},
		shadowSize: 0

	}], {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true,
				fillColor: {
					colors: [{
						opacity: 0.05
					}, {
						opacity: 0.01
					}]
				}
			},
			points: {
				show: true,
				radius: 3,
				lineWidth: 1
			},
			shadowSize: 2
		},
		grid: {
			hoverable: true,
			clickable: true,
			tickColor: "#eee",
			borderColor: "#eee",
			borderWidth: 1
		},
		
		xaxis: {
			ticks: array_label,
			tickDecimals: 0,
			tickColor: "#eee",
		},
		yaxis: {
			ticks: 11,
			tickDecimals: 0,
			tickColor: "#eee",
		}
	});
   

	var previousPoint = null;
	$("#chart_2").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				var cenas=array_label1.indexOf(Math.round(x));
				showTooltip(item.pageX, item.pageY, " Dia " + array_label2[cenas] + ": " + formatMoney(Math.round(y), 0,' ',',') +" "+ item.series.label);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});
});
</script>
<?php 
}

if($_POST['op']=="detalhado"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
	?>
<style>
#sample_5 th, #sample_5 td{
	text-align:center;
}

</style>     
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i></i><?php $RecursosCons->RecursosCons['de_label']; ?> <?php $RecursosCons->RecursosCons['ate_label'] .": ".$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body printable_div" style="overflow:inherit">
        <table class="table table-striped table-hover" id="sample_5">
        <thead>
        <tr>
            <th>
                 <?php echo $RecursosCons->RecursosCons['data']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['visitas']; ?>
            </th>
            <th class="hidden-xs">
                 <?php echo $RecursosCons->RecursosCons['visitantes_unicos']; ?>
            </th>
            <th class="hidden-xs">
                 <?php echo $RecursosCons->RecursosCons['vizualizacoes_pag']; ?>
            </th>
            <th class="hidden-xs">
                 <?php echo $RecursosCons->RecursosCons['novas_visitas']; ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php 
			
			$visitas=0;
			$visitantes=0;
			$pageviews=0;
			$newvisits=0;
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:date';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits, ga:visitors, ga:pageviews, ga:newVisits';
			$ga_handler->set_metrics($metrics);
			
			$sort='ga:date';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $contador=0;
			foreach ($data->rows as $dados) {	
					$contador ++;
					
					$data=date("Y-m-d", strtotime($dados[0]));
					$mes=$meses2[date("n", strtotime($dados[0]))];
					$dia=date("d", strtotime($dados[0]));
					$ano=date("Y", strtotime($dados[0]));
					
					$string=$dia." ".$mes." ".$ano;
					
					$visitas+=$dados[1];
					$visitantes+=$dados[2];
					$pageviews+=$dados[3];
					$newvisits+=$dados[4];
					
					?>
					
                    <tr>
                    <td><?php echo $string;?></td>
                    <td><?php echo $dados[1];?></td>
                    <td><?php echo $dados[2];?></td>
                    <td><?php echo $dados[3];?></td>
                    <td><?php echo $dados[4];?></td>
                    </tr>
            <?php    
            }
            ?>
        </tbody>
        </table>
    </div>
</div>          

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="pme-main">
<tr class="pme-row-0">
    <td width="150" height="15" valign="middle" class="pme-cell-0" style="text-align:center"><span class="link_linha"><b>Total</b></span></td>
    <td width="100" valign="middle" class="pme-cell-0" style="text-align:center"><strong><span class="link_linha"><?php echo $visitas;?></span></strong></td>
    <td width="170"  valign="middle" class="pme-cell-0" style="text-align:center"><strong><span class="link_linha"><?php echo $visitantes;?></span></strong></td>
    <td width="210" valign="middle" class="pme-cell-0" style="text-align:center"><strong><span class="link_linha"><?php echo $pageviews;?></span></strong></td>
    <td width="195" valign="middle" class="pme-cell-0" style="text-align:center"><strong><span class="link_linha"><?php echo $newvisits;?></span></strong></td>
</tr>
</table>
            
<?php }

if($_POST['op']=="fonte"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
 <div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body printable_div" style="overflow:inherit">
        <table class="table table-striped table-hover" id="sample_5">
        <thead>
        <tr>
            <th>
                 <?php echo $RecursosCons->RecursosCons['no.']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['origem_meio']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['visitas']; ?>
            </th>           
        </tr>
        </thead>
        <tbody>
        <?php 
			
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:source, ga:medium';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits';
			$ga_handler->set_metrics($metrics);
			
			$sort='-ga:visits';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $contador=0;
			foreach ($data->rows as $dados) {	
					$contador ++;
					
					if($dados[2]>0){		
					?>
					
                    <tr>
                    <td><?php echo $contador;?></td>
                    <td><?php echo $dados[0]." / <strong>".$dados[1]."</strong>";?></td>
                    <td><?php echo $dados[2];?></td>
                    </tr>
            <?php    
            }}
            ?>
        </tbody>
        </table>
    </div>
</div>          
           
<?php }

if($_POST['op']=="pais"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
 <div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body printable_div" style="overflow:inherit">
        <table class="table table-striped table-hover" id="sample_5">
        <thead>
        <tr>
            <th>
                 <?php echo $RecursosCons->RecursosCons['no.']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['pais']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['visitas']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['ver_mais']; ?>
            </th>           
        </tr>
        </thead>
        <tbody>          

            <?php
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:country';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits';
			$ga_handler->set_metrics($metrics);
			
			$sort='-ga:visits';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country==Portugal';
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $contador=0;
			foreach ($data->rows as $dados) {
					$contador ++;
						
					if($dados[1]>0){				
					?>
					
					<tr>
                   	  <td><?php echo $contador;?></td>
                      <td><a href="javascript:void(null)" onclick="est_visitas_carrega2('pais_detalhe', '<?php echo $inicio;?>', '<?php echo $fim;?>', '<?php echo $dados[0];?>');"><?php echo utf8_decode($dados[0]);?></a></td>
                      <td><?php echo $dados[1];?></td>
                      <td><a href="javascript:void(null)" class="fa fa-search-plus" onclick="est_visitas_carrega2('pais_detalhe', '<?php echo $inicio;?>', '<?php echo $fim;?>', '<?php echo $dados[0];?>');"></a></td>
             </tr>
            <?php   
					}
            }
            ?>
      </tbody>
        </table>
    </div>
</div>          
        
<?php }

if($_POST['op']=="pais_detalhe"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
           
<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left" valign="middle" class="datas_int"><a href="javascript:void(null)" onclick="est_visitas_carrega('pais', '<?php echo $inicio;?>', '<?php echo $fim;?>');"><?php echo $RecursosCons->RecursosCons['voltar_listagem']; ?></a></td>
  </tr>
</table>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i>
            <span class="caption-subject"><span style="font-size: 14px;"><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?></span></span>
            <span class="caption-helper" style="color:#fff;">Pa&iacute;s: <?php echo $_POST['filter'];?></span>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body printable_div" style="overflow:inherit">
        <table class="table table-striped table-hover" id="sample_5">
        <thead>
        <tr>
            <th>
                 <?php echo $RecursosCons->RecursosCons['no.']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['cidade']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['visitas']; ?>
            </th>          
        </tr>
        </thead>
        <tbody>          
        
            <?php 
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:city';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits';
			$ga_handler->set_metrics($metrics);
			
			$sort='-ga:visits';
			$ga_handler->set_sort($sort);
			
			$filter='ga:country=='.$_POST['filter'];
			$ga_handler->set_filter($filter);
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $contador=0;
			foreach ($data->rows as $dados) {
					$contador ++;
					
					if($dados[1]>0){
					?>
					
					<tr>
                   	  <td><?php echo $contador;?></td>
                      <td><?php echo utf8_decode($dados[0]);?></td>
                      <td><?php echo $dados[1];?></td>
            		</tr>
            <?php  
					}
            }
            ?>
      </tbody>
        </table>
    </div>
</div>          
        
<?php }

if($_POST['op']=="palavra_chave"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body printable_div" style="overflow:inherit">
        <table class="table table-striped table-hover" id="sample_5">
        <thead>
        <tr>
            <th>
                <?php echo $RecursosCons->RecursosCons['no.']; ?>
            </th>
            <th>
                <?php echo $RecursosCons->RecursosCons['palavras-chave_label']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['visitas']; ?>
            </th>           
        </tr>
        </thead>
        <tbody>
        <?php 
			
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:keyword';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:visits';
			$ga_handler->set_metrics($metrics);
			
			$sort='-ga:visits';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country=='.$_POST['filter'];
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $contador=0;
			foreach ($data->rows as $dados) {
					$contador ++;
							
					if($dados[1]>0){				
					?>
					
                    <tr>
                    <td><?php echo $contador;?></td>
                    <td><?php echo utf8_decode($dados[0]);?></td>
                    <td><?php echo $dados[1];?></td>
                    </tr>
            <?php    
            }}
            ?>
        </tbody>
        </table>
    </div>
</div>           
    
           
<?php }

if($_POST['op']=="paginas"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
   
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-globe"></i><?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body printable_div" style="overflow:inherit">
        <table class="table table-striped table-hover" id="sample_5">
        <thead>
        <tr>
            <th>
                <?php echo $RecursosCons->RecursosCons['no.']; ?>
            </th>
            <th>
                 <?php echo $RecursosCons->RecursosCons['palavras-chave_label']; ?>
            </th>
            <th>
                <?php echo $RecursosCons->RecursosCons['visitas']; ?>
            </th>           
        </tr>
        </thead>
        <tbody>
        <?php 
			//SET DATES INTERVAL
			$ga_handler->set_analytics_start_date($inicio);
			$ga_handler->set_analytics_end_date($fim);
			
			//SET DIMENSIONS
			$dimensions='ga:pagePath';
			$ga_handler->set_dimensions($dimensions);
			
			$metrics='ga:pageviews';
			$ga_handler->set_metrics($metrics);
			
			$sort='-ga:pageviews';
			$ga_handler->set_sort($sort);
			
			/*$filter='ga:country=='.$_POST['filter'];
			$ga_handler->set_filter($filter);*/
			
			//RUN
			$data = $ga_handler->get_analytics();
			
			
            $contador=0;
			foreach ($data->rows as $dados) {
					$contador ++;
							
					if($dados[1]>0){			
					?>
					
                    <tr>
                    <td><?php echo $contador;?></td>
                    <td><?php echo utf8_decode($dados[0]);?></td>
                    <td><?php echo $dados[1];?></td>
                    </tr>
            <?php    
            }}
            ?>
        </tbody>
        </table>
    </div>
</div>        

<?php }

if($_POST['op']=="navegador"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
<style>
#chart_5 > div > div > a{
	display:none !important;
} 
</style>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-bar-chart font-green-haze"></i>
            <span class="caption-subject bold uppercase font-green-haze"> <?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?></span>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_5" class="chart" style="height: 400px;"></div>
    </div>
</div>

           
<?php }

if($_POST['op']=="navegador2"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           

	//SET DATES INTERVAL
	$ga_handler->set_analytics_start_date($inicio);
	$ga_handler->set_analytics_end_date($fim);
	
	//SET DIMENSIONS
	$dimensions='ga:browser';
	$ga_handler->set_dimensions($dimensions);
	
	$metrics='ga:visits';
	$ga_handler->set_metrics($metrics);
	
	$sort='-ga:visits';
	$ga_handler->set_sort($sort);
	
	//RUN
	$data = $ga_handler->get_analytics();
	
	$contador=0;
	
	$cores = array("#FF0F00","#FF6600","#FF9E01","#8A0CCF","#F8FF01","#0D8ECF","#04D215","#B0DE09","#0D52D1","#2A0CD0","#FCD202","#754DEB","#DDDDDD","#999999","#333333");
	
	
	
	$graf = "[";
	
	foreach ($data->rows as $dados) {
		$contador ++;
		if($dados[1]>0){				
			$graf .= '{
				"navegador": "'.utf8_decode($dados[0]).'",
				"visitas": '.$dados[1].',
				"color": "'.$cores[$contador].'"
			}, ';
		}
	}	
	
	$graf = substr($graf,  0, -2);
	
	$graf .= "]";
	
	echo $graf;

}

if($_POST['op']=="sistema_operativo"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           
			?>
            
<style>
#chart_5 > div > div > a{
	display:none !important;
} 
</style>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-bar-chart font-green-haze"></i>
            <span class="caption-subject bold uppercase font-green-haze"> <?php echo $RecursosCons->RecursosCons['de_label']; ?>: <?=$inicio?> <?php echo $RecursosCons->RecursosCons['ate_label']; ?>: <?=$fim?></span>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div id="chart_5" class="chart" style="height: 400px;"></div>
    </div>
</div>

<?php }

if($_POST['op']=="sistema_operativo2"){

	if ($_POST['inicio']!="" && $inicio<=date("Y-m-d")) {
		$inicio = $_POST['inicio'];
	} else {
		$inicio = date("Y-m-01");
	}
	
	if ($_POST['fim']!="" && $_POST['fim']<=date("Y-m-d") && $_POST['fim']>=$inicio) {
		$fim = $_POST['fim'];
	} else {
		$fim = date("Y-m-d");
	}
           

	//SET DATES INTERVAL
	$ga_handler->set_analytics_start_date($inicio);
	$ga_handler->set_analytics_end_date($fim);
	
	//SET DIMENSIONS
	$dimensions='ga:operatingSystem';
	$ga_handler->set_dimensions($dimensions);
	
	$metrics='ga:visits';
	$ga_handler->set_metrics($metrics);
	
	$sort='-ga:visits';
	$ga_handler->set_sort($sort);
	
	//RUN
	$data = $ga_handler->get_analytics();
	
	$contador=0;
	
	$cores = array("#FF0F00","#FF6600","#FF9E01","#8A0CCF","#F8FF01","#0D8ECF","#04D215","#B0DE09","#0D52D1","#2A0CD0","#FCD202","#754DEB","#DDDDDD","#999999","#333333");
	
	$graf = "[";
	
	foreach ($data->rows as $dados) {
		$contador ++;
		if($dados[1]>0){				
			$graf .= '{
				"navegador": "'.utf8_decode($dados[0]).'",
				"visitas": '.$dados[1].',
				"color": "'.$cores[$contador].'"
			}, ';
		}
	}	
	
	$graf = substr($graf,  0, -2);
	
	$graf .= "]";
	
	echo $graf;

}

?>
