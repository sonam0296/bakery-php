<?php include_once('../inc_pages.php'); ?>
<?php include_once(ROOTPATH.'sendMail/send_mail.php'); ?>
<?php

$menu_sel='tickets';
$menu_sub_sel='listagem';

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_ticket_ed")) {
  $manter = $_POST['manter'];
  $tipo = $_POST['tipo'];

	if($tipo == 2 && $_POST['descricao'] != '') {
		$query_rsP = "SELECT * FROM tickets WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();

    $language = $row_rsP['lingua'];
    if($language == '') $language="pt";

    include_once(ROOTPATH."linguas/".$language.".php");
    $className = 'Recursos_'.$language;
    $Recursos = new $className();
		
		$insertSQL = "SELECT MAX(id) FROM tickets";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		
		$id_max = $row_rsInsert["MAX(id)"] + 1;

		$data = date('Y-m-d H:i:s');
		$visto = 1;
		
		$remetente = NOME_SITE;
    if(!$remetente) {
      $remetente = "Administração";
    }

    $estado = $_POST['estado'];

    $txt_field = '';
    $files = $_FILES['file'];
    $n_anexos = 0;

    if(!empty($files)) {
      $imgs_dir = "tickets";
      $anexos_field = fileUpload($imgs_dir, $files, '');  

      if(!empty($anexos_field)) {
        $anexos_array = $anexos_field;

        foreach($anexos_field as $anexos) {
          if($anexos[1]) {
            $anexos_txt .= $anexos[1].", "; 
            $n_anexos++;
          }
        }

        if($anexos_txt) {
          $anexos_txt = substr($anexos_txt, 0, -2);

          $txt_field = '<tr>
            <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["anexos"].' ('.$n_anexos.'):</strong></td>
            <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$anexos_txt.'</td>
          </tr>';
        }
      }
    }

		$insertSQL = "INSERT INTO tickets (id, id_pai, id_cliente, remetente, assunto, descricao, data, estado, visto, anexos) VALUES (:id, :id_pai, :id_cliente, :remetente, :assunto, :descricao, :data, :estado, :visto, :anexos)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id', $id_max, PDO::PARAM_INT);
		$rsInsert->bindParam(':id_pai', $id, PDO::PARAM_INT);	
		$rsInsert->bindParam(':id_cliente', $row_rsP['id_cliente'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':remetente', $remetente, PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':assunto', $row_rsP['assunto'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);		
		$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':estado', $estado, PDO::PARAM_INT);
		$rsInsert->bindParam(':visto', $visto, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':anexos', $anexos_txt, PDO::PARAM_STR, 5);
		$rsInsert->execute();
		
		$insertSQL = "UPDATE tickets SET estado=:estado WHERE id=:id OR id_pai=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->bindParam(':estado', $estado, PDO::PARAM_INT);
		$rsInsert->execute();
		
		##################################### mail
    $formcontent = getHTMLTemplate("contacto.htm");

    $rodape = email_social();
    
    $assunto = $row_rsP['assunto'];
    $mensagem = nl2br($_POST['descricao']);

    $mensagem_final = '
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
              <tr>
                <td style="font-family:arial; font-size:16px; line-height:16px; color:#444444; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>
              </tr>
            </table>
            <table width="100%" border="0" cellpadding="1" cellspacing="0">
              <tr>
              <td height="20">&nbsp;</td>
              <td align="left" valign="middle">&nbsp;</td>
              </tr>
              <tr>
              <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["tck_nome"].':</strong></td>
              <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$remetente.'</td>
              </tr>
              <tr>
              <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["tck_assunto"].':</strong></td>
              <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$assunto.'</td>
              </tr>
              <tr>
              <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>
              <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$mensagem.'</td>
              </tr>
              '.$txt_field.'
            </table>';

    $query_rsNotificacoes = "SELECT assunto FROM notificacoes".$extensao." WHERE id = 4";
    $rsNotificacoes = DB::getInstance()->query($query_rsNotificacoes);
    $row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsNotificacoes = $rsNotificacoes->rowCount();
    
    $titulo = $row_rsNotificacoes['assunto'];

    $pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."</a>"; 
    
    $formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);  
    $formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);
    $formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent); 
    $formcontent = str_replace ("#crodape#", $rodape, $formcontent); 
    $formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);
    
    $query_rsCliente = "SELECT email FROM clientes WHERE id=:id_cliente";
    $rsCliente = DB::getInstance()->prepare($query_rsCliente);
    $rsCliente->bindParam(':id_cliente', $row_rsP['id_cliente'], PDO::PARAM_INT); 
    $rsCliente->execute();
    $row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

    $to = $row_rsCliente['email'];
    $subject = $row_rsNotificacoes['assunto'];
    
    sendMail($to,'', $formcontent, $mensagem_final, $subject);
		####################################
		
    DB::close();
		
    if(!$manter) 
      header("Location: tickets.php");
    else
      header("Location: tickets-edit.php?id=".$id."&alt=1");
	}
  else if($tipo == 1 && $_POST['estado'] != '') {
    $insertSQL = "UPDATE tickets SET estado=:estado WHERE id=:id OR id_pai=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
    $rsInsert->bindParam(':estado', $_POST['estado'], PDO::PARAM_INT);
    $rsInsert->execute();

    DB::close();
    
    if(!$manter) 
      header("Location: tickets.php");
    else
      header("Location: tickets-edit.php?id=".$id."&alt=2");
  }
}

$query_rsP = "SELECT * FROM tickets WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$data_ticket = explode(' ', $row_rsP['data']);
$hora_ticket = substr($data_ticket['1'], 0, -3);
$data_ticket = data_hora($data_ticket[0]);

if($row_rsP['visto'] == 0) {
  $insertSQL = "UPDATE tickets SET visto=1 WHERE id=:id OR id_pai=:id";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
  $rsInsert->execute();
}

$query_rsTipo = "SELECT nome FROM tickets_tipos_pt WHERE id=:id";
$rsTipo = DB::getInstance()->prepare($query_rsTipo);
$rsTipo->bindParam(':id', $row_rsP['tipo'], PDO::PARAM_INT);  
$rsTipo->execute();
$row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTipo = $rsTipo->rowCount();

$query_rsClientes = "SELECT nome FROM clientes WHERE id=:id_cliente";
$rsClientes = DB::getInstance()->prepare($query_rsClientes);
$rsClientes->bindParam(':id_cliente', $row_rsP['id_cliente'], PDO::PARAM_INT);	
$rsClientes->execute();
$row_rsClientes = $rsClientes->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientes = $rsClientes->rowCount();

// tickets anteriores
$query_rsTickets = "SELECT data, remetente, descricao, anexos FROM tickets WHERE id_pai=:id ORDER BY data ASC";
$rsTickets = DB::getInstance()->prepare($query_rsTickets);
$rsTickets->bindParam(':id', $id, PDO::PARAM_INT);	
$rsTickets->execute();
$totalRows_rsTickets = $rsTickets->rowCount();

// último ticket
$query_rsUltimo = "SELECT estado FROM tickets WHERE id_pai=:id ORDER BY data DESC LIMIT 1";
$rsUltimo = DB::getInstance()->prepare($query_rsUltimo);
$rsUltimo->bindParam(':id', $id, PDO::PARAM_INT);	
$rsUltimo->execute();
$row_rsUltimo = $rsUltimo->fetch(PDO::FETCH_ASSOC);
$totalRows_rsUltimo = $rsUltimo->rowCount();

if($totalRows_rsUltimo == 0) {
	$query_rsUltimo = "SELECT estado FROM tickets WHERE id=:id";
	$rsUltimo = DB::getInstance()->prepare($query_rsUltimo);
	$rsUltimo->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsUltimo->execute();
	$row_rsUltimo = $rsUltimo->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsUltimo = $rsUltimo->rowCount();	
}

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['tickets']; ?>  <small> <?php echo $RecursosCons->RecursosCons['editar_registo']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="tickets.php"><?php echo $RecursosCons->RecursosCons['tickets']; ?></a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:"> <?php echo $RecursosCons->RecursosCons['editar_registo']; ?> </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?>  </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='tickets.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="form_ticket_ed" name="form_ticket_ed" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="1">
            <input type="hidden" name="tipo" id="tipo" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Tickets - #<?php echo str_pad($row_rsP['id'], 3, '0', STR_PAD_LEFT)." - ".$row_rsClientes["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='tickets.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> 
                </div>
              </div>
              <div class="form-body">
                <div class="alert alert-danger display-hide">
                  <button class="close" data-close="alert"></button>
                  <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                </div>  
                <?php if($_GET['alt'] == 1) { ?>                    
                  <div class="alert alert-info">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['ticket_criado_suc']; ?> 
                  </div>
                <?php } ?>
                <?php if($_GET['alt'] == 2) { ?>                    
                  <div class="alert alert-success">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['estado_alt']; ?> 
                  </div>
                <?php } ?>
                <div class="form-group">
                  <label class="col-md-2 control-label" for="estado"><?php echo $RecursosCons->RecursosCons['estado']; ?>:</label>
                  <div class="col-md-3">
                    <select class="form-control" id="estado" name="estado">
                      <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                      <option value="0" <?php if($row_rsUltimo['estado'] == 0) { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_fechado']; ?></option>
                      <option value="1" <?php if($row_rsUltimo['estado'] == 1) { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_aberto']; ?></option>
                    </select>
                  </div>
                  <button type="submit" class="btn green" onClick="document.getElementById('tipo').value='1'"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
                <hr>
                <div class="form-group">
                  <label class="col-md-2 control-label" for="descricao"><?php echo $RecursosCons->RecursosCons['resposta_label']; ?>: </label>
                  <div class="col-md-8">
                    <textarea class="form-control" id="descricao" name="descricao"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput"> 
                      <span class="btn default btn-file btn-sm"> 
                        <span class="fileinput-new"><i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['selec_ficheiro']; ?></span> 
                        <span class="fileinput-exists"><?php echo $RecursosCons->RecursosCons['btn_altera_ficheiro']; ?></span>
                        <input type="file" class="inputfile" name="file[]" multiple accept="application/pdf,application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/*">
                      </span> 
                      <span class="inpt"></span>
                      <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a> 
                    </div>
                    &nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-sm blue" onClick="document.getElementById('tipo').value='2'"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['enviar']; ?></button>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="form_ticket_ed"/>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" style="padding-top:30px">
          <div class="portlet box green">
            <div class="portlet-title">
              <div class="caption">
                <strong>#<?php echo str_pad($row_rsP['id'], 3, '0', STR_PAD_LEFT); ?></strong> - <?php echo $row_rsClientes["nome"]; ?> - <?php echo $data_ticket.", ".$hora_ticket; ?>
              </div>
              <div class="tools">
                <a href="javascript:;" class="collapse">
                </a>
                <a href="javascript:;" class="reload">
                </a>
              </div>
            </div>
            <div class="portlet-body">
              <div class="table-scrollable">
                <table class="table table-hover">
                	<tbody>  
                  	<tr>
                    	<td style="font-size: 14px; padding: 20px;">
                        <strong><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>:</strong> <?php echo $row_rsTipo["nome"]; ?><br><br>
                        <strong><?php echo $RecursosCons->RecursosCons['assunto_label']; ?>:</strong> <?php echo $row_rsP["assunto"]; ?>
                      </td>
                    </tr>   
                    <tr>
                      <td style="font-size: 14px; padding: 20px;">
                        <strong><?php echo $row_rsP['remetente']; ?></strong> - <?php echo $data_ticket.", ".$hora_ticket; ?>
                        <div style="display: block; padding-top: 10px; line-height: 20px; font-size: 13px;"><?php echo nl2br($row_rsP['descricao']); ?></div>
                        <?php if($row_rsP['anexos']) { ?>
                          <div style="display: block; padding-top: 20px;">
                            <?php 
                            $anexos = explode(',', $row_rsP['anexos']);
                            foreach($anexos as $upload) {
                              $upload = trim($upload);
                              if($upload && file_exists(ROOTPATH.'imgs/tickets/'.$upload)) { ?>
                                <a href="<?php echo ROOTPATH_HTTP; ?>imgs/tickets/<?php echo $upload; ?>" target="_blank"><i class="fa fa-file"></i> <?php echo $upload; ?></a>&nbsp;&nbsp;
                              <?php }
                            }
                            ?>
                          </div>
                        <?php } ?>
                      </td>
                    </tr>                            
                    <?php if($totalRows_rsTickets > 0) { ?>
				              <?php while($row_rsTickets = $rsTickets->fetch()) { 
                        $data_ticket = explode(' ', $row_rsTickets['data']);
                        $hora_ticket = substr($data_ticket['1'], 0, -3);
                        $data_ticket = data_hora($data_ticket[0]);
                        ?>
                        <tr>
                          <td style="font-size: 14px; padding: 20px;">
                            <strong><?php echo $row_rsTickets['remetente']; ?></strong> - <?php echo $data_ticket.", ".$hora_ticket; ?>
                            <div style="display: block; padding-top: 10px; line-height: 20px; font-size: 13px;"><?php echo nl2br($row_rsTickets['descricao']); ?></div>
                            <?php if($row_rsTickets['anexos']) { ?>
                              <div style="display: block; padding-top: 20px;">
                                <?php 
                                $anexos = explode(',', $row_rsTickets['anexos']);
                                foreach($anexos as $upload) {
                                  $upload = trim($upload);
                                  if($upload && file_exists(ROOTPATH.'imgs/tickets/'.$upload)) { ?>
                                    <a href="<?php echo ROOTPATH_HTTP; ?>imgs/tickets/<?php echo $upload; ?>" target="_blank"><i class="fa fa-file"></i> <?php echo $upload; ?></a>&nbsp;&nbsp;
                                  <?php }
                                }
                                ?>
                              </div>
                            <?php } ?>
                          </td>
                        </tr>
                  	  <?php } ?>  
                    <?php } ?>                      
                  </tbody>
                </table>  
              </div>
            </div>
          </div> 
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features

  $('.inputfile').each( function() {
    var $input = $( this ), $label = $input.parent().parent().find('span.inpt'), labelVal = $label.html();

    $input.on('change', function(e) {
      var fileName = '';
      var $this = $(this)[0];

      if($this.files && $this.files.length > 1 ) { 
        for(var i=0; i<$this.files.length; i++) {
          if(fileName) {
            fileName = fileName+", "+$this.files.item(i).name
          }
          else {
            fileName = $this.files.item(i).name
          }          
        }
      }
      else if(e.target.value) {
        fileName = e.target.value.split( '\\' ).pop();
      }

      if(fileName) $label.html( fileName );
      else $label.html( labelVal );
    });

    // Firefox bug fix
    $input
    .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
    .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
  });
});
</script>
<script type="text/javascript">
CKEDITOR.replace('descricao',
{
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "150px",
});;
</script>
</body>
<!-- END BODY -->
</html>