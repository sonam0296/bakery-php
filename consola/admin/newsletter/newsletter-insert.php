<?php include_once('../inc_pages.php'); ?>
<?php include_once('newsletter-funcoes-logs.php');

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_newsletter")) {
  $tipo_envio = $_POST['tipo_envio'];

	if($_POST['nome'] != '' && ($tipo_envio == 1 || ($tipo_envio == 2 && $_POST['mailgun_key'] != "" && $_POST['mailgun_dominio'] != ""))) {
    $query_rsConfig = "SELECT * FROM newsletters_config";
    $rsConfig = DB::getInstance()->prepare($query_rsConfig);
    $rsConfig->execute();
    $totalRows_rsConfig = $rsConfig->rowCount();
    $row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
    DB::close();

    $nome_from = $_POST['nome_from'];
    if($nome_from) {
      $nome_from = $row_rsConfig['nome_from'];
    }

    $email_from = $_POST['email_from'];
    if($email_from) {
      $email_from = $row_rsConfig['email_from'];
    }

    $email_reply = $_POST['email_reply'];
    if($email_reply) {
      $email_reply = $row_rsConfig['email_reply'];
    }

    $email_bounce = $_POST['email_bounce'];
    if($email_bounce) {
      $email_bounce = $row_rsConfig['email_bounce'];
    }

		$insertSQL = "SELECT MAX(id) FROM newsletters";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		DB::close();
		
		$max_id = $row_rsInsert["MAX(id)"]+1;
		$data = date('Y-m-d H:i:s'); 

		$insertSQL = "INSERT INTO newsletters (id, titulo, tipo, conteudo, nome_from, email_from, email_reply, email_bounce, data_criacao, tipo_envio, mailgun_key, mailgun_dominio) VALUES ('$max_id', :nome, :tipo, :conteudo, :nome_from, :email_from, :email_reply, :email_bounce, :data, :tipo_envio, :mailgun_key, :mailgun_dominio)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT);
		$rsInsert->bindParam(':conteudo', $_POST['conteudo'], PDO::PARAM_INT);
    $rsInsert->bindParam(':nome_from', $nome_from, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':email_from', $email_from, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':email_reply', $email_reply, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':email_bounce', $email_bounce, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':tipo_envio', $tipo_envio, PDO::PARAM_INT);
    $rsInsert->bindParam(':mailgun_key', $_POST['mailgun_key'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':mailgun_dominio', $_POST['mailgun_dominio'], PDO::PARAM_STR, 5);
		$rsInsert->execute();
		DB::close();
		
		$que_fez="criou newsletter";
		$nome_utilizador=$row_rsUser["username"];
		$class_news_logs->logs_agendamentos($nome_utilizador, $max_id, $que_fez, $_POST['nome']);
		
		header("Location: newsletter.php?env=1");
	}
}

$query_rsCont = "SELECT * FROM news_conteudo ORDER BY id DESC";
$rsCont = DB::getInstance()->prepare($query_rsCont);
$rsCont->execute();
$totalRows_rsCont = $rsCont->rowCount();
DB::close();

$query_rsConfig = "SELECT * FROM newsletters_config";
$rsConfig = DB::getInstance()->prepare($query_rsConfig);
$rsConfig->execute();
$totalRows_rsConfig = $rsConfig->rowCount();
$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
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
      <h3 class="page-title"> Newsletters <small>criar nova</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="newsletter.php">Newsletters</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_newsletter" name="frm_newsletter" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletters - Novo registo</div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='newsletter.php'"><i class="fa fa-angle-left"></i> Voltar</button>
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
                  <?php if($row_rsConfig["mostra_tipo"] == 1) { ?>
                    <div class="form-group">
                      <label class="col-md-2 control-label">Plataforma de envio: </label>
                      <div class="col-md-10">
                        <div class="md-radio-inline">
                          <div class="md-radio">
                            <input type="radio" id="tipo_envio_1" name="tipo_envio" class="md-radiobtn" value="1" <?php if((!isset($_POST["tipo_envio"]) && $row_rsConfig['tipo_envio'] == 1) || $_POST["tipo_envio"] == 1) { ?>checked<?php } ?> onclick="verificaTipo(this.value);">
                            <label for="tipo_envio_1"> <span></span> <span class="check"></span> <span class="box"></span> Interno </label>
                          </div>
                          <div class="md-radio">
                            <input type="radio" id="tipo_envio_2" name="tipo_envio" class="md-radiobtn" value="2" <?php if((!isset($_POST["tipo_envio"]) && $row_rsConfig['tipo_envio'] == 2) || $_POST["tipo_envio"] == 2) { ?>checked<?php } ?> onclick="verificaTipo(this.value);">
                            <label for="tipo_envio_2"> <span></span> <span class="check"></span> <span class="box"></span> Mailgun </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="div_mailgun" <?php if((!isset($_POST["tipo_envio"]) && $row_rsConfig['tipo_envio'] == 1) || $_POST["tipo_envio"] == 1) { ?> style="display: none;" <?php } ?>>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="mailgun_key">Chave API: <span class="required"> * </span></label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" name="mailgun_key" id="mailgun_key" value="<?php echo $row_rsConfig['mailgun_key']; ?>" data-required="1">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="mailgun_dominio">Domínio: <span class="required"> * </span></label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" name="mailgun_dominio" id="mailgun_dominio" value="<?php echo $row_rsConfig['mailgun_dominio']; ?>" data-required="1">
                        </div>
                      </div>
                    </div>
                  <?php } 
                  else { ?>
                    <input type="hidden" id="tipo_envio" name="tipo_envio" value="1">
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome">Nome / Assunto: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="conteudo">Conteúdo: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="conteudo" name="conteudo">
                        <option value="">Selecionar...</option>
                        <?php while($row_rsCont = $rsCont->fetch()) { ?>
                          <option value="<?php echo $row_rsCont["id"]; ?>"><?php echo $row_rsCont["nome"]; ?></option>
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
                            <option value="<?php echo $row_rsTipos["id"]; ?>"><?php echo $row_rsTipos["nome"]; ?></option>
                          <?php } 
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome_from">Nome <em>De</em> : </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome_from" id="nome_from" value="<?php echo $row_rsConfig['nome_from']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email_from">Email <em>De</em> : </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="email_from" id="email_from" value="<?php echo $row_rsConfig['email_from']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email_reply">Email <em>Responder</em> : </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="email_reply" id="email_reply" value="<?php echo $row_rsConfig['email_reply']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email_bounce">Email <em>Bounce To</em> : </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="email_bounce" id="email_bounce" value="<?php echo $row_rsConfig['email_bounce']; ?>">
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

function verificaTipo(id) {
  if(id == 2) $("#div_mailgun").fadeIn();
  else $("#div_mailgun").fadeOut();
}
</script>
</body>
<!-- END BODY -->
</html>