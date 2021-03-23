<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='newsletter_mails';
$menu_sub_sel='';

$query_rsListas = "SELECT * FROM news_listas ORDER BY ordem ASC";
$rsListas = DB::getInstance()->prepare($query_rsListas);	
$rsListas->execute();
$totalRows_rsListas = $rsListas->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['news_page_title_emails']; ?> <small><?php echo $RecursosCons->RecursosCons['importar']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['newsletters']; ?> </a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="#"><?php echo $RecursosCons->RecursosCons['importar_emails']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['importar']; ?> </div>
              <div class="form-actions actions btn-set">
                <button type="button" name="back" class="btn default" onClick="document.location='emails.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                <button type="button" class="btn green" onClick="importa()"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['importar']; ?></button>
              </div>
            </div>
            <div class="portlet-body">
              <?php if(isset($_GET['suc']) && $_GET['suc'] == 1) { ?>
              <div class="alert alert-success">
                <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['file_import_suc']; ?></div>
              <?php } ?>
              <div class="row" style="padding: 10px 0">
                <div class="form-group">
                  <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['ficheiro_exemplo']; ?>: </label>
                  <div class="col-md-4">
                    <a href="data/exemplo.xlsx" download><?php echo $RecursosCons->RecursosCons['download']; ?></a>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="data" style="text-align:right"><?php echo $RecursosCons->RecursosCons['ficheiro']; ?> <strong>XLSX</strong>:<br>Máx: <strong>32Mb</strong> </label>
                  <div class="col-md-4">
                    <form action="emails-import-upload.php" class="dropzone" id="my-dropzone-fich" method="post">
                      <input type="hidden" name="lista" id="lista">
                      <div class="fallback">
                        <input name="file" type="file" multiple />
                      </div>
                      <button id="submete_upload_ficheiro" type="submit" class="btn green" style="display:none"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['importar']; ?></button>
                    </form>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['lista_label']; ?>: <span class="required"> * </span></label>
                  <div class="col-md-6">
                    <div id="div_listas" class="form-control height-auto">
                      <div class="scroller" style="height: 275px;" data-always-visible="1">
                        <ul class="list-unstyled">
                          <?php if($totalRows_rsListas > 0) { ?>
                            <?php while($row_rsListas = $rsListas->fetch()) { ?>
                              <li>
                                <label>
                                  <input type="checkbox" name="listas[]" value="<?php echo $row_rsListas['id']; ?>">
                                  <?php echo $row_rsListas['nome']; ?>
                                </label>
                              </li>
                            <?php } ?>
                          <?php } ?>
                        </ul>
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
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/dropzone.js"></script> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  FormValidation.init();
   
  if(document.getElementById('my-dropzone-fich')) {
	  var myDropzone2 = new Dropzone("#my-dropzone-fich", { 
		method: "post",
		maxFiles: 1,
		maxFilesize: 32,
    acceptedFiles: ".xlsx",
		autoProcessQueue: false,
		dictDefaultMessage: "Arraste o ficheiro para aqui",
		dictFallbackMessage: "O seu browser não suporta esta funcionalidade.",
		dictFallbackText: "Por favor utilize o formulário abaixo para fazer upload de ficheiros à moda antiga.",
		dictFileTooBig: "O ficheiro é muito grande ({{filesize}}MiB). Tamanho máximo: {{maxFilesize}}MiB.",
		dictInvalidFileType: "Não pode fazer upload de ficheiros deste tipo.",
		dictResponseError: "O servidor respondeu com o código {{statusCode}}.",
		dictCancelUpload: "Cancelar upload",
		dictCancelUploadConfirmation: "Tem a certeza que deseja cancelar o upload?",
		dictRemoveFile: "Eliminar ficheiro",
		dictRemoveFileConfirmation: null,
		dictMaxFilesExceeded: "Não pode carregar mais ficheiros.",
		init: function() {
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
			document.location='emails-import.php?suc='+response;
		}
	   });
   }
	
});
	
// advance validation
function importa() {
	var sel = $("input[name='listas[]']:checked").size();
	var lista = "";
	if(sel > 0) {
		$("input[name='listas[]']:checked").each(function() {
			lista += $(this).val()+",";	
		});
		document.getElementById('lista').value = lista;
		$('#div_listas').css("border","1px solid #e5e5e5");
		document.getElementById('submete_upload_ficheiro').click(); // submit the form
	} else $('#div_listas').css("border","1px solid #ebccd1");
}
</script>
</body>
<!-- END BODY -->
</html>