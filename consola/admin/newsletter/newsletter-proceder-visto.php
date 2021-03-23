<?php require_once('../../../Connections/connADMIN.php'); ?>
<?php include_once('../../sendMail/send_mail.php'); ?>
<?php include_once('news-funcoes-logs.php'); ?>
<?php

$id_linha=$_GET['id'];
$code=$_GET['code'];

//Através do código obtido vamos obter o id do email correspondente
$query_rs = "SELECT id FROM news_emails WHERE codigo=:code";
$rs = DB::getInstance()->prepare($query_rs);
$rs->bindParam(':code', $code, PDO::PARAM_STR, 5);
$rs->execute();
$row_rs = $rs->fetch(PDO::FETCH_ASSOC);
$totalRows_rs = $rs->rowCount();
DB::close();

if($totalRows_rs > 0) {
  $email_id = $row_rs['id'];

  $data = date('Y-m-d');
  $hora=date('H:i:s');

  //Verificar se já tinha visto esta newsletter
  $query_rs = "SELECT visto FROM newsletters_vistos WHERE newsletter_id_historico=:newsletter_id_historico AND email_id=:email_id";
  $rs = DB::getInstance()->prepare($query_rs);
  $rs->bindParam(':newsletter_id_historico', $_GET['id'], PDO::PARAM_INT);
  $rs->bindParam(':email_id', $email_id, PDO::PARAM_STR, 5);
  $rs->execute();
  $row_rs = $rs->fetch(PDO::FETCH_ASSOC);
  $totalRows_rs = $rs->rowCount();
  DB::close();

  /*$data_envio=$row_rs['data_envio'];
  $data_visto=$data." ".$hora;
  $data_envio=strtotime($data_envio);
  $data_visto=strtotime($data_visto);
  
  $diff=round(abs($data_visto-$data_envio) / 60,2);*/
  
  $ip=$_SERVER['REMOTE_ADDR'];

  if($ip=="") {
    $ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
  }
  
  //94.126.169.55 - FILTRO SPAM 
  
  //Verifica se passou 1 min depois que o email foi enviado.. por causa do filtro de Spam!!
  //if($totalRows_rs > 0 && $diff>=1) {
  if($totalRows_rs > 0 && $ip!='94.126.169.55') {
    //Se visto = 1, não conta para as visualizações únicas
    if($row_rs['visto'] != 1) {
      $data_visto = $data." ".$hora;

      //Vamos registar na tabela que um certo utilizador abriu uma newsletter
      $query_rs = "UPDATE newsletters_vistos SET visto=1, data_visto=:data_visto WHERE newsletter_id_historico=:newsletter_id_historico AND email_id=:email_id";
      $rs = DB::getInstance()->prepare($query_rs);
      $rs->bindParam(':newsletter_id_historico', $_GET['id'], PDO::PARAM_INT);  
      $rs->bindParam(':data_visto', $data_visto, PDO::PARAM_STR, 5);
      $rs->bindParam(':email_id', $email_id, PDO::PARAM_STR, 5);
      $rs->execute();
      DB::close();

      //Registar mais uma visualização única
      $insertSQL = "UPDATE newsletters_historico SET emails_vistos_unicos=emails_vistos_unicos+1 WHERE id='$_GET[id]'";
      $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
      $rsInsertSQL->execute();
      DB::close();
    }

    //Registar mais uma visualização para este utilizador
    $insertSQL = "UPDATE newsletters_vistos SET vistos=vistos+1 WHERE newsletter_id_historico=:newsletter_id_historico AND email_id='$email_id'";
    $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
    $rsInsertSQL->bindParam(':newsletter_id_historico', $_GET['id'], PDO::PARAM_INT);  
    $rsInsertSQL->execute();
    DB::close();

    //Registar mais uma visualização para a newsletter
    $insertSQL = "UPDATE newsletters_historico SET emails_vistos=emails_vistos+1 WHERE id='$id_linha'";
    $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
    $rsInsertSQL->execute();
    DB::close();
  }
}

header("Content-type: img/gif");

$im=imagecreatefromgif('../../imgs/fill.gif');

imagegif($im);
imagedestroy($im);

?>