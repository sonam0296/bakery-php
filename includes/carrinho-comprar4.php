<?php require_once('Connections/connADMIN.php'); ?>
<?php
if(ECOMMERCE_ATIVO == 0) {
  header("Location: ".ROOTPATH_HTTP."carrinho-contacto.php");
  exit();
}

$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$title = $row_rsMeta["title"];
$description = $row_rsMeta["description"];
$keywords = $row_rsMeta["keywords"];

if($row_rsCliente == 0 && CARRINHO_LOGIN == 1) {
  header("Location: ".ROOTPATH_HTTP."carrinho.php");
  exit(); 
}

$id_cliente = 0;
if(!empty($row_rsCliente['id'])) {
  $id_cliente = $row_rsCliente['id'];
}

$encomendas_id = $_GET['encomendas_id'];
$txn_id = $_GET['txn_id'];
$estado = 4;


if($txn_id != "")
{  
    $insertSQL = "UPDATE encomendas SET txn_id='".$txn_id."',estado='".$estado."' WHERE id=".$encomendas_id."";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
}  

if(isset($_SESSION["email_user"])) $query_rsEncomenda = "SELECT * FROM encomendas WHERE email = '".$_SESSION["email_user"]."' ORDER BY id DESC LIMIT 1";
else $query_rsEncomenda = "SELECT * FROM encomendas WHERE id_cliente = '$id_cliente' ORDER BY id DESC LIMIT 1";
$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
$rsEncomenda->execute();
$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncomenda = $rsEncomenda->rowCount();

if($totalRows_rsEncomenda == 0) {
  header("Location: index.php");
  exit();
}

$txn_id = $row_rsEncomenda['txn_id'];
$id_encomenda = $id_enc = $row_rsEncomenda['id'];
$numero_enc = $row_rsEncomenda['numero'];
$total_enc = $class_carrinho->mostraPrecoEnc($id_encomenda, $row_rsEncomenda['valor_total'], 1, 0);

$query_rsCarrinhoFinal = "SELECT * FROM encomendas_produtos WHERE id_encomenda='$id_encomenda' ORDER BY id ASC";
$rsCarrinhoFinal = DB::getInstance()->prepare($query_rsCarrinhoFinal);
$rsCarrinhoFinal->execute();
$row_rsCarrinhoFinal = $rsCarrinhoFinal->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsCarrinhoFinal = $rsCarrinhoFinal->rowCount();  
    
$moeda = $row_rsEncomenda['moeda'];
$currency_code = $row_rsEncomenda['codigo_moeda'];
$link_paypal = "";
$pagamento = $row_rsEncomenda['met_pagamt_id'];

$query_rsQtds = "SELECT * FROM met_pagamento".$extensao." WHERE id = $pagamento";
$rsQtds = DB::getInstance()->prepare($query_rsQtds);
$rsQtds->execute();
$row_rsQtds = $rsQtds->fetch(PDO::FETCH_ASSOC);
$totalRows_rsQtds = $rsQtds->rowCount();

$menu_sel = "carrinho";

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
<?php if($title){ echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); }else{ echo $Recursos->Resources["pag_title"];}?>
</title>
<?php if($description){?>
<META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php if($keywords!=""){?>
<META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php include_once('codigo_antes_head.php'); ?>
<?php include_once('funcoes.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
  <div id="loader">
    
  </div>
</div>
<div class="mainDiv">
  <div class="row1">
    <div class="div_table_cell">
      <?php include_once('header.php'); ?>
      <div class="row collapse content">
        <div class="column">
          <div class="div_100 carrinho">
            <nav class="row show-for-medium" aria-label="You are here:" role="navigation">
              <div class="column">
                <ul class="row collapse carrinho_nav">
                  <li class="column uppercase"><?php echo $Recursos->Resources["carrinho_passo1"]; ?></li><!--
                  --><li class="column uppercase"><?php echo $Recursos->Resources["carrinho_passo2"]; ?></li><!--
                  --><li class="column uppercase"><?php echo $Recursos->Resources["carrinho_passo3"]; ?></li>
                </ul>
              </div>
            </nav>        
            <div class="div_100 carrinho_finalize">
              <div class="row align-right">
                <div class="small-12 column info">
                  <?php if($txn_id != ""){ ?>
                  <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark_circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark_check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
                  <?php } ?>
                  <?php if($pagamento == 2 || $pagamento == 6 || $pagamento == 1) { ?> 
                    <h1><?php echo $Recursos->Resources["comprar_msg_sucesso4"]."<br><br>".$Recursos->Resources["comprar_msg_sucesso4_2"]; ?></h1>       
                  <?php }else{ ?>  
                    <h1><?php echo $Recursos->Resources["comprar_msg_sucesso4"]; ?></h1>
                  <?php } ?>             
                  <div class="dados">
                    <div class="pagamento_info">
                      <?php
                      $img = ROOTPATH_HTTP."imgs/carrinho/geral.png";
                      if($row_rsQtds['imagem']!='' && file_exists(ROOTPATH.'imgs/carrinho/'.$row_rsQtds['imagem'])) { 
                        $img = ROOTPATH_HTTP."imgs/carrinho/".$row_rsQtds['imagem'];
                      }
                      
                      $nome = $row_rsQtds['nome'];
                  
                      if($row_rsQtds['descricao']) {
                        $descricao = $row_rsQtds['descricao'];
                      }
                      if($row_rsQtds['descricao2']) {
                        $descricao2 = $row_rsQtds['descricao2'];
                      }
                      ?>
                      <div class="img"><img src="<?php echo $img; ?>" width="100%" /></div><!--
                      --><div class="txt">
                        <span><?php echo $Recursos->Resources["comprar_pagamento"]; ?><br><?php echo $nome;?></span>
                        <?php if($descricao) { ?><div class="desc"><?php echo $descricao; ?></div><?php } ?>  
                          
                        <?php if($row_rsQtds['id'] == 10) { // WorldPay
                          include_once("worldpay/index.php");
                        } 
                        else if($row_rsQtds['id'] == 6 || $row_rsQtds['id'] == 7) { // Multibanco
                          $ref_pag = $row_rsEncomenda['ref_pagamento'];
                          $ref_pagamento = substr($ref_pag, 0, 3)." ".substr($ref_pag, 3, 3)." ".substr($ref_pag, 6, 3);
                          ?>
                          <div class="desc multibanco">
                            <?php echo $Recursos->Resources["comprar4_entidade"].": <strong>".$row_rsEncomenda['entidade']."</strong>"; ?><br>
                            <?php echo $Recursos->Resources["comprar4_referencia"].": <strong>".$ref_pagamento."</strong>"; ?><br>
                            <?php echo $Recursos->Resources["comprar4_montante"].": <strong>".number_format($row_rsEncomenda['valor_c_iva'], 2,',', ' ').$moeda."</strong>"; ?>
                          </div>
                        <?php } 
                        else if($row_rsQtds['id'] == 8) { // Cartão de Crédito EasyPay ?>
                          <div class="desc multibanco">
                            <a href="<?php echo $row_rsEncomenda['url_pagamento'];?>" class="comprar4_paypal" target="_blank"><?php echo $Recursos->Resources["car_comprar_easypay"]; ?></a>
                          </div>
                        <?php } ?>
                      </div>
                    </div>  
                  </div>
                  <?php if($descricao2) { ?>
                    <div class="dados">
                      <img src="<?php echo ROOTPATH_HTTP; ?>imgs/carrinho/info.svg" width="100%" style="max-width:30px; margin:auto; margin-bottom:20px;" />
                      <div class="desc"><?php echo $descricao2; ?></div>
                    </div>
                  <?php } ?>
                  <h4><?php echo $Recursos->Resources["comprar4_obrigado"]; ?></h4>
                   <?php if($txn_id != ""){ ?>
                  <a class="carrinho_btn reverse" href="imprime_encomenda.php?encomenda=<?php echo $row_rsEncomenda['id']; ?>" target="_blank">
                    <img src="<?php echo ROOTPATH_HTTP; ?>imgs/carrinho/print.svg" width="100%" />
                    <?php echo $Recursos->Resources["car_imprimir"]; ?>
                  </a>
                <?php } ?>
                  <a class="carrinho_btn" href="<?php echo CARRINHO_VOLTAR; ?>"><?php echo $Recursos->Resources["fazer_compra"]; ?></a>
                </div>
              </div>  
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>    
</div>
<input type="hidden" name="menu_sel" id="menu_sel" value="carrinho" />

<?php if($row_rsEncomenda['id'] > 0 && $row_rsEncomenda['numero'] > 0 && $row_rsEncomenda['email_enviado'] == 0) { 
  if($link_paypal != "") {
    $query_rsUpdate = "UPDATE encomendas SET url_paypal = :url WHERE id = :id";
    $rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
    $rsUpdate->bindParam(':url', $link_paypal, PDO::PARAM_STR, 5);
    $rsUpdate->bindParam(':id', $row_rsEncomenda['id'], PDO::PARAM_INT);
    $rsUpdate->execute();
  }

  $class_carrinho->emailEncomenda($row_rsEncomenda['id']);

  $valor_enc = $row_rsEncomenda['valor_c_iva'];
  $valor_portes_ent = $row_rsEncomenda['portes_entrega'];

  if($row_rsEncomenda['valor_conversao'] > 0) {
    $valor_enc = round($row_rsEncomenda['valor_c_iva'] / $row_rsEncomenda['valor_conversao'], 2);
    $valor_portes_ent = round($row_rsEncomenda['portes_entrega'] / $row_rsEncomenda['valor_conversao'], 2);
  }
  
  if(in_array($_SERVER['HTTP_HOST'], $array_servidor) && !strstr($_SERVER['REQUEST_URI'], '/proposta')) { ?>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?php echo ANALYTICS; ?>']);
      _gaq.push(['_trackPageview']);
      _gaq.push(['_addTrans',
        '<?php echo $row_rsEncomenda['id']; ?>',        // order ID - required
        '<?php echo NOME_SITE; ?>',             // affiliation or store name
        '<?php echo $valor_enc; ?>',    // total - required
        '0',                                // tax
        '<?php echo $valor_portes_ent; ?>', // shipping
        '',                               // city
        '<?php echo $row_rsEncomenda['pais_envio']; ?>'   // country
      ]);

      <?php foreach ($row_rsCarrinhoFinal as $produtos)  {  
        if($produtos['cheque_prenda'] == 1) {
          $ref = NOME_SITE;
          $row_rsCategoria['nome'] = "Cheque Prenda";     
        }
        else {
          $ref = $produtos['ref'];
          if(!$ref) $ref = $produtos['produto_id'];
          if($produtos['opcoes']) {
            $ref .= "_".$produtos['id'];
          }
          
          $id_p = $produtos['produto_id'];
          
          $query_rsPeca = "SELECT categoria FROM l_pecas".$extensao." WHERE id='$id_p'";
          $rsPeca = DB::getInstance()->prepare($query_rsPeca);
          $rsPeca->execute();
          $row_rsPeca = $rsPeca->fetch(PDO::FETCH_ASSOC);
          
          $query_rsCategoria = "SELECT nome FROM l_categorias".$extensao." WHERE id='".$row_rsPeca['categoria']."'";
          $rsCategoria = DB::getInstance()->prepare($query_rsCategoria);
          $rsCategoria->execute();
          $row_rsCategoria = $rsCategoria->fetch(PDO::FETCH_ASSOC);
        }
      
        $preco = $produtos['preco'];
        if($row_rsEncomenda['valor_conversao'] > 0) {
          $preco = round($produtos['preco'] / $row_rsEncomenda['valor_conversao'], 2);
        }
        ?>
        _gaq.push(['_addItem',
          '<?php echo $row_rsEncomenda['id']; ?>',           // order ID - required
          '<?php echo trim($ref); ?>',           // SKU/code - required
          '<?php echo addslashes(trim($produtos['produto'])); ?>',        // product name
          '<?php echo addslashes($row_rsCategoria['nome']); ?>',   // category or variation
          '<?php echo $preco; ?>',          // unit price - required
          '<?php echo $produtos['qtd']; ?>'               // quantity - required
        ]);
      <?php } ?>
      _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
        'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
      })();
   </script>
  <?php } ?> 
<?php } ?>

<?php 
DB::close();
include_once('codigo_antes_body.php'); 
?>
</body>
</html>