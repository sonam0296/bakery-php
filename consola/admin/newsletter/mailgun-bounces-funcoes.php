<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php include_once('../inc_pages.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

set_time_limit(0);

function getBetween($var1="",$var2="",$pool) {
  $temp1 = strpos($pool,$var1)+strlen($var1);
  $result = substr($pool,$temp1,strlen($pool));
  $dd=strpos($result,$var2);
  
  if($dd == 0) {
    $dd = strlen($result);
  }

  return substr($result,0,$dd);
}

function mg_bounces($news_id) {
  if($news_id > 0) { // se vier com id da newsletter carrega dados mailgun da mesma
    $query_rsP = "SELECT mailgun_key, mailgun_dominio FROM newsletters WHERE id = '$news_id'";
    $rsP = DB::getInstance()->prepare($query_rsP);
    $rsP->execute();
    $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsP = $rsP->rowCount();

    define("MAILGUN_API", $row_rsP["mailgun_key"]);
    define("DOMAIN", $row_rsP["mailgun_dominio"]);
  }
  else { // senão carrega dados mailgun da configuração geral
    $query_rsConfig = "SELECT mailgun_key, mailgun_dominio FROM newsletters_config WHERE id = '1'";
    $rsConfig = DB::getInstance()->prepare($query_rsConfig);
    $rsConfig->execute();
    $row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsConfig = $rsConfig->rowCount();

    define("MAILGUN_API", $row_rsConfig["mailgun_key"]);
    define("DOMAIN", $row_rsConfig["mailgun_dominio"]);
  }

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAILGUN_API);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.DOMAIN.'/bounces?limit=100000&skip=0');

  $j = json_decode(curl_exec($ch));

  $info = curl_getinfo($ch);

  curl_close($ch);

  if($info['http_code'] != 200) {
    return 0;
  }
  else {
    return $j;
  }
}

function mg_bounces_delete($email) {
  $query_rsConfig = "SELECT mailgun_key, mailgun_dominio FROM newsletters_config WHERE id = '1'";
  $rsConfig = DB::getInstance()->prepare($query_rsConfig);
  $rsConfig->execute();
  $row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsConfig = $rsConfig->rowCount();

  define("MAILGUN_API", $row_rsConfig["mailgun_key"]);
  define("DOMAIN", $row_rsConfig["mailgun_dominio"]);

  $email = trim($email);
  $email_is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);

  if($email && $email_is_valid) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAILGUN_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.DOMAIN.'/bounces/'.$email);

    $j = json_decode(curl_exec($ch));

    $info = curl_getinfo($ch);

    curl_close($ch);

    if($info['http_code'] != 200) {
      return 0;
    }
    else {
      return $j;
    }
  }
}

?>