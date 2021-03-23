<?php

class Carrinho {

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



	//Ao fazer login, temos de atualizar o ID do cliente no carrinho (caso tenha adicionado produtos sem login)

	public static function carregaCarrinhoLogin($id_cliente) {

		global $cookie_secure;



		//Se já existir sessão criada, atualizar o ID do cliente

		if(isset($_COOKIE[CARRINHO_SESSION])) {

			$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		

			$insertSQL = "UPDATE carrinho SET id_cliente = :id_cliente WHERE session = :session";

			$Result1 = DB::getInstance()->prepare($insertSQL);

			$Result1->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

			$Result1->bindParam(':session', $carrinho_session, PDO::PARAM_STR, 5);

			$Result1->execute();

		}



		//Obter a última sessão do cliente

		$query_rsCarrinho = "SELECT session FROM carrinho WHERE id_cliente = :user ORDER BY session DESC LIMIT 1";

		$rsCarrinho = DB::getInstance()->prepare($query_rsCarrinho);

		$rsCarrinho->bindParam(':user', $id_cliente, PDO::PARAM_INT);	

		$rsCarrinho->execute();

		$row_rsCarrinho = $rsCarrinho->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsCarrinho = $rsCarrinho->rowCount();



		if($totalRows_rsCarrinho > 0) {

			$session = $row_rsCarrinho['session'];



			//Apagar as sessões mais antigas deste cliente

			$deleteSQL = "DELETE FROM carrinho WHERE session != :session";

			$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

			$rsDeleteSQL->bindParam(':session', $session, PDO::PARAM_INT);

			$rsDeleteSQL->execute();

			

			$deleteSQL  = "DELETE FROM carrinho_comprar WHERE session != :session";

			$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

			$rsDeleteSQL->bindParam(':session', $session, PDO::PARAM_INT);

			$rsDeleteSQL->execute();

			

			if(CARRINHO_CODIGOS == 1) {

				$deleteSQL  = "DELETE FROM carrinho_cod_prom WHERE session != :session";		

				$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

				$rsDeleteSQL->bindParam(':session', $session, PDO::PARAM_INT);

				$rsDeleteSQL->execute(); 

			}



			$timeout = 3600 * 24 * 5; //5 dias

			setcookie(CARRINHO_SESSION, $session, time() + $timeout, "/", "", $cookie_secure, true);



			$query_rsGuardado = "SELECT id FROM carrinho_cliente WHERE id_cliente = :user";

			$rsGuardado = DB::getInstance()->prepare($query_rsGuardado);

			$rsGuardado->bindParam(':user', $id_cliente, PDO::PARAM_INT);	

			$rsGuardado->execute();

			$row_rsGuardado = $rsGuardado->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsGuardado = $rsGuardado->rowCount();

			

			$data = date('Y-m-d H:i:s');



			if($totalRows_rsGuardado > 0) {						

				$query_insertSQL = "UPDATE carrinho_cliente SET data = :data, enviado = 0 WHERE id_cliente = :user";

				$insertSQL = DB::getInstance()->prepare($query_insertSQL);

				$insertSQL->bindParam(':user', $id_cliente, PDO::PARAM_INT);

				$insertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);

				$insertSQL->execute();	

			} 

			else {

				$query_insertSQL = "INSERT INTO carrinho_cliente (id_cliente, data, enviado) VALUES (:user, :data, 0)";

				$insertSQL = DB::getInstance()->prepare($query_insertSQL);

				$insertSQL->bindParam(':user', $id_cliente, PDO::PARAM_INT);

				$insertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);

				$insertSQL->execute();	

			}

		}



		DB::close();

	}

	

	//Se existir login, temos de apagar tudo o que seja do cliente mas de uma session antiga e atualizar o ID Cliente da nova session

	public static function atualizaCarrinho($id_cliente) {

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];



		$query_rsSesOld = "SELECT session FROM carrinho WHERE id_cliente = :user AND session != :session";

		$rsSesOld = DB::getInstance()->prepare($query_rsSesOld);

		$rsSesOld->bindParam(':user', $id_cliente, PDO::PARAM_INT);	

		$rsSesOld->bindParam(':session', $carrinho_session, PDO::PARAM_INT);

		$rsSesOld->execute();

		$row_rsSesOld = $rsSesOld->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsSesOld = $rsSesOld->rowCount();



		if($totalRows_rsSesOld > 0) {

			$ses_old = $row_rsSesOld['session'];



			$deleteSQL = "DELETE FROM carrinho where session = :session";

			$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

			$rsDeleteSQL->bindParam(':session', $ses_old, PDO::PARAM_INT);

			$rsDeleteSQL->execute();

			

			$deleteSQL  = "DELETE FROM carrinho_comprar where session = :session";

			$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

			$rsDeleteSQL->bindParam(':session', $ses_old, PDO::PARAM_INT);

			$rsDeleteSQL->execute();

			

			if(CARRINHO_CODIGOS == 1) {

				$deleteSQL  = "DELETE FROM carrinho_cod_prom where session = :session";		

				$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

				$rsDeleteSQL->bindParam(':session', $ses_old, PDO::PARAM_INT);

				$rsDeleteSQL->execute(); 

			}

		}



		$insertSQL = "UPDATE carrinho SET id_cliente = :user WHERE session = :session";

		$Result1 = DB::getInstance()->prepare($insertSQL);

		$Result1->bindParam(':user', $id_cliente, PDO::PARAM_INT);

		$Result1->bindParam(':session', $carrinho_session, PDO::PARAM_INT);

		$Result1->execute();



		$query_rsGuardado = "SELECT id FROM carrinho_cliente WHERE id_cliente = :user";

		$rsGuardado = DB::getInstance()->prepare($query_rsGuardado);

		$rsGuardado->bindParam(':user', $id_cliente, PDO::PARAM_INT);	

		$rsGuardado->execute();

		$row_rsGuardado = $rsGuardado->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsGuardado = $rsGuardado->rowCount();

		

		$data = date('Y-m-d H:i:s');



		if($totalRows_rsGuardado > 0) {						

			$query_insertSQL = "UPDATE carrinho_cliente SET data = :data, enviado = 0 WHERE id_cliente = :user";

			$insertSQL = DB::getInstance()->prepare($query_insertSQL);

			$insertSQL->bindParam(':user', $id_cliente, PDO::PARAM_INT);

			$insertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);

			$insertSQL->execute();	

		} 

		else {

			$query_insertSQL = "INSERT INTO carrinho_cliente (id_cliente, data, enviado) VALUES (:user, :data, 0)";

			$insertSQL = DB::getInstance()->prepare($query_insertSQL);

			$insertSQL->bindParam(':user', $id_cliente, PDO::PARAM_INT);

			$insertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);

			$insertSQL->execute();	

		}



		DB::close();

	}

	

	//Verifica se no carrinho existe só cheque prenda ou também se existem outros produtos

	public static function verificaCarrinho() {

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];



		$query_rsCar = "SELECT COUNT(id) as total FROM carrinho WHERE session='$carrinho_session' AND cheque_prenda = 0";

		$rsCar = DB::getInstance()->prepare($query_rsCar);

		$rsCar->execute();

		$totalRows_rsCar = $rsCar->rowCount();

		$row_rsCar = $rsCar->fetch(PDO::FETCH_ASSOC);

		DB::close();



		if($row_rsCar['total'] > 0) {

			return 1;

		}

		else {

			return 0;

		}

	}



	//Verifica se na encomenda existe só cheque prenda ou também se existem outros produtos

	public static function verificaEncomenda($id) {

		$query_rsEnc = "SELECT COUNT(id) as total FROM encomendas_produtos WHERE id_encomenda = '$id' AND cheque_prenda = 0";

		$rsEnc = DB::getInstance()->prepare($query_rsEnc);

		$rsEnc->execute();

		$totalRows_rsEnc = $rsEnc->rowCount();

		$row_rsEnc = $rsEnc->fetch(PDO::FETCH_ASSOC);

		DB::close();



		if($row_rsEnc['total'] > 0) {

			return 1;

		}

		else {

			return 0;

		}

	}



	//Calcula o total de peso dos produtos do carrinho

	public static function totalPeso() {

		global $extensao, $Recursos, $carrinho_session, $moeda, $moeda_simbol;



		//calcula o peso da mercadoria - pode estar em 2 tabelas

		$query_rsPeso = "SELECT l_pecas".$extensao.".*, carrinho.op1, carrinho.op2, carrinho.op3, carrinho.op4, carrinho.op5, carrinho.quantidade FROM l_pecas".$extensao.", carrinho WHERE l_pecas".$extensao.".id=carrinho.produto AND carrinho.session='$carrinho_session'";

		$rsPeso = DB::getInstance()->prepare($query_rsPeso);

		$rsPeso->execute();

		$totalRows_rsPeso = $rsPeso->rowCount();

		

		$total_peso = 0;

		$total_volume = 0;



		if($totalRows_rsPeso > 0) {

			while($row_rsPeso = $rsPeso->fetch()) {

				$produto = $row_rsPeso['id'];

				

				if($row_rsPeso['op1'] > 0) $tam1 = $row_rsPeso['op1']; else $tam1 = 0;

				if($row_rsPeso['op2'] > 0) $tam2 = $row_rsPeso['op2']; else $tam2 = 0;

				if($row_rsPeso['op3'] > 0) $tam3 = $row_rsPeso['op3']; else $tam3 = 0;

				if($row_rsPeso['op4'] > 0) $tam4 = $row_rsPeso['op4']; else $tam4 = 0;

				if($row_rsPeso['op5'] > 0) $tam5 = $row_rsPeso['op5']; else $tam5 = 0;

				

				$totalRows_rsT = 0;



				if(CARRINHO_TAMANHOS == 1) {

					$query_rsT = "SELECT peso, volume FROM l_pecas_tamanhos WHERE l_pecas_tamanhos.peca='$produto' AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.op3='$tam3' AND l_pecas_tamanhos.op4='$tam4' AND l_pecas_tamanhos.op5='$tam5'";

					$rsT = DB::getInstance()->prepare($query_rsT);

					$rsT->execute();

					$row_rsT = $rsT->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsT = $rsT->rowCount();

				}

				

				if($totalRows_rsT > 0) {

					$peso_produto = $row_rsT['peso'];

					

					if(!($peso_produto > 0)) {

						$peso_produto = $row_rsPeso['peso'];

					}

					

					$volume_produto = $row_rsT['volume'];

					

					if(!($volume_produto > 0)) {

						$volume_produto = $row_rsPeso['volume'];

					}

				}

				else {

					$peso_produto = $row_rsPeso['peso'];

					$volume_produto = $row_rsPeso['volume'];

				}

				

				$total_peso = $total_peso + ($peso_produto * $row_rsPeso['quantidade']);

				$total_volume = $total_volume + ($volume_produto * $row_rsPeso['quantidade']);

			}

		}



		//VERIFICA QUAL O PESO PARA O CÁLCULO

		//cálculo para fazer a conversão do volume para peso



		/*$total_volume=$total_volume*250;

		if($total_volume>$total_peso) $total_peso=$total_volume;*/



		DB::close();



		return $total_peso;

	}

	

	//Atualiza o carrinho ao carregar a página

	public static function carrinhoLoad() {		

		$row_rsCliente = User::getInstance()->isLogged();



		if($row_rsCliente > 0) {

			$id_cliente = $row_rsCliente["id"];

			  			

			if(isset($_COOKIE[CARRINHO_SESSION])) {

				$carrinho_session = $_COOKIE[CARRINHO_SESSION];

			

				$insertSQL = "UPDATE carrinho SET id_cliente = $row_rsCliente[id] WHERE session = '$carrinho_session'";

				$Result1 = DB::getInstance()->prepare($insertSQL);

				$Result1->execute();

				

				// Verifica se existem produtos do cliente no carrinho com sessão diferente e atribui-lhe a nova sessão

				// Se tiver produtos repetidos adiciona à quantidade

				$query_rsGuardado = "SELECT id_linha, produto, op1, op2, op3, op4, op5 FROM carrinho WHERE id_cliente=:user AND session != '$carrinho_session'";

				$rsGuardado = DB::getInstance()->prepare($query_rsGuardado);

				$rsGuardado->bindParam(':user', $row_rsCliente["id"], PDO::PARAM_INT);	

				$rsGuardado->execute();

				$row_rsGuardado = $rsGuardado->fetchAll();

				$totalRows_rsGuardado = $rsGuardado->rowCount();

				

				if($totalRows_rsGuardado > 0) {

					foreach($row_rsGuardado as $guardado) {

						$query_rsActual = "SELECT id_linha, quantidade FROM carrinho WHERE session = '$carrinho_session' AND produto = :produto AND op1 = :op1 AND op2 = :op2 AND op3 = :op3 AND op4 = :op4 AND op5 = :op5";

						$rsActual = DB::getInstance()->prepare($query_rsActual);

						$rsActual->bindParam(':produto', $guardado["produto"], PDO::PARAM_INT);	

						$rsActual->bindParam(':op1', $guardado["op1"], PDO::PARAM_INT);	

						$rsActual->bindParam(':op2', $guardado["op2"], PDO::PARAM_INT);	

						$rsActual->bindParam(':op3', $guardado["op3"], PDO::PARAM_INT);	

						$rsActual->bindParam(':op4', $guardado["op4"], PDO::PARAM_INT);	

						$rsActual->bindParam(':op5', $guardado["op5"], PDO::PARAM_INT);	

						$rsActual->execute();

						$row_rsActual = $rsActual->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsActual = $rsActual->rowCount();

						

						//Atualizar quantidades das ofertas (se existirem)

						if($totalRows_rsActual > 0) {

							$insertSQL = "UPDATE carrinho SET quantidade = quantidade + '".$row_rsActual["quantidade"]."' WHERE id_linha = '".$guardado["id_linha"]."'";

							$Result1 = DB::getInstance()->prepare($insertSQL);

							$Result1->execute();

							

							$insertSQL = "DELETE FROM carrinho WHERE id_linha = '".$row_rsActual["id_linha"]."'";

							$Result1 = DB::getInstance()->prepare($insertSQL);

							$Result1->execute();

						}

					}

					

					$insertSQL = "UPDATE carrinho SET session = '$carrinho_session' WHERE session != '$carrinho_session' AND id_cliente = '".$row_rsCliente["id"]."'";

					$Result1 = DB::getInstance()->prepare($insertSQL);

					$Result1->execute();

				}

				

				// Verifica se existem produtos repetidos e incrementa a quantidade

				$query_rsCarrinhoActual = "SELECT id_linha, produto, op1, op2, op3, op4, op5 FROM carrinho WHERE session = '$carrinho_session' GROUP BY produto";

				$rsCarrinhoActual = DB::getInstance()->prepare($query_rsCarrinhoActual);

				$rsCarrinhoActual->bindParam(':user', $row_rsCliente["id"], PDO::PARAM_INT);	

				$rsCarrinhoActual->execute();

				$row_rsCarrinhoActual = $rsCarrinhoActual->fetchAll();

				$totalRows_rsCarrinhoActual = $rsCarrinhoActual->rowCount();

				

				if($totalRows_rsCarrinhoActual > 0) {

					foreach($row_rsCarrinhoActual as $actual) {

						$query_rsExiste = "SELECT quantidade, id_linha FROM carrinho WHERE session = '$carrinho_session' AND produto = :produto AND op1 = :op1 AND op2 = :op2 AND op3 = :op3 AND op4 = :op4 AND op5 = :op5 AND id_linha != :id_linha";

						$rsExiste = DB::getInstance()->prepare($query_rsExiste);

						$rsExiste->bindParam(':produto', $actual["produto"], PDO::PARAM_INT);

						$rsExiste->bindParam(':op1', $actual["op1"], PDO::PARAM_INT);

						$rsExiste->bindParam(':op2', $actual["op2"], PDO::PARAM_INT);

						$rsExiste->bindParam(':op3', $actual["op3"], PDO::PARAM_INT);

						$rsExiste->bindParam(':op4', $actual["op4"], PDO::PARAM_INT);

						$rsExiste->bindParam(':op5', $actual["op5"], PDO::PARAM_INT);

						$rsExiste->bindParam(':id_linha', $actual["id_linha"], PDO::PARAM_INT);

						$rsExiste->execute();

						$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsExiste = $rsExiste->rowCount();

						

						//Atualizar quantidades das ofertas (se existirem)

						if($totalRows_rsExiste > 0) {						

							$insertSQL = "UPDATE carrinho SET quantidade = quantidade + '".$row_rsExiste["quantidade"]."' WHERE id_linha = '".$actual["id_linha"]."'";

							$Result1 = DB::getInstance()->prepare($insertSQL);

							$Result1->execute();



							$insertSQL = "DELETE FROM carrinho WHERE id_linha = '".$row_rsExiste["id_linha"]."'";

							$Result1 = DB::getInstance()->prepare($insertSQL);

							$Result1->execute();

						}

					}

				}



				DB::close();

			}

		}

	}

	

	public static function mudaMoeda($currency) {		

		global $cookie_secure;

		

		$query_rsMoeda = "SELECT simbolo FROM moedas WHERE abreviatura=:abreviatura AND visivel=1";

		$rsMoeda = DB::getInstance()->prepare($query_rsMoeda);

		$rsMoeda->bindParam(':abreviatura', $currency, PDO::PARAM_STR, 5);

		$rsMoeda->execute();

		$row_rsMoeda = $rsMoeda->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsMoeda = $rsMoeda->rowCount();

		DB::close();



		// Estrutura do cookie: moeda-simbolo

		if($totalRows_rsMoeda > 0) {

			setcookie("SITE_currency", $currency."-".$row_rsMoeda['simbolo'], time()+3600*24*30*12*5, '/', '', $cookie_secure, true);

			$_COOKIE['SITE_currency'] = $currency."-".$row_rsMoeda['simbolo'];

		}

		else {

			setcookie("SITE_currency", "lb-&pound;", time()+3600*24*30*12*5, '/', '', $cookie_secure, true);

			$_COOKIE['SITE_currency'] = "lb-&pound;";

		}

	}

	

	public static function getMoeda($tipo = 0) {		

		if(!$_COOKIE["SITE_currency"]) {

			$moeda_abrev = "lb";



			$USER_AGENT = $_SERVER['HTTP_USER_AGENT'];

	

			if(!crawlerDetect($USER_AGENT)) {

				$ip = $_SERVER['REMOTE_ADDR'];

				if($ip == "") {

					$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

				}



				$pais = @file_get_contents(ROOTPATH_HTTP."geoplugin/index.php?ip=".$ip);



				if($pais != '') {

					$query_rsPais = "SELECT moeda FROM paises WHERE codigo=:codigo AND visivel=1";

					$rsPais = DB::getInstance()->prepare($query_rsPais);

					$rsPais->bindParam(':codigo', $pais, PDO::PARAM_STR, 5);

					$rsPais->execute();

					$row_rsPais = $rsPais->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsPais = $rsPais->rowCount();

					

					if($totalRows_rsPais > 0) {

						$query_rsMoeda = "SELECT abreviatura FROM moedas WHERE id=:id AND visivel=1";

						$rsMoeda = DB::getInstance()->prepare($query_rsMoeda);

						$rsMoeda->bindParam(':id', $row_rsPais['moeda'], PDO::PARAM_INT);

						$rsMoeda->execute();

						$row_rsMoeda = $rsMoeda->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsMoeda = $rsMoeda->rowCount();



						if($totalRows_rsMoeda > 0) {

							$moeda_abrev = $row_rsMoeda['abreviatura'];

						}

					}

				}

			}



			self::mudaMoeda($moeda_abrev);

		}



		$moeda_val = explode("-", $_COOKIE["SITE_currency"]);



		//Verificar se a moeda atual ainda está ativa

		$query_rsMoedaAtual = "SELECT id FROM moedas WHERE abreviatura=:abreviatura AND visivel=1";

		$rsMoedaAtual = DB::getInstance()->prepare($query_rsMoedaAtual);

		$rsMoedaAtual->bindParam(':abreviatura', $moeda_val['0'], PDO::PARAM_STR, 5);

		$rsMoedaAtual->execute();

		$totalRows_rsMoedaAtual = $rsMoedaAtual->rowCount();



		if($totalRows_rsMoedaAtual == 0) {

			self::mudaMoeda("lb");

			$moeda_val = explode("-", $_COOKIE["SITE_currency"]);

		}



		DB::close();



		if($tipo == 0) {

			$moeda = $moeda_val[0];

		}

		else if($tipo == 1) {

			$moeda = $moeda_val[1];

		}

		elseif($tipo == 2) {

			$moeda = $moeda_val[0]."###".$moeda_val[1];

		}

		

		return $moeda;

	}



	public static function valorConversao($to) {				

		if($to == "lb") {

			$valor = 0;

		}

		else {

			$query_rsTaxa = "SELECT taxa FROM moedas WHERE abreviatura='$to'";

			$rsTaxa = DB::getInstance()->prepare($query_rsTaxa);

			$rsTaxa->execute();

			$row_rsTaxa = $rsTaxa->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsTaxa = $rsTaxa->rowCount();

			DB::close();

			

			$valor = $row_rsTaxa["taxa"];

		}

		

		return $valor;

	}



	public static function currency_convert($to, $amount) {		

		if($to != "lb") {

			$conversion = 0;

			$taxa = self::valorConversao($to);

			

			$conversion = (float)$amount * (float)$taxa;

			

			return $conversion;

		}

		else {

			return $amount;

		}

	}

	

	public static function verifica_cod_promo($cod, $total) {

		global $extensao, $row_rsCliente, $class_produtos;

		

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];



		if(CARRINHO_CODIGOS == 1) {

			if($total && $cod) {

				$data = date('Y-m-d');

				

				$query_rsCod = "SELECT * FROM codigos_promocionais WHERE visivel='1' AND ((datai<='$data' OR datai IS NULL OR datai='') AND (dataf>='$data' OR dataf IS NULL OR dataf='')) AND codigo='$cod' AND desconto>=0";

				$rsCod = DB::getInstance()->prepare($query_rsCod);

				$rsCod->execute();

				$row_rsCod = $rsCod->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsCod = $rsCod->rowCount();

				

				$valor_minimo = $row_rsCod['valor_minimo'];

				

				if($totalRows_rsCod > 0) {

					$id_cliente = $row_rsCliente['id'];



					// Se o tipo for vale automático e se o cliente já pertence à lista de emails enviados

		      $existe_user = 0;

		      if($row_rsCod["tipo_codigo"] > 1) {

	          $query_rsClientes = "SELECT COUNT(id) AS total FROM codigos_promocionais_emails WHERE id_codigo = '".$row_rsCod["id"]."' AND email = '".$row_rsCliente["email"]."'";

	          $rsClientes = DB::getInstance()->prepare($query_rsClientes);

	          $rsClientes->execute();

	          $row_rsClientes = $rsClientes->fetch(PDO::FETCH_ASSOC);

	          $totalRows_rsClientes = $rsClientes->rowCount();

	          

	          if($row_rsClientes['total'] > 0) {

	          	$existe_user = 1;

	          }

		      }

					

					$query_rsEncomendas = "SELECT * FROM encomendas WHERE id_cliente='$id_cliente' AND codigo_promocional='$cod' AND estado!='5'";

					$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);

					$rsEncomendas->execute();

					$totalRows_rsEncomendas = $rsEncomendas->rowCount();

					

					$query_rsEncomendas2 = "SELECT * FROM encomendas WHERE codigo_promocional='$cod' AND estado!='5'";

					$rsEncomendas2 = DB::getInstance()->prepare($query_rsEncomendas2);

					$rsEncomendas2->execute();

					$totalRows_rsEncomendas2 = $rsEncomendas2->rowCount();

					

					$tem_desconto = 0;

					$total_com_promo = 0;

					

					//Produtos sem desconto

					if($row_rsCod['tipo'] == 2) { 

						$tem_desconto = 1;

						

						$query_rsCar_Prod = "SELECT * FROM carrinho WHERE session='$carrinho_session'";

						$rsCar_Prod = DB::getInstance()->prepare($query_rsCar_Prod);

						$rsCar_Prod->execute();

						$totalRows_rsCar_Prod = $rsCar_Prod->rowCount();

						DB::close();

						

						if($totalRows_rsCar_Prod > 0) {

							while($row_rsCar_Prod = $rsCar_Prod->fetch()) {

								if($row_rsCar_Prod["produto"] > 0) {

									$preco_tot = $row_rsCar_Prod['preco'] * $row_rsCar_Prod['quantidade'];



									//Verifica se tem promoção

									$tem_promo = $class_produtos->promocaoProduto($row_rsCar_Prod["produto"], 2);

									if($tem_promo == 0) {

										$tem_desconto = 0;

									}



									//Verifica se tem desconto por quantidade

									if($row_rsCar_Prod['desconto'] > 0) {

										$preco_tot = $preco_tot - ($preco_tot * ($row_rsCar_Prod['desconto'] / 100));

									}



									//Somar ao total dos produtos com promoção

									if($tem_promo > 0) {

										$total_com_promo += $preco_tot;

									}

								}

							}

						}



						//Ao valor total retira o valor que tem promoção

						$total = $total - $total_com_promo;

					}

					

					// Verifica se o código é específico para uma categoria ou produto

					$marca = 0;

					$categoria = 0;

					$produto = 0;

					$tamanho = 0;

					

					if($row_rsCod['id_marca'] != 0 || $row_rsCod['id_categoria'] != 0 || $row_rsCod['id_peca'] != 0) {

						$query_rsCar_Prod = "SELECT * FROM carrinho WHERE session='$carrinho_session'";

						$rsCar_Prod = DB::getInstance()->prepare($query_rsCar_Prod);

						$rsCar_Prod->execute();

						$totalRows_rsCar_Prod = $rsCar_Prod->rowCount();

						

						while($row_rsCar_Prod = $rsCar_Prod->fetch()) {

							$query_rsProd = "SELECT * FROM l_pecas".$extensao." WHERE id='$row_rsCar_Prod[produto]'";

							$rsProd = DB::getInstance()->prepare($query_rsProd);

							$rsProd->execute();

							$row_rsProd = $rsProd->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsProd = $rsProd->rowCount();

							

							if($row_rsCod['id_marca'] != 0 && $row_rsProd["marca"] == $row_rsCod['id_marca']) {

								$marca = 1;

							}

							

							if($row_rsCod['id_categoria'] != 0) {

								if(CATEGORIAS == 1) {

									if($row_rsProd["categoria"] == $row_rsCod['id_categoria']) {

										$categoria = 1;

									}

									else {

										$query_rsCat = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsProd["categoria"]."'";

										$rsCat = DB::getInstance()->prepare($query_rsCat);

										$rsCat->execute();

										$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

										$totalRows_rsCat = $rsCat->rowCount();

										

										if($row_rsCat["cat_mae"] == $row_rsCod['id_categoria']) {

											$categoria = 1;

										}

										else {

											$query_rsCat2 = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCat["cat_mae"]."'";

											$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

											$rsCat2->execute();

											$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

											$totalRows_rsCat2 = $rsCat2->rowCount();

											

											if($row_rsCat2["cat_mae"] == $row_rsCod['id_categoria']) {

												$categoria = 1;

											}

											else {

												$query_rsCat3 = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCat2["cat_mae"]."'";

												$rsCat3 = DB::getInstance()->prepare($query_rsCat3);

												$rsCat3->execute();

												$row_rsCat3 = $rsCat3->fetch(PDO::FETCH_ASSOC);

												$totalRows_rsCat3 = $rsCat3->rowCount();

												

												if($row_rsCat3["cat_mae"] == $row_rsCod['id_categoria']) {

													$categoria = 1;

												}

											}

										}

									}

								}

								else if(CATEGORIAS == 2) {

									$query_rsCategorias = "SELECT id_categoria FROM l_pecas_categorias WHERE id_peca='".$row_rsProd["id"]."'";

									$rsCategorias = DB::getInstance()->prepare($query_rsCategorias);

									$rsCategorias->execute();

									$totalRows_rsCategorias = $rsCategorias->rowCount();



									if($totalRows_rsCategorias > 0) {

										while($row_rsCategorias = $rsCategorias->fetch()) {

											if($row_rsCategorias["id_categoria"] == $row_rsCod['id_categoria']) {

												$categoria = 1;

											}

											else {

												$query_rsCat = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCategorias["id_categoria"]."'";

												$rsCat = DB::getInstance()->prepare($query_rsCat);

												$rsCat->execute();

												$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

												$totalRows_rsCat = $rsCat->rowCount();

												

												if($row_rsCat["cat_mae"] == $row_rsCod['id_categoria']) {

													$categoria = 1;

												}

												else {

													$query_rsCat2 = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCat["cat_mae"]."'";

													$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

													$rsCat2->execute();

													$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

													$totalRows_rsCat2 = $rsCat2->rowCount();

													

													if($row_rsCat2["cat_mae"] == $row_rsCod['id_categoria']) {

														$categoria = 1;

													}

													else {

														$query_rsCat3 = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCat2["cat_mae"]."'";

														$rsCat3 = DB::getInstance()->prepare($query_rsCat3);

														$rsCat3->execute();

														$row_rsCat3 = $rsCat3->fetch(PDO::FETCH_ASSOC);

														$totalRows_rsCat3 = $rsCat3->rowCount();

														

														if($row_rsCat3["cat_mae"] == $row_rsCod['id_categoria']) {

															$categoria = 1;

														}

													}

												}

											}

										}

									}

								}

							}

							

							if($row_rsCod['id_peca'] != 0 && $row_rsProd["id"] == $row_rsCod['id_peca']) {

								$produto = 1;

							}

							

							if($row_rsCod['tamanho'] != 0) {

								$tam1 = 0;

								$tam2 = 0;

								$tam3 = 0;

								$tam4 = 0;

								$tam5 = 0;



								if($row_rsCar_Prod['op1'] > 0) {

									$tam1 = $row_rsCar_Prod['op1']; 

								}

								if($row_rsCar_Prod['op2'] > 0) {

									$tam2 = $row_rsCar_Prod['op2'];

								}

								if($row_rsCar_Prod['op3'] > 0) {

									$tam3 = $row_rsCar_Prod['op3'];

								}

								if($row_rsCar_Prod['op4'] > 0) {

									$tam4 = $row_rsCar_Prod['op4']; 

								}

								if($row_rsCar_Prod['op5'] > 0) {

									$tam5 = $row_rsCar_Prod['op5'];

								}

								

								$query_rsT = "SELECT * FROM l_pecas_tamanhos WHERE peca='$row_rsCar_Prod[produto]' AND op1='$tam1' AND op2='$tam2' AND op3='$tam3' AND op4='$tam4' AND op5='$tam5'";

								$rsT = DB::getInstance()->prepare($query_rsT);

								$rsT->execute();

								$row_rsT = $rsT->fetch(PDO::FETCH_ASSOC);

								$totalRows_rsT = $rsT->rowCount();



								if($totalRows_rsT > 0 && $row_rsT["id"] == $row_rsCod["tamanho"]) {

									$tamanho = 1;

								}

							}

						}

					}



					if($row_rsCliente['pais'] != $row_rsCod['id_country'] && $row_rsCod['id_country'] != 0) {

						return "7"; //CÓD NÃO É VÁLIDO PARA O TAMANHO

					}

					else if($row_rsCod['tamanho'] != 0 && $tamanho == 0) {

						return "8"; //CÓD NÃO É VÁLIDO PARA O TAMANHO

					}

					else if($row_rsCod['tamanho'] == 0 && $row_rsCod['id_peca'] != 0 && $produto == 0) {

						return "8"; //CÓD NÃO É VÁLIDO PARA O PRODUTO

					}

					else if($row_rsCod['tamanho'] == 0 && $row_rsCod['id_peca'] == 0 && $row_rsCod['id_categoria'] != 0 && $categoria == 0) { 

						return "8"; //CÓD NÃO É VÁLIDO PARA A CATEGORIA

					}

					else if($row_rsCod['id_categoria'] != 0 && $categoria == 0) { 

						return "8"; //CÓD NÃO É VÁLIDO PARA A CATEGORIA

					}

					else if($row_rsCod['tamanho'] == 0 && $row_rsCod['id_peca'] == 0 && $row_rsCod['id_categoria'] == 0 && $row_rsCod['id_marca'] != 0 && $marca == 0) {

						return "8"; //CÓD NÃO É VÁLIDO PARA A MARCA

					}

					else if($row_rsCod['id_marca'] != 0 && $marca == 0) {

						return "8"; //CÓD NÃO É VÁLIDO PARA A MARCA

					}

					else if(($row_rsCod["grupo"] && ($row_rsCod["grupo"] != $row_rsCliente["grupo"])) || ($row_rsCod["id_cliente"] && ($row_rsCod["id_cliente"] != $row_rsCliente["id"])) || ($row_rsCod["tipo_codigo"] > 1 && $existe_user == 0)) {	

						return "7"; //CÓD NÃO É VÁLIDO PARA O CLIENTE	

					}

					elseif($tem_desconto == 1) {

						return "5"; //CÓD APENAS PARA SEM DESCONTO

					}

					elseif($totalRows_rsEncomendas2 > $row_rsCod['limite_total'] && $row_rsCod['limite_total'] > 0) {

						return "1"; //CÓD JÁ USADO NO TOTAL

					}

					elseif($row_rsCod['limite_cliente'] > 0 && $totalRows_rsEncomendas >= $row_rsCod['limite_cliente']) {

						return "4"; //CÓD JÁ USADO PELO CLIENTE

					}

					elseif($total >= $valor_minimo) {

						return "3"; //TUDO DIREITO

					}

					else {

						return "2"; //VALOR INFERIOR AO MINIMO

					}

				}

				else{

					return "1"; //CÓDIGO NÃO VÁLIDO

				}	



				DB::close();

			}

			else {

				return "0"; //ERRO

			}

		}

	}

	

	public static function calcula_cod_promo($cod) {

		global $extensao, $class_produtos;

		

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		$desconto = 0;

		

		if(CARRINHO_CODIGOS == 1) {		

			$data = date('Y-m-d');				

			$total_cat_com_promo = 0;

			$total_com_promo = 0;

			$total = self::precoTotal();	

			

			if($total && $cod) {

				$query_rsCod = "SELECT * FROM codigos_promocionais WHERE visivel='1' AND ((datai<='$data' OR datai IS NULL OR datai='') AND (dataf>='$data' OR dataf IS NULL OR dataf='')) AND codigo='$cod' AND desconto>=0 AND (valor_minimo<='$total' OR valor_minimo is NULL)";

				$rsCod = DB::getInstance()->prepare($query_rsCod);

				$rsCod->execute();

				$row_rsCod = $rsCod->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsCod = $rsCod->rowCount();

				

				if($totalRows_rsCod > 0) {

					$query_rsCar = "SELECT * FROM carrinho WHERE session='$carrinho_session'";

					$rsCar = DB::getInstance()->prepare($query_rsCar);

					$rsCar->execute();

					$totalRows_rsCar = $rsCar->rowCount();

					

					$total_com_cat = 0;



					while($row_rsCar = $rsCar->fetch(PDO::FETCH_ASSOC)) {

						$linha_id = $row_rsCar['id'];

						$produto = $row_rsCar['produto'];

						

						$query_rsProduto = "SELECT * FROM l_pecas".$extensao." WHERE id = '$produto'";

						$rsProduto = DB::getInstance()->prepare($query_rsProduto);

						$rsProduto->execute();

						$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsProduto = $rsProduto->rowCount();

						

						$preco = $row_rsCar['preco'];

						$quantidade = $row_rsCar['quantidade'];

						$preco_tot = $preco * $quantidade;

								

						//verifica se tem desconto por quantidade

						if($row_rsCar['desconto'] > 0) {

							$preco_tot = $preco_tot - ($preco_tot * ($row_rsCar['desconto'] / 100));

						}



						//Verificar se o produto tem promoção

						$promocao = 0;

						$tem_promo = $class_produtos->promocaoProduto($produto, 2);

						if($tem_promo > 0) {

							$promocao = 1;

							$total_com_promo += $preco_tot;

						}

												

						//Verifica se o código é específico para uma categoria ou produto

						$marca = 0;

						$categoria = 0;

						$produto = 0;

						$tamanho = 0;



						if($row_rsCod['id_marca'] != 0 || $row_rsCod['id_categoria'] != 0 || $row_rsCod['id_peca'] != 0) {		

							if($row_rsCod['id_marca'] != 0 && $row_rsProduto["marca"] == $row_rsCod['id_marca']) {

								$marca = 1;

							}

							

							if($row_rsCod['id_categoria'] != 0) {

								if(CATEGORIAS == 1) {

									if($row_rsProduto["categoria"] == $row_rsCod['id_categoria']) {

										$categoria = 1;

									}

									else {

										$query_rsCat = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsProduto["categoria"]."'";

										$rsCat = DB::getInstance()->prepare($query_rsCat);

										$rsCat->execute();

										$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

										$totalRows_rsCat = $rsCat->rowCount();

										

										if($row_rsCat["cat_mae"] == $row_rsCod['id_categoria']) {

											$categoria = 1;

										}

										else {

											$query_rsCat2 = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCat["cat_mae"]."'";

											$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

											$rsCat2->execute();

											$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

											$totalRows_rsCat2 = $rsCat2->rowCount();

											

											if($row_rsCat2["cat_mae"] == $row_rsCod['id_categoria']) {

												$categoria = 1;

											}

											else {

												$query_rsCat3 = "SELECT * FROM l_categorias".$extensao." WHERE id='".$row_rsCat2["cat_mae"]."'";

												$rsCat3 = DB::getInstance()->prepare($query_rsCat3);

												$rsCat3->execute();

												$row_rsCat3 = $rsCat3->fetch(PDO::FETCH_ASSOC);

												$totalRows_rsCat3 = $rsCat3->rowCount();

												

												if($row_rsCat3["cat_mae"] == $row_rsCod['id_categoria']) {

													$categoria = 1;

												}

											}

										}

									}

								}

								else if(CATEGORIAS == 2) {

									$query_rsCategoriasProd = "SELECT id_categoria FROM l_pecas_categorias WHERE id_peca = :id";

									$rsCategoriasProd = DB::getInstance()->prepare($query_rsCategoriasProd);

									$rsCategoriasProd->bindParam(':id', $row_rsProduto['id'], PDO::PARAM_INT);

									$rsCategoriasProd->execute();

									$totalRows_rsCategoriasProd = $rsCategoriasProd->rowCount();



									if($totalRows_rsCategoriasProd > 0) {

										while(($row_rsCategoriasProd = $rsCategoriasProd->fetch()) && $categoria == 0) {

											if($row_rsCategoriasProd["id_categoria"] == $row_rsCod['id_categoria']) {

												$total_com_cat += $preco_tot;

												if($promocao > 0) {

													$total_cat_com_promo += $preco_tot;

												}



												$categoria = 1;

											}

											else {

												$query_rsCat2 = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id=:id";

												$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

												$rsCat2->bindParam(':id', $row_rsCategoriasProd["id_categoria"], PDO::PARAM_INT);

												$rsCat2->execute();

												$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

												$totalRows_rsCat2 = $rsCat2->rowCount();



												if($row_rsCat2["cat_mae"] == $row_rsCod['id_categoria']) {

													$total_com_cat += $preco_tot;

													if($promocao > 0) {

														$total_cat_com_promo += $preco_tot;

													}



													$categoria = 1;

												}

												else {

													$query_rsCat3 = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id=:id";

													$rsCat3 = DB::getInstance()->prepare($query_rsCat3);

													$rsCat3->bindParam(':id', $row_rsCat2["cat_mae"], PDO::PARAM_INT);

													$rsCat3->execute();

													$row_rsCat3 = $rsCat3->fetch(PDO::FETCH_ASSOC);

													$totalRows_rsCat3 = $rsCat3->rowCount();



													if($row_rsCat3["cat_mae"] == $row_rsCod['id_categoria']) {

														$total_com_cat += $preco_tot;

														if($promocao > 0) {

															$total_cat_com_promo += $preco_tot;

														}



														$categoria = 1;

													}

												}

											}

										}

									}

								}

							}

							

							if($row_rsCod['id_peca'] != 0 && $row_rsProduto["id"] == $row_rsCod['id_peca']) {

								$produto = 1;

							}

							

							if($row_rsCod['tamanho'] != 0) {

								$tam1 = 0;

								$tam2 = 0;

								$tam3 = 0;

								$tam4 = 0;

								$tam5 = 0;



								if($row_rsCar['op1'] > 0) {

									$tam1 = $row_rsCar['op1'];

								}

								if($row_rsCar['op2'] > 0) {

									$tam2 = $row_rsCar['op2'];

								}

								if($row_rsCar['op3'] > 0) {

									$tam3 = $row_rsCar['op3'];

								}

								if($row_rsCar['op4'] > 0) {

									$tam4 = $row_rsCar['op4'];

								}

								if($row_rsCar['op5'] > 0) {

									$tam5 = $row_rsCar['op5'];

								}

							

								$query_rsT = "SELECT * FROM l_pecas_tamanhos WHERE peca='$row_rsCar[produto]' AND op1='$tam1' AND op2='$tam2' AND op3='$tam3' AND op4='$tam4' AND op5='$tam5'";

								$rsT = DB::getInstance()->prepare($query_rsT);

								$rsT->execute();

								$row_rsT = $rsT->fetch(PDO::FETCH_ASSOC);

								$totalRows_rsT = $rsT->rowCount();

								

								if($totalRows_rsT > 0 && $row_rsT["id"] == $row_rsCod["tamanho"]) {

									$tamanho = 1;

								}

							}

						}

						

						if($row_rsCod['tamanho'] != 0 && $tamanho == 1) {

							$total_com_cat += $preco_tot;

							if($promocao > 0) {

								$total_cat_com_promo += $preco_tot;

							}

						} 

						elseif($row_rsCod['tamanho'] == 0 && $row_rsCod['id_peca'] != 0 && $produto == 1) {

							$total_com_cat += $preco_tot;

							if($promocao > 0) {

								$total_cat_com_promo += $preco_tot;

							}

						} 

						elseif($row_rsCod['tamanho'] == 0 && $row_rsCod['id_peca'] == 0 && $row_rsCod['id_categoria'] != 0 && $categoria == 1) {

							$total_com_cat += $preco_tot;

							if($promocao > 0) {

								$total_cat_com_promo += $preco_tot;	

							}

						} 

						elseif($row_rsCod['tamanho'] == 0 && $row_rsCod['id_peca'] == 0 && $row_rsCod['id_categoria'] == 0 && $row_rsCod['id_marca'] != 0 && $marca == 1) {

							$total_com_cat += $preco_tot;

							if($promocao > 0) {

								$total_cat_com_promo += $preco_tot;

							}

						}

					}

					

					//TOTAL PRODUTOS SEM PROMOÇÃO

					$total_sem_promo = $total - $total_com_promo;

					$total_cat_sem_promo = $total_com_cat - $total_cat_com_promo;

					

					if($row_rsCod['tipo'] == 2) {

						$total = $total_sem_promo;

						$total_com_cat = $total_cat_sem_promo;

					}

				

					if($row_rsCod["tipo_desconto"] == 1) {

						if($total_com_cat == 0) {

							$total_saldo = $total * ($row_rsCod['desconto'] / 100);

						}

						else {

							$total_saldo = $total_com_cat * ($row_rsCod['desconto'] / 100);

						}

					} 

					else {

						$total_saldo = $row_rsCod['desconto'];

					}

					

					$total_saldo = number_format(round($total_saldo, 2), 2, '.', '');

					$desconto = $total_saldo;

				}

				else {

					$desconto = 0;

				}



				DB::close();

			}	

		}

		

		return $desconto;

	}

	

	public static function calcula_valor_saldo($id, $id_tamanho = 0, $qtd = 1) {

		$ret = 0;



		$query_rsProduto = "SELECT * FROM l_pecas_en WHERE id='$id'";

		$rsProduto = DB::getInstance()->prepare($query_rsProduto);

		$rsProduto->execute();

		$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsProduto = $rsProduto->rowCount();



		$preco_tamanho = 0;

		if($id_tamanho > 0) {

			$query_rsTamanho = "SELECT * FROM l_pecas_tamanhos WHERE id = '$id_tamanho'";

			$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);

			$rsTamanho->execute();

			$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsTamanho = $rsTamanho->rowCount();



			$preco_tamanho = $row_rsTamanho['preco'];

		}



		if($row_rsProduto['promocao'] > 0 && $row_rsProduto['promocao_desconto'] > 0 && $row_rsProduto['saldo'] == 1) {		

			if($preco_tamanho > 0) {

				$preco_prod = $preco_tamanho * $qtd;

			}

			else {

				$preco_prod = $row_rsProduto['preco'];

				

				$preco_prod = $preco_prod * $qtd;

			}



			$promocao = number_format($row_rsProduto['promocao_desconto'], 0, '', '');

			

			$valor_saldo = $preco_prod * ($promocao / 100);



			$ret = round($valor_saldo, 2);

		}



		DB::close();



		return $ret;	

	}

	

	public static function verificaMetodos($pagamento, $portes_pag, $entrega, $portes_ent, $pais) {

		global $extensao, $row_rsCliente, $class_user, $valor_conversao;

				

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		$preco_cliente = $class_user->clienteData('pvp');



		//Verificar se apenas existe um Cheque Prenda no carrinho ou se também existem produtos.

		$verifica_carrinho = self::verificaCarrinho();

			

		//Métodos de envio disponíveis para o pagamento selecionado

		$query_rsDisp = "SELECT id FROM met_pag_envio WHERE met_pagamento='$pagamento' AND met_envio = '$entrega'";

		$rsDisp = DB::getInstance()->prepare($query_rsDisp);

		$rsDisp->execute();

		$row_rsDisp = $rsDisp->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsDisp = $rsDisp->rowCount();

		

		$total_preco = self::precoTotal() * $valor_conversao;

		$valor_pagamento = 0;

		$valor_entrega = 0;

		$encontrado = 0;

		$total_peso = self::totalPeso();

		

		//Se existirem produtos e os métodos de pagamento e envio estiverem relacionados OU se existir apenas cheque prenda no carrinho

		if(($verifica_carrinho == 1 && $totalRows_rsDisp > 0) || $verifica_carrinho == 0) {

			if(CARRINHO_SALDO == 1) {

				$query_rsProcS = "SELECT valor FROM carrinho_comprar WHERE session='$carrinho_session'";

				$rsProcS = DB::getInstance()->prepare($query_rsProcS);

				$rsProcS->execute();

				$row_rsProcS = $rsProcS->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsProcS = $rsProcS->rowCount();

			

				$saldo_disp = $row_rsCliente['saldo'];  

				

				if($saldo_disp > 0 && $row_rsProcS['valor'] > 0) {

					if($saldo_disp >= $total_preco) {

						$saldo_compra = $total_preco;

						$saldo_disp = $saldo_disp - $total_preco;

						$total_preco = 0;

					}

					else {

						$total_preco = $total_preco - $saldo_disp; 

					}

				}

			}

			

			$desconto_promo = 0;

			if(CARRINHO_CODIGOS == 1) {

				$query_rsCarCodProm = "SELECT id_codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";

				$rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);

				$rsCarCodProm->execute();

				$row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsCarCodProm = $rsCarCodProm->rowCount();

				

				if($totalRows_rsCarCodProm > 0) {

					$query_rsCodProm = "SELECT codigo FROM codigos_promocionais WHERE id='".$row_rsCarCodProm["id_codigo"]."'";

					$rsCodProm = DB::getInstance()->prepare($query_rsCodProm);

					$rsCodProm->execute();

					$row_rsCodProm = $rsCodProm->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsCodProm = $rsCodProm->rowCount();

					DB::close();

					

					if($totalRows_rsCodProm > 0) {

						$desconto_promo = self::calcula_cod_promo($row_rsCodProm['codigo']);



						$total_preco = $total_preco - $desconto_promo; 

					}

				}

			}

					

			//Verificar Método de Pagamento

			$query_rsPagamento = "SELECT zonas_pag.portes, zonas_pag.tipo, met_pagamento.* FROM zonas_met_pagamento AS zonas_pag, met_pagamento".$extensao." AS met_pagamento, zonas, paises WHERE zonas_pag.id_zona=zonas.id AND zonas_pag.id_metodo=met_pagamento.id AND paises.zona=zonas.id AND paises.id='$pais' AND met_pagamento.id='$pagamento' ORDER BY met_pagamento.ordem ASC, met_pagamento.nome ASC";

			$rsPagamento = DB::getInstance()->prepare($query_rsPagamento);

			$rsPagamento->execute();

			$row_rsPagamento = $rsPagamento->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsPagamento = $rsPagamento->rowCount();

			

			if($row_rsPagamento['tipo'] == 1) {

				$valor_pagamento = $row_rsPagamento['portes'];

			}

			else {

				//Quando o valor método de pagamento é em %, temos de somar ao total do carrinho o valor dos portes de entrega

				$aux = $total_preco + $portes_ent + $desconto_promo;

				if($row_rsPagamento['portes'] > 0) {

					$valor_pagamento = $aux - ($aux - ($aux * ($row_rsPagamento['portes'] / 100)));

				}

			}

			

			//Verificar Método de Envio -  apenas verificamos o método de envio caso o carrinho não tenha apenas Cheque Prenda

			if($verifica_carrinho == 1) {

				$query_rsEntrega = "SELECT zonas_met_envio.portes, zonas_met_envio.tipo, zonas_met_envio.tabela, zonas_met_envio.custo, met_envio".$extensao.".*, zonas.portes_gratis".$preco_cliente." FROM zonas_met_envio, met_envio".$extensao.", zonas, paises WHERE zonas_met_envio.id_zona=zonas.id AND zonas_met_envio.id_metodo=met_envio".$extensao.".id AND paises.zona=zonas.id AND paises.id='$pais' AND met_envio".$extensao.".id='$entrega' ORDER BY met_envio".$extensao.".ordem ASC, met_envio".$extensao.".nome ASC";

				$rsEntrega = DB::getInstance()->prepare($query_rsEntrega);

				$rsEntrega->execute();

				$row_rsEntrega = $rsEntrega->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsEntrega = $rsEntrega->rowCount();

						

				//Soma as unidades do carrinho

				$query_rsQuant = "SELECT SUM(quantidade) AS total_qtd FROM carrinho WHERE session='$carrinho_session'";

				$rsQuant = DB::getInstance()->prepare($query_rsQuant);

				$rsQuant->execute();

				$row_rsQuant = $rsQuant->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsQuant = $rsQuant->rowCount();



				if($row_rsEntrega['tabela'] != 0) {

					$id_tabela = $row_rsEntrega['tabela'];

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

					//Se não existir um intervalo válido para o peso da encomenda, verificar se existem valores para o "Por cada X Kg adicional cobra Y €"

					else {

						$query_rsTabela = "SELECT kg, preco FROM transportadoras WHERE id = '$id_tabela'";

						$rsTabela = DB::getInstance()->prepare($query_rsTabela);

						$rsTabela->execute();

						$row_rsTabela = $rsTabela->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsTabela = $rsTabela->rowCount();



						if($totalRows_rsTabela > 0 && $row_rsTabela['kg'] > 0 && $row_rsTabela['preco'] > 0) {

							//Obter o preço associado ao intervalo máximo da tabela

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

						}

					}

					

					$valor_entrega = $preco_transp + $row_rsEntrega['custo'];

				}

				else {		

					$peso = $total_peso;	

					

					if($row_rsEntrega['tipo'] == 1) {

						$valor_entrega = $row_rsEntrega['portes'] * $row_rsQuant['total_qtd'];

					}

					else if($row_rsEntrega['tipo'] == 2) {

						if($peso > 0) {

							$valor_entrega = $row_rsEntrega['portes'] * $peso;

						}

						else {

							$valor_entrega = $row_rsEntrega['portes'];

						}

					}

					else {

						$valor_entrega = 0;

					}

					

					$valor_entrega = $valor_entrega + $row_rsEntrega['custo'];

				}

			}

					

			//VERIFICA SE HA CAMPANHAS DE PORTES GRATIS PARA ESTE CARRINHO

			$query_rsPGratis = "SELECT zonas.*, paises.nome AS pais_nome FROM zonas, paises WHERE paises.id='$pais' AND paises.zona=zonas.id";

			$rsPGratis = DB::getInstance()->prepare($query_rsPGratis);

			$rsPGratis->execute();

			$row_rsPGratis = $rsPGratis->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsPGratis = $rsPGratis->rowCount();

			

			$zona_cliente = $row_rsPGratis['id'];		

			$data = date('Y-m-d H:i:s');

			$produto_com_portes_gratis = 0;

			

			$query_rsCarList = "SELECT produto FROM carrinho WHERE session = '$carrinho_session' ORDER BY id ASC";

			$rsCarList = DB::getInstance()->prepare($query_rsCarList);

			$rsCarList->execute();

			$totalRows_rsCarList = $rsCarList->rowCount();

			DB::close();

			

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

							//Verifica se os portes gratis se aplicam com base no preço mínimo e peso máximo

							$aplica_p_gratis = 1;



							if($row_rsCampGratis['min_encomenda'] > 0 && $row_rsCampGratis['min_encomenda'] > $total_preco) {

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



			if($produto_com_portes_gratis == 1) {

				$valor_entrega = 0;

			}

			

			if($valor_entrega > 0) {

	      if(!$total_peso) {

	      	$total_peso = self::totalPeso();

	      }



	      $portes_gratis_peso = 0;



	      if($row_rsEntrega['peso_max'] > 0) {

          $peso_max = $row_rsEntrega['peso_max'];



          if($total_peso > $peso_max) {

            $portes_gratis_peso = 1;

          }

	      }



				if($row_rsEntrega['portes_gratis'.$preco_cliente]!=NULL && $row_rsEntrega['portes_gratis'.$preco_cliente] > 0 && self::mostraPreco($row_rsEntrega['portes_gratis'.$preco_cliente], 2) <= $total_preco && $portes_gratis_peso == 0) {

					$valor_entrega = 0; 

				}

			}

			

			$valor_pagamento = self::mostraPreco($valor_pagamento, 2, 1);

			$valor_entrega = self::mostraPreco($valor_entrega, 2, 1);

			

			$portes_pag = self::mostraPreco($portes_pag, 2, 0);

			$portes_ent = self::mostraPreco($portes_ent, 2, 0);

			

			// echo $valor_pagamento." - ".$portes_pag." - ".$valor_entrega." - ".$portes_ent;



			if($verifica_carrinho == 1) {

				if($valor_pagamento == $portes_pag && $valor_entrega == $portes_ent) {

					$encontrado = 1;		

				}

			}

			else {

				if($valor_pagamento == $portes_pag) {

					$encontrado = 1;

				}

			}

		}



		DB::close();

			

		return $encontrado;

	}

	

	public static function acumularSaldo() {

		global $extensao;

		

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		$saldo_acumula = 0;



		$query_rsCar = "SELECT carrinho.*, carrinho.id AS id_linha FROM carrinho LEFT JOIN l_pecas".$extensao." AS pecas ON carrinho.produto = pecas.id WHERE carrinho.session = '$carrinho_session' AND pecas.visivel = 1 GROUP BY pecas.id ORDER BY pecas.ordem ASC";

		$rsCar = DB::getInstance()->prepare($query_rsCar);

		$rsCar->execute();

		$row_rsCar = $rsCar->fetchAll();

		$totalRows_rsCar = $rsCar->rowCount();

		

		if($totalRows_rsCar > 0) {	

			foreach($row_rsCar as $carrinho){ 

				if(!$cheque_prenda) {

					$produto = $carrinho['produto'];     		

					$quantidade = $carrinho['quantidade'];

					$produto_acumula = 0;

									

					$query_rsTamDef = "SELECT id FROM l_pecas_tamanhos WHERE op1=:tam1 AND op2=:tam2 AND op3=:tam3 AND op4=:tam4 AND op5=:tam5 AND peca=:id";

					$rsTamDef = DB::getInstance()->prepare($query_rsTamDef);

					$rsTamDef->bindParam(':id', $produto, PDO::PARAM_INT, 5); 

					$rsTamDef->bindParam(':tam1', $carrinho["op1"], PDO::PARAM_INT, 5); 

					$rsTamDef->bindParam(':tam2', $carrinho["op2"], PDO::PARAM_INT, 5); 

					$rsTamDef->bindParam(':tam3', $carrinho["op3"], PDO::PARAM_INT, 5); 

					$rsTamDef->bindParam(':tam4', $carrinho["op4"], PDO::PARAM_INT, 5); 

					$rsTamDef->bindParam(':tam5', $carrinho["op5"], PDO::PARAM_INT, 5); 

					$rsTamDef->execute();

					$row_rsTamDef = $rsTamDef->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsTamDef = $rsTamDef->rowCount();

														

					$produto_acumula += self::calcula_valor_saldo($produto, $row_rsTamDef['id'], $quantidade);

					

					if($produto_acumula > 0) {

						$saldo_acumula += $produto_acumula;

					}

				}

			}

		}



		DB::close();

		

		return $saldo_acumula;

	}

	

	public static function isEmpty() {

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		

		$query_rsCar = "SELECT id FROM carrinho WHERE session = '$carrinho_session'";

		$rsCar = DB::getInstance()->prepare($query_rsCar);

		$rsCar->execute();

		$row_rsCar = $rsCar->fetchAll();

		$totalRows_rsCar = $rsCar->rowCount();

		DB::close();

		

		return $totalRows_rsCar;

	}

	

	public static function mostraPreco($valor, $tipo = 0, $converter = 1, $simbolo = "after") {

		global $moeda;



		$query_rsTaxa = "SELECT * FROM moedas WHERE abreviatura='$moeda'";

		$rsTaxa = DB::getInstance()->prepare($query_rsTaxa);

		$rsTaxa->execute();

		$row_rsTaxa = $rsTaxa->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsTaxa = $rsTaxa->rowCount();

		DB::close();



		if($row_rsTaxa['local'] == 1) {

			$simbolo2 = "after";

		}

		else {

			$simbolo2 = "before";

		}



		if($simbolo != $simbolo2) {

			$simbolo = $simbolo2;

		}

	

		$moeda_name = self::getMoeda(0);

		$moeda_simbol = self::getMoeda(1);

		

		if($converter == 1) {

			$valor = self::currency_convert($moeda, $valor);

		}

		

		$preco_helpers = "<small> &pound; </small>";

			

		//$ret = number_format($valor, 2, ".", ".");

		$ret = $valor;

					

		// if($tipo == 0) {

		// 	$ret = number_format($valor, 2, ".", ".");

			

		// 	if($simbolo == "after") {

		// 		$ret = $preco_helpers.$ret;

		// 	}

		// 	if($simbolo == "before") {

		// 	  $ret = $ret.$preco_helpers;

		// 	}

		// }

		// else if($tipo == 2) {

		// 	$ret = number_format($valor, 2, ".", "");

		// }

		// else {

		// 	$ret = number_format($valor, 2, ".", ".");

		// }

		if ($tipo == 0) {

			if($simbolo == "after") {

				$ret = $preco_helpers.$ret;

			}

			if($simbolo == "before") {

			  $ret = $preco_helpers.$ret;

			}

		}

				

		return $ret;	

	}

	

	public static function mostraPrecoEnc($id, $valor) {

		$query_rsEncomenda = "SELECT moeda FROM encomendas WHERE id = '$id'";

		$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);

		$rsEncomenda->execute();

		$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsEncomenda = $rsEncomenda->rowCount();

		DB::close();

		

		$moeda = $row_rsEncomenda['moeda'];



		$ret = number_format($valor, 2, ",", ".");

		return $moeda.$ret;	

	}

	

	public static function precoTotal() {

		global $class_produtos;

		

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		$preco = 0;

		

		$query_rsCar = "SELECT * FROM carrinho WHERE session='$carrinho_session'";

		$rsCar = DB::getInstance()->prepare($query_rsCar);

		$rsCar->execute();

		$totalRows_rsCar = $rsCar->rowCount();

		DB::close();

		

		while($row_rsCar = $rsCar->fetch()) {

			if($row_rsCar['cheque_prenda'] == 1) {

				$preco += $row_rsCar['preco'];

			}

			else {

				$produto = $row_rsCar["produto"];

				$quantidade = $row_rsCar["quantidade"];

				$tam1 = $row_rsCar['op1'];

				$tam2 = $row_rsCar['op2'];

				$tam3 = $row_rsCar['op3'];

				$tam4 = $row_rsCar['op4'];

				$tam5 = $row_rsCar['op5'];



				$query_rsTamDef = "SELECT id FROM l_pecas_tamanhos WHERE op1=:tam1 AND op2=:tam2 AND op3=:tam3 AND op4=:tam4 AND op5=:tam5 AND peca=:id";

				$rsTamDef = DB::getInstance()->prepare($query_rsTamDef);

				$rsTamDef->bindParam(':id', $produto, PDO::PARAM_INT, 5); 

				$rsTamDef->bindParam(':tam1', $tam1, PDO::PARAM_INT, 5); 

				$rsTamDef->bindParam(':tam2', $tam2, PDO::PARAM_INT, 5); 

				$rsTamDef->bindParam(':tam3', $tam3, PDO::PARAM_INT, 5); 

				$rsTamDef->bindParam(':tam4', $tam4, PDO::PARAM_INT, 5); 

				$rsTamDef->bindParam(':tam5', $tam5, PDO::PARAM_INT, 5); 

				$rsTamDef->execute();

				$row_rsTamDef = $rsTamDef->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsTamDef = $rsTamDef->rowCount();

				DB::close();



				$preco += $class_produtos->precoProduto($produto, 0, $quantidade, $row_rsTamDef['id']) * $quantidade;

			}

		}

		

		return $preco;

	}	

	

	public static function emailEncomenda($id, $imprime = 0) {

		global $extensao, $Recursos, $class_produtos;



		//Verificar se apenas existe um Cheque Prenda no carrinho ou se também existem produtos.

		$verifica_encomenda = self::verificaEncomenda($id);



		$query_rsEncomenda = "SELECT * FROM encomendas WHERE id = :id";

		$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);

		$rsEncomenda->bindParam(':id', $id, PDO::PARAM_INT); 

		$rsEncomenda->execute();

		$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsEncomenda = $rsEncomenda->rowCount();

		$query_rsCar_pre = "SELECT * FROM encomendas_produtos LEFT JOIN l_pecas_en AS pecas ON (encomendas_produtos.produto_id = pecas.id AND pecas.visivel = 1) WHERE id_encomenda = :id";

		$rsCar_pre = DB::getInstance()->prepare($query_rsCar_pre);

		$rsCar_pre->bindParam(':id', $id, PDO::PARAM_INT);

		$rsCar_pre->execute();

		$row_rsCar_pre = $rsCar_pre->fetch(PDO::FETCH_ASSOC);

		$totalRows_rspre = $rsCar_pre->rowCount();


		$store_name = $row_rsEncomenda["store_name"];

		$clienteid = $row_rsEncomenda["id_cliente"];



		$query_rsstore = "SELECT * FROM store_locater WHERE b_name = '".$store_name."'";

		$rsstore = DB::getInstance()->prepare($query_rsstore);

		$rsstore->execute();

		$row_rsstore = $rsstore->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsstore = $rsstore->rowCount();



		$start_unserilize  = $row_rsstore["start_time"];

		$close_unserilize  = $row_rsstore["close"];

		$unserialize_strat = unserialize($start_unserilize);



		setlocale(LC_TIME, "C");

		$today = strftime("%A");



		$str = strtolower($today);

		$endsday = $str."_end";



		foreach ($unserialize_strat as $key => $value) {



			if($str == $key)

			{

				$convert_start = $value;

				$cur_start = date('h:i A', strtotime($convert_start));	

			}



			if($endsday == $key)

			{

				$convert_end = $value;

				$cur_end = date('h:i A', strtotime($convert_end));

			}



		}





		$mondaycheck 	= $unserialize_strat["monday"];

		$m_check_end  	= $unserialize_strat["monday_end"];



		$query_rsPCheck = "SELECT * FROM clientes WHERE id = ".$clienteid;

		$rsPcheck = DB::getInstance()->prepare($query_rsPCheck);

		$rsPcheck->execute();

		$row_rsPcheck = $rsPcheck->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPcheck = $rsPcheck->rowCount();

		

		$rolecheck = $row_rsPcheck["roll"];



		$link_paypal = "";

		if($row_rsEncomenda['url_paypal'] != "") {

			$link_paypal = $row_rsEncomenda['url_paypal'];

		}



		$query_rsCarrinhoFinal = "SELECT * FROM encomendas_produtos WHERE id_encomenda=:id ORDER BY id ASC";

		$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);

		$rsCarrinhoFinal->bindParam(':id', $id, PDO::PARAM_INT); 

		$rsCarrinhoFinal->execute();

		$row_rsCarrinhoFinal = $rsCarrinhoFinal->fetchAll(PDO::FETCH_ASSOC);

		$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();





		$product_cate_array = array();



		foreach ($row_rsCarrinhoFinal as $keymain => $value) {

			$getq_pro_id = $value['produto_id'];



			$query_rsCar = "SELECT categorias.nome, pecas_cate.id_categoria as cate_id, categorias.cat_mae FROM l_pecas_categorias as pecas_cate LEFT JOIN l_categorias_en as categorias ON (categorias.id = pecas_cate.id_categoria) WHERE pecas_cate.id_peca = '$getq_pro_id' ORDER BY pecas_cate.id ASC";



			$rsCar = DB::getInstance()->prepare($query_rsCar);

			$rsCar->execute();

			$row_rsCar = $rsCar->fetchAll();

			$totalRows_rsCar = $rsCar->rowCount();



			$product_cate_array[$keymain]['product_id'] = $getq_pro_id;

			

			foreach ($row_rsCar as $key => $innermain) {

				if ($innermain['cat_mae'] == 0) {

					$product_cate_array[$keymain]['id'] = $innermain['cate_id'];	

					$product_cate_array[$keymain]['nome'] = $innermain['nome'];

					$product_cate_array[$keymain]['cat_mae'] = $innermain['cat_mae'];

					break 1;

				}else{



					$innermain_id = $innermain['id'];

					$query_rsCar = "SELECT id, nome, cat_mae  FROM l_categorias_en as cate WHERE cate.id = '$innermain_id' ORDER BY cate.id ASC";

					$rsCar = DB::getInstance()->prepare($query_rsCar);

					$rsCar->execute();

					$row_rsCar = $rsCar->fetchAll();

					$totalRows_rsCar = $rsCar->rowCount();

					foreach ($row_rsCar as $key => $inner) {

						if ($inner['cat_mae'] == 0) {

							$product_cate_array[$keymain] = $inner;

							break 2;	

						}else{

							$inner_id = $inner['id'];

							$query_rsCar = "SELECT id, nome, cat_mae FROM l_categorias_en as cate WHERE cate.id = '$inner_id' ORDER BY cate.id ASC";

							$rsCar = DB::getInstance()->prepare($query_rsCar);

							$rsCar->execute();

							$row_rsCar = $rsCar->fetchAll();

							$totalRows_rsCar = $rsCar->rowCount();

							$product_cate_array[$keymain] = $row_rsCar[0];

						}

					}	

				}

				

			}



		}



		// echo "<pre>";

		// print_r ($product_cate_array);

		// echo "</pre>";



		$newArray = array(); 

		$usedFruits = array(); 

		foreach ( $product_cate_array AS $key => $line ) { 

		    if ( !in_array($line['id'], $usedFruits) ) { 

		        $usedFruits[] = $line['id']; 

		        $newArray[$key] = $line; 

		    } 

		} 

		$products_cate_array = $newArray; 

		$newArray = NULL;

		$usedFruits = NULL;



		$final_new_array = array();

		foreach ($products_cate_array as $keygg => $value) {



			foreach ($product_cate_array as $key => $all_pro_cate) {

				if ($all_pro_cate['id'] == $value['id']) {

					$final_new_array[$keygg]['cate_data'] = $value;

					$final_new_array[$keygg]['cate_product'][] = $all_pro_cate['product_id'];

					//break 2;

				}

			}

			

		}



		$finalget_array = array();

		foreach ($final_new_array as $key => $value) {

			$finalget_array[$key]['cate_name'] = $value['cate_data']['nome'];

			$finalget_array[$key]['cate_product'] = $value['cate_product'];

		}



		$finalget_array = array_values(array_filter($finalget_array));

		

		$price = array();

		foreach ($finalget_array as $key => $row)

		{

		    $price[$key] = $row['cate_name'];

		}

		array_multisort($price, SORT_ASC, $finalget_array);



		if($imprime == 0) {	

			$rodape = email_social(1);

		}

		else {

			$rodape = "";

		}



		$formcontent = getHTMLTemplate("comprar.htm");

		

		$id_encomenda = $row_rsEncomenda['id'];

		$enc = $row_rsEncomenda['numero'];

		$nome = $row_rsEncomenda['nome'];

		$nome_env = $row_rsEncomenda['nome_envio'];

		$mor_env = $row_rsEncomenda['morada_envio'];

		$mor_fac = $row_rsEncomenda['morada_fatura'];

		$cpostal_env = $row_rsEncomenda['codpostal_envio'];

		$cpostal_fac = $row_rsEncomenda['codpostal_fatura'];

		$local_env = $row_rsEncomenda['localidade_envio'];

		$local_fac = $row_rsEncomenda['localidade_fatura'];

		$pais_fac = $row_rsEncomenda['pais_fatura'];

		$email = $row_rsEncomenda['email'];

		$pais = $row_rsEncomenda['pais_envio'];

		$telemovel = $row_rsEncomenda['telemovel'];

		$nif = $row_rsEncomenda['nif'];

		$portes_pag = $row_rsEncomenda['portes_pagamento'];

		$portes_env = $row_rsEncomenda['portes_entrega'];

		$pag = $row_rsEncomenda['pagamento'];

		$entrega = $row_rsEncomenda['entrega'];

		$observacoes = $row_rsEncomenda['observacoes'];

		$data = $row_rsEncomenda['data'];

		$data_change = date("d-m-Y h:i:sa", strtotime($data));

		$Order_date = date("d-m-Y", strtotime($data));


		$date_prepration = date('d-m-Y',date(strtotime("+".$row_rsCar_pre['prepare']." day", strtotime($data))));

	/*	$date_prepration = date('d-m-Y', strtotime($data. ' + ' .$row_rsCar_pre['prepare']. ' day'));*/

		$cancle_mes = $row_rsEncomenda['cancle_mes'];

		$total_enc = number_format($row_rsEncomenda['valor_c_iva'], 2, ',', '.')."&pound;";

		

		$valor_iva = $row_rsEncomenda['valor_iva'];

		$totale2 = $row_rsEncomenda['valor_total'];

		

		$segue_envio = "";

		$language = $row_rsEncomenda['lingua'];

		if(!$language) {

			$language = "pt";

		}

		

		include_once(ROOTPATH."linguas/".$language.".php");

    	$className = 'Recursos_'.$language;

		$Recursos = new $className();

			

		//Carregar notificações para os estados da encomenda

		$query_rsNotificacao = "SELECT * FROM textos_notificacoes_".$language." WHERE estado = :estado";

		$rsNotificacao = DB::getInstance()->prepare($query_rsNotificacao);

		$rsNotificacao->bindParam(':estado', $row_rsEncomenda['estado'], PDO::PARAM_INT);

		$rsNotificacao->execute();

		$row_rsNotificacao = $rsNotificacao->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsNotificacao = $rsNotificacao->rowCount();

		

		$subject = $row_rsNotificacao['assunto'];

		if(!$subject) {

			$subject = $Recursos->Resources["nova_encomenda_tit"];

		}



		$desc = $row_rsNotificacao['texto'];

		if(!$desc) {

			$desc = $Recursos->Resources["nova_encomenda_txt"];

		}

		

		if($row_rsEncomenda["estado"] == 2) { //ENCOMENDA EM PROCESSAMENTO		

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_proc_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_proc_txt"];

			}

		}

		else if($row_rsEncomenda["estado"] == 3) { //ENCOMENDA ENVIADA	

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_env_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_env_txt"];

			}

			

			if($row_rsEncomenda["envio_link"]!='' && $row_rsEncomenda["envio_ref"]!='') {

				$segue_envio = "Código: ".$row_rsEncomenda["envio_ref"]."<br>"."URL: ".$row_rsEncomenda["envio_link"];

				$segue_envio = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

					  <tbody>

							<tr>

								<td colspan="2" height="5"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" height="5" /></td>

							</tr>

							<tr>

							  <td class="textos" width="50">'.$Recursos->Resources["seguir_envio_cod"].'</td>

							  <td class="textos"><strong>'.$row_rsEncomenda['envio_ref'].'</strong></td>

							</tr>

							<tr>

							  <td class="textos">URL</td>

							  <td class="textos"><a href="'.$row_rsEncomenda["envio_link"].'" target="_blank"><strong>'.$Recursos->Resources["seguir_envio_clique"].'</strong></a></td>

							</tr>



					  </tbody>

					</table>';

			}		

		}

		else if($row_rsEncomenda["estado"] == 5) { //ENCOMENDA ANULADA	

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_canc_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_canc_txt"];

				//$desc = $Recursos->Resources["encomenda_canc_txt"];





				

			}

		}

		else if($row_rsEncomenda["estado"] == 6) { //ENCOMENDA PRONTA PARA LEVANTAMENTO	

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_pronta_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_pronta_txt"];



			}



		}



		//Substituir possíveis tags no texto

		$subject = str_replace("#nome#", $nome, $subject);	

		$subject = str_replace("#enc#", $enc, $subject);	

		$subject = str_replace("#total#", $total_enc, $subject);	

		$subject = str_replace("#data#", $data, $subject);	



		$desc = str_replace("#nome#", $nome, $desc);	

		$desc = str_replace("#enc#", $enc, $desc);	

		$desc = str_replace("#total#", $total_enc, $desc);	

		$desc = str_replace("#data#", $data, $desc);	

			

		/* TEXTO DE INTRODUÇÃO */	



		$msg_intro = '';

		if($imprime == 0) {

			$msg_intro = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #ebeced;">

			  <tbody>

					<tr>

					  <td colspan="3" height="45">&nbsp;</td>

					</tr>

					<tr>

					  <td width="15"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" width="15" /></td>

					  <td align="left" valign="top" class="intro" style="line-height:25px">#car_mail_intro#</td>

					  <td width="15"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" width="15" /></td>

					</tr>

					<tr>

					  <td colspan="3" height="45">&nbsp;</td>

					</tr>

			  </tbody>

			</table>

			';

		}

		

		/* DADOS DE FATURAÇÃO */



		$canceltale="";

		if($cancle_mes != ""){

			$canceltale='<table width="100%" border="1" cellspacing="0" cellpadding="0">

		 	<tbody>

		 		<tr>

		 			<td class="titulos">'.$Recursos->Resources["reason_of_calcle_mes"].' <br> <h5>'.$cancle_mes.'</h5></td>

		 		</tr>

		 	</tbody>

		</table>';

		}
		$mailStoredetail = $canceltale.'

		<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tbody>
					<tr>

					  <td class="titulos">Store Details</td>

					</tr>

					<tr>

					  <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

					</tr>

					<tr>

					  <td class="textos">
							<strong>'.$Recursos->Resources["comprar_nome"].':</strong> '.$row_rsstore["b_name"].'<br>

							<strong>'.$Recursos->Resources["ar_morada"].':</strong> '.$row_rsstore["Sreet"].'<br>

							<strong>'.$Recursos->Resources["comprar_postal"].':</strong> '.$row_rsstore["pincode"].'<br>

							<strong>City:</strong> '.$row_rsstore["city"].'<br>

							<strong>'.$Recursos->Resources["comprar_localidade"].':</strong> '.$row_rsstore["country"].'<br>

							<strong>Contact:</strong> '.$row_rsstore["phone"].'<br>

							<strong>'.$Recursos->Resources["comprar_email"].':</strong> '.$row_rsstore["email"].'

					  </td>

					</tr>

			  </tbody>

			</table>';

		$mailFaturacao = $canceltale.'

		<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tbody>
					<tr>

					  <td class="titulos">'.$Recursos->Resources["dados_faturacao"].'</td>

					</tr>

					<tr>

					  <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

					</tr>

					<tr>

					  <td class="textos">
							<strong>'.$Recursos->Resources["comprar_nome"].':</strong> '.$nome.'<br>
							<strong>'.$Recursos->Resources["ar_morada"].':</strong> '.nl2br($mor_fac).'<br>
							<strong>'.$Recursos->Resources["comprar_postal"].':</strong> '.$cpostal_fac.'<br>
							<strong>'.$Recursos->Resources["comprar_localidade"].':</strong> '.$local_fac.'<br>
							<strong>'.$Recursos->Resources["ar_pais"].':</strong> '.$pais_fac.'
							<br>
							<strong>'.$Recursos->Resources["comprar_email"].':</strong> '.$email.'<br>
					  </td>

					</tr>

			  </tbody>

			</table>';

		

		/* DADOS DE ENVIO */
		if($row_rsEncomenda['entrega_id'] == 14){

		$mailEnvio = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tbody>

					<tr>

					  <td class="titulos">'.$Recursos->Resources["dados_envio"].'</td>

					</tr>

					<tr>

					  <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

					</tr>

					<tr>

					  <td class="textos">

							<strong>'.$Recursos->Resources["comprar_nome"].':</strong> '.$nome_env.'<br>

							<strong>'.$Recursos->Resources["ar_morada"].':</strong> '.nl2br($mor_env).'<br>

							<strong>'.$Recursos->Resources["comprar_postal"].':</strong> '.$cpostal_env.'<br>

							<strong>'.$Recursos->Resources["comprar_localidade"].':</strong> '.$local_env.'<br>

							<strong>'.$Recursos->Resources["ar_pais"].':</strong> '.$pais.'

							<br>

							<strong>'.$Recursos->Resources["comprar_contacto"].':</strong> '.$telemovel.'

					  </td>

					</tr>

			  </tbody>

			</table>';

		}

		/* MÉTODO PAGAMENTO */

		$query_rsPagamento = "SELECT * FROM met_pagamento_".$language." WHERE id='$row_rsEncomenda[met_pagamt_id]'";

		$rsPagamento = DB::getInstance()->prepare($query_rsPagamento);

		$rsPagamento->execute();

		$row_rsPagamento = $rsPagamento->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPagamento = $rsPagamento->rowCount();

		

		$nome_pag = $row_rsPagamento['nome'];

		$img_pag = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/geral.png" width="50" />';

		if($row_rsPagamento['imagem'] != '' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsPagamento['imagem'])) { 

			$img_pag = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/'.$row_rsPagamento['imagem'].'" width="50" />';

		}

		

		if($row_rsEncomenda['estado'] == 1) {		

			if($row_rsEncomenda['met_pagamt_id'] == 1) {

				$pagP = '<table border="0" cellpadding="0" cellspacing="0" width="256" style="width:256px"> 

					<tr>

						<td height="35" valign="middle" align="center" bgcolor="'.COR_SITE.'"><a href="'.$link_paypal.'" target="_blank" class="button">'.$Recursos->Resources["car_comprar_paypal"].'</a></td>

					</tr>

				</table>';

			}

			else if($row_rsEncomenda['met_pagamt_id'] == 6 || $row_rsEncomenda['met_pagamt_id'] == 7) {

				$ref_pag = $row_rsEncomenda['ref_pagamento'];

				$ref_pagamento = substr($ref_pag, 0, 3)." ".substr($ref_pag, 3, 3)." ".substr($ref_pag, 6, 3);

				

				$pagP = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

					  <tbody>

					  	<tr>

								<td colspan="2" height="5"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" height="5" /></td>

							</tr>

							<tr>

							  <td class="textos" width="100">'.$Recursos->Resources["comprar4_entidade"].'</td>

							  <td class="textos"><strong>'.$row_rsEncomenda['entidade'].'</strong></td>

							</tr>

							<tr>

							  <td class="textos" width="100">'.$Recursos->Resources["comprar4_referencia"].'</td>

							  <td class="textos"><strong>'.$ref_pagamento.'</strong></td>

							</tr>

							<tr>

							  <td class="textos" width="100">'.$Recursos->Resources["comprar4_montante"].'</td>

							  <td class="textos"><strong>'.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['valor_c_iva']).'</strong></td>

							</tr>

					  </tbody>

					</table>';

			}

			else if($row_rsEncomenda['met_pagamt_id'] == 8) {

				$pagP = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:256px"> 

					<tr>

						<td height="35" valign="middle" align="center" bgcolor="'.COR_SITE.'"><a href="'.$row_rsEncomenda['url_pagamento'].'" target="_blank" class="button">'.$Recursos->Resources["car_comprar_easypay"].'</a></td>

					</tr>

				</table>';

			}

			else if($row_rsPagamento['descricao']) { 

				$pagP = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 256px"> 

					<tr>

						<td class="textos">'.$row_rsPagamento['descricao'].'</td>

					</tr>

				</table>';

			}



			if($row_rsPagamento['descricao2']) {

				$pagP .= '<table border="0" cellpadding="0" cellspacing="0" width="100%"> 

					<tr>

						<td height="10"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" height="10"/></td>

					</tr>

					<tr>

						<td class="textos">'.$row_rsPagamento['descricao2'].'</td>

					</tr>

				</table>';

			}

		}


		$preparation_date = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">Prepration Date</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td>Order Date : '.$Order_date.'</td>

														</tr>

														<tr>

														  <td>Prepration Date : '.$date_prepration.'</td>

														</tr>

												  </tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td class="textos">

										  	<br>

												'.$pagP.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>

        ';

		$mailPagamento = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">'.$Recursos->Resources["comprar_pagamento"].'</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td width="50">'.$img_pag.'</td>

														  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

														  <td align="left" class="textos">'.$nome_pag.'</td>

														</tr>

												  </tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td class="textos">

										  	<br>

												'.$pagP.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>

        ';

		

		/* MÉTODO ENVIO */
if($row_rsEncomenda['entrega_id'] == 14){
		if($verifica_encomenda == 1) {

			if($row_rsEncomenda['entrega_id'] > 0) {

				$query_rsEntrega = "SELECT * FROM met_envio_".$language." WHERE id='$row_rsEncomenda[entrega_id]'";

				$rsEntrega = DB::getInstance()->prepare($query_rsEntrega);

				$rsEntrega->execute();

				$row_rsEntrega = $rsEntrega->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsEntrega = $rsEntrega->rowCount();

				

				$nome_ent = $row_rsEntrega['nome'];

				$desc_ent = "";

				if($row_rsEntrega['descricao']) {

					$desc_ent .= $row_rsEntrega['descricao'];

				}

				

				$img_ent = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/geral.png" width="50" />';

				if($row_rsEntrega['imagem'] != '' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsEntrega['imagem'])) { 

					$img_ent = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/'.$row_rsEntrega['imagem'].'" width="50" />';

				}

			}

			$mailEntrega = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">'.$Recursos->Resources["comprar_entrega"].'</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td width="50">'.$img_ent.'</td>

														  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

														  <td align="left" class="textos">'.$nome_ent.'</td>

														</tr>

													</tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

											<td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

											<td class="textos">

										  	<br>

												'.$desc_ent.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										';



			if($segue_envio) {

				$mailEntrega .= '<tr>

										  <td colspan="3" height="25"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="25" /></td>

										</tr>

										<tr>

											<td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td colspan="3" class="titulos">'.$Recursos->Resources["seguir_envio"].'</td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

											<td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												'.$segue_envio.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										';

			}

			

			$mailEntrega .= '<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>';

		}
	}

		else {

			$mailEntrega = '';

		}

	

		/* OBSERVAÇÕES */

		$mailObs = '';

		if($observacoes) {

			$mailObs = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">'.$Recursos->Resources["obs_encomenda"].'</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td colspan="3" class="textos">'.nl2br($observacoes).'</td>

														</tr>

												  </tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>';

		}

		

		/* PRODUTOS */

		$mailprodutos = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

        <tbody>

        	<tr>

            <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

          </tr>

          <tr>

            <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

            <td class="titulos">'.$Recursos->Resources["comprar_resumo"].'</td>

            <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

          </tr>

          <tr>

            <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

          </tr>

        </tbody>

      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7; border-top: 0;">

        <tbody>

        	<tr>

            <td colspan="4" height="7"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="7" /></td>

          </tr>

          <tr>

            <td class="subtitulos" style="padding-left: 15px;">'.$Recursos->Resources["cart_prod"].'</td>

            <td width="100" class="subtitulos" style="text-align: center;">Unit Price</td>

			<td width="100" class="subtitulos" style="text-align: center;">VAT</td>

			<td width="100" class="subtitulos" style="text-align: center;">Unit Price (Incl. VAT)</td>

            <td width="100" class="subtitulos" style="text-align: center;">Quantity</td>

            <td width="100" class="subtitulos" style="text-align: center;">'.$Recursos->Resources["car_sub_total"].'(Excl. VAT)</td>

          </tr>

          <tr>

            <td colspan="4" height="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

          </tr>

        </tbody>  

      </table>

			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid #e7e7e7;">

        <tbody>

          <tr>

            <td>';

		

		$count = 0;



		foreach ($finalget_array as $key => $value) {

			$cate_name = $value['cate_name'];

			$check_product = $value['cate_product'];

			

			$mailprodutos .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #e7e7e7;">

              	<tbody>

	                <tr>

	                  <td colspan="5"><h5 style="text-align:center; padding-top:12px; padding-bottom:12px; margin:0;">'.$cate_name.'</h5></td>

	                </tr>

                <tbody>

            </table>'

                ;



        	foreach($row_rsCarrinhoFinal as $produtos) {

				$produto_id = $produtos['produto_id'];

				if (in_array($produto_id, $check_product) == false) {

				 	continue 1;

				} 

				$count++;

				

				$query_rsProc = "SELECT * FROM l_pecas_en WHERE id=:id";

				$rsProc = DB::getInstance()->prepare($query_rsProc);

				$rsProc->bindParam(':id', $produto_id, PDO::PARAM_INT);

				$rsProc->execute();

				$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsProc= $rsProc->rowCount();

				// echo "<pre>";

				// print_r ($row_rsProc);

				// echo "</pre>";

				// echo "<pre>";

				// print_r ($row_rsProc);

				// echo "</pre>";



				$image = $produtos['imagem1'];

				$nome_prod = $row_rsProc['nome'];

				$ref_prod = "Ref.: <strong>".$row_rsProc['ref']."</strong>";

				$codigo = $produtos['ref'];

				$quantidade = $produtos['qtd'];

				$preco = $produtos['preco'];

				$opcoes = $produtos['opcoes'];

				$pack_obs = $produtos['pack_obs'];



				$reguler_Price = $produtos["preco"];

				$vat = $produtos['iva'];



				$totlePrice =  $reguler_Price * $quantidade;



				$unitprice =$reguler_Price - ($vat*($reguler_Price/100));



			    $vat_price2 = $totlePrice - ($vat*($totlePrice/100));

			    $total_price2 += $vat_price2;



				//$subtotal += $vat_price;



				$vat1 = $produtos["iva"] / 100;			

			    $vat_price1 = ($vat1 * $totlePrice);

			    $total_vat += $vat_price1;



				

				$new_path = explode("/", $image);

				$new_path = ROOTPATH."imgs/produtos/".end($new_path);



				$horVert = tamanho_imagem2($new_path, 50, 50);

				

				$image_prod = '<img src="'.$image.'" width="50" />';

				if($horVert == "height"){

					$image_prod = '<img src="'.$image.'" height="50" />';

				}

				

				$border = ' style="border-bottom:1px solid #e7e7e7;"';

				if($count == $totalRows_rsCarrinhoFinal) {

					$border = "";

				}

				

				$opcoes2 = explode("<br>", str_replace(";", ",", $opcoes));

				$opcoes_mail = "";

				foreach($opcoes2 as $opcao) {

					$opcao = explode(":", $opcao);

					$opcoes_mail .= $opcao[1]." ";

				}



				$query_rsP = "SELECT * FROM pickup WHERE id='1'";

				$rsP = DB::getInstance()->prepare($query_rsP);

				$rsP->execute();

				$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsP = $rsP->rowCount();

				$discount = $row_rsP["discount"] / 100;



				$reguler_total = $row_rsEncomenda["valor_c_iva"];



				$discount_total = $reguler_total - ($reguler_total * $discount);



				$dicsount_midd = $reguler_total -  $discount_total;



				$mailprodutos .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"'.$border.'>

	              <tbody>

	                <tr>

	                  <td colspan="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

	                </tr>

	                <tr>

	                  <td width="2%"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

	                  <td>

	                  	<table width="100%" border="0" cellspacing="0" cellpadding="0">

	                      <tbody>

	                        <tr>

	                          <td width="50">'.$image_prod.'</td>

	                          <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

	                          <td>

	                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

	                              <tbody>

	                              	<tr>

	                                  <td colspan="3" class="ref_produto">'.$ref_prod.'</td>

	                                </tr>

	                                <tr>

	                                  <td colspan="3" class="nome_produto">'.$nome_prod.'</td>

	                                </tr>

	                                <tr>

	                                  <td colspan="3" class="opcoes_produto">'.$pack_obs.'</td>

	                                </tr>

	                                <tr>

	                                  <td colspan="3" class="opcoes_produto">'.$opcoes_mail.'</td>

	                                </tr>

	                                <tr>

	                                  <td colspan="3" height="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

	                                </tr>

	                              </tbody>

	                            </table>

	                          </td>

	                        </tr>

	                      </tbody>

	                    </table>

	                  </td>

	                   <td width="100" class="price_produto" style="text-align: center;">

	                  	'.self::mostraPrecoEnc($id_encomenda, $unitprice).'

	                  </td>

	                   <td width="100" class="qtd_produto" style="text-align: center;">';

	                  	$mailprodutos .= (!empty($produtos['iva'])) ? $produtos['iva'].'%' : '0%';

	                 $mailprodutos .= '



	                 </td>

	                  <td width="100" class="price_produto" style="text-align: center;">

	                  	'.self::mostraPrecoEnc($id_encomenda, $reguler_Price).'

	                  </td>

	    

	                  <td width="100" class="qtd_produto" style="text-align: center;">

	                  	'.$quantidade.'

	                  </td>



	                  <td width="100" class="price_produto" style="text-align: center;">

	                  	'.self::mostraPrecoEnc($id_encomenda, $preco * $quantidade).'

	                  </td>

	                </tr>

	                <tr>

	                  <td colspan="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

	                </tr>

	              </tbody>

	            </table>

	          ';

			}



		}



		$mailprodutos .= '</td>

          </tr>

        </tbody>

      </table>';

				

		/* TOTAIS */		

		$tabela_totais = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tbody>

				<tr>

				  <td colspan="2"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

				</tr>

				<tr>

				  <td align="right">

						<table width="100%" border="0" cellspacing="0" cellpadding="0">

						  <tbody>

								<tr>

								  <td align="right" class="infos"><strong>'.$Recursos->Resources['car_sub_total'].' (Excl. VAT)</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $total_price2).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>

								<tr>

								  <td align="right" class="infos"><strong>VAT Price</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $total_vat).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>

								<tr>

								  <td align="right" class="infos"><strong>'.$Recursos->Resources['car_sub_total'].' (Incl. VAT)</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['valor_c_iva']).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>

								';



		if($row_rsEncomenda['valor_c_iva'] > 50 &&  $row_rsEncomenda["pickup_data"] != "" && $row_rsEncomenda["deliverystatus"] == 0 && $rolecheck != "franchise")

		{ 

			$tabela_totais .= 	'<tr>

								  <td align="right" class="infos"><strong>Discount Apply('.$row_rsP["discount"].'%)</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $dicsount_midd).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>';

								 } 

				

		if($row_rsEncomenda["compra_valor_saldo"] > 0) {

			$tabela_totais .= '<tr>

								  <td align="right" class="infos"><strong>'.$Recursos->Resources['comprar_saldo'].'</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>- '.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda["compra_valor_saldo"]).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>

								';

		}



		if($row_rsEncomenda['codigo_promocional'] && $row_rsEncomenda['codigo_promocional_desconto'] > 0 && $row_rsEncomenda['codigo_promocional_valor'] > 0) {

			$tabela_totais .= '<tr>

								  <td align="right" class="infos"><strong>'.$Recursos->Resources["codigo_promocional"]." (".$row_rsEncomenda["codigo_promocional"].")".'</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>- '.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda["codigo_promocional_valor"]).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>

								';

		}



		$tabela_totais .= '<tr>

								  <td align="right" class="infos"><strong>'.$Recursos->Resources['portes_de_pagamento'].'</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>+ '.self::mostraPrecoEnc($id_encomenda, $portes_pag).'</strong></td>

								</tr>

								<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

								</tr>

								<tr>

								  <td align="right" class="infos"><strong>'.$Recursos->Resources['portes_de_envio'].'</strong></td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="infos" width="100"><strong>';



	  if($portes_env >= 0) {

	  	$tabela_totais .= '+ ';

	  }

	  else {

	  	$tabela_totais .= '- ';

	  }



		$tabela_totais .= self::mostraPrecoEnc($id_encomenda, abs($portes_env)).'</strong></td>

								</tr>

								';

		$query_rsP = "SELECT * FROM pickup WHERE id='1'";

		$rsP = DB::getInstance()->prepare($query_rsP);

		$rsP->execute();

		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsP = $rsP->rowCount();

		$discount = $row_rsP["discount"] / 100;



		$reguler_total = $row_rsEncomenda["valor_c_iva"];



		$discount_total = $reguler_total - ($reguler_total * $discount);



		if($row_rsEncomenda['valor_c_iva'] > 50  &&  $row_rsEncomenda["pickup_data"] != "" && $row_rsEncomenda["deliverystatus"] == 0 && $rolecheck != "franchise"){ 



		$tabela_totais .= '<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

								</tr>

								<tr>

								  <td align="right" class="total">'.$Recursos->Resources['car_total'].'</td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="total" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $discount_total).'</strong></td>

								</tr>

						  </tbody>

						</table>

				  </td>

				  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

				</tr>

				<tr>

				  <td colspan="2"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

				</tr>

		  </tbody>

		</table>';

		}

		else

		{
			$tabela_totais .= '<tr>

								  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

								</tr>

								<tr>

								  <td align="right" class="total">'.$Recursos->Resources['car_total'].'</td>

								  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

								  <td align="right" class="total" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $reguler_total).'</strong></td>

								</tr>

						  </tbody>

						</table>

				  </td>

				  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

				</tr>

				<tr>

				  <td colspan="2"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

				</tr>

		  </tbody>

		</table>';	

		}

		if($row_rsEncomenda['entrega_id'] == 14){
			
		$tabela_totais .= '<h3 align="center"><strong>Note :</strong> delivery between 4pm and 7pm </h3>';

		}
		/*<div style="background-color: red;"><h3>tel: +'.$row_rsstore["phone"].'</h3><h3>Opening Hours: '.$cur_start.' to '.$cur_end.'</h3></div>*/
		/* EXTRAS */

		$tabela_extras = "";

		if($row_rsEncomenda['pontos_compra'] > 0) {

			$tabela_extras .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tbody>

					<tr>

					  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

					  <td height="25" valign="middle" align="right" class="extras">'.str_replace("###", number_format($row_rsEncomenda['pontos_compra'], 0, '.', ''), $Recursos->Resources["pontos_acumular"]).'</td>

					  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

					</tr>

			  </tbody>

			</table>';

		}

		if($row_rsEncomenda['saldo_compra'] > 0) {

			$tabela_extras .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tbody>

					<tr>

					  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

					  <td height="25" valign="middle" align="right" class="extras">'.str_replace("###", self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['saldo_compra']), $Recursos->Resources["saldo_acumular"]).'</td>

					  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

					</tr>

			  </tbody>

			</table>';

		}



		/* TEXTOS */

		$tabela_textos = '';

		if($imprime == 0) {

			$tabela_textos = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tbody>

				  <tr>

					  <td height="30"></td>

					</tr>

					<tr>

					  <td align="center" class="textos">'.$Recursos->Resources["comprar4_texto"].'</td>

					</tr>

					<tr>

					  <td height="20">&nbsp;</td>

					</tr>

					<tr>

					  <td align="center" class="cumprimentos">'.$Recursos->Resources["car_mail_7"].'</td>

					</tr>

					<tr>

					  <td align="center" class="nome_site">'.NOME_SITE.'</td>

					</tr>

					<tr>

					  <td height="40">&nbsp;</td>

					</tr>

					<tr>

					  <td>

							<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#c7c7c7">

							  <tbody>

									<tr>

									  <td colspan="3" height="20"></td>

									</tr>

									<tr>

									  <td width="30%" align="center"><a class="textos_rodape" href="'.ROOTPATH_HTTP.'area-reservada.php">'.$Recursos->Resources["car_mail_cliente"].'</a></td>

									  <td align="center"><a class="textos_rodape" href="'.ROOTPATH_HTTP.'contactos.php">'.$Recursos->Resources["car_mail_maisinfo"].'</a></td>

									  <td align="center" width="30%"><a class="textos_rodape" href="'.ROOTPATH_HTTP.'">www.'.SERVIDOR.'</a></td>

									</tr>

									<tr>

									  <td colspan="3" height="20"></td>

									</tr>

							  </tbody>

							</table>

					  </td>

					</tr>

					<tr>

					  <td height="25"></td>

					</tr>

			  </tbody>

			</table>';

		}

					

		$formcontent = str_replace ("#msg_intro#", $msg_intro, $formcontent);

		$formcontent = str_replace ("#car_mail_intro#", $desc, $formcontent);

		$formcontent = str_replace ("#car_resumo_enc#", $Recursos->Resources["car_resumo_enc"], $formcontent);
	
		/*$formcontent = str_replace ("#logo_round#",'<img src="'.ROOTPATH_HTTP.'imgs/elem/logo-round.png" width="50" />', $formcontent);*/
		if($row_rsEncomenda['entrega_id'] == 14){

		$formcontent = str_replace ("#shipping_type#","delivery", $formcontent);
		}
		else
		{

		$formcontent = str_replace ("#shipping_type#","Pickup", $formcontent);
		}
		$formcontent = str_replace ("#Branch_Name#","<strong>".$row_rsstore["b_name"]."</strong>", $formcontent);

		$formcontent = str_replace ("#car_num_enc#", $Recursos->Resources["car_num_enc"]." <strong>#".$row_rsEncomenda['numero']."</strong>", $formcontent);

		$formcontent = str_replace ("#car_data_enc#", $Recursos->Resources["car_data_enc"]." <strong>".$data_change."</strong>", $formcontent);

		$formcontent = str_replace ("#reason_of_calcle_mes#", $cancle_messge, $formcontent);

		$formcontent = str_replace ("#store_details#", $mailStoredetail, $formcontent);

		$formcontent = str_replace ("#dados_faturacao#", $mailFaturacao, $formcontent);

		$formcontent = str_replace ("#dados_envio#", $mailEnvio, $formcontent);

		$formcontent = str_replace ("#preparation_date#", $preparation_date, $formcontent);

		$formcontent = str_replace ("#car_pagamento#", $mailPagamento, $formcontent);

		$formcontent = str_replace ("#car_entrega#", $mailEntrega, $formcontent);

		$formcontent = str_replace ("#car_observacoes#", $mailObs, $formcontent);

		$formcontent = str_replace ("#encomenda_produtos#", $mailprodutos, $formcontent);



		$formcontent = str_replace ("#ctotais#", $tabela_totais, $formcontent);

		$formcontent = str_replace ("#cextras#", $tabela_extras, $formcontent);

		$formcontent = str_replace ("#ctextos#", $tabela_textos, $formcontent);

		$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

			

		if($imprime == 0) {	

			$query_rsCont = "SELECT * FROM notificacoes_".$language." WHERE id='3'";

			$rsCont = DB::getInstance()->prepare($query_rsCont);

			$rsCont->execute();

			$row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsCont = $rsCont->rowCount();

								

			$mail_cont = $row_rsCont['email'];		

			if($mail_cont) {			

			  sendMail($email, '', $formcontent, $subject, $subject);		

				sendMail($mail_cont, '', $formcontent, $subject, $subject, $row_rsCont['email2'], $row_rsCont['email3']);

			}

			

			$insertSQL = "UPDATE encomendas SET email_enviado='1' WHERE id='$id_encomenda'";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->execute();

		}

		else {

			return $formcontent;

		}



		DB::close();

	}



	public static function packingSlip($id, $imprime = 0) {

		global $extensao, $Recursos, $class_produtos;



		//Verificar se apenas existe um Cheque Prenda no carrinho ou se também existem produtos.

		$verifica_encomenda = self::verificaEncomenda($id);



		$query_rsEncomenda = "SELECT * FROM encomendas WHERE id = :id";

		$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);

		$rsEncomenda->bindParam(':id', $id, PDO::PARAM_INT); 

		$rsEncomenda->execute();

		$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsEncomenda = $rsEncomenda->rowCount();



		$link_paypal = "";

		if($row_rsEncomenda['url_paypal'] != "") {

			$link_paypal = $row_rsEncomenda['url_paypal'];

		}



		$query_rsCarrinhoFinal = "SELECT * FROM encomendas_produtos WHERE id_encomenda=:id ORDER BY qtd ASC";

		$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);

		$rsCarrinhoFinal->bindParam(':id', $id, PDO::PARAM_INT); 

		$rsCarrinhoFinal->execute();

		$row_rsCarrinhoFinal = $rsCarrinhoFinal->fetchAll(PDO::FETCH_ASSOC);

		$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();



		$product_cate_array = array();



		foreach ($row_rsCarrinhoFinal as $keymain => $value) {

			$getq_pro_id = $value['produto_id'];



			$query_rsCar = "SELECT categorias.nome, pecas_cate.id_categoria as cate_id, categorias.cat_mae FROM l_pecas_categorias as pecas_cate LEFT JOIN l_categorias_en as categorias ON (categorias.id = pecas_cate.id_categoria) WHERE pecas_cate.id_peca = '$getq_pro_id' ORDER BY pecas_cate.id ASC";



			$rsCar = DB::getInstance()->prepare($query_rsCar);

			$rsCar->execute();

			$row_rsCar = $rsCar->fetchAll();

			$totalRows_rsCar = $rsCar->rowCount();



			$product_cate_array[$keymain]['product_id'] = $getq_pro_id;

			

			foreach ($row_rsCar as $key => $innermain) {

				if ($innermain['cat_mae'] == 0) {

					$product_cate_array[$keymain]['id'] = $innermain['cate_id'];	

					$product_cate_array[$keymain]['nome'] = $innermain['nome'];

					$product_cate_array[$keymain]['cat_mae'] = $innermain['cat_mae'];

					break 1;

				}else{



					$innermain_id = $innermain['id'];

					$query_rsCar = "SELECT id, nome, cat_mae  FROM l_categorias_en as cate WHERE cate.id = '$innermain_id' ORDER BY cate.id ASC";

					$rsCar = DB::getInstance()->prepare($query_rsCar);

					$rsCar->execute();

					$row_rsCar = $rsCar->fetchAll();

					$totalRows_rsCar = $rsCar->rowCount();

					foreach ($row_rsCar as $key => $inner) {

						if ($inner['cat_mae'] == 0) {

							$product_cate_array[$keymain] = $inner;

							break 2;	

						}else{

							$inner_id = $inner['id'];

							$query_rsCar = "SELECT id, nome, cat_mae FROM l_categorias_en as cate WHERE cate.id = '$inner_id' ORDER BY cate.id ASC";

							$rsCar = DB::getInstance()->prepare($query_rsCar);

							$rsCar->execute();

							$row_rsCar = $rsCar->fetchAll();

							$totalRows_rsCar = $rsCar->rowCount();

							$product_cate_array[$keymain] = $row_rsCar[0];

						}

					}	

				}

				

			}



		}



		// echo "<pre>";

		// print_r ($product_cate_array);

		// echo "</pre>";



		$newArray = array(); 

		$usedFruits = array(); 

		foreach ( $product_cate_array AS $key => $line ) { 

		    if ( !in_array($line['id'], $usedFruits) ) { 

		        $usedFruits[] = $line['id']; 

		        $newArray[$key] = $line; 

		    } 

		} 

		$products_cate_array = $newArray; 

		$newArray = NULL;

		$usedFruits = NULL;



		$final_new_array = array();

		foreach ($products_cate_array as $keygg => $value) {



			foreach ($product_cate_array as $key => $all_pro_cate) {

				if ($all_pro_cate['id'] == $value['id']) {

					$final_new_array[$keygg]['cate_data'] = $value;

					$final_new_array[$keygg]['cate_product'][] = $all_pro_cate['product_id'];

					//break 2;

				}

			}

			

		}



		$finalget_array = array();

		foreach ($final_new_array as $key => $value) {

			$finalget_array[$key]['cate_name'] = $value['cate_data']['nome'];

			$finalget_array[$key]['cate_product'] = $value['cate_product'];

		}



		$finalget_array = array_values(array_filter($finalget_array));

		

		$price = array();

		foreach ($finalget_array as $key => $row)

		{

		    $price[$key] = $row['cate_name'];

		}

		array_multisort($price, SORT_ASC, $finalget_array);



		// echo "<pre>";

		// print_r ($price);

		// echo "</pre>";



		if($imprime == 0) {	

			$rodape = email_social(1);

		}

		else {

			$rodape = "";

		}



		$formcontent = getHTMLTemplate("packingslip.htm");



		$id_cliente = $row_rsEncomenda['id_cliente'];



		$query_rsCliente  = "SELECT * FROM clientes WHERE id=:id_cliente ORDER BY id ASC";

		$rsCliente = DB::getInstance()->prepare($query_rsCliente);

		$rsCliente->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT); 

		$rsCliente->execute();

		$row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsCliente = $rsCliente->rowCount();

		

		$id_encomenda = $row_rsEncomenda['id'];

		$enc = $row_rsEncomenda['numero'];

		$nome = $row_rsEncomenda['nome'];

		$nome_env = $row_rsEncomenda['nome_envio'];

		$mor_env = $row_rsEncomenda['morada_envio'];

		$mor_fac = $row_rsEncomenda['morada_fatura'];

		$cpostal_env = $row_rsEncomenda['codpostal_envio'];

		$cpostal_fac = $row_rsEncomenda['codpostal_fatura'];

		$local_env = $row_rsEncomenda['localidade_envio'];

		$local_fac = $row_rsEncomenda['localidade_fatura'];

		$pais_fac = $row_rsEncomenda['pais_fatura'];

		$email = $row_rsEncomenda['email'];

		$pais = $row_rsEncomenda['pais_envio'];

		$telemovel = $row_rsEncomenda['telemovel'];

		$nif = $row_rsEncomenda['nif'];

		$portes_pag = $row_rsEncomenda['portes_pagamento'];

		$portes_env = $row_rsEncomenda['portes_entrega'];

		$pag = $row_rsEncomenda['pagamento'];

		$entrega = $row_rsEncomenda['entrega'];

		$observacoes = $row_rsEncomenda['observacoes'];

		//$data = $row_rsEncomenda['data'];

		$cancle_mes = $row_rsEncomenda['cancle_mes'];

		$total_enc = number_format($row_rsEncomenda['valor_c_iva'], 2, ',', '.')."&pound;";

		

		$valor_iva = $row_rsEncomenda['valor_iva'];

		$totale2 = $row_rsEncomenda['valor_total'];

		

		$segue_envio = "";

		$language = $row_rsEncomenda['lingua'];

		if(!$language) {

			$language = "pt";

		}

		

		include_once(ROOTPATH."linguas/".$language.".php");

    	$className = 'Recursos_'.$language;

		$Recursos = new $className();

			

		//Carregar notificações para os estados da encomenda

		$query_rsNotificacao = "SELECT * FROM textos_notificacoes_".$language." WHERE estado = :estado";

		$rsNotificacao = DB::getInstance()->prepare($query_rsNotificacao);

		$rsNotificacao->bindParam(':estado', $row_rsEncomenda['estado'], PDO::PARAM_INT);

		$rsNotificacao->execute();

		$row_rsNotificacao = $rsNotificacao->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsNotificacao = $rsNotificacao->rowCount();

		

		$subject = $row_rsNotificacao['assunto'];

		if(!$subject) {

			$subject = $Recursos->Resources["nova_encomenda_tit"];

		}



		$desc = $row_rsNotificacao['texto'];

		if(!$desc) {

			$desc = $Recursos->Resources["nova_encomenda_txt"];

		}

		

		if($row_rsEncomenda["estado"] == 2) { //ENCOMENDA EM PROCESSAMENTO		

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_proc_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_proc_txt"];

			}

		}

		else if($row_rsEncomenda["estado"] == 3) { //ENCOMENDA ENVIADA	

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_env_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_env_txt"];

			}

			

			if($row_rsEncomenda["envio_link"]!='' && $row_rsEncomenda["envio_ref"]!='') {

				$segue_envio = "Código: ".$row_rsEncomenda["envio_ref"]."<br>"."URL: ".$row_rsEncomenda["envio_link"];

				$segue_envio = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

					  <tbody>

							<tr>

								<td colspan="2" height="5"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" height="5" /></td>

							</tr>

							<tr>

							  <td class="textos" width="50">'.$Recursos->Resources["seguir_envio_cod"].'</td>

							  <td class="textos"><strong>'.$row_rsEncomenda['envio_ref'].'</strong></td>

							</tr>

							<tr>

							  <td class="textos">URL</td>

							  <td class="textos"><a href="'.$row_rsEncomenda["envio_link"].'" target="_blank"><strong>'.$Recursos->Resources["seguir_envio_clique"].'</strong></a></td>

							</tr>



					  </tbody>

					</table>';

			}		

		}

		else if($row_rsEncomenda["estado"] == 5) { //ENCOMENDA ANULADA	

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_canc_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_canc_txt"];

				//$desc = $Recursos->Resources["encomenda_canc_txt"];





				

			}

		}

		else if($row_rsEncomenda["estado"] == 6) { //ENCOMENDA PRONTA PARA LEVANTAMENTO	

			$subject = $row_rsNotificacao['assunto'];

			if(!$subject) {

				$subject = $Recursos->Resources["encomenda_pronta_tit"];

			}



			$desc = $row_rsNotificacao['texto'];

			if(!$desc) {

				$desc = $Recursos->Resources["encomenda_pronta_txt"];



			}



		}



		//Substituir possíveis tags no texto

		$subject = str_replace("#nome#", $nome, $subject);	

		$subject = str_replace("#enc#", $enc, $subject);	

		$subject = str_replace("#total#", $total_enc, $subject);	

		$subject = str_replace("#data#", $data, $subject);	



		$desc = str_replace("#nome#", $nome, $desc);	

		$desc = str_replace("#enc#", $enc, $desc);	

		$desc = str_replace("#total#", $total_enc, $desc);	

		$desc = str_replace("#data#", $data, $desc);	

			

		/* TEXTO DE INTRODUÇÃO */	



		$msg_intro = '';

		if($imprime == 0) {

			$msg_intro = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px solid #ebeced;">

			  <tbody>

					<tr>

					  <td colspan="3" height="45">&nbsp;</td>

					</tr>

					<tr>

					  <td width="15"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" width="15" /></td>

					  <td align="left" valign="top" class="intro" style="line-height:25px">#car_mail_intro#</td>

					  <td width="15"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" width="15" /></td>

					</tr>

					<tr>

					  <td colspan="3" height="45">&nbsp;</td>

					</tr>

			  </tbody>

			</table>

			';

		}

		

		/* DADOS DE FATURAÇÃO */



		// $canceltale="";

		// if($cancle_mes != ""){

		// 	$canceltale='<table width="100%" border="1" cellspacing="0" cellpadding="0">

		//  	<tbody>

		//  		<tr>

		//  			<td class="titulos">'.$Recursos->Resources["reason_of_calcle_mes"].' <br> <h5>'.$cancle_mes.'</h5></td>

		//  		</tr>

		//  	</tbody>

		// 	</table>';

		// }



		if ($row_rsCliente["roll"] == 'franchise') {

		}



		//$canceltale="";

			$canceltale ='<table width="100%" border="0" cellspacing="0" cellpadding="0">

		 	<tbody>

		 		<tr>

		 			<td ><strong>Branch Name:</strong> '.$row_rsCliente["branch_name"].'</td>

		 		</tr>

		 	</tbody>

			</table>';


		// $mailFaturacao = $canceltale.'<br>

		// <table width="100%" border="0" cellspacing="0" cellpadding="0">

		// 	  <tbody>

		// 			<tr>

		// 			  <td class="titulos">'.$Recursos->Resources["dados_faturacao"].'</td>

		// 			</tr>

		// 			<tr>

		// 			  <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

		// 			</tr>

		// 			<tr>

		// 			  <td class="textos">

					  		

		// 					<strong>'.$Recursos->Resources["comprar_nome"].':</strong> '.$nome.'<br>

		// 					<strong>'.$Recursos->Resources["ar_morada"].':</strong> '.nl2br($mor_fac).'<br>

		// 					<strong>'.$Recursos->Resources["comprar_postal"].':</strong> '.$cpostal_fac.'<br>

		// 					<strong>'.$Recursos->Resources["comprar_localidade"].':</strong> '.$local_fac.'<br>

		// 					<strong>'.$Recursos->Resources["ar_pais"].':</strong> '.$pais_fac.'

		// 					<br><br>

		// 					<strong>Role:</strong> '.$row_rsCliente["roll"].'<br>';

		// 					if ($row_rsCliente["roll"] == 'franchise') {

		// 						$mailFaturacao .= '<strong>Branch Name:</strong> '.$row_rsCliente["branch_name"].'<br>';

		// 					} 

		// 					$mailFaturacao .= '<br>

		// 					<strong>'.$Recursos->Resources["comprar_email"].':</strong> '.$email.'<br>

		// 					<strong>'.$Recursos->Resources["comprar_nif"].':</strong> '.$nif.'

		// 			  </td>

		// 			</tr>

		// 	  </tbody>

		// 	</table>';

		

		// /* DADOS DE ENVIO */

		// $mailEnvio = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

		// 	  <tbody>

		// 			<tr>

		// 			  <td class="titulos">'.$Recursos->Resources["dados_envio"].'</td>

		// 			</tr>

		// 			<tr>

		// 			  <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

		// 			</tr>

		// 			<tr>

		// 			  <td class="textos">

		// 					<strong>'.$Recursos->Resources["comprar_nome"].':</strong> '.$nome_env.'<br>

		// 					<strong>'.$Recursos->Resources["ar_morada"].':</strong> '.nl2br($mor_env).'<br>

		// 					<strong>'.$Recursos->Resources["comprar_postal"].':</strong> '.$cpostal_env.'<br>

		// 					<strong>'.$Recursos->Resources["comprar_localidade"].':</strong> '.$local_env.'<br>

		// 					<strong>'.$Recursos->Resources["ar_pais"].':</strong> '.$pais.'

		// 					<br><br>

		// 					<strong>'.$Recursos->Resources["comprar_contacto"].':</strong> '.$telemovel.'

		// 			  </td>

		// 			</tr>

		// 	  </tbody>

		// 	</table>';

				

		/* MÉTODO PAGAMENTO */

		$query_rsPagamento = "SELECT * FROM met_pagamento_".$language." WHERE id='$row_rsEncomenda[met_pagamt_id]'";

		$rsPagamento = DB::getInstance()->prepare($query_rsPagamento);

		$rsPagamento->execute();

		$row_rsPagamento = $rsPagamento->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPagamento = $rsPagamento->rowCount();

		

		$nome_pag = $row_rsPagamento['nome'];

		$img_pag = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/geral.png" width="50" />';

		if($row_rsPagamento['imagem'] != '' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsPagamento['imagem'])) { 

			$img_pag = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/'.$row_rsPagamento['imagem'].'" width="50" />';

		}

		

		if($row_rsEncomenda['estado'] == 1) {		

			if($row_rsEncomenda['met_pagamt_id'] == 1) {

				$pagP = '<table border="0" cellpadding="0" cellspacing="0" width="256" style="width:256px"> 

					<tr>

						<td height="35" valign="middle" align="center" bgcolor="'.COR_SITE.'"><a href="'.$link_paypal.'" target="_blank" class="button">'.$Recursos->Resources["car_comprar_paypal"].'</a></td>

					</tr>

				</table>';

			}

			else if($row_rsEncomenda['met_pagamt_id'] == 6 || $row_rsEncomenda['met_pagamt_id'] == 7) {

				$ref_pag = $row_rsEncomenda['ref_pagamento'];

				$ref_pagamento = substr($ref_pag, 0, 3)." ".substr($ref_pag, 3, 3)." ".substr($ref_pag, 6, 3);

				

				$pagP = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

					  <tbody>

					  	<tr>

								<td colspan="2" height="5"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" height="5" /></td>

							</tr>

							<tr>

							  <td class="textos" width="100">'.$Recursos->Resources["comprar4_entidade"].'</td>

							  <td class="textos"><strong>'.$row_rsEncomenda['entidade'].'</strong></td>

							</tr>

							<tr>

							  <td class="textos" width="100">'.$Recursos->Resources["comprar4_referencia"].'</td>

							  <td class="textos"><strong>'.$ref_pagamento.'</strong></td>

							</tr>

							<tr>

							  <td class="textos" width="100">'.$Recursos->Resources["comprar4_montante"].'</td>

							  <td class="textos"><strong>'.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['valor_c_iva']).'</strong></td>

							</tr>

					  </tbody>

					</table>';

			}

			else if($row_rsEncomenda['met_pagamt_id'] == 8) {

				$pagP = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:256px"> 

					<tr>

						<td height="35" valign="middle" align="center" bgcolor="'.COR_SITE.'"><a href="'.$row_rsEncomenda['url_pagamento'].'" target="_blank" class="button">'.$Recursos->Resources["car_comprar_easypay"].'</a></td>

					</tr>

				</table>';

			}

			else if($row_rsPagamento['descricao']) { 

				$pagP = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 256px"> 

					<tr>

						<td class="textos">'.$row_rsPagamento['descricao'].'</td>

					</tr>

				</table>';

			}



			if($row_rsPagamento['descricao2']) {

				$pagP .= '<table border="0" cellpadding="0" cellspacing="0" width="100%"> 

					<tr>

						<td height="10"><img src="'.ROOTPATH_HTTP.'/imgs/carrinho/fill.gif" height="10"/></td>

					</tr>

					<tr>

						<td class="textos">'.$row_rsPagamento['descricao2'].'</td>

					</tr>

				</table>';

			}

		}



		$mailPagamento = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">'.$Recursos->Resources["comprar_pagamento"].'</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td width="50">'.$img_pag.'</td>

														  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

														  <td align="left" class="textos">'.$nome_pag.'</td>

														</tr>

												  </tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td class="textos">

										  	<br>

												'.$pagP.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>

        ';

		

		/* MÉTODO ENVIO */

		if($verifica_encomenda == 1) {

			if($row_rsEncomenda['entrega_id'] > 0) {

				$query_rsEntrega = "SELECT * FROM met_envio_".$language." WHERE id='$row_rsEncomenda[entrega_id]'";

				$rsEntrega = DB::getInstance()->prepare($query_rsEntrega);

				$rsEntrega->execute();

				$row_rsEntrega = $rsEntrega->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsEntrega = $rsEntrega->rowCount();

				

				$nome_ent = $row_rsEntrega['nome'];

				$desc_ent = "";

				if($row_rsEntrega['descricao']) {

					$desc_ent .= $row_rsEntrega['descricao'];

				}

				

				$img_ent = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/geral.png" width="50" />';

				if($row_rsEntrega['imagem'] != '' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsEntrega['imagem'])) { 

					$img_ent = '<img src="'.ROOTPATH_HTTP.'imgs/carrinho/'.$row_rsEntrega['imagem'].'" width="50" />';

				}

			}



			$mailEntrega = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">'.$Recursos->Resources["comprar_entrega"].'</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td width="50">'.$img_ent.'</td>

														  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

														  <td align="left" class="textos">'.$nome_ent.'</td>

														</tr>

													</tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

											<td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

											<td class="textos">

										  	<br>

												'.$desc_ent.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										';



			if($segue_envio) {

				$mailEntrega .= '<tr>

										  <td colspan="3" height="25"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="25" /></td>

										</tr>

										<tr>

											<td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td colspan="3" class="titulos">'.$Recursos->Resources["seguir_envio"].'</td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

											<td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												'.$segue_envio.'

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										';

			}

			

			$mailEntrega .= '<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>';

		}

		else {

			$mailEntrega = '';

		}

	

		/* OBSERVAÇÕES */

		$mailObs = '';

		if($observacoes) {

			$mailObs = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

            </tr>

            <tr>

              <td align="left" valign="top">

              	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

								  <tbody>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

										<tr>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										  <td>

												<table width="100%" border="0" cellspacing="0" cellpadding="0">

												  <tbody>

														<tr>

														  <td colspan="3" class="titulos">'.$Recursos->Resources["obs_encomenda"].'</td>

														</tr>

														<tr>

														  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

														</tr>

														<tr>

														  <td colspan="3" class="textos">'.nl2br($observacoes).'</td>

														</tr>

												  </tbody>

												</table>

										  </td>

										  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

										</tr>

										<tr>

										  <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

										</tr>

								  </tbody>

								</table>

							</td>

            </tr>

          </tbody>

        </table>';

		}

		

		/* PRODUTOS */

		$mailprodutos = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7;">

        <tbody>

        	<tr>

            <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

          </tr>

          <tr>

            <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

            <td class="titulos">'.$Recursos->Resources["comprar_resumo"].'</td>

            <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

          </tr>

          <tr>

            <td colspan="3" height="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

          </tr>

        </tbody>

      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e7e7e7; border-top: 0;">

        <tbody>

        	<tr width="5%">

            <td colspan="4" height="7"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="7" /></td>

          </tr>

          <tr>

            <td class="subtitulos" style="padding-left: 15px;">'.$Recursos->Resources["cart_prod"].'</td>

            

            <td width="100" class="subtitulos" style="text-align: center;">QTY</td>

            

          </tr>

          <tr width="5%">

            <td colspan="4" height="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

          </tr>

        </tbody>  

      </table>

			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid #e7e7e7;">

        <tbody>

          <tr>

            <td>';

			

		$count = 0;



		// echo "<pre>";

		// print_r ($finalget_array);

		// echo "</pre>";



		foreach ($finalget_array as $key => $value) {

			$cate_name = $value['cate_name'];

			$check_product = $value['cate_product'];

			

			$mailprodutos .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #e7e7e7;">

              	<tbody>

	                <tr>

	                  <td colspan="5"><h5 style="text-align:center; padding-top:12px; padding-bottom:12px; margin:0;">'.$cate_name.'</h5></td>

	                </tr>

                <tbody>

            </table>'

                ;



		foreach($row_rsCarrinhoFinal as $produtos) {

			$produto_id = $produtos['produto_id'];

			if (in_array($produto_id, $check_product) == false) {

			 	continue 1;

			} 

			$count++;

			



			$query_rsProc = "SELECT * FROM l_pecas_en WHERE id=:id";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':id', $produto_id, PDO::PARAM_INT);

			$rsProc->execute();

			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProc= $rsProc->rowCount();



			$image = $produtos['imagem1'];

			$nome_prod = $row_rsProc['nome'];

			$ref_prod = "Ref.: <strong>".$row_rsProc['ref']."</strong>";

			$codigo = $produtos['ref'];

			$quantidade = $produtos['qtd'];

			$preco = $produtos['preco'];

			$opcoes = $produtos['opcoes'];

			$pack_obs = $produtos['pack_obs'];

			

			$new_path = explode("/", $image);

			$new_path = ROOTPATH."imgs/produtos/".end($new_path);



			$horVert = tamanho_imagem2($new_path, 50, 50);

			

			$image_prod = '<img src="'.$image.'" width="50" />';

			if($horVert == "height"){

				$image_prod = '<img src="'.$image.'" height="50" />';

			}

			

			$border = ' style="border-bottom:1px solid #e7e7e7;"';

			if($count == $totalRows_rsCarrinhoFinal) {

				$border = "";

			}

			

			$opcoes2 = explode("<br>", str_replace(";", ",", $opcoes));

			$opcoes_mail = "";

			foreach($opcoes2 as $opcao) {

				$opcao = explode(":", $opcao);

				$opcoes_mail .= $opcao[1]." ";

			}

			

			$mailprodutos .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"'.$border.'>

              <tbody>

                <tr>

                  <td colspan="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

                </tr>

                <tr>

                  <td width="2%"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

                  <td>

                  	<table width="100%" border="0" cellspacing="0" cellpadding="0">

                      <tbody>

                        <tr>

                          <td width="50">'.$image_prod.'</td>

                          <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

                          <td>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                              <tbody>

                              	<tr>

                                  <td colspan="3" class="ref_produto">'.$ref_prod.'</td>

                                </tr>

                                <tr>

                                  <td colspan="3" class="nome_produto">'.$nome_prod.'</td>

                                </tr>

                                <tr>

                                  <td colspan="3" class="opcoes_produto">'.$pack_obs.'</td>

                                </tr>

                                <tr>

                                  <td colspan="3" class="opcoes_produto">'.$opcoes_mail.'</td>

                                </tr>

                                <tr>

                                  <td colspan="3" height="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

                                </tr>

                              </tbody>

                            </table>

                          </td>

                        </tr>

                      </tbody>

                    </table>

                  </td>

                  

                  <td width="100" class="qtd_produto" style="text-align: center;">

                  	'.$quantidade.'

                  </td>

                 

                </tr>

                <tr>

                  <td colspan="5"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="10" /></td>

                </tr>

              </tbody>

            </table>

          ';

		}



		}



		$mailprodutos .= '</td>

          </tr>

        </tbody>

      	</table>';

				

		/* TOTAIS */		

		// $tabela_totais = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

  //     		<tbody>

		// 		<tr>

		// 		  <td colspan="2"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

		// 		</tr>

		// 		<tr>

		// 		  <td align="right">

		// 				<table width="100%" border="0" cellspacing="0" cellpadding="0">

		// 				  <tbody>

		// 						<tr>

		// 						  <td align="right" class="infos"><strong>'.$Recursos->Resources['car_sub_total'].'</strong></td>

		// 						  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 						  <td align="right" class="infos" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['prods_total']).'</strong></td>

		// 						</tr>

		// 						<tr>

		// 						  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

		// 						</tr>

		// 						';



		// if($row_rsEncomenda["compra_valor_saldo"] > 0) {

		// 	$tabela_totais .= '<tr>

		// 						  <td align="right" class="infos"><strong>'.$Recursos->Resources['comprar_saldo'].'</strong></td>

		// 						  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 						  <td align="right" class="infos" width="100"><strong>- '.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda["compra_valor_saldo"]).'</strong></td>

		// 						</tr>

		// 						<tr>

		// 						  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

		// 						</tr>

		// 						';

		// }



		// if($row_rsEncomenda['codigo_promocional'] && $row_rsEncomenda['codigo_promocional_desconto'] > 0 && $row_rsEncomenda['codigo_promocional_valor'] > 0) {

		// 	$tabela_totais .= '<tr>

		// 						  <td align="right" class="infos"><strong>'.$Recursos->Resources["codigo_promocional"]." (".$row_rsEncomenda["codigo_promocional"].")".'</strong></td>

		// 						  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 						  <td align="right" class="infos" width="100"><strong>- '.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda["codigo_promocional_valor"]).'</strong></td>

		// 						</tr>

		// 						<tr>

		// 						  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

		// 						</tr>

		// 						';

		// }



		// $tabela_totais .= '<tr>

		// 						  <td align="right" class="infos"><strong>'.$Recursos->Resources['portes_de_pagamento'].'</strong></td>

		// 						  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 						  <td align="right" class="infos" width="100"><strong>+ '.self::mostraPrecoEnc($id_encomenda, $portes_pag).'</strong></td>

		// 						</tr>

		// 						<tr>

		// 						  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="5" /></td>

		// 						</tr>

		// 						<tr>

		// 						  <td align="right" class="infos"><strong>'.$Recursos->Resources['portes_de_envio'].'</strong></td>

		// 						  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 						  <td align="right" class="infos" width="100"><strong>';



		// 		  if($portes_env >= 0) {

		// 		  	$tabela_totais .= '+ ';

		// 		  }

		// 		  else {

		// 		  	$tabela_totais .= '- ';

		// 		  }



		// 		$tabela_totais .= self::mostraPrecoEnc($id_encomenda, abs($portes_env)).'</strong></td>

		// 						</tr>

		// 						';



		// 		$tabela_totais .= '<tr>

		// 						  <td colspan="3"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

		// 						</tr>

		// 						<tr>

		// 						  <td align="right" class="total">'.$Recursos->Resources['car_total'].'</td>

		// 						  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 						  <td align="right" class="total" width="100"><strong>'.self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['valor_c_iva']).'</strong></td>

		// 						</tr>

		// 				  </tbody>

		// 				</table>

		// 		  </td>

		// 		  <td width="15"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="15" /></td>

		// 		</tr>

		// 		<tr>

		// 		  <td colspan="2"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" height="15" /></td>

		// 		</tr>

		//   </tbody>

		// </table>';

		

		// /* EXTRAS */

		// $tabela_extras = "";

		// if($row_rsEncomenda['pontos_compra'] > 0) {

		// 	$tabela_extras .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">

		// 	  <tbody>

		// 			<tr>

		// 			  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 			  <td height="25" valign="middle" align="right" class="extras">'.str_replace("###", number_format($row_rsEncomenda['pontos_compra'], 0, '.', ''), $Recursos->Resources["pontos_acumular"]).'</td>

		// 			  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 			</tr>

		// 	  </tbody>

		// 	</table>';

		// }

		// if($row_rsEncomenda['saldo_compra'] > 0) {

		// 	$tabela_extras .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">

		// 	  <tbody>

		// 			<tr>

		// 			  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 			  <td height="25" valign="middle" align="right" class="extras">'.str_replace("###", self::mostraPrecoEnc($id_encomenda, $row_rsEncomenda['saldo_compra']), $Recursos->Resources["saldo_acumular"]).'</td>

		// 			  <td width="10"><img src="'.ROOTPATH_HTTP.'imgs/carrinho/fill.gif" width="10" /></td>

		// 			</tr>

		// 	  </tbody>

		// 	</table>';

		// }



		// /* TEXTOS */

		// $tabela_textos = '';

		// if($imprime == 0) {

		// 	$tabela_textos = '<table width="100%" border="0" cellspacing="0" cellpadding="0">

		// 	  <tbody>

		// 		  <tr>

		// 			  <td height="30"></td>

		// 			</tr>

		// 			<tr>

		// 			  <td align="center" class="textos">'.$Recursos->Resources["comprar4_texto"].'</td>

		// 			</tr>

		// 			<tr>

		// 			  <td height="20">&nbsp;</td>

		// 			</tr>

		// 			<tr>

		// 			  <td align="center" class="cumprimentos">'.$Recursos->Resources["car_mail_7"].'</td>

		// 			</tr>

		// 			<tr>

		// 			  <td align="center" class="nome_site">'.NOME_SITE.'</td>

		// 			</tr>

		// 			<tr>

		// 			  <td height="40">&nbsp;</td>

		// 			</tr>

		// 			<tr>

		// 			  <td>

		// 					<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#c7c7c7">

		// 					  <tbody>

		// 							<tr>

		// 							  <td colspan="3" height="20"></td>

		// 							</tr>

		// 							<tr>

		// 							  <td width="30%" align="center"><a class="textos_rodape" href="'.ROOTPATH_HTTP.'area-reservada.php">'.$Recursos->Resources["car_mail_cliente"].'</a></td>

		// 							  <td align="center"><a class="textos_rodape" href="'.ROOTPATH_HTTP.'contactos.php">'.$Recursos->Resources["car_mail_maisinfo"].'</a></td>

		// 							  <td align="center" width="30%"><a class="textos_rodape" href="'.ROOTPATH_HTTP.'">www.'.SERVIDOR.'</a></td>

		// 							</tr>

		// 							<tr>

		// 							  <td colspan="3" height="20"></td>

		// 							</tr>

		// 					  </tbody>

		// 					</table>

		// 			  </td>

		// 			</tr>

		// 			<tr>

		// 			  <td height="25"></td>

		// 			</tr>

		// 	  </tbody>

		// 	</table>';

		// }

					

		$formcontent = str_replace ("#msg_intro#", $msg_intro, $formcontent);

		$formcontent = str_replace ("#car_mail_intro#", $desc, $formcontent);

		$formcontent = str_replace ("#car_resumo_enc#", $Recursos->Resources["car_resumo_enc"], $formcontent);

		$formcontent = str_replace ("#car_num_enc#", $Recursos->Resources["car_num_enc"]." <strong>#".$row_rsEncomenda['numero']."</strong>", $formcontent);

		$formcontent = str_replace ("#car_data_enc#", $Recursos->Resources["car_data_enc"]." <strong>".$row_rsEncomenda['data']."</strong>", $formcontent);

		$formcontent = str_replace ("#fachasename#", $canceltale, $formcontent);

		$formcontent = str_replace ("#dados_faturacao#", $mailFaturacao, $formcontent);

		$formcontent = str_replace ("#dados_envio#", $mailEnvio, $formcontent);



		$formcontent = str_replace ("#car_pagamento#", '', $formcontent);

		$formcontent = str_replace ("#car_entrega#", '', $formcontent);

		$formcontent = str_replace ("#car_observacoes#", $mailObs, $formcontent);

		$formcontent = str_replace ("#encomenda_produtos#", $mailprodutos, $formcontent);



		$formcontent = str_replace ("#ctotais#", '', $formcontent);

		$formcontent = str_replace ("#cextras#", '', $formcontent);

		$formcontent = str_replace ("#ctextos#", '', $formcontent);

		$formcontent = str_replace ("#crodape#", $rodape, $formcontent);

			

		if($imprime == 0) {	

			// $query_rsCont = "SELECT * FROM notificacoes_".$language." WHERE id='3'";

			// $rsCont = DB::getInstance()->prepare($query_rsCont);

			// $rsCont->execute();

			// $row_rsCont = $rsCont->fetch(PDO::FETCH_ASSOC);

			// $totalRows_rsCont = $rsCont->rowCount();

								

			// $mail_cont = $row_rsCont['email'];		

			// if($mail_cont) {			

			//  //  sendMail($email, '', $formcontent, $subject, $subject);		

			// 	// sendMail($mail_cont, '', $formcontent, $subject, $subject, $row_rsCont['email2'], $row_rsCont['email3']);

			// }

			

			// $insertSQL = "UPDATE encomendas SET email_enviado='1' WHERE id='$id_encomenda'";

			// $rsInsert = DB::getInstance()->prepare($insertSQL);

			// $rsInsert->execute();

		}

		else {

			return $formcontent;

		}



		DB::close();

	}

		

	public static function carrinhoResumo($tudo = 0, $converter = 1) {

		global $Recursos, $row_rsCliente, $pagamento, $portes_pag, $entrega, $portes_env, $total;

		

		$subtotal = $total = self::precoTotal();

		$carrinho_session = $_COOKIE[CARRINHO_SESSION];

		

		if(CARRINHO_SALDO == 1) {

			$query_rsProcS = "SELECT valor FROM carrinho_comprar WHERE session='$carrinho_session'";

			$rsProcS = DB::getInstance()->prepare($query_rsProcS);

			$rsProcS->execute();

			$row_rsProcS = $rsProcS->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsProcS = $rsProcS->rowCount();

		

			$saldo_acumula = self::acumularSaldo();

			$saldo_disp = $row_rsCliente['saldo'];  



			if($saldo_disp > 0 && $totalRows_rsProcS > 0 && $row_rsProcS['valor'] > 0) {

				if($saldo_disp >= $total) {

					$saldo_compra = $total;

					$saldo_disp = $saldo_disp - $total;

					$total = 0;

				}

				else {

					$saldo_compra = $saldo_disp;

					$saldo_disp = 0;

					$total = $total - $saldo_compra; 

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

				$query_rsCodProm = "SELECT codigo FROM codigos_promocionais WHERE id='".$row_rsCarCodProm["id_codigo"]."'";

				$rsCodProm = DB::getInstance()->prepare($query_rsCodProm);

				$rsCodProm->execute();

				$row_rsCodProm = $rsCodProm->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsCodProm = $rsCodProm->rowCount();

				

				if($totalRows_rsCodProm > 0) {

					$desconto_promo = self::calcula_cod_promo($row_rsCodProm['codigo']);

	        if($row_rsCodProm['tipo_desconto'] == 1) {

            $total = $total - $desconto_promo; 

            $desconto_promo = "- ".self::mostraPreco($desconto_promo);

	        }

	        else {

            $total = $total - $desconto_promo; 

            $desconto_promo = "- ".self::mostraPreco($desconto_promo);

	        }

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



		if($converter == 0) {

			$total = self::mostraPreco($total, 2);

		}



		if($portes_pag != 0) {

			$total = $total + $portes_pag;

		}

		

		if($portes_env != 0) {

			$total = $total + $portes_env;

		}



		DB::close();

		?>

    <div class="comprar_resumo">

      <h3 class="comprar_tit"><?php echo $Recursos->Resources["comprar_resumo"]; ?></h3>

      <div class="resumo">

        <ul class="carrinho_resumo active"><?php echo self::carrinhoDivs("dropdown", $tudo); ?></ul>

        <div class="div_100 comprar_totais">

          <div class="row collapse align-middle">

            <div class="column"><strong><?php echo $Recursos->Resources["car_sub_total"]; ?></strong></div>

            <div class="column shrink"><strong><?php echo self::mostraPreco($subtotal, 0);?></strong></div>

            <input name="subtotal" id="subtotal" value="<?php echo self::mostraPreco($subtotal, 2); ?>" type="hidden" />

          </div>

          <?php if(CARRINHO_SALDO == 1 && $saldo_compra > 0) { ?>

            <div class="row collapse align-middle">

              <div class="column"><?php echo $Recursos->Resources["comprar_saldo"]; ?></div>

              <div class="column shrink">- <?php echo self::mostraPreco($saldo_compra, 0); ?></div>

              <input name="saldo_compra" type="hidden" id="saldo_compra" value="<?php echo self::mostraPreco($saldo_compra, 2); ?>" />

              <input name="saldo_cliente" type="hidden" id="saldo_cliente" value="<?php echo self::mostraPreco($saldo_disp, 2); ?>" />

            </div>

          <?php } ?>

          <?php if(CARRINHO_CODIGOS == 1 && $row_rsCodProm["codigo"]) { ?>

            <div class="row collapse align-middle">

              <div class="column"><?php echo $Recursos->Resources["codigo_promocional"]." (".$row_rsCodProm["codigo"].")"; ?></div>

              <div class="column shrink"><?php echo $desconto_promo; ?></div>

              <input name="codpromo" type="hidden" id="codpromo" value="<?php echo self::mostraPreco($desconto_promo, 2, $converter); ?>" />

              <input name="codpromo_val" type="hidden" id="codpromo_val" value="<?php echo self::mostraPreco($desconto_promo, 2, $converter); ?>" />

            </div>

          <?php } ?>

          <div class="row collapse align-middle">

            <div class="column"><?php echo $Recursos->Resources["portes_de_pagamento"]; ?></div>

            <div class="column shrink" id="portes_pag_html">+ <?php echo self::mostraPreco($portes_pag, 0, $converter); ?></div>

            <input name="pagamento" id="pagamento" value="<?php echo $pagamento; ?>" type="hidden" />

            <input name="portes_pag" id="portes_pag" value="<?php echo self::mostraPreco($portes_pag, 2, $converter); ?>" type="hidden" />

          </div>



         <!-- Hide/Show Start -->

         

         

          <div class="row collapse align-middle portdisbale">

            <div class="column"><?php echo $Recursos->Resources["portes_de_envio"]; ?></div>

            <div class="column shrink" id="portes_env_html">+ <?php echo self::mostraPreco($portes_env, 0, $converter); ?></div>

            <input name="portes_env" id="portes_env" value="<?php echo self::mostraPreco($portes_env, 2, $converter); ?>" type="hidden" />

            <input name="entrega" id="entrega" value="<?php echo $entrega; ?>" type="hidden" />

          </div>

          <div class="row collapse align-middle">

            <div class="column big"><strong><?php echo $Recursos->Resources["car_total"]; ?></strong></div>

            <div class="column shrink big"><strong class="total_update"><?php echo self::mostraPreco($total, 0, $converter); ?></strong></div>

            <input name="total" id="total" value="<?php echo self::mostraPreco($total, 2, $converter); ?>" type="hidden" />

          </div>

        </div>

        <?php if(CARRINHO_PONTOS == 1 && $pontos_compra > 0) { ?>

          <div class="comprar_extras">

            <div class="row collapse">

              <div class="small-12 column">

                <?php echo str_replace("###", number_format($pontos_compra, 0, '.', ''), $Recursos->Resources["pontos_acumular"]); ?>

                <input name="pontos_compra" type="hidden" id="pontos_compra" value="<?php echo $pontos_compra; ?>" />

              </div>

          	</div>

          </div>

        <?php } ?>

        <?php if(CARRINHO_SALDO == 1 && $saldo_acumula > 0) { ?>

          <div class="comprar_extras">

            <div class="row collapse">

              <div class="small-12 column">

                <?php echo str_replace("###", self::mostraPreco($saldo_acumula, 0, $converter), $Recursos->Resources["saldo_acumular"]); ?>

                <input name="saldo_acumula" type="hidden" id="saldo_acumula" value="<?php echo self::mostraPreco($saldo_acumula, 2, $converter); ?>" />

              </div>

            </div>

          </div>

        <?php } ?>

      </div>

    </div>

    <?php

	}

	

	public static function carrinhoDivs($tipo, $tudo = 0) {

		global $extensao, $Recursos, $class_produtos, $produto_com_portes_gratis, $saldo_acumula, $faz_reload, $moeda, $total, $total_peso, $row_rsPGratis;



		$carrinho_session = $_COOKIE[CARRINHO_SESSION];



		$query_rsCar = "SELECT pecas.id, pecas.nome, pecas.ref, pecas.url, pecas.nao_limitar_stock, carrinho.*, carrinho.id AS id_linha FROM carrinho LEFT JOIN l_pecas".$extensao." AS pecas ON (carrinho.produto = pecas.id AND pecas.visivel = 1) WHERE carrinho.session = '$carrinho_session' ORDER BY pecas.ordem ASC";

		$rsCar = DB::getInstance()->prepare($query_rsCar);

		$rsCar->execute();

		$row_rsCar = $rsCar->fetchAll();

		$totalRows_rsCar = $rsCar->rowCount();

		

		if($totalRows_rsCar > 0) {	

			foreach($row_rsCar as $carrinho) { 

				$cheque_prenda = 0;

				if($carrinho['cheque_prenda'] == 1) {

					$linha_id = $carrinho['id_linha'];	

					$cheque_prenda = 1;

					$nome = $Recursos->Resources["cheque_prenda_2"]."<br>".$carrinho['cheque_nome']." - ".$carrinho['cheque_email'];

					$preco_produto = $carrinho['preco'];

					$image = $class_produtos->imgProduto(0, 0, 1);

					$quantidade = $carrinho['quantidade'];

				}

				else {

					$linha_id = $carrinho['id_linha'];	

					$produto = $carrinho['produto'];     								

					$image = $class_produtos->imgProduto($produto, 0, 1);   

					$nome = $carrinho['nome'];

					$codigo = $rsCarrinho['ref'];

					$produto_url = $carrinho['url'];

					$quantidade = $carrinho['quantidade'];

					$produto_acumula = 0;



					if(CARRINHO_TAMANHOS == 1) {	

						$query_rsTamDef = "SELECT * FROM l_pecas_tamanhos WHERE op1=:tam1 AND op2=:tam2 AND op3=:tam3 AND op4=:tam4 AND op5=:tam5 AND peca=:id";

						$rsTamDef = DB::getInstance()->prepare($query_rsTamDef);

						$rsTamDef->bindParam(':id', $produto, PDO::PARAM_INT, 5); 

						$rsTamDef->bindParam(':tam1', $carrinho["op1"], PDO::PARAM_INT, 5); 

						$rsTamDef->bindParam(':tam2', $carrinho["op2"], PDO::PARAM_INT, 5); 

						$rsTamDef->bindParam(':tam3', $carrinho["op3"], PDO::PARAM_INT, 5); 

						$rsTamDef->bindParam(':tam4', $carrinho["op4"], PDO::PARAM_INT, 5); 

						$rsTamDef->bindParam(':tam5', $carrinho["op5"], PDO::PARAM_INT, 5); 

						$rsTamDef->execute();

						$row_rsTamDef = $rsTamDef->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsTamDef = $rsTamDef->rowCount();

					}

												

					$preco = $carrinho['preco'];

					$preco_produto = $class_produtos->precoProduto($produto, 0, $quantidade, $row_rsTamDef['id']);

					

					$stock_disponivel = $class_produtos->stockProduto($produto, $carrinho["op1"], $carrinho["op2"], $carrinho["op3"], $carrinho["op4"], $carrinho["op5"], 2);					

																		

					if($carrinho['nao_limitar_stock'] && $quantidade > $stock_disponivel) {

						$stock_disponivel = $quantidade;

					}



					if($stock_disponivel > 0) {											

						if($quantidade > $stock_disponivel && $carrinho['nao_limitar_stock'] == '0') {

							$insertSQL = "UPDATE carrinho SET quantidade='$stock_disponivel' WHERE id='$linha_id' AND session='$carrinho_session'";

							$rsInsertSQL = DB::getInstance()->prepare($insertSQL);

							$rsInsertSQL->execute();

							

							$faz_reload = 1;

						}

					}

					else if($carrinho['nao_limitar_stock'] != '1') {

						$deleteSQL = "DELETE FROM carrinho WHERE id='$linha_id' AND session='$carrinho_session'";	

						$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

						$rsDeleteSQL->execute();

						

						$faz_reload = 1;

					}

									

					if($preco_produto != $preco) {

						$insertSQL = "UPDATE carrinho SET preco='$preco_produto' WHERE id='$linha_id' AND session='$carrinho_session'";	

						$rsInsertSQL = DB::getInstance()->prepare($insertSQL);

						$rsInsertSQL->execute();

						

						$faz_reload = 1;

					}

					

					if($preco_produto <= 0) {

						$deleteSQL = "DELETE FROM carrinho WHERE id='$linha_id' AND session = '$carrinho_session'";

						$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

						$rsDeleteSQL->execute();

						

						$faz_reload = 1;

					}

					

					// verifica se o produto já existe no carrinho		

					$query_rsExiste = "SELECT * FROM carrinho WHERE session = '$carrinho_session' AND produto = '$produto' AND id != '$linha_id' AND op1='$tam1' AND op2='$tam2' AND op3='$tam3' AND op4='$tam4' AND op5='$tam5'";

					$rsExiste = DB::getInstance()->prepare($query_rsExiste);

					$rsExiste->execute();

					$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsExiste = $rsExiste->rowCount();

					

					$ja_esta = 0;

					if($totalRows_rsExiste > 0 && $ja_esta == 0) {

						$insertSQL = "UPDATE carrinho SET quantidade=quantidade+".$row_rsExiste["quantidade"]." WHERE id='$linha_id' AND session='$carrinho_session'";	

						$rsInsertSQL = DB::getInstance()->prepare($insertSQL);

						$rsInsertSQL->execute();

						

						$deleteSQL = "DELETE FROM carrinho WHERE id='".$row_rsExiste["id"]."' AND session = '$carrinho_session'";

						$rsDeleteSQL = DB::getInstance()->prepare($deleteSQL);

						$rsDeleteSQL->execute();

						

						$ja_esta = 1;

						$faz_reload = 1;

					}

				}



				//VERIFICA SE NO CARRINHO EXISTE O MESMO PRODUTO COM CARACTERISTICAS DIFERENTES

				if(CARRINHO_DESCONTOS != 0) {

					$query_rsProdCarrinho = "SELECT * FROM carrinho WHERE produto='$produto' AND id!='$linha_id' AND session='$carrinho_session'";

					$rsProdCarrinho = DB::getInstance()->prepare($query_rsProdCarrinho);

					$rsProdCarrinho->execute();

					$row_rsProdCarrinho = $rsProdCarrinho->fetchAll();

					$totalRows_rsProdCarrinho = $rsProdCarrinho->rowCount();

					

					if($totalRows_rsProdCarrinho > 0) {

						foreach($row_rsProdCarrinho as $prod_carrinho) {

							$qtd_desc += $prod_carrinho['quantidade'];

						}

					}

	

					//Verifica se o preço tem desconto

					$query_rsDescProd = "SELECT * FROM l_pecas_desconto WHERE id_peca='$produto' AND min<='$qtd_desc' AND (max>='$qtd_desc' OR max IS NULL OR max='') ORDER BY desconto DESC";

					$rsDescProd = DB::getInstance()->prepare($query_rsDescProd);

					$rsDescProd->execute();

					$row_rsDescProd = $rsDescProd->fetch(PDO::FETCH_ASSOC);

					$totalRows_rsDescProd = $rsDescProd->rowCount();

					DB::close();

					

					$desconto_produto = 0;

					if($totalRows_rsDescProd > 0) {

						$desconto_produto = $row_rsDescProd['desconto'];

					}

					

					if($carrinho['desconto'] != $desconto_produto) {

						$insertSQL = "UPDATE carrinho SET desconto='$desconto_produto' WHERE id='$linha_id' AND session='$carrinho_session'";	

						$rsInsertSQL = DB::getInstance()->prepare($insertSQL);

						$rsInsertSQL->execute();

						

						$faz_reload = 1;

					}

				}

						

				//VERIFICA SE HA CAMPANHAS DE PORTES GRATIS PARA ESTE PRODUTO

				if(CARRINHO_PORTES == 1) {

					if($produto_com_portes_gratis == 0) {

						$query_rsProduto = "SELECT categoria, marca FROM l_pecas".$extensao." WHERE visivel='1' AND id='$produto'";

						$rsProduto = DB::getInstance()->prepare($query_rsProduto);

						$rsProduto->execute();

						$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);

						$totalRows_rsProduto = $rsProduto->rowCount();



						$zona_cliente = $row_rsPGratis['id'];											

						$categoria_produto = $row_rsProduto['categoria'];

						$marca_produto = $row_rsProduto['marca'];

						$data = date('Y-m-d H:i:s');

						$valor_portes = 0;

						

						$where = $left_join = "";

						

						if(ECC_MARCAS == 1) {

							$where = " OR portes_gratis_marcas.marca='$marca_produto'";

							$left_join = " LEFT JOIN portes_gratis_marcas ON portes_gratis.id=portes_gratis_marcas.portes_gratis";

						}



						if(CATEGORIAS == 1) {

							$query_rsCampGratis = "SELECT portes_gratis.* FROM portes_gratis LEFT JOIN portes_gratis_categorias ON portes_gratis.id=portes_gratis_categorias.portes_gratis".$left_join." LEFT JOIN portes_gratis_zonas ON portes_gratis.id=portes_gratis_zonas.portes_gratis LEFT JOIN l_categorias_en ON l_categorias_en.id = '$categoria_produto' WHERE portes_gratis.visivel='1' AND portes_gratis.datai<='$data' AND portes_gratis.dataf>='$data' AND ((portes_gratis.id=portes_gratis_zonas.portes_gratis AND portes_gratis_zonas.zona='$zona_cliente') OR l_categorias_en.cat_mae=portes_gratis_categorias.categoria OR portes_gratis_categorias.categoria='$categoria_produto'".$where.") GROUP BY portes_gratis.id";

							$rsCampGratis = DB::getInstance()->prepare($query_rsCampGratis);

							$rsCampGratis->execute();

							$row_rsCampGratis = $rsCampGratis->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsCampGratis = $rsCampGratis->rowCount();

						}

						else if(CATEGORIAS == 2) {

							$query_rsCampGratis = "SELECT portes_gratis.* FROM portes_gratis LEFT JOIN portes_gratis_categorias ON portes_gratis.id=portes_gratis_categorias.portes_gratis".$left_join." LEFT JOIN portes_gratis_zonas ON portes_gratis.id=portes_gratis_zonas.portes_gratis LEFT JOIN l_categorias_en ON l_categorias_en.id = '$categoria_produto' WHERE portes_gratis.visivel='1' AND portes_gratis.datai<='$data' AND portes_gratis.dataf>='$data' AND ((portes_gratis.id=portes_gratis_zonas.portes_gratis AND portes_gratis_zonas.zona='$zona_cliente') OR l_categorias_en.cat_mae=portes_gratis_categorias.categoria OR (portes_gratis_categorias.categoria='$categoria_produto' OR portes_gratis_categorias.categoria IN (SELECT categoria FROM l_pecas_categorias WHERE id_peca='$produto'))".$where.") GROUP BY portes_gratis.id";

							$rsCampGratis = DB::getInstance()->prepare($query_rsCampGratis);

							$rsCampGratis->execute();

							$row_rsCampGratis = $rsCampGratis->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsCampGratis = $rsCampGratis->rowCount();

						}

			

						if($totalRows_rsCampGratis > 0) {

							//Verifica se os portes gratis se aplicam com base no preço mínimo e peso máximo

							$aplica_p_gratis = 1;



							if($row_rsCampGratis['min_encomenda'] > 0 && $row_rsCampGratis['min_encomenda'] > $total) {

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

					

				switch ($tipo) {

					case "carrinho": 

						?>

            <div class="row collapse body align-middle">

              <div class="small-12 xsmall-12 xxsmall-7 medium-6 large-6 column xxsmall-order-1">

              	<a href="<?php echo $produto_url; ?>" class="image has_bg contain" style="<?php echo $image; ?>;"></a><!--

                --><a href="<?php echo $produto_url; ?>" class="info">

                	<h3 class="name"><?php echo $nome; ?></h3>

									<?php if($codigo) { ?>

										<h5 class="ref"><?php echo $codigo; ?></h5>

									<?php } ?>

									<?php if($carrinho['opcoes']) {

										$opcoes = explode("<br>", str_replace(";", "", $carrinho['opcoes']));

										foreach($opcoes as $opcao) { ?><!--

											--><span class="options"><?php $opcao = explode(":", $opcao); echo $opcao[1]; ?></span><!--

										--><?php } 

									} ?>

                </a>

              </div>

              <div class="small-12 xsmall-6 xxsmall-12 medium-3 large-2 column xxsmall-order-4 medium-order-2">

                <?php if($cheque_prenda != 1) { ?>

                	<div class="quantidades">

                  	<button class="qtd_inc menos" onClick="document.getElementById('qtd_<?php echo $linha_id;?>').value--; alteraCart($('#qtd_<?php echo $linha_id;?>'));">-</button><!--

                    --><input name="qtd" type="text" id="qtd_<?php echo $linha_id;?>" data-id="<?php echo $linha_id;?>" onBlur="alteraCart($(this));" maxlength="6" value="<?php echo $quantidade; ?>" onkeyup="onlyNumber(this)" /><!--

                    --><button class="qtd_inc mais" onClick="document.getElementById('qtd_<?php echo $linha_id;?>').value++; alteraCart($('#qtd_<?php echo $linha_id;?>'))">+</button>

                  </div>

                <?php } else { ?> 

                  <div class="quantidades">

                    <span style="color: #42464b; font-size: 1.4em; line-height: 2.357em; font-weight: 700; text-align: center;"><?php echo $quantidade; ?></span>

                  </div>

                <?php } ?>

              </div>

              <div class="small-3 xsmall-3 medium-2 large-3 column xxsmall-order-2 medium-order-3 preco_col">

              	<?php if(ECOMMERCE_ATIVO == 1) { ?>

                  <div class="price">

                    <?php if($cheque_prenda == 1) {

                      echo self::mostraPreco($preco_produto);

                    }

                    else {

                      echo self::mostraPreco(($preco_produto * $quantidade));

                    } ?>

                  </div>

                  <?php 

                  $preco_antigo = 0;

                  

                  if($cheque_prenda != 1) {

                    $preco_antigo = $class_produtos->precoProduto($produto, 3, $quantidade, $row_rsTamDef['id']);

                    

                    if($quantidade > 1 || $preco_antigo > 0) { ?>

                      <div class="div_100 price_comps">

                        <?php if($preco_antigo > 0) { ?>

                        	<span><?php echo $preco_antigo; ?></span>

                        <?php } ?><!--

                        --><p><?php echo self::mostraPreco($preco_produto); ?></p>

                      </div>

                    <?php } ?>

                  <?php } ?>

              	<?php } ?>

              </div>

              <div class="small-3 xsmall-3 xxsmall-2 medium-1 column text-center xxsmall-order-3 medium-order-4">

              	<a href="javascript:;" class="carrinho-delete" onClick="removeCart(<?php echo $linha_id; ?>)"></a>

              </div>

            </div>

            <?php

						break;

					case "dropdown": 

						?>

            <li class="product" id="prod_<?php echo $produto; ?>" data-id="<?php echo $produto;?>" data-linha="<?php echo $linha_id; ?>">

              <a href="<?php echo $produto_url; ?>" class="image has_bg contain" style="<?php echo $image; ?>;"></a><!--

              --><div class="details<?php if($tudo!=0) echo " full"; ?>">

                <a href="<?php echo $produto_url; ?>" class="name"><?php echo $nome; ?></a>

                <span class="options">

                  <?php 

									$opcoes = explode("<br>", str_replace(";", ",", $carrinho['opcoes']));

									foreach($opcoes as $opcao) {

                    $opcao = explode(":", $opcao);

                    echo $opcao[1]." ";

                  } 

                  ?>

                </span>

                <div class="div_100">

                  <div class="quantity-item">

                  	<?php if($cheque_prenda == 1) { ?>

                      <span>Qtd. <?php echo $quantidade; ?></span>

                    <?php } else { ?>

											<?php if($tudo == 0) { ?>

                        <label>Qtd.</label><!--

                        --><select id="cart-qtd-<?php echo $produto; ?>" data-id="<?php echo $linha_id;?>" name="cart-qtd" onChange="alteraCart($(this))">

                            <?php for($i=1; $i<=$stock_disponivel; $i++){ ?>

                            <option value="<?php echo $i; ?>" <?php if($i == $quantidade) echo "selected"; ?>><?php echo $i; ?></option>

                            <?php } ?>

                        </select>

                      <?php } else { ?>

                        <span>Qtd. <?php echo $quantidade; ?></span>

                      <?php } ?>

                    <?php } ?>

                  </div><!--

                  --><?php if(ECOMMERCE_ATIVO == 1) { ?>

                		<span class="price">

                    	<?php echo self::mostraPreco($preco_produto * $quantidade); ?>

                    </span>

                   <?php } ?>

                </div>

            	</div>

            	<?php if($tudo == 0) { ?><!--

              	--><a href="javascript:;" class="delete-item" onClick="removeCart(<?php echo $linha_id; ?>)"></a><!--

              --><?php } ?>

          	</li>

						<?php 

						break;

				} 										

			}

		}

		else {

			switch($tipo) {

				case "carrinho": 

					?>

          <div class="carrinho_vazio">

            <i></i>

            <h4><?php echo $Recursos->Resources["cesto_de_compras_vazio"]; ?></h4>

            <a class="button invert" href="<?php echo ROOTPATH_HTTP_LANG.CARRINHO_VOLTAR; ?>"><?php echo $Recursos->Resources["cesto_de_compras_voltar"]; ?></a>

          </div>

					<?php 

					break;

				case "dropdown": 

					?>

          <div class="cart-empty">

            <div class="div_100" style="height:100%">

              <div class="div_table_cell">

                <i></i>

                <h4><?php echo $Recursos->Resources["cesto_de_compras_vazio"]; ?></h4>

                <a class="button invert" href="<?php echo ROOTPATH_HTTP_LANG.CARRINHO_VOLTAR; ?>"><?php echo $Recursos->Resources["cesto_de_compras_voltar"]; ?></a>

              </div>

            </div>

          </div>

					<?php 

					break;

			}

		}



		DB::close();

	}

}



//Inicializa instância da classe

$class_carrinho = Carrinho::getInstance();

// if(!$is_cron_file) {

// 	$class_carrinho->carrinhoLoad();

// }



$moeda_val = explode("###", $class_carrinho->getMoeda(2));

$moeda = $moeda_val[0];

$moeda_simbolo = $moeda_val[1];



$pais_cliente = $class_user->clienteData('pais');

$preco_cliente = $class_user->clienteData('pvp');



$query_rsPGratis = "SELECT zonas.*, paises.nome AS pais_nome FROM zonas, paises WHERE paises.id='$pais_cliente' AND paises.zona=zonas.id AND zonas.portes_gratis".$preco_cliente." is not NULL";

$rsPGratis = DB::getInstance()->prepare($query_rsPGratis);

$rsPGratis->execute();

$row_rsPGratis = $rsPGratis->fetch(PDO::FETCH_ASSOC);

$totalRows_rsPGratis = $rsPGratis->rowCount();

DB::close();



$GLOBALS['valor_portes_gratis'] = 0;

$nome_portes = $row_rsPGratis['pais_nome'];

$valor_portes_gratis = 0;



if($row_rsPGratis['portes_gratis'.$preco_cliente] > 0) {

	$valor_portes_gratis = number_format($row_rsPGratis['portes_gratis'.$preco_cliente], 0, '.', ''); 

}



//Faz verificação pelo peso

if($row_rsPGratis['peso_max'] > 0) {

	$peso_max = $row_rsPGratis['peso_max'];

	$num_descimal = 2;



	if((round($peso_max / 100, 2) * 100) == $peso_max) {

		$num_descimal = 0;

	}



	$valor_portes_gratis_peso = number_format($peso_max, $num_descimal, ",", " ");

}

?>