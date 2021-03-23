<?php require_once('Connections/connADMIN.php'); ?>
<?php
if(!isset($_SESSION)) {
  session_start();
}

try {
	$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
	$rsMeta = DB::getInstance()->prepare($query_rsMeta);
  $rsMeta->execute();
  $row_rsMeta = $rsMeta->fetchAll();
	$totalRows_rsMeta = $rsMeta->rowCount();
	DB::close();

  foreach($row_rsMeta as $row) {
		$title = $row["title"];
		$description = $row["description"];
		$keywords = $row["keywords"];
  }
} 
catch(PDOException $e) {
  echo $e->getMessage();
}

$menu_sel = "anular";

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<?php include_once('funcoes.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
	<div id="loader"></div>
</div>
<!--Preloader-->

<div class="mainDiv">
	<div class="row1">
  	<div class="div_table_cell news_remover">
  	 <?php include_once('header.php'); ?>
     <div class="div_100 news_cont">
        <div class="row content align-center">
          <div class="login_divs small-12 columns">
            <div class="div_100 text-center">
              <div class="titulos" style="margin-bottom: 1rem;"><?php echo $Recursos->Resources["confirmar_subs_tit"];?></div>
              <div style="margin-top: 3rem;"><img src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/checked.png" style="width: 100px; max-width: 90px; margin: auto;"></div>
              <div class="textos" style="margin-top: 2rem; font-size: 15px;"><?php echo $Recursos->Resources["mail_msg_5"];?></div>
            </div>
          </div>           
        </div>
      </div> 
	  </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>
<?php include_once('codigo_antes_body.php'); ?>
<?php include_once('footer_scripts.php'); ?>
</body>
</html>