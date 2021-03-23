<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='promocoes';
$menu_sub_sel='texto';

$id=1;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_texto")) {  
  $insertSQL = "UPDATE l_promocoes_textos".$extensao." SET titulo=:titulo, texto=:texto, texto_listagem=:texto_listagem WHERE id=:id";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5); 
  $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);   
  $rsInsert->bindParam(':texto_listagem', $_POST['texto_listagem'], PDO::PARAM_STR, 5);   
  $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 
  $rsInsert->execute();

  $datai = NULL;
  if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];
  $dataf = NULL;
  if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];

  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $totalRows_rsLinguas = $rsLinguas->rowCount();

  while($row_rsLinguas = $rsLinguas->fetch()) {
    $insertSQL = "UPDATE l_promocoes_textos_".$row_rsLinguas['sufixo']." SET datai=:datai, dataf=:dataf, pagina=:pagina WHERE id=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);   
    $rsInsert->bindParam(':pagina', $_POST['pagina'], PDO::PARAM_INT); 
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 
    $rsInsert->execute();
  }
  DB::close();

  header("Location: texto.php?alt=1");
}

$query_rsP = "SELECT * FROM l_promocoes_textos".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);  
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsPaginas = "SELECT id, nome FROM paginas_pt ORDER BY nome ASC";
$rsPaginas = DB::getInstance()->prepare($query_rsPaginas);
$rsPaginas->execute();
$totalRows_rsPaginas = $rsPaginas->rowCount();
$row_rsPaginas = $rsPaginas->fetch(PDO::FETCH_ASSOC);
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<!--COR-->
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_promocoes']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>           
          <li>
            <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_produtos']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_promocoes']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="texto.php"><?php echo $RecursosCons->RecursosCons['menu_promocoes_texto']; ?></a> 
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->      
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">     
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>  
          <form id="frm_texto" name="frm_texto" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_promocoes_texto']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></small> </button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <?php if($_GET['alt'] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>
                    </div>
                  <?php } ?>                
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $row_rsP['datai']; ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="dataf"><?php echo $RecursosCons->RecursosCons['data_fim_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo $row_rsP['dataf']; ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="pagina"><?php echo $RecursosCons->RecursosCons['pagina']; ?>: </label>
                    <div class="col-md-8">
                      <select class="form-control select2me" name="pagina" id="pagina">
                        <option value="">Selecionar...</option>
                        <?php if($totalRows_rsPaginas > 0) {
                          while($row_rsPaginas = $rsPaginas->fetch()) { ?>
                            <option value="<?php echo $row_rsPaginas['id']; ?>" <?php if($row_rsP['pagina'] == $row_rsPaginas['id']) echo "selected"; ?>><?php echo $row_rsPaginas['nome']; ?></option>
                          <?php }
                        } ?>
                      </select>
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['promocoes_aviso2']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" name="texto" id="texto"><?php echo $row_rsP['texto']; ?></textarea>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_listagem"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                    <div class="col-md-8">
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['texto_listagem_aviso']; ?></p>
                      <textarea class="form-control" name="texto_listagem" id="texto_listagem"><?php echo $row_rsP['texto_listagem']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_texto"/>
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
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features

  $('.date-picker').datepicker({
    rtl: Metronic.isRTL(),
    autoclose: true
  });
});
</script> 
<script type="text/javascript">
CKEDITOR.replace('texto', {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "200px"
});
CKEDITOR.replace('texto_listagem', {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "200px"
});
</script>
</body>
<!-- END BODY -->
</html>