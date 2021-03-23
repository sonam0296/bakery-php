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
		
		if($opcao == 1 || $opcao == 2) { // colocar todas as notícias em destaque
			if($opcao == 1) $query_rsUpd = "UPDATE news_temas SET visivel = '1' WHERE id IN $lista";
			else $query_rsUpd = "UPDATE news_temas SET visivel = '0' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->query($query_rsUpd);
			$rsUpd->execute();
			DB::close();
		} 
		else if($opcao == '-1') { // elimina listas selecionadas
			$query_rsTema = "SELECT * FROM news_temas WHERE id IN $lista";
	    $rsTema = DB::getInstance()->prepare($query_rsTema);
	    $rsTema->execute();
	    $totalRows_rsTema = $rsTema->rowCount();
	    DB::close();

	    if($totalRows_rsTema > 0) {
	      while($row_rsTema = $rsTema->fetch()) {
	        $query_rsProdutos = "SELECT * FROM news_produtos WHERE id_tema=:id";
	        $rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	        $rsProdutos->bindParam(':id', $row_rsTema['id'], PDO::PARAM_INT);
	        $rsProdutos->execute();
	        $totalRows_rsProdutos = $rsProdutos->rowCount();
	        DB::close();

	        if($totalRows_rsProdutos > 0) {
	          while($row_rsProdutos = $rsProdutos->fetch()) {
	            @unlink('../../../imgs/imgs_news/produtos/'.$row_rsProdutos['imagem1']);
	            @unlink('../../../imgs/imgs_news/produtos/'.$row_rsProdutos['imagem2']);
	          }

	          $query_rsDelete = "DELETE FROM news_produtos WHERE id_tema=:id";
	          $rsDelete = DB::getInstance()->prepare($query_rsDelete);
	          $rsDelete->bindParam(':id', $row_rsTema['id'], PDO::PARAM_INT);
	          $rsDelete->execute();
	          DB::close();
	        }
	      }
	    }

	    $query_rsDelete = "DELETE FROM news_temas WHERE id IN $lista";
	    $rsDelete = DB::getInstance()->prepare($query_rsDelete);
	    $rsDelete->execute();
	    DB::close();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC";
  $colunas = array('id', 'nome', 'tipo', 'ordem', 'visivel', '');
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
	$pesq_visivel = $_REQUEST['form_visivel'];
	
	if($pesq_form != "") $where_pesq .= " AND (nome = '$pesq_form' OR nome LIKE '%$pesq_form%')";
	if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM news_temas WHERE conteudo = '".$_REQUEST['id_cont']."'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM news_temas WHERE conteudo = '".$_REQUEST['id_cont']."'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  
	  if($row_rsTotal['tipo'] == 1) $tipo = "Produtos";
	  else $tipo = "Texto e/ou Imagem";
	  
	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = "Sim";
		  $etiqueta2 = "success";
	  } else {
		  $visivel = "Não";
		  $etiqueta2 = "danger";
	  }
	  
	  $editar = '<a href="conteudos-blocos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>';
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
	  $tipo,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  $editar,
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>