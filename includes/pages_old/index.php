<?php //echo "<pre>"; print_r($Recursos); echo "</pre>"; die(); 

?>
<style>
.ratinghome {
    display:block !important;
}
/*#banners1 .slick-slide div, #banners1 .banners_slide{ display: inline-block !important; }*/
.banners .banner_cont .desktop-bananer {
   width: 50% !important;
   padding-right: 5%;
}
#banners1 .banner-main-wrapper{ display: flex !important; }
.banners .banner_cont .banner_content{ width: 50% !important; max-width: 50%;  }
/*.banners.fullscreen {
    height: 680px !important;
    
}*/

#banners1 .slick-slide div .inner-wrap {
    position: relative;
    width: 100% !important;
    height: 100% !important;
}

.banners .banner_cont{ padding: 0 !important;  }
.desktop-bananer img{ display: inline-block !important; }
.desktop-bananer div{ height: auto !important; }
.banner-modal-display {
    background-image: none !important;
}
.banners .banner_cont a.linker {
    display: none;
}

.mark-top svg,.mark-bottom svg {
    fill: #fff;
}
.mark-top {
    width: 30% !important;
    position: absolute;
    left: 10%;
    top:0;
    max-width: 230px;
}
.desktop-bananer {
    position: relative;
}
#banners1 .slick-slide div .mark-bottom{ height: 100% !important; }
.mark-bottom {
    position: absolute;
    left: 0;
    bottom: 0;
}

.desktop-bananer img {
    position: absolute;
    right: 0%;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
}
div#popup {
    top: 50% !important;
    transform: translateY(-50%) !important;
    z-index: 121111111;
    height: 80vh;
    overflow-y: auto;
}
/*body .header {
    z-index: 10 !important;
}*/
.textos{
	color: black !important;
}

</style>
<?php include_once('pages_head.php');

geraSessions('banners');
geraSessions('homepage');
geraSessions('destaques');
geraSessions('novidades');
geraSessions('promocoes');

$query_rsDestaquesBig = "SELECT * FROM destaques".$extensao." WHERE visivel = 1 ORDER BY ordem ASC LIMIT 6";
$rsDestaquesBig = DB::getInstance()->prepare($query_rsDestaquesBig);
$rsDestaquesBig->execute();
$row_rsDestaques = $rsDestaquesBig->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsDestaquesBig = $rsDestaquesBig->rowCount();

$query_rsmarcas = "SELECT * FROM l_marcas".$extensao." WHERE visivel = 1 ORDER BY id ASC LIMIT 50";
$rsmarcas = DB::getInstance()->prepare($query_rsmarcas);
$rsmarcas->execute();
$row_rsmarcas = $rsmarcas->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsmarcas = $rsmarcas->rowCount();

$query_rsnoticias = "SELECT * FROM noticias".$extensao." WHERE visivel = 1 ORDER BY data DESC LIMIT 50";
$rsnoticias = DB::getInstance()->prepare($query_rsnoticias);
$rsnoticias->execute();
$row_rsnoticias = $rsnoticias->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsnoticias = $rsnoticias->rowCount();

$query_rsdestaque = "SELECT * FROM l_pecas".$extensao." WHERE destaque = 1 AND visivel = 1 ORDER BY id ASC LIMIT 50";
$rsdestaque = DB::getInstance()->prepare($query_rsdestaque);
$rsdestaque->execute();
$row_rsdestaque = $rsdestaque->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsdestaque = $rsdestaque->rowCount();

$query_rsnovidade = "SELECT * FROM l_pecas".$extensao." WHERE novidade = 1 AND visivel = 1 ORDER BY id ASC LIMIT 50";
$rsnovidade = DB::getInstance()->prepare($query_rsnovidade);
$rsnovidade->execute();
$row_rsnovidade = $rsnovidade->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsnovidade = $rsnovidade->rowCount();

$query_rshomepage_images = "SELECT * FROM homepage_image".$extensao." WHERE visivel = 1 ORDER BY id ASC LIMIT 50";
$rshomepage_images = DB::getInstance()->prepare($query_rshomepage_images);
$rshomepage_images->execute();
$row_rshomepage_images = $rshomepage_images->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rshomepage_images = $rshomepage_images->rowCount();


$ids = 2;
$query_rsPa = "SELECT * FROM homepage".$extensao." WHERE id = :ids";
$rsPa = DB::getInstance()->prepare($query_rsPa);
$rsPa->bindParam(':ids', $ids, PDO::PARAM_INT);	
$rsPa->execute();
$row_rsPa = $rsPa->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPa = $rsPa->rowCount();
DB::close();



$menu_sel = "home";
?>
<main class="page-load homepage">

	<section class="div_100 middle" id="banners">
	    <div class="row align-middle">
		<?php include_once(ROOTPATH.'includes/index_banners.php'); ?>
		</div>
	</section>
	
<?php if($totalRows_rsPa){ ?>	
	<section class="div_100 middle" id="info">
	<div class="row align-middle div_100">
	   	<ul class="info-mobile_view">
	   		<?php foreach ($row_rshomepage_images as $key => $homepage_images): ?>
		   		<li class="div_40" style="">
			   		<a href="<?php echo $homepage_images['link']; ?>" title="">
				   		<div class="info-icon">
				   			<img src="<?php echo ROOTPATH_HTTP; ?>imgs/homepage/<?php echo $homepage_images['imagem'];  ?>" alt="delivery">
				   		</div>
				   		<div class="info-text">
				   			<h3><?php echo $homepage_images['nome']; ?></h3>
				   			<p><?php echo $homepage_images['titulo']; ?></p>
				   		</div>	
			   		</a>
			   	</li>
		   	<?php endforeach ?>
	   	</ul>

	   </div>
	</section>
<?php } ?>	

<?php if($totalRows_rsdestaque > 0) { ?>
		<section class="div_100 slider-container" id="destaques">
			<div class="row medium-collapse">
				<div class="inner-container-wrap">	
					<div class="column text-center medium-text-left">
						<div class="main-heading-wrap">
							<h3 class="titulos uppercase bold"><?php echo $Recursos->Resources["em_destaques"]; ?></h3>
						</div>
						<div class="outer">
							<div class="div_100">
								<div class="slick-of3 has_dots">
									<?php foreach($row_rsdestaque as $destaque) { ?>
										<?php echo $class_produtos->divsProduto($destaque, ''); ?>
									<?php } ?>
								</div>

							</div>
						</div>
						<div class="view_all_product">
							<a href="loja" title="">VIEW MORE</a>
						</div>
					</div>
				</div>	
			</div>
		</section>
	<?php } ?>	
	
<?php if($totalRows_rsDestaquesBig > 0){ ?>	
	<section class="div_100" id="categorias_populares">
		<div class="row">
			<div class="column">
				<div class="main-heading-wrap">
					<h3 class="titulos uppercase bold"><?php echo $Recursos->Resources["categorias_populares"]; ?></h3>
				</div>
				<div class="divs_destaques">
					<div class="row collapse full main-popular-category-wrap">	
						<?php 
							$count=1; 
							foreach ($row_rsDestaques as $destaque) { ?>
								<?php if ($count <= 6): ?>
									<div class="popular-category-wrap theme-<?php echo $destaque['tema']; ?>" >
										<a class="bt_destaque icon-right" href="<?php echo $destaque['link']; ?>" target="<?php echo $destaque['target']; ?>">
											<?php if($destaque['texto']){ ?>
												<h2 class="subtitulos uppercase" style="color: <?php echo trim($destaque['cor'], ' '); ?>"><?php echo $destaque['texto']; ?></h2>
											<?php } ?>
											<?php if($destaque['titulo']){?>
												<h1 class="titulos after uppercase" style="color: <?php echo trim($destaque['cor'], ' '); ?>"><?php echo $destaque['titulo']; ?></h1>
											<?php } ?>
											<div class="content">
												<div class="imagem has_bg lazy" data-src="destaques/<?php echo $destaque['imagem1']; ?>">
													<?php echo getFill('destaques', 2); ?>
													<div class="conteudo <?php echo $class; ?>">
														<span class="bg-layer" style="background: <?php echo trim($destaque['bg_cor'], ' '); ?>"></span>
													</div>
												</div>
											</div>
										</a>
									</div>	
								<?php endif ?>
							<?php
								$count ++;
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>

	<?php //if(!empty($GLOBALS['divs_novidades']) || !empty($GLOBALS['divs_promocoes'])) { ?>
		<?php 
		/* $style = "background-color:#F3F2F3;";
		if($GLOBALS['divs_homepage']['imagem1'] && file_exists(ROOTPATH.'imgs/homepage/'.$GLOBALS['divs_homepage']['imagem1'])) { 
			$style = "background-image:url('".ROOTPATH_HTTP."imgs/homepage/".$GLOBALS['divs_homepage']['imagem1']."');";
		} */
		?>
		<!--<section class="div_100" id="produtos">
			<picture class="div_100 has_bg fixed" style="<?php //echo $style; ?>">
				<?php //echo getFill('homepage'); ?>
			</picture>
			<div class="row medium-collapse">	
				<div class="column medium-offset-3">
					<div class="div_100 tabs">
						<?php i//f(!empty($GLOBALS['divs_novidades'])) { ?><a class="subtitulos uppercase text-center" href="javascript:;"><?php //echo $Recursos->Resources["novidades"]; ?></a><?php //} ?> 
						<?php //if(!empty($GLOBALS['divs_promocoes'])) { ?><a class="subtitulos uppercase text-center" href="javascript:;"><?php //echo $Recursos->Resources["promocoes"]; ?></a><?php //} ?>
					</div>
					<div class="div_100 tabs-content">
						<?php //if(!empty($GLOBALS['divs_novidades'])) { ?>
							<div class="div_100 tab">
								<div class="slick-of3 has_dots">
									<?php //foreach($GLOBALS['divs_novidades'] as $novidades) { ?>
										<?php //echo $class_produtos->divsProduto($novidades, ''); ?>
									<?php //} ?>
								</div>
							</div>
						<?php //} ?>
						<?php //if(!empty($GLOBALS['divs_promocoes'])) { ?>
							<div class="div_100 tab">
								<div class="slick-of3 has_dots">
									<?php //foreach($GLOBALS['divs_promocoes'] as $promocoes) { ?>
										<?php //echo $class_produtos->divsProduto($promocoes, ''); ?>
									<?php //} ?>
								</div>
							</div>
						<?php //} ?>
					</div>
				</div>	
			</div>
		</section>-->
	<?php //} ?>

	<?php //if($GLOBALS['divs_homepage']['imagem2'] && file_exists(ROOTPATH.'imgs/homepage/'.$GLOBALS['divs_homepage']['imagem2'])) { ?>
		<!--<section class="div_100 banners" id="portfolio">
			<div class="row medium-collapse">	
				<div class="column medium-offset-3">
					<div class="div_100 has_bg has_mask clipped-left lazy" data-src="homepage/<?php //echo $GLOBALS['divs_homepage']['imagem2']; ?>">
						<?php //echo getFill('homepage', 2); ?>
						<div class="banner_cont">
							<div class="row collapse full align-middle" style="height: 100%;">
								<div class="column small-12">                                   
									<div class="banner_content">
										<?php //if($GLOBALS['divs_homepage']['titulo2']) { ?>
											<h2 class="titulos show"><?php //echo $GLOBALS['divs_homepage']['titulo2']; ?></h2>
										<?php //} ?>
										<?php //echo text_link($GLOBALS['divs_homepage']['link2'], $GLOBALS['divs_homepage']['target2'], $GLOBALS['divs_homepage']['texto_link2'], "button show"); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</section>-->
	<?php //} ?>
	
	<?php
	if($totalRows_rsmarcas > 0){
	?>
	<section class="div_100 brand brand_logo_slider" id="brand">
		<div class="row brand-slider" >
			<?php foreach($row_rsmarcas as $marcas){ ?>
		    	<div class="item">
		    		<?php if (!empty($marcas['imagem1'])): ?>
		    			<img class="brnadimg" src="<?php echo ROOTPATH_HTTP; ?>imgs/marcas/<?php echo $marcas['imagem1']; ?>" alt="<?php echo $marcas['nome']; ?>" />
		    		<?php else: ?>
		    			<img class="brnadimg" src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/geral.svg" alt="<?php echo $marcas['nome']; ?>" />
		    		<?php endif ?>
		    	</div>
			<?php } ?>
	    </div>
	</section>
	<?php } ?>
	
	
          
   <?php if($totalRows_rsnoticias > 0) { ?>
		<section class="div_100 gray-bg-light slider-container home_blog" id="novidades">
			<div class="row medium-collapse">
				<div class="inner-container-wrap">
					<div class="column news-wrapper text-center medium-text-left">
						<div class="main-heading-wrap">
							<h3 class="titulos uppercase bold"><?php echo $Recursos->Resources["nutra_news"];?></h3>
						</div>
						<div class="outer"> 
							<div class="div_100 blog_block">
								<div class="slick-of3news has_dots slick-of-blog has_dots noticias_cont">
									<?php foreach($row_rsnoticias as $noticias) { 
										?>
										<article class="noticias_divs the_news_item col-md-4" id="noticia<?php echo $noticias['id']; ?>">
					                        <figure>
					                            <picture class="img has_bg has_mask icon-mais lazy" data-src="noticias/<?php echo $noticias['imagem1']; ?>">
					                                <?php echo getFill('noticias', 2); ?> 
					                            </picture>                              
					                            <figcaption class="info text-left">
					                                <div class="blog_box_date">
					                                    <strong><?php echo date('d/m/Y',strtotime($noticias['data'])); ?></strong><br>
					                                    <strong><?php echo ucfirst($noticias['tags']); ?></strong>
					                                </div><!-- /.blog_box_date -->
					                                <h5 class="list_tit"><?php echo $noticias['nome']; ?></h5>
					                                <div class="textos"><?php echo str_text($noticias['resumo'], 300); ?></div>
					                                <button class="button"><?php echo $Recursos->Resources["saiba_mais"];?></button>
					                                <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias-detalhe.php?id=<?php echo $noticias['id']; ?>" class="linker"></a>
					                            </figcaption>
					                        </figure>
					                    </article>
									<?php } ?>
									<?php foreach($row_rsnoticias as $noticias) { 
										?>
										<article class="noticias_divs the_news_item col-md-4" id="noticia<?php echo $noticias['id']; ?>">
					                        <figure>
					                            <picture class="img has_bg has_mask icon-mais lazy" data-src="noticias/<?php echo $noticias['imagem1']; ?>">
					                                <?php echo getFill('noticias', 2); ?> 
					                            </picture>                              
					                            <figcaption class="info text-left">
					                                <div class="blog_box_date">
					                                    <strong><?php echo date('d/m/Y',strtotime($noticias['data'])); ?></strong><br>
					                                    <strong><?php echo ucfirst($noticias['tags']); ?></strong>
					                                </div><!-- /.blog_box_date -->
					                                <h5 class="list_tit"><?php echo $noticias['nome']; ?></h5>
					                                <div class="textos"><?php echo str_text($noticias['resumo'], 300); ?></div>
					                                <button class="button"><?php echo $Recursos->Resources["saiba_mais"];?></button>
					                                <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias-detalhe.php?id=<?php echo $noticias['id']; ?>" class="linker"></a>
					                            </figcaption>
					                        </figure>
					                    </article>
									<?php } ?>
									
								</div>
							</div>
						</div>
						<div class="view_all_product_button">
							<a href="noticias.php" class="button invert2 invert-type3 action">
								<?php echo $Recursos->Resources["ver_todos"];?>
							</a>
						</div>
					</div>	
				</div>	
			</div>
		</section>
	<?php } ?>

</main>



<?php include_once('pages_footer.php'); ?>