<?php require_once('../../../Connections/connADMIN.php'); ?>
<?php

$id_encomenda=$_GET['encomenda'];

/*include_once(ROOTPATH."linguas/pt.php");
$Recursos = new Recursos_pt();
$extensao = "_pt";*/

?>
<html>
<head>
<title>Cakery Suppliers <?php echo $id_encomenda; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>
<script type="text/javascript">
function imprime(){
	print();
}
<?php if($_GET['naoimprime']!=1){?>
window.onload=imprime;
<?php } ?>
</script>
<body>
<div class="imprime_cont">
	<?php echo $class_carrinho->packingSlip($id_encomenda, 1); ?>
</div>
</body>
</html>
