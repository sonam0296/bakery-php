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
			foreach ($row_rsLinguas as $linguas) {
				$query_rsProc = "SELECT * FROM l_categorias_".$linguas['sufixo']." WHERE id IN $lista";
				$rsProc = DB::getInstance()->prepare($query_rsProc);
				$rsProc->execute();
				$totalRows_rsProc = $rsProc->rowCount();	
				
				if($totalRows_rsProc > 0) {
					while($row_rsProc = $rsProc->fetch()) {
						$insertSQL = "UPDATE l_categorias_".$linguas["sufixo"]." SET cat_mae='0' WHERE cat_mae=:cat_mae";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':cat_mae', $row_rsProc['id'], PDO::PARAM_INT);
						$rsInsert->execute();
						
						
						if($row_rsProc['imagem1'] != '') { 
							@unlink('../../../imgs/categorias/'.$row_rsProc['imagem1']);
						}
						
						if($row_rsProc['catalogo'] != '') { 
							@unlink('../../../imgs/categorias/'.$row_rsProc['catalogo']);
						}			
					}
				}

				$query_rsUpdate = "UPDATE l_pecas_".$linguas['sufixo']." SET categoria = 0 WHERE categoria IN $lista";
				$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
				$rsUpdate->execute();
				
				$query_rsDel = "DELETE FROM l_categorias_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();	
			}

			include_once("../funcoes.php");
			alteraSessions('categorias');
		} 

		else if($opcao == 5 || $opcao == 6) { // colocar todas as not�cias vis�veis

		
			foreach ($row_rsLinguas as $linguas) {
				if($opcao == 5) $query_rsUpd = "UPDATE l_categorias_".$linguas["sufixo"]." SET franchise = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE l_categorias_".$linguas["sufixo"]." SET franchise = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();
			}

			include_once("../funcoes.php");
			alteraSessions('categorias');
		}
			
		else if($opcao == 3 || $opcao == 4) { // colocar todas as not�cias vis�veis
			foreach ($row_rsLinguas as $linguas) {
				if($opcao == 3) $query_rsUpd = "UPDATE l_categorias_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE l_categorias_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();
			}

			include_once("../funcoes.php");
			alteraSessions('categorias');
		}
		
  }
  
  // ordena��o
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'nome', 'cat_mae', 'ordem', 'visivel', '');
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
		$pesq_categoria = $_REQUEST['form_categoria'];
		$pesq_visivel = $_REQUEST['form_visivel'];
		
		if($pesq_form != "") $where_pesq .= " AND (cat1.nome LIKE '%$pesq_form%' OR cat2.nome LIKE '%$pesq_form%' OR cat3.nome LIKE '%$pesq_form%')";
		if($pesq_categoria != "") $where_pesq .= " AND (cat1.id = '$pesq_categoria' OR cat2.id = '$pesq_categoria' OR cat3.id = '$pesq_categoria')";
		if($pesq_visivel != "") $where_pesq .= " AND cat1.visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);

  $query_rsTotal = "SELECT cat1.id, cat1.nome, cat1.cat_mae, cat1.visivel, cat1.ordem, cat2.nome AS nome_cat_mae, cat3.nome AS nome_cat_avo, cat1.franchise as franchise FROM l_categorias_en AS cat1 LEFT JOIN l_categorias_en AS cat2 ON cat1.cat_mae=cat2.id LEFT JOIN l_categorias_en AS cat3 ON cat2.cat_mae=cat3.id WHERE cat1.id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT cat1.id, cat1.nome, cat1.cat_mae, cat1.visivel, cat1.ordem, cat2.nome AS nome_cat_mae, cat3.nome AS nome_cat_avo, cat1.franchise as franchise FROM l_categorias_en AS cat1 LEFT JOIN l_categorias_en AS cat2 ON cat1.cat_mae=cat2.id LEFT JOIN l_categorias_en AS cat3 ON cat2.cat_mae=cat3.id WHERE cat1.id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $nome = "";
	  if($row_rsTotal['nome_cat_avo']){
		  $nome.=utf8_encode($row_rsTotal['nome_cat_mae'].' � ');
	  }
	  $nome.=utf8_encode($row_rsTotal['nome']);
		  
    $nome_principal = "-";
	  if($row_rsTotal['nome_cat_avo']){
		  $nome_principal = utf8_encode($row_rsTotal['nome_cat_avo']);
	  }else if($row_rsTotal['nome_cat_mae']){
		  $nome_principal = utf8_encode($row_rsTotal['nome_cat_mae']);
	  }
	  
	  //visivel
	  if($row_rsTotal['franchise'] == 1) {
		  $franchise = $RecursosCons->RecursosCons['text_visivel_sim'];
		  $etiqueta3 = "success";
	  } else {
		  $franchise = $RecursosCons->RecursosCons['text_visivel_nao'];
		  $etiqueta3 = "danger";
	  }

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
	  //html_entity_decode($nome),
	  str_replace("ï¿½","-",$nome),
	  $nome_principal,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta3.'">'.utf8_encode($franchise).'</span>',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="categorias-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>
