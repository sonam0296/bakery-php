<?php include_once('../../../Connections/connADMIN.php'); ?>
<?php 

  // ordenação
  $sOrder = " ORDER BY c.data_pedido DESC";
  $colunas = array( '', 'cm.email', 'cm.data_pedido', 'cm.data_remocao', '');
  if(isset($_REQUEST['order'])) {
	  $sOrder = " ORDER BY ";
	  for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
	 	 if($i>0) $sOrder .= ", ";
		 $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
	  }
  }
  
  // pesquisa
  $where_pesq = "";
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
		$pesq_email = utf8_decode($_REQUEST['form_email']);
		$data_pedido = $_REQUEST['form_data'];
    $data_remocao = $_REQUEST['form_data_remocao'];
		
		if($pesq_email != "") $where_pesq .= " AND cm.email LIKE '%$pesq_email%'";
		if($data_pedido != "") $where_pesq .= " AND cm.data_pedido LIKE '$data_pedido%'";
    if($data_remocao != "") $where_pesq .= " AND cm.data_remocao LIKE '$data_remocao%'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT cm.* FROM clientes_remocao cm LEFT JOIN clientes c ON c.id = cm.id_cliente WHERE cm.id>0".$where_pesq." GROUP BY cm.id ".$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT cm.* FROM clientes_remocao cm LEFT JOIN clientes c ON c.id = cm.id_cliente WHERE cm.id>0".$where_pesq." GROUP BY cm.id ".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $email = utf8_encode($row_rsTotal['email']);
    $data = $row_rsTotal['data_pedido'];
    $data_remocao = $row_rsTotal['data_remocao'];

    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $email,
	  $data,
    $data_remocao,
	  '<a href="clientes_remocao-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Ver</a>'
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>