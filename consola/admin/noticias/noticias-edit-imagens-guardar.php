<?php 

include_once('../inc_pages.php');

$id_prod = $_POST['id_noticia'];
$imagem = $_POST['ficheiro'];

require("../resize_image.php");

$tamanho_imagens1 = getFillSize('Noticias', 'imagem1');

$temp_var = explode('.', $imagem);

if($imagem!="" && file_exists("../../../imgs/noticias/".$imagem)) {
	
	/*
	* Verifica se é imagem. Se for imagem faz o resize e o compress. Se não for imagem, então é vídeo.
	*/
	if($temp_var[1]== "jpg" || $temp_var[1]== "gif" || $temp_var[1]== "png" || $temp_var[1]== "jpeg"){
		$maxW=$tamanho_imagens1['0'];
		$maxH=$tamanho_imagens1['1'];
		
		$sizes=getimagesize("../../../imgs/noticias/".$imagem);
		
		$imageW=$sizes[0];
		$imageH=$sizes[1];
		
		if($imageW>$maxW || $imageH>$maxH) {			
			$img1=new Resize("../../../imgs/noticias/", $imagem, $imagem, $maxW, $maxH);
			$img1->resize_image();
		}

		compressImage('../../../imgs/noticias/'.$imagem, '../../../imgs/noticias/'.$imagem);
	}

	$insertSQL = "SELECT MAX(id) FROM noticias_imagens";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->execute();
	$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
	
	$max_id_2 = $row_rsInsert["MAX(id)"]+1;			
	
	$insertSQL = "INSERT INTO noticias_imagens (id, id_peca, imagem1) VALUES (:max_id_2, :id_prod, :imagem1)";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
	$rsInsert->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
	$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
	$rsInsert->execute();

	DB::close();	

	alteraSessions('noticias');
}

?>
