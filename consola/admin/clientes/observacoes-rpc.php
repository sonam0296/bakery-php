<?php include_once('../inc_pages.php'); ?>
<?  
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=iso-8859-1");
?>
<?

if($_POST['op']=="enviaMail"){
	
	$id=$_POST['id'];
	
	if($id>0){
	
		$query_rsP = "SELECT * FROM clientes_obs WHERE id=:id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		DB::close();
		
		if($totalRows_rsP>0){	
		
		//enviar email
		
		}
	
	}
	
}

?>