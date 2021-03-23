<?php include_once('../inc_pages.php'); ?>
<?php 

require("../resize_image.php");

$tipo2 = $_REQUEST['tipo2'];
			
$imagem="";	

$contaimg = 1; 

if (!empty($_FILES)) {
	foreach($_FILES as $file_name => $file_array) {
		
		$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
		
		switch ($contaimg) {
			case '1': case '2': case '3':    
				$file_dir =  "../../../imgs/imgs_news/produtos";
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
	
	if($imagem!="" && file_exists("../../../imgs/imgs_news/produtos/".$imagem)) {
							
		if($tipo2 == 1) {
			$maxW=300;
			$maxH=800;
		} else {
			$maxW=600;
			$maxH=2000;
		}
		
		$sizes=getimagesize("../../../imgs/imgs_news/produtos/".$imagem);
		
		$imageW=$sizes[0];
		$imageH=$sizes[1];
		
		if($imageW>$maxW || $imageH>$maxH){
							
			$img1=new Resize("../../../imgs/imgs_news/produtos/", $imagem, $imagem, $maxW, $maxH);
			$img1->resize_image();
			
		}
	
	}

	echo $imagem;
	
}

?>