<?php include_once("../inc_pages.php"); ?>
<?php 

$menu_sel="portes";
$menu_sub_sel="portes_gratis";

$id=$_GET["id"];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "portes_gratis_form")) {
	if($_POST["nome"] != '') {
		$datai = NULL;
    if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") {
      $datai = $_POST['datai'];
    }

    $dataf = NULL;
    if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") {
      $dataf = $_POST['dataf'];
    }

    $horai = NULL;
    if(isset($_POST['horai']) && $_POST['horai'] != '') {
      $horai = $_POST['horai'];
    }

    $horaf = NULL;
    if(isset($_POST['horaf']) && $_POST['horaf'] != '') {
      $horaf = $_POST['horaf'];
    }

    if($horai) {
      $datai .= ' '.$horai;
    }

    if($horaf) {
      $dataf .= ' '.$horaf;
    }

		$insertSQL = "UPDATE portes_gratis SET nome=:nome, datai=:datai, dataf=:dataf, min_encomenda=:min_encomenda, peso_max=:peso_max WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(":nome", $_POST["nome"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(":datai", $datai, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(":dataf", $dataf, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(":min_encomenda", $_POST["min_encomenda"], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(":peso_max", $_POST["peso_max"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(":id", $id, PDO::PARAM_INT, 5);	
		$rsInsert->execute();

		DB::close();
		
		header("Location: p_portes_gratis-edit.php?id=".$id."&alt=1");
	}
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "zonasForm")) {
	$insertSQL = "DELETE FROM portes_gratis_zonas WHERE portes_gratis=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);
	$rsInsert->execute();
	DB::close();	
	
	for($i=0; $i<sizeof($_POST["zonas"]); $i++) {
		$zona=$_POST["zonas"][$i];
			
		$insertSQL = "INSERT INTO portes_gratis_zonas (portes_gratis, zona) VALUES (:id, :zona)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);
		$rsInsert->bindParam(":zona", $zona, PDO::PARAM_INT);
		$rsInsert->execute();
		DB::close();	
	}
	
	header("Location: p_portes_gratis-edit.php?id=".$id."&alt=1");
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "categoriasForm")) {
	$insertSQL = "DELETE FROM portes_gratis_categorias WHERE portes_gratis=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);
	$rsInsert->execute();
	DB::close();	
	
	for($i=0; $i<sizeof($_POST["categorias"]); $i++) {
		$categoria=$_POST["categorias"][$i];
			
		$insertSQL = "INSERT INTO portes_gratis_categorias (portes_gratis, categoria) VALUES (:id, :categoria)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);
		$rsInsert->bindParam(":categoria", $categoria, PDO::PARAM_INT);
		$rsInsert->execute();
		DB::close();	
	}
	
	header("Location: p_portes_gratis-edit.php?id=".$id."&alt=1");
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "marcasForm")) {
  $insertSQL = "DELETE FROM portes_gratis_marcas WHERE portes_gratis=:id";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(":id", $id, PDO::PARAM_INT);
  $rsInsert->execute();
  DB::close();  
  
  for($i=0; $i<sizeof($_POST["marcas"]); $i++) { 
    $marca=$_POST["marcas"][$i];
      
    $insertSQL = "INSERT INTO portes_gratis_marcas (portes_gratis, marca) VALUES (:id, :marca)";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(":id", $id, PDO::PARAM_INT);
    $rsInsert->bindParam(":marca", $marca, PDO::PARAM_INT);
    $rsInsert->execute();
    DB::close();   
  }
  
  header("Location: p_portes_gratis-edit.php?id=".$id."&alt=1");
}

$query_rsP = "SELECT * FROM portes_gratis WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(":id", $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$parts1 = explode(' ', $row_rsP['datai']);
$parts2 = explode(' ', $row_rsP['dataf']);

$data_inicio = $parts1[0];
$hora_inicio = $parts1[1];

$data_fim = $parts2[0];
$hora_fim = $parts2[1];

$query_rsPortes2 = "SELECT * FROM zonas WHERE id NOT IN (SELECT zona FROM portes_gratis_zonas WHERE portes_gratis=:id) ORDER BY zonas.nome ASC";
$rsPortes2 = DB::getInstance()->prepare($query_rsPortes2);
$rsPortes2->bindParam(":id", $id, PDO::PARAM_INT);	
$rsPortes2->execute();
$totalRows_rsPortes2 = $rsPortes2->rowCount();
DB::close();

$query_rsPortes = "SELECT * FROM zonas WHERE id IN (SELECT zona FROM portes_gratis_zonas WHERE portes_gratis=:id) ORDER BY zonas.nome ASC";
$rsPortes = DB::getInstance()->prepare($query_rsPortes);
$rsPortes->bindParam(":id", $id, PDO::PARAM_INT);	
$rsPortes->execute();
$totalRows_rsPortes = $rsPortes->rowCount();
DB::close();

$query_rsCat = "SELECT * FROM l_categorias_pt WHERE cat_mae = 0 ORDER BY nome ASC";
$rsCat = DB::getInstance()->prepare($query_rsCat);
$rsCat->bindParam(":id", $id, PDO::PARAM_INT);	
$rsCat->execute();
$totalRows_rsCat = $rsCat->rowCount();
DB::close();

$query_rsMarcas = "SELECT * FROM l_marcas_pt ORDER BY nome ASC";
$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
$rsMarcas->bindParam(":id", $id, PDO::PARAM_INT);  
$rsMarcas->execute();
$totalRows_rsMarcas = $rsMarcas->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN."inc_head_1.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN."inc_head_2.php"); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN."inc_topo.php"); ?>
<div class="clearfix"> </div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once(ROOTPATH_ADMIN."inc_menu.php"); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content"> 
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title"> Portes » Portes Grátis </h3>
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
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?>  </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='p_portes_gratis.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
        </div>
      </div>
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="portes_gratis_form" name="portes_gratis_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $row_rsP["nome"]; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='p_portes_gratis.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <?php if($_GET["ins"] == 1) { ?>
                  	<div class="alert alert-success display-show">
	                    <button class="close" data-close="alert"></button>
	                    <span> <?php echo $RecursosCons->RecursosCons['ins']; ?> </span> 
                    </div>
                  <?php } ?>
                  <?php if($_GET["alt"] == 1) { ?>
                  	<div class="alert alert-success display-show">
	                    <button class="close" data-close="alert"></button>
	                    <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-9">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP["nome"]; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['inicio_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="<?php echo $RecursosCons->RecursosCons['data_label']; ?>" id="datai" value="<?php echo $data_inicio; ?>" data-required="1">
                        <span class="input-group-btn">
                       		<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                    <label class="control-label col-md-2"><?php echo $RecursosCons->RecursosCons['hora_inicio_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control timepicker timepicker-24" name="horai" id="horai" placeholder="Hora" value="<?php echo (isset($hora_inicio) ? $hora_inicio : "0:00");?>">
                        <span class="input-group-btn">
                        	<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="dataf"><?php echo $RecursosCons->RecursosCons['fim_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="<?php echo $RecursosCons->RecursosCons['data_label']; ?>" id="dataf" value="<?php echo $data_fim; ?>" data-required="1">
                        <span class="input-group-btn">
                        	<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                    <label class="control-label col-md-2"><?php echo $RecursosCons->RecursosCons['hora_fim_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control timepicker timepicker-24" name="horaf" id="horaf" placeholder="Hora" value="<?php echo (isset($hora_fim) ? $hora_fim : "23:59");?>">
                        <span class="input-group-btn">
                        	<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="min_encomenda"><?php echo $RecursosCons->RecursosCons['minimo_encomenda']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="min_encomenda" id="min_encomenda" value="<?php echo $row_rsP['min_encomenda']; ?>">
                        <span class="input-group-addon">&pound;</span> 
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="peso_max"><?php echo $RecursosCons->RecursosCons['peso_max_portes']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="peso_max" id="peso_max" value="<?php echo $row_rsP['peso_max']; ?>" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon">kg</span> 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="portes_gratis_form" />
          </form>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="row" style="margin-top:20px">
        <div class="col-md-12">
          <?php if ($totalRows_rsPortes > 0 || $totalRows_rsPortes2 > 0) { ?>
          <div class="col-md-6">
            <form id="zonasForm" name="zonasForm" class="form-horizontal form-row-seperated" method="post" role="form">
              <div class="portlet">
                <div class="portlet-body">
                  <div class="form-body">
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="zonas"><?php echo $RecursosCons->RecursosCons['zonas_label']; ?>:</label>
                      <div class="col-md-8">
                        <div class="form-control height-auto">
                          <div class="scroller" style="height:275px;" data-always-visible="1">
                            <ul class="list-unstyled">
                              <?php if($totalRows_rsPortes >0){ ?>
                              <?php while($row_rsPortes = $rsPortes->fetch()) { 
              							  	$tamanho=$row_rsPortes["id"];
              								
                								$query_rsP = "SELECT * FROM portes_gratis_zonas WHERE portes_gratis=:id AND zona='$tamanho'";
                								$rsP = DB::getInstance()->prepare($query_rsP);
                								$rsP->bindParam(":id", $_GET["id"], PDO::PARAM_INT, 5);	
                								$rsP->execute();
                								$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
                								$totalRows_rsP = $rsP->rowCount();
                								DB::close();
              							  ?>
                              <li>
                                <label>
                                  <input type="checkbox" name="zonas[]" value="<?php echo $row_rsPortes["id"]; ?>" <?php if($totalRows_rsP>0){?>checked="checked"<?php }?>>
                                  <?php echo $row_rsPortes["nome"]; ?></label>
                              </li>
                              <?php } ?>
                              <?php } ?>
                              <?php if($totalRows_rsPortes2 >0){ ?>
                              <?php while($row_rsPortes2 = $rsPortes2->fetch()) { 
              							  	$tamanho=$row_rsPortes2["id"];
              								
                								$query_rsP = "SELECT * FROM portes_gratis_zonas WHERE portes_gratis=:id AND zona='$tamanho'";
                								$rsP = DB::getInstance()->prepare($query_rsP);
                								$rsP->bindParam(":id", $_GET["id"], PDO::PARAM_INT, 5);	
                								$rsP->execute();
                								$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
                								$totalRows_rsP = $rsP->rowCount();
                								DB::close();
              							  ?>
                              <li>
                                <label>
                                  <input type="checkbox" name="zonas[]" value="<?php echo $row_rsPortes2["id"]; ?>" <?php if($totalRows_rsP>0){?>checked="checked"<?php }?>>
                                  <?php echo $row_rsPortes2["nome"]; ?></label>
                              </li>
                              <?php } ?>
                              <?php } ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row" align="right">
                      <div class="col-md-2">&nbsp;</div>
                      <div class="col-md-8">
                        <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="zonasForm" />
            </form>
          </div>
          <?php } ?><?php if ($totalRows_rsCat > 0) { ?>
          <div class="col-md-6">
            <form id="categoriasForm" name="categoriasForm" class="form-horizontal form-row-seperated" method="post" role="form">
              <div class="portlet">
                <div class="portlet-body">
                  <div class="form-body">
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="categorias"><?php echo $RecursosCons->RecursosCons['categorias']; ?>:</label>
                      <div class="col-md-8">
                        <div class="form-control height-auto">
                          <div class="scroller" style="height:275px;" data-always-visible="1">
                            <ul class="list-unstyled">
                              <?php variasCategoriasPorProd(0, $_GET["id"], 2); ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row" align="right">
                      <div class="col-md-2">&nbsp;</div>
                      <div class="col-md-8">
                        <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="categoriasForm" />
            </form>
          </div>
          <?php } ?><?php if ($totalRows_rsMarcas > 0) { ?>
          <div class="col-md-6">
            <form id="marcasForm" name="marcasForm" class="form-horizontal form-row-seperated" method="post" role="form">
              <div class="portlet">
                <div class="portlet-body">
                  <div class="form-body">
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="marcas"><?php echo $RecursosCons->RecursosCons['marcas']; ?>:</label>
                      <div class="col-md-8">
                        <div class="form-control height-auto">
                          <div class="scroller" style="height:275px;" data-always-visible="1">
                            <ul class="list-unstyled">
                              <?php if($totalRows_rsMarcas >0){ ?>
                              <?php while($row_rsMarcas = $rsMarcas->fetch()) { ?>
                                <li>
                                  <?php 
                                    $tamanho=$row_rsMarcas["id"];
                                  
                                    $query_rsP = "SELECT * FROM portes_gratis_marcas WHERE portes_gratis=:id AND marca='$tamanho'";
                                    $rsP = DB::getInstance()->prepare($query_rsP);
                                    $rsP->bindParam(":id", $_GET["id"], PDO::PARAM_INT, 5); 
                                    $rsP->execute();
                                    $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
                                    $totalRows_rsP = $rsP->rowCount();
                                    DB::close();
                                  ?>
                                  <label>
                                    <input type="checkbox" name="marcas[]" value="<?php echo $row_rsMarcas["id"]; ?>" <?php if($totalRows_rsP>0){?>checked="checked"<?php }?>>
                                    <?php echo $row_rsMarcas["nome"]; ?></label>
                                </li>
                              <?php } ?>
                              <?php } ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row" align="right">
                      <div class="col-md-2">&nbsp;</div>
                      <div class="col-md-8">
                        <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="marcasForm" />
            </form>
          </div>
          <?php } ?>
          </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once(ROOTPATH_ADMIN."inc_quick_sidebar.php"); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN."inc_footer_1.php"); ?>
<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<!-- LINGUA PORTUGUESA --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/components-pickers.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN."inc_footer_2.php"); ?>
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
	ComponentsPickers.init();
	FormValidation.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>