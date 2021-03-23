<?php


error_reporting(E_ALL); ini_set('display_errors', '1');

if(!isset($_SESSION)) {
  session_start();
}


//AGENDAMENTO
function logs_agendamentos($id, $que_fez, $nome_newsletter, $lista_nomes=''){
	
	include('../../../Connections/connADMIN.php');
	
	$utilizador=$_SESSION['ADMIN_USER'];
	
	$data_final=date('Y-m-d :: H:i:s'); 
	
	$insertSQL = "INSERT INTO newsletters_logs (utilizador, newsletter, newsletter_id, que_fez, data, listas) VALUES ('$utilizador', '$nome_newsletter', '$id', '$que_fez', '$data_final', '$lista_nomes')";
	$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
	$rsInsertSQL->execute();
	
	
}
?>