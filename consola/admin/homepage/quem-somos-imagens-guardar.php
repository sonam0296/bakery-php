<?php include_once('../inc_pages.php');

$imagem = $_POST['ficheiro'];

require("../resize_image.php");

$tamanho_imagens1 = getFillSize('Quem Somos', 'imagem1');

if($imagem!="" && file_exists("../../../imgs/quem-somos/".$imagem)) {
							
	$maxW=$tamanho_imagens1['0'];
	$maxH=$tamanho_imagens1['1'];
	
	$sizes=getimagesize("../../../imgs/quem-somos/".$imagem);
	
	$imageW=$sizes[0];
	$imageH=$sizes[1];
	
	if($imageW>$maxW || $imageH>$maxH) {			
		$img1=new Resize("../../../imgs/quem-somos/", $imagem, $imagem, $maxW, $maxH);
		$img1->resize_image();
	}

	compressImage('../../../imgs/quem-somos/'.$imagem, '../../../imgs/quem-somos/'.$imagem);
	
	$insertSQL = "SELECT MAX(id) FROM quem_somos_imagens";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->execute();
	$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
	
	$max_id_2 = $row_rsInsert["MAX(id)"]+1;			
	
	$insertSQL = "INSERT INTO quem_somos_imagens (id, imagem1) VALUES (:max_id_2, :imagem1)";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
	$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
	$rsInsert->execute();

	DB::close();	

	alteraSessions('quem_somos');
}

?>
