<?php include_once('../inc_pages.php'); ?>
<?php ini_set('display_errors', 1);

$menu_sel='ec_produtos_produtos';
$menu_sub_sel='';

$tab_sel=2;
$id=$_GET['id'];
$inserido=0;

$tamanho_imagens1 = getFillSize('Produtos', 'imagem1');

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
  if($id>0 && isset($_GET['id_img']) && $_GET['id_img'] != "" && $_GET['id_img'] != 0) {
    $id_img = $_GET['id_img'];  
    
    $query_rsProc = "SELECT imagem1, imagem2, imagem3, imagem4 FROM l_pecas_imagens WHERE id=:id_img AND id_peca = :id";
    $rsProc = DB::getInstance()->prepare($query_rsProc);
    $rsProc->bindParam(':id', $id, PDO::PARAM_INT);
    $rsProc->bindParam(':id_img', $id_img, PDO::PARAM_INT); 
    $rsProc->execute();
    $row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsProc = $rsProc->rowCount();
    
    if($totalRows_rsProc > 0) {
      @unlink('../../../imgs/produtos/'.$row_rsProc['imagem1']);
      @unlink('../../../imgs/produtos/'.$row_rsProc['imagem2']);
      @unlink('../../../imgs/produtos/'.$row_rsProc['imagem3']);
      @unlink('../../../imgs/produtos/'.$row_rsProc['imagem4']);
    
      $query_rsP = "DELETE FROM l_pecas_imagens WHERE id=:id_img AND id_peca = :id";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->bindParam(':id', $id, PDO::PARAM_INT);
      $rsP->bindParam(':id_img', $id_img, PDO::PARAM_INT);
      $rsP->execute();  
    } 

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
    
    while($row_rsLinguas = $rsLinguas->fetch()) {
      $query_rsP = "UPDATE l_pecas_".$row_rsLinguas["sufixo"]." SET imagem1 = NULL, imagem2 = NULL, imagem3 = NULL, imagem4 = NULL WHERE id = :id";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->bindParam(':id', $id, PDO::PARAM_INT);
      $rsP->execute();
    }

    DB::close();
    
    header("Location: produtos-edit-imagens.php?id=".$id."&r=1");
  }
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_form")) {
  $manter = $_POST['manter'];
  
  $query_rsImg = "SELECT * FROM l_pecas_imagens WHERE id_peca=:id ORDER BY ordem ASC, id DESC";
  $rsImg = DB::getInstance()->prepare($query_rsImg);
  $rsImg->bindParam(':id', $id, PDO::PARAM_INT);
  $rsImg->execute();
  $totalRows_rsImg = $rsImg->rowCount();
  
  if($totalRows_rsImg) {
    while($row_rsImg = $rsImg->fetch()) {
      $id_img=$row_rsImg['id'];     
      $ordem=$_POST['ordem_'.$id_img];
              
      $insertSQL = "UPDATE l_pecas_imagens SET ordem=:ordem WHERE id=:id_img AND id_peca = :id";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);
      $rsInsert->bindParam(':id_img', $id_img, PDO::PARAM_INT);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
      $rsInsert->execute(); 
    }
  }

  DB::close();
    
  if(!$manter) 
    header("Location: produtos.php?alt=1");
  else
    header("Location: produtos-edit-imagens.php?alt=1&id=".$id);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_link_video")) {
  $insertSQL1 = "SELECT MAX(id) FROM l_pecas_imagens";
  $rsInsert1 = DB::getInstance()->prepare($insertSQL1);
  $rsInsert1->execute();
  $row_rsInsert1 = $rsInsert1->fetch(PDO::FETCH_ASSOC);
  DB::close();
  $max_id_2 = $row_rsInsert1["MAX(id)"]+1;
//echo $max_id_2;
  $tipo = 2;   
 // pre($_POST);

//  die('here');
  $insertSQL2 = "INSERT INTO l_pecas_imagens (id, id_peca, video, tipo) VALUES (:max_id_2, :id_peca, :video, :tipo)";
  $rsInsert2 = DB::getInstance()->prepare($insertSQL2);
  $rsInsert2->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
  $rsInsert2->bindParam(':id_peca', $id, PDO::PARAM_INT);
  $rsInsert2->bindParam(':video', $_POST['video'], PDO::PARAM_STR,5);
 // $rsInsert->bindParam(':proporcao_video', $_POST['proporcao_video'], PDO::PARAM_INT);
  $rsInsert2->bindParam(':tipo', $tipo, PDO::PARAM_INT);
  //echo $rsInsert;
  /*pre($rsInsert2);*/

  //die;
  $rsInsert2->execute();

  $lastId = DB::getInstance()->lastInsertId();

  /*die($lastId.' hola');*/
  DB::close();
   
   header("Location: produtos-edit-imagens.php?id=".$id);
}

$query_rsP = "SELECT * FROM l_pecas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsImg = "SELECT * FROM l_pecas_imagens WHERE id_peca=:id ORDER BY ordem ASC, id DESC";
$rsImg = DB::getInstance()->prepare($query_rsImg);
$rsImg->bindParam(':id', $id, PDO::PARAM_INT);
$rsImg->execute();
$totalRows_rsImg = $rsImg->rowCount();

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
  #alert_div {
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

      <!-- video code starts -->
      <div class="modal fade bs-modal-lg" id="large" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%;">
          <div class="modal-content">
            <form id="form_link_video" name="form_link_video" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['adicionar_link_video']; ?></h4>
            </div>
            <div class="modal-body">
              <div class="portlet-body">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="video"><?php echo $RecursosCons->RecursosCons['video_label']; ?>: </label>
                  <div class="col-md-8">
                    <textarea class="form-control" name="video" id="video"><?php echo $_POST['video']; ?></textarea>
                    <p class="help-block"><?php echo $RecursosCons->RecursosCons['help_block_video']; ?></strong></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label" for="proporcao_video"><?php echo $RecursosCons->RecursosCons['proporcao_video']; ?>: </label>
                  <div class="col-md-8 md-radio-inline">
                    <div class="md-radio">
                      <input id="proporcao1" name="proporcao_video" value="1" checked class="md-radiobtn radio_proporcao" type="radio">
                      <label for="proporcao1">
                      <span></span>
                      <span class="check"></span>
                      <span class="box"></span>
                      16:9 </label>
                    </div>
                    <div class="md-radio">
                      <input id="proporcao2" name="proporcao_video" value="2" class="md-radiobtn radio_proporcao" type="radio">
                      <label for="proporcao2">
                      <span></span>
                      <span class="check"></span>
                      <span class="box"></span>
                      4:3 </label>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="form_link_video"/>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['btn_fechar']; ?></button>
              <button type="submit" class="btn green"><i class="fa fa-save"></i> <?php echo $RecursosCons->RecursosCons['inserir']; ?></button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- video code ends -->

      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
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
                    <li onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=5'"> <a href="#tab_promocao" data-toggle="tab" onClick="document.getElementById('tab_sel').value='5'"> <?php echo $RecursosCons->RecursosCons['tab_promocao']; ?> </a> </li>
                    <li class="nav-tab active" onClick="window.location='produtos-edit-imagens.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit-stocks.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_stocks']; ?> </a> </li>
                    <li onClick="window.location='produtos-edit-filtros.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_filtros']; ?> </a> </li>
                    <!-- <li class="nav-tab" onClick="window.location='produtos-edit-quantidades.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_quantidades']; ?> </a> </li> -->
                    <li class="nav-tab" onClick="window.location='produtos-edit-relacionados.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_relacionados']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=2'"> <a id="tab_2" href="#tab_estatisticas" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=3'"> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                    
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_imagens">
                      <?php if($_GET['alt']==1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> </div>
                      <?php } ?>
                      <?php if($_GET['suc']==1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_insert_suc']; ?></span> </div>
                      <?php } ?>
                      <?php if($_GET['r']==1) { ?>
                      <div class="alert alert-danger display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_rem']; ?> </span> </div>
                      <?php } ?>
                      <div id="alert_div" class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> 
                      </div>
                      <div id="tab_images_uploader_container" class="form-group text-align-reverse margin-bottom-10">
                        <div class="col-md-4" align="left" style="margin-top:5px;">
                          <strong><?php echo $RecursosCons->RecursosCons['tamanho_max_img']; ?> <?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?></strong><br>
                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
                        </div>
                        <div class="col-md-8">
                          <a id="add_videos" href="#large" data-toggle="modal" class="btn blue"> <i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['add_videos_link']; ?> </a>
                          <a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn yellow"> <i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </a> <a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn green"> <i class="fa fa-share"></i> <?php echo $RecursosCons->RecursosCons['upload_imagens']; ?> </a></div> </div>
                      <div class="form-group">
                        <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12"> </div>
                      </div>
                      <table class="table table-bordered table-hover margin-top-10">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="20%"><?php echo $RecursosCons->RecursosCons['imagem']; ?> </th>
                            <th width="10%"><?php echo $RecursosCons->RecursosCons['ordem']; ?> </th>
                            <th width="10%"><?php echo $RecursosCons->RecursosCons['visivel_tab']; ?> </th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <?php if($totalRows_rsImg>0){ ?>
                        <tbody>
                          <?php $cont=0; while($row_rsImg = $rsImg->fetch()) { $cont++; ?>
                          <?php if( ($row_rsImg['imagem1']!="" && file_exists("../../../imgs/produtos/".$row_rsImg['imagem1']))  || $row_rsImg['video'] != "" ) { ?>
                          <?php   
                          if($cont==1) { //copiar primeira imagem para a tabela das peças             
                            $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
                            $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
                            $rsLinguas->execute();
                            $totalRows_rsLinguas = $rsLinguas->rowCount();
              
                            while($row_rsLinguas = $rsLinguas->fetch()) {
                              $insertSQL = "UPDATE l_pecas_".$row_rsLinguas['sufixo']." SET imagem1=:imagem1, imagem2=:imagem2, imagem3=:imagem3, imagem4=:imagem4 WHERE id=:id";
                              $rsInsert = DB::getInstance()->prepare($insertSQL);
                              $rsInsert->bindParam(':imagem1', $row_rsImg['imagem1'], PDO::PARAM_STR, 5);
                              $rsInsert->bindParam(':imagem2', $row_rsImg['imagem2'], PDO::PARAM_STR, 5);
                              $rsInsert->bindParam(':imagem3', $row_rsImg['imagem3'], PDO::PARAM_STR, 5);
                              $rsInsert->bindParam(':imagem4', $row_rsImg['imagem4'], PDO::PARAM_STR, 5);
                              $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
                              $rsInsert->execute();
                            }
                            DB::close();
                          }
                          ?>
                          <tr>
                            <td>
                              <?php
                              if( $row_rsImg['tipo'] == 2 )
                              {
                                ?>
                                  <iframe width="180" height="100%" src="<?php echo $row_rsImg['video']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <?php
                              }else{
                              ?>
                              <a href="../../../imgs/produtos/<?php echo $row_rsImg['imagem1']; ?>" data-fancybox="gallery"> <img class="img-responsive" style="max-width:180px" src="../../../imgs/produtos/<?php echo $row_rsImg['imagem3']; ?>"> </a>
                              <?php
                              }
                              ?>
                            </td>
                            <td><input type="text" class="form-control" name="ordem_<?php echo $row_rsImg['id']; ?>" value="<?php echo $row_rsImg['ordem']; ?>"></td>
                            <td><input type="checkbox" <?php if($row_rsImg['visivel'] == 1) { echo "checked"; } ?> name="switch" id="switch_<?php echo $row_rsImg['id']; ?>" class="make-switch" data-on-color="success" data-on-text="<?php echo $RecursosCons->RecursosCons['text_visivel_sim']; ?>" data-off-color="danger" data-off-text="<?php echo $RecursosCons->RecursosCons['text_visivel_nao']; ?>" data-off-value="0" data-on-value="1"></td>
                            <td><a href="#modal_delete_img_<?php echo $row_rsImg['id']; ?>" data-toggle="modal" class="btn default btn-sm"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?></a>
                              <div class="modal fade" id="modal_delete_img_<?php echo $row_rsImg['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                      <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
                                    </div>
                                    <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['rem_imagem_msg']; ?> </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn blue" onClick="document.location='produtos-edit-imagens.php?id=<?php echo $id; ?>&rem=1&id_img=<?php echo $row_rsImg['id']; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
                                      <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
                                    </div>
                                  </div>
                                  <!-- /.modal-content --> 
                                </div>
                                <!-- /.modal-dialog --> 
                              </div></td>
                          </tr>
                          <?php } else { ?>
                          <?php 
                            /*$query_rsDelete = "DELETE FROM l_pecas_imagens WHERE id='".$row_rsImg['id']."'";
                            $rsDelete = DB::getInstance()->query($query_rsDelete);
                            $rsDelete->execute();
                            DB::close();*/
                          } ?>
                          <?php } ?>
                        </tbody>
                        <?php } ?>
                      </table>
                      <?php if($totalRows_rsImg==0) { ?>
                        Não tem imagens inseridas.
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="produtos_form" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
   
<script src="produtos-edit-imagens.js" data-id="<?php echo $id; ?>"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  EcommerceProductsEdit.init();


  $('.bootstrap-switch').click(function() {
    var valor = 0;
    if($(this).hasClass('bootstrap-switch-on')) valor = 1;
    var parts = $(this).find('input').attr('id').split('_');
    var id = parts['1'];
    $.ajax({
      url: 'produtos-rpc.php',
      type: 'POST',
      data: {op: 'galeria_visivel', valor: valor, id: id},
    })
    .done(function(data) {
      $('#alert_div').css('display', 'block');

      setTimeout(function() { $('#alert_div').css('display', 'none'); }, 10000);
    });
  });
});
</script>
</body>
<!-- END BODY -->
</html>
