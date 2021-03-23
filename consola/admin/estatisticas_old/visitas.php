<?php include_once('../inc_pages.php');
include_once('../funcoes.php'); ?>
<?php 

$menu_sel='visitas';
$menu_sub_sel='';

$query_rsAnalytics = "SELECT * FROM analytics WHERE id='1'";
$rsAnalytics = DB::getInstance()->prepare($query_rsAnalytics);
$rsAnalytics->execute();
$row_rsAnalytics = $rsAnalytics->fetch(PDO::FETCH_ASSOC);
$totalRows_rsAnalytics = $rsAnalytics->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!--DATATABLES-->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<!--DATATABLES-->


<style type="text/css">
.printable_div{
	overflow:auto;
}

.noprintable_div{
	display:block;	
}

@media print{
	.printable_div{
		overflow:visible;
		height:auto;
	}
	
	.noprintable_div{
		display:none;	
	}
}
	
</style>

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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['visitas']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
            <div class="portlet">
              
              <div class="portlet-body">
                <div class="form-body">                  
                	<div style="width: 100%;" align="center">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td height="30" align="center" valign="middle">
                            <div class="noprintable_div">
                            
                            <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:30px;">
                              <tr>
                                <td>
                                <form id="form1" name="form1" method="post" action="javascript:est_visitas_carrega(document.getElementById('tipo').value, document.getElementById('from').value, document.getElementById('to').value);">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="80" align="center"><a href="javascript:imprime();"><img src="../../imgs/iconPrint.gif" width="20" border="0" /></a></td>
                                        <td width="50" align="center"><label class="control-label" for="tipo" id="l_tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?></label></td>
                                        <td align="center">
                                          <label>
                                            <select name="tipo" id="tipo" class="form-control select2me" style="width:200px;">
                                              <option value="geral"><?php echo $RecursosCons->RecursosCons['opt_geral']; ?></option>
                                              <option value="detalhado"><?php echo $RecursosCons->RecursosCons['opt_detalhados']; ?></option>
                                              <option value="fonte"><?php echo $RecursosCons->RecursosCons['opt_fonte']; ?></option>
                                              <option value="pais"><?php echo $RecursosCons->RecursosCons['opt_pais']; ?></option>
                                              <option value="palavra_chave"><?php echo $RecursosCons->RecursosCons['opt_palavras_chave']; ?></option>
                                              <option value="paginas"><?php echo $RecursosCons->RecursosCons['opt_paginas']; ?></option>
                                              <option value="navegador"><?php echo $RecursosCons->RecursosCons['opt_navegador']; ?></option>
                                              <option value="sistema_operativo"><?php echo $RecursosCons->RecursosCons['opt_sist_operativo']; ?></option>
                                                
                                            </select>
                                          </label>
                                        </td>
                                        <td width="40" align="center"><label class="control-label" for="from" id="l_from"><?php echo $RecursosCons->RecursosCons['de_label']; ?></label></td>
                                        <td align="center"><div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                              <input type="text" class="form-control form-filter input-sm" name="from" placeholder="De" id="from" autocomplete="none">
                              <span class="input-group-btn">
                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                              </span> </div></td>
                                        <td width="50" align="center"><label class="control-label" for="to" id="l_to"><?php echo $RecursosCons->RecursosCons['ate_label']; ?></label></td>
                                        <td align="center"><div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                              <input type="text" class="form-control form-filter input-sm" name="to" placeholder="Ate" id="to" autocomplete="none">
                              <span class="input-group-btn">
                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                              </span> </div></td>
                                        <td width="60" align="center"><input type="submit" name="submit" id="submit" value="Ver" class="btn default"/></td>
                                      </tr>
                                    </table>
                                </form>
                                </td>
                              </tr>
                            </table>
                            </div>
                            </td>
                          </tr>
                          <tr>
                         <td align="center"><div id="listagem"></div>
                         <div id="loading">
                            
                        <table width="150" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="350" align="center"><table width="150" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="80" align="center"><img src="../../imgs/loading.gif" width="54" height="55" /></td>
                              </tr>
                              <tr>
                                <td align="center"><?php echo $RecursosCons->RecursosCons['txt_carrega_info']; ?></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                        </div></td>
                      </tr>
                    </table>
                </div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<link rel="stylesheet" type="text/css" media="all" href="analytics/css/estilo.css"/>
<link rel="stylesheet" type="text/css" media="all" href="analytics/css/generic.css"/>

<script type="text/javascript" src="analytics/functions.js"></script>
<!--DATATABLES-->
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="table-advanced.js"></script>
<!--DATATABLES-->

<!-- 3D CHARTS -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>

<script src="charts-amcharts.js"></script>
<!-- 3D CHARTS -->

<!-- LINE CHARTS -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.pie.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.stack.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.crosshair.min.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<!-- LINE CHARTS -->

<script src="est-validation.js"></script> 
<script type="text/javascript">
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   Est.init();
});
</script>
<script type="text/javascript">
	document.ready=est_visitas_carrega("geral", '', '');
	function imprime(){
		print();
	}
	
</script>
</body>
<!-- END BODY -->
</html>