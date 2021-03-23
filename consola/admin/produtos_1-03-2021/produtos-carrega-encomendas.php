<?php include_once('../inc_pages.php'); ?>
<?php

$client_id = $_POST['id_client'];
$last_order_id = $_POST['last_order'];
$dir = $_POST['dir'];
$product_id = $_POST['product_id'];
$flag = $_POST['flag'];

if($flag != null && $flag == 'geral') {
	$query_rsEncomendas = "SELECT COUNT(e.id) AS totalEnc, SUM(e.valor_c_iva) AS valorEnc FROM encomendas e WHERE e.id_cliente='$client_id'";
	$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
	$rsEncomendas->execute();
	$row_rsEncomendas = $rsEncomendas->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsEncomendas = $rsEncomendas->rowCount();

	$res = array("total_enc" => $row_rsEncomendas['totalEnc'], "valor_enc" => $row_rsEncomendas['valorEnc']);

	echo json_encode($res);
}
else {
	if($dir=='ant') {
		$query_rsEncomendas = "SELECT DISTINCT(e.id), e.*, ee.id as id_estado, ee.nome_pt, ep.qtd, ep.preco FROM encomendas e, encomendas_produtos ep, enc_estados ee WHERE ee.id=e.estado AND e.id_cliente='$client_id' AND e.id=ep.id_encomenda AND ep.produto_id='$product_id' AND e.id<'$last_order_id' ORDER BY e.id DESC";
		$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
		$rsEncomendas->execute();
		$row_rsEncomendas = $rsEncomendas->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsEncomendas = $rsEncomendas->rowCount();

		if($totalRows_rsEncomendas == 0) {

			$query_rsEncomendas = "SELECT DISTINCT(e.id), e.*, ee.id as id_estado, ee.nome_pt, ep.qtd, ep.preco FROM encomendas e, encomendas_produtos ep, enc_estados ee WHERE ee.id=e.estado AND e.id_cliente='$client_id' AND e.id=ep.id_encomenda AND ep.produto_id='$product_id' AND e.id='$last_order_id'";
			$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
			$rsEncomendas->execute();
			$row_rsEncomendas = $rsEncomendas->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsEncomendas = $rsEncomendas->rowCount();
		}
	}
	else if($dir=='prox'){
		$query_rsEncomendas = "SELECT DISTINCT(e.id), e.*, ee.id as id_estado, ee.nome_pt, ep.qtd, ep.preco FROM encomendas e, encomendas_produtos ep, enc_estados ee WHERE ee.id=e.estado AND e.id_cliente='$client_id' AND e.id=ep.id_encomenda AND ep.produto_id='$product_id' AND e.id>'$last_order_id' ORDER BY e.id ASC";
		$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
		$rsEncomendas->execute();
		$row_rsEncomendas = $rsEncomendas->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsEncomendas = $rsEncomendas->rowCount();

		if($totalRows_rsEncomendas == 0) {
			$query_rsEncomendas = "SELECT DISTINCT(e.id), e.*, ee.id as id_estado, ee.nome_pt, ep.qtd, ep.preco FROM encomendas e, encomendas_produtos ep, enc_estados ee WHERE ee.id=e.estado AND e.id_cliente='$client_id' AND e.id=ep.id_encomenda AND ep.produto_id='$product_id' AND e.id='$last_order_id'";
			$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
			$rsEncomendas->execute();
			$row_rsEncomendas = $rsEncomendas->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsEncomendas = $rsEncomendas->rowCount();
		}
	}
	else {
		$query_rsEncomendas = "SELECT DISTINCT(e.id), e.*, ee.id as id_estado, ee.nome_pt, ep.qtd, ep.preco FROM encomendas e, encomendas_produtos ep, enc_estados ee WHERE ee.id=e.estado AND e.id_cliente='$client_id' AND e.id=ep.id_encomenda AND ep.produto_id='$product_id'";
		$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
		$rsEncomendas->execute();
		$row_rsEncomendas = $rsEncomendas->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsEncomendas = $rsEncomendas->rowCount();
	}

	if($row_rsEncomendas['id_estado'] == 1)
		$estado = "<span class='label label-info'>".utf8_encode($row_rsEncomendas['nome_pt'])."</span>";
	else if($row_rsEncomendas['id_estado'] == 2)
		$estado = "<span class='label label-primary'>".utf8_encode($row_rsEncomendas['nome_pt'])."</span>";
	else if($row_rsEncomendas['id_estado'] == 3 || $row_rsEncomendas['id_estado'] == 4)
		$estado = "<span class='label label-success'>".utf8_encode($row_rsEncomendas['nome_pt'])."</span>";
	else if($row_rsEncomendas['id_estado'] == 6)
		$estado = "<span class='label label-warning'>".utf8_encode($row_rsEncomendas['nome_pt'])."</span>";
	else if($row_rsEncomendas['id_estado'] == 5)
		$estado = "<span class='label label-danger'>".utf8_encode($row_rsEncomendas['nome_pt'])."</span>";
	else
		$estado = utf8_encode($row_rsEncomendas['nome_pt']);

	$res = array("data" => $row_rsEncomendas['data'], "id" => $row_rsEncomendas['id'], "estado" => $estado, "qtd_prod" => $row_rsEncomendas['qtd'], "total_prod" => $row_rsEncomendas['preco'], "total_enc" => $row_rsEncomendas['valor_c_iva']);

	echo json_encode($res);
}



?>