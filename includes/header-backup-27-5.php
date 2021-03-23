<?php
if(tableExists(DB::getInstance(), "clientes")) {
  $id_cliente = 0;

  if(!empty($row_rsCliente)) {
    $id_cliente = $row_rsCliente['id'];

    if(substr_count($row_rsCliente['nome'], ' ') >= 2) {
      $parts = explode(' ', $row_rsCliente['nome']);
      $firstname = array_shift($parts);
      $lastname = array_pop($parts);  
      $login_txt = $firstname." ".$lastname;
    }else {
      $login_txt = $row_rsCliente['nome'];
    }
  }
}

$nome_lingua = arraySearch($GLOBALS['divs_linguas'], "sufixo", $lang, "nome");
$nome_moeda = $moeda;

$lang_txt = "";
if($nome_moeda || $nome_lingua) {
  if($nome_lingua) {
    $lang_txt .= $nome_lingua;
  }
  if($nome_lingua && $nome_moeda) {
    $lang_txt .= " | ";
  }
  if($nome_moeda) {
    $lang_txt .= $nome_moeda;
  }
}

$row_rsContactos = $GLOBALS['divs_contactos'];
if($GLOBALS['divs_contactos']['info']["id"]) {
  $row_rsContactos = $GLOBALS['divs_contactos']['info'];
}
$paginasInfo = $GLOBALS['divs_paginas'];
$pagCookies = $paginasInfo[2]['info'];
$pagPolitica = $paginasInfo[3]['info'];
$pagRal = $paginasInfo[4]['info'];
$pagSobre = $paginasInfo[5]['info'];
$pagTermos = $paginasInfo[6]['info'];
/*$pagEntregas = $paginasInfo[9]['info'];
$pagMetodos = $paginasInfo[10]['info'];*/

?>
<header class="div_100 header">
  <div class="div_100 top show-for-medium">
    <div class="row align-middle">
      <!--LEFT TEXT-->
      <?php if($GLOBALS['divs_icons_services'][4]["texto_header"]){ ?>
        <div class="column small-6 text-left">
          <span class="txt_delivery"><?php echo $GLOBALS['divs_icons_services'][4]["texto_header"]; ?></span>
        </div>  
      <?php } ?>

       <!--Links-->
      <div class="column text-right">
        <a href="<?php echo get_meta_link(2); ?>" class="list_txt links"><?php echo $Recursos->Resources["contactos"]; ?></a><!--
        --><a href="<?php echo $pagSobre['url']; ?>" class="list_txt links"><?php echo $pagSobre['nome']; ?></a><!--
        --><a href="<?php echo get_meta_link(2); ?>" class="list_txt links"><?php echo $Recursos->Resources["orcamento"]; ?></a>
      </div>

      <!--CHANGE LANGUAGE-->
      <?php if(!empty($GLOBALS['divs_linguas']) && count(array_filter($GLOBALS['divs_linguas'])) > 1) { ?>
        <div class="column shrink">
          <div class="wrapper_linguas">
            <ul class="actions header_linguas dropdown menu" dropdown accordion-absolute accordion-icon="icon-down">
              <li accordion-item>
                <a href="javascript:;" class="nav-linguas icon-arrow-down" onclick="menuLinguas()" accordion-title><img class="lg-atual" src="<?php echo ROOTPATH_HTTP.'imgs/elem/'.$lang.'.svg'; ?>" ><?php echo $nome_lingua; ?></a>
                <ul class="menu" accordion-nested>
                  <?php foreach ($GLOBALS['divs_linguas'] as $lingua_cli) {
                    $linguas_link = ${'pagina_'.$lingua_cli['sufixo']}."lg=".$lingua_cli['id'];
                    $nome_ling = $lingua_cli['nome'];
                    if ($extensao!="_".$lingua_cli['sufixo']) { ?>
                      <li accordion-item><a class="nav-linguas" href="<?php echo $linguas_link; ?>" accordion-title><img src="<?php echo ROOTPATH_HTTP.'imgs/elem/'.$lingua_cli['sufixo'].'.svg'; ?>"><?php echo $lingua_cli['nome']?></a></li>
                    <?php }
                  } ?>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="div_100 middle">
    <div class="row align-middle" style="position: static;">

      <div class="column hide-for-medium">
        <a href="javascript:;" class="menu_holder icons nav-trigger"><span></span></a>
      </div>

      <div class="column show-for-medium">
        <div class="header_help">
          <a href="tel:<?php echo $row_rsContactos['telefone']; ?>" class="uppercase"><span><?php echo $Recursos->Resources["txt_help"]; ?></span><?php echo $row_rsContactos['telefone']; ?></a>
        </div>
      </div>

      <div class="column shrink medium-expand medium-text-center">
        <a href="<?php echo get_meta_link(1); ?>" class="logo" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false">
          <img src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/logo.svg" width="100%" />
        </a>
      </div>

       <!--LOGIN-->
      <div class="column text-right">
        <?php if(ECOMMERCE_ATIVO == 1) { ?>
        <div class="login_header show-for-medium">
          <?php if($row_rsCliente != 0) { ?>
            <a href="<?php echo ROOTPATH_HTTP_LANG; ?>logout.php" class="logout"><?php echo file_get_contents(ROOTPATH."imgs/elem/logout.svg"); ?></a><!-- 
            --><span class="textos separator">|</span><!--
            --><a href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada.php" class="<?php if($menu_sel=="area-reservada") echo " sel"; ?>"><?php echo $Recursos->Resources["bem_vindo"]." ".$login_txt; ?></a>
          <?php } else { ?>
              <a href="<?php echo ROOTPATH_HTTP_LANG; ?>login.php" class="<?php if($menu_sel=="login") echo " sel"; ?>"><?php echo $Recursos->Resources["login"]; ?></a><!-- 
            --><span class="textos separator">/</span><!--
            --><a href="<?php echo ROOTPATH_HTTP_LANG; ?>login.php?anchor=form_regista" class="<?php if($menu_sel=="registo") echo " sel"; ?>"><?php echo $Recursos->Resources["criar_registo"]; ?></a>
          <?php } ?>
        </div>
      <?php } ?>
      <a class="fav_btn links_top text-center show-for-medium" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-favoritos.php" id="favs">
        <i class="icon-favorite">
          <span class="count"><small>0</small></span>
        </i>
      </a>
        <a href="<?php echo ROOTPATH_HTTP_LANG; ?>carrinho.php" class="cart-btn text-center" data-sel="carrinho"> 
          <i class="icon-bag"></i>
          <span class="count"><small>0</small></span>
        </a>
      </div>
    </div>
  </div>
  <div class="div_100 bottom show-for-medium to_sticky head_menu" id="header">
    <div class="row collapse full align-middle">
      
      <!-- SEARCH SECTION ____ DELETE WHAT YOU DONT USE-->
      <a href="javascript:;" class="column shrink search-btn search-trigger actions">
        <span class="icon-search"></span>
      </a>

      <?php if(ECOMMERCE_ATIVO == 1) { ?>
      <form class="column shrink search_form_head" action="<?php echo ROOTPATH_HTTP_LANG; ?>loja" method="get" autocomplete="off" novalidate id="pesq_form" name="pesq_form" onsubmit="return validaForm('pesq_form')">
        <div class="inpt_holder no_marg simple icon-pesq">
          <input class="inpt" name="pesq" type="pesq" value="<?php echo $_GET['pesq']; ?>" placeholder="<?php echo $Recursos->Resources["pesquisa_msg"]; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />        
          <button type="submit" class="icon-search"></button>
        </div>
      </form>
      <?php }else{ ?>
      <form class="column shrink search_form" action="<?php echo ROOTPATH_HTTP_LANG; ?>pesquisa.php" method="get" autocomplete="off" novalidate id="pesq_form" name="pesq_form" onsubmit="return validaForm('pesq_form')">
        <div class="inpt_holder no_marg simple icon-pesq">
          <input class="inpt" name="search" type="search" placeholder="<?php echo $Recursos->Resources["pesquisa_msg"]; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />   
          <button type="submit" class="icon-search"></button>
        </div>
      </form> 
      <?php } ?>
      <!-- SEARCH SECTION ____ DELETE WHAT YOU DONT USE-->

      <ul  class="column navbar-nav text-center">
        <li class="menu-item show-for-medium">
          <a href="<?php echo ROOTPATH_HTTP_LANG; ?>" class="nav-link uppercase"><?php echo $Recursos->Resources["home"]; ?></a>
        </li><!--
      --><li class="menu-item show-for-medium">
          <a href="loja" class="nav-link promo uppercase"><?php echo $Recursos->Resources["promocoes"]; ?></a>
        </li><!--
        --><?php if(!empty($GLOBALS['divs_categorias'])){ 
          foreach($GLOBALS['divs_categorias'] as $cats) {
            $subs_cats = $cats['subs'];
            if($cats['info']){
              $cats = $cats['info'];
            } ?><!--
            --><li class="menu-item show-for-medium">
              <a href="<?php echo ROOTPATH_HTTP_LANG.$cats['url']; ?>" class="nav-link uppercase <?php if($cat_redirect == $cats['id'] || $sub_redirect == $cats['id']) echo ' active';?> <?php if(!empty($subs_cats)) echo "header_drop"; ?>" data-id="<?php echo $cats['id']; ?>"><?php echo $cats["nome"]; ?></a>
              </li><!--
            --><?php
          }
        } ?>
      </ul>
    </div>
  </div>

  <div class="menu_desktop header_menu_drop">
        
  </div>
</header>


<?php /* if ECOMMERCE == 0  ?>
<form class="search_form" action="<?php echo ROOTPATH_HTTP_LANG; ?>pesquisa.php" method="get" autocomplete="off" novalidate id="pesq_form" name="pesq_form" onsubmit="return validaForm('pesq_form')">
  <div class="inpt_holder no_marg simple icon-pesq">
    <input class="inpt" name="search" type="search" placeholder="<?php echo $Recursos->Resources["pesquisa_msg"]; ?>..." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />    
    <button type="submit" class="icon-search"></button>
  </div>
</form> 

<?php /* if ECOMMERCE == 1  ?>
<form class="search_form_head" action="<?php echo ROOTPATH_HTTP_LANG; ?>loja" method="get" autocomplete="off" novalidate id="pesq_form" name="pesq_form" onsubmit="return validaForm('pesq_form')">
  <div class="inpt_holder no_marg simple icon-pesq">
    <input class="inpt" name="pesq" type="pesq" value="<?php echo $_GET['pesq']; ?>" placeholder="<?php echo $Recursos->Resources["pesquisa_msg"]; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />        
    <button type="submit" class="icon-search"></button>
  </div>
</form>
<?php /* In dev delete comments and other form! :D */?>
            
<?php if(PESQ_TYPE == 1) {?>
  <div class="search">
    <button id="btn-search-close" class="close-button btn-search-close" onclick="closeSearch();" aria-label="Close search form">&times;</button>
    <div class="div_100 search_inner search_inner-up">
      <form class="search_form" action="<?php echo ROOTPATH_HTTP_LANG; ?>pesquisa.php" method="get" autocomplete="off" novalidate id="pesq_form" name="pesq_form" onsubmit="return validaForm('pesq_form')">
        <input class="search_input" name="search" type="search" placeholder="<?php echo $Recursos->Resources["pesquisa_msg"]; ?>..." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
        <span class="search_info"><?php echo $Recursos->Resources["pesquisa_txt"]; ?></span>
      </form>
    </div>
    <div class="div_100 search_inner search_inner-down"></div>
  </div>
<?php } ?>

<nav id="menu" class="menu_mobile left-side">
  <div class="menu_mobile_scroller">
    <?php if(ECOMMERCE_ATIVO == 1) { ?>
      <div class="div_100 section">
        <?php if($row_rsCliente == 0) { ?>
          <a href="<?php echo ROOTPATH_HTTP_LANG; ?>login.php" class="textos"><?php echo $Recursos->Resources["iniciar_sessao"]; ?></a>
        <?php } else { ?>
          <a href="<?php echo ROOTPATH_HTTP_LANG; ?>logout.php" class="textos"><?php echo $Recursos->Resources["logout"]; ?></a>
        <?php } ?>
      </div>
    <?php } ?> 
    <?php if(!empty($GLOBALS['divs_categorias'])){ ?>
      <div class="div_100 section">
        <span class="textos"><?php echo $Recursos->Resources["loja_online"]; ?></span>
        <ul>
          <li class="<?php if($file_to_include == 'produtos.php' && $cat_redirect == 0) echo ' is-active';?>"><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG;?>loja"><?php echo $Recursos->Resources["ver_todos"]; ?></a></li>
          <?php foreach($GLOBALS['divs_categorias'] as $cats) {
            $subs_cats = $cats['subs'];
            if($cats['info']){
              $cats = $cats['info'];
            }
            ?>
            <li class="<?php if(!empty($subs_cats)) echo 'has-sub'; ?><?php if($cat_redirect == $cats['id'] || $sub_redirect == $cats['id']) echo ' is-active';?>">
              <a class="list_txt<?php if($cat_redirect == $cats['id'] || $sub_redirect == $cats['id']) echo ' active';?>" href="<?php if(!empty($subs_cats)) echo 'javascript:;'; else echo ROOTPATH_HTTP_LANG.$cats['url']; ?>"><?php echo $cats["nome"]; ?></a>
              <?php if(!empty($subs_cats)) { ?>
                <ul<?php if($cat_redirect == $cats['id'] || $sub_redirect == $cats['id']) echo ' style="display:block;"'; ?>>
                  <?php foreach($subs_cats as $subs) { 
                    $subssubs = $subs['subs'];
                    if($subs['info']) {
                      $subs = $subs['info'];
                    }
                    ?>
                    <?php if(!empty($subssubs)) { ?> 
                      <li class="has-sub <?php if($cat_redirect == $subs['id'] || $subsub_redirect == $subs['id']) echo ' is-active'; ?>">
                        <a class="list_txt" href="javascript:;"><?php echo $subs["nome"]; ?></a>
                        <ul style="padding-left: 10px; <?php if($cat_redirect == $subs['id'] || $subsub_redirect == $subs['id']) echo 'display: block;'; ?>">
                          <?php foreach($subssubs as $subssubs) { ?>
                            <li class="<?php if($cat_redirect == $subssubs['id']) echo ' is-active';?>"><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG.$subssubs['url']; ?>"><?php echo $subssubs["nome"]; ?></a></li>
                          <?php } ?>
                          <li><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG.$subs["url"]; ?>"><?php echo $Recursos->Resources["ver_todos"]; ?></a></li>
                        </ul>
                      </li>
                    <?php } else { ?>
                      <li class="<?php if($cat_redirect == $subs['id']) echo ' is-active';?>"><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG.$subs['url']; ?>"><?php echo $subs["nome"]; ?></a></li>
                    <?php } ?>  
                  <?php } ?>
                  <li><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG.$cats['url']; ?>"><?php echo $Recursos->Resources["ver_todos"]; ?></a></li>
                </ul>
              <?php } ?>
            </li>
          <?php } ?>
          <li><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG; ?>novidades"><?php echo $Recursos->Resources["novidades"]; ?></a></li>
          <li><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG; ?>promocoes"><?php echo $Recursos->Resources["promocoes"]; ?></a></li>
        </ul>
      </div>
    <?php } ?>
    <?php if(ECOMMERCE_ATIVO == 1) { ?>
      <div class="div_100 section">
        <span class="textos"><?php echo $Recursos->Resources["minha_area"]; ?></span>
        <ul>
          <?php if($row_rsCliente != 0) { ?>
            <li><a class="list_txt<?php if($menu_sel_area == "entrada") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada.php"><?php echo $Recursos->Resources["area_reservada"]; ?></a></li>
            <li><a class="list_txt<?php if($menu_sel_area == "encomendas") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-encomendas.php"><?php echo $Recursos->Resources["minhas_encomendas"]; ?></a></li>
            <li><a class="list_txt<?php if($menu_sel_area == "dados") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-dados.php"><?php echo $Recursos->Resources["meus_dados"]; ?></a></li>
            <?php if(tableExists(DB::getInstance(), 'lista_desejo')) { ?>
              <li><a class="list_txt<?php if($menu_sel_area == "favoritos") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-favoritos.php"><?php echo $Recursos->Resources["meus_favoritos"]; ?></a></li>
            <?php } ?>
            <li><a class="list_txt<?php if($menu_sel_area == "tickets") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-tickets.php"><?php echo $Recursos->Resources["meus_tickets"]; ?></a></li>
            <?php if(CARRINHO_CONVIDAR == 1) { ?>
              <li><a class="list_txt<?php if($menu_sel_area == "convidar") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-convidar.php"><?php echo $Recursos->Resources["meus_amigos"]; ?></a></li>
            <?php } ?>
            <li><a class="list_txt" href="<?php echo ROOTPATH_HTTP_LANG; ?>logout.php"><?php echo $Recursos->Resources["logout"]; ?></a></li>
          <?php } else { ?>                            
            <li><a class="list_txt<?php if($menu_sel == 'login') echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>login.php"><?php echo $Recursos->Resources["iniciar_sessao"]; ?></a></li>
            <li><a class="list_txt<?php if($menu_sel == 'registo') echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>login.php?reg=1"><?php echo $Recursos->Resources["criar_registo"]; ?></a></li>
            <?php if(CARRINHO_CONVIDAR == 1) { ?>
              <li><a class="list_txt<?php if($menu_sel == $pagConvidar['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagConvidar['url']; ?>"><?php echo $pagConvidar['nome']; ?></a></li>
            <?php } ?>
          <?php } ?>
          <?php if($row_rsCliente == 0) { ?>
            <li><a class="list_txt<?php if($menu_sel_area == "favoritos") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>area-reservada-favoritos.php"><?php echo $Recursos->Resources["meus_favoritos"]; ?></a></li>
          <?php } ?>
          <li><a class="list_txt<?php if($menu_sel == 'carrinho') echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG; ?>carrinho.php"><?php echo $Recursos->Resources["carrinho"]; ?></a></li>
        </ul>
      </div>
    <?php } ?> 
    <div class="div_100 section">
      <span class="textos"><?php echo $Recursos->Resources["o_projeto"]; ?></span>
      <ul>
        <li><a class="list_txt <?php if($menu_sel == $pagSobre['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagSobre['url']; ?>"><?php echo $pagSobre['nome']; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel == "contactos") echo ' active'; ?>" href="<?php echo get_meta_link(2); ?>"><?php echo $Recursos->Resources["tit_contactos2"]; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel == "blog") echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_BLOG_LANG; ?>"><?php echo $Recursos->Resources["blog"]; ?></a> </li>
      </ul>
    </div>
    <div class="div_100 section">
      <span class="textos"><?php echo $Recursos->Resources["mais_info"]; ?></span>
      <ul>
        <li><a class="list_txt <?php if($menu_sel=="faqs") echo ' active'; ?>" href="<?php echo get_meta_link(5); ?>"><?php echo $Recursos->Resources["faqs"]; ?></a> </li>
        <li><a class="list_txt <?php if($menu_sel==$pagPolitica['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagPolitica['url']; ?>"><?php echo $pagPolitica['nome']; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel==$pagTermos['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagTermos['url']; ?>"><?php echo $pagTermos['nome']; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel==$pagMetodos['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagMetodos['url']; ?>"><?php echo $pagMetodos['nome']; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel==$pagEntregas['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagEntregas['url']; ?>"><?php echo $pagEntregas['nome']; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel==$pagRal['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagRal['url']; ?>"><?php echo $pagRal['nome']; ?></a></li>
        <li><a class="list_txt <?php if($menu_sel==$pagCookies['url']) echo ' active'; ?>" href="<?php echo ROOTPATH_HTTP_LANG.$pagCookies['url']; ?>"><?php echo $pagCookies['nome']; ?></a></li>
      </ul>
    </div>
    <?php if(!empty($GLOBALS['divs_redes'])) { ?>
      <div class="div_100 section redes">
        <?php foreach($GLOBALS['divs_redes'] as $redes) { ?>
          <div class="div_table_cell">
            <a class="share-<?php echo strtolower($redes['nome']); ?>" href="<?php echo $redes['link']; ?>" target="_blank"></a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
</nav>