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

if($row_rsCliente != 0) {
	$id_cliente = $row_rsCliente['id'];
  $where = "lista.cliente = '$id_cliente'";
}
else {
  $wish_session = $_COOKIE[WISHLIST_SESSION];
  $where = "lista.session = '$wish_session'";
}

// 1 - listagem prods; 2 - tabela
$tipo_whish = 2; 

$query_rsFavoritos = "SELECT lista.*, pecas.url FROM lista_desejo AS lista LEFT JOIN l_pecas".$extensao." AS pecas ON lista.produto = pecas.id WHERE ".$where." AND pecas.visivel = 1 GROUP BY pecas.id ORDER BY pecas.ordem ASC";
$rsFavoritos = DB::getInstance()->prepare($query_rsFavoritos);
$rsFavoritos->execute();
$row_rsFavoritos = $rsFavoritos->fetchAll();
$totalRows_rsFavoritos = $rsFavoritos->rowCount();

$menu_sel = "area_reservada";
$menu_sel_area = "favoritos";

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
                <span><?php echo $Recursos->Resources["meus_favoritos"]; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="div_100 area_reservada favoritos">
        <div class="row">
          <?php if($row_rsCliente['id'] > 0) { ?>
            <div class="column small-12 medium-3">
              <?php include_once('area-reservada-menu.php'); ?>               
            </div>
          <?php } ?>
          <div class="column small-12<?php if($row_rsCliente['id'] > 0) { ?> medium-9<?php } ?> text-center">
            <div class="listagem">
            <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["meus_favoritos"]; ?></h3></div>
            <?php if($totalRows_rsFavoritos > 0) { ?>
              <?php if($row_rsCliente['id'] == 0) { ?>
                <h2 class="favoritos_login textos"><?php echo $Recursos->Resources["favoritos_login"]; ?></h2>
              <?php } ?>
              <div class="<?php if($tipo_whish == 1) echo "listagem"; else echo "div_100 favoritos-list-container"; ?> text-left">
                <div class="row collapse<?php if($tipo_whish == 1) echo " small-up-2 xxsmall-up-3 medium-up-4 large-up-5"; else echo " align-middle"; ?>">
                  <?php if($tipo_whish == 1) { ?>
                    <?php foreach($row_rsFavoritos as $favorito) { ?>
                      <div class="column">
                        <?php echo $class_produtos->divsProduto($favorito, 'elements_animated bottom', 1); ?>
                      </div>
                    <?php } ?>
                  <?php } else if($tipo_whish == 2) { ?>
                    <div class="column small-12 xxsmall-expand">
                      <div class="filter_cont icon-search">
                        <input class="inpt_search textos custom_table_search" type="text" name="search" id="search" value="" placeholder="<?php echo $Recursos->Resources["pesq_fav"]; ?>" />
                      </div>
                    </div>
                    <div class="column small-12 xxsmall-shrink">
                      <h3 class="list_txt"><?php echo $Recursos->Resources["favoritos_total"]; ?> <?php echo $totalRows_rsFavoritos; ?></h3>
                    </div>
                    <div class="column small-12">
                      <div class="custom_table text-left padded" id="favoritos-list">
                        <div class="thead">
                          <div class="table-tr">
                            <div class="table-td sort nome" data-sort="nome"><?php echo $Recursos->Resources["ar_produto"]; ?> <i></i></div>
                            <div class="table-td sort data text-center" data-sort="data"><?php echo $Recursos->Resources["tck_data"]; ?> <i></i></div>
                            <div class="table-td sort valor text-center" data-sort="valor"><?php echo $Recursos->Resources["cart_price"]; ?> <i></i></div>
                            <div class="table-td comprar">&nbsp;</div>
                            <div class="table-td detalhe">&nbsp;</div>
                          </div>
                        </div>
                        <div class="tbody list">
                          <?php foreach($row_rsFavoritos as $favorito) { 
                            $data_hora = explode(" ", $favorito['ult_atualizacao']);
                            $hora = substr($data_hora[1],0,5);
                            $data = date('d/m/Y', strtotime($data_hora[0]));
                            $timestamp = strtotime($favorito['ult_atualizacao']);
                            ?><!-- 
                            --><div class="table-tr list_div" data-favorito="<?php echo $favorito['id']; ?>">
                              <div class="table-td nome" data-tit="<?php echo $Recursos->Resources["ar_produto"]; ?>:">
                                <a href="<?php echo $favorito['url']; ?>"><?php echo $favorito['nome']; ?></a>
                              </div>
                              <div class="table-td data timestamp text-center" data-timestamp="<?php echo $timestamp; ?>" data-tit="<?php echo $Recursos->Resources["tck_data"]; ?>:"><?php echo $data." ".$hora; ?></div>
                              <div class="table-td valor text-center" data-tit="<?php echo $Recursos->Resources["cart_price"]; ?>:"><?php echo number_format($favorito['preco'],2,',','')."&pound;"; ?></div>
                              <div class="table-td comprar text-center">
                                <a class="button invert" href="<?php echo $favorito['url']; ?>"><?php echo $Recursos->Resources["comprar"]; ?></a>
                              </div>
                              <div class="table-td detalhe text-center">
                                <a class="close-button small active reload" onClick="adiciona_favoritos(<?php echo $favorito['produto']; ?>, this, event);" style="position: relative; top: auto;left: auto">&times;</a>
                              </div>
                            </div><!-- 
                          --><?php } ?>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            <?php } else { ?>
              <div class="area_reservada_resultados textos"><?php echo $Recursos->Resources["sem_favoritos"]; ?></div>
            <?php } ?>
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
        'nome', 
        {name: 'data', attr: 'data-timestamp'},
        'valor',
      ],
    };
    
    sortableTable('favoritos-list', options, '');
  });  
</script>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>