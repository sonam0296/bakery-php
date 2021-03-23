<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_conteudos';
$menu_sub_sel='';

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_conteudo_blocos")) {
	$manter = $_POST['manter'];
	
	if($_POST['nome']!='') {	
    $espacamento = 0;
    if(isset($_POST['espacamento'])) {
      $espacamento = 1;
    }

		$insertSQL = "UPDATE news_temas SET nome=:nome, titulo=:titulo, espacamento=:espacamento WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':espacamento', $espacamento, PDO::PARAM_INT);
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->execute();
		DB::close();

		if(!$manter) 
      header("Location: conteudos.php?alt=1");
    else
      header("Location: conteudos-blocos-edit.php?id=".$id."&alt=1");
	}
}

$query_rsP = "SELECT * FROM news_temas WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsCont = "SELECT * FROM news_conteudo WHERE id=:id";
$rsCont = DB::getInstance()->prepare($query_rsCont);
$rsCont->bindParam(':id', $row_rsP['conteudo'], PDO::PARAM_INT);
$rsCont->execute();
$row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCont = $rsCont->rowCount();
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
      <h3 class="page-title"> Newsletter » <?php echo $row_rsCont["nome"]; ?> » Blocos <small>alterar registo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos.php">Conteúdos</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos-blocos.php?id=<?php echo $row_rsP['conteudo']; ?>"> Blocos </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title">Eliminar registo</h4>
            </div>
            <div class="modal-body"> Deseja eliminar este registo? </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='conteudos-blocos.php?rem=1&id=<?php echo $row_rsP["id"]; ?>&id_cont=<?php echo $row_rsP["conteudo"]; ?>'">Ok</button>
              <button type="button" class="btn default" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_conteudo_blocos" name="frm_conteudo_blocos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - <?php echo $row_rsCont["nome"]; ?> - Blocos - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='conteudos-blocos.php?id=<?php echo $row_rsP["conteudo"]; ?>'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> Guardar e manter na página</button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> Eliminar</a> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="active"> <a href="#tab_general" data-toggle="tab"> Detalhe </a> </li>
                    <li > <a href="#tab_produtos" data-toggle="tab" onClick="document.location='conteudos-produtos.php?id=<?php echo $_GET['id']; ?>'"> Conteúdos </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_general">
                      <div class="form-body">
                        <?php if($_GET['alt'] == 1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> Registo alterado com sucesso. </span> 
                          </div>
                        <?php } ?>
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          Preencha todos os campos obrigatórios. 
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nome">Nome: <span class="required"> * </span></label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo">Titulo: </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="tipo">Tipo: <span class="required"> * </span></label>
                          <div class="col-md-6">
                            <select class="form-control" id="tipo" name="tipo" disabled>
                              <option value="">Selecionar...</option>
                              <option value="1" <?php if($row_rsP["tipo"] == 1) { ?>selected<?php } ?>>Produtos</option>
                              <option value="2" <?php if($row_rsP["tipo"] == 2) { ?>selected<?php } ?>>Texto e/ou Imagem</option>
                            </select>
                          </div>
                        </div>
                        <!-- <div class="form-group">
                          <label class="col-md-2 control-label" for="espacamento"><?php echo $RecursosCons->RecursosCons['espacamento_label']; ?>: </label>
                          <div class="col-md-8" style="padding-top: 8px;">
                            <input type="checkbox" class="form-control" name="espacamento" id="espacamento" value="1" <?php if($row_rsP['espacamento'] == 1) echo "checked"; ?>/>
                            <p class="help-block"><?php echo $RecursosCons->RecursosCons['espacamento_txt']; ?></p>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_conteudo_blocos" />
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