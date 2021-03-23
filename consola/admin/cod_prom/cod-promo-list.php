<?php include_once('../inc_pages.php'); ?>
<?php 
  /* 
   * Paging
   */

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
		
		if($opcao == 0 || $opcao == 1) { // torna clientes selecionados activos ou inactivos
			$query_rsUpd = "UPDATE codigos_promocionais SET visivel = '$opcao' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->query($query_rsUpd);
			$rsUpd->execute();
			DB::close();
		} 
		if($opcao == 2 || $opcao == 3) { // torna clientes selecionados activos ou inactivos
			if($opcao == 2) $query_rsUpd = "UPDATE codigos_promocionais SET visivel_listagem = '1' WHERE id IN $lista";
			else $query_rsUpd = "UPDATE codigos_promocionais SET visivel_listagem = '0' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->query($query_rsUpd);
			$rsUpd->execute();
			DB::close();
		} 
		else if($opcao == '-1') { // elimina clientes selecionados
			$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
	    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	    $rsLinguas->execute();
	    $totalRows_rsLinguas = $rsLinguas->rowCount();
	    
	    while($row_rsLinguas = $rsLinguas->fetch()) {
	      $query_rsP = "DELETE FROM codigos_promocionais_texto_".$row_rsLinguas["sufixo"]." WHERE id IN $lista";
	      $rsP = DB::getInstance()->prepare($query_rsP);
	      $rsP->execute();
	      DB::close();
	    }

			$query_rsDel = "DELETE FROM codigos_promocionais WHERE id IN $lista";
			$rsDel = DB::getInstance()->query($query_rsDel);
			$rsDel->execute();
			DB::close();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( 'id', 'nome', 'codigo', 'tipo', 'datai', 'dataf', 'visivel_listagem', 'visivel', '');
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
		$pesq_nome = utf8_decode($_REQUEST['form_nome']);
		$pesq_codigo = utf8_decode($_REQUEST['form_codigo']);
		$pesq_datai = $_REQUEST['form_inicio'];
		$pesq_dataf = $_REQUEST['form_fim'];
		$pesq_visivel_listagem = $_REQUEST['form_visivel_listagem'];
		$pesq_activo = $_REQUEST['form_activo'];
		$pesq_tipo = $_REQUEST['form_tipo'];
		
		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
		if($pesq_codigo != "") $where_pesq .= " AND codigo LIKE '%$pesq_codigo%'";
		if($pesq_datai != "") $where_pesq .= " AND datai = '$pesq_datai'";
		if($pesq_dataf != "") $where_pesq .= " AND dataf = '$pesq_dataf'";
		if($pesq_visivel_listagem != "") $where_pesq .= " AND visivel_listagem = '$pesq_visivel_listagem'";
		if($pesq_activo != "") $where_pesq .= " AND visivel = '$pesq_activo'";
		if($pesq_tipo != "") $where_pesq .= " AND tipo_codigo = '$pesq_tipo'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM codigos_promocionais WHERE id > 0".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM codigos_promocionais WHERE id >0".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
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
    $codigo = utf8_encode($row_rsTotal['codigo']);
    $datai = $row_rsTotal['datai'];
    $dataf = $row_rsTotal['dataf'];

    if($row_rsTotal['visivel_listagem'] == 1) {
		  $visivel_list = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta2 = "success";
	  } 
	  else {
		  $visivel_list = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta2 = "danger";
	  }

	  if($row_rsTotal['visivel'] == 1) {
		  $activo = $RecursosCons->RecursosCons['opt_ativo'];
		  $etiqueta = "success";
	  } 
	  else {
		  $activo = $RecursosCons->RecursosCons['opt_inativo'];
		  $etiqueta = "danger";
	  }

	  $query_rsTipo = "SELECT nome FROM codigos_promocionais_tipos WHERE id = '".$row_rsTotal['tipo_codigo']."'";
	  $rsTipo = DB::getInstance()->prepare($query_rsTipo);
	  $rsTipo->execute();
	  $totalRows_rsTipo = $rsTipo->rowCount();
	  $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
	  DB::close();

	  $tipo = '---';
	  if($totalRows_rsTipo > 0) {
	  	$tipo = utf8_encode($row_rsTipo['nome']);
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $nome,
	  $codigo,
	  $tipo,
	  $datai,
	  $dataf,
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel_list).'</span>',
	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($activo).'</span>',
	  '<a href="cod-promo-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>',
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>