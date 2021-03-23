<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='carrinho';
$menu_sub_sel='enviados';

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_carrinho']; ?> <small><?php echo $RecursosCons->RecursosCons['listagem']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?> </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="listagem.php">Enviados </a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12"> 
          <!-- Begin: life time stats -->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i>Enviados </div>
            </div>
            <div class="portlet-body">
              <div class="table-container">
                <table class="table table-striped table-bordered table-hover" id="datatable_products">
                  <thead>
                    <tr role="row" class="heading">
                      <th width="20%"> Nome </th>
                      <th width="20%"> Email </th>
                      <th width="12%"> Data Enviado </th>
                      <th width="10%"> Aberto </th>
                      <th width="12%"> Data Aberto </th>
                      <th width="10%"> Ações </th>
                    </tr>
                    <tr role="row" class="filter">
                      <td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
                      <td><input type="text" class="form-control form-filter input-sm" name="form_email" onKeyPress="submete(event)"></td>
                      <td>
                        <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                          <input type="text" class="form-control form-filter input-sm" readonly name="form_data_enviado" placeholder="Selecione">
                          <span class="input-group-btn">
                          <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                        </div>
                      </td>
                      <td><select name="form_aberto" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                          <option value="">Todos</option>
                          <option value="1">Sim</option>
                          <option value="0">Não</option>
                        </select>
                      </td>
                      <td>
                        <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                          <input type="text" class="form-control form-filter input-sm" readonly name="form_data_aberto" placeholder="Selecione">
                          <span class="input-group-btn">
                          <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                        </div>
                      </td>
                      <td><div class="margin-bottom-5">
                          <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Pesquisar</button>
                        </div>
                        <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Limpar</button></td>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- End: life time stats --> 
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/media/js/jquery.dataTables.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<!-- LINGUA PORTUGUESA -->
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/scripts/datatable.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<?php /*?><script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/ecommerce-products.js"></script><?php */?>
<script src="listagem-list.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {  
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  List.init();
});
</script> 
</body>
<!-- END BODY -->
</html>