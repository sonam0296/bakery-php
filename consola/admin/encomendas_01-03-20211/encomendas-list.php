<?php include_once('../inc_pages.php');

include_once('../funcoes.php');?>

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

		

		if($opcao == '-1') { // elimina utilizadores selecionados

			$query_deleteSQL = "DELETE FROM encomendas WHERE id IN $lista";

			$deleteSQL = DB::getInstance()->prepare($query_deleteSQL);

			$deleteSQL->execute();

			

			$query_deleteSQL = "DELETE FROM encomendas_produtos WHERE id_encomenda IN $lista";

			$deleteSQL = DB::getInstance()->prepare($query_deleteSQL);

			$deleteSQL->execute();

		}

  }

  

  // ordenação  

  $sOrder = " ORDER BY id DESC";

  $colunas = array('numero', 'data', 'nome', 'valor_c_iva', 'store_name', 'met_pagamt_id', 'estado', '');

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

		$pesq_num= utf8_decode($_REQUEST['form_num']);

		$pesq_data= utf8_decode($_REQUEST['form_data']);

		$pesq_data2= utf8_decode($_REQUEST['form_data2']);

		$pesq_nome= utf8_decode($_REQUEST['form_nome']);

		$pesq_valor= utf8_decode($_REQUEST['form_valor']);

		$pesq_estado= utf8_decode($_REQUEST['form_estado']);

		$pesq_pagamento= utf8_decode($_REQUEST['form_pagamento']);

		$pesq_store= utf8_decode($_REQUEST['form_Store']);

		

		if($pesq_num != "") $where_pesq .= " AND numero = '$pesq_num'";

		if($pesq_data != "") $where_pesq .= " AND data >= '".$pesq_data." 00:00:00'";

		if($pesq_data2 != "") $where_pesq .= " AND data <= '".$pesq_data2." 23:59:59'";

		if($pesq_nome != "") $where_pesq .= " AND (email LIKE '%$pesq_nome%' OR nome LIKE '%$pesq_nome%')";

		if($pesq_valor != "") $where_pesq .= " AND valor_c_iva LIKE '%$pesq_valor%'";

		if($pesq_estado != "") $where_pesq .= " AND estado = '$pesq_estado'";

		if($pesq_pagamento != "") $where_pesq .= " AND met_pagamt_id = '$pesq_pagamento'";

		if($pesq_store != "") $where_pesq .= " AND store_name = '$pesq_store'";

  }

  

  $iDisplayLength = intval($_REQUEST['length']);

  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 

  $iDisplayStart = intval($_REQUEST['start']);

  $sEcho = intval($_REQUEST['draw']);

  

  $query_rsTotal = "SELECT * FROM encomendas WHERE txn_id != '' OR met_pagamt_id = 9 AND id > '0'".$where_pesq.$sOrder;

  $rsTotal = DB::getInstance()->prepare($query_rsTotal);

  $rsTotal->execute();

  $totalRows_rsTotal = $rsTotal->rowCount();

  

  $iTotalRecords = $totalRows_rsTotal;

  

  $query_rsTotal = "SELECT * FROM encomendas WHERE txn_id != '' OR met_pagamt_id = 9 AND id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";

  $rsTotal = DB::getInstance()->prepare($query_rsTotal);

  $rsTotal->execute();

  $totalRows_rsTotal = $rsTotal->rowCount();

  

  $records = array();

  $records["data"] = array(); 



  $end = $iDisplayStart + $iDisplayLength;

  $end = $end > $iTotalRecords ? $iTotalRecords : $end;

  

  $i = $iDisplayStart;



  $count = 1;

  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {

    $id = $row_rsTotal['id'];

    $numero = utf8_encode($row_rsTotal['numero']);	

    $data = utf8_encode($row_rsTotal['data']);	

    $nome = utf8_encode($row_rsTotal['nome']);	

    $valor = utf8_encode($row_rsTotal['valor_c_iva']);	

    $estado = utf8_encode($row_rsTotal['estado']);	

    $store_name = utf8_encode($row_rsTotal['store_name']);	

	  

	  $query_rsEstado = "SELECT * FROM enc_estados WHERE id = '$estado'";

	  $rsEstado = DB::getInstance()->prepare($query_rsEstado);

	  $rsEstado->execute();

	  $row_rsEstado = $rsEstado->fetch(PDO::FETCH_ASSOC);

	  $totalRows_rsEstado = $rsEstado->rowCount();

	  

	  if($estado == 1) {

		  $etiqueta = "info";

	  } elseif($estado == 2) {

		  $etiqueta = "primary";

	  } elseif($estado == 3 || $estado == 4) {

		  $etiqueta = "success";

	  } elseif($estado == 6) {

		  $etiqueta = "warning";

	  } elseif($estado == 7) {

		  $etiqueta = "success";

	  }

	  elseif($estado == 8) {

		  $etiqueta = "primary";

	  }

	   else {

		  $etiqueta = "danger";

	  }

	

	  $moeda = $row_rsTotal["moeda"];

	  

	  if(!$row_rsTotal["moeda"]) {

	    $ret = number_format(round($valor,2),2,",",".")."&pound;";

	  } elseif($row_rsTotal["moeda"] && $moeda != "&pound;") {

			$ret = $moeda.number_format(round($valor,2),2,"."," ");

	  } else {

			$ret = number_format(round($valor,2),2,",",".").$moeda;

	  }



	  if($row_rsTotal['nova'] == 1) {

	  	$nova = '<span style="margin-left: 20px" class="label label-sm label-warning">Nova</span>';

	  }

	  else {

	  	$nova = '';

	  }



	  $query_rsMetPagamento = "SELECT nome_interno FROM met_pagamento_en WHERE id=:id";

	  $rsMetPagamento = DB::getInstance()->prepare($query_rsMetPagamento);

	  $rsMetPagamento->bindParam(':id', $row_rsTotal['met_pagamt_id'], PDO::PARAM_INT);

	  $rsMetPagamento->execute();

	  $row_rsMetPagamento = $rsMetPagamento->fetch(PDO::FETCH_ASSOC);

	  $totalRows_rsMetPagamento = $rsMetPagamento->rowCount();



	  $met_pagamento = '';

	  if($totalRows_rsMetPagamento > 0) 

	  	$met_pagamento = utf8_encode($row_rsMetPagamento['nome_interno']);

	  

    $records["data"][] = array(

	  $count,

	  $data,

	  $nome." ".$nova,

	  $ret,

	  $store_name,

	  $met_pagamento,

	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($row_rsEstado["nome_en"]).'</span>',

	  '<a href="encomendas-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '.$RecursosCons->RecursosCons['btn_editar'].'</a>',

    );

	  

	  $i++;

	  $count++;

  }



  DB::close();



  $records["draw"] = $sEcho;

  $records["recordsTotal"] = $iTotalRecords;

  $records["recordsFiltered"] = $iTotalRecords;

  

  echo json_encode($records);

?>