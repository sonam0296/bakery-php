<?php include_once('../inc_pages.php'); ?>
<?php  
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?php

if($_POST['op'] == 'refreshSessions') {	
	$data = date('Y-m-d H:i:s');
	
	$query_rsImgUpdate = "UPDATE config_sessions SET refresh=:data";
  $rsImgUpdate = DB::getInstance()->prepare($query_rsImgUpdate);
  $rsImgUpdate->bindParam(':data', $data, PDO::PARAM_INT, 5);
  $rsImgUpdate->execute();
  DB::close();
}

?>