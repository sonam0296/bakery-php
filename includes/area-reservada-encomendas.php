<?php require_once('Connections/connADMIN.php'); ?>
<?php

$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$title = $row_rsMeta["title"];
$description = $row_rsMeta["description"];
$keywords = $row_rsMeta["keywords"];

if($row_rsCliente == 0) {
  header("Location: login.php");  
  exit();
} 

$id_cliente = $row_rsCliente['id'];

$query_rsEncomenda = "SELECT * FROM encomendas WHERE id_cliente = '$id_cliente' ORDER BY id DESC";
$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
$rsEncomenda->execute();
$totalRows_rsEncomenda = $rsEncomenda->rowCount();

$query_rsEstados = "SELECT * FROM enc_estados ORDER BY ordem ASC";
$rsEstados = DB::getInstance()->prepare($query_rsEstados);    
$rsEstados->execute();
$row_rsEstados = $rsEstados->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsEstados = $rsEstados->rowCount();

$calendar = file_get_contents(ROOTPATH.'imgs/elem/calendario.svg');

$menu_sel = "area_reservada";
$menu_sel_area = "encomendas";

DB::close();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Recursos->Resources["charset"];?>" />
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
<?php if($title){ echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); }else{ echo $Recursos->Resources["pag_title"];}?>
</title>
<?php if($description){?>
<META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php if($keywords!=""){?>
<META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php include_once('codigo_antes_head.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
  <div id="loader">
  </div>
</div>
<!--Preloader-->
<div class="mainDiv">
  <div class="row1">
    <div class="div_table_cell">
      <?php include_once('header.php'); ?>  
      <nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
          <div class="column">
            <ul class="breadcrumbs">
              <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
              <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
              <li>
                <span><?php echo $Recursos->Resources["minhas_encomendas"]; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>     
      <div class="div_100 area_reservada encomendas">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>
          </div>
            
            <div class="column small-12 medium-9">
              <div class="listagem">
              <div class="div_100 text-center">
                <?php if($totalRows_rsEncomenda) { ?>
                  <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["minhas_encomendas"]; ?></h3></div>
                  <div class="div_100 encomendas-list-container text-left">
                    <div class="filter_cont icon-search">
                      <input class="inpt_search textos custom_table_search" type="text" name="search" id="search" value="" placeholder="<?php echo $Recursos->Resources["pesq_enc"]; ?>" />
                    </div><!--
                    --><ul class="vertical dropdown menu" accordion style="max-width: 520px;">
                      <li class="filter_cont" accordion-item>
                        <a href="javascript:;" class="textos" accordion-title><?php echo $Recursos->Resources["filtrar_enc"]; ?></a>
                        <ul class="vertical menu nested" accordion-content>
                          <li>
                            <div class="div_100 filter_container text-left">                                            
                              <?php if($totalRows_rsEstados > 1) { ?>
                                <div class="div_100 groups" data-type="estado">
                                  <h1 class="list_subtit"><?php echo $Recursos->Resources["tck_estado"]; ?></h1>
                                  <div class="div_100">
                                    <?php foreach($row_rsEstados as $estados) { ?><!--
                                      --><div class="inpt_checkbox">
                                        <input class="custom_table_filters" type="checkbox" name="estados" id="estados_<?php echo $estados['id']; ?>" value="<?php echo $estados['nome'.$extensao]; ?>" />
                                        <label for="estados_<?php echo $estados['id']; ?>"><?php echo $estados["nome".$extensao]; ?></label>
                                      </div><!--
                                    --><?php } ?>
                                  </div>
                                </div>
                              <?php } ?>                                              
                              <div class="div_100 groups ranged" data-type="data">
                                <h1 class="list_subtit"><?php echo $Recursos->Resources["tck_data"]; ?></h1>
                                <div class="div_100">
                                  <div class="inpt_cells">
                                    <div class="inpt_holder full icon-calendar">
                                      <input required class="datepicker top inpt range all_dates custom_table_filters" data-range-start data-toggle="datepicker" type="text" name="enc_i" id="enc_i" value="" placeholder="DD / MM / AAAA" />
                                      <?php echo $calendar; ?>
                                    </div>
                                  </div><!--
                                  --><div class="inpt_cells">
                                    <div class="inpt_holder full icon-calendar">
                                      <input required class="datepicker top inpt range all_dates custom_table_filters" data-range-end data-toggle="datepicker" type="text" name="enc_f" id="enc_f" value="" placeholder="DD / MM / AAAA" />
                                      <?php echo $calendar; ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="div_100">
                                <a class="limpar_filtros" href="area-reservada-encomendas.php"><?php echo $Recursos->Resources["limpar_filtros"]; ?></a>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </div>
                  <div class="custom_table text-left padded" id="encomendas-list">
                    <div class="thead">
                      <div class="table-tr">
                        <div class="table-td sort numero" data-sort="numero">Nº <i></i></div>
                        <div class="table-td sort data text-center" data-sort="data"><?php echo $Recursos->Resources["tck_data"]; ?> <i></i></div>
                        <div class="table-td sort valor text-center" data-sort="valor"><?php echo $Recursos->Resources["valor"]; ?> <i></i></div>
                        <div class="table-td sort estado text-center" data-sort="estado"><?php echo $Recursos->Resources["tck_estado"]; ?> <i></i></div>
                        <div class="table-td text-center" style="width: 15%;">&nbsp;</div>
                        <div class="table-td text-center" style="width: 15%;">&nbsp;</div>
                      </div>
                    </div>
                    <div class="tbody list">
                      <?php while($row_rsEncomenda = $rsEncomenda->fetch()) { 
                        $estado = $row_rsEncomenda['estado'];
                        
                        $query_rsEstado = "SELECT * FROM enc_estados WHERE id='$estado'";
                        $rsEstado = DB::getInstance()->prepare($query_rsEstado);    
                        $rsEstado->execute();
                        $row_rsEstado = $rsEstado->fetch(PDO::FETCH_ASSOC);
                        $totalRows_rsEstado = $rsEstado->rowCount();
                        DB::close();

                        $data = date('d/m/Y', strtotime($row_rsEncomenda['data']));
                        $timestamp = strtotime($row_rsEncomenda['data']);

                        $valor = number_format($row_rsEncomenda['valor_c_iva'], 2, ',', '.')." ".$row_rsEncomenda['moeda'];

                        ?><!-- 
                        --><div class="table-tr list_div" data-encomenda="<?php echo $row_rsEncomenda['id']; ?>">
                          <div class="table-td numero" data-tit="Nº:">
                            <a href="imprime_encomenda.php?encomenda=<?php echo $row_rsEncomenda['id']; ?>&naoimprime=1" target="_blank"><?php echo $row_rsEncomenda['numero']; ?></a>
                          </div>
                          <div class="table-td data timestamp text-center" data-timestamp="<?php echo $timestamp; ?>" data-tit="<?php echo $Recursos->Resources["tck_data"]; ?>:"><?php echo $data; ?></div>
                          <div class="table-td valor text-center" data-tit="<?php echo $Recursos->Resources["valor"]; ?>:"><?php echo $valor; ?></div>
                          <div class="table-td estado text-center" data-tit="<?php echo $Recursos->Resources["tck_estado"]; ?>:" style="color:<?php echo $row_rsEstado['cor']; ?>"><?php echo $row_rsEstado['nome'.$extensao]; ?></div>
                          <div class="table-td text-center" style="width: 15%;">
                            <a href="imprime_encomenda.php?encomenda=<?php echo $row_rsEncomenda['id']; ?>&naoimprime=1" target="_blank"><?php echo $Recursos->Resources["ar_ver_detalhe"]; ?></a>
                          </div>
                          <div class="table-td text-center" style="width: 15%;">
                            <a href="javascript:;" onClick="repetirEnc('<?php echo $row_rsEncomenda['id']; ?>', '<?php echo $row_rsEncomenda['numero']; ?>');"><?php echo $Recursos->Resources["rep_encomenda"]; ?></a>
                          </div>
                        </div><!-- 
                      --><?php } ?>
                    </div>
                  </div>
                  <div class="hidden area_reservada_resultados textos"><?php echo $Recursos->Resources["sem_encomendas"]; ?></div>
                <?php } else { ?>
                  <div class="area_reservada_resultados textos"><?php echo $Recursos->Resources["sem_encomendas"]; ?></div>
                <?php } ?>
              </div>
              </div>
            </div>
          
        </div>
      </div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>
<script type="text/javascript">
  $(window).on('load', function() {
    var options = {
      valueNames: [ 
        'numero', 
        {name: 'data', attr: 'data-timestamp'},
        'estado',
      ],
    };

    sortableTable('encomendas-list', options, '.area_reservada_resultados');
    initialScripts();
    initCalendar();
  });  

  function abreEnc(id) {
    $.post("area-reservada-enc-prods.php", {id:id}, function(data) {
      $('#modalProdutos > #rpc').html(data);   
      $('#modalProdutos').foundation('open');
      init_inputs();
    });
  }

  function repetirEnc(id, num) {
    var texto = "<?php echo $Recursos->Resources["rep_encomenda_txt"]; ?>";
    texto = texto.replace('#num#', num);

    ntg_confirm({
        type: 'info',
        title: "<?php echo $Recursos->Resources["rep_encomenda_tit"];?>",
        html: texto,
        showCloseButton: true,
        showCancelButton: true,
        cancelButtonText: "<?php echo $Recursos->Resources["cancelar"];?>",
        showConfirmButton: true,
        confirmButtonText: "<?php echo $Recursos->Resources["ok"]; ?>",
      },
      function() { 
        $.post(_includes_path+"carrinho-rpc.php", {op:"repetirEncomenda", id:id}, function(data) {
          window.open('carrinho.php', '_parent');
        });
      },
      function(){
        return false;
      },
      "",
      ""
    );
  }
</script>

<?php if(isset($_GET['inserido']) && $_GET['inserido'] == 1) { ?>
  <script type="text/javascript">   
    ntg_alert("<?php echo $Recursos->Resources["formulario_tickets_msg"]; ?>");
  </script>
<?php } ?>

<?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
  <script type="text/javascript"> 
    ntg_alert("<?php echo $Recursos->Resources["resposta_sucesso"]; ?>"); 
  </script>
<?php } ?>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>