<?php include_once('../inc_pages.php'); ?>

<?php include('../../../geraRef.php'); ?>

<?php



$menu_sel='portes';

$menu_sub_sel='met_pagamento';



$tab_sel=1;

if($_GET['tab_sel'] && $_GET['tab_sel'] != "") $tab_sel=$_GET['tab_sel'];



$id=$_GET['id'];

$erro=0;



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "met_pagamento")) {

	$query_rsP = "SELECT imagem, imagem2 FROM met_pagamento_en WHERE id=:id";

	$rsP = DB::getInstance()->prepare($query_rsP);

	$rsP->bindParam(':id', $id, PDO::PARAM_INT);	

	$rsP->execute();

	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsP = $rsP->rowCount();



	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC";

	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

	$rsLinguas->execute();

	$row_rsLinguas = $rsLinguas->fetchAll();

	$totalRows_rsLinguas = $rsLinguas->rowCount();



	$insertSQL = "UPDATE met_pagamento".$extensao." SET descricao=:descricao, descricao2=:descricao2, valor_encomenda=:valor_encomenda, valor_encomenda2=:valor_encomenda2 WHERE id=:id";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':descricao', $_POST["descricao"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':descricao2', $_POST["descricao2"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':valor_encomenda', $_POST["valor_encomenda"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':valor_encomenda2', $_POST["valor_encomenda2"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

	$rsInsert->execute();

	

	if($id==6){ // multibanco

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET entidade=:entidade, subentidade=:subentidade WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':entidade', $_POST["entidade"], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':subentidade', $_POST["subentidade"], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

			$rsInsert->execute();

		}

	}

	

	if($id==1) {

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET email=:email, paypal_key=:paypal_key WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':email', $_POST["email"], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':paypal_key', $_POST["paypal_key"], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

			$rsInsert->execute();

		}

	}

	if($id==10) {

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET client_key=:client_key, service_key=:service_key WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':client_key', $_POST["client"], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':service_key', $_POST["service"], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	

			$rsInsert->execute();

		}

	}

	

	if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {

		@unlink('../../../imgs/carrinho/'.$row_rsP['imagem']);

		

		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET imagem = NULL WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

			$rsInsert->execute();

		}

	}



	if(isset($_POST['img_remover2']) && $_POST['img_remover2']==1) {

		@unlink('../../../imgs/carrinho/'.$row_rsP['imagem2']);



		foreach($row_rsLinguas as $linguas) {

			$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET imagem2 = NULL WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

			$rsInsert->execute();

		}

	}

	

	if($_FILES['img']['name']!='' || $_FILES['img2']['name']!='') {

		//Verificar o formato do ficheiro

		$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

		$ext2 = strtolower(pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION));



		if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png" && $ext2 != "jpg" && $ext2 != "jpeg" && $ext2 != "gif" && $ext2 != "png") {

			$erro = 1;

		}

		else {

			require("../resize_image.php");

			

			$imagem="";

			$imagem2="";

			

			$imgs_dir = "../../../imgs/carrinho";

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

				}

				else {

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

			

			$imagem=$nome_file1;

			$imagem2=$nome_file2;

				

			//RESIZE DAS IMAGENS

		

			//IMAGEM 1

			if($_FILES['img']['name']!='') {

				if($imagem!="" && file_exists("../../../imgs/carrinho/".$imagem)){				

					$maxW=300;

					$maxH=172;

					

					$sizes=getimagesize("../../../imgs/carrinho/".$imagem);

					

					$imageW=$sizes[0];

					$imageH=$sizes[1];

					

					if($imageW>$maxW || $imageH>$maxH){

						$img1=new Resize("../../../imgs/carrinho/", $imagem, $imagem, $maxW, $maxH);

						$img1->resize_image();	

					}

				}		

				

				if($row_rsP['imagem']) {

					@unlink('../../../imgs/carrinho/'.$row_rsP['imagem']);

				}

				

				foreach($row_rsLinguas as $linguas) {

					$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET imagem=:imagem WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);	

					$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

					$rsInsert->execute();

				}

			}



			//IMAGEM 2

			if($_FILES['img2']['name']!='') {

				if($imagem2!="" && file_exists("../../../imgs/carrinho/".$imagem2)){				

					$maxW=300;

					$maxH=172;

					

					$sizes=getimagesize("../../../imgs/carrinho/".$imagem2);

					

					$imageW=$sizes[0];

					$imageH=$sizes[1];

					

					if($imageW>$maxW || $imageH>$maxH){

						$img1=new Resize("../../../imgs/carrinho/", $imagem2, $imagem2, $maxW, $maxH);

						$img1->resize_image();	

					}

				}		

				

				if($row_rsP['imagem2']) {

					@unlink('../../../imgs/carrinho/'.$row_rsP['imagem2']);

				}

				

				foreach($row_rsLinguas as $linguas) {

					$insertSQL = "UPDATE met_pagamento_".$linguas['sufixo']." SET imagem2=:imagem2 WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':imagem2', $imagem2, PDO::PARAM_STR, 5);	

					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

					$rsInsert->execute();

				}

			}

		}

	}

	

	// métodos de pagamento disponíveis

	$query_rsPaginas = "SELECT * FROM met_envio_$lingua_consola ORDER BY id ASC";

	$rsPaginas = DB::getInstance()->prepare($query_rsPaginas);

	$rsPaginas->execute();

	$totalRows_rsPaginas = $rsPaginas->rowCount();

	

	$insertSQL = "DELETE FROM met_pag_envio WHERE met_pagamento=:id";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

	$rsInsert->execute();

	

	while($row_rsPaginas = $rsPaginas->fetch()) {		

		$met_envio=$row_rsPaginas['id'];



		if(isset($_POST['pagina'.$met_envio]) && $_POST['pagina'.$met_envio]==1) {	

			$insertSQL = "INSERT INTO met_pag_envio (met_pagamento, met_envio) VALUES (:id, :met_envio)";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

			$rsInsert->bindParam(':met_envio', $met_envio, PDO::PARAM_INT);

			$rsInsert->execute();

		}	

	}



	DB::close();

	

	if($erro == 1)

		header("Location: p_met_pagamento-edit.php?id=".$id."&erro=1&tab_sel=".$_POST["tab_sel"]);

	else

		header("Location: p_met_pagamento-edit.php?id=".$id."&alt=1&tab_sel=".$_POST["tab_sel"]);

}



$query_rsP = "SELECT * FROM met_pagamento".$extensao." WHERE id=:id";

$rsP = DB::getInstance()->prepare($query_rsP);

$rsP->bindParam(':id', $id, PDO::PARAM_INT);	

$rsP->execute();

$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

$totalRows_rsP = $rsP->rowCount();



$query_rsMets = "SELECT met_envio FROM met_pag_envio WHERE met_pagamento=:id";

$rsMets = DB::getInstance()->prepare($query_rsMets);

$rsMets->bindParam(':id', $id, PDO::PARAM_INT);	

$rsMets->execute();

$totalRows_rsMets = $rsMets->rowCount();



$dados=array();



if($totalRows_rsMets > 0) {

	while($linha = $rsMets->fetch()){

		++$i;

		

		extract($linha);

		

		$dados[$i] = $met_envio;

	}

}



$query_rsPaginas = "SELECT * FROM met_envio_$lingua_consola ORDER BY id ASC";

$rsPaginas = DB::getInstance()->prepare($query_rsPaginas);

$rsPaginas->execute();

$totalRows_rsPaginas = $rsPaginas->rowCount();

DB::close();



?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>

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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['portes_met.pag_page_title']; ?> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>

          <form id="met_pagamento" name="met_pagamento" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="manter" id="manter" value="0">

            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">

            <input type="hidden" name="img_remover1" id="img_remover1" value="0">

            <input type="hidden" name="img_remover2" id="img_remover2" value="0">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $row_rsP["nome_interno"]; ?></div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='p_met_pagamento.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

                </div>

              </div>

              <div class="portlet-body">

                <div class="tabbable">

                  <ul class="nav nav-tabs">

                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>

                    <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_envio" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_met_env_disponiveis']; ?> </a> </li>

                  </ul>

                  <div class="tab-content no-space">

                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">

                      <div class="form-body">

                        <?php if($_GET["alt"] == 1) { ?>

	                        <div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                          <span>  <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 

	                        </div>

                        <?php } ?>

                        <?php if($_GET['erro'] == 1) { ?>

			                    <div class="alert alert-danger display-show">

			                    	<button class="close" data-close="alert"></button>

			                     	<?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> 

			                    </div>   

			                	<?php } ?> 

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="valor_encomenda"><strong> <?php echo $RecursosCons->RecursosCons['min_encomenda_label']; ?>:</strong> </label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="valor_encomenda" id="valor_encomenda" value="<?php echo $row_rsP['valor_encomenda']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">

                              <span class="input-group-addon">&pound;</span> </div>

                          </div>

                          <div class="col-md-2" style="padding-top:5px;"><?php echo $RecursosCons->RecursosCons['info_limite_min']; ?></div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="valor_encomenda2"><strong><?php echo $RecursosCons->RecursosCons['max_encomenda_label']; ?>:</strong> </label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="valor_encomenda2" id="valor_encomenda2" value="<?php echo $row_rsP['valor_encomenda2']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">

                              <span class="input-group-addon">&pound;</span> </div>

                          </div>

                          <div class="col-md-2" style="padding-top:5px;"><?php echo $RecursosCons->RecursosCons['info_limite_max']; ?></div>

                        </div>

                         <?php if($row_rsP['id'] == 10) {  ?>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="client"><strong>Client ID:</strong> </label>

	                          <div class="col-md-2">

	                            <input type="text" class="form-control" name="client" id="client" value="<?php echo $row_rsP['client_key']; ?>" style="width:500px;">

	                          </div>

	                        </div>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="service"><strong>Service ID: </label>

	                          <div class="col-md-2">

	                            <input type="text" class="form-control" name="service" id="service" value="<?php echo $row_rsP['service_key']; ?>" style="width:500px;">

	                          </div>

	                        </div>

                        <?php } ?>

                        <?php if($row_rsP['id'] == 6) { ?>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="entidade"><strong><?php echo $RecursosCons->RecursosCons['entidade_ref']; ?>:</strong> </label>

	                          <div class="col-md-2">

	                            <input type="text" class="form-control" name="entidade" id="entidade" value="<?php echo $row_rsP['entidade']; ?>" maxlength="5" style="width:100px;">

	                          </div>

	                        </div>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="subentidade"><strong><?php echo $RecursosCons->RecursosCons['sub_entidade_label']; ?>:</strong> </label>

	                          <div class="col-md-2">

	                            <input type="text" class="form-control" name="subentidade" id="subentidade" value="<?php echo $row_rsP['subentidade']; ?>" maxlength="3" style="width:100px;">

	                          </div>

	                        </div>

                        <?php }

                        else if($row_rsP['id'] == 1) { ?>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="email"><strong><?php echo $RecursosCons->RecursosCons['cli_email']; ?>:</strong> </label>

	                          <div class="col-md-4">

	                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $row_rsP['email']; ?>">

	                          </div>

	                        </div>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="paypal_key"><strong><?php echo $RecursosCons->RecursosCons['paypal_key_label']; ?>:</strong> </label>

	                          <div class="col-md-6">

	                            <input type="text" class="form-control" name="paypal_key" id="paypal_key" value="<?php echo $row_rsP['paypal_key']; ?>">

	                          </div>

	                        </div>

                        <?php }?>

                        <?php if($row_rsP['id'] != 1 && $row_rsP['id'] != 7 && $row_rsP['id'] != 8) { ?>

	                        <div class="form-group">

	                          <label class="col-md-2 control-label" for="descricao"><strong><?php echo $RecursosCons->RecursosCons['dados_pagamento_label']; ?>:</strong> </label>

	                          <div class="col-md-6">

	                            <p class="help-block"><strong><?php echo $RecursosCons->RecursosCons['info_dados_pag']; ?></strong></p>

	                            <textarea class="form-control" name="descricao" id="descricao"><?php echo $row_rsP['descricao']; ?></textarea>

	                          </div>

	                        </div>

	                      <?php } ?>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="descricao2"><strong><?php echo $RecursosCons->RecursosCons['descricao_pag_label']; ?>:</strong> </label>

                          <div class="col-md-6">

                          	<p class="help-block"><strong><?php echo $RecursosCons->RecursosCons['info_descricao_pag']; ?></strong></p>

                            <textarea class="form-control" name="descricao2" id="descricao2"><?php echo $row_rsP['descricao2']; ?></textarea>

                          </div>

                        </div>

                        <hr>

                        <div class="form-group">

                          <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['imagem_carrinho']; ?>:<br>

                            300px*172px</strong></label>

                          <div class="col-md-6">

                            <div class="fileinput fileinput-<?php if($row_rsP['imagem']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=sem+imagem" alt=""/> </div>

                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

                                <?php if($row_rsP['imagem']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem'])) { ?>

                                <a href="../../../imgs/carrinho/<?php echo $row_rsP['imagem']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/carrinho/<?php echo $row_rsP['imagem']; ?>"></a>

                                <?php } ?>

                              </div>

                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

                                <input id="upload_campo" type="file" name="img">

                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?>  </a> </div>

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

												    </script> 

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['imagem_footer']; ?>:<br>

                            300px*172px</strong></label>

                          <div class="col-md-6">

                            <div class="fileinput fileinput-<?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">

                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=sem+imagem" alt=""/> </div>

                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

                                <?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem2'])) { ?>

                                <a href="../../../imgs/carrinho/<?php echo $row_rsP['imagem2']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/carrinho/<?php echo $row_rsP['imagem2']; ?>"></a>

                                <?php } ?>

                              </div>

                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>

                                <input id="upload_campo" type="file" name="img2">

                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?>  </a> </div>

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

												    </script> 

                          </div>

                        </div>

                      </div>

                    </div>

                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_envio">

                      <?php if($totalRows_rsPaginas > 0) { ?>

                      <div class="form-body">

                        <?php if($_GET["alt"] == 1) { ?>

	                        <div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                          <span>  <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 

	                        </div>

                        <?php } ?>

                        <div class="form-group">

                          <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['met_envio_disponiveis']; ?>:</strong> </label>

                          <div class="col-md-6">

                            <?php $i=1; while($row_rsPaginas = $rsPaginas->fetch()) { ?>

                            <div style="width:200px; float:left">

                              <input type="checkbox" name="pagina<?php echo $row_rsPaginas['id']; ?>" <?php if(in_array($row_rsPaginas['id'],$dados)){?> checked="checked"<?php }?> value="1" />

                              &nbsp;<?php echo $row_rsPaginas['nome']; ?></div>

                            <?php 

															$i++;

															if($i > 3) {

																echo '<div class="clearfix"></div><br><br>';

																$i=1;

															}

														?>

                            <?php } ?>

                          </div>

                        </div>

                      </div>

                      <?php } ?>

                    </div>

                  </div>

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="met_pagamento" />

          </form>

        </div>

      </div>

      <!-- END PAGE CONTENT--> 

    </div>

  </div>

  <!-- END CONTENT -->

  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>

</div>

<!-- END CONTAINER -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>

<!-- BEGIN PAGE LEVEL PLUGINS --> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 

<!-- BEGIN PAGE LEVEL SCRIPTS --> 

<!-- END PAGE LEVEL SCRIPTS --> 

<script type="text/javascript">

jQuery(document).ready(function() {    

	Metronic.init(); // init metronic core components

	Layout.init(); // init current layout

	QuickSidebar.init(); // init quick sidebar

	Demo.init(); // init demo features

});

</script>

<script type="text/javascript">

CKEDITOR.replace('descricao2', {

  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',

  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',

  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',

  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',

  toolbar : "Basic2",

  height: "150px"

});

CKEDITOR.replace('descricao', {

  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',

  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',

  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',

  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',

  toolbar : "Basic2",

  height: "150px"

});

</script>

</body>

<!-- END BODY -->

</html>