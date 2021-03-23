<?php require_once('Connections/connADMIN.php'); ?>
<?php include('geraRef.php'); ?>
<?php //ini_set('display_errors', 1);
session_start();
if(ECOMMERCE_ATIVO == 0) {
  header("Location: ".ROOTPATH_HTTP."carrinho-contacto.php");
  exit();
}

$carrinho_session = $_COOKIE[CARRINHO_SESSION];
$moeda_session = $_COOKIE["SITE_currency"];
$empty = $class_carrinho->isEmpty();

$id_cliente = 0;
if(!empty($row_rsCliente['id'])) {
  $id_cliente = $row_rsCliente['id'];
}
else {
	$_SESSION["email_user"] = $_POST['email'];	
}

$tipo_cliente = $class_user->clienteData('tipo');
$preco_cliente = $class_user->clienteData('pvp');

if($empty == 0 || ($row_rsCliente == 0 && CARRINHO_LOGIN == 1)) {
	header("Location: ".ROOTPATH_HTTP."carrinho.php");	
	exit();
}

if($_POST['nome'] != "" && $_POST['email'] != "" && $empty > 0) {
	$query_rsLinguas = "SELECT sufixo FROM linguas ORDER BY id ASC";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$totalRows_rsLinguas = $rsLinguas->rowCount();
	$row_rsLinguas = $rsLinguas->fetchAll();

	if($_SESSION["store_update_name"] == "") {
		$store_frist = $_SESSION['store_name'];
	 }
	 else {
	 	
	 	$store_frist = $_SESSION['store_update_name'];
	 }

	$nome = $_POST['nome'];
	$prepare = $_POST['prepare'];
	$pickup_data = $_POST["data"];
	$Ptime = $_POST["Ptime"];
	$deliverystatus = $_POST["Checkpickup"];
	$nome_envio = $_POST['nome_envio'];
	$morada_fatura = $_POST['morada_factura'];
	$store_name = $store_frist;
	$morada_envio = $_POST['morada_envio'];
	$cod_postal = $_POST['cod_postal'];
	$cod_postal_envio = $_POST['cod_postal_envio'];
	$localidade = $_POST['localidade'];
	$localidade_envio = $_POST['localidade_envio'];
	$telemovel = $_POST['telemovel'];
	$pais = $_POST['pais'];
	$pais_fatura = $_POST['pais_fatura'];
	$email = $_POST['email'];
	$nif = $_POST['nif'];
	$observacoes = $_POST['observacoes'];

	$pagamento = $_POST['pagamento'];
	$portes_pag = $_POST['portes_pag'];

 	$entrega = $_POST['entrega'];
	$portes_ent = $_POST['portes_env'];

	$localidade_loja = $_POST['localidade_loja'];
	$data = date('Y-m-d');
	
	$desconto_promos = 0;
	$portes_envio = 0;

	if(tableExists(DB::getInstance(), 'moedas')) {
		$moeda_val = explode("-", $moeda_session);

		$query_rsMoeda = "SELECT simbolo, codigo, abreviatura, taxa FROM moedas WHERE abreviatura=:abreviatura";
		$rsMoeda = DB::getInstance()->prepare($query_rsMoeda);
		$rsMoeda->bindParam(':abreviatura', $moeda_val['0'], PDO::PARAM_STR, 5);
		$rsMoeda->execute();
		$row_rsMoeda = $rsMoeda->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsMoeda = $rsMoeda->rowCount();

		if($totalRows_rsMoeda > 0) {
			$moeda_enc = $row_rsMoeda['codigo'];
			$simbolo_moeda = $row_rsMoeda['simbolo'];
			$codigo_moeda = $row_rsMoeda['abreviatura'];
			$valor_conversao = $row_rsMoeda['taxa'];

			if($valor_conversao == 0 || !$valor_conversao) {
				$valor_conversao = 1;
			}
		}
		else {
			$moeda_enc = "&pound;";
			$simbolo_moeda = "£";
			$codigo_moeda = "lb";
			$valor_conversao = 1;
		}
	}
	
	$preco = $class_carrinho->precoTotal() * $valor_conversao;
	$subtotal = $preco;

	//Verificar se apenas existe um Cheque Prenda no carrinho ou se também existem produtos.
	$verifica_carrinho = $class_carrinho->verificaCarrinho();
	
	//Se o carrinho apenas tiver Cheque Prenda, apenas verificamos o método de pagamento. Senão verificamos os dois métodos
	if(($verifica_carrinho == 1 && ((!isset($_POST['pagamento']) || $_POST['pagamento'] == "" || $_POST['pagamento'] == '0') || (!isset($_POST['entrega']) || $_POST['entrega'] == "" || $_POST['entrega'] == '0'))) || ($verifica_carrinho == 0 && ((!isset($_POST['pagamento']) || $_POST['pagamento'] == "" || $_POST['pagamento'] == '0')))) {
		header("Location: ".ROOTPATH_HTTP."carrinho.php?erro=1");
		exit();
	}

	//Se os métodos de pagamento e envio e respetivos valores não estiverem corretos dá erro e encaminha para o carrinho
	// echo $pagamento." - ".$portes_pag." - ".$entrega." - ".$portes_ent."<br>";
	$verificaMetodos = $class_carrinho->verificaMetodos($pagamento, $portes_pag, $entrega, $portes_ent, $pais);	

	if(!$verificaMetodos) { 
	
		header("Location: ".ROOTPATH_HTTP."carrinho.php?erro=2");
		exit();
	}
		
	//Pontos para o cliente
	if(CARRINHO_PONTOS == 1) {
		$pontos_compra = $_POST['pontos_compra'];
	}

	//Saldo a acumular
	if(CARRINHO_SALDO == 1) {
		$saldo_acumula = $_POST['saldo_acumula'];
		$saldo_compra = $_POST['saldo_compra'];
		$saldo_cliente = $_POST['saldo_cliente'];
		
		$desconto_promos = $desconto_promos + $saldo_compra;

		$preco = $preco - $saldo_compra;
	}
	
	$codigo_promocional = "";
	$codigo_promocional_desconto = "";
	$codigo_promocional_valor = 0;
	
	if(CARRINHO_CODIGOS == 1) {
		$query_rsCarCodProm = "SELECT codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";
		$rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);
		$rsCarCodProm->execute();
		$row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsCarCodProm = $rsCarCodProm->rowCount();
		
		$preco_cod = $preco;
		$desconto_promo = 0;

		if($totalRows_rsCarCodProm > 0) {
			$codigo_promocional = $row_rsCarCodProm['codigo'];
			
			$query_rsCod = "SELECT codigo, tipo_desconto, desconto FROM codigos_promocionais WHERE codigo='$codigo_promocional'";
			$rsCod = DB::getInstance()->prepare($query_rsCod);
			$rsCod->execute();
			$row_rsCod = $rsCod->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsCod = $rsCod->rowCount();
			
			$desconto_promo = $class_carrinho->calcula_cod_promo($row_rsCod['codigo']) * $valor_conversao;

			if($row_rsCod['tipo_desconto'] == 1) {
				$codigo_promocional_valor_bd = $desconto_promo; 
				$preco = $preco - $codigo_promocional_valor_bd; 
				$codigo_promocional_desconto = number_format($row_rsCod['desconto'], 0, "", "")."%";
				$codigo_promocional_valor = $class_carrinho->mostraPreco($codigo_promocional_valor_bd, 1);
			}
			else {
				$preco = $preco - $desconto_promo; 
				$codigo_promocional_valor_bd = $desconto_promo;
				$codigo_promocional_valor = $class_carrinho->mostraPreco($desconto_promo, 1);
				$codigo_promocional_desconto = ($codigo_promocional_valor_bd * 100) / $preco_cod;
				$codigo_promocional_desconto = number_format($codigo_promocional_desconto, 0, "", "")."%";
			}	
			
			$desconto_promos = $desconto_promos + $codigo_promocional_valor_bd;
		}
	}

	/*$query_rsCar = "SELECT pecas.id, pecas.nome, pecas.ref, pecas.url, pecas_cat.*,cate.*, carrinho.*, carrinho.id AS id_linha FROM carrinho LEFT JOIN l_pecas".$extensao." AS pecas ON (carrinho.produto = pecas.id AND pecas.visivel = 1) INNER JOIN l_pecas_categorias as pecas_cat ON pecas_cat.id_peca = pecas.id
	 INNER JOIN l_categorias_en as cate ON cate.id = pecas_cat.id_categoria WHERE carrinho.session = '$carrinho_session' ORDER BY pecas.ordem ASC";*/
	 $query_rsCar = "SELECT pecas.id, pecas.nome, pecas.ref, pecas.url, carrinho.*, carrinho.id AS id_linha FROM carrinho LEFT JOIN l_pecas".$extensao." AS pecas ON (carrinho.produto = pecas.id AND pecas.visivel = 1) WHERE carrinho.session = '$carrinho_session' ORDER BY pecas.ordem ASC";

	$rsCar = DB::getInstance()->prepare($query_rsCar);
	$rsCar->execute();
	$row_rsCar = $rsCar->fetchAll();
	$totalRows_rsCar = $rsCar->rowCount();

	$query_rsPaises = "SELECT nome, codigo FROM paises WHERE id='$pais'";
	$rsPaises = DB::getInstance()->prepare($query_rsPaises);
	$rsPaises->execute();
	$row_rsPaises = $rsPaises->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsPaises = $rsPaises->rowCount();
	
	$nome_pais = $row_rsPaises['nome'];
	$cod_pais = $row_rsPaises['codigo'];
	
	$query_rsPaises = "SELECT nome FROM paises WHERE id='$pais_fatura'";
	$rsPaises = DB::getInstance()->prepare($query_rsPaises);
	$rsPaises->execute();
	$row_rsPaises = $rsPaises->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsPaises = $rsPaises->rowCount();
	
	$pais_fatura = $row_rsPaises['nome'];
	
	if($pagamento != '999') {				
		$query_rsPagamento = "SELECT zonas_met_pagamento.portes, zonas_met_pagamento.tipo, met_pagamento".$extensao.".* FROM zonas_met_pagamento, met_pagamento".$extensao.", zonas, paises WHERE zonas_met_pagamento.id_zona=zonas.id AND zonas_met_pagamento.id_metodo=met_pagamento".$extensao.".id AND paises.zona=zonas.id AND paises.id='$pais' AND met_pagamento".$extensao.".id='$pagamento' ORDER BY met_pagamento".$extensao.".ordem ASC, met_pagamento".$extensao.".nome ASC";
		$rsPagamento = DB::getInstance()->prepare($query_rsPagamento);
		$rsPagamento->execute();
		$row_rsPagamento = $rsPagamento->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsPagamento = $rsPagamento->rowCount();
		
		if($row_rsPagamento['tipo'] == 1) {
			$preco_pag = $row_rsQtds['portes'];
		}
		else {
			$preco_pag = $preco - ($preco - ($preco * ($row_rsPagamento['portes'] / 100)));
		}
		
		$met_pagamento = $row_rsPagamento['nome'];
		
		if($preco_pag > 0) { 
			$met_pagamento .=" (".$Recursos->Resources["comprar_acresce"]." ".$class_carrinho->mostraPreco($preco_pag).")";
		}
		if($row_rsPagamento['descricao']) {
			$met_pagamento .= "<br>".$row_rsPagamento['descricao'];
		}
		if($row_rsPagamento['descricao2']) {
			$met_pagamento .= "<br>".$row_rsPagamento['descricao2'];
		}	
		
		$portes_envio = $portes_envio + $preco_pag;
	}
	else {
		$met_pagamento = $Recursos->Resources["pagamt_com_saldo"];
	}
	
	//Se não for só Cheque-Prenda tem método de envio	
	if($entrega > 0 && $entrega != 999) {				
		$query_rsQtds = "SELECT zonas_met_envio.portes, zonas_met_envio.tipo, zonas_met_envio.tabela, zonas_met_envio.custo, met_envio".$extensao.".*, zonas.portes_gratis".$preco_cliente.", zonas.peso_max FROM zonas_met_envio, met_envio".$extensao.", zonas, paises WHERE zonas_met_envio.id_zona=zonas.id AND zonas_met_envio.id_metodo=met_envio".$extensao.".id AND paises.zona=zonas.id AND paises.id='$pais' AND met_envio".$extensao.".id='$entrega' ORDER BY met_envio".$extensao.".ordem ASC, met_envio".$extensao.".nome ASC";
		$rsQtds = DB::getInstance()->prepare($query_rsQtds);
		$rsQtds->execute();
		$row_rsQtds = $rsQtds->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsQtds = $rsQtds->rowCount();
		
		$envio_link = $row_rsQtds['link'];
								
		//Calcula o peso do carrinho
		$total_peso = $class_carrinho->totalPeso();
										
		//Soma as unidades das peças
		$query_rsQuant = "SELECT SUM(quantidade) AS total_qtd FROM carrinho WHERE session='$carrinho_session'";
		$rsQuant = DB::getInstance()->prepare($query_rsQuant);
		$rsQuant->execute();
		$row_rsQuant = $rsQuant->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsQuant = $rsQuant->rowCount();
		
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
			
			$preco_pag = $preco_transp + $row_rsQtds['custo'];
		}
		else {
			$peso = $total_peso;
			
			if($row_rsQtds['tipo'] == 1) {
				$preco_pag = $row_rsQtds['portes'] * $row_rsQuant['total_qtd'];
			}
			else if($row_rsQtds['tipo'] == 2) {
				$preco_pag = $row_rsQtds['portes'] * $peso;
			}
			else{
				$preco_pag = 0;
			}
			
			$preco_pag = $preco_pag + $row_rsQtds['custo'];
		}								
										
		//VERIFICA SE HA CAMPANHAS DE PORTES GRATIS PARA ESTE CARRINHO
		if(CARRINHO_PORTES == 1) {
			$query_rsPGratis = "SELECT zonas.id, paises.nome AS pais_nome FROM zonas, paises WHERE paises.id='$pais' AND paises.zona=zonas.id";
			$rsPGratis = DB::getInstance()->prepare($query_rsPGratis);
			$rsPGratis->execute();
			$row_rsPGratis = $rsPGratis->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsPGratis = $rsPGratis->rowCount();
			
			$zona_cliente = $row_rsPGratis['id'];		
			$data = date('Y-m-d H:i:s');
			$produto_com_portes_gratis = 0;
									
			$query_rsCarList = "SELECT * FROM carrinho WHERE session = '$carrinho_session' ORDER BY id ASC";
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
							//Verifica se os portes gratis se aplicam com base no preço mínimo e peso máximo
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
			
			if($produto_com_portes_gratis == 1) {
				$preco_pag = 0;
			}
		}
										
		$met_envio = $row_rsQtds['nome'];
		if($localidade_loja && $localidade_loja != "") {
			$met_envio = $met_envio." (".$localidade_loja.")";
		}
		
		$met_envio_id = $row_rsQtds['id'];

		if($preco_pag > 0) {
			//Faz verificação pelo peso
      if(!$total_peso) {
      	$total_peso = $class_carrinho->totalPeso();
      }

      $portes_gratis_peso = 0;

      if($row_rsQtds['peso_max'] > 0) {
        $peso_max = $row_rsQtds['peso_max'];
        if($total_peso > $peso_max) {
          $portes_gratis_peso = 1;
        }
      }

			if($row_rsQtds['portes_gratis'.$preco_cliente] != NULL && $row_rsQtds['portes_gratis'.$preco_cliente] > 0 && $row_rsQtds['portes_gratis'.$preco_cliente] <= $preco && $portes_gratis_peso == 0) { 
				$met_envio .= " (".$Recursos->Resources["comprar_portes_gratis"].")";
			}
			else {
				$met_envio .= " (".$Recursos->Resources["comprar_acresce"]." ".$class_carrinho->mostraPreco($preco_pag)." )";
			}
		}
		else if($produto_com_portes_gratis == 1) {
			$met_envio .= " (".$Recursos->Resources["comprar_portes_gratis"].")";
		}
		
		if($row_rsQtds['descricao']) {
			$met_envio .= "<br>".nl2br($row_rsQtds['descricao']);
		}
		
		$portes_envio = $portes_envio + $preco_pag;
	}
	else if($entrega == 999) { //Se for cheque prenda não se aplica portes de entrega
		$total_peso = 0;
		$preco_pag = 0;
		$met_envio = $Recursos->Resources["comprar_portes_gratis"];
		$portes_ent = 0;
	}
	else {
		$total_peso = 0;
		$preco_pag = 0;
		$met_envio = $Recursos->Resources["nao_aplicavel"];
		$portes_ent = 0;
	}
	
	$query_rsProc = "SELECT MAX(id) FROM encomendas";
	$rsProc = DB::getInstance()->prepare($query_rsProc);
	$rsProc->execute();
	$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsProc = $rsProc->rowCount();
	
	$id_encomenda = $row_rsProc['MAX(id)'] + 1;

	$query_rsProc = "SELECT MAX(numero) FROM encomendas";
	$rsProc = DB::getInstance()->prepare($query_rsProc);
	$rsProc->execute();
	$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsProc = $rsProc->rowCount();

	$query_rsdata = "SELECT * FROM encomendas";
	$rsProcdata = DB::getInstance()->prepare($query_rsdata);
	$rsProcdata->execute();
	$row_rsdata = $rsProcdata->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsdata = $rsProcdata->rowCount();
	
	$num_encomenda = $row_rsProc['MAX(numero)'] + 1;
	
	$query_rsTxIva = "SELECT iva FROM taxa_iva WHERE id = '1'";
	$rsTxIva = DB::getInstance()->prepare($query_rsTxIva);
	$rsTxIva->execute();
	$row_rsTxIva = $rsTxIva->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsTxIva = $rsTxIva->rowCount();
	
	$valor_iva = 0;
	
	//Aplica IVA para "PT Continental" e "PT Ilhas"
	if($pais == 267 || $pais == 197) {
		$total = $preco;
		$valor_iva = $total - ($total / (1 + ($row_rsTxIva['iva'] / 100)));
	}
	
	$valor_iva = number_format(round($valor_iva, 2), 2, ".", "");
	
	$valor_final = $preco + $portes_pag + $portes_ent;
	$valor_total = $preco;
	
	$ent_pagamento = "";
	$ref_pagamento = "";
	$url_pagamento = "";
	
	//MB IfThen
	if($pagamento == 6) {
		$query_rsRefMult = "SELECT entidade, subentidade FROM met_pagamento".$extensao." WHERE id='$pagamento'";
		$rsRefMult = DB::getInstance()->prepare($query_rsRefMult);
		$rsRefMult->execute();
		$row_rsRefMult = $rsRefMult->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsRefMult = $rsRefMult->rowCount();
			  
		$ent_id = $row_rsRefMult['entidade'];
		$subent_id = $row_rsRefMult['subentidade'];
		
		$order_id = $num_encomenda;
		$order_value = $valor_final;
				
		$ref_pagamento = GenerateMbRef2 ($ent_id, $subent_id, $order_id, $order_value); 
		$ent_pagamento = $ent_id;
	}
	//MB e Cartão de Crédito EasyPay
	else if($pagamento == 7 || $pagamento == 8) {
		$order_value = $valor_final;
				
		$easypay_resp = geraRefEasypay($id_encomenda, $num_encomenda, $order_value, $nome, $email, $telemovel);

		$ref_pagamento = $easypay_resp[0];
		$url_pagamento = $easypay_resp[1];
		$ent_pagamento = EASYP_ENT;
	}

	//Se o método de pagamento for "Cobrança" OU "Pagamento na loja" fica logo com o estado "Em processamento"
	$estado = 1;
	if($pagamento == 3 || $pagamento == 4) {
		$estado = 2;
	}
	
	$language = $lang;
	if($language == '') { 
		$language = 'pt'; 
	}

	foreach($row_rsCar as $carrinho_printmes) {
		$msg = $carrinho_printmes['message'];
	}
		
	$data_enc = date('Y-m-d H:i:s');
		
	$insertSQL = "INSERT INTO encomendas (id, prepration,  store_name, pickup_data, collection_time, deliverystatus, message, numero, id_cliente, nome, nome_envio, morada_fatura, morada_envio, codpostal_fatura, codpostal_envio, localidade_fatura, localidade_envio, pais_envio, pais_fatura, email, telemovel, nif, pagamento, entidade, ref_pagamento, url_pagamento, prods_total, valor_total, valor_iva, valor_c_iva, observacoes, portes_pagamento, portes_entrega, entrega, opcao_texto, opcao, fatura_digital, data, estado, lingua, saldo_compra, codigo_promocional, codigo_promocional_desconto, codigo_promocional_valor, pontos_compra, compra_valor_saldo, pontos_compra_utilizado, envio_link, met_pagamt_id, entrega_id, moeda, valor_conversao, cod_pais) VALUES (:id, :prepare, '".$store_name."', '".$pickup_data."', '".$Ptime."', :deliverystatus, '".$msg."', :numero, :id_cliente, :nome, :nome_envio, :morada_fatura, :morada_envio, :codpostal_fatura, :codpostal_envio, :localidade_fatura, :localidade_envio, :pais_envio, :pais_fatura, :email, :telemovel, :nif, :pagamento, :entidade, :ref_pagamento, :url_pagamento, :prods_total, :valor_total, :valor_iva, :valor_c_iva, :observacoes, :portes_pagamento, :portes_entrega, :entrega, :opcao_texto, :opcao, :fatura_digital, :data, :estado, '$language', :saldo_compra, :codigo_promocional, :codigo_promocional_desconto, :codigo_promocional_valor, :pontos_compra, :compra_valor_saldo, '0', :envio_link, :met_pagamt_id, :entrega_id, :moeda, :valor_conversao, :cod_pais)";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':id', $id_encomenda, PDO::PARAM_INT);
	$rsInsert->bindParam(':deliverystatus', $deliverystatus, PDO::PARAM_INT);
	$rsInsert->bindParam(':prepare', $prepare, PDO::PARAM_INT);
	/*pickup_data*/
	$rsInsert->bindParam(':numero', $num_encomenda, PDO::PARAM_INT);
	$rsInsert->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
	$rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':nome_envio', $nome_envio, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':morada_fatura', $morada_fatura, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':morada_envio', $morada_envio, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':codpostal_fatura', $cod_postal, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':codpostal_envio', $cod_postal_envio, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':localidade_fatura', $localidade, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':localidade_envio', $localidade_envio, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':pais_fatura', $pais_fatura, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':pais_envio', $nome_pais, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':cod_pais', $cod_pais, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':email', $email, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':telemovel', $telemovel, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':nif', $nif, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':pagamento', $met_pagamento, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':entidade', $ent_pagamento, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':ref_pagamento', $ref_pagamento, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':url_pagamento', $url_pagamento, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':prods_total', $subtotal, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':valor_total', $valor_total, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':valor_iva', $valor_iva, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':valor_c_iva', $valor_final, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':observacoes', $observacoes, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':portes_pagamento', $portes_pag, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':portes_entrega', $portes_ent, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':entrega_id', $met_envio_id, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':entrega', $met_envio, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':opcao_texto', $opcao_texto, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':opcao', $valor_opcao, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':fatura_digital', $fatura_digital, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':data', $data_enc, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':estado', $estado, PDO::PARAM_INT);
	$rsInsert->bindParam(':saldo_compra', $saldo_acumula, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':codigo_promocional', $codigo_promocional, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':codigo_promocional_desconto', $codigo_promocional_desconto, PDO::PARAM_INT);
	$rsInsert->bindParam(':codigo_promocional_valor', $codigo_promocional_valor_bd, PDO::PARAM_INT);
	$rsInsert->bindParam(':pontos_compra', $pontos_compra, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':compra_valor_saldo', $saldo_compra, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':envio_link', $envio_link, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':met_pagamt_id', $pagamento, PDO::PARAM_INT);
	$rsInsert->bindParam(':moeda', $moeda_enc, PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':valor_conversao', $valor_conversao, PDO::PARAM_STR, 5);
	$rsInsert->execute();

	//Se o método de pagamento for "Cobrança" OU "Pagamento na loja" fica logo com o estado "Em processamento"
	if($pagamento == 3 || $pagamento == 4) {
		$data = date("Y-m-d H:i:s");

		$insertSQL = "INSERT INTO enc_estados_historico (id, id_encomenda, estado, data, notificado) VALUES ('', :id, '2', :data, '1')";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(":data", $data, PDO::PARAM_STR, 5);			
		$rsInsert->bindParam(":id", $id_encomenda, PDO::PARAM_INT);
		$rsInsert->execute();
	}
		
	//Se descontou saldo entao vamos retirar da conta do cliente
	if(CARRINHO_SALDO == 1) {
		//Converter saldo para € (caso necessário)
		if($valor_conversao > 0) {
			$saldo_compra = round($saldo_compra / $valor_conversao, 2);
		}
			
		if($saldo_compra > 0) {
			$cliente = $id_cliente;
			$valor = $saldo_compra;
			$encomenda_id = $id_encomenda;
			$operacao = 2;
			$validado = 1;
			$bonus_id = 0;
			$cheque_id = 0;
			$data = $data_enc;
			
			$query_rsProc = "SELECT MAX(id) FROM clientes_saldo";
			$rsProc = DB::getInstance()->prepare($query_rsProc);
			$rsProc->execute();
			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsProc = $rsProc->rowCount();
			
			$id_max = $row_rsProc['MAX(id)'] + 1;					
			$numero = $num_encomenda;
			$detalhe = "Saldo Descontado - Encomenda N.".$numero;
	
			$insertSQL = "INSERT INTO clientes_saldo (id, cliente_id, valor, encomenda_id, operacao, detalhe, data, validado, cheque_id) VALUES ('$id_max', '$cliente', '$valor', '$encomenda_id', '$operacao', '$detalhe', '$data', '$validado', '$cheque_id')";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->execute();
	
			$insertSQL = "UPDATE clientes SET saldo='$saldo_cliente' WHERE id='$cliente'";
			$rsInsert = DB::getInstance()->prepare($insertSQL);			
			$rsInsert->execute();
		}
	}
		
	foreach($row_rsCar as $carrinho) {
		$cheque_nome = "";
		$cheque_email = "";
		$cheque_prenda = 0;

		$user_review = $carrinho['message'];

		if($carrinho['cheque_prenda'] == 1) {
			$cheque_prenda = 1;
			$nome = $Recursos->Resources["cheque_prenda_2"];
						
			$preco = $carrinho['preco'];
			$url = "";
			$ref = "";
			$image = ROOTPATH_HTTP."imgs/elem/geral.jpg";
			$qtd = 1;
			$iva_prod = $valor_iva;
			
			$cheque_nome = $carrinho['cheque_nome'];
			$cheque_email = $carrinho['cheque_email'];
			
			$nome .= " - ".$cheque_nome." - ".$cheque_email;
			
			for($i = 0; $i < $qtd; $i++) {
				$insertSQL = "INSERT INTO cheques (num, encomenda, data, valor, nome, email, utilizado, val) VALUES ('', '$id_encomenda', '$data_enc', '$preco', '$cheque_nome', '$cheque_email', '0', '0')";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->execute();
			}
		}
		else {
			$linha_id = $carrinho['id_linha'];	
			$produto = $carrinho['produto']; 
			$cat_mea = $carrinho['cat_mae'];     								
			$image = $class_produtos->imgProduto($produto, 2, 1);   
			$nome = $carrinho['nome'];
			$ref = $carrinho['ref'];
			$url = $carrinho['url'];
			$qtd = $carrinho['quantidade'];
			$opcoes = $carrinho['opcoes'];
			$preco = $carrinho['preco'] * $valor_conversao;
			$opcoes_id = 0;
			
			$desconto_produto = $carrinho['desconto'];
			
			$data_enc = date('Y-m-d H:i:s');
			
			$tam1 = 0;
			$tam2 = 0;
			$tam3 = 0;
			$tam4 = 0;
			$tam5 = 0;

			if($carrinho['op1'] > 0) {
				$tam1 = $carrinho['op1'];
			}
			if($carrinho['op2'] > 0) {
				$tam2 = $carrinho['op2'];
			}
			if($carrinho['op3'] > 0) {
				$tam3 = $carrinho['op3'];
			}
			if($carrinho['op4'] > 0) {
				$tam4 = $carrinho['op4'];
			}
			if($carrinho['op5'] > 0) {
				$tam5 = $carrinho['op5'];
			}
			
			$totalRows_rsT = 0;
			if(CARRINHO_TAMANHOS == 1) {		
				$query_rsT = "SELECT * FROM l_pecas_tamanhos WHERE op1=:tam1 AND op2=:tam2 AND op3=:tam3 AND op4=:tam4 AND op5=:tam5 AND peca=:id";
				$rsT = DB::getInstance()->prepare($query_rsT);
				$rsT->bindParam(':id', $produto, PDO::PARAM_INT); 
				$rsT->bindParam(':tam1', $tam1, PDO::PARAM_INT); 
				$rsT->bindParam(':tam2', $tam2, PDO::PARAM_INT); 
				$rsT->bindParam(':tam3', $tam3, PDO::PARAM_INT); 
				$rsT->bindParam(':tam4', $tam4, PDO::PARAM_INT); 
				$rsT->bindParam(':tam5', $tam5, PDO::PARAM_INT); 
				$rsT->execute();
				$row_rsT = $rsT->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsT = $rsT->rowCount();
			}
														
			if($totalRows_rsT > 0) {
				$opcoes_id = $row_rsT['id'];

				if($row_rsT['ref']) {
					$ref = $row_rsT['ref'];
				}
					
				//Ver se tem imagem associada a este tamanho
				$query_rsImg = "SELECT imagem4 FROM l_pecas_imagens WHERE id_tamanho = '".$row_rsT["id"]."'";
				$rsImg = DB::getInstance()->prepare($query_rsImg);
				$rsImg->execute();
				$row_rsImg = $rsImg->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsImg = $rsImg->rowCount();
				
				if($totalRows_rsImg > 0 && $row_rsImg["imagem4"] && file_exists(ROOTPATH."imgs/produtos/".$row_rsImg['imagem4'])) {
					$image = ROOTPATH_HTTP."imgs/produtos/".$row_rsImg["imagem4"];
				}
				
				//Tirar o stock
				$insertSQL = "UPDATE l_pecas_tamanhos SET stock=stock-'$qtd' WHERE peca='$produto' AND op1='$tam1' AND op2='$tam2' AND op3='$tam3' AND op4='$tam4' AND op5='$tam5'";	
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->execute();
				
				$stock_disponivel = $class_produtos->stockProduto($produto, $tam1, $tam2, $tam3, $tam4, $tam5, 4);	
				
				if($stock_disponivel < 0) {
					$insertSQL = "UPDATE l_pecas_tamanhos SET stock='0' WHERE peca='$produto' AND op1='$tam1' AND op2='$tam2' AND op3='$tam3' AND op4='$tam4' AND op5='$tam5'";	
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->execute();
				}
			}
			else {
				if($totalRows_rsLinguas > 0) {
					foreach($row_rsLinguas as $lingua) {
						$insertSQL = "UPDATE l_pecas_".$lingua['sufixo']." SET stock=stock-'$qtd' WHERE id='$produto'";		
						$rsInsert = DB::getInstance()->prepare($insertSQL);
						$rsInsert->execute();
					}
				}
				
				$stock_disponivel = $class_produtos->stockProduto($produto, 0, 0, 0, 0, 0, 4);	
				
				if($stock_disponivel < 0) {
					if($totalRows_rsLinguas > 0) {
						foreach($row_rsLinguas as $lingua) {
							$insertSQL = "UPDATE l_pecas_".$lingua['sufixo']." SET stock='0' WHERE id='$produto'";		
							$rsInsert = DB::getInstance()->prepare($insertSQL);
							$rsInsert->execute();
						}
					}						
				}
			}							

			$query_rsProduto = "SELECT iva FROM l_pecas_en WHERE id=:id";
			$rsProduto = DB::getInstance()->prepare($query_rsProduto);
			$rsProduto->bindParam(':id', $produto, PDO::PARAM_INT); 
			$rsProduto->execute();
			$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsProduto = $rsProduto->rowCount();
			
			$iva_prod = $row_rsProduto['iva'];


		}

		if($qtd > 0) {
			$id_oferta = 0;
			if($row_rsCarrinho['id_oferta'] > 0) {
				$id_oferta = 1;
			}
			
			$insertSQL = "INSERT INTO encomendas_produtos (id_encomenda, id_oferta, produto, ref, imagem1, url, opcoes, qtd, preco, desconto, iva, produto_id, opcoes_id, user_message, cheque_prenda, cheque_nome, cheque_email) VALUES (:id_encomenda, :id_oferta, :produto, :ref, :imagem, :url, :opcoes, :qtd, :preco, :desconto, :iva, :produto_id, :opcoes_id, :user_review, :cheque_prenda, :cheque_nome, :cheque_email)";

		
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':id_encomenda', $id_encomenda, PDO::PARAM_INT);
			$rsInsert->bindParam(':id_oferta', $id_oferta, PDO::PARAM_INT);
			$rsInsert->bindParam(':produto', $nome, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':ref', $ref, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':imagem', $image, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':url', $url, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':opcoes', $opcoes, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':qtd', $qtd, PDO::PARAM_INT);
			$rsInsert->bindParam(':preco', $preco, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':desconto', $desconto_produto, PDO::PARAM_INT);
			$rsInsert->bindParam(':iva', $iva_prod, PDO::PARAM_INT);
			$rsInsert->bindParam(':produto_id', $produto, PDO::PARAM_INT);
			$rsInsert->bindParam(':opcoes_id', $opcoes_id, PDO::PARAM_INT);
			$rsInsert->bindParam(':user_review', $user_review, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':cheque_prenda', $cheque_prenda, PDO::PARAM_INT);
			$rsInsert->bindParam(':cheque_nome', $cheque_nome, PDO::PARAM_STR, 30);
			$rsInsert->bindParam(':cheque_email', $cheque_email, PDO::PARAM_STR, 30);
			$rsInsert->execute();
			
			if($cheque_prenda != 1) {
				foreach($row_rsLinguas as $lingua) {
					$insertSQL = "UPDATE l_pecas_".$lingua['sufixo']." SET contagem_vendas=contagem_vendas+'$qtd' WHERE id='$produto'";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->execute();
				}				
			}				
		}
	}
	
	//Apaga o carrinho
	$deleteSQL  = "DELETE FROM carrinho where session='$carrinho_session'";
	$rsDelete = DB::getInstance()->prepare($deleteSQL);
	$rsDelete->execute();
		
	$query_insertSQL = "DELETE FROM carrinho_cliente WHERE id_cliente=:user";
	$insertSQL = DB::getInstance()->prepare($query_insertSQL);
	$insertSQL->bindParam(':user', $row_rsCliente["id"], PDO::PARAM_INT);
	$insertSQL->execute();	
	
	$deleteSQL  = "DELETE FROM carrinho_comprar where session='$carrinho_session'";
	$rsDelete = DB::getInstance()->prepare($deleteSQL);
	$rsDelete->execute();
	
	if(CARRINHO_CODIGOS == 1) {		
		$deleteSQL  = "DELETE FROM carrinho_cod_prom where session='$carrinho_session'";
		$rsDelete = DB::getInstance()->prepare($deleteSQL);
		$rsDelete->execute();
	}

	DB::close();
		
	header("Location: carrinho-comprar4.php");	
}
else {
	header("Location: carrinho.php");	
}
?>