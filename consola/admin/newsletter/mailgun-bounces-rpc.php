<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php include_once('../inc_pages.php'); ?>
<?php include_once('mailgun-bounces-funcoes.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

set_time_limit(0);

if($_POST['op'] == "processarEmailsDevolvidos") {
  $total = 0;

  $bounces = mg_bounces(0);

  if($bounces != 0) {
    $query_rsDelete = "TRUNCATE TABLE news_mailgun_bounces";
    $rsDelete = DB::getInstance()->prepare($query_rsDelete);
    $rsDelete->execute();
    DB::close();

    foreach($bounces->items as $bounce) {
      $email = $bounce->address;
      $erro = $bounce->error;
      $data = date('Y-m-d H:i:s', strtotime($bounce->created_at));

      $insertSQL = "SELECT MAX(id) FROM news_mailgun_bounces";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->execute();
      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
      DB::close();
      
      $max_id = $row_rsInsert["MAX(id)"] + 1;

      $insertSQL = "INSERT INTO news_mailgun_bounces (id, email, erro, data) VALUES (:id, :email, :erro, :data)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT); 
      $rsInsert->bindParam(':email', $email, PDO::PARAM_STR, 5);  
      $rsInsert->bindParam(':erro', $erro, PDO::PARAM_STR, 5);  
      $rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5); 
      $rsInsert->execute();
      DB::close();

      $total++;
    }
  }

  echo $total;
}

if($_POST['op'] == "desativarEmail") {
  $tipo = $_POST['tipo'];
  $id = $_POST['id'];

  if($tipo == 1) {
    $query_rsEmail = "SELECT email FROM news_mailgun_bounces WHERE id=:id";
    $rsEmail = DB::getInstance()->prepare($query_rsEmail);
    $rsEmail->bindParam(':id', $id, PDO::PARAM_INT);
    $rsEmail->execute();
    $totalRows_rsEmail = $rsEmail->rowCount();
    $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

    if($totalRows_rsEmail > 0) {
      $query_rsUpdate = "UPDATE news_emails SET visivel=0 WHERE email=:email";
      $rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
      $rsUpdate->bindParam(':email', $row_rsEmail['email'], PDO::PARAM_STR, 5);
      $rsUpdate->execute();
    }
  }
  else if($tipo == 2) {
    $query_rsEmail = "SELECT email FROM news_mailgun_bounces WHERE id=:id";
    $rsEmail = DB::getInstance()->prepare($query_rsEmail);
    $rsEmail->bindParam(':id', $id, PDO::PARAM_INT);
    $rsEmail->execute();
    $totalRows_rsEmail = $rsEmail->rowCount();
    $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

    if($totalRows_rsEmail > 0) {
      mg_bounces_delete($row_rsEmail['email']);

      $query_rsDelete = "DELETE FROM news_mailgun_bounces WHERE id=:id";
      $rsDelete = DB::getInstance()->prepare($query_rsDelete);
      $rsDelete->bindParam(':id', $id, PDO::PARAM_INT);
      $rsDelete->execute();

      $query_rsExiste = "SELECT id FROM news_emails WHERE email=:email";
      $rsExiste = DB::getInstance()->prepare($query_rsExiste);
      $rsExiste->bindParam(':email', $row_rsEmail['email'], PDO::PARAM_STR, 5);
      $rsExiste->execute();
      $totalRows_rsExiste = $rsExiste->rowCount();
      $row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);

      if($totalRows_rsExiste > 0) {
        $query_rsDelete = "DELETE FROM news_emails WHERE id=:id";
        $rsDelete = DB::getInstance()->prepare($query_rsDelete);
        $rsDelete->bindParam(':id', $row_rsExiste['id'], PDO::PARAM_INT);
        $rsDelete->execute();

        $query_rsDelete = "DELETE FROM news_emails_listas WHERE email=:email";
        $rsDelete = DB::getInstance()->prepare($query_rsDelete);
        $rsDelete->bindParam(':email', $row_rsExiste['id'], PDO::PARAM_INT);
        $rsDelete->execute();
      }
    }
  }
}

?>