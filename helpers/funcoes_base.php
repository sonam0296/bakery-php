<?php
if(!isset($_SESSION)) {
  session_start();
}

function gerarSitemap() {
	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel='1' AND ativo='1' ORDER BY id ASC";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$row_rsLinguas = $rsLinguas->fetchAll();
	$totalRows_rsLinguas = $rsLinguas->rowCount();
	
	$query_rsLangTot = "SELECT id FROM linguas WHERE visivel='1' ORDER BY id ASC";
	$rsLangTot = DB::getInstance()->prepare($query_rsLangTot);
	$rsLangTot->execute();
	$totalRows_rsLangTot = $rsLangTot->rowCount();
	
	$query_rsSiteMap = "SELECT * FROM sitemap WHERE id='1'";
	$rsSiteMap = DB::getInstance()->prepare($query_rsSiteMap);
	$rsSiteMap->execute();
	$row_rsSiteMap = $rsSiteMap->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsSiteMap = $rsSiteMap->rowCount();

	$url_site = $row_rsSiteMap['url'];
	$num_links = 0;

	$conteudo = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

	$conteudo .= '
	<url>
		<loc>'.$url_site.'</loc>
		<priority>1.0</priority>
		<lastmod>'.date('Y-m-d').'</lastmod>
		<changefreq>daily</changefreq>
	</url>';

	$num_links++;

	$query_rsSiteMapEstatico = "SELECT * FROM sitemap_estatico ORDER BY id ASC";
	$rsSiteMapEstatico = DB::getInstance()->prepare($query_rsSiteMapEstatico);
	$rsSiteMapEstatico->execute();
	$row_rsSiteMapEstatico = $rsSiteMapEstatico->fetchAll();
	$totalRows_rsSiteMapEstatico = $rsSiteMapEstatico->rowCount();

	if($totalRows_rsSiteMapEstatico > 0) {
		foreach($row_rsSiteMapEstatico as $row_rsSiteMapEstatico) {
			foreach($row_rsLinguas as $lingua) {
				$url_site2 = $url_site;

				if($totalRows_rsLangTot > 1) {
					$url_site2 .= $lingua['sufixo']."/";
				}

				$novo_url = $url_site2.$row_rsSiteMapEstatico['url'];

				if(!strstr($conteudo, '<loc>'.$novo_url.'</loc>')) {
					$conteudo .= '
					<url>
						<loc>'.$novo_url.'</loc>
						<priority>'.$row_rsSiteMapEstatico['prioridade'].'</priority>
					</url>';

					$num_links++;
				}
			}
		}
	}

	$query_rsSiteMapTab = "SELECT * FROM sitemap_tabelas ORDER BY id ASC";
	$rsSiteMapTab = DB::getInstance()->prepare($query_rsSiteMapTab);
	$rsSiteMapTab->execute();
	$row_rsSiteMapTab = $rsSiteMapTab->fetchAll();
	$totalRows_rsSiteMapTab = $rsSiteMapTab->rowCount();

	if($totalRows_rsSiteMapTab > 0) {
		foreach($row_rsSiteMapTab as $row_rsSiteMapTab) {
			$nome = $row_rsSiteMapTab['nome'];
			$imagem = $row_rsSiteMapTab['imagem'];
			$pasta = $row_rsSiteMapTab['pasta'];
			$linguas = $row_rsSiteMapTab['linguas'];
			$prioridade = $row_rsSiteMapTab['prioridade'];
			$blog = $row_rsSiteMapTab['blog'];

			$url_site2 = $url_site;
			
			//Blog
			if($blog == 1) {
				$url_site2 .= "blog/";
			}

			if($linguas == 1) {
				foreach($row_rsLinguas as $lingua) {
					$query_rsProc = "SELECT * FROM ".$nome."_".$lingua['sufixo']." WHERE visivel='1' ORDER BY id DESC";
					$rsProc = DB::getInstance()->prepare($query_rsProc);
					$rsProc->execute();
					$row_rsProc = $rsProc->fetchAll();
					$totalRows_rsProc = $rsProc->rowCount();

					if($totalRows_rsProc > 0) {
						foreach($row_rsProc as $row_rsProc) {
							$nome_reg = $row_rsProc['nome'];
							if($row_rsSiteMapTab['id'] == 1) {
								$nome_reg = $row_rsProc['titulo'];
							}

							$url_site3 = $url_site2;

							//Blog Metatags
							if($nome == "metatags" && $row_rsProc['blog'] == 1) {
								$url_site3 = $url_site2."blog/";
							}

							if($totalRows_rsLangTot > 1) {
								if($row_rsSiteMapTab['prefixo_url']) {
									$novo_url = $url_site3.$lingua['sufixo']."/".$row_rsSiteMapTab['prefixo_url'].$row_rsProc['url'];
								}
								else {
									$novo_url = $url_site3.$lingua['sufixo']."/".$row_rsProc['url'];
								}
							}
							else {
								if($row_rsSiteMapTab['prefixo_url']) {
									$novo_url = $url_site3.$row_rsSiteMapTab['prefixo_url'].$row_rsProc['url'];
								}
								else {
									$novo_url = $url_site3.$row_rsProc['url'];
								}
							}

							if(!strstr($conteudo, '<loc>'.$novo_url.'</loc>')) {
								$conteudo .= '
								<url>
									<loc>'.$novo_url.'</loc>
									<priority>'.$prioridade.'</priority>';

									if($imagem && $row_rsProc[$imagem] && file_exists(ROOTPATH.$pasta.$row_rsProc[$imagem])) {
										$conteudo .= '
										<image:image>
										<image:loc>'.$url_site.$pasta.$row_rsProc[$imagem].'</image:loc>
										<image:caption>'.str_replace("&", " ", utf8_encode(strip_tags($nome_reg))).'</image:caption>
										<image:title>'.str_replace("&", " ", utf8_encode(strip_tags($nome_reg))).'</image:title>
									</image:image>';
									}
								$conteudo .= '
							</url>';

								$num_links++;	
							}
						}
					}
				}
			}
			else {
				$query_rsProc = "SELECT * FROM ".$nome." WHERE visivel='1' ORDER BY id DESC";
				$rsProc = DB::getInstance()->prepare($query_rsProc);
				$rsProc->execute();
				$row_rsProc = $rsProc->fetchAll();
				$totalRows_rsProc = $rsProc->rowCount();

				if($totalRows_rsProc > 0) {
					foreach($row_rsProc as $row_rsProc) {
						if(!strstr($conteudo, '<loc>'.$url_site.$row_rsProc['url'].'</loc>')) {
							$conteudo .= '
							<url>
								<loc>'.$url_site.$row_rsProc['url'].'</loc>
								<priority>'.$prioridade.'</priority>';

								if($imagem && $row_rsProc[$imagem] && file_exists(ROOTPATH.$pasta.$row_rsProc[$imagem])) {
									$conteudo .= '
									<image:image>
									<image:loc>
									'.$url_site.$pasta.$row_rsProc[$imagem].'
								</image:loc>
								<image:caption>'.str_replace("&", " ", utf8_encode(strip_tags($row_rsProc['nome']))).'</image:caption>
								<image:title>'.str_replace("&", " ", utf8_encode(strip_tags($row_rsProc['nome']))).'</image:title>
							</image:image>';
								}
						
							$conteudo .= '
					</url>';

							$num_links++;
						}
					}
				}
			}
		}
	}

	$conteudo .= '
</urlset>';

	$fp = fopen(ROOTPATH."sitemap/sitemap.xml", "w+");
	fwrite($fp, $conteudo);
	fclose($fp);
	@chmod(ROOTPATH."sitemap/sitemap.xml", 0777);

	$insertSQL = "UPDATE sitemap SET data=:data, links=:links WHERE id='1'";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':data', date('Y-m-d H:i:s'), PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':links', $num_links, PDO::PARAM_INT);	
	$rsInsert->execute();

	DB::close();
}

if(!function_exists('crawlerDetect')) {
	function crawlerDetect($USER_AGENT) {
		$crawlers = array(
		'Google' => 'Google',
		'MSN' => 'msnbot',
		'Rambler' => 'Rambler',
		'Yahoo' => 'Yahoo',
		'AbachoBOT' => 'AbachoBOT',
		'accoona' => 'Accoona',
		'AcoiRobot' => 'AcoiRobot',
		'ASPSeek' => 'ASPSeek',
		'CrocCrawler' => 'CrocCrawler',
		'Dumbot' => 'Dumbot',
		'FAST-WebCrawler' => 'FAST-WebCrawler',
		'GeonaBot' => 'GeonaBot',
		'Gigabot' => 'Gigabot',
		'Lycos spider' => 'Lycos',
		'MSRBOT' => 'MSRBOT',
		'Altavista robot' => 'Scooter',
		'AltaVista robot' => 'Altavista',
		'ID-Search Bot' => 'IDBot',
		'eStyle Bot' => 'eStyle',
		'Scrubby robot' => 'Scrubby',
		'Facebook' => 'facebookexternalhit',
		);
		
		$is_bot = 0;
		
		foreach($crawlers as $key=>$value) {
			if(strstr($USER_AGENT, $value)) {
				$is_bot = 1;
				break;
			}
		}
		
		if($is_bot == 0) {
			return false;
		}
		else {
			return true;
		}
	}
}

function data_hora($data_p, $suf = "", $tipo = 1) {
	global $extensao;
	if(!$suf) {
		$suf = $extensao;
	}

	$arraydia = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
	
	$data = explode('-', $data_p);
	$dia = $data['2'];
	
	if($dia < '10') {
		$dia = $arraydia[$dia - 1];
	}

	$mes = getMes($data['1'], 2, $suf);
	$ano = $data['0'];
	
	if($tipo == 1) { //Dia e mes
		$data_final = $dia." de ".$mes;
	}
	elseif($tipo == 2) { //Dia e mes e ano
		$data_final=$dia." ".$mes." ".$ano;
	}
	elseif($tipo == 3) { //Ano
		$data_final .= " ".$ano;
	}
	elseif($tipo == 4) { //mes
		$data_final .= " ".$mes;
	}
	elseif($tipo == 5) { //texto dia, dia e mes
		$data_final .= utf8_decode(strftime('%A', strtotime($data_p))).", ".$dia." de ".$mes;
	}
	elseif($tipo == 6) { //Sexta, 29 Junho 2018
		$data_final .= utf8_decode(strftime('%A', strtotime($data_p))).", ".$dia." ".$mes." ".$ano;
	}

	return $data_final;
}

function getMes($mes, $tipo, $suf = "") {
	global $extensao;
	if(!$suf) {
		$suf = $extensao;
	}

	if(!$suf || $suf == "_pt") {
		$arraymes = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
		$arraymes2 = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	}
	else if($suf == '_en') {
		$arraymes = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
		$arraymes2 = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	}
	else if($suf == '_es') {
		$arraymes = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dic');
		$arraymes2 = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	}
	else if($suf == '_fr') {
		$arraymes = array('Janv', 'Févr', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil', 'Aout', 'Sept', 'Oct', 'Nov', 'Déc');
		$arraymes2 = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	}
	else if($suf == '_de') {
		$arraymes = array('Jan', 'Feb', 'März', 'Apr', 'Mai', 'Juni', 'Juli', 'Aug', 'Sept', 'Okt', 'Nov', 'Dez');
		$arraymes2 = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
	}
	else if($suf == '_it') {
		$arraymes = array('Genn', 'Febbr', 'Mar', 'Apr', 'Magg', 'Giugno', 'Luglio', 'Ag', 'Sett', 'Ott', 'Nov', 'Dic');
		$arraymes2 = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
	}
	
	$mes_nome = $arraymes[$mes - 1];
	if($tipo == 2) {
		$mes_nome = $arraymes2[$mes - 1];
	}
	
	return $mes_nome;
}

function SomarData($data, $dias, $meses, $ano) {
  $data = explode("-", $data);
  $newData = date("Y-m-d", mktime(0, 0, 0, $data[1] + $meses, $data[2] + $dias, $data[0] + $ano));
  
  return $newData;
}

if(!function_exists('verifica_nome')) {
	function verifica_nome($nome, $is_url = 0) {
		$strlogin = $nome;
		$caracteres = array('º','ª','"','“','”','“','”',',',';','/','<','>',':','?','~','^',']','}','´','`','[','{','=','+','-',')','\\','(','*','&','¨','%','$','#','@','!','|','à','è','ì','ò','ù','â','ê','î','ô','û','ä','ë','ï','ö','ü','á','é','í','ó','ú','ã','õ','À','È','Ì','Ò','Ù','Â','Ê','Î','Ô','Û','Ä','Ë','Ï','Ö','Ü','Á','É','Í','Ó','Ú','Ã','Õ','ç','Ç',' ','\'','™','©','®','«','»','ñ','Ñ','Æ','“','”',',','‚','€','–','§','£','…','ø','Ø','Å','å','æ','Æ','•');
		
		if($is_url == 1) {
			array_push($caracteres, ".");
		}
		
		for($i = 0; $i < count($caracteres); $i++) {
			if($caracteres[$i] == "á" || $caracteres[$i] == "à" || $caracteres[$i] == "Á" || $caracteres[$i] == "À" || $caracteres[$i] == "ã" || $caracteres[$i] == "Ã" || $caracteres[$i] == "â" || $caracteres[$i] == "Â" || $caracteres[$i] == "å" || $caracteres[$i] == "Å") {
				$strlogin = str_replace($caracteres[$i], "a", $strlogin);
			}
			else if($caracteres[$i] == "ó" || $caracteres[$i] == "ò" || $caracteres[$i] == "Ó" || $caracteres[$i] == "Ò" || $caracteres[$i] == "õ" || $caracteres[$i] == "Õ" || $caracteres[$i] == "ô" || $caracteres[$i] == "Ô" || $caracteres[$i] == "ø" || $caracteres[$i] == "Ø") {
				$strlogin = str_replace($caracteres[$i], "o", $strlogin);
			}
			else if($caracteres[$i] == "é" || $caracteres[$i] == "É" || $caracteres[$i] == "è" || $caracteres[$i] == "È" || $caracteres[$i] == "ê" || $caracteres[$i] == "Ê") {
				$strlogin = str_replace($caracteres[$i], "e", $strlogin);
			}
			else if($caracteres[$i] == "ç" || $caracteres[$i] == "Ç") {
				$strlogin = str_replace($caracteres[$i], "c", $strlogin);
			}
			else if($caracteres[$i] == "í" || $caracteres[$i] == "Í") {
				$strlogin = str_replace($caracteres[$i], "i", $strlogin);
			}
			else if($caracteres[$i] == "ú" || $caracteres[$i] == "Ú") {
				$strlogin = str_replace($caracteres[$i], "u", $strlogin);
			}
			else if($caracteres[$i] == "ñ" || $caracteres[$i] == "Ñ") {
				$strlogin = str_replace($caracteres[$i], "n", $strlogin);
			}
			else if($caracteres[$i] == "æ" || $caracteres[$i] == "Æ") {
				$strlogin = str_replace($caracteres[$i], "ae", $strlogin);
			}
			else {
				$strlogin = str_replace($caracteres[$i], "-", $strlogin);
			}
		}

		if($strlogin[(strlen($strlogin) - 1)] == '-') {
			$strlogin = substr($strlogin, 0, strlen($strlogin) - 1);
		}

		$strlogin = str_replace("----", "-", $strlogin);
		$strlogin = str_replace("---", "-", $strlogin);
		$strlogin = str_replace("--", "-", $strlogin);
		
		return $strlogin;
	}
}

function randomCodeNews($size = '24', $tabela = 'news_emails') {
  $string = '';
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  for($i = 0; $i < $size; $i++) {
    $string .= $characters[mt_rand(0, (strlen($characters) - 1))];  
  }

  $query_rsExists = "SELECT id FROM ".$tabela." WHERE codigo = '$string'";
  $rsExists = DB::getInstance()->prepare($query_rsExists);
  $rsExists->execute();
  $totalRows_rsExists = $rsExists->rowCount();
  DB::close();

  if($totalRows_rsExists == 0) {
    return $string;
  }
  else {
    return randomCodeNews($size, $tabela);
  }
}

function email_social() {
	$query_rsRedes = "SELECT * FROM redes_sociais WHERE visivel = '1' ORDER BY ordem ASC";
	$rsRedes = DB::getInstance()->query($query_rsRedes);
	$row_rsRedes = $rsRedes->fetchAll();
	$totalRows_rsRedes = $rsRedes->rowCount();
	DB::close();	
		
	$rodape = "";

	if($totalRows_rsRedes > 0) {
		$rodape = "<table border='0' cellspacing='0' cellpadding='0'><tr>";
		
		foreach($row_rsRedes as $redes) {
      if($redes['link'] != "") {
        $rodape.="
					<td height='26' width='10'>&nbsp;</td>
					<td valign='middle'>
						<a href='".$redes['link']."'>
							<img src='".HTTP_DIR."/imgs/elem/social/".strtolower($redes['nome']).".png' height='26' alt='".$redes['nome']."' style='display:block' border='0'/>
						</a>
					</td>";
      }
    }

		$rodape .= "</tr></table>";
  }	
	
	return $rodape;
}

function tamanho_imagem2($imagem, $largura_disp, $altura_disp) {
	$tamanho = "";
	list($width, $height, $type, $attr) = getimagesize($imagem);

	if($width > $largura_disp || $height > $altura_disp) {			
		if(($largura_disp / $width) < ($altura_disp / $height)) {
			$tamanho = "width";
		}
		else {
			$tamanho = "height"; 
		}
	}

	return $tamanho;
}

function noHTML($input, $encoding = 'UTF-8') {
	$input = str_replace("\r\n", "_nline_", $input);
	$input = str_replace("\n", "_nline2_", $input);
	$input = htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
	
	$input = str_replace("&lowbar;nline&lowbar;", "\r\n", $input);
	$input = str_replace("&lowbar;nline2&lowbar;", "\n", $input);
	
	return $input;
}

function hasParameter($query, $param) {
	if(strpos($query, $param." ") !== false || strpos($query, $param.")") !== false || strpos($query, $param.",") !== false) {
		return true;
	}
	else {
		return false;
	}
}

//if $nome == 1 retorna o nome|| nome == 2 retorna o titulo
function get_meta_link($pag, $remote = 0, $nome = 0) {
	global $extensao, $row_rsCliente;
	$row_rsMeta = $GLOBALS['divs_metatags'][$pag];

	$ficheiro = $row_rsMeta['ficheiro'];
	$url = $row_rsMeta['url'];
	
	if($row_rsMeta) {
		if ($nome == 0){
			if($remote == 1) {
				echo ROOTPATH_HTTP."includes/pages/".$ficheiro;
			}
			else  {
				echo ROOTPATH_HTTP_LANG.$url;
			}
		} else if ($nome == 1 && $remote == 0){
			echo $row_rsMeta['pagina'];
		}else if ($nome == 1 && $remote == 1){
			return $ficheiro;
		}else{
			echo $row_rsMeta['title'];
		}
	}
}

function getFill($nome, $area = 1, $imagem = '') {
	$query_rsConfigImg = "SELECT * FROM config_imagens WHERE nome = :nome";
	$rsConfigImg = DB::getInstance()->prepare($query_rsConfigImg);
	$rsConfigImg->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
	$rsConfigImg->execute();
	$row_rsConfigImg = $rsConfigImg->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsConfigImg = $rsConfigImg->rowCount();
	DB::close();

	$proporcional = 1;
	
	if($totalRows_rsConfigImg > 0) {
		$nome_pasta = strtolower(str_replace(" ", "_", $row_rsConfigImg['nome']));
		
		if($row_rsConfigImg['imagem1']) {			
			$size = explode("x",$row_rsConfigImg['imagem1']);
			$width_img1 = $size[0];
			$height_img1 = $size[1];		
		}

		if($row_rsConfigImg['imagem2']) {
			$size = explode("x",$row_rsConfigImg['imagem2']);

			$width_img2 = $size[0];
			$height_img2 = $size[1];

			$prop_width = $width_img1 / $width_img2;
			$prop_height = $height_img1 / $height_img2;

			if($prop_width != $prop_height) {
				$proporcional = 0;
			}
		}
		
		if($row_rsConfigImg['imagem3']) {
			$size = explode("x",$row_rsConfigImg['imagem3']);

			$width_img3 = $size[0];
			$height_img3 = $size[1];

			$prop_width = $width_img2 / $width_img3;
			$prop_height = $height_img2 / $height_img3;

			if($prop_width != $prop_height) {
				$proporcional = 0;
			}		
		}

		if($row_rsConfigImg['imagem4']) {
			$size = explode("x",$row_rsConfigImg['imagem4']);

			$width_img4 = $size[0];
			$height_img4 = $size[1];

			$prop_width = $width_img3 / $width_img4;
			$prop_height = $height_img3 / $height_img4;

			if($prop_width != $prop_height) {
				$proporcional = 0;
			}		
		}

		if($row_rsConfigImg['imagem5']) {
			$size = explode("x",$row_rsConfigImg['imagem5']);

			$width_img5 = $size[0];
			$height_img5 = $size[1];

			$prop_width = $width_img4 / $width_img5;
			$prop_height = $height_img4 / $height_img5;

			if($prop_width != $prop_height) {
				$proporcional = 0;
			}		
		}
		
		if($row_rsConfigImg['imagem'.$area]) {
			if($area > 1) {
				$path = "imgs/".$nome_pasta."/fill".$area.".gif";
			}
			else {
				$path = "imgs/".$nome_pasta."/fill.gif";
			}
		}
		
		if(!$path || !file_exists(ROOTPATH.$path)) {
			$path = "imgs/".$nome_pasta."/fill.gif";
		}
		
		$min_height = "";
		$max_width = "";
		$style = "margin:auto;";
		
		$width_img = ${'width_img'.$area};
		$height_img = ${'height_img'.$area};

		if($row_rsConfigImg['max_width'.$area]) {
			$width_img = $row_rsConfigImg['max_width'.$area];
			$height_img = ($width_img * ${'height_img'.$area}) / ${'width_img'.$area};
		}

		$style .= " max-width:".$width_img."px;";
		$style .= " max-height:".$height_img."px;";

		if($row_rsConfigImg['min_height'.$area]) {
			$style .= " min-height:".$row_rsConfigImg['min_height'.$area]."px;";
		}

		//Específico para efeito de imagens listadas em scroll
		if($imagem) {
			list($width_temp, $height_temp, $type_temp, $attr_temp) = getimagesize($imagem);
			$width_img = $width_temp;
			$height_img = $height_temp;
		}
		
		$fillSize = ($height_img / $width_img) * 100;
		$fillSize = str_replace(",", ".", $fillSize);
		$img = '<div class="aspectRatioPlaceholder" style="'.$style.'">
			<div class="fill" style="padding-bottom: '.$fillSize.'%"></div>
		</div>';
				
		return $img;
	}
}

function tableExists($pdo, $table) {
  try {
    $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
  } 
  catch (Exception $e) {
    return FALSE;
  }

  return $result !== FALSE;
}

function str_text($message, $length) {
	if(strlen($message) > $length) {
		$message = substr($message, 0, ($length - 3));
		$message .= "...";
	}
	
	return strip_tags($message);
}

function text_link($link, $target, $text, $classes) {
	global $Recursos;
	
	$texto_link = $Recursos->Resources["saiba_mais"];
	$target_txt = "";
	$element = '';
	
	if($text) {
		$texto_link = $text;
	}

	if($target) {
		$target_txt = ' target="'.$target.'"';
	}
	
	if($link) {
		$element = '<a class="'.$classes.'" href="'.$link.'"'.$target_txt.'>'.$texto_link.'</a>';
	}
		
	return $element;
}

function arraySearch($array, $field, $search, $return = '', $keys = array()) {
  foreach($array as $key => $value) {
 		if($array[$field] == $search) {
 			if($return == "array") {
 				return $array;
 			}
 			else {
 				return $array[$return];
 			}
  		
  	}
  	else if(is_array($value)) {
			$sub = arraySearch($value, $field, $search, $return, array_merge($keys, array($key)));

      if(count($sub)) {
        return $sub;
      }
    } 
    else if($key == $field && $value == $search) {
			return $value;
		}
  }

  return array();
}

//seach array do tiago
function arraySearch2($array, $field, $value){	
	foreach($array as $key => $product){
		if ( $product[$field] === $value ) return $key;
	}
	return false;
}

/**
 * função para verificar o mime type do ficheiro
 * não basta só verificar a extensão do ficheiro, esta pode ser trocada
 *
 * Exemplos: $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
 * 					 $allowedTypes = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
 **/

function verificaMIME($allowedTypes, $fich, $name, $type = "image") {
	if($type == "image") {
	  $detectedType = exif_imagetype($fich);

	  if(!in_array($detectedType, $allowedTypes)) {
	    return false;
	  } 
	  else {
	  	return true;
	  }
	} 
	else {
		$mime = mime_content_type($fich);
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION)); 

		if(isset($allowedTypes[$mime]) && $allowedTypes[$mime] == $ext) {
			return true;
	  } 
	  else {
			return false;
	  }
	}
}

function fileUpload($path, $files, $prefix, $extensions = "", $allowedSize = 6000000) {	
	$anexos_array = array();
	$file_prefix = "";

	$imgs_dir = ROOTPATH."imgs/".$path."/";

	if(!$extensions) {
		$allowedExts = array(
		  "pdf", 
		  "doc", 
		  "docx",
		  "png",
		  "jpg",
		  "jpeg",
		  "gif",
		  "xls",
		  "xlsx"
		); 

		$allowedMimeTypes = array( 
		  'application/msword',
		  'application/pdf',
		  'application/vnd.ms-excel',
		  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		  'text/pdf',
		  'image/gif',
		  'image/jpeg',
		  'image/png'
		);

		$allowedTypes = array('application/msword'=>"doc", 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>"docx", "application/pdf"=>"pdf", "image/pjpeg"=>"jpg", "image/jpeg"=>"jpg", "image/jpg"=>"jpg", "image/png"=>"png", "image/x-png"=>"png", "image/gif"=>"gif", 'application/vnd.ms-excel'=>"xls", 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=>"xlsx");
	}

	$errors = "";

	if(!empty($files)) {
		for($i = 0; $i < count($files['name']); $i++) {
			if(count($files['name']) > 1) {
				$file_name = $files["name"][$i];
				$file_type = $files["type"][$i];
				$file_tmpname = $files["tmp_name"][$i];
				$file_size = $files["size"][$i];
				$error = $files["error"][$i];
			}
			else if((is_array($files["name"][0]) && !empty($files["name"][0]) && sizeof($files["name"][0]) > 1) || is_array($files["name"])) {
				$file_name = $files["name"][0];
				$file_type = $files["type"][0];
				$file_tmpname = $files["tmp_name"][0];
				$file_size = $files["size"][0];
				$error = $files["error"][0];
			}
			else {
				$file_name = $files["name"];
				$file_type = $files["type"];
				$file_tmpname = $files["tmp_name"];
				$file_size = $files["size"];
				$error = $files["error"];
			}

			if($file_size > 0 && $error == 0) {
				if($prefix) {
					$file_prefix = preg_replace('/[^0-9a-zA-Z_]/',"",$prefix);
				}

				$extension = strtolower(end(explode(".", $file_name)));

				if($file_size > $allowedSize) {
					$errors .= "1**".$file_name.";";
				}
				else if(!verificaMIME($allowedTypes, $file_tmpname, $file_name, "doc") && !verificaMIME($allowedTypes, $file_tmpname, $file_name, "docx") && !verificaMIME($allowedTypes, $file_tmpname, $file_name, "pdf") && !verificaMIME($allowedTypes, $file_tmpname, $file_name, "jpg") && !verificaMIME($allowedTypes, $file_tmpname, $file_name, "png") && !verificaMIME($allowedTypes, $file_tmpname, $file_name, "gif")) {
					$errors .= "2**".$file_name.";";
	      }
				else if((!in_array($extension, $allowedExts)) && (!in_array($file_type, $allowedMimeTypes))) {
					$errors .= "2**".$file_name.";";
				}
				else {
					if($file_tmpname) {
						$id_file = date("his").'_'.$i.'_'.rand(0,9999);
						$nome_img = verifica_nome(utf8_decode($prefix.$file_name));
						$nome_file = $id_file."_".$nome_img;
						
						move_uploaded_file($file_tmpname, $imgs_dir.$nome_file);
						
						$file = $imgs_dir.$nome_file;		
						@chmod($file, 0777);	
										
						if($nome_file) {
							$anexos_array[] = array($file, $nome_file);	
						}
					}
				}
			}
		}		
	}

	$anexos_array[] = array("erros", $errors);

	return $anexos_array;
}

/*function insereTermoPesquisa($str) {
	$str = htmlspecialchars($str, ENT_COMPAT, 'UTF-8');

	$query_rsPesquisa = "SELECT id FROM termos_pesquisa WHERE termo = :termo";
	$rsPesquisa = DB::getInstance()->prepare($query_rsPesquisa);
	$rsPesquisa->bindParam(':termo', $str, PDO::PARAM_STR, 5);
	$rsPesquisa->execute();
	$row_rsPesquisa = $rsPesquisa->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsPesquisa = $rsPesquisa->rowCount();

	if($totalRows_rsPesquisa > 0) {
		$insertSQL = "UPDATE termos_pesquisa SET counter = counter + 1 WHERE termo = :termo";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':termo', strtolower($str), PDO::PARAM_STR, 5);
    $rsInsert->execute();
	}
	else {
    $insertSQL = "INSERT INTO termos_pesquisa (id, termo) VALUES ('', :termo)";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':termo', strtolower($str), PDO::PARAM_STR, 5);
    $rsInsert->execute();
	}

	DB::close();
}*/

function isValidCaptcha($captcha) {
	try {
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => CAPTCHA_SECRET, 
			'response' => $captcha, 
			'remoteip' => $_SERVER['REMOTE_ADDR']
		); 
		$options = array( 
			'http' => array( 
				'header' => "Content-type: application/x-www-form-urlencoded\r\n", 
				'method' => 'POST', 
				'content' => http_build_query($data) 
			)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		return json_decode($result)->success;
	} 
	catch (Exception $e) {
		return "erro";
	}
}

/*==================================================================
=            Quando der erro no reCaptcha utilizar este            =
==================================================================*/
/*function isValidCaptcha($captcha) {
	try {
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => CAPTCHA_SECRET, 
			'response' => $captcha, 
			'remoteip' => $_SERVER['REMOTE_ADDR']
		); 

		$result = url_get_contents($url, $data);

		return json_decode($result)->success;
	} 
	catch (Exception $e) {
		return "erro";
	}
}

function url_get_contents ($Url, $data) {
  if (!function_exists('curl_init')){ 
      die('CURL is not installed!');
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $Url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HEADER, "Content-type: application/x-www-form-urlencoded");
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}*/
/*=====  End of Quando der erro no reCaptcha utilizar este  ======*/


function campanhaValida($datai, $dataf) {
	global $Recursos, $extensao;

	$return = "";
	
	if($datai && !$dataf) {
		$return = str_replace("#de#", data_hora($datai, $extensao), $Recursos->Resources["campanha_de"]);
	}
	else if($datai && $dataf) {
		$return = str_replace("#de#", data_hora($datai, $extensao), $Recursos->Resources["campanha_de_ate"]);
		$return = str_replace("#ate#", data_hora($dataf, $extensao), $return);
	}
	else if(!$datai && $dataf) {
		$return = str_replace("#ate#", data_hora($dataf, $extensao), $Recursos->Resources["campanha_ate"]);
	}

	return $return;
}

function imageAnuncioMasc($image, $masc, $local, $proporcional) {
	$stamp = imagecreatefrompng($masc);
	$sizes = getimagesize($image);	
	$imageW = $sizes[0];
	$imageH = $sizes[1];	
	$imageType = $sizes[2];
	$imageType = image_type_to_mime_type($imageType);
	
	switch($imageType) {
		case "image/gif":
			$im = imagecreatefromgif($image); 
			break;
		case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$im = imagecreatefromjpeg($image); 
			break;
		case "image/png":
		case "image/x-png":
			$im = imagecreatefrompng($image); 
			break;
	}

	// Set the margins for the stamp and get the height/width of the stamp image
	$marge_right = 10;
	$marge_left = 10;
	$marge_bottom = 10;
	$marge_top = 10;

	$sx = imagesx($stamp); //Obtem a largura da marca d'água
	$sy = imagesy($stamp); //Obtem a altura da marca d'água

	$locationX = 0; //Define a localização na largura da imagem
	$locationY = 0;	//Define a localização na altura da imagem

	//Verifica se a marca d'água vai ser proporcional às dimensões da imagem. Se não for proporcional, a marca d'água vai ter as dimensões originais na imagem
	if($proporcional == 1) {
		$newWatermarkWidth = $sizes[0]/2;
		$newWatermarkHeight = $sy * $newWatermarkWidth / $sx;
	}
	else {
		$newWatermarkWidth = $sx;
		$newWatermarkHeight = $sy;
	}

	if($local == 1) { // Marca d'água no centro da imagem
		$locationY = $sizes[1]/2 - $newWatermarkHeight/2;
		$locationX = $sizes[0]/2 - $newWatermarkWidth/2;
	}
	if($local == 2) { // Marca d'água no topo da imagem
		$locationY = $marge_top;
		$locationX = $sizes[0]/2 - $newWatermarkWidth/2;
	}
	if($local == 3) { // Marca d'água no fundo da imagem
		$locationY = imagesy($im) - $newWatermarkHeight - $marge_bottom;
		$locationX = $sizes[0]/2 - $newWatermarkWidth/2;
	}
	if($local == 4) { // Marca d'água no topo esquerdo da imagem
		$locationY = $marge_top;
		$locationX = $marge_left;
	}
	if($local == 5) { // Marca d'água no topo direito da imagem
		$locationY = $marge_top;
		$locationX = imagesx($im) - $newWatermarkWidth - $marge_right;
	}
	if($local == 6) { // Marca d'água no centro esquerdo da imagem
		$locationY = $sizes[1]/2 - $newWatermarkHeight/2;
		$locationX = $marge_left;
	}
	if($local == 7) { // Marca d'água no centro direito da imagem
		$locationY = $sizes[1]/2 - $newWatermarkHeight/2;
		$locationX = imagesx($im) - $newWatermarkWidth - $marge_right;
	}
	if($local == 8) { // Marca d'água no fundo esquerdo da imagem
		$locationY = imagesy($im) - $newWatermarkHeight - $marge_bottom;
		$locationX = $marge_left;
	}
	if($local == 9) { // Marca d'água no fundo direito da imagem
		$locationY = imagesy($im) - $newWatermarkHeight - $marge_bottom;
		$locationX = imagesx($im) - $newWatermarkWidth - $marge_right;
	}

	imagecopyresized($im, $stamp, $locationX, $locationY, 0, 0, $newWatermarkWidth, $newWatermarkHeight, imagesx($stamp), imagesy($stamp));

	switch($imageType) {
		case "image/gif":
			imagegif($im,$image); 
			break;
		case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			imagejpeg($im,$image);
			break;
		case "image/png":
		case "image/x-png":
			imagepng($im, $image);
			break;
	}
	
	imagedestroy($im);	
}

//Devolve o ficheiro HTML indicado já com as variávéis base substituidas (caminhos, cores, nome site, etc...)
function getHTMLTemplate($nome, $template=0) {
	$formcontent = "";

	$filename = ROOTPATH."templates/".$nome;

	if(file_exists($filename)){
		$fp = fopen($filename, "r");
		$formcontent = fread($fp, filesize($filename));
		fclose($fp);	
	
		if(strpos($formcontent, '#rootpath#') !== false) {
			$formcontent = str_replace('#rootpath#', ROOTPATH_HTTP, $formcontent);
		}
		if(strpos($formcontent, '#url#') !== false) {
			$formcontent = str_replace('#url#', str_replace(array('http://', 'https://'), '', HTTP_DIR), $formcontent);
		}
		if(strpos($formcontent, '#nome_site#') !== false) {
			$formcontent = str_replace('#nome_site#', NOME_SITE, $formcontent);
		}
		if(strpos($formcontent, '#cor_site#') !== false) {
			$formcontent = str_replace('#cor_site#', COR_SITE, $formcontent);
		}
		if(strpos($formcontent, '#blocos#') !== false) {
			$formcontent = str_replace('#blocos#', $blocos, $formcontent);
		}
	}

	return $formcontent;
}

function getImage($imagem, $local) {
	$img = "elem/geral.svg";
  
  if($imagem && file_exists(ROOTPATH.'imgs/'.$local.'/'.$imagem)) {
    $img = $local.'/'.$imagem;
	} 

	return $img;
}

/**
 * função para verificar se o servidor tem ceritficado SSL instalado
 * é usada na criação de cookies, para colocar o parâmetro secure a true ou false
 **/
function isSecure() {
  return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
}

class csrf {
	public function get_token_id() {
    if(isset($_SESSION['token_id'])) { 
      return $_SESSION['token_id'];
    } 
    else {
			$token_id = $this->random(10);
			$_SESSION['token_id'] = $token_id;
			
			return $token_id;
    }
	}
	
	public function get_token() {
		if(isset($_SESSION['token_value'])) {
			return $_SESSION['token_value']; 
		} 
		else {
			$token = hash('sha256', $this->random(500));
			$_SESSION['token_value'] = $token;
			return $token;
		}
	}
	
	public function check_valid($method) {
		if($method == 'post' || $method == 'get') {
			$post = $_POST;
			$get = $_GET;
			
			if(isset(${$method}[$this->get_token_id()]) && (${$method}[$this->get_token_id()] == $this->get_token())) {
				return true;
			} 
			else {
				return false;   
			}
		} 
		else {
			return false;   
		}
	}
	
	public function form_names($names, $regenerate) {
    $values = array();
    
    foreach($names as $n) {
			if($regenerate == true) {
				unset($_SESSION[$n]);
			}

			$s = isset($_SESSION[$n]) ? $_SESSION[$n] : $this->random(10);
			$_SESSION[$n] = $s;
			$values[$n] = $s;       
    }

    return $values;
	}
	
	private function random($len) {
    if(function_exists('openssl_random_pseudo_bytes')) {
			$byteLen = intval(($len / 2) + 1);
			$return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $len);
    } 
    else if(@is_readable('/dev/urandom')) {
			$f = fopen('/dev/urandom', 'r');
			$urandom = fread($f, $len);
			fclose($f);
			$return = '';
    }
 
    if(empty($return)) {
			for($i = 0;$i < $len; ++$i) {
				if(!isset($urandom)) {
					if($i % 2 == 0) {
						mt_srand(time() % 2147 * 1000000 + (double)microtime() * 1000000);
					}

					$rand = 48 + mt_rand() % 64;
				} 
				else {
					$rand = 48 + ord($urandom[$i]) % 64;
				}

				if($rand > 57) {
					$rand += 7;
				}
				if($rand > 90) {
					$rand += 6;
				}

				if($rand == 123) {
					$rand = 52;
				}
				if($rand == 124) {
					$rand = 53;
				}

				$return .= chr($rand);
			}
    }
    
    return $return;
	}
}

function arrayDescSort($item1, $item2) {
  if($item1['OrderTipo'] == $item2['OrderTipo']) {
  	return 0;
  }

  return ($item1['OrderTipo'] > $item2['OrderTipo']) ? 1 : -1;
}

function check_value(&$value) {
  $value = utf8_encode($value);
  $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
  $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function pre($value)
{
	echo '<pre>';
   print_r($value);
   echo "</pre>";

}

function get_banner_slide_tras()
{
	$query_rsConfig = "SELECT trans_time FROM config ".$extensao."";
	$rsPromoConfig = DB::getInstance()->prepare($query_rsConfig);
	$rsPromoConfig->execute();
	$row_rsConfig = $rsPromoConfig->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsConfig = $rsPromoConfig->rowCount();
	$trans_time = (!empty($row_rsConfig['trans_time'])) ? $row_rsConfig['trans_time'] : 4000;
	return $trans_time; 	
}

?>