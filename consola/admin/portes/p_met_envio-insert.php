<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='portes';
$menu_sub_sel='met_envio';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "met_envio")) {
	if($_POST['nome']!='') {
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		
		$insertSQL = "SELECT MAX(id) FROM met_envio_$lingua_consola";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		
		$max_id = $row_rsInsert["MAX(id)"]+1;
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
			$insertSQL = "INSERT INTO met_envio_".$row_rsLinguas["sufixo"]." (id, nome, descricao, link) VALUES (:id, :nome, :descricao, :link)";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':nome', $_POST["nome"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':descricao', $_POST["descricao"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':link', $_POST["link"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT, 5);	
			$rsInsert->execute();
		}

    DB::close();
		
		header("Location: p_met_envio-edit.php?id=".$max_id."&ins=1");
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['portes_met_envio_page_title']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="met_envio" name="met_envio" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['novo_registo']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='p_met_envio.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><strong><?php echo $RecursosCons->RecursosCons['nome_label']; ?>:</strong> <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="descricao"><strong><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>:</strong> </label>
                    <div class="col-md-8">
                      <textarea class="form-control" name="descricao" id="descricao" style="height:70px;"><?php echo $row_rsP['descricao']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="link"><strong><?php echo $RecursosCons->RecursosCons['link_label']; ?>:</strong> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="met_envio" />
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