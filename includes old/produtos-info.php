<?php 
if($row_rsCliente != 0) {
  $id_cliente = $row_rsCliente['id'];
  $where_list = "lista.cliente = '$id_cliente'";
}
else {
  $wish_session = $_COOKIE[WISHLIST_SESSION];
  
  if($wish_session) {
    
    $where_list = "lista.session = '$wish_session'";
  }
}

$totalRows_rsFavorito = 0;
if($where_list) {
  $query_rsFavorito = "SELECT lista.id FROM lista_desejo AS lista LEFT JOIN l_pecas".$extensao." AS pecas ON lista.produto = pecas.id WHERE ".$where_list." AND lista.produto = '".$produto."' AND pecas.visivel = 1 GROUP BY pecas.id ORDER BY pecas.ordem ASC";
  $rsFavorito = DB::getInstance()->prepare($query_rsFavorito);
  $rsFavorito->execute();
  $row_rsFavorito = $rsFavorito->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsFavorito = $rsFavorito->rowCount();
}
?>
<div class="column small-12<?php if($menu_sel == "produtos") echo " xxsmall-7 medium-7"; ?>">
  <div class="div_100 product-detail-info"<?php if($menu_sel != "produtos") echo ' style="padding: 0; margin-bottom: 2rem;"'; ?>>
  <div class="product-details-cls">
    <div class="div_100">
      <h1><?php echo $row_rsProduto['nome']; ?></h1>
      <?php if($row_rsProduto['ref']) { ?>
        <h3>Ref. <?php echo $row_rsProduto['ref']; ?></h3>
      <?php } ?>
      <?php if($row_rsMarca['nome']) { ?>
        <h3><strong><?php echo $Recursos->Resources['marca']; ?></strong> <?php echo $row_rsMarca['nome']; ?></h3>
      <?php } ?>
      <?php if($row_rsProduto['descricao']) { ?>
        <div class="descricao_text">
          <p><?php echo $row_rsProduto['descricao']; ?></p>
        </div>
      <?php } ?>
    </div>  
    <div class="detalhe_divs">
      <?php 
        $reviewsAll = $class_produtos->produtoallrating($row_rsProduto['id']);
        $produtoRating = $class_produtos->produtoRating($row_rsProduto['id']);
        $avarage_rating = $produtoRating['avarage_rating'];

      ?>
      <!-- <div class="star-rating">
          <?php for ($x = 1; $x <= 5; $x++) {
              if ($x <= round($avarage_rating)) {
                  echo '<i class="fa fa-star"></i>';
              }else{
                  echo '<i class="fa fa-star-o"></i>'; 
              }
          } ?>
          <b class="text-black ml-2">(<?php echo count($reviewsAll); ?> reviews) </b>
      </div> -->
      <?php if($row_rsProduto['descricao']) { ?>
        <!-- <ul accordion styled id="desc_accordion">
          <li accordion-item>
            <a href="javascript:;" class="list_tit" accordion-title><?php echo $Recursos->Resources['descricao']; ?></a>
            <div class="textos" accordion-content><?php echo $row_rsProduto['short_descricao']; ?></div>
          </li>
        </ul> -->
      <?php } ?> 
      <?php if(!empty($array_promocao) && $detalhe == 1) { ?>
        <!-- <div class="div_100 detalhe_promocoes row collapse"> 
          <?php if($array_promocao['3'] != '') { 
          
              $query_rsPaginaCondicoes = "SELECT url FROM paginas".$extensao." WHERE id = '".$array_promocao['3']."' AND visivel = 1";
              $rsPaginaCondicoes = DB::getInstance()->prepare($query_rsPaginaCondicoes);
              $rsPaginaCondicoes->execute();
              $row_rsPaginaCondicoes = $rsPaginaCondicoes->fetch(PDO::FETCH_ASSOC);
              $totalRows_rsPaginaCondicoes = $rsPaginaCondicoes->rowCount();

              if($totalRows_rsPaginaCondicoes > 0) { ?>
                <div class="column">
                  <div class="row collapse">
                    <div class="column detalhe_promocoes_pagina">
                      <a href="<?php echo ROOTPATH_HTTP.$row_rsPaginaCondicoes['url']; ?>" target="_blank" class="button invert2"><?php echo $Recursos->Resources["promocao_link"]; ?></a>
                    </div>
                  </div>

                </div>
              <?php }
            } ?>
          
        </div> -->
      <?php } ?>
    </div>

      
      <div class="detalhe_quantidade"> 
        <div class="detalhe_preco" id="conteudo_preco_<?php echo $row_rsProduto['id']; ?>">
          <?php echo $class_produtos->precoProduto($row_rsProduto['id']); ?>
          <?php //echo $class_produtos->labelsProduto($row_rsProduto['id'], 2, 'detalhe'); ?>
          <input name="preco_final" id="preco_final_<?php echo $row_rsProduto['id']; ?>" type="hidden" value="<?php echo $class_produtos->precoProduto($row_rsProduto['id'], 0); ?>" />
        </div>

        <!-- <div class="row collapse">
          <div class="column detalhe_promocoes_tit">
            <?php if($array_promocao['1'] != '') echo $array_promocao['1']; else echo $Recursos->Resources['promocao_titulo']; ?>
          </div>
        </div> -->
        <?php if($array_promocao['0'] != '') { ?>
          <div class="row collapse">
            <div class="column detalhe_promocoes_datas">
                <?php echo $array_promocao['0']; ?>
            </div>
          </div>
        <?php }
        if($array_promocao['2'] != '') { ?>
          <!-- <div class="row collapse">
            <div class="column detalhe_promocoes_txt">
              <?php echo $array_promocao['2']; ?>
            </div>
          </div> -->
        <?php } ?>

        
      </div>
    
    
    </div>
    <?php
     $rsInsert = "SELECT * FROM l_pecas_tamanhos WHERE peca = ".$row_rsProduto["id"]."";
      $rsInsert = DB::getInstance()->prepare($rsInsert);
      $rsInsert->execute();
      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
      $totalRows_tamanhos = $rsInsert->rowCount();
     ?>
        <div class="bg-wrap">
      <?php if($totalRows_tamanhos > 0) { ?>
         <div class="filter-wraps">
            <div class="filter-size <?php echo ($row_rsProduto['custom_variation'] == 1) ? 'custom_variation' : null; ?>" id="prod_opc_1_<?php echo $produto; ?>"><?php echo $class_produtos->tamanhosProduto($row_rsProduto["id"]); ?></div>
            <div class="filter-size" id="prod_opc_2_<?php echo $produto; ?>" style="display: none;"></div>
            <div class="filter-size" id="prod_opc_3_<?php echo $produto; ?>" style="display: none;"></div>
            <div class="filter-size" id="prod_opc_4_<?php echo $produto; ?>" style="display: none;"></div>
            <div class="filter-size" id="prod_opc_5_<?php echo $produto; ?>" style="display: none;"></div>
        </div>
      <?php } else{ ?>
          
      <?php } ?>

      <div class="detail_info_select">
        <div class="tamanhos_divs">
          
          <?php 
            $query_rsRole = "SELECT * FROM roll";
            $rsRole = DB::getInstance()->prepare($query_rsRole);
            $rsRole->execute();
            $totalRows_rsRoll = $rsRole->fetchAll();
            DB::close();

            $query_rs_supp = "SELECT * FROM l_pecas_en where id =".$produto;
            $rsP_supp = DB::getInstance()->prepare($query_rs_supp); 
            $rsP_supp->execute();
            $totalRows_rsP_supp = $rsP_supp->rowCount();
            $row_rsP_supp = $rsP_supp->fetch(PDO::FETCH_ASSOC);


            $row_rsCliente = User::getInstance()->isLogged();
            foreach ($totalRows_rsRoll as $role) { 

            if($row_rsCliente["roll"] == $role['roll_name'] && $row_rsP_supp['stock'] != 0){

            $product_qulity = 'product_qulity_'.$role['roll_name'];
            $product_qulityy = $row_rsP_supp['stock'];
          ?>
          
          <?php if (!empty($row_rsCliente["roll"])): ?>
            <p>For <?php echo $row_rsCliente["roll"]; ?>  <?php echo $product_qulityy;?> Qtn</p>
          <?php else: ?>
            <label class="list_tit" for="quantidades"><?php echo $Recursos->Resources["cart_qtd"]; ?></label>
          <?php endif ?>

          <?php 

          $query_rsList = "SELECT * FROM l_pecas_tamanhos WHERE l_pecas_tamanhos.peca=:id ORDER BY l_pecas_tamanhos.id DESC";
          $rsList = DB::getInstance()->prepare($query_rsList);
          $rsList->bindParam(':id', $produto, PDO::PARAM_INT);
          $rsList->execute();
          $totalRows_rsList = $rsList->rowCount();

           ?>
           <?php
            $check_qty_disable = true;
            if ($totalRows_rsList > 0) {
              if ($row_rsProduto['custom_variation'] == 1) {
                  $check_qty_disable = false;
              }
            }
            ?>
          <div class="select_holder icon-down">
            <select name="quantidades" data-stock="<?php echo $product_qulityy;?>" id="qtd_<?php echo $produto; ?>" required data-produto="<?php echo $produto; ?>" <?php echo ($check_qty_disable == false) ? 'disabled="disabled"' : null; ?>>
                <option><?php echo $Recursos->Resources["cart_qtd"]; ?></option>
              <?php for($i = 1; $i <= $product_qulityy; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if($i == $quantidade) echo "selected"; ?>><?php echo $i; ?></option>
              <?php } ?>
            </select>
          </div>
          <?php } } ?>    
        </div>
        <?php if ($row_rsProduto['custom_variation'] == 1): ?>
            
            <?php 
              $custom_variation_options = unserialize($row_rsProduto['custom_variation_options']);
              $custom_variation_options_string = implode(', ', $custom_variation_options);
              $query_rsCats = "SELECT * FROM l_caract_categorias_en WHERE id IN ($custom_variation_options_string) ORDER BY ordem ASC";
              $rsCats = DB::getInstance()->prepare($query_rsCats);
              $rsCats->execute();
              $row_rsCats = $rsCats->fetchAll(PDO::FETCH_ASSOC);
              $totalRows_rsCats = $rsCats->rowCount();
            ?>
          
        <?php endif ?>
        <div class="detalhe_quantidade <?php  if($row_rsP_supp['stock'] == 0) { echo "out_of_stock";} else{ "";} ?>">

          <?php if(!empty($array_promocao) && $detalhe != 1) { ?>
            <div class="div_100 detalhe_divs_promocoes row collapse">
              <div class="column detalhe_promocoes_datas">
                <?php echo $array_promocao['0']; ?>
                <a href="<?php echo $row_rsProduto['url']; ?>" class="detalhe_promocoes_link"><?php echo $Recursos->Resources['promocoes_ver_mais']; ?></a>
              </div>
            </div>
          <?php } ?>

            
            
          <?php if($totalRows_rsCats > 0): ?>  
            <div class="custom-variation-wrap repeater" style="display: none;" data-options="<?php echo $custom_variation_options_string; ?>">
              <div data-repeater-list="outer-list">
                <div data-repeater-item>
                  <ul>
                    <?php foreach ($row_rsCats as $key => $value): ?>
                      <li>
                        <?php 
                          $op_id =$value['id']; 
                          $query_rsCat2 = "SELECT * FROM l_caract_opcoes_en WHERE categoria='$op_id' ORDER BY ordem ASC, nome ASC";
                          $rsCat2s = DB::getInstance()->prepare($query_rsCat2);
                          $rsCat2s->execute();
                          $totalRows_rsCat2s = $rsCat2s->rowCount();
                          if($totalRows_rsCat2s>0) { ?> 
                              <select class="form-control" style="width:200px;" id="v_<?php echo $value['id']; ?>" name="v_<?php echo $value['id']; ?>">
                                <option value="0">Select <?php echo $value['nome']; ?></option>                 
                                <?php while($row_rsCat2s = $rsCat2s->fetch()) { ?>
                                  <option value="<?php echo $row_rsCat2s['id']; ?>"><?php echo $row_rsCat2s['nome']; ?></option>
                                <?php } ?>
                              </select>
                          <?php } ?>
                      </li>
                    <?php endforeach; ?>
                    <li>
                      <input type="number" class="form-control qty_data" placeholder="Qty" name="v_qty"/>
                    </li>
                  </ul>
                  <input data-repeater-delete type="button" value="Remove"/>
                  <!-- innner repeater -->
                </div>
              </div>
              <input data-repeater-create type="button" value="Add"/>
            </div>
          <?php endif; ?>

          <?php if($row_rsP_supp['stock'] == 0 && 'product_qulity_'.$role['roll_name'] == 0 ) { ?>
            <h1>Out Of Stock</h1>
          <?php } else { ?>
              <a href="javascript:;" class="<?php echo ($popop_addto) ? $popop_addto : null; ?> detalhe_adiciona adiciona_carrinho button-big invert2" data-product="<?php echo $row_rsProduto['id']; ?>"><?php echo $Recursos->Resources["comprar"]; ?></a>
          <?php } ?>
            <!-- <div class="stock list_txt" id="conteudo_stock"><?php echo $class_produtos->stockProduto($row_rsProduto['id'], 0, 0, 0, 0, 0, 3); ?></div>  --> 
           
        </div>
      </div><!-- /detail_info_select -->

      <div class="share-fav detalhe_quantidade">
        <a href="javascript:;" class="favoritos text-center<?php if($totalRows_rsFavorito > 0) echo " icon-favoritos2 active"; else echo " icon-favoritos"; ?>" onClick="adiciona_favoritos(<?php echo $row_rsProduto['id']; ?>, this, event);"><?php echo $Recursos->Resources["add_favoritos"]; ?></a>
      <div class="share_section text-left" id="share">
        <?php
        $sharePos = "bottom";
        $shareClass = "shareInvert"; //shareInvert
        $shareTitulo = $Recursos->Resources["partilhar"];
        $shareNome = urlencode(utf8_encode($row_rsProduto["nome"]));
        $shareDesc = urlencode(str_replace(utf8_encode('"'), "'", $row_rsProduto["descricao"]));
        $shareUrl = ROOTPATH_HTTP.$row_rsProduto["url"];
        if($countLang > 1) {
          $shareUrl = ROOTPATH_HTTP.$lang."/".$row_rsProduto["url"];
        }
        $shareImg = ROOTPATH_HTTP."/imgs/produtos/".$row_rsProduto["imagem1"];
        include_once(ROOTPATH.'includes/share-list.php');
        ?>
      </div>
      </div>

    </div>
      <?php if (!empty($row_rsProduto['marca'])): ?>
        <div class="productBrand">
          <?php $produtomarca = $class_produtos->get_brand($row_rsProduto['marca']); ?>
          <?php if (!empty($produtomarca)): ?>
            <a href="javascript:;" title="">
              <?php if (!empty($new_array[3])): ?>
                <?php $produtomarca_image = ROOTPATH_HTTP."imgs/marcas/".$produtomarca[3]; ?> 
              <?php else: ?>
                <?php $produtomarca_image = ROOTPATH_HTTP."imgs/elem/geral.svg"; ?>
              <?php endif ?>
              <img width="80" src="<?php echo $produtomarca_image; ?>" alt="<?php echo $produtomarca[1]; ?>">        
            </a>
          <?php endif ?>
        </div>
      <?php endif ?>
      
  </div>
</div>
  