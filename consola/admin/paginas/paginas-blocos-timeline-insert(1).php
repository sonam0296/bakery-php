<?php include_once('../inc_pages.php'); ?>
<?php

$id_bloco = $_GET['id_bloco'];
$fixo = $_GET['fixo'];
$pagina = $_GET['pagina'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Paginas Fixas';

if($fixo == 0) {
  $menu_sub_sel='paginas_outras';
  $nome_sel='Outras Páginas';
}

$query_rsTotal = "SELECT id FROM paginas_blocos_timeline_pt WHERE bloco = :id_bloco";
$rsTotal = DB::getInstance()->prepare($query_rsTotal);
$rsTotal->bindParam(':id_bloco', $id_bloco, PDO::PARAM_INT);  
$rsTotal->execute();
$row_rsTotal = $rsTotal->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTotal = $rsTotal->rowCount();

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_bloco_timeline")) {
	if($_POST['nome']!='') {
		$insertSQL = "SELECT MAX(id) FROM paginas_blocos_timeline_pt";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		
		$id = $row_rsInsert["MAX(id)"]+1;
		
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
									
			$insertSQL = "INSERT INTO paginas_blocos_timeline_".$row_rsLinguas["sufixo"]." (id, bloco, nome, titulo, texto, ano) VALUES (:id, :bloco, :nome, :titulo, :texto, :ano)";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
      $rsInsert->bindParam(':bloco', $id_bloco, PDO::PARAM_INT);
      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR, 5);
			$rsInsert->execute();
		}

    DB::close();
		
		//header("Location: paginas-blocos-timeline-edit.php?id=".$id."&id_bloco=".$id_bloco."&fixo=".$fixo."&pagina=".$pagina."&env=1");
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?> - <?php echo $RecursosCons->RecursosCons['blocos']; ?> - <?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li>
            <a href="paginas.php?fixo=<?php echo $fixo; ?>"> <?php echo $RecursosCons->RecursosCons['paginas']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="paginas-edit.php?fixo=<?php echo $fixo; ?>&id=<?php echo $pagina; ?>"> <?php echo $RecursosCons->RecursosCons['blocos']; ?> </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:;"> <?php echo $RecursosCons->RecursosCons['blocos_sel_timeline']; ?> </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li> 
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a> 
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_bloco_timeline" name="frm_bloco_timeline" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['blocos_sel_timeline']." - ".$RecursosCons->RecursosCons['novo_registo']; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='paginas-blocos-timeline.php?fixo=<?php echo $fixo; ?>&pagina=<?php echo $pagina; ?>&id_bloco=<?php echo $id_bloco; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="ano"><?php echo $RecursosCons->RecursosCons['ano_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="ano" id="ano" value="<?php echo $_POST['ano']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label id="texto1_label" class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                    <div class="col-md-8">
                       <textarea class="form-control" name="texto" id="texto" style="resize:none;height:250px"><?php echo $row_rsP2['texto']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_bloco_timeline" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>
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
<script type="text/javascript">
var wordCountConf1 = {
  showParagraphs: false,
  showWordCount: false,
  showCharCount: true,
  countSpacesAsChars: true,
  countHTML: false,
  maxWordCount: -1,
  maxCharCount: 150
}

CKEDITOR.replace('texto',
{
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