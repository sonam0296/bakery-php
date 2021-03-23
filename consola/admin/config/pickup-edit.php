<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='configuracao';
$menu_sub_sel='pickupdate';

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_codigo")) {
    $manter = $_POST['manter'];
    $title = $_POST['title'];
    $start_event = $_POST["datai"];
    $datai = NULL;
    if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];
    $dataf = NULL;
    if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];
    
    $insertSQL = "UPDATE events SET title='".$title."', start_event='".$start_event."' WHERE id=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
    $rsInsert->execute();

    $id_marca = 0;
    if($_POST['marca'] > 0 && $_POST['marca'] != '') 
      $id_marca = $_POST['marca'];

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
    
    while($row_rsLinguas = $rsLinguas->fetch()) {
      $insertSQL = "UPDATE events SET title='".$title."', start_event='".$start_event."' WHERE id=:id";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
      $rsInsert->execute();
    }

    DB::close();
    
    if(!$manter)
      header('Location: pickupshow.php?alt=1');
    else
      header('Location: pickup-edit.php?id='.$id.'&alt=1');
  
}

$query_rsP = "SELECT * FROM events WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);  
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsCategorias = "SELECT id, nome FROM l_categorias_en WHERE cat_mae = 0 ORDER BY nome ASC";
$rsCategorias = DB::getInstance()->prepare($query_rsCategorias);
$rsCategorias->execute();
$totalRows_rsCategorias = $rsCategorias->rowCount();
$row_rsCategorias = $rsCategorias->fetchAll();

$query_rsMarcas = "SELECT id, nome FROM l_marcas_pt ORDER BY nome ASC";
$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
$rsMarcas->execute();
$totalRows_rsMarcas = $rsMarcas->rowCount();
$row_rsMarcas = $rsMarcas->fetchAll();

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
      <h3 class="page-title"> PickUp Date <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_produtos']; ?> </a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="pickupshow.php">PickUp Date </a> <i class="fa fa-angle-right"></i></li>
        
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='promocoes.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>  
          <form id="form_codigo" name="form_codigo" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>PickUp Date - <?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='pickupdate.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?>
                  </div>  
                  <?php if($_GET['alt'] == 1) { ?>                    
                    <div class="alert alert-success">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $row_rsP['start_event']; ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                  </div> 
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="title"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="title" id="title" value="<?php echo $row_rsP['title']; ?>">
                    </div>
                  </div>
                                    
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="form_codigo" />
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

  carregaProdutos('<?php echo $row_rsP["id_categoria"]; ?>', '<?php echo $row_rsP["id_marca"]; ?>');
  });

function carregaProdutos(categoria, marca) {
  $.post("promocoes-rpc.php", {op:"carregaProdutos", categoria:categoria, marca:marca, id:'<?php echo $row_rsP["id_peca"]; ?>'}, function(data){
    document.getElementById('div_produtos').innerHTML=data;  
    $('#produto').select2();                
  });
}
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
</script>
</body>
<!-- END BODY -->
</html>