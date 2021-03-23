<?php require_once('../Connections/connADMIN.php'); ?>
<?php 
$query_rs_supp = "SELECT * FROM store_locater";
$rsP_supp = DB::getInstance()->prepare($query_rs_supp); 
$rsP_supp->execute();
$totalRows_rsP_supp = $rsP_supp->rowCount();
$row_rsP_supp = $rsP_supp->fetchAll();

$query_rs_pro = "SELECT * FROM l_pecas_en WHERE id = '".$_POST["e_product_id"]."' ";
$rsP_pro = DB::getInstance()->prepare($query_rs_pro); 
$rsP_pro->execute();
$totalRows_rsP_pro = $rsP_pro->rowCount();
$row_rsP_pro = $rsP_pro->fetch(PDO::FETCH_ASSOC);

?>
 <style type="text/css">
 	.fancybox-stage label.col-md-3.control-label {
    padding: 0 10px 0 0;
}
 </style>
  

  <div>
    <h4 style="color: red;">10 Tier Tower Cake Code W64</h4>
      <h4 style="color: red;"><?php echo $row_rsP_pro['id']; ?></h4>
  	<br>
  	<form method="post">
  		<div class="select_holder icon-down">
          <select name="store-value" class="store_detail" required>
              <option>Choose Value</option>
            <?php foreach ($row_rsP_supp as $value) { ?>
              <option  id="store_id_get" value="<?php echo $value['id']; ?>"><?php echo $value["b_name"]; ?></option>
            <?php } ?>
          </select>
      </div>
        <input type="hidden" id="e_product_id" value="<?php echo $row_rsP_pro['id']; ?>" >
  	</form>
  </div>

	<div id="showvalue">
	
	</div>


<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/workers.main.js'></script>

