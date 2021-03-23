<?php include_once('pages_head.php');?>
<h1>CHOOSE STORE FOR COLLECTION</h1>
<style type="text/css">
  
  .pac-container.pac-logo {

    z-index: 99999999  !important;
}

</style>
<?php

$check_pickup_radio = $_POST["doorcheck1"];


$query_rsImagem = "SELECT * FROM imagens_topo";
$rsImagem = DB::getInstance()->query($query_rsImagem);
$row_rsImagem = $rsImagem->fetch(PDO::FETCH_ASSOC);
$totalRows_rsImagem = $rsImagem->rowCount();
DB::close();

$row_rsContactos = $GLOBALS['divs_contactos'];

$query_rscontactos= "SELECT * FROM contactos_pt";
$rscontactos = DB::getInstance()->query($query_rscontactos);
$row_rscontactos = $rscontactos->fetch(PDO::FETCH_ASSOC);
$totalRows_rscontactos = $rscontactos->rowCount();

$GLOBALS['divs_contactos'] = $GLOBALS['divs_contactos'][1];
if($GLOBALS['divs_contactos']['info']) {
  $row_rsContactos = $GLOBALS['divs_contactos']['info'];
}

$row_rsLocais = $GLOBALS['divs_contactos']['subs'];

$locais_gps = 0;
if(!empty($row_rsLocais)) {
  foreach($row_rsLocais as $local) {
    if($local["gps"]) {
      $locais_gps = 1;
      break;
    }
  }
}

$markerW = 73;
$markerH = 94;


$menu_sel = "contactos";


$query_rsP = "SELECT * FROM store_locater WHERE visivel = 1";
$rsP = DB::getInstance()->prepare($query_rsP);
//$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 
$rsP->execute();
$row_rsP = $rsP->fetchAll();
$totalRows_rsP = $rsP->rowCount();

$query_rsP1 = "SELECT * FROM store_locater";
$rsP1 = DB::getInstance()->prepare($query_rsP1);
//$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 
$rsP1->execute();
$row_rsP1 = $rsP1->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsP1 = $rsP1->rowCount();
?>


<main class="page-load contactos contactos_bd_data_page">
 
 <div class="div_100 contactos_cont contactos_cont_data_page">

        <div class="row align-left">

            <div class="small-12 medium-5 column">
                

                <form method="post" name="form">
        
                        <div class="shipping">
                        <div class="form_group_main">
                          <input id="miless" type="hidden"  />  
                          <div class="form_group">
                            <p>Enter your PostCode</p>
                            <div id="locationField">
                                <input
                                  id="autocomplete"
                                 
                                  onFocus="geolocate()"
                                  type="text"
                                 onFocus = "getval();"/>
                              </div>
                          </div>

                            <table id="address">
                              <div class="form_group" style="display: none;">
                                <p>Street address</p>
                                  <input class="field" id="street_number" disabled="true" />
                                <td class="wideField" colspan="2" style="display: none;">
                                  <input class="field" id="route" disabled="true" />
                                </td>
                              </div>
                              <div class="form_group" style="display: none;">
                                <p>City</p>
                                  <input class="field" id="locality" disabled="true" />
                              </div>
                              <div class="form_group">
                               
                                  <input
                                    class="field"
                                    id="administrative_area_level_1"
                                    disabled="true"
                                  style="display: none;"/>
                                <p>Zip code</p>
                                  <input class="field" id="postal_code" disabled="true" onblur = "getval();"/>
                              </div>
                              <div class="form_group" style="display: none;">
                                <p>Country</p>
                                  <input  class="field" id="country" disabled="true" />
                              </div>
                            </table>
                          </div>
                        
                      </div>
                    </form>
                  <div id="value_show">
                   <?php foreach ($row_rsP as $row) { ?>
                    <?php  if($row['id'] == 2) { ?>
                    <label><?php echo $row['b_name']; ?></label>  
                    <a class="store_fetch" id="<?php echo $row['id']; ?>"> 
                      <button class="button-big uppercase btn-enviar" style="background: red !important;">CHOOSE</button>
                    </a><br>
                    <label id="miless1"></label> Miles<br> <HR>
                    <?php } ?>
                    <?php  if($row['id'] == 4) { ?>
                    <label><?php echo $row['b_name']; ?></label> 
                    <a class="store_fetch" id="<?php echo $row['id']; ?>">
                       <button class="button-big uppercase btn-enviar" style="background: red !important;">CHOOSE</button>
                    </a>
                    <br>
                    <label id="miless2"></label> Miles <br> <HR>
                    <?php } ?>
                    <?php  if($row['id'] == 6) { ?>
                    <label><?php echo $row['b_name']; ?></label> 
                    <a class="store_fetch" id="<?php echo $row['id']; ?>"> 
                       <button class="button-big uppercase btn-enviar" style="background: red !important;">CHOOSE</button>
                    </a>
                    <br>
                    <label id="miless3"></label> Miles <br> <HR>
                    <?php } ?>
                    <?php  if($row['id'] == 7) { ?>
                    <label><?php echo $row['b_name']; ?></label> 
                    <a class="store_fetch" id="<?php echo $row['id']; ?>"> 
                       <button class="button-big uppercase btn-enviar" style="background: red !important;">CHOOSE</button>
                     </a>
                    <br>
                    <label id="miless4"></label> Miles <br> <HR>
                    <?php } ?>
                    <?php  if($row['id'] == 8) { ?>
                    <label><?php echo $row['b_name']; ?></label> 
                    <a class="store_fetch" id="<?php echo $row['id']; ?>"> 
                       <button class="button-big uppercase btn-enviar" style="background: red !important;">CHOOSE</button>
                     </a>
                    <br>
                    <label id="miless5"></label> Miles <br> <HR>
                    <?php } ?>
                    <?php  if($row['id'] == 9) { ?>
                    <label><?php echo $row['b_name']; ?></label>
                    <a class="store_fetch" id="<?php echo $row['id']; ?>"> 
                       <button class="button-big uppercase btn-enviar" style="background: red !important;">CHOOSE</button>
                     </a>
                    <br>
                    <label id="miless6"></label> Miles <br> <HR>
                    <?php } ?>
                  <?php } ?>
             </div>

             <br>
            
<!-- 
                <?php foreach ($row_rsP as $row) { ?>
               <a class="store_fetch" id="<?php echo $row['id']; ?>"> 
                <?php if($row['b_name']){ ?>
                <div class="contactos_txt textos"><?php echo $row['b_name']; ?></div>
                <br>
                <?php } ?>

                 <?php if($row['Sreet']){ ?>
                <div class="contactos_txt textos"><?php echo $row['Sreet']; ?>,<br><?php echo $row['city']; ?>,<br><?php echo $row['country']; ?></div>
                <br>
                <?php } ?>

                <?php if($row['phone']){ ?>
                <div class="contactos_txt textos"><?php echo $row['phone']; ?></div>
                <br>
                <?php } ?>
                <?php if($row['email']){ ?>
                    <div class="contactos_txt textos"><?php echo $row['email'];?></div><br><hr>
                <?php }?> 
            </a>
            <?php } ?> -->
           
        </div>
        
         <div id="Fechshow">
     
         </div>
    </div>
    <!-- FUll MAP START -->
    <div id="main_map">

    <div id="map" style="width:100%;height:400px;"></div>

    <!-- this div will hold your store info -->
    <div id="info_div"></div>
    <!--  <div id="googleMap" style="width:100%;height:400px;"></div>
     -->    
    </div>
    <!-- END FUll MAP -->
</main>

<!--  <script>
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
</script> -->

<!-- Map locater Script -->

<?php include_once('codigo_antes_body.php'); ?>



<script>
function initMap() {
  var myMapCenter = {lat: 52.486704557938154, lng: -1.9593319416276886};

  // Create a map object and specify the DOM element for display.
  var map = new google.maps.Map(document.getElementById('map'), {
    center: myMapCenter,
    zoom: 10
  });


  function markStore(storeInfo){

    // Create a marker and set its position.
    var marker = new google.maps.Marker({
      map: map,
      position: storeInfo.location,
      title: storeInfo.name
    });

    // show store info when marker is clicked
    marker.addListener('click', function(){
      showStoreInfo(storeInfo);
    });
  }

  // show store info in text box
  function showStoreInfo(storeInfo){
    var info_div = document.getElementById('info_div');
    info_div.innerHTML = 'Store name: '
      + storeInfo.name
      + '<br>Hours: ' + storeInfo.hours;
  }

  var stores = [
    {
      name: 'WASHWOOD HEATH BRANCH',
      location: {lat: 52.49385359419111, lng: -1.8337273644182142},
      hours: '10AM to 7PM'
    },
    {
      name: 'SPARK HILL BRANCH',
      location: {lat: 52.446594, lng: -1.860262},
      hours: '10AM to 7PM'
    },
    {
      name: 'SMALL HEATH BRANCH',
      location: {lat: 52.467251, lng: -1.850625},
      hours: '10AM to 7PM'
    },
    {
      name: 'LOZELLS BRANCH',
      location: {lat: 52.502984, lng: -1.905712},
      hours: '10AM to 7PM'
    },
    {
      name: 'DUDLEY ROAD BRANCH',
      location: {lat: 52.486936, lng: -1.935627},
      hours: '10AM to 7PM'
    },
    {
      name: 'ALUM ROCK BRANCH',
      location: {lat: 52.488679, lng: -1.850380},
      hours: '10AM to 7PM'
    }
  ];

  stores.forEach(function(store){
    markStore(store);
  });

}
</script>
<script type="text/javascript">
// This sample uses the Autocomplete widget to help the user select a
      // place, then it retrieves the address components associated with that
      // place, and then it populates the form fields with those details.
      // This sample requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script
      // src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      let placeSearch;
      let autocomplete;
      const componentForm = {
        street_number: "short_name",
        route: "long_name",
        locality: "long_name",
        administrative_area_level_1: "short_name",
        country: "long_name",
        postal_code: "short_name",
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
          document.getElementById("autocomplete"),
          { types: ["geocode"] }
        );
        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(["address_component"]);
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();

        for (const component in componentForm) {
          document.getElementById(component).value = "";
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        for (const component of place.address_components) {
          const addressType = component.types[0];

          if (componentForm[addressType]) {
            const val = component[componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition((position) => {
            const geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            const circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy,
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
</script>
<script type="text/javascript"> 
  function initialize() {
   //initMap();
   initAutoComplete();
}
</script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYw1qIZUqAK4lfkB8dLIOK0XOVU9e66xE&libraries=places&callback=initAutocomplete" async defer></script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYw1qIZUqAK4lfkB8dLIOK0XOVU9e66xE&callback=initialize" async defer></script> -->

<script>
  function getval() {
  var postal_code = document.getElementById('postal_code').value;


   $.ajax({
      url: "distancemile.php",
      type: 'post',
      dataType: 'json',
      data: {postal_code:postal_code},
      //contentType: 'application/json',
      success: function (data) {value_show
        //alert(JSON.stringify(data.lag_key));
        if(data.store_one_pin == false)
        {
           setTimeout(function() {
          $("#value_show").hide(); 
          $("#hidepickup").hide(); 
            }, 10);
        }
        else
        {
          $("#value_show").show(); 
        }
        $("#miless1").html(data.store_one_pin);
        $("#miless2").html(data.store_two_pin);
        $("#miless3").html(data.store_three_pin);
        $("#miless4").html(data.store_four_pin);
        $("#miless5").html(data.store_five_pin);
        $("#miless6").html(data.store_six_pin);
        $("#miles_id_one").html(data.store_one_id);
    }
  });
}

jQuery(document).ready(function($) {
     getval();
    });

</script>
<script type="text/javascript">
  $(document).on('click', '.store_fetch', function(event) {
  event.preventDefault();
  var data_id = $(this).attr('id');
  var datepicker = document.getElementById("datepicker").value();
  var Ptime = document.getElementById("Ptime").value();
 // var x = document.getElementById("Fechshow");
  $.ajax({
    url: 'store_popup_record.php',
    type: 'post',
    dataType: 'html',
    data: {data_id: data_id, datepicker: datepicker, Ptime: Ptime},
  })
  .done(function(html) {
    console.log("success");
     console.log(data_id);
    if(html == true)
    { 
      setTimeout(function() {
      $("#value_show").hide(); 
      $("#hidepickup").show(); 
      $("#main_map").hide();
      }, 10);
    }
    else
    {
      setTimeout(function() {
      $("#main_map").hide();    
      $("#value_show").hide(); 
      $("#hidepickup").show();
      }, 10);
    }

    $("#Fechshow").html(html);

  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
  
});

</script>

