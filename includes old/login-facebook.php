<?php error_reporting(E_ALL); ini_set("display_errors", "1"); ?>
<?php require_once('../Connections/connADMIN.php'); ?>
<?php include_once(ROOTPATH.'helpers/funcoes_base.php'); ?>
<?php 

// *** Validate request to login to this site.
if(!isset($_SESSION)) {
  session_start();
}

$lang=$_SESSION["LANG"];
if($lang=='') {
	$lang='pt';
	$_SESSION["LANG"]="pt";
}

include_once(ROOTPATH."linguas/".$lang.".php");
$className = 'Recursos_'.$lang;
$Recursos = new $className();

$tipo=$_GET['tipo'];

//COMEÇA SCRIPT PARA LOGIN COM FACEBOOK
require_once(ROOTPATH.'facebook/src/facebook.php');

$facebook = new Facebook(array(
 'appId'  => '904440543042931',
 'secret' => '4bbac30d2a8d93ed33e7dada89cfddc8',
));

$user = $facebook->getUser();

if($user) {
  try {
    $user_profile = $facebook->api('/me?fields=id,name,email');
  } 
  catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}else {
  $url = $facebook->getLoginUrl(array(
		'scope' => 'email, user_location, user_hometown',
		'display' => 'popup'
	));
}

$response = 0;

if($user) {	
	if($user_profile["email"]) {
		$user = $user_profile["email"];
		
		if($user && $tipo == 1) { //FAZ LOGIN
			$query_rsCliente = "SELECT * FROM clientes WHERE email='$user'";	
			$rsCliente = DB::getInstance()->prepare($query_rsCliente);
			$rsCliente->execute();
			$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsCliente = $rsCliente->rowCount();
			DB::close();

			$password_salt_db = $row_rsCliente['password_salt'];
			$password_final = hash('sha256', $password_salt_db . $row_rsCliente['password']); 

			$response = $class_login->login($user, $password_salt_db);					
		}else if($user && $tipo == 2) { //FAZ REGISTO
			$email = $user_profile["email"];

			if($user_profile["email"]){
				$primeiro=$user_profile['primeiro'];
				$ultimo=$user_profile['ultimo'];
				$password = $class_login->geraSenha(8, true, true);
				
				$response = $class_login->registo($primeiro, $ultimo, $email, $password);							
			}
		}
	}
	?>
	<script type="text/javascript">
		<?php if($response == 1) { ?>
			parent.opener.location = 'area-reservada-dados.php?l=1';
		<?php } else if($response == 2) { ?>
			parent.opener.location = 'area-reservada-dados.php?erro=1';
		<?php } else if($response == 3) { ?>
			parent.opener.location = 'area-reservada.php';
		<?php } else if($response > 0) { ?>
			parent.opener.erroLoginFB('<?php echo $erro; ?>');
		<?php } ?>
		window.close();
	</script>
	<?php 
} else {
	header("Location: ".$url);	
}
?>