<?php include_once('../inc_pages.php'); ?>
<?php 
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=utf-8");
?>
<?php 

if($_POST['op'] == 'carregaProdutos') {
  $categoria = $_POST['categoria'];
  $marca = $_POST['marca'];
  $id = $_POST['id'];
  
  $where = "";
  $where_cat = "";
  $left_join = "";
  if($categoria > 0) {
    if(CATEGORIAS == 1) {
      $where_cat .= " AND (p.categoria = '$categoria' OR c1.cat_mae = '$categoria'";
    }
    else if(CATEGORIAS == 2){
      $where_cat .= " AND (pc.id_categoria = '$categoria' OR c1.cat_mae = '$categoria'";
    }

    if(CATEGORIAS_NIVEL > 2) {
      for($i = 2; $i < CATEGORIAS_NIVEL; $i++) {
        $left_join .= " LEFT JOIN l_categorias_en c".$i." ON c".$i.".id = c".($i-1).".cat_mae";
        
        $where_cat .= " OR c".$i.".cat_mae = '$categoria'";
      }
    }

    $where_cat .= ")";
  }

  if($marca > 0) {
    $where .= " AND p.marca = '$marca'";
  }

  if(CATEGORIAS == 1) {
    $query_rsProdutos = "SELECT p.id, p.ref, p.nome FROM l_pecas_pt p LEFT JOIN l_categorias_en c1 ON c1.id = p.categoria ".$left_join." WHERE p.id > 0 ".$where.$where_cat." GROUP BY p.id ORDER BY p.nome ASC";
    $rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
    $rsProdutos->execute();
    $totalRows_rsProdutos = $rsProdutos->rowCount();
  }
  else if(CATEGORIAS == 2) {
    $query_rsProdutos = "SELECT p.id, p.ref, p.nome FROM l_pecas_pt p LEFT JOIN l_pecas_categorias pc ON pc.id_peca = p.id LEFT JOIN l_categorias_en c1 ON c1.id = pc.id_categoria ".$left_join." WHERE p.id > 0 ".$where.$where_cat." GROUP BY p.id ORDER BY p.nome ASC";
    $rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
    $rsProdutos->execute();
    $totalRows_rsProdutos = $rsProdutos->rowCount();
  }
  ?>
  <select class="form-control select2me" name="produto" id="produto">
    <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
    <?php if($totalRows_rsProdutos > 0) { 
      while($row_rsProdutos = $rsProdutos->fetch()) { ?>
        <option value="<?php echo $row_rsProdutos['id']; ?>" <?php if($row_rsProdutos['id'] == $id) echo "selected"; ?>><?php echo utf8_encode($row_rsProdutos['ref']." - ".$row_rsProdutos['nome']); ?></option>
      <?php } 
    } ?>
  </select>
  <?php
}

DB::close();

?>