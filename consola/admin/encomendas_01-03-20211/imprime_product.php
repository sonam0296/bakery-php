<?php require_once('../../../Connections/connADMIN.php'); ?>
<?php

$id_product=$_GET['product_id'];

$query_rsCarrinhoFinal = "SELECT * FROM encomendas_produtos WHERE id_encomenda=:id ORDER BY cat_mea ASC";
$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);
$rsCarrinhoFinal->bindParam(':id', $id_product, PDO::PARAM_INT); 
$rsCarrinhoFinal->execute();
$row_rsCarrinhoFinal = $rsCarrinhoFinal->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();

?>
<html>
<head>
<title>Product Slip</title>
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
	<h2 align="Center">Product Slip</h2>

	<table border="1" align="Center">
	  <tbody>  
	  	<tr>
			<th>Product Name </th>
			<th>Qtn  </th>
		</tr>
		<?php 
			foreach($row_rsCarrinhoFinal as $produtos) { 
			$produto = $produtos['produto_id'];
			$nome_prod = $produtos['produto'];
			$codigo = $produtos['ref'];
			$quantidade = $produtos['qtd'];
			$cate = $produtos['cat_mea'];
			if($cate != 0)
			{
		?>
        <tr>
          <td  ><?php echo $nome_prod; ?></td>
           <td  ><?php echo $quantidade; ?></td>
        </tr>	
       
        <?php } 
          } ?>
      </tbody>
    </table>

<!-- <div class="imprime_cont">
	<?php //echo $class_carrinho->emailEncomenda($id_product, 1); ?>
</div> -->
</body>
</html>
