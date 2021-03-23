<?php include_once('../inc_pages.php'); ?>
<?php include_once('newsletter-funcoes-logs.php');

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_newsletter")) {
  $query_rsP = "SELECT * FROM newsletters WHERE id=:id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $id, PDO::PARAM_INT);
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
  DB::close();

  $query_rsContNews = "SELECT * FROM news_conteudo WHERE id=:id";
  $rsContNews = DB::getInstance()->prepare($query_rsContNews);
  $rsContNews->bindParam(':id', $row_rsP['conteudo'], PDO::PARAM_INT);
  $rsContNews->execute();
  $totalRows_rsContNews = $rsContNews->rowCount();
  DB::close();

  if($row_rsP['conteudo'] > 0 && $totalRows_rsContNews > 0) {
    $conteudo = $row_rsP['conteudo'];
  }
  else {
    $conteudo = $_POST['conteudo'];
  }

  $insertSQL = "UPDATE newsletters SET conteudo=:conteudo, titulo=:nome, tipo=:tipo WHERE id=:id";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':conteudo', $conteudo, PDO::PARAM_INT);
  $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_STR, 5); 
  $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 
  $rsInsert->execute();
  DB::close();

  header("Location: newsletter-detalhe.php?id=".$id."&alt=1");
}

$query_rsP = "SELECT * FROM newsletters WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsConfig = "SELECT * FROM newsletters_config WHERE id=1";
$rsConfig = DB::getInstance()->prepare($query_rsConfig);
$rsConfig->execute();
$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
$totalRows_rsConfig = $rsConfig->rowCount();
DB::close();

$query_rsCont = "SELECT * FROM news_conteudo ORDER BY id DESC";
$rsCont = DB::getInstance()->prepare($query_rsCont);
$rsCont->execute();
$totalRows_rsCont = $rsCont->rowCount();
DB::close();

$query_rsContNews = "SELECT * FROM news_conteudo WHERE id=:id";
$rsContNews = DB::getInstance()->prepare($query_rsContNews);
$rsContNews->bindParam(':id', $row_rsP['conteudo'], PDO::PARAM_INT);
$rsContNews->execute();
$totalRows_rsContNews = $rsContNews->rowCount();
DB::close();

$query_rsTipos = "SELECT * FROM news_tipos_pt ORDER BY nome ASC";
$rsTipos = DB::getInstance()->prepare($query_rsTipos);
$rsTipos->execute();
$totalRows_rsTipos = $rsTipos->rowCount();
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
      <h3 class="page-title"> <?php echo $row_rsP["titulo"]; ?> <small>detalhe</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_newsletter" name="frm_newsletter" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletters - Detalhe</div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='newsletter.php'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="active"> <a href="#tab_detalhe" data-toggle="tab"> Detalhe </a> </li>
                    <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-enviar.php?id=<?php echo $id; ?>'"> Agendar </a> </li>
                    <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-historico.php?id=<?php echo $id; ?>'"> Agendamentos </a> </li>
                    <li> <a href="#tab_envio_teste" data-toggle="tab" onClick="document.location='newsletter-enviar-teste.php?id=<?php echo $id; ?>'"> Envio teste </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_general">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          Preencha todos os campos obrigatórios. 
                        </div>    
                        <?php if($_GET['alt'] == 1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> Registo alterado com sucesso. </span> 
                          </div>
                        <?php } ?>
                        <?php if($totalRows_rsContNews == 0) { ?>
                          <div class="alert alert-danger display-show">
                            <button class="close" data-close="alert"></button>
                            Esta newsletter não tem conteúdo associado. 
                          </div>
                        <?php }?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nome">Nome / Assunto: <span class="required"> * </span></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['titulo']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="conteudo">Conteúdo:</label>
                          <div class="col-md-8">
                            <select class="form-control select2me" id="conteudo" name="conteudo" <?php if($row_rsP["conteudo"] > 0 && $totalRows_rsContNews > 0) echo "disabled"; ?>>
                              <option value="">Selecionar...</option>
                              <?php while($row_rsCont = $rsCont->fetch()) { ?>
                                <option value="<?php echo $row_rsCont["id"]; ?>" <?php if($row_rsP["conteudo"] == $row_rsCont["id"]) { ?>selected<?php } ?>><?php echo $row_rsCont["nome"]; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="tipo">Tipo: <span class="required"> * </span></label>
                          <div class="col-md-8">
                            <select class="form-control select2me" id="tipo" name="tipo">
                              <option value="">Selecionar...</option>
                              <?php if($totalRows_rsTipos > 0) { 
                                while($row_rsTipos = $rsTipos->fetch()) { ?>
                                  <option value="<?php echo $row_rsTipos["id"]; ?>" <?php if($row_rsP['tipo'] == $row_rsTipos['id']) echo "selected"; ?>><?php echo $row_rsTipos["nome"]; ?></option>
                                <?php } 
                              } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_newsletter" />
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
  FormValidation.init();
});
</script>
</body>
<!-- END BODY -->
</html>