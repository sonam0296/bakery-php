<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='clientes';
$menu_sub_sel='listagem';

$id = $_GET['id'];

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['obs']) && $_GET['obs'] != "" && $_GET['obs'] != 0) {
		if(isset($_GET['enc'])) {
			$query_rsP = "DELETE FROM encomendas_obs WHERE id = :id AND id_encomenda = :enc";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $_GET['obs'], PDO::PARAM_INT);
			$rsP->bindParam(':enc', $_GET['enc'], PDO::PARAM_INT);
			$rsP->execute();
		}
		else {
			$query_rsP = "DELETE FROM clientes_obs WHERE id = :id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $_GET['obs'], PDO::PARAM_INT);
			$rsP->execute();
		}

		DB::close();
		
		header("Location: observacoes.php?id=".$id."&r=1");
	}
}

$query_rsP = "SELECT * FROM clientes WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
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
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->            
			<h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['clientes']; ?> <small><?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="clientes.php"><?php echo $RecursosCons->RecursosCons['clientes']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?></a> </li>
        </ul>
      </div>
			<!-- END PAGE HEADER-->            
			<!-- BEGIN PAGE CONTENT-->
      <?php if(isset($_GET['r']) && $_GET['r'] == 1) { ?>
        <div class="alert alert-danger display-show">
          <button class="close" data-close="alert"></button>
          <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> 
        </div>
      <?php } ?>
      <?php if(isset($_GET['suc']) && $_GET['suc'] == 1) { ?>
        <div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span> <?php echo $RecursosCons->RecursosCons['env']; ?> </span> 
        </div>
      <?php } ?>
      <?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
        <div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 
        </div>
      <?php } ?>     
			<div class="row">
				<div class="col-md-12">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i><?php echo $RecursosCons->RecursosCons['clientes']; ?> - <?php echo $row_rsP['nome']; ?> - <?php echo $RecursosCons->RecursosCons['observacoes']; ?>
							</div>
							<div class="actions"> <a href="observacoes-insert.php?id=<?php echo $id; ?>" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?php echo $RecursosCons->RecursosCons['nova_observacao']; ?> </span> </a> </div>
              </div>							
						</div>
            <div class="tabbable">
              <ul class="nav nav-tabs nav-tabs-lg">
                <li class="nav-tab" onClick="window.location='clientes-edit.php?id=<?php echo $id; ?>&tab_sel=1'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                <li class="nav-tab active" onClick="window.location='observacoes.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_observacoes']; ?> </a> </li>
                <li class="nav-tab" onClick="window.location='clientes-edit.php?id=<?php echo $id; ?>&tab_sel=3'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>
                <li class="nav-tab" onClick="window.location='clientes-edit.php?id=<?php echo $id; ?>&tab_sel=4'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_lista_desejos']; ?> </a> </li>
                <li class="nav-tab" onClick="window.location='clientes-edit.php?id=<?php echo $id; ?>&tab_sel=5'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_seguir_produto']; ?> </a> </li>
              </ul>  
            </div>
						<div class="portlet-body">
							<div class="table-container">
								<div class="table-actions-wrapper">
									<span>
									</span>
									<select class="table-group-action-input form-control input-inline input-small input-sm">
										<option value=""><?php echo $RecursosCons->RecursosCons['opt_selecione']; ?></option>
										<!-- <option value="1"><?php echo $RecursosCons->RecursosCons['opt_aberto']; ?></option>
										<option value="0"><?php echo $RecursosCons->RecursosCons['opt_fechado']; ?></option> -->
										<option value="-1"><?php echo $RecursosCons->RecursosCons['eliminar']; ?></option>
									</select>
									<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['submeter']; ?></button>
								</div>
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
								<thead>
								<tr role="row" class="heading">
									<th width="1%"><input type="checkbox" class="group-checkable"></th>
									<th width="15%"><?php echo $RecursosCons->RecursosCons['data']; ?></th>
									<th width="10%"><?php echo $RecursosCons->RecursosCons['encomenda']; ?></th>
  	              <th>Descrição</th>
									<!-- <th width="10%"><?php echo $RecursosCons->RecursosCons['estado']; ?></th>
									<th width="10%"><?php echo $RecursosCons->RecursosCons['mensagens']; ?></th> -->
									<th width="10%"><?php echo $RecursosCons->RecursosCons['acoes']; ?></th>
								</tr>
								<!-- <tr role="row" class="filter">
									<td></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_id" onKeyPress="submete(event)"></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
									<td><div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                      <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="<?php echo $RecursosCons->RecursosCons['opt_selecione']; ?>">
                      <span class="input-group-btn">
                      <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                  </div></td> -->
									<!-- <td><select name="form_activo" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
											<option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?></option>
											<option value="1"><?php echo $RecursosCons->RecursosCons['opt_aberto']; ?></option>
											<option value="0"><?php echo $RecursosCons->RecursosCons['opt_fechado']; ?></option>
									</select></td>
									<td></td> -->
									<!-- <td><div class="margin-bottom-5">
											<button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <?php echo $RecursosCons->RecursosCons['pesquisar']; ?></button>
										</div>
										<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                    <input type="hidden" name="enc_id" id="enc_id" value="<?php echo $enc; ?>" />
									</td>
								</tr> -->
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
<script src="observacoes-list.js" data-id="<?php echo $id; ?>" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>" ></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {  
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  UtilizadoresList.init();
});
</script>
<script type="text/javascript">
function submete(e) {
  if(e.keyCode == 13) {
    document.getElementById('pesquisa').click();
  }
}
</script>
</body>
<!-- END BODY -->
</html>
