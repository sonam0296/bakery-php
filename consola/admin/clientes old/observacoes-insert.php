<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='clientes';
$menu_sub_sel='listagem';

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "observacoes_form")) {
	if($_POST['descricao']!='') {
		$insertSQL = "SELECT MAX(id) FROM clientes_obs";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		
		$max_id = $row_rsInsert["MAX(id)"]+1;
    $data = date('Y-m-d H:i');

		$insertSQL = "INSERT INTO clientes_obs (id, id_cliente, descricao, data) VALUES (:max_id, :id_cliente, :descricao, :data)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':id_cliente', $id, PDO::PARAM_INT, 5);
		$rsInsert->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':max_id', $max_id, PDO::PARAM_INT);	
		$rsInsert->execute();

		DB::close();

		header("Location: observacoes.php?suc=1&id=".$id);
	}
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
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?> </a> <i class="fa fa-angle-right"></i> </li>
          <li>
            <a href="clietnes.php"><?php echo $RecursosCons->RecursosCons['clientes']; ?> </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="observacoes_form" name="observacoes_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-user"></i> <?php echo $RecursosCons->RecursosCons['clientes']; ?> - <?php echo $row_rsCliente['nome']; ?> - <?php echo $RecursosCons->RecursosCons['obs_nova_observacao']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='observacoes.php?id=<?php echo $id; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>                  
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="descricao"><?php echo $RecursosCons->RecursosCons['observacao']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <textarea class="form-control" rows="5" id="descricao" name="descricao"></textarea>
                    </div>
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
   User.init();
});
</script> 
</body>
<!-- END BODY -->
</html>