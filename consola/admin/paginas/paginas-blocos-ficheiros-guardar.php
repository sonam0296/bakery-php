<?php 
include_once('../inc_pages.php');
require("../resize_image.php");

$id_prod = $_POST['id_produto'];
$imagem = $_POST['ficheiro'];
$tipo = $_POST['tipo'];
$orientacao = $_POST['orientacao'];
$fullscreen = $_POST['fullscreen'];

$temp_var = explode('.', $imagem);

function formatSizeUnits($bytes) {
  if($bytes >= 1073741824) {
    $bytes = number_format($bytes / 1073741824, 2);
    $bytes = number_format(round($bytes, 1), 1, ',', '.');
    $bytes .= ' GB';
  }
  elseif($bytes >= 1048576) {
    $bytes = number_format($bytes / 1048576, 2);
    $bytes = number_format(round($bytes, 1), 1, ',', '.');
    $bytes .= ' MB';
  }
  elseif($bytes >= 1024) {
    $bytes = number_format($bytes / 1024, 2);
    $bytes = number_format(round($bytes, 1), 1, ',', '.');
    $bytes .= ' KB';
  }
  elseif($bytes > 1) {
    $bytes = $bytes . ' bytes';
  }
  elseif($bytes == 1) {
    $bytes = $bytes . ' byte';
  }
  else {
    $bytes = '0 bytes';
  }

  return $bytes;
}

if($imagem!="" && file_exists("../../../imgs/paginas/".$imagem)) {

	$file_size = filesize('../../../imgs/paginas/'.$imagem);
	$file_size = formatSizeUnits($file_size);

	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$row_rsLinguas = $rsLinguas->fetchAll();
	$totalRows_rsLinguas = $rsLinguas->rowCount();

	$insertSQL = "SELECT MAX(id) FROM paginas_blocos_ficheiros_pt";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->execute();
	$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
	
	$max_id_2 = $row_rsInsert["MAX(id)"]+1;

	foreach ($row_rsLinguas as $linguas) {		
		$insertSQL = "INSERT INTO paginas_blocos_ficheiros_".$linguas['sufixo']." (id, bloco, ficheiro, tamanho) VALUES (:max_id_2, :id_prod, :ficheiro, :tamanho)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
		$rsInsert->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
		$rsInsert->bindParam(':ficheiro', $imagem, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':tamanho', $file_size, PDO::PARAM_STR, 5);
		$rsInsert->execute();
	}

	DB::close();

	alteraSessions('paginas');
  alteraSessions('paginas_menu');
  alteraSessions('paginas_fixas');
}


?>
