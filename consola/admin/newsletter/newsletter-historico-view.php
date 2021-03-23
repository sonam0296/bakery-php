<?php include_once('../inc_pages.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

$id=$_GET['id'];

function getBetween($var1="",$var2="",$pool) {
  $temp1 = strpos($pool,$var1)+strlen($var1);
  $result = substr($pool,$temp1,strlen($pool));
  $dd=strpos($result,$var2);
  
  if($dd == 0) {
    $dd = strlen($result);
  }

  return substr($result,0,$dd);
}

function emails_devolvidos($id_news, $id_agendamento) {
  // $mail_box = '{cpanel.netgocioserver4.com:995/novalidate-cert/pop3/ssl}'; //imap example
  // $mail_user = 'webtech.dev@gmail.com'; //mail username
  // $mail_pass = 'mit99049#sa'; //mail password

  //$mail_box = '{cpanel.netgocioserver4.com:995/novalidate-cert/pop3/ssl}'; //imap example
  $mail_user = 'webtech.dev@gmail.com'; //mail username
  $mail_pass = 'mit99049'; //mail password

  $total = 0;

  $conn = imap_open ($mail_box, $mail_user, $mail_pass) or die(imap_last_error());
  $num_msgs = imap_num_msg($conn);

  require_once('bounce_handler/bounce_driver.class.php');
  $bouncehandler=new Bouncehandler();

  for($n=1;$n<=$num_msgs;$n++) {
    $bounce = imap_fetchheader($conn, $n).imap_body($conn, $n);

    if(strpos($bounce, 'Action: failed') !== false) {
      if(strpos($bounce, '550 No Such User Here') !== false) {
        $erro = 1;

        $news = trim(getBetween('NewsCod:', '#', $bounce));
        $agendamento = trim(getBetween('NewsAgenCod:', '#', $bounce));
        $email = trim(getBetween('NewsEmail:', '#', $bounce));
      }
      else if(strpos($bounce, 'Mailbox is full') !== false) {
        $erro = 2;

        $news = trim(getBetween('NewsCod:', '#', $bounce));
        $agendamento = trim(getBetween('NewsAgenCod:', '#', $bounce));
        $email = trim(getBetween('NewsEmail:', '#', $bounce));
      }
      else {
        $erro = 0;

        $news = trim(getBetween('NewsCod:', '#', $bounce));
        $agendamento = trim(getBetween('NewsAgenCod:', '#', $bounce));
        $email = trim(getBetween('NewsEmail:', '#', $bounce));
      }

      if($news == $id_news && $agendamento == $id_agendamento)
        $total++;
    }
  }

  imap_close($conn);

  return $total;
}

$query_rsP = "SELECT newsletters_historico.*, newsletters_historico_estados.nome as nome_estado FROM newsletters_historico, newsletters_historico_estados WHERE newsletters_historico.id=:id AND newsletters_historico.estado=newsletters_historico_estados.id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsNews = "SELECT * FROM newsletters WHERE id='".$row_rsP['newsletter_id']."'";
$rsNews = DB::getInstance()->prepare($query_rsNews);
$rsNews->execute();
$row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);
$totalRows_rsNews = $rsNews->rowCount();
DB::close();

$query_rsTipo = "SELECT * FROM news_grupos WHERE id='".$row_rsP['grupo']."'";
$rsTipo = DB::getInstance()->prepare($query_rsTipo);
$rsTipo->execute();
$row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTipo = $rsTipo->rowCount();
DB::close();

$query_rsList = "SELECT news_listas.* FROM newsletters_historico_listas, news_listas WHERE newsletters_historico_listas.newsletter_historico='".$row_rsP['id']."' AND newsletters_historico_listas.lista=news_listas.id GROUP BY news_listas.id ORDER BY news_listas.ordem ASC, news_listas.nome ASC";
$rsList = DB::getInstance()->prepare($query_rsList);
$rsList->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsList->execute();
$totalRows_rsList = $rsList->rowCount();
DB::close();

$array_lista="";

if($totalRows_rsList > 0) {
	while($row_rsList = $rsList->fetch()) {
		if($array_lista=="") {
			$array_lista.=$row_rsList['nome'];
		}
    else {
			$array_lista.=", ".$row_rsList['nome'];
		}
	}
}

$total_emails = 0;
$total_enviados = 0;
$total_vistos = 0;
$total_vistos_unicos = 0;
$i = 0;
$subtotal = 0;
$vistos = 0;
$vistos_unicos = 0;
$n_links = 0;
$n_cliques = 0;
$array_news = array();
$perc1 = 0;
$perc2 = 0;
$perc3 = 0;
$media1 = 0;
$n_devolvidos = 0;
$n_recebidos = 0;


$total_emails = $row_rsP['emails_total'];
$total_enviados = $row_rsP['emails_enviados'];
$total_vistos = $row_rsP['emails_vistos'];
$total_vistos_unicos = $row_rsP['emails_vistos_unicos'];


//Calcular total de links na newsletter
if(!in_array($row_rsP['newsletter_id'], $array_news)) {
  $query_rsLinks = "SELECT COUNT(DISTINCT(url)) as total FROM news_links WHERE newsletter_id_historico='".$row_rsP['id']."'";
  $rsLinks = DB::getInstance()->prepare($query_rsLinks);
  $rsLinks->execute();
  $totalRows_rsLinks = $rsLinks->rowCount();
  $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);
  DB::close();

  $n_links = $row_rsLinks['total'];
  array_push($array_news, $row_rsP['newsletter_id']);
}


//Emais recebidos/devolvidos
// if(strstr($_SERVER['HTTP_HOST'], SERVIDOR)) {
//   $res = emails_devolvidos($row_rsP['newsletter_id'], $row_rsP['id']);

//   $res2 = $row_rsP['emails_enviados'] - $res;

//   $n_devolvidos = $res;
//   $n_recebidos = $res2;
// }


//Calcular total de cliques na newsletter
$query_rsCliques = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico='".$row_rsP['id']."'";
$rsCliques = DB::getInstance()->prepare($query_rsCliques);
$rsCliques->execute();
$totalRows_rsCliques = $rsCliques->rowCount();
$row_rsCliques = $rsCliques->fetch(PDO::FETCH_ASSOC);
DB::close();

$n_cliques = $row_rsCliques['total'];

//Calcular média entre a data de envio e a data de abertura
$query_rsMedia = "SELECT data_visto, data_envio FROM newsletters_vistos WHERE newsletter_id_historico='".$row_rsP['id']."'";
$rsMedia = DB::getInstance()->prepare($query_rsMedia);
$rsMedia->execute();
$totalRows_rsMedia = $rsMedia->rowCount();
DB::close();

while($row = $rsMedia->fetch()) {
  if($row['data_visto'] != NULL) {
    $min = round((strtotime($row['data_visto']) - strtotime($row['data_envio']))/(60));
    $subtotal+=$min;
    $i++;
  }
}

//Calcular a percentagem de visualizações únicas
$query_rsPerc = "SELECT * FROM newsletters_historico WHERE id='".$row_rsP['id']."'";
$rsPerc = DB::getInstance()->prepare($query_rsPerc);
$rsPerc->execute();
$row_rsPerc = $rsPerc->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPerc = $rsPerc->rowCount();
DB::close();

$vistos = $row_rsPerc['emails_vistos'];
$vistos_unicos+= $row_rsPerc['emails_vistos_unicos'];


//Calcular percentagem de emails enviados
$perc1 = round($total_enviados / $total_emails, 2) * 100;

//Calcular percentagem de emails abertos
$perc2 = round($total_vistos_unicos / $total_enviados, 2) * 100;

//Calcular média entre a data de envio e a data de abertura
$media1 = round($subtotal / $i, 2);

//Calcular a percentagem de visualizações únicas
$perc3 = round($vistos_unicos / $vistos, 3) * 100;

//Calcular a percentagem de emails recebidos
$perc4 = round($n_recebidos / $total_enviados, 2) * 100;

//Calcular a percentagem de emails devolvidos
$perc5 = round($n_devolvidos / $total_enviados, 2) * 100;

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix"> </div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content"> 
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title"> <?php echo $row_rsNews["titulo"]; ?> <small>agendamentos</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="newsletter.php">Newsletters</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-search"></i>Newsletters - Dados envio</div>
              <div class="form-actions actions btn-set">
                <button type="button" name="back" class="btn default" onClick="document.location='newsletter-historico.php?id=<?php echo $row_rsNews["id"]; ?>'"><i class="fa fa-angle-left"></i> Voltar</button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-control" style="border:0; background-color:#EFEFEF"><strong><?php echo $row_rsNews["titulo"]; ?></strong></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Agendamento:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $row_rsP['data']; ?> // <?php echo date('H:i',strtotime($row_rsP['hora'])); ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Estado:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $row_rsP['nome_estado']; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Tipo de Cliente:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php if($row_rsTipo['nome']) { echo $row_rsTipo['nome']; } else { echo "---"; } ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Listas:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $array_lista; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Total de Emails:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $row_rsP['emails_total']; ?></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-control" style="border:0; background-color:#EFEFEF"><strong>Envio</strong></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Início:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php if($row_rsP['data_inicio']>0){ ?><?php echo $row_rsP['data_inicio']; ?> // <?php echo date('H:i',strtotime($row_rsP['hora_inicio'])); ?><?php }else echo "-----"; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong>Fim:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php if($row_rsP['data_fim']>0){ ?><?php echo $row_rsP['data_fim']; ?> // <?php echo date('H:i',strtotime($row_rsP['hora_fim'])); ?><?php }else echo "-----"; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-control" style="border:0; background-color:#EFEFEF"><strong>Estatísticas</strong></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Total de emails:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $total_emails; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails escolhidos para receber a newsletter</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Enviados:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px">
          						<?php if($row_rsP['estado'] == 2) {
          							echo $totalRows_rsMedia." <em>(a enviar)</em>";
          						}
                      else {
          							echo $total_enviados;							
          						}
                      ?>
                    </div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails enviados</p>
                </div>
              </div>
              <!-- <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Percentagem de enviados:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $perc1."%"; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails enviados</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Recebidos:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $n_recebidos; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que receberam a newsletter</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Percentagem de recebidos:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $perc4."%"; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails recebidos</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Devolvidos:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_devolvidos; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que não receberam a newsletter</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Percentagem de devolvidos:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $perc5."%"; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails devolvidos</p>
                </div>
              </div> -->
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Visualizações:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $total_vistos; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de visualizações</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Visualizações únicas:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $total_vistos_unicos; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de visualizações únicas (uma por cada email)</p>
                </div>
              </div>
              <!-- <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Percentagem de visualizações únicas:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $perc2."%"; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de visualizações únicas</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Rácio entre visualizações:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $perc3."%"; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">do nº total de visualizações, a percentagem correspondente às visualizações únicas</p>
                </div>
              </div> -->
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Média de tempo:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $media1." minuto(s)"; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">média de tempo entre receber a newsletter e abrir o email</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Total de links:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $n_links; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de links únicos presentes na newsletter</p>
                </div>
              </div>
              <div class="row">
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-md-3 control-label" style="text-align: right; font-size: 13px"><strong>Total de cliques:</strong></label>
                  <div class="col-md-3" style="padding-top: 1px;">
                    <div class="form-control" style="border:0; padding: 0 12px"><?php echo $n_cliques; ?></div>
                  </div>
                  <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cliques em todos os links da newsletter</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END PAGE CONTENT--> 
      </div>
    </div>
    <!-- END CONTENT -->
    <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
  </div>
  <!-- END CONTAINER --> 
</div>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>