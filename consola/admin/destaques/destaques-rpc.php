<?php include_once('../inc_pages.php'); ?>
<?php  
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?php

$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
$rsLinguas->execute();
$row_rsLinguas = $rsLinguas->fetchAll();
$totalRows_rsLinguas = $rsLinguas->rowCount();

if($_POST['op'] == 'ordenar_registos') {	
	$valor = $_POST['campos'];
	$array = explode("&",$valor);
	
	foreach ($array as $valor) {
		$valor_final = explode("=",$valor);		
		$ordem_final = $valor_final[1];
		
		$valor_final_2 = explode("_",$valor_final[0]);		
		$campo_id = $valor_final_2[1];		
		
		if($campo_id > 0 && $ordem_final > 0) {		
			foreach ($row_rsLinguas as $linguas) {	
				$insertSQL = "UPDATE destaques_".$linguas["sufixo"]." SET ordem=:ordem_final WHERE id=:campo_id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':ordem_final', $ordem_final, PDO::PARAM_INT);
				$rsInsert->bindParam(':campo_id', $campo_id, PDO::PARAM_INT);
				$rsInsert->execute();	
			}
		}
	}
}

DB::close();

?>