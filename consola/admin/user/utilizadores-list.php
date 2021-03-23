<?php include_once('../../../Connections/connADMIN.php'); ?>
<?php
  /* 
   * Paging
   */

  //var_dump($_POST["selected"]);

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
	
	if($opcao == 0 || $opcao == 1) { // torna utilizadores selecionados activos ou inactivos
		$query_rsUpd = "UPDATE acesso SET activo = '$opcao' WHERE id IN $lista";
		$rsUpd = DB::getInstance()->query($query_rsUpd);
		$rsUpd->execute();
		DB::close();
	} else { // elimina utilizadores selecionados
		$query_rsDel = "DELETE FROM acesso WHERE id IN $lista";
		$rsDel = DB::getInstance()->query($query_rsDel);
		$rsDel->execute();
		DB::close();
	}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'id', 'nome', '', 'activo', '');
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
	$pesq_id = $_REQUEST['form_id'];
	$pesq_nome = utf8_decode($_REQUEST['form_nome']);
	$pesq_activo = $_REQUEST['form_activo'];
	
	if($pesq_id != "") $where_pesq .= " AND id = '$pesq_id'";
	if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";
	if($pesq_activo != "") $where_pesq .= " AND activo = '$pesq_activo'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM acesso WHERE super_administrador = '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM acesso WHERE super_administrador = '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
      
	  if($row_rsTotal['imagem1'] && file_exists("../../imgs/user/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../imgs/user/'.utf8_encode($row_rsTotal['imagem1']).'" class="fancybox-button" data-rel="fancybox-button"><img src="../../imgs/user/'.utf8_encode($row_rsTotal['imagem1']).'" height="70" /></a>';
	  else $imagem = '';
	  
	  if($row_rsTotal['activo'] == 1) {
		  $activo = "Activo";
		  $etiqueta = "success";
	  } else {
		  $activo = "Inactivo";
		  $etiqueta = "danger";
	  }
	  
      $records["data"][] = array(
		  '<input type="checkbox" name="id[]" value="'.$id.'">',
		  $id,
		  $nome,
		  $imagem,
		  '<span class="label label-sm label-'.$etiqueta.'">'.$activo.'</span>',
		  '<a href="utilizadores-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>',
      );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>