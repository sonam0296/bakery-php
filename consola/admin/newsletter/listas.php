<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_lista';
$menu_sub_sel='';

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	
		
		$query_rsP = "DELETE FROM news_listas WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$rsP->execute();
		
		$query_rsP = "DELETE FROM news_emails_listas WHERE lista=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$rsP->execute();
    
		DB::close();
		
		header("Location: listas.php?r=1");
	}
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['news_page_title_list']; ?> <small> <?php echo $RecursosCons->RecursosCons['listagem']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['newsletters']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="listas.php"><?php echo $RecursosCons->RecursosCons['lista_correio']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <?php if(isset($_GET['r']) && $_GET['r']==1){ ?>
      <div class="alert alert-danger display-show">
        <button class="close" data-close="alert"></button>
        <span> <?php echo $RecursosCons->RecursosCons['r']; ?></span> </div>
      <?php } ?>
      <?php if(isset($_GET['alt']) && $_GET['alt']==1){ ?>
      <div class="alert alert-success display-show">
        <button class="close" data-close="alert"></button>
        <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-12"> 
          <!-- Begin: life time stats -->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-folder"></i><?php echo $RecursosCons->RecursosCons['news_page_title_list']; ?> </div>
              <div class="actions"> <a href="listas-insert.php" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"><?php echo $RecursosCons->RecursosCons['nova_lista']; ?> </span> </a> </div>
            </div>
            <div class="portlet-body">
              <div class="table-container">
                <div class="table-actions-wrapper"> <span> </span>
                  <select class="table-group-action-input form-control input-inline input-small input-sm">
                    <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecione']; ?></option>
                    <option value="-1"><?php echo $RecursosCons->RecursosCons['eliminar']; ?></option>
                  </select>
                  <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['submeter']; ?></button>
                  <button class="btn btn-sm green" id="bt_submete" type="button"><i class="fa fa-refresh"></i> <?php echo $RecursosCons->RecursosCons['altera_order']; ?></button>
                </div>
                <table class="table table-striped table-bordered table-hover" id="datatable_products">
                  <thead>
                    <tr role="row" class="heading">
                      <th width="1%"> <input type="checkbox" class="group-checkable">
                      </th>
                      <th> <?php echo $RecursosCons->RecursosCons['nome']; ?> </th>
                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['inscritos']; ?> </th>
                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['ordem']; ?> </th>
                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['acoes']; ?> </th>
                    </tr>
                    <tr role="row" class="filter">
                      <td></td>
                      <td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
                      <td></td>
                      <td></td>
                      <td><div class="margin-bottom-5">
                          <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <?php echo $RecursosCons->RecursosCons['pesquisar']; ?></button>
                        </div>
                        <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button></td>
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
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<!--     --> 
<script src="listas-list.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {  
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
    ConteudoDados.init();
});
</script> 
<script type="text/javascript">
function submete(e) {
    if (e.keyCode == 13) {
        document.getElementById('pesquisa').click();
    }
}
function alteraOrdem(e) {
    if (e.keyCode == 13) {
        document.getElementById('bt_submete').click();
    }
}
</script>
</body>
<!-- END BODY -->
</html>