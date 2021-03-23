<?php require_once('Connections/connADMIN.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

//ini_set('display_errors',1);
 
try {
	$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
	$rsMeta = DB::getInstance()->prepare($query_rsMeta);
    $rsMeta->execute();
    $row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsMeta = $rsMeta->rowCount();
	DB::close();

	$title = $row_rsMeta["title"];
	$description = $row_rsMeta["description"];
	$keywords = $row_rsMeta["keywords"];

} catch(PDOException $e){
echo $e->getMessage();
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");

$faz_reload=0;

$produto_com_portes_gratis=0;
$class_carrinho = Carrinho::getInstance();
$carrinho_session=$_COOKIE[CARRINHO_SESSION];
$empty = $class_carrinho->isEmpty();
$total = $total_final = $total_final_sem_promo = $class_carrinho->precoTotal();
$row_rsCliente = $class_user->isLogged();

$menu_sel = "carrinho";
?>
<!DOCTYPE html>
<html lang="<?php if($lang == "uk") echo "en"; else echo "pt"; ?>"><head>
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
<link href='<?php echo ROOTPATH_HTTP; ?>css/carrinho.css' rel='stylesheet' type='text/css'>
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
                         <nav class="row" aria-label="You are here:" role="navigation">
                            <div class="column">
                                <ul class="row collapse carrinho_nav">
                                    <li class="column active"><?php echo $Recursos->Resources["carrinho_contacto"]; ?></li><!--
                                    --><li class="column"><?php echo $Recursos->Resources["carrinho_contacto2"]; ?></li>
                                </ul>
                            </div>
                        </nav>
                        
                        <form action="carrinho-contacto2.php" class="row align-right carrinho_list" id="comprar2" name="comprar2" method="post" autocomplete="off" novalidate>
                            <div class="small-12 column">
                            	<?php if($empty>0){ ?>
                                <div class="row collapse head">
                                    <div class="small-12 xsmall-12 xxsmall-7 medium-6 large-6 column"><h3><?php echo $Recursos->Resources["cart_prod"]; ?></h3></div>
                                    <div class="show-for-medium medium-3 large-2 column text-right"><h3><?php echo $Recursos->Resources["cart_qtd"]; ?></h3></div>
                                    <div class="show-for-xxsmall xxsmall-3 medium-2 large-3 column text-right"></div>
                                    <div class="show-for-xxsmall xxsmall-2 medium-1 column text-center"><h3><?php echo $Recursos->Resources["cart_delete"]; ?></h3></div>
                                </div>
                                <?php } ?>
                                <div class="div_100">
	                                <?php $listagem_cart = $class_carrinho->carrinhoDivs("carrinho"); ?>
                                </div>
                            </div>
                            <?php if($empty>0){ ?>
                            <div class="small-12 column">
                                <div class="head" style="margin-top:2rem">
                                    <h3 class="comprar_tit marg"><?php echo $Recursos->Resources["obs_encomenda"]; ?></h3>
                                </div>
                                <textarea class="carrinho_inpt" name="observacoes" id="observacoes"></textarea>
                            </div>
                            <div class="small-12 column">
                            	<div class="row collapse">
                                	<div class="small-12 medium-6 column">
                                        <a class="carrinho_btn disabled " href="<?php echo CARRINHO_VOLTAR; ?>"><?php echo $Recursos->Resources["fazer_compra2"]; ?></a>
                                    </div>
                                    <div class="small-12 medium-6 column">
                                        <?php if($row_rsCliente!=0){?><button type="submit" class="carrinho_btn"><?php echo $Recursos->Resources["carrinho_contacto"]; ?></a><?php } ?>
                                        <?php if($row_rsCliente==0){?><a class="carrinho_btn" href="login.php?carr=1"><?php echo $Recursos->Resources["carrinho_contacto"]; ?></a><?php } ?>
                                    </div>   
                                </div>
                            </div>
                            <?php } ?>
                        </form>                        
                    </div> 
            	</div>
            </div>
        </div>
    </div>
	<?php include_once('footer.php'); ?>    
</div>
<script>

<?php if($faz_reload == 1) { ?>
$(document).ready(function(e) {
    location.reload();
});
<?php } ?>

<?php if(isset($_GET['erro']) && $_GET['erro'] == 1) { ?>
ntg_error("<?php echo $Recursos->Resources["carrinho_erro1"]; ?>");    
<?php } ?>

<?php if(isset($_GET['erro']) && $_GET['erro'] == 2) { ?>
ntg_error("<?php echo $Recursos->Resources["carrinho_erro2"]; ?>");    
<?php } ?>
</script>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>