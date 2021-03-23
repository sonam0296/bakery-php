<?php

//defini��es para o body

$body_info="page-header-fixed page-sidebar-fixed page-footer-fixed";



//encriptar password

if(!function_exists('createSalt')) {

	function createSalt() {

		$text = md5(uniqid(rand(), TRUE));

		return substr($text, 0, 3);

	}

}



//VERIFICA NOME

if(!function_exists('verifica_nome')) {

	function verifica_nome($nome){

		$strlogin = $nome;

		$caracteres = array('�','�','"','�','�','�','�',',',';','/','<','>',':','?','~','^',']','}','�','`','[','{','=','+','-',')','\\','(','*','&','�','%','$','#','@','!','|','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�',' ','\'','�','�','�','�','�','�','�','�','�','�',',','�','�','�', '\'', '"','�','�','�','�','�','�','�','�','	');

		

		for ($i = 0;$i<count($caracteres);$i++){

			

			if($caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "a", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "o", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "e", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "c", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "i", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "u", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "n", $strlogin);

			}elseif($caracteres[$i]=="�" || $caracteres[$i]=="�"){

				$strlogin=str_replace($caracteres[$i], "ae", $strlogin);

			}else{

				$strlogin=str_replace($caracteres[$i], "-", $strlogin);

			}



		}

		if($strlogin[(strlen($strlogin)-1)]=='-'){

			$strlogin=substr($strlogin, 0, strlen($strlogin)-1);

		}

		$strlogin=str_replace("----", "-", $strlogin);

		$strlogin=str_replace("---", "-", $strlogin);

		$strlogin=str_replace("--", "-", $strlogin);

		

		return $strlogin;

	}

}



if(!function_exists('geraSenha')) {

	function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){

		//$lmin = 'abcdefghijklmnopqrstuvwxyz';

		$lmin = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$num = '1234567890';

		$simb = '!@#$%*-';

		$retorno = '';

		$caracteres = '';

		$caracteres .= $lmin;

		if ($maiusculas) $caracteres .= $lmai;

		if ($numeros) $caracteres .= $num;

		if ($simbolos) $caracteres .= $simb;

		$len = strlen($caracteres);

		

		for ($n = 1; $n <= $tamanho; $n++) {

			$rand = mt_rand(1, $len);

			$retorno .= $caracteres[$rand-1];

		}

		

		return $retorno;

	}

}



if(!function_exists('mostraPrecoEnc')) {

	function mostraPrecoEnc($id, $valor) {

		require_once('../../../Connections/connADMIN.php');

		

		$query_rsEnc = "SELECT * FROM encomendas WHERE id='$id'";

		$rsEnc = DB::getInstance()->prepare($query_rsEnc);

		$rsEnc->execute();

		$row_rsEnc = $rsEnc->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsEnc = $rsEnc->rowCount();

		DB::close();

		

		$moeda = $row_rsEnc["moeda"];

		

		if(!$row_rsEnc["moeda"]) {

		    $ret = number_format(round($valor,2),2,",",".")."&pound;";

	  	} elseif($moeda != "&pound;") {

			$ret = $moeda.number_format(round($valor,2),2,"."," ");

		} else {

			$ret = number_format(round($valor,2),2,",",".").$moeda;

		}

		

		return $ret;	

	}

}





if(!function_exists('randomCodeRecupera')) {

	function randomCodeRecupera($size = '32') {

		$string = '';

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';



		for($i = 0; $i < $size; $i++) {

			$string .= $characters[mt_rand(0, (strlen($characters) - 1))];  

		}



		$query_rsExists = "SELECT id FROM clientes WHERE cod_recupera = '$string'";

		$rsExists = DB::getInstance()->prepare($query_rsExists);

		$rsExists->execute();

		$totalRows_rsExists = $rsExists->rowCount();

		DB::close();



		if($totalRows_rsExists == 0) {

			return $string;

		}

	}

}





//GERAR FILLS DAS IMAGENS



function getFillSize($tabela, $campo) {

	$query_rsConfigImg = "SELECT * FROM config_imagens WHERE titulo = '$tabela'";

	$rsConfigImg = DB::getInstance()->prepare($query_rsConfigImg);

	$rsConfigImg->execute();

	$row_rsConfigImg = $rsConfigImg->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsConfigImg = $rsConfigImg->rowCount();

	DB::close();



	return $size = explode("x", $row_rsConfigImg[$campo]);

}



function gerarFill($id) {

	$query_rsConfigImg = "SELECT * FROM config_imagens WHERE id = '$id'";

	$rsConfigImg = DB::getInstance()->prepare($query_rsConfigImg);

	$rsConfigImg->execute();

	$row_rsConfigImg = $rsConfigImg->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsConfigImg = $rsConfigImg->rowCount();

	DB::close();



	// $is_prop = 1;

	$generate1 = 0;

	$generate2 = 0;

	$generate3 = 0;

	$generate4 = 0;



	if($totalRows_rsConfigImg > 0) {

		if($row_rsConfigImg['imagem1']) {

			$nome_pasta = strtolower(str_replace(" ", "_", $row_rsConfigImg['nome']));

			$path = ROOTPATH."imgs/".$nome_pasta."/fill.gif";

			

			$size = explode("x",$row_rsConfigImg['imagem1']);

			$width_img1 = $size[0];

			$height_img1 = $size[1];		



			$generate1 = 1;	

			// $is_prop = 0;

		}



		if($row_rsConfigImg['imagem2']) {

			$size = explode("x",$row_rsConfigImg['imagem2']);



			$width_img2 = $size[0];

			$height_img2 = $size[1];



			$prop_width = $width_img1 / $width_img2;

			$prop_height = $height_img1 / $height_img2;



			// if($prop_width != $prop_height){

				$nome_pasta2 = strtolower(str_replace(" ", "_", $row_rsConfigImg['nome']));

				$path2 = ROOTPATH."imgs/".$nome_pasta2."/fill2.gif";



				$generate1 = 1;

				$generate2 = 1;

				// $is_prop = 0;

			// }

			// else {

			// 	$width_prop = $width_img2;

			// 	$height_prop = $height_img2;

			// 	$is_prop = 1;

			// }

		}

		

		if($row_rsConfigImg['imagem3']) {

			$size = explode("x",$row_rsConfigImg['imagem3']);



			$width_img3 = $size[0];

			$height_img3 = $size[1];



			$prop_width = $width_img2 / $width_img3;

			$prop_height = $height_img2 / $height_img3;



			// if($prop_width != $prop_height){

				$nome_pasta3 = strtolower(str_replace(" ", "_", $row_rsConfigImg['nome']));

				$path3 = ROOTPATH."imgs/".$nome_pasta3."/fill3.gif";

				

				$generate1 = 1;

				$generate2 = 1;

				$generate3 = 1;

				// $is_prop = 0;

			// }

			// else {

			// 	$width_prop = $width_img3;

			// 	$height_prop = $height_img3;

			// 	$is_prop = 1;

			// }		

		}



		if($row_rsConfigImg['imagem4']) {

			$size = explode("x",$row_rsConfigImg['imagem4']);



			$width_img4 = $size[0];

			$height_img4 = $size[1];



			$prop_width = $width_img3 / $width_img4;

			$prop_height = $height_img3 / $height_img4;



			// if($prop_width != $prop_height){

				$nome_pasta4 = strtolower(str_replace(" ", "_", $row_rsConfigImg['nome']));

				$path4 = ROOTPATH."imgs/".$nome_pasta4."/fill4.gif";

				

				$generate1 = 1;

				$generate2 = 1;

				$generate3 = 1;

				$generate4 = 1;

				// $is_prop = 0;

			// }

			// else {

			// 	$width_prop = $width_img4;

			// 	$height_prop = $height_img4;

			// 	$is_prop = 1;

			// }	

		}



		// if($is_prop == 0) {

			if($generate1 == 1) {

				if(file_exists($path)) @unlink($path);

				

				$img1 = imagecreatetruecolor($width_img1, $height_img1);

				imagesavealpha($img1, true);

				$color1 = imagecolorallocate($img1, 0, 0, 0);

				imagecolortransparent($img1, $color1);

	

				// Save the image

				imagegif($img1, $path);

				imagedestroy($img1);

			}

	

			if($generate2 == 1) {

				if(file_exists($path2)) @unlink($path2);

	

				$img2 = imagecreatetruecolor($width_img2, $height_img2);

				imagesavealpha($img2, true);

				$color2 = imagecolorallocate($img2, 0, 0, 0);

				imagecolortransparent($img2, $color2);

				

				// Save the image

				imagegif($img2, $path2);

				imagedestroy($img2);

			}

	

			if($generate3 == 1) {

				if(file_exists($path3)) @unlink($path3);

	

				$img3 = imagecreatetruecolor($width_img3, $height_img3);

				imagesavealpha($img3, true);

				$color3 = imagecolorallocate($img3, 0, 0, 0);

				imagecolortransparent($img3, $color3);

				

				// Save the image

				imagegif($img3, $path3);

				imagedestroy($img3);

			}

			

			if($generate4 == 1) {

				if(file_exists($path4)) @unlink($path4);

	

				$img4 = imagecreatetruecolor($width_img4, $height_img4);

				imagesavealpha($img4, true);

				$color4 = imagecolorallocate($img4, 0, 0, 0);

				imagecolortransparent($img4, $color4);

				

				// Save the image

				imagegif($img4, $path4);

				imagedestroy($img4);

			}

		// }

		// else {

		// 	if(file_exists($path)) @unlink($path);

			

		// 	$img1 = imagecreatetruecolor($width_prop, $height_prop);

		// 	imagesavealpha($img1, true);

		// 	$color1 = imagecolorallocate($img1, 0, 0, 0);

		// 	imagecolortransparent($img1, $color1);



		// 	// Save the image

		// 	imagegif($img1, $path);

		// 	imagedestroy($img1);

		// }

	}

}



if(!function_exists('alteraSessions')) {

	function alteraSessions($area) {

		$query_rsExisteTabela = "SHOW TABLES LIKE 'config_sessions'";

		$rsExisteTabela = DB::getInstance()->prepare($query_rsExisteTabela);

		$rsExisteTabela->execute();

		$totalRows_rsExisteTabela = $rsExisteTabela->rowCount();

		DB::close();



		if($totalRows_rsExisteTabela > 0) {

			$query_rsExiste = "SELECT id FROM config_sessions WHERE nome_session = :area";

			$rsExiste = DB::getInstance()->prepare($query_rsExiste);

			$rsExiste->bindParam(':area', $area, PDO::PARAM_STR, 5);

			$rsExiste->execute();

			$totalRows_rsExiste = $rsExiste->rowCount();

			$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);

			DB::close();



			if($totalRows_rsExiste > 0) {

				$query_rsUpdate = "UPDATE config_sessions SET refresh = NOW() WHERE id = '".$row_rsExiste['id']."'";

				$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);

				$rsUpdate->execute();

				DB::close();

			}

		}

	}

}



function compressImage($source_url, $destination_url, $quality = 90) {

	//SOURCE: https://cloudinary.com/blog/image_optimization_in_php#optimizing_images_in_php

	//ImageOptmizer change: https://github.com/spatie/image-optimizer

	//NOTA: as librarias estao todas em consola/assets/optimizer/*



	//1 - Optimiza��o com GD library.

	// $info = getimagesize($source_url);



	// if($info['mime'] == 'image/jpeg') {

	// 	$image = imagecreatefromjpeg($source_url);

	// 	imagejpeg($image, $destination_url, $quality);

	// }

	// elseif($info['mime'] == 'image/gif') {

	// 	$image = imagecreatefromgif($source_url);

	// 	imagegif($image, $destination_url, $quality);

	// }

	// elseif($info['mime'] == 'image/png') {

	// 	$image = imagecreatefrompng($source_url);

	// 	imagepng($image, $destination_url, $quality);

	// }



	//2 - Optimiza��o com Imagick

	require_once(ROOTPATH_CONSOLA.'assets/netgocio/optimizer/ImageCache/ImageCache.php');

	// $im = new Imagick($source_url);

	// $im->optimizeImageLayers();

	// $im->setImageCompression(Imagick::COMPRESSION_JPEG);

	// $im->setImageCompressionQuality($quality);

	// $im->writeImages($destination_url, true);

}



function listaCategorias($cat_mae, $nivel, $nome_cat, $id_categoria, $categoria_atual){

  if($cat_mae >= 0 && CATEGORIAS_NIVEL >= 2){

  	$nivel++;

  	/* Verifica��o para que a categoria que esteja a ser editada n�o aparece na listagem de categorias. */

  	if($id_categoria > 0){ 

  		$query_rsCat = "SELECT * FROM l_categorias_en WHERE cat_mae=:cat_mae AND id != '$id_categoria' ORDER BY nome ASC";

  	}

  	else {

  		$query_rsCat = "SELECT * FROM l_categorias_en";

  	}



    $rsCat = DB::getInstance()->prepare($query_rsCat);

    $rsCat->bindParam(':cat_mae', $cat_mae, PDO::PARAM_INT, 5);

    $rsCat->execute();

    $totalRows_rsCat = $rsCat->rowCount();

    DB::close();



    if($totalRows_rsCat > 0) {

    	while($row_rsCat = $rsCat->fetch()) { 

    		if($row_rsCat['cat_mae'] == 0){

    			$nome_cat = ""; 

    		}?>

        <option value="<?php echo $row_rsCat['id']; ?>" <?php if($categoria_atual == $row_rsCat['id']) echo "selected"; ?>><?php echo $nome_cat.$row_rsCat['nome']; ?></option><?php      

        if($nivel <= CATEGORIAS_NIVEL){

        	listaCategorias($row_rsCat['id'], $nivel, $nome_cat.$row_rsCat['nome']." � ", $id_categoria, $categoria_atual);

        }

      }

    }

  }

}



function umaCategoriaPorProd($cat_mae, $nome_cat, $categoria_atual, $listagem = 0) {

  if($cat_mae >= 0) {

    $query_rsCat = "SELECT id, cat_mae, nome FROM l_categorias_en WHERE cat_mae=:cat_mae ORDER BY nome ASC";

    $rsCat = DB::getInstance()->prepare($query_rsCat);

    $rsCat->bindParam(':cat_mae', $cat_mae, PDO::PARAM_INT);

    $rsCat->execute();

    $totalRows_rsCat = $rsCat->rowCount();

    DB::close();



    if($totalRows_rsCat > 0) {

    	while($row_rsCat = $rsCat->fetch()) {

    		$query_rsFilhos = "SELECT COUNT(id) AS total FROM l_categorias_en WHERE cat_mae=:cat_mae ORDER BY nome ASC";

		    $rsFilhos = DB::getInstance()->prepare($query_rsFilhos);

		    $rsFilhos->bindParam(':cat_mae', $row_rsCat['id'], PDO::PARAM_INT);

		    $rsFilhos->execute();

		    $row_rsFilhos = $rsFilhos->fetch(PDO::FETCH_ASSOC);

		    DB::close();



    		if($row_rsCat['cat_mae'] == 0) {

    			$nome_cat = ""; 

    		}

    		?>

        <option value="<?php echo $row_rsCat['id']; ?>" <?php if($categoria_atual == $row_rsCat['id']) echo "selected"; ?> <?php if($listagem == 0 && $row_rsFilhos['total'] > 0) echo "disabled"; ?>><?php echo $nome_cat.$row_rsCat['nome']; ?></option>

        <?php

      	umaCategoriaPorProd($row_rsCat['id'], $nome_cat.$row_rsCat['nome']." &#xbb; ", $categoria_atual, $listagem);

      }

    }

  }

}



function variasCategoriasPorProd($cat_mae, $id_prod, $fonte) {

  if($cat_mae >= 0) { 

    $query_rsCat = "SELECT id, nome FROM l_categorias_en WHERE cat_mae=:cat_mae ORDER BY nome ASC";

    $rsCat = DB::getInstance()->prepare($query_rsCat);

    $rsCat->bindParam(':cat_mae', $cat_mae, PDO::PARAM_INT);

    $rsCat->execute();

    $totalRows_rsCat = $rsCat->rowCount();

    DB::close();

    ?>

    <ul class="list-unstyled"> 

    	<?php if($totalRows_rsCat > 0) { 

        while($row_rsCat = $rsCat->fetch()) { 

        	if($id_prod > 0 && $fonte == 1) { //Se $fonte for igual a 1 significa que o pedido vem dos produtos, se for 2 vem dos portes gr�tis

			    	$query_rsTotal = "SELECT id FROM l_pecas_categorias WHERE id_peca=:id_prod AND id_categoria=:id_categoria";

			      $rsTotal = DB::getInstance()->prepare($query_rsTotal);

			      $rsTotal->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

			      $rsTotal->bindParam(':id_categoria', $row_rsCat['id'], PDO::PARAM_INT);

			      $rsTotal->execute();

			      $totalRows_rsTotal = $rsTotal->rowCount();

			    } 

			    else if($id_prod > 0 && $fonte == 2) {

			    	$query_rsTotal = "SELECT * FROM portes_gratis_categorias WHERE portes_gratis=:id_portes AND categoria=:id_categoria";

						$rsTotal = DB::getInstance()->prepare($query_rsTotal);

						$rsTotal->bindParam(":id_portes", $id_prod, PDO::PARAM_INT, 5);

						$rsTotal->bindParam(":id_categoria", $row_rsCat['id'], PDO::PARAM_INT, 5);	

						$rsTotal->execute();

						$totalRows_rsTotal = $rsTotal->rowCount();

						DB::close();

			    }?> 

          <li>

            <label><input type="checkbox" name="categorias[]" value="<?php echo $row_rsCat['id']; ?>" <?php if($totalRows_rsTotal > 0) echo "checked"; ?> ><?php echo $row_rsCat['nome']; ?></label>

            <?php variasCategoriasPorProd($row_rsCat['id'], $id_prod, $fonte); ?>

          </li><?php

        }

      } ?>

    </ul>

	<?php }

}



function copiarProduto($id_prod, $nome_prod, $imagens, $relacionados, $quantidades, $promocao, $filtros, $stocks) {

  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

  $rsLinguas = DB::getInstance()->query($query_rsLinguas);

  $rsLinguas->execute();

  $totalRows_rsLinguas = $rsLinguas->rowCount();

  $row_rsLinguas = $rsLinguas->fetchAll();

  DB::close();



	//Copiar Detalhes

  $insertSQL = "SELECT MAX(id) FROM l_pecas_en";

  $rsInsert = DB::getInstance()->prepare($insertSQL);

  $rsInsert->execute();

  $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

  DB::close();

  

  $new_prod = $row_rsInsert["MAX(id)"] + 1;



  foreach($row_rsLinguas as $lingua) {

    $query_rsOldProd = "SELECT * FROM l_pecas_".$lingua['sufixo']." WHERE id = :id_prod";

    $rsOldProd = DB::getInstance()->prepare($query_rsOldProd);

    $rsOldProd->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsOldProd->execute();

    $totalRows_rsOldProd = $rsOldProd->rowCount();

    $row_rsOldProd = $rsOldProd->fetch(PDO::FETCH_ASSOC);



    if($totalRows_rsOldProd > 0) {

      $nome_url = $nome_prod;

      if(!$nome_prod) {

      	$nome_prod = $row_rsOldProd['nome']." - C�PIA ".date('Y-m-d H:i:s');

      	$nome_url = $row_rsOldProd['nome'];

      }



      $ref = $row_rsOldProd['ref'];

      $categoria = $row_rsOldProd['categoria'];

      $marca = $row_rsOldProd['marca'];

      $descricao = $row_rsOldProd['descricao'];

      $novidade = $row_rsOldProd['novidade'];

      $destaque = $row_rsOldProd['destaque'];

      $visivel = $row_rsOldProd['visivel'];

      $ordem = $row_rsOldProd['ordem'];

      $preco = $row_rsOldProd['preco'];

      $preco_ant = $row_rsOldProd['preco_ant'];

      $peso = $row_rsOldProd['peso'];

      $stock = $row_rsOldProd['stock'];

      $nao_limitar_stock = $row_rsOldProd['nao_limitar_stock'];

      $descricao_stock = $row_rsOldProd['descricao_stock'];

      $iva = $row_rsOldProd['iva'];

      $quantidades_descricao = $row_rsOldProd['quantidades_descricao'];



      $url = "";

      

      if($categoria != '' && CATEGORIAS == 1) {

      	$query_rsCat = "SELECT id, url FROM l_categorias_".$lingua['sufixo']." WHERE id = :id";

	      $rsCat = DB::getInstance()->prepare($query_rsCat);

	      $rsCat->bindParam(':id', $categoria, PDO::PARAM_STR, 5);

	      $rsCat->execute();

	      $row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

	      $totalRows_rsCat = $rsCat->rowCount();



	      if($totalRows_rsCat > 0) {

	      	$url .= $row_rsCat['url']."-";

	      }

      }



      $url .= strtolower(verifica_nome($nome_url));



      $query_rsProc = "SELECT id FROM l_pecas_".$lingua['sufixo']." WHERE url LIKE :url AND id != :id";

      $rsProc = DB::getInstance()->prepare($query_rsProc);

      $rsProc->bindParam(':url', $url, PDO::PARAM_STR, 5);

      $rsProc->bindParam(':id', $new_prod, PDO::PARAM_INT);

      $rsProc->execute();

      $totalRows_rsProc = $rsProc->rowCount();

      DB::close();

      

      if($totalRows_rsProc > 0) {

        $url = $url."-".$new_prod;

      }



      $title = $nome_url;

      $description = $row_rsOldProd['description'];

      $keywords = $row_rsOldProd['keywords'];



      //Copiar Promo��o (caso aplic�vel)

      $promocao_ativa = 0;

      $promocao_desconto = 0;

      $promocao_datai = NULL;

      $promocao_dataf = NULL;

      $promocao_pagina = 0;

      $promocao_titulo = NULL;

      $promocao_texto = NULL;



      if($promocao == 1) {

	      $promocao_ativa = $row_rsOldProd['promocao'];

	      $promocao_desconto = $row_rsOldProd['promocao_desconto'];

	      $promocao_datai = $row_rsOldProd['promocao_datai'];

	      $promocao_dataf = $row_rsOldProd['promocao_dataf'];

	      $promocao_pagina = $row_rsOldProd['promocao_pagina'];

	      $promocao_titulo = $row_rsOldProd['promocao_titulo'];

	      $promocao_texto = $row_rsOldProd['promocao_texto'];

	    }

      

      $insertSQL = "INSERT INTO l_pecas_".$lingua['sufixo']." (id, ref, nome, categoria, marca, descricao, novidade, destaque, visivel, ordem, promocao, promocao_desconto, promocao_datai, promocao_dataf, promocao_pagina, promocao_titulo, promocao_texto, preco, preco_ant, peso, stock, nao_limitar_stock, descricao_stock, iva, quantidades_descricao, url, title, description, keywords) VALUES (:id, :ref, :nome, :categoria, :marca, :descricao, :novidade, :destaque, :visivel, :ordem, :promocao, :promocao_desconto, :promocao_datai, :promocao_dataf, :promocao_pagina, :promocao_titulo, :promocao_texto, :preco, :preco_ant, :peso, :stock, :nao_limitar_stock, :descricao_stock, :iva, :quantidades_descricao, :url, :title, :description, :keywords)";

      $rsInsert = DB::getInstance()->prepare($insertSQL);

      $rsInsert->bindParam(':ref', $ref, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':nome', $nome_prod, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);

      $rsInsert->bindParam(':marca', $marca, PDO::PARAM_INT);

      $rsInsert->bindParam(':descricao', $descricao, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':novidade', $novidade, PDO::PARAM_INT);

      $rsInsert->bindParam(':destaque', $destaque, PDO::PARAM_INT);

      $rsInsert->bindParam(':visivel', $visivel, PDO::PARAM_INT);

      $rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);

      $rsInsert->bindParam(':promocao', $promocao_ativa, PDO::PARAM_INT);

      $rsInsert->bindParam(':promocao_desconto', $promocao_desconto, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':promocao_datai', $promocao_datai, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':promocao_dataf', $promocao_dataf, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':promocao_pagina', $promocao_pagina, PDO::PARAM_INT);

      $rsInsert->bindParam(':promocao_titulo', $promocao_titulo, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':promocao_texto', $promocao_texto, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':preco', $preco, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':preco_ant', $preco_ant, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':peso', $peso, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':stock', $stock, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':nao_limitar_stock', $nao_limitar_stock, PDO::PARAM_INT);

      $rsInsert->bindParam(':descricao_stock', $descricao_stock, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':iva', $iva, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':quantidades_descricao', $quantidades_descricao, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':url', $url, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':title', $title, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':description', $description, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':keywords', $keywords, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':id', $new_prod, PDO::PARAM_INT);

      $rsInsert->execute();

    }

  }



  //Copiar Imagens

  if($imagens == 1) {

    $query_rsImagens = "SELECT imagem1, imagem2, imagem3, imagem4, ordem, visivel, nome FROM l_pecas_imagens WHERE id_peca = :id_prod ORDER BY id ASC";

    $rsImagens = DB::getInstance()->prepare($query_rsImagens);

    $rsImagens->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsImagens->execute();

    $totalRows_rsImagens = $rsImagens->rowCount();



    if($totalRows_rsImagens > 0) {

    	$i = 1;

      while($row_rsImagens = $rsImagens->fetch()) {

      	$imagem1 = $row_rsImagens['imagem1'];

      	$imagem2 = $row_rsImagens['imagem2'];

      	$imagem3 = $row_rsImagens['imagem3'];

      	$imagem4 = $row_rsImagens['imagem4'];

      	$ordem = $row_rsImagens['ordem'];

      	$visivel = $row_rsImagens['visivel'];

      	$descricao = $row_rsImagens['nome'];



        $new_imagem1 = NULL;

	      $new_imagem2 = NULL;

	      $new_imagem3 = NULL;

	      $new_imagem4 = NULL;



	      if($imagem1 && file_exists('../../../imgs/produtos/'.$imagem1)) {

	      	$ext = strtolower(pathinfo($imagem1, PATHINFO_EXTENSION));



	      	$new_imagem1 = "gd_".$new_prod."_".$i.".".$ext;



	      	@copy('../../../imgs/produtos/'.$imagem1, '../../../imgs/produtos/'.$new_imagem1);

	      }

	      if($imagem2 && file_exists('../../../imgs/produtos/'.$imagem2)) {

	      	$ext = strtolower(pathinfo($imagem2, PATHINFO_EXTENSION));



	      	$new_imagem2 = $new_prod."_".$i.".".$ext;



	      	@copy('../../../imgs/produtos/'.$imagem2, '../../../imgs/produtos/'.$new_imagem2);

	      }

	      if($imagem3 && file_exists('../../../imgs/produtos/'.$imagem3)) {

	      	$ext = strtolower(pathinfo($imagem3, PATHINFO_EXTENSION));



	      	$new_imagem3 = "md_".$new_prod."_".$i.".".$ext;



	      	@copy('../../../imgs/produtos/'.$imagem3, '../../../imgs/produtos/'.$new_imagem3);

	      }

	      if($imagem4 && file_exists('../../../imgs/produtos/'.$imagem4)) {

	      	$ext = strtolower(pathinfo($imagem4, PATHINFO_EXTENSION));



	      	$new_imagem4 = "pq_".$new_prod."_".$i.".".$ext;



	      	@copy('../../../imgs/produtos/'.$imagem4, '../../../imgs/produtos/'.$new_imagem4);

	      }



	      $insertSQL = "SELECT MAX(id) FROM l_pecas_imagens";

	      $rsInsert = DB::getInstance()->prepare($insertSQL);

	      $rsInsert->execute();

	      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

	      

	      $max_id = $row_rsInsert["MAX(id)"] + 1;



        $query_rsInsert = "INSERT INTO l_pecas_imagens (id, id_peca, imagem1, imagem2, imagem3, imagem4, ordem, visivel, nome) VALUES (:id, :id_peca, :imagem1, :imagem2, :imagem3, :imagem4, :ordem, :visivel, :nome)";

        $rsInsert = DB::getInstance()->prepare($query_rsInsert);

        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_peca', $new_prod, PDO::PARAM_INT);

        $rsInsert->bindParam(':imagem1', $new_imagem1, PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':imagem2', $new_imagem2, PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':imagem3', $new_imagem3, PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':imagem4', $new_imagem4, PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);

        $rsInsert->bindParam(':visivel', $visivel, PDO::PARAM_INT);

        $rsInsert->bindParam(':nome', $descricao, PDO::PARAM_STR, 5);

        $rsInsert->execute();



        if($i == 1) {

        	foreach($row_rsLinguas as $lingua) {

	        	$insertSQL = "UPDATE l_pecas_".$lingua['sufixo']." SET imagem1=:imagem1, imagem2=:imagem2, imagem3=:imagem3, imagem4=:imagem4 WHERE id=:id";

			      $rsInsert = DB::getInstance()->prepare($insertSQL);

			      $rsInsert->bindParam(':imagem1', $new_imagem1, PDO::PARAM_STR, 5);

			      $rsInsert->bindParam(':imagem2', $new_imagem2, PDO::PARAM_STR, 5);

			      $rsInsert->bindParam(':imagem3', $new_imagem3, PDO::PARAM_STR, 5);

			      $rsInsert->bindParam(':imagem4', $new_imagem4, PDO::PARAM_STR, 5);

			      $rsInsert->bindParam(':id', $new_prod, PDO::PARAM_INT);

			      $rsInsert->execute();

				  }

        }



        $i++;

      }

    }

  }



  //Copiar Descontos por Quantidades

  if($quantidades == 1) {

    $query_rsDescontos = "SELECT * FROM l_pecas_desconto WHERE id_peca = :id_prod ORDER BY id ASC";

    $rsDescontos = DB::getInstance()->prepare($query_rsDescontos);

    $rsDescontos->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsDescontos->execute();

    $totalRows_rsDescontos = $rsDescontos->rowCount();



    if($totalRows_rsDescontos > 0) {

      while($row_rsDescontos = $rsDescontos->fetch()) {

      	$min = $row_rsDescontos['min'];

      	$max = $row_rsDescontos['max'];

      	$desconto = $row_rsDescontos['desconto'];



      	$insertSQL = "SELECT MAX(id) FROM l_pecas_desconto";

	      $rsInsert = DB::getInstance()->prepare($insertSQL);

	      $rsInsert->execute();

	      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

	      

	      $max_id = $row_rsInsert["MAX(id)"] + 1;



        $query_rsInsert = "INSERT INTO l_pecas_desconto (id, id_peca, min, max, desconto) VALUES (:id, :id_peca, :min, :max, :desconto)";

        $rsInsert = DB::getInstance()->prepare($query_rsInsert);

        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_peca', $new_prod, PDO::PARAM_INT);

        $rsInsert->bindParam(':min', $min, PDO::PARAM_INT);

        $rsInsert->bindParam(':max', $max, PDO::PARAM_INT);

        $rsInsert->bindParam(':desconto', $desconto, PDO::PARAM_STR, 5);

        $rsInsert->execute();

      }

    }

  }



  //Copiar Produtos Relacionados

  if($relacionados == 1) {

    $query_rsOldRelacionados = "SELECT * FROM l_pecas_relacao WHERE id_peca = :id_prod";

    $rsOldRelacionados = DB::getInstance()->prepare($query_rsOldRelacionados);

    $rsOldRelacionados->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsOldRelacionados->execute();

    $totalRows_rsOldRelacionados = $rsOldRelacionados->rowCount();



    if($totalRows_rsOldRelacionados > 0) {

      while($row_rsOldRelacionados = $rsOldRelacionados->fetch()) {

      	$insertSQL = "SELECT MAX(id) FROM l_pecas_relacao";

	      $rsInsert = DB::getInstance()->prepare($insertSQL);

	      $rsInsert->execute();

	      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

	      

	      $max_id = $row_rsInsert["MAX(id)"] + 1;



        $query_rsInsert = "INSERT INTO l_pecas_relacao (id, id_peca, id_relacao, ordem) VALUES (:id, :id_peca, :id_relacao, :ordem)";

        $rsInsert = DB::getInstance()->prepare($query_rsInsert);

        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_peca', $new_prod, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_relacao', $row_rsOldRelacionados['id_relacao'], PDO::PARAM_INT);

        $rsInsert->bindParam(':ordem', $row_rsOldRelacionados['ordem'], PDO::PARAM_INT);

        $rsInsert->execute();

      }

    }



    $query_rsOldRelacionados = "SELECT * FROM l_pecas_relacao WHERE id_relacao = :id_prod";

    $rsOldRelacionados = DB::getInstance()->prepare($query_rsOldRelacionados);

    $rsOldRelacionados->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsOldRelacionados->execute();

    $totalRows_rsOldRelacionados = $rsOldRelacionados->rowCount();



    if($totalRows_rsOldRelacionados > 0) {

      while($row_rsOldRelacionados = $rsOldRelacionados->fetch()) {

      	$insertSQL = "SELECT MAX(id) FROM l_pecas_relacao";

	      $rsInsert = DB::getInstance()->prepare($insertSQL);

	      $rsInsert->execute();

	      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

	      

	      $max_id = $row_rsInsert["MAX(id)"] + 1;



        $query_rsInsert = "INSERT INTO l_pecas_relacao (id, id_peca, id_relacao, ordem) VALUES (:id, :id_peca, :id_relacao, :ordem)";

        $rsInsert = DB::getInstance()->prepare($query_rsInsert);

        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_peca', $row_rsOldRelacionados['id_peca'], PDO::PARAM_INT);

        $rsInsert->bindParam(':id_relacao', $new_prod, PDO::PARAM_INT);

        $rsInsert->bindParam(':ordem', $row_rsOldRelacionados['ordem'], PDO::PARAM_INT);

        $rsInsert->execute();

      }

    }

  }



  //Copiar Filtros

  if($filtros == 1) {

    $query_rsOldFiltros = "SELECT * FROM l_pecas_filtros WHERE id_peca = :id_prod";

    $rsOldFiltros = DB::getInstance()->prepare($query_rsOldFiltros);

    $rsOldFiltros->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsOldFiltros->execute();

    $totalRows_rsOldFiltros = $rsOldFiltros->rowCount();



    if($totalRows_rsOldFiltros > 0) {

      while($row_rsOldFiltros = $rsOldFiltros->fetch()) {

      	$insertSQL = "SELECT MAX(id) FROM l_pecas_filtros";

	      $rsInsert = DB::getInstance()->prepare($insertSQL);

	      $rsInsert->execute();

	      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

	      

	      $max_id = $row_rsInsert["MAX(id)"] + 1;



        $query_rsInsert = "INSERT INTO l_pecas_filtros (id, id_peca, id_filtro) VALUES (:id, :id_peca, :id_filtro)";

        $rsInsert = DB::getInstance()->prepare($query_rsInsert);

        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_peca', $new_prod, PDO::PARAM_INT);

        $rsInsert->bindParam(':id_filtro', $row_rsOldFiltros['id_filtro'], PDO::PARAM_INT);

        $rsInsert->execute();

      }

    }

  }



  //Copiar Stocks

  if($stocks == 1) {

    $query_rsOldStocks = "SELECT * FROM l_pecas_tamanhos WHERE peca = :id_prod";

    $rsOldStocks = DB::getInstance()->prepare($query_rsOldStocks);

    $rsOldStocks->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);

    $rsOldStocks->execute();

    $totalRows_rsOldStocks = $rsOldStocks->rowCount();



    if($totalRows_rsOldStocks > 0) {

      while($row_rsOldStocks = $rsOldStocks->fetch()) {

      	$insertSQL = "SELECT MAX(id) FROM l_pecas_tamanhos";

	      $rsInsert = DB::getInstance()->prepare($insertSQL);

	      $rsInsert->execute();

	      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

	      

	      $max_id = $row_rsInsert["MAX(id)"] + 1;



        $query_rsInsert = "INSERT INTO l_pecas_tamanhos (id, peca, ref,l_caract_categorias_en_id, l_caract_opcoes_en_id, car1, op1, car2, op2, car3, op3, car4, op4, car5, op5, preco, preco_old, preco_forn, peso, volume, stock, defeito) VALUES (:id, :peca, :ref, :l_caract_categorias_en_id, :l_caract_opcoes_en_id, :car1, :op1, :car2, :op2, :car3, :op3, :car4, :op4, :car5, :op5, :preco, :preco_old, :preco_forn, :peso, :volume, :stock, :defeito)";

        $rsInsert = DB::getInstance()->prepare($query_rsInsert);

        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);

        $rsInsert->bindParam(':peca', $new_prod, PDO::PARAM_INT);

        $rsInsert->bindParam(':ref', $row_rsOldStocks['ref'], PDO::PARAM_STR, 5);

		$rsInsert->bindParam(':l_caract_categorias_en_id', $row_rsOldStocks['l_caract_categorias_en_id'], PDO::PARAM_INT);

        $rsInsert->bindParam(':l_caract_opcoes_en_id', $row_rsOldStocks['l_caract_opcoes_en_id'], PDO::PARAM_INT);

        $rsInsert->bindParam(':car1', $row_rsOldStocks['car1'], PDO::PARAM_INT);

        $rsInsert->bindParam(':op1', $row_rsOldStocks['op1'], PDO::PARAM_INT);

        $rsInsert->bindParam(':car2', $row_rsOldStocks['car2'], PDO::PARAM_INT);

        $rsInsert->bindParam(':op2', $row_rsOldStocks['op2'], PDO::PARAM_INT);

        $rsInsert->bindParam(':car3', $row_rsOldStocks['car3'], PDO::PARAM_INT);

        $rsInsert->bindParam(':op3', $row_rsOldStocks['op3'], PDO::PARAM_INT);

        $rsInsert->bindParam(':car4', $row_rsOldStocks['car4'], PDO::PARAM_INT);

        $rsInsert->bindParam(':op4', $row_rsOldStocks['op4'], PDO::PARAM_INT);

        $rsInsert->bindParam(':car5', $row_rsOldStocks['car5'], PDO::PARAM_INT);

        $rsInsert->bindParam(':op5', $row_rsOldStocks['op5'], PDO::PARAM_INT);

        $rsInsert->bindParam(':preco', $row_rsOldStocks['preco'], PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':preco_old', $row_rsOldStocks['preco_old'], PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':preco_forn', $row_rsOldStocks['preco_forn'], PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':peso', $row_rsOldStocks['peso'], PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':volume', $row_rsOldStocks['volume'], PDO::PARAM_STR, 5);

        $rsInsert->bindParam(':stock', $row_rsOldStocks['stock'], PDO::PARAM_INT);

        $rsInsert->bindParam(':defeito', $row_rsOldStocks['defeito'], PDO::PARAM_INT);

        $rsInsert->execute();

      }

    }

  }



	DB::close();



	return $new_prod;

}



/* Apagar um ou mais ficheiros de uma determinada tabela */

if(!function_exists('apagaFicheiros')) {

	function apagaFicheiros($tabela, $pasta_imgs, $id, $extensao, $array_imagens, $opcao) {

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel='1'";

		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

		$rsLinguas->execute();

		$row_rsLinguas = $rsLinguas->fetchAll();

		$totalRows_rsLinguas = $rsLinguas->rowCount();



		if($opcao == 1) {

			foreach($array_imagens as $coluna => $imagem) {

				$insertSQL = "UPDATE ".$tabela.$extensao." SET ".$coluna."=NULL WHERE id=:id";

				$rsInsert = DB::getInstance()->prepare($insertSQL);

				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

				$rsInsert->execute();



				$r = 0;



				//Verificar se o ficheiro est� a ser usado noutro idioma

				foreach($row_rsLinguas as $linguas) {

					$query_rsImagem = "SELECT id FROM ".$tabela."_".$linguas["sufixo"]." WHERE ".$coluna."=:imagem AND id=:id";

					$rsImagem = DB::getInstance()->prepare($query_rsImagem);

					$rsImagem->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);

					$rsImagem->bindParam(':id', $id, PDO::PARAM_INT);

					$rsImagem->execute();

					$totalRows_rsImagem = $rsImagem->rowCount();



					if($totalRows_rsImagem > 0) {

						$r = 1;

					}

				}



				//Se a vari�vel for igual a 0, significa que o ficheiro n�o � usada em mais nenhum registo e podemos apag�-lo

				if($r == 0) {

					@unlink(ROOTPATH.'imgs/'.$pasta_imgs.'/'.$imagem);

				}

			}

		}

		else if($opcao == 2) {

			foreach($array_imagens as $coluna => $imagem) {

				foreach($row_rsLinguas as $linguas) {

					$query_rsSelect = "SELECT ".$coluna." FROM ".$tabela."_".$linguas['sufixo']." WHERE id=:id";

					$rsSelect = DB::getInstance()->prepare($query_rsSelect);

					$rsSelect->bindParam(':id', $id, PDO::PARAM_INT);

					$rsSelect->execute();

					$row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);



					@unlink(ROOTPATH.'imgs/'.$pasta_imgs.'/'.$row_rsSelect[$coluna]);



					$insertSQL = "UPDATE ".$tabela."_".$linguas["sufixo"]." SET ".$coluna."=NULL WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

					$rsInsert->execute();

				}

			}

		}

			

		DB::close();

	}

}

?>