<?php require_once('Connections/connADMIN.php'); ?>
<?php

if($row_rsCliente == 0 && CARRINHO_LOGIN == 1) {
	header("Location: ".ROOTPATH_HTTP."index.php");	
	exit();
}

$id_cliente = 0;
if(!empty($row_rsCliente['id'])) {
  $id_cliente = $row_rsCliente['id'];
}

$id_encomenda = $_GET['encomenda'];

if(isset($_SESSION["email_user"])) {
	$query_rsEncomenda = "SELECT id FROM encomendas WHERE email = :email AND id = :id";
	$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
	$rsEncomenda->bindParam(':id', $id_encomenda, PDO::PARAM_INT); 
	$rsEncomenda->bindParam(':email', $_SESSION["email_user"], PDO::PARAM_INT); 
	$rsEncomenda->execute();
	$totalRows_rsEncomenda = $rsEncomenda->rowCount();
	DB::close();
}
else {
	$query_rsEncomenda = "SELECT id FROM encomendas WHERE id_cliente = :cliente AND id = :id";
	$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
	$rsEncomenda->bindParam(':id', $id_encomenda, PDO::PARAM_INT); 
	$rsEncomenda->bindParam(':cliente', $id_cliente, PDO::PARAM_INT); 
	$rsEncomenda->execute();
	$totalRows_rsEncomenda = $rsEncomenda->rowCount();
	DB::close();
}

if($totalRows_rsEncomenda == 0) {
	header("Location: ".ROOTPATH_HTTP."index.php");	
	exit();
}

?>
<html>
<head>
<title>Encomenda Nº<?php echo $id_encomenda; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<script type="text/javascript">
	function imprime(){
		print();
	}
	<?php if($_GET['naoimprime'] != 1) { ?>
		window.onload = imprime;
	<?php } ?>
</script>
<body>
	<div class="imprime_cont">
		<?php echo $class_carrinho->emailEncomenda($id_encomenda, 1); ?>
	</div>
</body>
</html>
