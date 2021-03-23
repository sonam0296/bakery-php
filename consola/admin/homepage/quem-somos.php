<?php include_once('../inc_pages.php'); ?>
<?php ini_set('display_errors', 1);

$menu_sel='quem_somos';
$menu_sub_sel='';
$erro = 0;

$tab_sel = 1;
if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];

$id = 1;
$tamanho_imagens1 = getFillSize('Quem Somos', 'imagem1');
$tamanho_imagens2 = getFillSize('Quem Somos', 'imagem2');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_quem_somos")) {
	$manter = $_POST['manter'];
	$tab_sel = $_REQUEST['tab_sel'];

	if($tab_sel == 1) {
		// actualiza detalhes
		#$insertSQL = "UPDATE quem_somos".$extensao." SET titulo_home=:titulo, texto_home=:texto, link=:link, target=:target, texto_link=:texto_link, alignh=:alignh, alignv=:alignv  WHERE id=:id";
		$insertSQL = "UPDATE quem_somos".$extensao." SET titulo_home=:titulo, texto_home=:texto, link=:link, target=:target, texto_link=:texto_link  WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5);
		#$rsInsert->bindParam(':alignh', $_POST['alignh'], PDO::PARAM_STR, 5);
		#$rsInsert->bindParam(':alignv', $_POST['alignv'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
		$rsInsert->execute();

		DB::close();
		
		alteraSessions('quem_somos');

		header("Location: quem-somos.php?alt=1&tab_sel=1");
	}

}


$query_rsP = "SELECT * FROM quem_somos".$extensao." WHERE id = :id";
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_quem_somos']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_conteudo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="quem-somos.php"><?php echo $RecursosCons->RecursosCons['menu_quem_somos']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['editar_conteudo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_quem_somos" name="frm_quem_somos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="imghome_remover" id="imghome_remover" value="0">
            <input type="hidden" name="opcao" id="opcao" value="1">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <input type="hidden" name="opcao1" id="opcao" value="1">
            <input type="hidden" name="img_remover2" id="img_remover2" value="0">
            <input type="hidden" name="opcao2" id="opcao" value="1">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_quem_somos']; ?></div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
	              </div>
	            </div>
	            <div class="portlet-body">
	              <div class="tabbable">
	                <ul class="nav nav-tabs">
	                  <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_home']; ?> </a> </li>
	                  <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_imagens" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagem']; ?> </a> </li>
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
			                        <?php echo $RecursosCons->RecursosCons['alt']; ?>
			                    </div>
			                    <?php } ?>
			                    <?php if($_GET['env'] == 1) { ?>
			                    <div class="alert alert-success display-show">
			                    	<button class="close" data-close="alert"></button>
			                        <?php echo $RecursosCons->RecursosCons['env']; ?>
			                    </div>
			                    <?php } ?>
			                    <div class="form-group">
                        		<label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                        		<div class="col-md-8">
                          		<input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo_home']; ?>">
                        		</div>
	                        </div>
		                      <div class="form-group">
		                       	<label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
		                       	<div class="col-md-8">
		                       		<textarea class="form-control" id="texto" name="texto"><?php echo $row_rsP['texto_home']; ?></textarea>
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
	                            <select class="form-control select2me" name="target" id="target">
	                              <option value="0"></option>
	                              <option value="_blank" <?php if($row_rsP['target'] == "_blank") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>
	                              <option value="_parent" <?php if($row_rsP['target'] == "_parent") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>
	                            </select>
	                          </div>
                          	<label class="col-md-2 control-label" for="texto_link"><?php echo $RecursosCons->RecursosCons['texto_link']; ?>: </label>
			                    	<div class="col-md-3">
			                      	<input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $row_rsP['texto_link']; ?>">
			                    	</div>
                        	</div>
                        	<?php /*<div class="form-group div_imagem">
			                    	<label class="col-md-2 control-label" for="alignh"><?php echo $RecursosCons->RecursosCons['alinhar_texto_horizontal_label']; ?>: </label>
				                    <div class="col-md-3">
				                      <select class="form-control" name="alignh" id="alignh">
				                        <option value="left" <?php echo ($row_rsP['alignh']=="left")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_esquerda']; ?></option>
				                        <option value="center" <?php echo ($row_rsP['alignh']=="center")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>
				                        <option value="right" <?php echo ($row_rsP['alignh']=="right")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_direita']; ?></option>
				                      </select>
				                    </div>
				                    <label class="col-md-2 control-label" for="alignv"><?php echo $RecursosCons->RecursosCons['alinhar_texto_vertical_label']; ?>: </label>
				                    <div class="col-md-3">
				                      <select class="form-control" name="alignv" id="alignv">
				                        <option value="top" <?php echo ($row_rsP['alignv']=="top")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_topo']; ?></option>
				                        <option value="middle" <?php echo ($row_rsP['alignv']=="middle")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_meio']; ?></option>
				                        <option value="bottom" <?php echo ($row_rsP['alignv']=="bottom")?"selected":""; ?>><?php echo $RecursosCons->RecursosCons['opt_baixo']; ?></option>
				                      </select>
				                    </div>
				                	</div> */ ?>
		                    </div>
	                 		</div>
		                 	<div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_imagens">
                  			<div class="form-body">
                      	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['img_alt']; ?>
	                      	</div>
                        <?php } ?>
                        <?php if($_GET['rem'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-danger display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['img_rem']; ?> 
	                      	</div>
                        <?php } ?>
                        <?php if(isset($_GET['erro']) && $_GET['erro'] == 1) { ?>
			                	<div class="alert alert-danger display-show">
		                    	<button class="close" data-close="alert"></button>
		                    	<?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> 
		                    </div>   
	                			<?php } ?> 
                    		<div class="form-group">
                      		<label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem']; ?><br>
                        		<strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> 
                        	</label>
                      		<div class="col-md-4">
                        		<div class="fileinput fileinput-<?php if($row_rsP['imagem_home']!="" && file_exists("../../../imgs/quem_somos/".$row_rsP['imagem_home'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                          		<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                          		<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                            		<?php if($row_rsP['imagem_home']!="" && file_exists("../../../imgs/quem_somos/".$row_rsP['imagem_home'])) { ?>
                            		<a href="../../../imgs/quem_somos/<?php echo $row_rsP['imagem_home']; ?>" data-fancybox><img src="../../../imgs/quem_somos/<?php echo $row_rsP['imagem_home']; ?>"></a>
                            		<?php } ?>
                          		</div>
                          		<div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                            	<input id="upload_campo" type="file" name="img_home">
                            	</span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('imghome_remover').value='1'"><?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a>
                            	</div>
                        		</div>
                        		<div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
	                        	<script type="text/javascript">
	                        	function alterar_imagem(){
	                            document.getElementById('file_delete_home').value='';
	                        	}
	                        	function remover_imagem(){
	                            document.getElementById('file_delete_home').value='';
	                            document.getElementById('img_cont_home_vazia').style.display='block';									
	                            document.getElementById('img_cont_home').style.display='none';
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
	                </div>
	              </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_quem_somos" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
});
</script> 
<script type="text/javascript">
var areas = Array('texto', 'detalhe1', 'detalhe2');
$.each(areas, function (i, area) {
 CKEDITOR.replace(area, {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "250px"
 });
});
</script> 
<script type="text/javascript">
document.ready=carregaPreview();
</script>
</body>
<!-- END BODY -->
</html>