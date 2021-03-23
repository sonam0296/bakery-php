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

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$row_rsLinguas = $rsLinguas->fetchAll();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		
		if($opcao == '-1') { // elimina utilizadores selecionados
			$query_rsDel = "UPDATE l_precos_historial SET id_marca = 0 WHERE id_marca IN $lista";
	    $rsDel = DB::getInstance()->prepare($query_rsDel);
	    $rsDel->execute();
	    DB::close();

			foreach ($row_rsLinguas as $linguas) {
				$query_rsProc = "SELECT imagem1 FROM l_marcas_".$linguas['sufixo']." WHERE id IN $lista";
				$rsProc = DB::getInstance()->query($query_rsProc);
				$rsProc->execute();
				$totalRows_rsProc = $rsProc->rowCount();	
				
				if($totalRows_rsProc > 0) {
					while($row_rsProc = $rsProc->fetch()) {
						if ($row_rsProc['imagem1'] != '') { 
							@unlink('../../../imgs/marcas/'.$row_rsProc['imagem1']);
						}
					}
				}
				
				$query_rsP = "UPDATE l_pecas_".$linguas["sufixo"]." SET marca = 0 WHERE marca IN $lista";
				$rsP = DB::getInstance()->prepare($query_rsP);
				$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
				$rsP->execute();

				$query_rsDel = "DELETE FROM l_marcas_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsDel = DB::getInstance()->query($query_rsDel);
				$rsDel->execute();
			}
		}
		else if($opcao == 3 || $opcao == 4) { // colocar todas as notícias visíveis
			foreach ($row_rsLinguas as $linguas) {
				if($opcao == 3) $query_rsUpd = "UPDATE l_marcas_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE l_marcas_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->query($query_rsUpd);
				$rsUpd->execute();
				
			}
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'nome', 'imagem1', 'ordem', 'visivel', '');
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
		$pesq_visivel = $_REQUEST['form_visivel'];

		if($pesq_form != "") $where_pesq .= " AND (nome LIKE '%$pesq_form%')";
		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  
  $query_rsTotal = "SELECT * FROM l_marcas_pt WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM l_marcas_pt WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  $nome=utf8_encode($row_rsTotal['nome']);
		
		if($row_rsTotal['imagem1'] && file_exists("../../../imgs/marcas/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/marcas/'.utf8_encode($row_rsTotal['imagem1']).'" data-fancybox="gallery" data-caption="'.utf8_encode($row_rsTotal['nome']).'" ><img width="100" src="../../../imgs/marcas/'.utf8_encode($row_rsTotal['imagem1']).'" /></a>';
	  else $imagem = '';
	  
	  //visivel
	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta2 = "success";
	  } else {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta2 = "danger";
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
	  $imagem,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="marcas-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>