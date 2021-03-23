<?php include_once('../inc_pages.php'); ?>

<?php



$menu_sel='online_faqs';

$menu_sub_sel='online_lista';

		

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_faqs")) {

	

	if($_POST['pergunta']!='' && $_POST['categoria'] != '') {

	

		$insertSQL = "SELECT MAX(id) FROM faqs_en";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->execute();

		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

		

		$id = $row_rsInsert["MAX(id)"]+1;

		

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

    $rsLinguas->execute();

    $totalRows_rsLinguas = $rsLinguas->rowCount();

    

  	$categoria = $_POST['categoria'];

		

  	while($row_rsLinguas = $rsLinguas->fetch()) {	

			

			$insertSQL = "INSERT INTO faqs_".$row_rsLinguas["sufixo"]." (id, categoria, pergunta, resposta) VALUES (:id, :categoria, :pergunta, :resposta)";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);

			$rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT, 5);	

			$rsInsert->bindParam(':pergunta', $_POST['pergunta'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':resposta', $_POST['resposta'], PDO::PARAM_STR, 5);

			$rsInsert->execute();

		}

		DB::close();



		alteraSessions("faqs");



		header("Location: faqs.php?ins=1");

	}

}



$query_rsCats = "SELECT id, nome FROM faqs_categorias_pt WHERE visivel = '1' ORDER BY nome ASC";

$rsCats = DB::getInstance()->prepare($query_rsCats);

$rsCats->execute();

$totalRows_rsCats = $rsCats->rowCount();

DB::close();



?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>

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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['faqs']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="faqs.php"><?php echo $RecursosCons->RecursosCons['faqs']; ?> <i class="fa fa-angle-right"></i> </a></li>

          <li> <a href="javascript:;"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a> </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

          <form id="frm_faqs" name="frm_faqs" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['faqs_novo_registo']; ?> </div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='faqs.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>

                </div>

              </div>

              <div class="portlet-body">

                <div class="form-body">

                  <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="categoria"><?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: <span class="required"> * </span> </label>

                    <div class="col-md-8">

                      <select name="categoria" id="categoria" class="form-control select2me">

                      	<option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <?php while($row_rsCats = $rsCats->fetch()) { ?>

                        <option value="<?php echo $row_rsCats["id"]; ?>"><?php echo $row_rsCats["nome"]; ?></option>

                        <?php } ?>

                      </select>

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="pergunta"><?php echo $RecursosCons->RecursosCons['pergunta_label']; ?>: <span class="required"> * </span> </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="pergunta" id="pergunta" value="<?php echo $_POST['pergunta']; ?>" data-required="1">

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="resposta"><?php echo $RecursosCons->RecursosCons['resposta_label']; ?>: </label>

                    <div class="col-md-8">

                      <textarea class="form-control" id="resposta" name="resposta"><?php echo $_POST['resposta']; ?></textarea>

                    </div>

                  </div>

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="frm_faqs" />

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

<!-- LINGUA PORTUGUESA --> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 

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

   Form.init();

});

</script> 

<script type="text/javascript">

CKEDITOR.replace('resposta',

{

	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',

	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',

	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',

	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',

	toolbar : "Basic",

	height: "200px"

	

});

</script>

</body>

<!-- END BODY -->

</html>