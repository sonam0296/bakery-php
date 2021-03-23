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
  		// $query_rsUpd = "UPDATE clientes_obs SET estado = :opcao WHERE id IN $lista OR id_pai IN $lista";
  		// $rsUpd = DB::getInstance()->prepare($query_rsUpd);
      // $rsUpd->bindParam(':opcao', $opcao, PDO::PARAM_INT);
  		// $rsUpd->execute();
  	} 
    else if($opcao == '-1') { // elimina clientes_obs selecionados
  		$query_rsDel = "DELETE FROM clientes_obs WHERE id IN $lista";
  		$rsDel = DB::getInstance()->prepare($query_rsDel);
  		$rsDel->execute();
  	}
  }
  
  $id_cliente = $_GET["id"];
  
  // ordenação
  $sOrder = " ORDER BY data DESC";
  $colunas = array( '', 'data', 'id_encomenda', 'descricao', '');
  	
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
	$query_rsTotal = "(SELECT id, @id_encomenda := 0 as id_encomenda, data, descricao FROM clientes_obs WHERE id_cliente =:id_cliente) UNION (SELECT id, id_encomenda, data, descricao FROM encomendas_obs WHERE id_cliente =:id_cliente)".$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "(SELECT id, @id_encomenda := 0 as id_encomenda, data, descricao FROM clientes_obs WHERE id_cliente =:id_cliente) UNION (SELECT id, id_encomenda, data, descricao FROM encomendas_obs WHERE id_cliente =:id_cliente)".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
  	$id = $row_rsTotal['id'];

    $encomenda = $row_rsTotal['id_encomenda'];
    $link = '<a href="observacoes-edit.php?id='.$id_cliente.'&enc='.$encomenda.'&obs='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i>  '. $RecursosCons->RecursosCons['btn_editar'].'</a>';
    $input = '<input type="checkbox" name="id[]" value="'.$id.'" disabled>';

    if($encomenda == 0) {   
      $encomenda = '-';
      $link = '<a href="observacoes-edit.php?id='.$id_cliente.'&obs='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>';
      $input = '<input type="checkbox" name="id[]" value="'.$id.'">';
    }

    $descricao = utf8_encode($row_rsTotal['descricao']);
    $data = $row_rsTotal['data'];
  
    $records["data"][] = array(
	  $input,
	  $data,
    $encomenda,
	  $descricao,
	  // '<span class="label label-sm label-'.$etiqueta.'">'.$activo.'</span>',
	  // $mensagens_total,
	  $link,
    );
	  
	  $i++;
	  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>
