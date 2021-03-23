<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_conteudos';
$menu_sub_sel='';

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	

    $query_rsTema = "SELECT * FROM news_temas WHERE conteudo=:id";
    $rsTema = DB::getInstance()->prepare($query_rsTema);
    $rsTema->bindParam(':id', $id, PDO::PARAM_INT);
    $rsTema->execute();
    $totalRows_rsTema = $rsTema->rowCount();
    DB::close();

    if($totalRows_rsTema > 0) {
      while($row_rsTema = $rsTema->fetch()) {
        $query_rsProdutos = "SELECT * FROM news_produtos WHERE id_tema=:id";
        $rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
        $rsProdutos->bindParam(':id', $row_rsTema['id'], PDO::PARAM_INT);
        $rsProdutos->execute();
        $totalRows_rsProdutos = $rsProdutos->rowCount();
        DB::close();

        if($totalRows_rsProdutos > 0) {
          while($row_rsProdutos = $rsProdutos->fetch()) {
            @unlink('../../../imgs/imgs_news/produtos/'.$row_rsProdutos['imagem1']);
            @unlink('../../../imgs/imgs_news/produtos/'.$row_rsProdutos['imagem2']);
          }

          $query_rsDelete = "DELETE FROM news_produtos WHERE id_tema=:id";
          $rsDelete = DB::getInstance()->prepare($query_rsDelete);
          $rsDelete->bindParam(':id', $row_rsTema['id'], PDO::PARAM_INT);
          $rsDelete->execute();
          DB::close();
        }
      }

      $query_rsDelete = "DELETE FROM news_temas WHERE conteudo=:id";
      $rsDelete = DB::getInstance()->prepare($query_rsDelete);
      $rsDelete->bindParam(':id', $id, PDO::PARAM_INT);
      $rsDelete->execute();
      DB::close();
    }
		
		$query_rsDelete = "DELETE FROM news_conteudo WHERE id=:id";
		$rsDelete = DB::getInstance()->prepare($query_rsDelete);
		$rsDelete->bindParam(':id', $id, PDO::PARAM_INT);
		$rsDelete->execute();
		DB::close();
		
		$query_rsUpdate = "UPDATE newsletters SET conteudo = 0 WHERE conteudo=:id";
		$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
		$rsUpdate->bindParam(':id', $id, PDO::PARAM_INT);
		$rsUpdate->execute();
		DB::close();
		
		header("Location: conteudos.php?r=1");
	}
}

$query_rsTopos = "SELECT id, nome FROM news_topos ORDER BY nome ASC";
$rsTopos = DB::getInstance()->prepare($query_rsTopos);
$rsTopos->execute();
$totalRows_rsTopos = $rsTopos->rowCount();
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
      <h3 class="page-title"> Newsletter » Conteúdos <small>Listagem</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;">Conteúdos</a> </li>
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
              <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - Conteúdos </div>
              <div class="actions"> <a href="conteudos-insert.php" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> Novo Registo </span> </a> </div>
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
                      <!-- <th width="30%"> Topo </th> -->
                      <th width="10%"> Ações </th>
                    </tr>
                    <tr role="row" class="filter">
                      <td></td>
                      <td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
                      <?php /*<td>
                        <select name="form_topo" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                          <option value="">Todos</option>
                          <?php if($totalRows_rsTopos > 0) {
                            while($row_rsTopos = $rsTopos->fetch()) { ?>
                              <option value="<?php echo $row_rsTopos["id"]; ?>"><?php echo $row_rsTopos["nome"]; ?></option>
                            <?php }
                          } ?>
                        </select>
                      </td> */?>
                      <td><div class="margin-bottom-5">
                          <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Pesquisar</button>
                        </div>
                        <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Limpar</button>
                      </td>
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
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/scripts/datatable.js"></script> 
<script src="conteudos-list.js"></script> 
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