<?php 
if($_SESSION["store_update_name"] != "")
{
$query_rsMeta = "SELECT * FROM store_locater WHERE id = 2";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 4";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta2 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 6";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta3 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 7";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta4 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 8";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta5 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 9";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta6 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

}
else {
$query_rsMeta = "SELECT * FROM store_locater WHERE id = 2";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 4";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta2 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 6";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta3 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 7";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta4 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 8";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta5 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$query_rsMeta = "SELECT * FROM store_locater WHERE id = 9";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta6 = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

}
?>

<?php 
// CODE ADD BY VISHAL PRAJAPATI || 01-Des-2020


/*function calculateDistance($lat1, $long1, $lat2, $long2,$unit){

  $theta = $long1 - $long2;
  $dist = (sin(deg2rad($lat1))) * sin(deg2rad($lat2)) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);
  if ($unit == "K") {
      return ($miles * 1.609344);
    }
  if ($unit == "N") {
    return ($miles * 0.8684);
  } 
  /*$result['feet'] = $result['miles']*5280;
  $result['yards'] = $result['feet']/3;
  $result['kilometers'] = $result['miles']*1.609344;
  $result['meters'] = $result['kilometers']*1000;*/
  //echo "hello";
 /* return $miles; 
}
  //echo "<pre>";
  $all_repl=calculateDistance($row_rsMeta['lat'], $row_rsMeta['lng'], $_POST['lat'], $_POST['lag'], "M");
  echo json_encode(array('lag_key' => $all_repl));*/
?>
<?php 
//CODE BY VISHAL PRAJAPATI || 02-12-2020

function calculateDistance($zipcode1, $zipcode2)
{
  $distance_data = file_get_contents(
      'https://maps.googleapis.com/maps/api/distancematrix/json?&origins='.urlencode($zipcode1).'&destinations='.urlencode($zipcode2).'&key=AIzaSyAYw1qIZUqAK4lfkB8dLIOK0XOVU9e66xE'
  );
   

  $data = json_decode($distance_data);

   $distance = 0;
      foreach($data->rows[0]->elements as $road) {
          $distance = $road->distance->value * 0.000621371;
      }

 /*  $mile =   $distance * 0.000621371;*/
   return  round($distance);
}

  $all_repl1=calculateDistance($row_rsMeta['pincode'], $_POST['postal_code']);
  $all_repl2=calculateDistance($row_rsMeta2['pincode'], $_POST['postal_code']);
  $all_repl3=calculateDistance($row_rsMeta3['pincode'], $_POST['postal_code']);
  $all_repl4=calculateDistance($row_rsMeta4['pincode'], $_POST['postal_code']);
  $all_repl5=calculateDistance($row_rsMeta5['pincode'], $_POST['postal_code']);
  $all_repl6=calculateDistance($row_rsMeta6['pincode'], $_POST['postal_code']);

  echo json_encode(array('store_one_pin' => $all_repl1, 'store_two_pin' => $all_repl2, 'store_three_pin' => $all_repl3, 'store_four_pin' => $all_repl4, 'store_five_pin' => $all_repl5, 'store_six_pin' => $all_repl6));

?>