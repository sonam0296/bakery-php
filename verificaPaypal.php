<?php
$is_cron_file=1;
require_once('Connections/connADMIN.php'); ?>
<?php //include_once('sendMail/send_mail.php'); ?>
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION)) {
  session_start();
}

//reading raw POST data from input stream. reading pot data from $_POST may cause serialization issues since POST data may contain arrays
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval)
{
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
	 $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc'))
{
   $get_magic_quotes_exits = true;
} 
foreach ($myPost as $key => $value)
{        
   if($get_magic_quotes_exits == true && get_magic_quotes_gpc() == 1)
   { 
		$value = urlencode(stripslashes($value)); 
   }
   else
   {
		$value = urlencode($value);
   }
   $req .= "&$key=$value";
}
//mail("davide@netgocio.pt", "Paypal", $req);  //Aviso para Cliente
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/cgi-bin/webscr');
//curl_setopt($ch, CURLOPT_URL, 'https://www.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
/*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);*/

//AS SEGUINTES 2 LINHAS FORAM ADICIONADAS POR CAUSA DO SERVIDOR DA AMEN (IUPULL)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.paypal.com'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.sandbox.paypal.com'));
// In wamp like environment where the root authority certificate doesn't comes in the bundle, you need
// to download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
$res = curl_exec($ch);
curl_close($ch);

// assign posted variables to local variables
$item_name = $_POST['item_name1'];
$item_number = $_POST['item_number1'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];


/*$item_number = 2;
$payment_status = "Completed";
$payment_amount = "1377.62";
$txn_id = "12312das";
$receiver_email = "";
$res="VERIFIED";
*/
// email do cliente
$query_rsQtds = "SELECT met_pagamento_pt.* FROM met_pagamento_pt WHERE met_pagamento_pt.id='1'";
$rsQtds = DB::getInstance()->query($query_rsQtds);
$row_rsQtds = $rsQtds->fetch(PDO::FETCH_ASSOC);
$totalRows_rsQtds = $rsQtds->rowCount();
DB::close();

//mail("davide@netgocio.pt", "Paypal", $res);  //Aviso para Cliente

if (strcmp ($res, "VERIFIED") == 0) {
	// check the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your Primary PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment
	
	//mail("davide@netgocio.pt", "Paypal", "Entrou no verified");  //Aviso para Cliente
	if($receiver_email == $row_rsQtds['email']) {
	//if($receiver_email == 'nuno.c_1346690809_biz@netgocio.pt') {
		if($payment_status == 'Completed') {
			// verifica se txn_id já foi usado
			$query_rsTxnId = "SELECT * FROM encomendas WHERE met_pagamt_id='1' AND ref_pagamento='$txn_id'";
			$rsTxnId = DB::getInstance()->query($query_rsTxnId);
			$row_rsTxnId = $rsTxnId->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsTxnId = $rsTxnId->rowCount();
			DB::close();
	
	
			//mail("davide@netgocio.pt", "Paypal", "Entrou no Completed item_number:".$item_number.", payment_amount:".$payment_amount);  //Aviso para Cliente
			
			if($totalRows_rsTxnId == 0) { // o txn_id não foi usado, não há tentativa de fraude
				$query_rsEnc = "SELECT * FROM encomendas WHERE numero='$item_number'";
				$rsEnc = DB::getInstance()->query($query_rsEnc);
				$row_rsEnc = $rsEnc->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsEnc = $rsEnc->rowCount();
				DB::close();
			
				//mail("davide@netgocio.pt", "Paypal", "Entrou no totalRows_rsTxnId row_rsEnc['valor_final']:".$row_rsEnc['valor_final']);  //Aviso para Cliente
				
				if($totalRows_rsEnc > 0) {
					
					//$valor_enc=$row_rsEnc['valor_c_iva']-$row_rsEnc['compra_valor_saldo'];
					$valor_enc=$row_rsEnc['valor_c_iva'];
					
					if(number_format($valor_enc,2,'.','') == number_format($payment_amount,2,'.','')){
						$id_enc=$row_rsEnc['id'];
						
						//atualiza estado encomenda
						$insertSQL = "UPDATE encomendas SET estado = '2', ref_pagamento='$txn_id' WHERE id='$id_enc'";
						$Result1 = DB::getInstance()->prepare($insertSQL);
						$Result1->execute();
						DB::close();
				
						//insere histórico nos logs
						$data = date('Y-m-d H:i:s');
		
						$insertSQL = "INSERT INTO enc_estados_historico (id, id_encomenda, estado, data, notificado) VALUES ('', :id, '2', :data, '1')";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);			
						$rsInsert->bindParam(':id', $row_rsEnc['id'], PDO::PARAM_INT);
						$rsInsert->execute();
						DB::close();
				
						//envia email
						echo $class_carrinho->emailEncomenda($row_rsEnc['id']);
						
						//sendMail("davide@netgocio.pt",'',"Correu verificaPaypal","Correu verificaPaypal","Correu verificaPaypal");
						
					}
				}
			}
		}
	}
}
else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
}


?>