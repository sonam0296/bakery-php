<?php include_once('../inc_pages.php'); ?>
<?php

$fixo = $_GET['fixo'];
$pagina = $_GET['pagina'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Páginas Fixas';

if($fixo == 0){
  $menu_sub_sel='paginas_outras';
  $nome_sel='Outras Páginas';
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "paginas_blocos_form")) {
  if($_POST['nome'] != '' && $_POST['tipo'] != '') {
    //Apenas é permitido um bloco do tipo formulário por página. Vamos verificar se já existe algum
    if($_POST['tipo'] == 5) {
      $query_rsExiste = "SELECT id FROM paginas_blocos_$lingua_consola WHERE pagina = :pagina AND tipo = 5";
      $rsExiste = DB::getInstance()->prepare($query_rsExiste);
      $rsExiste->bindParam(':pagina', $pagina, PDO::PARAM_INT);
      $rsExiste->execute();
      $totalRows_rsExiste = $rsExiste->rowCount();

      if($totalRows_rsExiste > 0) {
        header("Location: paginas-blocos-insert.php?err=1&pagina=".$pagina."&fixo=".$fixo);
        exit();
      }
    }

    $insertSQL = "SELECT MAX(id) FROM paginas_blocos_$lingua_consola";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    
    $max_id = $row_rsInsert["MAX(id)"]+1;
    
    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
    
    while($row_rsLinguas = $rsLinguas->fetch()) {       
      $insertSQL = "INSERT INTO paginas_blocos_".$row_rsLinguas["sufixo"]." (id, pagina, nome, titulo, tipo) VALUES (:max_id, :pagina, :nome, :titulo, :tipo)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':max_id', $max_id, PDO::PARAM_INT);
      $rsInsert->bindParam(':pagina', $pagina, PDO::PARAM_INT);
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT, 5);
      $rsInsert->execute();
    }

    DB::close();

    alteraSessions('paginas');
    alteraSessions('paginas_menu');
    alteraSessions('paginas_fixas');
  
    header("Location: paginas-blocos.php?env=1&pagina=".$pagina."&fixo=".$fixo);
    exit();
  }
}

$query_rsP = "SELECT * FROM paginas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $pagina, PDO::PARAM_INT, 5); 
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<style type="text/css">
  #subtipo_div {
    display: none;
  }
</style>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?> - <?php echo $RecursosCons->RecursosCons['blocos']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
            <li>
              <a href="paginas.php?fixo=<?php echo $fixo; ?>"><?php echo $RecursosCons->RecursosCons['paginas']; ?> </a>
              <i class="fa fa-angle-right"></i>
            </li>
            <li>
              <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['blocos']; ?></a>
            </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="paginas_blocos_form" name="paginas_blocos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $nome_sel; ?> - <?php echo $row_rsP['nome']; ?> - <?php echo $RecursosCons->RecursosCons['blocos']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='paginas-blocos.php?pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
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
                  <?php if($_GET['err'] == 1) { ?>
                    <div class="alert alert-danger display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['bloco_form_alert']; ?> 
                    </div>  
                  <?php } ?> 
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="tipo" name="tipo" >
                        <option value=""><?php echo $RecursosCons->RecursosCons['blocos_sel_tipo']; ?></option>
                        <option value="1"><?php echo $RecursosCons->RecursosCons['blocos_sel_textoimg']; ?></option>  
                        <option value="2"><?php echo $RecursosCons->RecursosCons['blocos_sel_texto']; ?></option>
                        <option value="3"><?php echo $RecursosCons->RecursosCons['blocos_sel_2videos']; ?></option>
                        <option value="4"><?php echo $RecursosCons->RecursosCons['blocos_sel_google_maps']; ?></option>
                        <option value="5"><?php echo $RecursosCons->RecursosCons['blocos_sel_formulario']; ?></option>
                        <option value="6"><?php echo $RecursosCons->RecursosCons['blocos_sel_ficheiros']; ?></option>
                        <option value="7"><?php echo $RecursosCons->RecursosCons['blocos_sel_timeline']; ?></option>                         
                      </select>
                    </div>
                  </div>
                  <?php /* <div id="subtipo_div" class="form-group">
                    <label class="col-md-2 control-label" for="colunas"><?php echo $RecursosCons->RecursosCons['num_colunas_label']; ?>: </label>
                    <div class="col-md-8 md-radio-inline">
                      <div class="md-radio">
                        <input id="radio1" name="colunas" value="1" checked class="md-radiobtn" type="radio">
                        <label for="radio1">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        1 </label>
                      </div>
                      <div class="md-radio">
                        <input id="radio2" name="colunas" value="2" class="md-radiobtn" type="radio">
                        <label for="radio2">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        2 </label>
                      </div>
                      <div class="md-radio">
                        <input id="radio3" name="colunas" value="3" class="md-radiobtn" type="radio">
                        <label for="radio3">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        3 </label>
                      </div>
                    </div>
                  </div> */ ?>               
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">
                    </div>
                  </div> 
                  <div id="titulo" class="form-group">
                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">
                    </div>
                  </div>                  
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="paginas_blocos_form" />
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

   /*$('#tipo').on('change', function() {
    if($('#tipo').val() == 2) 
      $('#subtipo_div').css('display', 'block');
    else 
      $('#subtipo_div').css('display', 'none');

    if($('#tipo').val() == 4)
      $('#titulo').css('display', 'none');
    else
      $('#titulo').css('display', 'block');
   });*/
});
</script> 
</body>
<!-- END BODY -->
</html>