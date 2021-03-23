<?php include_once('pages_head.php');

if($_GET['id']) $categoria = $_GET['id'];
if($cat_redirect) $categoria = $cat_redirect;
if($sub_redirect) $catmae = $sub_redirect;
if($subsub_redirect) $submae = $subsub_redirect;

if(!$submae) {
  $submae = arraySearch($GLOBALS['divs_categorias'], 'id', $categoria, "cat_mae");
}
if(!$catmae) {
  $catmae = arraySearch($GLOBALS['divs_categorias'], 'id', $submae, "cat_mae");
}
if($catmae == $submae) {
  $submae = 0;
}

// echo "GET: ".$categoria."<br>";
// echo "redirect: ".$cat_redirect."<br>";
// echo $categoria."<br>";
// echo $catmae."<br>";
// echo $submae."<br>";

$extra_link = "";
$cat_refresh = 0;
$arrayCategorias = array();

if($categoria > 0) {
  array_push($arrayCategorias, $GLOBALS['divs_categorias'][$categoria]);

  if((int)$catmae > 0) {
      $arrayCategorias[0] = $GLOBALS['divs_categorias'][$catmae];
  }
}
else { //preciso para ter o link = "promocoes"
  $cat_refresh = 1;
  $arrayCategorias = $GLOBALS['divs_categorias'];

  if($meta_id == 4) { // NOVIDADES
    $categoria = "-1";
    $extra_link = "?filtrar_por=1";
  }
  if($meta_id == 5) { // PROMOCOES
    $categoria = "-2";
    $extra_link = "?filtrar_por=2";
  }
}

$query_rsPromoTexto = "SELECT texto_listagem FROM l_promocoes_textos".$extensao." WHERE id = '1'";
$rsPromoTexto = DB::getInstance()->prepare($query_rsPromoTexto);
$rsPromoTexto->execute();
$row_rsPromoTexto = $rsPromoTexto->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPromoTexto = $rsPromoTexto->rowCount();
DB::close();

$menu_sel = "produtos";

?>
<main class="page-load produtos">
  <section class="div_100 categorias_banner" id="banners">

  </section>

  <nav class="breadcrumbs_cont product-nav" aria-label="You are here:" role="navigation">
    <div class="row">
      <div class="column">
        <ul class="breadcrumbs">
          <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
          <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="container">
    <div class="row listings <?php echo $menu_sel; ?>_list">
      <div class="column">
        <div class="div_100">
          <div class="div_table_cell listings_filters">
            <div class="div_100 listings_filters_bg">
              <div class="div_100 listings_filters_content to_sticky" id="fixedFilters">
                <div class="filtersHead hide-for-medium">
                  <div class="row collapse align-middle">
                    <div class="column">
                      <button class="close-button invert relative" aria-label="Close filters" role="button" type="button">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="column shrink">
                      <h4 class="list_subtit icon-filtro"><?php echo $Recursos->Resources["mostrar_filtros"]; ?></h4>
                    </div>
                    <div class="column text-right">
                      <a class="clearFilters textos" href="javascript:;" onclick="window.location = location.protocol+'//'+location.host+location.pathname"><?php echo $Recursos->Resources["limpar_filtrar"]; ?></a>
                    </div>
                  </div>
                </div>
                <div class="div_100 filtersBody">
                  <div class="scroller">
                    <?php $dd = 1; ?>
                    <?php if(!empty($GLOBALS['divs_categorias']) && $dd == 0) { ?>
                      <div id="filtersUrl" class="filters_divs" style="padding-top:0;">
                        <h3 class="subtitulos uppercase"><?php echo $Recursos->Resources["categorias"]; ?></h3>
                        <ul accordion accordion-icon="icon-down" accordion-clickToClose="false" accordion-allClosed="true">
                          <?php foreach($GLOBALS['divs_categorias'] as $cat) {
                            $subs = $cat['subs'];
                            if($cat['info']['nome']) {
                              $cat = $cat['info'];
                            }

                            $cats_el = " loja_inpt has-url";
                            if(empty($subs)) {
                              $cats_el = " loja_inpt has-url";
                            }
                            ?> 
                            <li accordion-item>
                              <a class="loja_inpt has-url type1 list_subtit<?php if($catmae == $cat['id'] || $categoria == $cat['id']) echo " active"; ?>" data-id="<?php echo $cat['id']; ?>" data-catmae="0" data-submae="0" data-url="<?php echo $cat['url'].$extra_link; ?>" href="<?php echo $cat['url'].$extra_link; ?>" accordion-title><?php echo $cat['nome']; ?></a>
                              <?php if(!empty($subs)) { ?>
                                <ul accordion-nested <?php if(($catmae == $cat['id'] || $categoria == $cat['id']) || count($GLOBALS['divs_categorias']) == 1) echo " content-visible"; ?>>
                                  <?php foreach($subs as $sub) {
                                    $subssubs = $sub['subs'];
                                    if($sub['info']['nome']) {
                                      $sub = $sub['info'];
                                    }
                                    ?> 
                                    <li accordion-item>
                                      <a class="filters loja_inpt has-url type2 list_txt <?php if($submae == $sub['id'] || $categoria == $sub['id']) echo " active"; ?>" href="javascript:;" accordion-title data-id="<?php echo $sub['id']; ?>" data-catmae="<?php echo $cat['id']; ?>" data-submae="0" name="sub" id="sub_<?php echo $sub['id']; ?>" data-url="<?php echo $sub['url']; ?>"><?php echo $sub['nome']; ?></a>
                                      <?php if(!empty($subssubs)) { ?>
                                        <ul accordion-nested <?php if($submae == $sub['id'] || $categoria == $sub['id']) echo " content-visible"; ?>>
                                          <?php foreach($subssubs as $subssub) { ?>
                                            <li accordion-item>
                                              <a class="filters" href="javascript:;" accordion-title>
                                                <input class="loja_inpt has-url type3 list_txt" type="checkbox" data-id="<?php echo $subssub['id']; ?>" data-catmae="<?php echo $cat['id']; ?>" data-submae="<?php echo $sub['id']; ?>" name="subssub" id="subssub_<?php echo $subssub['id']; ?>" data-url="<?php echo $subssub['url']; ?>" value="<?php echo $subssub['id']; ?>" <?php if($categoria==$subssub['id']) echo "checked"; ?> />
                                                <h5><?php echo $subssub["nome"]; ?></h5>
                                              </a>
                                            </li>
                                          <?php } ?>
                                        </ul>
                                      <?php } ?>
                                    </li>
                                  <?php } ?>
                                </ul>
                              <?php } ?>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    <?php } ?>
                    <input class="hidden" type="hidden" name="categoria" id="categoria" value="<?php echo $categoria; ?>"/>
                    <input class="hidden" type="hidden" name="catmae" id="catmae" value="<?php echo $catmae; ?>" />
                    <input class="hidden" type="hidden" name="submae" id="submae" value="<?php echo $submae; ?>" />

                    <div class="div_100">
                        <?php if(!empty($GLOBALS['divs_categorias'])) { ?>
                        <?php if (empty($categoria) && empty($catmae) && empty($submae)): ?>                 
                            <div class="filters_divs active input_check">
                                <h3 class="list_subtit mb-0 accordion-head active"><?php echo $Recursos->Resources["categorias"]; ?></h3>
                                <div class="cat-wrap accordion-content active mt-20"  native-window data-title="<?php echo $filt_cat["nome"]; ?>" > 
                                    <?php 
                                        foreach($GLOBALS['divs_categorias'] as $cats) {
                                            $subs = $cats['subs']; 
                                            if (!empty($subs)) { ?>
                                                <div class="div_100 mt-20">
                                                    <h2 class="sub-cate-head" style="margin-bottom:10px;"><?php echo $cats['info']['nome']; ?></h2>
                                                    <?php foreach($subs as $sbcat) { ?>
                                                        <?php 
                                                            $totalRows_rsCounterpr = 0;
                                                        $query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas WHERE pecas.categoria='".$sbcat['info']['id']."' AND pecas.visivel=1 GROUP BY pecas.id";
                                                            $rsCounterpr = DB::getInstance()->prepare($query_rsPr);
                                                            if(hasParameter($query_rsPr, ':categoria')) $rsCounterpr->bindParam(':categoria', $categoria, PDO::PARAM_INT);
                                                            $rsCounterpr->execute();
                                                            $row_rsCounterpr = $rsCounterpr->fetch(PDO::FETCH_ASSOC);
                                                            $totalRows_rsCounterpr = $rsCounterpr->rowCount();
                                                         ?>
                                                        <a class="filters" href="javascript:;">
                                                            <input class="loja_inpt has-url type3 list_txt" type="checkbox" data-name="categoria" name="categoria" id="categoria<?php echo $sbcat['info']['id']; ?>" data-id="<?php echo $sbcat['info']['id']; ?>"  data-catmae="<?php echo $cats['info']['id']; ?>" data-submae="<?php echo $sbcat['info']['id']; ?>" data-url="<?php echo $sbcat['info']['url']; ?>" value="<?php echo $sbcat['info']['id']; ?> ">
                                                            <h5 class="list_txt"><?php echo $sbcat['info']["nome"]; ?></h5><p class="list_txt"><?php echo "(".$totalRows_rsCounterpr.")"; ?></p>
                                                        </a>
                                                        <?php 
                                                        $subs_iner = $sbcat['subs'];
                                                        if (!empty($subs_iner)) { ?>
                                                          <div class="inner_cate">
                                                          <h2 style="margin-bottom:10px;"><?php echo $sbcat['info']['nome']; ?></h2>
                                                          <?php foreach ($subs_iner as $key => $cat): ?>

                                                            <?php 
                                                            $totalRows_rsCounterpr = 0;
                                                              $query_rsPr = "SELECT pecas.id FROM l_pecas".$extensao." AS pecas WHERE pecas.categoria='".$scat['id']."' AND pecas.visivel=1 GROUP BY pecas.id";
                                                              $rsCounterpr = DB::getInstance()->prepare($query_rsPr);
                                                              if(hasParameter($query_rsPr, ':categoria')) $rsCounterpr->bindParam(':categoria', $categoria, PDO::PARAM_INT);
                                                              $rsCounterpr->execute();
                                                              $row_rsCounterpr = $rsCounterpr->fetch(PDO::FETCH_ASSOC);
                                                              $totalRows_rsCounterpr = $rsCounterpr->rowCount();
                                                             ?>
                                                            <a class="filters" href="javascript:;">
                                                                <input class="loja_inpt type3" type="checkbox" data-name="categoria" name="categoria" id="categoria<?php echo $cat['id']; ?>" data-url="<?php echo $cat['url']; ?>" value="<?php echo $cat['id']; ?> ">
                                                              <h5 class="list_txt"><?php echo $cat["nome"]; ?></h5><p class="list_txt"><?php echo "(".$totalRows_rsCounterpr.")"; ?></p>
                                                            </a>
                                                          <?php endforeach ?>
                                                          </div>
                                                        <?php }
                                                    } ?>
                                                </div>     
                                            <?php
                                            }
                                        } 
                                    ?>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php } ?>
                    </div>
                    <div class="div_100" id="filtros_rpc">

                    </div>
                  </div>
                </div>  
                <div class="div_100 filtersFoot">
                  <!-- <a class="btnFilters button-big invert show-for-medium" href="javascript:;" onclick="window.location = location.protocol+'//'+location.host+location.pathname"><?php echo $Recursos->Resources["limpar_filtrar"]; ?></a> -->
                   <a class="btnFilters button-big invert show-for-medium" href="javascript:;" onclick="window.location = location.protocol+'//'+location.host+location.pathname"><?php echo $Recursos->Resources["limpar_filtrar"]; ?></a>
                  <a class="btnFilters button-big invert hide-for-medium" href="javascript:;"><?php echo $Recursos->Resources["filtrar"]; ?></a>
                </div>
              </div>
            </div>
          </div>
          <div id="sticked_filter" class="div_100 sticked_filter to_sticky hide-for-medium text-center">
            <button class="filtersToggle text-center" type="button">
              <span class="list_subtit icon-filter"><?php echo $Recursos->Resources["mostrar_filtros"]; ?></span>
            </button>
          </div>
          <div class="div_table_cell listings_container">
            <div class="listing-shorting-head row">
                <div class="half-wrap column small-12 medium-6 large-6">
                     <p> Showing all <span class="listing-count">0</span> results</p> 
                </div>  
                <div class="half-wrap column small-12 medium-6 large-6">
                  <p>Ordenar por:</p>
                  <?php if (!empty($_GET['order'])): 
                    $get_order = $_GET['order'];
                  else:
                    $get_order = 0; 
                  endif ?>
                  <select class="filter-select">>
                        <option value="0" <?php echo ($get_order == 0) ? 'selected="selected"' : null; ?>><?php echo $Recursos->Resources["relevance"]; ?></option>
                        <option value="1" <?php echo ($get_order == 1) ? 'selected="selected"' : null; ?>><?php echo $Recursos->Resources["mais_recente"]; ?></option>
                        <option value="2" <?php echo ($get_order == 2) ? 'selected="selected"' : null; ?>><?php echo $Recursos->Resources["mais_antigo"]; ?></option>
                        <option value="3" <?php echo ($get_order == 3) ? 'selected="selected"' : null; ?>><?php echo $Recursos->Resources["mais_barato"]; ?></option>
                        <option value="4" <?php echo ($get_order == 4) ? 'selected="selected"' : null; ?>><?php echo $Recursos->Resources["mais_caro"]; ?></option>
                  </select>
                </div>
            </div>
            <div class="listings_divs text-center">
              <div class="listing_mask">
                <div class="listing_loader to_sticky" id="listMask">
                  <div class="circle"></div>
                  <div class="line-mask">
                    <div class="line"></div>
                  </div>
                </div>
              </div>
              <?php if($row_rsPromoTexto['texto_listagem']) { ?>
                <div class="listagem_promocoes row collapse text-center">
                  <div class="column">
                    <div class="listagem_promocoes_txt">
                      <?php echo $row_rsPromoTexto['texto_listagem']; ?>
                    </div>
                  </div>
                </div>
              <?php } 
?>
              <div class="row collapse small-up-2 xsmall-up-2 xxsmall-up-3 text-center <?php echo ($_GET['marcas_page']==1) ? 'brand-active' : null; ?>" id="<?php echo $menu_sel; ?>">

              </div>
     
            </div>
          </div>
        </div>
      </div>
    </div>    
  </section>    

</main>
<?php include_once('pages_footer.php'); ?>