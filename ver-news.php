<?php

require_once('Connections/connADMIN.php');

$id=$_GET['id'];

$url_site = HTTP_DIR."/";

$codigo = '';
$newsletter_id_historico = 0;

if(isset($_GET['codigo']))
	$codigo = $_GET['codigo'];

if(isset($_GET['hist']))
	$newsletter_id_historico = $_GET['hist'];

if($codigo != '' && $newsletter_id_historico > 0)
	$news_content=file_get_contents($url_site.'consola/admin/newsletter/newsletter-edit.php?id='.$id.'&hist='.$newsletter_id_historico.'&codigo='.$codigo.'&ver=1');
else
	$news_content=file_get_contents($url_site.'consola/admin/newsletter/newsletter-edit.php?id='.$id.'&ver=1');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="background-color:#F1F1F1">
<?php echo $news_content; ?>
</html>