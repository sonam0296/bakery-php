<?php
$path = ""; 
if($redirect != 1 && $ishome == 0) {
	$path = "../../";
}

require_once($path.'Connections/connADMIN.php');

$query_rsLinguas = "SELECT * FROM linguas WHERE visivel = 1 AND ativo = 1 ORDER BY id ASC";
$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
$rsLinguas->execute();
$row_rsLinguas = $rsLinguas->fetchAll();
$totalRows_rsLinguas = $rsLinguas->rowCount();

$countLang = $totalRows_rsLinguas;
?>