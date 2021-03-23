<?php

class User {

	/** varávies da classe */

	private static $instance = NULL;

	

	// construtor onde se pode inicializar as variáveis

	private function __construct() {}

	

	public static function getInstance() {

		if (!self::$instance) {

			self::$instance = new self();

		}

		

		return self::$instance;

	}

	

	public static function login($user, $pass, $lembrar = 0, $carrinho = 0, $recupera_carrinho = 0) {

		global $login_post, $class_carrinho, $cookie_secure;



		$query_rsCliente = "SELECT * FROM clientes WHERE email=:email";

		$rsCliente = DB::getInstance()->prepare($query_rsCliente);

		$rsCliente->bindParam(':email', $user, PDO::PARAM_STR, 5);

		$rsCliente->execute();

		$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsCliente = $rsCliente->rowCount();

	

		if($totalRows_rsCliente > 0) {

			if($row_rsCliente['validado'] > 0 && $row_rsCliente['ativo'] > 0) {	

				$pass = hash('sha256', $pass);



				$password_db = $row_rsCliente['password'];

				$password_salt_db = $row_rsCliente['password_salt'];

		

				$password_final = hash('sha256', $password_salt_db . $pass);

				

				if($password_final == $password_db) {

					$data_hoje = date('Y-m-d H:i:s');		

					

					$insertSQL = "UPDATE clientes SET ultima_entrada=:data WHERE email=:user";

					$Result1 = DB::getInstance()->prepare($insertSQL);

					$Result1->execute(array(':data'=>$data_hoje, ':user'=>$user));



					//Mudar para a moeda do cliente

					if(tableExists(DB::getInstance(), 'moedas')) {

						$query_rsPais = "SELECT moeda FROM paises WHERE id=:id";

						$rsPais = DB::getInstance()->prepare($query_rsPais);

						$rsPais->bindParam(':id', $row_rsCliente['pais'], PDO::PARAM_INT);

						$rsPais->execute();

						$row_rsPais = $rsPais->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsPais = $rsPais->rowCount();



						if($totalRows_rsPais > 0) {

							$query_rsMoeda = "SELECT abreviatura, simbolo FROM moedas WHERE id=:id";

							$rsMoeda = DB::getInstance()->prepare($query_rsMoeda);

							$rsMoeda->bindParam(':id', $row_rsPais['moeda'], PDO::PARAM_INT);

							$rsMoeda->execute();

							$row_rsMoeda = $rsMoeda->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsMoeda = $rsMoeda->rowCount();



							if($totalRows_rsMoeda > 0) {

								setcookie("SITE_currency", $row_rsMoeda['abreviatura']."-".$row_rsMoeda['simbolo'], time()+3600*24*30*12*5, '/', '', $cookie_secure, true);

							}

						}

					}

														

					if($lembrar == '1') {

						setcookie("SITE_user", $user, time()+3600*24*30*12*5, '/', '', $cookie_secure, true);

						setcookie("SITE_pass", $password_final, time()+3600*24*30*12*5, '/', '', $cookie_secure, true);

					}

					else {

						setcookie("SITE_user", $user, 0, '/', '', $cookie_secure, true);

						setcookie("SITE_pass", $password_final, 0, '/', '', $cookie_secure, true);	

					}

					

					$cliente_valido = self::isValidUser($row_rsCliente['id']);		



					if(tableExists(DB::getInstance(), 'lista_desejo')) {

						$wish_session = $_COOKIE[WISHLIST_SESSION];

						if($wish_session){

							$insertSQL = "UPDATE lista_desejo SET cliente = $row_rsCliente[id] WHERE session = '$wish_session'";

							$Result1 = DB::getInstance()->prepare($insertSQL);

							$Result1->execute();



							$insertSQL = "UPDATE lista_desejo SET session = '$wish_session' WHERE cliente = $row_rsCliente[id] AND (session=0 OR session='' OR session IS NULL)";

							$Result1 = DB::getInstance()->prepare($insertSQL);

							$Result1->execute();

						}

					}



					//Ao fazer login, temos de atualizar o ID do cliente no carrinho (caso tenha adicionado produtos sem login)

					$class_carrinho->carregaCarrinhoLogin($row_rsCliente['id']);

					

					DB::close();



					if($cliente_valido == 0) {

						header("Location: area-reservada-dados.php?erro=1");	

						exit();

					}

					else {

						if($recupera_carrinho == 1) {

							header("Location: carrinho.php?rc=1");	

							exit();

						}

						else if($carrinho == 1) {

							header("Location: carrinho-comprar.php");	

							exit();

						}

						else {

							header('location: area-reservada.php');

							exit();

						}

					}

				}

				else {

					$login_post = "-1";

				}

			}

			else if($row_rsCliente['validado'] == 0) {

				if($row_rsCliente['tipo'] == 2) {

					$login_post = "-2";

				}

				else {

					$login_post = $row_rsCliente['id'];

				}

			}

			else if($row_rsCliente['ativo'] == 0) {

				$login_post = "-1";

			}

		}

		else {

			$login_post = "-1";

		}



		DB::close();



		return $login_post;

	}



	public static function registo($tipo, $nome, $email, $password, $telemovel, $telefone, $nif, $morada, $localidade, $codpostal,$roll, $pais, $atividade, $atividade2, $pessoa, $news, $titulo_pag, $referer_code) {

		global $extensao, $Recursos, $registo_post;



		$query_rsEmail = "SELECT * FROM clientes WHERE email='$email'";

		$rsEmail = DB::getInstance()->query($query_rsEmail);

		$row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsEmail = $rsEmail->rowCount();	



		if($totalRows_rsEmail > 0 && $row_rsEmail['validado'] == 1) {

			$registo_post = "-2";

		}

		else if($totalRows_rsEmail > 0 && $row_rsEmail['validado'] == 0) {

			if($row_rsEmail['tipo'] == 1) {

				$registo_post = $row_rsEmail['id'];

			}

			else {

				$registo_post = "-3";

			}

		}

		else {			

			if($lang == '') { 

				$lang = 'pt'; 

			}

	

			$salt = self::createSalt();	

			$hash = hash('sha256', $password);

			

			$password_final = hash('sha256', $salt . $hash);

			$user_activation_hash = sha1(uniqid(mt_rand(), true));

			

			$data = date('Y-m-d H:i:s');



			$tipo_txt = $Recursos->Resources["particular"];

			$texto_mail = $Recursos->Resources["reg_mail_txt"];

			$validado = 0;

			$registo_post = "-1";



			if($tipo == 2) {

				$tipo_txt = $Recursos->Resources["profissional"];

				$texto_mail = $Recursos->Resources["reg_mail_txt2"];

				$validado = 0;

				$registo_post = "-4";

			}



			do {

				$cod_bonus = self::geraSenha(8, true, true);

				

				$rsGeraCod = "SELECT id FROM clientes WHERE cod_bonus='$cod_bonus'";

				$rsGeraCod = DB::getInstance()->prepare($rsGeraCod);

				$rsGeraCod->execute();

				$totalRows_rsGeraCod = $rsGeraCod->fetch(PDO::FETCH_ASSOC);

			} while($totalRows_rsGeraCod > 0);



			//Verificar se existe um referer para o registo

			$referer_id = 0;

			if($referer_code != '') {

		    $query_rsReferer = "SELECT id FROM clientes WHERE cod_bonus = :cod_bonus AND validado = 1 AND ativo = 1";

		    $rsReferer = DB::getInstance()->prepare($query_rsReferer);

		    $rsReferer->bindParam(':cod_bonus', $referer_code, PDO::PARAM_STR, 5);

		    $rsReferer->execute();

		    $row_rsReferer = $rsReferer->fetch(PDO::FETCH_ASSOC);

		    $totalRows_rsReferer = $rsReferer->rowCount();



		    if($totalRows_rsReferer > 0) {

		      $referer_id = $row_rsReferer['id'];

		    }

			}



			$insertSQL = "INSERT INTO clientes (data_registo, tipo, nome, email, telemovel, telefone, morada, localidade, nif, cod_postal, roll, pais, atividade, atividade2, pessoa, password, password_salt, activation_hash, validado, lingua, cod_bonus, referer) VALUES (:data, :tipo, :nome, :email, :telemovel, :telefone, :morada, :localidade, :nif, :cod_postal, :roll, :pais, :atividade, :atividade2, :pessoa, :password, :salt, :activation_hash, :validado, :lingua, :cod_bonus, :referer)";

			$Result1 = DB::getInstance()->prepare($insertSQL);

			$Result1->bindParam(':data', $data, PDO::PARAM_STR, 5);

			$Result1->bindParam(':tipo', $tipo, PDO::PARAM_INT);

			$Result1->bindParam(':nome', $nome, PDO::PARAM_STR, 5);

			$Result1->bindParam(':email', $email, PDO::PARAM_STR, 5);

			$Result1->bindParam(':telemovel', $telemovel, PDO::PARAM_STR, 5);

			$Result1->bindParam(':telefone', $telefone, PDO::PARAM_STR, 5);

			$Result1->bindParam(':morada', $morada, PDO::PARAM_STR, 5);	

			$Result1->bindParam(':localidade', $localidade, PDO::PARAM_STR, 5);	

			$Result1->bindParam(':nif', $nif, PDO::PARAM_STR, 5);	

			$Result1->bindParam(':cod_postal', $codpostal, PDO::PARAM_STR, 5);

			$Result1->bindParam(':roll', $roll, PDO::PARAM_STR, 5);	

			$Result1->bindParam(':pais', $pais, PDO::PARAM_INT);		

			$Result1->bindParam(':atividade', $atividade, PDO::PARAM_STR, 5);

			$Result1->bindParam(':atividade2', $atividade2, PDO::PARAM_STR, 5);

			$Result1->bindParam(':pessoa', $pessoa, PDO::PARAM_STR, 5);		

			$Result1->bindParam(':password', $password_final, PDO::PARAM_STR, 5);	

			$Result1->bindParam(':salt', $salt, PDO::PARAM_STR, 5);	

			$Result1->bindParam(':activation_hash', $user_activation_hash, PDO::PARAM_STR, 5);

			$Result1->bindParam(':validado', $validado, PDO::PARAM_INT);

			$Result1->bindParam(':lingua', $lang, PDO::PARAM_STR, 5);

			$Result1->bindParam(':cod_bonus', $cod_bonus, PDO::PARAM_STR, 5);

			$Result1->bindParam(':referer', $referer_id, PDO::PARAM_INT);

			$Result1->execute();

			$user_id = DB::getInstance()->lastInsertId();



			//Se foi convidado por alguém, vamos marcar como aceite no "convidar amigos"

			if($referer_id > 0) {

				$query_rsUpdate = "UPDATE convidar_amigos SET aceite = 1, id_novo_cliente = :id_novo_cliente WHERE id_cliente = :id_cliente AND email_convidado = :email_convidado";

				$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);

				$rsUpdate->bindParam(':email_convidado', $email, PDO::PARAM_STR, 5);

				$rsUpdate->bindParam(':id_novo_cliente', $user_id, PDO::PARAM_INT);

				$rsUpdate->bindParam(':id_cliente', $referer_id, PDO::PARAM_INT);

				$rsUpdate->execute();

			}



			//Subscrição de newsletter

			if($email != "" && isset($news)) {

        include_once(ROOTPATH."includes/subs_obrigado.php");

        insereSubs($email, $titulo_pag, 0, $nome);

 	 		}

														

			#####################################

			$formcontent = getHTMLTemplate("contacto.htm");

			

			$link_mail = "";

			if($tipo == 1) {

				$link = ROOTPATH_HTTP.'login_valida.php?id='.urlencode($user_id).'&verification_code='.urlencode($user_activation_hash);

				$link_mail = '<a style="background: #ffffff; font-family: arial; font-weight: 400; font-size: 13px; color: #000000; line-height: 40px; text-align: center; text-transform: uppercase;" href="'.$link.'">'.$Recursos->Resources["validar_registo_btn"].'</a>';



				$link_email = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

					<tr>

						<td height="15">&nbsp;</td>

				  </tr>

				</table>

				<table width="140" height="40" style="border: 2px solid #000000;" cellpadding="0" cellspacing="0">

				  <tr>

						<td width="140" height="40" align="center">'.$link_mail.'</td>

				  </tr>

				</table>';



				if(strpos($texto_mail, '#link#') !== false) {

					$texto_mail = str_replace('#link#', $link, $texto_mail);

				}

			}



			$texto_email = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

			  	<td align="left" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$texto_mail.'</td>

		    </tr>

        <tr>

          <td height="30">&nbsp;</td>

        </tr>

        <tr>

          <td height="1" bgcolor="#dadada"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif" height="1" border="0"></td>

        </tr>

        <tr>

          <td height="30">&nbsp;</td>

        </tr>

      </table>';	

				

			$mensagem_final = $texto_email.'

				<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

					<tr>

						<td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["ar_mail_reg_novo_tit"].'</strong></td>

					</tr>

				</table>

				<table width="100%" border="0" cellpadding="1" cellspacing="0">

				  <tr>

					<td height="20">&nbsp;</td>

						<td align="left" valign="middle">&nbsp;</td>

				  </tr>

				  <tr>

						<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["ar_tipo"].':</strong></td>

						<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$tipo_txt.'</td>

				  </tr>

				  <tr>

						<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["nome"].':</strong></td>

						<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$nome.'</td>

				  </tr>

				  <tr>

						<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mail"].':</strong></td>

						<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$email.'</td>

				  </tr>

				  <tr>

						<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["contacto"].':</strong></td>

						<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$telemovel.'</td>

				  </tr>		

				</table>'.$link_email;

										



			$rodape = email_social();

			$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 2";

			$rsNotificacoes = DB::getInstance()->query($query_rsNotificacoes);

			$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

			DB::close();

			

			$titulo = $Recursos->Resources["ar_mail_reg"];

			$subject = $Recursos->Resources["ar_mail_reg_novo_tit"];

			

			$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

			$pagina_form = $origem."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."contactos.php'>".ROOTPATH_HTTP."contactos.php</a>";		

			$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

			$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

			$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

			$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

			$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);



			$formcontent_admin = $formcontent;

			$formcontent_admin = str_replace($link_email, '', $formcontent_admin);



			//Para os clientes do tipo "Empresas", como tem de ser validados na consola, coloca um aviso para a administração no email

			if($tipo == 2) {

				$link_prof = $link = ROOTPATH_HTTP_ADMIN.'/clientes/clientes-edit.php?id='.$user_id.'&v=1';

				$texto_mail_prof = $Recursos->Resources["reg_mail_txt_admin"];



				if(strpos($texto_mail_prof, '#link#') !== false) {

					$texto_mail_prof = str_replace('#link#', $link_prof, $texto_mail_prof);

				}



				$texto_email_prof = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

	        <tr>

				  	<td align="left" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$texto_mail_prof.'</td>

			    </tr>

	        <tr>

	          <td height="30">&nbsp;</td>

	        </tr>

	        <tr>

	          <td height="1" bgcolor="#dadada"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif" height="1" border="0"></td>

	        </tr>

	        <tr>

	          <td height="30">&nbsp;</td>

	        </tr>

	      </table>';





	      $formcontent_admin = str_replace($texto_email, $texto_email_prof, $formcontent_admin);

			}

			else {

				$formcontent_admin = str_replace($texto_email, '', $formcontent_admin);

			}

			

			



			sendMail($email, '', $formcontent, $mensagem_final, $subject);

			sendMail($row_rsNotificacoes['email'], '', $formcontent_admin, $mensagem_final, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3']);

			#####################################						

		}



		DB::close();	



		return $registo_post;

	}



	public static function recuperar_password($email, $titulo_pag) {

		global $extensao, $Recursos, $recupera_post;



		$query_rsUser="SELECT * FROM clientes WHERE email=:email AND validado='1' AND ativo='1'";

		$rsUser = DB::getInstance()->prepare($query_rsUser);

		$rsUser->bindParam(':email', $email, PDO::PARAM_STR, 5);

		$rsUser->execute();

		$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsUser = $rsUser->rowCount();

		

		if($totalRows_rsUser > 0) {

			$code = self::randomCodeRecupera();



			$query_rsUpdate = "UPDATE clientes SET cod_recupera = :link WHERE id=:id";

			$rsUpdate = DB::getInstance()->prepare($query_rsUpdate);

			$rsUpdate->bindParam(':link', $code, PDO::PARAM_STR, 5);

			$rsUpdate->bindParam(':id', $row_rsUser['id'], PDO::PARAM_INT);

			$rsUpdate->execute();

			

			$formcontent = getHTMLTemplate("contacto.htm");

		

			$link = ROOTPATH_HTTP.'login_recuperar.php?v='.urlencode($code);

			$link_mail = '<a style="background: #ffffff; font-family: arial; font-weight: 400; font-size: 13px; color: #000000; line-height: 40px; text-align: center; text-transform: uppercase;" href="'.$link.'">'.$Recursos->Resources["recupera_password_clique"].'</a>';



			$mensagem_final = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

				  <td align="left" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$Recursos->Resources["recupera_password"].'</td>

		    </tr>

        <tr>

          <td height="30">&nbsp;</td>

        </tr>

			</table>

			<table width="140" height="40" style="border: 2px solid #000000;" cellpadding="0" cellspacing="0">

			  <tr>

					<td width="140" height="40" align="center">'.$link_mail.'</td>

			  </tr>

			</table>';		

			

			$rodape = email_social();

		

			$titulo = $Recursos->Resources["recuperar_password"];

			$subject = $Recursos->Resources["recuperar_password"];

			

			$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

			$pagina_form = $Recursos->Resources["login"]."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."contactos.php'>".ROOTPATH_HTTP."contactos.php</a>";		

			

			$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

			$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

			$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

			$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

			$formcontent = str_replace ("#crodape#", $rodape, $formcontent);	

				

			sendMail($email, '', $formcontent, $formcontent, $subject);

			####################################

			

			$recupera_post = "1";

		}

		else {

			$recupera_post = "-1";

		}



		DB::close();



		return $recupera_post;

	}



	public static function validaUser($user_id, $user_activation_hash) {

		$query_rsUser = "SELECT email, password FROM clientes WHERE id=:user_id AND activation_hash = :user_activation_hash";

		$rsUser = DB::getInstance()->prepare($query_rsUser);

		$rsUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);

		$rsUser->bindParam(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);

		$rsUser->execute();

		$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsUser = $rsUser->rowCount();

		

		if($totalRows_rsUser > 0) {

			$insertSQL = "UPDATE clientes SET validado = '1', ativo = '1', activation_hash = NULL WHERE id = :user_id AND activation_hash = :user_activation_hash";

			$Result1 = DB::getInstance()->prepare($insertSQL);

			$Result1->bindParam(':user_id', intval(trim($user_id)), PDO::PARAM_INT);

			$Result1->bindParam(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);

			$Result1->execute();



			$user = $row_rsUser['email'];

			$pass = $row_rsUser['password'];

			$password_final = $pass;

			

			$data_hoje=date('Y-m-d H:i:s');

			

			$insertSQL = "UPDATE clientes SET ultima_entrada=:data WHERE email=:user";

			$Result1 = DB::getInstance()->prepare($insertSQL);

			$Result1->execute(array(':data'=>$data_hoje, ':user'=>$user));



			setcookie("SITE_user", $user, 0, '/', '', $cookie_secure, true);

			setcookie("SITE_pass", $password_final, 0, '/', '', $cookie_secure, true);	



			DB::close();

			

			header('location: area-reservada.php?intro=1');

			exit();

		}

		else {

			DB::close();



			header('location: login.php');

			exit();

		}

	}



	public static function randomCodeRecupera($size = '32') {

		$string = '';

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';



		for($i = 0; $i < $size; $i++) {

			$string .= $characters[mt_rand(0, (strlen($characters) - 1))];  

		}



		$query_rsExists = "SELECT id FROM clientes WHERE cod_recupera = '$string'";

		$rsExists = DB::getInstance()->prepare($query_rsExists);

		$rsExists->execute();

		$totalRows_rsExists = $rsExists->rowCount();

		DB::close();



		if($totalRows_rsExists == 0) {

			return $string;

		}

		else {

			return self::randomCodeRecupera();

		}

	}



	public static function createSalt() {

		$text = md5(uniqid(rand(), TRUE));

		return substr($text, 0, 3);

	}



	public static function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {

		//$lmin = 'abcdefghijklmnopqrstuvwxyz';

		$lmin = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$num = '1234567890';

		$simb = '!@#$%*-';

		$retorno = '';

		$caracteres = '';

		$caracteres .= $lmin;



		if($maiusculas) {

			$caracteres .= $lmai;

		}

		if($numeros) {

			$caracteres .= $num;

		}

		if($simbolos) {

			$caracteres .= $simb;

		}

		

		$len = strlen($caracteres);

		

		for($n = 1; $n <= $tamanho; $n++) {

			$rand = mt_rand(1, $len);

			$retorno .= $caracteres[$rand-1];

		}

		

		return $retorno;

	}



	public static function isLogged() {

		$user = $_COOKIE['SITE_user'];

		$pass = $_COOKIE['SITE_pass'];

		

		if($user && tableExists(DB::getInstance(), 'clientes')) {

			$query_rsCliente = "SELECT * FROM clientes WHERE email=:user AND validado='1' AND ativo='1'";

			$rsCliente = DB::getInstance()->prepare($query_rsCliente);

			$rsCliente->bindParam(':user', $user, PDO::PARAM_STR, 5);	

			$rsCliente->execute();

			$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsCliente = $rsCliente->rowCount();

			DB::close();

			

			$id_cliente = $row_rsCliente["id"];

			$password_db = $row_rsCliente['password'];

	

			if($totalRows_rsCliente > 0 && ($pass == $password_db)) {

				return $row_rsCliente;

			}

			else {

				return 0;

			}

		}

	}

	

	public static function isValidUser($user) {

		if($user && tableExists(DB::getInstance(), 'clientes')) {

			$query_rsCliente = "SELECT tipo, nif, nome, email, telemovel, morada, localidade, cod_postal, pais FROM clientes WHERE id='$user' AND validado='1' AND ativo='1'";

			$rsCliente = DB::getInstance()->prepare($query_rsCliente);

			$rsCliente->execute();

			$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsCliente = $rsCliente->rowCount();

			DB::close();

	

			$valid = 1;

			

			if(!$row_rsCliente['nif'] && $row_rsCliente['tipo'] > 1) {

				$valid = 0;

			}

			if(!$row_rsCliente['nome']) {

				$valid = 0;

			}

			if(!$row_rsCliente['email']) {

				$valid = 0;

			}

			if(!$row_rsCliente['telemovel']) {

				$valid = 0;

			}

			if(!$row_rsCliente['morada']) {

				$valid = 0;

			}

			if(!$row_rsCliente['localidade']) {

				$valid = 0;

			}

			if(!$row_rsCliente['cod_postal']) {

				$valid = 0;

			}

			if(!$row_rsCliente['pais']) {

				$valid = 0;

			}

			

			return $valid;		

		}

	}



	public static function reenvia_email($user) {

		global $Recursos;



		$query_rsEmail = "SELECT id, activation_hash, email FROM clientes WHERE id=:user";

		$rsEmail = DB::getInstance()->prepare($query_rsEmail);

		$rsEmail->bindParam(':user', $user, PDO::PARAM_INT);	

		$rsEmail->execute();

		$row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsEmail = $rsEmail->rowCount();	

		DB::close();

		

		$user_id = $row_rsEmail['id'];

		$user_activation_hash = $row_rsEmail['activation_hash'];

		$email = $row_rsEmail['email'];

		

		$link = ROOTPATH_HTTP.'login_valida.php?id='.urlencode($user_id).'&verification_code='.urlencode($user_activation_hash);

		$link_mail = '<a style="background-color: #ffffff; font-family: arial; font-weight: 400; font-size: 13px; color: #000000; line-height: 40px; text-align: center; text-decoration:uppercase;" href="'.$link.'">'.$Recursos->Resources["validar_registo_btn"].'</a>';

			

		#####################################

		$formcontent = getHTMLTemplate("contacto.htm");



		$titulo = $Recursos->Resources["ar_mail_reg_novo_tit"];

		$pagina_form = $Recursos->Resources["login"]."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."contactos.php'>http://".ROOTPATH_HTTP."contactos.php</a>";	



		$mensagem_final = '<table width="100%" border="0" cellpadding="1" cellspacing="0">

			  <tr>

					<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$Recursos->Resources["validar_conta_txt3"].'</td>

			  </tr>

			  <tr>

					<td height="15">&nbsp;</td>

			  </tr>

			</table>

			<table width="140" height="40" style="border: 2px solid #000000;" cellpadding="0" cellspacing="0">

			  <tr>

					<td width="140" height="40" align="center">'.$link_mail.'</td>

			  </tr>

			</table>';

						

		$rodape = email_social();

		$subject = $Recursos->Resources["ar_mail_reg_novo_tit"];



		$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

		$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

		$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

		$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

		$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);



		sendMail($email, '', $formcontent, $mensagem, $subject);

	}

	



	public static function sendEnquiry( $emailType, $emailDetails )

	{

		global $extensao, $Recursos, $recupera_post;

		//echo "<pre>"; print_r($emailDetails); die;

		if( $emailType != "" && ! empty($emailDetails) )

		{

			$formcontent = getHTMLTemplate("contacto.htm");	



			if( $emailType == "productsuccess" )

			{



				$mensagem_final = '<table width="100%" border="0" cellpadding="1" cellspacing="0">

					  <tr>

							<td height="20" style="text-align:center; font-family:verdana;"> 

								<h1>'.$Recursos->Resources["successemail"].'</h1>

								<img src="'.ROOTPATH_HTTP.'imgs/email_img/bismillahBakery.png" style="max-width: 20%;">

								<h2 style="color: green;">'.$Recursos->Resources["paymentsuccessfully"].'</h2>

							</td>

					  </tr>

					  <tr>

					  		<td style="width:250px; text-align:center;" >

					  			<table width="100%" height="40" style="font-size:16px; font-family:verdana;">

					  			<tr>

									<td height="15" style="text-align:left; width:50%">Product Name: </td>

									<td style="text-align:left; width:50%"><b>'.$emailDetails["productInfo"]["title"].'</b></td>

								</tr>

								<tr>

									<td height="15" style="text-align:left; width:50%">Product Price: </td>

									<td style="text-align:left; width:50%"><b>'.$emailDetails["productInfo"]["preco"].'</b></td>

								</tr>

								<tr>

									<td height="15" style="text-align:left; width:50%">Name: </td>

									<td style="text-align:left; width:50%"><b>'.$emailDetails["clientInfo"]["nome"].'</b></td>

								</tr>

								<tr>

									<td height="15" style="text-align:left; width:50%">Email ID: </td>

									<td style="text-align:left; width:50%"><b>'.$emailDetails["clientInfo"]["email"].'</b></td>

								</tr>

								<tr>

									<td height="15" style="text-align:left; width:50%">Telephone: </td>

									<td style="text-align:left; width:50%"><b>'.$emailDetails["clientInfo"]["telemovel"].'</b></td>

								</tr>

								<tr>

									<td height="15" style="text-align:left; width:50%">Store Name: </td>

									<td style="text-align:left; width:50%"><b>'.$emailDetails["storetInfo"]["b_name"].'</b></td>

								</tr>

								<tr>

									<td height="15" style="text-align:left;  width:50%">Store Telefone: </td>

									<td style="text-align:left;  width:50%"><b>'.$emailDetails["storetInfo"]["phone"].'</b></td>

								</tr>

								</table>

					  		</td>

					  </tr>

				</table>';		

		    }			



		    print_r($mensagem_final);

		    exit();



			$rodape = email_social();

			$subject = $Recursos->Resources[$emailType]." - www.".SERVIDOR;

			$to = 'prajapativishal999991@gmail.com';

			$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

			$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

			$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

			$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

			$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

			

			sendMail($to, '', $formcontent, "", $subject);

		}

		else

		{

			return "You have Missing Perameters!";

		}

	}









	public static function clienteData($qual) {

		$user = $_COOKIE['SITE_user'];

		$pass = $_COOKIE['SITE_pass'];

		$tipo_cliente = 1;

		$preco_cliente = 1;

		$desc_cliente = 0; 

		$pais_cliente = '197';

		

		if($user && tableExists(DB::getInstance(), 'clientes')) {

			$query_rsCliente = "SELECT id, password, tipo, pvp, desconto, pais FROM clientes WHERE email = :user AND validado='1' AND ativo='1'";

			$rsCliente = DB::getInstance()->prepare($query_rsCliente);

			$rsCliente->bindParam(':user', $user, PDO::PARAM_STR, 5);

			$rsCliente->execute();

			$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsCliente = $rsCliente->rowCount();

			DB::close();

			

			if($totalRows_rsCliente > 0) {

				$password_db = $row_rsCliente['password'];



				if($pass == $password_db) {

					$id_cliente = $row_rsCliente["id"];

					$tipo_cliente = $row_rsCliente["tipo"];

					$preco_cliente = $row_rsCliente["pvp"];

					$desc_cliente = $row_rsCliente["desconto"];

					$pais_cliente = $row_rsCliente["pais"];

				}

			}

		}



		$return = "";

		

		if($qual == "id") {

			$return = $id_cliente;

		}

		if($qual == "pais") {

			$return = $pais_cliente;

		}

		if($qual == "tipo") {

			$return = $tipo_cliente;

		}

		if($qual == "pvp") {

			$return = $preco_cliente;

		}

		if($qual == "desconto") {

			$return = $desc_cliente;

		}

		

		return $return;

	}

}



// Inicializa instância da classe

$class_user = User::getInstance();

$row_rsCliente = $class_user->isLogged();



if(tableExists(DB::getInstance(), 'lista_desejo')) {

	$wish_session = $_COOKIE[WISHLIST_SESSION];

  $ses_id_old = strtotime(date("YmdHis", strtotime("-5 days"))); //5 dias atrás

  

  if($wish_session == "" || $wish_session <= $ses_id_old) {

    $ses_id = strtotime(date("YmdHis",time()));

    

    $insertSQL = "DELETE FROM lista_desejo WHERE cliente = 0 AND session < '$ses_id_old'";

    $rsInsertSQL = DB::getInstance()->prepare($insertSQL);

    $rsInsertSQL->execute();

    

    $timeout = 3600*24*5; //5 dias

    setcookie(WISHLIST_SESSION, $ses_id, time()+$timeout, "/", "", $cookie_secure, true);

    $wish_session = $ses_id;

  }



	$query_rsWishlist = "SELECT * FROM lista_desejo WHERE ((cliente=:cliente AND (session=0 OR session IS NULL OR session = '')) OR (cliente=0 AND session=:session))";

	$rsWishlist = DB::getInstance()->prepare($query_rsWishlist);

	$rsWishlist->bindParam(':cliente', $id_cliente, PDO::PARAM_STR, 5);

	$rsWishlist->bindParam(':session', $wish_session, PDO::PARAM_STR, 5);

	$rsWishlist->execute();

	$row_rsWishlist = $rsWishlist->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsWishlist = $rsWishlist->rowCount();

	DB::close();

}



?>