<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */
   
  // actualiza estado dos registos selecionados
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
		
		if($opcao == 1 || $opcao == 2) { // colocar todas as notícias visíveis	
			if($opcao == 1) $query_rsUpd = "UPDATE portes_gratis SET visivel = '1' WHERE id IN $lista";
			else $query_rsUpd = "UPDATE portes_gratis SET visivel = '0' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->prepare($query_rsUpd);
			$rsUpd->execute();
		} 
		elseif($opcao == '-1') {
			$query_rsP = "DELETE FROM portes_gratis WHERE id IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
			
			$query_rsP = "DELETE FROM portes_gratis_categorias WHERE portes_gratis IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
			
			$query_rsP = "DELETE FROM portes_gratis_zonas WHERE portes_gratis IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
			
			$query_rsP = "DELETE FROM portes_gratis_marcas WHERE portes_gratis IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY nome ASC";
  $colunas = array( 'id', 'nome', 'datai', 'dataf', 'visivel', '');
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
		$pesq_nome= utf8_decode($_REQUEST['form_nome']);
		$pesq_inicio= utf8_decode($_REQUEST['form_inicio']);
		$pesq_fim= utf8_decode($_REQUEST['form_fim']);
		$pesq_visivel= utf8_decode($_REQUEST['form_visivel']);
		
		if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";
		if($pesq_inicio != "") $where_pesq .= " AND datai = '$pesq_inicio'";
		if($pesq_fim != "") $where_pesq .= " AND dataf = '$pesq_fim'";
		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM portes_gratis WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM portes_gratis WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $datai = utf8_encode($row_rsTotal['datai']);	
    $dataf = utf8_encode($row_rsTotal['dataf']);	
	  
	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = "Sim";
		  $etiqueta2 = "success";
	  } 
	  else {
		  $visivel = "Não";
		  $etiqueta2 = "danger";
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
	  $datai,
	  $dataf,
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="p_portes_gratis-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>