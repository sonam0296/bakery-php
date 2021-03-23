<?php require_once('../Connections/connADMIN.php'); ?>
<?php

if (isset($_POST['user_rating']) && isset($_POST['user_rating_comment']) && isset($_POST['rating_product_id'])) {

	$user_is_logged = $class_user->isLogged();

    if($user_is_logged != 0){

        $reviwer_id = $row_rsCliente['id'];
        $reviwer_nome = $row_rsCliente['nome'];
        $date_time = date('Y-m-d H:i:s');

        if (isset($_POST['user_rating']) && isset($_POST['user_rating_comment']) && isset($_POST['rating_product_id'])) {

            $insertSQL = "INSERT INTO l_pecas_reviews".$extensao." (product_id, discription, rating, rating_by, reviewer, create_date) VALUES (:product_id, :discription, :rating, :rating_by, :reviewer, :create_date)";

            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':product_id', $_POST['rating_product_id'], PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':discription', $_POST['user_rating_comment'], PDO::PARAM_STR);
            $rsInsert->bindParam(':rating', $_POST['user_rating'], PDO::PARAM_INT);
            $rsInsert->bindParam(':rating_by', $reviwer_id, PDO::PARAM_INT, 5);
            $rsInsert->bindParam(':reviewer', $reviwer_nome, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':create_date', $date_time, PDO::PARAM_STR, 5);  
            $rsInsert->execute();  

            DB::close();

            $rating = $_POST['rating'];
	    	$discription = $_POST['user_rating_comment'];
	    	$class_produtos->reviews_html($rating,$reviwer_nome,$date_time,$discription);
        }
    }else{
        
    }
}

if (isset($_POST['product_id']) && isset($_POST['offset'])) {
	
	$product_id = $_POST['product_id'];
	$offset = $_POST['offset'];
	$limit = 2;
	$reviewsAll = $class_produtos->produto_loadmore_rating($product_id,$offset,$limit);
    foreach ($reviewsAll as $key => $reviews) { 
    	$rating = $reviews['rating'];
    	$reviewer = $reviews['reviewer'];
    	$create_date = $reviews['create_date'];
    	$discription = $reviews['discription'];
    	$class_produtos->reviews_html($rating,$reviewer,$create_date,$discription);
    }

}




if($_POST['op'] == "carregaMarcas") { 
	$categoria = $_POST['categoria'];

	$query_rsMarcas = "SELECT opcoes.id, opcoes.nome FROM port_categorias AS port_cat LEFT JOIN portefolios".$extensao." AS portfolio ON portfolio.id = port_cat.id_portefolio LEFT JOIN l_filt_opcoes".$extensao." AS opcoes ON opcoes.id = portfolio.marca WHERE opcoes.categoria = 1 AND portfolio.visivel = 1 AND port_cat.id_categoria=:id GROUP BY opcoes.id ORDER BY opcoes.nome";
	$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
	$rsMarcas->bindParam(':id', $categoria, PDO::PARAM_INT, 5); 
	$rsMarcas->execute();
	$row_rsMarcas = $rsMarcas->fetchAll(PDO::FETCH_ASSOC);
	$totalRows_rsMarcas = $rsMarcas->rowCount();
	DB::close();

	?>
	<option value="0"><?php echo $Recursos->Resources["marca"]; ?></option>
	<?php foreach($row_rsMarcas as $marcas) { ?>
		<option value="<?php echo $marcas['id']; ?>" <?php if($marca == $marcas["id"]) echo "selected"; ?>><?php echo $marcas["nome"]; ?></option>
	<?php }
}

if($_POST['op'] == "carregaModelos") { 
	$categoria = $_POST['categoria'];
	$marca = $_POST['marca'];

	$query_rsModelos = "SELECT opcoes.id, opcoes.nome FROM port_categorias AS port_cat LEFT JOIN portefolios".$extensao." AS portfolio ON portfolio.id = port_cat.id_portefolio LEFT JOIN l_filt_opcoes".$extensao." AS opcoes ON opcoes.id = portfolio.modelo WHERE opcoes.categoria = 2 AND portfolio.visivel = 1 AND port_cat.id_categoria=:id AND opcoes.marca=:marca GROUP BY opcoes.id ORDER BY opcoes.nome";
	$rsModelos = DB::getInstance()->prepare($query_rsModelos);
	$rsModelos->bindParam(':id', $categoria, PDO::PARAM_INT, 5); 
	$rsModelos->bindParam(':marca', $marca, PDO::PARAM_INT, 5); 
	$rsModelos->execute();
	$row_rsModelos = $rsModelos->fetchAll(PDO::FETCH_ASSOC);
	$totalRows_rsModelos = $rsModelos->rowCount();
	DB::close();

	?>
	<option value="0"><?php echo $Recursos->Resources["modelo"]; ?></option>
	<?php foreach($row_rsModelos as $modelos) { ?>
		<option value="<?php echo $modelos['id']; ?>" <?php if($modelo == $modelos["id"]) echo "selected"; ?>><?php echo $modelos["nome"]; ?></option>
	<?php }
}

if($_POST['op'] == "carrega_menu") { 
		$id = $_POST['id'];
		
		// echo "<pre>";
		// print_r ($$id);
		// echo "</pre>";
	$market = 1;
	if(isset($_SESSION["SITE_market"]) && !empty($_SESSION["SITE_market"])){
		$market = $_SESSION["SITE_market"];
	}
	


	// $row_rsCategorias = $GLOBALS['divs_categorias'][$id]['subs'];
	// $array = $GLOBALS['divs_categorias'][$id]['info'];

	// $query_rsProdutos = "SELECT dest.* FROM destaques_menu".$extensao." AS dest LEFT JOIN destaques_menu_categorias AS cats ON dest.id = cats.destaque WHERE dest.visivel = 1 AND cats.categoria=:id";
	// $rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	// $rsProdutos->bindParam(':id', $id, PDO::PARAM_INT, 5); 
	// $rsProdutos->execute();
	// $row_rsProdutos = $rsProdutos->fetch(PDO::FETCH_ASSOC);
	// $totalRows_rsProdutos = $rsProdutos->rowCount();
	// DB::close();

	//$row_rsCategorias = $GLOBALS['divs_categorias'];

	$row_rsCategorias = $GLOBALS['divs_categorias'][$id]['subs'];

	$query_rsImagem = "SELECT imagem1 FROM l_categorias".$extensao." WHERE visivel = 1 AND cat_mae = 0 AND imagem1 != ' ' AND imagem1 IS NOT NULL ORDER BY ordem ASC LIMIT 1";
	$rsImagem = DB::getInstance()->prepare($query_rsImagem);
	$rsImagem->execute();
	$row_rsImagem = $rsImagem->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsImagem = $rsImagem->rowCount();
	?>
	<?php if(!empty($row_rsCategorias)) { ?>
		<div class="div_100" style="height: 100%;">
			<div class="collapse" style="height: 100%;">
			    <div class="row column" style="height: 100%;">
		        	<div class="collapse div_100 align-stretch menu_desk_bg" style="height: 100%;">
		        		<div class="column small-12 medium-10 divs" id="menu_rpc_1">
		        			<div class="">
		               			<div class="collapse">
						            <div class="column small-12">
						                <ul class="menu_categorias">
					                    <?php foreach($row_rsCategorias as $categorias) { 
				                        $subs = $categorias['subs'];
				                        //if (!empty($subs)) {
					                        if($categorias['info']) {
					                          $categorias = $categorias['info'];
					                        }
						                    ?>
						                    <li>
					                        <a data-id="<?php echo $categorias['id']; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$categorias['url']; ?>"><?php echo $categorias['nome'] ?></a>
						                        		<?php $test = 1; ?>
						                        <?php if (!empty($subs) && $test ==0): ?>
						                        	<ul class="child-menu-item">
							                            <?php foreach($subs as $sub) { 
							                              if($sub['info']) {
							                                $sub = $sub['info'];
							                              }
								                            ?>
								                            <li><a data-id="<?php echo $sub['id']; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$sub['url']; ?>"><?php echo $sub['nome']; ?></a></li>
							                            <?php } ?>
							                        </ul>	
						                        <?php endif ?>
						                    </li>
					                    <?php //} 
					                } ?>
						                </ul>
						            </div>
						        </div>
			            	</div>
		            	</div>
		            	<div class="column small-12 medium-3 medium-offset-1 divs">
							<div <?php /*class="menu_desk_scroll"*/ ?>>
								<?php if($totalRows_rsProdutos > 0) {
									$img = "elem/geral.svg";
							    if($row_rsProdutos['imagem1'] && file_exists(ROOTPATH.'imgs/destaques/'.$row_rsProdutos['imagem1'])) {
						        $img = "destaques/".$row_rsProdutos['imagem1'];
							    }
									?>
									<div class="div_100" id="novidades" style="margin: 0">
										<article class="novidades_divs">
									    <figure>
									    	<div class="div_100 img_cont">
							            <div class="img has_bg has_mask icon-mais lazy" data-src="<?php echo $img; ?>">
						                <?php echo getFill('destaques'); ?> 
							            </div>  
								        </div>
								        <figcaption class="absolute info text-center">
								        	<div>
						                <h6 class="list_tit"><?php echo $row_rsProdutos['titulo']; ?></h6>
						                <div class="list_txt"><?php if($row_rsProdutos['subtitulo']) echo $row_rsProdutos['subtitulo']; else echo "&nbsp;"; ?></div>
							            </div>
								        </figcaption>
								        <?php if($array['url']) { ?><a href="<?php echo ROOTPATH_HTTP_LANG.$array['url']; ?>" class="linker"></a><?php } ?>
									    </figure>
										</article>
									</div>
								<?php } ?>
							</div>
		            	</div>
		          	</div>
			    </div>
			</div>
		</div>
	<?php } 
}

if($_POST['op'] == "carrega_menu_cats") { 
	$id = $_POST['id'];
	$subid = $_POST['subid'];
	$remove_sub = 0;

	if($subid > 0) {
		$query_rsProdutos = "SELECT * FROM l_pecas".$extensao." WHERE visivel = '1' AND categoria = '$subid' GROUP BY id ORDER BY ordem ASC";
		$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
		$rsProdutos->execute();
		$row_rsProdutos = $rsProdutos->fetchAll();
		$totalRows_rsProdutos = $rsProdutos->rowCount();
		DB::close();
		
		$array = $row_rsProdutos;
		$remove_sub = 1;
	}
	else if($id > 0) {
		$array = $GLOBALS['divs_categorias'][$id]['subs'];
	}
	
	if(!empty($array)) {
    foreach($array as $categorias) {
    	if(!empty($categorias['info'])) {
    		$categorias = $categorias['info'];
    	}
    	?>
			<a data-id="<?php echo $id; ?>" <?php /*data-subid="<?php echo $categorias['id']; ?>"*/ ?> href="<?php echo ROOTPATH_HTTP_LANG.$categorias['url']; ?>" class="sub list_subtit icon-right<?php if($categorias['id'] == $categoria) echo " active"; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/produtos.php" data-ajaxTax="<?php echo $categorias['id']; ?>" data-remote="false"><?php echo $categorias['nome']; ?></a>
  	<?php }
  }
}

if($_POST['op'] == "carrega_menu_dests") {
	$id = $_POST['id'];
	$array = $GLOBALS['divs_categorias'][$id]['info'];
	
	$query_rsProdutos = "SELECT dest.* FROM destaques_menu".$extensao." AS dest LEFT JOIN destaques_menu_categorias AS cats ON dest.id = cats.destaque WHERE dest.visivel = 1 AND cats.categoria=:id";
	$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	$rsProdutos->bindParam(':id', $_POST['id'], PDO::PARAM_INT, 5); 
	$rsProdutos->execute();
	$row_rsProdutos = $rsProdutos->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsProdutos = $rsProdutos->rowCount();
	DB::close();

	if($totalRows_rsProdutos > 0) {
		$img = "elem/geral.svg";
    if($row_rsProdutos['imagem1'] && file_exists(ROOTPATH.'imgs/destaques/'.$row_rsProdutos['imagem1'])) {
      $img = "destaques/".$row_rsProdutos['imagem1'];
    }
		?>
		<div class="div_100" id="novidades" style="margin: 0">
			<article class="novidades_divs">
		    <figure>
		    	<div class="div_100 img_cont">
            <div class="img has_bg has_mask icon-mais lazy" data-src="<?php echo $img; ?>">
              <?php echo getFill('destaques'); ?> 
            </div>  
	        </div>
	        <figcaption class="absolute info text-center">
	        	<div>
              <h6 class="list_tit"><?php echo $row_rsProdutos['titulo']; ?></h6>
              <div class="list_txt"><?php if($row_rsProdutos['subtitulo']) echo $row_rsProdutos['subtitulo']; else echo " "; ?></div>
            </div>
	        </figcaption>
	        <?php if($array['url']) { ?><a href="<?php echo ROOTPATH_HTTP_LANG.$array['url']; ?>" class="linker"></a><?php } ?>
		    </figure>
			</article>
		</div>
	<?php }
}

if($_POST['op'] == "carrega_menu_pesquisa") { 
	$pesq = urldecode($_POST['search']);

	if(!$pesq) {
    exit();
	}

	$limit = 10;
	?>
	<div class="row" style="display: none; height: 100%;">
    <div class="column">
    	<div class="row collapse menu_desk_bg" style="height: 100%;">
    		<div class="column small-12">
	  			<div class="menu_desk_scroll">
	          <?php include_once(ROOTPATH.'includes/pesquisa-list.php'); ?>
	        </div>
        </div>
      </div>
    </div>
	</div>
	<?php
}

if($_POST['op'] == "fileUpload") {
  $verifyToken = md5('unique_salt'.$_POST['timestamp']);
  $targetFolder = $_POST['folder']; // Relative to the root
  $allowedSize = $_POST['allowedSize']; // Relative to the root

  if(!empty($_FILES) && $_POST['token'] == $verifyToken) {
    $success_upl = fileUpload($targetFolder, $_FILES['file'], '', '', $allowedSize);

    if(is_array($success_upl) && !empty($success_upl)) {
      echo json_encode($success_upl);
    } 
    else {
      echo $success_upl;
    }
  }
}

if($_POST['op'] == "fetchLang") {	
	$what = $_POST['what']; 

	if($what == "all") {
		$array = $Recursos->Resources;
		
		//ENCONDING PARA ARRAYS ASSOCIATIVOS.
		array_walk_recursive( $array, 'check_value');
		echo json_encode($array);
		exit();
	}
	else if($what == "extensao") {
		echo $extensao;
		exit();
	}
	else {
		echo $Recursos->Resources[$what];
		exit();	
	}
}

if($_POST['op'] == "carrega_videoBanner") {
	$video_link = $_POST['link'];

	if($video_link) {
		$class = "";
			
		if(strstr($video_link, "youtube") || strstr($video_link, "youtu.be")) {
			$class = " youtube full";
		}
		else if(strstr($video_link, "vimeo")) {
			$class = " vimeo full";
		}
		else{
			$class = "iframe";
		}
		?>
		<img src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/fill_video.gif" width="100%" style="max-width: 1500px;"/>
	  <?php if($class == "iframe") { ?>
      <iframe class="video_frame absolute" src="<?php echo $video_link; ?>" allowfullscreen width="854" height="480" frameborder="0"></iframe>
	  <?php } else { ?>
      <div class="video_frame absolute<?php echo $class; ?>" data-vid="<?php echo $video_link; ?>"></div>
	  <?php }
	}
}

if($_POST['op'] == "carregaMapa") {
	$query_rsContactos = "SELECT * FROM contactos".$extensao;
	$rsContactos = DB::getInstance()->query($query_rsContactos);
	$row_rsContactos = $rsContactos->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsContactos = $rsContactos->rowCount();
	DB::close();
	
	echo $row_rsContactos['mapa'];
}

if($_POST['op'] == "fecha_popup") {
	
	$query_rsPopUp = "SELECT * FROM config WHERE id='1'";
	$rsPopUp = DB::getInstance()->prepare($query_rsPopUp);	
	$rsPopUp->execute();
	$row_rsPopUp = $rsPopUp->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsPopUp = $rsPopUp->rowCount();
	DB::close();
	if($row_rsPopUp['tipo_popup'] == 1) {
		$_SESSION["popup_closed"] = 1;
	}
	else {
		$_SESSION["popup_closed"] = "";
	}
}

if($_POST['op'] == 'allowCookies' ) {
	setcookie("allowCookies", 1, time()+3600*24*30*12*2, '/', '', $cookie_secure, true);
}

if($_POST['op'] == 'carrega_morada') {
	$id = $_POST['id'];
	$tipo = $_POST['tipo'];

	if($id > 0) {
		$query_rsSelect = "SELECT * FROM clientes_moradas WHERE id = '$id'";
		$rsSelect = DB::getInstance()->prepare($query_rsSelect);
		$rsSelect->execute();
		$row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);
		DB::close();

		echo $row_rsSelect['nome']."##".$row_rsSelect['morada']."##".$row_rsSelect['localidade']."##".$row_rsSelect['distrito']."##".$row_rsSelect['cod_postal']."##".$row_rsSelect['pais'];
	}
	//Se o valor for 0, vamos buscar a morada principal para o comprar.php
	else {
		$row_rsCliente = $class_user->isLogged();

		$query_rsCliente = "SELECT id, morada, cod_postal, localidade, distrito, pais FROM clientes WHERE id = '".$row_rsCliente['id']."'";
		$rsCliente = DB::getInstance()->prepare($query_rsCliente);
		$rsCliente->execute();
		$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
		DB::close();

		echo $row_rsCliente['id']."##".$row_rsCliente['morada']."##".$row_rsCliente['localidade']."##".$row_rsCliente['distrito']."##".$row_rsCliente['cod_postal']."##".$row_rsCliente['pais'];
	}
}

if($_POST['op'] == 'guarda_morada') {
	$id = $_POST['id'];
	$nome = utf8_decode($_POST['nome']);
	$morada = utf8_decode($_POST['morada']);
	$cod_postal = utf8_decode($_POST['cod_postal']);
	$localidade = utf8_decode($_POST['localidade']);
	$distrito = utf8_decode($_POST['distrito']);
	$pais = utf8_decode($_POST['pais']);

	$query_rsUpdate = "UPDATE clientes_moradas SET nome=:nome, morada=:morada, cod_postal=:cod_postal, localidade=:localidade, distrito=:distrito, pais=:pais WHERE id=:id";
	$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
	$rsUpdate->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
	$rsUpdate->bindParam(':morada', $morada, PDO::PARAM_STR, 5);
	$rsUpdate->bindParam(':cod_postal', $cod_postal, PDO::PARAM_STR, 5);
	$rsUpdate->bindParam(':localidade', $localidade, PDO::PARAM_STR, 5);
	$rsUpdate->bindParam(':distrito', $distrito, PDO::PARAM_STR, 5);
	$rsUpdate->bindParam(':pais', $pais, PDO::PARAM_INT);
	$rsUpdate->bindParam(':id', $id, PDO::PARAM_INT);
	$rsUpdate->execute();
	DB::close();
}

if($_POST['op'] == 'remover_morada') {
	$id = $_POST['id'];

	$query_rsDelete = "DELETE FROM clientes_moradas WHERE id = '$id'";
	$rsDelete = DB::getInstance()->prepare($query_rsDelete);
	$rsDelete->execute();
	DB::close();
}

?>