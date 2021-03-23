<?php
$path = explode("/", dirname(__DIR__));
unset($path[count($path) -1]);
$path = implode($path, "/");



require_once($path."/Connections/connADMIN.php");



include("activity-rpc.php");

$get_act = get_activity(1);

last_activity();

if(!isset($_SESSION)) {

  session_start();

}



include("funcoes.php");



//verifica utilizador

$username=$_SESSION['ADMIN_USER'];

$password=$_SESSION['ADMIN_PASS'];



$query_rsUser = "SELECT * FROM acesso WHERE username=:username AND activo='1'";

$rsUser = DB::getInstance()->prepare($query_rsUser);

$rsUser->bindParam(':username', $username, PDO::PARAM_STR, 5);

$rsUser->execute();

$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);

$totalRows_rsUser = $rsUser->rowCount();

DB::close();



if($username=='' || $totalRows_rsUser==0){

	header("Location: ".ROOTPATH_HTTP_CONSOLA."index.php");	

	exit();

}



//verifica password

$password_db=$row_rsUser['password'];

$password_salt_db=$row_rsUser['password_salt'];



$password_db_final = hash('sha256', $password_salt_db . $password_db);



if($password=='' || ($password!=$password_db)){

	header("Location: ".ROOTPATH_HTTP_CONSOLA."index_lock.php");	

	exit();

}



$consolaLG__query="SELECT * FROM linguas WHERE ativo=1 AND consola=1";

$consolaLG = DB::getInstance()->prepare($consolaLG__query);

$consolaLG->execute();

$row_rsconsolaLG = $consolaLG->fetchAll();

$consolaLG_count = $consolaLG->rowCount();

DB::close();



/*

$hash = hash('sha256', $password1);



FUNCTION createSalt()

{

$text = md5(uniqid(rand(), TRUE));

RETURN substr($text, 0, 3);

}



$salt = createSalt();

$password = hash('sha256', $salt . $hash);



*/



if($row_rsUser['lingua'] != "") {

	include_once(ROOTPATH_CONSOLA."linguas/".$row_rsUser['lingua'].".php");

	$lingua_consola = $row_rsUser['lingua'];

}

else if(isset($_SESSION['lang']) && $_SESSION['lang'] !== "") {

	include_once(ROOTPATH_CONSOLA."linguas/".$_SESSION['lang'].".php");

	$lingua_consola = $_SESSION['lang'];

}

else {

	include_once(ROOTPATH_CONSOLA."linguas/pt.php");

	$lingua_consola = "pt";

}

$RecursosCons = new RecursosCons();



$nome_comp=$row_rsUser['nome'];

$nom_exp=explode(" ",$nome_comp);



$tam=count($nom_exp);



if($tam>1){

	$nome_mostra=$nom_exp[0]." ".$nom_exp[$tam-1];

}else{

	$nome_mostra=$nom_exp[0];

}





$id_user=$row_rsUser['id'];





//LINGUA PARA EDITAR

if($_SESSION['LG_EDITAR']){

	$lg_extensao=$_SESSION['LG_EDITAR'];

}else{

	

	$query_rsLg = "SELECT * FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC limit 1";

	$rsLg = DB::getInstance()->prepare($query_rsLg);

	$rsLg->execute();

	$row_rsLg = $rsLg->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLg = $rsLg->rowCount();

	DB::close();

	

	$lg_extensao=$row_rsLg['sufixo'];

}



if($_GET['lg']){

	

	$lg=$_GET['lg'];	

	

	$query_rsLg = "SELECT * FROM linguas WHERE id=:lg AND visivel='1'";

	$rsLg = DB::getInstance()->prepare($query_rsLg);

	$rsLg->bindParam(':lg', $lg, PDO::PARAM_STR, 5);	

	$rsLg->execute();

	$row_rsLg = $rsLg->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLg = $rsLg->rowCount();

	DB::close();

	

	if($totalRows_rsLg>0) $lg_extensao=$row_rsLg['sufixo'];

}



if($lg_extensao=='') $lg_extensao='pt';



$_SESSION['LG_EDITAR']=$lg_extensao;

$extensao="_".$lg_extensao;





//REDIRECCIONAMENTOS 301

function redirectURL($url_old, $url_new, $lang, $tipo='editar'){

	

	if($url_new!="" && $url_old!=""){

		

		$data_ini=date('Y-m-d H:i:s', strtotime('-1day'));

		

		$query_rsP = "SELECT * FROM redirects_301 WHERE url_old='$url_old' AND lang='$lang'";

		$rsP = DB::getInstance()->prepare($query_rsP);

		$rsP->bindParam(':lg', $lg, PDO::PARAM_STR, 5);	

		$rsP->execute();

		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsP = $rsP->rowCount();

		DB::close();

		

		if($totalRows_rsP>0){

			$insertSQL = "UPDATE redirects_301 SET url_new='$url_new' WHERE url_old='$url_old' AND lang='$lang'";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->execute();

			DB::close();

		}else{			

			$query_rsP = "SELECT * FROM redirects_301 WHERE url_new='$url_old' AND data>='$data_ini' AND lang='$lang'";

			$rsP = DB::getInstance()->prepare($query_rsP);

			$rsP->bindParam(':lg', $lg, PDO::PARAM_STR, 5);	

			$rsP->execute();

			$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsP = $rsP->rowCount();

			DB::close();

			

			

			if($totalRows_rsP==0){

				$insertSQL = "INSERT INTO redirects_301 (url_old, url_new, data, lang) VALUES ('$url_old', '$url_new', NOW(), '$lang')";

				$rsInsert = DB::getInstance()->prepare($insertSQL);

				$rsInsert->execute();

				DB::close();

		

				if($tipo=="editar"){

					$insertSQL = "DELETE FROM redirects_301 WHERE url_new='$url_old' AND data>'$data_ini' AND lang='$lang'";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->execute();

					DB::close();

				}

			}

		}

		

		

		//URL ANTIGO ESTÁ COMO NOVO	

		$insertSQL = "UPDATE redirects_301 SET url_new='$url_new' WHERE url_new='$url_old' AND lang='$lang'";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->execute();

		DB::close();

		

		if($tipo=="editar"){

			$insertSQL = "DELETE FROM redirects_301 WHERE url_new=url_old AND data>'$data_ini' AND lang='$lang'";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->execute();

			DB::close();

		}

	}		

}



if(tableExists(DB::getInstance(), 'newsletters_config')) {

	$query_rsConfigNews = "SELECT * FROM newsletters_config WHERE id='1'";

	$rsConfigNews = DB::getInstance()->prepare($query_rsConfigNews);

	$rsConfigNews->execute();

	$row_rsConfigNews = $rsConfigNews->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsConfigNews = $rsConfigNews->rowCount();

	DB::close();

}

?>