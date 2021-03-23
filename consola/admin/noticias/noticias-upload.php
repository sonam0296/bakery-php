<?php include_once('../inc_pages.php'); ?>
<?php 

$id = $_REQUEST['id_imagem'];
$tipo = $_REQUEST['tipo_imagem'];
if(!isset($_REQUEST['id_imagem'])){
	$id = $_REQUEST['id_ficheiro'];
	$tipo = $_REQUEST['tipo_ficheiro'];
}
$opcao = $_REQUEST['opt'];
$extensao = $_REQUEST['extensao'];

$query_rsP = "SELECT * FROM noticias_pt WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

require("../resize_image.php");
			
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
	
	if($tipo == "imagem") {
		
		if($imagem!="" && file_exists("../../../imgs/noticias/".$imagem)){
							
			$maxW=1280;
			$maxH=400;
			
			$sizes=getimagesize("../../../imgs/noticias/".$imagem);
			
			$imageW=$sizes[0];
			$imageH=$sizes[1];
			
			if($imageW>$maxW || $imageH>$maxH){
								
				$img1=new Resize("../../../imgs/noticias/", $imagem, $imagem, $maxW, $maxH);
				$img1->resize_image();
				
			}
		
		}
		
	}

	if($opcao == 1) {
		if($tipo == "imagem"){
			$insertSQL = "UPDATE noticias".$extensao." SET imagem1=:imagem WHERE id=:id";
		}else{
			$insertSQL = "UPDATE noticias".$extensao." SET imagem3=:imagem WHERE id=:id";
		}
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $row_rsP["id"], PDO::PARAM_INT);
		$rsInsert->execute();
	}
	else if($opcao == 2) {
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
			if($tipo == "imagem"){
				$insertSQL = "UPDATE noticias_".$row_rsLinguas["sufixo"]." SET imagem1=:imagem WHERE id=:id";
			}else{
				$insertSQL = "UPDATE noticias_".$row_rsLinguas["sufixo"]." SET imagem3=:imagem WHERE id=:id";
			}
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $row_rsP["id"], PDO::PARAM_INT);
			$rsInsert->execute();
		}
	}

	DB::close();

	alteraSessions('noticias');

	echo $row_rsP["id"];
	
}/* else {  
                                                         
    $result  = array();
	$obj['name'] = $row_rsP["imagem3"];
	$obj['size'] = filesize("../../../imgs/noticias/".$row_rsP["imagem3"]);
	$result[] = $obj;
     
    header('Content-type: text/json');
    header('Content-type: application/json');
    echo json_encode($result);
}*/

?>