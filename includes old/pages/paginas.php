 <?php include_once('pages_head.php');

if($pag_redirect) $pag = $pag_redirect;
if($_GET['id']) $pag = $_GET['id'];
if($pag_menu_redirect) $menu = $pag_menu_redirect;


$row_rsPagina = $GLOBALS['divs_paginas'][$pag]['info'];
$row_rsBlocos = $GLOBALS['divs_paginas'][$pag]['subs'];

$totalRows_rsPaginasMenu = 0; 
if(tableExists(DB::getInstance(), 'menus_pt')) {
    /* MENU LATERAL*/
    $query_rsMenu = "SELECT * FROM menus".$extensao." WHERE id = :menu";
    $rsMenu = DB::getInstance()->prepare($query_rsMenu);
    $rsMenu->bindParam(':menu', $menu, PDO::PARAM_INT, 5); 
    $rsMenu->execute();
    $row_rsMenu = $rsMenu->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsMenu = $rsMenu->rowCount();

    $query_rsPaginasMenu = "SELECT * FROM paginas".$extensao." WHERE menu = :menu";
    $rsPaginasMenu = DB::getInstance()->prepare($query_rsPaginasMenu);
    $rsPaginasMenu->bindParam(':menu', $menu, PDO::PARAM_INT, 5); 
    $rsPaginasMenu->execute();
    $row_rsPaginasMenu = $rsPaginasMenu->fetchAll();
    $totalRows_rsPaginasMenu = $rsPaginasMenu->rowCount();
}


$titulo = $row_rsPagina['nome'];
if($row_rsPagina['titulo']) $titulo = $row_rsPagina['titulo'];


$menu_sel2= "paginas";
$menu_sel = $row_rsPagina['url'];


function getGalVid($pag, $block, $coluna=0){   

    $row_rsBloco = $GLOBALS['divs_paginas'][$pag]['subs'][$block];

    $row_rsBanners = array();
    

    if(!empty($GLOBALS['divs_paginas'][$pag]['subs'][$block]['info'])){
        $row_rsBloco = $GLOBALS['divs_paginas'][$pag]['subs'][$block]['info'];
        if($coluna==0){
            $row_rsBanners = $GLOBALS['divs_paginas'][$pag]['subs'][$block]['subs'];
        }else{
            $query_rsBanners = "SELECT * FROM paginas_blocos_imgs WHERE visivel = '1' AND bloco = :id AND coluna = :coluna ORDER BY ordem ASC";
            $rsBanners = DB::getInstance()->prepare($query_rsBanners);
            $rsBanners->bindParam(':id', $block, PDO::PARAM_INT, 5);
            $rsBanners->bindParam(':coluna', $coluna, PDO::PARAM_INT, 5);
            $rsBanners->execute();
            $row_rsBanners = $rsBanners->fetchAll();
            $totalRows_rsBanners = $rsBanners->rowCount();
            DB::close();
        }
    }
            
        
    if(!empty($row_rsBanners)){
        $fill = getFill('paginas', 2);
        $class=" full";
        $type="";
        $style = "";
        
       /* if($row_rsBloco['video']){
            if(strstr($row_rsBloco['video'], "youtube") || strstr($row_rsBloco['video'], "youtu.be")){
                $type=" youtube";
            }elseif(strstr($row_rsBloco['video'], "vimeo")){
                $type=" vimeo";
            }else{
                $type = "iframe";
            }
        }*/
        
        /*if($row_rsBloco['fullscreen']!=1){
             $fill = getFill('paginas', 2);
        }*/

        if($row_rsBloco['tipo']==2 && $row_rsBloco['tipo']==3 && $row_rsBloco['orientacao']==1 && $row_rsBloco['orientacao']==2){
            $fill = getFill('paginas', 3);
            $class="";
        }
        
        /*if($row_rsBloco['tipo_imagens'] == 0 && $row_rsBloco['esp_imagens'] > 0) {
            $style = "margin-bottom:".$row_rsBloco['esp_imagens']."px";     
        }*/

        $largura_max = "";
        if($row_rsBloco['largura_imgs'] == 2 && $row_rsBloco['valor_largura_imgs']){
            $largura_max = ' max-width: '.$row_rsBloco['valor_largura_imgs'].'px; margin: auto;';
        }
    
    ?>
    <div class="div_100 div_cont_gal" data-color="<?php echo COR_SITE; ?>" data-direction="rl">
        <div class="div_100" style="position: relative;<?php echo $largura_max;?>">
            <button class="arrows_slick gallery_arrows show_arrows prev show-for-medium" aria-label="Prev Arrow" role="button">
                <span class="icon-left"></span>
                <div><div class="has_bg"></div></div>
            </button>       
            <button class="arrows_slick gallery_arrows show_arrows next show-for-medium" aria-label="Next Arrow" role="button">
                <span class="icon-right"></span>
                <div><div class="has_bg"></div></div>
            </button>
            <div class="gallery gallery_slick" style="display:block">
                <?php
                $count = 0;
                foreach($row_rsBanners as $bann){
                    
                    $count++;

                    if($bann['tipo']==0){ 
                    
                        if($count == 1) $fill = getFill('paginas', 2, ROOTPATH."imgs/paginas/".$bann['imagem1']);
                        /*if($row_rsBloco['tipo']==2 && $row_rsBloco['tipo']==3 && $row_rsBloco['orientacao']==1 && $row_rsBloco['orientacao']==2){
                            $fill = getFill('paginas', 3, ROOTPATH."imgs/paginas/".$bann['imagem1']);
                            $class="";
                        }*/
                        $href = "";
                        if($bann['link'] && $bann['link']!=""){
                            $href = $bann['link'];
                            ?>
                        <a href="<?php echo $href; ?>" target="_blank" class="gallery-cell lazy has_bg" data-src="paginas/<?php echo $bann['imagem1']; ?>">
                            <?php echo $fill; ?>
                        </a>
                        <?php }else{ ?>
                        <div  class="gallery-cell lazy has_bg" data-src="paginas/<?php echo $bann['imagem1']; ?>">
                            <?php echo $fill; ?>
                        </div>
                    <?php } ?>
                    <?php }else if($bann['tipo']==1){?>
                        <div class="div_100" style="position: relative;">
                            <?php
                            //fill vídeo proporção
                            if($bann['proporcao_video'] && $count == 1){
                                $proporcao = "16:9";
                                if($bann['proporcao_video'] == 2) $proporcao = "4:3";
                                $fill = getFill('paginas', 2, '', $proporcao);
                            }

                            echo $fill;
                            ?>
                            <div class="pag_video_cont">
                                <video id="video1" class="video_player" loop autobuffer="autobuffer" controls height="100%">
                                    <source src="<?php echo ROOTPATH_HTTP; ?>imgs/paginas/<?php echo $bann['imagem1']; ?>">
                                </video>
                            </div>  
                        </div>        
                    <?php }else{
                        $video=$bann['imagem1'];
                        $class="";

                        if(strstr($video, "youtube") || strstr($video, "youtu.be")){
                            $class=" youtube full";
                        }elseif(strstr($video, "vimeo")){
                            $class=" vimeo full";
                        }else{
                            $class = "iframe";
                        }
                        
                        ?>
                        <div data-thumb="<?php echo $thumb; ?>" style="position:relative;">
                            <div class="div_100">
                                <?php
                                //fill vídeo proporção
                                if($bann['proporcao_video'] && $count == 1){
                                    $proporcao = "16:9";
                                    if($bann['proporcao_video'] == 2) $proporcao = "4:3";
                                    $fill = getFill('paginas', 2, '', $proporcao);
                                }

                                echo $fill;
                                ?>
                            </div>
                            
                            <?php if($class=="iframe"){ ?>
                                <iframe src="<?php echo $video; ?>" allowfullscreen width="854" height="480" frameborder="0"></iframe>
                            <?php }else{ ?>
                                <div class="video_frame absolute<?php echo $class; ?>" data-vid="<?php echo $video; ?>"></div>
                            <?php } ?>  
                        </div>                          
                    <?php } ?>
                <?php } ?> 
            </div>
        </div>
    </div>
    <?php } ?>
<?php
}

?>
<main class="page-load paginas paginas-data-privacy-tab">
    <?php 

        $style = "";

        $head_class="";

        if($row_rsPagina['tem_fundo'] == 1){ 

            if($row_rsPagina['tipo_fundo'] == 1 && $row_rsPagina['cor_fundo']){

                $head_class="has_color has_color_data_tab_privacy";

                $style="background: ".$row_rsPagina['cor_fundo'];

            }

            if($row_rsPagina['tipo_fundo'] == 2 && $row_rsPagina['imagem1'] && file_exists(ROOTPATH.'imgs/paginas/'.$row_rsPagina['imagem1'])){

                 $head_class=" has_bg";

                 //$style='background-image:url('.ROOTPATH_HTTP.'imgs/paginas/'.$row_rsPagina['imagem1'].')';

                 $data_src = 'paginas/'.$row_rsPagina['imagem1'];

            }

        } 



        ?>



    <?php if($row_rsPagina['mostra_titulo'] == 1){ ?>

    <div class="div_100 paginas_header <?php echo $head_class; ?> elements_animated lazy paginas_header-lazy-data-hide" data-src="<?php echo $data_src; ?>" style="<?php echo $style; ?>" >

    <?php } else { ?>

        <div class="div_100 paginas_header<?php echo $head_class; ?> elements_animated lazy paginas_header-lazy-data-hide" data-src="<?php echo $data_src; ?>" style="<?php echo $style; ?>" >

    <?php } ?>

        <?php 

       // pre($row_rsPagina);



        if($row_rsPagina['mostra_titulo'] == 1){ 



            $cor = "#fff";

            if($row_rsPagina['cor_titulo']){

                $cor = $row_rsPagina['cor_titulo'];

            }

        ?>

         <?php if($row_rsPagina['tipo_fundo'] == 2 || $row_rsPagina['tipo_fundo'] == 1){ ?>

            <?php  echo getFill('paginas'); ?>

        <?php } ?>

            <div class="paginas_head_cont paginas_head_cont_data_tab_privacy">

                <div class="div_100" style="height: 100%">

                    <div class="div_table_cell">

                        <div class="row">

                            <div class="column small-12">

                                <h1 style="color: <?php echo $cor; ?>"><?php echo $titulo; ?></h1>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>
    
       <!--  <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div> -->


    <nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
            <div class="column">
                <ul class="breadcrumbs">
                    <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
                    <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
                    <li>
                         <span><?php echo $row_rsPagina["nome"]; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="div_100 paginas_cont paginas_cont_data_tab_privacy"> 
        <div class="row">
            <div class="column small-12">
                <div class="row collapse align-center">
                    
                    <?php if($totalRows_rsPaginasMenu > 0){?>
                        <!-- <div class="column small-3 show-for-medium">
                            <div class="outras_paginas to_sticky vertical" id="outrasPaginas">
                                <?php foreach($row_rsPaginasMenu as $pagin_menu){ ?>
                                    <a class="subtitulos icon-right <?php if($pagin_menu['id']==$pag) echo 'active'; ?>" href="<?php echo $pagin_menu['url']; ?>"><?php echo $pagin_menu['nome']; ?></a>
                                <?php }?>
                            </div>
                        </div> -->
                    <?php }?>

                    <?php if ($pag == 8 || $pag == 7 || $pag == 6 || $pag == 4 || $pag == 3 || $pag == 2 ): ?>
                        <div class="column small-3 show-for-medium">
                            <div class="outras_paginas to_sticky vertical" id="outrasPaginas">
                                <ul>
                                    <li>
                                        <a class="list_txt <?php if($pagMetodos['id']==$pag) echo 'active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagMetodos['url']; ?>"><?php echo $pagMetodos['nome']; ?></a>
                                    </li>
                                    <li>
                                        <a class="list_txt <?php if($pagdelivery_return['id']==$pag) echo 'active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagdelivery_return['url']; ?>"><?php echo $pagdelivery_return['nome']; ?></a>
                                    </li>
                                    <li>
                                        <a class="list_txt <?php if($pagCookies['id']==$pag) echo 'active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagCookies['url']; ?>"><?php echo $pagCookies['nome']; ?></a>
                                    </li>
                                    <li>
                                        <a class="list_txt <?php if($pagPolitica['id']==$pag) echo 'active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagPolitica['url']; ?>"><?php echo $pagPolitica['nome']; ?></a>
                                    </li>
                                    <li>
                                        <a class="list_txt <?php if($pagRal['id']==$pag) echo 'active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagRal['url']; ?>"><?php echo $pagRal['nome']; ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif ?>
                    

                    <div class="column small-12 medium-9">   
                        <?php if(!empty($row_rsBlocos)){?>                 
                            <?php $counter=0; foreach($row_rsBlocos as $bloco){ $counter++; 
                                if(!empty($bloco['info'])){
                                    $bloco = $bloco['info'];
                                }
                                ?>                      
                                <!-- <?php if($row_rsPagina['mostrar_topo'] == 0 && $counter==1 && ($bloco['tipo']==1 || $bloco['tipo']==3)) { ?>
                                    <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                <?php } ?> -->

                                <?php if($bloco['tipo']==1){ //Texto & Imagem / Vídeo ?>
                                <div class="div_100 paginas_container" id="<?php echo verifica_nome(strtolower($bloco['nome'])) ?>">
                                    <div class="row collapse">
                                        <?php if($bloco["titulo"]/* && ($bloco['orientacao']==2 || $bloco['orientacao']==3)*/){ ?>
                                            <div class="column small-12">
                                                <h2 class="paginas_tit elements_animated top full"><?php echo $bloco["titulo"]; ?></h2>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row collapse align-center paginas_bloco">
                                        <?php if($bloco['orientacao']==0 || $bloco['orientacao']==1 || $bloco['orientacao']==2 || $bloco['orientacao']==3){ 
                                                $first_col = "small-12 medium-6 column order-2 medium-order-1 ";
                                                if($bloco['texto_contorna']==1){
                                                    $first_col = "small-12 column order-2 medium-order-1";
                                                }

                                                $second_col = "small-12 medium-6 column order-1 medium-order-2";
                                                $padd_col1 = " order_1 right";
                                                $padd_col2 = " order_2 left";

                                                //largura máxima imagens
                                                $largura_max = "";
                                                if($bloco['largura_imgs'] == 2 && $bloco['valor_largura_imgs']){
                                                    $largura_max = $bloco['valor_largura_imgs'];
                                                    $first_col = "small-12 medium-expand column order-2 medium-order-1 ";
                                                    $second_col = "small-12 medium-shrink column order-1 medium-order-2";
                                                }
                                                
                                                //largura máxima texto
                                                $largura_max_text = "";
                                                if(($bloco['orientacao'] == 2 || $bloco['orientacao'] == 3) && ($bloco['largura_texto'] == 2 && $bloco['valor_largura_texto'])){
                                                    $largura_max_text = $bloco['valor_largura_texto'];
                                                }

                                                if($bloco['orientacao']==1){
                                                    $first_col = "small-12 medium-6 column order-2 medium-order-2";
                                                        
                                                    if($bloco['texto_contorna']==1){
                                                        $first_col = "small-12 column order-2 medium-order-2";
                                                    }
                                                    $second_col = "small-12 medium-6 column order-1 medium-order-1";
                                                    $padd_col1 = " order_1 left";
                                                    $padd_col2 = " order_2 right";
                                                }
                                                if($bloco['orientacao']==2){
                                                    $first_col = "small-12 column order-2 medium-order-2";
                                                        
                                                    if($bloco['texto_contorna']==1){
                                                        $first_col = "small-12 column order-2 medium-order-2";
                                                    }
                                                    $second_col = "small-12 column order-1 medium-order-1";
                                                    $padd_col1 = "top";
                                                    $padd_col2 = "bottom";
                                                }
                                                if($bloco['orientacao']==3){
                                                    $first_col = "small-12 column order-2 medium-order-1 ";
                                                        
                                                    if($bloco['texto_contorna']==1){
                                                        $first_col = "small-12 column order-2 medium-order-2 ";
                                                    }
                                                    $second_col = "small-12 column order-1 medium-order-2 ";
                                                    $padd_col1 = "bottom";
                                                    $padd_col2 = "top";
                                                }


                                            ?>
                                            
                                            <div class="<?php echo $first_col; ?>">
                                                <div class="paginas_conteudo <?php echo $padd_col1; ?>">
                                                    <?php /*if($bloco["titulo"] && ($bloco['orientacao']==0 || $bloco['orientacao']==1)){ ?>
                                                        <h2 class="paginas_tit elements_animated top"><?php echo $bloco["titulo"]; ?></h2>
                                                    <?php }*/ ?>
                                                    <?php /*if($bloco['orientacao']==2){?>
                                                        <div class="div_100 gallery_cont"<?php if($largura_max){?> style="max-width: <?php echo $largura_max."px";?>; margin: auto;"<?php }?>>
                                                            <div class="paginas_img single <?php echo $padd_col2; ?>">
                                                                <?php echo getGalVid($pag, $bloco['id']);?>
                                                            </div>
                                                        </div>
                                                    <?php }*/ ?>
                                                    <div class="paginas_txt elements_animated bottom"<?php if($largura_max_text){?> style="max-width: <?php echo $largura_max_text."px";?>; margin: auto;"<?php }?>>
                                                        <?php if($bloco['texto_contorna']==1){?>
                                                            <div class="paginas_img <?php echo $padd_col1; ?>_float"<?php if($largura_max){?> style="max-width: <?php echo $largura_max."px";?>"<?php }?>>
                                                                <?php echo getGalVid($pag, $bloco['id']);?>
                                                            </div>
                                                        <?php }?>
                                                        <?php echo $bloco["texto"]; ?>
                                                    </div>
                                                    <div class="div_100"<?php if($largura_max_text){?> style="max-width: <?php echo $largura_max_text."px";?>; margin: auto;"<?php }?>>
                                                        <?php echo text_link($bloco['link1'], $bloco['target1'], $bloco['texto_botao1'], "paginas_btn button invert border elements_animated"); ?>
                                                    </div>
                                                    
                                                   <!--  <?php if($bloco['orientacao']==3){?>
                                                        <div class="div_100 gallery_cont"<?php if($largura_max){?> style="max-width: <?php echo $largura_max."px";?>; margin: auto;"<?php }?>>
                                                            <div class="paginas_img single <?php echo $padd_col2; ?>">
                                                                <?php echo getGalVid($pag, $bloco['id']);?>
                                                            </div>
                                                        </div>
                                                    <?php } ?> -->
                                                </div>
                                            </div>
                                            <?php if($bloco['texto_contorna']==0){?>
                                            <div class="<?php echo $second_col; ?>">
                                                <div class="div_100 gallery_cont"<?php if($largura_max){?> style="max-width: <?php echo $largura_max."px";?>; margin: auto;"<?php }?>>
                                                    <div class="paginas_img single <?php echo $padd_col2; ?>">
                                                        <?php echo getGalVid($pag, $bloco['id']);?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?> 
                                        <?php } ?>  
                                    </div>
                                    <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                </div>
                                <?php } ?>
                                
                                <?php if($bloco['tipo']==2){ //Texto

                                    //largura máxima texto
                                    $largura_max_text = "";
                                    if($bloco['largura_texto'] == 2 && $bloco['valor_largura_texto']){
                                        $largura_max_text = $bloco['valor_largura_texto'];
                                    }
                                ?>
                                    <div class="div_100 paginas_container el" id="<?php echo verifica_nome(strtolower($bloco['nome'])) ?>">
                                        <div class="row collapse align-center paginas_bloco cenas">
                                            <?php if($bloco["colunas"] == 1){ ?>
                                            <div class="column">
                                                <div class="div_100 <?php /*limited*/?>"<?php if($largura_max_text){?> style="max-width: <?php echo $largura_max_text."px";?>; margin: auto;"<?php }?>>
                                                    <?php if($bloco["titulo"]){ ?><h2 class="paginas_tit center elements_animated top"><?php echo $bloco["titulo"]; ?></h2><?php } ?>
                                                    <div class="paginas_txt elements_animated bottom"><?php echo $bloco["texto"]; ?></div>
                                                    <?php echo text_link($bloco['link1'], $bloco['target1'], $bloco['texto_botao1'], "paginas_btn button border elements_animated"); ?>
                                                </div>
                                            </div>
                                            <?php }elseif($bloco["colunas"] == 2){ ?>
                                                <?php if($bloco["titulo"]){ ?>
                                                <div class="small-12 column">
                                                    <h2 class="paginas_tit center elements_animated top"><?php echo $bloco["titulo"]; ?></h2>
                                                </div>
                                                <?php } ?>
                                                <div class="small-12 xxsmall-12 medium-5 column">
                                                    <?php if($bloco["titulo1"]){ ?><h3 class="paginas_tit_small elements_animated"><?php echo $bloco["titulo1"]; ?></h3><?php } ?>
                                                    <div class="paginas_txt elements_animated bottom"><?php echo $bloco["texto"]; ?></div>
                                                    <?php echo text_link($bloco['link1'], $bloco['target1'], $bloco['texto_botao1'], "paginas_btn button border elements_animated"); ?>

                                                </div>
                                                <div class="small-12 xxsmall-12 medium-2 column">
                                                    <div class="div_100 paginas_bloco_margin small hide-for-medium" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                                </div>
                                                <div class="small-12 xxsmall-12 medium-5 column">
                                                    <?php if($bloco["titulo2"]){ ?><h3 class="paginas_tit_small elements_animated"><?php echo $bloco["titulo2"]; ?></h3><?php } ?>
                                                    <div class="paginas_txt elements_animated bottom"><?php echo $bloco["texto2"]; ?></div>
                                                    <?php echo text_link($bloco['link2'], $bloco['target2'], $bloco['texto_botao2'], "paginas_btn button border elements_animated"); ?>
                                                </div>
                                            <?php }else{ ?>
                                                <?php if($bloco["titulo"]){ ?>
                                                <div class="small-12 column">
                                                    <h2 class="paginas_tit center elements_animated top"><?php echo $bloco["titulo"]; ?></h2>
                                                </div>
                                                <?php } ?>
                                                <div class="small-12 xxsmall-12 medium-4 column">
                                                    <?php if($bloco["titulo1"]){ ?><h3 class="paginas_tit_small elements_animated"><?php echo $bloco["titulo1"]; ?></h3><?php } ?>
                                                    <div class="paginas_txt has_max elements_animated bottom"><?php echo $bloco["texto"]; ?></div>
                                                    <div class="div_100 paginas_bloco_margin small hide-for-medium" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                                    <?php echo text_link($bloco['link1'], $bloco['target1'], $bloco['texto_botao1'], "paginas_btn button border elements_animated"); ?>
                                                </div>
                                                <div class="small-12 xxsmall-12 medium-4 column">
                                                    <?php if($bloco["titulo2"]){ ?><h3 class="paginas_tit_small elements_animated"><?php echo $bloco["titulo2"]; ?></h3><?php } ?>
                                                    <div class="paginas_txt has_max elements_animated bottom"><?php echo $bloco["texto2"]; ?></div>
                                                    <div class="div_100 paginas_bloco_margin small hide-for-medium" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                                    <?php echo text_link($bloco['link2'], $bloco['target2'], $bloco['texto_botao2'], "paginas_btn button border elements_animated"); ?>
                                                </div>
                                                <div class="small-12 xxsmall-12 medium-4 column">
                                                    <?php if($bloco["titulo3"]){ ?><h3 class="paginas_tit_small elements_animated"><?php echo $bloco["titulo3"]; ?></h3><?php } ?>
                                                    <div class="paginas_txt has_max elements_animated bottom"><?php echo $bloco["texto3"]; ?></div>
                                                    <?php echo text_link($bloco['link3'], $bloco['target3'], $bloco['texto_botao3'], "paginas_btn button border elements_animated"); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                <?php } ?>


                                <?php if($bloco['tipo']==3){ //2 Colunas com Imagem / Vídeo ?>
                                    <div class="div_100 paginas_container estrutura_3" id="<?php echo verifica_nome(strtolower($bloco['nome'])) ?>">
                                        <div class="row collapse align-center paginas_bloco">
                                            <?php if($bloco["colunas"] == 2){?>
                                                <div class="small-12 medium-6 column">
                                                    <?php if($bloco["titulo1"]){ ?>
                                                        <div class="paginas_conteudo  hide-for-medium">
                                                            <h2 class="paginas_tit elements_animated"><?php echo $bloco["titulo1"]; ?></h2>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="div_100 gallery_cont">
                                                        <div class="paginas_img single bottom">
                                                            <?php echo getGalVid($pag, $bloco['id'], 1);?>
                                                        </div>
                                                    </div>
                                                    <div class="paginas_conteudo top">
                                                        <?php if($bloco["titulo1"]){ ?>
                                                            <h2 class="paginas_tit elements_animated top show-for-medium"><?php echo $bloco["titulo1"]; ?></h2>
                                                        <?php } ?>
                                                        <?php if($bloco["texto"]){ ?>
                                                        <div class="paginas_txt elements_animated">
                                                            <?php echo $bloco["texto"]; ?>
                                                        </div>
                                                        <?php }?>
                                                        <?php echo text_link($bloco['link1'], $bloco['target1'], $bloco['texto_botao1'], "paginas_btn button invert border elements_animated"); ?>
                                                    </div>
                                                </div>
                                                <div class="small-12 medium-6 column">
                                                    <?php if($bloco["titulo2"]){ ?>
                                                        <div class="paginas_conteudo hide-for-medium">
                                                            <h2 class="paginas_tit elements_animated"><?php echo $bloco["titulo2"]; ?></h2>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="div_100 gallery_cont">
                                                        <div class="paginas_img single bottom">
                                                            <?php echo getGalVid($pag, $bloco['id'], 2);?>
                                                        </div>
                                                    </div>
                                                    <div class="paginas_conteudo top">
                                                        <?php if($bloco["titulo2"]){ ?>
                                                            <h2 class="paginas_tit elements_animated top show-for-medium"><?php echo $bloco["titulo2"]; ?></h2>
                                                        <?php } ?>
                                                        <?php if($bloco["texto2"]){ ?>
                                                        <div class="paginas_txt elements_animated">
                                                            <?php echo $bloco["texto2"]; ?>
                                                        </div>
                                                        <?php }?>
                                                        <?php echo text_link($bloco['link2'], $bloco['target2'], $bloco['texto_botao2'], "paginas_btn button invert border elements_animated"); ?>
                                                    </div>
                                                </div>
                                            <?php }else{?>
                                                <div class="small-12 medium-4 column">
                                                    <?php if($bloco["titulo1"]){ ?>
                                                        <div class="paginas_conteudo hide-for-medium">
                                                            <h2 class="paginas_tit elements_animated"><?php echo $bloco["titulo1"]; ?></h2>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="div_100 gallery_cont">
                                                        <div class="paginas_img single bottom">
                                                            <?php echo getGalVid($pag, $bloco['id'], 1);?>
                                                        </div>
                                                    </div>
                                                    <div class="paginas_conteudo top">
                                                        <?php if($bloco["titulo1"]){ ?>
                                                            <h2 class="paginas_tit elements_animated top show-for-medium"><?php echo $bloco["titulo1"]; ?></h2>
                                                        <?php } ?>
                                                        <?php if($bloco["texto"]){ ?>
                                                        <div class="paginas_txt elements_animated">
                                                            <?php echo $bloco["texto"]; ?>
                                                        </div>
                                                        <?php }?>
                                                        <?php echo text_link($bloco['link1'], $bloco['target1'], $bloco['texto_botao1'], "paginas_btn button invert border elements_animated"); ?>
                                                    </div>
                                                </div>
                                                <div class="small-12 medium-4 column">
                                                    <?php if($bloco["titulo2"]){ ?>
                                                        <div class="paginas_conteudo hide-for-medium">
                                                            <h2 class="paginas_tit elements_animated"><?php echo $bloco["titulo2"]; ?></h2>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="div_100 gallery_cont">
                                                        <div class="paginas_img single bottom">
                                                            <?php echo getGalVid($pag, $bloco['id'], 2);?>
                                                        </div>
                                                    </div>
                                                    <div class="paginas_conteudo top">
                                                        <?php if($bloco["titulo2"]){ ?>
                                                            <h2 class="paginas_tit elements_animated top show-for-medium"><?php echo $bloco["titulo2"]; ?></h2>
                                                        <?php } ?>
                                                        <?php if($bloco["texto2"]){ ?>
                                                        <div class="paginas_txt elements_animated">
                                                            <?php echo $bloco["texto2"]; ?>
                                                        </div>
                                                        <?php }?>
                                                        <?php echo text_link($bloco['link2'], $bloco['target2'], $bloco['texto_botao2'], "paginas_btn button invert border elements_animated"); ?>
                                                    </div>
                                                </div>
                                                <div class="small-12 medium-4 column">
                                                    <?php if($bloco["titulo3"]){ ?>
                                                        <div class="paginas_conteudo  hide-for-medium">
                                                            <h2 class="paginas_tit elements_animated"><?php echo $bloco["titulo3"]; ?></h2>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="div_100 gallery_cont">
                                                        <div class="paginas_img single bottom">
                                                            <?php echo getGalVid($pag, $bloco['id'], 3);?>
                                                        </div>
                                                    </div>
                                                    <div class="paginas_conteudo top">
                                                        <?php if($bloco["titulo3"]){ ?>
                                                            <h2 class="paginas_tit elements_animated top show-for-medium"><?php echo $bloco["titulo3"]; ?></h2>
                                                        <?php } ?>
                                                        <?php if($bloco["texto3"]){ ?>
                                                        <div class="paginas_txt elements_animated">
                                                            <?php echo $bloco["texto3"]; ?>
                                                        </div>
                                                        <?php }?>
                                                        <?php echo text_link($bloco['link3'], $bloco['target3'], $bloco['texto_botao3'], "paginas_btn button invert border elements_animated"); ?>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                                        <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                    </div>
                                <?php }?>

                                <?php if($bloco['tipo']==4){ //Contactos & Google Maps ?>
                                    <div class="div_100 paginas_container " id="<?php echo verifica_nome(strtolower($bloco['nome'])) ?>">
                                        <div class="row collapse align-center paginas_bloco">
                                            <div class="small-12 medium-6 column order-1 medium-order-2">
                                                <div class="paginas_conteudo left">
                                                    <div class="paginas_txt elements_animated bottom">
                                                        <?php if($bloco["titulo"]){ ?>
                                                            <h2 class="paginas_tit elements_animated top "><?php echo $bloco["titulo"]; ?></h2>
                                                        <?php } ?>
                                                        <?php echo $bloco["texto"]; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="small-12 medium-6 column order-2 medium-order-1">
                                                <div class="div_100 mapa_paginas paginas_img right single">
                                                    <div class="mapa_container">
                                                        <?php echo $bloco['mapa']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                    </div>
                                <?php }?>
                                
                                <?php if($bloco['tipo']==5){ // Formulário
                                    $pos1 = " medium-6 order-1 medium-order-2";
                                    $pos2 = " medium-6 order-2 medium-order-1";
                                    $class1="text-left";
                                    $class2=" full left";  
                                    $class=" full right";
                                    
                                    if($bloco['orientacao']==1){
                                        $pos1 = " medium-6 order-1 medium-order-1";
                                        $pos2 = " medium-6 order-2 medium-order-2"; 
                                        $class1="text-left";
                                        $class=" full left";   
                                        $class2=" full right"; 
                                    }
                                    if($bloco['orientacao']==2){
                                        $class=" top";
                                        $class1="texto_centro";
                                        $pos1 = " medium-9";
                                        $pos2 = " medium-9";
                                    }
                                    ?>
                                    <div class="div_100 paginas_container">
                                        <div class="row collapse align-center paginas_bloco paginas_form <?php echo $class1; ?>">
                                            <?php if($bloco["titulo"] || $bloco["texto"]){ ?>
                                            <div class="small-12<?php echo $pos1; ?> column">
                                                <div class="paginas_conteudo <?php echo $class2; ?>">
                                                    <?php if($bloco["titulo"]){ ?><h2 class="paginas_tit elements_animated top"><?php echo $bloco["titulo"]; ?></h2><?php } ?>
                                                    <?php if($bloco["texto"]){ ?><div class="paginas_txt elements_animated bottom"><?php echo $bloco["texto"]; ?></div><?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="small-12<?php echo $pos2; ?> column">
                                                <form class="<?php echo $class; ?>" action="" onSubmit="return validaForm('form_paginas')" method="post" name="form_paginas" id="form_paginas" novalidate autocomplete="off">
                                                    <div class="animated_elements"> 
                                                        <div class="inpt_holder">
                                                            <label class="inpt_label" for="<?php echo $form_paginas['nome']; ?>"><?php echo $Recursos->Resources["nome"];?> *</label><!--
                                                            --><input required class="inpt" type="text" id="<?php echo $form_paginas['nome']; ?>" name="<?php echo $form_paginas['nome']; ?>" />
                                                        </div>
                                                        <div class="inpt_holder">
                                                            <label class="inpt_label" for="<?php echo $form_paginas['email']; ?>"><?php echo $Recursos->Resources["mail"];?> *</label><!--
                                                            --><input required class="inpt" type="email" id="<?php echo $form_paginas['email']; ?>" name="<?php echo $form_paginas['email']; ?>" />
                                                        </div>
                                                        <div class="inpt_holder">
                                                            <label class="inpt_label" for="<?php echo $form_paginas['assunto']; ?>"><?php echo $Recursos->Resources["assunto"];?> *</label><!--
                                                            --><input required class="inpt" type="text" id="<?php echo $form_paginas['assunto']; ?>" name="<?php echo $form_paginas['assunto']; ?>" />
                                                        </div>
                                                        <div class="inpt_holder textarea">
                                                            <label class="inpt_label" for="<?php echo $form_paginas['mensagem']; ?>"><?php echo $Recursos->Resources["mensagem"];?> *</label><!--
                                                            --><textarea required class="inpt" id="<?php echo $form_paginas['mensagem']; ?>" name="<?php echo $form_paginas['mensagem']; ?>"></textarea>
                                                        </div>

                                                        <div class="inpt_holder simple">
                                                             <div class="inpt_checkbox">
                                                                <input type="checkbox" required name="<?php echo $form_paginas['aceita_politica']; ?>" id="<?php echo $form_paginas['aceita_politica']; ?>" value="1" />
                                                                <label for="<?php echo $form_paginas['aceita_politica']; ?>"><?php echo $Recursos->Resources["aceito_termos"]; ?></label>
                                                             </div>
                                                        </div>
                                                        
                                                        <?php if(CAPTCHA_KEY!=NULL){ ?>
                                                            <div class="inpt_holder simple">
                                                                <div class="captcha" id="paginas_captcha" data-sitekey="<?php echo CAPTCHA_KEY; ?>" data-error="<?php echo $Recursos->Resources["preencha_captcha"]; ?>"></div>
                                                            </div>
                                                        <?php }else{ ?>
                                                            <div class="inpt_holder">
                                                                <?php $cod1=rand(1,10); $cod2=rand(1,10); $cod3=$cod1+$cod2; ?>

                                                                <label class="inpt_label" for="<?php echo $form_seguranca['cod_seg']; ?>"><?php echo $cod1." + ".$cod2." ="; ?></label><!--
                                                                --><input required type="text" class="inpt confirm" name="<?php echo $form_seguranca['cod_seg']; ?>" id="<?php echo $form_seguranca['cod_seg']; ?>" value="" placeholder="<?php echo $Recursos->Resources["seguranca"]; ?> *"/>
                                                                <input type="hidden" class="cod_confirm" name="<?php echo $form_seguranca['cod_res']; ?>" id="<?php echo $form_seguranca['cod_res']; ?>" value="<?php echo $cod3; ?>"/>
                                                            </div>    
                                                        <?php } ?>
                                                        
                                                        <button type="submit" class="button invert border"><?php echo $Recursos->Resources["enviar"];?></button>
                                                        
                                                        <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                                                        <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                                                        <input type="hidden" name="MM_insert" value="form_paginas" />
                                                        <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                                                    </div>                
                                                </form>
                                            </div>
                                        </div>
                                        <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                    </div>
                                <?php } ?> 

                                <?php if($bloco['tipo']==6){ //Download de Ficheiros
                                    $query_rsFicheiro = "SELECT * FROM paginas_blocos_ficheiros".$extensao." WHERE visivel = '1' AND bloco = :id ORDER BY ordem ASC";
                                    $rsFicheiro = DB::getInstance()->prepare($query_rsFicheiro);
                                    $rsFicheiro->bindParam(':id', $bloco['id'], PDO::PARAM_INT, 5);
                                    $rsFicheiro->execute();
                                    $row_rsFicheiro = $rsFicheiro->fetchAll();
                                    $totalRows_rsFicheiro = $rsFicheiro->rowCount();
                                    DB::close();
                                    ?>
                                    <div class="div_100 paginas_container " id="<?php echo verifica_nome(strtolower($bloco['nome'])) ?>">
                                        <div class="row collapse align-center paginas_bloco">
                                            <div class="small-12 column">
                                                <div class="paginas_txt elements_animated bottom">
                                                    <?php if($bloco["titulo"]){ ?>
                                                        <h2 class="paginas_tit elements_animated top "><?php echo $bloco["titulo"]; ?></h2>
                                                    <?php } ?>
                                                    <?php echo $bloco["texto"]; ?>
                                                </div>
                                            </div>
                                            <div class="small-12 column">
                                                <div class="div_100 paginas_downs">
                                                    <?php foreach ($row_rsFicheiro as $ficheiro) {?>
                                                        <a class="bloco_down" href="<?php echo ROOTPATH_HTTP;?>imgs/paginas/<?php echo $ficheiro['ficheiro'];?>" target="_blank">
                                                            <div class="row collapse align-middle">
                                                                <div class="column shrink">
                                                                    <div class="icon icon-download"></div>
                                                                </div>
                                                                <div class="column">
                                                                    <div class="textos">
                                                                        <?php if ($ficheiro['nome']) {
                                                                            echo $ficheiro['nome']."<br>";
                                                                        } ?> 
                                                                        <span><?php echo $ficheiro['tamanho']; ?></span> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php }?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                    </div>
                                <?php } ?>   


                                <?php if($bloco['tipo']==7){ //Timeline
                                    $query_rsTimeline = "SELECT * FROM paginas_blocos_timeline".$extensao." WHERE visivel = 1 AND bloco = :id ORDER BY ordem ASC";
                                    $rsTimeline = DB::getInstance()->prepare($query_rsTimeline);
                                    $rsTimeline->bindParam(':id', $bloco['id'], PDO::PARAM_INT, 5);
                                    $rsTimeline->execute();
                                    $row_rsTimeline = $rsTimeline->fetchAll();
                                    $totalRows_rsTimeline = $rsTimeline->rowCount();
                                    DB::close();    
                                    ?>
                                    <?php if($totalRows_rsTimeline>0){ ?>
                                        <div class="div_100 paginas_container " id="<?php echo verifica_nome(strtolower($bloco['nome'])) ?>">
                                            <div class="row collapse align-center paginas_bloco">
                                                <div class="column">
                                                    <div class="row collapse timeline"> 
                                                        <div class="small-12 column">
                                                            <div class="div_100">
                                                                <div class="slick-timeline full_height">
                                                                <?php foreach($row_rsTimeline as $timeline){ 
                                                                    
                                                                    $img = ROOTPATH_HTTP."imgs/elem/geral.svg";
                                                                    if($timeline['imagem1'] && file_exists(ROOTPATH.'imgs/paginas/'.$timeline['imagem1'])){
                                                                        $img = ROOTPATH_HTTP."imgs/paginas/".$timeline['imagem1'];
                                                                    }
                                                                    ?>
                                                                    <div class="div_100 timeline_divs">
                                                                        <div class="row full collapse" style="height: 100%">
                                                                            <div class="column small-12 medium-6 elements_animated left">
                                                                                <picture class="img has_bg" style="background-image:url('<?php echo $img; ?>'); height: 100%;">
                                                                                    <?php echo getFill('paginas', 3); ?>
                                                                                </picture>
                                                                            </div>
                                                                            <div class="column small-12 medium-6">
                                                                                <div class="info morphArea_content elements_animated right">
                                                                                    <h3 class="titulos"><?php echo $timeline['titulo']; ?></h3>
                                                                                    <div class="textos morphArea_watcher" data-class="timeline_info"><p><?php echo $timeline['texto']; ?></p></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                </div>
                                                            </div>
                                                            <ul class="slick-years has_cursor elements_animated">
                                                            <?php foreach($row_rsTimeline as $years){ ?>
                                                                <li><span title="<?php echo $years['titulo']; ?>"></span></li>
                                                            <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="div_100 paginas_bloco_margin" style="height: <?php echo $row_rsPagina['esp_blocos']; ?>px; min-height: <?php echo $row_rsPagina['esp_blocos_mob']; ?>px"></div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                
                            <?php } ?>  
                        <?php }else{ ?>
                            <div class="subtitulos text-center elements_animated top" style="padding-bottom: 5rem;"><?php echo $Recursos->Resources["disponivel_breve"]; ?></div>
                        <?php } ?>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php include_once('pages_footer.php'); ?>