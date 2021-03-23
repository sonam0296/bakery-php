<?php include_once('../inc_pages.php'); ?>

<?php 

$menu_sel='encomendas';

$menu_sub_sel='';



if(isset($_GET['rem']) && $_GET['rem'] == 1) {

	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {

		$id = $_GET['id'];

		

		$query_deleteSQL = "DELETE FROM encomendas WHERE id = :id";

		$deleteSQL = DB::getInstance()->prepare($query_deleteSQL);

		$deleteSQL->bindParam(':id', $id, PDO::PARAM_INT, 5);	

		$deleteSQL->execute();

		

		$query_deleteSQL = "DELETE FROM encomendas_produtos WHERE id_encomenda = :id";

		$deleteSQL = DB::getInstance()->prepare($query_deleteSQL);

		$deleteSQL->bindParam(':id', $id, PDO::PARAM_INT, 5);	

		$deleteSQL->execute();



		DB::close();

		

		header("Location: encomendas.php?r=1");

	}

}



$query_rsGrupos = "SELECT * FROM enc_estados ORDER BY ordem ASC";

$rsGrupos = DB::getInstance()->prepare($query_rsGrupos);

$rsGrupos->execute();

$totalRows_rsGrupos = $rsGrupos->rowCount();	



$query_rsMetPagamentos = "SELECT * FROM met_pagamento_en WHERE visivel='1' ORDER BY id ASC";

$rsMetPagamentos = DB::getInstance()->prepare($query_rsMetPagamentos);

$rsMetPagamentos->execute();

$totalRows_rsMetPagamentos = $rsMetPagamentos->rowCount();



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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['encomendas']; ?> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?> </a> </li>

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

              <div class="caption"> <i class="fa fa-shopping-cart"></i><?php echo $RecursosCons->RecursosCons['listagem']; ?>  </div>

              <div class="actions"> <a href="estatisticas.php" class="btn blue"> <span class="hidden-480"> <i class="icon-graph"></i> <?php echo $RecursosCons->RecursosCons['estatisticas_label']; ?> </span> </a> </div>

              <!-- <div class="actions"> <a href="encomendas-imprime.php" class="btn blue"> <span class="hidden-480"> <i class="fa fa-print"></i> Imprimir </span> </a> </div> -->

            </div>

            <div class="portlet-body">

              <div class="table-container">

                <!-- <div class="table-actions-wrapper"> <span> </span>

                  <select class="table-group-action-input form-control input-inline input-small input-sm">

                    <option value="">Selecione</option>

                    <option value="-1">Eliminar</option>

                  </select>

                  <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submeter</button>

                </div> -->

                <table class="table table-striped table-bordered table-hover" id="datatable_products">

                  <thead>

                    <tr role="row" class="heading">

                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['num']; ?>  </th>

                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['data']; ?>  </th>

                      <th> <?php echo $RecursosCons->RecursosCons['nome']; ?>  </th>

                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['valor']; ?>  </th>

                      <th width="10%"> Store Name  </th>

                      <th width="14%"> <?php echo $RecursosCons->RecursosCons['met_pagamento']; ?>  </th>

                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['estado']; ?>  </th>

                      <th width="10%"> <?php echo $RecursosCons->RecursosCons['acoes']; ?>  </th>

                    </tr>

                    <tr role="row" class="filter">

                      <td><input type="text" class="form-control form-filter input-sm" name="form_num" onKeyPress="submete(event)"></td>

                      <td>

                      	<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">

                          <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="<?php echo $RecursosCons->RecursosCons['opt_datainicio']; ?>">

                          <span class="input-group-btn">

                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                          </span>

                        </div>

                        <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">

                          <input type="text" class="form-control form-filter input-sm" readonly name="form_data2" placeholder="<?php echo $RecursosCons->RecursosCons['opt_datafim']; ?>">

                          <span class="input-group-btn">

                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                          </span>

                        </div>

                      </td>

                      <td><input type="text" class="form-control form-filter input-sm" name="form_nome" placeHolder="<?php echo $RecursosCons->RecursosCons['pesq_por_nome_email']; ?>" onKeyPress="submete(event)"></td>

                      <td><input type="text" class="form-control form-filter input-sm" name="form_valor" onKeyPress="submete(event)"></td>

                      <?php 

                        /* Code Start || Store Name */

                        $query_store = "SELECT * FROM store_locater";

                        $Store = DB::getInstance()->prepare($query_store);

                        //$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 

                        $Store->execute();

                        $row_store = $Store->fetchAll();

                        $totalRows_store = $Store->rowCount();

                        /* Code Start || Store Name */

                      ?>

                      <td>
                        <?php
                          $query_rsUser = "SELECT * FROM acesso WHERE acesso.username='$username' AND id='$id_user'";

                          $rsUser = DB::getInstance()->prepare($query_rsUser);

                          $rsUser->execute();

                          $row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);

                          $totalRows_rsUser = $rsUser->rowCount();

                          DB::close();

                          $store  = $row_rsUser["store_name"];
                        ?>
                    <?php if($store == "ADMIN"){ ?>
                      <select name="form_Store" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">

                          <option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?> </option> 

                            <?php foreach ($row_store as $row) { ?>        

                              <option value="<?php echo $row["b_name"]; ?>"><?php echo $row["b_name"]; ?></option>

                            <?php } ?>

                      </select>
                    <?php } ?>
                      </td>

                      <td>

                        <select name="form_pagamento" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">

                          <option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?> </option>

                          <?php if($totalRows_rsMetPagamentos > 0) {

                            while($row_rsMetPagamentos = $rsMetPagamentos->fetch()) { ?>

                              <option value="<?php echo $row_rsMetPagamentos["id"]; ?>"><?php echo $row_rsMetPagamentos["nome_interno"]; ?></option>

                            <?php }

                          } ?>

                        </select>

                      </td>

                      <td>

                        <select name="form_estado" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">

                          <option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?> </option>

                          <?php if($totalRows_rsGrupos > 0) {

                            while($row_rsGrupos = $rsGrupos->fetch()) { ?>

                              <option value="<?php echo $row_rsGrupos["id"]; ?>"><?php echo $row_rsGrupos["nome_en"]; ?></option>

                            <?php }

                          } ?>

                        </select>

                      </td>

                      <td>

                        <div class="margin-bottom-5">

                          <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <?php echo $RecursosCons->RecursosCons['pesquisar']; ?> </button>

                        </div>

                        <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button></td>

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

<script src="encomendas-rpc.js" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>"></script> 

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

</script>

</body>

<!-- END BODY -->

</html>