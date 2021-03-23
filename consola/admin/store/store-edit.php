<?php include_once('../inc_pages.php'); ?>

<?php



$menu_sel='store';

$menu_sub_sel='';

$tab_sel=1;



if($_GET['env']==1) $tab_sel=1;

if(isset($_GET['tab_sel']))

	$tab_sel = $_GET['tab_sel'];



$id=$_GET['id'];

$inserido=0;

$erro = 0;







if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "categorias_form")) {

	$manter = $_POST['manter'];

		

	$tab_sel = $_REQUEST['tab_sel'];	



	$query_rsP = "SELECT * FROM store_locater WHERE id=:id";

	$rsP = DB::getInstance()->prepare($query_rsP);

	$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);

	$rsP->execute();

	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsP = $rsP->rowCount();

	

	if($_POST['b_name']!='' && $tab_sel==1) {

		//Só atualiza o URL se a checkbox for selecionada (para não perder os URL's personalizados)

		//VERIFICA SE O TULO JÁ ESTÁ PERSONALIZADO

		$set_monday		  = $_POST["monday"]; 

		$monday_start 	  =	$_POST['m_start'];

		$monday_end 	  =	$_POST['m_end'];

		$set_tuesday	  = $_POST["tuesday"]; 

		$tuesday_start	  =	$_POST['t_start'];

		$tuesday_end 	  =	$_POST['t_end'];

		$set_wednesday	  = $_POST["wednesday"]; 

		$wednesday_start  =	$_POST['w_start'];

		$wednesday_end    =	$_POST['w_end'];

		$set_thursday	  = $_POST["thursday"];

		$thursday_start   =	$_POST['th_start'];

		$thursday_end     =	$_POST['th_end'];

		$set_friday		  = $_POST["friday"];

		$friday_start 	  =	$_POST['f_start'];

		$friday_end 	  =	$_POST['f_end'];

		$set_saturday	  = $_POST["saturday"];

		$saturday_start   =	$_POST['s_start'];

		$saturday_end     =	$_POST['s_end'];

		$set_sunday		  = $_POST["sunday"];

		$sunday_start 	  =	$_POST['su_start'];

		$sunday_end 	  =	$_POST['su_end'];



		$start_day = array(

			"set_monday"	=> $set_monday,

			"monday"		=> $monday_start,

			"monday_end"	=> $monday_end, 

			"set_tuesday"	=> $set_tuesday,

			"tuesday"		=> $tuesday_start,

			"tuesday_end"	=> $tuesday_end, 

			"set_wednesday"	=> $set_wednesday,

			"wednesday"		=> $wednesday_start,

			"wednesday_end"	=> $wednesday_end,

			"set_thursday"	=> $set_thursday,

			"thursday"		=> $thursday_start,

			"thursday_end"	=> $thursday_end,

			"set_friday"	=> $set_friday,

			"friday"		=> $friday_start,

			"friday_end"	=> $friday_end,

			"set_saturday"	=> $set_saturday,

			"saturday"		=> $saturday_start,

			"saturday_end"	=> $saturday_end,

			"set_sunday"	=> $set_sunday,

			"sunday"		=> $sunday_start,

			"sunday_end"	=> $sunday_end

		);



	    $monday_close 	  =	$_POST['m_close'];

	    $tuesday_close 	  =	$_POST['t_close'];

	    $wednesday_close  =	$_POST['w_close'];

	    $thursday_close   =	$_POST['th_close'];

	    $friday_close 	  =	$_POST['f_close'];

	    $saturday_close   =	$_POST['s_close'];

	    $sunday_close 	  =	$_POST['su_close'];



		$close_day = array(

			"monday_close"	  => $monday_close,

			"tuesday_close"	  => $tuesday_close, 

			"wednesday_close" => $wednesday_close,

			"thursday_close"  => $thursday_close, 

			"friday_close"	  => $friday_close,

			"saturday_close"  => $saturday_close,

			"sunday_close"	  => $sunday_close

		);



    	$start_time = serialize($start_day);

    	$close_time = serialize($close_day);



		$insertSQL = "UPDATE store_locater SET b_name=:b_name, Sreet=:Sreet, lat=:lat, lng=:lng, city=:city, country=:country, pincode=:pincode, phone=:phone, email=:email,start_time='".$start_time."',close='".$close_time."', door=:door WHERE id=:id";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->bindParam(':b_name', $_POST['b_name'], PDO::PARAM_STR, 5);		

		$rsInsert->bindParam(':Sreet', $_POST['Sreet'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':lat', $_POST['lat'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':lng', $_POST['lng'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':city', $_POST['city'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':country', $_POST['country'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':pincode', $_POST['pincode'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);

		$rsInsert->bindParam(':door', $_POST['door'], PDO::PARAM_STR, 5);		

		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

		$rsInsert->execute();



		alteraSessions('categorias');



		DB::close();

		

		if(!$manter) 

			header("Location: store.php?alt=1");

		else 

			header("Location: store-edit.php?id=".$id."&alt=1&tab_sel=1");

	}

	

}



$query_rsP = "SELECT * FROM store_locater WHERE id=:id";

$rsP = DB::getInstance()->prepare($query_rsP);

$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	

$rsP->execute();

$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

$totalRows_rsP = $rsP->rowCount();





$start_unserilize  = $row_rsP["start_time"];

$close_unserilize  = $row_rsP["close"];

$unserialize_strat = unserialize($start_unserilize); 

$unserialize_close = unserialize($close_unserilize); 



$mondaycheck 	= $unserialize_strat["monday"];

$tuesdaycheck 	= $unserialize_strat["tuesday"];

$wednesdaycheck = $unserialize_strat["wednesday"];

$thursdaycheck  = $unserialize_strat["thursday"];

$fridaycheck 	= $unserialize_strat["friday"];

$saturdaycheck  = $unserialize_strat["saturday"];

$sundaycheck 	= $unserialize_strat["sunday"];



$m_check_end  = $unserialize_strat["monday_end"];

$t_check_end  = $unserialize_strat["tuesday_end"];

$w_check_end  = $unserialize_strat["wednesday_end"];

$th_check_end = $unserialize_strat["thursday_end"];

$f_check_end  = $unserialize_strat["friday_end"];

$s_check_end  = $unserialize_strat["saturday_end"];

$su_check_end = $unserialize_strat["sunday_end"];



$m_check_close  = $unserialize_close["monday_close"];

$t_check_close  = $unserialize_close["tuesday_close"];

$w_check_close  = $unserialize_close["wednesday_close"];

$th_check_close = $unserialize_close["thursday_close"];

$f_check_close  = $unserialize_close["friday_close"];

$s_check_close  = $unserialize_close["saturday_close"];

$su_check_close = $unserialize_close["sunday_close"];



DB::close();



?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>

<!--COR-->

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>

<body class="<?php echo $body_info; ?>">

<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>

<div class="clearfix"> </div>

<!-- BEGIN CONTAINER -->

<div class="page-container">

  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>

  <!-- BEGIN CONTENT -->

  <div class="page-content-wrapper">

    <div class="page-content"> 

      <!-- BEGIN PAGE HEADER-->

      <h3 class="page-title"> Store <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>

			<div class="page-bar">

				<ul class="page-breadcrumb">

					<li>

						<i class="fa fa-home"></i>

						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>

						<i class="fa fa-angle-right"></i>

					</li>

					<li>

						<a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?></a>

						<i class="fa fa-angle-right"></i>

					</li>

					<li>

						<a href="store.php">Store <i class="fa fa-angle-right"></i></a>

					</li>

					<li>

						<a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a>

					</li>

				</ul>

			</div>

      <!-- END PAGE HEADER-->      

      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

          <div class="modal-content">

            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>

            </div>

            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>

            <div class="modal-footer">

              <button type="button" class="btn blue" onClick="document.location='categorias.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>

              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>

            </div>

          </div>

          <!-- /.modal-content --> 

        </div>

        <!-- /.modal-dialog --> 

      </div>

      <!-- /.modal --> 

      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

		  		<?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?> 

          <form id="categorias_form" name="categorias_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="manter" id="manter" value="0">

            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">

            <input type="hidden" name="img_remover1" id="img_remover1" value="0">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i>Store - <?php echo $row_rsP['nome']; ?></div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='store.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>

                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>

                </div>

              </div>

              <div class="portlet-body">

              	<div class="tabbable">

                  <ul class="nav nav-tabs">

                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>

                  </ul>

                  <div class="tab-content no-space">

                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">

                      <div class="form-body">

					  						<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>

                        	<div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                          <?php echo $RecursosCons->RecursosCons['alt']; ?> 

	                        </div>

                        <?php } ?>

												<?php if($_GET['env'] == 1) { ?>  

	                        <div class="alert alert-success display-show">

	                          <button class="close" data-close="alert"></button>

	                          <?php echo $RecursosCons->RecursosCons['env_config']; ?> 

	                        </div>

                        <?php }�?>

	                      <div class="alert alert-danger display-hide">

	                        <button class="close" data-close="alert"></button>

	                        <?php echo $RecursosCons->RecursosCons['msg_required']; ?>

	                      </div>                  

	                      <div class="form-group">

	                        <label class="col-md-2 control-label" for="b_name"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>

	                        <div class="col-md-5">

	                          <input type="text" class="form-control" name="b_name" id="b_name" value="<?php echo $row_rsP['b_name']; ?>">

	                        </div>

	                      </div>      

		                	<div class="form-group">

		                    	<label class="col-md-2 control-label" for="Sreet">Address: </label>

			                    <div class="col-md-6">

			                      <textarea class="form-control" rows="3" id="Sreet" name="Sreet"><?php echo $row_rsP['Sreet']; ?></textarea>

			                    </div>

		                  	</div>  



		                  	<div class="form-group">

								<label class="col-md-2 control-label" for="lat">Latitude: <span class="required"> * </span></label>

									<div class="col-md-3">

										<input type="text" class="form-control" name="lat" id="lat" value="<?php echo $row_rsP['lat']; ?>">

									</div>



								<label class="col-md-2 control-label" for="lng">Longitude: <span class="required"> * </span></label>

									<div class="col-md-3">

										<input type="text" class="form-control" name="lng" id="lng" value="<?php echo $row_rsP['lng']; ?>">

									</div>

							</div> 



							<div class="form-group">

								<label class="col-md-2 control-label" for="city">City: <span class="required"> * </span></label>

									<div class="col-md-3">

										<input type="text" class="form-control" name="city" id="city" value="<?php echo $row_rsP['city']; ?>">

									</div>



									<label class="col-md-2 control-label" for="country">Country: <span class="required"> * </span></label>

									<div class="col-md-3">

										<?php

										$query_rsP = "SELECT * FROM paises";

										$rsP = DB::getInstance()->prepare($query_rsP);  

										$rsP->execute();

										$row_rsPs = $rsP->fetchAll();

										$totalRows_rsP = $rsP->rowCount();

										?>

										<select name="country" id="country">

											<?php foreach ($row_rsPs as $value) { ?>

											<option value="<?php echo $value['nome']; ?>" <?php if($value['nome'] == $row_rsP['country']) { echo "selected"; } ?>><?php echo $value["nome"]; ?></option>

										<?php } ?>

										</select>

									</div>

							</div>  





		                  	<div class="form-group">

			                    <label class="col-md-2 control-label" for="phone">Phone Number: <span class="required"> * </span></label>

			                    <div class="col-md-3">

			                      <input type="number" class="form-control" name="phone" id="phone" value="<?php echo $row_rsP['phone']; ?>">

			                    </div>

			                    <label class="col-md-2 control-label" for="city">Pincode: <span class="required"> * </span></label>

								<div class="col-md-3">

									<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $row_rsP['pincode']; ?>">

								</div>

			                </div>



			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="email">Email Id: <span class="required"> * </span></label>

			                    <div class="col-md-3">

			                      <input type="email" class="form-control" name="email" id="email" value="<?php echo $row_rsP['email']; ?>">

			                    </div>

			                </div>



			                <hr>

			                  <h2>Open Hours</h2>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Monday: </span></label>

			                    <input type="hidden" name="monday" value="Monday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="m_start" id="m_start" value="<?php echo $mondaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="m_end" id="m_end" value="<?php echo $m_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="m_close" id="m_close" value="1" <?php if($m_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Tuesday: </span></label>

			                    <input type="hidden" name="tuesday" value="Tuesday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="t_start" id="t_start" value="<?php echo $tuesdaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="t_end" id="t_end" value="<?php echo $th_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="t_close" id="t_close" value="1" <?php if($t_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Wednesday: </span></label>

			                    <input type="hidden" name="wednesday" value="Wednesday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="w_start" id="w_start" value="<?php echo $wednesdaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="w_end" id="w_end" value="<?php echo $w_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="w_close" id="w_close" value="1" <?php if($w_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Thursday: </span></label>

			                    <input type="hidden" name="thursday" value="Thursday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="th_start" id="th_start" value="<?php echo $thursdaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="th_end" id="th_end" value="<?php echo $th_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="th_close" id="th_close" value="1" <?php if($th_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Friday: </span></label>

			                    <input type="hidden" name="friday" value="Friday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="f_start" id="f_start" value="<?php echo $fridaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="f_end" id="f_end" value="<?php echo $f_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="f_close" id="f_close" value="1" <?php if($f_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Saturday: </span></label>

			                    <input type="hidden" name="saturday" value="Saturday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="s_start" id="s_start" value="<?php echo $saturdaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="s_end" id="s_end" value="<?php echo $s_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="s_close" id="s_close" value="1" <?php if($s_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="start_time">Sunday: </span></label>

			                    <input type="hidden" name="sunday" value="Sunday">

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="su_start" id="su_start" value="<?php echo $sundaycheck; ?>">

			                    </div>

			                    <div class="col-md-3">

			                      <input type="time" class="form-control" name="su_end" id="su_end" value="<?php echo $su_check_end; ?>">

			                    </div>

			                     <div class="col-md-3">

			                      <input type="checkbox" class="form-control" name="su_close" id="su_close" value="1" <?php if($su_check_close == 1) { echo "checked"; } ?>>

			                    </div>

			                </div>

			                <hr>

			                <h2>Shipping Methods</h2>

			                <div class="form-group">

			                    <label class="col-md-2 control-label" for="email">Door : <span class="required"> * </span></label>

			                    <div class="col-md-3">

			                      <input type="radio" class="form-control" name="door" id="door" value="1" <?php if($row_rsP['door'] == 1){ echo "checked"; } ?>>

			                    </div>

			                    <label class="col-md-2 control-label" for="email">Store : <span class="required"> * </span></label>

			                    <div class="col-md-3">

			                      <input type="radio" class="form-control" name="door" id="store" value="0" <?php if($row_rsP['door'] == 0){ echo "checked"; } ?>>

			                    </div>

			                </div>              

                    	</div>

                    </div>

                  </div>

                </div> 

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="categorias_form" />

          </form>

        </div>

      </div>

      <!-- END PAGE CONTENT--> 

    </div>

  </div>

  <!-- END CONTENT -->

  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>

</div>

<!-- END CONTAINER -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>

<!-- BEGIN PAGE LEVEL PLUGINS --> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS --> 

<script src="form-validation.js"></script> 

<!-- END PAGE LEVEL SCRIPTS --> 

<script>

jQuery(document).ready(function() {    

   Metronic.init(); // init metronic core components

   Layout.init(); // init current layout

   QuickSidebar.init(); // init quick sidebar

   Demo.init(); // init demo features

   FormValidation.init();

});

</script> 

<script type="text/javascript">

document.ready=carregaPreview();

</script>

<script type="text/javascript">

CKEDITOR.replace('Sreet', {

  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',

  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',

  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',

  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',

  toolbar : "Basic2"

});

</script>



<script type="text/javascript">

		$('.bg-type-main .radio').click(function(event) {

			/* Act on the event */

			var checked_val = $(this).find('input:checked').val();

			bg_type_hide_show(checked_val); 

		});



		jQuery(document).ready(function($) {

			var checked_val = $('.bg-type-main .radio').find('input:checked').val();

			bg_type_hide_show(checked_val);

		});



		function bg_type_hide_show(checked_val) {

			$('.data-bg').each(function(index, el) {

				if ($(this).attr('data-bg-type') == checked_val) {

					$(this).show();

				}else{

					$(this).hide();

				}

			});

		}

	</script>

</body>

<!-- END BODY -->

</html>

