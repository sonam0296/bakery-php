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
	
	if($opcao == '-1') {}
	
  }
  
  // ordenação
  $sOrder = " ORDER BY id ASC";
  $colunas = array( 'id', 'nome', '', '', '');
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
	
	if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM galerias_pt WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM galerias_pt WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  if($row_rsTotal['imagem1'] && file_exists("../../../imgs/galerias/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/galerias/'.utf8_encode($row_rsTotal['imagem1']).'" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/galerias/'.utf8_encode($row_rsTotal['imagem1']).'" height="70" /></a>';
	  
      $records["data"][] = array(
		  $id,
		  $nome,
		  $imagem,
		  '<a href="galerias-conteudo.php?id='.$id.'" class="btn btn-xs blue btn-editable"><i class="fa fa-search"></i> '. $RecursosCons->RecursosCons['ver_label'].' </a>',
		  '<a href="galerias-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
      );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>