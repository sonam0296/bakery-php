<?php require_once('../Connections/connADMIN.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

include_once("admin/funcoes.php");

if(isset($_SESSION['lang']) && $_SESSION['lang'] !== "") {
	include_once(ROOTPATH_CONSOLA."linguas/".$_SESSION['lang'].".php");
	$lingua_consola = $_SESSION['lang'];
}
else {
	include_once(ROOTPATH_CONSOLA."linguas/pt.php");
	$lingua_consola = "pt";
}
$RecursosCons = new RecursosCons();


$query_rsUsers="SELECT * FROM acesso WHERE activo='1'";
$rsUsers = DB::getInstance()->prepare($query_rsUsers);
$rsUsers->execute();
$totalRows_rsUsers = $rsUsers->rowCount();
DB::close();

$id_cliente = 0;
if($totalRows_rsUsers == 0) header("Location: index.php"); 
else {
	if(isset($_GET['v']) && $_GET['v'] != "") {
		while($row_rsUsers = $rsUsers->fetch()) {
			
			$email = $row_rsUsers["email"];
			$salt = $row_rsUsers["password_salt"];
			
			$link = $row_rsUsers["cod_recupera"];
			
			if($link == $_GET['v']) $id_cliente = $row_rsUsers["id"];
		}
	}
}

if(!$id_cliente) header("Location: recuperar.php?error=1"); 

// recuperar password
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form15")) {
	
	$password=$_POST['password'];
	$rep_password=$_POST['rep_password'];
	
	if($rep_password == $password && $id_cliente) {
		
		$hash = hash('sha256', $password);
		$salt = createSalt();				
		
		$password_final=hash('sha256', $salt . $hash);
		
		$insertSQL = "UPDATE acesso SET password=:password, password_salt=:salt, cod_recupera=NULL WHERE id=:id_cliente";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':password', $password_final, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':salt', $salt, PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
		$rsInsert->execute();
		DB::close();
		
		header("Location: index.php?alt=1");

	}
	else {
		$erro_pass = 1;
	}
}

?>
<?php include_once("admin/inc_head_1.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<?php include_once("admin/inc_head_2.php"); ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler"> </div>
<!-- END SIDEBAR TOGGLER BUTTON --> 
<!-- BEGIN LOGIN -->
<div style="position:absolute; top:50%; left:50%; margin-top:-170px; margin-left:-200px;">
	<div class="content"> 
	  <!-- BEGIN FORGOT PASSWORD FORM -->
	  <form action="recuperar.php?v=<?php echo $_GET['v']; ?>" name="form15" id="form15" class="change-pass" method="post">
	    <h3 class="form-title">Recuperar Password </h3>
	    <div class="alert alert-danger display-hide">
	      <button class="close" data-close="alert"></button>
	      <span> Insira o valor dos campos. </span> 
	    </div>
	    <?php if($erro_pass == 1) { ?>
		    <div class="alert alert-danger">
		      <button class="close" data-close="alert"></button>
		      <span> Verifique se as passwords inseridas são iguais. </span> 
		    </div>
		  <?php } ?>
	    <div class="form-group">
	      <div class="input-icon right">
	        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Nova password" name="password" data-required="1" />
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="input-icon right">
	        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Repetir password" name="rep_password" data-required="1" />
	      </div>
	    </div>
	    <div class="form-actions">
	      <button type="submit" class="btn btn-success uppercase pull-right">Alterar</button>
	    </div>
	    <input type="hidden" name="MM_insert" value="form15" />
	  </form>
	  <!-- END FORGOT PASSWORD FORM --> 
	</div>
	<?php include_once("admin/inc_footer_1.php"); ?>
	<!-- BEGIN PAGE LEVEL PLUGINS --> 
	<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script> 
	<!-- END PAGE LEVEL PLUGINS --> 
	<!-- BEGIN PAGE LEVEL SCRIPTS --> 
	<script src="assets/admin/pages/scripts/login.js" type="text/javascript"></script> 
	<!-- END PAGE LEVEL SCRIPTS -->
	<?php include_once("admin/inc_footer_2.php"); ?>
	<script>
	jQuery(document).ready(function() {     
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	Login.init();
	Demo.init();
	});
	</script>
</div>
</body>
<!-- END BODY -->
</html>