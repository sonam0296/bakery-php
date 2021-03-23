<?php require_once('../Connections/connADMIN.php'); ?>
<?php
session_start();
$query_store = "SELECT * FROM l_pecas".$extensao." as pro
INNER JOIN l_pecas_store ON pro.id=l_pecas_store.product_id";
$rs_store = DB::getInstance()->prepare($query_store);
$rs_store->execute();
$row_rs_store = $rs_store->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_store = $rs_store->rowCount();



if($_POST['op'] == 'carregaBanners') {
	$id = $_POST['id'];
	$data = date('Y-m-d H:i:s');

	if($id > 0) {
		$folder = "categorias";

		$query_rsBanners = "SELECT * FROM l_categorias".$extensao." WHERE id=:id";
		$rsBanners = DB::getInstance()->prepare($query_rsBanners);
		$rsBanners->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsBanners->execute();
		$row_rsBanners = $rsBanners->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsBanners = $rsBanners->rowCount();
		if($totalRows_rsBanners == 0) {
			$query_rsBanners = "SELECT * FROM l_categorias".$extensao." WHERE id=:id";
			$rsBanners = DB::getInstance()->prepare($query_rsBanners);
			$rsBanners->bindParam(':id', $row_rsBanners['cat_mae'], PDO::PARAM_INT, 5);	
			$rsBanners->execute();
			$row_rsBanners = $rsBanners->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsBanners = $rsBanners->rowCount();
		}

		$titulo = $row_rsBanners['title'];
		$descricao = $row_rsBanners['descricao'];
	}
	// else if($id == "-1" || $id == "-2") { //NOVIDADES || PROMOCOES
	// 	$id = intval(abs($id));
	// 	$folder = "banners_loja";

	// 	$query_rsBanners = "SELECT * FROM banners_l".$extensao." WHERE id=:id";
	// 	$rsBanners = DB::getInstance()->prepare($query_rsBanners);
	// 	$rsBanners->bindParam(':id', $id, PDO::PARAM_INT, 5);	
	// 	$rsBanners->execute();
	// 	$row_rsBanners = $rsBanners->fetch(PDO::FETCH_ASSOC);
	// 	$totalRows_rsBanners = $rsBanners->rowCount();

	// 	$titulo = $row_rsBanners['titulo'];
	// 	$descricao = $row_rsBanners['texto'];
	// }

	//if($row_rsBanners['imagem1'] && file_exists(ROOTPATH.'imgs/'.$folder.'/'.$row_rsBanners['imagem1'])) { 
		if($row_rsBanners['cor']) $color = $row_rsBanners['cor1'];

		$mask = "";
		if($row_rsBanners['mascara'] == 1) {
			$mask .= " has_mask"; 
		}

		$img_banners = "";
		if($row_rsBanners['imagem2'] && file_exists(ROOTPATH.'imgs/'.$folder.'/'.$row_rsBanners['imagem2'])) {
			$img_banners = ROOTPATH_HTTP."imgs/".$folder."/".$row_rsBanners['imagem2'];
		}
		else if($row_rsBanners['imagem1'] && file_exists(ROOTPATH.'imgs/'.$folder.'/'.$row_rsBanners['imagem1'])) {
			$img_banners = ROOTPATH_HTTP."imgs/".$folder."/".$row_rsBanners['imagem1'];
		}
		$mostra_titulo = $row_rsBanners["mostra_titulo"];
		?>
		<?php if (!empty($titulo)): ?>
			<div class="div_100 banners">
				<div class="collapse">
					<div class="column text-center">
						<?php if ($row_rsBanners['tipo_fundo'] == 1 && !empty($img_banners)): ?>
							<div class="banners_slide has_bg<?php echo $mask; ?>" bg-srcset="<?php echo $img_banners; ?> 950w, <?php echo ROOTPATH_HTTP; ?>imgs/<?php echo $folder; ?>/<?php echo $row_rsBanners['imagem1']; ?>" style="position:relative; min-height: <?php echo $row_rsConfigImg['min_height1'].'px'; ?>">
								<?php echo getFill('categorias'); ?>
								<?php if($titulo != '' || $descricao != '') { ?>
									<div class="div_absolute">
										<?php if (!empty($mostra_titulo)): ?>
											<div class="div_100">
												<?php 
													$cor_titulo = $row_rsBanners["cor_titulo"]; 
												?>
												<?php if($titulo != '') { ?>
													<h3 class="titulos" style="<?php echo ($cor_titulo) ? 'color:'.$cor_titulo : NULL; ?>"><?php echo $titulo; ?></h3>
												<?php } ?>
												<?php if($descricao != '') { ?>
													<div class="textos" style="<?php echo ($cor_titulo) ? 'color:'.$cor_titulo : NULL ; ?>"><p><?php echo $descricao; ?></p></div>
												<?php } ?>
											</div>
										<?php endif ?>
									</div>
								<?php } ?>
							</div>
						<?php else: ?>
							<div class="banners_slide has_bg"  style="position:relative; background-color: <?php echo $row_rsBanners['cor_fundo']; ?>">
								<?php echo getFill('categorias'); ?>
								<?php if($titulo != '' || $descricao != '') { ?>
									<div class="div_absolute">
										<?php //if (!empty($mostra_titulo)): ?>
											<div class="div_100">
												<?php 
													$cor_titulo = $row_rsBanners["cor_titulo"]; 
												?>
												<?php if($titulo != '') { ?>
													<h3 class="titulos" style="<?php echo ($cor_titulo) ? 'color:'.$cor_titulo : NULL; ?>"><?php echo $titulo; ?></h3>
												<?php } ?>
												<?php if($descricao != '') { ?>
													<div class="textos" style="<?php echo ($cor_titulo) ? 'color:'.$cor_titulo : NULL ; ?>"><p><?php echo $descricao; ?></p></div>
												<?php } ?>
											</div>
										<?php //endif ?>
									</div>
								<?php } ?>
							</div>
						<?php endif ?>

					</div>
				</div>
			</div>	
		<?php endif ?>
		
	<?php 
	//}

	DB::close();
}

if($_POST['op'] == 'carregaNavigation') {
	$id = $_POST['id'];

	if($id > 0) {
		$query_rsCategoria = "SELECT id, nome, cat_mae FROM l_categorias".$extensao." WHERE id=:id";
		$rsCategoria = DB::getInstance()->prepare($query_rsCategoria);
		$rsCategoria->bindParam(':id', $id, PDO::PARAM_INT, 5);	
		$rsCategoria->execute();
		$row_rsCategoria = $rsCategoria->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsCategoria = $rsCategoria->rowCount();

		$query_rsTotal = "SELECT id, url FROM l_categorias".$extensao." WHERE visivel = '1' AND cat_mae = '$row_rsCategoria[cat_mae]' GROUP BY id ORDER BY ordem ASC";
		$rsTotal = DB::getInstance()->prepare($query_rsTotal);
		$rsTotal->bindParam(':categoria', $categoria, PDO::PARAM_INT, 5); 
		$rsTotal->execute();
		$totalRows_rsTotal = $rsTotal->rowCount();

		$prod_ant = "";
		$prod_ant_img = "";
		$prod_ant_nome = "";
		$prod_seg = "";
		$prod_seg_img = "";
		$prod_seg_nome = "";
		$encontrado = 0;
		$conta_reg = 0;

		if($totalRows_rsTotal > 1) {
			while($row_rsTotal = $rsTotal->fetch()){
				$registo_actual++;
				
				if($encontrado == 1) {
					$prod_seg = $row_rsTotal['url'];
					$prod_seg_id = $row_rsTotal['id'];
					break;          
				}
				
				if($row_rsTotal['id'] != $id && $encontrado == 0) {
					$prod_ant = $row_rsTotal['url'];
					$prod_ant_id = $row_rsTotal['id'];
				} 
				else if($row_rsTotal['id'] == $id) {
					$encontrado = 1;            
				}
			}
		}
		else {
			$registo_actual++;
		}
		?>
		<nav class="div_100 listagem_nav show-for-medium">
			<ul class="row collapse full align-middle">
				<li class="column shrink text-left">
					<h3 class="list_subtit"><?php echo $row_rsCategoria['nome']; ?></h3>
				</li>
				<li class="column text-right">
					<?php if($prod_ant) { ?>
						<a class="list_subtit prev" href="<?php echo $prod_ant; ?>">
							<i class="icon-left"></i><!-- 
							--><span class="show-for-xsmall"><?php echo $Recursos->Resources["anterior"]; ?></span>
						</a><!-- 
					--><?php } ?><!-- 
					--><?php if($prod_seg) { ?><!-- 
						--><a class="list_subtit next" href="<?php echo $prod_seg; ?>">
							<span class="show-for-xsmall"><?php echo $Recursos->Resources["seguinte"]; ?></span><!-- 
							--><i class="icon-right"></i>    
						</a>
					<?php } ?>
				</li>
			</ul>
		</nav>
	<?php }

	DB::close();
}

if($_POST['op'] == 'carrega_meta') {
	$id = $_POST['id'];
	
	$query_rsCategoria = "SELECT title, description, keywords, nome, url FROM l_categorias".$extensao." WHERE id=:id";
	$rsCategoria = DB::getInstance()->prepare($query_rsCategoria);
	$rsCategoria->bindParam(':id', $id, PDO::PARAM_INT, 5);	
	$rsCategoria->execute();
	$row_rsCategoria = $rsCategoria->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsCategoria = $rsCategoria->rowCount();
	DB::close();
	
	echo $row_rsCategoria['title']."__".$row_rsCategoria['description']."__".$row_rsCategoria['keywords']."__".$row_rsCategoria['url'];		
}



if($_POST['product_search'] == 'product_search' && isset($_POST['product_name'])) {

	$search = $_POST['product_name'];
	$where = " AND pecas.nome LIKE '%$search%'";
	$query_rsProdutos = "SELECT * FROM l_pecas".$extensao." AS pecas WHERE pecas.visivel = '1' ".$where." ORDER BY pecas.nome";
	$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	$rsProdutos->execute();
	$row_rsProdutos = $rsProdutos->fetchAll();

	if (empty($search)) {
		$row_rsProdutos = array();
	}

	if (!empty($row_rsProdutos)) {
		echo '<ul>';
			$i=1;
			foreach ($row_rsProdutos as $key => $value) { ?>
				<?php if ($i <= 5 ): ?>
					<li>
						<a href="<?php echo $value['url']; ?>" title="">
							<div class="product_image">
								<?php $imagem = ROOTPATH_HTTP."imgs/produtos/".$value['imagem1']; ?>
								<img src="<?php echo $imagem; ?>" alt="">
							</div>
							<p><?php echo $value['nome']; ?></p>
						</a>
					</li>
				<?php
					$i++; 
				endif; ?>
			<?php }
				if (count($row_rsProdutos) > 5): ?>
					<li class="all-pro"><a href="loja" title="">View all products</a></li>
				<?php endif;
		echo '</ul>';
	} else {
		echo '<ul>';
			echo '<li>No Product</li>';
		echo '</ul>';
	}

	
	

}



if($_POST['op'] == 'carrega_bread') {
	$id = $_POST['id'];
	if($id > 0) {
		$categoria = $id;
		$catmae = $_POST['catmae'];
		$submae = $_POST['submae'];

		$row_rsCategoria = $GLOBALS['divs_categorias'][$categoria]['info'];

		if($categoria == $submae) {
			$submae = 0;
		}
	
		if($catmae > 0 && $submae == 0) {
			$row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['info'];
			$row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$categoria]['info'];
		}
		else if($catmae > 0 && $submae > 0) {    
			$row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['info'];
			$row_rsSubMae = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['info'];
			$row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria];
		}
	}
	?>
	<li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
	<?php if(!empty($row_rsCategoria)) { ?>
		
		<?php if(!empty($row_rsCatMae)) { ?>
			<li><a href="<?php echo $row_rsCatMae["url"]; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/produtos.php" data-ajaxTax="<?php echo $row_rsCatMae['id']; ?>" data-remote="false"><?php echo $row_rsCatMae["nome"]; ?></a></li>
		<?php } ?>
		<?php if(!empty($row_rsSubMae)) { ?>
			<li><a href="<?php echo $row_rsSubMae["url"]; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/produtos.php" data-ajaxTax="<?php echo $row_rsSubMae['id']; ?>" data-remote="false"><?php echo $row_rsSubMae["nome"]; ?></a></li>
		<?php } ?>

		<li> <span><?php echo $row_rsCategoria["nome"]; ?></span></li>
	<?php } else if($id == "-1" || $id == "-2") { ?>
		<li><a href="loja"><?php echo $Recursos->Resources['produtos']; ?></a></li>
		<li> 
			<?php if($id == "-1") { ?>
				<span><?php echo $Recursos->Resources['novidades']; ?></span>
			<?php } else if($id == "-2") { ?><?php  ?>
				<span><?php echo $Recursos->Resources['promocoes']; ?></span>
			<?php } ?>
		</li>
	<?php } else { ?>
		<li> 
			<span><?php echo $Recursos->Resources['produtos']; ?></span>
		</li>
	<?php }
}


if($_POST['op'] == "filtros") {
	$id = $_POST['id'];
	$join1 = "";
	$where = "";

	$url_final = $_POST['url'];
	$orders = 1;
	$filt_por = 1;
	$counters_active = 1;
	$accordion = $_POST['accordions'];

	$pesq = "";
	$filtros = array();
	$opcoes = array();

	$qtd = 0;
	$data = $_COOKIE['data'];
	
	if($url_final) {
		$url_param = explode("?", $url_final);
		$url = $url_param[0];
		$parametro = explode("&", $url_param[1]);
		foreach($parametro as $params) {
			$params = explode("=", $params);
			$nome = $params[0];
			$value = $params[1];

			if($nome == "pesq") {
				$pesq = $value;
			}
			else if($nome == "ordem") {
				$ordem = $value;
			}
			else if($nome == "promocoes" && $value == 1) {
				$ordem = 5;
			}
			else if($nome == "novidades" && $value == 1) {
				$ordem = 2;
			}
			else if($nome == "marcas") {
				$marca = $value;

				if($value != "") {
					$marca_array = explode(",", $value);
				}
			}
			else if($nome == "categoria") {
				$categoria = $value;

				if($value != "") {
					$categoria_array = explode(",", $value);
				}
			}
			else if($nome == "opcoes") {
				if($value != "") {
					$opcoes = explode(",", $value);
				}
			}
			else if($nome == "filtros") {
				if($value != "") {
					$filtros = explode(",", $value);
				}
			}
			else if($nome == "filtrar_por") {
				$filtrar_por = $value;
			}
		}
	}


	$join1 = "";
	$where_opcoes = " AND pecas.id = pecas_tam.peca";
	$where_filt = " AND pecas.id = pecas_filt.id_peca";
	
	if($id > 0) {

		$categoria = $id;
		$catmae = $_POST['catmae'];
		$submae = $_POST['submae'];

		$row_rsCatMae = $GLOBALS['divs_categorias'][$categoria]['info'];
		$row_rsCategoria = $GLOBALS['divs_categorias'][$categoria]['info'];
		$row_rsSubCats = $GLOBALS['divs_categorias'][$categoria]['subs'];
		
		if($catmae > 0 && $submae == 0) {
			$row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['info'];
			$row_rsSubCats = $GLOBALS['divs_categorias'][$catmae]['subs'];

			if($categoria != $catmae) { 
				$row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$categoria];

				if($row_rsCategoria['info']['nome']) {
					$row_rsCategoria = $row_rsCategoria['info'];
				}

				$row_rsSubCats = $GLOBALS['divs_categorias'][$catmae]['subs'][$categoria]['subs'];
			}
			
		}
		else if($catmae > 0 && $submae > 0) {
			$row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria];
			$row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria];
			$row_rsSubCats = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria]['subs'];
		}

		$categoria_list = "";
		
		if(!empty($row_rsCategoria)) {
			if(!empty($row_rsSubCats)) {
				foreach($row_rsSubCats as $sub_cats) {
					if($sub_cats['info']['nome']) {
						$sub_cats = $sub_cats['info'];
					}
	
					$categoria_list .= $sub_cats['id'].",";
					
					$row_rsSubCats2 = $row_rsSubCats[$sub_cats['id']]['subs'];
					if(!empty($row_rsSubCats2)) {
						foreach($row_rsSubCats2 as $sub_cats2) {
							$categoria_list .= $sub_cats2['id'].",";
						}
					}
				}
				
				if($categoria_list) {
					$categoria_list = substr($categoria_list, 0, -1);
				}
	
				if(CATEGORIAS == 2) {
					$join1 .= " LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca";
					$where .= " AND cats_pecas.id_categoria IN ($categoria_list)";
				}
				else {
					$where .= " AND pecas.categoria IN ($categoria_list)";
				}
			}
			else {
				if(CATEGORIAS == 2) {
					$join1 .= " LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca";
					$where .= " AND cats_pecas.id_categoria = :categoria";
				}
				else {
					$where .= " AND pecas.categoria = :categoria";
				}
			}
		}
	}
	else if($id == "-1" || $id == "-2") { //NOVIDADES || PROMOCOES
		$filtrar_por = intval(abs($id));

		if($filtrar_por == 1) {
			$where .= " AND pecas.novidade = 1";
		}
		else if($filtrar_por == 2) {
			$data_hoje = date('Y-m-d');

			if(CATEGORIAS == 1) {
				$join1 .=" LEFT JOIN l_categorias".$extensao." AS cats ON pecas.categoria = cats.id LEFT JOIN l_categorias".$extensao." AS cats2 ON cats.cat_mae = cats2.id LEFT JOIN l_promocoes".$extensao." promo ON ((promo.id_peca = 0 OR promo.id_peca = pecas.id) AND (promo.id_marca = 0 OR promo.id_marca = pecas.marca) AND (promo.id_categoria = 0 OR promo.id_categoria = pecas.categoria OR promo.id_categoria = cats.cat_mae OR promo.id_categoria = cats2.cat_mae)), l_promocoes_textos".$extensao." geral ";

				$where .= " AND ((pecas.promocao = 1 AND pecas.promocao_desconto > 0 AND (pecas.promocao_datai <= '$data_hoje' OR ((pecas.promocao_datai IS NULL OR pecas.promocao_datai = '') AND geral.datai <= '$data_hoje')) AND (pecas.promocao_dataf >= '$data_hoje' OR ((pecas.promocao_dataf IS NULL OR pecas.promocao_dataf = '') AND geral.dataf >= '$data_hoje'))) OR (promo.visivel = 1 AND promo.datai <= '$data_hoje' AND promo.dataf >= '$data_hoje'))";
			}
			else if(CATEGORIAS == 2) {
				$join1 .= " LEFT JOIN l_categorias".$extensao." AS cats ON pecas.categoria = cats.id LEFT JOIN l_categorias".$extensao." AS cats2 ON cats.cat_mae = cats2.id LEFT JOIN l_promocoes".$extensao." promo ON ((promo.id_peca = 0 OR promo.id_peca = pecas.id) AND (promo.id_marca = 0 OR promo.id_marca = pecas.marca) AND (promo.id_categoria = 0 OR promo.id_categoria = pecas.categoria OR promo.id_categoria = cats.cat_mae OR promo.id_categoria = cats2.cat_mae)), l_promocoes_textos".$extensao." geral ";
		
				$where .= " AND ((((pecas.promocao = 1 AND pecas.promocao_desconto > 0) OR (pecas.preco_ant > pecas.preco)) AND (pecas.promocao_datai <= ".$data_hoje." OR ((pecas.promocao_datai IS NULL OR pecas.promocao_datai = '') AND geral.datai <= ".$data_hoje.")) AND (pecas.promocao_dataf >= ".$data_hoje." OR ((pecas.promocao_dataf IS NULL OR pecas.promocao_dataf = '') AND geral.dataf >= ".$data_hoje."))) OR (promo.visivel = 1 AND promo.datai <= ".$data_hoje." AND promo.dataf >= ".$data_hoje."))";
			}
		}

		//$filt_por = 0;
	}
	
	$opcoes_categorias = array();
	$opcoes_opcoes = array();
	foreach($row_rsOpcoes as $opcoes_list) {
		$opcoes_categorias[$opcoes_list["id"]] = array("nome"=>$opcoes_list["nome"], "tipo"=>$opcoes_list["tipo"]);
		$opcoes_opcoes[$opcoes_list["opcoes_id"]] = array("cat"=>$opcoes_list["id"], "nome"=>$opcoes_list["opcoes_nome"], "cor"=>$opcoes_list["cor"], "imagem1"=>$opcoes_list["imagem1"]);
	}

	/* Marcas */
	if(tableExists(DB::getInstance(), 'l_marcas_pt')) {
		$query_rsMarcas = "SELECT marcas.id, marcas.nome FROM l_marcas".$extensao." AS marcas, l_pecas".$extensao." AS pecas ".$join1." WHERE marcas.id = pecas.marca AND pecas.visivel=1 AND marcas.visivel=1 ".$where." GROUP BY marcas.id ORDER BY marcas.ordem ASC, marcas.nome ASC";
		$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
		if(hasParameter($query_rsMarcas, ':categoria')) $rsMarcas->bindParam(':categoria', $categoria, PDO::PARAM_INT);
		$rsMarcas->execute();
		$row_rsMarcas = $rsMarcas->fetchAll();
		$totalRows_rsMarcas = $rsMarcas->rowCount();
	}

	/* Filtros */
	if(tableExists(DB::getInstance(), 'l_filt_categorias_en') && tableExists(DB::getInstance(), 'l_filt_opcoes_pt')) {
		$query_rsFiltros = "SELECT cat.id, cat.nome, cat.tipo, sub.id AS sub_id, sub.nome AS sub_nome FROM l_filt_categorias".$extensao." AS cat, l_filt_opcoes".$extensao." AS sub, l_pecas_filtros AS pecas_filt, l_pecas".$extensao." AS pecas ".$join1." WHERE pecas_filt.id_filtro = sub.id AND pecas.visivel=1 AND cat.id = sub.categoria AND cat.id > 0 AND sub.id > 0".$where.$where_filt." GROUP BY cat.id, sub.id ORDER BY cat.ordem ASC, cat.id ASC";
		$rsFiltros = DB::getInstance()->prepare($query_rsFiltros);
		if(hasParameter($query_rsFiltros, ':categoria')) $rsFiltros->bindParam(':categoria', $categoria, PDO::PARAM_INT);		
		$rsFiltros->execute();
		$row_rsFiltros = $rsFiltros->fetchAll();
		$totalRows_rsFiltros = $rsFiltros->rowCount();

		$filt_categorias = array();
		$filt_opcoes = array();
		foreach($row_rsFiltros as $filt) {
			if(!$filt_categorias[$filt["id"]]) {
				$filt_categorias[$filt["id"]] = array("nome"=>$filt["nome"], "tipo"=>$filt["tipo"], "imagem1"=>$filt["imagem1"]);
			}			

			${'counter_'.$filt["id"]}++;
			$filt_opcoes[$filt["sub_id"]] = array("cat"=>$filt["id"], "nome"=>$filt["sub_nome"]);
		}
	}
	
	if(!empty($row_rsSubCats)) { ?>
    	<div class="filters_divs active input_check">
      		<h3 class="list_subtit accordion-head"><?php echo $Recursos->Resources["categorias"]; ?></h3>
			<div class="cat-wrap accordion-content active" native-window data-title="<?php echo $filt_cat["nome"]; ?>"> 
				<div class="div_100">
				<h2 class="sub-cate-head hh" style="margin-bottom:10px;"><?php echo $row_rsCategoria['nome']; ?></h2>
	        <?php $counter =- 1; 
	        foreach($row_rsSubCats as $sbcat) { 
	        	$counter++;
	     //    	echo "<pre>";
						// print_r($sbcat['info']['id']);
						// echo "</pre>";
	        	if (array_key_exists("info",$sbcat)) {
	        			$query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas".$join1." WHERE pecas.categoria='".$sbcat['info']['id']."' AND pecas.visivel=1 ".$where." GROUP BY pecas.id";

	        			if(CATEGORIAS == 2) {
	                      	$query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca WHERE cats_pecas.id_categoria='".$sbcat['info']['id']."' AND pecas.visivel=1 GROUP BY pecas.id";
	                     }


						$rsCounterpr = DB::getInstance()->prepare($query_rsPr);
						// if(hasParameter($query_rsPr, ':categoria')) $rsCounterpr->bindParam(':categoria', $categoria, PDO::PARAM_INT);
						$rsCounterpr->execute();
						$row_rsCounterpr = $rsCounterpr->fetch(PDO::FETCH_ASSOC);
						$totalRows_rsCounterpr = $rsCounterpr->rowCount();



			        ?>

			        <?php if($accordion > 0 && $counter == $accordion) { ?>
		            <div class="hidden_filters">
			        <?php } ?>
			        <div class="catewrap">
			        <a class="filters sub-filters" href="javascript:;">
		            <input class="loja_inpt" type="checkbox" data-replace="2" data-name="categoria" name="categoria" id="categoria<?php echo $sbcat['info']['id']; ?>" value="<?php echo $sbcat['info']['id']; ?>" <?php if(in_array($sbcat['info']['id'], $categoria_array)) echo "checked"; ?> />
		            <h5 class="list_txt"><?php echo $sbcat['info']["nome"]; ?></h5><?php if($counters_active) { ?><p class="list_txt main-cate-count"><?php echo "(".$totalRows_rsCounterpr.")"; ?></p><?php } ?>
			        </a>
			        <?php 
                    $subs_iner = $sbcat['subs'];
                    if (!empty($subs_iner)) { ?>
                      <div class="inner_cate">
                      <h2 style="margin-bottom:10px;"><?php echo $sbcat['info']['nome']; ?></h2>
                      <?php foreach ($subs_iner as $key => $cat): ?>
                        <?php 
                          $query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas WHERE pecas.categoria='".$cat['id']."' AND pecas.visivel=1 GROUP BY pecas.id";

                          if(CATEGORIAS == 2) {
                          if($_SESSION["store_id"] != "")	
							{
								$query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas
									INNER JOIN l_pecas_store ON pecas.id = l_pecas_store.product_id
								 	LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca WHERE cats_pecas.id_categoria='".$cat['id']."' AND l_pecas_store.b_name_pro = ".$_SESSION["store_id"]." AND pecas.visivel=1 GROUP BY pecas.id";
							}	

							if($_SESSION["store_update_id"] != "")
							{

								$query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas
									INNER JOIN l_pecas_store ON pecas.id = l_pecas_store.product_id
								 	LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca WHERE cats_pecas.id_categoria='".$cat['id']."' AND l_pecas_store.b_name_pro = ".$_SESSION["store_update_id"]." AND pecas.visivel=1 GROUP BY pecas.id";
							}
                          }

                          $rsCounterpr = DB::getInstance()->prepare($query_rsPr);
                          //if(hasParameter($query_rsPr, ':categoria')) $rsCounterpr->bindParam(':categoria', $categoria, PDO::PARAM_INT);
                          $rsCounterpr->execute();
                          $row_rsCounterpr = $rsCounterpr->fetch(PDO::FETCH_ASSOC);
                          $totalRows_rsCounterpr = $rsCounterpr->rowCount();


                         ?>
                        <a class="filters sub-sub-filters" href="javascript:;">
                            <input class="loja_inpt dd" type="checkbox" data-name="categoria" name="categoria" id="categoria<?php echo $cat['id']; ?>" value="<?php echo $cat['id']; ?>" <?php if(in_array($cat['id'], $categoria_array)) echo "checked"; ?>>
                          <h5 class="list_txt"><?php echo $cat["nome"]; ?></h5><p single-count="<?php echo $totalRows_rsCounterpr; ?>" class="list_txt"><?php echo "(".$totalRows_rsCounterpr.")"; ?></p>
                        </a>
                      <?php endforeach ?>
                      </div>
                    <?php } ?>
                    </div>
	        	<?php } else {

	        		$query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas".$join1." WHERE pecas.categoria='".$sbcat['id']."' AND pecas.visivel=1 ".$where." GROUP BY pecas.id";
						$rsCounterpr = DB::getInstance()->prepare($query_rsPr);
						//if(hasParameter($query_rsPr, ':categoria')) $rsCounterpr->bindParam(':categoria', $categoria, PDO::PARAM_INT);
						
						if(CATEGORIAS == 2) {
							$cate_iddd = $sbcat['id'];
                      	$query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas LEFT JOIN l_pecas_categorias AS cats_pecas ON cats_pecas.id_peca = pecas.id WHERE cats_pecas.id_categoria=$cate_iddd AND pecas.visivel=1 GROUP BY pecas.id";

                      	// $query_rsPr = "SELECT * FROM l_pecas_categorias  WHERE id_categoria=$cate_iddd";

                      	// echo "<pre>";
                      	// print_r ($query_rsPr);
                      	// echo "</pre>";
                      }
                      	$rsCounterpr = DB::getInstance()->prepare($query_rsPr);
						$rsCounterpr->execute();
						$row_rsCounterpr = $rsCounterpr->fetchAll(PDO::FETCH_ASSOC);
						// echo "<pre>";
						// print_r ($row_rsCounterpr);
						// echo "</pre>";
						$totalRows_rsCounterpr = $rsCounterpr->rowCount();

			        ?>
			        <?php if($accordion > 0 && $counter == $accordion) { ?>
		            <div class="hidden_filters">
			        <?php } ?>
			        <div class="inner_cate">
			        <a class="filters" href="javascript:;">
		            <input class="loja_inpt" type="checkbox" data-replace="2" data-name="categoria" name="categoria" id="categoria<?php echo $sbcat['id']; ?>" value="<?php echo $sbcat['id']; ?>" <?php if(in_array($sbcat['id'], $categoria_array)) echo "checked"; ?> />
		            <h5 class="list_txt"><?php echo $sbcat["nome"]; ?></h5><?php if($counters_active) { ?><p class="list_txt"><?php echo "(".$totalRows_rsCounterpr.")"; ?></p><?php } ?>
			        </a>
			        </div>
	        	<?php }
	        	
        	} ?>
	        <?php if($accordion > 0 && $counter >= $accordion && ($counter + 1) == count($row_rsMarcas)) { ?>
            </div>
	        <?php } ?>
	        <div class="hidden_filters_btn textos"><?php echo $Recursos->Resources["ver_todos"]; ?></div>
	      </div>
      </div>
  	</div>
  	</div>
	<?php }
	
	if(!empty($row_rsMarcas)) { ?>
	<div class="div_100" id="marcas_div">
    	<div class="filters_divs active input_check">
      		<h3 class="list_subtit active accordion-head" ><?php echo $Recursos->Resources["marcas"]; ?>Marcas</h3>
			<div class="cat-wrap accordion-content active" native-window data-title="<?php echo $filt_cat["nome"]; ?>"> 
				<div class="div_100">
		        <?php $counter =- 1; 
		        foreach($row_rsMarcas as $marcas) { 
		        	$counter++;

		        	$query_rsCounter = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas".$join1." WHERE pecas.marca='".$marcas['id']."' AND pecas.visivel=1 ".$where." GROUP BY pecas.id";
							$rsCounter = DB::getInstance()->prepare($query_rsCounter);
							if(hasParameter($query_rsCounter, ':categoria')) $rsCounter->bindParam(':categoria', $categoria, PDO::PARAM_INT);
							$rsCounter->execute();
							$row_rsCounter = $rsCounter->fetch(PDO::FETCH_ASSOC);
							$totalRows_rsCounter = $rsCounter->rowCount();

			        ?>
			        <?php if($accordion > 0 && $counter == $accordion) { ?>
		            <div class="hidden_filters">
			        	<?php } ?>
			        	<a class="filters" href="javascript:;">
		            		<input class="loja_inpt" type="checkbox" data-replace="2" data-name="marcas" name="marcas" id="marcas_<?php echo $marcas["id"]; ?>" value="<?php echo $marcas["id"]; ?>" <?php if(in_array($marcas['id'], $marca_array)) echo "checked"; ?> />
		            		<h5 class="list_txt"><?php echo $marcas["nome"]; ?></h5><?php if($counters_active) { ?><p class="list_txt"><?php echo "(".$totalRows_rsCounter.")"; ?></p><?php } ?>
			       		 </a>
	        			<?php } ?>
		        		<?php if($accordion > 0 && $counter >= $accordion && ($counter + 1) == count($row_rsMarcas)) { ?>
	            	</div>
		        <?php } ?>
	        	<div class="hidden_filters_btn textos"><?php echo $Recursos->Resources["ver_todos"]; ?></div>
	      		</div>
      		</div>
  		</div>
  	</div>
	<?php } ?>

	<?php if((!empty($filt_categorias) && !empty($filt_opcoes))) { ?>
  	<?php foreach($filt_categorias as $cat_id => $filt_cat) {
  		$type = "";
  		if($filt_cat['tipo'] == 2) {
  			$type = "data-replace=\"1\"";
  		}
    	?>
      	<div class="filters_divs input_check" id="filt_div">
        	<h3 class="list_subtit"><?php echo $filt_cat["nome"]; ?></h3>
        		<div class="cat-wrap filters_divs_pad" native-window data-title="<?php echo $filt_cat["nome"]; ?>"> 
					<div class="div_100">
						<?php $counter =- 1; 
						foreach($filt_opcoes as $sub_id=>$filt_opc) { 
							if($filt_opc["cat"] == $cat_id) { 
								$counter++;

		          	$query_rsCounter = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas".$join1.", l_pecas_filtros AS pecas_filt WHERE pecas_filt.id_filtro = '".$sub_id."' AND pecas.visivel=1".$where.$where_filt." GROUP BY pecas.id";
								$rsCounter = DB::getInstance()->prepare($query_rsCounter);
								if(hasParameter($query_rsCounter, ':categoria')) $rsCounter->bindParam(':categoria', $categoria, PDO::PARAM_INT);
								$rsCounter->execute();
								$row_rsCounter = $rsCounter->fetch(PDO::FETCH_ASSOC);
								$totalRows_rsCounter = $rsCounter->rowCount();
								DB::close();

								$total = ${'counter_'.$cat_id};
								?>
								<?php if($accordion > 0 && $counter == $accordion) { ?>
		              <div class="hidden_filters">
			          <?php } ?>
			          <a class="filters" href="javascript:;">
		              <input class="loja_inpt" type="checkbox" <?php echo $type; ?> data-name="filtros" name="filtros_<?php echo $filt_opc["cat"]; ?>" id="filtros_<?php echo $filt_opc["cat"]; ?>_<?php echo $sub_id; ?>" value="<?php echo $sub_id; ?>" <?php if(in_array($sub_id, $filtros)) echo "checked"; ?> />
		              <h5 class="list_txt"><?php echo $filt_opc["nome"]; ?></h5><?php if($counters_active) { ?><p class="list_txt"><?php echo "(".$totalRows_rsCounter.")"; ?></p><?php } ?>
			          </a>
	        		<?php } 
	        	} ?>
	          <?php if($accordion > 0 && $counter >= $accordion && ($counter + 1) == $total) { ?>
              </div>
	          <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
	<?php } ?>
    
  <?php if((!empty($opcoes_categorias) && !empty($opcoes_opcoes))) { ?>
  	<?php foreach($opcoes_categorias as $cat_id => $filt_cat) { ?>
      <div class="filters_divs input_check">
        <h3 class="list_subtit"><?php echo $filt_cat["nome"]; ?></h3>
				<div class="cat-wrap filters_divs_pad" native-window data-title="<?php echo $filt_cat["nome"]; ?>"> 
					<div class="div_100">
            <?php $counter =- 1; 
            foreach($opcoes_opcoes as $sub_id => $filt_opc) { 
            	if($filt_opc["cat"] == $cat_id) { 
            		$counter++;
								$class = "";
								$style = "";
							
								if($filt_cat['tipo'] == 1) {
									$class = " tamanhos";
									$nome = "&nbsp;";
									
									if($filt_opc['cor']) {
										$style = 'background:'.$filt_opc['cor'];
									}
									if($filt_opc['imagem1'] && file_exists(ROOTPATH.'imgs/uploads/'.$filt_opc['imagem1'])) {
										$style = "background:url('".ROOTPATH_HTTP."imgs/uploads/".$filt_opc['imagem1']."')";
										$class .= " has_bg";
									}
								}
								?>
								<?php if($accordion > 0 && $counter == $accordion) { ?>
	                <div class="hidden_filters">
		            <?php } ?>
		            <a class="filters<?php echo $class; ?>" href="javascript:;">
	                <input class="loja_inpt" type="checkbox" data-name="opcoes" name="opcoes<?php echo $filt_opc["cat"]; ?>" id="opcoes_<?php echo $filt_opc["cat"]; ?>_<?php echo $sub_id; ?>" value="<?php echo $sub_id; ?>" <?php if(in_array($sub_id, $opcoes)) echo "checked"; ?> />
	                <span style="<?php echo $style; ?>">&nbsp;</span>
	                <h5 class="list_txt"><?php echo $filt_opc["nome"]; ?></h5><?php if($counters_active) { ?><p class="list_txt"><?php echo "(".$filt_opc["total"].")"; ?></p><?php } ?>
		            </a>
	          	<?php } 
	         	} ?>
            <?php if($accordion > 0 && $counter >= $accordion && ($counter + 1) == count($opcoes_opcoes)) { ?>
              </div>
            <?php } ?>
            <div class="hidden_filters_btn textos"><?php echo $Recursos->Resources["ver_todos"]; ?></div>
          </div>
        </div>
      </div>
    <?php } ?>
	<?php } ?>

	<?php if($filt_por != "-1") { ?>
		<!-- <div class="filters_divs input_check">
			<h3 class="list_subtit"><?php echo $Recursos->Resources["filtrar_por"]; ?></h3>
			<div class="div_100">
				<div class="cat-wrap">
					<a class="filters" href="javascript:;">
		        		<input class="loja_inpt" type="checkbox" data-name="filtrar_por" data-replace="1" name="filtrar_por" id="filtrar_por_1" value="1" <?php if($filtrar_por == 1) echo "checked"; ?> />
		        		<h5 class="list_txt"><?php echo $Recursos->Resources["novidade"]; ?></h5>
			    	</a>
			    	<a class="filters" href="javascript:;">
		        		<input class="loja_inpt" type="checkbox" data-name="filtrar_por" data-replace="1" name="filtrar_por" id="filtrar_por_2" value="2" <?php if($filtrar_por == 2) echo "checked"; ?> />
		        		<h5 class="list_txt"><?php echo $Recursos->Resources["em_promocao"]; ?></h5>
			    	</a>
			    </div>
			</div>
	  </div> -->
  <?php } ?>

	<?php if($orders == 1) { ?>
		<div class="filters_divs input_check" style="display: none;">
			<h3  class="list_subtit"><?php echo $Recursos->Resources["ordenar"]; ?></h3>
			<div class="div_100">
				<div class="cat-wrap filter-by">
					<a class="filters" href="javascript:;">
		          		<input class="loja_inpt" type="checkbox" data-name="ordem" data-replace="1" name="ordem" id="ordem_1" value="1" <?php if($ordem == 1) echo "checked"; ?> />
		         		<h5 class="list_txt"><?php echo $Recursos->Resources["mais_recente"]; ?></h5>
			      	</a>
			      	<a class="filters" href="javascript:;">
		          		<input class="loja_inpt" type="checkbox" data-name="ordem" data-replace="1" name="ordem" id="ordem_2" value="2" <?php if($ordem == 2) echo "checked"; ?> />
		          		<h5 class="list_txt"><?php echo $Recursos->Resources["mais_antigo"]; ?></h5>
			     	 </a>
			     	<a class="filters" href="javascript:;">
		          		<input class="loja_inpt" type="checkbox" data-name="ordem" data-replace="1" name="ordem" id="ordem_3" value="3" <?php if($ordem == 3) echo "checked"; ?> />
		          		<h5 class="list_txt"><?php echo $Recursos->Resources["mais_barato"]; ?></h5>
			      	</a>
			      	<a class="filters" href="javascript:;">
		          		<input class="loja_inpt" type="checkbox" data-name="ordem" data-replace="1" name="ordem" id="ordem_4" value="4" <?php if($ordem == 4) echo "checked"; ?> />
		          		<h5 class="list_txt"><?php echo $Recursos->Resources["mais_caro"]; ?></h5>
	      			</a>
	      		</div>
	    	</div>
	  	</div>
  <?php } ?>
	<?php
	DB::close();
}

if($_POST['op'] == "elementos") {
	$where = "";
	$left_join = "";
	$id = $_POST['id'];
	$url_final = $_POST['url'];
	$url_param = explode("?", $url_final);
	$url = $url_param[0];
	$parametro = explode("&", $url_param[1]);
	
	if($id > 0) {
		$categoria = $id;
		$catmae = $_POST['catmae'];
		$submae = $_POST['submae'];
		
		if($catmae == $submae) {
			$submae = 0;
		}

		$row_rsCatMae = $GLOBALS['divs_categorias'][$categoria]['info'];
		$row_rsCategoria = $GLOBALS['divs_categorias'][$categoria]['info'];
		$row_rsSubCats = $GLOBALS['divs_categorias'][$categoria]['subs'];
		
		if($catmae > 0 && $submae == 0) {
			$row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['info'];
			$row_rsSubCats = $GLOBALS['divs_categorias'][$catmae]['subs'];

			if($categoria != $catmae) { 
				$row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$categoria];

				if($row_rsCategoria['info']['nome']) {
					$row_rsCategoria = $row_rsCategoria['info'];
				}

				$row_rsSubCats = $GLOBALS['divs_categorias'][$catmae]['subs'][$categoria]['subs'];
			}
			
		}
		else if($catmae > 0 && $submae > 0) {
			$row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria];
			$row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria];
			$row_rsSubCats = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria]['subs'];
		}

		$categoria_list = "";
		
		if(!empty($row_rsCategoria)) {
			if(!empty($row_rsSubCats)) {
				foreach($row_rsSubCats as $sub_cats) {
					if($sub_cats['info']['nome']) {
						$sub_cats = $sub_cats['info'];
					}

					$categoria_list .= $sub_cats['id'].",";
					
					$row_rsSubCats2 = $row_rsSubCats[$sub_cats['id']]['subs'];
					if(!empty($row_rsSubCats2)) {
						foreach($row_rsSubCats2 as $sub_cats2) {
							$categoria_list .= $sub_cats2['id'].",";
						}
					}
				}
				
				if($categoria_list) {
					$categoria_list = substr($categoria_list, 0, -1);
				}
			
				if(CATEGORIAS == 2) {
					$left_join .= " LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca INNER JOIN l_categorias".$extensao." as cat ON cat.id = cats_pecas.id_categoria";
					$where .= "AND cats_pecas.id_peca = pecas.id ";
				}
				else {
					$where .= " AND pecas.categoria IN ($categoria_list)";
				}	
			}
			else {
				if(CATEGORIAS == 2) {

					$left_join .= " LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca";
					$where .= " AND cats_pecas.id_categoria = :categoria";
				}
				else {
					$where = " AND pecas.categoria = :categoria";
				}
			}
		}
		else {
			$left_join .= " LEFT JOIN l_categorias".$extensao." AS cat ON pecas.categoria = cat.id";
		}
	}else if(($id == "-1" || $id == "-2") || $parametro[0] == "") { //NOVIDADES || PROMOCOES || NAO TEM NENHUM FILTRO ATIVO!
		$filtrar_por = intval(abs($id));
		
			if($id == "-1") {
				$where .= " AND pecas.novidade = 1";
			}
			else if($id == "-2") {
				$data_hoje = date('Y-m-d');

				if(CATEGORIAS == 1) {

					$left_join .= " LEFT JOIN l_categorias".$extensao." AS cats ON pecas.categoria = cats.id LEFT JOIN l_categorias".$extensao." AS cats2 ON cats.cat_mae = cats2.id LEFT JOIN l_promocoes".$extensao." promo ON ((promo.id_peca = 0 OR promo.id_peca = pecas.id) AND (promo.id_marca = 0 OR promo.id_marca = pecas.marca) AND (promo.id_categoria = 0 OR promo.id_categoria = pecas.categoria OR promo.id_categoria = cats.cat_mae OR promo.id_categoria = cats2.cat_mae)), l_promocoes_textos".$extensao." geral";
				}
				else if(CATEGORIAS == 2) {

					$left_join .= " LEFT JOIN l_pecas_categorias AS pecas_cats ON pecas_cats.id_peca = pecas.id LEFT JOIN l_categorias".$extensao." AS cats ON pecas_cats.id_categoria = cats.id LEFT JOIN l_categorias".$extensao." AS cats2 ON cats.cat_mae = cats2.id LEFT JOIN l_promocoes".$extensao." promo ON ((promo.id_peca = 0 OR promo.id_peca = pecas.id) AND (promo.id_marca = 0 OR promo.id_marca = pecas.marca) AND (promo.id_categoria = 0 OR promo.id_categoria = pecas_cats.id_categoria OR promo.id_categoria = cats.cat_mae OR promo.id_categoria = cats2.cat_mae)), l_promocoes_textos".$extensao." geral";
				}

				$where .= " AND ((((pecas.promocao = 1 AND pecas.promocao_desconto > 0) OR (pecas.preco_ant > pecas.preco)) AND (pecas.promocao_datai <= '$data_hoje' OR ((pecas.promocao_datai IS NULL OR pecas.promocao_datai = '') AND geral.datai <= '$data_hoje')) AND (pecas.promocao_dataf >= '$data_hoje' OR ((pecas.promocao_dataf IS NULL OR pecas.promocao_dataf = '') AND geral.dataf >= '$data_hoje'))) OR (promo.visivel = 1 AND promo.datai <= '$data_hoje' AND promo.dataf >= '$data_hoje'))";
			}
	}
	else{
		$left_join .= " LEFT JOIN l_pecas_categorias AS cats_pecas ON pecas.id = cats_pecas.id_peca";
	}

	$filtros_array = array();
	$filtrosQuery = "";

	$order_by = " ORDER BY pecas.ordem ASC, pecas.id DESC";
	$left_join_filter = "";

	foreach($parametro as $params) {
		$params = explode("=", $params);
		$nome = $params[0];
		$value = $params[1];

		if($nome == "promocoes"){
			$nome = "filtrar_por";
			$filtrar_por = 2;
		} else 	if($nome == "novidade"){
			$nome = "filtrar_por";
			$filtrar_por = 1;
		}
		if($nome == "ordem") {
			$ordem = $value;
			if(!empty($ordem) && $ordem != 0) {
				if($ordem == 1) {
					$order_by = " ORDER BY nome ASC";
				}
				if($ordem == 2) {
					$order_by = " ORDER BY nome DESC";
				}
				if($ordem == 3) {
					$order_by = " ORDER BY preco ASC";
				}
				if($ordem == 4) {
					$order_by = " ORDER BY preco DESC";
				}
			}
		}
		else if($nome == "pesq") {
			$nome_pesq_query = urldecode($value);
			$where .= " AND (pecas.nome LIKE :pesq OR pecas.ref LIKE :pesq OR pecas.descricao LIKE :pesq)";	
		}
		else if($nome == "marcas") {
			$marca = $value;

			if(strpos($marca, ",") !== false) {
				$filt_marca = "";
				$dados_marca = explode(",", $marca);

				foreach($dados_marca as $marca) {
					$query_rsExiste = "SELECT l_marcas".$extensao.".id FROM l_marcas".$extensao." WHERE l_marcas".$extensao.".id = :marca";
					$rsExiste = DB::getInstance()->prepare($query_rsExiste);
					$rsExiste->bindParam(':marca', $marca, PDO::PARAM_INT);
					$rsExiste->execute();
					$row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsExiste = $rsExiste->rowCount();
					
					if($totalRows_rsExiste > 0) {
						$filt_marca .= $row_rsExiste['id'].",";	
					}
				}	

				if($filt_marca) {
					$filt_marca = substr($filt_marca, 0, -1);
				}

				$where .= " AND pecas.marca IN (".$filt_marca.")";
			}
			else {
				$where .= " AND pecas.marca = :marca";
			}
		}
		else if($nome == "categoria") {
			$categoria = $value;
			if(strpos($categoria, ",") !== false || !empty($categoria)) {
				$filt_categoria = "";
				$dados_categoria = explode(",", $categoria);
				foreach($dados_categoria as $categoria) {

					$query_rsExiste = "SELECT l_categorias".$extensao.".id FROM l_categorias".$extensao." WHERE l_categorias".$extensao.".cat_mae = :categoria";
					$rsExiste = DB::getInstance()->prepare($query_rsExiste);
					$rsExiste->bindParam(':categoria', $categoria, PDO::PARAM_INT);
					$rsExiste->execute();
					$row_rsExiste = $rsExiste->fetchAll(PDO::FETCH_ASSOC);
					$totalRows_rsExiste = $rsExiste->rowCount();
					// echo "<pre>";
					// print_r ($row_rsExiste);
					// echo "</pre>";
					if ($_POST['catmae'] > 0 ) {
						
					}
					if ($_POST['catmae'] > 0 || $totalRows_rsExiste == 0 && $_POST['submae'] <= 0) {
						$filt_categoria .= $categoria.",";
					}else{
						$check_exists = false;
						if($totalRows_rsExiste > 0) {

							foreach ($row_rsExiste as $key => $value) {
								if (in_array($value['id'], $dados_categoria)) {
									$check_exists = true;
									$filt_categoria .= $value['id'].",";
								}else {
								}
							}

							if ($check_exists == false) {
								foreach ($row_rsExiste as $key => $value) {
									$filt_categoria .= $value['id'].",";
								}
							}else{
								$filt_categoria .= $categoria.",";
							}
						}
					}
					
				}	

				$filt_categoria .= $categoria.",";


				if($filt_categoria) {
					$filt_categoria = substr($filt_categoria, 0, -1);
				}

				if (CATEGORIAS == 1) {
					$where .= " AND pecas.categoria IN (".$filt_categoria.")";
				}else {
					$where .= " AND cats_pecas.id_categoria IN (".$filt_categoria.")";
				}
				
			}
			else {
				$where .= " AND pecas.categoria = :categoria";
			}

		}
		else if($nome == "filtrar_por") {
			$filtrar_por = $value;

			if($filtrar_por && $filtrar_por != 0) {
				if($filtrar_por == 1) {
					$where .= " AND pecas.novidade = 1";
				}
				else if($filtrar_por == 2) {
					$data_hoje = date('Y-m-d');

					if(CATEGORIAS == 1) {
						$left_join .= " LEFT JOIN l_categorias".$extensao." AS cats ON pecas.categoria = cats.id LEFT JOIN l_categorias".$extensao." AS cats2 ON cats.cat_mae = cats2.id LEFT JOIN l_promocoes".$extensao." promo ON ((promo.id_peca = 0 OR promo.id_peca = pecas.id) AND (promo.id_marca = 0 OR promo.id_marca = pecas.marca) AND (promo.id_categoria = 0 OR promo.id_categoria = pecas.categoria OR promo.id_categoria = cats.cat_mae OR promo.id_categoria = cats2.cat_mae)), l_promocoes_textos".$extensao." geral";
					}
					else if(CATEGORIAS == 2) {
						$left_join .= " LEFT JOIN l_pecas_categorias AS pecas_cats ON pecas_cats.id_peca = pecas.id LEFT JOIN l_categorias".$extensao." AS cats ON pecas_cats.id_categoria = cats.id LEFT JOIN l_categorias".$extensao." AS cats2 ON cats.cat_mae = cats2.id LEFT JOIN l_promocoes".$extensao." promo ON ((promo.id_peca = 0 OR promo.id_peca = pecas.id) AND (promo.id_marca = 0 OR promo.id_marca = pecas.marca) AND (promo.id_categoria = 0 OR promo.id_categoria = pecas_cats.id_categoria OR promo.id_categoria = cats.cat_mae OR promo.id_categoria = cats2.cat_mae)), l_promocoes_textos".$extensao." geral";
					}

					$where .= " AND ((((pecas.promocao = 1 AND pecas.promocao_desconto > 0) OR (pecas.preco_ant > pecas.preco)) AND (pecas.promocao_datai <= '$data_hoje' OR ((pecas.promocao_datai IS NULL OR pecas.promocao_datai = '') AND geral.datai <= '$data_hoje')) AND (pecas.promocao_dataf >= '$data_hoje' OR ((pecas.promocao_dataf IS NULL OR pecas.promocao_dataf = '') AND geral.dataf >= '$data_hoje'))) OR (promo.visivel = 1 AND promo.datai <= '$data_hoje' AND promo.dataf >= '$data_hoje'))";
				}
				else if($filtrar_por == 3) {
					$left_join .= ", l_pecas_tamanhos AS tam, l_caract_opcoes_en AS op";

					$where .= " AND tam.peca = pecas.id AND ((tam.car1 = 2 AND tam.op1 = op.id) OR (tam.car2 = 2 AND tam.op2 = op.id) OR (tam.car3 = 2 AND tam.op3 = op.id) OR (tam.car4 = 2 AND tam.op4 = op.id) OR (tam.car5 = 2 AND tam.op5 = op.id)) AND (op.nome LIKE '%+%' OR op.nome LIKE '%x%')";
				}
			}
		}
		else if($nome == "filtros") {
			if($value != "") {
				$filtro = $value;
				$dados_filtro = explode(",", $filtro);
				$cat_filt = 0;
				$filt_cat = "";
				$count = 0;
				$count2 = 0;

				foreach($dados_filtro as $filtro) {
					$id_filtro = $filtro;
					$count2++;
					
					$query_rsFiltros = "SELECT categoria FROM l_filt_opcoes".$extensao." WHERE id = '$id_filtro'";
					$rsFiltros = DB::getInstance()->prepare($query_rsFiltros);
					$rsFiltros->execute();
					$row_rsFiltros = $rsFiltros->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsFiltros = $rsFiltros->rowCount();
					
					if($count2 == 1) {
						$cat_filt = $row_rsFiltros['categoria'];
					}
					
					if($totalRows_rsFiltros > 0 && $cat_filt != $row_rsFiltros['categoria']) {
						$count++;
						$left_join_filter .= ", l_pecas_filtros AS peca_filtro".$count;
						$filt_cat = substr($filt_cat,0,-1);
						
						$where .= " AND peca_filtro".$count.".id_peca = pecas.id";
						$where .= " AND peca_filtro".$count.".id_filtro IN (".$filt_cat.")";
						
						$cat_filt = $row_rsFiltros['categoria'];
						$filt_cat = "";
						$filt_cat .= $id_filtro.",";
					}
					else {		
						$filt_cat .= $id_filtro.",";	
					}
					
				}	
				
				//Para o último filtro
				$query_rsFiltros = "SELECT categoria FROM l_filt_opcoes".$extensao." WHERE id = '$id_filtro'";
				$rsFiltros = DB::getInstance()->prepare($query_rsFiltros);
				$rsFiltros->execute();
				$row_rsFiltros = $rsFiltros->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsFiltros = $rsFiltros->rowCount();
				
				if($count2 == 1) {
					$cat_filt = $row_rsFiltros['categoria'];
				}
				
				if($totalRows_rsFiltros > 0) {
					$count++;
					$left_join_filter .= ", l_pecas_filtros AS peca_filtro".$count;
					$filt_cat = substr($filt_cat,0,-1);
					
					$where .= " AND peca_filtro".$count.".id_peca = pecas.id";
					$where .= " AND peca_filtro".$count.".id_filtro IN (".$filt_cat.")";
					
					$cat_filt = $row_rsFiltros['categoria'];
					$filt_cat = "";
					$filt_cat .= $id_filtro.",";
				}
			}
		}
	}

	// echo "<pre>";
	// print_r ($url_param[1]);
	// echo "</pre>";
	// exit();

	if ($url_param[1] == "mais-vendido") {
		$query_rsProdutos = "SELECT pecas.* FROM l_pecas".$extensao." AS pecas WHERE pecas.contagem_vendas > 0 AND pecas.visivel = '1' ORDER BY pecas.contagem_vendas DESC";
		$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	}else{
	/* Strat || New Code || Vishal Prajapti || Product Show */

	if($_SESSION["store_id"] != "")	
	{	
		$query_rsProdutos = "SELECT pecas.* FROM l_pecas".$extensao." AS pecas INNER JOIN l_pecas_store ON
			pecas.id = l_pecas_store.product_id ".$left_join.$left_join_filter."  WHERE l_pecas_store.b_name_pro = ".$_SESSION["store_id"]." AND pecas.visivel = '1' ".$where."  GROUP BY pecas.id".$order_by."";

	}
	if($_SESSION["store_update_id"] != "")
	{
		$query_rsProdutos = "SELECT pecas.* FROM l_pecas".$extensao." AS pecas INNER JOIN l_pecas_store ON
			pecas.id = l_pecas_store.product_id ".$left_join.$left_join_filter."  WHERE l_pecas_store.b_name_pro = ".$_SESSION["store_update_id"]." AND pecas.visivel = '1' ".$where."  GROUP BY pecas.id".$order_by."";
	}
	if($_SESSION["store_update_id"] == "" && $_SESSION["store_id"] == "")
	{
		$query_rsProdutos = "SELECT pecas.* FROM l_pecas".$extensao." AS pecas  ".$left_join.$left_join_filter."  WHERE pecas.visivel = '1' ".$where."  GROUP BY pecas.id".$order_by."";
	}
	/* End|| Vishal Prajapti */

	$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	if(hasParameter($query_rsProdutos, ':categoria')) $rsProdutos->bindParam(':categoria', $categoria, PDO::PARAM_INT);
	if(hasParameter($query_rsProdutos, ':pesq')) $rsProdutos->bindValue(':pesq', "%$nome_pesq_query%", PDO::PARAM_STR);
	if(hasParameter($query_rsProdutos, ':marca')) $rsProdutos->bindValue(':marca', $marca, PDO::PARAM_INT);
	}



	$rsProdutos->execute();
	$row_rsProdutos = $rsProdutos->fetchAll();
	$totalRows_rsProdutos = $rsProdutos->rowCount();
	
	$cate_list = array(); 
	foreach ($row_rsProdutos as $value) {

		if (CATEGORIAS == 2) {
			$query_rsCategoria = "SELECT id_categoria FROM l_pecas_categorias WHERE id_peca=:id_peca";
			$rsCategoria = DB::getInstance()->prepare($query_rsCategoria);
			$rsCategoria->bindParam(':id_peca', $value['id'], PDO::PARAM_INT, 5); 
			$rsCategoria->execute();
			$row_rsCategoria_product = $rsCategoria->fetchAll(PDO::FETCH_ASSOC);
		}

		foreach ($row_rsCategoria_product as $categoria_product) {
			if (!in_array($categoria_product['id_categoria'], $cate_list)) {
				$cate_list[] = $categoria_product['id_categoria'];
			}
		}

	}
	
	$json_cate_list = implode(',', $cate_list);

	$total_prods = $totalRows_rsProdutos;

	//necessário por causa dos caracteres especiais
	header("Content-type: text/html; charset=UTF-8");
	
	$is_first = $_POST['start'];
	if($is_first == 1) {
		$page = 1;
	}
	else {
		$page = (int) (!isset($_POST['first'])) ? 1 : $_POST['first'];
	}
	
	$limit = $_POST['limit']; #item per page
	
	# find out query stat point
	$start = ($page * $limit) - $limit;
	# query for page navigation
	if($totalRows_rsProdutos > ($page * $limit)) {
		$next = ++$page;
	}
	
	$query_rsProdutos = $query_rsProdutos. " LIMIT {$start}, {$limit}";
	$rsProdutos = DB::getInstance()->prepare($query_rsProdutos);
	if(hasParameter($query_rsProdutos, ':categoria')) $rsProdutos->bindParam(':categoria', $categoria, PDO::PARAM_INT);
	if(hasParameter($query_rsProdutos, ':pesq')) $rsProdutos->bindValue(':pesq', "%$nome_pesq_query%", PDO::PARAM_STR);
	if(hasParameter($query_rsProdutos, ':marca')) $rsProdutos->bindValue(':marca', $marca, PDO::PARAM_INT);
	$rsProdutos->execute();
	$row_rsProdutos = $rsProdutos->fetchAll();
	$totalRows_rsProdutos = $rsProdutos->rowCount();

	DB::close();
$query_rsCheck = "SELECT * FROM l_categorias".$extensao." Where type = 1";
$rs_Check = DB::getInstance()->prepare($query_rsCheck);
$rs_Check->execute();
$row_Check = $rs_Check->fetchAll();
$totalRows_rsCheck = $rs_Check->rowCount();

	//print_r($totalRows_rsProdutos); 
	if($totalRows_rsProdutos > 0) { ?>

		<?php if($is_first == 1) { ?>
			
			<?php /*<input type="hidden" name="teste" id="total_prods" value="<?php var_dump($rsProdutos); ?>" /> */ ?>
			<input type="hidden" name="json_cate_list" id="json_cate_list" value="<?php echo $json_cate_list; ?>" />

			<?php if ($nome_pesq_query){ ?>
				<div class="div_100 titulo_pesq">
					<h1><?php echo $Recursos->Resources["pesq_listagem"].'<span>'.$nome_pesq_query.'</span>'; ?></h1>
					<p><?php echo $Recursos->Resources["pesq_resultados"].$total_prods; ?></p>
				</div>
			<?php } ?>

		<?php } 
			if($catmae != 0)
			{ 
		?>
			<?php foreach($row_rsProdutos as $produtos) {
				echo $class_produtos->divsProduto($produtos, 'column');
			}
		}
		else if($url_param[1]) {
			//print_r($url_param[1]) ;
			foreach($row_rsProdutos as $produtos) {
				echo $class_produtos->divsProduto($produtos, 'column');
			}
		 ?>
		<input type="hidden" name="total_prods" id="total_prods" value="<?php echo $total_prods; ?>" />
		<?php } else {
			if($_SESSION["store_id"] != "")
			{
		        $query_rsbanners_h = "SELECT * FROM l_categorias".$extensao." as cat INNER JOIN category_store ON cat.id = category_store.category_id WHERE category_store.store_id = ".$_SESSION["store_id"]." AND cat.cat_mae=:id";
				$rs_banners_h = DB::getInstance()->prepare($query_rsbanners_h);
				$rs_banners_h->bindParam(':id', $_REQUEST["id"], PDO::PARAM_INT, 5);
				$rs_banners_h->execute();
				$row_rsbanners_h = $rs_banners_h->fetchAll();
				$totalRows_rsbanners_h = $rs_banners_h->rowCount();
			}

			if($_SESSION["store_id"] == "")
			{
				$query_rsbanners_h = "SELECT * FROM l_categorias".$extensao." as cat  WHERE cat.cat_mae=:id";
				$rs_banners_h = DB::getInstance()->prepare($query_rsbanners_h);
				$rs_banners_h->bindParam(':id', $_REQUEST["id"], PDO::PARAM_INT, 5);
				$rs_banners_h->execute();
				$row_rsbanners_h = $rs_banners_h->fetchAll();
				$totalRows_rsbanners_h = $rs_banners_h->rowCount();
			}

				/* End || Vishal Prajapti */

				foreach ($row_rsbanners_h as $value) {	
				?>
				<div class="produtos_divs text-left">
					<a href="<?php echo ROOTPATH_HTTP_LANG.$value['url']; ?>" class="nav-link uppercase <?php if($cat_redirect == $value['id'] || $sub_redirect == $value['id']) echo ' active';?> <?php if(!empty($subs_cats)) echo "header_drop"; ?>" data-id="<?php echo $value['id']; ?>">
					<table border="1">
						<tr>
							<td><img width="200px" height="200px;" src="<?php echo ROOTPATH_HTTP; ?>imgs/categorias/<?php echo $value['imagem1']; ?>"></td>
						</tr>
						<tr>
							<td><?php echo $value["nome"]; ?></td>
						</tr>
						
					</table>
				</a>
				</div>
				 <input type="hidden" name="total_cat" id="total_cat" value="<?php echo $totalRows_rsbanners_h; ?>" />
	  <?php	} }


	} else { ?>

		<?php  if ($nome_pesq_query) { ?>
				<div class="div_100 titulo_pesq">
					<h1><?php echo $Recursos->Resources["pesq_listagem"].'<span>'.$nome_pesq_query.'</span>'; ?></h1>
					<p><?php echo $Recursos->Resources["pesq_resultados"].$total_prods; ?></p>
				</div>
				<h6 class="sem_prods"><?php echo $Recursos->Resources["pesq_zeo_result"]; ?></h6>
		<?php }

	  } ?>

	

<input type="hidden" name="json_cate_list" id="json_cate_list" value="<?php echo $json_cate_list; ?>" />
<?php } ?>


