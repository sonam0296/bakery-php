<?php include_once("inc_pages.php"); ?>

<?php 
  
$ano_atual = date('Y'); 

$fd = date("Y-m-d",strtotime('monday this week')); 
$ld = date("Y-m-d",strtotime("sunday this week"));

$prevmonth = date('Y-m-d', strtotime('-1 months'));
$prevmonthnot = date('Y-m-d');


$query_rsUser = "SELECT * FROM acesso WHERE acesso.username='$username' AND id='$id_user'";

$rsUser = DB::getInstance()->prepare($query_rsUser);

$rsUser->execute();

$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);

$totalRows_rsUser = $rsUser->rowCount();

DB::close();

$store  = $row_rsUser["store_name"];

if($store == "ADMIN"){ 
//Profit SQL 
$query_rsEncTotalAno = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE txn_id != '' AND  estado!='5' AND data >= '$fd-%' AND data <= '$ld-%'"; 
$rsEncTotalAno = DB::getInstance()->prepare($query_rsEncTotalAno);
$rsEncTotalAno->execute();
$row_rsEncTotalAno = $rsEncTotalAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno = $rsEncTotalAno->rowCount();
DB::close();

$enc_final_ano = number_format(round($row_rsEncTotalAno['valor_c_iva'], 2), 2, ",", ".")."&pound;";

$query_rsEncTotalAno_curryear = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE txn_id != '' AND estado!='5' AND YEAR(data) = YEAR(CURRENT_DATE())"; 
$rsEncTotalAno_curryear = DB::getInstance()->prepare($query_rsEncTotalAno_curryear);
$rsEncTotalAno_curryear->execute();
$row_rsEncTotalAno_curryear = $rsEncTotalAno_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno_curryear = $rsEncTotalAno_curryear->rowCount();
DB::close();

$enc_final_ano_curryear = number_format(round($row_rsEncTotalAno_curryear['valor_c_iva'], 2), 2, ",", ".")."&pound;";

$query_rsEncTotalAno_currmoth = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE txn_id != '' AND estado!='5' AND MONTH(data) = MONTH(CURRENT_DATE())"; 
$rsEncTotalAno_currmoth = DB::getInstance()->prepare($query_rsEncTotalAno_currmoth);
$rsEncTotalAno_currmoth->execute();
$row_rsEncTotalAno_currmoth = $rsEncTotalAno_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno_currmoth = $rsEncTotalAno_currmoth->rowCount();
DB::close();

$enc_final_ano_currmoth = number_format(round($row_rsEncTotalAno_currmoth['valor_c_iva'], 2), 2, ",", ".")."&pound;";

$query_rsEncTotalAno_lastmoth = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE txn_id != '' AND estado!='5' AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))"; 
$rsEncTotalAno_lastmoth = DB::getInstance()->prepare($query_rsEncTotalAno_lastmoth);
$rsEncTotalAno_lastmoth->execute();
$row_rsEncTotalAno_lastmoth = $rsEncTotalAno_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno_lastmoth = $rsEncTotalAno_lastmoth->rowCount();
DB::close();

$enc_final_ano_lastmoth = number_format(round($row_rsEncTotalAno_lastmoth['valor_c_iva'], 2), 2, ",", ".")."&pound;";


//Total Oder 
$query_rsEncomendasAno = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND estado!='5' AND data >= '$fd-%' AND data <= '$ld-%'";
$rsEncomendasAno = DB::getInstance()->prepare($query_rsEncomendasAno);
$rsEncomendasAno->execute();
$row_rsEncomendasAno = $rsEncomendasAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncomendasAno = $rsEncomendasAno->rowCount();
DB::close();

$query_rs_currmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND estado!='5' AND MONTH(data) = MONTH(CURRENT_DATE())";
$rs_currmoth = DB::getInstance()->prepare($query_rs_currmoth);
$rs_currmoth->execute();
$row_rs_currmoth = $rs_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_currmoth = $rs_currmoth->rowCount();
DB::close();

$query_rs_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND estado!='5' AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rs_lastmoth = DB::getInstance()->prepare($query_rs_lastmoth);
$rs_lastmoth->execute();
$row_rs_lastmoth = $rs_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_lastmoth = $rs_lastmoth->rowCount();
DB::close();

$query_rs_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND estado!='5' AND YEAR(data) = YEAR(CURRENT_DATE())";
$rs_curryear = DB::getInstance()->prepare($query_rs_curryear);
$rs_curryear->execute();
$row_rs_curryear = $rs_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_curryear = $rs_curryear->rowCount();
DB::close();

//Total Client 
$query_rsClientesAno = "SELECT COUNT(id) AS total FROM clientes WHERE data_registo LIKE '$ano_atual-%'";
$rsClientesAno = DB::getInstance()->prepare($query_rsClientesAno);
$rsClientesAno->execute();
$row_rsClientesAno = $rsClientesAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno = $rsClientesAno->rowCount();
DB::close();

$query_rsClientesAno_lastmoth = "SELECT COUNT(id) AS total FROM clientes WHERE MONTH( data_registo ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rsClientesAno_lastmoth = DB::getInstance()->prepare($query_rsClientesAno_lastmoth);
$rsClientesAno_lastmoth->execute();
$row_rsClientesAno_lastmoth = $rsClientesAno_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno_lastmoth = $rsClientesAno_lastmoth->rowCount();
DB::close();

$query_rsClientesAno_currmonth = "SELECT COUNT(id) AS total FROM clientes WHERE MONTH(data_registo) = MONTH(CURRENT_DATE())";
$rsClientesAno_currmonth = DB::getInstance()->prepare($query_rsClientesAno_currmonth);
$rsClientesAno_currmonth->execute();
$row_rsClientesAno_currmonth = $rsClientesAno_currmonth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno_currmonth = $rsClientesAno_currmonth->rowCount();
DB::close();

$query_rsClientesAno_curryear = "SELECT COUNT(id) AS total FROM clientes WHERE YEAR(data_registo) = YEAR(CURRENT_DATE())";
$rsClientesAno_curryear = DB::getInstance()->prepare($query_rsClientesAno_curryear);
$rsClientesAno_curryear->execute();
$row_rsClientesAno_curryear = $rsClientesAno_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno_curryear = $rsClientesAno_curryear->rowCount();
DB::close();


//Total Delivery Order
$query_rsTicketsAno = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 1 AND data >= '$fd-%' AND data <= '$ld-%'";
$rsTicketsAno = DB::getInstance()->prepare($query_rsTicketsAno);
$rsTicketsAno->execute();
$row_rsTicketsAno = $rsTicketsAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTicketsAno = $rsTicketsAno->rowCount();
DB::close();

$query_delivery_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 1  AND MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rs_delivery_lastmoth = DB::getInstance()->prepare($query_delivery_lastmoth);
$rs_delivery_lastmoth->execute();
$row_delivery_lastmoth = $rs_delivery_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_delivery_lastmoth = $rs_delivery_lastmoth->rowCount();
DB::close();

$query_delivery_currtmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 1  AND MONTH(data) = MONTH(CURRENT_DATE())";
$rs_delivery_currmoth = DB::getInstance()->prepare($query_delivery_currtmoth);
$rs_delivery_currmoth->execute();
$row_delivery_currmoth = $rs_delivery_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_delivery_currmoth = $rs_delivery_currmoth->rowCount();
DB::close(); 

$query_delivery_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 1  AND YEAR(data) = YEAR(CURRENT_DATE())";
$rs_delivery_curryear = DB::getInstance()->prepare($query_delivery_curryear);
$rs_delivery_curryear->execute();
$row_delivery_curryear = $rs_delivery_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_delivery_curryear  = $rs_delivery_curryear->rowCount();
DB::close();


//Total Pickup Order
$query_rsPickup= "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 0 AND data >= '$fd-%' AND data <= '$ld-%'";
$rsPickup = DB::getInstance()->prepare($query_rsPickup);
$rsPickup->execute();
$row_rsPickup = $rsPickup->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup = $rsPickup->rowCount();
DB::close();

$query_rsPickup_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 0 AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rsPickup_lastmoth = DB::getInstance()->prepare($query_rsPickup_lastmoth);
$rsPickup_lastmoth->execute();
$row_rsPickup_lastmoth = $rsPickup_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup_lastmoth = $rsPickup_lastmoth->rowCount();
DB::close();

$query_rsPickup_currmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 0 AND MONTH(data) = MONTH(CURRENT_DATE())";
$rsPickup_currmoth = DB::getInstance()->prepare($query_rsPickup_currmoth);
$rsPickup_currmoth->execute();
$row_rsPickup_currmoth = $rsPickup_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup_currmoth = $rsPickup_currmoth->rowCount();
DB::close();

$query_rsPickup_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND deliverystatus = 0 AND YEAR(data) = YEAR(CURRENT_DATE())";
$rsPickup_curryear = DB::getInstance()->prepare($query_rsPickup_curryear);
$rsPickup_curryear->execute();
$row_rsPickup_curryear = $rsPickup_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup_curryear = $rsPickup_curryear->rowCount();
DB::close();

//Total Click and collect
$query_rsprepration= "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND prepration = 1 AND data >= '$fd-%' AND data <= '$ld-%'";
$rsprepration = DB::getInstance()->prepare($query_rsprepration);
$rsprepration->execute();
$row_rsprepration = $rsprepration->fetch(PDO::FETCH_ASSOC);
$totalRows_rsprepration = $rsprepration->rowCount();
DB::close();

$query_rsPrepration_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND prepration = 1 AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rsPrepration_lastmoth = DB::getInstance()->prepare($query_rsPrepration_lastmoth);
$rsPrepration_lastmoth->execute();
$row_rsPrepration_lastmoth = $rsPrepration_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPrepration_lastmoth = $rsPrepration_lastmoth->rowCount();
DB::close();

$query_rsPrepration_currmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND prepration = 1 AND MONTH(data) = MONTH(CURRENT_DATE())";
$rsPrepration_currmoth = DB::getInstance()->prepare($query_rsPrepration_currmoth);
$rsPrepration_currmoth->execute();
$row_rsPrepration_currmoth = $rsPrepration_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPrepration_currmoth = $rsPrepration_currmoth->rowCount();
DB::close();

$query_rsPrepration_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE txn_id != '' AND prepration = 1 AND YEAR(data) = YEAR(CURRENT_DATE())";
$rsPrepration_curryear = DB::getInstance()->prepare($query_rsPrepration_curryear);
$rsPrepration_curryear->execute();
$row_rsPrepration_curryear = $rsPrepration_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPrepration_curryear = $rsPrepration_curryear->rowCount();
DB::close();
}

else
{
  $query_rsEncTotalAno = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND  estado!='5' AND data >= '$fd-%' AND data <= '$ld-%'"; 
$rsEncTotalAno = DB::getInstance()->prepare($query_rsEncTotalAno);
$rsEncTotalAno->execute();
$row_rsEncTotalAno = $rsEncTotalAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno = $rsEncTotalAno->rowCount();
DB::close();

$enc_final_ano = number_format(round($row_rsEncTotalAno['valor_c_iva'], 2), 2, ",", ".")."&pound;";

$query_rsEncTotalAno_curryear = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND YEAR(data) = YEAR(CURRENT_DATE())"; 
$rsEncTotalAno_curryear = DB::getInstance()->prepare($query_rsEncTotalAno_curryear);
$rsEncTotalAno_curryear->execute();
$row_rsEncTotalAno_curryear = $rsEncTotalAno_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno_curryear = $rsEncTotalAno_curryear->rowCount();
DB::close();

$enc_final_ano_curryear = number_format(round($row_rsEncTotalAno_curryear['valor_c_iva'], 2), 2, ",", ".")."&pound;";

$query_rsEncTotalAno_currmoth = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND MONTH(data) = MONTH(CURRENT_DATE())"; 
$rsEncTotalAno_currmoth = DB::getInstance()->prepare($query_rsEncTotalAno_currmoth);
$rsEncTotalAno_currmoth->execute();
$row_rsEncTotalAno_currmoth = $rsEncTotalAno_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno_currmoth = $rsEncTotalAno_currmoth->rowCount();
DB::close();

$enc_final_ano_currmoth = number_format(round($row_rsEncTotalAno_currmoth['valor_c_iva'], 2), 2, ",", ".")."&pound;";

$query_rsEncTotalAno_lastmoth = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))"; 
$rsEncTotalAno_lastmoth = DB::getInstance()->prepare($query_rsEncTotalAno_lastmoth);
$rsEncTotalAno_lastmoth->execute();
$row_rsEncTotalAno_lastmoth = $rsEncTotalAno_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncTotalAno_lastmoth = $rsEncTotalAno_lastmoth->rowCount();
DB::close();

$enc_final_ano_lastmoth = number_format(round($row_rsEncTotalAno_lastmoth['valor_c_iva'], 2), 2, ",", ".")."&pound;";


//Total Oder 
$query_rsEncomendasAno = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND data >= '$fd-%' AND data <= '$ld-%'";
$rsEncomendasAno = DB::getInstance()->prepare($query_rsEncomendasAno);
$rsEncomendasAno->execute();
$row_rsEncomendasAno = $rsEncomendasAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncomendasAno = $rsEncomendasAno->rowCount();
DB::close();

$query_rs_currmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND MONTH(data) = MONTH(CURRENT_DATE())";
$rs_currmoth = DB::getInstance()->prepare($query_rs_currmoth);
$rs_currmoth->execute();
$row_rs_currmoth = $rs_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_currmoth = $rs_currmoth->rowCount();
DB::close();

$query_rs_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rs_lastmoth = DB::getInstance()->prepare($query_rs_lastmoth);
$rs_lastmoth->execute();
$row_rs_lastmoth = $rs_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_lastmoth = $rs_lastmoth->rowCount();
DB::close();

$query_rs_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND estado!='5' AND YEAR(data) = YEAR(CURRENT_DATE())";
$rs_curryear = DB::getInstance()->prepare($query_rs_curryear);
$rs_curryear->execute();
$row_rs_curryear = $rs_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rs_curryear = $rs_curryear->rowCount();
DB::close();

//Total Client 
$query_rsClientesAno = "SELECT COUNT(id) AS total FROM clientes WHERE data_registo LIKE '$ano_atual-%'";
$rsClientesAno = DB::getInstance()->prepare($query_rsClientesAno);
$rsClientesAno->execute();
$row_rsClientesAno = $rsClientesAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno = $rsClientesAno->rowCount();
DB::close();

$query_rsClientesAno_lastmoth = "SELECT COUNT(id) AS total FROM clientes WHERE MONTH( data_registo ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rsClientesAno_lastmoth = DB::getInstance()->prepare($query_rsClientesAno_lastmoth);
$rsClientesAno_lastmoth->execute();
$row_rsClientesAno_lastmoth = $rsClientesAno_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno_lastmoth = $rsClientesAno_lastmoth->rowCount();
DB::close();

$query_rsClientesAno_currmonth = "SELECT COUNT(id) AS total FROM clientes WHERE MONTH(data_registo) = MONTH(CURRENT_DATE())";
$rsClientesAno_currmonth = DB::getInstance()->prepare($query_rsClientesAno_currmonth);
$rsClientesAno_currmonth->execute();
$row_rsClientesAno_currmonth = $rsClientesAno_currmonth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno_currmonth = $rsClientesAno_currmonth->rowCount();
DB::close();

$query_rsClientesAno_curryear = "SELECT COUNT(id) AS total FROM clientes WHERE YEAR(data_registo) = YEAR(CURRENT_DATE())";
$rsClientesAno_curryear = DB::getInstance()->prepare($query_rsClientesAno_curryear);
$rsClientesAno_curryear->execute();
$row_rsClientesAno_curryear = $rsClientesAno_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClientesAno_curryear = $rsClientesAno_curryear->rowCount();
DB::close();


//Total Delivery Order
$query_rsTicketsAno = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 1 AND data >= '$fd-%' AND data <= '$ld-%'";
$rsTicketsAno = DB::getInstance()->prepare($query_rsTicketsAno);
$rsTicketsAno->execute();
$row_rsTicketsAno = $rsTicketsAno->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTicketsAno = $rsTicketsAno->rowCount();
DB::close();

$query_delivery_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 1  AND MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rs_delivery_lastmoth = DB::getInstance()->prepare($query_delivery_lastmoth);
$rs_delivery_lastmoth->execute();
$row_delivery_lastmoth = $rs_delivery_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_delivery_lastmoth = $rs_delivery_lastmoth->rowCount();
DB::close();

$query_delivery_currtmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 1  AND MONTH(data) = MONTH(CURRENT_DATE())";
$rs_delivery_currmoth = DB::getInstance()->prepare($query_delivery_currtmoth);
$rs_delivery_currmoth->execute();
$row_delivery_currmoth = $rs_delivery_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_delivery_currmoth = $rs_delivery_currmoth->rowCount();
DB::close(); 

$query_delivery_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 1  AND YEAR(data) = YEAR(CURRENT_DATE())";
$rs_delivery_curryear = DB::getInstance()->prepare($query_delivery_curryear);
$rs_delivery_curryear->execute();
$row_delivery_curryear = $rs_delivery_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_delivery_curryear  = $rs_delivery_curryear->rowCount();
DB::close();


//Total Pickup Order
$query_rsPickup= "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 0 AND data >= '$fd-%' AND data <= '$ld-%'";
$rsPickup = DB::getInstance()->prepare($query_rsPickup);
$rsPickup->execute();
$row_rsPickup = $rsPickup->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup = $rsPickup->rowCount();
DB::close();

$query_rsPickup_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 0 AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rsPickup_lastmoth = DB::getInstance()->prepare($query_rsPickup_lastmoth);
$rsPickup_lastmoth->execute();
$row_rsPickup_lastmoth = $rsPickup_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup_lastmoth = $rsPickup_lastmoth->rowCount();
DB::close();

$query_rsPickup_currmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 0 AND MONTH(data) = MONTH(CURRENT_DATE())";
$rsPickup_currmoth = DB::getInstance()->prepare($query_rsPickup_currmoth);
$rsPickup_currmoth->execute();
$row_rsPickup_currmoth = $rsPickup_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup_currmoth = $rsPickup_currmoth->rowCount();
DB::close();

$query_rsPickup_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND deliverystatus = 0 AND YEAR(data) = YEAR(CURRENT_DATE())";
$rsPickup_curryear = DB::getInstance()->prepare($query_rsPickup_curryear);
$rsPickup_curryear->execute();
$row_rsPickup_curryear = $rsPickup_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPickup_curryear = $rsPickup_curryear->rowCount();
DB::close();

//Total Click and collect
$query_rsprepration= "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND prepration = 1 AND data >= '$fd-%' AND data <= '$ld-%'";
$rsprepration = DB::getInstance()->prepare($query_rsprepration);
$rsprepration->execute();
$row_rsprepration = $rsprepration->fetch(PDO::FETCH_ASSOC);
$totalRows_rsprepration = $rsprepration->rowCount();
DB::close();

$query_rsPrepration_lastmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND prepration = 1 AND  MONTH( data ) = MONTH( DATE_SUB(CURDATE(),INTERVAL 1 MONTH ))";
$rsPrepration_lastmoth = DB::getInstance()->prepare($query_rsPrepration_lastmoth);
$rsPrepration_lastmoth->execute();
$row_rsPrepration_lastmoth = $rsPrepration_lastmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPrepration_lastmoth = $rsPrepration_lastmoth->rowCount();
DB::close();

$query_rsPrepration_currmoth = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND prepration = 1 AND MONTH(data) = MONTH(CURRENT_DATE())";
$rsPrepration_currmoth = DB::getInstance()->prepare($query_rsPrepration_currmoth);
$rsPrepration_currmoth->execute();
$row_rsPrepration_currmoth = $rsPrepration_currmoth->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPrepration_currmoth = $rsPrepration_currmoth->rowCount();
DB::close();

$query_rsPrepration_curryear = "SELECT COUNT(id) AS total FROM encomendas WHERE store_name = '".$store."' AND txn_id != '' AND prepration = 1 AND YEAR(data) = YEAR(CURRENT_DATE())";
$rsPrepration_curryear = DB::getInstance()->prepare($query_rsPrepration_curryear);
$rsPrepration_curryear->execute();
$row_rsPrepration_curryear = $rsPrepration_curryear->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPrepration_curryear = $rsPrepration_curryear->rowCount();
DB::close();
}

$menu_sel='dashboard';
$menu_sub_sel='';

 ?>
<?php include_once("inc_head_1.php"); ?>
<?php include_once("inc_head_2.php"); ?>
<style>
#chart_11 > div > div > a, #chart_10 > div > div > a, #chart_4 > div > div > a, #chartdiv > div > div > a, #chartdiv2 > div > div > a {display:none !important;}
</style>
<body class="<?php echo $body_info; ?> page-sidebar-closed-hide-logo page-container-bg-solid">
<?php include_once("inc_topo.php"); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once("inc_menu.php"); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content">
    
      <?php //include_once("inc_customize.php"); ?>
      <!-- BEGIN PAGE HEADER-->
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="index.php"><?php echo $RecursosCons->RecursosCons['dashboard']; ?></a>
          </li>
        </ul>
      </div>
      <h3 class="page-title">
      <?php echo $RecursosCons->RecursosCons['dashboard']; ?> <small><?php echo $store; ?></small>
      </h3>
      <!-- END PAGE HEADER-->
      <!-- BEGIN DASHBOARD STATS -->
      <!-- START ROW  -->
      <div class="row">
      	 <!-- Currunt Week List -->
        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
          <div class="dashboard-stat red-intense">
            <div class="visual" style="height: 200px;">
              <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $enc_final; ?>
              </div>
              <div class="desc">
                <b>Last Week</b>&nbsp;<?php echo date('Y'); ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong>Profit</strong> - <?php echo $enc_final_ano; ?><br>
                <strong>Order</strong> - <?php echo $row_rsEncomendasAno['total']; ?><br>
                <strong>Client</strong> - <?php echo $row_rsClientesAno['total']; ?><br>
                <strong>Delivery Orders</strong> - <?php echo $row_rsTicketsAno['total']; ?><br>
                <strong>Pickup Order</strong> - <?php echo $row_rsPickup['total']; ?><br>
                <strong>Click And Collect Orders</strong> - <?php echo $row_rsprepration['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>

        <!-- Last Month List -->
         <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
          <div class="dashboard-stat green-haze">
            <div class="visual" style="height: 200px;">
              <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $enc_final; ?>
              </div>
              <div class="desc">
                <b>Last Month</b>&nbsp;<?php echo date('Y'); ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong>Profit</strong> - <?php echo $enc_final_ano_lastmoth; ?><br>
                <strong>Order</strong> - <?php echo $row_rs_lastmoth['total']; ?><br>
                <strong>Client</strong> - <?php echo $row_rsClientesAno_lastmoth['total']; ?><br>
                <strong>Delivery Orders</strong> - <?php echo $row_delivery_lastmoth['total']; ?><br>
                <strong>Pickup Order</strong> - <?php echo $row_rsPickup_lastmoth['total']; ?><br>
                <strong>Click And Collect Orders</strong> - <?php echo $row_rsPrepration_lastmoth['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>

        <!-- Currunt Month List -->
        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
          <div class="dashboard-stat purple-plum">
            <div class="visual" style="height: 200px;">
              <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $enc_final; ?>
              </div>
              <div class="desc">
                <b>Currunt Month</b>&nbsp;<?php echo date('Y'); ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong>Profit</strong> - <?php echo $enc_final_ano_currmoth; ?><br>
                <strong>Order</strong> - <?php echo $row_rs_currmoth['total']; ?><br>
                <strong>Client</strong> - <?php echo $row_rsClientesAno_currmonth['total']; ?><br>
                <strong>Delivery Orders</strong> - <?php echo $row_delivery_currmoth['total']; ?><br>
                <strong>Pickup Order</strong> - <?php echo $row_rsPickup_currmoth['total']; ?><br>
                <strong>Click And Collect Orders</strong> - <?php echo $row_rsPrepration_currmoth['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>

          <!-- Currunt Year List -->
        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
          <div class="dashboard-stat blue-madison">
            <div class="visual" style="height: 200px;">
              <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $enc_final; ?>
              </div>
              <div class="desc">
                <b>This Year</b>&nbsp;<?php echo date('Y'); ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong>Profit</strong> - <?php echo $enc_final_ano_curryear; ?><br>
                <strong>Order</strong> - <?php echo $row_rs_curryear['total']; ?><br>
                <strong>Client</strong> - <?php echo $row_rsClientesAno_curryear['total']; ?><br>
                <strong>Delivery Orders</strong> - <?php echo $row_delivery_curryear['total']; ?><br>
                <strong>Pickup Order</strong> - <?php echo $row_rsPickup_curryear['total']; ?><br>
                <strong>Click And Collect Orders</strong> - <?php echo $row_rsPrepration_curryear['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>

      </div>
      <!-- END ROW  -->

    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once("inc_quick_sidebar.php"); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN."inc_footer_1.php"); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- 3D CHARTS -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/maps/js/continentsLow.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
<!-- 3D CHARTS -->
<?php include_once(ROOTPATH_ADMIN."inc_footer_2.php"); ?>
<script>
var Index = function () {
  return {
    //main function
    init: function () {
      Metronic.addResizeHandler(function () {
        jQuery('.vmaps').each(function () {
          var map = jQuery(this);
          map.width(map.parent().width());
        });
      });
    },

    initCharts: function () {
      if(!jQuery.plot) {
        return;
      }

      function showChartTooltip(x, y, xValue, yValue) {
        $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
          position: "absolute",
          display: "none",
          top: y - 40,
          left: x - 40,
          border: "0px solid #ccc",
          padding: "2px 6px",
          "background-color": "#fff"
        }).appendTo("body").fadeIn(200);
      }

      var data = [];
      var totalPoints = 250;

      // random data generator for plot charts
      function randValue() {}

      var visitors = <?php echo $flot_data_visits; ?>;

      if($('#site_statistics').size() != 0) {
        $('#site_statistics_loading').hide();
        $('#site_statistics_content').show();

        var plot_statistics = $.plot($("#site_statistics"),
          [{
            data: visitors,
            lines: {
              fill: 0.6,
              lineWidth: 0
            },
            color: ["#1F897F"]
          }, {
            data: visitors,
            points: {
              show: true,
              fill: true,
              radius: 5,
              fillColor: "#1F897F",
              lineWidth: 3
            },
            color: "#fff",
            shadowSize: 0
          }],
          {
            xaxis: {
              tickLength: 0,
              tickDecimals: 0,
              mode: "categories",
              min: 0,
              font: {
                lineHeight: 14,
                style: "normal",
                variant: "small-caps",
                color: "#6F7B8A"
              }
            },
            yaxis: {
              ticks: 5,
              tickDecimals: 0,
              tickColor: "#eee",
              font: {
                lineHeight: 14,
                style: "normal",
                variant: "small-caps",
                color: "#6F7B8A"
              }
            },
            grid: {
              hoverable