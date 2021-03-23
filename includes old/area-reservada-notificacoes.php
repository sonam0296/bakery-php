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
	exit;
}	

$id_cliente = $row_rsCliente['id'];

$form_notificacoes = $csrf->form_names(array('aceita_encomendas'), false);
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_notificacoes")) {
  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) {      
      if(isset($_POST['aceita_newsletter'])) {
        include_once(ROOTPATH."includes/subs_obrigado.php");
        insereSubs($row_rsCliente["email"], "Notificações área reservada", 0);
      }

      header("Location: area-reservada-notificacoes.php?alterado=1");
      exit();
    }
  }
  else {
    header("Location: area-reservada-notificacoes.php?alterado=1");
    exit();
  }
}

$menu_sel = "area_reservada";
$menu_sel_area = "notificacoes";

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
	<div id="loader"></div>
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
                <span><?php echo $Recursos->Resources["minhas_notificacoes"]; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="div_100 area_reservada notificacoes">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>               
          </div>
          <div class="column small-12 medium-9">
            <div class="listagem">
            <div class="div_100">
              <div class="elements_animated right text-center">
                <form action="" class="area_margin" method="POST" name="form_notificacoes" id="form_notificacoes" onSubmit="return validaForm('form_notificacoes')" autocomplete="off" novalidate>
                  <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["minhas_notificacoes"]; ?></h3></div>
                  <div class="div_100 text-left">
                    <ul class="notificacoes_cont">
                      <li class="inpt_holder simple">
                        <div class="noti-wrapper">
                          <input name="<?php echo $form_notificacoes['aceita_newsletter']; ?>" id="<?php echo $form_notificacoes['aceita_newsletter']; ?>" type="checkbox">
                          <span></span>
                        </div><!-- 
                        --><label class="textos" for="<?php echo $form_notificacoes['aceita_newsletter']; ?>"><?php echo $Recursos->Resources["aceita_newsletter"]; ?></label>
                      </li>
                    </ul>                             

                    <button type="submit" class="button invert"><?php echo $Recursos->Resources["enviar"]; ?></button> 

                    <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                    <input type="hidden" name="MM_insert" value="form_notificacoes" />
                    <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                  </div>
                </form>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>