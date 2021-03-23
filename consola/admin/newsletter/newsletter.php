<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	

    $query_rsProc = "SELECT * FROM newsletters_historico WHERE newsletter_id=:id";
    $rsProc = DB::getInstance()->prepare($query_rsProc);
    $rsProc->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $rsProc->execute();
    $totalRows_rsProc = $rsProc->rowCount();
    DB::close();

    while($row = $rsProc->fetch()){
      $query_insertSQL = "DELETE FROM newsletters_historico WHERE id='".$row["id"]."' AND newsletter_id=:id";
      $insertSQL = DB::getInstance()->prepare($query_insertSQL);
      $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
      $insertSQL->execute();
      DB::close();
      
      $query_insertSQL = "DELETE FROM newsletters_historico_listas WHERE newsletter_historico='".$row["id"]."' AND newsletter_id=:id";
      $insertSQL = DB::getInstance()->prepare($query_insertSQL);
      $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
      $insertSQL->execute();
      DB::close();
      
      $query_insertSQL = "DELETE FROM news_links WHERE newsletter_id_historico='".$row["id"]."'";
      $insertSQL = DB::getInstance()->prepare($query_insertSQL);
      $insertSQL->execute();
      DB::close();
      
      $query_insertSQL = "DELETE FROM newsletters_vistos WHERE newsletter_id_historico='".$row["id"]."' AND newsletter_id=:id";
      $insertSQL = DB::getInstance()->prepare($query_insertSQL);
      $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
      $insertSQL->execute();
      DB::close();
        
      $query_insertSQL = "DELETE FROM news_remover WHERE newsletter_id_historico='".$row["id"]."' AND newsletter_id=:id";
      $insertSQL = DB::getInstance()->prepare($query_insertSQL);
      $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
      $insertSQL->execute();
      DB::close();
    }

		$query_rsP = "DELETE FROM newsletters WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$rsP->execute();
		DB::close();
		
		header("Location: newsletters.php?r=1");
	}
}

$query_rsTipos = "SELECT * FROM news_tipos_pt ORDER BY nome ASC";
$rsTipos = DB::getInstance()->prepare($query_rsTipos);
$rsTipos->execute();
$totalRows_rsTipos = $rsTipos->rowCount();
DB::close();

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
      <h3 class="page-title"> Newsletters <small>Listagem</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="newsletter.php">Newsletters</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <?php if(isset($_GET['r']) && $_GET['r'] == 1) { ?>
        <div class="alert alert-danger display-show">
          <button class="close" data-close="alert"></button>
          <span> Registo eliminado com sucesso. </span> 
        </div>
      <?php } ?>
      <?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
        <div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span> Registo alterado com sucesso. </span> 
        </div>
      <?php } ?>
      <?php if(isset($_GET['env']) && $_GET['env'] == 1) { ?>
        <div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span> Registo inserido com sucesso. </span> 
        </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-12"> 
          <!-- Begin: life time stats -->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletters </div>
              <div class="actions"> <a href="newsletter-insert.php" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> Novo Registo </span> </a> </div>
            </div>
            <div class="portlet-body">
              <div class="table-container">
                <div class="table-actions-wrapper"> <span> </span>
                  <select class="table-group-action-input form-control input-inline input-small input-sm">
                    <option value="">Selecione</option>
                    <option value="-1">Eliminar</option>
                  </select>
                  <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submeter</button>
                </div>
                <table class="table table-striped table-bordered table-hover" id="datatable_products">
                  <thead>
                    <tr role="row" class="heading">
                      <th width="1%"> <input type="checkbox" class="group-checkable">
                      </th>
                      <th> Nome </th>
                      <th width="15%"> Tipo </th>
                      <th width="15%"> Criação </th>
                      <th width="15%"> Envio </th>
                      <th width="10%"> &nbsp; </th>
                      <th width="10%"> &nbsp; </th>
                      <th width="8%"> Ações </th>
                    </tr>
                    <tr role="row" class="filter">
                      <td></td>
                      <td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
                      <td><select name="form_tipos" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                          <option value="">Todos</option>
                          <?php if($totalRows_rsTipos > 0) {
                            while($row_rsTipos = $rsTipos->fetch()) { ?>
                              <option value="<?php echo $row_rsTipos['id']; ?>"><?php echo $row_rsTipos['nome']; ?></option>
                            <?php } 
                          } ?>
                        </select></td>
                      <td>
                      	<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="Selecione">
                            <span class="input-group-btn">
                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div></td>
                      <td>
                      	<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-filter input-sm" readonly name="form_envio" placeholder="Selecione">
                            <span class="input-group-btn">
                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div></td>
                      <td></td>
                      <td></td>
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
<script src="newsletter-list.js"></script> 
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
</script>
</body>
<!-- END BODY -->
</html>