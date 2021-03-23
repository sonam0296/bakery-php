<?php
if (!isset($_SESSION)) {
  session_start();
}

$lang=$_SESSION["LANG"];

//echo file_get_contents('http://cookies.netgocio.pt/politica-de-cookies.php?lang='.$lang);
?>