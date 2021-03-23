<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='noticias';
$menu_sub_sel='';
$tab_sel=0;

$id=$_GET['id'];
$inserido=0;

$tamanho_imagens1 = getFillSize('Noticias', 'imagem1');

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if($id>0 && isset($_GET['id_img']) && $_GET['id_img'] != "" && $_GET['id_img'] != 0) {
		$id_img = $_GET['id_img'];	
		
		$query_rsProc = "SELECT imagem1, imagem2 FROM noticias_imagens WHERE id=:id_img AND id_peca = :id";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);
		$rsProc->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsProc->execute();
    $row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsProc = $rsProc->rowCount();
		
		if($totalRows_rsProc>0) {
			if ($row_rsProc['imagem1']!='') { 
				@unlink('../../../imgs/noticias/'.$row_rsProc['imagem1']);
				@unlink('../../../imgs/noticias/'.$row_rsProc['imagem2']);
			}
		
			$query_rsP = "DELETE FROM noticias_imagens WHERE id=:id_img AND id_peca = :id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id_img', $_GET['id_img'], PDO::PARAM_INT, 5);
			$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsP->execute();		
		}	
    DB::close();

    alteraSessions('noticias');		
		
		header("Location: noticias-edit-imagens.php?id=".$id."&r=1");
	}
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "noticias_form")) {
	$manter = $_POST['manter'];
	
	$query_rsImg = "SELECT * FROM noticias_imagens WHERE id_peca=:id ORDER BY ordem ASC, id DESC";
	$rsImg = DB::getInstance()->prepare($query_rsImg);
	$rsImg->bindParam(':id', $id, PDO::PARAM_INT, 5);
	$rsImg->execute();
	$totalRows_rsImg = $rsImg->rowCount();
	
	if($totalRows_rsImg) {
		while($row_rsImg = $rsImg->fetch()) {
			$id_img=$row_rsImg['id'];
			$descricao=$_POST['descricao_'.$id_img];
			$ordem=$_POST['ordem_'.$id_img];
			
			if($ordem > 0) {
				$insertSQL = "UPDATE noticias_imagens SET ordem=:ordem, legenda=:nome WHERE id=:id_img AND id_peca = :id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);
				$rsInsert->bindParam(':nome', $descricao, PDO::PARAM_STR, 5);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
        $rsInsert->bindParam(':id_img', $id_img, PDO::PARAM_INT);
				$rsInsert->execute();
			}
		}
	}

  DB::close();

  alteraSessions('noticias');
		
	if(!$manter) 
    header("Location: noticias.php?alt=1");
  else
    header("Location: noticias-edit-imagens.php?id=".$id."&alt=1");
}


$query_rsP = "SELECT * FROM noticias".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsImg = "SELECT * FROM noticias_imagens WHERE id_peca=:id ORDER BY ordem ASC, id DESC";
$rsImg = DB::getInstance()->prepare($query_rsImg);
$rsImg->bindParam(':id', $id, PDO::PARAM_INT, 5);
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['noticias']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="noticias.php"><?php echo $RecursosCons->RecursosCons['noticias']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>
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
              <button type="button" class="btn blue" onClick="document.location='noticias.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </button>
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
          <form id="noticias_form" name="noticias_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['noticias']; ?>  - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='noticias.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li onClick="window.location='noticias-edit.php?id=<?php echo $id; ?>&tab_sel=1'"> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li onClick="window.location='noticias-edit.php?id=<?php echo $id; ?>&tab_sel=2'"> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagem']; ?> </a> </li>
                    <li class="active" onClick="window.location='noticias-edit-imagens.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_galeria']; ?> </a> </li>
                    <li onClick="window.location='noticias-ficheiro.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_ficheiro']; ?> </a> </li>
                    <li onClick="window.location='noticias-edit.php?id=<?php echo $id; ?>&tab_sel=3'" <?php if($tab_sel==3) echo "class=\"active\""; ?>> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_imagens">
                      <?php if($_GET['suc'] == 1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_insert_suc']; ?> </span> </div>
                      <?php } ?>
                      <?php if($_GET['r'] == 1) { ?>
                      <div class="alert alert-danger display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_rem']; ?> </span> </div>
                      <?php } ?>
                      <?php if($_GET['alt'] == 1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> </div>
                      <?php } ?>
                      <div id="alert_div" class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> 
                      </div>
                      <div id="tab_images_uploader_container" class="form-group text-align-reverse margin-bottom-10">
                        <div class="col-md-4" align="left" style="margin-top:5px;">
                          <strong><?php echo $RecursosCons->RecursosCons['tamanho_max_img']; ?>: <?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?></strong><br>
                          <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
                        </div>
                        <div class="col-md-8"><a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn yellow"> <i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </a> <a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn green"> <i class="fa fa-share"></i> <?php echo $RecursosCons->RecursosCons['upload_imagens']; ?> </a></div>
                      </div>
                      <div class="form-group">
                        <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12"> </div>
                      </div>
                      <table class="table table-bordered table-hover margin-top-10">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="20%"> <?php echo $RecursosCons->RecursosCons['imagem']; ?> </th>
                            <th width="8%"> <?php echo $RecursosCons->RecursosCons['ordem']; ?> </th>
                            <th width="8%"> <?php echo $RecursosCons->RecursosCons['visivel_tab']; ?> </th>
                            <th width="10%"> </th>
                          </tr>
                        </thead>
                        <?php if($totalRows_rsImg>0){ ?>
                        <tbody>
                          <?php $cont=0; while($row_rsImg = $rsImg->fetch()) { $cont++; ?>
                          <?php if($row_rsImg['imagem1']!="" && file_exists("../../../imgs/noticias/".$row_rsImg['imagem1'])) { ?>
                          <tr>
                            <td><?php
                              $temp_var = explode('.', $row_rsImg['imagem1']);
                              if($temp_var[1] != "mp4" && $temp_var[1] != "wmv"){ ?>
                              <a href="../../../imgs/noticias/<?php echo $row_rsImg['imagem1']; ?>" data-fancybox="gallery"> <img class="img-responsive" style="max-width: 150px" src="../../../imgs/noticias/<?php echo $row_rsImg['imagem1']; ?>"> </a>
                              <?php } else { ?>
                                <a data-fancybox="gallery" href="../../../imgs/noticias/<?php echo $row_rsImg['imagem1']; ?>" class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"><?php echo $RecursosCons->RecursosCons['ver_video']; ?></a>
                              <?php } ?>
                            </td>
                            <td><input type="text" class="form-control" name="ordem_<?php echo $row_rsImg['id']; ?>" value="<?php echo $row_rsImg['ordem']; ?>"></td>
                            <td><input type="checkbox" <?php if($row_rsImg['visivel'] == 1) { echo "checked"; } ?> name="switch" id="switch_<?php echo $row_rsImg['id']; ?>" class="make-switch" data-on-color="success" data-on-text="Sim" data-off-color="danger" data-off-text="Não" data-off-value="0" data-on-value="1"></td>
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
                                      <button type="button" class="btn blue" onClick="document.location='noticias-edit-imagens.php?id=<?php echo $id; ?>&rem=1&id_img=<?php echo $row_rsImg['id']; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
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
              							$query_rsDelete = "DELETE FROM noticias_imagens WHERE id='".$row_rsImg['id']."'";
              							$rsDelete = DB::getInstance()->query($query_rsDelete);
              							$rsDelete->execute();
              							DB::close();
              						} ?>
                          <?php } ?>
                        </tbody>
                        <?php } ?>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="noticias_form" />
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
   
<script src="noticias-edit-imagens.js" data-id="<?php echo $id; ?>"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   NoticiasEdit.init();

   $('.bootstrap-switch').click(function() {
    var valor = 0;
    if($(this).hasClass('bootstrap-switch-on')) valor = 1;
    var parts = $(this).find('input').attr('id').split('_');
    var id = parts['1'];
    $.ajax({
      url: 'noticias-rpc.php',
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