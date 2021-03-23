<?php include_once('../inc_pages.php'); ?>
<?php
  /* 
   * Paging
   */

  if(isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
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
		
		if($opcao == 1 || $opcao == 2) {// colocar todos os banners visíveis			
			foreach ($row_rsLinguas as $linguas) {
				if($opcao == 1) $query_rsUpd = "UPDATE clientes_blocos_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE clientes_blocos_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();
			}
		} 
		else if($opcao == '-1') { // elimina os banners selecionados
			foreach ($row_rsLinguas as $linguas) {
				$query_rsP = "SELECT * FROM clientes_blocos_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsP = DB::getInstance()->prepare($query_rsP);
				$rsP->execute();
				$totalRows_rsP = $rsP->rowCount();
				
				while($row_rsP = $rsP->fetch()) {
					@unlink("../../../imgs/area_reservada/".$row_rsP['imagem1']);
				}
			
				$query_rsDel = "DELETE FROM clientes_blocos_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();
			}
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC";
  $colunas = array('', 'nome', 'tipo', '', 'ordem', 'visivel', '');
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
		$pesq_nome = utf8_decode($_REQUEST['form_nome']);
		$pesq_tipo = $_REQUEST['form_tipo'];
		$pesq_visivel = $_REQUEST['form_visivel'];
		
		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
		if($pesq_tipo != "") $where_pesq .= " AND tipo = '$pesq_tipo'";
		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT id, nome, imagem1, tipo, visivel, ordem FROM clientes_blocos_pt WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT id, nome, imagem1, tipo, visivel, ordem FROM clientes_blocos_pt WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
     
	  if($row_rsTotal['imagem1'] && file_exists("../../../imgs/area_reservada/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/area_reservada/'.utf8_encode($row_rsTotal['imagem1']).'" data-fancybox="gallery" data-caption="'.$row_rsTotal['nome'].'" ><img src="../../../imgs/area_reservada/'.utf8_encode($row_rsTotal['imagem1']).'" width="100%" style="max-width:120px;" /></a>';
	  else $imagem = '';
	  
	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta2 = "success";
	  } 
	  else {
		  $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta2 = "danger";
	  }

	  if($row_rsTotal['tipo'] == 1) {
	  	$tipo = utf8_encode("Imagem");
	  }
	  else if($row_rsTotal['tipo'] == 2) {
	  	$tipo = utf8_encode("Imagem & Texto");
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $nome,
	  $tipo,
	  $imagem,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="blocos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i>'. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>