<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='ec_produtos_marcas';
$menu_sub_sel='';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "marcas_form")) {
	if($_POST['nome'] != '') {
    $insertSQL = "SELECT MAX(id) FROM l_marcas_pt";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    DB::close();
    
    $max_id = $row_rsInsert["MAX(id)"] + 1;
    
    $query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
    DB::close();
    
    while($row_rsLinguas = $rsLinguas->fetch()) {
      $nome_url = strtolower(verifica_nome($_POST['nome']));
      
      $query_rsProc = "SELECT id FROM l_marcas_".$row_rsLinguas['sufixo']." WHERE url LIKE :url AND id!=:id";
      $rsProc = DB::getInstance()->prepare($query_rsProc);
      $rsProc->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);
      $rsProc->bindParam(':id', $max_id, PDO::PARAM_INT);
      $rsProc->execute();
      $totalRows_rsProc = $rsProc->rowCount();
      
      if($totalRows_rsProc > 0) {
        $nome_url = $nome_url."-".$max_id;
      }

      $insertSQL = "INSERT INTO l_marcas_".$row_rsLinguas["sufixo"]." (id, nome, url, title) VALUES (:id, :nome, :url, :title)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':title', $_POST['nome'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);  
      $rsInsert->execute();
    }

    DB::close();
    
    header("Location: marcas-edit.php?v=2&tab_sel=2&id=".$max_id);
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['marcas']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li>
            <a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?> <i class="fa fa-angle-right"></i></a>
          </li>
          <li>
            <a href="marcas.php"><?php echo $RecursosCons->RecursosCons['marcas']; ?> <i class="fa fa-angle-right"></i></a>
          </li>
          <li>
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?> </a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="marcas_form" name="marcas_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['marcas_novo_registo']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='marcas.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
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
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">
                    </div>
                  </div>         
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="marcas_form" />
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