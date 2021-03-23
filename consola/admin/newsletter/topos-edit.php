<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_topos';
$menu_sub_sel='';

$tab_sel=1;
if(isset($_GET['tab_sel']) && $_GET['tab_sel'] != "" && $_GET['tab_sel'] != 0) $tab_sel=$_GET['tab_sel'];

$id = $_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_topos")) {
	$manter = $_POST['manter'];
	$tab_sel = $_REQUEST['tab_sel'];
	
	$query_rsP = "SELECT * FROM news_topos WHERE id = :id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();
	
	if($_POST['nome']!='' && $tab_sel == 1) {
		$insertSQL = "UPDATE news_topos SET nome=:nome, link=:link WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->execute();
		DB::close();
		
		if(!$manter) 
			header("Location: topos.php?alt=1");
		else 
			header("Location: topos-edit.php?id=".$id."&alt=1&tab_sel=1");
	}
	
	if($tab_sel==2) {
		$rem = 0;
		$opcao = $_POST['opcao'];
		$imagem = $row_rsP['imagem1'];

		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
			@unlink('../../../imgs/imgs_news/'.$imagem);

			$insertSQL = "UPDATE news_topos SET imagem1=NULL WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
			$rsInsert->execute();
			DB::close();

			$rem = 1;
		}
		
		$ins = 0;

		if($_FILES['img']['name']!=''){ // actualiza imagem
			//Verificar o formato do ficheiro
			$ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

			if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png") {
				$erro = 1;
			}
			else {
				$ins = 1;	
				require("../resize_image.php");
				
				$imagem="";		
				
				$imgs_dir = "../../../imgs/imgs_news";
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
					if($imagem!="" && file_exists("../../../imgs/imgs_news/".$imagem)){
										
						$maxW=600;
						$maxH=2000;
						
						$sizes=getimagesize("../../../imgs/imgs_news/".$imagem);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
											
							$img1=new Resize("../../../imgs/imgs_news/", $imagem, $imagem, $maxW, $maxH);
							$img1->resize_image();
							
						}
					
					}		
					
					if($row_rsP['imagem1']){
						@unlink('../../../imgs/imgs_news/'.$row_rsP['imagem1']);
					}

					$insertSQL = "UPDATE news_topos SET imagem1=:imagem1 WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
					$rsInsert->execute();
					DB::close();
				}
			}
		}
		
		if($erro == 1)
			header("Location: topos-edit.php?id=".$id."&erro=1&tab_sel=2");
		else {
			if(!$manter) 
				header("Location: topos.php?alt=1");
			else {
				if($rem == 1 && $ins == 0)
					header("Location: topos-edit.php?id=".$id."&rem=1&tab_sel=2");
				else
					header("Location: topos-edit.php?id=".$id."&alt=1&tab_sel=2");
			}
		}
	}
}

$query_rsP = "SELECT * FROM news_topos WHERE id = :id";
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
      <h3 class="page-title"> Newsletter <small>editar topo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:void(null)">Newsletters</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="topos.php">Topos</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:">Editar topo</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title">Eliminar registo</h4>
            </div>
            <div class="modal-body"> Deseja eliminar o registo <?php echo $row_rsP["nome"]; ?>? </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='topos.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'">Ok</button>
              <button type="button" class="btn default" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_topos" name="frm_topos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - Topos - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='topos.php'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> Guardar e manter na página</button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> Eliminar</a> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> Detalhe </a> </li>
                    <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> Imagem </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">
                      <div class="form-actions actions btn-set margin-bottom-20" align="right"> </div>
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        Preencha todos os campos obrigatórios. 
                      </div>    
                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>
                        	 <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          Dados alterados com sucesso. 
	                        </div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nome">Nome: <span class="required"> * </span> </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                          </div>
                        </div>
                        <div class="form-group">
			                    <label class="col-md-2 control-label" for="link">Link: </label>
			                    <div class="col-md-8">
			                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $row_rsP['link']; ?>">
			                    </div>
			                  </div>
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">
                      <div class="form-body">
                      	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          Imagem alterada com sucesso. 
	                        </div>
                        <?php } ?>
                        <div class="alert alert-success<?php if($_GET['v'] != 2 || ($_REQUEST['tab_sel'] != 2)) echo " display-hide"; ?>">
                          <button class="close" data-close="alert"></button>
                          Registo inserido com sucesso. 
                        </div>
                        <?php if($_GET['rem'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-danger display-show">
	                          <button class="close" data-close="alert"></button>
	                          Imagem eliminada com sucesso. 
	                        </div>
                        <?php } ?>
                        <?php if(isset($_GET['erro']) && $_GET['erro']==1) { ?>
			                    <div class="alert alert-danger display-show">
				                    <button class="close" data-close="alert"></button>
				                    Tipo de ficheiro não é permitido. 
				                  </div>   
			                	<?php } ?> 
                        <div class="form-group">
                          <label class="col-md-2 control-label" style="text-align:right">Imagem<br>
                            <strong>Largura Máxima: 600 px:</strong> </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/imgs_news/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=sem+imagem" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/imgs_news/".$row_rsP['imagem1'])) { ?>
                                	<a href="../../../imgs/imgs_news/<?php echo $row_rsP['imagem1']; ?>" data-fancybox="gallery"><img src="../../../imgs/imgs_news/<?php echo $row_rsP['imagem1']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> Selecionar Imagem</span> <span class="fileinput-exists"> Alterar </span>
                                <input id="upload_campo" type="file" name="img">
                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> Remover </a> </div>
                            </div>
                            <div class="clearfix margin-top-10"> <span class="label label-danger">Nota!</span> <span>Esta funcionalidade somente é permitida nas últimas versões do seu browser.</span> </div>
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
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_topos" />
          </form>
        </div>
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