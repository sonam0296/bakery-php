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

// echo $query_rsdestaque;
// pr($row_rsdestaque);
// echo $totalRows_rsdestaque;
// die('stop');


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


$ids_home = '1';
$query_rsPa1 = "SELECT * FROM homepage".$extensao." WHERE id = :ids";
$rsPa1 = DB::getInstance()->prepare($query_rsPa1);
$rsPa1->bindParam(':ids', $ids_home, PDO::PARAM_INT);	
$rsPa1->execute();
$row_rsPa1 = $rsPa1->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPa1 = $rsPa1->rowCount();
DB::close();

//pr($row_rsPa1);

$menu_sel = "home";
?>

<main class="page-load homepage">

	<section class="div_100 middle" id="banners">
	    <div class="row align-middle">
		<?php include_once(ROOTPATH.'includes/index_banners.php'); ?>
		</div>
	</section>
	
	<?php if($totalRows_rsPa){ ?>	
		<!-- <section class="div_100 middle" id="info">
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
		</section> -->
	<?php } ?>	


	<section class="div_100 middle" id="info_products">
	    <div class="row">
			<div class="column">
				<div class="head_wrap_main">
					<h2><?php echo $row_rsPa1['home_pft']; ?></h2>
					<p><?php echo $row_rsPa1['home_pfc']; ?></p>
				</div>
			</div>
		</div>
	</section>

	<?php if($totalRows_rsdestaque > 0) { ?>
		<section class="div_100 slider-container" id="destaques">
			<div class="row medium-collapse">
				<div class="inner-container-wrap">	
					<div class="column text-center medium-text-left">
						<div class="outer">
							<div class="div_100">
								<div class="slick-of3 has_dots">
									<?php foreach($row_rsdestaque as $destaque) {
										//pr($destaque);
										 ?>
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
	

	<section class="div_100 middle" id="info_category">
	   	<div class="row">
			<div class="column">
				<div class="head_wrap_main">
					<h2><?php echo $row_rsPa1['home_cft']; ?></h2>
					<p><?php echo $row_rsPa1['home_cfc']; ?></p>
				</div>
			</div>
		</div>
	</section>


	<?php if($totalRows_rsDestaquesBig > 0){ ?>	
	<section class="div_100" id="categorias_populares">
		<div class="row">
			<div class="column">
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



	<section class="section variety-of-tasty-cakes">
		<div class="row">
			<div class="column">
				<div class="column-wrap" style="background-image: url(https://bbakery.co.uk/wp-content/uploads/2019/08/variety-of-tasty-cakes.png); background-position: center center;background-repeat: no-repeat; background-size: cover;">
					<div class="widget-wrap">
						<div class="widget-container">
							<div class="text-editor clearfix">
								<h4 class="title">Variety of tasty cakes</h4>
								<h5><a href="https://bbakery.co.uk/shop/">View all cakes</a></h5>
								<h6 class="nearest-store-code">
									<a href="#">Call your nearest branch</a>
								</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="div_100 middle" id="info_delivered">
		<div class="row">
			<div class="column">
				<div class="head_wrap_main">
					<h2>Fresh Cakes Delivered to You in Birmingham</h2>
					<p>We offer a full delivery service, so whether you are planning a small family birthday party or need cakes for a large corporate event or function, we&#39;ll ensure your cake arrives swiftly and safely. We have several bakeries in Birmingham. Use our store locator to find your local cake shop. Our cakes are available to suit all your dietary needs including gluten-free, dairy free, vegan, and diabetic. We&#39;ll try to accommodate your every need so if you have a special dietary or other requirement just let us know and we&#39;ll let you know if it is possible.</p>
				</div>
			</div>
		</div>
	</section>


	<section class="section" id="botton-info-section">
		<div class="container">
			<div class="row">
				<div class="column top-column">
					<div class="column-wrap element-populated">
						<div class="widget-wrap">
							<div class="element widget-image">
								<div class="widget-container">
									<div class="image">
										<a href="#">
											<img width="538" height="261" src="https://bbakery.co.uk/wp-content/uploads/2019/08/n-locations.png" class="attachment-large size-large" alt="">
										</a>
									</div>
								</div>
							</div>
							<div class="element widget-heading">
								<div class="widget-container">
									<h5 class="heading-title size-default"><a href="#">Find our branches</a></h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="column-wrap element-populated">
						<div class="widget-wrap">
							<div class="element widget-image">
								<div class="widget-container">
									<div class="image">
										<a href="#">
											<img width="231" height="261" src="https://bbakery.co.uk/wp-content/uploads/2019/08/n-careers.png" class="attachment-large size-large" alt="" loading="lazy">
										</a>
									</div>
								</div>
							</div>
							<div class="element widget-heading">
								<div class="widget-container">
									<h5 class="heading-title size-default"><a href="#">Join our team</a></h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="column-wrap element-populated">
						<div class="widget-wrap">
							<div class="element widget-image">
								<div class="widget-container">
									<div class="image">
										<a href="#">
											<img width="538" height="261" src="https://bbakery.co.uk/wp-content/uploads/2019/08/franchise.png" class="attachment-large size-large" alt="">
										</a>
									</div>
								</div>
							</div>
							<div class="element widget-heading">
								<div class="widget-container">
									<h5 class="heading-title size-default"><a href="#">Make Enquiry</a></h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</main>



<?php include_once('pages_footer.php'); ?>