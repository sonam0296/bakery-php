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
  	
  	if($opcao == 3 || $opcao == 4) { // colocar todas as notícias visíveis
      if($opcao == 3) $query_rsUpd = "UPDATE paises SET visivel = '1' WHERE id IN $lista";
      else $query_rsUpd = "UPDATE paises SET visivel = '0' WHERE id IN $lista";
      $rsUpd = DB::getInstance()->query($query_rsUpd);
      $rsUpd->execute();

      include_once("../funcoes.php");
      alteraSessions('paises');
    }	
  }
  
  // ordenação
  $sOrder = " ORDER BY nome ASC";
  $colunas = array( 'id', 'nome', 'zona', 'visivel', '');
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
  	$pesq_zona= utf8_decode($_REQUEST['form_zona']);
    $pesq_visivel = $_REQUEST['form_visivel'];
  	
  	if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
  	if($pesq_zona != "") $where_pesq .= " AND zona = '$pesq_zona'";
    if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM paises WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM paises WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
    $nome = utf8_encode($row_rsTotal['nome']);	
    $zona = utf8_encode($row_rsTotal['zona']);	
	  
	  $query_rsZona = "SELECT nome FROM zonas WHERE id = :zona";
	  $rsZona = DB::getInstance()->prepare($query_rsZona);
    $rsZona->bindParam(':zona', $zona, PDO::PARAM_INT);
	  $rsZona->execute();
	  $row_rsZona = $rsZona->fetch(PDO::FETCH_ASSOC);

    //visivel
    if($row_rsTotal['visivel'] == 1) {
      $visivel = "Sim";
      $etiqueta2 = "success";
    } 
    else {
      $visivel = "Não";
      $etiqueta2 = "danger";
    }
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
	  utf8_encode($row_rsZona["nome"]),
    '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="p_paises-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>