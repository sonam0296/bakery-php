<?php include_once('../inc_pages.php'); ?>
<?php //ini_set("display_errors", 1);

$menu_sel='banners';
$menu_sub_sel='popups';
$tab_sel=1;

if(isset($_GET['tab_sel']) && $_GET['tab_sel'] != "" && $_GET['tab_sel'] != 0) $tab_sel=$_GET['tab_sel'];
elseif(isset($_POST['tab_sel']) && $_POST['tab_sel'] != "" && $_POST['tab_sel'] != 0) $tab_sel=$_POST['tab_sel'];

$id = $_GET['id'];
$erro = 0;

$tamanho_imagens1 = getFillSize('Banners', 'imagem3');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_banners_h")) {
	$manter = $_POST['manter'];

	$tab_sel = $_REQUEST['tab_sel'];
	
	$query_rsP = "SELECT imagem1, imagem2, imagem3 FROM banners_popups".$extensao." WHERE id = :id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();

	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $row_rsLinguas = $rsLinguas->fetchAll();
  $totalRows_rsLinguas = $rsLinguas->rowCount();
	
	if($_POST['nome']!='' && $tab_sel == 1) {			
		// actualiza detalhes
		$insertSQL = "UPDATE banners_popups".$extensao." SET nome=:nome, titulo=:titulo, subtitulo=:subtitulo, texto=:texto, link=:link, texto_link=:texto_link WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':subtitulo', $_POST['subtitulo'], PDO::PARAM_STR, 5); 	
		$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
		$rsInsert->execute();
		
		$datai = NULL;
		if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];
		$dataf = NULL;
		if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];
		
		foreach ($row_rsLinguas as $linguas) {
			$insertSQL = "UPDATE banners_popups_".$linguas["sufixo"]." SET tipo=:tipo, datai=:datai, dataf=:dataf, target=:target WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT);
			$rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
			$rsInsert->execute();
		}

		alteraSessions('banners_popups');

		DB::close();

		if(!$manter) 
			header("Location: slideshow-popups.php?alt=1");
		else 
			header("Location: slideshow-popups-edit.php?id=".$id."&alt=1&tab_sel=1");
	}
	
	if($tab_sel==2) {
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $row_rsLinguas = $rsLinguas->fetchAll();
    $totalRows_rsLinguas = $rsLinguas->rowCount();

		$opcao = $_POST['opcao'];
		$imagem = $row_rsP['imagem1'];

		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
			if($opcao == 1) {
				$insertSQL = "UPDATE banners_popups".$extensao." SET imagem1=NULL WHERE id=:id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
				$rsInsert->execute();

				$r = 0;

				//Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...
				foreach($row_rsLinguas as $linguas) {	
					$query_rsImagem = "SELECT id FROM banners_popups_".$linguas["sufixo"]." WHERE imagem1=:imagem AND id=:id";
					$rsImagem = DB::getInstance()->prepare($query_rsImagem);
					$rsImagem->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
					$rsImagem->bindParam(':id', $id, PDO::PARAM_INT);
					$rsImagem->execute();
					$totalRows_rsImagem = $rsImagem->rowCount();

					if($totalRows_rsImagem > 0)
						$r = 1;
				}

				//Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
				if($r == 0) {
					@unlink('../../../imgs/banners/'.$imagem);
				}
			}
			else if($opcao == 2) {
				foreach($row_rsLinguas as $linguas) {
					$query_rsSelect = "SELECT imagem1 FROM banners_popups_".$linguas['sufixo']." WHERE id=:id";
					$rsSelect = DB::getInstance()->prepare($query_rsSelect);
					$rsSelect->bindParam(':id', $id, PDO::PARAM_INT);
					$rsSelect->execute();
					$row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

					@unlink('../../../imgs/banners/'.$row_rsSelect['imagem1']);

					$insertSQL = "UPDATE banners_popups_".$linguas["sufixo"]." SET imagem1=NULL WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
					$rsInsert->execute();
				}
			}
		}

		if($_FILES['img']['name']!='') { // actualiza imagem
			//Verificar o formato do ficheiro
			$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

			if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png") {
				$erro = 1;
			}
			else {
				$ins = 1;	
				require("../resize_image.php");
				
				$imagem="";		
				
				$imgs_dir = "../../../imgs/banners";
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

				//IMAGEM 1
				if($_FILES['img']['name']!='') {
					if($imagem!="" && file_exists("../../../imgs/banners/".$imagem)){
										
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
						@unlink('../../../imgs/banners/'.$row_rsP['imagem1']);
					}

					compressImage('../../../imgs/banners/'.$imagem, '../../../imgs/banners/'.$imagem);

					//Inserir apenas na língua atual
					if($opcao == 1) {
						$insertSQL = "UPDATE banners_popups".$extensao." SET imagem1=:imagem1 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
						$rsInsert->execute();
						
					}
					//Inserir para todas as línguas
					else if($opcao == 2) {
						foreach($row_rsLinguas as $linguas) {		
							$insertSQL = "UPDATE banners_popups_".$linguas["sufixo"]." SET imagem1=:imagem1 WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
							$rsInsert->execute();
						}
					}
				}
			}
		}

		DB::close();

		alteraSessions('banners_popups');
		
		if($erro == 1)
			header("Location: slideshow-popups-edit.php?id=".$id."&erro=1&tab_sel=2");
		else {
			if(!$manter) 
				header("Location: slideshow-popups.php?alt=1");
			else 
				header("Location: slideshow-popups-edit.php?id=".$id."&alt=1&tab_sel=2");
		}
	}

	if($tab_sel==4){
		$codprom = $_POST['codigo_promocional'];

		foreach ($row_rsLinguas as $linguas) {
			$insertSQL = "UPDATE banners_popups_".$linguas["sufixo"]." SET tipo_cliente=:tipo_cliente, cliente_registo=:cliente_registo, timer=:timer, cod_prom=:cod_prom WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':tipo_cliente', $_POST['tipo_cliente'], PDO::PARAM_INT);
			$rsInsert->bindParam(':cliente_registo', $_POST['cliente_registo'], PDO::PARAM_INT);
			$rsInsert->bindParam(':timer', $_POST['timer'], PDO::PARAM_INT);
			$rsInsert->bindParam(':cod_prom', $codprom, PDO::PARAM_INT);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
			$rsInsert->execute();
		}

		alteraSessions('banners_popups');

		DB::close();

		if(!$manter) 
			header("Location: slideshow-popups.php?alt=1");
		else 
			header("Location: slideshow-popups-edit.php?id=".$id."&alt=1&tab_sel=4");
	}
}

$query_rsP = "SELECT * FROM banners_popups".$extensao." WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsCodProm = "SELECT * FROM codigos_promocionais WHERE id > 0";
$rsCodProm = DB::getInstance()->prepare($query_rsCodProm);
$rsCodProm->execute();
$row_rsCodProm = $rsCodProm->fetchAll();
$totalRows_rsCodProm = $rsCodProm->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<?php if($row_rsP['tipo'] == 1){ ?>
<style type="text/css">
  .div_newsletter {
    display: none;
  }
</style>
<?php } else if($row_rsP['tipo'] == 2) { ?>
<style type="text/css">
  .div_geral {
    display: none;
  }
</style>
<?php }?>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['banner_popups_title']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="slideshow-popups.php"><?php echo $RecursosCons->RecursosCons['banner_popups_title']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
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
              <button type="button" class="btn blue" onClick="document.location='slideshow-popups.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
        </div>
      </div>
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <!-- BEGIN PAGE CONTENT--> 
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
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['banner_popups_title']; ?> - <?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='slideshow-popups.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
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
                    <li class="<?php if($tab_sel==2) echo 'active'; ?>"> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagem']; ?> </a> </li>
                    <li class="<?php if($tab_sel==4) echo 'active'; ?>"> <a href="#tab_config" data-toggle="tab" onClick="document.getElementById('tab_sel').value='4'"> <?php echo $RecursosCons->RecursosCons['configuracao']; ?> </a> </li>
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
                        <?php if($_GET['ins'] == 1) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['env_config']; ?>
	                        </div>
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
			                        <option value="1" <?php if($row_rsP['tipo'] == 1) echo "selected"; ?> ><?php echo $RecursosCons->RecursosCons['geral_label']; ?></option>
			                        <option value="2" <?php if($row_rsP['tipo'] == 2) echo "selected"; ?> ><?php echo $RecursosCons->RecursosCons['newsletter']; ?></option>
			                      </select>
			                    </div>
			                  </div>
			                  <hr>
			                  <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" id="texto" name="texto"><?php echo $row_rsP['texto']; ?></textarea>
                            <!-- <p class="help-block"><?php echo $RecursosCons->RecursosCons['texto_help_bloco']; ?></p> -->
                          </div>
                        </div>   
                        <div class="form-group div_geral">
                          <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                        	<label class="col-md-2 control-label" for="texto_link"><?php echo $RecursosCons->RecursosCons['texto_link']; ?>: </label>
			                    <div class="col-md-3">
			                      <input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $row_rsP['texto_link']; ?>">
			                    </div>
			                    <div class="div_geral">
	                          <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>
	                          <div class="col-md-3">
	                            <select class="form-control select2me" name="target" id="target">
	                              <option value="0"><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
	                              <option value="_blank" <?php if($row_rsP['target'] == "_blank") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>
	                              <option value="_parent" <?php if($row_rsP['target'] == "_parent") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>
	                            </select>
	                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">
                      <div class="form-body">
                      	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 
	                        </div>
                        <?php } ?>
                        <?php if($_GET['erro'] == 1 && $_GET['tab_sel'] == 2) { ?>
			                    <div class="alert alert-danger display-show">
			                    	<button class="close" data-close="alert"></button>
			                     	<?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> 
			                   	</div>   
			                	<?php } ?> 
                        <div class="form-group">
                          <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem']; ?><br>
                            <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/banners/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/banners/".$row_rsP['imagem1'])) { ?>
                                	<a href="../../../imgs/banners/<?php echo $row_rsP['imagem1']; ?>" data-fancybox ><img src="../../../imgs/banners/<?php echo $row_rsP['imagem1']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                                <input id="upload_campo" type="file" name="img">
                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                            </div>
                            <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span> </div>
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
                    <div class="tab-pane <?php if($tab_sel==4) echo "active"; ?>" id="tab_config">
                    	<div class="form-body">
                    		<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 4) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 
	                        </div>
                        <?php } ?>
                        <div class="form-group ">
			                    <label class="col-md-2 control-label" for="tipo_cliente"><?php echo $RecursosCons->RecursosCons['tipo_cliente']; ?>: </label>
			                    <div class="col-md-3">
			                      <select class="form-control" name="tipo_cliente" id="tipo_cliente">
			                      	<option value="3" <?php if($row_rsP['tipo_cliente'] == 3) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['ambos_label']; ?></option>
			                        <option value="1" <?php if($row_rsP['tipo_cliente'] == 1) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['tipo1_label']; ?></option>
			                        <option value="2" <?php if($row_rsP['tipo_cliente'] == 2) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['tipo2_label']; ?></option>
			                      </select>
			                    </div>
			                    <label class="col-md-2 control-label" for="cliente_registo"><?php echo $RecursosCons->RecursosCons['clientes']; ?>: </label>
			                    <div class="col-md-3">
			                      <select class="form-control" name="cliente_registo" id="cliente_registo">
			                      	<option value="3" <?php if($row_rsP['cliente_registo'] == 3) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['ambos_label']; ?></option>
			                        <option value="1" <?php if($row_rsP['cliente_registo'] == 1) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['com_registo_label']; ?></option>
			                        <option value="2" <?php if($row_rsP['cliente_registo'] == 2) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['sem_registo_label']; ?></option>
			                      </select>
			                    </div>
			                  </div>
			                  <div class="form-group">
			                    <label class="col-md-2 control-label" for="timer"><?php echo $RecursosCons->RecursosCons['timer_label']; ?>: </label>
			                    <div class="col-md-2">
			                      <div class="input-group">
			                        <input type="text" class="form-control" name="timer" id="timer" value="<?php echo $row_rsP['timer']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
			                        <span class="input-group-addon">s</span>
			                      </div> 
			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['timer_help_block']; ?></p>
			                    </div>
			                  </div>
			                  <div class="form-group div_newsletter">
			                    <label class="col-md-2 control-label" for="codigo_promocional"><?php echo $RecursosCons->RecursosCons['cod_promocional']; ?>: </label>
			                    <div class="col-md-8">
			                      <select class="form-control select2me" name="codigo_promocional" id="codigo_promocional">
			                      	<option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
			                      	<?php if($totalRows_rsCodProm > 0){ 
		                      			foreach($row_rsCodProm as $codprom){ ?>
		                      				<option value="<?php echo $codprom['id']; ?>" <?php if($row_rsP['cod_prom'] == $codprom['id']) echo "selected"; ?>><?php echo $codprom['nome']." - ".$codprom['codigo']; ?></option>
			                      		<?php }
			                    		} ?>
			                      </select>
			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['cod_prom_banner_help']; ?></p>
			                    </div>
			                  </div>
			                  <hr>
			                  <div class="form-group ">
			                  	<label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['link_partilha']; ?>:</strong> </label>
			                  	<div class="col-md-8">
			                  		<input type="text" class="form-control" value="<?php echo ROOTPATH_HTTP.substr($extensao, 1)."/index.php?k=".$row_rsP['codigo']; ?>" readonly>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<script src="slideshow-popups-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	Form.init();

	if('<?php echo $row_rsP["tipo"]; ?>' == '1') {
		$('.div_geral').css('display', 'block');
	}
	else {
		$('.div_geral').css('display', 'none');
	}

	$('#tipo').on('change', function() {
    if($(this).val() == 1) {
      $('.div_geral').css('display', 'block');
    }
    else {
      $('.div_geral').css('display', 'none');
    }
  });

});
</script>
<script type="text/javascript">
CKEDITOR.replace("texto", {
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