<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='quem_somos';
$menu_sub_sel='';

$tab_sel=0;

$inserido=0;

$tamanho_imagens1 = getFillSize('Quem Somos', 'imagem1');

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id_img']) && $_GET['id_img'] != "" && $_GET['id_img'] != 0) {
		$id_img = $_GET['id_img'];	
		
		$query_rsProc = "SELECT imagem1 FROM quem_somos_imagens WHERE id=:id_img";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);
		$rsProc->execute();
    $row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsProc = $rsProc->rowCount();
		
		if($totalRows_rsProc>0) {
			if ($row_rsProc['imagem1']!='') { 
				@unlink('../../../imgs/quem-somos/'.$row_rsProc['imagem1']);
			}
		
			$query_rsP = "DELETE FROM quem_somos_imagens WHERE id=:id_img";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);
			$rsP->execute();		
		}	
    DB::close();

    alteraSessions('quem_somos');		
		
		header("Location: quem-somos-imagens.php?r=1");
	}
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "quem_somos_imgs_form")) {

	$query_rsImg = "SELECT * FROM quem_somos_imagens ORDER BY ordem ASC, id DESC";
	$rsImg = DB::getInstance()->prepare($query_rsImg);
	$rsImg->execute();
	$totalRows_rsImg = $rsImg->rowCount();
	
	if($totalRows_rsImg) {
		while($row_rsImg = $rsImg->fetch()) {
			$id_img=$row_rsImg['id'];
			$ordem=$_POST['ordem_'.$id_img];
			
			if($ordem > 0) {
				$insertSQL = "UPDATE quem_somos_imagens SET ordem=:ordem WHERE id=:id_img ";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);
        $rsInsert->bindParam(':id_img', $id_img, PDO::PARAM_INT);
				$rsInsert->execute();
			}
		}
	}

  DB::close();

  alteraSessions('quem_somos'); 
		
  header("Location: quem-somos-imagens.php?alt=1");
}

$query_rsImg = "SELECT * FROM quem_somos_imagens WHERE slider = 0 and visivel = 1 ORDER BY ordem ASC, id DESC";
$rsImg = DB::getInstance()->prepare($query_rsImg);
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_quem_somos']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_conteudo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="quem-somos.php"><?php echo $RecursosCons->RecursosCons['menu_quem_somos']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['editar_conteudo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="quem_somos_imgs_form" name="quem_somos_imgs_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_quem_somos']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li onClick="window.location='quem-somos.php?tab_sel=1'"> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li onClick="window.location='quem-somos.php?tab_sel=2'"> <a href="#tab_imagens" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>
                    <li class="active" onClick="window.location='quem-somos-imagens.php'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_galeria']; ?> </a> </li> </li>
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
                      <?php if($totalRows_rsImg>0){ ?>
                      <table class="table table-bordered table-hover margin-top-10">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="20%"> <?php echo $RecursosCons->RecursosCons['imagem']; ?> </th>
                            <th width="8%"> <?php echo $RecursosCons->RecursosCons['ordem']; ?> </th>
                            <th width="8%"> <?php echo $RecursosCons->RecursosCons['visivel_tab']; ?> </th>
                            <th width="10%"> </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $cont=0; while($row_rsImg = $rsImg->fetch()) { $cont++; ?>
                          <?php if($row_rsImg['imagem1']!="" && file_exists("../../../imgs/quem-somos/".$row_rsImg['imagem1'])) { ?>
                          <tr>
                            <td><a href="../../../imgs/quem-somos/<?php echo $row_rsImg['imagem1']; ?>" data-fancybox="gallery"> <img class="img-responsive" style="max-width: 150px" src="../../../imgs/quem-somos/<?php echo $row_rsImg['imagem1']; ?>"> </a></td>
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
                                      <button type="button" class="btn blue" onClick="document.location='quem-somos-imagens.php?rem=1&id_img=<?php echo $row_rsImg['id']; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
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
              							$query_rsDelete = "DELETE FROM quem_somos_imagens WHERE id='".$row_rsImg['id']."'";
              							$rsDelete = DB::getInstance()->query($query_rsDelete);
              							$rsDelete->execute();
              							DB::close();
              						} ?>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php }
                        else {
                          echo $RecursosCons->RecursosCons['sem_imagens_info'];
                        } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="quem_somos_imgs_form" />
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
<script src="quem-somos-imagens.js" data-id="<?php echo $id; ?>"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   GaleriaInit.init();

   $('.bootstrap-switch').click(function() {
    var valor = 0;
    if($(this).hasClass('bootstrap-switch-on')) valor = 1;
    var parts = $(this).find('input').attr('id').split('_');
    var id = parts['1'];
    $.ajax({
      url: 'conteudo-rpc.php',
      type: 'POST',
      data: {op: 'galeria_visivel_quem_somos', valor: valor, id: id},
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