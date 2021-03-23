<?php require_once('Connections/connADMIN.php'); ?>
<?php

if(isset($_GET['code']) && $_GET['code']!="") {
	$code=$_GET['code'];

	$query_rsP = "SELECT * FROM news_emails_temp WHERE codigo='$code'";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();
	
	if($totalRows_rsP > 0) {
		$email=$row_rsP['email'];
		$nome=$row_rsP['nome'];
		$origem=$row_rsP['origem'];
		$data=date("Y-m-d H:i:s");

		$query_rsP = "SELECT * FROM news_emails WHERE email='$email'";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		DB::close();

		if($totalRows_rsP == 0) {
			$query_rsP2 = "SELECT MAX(id) FROM news_emails";
			$rsP2 = DB::getInstance()->prepare($query_rsP2);
			$rsP2->execute();
			$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsP2 = $rsP2->rowCount();
			DB::close();

			$id=$row_rsP2['MAX(id)']+1;

			$insertSQL = "INSERT INTO news_emails (id, email, nome, data, origem, aceita, visivel, codigo) VALUES (:id, :email, :nome, :data, :origem, '1', '1', :codigo)";
			$Result1 = DB::getInstance()->prepare($insertSQL);
			$Result1->execute(array(':id'=>$id, ':email'=>$email, ':nome'=>$nome, ':data'=>$data, ':origem'=>$origem, ':codigo'=>$code));
			DB::close();

			$insertSQL = "INSERT INTO news_emails_listas (email, lista) VALUES (:id, '1')";
			$Result1 = DB::getInstance()->prepare($insertSQL);
			$Result1->execute(array(':id'=>$id));
			DB::close();
		}
		else {
			$id=$row_rsP['id'];

			$insertSQL = "UPDATE news_emails SET visivel = '1', data='$data', origem=:origem, aceita='1', data_remocao=NULL, origem_remocao=NULL, codigo=:codigo WHERE id = :id";
			$Result1 = DB::getInstance()->prepare($insertSQL);
			$Result1->execute(array(':origem' => $origem, ':id' => $id, ':codigo'=>$code));
			DB::close();

			$query_rsP2 = "SELECT id FROM news_emails_listas WHERE email='$id' AND lista='1'";
			$rsP2 = DB::getInstance()->prepare($query_rsP2);
			$rsP2->execute();
			$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsP2 = $rsP2->rowCount();
			DB::close();

			if($totalRows_rsP2==0){
				$insertSQL = "INSERT INTO news_emails_listas (email, lista) VALUES (:id, '1')";
				$Result1 = DB::getInstance()->prepare($insertSQL);
				$Result1->execute(array(':id'=>$id));
				DB::close();
			}
		}

		$insertSQL = "DELETE FROM news_emails_temp WHERE codigo='$code'";
		$Result1 = DB::getInstance()->prepare($insertSQL);
		$Result1->execute();
		DB::close();

		header("Location: index.php?subs=1");
		exit();
	}
}

header("Location: index.php");
exit();
?>