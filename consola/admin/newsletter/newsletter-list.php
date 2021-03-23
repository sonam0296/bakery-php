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
  	
  	if($opcao == '-1') { // elimina listas selecionadas

      $query_rsProc = "SELECT * FROM newsletters_historico WHERE newsletter_id IN $lista";
      $rsProc = DB::getInstance()->prepare($query_rsProc);
      $rsProc->execute();
      $totalRows_rsProc = $rsProc->rowCount();
      DB::close();

      while($row = $rsProc->fetch()) {
        $query_insertSQL = "DELETE FROM newsletters_historico WHERE id='".$row["id"]."' AND newsletter_id=:id";
        $insertSQL = DB::getInstance()->prepare($query_insertSQL);
        $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
        $insertSQL->execute();
        DB::close();
        
        $query_insertSQL = "DELETE FROM newsletters_historico_listas WHERE newsletter_historico='".$row["id"]."' AND newsletter_id=:id";
        $insertSQL = DB::getInstance()->prepare($query_insertSQL);
        $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
        $insertSQL->execute();
        DB::close();
        
        $query_insertSQL = "DELETE FROM news_links WHERE newsletter_id_historico='".$row["id"]."'";
        $insertSQL = DB::getInstance()->prepare($query_insertSQL);
        $insertSQL->execute();
        DB::close();
        
        $query_insertSQL = "DELETE FROM newsletters_vistos WHERE newsletter_id_historico='".$row["id"]."' AND newsletter_id=:id";
        $insertSQL = DB::getInstance()->prepare($query_insertSQL);
        $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
        $insertSQL->execute();
        DB::close();
        
        $query_insertSQL = "DELETE FROM news_remover WHERE newsletter_id_historico='".$row["id"]."' AND newsletter_id=:id";
        $insertSQL = DB::getInstance()->prepare($query_insertSQL);
        $insertSQL->bindParam(':id', $row["newsletter_id"], PDO::PARAM_INT);
        $insertSQL->execute();
        DB::close();
      }

  		$query_rsDel = "DELETE FROM newsletters WHERE id IN $lista";
  		$rsDel = DB::getInstance()->query($query_rsDel);
  		$rsDel->execute();
  		DB::close();
  	}
    else if($opcao == 1 || $opcao == 2) { // colocar todas as notícias visíveis
      if($opcao == 1) $query_rsUpd = "UPDATE newsletters SET visivel = '1' WHERE id IN $lista";
      else $query_rsUpd = "UPDATE newsletters SET visivel = '0' WHERE id IN $lista";
      $rsUpd = DB::getInstance()->query($query_rsUpd);
      $rsUpd->execute();
      DB::close();
    }
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array('id', 'titulo', 'tipo', 'data_criacao', 'data_envio', '', '', 'visivel', '');
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
  	$pesq_form= utf8_decode($_REQUEST['form_nome']);
  	$pesq_data= $_REQUEST['form_data'];
  	$pesq_envio= $_REQUEST['form_envio'];
    $pesq_tipos= $_REQUEST['form_tipos'];
    $pesq_visivel= $_REQUEST['form_visivel'];
  	
  	if($pesq_form != "") $where_pesq .= " AND titulo LIKE '%$pesq_form%'";
  	if($pesq_data != "") $where_pesq .= " AND data_criacao LIKE '$pesq_data%'";
  	if($pesq_envio != "") $where_pesq .= " AND data_envio = '$pesq_envio'";
    if($pesq_tipos != "") $where_pesq .= " AND tipo = '$pesq_tipos'";
    if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM newsletters WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM newsletters WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $nome = utf8_encode($row_rsTotal['titulo']);
    $data_criacao = $row_rsTotal['data_criacao'];
    $data_envio = $row_rsTotal['data_envio'];
	  
	  $agendar = '<a href="newsletter-enviar.php?id='.$id.'" class="btn btn-xs default btn-editable blue"><i class="fa fa-calendar"></i> Agendar</a>';
	  $download = '<a href="newsletter-finalizar.php?id='.$id.'" class="btn btn-xs default btn-editable green"><i class="fa fa-download"></i> Descarregar</a>';
	  $editar = '<a href="newsletter-edit.php?id='.$id.'" class="btn btn-xs default btn-editable" target="_blank"><i class="fa fa-search"></i> Ver</a>';
	  
    $query_rsTipo = "SELECT nome FROM news_tipos_pt WHERE id = '".$row_rsTotal['tipo']."'";
    $rsTipo = DB::getInstance()->prepare($query_rsTipo);
    $rsTipo->execute();
    $totalRows_rsTipo = $rsTipo->rowCount();
    $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
    DB::close();

    $tipo = '---';
    if($totalRows_rsTipo > 0) {
      $tipo = utf8_encode($row_rsTipo['nome']);
    }

    $query_rsContNews = "SELECT id FROM news_conteudo WHERE id=:id";
    $rsContNews = DB::getInstance()->prepare($query_rsContNews);
    $rsContNews->bindParam(':id', $row_rsTotal['conteudo'], PDO::PARAM_INT);
    $rsContNews->execute();
    $totalRows_rsContNews = $rsContNews->rowCount();
    DB::close();

    if($totalRows_rsContNews == 0) {
      $nome.=' <span class="label label-sm label-danger">'.utf8_encode('SEM CONTEÚDO!').'</span>';
    }

    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
    $tipo,
	  $data_criacao,
	  $data_envio,
	  $agendar,
	  $download,
	  $editar,
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>