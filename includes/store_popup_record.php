  <?php include_once('pages_head.php');?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <?php 

  $query_rsCar = "SELECT * FROM carrinho LEFT JOIN l_pecas_en AS pecas ON (carrinho.produto = pecas.id AND pecas.visivel = 1)";
  $rsCar = DB::getInstance()->prepare($query_rsCar);
  $rsCar->execute();
  $row_rsCar = $rsCar->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsCar = $rsCar->rowCount();

  $query_rsP_Event = "SELECT * FROM events Where visivel = 1";
  $rsP_event = DB::getInstance()->prepare($query_rsP_Event); 
  $rsP_event->execute();
  $row_rsP_Event = $rsP_event->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP_Event = $rsP_event->rowCount();

    session_start();
    $id = $_POST["data_id"];

    $query_rsP1 = "SELECT * FROM store_locater WHERE id = '".$id."'";
    $rsP1 = DB::getInstance()->prepare($query_rsP1);
    //$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 
    $rsP1->execute();
    $row_rsP1 = $rsP1->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsP1 = $rsP1->rowCount();



      $_SESSION["store_update_name"] = $row_rsP1['b_name'];
      $_SESSION["store_update_id"] = $row_rsP1['id'];
      //$new_store_session = $_SESSION["store_update_name"];

  ?>
 

  <form action="carrinho-comprar.php" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="post" name="form" id="form" novalidate autocomplete="off" nearby-validator onSubmit="return validaForm('form_pickup')">

      <label>Collection Date:</label>
        <?php $current_Date = date('d/m/Y'); ?>
        <?php $date = date('d/m/Y', strtotime(date('d-m-Y'). ' + ' .$row_rsCar['prepare']. ' day')) ?>
         <label style="margin-left: 70px; color: red;" id="myLabel"><?php if($row_rsP_Event["start_event"] == $date) { echo $row_rsP_Event["title"]; } else { echo ""; } ?></label>
        <input type="text" name="data" class="<?php if($row_rsP_Event["start_event"] == $date) { echo "red"; } ?>"   id="<?php echo $form_pickup['datepicker']; ?>"  value="<?php if($_POST["data"] != "") { echo $_POST["date"]; } else { echo ""; } ?>" onchange="datechanged(this)" readonly="readonly">     
       
        <a  onclick="document.getElementById('datepicker').value = ''">clear</a>

        <label>Collection Time:</label>
        <select name="Ptime" id="<?php echo $form_pickup['Ptime']; ?>">
          <option value="10:00 AM">10:00 AM</option>
          <option value="10:30 AM">10:30 AM</option>
          <option>11:00 AM</option>
          <option>11:30 AM</option>
          <option>12:00 PM</option>
          <option>12:30 PM</option>
          <option>01:00 PM</option>
          <option>01:30 PM</option>
          <option>02:00 PM</option>
          <option>04:00 PM</option>
          <option>04:30 PM</option>
          <option>05:00 PM</option>
          <option>05:30 PM</option>
          <option>06:00 PM</option>
          <option>06:30 PM</option>
          <option>07:00 PM</option>
        </select>

        <table border="1" width="300px">
                <tr> 
                    <td>Contact Details</td>
                </tr>
              
                <tr> 
                    <td><?php echo $row_rsP1['b_name']; ?></td>
                </tr>
                 <tr> 
                    <td><?php echo $row_rsP1['Sreet']; ?>, <?php echo $row_rsP1['city']; ?>, <?php echo $row_rsP1['country']; ?></td>
                </tr>
                 <tr> 
                    <td><?php echo $row_rsP1['phone']; ?></td>
                </tr>
                 <tr> 
                    <td><?php echo $row_rsP1['email']; ?></td>
                </tr>
                 <tr> 
                    <td> 
              
                    <input type="hidden" class="inpt inpt_conto" type="text" id="<?php echo $form_pickup['new_store_name']; ?>" name="new_store_name" value="<?php echo $row_rsP1['b_name']; ?>" />

                    <input type="hidden" class="inpt inpt_conto" type="text" id="new_store_id" name="new_store_id" value="<?php echo $row_rsP1['id']; ?>" />
                
                     <button type="submit" name="update" id="update" class="button-big uppercase btn-enviar">NEXT</button>
                    </td>
                  
                </tr>          
        </table>
      </form>
    <div id="googleMap" style="width:100%;height:400px;"></div>

    <script>
function myMap() {

const myLatLng = { lat: <?php echo $row_rsP1['lat']; ?>, lng: <?php echo $row_rsP1['lng']; ?> };
        const map = new google.maps.Map(document.getElementById("googleMap"), {
          zoom: 16,
          center: myLatLng,
        });
        new google.maps.Marker({
          position: myLatLng,
          map,
          title: "Hello World!",
        });


/*
var mapProp= {
  center:new google.maps.LatLng("52.493860", "-1.833330"),
  zoom:16,
};
var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
 new google.maps.Marker({
      position: mapProp,
      map,
      title: "Hello World!",
    });*/

}
</script>
<?php include_once('codigo_antes_body.php'); ?>
 <script>
      $(function () {
        $("#datepicker").datepicker(
        {
          dateFormat: "dd-mm-yy",
          language: 'en',
          minDate: 'yy/mm/dd <?php if($row_rsCar['prepare'] == "") { echo ""; } else
          { echo $row_rsCar['prepare']; } ?>'
        });
      });
  </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYw1qIZUqAK4lfkB8dLIOK0XOVU9e66xE&callback=myMap"></script>