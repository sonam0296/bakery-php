<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='ec_produtos_categorias';
$menu_sub_sel='';

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		DB::close();
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
			$query_rsProc = "SELECT * FROM l_categorias_".$row_rsLinguas['sufixo']." WHERE id = :id";
			$rsProc = DB::getInstance()->prepare($query_rsProc);
			$rsProc->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsProc->execute();
			$totalRows_rsProc = $rsProc->rowCount();	
			
			if($totalRows_rsProc > 0) {
				while($row_rsProc = $rsProc->fetch()) {
					$insertSQL = "UPDATE l_categorias_".$row_rsLinguas["sufixo"]." SET cat_mae='0' WHERE cat_mae=:cat_mae";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':cat_mae', $row_rsProc['id'], PDO::PARAM_INT);
					$rsInsert->execute();
					
					
					if($row_rsProc['catalogo'] != '') { 
						@unlink('../../../imgs/categorias/'.$row_rsProc['catalogo']);
					}
					
					if($row_rsProc['imagem1'] != '') { 
						@unlink('../../../imgs/categorias/'.$row_rsProc['imagem1']);
					}
				}
			}

			$insertSQL = "UPDATE l_pecas_".$row_rsLinguas["sufixo"]." SET categoria='0' WHERE categoria=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsInsert->execute();
			
			$query_rsP = "DELETE FROM l_categorias_".$row_rsLinguas["sufixo"]." WHERE id = :id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsP->execute();
		}

		DB::close();

		alteraSessions('categorias');
			
		header("Location: categorias.php?r=1");
	}
}

$query_rsCat = "SELECT id, nome FROM l_categorias_en WHERE cat_mae=0 ORDER BY ordem ASC, nome ASC";
$rsCat = DB::getInstance()->prepare($query_rsCat);
$rsCat->execute();
$totalRows_rsCat = $rsCat->rowCount();
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
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->      
  		<h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['categorias']; ?> <small> <?php echo $RecursosCons->RecursosCons['listagem']; ?></small> </h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="categorias.php"><?php echo $RecursosCons->RecursosCons['categorias']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->            
			<!-- BEGIN PAGE CONTENT-->
      <?php if(isset($_GET['r']) && $_GET['r']==1){ ?>
      <div class="alert alert-danger display-show">
      <button class="close" data-close="alert"></button>
      <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> </div>  
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
							<div class="caption">
								<i class="fa fa-folder"></i><?php echo $RecursosCons->RecursosCons['categorias']; ?>
							</div>
              <div class="actions"> <a href="categorias-insert.php" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?php echo $RecursosCons->RecursosCons['nova_categoria']; ?> </span> </a> </div>
            </div>
						<div class="portlet-body">
							<div class="table-container">	
              	<div class="table-actions-wrapper">
									<span>
									</span>
									<select class="table-group-action-input form-control input-inline input-small input-sm">
										<option value=""><?php echo $RecursosCons->RecursosCons['opt_selecione']; ?></option>
					                    <option value="3"><?php echo $RecursosCons->RecursosCons['visivel']; ?></option>
					                    <option value="4"><?php echo $RecursosCons->RecursosCons['nao_visivel']; ?></option>
					                     <option value="5"><?php echo $RecursosCons->RecursosCons['text_category_sim']; ?></option>
					                    <option value="6"><?php echo $RecursosCons->RecursosCons['text_category_nao']; ?></option>
										<option value="-1"><?php echo $RecursosCons->RecursosCons['eliminar']; ?></option>
									</select>
									<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['submeter']; ?></button>
                					<button class="btn btn-sm green" id="bt_submete" type="button"><i class="fa fa-refresh"></i> <?php echo $RecursosCons->RecursosCons['guarda_alt']; ?></button>
								</div>				
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
								<thead>
								<tr role="row" class="heading">
									<th width="1%"><input type="checkbox" class="group-checkable"></th>
									<th><?php echo $RecursosCons->RecursosCons['nome']; ?></th>
									<th width="20%"><?php echo $RecursosCons->RecursosCons['categoria_principal_label']; ?></th>
									<th width="10%"><?php echo $RecursosCons->RecursosCons['ordem']; ?></th>
									<th width="8%">franchise </th>
                  <th width="10%"><?php echo $RecursosCons->RecursosCons['visivel_tab']; ?></th>
									<th width="8%"><?php echo $RecursosCons->RecursosCons['acoes']; ?></th>
								</tr>
								<tr role="row" class="filter">
									<td></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)"></td>
									<td><select name="form_categoria" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                    <option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
                    <?php if($totalRows_rsCat >0){ ?>
                      <?php while($row_rsCat = $rsCat->fetch()) { ?>
                        <option value="<?php echo $row_rsCat['id']; ?>"><?php echo $row_rsCat['nome']; ?></option>
                      <?php } ?>
                    <?php } ?>
									</select></td>
									<td></td>
				  <td><select name="form_visivel" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                    <option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
                    <option value="1"><?php echo $RecursosCons->RecursosCons['visiveis']; ?></option>
                    <option value="0"><?php echo $RecursosCons->RecursosCons['nao_visiveis']; ?></option>
                  </select></td>
                  <td><select name="form_visivel" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                    <option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
                    <option value="1"><?php echo $RecursosCons->RecursosCons['visiveis']; ?></option>
                    <option value="0"><?php echo $RecursosCons->RecursosCons['nao_visiveis']; ?></option>
                  </select></td>
									<td>
										<div class="margin-bottom-5">
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
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
  
<script src="categorias-rpc.js" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>"></script>
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
function alteraDesconto(e) {
    if (e.keyCode == 13) {
        document.getElementById('bt_submete').click();
    }
}
</script>
</body>
<!-- END BODY -->
</html>