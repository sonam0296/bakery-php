<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='portes';
$menu_sub_sel='portes_gratis';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "portes_gratis_form")) {
  if($_POST['nome'] != '') {
    $insertSQL = "SELECT MAX(id) FROM portes_gratis";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    
    $max_id = $row_rsInsert["MAX(id)"] + 1;

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
    
    $insertSQL = "INSERT INTO portes_gratis (id, nome, datai, dataf, min_encomenda, peso_max, visivel) VALUES (:id, :nome, :datai, :dataf, :min_encomenda, :peso_max, '0')";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':nome', $_POST["nome"], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':min_encomenda', $_POST["min_encomenda"], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':peso_max', $_POST["peso_max"], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);  
    $rsInsert->execute();
    
    DB::close();
    
    header("Location: p_portes_gratis-edit.php?id=".$max_id."&ins=1");
  }
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['portes_gratis_page_title']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="portes_gratis_form" name="portes_gratis_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['novo_registo']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='p_portes_gratis.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['inicio_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $_POST['datai']; ?>" data-required="1">
                        <span class="input-group-btn">
                          <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                      </div>
                    </div>
                    <label class="control-label col-md-2"><?php echo $RecursosCons->RecursosCons['hora_inicio_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control timepicker timepicker-24" name="horai" id="horai" placeholder="Hora" value="<?php echo (isset($_POST['datai']) ? $_POST['datai'] : "0:00");?>">
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
                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo $_POST['dataf']; ?>" data-required="1">
                        <span class="input-group-btn">
                          <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                    <label class="control-label col-md-2"><?php echo $RecursosCons->RecursosCons['hora_fim_label']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control timepicker timepicker-24" name="horaf" id="horaf" placeholder="Hora" value="<?php echo (isset($_POST['datai']) ? $_POST['datai'] : "23:59");?>">
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
                        <input type="text" class="form-control" name="min_encomenda" id="min_encomenda" value="<?php echo $_POST['min_encomenda']; ?>">
                        <span class="input-group-addon">&pound;</span> 
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="peso_max"><?php echo $RecursosCons->RecursosCons['peso_max_portes']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="peso_max" id="peso_max" value="<?php echo $_POST['peso_max']; ?>" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/components-pickers.js"></script>
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
  ComponentsPickers.init();
  FormValidation.init();
});
</script>
</body>
<!-- END BODY -->
</html>