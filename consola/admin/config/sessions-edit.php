<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='sessions';

if($row_rsUser['username'] != 'netg') {
  header("Location: ../index.php");
  exit();
}

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_sessions")) {
  $manter = $_POST['manter'];
  
  $query_rsP = "UPDATE config_sessions SET query=:query, query2=:query2, query3=:query3, refresh=NOW() WHERE id = '$id'";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':query', $_POST['query'], PDO::PARAM_STR, 5);
  $rsP->bindParam(':query2', $_POST['query2'], PDO::PARAM_STR, 5);
  $rsP->bindParam(':query3', $_POST['query3'], PDO::PARAM_STR, 5);
  $rsP->execute();
  DB::close();

  if(!$manter) {
    header("Location: sessions.php?alt=1");
  }
  else {
    header("Location: sessions-edit.php?id=".$id."&alt=1");
  }
}

$query_rsP = "SELECT * FROM config_sessions WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);   
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

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
      <h3 class="page-title"><?php echo $RecursosCons->RecursosCons['sessions']; ?> <small>Gestão de todas as Sessions</small></h3>
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
            <a href="sessions.php"><?php echo $RecursosCons->RecursosCons['sessions']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_sessions" name="frm_sessions" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-code"></i><?php echo $RecursosCons->RecursosCons['sessions']; ?> - <?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='sessions.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>            
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>    
                  <?php if($_GET['alt'] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="refresh"><?php echo $RecursosCons->RecursosCons['ultimo_update_label']; ?>: </label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" name="refresh" id="refresh" value="<?php echo $row_rsP['refresh']; ?>" disabled>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="query"><?php echo $RecursosCons->RecursosCons['query_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="query" name="query" rows="8"><?php echo $row_rsP['query']; ?></textarea>
                    </div> 
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="query2"><?php echo $RecursosCons->RecursosCons['query_2_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="query2" name="query2" rows="8"><?php echo $row_rsP['query2']; ?></textarea>
                    </div> 
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="query3"><?php echo $RecursosCons->RecursosCons['query_3_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="query3" name="query3" rows="8"><?php echo $row_rsP['query3']; ?></textarea>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_sessions" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>