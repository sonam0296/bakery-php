<?php

//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

require_once('../../../Connections/connADMIN.php');
include_once(ROOTPATH.'sendMail/sendMailNews.php');
include_once(ROOTPATH_ADMIN.'newsletter/newsletter-funcoes-logs.php');

if(!isset($_SESSION)) {
  session_start();
}

$data_aviso = date('Y-m-d H:i:s');
sendMail('webtech.dev@gmail.com', '', 'News proceed shipping ran at: '.$data_aviso, '', 'Crons - pintocruz.pt', '', '', '');

//exit();

//Se não houver esta flag, significa que a página foi chamada através do servidor a cada hora e é para fazer um reset ao nº de emails enviados na última hora
//Se existir a flag, significa que a página foi chamada através dela própria e não é para fazer um reset ao nº de emails enviados na última hora
if(!isset($_GET['emails_reset'])) {
	$insertSQL = "UPDATE newsletters_config SET emails_sent=0";
	$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
	$rsInsertSQL->execute();
	//DB::close();

	//Reset ao nº de emails enviados por hora para cada newsletter
	$query_rsUpd = "UPDATE newsletters_historico SET envios_hora = 0";
	$rsUpd = DB::getInstance()->prepare($query_rsUpd);
	$rsUpd->execute();
	//DB::close();
}

//Verificar limites de emails enviados
$query_rsE = "SELECT * FROM newsletters_config";
$rsE = DB::getInstance()->prepare($query_rsE);
$rsE->execute();
$row_rsE = $rsE->fetch(PDO::FETCH_ASSOC);
$totalRows_rsE = $rsE->rowCount();
//DB::close();

if($totalRows_rsE > 0) {
	$max_emails = $row_rsE['max_emails'];
	$emails_sent = $row_rsE['emails_sent'];
}


$data=date('Y-m-d');
$hora=date('H:i:s');

//Fazer um teste inicial para determinar se ainda é possível enviar emails.
if($emails_sent < $max_emails) {
	//verifica se está a enviar alguma newsletter - se for igual a 0 não está nada a enviar
	$query_rsN = "SELECT * FROM newsletters_historico WHERE estado='2'";
	$rsN = DB::getInstance()->prepare($query_rsN);
	$rsN->execute();
	$totalRows_rsN = $rsN->rowCount();
	//DB::close();

	if($totalRows_rsN==0) {
		//Verificar se existe algum envio de uma newsletter interrompido, se existir vamos continuar o envio dessa mesma
		$query_rsP = "SELECT newsletters_historico.*, newsletters.titulo FROM newsletters_historico, newsletters WHERE newsletters_historico.estado='5' AND (newsletters_historico.limite=0 OR (newsletters_historico.limite>0 AND newsletters_historico.limite>newsletters_historico.envios_hora)) AND (newsletters_historico.data<'$data' OR (newsletters_historico.data='$data' AND newsletters_historico.hora<='$hora')) AND newsletters_historico.newsletter_id=newsletters.id ORDER BY newsletters_historico.data_inicio ASC, newsletters_historico.hora_inicio ASC";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		//DB::close();

		if($totalRows_rsP > 0) {
			$newsletter_id = $row_rsP['newsletter_id'];
			$newsletter_id_historico = $row_rsP['id'];
			$nome_from = $row_rsP['nome_from'];
			$email_from=$row_rsP['email_from'];
			$email_reply=$row_rsP['email_reply'];
			$email_bounce=$row_rsP['email_bounce'];
			$news_content_txt=$row_rsP['titulo'];
			$titulo=$row_rsP['titulo'];
			$data_inicio=$row_rsP['data_inicio'];
			$hora_inicio=$row_rsP['hora_inicio'];
			$estado = 5;
			$username = $row_rsP['utilizador'];
			$limite = $row_rsP['limite'];
			$envios_hora = $row_rsP['envios_hora'];
			$tipo_envio = $row_rsP["tipo_envio"]; //Interno | Mailgun

			//se mailgun o limite máximo de envio "em massa" são 1000 emails
			if($tipo_envio == 2 && ($limite > 80 || $limite == 0)){ //limita a 80 para envio através do Mailgun
				$limite = 80;
			}
		}
		else {
			//procura pela próxima newsletter a enviar - se existir procede ao envio
			$query_rsP = "SELECT newsletters_historico.*, newsletters.titulo FROM newsletters_historico, newsletters WHERE newsletters_historico.estado='1' AND (newsletters_historico.limite=0 OR (newsletters_historico.limite>0 AND newsletters_historico.limite>newsletters_historico.envios_hora)) AND (newsletters_historico.data<'$data' OR (newsletters_historico.data='$data' AND newsletters_historico.hora<='$hora')) AND newsletters_historico.newsletter_id=newsletters.id ORDER BY newsletters_historico.data ASC, newsletters_historico.hora ASC";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
			$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsP = $rsP->rowCount();
			//DB::close();

			if($totalRows_rsP > 0) {
				$newsletter_id = $row_rsP['newsletter_id'];
				$newsletter_id_historico = $row_rsP['id'];
  			$nome_from = $row_rsP['nome_from'];
				$email_from=$row_rsP['email_from'];
				$email_reply=$row_rsP['email_reply'];
				$email_bounce=$row_rsP['email_bounce'];
				$news_content_txt=$row_rsP['titulo'];
				$titulo=$row_rsP['titulo'];
				$data_inicio=$row_rsP['data_inicio'];
				$hora_inicio=$row_rsP['hora_inicio'];
				$estado = 1;
				$username = $row_rsP['utilizador'];
				$limite = $row_rsP['limite'];
				$envios_hora = $row_rsP['envios_hora'];
				$tipo_envio = $row_rsP["tipo_envio"]; //Interno | Mailgun

				//se mailgun o limite máximo de envio "em massa" são 1000 emails
				if($tipo_envio == 2 && ($limite > 80 || $limite == 0)){ //limita a 80 para envio através do Mailgun
					$limite = 80;
				}
			}
		}

		//teste para saber se está a correr
		// sendMail('webtech.dev@gmail.com', '', 'News proceed full shipping: '.$totalRows_rsP, '', 'Crons - pintocruz.pt', '', '', '');


		//UPDATE 7/08/2017

		/*Verifica se a newsletter tem conteúdo associado.
		Se não tiver não envia!! (por causa de erro causado pelo conteúdo=0 na newsletter)*/

		$tem_conteudo=0;

		$query_rsNewsletter = "SELECT conteudo FROM newsletters WHERE id=:id";
		$rsNewsletter = DB::getInstance()->prepare($query_rsNewsletter);
		$rsNewsletter->bindParam(':id', $newsletter_id, PDO::PARAM_INT);
		$rsNewsletter->execute();
		$row_rsNewsletter = $rsNewsletter->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsNewsletter = $rsNewsletter->rowCount();
		//DB::close();

		if($row_rsNewsletter['conteudo']>0){
			$query_rsCont = "SELECT id FROM news_conteudo WHERE id=:id";
			$rsCont = DB::getInstance()->prepare($query_rsCont);
			$rsCont->bindParam(':id', $row_rsNewsletter['conteudo'], PDO::PARAM_INT);
			$rsCont->execute();
			$totalRows_rsCont = $rsCont->rowCount();
			//DB::close();

			if($totalRows_rsCont>0){
				$tem_conteudo=1;
			}
		}

		////

		//Depois de determinar qual a newsletter a enviar (e se existir), vamos proceder ao envio da mesma
		if($totalRows_rsP > 0 && $tem_conteudo == 1) {

			// verificar qual o tipo de envio: 1- sendMail; 2- Mailgun
			// se for do tipo mailgun faz include do script
			if($tipo_envio == 2) { //
				include_once(ROOTPATH."mailgun/mailgun.php");
			}

			//Selecionar todos os emails restantes de todas as listas escolhidas que ainda não tinham recebido a newsletter.
			$query_rsP = "SELECT ne.id, ne.email, ne.codigo, ne.nome FROM news_emails ne, newsletters_historico nh WHERE nh.id='$newsletter_id_historico' AND ne.visivel=1 AND ne.aceita=1 AND ne.id IN (SELECT DISTINCT(ne.id) FROM news_emails ne, news_emails_listas nel, newsletters_historico_listas nhl, newsletters_historico nh WHERE nh.id='$newsletter_id_historico' AND nh.id=nhl.newsletter_historico AND nel.lista=nhl.lista and nel.email=ne.id) AND ne.id NOT IN (SELECT email_id FROM newsletters_vistos WHERE newsletter_id_historico='$newsletter_id_historico') ORDER BY ne.id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->execute();
			$totalRows_rsP = $rsP->rowCount();
			//DB::close();

			if($totalRows_rsP > 0) {

				//Se a newsletter tiver o estado "aguardar envio" ou "pausa" antes de ser enviada, vamos verificar se o número de destinatários se manteve ou não.
				if($estado == 1 || $estado == 5) {
					$query_rsE = "SELECT emails_total, emails_enviados FROM newsletters_historico WHERE id='$newsletter_id_historico'";
					$rsE = DB::getInstance()->prepare($query_rsE);
					$rsE->execute();
					$row_rsE = $rsE->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsE = $rsE->rowCount();
					//DB::close();

					//O número de destinatários foi alterado, vamos guardar na base de dados o novo número
					if($estado == 1 && $row_rsE['emails_total'] != $totalRows_rsP) {
						$query_rsU = "UPDATE newsletters_historico SET emails_total=:emails_total WHERE id='$newsletter_id_historico'";
						$rsU = DB::getInstance()->prepare($query_rsU);
						$rsU->bindParam(':emails_total', $totalRows_rsP, PDO::PARAM_INT);
						$rsU->execute();
						//DB::close();
					}

					//Se a newsletter já tiver sido enviada para alguns, vamos verificar se o total previsto no início é igual ao nº de enviados mais os que vamos enviar agora
					if($estado == 5 && $row_rsE['emails_total'] != ($totalRows_rsP + $row_rsE['emails_enviados'])) {
						/*$query_rsU = "UPDATE newsletters_historico SET emails_total=emails_total+:emails_total WHERE id='$newsletter_id_historico'";
						$rsU = DB::getInstance()->prepare($query_rsU);
						$rsU->bindParam(':emails_total', $totalRows_rsP, PDO::PARAM_INT);
						$rsU->execute();
						//DB::close();*/

						$emails_total2=$totalRows_rsP + $row_rsE['emails_enviados'];
						
						$query_rsU = "UPDATE newsletters_historico SET emails_total=:emails_total WHERE id='$newsletter_id_historico'";
						$rsU = DB::getInstance()->prepare($query_rsU);
						$rsU->bindParam(':emails_total', $emails_total2, PDO::PARAM_INT);
						$rsU->execute();
						//DB::close();
					}
				}

				//Verificar em que estado está esta newsletter. Se estiver em pausa não vamos definir outra vez a data e hora de inicio. Se estiver a aguardar envio, temos de definir a hora e data de inicio
				if($estado == 5) {
					//actualiza o estado da newsletter e cria um log
					$insertSQL = "UPDATE newsletters_historico SET estado='2' WHERE id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
					//DB::close();

					$que_fez="continuou o envio da newsletter às ".$data." // ".$hora;
					$class_news_logs->logs_agendamentos($username, $newsletter_id, $que_fez, $titulo);
					
					//actualiza a data de envio na newsletter
					$insertSQL = "UPDATE newsletters SET data_envio='$data' WHERE id='$newsletter_id'";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
					//DB::close();
				}
				else {
					//actualiza o estado e a data de inicio - cria o log tb
					$insertSQL = "UPDATE newsletters_historico SET data_inicio='$data', hora_inicio='$hora', estado='2' WHERE id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
					//DB::close();

					$que_fez="iniciou o envio da newsletter às ".$data." // ".$hora;
					$class_news_logs->logs_agendamentos($username, $newsletter_id, $que_fez, $titulo);
					
					//actualiza a data de envio na newsletter
					$insertSQL = "UPDATE newsletters SET data_envio='$data' WHERE id='$newsletter_id'";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
					//DB::close();
				}

				$emails_enviados = 0;
				$suspendeu = 0;

				//Se o limite for 0, significa que não há limites por hora neste agendamento. Como tal, atribuímos o valor de -1 para o ciclo correr na mesma
				if($limite == 0)
					$envios_hora = -1;

				//Enquanto tiver emails para enviar e não tiver atingido o limite, continua a enviar emails
				//mailgun vars
				$array_emails = array();
				$data_to = array();

				while(($row = $rsP->fetch()) && ($emails_sent < $max_emails) && ($suspendeu == 0) && ($envios_hora < $limite)) {

					//verifica se ainda está activa a newsletter - podem suspender o envio enquanto está activo
					$query_rsProc = "SELECT * FROM newsletters_historico WHERE estado='2' AND id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
					$rsProc = DB::getInstance()->prepare($query_rsProc);
					$rsProc->execute();
					$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsProc = $rsProc->rowCount();
					//DB::close();
					
					if($totalRows_rsProc>0) {
						$codigo = $row['codigo'];

						$news_content=file_get_contents(HTTP_DIR.'/consola/admin/newsletter/newsletter-edit.php?id='.$newsletter_id.'&hist='.$newsletter_id_historico.'&codigo='.$codigo);
						$news_content.='<img src="'.HTTP_DIR.'/consola/admin/newsletter/newsletter-proceder-visto.php?id='.$newsletter_id_historico.'&code='.$codigo.'" width="1" height="1" border="0" />';

						$envia=trim($row['email']);

						//corrige erros
						$envia = str_replace(array(" ", "\r\n", "\r", "\n"), "", $envia);

						$email_id=$row['id'];
						
						// verificar qual o tipo de envio: 1- sendMail; 2- Mailgun
						if($tipo_envio == 1) { // envia normalmente por sendmail
							$news_content = str_replace("#name#", $row['nome'], $news_content);
							sendMailNews($envia, $email_from, $news_content, $news_content_txt, $titulo, $email_reply, $email_bounce, $newsletter_id, $newsletter_id_historico, $envia, $nome_from);
						}
						else { // envia através do mailgun
							
							//verifica se o email é válido para acrescentar ao array do mailgun
							$email_is_valid = filter_var($envia, FILTER_VALIDATE_EMAIL);

							if($email_is_valid){
								array_push($array_emails, $envia);

								$news_content = str_replace($codigo, "#codigo#", $news_content);
								$data_to[$envia] = array("email"=>$envia, "codigo"=>$codigo, "name"=>$row['nome']);

								// não envia 1 a 1... envia no final todos de uma vez
								// if($nome_from) $email_from = utf8_encode($nome_from).' <'.$email_from.'>';
								// mg_send($newsletter_id, $envia, $email_from, $news_content, $news_content_txt, $titulo, $email_reply, $email_bounce, $newsletter_id_historico);
							}
						}

						//Esta variável controla o nº de emails enviados nesta ronda
						$emails_enviados++;

						//Esta variável controla o nº de emails enviados na última hora
						$emails_sent++;

						//Controla o nº de emails enviados nesta hora para uma newsletter em específico. Apenas se houver limite é que vamos incrementar a variável
						if($limite > 0)
							$envios_hora++;
						
						$data_envio=date('Y-m-d H:i:s');

						//Registar na tabela "newsletters_vistos"
						$insertSQL = "INSERT INTO newsletters_vistos (id, newsletter_id_historico, newsletter_id, email_id, data_envio, visto) VALUES ('', :newsletter_id_historico, :newsletter_id, :email_id, :data_envio, 0)";
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->bindParam(':newsletter_id_historico', $newsletter_id_historico, PDO::PARAM_INT);
						$rsInsert->bindParam(':newsletter_id', $newsletter_id, PDO::PARAM_INT);		
						$rsInsert->bindParam(':email_id', $email_id, PDO::PARAM_STR, 5);
						$rsInsert->bindParam(':data_envio', $data_envio, PDO::PARAM_STR, 5);	
						$rsInsert->execute();
						//DB::close();
					}
					else {
						$suspendeu=1;
					}
				}

				// envia por mailgun
				// envia para todos os emails de uma vez... máximo é 1000 emails!!
				if($tipo_envio == 2) {
					if($nome_from) $email_from = utf8_encode($nome_from).' <'.$email_from.'>';
					$emails_to = implode(",", $array_emails);
					mg_send($newsletter_id, $emails_to, $email_from, $news_content, $news_content_txt, $titulo, $email_reply, $email_bounce, $newsletter_id_historico, $data_to);
				}


				//Se não houver limite, voltamos a dar o valor de 0 á variável (como estava antes do ciclo)
				if($limite == 0)
					$envios_hora = 0;

				//actualiza a tabela com incrementador de envio
				$insertSQL = "UPDATE newsletters_historico SET emails_enviados=emails_enviados+'$emails_enviados', envios_hora='$envios_hora' WHERE id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->execute();
				//DB::close();

				//actualiza o nº de emails enviados na última hora
				$insertSQL = "UPDATE newsletters_config SET emails_sent='$emails_sent'";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->execute();
				//DB::close();

				//Verificar se o número de emails enviados é igual ao números de emails inscritos. Se não for, a newsletter continua com o mesmo estado
				$query_rsP = "SELECT emails_enviados, emails_total FROM newsletters_historico WHERE id='$newsletter_id_historico'";
				$rsP = DB::getInstance()->prepare($query_rsP);
				$rsP->execute();
				$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsP = $rsP->rowCount();
				//DB::close();

				if($totalRows_rsP > 0) {
					if($row_rsP['emails_enviados'] >= $row_rsP['emails_total']) {
						//Todos os emails foram enviados. Alterar o estado da newsletter para 'Enviado' e guardar a hora de fim
						$data_fim = date('Y-m-d');
						$hora_fim = date('H:i:s');

						$insertSQL = "UPDATE newsletters_historico SET data_fim='$data_fim', hora_fim='$hora_fim', estado='3' WHERE id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
						$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
						$rsInsertSQL->execute();
						//DB::close();
						
						$que_fez="finalizou envio da newsletter às ".$data_fim." // ".$hora_fim;
						$class_news_logs->logs_agendamentos($username, $newsletter_id, $que_fez, $titulo);
					}
					//Se a newsletter não tiver sido enviada para todos os emails e a mesma não tiver sido suspensa, vamos alterar o estado para "pausa"
					else {
						if($suspendeu == 0) {
							$insertSQL = "UPDATE newsletters_historico SET estado='5' WHERE id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
							$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
							$rsInsertSQL->execute();
							//DB::close();
						}
					}
				}
			}
			else {
				//Todos os emails foram enviados. Alterar o estado da newsletter para 'Enviado' e guardar a hora de fim
				$data_fim = date('Y-m-d');
				$hora_fim = date('H:i:s');

				$insertSQL = "UPDATE newsletters_historico SET emails_total = emails_enviados, data_fim='$data_fim', hora_fim='$hora_fim', estado='3' WHERE id='$newsletter_id_historico' AND newsletter_id='$newsletter_id'";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->execute();
				//DB::close();
				
				$que_fez="finalizou envio da newsletter às ".$data_fim." // ".$hora_fim;
				$class_news_logs->logs_agendamentos($username, $newsletter_id, $que_fez, $titulo);
			}
		}
	}
}

//Verificar se ainda existe possibilidade de enviar mais emails
// if($emails_sent < $max_emails) {
// 	//verifica se há outra news para iniciar - se tiver corre novamente a página
// 	$data=date('Y-m-d');
// 	$hora=date('H:i:s');
	
// 	$query_rsP = "SELECT newsletters_historico.*, newsletters.titulo FROM newsletters_historico, newsletters WHERE (newsletters_historico.estado='1' OR newsletters_historico.estado='5') AND (newsletters_historico.limite=0 OR (newsletters_historico.limite>0 AND newsletters_historico.limite>newsletters_historico.envios_hora)) AND (newsletters_historico.data<'$data' OR (newsletters_historico.data='$data' AND newsletters_historico.hora<'$hora')) AND newsletters_historico.newsletter_id=newsletters.id ORDER BY newsletters_historico.data ASC, newsletters_historico.hora ASC";
// 	$rsP = DB::getInstance()->prepare($query_rsP);
// 	$rsP->execute();
// 	$totalRows_rsP = $rsP->rowCount();
// 	//DB::close();
	
// 	if($totalRows_rsP>0){		
// 		header('Location: newsletter-proceder-envio.php?emails_reset=0');		
// 	}
// 	else {
// 		// Para encerrar a instância do Iexplore
// 		// Funciona, não retirar.
		
// 		echo "<script>window.open('', '_self', '');window.close();</script>";
		
// 		//
// 	}
// }
// else {
// 	// Para encerrar a instância do Iexplore
// 	// Funciona, não retirar.
	
// 	echo "<script>window.open('', '_self', '');window.close();</script>";
	
// 	//
// }

	// Para encerrar a instância do Iexplore
	// Funciona, não retirar.
	
	//echo "<script>window.open('', '_self', '');window.close();</script>";
	
	//

DB::close();
?>
Feito.