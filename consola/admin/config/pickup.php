<?php include_once('../inc_pages.php'); ?>
<?php 

$inserido=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
  $insertSQL = "UPDATE pickup SET discount=:discount, texto=:texto WHERE id='1'";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':discount', $_POST['discount'], PDO::PARAM_INT);  
  $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5); 
  $rsInsert->execute();
  DB::close();

  alteraSessions('contactos');

  $inserido=1;
}

$query_rsP = "SELECT * FROM pickup WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$menu_sel='configuracao';
$menu_sub_sel='pickup';

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content">
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title">
      <?php echo $RecursosCons->RecursosCons['pickup']; ?> <small><?php echo $RecursosCons->RecursosCons['preencher_dados_contactos']; ?></small>
      </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['configuracao']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="pickup.php"><?php echo $RecursosCons->RecursosCons['pickup']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <?php if($totalRows_rsP > 0) { ?>
            <form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data" class="form-horizontal">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['pickup']; ?>
                  </div>
                  <div class="actions btn-set">
                    <button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                    <button type="reset" class="btn default"><i class="fa fa-eraser"></i>  <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>            
                  </div>
                </div>
                <div class="portlet-body"> 
                  <?php /* <div class="tabbable">
                    <ul class="nav nav-tabs">
                      <li class="nav-tab active"  onClick="window.location='contactos.php?id=<?php echo $id; ?>&tab_sel=1'"> <a href="#dados_pessoais" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"><?php echo $RecursosCons->RecursosCons['contactos_gerais']; ?></a> </li>
                      <li class="nav-tab" onClick="window.location='l_contactos.php'"> <a href="javascript:void(null)" data-toggle="tab"><?php echo $RecursosCons->RecursosCons['contactos_locais']; ?></a></li>
                    </ul>
                  </div> */ ?>
                  <div class="form-body">
                    <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
                    </div>
                    <!-- <div class="form-group">
                      <label class="col-md-2 control-label" for="gps"><?php echo $RecursosCons->RecursosCons['coord_gps_portugal']; ?>: </label>
                      <div class="col-md-4">
                        <input type="text" class="form-control" name="gps" id="gps" value="<?php echo $row_rsP['gps']; ?>">
                      </div>
                      <div class="col-md-2" style="padding-top:7px">
                        <a href="http://www.gpscoordinates.eu/determine-gps-coordinates.php" target="_blank"><?php echo $RecursosCons->RecursosCons['determinar_coord']; ?></a>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="link_google_maps"><?php echo $RecursosCons->RecursosCons['link_google_maps_label']; ?>: </label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" name="link_google_maps" id="link_google_maps" value="<?php echo $row_rsP['link_google_maps']; ?>">
                      </div>
                    </div>
                    <hr> -->
                    
                    <hr>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="telefone">Discount: </label>
                      <div class="col-md-3">
                        <input type="text" class="form-control" name="discount" id="discount" value="<?php echo $row_rsP['discount']; ?>">
                        
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>:</label>
                      <div class="col-md-8">
                        <textarea class="form-control" name="texto" id="texto" rows="4" style="resize:none"><?php echo $row_rsP['texto']; ?></textarea>
                      </div>
                    </div>                                         
                  </div>    
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="alterar" />
            </form>
          <?php } ?>    
        </div>
      </div>
      <!-- END PAGE CONTENT-->
    </div>
  </div>
  </div>
  <!-- END CONTENT -->
    <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
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