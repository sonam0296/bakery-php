<?php include_once('../inc_pages.php'); ?>
<?php 

$fixo = $_GET['fixo'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Páginas Fixas';

if($fixo == 0) {
	$menu_sub_sel='paginas_outras';
	$nome_sel='Outras Páginas';
}

$tab_sel=1;
if($_GET['env']==1) $tab_sel=1;
if(isset($_GET['tab_sel'])) $tab_sel = $_GET['tab_sel'];

$id=$_GET['id'];

$tamanho_imagens1 = getFillSize('Paginas', 'imagem1');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "paginas_form")) {
	$manter = $_POST['manter'];
	$tab_sel = $_REQUEST['tab_sel'];	

	$query_rsP = "SELECT * FROM paginas".$extensao." WHERE id=:id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	
	if($_POST['nome']!='' && $tab_sel == 1) {
    //Só atualiza o URL se a checkbox for selecionada (para não perder os URL's personalizados)
    if(isset($_POST['atualizar_url'])) {
	    $nome_url = strtolower(verifica_nome($_POST['nome']));	
				
			$query_rsProc = "SELECT id FROM paginas".$extensao." WHERE url like :nome_url AND id!=:id";
			$rsProc = DB::getInstance()->prepare($query_rsProc);
			$rsProc->bindParam(':id', $id, PDO::PARAM_INT);
			$rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);
			$rsProc->execute();
			$totalRows_rsProc = $rsProc->rowCount();
						
			if($totalRows_rsProc > 0) {
				$nome_url = $nome_url."-".$id;
			}

			//REDIRECT 301
			if($row_rsP['url']!=$nome_url) redirectURL($row_rsP['url'], $nome_url, substr($extensao,1));
		}
		else {
      $nome_url = $row_rsP['url'];
    }
		
		//VERIFICA SE O TITULO JÁ ESTÁ PERSONALIZADO
		$title=$_POST['nome'];
		if($row_rsP['title']!=$row_rsP['nome']) {
			$title=$row_rsP['title'];
		}
		
		$insertSQL = "UPDATE paginas".$extensao." SET nome=:nome, titulo=:titulo, url=:url, title=:title WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);		
		$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);			
		$rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':title', $title, PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsInsert->execute();

		$mostra_titulo=0;
		if(isset($_POST['mostra_titulo'])) $mostra_titulo=1;

		$mostrar_topo=0;
		if(isset($_POST['mostrar_topo'])) $mostrar_topo=1;

		$tem_fundo=0;
		if(isset($_POST['tem_fundo'])) $tem_fundo=1;

		$tipo_fundo = $_POST['tipo_fundo'];

		$mostrar_menu=0;
		if(isset($_POST['mostrar_menu'])) $mostrar_menu=1;

		$query_rsLinguas = "SELECT * FROM linguas WHERE visivel = 1";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		DB::close();

		while($row_rsLinguas = $rsLinguas->fetch()) {
			$insertSQL = "UPDATE paginas_".$row_rsLinguas['sufixo']." SET menu=:menu, mostrar_menu=:mostrar_menu, mostrar_topo=:mostrar_topo, esp_blocos=:esp_blocos, esp_blocos_mob=:esp_blocos_mob, mostra_titulo=:mostra_titulo, tem_fundo=:tem_fundo, tipo_fundo=:tipo_fundo, cor_fundo=:cor_fundo, cor_titulo=:cor_titulo WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);	
			$rsInsert->bindParam(':menu', $_POST['menu'], PDO::PARAM_INT);
			$rsInsert->bindParam(':mostrar_menu', $mostrar_menu, PDO::PARAM_INT);
			$rsInsert->bindParam(':mostrar_topo', $mostrar_topo, PDO::PARAM_INT);	
			$rsInsert->bindParam(':esp_blocos', $_POST['esp_blocos'], PDO::PARAM_INT);		
			$rsInsert->bindParam(':esp_blocos_mob', $_POST['esp_blocos_mob'], PDO::PARAM_INT);	
			$rsInsert->bindParam(':mostra_titulo', $mostra_titulo, PDO::PARAM_INT);	
			$rsInsert->bindParam(':tem_fundo', $tem_fundo, PDO::PARAM_INT);	
			$rsInsert->bindParam(':tipo_fundo', $tipo_fundo, PDO::PARAM_INT);	
			$rsInsert->bindParam(':cor_titulo', $_POST['cor_titulo'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':cor_fundo', $_POST['cor_fundo'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
			$rsInsert->execute();
		}


		$imagem = $row_rsP['imagem1'];

		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
			$insertSQL = "UPDATE paginas".$extensao." SET imagem1=NULL WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
			$rsInsert->execute();

			@unlink('../../../imgs/paginas/'.$imagem);
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
				
				$imgs_dir = "../../../imgs/paginas";
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
					if($imagem!="" && file_exists("../../../imgs/paginas/".$imagem)){
										
						$maxW=$tamanho_imagens1['0'];
						$maxH=$tamanho_imagens1['1'];
						
						$sizes=getimagesize("../../../imgs/paginas/".$imagem);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
											
							$img1=new Resize("../../../imgs/paginas/", $imagem, $imagem, $maxW, $maxH);
							$img1->resize_image();
							
						}
					}		
					
					if($row_rsP['imagem1']){
						@unlink('../../../imgs/paginas/'.$row_rsP['imagem1']);
					}

					compressImage('../../../imgs/paginas/'.$imagem, '../../../imgs/paginas/'.$imagem);

					$insertSQL = "UPDATE paginas".$extensao." SET imagem1=:imagem1 WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
					$rsInsert->execute();
				}
			}
		}

		DB::close();

		alteraSessions('paginas');
		alteraSessions('paginas_menu');
		alteraSessions('paginas_fixas');
			
		if(!$manter) 
			header("Location: paginas.php?alt=1&fixo=".$fixo);
		else
			header("Location: paginas-edit.php?id=".$id."&alt=1&tab_sel=1&fixo=".$fixo);		
	}
		
	if($tab_sel==3) {

		$nome_url=$_POST['url'];
		
		$query_rsP = "SELECT url FROM paginas".$extensao." WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		
		$nome_url=strtolower(verifica_nome($nome_url));
		
		//REDIRECT 301
		if($row_rsP['url']!=$nome_url) redirectURL($row_rsP['url'], $nome_url, substr($extensao,1));
		
		$insertSQL = "UPDATE paginas".$extensao." SET url=:url, title=:title, description=:description, keywords=:keywords WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':title', $_POST['title'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':description', $_POST['description'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':keywords', $_POST['keywords'], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsInsert->execute();

		DB::close();
		
		alteraSessions('paginas');
		alteraSessions('paginas_menu');
		alteraSessions('paginas_fixas');
		
		if(!$manter) 
			header("Location: paginas.php?alt=1");
		else
			header("Location: paginas-edit.php?id=".$id."&alt=1&tab_sel=3&fixo=".$fixo);
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

$query_rsMenus = "SELECT * FROM menus_paginas".$extensao." WHERE visivel = 1";
$rsMenus = DB::getInstance()->prepare($query_rsMenus);
$rsMenus->execute();
$row_rsMenus = $rsMenus->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsMenus = $rsMenus->rowCount();
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
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?></div>
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
          <form id="paginas_form" name="paginas_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['paginas']; ?>  - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='paginas.php?fixo=<?php echo $fixo; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
                  <?php if($row_rsP['fixo']!=1){ ?><a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a><?php } ?>
                </div>
              </div>
              <div class="portlet-body">
              	<div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1';"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='paginas-blocos.php?fixo=<?php echo $fixo; ?>&pagina=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> Blocos </a> </li>
                    <li <?php if($tab_sel==3) echo "class=\"active\""; ?>> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3';"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">
                      <div class="form-body">
						  					<?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
		                      <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <span>  <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>
		                      </div>
	                      <?php } ?>
												<?php if(isset($_GET['env']) && $_GET['env'] == 1) { ?>  
	                        <div class="alert alert-success display-show">
	                        <button class="close" data-close="alert"></button>
	                         <?php echo $RecursosCons->RecursosCons['env_config']; ?> </div>
	                    	<?php } ?>
	                      <div class="alert alert-danger display-hide">
	                        <button class="close" data-close="alert"></button>
	                        <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>                  
	                      <div class="form-group">
	                        <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>
	                        <div class="col-md-5">
	                          <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>">
	                        </div>
	                        <label class="col-md-1 control-label" for="atualizar_url"><?php echo $RecursosCons->RecursosCons['atualizar_url']; ?>? </label>
                          <div class="col-md-2" style="padding-top: 7px;">
                            <input type="checkbox" class="form-control" name="atualizar_url" id="atualizar_url" value="1">
                            <p class="help-block"><?php echo $RecursosCons->RecursosCons['atualizar_url_txt']; ?></p>
                          </div>
	                      </div> 
	                      <div class="form-group">
			                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>
			                    <div class="col-md-8">
			                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>">
			                    </div>
			                  </div>
			                  <?php /*<div class="form-group">
			                    <label class="col-md-2 control-label" for="mostrar_menu" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['mostrar_menu_label']; ?> </label>
			                    <div class="col-md-3">
			                      <input type="checkbox" class="form-control" name="mostrar_menu" id="mostrar_menu" value="1" <?php if($row_rsP['mostrar_menu'] == 1) echo "checked";?>>
			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['info_select_menu']; ?></p>
			                    </div>
			                  </div>*/ ?>
			                  <?php if($totalRows_rsMenus > 0) { ?>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" for="menu"><?php echo $RecursosCons->RecursosCons['menu_label']; ?>: </label>
		                      <div class="col-md-8">
		                        <select class="form-control select2me" id="menu" name="menu">
		                          <option value="0"><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>    
		                          <?php foreach ($row_rsMenus as $menu) { ?>
		                            <option value="<?php echo $menu['id']; ?>" <?php if($menu['id'] == $row_rsP['menu']) echo "selected"; ?> ><?php echo $menu['nome']; ?></option>
		                          <?php } ?>
		                        </select>
		                        <p class="help-block"><?php echo $RecursosCons->RecursosCons['menu_opcao_help']; ?></p>
		                      </div>
		                    </div>
		                  <?php } ?> 
			                  <hr>
			                  <div class="form-group">
			                    <label class="col-md-2 control-label" for="mostrar_topo" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['mostrar_topo_label']; ?> </label>
			                    <div class="col-md-3">
			                      <input type="checkbox" class="form-control" name="mostrar_topo" id="mostrar_topo" value="1" <?php if($row_rsP['mostrar_topo'] == 1) echo "checked";?>>
			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['info_selec_topo']; ?></p>
			                    </div>
			                  </div>
			                  <div class="form-group">
			                    <label class="col-md-2 control-label" for="esp_blocos"><?php echo $RecursosCons->RecursosCons['espacamento_blocos']; ?>:</label>
			                    <div class="col-md-3">
			                      <div class="input-group">
	                            <input type="text" class="form-control" name="esp_blocos" id="esp_blocos" value="<?php echo $row_rsP['esp_blocos']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
		                        	<span class="input-group-addon">Px</span>
		                        </div>
		                        <p class="help-block"><?php echo $RecursosCons->RecursosCons['espacamento_blocos_desktop']; ?></p>
			                    </div>
			                    <label class="col-md-2 control-label" for="esp_blocos_mob"><?php echo $RecursosCons->RecursosCons['espacamento_blocos']; ?>:</label>
			                    <div class="col-md-3">
			                      <div class="input-group">
	                            <input type="text" class="form-control" name="esp_blocos_mob" id="esp_blocos_mob" value="<?php echo $row_rsP['esp_blocos_mob']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
		                        	<span class="input-group-addon">Px</span>
		                        </div>
		                        <p class="help-block"><?php echo $RecursosCons->RecursosCons['espacamento_blocos_mobile']; ?></p>
			                    </div>
			                  </div>
			                  <hr>
			                  <div class="form-group div_topo">
			                    <label class="col-md-2 control-label" for="mostra_titulo" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['mostrar_titulo']; ?> </label>
			                    <div class="col-md-3">
			                      <input type="checkbox" class="form-control" name="mostra_titulo" id="mostra_titulo" value="1" <?php if($row_rsP['mostra_titulo'] == 1) echo "checked";?>>
			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['info_mostra_titulo']; ?></p>
			                    </div>
			                    <label class="col-md-2 control-label div_cor" for="cor_titulo"><?php echo $RecursosCons->RecursosCons['cor_titulo_label']; ?>: </label>
			                    <div class="col-md-2 div_cor">
			                    	<input type="text" class="form-control color" name="cor_titulo" id="cor_titulo" value="<?php echo $row_rsP['cor_titulo']; ?>">
			                    </div>
			                    <div class="col-md-3">
			                    	<strong style="display: block; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['cores_website_label']; ?></strong>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['primeira_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #217C36; color: #ffffff; padding: 3px 5px;">#217C36</span><br>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['segunda_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #2FAD49; color: #ffffff; padding: 3px 5px;">#2FAD49</span><br>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['terceira_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #FCC91E; color: #000000; padding: 3px 5px;">#FCC91E</span><br>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['quarta_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #FAF5E4; color: #000000; padding: 3px 5px;">#FAF5E4</span>
			                    </div>
			                  </div>
			                  <div class="form-group div_topo" style="margin-top: 40px;">
			                    <label class="col-md-2 control-label" for="tem_fundo" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['com_fundo']; ?> </label>
			                    <div class="col-md-10">
			                      <input type="checkbox" class="form-control" name="tem_fundo" id="tem_fundo" value="1" <?php if($row_rsP['tem_fundo'] == 1) echo "checked";?>>
			                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['info_cor_fundo']; ?></p>
			                    </div>
			                  </div>
			                  <div class="form-group div_topo" id="div_tipo">
													<label class="col-md-2 control-label" for="tipo_fundo"><?php echo $RecursosCons->RecursosCons['tipo_fundo_label']; ?>: </label>
													<div class="col-md-3" style="padding-top: 6px;">
														<div class="radio-list">
															<label class="radio" style="margin-bottom: 10px;">
															<input type="radio" name="tipo_fundo" id="tipo_fundo1" value="1" <?php if($row_rsP['tipo_fundo'] == 1) echo "checked"; ?>> <?php echo $RecursosCons->RecursosCons['cor_label']; ?> </label>
															<label class="radio">
															<input type="radio" name="tipo_fundo" id="tipo_fundo2" value="2" <?php if($row_rsP['tipo_fundo'] == 2) echo "checked"; ?>> <?php echo $RecursosCons->RecursosCons['imagem']; ?> </label>
														</div>
													</div>
													<label class="col-md-2 control-label div_cor_fundo" for="cor_fundo"><?php echo $RecursosCons->RecursosCons['cor_fundo_label']; ?>: </label>
			                    <div class="col-md-2 div_cor_fundo">
			                    	<input type="text" class="form-control color" name="cor_fundo" id="cor_fundo" value="<?php echo $row_rsP['cor_fundo']; ?>">
			                    </div>
			                    <div class="col-md-3 div_cor_fundo">
			                    	<strong style="display: block; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['cores_website_label']; ?></strong>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['primeira_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #217C36; color: #ffffff; padding: 3px 5px;">#217C36</span><br>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['segunda_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #2FAD49; color: #ffffff; padding: 3px 5px;">#2FAD49</span><br>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['terceira_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #FCC91E; color: #000000; padding: 3px 5px;">#FCC91E</span><br>
			                    	<span style="display: inline-block; width: 80px; margin-bottom: 10px;"><?php echo $RecursosCons->RecursosCons['quarta_cor']; ?>:</span> <span style="display: inline-block; width: 65px; height: 25px; background-color: #FAF5E4; color: #000000; padding: 3px 5px;">#FAF5E4</span>
			                    </div>
												</div>
			                  <div class="form-group div_topo" id="div_imagem">
                          <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem']; ?><br>
                            <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/paginas/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/paginas/".$row_rsP['imagem1'])) { ?>
                                <a href="../../../imgs/paginas/<?php echo $row_rsP['imagem1']; ?>" data-fancybox ><img src="../../../imgs/paginas/<?php echo $row_rsP['imagem1']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?> </span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
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
                        </div>
	                    </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_dados">
                      <div class="form-body">
					  						<?php if($_GET['alt']==1 && $_GET['tab_sel'] == 3) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span>  <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>
                          </div>
                        <?php } ?>
                    	  <div class="form-group">
                          <label class="col-md-2 control-label" for="url"> <?php echo $RecursosCons->RecursosCons['url_label']; ?>:</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" name="url" id="url" value="<?php echo $row_rsP['url']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">
                          </div>
                        </div>
                      	<div class="form-group">
                          <label class="col-md-2 control-label" for="title"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $row_rsP['title']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">
                          </div>
	                      </div>
	                      <div class="form-group">
                          <label class="col-md-2 control-label" for="description"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>:</label>
                          <div class="col-md-10">
                            <textarea class="form-control" rows="5" id="description" name="description" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['description']; ?></textarea>
                          </div>
	                      </div>
	                      <div class="form-group">
                          <label class="col-md-2 control-label" for="keywords"><?php echo $RecursosCons->RecursosCons['palavras-chave_label']; ?>:</label>
                          <div class="col-md-10">
                            <textarea class="form-control" rows="5" id="keywords" name="keywords" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['keywords']; ?></textarea>
                          	<span class="help-block"><strong><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>:</strong> <?php echo $RecursosCons->RecursosCons['nota_palavra-chave']; ?></span>
                          </div>
	                      </div>
	                      <div class="form-group">
                          <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['pre-view_google_label']; ?>:</label>
                          <div class="col-md-10" style="padding:0 15px">
                            <div style="border:1px solid #e5e5e5;min-height:50px;padding:10px" id="googlePreview"></div>
                          </div>
	                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" id="MM_paginas" value="paginas_form" />
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

	if('<?php echo $row_rsP["tem_fundo"]; ?>' == '0') {
		$('#div_tipo').css('display', 'none');
		$('#div_imagem').css('display', 'none');
	}
	else {
		$('#div_tipo').css('display', 'block');
	}

	if('<?php echo $row_rsP["tem_fundo"]; ?>' == '1' && '<?php echo $row_rsP["tipo_fundo"]; ?>' == '2') {
		$('#div_imagem').css('display', 'block');
		$('.div_cor_fundo').css('display', 'none');
	}
	else {
		$('#div_imagem').css('display', 'none');
		$('.div_cor_fundo').css('display', 'inline-block');
	}

	$('#tem_fundo').on('change', function() {
		if($(this).is(':checked')) {
			$('#div_tipo').css('display', 'block');
		}
		else {
			$('#div_tipo').css('display', 'none');
			$('#div_imagem').css('display', 'none');
		}
	});

	$('input[type=radio][name=tipo_fundo]').change(function() {
		if(this.value == '1') {
			$('#div_imagem').css('display', 'none');
			$('.div_cor_fundo').css('display', 'inline-block');
		}
		else if(this.value == '2') {
			$('#div_imagem').css('display', 'block');
			$('.div_cor_fundo').css('display', 'none');
		}
	});

	if('<?php echo $row_rsP["mostra_titulo"]; ?>' == '0') {
		$('.div_cor').css('display', 'none');
	}
	else {
		$('.div_cor').css('display', 'block');
	}

	$('#mostra_titulo').on('change', function() {
		if($(this).is(':checked')) {
			$('.div_cor').css('display', 'block');
		}
		else {
			$('.div_cor').css('display', 'none');
		}
	});

	$('#mostrar_topo').on('change', function() {
		if($(this).is(':checked')) {
			$('.div_topo').css('display', 'block');
		}
		else {
			$('.div_topo').css('display', 'none');
		}
	});

	if('<?php echo $row_rsP["mostrar_topo"]; ?>' == '0') {
		$('.div_topo').css('display', 'none');
	}
	else {
		$('.div_topo').css('display', 'block');
	}
});
</script> 
<script type="text/javascript">
document.ready=carregaPreview();
</script>
</body>
<!-- END BODY -->
</html>