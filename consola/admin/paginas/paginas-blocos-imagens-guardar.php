<?php 
include_once('../inc_pages.php');
require("../resize_image.php");

$id_prod = $_POST['id_produto'];
$imagem = $_POST['ficheiro'];
$tipo = $_POST['tipo'];
$orientacao = $_POST['orientacao'];
$colunas = $_POST['colunas'];
$fullscreen = $_POST['fullscreen'];

$tamanho_imagens2 = getFillSize('Paginas', 'imagem2');

$temp_var = explode('.', $imagem);
$extensao = strtolower($temp_var[sizeof($temp_var)-1]);

$tipo = 1;

if($imagem!="" && file_exists("../../../imgs/paginas/".$imagem)) {
	if($extensao== "jpg" || $extensao== "gif" || $extensao== "png" || $extensao== "jpeg"){ //Verifica se a extensão do ficheiro é imagem.
	
		if($tipo == 1 && $orientacao == 2 || $orientacao == 3){
			$maxW = $tamanho_imagens2['0'];
		}
		else if($tipo == 1 && $orientacao == 0 || $orientacao == 1){
			$maxW = $tamanho_imagens2['0']/2;
		}
		else if($tipo == 3 && $colunas == 2){
			$maxW = $tamanho_imagens2['0']/2;
		}
		else if($tipo == 3 && $colunas == 2){
			$maxW = $tamanho_imagens2['0']/3;
		}

		$maxH=10000;
		
		$sizes=getimagesize("../../../imgs/paginas/".$imagem);
		
		$imageW=$sizes[0];
		$imageH=$sizes[1];
		
		if($imageW>$maxW || $imageH>$maxH) {				
			$img1=new Resize("../../../imgs/paginas/", $imagem, $imagem, $maxW, $maxH);
			$img1->resize_image();
		}

		compressImage('../../../imgs/paginas/'.$imagem, '../../../imgs/paginas/'.$imagem);
		$tipo = 0;
	}
	
	$insertSQL = "SELECT MAX(id) FROM paginas_blocos_imgs";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->execute();
	$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
	
	$max_id_2 = $row_rsInsert["MAX(id)"]+1;			
	
	$insertSQL = "INSERT INTO paginas_blocos_imgs (id, bloco, imagem1, tipo) VALUES (:max_id_2, :id_prod, :imagem1, :tipo)";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
	$rsInsert->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
	$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':tipo', $tipo, PDO::PARAM_INT);
	$rsInsert->execute();

	DB::close();

	alteraSessions('paginas');
	alteraSessions('paginas_menu');
	alteraSessions('paginas_fixas');	
}


?>
