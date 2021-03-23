<?php

$redirect = 1;

require_once('Connections/connADMIN.php');



$query_rsLinguas = "SELECT * FROM linguas WHERE ativo = 1 ORDER BY id ASC";

$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

$rsLinguas->execute();

$row_rsLinguas = $rsLinguas->fetchAll();

$totalRows_rsLinguas = $rsLinguas->rowCount();

$gets = explode("/", str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]));



//unset($gets[1]);

//$gets = array_values($gets);



$encontrou_url = 0; //flag para verificar se já encontrou url válido e não percorrer sempre todas as tabelas

$is_blog = 0; //indica se está dentro do blog ou não



if (ENV == 0 || ENV == 3) { # ENV = localhost

	$pasta=$gets[1]; //lingua

	if($totalRows_rsLinguas>1){

		$count=3;

		$pasta2=$gets[2]; //ficheiro

		$pasta3=$gets[3]; //blog

	}

	else{

		$count=2;

		$pasta2=$gets[1]; //ficheiro

		$pasta3=$gets[2]; //blog

	}

} else if(ENV == 1) { 

	#### LOCAL_PATH SUEZ ####

	$pasta = $gets[5]; //lingua

	if($totalRows_rsLinguas > 1) {

		$count = 7;

		$pasta2 = $gets[6]; //ficheiro

		$pasta3 = $gets[7]; //blog

	}

	else {

		$count = 6;

		$pasta2 = $gets[5]; //ficheiro

		$pasta3 = $gets[6]; //blog

	}

} else if (ENV == 2) { 

	#### ONLINE_PATH PROPOSTA ####

	$pasta = $gets[2]; //lingua

	if($totalRows_rsLinguas > 1) {

		$count = 4;

		$pasta2 = $gets[3]; //ficheiro

		$pasta3 = $gets[4]; //blog

	}

	else {

		$count = 3;

		$pasta2 = $gets[2]; //ficheiro

		$pasta3 = $gets[3]; //blog

	}

}



if(!file_exists($pasta)) {

	//específico para projetos com Blog no site ficará "http://www.website.pt/blog/" | "http://www.website.pt/blog/pt/"

	$blogExists = tableExists(DB::getInstance(), "blog_posts_en");

	if($pasta == "blog" && $blogExists) {

		$is_blog = 1;



		if(count($gets) < $count) {

			header("HTTP/1.1 301 Moved Permanently");

			header("Location: ".$pasta."/");

			exit();

		}



		$count = $count + 1;

		$pasta4 = $pasta;

		$pasta = $pasta2;

		$pasta2 = $pasta3;

	}



	if($totalRows_rsLinguas > 1) {

		if($pasta == "pt" || $pasta == "en") {

			$_SESSION["LANG"] = $pasta;

			$lang = $_SESSION["LANG"];

		}

	}

	

	require_once(ROOTPATH.'linguasLG.php');

	$extensao = $Recursos->Resources["extensao"];



	//tem de incluir porque no connADMIN não carrega

	include_once(ROOTPATH.'helpers/sessions.php');

	include_once(ROOTPATH.'helpers/handle-forms.php');



	if($totalRows_rsLinguas > 1) {

		//Caso o cliente queria passar url's limpos aos clientes, se não tiver uma língua no URL, coloca o pt por defeito e faz um redirecionamento para depois não causar problemas ao mudar entre as línguas.

		if($pasta != "pt" && $pasta != "en") {

			if(count($gets) == $count - 1) {

				if(!empty($_SERVER['QUERY_STRING'])) {

					$pasta .= "?".$_SERVER['QUERY_STRING'];

				}



				if($is_blog == 1) {

					header("HTTP/1.1 301 Moved Permanently");

					header("Location: ".ROOTPATH_HTTP."blog/".$lang."/".$pasta);

				}

				else {

					header("HTTP/1.1 301 Moved Permanently");

					header("Location: ".ROOTPATH_HTTP.$lang."/".$pasta);

				}



				exit();

			}

		}

		

		if($pasta == "pt" || $pasta == "en") {

			if(count($gets) < $count) {

				header("HTTP/1.1 301 Moved Permanently");

				header("Location: ".$pasta."/");

				exit();

			}

			

			if($lang != $pasta) {	

				if($pasta == "en") {

					$id_lang = 2;

				}	

				else {		

					$id_lang = 1;

				}

				

				header("Location: ".$pagina."lg=".$id_lang);

				exit();

			}

		

			if(!$pasta2) {

				if($is_blog == 1) {

					$file_to_include = 'blog-intro.php';

				}

				else {

					$file_to_include = 'index.php';

				}



				$encontrou_url = 1;

			}

		}

		else {

			header('HTTP/1.0 404 Not Found');

			$file_to_include = '404.php';

			$encontrou_url = 1;

			$is_blog = 0;

		}

	}



	$caminho = $pasta2;

	

	//específico para projetos com Blog no site ficará "http://www.website.pt/blog/" | "http://www.website.pt/blog/pt/"

	$blog = '0';

	if($is_blog == 1) {

		$enc_url_blog = 1; //flag para verificar se já encontrou url válido no blog e não percorrer sempre todas as tabelas



		if($caminho == "index.php" || !$caminho) {

			$file_to_include = 'blog-intro.php';

			$enc_url_blog = 0;

		}

		else{

			if($totalRows_rsLinguas > 0) {

				foreach($row_rsLinguas as $lingua) {

					$query_rsProc = "SELECT id, titulo, description, imagem1, url FROM blog_posts_".$lingua['sufixo']." WHERE visivel = '1' AND url='$caminho'";

					$rsProc = DB::getInstance()->prepare($query_rsProc);

					$rsProc->execute();

					$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsProc = $rsProc->rowCount();

					

					if($totalRows_rsProc > 0) {

						$blog = $row_rsProc['id'];



						$tem_share = 1;

						$share_title = $row_rsProc['titulo'];

						$share_url = "blog/".$row_rsProc['url'];

						if($totalRows_rsLinguas > 1) {

							$share_url = "blog/".$lang."/".$row_rsProc['url'];

						}

						$share_desc = strip_tags($row_rsProc['description']);

						$share_img = "blog/posts/".$row_rsProc['imagem1'];



						$enc_url_blog = 0;

						break;

					}

				}

			}

		}



		$encontrou_url = 1;

		$count = $count - 1;

		$caminho = $pasta2;

		$pasta2 = $pasta;

		$pasta = $pasta4;

	}



	$caminho_existe = 0;

	if($caminho && file_exists(ROOTPATH."includes/".$caminho) && $caminho != "index.php" && $encontrou_url == 0) {

		include_once('includes/'.$caminho);

		exit();

	}



	//REDIRECTS PÁGINAS

	$pagina_fixa='';

	if($totalRows_rsLinguas>0 && $encontrou_url==0){

		$pagina_fixa = arraySearch2($GLOBALS['divs_metatags'], 'url' , $caminho);



		if($pagina_fixa != NULL && $pagina_fixa > 0 && file_exists(ROOTPATH."includes/pages/".$GLOBALS['divs_metatags'][$pagina_fixa]['ficheiro'])){		

			$pagina_fixa_meta = $GLOBALS['divs_metatags'][$pagina_fixa]['id'];

			$pagina_fixa = $GLOBALS['divs_metatags'][$pagina_fixa]['ficheiro'];

			$encontrou_url=1;

		}

	}



	//PAGINAS

	$texto_base='0';

	$menu='';

	if($totalRows_rsLinguas>0 && $encontrou_url==0){

		foreach($row_rsLinguas as $rowL) {



			$query_rsProc = "SELECT id, nome, description, imagem1, url, menu FROM paginas_".$rowL['sufixo']." WHERE visivel = '1' AND url=:caminho";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':caminho', $caminho, PDO::PARAM_STR, 5);	

			$rsProc->execute();

			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProc = $rsProc->rowCount();

			DB::close();



			if($totalRows_rsProc>0){

				$texto_base=$row_rsProc['id'];

				$menu = $row_rsProc['menu'];

				$encontrou_url=1;



				$tem_share=1;

				$share_title = $row_rsProc['nome'];

				$share_url = $row_rsProc['url'];

				if($totalRows_rsLinguas>1) $share_url = $lang."/".$row_rsProc['url'];

				$share_desc = $row_rsProc['description'];

				$share_img = "paginas/".$row_rsProc['imagem1'];



				break;

			}



		}

	}

	

	//NOTICIAS

	$noticias = '0';

	$noticiasExists = tableExists(DB::getInstance(), "noticias_en");

	if($totalRows_rsLinguas > 0 && $encontrou_url == 0 && $noticiasExists) {

		foreach($row_rsLinguas as $lingua) {

			$query_rsProc = "SELECT id, nome, resumo, imagem1, url FROM noticias_".$lingua['sufixo']." WHERE visivel = '1' AND url=:caminho";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':caminho', $caminho, PDO::PARAM_STR, 5);	

			$rsProc->execute();

			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProc = $rsProc->rowCount();

			

			if($totalRows_rsProc > 0) {

				$noticias = $row_rsProc['id'];

				$encontrou_url = 1;



				$tem_share = 1;

				$share_title = $row_rsProc['nome'];

				$share_url = $row_rsProc['url'];

				if($totalRows_rsLinguas > 1) {

					$share_url = $lang."/".$row_rsProc['url'];

				}

				$share_desc = strip_tags($row_rsProc['resumo']);

				$share_img = "noticias/".$row_rsProc['imagem1'];



				break;

			}

		}

	}



	//CATEGORIAS

	$categorias = '0';

	$subs_mae = '0';

	$cats_mae = '0';

	$categoriasExists = tableExists(DB::getInstance(), "l_categorias_en");

	if($totalRows_rsLinguas > 0 && $encontrou_url == 0 && $categoriasExists) {

		foreach($row_rsLinguas as $lingua) {

			$query_rsProc = "SELECT id, nome, descricao, imagem1, url, cat_mae FROM l_categorias_".$lingua['sufixo']." WHERE visivel = '1' AND url=:caminho";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':caminho', $caminho, PDO::PARAM_STR, 5);	

			$rsProc->execute();

			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProc = $rsProc->rowCount();

			

			if($totalRows_rsProc > 0) {

				$categorias = $row_rsProc['id'];

				

				if($row_rsProc['cat_mae'] > 0) {

					$cats_mae = $row_rsProc['cat_mae'];

					

					$query_rsMae = "SELECT id, cat_mae FROM l_categorias_".$lingua['sufixo']." WHERE visivel = '1' AND id='$cats_mae'";

					$rsMae = DB::getInstance()->prepare($query_rsMae);

					$rsMae->execute();

					$row_rsMae = $rsMae->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsMae = $rsMae->rowCount();

					

					if($row_rsMae['cat_mae'] > 0) {

						$cats_mae = $row_rsMae['cat_mae'];		

						$subs_mae = $row_rsMae['id'];

					}

				}



				$tem_share = 1;

				$share_title = $row_rsProc['nome'];

				$share_url = $row_rsProc['url'];

				if($totalRows_rsLinguas > 1) {

					$share_url = $lang."/".$row_rsProc['url'];



				}

				$share_desc = strip_tags($row_rsProc['descricao']);

				$share_img = "categorias/".$row_rsProc['imagem1'];

				

				$encontrou_url = 1;

				break;			

			}

		}

	}

	

	//PRODUTOS

	$produtos = '0';

	$cat = '0';

	$subs_prod = '0';

	$cats_prod = '0';

	$url_open = "";

	$produtpsExists = tableExists(DB::getInstance(), "l_pecas_en");

	if($totalRows_rsLinguas > 0 && $encontrou_url == 0) {

		foreach($row_rsLinguas as $lingua) {

			$query_rsProc = "SELECT id, nome, descricao, imagem1, url, visivel, categoria FROM l_pecas_".$lingua['sufixo']." WHERE url=:caminho";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':caminho', $caminho, PDO::PARAM_STR, 5);	

			$rsProc->execute();

			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProc = $rsProc->rowCount();



			if($totalRows_rsProc > 0) {

				if($row_rsProc['visivel'] == 1 || $_SESSION['ADMIN_USER']) {

					$produtos = $row_rsProc['id'];

					$url_open = $row_rsProc['url'];				



					$tem_share = 1;

					$share_title = $row_rsProc['nome'];

					$share_url = $row_rsProc['url'];

					if($totalRows_rsLinguas > 1) {

						$share_url = $lang."/".$row_rsProc['url'];

					}

					$share_desc = strip_tags($row_rsProc['descricao']);

					$share_img = "produtos/".$row_rsProc['imagem1'];



					$encontrou_url = 1;

					

					if($categoriasExists) {

						if(CATEGORIAS == 2) {

							$query_rsCatProd = "SELECT peca_cat.id_categoria FROM l_pecas_categorias AS peca_cat, l_categorias_".$lingua['sufixo']." AS cat WHERE peca_cat.id_peca=:id_peca AND cat.id=peca_cat.id_categoria AND cat.visivel = '1' ORDER BY cat.ordem ASC LIMIT 1";

							$rsCatProd = DB::getInstance()->prepare($query_rsCatProd);

							$rsCatProd->bindParam(':id_peca', $produtos, PDO::PARAM_INT, 5);

							$rsCatProd->execute();

							$row_rsCatProd = $rsCatProd->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsCatProd = $rsCatProd->rowCount();



							if($totalRows_rsCatProd > 0) {

								$cat = $row_rsCatProd['id_categoria'];

							}

						}

						else {

							$cat = $row_rsProc['categoria'];

						}			



						$query_rsCat = "SELECT id, cat_mae FROM l_categorias_".$lingua['sufixo']." WHERE visivel = '1' AND id='$cat'";

						$rsCat = DB::getInstance()->prepare($query_rsCat);

						$rsCat->execute();

						$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsCat = $rsCat->rowCount();



						if($totalRows_rsCat > 0) {							

							if($row_rsCat['cat_mae'] > 0) {

								$cats_prod = $row_rsCat['cat_mae'];

								

								$query_rsMae = "SELECT id, cat_mae FROM l_categorias_".$lingua['sufixo']." WHERE visivel = '1' AND id='$cats_prod'";

								$rsMae = DB::getInstance()->prepare($query_rsMae);

								$rsMae->execute();

								$row_rsMae = $rsMae->fetch(PDO::FETCH_ASSOC);

								$totalRows_rsMae = $rsMae->rowCount();

								

								if($row_rsMae['cat_mae'] > 0) {

									$cats_prod = $row_rsMae['cat_mae'];		

									$subs_prod = $row_rsMae['id'];

								}

							}	

						}

					}

					

					break;

				}

			}

		}

	}

	

	if(!$file_to_include) {

		$file_to_include = 'index.php';

	}

	if(!$meta_id) {

		$meta_id = 1;

	}



	$cat_redirect = 0;

	$sub_redirect = 0;

	$subsub_redirect = 0;



	$place_redirect = 0;



	$pag_redirect = 0;

	$produto_redirect = 0;



	$blog_redirect = 0;



	DB::close();



	if($caminho_existe != '0') {

		$file_to_include = $caminho;

	}

	else if($texto_base != '0') {

		$query_rsMeta = $query_rsMetatags = "SELECT title, description, keywords, url FROM paginas".$extensao." WHERE id = :id";

		$meta_id = $texto_base;



		$pag_redirect = $texto_base;

		$pag_menu_redirect = $menu;

		$file_to_include='paginas.php';

	}

	else if($pagina_fixa != "") {

		$query_rsMeta = $query_rsMetatags = "SELECT title, description, keywords, url FROM metatags".$extensao." WHERE id = :id";

		$meta_id = $pagina_fixa_meta;



		$file_to_include = $pagina_fixa;

	}

	else if($is_blog == 1 && $blog == '0' && $enc_url_blog == 0) {

		$query_rsMeta = $query_rsMetatags = "SELECT * FROM metatags".$extensao." WHERE id = :id";

		$meta_id = 8;

		

		$menu_sel = "blog";

		$file_to_include = 'blog-intro.php';

	}

	else if($is_blog == 1 && $blog != '0') {

		$query_rsMeta = $query_rsMetatags = "SELECT title, description, keywords, url FROM blog_posts".$extensao." WHERE id = :id";

		$meta_id = $blog;

		

		$blog_redirect = $blog;

		$file_to_include = 'blog-detalhe.php';

	}

	else if($noticias != 0) {

		$query_rsMeta = $query_rsMetatags = "SELECT title, description, keywords, url FROM noticias".$extensao." WHERE id = :id";

		$meta_id = $texto_base;



		$id_open = $noticias;

		$file_to_include = 'noticias.php';

	}

	else if($caminho == 'loja' || $caminho == 'store') {

		$query_rsMeta = $query_rsMetatags = "SELECT * FROM metatags".$extensao." WHERE id = :id";

		$meta_id = 6;



		$menu_sel = "produtos";

		$file_to_include = 'produtos.php';

	}

	else if($caminho == 'promocoes' || $caminho == 'promotions') {

		$query_rsMeta = $query_rsMetatags = "SELECT * FROM metatags".$extensao." WHERE id = :id";

		$meta_id = 6;

		//CRIAR METATAGS NA BASE DE DADOS E DAR O ID AQUI!



		$cat_redirect = "-2";



		$menu_sel = "promocoes";

		$file_to_include = 'produtos.php';

	}

	else if($caminho == 'novidades' || $caminho == 'new') {

		$query_rsMeta = $query_rsMetatags = "SELECT * FROM metatags".$extensao." WHERE id = :id";

		$meta_id = 6;

		//CRIAR METATAGS NA BASE DE DADOS E DAR O ID AQUI!



		$cat_redirect = "-1";



		$menu_sel = "novidades";

		$file_to_include = 'produtos.php';

	}

	else if($categorias != '0') {		

		$query_rsMeta = $query_rsMetatags = "SELECT title, description, keywords, url FROM l_categorias".$extensao." WHERE id = :id";

		$meta_id = $categorias;



		$cat_redirect = $categorias;

		$sub_redirect = $cats_mae;

		$subsub_redirect = $subs_mae;



		$menu_sel = "produtos";

		

		$file_to_include = 'produtos.php';

	}

	else if($produtos != '0') {

		$query_rsMeta = $query_rsMetatags = "SELECT title, description, keywords, url FROM l_pecas".$extensao." WHERE id = :id";

		$meta_id = $produtos;



		$id_open = $produtos;

		$produto_redirect = $produtos;



		$cat_redirect = $cat;

		$sub_redirect = $cats_prod;

		$subsub_redirect = $subs_prod;



		//$file_to_include='produtos.php'; //Deixar produtos.php se o pageloader estiver ativo!

		$file_to_include = 'produtos-detalhe.php'; //Deixar produtos-detalhe.php se o pageloader estiver desactivado!

	}

	else if($encontrou_url == 0 || $enc_url_blog == 1) {

		$where_lang = "";

		if(isset($_SESSION["LANG"])) {

			$where_lang = " AND lang='".$_SESSION["LANG"]."'";

		}



		//REDIRECTS GERAIS

		$query_rsRedirect = "SELECT url_new FROM redirects_301 WHERE url_old=:caminho".$where_lang;

		$rsRedirect = DB::getInstance()->prepare($query_rsRedirect);

		$rsRedirect->bindParam(':caminho', $caminho, PDO::PARAM_STR, 5);	

		$rsRedirect->execute();

		$row_rsRedirect = $rsRedirect->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsRedirect = $rsRedirect->rowCount();



		if($totalRows_rsRedirect > 0 && $row_rsRedirect['url_new'] != $caminho) {

			header("HTTP/1.1 301 Moved Permanently");

			header("Location: ".$row_rsRedirect['url_new']);

			exit();

		}

		else {

			header('HTTP/1.0 404 Not Found');

			$file_to_include = $caminho;

		}

	}

}

else {

	require_once('linguasLG.php');

	$extensao = $Recursos->Resources["extensao"];

	

	header('HTTP/1.0 404 Not Found');

	$file_to_include = $caminho;



	DB::close();

}



include_once(ROOTPATH.'includes/index.php');

?>