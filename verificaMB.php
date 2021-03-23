<?php
$is_cron_file=1;
require_once('Connections/connADMIN.php'); ?>
<?php //include_once('sendMail/send_mail.php'); ?>
<?php
error_reporting(E_ALL); ini_set("display_errors", "1");
if (!isset($_SESSION)) {
  session_start();
}

$chave = $_GET['chave'];
$entidade = $_GET['entidade'];
$referencia = $_GET['referencia'];
$valor = $_GET['valor'];

if($chave == 'dr4fr56yb51c473dy68i5d2a5ba041b6') {
	
	$query_rsRefMult = "SELECT * FROM met_pagamento_pt WHERE id='6'";
	$rsRefMult = DB::getInstance()->query($query_rsRefMult);
	$row_rsRefMult = $rsRefMult->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsRefMult = $rsRefMult->rowCount();
	DB::close();
		  
	$ent_id=$row_rsRefMult['entidade'];
	
	if($entidade == $ent_id) {
		
		$query_rsEnc = "SELECT * FROM encomendas WHERE ref_pagamento='$referencia' AND estado='1'";
		$rsEnc = DB::getInstance()->query($query_rsEnc);
		$row_rsEnc = $rsEnc->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsEnc = $rsEnc->rowCount();
		DB::close();
		
		if($totalRows_rsEnc > 0) {
			
			//$valor_enc=$row_rsEnc['valor_c_iva']-$row_rsEnc['compra_valor_saldo'];
			$valor_enc=$row_rsEnc['valor_c_iva'];
		
			if(number_format($valor_enc,2,'.','') == number_format($valor,2,'.','')){
				
				//atualiza estado encomenda
				$insertSQL = "UPDATE encomendas SET estado = '2' WHERE id='".$row_rsEnc['id']."'";
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
				
				//sendMail("davide@netgocio.pt",'',"Correu verificaMB","Correu verificaMB","Correu verificaMB");
				
			}
		}
	}
}

?>