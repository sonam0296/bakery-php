<?php 
$path = "";
if(isset($_POST['meuEmail']) && $_POST['meuEmail'] != "") {
	$path = "../";
}
require_once($path.'Connections/connADMIN.php'); 

header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");

if(!function_exists('randomCodeNewsTemp')) {
	function randomCodeNewsTemp($size = '24') {
	  $string = '';
	  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	  for ($i = 0; $i < $size; $i++)
	    $string .= $characters[mt_rand(0, (strlen($characters) - 1))];  

	  $query_rsExists = "SELECT id FROM news_emails_temp WHERE codigo = '$string'";
	  $rsExists = DB::getInstance()->prepare($query_rsExists);
	  $rsExists->execute();
	  $totalRows_rsExists = $rsExists->rowCount();
	  DB::close();

	  $query_rsExists2 = "SELECT id FROM news_emails WHERE codigo = '$string'";
	  $rsExists2 = DB::getInstance()->prepare($query_rsExists2);
	  $rsExists2->execute();
	  $totalRows_rsExists2 = $rsExists2->rowCount();
	  DB::close();

	  if($totalRows_rsExists == 0 && $totalRows_rsExists2 == 0)
	    return $string;
	  else
	    return randomCodeNewsTemp();
	}
}
function insereSubs($email, $origem, $resposta, $nome) {
	global $lang;
	$data = date("Y-m-d H:i:s");

	/****** LIMPAR REGISTOS NA TABELA TEMPORÁRIA COM MAIS DE 30 DIAS ******/
	$data_elimina = date('Y-m-d', (strtotime('-30 day', strtotime($data))));

	$query_rsDelete = "DELETE FROM news_emails_temp WHERE data <= '$data_elimina'";
	$rsDelete = DB::getInstance()->prepare($query_rsDelete);
	$rsDelete->execute();
	DB::close();
	/********************************************************************/

	$query_rsP = "SELECT * FROM news_emails_temp WHERE email='$email'";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();

	$query_rsP2 = "SELECT * FROM news_emails WHERE email='$email' AND visivel='1' AND aceita='1'";
	$rsP2 = DB::getInstance()->prepare($query_rsP2);
	$rsP2->execute();
	$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP2 = $rsP2->rowCount();
	DB::close();

	if($totalRows_rsP == 0 && $totalRows_rsP2 == 0) {
		$code = randomCodeNewsTemp();

		$query_rsMaxID = "SELECT MAX(id) FROM news_emails_temp";
		$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);
		$rsMaxID->execute();
		$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsMaxID = $rsMaxID->rowCount();
		DB::close();

		$id=$row_rsMaxID['MAX(id)']+1;

		$insertSQL = "INSERT INTO news_emails_temp (id, email, nome, data, origem, aceita, codigo) VALUES (:id, :email, :nome, :data, :origem, :aceita, :codigo)";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->bindParam(':id', $id, PDO::PARAM_INT); 
		$Result1->bindParam(':email', $email, PDO::PARAM_STR, 5); 
		$Result1->bindParam(':nome', $nome, PDO::PARAM_STR, 5); 
		$Result1->bindParam(':data', $data, PDO::PARAM_STR, 5); 
		$Result1->bindParam(':origem', $origem, PDO::PARAM_INT, 5); 
		$Result1->bindParam(':aceita', $aceita, PDO::PARAM_INT); 
		$Result1->bindParam(':codigo', $code, PDO::PARAM_STR, 5); 
		$Result1->execute();
		DB::close();
	}
	else {
		$code=$row_rsP['codigo'];
	}

	include_once("linguas/".$lang.".php");
	$className = 'Recursos_'.$lang;
	$Recursos = new $className();

	if($code && $totalRows_rsP2 == 0) {
		##################################### mail
		$formcontent = getHTMLTemplate("contacto.htm");
			
		$rodape = email_social();

		$texto = str_replace("#link#", ROOTPATH_HTTP."subs_valida.php?code=".$code, $Recursos->Resources['confirmar_subs_txt']);

		$mensagem_final = '
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
				<tr>
					<td style="font-family:arial; font-size:15px; line-height:16px; color:#444444; font-weight: bold;">'.$Recursos->Resources['confirmar_subs'].'</td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="1" cellspacing="0">
			  <tr>
				<td align="left" valign="middle">&nbsp;</td>
			  </tr>
			  <tr>
				<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:13px; line-height:16px; color:#444444;">'.$texto.'</td>
			  </tr>
			  <tr>
				<td align="left" valign="middle">&nbsp;</td>
			  </tr>
			</table>';	
		
		$titulo = $Recursos->Resources['confirmar_subs_tit'];
		$subject = $Recursos->Resources['confirmar_subs_subject'];
							
		$pagina_form = $origem."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."contactos.php'>".ROOTPATH_HTTP."contactos.php</a>";	

		$formcontent = str_replace ("#cpagina#",$pagina_form,$formcontent);
		$formcontent = str_replace ("#crodape#",$rodape,$formcontent);
		$formcontent = str_replace ("#ctitulo#",$titulo,$formcontent);
		$formcontent = str_replace ("#cmensagem#",$mensagem_final,$formcontent);
		$formcontent = str_replace ("#tit_mail_compr#",$Recursos->Resources["car_mail_7"],$formcontent);
		
		sendMail($email,'',$formcontent,$formcontent,$subject);
		####################################

		if($resposta == 1) echo "1###".$Recursos->Resources["mail_msg_5"];
	}
	else if($totalRows_rsP2 > 0){
		if($resposta == 1) echo "0###".$Recursos->Resources["mail_msg_4"];
	}
}
if(isset($_POST['meuEmail']) && $_POST['meuEmail'] != "") {
	if($_POST['valido'] == "") {
		$email = $_POST['meuEmail'];
		$nome = utf8_decode($_POST['nome']);
		insereSubs($email, "Subscrição newsletter", 1, $nome);
	}	
}
?>