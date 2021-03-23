<?php
require_once('linguasLG.php'); 
$extensao = $Recursos->Resources["extensao"];

$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();
DB::close();

$title = $row_rsMeta["title"];
$description = $row_rsMeta["description"];
$keywords = $row_rsMeta["keywords"];

$code = $_GET['v'];

$query_rsUser="SELECT id FROM clientes WHERE cod_recupera=:code AND validado='1' AND ativo='1'";
$rsUser = DB::getInstance()->prepare($query_rsUser);
$rsUser->bindParam(':code', $code, PDO::PARAM_STR, 5);
$rsUser->execute();
$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);
$totalRows_rsUser = $rsUser->rowCount();
DB::close();

if($totalRows_rsUser == 0 && $_GET['error'] != 1 && $_GET['inserido'] != 1) { 
	header("Location: index.php");
	exit();
}

$menu_sel = "recupera";

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>"><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Recursos->Resources["charset"];?>" />
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
	<?php if($title) { echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); } else { echo $Recursos->Resources["pag_title"]; } ?>
</title>
<?php if($description) { ?>
	<META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php if($keywords != "") { ?>
	<META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
<?php } ?>
<?php include_once('codigo_antes_head.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
	<div id="loader">
	
	</div>
</div>
<!--Preloader-->
<div class="mainDiv">
	<div class="row1">
		<div class="div_table_cell">
			<?php include_once("header.php");?>
			<nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
				<div class="row">
					<div class="column">
						<ul class="breadcrumbs">
							<li class="disabled"><?php echo $Recursos->Resources["bread_tit"]; ?></li>
							<li><a href="<?php echo get_meta_link(1); ?>"><?php echo $Recursos->Resources["home"]; ?></a></li>
							<li><a href="login.php"><?php echo $Recursos->Resources["login"]; ?></a></li>
							<li>
								<?php echo $Recursos->Resources["recuperar_password"]; ?>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div class="login_container">
				<div class="row content align-center">
					<div class="login_divs small-12 xxmedium-6 column">
						<div class="div_100 text-left">
							<form name="form_recupera2" id="form_recupera2" method="post" onSubmit="return validaForm('form_recupera2')" autocomplete="off" novalidate action="">
								<h2 class="list_tit"><?php echo $Recursos->Resources["recuperar_password"]; ?></h2>
								<?php if(isset($_GET['inserido']) && $_GET['inserido'] == 1) { ?>
									<div class="textos"><?php echo $Recursos->Resources["recuperar_msg_sucesso"]; ?></div>
								<?php } else if(isset($_GET['error']) && $_GET['error'] == 1) { ?>
									<div class="textos"><?php echo $Recursos->Resources["recuperar_msg_erro"]; ?></div>
								<?php } else { ?>
								<div class="div_100" style="margin-top: 2rem">
										<div class="inpt_holder">
											<label class="inpt_label" for="<?php echo $form_recupera['password']; ?>"><strong><?php echo $Recursos->Resources["password"]; ?></strong></label><!--
											--><input required class="inpt confirm" type="password" id="<?php echo $form_recupera['password']; ?>" name="<?php echo $form_recupera['password']; ?>"/>
											<div class="passwordToggler" onClick="changePassType(this)"></div>
										</div>
										<div class="inpt_holder">
											<label class="inpt_label" for="password2"><strong><?php echo $Recursos->Resources["ar_password_conf"]; ?></strong></label><!--
											--><input required class="inpt cod_confirm" type="password" id="password2" name="password2"/>
											<div class="passwordToggler" onClick="changePassType(this)"></div>
										</div>

										<input type="hidden" name="<?php echo $form_recupera['cliente']; ?>" id="<?php echo $form_recupera['cliente']; ?>" value="<?php echo $row_rsUser['id']; ?>">

										<button class="button invert has_marg" type="submit"><?php echo $Recursos->Resources['enviar'];?></button>
										<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
										<input type="hidden" name="MM_insert" value="form_recupera2" />
										<input type="hidden" name="titulo_pag" id="titulo_pag_recupera2" value="<?php echo $title; ?>" />
										<input type="text" name="form_hidden" id="form_hidden_recupera2" class="inpt hidden" value="" />
									</div>
								<?php } ?> 
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once('footer.php'); ?>
</div>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>