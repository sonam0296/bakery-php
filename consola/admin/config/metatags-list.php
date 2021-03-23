<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC, id DESC";
  $colunas = array( '', 'id', 'pagina', 'ordem', '');
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
	$pesq_nome = utf8_decode($_REQUEST['form_nome']);
	
	if($pesq_id != "") $where_pesq .= " AND id = '$pesq_id'";
	if($pesq_nome != "") $where_pesq .= " AND (pagina = '$pesq_nome' OR pagina LIKE '%$pesq_nome%')";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM metatags_pt WHERE visivel = '1'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM metatags_pt WHERE visivel = '1'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
      $nome = utf8_encode($row_rsTotal['pagina']);
	  
      $records["data"][] = array(
		  '<input type="checkbox" name="id[]" value="'.$id.'">',
		  $id,
		  $nome,
		  $row_rsTotal['ordem'],
		  '<a href="metatags-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
      );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>