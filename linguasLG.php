<?php

if(!isset($_SESSION)) {

  session_start();

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

		// to get crawlers string used in function uncomment it

		// it is better to save it in string than use implode every time

		// global $crawlers

		// $crawlers_agents = implode('|',$crawlers);

		

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



$pagina = $_SERVER['REQUEST_URI'];



if($_SERVER['QUERY_STRING'] != "") {

	$pagina = str_replace("?".$_SERVER['QUERY_STRING'], "", $pagina);

	$url_loader = str_replace("?".$_SERVER['QUERY_STRING'], "", $pagina);

	$url_form = str_replace("?".$_SERVER['QUERY_STRING'], "", $pagina);

	

	$queryString_rsP2 = "";

	$url_loader2 = "";

	$url_form2 = "";



	if(!empty($_SERVER['QUERY_STRING'])) {

	  $params = explode("&", $_SERVER['QUERY_STRING']);

	  $newParams = array();

	  $newParams2 = array();

	  $newParams3 = array();



	  foreach($params as $param) {

			if(stristr($param, "lg=") == false) {

			  array_push($newParams, $param);

			}

			if(stristr($param, "p=") == false) {

			  array_push($newParams2, $param);

			}

			if(stristr($param, "env=") == false &&

				stristr($param, "alt=") == false &&

				stristr($param, "erro=") == false &&

				stristr($param, "terminado=") == false &&

				stristr($param, "alterado=") == false) {

			  array_push($newParams3, $param);

			}

	  }



	  if(count($newParams) != 0) {

			$queryString_rsP2 = "?" . htmlentities(implode("&", $newParams));

	  }

	  if(count($newParams2) != 0) {

			$url_loader2 = "?" . htmlentities(implode("&", $newParams2));

	  }

	  if(count($newParams3) != 0) {

			$url_form2 = "?" . htmlentities(implode("&", $newParams3));

	  }

	}



	$queryString_rsP2 = sprintf("%s", $queryString_rsP2);

	$url_loader2 = sprintf("%s", $url_loader2);

	$url_form2 = sprintf("%s", $url_form2);



	if($queryString_rsP2 != "") {

		$pagina .= $queryString_rsP2."&";

	}

	else {

		$pagina .= "?";

	}



	if($url_loader2 != "") {

		$url_loader .= $url_loader2."&";

	}

	else {

		$url_loader .= "?";

	}



	if($url_form2 != "") {

		$url_form .= $url_form2."&";

	}

	else {

		$url_form .= "?";

	}

} 

else {

	if(strpos($pagina, "?") === false) {

		$pagina .= "?";

	}



	$url_loader = $pagina;

	$url_form = $pagina;

}



$lingua = ""; 

if(isset($_GET['lg'])) {

	$lingua = $_GET['lg'];

}



if($lingua != '') {

	if($lingua == 1) {

		$_SESSION["LANG"] = 'pt';

	}

	else {

		$_SESSION["LANG"] = 'en';

	}

	

	$lang = $_SESSION["LANG"];



	//PARA NÃO MANTER O ?lg=1 NO URL

	header("Location: ".html_entity_decode(substr($pagina, 0, strlen($pagina) - 1)));

}

else {

	$lang = $_SESSION["LANG"];

}



ini_set("memory_limit", "10000M");

ini_set('max_execution_time', 6000);



$ip = $_SERVER['REMOTE_ADDR'];



if($ip == "") {

	$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

}



if($lang == '') {

	$USER_AGENT = $_SERVER['HTTP_USER_AGENT'];

	

	if(!crawlerDetect($USER_AGENT)) {

		//se ficar no nosso alojamento chama o da Netgocio

		//$pais = @file_get_contents("http://www.netgocio.pt/geoplugin/index.php?ip=".$ip);

		

		//senão chama o do proprio site

		//$pais = @file_get_contents(ROOTPATH_HTTP."geoplugin/index.php?ip=".$ip);

	}



	$pais = 'EN';

	

	if($pais == 'PT' || $pais == "" || crawlerDetect($USER_AGENT)) {

		$lang = "pt";

	}

	else {

		$lang = "en";

	}



	$_SESSION["LANG"] = $lang;

}



$lang_url = $lang;



$pagina_pt = str_replace("/".$lang_url."/", "/pt/", $pagina);

$pagina_en = str_replace("/".$lang_url."/", "/en/", $pagina);



include_once("linguas/".$lang.".php");

$className = 'Recursos_'.$lang;

$Recursos = new $className();



//URL Para linguas

$query_rsLinguasTot = "SELECT id FROM linguas WHERE visivel = 1 AND ativo = 1 ORDER BY id ASC";

$rsLinguasTot = DB::getInstance()->prepare($query_rsLinguasTot);

$rsLinguasTot->execute();

$totalRows_rsLinguasTot = $rsLinguasTot->rowCount();



$ROOTPATH_HTTP = ROOTPATH_HTTP;

$ROOTPATH_HTTP_BLOG = ROOTPATH_HTTP_BLOG;



if($totalRows_rsLinguasTot > 1) {

  $ROOTPATH_HTTP .= $lang."/";

  $ROOTPATH_HTTP_BLOG .= $lang."/";

}



define("ROOTPATH_HTTP_LANG", $ROOTPATH_HTTP);

define("ROOTPATH_HTTP_BLOG_LANG", $ROOTPATH_HTTP_BLOG);

?>