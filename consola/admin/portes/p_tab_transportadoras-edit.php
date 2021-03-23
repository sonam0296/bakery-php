<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='portes';
$menu_sub_sel='tab_transportadoras';

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "tab_transp")) {
	if($_POST['nome'] != '') {
		$insertSQL = "UPDATE transportadoras SET nome=:nome, kg=:kg, preco=:preco WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST["nome"], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':kg', $_POST["kg"], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':preco', $_POST["preco"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->execute();
		DB::close();
		
		header("Location: p_tab_transportadoras-edit.php?id=".$id."&alt=1");
	}
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "bannerForm2")) {
	$query_rsP2 = "SELECT id FROM transp_valores WHERE id_transp=:id ORDER BY min ASC, max ASC";
	$rsP2 = DB::getInstance()->prepare($query_rsP2);
	$rsP2->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsP2->execute();
	$totalRows_rsP2 = $rsP2->rowCount();
	
	if($totalRows_rsP2 > 0) {
		while($row_rsP2 = $rsP2->fetch()) {
			$id_valor = $row_rsP2['id'];
			
			if(isset($_POST['remover_'.$id_valor])) {
				$insertSQL = "DELETE FROM transp_valores WHERE id=:id_valor";	
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->bindParam(':id_valor', $id_valor, PDO::PARAM_INT);	
				$rsInsertSQL->execute();
			}
      else {
				$insertSQL = "UPDATE transp_valores SET min=:min, max=:max, preco=:preco WHERE id=:id_valor";	
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->bindParam(':min', $_POST['min_'.$id_valor], PDO::PARAM_STR, 5);	
				$rsInsertSQL->bindParam(':max', $_POST['max_'.$id_valor], PDO::PARAM_STR, 5);	
				$rsInsertSQL->bindParam(':preco', $_POST['preco_'.$id_valor], PDO::PARAM_STR, 5);	
				$rsInsertSQL->bindParam(':id_valor', $id_valor, PDO::PARAM_INT);	
				$rsInsertSQL->execute();
			}
		}
	}
	
	if($_POST['min_n']!="" && $_POST['max_n']!="" && $_POST['preco_n']!="") {
		$insertSQL = "INSERT INTO transp_valores (id_transp, min, max, preco) VALUES (:id_transp, :min, :max, :preco)";	
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->bindParam(':min', $_POST['min_n'], PDO::PARAM_STR, 5);	
		$rsInsertSQL->bindParam(':max', $_POST['max_n'], PDO::PARAM_STR, 5);	
		$rsInsertSQL->bindParam(':preco', $_POST['preco_n'], PDO::PARAM_STR, 5);	
		$rsInsertSQL->bindParam(':id_transp', $id, PDO::PARAM_INT);	
		$rsInsertSQL->execute();
	}
	
	header("Location: p_tab_transportadoras-edit.php?id=".$id."&alt=1");
}

$query_rsP = "SELECT * FROM transportadoras WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);  
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsTranspValore = "SELECT * FROM transp_valores WHERE id_transp=:id ORDER BY min ASC, max ASC";
$rsTranspValore = DB::getInstance()->prepare($query_rsTranspValore);
$rsTranspValore->bindParam(':id', $id, PDO::PARAM_INT);	
$rsTranspValore->execute();
$totalRows_rsTranspValore = $rsTranspValore->rowCount();

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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['portes_transp_page_title']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
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
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='p_tab_transportadoras.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <div class="modal fade" id="modal_instrucoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><strong><?php echo $RecursosCons->RecursosCons['instrucoes_label']; ?></strong></h4>
            </div>
            <div class="modal-body">
              <?php echo $RecursosCons->RecursosCons['info_peso1']; ?>
              <br><br>
              <?php echo $RecursosCons->RecursosCons['info_peso2']; ?>
              <br><br>
              <?php echo $RecursosCons->RecursosCons['exemplo_label']; ?>:
              <br><br>
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr role="row" class="heading">
                    <th width="33%"> <?php echo $RecursosCons->RecursosCons['peso_min_kg']; ?> </th>
                    <th width="33%"> <?php echo $RecursosCons->RecursosCons['peso_max_kg']; ?> </th>
                    <th width="33%"> <?php echo $RecursosCons->RecursosCons['preco']; ?> (€) </th>
                  </tr>
                  <tr role="row">
                    <td>0.000</td>
                    <td>0.500</td>
                    <td>3.00</td>
                  </tr>
                  <tr role="row">
                    <td>0.501</td>
                    <td>1.000</td>
                    <td>5.00</td>
                  </tr>
                  <tr role="row">
                    <td>1.001</td>
                    <td>2.000</td>
                    <td>7.50</td>
                  </tr>
                </thead>
              </table>
              <?php echo $RecursosCons->RecursosCons['info_prod_com_peso']; ?> 
            </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['btn_fechar']; ?> </button>
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
          <form id="tab_transp" name="tab_transp" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $row_rsP["nome"]; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='p_tab_transportadoras.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?> </a> </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <?php if($_GET["ins"] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <span> <?php echo $RecursosCons->RecursosCons['ins']; ?>  </span> 
                    </div>
                  <?php } ?>
                  <?php if($_GET["alt"] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <span> <?php echo $RecursosCons->RecursosCons['alt']; ?>  </span> 
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="kg"><strong><?php echo $RecursosCons->RecursosCons['kg_adicional']; ?>:</strong> </label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="kg" id="kg" value="<?php echo $row_rsP['kg']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon"><strong>Kg</strong></span>
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="preco"><strong><?php echo $RecursosCons->RecursosCons['acresce_preco']; ?>: </strong></label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="preco" id="preco" value="<?php echo $row_rsP['preco']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon"><strong>€</strong></span>
                      </div>                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="tab_transp" />
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" style="padding-top:30px">
          <form id="bannerForm2" name="bannerForm2" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="form-group" style="padding-bottom: 10px;">
              <div class="col-md-8">
                <button class="btn btn-sm yellow" type="button" href="#modal_instrucoes" data-toggle="modal" title="Clique aqui para ver as instruções"><i class="fa fa-list-ol"></i> <?php echo $RecursosCons->RecursosCons['ver_instrucoes_label']; ?></button>
                <br><br>
                <label class="label label-danger"><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>:</label> <strong><?php echo $RecursosCons->RecursosCons['clique_txt']; ?> <a href="../../imgs/exemplo_tabela.jpg" data-fancybox style="font-size: 14px; color: red;"><?php echo $RecursosCons->RecursosCons['aqui_txt']; ?></a> <?php echo $RecursosCons->RecursosCons['exemplo_preenchimento_tab']; ?></strong>
              </div>
              <div class="col-md-4" style="text-align: right;">
                <button class="btn btn-sm green" id="bt_submete" type="submit"><i class="fa fa-refresh"></i> <?php echo $RecursosCons->RecursosCons['guarda_alt']; ?></button>
              </div>
            </div>
            <?php if(isset($_GET['r']) && $_GET['r']==1 && $erro_validar_insercao==0 && $erro_validar_duplicado==0 && $inserido==0) { ?>
              <div class="alert alert-danger display-show">
                <button class="close" data-close="alert"></button>
                <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> 
              </div>
            <?php } ?>
            <div class="portlet box green">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-list"></i><?php echo $RecursosCons->RecursosCons['valores_inseridos']; ?> </div>
                <div class="tools"> <a href="javascript:;" class="collapse"> </a> <a href="javascript:;" class="reload"> </a> </div>
              </div>
              <div class="portlet-body">
                <div class="table-scrollable">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th width="10%"><?php echo $RecursosCons->RecursosCons['rem.']; ?></th>
                        <th width="20%"><?php echo $RecursosCons->RecursosCons['peso_min']; ?></th>
                        <th width="20%"><?php echo $RecursosCons->RecursosCons['peso_max']; ?></th>
                        <th width="20%"><?php echo $RecursosCons->RecursosCons['preco']; ?></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($totalRows_rsTranspValore > 0) { ?>
                        <?php while($row_rsTranspValore = $rsTranspValore->fetch()) { ?>
                          <tr>
                            <td>
                              <label>
                                <input type="checkbox" name="remover_<?php echo $row_rsTranspValore['id'];?>" id="remover_<?php echo $row_rsTranspValore['id'];?>" />
                              </label>
                            </td>
                            <td>
                              <div class="input-group">
                                <input class="form-control" name="min_<?php echo $row_rsTranspValore['id'];?>" id="min_<?php echo $row_rsTranspValore['id'];?>" type="text" value="<?php echo $row_rsTranspValore['min'];?>" onkeyup="onlyDecimal(this)" />
                                <span class="input-group-addon">kg</span> 
                              </div>
                            </td>
                            <td>
                              <div class="input-group">
                                <input class="form-control" name="max_<?php echo $row_rsTranspValore['id'];?>" id="max_<?php echo $row_rsTranspValore['id'];?>" type="text" value="<?php echo $row_rsTranspValore['max'];?>" onkeyup="onlyDecimal(this)" />
                                <span class="input-group-addon">kg</span> 
                              </div>
                            </td>
                            <td>
                              <div class="input-group">
                                <input class="form-control" name="preco_<?php echo $row_rsTranspValore['id'];?>" id="preco_<?php echo $row_rsTranspValore['id'];?>" type="text" value="<?php echo $row_rsTranspValore['preco'];?>" onkeyup="onlyDecimal(this)" />
                                <span class="input-group-addon">&pound;</span> 
                              </div>
                            </td>
                            <td>&nbsp;</td>
                          </tr>
                        <?php }
                      } ?>
                      <tr>
                        <td>&nbsp;</td>
                        <td>
                          <div class="input-group">
                            <input class="form-control" name="min_n" id="min_n" type="text" value="" onkeyup="onlyDecimal(this)" />
                            <span class="input-group-addon">kg</span> 
                          </div>
                        </td>
                        <td>
                          <div class="input-group">
                            <input class="form-control" name="max_n" id="max_n" type="text" value="" onkeyup="onlyDecimal(this)" />
                            <span class="input-group-addon">kg</span> 
                          </div>
                        </td>
                        <td>
                          <div class="input-group">
                            <input class="form-control" name="preco_n" id="preco_n" type="text" value="" onkeyup="onlyDecimal(this)" />
                            <span class="input-group-addon">&pound;</span> 
                          </div>
                        </td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="bannerForm2" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script type="text/javascript">
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   FormValidation.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>