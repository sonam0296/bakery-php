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

/* Google App Client Id */
define('CLIENT_ID', 'xxxxxxxxxxxxxxxxxxxxxxxx');
/* Google App Client Secret */
define('CLIENT_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxx');
/* Google App Redirect Url */
define('CLIENT_REDIRECT_URL', 'xxxxxxxxxxxxxxxxxxxxxxxx');

function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
	$url = 'https://accounts.google.com/o/oauth2/token';			
	
	$curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, $url);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
	curl_setopt($ch, CURLOPT_POST, 1);		
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);	
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
	if($http_code != 200) 
		throw new Exception('Error : Failed to receieve access token');
		
	return $data;
}
function GetUserProfileInfo($access_token) {	
	$url = 'https://www.googleapis.com/plus/v1/people/me';			
	
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, $url);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
	if($http_code != 200) 
		throw new Exception('Error : Failed to get user information');
		
	return $data;
}

$response = 0;
if(isset($_GET['code'])) {
	try {
		$gapi = new GoogleLoginApi();
		
		// Get the access token 
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
		
		// Get user information
		$user_profile = $gapi->GetUserProfileInfo($data['access_token']);

		echo '<pre>';print_r($user_profile); echo '</pre>';

		if(!empty($user_profile)){	
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
	}catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}
?>