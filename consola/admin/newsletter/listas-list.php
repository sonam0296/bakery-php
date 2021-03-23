<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */
   
   // actualiza estado dos registos selecionados
   if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
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
	
	if($opcao == '-1') { // elimina listas selecionadas
		
		$query_rsDel = "DELETE FROM news_listas WHERE id IN $lista";
		$rsDel = DB::getInstance()->query($query_rsDel);
		$rsDel->execute();
		
		$query_rsDel = "DELETE FROM news_emails_listas WHERE lista IN $lista";
		$rsDel = DB::getInstance()->query($query_rsDel);
		$rsDel->execute();
		
	}
	
  }
  
  // ordenação
  $sOrder = " ORDER BY news_listas.ordem ASC, news_listas.nome ASC";
  $colunas = array( '', 'news_listas.nome', '', 'news_listas.ordem', '');
  if(isset($_REQUEST['order'])) {
	  $sOrder = " ORDER BY ";
	  $i=0;
	  
	  for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
	 	 if($i>0) $sOrder .= ", ";
		 $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
	  }
  }
  
  // pesquisa
  $where_pesq = "";
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
	$pesq_form= utf8_decode($_REQUEST['form_nome']);
	
	if($pesq_form != "") $where_pesq .= " AND (news_listas.nome = '$pesq_form' OR news_listas.nome LIKE '%$pesq_form%')";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT news_listas.*, COUNT(news_emails_listas.email) AS total FROM news_listas LEFT JOIN news_emails_listas ON news_listas.id=news_emails_listas.lista WHERE news_listas.id > '0'".$where_pesq." GROUP BY news_listas.id".$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT news_listas.*, COUNT(news_emails_listas.email) AS total FROM news_listas LEFT JOIN news_emails_listas ON news_listas.id=news_emails_listas.lista WHERE news_listas.id > '0'".$where_pesq." GROUP BY news_listas.id".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
    $id = $row_rsTotal['id'];
    $nome = utf8_encode($row_rsTotal['nome']);
	  
	  $editar = "";
    $input = "";
	  if($id>2) {
      $editar = '<a href="listas-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>';
      $input = '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">';
    }
    
    $records["data"][] = array(
	  $input,
	  $nome,
	  $row_rsTotal['total'],
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  $editar,
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>