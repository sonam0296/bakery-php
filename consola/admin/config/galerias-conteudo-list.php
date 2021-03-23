<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */
   
   // actualiza estado dos registos selecionados
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
	
	if($opcao == 1 || $opcao == 2) { // colocar todas as notícias visíveis
	
		if($opcao == 1) $query_rsUpd = "UPDATE galerias_conteudo SET visivel = '1' WHERE id IN $lista";
		else $query_rsUpd = "UPDATE galerias_conteudo SET visivel = '0' WHERE id IN $lista";
		$rsUpd = DB::getInstance()->query($query_rsUpd);
		$rsUpd->execute();
		DB::close();
			
	} elseif($opcao == '-1') {
		$query_rsProjecto = "SELECT * FROM galerias_conteudo WHERE id IN $lista";
		$rsProjecto = DB::getInstance()->prepare($query_rsProjecto);
		$rsProjecto->execute();
		$totalRows_rsProjecto = $rsProjecto->rowCount();
		DB::close();	
		
		while($row_rsProjecto = $rsProjecto->fetch()) {
			if ($row_rsProjecto['imagem1']!='') { 
				@unlink('../../../imgs/galerias/'.$row_rsProjecto['imagem1']);
			}
		}
		
		$query_deleteSQL = "DELETE FROM galerias_conteudo WHERE id IN $lista";
		$deleteSQL = DB::getInstance()->prepare($query_deleteSQL);
		$deleteSQL->execute();
		DB::close();	
	}
	
  }
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC";
  $colunas = array( 'id', 'nome', '', 'ordem', 'visivel', '');
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
	$pesq_visivel = $_REQUEST['form_visivel'];
	
	if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";
	if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM galerias_conteudo WHERE id_galeria = '".$_REQUEST['id_galeria']."'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM galerias_conteudo WHERE id_galeria = '".$_REQUEST['id_galeria']."'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  
	  $imagem = '';
	  if($id != 1 && $row_rsTotal['imagem1'] && file_exists("../../../imgs/galerias/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/galerias/'.utf8_encode($row_rsTotal['imagem1']).'" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/galerias/'.utf8_encode($row_rsTotal['imagem1']).'" height="70" /></a>';
	  elseif($id==1) {
		 // $imagem = '<a href="'.$row_rsTotal['link'].'" target="_blank" class="btn btn-xs blue btn-editable"><i class="fa fa-search"></i> Ver</a>';
	  }
	  	
      $ordem = '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">';
	  
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
		  $ordem,
		  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
		  '<a href="galerias-conteudo-edit.php?id_galeria='.$_REQUEST['id_galeria'].'&id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['editar'].'</a>',
      );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>