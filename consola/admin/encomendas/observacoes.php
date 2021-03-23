<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='encomendas';
$menu_sub_sel='';

$enc = $_GET['enc'];

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	
	
		$query_rsP = "DELETE FROM encomendas_obs WHERE id = :id AND id_encomenda =:id_encomenda";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
		$rsP->bindParam(':id_encomenda', $enc, PDO::PARAM_INT, 5);
		$rsP->execute();
		DB::close();
		
		header("Location: observacoes.php?enc=".$enc."&r=1");
	}
}

$query_rsP = "SELECT * FROM encomendas WHERE id='$enc'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

$id_cliente = $row_rsP["id_cliente"];

$query_rsCliente = "SELECT id FROM clientes WHERE id=:id_cliente";
$rsCliente = DB::getInstance()->prepare($query_rsCliente);
$rsCliente->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$rsCliente->execute();
$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCliente = $rsCliente->rowCount();

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
			<h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['encomendas']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small></h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="encomendas.php"><?php echo $RecursosCons->RecursosCons['encomendas']; ?></a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>
        </ul>
      </div>
			<!-- END PAGE HEADER-->            
			<!-- BEGIN PAGE CONTENT-->
	    <div class="alert alert-danger display-<?php if(isset($_GET['r']) && $_GET['r']==1) echo "show"; else echo "hide"; ?>">
	      <button class="close" data-close="alert"></button>
	      <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> </div>
      <?php if($_GET['suc']==1){?>
        <div class="alert alert-success display-show; ?>">
          <button class="close" data-close="alert"></button>
          <span> <?php echo $RecursosCons->RecursosCons['env']; ?> </span> </div>
      <?php } ?>   
			<div class="row">
				<div class="col-md-12">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-shopping-cart"></i> <?php echo $RecursosCons->RecursosCons['encomenda_num']; ?> <?php echo $enc; ?> - <?php echo $RecursosCons->RecursosCons['observacoes']; ?>
							</div>
							<div class="actions"> <a href="observacoes-insert.php?enc=<?php echo $enc; ?>&cli=<?php echo $row_rsP['id_cliente']; ?>" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?php echo $RecursosCons->RecursosCons['nova_observacao']; ?> </span> </a> </div>
              </div>							
						</div>
            <div class="tabbable">
              <ul class="nav nav-tabs nav-tabs-lg">
                <li onClick="window.location = 'encomendas-edit.php?id=<?php echo $enc; ?>&tab_sel=1'">
                  <a href="#tab_1" data-toggle="tab">
                    <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a>
                </li>
                <?php if($id_cliente > 0 && $totalRows_rsCliente > 0) { ?>
	                <li onClick="window.location = 'encomendas-edit.php?id=<?php echo $enc; ?>&tab_sel=2'">
	                  <a href="#tab_2" data-toggle="tab">
	                    <?php echo $RecursosCons->RecursosCons['tab_cliente']; ?> 
	                    </a>
	                </li>
	              <?php } ?>
                <li onClick="window.location='encomendas-edit.php?id=<?php echo $enc; ?>&tab_sel=4'">
                  <a href="#tab_4" data-toggle="tab">
                    <?php echo $RecursosCons->RecursosCons['tab_historico']; ?> </a>
                </li>
                <li class="active">
                  <a href="#tab_5" data-toggle="tab">
                    <?php echo $RecursosCons->RecursosCons['tab_observacoes']; ?> </a>
                </li>                                   
              </ul>  
            </div>
						<div class="portlet-body">
							<div class="table-container">
								<div class="table-actions-wrapper">
									<span>
									</span>
									<select class="table-group-action-input form-control input-inline input-small input-sm">
										<option value=""><?php echo $RecursosCons->RecursosCons['opt_selecione']; ?></option>
										<!-- <option value="1">Aberto</option>
										<option value="0">Fechado</option> -->
										<option value="-1"><?php echo $RecursosCons->RecursosCons['eliminar']; ?></option>
									</select>
									<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['submeter']; ?></button>
								</div>
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
								<thead>
								<tr role="row" class="heading">
									<th width="1%"><input type="checkbox" class="group-checkable"></th>
									<th width="15%"><?php echo $RecursosCons->RecursosCons['data']; ?></th>
									<th><?php echo $RecursosCons->RecursosCons['descricao']; ?></th>
									<!-- <th width="10%">Estado</th>
									<th width="10%">Mensagens</th> -->
									<th width="10%"><?php echo $RecursosCons->RecursosCons['acoes']; ?></th>
								</tr>
								<!-- <tr role="row" class="filter">
									<td></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_id" onKeyPress="submete(event)"></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
									<td><div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="Selecione">
                            <span class="input-group-btn">
                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div></td> -->
									<!-- <td><select name="form_activo" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
											<option value="">Todos</option>
											<option value="1">Aberto</option>
											<option value="0">Fechado</option>
									</select></td>
									<td></td> -->
									<!-- <td><div class="margin-bottom-5">
											<button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Pesquisar</button>
										</div>
										<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Limpar</button>
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
<script src="observacoes-list.js" data-id="<?php echo $enc; ?>" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>"></script>
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
    if (e.keyCode == 13) {
        document.getElementById('pesquisa').click();
    }
}
</script>
</body>
<!-- END BODY -->
</html>