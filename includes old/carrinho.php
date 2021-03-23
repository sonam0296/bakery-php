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

$faz_reload = 0;
$produto_com_portes_gratis = 0;
$carrinho_session = $_COOKIE[CARRINHO_SESSION];
$empty = $class_carrinho->isEmpty();
$total = $total_final = $total_final_sem_promo = $class_carrinho->precoTotal();

//Se existir este parâmetro, significa que o cliente clicou no link para finalizar a encomenda através do email "Carrinho Abandonado".
//Neste caso temos de atualizar a session na tabela do carrinho para o novo valor, em todas as linhas com o id_cliente atual
if(isset($_GET['rc']) && $_GET['rc'] == 1 && $row_rsCliente['id'] > 0) {
  $query_rsSelect = "SELECT session FROM carrinho WHERE id_cliente = :user LIMIT 1";
  $rsSelect = DB::getInstance()->prepare($query_rsSelect);
  $rsSelect->bindParam(':user', $row_rsCliente['id'], PDO::PARAM_INT);
  $rsSelect->execute();
  $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsSelect = $rsSelect->rowCount();

  if($totalRows_rsSelect > 0) {
    $carrinho_session = $row_rsSelect['session'];

    $timeout = 3600*24*5; //5 dias
    setcookie(CARRINHO_SESSION, $carrinho_session, time()+$timeout, "/", "", $cookie_secure, true);
  }
}

if(CARRINHO_SALDO == 1) {
  $query_rsProcS = "SELECT valor FROM carrinho_comprar WHERE session='$carrinho_session'";
  $rsProcS = DB::getInstance()->prepare($query_rsProcS);
  $rsProcS->execute();
  $row_rsProcS = $rsProcS->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsProcS = $rsProcS->rowCount();
  
  $saldo_acumula = $class_carrinho->acumularSaldo();
  
  $saldo_disp = $row_rsCliente['saldo'];  
  if($saldo_disp > 0) {
    if($saldo_disp >= $total) {
      $saldo_a_utilizar = $total;
    }
    else {
      $saldo_a_utilizar = $saldo_disp;
    }

    if($totalRows_rsProcS > 0 && $row_rsProcS['valor'] > 0) { 
      if($saldo_disp >= $total) {
        $saldo_compra = $total;
        $saldo_disp = $saldo_disp - $total;
        $total = 0;
      }
      else {
        $saldo_compra = $saldo_disp;
        $total = $total - $saldo_disp; 
        $saldo_disp = 0;
      }
    }
  }
}

if(CARRINHO_CODIGOS == 1) {
  $query_rsCarCodProm = "SELECT id_codigo FROM carrinho_cod_prom WHERE session='$carrinho_session'";
  $rsCarCodProm = DB::getInstance()->prepare($query_rsCarCodProm);
  $rsCarCodProm->execute();
  $row_rsCarCodProm = $rsCarCodProm->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsCarCodProm = $rsCarCodProm->rowCount();
  
  if($totalRows_rsCarCodProm > 0) {
    $query_rsCodProm = "SELECT codigo FROM codigos_promocionais WHERE id='".$row_rsCarCodProm["id_codigo"]."'";
    $rsCodProm = DB::getInstance()->prepare($query_rsCodProm);
    $rsCodProm->execute();
    $row_rsCodProm = $rsCodProm->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsCodProm = $rsCodProm->rowCount();

    $desconto_promo = $class_carrinho->calcula_cod_promo($row_rsCodProm['codigo']);

    if($row_rsCodProm['tipo_desconto'] == 1) {
      $total = $total - $desconto_promo; 
      $desconto_promo = "- ".$class_carrinho->mostraPreco($desconto_promo);
    }
    else {
      $total = $total - $desconto_promo; 
      $desconto_promo = "- ".$class_carrinho->mostraPreco($desconto_promo);
    }
  }
}

DB::close();

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<?php include_once('codigo_antes_head.php'); ?>
<?php include_once('funcoes.php'); ?>
</head>
<body class="mask-visible">
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
            <nav class="row" aria-label="You are here:" role="navigation">
              <div class="column">
                <ul class="row collapse carrinho_nav">
                  <li class="uppercase column active"><?php echo $Recursos->Resources["carrinho_passo1"]; ?></li><!--
                  --><li class="uppercase column"><?php echo $Recursos->Resources["carrinho_passo2"]; ?></li><!--
                  --><li class="uppercase column"><?php echo $Recursos->Resources["carrinho_passo3"]; ?></li>
                </ul>
              </div>
            </nav>
            
            <div class="row align-right carrinho_list">
                
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>

 <div style="display: none;" id="animatedModal" class="animated-modal text-center p-5">

 </div>

  <?php include_once('footer.php'); ?>    
</div>
<input type="hidden" name="menu_sel" id="menu_sel" value="carrinho" />

<script type="text/javascript">
  $(document).on('click', '.store_name', function(event) {
  event.preventDefault();

  var data_id = $(this).attr('id');
  $.ajax({
    url: 'store_popup.php',
    type: 'get',
    dataType: 'html',
    data: {data_id: data_id},
  })
  .done(function(html) {
    console.log("success");
    $("#animatedModal").html(html);
    $.fancybox.open($('#animatedModal'));
    console.log(data_id);

  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
  
});
</script>

<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


<script type="text/javascript">
$(window).on('load', function() {
  carregaElementos();
});

function carregaElementos() {
  $.post(_includes_path+"carrinho-rpc.php", {op:"listagemCarrinho"}, function(data) {
    $('.carrinho_list').html(data);
  });
}

function loginCheck() {
  ntg_confirm(
    {
      type: 'info',
      title: "<?php echo $Recursos->Resources["cart_semlogin_tit"]; ?>",
      html: "<?php echo $Recursos->Resources["cart_semlogin_txt"]; ?>",
      showCloseButton: true,
      showCancelButton: true,
      cancelButtonText: "<?php echo $Recursos->Resources["cart_semlogin_btn1"]; ?>",
      showConfirmButton: true,
      confirmButtonText: "<?php echo $Recursos->Resources["cart_semlogin_btn2"]; ?>",
    },
    function() { 
      window.location = 'carrinho-comprar.php';
    },
    function() {
      window.location = 'login.php?carrinho=1';
    },
    "",
    ""
  );
}

<?php if(isset($_GET['erro']) && $_GET['erro'] == 1) { ?>
  ntg_error("<?php echo $Recursos->Resources["carrinho_erro1"]; ?>");    
<?php } ?>

<?php if(isset($_GET['erro']) && $_GET['erro'] == 2) { ?>
  ntg_error("<?php echo $Recursos->Resources["carrinho_erro2"]; ?>");    
<?php } ?>

<?php if(CARRINHO_SALDO == 1) { ?>
  function comprar_com_saldo() {
    if(document.getElementById('usar_saldo').checked == true) {
      $.post(_includes_path+"carrinho-rpc.php", {op:'comprar_com_saldo', tipo:'1', valor:'<?php echo $saldo_a_utilizar; ?>'}, function(data) {
        carregaElementos();
      });
    }
    else {
      $.post(_includes_path+"carrinho-rpc.php", {op:'comprar_com_saldo', tipo:'0'}, function(data) {
        carregaElementos();
      });
    }
  }
<?php } ?>

<?php if(CARRINHO_CODIGOS == 1) { ?>
  //Tipo
  //1 - faz reload á página
  //0 - não faz reload
  function altera_cod_promo(valor, valor_sem_promo, tipo) {
    valor = valor.replace( ",", "." );      
    valor = parseFloat(valor);
    valor = valor.toFixed(2);
    
    var cod_promo = "";
    if(document.getElementById('cod_promo')) {
      cod_promo = document.getElementById('cod_promo').value;
    }
    else if(document.getElementById('cod_promo_esc')) {
      cod_promo = document.getElementById('cod_promo_esc').value;
    }
    
    if(cod_promo == '') {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_1"]; ?>");
    }
    else {
      $.post(_includes_path+"carrinho-rpc.php", {op:"carregaCodigoPromo", total:valor, cod:cod_promo}, function(data) {
        data = data.trim();
        
        if(data == 1) {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 1);
        }
        else if(data == 2) {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 2);
        }
        else if(data == 4) {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 4);
        }
        else if(data == 5) {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 5);
        }
        else if(data == 7) {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 7);  
        }
        else if(data == 8) {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 8);
        }
        else if(data == 3) {
          $.post(_includes_path+"carrinho-rpc.php", {op:"carregaCodigoPromoTotal", total:valor, total_sem_promo:valor_sem_promo, cod:cod_promo}, function(data) {
            if(tipo == 1) {
              carregaElementos();  
            }
          });
        }
        else {
          limpaCodigoP('<?php echo $total_final; ?>', '<?php echo $total_final_sem_promo; ?>', '<?php echo $row_rsCodProm["codigo"]; ?>', 1, 0);
        }
      });
    }
  }

  //Tipo
  //1 - remove código sem perguntar
  //0 - perguntar se quer remover o código
  function limpaCodigoP(total, total_sem_promo, cod, tipo, erro) {
    if(tipo == 1) {
      $.post(_includes_path+"carrinho-rpc.php", {op:"carregaCodigoPromoTotal", total:total, total_sem_promo:total_sem_promo, cod:cod, elim:1}, function(data) {
        <?php if($totalRows_rsCarCodProm > 0) { ?>
          window.open('carrinho.php?err='+erro, '_parent');
        <?php } else { ?>
          errosCodigoP(erro);
        <?php } ?>
      });
    }
    else {
      ntg_confirm(
        {
          type: 'info',
          title: "<?php echo $Recursos->Resources["codigo_promo_remover"]; ?>",
          html: "<?php echo $Recursos->Resources["codigo_promo_remover_txt"]; ?>",
          showCloseButton: true,
          showCancelButton: true,
          cancelButtonText: "<?php echo $Recursos->Resources["cancelar"]; ?>",
          showConfirmButton: true,
          confirmButtonText: "<?php echo $Recursos->Resources["ok"]; ?>",
        },
        function() { 
          $.post(_includes_path+"carrinho-rpc.php", {op:"carregaCodigoPromoTotal", total:total, total_sem_promo:total_sem_promo, cod:cod, elim:1}, function(data) {
            $.post(_includes_path+"carrinho-rpc.php", {op:"listagemCarrinho"}, function(data) {
              $('.carrinho_list').html(data);
            });
          });
        },
        function() {
          return false
        },
        "",
        ""
      );
    }
  }

  function errosCodigoP(erro) {
    if(erro == 1) {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_2"]; ?>");
    }
    else if(erro == 2) {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_3"]; ?>");
    }
    else if(erro == 4) {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_6"]; ?>");
    }
    else if(erro == 5) {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_7"]; ?>");
    }
    else if(erro == 7) {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_9"]; ?>");
    }
    else if(erro == 8) {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_10"]; ?>");
    }
    else {
      ntg_error("<?php echo $Recursos->Resources["codigo_promocional_mensagem_5"]; ?>");
    }
  }

  <?php if($_GET['err'] != '') { ?>
    $(window).on('load', function(){
      var erro = '<?php echo $_GET['err']; ?>';

      errosCodigoP(erro);

      window.history.replaceState('Object', document.title, 'carrinho.php');
    });
  <?php } ?>

<?php } ?>

<?php if(isset($_GET['rc']) && $_GET['rc'] == 1) { ?>
  $(window).on('load', function() {
    window.history.replaceState('Object', document.title, 'carrinho.php');
  });
<?php } ?>
</script>


<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>