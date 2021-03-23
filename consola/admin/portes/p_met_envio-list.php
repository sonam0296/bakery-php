<?php include_once('../inc_pages.php'); ?>

<?php

  /* 

   * Paging

   */

   

  // actualiza estado dos registos selecionados

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

		

		if($opcao == 1 || $opcao == 2) {// colocar todos os banners visíveis

			$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

	    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

	    $rsLinguas->execute();

	    $totalRows_rsLinguas = $rsLinguas->rowCount();

			

			while($row_rsLinguas = $rsLinguas->fetch()) {

				if($opcao == 1) $query_rsUpd = "UPDATE met_envio_".$row_rsLinguas["sufixo"]." SET visivel_footer = '1' WHERE id IN $lista";

				else $query_rsUpd = "UPDATE met_envio_".$row_rsLinguas["sufixo"]." SET visivel_footer = '0' WHERE id IN $lista";

				$rsUpd = DB::getInstance()->prepare($query_rsUpd);

				$rsUpd->execute();

			}

		} 


  }

  

  // ordenação

  $sOrder = " ORDER BY ordem ASC";

  $colunas = array( 'id', 'nome', '', 'ordem', 'visivel_footer', '');

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

		$pesq_nome = utf8_decode($_REQUEST['form_nome']);

		$pesq_visivel = $_REQUEST['form_visivel'];

		

		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";

		if($pesq_visivel != "") $where_pesq .= " AND visivel_footer = '$pesq_visivel'";

  }

  

  $iDisplayLength = intval($_REQUEST['length']);

  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 

  $iDisplayStart = intval($_REQUEST['start']);

  $sEcho = intval($_REQUEST['draw']);

  

  $query_rsTotal = "SELECT * FROM met_envio_en WHERE id > '0'".$where_pesq.$sOrder;

  $rsTotal = DB::getInstance()->prepare($query_rsTotal);

  $rsTotal->execute();

  $totalRows_rsTotal = $rsTotal->rowCount();

  

  $iTotalRecords = $totalRows_rsTotal;

  

  $query_rsTotal = "SELECT * FROM met_envio_en WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";

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

	  

	  $imagem = '';

	  if($row_rsTotal['imagem'] && file_exists("../../../imgs/carrinho/".$row_rsTotal['imagem'])) $imagem = '<a href="../../../imgs/carrinho/'.utf8_encode($row_rsTotal['imagem']).'" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/carrinho/'.utf8_encode($row_rsTotal['imagem']).'" height="70" /></a>';

	  	

  	if($row_rsTotal['visivel_footer'] == 1) {

		  $visivel = $RecursosCons->RecursosCons['text_visivel_sim'];

		  $etiqueta = "success";

	  } 

	  else {

		  $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];

		  $etiqueta = "danger";

	  }



    $records["data"][] = array(

	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',

	  $nome,

	  $imagem,

	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',

	  '<span class="label label-sm label-'.$etiqueta.'">'.utf8_encode($visivel).'</span>',

	  '<a href="p_met_envio-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',

    );

	  

	  $i++;

  }



  DB::close();



  $records["draw"] = $sEcho;

  $records["recordsTotal"] = $iTotalRecords;

  $records["recordsFiltered"] = $iTotalRecords;

  

  echo json_encode($records);

?>