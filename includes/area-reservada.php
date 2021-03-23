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

if($row_rsCliente == 0){
	header("Location: login.php");	
	exit();
}	

$id_cliente = $row_rsCliente['id'];

$query_rsArea1 = "SELECT * FROM clientes_blocos".$extensao." WHERE visivel = 1 AND tipo = 1 ORDER BY rand() LIMIT 2";
$rsArea1 = DB::getInstance()->prepare($query_rsArea1);
$rsArea1->execute();
$row_rsArea1 = $rsArea1->fetchAll();
$totalRows_rsArea1 = $rsArea1->rowCount();

$query_rsArea2 = "SELECT * FROM clientes_blocos".$extensao." WHERE visivel = 1 AND tipo = 2 ORDER BY rand() LIMIT 2";
$rsArea2 = DB::getInstance()->prepare($query_rsArea2);
$rsArea2->execute();
$row_rsArea2 = $rsArea2->fetchAll();
$totalRows_rsArea2 = $rsArea2->rowCount();

$query_rsTickets = "SELECT COUNT(id) AS total FROM tickets WHERE id_cliente = '$id_cliente' AND id_pai = '0' AND estado != 0";
$rsTickets = DB::getInstance()->prepare($query_rsTickets);
$rsTickets->execute();
$row_rsTickets = $rsTickets->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTickets = $rsTickets->rowCount();

$query_rsEncomenda = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente = '$id_cliente' AND estado IN (4,5)";
$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
$rsEncomenda->execute();
$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncomenda = $rsEncomenda->rowCount();

$menu_sel = "area_reservada";
$menu_sel_area = "entrada";

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
                <span><?php echo $Recursos->Resources["bem_vindo"].$row_rsCliente['nome']; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>        
      <div class="div_100 area_reservada entrada">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>
          </div>
          <div class="column small-12 medium-9">
            <div class="listagem">
              <?php if($totalRows_rsArea1 > 0) { ?>
                <div class="div_100">
                  <?php foreach($row_rsArea1 as $bloco) { 
                    $img = "elem/geral.svg";
                    $tem_link = "";

                    if($bloco['imagem1'] && file_exists(ROOTPATH.'imgs/area_reservada/'.$bloco['imagem1'])) {
                      $img = "area_reservada/".$bloco['imagem1'];
                    }

                    if($bloco['link'] != '') { 
                      $tem_link = " hoverable";
                    }
                    ?><!-- 
                    --><figure class="area_bloco type1<?php echo $tem_link; ?>">
                      <div>
                        <picture class="has_bg lazy" data-src="<?php echo $img; ?>">
                          <?php echo getFill('area_reservada'); ?>        
                        </picture>
                        <figcaption class="absolute">
                          <div class="div_100">
                            <h2><?php echo $bloco['titulo']; ?></h2>
                            <p><?php echo $bloco['descricao']; ?></p>
                            <?php if($bloco['link'] != '') { ?>
                              <a class="linker" href="<?php echo $bloco['link']; ?>" target="<?php echo $bloco['target']; ?>"></a>
                            <?php } ?>
                          </div>
                        </figcaption>
                      </div>
                    </figure><!-- 
                  --><?php } ?>
                </div>
              <?php } ?>
              <div class="div_100">
                <a href="area-reservada-tickets.php" class="area_bloco type3">
                  <div class="div_100">
                    <h2><?php echo $Recursos->Resources['tickets_abertos']; ?></h2>
                    <p class="text-right"><?php echo $row_rsTickets['total']; ?></p>
                  </div>
                </a><!-- 
                --><a href="area-reservada-encomendas.php" class="area_bloco type3">
                  <div class="div_100">
                    <h2><?php echo $Recursos->Resources['enc_abertas']; ?></h2>
                    <p class="text-right"><?php echo $row_rsEncomenda['total']; ?></p>
                  </div>
                </a>
              </div>
              <?php if($totalRows_rsArea2 > 0) { ?>
                <div class="div_100">
                  <?php foreach($row_rsArea2 as $bloco) { 
                    $img = "elem/geral.svg";
                    if($bloco['imagem1'] && file_exists(ROOTPATH.'imgs/area_reservada/'.$bloco['imagem1'])) {
                      $img = "area_reservada/".$bloco['imagem1'];
                    }
                    ?><!-- 
                    --><div class="area_bloco type2">
                      <figure data-equalize="area_bloco_type2" data-equalize-stop="750">
                        <picture class="has_bg lazy" data-src="<?php echo $img; ?>">
                          <?php echo getFill('area_reservada', 2); ?>        
                        </picture>
                        <figcaption>
                          <h3 class="list_subtit"><?php echo $bloco['titulo']; ?></h3>
                          <div class="textos"><p><?php echo $bloco['descricao']; ?></p></div>
                          <?php if($bloco['link'] != '') { ?>
                            <a class="button border" href="<?php echo $bloco['link']; ?>" target="<?php echo $bloco['target']; ?>"><?php echo $bloco['texto_botao']; ?></a>
                          <?php } ?>
                        </figcaption>
                      </figure>
                    </div><!-- 
                  --><?php } ?>
                </div>
              <?php } ?>
              <div class="div_100">
                <?php if(CARRINHO_SALDO == 1) { ?>
                  <a href="area-reservada-tickets.php" class="area_bloco type3">
                    <div class="div_100">
                      <h2><?php echo $Recursos->Resources['saldo_disponivel']; ?></h2>
                      <p class="text-right"><?php echo $row_rsCliente['saldo']; ?></p>
                    </div>
                  </a><!--
                --><?php } ?><!-- 
                --><?php if(CARRINHO_PONTOS == 1) { ?><!--
                  --><a href="area-reservada-encomendas.php" class="area_bloco type3">
                    <div class="div_100">
                      <h2><?php echo $Recursos->Resources['pontos_disponiveis']; ?></h2>
                      <p class="text-right"><?php echo $row_rsCliente['pontos']; ?></p>
                    </div>
                  </a><!--
                --><?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>
<?php if(isset($_GET['intro']) && $_GET['intro'] == 1) { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      window.history.replaceState('Object', document.title, 'area-reservada.php');
      ntg_success("<?php echo $Recursos->Resources["reg_mail_front"]; ?>");
    });
  </script>
<?php } ?>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>