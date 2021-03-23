<?php require_once('Connections/connADMIN.php'); ?>
  <?php 

  if(!empty($_SESSION["store_update_name"]))
  {
    session_destroy();
  }
    //session_destroy();

    $session_name = $_POST["session_name"];
   

    $id = $_POST["data_id"];

    $query_rsP1 = "SELECT * FROM store_locater WHERE id = '".$id."'";
    $rsP1 = DB::getInstance()->prepare($query_rsP1);
    //$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 
    $rsP1->execute();
    $row_rsP1 = $rsP1->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsP1 = $rsP1->rowCount();

    $query_rsP2 = "SELECT * FROM store_locater WHERE b_name = '".$session_name."'";
    $rsP2 = DB::getInstance()->prepare($query_rsP2);
    //$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 
    $rsP2->execute();
    $row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsP2 = $rsP2->rowCount();

    $start_unserilize  = $row_rsP1["start_time"];

    $close_unserilize  = $row_rsP1["close"];

    $unserialize_strat = unserialize($start_unserilize);

    $unserialize_close = unserialize($close_unserilize); 

     $_SESSION["store_name"] = $row_rsP2["b_name"];
     $_SESSION["store_id"] = $row_rsP2["id"];

    $mondaycheck    = date('h:i A', strtotime($unserialize_strat["monday"]));

    $tuesdaycheck   = date('h:i A', strtotime($unserialize_strat["tuesday"]));

    $wednesdaycheck = date('h:i A', strtotime($unserialize_strat["wednesday"]));

    $thursdaycheck  = date('h:i A', strtotime($unserialize_strat["thursday"]));

    $fridaycheck    = date('h:i A', strtotime($unserialize_strat["friday"]));

    $saturdaycheck  = date('h:i A', strtotime($unserialize_strat["saturday"]));

    $sundaycheck    = date('h:i A', strtotime($unserialize_strat["sunday"]));

    $m_check_end    = date('h:i A', strtotime($unserialize_strat["monday_end"]));

    $t_check_end    = date('h:i A', strtotime($unserialize_strat["tuesday_end"]));

    $w_check_end    = date('h:i A', strtotime($unserialize_strat["wednesday_end"]));

    $th_check_end   = date('h:i A', strtotime($unserialize_strat["thursday_end"]));

    $f_check_end    = date('h:i A', strtotime($unserialize_strat["friday_end"]));

    $s_check_end    = date('h:i A', strtotime($unserialize_strat["saturday_end"]));

    $su_check_end   = date('h:i A', strtotime($unserialize_strat["sunday_end"]));

    $m_check_close  = $unserialize_close["monday_close"];

    $t_check_close  = $unserialize_close["tuesday_close"];

    $w_check_close  = $unserialize_close["wednesday_close"];

    $th_check_close = $unserialize_close["thursday_close"];

    $f_check_close  = $unserialize_close["friday_close"];

    $s_check_close  = $unserialize_close["saturday_close"];

    $su_check_close = $unserialize_close["sunday_close"];

  ?>
    <?php echo $_SESSION["store_name"]; ?>
    <?php if( $_SESSION["store_name"] == "") {?>
    <form action="#" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="post" name="form" id="form" novalidate autocomplete="off" nearby-validator>
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
                        <button id="<?php echo $row_rsP1['b_name']; ?>" name="confirm_ajax" value="submit" class="confirm_ajax button-big uppercase btn-enviar">Confirm</button>
                    </td>        
                </tr>          
        </table>
      </form>
    <div id="googleMap" style="width:100%;height:400px;"></div>
     Opening Hours: <hr>
     Monday:  <?php if($m_check_close == 1){ echo "Close"; } else { echo $mondaycheck; ?> to <?php echo $m_check_end;} ?><br>
     Tuesday: <?php if($t_check_close == 1){ echo "Close"; } else { echo $tuesdaycheck; ?> to <?php echo $t_check_end;} ?><br>
     Wednesday: <?php if($w_check_close == 1){ echo "Close"; } else { echo $wednesdaycheck; ?> to <?php echo $w_check_end;} ?><br>
     Thursday  : <?php if($th_check_close == 1){ echo "Close"; } else { echo $thursdaycheck; ?> to <?php echo $th_check_end;} ?><br>
     Friday    : <?php if($f_check_close == 1){ echo "Close"; } else { echo $fridaycheck; ?> to <?php echo $f_check_end;} ?><br>
     Saturday  : <?php if($s_check_close == 1){ echo "Close"; } else { echo $saturdaycheck; ?> to <?php echo $s_check_end;} ?><br>
     Sunday    : <?php if($su_check_close == 1){ echo "Close"; } else { echo $sundaycheck; ?> to <?php echo $su_check_end;} ?> 
  <?php } else { ?>
    <form action="#" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="post" name="form" id="form" novalidate autocomplete="off" nearby-validator>
       <table border="1" width="300px">
                <tr> 
                    <td>Contact Details</td>
                </tr>
              
                <tr> 
                    <td><?php echo $row_rsP2['b_name']; ?></td>
                </tr>
                 <tr> 
                    <td><?php echo $row_rsP2['Sreet']; ?>, <?php echo $row_rsP2['city']; ?>, <?php echo $row_rsP2['country']; ?></td>
                </tr>
                 <tr> 
                    <td><?php echo $row_rsP2['phone']; ?></td>
                </tr>
                 <tr> 
                    <td><?php echo $row_rsP2['email']; ?></td>
                </tr>
                 <tr> 
                    <td>   
                        <a class="carrinho_btn reverse" href="<?php echo CARRINHO_VOLTAR; ?>"><?php echo $Recursos->Resources["carrinho_btn_prev"]; ?></a>
                    </td>
                  
                </tr>          
        </table>
      </form>
    <div id="googleMap" style="width:100%;height:400px;"></div>

    Opening Hours: <?php echo $mondaycheck; ?>
  <?php } ?>  

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
}
</script>

<script type="text/javascript">
  $(document).on('click', '.confirm_ajax', function(event) {
    event.preventDefault();

    var session_name = $(this).attr('id');
    // var x = document.getElementById("Fechshow");
    $.ajax({
    url: 'store_record_multi.php',
    type: 'post',
    dataType: 'html',
    data: {session_name: session_name},
    })
    .done(function(html) {
    console.log("success");
     console.log(session_name);
     setInterval('location.reload()', 500);
    })
    .fail(function() {
    console.log("error");
    })
    .always(function() {
    console.log("complete");
    });

    });
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYw1qIZUqAK4lfkB8dLIOK0XOVU9e66xE&callback=myMap"></script>

