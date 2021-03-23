<?php
if(empty($_GET)) {
	require_once('../Connections/connADMIN.php'); 
}
else {
	require_once('Connections/connADMIN.php'); 
}

$resultado = 0;
$error = "";

if($_POST['op']=="reenvia_email" && $_POST['user']) {
	$class_user->reenvia_email($_POST['user']);
	exit();
}

if($_GET['id'] && $_GET['verification_code']) {
	$class_user->validaUser($_GET['id'], $_GET['verification_code']);
}

if(empty($_GET) && empty($_POST)) {
	header('location: login.php');
	exit();
}
?>