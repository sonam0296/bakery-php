<?php include_once('../../../Connections/connADMIN.php'); ?>
<?php
  /* 
   * Paging
   */
  
  // ordenação
  $sOrder = " ORDER BY data_enviado DESC";
  $colunas = array('nome', 'email', 'data_enviado', 'aberto', 'data_aberto', '');
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
		$pesq_nome = utf8_decode($_REQUEST['form_nome']);
		$pesq_email = utf8_decode($_REQUEST['form_email']);
		$pesq_aberto = $_REQUEST['form_aberto'];
		$pesq_data_enviado = $_REQUEST['form_data_enviado'];
		$pesq_data_aberto = $_REQUEST['form_data_aberto'];
		
		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
		if($pesq_email != "") $where_pesq .= " AND email LIKE '%$pesq_email%'";
		if($pesq_aberto != "") $where_pesq .= " AND aberto = '$pesq_aberto'";
		if($pesq_data_enviado != "") $where_pesq .= " AND data_enviado LIKE '$pesq_data_enviado%'";
		if($pesq_data_aberto != "") $where_pesq .= " AND data_aberto LIKE '$pesq_data_aberto%'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM carrinho_cliente_hist WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM carrinho_cliente_hist WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $nome = utf8_encode($row_rsTotal['nome']);
    $email = utf8_encode($row_rsTotal['email']);
    $data_enviado = utf8_encode($row_rsTotal['data_enviado']);

    $data_aberto = "---";
    if($row_rsTotal['data_aberto']) {
    	$data_aberto = utf8_encode($row_rsTotal['data_aberto']);
    }
	  
	  if($row_rsTotal['aberto'] == 1) {
		  $aberto = "Sim";
		  $etiqueta = "success";
	  }
	  else {
		  $aberto = "Não";
		  $etiqueta = "danger";
	  }
	  
	  $botao = '---';
	  if($row_rsTotal['id_cliente'] > 0) {
		  $query_rsCliente = "SELECT id FROM clientes WHERE id = '".$row_rsTotal['id_cliente']."'";
		  $rsCliente = DB::getInstance()->prepare($query_rsCliente);
		  $rsCliente->execute();
		  $totalRows_rsCliente = $rsCliente->rowCount();
		  DB::close();

		  if($totalRows_rsCliente > 0) {
		  	$botao = '<a href="../clientes/clientes-edit.php?id='.$row_rsTotal['id_cliente'].'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Ver Cliente</a>';
		  }
		}

    $records["data"][] = array(
	  $nome,
	  $email,
	  $data_enviado,
	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($aberto).'</span>',
	  $data_aberto,
	  $botao,
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>