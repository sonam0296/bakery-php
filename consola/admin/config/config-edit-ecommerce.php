<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='ecommerce';

$inserido=0;
$erro_password=0;
$tab_sel=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
  $ativar_vales = 0;
  if(isset($_POST['ativar_vales']))
    $ativar_vales = 1;
		
  $insertSQL = "UPDATE config_ecommerce SET ativar_vales=:ativar_vales, valor_embrulho=:valor_embrulho, max_saldo=:max_saldo, euros=:euros, pontos=:pontos, euro_saldo=:euro_saldo, saldo_por_compra_amigo=:saldo_por_compra_amigo, tipo_compra_amigo=:tipo_compra_amigo, valor_por_compra_amigo=:valor_por_compra_amigo  WHERE id='1'";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':ativar_vales', $ativar_vales, PDO::PARAM_INT); 
  $rsInsert->bindParam(':valor_embrulho', $_POST['valor_embrulho'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':max_saldo', $_POST['max_saldo'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':euros', $_POST['euro_pontos'], PDO::PARAM_STR, 5); 
  $rsInsert->bindParam(':euro_saldo', $_POST['euro_saldo'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':pontos', $_POST['pontos'], PDO::PARAM_INT);
  $rsInsert->bindParam(':saldo_por_compra_amigo', $_POST['saldo_por_compra_amigo'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':tipo_compra_amigo', $_POST['radio_desconto'], PDO::PARAM_INT);
  $rsInsert->bindParam(':valor_por_compra_amigo', $_POST['valor_por_compra_amigo'], PDO::PARAM_STR, 5);
  $rsInsert->execute();
  DB::close();
	
	$inserido=1;
}

$query_rsP = "SELECT * FROM config_ecommerce WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
  .form-horizontal .radio {
    padding-top: 2px;
  }
</style>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['config_ecommerce']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php if($totalRows_rsP>0){ ?>
          <form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data" class="form-horizontal">
          <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-desktop"></i><?php echo $RecursosCons->RecursosCons['eCommerce']; ?> </div>
                <div class="actions btn-set">
                  <button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['config_alt']; ?> 
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="ativar_vales" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['ativar_vales']; ?>:</label>
                    <div class="col-md-10">
                      <input type="checkbox" class="form-control" name="ativar_vales" id="ativar_vales" value="1" <?php if($row_rsP['ativar_vales'] == 1) echo "checked";?>>
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['help_block_vales']; ?></p>
                    </div>
                  </div>
                  <?php if(CARRINHO_SALDO == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['saldo_clientes_label']; ?></strong></label>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="max_saldo"><?php echo $RecursosCons->RecursosCons['maximo_saldo_label']; ?>:</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="max_saldo" id="max_saldo" value="<?php echo $row_rsP['max_saldo']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                          <span class="input-group-addon">€</span>
                        </div>  
                      </div>
                    </div>
                  <?php } ?>
                  <?php if(CARRINHO_EMBRULHO == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['embrulho_presente_label']; ?></strong></label>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="valor_embrulho"><?php echo $RecursosCons->RecursosCons['custo_label']; ?>:</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="valor_embrulho" id="valor_embrulho" value="<?php echo $row_rsP['valor_embrulho']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                          <span class="input-group-addon">€</span>
                        </div>  
                      </div>
                    </div>
                  <?php } ?>
                  <?php if(CARRINHO_PONTOS == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['pontos_label']; ?></strong></label>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="euro_pontos"><?php echo $RecursosCons->RecursosCons['conversao_euros_pontos_label']; ?>:</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="euro_pontos" id="euro_pontos" value="<?php echo $row_rsP['euros']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                          <span class="input-group-addon">€ &nbsp;=&nbsp; <?php echo $RecursosCons->RecursosCons['1_ponto']; ?></span>
                        </div>  
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="pontos"><?php echo $RecursosCons->RecursosCons['conversao_pontos_euros_label']; ?>:</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="pontos" id="pontos" value="<?php echo $row_rsP['pontos']; ?>" maxlength="8" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon"><?php echo $RecursosCons->RecursosCons['pontos_label']; ?></span>
                        </div>  
                      </div>
                      <label class="col-md-1" style="text-align: center; padding-top: 7px"> <strong>=</strong> </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="euro_saldo" id="euro_saldo" value="<?php echo $row_rsP['euro_saldo']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                          <span class="input-group-addon"><?php echo $RecursosCons->RecursosCons['saldo_€_label']; ?></span>
                        </div>  
                      </div>
                    </div>
                  <?php } ?>
                  <?php if(CARRINHO_CONVIDAR == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['convidar_amigos_label']; ?></strong></label>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="valor_por_compra_amigo"><?php echo $RecursosCons->RecursosCons['compra_superior_label']; ?>: </label>
                      <div class="col-md-2">
                        <div class="input-group">
                          <input type="text" class="form-control" name="valor_por_compra_amigo" id="valor_por_compra_amigo" value="<?php echo $row_rsP['valor_por_compra_amigo']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                          <span class="input-group-addon">€</span>
                        </div>  
                      </div>
                      <label class="col-md-2 control-label" for="saldo_por_compra_amigo"><?php echo $RecursosCons->RecursosCons['recebe_saldo_label']; ?>: </label>
                      <div class="col-md-2">
                        <div class="input-group">
                          <input type="text" class="form-control" name="saldo_por_compra_amigo" id="saldo_por_compra_amigo" value="<?php echo $row_rsP['saldo_por_compra_amigo']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                          <span id="span_desconto" class="input-group-addon"><?php if($row_rsP['tipo_compra_amigo'] == 1) echo "%"; else echo "&pound;"; ?></span>
                        </div>  
                      </div>
                      <div class="col-md-2 md-radio-inline">
                          <div class="md-radio">
                            <input type="radio" id="desc_perc" name="radio_desconto" class="md-radiobtn" value="1" <?php if($row_rsP['tipo_compra_amigo'] == 1) echo "checked"; ?>>
                            <label for="desc_perc">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            % </label>
                          </div>
                          <div class="md-radio">
                            <input type="radio" id="desc_preco" name="radio_desconto" class="md-radiobtn" value="2" <?php if($row_rsP['tipo_compra_amigo'] == 2) echo "checked"; ?>>
                            <label for="desc_preco">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            € </label>
                          </div>
                        </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="alterar" />
          </form>
          <?php } ?>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/components-pickers.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS --> 
<?php
$ip=$_SERVER['REMOTE_ADDR'];

if($ip==""){
	$ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
}
?>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  ComponentsPickers.init();
});
function addRemoteAddr(){
var length = $('input[name=ips]').attr('value').length;
if (length > 0)
$('input[name=ips]').attr('value',$('input[name=ips]').attr('value') +',<?php echo $ip; ?>');
else
$('input[name=ips]').attr('value','<?php echo $ip; ?>');
}
</script>
</body>
<!-- END BODY -->
</html>