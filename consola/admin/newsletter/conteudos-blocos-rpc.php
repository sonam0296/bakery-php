<?php include_once('../inc_pages.php'); ?>
<?  
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?

if ($_POST['op'] == 'ordenar_registos' ) {	
	
	$valor=$_POST['campos'];
	
	$array = explode("&",$valor);
	
	foreach ($array as $valor) {
		
		$valor_final = explode("=",$valor);		
		$ordem_final = $valor_final[1];
		
		$valor_final_2 = explode("_",$valor_final[0]);		
		$campo_id = $valor_final_2[1];		
		
		if($campo_id>0 && $ordem_final>0){		
		
			$insertSQL = "UPDATE news_temas SET ordem='$ordem_final' WHERE id='$campo_id'";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->execute();
			DB::close();
		
		}
		
	}
	
	
}
?>