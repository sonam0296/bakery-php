<?php include_once('pages_head.php');
geraSessions('noticias');

$noticia = $_GET['id'];
//pre($noticia);
//die('test');
$row_rsNoticias = $GLOBALS['divs_noticias'][$noticia];

if($GLOBALS['divs_noticias'][$noticia]['info']) {
    $row_rsSubs = $GLOBALS['divs_noticias'][$noticia]['subs'];
    $row_rsNoticias = $GLOBALS['divs_noticias'][$noticia]['info'];
}

$text_align = "";
if($row_rsNoticias['text_align']){
    $text_align = " ".$row_rsNoticias['text_align'];
}


//procura produto anterior e seguinte
$query_rsTotal = "SELECT * FROM noticias".$extensao." ORDER BY ordem ASC";
$rsTotal = DB::getInstance()->prepare($query_rsTotal);
$rsTotal->execute();
$totalRows_rsTotal = $rsTotal->rowCount();
DB::close();

$prod_ant = "";
$prod_ant_id = "";
$prod_seg = "";
$prod_seg_id = "";
$encontrado = 0;
$conta_reg = 0;

if($totalRows_rsTotal > 1) {
    while($row_rsTotal = $rsTotal->fetch()){
        
        $registo_actual++;
        
        if($encontrado == 1){
            $prod_seg = $row_rsTotal['url'];
            $prod_seg_id = $row_rsTotal['id'];
            break;          
        }
        
        if($row_rsTotal['id'] != $noticia && $encontrado == 0) {
            $prod_ant = $row_rsTotal['url'];
            $prod_ant_id = $row_rsTotal['id'];
        } else if($row_rsTotal['id'] == $noticia) {
            $encontrado = 1;            
        }
    
    }
}else{
    $registo_actual++;
}

$query_rsMetatags = "SELECT title, description, keywords, url FROM noticias".$extensao." WHERE id = :id";
$meta_id = $noticia;

$menu_sel="noticias";
 

?>
<style type="text/css">
    .popup_container.active {
    opacity: 0;
    visibility: hidden;
}
</style>
<main class="div_100 noticias-detail the_news_detail">
    <!-- <a class="close-button" data-remote="true" data-ajaxurl="<?php //echo ROOTPATH_HTTP; ?>includes/pages/noticias.php" data-pagetrans="close-noticias-detail">&times;</a> -->
    <?php 
    $get_lang = explode('_', $extensao);
$lang = $get_lang[1];
    ?>
    <nav class="breadcrumbs_cont breadcrumbs_detail" aria-label="You are here:" role="navigation">
        <div class="row">
            <div class="column">
                <ul class="breadcrumbs">
                    <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
                    <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="true"><?php echo $Recursos->Resources["home"]; ?></a></li>
                    <li>
                         <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias.php"><span><?php echo $Recursos->Resources["noticias"]; ?></span></a>
                    </li>
                    <li><span><?php echo $row_rsNoticias['nome']; ?></span></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="div_100 noticias_detalhe">
        <div class="noticias_detalhe_topbox">
            <strong><?php 
            $get_date = explode("-", $row_rsNoticias['data']);
            $year = $get_date[0];
            $month = $get_date[1];
            $day = $get_date[2]; 
            $final_date= $day.'-'.$month.'-'.$year;
            echo $final_date;
            ?></strong>
            <strong><?php echo $row_rsNoticias['tags']; ?></strong>
        </div><!-- /.noticias_detalhe_topbox -->
        <h1 class="subtitulos<?php echo $text_align; ?>"><?php echo $row_rsNoticias['nome']; ?></h1>
        <article class="row align-center">

            <?php if(!empty($row_rsSubs)) { ?>
                <div class="small-12 medium-12 medium-order-1 column" role="banner">
                    <div class="div_100 img">
                        <div class="slick-cont" style="display:block;">
                            <?php foreach($row_rsSubs as $row) { ?>
                                <?php if($row['imagem1'] && file_exists(ROOTPATH.'imgs/noticias/'.$row['imagem1'])){ ?>
                                <div class="slide">
                                    <div class="div_100 has_bg lazy" data-src="noticias/<?php echo $row['imagem1']; ?>">
                                        <?php echo getFill('noticias'); ?>   
                                    </div>
                                </div>
                                <?php } ?> 
                            <?php } ?> 
                        </div>
                    </div>
                </div>
            <?php } ?> 
            <main class="small-12 medium-12 medium-order-2 column text-left">
                <?php if(empty($row_rsSubs) && $row_rsNoticias['imagem1'] && file_exists(ROOTPATH.'imgs/noticias/'.$row_rsNoticias['imagem1'])){ ?>
                    <picture class="div_100">
                        <img class="lazy" data-src="noticias/<?php echo $row_rsNoticias['imagem1']; ?>" style="margin:auto; max-height: 500px; width: auto;" />
                    </picture>
                <?php } ?> 
                <div class="div_100 info to_sticky" id="noticia_info" style="left: auto;">
                    <div class="div_100 navigation">
                        <!-- <div class="svg-wrap hidden">
                            <svg width="64" height="64" viewBox="0 0 64 64">
                                <path id="arrow-left" d="M44.797 17.28l0.003 29.44-25.6-14.72z" />
                            </svg>
                            <svg width="64" height="64" viewBox="0 0 64 64">
                                <path id="arrow-right" d="M19.203 17.28l-0.003 29.44 25.6-14.72z" />
                            </svg>
                        </div> -->
                        <!-- <div class="row collapse">
                            <div class="column">
                                <?php if($prod_ant){ ?>
                                <a class="prev" href="<?php echo $prod_ant; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/noticias-detalhe.php" data-ajaxTax="<?php echo $prod_ant_id; ?>" data-remote="true" data-pagetrans="noticias-detail" data-detail="1"> 
                                    <span class="icon-wrap"><svg class="icon" width="20" height="20" viewBox="0 0 64 64"><use xlink:href="#arrow-left"></use></svg></span>
                                    <div><span class="list_subtit"><?php echo $Recursos->Resources["anterior"]; ?></span></div>
                                </a>
                                <?php } ?>
                            </div>
                            <?php if($prod_seg){ ?>
                            <div class="column text-right">
                                <a class="next" href="<?php echo $prod_seg; ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/noticias-detalhe.php" data-ajaxTax="<?php echo $prod_seg_id; ?>" data-remote="true" data-pagetrans="noticias-detail" data-detail="1">
                                    <span class="icon-wrap"><svg class="icon" width="20" height="20" viewBox="0 0 64 64"><use xlink:href="#arrow-right"></use></svg></span>
                                    <div><span class="list_subtit"><?php echo $Recursos->Resources["seguinte"]; ?></span></div>
                                </a>
                            </div>
                            <?php } ?>
                        </div> -->
                    </div>
                    <div class="div_100 container text-center">
                        
                        <div class="desc textos text-left"><?php echo $row_rsNoticias['descricao']; ?></div>
                        <?php if($row_rsNoticias['ficheiro'] && file_exists(ROOTPATH."imgs/noticias/".$row_rsNoticias['ficheiro'])){ ?>
                        <div class="div_100 text-center" style="margin-top: 20px">
                            <a class="button" href="<?php echo ROOTPATH_HTTP."imgs/noticias/".$row_rsNoticias['ficheiro']; ?>" target="_blank">Download</a>
                        </div>
                        <?php } ?>
                        <!-- <div class="share">
                            <?php
                            #$sharePos = "top";
                            #$shareClass = "shareNormal"; //shareInvert
                            #$shareNome = urlencode(utf8_encode($row_rsNoticias["nome"]));
                            #$shareDesc = urlencode(str_replace(utf8_encode('"'), "'", $row_rsNoticias["resumo"]));
                            #$shareUrl = ROOTPATH_HTTP.$row_rsNoticias["url"];
                            #if($countLang>1) $shareUrl = ROOTPATH_HTTP.$lang."/".$row_rsNoticias["url"];
                            #$shareImg = ROOTPATH_HTTP."/imgs/noticias/".$row_rsNoticias["imagem1"];
                            #include_once(ROOTPATH.'includes/share-list.php');
                            ?>
                        </div> -->
                    </div>
                </div>
                <div class="blog_detail_pagination">
                    <div class="detail_pagination_left">
                        <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias.php"><img src="http://propostas.netgocio.pt/istofazse/imgs/svg/arrow_left_img.svg"><?php echo $Recursos->Resources["notice_list"]; ?></a>
                    </div><!-- /.detail_pagination_left -->
                    <div class="detail_pagination_right">
                        <?php  
                        //echo 'left prod_ant=>'.$prod_ant;
                        if($prod_ant) { ?>
                             <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias-detalhe.php?id=<?php echo $prod_ant_id; ?>"><img src="http://propostas.netgocio.pt/istofazse/imgs/svg/arrow_left_img.svg"><?php echo $Recursos->Resources["prev_notice"]; ?></a>
                        <?php } ?>
  
                        <?php 
                        //echo 'right prod_seg=>'.$prod_seg;
                        if($prod_seg) { ?>
                            <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias-detalhe.php?id=<?php echo $prod_seg_id; ?>"><?php echo $Recursos->Resources["next_notice"]; ?><img src="http://propostas.netgocio.pt/istofazse/imgs/svg/arrow_right_img.svg"></a>
                        <?php } ?>
                    </div><!-- /.detail_pagination_right -->
                </div><!-- /.blog_detail_pagination -->
            </main>
        </article>
        
    </section>    
</main>

<?php include_once('pages_footer.php'); ?>