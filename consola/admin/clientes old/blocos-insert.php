<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='outros_clientes_blocos';
$menu_sub_sel='';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_blocos")) {
  if($_POST['nome']!='') {
    $insertSQL = "SELECT MAX(id) FROM clientes_blocos_pt";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    
    $id = $row_rsInsert["MAX(id)"] + 1;

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();

    while($row_rsLinguas = $rsLinguas->fetch()) {
      $insertSQL = "INSERT INTO clientes_blocos_".$row_rsLinguas["sufixo"]." (id, tipo, nome, titulo, descricao, link, texto_botao, target) VALUES (:id, :tipo, :nome, :titulo, :descricao, :link, :texto_botao, :target)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 
      $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT); 
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':texto_botao', $_POST['texto_botao'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);  
      $rsInsert->execute();
    }
    
    DB::close();

    header("Location: blocos-edit.php?id=".$id."&ins=1");
  }
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<style type="text/css">
  .tipo2 {
    display: none
  }
</style>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_clientes_blocos']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_clientes']; ?></a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="blocos.php"><?php echo $RecursosCons->RecursosCons['menu_clientes_blocos']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_blocos" name="frm_blocos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_clientes_blocos']; ?> - <?php echo $RecursosCons->RecursosCons['novo_registo']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='blocos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
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
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-3">
                      <select class="form-control" name="tipo" id="tipo">
                        <option value="1"><?php echo $RecursosCons->RecursosCons['blocos_tipo1']; ?></option>
                        <option value="2"><?php echo $RecursosCons->RecursosCons['blocos_tipo2']; ?></option>
                      </select>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="descricao"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="descricao" id="descricao" value="<?php echo $_POST['descricao']; ?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $_POST['link']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>
                    <div class="col-md-3">
                      <select class="form-control" name="target" id="target">
                        <option value="0"></option>
                        <option value="_blank"><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>
                        <option value="_parent"><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>
                      </select>
                    </div>
                    <label class="col-md-2 control-label tipo2" for="texto_botao"><?php echo $RecursosCons->RecursosCons['texto_link']; ?>: </label>
                    <div class="col-md-3 tipo2">
                      <input type="text" class="form-control" name="texto_botao" id="texto_botao" value="<?php echo $_POST['texto_botao']; ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_blocos" />
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

  $('#tipo').on('change', function() {
    if($(this).val() == 2) {
      $('.tipo2').css('display', 'block');
    }
    else {
      $('.tipo2').css('display', 'none');
    }
  });
});
</script> 
</body>
<!-- END BODY -->
</html>