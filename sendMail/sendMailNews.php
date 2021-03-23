<?php 

//DADOS SMTP
$smtp    = "smtp.gmail.com";
$usuario = "rvkumarsmtp@gmail.com";
$senha   = "8104637328";

require_once 'smtp.php';

$mail = new SMTP;
$mail->Delivery('relay');
// $mail->Relay($smtp, $usuario, $senha, 465, 'login', 'tls');
$mail->Relay($smtp, $usuario, $senha, 25, 'login', false);
$mail->TimeOut(10);
//$mail->Priority('normal');


//AUTENTICAR EMAIL
function sendMailNews($para, $de='', $mensagem, $mensagem_text, $assunto, $reply='', $email_bounce='', $id_news = 0, $id_agendamento = 0, $email = '', $de_nome = '') {
	
	global $mail;
	
	//elimina tudo para não manter em cada envio a informação do envio anterior
	$mail->delheader('Reply-To');
	$mail->delheader('Return-path');
	$mail->delheader('Errors-To');
	$mail->delheader('NewsCod');
	$mail->delheader('NewsAgenCod');
	$mail->delheader('NewsEmail');
	$mail->delto(); // !important!!!!
	
	if($reply) $mail->addheader('Reply-To', $reply);	

	if($email_bounce) {
		$mail->addheader('Return-path', $email_bounce); 
		$mail->addheader('Errors-To', $email_bounce); 
	}

	if($id_news > 0)
		$mail->addheader('NewsCod', $id_news."#");

	if($id_agendamento > 0)
		$mail->addheader('NewsAgenCod', $id_agendamento."#");

	if($email != '')
		$mail->addheader('NewsEmail', $email."#");

	//Não indicar um endereço de remetente diferente do endereço do utilizador indicado para não ir parar ao anti spam classificado como forged sender
	
	if(!$de) $de='info@webtech-evolution.com';
	if(!$de_nome) $de_nome='Webtech-Evolution';
	$mail->From($de, $de_nome);
	
	$para2=explode(',',$para);
	for($i=0; $i<count($para2); $i++){
		$mail->AddTo(trim($para2[$i]));
	}
	
	$mail->Html($mensagem);
	$mail->Text(strip_tags($mensagem_text));
	
	if($mail->Send($assunto)) {
		return true;
	}
	else {
		return false;
	}
}
?>