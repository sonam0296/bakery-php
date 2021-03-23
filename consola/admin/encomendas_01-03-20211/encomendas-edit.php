<?php include_once("../inc_pages.php");

include("../../../includes/geraRef.php");

//ini_set('display_errors', 1);



$menu_sel="encomendas";

$menu_sub_sel="";



$tab_sel=1;

if($_GET["tab_sel"] > 0) $tab_sel = $_GET['tab_sel'];



$id=$_GET["id"];



$inserido=0;

$estado_muda = 0;

$estado = 0;



if(isset($_POST["MM_edit"]) && $_POST["MM_edit"] == "edit_morada_fatura") {

	$insertSQL = "UPDATE encomendas SET nome=:nome_fatura, email=:email_fatura, nif=:nif_fatura, morada_fatura=:morada_fatura, codpostal_fatura=:codpostal_fatura, localidade_fatura=:localidade_fatura, pais_fatura=:pais_fatura WHERE id=:id";

	$rsInsert = DB::getInstance()->prepare($insertSQL);		

	$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);

	$rsInsert->bindParam(":nome_fatura", $_POST["nome_fatura"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":email_fatura", $_POST["email_fatura"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":nif_fatura", $_POST["nif_fatura"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":pais_fatura", $_POST["pais_fatura_select"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":localidade_fatura", $_POST["localidade_fatura"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":codpostal_fatura", $_POST["codpostal_fatura"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":morada_fatura", $_POST["morada_fatura"], PDO::PARAM_STR, 5);

	$rsInsert->execute();



	$inserido=1;

}



if(isset($_POST["MM_edit"]) && $_POST["MM_edit"] == "edit_morada_envio") {

	$insertSQL = "UPDATE encomendas SET nome_envio=:nome_envio, telemovel=:telemovel_envio, morada_envio=:morada_envio, codpostal_envio=:codpostal_envio, localidade_envio=:localidade_envio, pais_envio=:pais_envio WHERE id=:id";

	$rsInsert = DB::getInstance()->prepare($insertSQL);		

	$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);

	$rsInsert->bindParam(":nome_envio", $_POST["nome_envio"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":telemovel_envio", $_POST["telemovel_envio"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":pais_envio", $_POST["pais_envio_select"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":codpostal_envio", $_POST["codpostal_envio"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":localidade_envio", $_POST["localidade_envio"], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(":morada_envio", $_POST["morada_envio"], PDO::PARAM_STR, 5);

	$rsInsert->execute();



	$inserido=1;

}



if((isset($_POST["MM_edit"])) && ($_POST["MM_edit"] == "edit_encomenda")) {

	$query_rsEncomenda = "SELECT * FROM encomendas WHERE id=:id_encomenda";

	$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);

	$rsEncomenda->bindParam(":id_encomenda", $id, PDO::PARAM_INT);	

	$rsEncomenda->execute();

	$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsEncomenda = $rsEncomenda->rowCount();

	

	$enviar_email = 0;

	if(isset($_POST["enviar_email"])) $enviar_email = 1;

	

	//Guardar alterações sobre o estado na base de dados

	if($row_rsEncomenda["estado"] != $_POST["encomenda_estado_select"]) {

		$data = date("Y-m-d H:i:s");



		$insertSQL = "INSERT INTO enc_estados_historico (id_encomenda, estado, data, notificado) VALUES (:id, :estado, :data, :notificado)";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->bindParam(":estado", $_POST["encomenda_estado_select"], PDO::PARAM_INT);	

		$rsInsert->bindParam(":data", $data, PDO::PARAM_STR, 5);			

		$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);

		$rsInsert->bindParam(":notificado", $enviar_email, PDO::PARAM_INT);

		$rsInsert->execute();

		

		$estado = $_POST['encomenda_estado_select'];

		$estado_muda=1;

	}



	$insertSQL = "UPDATE encomendas SET estado=:estado, envio_link=:envio_link, envio_ref=:envio_ref, cancle_mes=:cancle_mes WHERE id=:id";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(":estado", $_POST["encomenda_estado_select"], PDO::PARAM_INT);	

	$rsInsert->bindParam(":envio_link", $_POST["envio_link"], PDO::PARAM_STR, 5);		

	$rsInsert->bindParam(":envio_ref", $_POST["envio_ref"], PDO::PARAM_STR, 5);	

	$rsInsert->bindParam(":cancle_mes", $_POST["cancle_mes"], PDO::PARAM_STR, 5);			

	$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);

	$rsInsert->execute();



	

  //ATRIBUI SALDO AO UTILIZADOR QUANDO VALIDA A ENCOMENDA

  if(CARRINHO_PONTOS == 1 || CARRINHO_SALDO == 1) {

	  if($_POST["encomenda_estado_select"]==2 || $_POST["encomenda_estado_select"]==3 || $_POST["encomenda_estado_select"]==4 || $_POST["encomenda_estado_select"]==6) {

			

			//ATRIBUI SALDO NA CONTA

			if(CARRINHO_SALDO == 1) {

				$saldo_disponivel=$row_rsEncomenda["saldo_compra"];

				$saldo_compra_utilizado=$row_rsEncomenda["saldo_compra_utilizado"];



				//Converter saldo para € (caso necessário)

				if($row_rsEncomenda['valor_conversao'] > 0) {

					$saldo_disponivel = round($row_rsEncomenda["saldo_compra"] / $row_rsEncomenda['valor_conversao'], 2);

				}

				

				if($saldo_disponivel>0 && $saldo_compra_utilizado!="1") {

					$id=$id;

					

					$insertSQL = "UPDATE encomendas SET saldo_compra_utilizado='1' WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);

					$rsInsert->execute();

					

					//insere a informação no saldo

					$query_rsMaxID = "SELECT MAX(clientes_saldo.id) FROM clientes_saldo";

					$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);

					$rsMaxID->execute();

					$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsMaxID = $rsMaxID->rowCount();

					

					$id_max=$row_rsMaxID["MAX(clientes_saldo.id)"]+1;

											

					$numero=$row_rsEncomenda["numero"];

					

					$detalhe="Encomenda N.".$numero;

					

					$cliente=$row_rsEncomenda["id_cliente"];

					$valor=$saldo_disponivel;

					$encomenda_id=$id;

					$operacao=1;

					$validado=1;

					$bonus_id=0;

					$cheque_id=0;

					$data=date('Y-m-d H:i:s');

					

					$insertSQL = "INSERT INTO clientes_saldo (id, cliente_id, valor, encomenda_id, operacao, detalhe, data, validado, cheque_id) VALUES (:id_max, :cliente_id, :valor, :encomenda_id, :operacao, :detalhe, :data, :validado, :cheque_id)";	

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':encomenda_id', $id, PDO::PARAM_INT);

					$rsInsert->bindParam(':valor', $valor, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':cliente_id', $cliente, PDO::PARAM_INT);

					$rsInsert->bindParam(':operacao', $operacao, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':detalhe', $detalhe, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':validado', $validado, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':cheque_id', $cheque_id, PDO::PARAM_INT);

					$rsInsert->bindParam(':id_max', $id_max, PDO::PARAM_INT);

					$rsInsert->execute();

					

					//atribui saldo na conta

					$query_rsProc = "SELECT saldo FROM clientes WHERE id=:id";

					$rsProc = DB::getInstance()->prepare($query_rsProc);

					$rsProc->bindParam(':id', $cliente, PDO::PARAM_INT);

					$rsProc->execute();

					$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsProc = $rsProc->rowCount();

							

					if($row_rsProc['saldo']>0) $saldo_actual=$row_rsProc['saldo']; else $saldo_actual=0;

			

					$valor_final=$saldo_actual+$valor;

					

					if($valor_final<0) $valor_final=0;

					

					$insertSQL = "UPDATE clientes SET saldo=:saldo WHERE id=:id_cliente";	

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':saldo', $valor_final, PDO::PARAM_INT);

					$rsInsert->bindParam(':id_cliente', $cliente, PDO::PARAM_INT);

					$rsInsert->execute();

				} 

			} 

			

			

			//ATRIBUI PONTOS AO CLIENTE

			if(CARRINHO_PONTOS == 1) {

				$pontos_disponivel=$row_rsEncomenda["pontos_compra"];

				$pontos_compra_utilizado=$row_rsEncomenda["pontos_compra_utilizado"];

				

				if($pontos_disponivel>0 && $pontos_compra_utilizado!="1") {

					$id=$id;

					

					$insertSQL = "UPDATE encomendas SET pontos_compra_utilizado='1' WHERE id=:id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(":id", $id, PDO::PARAM_INT);

					$rsInsert->execute();

					

					//insere a informação no saldo

					$query_rsMaxID = "SELECT MAX(clientes_pontos.id) FROM clientes_pontos";

					$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);

					$rsMaxID->execute();

					$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsMaxID = $rsMaxID->rowCount();

					

					$id_max=$row_rsMaxID["MAX(clientes_pontos.id)"]+1;

											

					$numero=$row_rsEncomenda["numero"];

					

					$detalhe="Encomenda N.".$numero;

					

					$cliente=$row_rsEncomenda["id_cliente"];

					$valor=$pontos_disponivel;

					$encomenda_id=$id;

					$operacao=1;

					$validado=1;

					$data=date('Y-m-d H:i:s');

					

					$insertSQL = "INSERT INTO clientes_pontos (id, cliente_id, valor, encomenda_id, operacao, detalhe, data, validado) VALUES (:id_max, :cliente_id, :valor, :encomenda_id, :operacao, :detalhe, :data, :validado)";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':cliente_id', $cliente, PDO::PARAM_INT);

					$rsInsert->bindParam(':valor', $valor, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':encomenda_id', $id, PDO::PARAM_INT);

					$rsInsert->bindParam(':operacao', $operacao, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':detalhe', $detalhe, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':validado', $validado, PDO::PARAM_STR, 5);

					$rsInsert->bindParam(':id_max', $id_max, PDO::PARAM_INT);

					$rsInsert->execute();



					

					//atribui saldo na conta

					$query_rsProc = "SELECT pontos FROM clientes WHERE id=:cliente_id";

					$rsProc = DB::getInstance()->prepare($query_rsProc);

					$rsProc->bindParam(':cliente_id',$cliente, PDO::PARAM_INT);

					$rsProc->execute();

					$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsProc = $rsProc->rowCount();

							

					if($row_rsProc["pontos"]>0) $pontos_actual=$row_rsProc["pontos"]; else $pontos_actual=0;

			

					$valor_final=$pontos_actual+$valor;

					

					if($valor_final<0) $valor_final=0;

					

					$insertSQL = "UPDATE clientes SET pontos=:valor_final WHERE id=:cliente";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':valor_final', $valor_final, PDO::PARAM_INT);

					$rsInsert->bindParam(':cliente', $cliente, PDO::PARAM_INT);

					$rsInsert->execute();



					DB::close();

				}

			} 





			//VERIFICA SE É A PRIMEIRA COMPRA - DAR VALOR AO AMIGO

			// $valor_encomenda=$row_rsEncomenda['valor_total'];

			// $cliente=$row_rsEncomenda['id_cliente'];



			// //CALCULA % A DAR

			// $query_rsTx = "SELECT * FROM config_ecommerce WHERE id='1'";

			// $rsTx = DB::getInstance()->prepare($query_rsTx);

			// $rsTx->execute();

			// $row_rsTx = $rsTx->fetch(PDO::FETCH_ASSOC);

			// $totalRows_rsTx = $rsTx->rowCount();



			// $valor_por_compra_amigo = $row_rsTx['valor_por_compra_amigo'];

			// $tipo_compra_amigo = $row_rsTx['tipo_compra_amigo'];

			// $perc_valor_por_compra_amigo = $row_rsTx['saldo_por_compra_amigo'];



			// $query_rsProcE = "SELECT id FROM encomendas WHERE id_cliente=:cliente AND id!=:id AND valor_total >= :valor_por_compra_amigo AND estado!='1' AND estado!='5'";

			// $rsProcE = DB::getInstance()->prepare($query_rsProcE);

			// $rsProcE->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			// $rsProcE->bindParam(':id', $id, PDO::PARAM_INT);

			// $rsProcE->bindParam(':valor_por_compra_amigo', $valor_por_compra_amigo, PDO::PARAM_INT);

			// $rsProcE->execute();

			// $row_rsProcE = $rsProcE->fetch(PDO::FETCH_ASSOC);

			// $totalRows_rsProcE = $rsProcE->rowCount();

			

			// if($totalRows_rsProcE==0 && $valor_encomenda >= $valor_por_compra_amigo) {

			// 	//procura amigo

			// 	$query_rsProcC = "SELECT * FROM clientes WHERE id=:cliente";

			// 	$rsProcC = DB::getInstance()->prepare($query_rsProcC);

			//  $rsProcC->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			// 	$rsProcC->execute();

			// 	$row_rsProcC = $rsProcC->fetch(PDO::FETCH_ASSOC);

			// 	$totalRows_rsProcC = $rsProcC->rowCount();

				

			// 	$id_amigo=$row_rsProcC['referer'];



			// 	$query_rsProcA = "SELECT * FROM clientes WHERE id=:id_amigo";

			// 	$rsProcA = DB::getInstance()->prepare($query_rsProcA);

			//  $rsProcA->bindParam(':id_amigo', $id_amigo, PDO::PARAM_INT);

			// 	$rsProcA->execute();

			// 	$row_rsProcA = $rsProcA->fetch(PDO::FETCH_ASSOC);

			// 	$totalRows_rsProcA = $rsProcA->rowCount();

				

			// 	//insere se tiver amigo

			// 	if($totalRows_rsProcA > 0) {				

			//		if($tipo_compra_amigo == 1) { //Atribui X %

			//			$saldo_disponivel = $valor_encomenda * ($perc_valor_por_compra_amigo / 100);

			//		}

			//		else if($tipo_compra_amigo == 2) { //Atribui X €

			//			$saldo_disponivel = $perc_valor_por_compra_amigo;

			//		}

					

			// 		//Verificar se já atribuiu o saldo ao amigo para não voltar a fazer

			// 		$encomenda_id=$id;

			// 		$cliente=$row_rsProcA['id'];



			// 		$query_rsExiste = "SELECT COUNT(id) as total FROM clientes_saldo WHERE cliente_id=:cliente AND encomenda_id =:encomenda_id";

			// 		$rsExiste = DB::getInstance()->prepare($query_rsExiste);

			//		$rsExiste->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			//		$rsExiste->bindParam(':encomenda_id', $encomenda_id, PDO::PARAM_INT);

			// 		$rsExiste->execute();

			// 		$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);



			// 		if($saldo_disponivel > 0 && $row_rsExiste['total'] == 0) {

			// 			$query_rsMaxID = "SELECT MAX(id) FROM clientes_saldo";

			// 			$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);

			// 			$rsMaxID->execute();

			// 			$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);

			// 			$totalRows_rsMaxID = $rsMaxID->rowCount();

						

			// 			$id_max=$row_rsMaxID['MAX(id)']+1;

												

			// 			$numero=$row_rsEncomenda['numero'];

						

			// 			$detalhe="Amigo ".$row_rsProcC['nome']." - Encomenda N.".$numero;

						

			// 			$valor=$saldo_disponivel;

			// 			$operacao=1;

			// 			$validado=1;

			// 			$data=date('Y-m-d H:i:s');



			// 			$insertSQL = "INSERT INTO clientes_saldo (id, cliente_id, valor, encomenda_id, operacao, detalhe, data, validado) VALUES (:id_max, :cliente, :valor, :encomenda_id, :operacao, :detalhe, :data, :validado)";	

			// 			$rsInsert = DB::getInstance()->prepare($insertSQL);

			//			$rsInsert->bindParam(':id_max', $id_max, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':valor', $valor, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':encomenda_id', $encomenda_id, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':operacao', $operacao, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':detalhe', $detalhe, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':data', $data, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':validado', $validado, PDO::PARAM_INT);

			// 			$rsInsert->execute();

						

			// 			if($row_rsProcA['saldo']>0) $saldo_actual=$row_rsProcA['saldo']; else $saldo_actual=0;



			// 			$valor_final=$saldo_actual+$valor;

						

			// 			if($valor_final<0) $valor_final=0;



			// 			$insertSQL = "UPDATE clientes SET saldo=:valor_final WHERE id=:cliente";	

			// 			$rsInsert = DB::getInstance()->prepare($insertSQL);

			//			$rsInsert->bindParam(':valor_final', $valor_final, PDO::PARAM_INT);

			//			$rsInsert->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			// 			$rsInsert->execute();

			// 			DB::close();

			// 		}

			// 	}

			// }

	  }

	}

	 	 



  //REPÕE O STOCK			

	if($_POST["encomenda_estado_select"]==5 && $row_rsEncomenda["estado"]!=5 && isset($_POST["repoe_stock"])) {

		$id_enc=$row_rsEncomenda["id"];

		

		$query_rsProdutos = "SELECT * FROM encomendas_produtos WHERE id_encomenda = :id_enc";

		$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);

		$rsProdutos->bindParam(':id_enc', $id_enc, PDO::PARAM_INT);

		$rsProdutos->execute();

		$row_rsProdutos = $rsProdutos->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsProdutos = $rsProdutos->rowCount();



		do {

			$produto=$row_rsProdutos["produto_id"];

			$tamanho_id=$row_rsProdutos["opcoes_id"];

			$qtd=$row_rsProdutos["qtd"];

		

			//verifica se o produto é um conjunto

			$query_rsProcProd = "SELECT * FROM l_pecas_en WHERE id =:produto";

			$rsProcProd = DB::getInstance()->prepare($query_rsProcProd);

			$rsProcProd->bindParam(':produto', $produto, PDO::PARAM_INT);

			$rsProcProd->execute();

			$row_rsProcProd = $rsProcProd->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProcProd = $rsProcProd->rowCount();

			

			if($row_rsProcProd["tem_conjunto"]==1) {

				$query_rsConjunto = "SELECT id_relacao FROM l_pecas_relacao WHERE id_peca=:produto ORDER BY id ASC";

				$rsConjunto = DB::getInstance()->prepare($query_rsConjunto);

				$rsConjunto->bindParam(':produto', $produto, PDO::PARAM_INT);

				$rsConjunto->execute();

				$row_rsConjunto = $rsConjunto->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsConjunto = $rsConjunto->rowCount();

			

				if($totalRows_rsConjunto>0) {

					do {

						//produto do conjunto

						$produto_conjunto=$row_rsConjunto["id_relacao"];

													

						$query_rsLinguas = "SELECT sufixo FROM linguas ORDER BY linguas.id ASC";

						$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

						$rsLinguas->execute();

						$row_rsLinguas = $rsLinguas->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsLinguas = $rsConjunto->rowCount();

						

						if($totalRows_rsLinguas>0) {

							do {

								$insertSQL = "UPDATE l_pecas_".$row_rsLinguas["sufixo"]." SET stock=stock+:qtd WHERE id=:produto_conjunto";		

								$rsInsert = DB::getInstance()->prepare($insertSQL);

								$rsInsert->bindParam(':qtd', $qtd, PDO::PARAM_INT);

								$rsInsert->bindParam(':produto_conjunto', $produto_conjunto, PDO::PARAM_INT);

								$rsInsert->execute();

							} while($row_rsLinguas = $rsLinguas->fetch());

						}

						

						$query_rsProc = "SELECT * FROM l_pecas_en WHERE id = :produto_conjunto";

						$rsProc = DB::getInstance()->prepare($query_rsProc);

						$rsProc->bindParam(':produto_conjunto', $produto_conjunto, PDO::PARAM_INT);

						$rsProc->execute();

						$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsProc = $rsProc->rowCount();

						

						$stock_disponivel=$row_rsProc["stock"];

						

						if($stock_disponivel<0) {

							$query_rsLinguas = "SELECT sufixo FROM linguas ORDER BY linguas.id ASC";

							$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

							$rsLinguas->execute();

							$row_rsLinguas = $rsLinguas->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsLinguas = $rsLinguas->rowCount();



							if($totalRows_rsLinguas>0) {

								do {

									$insertSQL = "UPDATE l_pecas_".$row_rsLinguas['sufixo']." SET stock='0' WHERE id=:produto_conjunto";		

									$rsInsert = DB::getInstance()->prepare($insertSQL);

									$rsInsert->bindParam(':produto_conjunto', $produto_conjunto, PDO::PARAM_INT);

									$rsInsert->execute();

								} while($row_rsLinguas = $rsLinguas->fetch());

							}

						}

					} while($row_rsConjunto = $rsConjunto->fetch());

				}

			}

			else {

				$query_rsT = "SELECT id FROM l_pecas_tamanhos WHERE l_pecas_tamanhos.peca=:produto AND l_pecas_tamanhos.id=:tamanho_id";

				$rsT = DB::getInstance()->prepare($query_rsT);

				$rsT->bindParam(':produto', $produto, PDO::PARAM_INT);

				$rsT->bindParam(':tamanho_id', $tamanho_id, PDO::PARAM_INT);

				$rsT->execute();

				$totalRows_rsT = $rsT->rowCount();

				

				if($totalRows_rsT>0) {

					$insertSQL = "UPDATE l_pecas_tamanhos SET stock=stock+:qtd WHERE l_pecas_tamanhos.peca=:produto AND l_pecas_tamanhos.id=:tamanho_id";

					$rsInsert = DB::getInstance()->prepare($insertSQL);

					$rsInsert->bindParam(':qtd', $qtd, PDO::PARAM_INT);

					$rsInsert->bindParam(':produto', $produto, PDO::PARAM_INT);

					$rsInsert->bindParam(':tamanho_id', $tamanho_id, PDO::PARAM_INT);

					$rsInsert->execute();

					// $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

					

					$query_rsProc = "SELECT stock FROM l_pecas_tamanhos WHERE l_pecas_tamanhos.peca=:produto AND l_pecas_tamanhos.id=:tamanho_id";

					$rsProc = DB::getInstance()->prepare($query_rsProc);

					$rsProc->bindParam(':produto', $produto, PDO::PARAM_INT);

					$rsProc->bindParam(':tamanho_id', $tamanho_id, PDO::PARAM_INT);

					$rsProc->execute();

					$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsProc = $rsProc->rowCount();

					

					$stock_disponivel=$row_rsProc["stock"];

					

					if($stock_disponivel<0) {

						$insertSQL = "UPDATE l_pecas_tamanhos SET stock='0' WHERE l_pecas_tamanhos.peca=:produto AND l_pecas_tamanhos.id=:tamanho_id";

						$rsInsert = DB::getInstance()->prepare($insertSQL);

						$rsInsert->bindParam(':produto', $produto, PDO::PARAM_INT);

						$rsInsert->bindParam(':tamanho_id', $tamanho_id, PDO::PARAM_INT);

						$rsInsert->execute();

					}

				}

				else {

					$query_rsLinguas = "SELECT sufixo FROM linguas ORDER BY linguas.id ASC";

					$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

					$rsLinguas->execute();

					$row_rsLinguas = $rsLinguas->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsLinguas = $rsLinguas->rowCount();

					

					if($totalRows_rsLinguas>0) {		

						do {	

							$insertSQL = "UPDATE l_pecas_".$row_rsLinguas["sufixo"]." SET stock=stock+ :qtd WHERE id=:produto";		

							$rsInsert = DB::getInstance()->prepare($insertSQL);

							$rsInsert->bindParam(':qtd', $qtd, PDO::PARAM_INT);

							$rsInsert->bindParam(':produto', $produto, PDO::PARAM_INT);

							$rsInsert->execute();

						} while($row_rsLinguas = $rsLinguas->fetch());

					}

					

					$query_rsProc = "SELECT stock FROM l_pecas_en WHERE id =:produto";

					$rsProc = DB::getInstance()->prepare($query_rsProc);

					$rsProc->bindParam(':produto', $produto, PDO::PARAM_INT);

					$rsProc->execute();

					$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsProc = $rsProc->rowCount();

					

					$stock_disponivel=$row_rsProc["stock"];

					

					if($stock_disponivel<0) {

						$query_rsLinguas = "SELECT sufixo FROM linguas ORDER BY linguas.id ASC";

						$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

						$rsLinguas->execute();

						$row_rsLinguas = $rsLinguas->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsLinguas = $rsLinguas->rowCount();

						

						if($totalRows_rsLinguas>0) {

							do {

								$insertSQL = "UPDATE l_pecas_".$row_rsLinguas["sufixo"]." SET stock='0' WHERE id=:produto";		

								$rsInsert = DB::getInstance()->prepare($insertSQL);

								$rsInsert->bindParam(':produto', $produto, PDO::PARAM_INT);

								$rsInsert->execute();

								

							} while($row_rsLinguas = $rsLinguas->fetch());

						}

					}

				}

			}

		} while($row_rsProdutos = $rsProdutos->fetch());

		DB::close();			

	}





	//Verifica se foi utilizado saldo na encomenda a anular e repõe o saldo

	if($_POST['encomenda_estado_select']==5 && $row_rsEncomenda['estado']!=5) {	

		$id=$_GET['id'];

			

		$saldo_disponivel=$row_rsEncomenda['compra_valor_saldo'];

		

		if($saldo_disponivel>0) {

			$query_rsMaxID = "SELECT MAX(id) FROM clientes_saldo";

			$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);

			$rsMaxID->execute();

			$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);

			DB::close();

			

			$id_max=$row_rsMaxID['MAX(id)']+1;

									

			$numero=$row_rsEncomenda['numero'];

			

			$detalhe="Anular - Encomenda N.".$numero;

			

			$cliente=$row_rsEncomenda['id_cliente'];

			$valor=$saldo_disponivel;

			$encomenda_id=$id;

			$operacao=1;

			$validado=1;

			$bonus_id=0;

			$cheque_id=0;

			$data=date('Y-m-d H:i:s');



			$insertSQL = "INSERT INTO clientes_saldo (id, cliente_id, valor, encomenda_id, operacao, detalhe, data, validado) VALUES (:id_max, :cliente, :valor, :encomenda_id, :operacao, :detalhe, :data, :validado)";

			$rsInsert->bindParam(':id_max', $id_max, PDO::PARAM_INT);

			$rsInsert->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			$rsInsert->bindParam(':valor', $valor, PDO::PARAM_INT);

			$rsInsert->bindParam(':encomenda_id', $encomenda_id, PDO::PARAM_INT);

			$rsInsert->bindParam(':operacao', $operacao, PDO::PARAM_INT);

			$rsInsert->bindParam(':detalhe', $detalhe, PDO::PARAM_INT);

			$rsInsert->bindParam(':data', $data, PDO::PARAM_INT);

			$rsInsert->bindParam(':validado', $validado, PDO::PARAM_INT);

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->execute();



			

			//atribui saldo na conta

			$query_rsProc = "SELECT saldo FROM clientes WHERE id=:cliente";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			$rsProc->execute();

			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProc = $rsProc->rowCount();

					

			if($row_rsProc['saldo']>0) $saldo_actual=$row_rsProc['saldo']; else $saldo_actual=0;

	

			$valor_final=$saldo_actual+$valor;

			

			if($valor_final<0) $valor_final=0;



			$insertSQL = "UPDATE clientes SET saldo=:valor_final WHERE id=:cliente";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':valor_final', $valor_final, PDO::PARAM_INT);

			$rsInsert->bindParam(':cliente', $cliente, PDO::PARAM_INT);

			$rsInsert->execute();

		}

	}



	if(($_POST["encomenda_estado_select"] == 2 && $row_rsEncomenda["estado"] != 2) || ($_POST["encomenda_estado_select"] == 3 && $row_rsEncomenda["estado"] != 3) || ($_POST["encomenda_estado_select"] == 5 && $row_rsEncomenda["estado"] != 5) || ($_POST["encomenda_estado_select"] == 6 && $row_rsEncomenda["estado"] != 6)) {

		$id_enc = $row_rsEncomenda["id"];



		//Envia email

		if($enviar_email == 1) {

			$lingua = "pt";

			if($row_rsEncomenda['lingua']) {

				$lingua = $row_rsEncomenda['lingua'];

			}



			include_once(ROOTPATH."linguas/".$lingua.".php");

			$className = "Recursos_".$lingua;

			$Recursos = new $className();

			$extensao = "_".$lingua;



			$class_carrinho->emailEncomenda($id_enc);

		}

	}

			  

	$inserido=1;

}



$query_rsEncomenda = "SELECT * FROM encomendas WHERE id=:id_encomenda";

$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);

$rsEncomenda->bindParam(":id_encomenda", $id, PDO::PARAM_INT);	

$rsEncomenda->execute();

$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);

$totalRows_rsEncomenda = $rsEncomenda->rowCount();



$id_encomenda = $row_rsEncomenda["id"];

$clienteid = $row_rsEncomenda["id_cliente"];



$query_rsPCheck = "SELECT * FROM clientes WHERE id = ".$clienteid;

$rsPcheck = DB::getInstance()->prepare($query_rsPCheck);

$rsPcheck->execute();

$row_rsPcheck = $rsPcheck->fetch(PDO::FETCH_ASSOC);

$totalRows_rsPcheck = $rsPcheck->rowCount();



$rolecheck = $row_rsPcheck["roll"];



$query_rsEstado = "SELECT ee.* FROM enc_estados ee, encomendas e WHERE e.estado=ee.id AND e.id=:id_encomenda";

$rsEstado = DB::getInstance()->prepare($query_rsEstado);

$rsEstado->bindParam(":id_encomenda", $id_encomenda, PDO::PARAM_INT);	

$rsEstado->execute();

$row_rsEstado = $rsEstado->fetch(PDO::FETCH_ASSOC);

$totalRows_rsEstado = $rsEstado->rowCount();



$query_rsMetPagamento = "SELECT mp.* FROM met_pagamento_pt mp, encomendas e WHERE e.id=:id_encomenda AND e.met_pagamt_id=mp.id";

$rsMetPagamento = DB::getInstance()->prepare($query_rsMetPagamento);

$rsMetPagamento->bindParam(":id_encomenda", $id_encomenda, PDO::PARAM_INT);	

$rsMetPagamento->execute();

$row_rsMetPagamento = $rsMetPagamento->fetch(PDO::FETCH_ASSOC);

$totalRows_rsMetPagamento = $rsMetPagamento->rowCount();



$query_rsPaisEnvio = "SELECT p.nome FROM paises p, encomendas e WHERE e.id=:id_encomenda AND p.nome=e.pais_envio";

$rsPaisEnvio = DB::getInstance()->prepare($query_rsPaisEnvio);

$rsPaisEnvio->bindParam(":id_encomenda", $id_encomenda, PDO::PARAM_INT);	

$rsPaisEnvio->execute();

$row_rsPaisEnvio = $rsPaisEnvio->fetch(PDO::FETCH_ASSOC);

$totalRows_rsPaisEnvio = $rsPaisEnvio->rowCount();



$query_rsPaisFaturacao = "SELECT p.nome FROM paises p, encomendas e WHERE e.id=:id_encomenda AND p.nome=e.pais_fatura";

$rsPaisFaturacao = DB::getInstance()->prepare($query_rsPaisFaturacao);

$rsPaisFaturacao->bindParam(":id_encomenda", $id_encomenda, PDO::PARAM_INT);	

$rsPaisFaturacao->execute();

$row_rsPaisFaturacao = $rsPaisFaturacao->fetch(PDO::FETCH_ASSOC);

$totalRows_rsPaisFaturacao = $rsPaisFaturacao->rowCount();



//Marca a encomenda como vista

if($row_rsEncomenda['nova'] == 1) {

	$query_rsVista = "UPDATE encomendas SET nova=0 WHERE id=:id_encomenda";

	$rsVista = DB::getInstance()->prepare($query_rsVista);

	$rsVista->bindParam(":id_encomenda", $id_encomenda, PDO::PARAM_INT);

	$rsVista->execute();

}



$query_rsCarrinho2 = "SELECT * FROM encomendas_produtos WHERE id_encomenda=:id_encomenda ORDER BY cat_mea  ASC";

$rsCarrinho2 = DB::getInstance()->prepare($query_rsCarrinho2);

$rsCarrinho2->bindParam(":id_encomenda", $id, PDO::PARAM_INT);	

$rsCarrinho2->execute();

$row_rsCarrinho2 = $rsCarrinho2->fetchAll();

$totalRows_rsCarrinho2 = $rsCarrinho2->rowCount();



$query_rsCarrinho1 = "SELECT DISTINCT cat_mea,id FROM encomendas_produtos WHERE id_encomenda=:id_encomenda ORDER BY cat_mea  ASC";

$rsCarrinho1 = DB::getInstance()->prepare($query_rsCarrinho1);

$rsCarrinho1->bindParam(":id_encomenda", $id, PDO::PARAM_INT);	

$rsCarrinho1->execute();

$row_rsCarrinho1 = $rsCarrinho1->fetchAll();

$totalRows_rsCarrinho1 = $rsCarrinho1->rowCount();



$query_rsCarrinho = "SELECT * FROM encomendas_produtos WHERE id_encomenda=:id_encomenda";

$rsCarrinho = DB::getInstance()->prepare($query_rsCarrinho);

$rsCarrinho->bindParam(":id_encomenda", $id, PDO::PARAM_INT);	

$rsCarrinho->execute();

$totalRows_rsCarrinho = $rsCarrinho->rowCount();



$query_rsCarrinhoFinal4 = "SELECT * FROM encomendas_produtos WHERE id_encomenda=:id ORDER BY qtd ASC";

$rsCarrinhoFinal4 = DB::getInstance()->prepare($query_rsCarrinhoFinal4);

$rsCarrinhoFinal4->bindParam(':id', $id, PDO::PARAM_INT); 

$rsCarrinhoFinal4->execute();

$row_rsCarrinhoFinal4 = $rsCarrinhoFinal4->fetchAll(PDO::FETCH_ASSOC);

$totalRows_rsCarrinhoFinal4 = $rsCarrinhoFinal4->rowCount();



foreach ($row_rsCarrinhoFinal4 as  $value) {

	$getq_pro_id = $value['produto_id'];

	}



$query_rsProc = "SELECT * FROM l_pecas_en WHERE id=:id";

$rsProc = DB::getInstance()->prepare($query_rsProc);

$rsProc->bindParam(':id', $getq_pro_id, PDO::PARAM_INT);

$rsProc->execute();

$row_rsProc1 = $rsProc->fetch(PDO::FETCH_ASSOC);

$totalRows_rsProc1= $rsProc->rowCount();





$query_rsGrupos = "SELECT * FROM enc_estados ORDER BY ordem ASC";

$rsGrupos = DB::getInstance()->prepare($query_rsGrupos);

$rsGrupos->execute();

$totalRows_rsGrupos = $rsGrupos->rowCount();



$data = $row_rsEncomenda["data"];



$query_rsLastOrder = "SELECT e.*, es.nome_pt, es.id AS estado_id FROM encomendas e, enc_estados es WHERE e.id_cliente=:id_cliente AND e.estado = es.id AND e.id< :id ORDER BY e.id DESC";

$rsLastOrder = DB::getInstance()->prepare($query_rsLastOrder);

$rsLastOrder->bindParam(":id_cliente", $row_rsEncomenda["id_cliente"], PDO::PARAM_INT);

$rsLastOrder->bindParam(":id", $id, PDO::PARAM_INT);

$rsLastOrder->execute();

$row_rsLastOrder = $rsLastOrder->fetch(PDO::FETCH_ASSOC);

$totalRows_rsLastOrder = $rsLastOrder->rowCount();



$id_cliente = $row_rsEncomenda["id_cliente"];



$query_rsCliente = "SELECT * FROM clientes WHERE id=:id_cliente";

$rsCliente = DB::getInstance()->prepare($query_rsCliente);

$rsCliente->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

$rsCliente->execute();

$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

$totalRows_rsCliente = $rsCliente->rowCount();



$query_rsClientePais = "SELECT p.nome FROM clientes c, paises p WHERE c.id=:id_cliente AND c.pais=p.id";

$rsClientePais = DB::getInstance()->prepare($query_rsClientePais);

$rsClientePais->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

$rsClientePais->execute();

$row_rsClientePais = $rsClientePais->fetch(PDO::FETCH_ASSOC);

$totalRows_rsClientePais = $rsClientePais->rowCount();



//Calcular o total de encomendas

$query_rsTotalEnc = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND (estado=2 OR estado=3 OR estado=4 OR estado=6)";

$rsTotalEnc = DB::getInstance()->prepare($query_rsTotalEnc);

$rsTotalEnc->bindParam(":id", $id_cliente, PDO::PARAM_INT);

$rsTotalEnc->execute();

$row_rsTotalEnc = $rsTotalEnc->fetch(PDO::FETCH_ASSOC);

$totalRows_rsTotalEnc = $rsTotalEnc->rowCount();



//Calcular o total gasto

$query_rsEnc = "SELECT * FROM encomendas WHERE id_cliente=:id AND (estado=2 OR estado=3 OR estado=4 OR estado=6)";

$rsEnc = DB::getInstance()->prepare($query_rsEnc);

$rsEnc->bindParam(":id", $id_cliente, PDO::PARAM_INT);

$rsEnc->execute();

$totalRows_rsEnc = $rsEnc->rowCount();



$total_gasto = 0;



if($totalRows_rsEnc > 0) {

	while($row = $rsEnc->fetch()) {

	  if($row["moeda"] == "$") {

	    $convert = $row["valor_c_iva"] * $row["valor_conversao"];

	    $total_gasto+=$convert;

	  }

	  else if($row["moeda"] == "&pound;") {

	    $convert = $row["valor_c_iva"] * $row["valor_conversao"];

	    $total_gasto+=$convert;

	  }

	  else {

	    $total_gasto+=$row["valor_c_iva"];

	  }

	}

}



//Obter o histórico dos estados da encomenda

$query_rsEncHist = "SELECT * FROM enc_estados_historico WHERE id_encomenda=:id ORDER BY data DESC";

$rsEncHist = DB::getInstance()->prepare($query_rsEncHist);

$rsEncHist->bindParam(':id', $id, PDO::PARAM_INT);

$rsEncHist->execute();

$totalRows_rsEncHist = $rsEncHist->rowCount();



$query_rsPaises = "SELECT * FROM paises ORDER BY nome ASC";

$rsPaises = DB::getInstance()->prepare($query_rsPaises);

$rsPaises->execute();

$row_rsPaises = $rsPaises->fetchAll();

$totalRows_rsPaises = $rsPaises->rowCount();



DB::close();



?>

<?php include_once(ROOTPATH_ADMIN."inc_head_1.php"); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN."inc_head_2.php"); ?>

<style type="text/css">

	.encomenda tbody tr, .encomenda tbody td {

		font-size:14px;

	}

	#view-last-order {

		margin-left: 10px;

	}

	#view-client {

		margin-left: 10px;

	}

	#div-repor-stock {

		display: none;

	}

	#div-repor-stock label {

		padding-top: 0;

		margin-top: 0;

	}

	div.checker {

		margin-left: 0;

	}

	#uniform-finalizada {

		padding-top: 8px;

	}

	.printout {

    display: flex;

    flex-wrap: wrap;

    justify-content: flex-end;

    align-items: center;

    margin: 20px 30px 0 0;

}

</style>

<body class="<?php echo $body_info; ?>">

<?php include_once(ROOTPATH_ADMIN."inc_topo.php"); ?>

<div class="clearfix"> </div>

<!-- BEGIN CONTAINER -->

<div class="page-container">

  <?php include_once(ROOTPATH_ADMIN."inc_menu.php"); ?>

  <!-- BEGIN CONTENT -->

  <div class="page-content-wrapper">

    <div class="page-content"> 

      <!-- BEGIN PAGE HEADER-->

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['encomendas']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small></h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i></li>

          <li> <a href="encomendas.php"><?php echo $RecursosCons->RecursosCons['encomendas']; ?></a> <i class="fa fa-angle-right"></i></li>

          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-shopping-cart"></i><?php echo $RecursosCons->RecursosCons['encomenda_num']; ?><?php echo $row_rsEncomenda["numero"]; ?></div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='encomendas.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                </div>

              </div>

              <div class="portlet-body">

              <?php if($inserido == 1) { ?>

              	<div class="alert alert-success display-show">

                  <button class="close" data-close="alert"></button>

                  <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>

                </div>

            	<?php } ?>

							<div class="tabbable">

								<ul class="nav nav-tabs nav-tabs-lg">

									<li <?php if($tab_sel == 1) echo "class='active'"; ?>>

										<a href="#tab_1" data-toggle="tab">

										<?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a>

									</li>

									<li <?php if($tab_sel == 2) echo "class='active'"; ?>>

										<a href="#tab_2" data-toggle="tab">

										<?php echo $RecursosCons->RecursosCons['tab_cliente']; ?>

										</a>

									</li>

									<li <?php if($tab_sel == 4) echo "class='active'"; ?>>

										<a href="#tab_4" data-toggle="tab">

										<?php echo $RecursosCons->RecursosCons['tab_historico']; ?> </a>

									</li>

									<li onClick="window.location='observacoes.php?enc=<?php echo $id; ?>'">

										<a href="#tab_5" data-toggle="tab">

										<?php echo $RecursosCons->RecursosCons['tab_observacoes']; ?> </a>

									</li>                                   

								</ul>

								<div class="tab-content">

									<div class="tab-pane <?php if($tab_sel == 1) echo "active"; ?>" id="tab_1">

										<div class="row">

											<div class="col-md-6 col-sm-12">

												<div class="portlet blue-madison box">

													<div class="portlet-title">

														<div class="caption">

															<i class="fa fa-shopping-cart"></i><?php echo $RecursosCons->RecursosCons['detalhe_enc_label']; ?> - #<?php echo $row_rsEncomenda['numero']; ?>

														</div>

														<div class="actions">

															<a href="javascript:" id="edit-encomenda-bt" class="btn btn-default btn-sm">

															<i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

															<a style="padding-bottom: 4px" href="javascript:" id="cancelar-edit-encomenda" class="btn btn-default btn-sm">

															<i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </a>

															<button type="submit" id="save-encomenda" class="btn btn-default btn-sm" onClick="document.edit_encomenda.submit()">

															<i class="fa fa-floppy-o"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>

														</div>

													</div>

													<div class="portlet-body">

														<div id="encomenda">

															<div class="row static-info">

																<div class="col-md-4 name">

																	<?php echo $RecursosCons->RecursosCons['encomenda_num']; ?>:

																</div>

																<div class="col-md-8 value">

																	<?php echo $row_rsEncomenda["numero"]; ?> 

																</div>

															</div>

															<div class="row static-info">

																<div class="col-md-4 name">

																	<?php echo $RecursosCons->RecursosCons['data_enc_label']; ?>:

																</div>

																<div class="col-md-8 value">

																	<?php echo $row_rsEncomenda['data']; ?>

																</div>

															</div>

															<div class="row static-info">

																<div class="col-md-4 name">

																	<?php echo $RecursosCons->RecursosCons['estado_enc_label']; ?>:

																</div>

																<div class="col-md-8 value">

																	<span class="label <?php if($row_rsEstado['id'] == 1) { echo "label-info"; } else if($row_rsEstado['id'] == 2) { echo "label-primary"; } else if($row_rsEstado['id'] == 3 || $row_rsEstado['id'] == 4) { echo "label-success"; } else if($row_rsEstado['id'] == 6) { echo "label-warning"; } else if($row_rsEstado['id'] == 5) { echo "label-danger"; } ?>">

																	<?php echo $row_rsEstado['nome_pt']; ?> </span>

																</div>

															</div>

															<div class="row static-info">

																<div class="col-md-4 name">

																	<?php echo $RecursosCons->RecursosCons['total_enc_label']; ?>:

																</div>

																<div class="col-md-8 value">

																	<?php echo mostraPrecoEnc($id_encomenda, $row_rsEncomenda['valor_c_iva']); ?>

																</div>

															</div>

															<hr>

															<div class="row static-info">

																<div class="col-md-4 name">

																	<?php echo $RecursosCons->RecursosCons['metodo_pag_label']; ?>:

																</div>

																<div class="col-md-8 value">

																	<?php echo $row_rsEncomenda['pagamento']; ?>

																</div>

															</div>

															<?php if($row_rsEncomenda['entrega']) { ?>

																<hr>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['metodo_envio_label']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsEncomenda['entrega']; ?>

																	</div>

																</div>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['seguir_envio_link_label']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsEncomenda['envio_link']; ?>

																	</div>

																</div>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['seguir_envio_ref_label']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsEncomenda['envio_ref']; ?>

																	</div>

																</div>

															<?php } ?>

															<hr>

															<div class="row static-info">

																<div class="col-md-4 name">

																	<?php echo $RecursosCons->RecursosCons['observacoes']; ?>:

																</div>

																<div class="col-md-8 value">

																	<?php if($row_rsEncomenda['observacoes'] != '') echo $row_rsEncomenda['observacoes']; else echo "---"; ?>

																</div>

															</div>

														</div>

														<div id="encomenda-edit">

													 		<form id="edit_encomenda" name="edit_encomenda" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

					                      <div class="form-group">

				                          <label class="col-md-4 control-label" for="encomenda_select_estado"><?php echo $RecursosCons->RecursosCons['estado_enc_label']; ?>: <span class="required"> * </span> </label>

				                          <div class="col-md-7">

				                            <select name="encomenda_estado_select" id="encomenda_estado_select" class="form-control">

				                              <option value="" disabled="disabled"><?php echo $RecursosCons->RecursosCons['opt_estado']; ?></option>

				                              <?php if($totalRows_rsGrupos > 0) {

				                                while($row_rsGrupos = $rsGrupos->fetch()) { ?>

								                        	<option value="<?php echo $row_rsGrupos["id"]; ?>" <?php if($row_rsEncomenda["estado"] == $row_rsGrupos["id"]) { ?>selected<?php } ?>><?php echo $row_rsGrupos["nome_en"]; ?></option>

								                        <?php } ?>

				                              <?php } ?>

				                            </select>

				                          </div>

				                        </div>

				                        <div class="form-group">

				                        	<label class="col-md-4 control-label" for="enviar_email"><?php echo $RecursosCons->RecursosCons['enviar_email_cliente_label']; ?>: </label>

					                        <div style="padding-top: 8px" class="col-md-3">

					                        	<input type="checkbox" name="enviar_email" id="enviar_email" value="1" checked/>

					                        </div>

				                        </div>

				                        <?php if($row_rsEncomenda['entrega']) { ?>

					                        <div id="div-repor-stock" style="padding-bottom: 10px" class="row">

								                    <label class="col-md-4 control-label" style="text-align:right;"><?php echo $RecursosCons->RecursosCons['repor_stocks_label']; ?>:</label>

								                    <div class="col-md-3">

								                      <input type="checkbox" style="border:0; padding-top:9px;" name="repoe_stock" id="repoe_stock" value="1" <?php /* checked="checked" */ ?>/>

								                    </div>

								                  </div>

								                  <div class="form-group">

																		<label class="col-md-4 control-label" for="envio_link"><?php echo $RecursosCons->RecursosCons['seguir_envio_link_label']; ?>:  </label>

																		<div class="col-md-7">

																			<input class="form-control" type="text" name="envio_link" id="envio_link" value="<?php echo $row_rsEncomenda['envio_link']; ?>"/>

																		</div>

																	</div>

																	<div class="form-group">

																		<label class="col-md-4 control-label" for="envio_ref"><?php echo $RecursosCons->RecursosCons['seguir_envio_ref_label']; ?>:  </label>

																		<div class="col-md-7">

																			<input class="form-control" type="text" name="envio_ref" id="envio_ref" value="<?php echo $row_rsEncomenda['envio_ref']; ?>"/>

																		</div>

																	</div>

																	<div class="form-group">

																		<label class="col-md-4 control-label" for="envio_ref"><?php echo $RecursosCons->RecursosCons['Cancel_mes_text']; ?>:  </label>

																		<div class="col-md-7">

																			<textarea class="form-control" type="text" name="cancle_mes" id="cancle_mes" value="<?php echo $row_rsEncomenda['cancle_mes']; ?>"></textarea>

																		</div>

																	</div>

																<?php } ?>

			                       		<input type="hidden" name="MM_edit" value="edit_encomenda" />

															</form>	

			                      </div>

													</div>

												</div>

											</div>

											<div class="col-md-6 col-sm-12">

												<?php if($id_cliente > 0 && $totalRows_rsCliente > 0) { ?>

													<div class="row">

														<div class="portlet yellow box">

															<div class="portlet-title">

																<div class="caption">

																	<i class="fa fa-user"></i><?php echo $RecursosCons->RecursosCons['info_cli_label']; ?>

																</div>

																<div class="actions">

																	<a href="../clientes/clientes-edit.php?id=<?php echo $row_rsCliente['id']; ?>" class="btn btn-default btn-sm">

																	<i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

																</div>

															</div>

															<div class="portlet-body">

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['nome_label']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsCliente['nome']; ?>

																	</div>

																</div>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['cli_email']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsCliente['email']; ?>

																	</div>

																</div>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['cli_morada']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo nl2br($row_rsCliente['morada']); ?><br>

																		<?php echo $row_rsCliente['cod_postal']." ".$row_rsCliente['localidade']; ?><br>

																		<?php echo $row_rsClientePais['nome']; ?><br>

																	</div>

																</div>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['cli_telemovel']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsCliente['telemovel']; ?>

																	</div>

																</div>

																<div class="row static-info">

																	<div class="col-md-4 name">

																		<?php echo $RecursosCons->RecursosCons['cli_telefone']; ?>:

																	</div>

																	<div class="col-md-8 value">

																		<?php echo $row_rsCliente['telefone']; ?>

																	</div>

																</div>

															</div>

														</div>

													</div>

												<?php } ?>

												<div class="row">

													<div class="portlet green-seagreen box">

														<div class="portlet-title">

															<div class="caption">

																<i class="fa fa-home"></i><?php echo $RecursosCons->RecursosCons['ender_faturacao_label']; ?>

															</div>

															<div class="actions">

																<a href="javascript:" id="edit-morada-fatura" class="btn btn-default btn-sm">

																<i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

																<a style="padding-bottom: 4px" href="javascript:" id="cancelar-morada-fatura" class="btn btn-default btn-sm">

																<i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </a>

																<button type="submit" id="save-morada-fatura" class="btn btn-default btn-sm" onClick="document.edit_morada_fatura.submit()">

																<i class="fa fa-floppy-o"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>

															</div>

														</div>

														<div class="portlet-body">

															<div class="row static-info">

																<div class="col-md-12">

																	<div id="morada-fatura">

																 		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['nome_label']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsEncomenda['nome']; ?>

																			</div>

																		</div>

																		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['cli_email']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsEncomenda['email']; ?>

																			</div>

																		</div>

																		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['nif_encomenda_label']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsEncomenda['nif']; ?>

																			</div>

																		</div>

																		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['cli_morada']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsEncomenda['morada_fatura']; ?>

																			</div>

																		</div>

																		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['cli_cod_postal']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsEncomenda['codpostal_fatura']; ?>

																			</div>

																		</div>

																		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['cli_localidade']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsEncomenda['localidade_fatura']; ?>

																			</div>

																		</div>

																		<div class="row static-info">

																			<div class="col-md-4 name">

																				<?php echo $RecursosCons->RecursosCons['cli_pais']; ?>:

																			</div>

																			<div class="col-md-8 value">

																				<?php echo $row_rsPaisFaturacao['nome']; ?>

																			</div>

																		</div>

																 	</div>

																 	<div id="morada-fatura-edit">

																 		<form id="edit_morada_fatura" name="edit_morada_fatura" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

																		 	<div class="form-group">

								                        <label class="col-md-2 control-label" for="nome_fatura"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>

								                        <div class="col-md-8">

								                          <input type="text" class="form-control" id="nome_fatura" name="nome_fatura" value="<?php echo $row_rsEncomenda['nome']; ?>">

								                        </div>

								                      </div>

								                      <div class="form-group">

								                        <label class="col-md-2 control-label" for="email_fatura"><?php echo $RecursosCons->RecursosCons['cli_email']; ?>: <span class="required"> * </span> </label>

								                        <div class="col-md-8">

								                          <input type="text" class="form-control" id="email_fatura" name="email_fatura" value="<?php echo $row_rsEncomenda['email']; ?>">

								                        </div>

								                      </div>

								                      <div class="form-group">

								                        <label class="col-md-2 control-label" for="nif_fatura"><?php echo $RecursosCons->RecursosCons['nif_encomenda_label']; ?>: <span class="required"> * </span> </label>

								                        <div class="col-md-8">

								                          <input type="text" class="form-control" id="nif_fatura" name="nif_fatura" value="<?php echo $row_rsEncomenda['nif']; ?>">

								                        </div>

								                      </div>

																		 	<div class="form-group">

								                        <label class="col-md-2 control-label" for="morada_fatura"><?php echo $RecursosCons->RecursosCons['cli_morada']; ?>: <span class="required"> * </span> </label>

								                        <div class="col-md-8">

								                          <textarea class="form-control" rows="2" id="morada_fatura" name="morada_fatura"><?php echo $row_rsEncomenda['morada_fatura']; ?></textarea>

								                        </div>

								                      </div>

								                      <div class="form-group">

								                        <label class="col-md-2 control-label" for="codpostal_fatura"><?php echo $RecursosCons->RecursosCons['cli_cod_postal']; ?>: <span class="required"> * </span> </label>

								                        <div class="col-md-8">

								                          <input type="text" class="form-control" id="codpostal_fatura" name="codpostal_fatura" value="<?php echo $row_rsEncomenda['codpostal_fatura']; ?>">

								                        </div>

								                      </div>

								                      <div class="form-group">

								                        <label class="col-md-2 control-label" for="localidade_fatura"><?php echo $RecursosCons->RecursosCons['cli_localidade']; ?>: <span class="required"> * </span> </label>

								                        <div class="col-md-8">

								                          <input type="text" class="form-control" id="localidade_fatura" name="localidade_fatura" value="<?php echo $row_rsEncomenda['localidade_fatura']; ?>">

								                        </div>

								                      </div>

								                      <div class="form-group">

							                          <label class="col-md-2 control-label" for="pais_fatura_select"><?php echo $RecursosCons->RecursosCons['cli_pais']; ?>: <span class="required"> * </span> </label>

							                          <div class="col-md-8">

							                            <select name="pais_fatura_select" class="form-control">

							                              <option value="" disabled="disabled" selected><?php echo $RecursosCons->RecursosCons['opt_selec_pais']; ?></option>

							                              <?php if($totalRows_rsPaises > 0) {

							                                foreach($row_rsPaises as $row) { ?> 

							                                  <option value="<?php echo $row['nome']; ?>" <?php if($row['nome'] == $row_rsPaisFaturacao['nome']) echo 'selected'; ?>><?php echo $row['nome']; ?></option>

							                              	<?php } 

							                              } ?>

							                            </select>

							                          </div>

							                        </div>

						                       		<input type="hidden" name="MM_edit" value="edit_morada_fatura" />

																		</form>	

						                      </div>

																</div>

															</div>

														</div>

													</div>

												</div>

												<?php if($row_rsEncomenda['entrega']) { ?>

													<div class="row">

														<div class="portlet red-intense box">

															<div class="portlet-title">

																<div class="caption">

																	<i class="fa fa-home"></i><?php echo $RecursosCons->RecursosCons['ender_envio_label']; ?>

																</div>

																<div class="actions">

																	<a href="javascript:" id="edit-morada-envio" class="btn btn-default btn-sm">

																	<i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

																	<a style="padding-bottom: 4px" href="javascript:" id="cancelar-morada-envio" class="btn btn-default btn-sm">

																	<i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?> </a>

																	<button type="submit" id="save-morada-envio" class="btn btn-default btn-sm" onClick="document.edit_morada_envio.submit()">

																	<i class="fa fa-floppy-o"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>

																</div>

															</div>

															<div class="portlet-body">

																<div class="row static-info">

																	<div class="col-md-12">

																		<div id="morada-envio">

																			<div class="row static-info">

																				<div class="col-md-4 name">

																					<?php echo $RecursosCons->RecursosCons['nome_label']; ?>:

																				</div>

																				<div class="col-md-8 value">

																					<?php echo $row_rsEncomenda['nome_envio']; ?>

																				</div>

																			</div>

																			<div class="row static-info">

																				<div class="col-md-4 name">

																					<?php echo $RecursosCons->RecursosCons['cli_telemovel']; ?>:

																				</div>

																				<div class="col-md-8 value">

																					<?php echo $row_rsEncomenda['telemovel']; ?>

																				</div>

																			</div>

																			<div class="row static-info">

																				<div class="col-md-4 name">

																					<?php echo $RecursosCons->RecursosCons['cli_morada']; ?>:

																				</div>

																				<div class="col-md-8 value">

																					<?php echo $row_rsEncomenda['morada_envio']; ?>

																				</div>

																			</div>

																			<div class="row static-info">

																				<div class="col-md-4 name">

																					<?php echo $RecursosCons->RecursosCons['cli_cod_postal']; ?>:

																				</div>

																				<div class="col-md-8 value">

																					<?php echo $row_rsEncomenda['codpostal_envio']; ?>

																				</div>

																			</div>

																			<div class="row static-info">

																				<div class="col-md-4 name">

																					<?php echo $RecursosCons->RecursosCons['cli_localidade']; ?>:

																				</div>

																				<div class="col-md-8 value">

																					<?php echo $row_rsEncomenda['localidade_envio']; ?>

																				</div>

																			</div>

																			<div class="row static-info">

																				<div class="col-md-4 name">

																					<?php echo $RecursosCons->RecursosCons['cli_pais']; ?>:

																				</div>

																				<div class="col-md-8 value">

																					<?php echo $row_rsPaisEnvio['nome']; ?>

																				</div>

																			</div>

																		</div>

																		<div id="morada-envio-edit">

																	 		<form id="edit_morada_envio" name="edit_morada_envio" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

																			 	<div class="form-group">

									                        <label class="col-md-2 control-label" for="nome_envio"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>

									                        <div class="col-md-8">

									                          <input type="text" class="form-control" id="nome_envio" name="nome_envio" value="<?php echo $row_rsEncomenda['nome_envio']; ?>">

									                        </div>

									                      </div>

									                      <div class="form-group">

									                        <label class="col-md-2 control-label" for="telemovel_envio"><?php echo $RecursosCons->RecursosCons['cli_telemovel']; ?>: <span class="required"> * </span> </label>

									                        <div class="col-md-8">

									                          <input type="text" class="form-control" id="telemovel_envio" name="telemovel_envio" value="<?php echo $row_rsEncomenda['telemovel']; ?>">

									                        </div>

									                      </div>

																			 	<div class="form-group">

									                        <label class="col-md-2 control-label" for="morada_envio"><?php echo $RecursosCons->RecursosCons['cli_morada']; ?>: <span class="required"> * </span> </label>

									                        <div class="col-md-8">

									                          <textarea class="form-control" rows="2" id="morada_envio" name="morada_envio"><?php echo $row_rsEncomenda['morada_envio']; ?></textarea>

									                        </div>

									                      </div>

									                      <div class="form-group">

									                        <label class="col-md-2 control-label" for="codpostal_envio"><?php echo $RecursosCons->RecursosCons['cli_cod_postal']; ?>: <span class="required"> * </span> </label>

									                        <div class="col-md-8">

									                          <input type="text" class="form-control" id="codpostal_envio" name="codpostal_envio" value="<?php echo $row_rsEncomenda['codpostal_envio']; ?>">

									                        </div>

									                      </div>

									                      <div class="form-group">

									                        <label class="col-md-2 control-label" for="localidade_envio"><?php echo $RecursosCons->RecursosCons['cli_localidade']; ?>: <span class="required"> * </span> </label>

									                        <div class="col-md-8">

									                          <input type="text" class="form-control" id="localidade_envio" name="localidade_envio" value="<?php echo $row_rsEncomenda['localidade_envio']; ?>">

									                        </div>

									                      </div>

									                      <div class="form-group">

								                          <label class="col-md-2 control-label" for="pais_envio_select"><?php echo $RecursosCons->RecursosCons['cli_pais']; ?>: <span class="required"> * </span> </label>

								                          <div class="col-md-8">

								                            <select name="pais_envio_select" class="form-control">

								                              <option value="" disabled="disabled" selected><?php echo $RecursosCons->RecursosCons['opt_selec_pais']; ?></option>

								                              <?php if($totalRows_rsPaises > 0) {

								                              	foreach($row_rsPaises as $row) { ?> 

								                                  <option value="<?php echo $row['nome']; ?>" <?php if($row['nome'] == $row_rsPaisEnvio['nome']) echo 'selected'; ?>><?php echo $row['nome']; ?></option>

								                              	<?php } 

								                              } ?>

								                            </select>

								                          </div>

								                        </div>

							                       		<input type="hidden" name="MM_edit" value="edit_morada_envio" />

																			</form>	

							                      </div>

																	</div>

																</div>

															</div>

														</div>

													</div>

												<?php } ?>

											</div>

										</div>

										<div class="row">

											<div class="col-md-12 col-sm-12">

												<div class="portlet grey-cascade box">

													<div class="portlet-title">

														<div class="caption">

															<i class="fa fa-shopping-cart"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?>

														</div>

													</div>

													<div class="portlet-body">

														<div class="table-responsive">

															<table class="table table-hover table-bordered table-striped">

																<thead>

																	<tr>

																		<th width="20%">

																			<?php echo $RecursosCons->RecursosCons['designacao_prod_label']; ?>

																		</th>

																		<th width="10%">

																			<?php echo $RecursosCons->RecursosCons['preco_uni_prod_label']; ?> 

																		</th>

																		</th>

																			<th width="10%">

																			VAT

																		</th>

																		<th width="10%">

																			<?php echo $RecursosCons->RecursosCons['preco_uni_prod_label']; ?>(Incl. VAT)

																		

																		<th width="10%">

																			Quantity

																		</th>

																	

																		<th width="10%">

																			<?php echo $RecursosCons->RecursosCons['sub_total_prod_label']; ?> (Excl. VAT)

																		</th>

																	</tr>

																</thead>

																<tbody>

																	<?php while($row_rsCarrinho = $rsCarrinho->fetch()) { ?>

		                                <tr>

																			<td>

																				<?php if($row_rsCarrinho['ref'] != '') { ?><div style="font-size: 12px;"><?php echo $RecursosCons->RecursosCons['ref'].":<strong> ".$row_rsCarrinho['ref']."</strong>"; ?></div><?php } ?>

																				<strong><?php echo $row_rsCarrinho['produto']; ?></strong>

																				<?php if($row_rsCarrinho['opcoes'] != '') { ?><div style="font-size: 12px; margin-top: 5px;"><?php echo $row_rsCarrinho["opcoes"]; ?></div><?php } ?>

																			</td>

																			

																			<td>

																				<?php 



																				$reguler_Price = $row_rsCarrinho["preco"];

																				$vat = $row_rsCarrinho["iva"];

																				$vat_price =$reguler_Price - ($vat*($reguler_Price/100));



																				?>



																				<?php echo mostraPrecoEnc($id_encomenda, $vat_price); ?>

																			</td>

																			<td>

																				<?php if($row_rsCarrinho["iva"] > 0) { 

																					echo $row_rsCarrinho["iva"]."%"; 

																				} ?>

																			</td>

																			<td>

																				<?php $reguler_Price = $row_rsCarrinho["preco"];?>



																				<?php echo mostraPrecoEnc($id_encomenda, $reguler_Price); ?>

																			</td>



																			<td>

																				<?php echo $row_rsCarrinho["qtd"]; ?> 

																			</td>

																		

																			<td>

																				<?php echo mostraPrecoEnc($id_encomenda, $row_rsCarrinho["preco"] * $row_rsCarrinho["qtd"]); ?>

																			</td>

		                                </tr>

																	<?php } ?>

																</tbody>

															</table>

														</div>

													</div>

												</div>

											</div>

										</div>

										<div class="row">

											<div class="col-md-6">

												<?php  if ($row_rsEncomenda['met_pagamt_id'] == 6 || $row_rsEncomenda['met_pagamt_id'] == 7) {

													$ref_pag = $row_rsEncomenda['ref_pagamento'];

                    			$ref_pagamento = substr($ref_pag, 0, 3)." ".substr($ref_pag, 3, 3)." ".substr($ref_pag, 6, 3);

													?>

												  <table cellpadding="3" width="300px" cellspacing="0" style="border: 1px solid #292929">

												    <tr>

												      <td height="15" colspan="3" align="center" style="border-bottom: 1px solid #292929; background-color: #292929; color: #FFFFFF; font-size:11px"><?php echo $RecursosCons->RecursosCons['pagam_multibanco_homebanking']; ?></td>

												    </tr>

												    <tr>

												      <td width="130" height="100" align="center"><img src="../../../imgs/carrinho/multibanco.png" alt="" width="100" /></td>

												      <td colspan="2" style="font-size: 12px; text-align:left">

												      	<?php echo $RecursosCons->RecursosCons['entidade_ref'].": <strong>".$row_rsEncomenda['entidade']."</strong>"; ?><br>

		                            <?php echo $RecursosCons->RecursosCons['referencia_ref'].": <strong>".$ref_pagamento."</strong>"; ?><br>

		                            <?php echo $RecursosCons->RecursosCons['montante_ref'].": <strong>".number_format($row_rsEncomenda['valor_c_iva'], 2,',', ' ')." lb</strong>"; ?><br>

												      </td>

												    </tr>

												  </table>

												<?php } ?>

											</div>

											<div class="col-md-6">

												<div class="well">

													<div class="row static-info align-reverse">

														<div class="col-md-8 name">

															<?php echo $RecursosCons->RecursosCons['sub_total_prod_label']; ?> (Excl. VAT):

														</div>

														<div class="col-md-3 value">

																<?php foreach ($row_rsCarrinho2 as $row_rsCarrinho) {



																		$qtn = $row_rsCarrinho["qtd"];

																		$reguler_Price = $row_rsCarrinho["preco"] * $qtn;

																		$vat = $row_rsCarrinho["iva"];

																		

																	    $vat_price = $reguler_Price - ($vat*($reguler_Price/100));

																	    $total_price += $vat_price;

																	}

																 ?>



															<?php echo mostraPrecoEnc($id_encomenda, $total_price); ?>	

														</div>

													</div>



													<div class="row static-info align-reverse">

														<div class="col-md-8 name">

															VAT Price:

														</div>

														<div class="col-md-3 value">

																<?php foreach ($row_rsCarrinho2 as $row_rsCarrinho1) {

																		$reguler_Price1 = $row_rsCarrinho1["preco"] * $row_rsCarrinho1["qtd"];

																		$vat1 = $row_rsCarrinho1["iva"] / 100;			

																	    $vat_price1 = ($vat1 * $reguler_Price1);

																	    $total_vat += $vat_price1;

																	}

																 ?>



															<?php echo mostraPrecoEnc($id_encomenda, $total_vat); ?>	

														</div>

													</div>

													<div class="row static-info align-reverse">

														<div class="col-md-8 name">

															Subtotal (Incl. VAT):

														</div>

														<div class="col-md-3 value">

															<?php echo mostraPrecoEnc($id_encomenda, $row_rsEncomenda["valor_total"]+$row_rsEncomenda["compra_valor_saldo"]+$row_rsEncomenda["codigo_promocional_valor"]); ?>

														</div>

													</div>

													<?php

														$query_rsP = "SELECT * FROM pickup WHERE id='1'";

														$rsP = DB::getInstance()->prepare($query_rsP);

														$rsP->execute();

														$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

														$totalRows_rsP = $rsP->rowCount();

														$discount = $row_rsP["discount"] / 100;



														$reguler_total = $row_rsEncomenda["valor_c_iva"];



														$discount_total = $reguler_total - ($reguler_total * $discount);



														$dicsount_midd = $reguler_total -  $discount_total;

														?>

														<?php

														if($row_rsEncomenda["valor_c_iva"] > 50 &&  $row_rsEncomenda["pickup_data"] != "" && $row_rsEncomenda["deliverystatus"] == 0 && $rolecheck != "franchise")

														{

													?>

													<div class="row static-info align-reverse">

														<div class="col-md-8 name">

															Discount Apply (<?php echo $row_rsP["discount"]; ?> %):

														</div>

														<div class="col-md-3 value">												

															<?php  echo mostraPrecoEnc($id_encomenda,  $dicsount_midd); ?>

														</div>

													</div>

													<?php } ?>



													<div class="row static-info align-reverse">

														<div class="col-md-8 name">

															Grand Total:

														</div>

														<div class="col-md-3 value">

															<?php

															$query_rsP = "SELECT * FROM pickup WHERE id='1'";

															$rsP = DB::getInstance()->prepare($query_rsP);

															$rsP->execute();

															$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

															$totalRows_rsP = $rsP->rowCount();

															$discount = $row_rsP["discount"] / 100;



															 $discount_total = $row_rsEncomenda["valor_c_iva"];

															 $discountapply = $discount_total - ($discount_total * $discount); ?>



															<?php

															if($row_rsEncomenda["valor_c_iva"] > 50  &&  $row_rsEncomenda["pickup_data"] != "" && $row_rsEncomenda["deliverystatus"] == 0 && $rolecheck != "franchise")

															{

															 echo mostraPrecoEnc($id_encomenda,  $discountapply +$row_rsEncomenda["compra_valor_saldo"]+$row_rsEncomenda["codigo_promocional_valor"]);

															}

															else

															{

															 echo mostraPrecoEnc($id_encomenda,  $discount_total +$row_rsEncomenda["compra_valor_saldo"]+$row_rsEncomenda["codigo_promocional_valor"]); 

															}

															?>

														</div>

													</div>

													<?php if($row_rsEncomenda["compra_valor_saldo"] > 0) { ?>

														<div class="row static-info align-reverse">

															<div class="col-md-8 name">

																<?php echo $RecursosCons->RecursosCons['usar_saldo_label']; ?>:

															</div>

															<div class="col-md-3 value">

																<?php echo "- ".mostraPrecoEnc($id_encomenda, $row_rsEncomenda["compra_valor_saldo"]); ?>

															</div>

														</div>

													<?php } ?>

													<?php if($row_rsEncomenda["codigo_promocional_valor"] > 0) { ?>

														<div class="row static-info align-reverse">

															<div class="col-md-8 name">

																 <?php echo $RecursosCons->RecursosCons['cod_promocional']; ?> (<?php echo $row_rsEncomenda['codigo_promocional']; ?>):

															</div>

															<div class="col-md-3 value">

																 <?php echo "- (".$row_rsEncomenda['codigo_promocional_desconto'].") ".mostraPrecoEnc($id_encomenda, $row_rsEncomenda["codigo_promocional_valor"]); ?>

															</div>

														</div>

													<?php } ?>

													<?php if($row_rsEncomenda["portes_pagamento"] > 0) { ?>

														<div class="row static-info align-reverse">

															<div class="col-md-8 name">

																<?php echo $RecursosCons->RecursosCons['metodo_pag_label']; ?>:

															</div>

															<div class="col-md-3 value">

																<?php echo "+ ".mostraPrecoEnc($id_encomenda, $row_rsEncomenda["portes_pagamento"]); ?>

															</div>

														</div>

													<?php } ?>

													<?php if($row_rsEncomenda["portes_entrega"] > 0) { ?>

														<div class="row static-info align-reverse">

															<div class="col-md-8 name">

																<?php echo $RecursosCons->RecursosCons['portes_label']; ?>:

															</div>

															<div class="col-md-3 value">

																<?php echo "+ ".mostraPrecoEnc($id_encomenda, $row_rsEncomenda["portes_entrega"]); $portes=$row_rsEncomenda["portes_entrega"]+$row_rsEncomenda["portes_pagamento"]; ?>

															</div>

														</div>

													<?php } ?>

													<?php $total_final=$row_rsEncomenda["valor_c_iva"]+$row_rsEncomenda["compra_valor_saldo"]; if($total_final<0) $total_final=0; ?>

                          <?php if($row_rsEncomenda["opcao"] > 0) { ?>

														<div class="row static-info align-reverse">

															<div class="col-md-8 name">

																<?php echo $RecursosCons->RecursosCons['opcao_envio_enc_label']; ?>:

															</div> 

															<div class="col-md-3 value">

																<?php $valor_opcao=$row_rsEncomenda["opcao"]; echo mostraPrecoEnc($id_encomenda, $row_rsEncomenda["opcao"]); ?>

															</div>

														</div>

													<?php } ?>

													<?php if($row_rsEncomenda["compra_valor_saldo"] > 0) { 

														$compra_valor_saldo=$row_rsEncomenda["compra_valor_saldo"];

														$total_final=$total_final-$compra_valor_saldo; 

													} ?>

													<div class="row static-info align-reverse">

														<div class="col-md-8 name">

															<?php echo $RecursosCons->RecursosCons['total_pagar_label']; ?>:

														</div>

														<div class="col-md-3 value">



															<?php if($row_rsEncomenda["valor_c_iva"] > 50  &&  $row_rsEncomenda["pickup_data"] != "" && $row_rsEncomenda["deliverystatus"] == 0 && $rolecheck != "franchise")

															{ ?>

															<?php $totale= number_format(($discountapply),2,'.',''); ?>

                              								<?php echo mostraPrecoEnc($id_encomenda, $discountapply); ?>

                              								<?Php } else

                              								{

                              									$totale= number_format(($total_final),2,'.','');

                              									echo mostraPrecoEnc($id_encomenda, $total_final); 



                              								}?>

														</div>

													</div>

													<div class="row static-info align-reverse printout">

														<div class="col-md-3 value">

														<a href="imprime_encomenda.php?encomenda=<?php echo $row_rsEncomenda['id']; ?>" style="text-decoration:none" target="_blank"> <img style="margin-right: 10px" src="../../../imgs/elem/mail.gif" alt="imprimir" width="32" height="32" border="0" /> <br />

                              <strong class="link_linha"><?php echo $RecursosCons->RecursosCons['imprimir_label']; ?></strong></a>

														</div>

														<div class="col-md-3 value">

														<a href="packing-slip.php?encomenda=<?php echo $row_rsEncomenda['id'];?>" style="text-decoration:none" target="_blank"> <img style="margin-right: 10px" src="../../../imgs/elem/mail.gif" alt="imprimir" width="32" height="32" border="0" /> <br />

                              <strong class="link_linha">Packing Slip</strong></a>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="tab-pane <?php if($tab_sel == 2) echo "active"; ?>" id="tab_2">

			              <div class="row">

											<div class="col-md-6 col-sm-12">

												<div class="portlet blue-madison box">

													<div class="portlet-title">

														<div class="caption">

															<i class="fa fa-cogs"></i><?php echo $RecursosCons->RecursosCons['detalhes_ultima_enc_label']; ?>

														</div>

														<div class="actions">

															<a href="<?php if($row_rsLastOrder["id"]) { echo "encomendas-edit.php?id=".$row_rsLastOrder["id"]; } else { echo "javascript:"; } ?>" class="btn btn-default btn-sm">

															<i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

														</div>

													</div>

													<div class="portlet-body">

														<div class="row static-info">

															<div class="col-md-5 name">

																<?php echo $RecursosCons->RecursosCons['encomenda_num']; ?>:

															</div>

															<div class="col-md-7 value">

																<?php if($row_rsLastOrder["id"]) { echo $row_rsLastOrder["numero"]; } else { ?> <?php echo $RecursosCons->RecursosCons['sem_dados_label']; ?> <?php } ?>

															</div>

														</div>

														<div class="row static-info">

															<div class="col-md-5 name">

																<?php echo $RecursosCons->RecursosCons['data_enc_label']; ?>:

															</div>

															<div class="col-md-7 value">

																<?php if($row_rsLastOrder["id"]) { echo nl2br($row_rsLastOrder["data"]); } else { echo "---"; } ?>

															</div>

														</div>

														<div class="row static-info">

															<div class="col-md-5 name">

																<?php echo $RecursosCons->RecursosCons['estado_enc_label']; ?>:

															</div>

															<div class="col-md-7 value">

																<?php if($row_rsLastOrder["id"]) { ?> <span class="label <?php if($row_rsLastOrder["estado_id"] == 1) { echo "label-info"; } else if($row_rsLastOrder["estado_id"] == 2) { echo "label-primary"; } else if($row_rsLastOrder["estado_id"] == 3 || $row_rsLastOrder["estado_id"] == 4) { echo "label-success"; } else if($row_rsLastOrder["estado_id"] == 6) { echo "label-warning"; } else if($row_rsLastOrder["estado_id"] == 5) { echo "label-danger"; } ?>"><?php echo nl2br($row_rsLastOrder["nome_pt"]); ?></span><?php } else { echo "---"; } ?>

															</div>

														</div>

														<div class="row static-info">

															<div class="col-md-5 name">

																<?php echo $RecursosCons->RecursosCons['total_enc_label']; ?>:

															</div>

															<div class="col-md-7 value">

																<?php echo mostraPrecoEnc($row_rsLastOrder["id"], $row_rsLastOrder["valor_c_iva"]); ?>

															</div>

														</div>

													</div>

												</div>

											</div>

											<div class="col-md-6 col-sm-12">

												<div class="portlet yellow box">

													<div class="portlet-title">

														<div class="caption">

															<i class="fa fa-cogs"></i><?php echo $RecursosCons->RecursosCons['estatisticas_cli_label']; ?>

														</div>

														<div class="actions">

															<a href="../clientes/clientes-edit.php?id=<?php echo $row_rsCliente["id"]; ?>" class="btn btn-default btn-sm">

															<i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

														</div>

													</div>

													<div class="portlet-body">

														<div class="row static-info">

															<div class="col-md-4 name">

																<?php echo $RecursosCons->RecursosCons['nome_label']; ?>:

															</div>

															<div class="col-md-8 value">

																<?php echo $row_rsCliente["nome"]; ?>

															</div>

														</div>

														<div class="row static-info">

															<div class="col-md-4 name">

																<?php echo $RecursosCons->RecursosCons['data_registo']; ?>: 

															</div>

															<div class="col-md-8 value">

																<?php echo nl2br($row_rsCliente["data_registo"]); ?>

															</div>

														</div>

														<div class="row static-info">

															<div class="col-md-4 name">

																<?php echo $RecursosCons->RecursosCons['cli_total_encomendas']; ?>: 

															</div>

															<div class="col-md-8 value">

																<?php echo nl2br($row_rsTotalEnc["total"]); ?>

															</div>

														</div>

														<div class="row static-info">

															<div class="col-md-4 name">

																<?php echo $RecursosCons->RecursosCons['total_gasto_enc']; ?>: 

															</div>

															<div class="col-md-8 value">

																<?php echo number_format($total_gasto, 2, ',', '.')."&pound;"; ?>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="tab-pane <?php if($tab_sel == 4) echo "active"; ?>" id="tab_4">

										<div class="table-container">

											<table class="table table-striped table-bordered table-hover" id="datatable_history">

											<thead>

													<tr role="row" class="heading">

														<th width="25%">

															<?php echo $RecursosCons->RecursosCons['data']; ?>

														</th>

														<th width="55%">

															<?php echo $RecursosCons->RecursosCons['descricao']; ?>

														</th>

														<th width="10%">

															<?php echo $RecursosCons->RecursosCons['notificacao']; ?>

														</th>

													</tr>

												</thead>

												<tbody>

													<?php if($totalRows_rsEncHist > 0) {

														while($row = $rsEncHist->fetch()) {

															$query_rsEstadoHist = "SELECT nome_pt FROM enc_estados WHERE id=:id";

															$rsEstadoHist = DB::getInstance()->prepare($query_rsEstadoHist);

															$rsEstadoHist->bindParam(':id', $row['estado'], PDO::PARAM_INT);

															$rsEstadoHist->execute();

															$row_rsEstadoHist = $rsEstadoHist->fetch(PDO::FETCH_ASSOC);

															$totalRows_rsEstadoHist = $rsEstadoHist->rowCount();

															DB::close(); 



															$automatico = '';

															if($row['automatico'] == 1) {

																$automatico = '<span class="label label-danger label-sm">'.$RecursosCons->RecursosCons['anulada_auto'].'</label>';

															}

															?>

															<tr>

																<td>

																	<?php echo $row['data']; ?>

																</td>

																<td>

																	<?php echo $RecursosCons->RecursosCons['encomenda_estado_msg']; ?>: <strong><?php echo $row_rsEstadoHist['nome_pt']; ?></strong> <?php echo $automatico; ?>

																</td>

																<td>

																	<?php if($row['notificado'] == 1) { ?>

																		<span class="label label-success label-sm">

																			<?php echo $RecursosCons->RecursosCons['text_visivel_sim']; ?>

																		</span>

																	<?php } else { ?>

																		<span class="label label-danger label-sm">

																			<?php echo $RecursosCons->RecursosCons['text_visivel_nao']; ?>

																		</span>

																	<?php } ?>

																</td>

															</tr>

														<?php	}

													} ?>

													<tr>

														<td>

															<?php echo $row_rsEncomenda["data"]; ?>

														</td>

														<td>

															<?php echo $RecursosCons->RecursosCons['enc_criada_txt']; ?>

														</td>

														<td></td>

													</tr>

												</tbody>

											</table>

										</div>

									</div>

								</div>

							</div>

						</div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- END CONTENT -->

  <?php include_once(ROOTPATH_ADMIN."inc_quick_sidebar.php"); ?>

</div>

<!-- END CONTAINER -->

<?php include_once(ROOTPATH_ADMIN."inc_footer_1.php"); ?>

<!-- BEGIN PAGE LEVEL PLUGINS --> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN."inc_footer_2.php"); ?>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 

<script src="encomendas-edit.js" type="text/javascript"></script>

<script>

jQuery(document).ready(function() {

	$("#encomenda_estado_select").on("change", function (e) {

    var optionSelected = $("option:selected", this);

    var valueSelected = this.value;



    if(valueSelected == 5) {

    	$("#div-repor-stock").css("display", "block");

    }

    else {

    	$("#div-repor-stock").css("display", "none");

    }

	});

});

</script>

<script type="text/javascript">

jQuery(document).ready(function() {    

	Metronic.init(); // init metronic core components

	Layout.init(); // init current layout

	QuickSidebar.init(); // init quick sidebar

	Demo.init(); // init demo features

});

</script>

<?php if(in_array($_SERVER["HTTP_HOST"], $array_servidor) && !strstr($_SERVER["REQUEST_URI"],"/proposta/")) { ?> 

	<?php if($estado_muda == 1 && $estado == 5) { ?>

		<!-- <script type="text/javascript">

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

			$query_rsCarrinhoFinal = "SELECT * FROM encomendas_produtos WHERE id_encomenda='$id_encomenda' ORDER BY id ASC";

			$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);

			$rsCarrinhoFinal->execute();

			$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();

			DB::close();



		  while($row_rsCarrinhoFinal = $rsCarrinhoFinal->fetch()) {

				if($row_rsCarrinhoFinal["cheque_prenda"] == 1) {

					$ref = NOME_SITE;

					$row_rsCategoria["nome"] = "Cheque Prenda";			

				}

				else {

					$ref = $row_rsCarrinhoFinal['ref'];

					if(!$ref) $ref = $row_rsCarrinhoFinal['produto_id'];

					if($row_rsCarrinhoFinal['opcoes']) {

						$ref .= "_".$row_rsCarrinhoFinal["id"];

					}



					$id_p = $row_rsCarrinhoFinal["produto_id"];

					

					$query_rsPeca = "SELECT categoria FROM  l_pecas".$extensao." WHERE id=:id_p";

					$rsPeca = DB::getInstance()->prepare($query_rsPeca);

					$rsPeca->bindParam(':id_p', $id_p, PDO::PARAM_INT);

					$rsPeca->execute();

					$row_rsPeca = $rsPeca->fetch(PDO::FETCH_ASSOC);

					

					$query_rsCategoria = "SELECT nome FROM l_categorias".$extensao." WHERE id=:id_categoria";

					$rsCategoria = DB::getInstance()->prepare($query_rsCategoria);

					$rsCategoria->bindParam(':id_categoria', $row_rsPeca['categoria'], PDO::PARAM_INT);

					$rsCategoria->execute();

					$row_rsCategoria = $rsCategoria->fetch(PDO::FETCH_ASSOC);

					DB::close();

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

		</script> --> 

	<?php } ?>

<?php } ?>

</body>

<!-- END BODY -->

</html>

