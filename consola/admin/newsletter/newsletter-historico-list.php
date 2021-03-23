<?php include_once('../inc_pages.php');
include_once('newsletter-funcoes-logs.php');

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
		
		$query_rsProc = "SELECT * FROM newsletters_historico WHERE newsletter_id=:id AND id IN $lista";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
		$rsProc->execute();
		$totalRows_rsProc = $rsProc->rowCount();
		DB::close();
		
		$query_rsP = "SELECT * FROM newsletters WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		DB::close();
	
		if($totalRows_rsProc > 0) {
			while($row_rsProc = $rsProc->fetch()) {
				if($opcao == -1) {
					if($row_rsProc['estado']==2 || $row_rsProc['estado'] == 5) {			
						$query_insertSQL = "UPDATE newsletters_historico SET estado='4' WHERE id='".$row_rsProc["id"]."' AND newsletter_id=:id";
						$insertSQL = DB::getInstance()->prepare($query_insertSQL);
						$insertSQL->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
						$insertSQL->execute();
						DB::close();
				
						$que_fez="suspendeu agendamento de ".$row_rsProc['data']." // ".$row_rsProc['hora'];
						$nome_utilizador=$row_rsUser["username"];
						$class_news_logs->logs_agendamentos($nome_utilizador, $_REQUEST['id_news'], $que_fez, $row_rsP['titulo']);
					}
					else if($row_rsProc['estado']==1 || $row_rsProc['estado']==3) {
						$query_insertSQL = "DELETE FROM newsletters_historico WHERE id='".$row_rsProc["id"]."' AND newsletter_id=:id";
						$insertSQL = DB::getInstance()->prepare($query_insertSQL);
						$insertSQL->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
						$insertSQL->execute();
						DB::close();
						
						$query_insertSQL = "DELETE FROM newsletters_historico_listas WHERE newsletter_historico='".$row_rsProc["id"]."' AND newsletter_id=:id";
						$insertSQL = DB::getInstance()->prepare($query_insertSQL);
						$insertSQL->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
						$insertSQL->execute();
						DB::close();
						
						$query_insertSQL = "DELETE FROM news_links WHERE newsletter_id_historico='".$row_rsProc["id"]."'";
						$insertSQL = DB::getInstance()->prepare($query_insertSQL);
						$insertSQL->execute();
						DB::close();
						
						$query_insertSQL = "DELETE FROM newsletters_vistos WHERE newsletter_id_historico='".$row_rsProc["id"]."' AND newsletter_id=:id";
						$insertSQL = DB::getInstance()->prepare($query_insertSQL);
						$insertSQL->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
						$insertSQL->execute();
						DB::close();
	        
		        $query_insertSQL = "DELETE FROM news_remover WHERE newsletter_id_historico='".$row_rsProc["id"]."' AND newsletter_id=:id";
		        $insertSQL = DB::getInstance()->prepare($query_insertSQL);
		        $insertSQL->bindParam(':id', $_REQUEST["id_news"], PDO::PARAM_INT);
		        $insertSQL->execute();
		        DB::close();
				
						$que_fez="removeu agendamento de ".$row_rsProc['data']." // ".$row_rsProc['hora'];
						$nome_utilizador=$row_rsUser["username"];
						$class_news_logs->logs_agendamentos($nome_utilizador, $_REQUEST['id_news'], $que_fez, $row_rsP['titulo']);
					}
				}
				else if($opcao == -2) {
					if($row_rsProc['estado'] == 4) {
						$query_insertSQL = "UPDATE newsletters_historico SET estado='5' WHERE id='".$row_rsProc["id"]."' AND newsletter_id=:id";
						$insertSQL = DB::getInstance()->prepare($query_insertSQL);
						$insertSQL->bindParam(':id', $_REQUEST['id_news'], PDO::PARAM_INT);
						$insertSQL->execute();
						DB::close();
				
						$que_fez="reactivou agendamento de ".$row_rsProc['data']." // ".$row_rsProc['hora'];
						$nome_utilizador=$row_rsUser["username"];
						$class_news_logs->logs_agendamentos($nome_utilizador, $_REQUEST['id_news'], $que_fez, $row_rsP['titulo']);
					}
				}
			}
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY hist.id DESC";
  $colunas = array('hist.id', 'hist.data', 'hist.hora', 'hist.estado', 'hist.grupo', 'hist.limite', '', '');
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
		$pesq_data= utf8_decode($_REQUEST['form_data']);
		$pesq_hora= utf8_decode($_REQUEST['form_hora']);
		$pesq_estado= utf8_decode($_REQUEST['form_estado']);
		$pesq_tipo= utf8_decode($_REQUEST['form_tipo']);
		
		if($pesq_data != "") $where_pesq .= " AND hist.data = '$pesq_data'";
		if($pesq_hora != "") $where_pesq .= " AND hist.hora = '$pesq_hora'";
		if($pesq_estado != "") $where_pesq .= " AND hist.estado = '$pesq_estado'";
		if($pesq_tipo != "") $where_pesq .= " AND hist.grupo = '$pesq_tipo'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT hist.*, hist_estado.id AS estado_id, hist_estado.nome AS estado_nome FROM newsletters_historico AS hist, newsletters_historico_estados AS hist_estado WHERE hist_estado.id = hist.estado AND hist.newsletter_id = '".$_REQUEST['id_news']."'".$where_pesq." GROUP BY hist.id".$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT hist.*, hist_estado.id AS estado_id, hist_estado.nome AS estado_nome FROM newsletters_historico AS hist, newsletters_historico_estados AS hist_estado WHERE hist_estado.id = hist.estado AND hist.newsletter_id = '".$_REQUEST['id_news']."'".$where_pesq." GROUP BY hist.id".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  DB::close();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
    $id = $row_rsTotal['id'];
    $data = $row_rsTotal['data'];
    $hora = $row_rsTotal['hora'];
	  $estado = $row_rsTotal['estado_nome'];
	  $limite = $row_rsTotal['limite'];
	  $tipo = $row_rsTotal['grupo'];

	  $query_rsTipo = "SELECT nome FROM news_grupos WHERE id = '$tipo'";
	  $rsTipo = DB::getInstance()->prepare($query_rsTipo);
	  $rsTipo->execute();
	  $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
	  DB::close();

	  $tipo = '---';
	  if($row_rsTipo['nome']) {
	  	$tipo = utf8_encode($row_rsTipo['nome']);
	  }

	  if($limite == 0) {
	  	$limite = 'Sem limite';
	  }

	  if($row_rsTotal['estado_id'] < 4) {
	  	$etiqueta = "success";
	  }
	  else if($row_rsTotal['estado_id'] == 5) {
	  	$etiqueta = "warning";
	  }
	  else {
	  	$etiqueta = "danger";
	  }
	  
	  $query_rsList = "SELECT news_listas.* FROM newsletters_historico_listas, news_listas WHERE newsletters_historico_listas.newsletter_historico='".$row_rsTotal['id']."' AND newsletters_historico_listas.lista=news_listas.id GROUP BY news_listas.id ORDER BY news_listas.ordem ASC, news_listas.nome ASC";
	  $rsList = DB::getInstance()->prepare($query_rsList);
	  $rsList->execute();
	  $totalRows_rsList = $rsList->rowCount();
	  DB::close();
	
	  $array_lista="";
	
	  if($totalRows_rsList > 0) {
			$array_lista="(";
			
			while($row_rsList = $rsList->fetch()) {
				if($array_lista=="("){
					$array_lista.=utf8_encode($row_rsList['nome']);
				}else{
					$array_lista.=", ".utf8_encode($row_rsList['nome']);
				}
				
			}
			
			$array_lista.=")";
	  }
	  
	  $processar = '<a onClick="envia_news('.$_REQUEST['id_news'].','.$id.')" href="javascript:void(null)" class="btn btn-xs default btn-editable blue"><i class="fa fa-history"></i> Processar</a>';
	  $ver = '<a href="newsletter-historico-view.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-search"></i> Ver</a>';
	  
    $records["data"][] = array(
		  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
		  $data,
		  $hora,
		  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($estado).'</span>',
		  $tipo,
		  $array_lista,
		  $limite,
		  $ver,
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>