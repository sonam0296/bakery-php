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
	
	if($opcao == '-1') { // elimina utilizadores selecionados
		$query_rsDel = "DELETE FROM redirects_301 WHERE id IN $lista";
		$rsDel = DB::getInstance()->query($query_rsDel);
		$rsDel->execute();
		DB::close();
	}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'id', 'url_old', 'url_new', 'lang', '');
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
	$pesq_id = $_REQUEST['form_id'];
	$pesq_form_url_old = utf8_decode($_REQUEST['form_url_old']);
	$pesq_form_url_new = utf8_decode($_REQUEST['form_url_new']);
	$pesq_form_lang = utf8_decode($_REQUEST['form_lang']);
	
	if($pesq_id != "") $where_pesq .= " AND id = '$pesq_id'";
	if($pesq_form_url_old != "") $where_pesq .= " AND (url_old = '$pesq_form_url_old' OR url_old LIKE '%$pesq_form_url_old%')";
	if($pesq_form_url_new != "") $where_pesq .= " AND (url_new = '$pesq_form_url_new' OR url_new LIKE '%$pesq_form_url_new%')";
	if($pesq_form_lang != "") $where_pesq .= " AND (lang = '$pesq_form_lang' OR lang LIKE '%$pesq_form_lang%')";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM redirects_301 WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM redirects_301 WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->query($query_rsTotal);
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
      $url_old = utf8_encode($row_rsTotal['url_old']);
      $url_new = utf8_encode($row_rsTotal['url_new']);
	  
      $records["data"][] = array(
		  '<input type="checkbox" name="id[]" value="'.$id.'">',
		  $id,
		  $url_old,
		  $url_new,
		  $row_rsTotal['lang'],
		  '<a href="redirecionamentos_301-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
      );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>