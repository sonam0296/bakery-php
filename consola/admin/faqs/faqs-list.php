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

			foreach ($row_rsLinguas as $linguas) {

				if($opcao == 3) $query_rsUpd = "UPDATE faqs_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";

				else $query_rsUpd = "UPDATE faqs_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";

				$rsUpd = DB::getInstance()->prepare($query_rsUpd);

				$rsUpd->execute();

			}

		} 

		else if($opcao == '-1') { // elimina as notícias selecionadas

			foreach ($row_rsLinguas as $linguas) {

				$query_rsDel = "DELETE FROM faqs_".$linguas["sufixo"]." WHERE id IN $lista";

				$rsDel = DB::getInstance()->prepare($query_rsDel);	

				$rsDel->execute();

			}

		}

		include_once("../funcoes.php");

		alteraSessions("faqs");

  }

  

  // ordenação

  $sOrder = " ORDER BY ordem ASC, data DESC";

  $colunas = array( '', 'pergunta', 'categoria', 'ordem', 'visivel', '');

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

		$pesq_pergunta = utf8_decode($_REQUEST['form_pergunta']);

		$pesq_visivel = $_REQUEST['form_visivel'];

		$pesq_categoria = $_REQUEST['form_categoria'];

		if($pesq_pergunta != "") $where_pesq .= " AND (pergunta = '$pesq_pergunta' OR pergunta LIKE '%$pesq_pergunta%')";

		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";

		if($pesq_categoria != "") $where_pesq .= " AND categoria = '$pesq_categoria'";

	}

  

  $iDisplayLength = intval($_REQUEST['length']);

  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 

  $iDisplayStart = intval($_REQUEST['start']);

  $sEcho = intval($_REQUEST['draw']);

  

  $query_rsTotal = "SELECT * FROM faqs_en WHERE id > '0'".$where_pesq.$sOrder;

  $rsTotal = DB::getInstance()->prepare($query_rsTotal);

  $rsTotal->execute();

  $totalRows_rsTotal = $rsTotal->rowCount();

  

  $iTotalRecords = $totalRows_rsTotal;

  

  $query_rsTotal = "SELECT * FROM faqs_en WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";

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

    $pergunta = utf8_encode($row_rsTotal['pergunta']);

    $categoria = $row_rsTotal['categoria'];

		

		$query_rsCat = "SELECT nome FROM faqs_categorias_en WHERE id =:categoria";

		$rsCat = DB::getInstance()->prepare($query_rsCat);

		$rsCat->bindParam(':categoria', $categoria, PDO::PARAM_INT);

		$rsCat->execute();

		$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsCat = $rsCat->rowCount();



		$categoria = '-';

		if($totalRows_rsCat > 0)

			$categoria = utf8_encode($row_rsCat["nome"]);

		if($row_rsTotal['visivel'] == 1) {

		  $visivel = $RecursosCons->RecursosCons['text_visivel_sim'];

		  $etiqueta2 = "success";

	  } else {

		  $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];

		  $etiqueta2 = "danger";

	  }

	  

    $records["data"][] = array(

	  '<input type="checkbox" name="id[]" value="'.$id.'">',

	  $pergunta,

	  $categoria,

		'<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',

	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',

	  '<a href="faqs-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',

    );

	  

	  $i++;

  }



  DB::close();



  $records["draw"] = $sEcho;

  $records["recordsTotal"] = $iTotalRecords;

  $records["recordsFiltered"] = $iTotalRecords;

  

  echo json_encode($records);

?>

