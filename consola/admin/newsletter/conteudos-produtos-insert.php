<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='newsletter_conteudos';
$menu_sub_sel='';

$id_tema = $_GET['id'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_conteudo_produtos")) {
	$query_rsTema = "SELECT * FROM news_temas WHERE id=:id";
	$rsTema = DB::getInstance()->prepare($query_rsTema);
	$rsTema->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$rsTema->execute();
	$row_rsTema = $rsTema->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsTema = $rsTema->rowCount();
	DB::close();

	$query_rsCont = "SELECT * FROM news_conteudo WHERE id=:id";
	$rsCont = DB::getInstance()->prepare($query_rsCont);
	$rsCont->bindParam(':id', $row_rsTema['conteudo'], PDO::PARAM_INT);
	$rsCont->execute();
	$row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsCont = $rsCont->rowCount();
	DB::close();

	if($row_rsTema["tipo"] == 1) { // produtos
		if($_POST['nome_produto']!='' && $_POST['categoria']!='' && $_POST['produto']!='') {
			$query_rsProd = "SELECT * FROM l_pecas_en WHERE id=:produto";
			$rsProd = DB::getInstance()->prepare($query_rsProd);
			$rsProd->bindParam(':produto', $_POST['produto'], PDO::PARAM_INT);	
			$rsProd->execute();
			$row_rsProd = $rsProd->fetch(PDO::FETCH_ASSOC);
			DB::close();

			if($row_rsProd["imagem2"] && !file_exists("../../../imgs/imgs_news/produtos/".$row_rsProd["imagem2"])) {
				if(file_exists("../../../imgs/produtos/".$row_rsProd["imagem2"])) {
					@copy("../../../imgs/produtos/".$row_rsProd["imagem2"], "../../../imgs/imgs_news/produtos/".$row_rsProd["imagem2"]);
				}
			}

			$insertSQL = "SELECT MAX(id) FROM news_produtos";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->execute();
			$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
			DB::close();
			
			$max_id = $row_rsInsert["MAX(id)"]+1;

			$novidade = 0;
			if(isset($_POST['novidade'])) {
				$novidade = 1;
			}

			$insertSQL = "INSERT INTO news_produtos (id, categoria_id, produto_id, id_tema, tipo, nome, ref, preco, preco_ant, imagem1, link, texto_botao, descricao, novidade) VALUES ('$max_id', :categoria, :produto, :id_tema, '1', :nome, :ref, :preco, :preco_ant, '".$row_rsProd["imagem2"]."', :link, :texto_botao, :descricao, :novidade)";
			$rsInsert = DB::getInstance()->prepare($insertSQL);	
			$rsInsert->bindParam(':categoria', $_POST['categoria'], PDO::PARAM_INT);	
			$rsInsert->bindParam(':produto', $_POST['produto'], PDO::PARAM_INT);	
			$rsInsert->bindParam(':id_tema', $_GET['id'], PDO::PARAM_INT);	
			$rsInsert->bindParam(':nome', $_POST['nome_produto'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':ref', $_POST['ref'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':preco', $_POST['preco'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':preco_ant', $_POST['preco_ant'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':link', $_POST['link_produto'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':texto_botao', $_POST['texto_link_produto'], PDO::PARAM_STR, 5);		
			$rsInsert->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':novidade', $novidade, PDO::PARAM_INT);
			$rsInsert->execute();
			DB::close();

			header("Location: conteudos-produtos.php?id=".$id_tema."&env=1");
		}
	} 
	else { // texto e/ou imagem
		if($_POST['nome']!='') {
			$tipo2 = $_POST['tipo2'];

			$erro = 0;
			$erro2 = 0;
			if($tipo2 == 1 || $tipo2 == 3 || $tipo2 == 4) {
				//Verificar se existe a imagem 1
				if($tipo2 == 1 || $tipo2 == 3) {
					if($_FILES['img']['name']=='')
						$erro = 1;
				}

				//Verificar se existe a imagem 2
				if($tipo2 == 4) {
					if($_FILES['img2']['name']=='')
						$erro = 2;
				}

				if($erro == 0) {
					//Verificar o formato dos ficheiros
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$ext2 = pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION);

					if($_FILES['img']['name']!='' && $ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png") {
						$erro2 = 1;
					}

					if($_FILES['img2']['name']!='' && $ext2 != "jpg" && $ext2 != "jpeg" && $ext2 != "gif" && $ext2 != "png") {
						$erro2 = 1;
					}
				}
			}

			if($erro == 0 && $erro2 == 0) {
				$insertSQL = "SELECT MAX(id) FROM news_produtos";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->execute();
				$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
				DB::close();
				
				$max_id = $row_rsInsert["MAX(id)"]+1;
				
				$descricao=str_replace("</p><p>", "<br />", $_POST['descricao']);
				$descricao=str_replace("</p>", "", $descricao);
				$descricao=str_replace("<p>", "", $descricao);
				$descricao=str_replace("<br>", "<br />", $descricao);
				
				$descricao=trim($descricao);	

				if(strpos($descricao, "<td>") !== false) {
          $descricao = str_replace('<td>', '<td style="font-family: Arial, sans-serif; font-size:14px; color:#525051; line-height:23px; font-weight: normal;">', $descricao);
        }

	      $descricao2=str_replace("</p><p>", "<br />", $_POST['descricao2']);
	      $descricao2=str_replace("</p>", "", $descricao2);
	      $descricao2=str_replace("<p>", "", $descricao2);
	      $descricao2=str_replace("<br>", "<br />", $descricao2);
	      
	      $descricao2=trim($descricao2);

	      if(strpos($descricao2, "<td>") !== false) {
          $descricao2 = str_replace('<td>', '<td style="font-family: Arial, sans-serif; font-size:14px; color:#525051; line-height:23px; font-weight: normal;">', $descricao2);
        }

	      if($tipo2 == 3) {
	        $descricao = "";
	        $descricao2 = "";
	      }

	      $separador=0;
				if(isset($_POST['separador'])) $separador=1;

	      $insertSQL = "INSERT INTO news_produtos (id, id_tema, tipo, tipo2, nome, titulo, titulo2, descricao, descricao2, tipo_link1, tipo_link2, link, link2, texto_botao, texto_botao2) VALUES ('$max_id', :id_tema, '2', :tipo2, :nome, :titulo, :titulo2, :descricao, :descricao2, :tipo_link1, :tipo_link2, :link, :link2, :texto_botao, :texto_botao2)";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':id_tema', $id_tema, PDO::PARAM_INT);	
				$rsInsert->bindParam(':tipo2', $tipo2, PDO::PARAM_INT);	
				$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	
				$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	
				$rsInsert->bindParam(':titulo2', $_POST['titulo2'], PDO::PARAM_STR, 5);	
				$rsInsert->bindParam(':descricao', $descricao, PDO::PARAM_STR, 5);
				$rsInsert->bindParam(':tipo_link1', $_POST['tipo_link'], PDO::PARAM_INT);
				$rsInsert->bindParam(':link', $_POST['link_1'], PDO::PARAM_STR, 5);		
	      $rsInsert->bindParam(':descricao2', $descricao2, PDO::PARAM_STR, 5);
	      $rsInsert->bindParam(':tipo_link2', $_POST['tipo_link2'], PDO::PARAM_INT);
	      $rsInsert->bindParam(':link2', $_POST['link_2'], PDO::PARAM_STR, 5);  
	      $rsInsert->bindParam(':texto_botao', $_POST['texto_link_1'], PDO::PARAM_STR, 5);   
	      $rsInsert->bindParam(':texto_botao2', $_POST['texto_link_2'], PDO::PARAM_STR, 5); 
				$rsInsert->execute();
				DB::close();  

				//Imagens
				require("../resize_image.php");
		
				$imagem="";	
				$imagem2="";		
				
				$imgs_dir = "../../../imgs/imgs_news/produtos";
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
						if($_POST['file_db_'.$contaimg]) {
							$nome_file = $_POST['file_db_'.$contaimg];
						}
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
				$imagem2 = $nome_file2;

				//IMAGEM 1
				if($_FILES['img']['name']!='') {
					if($imagem!="" && file_exists("../../../imgs/imgs_news/produtos/".$imagem)){
										
						if($tipo2 == 1) {
              $maxW=600;
              $maxH=1500;
            } 
            else if($tipo2 == 4) {
              $maxW=280;
              $maxH=500;
            } 
            else {
              $maxW=600;
              $maxH=1500;
            }
						
						$sizes=getimagesize("../../../imgs/imgs_news/produtos/".$imagem);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH) {			
							$img1=new Resize("../../../imgs/imgs_news/produtos/", $imagem, $imagem, $maxW, $maxH);
							$img1->resize_image();
						}
					}		

					$insertSQL = "UPDATE news_produtos SET imagem1=:imagem1 WHERE id_tema=:id_tema AND id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':id_tema', $id_tema, PDO::PARAM_INT);
					$rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);
					$rsInsert->execute();
					DB::close();
				}

				//IMAGEM 2
				if($_FILES['img2']['name']!='') {
					if($imagem2!="" && file_exists("../../../imgs/imgs_news/produtos/".$imagem2)){
										
						if($tipo2 == 1) {
              $maxW=600;
              $maxH=1500;
            } 
            else if($tipo2 == 4) {
              $maxW=280;
              $maxH=500;
            } 
            else {
              $maxW=600;
              $maxH=1500;
            }
						
						$sizes=getimagesize("../../../imgs/imgs_news/produtos/".$imagem2);
						
						$imageW=$sizes[0];
						$imageH=$sizes[1];
						
						if($imageW>$maxW || $imageH>$maxH) {			
							$img1=new Resize("../../../imgs/imgs_news/produtos/", $imagem2, $imagem2, $maxW, $maxH);
							$img1->resize_image();
						}
					}		

					$insertSQL = "UPDATE news_produtos SET imagem2=:imagem2 WHERE id_tema=:id_tema AND id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':imagem2', $imagem2, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':id_tema', $id_tema, PDO::PARAM_INT);	
					$rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);	
					$rsInsert->execute();
					DB::close();
				}
			}

			if($erro == 2)
				header("Location: conteudos-produtos-insert.php?id=".$id_tema."&erro=3");
			else if($erro == 1)
				header("Location: conteudos-produtos-insert.php?id=".$id_tema."&erro=2");
			else if($erro2 == 1)
				header("Location: conteudos-produtos-insert.php?id=".$id_tema."&erro=1");
			else
				header("Location: conteudos-produtos.php?id=".$id_tema."&env=1");
		}
	}
}

$query_rsTema = "SELECT * FROM news_temas WHERE id=:id";
$rsTema = DB::getInstance()->prepare($query_rsTema);
$rsTema->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$rsTema->execute();
$row_rsTema = $rsTema->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTema = $rsTema->rowCount();
DB::close();

$query_rsCont = "SELECT * FROM news_conteudo WHERE id=:id";
$rsCont = DB::getInstance()->prepare($query_rsCont);
$rsCont->bindParam(':id', $row_rsTema['conteudo'], PDO::PARAM_INT);
$rsCont->execute();
$row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCont = $rsCont->rowCount();
DB::close();

$query_rsCategorias = "SELECT id, nome FROM l_categorias_en WHERE cat_mae = 0 ORDER BY nome ASC";
$rsCategorias = DB::getInstance()->prepare($query_rsCategorias);
$rsCategorias->execute();
$totalRows_rsCategorias = $rsCategorias->rowCount();
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
      <h3 class="page-title"> Newsletter » <?php echo $row_rsCont["nome"] ?> » Blocos » Conteudos <small>criar novo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos.php?id=<?php echo $row_rsCont['id']; ?>">Conteúdos</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos-blocos.php?id=<?php echo $row_rsTema["conteudo"]; ?>">Blocos </a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="conteudos-produtos.php?id=<?php echo $row_rsTema['id']; ?>"><?php echo $row_rsTema["nome"] ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
        	<form id="frm_conteudo_produtos" name="frm_conteudo_produtos" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
        		<input type="hidden" name="tipo_tema" id="tipo_tema" value="<?php echo $row_rsTema["tipo"]; ?>">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletter - <?php echo $row_rsCont["nome"] ?> - Blocos - Conteudos - Novo registo</div>
	              <div class="form-actions actions btn-set">
	                <button type="button" name="back" class="btn default" onClick="document.location='conteudos-produtos.php?id=<?php echo $_GET['id']; ?>'"><i class="fa fa-angle-left"></i> Voltar</button>
	                <button type="button" class="btn default" onClick="document.getElementById('btn_limpar').click()"><i class="fa fa-eraser"></i> Limpar</button>
	               	<button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar </button>
	              </div>
	            </div>
	            <div class="portlet-body">
	              <div class="form-body">
	              	<div class="alert alert-danger display-hide">
	                  <button class="close" data-close="alert"></button>
	                  Preencha todos os campos obrigatórios. 
	                </div>
	                <?php if(isset($_GET['erro']) && $_GET['erro'] == 1) { ?>
	                	<div class="alert alert-danger display-show">
		                  <button class="close" data-close="alert"></button>
		                  Tipo de imagem não suportado. Apenas ".jpg", ".png" ou ".gif".
		                </div>
	                <?php } ?>
	                <?php if(isset($_GET['erro']) && $_GET['erro'] == 2) { ?>
	                	<div class="alert alert-danger display-show">
		                  <button class="close" data-close="alert"></button>
		                  Deve selecionar uma imagem.
		                </div>
	                <?php } ?>
	                <?php if(isset($_GET['erro']) && $_GET['erro'] == 3) { ?>
	                	<div class="alert alert-danger display-show">
		                  <button class="close" data-close="alert"></button>
		                  Deve selecionar duas imagens.
		                </div>
	                <?php } ?>
	                <?php if($row_rsTema["tipo"] == 1) { // produtos ?>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="categoria">Categoria: <span class="required"> * </span></label>
                      <div class="col-md-8">
                        <select class="form-control select2me" id="categoria" name="categoria" onChange="carregaProdutos(this.value);">
                          <option value="">Selecionar...</option>
                          <?php if($totalRows_rsCategorias > 0) { ?>
	                          <?php while($row_rsCategorias = $rsCategorias->fetch()) { ?>
	                            <option value="<?php echo $row_rsCategorias['id']; ?>"><?php echo $row_rsCategorias['nome']; ?></option>
	                            <?php
	                            $query_rsCategorias2 = "SELECT id, nome FROM l_categorias_en WHERE cat_mae = '".$row_rsCategorias['id']."' ORDER BY nome ASC";
															$rsCategorias2 = DB::getInstance()->prepare($query_rsCategorias2);
															$rsCategorias2->execute();
															$totalRows_rsCategorias2 = $rsCategorias2->rowCount();
															DB::close();

															if($totalRows_rsCategorias2 > 0) {
																while($row_rsCategorias2 = $rsCategorias2->fetch()) { ?>
																	<option value="<?php echo $row_rsCategorias2['id']; ?>"><?php echo $row_rsCategorias['nome']." » ".$row_rsCategorias2['nome']; ?></option>
																<?php }
															}
	                          }
	                        } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="produto">Produto: <span class="required"> * </span></label>
                      <div class="col-md-8">
                      	<div id="div_produtos">
	                        <select class="form-control select2me" id="produto" name="produto" onChange="carregaDadosProd(this.value);">
	                          <option value="">Selecionar...</option>
	                        </select>
	                      </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="nome_produto">Nome: <span class="required"> * </span></label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" name="nome_produto" id="nome_produto" value="<?php echo $_POST['nome_produto']; ?>" data-required="1">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="ref">Referência: </label>
                      <div class="col-md-3">
                        <input type="text" class="form-control" name="ref" id="ref" value="<?php echo $_POST['ref']; ?>">
                      </div>
                    </div>
                    <div id="div_outlet_prom" class="form-group">
                      <label class="col-md-2 control-label" for="preco">Preço: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="preco" id="preco" value="<?php echo $_POST['preco']; ?>">
                          <span class="input-group-addon">&pound;</span> 
                        </div>
                      </div>
                      <label class="col-md-2 control-label" for="preco_ant">Preço ant.: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="preco_ant" id="preco_ant" value="<?php echo $_POST['preco_ant']; ?>">
                          <span class="input-group-addon">&pound;</span> 
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="descricao">Descricao: </label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" name="descricao" id="descricao" value="<?php echo $_POST['descricao']; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="link_produto">Link: </label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" name="link_produto" id="link_produto" value="<?php echo $_POST['link_produto']; ?>" readonly>
                      </div>
                    </div>
                    <div class="form-group">
	                    <label class="col-md-2 control-label" for="novidade">É Novidade? </label>
	                    <div class="col-md-8" style="padding-top: 8px;">
	                      <input type="checkbox" class="form-control" name="novidade" id="novidade" value="1"/>
	                    </div>
	                  </div>
	                <?php } else { // texto e/ou imagem ?>
                  	<div class="row">
                  		<div class="col-md-12">
		                  	<div class="form-group">
		                      <label class="col-md-2 control-label">Tipo: <span class="required"> * </span></label>
		                      <div class="col-md-9">
		                        <div class="md-radio-inline">
		                          <div class="md-radio">
		                            <input type="radio" id="tipo2_1" name="tipo2" class="md-radiobtn" value="1" checked>
		                            <label for="tipo2_1"> <span></span> <span class="check"></span> <span class="box"></span> Texto e Imagem </label>
		                          </div>
		                          <div class="md-radio">
		                            <input type="radio" id="tipo2_2" name="tipo2" class="md-radiobtn" value="2">
		                            <label for="tipo2_2"> <span></span> <span class="check"></span> <span class="box"></span> Texto </label>
		                          </div>
		                          <div class="md-radio">
		                            <input type="radio" id="tipo2_3" name="tipo2" class="md-radiobtn" value="3">
		                            <label for="tipo2_3"> <span></span> <span class="check"></span> <span class="box"></span> Imagem </label>
		                          </div>
		                          <div class="md-radio">
		                            <input type="radio" id="tipo2_4" name="tipo2" class="md-radiobtn" value="4">
		                            <label for="tipo2_4"> <span></span> <span class="check"></span> <span class="box"></span> 2 Textos/Imagens </label>
		                          </div>
		                          <div class="md-radio">
		                            <input type="radio" id="tipo2_5" name="tipo2" class="md-radiobtn" value="5">
		                            <label for="tipo2_5"> <span></span> <span class="check"></span> <span class="box"></span> 2 Imagens </label>
		                          </div>
		                          <div class="md-radio">
		                            <input type="radio" id="tipo2_6" name="tipo2" class="md-radiobtn" value="6">
		                            <label for="tipo2_6"> <span></span> <span class="check"></span> <span class="box"></span> Botão </label>
		                          </div>
		                        </div>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label class="col-md-2 control-label" for="nome">Nome do Conteúdo: <span class="required"> * </span></label>
		                      <div class="col-md-8">
		                        <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
		                      </div>
		                    </div>
		                  </div>
	                  </div>
                   	<hr>
                   	<div class="row">
                   		<div class="col-md-7">
                   			<div id="div_titulo_1" class="form-group">
		                      <label class="col-md-3 control-label" for="titulo">Título: </label>
		                      <div class="col-md-8">
		                        <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>" >
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label class="col-md-3 control-label">Tipo de Link: </label>
		                      <div class="col-md-8">
		                        <div class="md-radio-inline">
		                          <div class="md-radio">
		                            <input type="radio" id="tipo_link1_1" name="tipo_link" class="md-radiobtn" value="1" checked>
		                            <label for="tipo_link1_1"> <span></span> <span class="check"></span> <span class="box"></span> URL </label>
		                          </div>
		                          <div class="md-radio">
		                            <input type="radio" id="tipo_link1_2" name="tipo_link" class="md-radiobtn" value="2">
		                            <label for="tipo_link1_2"> <span></span> <span class="check"></span> <span class="box"></span> Email </label>
		                          </div>
		                        </div>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label class="col-md-3 control-label" for="link_1" id="link_1_label">Link: </label>
		                      <div class="col-md-8">
		                        <input type="text" class="form-control" name="link_1" id="link_1" value="<?php echo $_POST['link_1']; ?>">
		                      </div>
		                    </div>
		                    <div id="div_texto_link_1" class="form-group">
		                      <label class="col-md-3 control-label" for="texto_link_1">Texto Botão: </label>
		                      <div class="col-md-8">
		                        <input type="text" class="form-control" name="texto_link_1" id="texto_link_1" value="<?php echo $_POST['texto_botao']; ?>">
		                      </div>
		                    </div>
		                    <div id="div_descricao_1" class="form-group">
		                      <label class="col-md-3 control-label" for="descricao">Descrição: </label>
		                      <div class="col-md-8">
		                        <textarea class="form-control" name="descricao" id="descricao"><?php echo $_POST['descricao']; ?></textarea>
		                      </div>
		                    </div>
                   		</div>
                   		<div class="col-md-5">
                   			<div id="div_imagem_1" class="form-group">
			                  	<label class="col-md-3 control-label" style="text-align:right">Imagem <span class="required"> * </span><br>
		                        Largura Máxima: <span id="tam_imagem_1">600 px</span></label>
		                      <div class="col-md-8">
		                        <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/imgs_news/produtos/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
		                          <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=sem+imagem" alt=""/> </div>
		                          <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
		                            <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/imgs_news/produtos/".$row_rsP['imagem1'])) { ?>
		                            	<a href="../../../imgs/imgs_news/produtos/<?php echo $row_rsP['imagem1']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/imgs_news/produtos/<?php echo $row_rsP['imagem1']; ?>"></a>
		                            <?php } ?>
		                          </div>
		                          <div> <span class="btn default btn-file"> <span class="fileinput-new"> Selecionar Imagem</span> <span class="fileinput-exists"> Alterar </span>
		                            <input id="upload_campo" type="file" name="img">
		                            </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> Remover </a> </div>
		                        </div>
		                        <div class="clearfix margin-top-10"> <span class="label label-danger">Nota!</span> <span>Esta funcionalidade somente é permitida nas últimas versões do seu browser.</span> </div>
		                      </div>
		                    </div>
                   		</div>
                   	</div>
                   	<div id="div_2_textos">
	                   	<hr>
	                   	<div class="row">
	                   		<div class="col-md-7">
	                   			<div id="div_titulo_2" class="form-group">
			                      <label class="col-md-3 control-label" for="titulo2">Título 2: </label>
			                      <div class="col-md-8">
			                        <input type="text" class="form-control" name="titulo2" id="titulo2" value="<?php echo $_POST['titulo2']; ?>" >
			                      </div>
			                    </div>
			                    <div class="form-group">
			                      <label class="col-md-3 control-label">Tipo de Link 2: </label>
			                      <div class="col-md-8">
			                        <div class="md-radio-inline">
			                          <div class="md-radio">
			                            <input type="radio" id="tipo_link2_1" name="tipo_link2" class="md-radiobtn" value="1" checked>
			                            <label for="tipo_link2_1"> <span></span> <span class="check"></span> <span class="box"></span> URL </label>
			                          </div>
			                          <div class="md-radio">
			                            <input type="radio" id="tipo_link2_2" name="tipo_link2" class="md-radiobtn" value="2">
			                            <label for="tipo_link2_2"> <span></span> <span class="check"></span> <span class="box"></span> Email </label>
			                          </div>
			                        </div>
			                      </div>
			                    </div>
			                    <div class="form-group">
			                      <label class="col-md-3 control-label" for="link_2" id="link_2_label">Link 2: </label>
			                      <div class="col-md-8">
			                        <input type="text" class="form-control" name="link_2" id="link_2" value="<?php echo $_POST['link_2']; ?>">
			                      </div>
			                    </div>
			                    <div id="div_texto_link_2" class="form-group">
			                      <label class="col-md-3 control-label" for="texto_link_2">Texto Botão 2: </label>
			                      <div class="col-md-8">
			                        <input type="text" class="form-control" name="texto_link_2" id="texto_link_2" value="<?php echo $_POST['texto_botao2']; ?>">
			                      </div>
			                    </div>
			                    <div id="div_descricao_2" class="form-group">
			                      <label class="col-md-3 control-label" for="descricao2">Descrição 2: </label>
			                      <div class="col-md-8">
			                        <textarea class="form-control" name="descricao2" id="descricao2"><?php echo $_POST['descricao2']; ?></textarea>
			                      </div>
			                    </div>
	                   		</div>
	                   		<div class="col-md-5">
	                   			<div id="div_imagem_2" class="form-group">
				                  	<label class="col-md-3 control-label" style="text-align:right">Imagem 2 <span class="required"> * </span><br>
			                        Largura Máxima: <span id="tam_imagem_2">280 px</span> </label>
			                      <div class="col-md-7">
			                        <div class="fileinput fileinput-<?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/imgs_news/produtos/".$row_rsP['imagem2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
			                          <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=sem+imagem" alt=""/> </div>
			                          <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
			                            <?php if($row_rsP['imagem2']!="" && file_exists("../../../imgs/imgs_news/produtos/".$row_rsP['imagem2'])) { ?>
			                            	<a href="../../../imgs/imgs_news/produtos/<?php echo $row_rsP['imagem2']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/imgs_news/produtos/<?php echo $row_rsP['imagem2']; ?>"></a>
			                            <?php } ?>
			                          </div>
			                          <div> <span class="btn default btn-file"> <span class="fileinput-new"> Selecionar Imagem</span> <span class="fileinput-exists"> Alterar </span>
			                            <input id="upload_campo" type="file" name="img2">
			                            </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> Remover </a> </div>
			                        </div>
			                        <div class="clearfix margin-top-10"> <span class="label label-danger">Nota!</span> <span>Esta funcionalidade somente é permitida nas últimas versões do seu browser.</span> </div>
			                      </div>
			                    </div>
	                   		</div>
	                   	</div>
	                  </div>
	                <?php } ?>
	              </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_conteudo_produtos" />
	        </form>
        </div>
      </div>
    </div>
  </div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
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

	$('input[name="tipo_link"]').on('change', function() {
		var val = $('input[name="tipo_link"]:checked').val();

		if(val == 1)
			$('#link_1_label').text('Link:');
		else if(val == 2)
			$('#link_1_label').text('Email:');
	});

	$('#div_2_textos').fadeOut();

	$('input[name=tipo2]:radio').on('change', function() {
		var id = $('input[name=tipo2]:checked').val();
		
		//Texto e Imagem 
		if(id == 1) {
			// $('#div_texto_link_1').fadeOut();
			$('#div_2_textos').fadeOut();

			$('#div_titulo_1').fadeIn();
			$('#div_link_1').fadeIn();
			$('#div_texto_link_1').fadeIn();
			$('#div_descricao_1').fadeIn();
			$('#div_imagem_1').fadeIn();
			$('#tam_imagem_1').text('600 px');
		}
		//Texto
		else if(id == 2) {
			// $('#div_texto_link_1').fadeOut();
			$('#div_2_textos').fadeOut();
			$('#div_imagem_1').fadeOut();

			$('#div_titulo_1').fadeIn();
			$('#div_descricao_1').fadeIn();
			$('#div_texto_link_1').fadeIn();
		}
		// Imagem
		else if(id == 3) {
			$('#div_2_textos').fadeOut();
			$('#div_descricao_1').fadeOut();
			$('#div_texto_link_1').fadeOut();
			$('#div_titulo_1').fadeOut();

			$('#div_imagem_1').fadeIn();
			$('#tam_imagem_1').html('600 px');
		}
		//2 Textos/Imagens
		else if(id == 4) {
			// $('#div_texto_link_1').fadeOut();

			$('#div_2_textos').fadeIn();
			$('#div_titulo_2').fadeIn();
			$('#div_texto_link_2').fadeIn();
			$('#div_descricao_2').fadeIn();
			$('#div_titulo_1').fadeIn();
			$('#div_descricao_1').fadeIn();
			$('#div_texto_link_1').fadeIn();
			$('#div_imagem_1').fadeIn();
			$('#tam_imagem_1').text('280 px');
			$('#div_imagem_2').fadeIn();
			$('#tam_imagem_2').text('280 px');
		}
		//2 Imagens
		else if(id == 5) {
			$('#div_2_textos').fadeIn();

			$('#div_titulo_1').fadeOut();
			$('#div_titulo_2').fadeOut();
			$('#div_texto_link_1').fadeOut();
			$('#div_texto_link_2').fadeOut();
			$('#div_descricao_1').fadeOut();
			$('#div_descricao_2').fadeOut();

			$('#div_imagem_1').fadeIn();
			$('#tam_imagem_1').text('280 px');
			$('#div_imagem_2').fadeIn();
			$('#tam_imagem_2').text('280 px');
		}
		//Botão
		else if(id == 6) {
			$('#div_descricao_1').fadeOut();
			$('#div_titulo_2').fadeOut();
			$('#div_descricao_2').fadeOut();
			$('#div_imagem_1').fadeOut();
			$('#div_imagem_2').fadeOut();
			$('#div_2_textos').fadeOut();

			$('#div_titulo_1').fadeIn();
			$('#div_texto_link_1').fadeIn();
		}
	});
});
</script>
<?php if($row_rsTema["tipo"] == 1) { // produtos ?>
<script type="text/javascript">
function carregaProdutos(cat) {
	$.post("conteudos-produtos-rpc.php", {op: "carregaProdutos", cat: cat}, function(data) {
		document.getElementById('div_produtos').innerHTML = data;  
    $('#produto').select2();            
	});
}

function carregaDadosProd(id) {
	if(id != 0) {
		$.post("conteudos-produtos-rpc.php", {op:"carregaDadosProd", id:id}, function(data) {
			var arrayJson = JSON.parse(data);
			document.getElementById('nome_produto').value = arrayJson.nome;
			document.getElementById('ref').value = arrayJson.ref;
			document.getElementById('preco').value = arrayJson.preco;
			document.getElementById('link_produto').value = arrayJson.url;
		});	
	} 
	else {
		document.getElementById('nome_produto').value = "";
		document.getElementById('ref').value = "";
		document.getElementById('preco').value = "";
		document.getElementById('preco_ant').value = "";
		document.getElementById('link_produto').value = "";
	}
}
</script>
<?php } else { // texto e/ou imagem ?>
<script type="text/javascript">
var wordCountConf1 = {
  showParagraphs: false,
  showWordCount: false,
  showCharCount: true,
  countSpacesAsChars: true,
  countHTML: false,
  maxWordCount: -1,
  maxCharCount: 240
}

CKEDITOR.replace('descricao',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	toolbar : "Basic",
	// extraPlugins: 'wordcount,notification',
 //  wordcount: wordCountConf1
});
CKEDITOR.replace('descricao2',
{
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic",
  // extraPlugins: 'wordcount,notification',
  // wordcount: wordCountConf1
});
<?php } ?>
</script>
</body>
<!-- END BODY -->
</html>