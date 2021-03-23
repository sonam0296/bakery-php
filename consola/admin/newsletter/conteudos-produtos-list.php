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
			if($opcao == 1) $query_rsUpd = "UPDATE news_produtos SET visivel = '1' WHERE id IN $lista";
			else $query_rsUpd = "UPDATE news_produtos SET visivel = '0' WHERE id IN $lista";
			$rsUpd = DB::getInstance()->query($query_rsUpd);
			$rsUpd->execute();
			DB::close();
		} 
		else if($opcao == '-1') { // elimina listas selecionadas
			$query_rsP = "SELECT * FROM news_produtos WHERE id IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
			$rsP->execute();
			$totalRows_rsP = $rsP->rowCount();
			DB::close();
			
			if($totalRows_rsP > 0) {
				while($row_rsP = $rsP->fetch()) {
					// elimina a imagem se não existir
					$query_rsExiste = "SELECT * FROM news_produtos WHERE imagem1='".$row_rsP["imagem1"]."' AND id NOT IN $lista";
					$rsExiste = DB::getInstance()->prepare($query_rsExiste);
					$rsExiste->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
					$rsExiste->execute();
					$totalRows_rsExiste = $rsExiste->rowCount();
					DB::close();
					
					// elimina a imagem se não existir
					if($totalRows_rsExiste == 0) {
						@unlink("../../../imgs/imgs_news/produtos/".$row_rsP["imagem1"]);
					}

					// elimina a imagem se não existir
					$query_rsExiste = "SELECT * FROM news_produtos WHERE imagem2='".$row_rsP["imagem2"]."' AND id NOT IN $lista";
					$rsExiste = DB::getInstance()->prepare($query_rsExiste);
					$rsExiste->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
					$rsExiste->execute();
					$totalRows_rsExiste = $rsExiste->rowCount();
					DB::close();
					
					// elimina a imagem se não existir
					if($totalRows_rsExiste == 0) {
						@unlink("../../../imgs/imgs_news/produtos/".$row_rsP["imagem2"]);
					}
				}
			}
			
			$query_rsDel = "DELETE FROM news_produtos WHERE id IN $lista";
			$rsDel = DB::getInstance()->query($query_rsDel);
			$rsDel->execute();
			DB::close();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC";
  $colunas = array('id', 'nome', 'tipo', 'imagem1', 'ordem', 'visivel', '');
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
  
  $query_rsTotal = "SELECT * FROM news_produtos WHERE id_tema = '".$_REQUEST['id_tema']."'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM news_produtos WHERE id_tema = '".$_REQUEST['id_tema']."'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  
	  if($row_rsTotal['tipo2'] == 1) $tipo = "Texto e Imagem";
	  elseif($row_rsTotal['tipo2'] == 2) $tipo = "Texto";
	  elseif($row_rsTotal['tipo2'] == 3) $tipo = "Imagem";
	  elseif($row_rsTotal['tipo2'] == 4) $tipo = "2 Textos/Imagens";
	  elseif($row_rsTotal['tipo2'] == 5) $tipo = "2 Imagens";
	  elseif($row_rsTotal['tipo2'] == 6) $tipo = "Botão";
	  else $tipo = "Produto";
	  
	  $imagem = '';
	  if($row_rsTotal['imagem1'] && file_exists("../../../imgs/imgs_news/produtos/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/imgs_news/produtos/'.utf8_encode($row_rsTotal['imagem1']).'" class="fancybox-button" data-rel="fancybox-button"><img style="max-height: 70px; max-width: 100px" src="../../../imgs/imgs_news/produtos/'.utf8_encode($row_rsTotal['imagem1']).'" /></a>';
	  
	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = "Sim";
		  $etiqueta2 = "success";
	  } else {
		  $visivel = "Não";
		  $etiqueta2 = "danger";
	  }
	  
	  $editar = '<a href="conteudos-produtos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>';
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
	  utf8_encode($tipo),
	  $imagem,
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