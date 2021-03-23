<?php
if(!isset($_SESSION)) {
  session_start();
}

$loginLG__query="SELECT * FROM linguas WHERE ativo = 1 AND consola = 1";
$loginLG = DB::getInstance()->prepare($loginLG__query);
$loginLG->execute();
$row_rsloginLG = $loginLG->fetchAll();
$loginLG_count = $loginLG->rowCount();
DB::close();

if(isset($_SESSION['lang']) && $_SESSION['lang'] !== "") {
	$lang_login = $_SESSION['lang'];
}
else {
	$lang_login = "pt";
}
?>
<!--BEGIN HEADER -->
<style type="text/css">
.dropdown-toggle-linguas{
	width: 90px;
	text-align: right;
	color:#c6cfda;
	text-transform: uppercase;
}
.dropdown-linguas{
	min-width: 100px !important;
	width: 100px !important;
}
.flag-linguas{
	background-repeat: no-repeat !important;
	background-position: 15% 50% !important;
	background-size: 24px 15px !important;
}
.li-linguas{
	text-transform: uppercase;
	text-align: right;
	padding: 5px;
}
</style>
<div class="page-header -i navbar navbar-fixed-top">
	<div class="page-header-inner">
		<div class="page-logo">
			<a href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>index.php">
				<img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/logo.png" alt="logo" class="logo-default" style="max-width: 130px;"/>
			</a>
			<div class="menu-toggler sidebar-toggler hide">
			</div>
		</div>
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<?php if($loginLG_count > 1) { ?>
					<li class="dropdown dropdown-user">
						<a href="javascript:;" class="dropdown-toggle dropdown-toggle-linguas flag-linguas" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="background-image: url('<?php echo ROOTPATH_HTTP_CONSOLA;?>imgs/linguas/<?php echo $lang_login;?>.png');" title="<?php echo $lang_login; ?>"> <?php echo $lang_login;?> <i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-default dropdown-linguas">
							<?php foreach ($row_rsloginLG as $value) { ?>
								<li class="li-linguas" id="li-<?php echo $value['sufixo']; ?>">
									<a href="javascript:void(null)" onClick="changeLG_login('<?php echo $value['sufixo']; ?>');" class="flag-linguas" style="background-image: url('<?php echo ROOTPATH_HTTP_CONSOLA;?>imgs/linguas/<?php echo $value['sufixo']; ?>.png');"> <?php echo $value['sufixo']; ?></a>
								</li> 
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
$(document).ready(function() {
	$('#li-<?php echo $lang_login; ?>').css('display','none');
	if((<?php echo $loginLG_count ?> > 1)){
		$(".dropdown-linguas").css("opacity", "1");
		$(".fa-angle-down").css("opacity", "1");
	}
	else{
		$(".dropdown-linguas").css("opacity", "0");
		$(".fa-angle-down").css("opacity", "0");
	}
});

function changeLG_login(lng) {	 
	$.post("admin/rpc.php",{op_lg:'changeLG_login', lg_login:lng}, function(data) {
		window.location.reload(true);
	});
}
</script>