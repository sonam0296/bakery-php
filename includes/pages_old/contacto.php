<?php include_once('pages_head.php');?>

<?php
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

?>
<main class="page-load contactos contactos_bd_data_page">
  <?php
  $mask = "";
  if($row_rsImagem['contactos'] && file_exists(ROOTPATH.'imgs/imagens_topo/'.$row_rsImagem['contactos'])) {
    $img = "imagens_topo/".$row_rsImagem['contactos'];
    if($row_rsImagem['contactos_masc']){
      $mask = "has_mask mobile_has_mask";
    }
  }
  ?>
    <!-- <div class="div_100 banner_topo_data_conto banners <?php echo $mask; ?> <?php if ($img){ echo "has_bg lazy"; } else echo "banner_topo"?>"  <?php if ($img){ echo "data-src=".$img; } ?> style="margin-bottom: 0;">
    <?php echo getFill('imagens_topo'); ?>    
    <div class="div_absolute div_absolute_background_data" style="padding: 0">    
      <div class="row align-middle" style="height: 100%;">
        <div class="column small-12">
          <div class="banner_content text-center" style="max-width: unset;">
            <h1 class="titulos" style="color: white"><?php echo $Recursos->Resources["tit_contactos"]; ?></h1>
          </div>
        </div>
      </div> 
    </div> 
  </div> -->

  <nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
    <div class="row">
      <div class="column">
        <ul class="breadcrumbs">
          <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a>
          </li>
          <li>
            <span>Store Locater</span>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    
 <div class="div_100 contactos_cont contactos_cont_data_page">
        <div class="row align-center">
            <div class="small-12 medium-5 column elements_animated bottom">
                <!-- <div class="contactos_tit titulos" titles><?php echo $Recursos->Resources["tit_contactos"];?></div> -->
                <!-- <div class="contactos_txt list_subtit regular"><?php echo $row_rsLocais[1]['texto']; ?><br><br></div> -->

                <?php if($row_rscontactos['texto']){?>
                    <div class="contactos_txt textos margin" ><?php echo $row_rscontactos['texto']?></div>

                <?php }?>
                      <hr>
                <?php foreach ($row_rsP as $row) { ?>
               <a href="store-locater.php?id=<?php echo$row['id']; ?>"> 
                <?php if($row['b_name']){ ?>
                <div class="contactos_txt textos"><!-- <strong><?php //echo $Recursos->Resources['contacto']; ?>: </strong> --><?php echo $row['b_name']; ?></div>
                <br>
                <?php } ?>

                 <?php if($row['Sreet']){ ?>
                <div class="contactos_txt textos"><!-- <strong><?php //echo $Recursos->Resources['contacto']; ?>: </strong> --><?php echo $row['Sreet']; ?>,<br><?php echo $row['city']; ?>,<br><?php echo $row['country']; ?></div>
                <br>
                <?php } ?>

                <?php if($row['phone']){ ?>
                <div class="contactos_txt textos"><!-- <strong><?php //echo $Recursos->Resources['contacto']; ?>: </strong> --><?php echo $row['phone']; ?></div>
                <br>
                <?php } ?>
                <?php if($row['email']){ ?>
                    <div class="contactos_txt textos"><!-- <strong><?php //echo $Recursos->Resources['mail']; ?>: </strong> --><?php echo $row['email'];?></div><br><hr>
                <?php }?> 
            </a>
            <?php } ?>
                
            </div>
       

            <div class="small-12 medium-5 column elements_animated bottom">
                <div class="div_100 elements_animated bottom text-left" style="max-width: 768px; margin:auto">                    
                    <!-- <h1 class="contactos_tit titulos" titles><?php echo $Recursos->Resources["tit_contactos2"];?></h1> -->

                    <?php 
                    $id = $_GET["id"];
                    $query_rsP1 = "SELECT * FROM store_locater WHERE id = '".$id."'";
                    $rsP1 = DB::getInstance()->prepare($query_rsP1);
                    //$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5); 
                    $rsP1->execute();
                    $row_rsP1 = $rsP1->fetch(PDO::FETCH_ASSOC);
                    $totalRows_rsP1 = $rsP1->rowCount();
                    ?>
                    <?php if($row_rsP1['b_name']) { ?>
                    <div style="padding-top: 150px;">
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
                           
                        </table>
                         <div id="googleMap" style="width:100%;height:400px;"></div>
                    </div>
                <?php  } ?>
                </div>
            </div>
        </div>
    </div>
    
</main>

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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYw1qIZUqAK4lfkB8dLIOK0XOVU9e66xE&callback=myMap"></script>

