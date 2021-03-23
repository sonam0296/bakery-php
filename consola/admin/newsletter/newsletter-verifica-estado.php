<?php

//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

require_once('../../../Connections/connADMIN.php');
include_once(ROOTPATH.'sendMail/sendMailNews.php');

set_time_limit(0);

/***************************************************************************************************************
* Correr o ficheiro de 30 em 30 min (25 e 55 por exemplo) para verificar se uma dada newsletter está bloqueada *
***************************************************************************************************************/

//if(isset($_GET['op']) && $_GET['op'] == '8c947091i5uj189051fc905123jk5ch190c58') {
	//Verificar se existe alguma em processamento
	$query_rsNewsletter = "SELECT id FROM newsletters_historico WHERE estado = '2'";
	$rsNewsletter = DB::getInstance()->prepare($query_rsNewsletter);
	$rsNewsletter->execute();
	$row_rsNewsletter = $rsNewsletter->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsNewsletter = $rsNewsletter->rowCount();
	DB::close();

	if($totalRows_rsNewsletter > 0) {
		$query_rsAtualizacao = "SELECT * FROM newsletters_atualizacoes WHERE id = '1'";
		$rsAtualizacao = DB::getInstance()->prepare($query_rsAtualizacao);
		$rsAtualizacao->execute();
		$row_rsAtualizacao = $rsAtualizacao->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsAtualizacao = $rsAtualizacao->rowCount();
		DB::close();

		//Verificar o nº atual de enviados
		$query_rsEnviados = "SELECT COUNT(id) as total FROM newsletters_vistos WHERE newsletter_id_historico = '".$row_rsNewsletter['id']."'";
		$rsEnviados = DB::getInstance()->prepare($query_rsEnviados);
		$rsEnviados->execute();
		$row_rsEnviados = $rsEnviados->fetch(PDO::FETCH_ASSOC);
		DB::close();

		$data = date('Y-m-d H:i:s');

		//Verifica se da última vez estava com os mesmos enviados
		if($row_rsAtualizacao['enviados'] == $row_rsEnviados['total'] && $row_rsNewsletter['id'] == $row_rsAtualizacao['newsletter_id_historico']) {
			//Fazer reset aos valores
			$query_rsUpdate = "UPDATE newsletters_atualizacoes SET newsletter_id_historico = 0, enviados = 0, data = '$data' WHERE id = '1'";
			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
			$rsUpdate->execute();
			DB::close();

			//Mudar o estado do envio da newsletter para "Em pausa"
			$query_rsUpdate = "UPDATE newsletters_historico SET estado = 5, emails_enviados='".$row_rsEnviados['total']."' WHERE id='".$row_rsNewsletter['id']."'";
			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
			$rsUpdate->execute();
			DB::close();
		}
		else {
			$query_rsUpdate = "UPDATE newsletters_atualizacoes SET newsletter_id_historico = '".$row_rsNewsletter['id']."', enviados = '".$row_rsEnviados['total']."', data = '$data' WHERE id = '1'";
			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
			$rsUpdate->execute();
			DB::close();
		}
	}

	// $data = date('Y-m-d H:i:s');
	// sendMail('webtech.dev@gmail.com', '', 'News check status ran at: '.$data, '', 'Crons - pintocruz.pt', '', '', '');
	
	// Para encerrar a instância do Iexplore
	// Funciona, não retirar.
	
	//echo "<script>window.open('', '_self', '');window.close();</script>";
	
	//
//}

?>
Feito.