<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='contactos';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_contactos")) {
	if($_POST['nome']!='') {
		$insertSQL = "SELECT MAX(id) FROM contactos_locais_$lingua_consola";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		DB::close();
		
		$id = $row_rsInsert["MAX(id)"]+1;

		$query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->query($query_rsLinguas);
		$rsLinguas->execute();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		DB::close();
		
  	while($row_rsLinguas = $rsLinguas->fetch()) {
			$insertSQL = "INSERT INTO contactos_locais_".$row_rsLinguas["sufixo"]." (id, nome, texto, gps, link_google_maps) VALUES ('$id', :nome, :texto, :gps, :link_google_maps)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':link_google_maps', $_POST['link_google_maps'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':gps', $_POST['gps'], PDO::PARAM_STR, 5);  
      $rsInsert->execute();
      DB::close();
		}
  }

  alteraSessions('contactos');

  header("Location: l_contactos.php?env=1");
}
		
?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<style type="text/css">
 /* #link_div {
    display: none;
  }*/
</style>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['contactos']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li><a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['configuracao']; ?></a><i class="fa fa-angle-right"></i></li>
          <li> <a href="contactos.php"><?php echo $RecursosCons->RecursosCons['contactos']; ?></a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="l_contactos.php"><?php echo $RecursosCons->RecursosCons['contactos_locais']; ?></a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a></li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_contactos" name="frm_contactos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['contactos_locais']; ?> - <?php echo $RecursosCons->RecursosCons['novo_registo']; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='l_contactos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
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
                    <input type="text" class="form-control" name="nome" id="nome" value="" data-required="1">
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-md-2 control-label" for="gps"><?php echo $RecursosCons->RecursosCons['coord_gps_label']; ?>: </label>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="gps" id="gps" value="">
                  </div>
                  <div class="col-md-5" style="padding-top:7px">
                    <a href="https://www.gps-coordinates.net/" target="_blank"><?php echo $RecursosCons->RecursosCons['determinar_coord']; ?></a>
                    <strong> ou </strong>
                    <a href="https://www.latlong.net/" target="_blank"><?php echo $RecursosCons->RecursosCons['determinar_coord']; ?></a>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label" for="link_google_maps"><?php echo $RecursosCons->RecursosCons['link_google_maps_label']; ?>: </label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="link_google_maps" id="link_google_maps" value="">
                  </div>
                </div>         
                <div class="form-group">
                  <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                  <div class="col-md-8">
                    <textarea class="form-control" name="texto" id="texto" ></textarea>
                  </div>
                </div>                       
              </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_contactos"/>
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
<!-- LINGUA PORTUGUESA -->
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
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
  height: "200px"
});
</script>  
</body>
<!-- END BODY -->
</html>