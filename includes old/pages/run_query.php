<?php include_once('../inc_pages.php'); ?>
<style type="text/css">
	.mask {
    display: none;
}
</style>
<?php 
	

	$query_rsProc = "SELECT ref, imagem1 FROM l_pecas".$extensao."";
	$rsProc = DB::getInstance()->prepare($query_rsProc);
	$rsProc->execute();
	$row_rsProc = $rsProc->fetchAll(PDO::FETCH_ASSOC);
	$jj = 1;
	foreach ($row_rsProc as $proc) {
		if (!file_exists(ROOTPATH."imgs/produtos/".$proc['imagem1'])) {
			print_r ($proc['ref']);
			echo "<br>";
			//echo "</pre>";
			$jj++;
		}

	}
	echo "<pre>";
	print_r ($jj);
	echo "</pre>";

	exit();
	$totalRows_rsProc = $rsProc->rowCount();

	$i = 1;
	foreach ($row_rsProc as $proc) {
		
		$id = $proc['id'];    
        $categoria = $proc['categoria'];

        $insertSQL = "SELECT MAX(id) FROM l_pecas_categorias";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->execute();
        $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
        
        $max_id_2 = $row_rsInsert["MAX(id)"] + 1;     
        
        // $insertSQL = "INSERT INTO l_pecas_categorias (id, id_peca, id_categoria) VALUES (:max_id_2, :id, :categoria)";
        // $rsInsert = DB::getInstance()->prepare($insertSQL);
        // $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
        // $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);  
        // $rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);  
        // $rsInsert->execute();

		$i++;	
	}
	// echo "<pre>";
	// print_r ($max_id_2);
	// echo "</pre>";

 ?>