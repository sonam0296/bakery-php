<?php
// *** Logout the current user.

$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}

$_SESSION['ADMIN_USER'] = NULL;
unset($_SESSION['ADMIN_USER']);

$_SESSION['ADMIN_PASS'] = NULL;
unset($_SESSION['ADMIN_PASS']);

if ($logoutGoTo != "") {
	header("Location: $logoutGoTo");
	exit;
}

?>