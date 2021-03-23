<?php include_once('pages_head.php');

$query_rsImagem = "SELECT * FROM imagens_topo";
$rsImagem = DB::getInstance()->query($query_rsImagem);
$row_rsImagem = $rsImagem->fetch(PDO::FETCH_ASSOC);
$totalRows_rsImagem = $rsImagem->rowCount();
DB::close();

$faqs = [];
$faqs_cats = [];

geraSessions('faqs2');

$categoria = key($GLOBALS['divs_faqs2']);

if(!$GLOBALS['divs_faqs2'][$categoria]['info']) {
    $faqs = $GLOBALS['divs_faqs2'];
}
else {
    $faqs_cats = $GLOBALS['divs_faqs2'];
}

$menu_sel="faqs";

$query_rsContent = "SELECT * FROM faq_content".$extensao;
$rsContent = DB::getInstance()->query($query_rsContent);
$row_rsContent = $rsContent->fetch(PDO::FETCH_ASSOC);



?>

<main class="page-load faqs">
    <?php
    $img = "elem/topo.jpg";
    if($row_rsImagem['faqs'] && file_exists(ROOTPATH.'imgs/imagens_topo/'.$row_rsImagem['faqs'])){
       $img = "imagens_topo/".$row_rsImagem['faqs']; 
    }
    ?>
    <div class="div_100 banners banner_contactos has_mask lazy banner_faqs_bg_data" data-src="<?php echo $img; ?>" style="margin-bottom: 0;">
        <?php echo getFill('imagens_topo'); ?>      
        <div class="banner_cont<?php echo $mask; ?>" style="padding:0">    
            <div class="row align-middle" style="height: 100%;">
                <div class="column small-12">
                    <div class="banner_content text-center banner_content_h1_data" style="max-width: unset;">
                        <h1 class="titulos show titulos_data_faq"<?php if($color) echo ' style="color:'.$color.'"';?>><?php echo $Recursos->Resources["faqs"]; ?></h1>
                    </div>
                </div>
            </div> 
        </div> 
    </div>

    <nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
            <div class="column">
                <ul class="breadcrumbs">
                    <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
                    <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
                    <li>
                         <span><?php echo $Recursos->Resources["faqs"]; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="div_100 faqs_cont"> 
        <div class="row content">
            <div class="column small-12 medium-6 faqs_cont_row_tabs">
                <div class="faq-head-wrap">
                    <h2><?php echo $Recursos->Resources["faqs"]; ?></h2>
                </div>
                <?php if(!empty($faqs_cats) && count($faqs_cats)>1){ ?>
                    <div class="row collapse">
                        <div class="column small-4 show-for-medium">
                            <div class="faqs_cats to_sticky vertical" id="faqsCats">
                                <?php foreach($faqs_cats as $cats){ 
                                    if($cats['info']){
                                        $cats = $cats['info'];
                                    }
                                ?>
                                    <a class="list_subtit" href="javascript:;" data-anchor="<?php echo verifica_nome(strtolower($cats['nome'])); ?>"><?php echo $cats['nome']; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="column small-12 medium-8">
                            <?php foreach($faqs_cats as $cats){ 
                                $faqs = $cats['subs'];
                                if($cats['info']){
                                    $cats = $cats['info'];
                                }
                            ?>
                            <div class="div_100 text-center 3333" id="<?php echo verifica_nome(strtolower($cats['nome'])); ?>">
                                <h3 class="subtitulos"><?php echo $cats['nome']; ?></h3>
                                <ul accordion styled>
                                    <?php foreach($faqs as $faq){ ?>
                                        <li accordion-item id="faq_<?php echo $faq['id']; ?>">
                                            <a href="javascript:;" class="list_tit" accordion-title><?php echo $faq['pergunta']; ?></a>
                                            <div class="textos" accordion-content><?php echo $faq['resposta']; ?></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } elseif(!empty($faqs_cats) && count($faqs_cats)==1){  ?>
                    <?php foreach($faqs_cats as $cats){ 
                        $faqs = $cats['subs'];
                        if($cats['info']){
                            $cats = $cats['info'];
                        }
                        ?>
                        <div class="div_100 text-center 4444" id="<?php echo verifica_nome(strtolower($cats['nome'])); ?>">
                            <!-- <h3 titles><?php //echo $cats['nome']; ?></h3> -->
                            <ul accordion styled>
                                <?php foreach($faqs as $faq){ ?>
                                    <li accordion-item id="faq_<?php echo $faq['id']; ?>">
                                        <a href="javascript:;" class="list_tit" accordion-title><?php echo $faq['pergunta']; ?></a>
                                        <div class="textos" accordion-content><?php echo $faq['resposta']; ?></div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                <?php }elseif(!empty($faqs)){ ?>
                    <ul accordion styled>
                        <?php foreach($faqs as $faq){ ?>
                            <li accordion-item id="faq_<?php echo $faq['id']; ?>">
                                <a href="javascript:;" class="list_tit" accordion-title><?php echo $faq['pergunta']; ?></a>
                                <div class="textos" accordion-content><?php echo $faq['resposta']; ?></div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php }else{ ?>
                    <h6 class="sem_prods"><?php echo $Recursos->Resources["sem_produtos"]; ?></h6>
                <?php } ?>
            </div>

        </div>
    </div>
</main>

<?php include_once('pages_footer.php'); ?>