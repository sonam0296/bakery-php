<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='utilizadores';
$menu_sub_sel='';
$tab_sel=1;
if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];


$id = $_GET['id'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_user")) {
	$manter = $_POST['manter'];
	
	$password=$_POST['password'];
	$password_rep=$_POST['rep_password'];
	
	$erro_preencha_username = 0;
	$erro_preencha_password = 0;
	$erro_password=0;
	$erro_email = 0;
	$erro_username = 0;
	
	$query_rsP = "SELECT * FROM acesso WHERE id = :id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();
	
	if($password!='' && $password!=$password_rep) {
		$erro_password=1;
	}
	
	if(!$row_rsP["password"] && $password=='') {
		$erro_preencha_password=1;
	}
	
	if(!$erro_password && !$erro_preencha_password) {
		
		if($_POST['username']=="") {
			$erro_preencha_username=1;
		} else {
	
			$query_rsExiste = "SELECT * FROM acesso WHERE email=:email AND id != :id";
			$rsExiste = DB::getInstance()->prepare($query_rsExiste);
			$rsExiste->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);
			$rsExiste->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
			$rsExiste->execute();
			$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsExiste = $rsExiste->rowCount();
			DB::close();
			
			$query_rsExiste2 = "SELECT * FROM acesso WHERE username=:username AND id != :id";
			$rsExiste2 = DB::getInstance()->prepare($query_rsExiste2);
			$rsExiste2->bindParam(':username', $_POST['username'], PDO::PARAM_STR, 5);
			$rsExiste2->bindParam(':id', $_GET['id'], PDO::PARAM_INT);		
			$rsExiste2->execute();
			$row_rsExiste2 = $rsExiste2->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsExiste2 = $rsExiste2->rowCount();
			DB::close();
			
			if($totalRows_rsExiste > 0) {
				$erro_email = 1;
			} elseif($totalRows_rsExiste2 > 0) {
				$erro_username = 1;
			} else {
				
				if($_POST['nome']!='' && $_POST['email']!='' && $_POST['username']!=''){
					
					// actualiza detalhes
					$insertSQL = "UPDATE acesso SET nome=:nome, email=:email, telefone=:telefone, funcao=:funcao, observacoes=:observacoes, lingua=:lingua WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
					$rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);	
					$rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5);	
					$rsInsert->bindParam(':funcao', $_POST['funcao'], PDO::PARAM_STR, 5);		
					$rsInsert->bindParam(':observacoes', $_POST['observacoes'], PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':lingua', $_POST['idioma_backoffice'], PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
					$rsInsert->execute();
					DB::close();
					
					// actualiza imagem
					if(!$_FILES){ // apaga imagem
					
						@unlink('../../imgs/user/'.$row_rsP['imagem1']);
						$imagem='';
						
								
						$insertSQL = "UPDATE acesso SET imagem1=:imagem1 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
						$rsInsert->execute();
						DB::close();
					}
					
					if($_FILES['img']['name']!=''){ // actualiza imagem
						
						require("../resize_image.php");
						
						$imagem="";		
						
						$imgs_dir = "../../imgs/user";
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
						if($imagem!="" && file_exists("../../imgs/user/".$imagem)){
											
							$maxW=400;
							$maxH=400;
							
							$sizes=getimagesize("../../imgs/user/".$imagem);
							
							$imageW=$sizes[0];
							$imageH=$sizes[1];
							
							if($imageW>$maxW || $imageH>$maxH){
												
								$img1=new Thumb("../../imgs/user/", $imagem, $imagem, $maxW, $maxH);
								$img1->thumb_image();
								
							}
						
						}		
						
						if($row_rsP['imagem1']){
							@unlink('../../imgs/user/'.$row_rsP['imagem1']);
						}
								
						$insertSQL = "UPDATE acesso SET imagem1=:imagem1 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);	
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
						$rsInsert->execute();
						DB::close();
					}
					
					// actualiza dados de acesso
					$insertSQL = "UPDATE acesso SET username=:username WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':username', $_POST['username'], PDO::PARAM_STR, 5);	
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
					$rsInsert->execute();
					DB::close();
					
					if($password!=''){
						if($password==$password_rep){
							
							$salt = createSalt();				
							$hash = hash('sha256', $password);
							
							$password_final=hash('sha256', $salt . $hash);
							
							$insertSQL = "UPDATE acesso SET password=:password, password_salt = '$salt' WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':password', $password_final, PDO::PARAM_STR, 5);	
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
							$rsInsert->execute();
							DB::close();
							
						}
					
					}
					
					header("Location: utilizadores-edit.php?id=".$id."&v=1&tab_sel=".$_POST['tab_sel']);
				}
				
				if(!$manter) header("Location: utilizadores.php");
				
			}
			
		}
	}
}

$query_rsP = "SELECT * FROM acesso WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
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
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['utilizadores']; ?> <small><?php echo $RecursosCons->RecursosCons['criar_alterar_user']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <i class="fa fa-user"></i> <a href="utilizadores.php"><?php echo $RecursosCons->RecursosCons['utilizadores']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="#"><?php echo $RecursosCons->RecursosCons['alterar_user']; ?></a> </li>
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
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='utilizadores.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_user" name="frm_user" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="1">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-user"></i><?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='utilizadores.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="button" class="btn green" onClick="document.frm_user.submit();"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1'"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?> </button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?> </a> </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?>  </a> </li>
                    <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?>  </a> </li>
                    <li <?php if($tab_sel==3) echo "class=\"active\""; ?>> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['dados_acesso']; ?>  </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['error_form']; ?>  </div>
                        <div class="alert alert-success<?php if(!isset($_GET['v']) || ($_REQUEST['tab_sel'] != 1)) echo " display-hide"; ?>">
                          <button class="close" data-close="alert"></button>
                          Dados alterados com sucesso. </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?> : <span class="required"> * </span> </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="email"><?php echo $RecursosCons->RecursosCons['cli_email']; ?> : <span class="required"> * </span> </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $row_rsP['email']; ?>" data-required="1">
                          </div>
                        </div>
                        <div class="form-group">
			                    <label class="col-md-2 control-label" for="idioma_backoffice"><?php echo $RecursosCons->RecursosCons['idioma_backoffice']; ?>:</label>
			                    <div class="col-md-3">
			                      <select class="form-control select2me" id="idioma_backoffice" name="idioma_backoffice" >
			                        <?php if($consolaLG_count > 0){ ?>
			                          <?php foreach ($row_rsconsolaLG as $value) { ?>
			                             <option value="<?php echo $value['sufixo']; ?>" <?php if($value['sufixo']== $row_rsP['lingua']) echo "selected"; ?>><?php echo $value['nome']; ?></option>
			                           <?php } ?>
			                        <?php } ?>
			                      </select>
			                    </div>
			                  </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="telefone"><?php echo $RecursosCons->RecursosCons['cli_telefone']; ?> : </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo $row_rsP['telefone']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="funcao"><?php echo $RecursosCons->RecursosCons['funcao_label']; ?> : </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="funcao" id="funcao" value="<?php echo $row_rsP['funcao']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="observacoes"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?> : </label>
                          <div class="col-md-6">
                            <textarea class="form-control" rows="3" id="observacoes" name="observacoes"><?php echo $row_rsP['observacoes']; ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">
                      <div class="form-body">
                        <div class="alert alert-success<?php if(!isset($_GET['v']) || $_GET['v'] != 1 || ($_REQUEST['tab_sel'] != 2)) echo " display-hide"; ?>">
                          <button class="close" data-close="alert"></button>
                         <?php echo $RecursosCons->RecursosCons['img_alt']; ?>  </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label">&nbsp;</label>
                          <div class="col-md-6">
                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../imgs/user/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                <?php if($row_rsP['imagem1']!="" && file_exists("../../imgs/user/".$row_rsP['imagem1'])) { ?>
                                <a href="../../imgs/user/<?php echo $row_rsP['imagem1']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../imgs/user/<?php echo $row_rsP['imagem1']; ?>"></a>
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
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_dados">
                      <div class="form-body">
                        <?php if($erro_password == 1) { ?>
                        <div class="alert alert-danger display-block">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['pass_error']; ?> </div>
                        <?php } ?>
                        <?php if($_GET['v'] == 2 && $_REQUEST['tab_sel'] == 3) { ?>
                        <div class="alert alert-success display-show">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['user_inserido_suc']; ?> </div>
                          <?php } ?>
                        <?php if($_GET['v'] == 1 && $_REQUEST['tab_sel'] == 3) { ?>
                        <div class="alert alert-success display-block">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['dados_acesso_alt']; ?> </div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="username"><?php echo $RecursosCons->RecursosCons['username_label']; ?>: <span class="required"> * </span> </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="username" id="username" value="<?php echo $row_rsP['username']; ?>" data-required="1">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="password"><?php echo $RecursosCons->RecursosCons['cli_password']; ?>: </label>
                          <div class="col-md-6">
                            <input type="password" class="form-control" name="password" id="password" value="<?php echo $_POST['password']; ?>"><div style="font-size:13px"><?php echo $RecursosCons->RecursosCons['pass_manter_msg']; ?></div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="rep_password"><?php echo $RecursosCons->RecursosCons['repetir_pass_label']; ?>: </label>
                          <div class="col-md-6">
                            <input type="password" class="form-control" name="rep_password" id="rep_password" value="<?php echo $_POST['rep_password']; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_user" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="utilizadores-validation.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   User.init();
});
</script>
<?php if($erro_preencha_username==1 || $erro_preencha_password==1 || $erro_email == 1 || $erro_username == 1) { ?>
<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<div class="modal fade" id="modal_existe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['informacao_label']; ?></h4>
    </div>
    <div class="modal-body"> </div>
    <div class="modal-footer">
      <button type="button" class="btn blue" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
    </div>
  </div>
  <!-- /.modal-content --> 
</div>
<!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<a id="mostra_existe" href="#modal_existe" data-toggle="modal" style="display:none">&nbsp;</a>
<script type="text/javascript">
$(document).ready(function() {
	<?php if($erro_preencha_username == 1) { ?>$("#tab_3").click(); $("#modal_existe .modal-body").html("<?php echo $RecursosCons->RecursosCons['preencher_user_info']; ?>");<?php } ?>
	<?php if($erro_preencha_password == 1) { ?>$("#tab_3").click(); $("#modal_existe .modal-body").html("<?php echo $RecursosCons->RecursosCons['pass_nao_definifa_info']; ?>");<?php } ?>
	<?php if($erro_email == 1) { ?>$("#modal_existe .modal-body").html("<?php echo $RecursosCons->RecursosCons['email_existe_insere_novo']; ?>");<?php } ?>
	<?php if($erro_username == 1) { ?>$("#modal_existe .modal-body").html("<?php echo $RecursosCons->RecursosCons['username_atribuito_info']; ?>");	<?php } ?>
	$("#mostra_existe").click();	
});
</script>
<?php } ?>
</body>
<!-- END BODY -->
</html>