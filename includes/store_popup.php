<?php include_once('pages_head.php');?>
<h1>CHOOSE STORE FOR COLLECTION</h1>
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


?>


<main class="page-load contactos contactos_bd_data_page">

 <div class="div_100 contactos_cont contactos_cont_data_page">

        <div class="row align-left">

            <div class="small-12 medium-5 column">
            <hr>
              <?php if($check_pickup_radio == 1) {  ?>
                <?php foreach ($row_rsP as $row) { ?>
                  <?php if($row["door"] == 1) { ?>
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
            <?php } } } if($check_pickup_radio == 0) {
              ?>
              <?php foreach ($row_rsP as $row) { ?>
                  <?php if($row["door"] == 0) { ?>
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
            <?php } } } ?>
                        
           
        </div>
         <div id="Fechshow">
     
         </div>
    </div>
    
</main>

<script type="text/javascript">
  $(document).on('click', '.store_fetch', function(event) {
  event.preventDefault();

  var data_id = $(this).attr('id');
 // var x = document.getElementById("Fechshow");
  $.ajax({
    url: 'store_popup_record.php',
    type: 'post',
    dataType: 'html',
    data: {data_id: data_id},
  })
  .done(function(html) {
    console.log("success");
     console.log(data_id);
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



