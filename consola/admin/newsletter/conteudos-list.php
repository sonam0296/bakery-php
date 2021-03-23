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
  	
  	if($opcao == '-1') { // elimina listas selecionadas
  		$query_rsTema = "SELECT * FROM news_temas WHERE conteudo IN $lista";
      $rsTema = DB::getInstance()->prepare($query_rsTema);
      $rsTema->execute();
      $totalRows_rsTema = $rsTema->rowCount();
      DB::close();

      if($totalRows_rsTema > 0) {
        while($row_rsTema = $rsTema->fetch()) {
          $query_rsProdutos = "SELECT * FROM news_produtos WHERE id_tema=:id";
          $rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
          $rsProdutos->bindParam(':id', $row_rsTema['id'], PDO::PARAM_INT);
          $rsProdutos->execute();
          $totalRows_rsProdutos = $rsProdutos->rowCount();
          DB::close();

          if($totalRows_rsProdutos > 0) {
            while($row_rsProdutos = $rsProdutos->fetch()) {
              @unlink('../../../imgs/imgs_news/produtos/'.$row_rsProdutos['imagem1']);
              @unlink('../../../imgs/imgs_news/produtos/'.$row_rsProdutos['imagem2']);
            }

            $query_rsDelete = "DELETE FROM news_produtos WHERE id_tema=:id";
            $rsDelete = DB::getInstance()->prepare($query_rsDelete);
            $rsDelete->bindParam(':id', $row_rsTema['id'], PDO::PARAM_INT);
            $rsDelete->execute();
            DB::close();
          }
        }

        $query_rsDelete = "DELETE FROM news_temas WHERE conteudo IN $lista";
        $rsDelete = DB::getInstance()->prepare($query_rsDelete);
        $rsDelete->execute();
        DB::close();
      }
      
      $query_rsDelete = "DELETE FROM news_conteudo WHERE id IN $lista";
      $rsDelete = DB::getInstance()->prepare($query_rsDelete);
      $rsDelete->execute();
      DB::close();
      
      $query_rsUpdate = "UPDATE newsletters SET conteudo = 0 WHERE conteudo IN $lista";
      $rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
      $rsUpdate->execute();
      DB::close();
  	}
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array('id', 'nome', '');
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
  	$pesq_form= utf8_decode($_REQUEST['form_nome']);
    // $pesq_topo= $_REQUEST['form_topo'];
  	
  	if($pesq_form != "") $where_pesq .= " AND nome LIKE '%$pesq_form%'";
    // if($pesq_topo != "") $where_pesq .= " AND topo = '$pesq_topo'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT * FROM news_conteudo WHERE id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM news_conteudo WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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

    // $topo = '---';
    // if($row_rsTotal['topo'] > 0) {
    //   $query_rsTopo = "SELECT nome FROM news_topos WHERE id = '".$row_rsTotal['topo']."'";
    //   $rsTopo = DB::getInstance()->query($query_rsTopo);
    //   $rsTopo->execute();
    //   $totalRows_rsTopo = $rsTopo->rowCount();
    //   $row_rsTopo = $rsTopo->fetch(PDO::FETCH_ASSOC);
    //   DB::close();

    //   if($totalRows_rsTopo > 0) {
    //     $topo = utf8_encode($row_rsTopo['nome']);
    //   }
    // }
	  
    $records["data"][] = array(
	  '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
	  $nome,
    // $topo,
	  '<a href="conteudos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>',
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>