<?php include_once('../inc_pages.php'); ?>

<?php 



$fixo = $_GET['fixo'];



$menu_sel='paginas';

$menu_sub_sel='paginas_fixas';

$nome_sel='Paginas Fixas';



if($fixo == 0){

	$menu_sub_sel='paginas_outras';

	$nome_sel='Outras Páginas';

}



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "paginas_form")) {

	if($_POST['nome']!='') {	

		$insertSQL = "SELECT MAX(id) FROM paginas_en";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->execute();

		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

		

		$max_id = $row_rsInsert["MAX(id)"]+1;

		

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

    $rsLinguas->execute();

    $totalRows_rsLinguas = $rsLinguas->rowCount();

		

		while($row_rsLinguas = $rsLinguas->fetch()) {

      $nome_url=strtolower(verifica_nome($_POST['nome']));



			$query_rsProc = "SELECT * FROM paginas_".$row_rsLinguas['sufixo']." WHERE url like :nome_url AND id!=:max_id";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

      $rsProc->bindParam(':max_id', $max_id, PDO::PARAM_INT);

      $rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);

			$rsProc->execute();

			$totalRows_rsProc = $rsProc->rowCount();

			

			if($totalRows_rsProc>0){

				$nome_url=$nome_url."-".$max_id;

			}

				

			$insertSQL = "INSERT INTO paginas_".$row_rsLinguas["sufixo"]." (id, menu, nome, titulo, url, title, fixo) VALUES (:max_id, :menu, :nome, :titulo, :url, :title, :fixo)";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

      $rsInsert->bindParam(':menu', $_POST['menu'], PDO::PARAM_INT);

			$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':title', $_POST['nome'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':fixo', $fixo, PDO::PARAM_INT);

      $rsInsert->bindParam(':max_id', $max_id, PDO::PARAM_INT);	

			$rsInsert->execute();

		}



    DB::close();



    alteraSessions('paginas');

    alteraSessions('paginas_menu');

    alteraSessions('paginas_fixas');

	

		header("Location: paginas-edit.php?env=1&id=".$max_id."&fixo=".$fixo);

	}

}



$query_rsMenus = "SELECT * FROM menus_paginas_pt";

$rsMenus = DB::getInstance()->prepare($query_rsMenus);

$rsMenus->execute();

$row_rsMenus = $rsMenus->fetchAll(PDO::FETCH_ASSOC);

$totalRows_rsMenus = $rsMenus->rowCount();

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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li>

            <a href="paginas.php?fixo=<?php echo $fixo; ?>"><?php echo $RecursosCons->RecursosCons['paginas']; ?></a>

          </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

          <form id="paginas_form" name="paginas_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['paginas']; ?> - <?php echo $nome_sel; ?></div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='paginas.php?fixo=<?php echo $fixo; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

                </div>

              </div>

              <div class="portlet-body">

                <div class="form-body">

                  <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                   <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>                  

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">

                    </div>

                  </div> 

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">

                    </div>

                  </div>

                  <?php if($totalRows_rsMenus > 0) { ?>

                    <div class="form-group">

                      <label class="col-md-2 control-label" for="menu"><?php echo $RecursosCons->RecursosCons['menu_label']; ?>: </label>

                      <div class="col-md-6">

                        <select class="form-control select2me" id="menu" name="menu">

                          <option value="0"><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>    

                          <?php foreach ($row_rsMenus as $menu) { ?>

                            <option value="<?php echo $menu['id']; ?>"><?php echo $menu['nome']; ?></option>

                          <?php } ?>

                        </select>

                        <p class="help-block"><?php echo $RecursosCons->RecursosCons['menu_opcao_help']; ?></p>

                      </div>

                    </div>

                  <?php } ?>                  

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="paginas_form" />

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

});

</script> 

</body>

<!-- END BODY -->

</html>