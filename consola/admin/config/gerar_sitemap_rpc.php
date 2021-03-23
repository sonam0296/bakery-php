<?php include_once('../inc_pages.php'); ?>
<?php header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");

if(isset($_POST["op"]) && $_POST["op"] == "gera_sitemap") {
	gerarSitemap();
}

?>