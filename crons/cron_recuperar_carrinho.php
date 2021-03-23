<?php $is_cron_file = 1; require_once('../Connections/connADMIN.php'); ?>
<?php //ini_set('display_errors', 1);

@set_time_limit(0);

function randomCodeCarrinho($size = '24') {
  $string = '';
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  for($i = 0; $i < $size; $i++) {
    $string .= $characters[mt_rand(0, (strlen($characters) - 1))];  
  }

  $query_rsExists = "SELECT id FROM carrinho_cliente_hist WHERE codigo = '$string'";
  $rsExists = DB::getInstance()->prepare($query_rsExists);
  $rsExists->execute();
  $totalRows_rsExists = $rsExists->rowCount();
  DB::close();

  if($totalRows_rsExists == 0) {
    return $string;
  }
  else {
    return randomCodeCarrinho();
  }
}

if(isset($_GET['op']) && $_GET['op'] == '23890rudioasghd89y3uiedga9dyui23hdjksadg') {
	//Obter nº de horas que devem passar até enviar o email com o aviso
	$query_rsConfig = "SELECT horas FROM textos_notificacoes_pt WHERE id = 7";
	$rsConfig = DB::getInstance()->prepare($query_rsConfig);
	$rsConfig->execute();
	$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);

	$horas = $row_rsConfig['horas'];

	//0 » Desativar envio de emails
	if($horas > 0) {
		$data_hoje = date('Y-m-d H'); 
		$data_carrinho = date('Y-m-d H', strtotime('-'.$horas.' hours'));

		$query_rsCarCliente = "SELECT id_cliente FROM carrinho_cliente WHERE id_cliente > 0 AND data LIKE '$data_carrinho%' AND enviado = 0";
		$rsCarCliente = DB::getInstance()->prepare($query_rsCarCliente);
		$rsCarCliente->execute();
		$row_rsCarCliente = $rsCarCliente->fetchAll();
		$totalRows_rsCarCliente = $rsCarCliente->rowCount();

		if($totalRows_rsCarCliente > 0) {
			foreach($row_rsCarCliente as $car_cliente) {
				$id_cliente = $car_cliente['id_cliente'];

				$query_rsCliente = "SELECT email, nome, lingua FROM clientes WHERE id = :user";
				$rsCliente = DB::getInstance()->prepare($query_rsCliente);
				$rsCliente->bindParam(':user', $id_cliente, PDO::PARAM_INT);
				$rsCliente->execute();
				$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsCliente = $rsCliente->rowCount();

				$email = $row_rsCliente['email'];
				$nome = $row_rsCliente['nome'];

				$lang = $row_rsCliente['lingua'];
				if(!$lang) {
					$lang = "pt";
				}

				include_once(ROOTPATH."linguas/".$lang.".php");
				$className = 'Recursos_'.$lang;
				$Recursos = new $className();

				$query_rsConfigTextos = "SELECT assunto, texto FROM textos_notificacoes_$lang WHERE id = 7";
				$rsConfigTextos = DB::getInstance()->prepare($query_rsConfigTextos);
				$rsConfigTextos->execute();
				$row_rsConfigTextos = $rsConfigTextos->fetch(PDO::FETCH_ASSOC);

				$subject = $row_rsConfigTextos['assunto'];
				$desc = $row_rsConfigTextos['texto'];

				//Substituir possíveis tags no texto
				$subject = str_replace("#nome#", $nome, $subject);	
				$desc = str_replace("#nome#", $nome, $desc);	
					
				$query_rsCarrinhoFinal = "SELECT produto, opcoes, preco, quantidade FROM carrinho WHERE id_cliente = :user ORDER BY produto ASC";
				$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);
				$rsCarrinhoFinal->bindParam(':user', $id_cliente, PDO::PARAM_INT);
				$rsCarrinhoFinal->execute();
				$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();
				
				$mailprodutos = '';
				$total_produtos = 0;
				$total_carrinho = 0;

				if($totalRows_rsCarrinhoFinal > 0) {
					$mailprodutos = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
	          <tbody>
	            <tr>';
					
					while($row_rsCarrinhoFinal = $rsCarrinhoFinal->fetch()) {
						$total_produtos ++;
						$total_carrinho += ($row_rsCarrinhoFinal['preco'] * $row_rsCarrinhoFinal['quantidade']);

						$query_rsProd = "SELECT ref, nome, imagem4 FROM l_pecas_$lang WHERE id = :prod";
						$rsProd = DB::getInstance()->prepare($query_rsProd);
						$rsProd->bindParam(':prod', $row_rsCarrinhoFinal['produto'], PDO::PARAM_INT);
						$rsProd->execute();
						$row_rsProd = $rsProd->fetch(PDO::FETCH_ASSOC);
						$totalRows_rsProd = $rsProd->rowCount();

						$ref_produto = $row_rsProd['ref'];
						$nome_produto = $row_rsProd['nome'];
						$opcoes = $row_rsCarrinhoFinal['opcoes'];

						$img_produto = "imgs/elem/geral.jpg";
						if($row_rsProd['imagem4'] != "" && file_exists(ROOTPATH.'imgs/produtos/'.$row_rsProd['imagem4'])) {
							$img_produto = 'imgs/produtos/'.$row_rsProd['imagem4'];
						}

						$mailprodutos .= '<tr>
						  <td colspan="5" height="10"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="10" border="0"></td>
						</tr>
						<tr>
              <td width="20"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif" width="20"></td>
              <td width="80" valign="middle" align="left"><img src="'.ROOTPATH_HTTP.$img_produto.'" width="80"></td>
							<td width="20"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif" width="20"></td>
							<td valign="top" align="left">
								<table width="100%" border="0">
								  <tbody>
									  <tr>
										  <td style="font-family: arial; font-size: 12px; line-height: 18px; color: #3c3b3b;">Ref.: '.$ref_produto.'</td>
										</tr>
										<tr>
										  <td style="font-family: arial; font-size: 12px; line-height: 18px; color: #3c3b3b;">'.$nome_produto.'</td>
										</tr>
										<tr>
										  <td style="font-family: arial; font-size: 11px; line-height: 18px; color: #3c3b3b;">'.$opcoes.'</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="20"><img src="'.ROOTPATH_HTTP.'imgs/elem/fill.gif" width="20"></td>
            </tr>';

						if($total_produtos == $totalRows_rsCarrinhoFinal) {
							$mailprodutos .= '
						<tr>
						  <td colspan="5" height="30"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="30" border="0"></td>
						</tr>';
						}
						else {
							$mailprodutos .= '
						<tr>
						  <td colspan="5" height="10"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="10" border="0"></td>
						</tr>
            <tr>
						  <td colspan="5" height="1" bgcolor="#dadada"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="1" border="0"></td>
						</tr>';
						}
					}	

					$mailprodutos .= '</tr>
	          </tbody>
	        </table>';	
				}

				$link = '<a href="'.ROOTPATH_HTTP.'login.php?rc=1" target="_blank" style="text-decoration: none; color: #ffffff;">'.$Recursos->Resources["finalizar_encomenda"].'</a>';

				$mensagem_final = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    	<tbody>
						<tr>
						  <td align="left" valign="top" style="font-family:arial; font-size:12px; line-height:18px; color:#3c3b3b;">'.$desc.'</td>
						</tr>
						<tr>
						  <td height="30"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="1" border="0"></td>
						</tr>
					</tbody>
				</table>
				<table border="0" cellspacing="0" cellpadding="0" align="center">
          <tbody>
            <tr>
              <td width="180" align="center" valign="middle" height="40" style="font-family: arial; font-weight: bold; font-size:13px; background-color: #5d5d5c; padding: 0 20px; line-height: 20px; color: #ffffff;">'.$link.'</td>
            </tr>
          </tbody>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    	<tbody>
						<tr>
						  <td height="30"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="1" border="0"></td>
						</tr>
						<tr>
						  <td height="1" bgcolor="#dadada"><img src="'.HTTP_DIR.'/imgs/elem/fill.gif" height="1" border="0"></td>
						</tr>
					</tbody>
				</table>'.$mailprodutos;

				$formcontent = getHTMLTemplate("contacto.htm");

				$pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."</a>";   
				$rodape = email_social();

				$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);
			  $formcontent = str_replace ("#crodape#", $rodape, $formcontent);
			  $formcontent = str_replace ("#ctitulo#", $subject, $formcontent);
			  $formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);
			  $formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

			  //Adicionar um código no final para saber quais os emails abertos
				$codigo = randomCodeCarrinho();
				$formcontent .= '<img src="'.ROOTPATH_HTTP.'carrinho_proceder_visto.php?op=sdaioh2380usdo8f7804hf08y3hfiyf8923yh9&code='.$codigo.'" width="1" height="1" border="0" />';

				sendMail($email, '', $formcontent, $mensagem_final, $subject);

				$data = date('Y-m-d H:i:s');

				$insertSQL = "UPDATE carrinho_cliente SET enviado = 1, data_enviado = :data WHERE id_cliente = :user";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);
				$rsInsertSQL->bindParam(':user', $id_cliente, PDO::PARAM_INT);
				$rsInsertSQL->execute();

				$insertSQL = "SELECT MAX(id) FROM carrinho_cliente_hist";
		    $rsInsert = DB::getInstance()->prepare($insertSQL);
		    $rsInsert->execute();
		    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		    
		    $max_id = $row_rsInsert["MAX(id)"] + 1;

		    //Guardar sem o ID Cliente, serve apenas para manter um histórico
				$insertSQL = "INSERT INTO carrinho_cliente_hist (id, nome, email, data_enviado, total_produtos, total_carrinho, codigo) VALUES (:id, :nome, :email, :data, :total_produtos, :total_carrinho, :codigo)";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->bindParam(':id', $max_id, PDO::PARAM_INT);
				$rsInsertSQL->bindParam(':nome', $row_rsCliente['nome'], PDO::PARAM_STR, 5);
				$rsInsertSQL->bindParam(':email', $row_rsCliente['email'], PDO::PARAM_STR, 5);
				$rsInsertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);
				$rsInsertSQL->bindParam(':total_produtos', $total_produtos, PDO::PARAM_INT);
				$rsInsertSQL->bindParam(':total_carrinho', $total_carrinho, PDO::PARAM_STR, 5);
				$rsInsertSQL->bindParam(':codigo', $codigo, PDO::PARAM_STR, 5);
				$rsInsertSQL->execute();
			}
		}
	}

	DB::close();
}

?>
Feito.