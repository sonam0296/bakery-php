<?php
// *** Logout the current user.
$logoutGoTo = "index.php";

if (!isset($_SESSION)) {
  session_start();
}
//$_COOKIE['MM_MYPname']="";
setcookie("SITE_user", '', 0, '/', '', $cookie_secure, true);
setcookie("SITE_pass", '', 0, '/', '', $cookie_secure, true);	
if ($logoutGoTo != "") {
	header("Location: $logoutGoTo");
	exit;
}
?>