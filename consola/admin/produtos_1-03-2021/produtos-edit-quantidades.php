<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='ec_produtos_produtos';
$menu_sub_sel='';

$tab_sel=1;
if($_GET['tab_sel'] > 0) $tab_sel=$_GET['tab_sel'];

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_qtd_desc")) {
  $manter = $_POST['manter']; 
  
  $insertSQL = "UPDATE l_pecas".$extensao." SET quantidades_descricao=:descricao WHERE id=:id";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':descricao', $_POST['quantidades_descricao'], PDO::PARAM_STR, 5); 
  $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
  $rsInsert->execute();
  DB::close();  
  
  if(!$manter) 
    header("Location: produtos.php?alt=1");
  else
    header("Location: produtos-edit-quantidades.php?alt=1&id=".$id);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_qtd_atualiza")) {    
  $query_rsP2 ="SELECT id FROM l_pecas_desconto WHERE id_peca=:id ORDER BY min ASC, max ASC";
  $rsP2 = DB::getInstance()->prepare($query_rsP2);
  $rsP2->bindParam(':id', $id, PDO::PARAM_INT);
  $rsP2->execute();
  $totalRows_rsP2 = $rsP2->rowCount();
      
  if($totalRows_rsP2>0) {
    while($row_rsP2 = $rsP2->fetch()) {
      $id_valor=$row_rsP2['id'];

      $insertSQL = "UPDATE l_pecas_desconto SET min=:min, max=:max, desconto=:desconto WHERE id=:id_valor";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':min', $_POST['min_'.$id_valor], PDO::PARAM_INT);
      $rsInsert->bindParam(':max', $_POST['max_'.$id_valor], PDO::PARAM_INT);
      $rsInsert->bindParam(':desconto', $_POST['preco_'.$id_valor], PDO::PARAM_INT); 
      $rsInsert->bindParam(':id_valor', $id_valor, PDO::PARAM_INT);  
      $rsInsert->execute();  
    }
  }
      
  if($_POST['min_n']!="" && $_POST['preco_n']!="") {
    $insertSQL = "INSERT INTO l_pecas_desconto (id_peca, min, max, desconto) VALUES (:id, :min, :max, :desconto)";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
    $rsInsert->bindParam(':min', $_POST['min_n'], PDO::PARAM_INT);
    $rsInsert->bindParam(':max', $_POST['max_n'], PDO::PARAM_INT);
    $rsInsert->bindParam(':desconto', $_POST['preco_n'], PDO::PARAM_INT);  
    $rsInsert->execute();
  }

  DB::close(); 

  header("Location: produtos-edit-quantidades.php?alt=1&id=".$id);
}

if(isset($_GET['reg'])) {
  if(isset($_GET['rem']) && $_GET['rem']==1) {
    $projecto=$_GET['reg'];
    
    $insertSQL = "DELETE FROM l_pecas_desconto WHERE id=:projecto AND id_peca=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':projecto', $projecto, PDO::PARAM_INT);  
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
    $rsInsert->execute();
    DB::close();  
    
    header("Location: produtos-edit-quantidades.php?id=".$id."&r=1");
  } 
}

$query_rsP = "SELECT * FROM l_pecas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsList = "SELECT * FROM l_pecas_desconto WHERE id_peca=:id ORDER BY min ASC, max ASC";
$rsList = DB::getInstance()->prepare($query_rsList);
$rsList->bindParam(':id', $id, PDO::PARAM_INT);
$rsList->execute();
$totalRows_rsList = $rsList->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<script type="text/javascript">
function carregaFiltros(cat){
  $.post("produtos-rpc.php", {op:"carregaFiltros", cat:cat, id:'<?php echo $id; ?>'}, function(data){
    document.getElementById('div_filtros').innerHTML=data;  
    $('#filtro').select2();               
  });
}
</script>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['produtos']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           
          <li>
            <a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?> <i class="fa fa-angle-right"></i></a>
          </li>
          <li>
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?> </a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->      
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='produtos.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">     
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>     
          <form id="produtos_qtd_desc" name="produtos_qtd_desc" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?> - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='produtos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=1'"> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=5'"> <a href="#tab_promocao" data-toggle="tab" onClick="document.getElementById('tab_sel').value='5'"> <?php echo $RecursosCons->RecursosCons['tab_promocao']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit-imagens.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit-stocks.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_stocks']; ?> </a> </li>
                    <li onClick="window.location='produtos-edit-filtros.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_filtros']; ?> </a> </li>
                   <!--  <li class="nav-tab active" onClick="window.location='produtos-edit-quantidades.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_quantidades']; ?> </a> </li> -->
                    <li class="nav-tab" onClick="window.location='produtos-edit-relacionados.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_relacionados']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=2'"> <a id="tab_2" href="#tab_estatisticas" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=3'"> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_general">
                      <div class="form-body">
                        <?php if($_GET['alt']==1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>
                          </div>
                        <?php } ?>
                        <?php if(isset($_GET['r']) && $_GET['r']==1) { ?>
                          <div class="alert alert-danger display-show">
                          <button class="close" data-close="alert"></button>
                          <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> </div>   
                        <?php } ?> 
                        <div class="form-group">
                          <div class="col-md-6">
                            <label class="control-label" for="quantidades_descricao"><?php echo $RecursosCons->RecursosCons['descr_quantidades']; ?></label>
                            <textarea class="form-control" id="quantidades_descricao" name="quantidades_descricao"><?php echo $row_rsP['quantidades_descricao']; ?></textarea>
                          </div>
                        </div>            
                      </div>
                    </div>                   
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="produtos_qtd_desc" />
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" style="padding-top:30px">
          <form id="produtos_qtd_atualiza" name="produtos_qtd_atualiza" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div style="width:100%;text-align:right;padding-bottom:10px"><button class="btn btn-sm green" id="bt_submete" type="submit"><i class="fa fa-refresh"></i> <?php echo $RecursosCons->RecursosCons['guarda_alt']; ?> </button></div>       
            <div class="portlet box green">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-list"></i><?php echo $RecursosCons->RecursosCons['sel_quantidade_desc']; ?>
                </div>
                <div class="tools">
                  <a href="javascript:;" class="collapse">
                  </a>
                  <a href="javascript:;" class="reload">
                  </a>
                </div>
              </div>
              <div class="portlet-body">
                <div class="table-scrollable">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>&nbsp;</th>
                        <th><?php echo $RecursosCons->RecursosCons['qtd_min_uni']; ?></th>
                        <th><?php echo $RecursosCons->RecursosCons['qtd_max_uni']; ?></th>
                        <th><?php echo $RecursosCons->RecursosCons['desconto_perc']; ?></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>   
                      <tr>
                        <td><?php echo $cont; ?></td>
                        <td><input type="text" class="form-control" name="min_n" id="min_n" value="" maxlength="3" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)"></td>
                        <td><input type="text" class="form-control" name="max_n" id="max_n" value="" maxlength="3" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)"></td>
                        <td><input type="text" class="form-control" name="preco_n" id="preco_n" value="" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)"></td>
                        <td>&nbsp;</td>
                      </tr>
                      <?php if($totalRows_rsList>0){ ?>
                      <?php $cont=0; while($row_rsList = $rsList->fetch()) { $cont++; ?>
                      <tr>
                        <td valign="middle" align="center"><?php echo $cont; ?></td>
                        <td><input type="text" class="form-control" name="min_<?php echo $row_rsList['id'];?>" id="min_<?php echo $row_rsList['id'];?>" value="<?php echo $row_rsList['min'];?>" maxlength="3" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)"></td>
                        <td><input type="text" class="form-control" name="max_<?php echo $row_rsList['id'];?>" id="max_<?php echo $row_rsList['id'];?>" value="<?php if($row_rsList['max'] > 0) echo $row_rsList['max'];?>" maxlength="3" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)"></td>
                        <td><input type="text" class="form-control" name="preco_<?php echo $row_rsList['id'];?>" id="preco_<?php echo $row_rsList['id'];?>" value="<?php echo $row_rsList['desconto'];?>" maxlength="2" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)"></td>
                        <td>
                          <a href="#modal_delete_<?php echo $row_rsList['id'];?>" data-toggle="modal" class="btn btn-sm red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>
                          <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                          <div class="modal fade" id="modal_delete_<?php echo $row_rsList['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                  <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
                                </div>
                                <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn blue" onClick="document.location='produtos-edit-quantidades.php?id=<?php echo $id; ?>&rem=1&reg=<?php echo $row_rsList['id'];?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
                                  <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
                                </div>
                              </div>
                              <!-- /.modal-content --> 
                            </div>
                            <!-- /.modal-dialog --> 
                          </div>
                          <!-- /.modal -->
                        </td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="produtos_qtd_atualiza" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  FormValidation.init();
});
</script> 
<script type="text/javascript">
var areas = Array('quantidades_descricao');
$.each(areas, function (i, area) {
 CKEDITOR.replace(area, {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "200px"
 });
});
</script>
</body>
<!-- END BODY -->
</html>
