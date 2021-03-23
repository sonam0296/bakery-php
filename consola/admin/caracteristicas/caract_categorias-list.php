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
		
		if($opcao == '-1') { // elimina utilizadores selecionados
			$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
			$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
			$rsLinguas->execute();
			$totalRows_rsLinguas = $rsLinguas->rowCount();
			
			while($row_rsLinguas = $rsLinguas->fetch()) {
				$query_rsDel = "DELETE FROM l_caract_categorias_en WHERE id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();						
			}
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'nome', 'ordem', '');
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
  	$pesq_form= utf8_decode($_REQUEST['form_nome']);
  	
  	if($pesq_form != "") $where_pesq .= " AND (nome = '$pesq_form' OR nome LIKE '%$pesq_form%')";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT id, nome, ordem FROM l_caract_categorias_en WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
    
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT id, nome, ordem FROM l_caract_categorias_en WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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

    //Cores & Tamanhos
    if($id == 1)
    	$input = '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'" disabled>';
    else
    	$input = '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">';
  
    $records["data"][] = array(
	  $input,
	  $nome,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<a href="caract_categorias-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>