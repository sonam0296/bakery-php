<?php 
include_once('../inc_pages.php');
require("../resize_image.php");

$id_prod = $_POST['id_produto'];
$imagem = $_POST['ficheiro'];

$tamanho_imagens1 = getFillSize('Produtos', 'imagem1');
$tamanho_imagens2 = getFillSize('Produtos', 'imagem2');
$tamanho_imagens3 = getFillSize('Produtos', 'imagem3');
$tamanho_imagens4 = getFillSize('Produtos', 'imagem4');

if($imagem!="" && file_exists("../../../imgs/produtos/".$imagem)){						
	$imagem4="gd_".$imagem;
	$img4=new Resize("../../../imgs/produtos", $imagem, $imagem4, $tamanho_imagens1['0'], $tamanho_imagens1['1']);
	$img4->resize_image();
	
	$sizes=getimagesize("../../../imgs/produtos/".$imagem);
	
	$imageW=$sizes[0];
	$imageH=$sizes[1];

	$maxW=$tamanho_imagens2['0'];
	$maxH=$tamanho_imagens2['1'];
	
	if($imageW>$maxW || $imageH>$maxH) {			
		$img1=new Resize("../../../imgs/produtos/", $imagem, $imagem, $tamanho_imagens2['0'], $tamanho_imagens2['1']);
		$img1->resize_image();
	}
						
	$imagem2="md_".$imagem;
	$img2=new Resize("../../../imgs/produtos/", $imagem, $imagem2, $tamanho_imagens3['0'], $tamanho_imagens3['1']);
	$img2->resize_image();
	
	$imagem3="pq_".$imagem;
	$img3=new Resize("../../../imgs/produtos/", $imagem, $imagem3, $tamanho_imagens4['0'], $tamanho_imagens4['1']);
	$img3->resize_image();

	//compressImage('../../../imgs/produtos/'.$imagem, '../../../imgs/produtos/'.$imagem);
	//compressImage('../../../imgs/produtos/'.$imagem2, '../../../imgs/produtos/'.$imagem2);
	//compressImage('../../../imgs/produtos/'.$imagem3, '../../../imgs/produtos/'.$imagem3);
	//compressImage('../../../imgs/produtos/'.$imagem4, '../../../imgs/produtos/'.$imagem4);
	
	$insertSQL = "SELECT MAX(id) FROM l_pecas_imagens";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->execute();
	$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
	
	$max_id_2 = $row_rsInsert["MAX(id)"]+1;			
	
	$insertSQL = "INSERT INTO l_pecas_imagens (id, id_peca, imagem1, imagem2, imagem3, imagem4) VALUES (:max_id_2, :id_prod, :imagem1, :imagem2, :imagem3, :imagem4)";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':imagem1', $imagem4, PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':imagem2', $imagem, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':imagem3', $imagem2, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':imagem4', $imagem3, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
	$rsInsert->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
	$rsInsert->execute();
}

DB::close();	

?>
