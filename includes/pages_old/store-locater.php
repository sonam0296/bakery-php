<?php include_once('pages_head.php');?>

<?php
session_start();


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
        <div class="row align-left">
            <div class="small-12 medium-5 column elements_animated bottom">
               
                <?php if($row_rscontactos['texto']){?>
                    <div class="contactos_txt textos margin" ><?php echo $row_rscontactos['texto']?></div>

                <?php }?>
                      <hr>
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
            <?php } ?>
                
            </div>
            <div id="Fechshows">
     
            </div>
        </div>
    </div>
      
</main>

<script type="text/javascript">
   $(document).on('click', '.store_fetch', function(event) {
      event.preventDefault();

      var data_id = $(this).attr('id');
     // var x = document.getElementById("Fechshow");
      $.ajax({
        url: 'store_record_multi.php',
        type: 'post',
        dataType: 'html',
        data: {data_id: data_id},
      })
      .done(function(html) {
        console.log("success");
         console.log(data_id);
        $("#Fechshows").html(html);


      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    });
</script>

