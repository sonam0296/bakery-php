<?php include_once('../inc_pages.php'); ?>
<?php 

$inserido=0;
$erro_password=0;
$tab_sel=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	
	
	$cor=$_POST['cor'];	
	$cor2=$_POST['cor2'];
		
	$insertSQL = "UPDATE news_cores SET cor=:cor, cor2=:cor2 WHERE id='1'";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':cor', $cor, PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':cor2', $cor2, PDO::PARAM_STR, 5);	
	$rsInsert->execute();
	DB::close();
	
	$inserido=1;
		
}


$query_rsP = "SELECT * FROM news_cores WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();


$menu_sel='configuracao';
$menu_sub_sel='cores_news';

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['cores_newsletter']; ?></h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php if($totalRows_rsP>0){ ?>
          <form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data" class="form-horizontal">
          <input type="hidden" name="img_remover1" id="img_remover1" value="0">
          <input type="hidden" name="img_remover2" id="img_remover2" value="0">
          <input type="hidden" name="img_remover3" id="img_remover2" value="0">
          <input type="hidden" name="img_remover4" id="img_remover2" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-desktop"></i><?php echo $RecursosCons->RecursosCons['cores_newsletter']; ?> </div>
                <div class="actions btn-set">
                  <button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['config_alt']; ?> </div>
                  
                  <div class="row">
                  	<div class="col-md-6">
                    	<div class="form-group">
                                <label class="control-label col-md-4"><?php echo $RecursosCons->RecursosCons['cor_topo_label']; ?></label>
                                <div class="col-md-8">
                                    <input class="colorpicker-default form-control" name="cor" id="cor" value="<?php echo $row_rsP['cor']; ?>" type="text">
                                </div>
                            </div>	
                        
                    </div>
                    <div class="col-md-6">
                    	<div class="form-group">
                            <label class="control-label col-md-4"><?php echo $RecursosCons->RecursosCons['cor_footer_label']; ?></label>
                            <div class="col-md-8">
                                <input class="colorpicker-default form-control" name="cor2" id="cor2" value="<?php echo $row_rsP['cor2']; ?>" type="text">
                            </div>
                        </div>	
                         
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/components-pickers.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS --> 
<?php
$ip=$_SERVER['REMOTE_ADDR'];

if($ip==""){
	$ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
}
?>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  ComponentsPickers.init();
});
function addRemoteAddr(){
var length = $('input[name=ips]').attr('value').length;
if (length > 0)
$('input[name=ips]').attr('value',$('input[name=ips]').attr('value') +',<?php echo $ip; ?>');
else
$('input[name=ips]').attr('value','<?php echo $ip; ?>');
}
</script>
</body>
<!-- END BODY -->
</html>