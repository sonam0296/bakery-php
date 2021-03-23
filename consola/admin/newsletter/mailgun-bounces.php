<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_mailgun_bounces';
$menu_sub_sel='';

$query_rsErros = "SELECT DISTINCT(erro) FROM news_mailgun_bounces ORDER BY erro ASC";
$rsErros = DB::getInstance()->prepare($query_rsErros);
$rsErros->execute();
$totalRows_rsErros = $rsErros->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<style type="text/css">
  #working { 
    display: none;
  }
</style>
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
      <h3 class="page-title"> Newsletter » Mailgun Bounces <small>Listing</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;">Mailgun Bounces</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <div class="modal fade" id="processarEmailsDevolvidosModal" tabindex="-1" role="dialog" aria-labelledby="processarEmailsDevolvidosLabel">
        <div class="modal-dialog" style="width: 700px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="processarEmailsDevolvidosLabel"><strong>Process Bounced Emails</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              Are you sure you want to process bounced emails?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              <button type="button" id="confirm_process" class="btn btn-success">Yes</button>
            </div>
          </div>
        </div>
      </div> 
      <div class="modal fade" id="visivelModal" tabindex="-1" role="dialog" aria-labelledby="visivelLabel">
        <div class="modal-dialog" style="width: 700px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="visivelLabel"><strong>Put "Not visible"</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              Do you want to place this email as "not visible"?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              <button type="button" href="javascript:;" onClick="desativarEmail(1);" class="btn btn-success">Yes</button>
            </div>
          </div>
        </div>
      </div> 
      <div class="modal fade" id="removerModal" tabindex="-1" role="dialog" aria-labelledby="removerLabel">
        <div class="modal-dialog" style="width: 700px" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="display: inline-block" id="removerLabel"><strong>Remove from Mailgun</strong></h4>
              <i class="fa fa-times" style="float: right; cursor: pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body" style="width: 100%">
              Do you want to remove this email from Mailgun?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              <button type="button" href="javascript:;" onClick="desativarEmail(2);" class="btn btn-success">Yes</button>
            </div>
          </div>
        </div>
      </div> 
      <input type="hidden" id="id_reg" value="0"/>
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12"> 
          <!-- Begin: life time stats -->
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - Mailgun Bounces </div>
              <div class="actions">
                <span id="working" style="margin-left: 10px"><img src="ajax-loader.gif" alt=""/></span>
                <button type="button" id="processar" href="#processarEmailsDevolvidosModal" data-toggle="modal" class="btn blue" style="height: 33px"><i class="fa fa-refresh"></i> Process Bounced Emails</button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="alert alert-success display-hide" id="emails_devolvidos_alert">
                <button class="close" data-close="alert"></button>
                Bounced emails successfully processed! 
                <br><br>
                Total Bounced Emails: <strong id="emails_devolvidos_total"></strong>
              </div> 
              <div class="alert alert-success display-hide" id="emails_alert">
                <button class="close" data-close="alert"></button>
                Email deactivated successfully!
              </div> 
              <div class="alert alert-success display-hide" id="emails_alert2">
                <button class="close" data-close="alert"></button>
                Email removed from Mailgun successfully!
              </div> 
              <div class="table-container">
                <div class="table-actions-wrapper"> <span> </span>
                  <select class="table-group-action-input form-control input-inline input-small input-sm">
                    <option value="">Select</option>
                    <option value="1">Place not visible</option>
                    <option value="2">Remove from Mailgun</option>
                  </select>
                  <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> To submit</button>
                </div>
                <table class="table table-striped table-bordered table-hover" id="datatable_products">
                  <thead>
                    <tr role="row" class="heading">
                      <th width="1%"> <input type="checkbox" class="group-checkable"></th>
                      <th width="15%"> Date </th>
                      <th> Email </th>
                      <th width="50%"> Error </th>
                      <th width="10%"> Actions </th>
                    </tr>
                    <tr role="row" class="filter">
                      <td></td>
                      <td>
                        <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                          <input type="text" class="form-control form-filter input-sm" readonly name="form_data" placeholder="Select">
                          <span class="input-group-btn">
                          <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                        </div>
                      </td>
                      <td><input type="text" class="form-control form-filter input-sm" name="form_email" onKeyPress="submete(event)"></td>
                      <td>
                        <select name="form_erro" class="form-control form-filter input-sm select2me" onchange="document.getElementById('pesquisa').click();">
                          <option value="">All</option>
                          <?php if($totalRows_rsErros > 0) {
                            while($row_rsErros = $rsErros->fetch()) { ?>
                              <option value="<?php echo $row_rsErros["erro"]; ?>"><?php echo $row_rsErros["erro"]; ?></option>
                            <?php }
                          } ?>
                        </select>
                      </td>
                      <td><div class="margin-bottom-5">
                          <button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>
                        </div>
                        <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> To clean</button>
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
<script src="mailgun-bounces-list.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {  
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  ConteudoDados.init();

  $('#confirm_process').on('click', function() {
    $('#processarEmailsDevolvidosModal').modal('hide');
    $('#processar').attr("disabled", true);
    $('#working').css('display', 'inline-block'); 
    
    $.post("mailgun-bounces-rpc.php", {op:"processarEmailsDevolvidos"}, function(data) {
      $('#processar').attr("disabled", false);
      $('#working').css('display', 'none'); 

      $('#emails_devolvidos_alert').toggleClass('display-hide').toggleClass('display-show');
      $('#emails_devolvidos_total').text(data);

      setTimeout(function() {
        location.reload();
      }, 2000);
    });
  });
});

function saveID(id) {
  $('#id_reg').val(id);
}

//Tipo: 1 - colocar como não visível / 2 - apagar do mailgun
function desativarEmail(tipo) {
  $.post("mailgun-bounces-rpc.php", {op:"desativarEmail", tipo: tipo, id: $('#id_reg').val()}, function(data) {
    if(tipo == 1) {
      $('#visivelModal').modal('hide');

      $('#emails_alert').toggleClass('display-hide').toggleClass('display-show');
    }
    else if(tipo == 2) {
      $('#removerModal').modal('hide');

      $('#emails_alert2').toggleClass('display-hide').toggleClass('display-show');
    }

    setTimeout(function() {
      location.reload();
    }, 2000);
  });
}
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