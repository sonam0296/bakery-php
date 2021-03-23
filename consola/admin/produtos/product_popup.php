<?php require_once('../../../Connections/connADMIN.php'); ?>
 
 <style type="text/css">
 	.fancybox-stage label.col-md-3.control-label {
    padding: 0 10px 0 0;
}
 </style>


	<?php 

		//echo $_GET["data_id"];

		$data_id = $_GET["data_id"];
		$query_rs_supp = "SELECT * FROM l_pecas_supplier where product_id=".$data_id;
		$rsP_supp = DB::getInstance()->prepare($query_rs_supp);
		$rsP_supp->execute();
		$totalRows_rs_supp = $rsP_supp->fetchAll();

		$query_rsP = "SELECT * FROM l_pecas_en WHERE id=".$data_id;
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();      

		

 	?>
   

  <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">


  	<!-- Start || Product Quick Edit -->

  	<h4>Product Quick Edit</h4>

  	<hr>
  	<table width="1200">
  		<tr>
  			<td>
  				<div class="form-group">
                   <label class="col-md-3 control-label"> On Order: </label>
	 <input type="radio" name="order" id="order" value="1" <?php if($row_rsP['on_order']==1) { echo "checked=\"checked\""; }?>>Yes
	                  
	                <input type="radio" name="order" id="order" value="0" <?php if($row_rsP['on_order']==0) { echo "checked=\"checked\""; }?> >No
                  </div>
  			</td>
  			<td>
  				<div class="form-group">
                   <label class="col-md-3 control-label"> VAT: </label>
					<input type="radio" name="vat" id="vat" value="20" <?php if($row_rsP['iva'] != "") { echo "checked=\"checked\""; }?>>Yes
                     <input type="radio" name="vat" id="vat" value="0" <?php if($row_rsP['iva'] == "" || $row_rsP['iva'] == 0) { echo "checked=\"checked\""; }?>>No
                  </div>
  			</td>
  		
  		</tr>

  	</table>

  	<!-- End || Product Quick Edit -->

	<hr>

	<!-- Start || Simple Product Price Info  -->

	 	<?php
	    foreach($totalRows_rs_supp as $row) {
	      $primery =  $row["primery"];
	      $supp_price = $row["price"];
	    }
	   ?>

	<h4>Simple Product Price Info</h4>

	 <?php 
	$cost = $row_rsP["cost"];
	$markup = $row_rsP["markup"];

	$totalprice2 = $cost +  ( ($cost * $markup) / 100);
	?>

	 <?php 
	    $cost = $row_rsP["cost"];
	    $markup = $row_rsP["markup"];

	    if($cost != "" && $markup != "")
	    {
	      $totalprice2 = $cost +  ( ($cost * $markup) / 100);
	    }
	    if($row_rsP['preco'] == 0 || $row_rsP['preco'] == "")
	    {
	      $totalprice2 = "";
	    }
	    if( ($cost == "" ||  $cost == 0 || $cost != 0) && ($markup == "" || $markup == 0 ) )
	    {
	      $totalprice2 = $row_rsP['preco'];
	    }
	    ?>

  	 <div class="form-group">
	  <label class="col-md-2 control-label" for="preco">Regular Price:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="preco" id="preco" value="<?php echo $totalprice2; ?>" maxlength="8">
	    </div>
	  </div>

	  <label class="col-md-1 control-label" for="cost">Cost Price:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="cost" id="cost" value="<?php if($primery == 1) { echo $supp_price; } else { echo $row_rsP['cost']; }  ?>" maxlength="8" >

	    </div>  
	        <label class="col-md-1 control-label" for="preco_ant" style="color: red;"><?php foreach($totalRows_rs_supp as $row) { if($row["primery"] == 1) { echo $row["name"]; } } ?> </label>                   
	  </div>

	  <label class="col-md-2 control-label" for="markup">Markup Price %:</label>
      <div class="col-md-2">
        <div class="input-group">
          <input type="text" class="form-control" name="markup" id="markup" value="<?php echo $row_rsP['markup']; ?>" maxlength="8">
       
        </div>
      </div>
	</div>

	<hr>

	<!-- START QR CODE || CODE BY VISHAL PRAJAPATI || 17-12-20-->
	
	<h4>QR CODE</h4>

	<div class="form-group">
	  <label class="col-md-2 control-label" for="descricao_stock">QR Code Level:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      	<select name="level" class="form-control">
				<option value="L">L - smallest</option>
				<option value="M" selected>M</option>
				<option value="Q">Q</option>
				<option value="H">H - best</option>
			</select>
	    </div>
	  </div>

	  <label class="col-md-2 control-label" for="maxstock">QR Code Size:</label>
      <div class="col-md-2">
        <div class="input-group">
         	<select name="size" class="form-control">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4" selected>4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select> 
        </div>
      </div>
      	<div class="col-md-2">
	        <div class="input-group">
	      		<input type="submit" name="qr_submit" value="QR Update" class="btn btn-danger">
		  	</div>
		</div>

			<div class="col-md-2">
	        <div class="input-group">
	        	<?php echo $row_rsP['qr_code'];	 ?>
	      		<?php if($row_rsP['qr_code'] != "") { ?> 

        			<div class="text-center"><img src="../../../imgs/QR/<?php echo $row_rsP['qr_code']; ?>"/></div>
				<?php }
				else
				{
					echo "Renerate QR CODE!!!";
				}
				?>
		  	</div>
		</div>
	</div>

	<!-- END CODE || QR CODE -->

	<hr>

	<h4>Stock Manage</h4>

	 <div class="form-group">
	 
	  <label class="col-md-2 control-label" for="stock">Stock:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="stock" id="stock" value="<?php echo $row_rsP['stock']; ?>" maxlength="8">
	    </div>
	  </div>

	  <label class="col-md-2 control-label" for="descricao_stock">Min. Stock:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="descricao_stock" id="descricao_stock" value="<?php echo $row_rsP['descricao_stock']; ?>" maxlength="8">
	    </div>
	  </div>

	  <label class="col-md-2 control-label" for="maxstock">Max Stock:</label>
      <div class="col-md-2">
        <div class="input-group">
          <input type="text" class="form-control" name="maxstock" id="maxstock" value="<?php echo $row_rsP['maxstock']; ?>" maxlength="8">
       
        </div>
      </div>
	</div>

	<hr>

	<!-- End || Simple Product Price Info  -->

	<!-- Start || Supplier Info  -->

	<h4>Supplier Info</h4>

<?php foreach($totalRows_rs_supp as $row) { ?>

	<div class="form-group">
	  <label class="col-md-2 control-label" for="supp_name">Supplier Name:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="supp_name" id="supp_name" value="<?php echo $row["name"]; ?>" maxlength="8" disabled>
	    </div>
	  </div>

	   <label class="col-md-2 control-label" for="sell_preco">Price:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="sell_preco<?php echo $row['id']; ?>" id="sell_preco<?php echo $row['id']; ?>" value="<?php echo $row["price"]; ?>" maxlength="8">
	    </div>
	  </div>

	  <label class="col-md-3 control-label"> Primary: </label>
	  <input type="radio" name="primery" id="primery<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" <?php if($row['primery']==1) echo "checked=\"checked\""; ?>>
      </div>
	</div>

<?php  } ?>

	<hr>

	<!-- End || Supplier Info  -->

	<!-- Start || Role Info  -->

	<h4>Franchisee</h4>

	<div class="form-group">
	  <label class="col-md-2 control-label" for="roll_reg">Regular Price:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="roll_reg" id="roll_reg" value="" maxlength="8">
	    </div>
	  </div>

	   <label class="col-md-2 control-label" for="roll_sel">Selling Price:</label>
	  <div class="col-md-2">
	    <div class="input-group">
	      <input type="text" class="form-control" name="roll_sel" id="roll_sel" value="" maxlength="8">
	    </div>
	  </div>
	</div>

	<hr>
	<!-- End || Role Info  -->

	<input type="hidden" class="btn btn-success" value="<?php echo $_GET["data_id"]; ?>" name="id" id="id">

	<input type="submit" class="btn btn-success" value="Update" name="MM_update" id="MM_update">
	<input type="submit" class="btn btn-danger" value="Close" name="update">


  	
  </form>
  
  <script>

     $("#markup").change(function(){

        var cost   = $('#cost').val();
        var markup = $('#markup').val();
        var totalprice =  parseFloat(cost) +  ( (cost * markup) / 100);
        $('#preco').val(totalprice);
      });

       $("#preco").change(function(){

        var cost   = $('#cost').val();
        var preco = $('#preco').val();
        var totalprice2 =  100 * (preco - cost) / cost;
        $('#markup').val(totalprice2);
      });

       $("#cost").change(function(){

        var cost   = $('#cost').val();
        var markup = $('#markup').val();

        totalprice2 = parseFloat(cost) +  ( (cost * markup) / 100);

        $('#preco').val(totalprice2);
      });
       <?php foreach ($totalRows_rs_supp as $value) { ?>
       	
       $("#primery<?php echo $value['id']; ?>").change(function(){

        var preco  = $('#sell_preco<?php echo $value['id']; ?>').val();

        $('#cost').val(preco);
      });
     
      <?php } ?>

    </script>