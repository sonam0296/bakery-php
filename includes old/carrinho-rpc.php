
<?php

	require_once('../Connections/connADMIN.php');
$reviwer_id = $row_rsCliente['roll'];

$query_rsRole = "SELECT * FROM roll WHERE roll_name = :reviwer_id ";
$rsRole = DB::getInstance()->prepare($query_rsRole);
$rsRole->bindParam(':reviwer_id', $reviwer_id, PDO::PARAM_STR, 5);
$rsRole->execute();
$totalRows_rsRole = $rsRole->fetch(PDO::FETCH_ASSOC);
DB::close();

/* Alterar moeda do website */
if($_POST['op'] == "mudaMoeda") {
	$currency = $_POST['currency'];
	
	// estrutura do cookie: moeda-simbolo
	$class_carrinho->mudaMoeda($currency);
}

/* Adicionar/Remover dos Favoritos */
if($_POST['op'] == "favorito_produto") {
	$id_cliente = 0;
	
	if($row_rsCliente['id']) {
		$id_cliente = $row_rsCliente['id'];
	}

	$wish_session = $_COOKIE[WISHLIST_SESSION];
  $ses_id_old = strtotime(date("YmdHis", strtotime("-5 days"))); //5 dias atr�s
  
  if($wish_session == "" || $wish_session <= $ses_id_old) {
    $ses_id = strtotime(date("YmdHis", time()));
    
    $insertSQL = "DELETE FROM lista_desejo WHERE session < '$ses_id_old'";
    $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
    $rsInsertSQL->execute();
    
    $timeout = 3600*24*5; //5 dias
    setcookie(WISHLIST_SESSION, $ses_id, time()+$timeout, "/", "", $cookie_secure, true);
    $wish_session = $ses_id;
  }

	$info = json_decode($_POST['form_data']);

	$produto = $info->produto;
	$tipo = $info->tipo;

	$query_rsP = "SELECT nome FROM l_pecas".$extensao." WHERE visivel='1' AND id=:id";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $produto, PDO::PARAM_INT);
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();

	$nome = $row_rsP['nome'];
	$preco = $class_produtos->precoProduto($produto, 0);

	$arr_opcoes = array();
	if($info->opcoes) {
		$opcoes = $info->opcoes;	
		foreach($opcoes as $opc) {
			$arr_opcoes[$opc->grupo] = array($opc->opcao, $opc->opcaoVal, $opc->opcaoPrice);
		}
	}

	$arr_opcoes = serialize($arr_opcoes);

	if($tipo == 1) {
		$query_rsFavoritos = "SELECT id FROM lista_desejo WHERE produto = :id AND (cliente=:cliente OR session=:session)";
		$rsFavoritos = DB::getInstance()->prepare($query_rsFavoritos);
    $rsFavoritos->bindParam(':id', $produto, PDO::PARAM_STR, 5);
    $rsFavoritos->bindParam(':cliente', $id_cliente, PDO::PARAM_STR, 5);
    $rsFavoritos->bindParam(':session', $wish_session, PDO::PARAM_STR, 5);
    $rsFavoritos->execute();
    $row_rsFavoritos = $rsFavoritos->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsFavoritos = $rsFavoritos->rowCount();

    $ult_data = date('Y-m-d H:i:s');

    if($totalRows_rsFavoritos > 0) {
			$query_rsInsert = "UPDATE lista_desejo SET preco=:preco, opcoes=:opcoes, ult_atualizacao=:ult_atualizacao WHERE produto=:produto AND (cliente=:cliente OR session=:session)";
			$rsInsert = DB::getInstance()->prepare($query_rsInsert);
			$rsInsert->bindParam(':preco', $preco, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':opcoes', $arr_opcoes, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':ult_atualizacao', $ult_data, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':produto', $produto, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':session', $wish_session, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':cliente', $id_cliente, PDO::PARAM_STR, 5);
			$rsInsert->execute();

			echo "Updated";
		}
		else {
			$insertSQL = "UPDATE l_pecas".$extensao." SET favoritos = favoritos + 1 WHERE id=:produto";
			$Result1 = DB::getInstance()->prepare($insertSQL);
			$Result1->bindParam(':produto', $produto, PDO::PARAM_STR, 5);
			$Result1->execute();

			$query_rsInsert = "INSERT INTO lista_desejo (cliente, session, produto, nome, preco, opcoes, ult_atualizacao) VALUES (:cliente, :session, :produto, :nome, :preco, :opcoes, :ult_atualizacao)";
			$rsInsert = DB::getInstance()->prepare($query_rsInsert);
			$rsInsert->bindParam(':cliente', $id_cliente, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':session', $wish_session, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':produto', $produto, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':preco', $preco, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':opcoes', $arr_opcoes, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':ult_atualizacao', $ult_data, PDO::PARAM_STR, 5);
			$rsInsert->execute();

			echo "Inserted";
		}
	}
	else {
		$insertSQL = "UPDATE l_pecas".$extensao." SET favoritos = favoritos - 1 WHERE id=:produto";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->bindParam(':produto', $produto, PDO::PARAM_STR, 5);
		$Result1->execute();
		
		$insertSQL = "DELETE FROM lista_desejo WHERE produto = :produto AND (cliente=:cliente OR session=:session)";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->bindParam(':produto', $produto, PDO::PARAM_STR, 5);
		$Result1->bindParam(':session', $wish_session, PDO::PARAM_STR, 5);
		$Result1->bindParam(':cliente', $id_cliente, PDO::PARAM_STR, 5);
		$Result1->execute();

		echo "Deleted";
	}

	DB::close();
}

/* Seguir/Deixar de seguir o precoo de um produto */
if($_POST['op'] == "seguir_preco") {
	$id = $_POST['id'];
	$opcao1 = $_POST['opcao_1'];
	$opcao2 = $_POST['opcao_2'];
	$tipo = $_POST['tipo'];
	$cliente = $_POST['cliente'];
	
	$preco = $class_produtos->precoProduto($id, 1, 1);

	$id_opcao = 0;
	if($opcao1 && $opcao2) {
		$query_rsTamanho = "SELECT id FROM l_pecas_tamanhos WHERE peca = '$id' AND op1 = '$opcao1' AND op2 = '$opcao2'";
		$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
		$rsTamanho->execute();
		$totalRows_rsTamanho = $rsTamanho->rowCount();
		$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

		if($totalRows_rsTamanho > 0) {
			$id_opcao = $row_rsTamanho['id'];
			$preco = $class_produtos->precoProduto($id, 1, 1, $id_opcao);
		}
	}
	else if($opcao1) {
		$query_rsTamanho = "SELECT id FROM l_pecas_tamanhos WHERE peca = '$id' AND op1 = '$opcao1' AND op2 = 0";
		$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
		$rsTamanho->execute();
		$totalRows_rsTamanho = $rsTamanho->rowCount();
		$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

		if($totalRows_rsTamanho > 0) {
			$id_opcao = $row_rsTamanho['id'];
			$preco = $class_produtos->precoProduto($id, 1, 1, $id_opcao);
		}
	}
	else if($opcao2) {
		$query_rsTamanho = "SELECT id FROM l_pecas_tamanhos WHERE peca = '$id' AND op1 = '$opcao2' AND op2 = 0";
		$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
		$rsTamanho->execute();
		$totalRows_rsTamanho = $rsTamanho->rowCount();
		$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

		if($totalRows_rsTamanho > 0) {
			$id_opcao = $row_rsTamanho['id'];
			$preco = $class_produtos->precoProduto($id, 1, 1, $id_opcao);
		}
	}
	
	if($tipo == 1) {
		$insertSQL = "UPDATE l_pecas".$extensao." SET seguir_preco = seguir_preco + 1 WHERE id=".$id."";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->execute();
		
		$insertSQL = "INSERT INTO l_pecas_seguir (id_cliente, id_produto, id_opcao, preco) VALUES ('".$cliente."', '".$id."', '".$id_opcao."', '".$preco."')";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->execute();
	}
	else {
		$insertSQL = "UPDATE l_pecas".$extensao." SET seguir_preco = seguir_preco - 1 WHERE id=".$id."";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->execute();
		
		$insertSQL = "DELETE FROM l_pecas_seguir WHERE id_produto = '".$id."' AND id_cliente = '".$cliente."'";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->execute();
	}

	DB::close();
}

/* Altera imagem do produto */
if($_POST['op'] == 'alteraImgProd') { 
	$prod = $_POST['produto'];
	$tam1 = $_POST['op1'];
	$tam2 = $_POST['op2'];
	$tam3 = $_POST['op3'];
	$tam4 = $_POST['op4'];
	$tam5 = $_POST['op5'];

	$query_rsT = "SELECT imagem.id_tamanho FROM l_pecas_tamanhos AS tam LEFT JOIN l_pecas_imagens AS imagem ON imagem.id_tamanho = tam.id WHERE tam.peca='$prod' AND tam.op1='$tam1' AND tam.op2='$tam2' AND tam.op3='$tam3' AND tam.op4='$tam4' AND tam.op5='$tam5'";
	$rsT = DB::getInstance()->prepare($query_rsT);
	$rsT->execute();
	$row_rsT = $rsT->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsT = $rsT->rowCount();
	DB::close();

	echo $row_rsT["id_tamanho"];
}

/* Atualiza os totais do carrinho */
if($_POST['op'] == 'update_values') {
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	$totalRows_rsTopoCarrinho = 0;
		
	if($carrinho_session) {
		$query_rsTopoCarrinho = "SELECT pecas.id, pecas.nome, pecas.url, carrinho.quantidade, carrinho.preco, carrinho.desconto, carrinho.id AS id_linha FROM carrinho LEFT JOIN l_pecas".$extensao." AS pecas ON (carrinho.produto = pecas.id AND pecas.visivel = 1) WHERE carrinho.session = '$carrinho_session' ORDER BY pecas.ordem ASC";
		$rsTopoCarrinho = DB::getInstance()->query($query_rsTopoCarrinho);
		$row_rsTopoCarrinho = $rsTopoCarrinho->fetchAll();
		$totalRows_rsTopoCarrinho = $rsTopoCarrinho->rowCount();
		DB::close();
	}
	
	$valor_produtos = 0;
	$total_produtos = 0;
	
	if($totalRows_rsTopoCarrinho > 0) {
		foreach($row_rsTopoCarrinho as $carrinho_prods) { 
			if($carrinho_prods['cheque_prenda'] == 1) {
				$valor_produtos += $carrinho_prods['preco'];
				$total_produtos += $carrinho_prods['preco'];
			}
			else {
				$preco_final = $carrinho_prods['preco'];
				
				if($carrinho_prods["desconto"] > 0) {
					$preco_final = $preco_final - ($preco_final * ($carrinho_prods["desconto"] / 100));
				}
				
				$valor_produtos += $carrinho_prods['quantidade'] * $preco_final;
				$total_produtos += $carrinho_prods['quantidade'];
			}
		}
	}
	
	echo $class_carrinho->mostraPreco($valor_produtos)."###".$total_produtos;
}

//UPDATE FAVORITOS
if($_POST['op'] == 'update_favs'){
	if($row_rsCliente != 0) {
		$id_cliente = $row_rsCliente['id'];
		$where = "lista.cliente = '$id_cliente'";
	}
	else {
		$wish_session = $_COOKIE[WISHLIST_SESSION];
		$where = "lista.session = '$wish_session'";
	}
	$query_rsFavoritos = "SELECT lista.id FROM lista_desejo AS lista LEFT JOIN l_pecas".$extensao." AS pecas ON lista.produto = pecas.id WHERE ".$where." AND pecas.visivel = 1 GROUP BY pecas.id ORDER BY pecas.ordem ASC";
	$rsFavoritos = DB::getInstance()->prepare($query_rsFavoritos);
	$rsFavoritos->execute();
	$totalRows_rsFavoritos = $rsFavoritos->rowCount();

	echo($totalRows_rsFavoritos); 
}

/* Carrega os produtos para o "dropdown" do carrinho */
if($_POST['op'] == 'carrega_produtos') { ?>
  <ul class="carrinho_resumo"><?php echo $class_carrinho->carrinhoDivs("dropdown"); ?></ul>
<?php }

/* Modal com vista rapida do produto */
if($_POST['op'] == 'carregaModal') {
	$produto = $_POST['id'];
	
	$query_rsProduto = "SELECT pecas.*, marcas.imagem2 AS img_marca, marcas.nome AS nome_marca FROM l_pecas".$extensao." AS pecas LEFT JOIN l_marcas".$extensao." AS marcas ON marcas.id = pecas.marca WHERE pecas.visivel = 1 AND pecas.id = :id";
	$rsProduto = DB::getInstance()->prepare($query_rsProduto);
	$rsProduto->bindParam(':id', $produto, PDO::PARAM_INT, 5); 
	$rsProduto->execute();
	$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsProduto = $rsProduto->rowCount();   

	$query_rsImgs = "SELECT * FROM l_pecas_imagens WHERE id_peca = :id ORDER BY ordem ASC";
	$rsImgs = DB::getInstance()->prepare($query_rsImgs);
	$rsImgs->bindParam(':id', $produto, PDO::PARAM_INT, 5); 
	$rsImgs->execute();
	$row_rsImgs = $rsImgs->fetchAll();
	$totalRows_rsImgs = $rsImgs->rowCount();

	//Array com os detalhes da promocao do produto (se tiver)
	//Pos. 0: Datas
	//Pos. 1: T�tulo
	//Pos. 2: Texto
	//Pos. 3: P�gina
	$array_promocao = $class_produtos->promocaoProduto($produto); 

	DB::close();
	
	?>
	<div class="div_100" style="height:100%">
		<div class="div_table_cell">
			<div class="modal">
				<div class="scroller">
					<div class="div_100">
						<button class="close-button small" aria-label="Close reveal" type="button">
							<span aria-hidden="true">&times;</span>
						</button>
						<div class="row collapse text-left">
							<div class="column small-12 xxsmall-5 medium-4">
								<picture class="img modal_img has_bg lazy" data-src="<?php echo $class_produtos->imgProduto($produto, 3); ?>">
        					<?php echo getFill('produtos', 2); ?>
        				</picture>
							</div>
							<div class="column small-12 xxsmall-6 medium-7 xxsmall-offset-1">
								<?php include_once(ROOTPATH.'includes/produtos-info.php'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php 
}

/* Adicionar ao carrinho */
if($_POST['op'] == 'adicionaCarrinho') {
	$tipo_cliente = $class_user->clienteData('tipo');
	$id_cliente = $class_user->clienteData('id');
	
	$prod = $_POST['produto'];
	$price = $_POST['preco'];
	$qtd = $_POST['qtd']; 
	$message = $_POST['message'];

	if (!is_int($qtd) && $qtd == 0) {
		return '';
	}

	if(!$qtd > 0) {
		$qtd = 1;
	}
	$tam1 = $_POST['tam1'];
	$tam2 = $_POST['tam2'];
	$tam3 = $_POST['tam3'];
	$tam4 = $_POST['tam4'];
	$tam5 = $_POST['tam5'];
		
	$alt = 0;


	if($price > 0) {
		$descricao = "";
		
		if(CARRINHO_TAMANHOS == 1 && tableExists(DB::getInstance(), "l_caract_opcoes_en")) {	
			if($tam1 != '0') {
				$query_rsC = "SELECT l_caract_opcoes_en.nome AS nome_opcao, l_caract_categorias_en.nome AS nome_caract FROM l_caract_opcoes_en, l_caract_categorias_en WHERE  l_caract_opcoes_en.id='$tam1' AND  l_caract_opcoes_en.categoria=l_caract_categorias_en.id";
				$rsC = DB::getInstance()->prepare($query_rsC);
				$rsC->execute();
				$row_rsC = $rsC->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsC = $rsC->rowCount();
				
				if($totalRows_rsC > 0) {
					$descricao .= $row_rsC['nome_caract'].": ".$row_rsC['nome_opcao'].";";
				}
			
				if($tam2 != '0') {
					$query_rsC = "SELECT l_caract_opcoes_en.nome AS nome_opcao, l_caract_categorias_en.nome AS nome_caract FROM l_caract_opcoes_en, l_caract_categorias_en WHERE  l_caract_opcoes_en.id='$tam2' AND  l_caract_opcoes_en.categoria=l_caract_categorias_en.id";
					$rsC = DB::getInstance()->prepare($query_rsC);
					$rsC->execute();
					$row_rsC = $rsC->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsC = $rsC->rowCount();

					if($totalRows_rsC > 0) {
						$descricao .= "<br>".$row_rsC['nome_caract'].": ".$row_rsC['nome_opcao'].";";
					}
			
					if($tam3 != '0') {				
						$query_rsC = "SELECT l_caract_opcoes_en.nome AS nome_opcao, l_caract_categorias_en.nome AS nome_caract FROM l_caract_opcoes_en, l_caract_categorias_en WHERE  l_caract_opcoes_en.id='$tam3' AND  l_caract_opcoes_en.categoria=l_caract_categorias_en.id";
						$rsC = DB::getInstance()->prepare($query_rsC);
						$rsC->execute();
						$row_rsC = $rsC->fetch(PDO::FETCH_ASSOC);
						$totalRows_rsC = $rsC->rowCount();
						
						if($totalRows_rsC > 0) {
							$descricao .= "<br>".$row_rsC['nome_caract'].": ".$row_rsC['nome_opcao'].";";
						}
			
						if($tam4 != '0') {				
							$query_rsC = "SELECT l_caract_opcoes_en.nome AS nome_opcao, l_caract_categorias_en.nome AS nome_caract FROM l_caract_opcoes_en, l_caract_categorias_en WHERE  l_caract_opcoes_en.id='$tam4' AND  l_caract_opcoes_en.categoria=l_caract_categorias_en.id";
							$rsC = DB::getInstance()->prepare($query_rsC);
							$rsC->execute();
							$row_rsC = $rsC->fetch(PDO::FETCH_ASSOC);
							$totalRows_rsC = $rsC->rowCount();
							
							if($totalRows_rsC > 0) {
								$descricao .= "<br>".$row_rsC['nome_caract'].": ".$row_rsC['nome_opcao'].";";
							}
			
							if($tam5 != '0') {				
								$query_rsC = "SELECT l_caract_opcoes_en.nome AS nome_opcao, l_caract_categorias_en.nome AS nome_caract FROM l_caract_opcoes_en, l_caract_categorias_en WHERE  l_caract_opcoes_en.id='$tam5' AND  l_caract_opcoes_en.categoria=l_caract_categorias".$extensao.".id";
								$rsC = DB::getInstance()->prepare($query_rsC);
								$rsC->execute();
								$row_rsC = $rsC->fetch(PDO::FETCH_ASSOC);
								$totalRows_rsC = $rsC->rowCount();
								
								if($totalRows_rsC > 0) {
									$descricao .= "<br>".$row_rsC['nome_caract'].": ".$row_rsC['nome_opcao'].";";
								}
							}
						}
					}
				}
			}
		}
		
		$query_rsP = "SELECT nao_limitar_stock, tem_conjunto FROM l_pecas".$extensao." WHERE visivel='1' AND id='$prod'";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		
		$erro = 0;
		
		$carrinho_session = $_COOKIE[CARRINHO_SESSION];
		$ses_id_old = strtotime(date("YmdHis", strtotime("-5 days"))); //5 dias atr�s
		
		if($carrinho_session == "" || $carrinho_session <= $ses_id_old) {
			$ses_id = strtotime(date("YmdHis",time()));
			
			$insertSQL = "DELETE FROM carrinho WHERE session < '$ses_id_old'";
			$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
			$rsInsertSQL->execute();
			
			$insertSQL = "DELETE FROM carrinho_comprar WHERE session < '$ses_id_old'";
			$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
			$rsInsertSQL->execute();	
			
			$timeout = 3600*24*5; //5 dias
			setcookie(CARRINHO_SESSION, $ses_id, time()+$timeout, "/", "", $cookie_secure, true);
			$carrinho_session = $ses_id;
		}
		
		$query_rsProc = "SELECT id, quantidade FROM carrinho WHERE session = '$carrinho_session' AND produto = '$prod' AND opcoes = '$descricao'";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->execute();
		$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsProc = $rsProc->rowCount();
		
		$id_linha = 0;
		if($qtd > 0) {
			if(CARRINHO_TAMANHOS == 1 && tableExists(DB::getInstance(), 'l_pecas_tamanhos')) {					
				// if($totalRows_rsProc > 0) {	nao sabia o que fazia a variavel tot_carrinho, comentei -> tiago
				// 	$tot_carrinho = $row_rsProc['quantidade'] + $qtd;	
				// }
				// else {		
				// 	$tot_carrinho = $qtd;		
				// }
				$stock_disp = $class_produtos->stockProduto($prod, $tam1, $tam2, $tam3, $tam4, $tam5, 2);
			}
		}
		//Verificar se produto tem algum de oferta
		if($stock_disp > 0 && $stock_disp >= $qtd || $row_rsP['nao_limitar_stock'] == 1) {
			if($totalRows_rsProc > 0 && $row_rsP["tem_conjunto"] != 1) {
				if($row_rsP['nao_limitar_stock'] == 1) {
					$quantidade = $row_rsProc['quantidade'] + $qtd;
				}
				else if(($row_rsProc['quantidade'] + $qtd) > $stock_disp) {  //troquei aqui lifenatura tem o original +/-
					$quantidade = $stock_disp;
				} 
				else {
					$quantidade = $row_rsProc['quantidade'] + $qtd;	 //troquei aqui lifenatura tem o original +/-
				}
				$insertSQL = "UPDATE carrinho SET quantidade = '$quantidade' WHERE session = '$carrinho_session' AND produto = '$prod' AND opcoes = '$descricao'";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->execute();

				$id_linha = $row_rsProc['id'];
				
				if($totalRows_rsOferta > 0) {				
					$insertSQL = "UPDATE carrinho SET quantidade = '$quantidade' WHERE session = '$carrinho_session' AND id_oferta = '$id_linha'";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
				}
				else {		
					$insertSQL = "DELETE FROM carrinho WHERE id_oferta = '$id_linha'";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
				}
			}
			else {
				$query_rsMaxID = "SELECT MAX(id) FROM carrinho";
				$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);
				$rsMaxID->execute();
				$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsMaxID = $rsMaxID->rowCount();
				DB::close();
				
				$id = $row_rsMaxID['MAX(id)'] + 1;
			
				if($row_rsP['nao_limitar_stock'] == 1) {
					$quantidade = $qtd;
				}
				else if($qtd > $stock_disp) {
					$quantidade = $stock_disp;
				} 
				else {
					$quantidade = $qtd;
				}
				
				$insertSQL = "INSERT INTO carrinho (id, session, produto, opcoes, quantidade, preco, message, op1, op2, op3, op4, op5) VALUES ('$id', '$carrinho_session', '$prod', '$descricao', '$quantidade', '$price', '$message', '$tam1', '$tam2', '$tam3', '$tam4', '$tam5')";
				$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
				$rsInsertSQL->execute();
				
				$id_linha = $id;
				
				if($totalRows_rsOferta > 0) {	
					$query_rsMaxID2 = "SELECT MAX(id) FROM carrinho";
					$rsMaxID2 = DB::getInstance()->prepare($query_rsMaxID2);
					$rsMaxID2->execute();
					$row_rsMaxID2 = $rsMaxID2->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsMaxID2 = $rsMaxID2->rowCount();
					
					$id2 = $row_rsMaxID2['MAX(id)'] + 1;				
					
					$insertSQL = "INSERT INTO carrinho (id, id_oferta, session, produto, opcoes, quantidade, preco, op1, op2, op3, op4, op5) VALUES ('$id2', '$id', '$carrinho_session', '".$row_rsOferta["id_oferta"]."', '', '$quantidade', '0', '0', '0', '0', '0', '0')";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->execute();
				}
			}	

			//Se existir login, temos de apagar tudo o que seja do cliente mas de uma session antiga e atualizar o ID Cliente da nova session
			if($id_cliente > 0) {
				$class_carrinho->atualizaCarrinho($id_cliente);
			}
			
			echo $erro."-1"."-".$id_linha;
		}
		else {
			echo "0-0"."-".$id_linha;
		}
	}
	else {
		echo $alt."-0"; // N�o tem preco
	}

	DB::close();
}

/* Alterar quantidade no carrinho */
if($_POST['op'] == "altera_carrinho") {		
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	$id = $_POST['id'];
	$qtd = intval($_POST['qtd']);
	
	if($qtd <= 0) {
		$qtd = 1;	
	}
	
	$insertSQL = "UPDATE carrinho SET quantidade='$qtd' WHERE id='$id' AND session='$carrinho_session'";
	$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
	$rsInsertSQL->execute();	

	//Verificar se o c�digo promocional ainda se aplica. Se n�o se aplicar, apagamos o mesmo
	if(CARRINHO_CODIGOS == 1) {
		$query_rsCarCodProm = "SELECT codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";
		$rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);
		$rsCarCodProm->execute();
		$row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsCarCodProm = $rsCarCodProm->rowCount();

		if($totalRows_rsCarCodProm > 0) {
			$cod = $row_rsCarCodProm['codigo'];
			$total = $class_carrinho->precoTotal();

			$res = $class_carrinho->verifica_cod_promo($cod, $total);

			if($res != 3) {
				$deleteSQL  = "DELETE FROM carrinho_cod_prom where session = '$carrinho_session'";		
				$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);
				$rsDeleteSQL->execute(); 
			}
		}
	}

	DB::close();
}

if($_POST['op'] == "altera_carrinho") {		
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	$id = $_POST['id'];
	$qtd = intval($_POST['qtd']);
	
	if($qtd <= 0) {
		$qtd = 1;	
	}
	
	$insertSQL = "UPDATE carrinho SET quantidade='$qtd' WHERE id='$id' AND session='$carrinho_session'";
	$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
	$rsInsertSQL->execute();	

	//Verificar se o c�digo promocional ainda se aplica. Se n�o se aplicar, apagamos o mesmo
	if(CARRINHO_CODIGOS == 1) {
		$query_rsCarCodProm = "SELECT codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";
		$rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);
		$rsCarCodProm->execute();
		$row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsCarCodProm = $rsCarCodProm->rowCount();

		if($totalRows_rsCarCodProm > 0) {
			$cod = $row_rsCarCodProm['codigo'];
			$total = $class_carrinho->precoTotal();

			$res = $class_carrinho->verifica_cod_promo($cod, $total);

			if($res != 3) {
				$deleteSQL  = "DELETE FROM carrinho_cod_prom where session = '$carrinho_session'";		
				$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);
				$rsDeleteSQL->execute(); 
			}
		}
	}

	DB::close();
}

/* Remover produto do carrinho */
if($_POST['op'] == "remove_carrinho") {		
	$id = $_POST['id'];	
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	
	$insertSQL = "DELETE FROM carrinho WHERE id=:id AND session = '$carrinho_session'";
	$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
	$rsInsertSQL->bindParam(':id', $id, PDO::PARAM_INT);
	$rsInsertSQL->execute();

	//Verificar se o c�digo promocional ainda se aplica. Se n�o se aplicar, apagamos o mesmo
	if(CARRINHO_CODIGOS == 1) {
		$query_rsCarCodProm = "SELECT codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";
		$rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);
		$rsCarCodProm->execute();
		$row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsCarCodProm = $rsCarCodProm->rowCount();

		if($totalRows_rsCarCodProm > 0) {
			$cod = $row_rsCarCodProm['codigo'];
			$total = $class_carrinho->precoTotal();

			$res = $class_carrinho->verifica_cod_promo($cod, $total);

			if($res != 0) {
				$deleteSQL  = "DELETE FROM carrinho_cod_prom where session = '$carrinho_session'";		
				$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);
				$rsDeleteSQL->execute(); 
			}
		}
	}
	
	if($id == "0") {
		if($row_rsCliente > 0) {			
			$query_insertSQL = "DELETE FROM carrinho_cliente WHERE id_cliente=:user";
			$insertSQL = DB::getInstance()->prepare($query_insertSQL);
			$insertSQL->bindParam(':user', $row_rsCliente["id"], PDO::PARAM_INT);
			$insertSQL->execute();	
		} 
		
		$insertSQL = "DELETE FROM carrinho where session = '$carrinho_session'";
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->execute();
		
		$deleteSQL  = "DELETE FROM carrinho_comprar where session = '$carrinho_session'";
		$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);
		$rsDeleteSQL->execute();
		
		if(CARRINHO_CODIGOS == 1) {
			$deleteSQL  = "DELETE FROM carrinho_cod_prom where session = '$carrinho_session'";		
			$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);
			$rsDeleteSQL->execute(); 
		}
	}

	DB::close();
}

/* Alterar tamanho de um produto */
if($_POST['op'] == 'alteraTamanho') {
	$prod = $_POST['produto'];
	$tam1 = $_POST['tamanho_1'];
	$tam2 = $_POST['tamanho_2'];
	$tam3 = $_POST['tamanho_3'];
	$tam4 = $_POST['tamanho_4'];
	$tam5 = $_POST['tamanho_5'];

	echo $class_produtos->tamanhosProduto($prod, $tam1, $tam2, $tam3, $tam4, $tam5);
}

/* Verificar mensagem do stock */
if($_POST['op'] == 'alteraMsgStock') { 
	$prod = $_POST['produto'];
	$tam1 = $_POST['op1'];
	$tam2 = $_POST['op2'];
	$tam3 = $_POST['op3'];
	$tam4 = $_POST['op4'];
	$tam5 = $_POST['op5'];
	
	$msg_stock = $class_produtos->stockProduto($prod, $tam1, $tam2, $tam3, $tam4, $tam5, 3);
		
	echo $msg_stock;
}

/* Alterar preco do produto */
if($_POST['op'] == 'alteraPreco') { 
	$prod = $_POST['produto'];
	$quantidade = $_POST['qtd'];
	$tam1 = $_POST['op1'];
	$tam2 = $_POST['op2'];
	$tam3 = $_POST['op3'];
	$tam4 = $_POST['op4'];
	$tam5 = $_POST['op5'];
	
	$query_rsTamanho = "SELECT id FROM l_pecas_tamanhos WHERE op1=:tam1 AND op2=:tam2 AND op3=:tam3 AND op4=:tam4 AND op5=:tam5 AND peca=:id";
	$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
	$rsTamanho->bindParam(':id', $prod, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam1', $tam1, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam2', $tam2, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam3', $tam3, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam4', $tam4, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam5', $tam5, PDO::PARAM_INT, 5); 
	$rsTamanho->execute();
	$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsTamanho = $rsTamanho->rowCount();
	
	$preco = 0;
	$preco_string = "";
	
	if($totalRows_rsTamanho > 0) {
		$preco = $class_produtos->precoProduto($prod,2, $quantidade, $row_rsTamanho['id']);
		$preco_string = $class_produtos->precoProduto($prod, 0, $quantidade, $row_rsTamanho['id']);
	}
	else {
		$preco = $class_produtos->precoProduto($prod, 2, $quantidade);
		$preco_string = $class_produtos->precoProduto($prod, 0, $quantidade);
	}

	DB::close();
	?>
	<?php echo $preco; ?><!-- 
	--><?php echo $class_produtos->labelsProduto($prod, 2, 'detalhe'); ?>
	<input name="preco_final" id="preco_final_<?php echo $prod; ?>" type="hidden" value="<?php echo $preco_string; ?>" />
<?php }

/* Ativar/Desativar compra com Saldo no carrinho */
if($_POST['op'] == 'comprar_com_saldo') {
	$tipo = $_POST['tipo'];	
	$valor = $_POST['valor'];	
		
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	$ses_id_old = strtotime(date("YmdHis", strtotime("-5 days"))); //5 dias atr�s
	
	if($carrinho_session == "" || $carrinho_session <= $ses_id_old) {
		$ses_id = strtotime(date("YmdHis",time()));
		
		$insertSQL = "DELETE FROM carrinho_comprar WHERE session < '$ses_id_old'";
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->execute();	
		
		$timeout = 3600*24*5; //5 dias
		setcookie(CARRINHO_SESSION, $ses_id, time()+$timeout, "/", "", $cookie_secure, true);
		$carrinho_session = $ses_id;
	}

	if($tipo == 1) {
		$insertSQL = "INSERT INTO carrinho_comprar (session, valor) VALUES ('$carrinho_session', '$valor')";
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->execute();
	}
	else {
		$insertSQL = "DELETE FROM carrinho_comprar WHERE session='$carrinho_session'";
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->execute();
	}

	DB::close();
}

/* Verificar codigo promocional no carrinho */
if($_POST['op'] == "carregaCodigoPromo") {
	$total = $_POST['total'];
	$cod = $_POST['cod'];

	$res = $class_carrinho->verifica_cod_promo($cod, $total);

	echo $res;
}

if($_POST['op'] == "carregaCodigoPromoTotal") {
	$total_euro = $_POST['total'];
	$total_sem_promo = $_POST['total_sem_promo'];
	$cod = $_POST['cod'];
	$elim = $_POST['elim'];
	if(!isset($_POST['elim'])) {
		$elim = 0;
	}
	
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	
	if($total_euro && $cod && $elim != 1) {
		$data = date('Y-m-d H:i:s');
		
		$query_rsCod = "SELECT id, desconto FROM codigos_promocionais WHERE visivel='1' AND ((datai<='$data' OR datai IS NULL OR datai='') AND (dataf>='$data' OR dataf IS NULL OR dataf='')) AND codigo='$cod' AND desconto>=0 AND (valor_minimo<='$total_euro' OR valor_minimo is NULL)";
		$rsCod = DB::getInstance()->prepare($query_rsCod);
		$rsCod->execute();
		$row_rsCod = $rsCod->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsCod = $rsCod->rowCount();
		
		if($totalRows_rsCod > 0) {
			$query_rsExiste = "SELECT id_codigo FROM carrinho_cod_prom WHERE session = '$carrinho_session'";
			$rsExiste = DB::getInstance()->prepare($query_rsExiste);
			$rsExiste->execute();
			$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsExiste = $rsExiste->rowCount();
			
			if($totalRows_rsExiste == 0) {
				$query_inserir = "INSERT INTO carrinho_cod_prom (session, id_codigo, codigo, desconto) VALUES ('$carrinho_session', '".$row_rsCod["id"]."', '$cod', '".$row_rsCod['desconto']."')";
				$rs_inserir = DB::getInstance()->prepare($query_inserir);
				$rs_inserir->execute();
			}
		}
	}
	else if($elim == 1) {
		$query_apagar = "DELETE FROM carrinho_cod_prom WHERE session='$carrinho_session'";
		$rs_apagar = DB::getInstance()->prepare($query_apagar);
		$rs_apagar->execute();
	}

	DB::close();
}

/* Carregar Metodos de Pagamento */
if($_POST['op'] == "carregaPagamentos") {		
	$pais = $_POST['pais'];
	$portes = $_POST['portes'];
	$met_pagamento = $_POST['met_pagamento'];
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
		
	$query_rsQtds = "SELECT zonas_met_pagamento.portes, zonas_met_pagamento.tipo, met_pagamento".$extensao.".* FROM zonas_met_pagamento, met_pagamento".$extensao.", zonas, paises WHERE zonas_met_pagamento.id_zona=zonas.id AND zonas_met_pagamento.id_metodo=met_pagamento".$extensao.".id AND paises.zona=zonas.id AND paises.id='$pais' ORDER BY met_pagamento".$extensao.".ordem ASC, met_pagamento".$extensao.".nome ASC";
	
	$rsQtds = DB::getInstance()->prepare($query_rsQtds);
	$rsQtds->execute();
	$totalRows_rsQtds = $rsQtds->rowCount();
	
	$valor_pagamt = $class_carrinho->precoTotal();
	
	if($portes > 0) {
		$valor_pagamt += $portes;
	}
	
	$query_rsProcS = "SELECT valor FROM carrinho_comprar WHERE session='$carrinho_session'";
	$rsProcS = DB::getInstance()->prepare($query_rsProcS);
	$rsProcS->execute();
	$row_rsProcS = $rsProcS->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsProcS = $rsProcS->rowCount();
	
	if($totalRows_rsProcS > 0) {
		$valor_car = $row_rsProcS['valor'];
		
		if($valor_car < $valor_pagamt) {
			$valor_pagamt = $valor_pagamt - $valor_car;	
		}
		else {			
			$valor_pagamt = 0;
		}
	}
	
	if($valor_pagamt > 0) { 
  		while($row_rsQtds = $rsQtds->fetch()) {
			$portes_pag = $preco_pag = 0;		
					
			if($row_rsQtds['tipo'] == 1) {
				$preco_pag = $row_rsQtds['portes'];
			}
			else {
				if($row_rsQtds['portes'] > 0) {
					$preco_pag = $valor_pagamt - ($valor_pagamt - ($valor_pagamt * ($row_rsQtds['portes'] / 100)));
				}
			}

			$preco_pag = $class_carrinho->mostraPreco($preco_pag, 2);
			
			$nome = $row_rsQtds['nome'];
			if($preco_pag > 0) {
				$nome = $nome." (".$Recursos->Resources["comprar_acresce"]." ".$class_carrinho->mostraPreco($preco_pag, 0, 0).")";
			}

			$descricao = "";
			if($row_rsQtds['descricao2']) {
				$descricao = $row_rsQtds['descricao2'];
			}
		
			if($row_rsQtds['valor_encomenda2'] > 0) {
				$verifica = ($valor_pagamt >= $row_rsQtds['valor_encomenda'] && $valor_pagamt <= $row_rsQtds['valor_encomenda2']);
				
			} 
			else {
				$verifica = ($valor_pagamt >= $row_rsQtds['valor_encomenda']);

			}
		
			$img = ROOTPATH_HTTP."imgs/carrinho/geral.png";
			if($row_rsQtds['imagem']!='' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsQtds['imagem'])) { 
				$img = ROOTPATH_HTTP."imgs/carrinho/".$row_rsQtds['imagem'];
			}

       		// Check Condition Customer payment method
         ?> 		   	
		   	
		   	<?php if($totalRows_rsRole["roll_name"] == "customer" && $verifica && $row_rsQtds['id'] == 1 && $valor_pagamt > $totalRows_rsRole["cod_price"]) { ?>

		   		<?php echo  $totalRows_rsRole["roll_name"]; ?>
			    <div class="div_100 pagamentos_divs">
			    	<input required name="pagamento" type="radio" value="<?php echo $row_rsQtds['id']; ?>" id="pagamento_<?php echo $row_rsQtds['id']; ?>" onclick="verifica_pag(this.value)" <?php if($met_pagamento == $row_rsQtds['id'] || $totalRows_rsQtds == 1) echo "checked=\"checked\""; ?> data-preco="<?php echo $preco_pag; ?>" />
		        <label for="pagamento_<?php echo $row_rsQtds['id'];?>">
	            <div class="img"><img src="<?php echo $img; ?>" width="100%" /></div><!--
	            --><span><?php echo $nome; ?></span>
	            <?php if($descricao) { ?>
	            	<div class="desc"><?php echo $descricao; ?></div>
	            <?php } ?>
		        </label>
			    </div>
    		<?php }

			if($totalRows_rsRole["roll_name"] == "customer" && $verifica && $valor_pagamt <= $totalRows_rsRole["cod_price"]) { ?>
			<?php echo  $totalRows_rsRole["roll_name"]; ?>
		    <div class="div_100 pagamentos_divs">
		    	<input required name="pagamento" type="radio" value="<?php echo $row_rsQtds['id']; ?>" id="pagamento_<?php echo $row_rsQtds['id']; ?>" onclick="verifica_pag(this.value)" <?php if($met_pagamento == $row_rsQtds['id'] || $totalRows_rsQtds == 1) echo "checked=\"checked\""; ?> data-preco="<?php echo $preco_pag; ?>" />
	        <label for="pagamento_<?php echo $row_rsQtds['id'];?>">
            <div class="img"><img src="<?php echo $img; ?>" width="100%" /></div><!--
            --><span><?php echo $nome; ?></span>
            <?php if($descricao) { ?>
            	<div class="desc"><?php echo $descricao; ?></div>
            <?php } ?>
	        </label>
		    </div>
    	<?php } 

     ?>

    	<?php // Check Condition Franchaise Paymet method
    	if($totalRows_rsRole["roll_name"] == "franchise" && $verifica && $row_rsQtds['id'] == 1){ echo  $totalRows_rsRole["roll_name"];?>
    		<div class="div_100 pagamentos_divs">
		    	<input required name="pagamento" type="radio" value="<?php echo $row_rsQtds['id']; ?>" id="pagamento_<?php echo $row_rsQtds['id']; ?>" onclick="verifica_pag(this.value)" <?php if($met_pagamento == $row_rsQtds['id'] || $totalRows_rsQtds == 1) echo "checked=\"checked\""; ?> data-preco="<?php echo $preco_pag; ?>" />
	        <label for="pagamento_<?php echo $row_rsQtds['id'];?>">
            <div class="img"><img src="<?php echo $img; ?>" width="100%" /></div><!--
            --><span><?php echo $nome; ?></span>
            <?php if($descricao) { ?>
            	<div class="desc"><?php echo $descricao; ?></div>
            <?php } ?>
	        </label>
		    </div>
    	<?php } ?>
    <?php } ?> 
  <?php } else { ?>
  	<div class="div_100 pagamentos_divs">
      <input required name="pagamento" type="radio" value="<?php echo $row_rsQtds['id']; ?>" id="pagamento_<?php echo $row_rsQtds['id']; ?>" onclick="verifica_pag(this.value)" <?php if($met_pagamento == $row_rsQtds['id'] || $totalRows_rsQtds == 1) echo "checked=\"checked\""; ?> data-preco="<?php echo $preco_pag; ?>" />
      <label for="pagamento_<?php echo $row_rsQtds['id']; ?>">
        <div class="img">
        <?php if($row_rsQtds['imagem']!='' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsQtds['imagem'])) { ?>	
          <img src="<?php echo ROOTPATH_HTTP."imgs/carrinho/".$row_rsQtds['imagem']; ?>" width="100%" />
        <?php } ?>
        </div><!--
        --><span><?php echo $nome; ?></span>
        <?php if($descricao) { ?>
        	<div class="desc"><?php echo $descricao; ?></div>
        <?php } ?>
      </label>
    </div>
  <?php }

  DB::close();
} 

/* Carrega Metodos de Envio */
if($_POST['op'] == "carregaEntregas") {


	$carrinho_session = $_COOKIE[CARRINHO_SESSION];

	$tipo_cliente = $class_user->clienteData('tipo');
	$pvp = $class_user->clienteData('pvp');
		
	$preco = $_POST['preco'];
	$pais = $_POST['pais'];
	$met_pag = $_POST['met_pag'];
	$met_envio = $_POST['met_envio'];
	$localidade_pm = $_POST['localidade_pm'];
	$ponto_pm = $_POST['ponto_pm'];

	$extensao = "_pt";
	$from = "";
	$where = "";
	if($met_pag) {
		$from = " LEFT JOIN met_pag_envio ON met_pag_envio.met_envio=met_envio".$extensao.".id";
		$where = " AND met_pag_envio.met_pagamento='$met_pag'";	
	}
	
	 $query_rsQtds = "SELECT zonas_met_envio.portes, zonas_met_envio.tipo, zonas_met_envio.tabela, zonas_met_envio.custo, met_envio".$extensao.".*, zonas.portes_gratis".$pvp.", zonas.peso_max FROM zonas_met_envio, met_envio".$extensao."".$from.", zonas, paises WHERE zonas_met_envio.id_zona=zonas.id AND zonas_met_envio.id_metodo=met_envio".$extensao.".id AND paises.zona=zonas.id AND paises.id='$pais' ".$where." GROUP BY met_envio".$extensao.".id ORDER BY met_envio".$extensao.".ordem ASC, met_envio".$extensao.".nome ASC";

	

	$rsQtds = DB::getInstance()->prepare($query_rsQtds);
	$rsQtds->execute();
	$row_rsQTDTemp = $rsQtds->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsQtds = $rsQtds->rowCount();
	
	//Calcula o peso do carrinho
	$total_peso = $class_carrinho->totalPeso();
	
	//Soma as unidades das pecas
	$query_rsQuant = "SELECT SUM(quantidade) AS total_qtd FROM carrinho WHERE session='$carrinho_session'";
	$rsQuant = DB::getInstance()->prepare($query_rsQuant);
	$rsQuant->execute();
	$row_rsQuant = $rsQuant->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsQuant = $rsQuant->rowCount();
	
	//Verificar se existem campanhas de portes gratis para este carrinho
	$query_rsPGratis = "SELECT zonas.id, paises.nome AS pais_nome FROM zonas, paises WHERE paises.id='$pais' AND paises.zona=zonas.id";
	$rsPGratis = DB::getInstance()->prepare($query_rsPGratis);
	$rsPGratis->execute();
	$row_rsPGratis = $rsPGratis->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsPGratis = $rsPGratis->rowCount();
	
	$zona_cliente = $row_rsPGratis['id'];		
	$data = date('Y-m-d Y-m-d H:i:s');
	$produto_com_portes_gratis = 0;
	
	$query_rsCarList = "SELECT produto FROM carrinho WHERE session = '$carrinho_session' ORDER BY id ASC";
	$rsCarList = DB::getInstance()->prepare($query_rsCarList);
	$rsCarList->execute();
	$totalRows_rsCarList = $rsCarList->rowCount();


	if($totalRows_rsCarList > 0) {
		while($row_rsCarList = $rsCarList->fetch()) {
			if($produto_com_portes_gratis == 0) {
				$produto = $row_rsCarList['produto'];
	
				$query_rsProdutoList = "SELECT categoria, marca FROM l_pecas".$extensao." WHERE id = '$produto'";
				$rsProdutoList = DB::getInstance()->prepare($query_rsProdutoList);
				$rsProdutoList->execute();
				$row_rsProdutoList = $rsProdutoList->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsProdutoList = $rsProdutoList->rowCount();
												
				$categoria_produto = $row_rsProdutoList['categoria'];
				$marca_produto = $row_rsProdutoList['marca'];
	
				$where = $left_join = "";
						
				if(ECC_MARCAS == 1) {
					$where =" OR (portes_gratis_marcas.marca='$marca_produto')";
					$left_join = " LEFT JOIN portes_gratis_marcas ON portes_gratis.id=portes_gratis_marcas.portes_gratis";
				}

				if(tableExists(DB::getInstance(), 'l_pecas_categorias')) {
					$query_rsCampGratis = "SELECT portes_gratis.* FROM portes_gratis LEFT JOIN portes_gratis_categorias ON portes_gratis.id=portes_gratis_categorias.portes_gratis".$left_join." LEFT JOIN portes_gratis_zonas ON portes_gratis.id=portes_gratis_zonas.portes_gratis LEFT JOIN l_categorias_en ON l_categorias_en.id = '$categoria_produto' WHERE portes_gratis.visivel='1' AND portes_gratis.datai<='$data' AND portes_gratis.dataf>='$data' AND ((portes_gratis.id=portes_gratis_zonas.portes_gratis AND portes_gratis_zonas.zona='$zona_cliente') OR (l_categorias_en.cat_mae=portes_gratis_categorias.categoria) OR (portes_gratis_categorias.categoria='$categoria_produto' OR portes_gratis_categorias.categoria IN (SELECT categoria FROM l_pecas_categorias WHERE id_peca='$produto'))".$where.") GROUP BY portes_gratis.id";
					$rsCampGratis = DB::getInstance()->prepare($query_rsCampGratis);
					$rsCampGratis->execute();
					$row_rsCampGratis = $rsCampGratis->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsCampGratis = $rsCampGratis->rowCount();
				}
				else {
					$query_rsCampGratis = "SELECT portes_gratis.* FROM portes_gratis LEFT JOIN portes_gratis_categorias ON portes_gratis.id=portes_gratis_categorias.portes_gratis".$left_join." LEFT JOIN portes_gratis_zonas ON portes_gratis.id=portes_gratis_zonas.portes_gratis LEFT JOIN l_categorias_en ON l_categorias_en.id = '$categoria_produto' WHERE portes_gratis.visivel='1' AND portes_gratis.datai<='$data' AND portes_gratis.dataf>='$data' AND ((portes_gratis.id=portes_gratis_zonas.portes_gratis AND portes_gratis_zonas.zona='$zona_cliente') OR (l_categorias_en.cat_mae=portes_gratis_categorias.categoria) OR (portes_gratis_categorias.categoria='$categoria_produto')".$where.") GROUP BY portes_gratis.id";
					$rsCampGratis = DB::getInstance()->prepare($query_rsCampGratis);
					$rsCampGratis->execute();
					$row_rsCampGratis = $rsCampGratis->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsCampGratis = $rsCampGratis->rowCount();	
				}
				
				if($totalRows_rsCampGratis > 0) {
					//verifica se os portes gratis se aplicam com base no preco m�nimo e peso m�ximo
					$aplica_p_gratis = 1;

					if($row_rsCampGratis['min_encomenda'] > 0 && $row_rsCampGratis['min_encomenda'] > $preco) {
						$aplica_p_gratis = 0;
					}

					if($aplica_p_gratis == 1 && $row_rsCampGratis['peso_max'] > 0 && $total_peso > $row_rsCampGratis['peso_max']) {
						$aplica_p_gratis = 0;
					}

					if($aplica_p_gratis == 1) {
						$produto_com_portes_gratis = 1;
					}
				}
			}
		}
	}
	
	$rsQtdsNew = DB::getInstance()->prepare($query_rsQtds);
	$rsQtdsNew->execute();
	$row_rsQTDTemprsQtdsNew = $rsQtdsNew->fetchAll(PDO::FETCH_ASSOC);
	//Se tiver portes gratis, mostra apenas "Portes gratis" no metodo de envio
	
	foreach ($row_rsQTDTemprsQtdsNew as $row_rsQTDTemp) {
		$met_portes_gratis = 0; 
		if((($class_carrinho->mostraPreco($row_rsQTDTemp['portes_gratis'.$pvp], 2) > $preco || !$row_rsQTDTemp['portes_gratis'.$pvp]) && $produto_com_portes_gratis == 0) || $met_portes_gratis == 0) {

		$row_rsQtds = $row_rsQTDTemp;
    	//while($row_rsQtds = $rsQtdsNew->fetch()) {
			$mostra_envio = 1;
			$portes_pag = $portes_env = 0;
			
      		if($row_rsQtds['tabela'] != 0) { 
        		$id_tabela = $row_rsQtds['tabela'];
        		$peso = $total_peso;
				$query_rsTabTransp = "SELECT preco FROM transp_valores WHERE id_transp='$id_tabela' AND min<='$peso' AND (max>='$peso' OR max IS NULL OR max='' OR max='0') ORDER BY min ASC LIMIT 1";
				$rsTabTransp = DB::getInstance()->prepare($query_rsTabTransp);
				$rsTabTransp->execute();
				$row_rsTabTransp = $rsTabTransp->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsTabTransp = $rsTabTransp->rowCount();

				$preco_transp = 0;
        
				if($totalRows_rsTabTransp > 0) {
					$preco_transp = $row_rsTabTransp['preco'];
				}
				//Se n�o existir um intervalo v�lido para o peso da encomenda, verificar se existem valores para o "Por cada X Kg adicional cobra Y �"
				else {
					$query_rsTabela = "SELECT kg, preco FROM transportadoras WHERE id = '$id_tabela'";
					$rsTabela = DB::getInstance()->prepare($query_rsTabela);
					$rsTabela->execute();
					$row_rsTabela = $rsTabela->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsTabela = $rsTabela->rowCount();

					if($totalRows_rsTabela > 0 && $row_rsTabela['kg'] > 0 && $row_rsTabela['preco'] > 0) {
						//Obter o preco associado ao intervalo m�ximo da tabela
						$query_rsTabTranspMax = "SELECT preco, max FROM transp_valores WHERE id_transp='$id_tabela' ORDER BY max DESC LIMIT 1";
						$rsTabTranspMax = DB::getInstance()->prepare($query_rsTabTranspMax);
						$rsTabTranspMax->execute();
						$row_rsTabTranspMax = $rsTabTranspMax->fetch(PDO::FETCH_ASSOC);
						$totalRows_rsTabTranspMax = $rsTabTranspMax->rowCount();

						if($totalRows_rsTabTranspMax > 0) {
							$diff = $peso - $row_rsTabTranspMax['max'];
							$preco_transp = $row_rsTabTranspMax['preco'];
							
							$preco_transp += ceil($diff / $row_rsTabela['kg']) * $row_rsTabela['preco'];
						}
						else {
							$mostra_envio = 0;
						}
					}
					else {
						$mostra_envio = 0;
					}
				}
                
				$preco_pag = $preco_transp + $row_rsQtds['custo'];          
      		}
      		else {
        		$peso = $total_peso;
                
		        if($row_rsQtds['tipo'] == 1) {
		          $preco_pag = $row_rsQtds['portes'] * $row_rsQuant['total_qtd'];
		        }
		        else if($row_rsQtds['tipo'] == 2) {
					if($peso > 0) {
						$preco_pag = $row_rsQtds['portes'] * $peso;
					}
					else {
						$preco_pag = $row_rsQtds['portes'];
					}
		        }
		        else {
		          $preco_pag = 0;
		        }
                
        		$preco_pag = $preco_pag + $row_rsQtds['custo'];
        		$check_miles = $row_rsQtds['portes'];
      		}
            
	       	if($produto_com_portes_gratis == 1) {
	       		$preco_pag = 0;
	       	}
			
			$preco_pag = $class_carrinho->mostraPreco($preco_pag, 2);
			
			$nome = $row_rsQtds['nome'];
			$valor = 0;
			if($preco_pag > 0 && $row_rsQtds['portes_gratis'.$pvp] > $preco) {
				$valor = $preco_pag;
			}
			if( $row_rsQtds['id'] != 14)
			{
				$preco_env = $preco_pag;
				$reviwer_id = $row_rsCliente['roll'];
			}
			if( $row_rsQtds['id'] == 14 && $_POST['miless'] > $check_miles){

				$preco_env = ($preco_pag *  $_POST['miless']) - $check_miles;
			}
			if( $row_rsQtds['id'] == 14 && $_POST['miless'] < $check_miles){

				$preco_env = $preco_pag;
			}
				
			
			if($preco_pag > 0) {
				//Faz verifica��o pelo peso
				$portes_gratis_peso = 0;

		        if($row_rsQtds['peso_max'] > 0){
		        	$peso_max = $row_rsQtds['peso_max'];

		        	if($total_peso > $peso_max) {
		        		$portes_gratis_peso = 1;
		        	}
		        }

				if($row_rsQtds['portes_gratis'.$pvp] != NULL && $row_rsQtds['portes_gratis'.$pvp] > 0 && $class_carrinho->mostraPreco($row_rsQtds['portes_gratis'.$pvp], 2) <= $preco && $preco != 0 && $portes_gratis_peso == 0) { 
					$nome = $nome." (".$Recursos->Resources["comprar_portes_gratis"].")";
					$preco_env = 0;
				}
				else { 
					$nome = $nome." (".$Recursos->Resources["comprar_acresce"]." ".$class_carrinho->mostraPreco($preco_pag, 0, 0).")"; 
				}
			}

			$img = ROOTPATH_HTTP."imgs/carrinho/geral.png";

			if($row_rsQtds['imagem']!='' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsQtds['imagem'])) { 
				$img = ROOTPATH_HTTP."imgs/carrinho/".$row_rsQtds['imagem'];
			}
          
    		if($mostra_envio == 1 && $reviwer_id != $totalRows_rsRoll["roll_name"]) {?>
		    	<div class="div_100 pagamentos_divs">
		    		<input type="radio" required name="entrega" id="entrega_<?php echo $row_rsQtds['id']; ?>" value="<?php echo $row_rsQtds['id']; ?>" onclick="alterar_met_envio('<?php echo $row_rsQtds['id']; ?>', '<?php echo $valor; ?>')" <?php if($met_envio == $row_rsQtds['id'] || $totalRows_rsQtds == 1) echo "checked=\"checked\""; ?> data-preco="<?php echo $preco_env; ?>" />
		    		

	        		<label for="entrega_<?php echo $row_rsQtds['id']; ?>">
		            	<div class="img">
		            		<img src="<?php echo $img; ?>" width="100%" />
		            	</div>
            			<span><?php echo $nome; ?></span>
		        		<?php if($row_rsQtds['descricao']) { ?>
		        			<div class="desc"><?php echo nl2br($row_rsQtds['descricao']); ?></div>
		        		<?php } ?>
						<?php if($row_rsQtds['id'] == 5) { ?>
	            			<div id="pickme" class="div_100"
	            				<?php if($localidade_pm != '' && $localidade_pm != 0) { ?> style="display: block;" <?php } else { ?> style="display:none;" <?php } ?>>
								<?php
					                $query_rsPLoc = "SELECT location FROM chronopost_pickme GROUP BY location ORDER BY location ASC";
					                $rsPLoc = DB::getInstance()->prepare($query_rsPLoc);
					                $rsPLoc->execute();
					                $totalRows_rsPLoc = $rsPLoc->rowCount();
			                	?>
				                <div class="div_100">
				                  	<select name="localidade_pm" id="localidade_pm" onchange="carrega_pickme(0);">
					                    <option value="0"></option>
					                    <?php if($totalRows_rsPLoc > 0) { ?>
																<?php while($row_rsPLoc = $rsPLoc->fetch()) { ?>
					                      	<option value="<?php echo $row_rsPLoc['location']?>" <?php if($row_rsPLoc['location'] == $localidade_pm) echo "selected"; ?>><?php echo ucwords(strtolower($row_rsPLoc['location'])); ?></option>
					                      <?php } ?>
					                    <?php } ?>
				                  	</select>
				                </div>
	            			</div>
	    				<?php } ?>
    				</label>
		        	<?php if($row_rsQtds['id'] == 5) { ?>
			        	<div id="div_pickme_local"></div>
		        	<?php } ?>
    			</div>
    		<?php } 

    		if($mostra_envio == 1 && $reviwer_id == $totalRows_rsRoll["roll_name"] && $row_rsQtds['id'] != 14 && $row_rsQtds['id'] != 8){ ?>

    			<div class="div_100 pagamentos_divs">
		    		<input type="radio" required name="entrega" id="entrega_<?php echo $row_rsQtds['id']; ?>" value="<?php echo $row_rsQtds['id']; ?>" onclick="alterar_met_envio('<?php echo $row_rsQtds['id']; ?>', '<?php echo $valor; ?>')" <?php if($met_envio == $row_rsQtds['id'] || $totalRows_rsQtds == 1) echo "checked=\"checked\""; ?> data-preco="<?php echo $preco_env; ?>" />
		    		

	        		<label for="entrega_<?php echo $row_rsQtds['id']; ?>">
		            	<div class="img">
		            		<img src="<?php echo $img; ?>" width="100%" />
		            	</div>
            			<span><?php echo $nome; ?></span>
		        		<?php if($row_rsQtds['descricao']) { ?>
		        			<div class="desc"><?php echo nl2br($row_rsQtds['descricao']); ?></div>
		        		<?php } ?>
						<?php if($row_rsQtds['id'] == 5) { ?>
	            			<div id="pickme" class="div_100"
	            				<?php if($localidade_pm != '' && $localidade_pm != 0) { ?> style="display: block;" <?php } else { ?> style="display:none;" <?php } ?>>
								<?php
					                $query_rsPLoc = "SELECT location FROM chronopost_pickme GROUP BY location ORDER BY location ASC";
					                $rsPLoc = DB::getInstance()->prepare($query_rsPLoc);
					                $rsPLoc->execute();
					                $totalRows_rsPLoc = $rsPLoc->rowCount();
			                	?>
				                <div class="div_100">
				                  	<select name="localidade_pm" id="localidade_pm" onchange="carrega_pickme(0);">
					                    <option value="0"></option>
					                    <?php if($totalRows_rsPLoc > 0) { ?>
																<?php while($row_rsPLoc = $rsPLoc->fetch()) { ?>
					                      	<option value="<?php echo $row_rsPLoc['location']?>" <?php if($row_rsPLoc['location'] == $localidade_pm) echo "selected"; ?>><?php echo ucwords(strtolower($row_rsPLoc['location'])); ?></option>
					                      <?php } ?>
					                    <?php } ?>
				                  	</select>
				                </div>
	            			</div>
	    				<?php } ?>
    				</label>
		        	<?php if($row_rsQtds['id'] == 5) { ?>
			        	<div id="div_pickme_local"></div>
		        	<?php } ?>
    			</div>
    		
    		<?php } ?>	
  		<?php } else { ?>
		<div class="div_100 pagamentos_divs">
			<input type="radio" name="entrega" id="entrega_999" value="999" checked="checked" />
			<label for="entrega_999">
				<div class="img"><img src="<?php echo ROOTPATH_HTTP."imgs/carrinho/geral.png"; ?>" width="100%" /></div><!--
					--><span><?php echo $Recursos->Resources["comprar_portes_gratis"];?></span>
			</label>
		</div>
		<?php 
    	// while END
		} ?>
  	<?php 

  	}

  	DB::close();
} 

/* Carregar pontos "PickMe" */
if($_POST['op'] == "carregaPickMe") {
	$local = utf8_decode($_POST['localidade_pm']);
	$ponto = $_POST['ponto_pm'];
	
	$query_rsLoc = "SELECT * FROM chronopost_pickme WHERE location='$local' ORDER BY name ASC";
	$rsLoc = DB::getInstance()->prepare($query_rsLoc);
	$rsLoc->execute();
	$totalRows_rsLoc = $rsLoc->rowCount();
	DB::close();
		
	if($totalRows_rsLoc > 0) { ?>
	  <?php while($row_rsLoc = $rsLoc->fetch()) { ?>
	  	<div class="div_100 pagamentos_divs pickme">
	    	<input type="radio" required name="ponto_pickme" id="ponto_pickme_<?php echo $row_rsLoc['id_pickme_shop']; ?>" value="<?php echo $row_rsLoc['id_pickme_shop']; ?>" <?php if($ponto == $row_rsLoc['id_pickme_shop']) echo "checked"; ?> onclick="altera_ponto_pickme('<?php echo $row_rsLoc['id_pickme_shop']; ?>')" />
        <label for="ponto_pickme<?php echo $row_rsLoc['id']; ?>">
					<span><?php echo $row_rsLoc['name']; ?></span>
          <div class="desc">
          	<?php echo nl2br($row_rsLoc['address']); ?><br>
          	<?php echo $row_rsLoc['postal_code']; ?> 
          	<?php echo $row_rsLoc['location']; ?>
          </div>
        </label>
	    </div>
	  <?php } ?>
	<?php } 
}

/* Carregar morada de um ponto "PickMe" */
if($_POST['op'] == "carregaPickMeMorada") {
	$id = $_POST['ponto'];
	
	$query_rsLoc = "SELECT * FROM chronopost_pickme WHERE id_pickme_shop='$id'";
	$rsLoc = DB::getInstance()->prepare($query_rsLoc);
	$rsLoc->execute();
	$row_rsLoc = $rsLoc->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsLoc = $rsLoc->rowCount();
	DB::close();
	
	if($totalRows_rsLoc > 0) {
		echo $row_rsLoc['name']."###".nl2br($row_rsLoc['address'])."###".$row_rsLoc['postal_code']."###".$row_rsLoc['location'];
	}
}

/* Carregar a morada do cliente */
if($_POST['op'] == "carregaMoradaCliente") {
	echo $row_rsCliente['name']."###".nl2br($row_rsCliente['morada'])."###".$row_rsCliente['cod_postal']."###".$row_rsCliente['localidade'];
}

/* Carregar descontos por quantidade */
if($_POST['op'] == 'carregaQuantidades') {
	$prod = $_POST['prod'];
	$tam1 = $_POST['tamanho_1'];
	$tam2 = $_POST['tamanho_2'];
	$tam3 = $_POST['tamanho_3'];
	$tam4 = $_POST['tamanho_4'];
	$tam5 = $_POST['tamanho_5'];

	$query_rsDescontoQTD = "SELECT * FROM l_pecas_desconto WHERE id_peca = :id";
	$rsDescontoQTD = DB::getInstance()->prepare($query_rsDescontoQTD);
	$rsDescontoQTD->bindParam(':id', $prod, PDO::PARAM_INT); 
	$rsDescontoQTD->execute();
	$row_rsDescontoQTD = $rsDescontoQTD->fetchAll();
	$totalRows_rsDescontoQTD = $rsDescontoQTD->rowCount();

	$query_rsTamanho = "SELECT id FROM l_pecas_tamanhos WHERE op1=:tam1 AND op2=:tam2 AND op3=:tam3 AND op4=:tam4 AND op5=:tam5 AND peca=:id";
	$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
	$rsTamanho->bindParam(':id', $prod, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam1', $tam1, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam2', $tam2, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam3', $tam3, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam4', $tam4, PDO::PARAM_INT, 5); 
	$rsTamanho->bindParam(':tam5', $tam5, PDO::PARAM_INT, 5); 
	$rsTamanho->execute();
	$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsTamanho = $rsTamanho->rowCount();

	if($totalRows_rsTamanho > 0) {
		$base_price = $class_produtos->precoProduto($prod, 1, 1, $row_rsTamanho['id']); 
		$diferenca = $class_produtos->precoProduto($prod, 4, 1, $row_rsTamanho['id']); 

		$base_price2 = number_format($base_price, 2, ',', '.');
		?>
		<select name="desconto_quantidades" id="qtd_<?php echo $prod; ?>" data-produto="<?php echo $prod; ?>" class="detalhe_sels quantidade_sel">
	    <option value="1"><?php echo "1 ".$Recursos->Resources["qtd_unidade"]." = ".$base_price2."�"; ?></option>
	    <?php if($totalRows_rsDescontoQTD > 0) { 
	    	foreach($row_rsDescontoQTD as $desconto) { 
	        $preco_desc = $base_price - (($base_price * $desconto['desconto']) / 100);
	        $preco_desc=number_format($preco_desc, 2, ',', '.');
	        $nome_desc = $desconto['min']." ".$Recursos->Resources["qtd_unidades"]." = ".$preco_desc."� ".$Recursos->Resources["qtd_cada"];
	    		?>
	    		<option value="<?php echo $desconto['min']; ?>"><?php echo $nome_desc; ?></option>
	  		<?php } 
	  	} ?>
		</select>   
	<?php }

	DB::close();
}

/* Carregar o carrinho (listagem normal) */
if($_POST['op'] == 'listagemCarrinho') {
	$produto_com_portes_gratis = 0;
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	$empty = $class_carrinho->isEmpty();
	$total = $total_final = $total_final_sem_promo = $class_carrinho->precoTotal();
	$total_peso = $class_carrinho->totalPeso();

	if(CARRINHO_SALDO == 1) {
		$query_rsProcS = "SELECT valor FROM carrinho_comprar WHERE session='$carrinho_session'";
		$rsProcS = DB::getInstance()->prepare($query_rsProcS);
		$rsProcS->execute();
		$row_rsProcS = $rsProcS->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsProcS = $rsProcS->rowCount();
		
		$saldo_acumula = $class_carrinho->acumularSaldo();
		
		$saldo_disp = $row_rsCliente['saldo'];  
		if($saldo_disp > 0){
			if($saldo_disp >= $total) {
				$saldo_a_utilizar = $total;
			}
			else {
				$saldo_a_utilizar = $saldo_disp;
			}

			if($totalRows_rsProcS > 0 && $row_rsProcS['valor'] > 0)	{	
				if($saldo_disp >= $total) {
					$saldo_compra = $total;
					$saldo_disp = $saldo_disp - $total;
					$total = 0;
				}
				else {
					$saldo_compra = $saldo_disp;
					$total = $total - $saldo_disp; 
					$saldo_disp = 0;
				}
			}
		}
	}

	if(CARRINHO_CODIGOS == 1) {
	    $query_rsCarCodProm = "SELECT id_codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";
	    $rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);
	    $rsCarCodProm->execute();
	    $row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);
	    $totalRows_rsCarCodProm = $rsCarCodProm->rowCount();
    
	    if($totalRows_rsCarCodProm > 0) {
        $query_rsCodProm = "SELECT codigo, tipo_desconto FROM codigos_promocionais WHERE id='".$row_rsCarCodProm["id_codigo"]."'";
        $rsCodProm = DB::getInstance()->prepare($query_rsCodProm);
        $rsCodProm->execute();
        $row_rsCodProm = $rsCodProm->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsCodProm = $rsCodProm->rowCount();

        $desconto_promo = $class_carrinho->calcula_cod_promo($row_rsCodProm['codigo']);
        if($row_rsCodProm['tipo_desconto'] == 1) {
          $total = $total - $desconto_promo; 
          $desconto_promo = "- ".$class_carrinho->mostraPreco($desconto_promo);
        }
        else {
          $total = $total - $desconto_promo; 
          $desconto_promo = "- ".$class_carrinho->mostraPreco($desconto_promo);
        }
	    }
	}

	if(CARRINHO_PONTOS == 1) { 
		$pontos_compra = 0;
		$preco_para_pontos = $total;
		
		if($preco_para_pontos > 0) {
			$query_rsPontos = "SELECT euros FROM config_ecommerce WHERE id='1'";
			$rsPontos = DB::getInstance()->prepare($query_rsPontos);
			$rsPontos->execute();
			$row_rsPontos = $rsPontos->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsPontos = $rsPontos->rowCount();
	
			$ponto_valor = $row_rsPontos['euros'];
			
			if($ponto_valor > 0) {
				$pontos_compra = $preco_para_pontos / $ponto_valor;
			}
		}
	}
	?>
	<div class="small-12 column">
		<?php if($empty > 0) { ?>
	    <div class="row collapse head">
        <div class="small-12 xsmall-12 xxsmall-7 medium-6 large-6 column"><h3><?php echo $Recursos->Resources["cart_prod"]; ?></h3></div>
        <div class="show-for-medium medium-3 large-2 column text-center"><h3><?php echo $Recursos->Resources["cart_qtd"]; ?></h3></div>
        <div class="show-for-xxsmall xxsmall-3 medium-2 large-3 column text-center"><h3><?php echo $Recursos->Resources["cart_price"]; ?></h3></div>
        <div class="show-for-xxsmall xxsmall-2 medium-1 column text-center"><h3><?php echo $Recursos->Resources["cart_delete"]; ?></h3></div>
	    </div>
    <?php } ?>
    <div class="div_100">
      <?php $listagem_cart = $class_carrinho->carrinhoDivs("carrinho"); ?>
    </div>
	</div>

	<?php if($empty > 0) { ?>
		<div class="small-12 column">
	    <?php if(CARRINHO_SALDO == 1 && $saldo_a_utilizar > 0) { ?>
	    	<div class="info_divs">
	        <div class="row align-middle collapse">
            <div class="column text-right">
              <div style="display:inline-block; position:relative">
                <input type="checkbox" value="1" name="usar_saldo" id="usar_saldo" onChange="comprar_com_saldo()" <?php if($totalRows_rsProcS > 0 && $row_rsProcS['valor'] > 0) echo "checked=\"checked\""; ?> />
                <label for="usar_saldo"><h3><?php echo $Recursos->Resources["utilizar_saldo"]; ?></h3></label>
              </div>
            </div>
            <div class="xxsmall-1 medium-1 large-1 column text-right">
              <span>- <?php echo $class_carrinho->mostraPreco($saldo_a_utilizar); ?></span>
            </div>
            <div class="small-2 xxsmall-2 medium-1 column text-center show-for-xxsmall">&nbsp;</div>
	        </div>
	    	</div>		
	    <?php } ?>

	    <?php if(CARRINHO_CODIGOS == 1) { ?>
				<?php if($totalRows_rsCarCodProm == 0) { ?>
	        <div class="info_divs">
            <div class="row align-middle collapse">
              <div class="column text-right">
                <div style="position:relative">
                  <h3><?php echo $Recursos->Resources["codigo_promocional_inserir"]; ?></h3><!--
                  --><input type="text" name="cod_promo" id="cod_promo" value=""/>
                </div>
              </div>
              <div class="xmedium-2 medium-2 large-2 column text-right show-for-xmedium" style="max-width: 1em;">&nbsp;</div>
              <div class="small-2 xxsmall-2 medium-1 column text-center">
              	<button type="button" onClick="altera_cod_promo('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', 1)"><?php echo $Recursos->Resources["codigo_promocional_aplicar"]; ?></button>
              </div>
            </div>
	        </div>
				<?php } else { ?>
	        <div class="info_divs">
            <div class="row align-middle collapse">
              <div class="column text-right">
                <div style="position:relative">
                  <h3><?php echo $Recursos->Resources["codigo_promocional_inserir"]; ?></h3><!--
                  --><input type="text" name="cod_promo" id="cod_promo" disabled value="<?php echo $row_rsCodProm["codigo"]; ?>" />
            		<input type="hidden" name="cod_promo_esc" id="cod_promo_esc" value="<?php echo $row_rsCodProm["codigo"]; ?>" />
                </div>
              </div>
              <div class="xxsmall-2 medium-2 large-2 column text-right">
              	<span><?php echo $desconto_promo; ?></span>
              </div>
              <div class="small-2 xxsmall-2 medium-1 column text-center">
              	<a href="javascript:;" class="carrinho-delete" onClick="limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 0, '')"></a>
              </div>
            </div>
	        </div>
				<?php } ?>
			<?php } ?>
		
	    <?php if(CARRINHO_PORTES == 1) { 
				if($produto_com_portes_gratis == 0) { 
	        if($row_rsCliente > 0) {
	        	$pais_cliente = $row_rsCliente["pais"];
	        }
	        else {
	        	$pais_cliente = 197;
	        }
	        
	        $query_rsPGratis = "SELECT zonas.portes_gratis1, zonas.peso_max, paises.nome AS pais_nome FROM zonas, paises WHERE paises.id='$pais_cliente' AND paises.zona=zonas.id";
	        $rsPGratis = DB::getInstance()->prepare($query_rsPGratis);
	        $rsPGratis->execute();
	        $row_rsPGratis = $rsPGratis->fetch(PDO::FETCH_ASSOC);
	        $totalRows_rsPGratis = $rsPGratis->rowCount();
	        
	        if($totalRows_rsPGratis > 0) {
            $valor_portes_total = $row_rsPGratis['portes_gratis1'];
            $valor_encomenda = $total;
            
            $valor_portes_falta = 0;
            $portes_gratis = 0;
            $portes_gratis_peso = 0;
            
            if($valor_portes_total > $valor_encomenda) {
              $valor_portes_falta = $valor_portes_total-$valor_encomenda;
            }
            else if($valor_portes_total > 0) {
              $portes_gratis = 1;
            }

            //Faz verifica��o pelo peso
            if(!$total_peso) {
            	$total_peso = $class_carrinho->totalPeso();
            }

            if($row_rsPGratis['peso_max'] > 0) {
            	$peso_max = $row_rsPGratis['peso_max'];
            	$num_descimal = 2;

            	if((round($peso_max/100,2) * 100) == $peso_max) {
            		$num_descimal = 0;
            	}

            	$p_gratis_peso = "<br>".str_replace("#peso_max#", number_format($peso_max, $num_descimal, ",", " "), $Recursos->Resources["portes_peso"]);

            	if($portes_gratis == 1 && $total_peso > $peso_max) {
            		$portes_gratis = 0;
            		$portes_gratis_peso = 1;
            		$p_gratis_peso2 = str_replace("#peso_max#", number_format($peso_max, $num_descimal, ",", " "), $Recursos->Resources["portes_peso2"]);
            	}
            }
          }
	      } 
	    }
			?>
	    <div class="info_divs">
	      <div class="row align-middle collapse">
	        <div class="column text-right">
	          <div style="position:relative">
	            <h3 class="big"><?php echo $Recursos->Resources["car_total"]; ?></h3>
	          </div>
	        </div>
	        <div class="xxsmall-2 medium-2 large-3 column text-right">
	          <span class="big"><?php echo $class_carrinho->mostraPreco($total); ?></span>
	        </div>
	        <div class="xxsmall-2 medium-1 column text-center">
	        	<p><?php echo $Recursos->Resources["iva_incluido"]; ?></p>
	        </div>
	      </div>
	    </div>
	    
			<?php if(CARRINHO_PONTOS == 1 && $pontos_compra > 0) { ?>
		    <div class="extras_divs">
	        <div class="row align-right collapse">
	          <div class="small-12 large-6 column text-right">
	            <?php echo str_replace("###", number_format($pontos_compra, 0, '.', ''), $Recursos->Resources["pontos_acumular"]); ?>
	          </div>
	        </div>
		    </div>
	    <?php } ?>
		    
	    <?php if(CARRINHO_SALDO == 1 && $saldo_acumula > 0) { ?>
		    <div class="extras_divs">
	        <div class="row align-right collapse">
	          <div class="small-12 large-6 column text-right">
	            <?php echo str_replace("###", $class_carrinho->mostraPreco($saldo_acumula), $Recursos->Resources["saldo_acumular"]); ?>
	          </div>
	        </div>
		    </div>
	    <?php } ?>
		    
	    <?php if(CARRINHO_PORTES == 1) { ?>
				<?php if($produto_com_portes_gratis == 1) { ?>
	      	<div class="extras_divs">
	          <div class="row align-right collapse">
	            <div class="small-12 large-6 column text-center medium-text-right">
	              <?php echo $Recursos->Resources["label_portes2"]." ".$row_rsPGratis['pais_nome']; ?>
	            </div>
	          </div>
	        </div>
	      <?php } else {
	        if($totalRows_rsPGratis > 0) { ?>								
	          <?php if($valor_portes_falta > 0) { ?>
	          	<div class="extras_divs">
	              <div class="row align-right collapse">
	                <div class="small-12 large-6 column text-center medium-text-right">
	                  <span><?php echo $Recursos->Resources["por_mais"]." <strong>".$class_carrinho->mostraPreco($valor_portes_falta); ?></strong></span> 
	            			<?php echo $Recursos->Resources["por_mais_2"]." ".$row_rsPGratis['pais_nome']; ?>.
	                </div>
	              </div>
	            </div>
	          <?php } else if($portes_gratis == 1) { ?>
	          	<div class="extras_divs">
	              <div class="row align-right collapse">
	                <div class="small-12 large-6 column text-center medium-text-right">
	                  <?php echo $Recursos->Resources["label_portes2"]." ".$row_rsPGratis['pais_nome']; ?>
	                </div>
	              </div>
	            </div>
	          <?php } ?>
	        <?php } ?>
	      <?php } ?>
	  	<?php } ?>
		</div>
		<div class="small-12 column">
	  	<div class="row collapse">
	    	<div class="small-12 medium-6 column carrinho_btn_voltar">
	        <a class="carrinho_btn disabled " href="<?php echo get_meta_link(6); ?>"><?php echo $Recursos->Resources["carrinho_btn_prev"]; ?></a>
	      </div>
	      <div class="small-12 medium-6 column carrinho_btn_continuar">
	        <?php if($row_rsCliente == 0 && CARRINHO_LOGIN == 1) { ?>
	        	<a class="carrinho_btn icon_carrinho" href="login.php?carr=1"><span style="position: relative;"><?php echo $Recursos->Resources["carrinho_btn_next"]; ?> <i class="icon-right-carrinho"></i></span></a>
	        <?php } else if($row_rsCliente > 0) { ?>
	        	<a class="store_name carrinho_btn icon_carrinho" href="carrinho-comprar.php"><span style="position: relative;"><?php echo $Recursos->Resources["carrinho_btn_next"]; ?> <i class="icon-right-carrinho"></i></span></a>
	        <?php } else { ?>
	        	<a class="carrinho_btn icon_carrinho" href="javascript:;" onclick="loginCheck()"><span style="position: relative;"><?php echo $Recursos->Resources["carrinho_btn_next"]; ?> <i class="icon-right-carrinho"></i></span></a>
	        <?php } ?>
	      </div>   
	    </div>
	  </div>
	<?php }

	DB::close();
}

/* Repetir uma encomenda */
if($_POST['op'] == 'repetirEncomenda') {
	$id = $_POST['id'];
	$carrinho_session = $_COOKIE[CARRINHO_SESSION];
	
	if($carrinho_session != '') {
		$insertSQL = "DELETE FROM carrinho WHERE session <= '$carrinho_session'";
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->execute();
		
		$insertSQL = "DELETE FROM carrinho_comprar WHERE session <= '$carrinho_session'";
		$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
		$rsInsertSQL->execute();	

		if(CARRINHO_CODIGOS == 1) {
			$insertSQL = "DELETE FROM carrinho_cod_prom WHERE session <= '$carrinho_session'";
			$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
			$rsInsertSQL->execute();
		}
	}
	else {
		$ses_id = strtotime(date("YmdHis", time()));

		$timeout = 3600 * 24 * 5; //5 dias
		setcookie(CARRINHO_SESSION, $ses_id, time() + $timeout, "/", "", $cookie_secure, true);
		$carrinho_session = $ses_id;
	}

	$query_rsEncProds = "SELECT produto_id, opcoes, opcoes_id, qtd FROM encomendas_produtos WHERE id_encomenda=:id ORDER BY id ASC";
	$rsEncProds = DB::getInstance()->prepare($query_rsEncProds);
	$rsEncProds->bindParam(':id', $id, PDO::PARAM_INT);
	$rsEncProds->execute();
	$totalRows_rsEncProds = $rsEncProds->rowCount();

	if($totalRows_rsEncProds > 0) {
		while($row_rsEncProds = $rsEncProds->fetch()) {
			$id_prod = $row_rsEncProds['produto_id'];
			$opcoes = $row_rsEncProds['opcoes'];
			$id_opcao = $row_rsEncProds['opcoes_id'];
			$qtd = $row_rsEncProds['qtd'];
			$qtd_nova = 0;
			$tam1 = 0;
			$tam2 = 0;
			$tam3 = 0;
			$tam4 = 0;
			$tam5 = 0;
			$id_tamanho = 0;

			//Verificar se o produto ainda est� dispon�vel
			$query_rsProduto = "SELECT id, ref, nome, imagem4, url, iva, stock, nao_limitar_stock FROM l_pecas".$extensao." WHERE id = :id AND visivel = 1";
			$rsProduto = DB::getInstance()->prepare($query_rsProduto);
			$rsProduto->bindParam(':id', $id_prod, PDO::PARAM_INT);
			$rsProduto->execute();
			$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsProduto = $rsProduto->rowCount();

			if($totalRows_rsProduto > 0) {
				if($id_opcao > 0) {
					$query_rsTamanho = "SELECT * FROM l_pecas_tamanhos WHERE peca = :peca AND id = :id";
					$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
					$rsTamanho->bindParam(':peca', $id_prod, PDO::PARAM_INT);
					$rsTamanho->bindParam(':id', $id_opcao, PDO::PARAM_INT);
					$rsTamanho->execute();
					$totalRows_rsTamanho = $rsTamanho->rowCount();
					$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

					if($totalRows_rsTamanho > 0) {
						$id_tamanho = $row_rsTamanho['id'];
						$tam1 = $row_rsTamanho['op1'];
						$tam2 = $row_rsTamanho['op2'];
						$tam3 = $row_rsTamanho['op3'];
						$tam4 = $row_rsTamanho['op4'];
						$tam5 = $row_rsTamanho['op5'];

						if($row_rsProduto['nao_limitar_stock'] == 1 || $row_rsTamanho['stock'] >= $qtd) {
							$qtd_nova = $qtd;
						}
						else {
							$qtd_nova = $row_rsTamanho['stock'];
						}
					}
				}
				else {
					if($row_rsProduto['nao_limitar_stock'] == 1 || $row_rsProduto['stock'] >= $qtd) {
						$qtd_nova = $qtd;
					}
					else {
						$qtd_nova = $row_rsProduto['stock'];
					}
				}

				//Se estiver dispon�vel insere no carrinho
				if($qtd_nova > 0) {
					$preco = $class_produtos->precoProduto($id_prod, 0, $qtd_nova, $id_tamanho);

					$query_rsMaxID = "SELECT MAX(id) FROM carrinho";
					$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);
					$rsMaxID->execute();
					$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
					
					$max_id = $row_rsMaxID['MAX(id)'] + 1;
					
					$insertSQL = "INSERT INTO carrinho (id, session, produto, opcoes, quantidade, preco, op1, op2, op3, op4, op5) VALUES (:id, :session, :produto, :opcoes, :quantidade, :preco, :op1, :op2, :op3, :op4, :op5)";
					$rsInsertSQL = DB::getInstance()->prepare($insertSQL);
					$rsInsertSQL->bindParam(':id', $max_id, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':session', $carrinho_session, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':produto', $id_prod, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':opcoes', $opcoes, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':quantidade', $qtd_nova, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':preco', $preco, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':op1', $tam1, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':op2', $tam2, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':op3', $tam3, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':op4', $tam4, PDO::PARAM_INT);
					$rsInsertSQL->bindParam(':op5', $tam5, PDO::PARAM_INT);
					$rsInsertSQL->execute();
				}
			}
		}
	}

	DB::close();
}
?>

<script>
$(document).ready(function(){
  $("#entrega_8").click(function(){
    $(".portdisbale").hide();
    $(".shipping").hide();
  });
});
</script>

<script>
$(document).ready(function(){
  $("#entrega_14").click(function(){
    $(".portdisbale").show();
    $(".shipping").show();
  });
});
</script>
