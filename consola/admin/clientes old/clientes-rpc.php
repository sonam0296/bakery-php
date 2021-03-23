<?php include_once('../inc_pages.php'); ?>
<?php //include('../../../sendMail/send_mail.php'); ?>
<?php  
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?php

function randomCodeRecupera($size = '32') {
  $string = '';
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  for ($i = 0; $i < $size; $i++)
    $string .= $characters[mt_rand(0, (strlen($characters) - 1))];  

  $query_rsExists = "SELECT id FROM clientes WHERE cod_recupera =:string";
  $rsExists = DB::getInstance()->prepare($query_rsExists);
  $rsExists->bindParam(':string', $string, PDO::PARAM_STR, 5);
  $rsExists->execute();
  $totalRows_rsExists = $rsExists->rowCount();

  if($totalRows_rsExists == 0)
    return $string;
  else
    return randomCodeRecupera();
}

if($_POST['op'] == "enviaMail") {
	$id = $_POST['id'];
	
	if($id > 0) {
		$query_rsP = "SELECT * FROM clientes WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		
		if($totalRows_rsP > 0) { 	
			$language = $row_rsP['lingua'];
			if($language=='') $language="pt";
			
			include_once(ROOTPATH."linguas/".$language.".php");
			$className = 'Recursos_'.$language;
			$Recursos = new $className();
			
			##################################### mail
			$formcontent = getHTMLTemplate("contacto.htm");
			
			$email = $row_rsP['email'];
			
			$nome = $row_rsP['nome'];
			$telemovel = $row_rsP['telemovel'];

			$link = randomCodeRecupera();

			$query_rsUpdate = "UPDATE clientes SET cod_recupera = :link WHERE id=:id";
			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
			$rsUpdate->bindParam(':link', $link, PDO::PARAM_STR, 5);
			$rsUpdate->bindParam(':id', $id, PDO::PARAM_INT);
			$rsUpdate->execute();
			
			$mensagem = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tbody>
				<tr>
				  <td style="font-family:arial; font-size:14px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["dados_acesso"].'</td>
				</tr>
			  </tbody>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px; border-bottom:1px solid #d4dbe3;">
			  <tbody>
				<tr>
				  <td width="110" align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["ar_email"].'</td>
				  <td align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b;">'.$email.'</td>
				</tr>
				<tr>
				  <td width="110" align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["ar_password"].'</td>
				  <td align="left" style="font-family:arial; font-size:12px; line-height:auto; color:#3c3b3b;"><a href="'.ROOTPATH_HTTP.'login_recuperar.php?v='.$link.'">'.$Recursos->Resources["alterar_password"].'</a></td>
				</tr>
				<tr>
				  <td height="30">&nbsp;</td>
				  <td align="left">&nbsp;</td>
				</tr>
			  </tbody>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:30px;">
			  <tbody>
				<tr>
				  <td style="font-family:arial; font-size:14px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["dados_pessoais"].'</td>
				</tr>
			  </tbody>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px">
			  <tbody>
				<tr>
				  <td width="110" align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["ar_nome"].'</td>
				  <td align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b;">'.$nome.'</td>
				</tr>
				<tr>
				  <td width="110" align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["ar_telemovel"].'</td>
				  <td align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b;">'.$telemovel.'</td>
				</tr>
			  </tbody>
			</table>';
			
			$rodape = email_social();
			
			$titulo = $Recursos->Resources["dados_acesso"];
			
			$formcontent = str_replace ("#ctitulo#",$Recursos->Resources["ar_mail_reg_novo_tit"],$formcontent);
			$formcontent = str_replace ("#cmensagem#",$mensagem,$formcontent);
			$formcontent = str_replace ("#tit_mail_compr#",$Recursos->Resources["car_mail_7"],$formcontent);
			$formcontent = str_replace ("#crodape#",$rodape,$formcontent);		

			$pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."login.php</a>";	
			$formcontent = str_replace ("#cpagina#",$pagina_form,$formcontent);
			
			$para = $email;
			$subject = $Recursos->Resources["ar_mail_reg_novo_tit"]." - www.".SERVIDOR;
			
			sendMail($para,'',$formcontent,$mensagem,$subject,'','');
			####################################
		}
	}
}

if($_POST['op'] == "validaRegisto") {
	$id = $_POST['id'];
	
	if($id > 0) {
		$query_rsP = "SELECT * FROM clientes WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		
		if($totalRows_rsP > 0) {	
			$query_rsUpdate = "UPDATE clientes SET ativo = 1, validado = 1 WHERE id=:id";
			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
			$rsUpdate->bindParam(':id', $id, PDO::PARAM_INT);
			$rsUpdate->execute();

			$language = $row_rsP['lingua'];
			if($language == '') $language = "pt";
			
			include_once(ROOTPATH."linguas/".$language.".php");
			$className = 'Recursos_'.$language;
			$Recursos = new $className();

			$query_rsTextoEmail = "SELECT * FROM clientes_textos_$language WHERE id=1";
			$rsTextoEmail = DB::getInstance()->prepare($query_rsTextoEmail);
			$rsTextoEmail->execute();
			$row_rsTextoEmail = $rsTextoEmail->fetch(PDO::FETCH_ASSOC);
			
			##################################### mail
			$formcontent = getHTMLTemplate("contacto.htm");
			
			$email = $row_rsP['email'];
			$nome = $row_rsP['nome'];

			$link = randomCodeRecupera();

			$query_rsUpdate = "UPDATE clientes SET cod_recupera = :link WHERE id=:id";
			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
			$rsUpdate->bindParam(':link', $link, PDO::PARAM_STR, 5);
			$rsUpdate->bindParam(':id', $id, PDO::PARAM_INT);
			$rsUpdate->execute();
			
			if($row_rsTextoEmail["texto"]) {
				$texto = str_replace('#nome#', $nome, $row_rsTextoEmail["texto"]);

				$mensagem = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody>
					<tr>
					  <td style="font-family:arial; font-size:14px; line-height:20px; color:#3c3b3b;">'.$texto.'</td>
					</tr>
					<tr>
					  <td height="30">&nbsp;</td>
					</tr>
				  </tbody>
				</table>
				';
			}

			$mensagem .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tbody>
				<tr>
				  <td style="font-family:arial; font-size:14px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["dados_acesso"].'</td>
				</tr>
			  </tbody>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
			  <tbody>
				<tr>
				  <td width="110" align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["ar_email"].'</td>
				  <td align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b;">'.$email.'</td>
				</tr>
				<tr>
				  <td width="110" align="left" style="font-family:arial; font-size:12px; line-height:20px; color:#3c3b3b; font-weight:bold">'.$Recursos->Resources["ar_password"].'</td>
				  <td align="left" style="font-family:arial; font-size:12px; line-height:auto; color:#3c3b3b;"><a href="'.ROOTPATH_HTTP.'login_recuperar.php?v='.$link.'">'.$Recursos->Resources["alterar_password"].'</a></td>
				</tr>
				<tr>
				  <td height="30">&nbsp;</td>
				  <td align="left">&nbsp;</td>
				</tr>
			  </tbody>
			</table>';
			
			$rodape = email_social();

			$titulo=$Recursos->Resources["dados_acesso"];	
			
			$formcontent = str_replace ("#ctitulo#",$titulo,$formcontent);
			$formcontent = str_replace ("#cmensagem#",$mensagem,$formcontent);
			$formcontent = str_replace ("#tit_mail_compr#",$Recursos->Resources["car_mail_7"],$formcontent);
			$formcontent = str_replace ("#crodape#",$rodape,$formcontent);

			$pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."login.php</a>";	
			$formcontent = str_replace ("#cpagina#",$pagina_form,$formcontent);
			
			$para = $email;

			$subject = $row_rsTextoEmail['assunto'];
			if(!$subject) {
				$subject = $Recursos->Resources["novo_registo_tit_cliente"];
			}
			
			sendMail($para,'',$formcontent,$mensagem,$subject,'','');
			####################################
		}
	}
}

DB::close();

?>