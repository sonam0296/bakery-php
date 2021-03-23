<?php include_once('../inc_pages.php'); ?>
<?php

$erro_password=0;
$tab_sel=0;
$erro_update=0;
// $editFormAction = $_SERVER['PHP_SELF'];
// if (isset($_SERVER['QUERY_STRING'])) {
//   $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
// }

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_newsletter_config")) {
  $tipo_envio = $_POST['tipo_envio'];
  
  if($_POST['max_emails'] != '' && $_POST['email_from'] != '' && $_POST['email_reply'] != '' && ($tipo_envio == 1 || ($tipo_envio == 2 && $_POST['mailgun_key'] != "" && $_POST['mailgun_dominio'] != ""))) {

    $max_emails = $_POST['max_emails'];
    if($tipo_envio == 2 && $max_emails > 500){ //limita a 500 para envio através do Mailgun
      $max_emails = 500;
    }

    //$insertSQL = "UPDATE newsletters_config SET max_emails=:max_emails, email_from=:email_from, email_reply=:email_reply, email_bounce=:email_bounce WHERE id='1'";
    $insertSQL = "UPDATE newsletters_config SET max_emails=:max_emails, nome_from=:nome_from, email_from=:email_from, email_reply=:email_reply, email_bounce=:email_bounce, tipo_envio=:tipo_envio, mailgun_key=:mailgun_key, mailgun_dominio=:mailgun_dominio, url=:url, telefone=:telefone, texto_link=:texto_link, texto_email1=:texto_email1, email1=:email1, texto_email2=:texto_email2, email2=:email2, texto_email3=:texto_email3, email3=:email3, texto_email4=:texto_email4, email4=:email4 WHERE id='1'";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':nome_from', $_POST['nome_from'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':email_from', $_POST['email_from'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':email_reply', $_POST['email_reply'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':email_bounce', $_POST['email_bounce'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':max_emails', $max_emails, PDO::PARAM_INT);
    $rsInsert->bindParam(':tipo_envio', $tipo_envio, PDO::PARAM_INT);  
    $rsInsert->bindParam(':mailgun_key', $_POST['mailgun_key'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':mailgun_dominio', $_POST['mailgun_dominio'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':url', $_POST['url'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':texto_email1', $_POST['texto_email1'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':email1', $_POST['email1'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':texto_email2', $_POST['texto_email2'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':email2', $_POST['email2'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':texto_email3', $_POST['texto_email3'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':email3', $_POST['email3'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':texto_email4', $_POST['texto_email4'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':email4', $_POST['email4'], PDO::PARAM_STR, 5);
    $rsInsert->execute();
    DB::close();
  }
  else {
    $erro_update=1;
  }

  if($erro_update==0) header('Location: config.php?alt=1');
}

$query_rsP = "SELECT * FROM newsletters_config WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();


$menu_sel='newsletter_config';
$menu_sub_sel='';

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['config_newsletter']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"> <?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form method="POST" id="frm_newsletter_config" name="frm_newsletter_config" role="form" enctype="multipart/form-data" class="form-horizontal">
          <input type="hidden" name="img_remover1" id="img_remover1" value="0">
          <input type="hidden" name="img_remover2" id="img_remover2" value="0">
          <input type="hidden" name="img_remover3" id="img_remover2" value="0">
          <input type="hidden" name="img_remover4" id="img_remover2" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-desktop"></i> <?php echo $RecursosCons->RecursosCons['newsletters']; ?> </div>
                <div class="actions btn-set">
                  <button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-success<?php if(isset($_GET['alt']) && ($_GET['alt'] == 1)) { ?> display-show <?php } else { ?> display-hide <?php } ?>">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['config_alt']; ?> </div>
                  <div class="alert alert-danger<?php if($erro_update==1) { ?> display-show <?php } else { ?> display-hide <?php } ?>">
	                  <button class="close" data-close="alert"></button>
	                  <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                  <?php if($row_rsP["mostra_tipo"] == 1) { ?>
                    <div class="form-group">
                      <label class="col-md-2 control-label">Plataforma de envio:</label>
                      <div class="col-md-10">
                        <div class="md-radio-inline">
                          <div class="md-radio">
                            <input type="radio" id="tipo_envio_1" name="tipo_envio" class="md-radiobtn" value="1" <?php if($row_rsP["tipo_envio"] == 1) echo "checked"; ?> onclick="verificaTipo(this.value);">
                            <label for="tipo_envio_1"> <span></span> <span class="check"></span> <span class="box"></span> Interno </label>
                          </div>
                          <div class="md-radio">
                            <input type="radio" id="tipo_envio_2" name="tipo_envio" class="md-radiobtn" value="2" <?php if($row_rsP["tipo_envio"] == 2) echo "checked"; ?> onclick="verificaTipo(this.value);">
                            <label for="tipo_envio_2"> <span></span> <span class="check"></span> <span class="box"></span> Mailgun </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="div_mailgun" class="form-group" <?php if($row_rsP["tipo_envio"] == 1) { ?>style="display:none;"<?php } ?>>
                      <label class="col-md-2 control-label" for="mailgun_key">Chave API: <span class="required"> *</span></label>
                      <div class="col-md-4">
                        <input type="text" class="form-control" name="mailgun_key" id="mailgun_key" value="<?php echo $row_rsP['mailgun_key']; ?>" data-required="1">
                      </div>
                      <label class="col-md-1 control-label" for="mailgun_dominio">Domínio: <span class="required"> *</span></label>
                      <div class="col-md-5">
                        <input type="text" class="form-control" name="mailgun_dominio" id="mailgun_dominio" value="<?php echo $row_rsP['mailgun_dominio']; ?>" data-required="1">
                      </div>
                    </div>
                  <?php } else { ?>
                    <input type="hidden" id="tipo_envio" name="tipo_envio" value="1">
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="max_emails"><?php echo $RecursosCons->RecursosCons['limite_label']; ?>: <span class="required"> *</label>
                    <div class="col-md-10">
                      <input type="number" min="1" max="1000" class="form-control" name="max_emails" id="max_emails" value="<?php echo $row_rsP['max_emails']; ?>" data-required="1">
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['max_emails_enviados']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome_from"><?php echo $RecursosCons->RecursosCons['nome_label']; ?> <em><?php echo $RecursosCons->RecursosCons['de_label']; ?></em> : <span class="required"> *</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="nome_from" id="nome_from" value="<?php echo $row_rsP['nome_from'];?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email_from"><?php echo $RecursosCons->RecursosCons['cli_email']; ?> <em><?php echo $RecursosCons->RecursosCons['de_label']; ?></em> : <span class="required"> *</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="email_from" id="email_from" value="<?php echo $row_rsP['email_from'];?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email_reply"><?php echo $RecursosCons->RecursosCons['cli_email']; ?> <em><?php echo $RecursosCons->RecursosCons['responder_label']; ?></em> : <span class="required"> *</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="email_reply" id="email_reply" value="<?php echo $row_rsP['email_reply'];?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email_bounce"><?php echo $RecursosCons->RecursosCons['cli_email']; ?> <em><?php echo $RecursosCons->RecursosCons['bounce_to']; ?></em> : <span class="required"> *</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="email_bounce" id="email_bounce" value="<?php echo $row_rsP['email_bounce'];?>" data-required="1">
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['help_block_newsletter']; ?></p>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8"><strong>Informações do rodapé</strong></div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email1">Texto Email 1: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email1" id="texto_email1" value="<?php echo $row_rsP['texto_email1']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email1">Email 1: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email1" id="email1" value="<?php echo $row_rsP['email1']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email2">Texto Email 2: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email2" id="texto_email2" value="<?php echo $row_rsP['texto_email2']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email2">Email 2: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email2" id="email2" value="<?php echo $row_rsP['email2']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email3">Texto Email 3: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email3" id="texto_email3" value="<?php echo $row_rsP['texto_email3']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email3">Email 3: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email3" id="email3" value="<?php echo $row_rsP['email3']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email4">Texto Email 4: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email4" id="texto_email4" value="<?php echo $row_rsP['texto_email4']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email4">Email 4: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email4" id="email4" value="<?php echo $row_rsP['email4']; ?>">
                    </div>
                  </div>
                  <div class="form-group" style="margin-top: 50px;">
                    <label class="col-md-2 control-label" for="url">URL: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="url" id="url" value="<?php echo $row_rsP['url']; ?>">
                    </div>
                    <label class="col-md-2 control-label" for="texto_link">Texto URL: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $row_rsP['texto_link']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="telefone">Contacto: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo $row_rsP['telefone']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_newsletter_config" />
          </form>
        </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
</div>
<!-- END CONTENT -->
<?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/components-pickers.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  ComponentsPickers.init();
  FormValidation.init();
});

function verificaTipo(id) {
  if(id == 2) jQuery("#div_mailgun").fadeIn();
  else jQuery("#div_mailgun").fadeOut();
}
</script>
</body>
<!-- END BODY -->
</html>