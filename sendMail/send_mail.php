<?php

//AUTENTICAR EMAIL

function sendMail($para,$de='',$mensagem,$mensagem_text,$assunto,$paracc='',$parabcc='',$reply='',$file=''){

	

	//DADOS SMTP

	$smtp    = "smtp.gmail.com";

	$usuario = "rvkumarsmtp@gmail.com";

	$senha   = "8104637328";

	

	require_once 'smtp.php';

	

	$mail = new SMTP;

	$mail->Delivery('relay');

	$mail->Relay($smtp, $usuario, $senha, 25, 'login', false);

	$mail->TimeOut(10);

	//$mail->Priority('normal');

	

	if($reply) $mail->addheader('Reply-To', $reply);	

	

	if(!$de) $de='rvkumarsmtp@gmail.com';

	$mail->From($de, 'Webtech-Evolution');

	

	$para2=explode(',',$para);

	for($i=0; $i<count($para2); $i++){

		$mail->AddTo(trim($para2[$i]));

	}

	

	if($paracc){

		$paracc2=explode(',',$paracc);

		for($i=0; $i<count($paracc2); $i++){

			$mail->addcc(trim($paracc2[$i]));

		}

	}

	

	if($parabcc){

		$parabcc2=explode(',',$parabcc);

		for($i=0; $i<count($parabcc2); $i++){

			$mail->addbcc(trim($parabcc2[$i]));

		}

	}

	

	if($file){

		for($i=0; $i<count($file); $i++){

			if($file[$i][0] && file_exists($file[$i][0])){

				@chmod($file[$i][0], 0777);

				$mail->attachfile($file[$i][0], $name = $file[$i][1], $mimetype = 'autodetect', $disposition = 'attachment', $encoding = 'base64');

			}

		}

	}

	

	$mail->Html($mensagem);

	$mail->Text(strip_tags($mensagem_text));

	

	if($mail->Send($assunto))

		return true;

	else

		return false;

}

?>