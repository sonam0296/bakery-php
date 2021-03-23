<?php include_once('../inc_pages.php'); ?>
<?php 

$id=$_GET['id'];

$inserido=0;
$erro_password=0;
$tab_sel=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$query_rsP = "SELECT * FROM textos".$extensao." WHERE id=:id AND visivel='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);		
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	
	if($_POST['titulo'] != "") {
	
		$id=$_POST['id'];
		
		$nome_url=strtolower(verifica_nome($_POST['titulo']));
				
		$query_rsProc = "SELECT * FROM textos".$extensao." WHERE url like '$nome_url' AND id!='$id'";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->execute();
		$totalRows_rsProc = $rsProc->rowCount();
		DB::close();
		
		if($totalRows_rsProc>0){
			$nome_url=$nome_url."-".$id;
		}
		
		if($_POST['texto'] == "") $nome_url = "";
		
		$insertSQL = "UPDATE textos".$extensao." SET titulo=:titulo, sub_titulo=:sub_titulo, link=:link, target=:target, texto=:texto, url=:nome_url WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':sub_titulo', $_POST['sub_titulo'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);			
		$rsInsert->execute();
		DB::close();
		
		// actualiza imagem
		if(!$_FILES){ // apaga imagem
		
			@unlink('../../../imgs/uploads/images/'.$row_rsP['imagem1']);
			$imagem='';
			
					
			$insertSQL = "UPDATE textos".$extensao." SET imagem1=:imagem WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 5);	
			$rsInsert->execute();
			DB::close();
			
		}
		
		if($_FILES['img']['name']!=''){ // actualiza imagem
			
			require("../resize_image.php");
			
			$imagem="";		
			
			$imgs_dir = "../../../imgs/uploads/images";
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
			
			$imagem=$nome_file1;
				
			//RESIZE DAS IMAGENS
			
			//IMAGEM 1
			if($imagem!="" && file_exists("../../../imgs/uploads/images/".$imagem)){
								
				$maxW=190;
				$maxH=90;
				
				$sizes=getimagesize("../../../imgs/uploads/images/".$imagem);
				
				$imageW=$sizes[0];
				$imageH=$sizes[1];
				
				if($imageW>$maxW || $imageH>$maxH){
									
					$img1=new Resize("../../../imgs/uploads/images/", $imagem, $imagem, $maxW, $maxH);
					$img1->resize_image();
					
				}
			
			}		
			
			if($row_rsP['imagem1']){
				@unlink('../../../imgs/uploads/images/'.$row_rsP['imagem1']);
			}
					
			$insertSQL = "UPDATE textos".$extensao." SET imagem1=:imagem WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 5);
			$rsInsert->execute();
			DB::close();
		}
		
		$inserido=1;
	
	}
		
}

$query_rsP = "SELECT * FROM textos".$extensao." WHERE id=:id AND visivel='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);		
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$menu_sel='blocos';
$menu_sub_sel='blocos_'.$row_rsP['id'];

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['blocos']; ?> <small><?php echo $RecursosCons->RecursosCons['blocos_content_homepage']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['blocos']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="paginas.php?id=<?php echo $row_rsP['id']; ?>"><?php echo $row_rsP['nome']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <?php if($totalRows_rsP>0){ ?>
          <form method="POST" id="blocos" role="form" enctype="multipart/form-data" class="form-horizontal">
            <input type="hidden" name="id_pagina" id="id_pagina" value="<?php echo $row_rsP["id"]; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-file"></i><?php echo $row_rsP['nome']; ?> </div>
                <div class="actions btn-set">
                  <button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                    <button class="close" data-close="alert"></button>
                     <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="titulo"> <?php echo $RecursosCons->RecursosCons['titulo_homepage_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="sub_titulo"><?php echo $RecursosCons->RecursosCons['sub_titulo_homepage_label']; ?>:</label>
                    <div class="col-md-3">
                      <div style="height:25px;"><strong>Texto</strong></div>
                      <textarea class="form-control" name="sub_titulo" id="sub_titulo" style="resize:none; height:70px"><?php echo $row_rsP['sub_titulo']; ?></textarea>
                    </div>
                    <div class="col-md-4">
                      <div style="height:25px;"><strong><?php echo $RecursosCons->RecursosCons['blocos_img_tam']; ?> </strong></div>
                      <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/uploads/images/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                          <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/uploads/images/".$row_rsP['imagem1'])) { ?>
                          <a href="../../../imgs/uploads/images/<?php echo $row_rsP['imagem1']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/uploads/images/<?php echo $row_rsP['imagem1']; ?>"></a>
                          <?php } ?>
                        </div>
                        <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                          <input id="upload_campo" type="file" name="img">
                          </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                      </div>
                      <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>!</span> <span><?php echo $RecursosCons->RecursosCons['funcionalidade_msg']; ?></span> </div>
                      <script type="text/javascript">
						function alterar_imagem(){
							document.getElementById('file_delete_1').value='';
						}
						function remover_imagem(){
							document.getElementById('file_delete_1').value='';
							document.getElementById('img_cont_1_vazia').style.display='block';									
							document.getElementById('img_cont_1').style.display='none';
						}
					  </script> 
                    </div>
                  </div>
                  <div class="clearfix" style="margin-top:30px; margin-bottom:20px;"> <div class="col-md-2"></div>
                    <div class="col-md-6" style="color:#EB2D30"><strong><?php echo $RecursosCons->RecursosCons['bloco_link']; ?></strong></div> </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">
                    </div>
                    <label class="col-md-1 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>
                    <div class="col-md-2">
                      <select class="form-control" name="target" id="target">
                      	<option value="0"></option>
                      	<option value="_blank" <?php if($row_rsP["target"] == "_blank") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>
                      	<option value="_parent" <?php if($row_rsP["target"] == "_parent") { ?>selected<?php } ?>><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['conteudo_label']; ?>:</label>
                    <div class="col-md-10">
                      <?php if($row_rsP['texto'] != "") { ?><div style="padding-bottom:10px; color:#EB2D30"><strong>URL: <?php echo "http://suez/trabalhos/agrams/site/".$row_rsP['url']; ?></strong></div><?php } ?>
                      <textarea class="form-control" name="texto" id="texto" style="resize:none;height:250px"><?php echo $row_rsP['texto']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
            <input type="hidden" name="MM_insert" value="alterar" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   FormValidation.init(); // init quick sidebar
});
</script> 
<script type="text/javascript">
CKEDITOR.replace('texto',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	height: '600px',
	toolbar : "Full"
	
});
</script>
</body>
<!-- END BODY -->
</html>