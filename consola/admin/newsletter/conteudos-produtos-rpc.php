<?php include_once('../inc_pages.php'); ?>
<?php
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?php

if($_POST['op'] == 'ordenar_registos') {	
	$valor = $_POST['campos'];
	$array = explode("&",$valor);
	
	foreach($array as $valor) {
		$valor_final = explode("=",$valor);		
		$ordem_final = $valor_final[1];
		
		$valor_final_2 = explode("_",$valor_final[0]);		
		$campo_id = $valor_final_2[1];		
		
		if($campo_id > 0 && $ordem_final > 0) {		
			$insertSQL = "UPDATE news_produtos SET ordem='$ordem_final' WHERE id='$campo_id'";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->execute();
			DB::close();
		}
	}
}

if($_POST['op'] == "carregaProdutos") {
	$cat = $_POST['cat'];
	$id = $_POST['id'];
	
	if($cat > 0) {
		$query_rsProd = "SELECT p.id, p.nome FROM l_pecas_en p LEFT JOIN l_categorias_en c ON c.id = p.categoria WHERE p.categoria = :cat OR c.cat_mae = :cat GROUP BY p.id ORDER BY p.nome ASC";
		$rsProd = DB::getInstance()->prepare($query_rsProd);
		$rsProd->bindParam(':id', $id, PDO::PARAM_INT);
		$rsProd->bindParam(':cat', $cat, PDO::PARAM_INT);
		$rsProd->execute();
		$totalRows_rsProd = $rsProd->rowCount();
		DB::close();
	}
	else {
		$query_rsProd = "SELECT id, nome FROM l_pecas_en ORDER BY nome ASC";
		$rsProd = DB::getInstance()->prepare($query_rsProd);
		$rsProd->bindParam(':id', $id, PDO::PARAM_INT);
		$rsProd->execute();
		$totalRows_rsProd = $rsProd->rowCount();
		DB::close();
	}
	?>
  <select class="form-control select2me" id="produto" name="produto" onChange="carregaDadosProd(this.value);">
    <option value="">Selecionar...</option>
    <?php if($totalRows_rsProd > 0) { ?>
      <?php while($row_rsProd = $rsProd->fetch()) { ?>
        <option value="<?php echo $row_rsProd['id']; ?>" <?php if($id == $row_rsProd['id']) echo "selected"; ?>><?php echo $row_rsProd['nome']; ?></option>
      <?php } ?>
    <?php } ?>
  </select>
	<?php	
}

if($_POST['op'] == "carregaDadosProd") {
	$id_prod = $_POST['id'];

	$query_rsProd = "SELECT * FROM l_pecas_en WHERE id = ".$id_prod;
	$rsProd = DB::getInstance()->prepare($query_rsProd);
	$rsProd->execute();
	$row_rsProd = $rsProd->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsProd = $rsProd->rowCount();

	$nome = utf8_encode($row_rsProd['nome']);

	$string = json_encode(array("nome"=>$nome, "ref"=>$row_rsProd['ref'], "preco"=>$row_rsProd['preco'], "url"=>$row_rsProd['url']));

	echo $decoded = html_entity_decode( $string );
}

?>