<?php
if(!isset($_SESSION)) {
  session_start();
}

if($_POST['op_lg'] == "changeLG") {
	include_once('inc_pages.php');
	
	$username = $_SESSION['ADMIN_USER'];

	$query_rsUpdLG = "UPDATE acesso SET lingua=:lg WHERE username=:username AND activo='1'";
	$rsUpdate = DB::getInstance()->prepare($query_rsUpdLG);
	$rsUpdate->bindParam(':username', $username, PDO::PARAM_INT);
	$rsUpdate->bindParam(':lg', $_POST['lg'], PDO::PARAM_STR, 5);
	$rsUpdate->execute();
	DB::close();

	echo $_POST['lg'];
}

if($_POST['op_lg'] == "changeLG_login") {
	$_SESSION['lang'] = $_POST['lg_login'];
}

?>