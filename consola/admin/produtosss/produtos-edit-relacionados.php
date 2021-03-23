<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='ec_produtos_produtos';
$menu_sub_sel='';

$tab_sel=1;
if($_GET['tab_sel'] > 0) $tab_sel=$_GET['tab_sel'];

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_relacionado_form")) {
  $manter = $_POST['manter'];
  
  $query_rsP = "SELECT * FROM l_pecas".$extensao." WHERE id=:id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $id, PDO::PARAM_INT);
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
  DB::close();
    
  if($_POST['produto']!="" && $_POST['produto']!=0) {
    $query_rsProjecto = "SELECT * FROM l_pecas_relacao WHERE id_peca = :id AND id_relacao=:produto";
    $rsProjecto = DB::getInstance()->prepare($query_rsProjecto);
    $rsProjecto->bindParam(':id', $id, PDO::PARAM_INT);
    $rsProjecto->bindParam(':produto', $_POST['produto'], PDO::PARAM_INT);
    $rsProjecto->execute();
    $row_rsProjecto = $rsProjecto->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsProjecto = $rsProjecto->rowCount();
    
    if($totalRows_rsProjecto==0) {
      $insertSQL = "SELECT MAX(id) FROM l_pecas_relacao";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->execute();
      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
      
      $max_id_2 = $row_rsInsert["MAX(id)"]+1;     
      
      $insertSQL = "INSERT INTO l_pecas_relacao (id, id_peca, id_relacao) VALUES (:max_id_2, :id, :categoria)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
      $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);  
      $rsInsert->bindParam(':categoria', $_POST['produto'], PDO::PARAM_INT); 
      $rsInsert->execute();
    }
    
    if(isset($_POST['inverso']) && $_POST['inverso']==1) {
      $query_rsProjecto = "SELECT * FROM l_pecas_relacao WHERE id_peca = :produto AND id_relacao=:id";
      $rsProjecto = DB::getInstance()->prepare($query_rsProjecto);
      $rsProjecto->bindParam(':produto', $_POST['produto'], PDO::PARAM_INT);
      $rsProjecto->bindParam(':id', $id, PDO::PARAM_INT);
      $rsProjecto->execute();
      $row_rsProjecto = $rsProjecto->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsProjecto = $rsProjecto->rowCount();
      
      if($totalRows_rsProjecto==0) {
        $insertSQL = "SELECT MAX(id) FROM l_pecas_relacao";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->execute();
        $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
        
        $max_id_2 = $row_rsInsert["MAX(id)"]+1;     
        
        $insertSQL = "INSERT INTO l_pecas_relacao (id, id_peca, id_relacao) VALUES (:max_id_2, :categoria, :id)";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT); 
        $rsInsert->bindParam(':categoria', $_POST['produto'], PDO::PARAM_INT); 
        $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
        $rsInsert->execute();
      }
    }
  }

  DB::close();   
  
  if(!$manter) 
    header("Location: produtos.php?alt=1");
  else
    header("Location: produtos-edit-relacionados.php?id=".$id."&ins=1");
}


if(isset($_GET['reg'])) {
  if(isset($_GET['rem']) && $_GET['rem']==1) { 
    $projecto=$_GET['reg'];
    
    $insertSQL = "DELETE FROM l_pecas_relacao WHERE id=:projecto AND id_peca=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':projecto', $projecto, PDO::PARAM_INT);  
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
    $rsInsert->execute();
    DB::close();  
    
    header("Location: produtos-edit-relacionados.php?id=".$id."&r=1");
  }
}

$query_rsP = "SELECT * FROM l_pecas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsCat = "SELECT id, nome FROM l_categorias_en WHERE cat_mae='0' ORDER BY nome ASC";
$rsCat = DB::getInstance()->query($query_rsCat);
$rsCat->execute();
$totalRows_rsCat = $rsCat->rowCount();

$query_rsList = "SELECT l_pecas_relacao.*, l_pecas_en.imagem1, l_pecas_en.imagem2, l_pecas_en.ref, l_pecas_en.nome, l_pecas_en.id as peca_id FROM l_pecas_relacao, l_pecas_en WHERE id_peca=:id AND l_pecas_en.id=l_pecas_relacao.id_relacao ORDER BY l_pecas_relacao.ordem ASC, l_pecas_relacao.id ASC";
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
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<script type="text/javascript">
function carregaFiltros(cat){
  $.post("produtos-rpc.php", {op:"carregaProdutos", cat:cat, id:'<?php echo $id; ?>'}, function(data){
    document.getElementById('div_filtros').innerHTML=data;  
    $('#produto').select2();                
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
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>   
          <form id="produtos_relacionado_form" name="produtos_relacionado_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?> - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='produtos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
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
                    <li class="nab-tab" onClick="window.location='produtos-edit-filtros.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_filtros']; ?> </a> </li>
                   <!--  <li class="nav-tab" onClick="window.location='produtos-edit-quantidades.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_quantidades']; ?> </a> </li> -->
                    <li class="nav-tab active" onClick="window.location='produtos-edit-relacionados.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_relacionados']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=2'"> <a id="tab_2" href="#tab_estatisticas" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=3'"> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_general">
                      <div class="form-body">
                        <?php if(isset($_GET['ins']) && $_GET['ins']==1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['env']; ?> </span>
                          </div>
                        <?php } ?>
                        <?php if(isset($_GET['r']) && $_GET['r']==1) { ?>
                          <div class="alert alert-danger display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span>
                          </div>
                        <?php } ?>
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>  
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="categoria"><?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: </label>
                          <div class="col-md-6">
                            <select class="form-control select2me" id="categoria" name="categoria" onchange="carregaFiltros(this.value)">
                              <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                              <option value="0"<?php if(!(strcmp("0", $_GET['categoria']))) { echo "selected=\"selected\"";} ?>><?php echo $RecursosCons->RecursosCons['opt_todas_cat_label']; ?></option>
                              	<?php umaCategoriaPorProd(0,"",-1); ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="filtro"><?php echo $RecursosCons->RecursosCons['produto']; ?>: </label>
                          <div class="col-md-6">
                            <div id="div_filtros"><select class="form-control select2me" id="produto" name="produto" >
                              <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-1">
                            <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-upload"></i> <?php echo $RecursosCons->RecursosCons['inserir']; ?></button>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-2"></div>
                          <div class="col-md-7" style="padding-top:7px;text-align:left">
                            <label><input type="checkbox" class="form-control" name="inverso" id="inverso" value="1"/>&nbsp;<?php echo $RecursosCons->RecursosCons['rel_ordem_inversa_msg']; ?></label>
                          </div>
                        </div>              
                      </div>
                    </div>                   
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="produtos_relacionado_form" />
          </form>
        </div>
      </div>
      <?php if($totalRows_rsList>0) { ?>
        <div class="row">
          <div class="col-md-12" style="padding-top:30px">
            <div class="portlet box green">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-list"></i><?php echo $RecursosCons->RecursosCons['prod_relacionados']; ?>
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
                        <th>
                          <?php echo $RecursosCons->RecursosCons['imagem']; ?></th>
                        <th>
                         <?php echo $RecursosCons->RecursosCons['produto']; ?>
                        </th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $cont=0; while($row_rsList = $rsList->fetch()) { $cont++; ?>
                      <tr>
                        <td><?php echo $cont; ?></td>
                        <td><?php if ($row_rsList['imagem1'] != '' && file_exists('../../../imgs/produtos/'.$row_rsList['imagem1'])) {?>
                        <a href="../../../imgs/produtos/<?php echo $row_rsList['imagem2']; ?>" data-fancybox ><img src="../../../imgs/produtos/<?php echo $row_rsList['imagem2']; ?>" height="60" border="0" /></a>
                        <?php } ?></td>
                        <td><?php if($row_rsList['ref']!='') echo "Ref.: ".$row_rsList['ref']."<br>";?><a href="produtos-edit.php?id=<?php echo $row_rsList['id_relacao']; ?>" target="_blank"><?php echo $row_rsList['nome'];?></a></td>
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
                                  <button type="button" class="btn blue" onClick="document.location='produtos-edit-relacionados.php?id=<?php echo $id; ?>&rem=1&reg=<?php echo $row_rsList['id'];?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
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
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
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
</body>
<!-- END BODY -->
</html>
