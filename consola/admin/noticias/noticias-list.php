<?php include_once('../inc_pages.php'); ?>
<?php 
  /* 
   * Paging
   */

  if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
	
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
		
		if($opcao == 3 || $opcao == 4) { // colocar todas as notícias visíveis
			foreach($row_rsLinguas as $linguas) { 
				if($opcao == 3) $query_rsUpd = "UPDATE noticias_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE noticias_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();
			}

			include_once("../funcoes.php");
			alteraSessions('noticias');
		}
		if($opcao == 5 || $opcao == 6) { // colocar todas as notícias visíveis
			foreach($row_rsLinguas as $linguas) { 
				if($opcao == 5) $query_rsUpd = "UPDATE noticias_".$linguas["sufixo"]." SET destaque = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE noticias_".$linguas["sufixo"]." SET destaque = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();
			}

			include_once("../funcoes.php");
			alteraSessions('noticias');
		}
		else if($opcao == '-1') { // elimina as notícias selecionadas
			$query_rsImagens = "SELECT imagem1, imagem2 FROM noticias_imagens WHERE id_peca IN $lista";
			$rsImagens = DB::getInstance()->prepare($query_rsImagens);
			$rsImagens->execute();
			$totalRows_rsImagens = $rsImagens->rowCount();

			if($totalRows_rsImagens > 0) {
				while($row_rsImagens = $rsImagens->fetch()) {
					@unlink("../../../imgs/noticias/".$row_rsImagens['imagem1']);
					@unlink("../../../imgs/noticias/".$row_rsImagens['imagem2']);
				}
			}

			$query_rsDel = "DELETE FROM noticias_imagens WHERE id_peca IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();

			foreach($row_rsLinguas as $linguas) { 
				$query_rsP = "SELECT imagem1, ficheiro FROM noticias_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsP = DB::getInstance()->prepare($query_rsP);
				$rsP->execute();
				$totalRows_rsP = $rsP->rowCount();
				
				while($row_rsP = $rsP->fetch()) {
					@unlink("../../../imgs/noticias/".$row_rsP['imagem1']);
					@unlink("../../../imgs/noticias/".$row_rsP['ficheiro']);
				}
			
				$query_rsDel = "DELETE FROM noticias_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();
			}

			include_once("../funcoes.php");
			alteraSessions('noticias');
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC, data DESC";
  $colunas = array( '', 'data', 'nome', '', 'ordem', 'destaque', 'visivel', '');
  if(isset($_REQUEST['order'])) {
	  $sOrder = " ORDER BY ";
	  for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
	 	 if($i>0) $sOrder .= ", ";
		 $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
	  }
  }
  
  // pesquisa
  $where_pesq = "";
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
		$pesq_data = $_REQUEST['form_data'];
		$pesq_nome = utf8_decode($_REQUEST['form_nome']);
		$pesq_visivel = $_REQUEST['form_visivel'];
		$pesq_destaque = $_REQUEST['form_destaque'];
		
		if($pesq_data != "") $where_pesq .= " AND data = '$pesq_data%'";
		if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";
		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
		if($pesq_destaque != "") $where_pesq .= " AND destaque = '$pesq_destaque'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM noticias_pt WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM noticias_pt WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $data = $row_rsTotal['data'];
    $nome = utf8_encode($row_rsTotal['nome']);
      
	  if($row_rsTotal['imagem1'] && file_exists("../../../imgs/noticias/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/noticias/'.utf8_encode($row_rsTotal['imagem1']).'" data-fancybox="gallery"><img src="../../../imgs/noticias/'.utf8_encode($row_rsTotal['imagem1']).'" height="70" /></a>';
	  else $imagem = '';
	  
	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta2 = "success";
	  } else {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta2 = "danger";
	  }

	  if($row_rsTotal['destaque'] == 1) {
		  $destaque = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta1 = "success";
	  } else {
		  $destaque = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta1 = "danger";
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $data,
	  $nome,
	  $imagem,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta1.'">'.utf8_encode($destaque).'</span>',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="noticias-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>