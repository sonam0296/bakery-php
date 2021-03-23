<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='clientes';
$menu_sub_sel="roll";


$tab_sel=1;
$erro = 0;

$tab_sel = 1;
if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];

$id = $_GET['id'];
$tamanho_imagens1 = getFillSize('Noticias', 'imagem2');

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_noticias")) {
	$manter = $_POST['manter'];
	$tab_sel = $_REQUEST['tab_sel'];
	
	$query_rsP = "SELECT * FROM noticias".$extensao." WHERE id=:id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	
	if($_POST['roll_name']!='' && $tab_sel == 1) {
		//Só atualiza o URL se a checkbox for selecionada (para não perder os URL's personalizados)
    if(isset($_POST['atualizar_url'])) {
			$query_rsLingua = "SELECT nome_para_noticias FROM linguas WHERE sufixo =:lg_extensao";
	    $rsLingua = DB::getInstance()->prepare($query_rsLingua);
	    $rsLingua->bindParam(':lg_extensao', $lg_extensao, PDO::PARAM_STR, 5);
	    $rsLingua->execute();
			$row_rsLingua = $rsLingua->fetch(PDO::FETCH_ASSOC);
			
			$prefixo = $row_rsLingua['nome_para_noticias']."-";
			
			$nome_url = $prefixo.strtolower(verifica_nome($_POST['nome']));		
				
			$query_rsProc = "SELECT id FROM noticias".$extensao." WHERE url like :nome_url AND id!=:id";
			$rsProc = DB::getInstance()->prepare($query_rsProc);
			$rsProc->bindParam(':id', $id, PDO::PARAM_INT);
			$rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);		
			$rsProc->execute();
			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
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

		$tags=$_POST['tags'];
		if($row_rsP['tags']!=$row_rsP['tags']) {
			$title=$row_rsP['tags'];
		}
		
		$descricao=str_replace("</p><p>", "<br />", $_POST['resumo']);
		$descricao=str_replace("</p>", "", $descricao);
		$descricao=str_replace("<p>", "", $descricao);
		$descricao=str_replace("<br>", "<br />", $descricao);
		
		$descricao=trim($descricao);	
		   
		$data = NULL; 
		if(isset($_POST['roll_name'])  && $_POST['roll_name'] != "") 
					
		// actualiza detalhes
		$insertSQL = "UPDATE roll SET roll_name=:roll_name, cod_price=:cod_price WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':roll_name', $_POST['roll_name'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':cod_price', $_POST['cod_price'], PDO::PARAM_INT, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
		$rsInsert->execute();

		DB::close();
		
		alteraSessions('supplier');

		if(!$manter) 
			header("Location: roll.php?alt=1");
		else
			header("Location: roll-edit.php?id=".$id."&alt=1&tab_sel=1");
	}

	/*if($tab_sel==2) {
		//var_dump($_FILES);
		$rem = 0;
		$opcao = $_POST['opcao'];
		$imagem = $row_rsP['imagem1'];

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$row_rsLinguas = $rsLinguas->fetchAll();
		$totalRows_rsLinguas = $rsLinguas->rowCount();

		if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
			if($opcao == 1) {
				$insertSQL = "UPDATE noticias".$extensao." SET imagem1=NULL WHERE id=:id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
				$rsInsert->execute();

				$r = 0;

				//Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...
				foreach($row_rsLinguas as $linguas) {	
					$query_rsImagem = "SELECT id FROM noticias_".$linguas["sufixo"]." WHERE imagem1=:imagem AND id=:id";
					$rsImagem = DB::getInstance()->prepare($query_rsImagem);
					$rsImagem->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
					$rsImagem->bindParam(':id', $id, PDO::PARAM_INT);
					$rsImagem->execute();
					$totalRows_rsImagem = $rsImagem->rowCount();

					if($totalRows_rsImagem > 0)
						$r = 1;
				}

				//Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
				if($r == 0)
					@unlink('../../../imgs/noticias/'.$imagem);
			}
			else if($opcao == 2) {
				foreach($row_rsLinguas as $linguas) {		
					$query_rsSelect = "SELECT imagem1 FROM noticias_".$linguas['sufixo']." WHERE id=:id";
					$rsSelect = DB::getInstance()->prepare($query_rsSelect);
					$rsSelect->bindParam(':id', $id, PDO::PARAM_INT);
					$rsSelect->execute();
					$row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

					@unlink('../../../imgs/noticias/'.$row_rsSelect['imagem1']);

					$insertSQL = "UPDATE noticias_".$linguas["sufixo"]." SET imagem1=NULL WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
					$rsInsert->execute();
				}
			}

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
				
			$imgs_dir = "../../../imgs/noticias";
				$contaimg = 1; 
			//pre($_FILES);

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
							// echo $file_dir.'_'.$nome_file;
							// die('checking..');
					if (is_uploaded_file($file_array['tmp_name'])) 
					{ 
						move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); 
					}
		
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
					if($imagem!="" && file_exists("../../../imgs/noticias/".$imagem)){
										
						$maxW=$tamanho_imagens1['0'];
						$maxH=$tamanho_imagens1['1'];
						
						$sizes=getimagesize("../../../imgs/noticias/".$imagem);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH){
											
							$img1=new Resize("../../../imgs/noticias/", $imagem, $imagem, $maxW, $maxH);
							$img1->resize_image();
							
						}
					
					}		
					
					if($row_rsP['imagem1']){
						@unlink('../../../imgs/noticias/'.$row_rsP['imagem1']);
					}

					compressImage('../../../imgs/noticias/'.$imagem, '../../../imgs/noticias/'.$imagem);

					//Inserir apenas na língua atual
					if($opcao == 1) {
						$insertSQL = "UPDATE noticias".$extensao." SET imagem1=:imagem1 WHERE id=:id";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
						$rsInsert->execute();
					}
					//Inserir para todas as línguas
					else if($opcao == 2) {
						foreach($row_rsLinguas as $linguas) {		
							$insertSQL = "UPDATE noticias_".$linguas["sufixo"]." SET imagem1=:imagem1 WHERE id=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
							$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
							$rsInsert->execute();
						}
					}
				}
			}
		}

		DB::close();
		
		alteraSessions('noticias');

		if($erro == 1)
			header("Location: noticias-edit.php?id=".$id."&erro=1&tab_sel=2");
		else {
			if(!$manter) 
				header("Location: noticias.php?alt=1");
			else {
				if($rem == 1 && $ins == 0)
					header("Location: noticias-edit.php?id=".$id."&rem=1&tab_sel=2");
				else
					header("Location: noticias-edit.php?id=".$id."&alt=1&tab_sel=2");
			}
		}
	}*/

	/*if($tab_sel == 3) {
		if($_POST['url']!='') {
			$query_rsP = "SELECT url FROM noticias".$extensao." WHERE id=:id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	
			$rsP->execute();
			$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsP = $rsP->rowCount();
			
			$nome_url=strtolower(verifica_nome($_POST['url']));
			
			//REDIRECT 301
			if($row_rsP['url']!=$nome_url) redirectURL($row_rsP['url'], $nome_url, substr($extensao,1));

			$insertSQL = "UPDATE noticias".$extensao." SET url=:url, title=:title, description=:description, keywords=:keywords WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':url', $_POST['url'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':title', $_POST['title'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':description', $_POST['description'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':keywords', $_POST['keywords'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsInsert->execute();

			DB::close();
			
			alteraSessions('noticias');
			
			if(!$manter) 
				header("Location: noticias.php?alt=1");
			else
				header("Location: noticias-edit.php?id=".$id."&alt=1&tab_sel=3");
		}
	}*/
}


$query_rsP = "SELECT * FROM roll WHERE id = :id";
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
      <h3 class="page-title"> Roll <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="roll.php">Roll</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>
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
              <button type="button" class="btn blue" onClick="document.location='roll.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?> </button>
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
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_noticias" name="frm_noticias" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <input type="hidden" name="opcao" id="opcao" value="1">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['noticias']; ?>  - <?php echo $row_rsP["nome"]; ?> </div>
	              <div class="form-actions actions btn-set">
	                <button type="button" name="back" class="btn default" onClick="document.location='roll.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?> </button>
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
	                <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
	                <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> 
	              </div>
	            </div>
	            <div class="portlet-body">
	              <div class="tabbable">
	                <ul class="nav nav-tabs">
	                  <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
	                  <!-- <li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagem']; ?> </a> </li>
	                  <li onClick="window.location='noticias-edit-imagens.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_galeria']; ?> </a> </li>
	                  <li onClick="window.location='noticias-ficheiro.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_ficheiro']; ?> </a> </li>
	                  <li <?php if($tab_sel==3) echo "class=\"active\""; ?>> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li> -->
	                </ul>
	                <div class="tab-content no-space">
	                  <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">
	                    <div class="form-body">
	                      <div class="alert alert-danger display-hide">
	                        <button class="close" data-close="alert"></button>
	                        <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
	                      </div>
	                      <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>
		                      <div class="alert alert-success display-show">
		                        <button class="close" data-close="alert"></button>
		                        <?php echo $RecursosCons->RecursosCons['alt']; ?>
		                      </div>
		                    <?php } ?>
		                    <?php if($_GET['env'] == 1) { ?>
		                      <div class="alert alert-success display-show">
		                        <button class="close" data-close="alert"></button>
		                        <?php echo $RecursosCons->RecursosCons['env']; ?>
		                      </div>
		                    <?php } ?>
	                     

		                  <div class="form-group">
		                    <label class="col-md-2 control-label" for="roll_name">Roll Name: <span class="required"> * </span> </label>
		                    <div class="col-md-8">
		                      <input type="text" class="form-control" name="roll_name" id="roll_name" value="<?php echo $row_rsP['roll_name']; ?>" data-required="1">
		                    </div>
	                  	</div>
	                  	<?php if($row_rsP['roll_name'] == "customer") { ?>
	                  	<div class="form-group">
		                    <label class="col-md-2 control-label" for="cod_price">price (COD Option): <span class="required"> * </span> </label>
		                    <div class="col-md-2">
		                    <div class="input-group">
		                     <input type="text" class="form-control" name="cod_price" id="cod_price" value="<?php echo $row_rsP['cod_price']; ?>" maxlength="8">
                          	<span class="input-group-addon">€</span>
                          </div>
		                    </div>
	                  	</div>
	                 <?php } ?>
	                   
	              </div>
	          </div>

	                  <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">
                      <div class="form-body">
                      	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['img_alt']; ?> </div>
                        <?php } ?>
                        <?php if($_GET['rem'] == 1 && $_GET['tab_sel'] == 2) { ?>
	                        <div class="alert alert-danger display-show">
	                          <button class="close" data-close="alert"></button>
	                          <?php echo $RecursosCons->RecursosCons['img_rem']; ?> </div>
                        <?php } ?>
                        <?php if(isset($_GET['erro']) && $_GET['erro'] == 1) { ?>
			                    <div class="alert alert-danger display-show">
				                    <button class="close" data-close="alert"></button>
				                    <?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> </div>   
			                	<?php } ?> 
                        <div class="form-group">
                          <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem']; ?><br>
                            <strong><?php echo $tamanho_imagens1['0']." * ".$tamanho_imagens1['1']." px"; ?>:</strong> </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/noticias/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/noticias/".$row_rsP['imagem1'])) { ?>
                                <a href="../../../imgs/noticias/<?php echo $row_rsP['imagem1']; ?>" data-fancybox><img src="../../../imgs/noticias/<?php echo $row_rsP['imagem1']; ?>"></a>
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
	                  <!-- <div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_dados">
		                  <div class="form-body">
		                    <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 3) { ?>
			                    <div class="alert alert-success display-show">
			                      <button class="close" data-close="alert"></button>
			                      <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span> 
			                    </div>
		                    <?php } ?>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" for="url" style="text-align:right"><?php echo $RecursosCons->RecursosCons['url_label']; ?>:</label>
		                      <div class="col-md-6">
		                        <input type="text" class="form-control" name="url" id="url" value="<?php echo $row_rsP['url']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">
		                      </div>
		                    </div>
		                    <div class="clearfix"></div>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" for="title" style="text-align:right"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>
		                      <div class="col-md-6">
		                        <input type="text" class="form-control" name="title" id="title" value="<?php echo $row_rsP['title']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">
		                      </div>
		                    </div>
		                    <div class="clearfix"></div>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" for="description" style="text-align:right"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>:</label>
		                      <div class="col-md-6">
		                        <textarea class="form-control" rows="5" id="description" name="description" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['description']; ?></textarea>
		                      </div>
		                    </div>
		                    <div class="clearfix"></div>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" for="keywords" style="text-align:right"><?php echo $RecursosCons->RecursosCons['palavras-chave_label']; ?>:</label>
		                      <div class="col-md-6">
		                        <textarea class="form-control" rows="5" id="keywords" name="keywords" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['keywords']; ?></textarea>
		                        <span class="help-block"><strong><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>:</strong> <?php echo $RecursosCons->RecursosCons['nota_palavra-chave']; ?></span> </div>
		                    </div>
		                    <div class="clearfix"></div>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['pre-view_google_label']; ?>:</label>
		                      <div class="col-md-6" style="padding:0 15px">
		                        <div style="border:1px solid #e5e5e5;min-height:50px;padding:10px" id="googlePreview"></div>
		                      </div>
		                    </div>
		                  </div>
	                  </div> -->
	                </div>
	              </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_noticias" />
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
<script src="noticias-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   Noticias.init();
});
</script> 
<script type="text/javascript">
CKEDITOR.replace('supplier_address',
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

var wordCountConf1 = {
  showParagraphs: false,
  showWordCount: false,
  showCharCount: true,
  countSpacesAsChars: true,
  countHTML: false,
  maxWordCount: -1,
  maxCharCount: 150
}

CKEDITOR.replace('resumo',
{
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "150px",
  extraPlugins: 'wordcount,notification',
  wordcount: wordCountConf1
});
</script> 
<script type="text/javascript">
document.ready=carregaPreview();
</script>
</body>
<!-- END BODY -->
</html>