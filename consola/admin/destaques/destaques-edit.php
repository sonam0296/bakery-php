<?php include_once('../inc_pages.php'); ?>

<?php //ini_set('display_errors', 1);



$menu_sel='destaques';

$menu_sub_sel='';



$tab_sel=1;

if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];



$id = $_GET['id'];

$erro = 0;



$tamanho_imagens1 = getFillSize('Destaques', 'imagem1');

$tamanho_imagens2 = getFillSize('Destaques', 'imagem2');



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_destaques")) {

	$manter = $_POST['manter'];

	

	$query_rsP = "SELECT imagem1, imagem2 FROM destaques_en WHERE id=:id";

	$rsP = DB::getInstance()->prepare($query_rsP);

	$rsP->bindParam(':id', $id, PDO::PARAM_INT);	

	$rsP->execute();

	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsP = $rsP->rowCount();



	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

	$rsLinguas->execute();

	$row_rsLinguas = $rsLinguas->fetchAll();

	$totalRows_rsLinguas = $rsLinguas->rowCount();



	if($_POST['nome']!='' && $tab_sel == 1) {

		$insertSQL = "UPDATE destaques_en SET nome=:nome, titulo=:titulo, link=:link, texto=:texto, texto_botao=:texto_botao WHERE id=:id";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':texto_botao', $_POST['texto_botao'], PDO::PARAM_STR, 5);

		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

		$rsInsert->execute();



		foreach ($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE destaques_".$linguas['sufixo']." SET target=:target, tema=:tema WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':tema', $_POST['tema'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

			$rsInsert->execute();

		}



		DB::close();

		

		if(!$manter) 

			header("Location: destaques.php?alt=1");

		else

			header("Location: destaques-edit.php?id=".$id."&alt=1&tab_sel=".$_POST['tab_sel']);			

	}



	if($tab_sel==2) {

		$rem = 0;

		$opcao = $_POST['opcao'];

		$imagem = $row_rsP['imagem1'];

		$imagem2 = $row_rsP['imagem2'];

		

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE destaques_".$linguas["sufixo"]." SET cor=:cor, bg_cor=:bg_cor WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':cor', $_POST['cor'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':bg_cor', $_POST['bg_cor'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

			$rsInsert->execute();

		}



		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {

			if($opcao == 1) {

				$insertSQL = "UPDATE destaques_en SET imagem1=NULL WHERE id=:id";

				$rsInsert = DB::getInstance()->prepare($insertSQL);

				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

				$rsInsert->execute();



				$r = 0;



				//Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...

				foreach ($row_rsLinguas as $linguas) {

					$query_rsImagem = "SELECT id FROM destaques_".$linguas["sufixo"]." WHERE imagem1=:imagem AND id=:id";

					$rsImagem = DB::getInstance()->prepare($query_rsImagem);

					$rsImagem->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);

					$rsImagem->bindParam(':id', $id, PDO::PARAM_INT);

					$rsImagem->execute();

					$totalRows_rsImagem = $rsImagem->rowCount();



					if($totalRows_rsImagem > 0)

						$r = 1;

				}



				//Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la

				if($r == 0)

					@unlink('../../../imgs/destaques/'.$imagem);

			}

			else if($opcao == 2) {

				foreach ($row_rsLinguas as $linguas) {

					$query_rsSelect = "SELECT imagem1 FROM destaques_".$linguas['sufixo']." WHERE id=:id";

					$rsSelect = DB::getInstance()->prepare($query_rsSelect);

					$rsSelect->bindParam(':id', $id, PDO::PARAM_INT);

					$rsSelect->execute();

					$row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);



					@unlink('../../../imgs/destaques/'.$row_rsSelect['imagem1']);



					$insertSQL = "UPDATE destaques_".$linguas["sufixo"]." SET imagem1=NULL WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

					$rsInsert->execute();

				}

			}



			$rem = 1;

		}



		if(isset($_POST['img_remover2']) && $_POST['img_remover2']==1) {

			if($opcao == 1) {

				$insertSQL = "UPDATE destaques_en SET imagem2=NULL WHERE id=:id";

				$rsInsert = DB::getInstance()->prepare($insertSQL);

				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

				$rsInsert->execute();



				$r = 0;



				//Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...

				foreach ($row_rsLinguas as $linguas) {

					$query_rsImagem = "SELECT id FROM destaques_".$linguas["sufixo"]." WHERE imagem2=:imagem AND id=:id";

					$rsImagem = DB::getInstance()->prepare($query_rsImagem);

					$rsImagem->bindParam(':imagem', $imagem2, PDO::PARAM_STR, 5);

					$rsImagem->bindParam(':id', $id, PDO::PARAM_INT);

					$rsImagem->execute();

					$totalRows_rsImagem = $rsImagem->rowCount();



					if($totalRows_rsImagem > 0)

						$r = 1;

				}



				//Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la

				if($r == 0)

					@unlink('../../../imgs/destaques/'.$imagem2);

			}

			else if($opcao == 2) {

				foreach ($row_rsLinguas as $linguas) {

					$query_rsSelect = "SELECT imagem2 FROM destaques_".$linguas['sufixo']." WHERE id=:id";

					$rsSelect = DB::getInstance()->prepare($query_rsSelect);

					$rsSelect->bindParam(':id', $id, PDO::PARAM_INT);

					$rsSelect->execute();

					$row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);



					@unlink('../../../imgs/destaques/'.$row_rsSelect['imagem2']);



					$insertSQL = "UPDATE destaques_".$linguas["sufixo"]." SET imagem2=NULL WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

					$rsInsert->execute();

				}

			}



			$rem = 1;

		}

		

		$ins = 0;



		if($_FILES['img']['name']!='' || $_FILES['img2']['name']!='') { // actualiza imagem

			//Verificar o formato do ficheiro

			$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

			$ext2 = strtolower(pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION));



			if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png" && $ext2 != "jpg" && $ext2 != "jpeg" && $ext2 != "gif" && $ext2 != "png") {

				$erro = 1;

			}

			else {

				$ins = 1;	

				require("../resize_image.php");

				

				$imagem="";	

				

				$imgs_dir = "../../../imgs/destaques";

				$contaimg = 1; 

		

				foreach($_FILES as $file_name => $file_array) {

			

					$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

					

					switch ($contaimg) {

						case '1': case '2': case '3':    

							$file_dir =  $imgs_dir;

						break;

					}

					

		

					if($file_array['size'] > 0){

							$nome_img=verifica_nome($file_array['name']);

							$nome_file = $id_file."_".$nome_img;

							@unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);

					}else {

							//$nome_file = $_POST['file_db_'.$contaimg];

		

						if($_POST['file_db_'.$contaimg])

							$nome_file = $_POST['file_db_'.$contaimg];

						else{

							$nome_file ='';

							@unlink($file_dir.'/'.$_POST['file_db_del_'.$contaimg]);

							}

		

					}

							

					if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); }

		

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

				$imagem2 = $nome_file2;

			

				//IMAGEM 1

				if($_FILES['img']['name']!='') {

					if($imagem!="" && file_exists("../../../imgs/destaques/".$imagem)){

										

						$maxW=$tamanho_imagens1['0'];

						$maxH=$tamanho_imagens1['1'];

						

						$sizes=getimagesize("../../../imgs/destaques/".$imagem);

						

						$imageW=$sizes[0];

						$imageH=$sizes[1];

						

						if($imageW>$maxW || $imageH>$maxH){

											

							$img1=new Resize("../../../imgs/destaques/", $imagem, $imagem, $maxW, $maxH);

							$img1->resize_image();

							

						}

					

					}		

					

					if($row_rsP['imagem1']) {

						@unlink('../../../imgs/destaques/'.$row_rsP['imagem1']);

					}



					compressImage('../../../imgs/destaques/'.$imagem, '../../../imgs/destaques/'.$imagem);

					

					//Inserir apenas na língua atual

					if($opcao == 1) {

						$insertSQL = "UPDATE destaques_en SET imagem1=:imagem1 WHERE id=:id";

						$rsInsert = DB::getInstance()->prepare($insertSQL);

						$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);

						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		

						$rsInsert->execute();

					}

					//Inserir para todas as línguas

					else if($opcao == 2) {

						foreach ($row_rsLinguas as $linguas) {		

							$insertSQL = "UPDATE destaques_".$linguas["sufixo"]." SET imagem1=:imagem1 WHERE id=:id";

							$rsInsert = DB::getInstance()->prepare($insertSQL);

							$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);

							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		

							$rsInsert->execute();

							

						}

					}

				}



				//IMAGEM 2

				if($_FILES['img2']['name']!='') {

					if($imagem2!="" && file_exists("../../../imgs/destaques/".$imagem2)){

										

						$maxW=$tamanho_imagens1['0'];

						$maxH=$tamanho_imagens1['1'];

						

						$sizes=getimagesize("../../../imgs/destaques/".$imagem2);

						

						$imageW=$sizes[0];

						$imageH=$sizes[1];

						

						if($imageW>$maxW || $imageH>$maxH){

											

							$img1=new Resize("../../../imgs/destaques/", $imagem2, $imagem2, $maxW, $maxH);

							$img1->resize_image();

							

						}

					

					}		

					

					if($row_rsP['imagem2']) {

						@unlink('../../../imgs/destaques/'.$row_rsP['imagem2']);

					}



					compressImage('../../../imgs/destaques/'.$imagem2, '../../../imgs/destaques/'.$imagem2);

					

					//Inserir apenas na língua atual

					if($opcao == 1) {

						$insertSQL = "UPDATE destaques_en SET imagem2=:imagem1 WHERE id=:id";

						$rsInsert = DB::getInstance()->prepare($insertSQL);

						$rsInsert->bindParam(':imagem1', $imagem2, PDO::PARAM_STR, 5);

						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		

						$rsInsert->execute();

					}

					//Inserir para todas as línguas

					else if($opcao == 2) {

						foreach ($row_rsLinguas as $linguas) {		

							$insertSQL = "UPDATE destaques_".$linguas["sufixo"]." SET imagem2=:imagem1 WHERE id=:id";

							$rsInsert = DB::getInstance()->prepare($insertSQL);

							$rsInsert->bindParam(':imagem1', $imagem2, PDO::PARAM_STR, 5);

							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		

							$rsInsert->execute();

							

						}

					}

				}

			}

		}



		DB::close();

		

		if($erro == 1) 

			header("Location: destaques-edit.php?id=".$id."&erro=1&tab_sel=2");

		else {

			if(!$manter) 

				header("Location: destaques.php?alt=1");

			else {

				if($rem == 1 && $ins == 0)

					header("Location: destaques-edit.php?id=".$id."&rem=1&tab_sel=2");

				else

					header("Location: destaques-edit.php?id=".$id."&alt=1&tab_sel=2");

			}

		}

	}

}



$query_rsP = "SELECT * FROM destaques_en WHERE id = :id";

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

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>

<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>

<div class="clearfix"> </div>

<!-- BEGIN CONTAINER -->

<div class="page-container">

  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>

  <!-- BEGIN CONTENT -->

  <div class="page-content-wrapper">

    <div class="page-content"> 

      <!-- BEGIN PAGE HEADER-->

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['destaques']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="destaques.php"><?php echo $RecursosCons->RecursosCons['destaques']; ?> </a> <i class="fa fa-angle-right"></i></li>

          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?> </a> </li>

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

              <button type="button" class="btn blue" onClick="document.location='destaques.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>

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

          <form id="frm_destaques" name="frm_destaques" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="manter" id="manter" value="0">

            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">

            <input type="hidden" name="img_remover1" id="img_remover1" value="0">

            <input type="hidden" name="img_remover2" id="img_remover2" value="0">

	          <div class="portlet">

	            <div class="portlet-title">

	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['destaques']; ?> - <?php echo $row_rsP["nome"]; ?> </div>

	              <div class="form-actions actions btn-set">

	                <button type="button" name="back" class="btn default" onClick="document.location='destaques.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

	                <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>

	                <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>

	              </div>

	            </div>

	            <div class="portlet-body">

	              <div class="tabbable">

	                <ul class="nav nav-tabs">

	                  <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>

	                  <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>

	                </ul>

	                <div class="tab-content no-space">

	                  <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">

                      <div class="form-body">

                        <div class="alert alert-danger display-hide">

                          <button class="close" data-close="alert"></button>

                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?>

                        </div>

                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>

                        	<div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                        	<?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 

	                       	</div>

                        <?php } ?>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>">

                          </div>

                        </div>

                        <!-- <div class="form-group">

                          <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>

                          <div class="col-md-8">

                          	<input type="text" class="form-control" name="texto" id="texto" value="<?php echo $row_rsP['texto']; ?>">

                          </div>

                        </div> -->

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="tema"><?php echo "Tema: "; ?> </label>

                          <div class="col-md-8">

                            <select name="tema" id="tema" class="form-control select2me">

                              <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                              <option value="1" <?php if($row_rsP["tema"] == "1") { ?>selected<?php } ?>><?php echo "Tema 1 (Título alinhado ao topo)"; ?></option>

                              <option value="2" <?php if($row_rsP["tema"] == "2") { ?>selected<?php } ?>><?php echo "Tema 2 (Título alinhado ao centro)"; ?></option>

                              <option value="3" <?php if($row_rsP["tema"] == "3") { ?>selected<?php } ?>><?php echo "Tema 3 (Título alinhado ao inferior)"; ?></option>

                            </select>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>

                          <div class="col-md-3">

                            <select name="target" id="target" class="form-control select2me">

                              <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                              <option value="_blank" <?php if($row_rsP["target"] == "_blank") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>

                              <option value="_parent" <?php if($row_rsP["target"] == "_parent") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>

                            </select>

                          </div>

                          <!-- <label class="col-md-2 control-label" for="texto_botao"><?php echo $RecursosCons->RecursosCons['texto_botao_label']; ?>: </label>

                          <div class="col-md-3">

                            <input type="text" class="form-control" name="texto_botao" id="texto_botao" value="<?php echo $row_rsP['texto_botao']; ?>">

                          </div> -->

                        </div>

                      </div>

                  	</div>

	                  <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">

	                    <div class="form-body">

	                    	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>

		                      <div class="alert alert-success display-show">

		                        <button class="close" data-close="alert"></button>

		                        <?php echo $RecursosCons->RecursosCons['img_alt']; ?>

		                      </div>

	                      <?php } ?>

	                      <?php if($_GET['v'] == 1 && $_GET['tab_sel'] == 2) { ?>

		                      <div class="alert alert-success display-show">

		                        <button class="close" data-close="alert"></button>

		                        <?php echo $RecursosCons->RecursosCons['destaque_ins_suc']; ?> 

		                      </div>

		                    <?php } ?>

	                      <?php if($_GET['rem'] == 1 && $_GET['tab_sel'] == 2) { ?>

		                      <div class="alert alert-danger display-show">

		                        <button class="close" data-close="alert"></button>

		                        <?php echo $RecursosCons->RecursosCons['img_rem']; ?> 

		                      </div>

	                      <?php } ?>

	                      <?php if($_GET['erro'] == 1 && $_GET['tab_sel'] == 2) { ?>

	                        <div class="alert alert-danger display-show">

	                        	<button class="close" data-close="alert"></button>

	                        	<?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?>

	                        </div>

                        <?php } ?>

                        <div class="form-group">

			                    

			                  </div>

	                      <div class="form-group">

	                        <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['imagem_principal']; ?><br>

	                          <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>

	                        <div class="col-md-4">

	                          <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/destaques/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

	                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>

	                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

	                              <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/destaques/".$row_rsP['imagem1'])) { ?>

	                              <a href="../../../imgs/destaques/<?php echo $row_rsP['imagem1']; ?>" data-fancybox="gallery" ><img src="../../../imgs/destaques/<?php echo $row_rsP['imagem1']; ?>"></a>

	                              <?php } ?>

	                            </div>

	                            <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

	                              <input id="upload_campo" type="file" name="img">

	                              </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>

	                          </div>

	                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>

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

	                    <!-- <div class="form-group">

	                        <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['imagem_secundaria']; ?><br>

	                          <strong><?php echo $tamanho_imagens2['0']." * ".$tamanho_imagens2['1']." px"; ?>:</strong> </label>

	                        <div class="col-md-4">

	                          <div class="fileinput fileinput-<?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/destaques/".$row_rsP['imagem2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

	                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>

	                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

	                              <?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/destaques/".$row_rsP['imagem2'])) { ?>

	                              <a href="../../../imgs/destaques/<?php echo $row_rsP['imagem2']; ?>" data-fancybox="gallery" ><img src="../../../imgs/destaques/<?php echo $row_rsP['imagem2']; ?>"></a>

	                              <?php } ?>

	                            </div>

	                            <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

	                              <input id="upload_campo" type="file" name="img2">

	                              </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>

	                          </div>

	                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>

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

	                       

	                      </div> -->

	                    </div>

	                    <div class="form-group">

	                    	 <label class="col-md-2 control-label" for="cor"><?php echo $RecursosCons->RecursosCons['cor_texto_label']; ?>:</label>

		                    <div class="col-md-1">

		                      <input type="text" class="form-control color" name="cor" id="cor" value="<?php echo $row_rsP['cor']; ?>">

		                    </div>

		                    <i class="icon-trash" onclick="document.getElementById('cor').value=''; document.getElementById('cor').style.backgroundColor='#FFFFFF'" style="cursor:pointer; margin-top: 8px"></i>

	                    </div>

	                    <div class="form-group">

	                    	<label class="col-md-2 control-label" for="bg_cor"><?php echo $RecursosCons->RecursosCons['bg_cor_texto_label']; ?>:</label>

			                    <div class="col-md-1">

			                      <input type="text" class="form-control color" name="bg_cor" id="bg_cor" value="<?php echo $row_rsP['bg_cor']; ?>">

			                    </div>

			                    <i class="icon-trash" onclick="document.getElementById('bg_cor').value=''; document.getElementById('bg_cor').style.backgroundColor='#FFFFFF'" style="cursor:pointer; margin-top: 8px"></i>

	                    </div>

	                  </div>

	                </div>

	              </div>

	            </div>

	          </div>

	          <input type="hidden" name="MM_insert" value="frm_destaques" />

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

<script src="form-validation.js"></script> 

<!-- END PAGE LEVEL SCRIPTS --> 

<script>

jQuery(document).ready(function() {    

	Metronic.init(); // init metronic core components

	Layout.init(); // init current layout

	QuickSidebar.init(); // init quick sidebar

	Demo.init(); // init demo features

	Form.init();

});

</script> 

</body>

<!-- END BODY -->

</html>