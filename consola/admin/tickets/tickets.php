<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='tickets';
$menu_sub_sel='listagem';

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	
	
		$query_rsP = "DELETE FROM tickets WHERE id = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();
	
		$query_rsP = "DELETE FROM tickets WHERE id_pai = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();
		
		DB::close();
		
		header("Location: tickets.php?r=1");
	}
}

$query_rsTipos = "SELECT id, nome FROM tickets_tipos_pt ORDER BY nome ASC";
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
			<h3 class="page-title"><?php echo $RecursosCons->RecursosCons['tickets']; ?> <small><?php echo $RecursosCons->RecursosCons['listagem']; ?></small></h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="tickets.php"><?php echo $RecursosCons->RecursosCons['tickets']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->            
			<!-- BEGIN PAGE CONTENT-->
      <?php if($_GET['r'] == 1) { ?>
	      <div class="alert alert-success display-show">
		      <button class="close" data-close="alert"></button>
		      <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> 
		    </div>
		  <?php } ?>
      <?php if($_GET['suc'] == 1) { ?>
        <div class="alert alert-success display-show; ?>">
          <button class="close" data-close="alert"></button>
          <span> <?php echo $RecursosCons->RecursosCons['env']; ?> </span> 
        </div>
      <?php } ?>            
			<div class="row">
				<div class="col-md-12">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['tickets']; ?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-container">
								<div class="table-actions-wrapper">
									<span>
									</span>
									<select class="table-group-action-input form-control input-inline input-small input-sm">
										<option value=""><?php echo $RecursosCons->RecursosCons['opt_selecione']; ?></option>
										<option value="1"><?php echo $RecursosCons->RecursosCons['opt_aberto']; ?></option>
										<option value="0"><?php echo $RecursosCons->RecursosCons['opt_fechado']; ?></option>
										<option value="-1"><?php echo $RecursosCons->RecursosCons['eliminar']; ?></option>
									</select>
									<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['submeter']; ?></button>
								</div>
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
								<thead>
								<tr role="row" class="heading">
									<th width="1%"><input type="checkbox" class="group-checkable"></th>
									<th width="8%"><?php echo $RecursosCons->RecursosCons['id']; ?></th>
									<th><?php echo $RecursosCons->RecursosCons['nome']; ?></th>
									<th width="12%"><?php echo $RecursosCons->RecursosCons['data']; ?></th>
									<th width="20%"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?></th>
									<th width="10%"><?php echo $RecursosCons->RecursosCons['estado']; ?></th>
									<th width="10%"><?php echo $RecursosCons->RecursosCons['mensagens']; ?></th>
									<th width="8%"><?php echo $RecursosCons->RecursosCons['acoes']; ?></th>
								</tr>
								<tr role="row" class="filter">
									<td></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_id" onKeyPress="submete(event)"></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)" placeholder="Pesquisar por Nome ou Email"></td>
									<td><div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                      <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="Selecione">
                      <span class="input-group-btn">
                      <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                  </div></td>
                  <td><select name="form_tipo" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
											<option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?></option>
											<?php if($totalRows_rsTipos > 0) {
												while($row_rsTipos = $rsTipos->fetch()) { ?>
													<option value="<?php echo $row_rsTipos['id']; ?>"><?php echo $row_rsTipos['nome']; ?></option>
												<?php }
											} ?>
									</select></td>
									<td><select name="form_activo" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
											<option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?></option>
											<option value="1"><?php echo $RecursosCons->RecursosCons['opt_aberto']; ?></option>
											<option value="0"><?php echo $RecursosCons->RecursosCons['opt_fechado']; ?></option>
									</select></td>
									<td></td>
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
<!--     -->
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<?php /*?><script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/ecommerce-products.js"></script><?php */?>
<script src="tickets-list.js" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>"></script>
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