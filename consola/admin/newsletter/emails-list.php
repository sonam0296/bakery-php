<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */
   
  // actualiza estado dos registos selecionados
  if(isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    //$records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
	
		// actualiza estado dos registos selecionados
		$opcao = $_REQUEST["customActionName"];
		$array_ids = $_REQUEST["id"];
		$lista = "";
		foreach($array_ids as $id) {
			$lista.=$id.",";
		}
		$lista = "(".substr($lista,0,-1).")";
		
		if($opcao == '-1') { // elimina emails selecionados
			$query_rsDel = "DELETE FROM news_emails WHERE id IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();
			
			$query_rsDel = "DELETE FROM news_emails_listas WHERE email IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();	
		}
		else if($opcao == 1 || $opcao == 2) {
			if($opcao == 1) $query_rsUpd = "UPDATE news_emails SET visivel = '1' WHERE id IN $lista";
			else $query_rsUpd = "UPDATE news_emails SET visivel = '0' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->prepare($query_rsUpd);
			$rsUpd->execute();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY news_emails.data DESC";
  $colunas = array( 'news_emails.id', 'news_emails.email', '', 'news_emails.data', 'news_emails.data_remocao', 'news_emails.aceita', 'news_emails.visivel', '');
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
		$pesq_email= utf8_decode($_REQUEST['form_email']);
		$pesq_lista = utf8_decode($_REQUEST['form_lista']);
		$pesq_data = utf8_decode($_REQUEST['form_data']);
	  $pesq_data_remocao = utf8_decode($_REQUEST['form_data_remocao']);
	  $pesq_ativo = utf8_decode($_REQUEST['form_ativo']);
	  $pesq_aceita = utf8_decode($_REQUEST['form_aceita']);
		
		if($pesq_email != "") $where_pesq .= " AND news_emails.email LIKE '%$pesq_email%'";
		if($pesq_lista != "") $where_pesq .= " AND news_emails_listas.lista = '$pesq_lista'";
		if($pesq_data != "") $where_pesq .= " AND news_emails.data LIKE '$pesq_data%'";
	  if($pesq_data_remocao != "") $where_pesq .= " AND news_emails.data_remocao LIKE '$pesq_data_remocao%'";
	  if($pesq_ativo != "") $where_pesq .= " AND news_emails.visivel = '$pesq_ativo'";
	  if($pesq_aceita != "") $where_pesq .= " AND news_emails.aceita = '$pesq_aceita'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT COUNT(news_emails.id) AS total FROM news_emails LEFT JOIN news_emails_listas ON news_emails.id=news_emails_listas.email WHERE news_emails.id > '0'".$where_pesq." ".$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $row_rsTotal = $rsTotal->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsTotal = $rsTotal->rowCount();

  //$iTotalRecords = $totalRows_rsTotal;
  $iTotalRecords=$row_rsTotal['total'];
  
  $query_rsTotal = "SELECT news_emails.* FROM news_emails LEFT JOIN news_emails_listas ON news_emails.id=news_emails_listas.email WHERE news_emails.id > '0'".$where_pesq." GROUP BY news_emails.id".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
    $id = $row_rsTotal['id'];
    $email = utf8_encode($row_rsTotal['email']);
	  
	  $query_rsProc = "SELECT news_listas.* FROM news_emails_listas, news_listas WHERE news_emails_listas.email='".$row_rsTotal['id']."' AND news_emails_listas.lista=news_listas.id ORDER BY news_listas.id ASC";
	  $rsProc = DB::getInstance()->prepare($query_rsProc);
	  $rsProc->execute();
	  $totalRows_rsProc = $rsProc->rowCount();
	  
	  $lista = '';
		if($totalRows_rsProc > 0) {
			while($row_rsProc = $rsProc->fetch()) {
				$lista = $lista.utf8_encode($row_rsProc['nome']).", ";
			}

			$lista = substr($lista, 0, -2);
		} 
		else {
			$lista = "---";
		}

    $data = $row_rsTotal['data'];
    $data_remocao = $row_rsTotal['data_remocao'];
	  
	  if($row_rsTotal['aceita'] == 1) {
      $aceita = "Sim";
      $etiqueta = "success";
    } 
    else {
      $aceita = "Não";
      $etiqueta = "danger";
    }

	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta2 = "success";
	  } 
	  else {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta2 = "danger";
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $email,
	  $lista,
	  $data,
	  $data_remocao,
	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($aceita).'</span>',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="emails-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '.$RecursosCons->RecursosCons['btn_editar'].'</a>'
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>