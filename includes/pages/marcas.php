<?php include_once('pages_head.php'); ?>


<?php 
	
	$query_rsMarcas = "SELECT * FROM l_marcas".$extensao." AS marcas WHERE marcas.visivel=1 GROUP BY marcas.id ORDER BY marcas.ordem ASC, marcas.nome ASC";
	$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
	$rsMarcas->execute();
	$row_rsMarcas = $rsMarcas->fetchAll();
	$totalRows_rsMarcas = $rsMarcas->rowCount();

?>

<main class="page-load marcas">
	<nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
            <div class="column">
                <ul class="breadcrumbs">
                    <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
                    <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
                    <li>
                         <span><?php echo $Recursos->Resources["Marcas"]; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="div_100 faqs_cont"> 
        <div class="row content">
            <div class="column small-12">
            	<div class="row collapse align-center">
            		<div class="column small-12 medium-10">
	            		<h2 class="paginas_tit center top"><?php echo $Recursos->Resources["Marcas"]; ?></h2>
		            	<ul class="marcas-ul">
		            		<?php foreach ($row_rsMarcas as $key => $marcas ): ?>
                                <li>
                                    <?php $img = ROOTPATH_HTTP."imgs/elem/geral.jpg";
                                        $no_bg = 1;  
                                        if($marcas['imagem1'] && file_exists(ROOTPATH."imgs/marcas/".$marcas['imagem1'])) {
                                            $img = ROOTPATH_HTTP."imgs/marcas/".$marcas['imagem1'];
                                            $no_bg = 0; 
                                        }
                                    ?>
                                    <a href="loja?marcas=<?php echo $marcas['id']; ?>" title="">
                                        <div class="div_100 lazy <?php if($no_bg==1){ echo 'no_bg'; } ?>" style="background-image:url('<?php echo $img; ?>');" style="margin-bottom: 0;">
                                              <?php echo getFill('marcas'); ?>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach ?>
		            	</ul>
		            </div>
            	</div>
            </div>
        </div>
    </div>	
</main>

<?php include_once('pages_footer.php'); ?>




