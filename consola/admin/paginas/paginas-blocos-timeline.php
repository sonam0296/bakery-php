<?php include_once('../inc_pages.php'); ?>
<?php 

$id_bloco = $_GET['id'];
$fixo = $_GET['fixo'];
$pagina = $_GET['pagina'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Páginas Fixas';

if($fixo == 0) {
  $menu_sub_sel='paginas_outras';
  $nome_sel='Outras Páginas';
}

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	
		
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
      $query_rsP = "SELECT imagem1 FROM paginas_blocos_timeline_".$row_rsLinguas["sufixo"]." WHERE id=:id";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);  
      $rsP->execute();
      $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsP = $rsP->rowCount();

      if($totalRows_rsP > 0){
        @unlink('../../../imgs/paginas/'.$row_rsP['imagem1']);
      }

			$query_rsDel = "DELETE FROM paginas_blocos_timeline_".$row_rsLinguas["sufixo"]." WHERE id=:id";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->bindParam(':id', $id, PDO::PARAM_INT);	
			$rsDel->execute();
		}

    DB::close();
		
		header("Location: paginas-blocos-timeline.php?r=1&fixo=".$fixo."&pagina=".$pagina."&id_bloco=".$id_bloco);
	}
}

$query_rsP = "SELECT * FROM paginas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $pagina, PDO::PARAM_INT, 5); 
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsP2 = "SELECT * FROM paginas_blocos".$extensao." WHERE id=:id";
$rsP2 = DB::getInstance()->prepare($query_rsP2);
$rsP2->bindParam(':id', $id_bloco, PDO::PARAM_INT, 5);  
$rsP2->execute();
$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP2 = $rsP2->rowCount();

$query_rsTotal = "SELECT id FROM paginas_blocos_timeline".$extensao." WHERE bloco = :id_bloco";
$rsTotal = DB::getInstance()->prepare($query_rsTotal);
$rsTotal->bindParam(':id_bloco', $id_bloco, PDO::PARAM_INT);  
$rsTotal->execute();
$row_rsTotal = $rsTotal->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTotal = $rsTotal->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?> - <?php echo $RecursosCons->RecursosCons['blocos']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li>
            <a href="paginas.php?fixo=<?php echo $fixo; ?>"> <?php echo $RecursosCons->RecursosCons['paginas']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="paginas-edit.php?fixo=<?php echo $fixo; ?>&id=<?php echo $pagina; ?>"> <?php echo $RecursosCons->RecursosCons['blocos']; ?> </a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:;"> <?php echo $RecursosCons->RecursosCons['blocos_sel_timeline']; ?> </a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="alert alert-danger display-<?php if(isset($_GET['r']) && $_GET['r']==1) echo "show"; else echo "hide"; ?>">
        <button class="close" data-close="alert"></button>
        <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> </div>
      <?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
        <div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-12"> 
          <!-- Begin: life time stats -->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $nome_sel; ?> - <?php echo $row_rsP['nome']; ?> - <?php echo $RecursosCons->RecursosCons['bloco']; ?> - <?php echo $row_rsP2['nome']; ?></div>
              <div class="actions">
                <button type="button" name="back" class="btn default" onClick="document.location='paginas-blocos.php?pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button> 
                <a href="paginas-blocos-timeline-insert.php?id_bloco=<?php echo $id_bloco; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?php echo $RecursosCons->RecursosCons['novo_registo']; ?> </span> </a> 
              </div>
            </div>
            <div class="portlet-body">
              <div class="table-container">
                <ul class="nav nav-tabs">
                  <li onclick="window.location='paginas-blocos-edit.php?id=<?php echo $id_bloco; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&tab_sel=1'"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                  <li class="active" onclick="window.location='paginas-blocos-timeline.php?id_bloco=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>'"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['blocos_sel_timeline']; ?> </a> </li>
                </ul>
                <div class="table-actions-wrapper"> <span> </span>
                  <select class="table-group-action-input form-control input-inline input-small input-sm">
                    <option value=""><?php echo $RecursosCons->RecursosCons['titulo_controlo_op']; ?></option>
                    <option value="3"><?php echo $RecursosCons->RecursosCons['visivel']; ?></option>
                    <option value="4"><?php echo $RecursosCons->RecursosCons['nao_visivel']; ?></option>
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
                      <th width="15%"> <?php echo $RecursosCons->RecursosCons['imagem']; ?> </th>
                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['ordem']; ?> </th>
                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['visivel_tab']; ?> </th>
                      <th width="8%"> <?php echo $RecursosCons->RecursosCons['acoes']; ?> </th>
                    </tr>
                    <tr role="row" class="filter">
                      <td></td>
                      <td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>
                        <select name="form_visivel" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                          <option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
                          <option value="1"><?php echo $RecursosCons->RecursosCons['visiveis']; ?></option>
                          <option value="0"><?php echo $RecursosCons->RecursosCons['nao_visiveis']; ?></option>
                        </select>
                      </td>
                      <td><div class="margin-bottom-5">
                          <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <?php echo $RecursosCons->RecursosCons['pesquisar']; ?></button>
                        </div>
                        <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
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
<!-- LINGUA PORTUGUESA -->
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
   
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script>
<?php /*?><script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/ecommerce-products.js"></script><?php */?>
<script src="paginas-blocos-timeline-list.js" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>" data-bloco="<?php echo $id_bloco; ?>" data-pagina="<?php echo $pagina; ?>" data-fixo="<?php echo $fixo; ?>"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {  
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  EspecializacoesList.init();
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