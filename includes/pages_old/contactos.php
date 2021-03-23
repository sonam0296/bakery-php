<?php include_once('pages_head.php');

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
            <span><?php echo $Recursos->Resources["contactos"]; ?></span>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    
 <div class="div_100 contactos_cont contactos_cont_data_page">
        <div class="row align-center">
            <?php if($row_rscontactos['telefone'] || $row_rscontactos['email'] || $row_rscontactos['texto']){ ?>
            <div class="small-12 medium-5 column elements_animated bottom">
                <!-- <div class="contactos_tit titulos" titles><?php echo $Recursos->Resources["tit_contactos"];?></div> -->
                <!-- <div class="contactos_txt list_subtit regular"><?php echo $row_rsLocais[1]['texto']; ?><br><br></div> -->

                <?php if($row_rscontactos['texto']){?>
                    <div class="contactos_txt textos margin" ><?php echo $row_rscontactos['texto']?></div>
                <?php }?>

                <?php if($row_rscontactos['telefone']){ ?>
                <div class="contactos_txt textos"><!-- <strong><?php //echo $Recursos->Resources['contacto']; ?>: </strong> --><a class="contactos_txt telephone textos regular tel" href="tel:<?php echo $row_rscontactos['telefone'];?>"><?php echo $row_rscontactos['telefone']; ?></a></div>
                <br>
                <?php } ?>
                <?php if($row_rscontactos['email']){ ?>
                    <div class="contactos_txt textos"><!-- <strong><?php //echo $Recursos->Resources['mail']; ?>: </strong> --><a class="contactos_txt share-mail textos regular mail" href="mailto:<?php echo $row_rscontactos['email'];?>"><?php echo $row_rscontactos['email'];?></a></div>
                <?php }?> 
                
            </div>
            <?php } ?>

            <div class="small-12 medium-5 column elements_animated bottom">
                <div class="div_100 elements_animated bottom text-left" style="max-width: 768px; margin:auto">                    
                    <!-- <h1 class="contactos_tit titulos" titles><?php echo $Recursos->Resources["tit_contactos2"];?></h1> -->
                    <form action="" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="post" name="form_contactos" id="form_contactos" novalidate autocomplete="off" nearby-validator>
                        <div class="animated_elements right"> 
                            
                            <div class="inpt_holder">
                                <label class="inpt_label" for="<?php echo $form_contactos['nome']; ?>"><?php echo $Recursos->Resources["nome"];?> *</label><!--
                                --><input required class="inpt inpt_conto" type="text" id="<?php echo $form_contactos['nome']; ?>" name="<?php echo $form_contactos['nome']; ?>" autocomplete="name" />
                                <div class="inpt_error"></div>
                            </div>
                            
                            <div class="inpt_holder">
                                <label class="inpt_label" for="<?php echo $form_contactos['email']; ?>"><?php echo $Recursos->Resources["mail"];?> *</label><!--
                                --><input required class="inpt inpt_conto" type="email" id="<?php echo $form_contactos['email']; ?>" name="<?php echo $form_contactos['email']; ?>" autocomplete="email"/>
                                <div class="inpt_error"></div>
                            </div>
                            
                            <!-- <div class="inpt_holder">
                                <label class="inpt_label" for="<?php echo $form_contactos['assunto']; ?>"><?php echo $Recursos->Resources["assunto"];?> *</label><input required class="inpt" type="text" id="<?php echo $form_contactos['assunto']; ?>" name="<?php echo $form_contactos['assunto']; ?>" autocomplete="subject"/>
                                <div class="inpt_error"></div>
                            </div> -->

                            <div class="inpt_holder textarea">
                                <label class="inpt_label" for="<?php echo $form_contactos['address']; ?>"><?php echo $Recursos->Resources["address"];?> *</label><textarea style="height: 15rem !important;" required class="inpt inpt_conto" id="<?php echo $form_contactos['mensagem']; ?>" name="<?php echo $form_contactos['mensagem']; ?>" autocomplete="message"></textarea>
                                <div class="inpt_error"></div>
                            </div>

                            <div class="inpt_holder">
                                <label class="inpt_label" for="<?php echo $form_contactos['phone']; ?>"><?php echo $Recursos->Resources["phone"];?> *</label><!--
                                --><input required class="inpt inpt_conto" type="tel" id="<?php echo $form_contactos['phone']; ?>" name="<?php echo $form_contactos['phone']; ?>" autocomplete="phone"/>
                                <div class="inpt_error"></div>
                            </div>

                            <div class="inpt_holder simple " style="margin-bottom: 1rem;">
                                 <div class="inpt_checkbox">
                                    <input type="checkbox" required name="<?php echo $form_contactos['aceita_politica']; ?>" id="<?php echo $form_contactos['aceita_politica']; ?>" value="1" />
                                    <label for="<?php echo $form_contactos['aceita_politica']; ?>"><?php echo $Recursos->Resources["aceito_termos_reg"]; ?></label>
                                 </div>
                            </div>

                            <!-- <div class="inpt_holder simple ">
                                 <div class="inpt_checkbox">
                                    <input type="checkbox" name="<?php echo $form_contactos['aceita_newsletter']; ?>" id="<?php echo $form_contactos['aceita_newsletter']; ?>" value="1" />
                                    <label for="<?php echo $form_contactos['aceita_newsletter']; ?>"><?php echo $Recursos->Resources["aceito_news"]; ?></label>
                                 </div>
                            </div> -->
                            
                            <?php if(CAPTCHA_KEY!=NULL){ ?>
                                <div class="inpt_holder simple no_marg">
                                    <div class="captcha" id="contactos_captcha" data-sitekey="<?php echo CAPTCHA_KEY; ?>" data-error="<?php echo $Recursos->Resources["preencha_captcha"]; ?>"></div>
                                </div>
                            <?php }else{ ?>
                                <div class="inpt_holder">
                                    <?php $cod1=rand(1,10); $cod2=rand(1,10); $cod3=$cod1+$cod2; ?>

                                    <label class="inpt_label" for="<?php echo $form_seguranca['cod_seg']; ?>"><?php echo $Recursos->Resources["seguranca"]; ?></label><!--
                                    --><input required type="text" class="inpt confirm" name="<?php echo $form_seguranca['cod_seg']; ?>" id="<?php echo $form_seguranca['cod_seg']; ?>" value="" placeholder="<?php echo $cod1." + ".$cod2." ="; ?>"/>
                                    <input type="hidden" class="cod_confirm" name="<?php echo $form_seguranca['cod_res']; ?>" id="<?php echo $form_seguranca['cod_res']; ?>" value="<?php echo $cod3; ?>"/>
                                </div>   
                            <?php } ?>

                            <button type="submit" class="button-big uppercase btn-enviar"><?php echo $Recursos->Resources["enviar"];?></button>
                            
                            <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                            <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                            <input type="hidden" name="MM_insert" value="form_contactos" />
                            <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                        </div>                
                    </form>
                </div>
            </div>
        </div>
    </div>
  <?php if($row_rsContactos['mapa'] != '') { ?>
    <div class="div_100 mapa">
      <?php echo getFill('contactos'); ?> 
      <div class="mapa_container">
        <?php echo $row_rsContactos['mapa']; ?>
      </div>
    </div>
  <?php } ?>

  <?php if(($locais_gps == 0 && $row_rsContactos['gps']) || $locais_gps > 0) { ?>
    <div class="div_100 mapa">
      <?php echo getFill('contactos'); ?> 
      <div class="mapa_container">
        <div id="map_box" class="div_100" style="height:100%"></div>
      </div>
    </div>
  <?php } ?>
</main>

<?php include_once('pages_footer.php'); ?>

<?php if($locais_gps==0 && $row_rsContactos['gps']){ 
    $descricao=str_replace(array("\r\n","\n","\r"),"",nl2br(addslashes(strip_tags($row_rsContactos["texto"], "<strong>"))));
    $titulo = addslashes(htmlentities(NOME_SITE, ENT_COMPAT, 'UTF-8'));
    $mapa_link="";
    if($row_rsContactos["link_google_maps"]){
        $mapa_link='<div style="margin-top: 10px;"> <a target="_blank" href="'.$row_rsContactos["link_google_maps"].'" style="color:#00afab; text-decoration: none;"><span>'.$Recursos->Resources['mapa_google_ver'].'</span></a> </div>';
    }
    $contentString = '<table border="0" cellspacing="0" cellpadding="0"><tr><td align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0" style="max-width:200px"><tr><td align="left" class="mapa_titulo"><h1 id="firstHeading" class="firstHeading" style="font-size:18px;color:#000000;">'.$titulo.'</h1></td></tr><tr><td align="left" class="mapa_desc" style="margin-top: 10px; color: #575656;">'.$descricao.$mapa_link.'</td></tr></table></td></tr></table>';
?>
<script type="text/javascript">
function initMapa(){
    // Basic options for a simple Google Map
    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var mapOptions = {
        // How zoomed in you want the map to start at (always required)
        zoom: 10,
        // The latitude and longitude to center the map (always required)
        center: new google.maps.LatLng(<?php echo $row_rsContactos['gps']; ?>),
        styles: [{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"}]}]
    };

    // Get the HTML DOM element that will contain your map 
    // We are using a div with id="map" seen below in the <body>
    var mapElement = document.getElementById('map_box');

    // Create the Google Map using our element and options defined above
    var map = new google.maps.Map(mapElement, mapOptions);

    var contentString = '<?php echo $contentString; ?>';


    // Let's also add a marker while we're at it
    var companyImage = new google.maps.MarkerImage('<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png', null, null, null, new google.maps.Size(<?php echo $markerW; ?>,<?php echo $markerH; ?>));
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $row_rsContactos['gps']; ?>),
        map: map,
        icon: companyImage,
        title: $_nomeSite
    });
    
    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
}
</script>
<?php } ?>

<?php if($locais_gps>0){ ?>
<script type="text/javascript">
    var infowindow = null;
    var sites = [

    <?php $i=1; foreach($row_rsLocais as $mapas) { 
        $spl = explode(",",$mapas["gps"]);
        
        if(count($spl)==2){
            $contentString = '<table border="0" cellspacing="0" cellpadding="0"><tr>';
            $descricao=str_replace(array("\r\n","\n","\r"),"",nl2br(addslashes(strip_tags($mapas["texto"], "<strong>"))));
            
            $mapa_link="";
            if($mapas["link_google_maps"]){
                $mapa_link='<div style="margin-top: 10px;"> <a target="_blank" href="'.$mapas["link_google_maps"].'" style="color:#00afab; text-decoration: none;"><span>'.$Recursos->Resources['mapa_google_ver'].'</span> </a> </div>';
            }
            
            $contentString.= '<td align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0" style="max-width:200px"><tr><td align="left" class="mapa_titulo"><h1 id="firstHeading" class="firstHeading" style="font-size:18px;color:#000000;">'.addslashes(htmlentities($mapas["titulo"], ENT_COMPAT, 'UTF-8')).'</h1></td></tr><tr><td align="left" class="mapa_desc" style="margin-top: 10px; color: #575656;">'.$descricao.$mapa_link.'</td></tr></table></td></tr></table>';
            
            $aberto=0;
    ?>
    ['<?php echo addslashes(htmlentities($mapas["titulo"], ENT_COMPAT, 'UTF-8')); ?>', <?php echo $spl[0]; ?>, <?php echo $spl[1]; ?>, <?php echo $i; ?>, '<?php echo $contentString; ?>', '<?php echo $aberto; ?>']<?php if($i<count($row_rsLocais)) echo ","; ?>

        <?php }?>

    <?php $i++; } ?>

    ];


    var map="";
    var minZoomLevel = 3;

    function initMapa() {
        var geo = new google.maps.Geocoder;
        var lat;
        var zoom=6;
        var pais = "Lisboa, Portugal"; // coloca centro em Entroncamento para se ver o norte de Portugal
        var tipo = "pais";
            
        if($('body').innerWidth()<1650){
            zoom=5;
        }

        geo.geocode({'address':pais},function(results, status){
            if (status == google.maps.GeocoderStatus.OK) {
                lat = results[0].geometry.location;
            } else {
                //alert("Geocode was not successful for the following reason: " + status);
                console.log("Geocode was not successful for the following reason: " + status);
            }
            
            var myOptions = {
                minZoom:minZoomLevel,
                zoom: zoom,
                center: lat,
                disableDoubleClickZoom: true,
                scrollwheel: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                panControl: false,
                draggable: true,
                mapTypeControlOptions: {
                  style: google.maps.MapTypeControlStyle.DEFAULT,
                  mapTypeIds: [
                    google.maps.MapTypeId.ROADMAP,
                  ]
                },
                zoomControl: true,
                styles: [{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"}]}]

            }
            


            map = new google.maps.Map(document.getElementById("map_box"), myOptions);
            
            infowindow = new google.maps.InfoWindow({
                content: "loading..."
            });
            
            setMarkers(map, sites, tipo);
                    
            var bikeLayer = new google.maps.BicyclingLayer();
            bikeLayer.setMap(map);

       });

    }

    function setMarkers(map2, markers, tipo) {
        
        var markers2 = [];
        
        for (var i = 0; i < markers.length; i++) {

            var sites = markers[i];

            var siteLatLng = new google.maps.LatLng(sites[1], sites[2]);
            
            var companyImage = new google.maps.MarkerImage('<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png',
                new google.maps.Size(<?php echo $markerW; ?>,<?php echo $markerH; ?>), // Width and height of the marker
                new google.maps.Point(0,0),
                new google.maps.Point(20,39) // Position of the marker
            );
            
            var marker = new google.maps.Marker({
                position: siteLatLng,
                map: map,
                icon: companyImage,
                title: sites[0],
                zIndex: parseInt(sites[3]),
                html: sites[4]
            });
            markers2.push(marker);
            
            var contentString = "Some content";

            google.maps.event.addListener(marker, "click", function () {
                infowindow.setContent(this.html);
                infowindow.open(map, this);
            });
        }
        
        //set style options for marker clusters (these are the default styles)
        var markerClusterOptions = {
        gridSize: <?php echo $markerW; ?>,
        styles: [{
            textColor: 'white',
            width: <?php echo $markerW; ?>,
            height: <?php echo $markerH; ?>,
            url: "<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png"
        },
        {
            textColor: 'white',
            width: <?php echo $markerW; ?>,
            height: <?php echo $markerH; ?>,
            url: "<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png"
        },
        {
            textColor: 'white',
            width: <?php echo $markerW; ?>,
            height: <?php echo $markerH; ?>,
            url: "<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png"
        },
        {
            textColor: 'white',
            width: <?php echo $markerW; ?>,
            height: <?php echo $markerH; ?>,
            url: "<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png"
        },
        {
            textColor: 'white',
            width: <?php echo $markerW; ?>,
            height: <?php echo $markerH; ?>,
            url: "<?php echo ROOTPATH_HTTP; ?>imgs/elem/marker.png"
        }]}
        
        var markerCluster = new MarkerClusterer(map, markers2, markerClusterOptions);
        

    } 
</script>
<?php } ?>