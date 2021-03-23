<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$id = $_GET['id'];
$fixo = $_GET['fixo'];
$pagina = $_GET['pagina'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Paginas Fixas';

if($fixo == 0) {
	$menu_sub_sel='paginas_outras';
	$nome_sel='Outras Páginas';
}

$tab_sel=2;
$inserido=0;

$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
$rsLinguas->execute();
$row_rsLinguas = $rsLinguas->fetchAll();
$totalRows_rsLinguas = $rsLinguas->rowCount();

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if($id>0 && isset($_GET['id_img']) && $_GET['id_img'] != "" && $_GET['id_img'] != 0) {
		$id_img = $_GET['id_img'];	
		
    foreach ($row_rsLinguas as $linguas) {
  		$query_rsProc = "SELECT ficheiro FROM paginas_blocos_ficheiros_".$linguas["sufixo"]." WHERE id=:id_img AND bloco = :id";
  		$rsProc = DB::getInstance()->prepare($query_rsProc);
  		$rsProc->bindParam(':id', $id, PDO::PARAM_INT, 5);
      $rsProc->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);  
  		$rsProc->execute();
      $row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
  		$totalRows_rsProc = $rsProc->rowCount();
  		
  		if($totalRows_rsProc>0) {
  			if ($row_rsProc['ficheiro']!='') {
  				@unlink('../../../imgs/paginas/'.$row_rsProc['ficheiro']);
  			}
  		
  			$query_rsP = "DELETE FROM paginas_blocos_ficheiros_".$linguas["sufixo"]." WHERE id=:id_img AND bloco = :id";
  			$rsP = DB::getInstance()->prepare($query_rsP);
  			$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
        $rsP->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);
  			$rsP->execute();		
  		}
    }

    DB::close();	

    alteraSessions('paginas');
    alteraSessions('paginas_menu');
    alteraSessions('paginas_fixas');	
		
		header("Location: paginas-blocos-ficheiros.php?id=".$id."&pagina=".$pagina."&fixo=".$fixo."&r=1");
	}
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_form")) {
	$manter = $_POST['manter'];
	
	$query_rsImg = "SELECT * FROM paginas_blocos_ficheiros_pt WHERE bloco=:id ORDER BY ordem ASC, id DESC";
	$rsImg = DB::getInstance()->prepare($query_rsImg);
	$rsImg->bindParam(':id', $id, PDO::PARAM_INT, 5);
	$rsImg->execute();
	$totalRows_rsImg = $rsImg->rowCount();
	
	if($totalRows_rsImg) {
		while($row_rsImg = $rsImg->fetch()) {
			$id_img=$row_rsImg['id'];		
			$ordem=$_POST['ordem_'.$id_img];
      $nome=$_POST['nome_'.$id_img];
							
			$insertSQL = "UPDATE paginas_blocos_ficheiros".$extensao." SET nome=:nome WHERE id=:id_img AND bloco = :id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
      $rsInsert->bindParam(':id_img', $id_img, PDO::PARAM_INT);
			$rsInsert->execute();

      foreach ($row_rsLinguas as $linguas) {
        $insertSQL = "UPDATE paginas_blocos_ficheiros_".$linguas["sufixo"]." SET ordem=:ordem WHERE id=:id_img AND bloco = :id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);
        $rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
        $rsInsert->bindParam(':id_img', $id_img, PDO::PARAM_INT);
        $rsInsert->execute();
      }
		}	
	}

  DB::close();
		
  alteraSessions('paginas');
  alteraSessions('paginas_menu');
  alteraSessions('paginas_fixas');
  
	$inserido=1;
		
	if(!$manter) header("Location: paginas-blocos-ficheiros.php?alt=1&id=".$id."&pagina=".$pagina."&fixo=".$fixo);
}


$query_rsP = "SELECT * FROM paginas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $pagina, PDO::PARAM_INT, 5);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsP2 = "SELECT * FROM paginas_blocos".$extensao." WHERE id=:id";
$rsP2 = DB::getInstance()->prepare($query_rsP2);
$rsP2->bindParam(':id', $id, PDO::PARAM_INT, 5);	
$rsP2->execute();
$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP2 = $rsP2->rowCount();

$query_rsImg = "SELECT * FROM paginas_blocos_ficheiros".$extensao." WHERE bloco=:id ORDER BY ordem ASC, id DESC";
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?> - <?php echo $RecursosCons->RecursosCons['blocos']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
            <li>
                <a href="paginas.php?fixo=<?php echo $fixo; ?>"> <?php echo $RecursosCons->RecursosCons['paginas']; ?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="paginas-edit.php?fixo=<?php echo $fixo; ?>&id=<?php echo $pagina; ?>"> <?php echo $RecursosCons->RecursosCons['blocos']; ?> </a>
            </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
               <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $nome_sel; ?> - <?php echo $row_rsP['nome']; ?> - Bloco - <?php echo $row_rsP2['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='paginas-blocos.php?fixo=<?php echo $fixo; ?>&pagina=<?php echo $pagina; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li onclick="window.location='paginas-blocos-edit.php?id=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&tab_sel=1'"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                	   <li class="active"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_ficheiros']; ?> </a> </li>
                     <?php /* <li onclick="window.location='paginas-blocos-edit.php?id=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&tab_sel=3'"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_fundo']; ?> </a> </li> */ ?>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_imagens">
                      <?php if($inserido==1) {?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> </div>
                      <?php } else if($_GET['suc']==1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_insert_suc']; ?> </span> </div>
                      <?php } else if($_GET['r']==1) { ?>
                      <div class="alert alert-danger display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_r']; ?>  </span> </div>
                      <?php } ?>
                      <div id="alert_div" class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> 
                      </div>
                      <div>
                        <div id="tab_images_uploader_container" class="form-group text-align-reverse margin-bottom-10">
                          <div class="col-md-4" align="left" style="margin-top: 5px;">
                            <div><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt4']; ?></span></div>
                          </div>
                          <div class="col-md-8">
                            <a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn yellow"> <i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['selec_ficheiros']; ?> </a> 
                            <a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn green"> <i class="fa fa-share"></i> <?php echo $RecursosCons->RecursosCons['upload_ficheiros']; ?> </a>
                          </div> 
                        </div>
                        <div class="row">
                          <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12"> </div>
                        </div>
                        <table class="table table-bordered table-hover margin-top-10">
                          <thead>
                            <tr role="row" class="heading">
                              <th width="20%"><?php echo $RecursosConss->RecursosCons['ficheiro']; ?></th>
                              <th width="20%"><?php echo $RecursosCons->RecursosCons['nome_label']; ?></th>
                              <th width="10%"><?php echo $RecursosCons->RecursosCons['tamanho_label']; ?></th>
                              <th width="10%"><?php echo $RecursosCons->RecursosCons['ordem']; ?></th>
                              <th width="10%"><?php echo $RecursosCons->RecursosCons['visivel_tab']; ?></th>
                              <th width="10%"></th>
                            </tr>
                          </thead>
                          <?php if($totalRows_rsImg>0){ ?>
                          <tbody>
                            <?php $cont=0; while($row_rsImg = $rsImg->fetch()) { $cont++; ?>
                            <?php if($row_rsImg['ficheiro']!="" && file_exists("../../../imgs/paginas/".$row_rsImg['ficheiro'])) { ?>
                            <tr>
                              <td>
                                <a data-fancybox="gallery" href="../../../imgs/paginas/<?php echo $row_rsImg['ficheiro']; ?>" class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"><?php echo $RecursosCons->RecursosCons['ver_ficheiro']; ?></a>
                              </td>
                              <td><input type="text" class="form-control" name="nome_<?php echo $row_rsImg['id']; ?>" value="<?php echo $row_rsImg['nome']; ?>"></td>
                              <td><?php echo $row_rsImg['tamanho'];?></td>
                              <td><input type="text" class="form-control" name="ordem_<?php echo $row_rsImg['id']; ?>" value="<?php echo $row_rsImg['ordem']; ?>"></td>
                              <td><input type="checkbox" <?php if($row_rsImg['visivel'] == 1) { echo "checked"; } ?> name="switch" id="switch_<?php echo $row_rsImg['id']; ?>" class="make-switch" data-on-color="success" data-on-text="Sim" data-off-color="danger" data-off-text="Não" data-off-value="0" data-on-value="1"></td>
                              <td><a href="#modal_delete_img_<?php echo $row_rsImg['id']; ?>" data-toggle="modal" class="btn default btn-sm"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a>
                                <div class="modal fade" id="modal_delete_img_<?php echo $row_rsImg['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
                                      </div>
                                      <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['rem_ficheiro_msg']; ?> </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn blue" onClick="document.location='paginas-blocos-ficheiros.php?id=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&rem=1&id_img=<?php echo $row_rsImg['id']; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
                                        <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
                                      </div>
                                    </div>
                                    <!-- /.modal-content --> 
                                  </div>
                                  <!-- /.modal-dialog --> 
                                </div>
                              </td>
                            </tr>
                            <?php }else{ ?>
                            <?php	
                              foreach ($row_rsLinguas as $linguas) {
                                $query_rsDelete = "DELETE FROM paginas_blocos_ficheiros_".$linguas["sufixo"]." WHERE id='".$row_rsImg['id']."'";
                                $rsDelete = DB::getInstance()->query($query_rsDelete);
                                $rsDelete->execute();
                              }
                							DB::close();
                						} ?>
                            <?php } ?>
                          </tbody>
                          <?php } ?>
                        </table>
                        <?php if($totalRows_rsImg == 0) { ?>
                          <?php echo $RecursosCons->RecursosCons['sem_imagens_inseridas']; ?>
                        <?php } ?>
                      </div>
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
<!--     --> 
<script src="paginas-blocos-ficheiros.js" data-tipo="<?php echo $row_rsP2['tipo']; ?>" data-id="<?php echo $id; ?>" data-ori="<?php echo $row_rsP2['orientacao']; ?>" data-pag="<?php echo $pagina; ?>" data-fixo="<?php echo $fixo; ?>" data-fullscreen="<?php echo $row_rsP2['fullscreen']; ?>" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>"></script> 
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
      url: 'paginas-rpc.php',
      type: 'POST',
      data: {op: 'ficheiros_visivel', valor: valor, id: id},
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