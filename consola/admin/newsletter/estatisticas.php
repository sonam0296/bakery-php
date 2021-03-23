<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_estatisticas';
$menu_sub_sel='';

$query_rsTipos = "SELECT * FROM news_tipos_pt ORDER BY nome ASC";
$rsTipos = DB::getInstance()->prepare($query_rsTipos);
$rsTipos->execute();
$totalRows_rsTipos = $rsTipos->rowCount();
DB::close();

$query_rsGrupos = "SELECT * FROM news_grupos ORDER BY nome ASC";
$rsGrupos = DB::getInstance()->prepare($query_rsGrupos);
$rsGrupos->execute();
$totalRows_rsGrupos = $rsGrupos->rowCount();
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
      <h3 class="page-title"> Estatísticas </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>   
          <li>
            <a href="javascript:">Newsletter <i class="fa fa-angle-right"></i></a>
          </li>        
          <li>
            <a href="estatisticas.php">Estatísticas</a>
          </li>
        </ul>
      </div>
      <!-- MODAL: EXPORTAÇÃO -->
      <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportLabel">
        <div class="modal-dialog" style="width: 500px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="exportLabel"><strong>Exportar Resultados</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <p>Deseja exportar os resultados atuais?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
              <button type="button" id="confirm_export" class="btn btn-success">Sim</button>
            </div>
          </div>
        </div>
      </div>  
      <!-- MODAL: QUEM CLICOU -->
      <div class="modal fade" id="quemClicouModal" tabindex="-1" role="dialog" aria-labelledby="quemClicouLabel">
        <div class="modal-dialog" style="width: 1000px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="quemClicouLabel"><strong>Onde Clicou</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="quem_clicou_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div> 
      <!-- MODAL: EMAILS DEVOLVIDOS (GERAL e DETALHADO) -->
      <div class="modal fade" id="emailsDevolvidosModal" tabindex="-1" role="dialog" aria-labelledby="emailsDevolvidosLabel">
        <div class="modal-dialog" style="width: 1000px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="emailsDevolvidosLabel"><strong>Emails Devolvidos</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="emails_devolvidos_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div> 
      <div class="modal fade" id="emailsDevolvidosGeralModal" tabindex="-1" role="dialog" aria-labelledby="emailsDevolvidosLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="emailsDevolvidosLabel"><strong>Emails Devolvidos</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="emails_devolvidos_geral_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>  
      <div class="modal fade" id="processarEmailsDevolvidosModal" tabindex="-1" role="dialog" aria-labelledby="processarEmailsDevolvidosLabel">
        <div class="modal-dialog" style="width: 700px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="processarEmailsDevolvidosLabel"><strong>Processar Emails Devolvidos</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <strong>Tem a certeza que deseja processar os emails devolvidos?</strong><br>
              Após este processo, os emails serão eliminados automaticamente da caixa de entrada do email.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
              <button type="button" id="confirm_process" class="btn btn-success">Sim</button>
            </div>
          </div>
        </div>
      </div>  
      <!-- MODAL: EMAILS RECEBIDOS (GERAL e DETALHADO) -->
      <div class="modal fade" id="emailsRecebidosGeralModal" tabindex="-1" role="dialog" aria-labelledby="emailsRecebidosGeralLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="emailsRecebidosGeralLabel"><strong>Emails Recebidos</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="emails_recebidos_geral_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="emailsRecebidosModal" tabindex="-1" role="dialog" aria-labelledby="emailsRecebidosLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="emailsRecebidosLabel"><strong>Emails Recebidos</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="emails_recebidos_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>  
      <!-- MODAL: VISUALIZAÇÕES (GERAL e DETALHADO) -->
      <div class="modal fade" id="visualizacoesGeralModal" tabindex="-1" role="dialog" aria-labelledby="visualizacoesGeralLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="visualizacoesGeralLabel"><strong>Visualizações</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="visualizacoes_geral_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="visualizacoesModal" tabindex="-1" role="dialog" aria-labelledby="visualizacoesLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="visualizacoesLabel"><strong>Visualizações</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="visualizacoes_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>  
      <!-- MODAL: PEDIDOS DE CANCELAMENTO (GERAL e DETALHADO) -->
      <div class="modal fade" id="pedidosCancelamentoGeralModal" tabindex="-1" role="dialog" aria-labelledby="pedidosCancelamentoGeralLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="pedidosCancelamentoGeralLabel"><strong>Pedidos de Cancelamento</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="pedidos_cancelamento_geral_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>  
      <div class="modal fade" id="pedidosCancelamentoModal" tabindex="-1" role="dialog" aria-labelledby="pedidosCancelamentoLabel">
        <div class="modal-dialog" style="width: 1200px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="pedidosCancelamentoLabel"><strong>Pedidos de Cancelamento</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              <div id="pedidos_cancelamento_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - Estatísticas </div>
                <div class="actions">
                  <span id="working2" style="margin-left: 10px"><img src="ajax-loader.gif" alt=""/></span>
                  <!-- <button type="button" id="processar" href="#processarEmailsDevolvidosModal" data-toggle="modal" class="btn blue" style="height: 33px"><i class="fa fa-refresh"></i> Processar Emails Devolvidos</button> -->
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    Preencha todos os campos obrigatórios. </div>
                  <div class="alert alert-success display-hide" id="emails_devolvidos_alert">
                    <button class="close" data-close="alert"></button>
                    Emails devolvidos processados com sucesso! 
                    <br><br>
                    Total Emails Devolvidos: <strong id="emails_devolvidos_total"></strong>
                  </div>            
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="datai">Data Início: <span class="required">*</span></label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo date('Y-m-d', strtotime("-15 days")); ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="dataf">Data Fim: <span class="required">*</span></label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo date('Y-m-d'); ?>">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="resultados">Resultados: </label>
                    <div class="col-md-3">
                      <select class="form-control" name="resultados" id="resultados">
                        <option value="1">Geral</option>
                        <option value="2">Detalhado</option>
                        <option value="3">Por Cliente</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="tipo">Tipo de Newsletter: <span class="required">*</span></label>
                    <div class="col-md-3">
                      <select class="form-control select2me" name="tipo" id="tipo">
                        <option value="">Selecionar...</option>
                        <option value="0">Todos</option>
                        <?php if($totalRows_rsTipos > 0) {
                          while($row_rsTipos = $rsTipos->fetch()) { ?>
                            <option value="<?php echo $row_rsTipos['id']; ?>"><?php echo $row_rsTipos['nome']; ?></option>
                          <?php }
                        } ?>
                      </select>
                    </div>
                    <label class="col-md-2 control-label" for="grupo">Tipo de Cliente: <span class="required">*</span></label>
                    <div class="col-md-3">
                      <select class="form-control select2me" name="grupo" id="grupo">
                        <option value="">Selecionar...</option>
                        <option value="0">Todos</option>
                        <?php if($totalRows_rsGrupos > 0) {
                          while($row_rsGrupos = $rsGrupos->fetch()) { ?>
                            <option value="<?php echo $row_rsGrupos['id']; ?>"><?php echo $row_rsGrupos['nome']; ?></option>
                          <?php }
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="newsletter">Newsletter: </label>
                    <div class="col-md-8">
                      <div id="newsletter_div">
                        <select class="form-control select2me" name="newsletter" id="newsletter">
                          <option value="0">Todas</option>
                        </select>
                      </div>
                    </div>
                    <button type="button" id="filtrar" class="btn green" style="height: 33px"><i class="fa fa-filter"></i> Filtrar</button>
                    <span id="working" style="margin-left: 10px"><img src="ajax-loader.gif" alt=""/></span>
                  </div>
                  <hr>
                  <div id="results"></div>
                  <div id="results_news"></div>
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

  $('#results_news').hide();

  $('.date-picker').datepicker({
    rtl: Metronic.isRTL(),
    autoclose: true
  });

  $('#tipo').on('change', function() {
    var tipo = $('#tipo').val();
    var grupo = $('#grupo').val();
    var datai = $('#datai').val();
    var dataf = $('#dataf').val();

    if(tipo >= 0) {
      carregaNewsletters(tipo, grupo, datai, dataf);
    }
  });

  $('#datai').on('change', function() {
    var datai = $('#datai').val();
    var dataf = $('#dataf').val();
    var tipo = $('#tipo').val();
    var grupo = $('#grupo').val();

    carregaNewsletters(tipo, grupo, datai, dataf);
  });

  $('#dataf').on('change', function() {
    var datai = $('#datai').val();
    var dataf = $('#dataf').val();
    var tipo = $('#tipo').val();
    var grupo = $('#grupo').val();

    carregaNewsletters(tipo, grupo, datai, dataf);
  });

  $('#grupo').on('change', function() {
    var datai = $('#datai').val();
    var dataf = $('#dataf').val();
    var tipo = $('#tipo').val();
    var grupo = $('#grupo').val();

    if(grupo >= 0) {
      carregaNewsletters(tipo, grupo, datai, dataf);
    }
  });

  $('#filtrar').on('click', function() {
    var datai = $('#datai').val();
    var dataf = $('#dataf').val();
    var resultados = $('#resultados').val();
    var tipo = $('#tipo').val();
    var grupo = $('#grupo').val();
    var newsletter = $('#newsletter').val();

    if(datai == '')
      $('#datai').css('border', '1px solid red');
    else
      $('#datai').css('border', '1px solid #e5e5e5');

    if(dataf == '')
      $('#dataf').css('border', '1px solid red');
    else
      $('#dataf').css('border', '1px solid #e5e5e5');

    if(tipo == '')
      $('#s2id_tipo').css('border', '1px solid red');
    else
      $('#s2id_tipo').css('border', '0');

    if(grupo == '')
      $('#s2id_grupo').css('border', '1px solid red');
    else
      $('#s2id_grupo').css('border', '0');

    if(datai && dataf && tipo && grupo) {
      $(this).attr("disabled", true);
      $('#processar').attr("disabled", true);
      $('#working').css('display', 'inline-block'); 

      $('#results_news').slideUp();            
      $('#results').slideDown();

      carregaResultados(datai, dataf, resultados, tipo, grupo, newsletter);
    }
  });

  $('#confirm_export').on('click', function() {
    var datai = $('#datai').val();
    var dataf = $('#dataf').val();
    var resultados = $('#resultados').val();
    var tipo = $('#tipo').val();
    var grupo = $('#grupo').val();
    var newsletter = $('#newsletter').val();
    var export_type = $('#export_type').val();

    if(datai == '')
      $('#datai').css('border', '1px solid red');
    else
      $('#datai').css('border', '1px solid #e5e5e5');

    if(dataf == '')
      $('#dataf').css('border', '1px solid red');
    else
      $('#dataf').css('border', '1px solid #e5e5e5');

    if(tipo == '')
      $('#s2id_tipo').css('border', '1px solid red');
    else
      $('#s2id_tipo').css('border', '0');

    if(grupo == '')
      $('#s2id_grupo').css('border', '1px solid red');
    else
      $('#s2id_grupo').css('border', '0');

    if(datai && dataf && tipo && grupo) {
      exportarResultados(export_type, datai, dataf, resultados, tipo, grupo, newsletter);
    }
  });

  $('#confirm_process').on('click', function() {
    $('#processarEmailsDevolvidosModal').modal('hide');
    $('#filtrar').attr("disabled", true);
    $('#processar').attr("disabled", true);
    $('#working2').css('display', 'inline-block'); 
    
    $.post("estatisticas-rpc.php", {op:"processarEmailsDevolvidos"}, function(data) {
      $('#filtrar').attr("disabled", false);
      $('#processar').attr("disabled", false);
      $('#working2').css('display', 'none'); 

      $('#emails_devolvidos_alert').toggleClass('display-hide').toggleClass('display-show');
      $('#emails_devolvidos_total').text(data);
    });
  });
});

function carregaNewsletters(tipo, grupo, datai, dataf) {
  $.post("estatisticas-rpc.php", {op:"carregaNewsletters", tipo: tipo, grupo: grupo, datai: datai, dataf: dataf}, function(data) {
    document.getElementById('newsletter_div').innerHTML=data; 
    $('#newsletter').select2();                
  });
}

function carregaResultados(datai, dataf, resultados, tipo, grupo, newsletter) {
  $.post("estatisticas-rpc.php", {op:"carregaResultados", datai:datai, dataf:dataf, resultados:resultados, tipo:tipo, grupo:grupo, newsletter:newsletter}, function(data) {
    document.getElementById('results').innerHTML=data;   

    if(resultados == 2) {
      $('#sample_1').dataTable().fnDestroy();
      TableManaged.init(1);
    }
    else if(resultados == 3) {
      $('#sample_7').dataTable().fnDestroy();
      TableManaged.init(7);
    }

    $('#filtrar').attr("disabled", false);
    $('#processar').attr("disabled", false);
    $('#working').css('display', 'none');     
  });
}

function exportarResultados(export_type, datai, dataf, resultados, tipo, grupo, newsletter) {
  if(export_type == 1) {
    op = "exportarResultadosGeral";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 2) {
    op = "exportarResultadosNews";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 3) {
    op = "exportarResultadosDetalhes";
    var id_news = $('#id_news').val();
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 4) {
    op = "exportarResultadosClientes";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 5) {
    op = "exportarDevolvidosGeral";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 6) {
    op = "exportarDevolvidos";
    var id_news = $('#id_news').val();
    var id_hist = $('#id_hist').val();
    var quem_clicou = 0;
  }
  else if(export_type == 7) {
    op = "exportarRecebidosGeral";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 8) {
    op = "exportarRecebidos";
    var id_news = $('#id_news').val();
    var id_hist = $('#id_hist').val();
    var quem_clicou = 0;
  }
  else if(export_type == 9) {
    op = "exportarVisualizadosGeral";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 10) {
    op = "exportarVisualizados";
    var id_news = $('#id_news').val();
    var id_hist = $('#id_hist').val();
    var quem_clicou = 0;
  }
  else if(export_type == 11) {
    op = "exportarCancelamentosGeral";
    var id_news = newsletter;
    var id_hist = 0;
    var quem_clicou = 0;
  }
  else if(export_type == 12) {
    op = "exportarCancelamentos";
    var id_news = $('#id_news').val();
    var id_hist = $('#id_hist').val();
    var quem_clicou = 0;
  }
  else if(export_type == 13) {
    op = "exportarQuemClicou";
    var id_news = $('#newsletter').val();
    var id_hist = $('#id_hist').val();
    var quem_clicou = $('#quem_clicou').val();
  }
  else if(export_type == 14) {
    op = "exportarQuemClicouAgendamentos";
    var id_news = $('#id_news').val();
    var id_hist = $('#id_hist').val();
    var quem_clicou = 0;
  }

  $.post("estatisticas-export.php", {op:op, datai:datai, dataf:dataf, resultados:resultados, tipo:tipo, grupo:grupo, newsletter:id_news, historico:id_hist, quem_clicou:quem_clicou}, function(data) {
    $('#exportModal').modal('hide');
    document.location = 'estatisticas-export.php?op=exporta_resultados';       
  });
}

function clicksNews(id) {
  var datai = $('#datai').val();
  var dataf = $('#dataf').val();
  var tipo = $('#tipo').val();
  var grupo = $('#grupo').val();
  
  $.post("estatisticas-rpc.php", {op:"carregaResNews", id: id, datai:datai, dataf:dataf, tipo:tipo, grupo:grupo}, function(data) {
    document.getElementById('results_news').innerHTML=data;  

    $('#results').slideUp();            
    $('#results_news').slideDown();

    $('#accordion').on('hidden.bs.collapse', toggleChevron);
    $('#accordion').on('shown.bs.collapse', toggleChevron);

    $('.sample_2').dataTable().fnDestroy();
    TableManaged.init(2);

    $('.return_list').on('click', function() {
      $('#results_news').slideUp();            
      $('#results').slideDown();
    });
  });
}

function detalhesClicksNews(id) {
  $.post("estatisticas-rpc.php", {op:"carregaQuemClicou", id: id}, function(data) {
    document.getElementById('quem_clicou_div').innerHTML=data;  

    $('#sample_3').dataTable().fnDestroy();
    TableManaged.init(3);

    $('#quemClicouModal').modal('show');
  });
}

function detalhesLinks(id) {
  var lista_agendamentos = $('#lista_agendamentos').val();

  $.post("estatisticas-rpc.php", {op:"carregaQuemClicouURL", id: id, lista_agendamentos: lista_agendamentos}, function(data) {
    document.getElementById('quem_clicou_div').innerHTML=data;  

    $('#sample_3').dataTable().fnDestroy();
    TableManaged.init(3);

    $('#quemClicouModal').modal('show');
  });
}

function datelhesClicks(id) {
  $.post("estatisticas-rpc.php", {op:"carregaQuemClicou", id: id}, function(data) {
    document.getElementById('quem_clicou_div').innerHTML=data;  

    $('#sample_3').dataTable().fnDestroy();
    TableManaged.init(3);

    $('#quemClicouModal').modal('show');
  });
}

/* EMAILS DEVOLVIDOS (GERAL e DETALHADO) */
function emailsDevolvidosGeral() {
  var datai = $('#datai').val();
  var dataf = $('#dataf').val();
  var resultados = $('#resultados').val();
  var tipo = $('#tipo').val();
  var grupo = $('#grupo').val();
  var newsletter = $('#newsletter').val();

  $.post("estatisticas-rpc.php", {op:"carregaEmailsDevolvidosGeral", datai: datai, dataf: dataf, resultados: resultados, tipo: tipo, grupo: grupo, newsletter: newsletter}, function(data) {
    document.getElementById('emails_devolvidos_geral_div').innerHTML=data;  

    $('#sample_6').dataTable().fnDestroy();
    TableManaged.init(6);

    $('#emailsDevolvidosGeralModal').modal('show');
  });
}

function emailsDevolvidosNews(id) {
  $.post("estatisticas-rpc.php", {op:"carregaEmailsDevolvidos", id: id}, function(data) {
    document.getElementById('emails_devolvidos_div').innerHTML=data;  

    $('#sample_5').dataTable().fnDestroy();
    TableManaged.init(5);

    $('#emailsDevolvidosModal').modal('show');
  });
}

/* EMAILS RECEBIDOS (GERAL e DETALHADO) */
function emailsRecebidosGeral() {
  var datai = $('#datai').val();
  var dataf = $('#dataf').val();
  var resultados = $('#resultados').val();
  var tipo = $('#tipo').val();
  var grupo = $('#grupo').val();
  var newsletter = $('#newsletter').val();

  $.post("estatisticas-rpc.php", {op:"carregaEmailsRecebidosGeral", datai: datai, dataf: dataf, resultados: resultados, tipo: tipo, grupo: grupo, newsletter: newsletter}, function(data) {
    document.getElementById('emails_recebidos_geral_div').innerHTML=data;  

    $('#sample_6').dataTable().fnDestroy();
    TableManaged.init(6);

    $('#emailsRecebidosGeralModal').modal('show');
  });
}

function emailsRecebidosNews(id) {
  $.post("estatisticas-rpc.php", {op:"carregaEmailsRecebidos", id: id}, function(data) {
    document.getElementById('emails_recebidos_div').innerHTML=data;  

    $('#sample_5').dataTable().fnDestroy();
    TableManaged.init(5);

    $('#emailsRecebidosModal').modal('show');
  });
}

/* VISUALIZAÇÕES (GERAL e DETALHADO) */
function emailsVistosGeral() {
  var datai = $('#datai').val();
  var dataf = $('#dataf').val();
  var resultados = $('#resultados').val();
  var tipo = $('#tipo').val();
  var grupo = $('#grupo').val();
  var newsletter = $('#newsletter').val();

  $.post("estatisticas-rpc.php", {op:"carregaEmailsVistosGeral", datai: datai, dataf: dataf, resultados: resultados, tipo: tipo, grupo: grupo, newsletter: newsletter}, function(data) {
    document.getElementById('visualizacoes_geral_div').innerHTML=data;  

    $('#sample_7').dataTable().fnDestroy();
    TableManaged.init(7);

    $('#visualizacoesGeralModal').modal('show');
  });
}

function emailsVistosNews(id) {
  $.post("estatisticas-rpc.php", {op:"carregaEmailsVistos", id: id}, function(data) {
    document.getElementById('visualizacoes_div').innerHTML=data;  

    $('#sample_8').dataTable().fnDestroy();
    TableManaged.init(8);

    $('#visualizacoesModal').modal('show');
  });
}

/* PEDIDOS DE CANCELAMENTO (GERAL e DETALHADO) */
function pedidosCancelamentoGeral() {
  var datai = $('#datai').val();
  var dataf = $('#dataf').val();
  var resultados = $('#resultados').val();
  var tipo = $('#tipo').val();
  var grupo = $('#grupo').val();
  var newsletter = $('#newsletter').val();

  $.post("estatisticas-rpc.php", {op:"carregaPedidosCancelamentoGeral", datai: datai, dataf: dataf, resultados: resultados, tipo: tipo, grupo: grupo, newsletter: newsletter}, function(data) {
    document.getElementById('pedidos_cancelamento_geral_div').innerHTML=data;  

    $('#sample_6').dataTable().fnDestroy();
    TableManaged.init(6);

    $('#pedidosCancelamentoGeralModal').modal('show');
  });
}

function pedidosCancelamentoNews(id) {
  $.post("estatisticas-rpc.php", {op:"carregaPedidosCancelamento", id: id}, function(data) {
    document.getElementById('pedidos_cancelamento_div').innerHTML=data;  

    $('#sample_8').dataTable().fnDestroy();
    TableManaged.init(8);

    $('#pedidosCancelamentoModal').modal('show');
  });
}

function toggleChevron(e) {
  $(e.target).prev('.panel-heading').find("i.indicator").toggleClass('fa-chevron-down fa-chevron-up');
}
</script> 
</body>
<!-- END BODY -->
</html>