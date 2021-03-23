<?php

class Produtos {

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



	//1 - Devolve o array com a informação / 2 - Devolve apenas o valor do desconto

	public static function promocaoProduto($produto, $tipo = 1) {

		global $extensao, $Recursos;



		$query_rsProduto = "SELECT id, preco, preco_ant, categoria, marca, promocao, promocao_desconto, promocao_datai, promocao_dataf, promocao_titulo, promocao_texto, promocao_pagina FROM l_pecas".$extensao." WHERE id=:id AND visivel='1'";

		$rsProduto = DB::getInstance()->prepare($query_rsProduto);

		$rsProduto->bindParam(':id', $produto, PDO::PARAM_INT); 

		$rsProduto->execute();

		$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsProduto = $rsProduto->rowCount();



		$data_hoje = date('Y-m-d');



		$query_rsPromocao = "SELECT desconto, id_categoria, id_marca, id_peca, datai, dataf, titulo, texto, pagina FROM l_promocoes".$extensao." WHERE datai <= '$data_hoje' AND dataf >= '$data_hoje' AND visivel='1' ORDER BY desconto DESC, id ASC";

		$rsPromocao = DB::getInstance()->prepare($query_rsPromocao);

		$rsPromocao->execute();

		$row_rsPromocao = $rsPromocao->fetchAll();

		$totalRows_rsPromocao = $rsPromocao->rowCount();



		$query_rsPromoGeral = "SELECT * FROM l_promocoes_textos".$extensao." WHERE id = '1'";

		$rsPromoGeral = DB::getInstance()->prepare($query_rsPromoGeral);

		$rsPromoGeral->execute();

		$row_rsPromoGeral = $rsPromoGeral->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPromoGeral = $rsPromoGeral->rowCount();



		$array_promocao = array();

		$valor_promo = 0;



		//Se não existirem dados especificos no produto, carrega os gerais

		$datai_prod = $row_rsProduto['promocao_datai'];

		if(!$datai_prod) {

			$datai_prod = $row_rsPromoGeral['datai'];

		}



		$dataf_prod = $row_rsProduto['promocao_dataf'];

		if(!$dataf_prod) {

			$dataf_prod = $row_rsPromoGeral['dataf'];

		}



		$titulo_prod = $row_rsProduto['promocao_titulo'];

		if(!$titulo_prod) {

			$titulo_prod = $row_rsPromoGeral['titulo'];

		}



		$texto_prod = $row_rsProduto['promocao_texto'];

		if(!$texto_prod) {

			$texto_prod = $row_rsPromoGeral['texto'];

		}



		$pagina_prod = $row_rsProduto['promocao_pagina'];

		if(!$pagina_prod) {

			$pagina_prod = $row_rsPromoGeral['pagina'];

		}



		if($row_rsProduto['preco_ant'] != NULL && $row_rsProduto['preco_ant'] != '' && $row_rsProduto['preco_ant'] != '0.00' && $row_rsProduto['preco_ant'] > $row_rsProduto['preco'] && $datai_prod != '' && $dataf_prod != '' && strtotime($datai_prod) <= strtotime($data_hoje) && strtotime($dataf_prod) >= strtotime($data_hoje)) {

			$data_inicio = data_hora($datai_prod, $extensao, 2);

			$data_fim = data_hora($dataf_prod, $extensao, 2);



			$datas_promocao = str_replace('#datai#', $data_inicio, $Recursos->Resources['promocao_datas']);

			$datas_promocao = str_replace('#dataf#', $data_fim, $datas_promocao);



			$array_promocao['0'] = $datas_promocao;

			$array_promocao['1'] = $titulo_prod;

			$array_promocao['2'] = $texto_prod;

			$array_promocao['3'] = $pagina_prod;



			$valor_promo = round(100 - (($row_rsProduto['preco'] * 100) / $row_rsProduto['preco_ant']));

		}

		else if($row_rsProduto['promocao'] == 1 && $row_rsProduto['promocao_desconto'] > 0 && $datai_prod != '' && $dataf_prod != '' && strtotime($datai_prod) <= strtotime($data_hoje) && strtotime($dataf_prod) >= strtotime($data_hoje)) {	

			$data_inicio = data_hora($datai_prod, $extensao, 2);

			$data_fim = data_hora($dataf_prod, $extensao, 2);



			$datas_promocao = str_replace('#datai#', $data_inicio, $Recursos->Resources['promocao_datas']);

			$datas_promocao = str_replace('#dataf#', $data_fim, $datas_promocao);



			$array_promocao['0'] = $datas_promocao;

			$array_promocao['1'] = $titulo_prod;

			$array_promocao['2'] = $texto_prod;

			$array_promocao['3'] = $pagina_prod;



			$valor_promo = $row_rsProduto['promocao_desconto'];

		}

		else if($totalRows_rsPromocao > 0) {

			$promocao = 0;



			foreach($row_rsPromocao as $promo) {

				$id_categoria = 0;

				$id_marca = 0;

				$id_peca = 0;



				if($promo['id_categoria'] != 0) {

					if(CATEGORIAS == 1) { 

						if($row_rsProduto["categoria"] == $promo['id_categoria']) {

							$id_categoria = 1;

						}

						else {

							$query_rsCat = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id='".$row_rsProduto["categoria"]."'";

							$rsCat = DB::getInstance()->prepare($query_rsCat);

							$rsCat->execute();

							$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsCat = $rsCat->rowCount();

							

							if($row_rsCat["cat_mae"] > 0) {

								if($row_rsCat["cat_mae"] == $promo['id_categoria']) {

									$id_categoria = 1;

								}

								else {

									$query_rsCat2 = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id='".$row_rsCat["cat_mae"]."'";

									$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

									$rsCat2->execute();

									$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

									$totalRows_rsCat2 = $rsCat2->rowCount();



									if($row_rsCat2["cat_mae"] > 0 && $row_rsCat2["cat_mae"] == $promo['id_categoria']) {

										$id_categoria = 1;

									}

								}

							}

						}

					}

					else if(CATEGORIAS == 2) {

						$query_rsProdCats = "SELECT id_categoria FROM l_pecas_categorias WHERE id_peca = :id_peca";

						$rsProdCats = DB::getInstance()->prepare($query_rsProdCats);

						$rsProdCats->bindParam(':id_peca', $row_rsProduto["id"], PDO::PARAM_INT);

						$rsProdCats->execute();

						$totalRows_rsProdCats = $rsProdCats->rowCount();



						if($totalRows_rsProdCats > 0) {

							while(($row_rsProdCats = $rsProdCats->fetch()) && $id_categoria == 0) {

								if($row_rsProdCats["id_categoria"] == $promo['id_categoria']) {

									$id_categoria = 1;

								}

								else {

									$query_rsCat1 = "SELECT cat_mae FROM l_categorias_en WHERE id = :id";

									$rsCat1 = DB::getInstance()->prepare($query_rsCat1);

									$rsCat1->bindParam(':id', $row_rsProdCats["id_categoria"], PDO::PARAM_INT);

									$rsCat1->execute();

									$totalRows_rsCat1 = $rsCat1->rowCount();

									$row_rsCat1 = $rsCat1->fetch(PDO::FETCH_ASSOC);



									if($totalRows_rsCat1 > 0 && $row_rsCat1['cat_mae'] > 0) {

										if($row_rsCat1['cat_mae'] == $promo['id_categoria']) {

											$id_categoria = 1;

										}

										else {

											$query_rsCat2 = "SELECT cat_mae FROM l_categorias_en WHERE id = :id";

											$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

											$rsCat2->bindParam(':id', $row_rsCat1['cat_mae'], PDO::PARAM_INT);

											$rsCat2->execute();

											$totalRows_rsCat2 = $rsCat2->rowCount();

											$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);



											if($totalRows_rsCat2 > 0 && $row_rsCat2['cat_mae'] == $promo['id_categoria']) {

												$id_categoria = 1;

											}

										}

									}

								}

							}

						}

					}					

				}



				if($promo['id_peca'] != 0 && $row_rsProduto["id"] == $promo['id_peca']) {

					$id_peca = 1;

				}



				if($promo['id_marca'] != 0 && $row_rsProduto["marca"] == $promo['id_marca']) {

					$id_marca = 1;

				}



				if(

					($promo['id_peca'] == 0 && $promo['id_categoria'] == 0 && $promo['id_marca'] == 0) //não tem nada selecionado, logo é para toda a loja

					|| ($promo['id_peca'] !=0 && $id_peca == 1) //OU tem um produto específico, logo aplica apenas a esse produto

					|| ($promo['id_peca'] == 0 && ($promo['id_categoria'] == 0 || ($promo['id_categoria'] != 0 && $id_categoria == 1)) && ($promo['id_marca'] == 0 || ($promo['id_marca'] != 0 && $id_marca == 1))) //OU tem uma categoria OU uma marca selecionada e aí o produto tem de pertencer a essa categoria e/ou marca

				) {

					if($promocao == 0 || ($promocao == 1 && $valor_promo < $promo['desconto'])) {

						$promocao = 1;

						$valor_promo = $promo['desconto'];



						$data_inicio = data_hora($promo['datai'], $extensao, 2);

						$data_fim = data_hora($promo['dataf'], $extensao, 2);



						$datas_promocao = str_replace('#datai#', $data_inicio, $Recursos->Resources['promocao_datas']);

						$datas_promocao = str_replace('#dataf#', $data_fim, $datas_promocao);



						$array_promocao['0'] = $datas_promocao;

						$array_promocao['1'] = $promo['titulo'];

						$array_promocao['2'] = $promo['texto'];

						$array_promocao['3'] = $promo['pagina'];

					}

				}

			}

		}



		DB::close();



		if($tipo == 2) {

			return $valor_promo;

		}

		else {

			return $array_promocao;	

		}

	}



	public static function precoProduto($produto, $type = 2, $quantidade = 1, $tamanho = 0, $simbolo = "before", $antigo = "after") {

		global $extensao, $moeda;

		

		$query_rsTaxa = "SELECT local FROM moedas WHERE abreviatura='$moeda'";

		$rsTaxa = DB::getInstance()->prepare($query_rsTaxa);

		$rsTaxa->execute();

		$row_rsTaxa = $rsTaxa->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsTaxa = $rsTaxa->rowCount();



		if($row_rsTaxa['local'] == 1) {

			$simbolo2 = "after";

		}

		else {

			$simbolo2 = "before";

		}



		if($simbolo != $simbolo2) {

			$simbolo = $simbolo2;

		}

				

		$query_rsProduto = "SELECT id, categoria, marca, preco, preco_ant, promocao, promocao_datai, promocao_dataf, promocao_desconto FROM l_pecas".$extensao." WHERE id=:id";

		$rsProduto = DB::getInstance()->prepare($query_rsProduto);

		$rsProduto->bindParam(':id', $produto, PDO::PARAM_INT, 5); 

		$rsProduto->execute();

		$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);

		

		$totalRows_rsProduto = $rsProduto->rowCount();

		

		$preco = $row_rsProduto["preco"];

		$preco_ant = 0;

		$promocao = 0;

		$desc_cliente = 0;

		

		//Verificar tipo de cliente

		$tipo_cliente = 1;

		$preco_cliente = 1;

		$desc_cliente = 0;

		

		$row_rsCliente = User::getInstance()->isLogged();

		if($row_rsCliente != 0) {

			$tipo_cliente = User::getInstance()->clienteData('tipo');

			$preco_cliente = User::getInstance()->clienteData('pvp');

			$desc_cliente = User::getInstance()->clienteData('desconto');			

		}

	

		//Verificar tamanhos do produto

		if(CARRINHO_TAMANHOS == 1) {

			if($tamanho != 0) {

				$query_rsTamDef = "SELECT preco FROM l_pecas_tamanhos WHERE id='$tamanho' AND peca=:id";

			}

			else {

				$query_rsTamDef = "SELECT preco FROM l_pecas_tamanhos WHERE peca=:id AND defeito='1'";	

			}

			$rsTamDef = DB::getInstance()->prepare($query_rsTamDef);

			$rsTamDef->bindParam(':id', $row_rsProduto["id"], PDO::PARAM_INT, 5); 

			$rsTamDef->execute();

			$row_rsTamDef = $rsTamDef->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsTamDef = $rsTamDef->rowCount();

				

			if($totalRows_rsTamDef > 0) {

				if($row_rsTamDef['preco'] > 0) {

					$preco = $row_rsTamDef['preco'];

				}

			}

		}

		

		//Verificar Promo do Produto

		$data_hoje = date('Y-m-d');



		$query_rsPromocao = "SELECT desconto, id_categoria, id_marca, id_peca FROM l_promocoes".$extensao." WHERE datai <= '$data_hoje' AND dataf >= '$data_hoje' AND visivel='1' ORDER BY desconto DESC, id ASC";

		$rsPromocao = DB::getInstance()->prepare($query_rsPromocao);

		$rsPromocao->execute();

		$row_rsPromocao = $rsPromocao->fetchAll();

		$totalRows_rsPromocao = $rsPromocao->rowCount();



		$query_rsPromoGeral = "SELECT datai, dataf FROM l_promocoes_textos".$extensao." WHERE id = '1'";

		$rsPromoGeral = DB::getInstance()->prepare($query_rsPromoGeral);

		$rsPromoGeral->execute();

		$row_rsPromoGeral = $rsPromoGeral->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPromoGeral = $rsPromoGeral->rowCount();



		//Se não existirem datas especificas no produto, carrega as gerais

		$datai_prod = $row_rsProduto['promocao_datai'];

		$dataf_prod = $row_rsProduto['promocao_dataf'];

		if(!$datai_prod) {

			$datai_prod = $row_rsPromoGeral['datai'];

		}

		if(!$dataf_prod) {

			$dataf_prod = $row_rsPromoGeral['dataf'];

		}



		if($row_rsProduto['preco_ant'] != NULL && $row_rsProduto['preco_ant'] != '' && $row_rsProduto['preco_ant'] != '0.00' && $row_rsProduto['preco_ant'] > $row_rsProduto['preco'] && $datai_prod != '' && $dataf_prod != '' && strtotime($datai_prod) <= strtotime($data_hoje) && strtotime($dataf_prod) >= strtotime($data_hoje)) {

			$preco_ant = $row_rsProduto["preco_ant"];

		}

		else if($row_rsProduto['promocao'] == 1 && $row_rsProduto['promocao_desconto'] > 0 && $datai_prod != '' && $dataf_prod != '' && strtotime($datai_prod) <= strtotime($data_hoje) && strtotime($dataf_prod) >= strtotime($data_hoje)) {	

			$promocao = number_format($row_rsProduto['promocao_desconto'], 0, '', '');

			

			$preco_ant = $preco;

			$preco = $preco - ($preco * ($promocao / 100));

		}

		else if($totalRows_rsPromocao > 0) {

			$tem_promo = 0;

			$valor_promo = 0;



			foreach($row_rsPromocao as $promo) {

				$id_categoria = 0;

				$id_marca = 0;

				$id_peca = 0;



				if($promo['id_categoria'] != 0) {

					if(CATEGORIAS == 1) {

						if($row_rsProduto["categoria"] == $promo['id_categoria']) {

							$id_categoria = 1;

						}

						else {

							$query_rsCat = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id='".$row_rsProduto["categoria"]."'";

							$rsCat = DB::getInstance()->prepare($query_rsCat);

							$rsCat->execute();

							$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsCat = $rsCat->rowCount();

							

							if($row_rsCat["cat_mae"] > 0) {

								if($row_rsCat["cat_mae"] == $promo['id_categoria']) {

									$id_categoria = 1;

								}

								else {

									$query_rsCat2 = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id='".$row_rsCat["cat_mae"]."'";

									$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

									$rsCat2->execute();

									$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

									$totalRows_rsCat2 = $rsCat2->rowCount();



									if($row_rsCat2["cat_mae"] > 0 && $row_rsCat2["cat_mae"] == $promo['id_categoria']) {

										$id_categoria = 1;

									}

								}

							}

						}

					}

					else if(CATEGORIAS == 2) {

						$query_rsProdCats = "SELECT id_categoria FROM l_pecas_categorias WHERE id_peca = :id_peca";

						$rsProdCats = DB::getInstance()->prepare($query_rsProdCats);

						$rsProdCats->bindParam(':id_peca', $row_rsProduto["id"], PDO::PARAM_INT);

						$rsProdCats->execute();

						$totalRows_rsProdCats = $rsProdCats->rowCount();



						if($totalRows_rsProdCats > 0) {

							while(($row_rsProdCats = $rsProdCats->fetch()) && $id_categoria == 0) {

								if($row_rsProdCats["id_categoria"] == $promo['id_categoria']) {

									$id_categoria = 1;

								}

								else {

									$query_rsCat1 = "SELECT cat_mae FROM l_categorias_en WHERE id = :id";

									$rsCat1 = DB::getInstance()->prepare($query_rsCat1);

									$rsCat1->bindParam(':id', $row_rsProdCats["id_categoria"], PDO::PARAM_INT);

									$rsCat1->execute();

									$totalRows_rsCat1 = $rsCat1->rowCount();

									$row_rsCat1 = $rsCat1->fetch(PDO::FETCH_ASSOC);



									if($totalRows_rsCat1 > 0 && $row_rsCat1['cat_mae'] > 0) {

										if($row_rsCat1['cat_mae'] == $promo['id_categoria']) {

											$id_categoria = 1;

										}

										else {

											$query_rsCat2 = "SELECT cat_mae FROM l_categorias_en WHERE id = :id";

											$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

											$rsCat2->bindParam(':id', $row_rsCat1['cat_mae'], PDO::PARAM_INT);

											$rsCat2->execute();

											$totalRows_rsCat2 = $rsCat2->rowCount();

											$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);



											if($totalRows_rsCat2 > 0 && $row_rsCat2['cat_mae'] == $promo['id_categoria']) {

												$id_categoria = 1;

											}

										}

									}

								}

							}

						}

					}

				}



				if($promo['id_peca'] != 0 && $row_rsProduto["id"] == $promo['id_peca']) {

					$id_peca = 1;

				}



				if($promo['id_marca'] != 0 && $row_rsProduto["marca"] == $promo['id_marca']) {

					$id_marca = 1;

				}

				

				if(

					($promo['id_peca'] == 0 && $promo['id_categoria'] == 0 && $promo['id_marca'] == 0) //não tem nada selecionado, logo é para toda a loja

					|| ($promo['id_peca'] !=0 && $id_peca == 1) //OU tem um produto específico, logo aplica apenas a esse produto

					|| ($promo['id_peca'] == 0 && ($promo['id_categoria'] == 0 || ($promo['id_categoria'] != 0 && $id_categoria == 1)) && ($promo['id_marca'] == 0 || ($promo['id_marca'] != 0 && $id_marca == 1))) //OU tem uma categoria OU uma marca selecionada e aí o produto tem de pertencer a essa categoria e/ou marca

				) {

					if($tem_promo == 0 || ($tem_promo == 1 && $valor_promo < $promo['desconto'])) {

						$tem_promo = 1;

						$valor_promo = $promo['desconto'];

						$promocao = number_format($promo['desconto'], 0, '', '');

			

						$preco_ant = $preco;

						$preco = $preco - ($preco * ($promocao / 100));

					}

				}

			}

		}

							

		//Verificar Descontos por quantidade

		if(CARRINHO_DESCONTOS != 0) {

			$query_rsDescProd = "SELECT desconto FROM l_pecas_desconto WHERE id_peca=:id AND min<='$quantidade' AND (max>='$quantidade' OR max IS NULL OR max='') ORDER BY desconto DESC";

			$rsDescProd = DB::getInstance()->prepare($query_rsDescProd);

			$rsDescProd->bindParam(':id', $row_rsProduto["id"], PDO::PARAM_INT, 5); 

			$rsDescProd->execute();

			$row_rsDescProd = $rsDescProd->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsDescProd = $rsDescProd->rowCount();

			DB::close();

			

			if($totalRows_rsDescProd > 0) {

				$desconto_produto = number_format($row_rsDescProd['desconto'], 0, '', '');

				

				$preco_ant = $preco;

				$preco = $preco - ( $preco * ($desconto_produto / 100));

			}

		}

		

		$query_rsRole = "SELECT * FROM roll";

		$rsRole = DB::getInstance()->prepare($query_rsRole);

		$rsRole->execute();

		$totalRows_rsRoll = $rsRole->fetchAll();

		DB::close();



		$query_rs_supp = "SELECT * FROM l_pecas_en where id =".$produto;

		$rsP_supp = DB::getInstance()->prepare($query_rs_supp); 

		$rsP_supp->execute();

		$totalRows_rsP_supp = $rsP_supp->rowCount();

		$row_rsP_supp = $rsP_supp->fetch(PDO::FETCH_ASSOC);





		$row_rsCliente = User::getInstance()->isLogged();

			foreach ($totalRows_rsRoll as $role) { 



			if($row_rsCliente["roll"] == $role['roll_name']){



			$reguler_Price = 'reguler_price_'.$role['roll_name'];

		    $reguler_Pricee = $row_rsP_supp[$reguler_Price];



		    $selling_price = 'selling_price_'.$role['roll_name'];

		    $selling_pricee = $row_rsP_supp[$selling_price];

				

			if( $selling_pricee != 0 OR $reguler_Pricee != 0 OR $selling_pricee == "" OR $reguler_Pricee == "")

			{


					if($reguler_Pricee != 0)

					{

						$preco = $reguler_Pricee - ($reguler_Pricee * ($desc_cliente / 100));	

					}

					else

					{

						$preco = $preco - ($preco * ($desc_cliente / 100));

					}

					



					if($selling_pricee != 0)

					{

						$preco_ant = $selling_pricee - ($selling_pricee * ($desc_cliente / 100));	

					}

					else

					{

						$preco_ant = $preco_ant - ($preco_ant * ($desc_cliente / 100));

					}		

			//}

		

		}

		else

		{

			$preco = $preco - ($preco * ($desc_cliente / 100));

			$preco_ant = $preco_ant - ($preco_ant * ($desc_cliente / 100));

		}

	}

}

	

		$preco_string = "";

		$antigo_string = "";

		

		if($type == 0) { //PREÇO ATUAL SIMPLES

			

			$preco_string = $preco;

		}

		else if($type == 1) { //PREÇO ATUAL

			$preco_string = Carrinho::getInstance()->mostraPreco($preco,0, 1, $simbolo);

		}

		else if($type == 2) { //PREÇO STRING



			$preco_string = "<strong>".Carrinho::getInstance()->mostraPreco($preco, 0, 1, $simbolo)."</strong>";

			

			if($preco_ant > $preco ) {

				$antigo_string = Carrinho::getInstance()->mostraPreco($preco_ant, 0, 1, $simbolo);

				

				if($antigo == "after") {

					$preco_string = "<p>".$preco_string."</p><span class='list_txt'>".$antigo_string."</span>";

				}

				else if($antigo == "before") {

					$preco_string = "<span>".$antigo_string."</span>".$preco_string;

				}

			}

		}



		else if($type == 3) { //PREÇO ANTIGO

			$preco_string = Carrinho::getInstance()->mostraPreco($preco_ant, 0, 1, $simbolo);

		}

		else if($type == 4) { //QUANTO POUPOU

			$preco_string = "";

			

			if($preco_ant > $preco) {

				$diferenca = $preco_ant - $preco;

				$preco_string = Carrinho::getInstance()->mostraPreco($diferenca, 1, 1, $simbolo);	

			}

		}



		DB::close();



		return $preco_string;

	}

		

	public static function labelsProduto($id, $type = 1, $origem = 'listagem') {

		global $extensao, $class_user, $Recursos;



		$tipo_cliente = $class_user->clienteData('tipo');

	

		$query_rsProd = "SELECT id, categoria, marca, preco, preco_ant, promocao, promocao_desconto, promocao_datai, promocao_dataf, novidade FROM l_pecas".$extensao." WHERE id = :id";

		$rsProd = DB::getInstance()->prepare($query_rsProd);

		$rsProd->bindParam(':id', $id, PDO::PARAM_INT);

		$rsProd->execute();

		$row_rsProd = $rsProd->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsProd = $rsProd->rowCount();

			

		$preco = $row_rsProd['preco'];

		$promocao = 0;

		$valor_promocao = 0;

		$valor_promo = 0;

		$div = "";

	

		$portes_gratis = self::portesProduto($id);



		$data_hoje = date('Y-m-d');



		$query_rsPromoGeral = "SELECT * FROM l_promocoes_textos".$extensao." WHERE id = '1'";

		$rsPromoGeral = DB::getInstance()->prepare($query_rsPromoGeral);

		$rsPromoGeral->execute();

		$row_rsPromoGeral = $rsPromoGeral->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPromoGeral = $rsPromoGeral->rowCount();



		//Se não existirem dados especificos no produto, carrega os gerais

		$datai_prod = $row_rsProd['promocao_datai'];

		if(!$datai_prod) {

			$datai_prod = $row_rsPromoGeral['datai'];

		}



		$dataf_prod = $row_rsProd['promocao_dataf'];

		if(!$dataf_prod) {

			$dataf_prod = $row_rsPromoGeral['dataf'];

		}



		$query_rsPromocao = "SELECT desconto, id_categoria, id_marca, id_peca FROM l_promocoes".$extensao." WHERE datai <= '$data_hoje' AND dataf >= '$data_hoje' AND visivel='1' ORDER BY desconto DESC, id ASC";

		$rsPromocao = DB::getInstance()->prepare($query_rsPromocao);

		$rsPromocao->execute();

		$row_rsPromocao = $rsPromocao->fetchAll();

		$totalRows_rsPromocao = $rsPromocao->rowCount();



		if($row_rsProd['preco_ant'] != NULL && $row_rsProd['preco_ant'] != '' && $row_rsProd['preco_ant'] != '0.00' && $row_rsProd['preco_ant'] > $row_rsProd['preco'] && $datai_prod != '' && $dataf_prod != '' && strtotime($datai_prod) <= strtotime($data_hoje) && strtotime($dataf_prod) >= strtotime($data_hoje)) {

			$promocao = 1;

			$valor_promocao = number_format((100 - (($preco * 100) / $row_rsProd['preco_ant'])), 0, "", "")."%";

		}

		else if($row_rsProd['promocao'] == 1 && $row_rsProd['promocao_desconto'] > 0 && $datai_prod != '' && $dataf_prod != '' && strtotime($datai_prod) <= strtotime($data_hoje) && strtotime($dataf_prod) >= strtotime($data_hoje)) {	

			$promocao = 1;

			$valor_promocao = number_format($row_rsProd['promocao_desconto'], 0, "", "")."%";

		}

		else if($totalRows_rsPromocao > 0) {

			foreach($row_rsPromocao as $promo) {

				$id_categoria = 0;

				$id_marca = 0;

				$id_peca = 0;



				if($promo['id_categoria'] != 0) {

					if(CATEGORIAS == 1) {

						if($row_rsProd["categoria"] == $promo['id_categoria']) {

							$id_categoria = 1;

						}

						else {

							$query_rsCat = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id='".$row_rsProd["categoria"]."'";

							$rsCat = DB::getInstance()->prepare($query_rsCat);

							$rsCat->execute();

							$row_rsCat = $rsCat->fetch(PDO::FETCH_ASSOC);

							$totalRows_rsCat = $rsCat->rowCount();

							

							if($row_rsCat["cat_mae"] > 0) {

								if($row_rsCat["cat_mae"] == $promo['id_categoria']) {

									$id_categoria = 1;

								}

								else {

									$query_rsCat2 = "SELECT cat_mae FROM l_categorias".$extensao." WHERE id='".$row_rsCat["cat_mae"]."'";

									$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

									$rsCat2->execute();

									$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);

									$totalRows_rsCat2 = $rsCat2->rowCount();



									if($row_rsCat2["cat_mae"] > 0 && $row_rsCat2["cat_mae"] == $promo['id_categoria']) {

										$id_categoria = 1;

									}

								}

							}

						}

					}

					else if(CATEGORIAS == 2) {

						$query_rsProdCats = "SELECT id_categoria FROM l_pecas_categorias WHERE id_peca = :id_peca";

						$rsProdCats = DB::getInstance()->prepare($query_rsProdCats);

						$rsProdCats->bindParam(':id_peca', $id, PDO::PARAM_INT);

						$rsProdCats->execute();

						$totalRows_rsProdCats = $rsProdCats->rowCount();



						if($totalRows_rsProdCats > 0) {

							while(($row_rsProdCats = $rsProdCats->fetch()) && $id_categoria == 0) {

								if($row_rsProdCats["id_categoria"] == $promo['id_categoria']) {

									$id_categoria = 1;

								}

								else {

									$query_rsCat1 = "SELECT cat_mae FROM l_categorias_en WHERE id = :id";

									$rsCat1 = DB::getInstance()->prepare($query_rsCat1);

									$rsCat1->bindParam(':id', $row_rsProdCats["id_categoria"], PDO::PARAM_INT);

									$rsCat1->execute();

									$totalRows_rsCat1 = $rsCat1->rowCount();

									$row_rsCat1 = $rsCat1->fetch(PDO::FETCH_ASSOC);



									if($totalRows_rsCat1 > 0 && $row_rsCat1['cat_mae'] > 0) {

										if($row_rsCat1['cat_mae'] == $promo['id_categoria']) {

											$id_categoria = 1;

										}

										else {

											$query_rsCat2 = "SELECT cat_mae FROM l_categorias_en WHERE id = :id";

											$rsCat2 = DB::getInstance()->prepare($query_rsCat2);

											$rsCat2->bindParam(':id', $row_rsCat1['cat_mae'], PDO::PARAM_INT);

											$rsCat2->execute();

											$totalRows_rsCat2 = $rsCat2->rowCount();

											$row_rsCat2 = $rsCat2->fetch(PDO::FETCH_ASSOC);



											if($totalRows_rsCat2 > 0 && $row_rsCat2['cat_mae'] == $promo['id_categoria']) {

												$id_categoria = 1;

											}

										}

									}

								}

							}

						}

					}

				}



				if($promo['id_peca'] != 0 && $row_rsProd["id"] == $promo['id_peca']) {

					$id_peca = 1;

				}



				if($promo['id_marca'] != 0 && $row_rsProd["marca"] == $promo['id_marca']) {

					$id_marca = 1;

				}



				if(

					($promo['id_peca'] == 0 && $promo['id_categoria'] == 0 && $promo['id_marca'] == 0) //não tem nada selecionado, logo é para toda a loja

					|| ($promo['id_peca'] !=0 && $id_peca == 1) //OU tem um produto específico, logo aplica apenas a esse produto

					|| ($promo['id_peca'] == 0 && ($promo['id_categoria'] == 0 || ($promo['id_categoria'] != 0 && $id_categoria == 1)) && ($promo['id_marca'] == 0 || ($promo['id_marca'] != 0 && $id_marca == 1))) //OU tem uma categoria OU uma marca selecionada e aí o produto tem de pertencer a essa categoria e/ou marca

				) {

					if($promocao == 0 || ($promocao == 1 && $valor_promo < $promo['desconto'])) {

						$promocao = 1;

						$valor_promocao = number_format($promo['desconto'],0, "", "")."%";

						$valor_promo = $promo['desconto'];

					}

				}

			}

		}

		

		if($type == 1) {

			if($promocao == 1) {

				if($origem != 'listagem') {

					// $div = '<div class="prods_label promo">'.$Recursos->Resources["promocao"].'</div>';

					$valor_promocao = number_format($promo['desconto'],0, "", "")."%";

					$div = '<div class="prods_label promo_val">-'.$valor_promocao.'</div>';

				}

			}

			else if($row_rsProd['novidade'] == 1) {

				$div = '<div class="prods_label new">'.$Recursos->Resources["novidade"].'</div>';

			}

			else if($portes_gratis == 1) {

				// $div = '<div class="prods_label portes">'.$Recursos->Resources["portes"].'</div>';

			}

			else {

				$div = '';

			}

		}

		else {

			if($promocao == 1) {

				$div = '<div class="prods_label promo_val">-'.$valor_promocao.'</div>';

			}

			else {

				$div = '';

			}

		}



		DB::close();

			

		return $div;

	}

	

	public static function portesProduto($id) {

		global $extensao, $class_user;

	

		$portes_gratis = 0;

		$tipo_cliente = $class_user->clienteData('tipo');

		$pais_cliente = $class_user->clienteData('pais');



		$query_rsProd = "SELECT preco, marca, categoria, peso FROM l_pecas".$extensao." WHERE id = :id";

		$rsProd = DB::getInstance()->prepare($query_rsProd);

		$rsProd->bindParam(':id', $id, PDO::PARAM_INT);

		$rsProd->execute();

		$row_rsProd = $rsProd->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsProd = $rsProd->rowCount();	

	

		if($pais_cliente > 0) {

			$pais = $pais_cliente;

		}

		else {

			$pais = 197;

		}



		$peso = $row_rsProd['peso'];

		$preco = $row_rsProd['preco'];

	

		$query_rsPaisesZona = "SELECT zona FROM paises WHERE id = '$pais'";

		$rsPaisesZona = DB::getInstance()->prepare($query_rsPaisesZona);

		$rsPaisesZona->execute();

		$row_rsPaisesZona = $rsPaisesZona->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsPaisesZona = $rsPaisesZona->rowCount();

	

		if($totalRows_rsPaisesZona > 0) {

			$query_rsZona = "SELECT portes_gratis1, peso_max FROM zonas WHERE id = '".$row_rsPaisesZona['zona']."'";

			$rsZona = DB::getInstance()->prepare($query_rsZona);

			$rsZona->execute();

			$row_rsZona = $rsZona->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsZona = $rsZona->rowCount();

	

			if($totalRows_rsZona > 0) {

				if($row_rsZona['portes_gratis1'] > 0 && $preco >= $row_rsZona['portes_gratis1'] && $peso <= $row_rsZona['peso_max']) {

					$portes_gratis = 1;

				}

			}

		}

		

		if($portes_gratis == 0) {

			$data = date('Y-m-d H:i:s');



			//Verificar se a marca do produto tem portes grátis

			if(ECC_MARCAS==1){

				$query_rsPortesMarca = "SELECT p.id FROM portes_gratis p LEFT JOIN portes_gratis_marcas pm ON pm.portes_gratis = p.id WHERE pm.marca = '".$row_rsProd['marca']."' AND p.datai <= '$data' AND p.dataf >= '$data' AND p.visivel=1 AND (min_encomenda = 0 OR min_encomenda IS NULL OR (min_encomenda > 0 AND min_encomenda <= '$preco')) AND (peso_max = 0 OR peso_max IS NULL OR (peso_max > 0 AND peso_max >= '$peso'))"; 

				$rsPortesMarca = DB::getInstance()->prepare($query_rsPortesMarca);

				$rsPortesMarca->execute();

				$totalRows_rsPortesMarca = $rsPortesMarca->rowCount();

		

				if($totalRows_rsPortesMarca > 0) {

					$portes_gratis = 1;

				}

			}

	

			if($portes_gratis == 0) {

				//Verificar se a categoria do produto tem portes grátis

				$query_rsPortesCategoria = "SELECT p.id FROM portes_gratis p LEFT JOIN portes_gratis_categorias pc ON pc.portes_gratis = p.id WHERE (pc.categoria = '".$row_rsProd['categoria']."') AND p.datai <= '$data' AND p.dataf >= '$data' AND p.visivel=1 AND (min_encomenda = 0 OR min_encomenda IS NULL OR (min_encomenda > 0 AND min_encomenda <= '$preco')) AND (peso_max = 0 OR peso_max IS NULL OR (peso_max > 0 AND peso_max >= '$peso'))"; 

				$rsPortesCategoria = DB::getInstance()->prepare($query_rsPortesCategoria);

				$rsPortesCategoria->execute();

				$totalRows_rsPortesCategoria = $rsPortesCategoria->rowCount();

	

				if($totalRows_rsPortesCategoria > 0) {

					$portes_gratis = 1;

				}

			}

		}

		

		DB::close();



		return $portes_gratis;

	}

	

	public static function stockProduto($id, $tam1 = "", $tam2 = "", $tam3 = "", $tam4 = "", $tam5 = "", $tipo = 1) {

		global $extensao, $Recursos;

	

		$query_rsP = "SELECT stock, nao_limitar_stock, descricao_stock, message_text FROM l_pecas".$extensao." WHERE visivel='1' AND id='$id'";

		$rsP = DB::getInstance()->prepare($query_rsP);

		$rsP->execute();

		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsP = $rsP->rowCount();

		

		$where = "";

		$valor_stock = 0;

		

		if($tam1) {

			$where .= " AND op1='$tam1'";

		}

		if($tam2) {

			$where .= " AND op2='$tam2'";

		}

		if($tam3) {

			$where .= " AND op3='$tam3'";

		}

		if($tam4) {

			$where .= " AND op4='$tam4'";

		}

		if($tam5) {

			$where .= " AND op5='$tam5'";

		}

		

		if(CARRINHO_TAMANHOS == 1) {

			$query_rsT = "SELECT stock FROM l_pecas_tamanhos WHERE peca='$id'".$where;

			$rsT = DB::getInstance()->prepare($query_rsT);

			$rsT->execute();

			$row_rsT = $rsT->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsT = $rsT->rowCount();

		}

		

		$stock_disp = $row_rsP['stock'];

		if($totalRows_rsT > 0) {

			$stock_disp = $row_rsT['stock'];

		}

		

		if($stock_disp > 0 || $row_rsP['nao_limitar_stock'] == 1) {

			$stock = "<i class='stock has-stock has_bg contain'></i>";

			$msg = $Recursos->Resources["com_stock"];

		}

		else {

			$stock = "<i class='stock no-stock has_bg contain'></i>";	

			$msg = $Recursos->Resources["sem_stock"];

		}

		

		if($row_rsP['descricao_stock']) {

			$msg .= ": ".$row_rsP['descricao_stock'];

		}

		if($row_rsP['message_text']) {

			$msg = "please Select message text";

		}
		

		if($row_rsP['nao_limitar_stock'] == 1 && $tipo != 4) {

			$valor_stock = "20";

		}

		else {

			$valor_stock = $stock_disp;

		}



		DB::close();

		

		if($tipo == 1) {

			return $stock; // icon do stock

		}

		else if($tipo == 2 || $tipo == 4) { //tipo = 4 » stock real para usar no comprar3

			return $valor_stock; // stock disponivel

		}

		else if($tipo == 3) {

			return $stock." ".$msg; // mensagem e icon do stock

		}

		else {

			return $msg; // mensagem do stock

		}

	}

	

	public static function temTamanhos($prod) {

		$return = 0;



		if(CARRINHO_TAMANHOS == 1) {

			$query_rsTamanhos = "SELECT COUNT(id) AS total FROM l_pecas_tamanhos WHERE peca='$prod'";

			$rsTamanhos = DB::getInstance()->prepare($query_rsTamanhos);

			$rsTamanhos->execute();

			$row_rsTamanhos = $rsTamanhos->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsTamanhos = $rsTamanhos->rowCount();

			DB::close();



			if($row_rsTamanhos['total'] > 0) {

				$return = 1;

			}

		}



		return $return;

	}

	

	public static function tamanhosProduto($prod, $tam1 = "", $tam2 = "", $tam3 = "", $tam4 = "", $tipo = 0) {

		global $extensao, $Recursos;

		

		if(CARRINHO_TAMANHOS == 1) {

			$atributo = 1;

			$select = "";

			$opcoes = "AND l_pecas_tamanhos.car1=:carecteristica AND l_pecas_tamanhos.op1=l_caract_opcoes_en.id";

				

			if($tam1) {

				$atributo = 2;

				$select = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.car2!='0' AND l_pecas_tamanhos.car2=l_caract_categorias_en.id";

				$opcoes = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.car2=:carecteristica AND l_pecas_tamanhos.op2=l_caract_opcoes_en.id";

				$defeito = " AND op1='$tam1'";

			}

			if($tam2) {

				$atributo = 3;

				$select = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.car3!='0' AND l_pecas_tamanhos.car3=l_caract_categorias_en.id";

				$opcoes = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.car3=:carecteristica AND l_pecas_tamanhos.op3=l_caract_opcoes_en.id";

				$defeito = " AND op1='$tam1' AND op2='$tam2'";

			}

			if($tam3) {

				$atributo = 4;

				$select = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.op3='$tam3' AND l_pecas_tamanhos.car4!='0' AND l_pecas_tamanhos.car4=l_caract_categorias_en.id";

				$opcoes = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.op3='$tam3' AND l_pecas_tamanhos.car4=:carecteristica AND l_pecas_tamanhos.op4=l_caract_opcoes_en.id";

				$defeito = " AND op1='$tam1' AND op2='$tam2' AND op3='$tam3'";

			}

			if($tam4) {

				$atributo = 5;

				$select = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.op3='$tam3' AND l_pecas_tamanhos.op4='$tam4' AND l_pecas_tamanhos.car5!='0' AND l_pecas_tamanhos.car5=l_caract_categorias_en.id";

				$opcoes = " AND l_pecas_tamanhos.op1='$tam1' AND l_pecas_tamanhos.op2='$tam2' AND l_pecas_tamanhos.op3='$tam3' AND l_pecas_tamanhos.op4='$tam4' AND l_pecas_tamanhos.car5=:carecteristica AND l_pecas_tamanhos.op5=l_caract_opcoes_en.id";

				$defeito = " AND op1='$tam1' AND op2='$tam2' AND op3='$tam3' AND op4='$tam4'";

			}

				

			if($select != "") {

				$query_rsTamanho = "SELECT l_caract_categorias_en.* FROM l_pecas_tamanhos, l_caract_categorias_en WHERE l_pecas_tamanhos.peca='$prod'".$select." GROUP BY l_caract_categorias_en.id ORDER BY l_caract_categorias_en.ordem ASC, l_caract_categorias_en.nome ASC";

				$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);

				$rsTamanho->execute();

				$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsTamanho = $rsTamanho->rowCount();

			}

			else {		

				$query_rsTamanho = "SELECT l_caract_categorias_en.*, l_pecas_tamanhos.op1, l_pecas_tamanhos.op2 FROM l_pecas_tamanhos, l_caract_categorias_en WHERE l_pecas_tamanhos.peca='$prod' AND ((l_pecas_tamanhos.car1!=0 AND l_pecas_tamanhos.op1>0) OR (l_pecas_tamanhos.car2!=0 AND l_pecas_tamanhos.op2>0)) AND IF(l_pecas_tamanhos.car1!=0 AND l_pecas_tamanhos.op1>0, l_pecas_tamanhos.car1=l_caract_categorias_en.id, IF(l_pecas_tamanhos.car2!=0 AND l_pecas_tamanhos.op2>0, l_pecas_tamanhos.car2=l_caract_categorias_en.id, null)) GROUP BY l_caract_categorias_en.id ORDER BY l_caract_categorias_en.ordem ASC, l_caract_categorias_en.nome ASC";

				$rsTamanho = DB::getInstance()->prepare($query_rsTamanho);

				$rsTamanho->execute();

				$row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsTamanho = $rsTamanho->rowCount();

			}

			

			if($totalRows_rsTamanho > 0) { 

				$query_rsDefeito = "SELECT * FROM l_pecas_tamanhos WHERE peca='$prod' AND defeito='1'".$defeito;

				$rsDefeito = DB::getInstance()->prepare($query_rsDefeito);

				$rsDefeito->execute();

				$row_rsDefeito = $rsDefeito->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsDefeito = $rsDefeito->rowCount();

				

				$produto_caract = $row_rsTamanho['id'];

				

				$query_rsOp = "SELECT l_caract_opcoes_en.* FROM l_pecas_tamanhos, l_caract_opcoes_en WHERE l_pecas_tamanhos.peca='$prod'".$opcoes." GROUP BY l_caract_opcoes_en.id ORDER BY l_caract_opcoes_en.ordem ASC, l_caract_opcoes_en.nome ASC";		

				$rsOp = DB::getInstance()->prepare($query_rsOp);

				if(hasParameter($query_rsOp, ':carecteristica')) $rsOp->bindParam(':carecteristica', $produto_caract, PDO::PARAM_STR);

				$rsOp->execute();

				$row_rsOp = $rsOp->fetchAll();

				$totalRows_rsOp = $rsOp->rowCount();

			}

				

			DB::close();



			if($totalRows_rsOp > 0) {

				$nome_inpt = "caract_".$atributo."_".$prod;

				?>

				<div class="div_100 tamanhos_divs<?php if($row_rsTamanho['tipo'] == 1) echo " cores"; ?>">

					<input type="hidden" name="atributo<?php echo $atributo; ?>_<?php echo $prod; ?>" id="atributo<?php echo $atributo; ?>_<?php echo $prod; ?>" value="<?php echo $row_rsTamanho['nome']; ?>" />

					<?php if($row_rsTamanho['tipo'] == 0) { ?>

						<!-- <label class="list_tit" for="<?php echo $nome_inpt; ?>"><?php echo $row_rsTamanho['label']; ?></label> -->

						<!-- 

						-->

						<div class="select_holder icon-down">

							<select class="detalhe_sels" name="<?php echo $nome_inpt; ?>" id="<?php echo $nome_inpt; ?>" onChange="altera_caract(<?php echo $prod; ?>, this.name); carregaQuantidades(<?php echo $prod; ?>, this.name);" <?php if($row_rsDefeito["op".$atributo] == $op['id'] || $totalRows_rsOp == 1) echo 'data-attr="carrega_atributos"'; ?>>

								<option value="0"><?php echo $Recursos->Resources["selecione"]; ?></option>

								<?php foreach($row_rsOp as $op) { ?>

									<option value="<?php echo $op['id']; ?>" <?php if($row_rsDefeito["op".$atributo] == $op['id'] || $totalRows_rsOp == 1) echo "selected"; ?>><?php echo $op['nome']; ?></option>

								<?php } ?>

							</select>

						</div>

					<?php }

					else if($row_rsTamanho['tipo'] == 1) { ?>

						<h5 class="list_tit"><?php echo $row_rsTamanho['label']; ?></h5>

						<div class="div_100">	

							<?php foreach($row_rsOp as $op) {

								$style = "";



								if($op['cor']) {

									$style = 'background:'.$op['cor'];

								}



								if($op['imagem1'] && file_exists('imgs/uploads/'.$op['imagem1'])) {

									$style = "background:url('".ROOTPATH_HTTP."imgs/uploads/".$op['imagem1']."')";

								}

								?><!--

								--><div class="detalhe_opcoes" title="<?php echo $op['nome']; ?>" alt="<?php echo $op['nome']; ?>">

									<input type="radio" name="<?php echo $nome_inpt; ?>" id="<?php echo $nome_inpt; ?>_<?php echo $op['id']; ?>" value="<?php echo $op['id']; ?>" <?php if($row_rsDefeito["op".$atributo] == $op['id'] || $totalRows_rsOp == 1) echo "checked"; ?> onClick="altera_caract(<?php echo $prod; ?>, this.name); carregaQuantidades(<?php echo $prod; ?>, this.name); $('.detalhe_opcoes_txt').html('<?php echo $op['nome']; ?>');" <?php if($row_rsDefeito["op".$atributo] == $op['id'] || $totalRows_rsOp == 1) echo 'data-attr="carrega_atributos"'; ?> />

									<span title="<?php echo $op['nome']; ?>" alt="<?php echo $op['nome']; ?>" class="has_bg" style="<?php echo $style; ?>">&nbsp;</span>

								</div><!--

							--><?php } ?>

						</div>

					<?php } ?>

				</div>

				<?php if($row_rsTamanho['tipo'] == 1) { ?>

					<span class="textos detalhe_opcoes_txt"></span>

				<?php } ?>

			<?php }

		}

	}

	

	public static function imgProduto($prod, $tipo = 0, $carrinho = 0) {

		global $extensao;

	

		$query_rsProduto = "SELECT imagem1, imagem2, imagem3, imagem4 FROM l_pecas".$extensao." AS pecas WHERE pecas.visivel = 1 AND pecas.id = :id";

		$rsProduto = DB::getInstance()->prepare($query_rsProduto);

		$rsProduto->bindParam(':id', $prod, PDO::PARAM_INT, 5); 

		$rsProduto->execute();

		$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);

		$totalRows_rsProduto = $rsProduto->rowCount();

		DB::close();     

		

		//Usar a versão PNG da imagem geral para não dar problemas nos emails

		if($carrinho == 1) {

			$img_name = "imgs/elem/geral.svg";

			

			//Versão pequena da imagem para o carrinho

			if($row_rsProduto['imagem4'] && file_exists(ROOTPATH."imgs/produtos/".$row_rsProduto['imagem4'])) {

				$img_name = "imgs/produtos/".$row_rsProduto['imagem4']; 

			}

			else if($row_rsProduto['imagem3'] && file_exists(ROOTPATH."imgs/produtos/".$row_rsProduto['imagem3'])) {

				$img_name = "imgs/produtos/".$row_rsProduto['imagem3']; 

			}



			$img = "background-image:url(".ROOTPATH_HTTP.$img_name.")";

		}

		else {

			$img = "background-image:url(".ROOTPATH_HTTP."imgs/elem/geral.svg); background-size: 150px";

			$img_lazy = "elem/geral.svg";

			$img_name = "imgs/elem/geral.svg";

			$fill = "imgs/produtos/fill.gif"; 

			

			if($row_rsProduto['imagem3'] && file_exists(ROOTPATH."imgs/produtos/".$row_rsProduto['imagem3'])) {

				$img = "background-image:url(".ROOTPATH_HTTP."imgs/produtos/".$row_rsProduto['imagem3'].")";

				$img_lazy = "produtos/".$row_rsProduto['imagem3'];

				$img_name = "imgs/produtos/".$row_rsProduto['imagem3'];

				$fill = "imgs/produtos/fill3.gif"; 

			}

			else if($row_rsProduto['imagem2'] && file_exists(ROOTPATH."imgs/produtos/".$row_rsProduto['imagem2'])) {

				$img = "background-image:url(".ROOTPATH_HTTP."imgs/produtos/".$row_rsProduto['imagem2'].")";

				$img_lazy = "produtos/".$row_rsProduto['imagem2'];

				$img_name = "imgs/produtos/".$row_rsProduto['imagem2']; 

				$fill = "imgs/produtos/fill2.gif"; 

			}

			

			if(!file_exists(ROOTPATH.$fill)) {

				$fill = "imgs/produtos/fill.gif"; 

			}



			if(!file_exists(ROOTPATH.$img_name)) {

				$img = "background-image:url(".ROOTPATH_HTTP."imgs/produtos/".$row_rsProduto['imagem1'].")";

				$img_lazy = "produtos/".$row_rsProduto['imagem1'];

				$img_name = "imgs/produtos/".$row_rsProduto['imagem1']; 

			}

		}



		if($tipo == 0) {

			return $img;

		}

		else if($tipo == 2) {

			return ROOTPATH_HTTP.$img_name;

		}

		else if($tipo == 3) {

			return $img_lazy;

		}

		else {

			return ROOTPATH_HTTP.$fill;

		}

	}



	public static function divsProduto($produto, $classes, $favoritos = 0) {

		global $extensao, $Recursos, $class_user, $row_rsCliente;



		$where_list = "";

		$totalRows_rsFavoritos = 0;



		if(tableExists(DB::getInstance(), 'lista_desejo')) {

			if($row_rsCliente != 0) {

		    $id_cliente = $row_rsCliente['id'];

		    $where_list = " AND cliente = '$id_cliente'";

			}

			else {

		    $wish_session = $_COOKIE[WISHLIST_SESSION];

		

		    if($wish_session) {

		    	$where_list = " AND session = '$wish_session'";

		    }

			}



			$query_rsCategoria = "SELECT nome FROM l_categorias".$extensao." WHERE visivel = 1 AND id=:id";

			$rsCategoria = DB::getInstance()->prepare($query_rsCategoria);

			$rsCategoria->bindParam(':id', $produto['categoria'], PDO::PARAM_INT, 5); 

			$rsCategoria->execute();

			$row_rsCategoria = $rsCategoria->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsCategoria = $rsCategoria->rowCount();



 			$query_rsFiltros = "SELECT * FROM l_pecas_tamanhos WHERE  peca = ".$produto["id"]." ";

			$rsFiltros = DB::getInstance()->prepare($query_rsFiltros); 

			$rsFiltros->execute();

			$row_rsFiltros = $rsFiltros->fetchAll(PDO::FETCH_ASSOC);

			$totalRows_rsFiltros = $rsFiltros->rowCount();



			if($where_list){

				$query_rsFavoritos = "SELECT id FROM lista_desejo WHERE produto = :id ".$where_list;

				$rsFavoritos = DB::getInstance()->prepare($query_rsFavoritos);

				$rsFavoritos->bindParam(':id', $produto['id'], PDO::PARAM_INT, 5); 

				$rsFavoritos->execute();

				$row_rsFavoritos = $rsFavoritos->fetch(PDO::FETCH_ASSOC);

				$totalRows_rsFavoritos = $rsFavoritos->rowCount();

			}



			DB::close();

		}

		?>



		<div class="produtos_divs text-left <?php echo $classes; ?>" data-id="<?php echo $produto['id']; ?>">



			<figure>

				<picture class="productImg  img has_bg contain lazy" data-src="<?php echo self::imgProduto($produto['id'], 3); ?>" data-big="<?php echo self::imgProduto($produto['id'], 2, 0); ?>">

        	<?php echo getFill('produtos', 3); ?>

          <?php echo self::labelsProduto($produto['id'], 1, 'listagem'); ?>

          <div class="favoritos icon-favoritos <?php if($totalRows_rsFavoritos > 0) echo " active"; ?>" onClick="adiciona_favoritos(<?php echo $produto['id']; ?>, this, event);"></div>

        </picture>

        <figcaption class="info">

        	<h4 class="textos bold"><?php echo $produto['nome']; ?></h4>
        	<?php if($produto['stock'] > 0 ) { ?>
        	<h4>Available Stock - <?php echo $produto['stock']; ?></h4>
       		<?php } else if($produto["nao_limitar_stock"] == 1) { ?>
       		<h4>Available Stock</h4>
       		<?php } else {?>
       		<h4>Out Of Stock</h4>
       		<?php } ?>
        	<!-- <h5 class="list_subtit"><small><?php echo $row_rsCategoria['nome']; ?></small></h5> -->

        	<div class="price-and-review">

				<!-- <div class="column rating">

					<?php //echo self::produtoAvrageRating($produto['id']); ?>

				</div> -->

				<div class="column">

					<div class="subtitulos preco"><?php echo self::precoProduto($produto['id']); ?></div>

				</div>

				<div class="column">

					<?php echo self::labelsProduto($produto['id'], 2, 'listagem'); ?>

				</div>

        	</div>

        </figcaption>

        <a class="linker" href="<?php echo $produto['url']; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/produtos-detalhe.php" data-ajaxTax="<?php echo $produto['id']; ?>" data-remote="false" data-pagetrans="produtos-detalhe" data-product="true" data-detail="1"></a>

			</figure>



		<?php //} }?>

			<div class="div_100 action_holder">

				<div class="row collapse">

						<input name="preco_final" id="preco_final_<?php echo $produto['id']; ?>" type="hidden" value="<?php echo self::precoProduto($produto['id'], 0); ?>" />

				<?php 

				if($produto["enquiry_type"] != 1 &&  ($totalRows_rsFiltros > 0)   ) { ?>

					<div class="column">
						<a class="linker button invert2" style="width: 220px; height: 50px;" href="<?php echo $produto['url']; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/produtos-detalhe.php" data-ajaxTax="<?php echo $produto['id']; ?>" class="button invert2 action"  data-label="listagem" data-remote="false" data-pagetrans="produtos-detalhe" data-product="true" data-detail="1">Select options</a>
					</div>

				<?php } 

				else if($produto["enquiry_type"] == 1 && $rsFiltros == 1){ ?>

					<div class="column">
						<a class="linker button invert2"  href="<?php echo $produto['url']; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/produtos-detalhe.php" data-ajaxTax="<?php echo $produto['id']; ?>" data-remote="false" data-pagetrans="produtos-detalhe" data-product="true" data-detail="1">Make Enquiry</a>
					</div>				

				<?php } else { ?>

					<div class="column">
						<a href="javascript:;" class="button invert2 action" data-product="<?php echo $produto['id']; ?>" data-label="listagem"></a>
					</div>

				<?php } ?>

				</div>

			</div>

		</div>

		

    <?php

	}



	public static function produtoRating($produtoId) {

		global $extensao;

		$query_rsTotal = "SELECT * FROM l_pecas_reviews".$extensao." AS reviews WHERE reviews.product_id = ".$produtoId." ORDER BY reviews.create_date DESC";

        $reviewsTotal = DB::getInstance()->prepare($query_rsTotal);

        $reviewsTotal->execute();

        $reviewsAll = $reviewsTotal->fetchAll(PDO::FETCH_ASSOC);

        DB::close();



		$one_star = 0;

        $two_star = 0;

        $three_star = 0;

        $four_star = 0;

        $five_star = 0;



        foreach ($reviewsAll as $key => $reviews_count){

          $rating_value = $reviews_count['rating'];

          if ($rating_value == 1) {

              $one_star++;

          }

          if ($rating_value == 2) {

              $two_star++;

          }

          if ($rating_value == 3) {

              $three_star++;

          }

          if ($rating_value == 4) {

              $four_star++;

          }

          if ($rating_value == 5) {

              $five_star++;

          }

        }



        $starTotal = $five_star+$four_star+$three_star+$two_star+$one_star;



        $star1 = $one_star;

        $star2 = $two_star;

        $star3 = $three_star;

        $star4 = $four_star;

        $star5 = $five_star;



        $tot_stars = $star1 + $star2 + $star3 + $star4 + $star5;



        for ($i=1;$i<=5;++$i) {

          $var = "star$i";

          $count = $$var;

          $percent[$i] = $count * 100 / $tot_stars;

        }



        $avarage_rating = (5*$five_star + 4*$four_star + 3*$three_star + 2*$two_star + 1*$one_star) / $starTotal;



        $produtoRating['avarage_rating'] = $avarage_rating;

        $produtoRating['percent'] = $percent;



        return $produtoRating;



	}



	public static function produtoAvrageRating($produtoid) {

		$produtoRating = self::produtoRating($produtoid);

		$avarage_rating = $produtoRating['avarage_rating'];

		?>

		<span class="star-rating">

	       <?php for ($x = 1; $x <= 5; $x++) {

	            if ($x <= $avarage_rating) {

	                echo '<i class="fa fa-star"></i>';

	            }else{

	                echo '<i class="fa fa-star-o"></i>'; 

	            }

	        } ?>

	   </span>

	<?php }



	public static function produtoallrating($produtoid) {



		global $extensao;

		$query_rsTotal = "SELECT * FROM l_pecas_reviews".$extensao." AS reviews WHERE reviews.product_id = ".$produtoid." ORDER BY reviews.create_date DESC";

        $reviewsTotal = DB::getInstance()->prepare($query_rsTotal);

        $reviewsTotal->execute();

        $reviewsAll = $reviewsTotal->fetchAll(PDO::FETCH_ASSOC);

        DB::close();

        //exit();

        return $reviewsAll;

	}



	public static function get_brand($brand_id) {

		global $extensao;

		$query_rsMarcas = "SELECT * FROM l_marcas".$extensao." WHERE id = ".$brand_id;

		$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);

		$rsMarcas->execute();

		$row_rsMarcas = $rsMarcas->fetch(PDO::FETCH_ASSOC);

		DB::close();

		return $row_rsMarcas;

	}



	public static function produto_loadmore_rating($produtoid,$offset,$limit) {



		global $extensao;

		$query_rsTotal = "SELECT * FROM l_pecas_reviews".$extensao." AS reviews WHERE reviews.product_id = ".$produtoid." ORDER BY reviews.create_date DESC LIMIT ".$limit." OFFSET ".$offset;

        $reviewsTotal = DB::getInstance()->prepare($query_rsTotal);

        $reviewsTotal->execute();

        $reviewsAll = $reviewsTotal->fetchAll(PDO::FETCH_ASSOC);

        DB::close();

        //exit();

        return $reviewsAll;

	}



	public static function reviews_html($rating,$reviewer,$date_time,$comment) { ?>



		<div class="reviews-members pt-4 pb-4">

          <div class="media">

              <a href="#"><img alt="Generic placeholder image" src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" class="mr-3 rounded-pill"></a>

              <div class="media-body">

                  <div class="reviews-members-header">

                      <span class="star-rating">

                           <?php for ($x = 1; $x <= 5; $x++) {

                                if ($x <= $rating) {

                                    echo '<i class="fa fa-star"></i>';

                                }else{

                                    echo '<i class="fa fa-star-o"></i>'; 

                                }

                            } ?>

                      </span>

                      <h6 class="mb-1"><a class="text-black" href="#"><?php echo $reviewer; ?></a></h6>

                      <p class="text-gray"><?php echo date("D, F j Y", strtotime($date_time)); ?></p>

                  </div>

                  <div class="reviews-members-body">

                      <p><?php echo $comment; ?></p>

                  </div>

              </div>

          </div>

      	</div>

    	<hr>

		

	<?php }



}







// Inicializa instância da classe

$class_produtos = Produtos::getInstance();



?>

