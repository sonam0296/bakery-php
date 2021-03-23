<?php include_once('../inc_pages.php'); ?>
<?php

if(!function_exists("tableExists")) {
  function tableExists($pdo, $table) {
    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
      $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
      // We got an exception == table not found
      return FALSE;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
  }
}

$menu_sel='outros_clientes';
$menu_sub_sel='elimina';

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_cliente_ed")) {
  $query_rsP = "SELECT * FROM clientes_remocao WHERE id = :id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $id, PDO::PARAM_INT);  
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
  DB::close();

  $id_cliente = $row_rsP["id_cliente"];
  $email = $row_rsP["email"];

  $query_rsCliente = "SELECT * FROM clientes WHERE id='$id_cliente'";
  $rsCliente = DB::getInstance()->prepare($query_rsCliente); 
  $rsCliente->execute();
  $row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsCliente = $rsCliente->rowCount();
  DB::close();

  $nome = $row_rsCliente["nome"];
  $pais = $row_rsCliente["pais"];

  if($id_cliente > 0) { // se tem o id de cliente remove de cliente e outras tabelas

    // remove carrinho 
    // id_cliente
    if(tableExists(DB::getInstance(), 'carrinho')){
      $query_rsDel = "DELETE FROM carrinho WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove carrinho_cliente 
    // id_cliente
    if(tableExists(DB::getInstance(), 'carrinho_cliente')){
      $query_rsDel = "DELETE FROM carrinho_cliente WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove cliente 
    // id
    if(tableExists(DB::getInstance(), 'clientes')){
      $query_rsDel = "DELETE FROM clientes WHERE id='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove clientes_conta_corrente
    // id_cliente
    if(tableExists(DB::getInstance(), 'clientes_conta_corrente')){
      $query_rsDel = "DELETE FROM clientes_conta_corrente WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove clientes_moradas 
    // id_cliente
    if(tableExists(DB::getInstance(), 'clientes_moradas')){
      $query_rsDel = "DELETE FROM clientes_moradas WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove clientes_obs 
    // id_cliente
    if(tableExists(DB::getInstance(), 'clientes_obs')){
      $query_rsDel = "DELETE FROM clientes_obs WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove encomendas 
    // id_cliente
    if(tableExists(DB::getInstance(), 'encomendas')) {
      $query_rsDel = "UPDATE encomendas SET id_cliente='0', nome='CLIENTE REMOVIDO ".$id_cliente."', morada_fatura=NULL, codpostal_fatura=NULL, localidade_fatura=NULL, pais_fatura=NULL, nome_envio=NULL, morada_envio=NULL, codpostal_envio=NULL, localidade_envio=NULL, pais_envio=NULL, email=NULL, telefone=NULL, telemovel=NULL, nif=NULL WHERE id_cliente ='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove encomendas_msg 
    // id_cliente
    if(tableExists(DB::getInstance(), 'encomendas_msg')) {
      $query_rsDel = "DELETE FROM encomendas_msg WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove encomendas_obs 
    // id_cliente
    if(tableExists(DB::getInstance(), 'encomendas_obs')) {
      $query_rsDel = "DELETE FROM encomendas_obs WHERE id_cliente='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }

    // remove tickets
    // id_cliente
    if(tableExists(DB::getInstance(), 'tickets')) {
      $query_rsDel = "DELETE FROM tickets WHERE id_cliente ='$id_cliente'";
      $rsDel = DB::getInstance()->prepare($query_rsDel); 
      $rsDel->execute();
    }
  }

  // remove newsletter
  // email
  if(tableExists(DB::getInstance(), 'news_emails')) {
    $query_rsDel = "DELETE FROM news_emails WHERE email ='$email'";
    $rsDel = DB::getInstance()->prepare($query_rsDel); 
    $rsDel->execute();
  }

  // email
  if(tableExists(DB::getInstance(), 'news_emails_temp')) {
    $query_rsDel = "DELETE FROM news_emails_temp WHERE email ='$email'";
    $rsDel = DB::getInstance()->prepare($query_rsDel); 
    $rsDel->execute();
  }

  // actualiza data de remoção e campo removido
  $data_remocao = date("Y-m-d H:i:s");

  $query_rsUpd = "UPDATE clientes_remocao SET data_remocao='$data_remocao', removido='1' WHERE id_cliente = '$id_cliente'";
  $rsUpd = DB::getInstance()->prepare($query_rsUpd); 
  $rsUpd->execute();

  // enviar email ao cliente
  $lang_util = "pt";
  include_once(ROOTPATH."linguas/".$lang_util.".php");
  $className = 'Recursos_'.$lang_util;
  $Recursos = new $className();
  $extensao = $Recursos->Resources["extensao"];

  $filename = ROOTPATH."contacto.htm";
  $fp = fopen($filename, "r");
  $formcontent = fread($fp, filesize($filename));
  fclose($fp); 

  $query_rsTexto = "SELECT * FROM clientes_textos".$extensao." WHERE id='2'";
  $rsTexto = DB::getInstance()->prepare($query_rsTexto); 
  $rsTexto->execute();
  $row_rsTexto = $rsTexto->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsTexto = $rsTexto->rowCount();
  DB::close();

  $texto = str_replace(array("#nome#", "!link!"), array($nome, ROOTPATH_HTTP."login.php"), $row_rsTexto["texto"]);
  
  $mensagem_final = '
    <table width="100%" border="0" cellpadding="1" cellspacing="0" style="margin-top:20px">
      <tr>
        <td align="left" valign="top" height="25">'.$texto.'</td>
      </tr>
    </table>';
  
  $titulo = $row_rsTexto["assunto"];
  $subject = $row_rsTexto["assunto"]." - www.".SERVIDOR;

  $rodape = email_social();

  $formcontent = str_replace ("#cpagina#","",$formcontent);
  $formcontent = str_replace ("#crodape#",$rodape,$formcontent);
  $formcontent = str_replace ("#ctitulo#",$titulo,$formcontent);
  $formcontent = str_replace ("#cmensagem#",$mensagem_final,$formcontent);
  $formcontent = str_replace ("#tit_mail_compr#",$Recursos->Resources["car_mail_7"],$formcontent);

  sendMail($email,'',$formcontent,$formcontent,$subject,"","",$email);

  header("Location: clientes_remocao-edit.php?id=".$id."&elim=1");
}

$query_rsP = "SELECT * FROM clientes_remocao WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsCliente = "SELECT * FROM clientes WHERE id = '".$row_rsP["id_cliente"]."'";
$rsCliente = DB::getInstance()->prepare($query_rsCliente); 
$rsCliente->execute();
$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCliente = $rsCliente->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_pedidos_remocao']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small></h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="clientes_remocao_texto.php"><?php echo $RecursosCons->RecursosCons['menu_pedidos_remocao']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_conta']; ?></h4>
            </div>
            <div class="modal-body"><?php echo $RecursosCons->RecursosCons['eliminar_conta_txt']; ?></div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.form_cliente_ed.submit();"><?php echo $RecursosCons->RecursosCons['text_visivel_sim']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <form id="form_cliente_ed" name="form_cliente_ed" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_pedidos_remocao']; ?> <?php if($totalRows_rsCliente > 0) { ?>- Cliente Nº. <?php echo $row_rsCliente["ref_cliente"]; } ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='clientes_remocao.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <?php if($row_rsP["data_remocao"] == "") { ?><a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a><?php } ?>
                </div>
              </div>
              <div class="portlet-body">
                <?php if(isset($_GET['elim']) && $_GET['elim']==1) { ?>                    
                  <div class="alert alert-success">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['r']; ?>
                  </div>
                <?php } ?>
                <?php if($totalRows_rsCliente > 0) { ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['n_cliente']; ?>: </label>
                    <div class="col-md-8" style="padding-top: 9px;">
                      <?php echo $row_rsCliente["id"]; ?>
                    </div>
                  </div>
                <?php } ?>
                <div class="form-group">
                  <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['cli_email']; ?>: </label>
                  <div class="col-md-8" style="padding-top: 9px;">
                    <?php echo $row_rsP["email"]; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['motivo_label']; ?>: </label>
                  <div class="col-md-8" style="padding-top: 9px;">
                    <?php echo $row_rsP["descricao"]; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['data_pedido']; ?>: </label>
                  <div class="col-md-8" style="padding-top: 9px;">
                    <?php echo $row_rsP["data_pedido"]; ?>
                  </div>
                </div>
                <?php if($row_rsP["data_remocao"] != "") { ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['data_remocao']; ?>: </label>
                    <div class="col-md-8" style="padding-top: 9px;">
                      <?php echo $row_rsP["data_remocao"]; ?>
                    </div>
                  </div>
                <?php } ?>
              </div>   
            </div>
            <input type="hidden" name="MM_insert" value="form_cliente_ed" />
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
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
});
</script>
</body>
</html>