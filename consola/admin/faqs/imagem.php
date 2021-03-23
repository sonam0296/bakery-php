<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='faqs';
$menu_sub_sel='imagem';

$id = 1;
$inserido = 0;
$erro = 0;

$tamanho_imagens1 = getFillSize('Imagens Topo', 'imagem1');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_faqs")) {
	$query_rsP = "SELECT * FROM imagens_topo WHERE id='1'";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();

	$mascara = 0;
  if(isset($_POST['faqs_masc'])) {
    $mascara = 1;
  }

  $insertSQL = "UPDATE imagens_topo SET faqs_masc=:mascara WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':mascara', $mascara, PDO::PARAM_INT);	
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsInsert->execute();

	$imagem = $row_rsP['faqs'];

	if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
		$insertSQL = "UPDATE imagens_topo SET faqs=NULL WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->execute();
		
		@unlink('../../../imgs/imagens_topo/'.$imagem);

	}

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
				
	
				if($file_array['size'] > 0) {
						$nome_img=verifica_nome($file_array['name']);
						$nome_file = $id_file."_".$nome_img;
						@unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);
				}
				else {
					if($_POST['file_db_'.$contaimg])
						$nome_file = $_POST['file_db_'.$contaimg];
					else {
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
					
					if($imageW>$maxW || $imageH>$maxH){								
						$img1=new Resize("../../../imgs/imagens_topo/", $imagem, $imagem, $maxW, $maxH);
						$img1->resize_image();
					}
				}		
				
				if($row_rsP['faqs']){
					@unlink('../../../imgs/imagens_topo/'.$row_rsP['faqs']);
				}

				$insertSQL = "UPDATE imagens_topo SET faqs=:faqs WHERE id=:id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':faqs', $imagem, PDO::PARAM_STR, 5);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
				$rsInsert->execute();
			}

			$inserido=1;
		}
	}
	else {
		$inserido=1;
	}

	DB::close();

	alteraSessions('faqs');
}

$query_rsP = "SELECT * FROM imagens_topo WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['faqs']; ?> <small><?php echo $RecursosCons->RecursosCons['tab_imagem']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?> </a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['faqs']; ?> <i class="fa fa-angle-right"></i> </a></li>
          <li> <a href="imagem.php"><?php echo $RecursosCons->RecursosCons['tab_imagem']; ?></a> </li>
        </ul>
      </div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <?php if($totalRows_rsP > 0) { ?>
            <form id="frm_faqs" name="frm_faqs" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            	<input type="hidden" name="img_remover1" id="img_remover1" value="0">
							<div class="portlet">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['faqs']; ?> - <?php echo $RecursosCons->RecursosCons['tab_imagem']; ?>
									</div>
									<div class="actions btn-set">
										<button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>						
									</div>
								</div>
								<div class="portlet-body"> 
                  <div class="form-body">
                    <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                      <button class="close" data-close="alert"></button>
                       <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
                    </div>
                    <?php if($erro == 1) { ?>
	                    <div class="alert alert-danger display-show">
		                    <button class="close" data-close="alert"></button>
		                    <?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> 
		                  </div>   
	                	<?php } ?> 
                    <div class="form-group">
                      <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem_topo']; ?><br>
                        <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
                      <div class="col-md-3">
                        <div class="fileinput fileinput-<?php if($row_rsP['faqs']!="" && file_exists("../../../imgs/imagens_topo/".$row_rsP['faqs'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                          <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                          <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                            <?php if($row_rsP['faqs']!="" && file_exists("../../../imgs/imagens_topo/".$row_rsP['faqs'])) { ?>
                            <a href="../../../imgs/imagens_topo/<?php echo $row_rsP['faqs']; ?>" data-fancybox><img src="../../../imgs/imagens_topo/<?php echo $row_rsP['faqs']; ?>"></a>
                            <?php } ?>
                          </div>
                          <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                            <input id="upload_campo" type="file" name="img">
                            </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                        </div>
                        <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span> </div>
                        <script type="text/javascript">
                        function alterar_imagem(){
                            document.getElementById('file_delete_1').value='';
                        }
                        function remover_imagem(){
                            document.getElementById('file_delete_1').value='';
                            document.getElementById('img_cont_1_vazia').style.display='block';									
                            document.getElementById('img_cont_1').style.display='none';
                        }
                        </script><br><br>
                      </div>
                      <label class="col-md-1 control-label" for="faqs_masc" style="padding-top:0;"> <?php echo $RecursosCons->RecursosCons['tem_mascara_label']; ?></label>
	                    <div class="col-md-4">
	                      <input type="checkbox" class="form-control" name="faqs_masc" id="faqs_masc" value="1" <?php if($row_rsP['faqs_masc'] == 1) echo "checked";?>>
	                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['sel_mascara_msg']; ?></p>
	                    </div>
                    </div>
                  </div>		
								</div>
							</div>
            	<input type="hidden" name="MM_insert" value="frm_faqs" />
						</form>
          <?php } ?>    
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	</div>
	<!-- END CONTENT -->
    <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init();
});
</script>
</body>
<!-- END BODY -->
</html>