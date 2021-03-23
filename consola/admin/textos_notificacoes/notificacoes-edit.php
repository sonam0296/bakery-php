<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='notificacoes';
$menu_sub_sel='';

$id=$_GET['id'];
$inserido=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "notificacoes_form")) {
	$manter = $_POST['manter'];
	
	$insertSQL = "UPDATE textos_notificacoes".$extensao." SET assunto=:assunto, texto=:texto WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':assunto', $_POST['assunto'], PDO::PARAM_STR, 5); 
  $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);		
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsInsert->execute();
	DB::close();

  $query_rsLinguas = "SELECT * FROM linguas WHERE visivel=1";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $totalRows_rsLinguas = $rsLinguas->rowCount();
  DB::close();

  if($totalRows_rsLinguas > 0) {
    while($row_rsLinguas = $rsLinguas->fetch()) {
      $insertSQL = "UPDATE textos_notificacoes_".$row_rsLinguas['sufixo']." SET dias=:dias, horas=:horas WHERE id=:id";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':dias', $_POST['dias'], PDO::PARAM_INT);     
      $rsInsert->bindParam(':horas', $_POST['horas'], PDO::PARAM_INT);  
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);  
      $rsInsert->execute();
      DB::close();
    }
  }
	
  if(!$manter) 
    header("Location: notificacoes.php?alt=1");
  else 
    header("Location: notificacoes-edit.php?id=".$id."&alt=1");
}

$query_rsP = "SELECT * FROM textos_notificacoes".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['notificacoes']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           
          <li>
            <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['encomendas']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="notificacoes.php"> <?php echo $RecursosCons->RecursosCons['notificacoes']; ?> </a> 
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a>
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">     
		      <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>  
          <form id="notificacoes_form" name="notificacoes_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['enc_notificacoes']; ?> - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='notificacoes.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?> </button>
                  <!-- <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> Eliminar</a> -->
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
									<?php if($_GET['alt'] == 1) { ?>
                    <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['alt']; ?></span>
                    </div>
                  <?php } ?>
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                   <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>                  
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" disabled>
                    </div>
                  </div> 
                  <hr>
                  <?php if($row_rsP['id'] == 5 || $row_rsP['id'] == 6) { ?>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="dias"><?php echo $RecursosCons->RecursosCons['dias_label']; ?>: <span class="required"> * </span></label>
                      <div class="col-md-3">
                        <input type="number" class="form-control" name="dias" id="dias" value="<?php echo $row_rsP['dias']; ?>">
                      </div>
                      <div class="col-md-5" style="padding-top: 6px;">
                        <?php echo $RecursosCons->RecursosCons['desativar_aviso']; ?>
                      </div>
                    </div>
                    <hr>
                  <?php } 
                  else if($row_rsP['id'] == 7) { ?>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="horas"><?php echo $RecursosCons->RecursosCons['horas_label']; ?>: <span class="required"> * </span></label>
                      <div class="col-md-3">
                        <input type="number" class="form-control" name="horas" id="horas" value="<?php echo $row_rsP['horas']; ?>">
                      </div>
                      <div class="col-md-5" style="padding-top: 6px;">
                        <?php echo $RecursosCons->RecursosCons['desativar_aviso']; ?>
                      </div>
                    </div>
                    <hr>
                  <?php } ?>
                  <div class="form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <label class="label label-danger">
                        <?php if($row_rsP['id'] == 7) {
                          echo $RecursosCons->RecursosCons['tags_nome_cli'];
                        }
                        else {
                          echo $RecursosCons->RecursosCons['tags_nome_enc_total']; 
                          
                          if($row_rsP['id'] == 5 || $row_rsP['id'] == 6) {
                            echo " / ".$RecursosCons->RecursosCons['dias_label']." <strong>#dias#</strong>";
                          } 
                        } ?>
                    </div>
                  </div>    
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="assunto"><?php echo $RecursosCons->RecursosCons['assunto_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="assunto" id="assunto" value="<?php echo $row_rsP['assunto']; ?>">
                    </div>
                  </div> 
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" name="texto" id="texto"><?php echo $row_rsP['texto']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="notificacoes_form" />
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
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
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
CKEDITOR.replace('texto', {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "250px"
});
</script>
</body>
<!-- END BODY -->
</html>