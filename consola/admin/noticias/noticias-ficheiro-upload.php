<?php include_once('../inc_pages.php'); ?>
<?php 

$id = $_REQUEST['id_ficheiro'];
$extensao = $_REQUEST['extensao'];
$opt = $_REQUEST['opt'];

$query_rsP = "SELECT id FROM noticias".$extensao." WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
			
$imagem="";	

$contaimg = 1; 

if (!empty($_FILES)) {
	foreach($_FILES as $file_name => $file_array) {
		
		$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
		
		switch ($contaimg) {
			case '1': case '2': case '3':    
				$file_dir =  "../../../imgs/noticias";
			break;
		}
		
	
		if($file_array['size'] > 0){
				$nome_img=verifica_nome(utf8_decode($file_array['name']));
				$nome_file = $id_file."_".$nome_img;
				@unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);
		}else {
				//$nome_file = $_POST['file_db_'.$contaimg];
	
			if($_POST['file_db'])
				$nome_file = $_POST['file_db'];
			else{
				$nome_file ='';
				@unlink($file_dir.'/'.$_POST['file_db_del']);
				}
	
		}
				
		if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); }
		
		//store the name plus index as a string 
		$variableName = 'nome_file' . $contaimg; 
		//the double dollar sign is saying assign $imageName 
		// to the variable that has the name that is in $variableName
		$$variableName = $nome_file; 	
		$contaimg++;
												
	} 
		
	$imagem = $nome_file1;

	if($opt == 1) {
		$insertSQL = "UPDATE noticias".$extensao." SET ficheiro=:imagem WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $row_rsP["id"], PDO::PARAM_INT);
		$rsInsert->execute();
	}
	else if($opt == 2) {
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel=1";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();

		while($row_rsLinguas = $rsLinguas->fetch()) {
			$insertSQL = "UPDATE noticias_".$row_rsLinguas['sufixo']." SET ficheiro=:imagem WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $row_rsP["id"], PDO::PARAM_INT);
			$rsInsert->execute();
		}
	}
	
	DB::close();

	alteraSessions('noticias');

	echo $row_rsP["id"];
	
}

?>