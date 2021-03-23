<?php require_once('../Connections/connADMIN.php'); ?>
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

	<?php 

	if(!empty($row_rsCliente))
	{

		if( !isset($_POST["conform_mail"]))
		{


			$productData = array(
		                  'storetInfo'  =>   $row_rsP_value,
		                  'clientInfo'  =>   $row_rsP_client,
		                  'productInfo'  =>   $row_rsP_pro,
		          );


			/*echo "<pre>";
			print_r($row_rsP_value);
			print_r($row_rsP_pro);
			print_r($row_rsP_client);*/

			$query_rsProc = "SELECT MAX(id) FROM encomendas";
			$rsProc       = DB::getInstance()->prepare($query_rsProc);
			$rsProc->execute();
			$row_rsProc       = $rsProc->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsProc = $rsProc->rowCount();

			$id_encomenda = $row_rsProc['MAX(id)']+1;		
			$data_enc = date('Y-m-d H:i:s');

			$insertSQL = "INSERT INTO encomendas (id, store_name, id_cliente, nome, nome_envio,  morada_envio, codpostal_fatura, codpostal_envio, localidade_fatura, localidade_envio, pais_envio, pais_fatura, email, telemovel, prods_total, valor_total, valor_iva, valor_c_iva , data, estado , cod_pais) VALUES (:id, :store_name,  :id_cliente, :nome, :nome_envio, :morada_envio, :codpostal_fatura, :codpostal_envio, :localidade_fatura, :localidade_envio, 0 , 0 , :email, :telemovel, :prods_total, :valor_total, 1, :valor_c_iva , :data, 8 , 'en')";
			$rsInsert  = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id', $id_encomenda, PDO::PARAM_INT);
			$rsInsert->bindParam(':store_name', $row_rsP_value["b_name"], PDO::PARAM_STR, 5);
			//$rsInsert->bindParam(':numero', $num_encomenda, PDO::PARAM_INT);
			$rsInsert->bindParam(':id_cliente', $row_rsP_client["id"], PDO::PARAM_INT);
			$rsInsert->bindParam(':nome', $row_rsP_client["nome"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':nome_envio', $row_rsP_client["morada"], PDO::PARAM_STR, 5);
			//$rsInsert->bindParam(':morada_fatura', $morada_fatura, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':morada_envio', $morada_envio, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':codpostal_fatura', $row_rsP_client["cod_postal"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':codpostal_envio', $row_rsP_client["cod_postal"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':localidade_fatura', $row_rsP_client["localidade"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':localidade_envio', $row_rsP_client["localidade"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':email',  $row_rsP_client["email"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':telemovel', $row_rsP_client["telemovel"], PDO::PARAM_STR, 5);
		/*	$rsInsert->bindParam(':nif', $nif, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':pagamento', $met_pagamento, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':entidade', $ent_pagamento, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':ref_pagamento', $ref_pagamento, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':url_pagamento', $url_pagamento, PDO::PARAM_STR, 5);*/
			$rsInsert->bindParam(':prods_total', $row_rsP_pro["preco"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':valor_total', $row_rsP_pro["preco"], PDO::PARAM_STR, 5);
			//$rsInsert->bindParam(':valor_iva', "1", PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':valor_c_iva', $row_rsP_pro["preco"], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':data', $data_enc, PDO::PARAM_STR, 5);
			/*$rsInsert->bindParam(':observacoes', $observacoes, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':portes_pagamento', $portes_pag, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':portes_entrega', $portes_ent, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':entrega_id', $met_envio_id, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':entrega', $met_envio, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':opcao_texto', $opcao_texto, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':opcao', $valor_opcao, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':fatura_digital', $fatura_digital, PDO::PARAM_STR, 5);
			
			$rsInsert->bindParam(':estado', $estado, PDO::PARAM_INT);
			$rsInsert->bindParam(':saldo_compra', $saldo_acumula, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':codigo_promocional', $codigo_promocional, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':codigo_promocional_desconto', $codigo_promocional_desconto, PDO::PARAM_INT);
			$rsInsert->bindParam(':codigo_promocional_valor', $codigo_promocional_valor_bd, PDO::PARAM_INT);
			$rsInsert->bindParam(':pontos_compra', $pontos_compra, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':compra_valor_saldo', $saldo_compra, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':envio_link', $envio_link, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':met_pagamt_id', $pagamento, PDO::PARAM_INT);
			$rsInsert->bindParam(':moeda', $moeda_enc, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':valor_conversao', $valor_conversao, PDO::PARAM_STR, 5);*/
			$rsInsert->execute();


			$id_oferta = 0;
			if ($row_rsCarrinho['id_oferta'] > 0) {
				$id_oferta = 1;
			}

			$insertSQL1 = "INSERT INTO encomendas_produtos (id_encomenda,id_oferta, produto, ref, imagem1, url,  qtd, preco, produto_id) VALUES (:id_encomenda, '".$id_oferta."', :produto, :ref, :imagem, :url, '1', :preco, :produto_id)";
			$rsInsert1  = DB::getInstance()->prepare($insertSQL1);
			$rsInsert1->bindParam(':id_encomenda', $id_encomenda, PDO::PARAM_INT);
			//$rsInsert->bindParam(':id_oferta', $id_oferta, PDO::PARAM_INT);
			$rsInsert1->bindParam(':produto', $row_rsP_pro["nome"], PDO::PARAM_STR, 30);
			$rsInsert1->bindParam(':ref', $row_rsP_pro["ref"], PDO::PARAM_STR, 30);
			$rsInsert1->bindParam(':imagem', $row_rsP_pro["imagem1"], PDO::PARAM_STR, 30);
			$rsInsert1->bindParam(':url', $row_rsP_pro["url"], PDO::PARAM_STR, 30);
			//$rsInsert->bindParam(':opcoes', $opcoes, PDO::PARAM_STR, 30);
			//$rsInsert1->bindParam(':qtd', $qtd, PDO::PARAM_INT);
			$rsInsert1->bindParam(':preco', $row_rsP_pro["preco"], PDO::PARAM_STR, 30);
			//$rsInsert->bindParam(':desconto', $desconto_produto, PDO::PARAM_INT);
			//$rsInsert->bindParam(':iva', $iva_prod, PDO::PARAM_INT);
			$rsInsert1->bindParam(':produto_id', $row_rsP_pro["id"], PDO::PARAM_INT);
			//$rsInsert->bindParam(':opcoes_id', $opcoes_id, PDO::PARAM_INT);
			//$rsInsert->bindParam(':cheque_prenda', $cheque_prenda, PDO::PARAM_INT);
			//$rsInsert->bindParam(':cheque_nome', $cheque_nome, PDO::PARAM_STR, 30);
			//$rsInsert->bindParam(':cheque_email', $cheque_email, PDO::PARAM_STR, 30);
			$rsInsert1->execute();

			$classUser = User::getInstance();
		    $classUser->sendEnquiry("productsuccess", $productData);
			
			}
			else {
				echo "Not Found";
			}
		}
	else
	{
		echo "login plz";
	}
	
?>