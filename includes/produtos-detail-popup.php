<?php  include_once("pages/pages_head.php");

if($cat_redirect) {
  $categoria = $cat_redirect;
}
if($sub_redirect) {
  $catmae = $sub_redirect;
}
if($subsub_redirect) {
  $submae = $subsub_redirect;
}

if(!$submae) {
  $submae = arraySearch($GLOBALS['divs_categorias'], 'id', $categoria, "cat_mae");
}
if(!$catmae) {
  $catmae = arraySearch($GLOBALS['divs_categorias'], 'id', $submae, "cat_mae");
}
if($catmae == $submae) {
  $submae = 0;
}

if($produto_redirect) {
  $produto = $produto_redirect;
}

if($_POST["product_detail_id"]) {
  $produto = $_POST["product_detail_id"];
}

$id_cliente = $row_rsCliente['id'];
//$product_detail_id = $_POST["product_detail_id"];

$query_rsProduto = "SELECT * FROM l_pecas".$extensao." WHERE id = :id";
$rsProduto = DB::getInstance()->prepare($query_rsProduto);
$rsProduto->bindParam(':id', $produto, PDO::PARAM_INT); 
$rsProduto->execute();
$row_rsProduto = $rsProduto->fetch(PDO::FETCH_ASSOC);
$totalRows_rsProduto = $rsProduto->rowCount();

$query_rsImgs = "SELECT * FROM l_pecas_imagens WHERE id_peca = :id AND visivel = 1 ORDER BY ordem ASC, id DESC";
$rsImgs = DB::getInstance()->prepare($query_rsImgs);
$rsImgs->bindParam(':id', $produto, PDO::PARAM_INT); 
$rsImgs->execute();
$row_rsImgs = $rsImgs->fetchAll();
$totalRows_rsImgs = $rsImgs->rowCount();


$row_rsCatMae = array();
$row_rsSubMae = array();
$row_rsCategoria = array();

if($catmae > 0 && $submae == 0) {
  $row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['info'];
  $row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$categoria]['info'];
}
else if($catmae > 0 && $submae > 0) {    
  $row_rsCatMae = $GLOBALS['divs_categorias'][$catmae]['info'];
  $row_rsSubMae = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['info'];
  $row_rsCategoria = $GLOBALS['divs_categorias'][$catmae]['subs'][$submae]['subs'][$categoria];
}

if(tableExists(DB::getInstance(), 'l_marcas_pt') && ECC_MARCAS == 1 && $row_rsProduto['marca'] > 0) {
  $query_rsMarca = "SELECT * FROM l_marcas".$extensao." WHERE id = '$row_rsProduto[marca]'";
  $rsMarca = DB::getInstance()->query($query_rsMarca);
  $row_rsMarca = $rsMarca->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsMarca = $rsMarca->rowCount();
}

$query_rsRelacionados = "SELECT pecas.* FROM l_pecas_relacao AS relacao, l_pecas".$extensao." AS pecas WHERE pecas.id = relacao.id_relacao AND relacao.id_peca = :id AND pecas.visivel='1' ORDER BY rand() LIMIT 25";
$rsRelacionados = DB::getInstance()->prepare($query_rsRelacionados);
$rsRelacionados->bindParam(':id', $produto, PDO::PARAM_INT, 5); 
$rsRelacionados->execute();
$row_rsRelacionados = $rsRelacionados->fetchAll();
$totalRows_rsRelacionados = $rsRelacionados->rowCount();

//Carrega produtos associados às mesmas áreas se não tiver relacionados
if($totalRows_rsRelacionados == 0) { 
  if(CATEGORIAS == 2) {
    $query_rsRelacionados = "SELECT pecas.* FROM l_pecas".$extensao." AS pecas LEFT JOIN l_pecas_categorias AS pecas_cat ON pecas.id = pecas_cat.id_peca WHERE pecas_cat.id_categoria = '$categoria' AND pecas.id != '$produto' AND pecas.visivel='1' ORDER BY rand() LIMIT 25";
  }
  else {
    $query_rsRelacionados = "SELECT pecas.* FROM l_pecas".$extensao." AS pecas WHERE pecas.categoria = '$categoria' AND pecas.id != '$produto' AND pecas.visivel='1' ORDER BY rand() LIMIT 25";
  }
  $rsRelacionados = DB::getInstance()->query($query_rsRelacionados);
  $row_rsRelacionados = $rsRelacionados->fetchAll();
  $totalRows_rsRelacionados = $rsRelacionados->rowCount();   
}

if(CATEGORIAS == 1) {
  $query_rsTotal = "SELECT peca.id, peca.url, peca.imagem2, peca.nome FROM l_pecas".$extensao." AS peca WHERE peca.visivel = '1' AND peca.categoria = '$categoria' GROUP BY peca.id ORDER BY peca.ordem ASC, peca.id DESC";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
}
else if(CATEGORIAS == 2) {
  $query_rsTotal = "SELECT peca.id, peca.url, peca.imagem2, peca.nome FROM l_pecas".$extensao." AS peca LEFT JOIN l_pecas_categorias AS pca_cat ON pca_cat.id_peca = peca.id LEFT JOIN l_categorias".$extensao." AS cat1 ON cat1.id = pca_cat.id_categoria LEFT JOIN l_categorias".$extensao." AS cat2 ON cat2.id = cat1.cat_mae WHERE peca.visivel = '1' AND (cat1.id = :categoria OR cat2.id = :categoria OR cat2.cat_mae = :categoria) GROUP BY peca.id ORDER BY peca.ordem ASC, peca.id DESC";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->bindParam(':categoria', $categoria, PDO::PARAM_INT, 5); 
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
}

$prod_ant = "";
$prod_ant_img = "";
$prod_ant_nome = "";
$prod_seg = "";
$prod_seg_img = "";
$prod_seg_nome = "";
$encontrado = 0;
$conta_reg = 0;

if($totalRows_rsTotal > 1) {
  while($row_rsTotal = $rsTotal->fetch()) { 
    $registo_actual++;
    
    if($encontrado == 1) {
      $prod_seg = $row_rsTotal['url'];
      $prod_seg_id = $row_rsTotal['id'];
      $prod_seg_nome = $row_rsTotal['nome'];
      break;          
    }
    
    if($row_rsTotal['id'] != $produto && $encontrado == 0) {
      $prod_ant = $row_rsTotal['url'];
      $prod_ant_id = $row_rsTotal['id'];
      $prod_ant_nome = $row_rsTotal['nome'];
    } 
    else if($row_rsTotal['id'] == $produto) {
      $encontrado = 1;
    }
  }
}
else {
  $registo_actual++;
}

if (!empty($row_rsProduto['categoria'])) {
    $pro_cate = $row_rsProduto['categoria'];
    $query_rsMain_cate = "SELECT * FROM l_categorias".$extensao." WHERE id = '$pro_cate'";
    $rsMain_cate = DB::getInstance()->query($query_rsMain_cate);
    $row_rsMain_cate = $rsMain_cate->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsMain_cate = $rsMain_cate->rowCount();

    if (!empty($row_rsMain_cate)) {
        $parent_cate_id = $row_rsMain_cate['cat_mae'];
        $query_rsParent_cate = "SELECT * FROM l_categorias".$extensao." WHERE id = '$parent_cate_id'";
        $rsParent_cate = DB::getInstance()->query($query_rsParent_cate);
        $row_rsParent_cate = $rsParent_cate->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsParent_cate = $rsParent_cate->rowCount();
    }

    if (!empty($row_rsMain_cate)) {
        $parent_cate_id = $row_rsParent_cate['cat_mae'];
        $query_rsParent_main_cate = "SELECT * FROM l_categorias".$extensao." WHERE id = '$parent_cate_id'";
        $rsParent_main_cate = DB::getInstance()->query($query_rsParent_main_cate);
        $row_rsParent_main_cate = $rsParent_main_cate->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsParent_main_cate = $rsParent_main_cate->rowCount();
    }

    // echo "<pre>";
    // print_r ($row_rsMain_cate);
    // print_r ($row_rsParent_cate);
    // print_r ($row_rsParent_main_cate);
    // echo "</pre>";
}


//Array com os detalhes da promocao do produto (se tiver)
//Pos. 0: Datas
//Pos. 1: Título
//Pos. 2: Texto
//Pos. 3: Página
$array_promocao = $class_produtos->promocaoProduto($row_rsProduto['id']);

$meta_og = 1;
$share_img = ROOTPATH_HTTP."imgs/produtos/".$row_rsProduto['imagem1'];

$menu_sel = "produtos";
$menu_sel2 = "detalhe";
?>
<main class="div_100 product-detail detalhe_container"> 
  <div class="div_100 info_container">
    <div class="row align-center">
      <div class="small-12 xsmall-6 xxsmall-5 medium-5 xmedium-5 column product-media-cls"> 
        <?php /*<div class="div_100 detalhe_info hide-for-xxsmall hide-mobile">
          <h1><?php echo $row_rsProduto['nome']; ?></h1>
          <h3>Ref. <?php echo $row_rsProduto['ref']; ?></h3>
          <?php if($row_rsMarca['nome']) { ?>
            <h3><strong><?php echo $Recursos->Resources['marca']; ?></strong> <?php echo $row_rsMarca['nome']; ?></h3>
          <?php } ?>
        </div> */ ?>
        <?php if (!empty($row_rsProduto['marca'])): ?>
          <div class="productBrand hide-for-xxsmall show-mobile">
            <?php $produtomarca = $class_produtos->get_brand($row_rsProduto['marca']); 
            $new_array = array();
            foreach ($produtomarca as $key => $value) {
              $new_array[] = $value;
            } ?>
            <a href="<?php echo $produtomarca[7]; ?>" title="">
              <?php if (!empty($new_array[3])): ?>
                <?php $produtomarca_image = ROOTPATH_HTTP."imgs/marcas/".$produtomarca[3]; ?> 
              <?php else: ?>
                <?php $produtomarca_image = ROOTPATH_HTTP."imgs/elem/geral.svg"; ?>
              <?php endif ?>
              <img width="80" src="<?php echo $produtomarca_image; ?>" alt="<?php echo $produtomarca[1]; ?>">         
            </a>
          </div>
        <?php endif ?>
        <div class="product-detail-gallery" style="display: none;">
          
      
            <div id="div_imagem" class="div_100 product-detail-img">
              <?php //echo $class_produtos->precoProduto($row_rsProduto['id']); ?>
              <?php echo $class_produtos->labelsProduto($row_rsProduto['id'], 2, 'listagem'); ?>
              <?php if($totalRows_rsImgs > 0) { ?>  
                <div class="slick-imgs" style="display:block;">
                  <?php foreach($row_rsImgs as $imgs) {
                    if( $imgs['tipo'] == 2 )
                    {
                      ?>
                        <a data-id="<?php echo $imgs['id']; ?>" data-tamanho="<?php echo $imgs['id_tamanho']; ?>" href="#" class="item has_bg contain">
                        <?php //echo getFill('produtos', 2); ?>
                        <iframe width="331" height="350" src="<?php echo $imgs['video']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                      </a>
                      <?php
                    }
                    else
                    {
                      $img_gr =  $img = ROOTPATH_HTTP."imgs/elem/geral.jpg"; 
                      if($imgs['imagem1'] && file_exists(ROOTPATH."imgs/produtos/".$imgs['imagem1'])) {
                        $img_gr = ROOTPATH_HTTP."imgs/produtos/".$imgs['imagem1']; 
                      }
                      if($imgs['imagem2'] && file_exists(ROOTPATH."imgs/produtos/".$imgs['imagem2'])) {
                        $img = ROOTPATH_HTTP."imgs/produtos/".$imgs['imagem2']; 
                      } ?>

                      <a data-id="<?php echo $imgs['id']; ?>" data-tamanho="<?php echo $imgs['id_tamanho']; ?>" href="<?php echo $img_gr; ?>" class="item has_bg contain" style="background-image:url('<?php echo $img; ?>');">
                      <?php //echo getFill('produtos', 2); ?>
                      <img src="<?php echo $img_gr; ?>" alt="">
                    </a>
                    
                    <?php }
                    ?>
                    
                  <?php } ?> 
                </div>
              <?php } else { 
                $img_gr = $img = ROOTPATH_HTTP."imgs/elem/geral.jpg";

                if( $imgs['tipo'] == 2 )
                {
                  $img_gr = $img = ROOTPATH_HTTP."imgs/elem/youtube.png";
                }

                ?>
                <a class="div_100 item has_bg contain slick-current" href="<?php echo $img_gr; ?>"  style="background-image:url('<?php echo $img; ?>');">
                  <?php echo getFill('produtos', 2); ?>
                </a>
              <?php } ?>
              <?php if($totalRows_rsImgs > 0) { ?>
                <div class="icon-zoom show-for-medium" onclick="$('.item:eq(0)').click();"></div>
              <?php } ?>
            </div>
            <div class="div_100 detalhe_thumbs show-for-medium">
              <?php if($totalRows_rsImgs > 0) { ?>
              <div class="slick-thumbs" style="display:block;">
                <?php foreach($row_rsImgs as $imgs) {
                 $img = ROOTPATH_HTTP."imgs/elem/geral.jpg"; 
                if( $imgs['tipo'] == 2 )
                {
                  $img_gr = $img = ROOTPATH_HTTP."imgs/elem/youtube.png";
                }
                  if($imgs['imagem2'] && file_exists(ROOTPATH."imgs/produtos/".$imgs['imagem4'])) {
                    $img = ROOTPATH_HTTP."imgs/produtos/".$imgs['imagem4']; 
                  }
                ?>
                <div class="thumbs has_bg contain" style="background-image:url('<?php echo $img; ?>');">
                  <?php echo getFill('produtos', 4); ?>
                </div>
                <?php } ?>
              </div>
              <button class="arrows_slick produtos_arrows show_arrows prev" aria-label="Prev Arrow" role="button">
                <span class="icon-left"></span>
              </button>       
              <button class="arrows_slick produtos_arrows show_arrows next" aria-label="Next Arrow" role="button">
                <span class="icon-right"></span>
              </button>
            <?php } ?>
            </div>
        </div>
        <div class="detail_slider_area">
          <div class="detail_slider">
            <?php foreach($row_rsImgs as $imgs) {
                if( $imgs['tipo'] == 2 )
                {
                  $img_gr = $img = ROOTPATH_HTTP."imgs/elem/youtube.png";
                }
                  if($imgs['imagem2'] && file_exists(ROOTPATH."imgs/produtos/".$imgs['imagem4'])) {
                    $img = ROOTPATH_HTTP."imgs/produtos/".$imgs['imagem1']; 
                  }
                ?>
            <div class="detail_slide_box">
              <figure>
                <img src="<?php echo $img; ?>" alt="">
                 <?php echo getFill('produtos', 4); ?>
              </figure>
            </div><!-- /detail_slide_box -->
          <?php } ?>
          </div><!-- /detail_slider -->

          <div class="detail_thumb">
            <?php foreach($row_rsImgs as $imgs) {


                    if( $imgs['tipo'] == 2 )
                    {
                      ?>
                        <a data-id="<?php echo $imgs['id']; ?>" data-tamanho="<?php echo $imgs['id_tamanho']; ?>" href="#" class="item has_bg contain">
                        <?php //echo getFill('produtos', 2); ?>
                        <iframe width="331" height="350" src="<?php echo $imgs['video']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                      </a>
                      <?php
                    }
                    else
                    {

                      if($imgs['imagem1'] && file_exists(ROOTPATH."imgs/produtos/".$imgs['imagem1'])) {
                        $img_gr = ROOTPATH_HTTP."imgs/produtos/".$imgs['imagem1']; 
                      
                       ?>      
                  <div class="detail_thumb_box">
                    <figure>
                      <img src="<?php echo $img_gr; ?>" alt="">
                    </figure>
                  </div><!-- /detail_thumb_box -->
          <?php } 
              } 
            }?>
          </div><!-- /detail_slider -->
        </div><!-- /detail_slider_area -->
      </div> <?php $popop_addto = 'popop_add_to_cart'; ?>    
      <?php $detalhe = 1; include_once(ROOTPATH.'includes/produtos-info.php'); ?>                         
    </div> 
  </div>
  
      </div>
  </div>

  <?php if($totalRows_rsCombina > 0) { ?>
    <!-- <div class="div_100 relacionados_cont" id="combina">
      <div class="row">
        <div class="column">
          <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources['prods_combina']; ?></h3></div>
          <div class="listagem">
            <div class="slick-combina has_cursor">
            <?php foreach($row_rsCombina as $combina) { 
              echo $class_produtos->divsProduto($combina, '');
            } ?> 
            </div>
          </div>
        </div>
      </div>
    </div> -->
  <?php } ?>
    
  <?php if($totalRows_rsRelacionados > 0) { ?>
    <div class="div_100 relacionados_cont" id="relacionados">
      <div class="row">
        <div class="column relac-margin-top">
          <div class="main-heading-wrap related-head">
            <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources['prods_relacionados']; ?></h3></div>
          </div>
          <div class="listagem">
            <div class="slick-relacionados">
            <?php foreach($row_rsRelacionados as $relacionados) { 
              echo $class_produtos->divsProduto($relacionados, '');
            } ?> 
            
            </div>
             <button class="arrows_slick related_produtos_arrows show_arrows prev" aria-label="Prev Arrow" role="button">
                <span class="icon-left"></span>
              </button>       
              <button class="arrows_slick related_produtos_arrows show_arrows next" aria-label="Next Arrow" role="button">
                <span class="icon-right"></span>
              </button>

              <div class="view_all_product">
            <a href="loja" title="">VER MAIS</a>
          </div>

          </div>

        </div>
      </div>
    </div>
  <?php } ?>
</main>
<script type="text/javascript">
   $('.detail_slider').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: true,
  asNavFor: '.detail_thumb'
});
$('.detail_thumb').slick({
  slidesToShow: 5,
  slidesToScroll: 1,
  asNavFor: '.detail_slider',
  dots: false,
  centerMode: false,
  focusOnSelect: true
});
</script>
<style type="text/css">
  #ratinghome{ display: none !important; }
</style>
<?php 
DB::close();
include_once('pages_footer.php'); 
?>
