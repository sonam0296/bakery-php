<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='carrinho';
$menu_sub_sel='listagem';

$query_rsCli = "SELECT id, nome, email FROM clientes ORDER BY nome ASC";
$rsCli = DB::getInstance()->query($query_rsCli);
$rsCli->execute();
$totalRows_rsCli = $rsCli->rowCount();
DB::close();

$query_rsTotal = "SELECT SUM(quantidade) as total_prods, SUM(quantidade * preco) as preco_total FROM carrinho";
$rsTotal = DB::getInstance()->prepare($query_rsTotal);
$rsTotal->execute();
$totalRows_rsTotal = $rsTotal->rowCount();
$row_rsTotal = $rsTotal->fetch(PDO::FETCH_ASSOC);
DB::close();

$total_prods = $row_rsTotal['total_prods'];
$total = $row_rsTotal['preco_total'];

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
<div class="clearfix">
</div>
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
						<a href="carrinho.php"><?php echo $RecursosCons->RecursosCons['menu_carrinho']; ?> </a>
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
							<div class="caption">
								<i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_carrinho']; ?> 
							</div>
            </div>
						<div class="portlet-body">
							<div class="table-container">				
								<div class="table-actions-wrapper">
									<strong style="font-size: 15px;">
										<?php
										$texto = $RecursosCons->RecursosCons['texto_carrinho'];
										$texto = str_replace('#total#', number_format($total, 2, ',', '.')."£", $texto);
										$texto = str_replace('#total_prods#', $total_prods, $texto);

										echo $texto;
                  	?>
                	</strong>
                </div>
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
									<thead>
										<tr role="row" class="heading">
											<th width="30%"><?php echo $RecursosCons->RecursosCons['tab_cliente']; ?></th>
											<th width="10%"><?php echo $RecursosCons->RecursosCons['ip_label']; ?> </th>
		                  <th width="10%"><?php echo $RecursosCons->RecursosCons['data_label']; ?> </th>
		                  <th width="8%"><?php echo $RecursosCons->RecursosCons['itens_label']; ?> </th>
											<th width="8%"><?php echo $RecursosCons->RecursosCons['total_enc_label']; ?> </th>
											<th width="8%"><?php echo $RecursosCons->RecursosCons['acoes']; ?> </th>
										</tr>
										<tr role="row" class="filter">
											<td>
												<select name="form_cliente" class="form-control form-filter input-sm select2me" onchange="document.getElementById('pesquisa').click();" style="margin-top: 5px; width:100%; min-width:170px; display:inline-block">
			                    <option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?> </option>
		                      <option value="0"><?php echo $RecursosCons->RecursosCons['sem_cliente_assoc']; ?></option>
			                    <?php if($totalRows_rsCli > 0) { ?>
				                    <?php while($row_rsCli = $rsCli->fetch()) { ?>
				                      <option value="<?php echo $row_rsCli['id']; ?>"><?php echo $row_rsCli['nome']." - ".$row_rsCli['email']; ?></option>
				                    <?php }
				                  } ?>
				                </select>
											</td>
											<td><input type="text" class="form-control form-filter input-sm" name="form_ip" onKeyPress="submete(event)"></td>
		                  <td>
		                  	<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
		                      <input type="text" class="form-control form-filter input-sm" name="form_data" placeholder="Selecione">
		                      <span class="input-group-btn">
		                      <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
		                      </span>
		                    </div>
		                  </td>
		                  <td><input type="text" class="form-control form-filter input-sm" name="form_itens" onKeyPress="submete(event)"></td>
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
  
<script src="carrinho-rpc.js" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>"></script>
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
function submeteDados() {
	var data = table.$('input, select').serialize();
}
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