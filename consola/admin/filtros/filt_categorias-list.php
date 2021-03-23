<?php include_once('../inc_pages.php'); ?>

<?php

  /* 

   * Paging

   */

   

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

    

    if($opcao == '-1') { // elimina utilizadores selecionados

      // $query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";

      // $rsLinguas = DB::getInstance()->query($query_rsLinguas);

      // $rsLinguas->execute();

      // $totalRows_rsLinguas = $rsLinguas->rowCount();

      // DB::close();

      

      // while($row_rsLinguas = $rsLinguas->fetch()) {

      //  $query_rsDel = "DELETE FROM l_filt_categorias_en WHERE id IN $lista";

      //  $rsDel = DB::getInstance()->query($query_rsDel);

      //  $rsDel->execute();

      //  DB::close();    

      // }



      //   $query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";

      //   $rsLinguas = DB::getInstance()->query($query_rsLinguas);

      //   $rsLinguas->execute();

      //   $totalRows_rsLinguas = $rsLinguas->rowCount();

      //   DB::close();

      

      // //colocar as opções  da categoria=0

      // while($row_rsLinguas = $rsLinguas->fetch()) {

      //   $insertSQL = "UPDATE l_filt_opcoes_en SET categoria='0' WHERE categoria IN $lista";

      //   $rsInsert = DB::getInstance()->prepare($insertSQL);

      //   $rsInsert->execute();

      //   DB::close();  

      // }

      $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

      $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

      $rsLinguas->execute();

      $row_rsLinguas = $rsLinguas->fetchAll();

      $totalRows_rsLinguas = $rsLinguas->rowCount();

      

      foreach ($row_rsLinguas as $linguas) {

        $query_rsP = "DELETE FROM l_filt_categorias_".$linguas["sufixo"]." WHERE id IN $lista";

        $rsP = DB::getInstance()->prepare($query_rsP);

        // $rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 

        $rsP->execute();

      }



      foreach ($row_rsLinguas as $linguas) {

        $insertSQL = "UPDATE l_filt_opcoes_".$linguas["sufixo"]." SET categoria='0' WHERE categoria IN $lista";

        $rsInsert = DB::getInstance()->prepare($insertSQL);

        $rsInsert->execute();  

      }

      //colocar a relação nos produtos/filtro a nulo/eliminado

      // $query_rsGetAssociacoesFiltrosProduto="SELECT * , bp.titulo AS titulo, f.id as id_relacao FROM l_filt_categorias_$lingua_consola bf INNER JOIN l_filt_opcoes_$lingua_consola cb ON ( cb.categoria = bf.id ) INNER JOIN l_blog_filtros f ON ( f.id_filtro = cb.id ) INNER JOIN blog_posts_$lingua_consola bp ON ( bp.id = f.id_blog ) WHERE f.id_filtro>0 AND bf.id = ".$id."";

      $query_rsGetAssociacoesFiltrosProduto="SELECT l_pecas_filtros.*, l_filt_categorias_$lingua_consola.nome AS nome_categoria,l_filt_categorias_$lingua_consola.id AS idCat, l_filt_opcoes_$lingua_consola.nome FROM l_pecas_filtros, l_filt_categorias_$lingua_consola LEFT JOIN l_filt_opcoes_$lingua_consola ON l_filt_opcoes_$lingua_consola.categoria=l_filt_categorias_$lingua_consola.id WHERE l_pecas_filtros.id_peca IN $lista AND l_filt_opcoes_$lingua_consola.id=l_pecas_filtros.id_filtro ORDER BY nome_categoria ASC, l_filt_opcoes_$lingua_consola.nome ASC";

      $rsGetAssociacoesFiltrosProduto = DB::getInstance()->prepare($query_rsGetAssociacoesFiltrosProduto);

      $rsGetAssociacoesFiltrosProduto->execute();

      $totalRows_rsGetAssociacoesFiltrosProduto = $rsGetAssociacoesFiltrosProduto->rowCount();

      DB::close();



      while($row_rsGetAssociacoesFiltrosProduto = $rsGetAssociacoesFiltrosProduto->fetch()) {

        $query_rsRemoveAssociacoesFiltrosProdutos = "DELETE FROM l_pecas_filtros WHERE id = :id2";

        $rsRemoveAssociacoesFiltrosProdutos = DB::getInstance()->prepare($query_rsRemoveAssociacoesFiltrosProdutos);

        $rsRemoveAssociacoesFiltrosProdutos->bindParam(':id2',$row_rsGetAssociacoesFiltrosProduto['idCat'], PDO::PARAM_INT, 5); 

        $rsRemoveAssociacoesFiltrosProdutos->execute();

      }

    }

  }

  

  // ordenação

  $sOrder = " ORDER BY id DESC";

  $colunas = array( '', 'nome', 'ordem', '');

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

    

    if($pesq_form != "") $where_pesq .= " AND (nome = '$pesq_form' OR nome LIKE '%$pesq_form%')";

  }

  

  $iDisplayLength = intval($_REQUEST['length']);

  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 

  $iDisplayStart = intval($_REQUEST['start']);

  $sEcho = intval($_REQUEST['draw']);

  

  $query_rsTotal = "SELECT * FROM l_filt_categorias_en WHERE id > '0'".$where_pesq.$sOrder;

  $rsTotal = DB::getInstance()->prepare($query_rsTotal);

  $rsTotal->execute();

  $totalRows_rsTotal = $rsTotal->rowCount();

 

  $iTotalRecords = $totalRows_rsTotal;

  

  $query_rsTotal = "SELECT * FROM l_filt_categorias_en WHERE id > '0'".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";

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

  

    $records["data"][] = array(

    '<input type="checkbox" id="check_'.$id.'" name="id[]" value="'.$id.'">',

    $nome,

    '<input type="text" id="order_'.$id.'" name="order_'.$id.'" class="cx_ordenar" value="'.$row_rsTotal['ordem'].'" onKeyPress="alteraOrdem(event)">',

    '<a href="filt_categorias-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '. $RecursosCons->RecursosCons['btn_editar'].'</a>',

    );

    

    $i++;

  }



   DB::close();



  $records["draw"] = $sEcho;

  $records["recordsTotal"] = $iTotalRecords;

  $records["recordsFiltered"] = $iTotalRecords;

  

  echo json_encode($records);

?>