<?php $is_cron_file = 1; require_once('../Connections/connADMIN.php'); ?>
<?php //ini_set("display_errors", "1");

if(isset($_GET['op']) && $_GET['op'] == "chrono_update") {
  //$url="http://83.240.239.170:7790/ChronoWSB2CPointsv3/GetB2CPoints_v3Service?wsdl";
  $url = "https://83.240.239.170:7554/ChronoWSB2CPointsv3/GetB2CPoints_v3Service?wsdl";

  $client = new SoapClient($url, array("trace" => 1, "exception" => 0));

  $result = $client->getPointList_V3();

  if($result->return->lB2CPointsArr && !empty($result->return->lB2CPointsArr)){

    $insertSQL = "TRUNCATE TABLE chronopost_pickme";
    $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
    $rsInsertSQL->execute();

    foreach($result->return->lB2CPointsArr as $message) {
      $number = $message->Number;
      /*$name=utf8_decode(utf8_decode($message->Name));
      $address=utf8_decode(utf8_decode($message->Address));
      $postal_code=utf8_decode(utf8_decode($message->PostalCode));
      $location=utf8_decode(utf8_decode($message->PostalCodeLocation));*/

      $name = utf8_decode($message->Name);
      $address = utf8_decode($message->Address);
      $postal_code = utf8_decode($message->PostalCode);
      $location = utf8_decode($message->PostalCodeLocation);

      $insertSQL = "INSERT INTO chronopost_pickme (pickme_id, name, address, postal_code, location) VALUES (:pickme_id, :name, :address, :postal_code, :location)";
      $rsInsert = DB::getInstance()->prepare($insertSQL); 
      $rsInsert->bindParam(':pickme_id', $number, PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':name', $name, PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':address', $address, PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':postal_code', $postal_code, PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':location', $location, PDO::PARAM_STR, 5);
      $rsInsert->execute();
      DB::close();
    }
  }

  echo "Feito!";
}
?>