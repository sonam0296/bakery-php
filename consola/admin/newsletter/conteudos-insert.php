<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_conteudos';
$menu_sub_sel='';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_conteudo")) {
	if($_POST['nome'] != '') {
		$insertSQL = "SELECT MAX(id) FROM news_conteudo";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		DB::close();
		
		$max_id = $row_rsInsert["MAX(id)"] + 1;
		
		$insertSQL = "INSERT INTO news_conteudo (id, nome, topo, url, telefone, texto_link, mes, ano, texto_email1, email1, texto_email2, email2, texto_email3, email3, texto_email4, email4) VALUES ('$max_id', :nome, :topo, :url, :telefone, :texto_link, :mes, :ano, :texto_email1, :email1, :texto_email2, :email2, :texto_email3, :email3, :texto_email4, :email4)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':topo', $_POST['topo'], PDO::PARAM_INT);
    $rsInsert->bindParam(':url', $_POST['url'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR, 5);
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

		header("Location: conteudos.php?env=1");
	}
}

$query_rsTopos = "SELECT id, nome FROM news_topos ORDER BY nome ASC";
$rsTopos = DB::getInstance()->prepare($query_rsTopos);
$rsTopos->execute();
$totalRows_rsTopos = $rsTopos->rowCount();
DB::close();

$query_rsConfig = "SELECT * FROM newsletters_config WHERE id = 1";
$rsConfig = DB::getInstance()->prepare($query_rsConfig);
$rsConfig->execute();
$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
DB::close();

$mes = date('m');
if($mes == 1) {
  $mes_atual = 'JANEIRO';
}
else if($mes == 2) {
  $mes_atual = 'FEVEREIRO';
}
else if($mes == 3) {
  $mes_atual = 'MARÇO';
}
else if($mes == 4) {
  $mes_atual = 'ABRIL';
}
else if($mes == 5) {
  $mes_atual = 'MAIO';
}
else if($mes == 6) {
  $mes_atual = 'JUNHO';
}
else if($mes == 7) {
  $mes_atual = 'JULHO';
}
else if($mes == 8) {
  $mes_atual = 'AGOSTO';
}
else if($mes == 9) {
  $mes_atual = 'SETEMBRO';
}
else if($mes == 10) {
  $mes_atual = 'OUTUBRO';
}
else if($mes == 11) {
  $mes_atual = 'NOVEMBRO';
}
else if($mes == 12) {
  $mes_atual = 'DEZEMBRO';
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>
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
      <h3 class="page-title"> Newsletter » Conteúdos <small>criar novo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos.php">Conteúdos</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_conteudo" name="frm_conteudo" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - Conteúdos - Novo registo</div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='conteudos.php'"><i class="fa fa-angle-left"></i> Voltar</button>
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
                  <?php /*<div class="form-group">
                    <label class="col-md-2 control-label" for="topo">Topo: </label>
                    <div class="col-md-8">
                      <select class="form-control select2me" name="topo" id="topo">
                        <option value="">Selecionar...</option>
                        <?php if($totalRows_rsTopos > 0) {
                          while($row_rsTopos = $rsTopos->fetch()) { ?>
                            <option value="<?php echo $row_rsTopos['id']; ?>"><?php echo $row_rsTopos['nome']; ?></option>
                          <?php }
                        } ?>
                      </select>
                    </div>
                  </div>*/?>
                  <hr>
                  <div class="form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8"><strong>Informações do topo</strong></div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="mes">Mês: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="mes" id="mes" value="<?php echo $mes_atual; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="ano">Ano: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="ano" id="ano" value="<?php echo date('Y'); ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
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
                      <input type="text" class="form-control" name="texto_email1" id="texto_email1" value="<?php echo $row_rsConfig['texto_email1']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email1">Email 1: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email1" id="email1" value="<?php echo $row_rsConfig['email1']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email2">Texto Email 2: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email2" id="texto_email2" value="<?php echo $row_rsConfig['texto_email2']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email2">Email 2: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email2" id="email2" value="<?php echo $row_rsConfig['email2']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email3">Texto Email 3: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email3" id="texto_email3" value="<?php echo $row_rsConfig['texto_email3']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email3">Email 3: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email3" id="email3" value="<?php echo $row_rsConfig['email3']; ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto_email4">Texto Email 4: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_email4" id="texto_email4" value="<?php echo $row_rsConfig['texto_email4']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                    <label class="col-md-2 control-label" for="email4">Email 4: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="email4" id="email4" value="<?php echo $row_rsConfig['email4']; ?>">
                    </div>
                  </div>
                  <div class="form-group" style="margin-top: 50px;">
                    <label class="col-md-2 control-label" for="url">URL: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="url" id="url" value="<?php echo $row_rsConfig['url']; ?>">
                    </div>
                    <label class="col-md-2 control-label" for="texto_link">Texto URL: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $row_rsConfig['texto_link']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="telefone">Contacto: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo $row_rsConfig['telefone']; ?>">
                      <p class="help-block">Máximo: 30 caracteres</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_conteudo" />
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