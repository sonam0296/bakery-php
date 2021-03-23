<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);
  /* 
   * Paging
   */
   
   
   $fixo = $_GET['fixo'];
   
   // actualiza estado dos registos selecionados
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

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$row_rsLinguas = $rsLinguas->fetchAll();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		
		if($opcao == '-1') { // elimina utilizadores selecionados
			foreach($row_rsLinguas as $linguas) {
				$query_rsProc = "SELECT id FROM paginas_blocos_".$linguas['sufixo']." WHERE pagina IN $lista";
				$rsProc = DB::getInstance()->prepare($query_rsProc);
				$rsProc->execute();
				$totalRows_rsProc = $rsProc->rowCount();
				
				if($totalRows_rsProc){
					while($row_rsProc = $rsProc->fetch()) {

						//Elimina na tabela da timeline
						$query_rsImg = "SELECT imagem1 FROM paginas_blocos_timeline_".$linguas["sufixo"]." WHERE bloco=:id ORDER BY ordem ASC, id DESC";
						$rsImg = DB::getInstance()->prepare($query_rsImg);
						$rsImg->bindParam(':id', $row_rsProc['id'], PDO::PARAM_INT, 5);
						$rsImg->execute();
						$totalRows_rsImg = $rsImg->rowCount();
						
						if($totalRows_rsImg){
							while($row_rsImg = $rsImg->fetch()) {
								if ($row_rsImg['imagem1']!='') {
									@unlink('../../../imgs/paginas/'.$row_rsImg['imagem1']);
								}
							}
							
							$insertSQL = "DELETE FROM paginas_blocos_timeline_".$linguas["sufixo"]." WHERE bloco=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':id', $row_rsProc['id'], PDO::PARAM_INT, 5);
							$rsInsert->execute();
						}

						// Elimina os ficheiros e os respetivos registos
						$query_rsImg = "SELECT ficheiro FROM paginas_blocos_ficheiros_".$linguas["sufixo"]." WHERE bloco=:id ORDER BY ordem ASC, id DESC";
						$rsImg = DB::getInstance()->prepare($query_rsImg);
						$rsImg->bindParam(':id', $row_rsProc['id'], PDO::PARAM_INT, 5);
						$rsImg->execute();
						$totalRows_rsImg = $rsImg->rowCount();
						
						if($totalRows_rsImg){
							while($row_rsImg = $rsImg->fetch()) {
								if ($row_rsImg['ficheiro']!='') {
									@unlink('../../../imgs/paginas/'.$row_rsImg['ficheiro']);
								}
							}
							
							$insertSQL = "DELETE FROM paginas_blocos_ficheiros_".$linguas["sufixo"]." WHERE bloco=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':id', $row_rsProc['id'], PDO::PARAM_INT, 5);
							$rsInsert->execute();
						}

						//Elimina as imagens do bloco
						$query_rsImg = "SELECT imagem1 FROM paginas_blocos_imgs WHERE bloco=:id ORDER BY ordem ASC, id DESC";
						$rsImg = DB::getInstance()->prepare($query_rsImg);
						$rsImg->bindParam(':id', $row_rsProc['id'], PDO::PARAM_INT, 5);
						$rsImg->execute();
						$totalRows_rsImg = $rsImg->rowCount();
						
						if($totalRows_rsImg){
							while($row_rsImg = $rsImg->fetch()) {
								if ($row_rsImg['imagem1']!='') {
									@unlink('../../../imgs/paginas/'.$row_rsImg['imagem1']);
								}
							}
							
							$insertSQL = "DELETE FROM paginas_blocos_imgs WHERE bloco=:id";
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->bindParam(':id', $row_rsProc['id'], PDO::PARAM_INT, 5);
							$rsInsert->execute();
						}
					}
				}

				//Elimina os blocos associados à página
				$insertSQL = "DELETE FROM paginas_blocos_".$linguas["sufixo"]." WHERE pagina IN $lista";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->execute();
							
				$query_rsDel = "DELETE FROM paginas_".$linguas["sufixo"]." WHERE id IN $lista";
				$rsDel = DB::getInstance()->prepare($query_rsDel);
				$rsDel->execute();							
			}

			include_once("../funcoes.php");
			alteraSessions('paginas');
			alteraSessions('paginas_menu');
			alteraSessions('paginas_fixas');
		} 
		else if($opcao == 3 || $opcao == 4) { // colocar todas as notícias visíveis
			foreach($row_rsLinguas as $linguas) {
				if($opcao == 3) $query_rsUpd = "UPDATE paginas_".$linguas["sufixo"]." SET visivel = '1' WHERE id IN $lista";
				else $query_rsUpd = "UPDATE paginas_".$linguas["sufixo"]." SET visivel = '0' WHERE id IN $lista";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();
			}

			include_once("../funcoes.php");
			alteraSessions('paginas');
			alteraSessions('paginas_menu');
			alteraSessions('paginas_fixas');
		}
	}
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'nome', 'ordem', 'visivel', '');
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
		$pesq_visivel = $_REQUEST['form_visivel'];
		
		if($pesq_form != "") $where_pesq .= " AND (nome LIKE '%$pesq_form%')";
		if($pesq_visivel != "") $where_pesq .= " AND visivel = '$pesq_visivel'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
    
  $query_rsTotal = "SELECT * FROM paginas_$lingua_consola WHERE fixo='$fixo' AND id > '0'".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT * FROM paginas_$lingua_consola WHERE fixo='$fixo' AND id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
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
	  $nome=utf8_encode($row_rsTotal['nome']);
		$fixo = $row_rsTotal['fixo'];  
	  
	  //verifica se está bloqueado
	  if($fixo==1) {
		  $input='';
	  }else{
		  $input='<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">';
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
	  $input,
	  $nome,
	  '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',
	  '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
	  '<a href="paginas-edit.php?id='.$id.'&fixo='.$fixo.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',
    );
	  
	  $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>