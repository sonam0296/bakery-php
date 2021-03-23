<?php
// $path = explode("/", dirname(__DIR__));
// unset($path[count($path) -1]);
// $path = implode($path, "/");

$path = explode('\\', dirname(__DIR__));

unset($path[count($path) -1]);

$path = implode($path, "/");

require_once($path."/Connections/connADMIN.php");
?>

<?php



if (!isset($_SESSION)) {

  session_start();

}



function notify() {

	$insertSQL = "UPDATE acesso SET notify=0 WHERE username=:nome";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsInsert->execute();

	DB::close();

}



function get_activity() {

	$data = date('Y-m-d');

	$hora = date('H:i:s');

	$data_final = $data." ".$hora;



	$query_rsLL = "SELECT last_login, notif_from FROM acesso WHERE username=:nome";

	$rsLL = DB::getInstance()->prepare($query_rsLL);

	$rsLL->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsLL->execute();

	$row_rsLL = $rsLL->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLL = $rsLL->rowCount();

	DB::close();



	$last_login = $row_rsLL['last_login'];

	$notif_from = $row_rsLL['notif_from'];



	$query_rsLoginEnc = "SELECT e.* FROM encomendas e WHERE e.data>=:notif_from AND e.data<=:data_final";

	$rsLoginEnc = DB::getInstance()->prepare($query_rsLoginEnc);

	$rsLoginEnc->bindParam(':data_final', $data_final, PDO::PARAM_STR, 5);

	$rsLoginEnc->bindParam(':notif_from', $notif_from, PDO::PARAM_STR, 5);

	$rsLoginEnc->execute();

	// $row_rsLoginEnc = $rsLoginEnc->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLoginEnc = $rsLoginEnc->rowCount();

	DB::close();



	$query_rsLoginTick = "SELECT t.* FROM tickets t WHERE t.data>=:notif_from AND t.data<=:data_final";

	$rsLoginTick = DB::getInstance()->prepare($query_rsLoginTick);

	$rsLoginTick->bindParam(':data_final', $data_final, PDO::PARAM_STR, 5);

	$rsLoginTick->bindParam(':notif_from', $notif_from, PDO::PARAM_STR, 5);

	$rsLoginTick->execute();

	// $row_rsLoginTick = $rsLoginTick->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLoginTick = $rsLoginTick->rowCount();

	DB::close();



	$query_rsLoginCli = "SELECT c.* FROM clientes c WHERE c.data_registo>=:notif_from AND c.data_registo<=:data_final";

	$rsLoginCli = DB::getInstance()->prepare($query_rsLoginCli);

	$rsLoginCli->bindParam(':data_final', $data_final, PDO::PARAM_STR, 5);

	$rsLoginCli->bindParam(':notif_from', $notif_from, PDO::PARAM_STR, 5);

	$rsLoginCli->execute();

	// $row_rsLoginCli = $rsLoginCli->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLoginCli = $rsLoginCli->rowCount();

	DB::close();



	$query_rsLastLogin = "SELECT n_enc, n_tick, n_cli FROM acesso WHERE username=:nome";

	$rsLastLogin = DB::getInstance()->prepare($query_rsLastLogin);

	$rsLastLogin->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsLastLogin->execute();

	$row_rsLastLogin = $rsLastLogin->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLastLogin = $rsLastLogin->rowCount();

	DB::close();



	$arr = array($totalRows_rsLoginEnc, $totalRows_rsLoginTick, $totalRows_rsLoginCli, $row_rsLastLogin, $notif_from);



	return $arr;

}



function get_activity_login() {

	$data = date('Y-m-d');

	$hora = date('H:i:s');



	$data_final = $data." ".$hora;



	$insertSQL = "UPDATE acesso SET last_login=:data, notif_from=:data WHERE username=:nome";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':data', $data_final, PDO::PARAM_STR, 5);

	$rsInsert->execute();

	DB::close();



	$query_rsLastActivity = "SELECT last_activity, last_login FROM acesso WHERE username=:nome";

	$rsLastActivity = DB::getInstance()->prepare($query_rsLastActivity);

	$rsLastActivity->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsLastActivity->execute();

	$row_rsLastActivity = $rsLastActivity->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsLastActivity = $rsLastActivity->rowCount();

	DB::close();



	$last_activity = $row_rsLastActivity['last_activity'];

	$last_login = $row_rsLastActivity['last_login'];



	$query_rsActivityEnc = "SELECT e.* FROM encomendas e WHERE e.data>=:last_activity AND e.data<=:last_login";

	$rsActivityEnc = DB::getInstance()->prepare($query_rsActivityEnc);

	$rsActivityEnc->bindParam(':last_login', $last_login, PDO::PARAM_STR, 5);

	$rsActivityEnc->bindParam(':last_activity', $last_activity, PDO::PARAM_STR, 5);

	$rsActivityEnc->execute();

	// $row_rsActivityEnc = $rsActivityEnc->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsActivityEnc = $rsActivityEnc->rowCount();

	DB::close();



	$query_rsActivityTick = "SELECT t.* FROM tickets t WHERE t.data>=:last_activity AND t.data<=:last_login";

	$rsActivityTick = DB::getInstance()->prepare($query_rsActivityTick);

	$rsActivityTick->bindParam(':last_login', $last_login, PDO::PARAM_STR, 5);

	$rsActivityTick->bindParam(':last_activity', $last_activity, PDO::PARAM_STR, 5);

	$rsActivityTick->execute();

	// $row_rsActivityTick = $rsActivityTick->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsActivityTick = $rsActivityTick->rowCount();

	DB::close();



	$query_rsActivityCli = "SELECT c.* FROM clientes c WHERE c.data_registo>=:last_activity AND c.data_registo<=:last_login";

	$rsActivityCli = DB::getInstance()->prepare($query_rsActivityCli);

	$rsActivityCli->bindParam(':last_login', $last_login, PDO::PARAM_STR, 5);

	$rsActivityCli->bindParam(':last_activity', $last_activity, PDO::PARAM_STR, 5);

	$rsActivityCli->execute();

	// $row_rsActivityCli = $rsActivityCli->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsActivityCli = $rsActivityCli->rowCount();

	DB::close();



	$insertSQL = "UPDATE acesso SET n_enc=:n_enc, n_tick=:n_tick, n_cli=:n_cli WHERE username=:nome";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':n_enc', $totalRows_rsActivityEnc, PDO::PARAM_INT, 5);

	$rsInsert->bindParam(':n_tick', $totalRows_rsActivityTick, PDO::PARAM_INT, 5);

	$rsInsert->bindParam(':n_cli', $totalRows_rsActivityCli, PDO::PARAM_INT, 5);

	$rsInsert->execute();

	DB::close();

}



function last_activity() {

	$data = date('Y-m-d');

	$hora = date('H:i:s');



	$data_final = $data." ".$hora;



	$insertSQL = "UPDATE acesso SET last_activity=:last_activity WHERE username=:nome";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':last_activity', $data_final, PDO::PARAM_STR, 5);	

	$rsInsert->execute();

	DB::close();

}





if($_POST['opt'] == 'reset_activity') {

	$data = date('Y-m-d');

	$hora = date('H:i:s');

	$data_final = $data." ".$hora;



	$insertSQL = "UPDATE acesso SET last_activity=:last_activity, notif_from = :last_activity WHERE username=:nome";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsInsert->bindParam(':last_activity', $data_final, PDO::PARAM_STR, 5);	

	$rsInsert->execute();

	DB::close();



	$insertSQL = "UPDATE acesso SET n_enc=0, n_tick=0, n_cli=0 WHERE username=:nome";

	$rsInsert = DB::getInstance()->prepare($insertSQL);

	$rsInsert->bindParam(':nome', $_SESSION['ADMIN_USER'], PDO::PARAM_STR, 5);

	$rsInsert->execute();

	DB::close();

}



?>