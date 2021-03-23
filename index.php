<?php require_once('Connections/connADMIN.php'); ?>

<?php



$query_rsLinguas = "SELECT id FROM linguas WHERE visivel = 1 AND ativo = 1 ORDER BY id ASC";

$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

$rsLinguas->execute();

$row_rsLinguas = $rsLinguas->fetchAll();

$totalRows_rsLinguas = $rsLinguas->rowCount();



require_once('linguasLG.php');

$extensao = $Recursos->Resources["extensao"];
$extensao = '_en';


$file_to_include = 'index.php';



if($totalRows_rsLinguas > 1) {

	$pasta = $lang;

	header("Location: ".$pasta."/");

}

else {

	$meta_id = 1;

	include("includes/index.php");

}



exit();

?>