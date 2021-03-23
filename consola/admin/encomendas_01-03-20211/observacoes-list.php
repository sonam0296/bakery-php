<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */

  //var_dump($_POST["selected"]);

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
		
		if($opcao == 0 || $opcao == 1) { // torna tickets selecionados activos ou inactivos
			$query_rsUpd = "UPDATE encomendas_obs SET estado =:estado WHERE id IN $lista OR id_pai IN $lista";
			$rsUpd = DB::getInstance()->prepare($query_rsUpd);
			$rsUpd->bindParam(':estado', $opcao, PDO::PARAM_INT);
			$rsUpd->execute();

		} else { // elimina encomendas_obs selecionados
			$query_rsDel = "DELETE FROM encomendas_obs WHERE id IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();
		}
  }
  
  $enc = $_GET["enc"];
  
  // ordenação
  $sOrder = " ORDER BY data DESC";
  $colunas = array( '', 'descricao', 'data', '');
  if(isset($_REQUEST['order'])) {
	  $sOrder = " ORDER BY ";
	  for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
	 	 if($i>0) $sOrder .= ", ";
		 $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
	  }
  }
  
  // pesquisa
 //  $where_pesq = "";
 //  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
	// $pesq_id = $_REQUEST['form_id'];
	// $pesq_nome = utf8_decode($_REQUEST['form_nome']);
	// $data_registo = $_REQUEST['form_data'];
	// $pesq_activo = $_REQUEST['form_activo'];
	
	// if($pesq_id != "") $where_pesq .= " AND (encomendas_obs.id = '$pesq_id' OR encomendas_obs.id_pai ='$pesq_id')";
	// if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";
	// if($pesq_activo != "") $where_pesq .= " AND estado = '$pesq_activo'";
	// if($data_registo != "") $where_pesq .= " AND data like '$data_registo%'";
 //  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT id, descricao, data FROM encomendas_obs WHERE id_encomenda =:id_encomenda".$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT id, descricao, data FROM encomendas_obs WHERE id_encomenda =:id_encomenda".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
  
    $id = $row_rsTotal['id'];
  
    $descricao = utf8_encode($row_rsTotal['descricao']);
    $data = $row_rsTotal['data'];
  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $data,
	  $descricao,
	  // '<span class="label label-sm label-'.$etiqueta.'">'.$activo.'</span>',
	  // $mensagens_total,
	  '<a href="observacoes-edit.php?id='.$id.'&enc='.$enc.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '.$RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>