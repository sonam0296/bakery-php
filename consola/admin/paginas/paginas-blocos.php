<?php include_once('../inc_pages.php'); ?>
<?php

$fixo = $_GET['fixo'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Paginas Fixas';

if($fixo == 0){
	$menu_sub_sel='paginas_outras';
	$nome_sel='Outras Páginas';
}

$tab_sel=1;

if($_GET['env']==1) $tab_sel=1;

$id=$_GET['pagina'];
$inserido=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "paginas_list_form")) {
	$query_rsList = "SELECT MAX(id) FROM paginas_blocos".$extensao." WHERE pagina =:id ORDER BY ordem ASC, nome ASC";
	$rsList = DB::getInstance()->prepare($query_rsList);
	$rsList->bindParam(':id', $id, PDO::PARAM_INT);
	$rsList->execute();
	$row_rsList = $rsList->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsList = $rsList->rowCount();
	
	$maior = $row_rsList['MAX(id)'];	

	for($i=1;$i<=$maior;$i++) {
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();

    while($row_rsLinguas = $rsLinguas->fetch()) {		
			if(isset($_POST['order_'.$i])) {
				$ordem = $_POST['order_'.$i];

				$insertSQL = "UPDATE paginas_blocos_".$row_rsLinguas['sufixo']." SET ordem=:ordem WHERE id=:id AND pagina =:pagina";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_STR, 5);	
				$rsInsert->bindParam(':id', $i, PDO::PARAM_INT);
				$rsInsert->bindParam(':pagina', $id, PDO::PARAM_INT);
				$rsInsert->execute();
			}
			if(isset($_POST['visivel_'.$i])) { 
				$visivel = $_POST['visivel_'.$i];	
				$insertSQL = "UPDATE paginas_blocos_".$row_rsLinguas['sufixo']." SET visivel=:visivel WHERE id=:id AND pagina =:pagina";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':visivel', $visivel, PDO::PARAM_STR, 5);	
				$rsInsert->bindParam(':id', $i, PDO::PARAM_INT, 5);
				$rsInsert->bindParam(':pagina', $id, PDO::PARAM_INT);
				$rsInsert->execute();	
			}
		}
	}

	alteraSessions('paginas');
	alteraSessions('paginas_menu');
	alteraSessions('paginas_fixas');
	
	$inserido = 1;
}

if(isset($_GET['reg'])) {
	if(isset($_GET['rem']) && $_GET['rem']==1) {
		$projecto=$_GET['reg'];
		
		$query_rsImg = "SELECT imagem1 FROM paginas_blocos_imgs WHERE bloco=:id ORDER BY ordem ASC, id DESC";
		$rsImg = DB::getInstance()->prepare($query_rsImg);
		$rsImg->bindParam(':id', $projecto, PDO::PARAM_INT, 5);
		$rsImg->execute();
		$totalRows_rsImg = $rsImg->rowCount();
		
		if($totalRows_rsImg) {
			while($row_rsImg = $rsImg->fetch()) {
				if ($row_rsImg['imagem1']!='') {
					@unlink('../../../imgs/paginas/'.$row_rsImg['imagem1']);
				}
			}
		}
		
		$insertSQL = "DELETE FROM paginas_blocos_imgs WHERE bloco=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id', $projecto, PDO::PARAM_INT);	
		$rsInsert->execute();
		
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel=1";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();

		while($row_rsLinguas = $rsLinguas->fetch()) {

			$query_rsImg = "SELECT imagem1 FROM paginas_blocos_timeline_".$row_rsLinguas['sufixo']." WHERE bloco=:id ORDER BY ordem ASC, id DESC";
			$rsImg = DB::getInstance()->prepare($query_rsImg);
			$rsImg->bindParam(':id', $projecto, PDO::PARAM_INT, 5);
			$rsImg->execute();
			$totalRows_rsImg = $rsImg->rowCount();
			
			if($totalRows_rsImg) {
				while($row_rsImg = $rsImg->fetch()) {
					if ($row_rsImg['imagem1']!='') {
						@unlink('../../../imgs/paginas/'.$row_rsImg['imagem1']);
					}
				}
			}

			$insertSQL = "DELETE FROM paginas_blocos_timeline_".$row_rsLinguas['sufixo']." WHERE bloco=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $projecto, PDO::PARAM_INT);
			$rsInsert->execute();

			$query_rsImg = "SELECT ficheiro FROM paginas_blocos_ficheiros_".$row_rsLinguas['sufixo']." WHERE bloco=:id ORDER BY ordem ASC, id DESC";
			$rsImg = DB::getInstance()->prepare($query_rsImg);
			$rsImg->bindParam(':id', $projecto, PDO::PARAM_INT, 5);
			$rsImg->execute();
			$totalRows_rsImg = $rsImg->rowCount();
			
			if($totalRows_rsImg) {
				while($row_rsImg = $rsImg->fetch()) {
					if ($row_rsImg['ficheiro']!='') {
						@unlink('../../../imgs/paginas/'.$row_rsImg['ficheiro']);
					}
				}
			}

			$insertSQL = "DELETE FROM paginas_blocos_ficheiros_".$row_rsLinguas['sufixo']." WHERE bloco=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $projecto, PDO::PARAM_INT);
			$rsInsert->execute();

			$insertSQL = "DELETE FROM paginas_blocos_".$row_rsLinguas['sufixo']." WHERE id=:projecto AND pagina=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':projecto', $projecto, PDO::PARAM_INT, 5);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsInsert->execute();
		}

		DB::close();

		alteraSessions('paginas');
		alteraSessions('paginas_menu');
		alteraSessions('paginas_fixas');
		
		header("Location: paginas-blocos.php?pagina=".$id."&r=1&fixo=".$fixo);
	}
}

$query_rsP = "SELECT * FROM paginas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsList = "SELECT * FROM paginas_blocos".$extensao." WHERE pagina =:pagina ORDER BY ordem ASC";
$rsList = DB::getInstance()->prepare($query_rsList);
$rsList->bindParam(':pagina', $id, PDO::PARAM_INT);
$rsList->execute();
$totalRows_rsList = $rsList->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
  #alert_div {
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           
            <li>
                <a href="paginas.php?fixo=<?php echo $fixo; ?>"><?php echo $RecursosCons->RecursosCons['paginas']; ?></a>
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
              <button type="button" class="btn blue" onClick="document.location='paginas.php?rem=1&id=<?php echo $row_rsP["id"]; ?>&fixo=<?php echo $fixo; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
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
		  		<?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="paginas_list_form" name="paginas_list_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['paginas']; ?> - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='paginas.php?fixo=<?php echo $fixo; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <?php if($row_rsP['fixo']!=1) { ?><a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a><?php } ?>
                </div>
              </div>
              <div class="portlet-body">
              	<div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="nav-tab" onClick="window.location='paginas-edit.php?fixo=<?php echo $fixo; ?>&id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a id="tab_1" href="#tab_blocos" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1';"> <?php echo $RecursosCons->RecursosCons['blocos']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='paginas-edit.php?fixo=<?php echo $fixo; ?>&id=<?php echo $id; ?>&tab_sel=3'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_blocos">
                      <div class="form-body">
                      	<?php if(isset($_GET['r']) && $_GET['r'] == 1) { ?>
			                    <div class="alert alert-danger display-show">
			                    <button class="close" data-close="alert"></button>
			                    <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> </div>   
			                	<?php } ?> 
			                	<?php if($inserido==1){ ?>
	                      <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <span>  <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>
	                      </div>
	                      <?php } ?>
	                      <?php if($_GET['env'] == 1 && $inserido == 0) { ?>  
	                        <div class="alert alert-success display-show">
	                        <button class="close" data-close="alert"></button>
	                         <?php echo $RecursosCons->RecursosCons['env']; ?></div>
	                    	<?php } ?>
	                    	<div id="alert_div" class="alert alert-success display-show">
	                        <button class="close" data-close="alert"></button>
	                        <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 
	                      </div>
						  					<a href="paginas-blocos-insert.php?fixo=<?php echo $fixo; ?>&pagina=<?php echo $id; ?>" class="btn blue" style="float:right;"> <?php echo $RecursosCons->RecursosCons['add_blocos']; ?> <i class="fa fa-angle-right"></i></a>
        								<?php if($totalRows_rsList>0) { ?><button type="submit" class="btn green" style="float:right;margin-right:10px;"><i class="fa fa-check"></i>  <?php echo $RecursosCons->RecursosCons['guarda_alt']; ?></button><?php } ?>
            						<?php if($totalRows_rsList>0) { ?>
            							<div class="row">
                						<div class="col-md-12" style="padding-top:30px">
                   			  		<div class="portlet box green">
                        				<div class="portlet-title">
			                            <div class="caption">
		                                <i class="fa fa-list"></i> <?php echo $RecursosCons->RecursosCons['blocos_inseridos']; ?>
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
                                             <?php echo $RecursosCons->RecursosCons['nome']; ?></th>
	                                        <th>
                                             <?php echo $RecursosCons->RecursosCons['tipo_label']; ?>
	                                        </th>
	                                        <th>
                                             <?php echo $RecursosCons->RecursosCons['ordem']; ?>
	                                        </th>
	                                        <th>
                                             <?php echo $RecursosCons->RecursosCons['visivel_tab']; ?>
	                                        </th>
	                                        <th>&nbsp;</th>
                                        </tr>
                                    	</thead>
                                    	<tbody>
		                                    <?php $cont=0; while($row_rsList = $rsList->fetch()) { $cont++;  
																					$tipo = "";
																					if($row_rsList['tipo']==1) $tipo = $RecursosCons->RecursosCons['blocos_sel_textoimg'];
																					if($row_rsList['tipo']==2) $tipo = $RecursosCons->RecursosCons['blocos_sel_texto'];
																					if($row_rsList['tipo']==3) $tipo = $RecursosCons->RecursosCons['blocos_sel_2videos'];
																					if($row_rsList['tipo']==4) $tipo = $RecursosCons->RecursosCons['blocos_sel_google_maps'];
																					if($row_rsList['tipo']==5) $tipo = $RecursosCons->RecursosCons['blocos_sel_formulario'];
																					if($row_rsList['tipo']==6) $tipo = $RecursosCons->RecursosCons['blocos_sel_ficheiros'];
																					if($row_rsList['tipo']==7) $tipo = $RecursosCons->RecursosCons['blocos_sel_timeline'];
																
																					
																					/*if($row_rsList['tipo']==1) $tipo = $RecursosCons->RecursosCons['blocos_sel_texto'];
																					if($row_rsList['tipo']==2) $tipo = $RecursosCons->RecursosCons['blocos_sel_imagem_video'];
																					if($row_rsList['tipo']==3) $tipo = $RecursosCons->RecursosCons['blocos_sel_textoimg'];
																					if($row_rsList['tipo']==4) $tipo = $RecursosCons->RecursosCons['blocos_sel_botao'];
																					if($row_rsList['tipo']==5) $tipo = $RecursosCons->RecursosCons['blocos_sel_formulario'];
																					if($row_rsList['tipo']==6) $tipo = $RecursosCons->RecursosCons['blocos_sel_2videos'];
																					if($row_rsList['tipo']==7) $tipo = $RecursosCons->RecursosCons['blocos_sel_google_maps'];
																					if($row_rsList['tipo']==8) $tipo = $RecursosCons->RecursosCons['blocos_sel_ficheiros'];
																					if($row_rsList['tipo']==9) $tipo = $RecursosCons->RecursosCons['blocos_sel_timeline'];*/
																					?>
		                                    	<tr>
		                                        <td><?php echo $cont; ?></td>
		                                        <td><?php echo $row_rsList['nome'];?></td>
		                                        <td><?php echo $tipo;?></td>
		                                        <td><input type="text" id="order_<?php echo $row_rsList['id'];?>" name="order_<?php echo $row_rsList['id'];?>" class="cx_ordenar" value="<?php echo $row_rsList['ordem'];?>"></td>
		                                        <td><input type="checkbox" <?php if($row_rsList['visivel'] == 1) { echo "checked"; } ?> name="switch" id="switch_<?php echo $row_rsList['id']; ?>" class="make-switch" data-on-color="success" data-on-text="<?php echo $RecursosCons->RecursosCons['text_visivel_sim']; ?>" data-off-color="danger" data-off-text="<?php echo $RecursosCons->RecursosCons['text_visivel_nao']; ?>" data-off-value="0" data-on-value="1"></td>
		                                        <td>
		                                          <a href="paginas-blocos-edit.php?pagina=<?php echo $id; ?>&fixo=<?php echo $fixo; ?>&id=<?php echo $row_rsList['id'];?>" class="btn btn-sm default"><i class="fa fa-edit"></i> <?php echo $RecursosCons->RecursosCons['editar']; ?></a>
		                                          	
		                                          <a href="#modal_delete_<?php echo $row_rsList['id'];?>" data-toggle="modal" class="btn btn-sm red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?> </a>
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
		                                                  <button type="button" class="btn blue" onClick="document.location='paginas-blocos.php?pagina=<?php echo $id; ?>&fixo=<?php echo $fixo; ?>&rem=1&reg=<?php echo $row_rsList['id'];?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
		                                                  <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </button>
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
	                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" id="MM_paginas" value="paginas_list_form" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
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

  $('.bootstrap-switch').click(function() {
    var valor = 0;
    if($(this).hasClass('bootstrap-switch-on')) valor = 1;
    var parts = $(this).find('input').attr('id').split('_');
    var id = parts['1'];
    $.ajax({
      url: 'paginas-rpc.php',
      type: 'POST',
      data: {op: 'bloco_visivel', valor: valor, id: id},
    })
    .done(function(data) {
      $('#alert_div').css('display', 'block');

      setTimeout(function() { $('#alert_div').css('display', 'none'); }, 10000);
    });
  }); 

});
</script> 
<script type="text/javascript">
CKEDITOR.replace('texto', {
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	toolbar : "Basic2"
});
</script>
<script type="text/javascript">
document.ready=carregaPreview();
</script>
</body>
<!-- END BODY -->
</html>