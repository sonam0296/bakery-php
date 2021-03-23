<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='redirecionamentos_301';



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "redirecionamentos")) {
  
  if($_POST['url_old']!='' && $_POST['url_new']!=''){
    
    $insertSQL = "SELECT MAX(id) FROM redirects_301";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    DB::close();
    
    $max_id = $row_rsInsert["MAX(id)"]+1;
        
    $insertSQL = "INSERT INTO redirects_301 (id, url_old, url_new, lang, data) VALUES ('$max_id', :url_old, :url_new, :lang, now())";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':url_old', $_POST['url_old'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':url_new', $_POST['url_new'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':lang', $_POST['lang'], PDO::PARAM_STR, 5);     
    $rsInsert->execute();
    DB::close();
  
    header("Location: redirecionamentos_301.php?env=1");
  }
  
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['redirec_301']; ?> <small><?php echo $RecursosCons->RecursosCons['gestao_redirec']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
            <li>
                <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['configuracao']; ?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="redirecionamentos_301.php"><?php echo $RecursosCons->RecursosCons['redirecs_301']; ?></a>
            </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="redirecionamentos" name="redirecionamentos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Novo Redirecionamento</div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='redirecionamentos_301.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>                  
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="url_old"><?php echo $RecursosCons->RecursosCons['url_antigo_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="url_old" id="url_old" value="<?php echo $_POST['url_old']; ?>">
                    </div>
                  </div>              
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="url_new"><?php echo $RecursosCons->RecursosCons['url_novo_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="url_new" id="url_new" value="<?php echo $_POST['url_new']; ?>">
                    </div>
                  </div> 
                  <?php
                    $query_rsLg = "SELECT * FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC";
          $rsLg = DB::getInstance()->query($query_rsLg);
          $totalRows_rsLg = $rsLg->rowCount();
          DB::close();
          
          if($totalRows_rsLg>0){
                  ?>             
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="url_new"><?php echo $RecursosCons->RecursosCons['lingua_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-6">
                      <div class="md-radio-inline">
                      <?php $cont=0; while($row_rsLg = $rsLg->fetch()) { $cont++; ?>
                        <div class="md-radio" style="text-transform:uppercase">
                            <input type="radio" id="lang<?php echo $cont; ?>" name="lang" class="md-radiobtn" value="<?php echo $row_rsLg['sufixo']; ?>" <?php if($cont==1) echo "checked"; ?>>
                            <label for="lang<?php echo $cont; ?>">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            <?php echo $row_rsLg['sufixo']; ?> </label>
                        </div>
                    <?php } ?>    
                    </div>
                    
                    </div>
                  </div>
                  <?php } else { ?>
                      <?php
              $query_rsLg = "SELECT * FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC";
              $rsLg = DB::getInstance()->prepare($query_rsLg);
              $rsLg->execute();
              $row_rsLg = $rsLg->fetch(PDO::FETCH_ASSOC);
              
              $rsLg = DB::getInstance()->query($query_rsLg);
              $totalRows_rsLg = $rsLg->rowCount();
              DB::close();
            ?>
                      <input type="hidden" class="form-control" name="lang" id="lang" value="<?php echo $row_rsLg['sufixo']; ?>">
                  <?php } ?>                  
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="redirecionamentos" />
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
});
</script> 
</body>
<!-- END BODY -->
</html>