<?php
//PayPal Express Checkout
$pp_cli_email = $row_rsEncomenda['email'];
$pp_prodID = $row_rsQtds['paypal_key'];
$pp_idenc = $row_rsEncomenda['numero'];
$pp_subtotal = $row_rsEncomenda['valor_total'];
$pp_portesenvio = $row_rsEncomenda['portes_pagamento'] + $row_rsEncomenda['portes_entrega'];
$pp_total = $row_rsEncomenda['valor_c_iva'];
$pp_moeda = $currency_code;
if(!$pp_moeda) {
	$pp_moeda = "lb";
}

//DESCONTOS (SALDO + CODIGO PROMO)
$pp_desconto_promos = $row_rsEncomenda['compra_valor_saldo'] + $row_rsEncomenda['codigo_promocional_valor'];

// 14/02/2018 DS - Comentado uma vez que o $row_rsEncomenda['valor_total'] já é guardado com o desconto
// if($pp_desconto_promos>0){
// 	$pp_subtotal-=$pp_desconto_promos;
// }

$pp_telemovel = $row_rsEncomenda['telemovel'];
if(!$pp_telemovel) {
  $pp_telemovel = $row_rsEncomenda['telefone'];
}

if(!$array_servidor) {
	$array_servidor = unserialize(SERVIDOR_ARRAY);
}
if(in_array($_SERVER['HTTP_HOST'], $array_servidor) && !strstr($_SERVER['REQUEST_URI'], '/proposta')) {
	//Production
	$pp_prodENV = "production";

	$link_paypal = "https://www.paypal.com/cgi-bin/webscr?cmd=_cart&upload=1&no_note=0&lc=PT&currency_code=".$pp_moeda."&bn=PP-BuyNowBF&tax=0&rm=2&handling_cart=0&custom=&business=".$row_rsQtds['paypal_key']."&item_number_1=".$pp_idenc;
}
else {
	//Sandbox
	$pp_prodENV = "sandbox";

	$link_paypal = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_cart&upload=1&no_note=0&lc=PT&currency_code=".$pp_moeda."&bn=PP-BuyNowBF&tax=0&rm=2&handling_cart=0&custom=&business=davide-facilitator@netgocio.pt&item_number_1=".$pp_idenc;
}

?>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<div id="paypal-msg-container" class="desc" style="display: none; font-weight: 600; font-size: 1.4em;"><?php echo $Recursos->Resources["car_comprar_paypal_suc"]; ?></div>
<div id="paypal-button-container" style="text-align: left; margin-top: 20px"></div>
<script>
window.setTimeout(function() { //é necessário ter este window.setTimeout por causa do mmenu (não me perguntem porquê!!)
  // Render the PayPal button
  paypal.Button.render({
    // Set your environment
    env: '<?php echo $pp_prodENV; ?>', // sandbox | production

    // Specify the style of the button
    style: {
      label: 'pay', // checkout | credit | pay
      size:  'medium', // small | medium | responsive
      shape: 'pill', // pill | rect
      color: 'gold' // gold | blue | silver
    },

    client: {
      sandbox: 'AU58hXH3i_upNmp0cGLlJsX3WKZqrWIMv9poUTol51SUCK1VmlLbhi68WHOReftWOzGciHF8o_v5jiiR', //Netgocio Teste (webtech.dev@gmail.com)
      production: '<?php echo $pp_prodID; ?>'
    },

    commit: true, // Show a 'Pay Now' button

    payment: function(data, actions) {
      return actions.payment.create({
        payer: {
        	payer_info: { 
        		email: '<?php echo $pp_cli_email; ?>' 
        	}
        },
        transactions: [{
        	reference_id: '<?php echo $pp_idenc; ?>',
          amount: {
          	total: '<?php echo $pp_total; ?>',
          	currency: '<?php echo $pp_moeda; ?>',
          	details: {
							subtotal: "<?php echo $pp_subtotal; ?>",
							tax: "0.00",
							shipping: "<?php echo $pp_portesenvio; ?>",
							handling_fee: "0.00",
							shipping_discount: "0.00",
							insurance: "0.00"
						}
          },
          custom: '<?php echo $pp_cli_email; ?>',
          notify_url: '', //URL de Callback
          item_list: {
						items: [
							<?php 
              $count = 0;
              foreach($row_rsCarrinhoFinal as $produtos) {
                $count ++;											
                
                $nome_produto = $produtos['produto'];
                $preco_prod = $produtos['preco'];
                $quant_prod = $produtos['qtd'];
                $opcoes = $produtos['opcoes'];
    
                $desconto_prod = $produtos['desconto'];
                
                if($desconto_prod > 0) {
                  $preco_prod = $preco_prod - ($preco_prod * ($desconto_prod / 100));
                  $preco_prod = number_format($preco_prod, 2);
                }
                
                $preco_prod = number_format($preco_prod, 2, ".", "");
                
                $link_paypal .= "&item_name_".$count."=".utf8_encode($nome_produto)."&quantity_".$count."=".$quant_prod."&amount_".$count."=".$preco_prod;
              	?>
	              {
									name: "<?php echo html_entity_decode($nome_produto); ?>",
                  description: "<?php echo html_entity_decode(strip_tags($opcoes)); ?>",
									quantity: "<?php echo $quant_prod; ?>",
									price: "<?php echo $preco_prod; ?>",
									tax: "0.00",
									<?php if($count == 1) { ?>sku: "<?php echo $pp_idenc; ?>", <?php } ?>
									currency: "<?php echo $pp_moeda; ?>"
								}<?php if($count < $totalRows_rsCarrinhoFinal) echo ",";
							}
							
							if($pp_desconto_promos > 0) { ?>,
								{
									name: "Desconto",
									quantity: "1",
									price: "-<?php echo $pp_desconto_promos; ?>",
									currency: "<?php echo $pp_moeda; ?>"
								}
							<?php } ?>
						]<?php if($row_rsEncomenda['cod_pais']) { ?>,
              shipping_address: {
                recipient_name: "<?php echo html_entity_decode($row_rsEncomenda['nome_envio']); ?>",
                line1: "<?php echo html_entity_decode(preg_replace( "/\r|\n/", " ", $row_rsEncomenda['morada_envio'])); ?>",
                line2: "",
                city: "<?php echo html_entity_decode($row_rsEncomenda['localidade_envio']); ?>",
                country_code: "<?php echo html_entity_decode($row_rsEncomenda['cod_pais']); ?>",
                postal_code: "<?php echo html_entity_decode($row_rsEncomenda['codpostal_envio']); ?>",
                phone: "<?php echo str_replace(" ", "", $pp_telemovel); ?>",
                state: ""
              }
            <?php } ?>
					}
        }]
    	});
  	},

    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        document.getElementById('paypal-button-container').style.display = "none";
        document.getElementById('paypal-msg-container').style.display = "block";
      });
    }
  }, '#paypal-button-container');
},1000);
</script>
<?php
if($pp_portesenvio > 0) {
	$link_paypal .= "&shipping_1=".$pp_portesenvio;
}
if($pp_desconto_promos > 0) {
	$link_paypal .= "&discount_amount_cart=".$pp_desconto_promos;
}
?>