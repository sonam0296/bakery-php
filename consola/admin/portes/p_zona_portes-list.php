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
		
		if($opcao == '-1') {
			$query_rsP = "DELETE FROM zonas WHERE id IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();

			
			$query_rsP = "UPDATE paises SET zona='1' WHERE zona IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();

			
			$query_rsP = "DELETE FROM zonas_met_envio WHERE id_zona IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();

			
			$query_rsP = "DELETE FROM zonas_met_pagamento WHERE id_zona IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();

			
			$query_rsExiste = "SHOW TABLES LIKE 'portes_gratis_zonas'";
			$rsExiste = DB::getInstance()->prepare($query_rsExiste);
			$rsExiste->execute();
			$totalRows_rsExiste = $rsExiste->rowCount();
	
			
			if($totalRows_rsExiste > 0) {
				$query_rsP = "DELETE FROM portes_gratis_zonas WHERE zona IN $lista";
				$rsP = DB::getInstance()->prepare($query_rsP);
				$rsP->execute();
	
			}
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY nome ASC";
  $colunas = array( 'id', 'nome', '');
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
		
		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM zonas WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM zonas WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->query($query_rsTotal);
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
	  
	  if($id!=1) $id_reg = '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">';
	  else $id_reg = '';
	  
    $records["data"][] = array(
	  $id_reg,
	  $nome,
	  '<a href="p_zona_portes-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>