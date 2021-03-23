<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='clientes';
$menu_sub_sel='listagem';

$id = $_GET['id'];
$obs = $_GET['obs'];

$inserido=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "observacoes_form")) {
	if($_POST['descricao']!='')  {
		$manter = $_POST['manter'];
		
    if(isset($_GET['enc'])) {
      $query_rsUpdate = "UPDATE encomendas_obs SET descricao=:descricao WHERE id_encomenda = :id_enc AND id=:id";
      $rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
      $rsUpdate->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);
      $rsUpdate->bindParam(':id', $obs, PDO::PARAM_INT);  
      $rsUpdate->bindParam(':id_enc', $_GET['enc'], PDO::PARAM_INT);  
      $rsUpdate->execute();
    }
    else {
      $query_rsUpdate = "UPDATE clientes_obs SET descricao=:descricao WHERE id=:id";
      $rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
      $rsUpdate->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);
      $rsUpdate->bindParam(':id', $obs, PDO::PARAM_INT);  
      $rsUpdate->execute();
    }
		
		$inserido=1;

		DB::close();
		
		if(!$manter) header("Location: observacoes.php?alt=1&id=".$id);
	}
}

if(isset($_GET['enc'])) {
  $query_rsP = "SELECT * FROM encomendas_obs WHERE id_encomenda = :id_enc AND id=:id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $obs, PDO::PARAM_INT); 
  $rsP->bindParam(':id_enc', $_GET['enc'], PDO::PARAM_INT); 
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();

}
else {
  $query_rsP = "SELECT * FROM clientes_obs WHERE id=:id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $obs, PDO::PARAM_INT); 
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
}

$query_rsCliente = "SELECT nome FROM clientes WHERE id = :id";
$rsCliente = DB::getInstance()->prepare($query_rsCliente);
$rsCliente->bindParam(':id', $id, PDO::PARAM_INT);  
$rsCliente->execute();
$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCliente = $rsCliente->rowCount();


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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['clientes']; ?> <small> <?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li>
            <a href="clietnes.php"><?php echo $RecursosCons->RecursosCons['clientes']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:"> <?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?> </a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
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
              <?php if(isset($_GET['enc'])) { ?>
                <button type="button" class="btn blue" onClick="document.location='observacoes.php?rem=1&id=<?php echo $id; ?>&obs=<?php echo $obs; ?>&enc=<?php echo $_GET['enc']; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
              <?php } else { ?>
                <button type="button" class="btn blue" onClick="document.location='observacoes.php?rem=1&id=<?php echo $id; ?>&obs=<?php echo $obs; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <?php } ?>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="row">
        <div class="col-md-12">
          <form id="observacoes_form" name="observacoes_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="1">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-user"></i><?php echo $RecursosCons->RecursosCons['clientes']; ?> - <?php echo $row_rsCliente['nome']; ?> - <?php echo $RecursosCons->RecursosCons['observacoes']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='observacoes.php?id=<?php echo $id; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1'"><i class="fa fa-check-circle"></i><?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> </div>
              </div>
              <div class="form-body">
                <div class="alert alert-danger display-hide">
                  <button class="close" data-close="alert"></button>
                  <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>  
                <?php if($inserido==1) { ?>                    
                  <div class="alert alert-success">
                    <button class="close" data-close="alert"></button>
                     <?php echo $RecursosCons->RecursosCons['alt']; ?> </div>
                <?php } ?>
                <?php if(isset($_GET['enc'])) { ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label"> <?php echo $RecursosCons->RecursosCons['encomenda_num']; ?>: </label>
                    <div style="padding-top: 9px" class="col-md-6">
                      <a href="../encomendas/encomendas-edit.php?id=<?php echo $_GET['enc']; ?>"><?php echo $_GET['enc']; ?></a>
                    </div>
                  </div>
                <?php } ?>
                <div class="form-group">
                  <label class="col-md-2 control-label"> <?php echo $RecursosCons->RecursosCons['data_label']; ?>: </label>
                  <div style="padding-top: 9px" class="col-md-8">
                    <?php echo $row_rsP['data']; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label" for="descricao"> <?php echo $RecursosCons->RecursosCons['observacao']; ?>: <span class="required"> * </span></label>
                  <div class="col-md-8">
                    <textarea class="form-control" rows="5" id="descricao" name="descricao"><?php echo $row_rsP['descricao']; ?></textarea>
                  </div>
                </div> 
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="observacoes_form" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   User.init();
});
</script>
</body>
<!-- END BODY -->
</html>