<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='homepage';
$menu_sub_sel='icones';

$tab_sel=1;
if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];

$id = 1;
$erro = 0;

$tamanho_imagens1 = getFillSize('Homepage', 'imagem2');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_homepage")) {
	$manter = $_POST['manter'];
	
	$query_rsP = "SELECT icone1, icone2, icone3 FROM homepage".$extensao." WHERE id=:id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();

	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$row_rsLinguas = $rsLinguas->fetchAll(PDO::FETCH_ASSOC);
	$totalRows_rsLinguas = $rsLinguas->rowCount();

	if($tab_sel == 1) {
		$insertSQL = "UPDATE homepage".$extensao." SET titulo1=:titulo1, subtitulo1=:subtitulo1 WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);	
		$rsInsert->bindParam(':titulo1', $_POST['titulo1'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':subtitulo1', $_POST['subtitulo1'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
		$rsInsert->execute();

		$rem = 0;
		$opcao = $_POST['opcao'];
		$imagem = $row_rsP['icone1'];

		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
			$array_imagens = array();
      $array_imagens['icone1'] = $imagem;
      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao);
		}

		$ins = 0;

		if($_FILES['img']['name']!='') { // actualiza imagem
			//Verificar o formato do ficheiro
			$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

			if($ext != "svg") {
				$erro = 1;
			}
			else {
				$ins = 1;	
				require("../resize_image.php");
				
				$imagem="";	
				
				$imgs_dir = "../../../imgs/homepage";
				$contaimg = 1; 
		
				foreach($_FILES as $file_name => $file_array) {
			
					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

					if($file_array['size'] > 0){
							$nome_img=verifica_nome($file_array['name']);
							$nome_file = $id_file."_".$nome_img;
							@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);
					}else {
							//$nome_file = $_POST['file_db_'.$contaimg];
		
						if($_POST['file_db_'.$contaimg])
							$nome_file = $_POST['file_db_'.$contaimg];
						else{
							$nome_file ='';
							@unlink($imgs_dir.'/'.$_POST['file_db_del_'.$contaimg]);
							}
		
					}
							
					if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$imgs_dir/$nome_file") or die ("Couldn't copy"); }
		
					//store the name plus index as a string 
					$variableName = 'nome_file' . $contaimg; 
					//the double dollar sign is saying assign $imageName 
					// to the variable that has the name that is in $variableName
					$$variableName = $nome_file; 	
					$contaimg++;
															
				} // fim foreach
				//Fim do Trat. Imagens
					
				//RESIZE DAS IMAGENS
				$imagem = $nome_file1;
			
				//IMAGEM 1
				if($_FILES['img']['name']!='') {
					if($imagem!="" && file_exists("../../../imgs/homepage/".$imagem)){
										
						$maxW=$tamanho_imagens1['0'];
						$maxH=$tamanho_imagens1['1'];
						
						$sizes=getimagesize("../../../imgs/homepage/".$imagem);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
							$img1=new Resize("../../../imgs/homepage/", $imagem, $imagem, $maxW, $maxH);
							$img1->resize_image();
						}
					}		
					
					if($row_rsP['icone1']) {
						//@unlink('../../../imgs/destaques/'.$row_rsP['imagem1']);
						$array_imagens = array();
			      $array_imagens['icone1'] = $row_rsP['icone1'];
			      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao);
					}
					
					//Inserir apenas na língua atual
					if($opcao == 1) {
						$insertSQL = "UPDATE homepage".$extensao." SET icone1=:icone1 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':icone1', $imagem, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
						$rsInsert->execute();
					}
					//Inserir para todas as línguas
					else if($opcao == 2) {
						foreach ($row_rsLinguas as $linguas) {		
							$insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone1=:icone1 WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':icone1', $imagem, PDO::PARAM_STR, 5);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
							$rsInsert->execute();
							
						}
					}
				}
			}
		}
		DB::close();

		alteraSessions('icons');
		
		header("Location: icones.php?alt=1&tab_sel=".$_POST['tab_sel']);			
	}

	if($tab_sel == 2) {
		$insertSQL = "UPDATE homepage".$extensao." SET titulo2=:titulo2, subtitulo2=:subtitulo2 WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);	
		$rsInsert->bindParam(':titulo2', $_POST['titulo2'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':subtitulo2', $_POST['subtitulo2'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
		$rsInsert->execute();

		$rem = 0;
		$opcao2 = $_POST['opcao2'];
		$imagem2 = $row_rsP['icone2'];

		if(isset($_POST['img_remover2']) && $_POST['img_remover2']==1) {
			$array_imagens = array();
      $array_imagens['icone2'] = $imagem2;
      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao2);
		}

		$ins = 0;

		if($_FILES['img2']['name']!='') { // actualiza imagem
			//Verificar o formato do ficheiro
			$ext = strtolower(pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION));

			if($ext != "svg") {
				$erro = 1;
			}
			else {
				$ins = 1;	
				require("../resize_image.php");
				
				$imagem="";	
				
				$imgs_dir = "../../../imgs/homepage";
				$contaimg = 1; 
		
				foreach($_FILES as $file_name => $file_array) {
			
					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

					if($file_array['size'] > 0){
							$nome_img=verifica_nome($file_array['name']);
							$nome_file = $id_file."_".$nome_img;
							@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);
					}else {
							//$nome_file = $_POST['file_db_'.$contaimg];
		
						if($_POST['file_db_'.$contaimg])
							$nome_file = $_POST['file_db_'.$contaimg];
						else{
							$nome_file ='';
							@unlink($imgs_dir.'/'.$_POST['file_db_del_'.$contaimg]);
							}
		
					}
							
					if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$imgs_dir/$nome_file") or die ("Couldn't copy"); }
		
					//store the name plus index as a string 
					$variableName = 'nome_file' . $contaimg; 
					//the double dollar sign is saying assign $imageName 
					// to the variable that has the name that is in $variableName
					$$variableName = $nome_file; 	
					$contaimg++;
															
				} // fim foreach
				//Fim do Trat. Imagens
					
				//RESIZE DAS IMAGENS
				$imagem2 = $nome_file2;


			
				//IMAGEM 1
				if($_FILES['img2']['name']!='') {
					if($imagem2!="" && file_exists("../../../imgs/homepage/".$imagem2)){										
						$maxW=$tamanho_imagens1['0'];
						$maxH=$tamanho_imagens1['1'];
						
						$sizes=getimagesize("../../../imgs/homepage/".$imagem2);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
							$img1=new Resize("../../../imgs/homepage/", $imagem2, $imagem2, $maxW, $maxH);
							$img1->resize_image();
						}
					}		
					
					if($row_rsP['icone2']) {
						//@unlink('../../../imgs/destaques/'.$row_rsP['imagem1']);
						$array_imagens = array();
			      $array_imagens['icone2'] = $row_rsP['icone2'];
			      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao2);
					}
					
					//Inserir apenas na língua atual
					if($opcao2 == 1) {
						$insertSQL = "UPDATE homepage".$extensao." SET icone2=:icone2 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':icone2', $imagem2, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
						$rsInsert->execute();
					}
					//Inserir para todas as línguas
					else if($opcao2 == 2) {
						foreach ($row_rsLinguas as $linguas) {		
							$insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone2=:icone2 WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':icone2', $imagem2, PDO::PARAM_STR, 5);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
							$rsInsert->execute();
							
						}
					}
				}
			}
		}

		DB::close();

		alteraSessions('icons');
		
		header("Location: icones.php?alt=1&tab_sel=".$_POST['tab_sel']);			
	}

	if($tab_sel == 3) {
		$insertSQL = "UPDATE homepage".$extensao." SET titulo3=:titulo3, subtitulo3=:subtitulo3 WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);	
		$rsInsert->bindParam(':titulo3', $_POST['titulo3'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':subtitulo3', $_POST['subtitulo3'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
		$rsInsert->execute();

		$rem = 0;
		$opcao3 = $_POST['opcao3'];
		$imagem3 = $row_rsP['icone3'];

		if(isset($_POST['img_remover3']) && $_POST['img_remover3']==1) {
			$array_imagens = array();
      $array_imagens['icone3'] = $imagem3;
      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao3);
		}

		$ins = 0;

		if($_FILES['img3']['name']!='') { // actualiza imagem
			//Verificar o formato do ficheiro
			$ext = strtolower(pathinfo($_FILES['img3']['name'], PATHINFO_EXTENSION));

			if($ext != "svg") {
				$erro = 1;
			}
			else {
				$ins = 1;	
				require("../resize_image.php");
				
				$imagem="";	
				
				$imgs_dir = "../../../imgs/homepage";
				$contaimg = 1; 
		
				foreach($_FILES as $file_name => $file_array) {
			
					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

					if($file_array['size'] > 0){
							$nome_img=verifica_nome($file_array['name']);
							$nome_file = $id_file."_".$nome_img;
							@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);
					}else {
							//$nome_file = $_POST['file_db_'.$contaimg];
		
						if($_POST['file_db_'.$contaimg])
							$nome_file = $_POST['file_db_'.$contaimg];
						else{
							$nome_file ='';
							@unlink($imgs_dir.'/'.$_POST['file_db_del_'.$contaimg]);
							}
		
					}
							
					if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$imgs_dir/$nome_file") or die ("Couldn't copy"); }
		
					//store the name plus index as a string 
					$variableName = 'nome_file' . $contaimg; 
					//the double dollar sign is saying assign $imageName 
					// to the variable that has the name that is in $variableName
					$$variableName = $nome_file; 	
					$contaimg++;
															
				} // fim foreach
				//Fim do Trat. Imagens
					
				//RESIZE DAS IMAGENS
				$imagem3 = $nome_file3;
			
				//IMAGEM 1
				if($_FILES['img3']['name']!='') {
					if($imagem3!="" && file_exists("../../../imgs/homepage/".$imagem3)){
										
						$maxW=$tamanho_imagens1['0'];
						$maxH=$tamanho_imagens1['1'];
						
						$sizes=getimagesize("../../../imgs/homepage/".$imagem3);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
							$img1=new Resize("../../../imgs/homepage/", $imagem3, $imagem3, $maxW, $maxH);
							$img1->resize_image();
						}
					}		
					
					if($row_rsP['icone3']) {
						//@unlink('../../../imgs/destaques/'.$row_rsP['imagem1']);
						$array_imagens = array();
			      $array_imagens['icone3'] = $row_rsP['icone3'];
			      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao2);
					}
					
					//Inserir apenas na língua atual
					if($opcao3 == 1) {
						$insertSQL = "UPDATE homepage".$extensao." SET icone3=:icone3 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':icone3', $imagem3, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
						$rsInsert->execute();
					}
					//Inserir para todas as línguas
					else if($opcao3 == 2) {
						foreach ($row_rsLinguas as $linguas) {		
							$insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone3=:icone3 WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':icone3', $imagem3, PDO::PARAM_STR, 5);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
							$rsInsert->execute();
							
						}
					}
				}
			}
		}

		DB::close();

		alteraSessions('icons');
		
		header("Location: icones.php?alt=1&tab_sel=".$_POST['tab_sel']);			
	}

	if($tab_sel == 4) {
		$insertSQL = "UPDATE homepage".$extensao." SET titulo4=:titulo4, subtitulo4=:subtitulo4 WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);	
		$rsInsert->bindParam(':titulo4', $_POST['titulo4'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':subtitulo4', $_POST['subtitulo4'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
		$rsInsert->execute();

		$rem = 0;
		$opcao4 = $_POST['opcao4'];
		$imagem4 = $row_rsP['icone4'];

		if(isset($_POST['img_remover4']) && $_POST['img_remover4']==1) {
			$array_imagens = array();
      $array_imagens['icone4'] = $imagem4;
      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao4);
		}

		$ins = 0;

		if($_FILES['img4']['name']!='') { // actualiza imagem
			//Verificar o formato do ficheiro
			$ext = strtolower(pathinfo($_FILES['img4']['name'], PATHINFO_EXTENSION));

			if($ext != "svg") {
				$erro = 1;
			}
			else {
				$ins = 1;	
				require("../resize_image.php");
				
				$imagem="";	
				
				$imgs_dir = "../../../imgs/homepage";
				$contaimg = 1; 
		
				foreach($_FILES as $file_name => $file_array) {
			
					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

					if($file_array['size'] > 0){
							$nome_img=verifica_nome($file_array['name']);
							$nome_file = $id_file."_".$nome_img;
							@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);
					}else {
							//$nome_file = $_POST['file_db_'.$contaimg];
		
						if($_POST['file_db_'.$contaimg])
							$nome_file = $_POST['file_db_'.$contaimg];
						else{
							$nome_file ='';
							@unlink($imgs_dir.'/'.$_POST['file_db_del_'.$contaimg]);
							}
		
					}
							
					if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$imgs_dir/$nome_file") or die ("Couldn't copy"); }
		
					//store the name plus index as a string 
					$variableName = 'nome_file' . $contaimg; 
					//the double dollar sign is saying assign $imageName 
					// to the variable that has the name that is in $variableName
					$$variableName = $nome_file; 	
					$contaimg++;
															
				} // fim foreach
				//Fim do Trat. Imagens
					
				//RESIZE DAS IMAGENS
				$imagem4 = $nome_file4;
			
				//IMAGEM 1
				if($_FILES['img4']['name']!='') {
					if($imagem4!="" && file_exists("../../../imgs/homepage/".$imagem4)){
										
						$maxW=$tamanho_imagens1['0'];
						$maxH=$tamanho_imagens1['1'];
						
						$sizes=getimagesize("../../../imgs/homepage/".$imagem4);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
							$img1=new Resize("../../../imgs/homepage/", $imagem4, $imagem4, $maxW, $maxH);
							$img1->resize_image();
						}
					}		
					
					if($row_rsP['icone4']) {
						//@unlink('../../../imgs/destaques/'.$row_rsP['imagem1']);
						$array_imagens = array();
			      $array_imagens['icone4'] = $row_rsP['icone4'];
			      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao2);
					}
					
					//Inserir apenas na língua atual
					if($opcao4 == 1) {
						$insertSQL = "UPDATE homepage".$extensao." SET icone4=:icone4 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':icone4', $imagem4, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
						$rsInsert->execute();
					}
					//Inserir para todas as línguas
					else if($opcao4 == 2) {
						foreach ($row_rsLinguas as $linguas) {		
							$insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone4=:icone4 WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':icone4', $imagem4, PDO::PARAM_STR, 5);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
							$rsInsert->execute();
							
						}
					}
				}
			}
		}

		DB::close();

		alteraSessions('icons');
		
		header("Location: icones.php?alt=1&tab_sel=".$_POST['tab_sel']);			
	}
}

$query_rsP = "SELECT * FROM homepage".$extensao." WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jcrop/css/jquery.Jcrop.css" rel="stylesheet"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix"> </div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content"> 
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_icones']; ?> <small><?php echo $RecursosCons->RecursosCons['menu_homepage']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_homepage']; ?> </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_icones']; ?> </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="icones.php"><?php echo $RecursosCons->RecursosCons['editar_conteudo']; ?> </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_homepage" name="frm_homepage" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <input type="hidden" name="img_remover2" id="img_remover2" value="0">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_icones']; ?> </div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
	              </div>
	            </div>
	            <div class="portlet-body">
	              <div class="tabbable">
	                <ul class="nav nav-tabs">
	                  <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_icone1" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?> 1 </a> </li>
	                  <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_icone2" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?> 2 </a> </li>
	                  <li <?php if($tab_sel==3) echo "class=\"active\""; ?>> <a href="#tab_icone3" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?> 3 </a> </li>
										<li <?php if($tab_sel==4) echo "class=\"active\""; ?>> <a href="#tab_icone4" data-toggle="tab" onClick="document.getElementById('tab_sel').value='4'"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?> 4 </a> </li>
	                </ul>
	                <div class="tab-content no-space">
	                  <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_icone1">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?>
                        </div>
                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                        	<?php echo $RecursosCons->RecursosCons['alt']; ?> 
	                       	</div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo1" id="titulo1" value="<?php echo $row_rsP['titulo1']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo1"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="subtitulo1" id="subtitulo1" value="<?php echo $row_rsP['subtitulo1']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?><br>
	                          <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
	                        <div class="col-md-4">
	                          <div class="fileinput fileinput-<?php if($row_rsP['icone1']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
	                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
	                            <div class="fileinput-preview fileinput-exists thumbnail" <?php if(strtolower(pathinfo($row_rsP['icone1'], PATHINFO_EXTENSION))=='svg'){ ?> style="width: 150px; height: 100px;" <?php } ?>>
	                              <?php if($row_rsP['icone1']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone1'])) { ?>
	                              <a href="../../../imgs/homepage/<?php echo $row_rsP['icone1']; ?>" data-fancybox ><img src="../../../imgs/homepage/<?php echo $row_rsP['icone1']; ?>"></a>
	                              <?php } ?>
	                            </div>
	                            <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
	                              <input id="upload_campo" type="file" name="img" accept=".svg">
	                              </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
	                          </div>
	                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt5']; ?></span></div>
	                          <script type="text/javascript">
	                          function alterar_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                          }
	                          function remover_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                              document.getElementById('img_cont_1_vazia').style.display='block';									
	                              document.getElementById('img_cont_1').style.display='none';
	                          }
	                          </script><br><br>
	                        </div>
	                        <label class="col-md-2 control-label" for="opcao"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>
                          <div class="col-md-4">
                          	<div style="margin-top: 8px" class="md-radio-list">
															<div class="md-radio">
																<input type="radio" id="opcao1" name="opcao" value="1" class="md-radiobtn" checked>
																<label for="opcao1">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?> </label>
															</div>
															<div class="md-radio">
																<input type="radio" id="opcao2" name="opcao" value="2" class="md-radiobtn">
																<label for="opcao2">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>
															</div>
														</div>
                          </div>
	                      </div>
                      </div>
                  	</div>
                  	<div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_icone2">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?>
                        </div>
                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                        	<?php echo $RecursosCons->RecursosCons['alt']; ?> 
	                       	</div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo2"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo2" id="titulo2" value="<?php echo $row_rsP['titulo2']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo2"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="subtitulo2" id="subtitulo2" value="<?php echo $row_rsP['subtitulo2']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?><br>
	                          <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
	                        <div class="col-md-4">
	                          <div class="fileinput fileinput-<?php if($row_rsP['icone2']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
	                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
	                            <div class="fileinput-preview fileinput-exists thumbnail" <?php if(strtolower(pathinfo($row_rsP['icone2'], PATHINFO_EXTENSION))=='svg'){ ?> style="width: 150px; height: 100px;" <?php } ?>>
	                              <?php if($row_rsP['icone2']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone2'])) { ?>
	                              <a href="../../../imgs/homepage/<?php echo $row_rsP['icone2']; ?>" data-fancybox ><img src="../../../imgs/homepage/<?php echo $row_rsP['icone2']; ?>"></a>
	                              <?php } ?>
	                            </div>
	                            <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
	                              <input id="upload_campo" type="file" name="img2" accept=".svg">
	                              </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
	                          </div>
	                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt5']; ?></span></div>
	                          <script type="text/javascript">
	                          function alterar_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                          }
	                          function remover_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                              document.getElementById('img_cont_1_vazia').style.display='block';									
	                              document.getElementById('img_cont_1').style.display='none';
	                          }
	                          </script><br><br>
	                        </div>
	                        <label class="col-md-2 control-label" for="opcao2"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>
                          <div class="col-md-4">
                          	<div style="margin-top: 8px" class="md-radio-list">
															<div class="md-radio">
																<input type="radio" id="opcao21" name="opcao2" value="1" class="md-radiobtn" checked>
																<label for="opcao21">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?> </label>
															</div>
															<div class="md-radio">
																<input type="radio" id="opcao22" name="opcao2" value="2" class="md-radiobtn">
																<label for="opcao22">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>
															</div>
														</div>
                          </div>
	                      </div>
                      </div>
                  	</div>
                  	<div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_icone3">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?>
                        </div>
                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 3) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                        	<?php echo $RecursosCons->RecursosCons['alt']; ?> 
	                       	</div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo3"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo3" id="titulo3" value="<?php echo $row_rsP['titulo3']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo3"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="subtitulo3" id="subtitulo3" value="<?php echo $row_rsP['subtitulo3']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?><br>
	                          <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
	                        <div class="col-md-4">
	                          <div class="fileinput fileinput-<?php if($row_rsP['icone3']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone3'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
	                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
	                            <div class="fileinput-preview fileinput-exists thumbnail" <?php if(strtolower(pathinfo($row_rsP['icone3'], PATHINFO_EXTENSION))=='svg'){ ?> style="width: 150px; height: 100px;" <?php } ?>>
	                              <?php if($row_rsP['icone3']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone3'])) { ?>
	                              <a href="../../../imgs/homepage/<?php echo $row_rsP['icone3']; ?>" data-fancybox ><img src="../../../imgs/homepage/<?php echo $row_rsP['icone3']; ?>"></a>
	                              <?php } ?>
	                            </div>
	                            <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
	                              <input id="upload_campo" type="file" name="img3" accept=".svg">
	                              </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover3').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
	                          </div>
	                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt5']; ?></span></div>
	                          <script type="text/javascript">
	                          function alterar_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                          }
	                          function remover_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                              document.getElementById('img_cont_1_vazia').style.display='block';									
	                              document.getElementById('img_cont_1').style.display='none';
	                          }
	                          </script><br><br>
	                        </div>
	                        <label class="col-md-2 control-label" for="opcao3"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>
                          <div class="col-md-4">
                          	<div style="margin-top: 8px" class="md-radio-list">
															<div class="md-radio">
																<input type="radio" id="opcao31" name="opcao3" value="1" class="md-radiobtn" checked>
																<label for="opcao31">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?> </label>
															</div>
															<div class="md-radio">
																<input type="radio" id="opcao32" name="opcao3" value="2" class="md-radiobtn">
																<label for="opcao32">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>
															</div>
														</div>
                          </div>
	                      </div>
                      </div>
                  	</div>
										<div class="tab-pane <?php if($tab_sel==4) echo "active"; ?>" id="tab_icone4">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?>
                        </div>
                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 4) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                        	<?php echo $RecursosCons->RecursosCons['alt']; ?> 
	                       	</div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo4"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo4" id="titulo4" value="<?php echo $row_rsP['titulo4']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo4"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="subtitulo4" id="subtitulo4" value="<?php echo $row_rsP['subtitulo4']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['tab_icone']; ?><br>
	                          <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
	                        <div class="col-md-4">
	                          <div class="fileinput fileinput-<?php if($row_rsP['icone4']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone4'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
	                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
															<div class="fileinput-preview fileinput-exists thumbnail" <?php if(strtolower(pathinfo($row_rsP['icone4'], PATHINFO_EXTENSION))=='svg'){ ?> style="width: 150px; height: 100px;" <?php } ?>>
	                              <?php if($row_rsP['icone4']!="" && file_exists("../../../imgs/homepage/".$row_rsP['icone4'])) { ?>
	                              <a href="../../../imgs/homepage/<?php echo $row_rsP['icone4']; ?>" data-fancybox ><img src="../../../imgs/homepage/<?php echo $row_rsP['icone4']; ?>"></a>
	                              <?php } ?>
	                            </div>
	                            <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
	                              <input id="upload_campo" type="file" name="img4" accept=".svg">
	                              </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover4').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
	                          </div>
	                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt5']; ?></span></div>
	                          <script type="text/javascript">
	                          function alterar_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                          }
	                          function remover_imagem(){
	                              document.getElementById('file_delete_1').value='';
	                              document.getElementById('img_cont_1_vazia').style.display='block';									
	                              document.getElementById('img_cont_1').style.display='none';
	                          }
	                          </script><br><br>
	                        </div>
	                        <label class="col-md-2 control-label" for="opcao4"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>
                          <div class="col-md-4">
                          	<div style="margin-top: 8px" class="md-radio-list">
															<div class="md-radio">
																<input type="radio" id="opcao41" name="opcao4" value="1" class="md-radiobtn" checked>
																<label for="opcao41">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?> </label>
															</div>
															<div class="md-radio">
																<input type="radio" id="opcao42" name="opcao4" value="2" class="md-radiobtn">
																<label for="opcao42">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>
															</div>
														</div>
                          </div>
	                      </div>
                      </div>
                  	</div>
	                </div>
	              </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_homepage" />
          </form>
        </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<!-- LINGUA PORTUGUESA --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/dropzone.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jcrop/js/jquery.color.js"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>