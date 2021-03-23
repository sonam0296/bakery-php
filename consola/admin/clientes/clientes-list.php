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
		
		if($opcao == 0 || $opcao == 1) { // torna clientes selecionados activos ou inactivos
			$query_rsUpd = "UPDATE clientes SET ativo = '$opcao' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->prepare($query_rsUpd);
			$rsUpd->execute();
		} 
		else if($opcao == '-1') { // elimina clientes selecionados
			$query_rsDel = "DELETE FROM clientes_moradas WHERE id_cliente IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();

			$query_rsDel = "DELETE FROM clientes_obs WHERE id_cliente IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();

			if(CARRINHO_SALDO == 1) {
				$query_rsDel = "DELETE FROM clientes_saldo WHERE cliente_id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();
			}

			if(CARRINHO_PONTOS == 1) {
				$query_rsDel = "DELETE FROM clientes_pontos WHERE cliente_id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();
			}

			$query_rsDel = "DELETE FROM clientes WHERE id IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'id', 'nome', 'data_registo', 'pais', 'roll', 'validado', 'ativo', '');
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
		$pesq_validado = $_REQUEST['form_validado'];
		$pesq_activo = $_REQUEST['form_activo'];
		$pesq_tipo = $_REQUEST['form_tipo'];
		$pesq_pais = $_REQUEST['form_pais'];
		
		if($pesq_id != "") $where_pesq .= " AND id = '$pesq_id'";
		if($pesq_nome != "") $where_pesq .= " AND (email LIKE '%$pesq_nome%' OR nome LIKE '%$pesq_nome%')";
		if($pesq_validado != "") $where_pesq .= " AND validado = '$pesq_validado'";
		if($pesq_activo != "") $where_pesq .= " AND ativo = '$pesq_activo'";
		if($data_registo != "") $where_pesq .= " AND data_registo LIKE '$data_registo%'";
		if($pesq_tipo != "") $where_pesq .= " AND roll = '$pesq_tipo'";
		if($pesq_pais != "") $where_pesq .= " AND pais = '$pesq_pais'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT id, nome, data_registo, validado, ativo, novo, roll, pais FROM clientes WHERE id > 0".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT id, nome, data_registo, validado, ativo, novo, roll, pais FROM clientes WHERE id > 0".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $nome = utf8_encode($row_rsTotal['nome']);
    $data = $row_rsTotal['data_registo'];
	  
	  if($row_rsTotal['validado'] == 1) {
		  $validado = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta = "success";
	  } 
	  else {
		  $validado = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta = "danger";
	  }

	  if($row_rsTotal['ativo'] == 1) {
		  $activo = $RecursosCons->RecursosCons['opt_ativo'];
		  $etiqueta2 = "success";
	  } 
	  else {
		  $activo = $RecursosCons->RecursosCons['opt_inativo'];
		  $etiqueta2 = "danger";
	  }
	  
	  if($row_rsTotal['novo'] == 1) {
	  	$nova = '<span style="margin-left: 20px" class="label label-sm label-warning">New</span>';
	  }
	  else {
	  	$nova = '';
	  }

	  $tipo = $row_rsTotal['roll'];
	  if($row_rsTotal['roll'] == 1) {
	  	$tipo = utf8_encode($RecursosCons->RecursosCons['tipo1_label']);
	  }
	  else if($row_rsTotal['roll'] == 2) {
	  	$tipo = utf8_encode($RecursosCons->RecursosCons['tipo2_label']);
	  }

	  $query_rsPais = "SELECT id, nome FROM paises WHERE id = :id";
	  $rsPais = DB::getInstance()->prepare($query_rsPais);
	  $rsPais->bindParam(':id', $row_rsTotal['pais'], PDO::PARAM_INT);
	  $rsPais->execute();
	  $totalRows_rsPais = $rsPais->rowCount();
	  $row_rsPais = $rsPais->fetch(PDO::FETCH_ASSOC);

	  $pais = '---';
	  if($totalRows_rsPais > 0) {
	  	$pais = utf8_encode($row_rsPais['nome']);
	  }

    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $id,
	  $nome." ".$nova,
	  $data,
	  $pais,
	  $tipo,
	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($validado).'</span>',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($activo).'</span>',
	  '<a href="clientes-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>'
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>