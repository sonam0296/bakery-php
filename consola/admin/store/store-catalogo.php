<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='ec_produtos_categorias';
$menu_sub_sel='';

$id=$_GET['id'];
$inserido = 0;
$opcao = '';

if((isset($_GET["op"])) && ($_GET["op"] == "rem_file")) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != '0') {
    $opcao = $_GET['opt'];

    $query_rsSelect = "SELECT catalogo FROM l_categorias".$extensao." WHERE id=:id";
    $rsSelect = DB::getInstance()->prepare($query_rsSelect);
    $rsSelect->bindParam(':id', $id, PDO::PARAM_INT);
    $rsSelect->execute();
    $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$row_rsLinguas = $rsLinguas->fetchAll();
		$totalRows_rsLinguas = $rsLinguas->rowCount();

    $catalogo = $row_rsSelect['catalogo'];

    if($opcao == 1) {
      $insertSQL = "UPDATE l_categorias".$extensao." SET catalogo=NULL WHERE id=:id";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);  
      $rsInsert->execute();

      $r = 0;

      //Para todas as l�nguas vamos verificar se o catalogo ainda est� a ser usado numa delas
      foreach ($row_rsLinguas as $linguas) {
        $query_rsCatalogo = "SELECT id FROM l_categorias_".$linguas["sufixo"]." WHERE catalogo=:catalogo AND id=:id";
        $rsCatalogo = DB::getInstance()->prepare($query_rsCatalogo);
        $rsCatalogo->bindParam(':catalogo', $catalogo, PDO::PARAM_STR, 5);
        $rsCatalogo->bindParam(':id', $id, PDO::PARAM_INT);
        $rsCatalogo->execute();
        $totalRows_rsCatalogo = $rsCatalogo->rowCount();
        DB::close();

        if($totalRows_rsCatalogo > 0)
          $r = 1;
      }

      //Se a vari�vel for igual a 0, significa que o cat�logo n�o � usada em mais nenhum registo e podemos remov�-lo
      if($r == 0)
        @unlink('../../../imgs/categorias/'.$catalogo);
    }
    else if($opcao == 2) {     
      foreach ($row_rsLinguas as $linguas) {
        $query_rsSelect = "SELECT * FROM l_categorias_".$linguas['sufixo']." WHERE id=:id";
        $rsSelect = DB::getInstance()->prepare($query_rsSelect);
        $rsSelect->bindParam(':id', $id, PDO::PARAM_INT);
        $rsSelect->execute();
        $totalRows_rsSelect = $rsSelect->rowCount();
        $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

        if($totalRows_rsSelect > 0)
          @unlink("../../../imgs/categorias/".$row_rsSelect['catalogo']);

        $insertSQL = "UPDATE l_categorias_".$linguas["sufixo"]." SET catalogo=NULL WHERE id=:id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 
        $rsInsert->execute();
      }   
    }

	} else header("Location: categorias-catalogo.php?id=".$id);

	DB::close();
	
	header("Location: categorias-catalogo.php?rem=1&id=".$id);
}

$query_rsP = "SELECT * FROM l_categorias".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	
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
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<!--COR-->
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['categorias']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="categorias.php"><?php echo $RecursosCons->RecursosCons['categorias']; ?> <i class="fa fa-angle-right"></i></a>
					</li>
					<li>
						<a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a>
					</li>
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
              <button type="button" class="btn blue" onClick="deleteFile(<?php echo $id; ?>)"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['categorias']; ?> - <?php echo $row_rsP['nome']; ?></div>
              <div class="form-actions actions btn-set">
                <button type="button" name="back" class="btn default" onClick="document.location='categorias.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="tabbable">
                <ul class="nav nav-tabs">
                  <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='categorias-edit.php?id=<?php echo $id; ?>&tab_sel=1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                  <li> <a id="tab_2" href="#tab_images" data-toggle="tab" onClick="document.location='categorias-edit.php?id=<?php echo $id; ?>&tab_sel=2'"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>
  								<li class="active" onClick="window.location='categorias-catalogo.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_ficheiros']; ?> </a> </li>
                  <li> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.location='categorias-edit.php?id=<?php echo $id; ?>&tab_sel=3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                </ul>
                <div class="tab-content no-space">
                  <div class="tab-pane active" id="tab_catalogo">
                    <div class="form-body">
                      <?php if(isset($_GET['ins'])&& $_GET['ins'] == 1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <?php echo $RecursosCons->RecursosCons['ins_ficheiro']; ?> </div>
                      <?php }�?>
                      <?php if(isset($_GET['rem']) && $_GET['rem'] == 1) { ?>
                      <div class="alert alert-danger display-show">
                        <button class="close" data-close="alert"></button>
                        <?php echo $RecursosCons->RecursosCons['rem_ficheiro']; ?> </div>
                      <?php }�?>
                      <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['ficheiro_pdf_label']; ?>: </label>
                        <div class="col-md-4">
                          <?php if($row_rsP["catalogo"] && file_exists("../../../imgs/categorias/".$row_rsP["catalogo"])) { ?>
                            <a class="btn blue" href="../../../imgs/categorias/<?php echo $row_rsP["catalogo"]; ?>" target="_blank"><?php echo $RecursosCons->RecursosCons['abrir_ficheiro']; ?></a> <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                          <?php } else { ?>
                          <form action="categorias-catalogo-upload.php" class="dropzone" id="my-dropzone-fich" method="post">
                            <input type="hidden" name="id_ficheiro" value="<?php echo $_GET['id']; ?>"/>
                            <input type="hidden" name="extensao" value="<?php echo $extensao; ?>"/>
                            <input type="hidden" name="opt" id='opt' value="1"/>
                            <div>
                              <button id="submete_upload_ficheiro" type="submit" class="btn green" style="cursor:pointer"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_ficheiro']; ?> </button>
                            </div>
                          </form>
                          <?php } ?>
                        </div>
                        <label class="col-md-2 control-label" style="text-align:right" for="opcao"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?> </label>
                        <div class="col-md-4">
                          <div style="margin-top: 2px" class="md-radio-list">
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
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/dropzone.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   FormValidation.init();

   $('input[name=opcao]').on('change', function() {
      if($('input[name=opcao]:checked').val() == 1)
        $('#opt').val(1);
      else if($('input[name=opcao]:checked').val() == 2)
        $('#opt').val(2);
   });

   if(document.getElementById('my-dropzone-fich')) {
	   var myDropzone2 = new Dropzone("#my-dropzone-fich", { 
		method: "post",
		maxFiles: 1,
		maxFilesize: 32,
		acceptedFiles: "application/pdf",
		autoProcessQueue: false,
		dictDefaultMessage: "<?php echo $RecursosCons->RecursosCons['defaul_msg']; ?>",
		dictFallbackMessage: "<?php echo $RecursosCons->RecursosCons['fallback_msg']; ?>",
		dictFallbackText: "<?php echo $RecursosCons->RecursosCons['dictFallBackText']; ?>",
		dictFileTooBig: "<?php echo $RecursosCons->RecursosCons['dictFileTooBig']; ?>",
		dictInvalidFileType: "<?php echo $RecursosCons->RecursosCons['dictInvalidFileType']; ?>",
		dictResponseError: "<?php echo $RecursosCons->RecursosCons['dictResponseError']; ?>",
		dictCancelUpload: "<?php echo $RecursosCons->RecursosCons['dictCancelUpload']; ?>",
		dictCancelUploadConfirmation: "<?php echo $RecursosCons->RecursosCons['dictCancelUploadConfirmation']; ?>",
		dictRemoveFile: "<?php echo $RecursosCons->RecursosCons['dictRemoveFile']; ?>",
		dictRemoveFileConfirmation: null,
		dictMaxFilesExceeded: "<?php echo $RecursosCons->RecursosCons['dictMaxFilesExceeded']; ?>",
		init: function() {
			// carregar da base de dados
			/*thisDropzone = this;
			
			$.get('catalogos-upload.php?id='+data_id, function(data) {
				
				$.each(data, function(key,value){
					 
					var mockFile = { name: value.name, size: value.size };
					 
					thisDropzone.options.addedfile.call(thisDropzone, mockFile);
		
					thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "../../../imgs/catalogos/"+value.name);
					 
				});
				 
			});*/
			this.on("addedfile", function(file) {
				// Create the remove button
				var removeButton = Dropzone.createElement("<button class='btn btn-sm btn-block'>Eliminar ficheiro</button>");
				
				// Capture the Dropzone instance as closure.
				var _this = this;
		
				// Listen to the click event
				removeButton.addEventListener("click", function(e) {
				  // Make sure the button click doesn't submit the form:
				  e.preventDefault();
				  e.stopPropagation();
		
				  // Remove the file preview.
				  _this.removeFile(file);
				  // If you want to the delete the file on the server as well,
				  // you can do the AJAX request here.
				});
		
				// Add the button to the file preview element.
				file.previewElement.appendChild(removeButton);
			});
			
			var dz = this;
			
			this.element.querySelector("#submete_upload_ficheiro").addEventListener("click", function(e) {
				e.preventDefault();
				e.stopPropagation();
				dz.processQueue();
			});
		},
		success: function(file, response){
			document.location='categorias-catalogo.php?ins=1&id='+response;
		}
	   });
   }
});

function deleteFile(id) {
  var opt = $('input[name=opcao]:checked').val();
  document.location='categorias-catalogo.php?id='+id+'&op=rem_file&opt='+opt;
}
</script>
</body>
<!-- END BODY -->
</html>
