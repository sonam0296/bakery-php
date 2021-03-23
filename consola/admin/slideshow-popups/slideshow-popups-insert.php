<?php include_once('../inc_pages.php'); ?>
<?php //ini_set("display_errors", 1);

$menu_sel='banners';
$menu_sub_sel='popups';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_banners_h")) {
  if($_POST['nome']!='') {
    $insertSQL = "SELECT MAX(id) FROM banners_popups_pt";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    
    $id = $row_rsInsert["MAX(id)"]+1;
    
    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
    
    $datai = NULL;
    if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];
    $dataf = NULL;
    if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];

    $codigo = randomCodeNews('24', 'banners_popups_pt');
    
    while($row_rsLinguas = $rsLinguas->fetch()) {
      $insertSQL = "INSERT INTO banners_popups_".$row_rsLinguas["sufixo"]." (id, datai, dataf, nome, tipo, titulo, subtitulo, texto, link, texto_link, target, codigo) VALUES (:id, :datai, :dataf, :nome, :tipo, :titulo, :subtitulo, :texto, :link, :texto_link, :target, :codigo)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
      $rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR, 5);  
      $rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);  
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT);
      $rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':subtitulo', $_POST['subtitulo'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':codigo', $codigo, PDO::PARAM_STR, 5);
      $rsInsert->execute();
    }

    DB::close();

    alteraSessions('banners');
    
    if($_POST['tipo'] == 1) {
      header("Location: slideshow-popups-edit.php?id=".$id."&ins=1");
    }
    else {
      header("Location: slideshow-popups.php?ins=1");
    }
  }
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['banner_popups_title']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="slideshow-popups.php"><?php echo $RecursosCons->RecursosCons['banner_popups_title']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_banners_h" name="frm_banners_h" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['banner_popup_novo_registo']; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='slideshow-popups.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?></div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $_POST['datai']; ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="dataf"><?php echo $RecursosCons->RecursosCons['data_fim_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo $_POST['dataf']; ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>: </label>
                    <div class="col-md-3">
                      <select class="form-control" name="tipo" id="tipo">
                        <option value="1"><?php echo $RecursosCons->RecursosCons['geral_label']; ?></option>
                        <option value="2"><?php echo $RecursosCons->RecursosCons['newsletter']; ?></option>
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
                    <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="texto" name="texto"><?php echo $_POST['texto']; ?></textarea>
                      <!-- <p class="help-block"><?php echo $RecursosCons->RecursosCons['texto_help_bloco']; ?></p> -->
                    </div>
                  </div> 
                  <div class="form-group div_geral">
                    <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $_POST['link']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_link"><?php echo $RecursosCons->RecursosCons['texto_link']; ?>: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $_POST['texto_link']; ?>">
                    </div>
                    <div class="div_geral">
                      <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>
                      <div class="col-md-3">
                        <select class="form-control select2me" name="target" id="target">
                          <option value="0"><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                          <option value="_blank"><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>
                          <option value="_parent"><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_banners_h" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="slideshow-popups-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  Form.init();

   $('#tipo').on('change', function() {
    if($(this).val() == 1) {
      $('.div_geral').css('display', 'block');
    }
    else {
      $('.div_geral').css('display', 'none');
    }
  });
});
</script>
<script type="text/javascript">
CKEDITOR.replace("texto", {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "150px"
});
</script>
</body>
<!-- END BODY -->
</html>