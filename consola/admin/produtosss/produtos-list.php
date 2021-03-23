<?php include_once('../inc_pages.php'); ?>

<?php

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

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $row_rsLinguas = $rsLinguas->fetchAll();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
  
    if($opcao == '-1') { // elimina utilizadores selecionados
      $query_rsProc = "SELECT imagem1, imagem2, imagem3, imagem4 FROM l_pecas_imagens WHERE id_peca IN $lista";
      $rsProc = DB::getInstance()->prepare($query_rsProc);
      $rsProc->execute();
      $totalRows_rsProc = $rsProc->rowCount();
      
      if($totalRows_rsProc > 0) {
        while($row_rsProc = $rsProc->fetch()) {
          @unlink('../../../imgs/produtos/'.$row_rsProc['imagem1']);
          @unlink('../../../imgs/produtos/'.$row_rsProc['imagem2']);
          @unlink('../../../imgs/produtos/'.$row_rsProc['imagem3']);
          @unlink('../../../imgs/produtos/'.$row_rsProc['imagem4']);
        }
      }
      
      $query_rsP = "DELETE FROM l_pecas_imagens WHERE id_peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
      
      $query_rsP = "DELETE FROM l_pecas_categorias WHERE id_peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
    
      $query_rsP = "DELETE FROM l_pecas_caract WHERE id_peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
      
      $query_rsP = "DELETE FROM l_pecas_filtros WHERE id_peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
    
      $query_rsP = "DELETE FROM l_pecas_desconto WHERE id_peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();

      $query_rsP = "DELETE FROM l_pecas_tamanhos WHERE peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
    
      $query_rsP = "DELETE FROM l_pecas_relacao WHERE id_peca IN $lista";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();

      foreach($row_rsLinguas as $linguas) {
        $query_rsDel = "DELETE FROM l_pecas_en WHERE id IN $lista";
        $rsDel = DB::getInstance()->query($query_rsDel);
        $rsDel->execute();   
      }
    } 
    else if($opcao == 3 || $opcao == 4) { // colocar todas as notícias visíveis
      foreach($row_rsLinguas as $linguas) {
        if($opcao == 3) $query_rsUpd = "UPDATE l_pecas_en SET visivel = '1' WHERE id IN $lista";
        else $query_rsUpd = "UPDATE l_pecas_en SET visivel = '0' WHERE id IN $lista";
        $rsUpd = DB::getInstance()->query($query_rsUpd);
        $rsUpd->execute();
      }
    } 
    else if($opcao == 5 || $opcao == 6) { // destaques
      foreach($row_rsLinguas as $linguas) {
        if($opcao == 5) $query_rsUpd = "UPDATE l_pecas_en SET destaque = '1' WHERE id IN $lista";
        else $query_rsUpd = "UPDATE l_pecas_en SET destaque = '0' WHERE id IN $lista";
        $rsUpd = DB::getInstance()->query($query_rsUpd);
        $rsUpd->execute();
      }
    }
  else if($opcao == 7 || $opcao == 8) { // novidade
      foreach($row_rsLinguas as $linguas) {
        if($opcao == 7) $query_rsUpd = "UPDATE l_pecas_en SET novidade = '1' WHERE id IN $lista";
        else $query_rsUpd = "UPDATE l_pecas_en SET novidade = '0' WHERE id IN $lista";
        $rsUpd = DB::getInstance()->query($query_rsUpd);
        $rsUpd->execute();
      }
    }
  }
  
  // ordenação
  $sOrder = " ORDER BY id DESC";
  $colunas = array( '', 'ref','descricao_stock','stock', 'nome', 'promocao', 'markup', 'cost', 'destaque', 'novidade', 'visivel', '');
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
  $left_join = "";

   
    //exit(); 
  
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
    $pesq_ref = $_REQUEST['form_ref'];
    $pesq_form= utf8_decode($_REQUEST['form_nome']);
    $stock = $_REQUEST['stock'];
    $form_stock = $_REQUEST['form_stock'];
    $pesq_destaque = $_REQUEST['form_destaque'];
  $pesq_novidade = $_REQUEST['form_novidade'];
    $pesq_visivel = $_REQUEST['form_visivel'];
    $pesq_categoria = $_REQUEST['form_categoria'];
    $pesq_marca = $_REQUEST['form_marca'];
    
    if($pesq_ref != "") $where_pesq .= " AND p.ref LIKE '%$pesq_ref%'";
    if($pesq_form != "") $where_pesq .= " AND p.nome LIKE '%$pesq_form%'";
    if($pesq_destaque != "") $where_pesq .= " AND p.destaque= '$pesq_destaque'";
    //pre($form_stock);
   
     if ($stock > $form_stock ) {
      if($stock != "") $where_pesq .= " AND p.stock > p.descricao_stock";
    }else{
      if($stock != "") $where_pesq .= " AND p.stock <= p.descricao_stock";
    }

    if ($form_stock > 9 ) {
      if($form_stock != "") $where_pesq .= " AND p.descricao_stock > $form_stock";
    }else{
      if($form_stock != "") $where_pesq .= " AND p.descricao_stock <= $form_stock";
    }
    
  if($pesq_novidade != "") $where_pesq .= " AND p.novidade= '$pesq_novidade'";
    if($pesq_visivel != "") $where_pesq .= " AND p.visivel = '$pesq_visivel'";
    if($pesq_categoria != "") {
      if($pesq_categoria == 0) {
        if(CATEGORIAS == 1){
          $where_pesq .= " AND (p.categoria = 0";
        }
        else if(CATEGORIAS == 2){
          $where_pesq .= " AND (pc.id_categoria = 0";
        }
      }
      else {
        if(CATEGORIAS == 1){
          $where_pesq .= " AND (p.categoria= '$pesq_categoria' OR c1.cat_mae = '$pesq_categoria'";
        }
        else if(CATEGORIAS == 2){
          $where_pesq .= " AND (pc.id_categoria = '$pesq_categoria' OR c1.cat_mae = '$pesq_categoria'";
        }

        if(CATEGORIAS_NIVEL > 2){
          for($i = 2; $i < CATEGORIAS_NIVEL; $i++) {          
            $where_pesq .= " OR c".$i.".cat_mae = '$pesq_categoria'";
          }
        }
      }
      $where_pesq .=')';
    }
    if($pesq_marca != "") {
      if($pesq_marca == 0) {
        $where_pesq .= " AND p.marca = 0";
      } 
      else {
        $where_pesq .= " AND p.marca = '$pesq_marca'";
      }
    }
  }

  if(CATEGORIAS_NIVEL > 2){
    for($i = 2; $i < CATEGORIAS_NIVEL; $i++) {
      $left_join .= " LEFT JOIN l_categorias_en c".$i." ON c".$i.".id = c".($i-1).".cat_mae";
    }
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  if(CATEGORIAS == 1){
    $query_rsTotal = "SELECT p.*, m.nome as m_nome FROM l_pecas_en p LEFT JOIN l_categorias_en c1 ON c1.id = p.categoria ".$left_join." LEFT JOIN l_marcas_pt m ON p.marca = m.id WHERE p.id > '0'".$where_pesq." GROUP BY p.id ".$sOrder;
  }
  else if(CATEGORIAS == 2){
    $query_rsTotal = "SELECT p.*, m.nome as m_nome FROM l_pecas_en p LEFT JOIN l_pecas_categorias pc ON p.id = pc.id_peca LEFT JOIN l_categorias_en c1 ON c1.id = pc.id_categoria ".$left_join." LEFT JOIN l_marcas_pt m ON p.marca = m.id WHERE p.id > '0'".$where_pesq." GROUP BY p.id ".$sOrder;
  }

  //echo $query_rsTotal;
  //exit(); 
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  if(CATEGORIAS == 1){
    $query_rsTotal = "SELECT p.*, m.nome as m_nome FROM l_pecas_en p LEFT JOIN l_categorias_en c1 ON c1.id = p.categoria ".$left_join." LEFT JOIN l_marcas_pt m ON p.marca = m.id WHERE p.id > '0'".$where_pesq." GROUP BY p.id ".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  }
  else if(CATEGORIAS == 2){
    $query_rsTotal = "SELECT p.*, m.nome as m_nome FROM l_pecas_en p LEFT JOIN l_pecas_categorias pc ON p.id = pc.id_peca LEFT JOIN l_categorias_en c1 ON c1.id = pc.id_categoria ".$left_join." LEFT JOIN l_marcas_pt m ON p.marca = m.id WHERE p.id > '0'".$where_pesq." GROUP BY p.id ".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  }
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
    $ref = $row_rsTotal['ref'];
    $stock = $row_rsTotal['stock'];
    $price = $row_rsTotal['preco'];
    $imagem1 = $row_rsTotal['imagem1']; 
    $descricao_stock = $row_rsTotal['descricao_stock'];
    $nome = utf8_encode($row_rsTotal['nome']);
    $url = utf8_encode($row_rsTotal['url']);
    $marca = utf8_encode($row_rsTotal['m_nome']);

    if($imagem1 == "")
    {
      $image_front = "../../../imgs/elem/geral.svg";
    }
    else
    {
      $image_front = '../../../imgs/produtos/'.$imagem1.'';
    }

    if($row_rsTotal['stock'] <= $descricao_stock || $row_rsTotal['stock'] == 0 ) {
      $visivel_min_stock = $stock;
      if($stock == "" || $stock == 0)
      {
         $visivel_min = 0;
      }
      $etiqueta_min_stock = 'style="background-color: red;"';
    } 
    else{ 
      
       $visivel_min_stock = $stock;
      $etiqueta_min_stock = "success";
      
    }



     if($row_rsTotal['descricao_stock'] <= 9) {
      $visivel_min =   $descricao_stock;  
      if($descricao_stock == "")
      {
         $visivel_min = 0;
      }
      $etiqueta_min = "danger";
    } 
    else{ 
       $visivel_min = $descricao_stock;
       $etiqueta_min = "success";
      
    }

    //destaque
    if($row_rsTotal['destaque'] == 1) {
      $visivel_dest = $RecursosCons->RecursosCons['text_visivel_sim'];
      $etiqueta2_dest = "success";
    } 
    else {
      $visivel_dest = $RecursosCons->RecursosCons['text_visivel_nao'];
      $etiqueta2_dest = "danger";
    }
  
  //novidade
    if($row_rsTotal['novidade'] == 1) {
      $visivel_novi = $RecursosCons->RecursosCons['text_visivel_sim'];
      $etiqueta2_novi = "success";
    } 
    else {
      $visivel_novi = $RecursosCons->RecursosCons['text_visivel_nao'];
      $etiqueta2_novi = "danger";
    }

    //visivel
    if($row_rsTotal['visivel'] == 1) {
      $visivel =$RecursosCons->RecursosCons['text_visivel_sim'];
      $etiqueta2 = "success";
    } 
    else {
      $visivel = $RecursosCons->RecursosCons['text_visivel_nao'];
      $etiqueta2 = "danger";
    }
  
    
    $promo_val=""; if($row_rsTotal['promocao']>0) $promo_val=$row_rsTotal['promocao_desconto']; 
    
    $records["data"][] = array(
    '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',
    '<img src="'.$image_front.'" width="50px" height="50px" />',
    $ref,
    $nome,
    '<span class="price_value">'.$price.'</span>',
    '<input type="text" '.$etiqueta_min_stock.' id="stock_'.$id.'" name="stock_'.$id.'" class="cx_ordenar" value="'.$visivel_min_stock.'" onKeyPress="alteraStock(event)">',
    '<span class="label label-sm label-'.$etiqueta_min.'">'.utf8_encode($visivel_min).'</span>',
   /* '<input type="text" id="discount_'.$id.'" name="discount_'.$id.'" class="cx_ordenar" value="'.$promo_val.'" onKeyPress="alteraOrdem(event)">',*/
    'MRKP  <input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar markup" value="'.$row_rsTotal['markup'].'" onKeyPress="alteraOrdem(event)"><br>
     COST  <input type="text" id="cost_'.$id.'" name="cost_'.$id.'" class="cx_ordenar cost" value="'.$row_rsTotal['cost'].'" onKeyPress="alteraCost(event)" style="margin: 3px 0px 0px 3px;">
    ',
   /* '<span class="label label-sm label-'.$etiqueta2_dest.'">'.utf8_encode($visivel_dest).'</span>',
  '<span class="label label-sm label-'.$etiqueta2_novi.'">'.utf8_encode($visivel_novi).'</span>',*/
    '<span class="label label-sm label-'.$etiqueta2.'">'.utf8_encode($visivel).'</span>',
    '<a href="produtos-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i>'.$RecursosCons->RecursosCons['btn_editar'].'</a>&nbsp;&nbsp;&nbsp;<a href="../../../'.$url.'" target="_blank" class="btn btn-xs default btn-editable" title="Preview" alt="Preview"><i class="fa fa-eye"></i></a>
    <a target="_blank" id="'.$id.'" class="btn btn-xs default btn-editable popup" title="Preview" alt="Preview"><i class="fa fa-car"></i></a>
    <a  href="produtos.php?id_delete='.$id.'" class="btn btn-xs default btn-trash-o" title="Preview" alt="Preview" onclick="return checkDelete()"><i class="fa fa-trash-o"></i></a>
    '
    );
    
    $i++;
  }

  DB::close();

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>

