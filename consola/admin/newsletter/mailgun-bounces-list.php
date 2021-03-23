<?php include_once('../inc_pages.php'); ?>
<?php include_once('mailgun-bounces-funcoes.php'); ?>
<?php
   
  // actualiza estado dos registos selecionados
  if(isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    //$records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
	
  	// actualiza estado dos registos selecionados
  	$opcao = $_REQUEST["customActionName"];
  	$array_ids = $_REQUEST["id"];
  
  	if($opcao == 1) {
      foreach($array_ids as $id) {
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
  	}
    if($opcao == 2) {
      foreach($array_ids as $id) {
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

    DB::close();
  }
  
  // ordenação
  $sOrder = " ORDER BY data DESC";
  $colunas = array('', 'data', 'email', 'erro', '');
  if(isset($_REQUEST['order'])) {
	  $sOrder = " ORDER BY ";
	  if(sizeof($_REQUEST['order']) > 1) {
		  for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
			 if($i>0) $sOrder .= ", ";
			 $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
		  }
	  } elseif($colunas[$_REQUEST['order'][0]["column"]]) $sOrder .= $colunas[$_REQUEST['order'][0]["column"]]." ".$_REQUEST['order'][0]["dir"];
  }
  
  // pesquisa
  $where_pesq = "";
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
  	$pesq_email = utf8_decode($_REQUEST['form_email']);
    $pesq_erro = utf8_decode($_REQUEST['form_erro']);
    $pesq_data = $_REQUEST['form_data'];
  	
  	if($pesq_email != "") $where_pesq .= " AND email LIKE '%$pesq_email%'";
    if($pesq_erro != "") $where_pesq .= " AND erro LIKE '%$pesq_erro%'";
    if($pesq_data != "") $where_pesq .= " AND data LIKE '$pesq_data%'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM news_mailgun_bounces WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM news_mailgun_bounces WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
    $id = $row_rsTotal['id'];
    $email = utf8_encode($row_rsTotal['email']);
    $erro = utf8_encode($row_rsTotal['erro']);
    $data = $row_rsTotal['data'];

    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
    $data,
	  $email,
    $erro,
	  '<a href="#visivelModal" data-toggle="modal" onClick="saveID('.$id.')" class="btn btn-xs default btn-editable"><i class="fa fa-eye-slash"></i> '.utf8_encode("Não Visível").'</a><br><br><a href="#removerModal" data-toggle="modal" onClick="saveID('.$id.')" class="btn btn-xs default btn-editable"><i class="fa fa-times"></i> Remover do Mailgun</a>',
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>