<?php require_once("../Connections/connADMIN.php"); ?>
<?php

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if(isset($_GET["l"]) && $_GET["l"] == 1) {
	include_once("admin/activity-rpc.php");
	last_activity();
}

if(isset($_SESSION['lang']) && $_SESSION['lang'] !== "") {
	include_once(ROOTPATH_CONSOLA."linguas/".$_SESSION['lang'].".php");
	$lingua_consola = $_SESSION['lang'] ;
}
else {
	include_once(ROOTPATH_CONSOLA."linguas/pt.php");
	$lingua_consola = "es";
}
$RecursosCons = new RecursosCons();

$username=$_SESSION["ADMIN_USER"];

$query_rsUser = "SELECT * FROM acesso WHERE username=:username AND activo='1'";
$rsUser = DB::getInstance()->prepare($query_rsUser);
$rsUser->bindParam(":username", $username, PDO::PARAM_STR, 5);
$rsUser->execute();
$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);
$totalRows_rsUser = $rsUser->rowCount();
DB::close();

if($username=="" || $totalRows_rsUser==0){
	header("Location: ".ROOTPATH_HTTP_CONSOLA."index.php");	
	exit();
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form14")) {
	
	$password=$_POST["password"];
	
	$password = hash("sha256", $password); //encriptar password inserida
	  
	$password_db=$row_rsUser["password"];
	$password_salt_db=$row_rsUser["password_salt"];

	$password_db_final = hash("sha256", $password_salt_db . $password_db);
	
	$password_final = hash("sha256", $password_salt_db . $password); //aplica salt à password inserida
	
	if($password_final==$password_db){

		$_SESSION["ADMIN_PASS"]=$password_final;


		header("Location: admin/index.php" );
		
	} else {
		
		header("Location: index_lock.php?error=1" );	
		
	}
	
}

?>
<?php include_once("admin/inc_head_1.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/admin/pages/css/lock.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once("admin/inc_head_2.php"); ?>
<style type="text/css">
html, body {
	height: 100%;
}
.page-lock {
	margin-top: 0 !important;
}
</style>
<!-- BEGIN BODY -->
<body>
<div style="position:absolute; top:50%; left:50%; margin-top:-241px; margin-left:-225px;">
<div class="page-lock">
	<div class="page-body">
		<div class="lock-head">
			 <?php echo $RecursosCons->RecursosCons['sessao_bloqueada']; ?>
		</div>
        <div class="alert alert-danger <?php if(isset($_GET["error"]) && $_GET["error"]==1) { ?>display-show<?php } else { ?>display-hide<?php } ?>">
            <button class="close" data-close="alert"></button>
            <span>
            <?php echo $RecursosCons->RecursosCons['password_incorreta']; ?> </span>
        </div>
		<div class="lock-body">
			<div class="pull-left lock-avatar-block">
				<img src="imgs/user/<?php echo $row_rsUser["imagem1"]; ?>" width="110" class="lock-avatar">
			</div>
			<form name="form14" id="form14" class="lock-form pull-left" method="post">
				<h4><?php echo $row_rsUser["nome"]; ?></h4>
				<div class="form-group">
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="<?php echo $RecursosCons->RecursosCons['cli_password']; ?> " name="password"/>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success uppercase"><?php echo $RecursosCons->RecursosCons['login']; ?> </button>
				</div>
                <input type="hidden" name="MM_insert" value="form14" />
			</form>
		</div>
		<div class="lock-bottom">
			<a href="logout2.php"><?php echo $RecursosCons->RecursosCons['nao_e_txt']; ?>  <?php echo $row_rsUser["nome"]; ?>?</a>
		</div>
	</div>
</div>
<?php include_once("admin/inc_footer_1.php"); ?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once("admin/inc_footer_2.php"); ?>
<script>
jQuery(document).ready(function() {    
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init();
});
</script></div>
</body>
<!-- END BODY -->
</html>