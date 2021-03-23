<?php include_once('../inc_pages.php'); ?>

<?php ini_set('display_errors', 1);



$menu_sel='banners';

$menu_sub_sel='banners_h';

$tab_sel=1;



if(isset($_GET['tab_sel']) && $_GET['tab_sel'] != "" && $_GET['tab_sel'] != 0) $tab_sel=$_GET['tab_sel'];

elseif(isset($_POST['tab_sel']) && $_POST['tab_sel'] != "" && $_POST['tab_sel'] != 0) $tab_sel=$_POST['tab_sel'];



$id = $_GET['id'];

$erro = 0;



$tamanho_imagens1 = getFillSize('Banners', 'imagem1');

$tamanho_imagens2 = getFillSize('Banners', 'imagem2');

$maxUpload = (int)(ini_get('upload_max_filesize'));



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_banners_h")) {



	





	$manter = $_POST['manter'];



	$tab_sel = $_REQUEST['tab_sel'];

	

	$query_rsP = "SELECT imagem1, imagem2, imagem3, d_imagem_full, m_imagem_full FROM banners_h".$extensao." WHERE id = :id";

	$rsP = DB::getInstance()->prepare($query_rsP);

	$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

	$rsP->execute();

	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsP = $rsP->rowCount();

	if($_FILES['d_imagem_full']['name']){

	//full image desktop

	$imagename = $_FILES['d_imagem_full']['name'];

	$imagetmp  = $_FILES['d_imagem_full']['tmp_name'];



	 move_uploaded_file($imagetmp , "../../../imgs/banners/$imagename");



	$insertSQL = "UPDATE banners_h".$extensao." SET d_imagem_full=:d_imagem_full WHERE id=:id";

	$rsInsert = DB::getInstance()->prepare($insertSQL);	

	$rsInsert->bindParam(':d_imagem_full', $imagename , PDO::PARAM_STR, 5);	

	$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

	$rsInsert->execute();

	}



	if($_FILES['m_imagem_full']['name']){

	//full image mobile

	$imagemobilename = $_FILES['m_imagem_full']['name'];

	$imagemobiletmp  = $_FILES['m_imagem_full']['tmp_name'];



	 move_uploaded_file($imagemobiletmp , "../../../imgs/banners/$imagemobilename");



	$insertSQL = "UPDATE banners_h".$extensao." SET m_imagem_full=:m_imagem_full WHERE id=:id";

		$rsInsert = DB::getInstance()->prepare($insertSQL);	

		$rsInsert->bindParam(':m_imagem_full', $imagemobilename , PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

		$rsInsert->execute();

	}



	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

  $rsLinguas->execute();

  $row_rsLinguas = $rsLinguas->fetchAll(PDO::FETCH_ASSOC);

  $totalRows_rsLinguas = $rsLinguas->rowCount();

	



	if($_POST['nome']!='' && $tab_sel == 1) {			

		// actualiza detalhes

		$insertSQL = "UPDATE banners_h".$extensao." SET nome=:nome, titulo=:titulo, subtitulo=:subtitulo, m_d_radio=:m_d_radio, link=:link, texto_link=:texto_link, video=:video WHERE id=:id";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':subtitulo', $_POST['subtitulo'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':m_d_radio', $_POST['m_d_radio'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':video', $_POST['video'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

		$rsInsert->execute();

		

		$datai = NULL;

		if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];

		$dataf = NULL;

		if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];

		

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE banners_h_".$linguas["sufixo"]." SET tipo=:tipo, datai=:datai, dataf=:dataf, target=:target, link_class=:link_class, text_alignh=:text_alignh, text_alignv=:text_alignv, bg_color=:bg_color, slide_duration=:slide_duration WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT);	

			$rsInsert->bindParam(':link_class', $_POST['link_class'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':text_alignv', $_POST['text_alignv'], PDO::PARAM_STR, 5); 

      		$rsInsert->bindParam(':text_alignh', $_POST['text_alignh'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':slide_duration', $_POST['slide_duration'], PDO::PARAM_INT);

			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

			$rsInsert->bindParam(':bg_color', $_POST['bg_color'], PDO::PARAM_STR, 5);

			$rsInsert->execute();

		}



		alteraSessions('banners');



		DB::close();



		if(!$manter) 

			header("Location: slideshow.php?alt=1");

		else 

			header("Location: slideshow-edit.php?id=".$id."&alt=1&tab_sel=1");

	}

	

	if($tab_sel==2) {



    $mascara1 = 0;

    if(isset($_POST['mascara1'])) {

      $mascara1 = 1;

    }

		

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE banners_h_".$linguas["sufixo"]." SET cor1=:cor1, mascara1=:mascara1, align_h1=:align_h1, align_v1=:align_v1 WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':cor1', $_POST['cor1'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':mascara1', $mascara1, PDO::PARAM_INT);	

			$rsInsert->bindParam(':align_h1', $_POST['align_h1'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':align_v1', $_POST['align_v1'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

			$rsInsert->execute();

		}



		$opcao = $_POST['opcao'];

		$imagem = $row_rsP['imagem1'];

		$imagem3 = $row_rsP['imagem3'];



		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {

			$array_imagens = array();

      $array_imagens['imagem1'] = $imagem;

      $array_imagens['imagem3'] = $imagem3;

      apagaFicheiros('banners_h', 'banners', $id, $extensao, $array_imagens, $opcao);



		}



		if($_FILES['img']['name']!='') { // actualiza imagem

			//Verificar o formato do ficheiro

			$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));



			if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png" && $ext != "mp4") {

				$erro = 1;

			}

			else if($_FILES['img']['size']>($maxUpload*1000000)){

				$erro=2;

			} 

			else {

				$ins = 1;	

				require("../resize_image.php");

				

				$imagem="";		

				

				$imgs_dir = "../../../imgs/banners";

				$contaimg = 1; 

		

				foreach($_FILES as $file_name => $file_array) {

			

					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);		

		

					if($file_array['size'] > 0){

							$nome_img=verifica_nome($file_array['name']);

							$nome_file = $id_file."_".$nome_img;

							@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);

					}else {

						if($_POST['file_db_'.$contaimg])

							$nome_file = $_POST['file_db_'.$contaimg];

						else{

							$nome_file ='';

							@unlink($imgs_dir.'/'.$_POST['file_db_del_'.$contaimg]);

						}

					}

					

					//echo $file_array['tmp_name'],"$imgs_dir/$nome_file";

							

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

					if($imagem!="" && file_exists("../../../imgs/banners/".$imagem) && $ext != 'mp4' && $ext != 'gif'){

										

						$maxW=$tamanho_imagens1['0'];

						$maxH=$tamanho_imagens1['1'];

						

						$sizes=getimagesize("../../../imgs/banners/".$imagem);

						

						$imageW=$sizes[0];

						$imageH=$sizes[1];

						

						if($imageW>$maxW || $imageH>$maxH){

							$img1=new Resize("../../../imgs/banners/", $imagem, $imagem, $maxW, $maxH);

							$img1->resize_image();

						}

						

						$imagem3 = 'pq_'.$imagem;

						$img3=new Thumb("../../../imgs/banners/", $imagem, $imagem3, 150, 150);

						$img3->thumb_image();

					}		

					

					if($row_rsP['imagem1']){

						$array_imagens = array();

			      $array_imagens['imagem1'] = $row_rsP['imagem1'];

			      $array_imagens['imagem3'] = $row_rsP['imagem3'];

			      apagaFicheiros('banners_h', 'banners', $id, $extensao, $array_imagens, $opcao);

					}



					if($ext != 'mp4' && $ext != 'gif') {

						compressImage('../../../imgs/banners/'.$imagem, '../../../imgs/banners/'.$imagem, 80);

						compressImage('../../../imgs/banners/'.$imagem3, '../../../imgs/banners/'.$imagem3, 80);

					}



					//se video carregado

					$video='0';

					if($ext=='mp4')

						$video = '1';



					//Inserir apenas na língua atual

					if($opcao == 1) {

						$insertSQL = "UPDATE banners_h".$extensao." SET imagem1=:imagem1, imagem3=:imagem3, video=:video WHERE id=:id";

						$rsInsert = DB::getInstance()->prepare($insertSQL);

						$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);

						$rsInsert->bindParam(':imagem3', $imagem3, PDO::PARAM_STR, 5);

						$rsInsert->bindParam(':video', $video, PDO::PARAM_STR, 5);

						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		

						$rsInsert->execute();

					}

					//Inserir para todas as línguas

					else if($opcao == 2) {

						foreach($row_rsLinguas as $linguas) {		

							$insertSQL = "UPDATE banners_h_".$linguas["sufixo"]." SET imagem1=:imagem1, imagem3=:imagem3, video=:video WHERE id=:id";

							$rsInsert = DB::getInstance()->prepare($insertSQL);

							$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);

							$rsInsert->bindParam(':imagem3', $imagem3, PDO::PARAM_STR, 5);

							$rsInsert->bindParam(':video', $video, PDO::PARAM_STR, 5);

							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		

							$rsInsert->execute();

						}

					}

				}

			}

		}





		DB::close();



		alteraSessions('banners');

		

		if($erro == 1)

			header("Location: slideshow-edit.php?id=".$id."&erro=1&tab_sel=2");

		else {

			if(!$manter) 

				header("Location: slideshow.php?alt=1");

			else 

				header("Location: slideshow-edit.php?id=".$id."&alt=1&tab_sel=2");

		}

	}



	if($tab_sel==3) {



    $mascara2 = 0;

    if(isset($_POST['mascara2'])) {

      $mascara2 = 1;

    }

    

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE banners_h_".$linguas["sufixo"]." SET cor2=:cor2, mascara2=:mascara2, align_h2=:align_h2, align_v2=:align_v2 WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':cor2', $_POST['cor2'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':mascara2', $mascara2, PDO::PARAM_INT);	

			$rsInsert->bindParam(':align_h2', $_POST['align_h2'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':align_v2', $_POST['align_v2'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

			$rsInsert->execute();

		}



		$opcao = $_POST['opcao2'];

		$imagem2 = $row_rsP['imagem2'];

		

		if(isset($_POST['img_remover2']) && $_POST['img_remover2']==1) {

			$array_imagens = array();

      $array_imagens['imagem2'] = $imagem2;

      apagaFicheiros('banners_h', 'banners', $id, $extensao, $array_imagens, $opcao);

		}



		if($_FILES['img2']['name']!='') { // actualiza imagem

			//Verificar o formato do ficheiro

			$ext2 = strtolower(pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION));



			if($ext2 != "jpg" && $ext2 != "jpeg" && $ext2 != "gif" && $ext2 != "png") {

				$erro = 1;

			}

			else {

				$ins = 1;	

				require("../resize_image.php");



				$imagem2="";		

				

				$imgs_dir = "../../../imgs/banners";

				$contaimg = 1; 

		

				foreach($_FILES as $file_name => $file_array) {

			

					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

		

					if($file_array['size'] > 0){

							$nome_img=verifica_nome($file_array['name']);

							$nome_file = $id_file."_".$nome_img;

							@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);

					}else {

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

			

				//IMAGEM 2

				if($_FILES['img2']['name']!='') {

					if($imagem2!="" && file_exists("../../../imgs/banners/".$imagem2) && $ext2 != 'mp4'){

						

						$maxW=$tamanho_imagens2['0'];

						$maxH=$tamanho_imagens2['1'];

						

						$sizes=getimagesize("../../../imgs/banners/".$imagem2);

						

						$imageW=$sizes[0];

						$imageH=$sizes[1];

						

						if($imageW>$maxW || $imageH>$maxH){

							$img1=new Resize("../../../imgs/banners/", $imagem2, $imagem2, $maxW, $maxH);

							$img1->resize_image();

						}

					}		

					

					if($row_rsP['imagem2']){

						$array_imagens = array();

			      $array_imagens['imagem2'] = $row_rsP['imagem2'];

			      apagaFicheiros('banners_h', 'banners', $id, $extensao, $array_imagens, $opcao);

					}

					

					//compressImage('../../../imgs/banners/'.$imagem2, '../../../imgs/banners/'.$imagem2);



					if($opcao == 1) {

						$insertSQL = "UPDATE banners_h".$extensao." SET imagem2=:imagem2 WHERE id=:id";

						$rsInsert = DB::getInstance()->prepare($insertSQL);

						$rsInsert->bindParam(':imagem2', $imagem2, PDO::PARAM_STR, 5);

						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		

						$rsInsert->execute();

					}

					else if($opcao == 2) {

						foreach($row_rsLinguas as $linguas) {	

							$insertSQL = "UPDATE banners_h_".$linguas["sufixo"]." SET imagem2=:imagem2 WHERE id=:id";

							$rsInsert = DB::getInstance()->prepare($insertSQL);

							$rsInsert->bindParam(':imagem2', $imagem2, PDO::PARAM_STR, 5);

							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		

							$rsInsert->execute();

						}

					}

				}

			}

		}



		DB::close();

		

		alteraSessions('banners');

		

		if($erro == 1)

			header("Location: slideshow-edit.php?id=".$id."&erro=1&tab_sel=3");

		else {

			if(!$manter) 

				header("Location: slideshow.php?alt=1");

			else 

				header("Location: slideshow-edit.php?id=".$id."&alt=1&tab_sel=3");

		}

	}

}







$query_rsP = "SELECT * FROM banners_h".$extensao." WHERE id = :id";

$rsP = DB::getInstance()->prepare($query_rsP);

$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

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

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>

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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['banner_page_title']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="slideshow.php"><?php echo $RecursosCons->RecursosCons['banner_page_title']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT--> 

      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

          <div class="modal-content">

            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>

            </div>

            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>

            <div class="modal-footer">

              <button type="button" class="btn blue" onClick="document.location='slideshow.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>

              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>

            </div>

          </div>

          <!-- /.modal-content --> 

        </div>

        <!-- /.modal-dialog --> 

      </div>

      <!-- /.modal --> 

      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

      <div class="row">

        <div class="col-md-12">

          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>

          <form id="frm_banners_h" name="frm_banners_h" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="manter" id="manter" value="0">

            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">

            <input type="hidden" name="img_remover1" id="img_remover1" value="0">

            <input type="hidden" name="img_remover2" id="img_remover2" value="0">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['banner_page_title']; ?> - <?php echo $row_rsP["nome"]; ?> </div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='slideshow.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>

                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> 

                </div>

              </div>

              <div class="portlet-body">

                <div class="tabbable">

                  <ul class="nav nav-tabs">

                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>

                    <li class="div_imagem <?php if($tab_sel==2) echo 'active'; ?>"> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['desktop_label']; ?> </a> </li>

                    <li class="div_imagem <?php if($tab_sel==3) echo 'active'; ?>"> <a href="#tab_images2" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['mobile_label']; ?> </a> </li>

                  </ul>

                  <div class="tab-content no-space">

                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">

                      <div class="form-body">

                        <div class="alert alert-danger display-hide">

                        <button class="close" data-close="alert"></button>

                        <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>    

                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>

                        	<div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                          <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </div>

                        <?php } ?>

                        <?php if($_GET['ins'] == 1) { ?>

                        	<div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                          <?php echo $RecursosCons->RecursosCons['env_config']; ?></div>

                        <?php } ?>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: </label>

                          <div class="col-md-3">

                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                              <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $row_rsP['datai']; ?>" data-required="1">

                              <span class="input-group-btn">

                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                              </span> 

                            </div>

                          </div>

                          <label class="col-md-2 control-label" for="dataf"><?php echo $RecursosCons->RecursosCons['data_fim_label']; ?>: </label>

                          <div class="col-md-3">

                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                              <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo $row_rsP['dataf']; ?>" data-required="1">

                              <span class="input-group-btn">

                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                              </span> 

                            </div>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">

                          </div>

                        </div>

                        <div class="form-group">

			                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>: </label>

			                    <div class="col-md-3">

			                      <select class="form-control" name="tipo" id="tipo">

			                        <option value="1" <?php if($row_rsP['tipo'] == 1) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['tipo_banner_1']; ?></option>

			                        <option value="2" <?php if($row_rsP['tipo'] == 2) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['tipo_banner_2']; ?></option>

			                      </select>

			                    	<p class="help-block"><i><?php echo $RecursosCons->RecursosCons['tipo_banner_help']; ?></i></p>

			                    </div>

			                  </div>

			                  <div class="form-group div_imagem">

                          <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>">

                          </div>

                        </div>

                        

                        <!-- <div class="form-group">

		                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['slide_duration_label']; ?>: </label>

		                    <div class="col-md-3">

		                      <select class="form-control" name="slide_duration" id="slide_duration">

		                      	<option value="0">selecione a duração</option>

		                      <?php

		                          $i = 1; 

		                          while ($i <= 10) { ?>

		                            <option value="<?php echo $i*1000; ?>" <?php echo ($i*1000 == $row_rsP['slide_duration']) ? 'selected' : null; ?>><?php echo $i; ?> sec</option>

		                          <?php 

		                          $i++;

		                        } 

		                      ?>

		                      </select>

		                      <p class="help-block"><i><?php echo $RecursosCons->RecursosCons['slide_duration_help']; ?></i></p>

		                    </div>

		                </div> -->



                        <div class="form-group div_imagem">

			                    <label class="col-md-2 control-label" for="subtitulo"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>

			                    <div class="col-md-8">

			                      <input type="text" class="form-control" name="subtitulo" id="subtitulo" value="<?php echo $row_rsP['subtitulo']; ?>">

			                    </div>

			                  </div>

                        <div class="form-group div_imagem">

                          <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">

                          </div>

                        </div>

                        <div class="form-group div_imagem">

                          <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>

                          <div class="col-md-3">

                            <select class="form-control" name="target" id="target">

                              <option value="_blank" <?php if($row_rsP['target'] == "_blank") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>

                              <option value="_parent" <?php if($row_rsP['target'] == "_parent") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>

                            </select>

                          </div>

                          <label class="col-md-2 control-label" for="texto_link"><?php echo $RecursosCons->RecursosCons['texto_link']; ?>: </label>

			                    <div class="col-md-3">

			                      <input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $row_rsP['texto_link']; ?>">

			                      <p class="help-block"><i><?php echo $RecursosCons->RecursosCons['texto_link_help']; ?></i></p>

			                    </div>

                        </div>

                        <div class="form-group">

			           		<label class="col-md-2 control-label" for="target">Background Type:</label>

			           		<div class="col-md-3">

								<label for="banner_full">Banner Completo <input type="radio" id="banner_full" name="m_d_radio" value="1" <?php if ($row_rsP['m_d_radio'] == 1) { echo 'checked="checked"'; } ?> ></label> 

								<label for="banner_modal">Banner Modelo <input type="radio" id="banner_modal" name="m_d_radio" value="2" <?php if ($row_rsP['m_d_radio'] == 2) { echo 'checked="checked"'; } ?> ></label>

							</div>

						</div>

                        <div class="form-group div_imagem" id="otherAnswer">

			                  	<label class="col-md-2 control-label" for="bg_color"><?php echo $RecursosCons->RecursosCons['bg_cor_texto_label']; ?>:</label>

			                  	<div class="col-md-3">

				                    <select class="form-control" name="bg_color" id="bg_color">

	                        			<option value="#E5F8E9" <?php if($row_rsP['bg_color'] == "#E5F8E9") echo "selected"; ?>>Green</option>

	                        			<option value="#FAF5E4" <?php if($row_rsP['bg_color'] == "#FAF5E4") echo "selected"; ?>>Yellow</option>

	                        			<option value="#FCF0F0" <?php if($row_rsP['bg_color'] == "#FCF0F0") echo "selected"; ?>>Pink</option>

	                        		</select>

                        		</div>

			            </div>

			     

			           

                        <!-- <div class="form-group div_imagem">

			                    <label class="col-md-2 control-label" for="text_alignh"><?php echo $RecursosCons->RecursosCons['alinhar_texto_horizontal_label']; ?>: </label>

			                    <div class="col-md-3">

			                      <select class="form-control" name="text_alignh" id="text_alignh">

			                        <option value="left" <?php echo ($row_rsP['text_alignh']=="left")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_esquerda']; ?></option>

			                        <option value="center" <?php echo ($row_rsP['text_alignh']=="center")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>

			                        <option value="right" <?php echo ($row_rsP['text_alignh']=="right")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_direita']; ?></option>

			                      </select>

			                    </div>

			                    <label class="col-md-2 control-label" for="text_alignv"><?php echo $RecursosCons->RecursosCons['alinhar_texto_vertical_label']; ?>: </label>

			                    <div class="col-md-3">

			                      <select class="form-control" name="text_alignv" id="text_alignv">

			                        <option value="top" <?php echo ($row_rsP['text_alignv']=="top")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_topo']; ?></option>

			                        <option value="middle" <?php echo ($row_rsP['text_alignv']=="middle")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_meio']; ?></option>

			                        <option value="bottom" <?php echo ($row_rsP['text_alignv']=="bottom")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_baixo']; ?></option>

			                      </select>

			                    </div>

				                </div> -->

                        <div class="form-group div_video">

			                    <label class="col-md-2 control-label" for="video"><?php echo $RecursosCons->RecursosCons['video_label']; ?>: </label>

			                    <div class="col-md-8">

			                      <textarea class="form-control" name="video" id="video" rows="2"><?php echo $row_rsP['video']; ?></textarea>

			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['help_block_video']; ?></p>

			                    </div>

			                  </div>

                      </div>

                    </div>

                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">

                      <div class="form-body">

                      	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>

                        <div class="alert alert-success display-show">

                          <button class="close" data-close="alert"></button>

                           <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </div>

                        <?php } ?>

                        <?php if($_GET['erro'] == 1 && $_GET['tab_sel'] == 2) { ?>

			                    <div class="alert alert-danger display-show">

			                    <button class="close" data-close="alert"></button>

			                     <?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> </div>   

			                	<?php } ?> 

                        	<div class="form-group">

			                    <label class="col-md-2 control-label" for="mascara1" style="padding-top:0;"> <?php echo $RecursosCons->RecursosCons['tem_mascara_label']; ?></label>

			                    <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="mascara1" id="mascara1" value="1" <?php if($row_rsP['mascara1'] == 1) echo "checked";?>>

			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['sel_mascara_msg']; ?></p>

			                    </div>

			                    <label class="col-md-2 control-label" for="cor1"><?php echo $RecursosCons->RecursosCons['cor_texto_label']; ?>:</label>

			                    <div class="col-md-3">

			                      <input type="text" class="form-control color" name="cor1" id="cor1" value="<?php echo $row_rsP['cor1']; ?>">

			                    </div>

			                    <i class="icon-trash" onclick="document.getElementById('cor1').value=''; document.getElementById('cor1').style.backgroundColor='#FFFFFF'" style="cursor:pointer; margin-top: 8px"></i>

			                  </div>





			                  <div class="form-group">

                        	<label class="col-md-2 control-label" for="align_h1"><?php echo $RecursosCons->RecursosCons['alinha_horizontal_label']; ?>: </label>

                        	<div class="col-md-3">

                        		<select class="form-control" name="align_h1" id="align_h1">

                        			<option value="left" <?php if($row_rsP['align_h1'] == "left") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_esquerda']; ?></option>

                        			<option value="center" <?php if($row_rsP['align_h1'] == "center") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>

                        			<option value="right" <?php if($row_rsP['align_h1'] == "right") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_direita']; ?></option>

                        		</select>

                        		<p class="help-block"><?php echo $RecursosCons->RecursosCons['info_img_horizontal']; ?></p>

                        	</div>

                        	<label class="col-md-2 control-label" for="align_v1"><?php echo $RecursosCons->RecursosCons['alinha_vertical_label']; ?>: </label>

                        	<div class="col-md-3">

                        		<select class="form-control" name="align_v1" id="align_v1">

                        			<option value="top" <?php if($row_rsP['align_v1'] == "top") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_topo']; ?></option>

                        			<option value="center" <?php if($row_rsP['align_v1'] == "center") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>

                        			<option value="bottom" <?php if($row_rsP['align_v1'] == "bottom") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_baixo']; ?></option>

                        		</select>

                        		<p class="help-block"><?php echo $RecursosCons->RecursosCons['info_img_vertical']; ?></p>

                        	</div>

                        </div>

                        <hr>

                    

                        <div class="form-group" id="destopimg">

                          <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem_video_label']; ?><br>

                            <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?> / <?php echo $RecursosCons->RecursosCons['tamanho_maximo_video'].$maxUpload."Mb"; ?>:</strong><br/>

                            

                             </label>

                          <div class="col-md-4">

                          	<?php $ext = strtolower(pathinfo($row_rsP['imagem1'], PATHINFO_EXTENSION)); ?>

                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/banners/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>

                              	<?php if($ext != "wmv" && $ext != "mp4"){ ?>

	                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

	                                <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/banners/".$row_rsP['imagem1'])) { ?>

	                                <a href="../../../imgs/banners/<?php echo $row_rsP['imagem1']; ?>" data-fancybox ><img src="../../../imgs/banners/<?php echo $row_rsP['imagem1']; ?>"></a>

	                                <?php } ?>

	                              </div>

	                        	<?php }else{ ?>

	                        		<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

																<a data-fancybox="gallery" href="../../../imgs/banners/<?php echo $row_rsP['imagem1']; ?>" class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"><?php echo $RecursosCons->RecursosCons['ver_video']; ?></span></a>

															</div>

                          		<?php } ?>

                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

                                <input id="upload_campo" type="file" name="img">

                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>

                            </div>

                            <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt3']; ?></span> </div>

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



                        





                        	   <div class="form-group" id="destopimgfull">

                          <label class="col-md-2 control-label" style="text-align:right">fullbanner<br>

                            <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?> / <?php echo $RecursosCons->RecursosCons['tamanho_maximo_video'].$maxUpload."Mb"; ?>:</strong><br/>

                            

                             </label>

                          <div class="col-md-4">

                          	<?php $ext = strtolower(pathinfo($row_rsP['d_imagem_full'], PATHINFO_EXTENSION)); ?>

                            <div class="fileinput fileinput-<?php if($row_rsP['d_imagem_full']!="" && file_exists("../../../imgs/banners/".$row_rsP['d_imagem_full'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>

                              	<?php if($ext != "wmv" && $ext != "mp4"){ ?>

	                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

	                                <?php if($row_rsP['d_imagem_full']!="" && file_exists("../../../imgs/banners/".$row_rsP['d_imagem_full'])) { ?>

	                                <a href="../../../imgs/banners/<?php echo $row_rsP['d_imagem_full']; ?>" data-fancybox ><img src="../../../imgs/banners/<?php echo $row_rsP['d_imagem_full']; ?>"></a>

	                                <?php } ?>

	                              </div>

	                        	<?php }else{ ?>

	                        		<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

																<a data-fancybox="gallery" href="../../../imgs/banners/<?php echo $row_rsP['d_imagem_full']; ?>" class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"><?php echo $RecursosCons->RecursosCons['ver_video']; ?></span></a>

															</div>

                          		<?php } ?>

                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

                              <input id="upload_campo" type="file" name="d_imagem_full">

                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>

                            </div>

                            <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt3']; ?></span> </div>

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

                    <div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_images2">

                      <div class="form-body">

                      	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 3) { ?>

                        <div class="alert alert-success display-show">

                          <button class="close" data-close="alert"></button>

                          <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </div>

                        <?php } ?>

                        <?php if($_GET['erro'] == 1 && $_GET['tab_sel'] == 3) { ?>

			                    <div class="alert alert-danger display-show">

			                    <button class="close" data-close="alert"></button>

			                     <?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> </div>   

			                	<?php } ?> 

                        		<div class="form-group">

			                    	<label class="col-md-2 control-label" for="mascara2" style="padding-top:0;"> <?php echo $RecursosCons->RecursosCons['tem_mascara_label']; ?></label>

				                    <div class="col-md-3">

				                      <input type="checkbox" class="form-control" name="mascara2" id="mascara2" value="1" <?php if($row_rsP['mascara2'] == 1) echo "checked";?>>

				                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['sel_mascara_msg']; ?></p>

				                    </div>

			                    <label class="col-md-2 control-label" for="cor2"><?php echo $RecursosCons->RecursosCons['cor_texto_label']; ?>:</label>

			                    <div class="col-md-3">

			                      <input type="text" class="form-control color" name="cor2" id="cor2" value="<?php echo $row_rsP['cor2']; ?>">

			                    </div>

			                    <i class="icon-trash" onclick="document.getElementById('cor2').value=''; document.getElementById('cor2').style.backgroundColor='#FFFFFF'" style="cursor:pointer; margin-top: 8px"></i>

			                  </div>

			                  <div class="form-group">

                        	<label class="col-md-2 control-label" for="align_h2"><?php echo $RecursosCons->RecursosCons['alinha_horizontal_label']; ?>: </label>

                        	<div class="col-md-3">

                        		<select class="form-control" name="align_h2" id="align_h2">

                        			<option value="left" <?php if($row_rsP['align_h2'] == "left") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_esquerda']; ?></option>

                        			<option value="center" <?php if($row_rsP['align_h2'] == "center") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>

                        			<option value="right" <?php if($row_rsP['align_h2'] == "right") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_direita']; ?></option>

                        		</select>

                        		<p class="help-block"><?php echo $RecursosCons->RecursosCons['info_img_horizontal']; ?></p>

                        	</div>

                        	<label class="col-md-2 control-label" for="align_v2"><?php echo $RecursosCons->RecursosCons['alinha_vertical_label']; ?>: </label>

                        	<div class="col-md-3">

                        		<select class="form-control" name="align_v2" id="align_v2">

                        			<option value="top" <?php if($row_rsP['align_v2'] == "top") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_todo']; ?></option>

                        			<option value="center" <?php if($row_rsP['align_v2'] == "center") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>

                        			<option value="bottom" <?php if($row_rsP['align_v2'] == "bottom") echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['opt_baixo']; ?></option>

                        		</select>

                        		<p class="help-block"><?php echo $RecursosCons->RecursosCons['info_img_vertical']; ?></p>

                        	</div>

                        </div>

                        <hr>

                        <div class="form-group" id="mobileimg">

                          <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem2_label']; ?><br>

                            <strong><?php echo $tamanho_imagens2['0']." * ".$tamanho_imagens2['1']." px"; ?>:</strong> </label>

                          <div class="col-md-4">

                            <div class="fileinput fileinput-<?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/banners/".$row_rsP['imagem2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>

                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

                                <?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/banners/".$row_rsP['imagem2'])) { ?>

                                <a href="../../../imgs/banners/<?php echo $row_rsP['imagem2']; ?>" data-fancybox><img src="../../../imgs/banners/<?php echo $row_rsP['imagem2']; ?>"></a>

                                <?php } ?>

                              </div>

                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

                                <input id="upload_campo2" type="file" name="img2">

                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>

                            </div>

                            <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span> </div>

                            <script type="text/javascript">

                            function alterar_imagem(){

                                document.getElementById('file_delete_1').value='';

                            }

                            function remover_imagem(){

                                document.getElementById('file_delete_1').value='';

                                document.getElementById('img_cont_1_vazia').style.display='block';									

                                document.getElementById('img_cont_1').style.display='none';

                            }

                            </script> <br><br>

                          </div>

                          <label class="col-md-2 control-label" for="opcao2"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>

                          <div class="col-md-4">

                          	<div style="margin-top: 8px" class="md-radio-list">

															<div class="md-radio">

																<input type="radio" id="opcao2_1" name="opcao2" value="1" class="md-radiobtn" checked>

																<label for="opcao2_1">

																<span></span>

																<span class="check"></span>

																<span class="box"></span>

																<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?></label>

															</div>

															<div class="md-radio">

																<input type="radio" id="opcao2_2" name="opcao2" value="2" class="md-radiobtn">

																<label for="opcao2_2">

																<span></span>

																<span class="check"></span>

																<span class="box"></span>

																<?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>

															</div>

														</div>

                          </div>

                        </div>





                       <div class="form-group" id="mobileimgfull">

                          <label class="col-md-2 control-label" style="text-align:right">Mobile FullBanner<br>

                            <strong><?php echo $tamanho_imagens2['0']." * ".$tamanho_imagens2['1']." px"; ?>:</strong> </label>

                          <div class="col-md-4">

                            <div class="fileinput fileinput-<?php if($row_rsP['m_imagem_full']!="" && file_exists("../../../imgs/banners/".$row_rsP['m_imagem_full'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>

                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

                                <?php if($row_rsP['m_imagem_full']!="" && file_exists("../../../imgs/banners/".$row_rsP['m_imagem_full'])) { ?>

                                <a href="../../../imgs/banners/<?php echo $row_rsP['m_imagem_full']; ?>" data-fancybox><img src="../../../imgs/banners/<?php echo $row_rsP['m_imagem_full']; ?>"></a>

                                <?php } ?>

                              </div>

                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

                                <input id="upload_campo2" type="file" name="m_imagem_full">

                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>

                            </div>

                            <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span> </div>

                            <script type="text/javascript">

                            function alterar_imagem(){

                                document.getElementById('file_delete_1').value='';

                            }

                            function remover_imagem(){

                                document.getElementById('file_delete_1').value='';

                                document.getElementById('img_cont_1_vazia').style.display='block';									

                                document.getElementById('img_cont_1').style.display='none';

                            }

                            </script> <br><br>

                          </div>

                          <label class="col-md-2 control-label" for="opcao2"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>

                          <div class="col-md-4">

                          	<div style="margin-top: 8px" class="md-radio-list">

															<div class="md-radio">

																<input type="radio" id="opcao2_1" name="opcao2" value="1" class="md-radiobtn" checked>

																<label for="opcao2_1">

																<span></span>

																<span class="check"></span>

																<span class="box"></span>

																<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?></label>

															</div>

															<div class="md-radio">

																<input type="radio" id="opcao2_2" name="opcao2" value="2" class="md-radiobtn">

																<label for="opcao2_2">

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

            <input type="hidden" name="MM_insert" value="frm_banners_h" />

          </form>

        </div>

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

<script src="slideshow-validation.js"></script> 

<!-- END PAGE LEVEL SCRIPTS --> 





<script>

jQuery(document).ready(function() {    

	Metronic.init(); // init metronic core components

	Layout.init(); // init current layout

	QuickSidebar.init(); // init quick sidebar

	Demo.init(); // init demo features

	Form.init();



	if('<?php echo $row_rsP["tipo"]; ?>' == '1') {

		$('.div_imagem').css('display', 'block');

    	$('.div_video').css('display', 'none');

	}

	else {

		$('.div_imagem').css('display', 'none');

    $('.div_video').css('display', 'block');

	}



	$('#tipo').on('change', function() {

    if($(this).val() == 1) {

      $('.div_imagem').css('display', 'block');

      $('.div_video').css('display', 'none');

    }

    else {

      $('.div_imagem').css('display', 'none');

      $('.div_video').css('display', 'block');

    }

  });



  $('#link_class').trigger('change');

});



function previewBtn(class_btn) {

  $.post("slideshow-rpc.php", {op: "preview_btn", class: class_btn}, function(data) {

    $('#btn_preview').html(data);

  });

}

</script>



<script>

	$(document).ready(function(){

		$("input[type='radio']").change(function(){

			hide_show_banner_type($(this).val());

		});

	});



	



	jQuery(document).ready(function($) {

		var this_val = $('[name="m_d_radio"]:checked').val();

		console.log(this_val);

		hide_show_banner_type(this_val);

	});

	function hide_show_banner_type(value) {

		if(value==2)

		{

			$("#otherAnswer").show();

			$("#destopimg").show();

			$("#mobileimg").show();

			$("#destopimgfull").hide();

			$("#mobileimgfull").hide();

		}

		else

		{

			$("#otherAnswer").hide();

			$("#destopimg").hide();

			$("#mobileimg").hide();

			$("#destopimgfull").show();

			$("#mobileimgfull").show();

		}

	}

</script>



</body>

<!-- END BODY -->

</html>