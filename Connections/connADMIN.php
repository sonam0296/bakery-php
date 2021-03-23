<?php
ob_start();
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);


# 0 localhost
# 1 suez
# 2 proposta
# 3 produção
define("ENV", 2);



ini_set("default_charset", "UTF-8");

//FRONTEND
if(!strstr($_SERVER["PHP_SELF"], "consola/")) {
	ini_set("display_errors", "0");
}

//CONSOLA
if(strstr($_SERVER["PHP_SELF"], "consola/")) {
	ini_set("display_errors", "1");
}

//Usado no Google Analytics (codigo_antes_body.php)
define("ANALYTICS", "UA-99999999-9");
define("SERVIDOR", "bbakery.co.uk/beta");
define("SERVIDOR_ARRAY", serialize(array("bbakery.co.uk/beta", "bbakery.co.uk/beta")));
$array_servidor = unserialize(SERVIDOR_ARRAY);
define("NOME_SITE", "Bismillah");
define("COR_SITE", "#B88F5A");
define("NTG_PROPO", 0);

// BASE DE DADOS
define("DB_HOSTNAME", "localhost");
if(!in_array($_SERVER['HTTP_HOST'], $array_servidor) && NTG_PROPO==0) { 
	####LOCAL_BD####
	define("DB_USERNAME", "ud4owam3mcgig");
	define("DB_PASSWORD", "ud4owam3mcgig");
	define("DB_DATABASE", "db7u9msfz8mqrn");
	define("CAPTCHA_KEY", '6Lcyj2QaAAAAAAzNuFrbhymvPEPIrCaQzjp7u_uu');
	define("CAPTCHA_SECRET", '6Lcyj2QaAAAAAGoYL3jeevUeU39-HgQUuiWM__dV');
	define("MAPS_KEY", 'AIzaSyCnjcj-bGomkybPutgzFPg4uvAZ3yHOI9w');
}
else { 

	####ONLINE_BD####
	define("DB_USERNAME", "ud4owam3mcgig");
	define("DB_PASSWORD", 'ud4owam3mcgig');
	define("DB_DATABASE", "db7u9msfz8mqrn");
	define("CAPTCHA_KEY", '6Lcyj2QaAAAAAAzNuFrbhymvPEPIrCaQzjp7u_uu');
	define("CAPTCHA_SECRET", '6Lcyj2QaAAAAAGoYL3jeevUeU39-HgQUuiWM__dV');
	define("MAPS_KEY", '');
}

//VARIAVEIS E-COMMERCE
define("LOGIN", "TYPE2"); //ALL para ver tudo (fase de teste); TYPE# - substitur cardinal por numero do design (ex: TYPE1);
define("LOGIN_SOCIAL", "0"); //0=Nenhum; 1=Facebook; 2=Google; 3=Todos;

define("CATEGORIAS", 2); //1 - uma categoria por produto / 2 - várias categorias por produto
define("CATEGORIAS_NIVEL", 2); //Níveis das categorias

define("PESQ_TYPE", 1); //0=nenhum; 1=Modal; 2=autocomplete;
define("CARRINHO_LOGIN", 1); //1 para ser ter que fazer login ou 0 para poder continuar sem login;
define("ECOMMERCE", 1);
define("ECC_MARCAS", 0);
define("CARRINHO_SESSION", "natura_saudesession");
define("WISHLIST_SESSION", "natura_saudeWish");
define("CARRINHO_PONTOS", 0);
define("CARRINHO_SALDO", 0);
define("CARRINHO_TAMANHOS", 1);
define("CARRINHO_PORTES", 1);
define("CARRINHO_CODIGOS", 1);
define("CARRINHO_EMBRULHO", 0);
define("CARRINHO_CONVIDAR", 0);
define("CARRINHO_DESCONTOS", 1); // Descontos por quantidade, 1:Geral, 2:Papelaria.
define("CARRINHO_VOLTAR", "index.php");

$ecommerce_ativo = 0;
if(ECOMMERCE == 1) {
	$query_rsConfig = "SELECT ecommerce FROM config_ecommerce WHERE id=1";
	$rsConfig = DB::getInstance()->prepare($query_rsConfig);
	$rsConfig->execute();
	$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsConfig = $rsConfig->rowCount();
	DB::close();

	$ecommerce_ativo = $row_rsConfig["ecommerce"];
}

//Indica se os produtos estão para venda ou apenas para pedidos de contato
define("ECOMMERCE_ATIVO", $ecommerce_ativo);

if(!in_array($_SERVER['HTTP_HOST'], $array_servidor) && NTG_PROPO==0) { 

	####LOCAL_PATH####
	if (ENV == 0) {

		define("HTTP_DIR", "http://bbakery.co.uk/beta/"); //Usado nas Newsletters e Encomendas (encomendas-edit.php, newsletter-edit.php e newsletter-processar-envio.php)
		define("ROOTPATH", dirname(__DIR__)."/");
		define("ROOTPATH_HTTP", "http://".$_SERVER["HTTP_HOST"]."/beta/");
	}
	else{ 
		define("HTTP_DIR", "http://bbakery.co.uk/beta/"); //Usado nas Newsletters e Encomendas (encomendas-edit.php, newsletter-edit.php e newsletter-processar-envio.php)
		define("ROOTPATH", dirname(__DIR__)."/");
		define("ROOTPATH_HTTP", "http://".$_SERVER["HTTP_HOST"]."/beta/");
	}
}
else { 

	####ONLINE_BD####
	define("HTTP_DIR", "http://bbakery.co.uk/beta/"); //Usado nas Newsletters e Encomendas (encomendas-edit.php, newsletter-edit.php e newsletter-processar-envio.php)
	define("ROOTPATH", dirname(__DIR__)."/");
	define("ROOTPATH_HTTP", "http://".$_SERVER["HTTP_HOST"]."/beta/");
}

define("ROOTPATH_CONSOLA", ROOTPATH."consola/"); 
define("ROOTPATH_ADMIN", ROOTPATH."consola/admin/"); 

define("ROOTPATH_HTTP_BLOG", ROOTPATH_HTTP."blog/");
define("ROOTPATH_HTTP_CONSOLA", ROOTPATH_HTTP."consola/"); 
define("ROOTPATH_HTTP_ADMIN", ROOTPATH_HTTP."consola/admin/"); 

// define("FONT_SITE", "custom: {
// 	families: ['Metropolis:n4,n6,n7', 'Unna:i4,i7'],
// 	urls: ['".ROOTPATH_HTTP."css/fonts.css']
// }");

define("FONT_SITE", "google: {families: ['Poppins:300,400,500,600,700']}");

class db {

	/*** Declare instance ***/
	private static $instance = NULL;
	
	/**
	*
	* the constructor is set to private so
	* so nobody can create a new instance using new
	*
	*/
	private function __construct() {
	  /*** maybe set the db name here later ***/
	}
	
	/**
	*
	* Return DB instance or create intitial connection
	*
	* @return object (PDO)
	*
	* @access public
	*
	*/
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new PDO("mysql:host=".DB_HOSTNAME.";dbname=".DB_DATABASE, DB_USERNAME, DB_PASSWORD);
			//self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		
		return self::$instance;
	}
	
	public static function close() {
		if (self::$instance) {
			self::$instance = null;
		}
	}
	
	/**
	*
	* Like the constructor, we make __clone private
	* so nobody can clone the instance
	*
	*/
	private function __clone(){
	}

} /*** end of class ***/

$query_rsManutencao = "SELECT * FROM config WHERE id=1";
$rsManutencao = DB::getInstance()->prepare($query_rsManutencao);
$rsManutencao->execute();
$row_rsManutencao = $rsManutencao->fetch(PDO::FETCH_ASSOC);
$totalRows_rsManutencao = $rsManutencao->rowCount();
DB::close();

$ips = explode(",", str_replace(" ", "", $row_rsManutencao["ips"]));

$ip=$_SERVER["REMOTE_ADDR"];

if($ip==""){
	$ip=$HTTP_SERVER_VARS["REMOTE_ADDR"];
}

if($row_rsManutencao["manutencao"] == 1 && (!in_array($ip, $ips)) && $is_cron_file!=1){
	if(!strstr($_SERVER["PHP_SELF"], "manutencao.php") && !strstr($_SERVER["PHP_SELF"], "consola/")){
		header("Location: ".ROOTPATH_HTTP."manutencao.php");
		exit();
	}
}

if (!isset($_SESSION)) {
  session_start();
}

/* VARIÁVEIS POR DEFEITO PARA CRONS */
if($is_cron_file == 1){
	//lingua
	$lang = "en";
	$_SESSION["LANG"] = 'en';

	//moeda
	$_COOKIE['SITE_currency'] = "lb-&pound;";
}

/*HELPERS*/
include_once(ROOTPATH.'sendMail/send_mail.php');
include_once(ROOTPATH.'helpers/funcoes_base.php');
include_once(ROOTPATH.'helpers/utilizadores.php');
include_once(ROOTPATH.'helpers/produtos.php');
include_once(ROOTPATH.'helpers/carrinho.php');

function pr($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}


// Variável para usar ao criar o cookie, se o servidor tiver certificado SSL instalado torna o cookie seguro
$cookie_secure = false;
if(function_exists('isSecure') && isSecure()) {
	$cookie_secure = true;
}

// Se não vier do redirect / caso venha tem lá o include, depois de incluir o línguas
if(!strstr($_SERVER['REQUEST_URI'],"/consola/")) {
	
	include_once(ROOTPATH.'MobileDetect.php');
	$detect = new Mobile_Detect;

	if(!$redirect) {
		require_once(ROOTPATH.'linguasLG.php'); 
		$extensao=$Recursos->Resources["extensao"];
		
		include_once(ROOTPATH.'helpers/sessions.php');
		include_once(ROOTPATH.'helpers/handle-forms.php');
	}
}
?>