<?php 
require_once(ROOTPATH.'/Connections/connADMIN.php');

if(!isset($_SESSION)) {
  session_start();
}

function mg_send($news_id, $to, $from, $message, $message_txt, $subject, $reply_to, $bounce, $newsletter_id_historico="", $data_to="") {

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

  //recipient-variables
  // '{"rui.sa@netgocio.pt": {"email":"rui.sa@netgocio.pt", "codigo":"", "name":""}, "davide@netgocio.pt": {"email":"davide@netgocio.pt", "codigo":"", "name":""}}'
  $to_array = explode(",", $to);
  $recipient_var = array();
  if($data_to){
    //%recipient.VAR%
    foreach($to_array as $email_to){
      //get data for this email
      $variables = $data_to[$email_to];
      if($variables){
        foreach ($variables as $key => $value) {
          $message = str_replace("#".$key."#", "%recipient.".$key."%", $message);
          $message_txt = str_replace("#".$key."#", "%recipient.".$key."%", $message_txt);
          $subject = str_replace("#".$key."#", "%recipient.".$key."%", $subject);
        }
        $recipient_var[$email_to] = $variables;
      }else{
        $recipient_var[$email_to] = array("email"=>$email_to, "name"=>'');
      }      
    }
  }else{
    foreach($to_array as $email_to){
      $recipient_var[$email_to] = array("email"=>$email_to, "name"=>'');
    }
  }

  $recipient_var = json_encode($recipient_var);

  /*echo $recipient_var;
  echo "<br>".$message_txt;
  exit();*/

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAILGUN_API);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.DOMAIN.'/messages');
  curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => $from,
        'to' => $to,
        'recipient-variables' => $recipient_var,
        'h:Reply-To' => $reply_to,
        'h:Return-path' => $bounce,
        'h:Errors-To' => $bounce,
        'h:NewsCod' => $news_id."#",
        'h:NewsAgenCod' => $newsletter_id_historico."#",
        'h:NewsEmail' => "%recipient.email%#",
        'subject' => utf8_encode($subject),
        'html' => utf8_encode($message),
        'text' => utf8_encode($message_txt)));

  $j = json_decode(curl_exec($ch));

  $info = curl_getinfo($ch);
  /*print_r($info);
  echo "<br><br>-------------<br><br>";
  print_r($to);*/
  // print_r($j);
  curl_close($ch);

  if($info['http_code'] != 200)
    return 0;
  else 
    return 1;
}

?>