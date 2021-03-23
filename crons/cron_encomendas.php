<?php $is_cron_file = 1; include_once("../Connections/connADMIN.php"); ?>
<?php //ini_set("display_errors", 1);

/*********************************************************************************************************/

/************************************* Encomenda por pagar há X dias *************************************/

/*********************************************************************************************************/

if(isset($_GET['op']) && $_GET['op'] == "30294823f9cnuioyiwqyec0819047108943uyxdnoi13uyio2") {
	$query_rsDias = "SELECT dias FROM textos_notificacoes_pt WHERE id = '5'";
	$rsDias = DB::getInstance()->prepare($query_rsDias);
	$rsDias->execute();
	$row_rsDias = $rsDias->fetch(PDO::FETCH_ASSOC);
	
	$dias_notifica = $row_rsDias["dias"];

	if($dias_notifica > 0) {
		$hoje = date("Y-m-d");
		$data_notifica = date("Y-m-d", strtotime("-".$dias_notifica." days"))." 00:00:00";
		
		// notificar clientes após X dias
		$query_rsP = "SELECT * FROM encomendas WHERE estado = '1' AND notificado = '0' AND data <= '$data_notifica'";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->execute();
		$row_rsP = $rsP->fetchAll();
		$totalRows_rsP = $rsP->rowCount();	
		
		if($totalRows_rsP > 0) {
			foreach($row_rsP as $row_rsEncomenda) {
				$query_rsUpd = "UPDATE encomendas SET notificado = '1' WHERE id = '".$row_rsEncomenda["id"]."'";
				$rsUpd = DB::getInstance()->prepare($query_rsUpd);
				$rsUpd->execute();

				$nome = $row_rsEncomenda['nome'];
				$email = $row_rsEncomenda['email'];
				$id_enc = $row_rsEncomenda['id'];
				$numero_enc = $row_rsEncomenda['numero'];
				$data = date("d-m-Y", strtotime($row_rsEncomenda['data']));
				$valor = $row_rsEncomenda['valor_c_iva'];
				
				$lingua = "pt";
				if($row_rsEncomenda["lingua"]) $lingua = $row_rsEncomenda["lingua"];
				
				include_once(ROOTPATH."linguas/".$lingua.".php");
				$className = 'Recursos_'.$lingua;
				$Recursos = new $className();

				$query_rsTexto = "SELECT * FROM textos_notificacoes_".$lingua." WHERE id = '5'";
				$rsTexto = DB::getInstance()->prepare($query_rsTexto);
				$rsTexto->execute();
				$row_rsTexto = $rsTexto->fetch(PDO::FETCH_ASSOC);
				
				$link_paypal="";
				$outros_pag = "";
				$pagamento = $row_rsEncomenda['met_pagamt_id'];

				if($pagamento == 1) {
					$link_paypal = $row_rsEncomenda['url_paypal'];
				} 
				else {
					$query_rsQtds = "SELECT * FROM met_pagamento_".$lingua." WHERE id='$pagamento'";
					$rsQtds = DB::getInstance()->prepare($query_rsQtds);
					$rsQtds->execute();
					$row_rsQtds = $rsQtds->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsQtds = $rsQtds->rowCount();
					
					if($row_rsQtds['imagem'] != '' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsQtds['imagem'])) $img = ROOTPATH_HTTP."imgs/carrinho/".$row_rsQtds['imagem'];
					$descricao = "";
					$descricao2 = "";
					if($row_rsQtds['descricao']) {
						$descricao.= $row_rsQtds['descricao'];
					}
					if($row_rsQtds['descricao2']) {
						$descricao2.= "<br>".$row_rsQtds['descricao2'];
					}
					
					if($pagamento == 4 && $descricao == "") $descricao = $row_rsQtds["nome"];
					
					$outros_pag = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tbody>
						<tr>
						  <td width="100"><img src='.$img.' width="100" style="display: block; max-width:100px"></td>
						  <td width="30">&nbsp;</td>
						  <td style="font-family:arial; font-size:12px; line-height:18px; color:#333333;">';
						  if($pagamento == 6) {
							  $outros_pag .= $Recursos->Resources["comprar4_entidade"].": ".$row_rsQtds['entidade']."<br>";
							  $outros_pag .= $Recursos->Resources["comprar4_referencia"].": ".$row_rsEncomenda['ref_pagamento']."<br>";
							  $outros_pag .= $Recursos->Resources["comprar4_montante"].": ".number_format($row_rsEncomenda['valor_c_iva'], 2,',', '.')." EUR";
						  } 
						  else {
							  if($descricao) $outros_pag .= $descricao;
						  }
						  $outros_pag .= '</td>
						</tr>
					  </tbody>
					</table>';
				}
				
				// envia email
				$formcontent = getHTMLTemplate("contacto.htm");

				if($pagamento == 1) $pagamento_txt = '<a href="'.$link_paypal.'" style="color: #011f5b; font-weight: bold; font-size: 14px;">'.$Recursos->Resources["notificacao_paypal"].'</a>';
				else $pagamento_txt = "<strong>".$Recursos->Resources["notificacao_pag"].":</strong><br><br>".$outros_pag;

				$mensagem = $row_rsTexto['texto'];
				$mensagem = str_replace('#nome#', $nome, $mensagem);
				$mensagem = str_replace('#enc#', $numero_enc, $mensagem);
				$mensagem = str_replace('#data#', $data, $mensagem);
				$mensagem = str_replace('#dias#', $dias_notifica, $mensagem);
				$mensagem = str_replace('#preco#', number_format($valor, 2, ",", ".")."&pound;", $mensagem);
				$mensagem .= "<br><br>".$pagamento_txt;

				$rodape = email_social();
					
				$titulo = $Recursos->Resources["notificacao_tit"];
				$subject = $row_rsTexto['assunto'];
				
				$formcontent = str_replace("#ctitulo#", $titulo, $formcontent);	
				$formcontent = str_replace("#cmensagem#", $mensagem, $formcontent);
				$formcontent = str_replace("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);
				$formcontent = str_replace("#crodape#", $rodape, $formcontent);

				$pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."</a>";		
				$formcontent = str_replace("#cpagina#", $pagina_form, $formcontent);
						
				sendMail($email, '', $formcontent, $mensagem, $subject);
				####################################
			}
		}	
	}

	DB::close();
}


/************************************************************************************************************/

/************************************* Anular encomendas após há X dias *************************************/

/************************************************************************************************************/

if(isset($_GET['op']) && $_GET['op'] == "asjkdyhukweycv54ci3y5cb73c58i7589uiocy73567ibuc") {
	$query_rsDias = "SELECT dias FROM textos_notificacoes_pt WHERE id = '6'";
	$rsDias = DB::getInstance()->prepare($query_rsDias);
	$rsDias->execute();
	$row_rsDias = $rsDias->fetch(PDO::FETCH_ASSOC);
		
	
	$dias_anula = $row_rsDias["dias"];

	if($dias_anula > 0) {
		$hoje = date("Y-m-d");
		$data_anula = date("Y-m-d", strtotime("-".$dias_anula." days"))." 00:00:00";

		$query_rsP = "SELECT * FROM encomendas WHERE estado = '1' AND notificado='1' AND data <= '$data_anula'";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->execute();
		$row_rsP = $rsP->fetchAll();
		$totalRows_rsP = $rsP->rowCount();	
	
		if($totalRows_rsP > 0) {
			$array_enc = array();
			foreach($row_rsP as $row_rsEncomenda) {
				$nome = $row_rsEncomenda['nome'];
				$email = $row_rsEncomenda['email'];
				$id_enc = $row_rsEncomenda['id'];
				$numero_enc = $row_rsEncomenda['numero'];
				$data = date("d-m-Y", strtotime($row_rsEncomenda['data']));
				$valor = $row_rsEncomenda['valor_c_iva'];
				
				$array_enc[$numero_enc] = $nome;
				
				//Anular a encomenda
				$insertSQL = "UPDATE encomendas SET estado='5' WHERE id='".$id_enc."'";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->execute();

				//Guardar histórico
				$data_hist = date("Y-m-d H:i:s");

				$insertSQL = "INSERT INTO enc_estados_historico (id, id_encomenda, estado, data, notificado, automatico) VALUES ('', :id, 5, :data, 1, 1)";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(":data", $data_hist, PDO::PARAM_STR, 5);
				$rsInsert->bindParam(":id", $id_enc, PDO::PARAM_INT);
				$rsInsert->execute();
				
				$lingua = "pt";
				if($row_rsEncomenda["lingua"]) $lingua = $row_rsEncomenda["lingua"];
				
				include_once(ROOTPATH."linguas/".$lingua.".php");
				$className = 'Recursos_'.$lingua;
				$Recursos = new $className();

				$query_rsTexto = "SELECT * FROM textos_notificacoes_".$lingua." WHERE id = '6'";
				$rsTexto = DB::getInstance()->prepare($query_rsTexto);
				$rsTexto->execute();
				$row_rsTexto = $rsTexto->fetch(PDO::FETCH_ASSOC);
				
				// envia email
				$formcontent = getHTMLTemplate("contacto.htm");
					
				$mensagem = $row_rsTexto['texto'];
				$mensagem = str_replace('#nome#', $nome, $mensagem);
				$mensagem = str_replace('#dias#', $dias_anula, $mensagem);
				$mensagem = str_replace('#enc#', $numero_enc, $mensagem);
				$mensagem = str_replace('#data#', $data, $mensagem);
				$mensagem = str_replace('#preco#', number_format($valor,2,",","")."&pound;", $mensagem);

				$rodape = email_social();
					
				$titulo = $Recursos->Resources["notificacao_tit"];
				$subject = $row_rsTexto['assunto'];
				
				$formcontent = str_replace("#ctitulo#", $titulo, $formcontent);	
				$formcontent = str_replace("#cmensagem#", $mensagem, $formcontent);
				$formcontent = str_replace("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);
				$formcontent = str_replace("#crodape#", $rodape, $formcontent);

				$pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."</a>";		
				$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);
						
				sendMail($email, '', $formcontent, $mensagem, $subject);
				####################################
				
				?>
				<!--INÍCIO ESTATÍSTICAS DE VENDA--> 
				<script type="text/javascript">
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', '<?php echo ANALYTICS; ?>']);
				_gaq.push(['_trackPageview']);
				_gaq.push(['_addTrans',
						  '<?php echo $row_rsEncomenda['id']; ?>',           // order ID - required
						  '<?php echo NOME_SITE; ?>', // affiliation or store name
						  '-<?php echo $row_rsEncomenda['valor_c_iva']; ?>',          // total - required
						  '0',           // tax
						  '-<?php echo $row_rsEncomenda['portes_entrega']; ?>',              // shipping
						  '',       // city
						  '<?php echo $row_rsEncomenda['pais_envio']; ?>'             // country
						  ]);
				
				// add item might be called for every item in the shopping cart
				// where your ecommerce engine loops through each item in the cart and
				// prints out _addItem for each
				<?php
				
				$query_rsCarrinhoFinal = "SELECT * FROM encomendas_produtos WHERE id_encomenda='$id_enc' ORDER BY id ASC";
				$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);
				$rsCarrinhoFinal->execute();
				$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();
				
					while($row_rsCarrinhoFinal = $rsCarrinhoFinal->fetch()) {
						if($row_rsCarrinhoFinal["cheque_prenda"] == 1) {
							$ref = NOME_SITE;
							$row_rsCategoria["nome"] = "Cheque Prenda";			
						}
						else {
							$ref = $row_rsCarrinhoFinal['ref'];
							if(!$ref) $ref = $row_rsCarrinhoFinal['produto_id'];
							if($row_rsCarrinhoFinal['opcoes']) {
								$ref .= "_".$count;
							}
							
							$id_p = $row_rsCarrinhoFinal["produto_id"];
							
							$query_rsPeca = "SELECT categoria FROM  l_pecas".$extensao." WHERE id='$id_p'";
							$rsPeca = DB::getInstance()->prepare($query_rsPeca);
							$rsPeca->execute();
							$row_rsPeca = $rsPeca->fetch(PDO::FETCH_ASSOC);
							
							$query_rsCategoria = "SELECT nome FROM l_categorias".$extensao." WHERE id='".$row_rsPeca['categoria']."'";
							$rsCategoria = DB::getInstance()->prepare($query_rsCategoria);
							$rsCategoria->execute();
							$row_rsCategoria = $rsCategoria->fetch(PDO::FETCH_ASSOC);
						}
						
						$preco = $row_rsCarrinhoFinal['preco'];
						?>
						_gaq.push(['_addItem',
						  '<?php echo $row_rsEncomenda['id']; ?>',           // order ID - required
						  '<?php echo trim($ref); ?>',           // SKU/code - required
						  '<?php echo addslashes(trim($row_rsCarrinhoFinal['produto'])); ?>',        // product name
						  '<?php echo addslashes($row_rsCategoria['nome']); ?>',   // category or variation
						  '<?php echo $preco; ?>',          // unit price - required
						  '-<?php echo $row_rsCarrinhoFinal['qtd']; ?>'               // quantity - required
						  ]);
				<?php } ?>
				_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
				
				(function() {
				 var ga = document.createElement('script'); ga.type = 'text/javascript';
				 ga.async = true;
				 ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
						   'http://www') + '.google-analytics.com/ga.js';
				 var s = document.getElementsByTagName('script')[0];
				 s.parentNode.insertBefore(ga, s);
				 })();
				
				</script> 
				<!--FIM ESTATÍSTICAS DE VENDA-->
				<?php
			}

			// envia email para administração
			$formcontent = getHTMLTemplate("contacto.htm");
			
			$mensagem = "<br>As seguintes encomendas foram anuladas automaticamente por falta de pagamento:<br>";
			foreach($array_enc as $key=>$value) {
				$mensagem .= '<br>Encomenda <strong>'.$key.'</strong> do cliente <strong>'.$value.'</strong>;';
			}
				
			$rodape = email_social();
				
			$titulo = "Encomendas anuladas";
			$subject = "Encomendas anuladas automaticamente no website - ".SERVIDOR;
			
			$formcontent = str_replace("#ctitulo#", $titulo, $formcontent);	
			$formcontent = str_replace("#cmensagem#", $mensagem, $formcontent);
			$formcontent = str_replace("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);
			$formcontent = str_replace("#crodape#", $rodape, $formcontent);

			$pagina_form = "Homepage<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."</a>";		
			$formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);
			
			$query_rsCont = "SELECT * FROM notificacoes_pt WHERE id='3'";
			$rsCont = DB::getInstance()->prepare($query_rsCont);
			$rsCont->execute();
			$row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsCont = $rsCont->rowCount();
			
			if($row_rsCont["email"]) {
				sendMail($row_rsCont["email"], '', $formcontent, $mensagem, $subject, $row_rsCont['email2'], $row_rsCont['email3']);
			}
			
			####################################
		}
	}

	DB::close();
}

?>
Feito.
