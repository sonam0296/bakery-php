<?php

if (!isset($_SESSION)) {
  session_start();
}

$_SESSION['ADMIN_PASS'] = NULL;
unset($_SESSION['ADMIN_PASS']);

if ($_POST['id'] == 2) {	
	echo "2";
}else{	
	$_SESSION['ADMIN_USER'] = NULL;
	unset($_SESSION['ADMIN_USER']);
	
	echo "1";
}

?>
