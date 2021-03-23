<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='homepage';
$menu_sub_sel='imagem_news';

$tab_sel=1;
if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];

$id = 1;
$erro = 0;

$tamanho_imagens1 = getFillSize('Homepage', 'imagem1');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_imagem_news")) {
	$manter = $_POST['manter'];

	if(isset($_POST['descricao'])){
		$queryDesc = "UPDATE homepage".$extensao." SET texto_news = :descricao WHERE id=:id";
		$rsDesc = DB::getInstance()->prepare($queryDesc);
		$rsDesc->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsDesc->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);	
		$rsDesc->execute();
	}
	
	$query_rsP = "SELECT imagem_news FROM homepage".$extensao." WHERE id=:id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();

	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$row_rsLinguas = $rsLinguas->fetchAll(PDO::FETCH_ASSOC);
	$totalRows_rsLinguas = $rsLinguas->rowCount();

	$rem = 0;
	$opcao = $_POST['opcao'];
	$imagem = $row_rsP['imagem_news'];

	if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
		$array_imagens = array();
    $array_imagens['imagem_news'] = $imagem2;
    apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao);
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
			
			$imgs_dir = "../../../imgs/homepage";
			$contaimg = 1; 
	
			foreach($_FILES as $file_name => $file_array) {
		
				$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);

				if($file_array['size'] > 0){
						$nome_img=verifica_nome($file_array['name']);
						$nome_file = $id_file."_".$nome_img;
						@unlink($imgs_dir.'/'.$_POST['file_db_'.$contaimg]);
				}else {
						//$nome_file = $_POST['file_db_'.$contaimg];
	
					if($_POST['file_db_'.$contaimg])
						$nome_file = $_POST['file_db_'.$contaimg];
					else{
						$nome_file ='';
						@unlink($imgs_dir.'/'.$_POST['file_db_del_'.$contaimg]);
						}
	
				}
						
				if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$imgs_dir/$nome_file") or die ("Couldn't copy"); }
	
				//store the name plus index as a string 
				$variableName = 'nome_file' . $contaimg; 
				//the double dollar sign is saying assign $imageName 
				// to the variable that has the name that is in $variableName
				$$variableName = $nome_file; 	
				$contaimg++;
														
			} // fim foreach
			//Fim do Trat. Imagens
				
			//RESIZE DAS IMAGENS
			$imagem = $nome_file;

			//IMAGEM 1
			if($_FILES['img']['name']!='') {
				if($imagem!="" && file_exists("../../../imgs/homepage/".$imagem)){										
					$maxW=$tamanho_imagens1['0'];
					$maxH=$tamanho_imagens1['1'];
					
					$sizes=getimagesize("../../../imgs/homepage/".$imagem);
					
					$imageW=$sizes[0];
					$imageH=$sizes[1];
					
					if($imageW>$maxW || $imageH>$maxH){
						$img1=new Resize("../../../imgs/homepage/", $imagem, $imagem, $maxW, $maxH);
						$img1->resize_image();
					}
				}		
				
				if($row_rsP['imagem_news']) {
					$array_imagens = array();
		      $array_imagens['imagem_news'] = $row_rsP['imagem_news'];
		      apagaFicheiros('homepage', 'homepage', $id, $extensao, $array_imagens, $opcao);
				}

				compressImage('../../../imgs/homepage/'.$imagem, '../../../imgs/homepage/'.$imagem);
				
				//Inserir apenas na língua atual
				if($opcao == 1) {
					$insertSQL = "UPDATE homepage".$extensao." SET imagem_news=:imagem_news WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':imagem_news', $imagem, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
					$rsInsert->execute();
				}
				//Inserir para todas as línguas
				else if($opcao == 2) {
					foreach ($row_rsLinguas as $linguas) {		
						$insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET imagem_news=:imagem_news WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem_news', $imagem, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);		
						$rsInsert->execute();
						
					}
				}
			}
		}
	}

	DB::close();
	
	header("Location: imagem-news.php?alt=1");			
	
}

$query_rsP = "SELECT imagem_news, texto_news FROM homepage".$extensao." WHERE id = :id";
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
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jcrop/css/jquery.Jcrop.css" rel="stylesheet"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_imgs_news']; ?> <small><?php echo $RecursosCons->RecursosCons['menu_homepage']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_homepage']; ?> </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['menu_imgs_news']; ?> </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="imagem-news.php"><?php echo $RecursosCons->RecursosCons['editar_conteudo']; ?> </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_imagem_news" name="frm_imagem_news" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_imgs_news']; ?> </div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
	              </div>
	            </div>
	            <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?>
                  </div>
                  <?php if($_GET['alt'] == 1) { ?>
                  	<div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                    	<?php echo $RecursosCons->RecursosCons['img_alt']; ?> 
                   	</div>
                  <?php } ?>
									
									<div class="form-group">
										<label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['texto_label']; ?></label>
										<div class="col-md-8">
											<textarea class="form-control" id="descricao" name="descricao"><?php echo $row_rsP['texto_news']; ?></textarea>
										</div>
									</div>
								
									<div class="form-group">
                    <label class="col-md-2 control-label" style="text-align:right"> <?php echo $RecursosCons->RecursosCons['imagem']; ?><br>
                      <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
                    <div class="col-md-4">
                      <div class="fileinput fileinput-<?php if($row_rsP['imagem_news']!="" && file_exists("../../../imgs/homepage/".$row_rsP['imagem_news'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                          <?php if($row_rsP['imagem_news']!="" && file_exists("../../../imgs/homepage/".$row_rsP['imagem_news'])) { ?>
                          <a href="../../../imgs/homepage/<?php echo $row_rsP['imagem_news']; ?>" data-fancybox ><img src="../../../imgs/homepage/<?php echo $row_rsP['imagem_news']; ?>"></a>
                          <?php } ?>
                        </div>
                        <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                          <input id="upload_campo" type="file" name="img">
                          </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                      </div>
                      <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
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
                    <label class="col-md-2 control-label" for="opcao"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>
                    <div class="col-md-4">
                    	<div style="margin-top: 8px" class="md-radio-list">
												<div class="md-radio">
													<input type="radio" id="opcao1" name="opcao" value="1" class="md-radiobtn" checked>
													<label for="opcao1">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													<?php echo $RecursosCons->RecursosCons['lingua_atual']; ?> </label>
												</div>
												<div class="md-radio">
													<input type="radio" id="opcao2" name="opcao" value="2" class="md-radiobtn">
													<label for="opcao2">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													<?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>
												</div>
											</div>
                    </div>
                  </div>
                </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_imagem_news" />
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
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>  
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/dropzone.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jcrop/js/jquery.color.js"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});
</script>
<script type="text/javascript">
CKEDITOR.replace('descricao',
{
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "250px"
});
</script>
</body>
<!-- END BODY -->
</html>