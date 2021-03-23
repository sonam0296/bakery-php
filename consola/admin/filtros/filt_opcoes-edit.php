<?php include_once('../inc_pages.php'); ?>

<?php 



$menu_sel='ec_produtos_filtros';

$menu_sub_sel='opcoes';



$id=$_GET['id'];



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "filt_opcao")) {

	$manter = $_POST['manter'];

	

	if($_POST['nome']!='') {

		$insertSQL = "UPDATE l_filt_opcoes".$extensao." SET nome=:nome WHERE id=:id";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);		

		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	

		$rsInsert->execute();

		

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

		$rsLinguas->execute();

		$totalRows_rsLinguas = $rsLinguas->rowCount();



    $cor = $_POST['cor'];

    if($_POST['categoria'] != 1)

      $cor = NULL;

		

		while($row_rsLinguas = $rsLinguas->fetch()) {	

			$insertSQL = "UPDATE l_filt_opcoes_en SET categoria=:categoria, cor=:cor WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':categoria', $_POST['categoria'], PDO::PARAM_INT);	

      $rsInsert->bindParam(':cor', $cor, PDO::PARAM_STR, 6); 

			$rsInsert->bindParam(':id', $id, PDO::PARAM_STR, 5);	

			$rsInsert->execute();

		}



    DB::close();

		

		if(!$manter)

			header("Location: filt_opcoes.php?alt=1");

		else

      header("Location: filt_opcoes-edit.php?id=".$id."&alt=1");

	}	

}



$query_rsP = "SELECT * FROM l_filt_opcoes".$extensao." WHERE id=:id";

$rsP = DB::getInstance()->prepare($query_rsP);

$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);	

$rsP->execute();

$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

$totalRows_rsP = $rsP->rowCount();



$query_rsCat = "SELECT * FROM l_filt_categorias".$extensao." ORDER BY ordem ASC, nome ASC";

$rsCat = DB::getInstance()->prepare($query_rsCat);

$rsCat->execute();

$totalRows_rsCat = $rsCat->rowCount();

DB::close();



?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>

<style type="text/css">

  #div_cor {

    display: none;

  }

</style>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>

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

      <h3 class="page-title"><?php echo $RecursosCons->RecursosCons['filtros']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li>

            <i class="fa fa-home"></i>

            <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>

            <i class="fa fa-angle-right"></i>

          </li>

          <li>

            <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['produtos']; ?></a>

            <i class="fa fa-angle-right"></i>

          </li>

          <li>

            <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['filtros']; ?></a>

            <i class="fa fa-angle-right"></i>

          </li>

          <li>

            <a href="filt_opcoes.php"><?php echo $RecursosCons->RecursosCons['opcoes']; ?> <i class="fa fa-angle-right"></i></a>

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

              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></h4>

            </div>

            <div class="modal-body"><?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>

            <div class="modal-footer">

              <button type="button" class="btn blue" onClick="document.location='filt_opcoes.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>

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

          <form id="filt_opcao" name="filt_opcao" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="manter" id="manter" value="0">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?> - <?php echo $RecursosCons->RecursosCons['filtros']; ?></div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='filt_opcoes.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>

                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>

                </div>

              </div>

              <div class="portlet-body">

                <div class="form-body">

                  <?php if($_GET['alt'] == 1) { ?>

                    <div class="alert alert-success display-show">

                      <button class="close" data-close="alert"></button>

                      <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>

                    </div>

                  <?php } ?>

                  <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>                  

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>">

                    </div>

                  </div>  

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="categoria"><?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: <span class="required"> * </span></label>

                    <div class="col-md-8">

                      <select class="form-control select2me" id="categoria" name="categoria" >

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <?php if($totalRows_rsCat > 0) { ?>

						              <?php while($row_rsCat = $rsCat->fetch()) { ?>

                          	<option value="<?php echo $row_rsCat['id']; ?>" <?php if($row_rsP['categoria']==$row_rsCat['id']) echo "selected"; ?>><?php echo $row_rsCat['nome']; ?></option>

                          <?php } ?>

                        <?php } ?>

                      </select>

                    </div>

                  </div>              

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="filt_opcao" />

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

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

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



   if($('#categoria').val() == 1)

    $('#div_cor').css('display', 'block');

   else

    $('#div_cor').css('display', 'none');



   $('#categoria').on('change', function() {

    if($('#categoria').val() == 1)

      $('#div_cor').css('display', 'block');

    else

      $('#div_cor').css('display', 'none');

   });

});

</script> 

</body>

<!-- END BODY -->

</html>