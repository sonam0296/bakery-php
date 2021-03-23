<?php require_once('Connections/connADMIN.php'); ?>
<?php

$query_rsManutencao = "SELECT * FROM config WHERE id = 1";
$rsManutencao = DB::getInstance()->prepare($query_rsManutencao);
$rsManutencao->execute();
$row_rsManutencao = $rsManutencao->fetch(PDO::FETCH_ASSOC);
$totalRows_rsManutencao = $rsManutencao->rowCount();

$ips = explode(",", str_replace(" ", "", $row_rsManutencao['ips']));

$ip = $_SERVER['REMOTE_ADDR'];
if($ip == "") {
	$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
}

if(!$row_rsManutencao['manutencao'] || (in_array($ip, $ips))) {
	header("Location: index.php");
  exit();
}

require_once('linguasLG.php'); 
$extensao = $Recursos->Resources["extensao"];

$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$title = $row_rsMeta["title"];
$description = $row_rsMeta["description"];
$keywords = $row_rsMeta["keywords"];

DB::close();

$menu_sel = "home";
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
<?php include_once('includes/codigo_antes_head.php'); ?>
</head>
<div class="mask">
	<div id="loader">
    
  </div>
</div>

<body class="overHidden">
  <div class="mainDiv manutencao has_bg has-overlay" style="background-image: url('<?php echo ROOTPATH_HTTP; ?>imgs/404/bg.jpg');">
    <div class="div_100" style="height: 100%;">
      <div class="div_table_cell text-center">
        <div class="container_404">
          <div class="row collapse text-center align-middle">
            <div class="columns info">
              <div class="div_100 logo_404"><img src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/logo.svg" width="100%" /></div>
              <h1 class="elements_animated top"><?php echo $Recursos->Resources["manutencao"]; ?></h1>
              <h3 class="elements_animated left"><?php echo $Recursos->Resources["manutencao2"]; ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once("includes/footer_scripts.php"); ?>
  <?php include_once("includes/codigo_antes_body.php"); ?> 
</body>
</html>