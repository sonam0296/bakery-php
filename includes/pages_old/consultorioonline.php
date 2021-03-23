<?php include_once('pages_head.php');

$query_rsImagem = "SELECT * FROM imagens_topo";
$rsImagem = DB::getInstance()->query($query_rsImagem);
$row_rsImagem = $rsImagem->fetch(PDO::FETCH_ASSOC);
$totalRows_rsImagem = $rsImagem->rowCount();
DB::close();

$faqs = [];
$faqs_cats = [];

geraSessions('faqs');

$categoria = key($GLOBALS['divs_faqs']);

if(!$GLOBALS['divs_faqs'][$categoria]['info']) {
    $faqs = $GLOBALS['divs_faqs'];
}
else {
    $faqs_cats = $GLOBALS['divs_faqs'];
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
                         <span><?php echo $Recursos->Resources["online_office"]; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="div_100 faqs_cont"> 
        <div class="row content">
            <div class="column small-12 medium-6 faqs_cont_row_tabs">
                <div class="faq-head-wrap">
                    <h2><?php echo $Recursos->Resources["online_office"]; ?></h2>
                </div>
                <div class="faq-content-wrap faq-content-wrap_data_tab_faq">
					<?php echo $row_rsContent['resposta']; ?>
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

            <div class="column small-12 medium-6 faq-small-padding-data">
                <div class="div_100 elements_animated bottom text-left" style="max-width: 768px; margin:auto">                    
                    <!-- <h1 class="contactos_tit titulos" titles><?php echo $Recursos->Resources["tit_contactos2"];?></h1> -->
                    <form action="" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="post" name="form_consultorioonline" id="form_contactos" novalidate autocomplete="off" nearby-validator>
                        <div class="animated_elements right"> 
                            
                            <div class="inpt_holder">
                                <label class="inpt_label" for="<?php echo $form_consultorioonline['nome']; ?>"><?php echo $Recursos->Resources["nome"];?> *</label><!--
                                --><input required class="inpt inpt_conto" type="text" id="<?php echo $form_consultorioonline['nome']; ?>" name="<?php echo $form_consultorioonline['nome']; ?>" autocomplete="name" />
                                <div class="inpt_error"></div>
                            </div>
                            
                            <div class="inpt_holder">
                                <label class="inpt_label" for="<?php echo $form_consultorioonline['email']; ?>"><?php echo $Recursos->Resources["mail"];?> *</label><!--
                                --><input required class="inpt inpt_conto" type="email" id="<?php echo $form_consultorioonline['email']; ?>" name="<?php echo $form_consultorioonline['email']; ?>" autocomplete="email"/>
                                <div class="inpt_error"></div>
                            </div>
                            
                            <div class="inline-input">

                                <div class="inpt_holder">
                                    <label class="inpt_label" for="<?php echo $form_consultorioonline['idade']; ?>"><?php echo $Recursos->Resources["idade"];?> *</label><input required class="inpt inpt_conto" type="text" id="<?php echo $form_consultorioonline['idade']; ?>" placeholder="Anos" name="<?php echo $form_consultorioonline['idade']; ?>" autocomplete="idade"/>
                                    <div class="inpt_error"></div>
                                </div>

                                <div class="inpt_holder">
                                    <label class="inpt_label" for="<?php echo $form_consultorioonline['peso_atual']; ?>"><?php echo $Recursos->Resources["peso_atual"];?> *</label><input required class="inpt inpt_conto"  type="text" id="<?php echo $form_consultorioonline['peso_atual']; ?>" placeholder="Kg" name="<?php echo $form_consultorioonline['peso_atual']; ?>" autocomplete="idade"/>
                                    <div class="inpt_error"></div>
                                </div>

                                <div class="inpt_holder">
                                    <label class="inpt_label" for="<?php echo $form_consultorioonline['altura']; ?>"><?php echo $Recursos->Resources["altura"];?> *</label>
                                    <input required class="inpt inpt_conto" type="text" id="<?php echo $form_consultorioonline['altura']; ?>" placeholder="Cm" name="<?php echo $form_consultorioonline['altura']; ?>"/>
                                    <div class="inpt_error"></div>
                                </div>

                            </div>

                            <div class="inpt_holder textarea">
                                <label class="inpt_label" for="<?php echo $form_contactos['mensagem']; ?>"><?php echo $Recursos->Resources["Message"];?> *</label><!--
                                --><textarea required class="inpt inpt_conto" id="<?php echo $form_contactos['mensagem']; ?>" name="<?php echo $form_contactos['mensagem']; ?>" style="height: 15rem !important;" autocomplete="message"></textarea>
                                <div class="inpt_error"></div>
                            </div>

                            <div class="inpt_holder simple " style="margin-bottom: 1rem;">
                                 <div class="inpt_checkbox">
                                    <input type="checkbox" required name="<?php echo $form_contactos['aceita_politica']; ?>" id="<?php echo $form_contactos['aceita_politica']; ?>" value="1" />
                                    <label for="<?php echo $form_contactos['aceita_politica']; ?>"><?php echo $Recursos->Resources["aceito_termos_reg"]; ?></label>
                                 </div>
                            </div>
                            
                            <?php if(CAPTCHA_KEY!=NULL){ ?>
                                <div class="inpt_holder simple no_marg">
                                    <div class="captcha" id="contactos_captcha" data-sitekey="<?php echo CAPTCHA_KEY; ?>" data-error="<?php echo $Recursos->Resources["preencha_captcha"]; ?>"></div>
                                </div>
                            <?php }else{ ?>
                                <div class="inpt_holder">
                                    <?php $cod1=rand(1,10); $cod2=rand(1,10); $cod3=$cod1+$cod2; ?>

                                    <label class="inpt_label" for="<?php echo $form_seguranca['cod_seg']; ?>"><?php echo $Recursos->Resources["seguranca"]; ?></label><!--
                                    --><input required type="text" class="inpt confirm" name="<?php echo $form_seguranca['cod_seg']; ?>" id="<?php echo $form_seguranca['cod_seg']; ?>" value="" placeholder="<?php echo $cod1." + ".$cod2." ="; ?>"/>
                                    <input type="hidden" class="cod_confirm" name="<?php echo $form_seguranca['cod_res']; ?>" id="<?php echo $form_seguranca['cod_res']; ?>" value="<?php echo $cod3; ?>"/>
                                </div>   
                            <?php } ?>

                            <button type="submit" class="button-big uppercase button-big-faqs-data"><?php echo $Recursos->Resources["enviar"];?></button>
                            
                            <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                            <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                            <input type="hidden" name="MM_insert" value="form_consultorioonline" />
                            <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                        </div>                
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include_once('pages_footer.php'); ?>