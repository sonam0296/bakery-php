<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='portes';
$menu_sub_sel='met_envio';

$id=$_GET['id'];
$erro=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "met_envio")) {
	$query_rsP = "SELECT imagem, imagem2 FROM met_envio".$extensao." WHERE id=:id";
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

	if($_POST['nome']) {
		$insertSQL = "UPDATE met_envio".$extensao." SET nome=:nome, descricao=:descricao, link=:link WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST["nome"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':descricao', $_POST["descricao"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':link', $_POST["link"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->execute();

		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
			@unlink('../../../imgs/carrinho/'.$row_rsP['imagem']);
						
			foreach($row_rsLinguas as $linguas) {
				$insertSQL = "UPDATE met_envio_".$linguas["sufixo"]." SET imagem = NULL WHERE id=:id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
				$rsInsert->execute();
			}
		}

		if(isset($_POST['img_remover2']) && $_POST['img_remover2']==1) {
			@unlink('../../../imgs/carrinho/'.$row_rsP['imagem2']);
			
			foreach($row_rsLinguas as $linguas) {
				$insertSQL = "UPDATE met_envio_".$linguas["sufixo"]." SET imagem2 = NULL WHERE id=:id";
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
				}
				
				$imagem=$nome_file1;
				$imagem2=$nome_file2;
				
				//IMAGEM 1
				if($_FILES['img']['name']!='') {
					if($imagem!="" && file_exists("../../../imgs/carrinho/".$imagem)) {				
						$maxW=200;
						$maxH=200;
						
						$sizes=getimagesize("../../../imgs/carrinho/".$imagem);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH) {								
							$img1=new Resize("../../../imgs/carrinho/", $imagem, $imagem, $maxW, $maxH);
							$img1->resize_image();				
						}
					}		
					
					if($row_rsP['imagem']) {
						@unlink('../../../imgs/carrinho/'.$row_rsP['imagem']);
					}
					
					foreach($row_rsLinguas as $linguas) {
						$insertSQL = "UPDATE met_envio_".$linguas["sufixo"]." SET imagem=:imagem WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
						$rsInsert->execute();
					}
				}

				//IMAGEM 2
				if($_FILES['img2']['name']!='') {
					if($imagem2!="" && file_exists("../../../imgs/carrinho/".$imagem2)) {				
						$maxW=200;
						$maxH=200;
						
						$sizes=getimagesize("../../../imgs/carrinho/".$imagem2);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH) {								
							$img1=new Resize("../../../imgs/carrinho/", $imagem2, $imagem2, $maxW, $maxH);
							$img1->resize_image();				
						}
					}		
					
					if($row_rsP['imagem2']) {
						@unlink('../../../imgs/carrinho/'.$row_rsP['imagem2']);
					}
					
					foreach($row_rsLinguas as $linguas) {
						$insertSQL = "UPDATE met_envio_".$linguas["sufixo"]." SET imagem2=:imagem2 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem2', $imagem2, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
						$rsInsert->execute();
					}
				}
			}
		}
		
		DB::close();

		if($erro == 1)
			header("Location: p_met_envio-edit.php?id=".$id."&erro=1");
		else
			header("Location: p_met_envio-edit.php?id=".$id."&alt=1");
	}
}

$query_rsP = "SELECT * FROM met_envio".$extensao." WHERE id=:id";
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
              <button type="button" class="btn blue" onClick="document.location='p_met_envio.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['portes_met_envio_page_title']; ?> </h3>
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
          <form id="met_envio" name="met_envio" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <input type="hidden" name="img_remover2" id="img_remover2" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $row_rsP["nome"]; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='p_met_envio.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <!-- <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> -->
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <?php if($_GET["ins"] == 1) { ?>
	                  <div class="alert alert-success display-show">
	                    <button class="close" data-close="alert"></button>
	                    <span> <?php echo $RecursosCons->RecursosCons['ins']; ?> </span> 
	                  </div>
                  <?php } ?>
                  <?php if($_GET["alt"] == 1) { ?>
	                  <div class="alert alert-success display-show">
	                    <button class="close" data-close="alert"></button>
	                    <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 
	                  </div>
                  <?php } ?>
                  <?php if($_GET['erro'] == 1) { ?>
                    <div class="alert alert-danger display-show">
                    	<button class="close" data-close="alert"></button>
                     	<?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> 
                    </div>   
                	<?php } ?> 
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><strong><?php echo $RecursosCons->RecursosCons['nome_label']; ?>:</strong> <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="descricao"><strong><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>:</strong> </label>
                    <div class="col-md-8">
                      <textarea class="form-control" name="descricao" id="descricao" style="height:70px;"><?php echo $row_rsP['descricao']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="link"><strong><?php echo $RecursosCons->RecursosCons['link_label']; ?>:</strong> </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['imagem_carrinho']; ?>: <br>200 * 200 px</strong></label>
                    <div class="col-md-8">
                      <div class="fileinput fileinput-<?php if($row_rsP['imagem']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                          <?php if($row_rsP['imagem']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem'])) { ?>
                          	<a href="../../../imgs/carrinho/<?php echo $row_rsP['imagem']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/carrinho/<?php echo $row_rsP['imagem']; ?>"></a>
                          <?php } ?>
                        </div>
                        <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                          <input id="upload_campo" type="file" name="img">
                          </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
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
                  <div class="form-group" style="padding-top: 20px;">
                    <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['imagem_footer']; ?>:<br>
                      200 * 200 px</strong></label>
                    <div class="col-md-8">
                      <div class="fileinput fileinput-<?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                          <?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/carrinho/".$row_rsP['imagem2'])) { ?>
                          <a href="../../../imgs/carrinho/<?php echo $row_rsP['imagem2']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/carrinho/<?php echo $row_rsP['imagem2']; ?>"></a>
                          <?php } ?>
                        </div>
                        <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                          <input id="upload_campo" type="file" name="img2">
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
									    </script> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="met_envio" />
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
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script type="text/javascript">
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	FormValidation.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>