<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_conteudos';
$menu_sub_sel='';

$id_conteudo = $_GET['id'];

$query_rsCont = "SELECT * FROM news_conteudo WHERE id=:id";
$rsCont = DB::getInstance()->prepare($query_rsCont);
$rsCont->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsCont->execute();
$row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCont = $rsCont->rowCount();
DB::close();

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_conteudo_blocos")) {
	if($_POST['nome']!='' && $_POST['tipo']!="") {
		$insertSQL = "SELECT MAX(id) FROM news_temas";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		DB::close();
		
		$max_id = $row_rsInsert["MAX(id)"] + 1;

    $espacamento = 0;
    if(isset($_POST['espacamento'])) {
      $espacamento = 1;
    }
		
		$insertSQL = "INSERT INTO news_temas (id, conteudo, nome, titulo, tipo, espacamento) VALUES ('$max_id', :id_conteudo, :nome, :titulo, :tipo, :espacamento)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id_conteudo', $_GET['id'], PDO::PARAM_INT);	
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT);	
    $rsInsert->bindParam(':espacamento', $espacamento, PDO::PARAM_INT);
		$rsInsert->execute();
		DB::close();
		
		header("Location: conteudos-blocos.php?id=".$id_conteudo."&env=1");
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
      <h3 class="page-title"> Newsletter » <?php echo $row_rsCont["nome"]; ?> » Blocos <small>criar novo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos-edit.php?id=<?php echo $_GET['id']; ?>"> <?php echo $row_rsCont["nome"]; ?> </a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos-blocos.php?id=<?php echo $_GET['id']; ?>"> Blocos </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_conteudo_blocos" name="frm_conteudo_blocos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - <?php echo $row_rsCont["nome"]; ?> - Blocos - Novo registo</div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='conteudos-blocos.php?id=<?php echo $_GET['id']; ?>'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    Preencha todos os campos obrigatórios. 
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome">Nome: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="titulo">Titulo: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="tipo">Tipo: <span class="required"> * </span></label>
                    <div class="col-md-4">
                      <select class="form-control select2me" id="tipo" name="tipo">
                        <option value="">Selecionar...</option>
                        <option value="1">Produtos</option>
                        <option value="2">Texto e/ou Imagem</option>
                      </select>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label class="col-md-2 control-label" for="espacamento"><?php echo $RecursosCons->RecursosCons['espacamento_label']; ?>: </label>
                    <div class="col-md-8" style="padding-top: 8px;">
                      <input type="checkbox" class="form-control" name="espacamento" id="espacamento" value="1" checked/>
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['espacamento_txt']; ?></p>
                    </div>
                  </div>  -->                 
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