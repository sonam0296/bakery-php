<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='configuracao';
$menu_sub_sel='notificacao';

$id=$_GET['id'];

$inserido=0;
$erro_password=0;
$tab_sel=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	$manter = $_POST['manter'];
	
	$insertSQL = "UPDATE notificacoes".$extensao." SET assunto_cliente=:assunto_cliente, sucesso=:sucesso, assunto=:assunto, resposta=:resposta WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':assunto_cliente', $_POST['assunto_cliente'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':sucesso', $_POST['sucesso'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':assunto', $_POST['assunto'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':resposta', $_POST['resposta'], PDO::PARAM_STR, 5);		
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
	$rsInsert->execute();
	DB::close();

	$query_rsLinguas = "SELECT * FROM linguas WHERE visivel=1";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	DB::close();

	while($row_rsLinguas = $rsLinguas->fetch()) {
		$insertSQL = "UPDATE notificacoes_".$row_rsLinguas['sufixo']." SET email=:email, email2=:email2, email3=:email3 WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':email2', $_POST['email2'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':email3', $_POST['email3'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
		$rsInsert->execute();
		DB::close();
	}
	
	if(!$manter) 
		header("Location: notificacao.php?alt=1");
	else
		header("Location: notificacao-edit.php?id=".$id."&alt=1");
}

$query_rsP = "SELECT * FROM notificacoes".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);		
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			<?php echo $RecursosCons->RecursosCons['notificacao']; ?> <small><?php echo $RecursosCons->RecursosCons['emails_info_website']; ?></small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['configuracao']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="notificacao.php"><?php echo $RecursosCons->RecursosCons['notificacoes']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript"><?php echo $RecursosCons->RecursosCons['alterar_registo']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
        <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
      	<form id="alterar" name="alterar" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
      		<input type="hidden" name="manter" id="manter" value="0">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-tag"></i><?php echo $RecursosCons->RecursosCons['emails_notificacao']; ?> - <?php echo $row_rsP['nome']; ?>
							</div>
							<div class="actions btn-set">
								<button type="button" name="back" class="btn default" onClick="document.location='notificacao.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
								<button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>		
                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
							</div>
						</div>
						<div class="portlet-body">
              <div class="form-body">
              	<?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?> 
	                <div class="alert alert-success display-show">
	                  <button class="close" data-close="alert"></button>
	                  <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
	                </div>
                <?php } ?>
                <div class="form-group">
									<label class="col-md-2 control-label" for="email"><?php echo $RecursosCons->RecursosCons['para_label']; ?>:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="email" id="email" value="<?php echo $row_rsP['email']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="email2"><?php echo $RecursosCons->RecursosCons['cc_label']; ?>:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="email2" id="email2" value="<?php echo $row_rsP['email2']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="email3"><?php echo $RecursosCons->RecursosCons['bcc_label']; ?>:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="email3" id="email3" value="<?php echo $row_rsP['email3']; ?>">
										<span class="help-block"><strong><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>:</strong><?php echo $RecursosCons->RecursosCons['help_block_emails']; ?></span>
									</div>
								</div>
								<?php if($row_rsP['resposta_editavel'] == 1) { ?>
									<hr>
									<div class="form-group">
										<div class="col-md-2"></div>
										<div class="col-md-8"><strong><?php echo $RecursosCons->RecursosCons['resp_automatica_label']; ?></strong></div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="assunto"><?php echo $RecursosCons->RecursosCons['assunto_label']; ?>: </label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="assunto" id="assunto" value="<?php echo $row_rsP['assunto']; ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="assunto_cliente"><?php echo $RecursosCons->RecursosCons['assunto_cli_label']; ?>: </label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="assunto_cliente" id="assunto_cliente" value="<?php echo $row_rsP['assunto_cliente']; ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="sucesso"><?php echo $RecursosCons->RecursosCons['txt_suc_preenchimento']; ?>: </label>
										<div class="col-md-8">
	                  <textarea class="form-control" name="sucesso" id="sucesso" style="height:100px;"><?php echo $row_rsP['sucesso']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
	                  <label class="col-md-2"></label>
	                  <div class="col-md-6">
	                    <p class="help-block"><?php echo $RecursosCons->RecursosCons['tags_nome_cli']; ?> </p>
	                  </div>
	                </div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="resposta"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
										<div class="col-md-8">
											<textarea class="form-control" name="resposta" id="resposta"><?php echo $row_rsP['resposta']; ?></textarea>
										</div>
									</div>
								<?php } ?>
              </div>			
						</div>
					</div>
          <input type="hidden" name="MM_insert" value="alterar" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
});
</script>
<script type="text/javascript">
CKEDITOR.replace('resposta',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	toolbar : "Basic",
	height: "250px"
});
</script> 
</body>
<!-- END BODY -->
</html>