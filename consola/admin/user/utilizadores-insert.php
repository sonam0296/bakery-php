<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='utilizadores';
$menu_sub_sel='';
$tab_sel=1;
if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];
$erro_password=0;
$erro_email = 0;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_user")) {
  
  if($_POST['nome']!='' && $_POST['email']!=''){
    
    $query_rsExiste = "SELECT * FROM acesso WHERE email=:email";
    $rsExiste = DB::getInstance()->prepare($query_rsExiste);
    $rsExiste->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5); 
    $rsExiste->execute();
    $row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsExiste = $rsExiste->rowCount();
    DB::close();
    
    if($totalRows_rsExiste > 0) {
      $erro_email = 1;
    } else {
    
      $insertSQL = "SELECT MAX(id) FROM acesso";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->execute();
      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
      DB::close();
      
      $id = $row_rsInsert["MAX(id)"]+1;
      
      $insertSQL = "INSERT INTO acesso (id, nome, email, telefone, funcao, observacoes, lingua) VALUES (:id, :nome, :email, :telefone, :funcao, :observacoes, :lingua)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':funcao', $_POST['funcao'], PDO::PARAM_STR, 5);   
      $rsInsert->bindParam(':observacoes', $_POST['observacoes'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':lingua', $_POST['idioma_backoffice'], PDO::PARAM_STR, 5);
      $rsInsert->execute();
      DB::close();
      
      header("Location: utilizadores-edit.php?id=".$id."&v=2&tab_sel=3");
      
    }
  }
  
}

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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['utilizadores']; ?> <small><?php echo $RecursosCons->RecursosCons['criar_alterar_user']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <i class="fa fa-user"></i> <a href="utilizadores.php"><?php echo $RecursosCons->RecursosCons['utilizadores']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="#"><?php echo $RecursosCons->RecursosCons['inserir_user']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_user" name="frm_user" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-user"></i><?php echo $RecursosCons->RecursosCons['novo_user']; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='utilizadores.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?> : <span class="required"> * </span> </label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email"><?php echo $RecursosCons->RecursosCons['cli_email']; ?> : <span class="required"> * </span> </label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="email" id="email" value="<?php echo $_POST['email']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="idioma_backoffice"><?php echo $RecursosCons->RecursosCons['idioma_backoffice']; ?>:</label>
                    <div class="col-md-3">
                      <select class="form-control select2me" id="idioma_backoffice" name="idioma_backoffice" >
                        <?php if($consolaLG_count > 0){ ?>
                          <?php foreach ($row_rsconsolaLG as $value) { ?>
                              <option value="<?php echo $value['sufixo']; ?>" <?php if($value['sufixo']=='pt') echo "selected"; ?>><?php echo $value['nome']; ?></option>
                           <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="telefone"><?php echo $RecursosCons->RecursosCons['cli_telefone']; ?> : </label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo $_POST['telefone']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="funcao"><?php echo $RecursosCons->RecursosCons['funcao_label']; ?> : </label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="funcao" id="funcao" value="<?php echo $_POST['funcao']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="observacoes"><?php echo $RecursosCons->RecursosCons['descricao']; ?> : </label>
                    <div class="col-md-6">
                      <textarea class="form-control" rows="3" id="observacoes" name="observacoes"><?php echo $_POST['observacoes']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_user" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="utilizadores-validation.js"></script> 
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
<?php if($erro_email == 1) { ?>
<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<div class="modal fade" id="modal_existe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['informacao_label']; ?></h4>
    </div>
    <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['email_existe_insere_novo']; ?> </div>
    <div class="modal-footer">
      <button type="button" class="btn blue" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
    </div>
  </div>
  <!-- /.modal-content --> 
</div>
<!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<a id="mostra_existe" href="#modal_existe" data-toggle="modal" style="display:none">&nbsp;</a>
<script type="text/javascript">
$(document).ready(function() {
  $("#mostra_existe").click();  
});
</script>
<?php } ?>
</body>
<!-- END BODY -->
</html>