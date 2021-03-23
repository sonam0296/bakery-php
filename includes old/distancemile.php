<?php 
if($SESSION["store_update_name"] != "")
{
$query_rsMeta = "SELECT * FROM store_locater WHERE b_name = ".$SESSION["store_update_name"]."";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();
}
else {
$query_rsMeta = "SELECT * FROM store_locater WHERE b_name = '".$_SESSION["store_name"]."'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
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

  $all_repl=calculateDistance($row_rsMeta['pincode'], $_POST['postal_code']);
  echo json_encode(array('lag_key' => $all_repl));

?>