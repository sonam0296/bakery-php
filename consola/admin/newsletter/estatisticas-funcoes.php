<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php include_once('../inc_pages.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

set_time_limit(0);

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {
  $dates = array();
  $current = strtotime($first);
  $last = strtotime($last);

  while( $current <= $last ) {
    $dates[] = date($output_format, $current);
    $current = strtotime($step, $current);
  }

  return $dates;
}

function getBetween($var1="",$var2="",$pool) {
  $temp1 = strpos($pool,$var1)+strlen($var1);
  $result = substr($pool,$temp1,strlen($pool));
  $dd=strpos($result,$var2);
  
  if($dd == 0) {
    $dd = strlen($result);
  }

  return substr($result,0,$dd);
}

function lista_emails_devolvidos($id_news, $id_agendamento) {
  $query_rsSelect = "SELECT erro, email FROM news_emails_devolvidos WHERE newsletter_id = :newsletter_id AND newsletter_id_historico = :newsletter_id_historico";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->bindParam(':newsletter_id', $id_news, PDO::PARAM_INT);
  $rsSelect->bindParam(':newsletter_id_historico', $id_agendamento, PDO::PARAM_INT);
  $rsSelect->execute();
  $totalRows_rsSelect = $rsSelect->rowCount();

  $lista = array();

  if($totalRows_rsSelect > 0) {
    while($row_rsSelect = $rsSelect->fetch()) {
      $erro = $row_rsSelect['erro'];
      $email = $row_rsSelect['email'];

      if($erro == 1) 
        $motivo = 'Email não existe';
      else if($erro == 2)
        $motivo = 'Caixa de entrada cheia';
      else
        $motivo = 'Não disponível';

      $lista[$email] = $motivo;
    }
  }

  return $lista;
}

function lista_emails_devolvidos_geral($lista_ids) {
  $query_rsSelect = "SELECT erro, email, newsletter_id, newsletter_id_historico FROM news_emails_devolvidos WHERE newsletter_id_historico IN (".$lista_ids.")";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->execute();
  $totalRows_rsSelect = $rsSelect->rowCount();

  $lista = array();

  if($totalRows_rsSelect > 0) {
    while($row_rsSelect = $rsSelect->fetch()) {
      $erro = $row_rsSelect['erro'];
      $email = $row_rsSelect['email'];
      $newsletter_id = $row_rsSelect['newsletter_id'];
      $newsletter_id_historico = $row_rsSelect['newsletter_id_historico'];

      $query_rsNewsletter = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
      $rsNewsletter = DB::getInstance()->prepare($query_rsNewsletter);
      $rsNewsletter->execute();
      $row_rsNewsletter = $rsNewsletter->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsNewsletter = $rsNewsletter->rowCount();

      $newsletter = '---';
      if($totalRows_rsNewsletter > 0)
        $newsletter = $row_rsNewsletter['titulo'];

      $query_rsAgendamento = "SELECT data, hora FROM newsletters_historico WHERE id = '$newsletter_id_historico'";
      $rsAgendamento = DB::getInstance()->prepare($query_rsAgendamento);
      $rsAgendamento->execute();
      $row_rsAgendamento = $rsAgendamento->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsAgendamento = $rsAgendamento->rowCount();

      $agendamento = '---';
      if($totalRows_rsAgendamento > 0)
        $agendamento = $row_rsAgendamento['data']." ".$row_rsAgendamento['hora'];

      if($erro == 1) 
        $motivo = 'Email não existe';
      else if($erro == 2)
        $motivo = 'Caixa de entrada cheia';
      else
        $motivo = 'Não disponível';

      $res = $motivo.";".$newsletter.";".$agendamento;
      $lista[$email] = $res;
    }
  }

  return $lista;
}

function lista_emails_recebidos_geral($lista_ids) {
  $query_rsSelect = "SELECT id, email_id, newsletter_id, newsletter_id_historico FROM newsletters_vistos WHERE newsletter_id_historico IN (".$lista_ids.")";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->execute();
  $totalRows_rsSelect = $rsSelect->rowCount();

  $lista = array();

  if($totalRows_rsSelect > 0) {
    while($row_rsSelect = $rsSelect->fetch()) {
      $email = $row_rsSelect['email_id'];
      $newsletter_id = $row_rsSelect['newsletter_id'];
      $newsletter_id_historico = $row_rsSelect['newsletter_id_historico'];

      $query_rsEmail = "SELECT email, empresa, nome FROM news_emails WHERE id = '$email'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsEmail = $rsEmail->rowCount();

      if($totalRows_rsEmail > 0) {
        $query_rsNewsletter = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
        $rsNewsletter = DB::getInstance()->prepare($query_rsNewsletter);
        $rsNewsletter->execute();
        $row_rsNewsletter = $rsNewsletter->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsNewsletter = $rsNewsletter->rowCount();

        $newsletter = '---';
        if($totalRows_rsNewsletter > 0)
          $newsletter = $row_rsNewsletter['titulo'];

        $query_rsAgendamento = "SELECT data, hora FROM newsletters_historico WHERE id = '$newsletter_id_historico'";
        $rsAgendamento = DB::getInstance()->prepare($query_rsAgendamento);
        $rsAgendamento->execute();
        $row_rsAgendamento = $rsAgendamento->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsAgendamento = $rsAgendamento->rowCount();

        $agendamento = '---';
        if($totalRows_rsAgendamento > 0)
          $agendamento = $row_rsAgendamento['data']." ".$row_rsAgendamento['hora'];

        $res = $row_rsEmail['empresa']." - ".$row_rsEmail['nome'].";".$row_rsEmail['email'].";".$newsletter.";".$agendamento;
        $lista[$row_rsSelect['id']] = $res;
      }
    }
  }

  return $lista;
}

function lista_emails_recebidos($id_news, $id_agendamento) {
  $query_rsSelect = "SELECT email_id FROM newsletters_vistos WHERE newsletter_id = :newsletter_id AND newsletter_id_historico = :newsletter_id_historico";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->bindParam(':newsletter_id', $id_news, PDO::PARAM_INT);
  $rsSelect->bindParam(':newsletter_id_historico', $id_agendamento, PDO::PARAM_INT);
  $rsSelect->execute();
  $totalRows_rsSelect = $rsSelect->rowCount();

  $lista = array();

  if($totalRows_rsSelect > 0) {
    while($row_rsSelect = $rsSelect->fetch()) {
      $query_rsEmail = "SELECT email, empresa, nome FROM news_emails WHERE id = '".$row_rsSelect['email_id']."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsEmail = $rsEmail->rowCount();

      if($totalRows_rsEmail > 0) {
      	$lista[$row_rsEmail['email']] = $row_rsEmail['empresa']." - ".$row_rsEmail['nome'];
      }
    }
  }

  return $lista;
}

function lista_emails_vistos_geral($lista_ids) {
  $query_rsSelect = "SELECT id, email_id, newsletter_id,newsletter_id_historico, vistos FROM newsletters_vistos WHERE newsletter_id_historico IN (".$lista_ids.") AND visto = 1";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->execute();
  $totalRows_rsSelect = $rsSelect->rowCount();

  $lista = array();

  if($totalRows_rsSelect > 0) {
    while($row_rsSelect = $rsSelect->fetch()) {
      $email = $row_rsSelect['email_id'];
      $newsletter_id = $row_rsSelect['newsletter_id'];
      $newsletter_id_historico = $row_rsSelect['newsletter_id_historico'];

      $query_rsEmail = "SELECT email, empresa, nome FROM news_emails WHERE id = '$email'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsEmail = $rsEmail->rowCount();

      if($totalRows_rsEmail > 0) {
        $query_rsNewsletter = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
        $rsNewsletter = DB::getInstance()->prepare($query_rsNewsletter);
        $rsNewsletter->execute();
        $row_rsNewsletter = $rsNewsletter->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsNewsletter = $rsNewsletter->rowCount();

        $newsletter = '---';
        if($totalRows_rsNewsletter > 0)
          $newsletter = $row_rsNewsletter['titulo'];

        $query_rsAgendamento = "SELECT data, hora FROM newsletters_historico WHERE id = '$newsletter_id_historico'";
        $rsAgendamento = DB::getInstance()->prepare($query_rsAgendamento);
        $rsAgendamento->execute();
        $row_rsAgendamento = $rsAgendamento->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsAgendamento = $rsAgendamento->rowCount();

        $agendamento = '---';
        if($totalRows_rsAgendamento > 0)
          $agendamento = $row_rsAgendamento['data']." ".$row_rsAgendamento['hora'];

        $res = $row_rsEmail['empresa']." - ".$row_rsEmail['nome'].";".$row_rsEmail['email'].";".$row_rsSelect['vistos'].";".$newsletter.";".$agendamento;
        $lista[$row_rsSelect['id']] = $res;
      }
    }
  }

  return $lista;
}

function lista_emails_vistos($id_news, $id_agendamento) {
	$query_rsSelect = "SELECT email_id, vistos FROM newsletters_vistos WHERE newsletter_id = :newsletter_id AND newsletter_id_historico = :newsletter_id_historico AND visto = 1";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->bindParam(':newsletter_id', $id_news, PDO::PARAM_INT);
  $rsSelect->bindParam(':newsletter_id_historico', $id_agendamento, PDO::PARAM_INT);
  $rsSelect->execute();
  $totalRows_rsSelect = $rsSelect->rowCount();

  $lista = array();

  if($totalRows_rsSelect > 0) {
    while($row_rsSelect = $rsSelect->fetch()) {
      $query_rsEmail = "SELECT email, empresa, nome FROM news_emails WHERE id = '".$row_rsSelect['email_id']."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsEmail = $rsEmail->rowCount();

      if($totalRows_rsEmail > 0) {
      	$lista[$row_rsEmail['email']] = $row_rsEmail['empresa']." - ".$row_rsEmail['nome'].";".$row_rsSelect['vistos'];
      }
    }
  }

  return $lista;
}

?>