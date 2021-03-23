<?php include_once('../inc_pages.php'); ?>
<?php 
  /* 
   * Paging
   */

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
		
		if($opcao == 0 || $opcao == 1) { // torna tickets selecionados activos ou inactivos
			$query_rsUpd = "UPDATE tickets SET estado = '$opcao' WHERE id IN $lista OR id_pai IN $lista";
			$rsUpd = DB::getInstance()->prepare($query_rsUpd);
			$rsUpd->execute();
		} 
		else if($opcao == '-1') { // elimina tickets selecionados
			$query_rsDel = "DELETE FROM tickets WHERE id IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();
			
			$query_rsDel = "DELETE FROM tickets WHERE id_pai IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY tickets.id DESC";
  $colunas = array( '', 'tickets.id', 'clientes.nome', 'tickets.data', 'tickets.tipo', 'tickets.estado', '', '');
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
		$pesq_id = $_REQUEST['form_id'];
		$pesq_nome = utf8_decode($_REQUEST['form_nome']);
		$data_registo = $_REQUEST['form_data'];
		$pesq_activo = $_REQUEST['form_activo'];
		$pesq_tipo = $_REQUEST['form_tipo'];
		
		if($pesq_id != "") $where_pesq .= " AND (tickets.id = '$pesq_id' OR tickets.id_pai ='$pesq_id')";
		if($pesq_nome != "") $where_pesq .= " AND (clientes.email LIKE '%$pesq_nome%' OR clientes.nome LIKE '%$pesq_nome%')";
		if($pesq_activo != "") $where_pesq .= " AND tickets.estado = '$pesq_activo'";
		if($data_registo != "") $where_pesq .= " AND tickets.data LIKE '$data_registo%'";
		if($pesq_tipo != "") $where_pesq .= " AND tickets.tipo = '$pesq_tipo'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  
  $query_rsTotal = "SELECT tickets.*, clientes.nome FROM tickets, clientes WHERE clientes.id = tickets.id_cliente AND tickets.id_pai = '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();

  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT tickets.*, clientes.nome FROM tickets, clientes WHERE clientes.id = tickets.id_cliente AND tickets.id_pai = '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
    $id = $row_rsTotal['id'];
	  
  	// verificar se tem respostas
  	$query_rsResp = "SELECT COUNT(id) as total FROM tickets WHERE id_pai = :id";
		$rsResp = DB::getInstance()->prepare($query_rsResp);
		$rsResp->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsResp->execute();
		$row_rsResp = $rsResp->fetch(PDO::FETCH_ASSOC);
	
		// respostas novas
	  $query_rsRespNovas = "SELECT COUNT(id) as total FROM tickets WHERE id_pai = :id AND visto = '0'";
		$rsRespNovas = DB::getInstance()->prepare($query_rsRespNovas);
		$rsRespNovas->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsRespNovas->execute();
		$row_rsRespNovas = $rsRespNovas->fetch(PDO::FETCH_ASSOC);
	
		// último ticket	
  	$query_rsUltimo = "SELECT estado FROM tickets WHERE id_pai = :id ORDER BY data DESC, id DESC LIMIT 1";
		$rsUltimo = DB::getInstance()->prepare($query_rsUltimo);
		$rsUltimo->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsUltimo->execute();
		$row_rsUltimo = $rsUltimo->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsUltimo = $rsUltimo->rowCount();
	
		if($totalRows_rsUltimo == 0) {
			$query_rsUltimo = "SELECT estado FROM tickets WHERE id = :id";
			$rsUltimo = DB::getInstance()->prepare($query_rsUltimo);
			$rsUltimo->bindParam(':id', $id, PDO::PARAM_INT);	
			$rsUltimo->execute();
			$row_rsUltimo = $rsUltimo->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsUltimo = $rsUltimo->rowCount();
		}
		
		$mensagens_total = $row_rsResp['total'] + 1;
		
		if($row_rsRespNovas['total'] > 0) $mensagens_total.=" (".$row_rsRespNovas['total'].")";
		
		$estado = $row_rsUltimo['estado'];
		
		if($estado == 1) {
			$activo = $RecursosCons->RecursosCons['opt_aberto'];;
	  	$etiqueta = "danger";
		}
		else {
			$activo = $RecursosCons->RecursosCons['opt_fechado'];
			$etiqueta = "success";
		}
	  
	  if($row_rsTotal['visto'] == 0) {
	  	$nova = '<span style="margin-left: 20px" class="label label-sm label-warning">Novo</span>';
	  }
	  else {
	  	$nova = '';
	  }
	  
	  $query_rsTipo = "SELECT nome FROM tickets_tipos_pt WHERE id = '".$row_rsTotal['tipo']."'";
	  $rsTipo = DB::getInstance()->prepare($query_rsTipo);
	  $rsTipo->execute();
	 	$totalRows_rsTipo = $rsTipo->rowCount();
	 	$row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);

	 	$tipo = '---';
	 	if($totalRows_rsTipo > 0) {
	 		$tipo = utf8_encode($row_rsTipo['nome']);
	 	}

    $nome = utf8_encode($row_rsTotal['nome']);
    $data = $row_rsTotal['data'];
  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $id,
	  $nome." ".$nova,
	  $data,
	  $tipo,
	  '<span class="label label-sm label-'.$etiqueta.'">'.$activo.'</span>',
	  $mensagens_total,
	  '<a href="tickets-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>