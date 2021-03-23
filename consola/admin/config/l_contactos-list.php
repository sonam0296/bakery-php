<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);
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
		
		if($opcao == 3 || $opcao == 4) { // colocar os destaques delecionados visíveis
			$query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";
	    $rsLinguas = DB::getInstance()->query($query_rsLinguas);
	    $rsLinguas->execute();
	    $totalRows_rsLinguas = $rsLinguas->rowCount();
	    DB::close();
			
			while($row_rsLinguas = $rsLinguas->fetch()) {
				if($opcao == 3) $query_rsUpd = "UPDATE contactos_locais_".$row_rsLinguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE contactos_locais_".$row_rsLinguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->query($query_rsUpd);
				$rsUpd->execute();
				DB::close();
			}

			include_once("../funcoes.php");
			alteraSessions('contactos');
		} 
		else if($opcao == 1 || $opcao == 2) { // colocar os destaques delecionados visíveis
			$query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";
	    $rsLinguas = DB::getInstance()->query($query_rsLinguas);
	    $rsLinguas->execute();
	    $totalRows_rsLinguas = $rsLinguas->rowCount();
	    DB::close();
			
			while($row_rsLinguas = $rsLinguas->fetch()) {
				if($opcao == 1) $query_rsUpd = "UPDATE contactos_locais_".$row_rsLinguas["sufixo"]." SET footer = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE contactos_locais_".$row_rsLinguas["sufixo"]." SET footer = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->query($query_rsUpd);
				$rsUpd->execute();
				DB::close();
			}

			include_once("../funcoes.php");
			alteraSessions('contactos');
		} 
		else if($opcao == '-1') { // elimina os destaques selecionados
			$query_rsLinguas = "SELECT * FROM linguas WHERE visivel=1";
			$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
			$rsLinguas->execute();
			DB::close();

			while($row_rsLinguas = $rsLinguas->fetch()) {
				$query_rsDel = "DELETE FROM contactos_locais_".$row_rsLinguas['sufixo']." WHERE id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();
				DB::close();
			}

			include_once("../funcoes.php");
			alteraSessions('contactos');
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY ordem ASC";
  $colunas = array('', 'nome', 'ordem', 'footer', 'visivel', '');
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
		$pesq_visivel = $_REQUEST['form_visivel'];
		$pesq_footer = $_REQUEST['form_footer'];
		
		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
		if($pesq_footer != "") $where_pesq .= " AND footer = '$pesq_footer'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM contactos_locais_$lingua_consola WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;

  $query_rsTotal = "SELECT * FROM contactos_locais_$lingua_consola WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  
	  if($row_rsTotal['footer'] == 1) {
		  $footer = "Sim";
		  $etiqueta = "success";
	  } 
	  else {
		  $footer = "Não";
		  $etiqueta = "danger";
	  }

	  if($row_rsTotal['visivel'] == 1) {
		  $visivel = "Sim";
		  $etiqueta2 = "success";
	  } 
	  else {
		  $visivel = "Não";
		  $etiqueta2 = "danger";
	  }
	  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $nome,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($footer).'</span>',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="l_contactos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>