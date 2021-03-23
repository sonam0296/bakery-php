<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='blocos';
$menu_sub_sel='galerias';

$id = $_GET['id'];

$query_rsP = "SELECT * FROM galerias_pt WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 5);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();	

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "galerias")) {
	
	
	if($_POST['nome']!=''){
		
		$insertSQL = "SELECT MAX(id) FROM galerias_conteudo";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		DB::close();
		
		$max_id = $row_rsInsert["MAX(id)"]+1;
		
		$insertSQL = "INSERT INTO galerias_conteudo (id, id_galeria, nome, link) VALUES (:id, :id_galeria, :nome, :link)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST["nome"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':link', $_POST["link"], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT, 5);	
		$rsInsert->bindParam(':id_galeria', $_GET["id"], PDO::PARAM_INT, 5);	
		$rsInsert->execute();
		DB::close();
		
		if($_FILES['img']['name']!=''){ // actualiza imagem
			
			require("../resize_image.php");
			
			$imagem="";		
			
			$imgs_dir = "../../../imgs/galerias";
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
			if($imagem!="" && file_exists("../../../imgs/galerias/".$imagem)){
								
				$maxW=1200;
				$maxH=700;
				
				$sizes=getimagesize("../../../imgs/galerias/".$imagem);
				
				$imageW=$sizes[0];
				$imageH=$sizes[1];
				
				if($imageW>$maxW || $imageH>$maxH){
									
					$img1=new Resize("../../../imgs/galerias/", $imagem, $imagem, $maxW, $maxH);
					$img1->resize_image();
					
				}
			
			}
					
			$insertSQL = "UPDATE galerias_conteudo SET imagem1=:imagem WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT, 5);
			$rsInsert->execute();
			DB::close();
		}
		
		header("Location: galerias-conteudo.php?id=".$_GET['id']."&ins=1");
	}
	
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['page_title_galerias']; ?> » <?php echo $row_rsP["nome"]; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="galerias" name="galerias" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['novo_registo']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='galerias-conteudo.php?id=<?php echo $_GET['id']; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i><?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <?php if($_GET["id"] == 1) { ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_video_label']; ?>: </label>
                    <div class="col-md-6">
                      <textarea class="form-control" name="link" id="link"><?php echo stripslashes($_POST['link']); ?></textarea>
                    </div>
                  </div>
                  <?php } else { ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="img"><?php echo $RecursosCons->RecursosCons['img_galeria_tam_recomendado']; ?></label>
                    <div class="col-md-6">
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                        <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                          <input id="upload_campo" type="file" name="img">
                          </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                      </div>
                      <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['nota_txt']; ?> !</span> <span><?php echo $RecursosCons->RecursosCons['funcionalidade_msg']; ?></span> </div>
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
                  <?php } ?>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="galerias" />
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