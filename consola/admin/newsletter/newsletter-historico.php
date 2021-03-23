<?php include_once('../inc_pages.php'); ?>
<?php 

include_once('newsletter-funcoes-logs.php');

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

$id=$_GET['id'];

$query_rsP = "SELECT * FROM newsletters WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsAgen = "SELECT newsletters_historico.*, newsletters_historico_estados.nome AS nome_estado FROM newsletters_historico, newsletters_historico_estados WHERE newsletters_historico.newsletter_id=:id AND newsletters_historico.estado=newsletters_historico_estados.id ORDER BY newsletters_historico.data DESC, newsletters_historico.hora DESC, newsletters_historico.id DESC";
$rsAgen = DB::getInstance()->prepare($query_rsAgen);
$rsAgen->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsAgen->execute();
$totalRows_rsAgen = $rsAgen->rowCount();
DB::close();

$query_rsEstados = "SELECT * FROM newsletters_historico_estados ORDER BY nome ASC";
$rsEstados = DB::getInstance()->prepare($query_rsEstados);
$rsEstados->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsEstados->execute();
$totalRows_rsEstados = $rsEstados->rowCount();
DB::close();

if(isset($_GET['processar']) && $_GET['processar']==1){
	if(isset($_GET['id_hist']) && $_GET['id_hist'] != "" && $_GET['id_hist'] != 0) {
		$id_linha = $_GET['id_hist'];
		
		$data=date('Y-m-d');
		$hora=date('H').":00";
		
		$query_insertSQL = "UPDATE newsletters_historico SET data='$data', hora='$hora' WHERE id=:id_linha AND newsletter_id=:id";
		$insertSQL = DB::getInstance()->prepare($query_insertSQL);
		$insertSQL->bindParam(':id_linha', $_GET['id_hist'], PDO::PARAM_INT);
		$insertSQL->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$insertSQL->execute();
		DB::close();
			
		$que_fez="alterou agendamento para ".$data." // ".$hora;
		$nome_utilizador=$row_rsUser["username"];
		$class_news_logs->logs_agendamentos($nome_utilizador, $row_rsP['id'], $que_fez, $row_rsP['titulo']);
		
		header("Location: newsletter-historico.php?id=".$_GET['id']."&alt=1");	
	}
}

$query_rsTipos = "SELECT * FROM news_grupos ORDER BY nome ASC";
$rsTipos = DB::getInstance()->prepare($query_rsTipos);
$rsTipos->execute();
$totalRows_rsTipos = $rsTipos->rowCount();
DB::close();

$query_rsContNews = "SELECT * FROM news_conteudo WHERE id=:id";
$rsContNews = DB::getInstance()->prepare($query_rsContNews);
$rsContNews->bindParam(':id', $row_rsP['conteudo'], PDO::PARAM_INT);
$rsContNews->execute();
$totalRows_rsContNews = $rsContNews->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
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
      <h3 class="page-title"> <?php echo $row_rsP["titulo"]; ?> <small>agendamentos</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="newsletter.php">Newsletters <?php echo date("Y-m-d H:i:s");  ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <a href="#modal_delete" id="modal_trigg" data-toggle="modal" class="btn red" style="display:none;"><i class="fa fa-remove"></i> Eliminar</a>
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title">Newsletter</h4>
            </div>
            <div class="modal-body"> Newsletter enviada com sucesso!</div>
            <div class="modal-footer">
              <button type="button" class="btn default" data-dismiss="modal">OK</button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
        <div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span> Registo alterado com sucesso. </span> 
        </div>
      <?php } ?>
      <?php if($totalRows_rsContNews == 0) { ?>
        <div class="alert alert-danger display-show">
          <button class="close" data-close="alert"></button>
          Esta newsletter não tem conteúdo associado. 
        </div>
      <?php }?>
      <div class="row">
        <div class="col-md-12"> 
          <!-- Begin: life time stats -->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletters - Agendamentos</div>
              <div class="form-actions actions btn-set">
                <button type="button" name="back" class="btn default" onClick="document.location='newsletter.php'"><i class="fa fa-angle-left"></i> Voltar</button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="tabbable">
                <ul class="nav nav-tabs">
                  <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-detalhe.php?id=<?php echo $_GET['id']; ?>'"> Detalhe </a> </li>
                  <li> <a href="#tab_agendar" data-toggle="tab" onClick="document.location='newsletter-enviar.php?id=<?php echo $_GET['id']; ?>'"> Agendar </a> </li>
                  <li class="active"> <a href="#tab_agendamentos" data-toggle="tab"> Agendamentos </a> </li>
                  <li> <a href="#tab_envio_teste" data-toggle="tab" onClick="document.location='newsletter-enviar-teste.php?id=<?php echo $_GET['id']; ?>'"> Envio teste </a> </li>
                </ul>
                <div class="tab-content no-space">
                  <div class="tab-pane active" id="tab_agendamentos">
                    <div class="table-container">
                      <div class="table-actions-wrapper"> <span> </span>
                        <select class="table-group-action-input form-control input-inline input-small input-sm">
                          <option value="">Selecione</option>
                          <option value="-1">Eliminar</option>
                          <option value="-2">Reactivar</option>
                        </select>
                        <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submeter</button>
                      </div>
                      <table class="table table-striped table-bordered table-hover" id="datatable_products">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="1%"> <input type="checkbox" class="group-checkable">
                            </th>
                            <th width="10%"> Data </th>
                            <th width="10%"> Hora </th>
                            <th width="10%"> Estado </th>
                            <th width="10%"> Tipo de Cliente </th>
                            <th> Listas </th>
                            <th width="10%"> Limite por hora</th>
                            <th width="10"> Ações </th>
                          </tr>
                          <tr role="row" class="filter">
                            <td></td>
                            <td><div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="Selecione">
                                <span class="input-group-btn">
                                <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span> </div></td>
                            <td>&nbsp;</td>
                            <td><select name="form_estado" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                                <option value="">Todos</option>
                                <?php while($row_rsEstados = $rsEstados->fetch()) { ?>
                                <option value="<?php echo $row_rsEstados["id"]; ?>"><?php echo $row_rsEstados["nome"]; ?></option>
                                <?php } ?>
                              </select></td>
                            <td><select name="form_tipo" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                                <option value="">Todos</option>
                                <?php while($row_rsTipos = $rsTipos->fetch()) { ?>
                                <option value="<?php echo $row_rsTipos["id"]; ?>"><?php echo $row_rsTipos["nome"]; ?></option>
                                <?php } ?>
                              </select></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><div class="margin-bottom-5">
                                <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Pesquisar</button>
                              </div>
                              <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Limpar</button></td>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/media/js/jquery.dataTables.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<!-- LINGUA PORTUGUESA --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/scripts/datatable.js"></script> 
<script src="newsletter-historico-list.js" data-news="<?php echo $_GET['id']; ?>"></script> 
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
function envia_news(id, id2){
	$.post("newsletter-proceder-envio.php", {id:id, id_hist:id2}, function(data){
		$('#modal_trigg').click();
	});
}
</script>
</body>
<!-- END BODY -->
</html>