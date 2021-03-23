<?php include_once('../inc_pages.php'); ?>

<?php



$menu_sel='encomendas';

$menu_sub_sel='';



$query_rsMetPagamentos = "SELECT * FROM met_pagamento_en WHERE visivel='1' ORDER BY id ASC";

$rsMetPagamentos = DB::getInstance()->prepare($query_rsMetPagamentos);

$rsMetPagamentos->execute();

$totalRows_rsMetPagamentos = $rsMetPagamentos->rowCount();



$query_rsClientes = "SELECT id, nome, email FROM clientes ORDER BY nome ASC";

$rsClientes = DB::getInstance()->prepare($query_rsClientes);

$rsClientes->execute();

$totalRows_rsClientes = $rsClientes->rowCount();



DB::close();



?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<style type="text/css">

  #working, #working2 { 

    display: none;

  }

</style>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>

<!--COR-->

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>

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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['estatisticas_label']; ?> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>   

          <li>

            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['encomendas']; ?> <i class="fa fa-angle-right"></i></a>

          </li>        

          <li>

            <a href="estatisticas.php"><?php echo $RecursosCons->RecursosCons['estatisticas_label']; ?></a>

          </li>

        </ul>

      </div>

      <!-- MODAL: EXPORTAÇÃO -->

      <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportLabel">

        <div class="modal-dialog" style="width: 500px" role="document">

          <div class="modal-content">

            <div class="modal-header">

              <h4 class="modal-title" style="display: inline-block" id="exportLabel"><strong><?php echo $RecursosCons->RecursosCons['exportar_resultados']; ?></strong></h4>

              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>

            </div>

            <div class="modal-body" style="width: 100%">

              <p><?php echo $RecursosCons->RecursosCons['exportar_resultados_msg']; ?></p>

            </div>

            <div class="modal-footer">

              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['text_visivel_nao']; ?></button>

              <button type="button" id="confirm_export" class="btn btn-success"><?php echo $RecursosCons->RecursosCons['text_visivel_sim']; ?></button>

            </div>

          </div>

        </div>

      </div>

       <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">   

          <form id="estatisticas_form" name="estatisticas_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="export_type" id="export_type" value="1"/>

            <input type="hidden" name="id_news" id="id_news" value="0"/>

            <input type="hidden" name="id_hist" id="id_hist" value="0"/>

            <input type="hidden" name="quem_clicou" id="quem_clicou" value="0"/>

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['encomendas'] ." - ". $RecursosCons->RecursosCons['estatisticas_label'] ?></div>

              </div>

              <div class="portlet-body">

                <div class="form-body">

                  <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?>

                  </div>      

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: </label>

                    <div class="col-md-3">

                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="">

                        <span class="input-group-btn">

                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                        </span> 

                      </div>

                    </div>

                    <label class="col-md-2 control-label" for="dataf"><?php echo $RecursosCons->RecursosCons['data_fim_label']; ?>: </label>

                    <div class="col-md-3">

                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="">

                        <span class="input-group-btn">

                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                        </span> 

                      </div>

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="estado"><?php echo $RecursosCons->RecursosCons['estado_encomenda']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control select2me" name="estado" id="estado">

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <option value="1"><?php echo $RecursosCons->RecursosCons['op_aguardar_pagamento']; ?></option>

                        <option value="2"><?php echo $RecursosCons->RecursosCons['op_em_processamento']; ?></option>

                        <option value="3"><?php echo $RecursosCons->RecursosCons['op_enviada']; ?></option>

                        <option value="4"><?php echo $RecursosCons->RecursosCons['op_concluida']; ?></option>

                        <option value="5"><?php echo $RecursosCons->RecursosCons['op_anulada']; ?></option>

                      </select>

                    </div>

                    <label class="col-md-2 control-label" for="cod_promocional"><?php echo $RecursosCons->RecursosCons['cod_promocional']; ?>: </label>

                    <div class="col-md-3">

                      <input type="text" class="form-control" name="cod_promocional" id="cod_promocional" value="">

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="pagamento"><?php echo $RecursosCons->RecursosCons['met_pagamento']; ?>: </label>

                    <div class="col-md-3">

                      <select name="pagamento" id="pagamento" class="form-control form-filter input-sm select2me">

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_todos']; ?> </option>

                        <?php while($row_rsMetPagamentos = $rsMetPagamentos->fetch()) { ?>

                          <option value="<?php echo $row_rsMetPagamentos["id"]; ?>"><?php echo $row_rsMetPagamentos["nome_interno"]; ?></option>

                        <?php } ?>

                      </select>

                    </div>

                    <label class="col-md-2 control-label" for="tipo_cliente"><?php echo $RecursosCons->RecursosCons['tipo_cliente']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control select2me" name="tipo_cliente" id="tipo_cliente">

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <option value="1"><?php echo $RecursosCons->RecursosCons['tipo1_label']; ?></option>

                        <option value="2"><?php echo $RecursosCons->RecursosCons['tipo2_label']; ?></option>

                      </select>

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="cliente"><?php echo $RecursosCons->RecursosCons['tab_cliente']; ?>: </label>

                    <div class="col-md-8" id="clientes">

                      <select class="form-control select2me" name="cliente" id="cliente">

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <?php while($row_rsClientes = $rsClientes->fetch()) { ?>

                          <option value="<?php echo $row_rsClientes['id']; ?>"><?php echo $row_rsClientes['nome']." - ".$row_rsClientes['email']; ?></option>

                        <?php } ?>

                      </select>

                    </div>

                  </div>

                  <div class="form-group">

                    <div class="col-md-10" style="text-align: right;">

                      <span id="working" style="margin-left: 10px"><img src="ajax-loader.gif" alt=""/></span>

                      <button type="button" id="filtrar" class="btn green" style="height: 33px"><i class="fa fa-filter"></i> <?php echo $RecursosCons->RecursosCons['filtrar_label']; ?></button>

                    </div>

                  </div>

                  <hr>

                  <div id="results"></div>

              	</div> 

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="estatisticas_form" />

          </form>

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

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 

<!-- LINGUA PORTUGUESA -->

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/table-managed.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<!-- BEGIN PAGE LEVEL SCRIPTS --> 

<!-- END PAGE LEVEL SCRIPTS --> 

<script>

jQuery(document).ready(function() {    

  Metronic.init(); // init metronic core components

  Layout.init(); // init current layout

  QuickSidebar.init(); // init quick sidebar

  Demo.init(); // init demo features



  $('.date-picker').datepicker({

    rtl: Metronic.isRTL(),

    autoclose: true

  });



  $('#tipo_cliente').on('change', function() {

    var tipo = $('#tipo_cliente').val();



    carregaClientes(tipo);

  });



  $('#filtrar').on('click', function() {

    var tipo = $('#tipo_cliente').val();

    var cliente = $('#cliente').val();

    var pagamento = $('#pagamento').val();

    var cod_prom = $('#cod_promocional').val();

    var estado = $('#estado').val();

    var datai = $('#datai').val();

    var dataf = $('#dataf').val();

  

    $(this).attr("disabled", true);

    $('#processar').attr("disabled", true);

    $('#working').css('display', 'inline-block'); 

         

    $('#results').slideDown();



    carregaResultados(tipo, cliente, pagamento, cod_prom, estado, datai, dataf);

  });



  $('#confirm_export').on('click', function() {

    var tipo = $('#tipo_cliente').val();

    var cliente = $('#cliente').val();

    var pagamento = $('#pagamento').val();

    var cod_prom = $('#cod_promocional').val();

    var estado = $('#estado').val();

    var datai = $('#datai').val();

    var dataf = $('#dataf').val();

    

    exportarResultados(tipo, cliente, pagamento, cod_prom, estado, datai, dataf);

  });

});



function carregaClientes(tipo) {

  $.post("estatisticas-rpc.php", {op:"carregaClientes", tipo:tipo}, function(data) {

    document.getElementById('clientes').innerHTML = data;

    $('#cliente').select2();

  });

}



function carregaResultados(tipo, cliente, pagamento, cod_prom, estado, datai, dataf) {

  $.post("estatisticas-rpc.php", {op:"carregaResultados", tipo: tipo, cliente: cliente, pagamento: pagamento, cod_prom: cod_prom, estado: estado, datai: datai, dataf: dataf}, function(data) {

    document.getElementById('results').innerHTML = data;   



    $('#sample_1').dataTable().fnDestroy();

    TableManaged.init(1);



    $('#filtrar').attr("disabled", false);

    $('#processar').attr("disabled", false);

    $('#working').css('display', 'none');     

  });

}



function exportarResultados(tipo, cliente, pagamento, cod_prom, estado, datai, dataf) {

  $.post("estatisticas-export.php", {op:"exportarGeral", tipo: tipo, cliente: cliente, pagamento: pagamento, cod_prom: cod_prom, estado: estado, datai: datai, dataf: dataf}, function(data) {

    $('#exportModal').modal('hide');

    document.location = 'estatisticas-export.php?op=exporta_resultados';       

  });

}

</script> 

</body>

<!-- END BODY -->

</html>