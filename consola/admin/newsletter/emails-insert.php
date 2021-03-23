<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_mails';
$menu_sub_sel='';

$email_erro = 0;
$lista_erro = 0;

function randomCode($size = '24') {
  $string = '';
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  for($i = 0; $i < $size; $i++) {
    $string .= $characters[mt_rand(0, (strlen($characters) - 1))];  
  }

  $query_rsExists = "SELECT id FROM news_emails WHERE codigo = '$string'";
  $rsExists = DB::getInstance()->prepare($query_rsExists);
  $rsExists->execute();
  $totalRows_rsExists = $rsExists->rowCount();

  if($totalRows_rsExists == 0) {
    return $string;
  }
  else {
    return randomCode();
  }
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_emails")) {
	if(sizeof($_POST['listas']) > 0) {
		if($_POST['email'] != '') {
			$query_rsEM = "SELECT * FROM news_emails where email=:email";
			$rsEM = DB::getInstance()->prepare($query_rsEM);
			$rsEM->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);
			$rsEM->execute();
			$row_rsEM = $rsEM->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsEM = $rsEM->rowCount();
			
			$existe_email = 0;
			$erro = "";
			
			if($totalRows_rsEM > 0) {
				$existe_email = 1;
			} 
      else {
				$insertSQL = "SELECT MAX(id) FROM news_emails";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->execute();
				$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
				
				$id = $row_rsInsert["MAX(id)"]+1;
				
				if(isset($_POST['listas'])) {
					$i=1;
					$size = sizeof($_POST['listas']);
					foreach($_POST['listas'] as $l) {
						if($l!='0') {
							$insertSQL = "INSERT INTO news_emails_listas (email, lista) VALUES (:id, '$l')";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
							$rsInsert->execute();
						}
					}
				}
				
				$data = date("Y-m-d H:i:s");
				$codigo = randomCode();

				$insertSQL = "INSERT INTO news_emails (id, email, nome, empresa, cargo, telefone, data, visivel, codigo) VALUES (:id, :email, :nome, :empresa, :cargo, :telefone, '$data', 1, :codigo)";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
				$rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':empresa', $_POST['empresa'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':cargo', $_POST['cargo'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':codigo', $codigo, PDO::PARAM_STR, 5);
				$rsInsert->execute();
				DB::close();
				
				if($erro == "") header("Location: emails.php?env=1");
			}
		} 
    else {
      $email_erro = 1;
    }
	} 
  else {
    $lista_erro = 1;
  }
}

$query_rsListas = "SELECT * FROM news_listas ORDER BY ordem ASC";
$rsListas = DB::getInstance()->prepare($query_rsListas);
$rsListas->execute();
$totalRows_rsListas = $rsListas->rowCount();
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['news_page_title_emails']; ?> <small><?php echo $RecursosCons->RecursosCons['criar_novo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['newsletters']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="emails.php"><?php echo $RecursosCons->RecursosCons['emails']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="#"><?php echo $RecursosCons->RecursosCons['inserir_email']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_emails" name="frm_emails" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['novo_email']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='emails.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                  <?php if($existe_email == 1) { ?>
                    <div class="alert alert-danger display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['exist_email']; ?> 
                    </div>
                  <?php } ?>
                  <?php if($email_erro == 1 || $lista_erro == 1) { ?>
                    <div class="alert alert-danger display-show">
                      <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="email"><?php echo $RecursosCons->RecursosCons['cli_email']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="email" id="email" value="<?php echo $_POST['email']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="empresa"><?php echo $RecursosCons->RecursosCons['empresa_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="empresa" id="empresa" value="<?php echo $_POST['empresa']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="cargo"><?php echo $RecursosCons->RecursosCons['cli_cargo']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="cargo" id="cargo" value="<?php echo $_POST['cargo']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="telefone"><?php echo $RecursosCons->RecursosCons['cli_telefone']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo $_POST['telefone']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['lista_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <div class="form-control height-auto" <?php if($lista_erro == 1) echo "style=\"border:1px solid #ebccd1\""; ?>>
                        <div class="scroller" style="height: 275px;" data-always-visible="1">
                          <ul class="list-unstyled">
                            <?php if($totalRows_rsListas > 0) { ?>
                              <?php while($row_rsListas = $rsListas->fetch()) { ?>
                                <li>
                                  <label>
                                    <input type="checkbox" name="listas[]" value="<?php echo $row_rsListas['id']; ?>">
                                    <?php echo $row_rsListas['nome']; ?>
                                  </label>
                                </li>
                              <?php } ?>
                            <?php } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_emails" />
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