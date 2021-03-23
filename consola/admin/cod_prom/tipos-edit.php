<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='outros_promo';
$menu_sub_sel='tipos';

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_tipo")) {
	$manter = $_POST['manter'];

	$insertSQL = "UPDATE codigos_promocionais_tipos SET dias=:dias, validade=:validade, desconto=:desconto, tipo_desconto=:tipo_desconto, valor_minimo=:valor_minimo WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':dias', $_POST['dias'], PDO::PARAM_INT);	
	$rsInsert->bindParam(':validade', $_POST['validade'], PDO::PARAM_INT);	
	$rsInsert->bindParam(':desconto', $_POST['desconto'], PDO::PARAM_STR, 5);	
  $rsInsert->bindParam(':tipo_desconto', $_POST['radio_desconto'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':valor_minimo', $_POST['valor_minimo'], PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
	$rsInsert->execute();
	DB::close();

  $insertSQL = "UPDATE codigos_promocionais_tipos_textos".$extensao." SET assunto=:assunto, texto=:texto WHERE tipo=:tipo";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':assunto', $_POST['assunto'], PDO::PARAM_STR, 5);  
  $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':tipo', $id, PDO::PARAM_INT);
  $rsInsert->execute();
  DB::close();
		
	if(!$manter) {
    header("Location: tipos.php?alt=1");
  }
  else {
    header("Location: tipos-edit.php?id=".$id."&alt=1"); 
  }
}

$query_rsP = "SELECT * FROM codigos_promocionais_tipos WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsP2 = "SELECT * FROM codigos_promocionais_tipos_textos".$extensao." WHERE tipo = :tipo";
$rsP2 = DB::getInstance()->prepare($query_rsP2);
$rsP2->bindParam(':tipo', $id, PDO::PARAM_INT);  
$rsP2->execute();
$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP2 = $rsP2->rowCount();
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
      <h3 class="page-title"> Tipos <small>editar registo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:;">Vales de Desconto </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="tipos.php">Tipos </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:;">Editar registo </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="form_tipo" name="form_tipo" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Tipos - <?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='tipos.php'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button>
                  <button type="submit" class="btn green to-hide" onClick="document.getElementById('manter').value='1'"><i class="fa fa-check-circle"></i> Guardar e manter na página</button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    Preencha todos os campos obrigatórios. 
                  </div>  
                  <?php if($_GET['alt'] == 1) { ?>                    
                    <div class="alert alert-success">
                      <button class="close" data-close="alert"></button>
                      Dados alterados com sucesso. 
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label">Nome: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" value="<?php echo $row_rsP['nome']; ?>" disabled>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <?php if($id != 2) { ?>
                      <label class="col-md-2 control-label" for="dias">Nº dias: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" id="dias" name="dias" class="form-control" value="<?php echo $row_rsP['dias']; ?>">
                          <span class="input-group-addon">dias</span>
                        </div>
                      </div>
                    <?php } ?>
                    <label class="col-md-2 control-label" for="validade">Validade: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" id="validade" name="validade" class="form-control" value="<?php echo $row_rsP['validade']; ?>">
                        <span class="input-group-addon">dias</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="desconto">Desconto: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="desconto" id="desconto" value="<?php echo $row_rsP['desconto']; ?>" data-required="1" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span id="span_desconto" class="input-group-addon"><?php if($row_rsP['tipo_desconto'] == 1) { echo "%"; } else { echo "£"; } ?></span>
                      </div> 
                    </div>
                    <div class="col-md-2 md-radio-inline">
                      <div class="md-radio">
                        <input type="radio" id="desc_perc" name="radio_desconto" class="md-radiobtn" value="1" <?php if($row_rsP['tipo_desconto'] == 1) { echo "checked"; } ?>>
                        <label for="desc_perc">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        % </label>
                      </div>
                      <div class="md-radio">
                        <input type="radio" id="desc_preco" name="radio_desconto" class="md-radiobtn" value="2" <?php if($row_rsP['tipo_desconto'] == 2) { echo "checked"; } ?>>
                        <label for="desc_preco">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                        £ </label>
                      </div>
                    </div>
                  </div> 
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="valor_minimo">Compra mínima: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="valor_minimo" id="valor_minimo" value="<?php echo $row_rsP['valor_minimo']; ?>" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon">£</span>
                      </div> 
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="assunto">Assunto: </label>
                    <div class="col-md-8">
                      <?php if($id != 2) { ?><p class="help-block">TAGS: Dias: <strong>#cdias#</strong></p><?php } ?>
                      <input type="text" class="form-control" name="assunto" id="assunto" value="<?php echo $row_rsP2['assunto']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto">Texto: </label>
                    <div class="col-md-8">
                      <p class="help-block">TAGS: Cliente: <strong>#cnome#</strong> / <?php if($id != 2) { ?>Dias: <strong>#cdias#</strong> /<?php } ?> Desconto: <strong>#cdesconto#</strong> / Valor Min. Enc.: <strong>#cminimo#</strong></p>
                      <textarea class="form-control" name="texto" id="texto"><?php echo $row_rsP2['texto']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="form_tipo" />
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
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features

  $('#desc_perc').on('change', function() {
    if($('#desc_perc').is(':checked')) 
      $('#span_desconto').text('%');
    else
      $('#span_desconto').text('£');
  });

  $('#desc_preco').on('change', function() {
    if($('#desc_preco').is(':checked')) 
      $('#span_desconto').text('£');
    else
      $('#span_desconto').text('%');
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
  height: '200px'
});
</script>
</body>
<!-- END BODY -->
</html>