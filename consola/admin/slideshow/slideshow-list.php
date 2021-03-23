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

			foreach($row_rsLinguas as $linguas) {

				if($opcao == 1) $query_rsUpd = "UPDATE banners_h_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";

				else $query_rsUpd = "UPDATE banners_h_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";

				$rsUpd = DB::getInstance()->prepare($query_rsUpd);

				$rsUpd->execute();

			}



			include_once("../funcoes.php");

			alteraSessions('banners');

		} 

		else if($opcao == '-1') { // elimina os banners selecionados

			foreach($row_rsLinguas as $linguas) {

				$query_rsP = "SELECT imagem1, imagem2, imagem3 FROM banners_h_".$linguas["sufixo"]." WHERE id IN $lista";

				$rsP = DB::getInstance()->prepare($query_rsP);

				$rsP->execute();

				$totalRows_rsP = $rsP->rowCount();

				

				while($row_rsP = $rsP->fetch()) {

					@unlink("../../../imgs/banners/".$row_rsP['imagem1']);

					@unlink("../../../imgs/banners/".$row_rsP['imagem2']);

					@unlink("../../../imgs/banners/".$row_rsP['imagem3']);

				}

			

				$query_rsDel = "DELETE FROM banners_h_".$linguas["sufixo"]." WHERE id IN $lista";

				$rsDel = DB::getInstance()->prepare($query_rsDel);

				$rsDel->execute();

			}



			include_once("../funcoes.php");

			alteraSessions('banners');

		}

  }

  

  // ordenação

  $sOrder = " ORDER BY ordem ASC";

  $colunas = array( 'id', 'nome', 'imagem1', 'datai', 'dataf', 'ordem', 'visivel', '');

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

		$pesq_inicio = $_REQUEST['form_inicio'];

		$pesq_fim = $_REQUEST['form_fim'];

		$pesq_visivel = $_REQUEST['form_visivel'];

		

		if($pesq_nome != "") $where_pesq .= " AND (nome = '$pesq_nome' OR nome LIKE '%$pesq_nome%')";

		if($pesq_inicio != "") $where_pesq .= " AND datai LIKE '$pesq_inicio%'";

		if($pesq_fim != "") $where_pesq .= " AND dataf LIKE '$pesq_fim%'";

		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";

  }

  

  $iDisplayLength = intval($_REQUEST['length']);

  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 

  $iDisplayStart = intval($_REQUEST['start']);

  $sEcho = intval($_REQUEST['draw']);

  

  $query_rsTotal = "SELECT id, nome, tipo, imagem1, imagem2, video, datai, dataf, ordem, visivel FROM banners_h_en WHERE id > '0'".$where_pesq.$sOrder;

  $rsTotal = DB::getInstance()->prepare($query_rsTotal);

  $rsTotal->execute();

  $totalRows_rsTotal = $rsTotal->rowCount();

  

  $iTotalRecords = $totalRows_rsTotal;

  

  $query_rsTotal = "SELECT id, nome, tipo, imagem1, imagem2, video, datai, dataf, ordem, visivel FROM banners_h_en WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";

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

     

    if($row_rsTotal['tipo'] == 1) {

    	$ext = strtolower(pathinfo($row_rsTotal['imagem1'], PATHINFO_EXTENSION));

    	if($ext != 'mp4') {

			  if($row_rsTotal['imagem1'] && file_exists("../../../imgs/banners/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/banners/'.utf8_encode($row_rsTotal['imagem1']).'" data-fancybox="gallery" data-caption="'.utf8_encode($row_rsTotal['nome']).'" ><img src="../../../imgs/banners/'.utf8_encode($row_rsTotal['imagem1']).'" width="100%" style="max-width:120px;" /></a>';

			  elseif($row_rsTotal['imagem2'] && file_exists("../../../imgs/banners/".$row_rsTotal['imagem2'])) $imagem = '<a href="../../../imgs/banners/'.utf8_encode($row_rsTotal['imagem2']).'" data-fancybox="gallery" data-caption="'.utf8_encode($row_rsTotal['nome']).'" ><img src="../../../imgs/banners/'.utf8_encode($row_rsTotal['imagem2']).'" width="100%" style="max-width:120px;" /></a>'; 

			  else $imagem = '';

		  }

		  else {

		  	$imagem = '<a data-fancybox="gallery" href="../../../imgs/banners/'.utf8_encode($row_rsTotal['imagem1']).'" class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"> '. utf8_encode($RecursosCons->RecursosCons['ver_video']).'</span></a>';

		  }

		}

		else {

			$imagem = '<a data-fancybox="gallery" href="'.utf8_encode($row_rsTotal['video']).'" class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"> '. utf8_encode($RecursosCons->RecursosCons['ver_video']).'</a>';

		}

	  

	  $datai = $row_rsTotal['datai'];

	  $dataf = $row_rsTotal['dataf'];

	  

	  if($row_rsTotal['visivel'] == 1) {

			$checked = "checked";

		} else {

			$checked = "";

		}

	  

    $records["data"][] = array(

	  '<input type="checkbox" name="id[]" value="'.$id.'">',

	  $nome,

	  $imagem,

	  $datai,

	  $dataf,

	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',

	  '<input type="checkbox" '.$checked.' name="switch_visivel" id="switch_visivel_'.$id.'" class="make-switch" data-on-color="success" data-on-text="'.utf8_encode($RecursosCons->RecursosCons['text_visivel_sim']).'" data-off-color="danger" data-off-text="'.utf8_encode($RecursosCons->RecursosCons['text_visivel_nao']).'" data-off-value="0" data-on-value="1">',

	  '<a href="slideshow-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',

    );

	  

	  $i++;

  }



  DB::close();



  $records["draw"] = $sEcho;

  $records["recordsTotal"] = $iTotalRecords;

  $records["recordsFiltered"] = $iTotalRecords;

  

  echo json_encode($records);

?>