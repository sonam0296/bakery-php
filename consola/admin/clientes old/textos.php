<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='clientes';
$menu_sub_sel='textos';

$id = 1;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_texto")) {
	$insertSQL = "UPDATE clientes_textos".$extensao." SET assunto=:assunto, texto=:texto WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':assunto', $_POST['assunto'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
	$rsInsert->execute();

	DB::close();
		
	header("Location: textos.php?alt=1");
}

$query_rsP = "SELECT assunto, texto FROM clientes_textos".$extensao." WHERE id = :id";
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_clientes']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_clientes']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="textos.php"><?php echo $RecursosCons->RecursosCons['menu_email']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_texto" name="frm_texto" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_clientes']; ?> - <?php echo $RecursosCons->RecursosCons['menu_email']; ?> </div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
	                <button type="submit" class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
	              </div>
	            </div>
	            <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                  </div>
                  <?php if($_GET['alt'] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 
                    </div>
                  <?php } ?>
                  <div class="form-group">
                  	<div class="col-md-2"></div>
                  	<div class="col-md-8"><strong><?php echo $RecursosCons->RecursosCons['email_aviso']; ?> </strong></div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="assunto"><?php echo $RecursosCons->RecursosCons['assunto_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="assunto" id="assunto" value="<?php echo $row_rsP['assunto']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                    <div class="col-md-8">
                    	<p class="help-block"><?php echo $RecursosCons->RecursosCons['email_tags']; ?></p>
                      <textarea class="form-control" id="texto" name="texto"><?php echo $row_rsP['texto']; ?></textarea>
                    </div>
                  </div>
                </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_texto" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
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
CKEDITOR.replace('texto',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	toolbar : "Basic2",
	height: "250px"
});
</script> 
</body>
<!-- END BODY -->
</html>