<?php include_once('../inc_pages.php');

if($_GET['op'] == 'exporta_mails') {
	header("Location: data/emails.xlsx");
	exit();
}

?>