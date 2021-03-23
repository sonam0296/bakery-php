<?php include_once('../inc_pages.php'); ?>
<?php
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?php

if($_POST['op'] == 'ordenar_registos' ) {	
	$valor=$_POST['campos'];
	
	$array = explode("&",$valor);
	
	foreach ($array as $valor) {
		$valor_final = explode("=",$valor);		
		$ordem_final = $valor_final[1];
		
		$valor_final_2 = explode("_",$valor_final[0]);		
		$campo_id = $valor_final_2[1];		
		
		if($campo_id>0 && $ordem_final>0) {		
			$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
			$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
			$rsLinguas->execute();
			$totalRows_rsLinguas = $rsLinguas->rowCount();
			
			while($row_rsLinguas = $rsLinguas->fetch()) {		
				$insertSQL = "UPDATE l_caract_opcoes_".$row_rsLinguas["sufixo"]." SET ordem=:ordem_final WHERE id=:campo_id";
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