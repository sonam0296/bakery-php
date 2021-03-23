<?php include_once('../../../Connections/connADMIN.php'); ?>
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
		
		if($opcao == '-1') { // elimina os banners selecionados
			$query_rsDel = "UPDATE news_conteudo SET topo = 0 WHERE topo IN $lista";
      $rsDel = DB::getInstance()->prepare($query_rsDel);
      $rsDel->execute();
      DB::close();

      $query_rsP = "SELECT imagem1 FROM news_topos WHERE id IN $lista";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
			$totalRows_rsP = $rsP->rowCount();
			DB::close();
			
			while($row_rsP = $rsP->fetch()) {
				@unlink("../../../imgs/imgs_news/".$row_rsP['imagem1']);
			}
			
			$query_rsDel = "DELETE FROM news_topos WHERE id IN $lista";
			$rsDel = DB::getInstance()->prepare($query_rsDel);
			$rsDel->execute();
			DB::close();
		}
  }
  
  // ordenação
  $sOrder = " ORDER BY id ASC";
  $colunas = array( '', 'nome', 'imagem1', '');
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
		
		if($pesq_nome != "") $where_pesq .= " AND nome LIKE '%$pesq_nome%'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM news_topos WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM news_topos WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
      
	  if($row_rsTotal['imagem1'] && file_exists("../../../imgs/imgs_news/".$row_rsTotal['imagem1'])) $imagem = '<a href="../../../imgs/imgs_news/'.utf8_encode($row_rsTotal['imagem1']).'" data-fancybox="gallery"><img src="../../../imgs/imgs_news/'.utf8_encode($row_rsTotal['imagem1']).'" width="100%" style="max-width:120px;" /></a>';
	  else $imagem = '';
	  
    $records["data"][] = array(
	  '<input type="checkbox" name="id[]" value="'.$id.'">',
	  $nome,
	  $imagem,
	  '<a href="topos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>',
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>