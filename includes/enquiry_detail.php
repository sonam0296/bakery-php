`<?php require_once('../Connections/connADMIN.php'); ?>
<?php
$query_rs_value = "SELECT * FROM store_locater WHERE id = '".$_POST["store_id"]."' ";
$rsP_value = DB::getInstance()->prepare($query_rs_value); 
$rsP_value->execute();
$totalRows_value = $rsP_value->rowCount();
$row_rsP_value = $rsP_value->fetch(PDO::FETCH_ASSOC);

$query_rs_pro = "SELECT * FROM l_pecas_en WHERE id= '".$_POST["e_product_id"]."' ";
$rsP_pro = DB::getInstance()->prepare($query_rs_pro); 
$rsP_pro->execute();
$totalRows_rsP_pro = $rsP_pro->rowCount();
$row_rsP_pro = $rsP_pro->fetch(PDO::FETCH_ASSOC);


if(tableExists(DB::getInstance(), "clientes")) {
	if(!empty($row_rsCliente)) 
	{
		$reviwer_id = $row_rsCliente['id'];
		$query_rs_client = "SELECT * FROM clientes WHERE id = '".$reviwer_id."' ";
		$rsP_client = DB::getInstance()->prepare($query_rs_client); 
		$rsP_client->execute();
		$totalRows_client = $rsP_client->rowCount();
		$row_rsP_client = $rsP_client->fetch(PDO::FETCH_ASSOC);
	}
}

?>

<h4>Call Us On:</h4>

<h3 style="color: red;"><?php echo $row_rsP_value["phone"]; ?></h3>	<br>
<h4 style="color: #ebb237;"><?php echo $row_rsP_value["b_name"]; ?></h4><br>

---------------Or------------------<br>


<form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
<input type="submit" name="conform_mail" value="Send us an email">
</form>
