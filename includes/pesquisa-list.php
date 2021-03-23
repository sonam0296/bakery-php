<?php require_once('../Connections/connADMIN.php'); ?>
<?php
//pre($_POST);
if($_POST['op'] == "elementos") {
  $where = "";
  $left_join = "";

  $url_final = $_POST['url'];
  $url_param = explode("?", $url_final);
  $url = $url_param[0];
  $parametro = explode("&", $url_param[1]);
     

  $filtros_array = array();
  $filtrosQuery="";

  $order_by = "";

  $limit = 0;

  foreach($parametro as $params) {
    $params = explode("=", $params);
    $nome = $params[0];
    $value = $params[1];

    if($nome == "filtro") {
      $filter = $value;
    }
    if($nome == "ordem") {
      $order_by = $value;
    }
    if($nome == "search") {
      $pesq = urldecode($value);
    }
  }

  //Para permitir pesquisa com palavras não seguidas. Ex: pesquisa por "Caudalie lábios" aparecer "Caudalie Baton Cuidado de Lábios"
  if(strpos($pesq, " ") !== false || strpos($pesq, "%20") !== false || strpos($pesq, "+") !== false) {
    $pesq = str_replace(array(" ", "%20", "+"), "%", $pesq);
  }

  $array_pesq = array();

  if(tableExists(DB::getInstance(), 'destaques_pt') && (!$filter || $filter == strtolower($Recursos->Resources["pesq_destaques"]))) {
    $order_item = 99;
    if($order_by == strtolower($Recursos->Resources["pesq_destaques"])) {
      $order_item = 1;
    }

    $query_rsDestaques = "SELECT id, titulo AS nome, titulo AS texto, imagem1, link,'".addslashes($Recursos->Resources["pesq_destaques"])."' AS NomeTipo,'".$order_item."' AS OrderTipo, 'destaques' AS pasta FROM destaques".$extensao." WHERE visivel = 1 HAVING (nome LIKE :pesq OR titulo LIKE :pesq OR texto LIKE :pesq OR NomeTipo LIKE :pesq) ORDER BY nome ASC";
    $rsDestaques = DB::getInstance()->prepare($query_rsDestaques);
    if(hasParameter($query_rsDestaques, ':pesq')) $rsDestaques->bindValue(':pesq', "%$pesq%", PDO::PARAM_STR);
    $rsDestaques->execute();    
    $row_rsDestaques = $rsDestaques->fetchAll();
    $totalRows_rsDestaques = $rsDestaques->rowCount();
    
    if($totalRows_rsDestaques > 0) $array_pesq = array_merge($array_pesq, $row_rsDestaques);
  }
  if(tableExists(DB::getInstance(), 'noticias_pt') && (!$filter || $filter == strtolower($Recursos->Resources["pesq_noticias"]))) {
    $order_item = 99;
    if($order_by == strtolower($Recursos->Resources["pesq_noticias"])) {
      $order_item = 1;
    }

    $query_rsNoticias = "SELECT id, nome, imagem1, resumo AS texto, url, '".addslashes($Recursos->Resources["pesq_noticias"])."' AS NomeTipo,'".$order_item."' AS OrderTipo, 'noticias' AS pasta FROM noticias".$extensao." WHERE visivel = 1 HAVING (nome LIKE :pesq OR resumo LIKE :pesq OR texto LIKE :pesq OR NomeTipo LIKE :pesq) ORDER BY nome ASC";
    $rsNoticias = DB::getInstance()->prepare($query_rsNoticias);
    if(hasParameter($query_rsNoticias, ':pesq')) $rsNoticias->bindValue(':pesq', "%$pesq%", PDO::PARAM_STR);
    $rsNoticias->execute();    
    $row_rsNoticias = $rsNoticias->fetchAll(PDO::FETCH_ASSOC);
    $totalRows_rsNoticias = $rsNoticias->rowCount();

    if($totalRows_rsNoticias > 0) $array_pesq = array_merge($array_pesq, $row_rsNoticias);
  }
  if(tableExists(DB::getInstance(), 'blog_posts_pt') && (!$filter || $filter == strtolower("Blog"))) {
    $order_item = 99;
    if($order_by == "blog") {
      $order_item = 1;
    }

    $query_rsBlog = "SELECT pecas.id, pecas.titulo AS nome, pecas.imagem1, pecas.resumo AS texto, pecas.url, 'Blog' AS NomeTipo,'".$order_item."' AS OrderTipo, 'blog/posts' AS pasta, pecas.tags, cat1.nome AS cat1_nome FROM blog_posts".$extensao." AS pecas LEFT JOIN l_categorias".$extensao." AS cat1 ON (pecas.categoria = cat1.id AND cat1.visivel = 1) WHERE pecas.visivel = 1 HAVING (pecas.titulo LIKE :pesq OR pecas.resumo LIKE :pesq OR tags LIKE :pesq OR NomeTipo LIKE :pesq OR cat1_nome LIKE :pesq) ORDER BY pecas.titulo ASC";
    $rsBlog = DB::getInstance()->prepare($query_rsBlog);
    if(hasParameter($query_rsBlog, ':pesq')) $rsBlog->bindValue(':pesq', "%$pesq%", PDO::PARAM_STR);
    $rsBlog->execute();    
    $row_rsBlog = $rsBlog->fetchAll(PDO::FETCH_ASSOC);
    $totalRows_rsBlog = $rsBlog->rowCount();

    if($totalRows_rsBlog > 0) $array_pesq = array_merge($array_pesq, $row_rsBlog);
  }
  if(tableExists(DB::getInstance(), 'l_categorias_en') && (!$filter || $filter == strtolower($Recursos->Resources["pesq_categorias"]))) {
    $order_item = 99;
    if($order_by == strtolower($Recursos->Resources["pesq_categorias"])) {
      $order_item = 1;
    }

    $query_rsCats = "SELECT id, nome, imagem1, descricao AS texto, url, '".addslashes($Recursos->Resources["pesq_categorias"])."' AS NomeTipo,'".$order_item."' AS OrderTipo, 'categorias' AS pasta FROM l_categorias".$extensao." WHERE visivel = 1 HAVING (nome LIKE :pesq OR texto LIKE :pesq OR NomeTipo LIKE :pesq) ORDER BY nome ASC";
    $rsCats = DB::getInstance()->prepare($query_rsCats);
    if(hasParameter($query_rsCats, ':pesq')) $rsCats->bindValue(':pesq', "%$pesq%", PDO::PARAM_STR);
    $rsCats->execute();    
    $row_rsCats = $rsCats->fetchAll(PDO::FETCH_ASSOC);
    $totalRows_rsCats = $rsCats->rowCount();

    if($totalRows_rsCats > 0) $array_pesq = array_merge($array_pesq, $row_rsCats);
  }
  if(tableExists(DB::getInstance(), 'l_pecas_en') && (!$filter || $filter == strtolower($Recursos->Resources["pesq_produtos"]))) {
    $order_item = 99;
    if($order_by == strtolower($Recursos->Resources["pesq_produtos"]) || $order_by == "") {
      $order_item = 1;
    }

    // $query_rsProds = "SELECT pecas.id, pecas.nome, pecas.imagem1, pecas.descricao AS texto, pecas.url, '".addslashes($Recursos->Resources["pesq_produtos"])."' AS NomeTipo,'".$order_item."' AS OrderTipo, 'produtos' AS pasta, pecas.ref, cat1.nome AS cat1_nome, cat2.nome AS cat2_nome, cat3.nome AS cat3_nome FROM l_pecas".$extensao." AS pecas LEFT JOIN l_categorias".$extensao." AS cat1 ON (pecas.categoria = cat1.id AND cat1.visivel = 1) LEFT JOIN l_categorias".$extensao." AS cat2 ON (cat2.id = cat1.cat_mae AND cat2.visivel = 1) LEFT JOIN l_categorias".$extensao." AS cat3 ON (cat3.id = cat2.cat_mae AND cat3.visivel = 1) WHERE pecas.visivel = 1 HAVING (pecas.nome LIKE :pesq OR pecas.ref LIKE :pesq OR texto LIKE :pesq OR NomeTipo LIKE :pesq OR cat1_nome LIKE :pesq OR cat2_nome LIKE :pesq OR cat3_nome LIKE :pesq OR cat3_nome LIKE :pesq) ORDER BY pecas.nome ASC";

    $query_rsProds = "SELECT pecas.id, pecas.nome, pecas.imagem1, pecas.descricao AS texto, pecas.url, '".addslashes($Recursos->Resources["pesq_produtos"])."' AS NomeTipo,'".$order_item."' AS OrderTipo, 'produtos' AS pasta, pecas.ref, cat1.nome AS cat1_nome, cat2.nome AS cat2_nome, cat3.nome AS cat3_nome, brand.nome AS brand_nome 
      FROM l_pecas".$extensao." AS pecas 
      LEFT JOIN l_categorias".$extensao." AS cat1 ON (pecas.categoria = cat1.id AND cat1.visivel = 1)
      LEFT JOIN l_categorias".$extensao." AS cat2 ON (cat2.id = cat1.cat_mae AND cat2.visivel = 1) 
      LEFT JOIN l_categorias".$extensao." AS cat3 ON (cat3.id = cat2.cat_mae AND cat3.visivel = 1)

      LEFT JOIN l_marcas".$extensao." AS brand ON (brand.id = pecas.marca AND brand.visivel = 1) 
      WHERE pecas.visivel = 1 
      HAVING (pecas.nome LIKE :pesq OR pecas.ref LIKE :pesq OR texto LIKE :pesq OR NomeTipo LIKE :pesq OR cat1_nome LIKE :pesq OR cat2_nome LIKE :pesq OR cat3_nome LIKE :pesq OR cat3_nome LIKE :pesq OR brand_nome LIKE :pesq) 
      ORDER BY pecas.nome ASC";


    //echo $query_rsProds;

    $rsProds = DB::getInstance()->prepare($query_rsProds);
    if(hasParameter($query_rsProds, ':pesq')) $rsProds->bindValue(':pesq', "%$pesq%", PDO::PARAM_STR);
    $rsProds->execute();    
    $row_rsProds = $rsProds->fetchAll(PDO::FETCH_ASSOC);
    $totalRows_rsProds = $rsProds->rowCount();

    if($totalRows_rsProds > 0) $array_pesq = array_merge($array_pesq, $row_rsProds);
   // pre($array_pesq);
  }
  if(tableExists(DB::getInstance(), 'paginas_pt') && (!$filter || $filter == strtolower($Recursos->Resources["pesq_outros"]))) {
    $order_item = 99;
    if($order_by == strtolower($Recursos->Resources["pesq_outros"])) {
      $order_item = 1;
    }

    $query_rsPaginas = "SELECT paginas.id, paginas.nome AS nome, paginas.imagem1, blocos.texto, paginas.url, '".addslashes($Recursos->Resources["pesq_outros"])."' AS NomeTipo,'".$order_item."' AS OrderTipo, 'paginas' AS pasta FROM paginas".$extensao." AS paginas LEFT JOIN paginas_blocos".$extensao." AS blocos ON paginas.id = blocos.pagina WHERE paginas.visivel = 1 AND (paginas.nome LIKE :pesq OR paginas.titulo LIKE :pesq OR blocos.nome LIKE :pesq OR blocos.titulo LIKE :pesq OR blocos.titulo1 LIKE :pesq OR blocos.texto LIKE :pesq OR blocos.titulo2 LIKE :pesq OR blocos.texto2 LIKE :pesq OR blocos.titulo3 LIKE :pesq OR blocos.texto3 LIKE :pesq) GROUP BY paginas.id ORDER BY paginas.ordem ASC";
    $rsPaginas = DB::getInstance()->prepare($query_rsPaginas);
    if(hasParameter($query_rsPaginas, ':pesq')) $rsPaginas->bindValue(':pesq', "%$pesq%", PDO::PARAM_STR);
    $rsPaginas->execute();    
    $row_rsPaginas = $rsPaginas->fetchAll(PDO::FETCH_ASSOC);
    $totalRows_rsPaginas = $rsPaginas->rowCount();

    if($totalRows_rsPaginas > 0) $array_pesq = array_merge($array_pesq, $row_rsPaginas);
  }

  $total_prods = count($array_pesq);
  
  //necessário por causa dos caracteres especiais
  header("Content-type: text/html; charset=UTF-8");
  
  $is_first = $_POST['start'];
  if($is_first == 1) {
    $page = 1;
  }
  else {
    $page = (int) (!isset($_POST['first'])) ? 1 : $_POST['first'];
  }
  
  $limit = $_POST['limit']; #item per page
  
  # find out query stat point
  $start = ($page * $limit) - $limit;
  # query for page navigation
  if($total_prods > ($page * $limit)) {
    $next = ++$page;
  }

  $lenght = count($array_pesq);
  usort($array_pesq, 'arrayDescSort');
  $array_pesq = array_slice($array_pesq, $start, $limit); 

  DB::close();
  
  if(!empty($array_pesq)) { ?>
    <?php if($is_first == 1) { ?>
      <input type="hidden" id="pesq_value" value="<?php echo str_replace('%', ' ', $pesq); ?>">
      <input type="hidden" name="total_prods" id="total_prods" value="<?php echo $total_prods; ?>" />
    <?php } ?>
    <?php foreach($array_pesq as $search) { 
      $img = ROOTPATH_HTTP."imgs/elem/geral.svg";
      if($search['imagem1'] && file_exists(ROOTPATH."imgs/".$search['pasta']."/".$search['imagem1'])) {
        $img = ROOTPATH_HTTP."imgs/".$search['pasta']."/".$search['imagem1'];
      }

      $link = ROOTPATH_HTTP_LANG.$search['url'];
      if($search['NomeTipo'] == "Blog") {
        $link = ROOTPATH_HTTP_BLOG_LANG.$search['url'];
      }
      ?>
      <div class="pesq_div">
        <div class="row collapse">
          <a href="<?php echo $link; ?>" class="column small-12 xxsmall-expand xxsmall-shrink img has_bg<?php if($search['NomeTipo'] == $Recursos->Resources["pesq_produtos"]) echo " contain"; ?>" style="background-image:url('<?php echo $img; ?>')"></a>
          <div class="column small-12 xxsmall-expand info">
            <div class="div_100">
              <h5><?php echo $search['NomeTipo']; ?></h5>
              <a class="nome" href="<?php echo $link; ?>"><?php echo $search['nome']; ?></a>
            </div>
            <div class="desc list_txt"><?php echo strip_tags($search['texto']); ?></div>
            <?php if($search['pasta'] == "produtos") { ?>
              <div class="textos preco"><?php echo $class_produtos->precoProduto($search['id']); ?></div>
            <?php } ?>
            <a class="action button border" href="<?php echo $link; ?>"><?php echo $Recursos->Resources["saiba_mais"]; ?></a>
          </div>
        </div>
      </div>
    <?php } ?>  
  <?php } else { ?>
    <h6 class="search_no_results"><?php echo $Recursos->Resources["sem_produtos_pesq"]; ?></h6>
  <?php } ?>
<?php } ?>