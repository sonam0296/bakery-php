<?php

//Generate Token Id and Valid

$csrf = new csrf();

$token_id = $csrf->get_token_id();

$token_value = $csrf->get_token($token_id);

//Codificar inputs de segurança

$form_seguranca = $csrf->form_names(array('cod_res', 'cod_seg'), false);



$form_testemunhos = $csrf->form_names(array('nome', 'localidade', 'comentario', 'imagem', 'aceita_politica'), false);

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_testemunhos") {

  if($_POST['form_hidden'] == "") {

    if($csrf->check_valid('post')) {

    	if((isset($_POST[$form_seguranca['cod_res']]) && $_POST[$form_seguranca['cod_res']] == $_POST[$form_seguranca['cod_seg']]) && !isset($_POST['g-recaptcha-response']) && CAPTCHA_KEY == NULL) {

				$response = 1;

			}

			else {

				$response = isValidCaptcha($_POST['g-recaptcha-response']);

			}



			if($response == 1) {

        if($_POST[$form_testemunhos['nome']] != "" && $_POST[$form_testemunhos['localidade']] != "" && $_POST[$form_testemunhos['comentario']] != "" && isset($_POST[$form_contactos['aceita_politica']])) {        

          $formcontent = getHTMLTemplate("contacto.htm");

          $rodape = email_social();

                              

          $nome = $_POST[$form_testemunhos['nome']];

          $localidade = $_POST[$form_testemunhos['localidade']];

          $mensagem = nl2br($_POST[$form_testemunhos['comentario']]);

          $files = $_FILES[$form_testemunhos["imagem"]];

          $email = "";

          $data = date('Y-m-d H:i:s');



          $anexos_array = array();

          $anexos_txt = "";

          $txt_field = '';

          $n_anexos = 0;



          if(!empty($files)) {

            $imgs_dir = "testemunhos";

            $anexos_field = fileUpload($imgs_dir, $files, '');  



            if(!empty($anexos_field)) {

              $anexos_array = $anexos_field;



              foreach($anexos_field as $anexos) {

                if($anexos[1]) {

                  $anexos_txt .= $anexos[1].", "; 

                  $n_anexos++;

                }

                else if($anexos) {

                  $anexos_txt .= $anexos.", ";    

                  $n_anexos++;

                }

              }



              if($anexos_txt) {

                $anexos_txt = substr($anexos_txt, 0, -2);



                $txt_field = '<tr>

                  <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["anexos"].' ('.$n_anexos.'):</strong></td>

                  <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$anexos_txt.'</td>

                </tr>';

              }

            }

          }



          $query_rsLinguasIns = "SELECT sufixo FROM linguas WHERE visivel = 1 ORDER BY id ASC";

					$rsLinguasIns = DB::getInstance()->prepare($query_rsLinguasIns);

					$rsLinguasIns->execute();

					$row_rsLinguasIns = $rsLinguasIns->fetchAll();

					$totalRows_rsLinguasIns = $rsLinguasIns->rowCount();



					foreach($row_rsLinguasIns as $lingua) {

	          $insertSQL = "INSERT INTO testemunhos_".$lingua['sufixo']." (id, nome, localidade, comentario, imagem1, visivel, novo, data) VALUES (:id, :nome, :localidade, :comentario, :imagem1, 0, 1, :data)";

	          $rsInsert = DB::getInstance()->prepare($insertSQL);

	          $rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);

	          $rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);    

	          $rsInsert->bindParam(':localidade', $localidade, PDO::PARAM_STR, 5);    

	          $rsInsert->bindParam(':comentario', $mensagem, PDO::PARAM_STR, 5);  

	          $rsInsert->bindParam(':imagem1', $anexos_txt, PDO::PARAM_STR, 5);   

	          $rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);

	          $rsInsert->execute();

	        }

            

          $mensagem_final = '

              <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

                <tr>

                  <td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["validar_testemunho"].'</strong></td>

                </tr>

              </table>

              <table width="100%" border="0" cellpadding="1" cellspacing="0">

                <tr>

                  <td height="20">&nbsp;</td>

                  <td align="left" valign="middle">&nbsp;</td>

                </tr>

                <tr>

                  <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["nome"].':</strong></td>

                  <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$nome.'</td>

                </tr>

                <tr>

                  <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["localidade"].':</strong></td>

                  <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$localidade.'</td>

                </tr>

                <tr>

                  <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>

                  <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$mensagem.'</td>

                </tr>

                '.$txt_field.'

              </table>';

            

          $posicao = strpos($mensagem, "<a");

          $posicao2 = strpos($mensagem, "#");

          

          if($posicao === false && $posicao2 === false) {             

            $query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 7";

            $rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

            $rsNotificacoes->execute();

            $row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

            $totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

            

            $titulo = $row_rsNotificacoes['assunto'];

            $subject = $row_rsNotificacoes['assunto'];

            

            $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);  

            $pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";    

            

            $formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

            $formcontent = str_replace ("#crodape#", $rodape, $formcontent);

            $formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

            $formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

            $formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);



            DB::close();



            if($row_rsNotificacoes['email']) {

              if($row_rsNotificacoes['resposta']) {

                envia_resposta($row_rsNotificacoes['id'], $nome, $email, $mensagem_final, $pagina_form);

              }



              sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email, $anexos_array);

              ####################################

                              

              header("location: ".$url_form."env=7");

              exit();

            }

          }

        }

      }

    }

	}

	else {

    header("location: ".$url_form."env=7");

    exit();

	}

}



$form_login = $csrf->form_names(array('login_email', 'login_pass'), false);

$login_post = 0;

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_login") {

	if($_POST['form_hidden'] == "") {

		if(isset($_POST[$form_login['login_email']], $_POST[$form_login['login_pass']])) {	

			if($csrf->check_valid('post')) {

				$user = utf8_decode($_POST[$form_login['login_email']]);	

				$pass = utf8_decode($_POST[$form_login['login_pass']]);

	

				if($user && $pass) {

					$login_post = $class_user->login($user, $pass, $_POST['lembrar'], $_GET['carr'], $_GET['rc']);				

				}

			}

		}

	}

}



$form_recupera = $csrf->form_names(array('recupera_email', 'password', 'cliente'), false);

$recupera_post = 0;



if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_recupera") {

	if($_POST['form_hidden'] == "") {

		if(isset($_POST[$form_recupera['recupera_email']])) {	

			if($csrf->check_valid('post')) {

				$recupera_post = $class_user->recuperar_password($_POST[$form_recupera['recupera_email']], $_POST['titulo_pag']);			

			}

		}

	}

}



if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_recupera2") {

	if($_POST['form_hidden'] == "") {

		if(isset($_POST[$form_recupera['password']])) {	

			if($csrf->check_valid('post')) {

			 	$id_cliente = $_POST[$form_recupera['cliente']];

				$password = $_POST[$form_recupera['password']];

				$rep_password = $_POST['password2'];



				if($rep_password == $password && $id_cliente) {

					$salt = $class_user->createSalt();				

					$hash = hash('sha256', $password);

					

					$password_final = hash('sha256', $salt . $hash);



					$insertSQL = "UPDATE clientes SET password=:password, password_salt=:password_salt, cod_recupera=NULL WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':password', $password_final, PDO::PARAM_STR, 5);	

					$rsInsert->bindParam(':password_salt', $salt, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':id', $id_cliente, PDO::PARAM_STR, 5);	

					$rsInsert->execute();

					DB::close();

					

					header("Location: login_recuperar.php?inserido=1");

					exit();

				}

				else {

					header("Location: login_recuperar.php?error=1");

					exit();

				}

			}

		}

	}

}



$form_registo = $csrf->form_names(array('tipo', 'nome', 'email', 'telemovel', 'telefone', 'pass', 'pass_conf', 'nif', 'morada', 'localidade', 'cpostal', 'cpostal2','roll', 'pais', 'atividade', 'pessoa_contacto', 'act_outro', 'aceita_politica', 'aceita_newsletter'), false);

$registo_post = 0;

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_registo") {

	if($_POST['form_hidden'] == "") {

		if($csrf->check_valid('post')) {

			if((isset($_POST[$form_seguranca['cod_res']]) && $_POST[$form_seguranca['cod_res']] == $_POST[$form_seguranca['cod_seg']]) && !isset($_POST['g-recaptcha-response']) && CAPTCHA_KEY == NULL) {

				$response = 1;

			}

			else {

				$response = isValidCaptcha($_POST['g-recaptcha-response']);

			}

			

			if($response == 1) {

				if($_POST[$form_registo['nome']] != "" && $_POST[$form_registo["email"]] != "" && $_POST[$form_registo['pass']] != "" && $_POST[$form_registo['pass_conf']] != "" && $_POST[$form_registo['pass']] == $_POST[$form_registo['pass_conf']] && $_POST[$form_registo['telemovel']] != "" && $_POST[$form_registo['cpostal']] != "" && $_POST[$form_registo['morada']] != "" && $_POST[$form_registo['localidade']] != "" && $_POST[$form_registo['roll']] != "" && $_POST[$form_registo['pais']] != "" && isset($_POST[$form_registo['aceita_politica']])) {

					//$tipo = $_POST[$form_registo['tipo']];

					$tipo = 1;

					$nome = $_POST[$form_registo['nome']];

					$email = $_POST[$form_registo['email']];

					$telefone = $_POST[$form_registo['telefone']];

					$telemovel = $_POST[$form_registo['telemovel']];

					$nif = $_POST[$form_registo['nif']];

					$morada = $_POST[$form_registo['morada']];

					$localidade = $_POST[$form_registo['localidade']];

					$codpostal = $_POST[$form_registo['cpostal']];

					$codpostal2 = $_POST[$form_registo['cpostal2']];

					if($codpostal2) {

						$codpostal .= "-".$codpostal2;

					}

					$roll = $_POST[$form_registo['roll']];

					$pais = $_POST[$form_registo['pais']];

					$atividade = $_POST[$form_registo['atividade']];

					$atividade2 = $_POST[$form_registo['act_outro']];

					$pessoa = $_POST[$form_registo['pessoa_contacto']];

					$password = $_POST[$form_registo['pass']];

					$referer_code = $_POST['referer_code'];

					

					$registo_post = $class_user->registo($tipo, $nome, $email, $password, $telemovel, $telefone, $nif, $morada, $localidade, $codpostal, $roll,  $pais, $atividade, $atividade2, $pessoa, $_POST[$form_registo['aceita_newsletter']], $_POST['titulo_pag'], $referer_code);	

				}

			}

		}

	}

}



$form_tickets = $csrf->form_names(array('tipo', 'assunto', 'mensagem', 'mensagem2', 'upload', 'upload2'), false);

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_tickets") {

	if($_POST['form_hidden'] == "") {

		if($csrf->check_valid('post')) {

			if(($_POST[$form_tickets["tipo"]] && $_POST[$form_tickets["assunto"]] && $_POST[$form_tickets["mensagem"]]) || $_POST[$form_tickets["mensagem2"]]) {

				$query_rsMaxID = "SELECT MAX(id) FROM tickets";

				$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);

				$rsMaxID->execute();

				$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsMaxID = $rsMaxID->rowCount();



				$anexos_array = array();

				$anexos_txt = "";

				

				$id_ticket = 0;

				$id = $row_rsMaxID['MAX(id)'] + 1;

				if($_POST['id_ticket']) $id_ticket = $_POST['id_ticket'];	



				$tipo = $_POST[$form_tickets["tipo"]];

				$assunto = $_POST[$form_tickets["assunto"]];

				$mensagem = $_POST[$form_tickets["mensagem"]];

				$files = $_FILES[$form_tickets["upload"]];



				if($_POST[$form_tickets["mensagem2"]]) {

					$mensagem = $_POST[$form_tickets["mensagem2"]];

					$files = $_FILES[$form_tickets["upload2"]];

				}

				

				if($id_ticket && $id_ticket > 0) {

					$query_rsPai = "SELECT assunto FROM tickets WHERE id = '$id_ticket'";

					$rsPai = DB::getInstance()->prepare($query_rsPai);

					$rsPai->execute();

					$row_rsPai = $rsPai->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsPai = $rsPai->rowCount();



					$mensagem = $_POST[$form_tickets["mensagem2"]];

					$assunto = $row_rsPai['assunto'];

				}



				$txt_field = '';

				$n_anexos = 0;

				$ne_anexos = 0;

				$upl_error = 0;



				if(!empty($files)) {

       		$imgs_dir = "tickets";

					$anexos_field = fileUpload($imgs_dir, $files, '');



					/*if (!is_array($anexos_field) && strrpos($anexos_field, "Error! ")>=0) {

						$upl_error = explode("Error! ", $anexos_field);

						$upl_error = $upl_error[1];

					}else*/ if(!empty($anexos_field)) {

						$anexos_array = $anexos_field;



						$lista_erros = "";



						if($anexos_array[sizeof($anexos_array)-1][0] == "erros" && $anexos_array[sizeof($anexos_array)-1][1] != "") {

							$array_erros = explode(";", $anexos_array[sizeof($anexos_array)-1][1]);



							foreach($array_erros as $erro) {

								$spl = explode("**", $erro);



								if($spl[1]) {

									$lista_erros .= "<strong>".$spl[1].":</strong> ".$Recursos->Resources["upl_error".$spl[0]]."<br>";

									$ne_anexos++;

								}

							}

						}



						foreach($anexos_field as $anexos) {

							if($anexos[0] != "erros" && $anexos[1]) {

								$anexos_txt .= $anexos[1].", ";	

								$n_anexos++;

							}

						}



						if($anexos_txt) {

							$anexos_txt = substr($anexos_txt, 0, -2);



							$txt_field = '<tr>

								<td align="left" valign="top" width="130" height="40"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif"></td>

								<td align="left" width="390" valign="top" height="40"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif"></td>

							</tr><tr>

								<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["anexos"].' ('.$n_anexos.'):</strong></td>

								<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.str_replace(", ", "<br>", $anexos_txt).'</td>

							</tr>';

						}



						if($lista_erros != "") {

							$txt_field .= '<tr>

								<td align="left" valign="top" width="130" height="20"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif"></td>

								<td align="left" width="390" valign="top" height="20"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif"></td>

							</tr><tr>

								<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["anexos_erro"].' ('.$ne_anexos.'):</strong></td>

								<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$lista_erros.'</td>

							</tr>';

						}

					}

				}



				$insertSQL = "INSERT INTO tickets (id, id_pai, id_cliente, tipo, remetente, assunto, descricao, anexos, data, estado, lingua) VALUES ('$id', '$id_ticket', '".$row_rsCliente['id']."', '$tipo', '".$row_rsCliente['nome']."', :assunto, :mensagem, :anexos, now(), '1', '$lang')";

				$rsInsert = DB::getInstance()->prepare($insertSQL);	

				$rsInsert->bindParam(':assunto', $assunto, PDO::PARAM_STR, 5);

				$rsInsert->bindParam(':mensagem', $mensagem, PDO::PARAM_STR, 5);

				$rsInsert->bindParam(':anexos', $anexos_txt, PDO::PARAM_STR, 5);

				$rsInsert->execute();

				

				#####################################

				$formcontent = getHTMLTemplate("contacto.htm", 4);

				$rodape = email_social();

										

				$mensagem_final = '

						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

							<tr>

								<td style="font-family:arial; font-size:16px; line-height:16px; color:#444444; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>

							</tr>

						</table>

						<table width="100%" border="0" cellpadding="1" cellspacing="0">

						  <tr>

							<td height="20">&nbsp;</td>

							<td align="left" valign="middle">&nbsp;</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["tck_nome"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$row_rsCliente['nome'].'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["tck_email"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$row_rsCliente['email'].'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["tck_assunto"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$assunto.'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.nl2br($mensagem).'</td>

						  </tr>

						  '.$txt_field.'

						</table>';

														

				$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 4";

				$rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

				$rsNotificacoes->execute();

				$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

				

				$titulo = $row_rsNotificacoes['assunto'];

				$subject = $row_rsNotificacoes['assunto'];

				

				$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

				$pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".$url_form."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";	

				

				$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

				$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

				$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

				$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

				$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);



				DB::close();

					

				if($row_rsNotificacoes['email']) {

					if($row_rsNotificacoes['resposta']) {

						envia_resposta($row_rsNotificacoes['id'], $row_rsCliente['nome'], $row_rsCliente['email'], $mensagem_final, $pagina_form);

					}



					sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email, $anexos_array);

					

					if($id_ticket && $id_ticket > 0) {

						header("Location: area-reservada-tickets.php?alt=1");

						exit();

					}

					else {

						header("Location: area-reservada-tickets.php?inserido=1");

						exit();

					}	

				}

			}

		}

	}

}







$form_consultorioonline = $csrf->form_names(array('nome', 'email', 'assunto', 'mensagem', 'aceita_politica', 'aceita_newsletter', 'idade', 'peso_atual','altura'), false);

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_consultorioonline") {



	$form_contactos = $form_consultorioonline; 



	if($_POST['form_hidden'] == "") {

		if($csrf->check_valid('post')) {

			if((isset($_POST[$form_seguranca['cod_res']]) && $_POST[$form_seguranca['cod_res']] == $_POST[$form_seguranca['cod_seg']]) && !isset($_POST['g-recaptcha-response']) && CAPTCHA_KEY == NULL) {

				$response = 1;

			}

			else {

				$response = isValidCaptcha($_POST['g-recaptcha-response']);

			}

			$response = 1; 

			

			

			if($response == 1) {



				if($_POST[$form_contactos['nome']] != "" && $_POST[$form_contactos['email']] != "" && $_POST[$form_contactos['idade']] != "" && $_POST[$form_contactos['peso_atual']] != "" && $_POST[$form_contactos['altura']] != "" && $_POST[$form_contactos['mensagem']] != "" && isset($_POST[$form_contactos['aceita_politica']])) {	



					$formcontent = getHTMLTemplate("contacto.htm");

					$rodape = email_social();

					

															

					$nome = $_POST[$form_contactos['nome']];

					$email = $_POST[$form_contactos['email']];

					$idade = $_POST[$form_contactos['idade']];

					$peso_atual = $_POST[$form_contactos['peso_atual']];

					$altura = $_POST[$form_contactos['altura']];

					$mensagem = nl2br($_POST[$form_contactos['mensagem']]);

        		

					

					$mensagem_final = '

						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

							<tr>

								<td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>

							</tr>

						</table>

						<table width="100%" border="0" cellpadding="1" cellspacing="0">

						  <tr>

							<td height="20">&nbsp;</td>

							<td align="left" valign="middle">&nbsp;</td>

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

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["idade"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$idade.'</td>

						  </tr>



						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["peso_atual"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$peso_atual.'</td>

						  </tr>



						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["altura"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$altura.'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$mensagem.'</td>

						  </tr>

						</table>';

					

					$posicao = strpos($mensagem, "<a");

					$posicao2 = strpos($mensagem, "#");

					



					if($posicao === false && $posicao2 === false) {	



						$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 10";

						$rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

						$rsNotificacoes->execute();

						$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

						DB::close();



						$titulo = $row_rsNotificacoes['assunto'];

						$subject = $row_rsNotificacoes['assunto'];

						

						$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

						$pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";	

						

						$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

						$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

						$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

						$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

						$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

					

						if($row_rsNotificacoes['email']) {				

							if($row_rsNotificacoes['resposta']) {

								envia_resposta($row_rsNotificacoes['id'], $nome, $email, $mensagem_final, $pagina_form);

							}

		

							sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email);



							####################################			

							header("location: ".$url_form."env=1");

							exit();

						}

					}

				}

			}

		}

	}

	else {



		header("location: ".$url_form."env=1");

		exit();

	}

}





$form_contactos = $csrf->form_names(array('nome', 'email', 'assunto', 'mensagem', 'aceita_politica', 'aceita_newsletter'), false);

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_contactos") {





	if($_POST['form_hidden'] == "") {

		if($csrf->check_valid('post')) {

			if((isset($_POST[$form_seguranca['cod_res']]) && $_POST[$form_seguranca['cod_res']] == $_POST[$form_seguranca['cod_seg']]) && !isset($_POST['g-recaptcha-response']) && CAPTCHA_KEY == NULL) {

				$response = 1;

			}

			else {

				$response = isValidCaptcha($_POST['g-recaptcha-response']);

			}

			$response = 1; 

			

			

			if($response == 1) {



				if($_POST[$form_contactos['nome']] != "" && $_POST[$form_contactos['email']] != "" && $_POST[$form_contactos['mensagem']] != "" && isset($_POST[$form_contactos['aceita_politica']])) {		

					$formcontent = getHTMLTemplate("contacto.htm");

					$rodape = email_social();

								

					$nome = $_POST[$form_contactos['nome']];

					$email = $_POST[$form_contactos['email']];

					//$assunto = $_POST[$form_contactos['assunto']];

					$mensagem = nl2br($_POST[$form_contactos['mensagem']]);

					

					$mensagem_final = '

						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

							<tr>

								<td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>

							</tr>

						</table>

						<table width="100%" border="0" cellpadding="1" cellspacing="0">

						  <tr>

							<td height="20">&nbsp;</td>

							<td align="left" valign="middle">&nbsp;</td>

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

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$mensagem.'</td>

						  </tr>

						</table>';

					

					$posicao = strpos($mensagem, "<a");

					$posicao2 = strpos($mensagem, "#");

					

					if($posicao === false && $posicao2 === false) {	



						$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 1";

						$rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

						$rsNotificacoes->execute();

						$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

						DB::close();





						$titulo = $row_rsNotificacoes['assunto'];

						$subject = $row_rsNotificacoes['assunto'];

						

						$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

						$pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";	

						

						$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

						$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

						$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

						$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

						$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

					

						if($row_rsNotificacoes['email']) {				

							if($row_rsNotificacoes['resposta']) {

								envia_resposta($row_rsNotificacoes['id'], $nome, $email, $mensagem_final, $pagina_form);

							}

	

							sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email);

							####################################	

							header("location: ".$url_form."env=1");

							exit();

						}

					}

				}

			}

		}

	}

	else {

		header("location: ".$url_form."env=1");

		exit();

	}



}



$form_paginas = $csrf->form_names(array('nome', 'email', 'assunto', 'mensagem', 'aceita_politica'), false);

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_paginas") {

	if($_POST['form_hidden'] == "") {

		if($csrf->check_valid('post')) {

			if((isset($_POST[$form_seguranca['cod_res']]) && $_POST[$form_seguranca['cod_res']] == $_POST[$form_seguranca['cod_seg']]) && !isset($_POST['g-recaptcha-response']) && CAPTCHA_KEY == NULL) {

				$response = 1;

			}

			else {

				$response = isValidCaptcha($_POST['g-recaptcha-response']);

			}



			if($response == 1) {

				if($_POST[$form_paginas['nome']] != "" && $_POST[$form_paginas['email']] != "" && $_POST[$form_paginas['assunto']] != "" && $_POST[$form_paginas['mensagem']] != "" && isset($_POST[$form_paginas['aceita_politica']])) {		

					$formcontent = getHTMLTemplate("contacto.htm");

					$rodape = email_social();

										

					$nome = $_POST[$form_paginas['nome']];

					$email = $_POST[$form_paginas['email']];

					$assunto = $_POST[$form_paginas['assunto']];

					$mensagem = nl2br($_POST[$form_paginas['mensagem']]);

					

					$mensagem_final = '

						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

							<tr>

								<td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>

							</tr>

						</table>

						<table width="100%" border="0" cellpadding="1" cellspacing="0">

						  <tr>

							<td height="20">&nbsp;</td>

							<td align="left" valign="middle">&nbsp;</td>

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

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["assunto"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$assunto.'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$mensagem.'</td>

						  </tr>

						</table>';

					

					$posicao = strpos($mensagem, "<a");

					$posicao2 = strpos($mensagem, "#");

					

					if($posicao === false && $posicao2 === false) {		

						$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 6";

						$rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

						$rsNotificacoes->execute();

						$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

						DB::close();

						

						$titulo = $row_rsNotificacoes['assunto'];

						$subject = $row_rsNotificacoes['assunto'];

						

						$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

						$pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";	

						

						$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

						$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

						$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

						$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

						$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);



						if($row_rsNotificacoes['email']) {				

							if($row_rsNotificacoes['resposta']) {

								envia_resposta($row_rsNotificacoes['id'], $nome, $email, $mensagem_final, $pagina_form);

							}

	

							sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email);

							####################################

											

							header("location: ".$url_form."env=6");

							exit();

						}

					}

				}

			}

		}

	}

	else {

		header("location: ".$url_form."env=6");

		exit();

	}

}



$form_blog = $csrf->form_names(array('nome', 'localidade', 'mensagem', 'aceita_politica'), false);

if(isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "form_blog") {

	if($_POST['form_hidden'] == "") {

		if($csrf->check_valid('post')) {

			if($_POST[$form_blog['nome']] != "" && $_POST[$form_blog['localidade']] != "" && $_POST[$form_blog['mensagem']] != "" && $_POST['id_post'] != "" && isset($_POST[$form_blog['aceita_politica']])) {

				$id = $_POST['id_post'];



				$query_rsP = "SELECT url, titulo FROM blog_posts".$extensao." WHERE id='$id'";

				$rsP = DB::getInstance()->prepare($query_rsP);	

				$rsP->execute();

			 	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsP = $rsP->rowCount();



				if($totalRows_rsP > 0) {

					$blog = '<a href="'.ROOTPATH_HTTP.$row_rsP['url'].'" target="_blank">'.$row_rsP['titulo'].'</a>';



					#####################################

					$formcontent = getHTMLTemplate("contacto.htm");

					$rodape = email_social();

										

					$nome = $_POST[$form_blog['nome']];

					$localidade = $_POST[$form_blog['localidade']];

					$mensagem = nl2br($_POST[$form_blog['mensagem']]);

					$data = date('Y-m-d H:i:s');



					$insertSQL = "INSERT INTO blog_comments (id, post, nome, local, data, texto) VALUES ('', :post, :nome, :local, :data, :texto)";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':post', $id, PDO::PARAM_INT, 5);

					$rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);	

					$rsInsert->bindParam(':local', $localidade, PDO::PARAM_STR, 5);	

					$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);	

					$rsInsert->bindParam(':texto', $mensagem, PDO::PARAM_STR, 5);	

					$rsInsert->execute();

				

					$mensagem_final = '

						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">

							<tr>

								<td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>

							</tr>

						</table>

						<table width="100%" border="0" cellpadding="1" cellspacing="0">

						  <tr>

							<td height="20">&nbsp;</td>

							<td align="left" valign="middle">&nbsp;</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["blog_post"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$blog.'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["nome"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$nome.'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["localidade"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$localidade.'</td>

						  </tr>

						  <tr>

							<td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mensagem"].':</strong></td>

							<td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$mensagem.'</td>

						  </tr>

						</table>';

					

					$posicao = strpos($mensagem, "<a");

					$posicao2 = strpos($mensagem, "#");

					

					if($posicao === false && $posicao2 === false) {

						$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 8";

						$rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

						$rsNotificacoes->execute();

						$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

						

						$titulo = $row_rsNotificacoes['assunto'];

						$subject = $row_rsNotificacoes['assunto'];

						

						$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);	

						$pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";	

						

						$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);

						$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

						$formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);

						$formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);

						$formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);



						DB::close();



						if($row_rsNotificacoes['email']) {

							sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email);

							####################################

											

							header("location: ".$url_form."env=8");

							exit();

						}

					}

				}

			}

		}

	}

	else

	{

		header("location: ".$url_form."env=8");

		exit();

	}

}



function envia_resposta($id, $nome, $email, $campos, $pagina) {

	global $extensao, $Recursos; 

	

	$formcontent = getHTMLTemplate("contacto.htm");

	$rodape = email_social();

	

	$query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = '$id'";

	$rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);

	$rsNotificacoes->execute();

	$row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsNotificacoes = $rsNotificacoes->rowCount();

	DB::close();

	

	$titulo = $row_rsNotificacoes['assunto_cliente'];

	$subject = $row_rsNotificacoes['assunto_cliente'];

	

	$mensagem_final = $row_rsNotificacoes['resposta'];

	

	if(strpos($mensagem_final, '#nome#') !== false) {

		$mensagem_final = str_replace("#nome#", $nome, $mensagem_final);

	}

	

	$mensagem_mail = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

    	<tbody>

			<tr>

			  <td align="left" valign="top" style="font-family:arial; font-size:12px; line-height:18px; color:#3c3b3b;">'.$mensagem_final.'</td>

			</tr>

			<tr>

			  <td height="30">&nbsp;</td>

			</tr>

			<tr>

			  <td height="1" bgcolor="#dadada" style="line-height: 1px; font-size: 0px;"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="1" border="0"></td>

			</tr>

			<tr>

			  <td height="40">&nbsp;</td>

			</tr>

		</tbody>

	</table>'.$campos;

	

	$formcontent = str_replace ("#cpagina#",$pagina,$formcontent);

	$formcontent = str_replace ("#crodape#",$rodape,$formcontent);

	$formcontent = str_replace ("#ctitulo#",$titulo,$formcontent);

	$formcontent = str_replace ("#cmensagem#",$mensagem_mail,$formcontent);

	$formcontent = str_replace ("#tit_mail_compr#",$Recursos->Resources["car_mail_7"],$formcontent);

	

	sendMail($email,'',$formcontent,$formcontent,$subject);

}



?>