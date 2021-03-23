<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='configuracao';
$menu_sub_sel='imagem';

$id = 1;
$erro = 0;

$tamanho_imagens1 = getFillSize('Imagens Topo', 'imagem1');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_imagem")) {
	$manter = $_POST['manter'];
	$tab_sel = $_REQUEST['tab_sel'];
	
	$query_rsP = "SELECT * FROM imagens_topo WHERE id=:id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();

	$mascara = 0;
  if(isset($_POST['contactos_masc'])) {
    $mascara = 1;
  }

  $insertSQL = "UPDATE imagens_topo SET contactos_masc=:mascara WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':mascara', $mascara, PDO::PARAM_INT);	
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsInsert->execute();
	
	$rem = 0;
	$imagem = $row_rsP['contactos'];

	if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
		$insertSQL = "UPDATE imagens_topo SET contactos=NULL WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsInsert->execute();
		
		@unlink('../../../imgs/imagens_topo/'.$imagem);

		$rem = 1;
	}
	
	$ins = 0;

	if($_FILES['img']['name']!='') { // actualiza imagem
		//Verificar o formato do ficheiro
		$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

		if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png") {
			$erro = 1;
		}
		else {
			$ins = 1;	
			require("../resize_image.php");
			
			$imagem="";		
			
			$imgs_dir = "../../../imgs/imagens_topo";
			$contaimg = 1; 
	
			foreach($_FILES as $file_name => $file_array) {
		
				$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
				
				switch ($contaimg) {
					case '1': case '2': case '3':    
						$file_dir =  $imgs_dir;
					break;
				}
				
	
				if($file_array['size'] > 0){
						$nome_img=verifica_nome($file_array['name']);
						$nome_file = $id_file."_".$nome_img;
						@unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);
				}else {
						//$nome_file = $_POST['file_db_'.$contaimg];
	
					if($_POST['file_db_'.$contaimg])
						$nome_file = $_POST['file_db_'.$contaimg];
					else{
						$nome_file ='';
						@unlink($file_dir.'/'.$_POST['file_db_del_'.$contaimg]);
						}
	
				}
						
				if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); }
	
				//store the name plus index as a string 
				$variableName = 'nome_file' . $contaimg; 
				//the double dollar sign is saying assign $imageName 
				// to the variable that has the name that is in $variableName
				$$variableName = $nome_file; 	
				$contaimg++;
														
			} // fim foreach
			//Fim do Trat. Imagens
				
			//RESIZE DAS IMAGENS
			$imagem = $nome_file1;
		
			//IMAGEM 1
			if($_FILES['img']['name']!='') {
				if($imagem!="" && file_exists("../../../imgs/imagens_topo/".$imagem)){
									
					$maxW=$tamanho_imagens1['0'];
					$maxH=$tamanho_imagens1['1'];
					
					$sizes=getimagesize("../../../imgs/imagens_topo/".$imagem);
					
					$imageW=$sizes[0];
					$imageH=$sizes[1];
					
					if($imageW>$maxW || $imageH>$maxH) {							
						$img1=new Resize("../../../imgs/imagens_topo/", $imagem, $imagem, $maxW, $maxH);
						$img1->resize_image();
					}
				}		
				
				if($row_rsP['contactos']) { 
					@unlink('../../../imgs/imagens_topo/'.$row_rsP['contactos']);
				}

				compressImage('../../../imgs/imagens_topo/'.$imagem, '../../../imgs/imagens_topo/'.$imagem);

				$insertSQL = "UPDATE imagens_topo SET contactos=:contactos WHERE id=:id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':contactos', $imagem, PDO::PARAM_STR, 5);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
				$rsInsert->execute();
			}
		}
	}

	DB::close();

	alteraSessions('contactos');
	
	if($erro == 1)
		header("Location: contactos-imagem.php?erro=1");
	else {
		if($rem == 1 && $ins == 0)
			header("Location: contactos-imagem.php?rem=1");
		else
			header("Location: contactos-imagem.php?alt=1");
	}
}

$query_rsP = "SELECT * FROM imagens_topo WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
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
      <h3 class="page-title">
			Contactos <small>preencha com os seus dados</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:void(null)">Configuração</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="contactos.php">Contactos</a>
					</li>
				</ul>
			</div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_imagem" name="frm_imagem" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <input type="hidden" name="img_remover2" id="img_remover2" value="0">
            <input type="hidden" name="opcao" id="opcao" value="1">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i>Contactos - Banner </div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
	                <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> Guardar</button>
	              </div>
	            </div>
	            <div class="portlet-body">
                <div class="form-body">

                	<?php if($_GET['alt'] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['img_alt']; ?>
                    </div>
                  <?php } ?>
                  <?php if($_GET['rem'] == 1) { ?>
                    <div class="alert alert-danger display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['img_rem']; ?> 
                    </div>
                  <?php } ?>
                  <?php if($_GET['erro'] == 1) { ?>
                    <div class="alert alert-danger display-show">
                    	<button class="close" data-close="alert"></button>
                    	<?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem_topo']; ?><br>
                      <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
                		<div class="col-md-3">
                      <div class="fileinput fileinput-<?php if($row_rsP['contactos']!="" && file_exists("../../../imgs/imagens_topo/".$row_rsP['contactos'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                          <?php if($row_rsP['contactos']!="" && file_exists("../../../imgs/imagens_topo/".$row_rsP['contactos'])) { ?>
                          <a href="../../../imgs/imagens_topo/<?php echo $row_rsP['contactos']; ?>" data-fancybox><img src="../../../imgs/imagens_topo/<?php echo $row_rsP['contactos']; ?>"></a>
                          <?php } ?>
                        </div>
                        <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                          <input id="upload_campo2" type="file" name="img">
                          </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                      </div>
                      <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span> </div>
                    </div>
                    <label class="col-md-1 control-label" for="contactos_masc" style="padding-top:0;"> <?php echo $RecursosCons->RecursosCons['tem_mascara_label']; ?></label>
                    <div class="col-md-4">
                      <input type="checkbox" class="form-control" name="contactos_masc" id="contactos_masc" value="1" <?php if($row_rsP['contactos_masc'] == 1) echo "checked";?>>
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['sel_mascara_msg']; ?></p>
                    </div>
                  </div>
                </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_imagem" />
          </form>
        </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script>  
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});
</script> 
</body>
<!-- END BODY -->
</html>