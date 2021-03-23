<?php include_once('../inc_pages.php'); ?>
<?php 

$inserido=0;
$erro_password=0;
$tab_sel=1;


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "dados_pessoais")) {
  	
	
  	if($_POST['nome']!='' && $_POST['email']!=''){	
		
		$insertSQL = "UPDATE acesso SET nome=:nome, email=:email, telefone=:telefone, funcao=:funcao, observacoes=:observacoes WHERE id='$id_user' AND username='$username'";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':funcao', $_POST['funcao'], PDO::PARAM_STR, 5);		
		$rsInsert->bindParam(':observacoes', $_POST['observacoes'], PDO::PARAM_STR, 5);	
		$rsInsert->execute();
		DB::close();
		
		$inserido=1;
		
	}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "altera_imagem")) {
	
	if(!$_FILES){
		
		@unlink('../../imgs/user/'.$row_rsUser['imagem1']);
		$imagem='';
		
				
		$insertSQL = "UPDATE acesso SET imagem1=:imagem1 WHERE id='$id_user' AND username='$username'";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);	
		$rsInsert->execute();
		DB::close();
		
	}
	
	if($_FILES['img']['name']!=''){
		
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
		
		if($row_rsUser['imagem1']){
			@unlink('../../imgs/user/'.$row_rsUser['imagem1']);
		}
				
		$insertSQL = "UPDATE acesso SET imagem1=:imagem1 WHERE id='$id_user' AND username='$username'";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);	
		$rsInsert->execute();
		DB::close();
	}
	
	$inserido=2;
	
	$tab_sel=2;
			
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "dados_acesso")) {
  	
	
  	if($_POST['username']!=''){			
		
		$insertSQL = "UPDATE acesso SET username=:username WHERE id='$id_user' AND username='$username'";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':username', $_POST['username'], PDO::PARAM_STR, 5);	
		$rsInsert->execute();
		DB::close();
		
		$password=$_POST['password'];
		$password_rep=$_POST['rep_password'];
		
		if($password!=''){
			if($password==$password_rep){
				
				$salt = createSalt();				
				$hash = hash('sha256', $password);
				
				$password_final=hash('sha256', $salt . $hash);
				
				$insertSQL = "UPDATE acesso SET password=:password, password_salt = '$salt' WHERE id='$id_user' AND username='$username'";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':password', $password_final, PDO::PARAM_STR, 5);	
				$rsInsert->execute();
				DB::close();
				
			}else{
				$erro_password=1;
			}
		
		}
		
		$inserido=3;
	
		$tab_sel=3;
	}
}

if($inserido>0){
	$query_rsUser = "SELECT * FROM acesso WHERE acesso.username='$username' AND id='$id_user'";
	$rsUser = DB::getInstance()->prepare($query_rsUser);
	$rsUser->execute();
	$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsUser = $rsUser->rowCount();
	DB::close();
}




$menu_sel='users_perfil';
$menu_sub_sel='';

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?> page-sidebar-closed-hide-logo page-container-bg-solid">
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
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['perfil']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="perfil-alterar.php"><?php echo $RecursosCons->RecursosCons['definicoes_conta']; ?></a>
					</li>
				</ul>				
			</div>
			<h3 class="page-title">
			<?php echo $RecursosCons->RecursosCons['meu_perfil']; ?> <small><?php echo $RecursosCons->RecursosCons['definicoes_conta']; ?></small>
			</h3>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row margin-top-20">
				<div class="col-md-12">
					<!-- BEGIN PROFILE SIDEBAR -->
					<div class="profile-sidebar">
						<!-- PORTLET MAIN -->
						<div class="portlet light profile-sidebar-portlet">
							<!-- SIDEBAR USERPIC -->
							<div class="profile-userpic">
                            	<?php if($row_rsUser['imagem1']!="" && file_exists("../../imgs/user/".$row_rsUser['imagem1'])){ ?>
								<img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/user/<?php echo $row_rsUser['imagem1']; ?>" class="img-responsive" width="150" alt="">
                                <?php } ?>
							</div>
							<!-- END SIDEBAR USERPIC -->
							<!-- SIDEBAR USER TITLE -->
							<div class="profile-usertitle">
								<div class="profile-usertitle-name">
									 <?php echo $nome_mostra; ?>
								</div>
								<div class="profile-usertitle-job">
									 <?php echo $row_rsUser['funcao']; ?>
								</div>
							</div>
							<!-- END SIDEBAR USER TITLE -->
							<!-- SIDEBAR MENU -->
							<div class="profile-usermenu">
								<ul class="nav">
									
								</ul>
							</div>
							<!-- END MENU -->
						</div>
					</div>
					<!-- END BEGIN PROFILE SIDEBAR -->
					<!-- BEGIN PROFILE CONTENT -->
					<div class="profile-content">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption caption-md">
											<i class="icon-globe theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold uppercase"><?php echo $RecursosCons->RecursosCons['dados_perfil']; ?></span>
										</div>
										<ul class="nav nav-tabs">
											<li <?php if($tab_sel==1) echo "class=\"active\""; ?>>
												<a href="#tab_1_1" data-toggle="tab"><?php echo $RecursosCons->RecursosCons['info_pessoal']; ?></a>
											</li>
											<li <?php if($tab_sel==2) echo "class=\"active\""; ?>>
												<a href="#tab_1_2" class="menu_link_2" data-toggle="tab"><?php echo $RecursosCons->RecursosCons['alterar_imagem']; ?></a>
											</li>
											<li <?php if($tab_sel==3) echo "class=\"active\""; ?>>
												<a href="#tab_1_3" class="menu_link_3" data-toggle="tab"><?php echo $RecursosCons->RecursosCons['dados_acesso']; ?></a>
											</li>
										</ul>
									</div>
									<div class="portlet-body">
										<div class="tab-content">
											<!-- PERSONAL INFO TAB -->
											<div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_1_1">
                                            	<!-- BEGIN FORM-->
												<form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data">
													<div class="form-body">
                                                    <div class="alert alert-danger display-hide">
                                                        <button class="close" data-close="alert"></button>
                                                        <?php echo $RecursosCons->RecursosCons['error_form']; ?>
                                                    </div>
                                                    <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                                                    	<button class="close" data-close="alert"></button>
                                                         <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
                                                    </div>     
                                                    <div class="form-group">
                                                        <label class="control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?> <span class="required"> * </span>
                                                        </label>
                                                        <div class="input-icon right">
                                                        	<i class="fa"></i>
                                                            <input id="nome" name="nome" type="text" class="form-control" value="<?php echo $row_rsUser['nome']; ?>" data-required="1"/>
                                                        </div>
                                                    </div>
													<div class="form-group">
														<label class="control-label" for="email"><?php echo $RecursosCons->RecursosCons['cli_email']; ?><span class="required"> * </span></label>
                                                    	<div class="input-icon right">
                                                        	<i class="fa"></i>
															<input id="email" name="email" type="email" class="form-control" value="<?php echo $row_rsUser['email']; ?>" data-required="1"/>
                                                    	</div>
													</div>
													<div class="form-group">
														<label class="control-label" for="telefone"><?php echo $RecursosCons->RecursosCons['cli_telefone']; ?></label>
														<input id="telefone" name="telefone" type="text" class="form-control" value="<?php echo $row_rsUser['telefone']; ?>"/>
													</div>
													<div class="form-group">
														<label class="control-label" for="funcao"><?php echo $RecursosCons->RecursosCons['funcao_label']; ?></label>
														<input type="text" id="funcao" name="funcao" class="form-control" value="<?php echo $row_rsUser['funcao']; ?>"/>
													</div>
													<div class="form-group">
														<label class="control-label" for="observacoes"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?></label>
														<textarea class="form-control" rows="3" id="observacoes" name="observacoes" style="resize:none"><?php echo $row_rsUser['observacoes']; ?></textarea>
													</div>
                                                </div>
                                                <div class="form-actions">   
													<div class="margiv-top-10">
                                                        <button type="submit" class="btn green"><?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?></button>
                                                        <button type="reset" class="btn default"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="MM_insert" value="dados_pessoais" />
												</form>
											</div>
											<!-- END PERSONAL INFO TAB -->
											<!-- CHANGE AVATAR TAB -->
											<div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_1_2">                                                
												<form action="<?php echo $editFormAction; ?>" method="POST" id="altera_imagem" role="form" enctype="multipart/form-data">
                                                    <div class="alert alert-success<?php if($inserido!=2) echo " display-hide"; ?>">
                                                    	<button class="close" data-close="alert"></button>
                                                        <?php echo $RecursosCons->RecursosCons['img_alt']; ?>
                                                    </div>  
													<div class="form-group">
                                                        <div class="fileinput fileinput-<?php if($row_rsUser['imagem1']!="" && file_exists("../../imgs/user/".$row_rsUser['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
															
                                                            
                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
																<img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/>
															</div>
															<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                                            <?php if($row_rsUser['imagem1']!="" && file_exists("../../imgs/user/".$row_rsUser['imagem1'])) { ?><img src="../../imgs/user/<?php echo $row_rsUser['imagem1']; ?>"><?php } ?>
															</div>
															<div>
																<span class="btn default btn-file">
																<span class="fileinput-new">
																<?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span>
																<span class="fileinput-exists">
																<?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
																<input id="upload_campo" type="file" name="img">
																</span>
																<a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
																<?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?>  </a>
															</div>
														</div>
														<div class="clearfix margin-top-10">
															<span class="label label-danger">Nota! </span>
															<span><?php echo $RecursosCons->RecursosCons['funcionalidade_msg']; ?> </span>
														</div>
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
													<div class="margin-top-10">
                                                        <button type="submit" class="btn green-haze"><?php echo $RecursosCons->RecursosCons['btn_enviar_img']; ?> </button>
													</div>
												<input type="hidden" name="MM_insert" value="altera_imagem" />
												</form>
											</div>
											<!-- END CHANGE AVATAR TAB -->
											<!-- CHANGE PASSWORD TAB -->
											<div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_1_3">
                                            	<form action="<?php echo $editFormAction; ?>" method="POST" id="dados_acesso" role="form" enctype="multipart/form-data">
                                                    <div class="alert alert-success<?php if($inserido!=3) echo " display-hide"; ?>">
                                                    	<button class="close" data-close="alert"></button>
                                                        <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
                                                    </div>
                                                    <div class="alert alert-danger <?php if($erro_password!=1) echo "display-hide"; ?>">
                                                        <button class="close" data-close="alert"></button>
                                                        <?php echo $RecursosCons->RecursosCons['pass_error']; ?>
                                                    </div>
                                                	<div class="form-group">
                                                        <label class="control-label" for="username"><?php echo $RecursosCons->RecursosCons['username_label']; ?> <span class="required"> * </span>
                                                        </label>
                                                        <div class="input-icon right">
                                                        	<i class="fa"></i>
                                                            <input id="username" name="username" type="text" class="form-control" autocomplete="off" value="<?php echo $row_rsUser['username']; ?>" data-required="1"/>
                                                        </div>
                                                    </div>
                                                	<div class="form-group">
                                                        <label class="control-label" for="password"><?php echo $RecursosCons->RecursosCons['cli_password']; ?> - <span style="font-size:13px">deixe em branco para manter</span>
                                                        </label>
                                                            <input id="password" name="password" type="password" autocomplete="off" class="form-control" value=""/>
                                                    </div>
                                                	<div class="form-group">
                                                        <label class="control-label" for="rep_password"><?php echo $RecursosCons->RecursosCons['repetir_pass_label']; ?>
                                                        </label>
                                                            <input id="rep_password" name="rep_password" type="password" class="form-control" value=""/>
                                                    </div>
													<div class="margin-top-10">
                                                        <button type="submit" class="btn green-haze"><?php echo $RecursosCons->RecursosCons['alterar']; ?></button>
													</div>
                                                <input type="hidden" name="MM_insert" value="dados_acesso" />
												</form>
											</div>
											<!-- END CHANGE PASSWORD TAB -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END PROFILE CONTENT -->
				</div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/profile.js" type="text/javascript"></script>
<script src="form-validation.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   FormValidation.init();
});
</script>
<?php if($row_rsUser['imagem1']!="" && file_exists("../../imgs/user/".$row_rsUser['imagem1'])){ ?>
<script type="text/javascript">
$(document).ready(function(e) {
	var images = "<?php echo $row_rsUser['imagem1']; ?>";
	
	$('#upload_campo').fileinput({
		uploadAsync: true,
		initialPreview: images
	});
});
</script>
<?php } ?>
</body>
<!-- END BODY -->
</html>