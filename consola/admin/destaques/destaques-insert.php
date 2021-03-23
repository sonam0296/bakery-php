<?php include_once('../inc_pages.php'); ?>

<?php ini_set('display_errors', 1);



$menu_sel='destaques';

$menu_sub_sel='';



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_destaques")) {

	if($_POST['nome']!='') {

		$insertSQL = "SELECT MAX(id) FROM destaques_en";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->execute();

		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

		

		$id = $row_rsInsert["MAX(id)"]+1;

		

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

		$rsLinguas->execute();

		$totalRows_rsLinguas = $rsLinguas->rowCount();

		

		while($row_rsLinguas = $rsLinguas->fetch()) {

			$insertSQL = "INSERT INTO destaques_".$row_rsLinguas["sufixo"]." (id, nome, titulo, link, target, texto, texto_botao, tema) VALUES (:id, :nome, :titulo, :link, :target, :texto, :texto_botao, :tema)";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

			$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5);		

			$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':texto_botao', $_POST['texto_botao'], PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':tema', $_POST['tema'], PDO::PARAM_STR, 5);

			$rsInsert->execute();

		}



		DB::close();

		

		header("Location: destaques-edit.php?id=".$id."&v=2&tab_sel=2");

	} 

}



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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['destaques']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="destaques.php"><?php echo $RecursosCons->RecursosCons['destaques']; ?> </a> <i class="fa fa-angle-right"></i></li>

          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?> </a> </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

          <form id="frm_destaques" name="frm_destaques" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['destaques_novo_registo']; ?> </div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='destaques.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>

                </div>

              </div>

              <div class="portlet-body">

                <div class="form-body">

                  <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">

                    </div>

                  </div>

                  <!-- <div class="form-group">

                    <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="texto" id="texto" value="<?php echo $_POST['texto']; ?>">

                    </div>

                  </div> -->

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="tema"><?php echo "Tema "; ?>: </label>

                    <div class="col-md-8">

                      <select name="tema" id="tema" class="form-control select2me">

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <option value="1"><?php echo "Tema 1 (Título e Subtitulo alinhado ao topo)"; ?></option>

                        <option value="2"><?php echo "Tema 2 (Título e Subtitulo alinhado ao centro)"; ?></option>

                        <option value="3"><?php echo "Tema 3 (Título e Subtitulo alinhado ao fundo)"; ?></option>

                      </select>

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $_POST['link']; ?>">

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>

                    <div class="col-md-3">

                      <select name="target" id="target" class="form-control select2me">

                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

                        <option value="_blank"><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>

                        <option value="_parent"><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>

                      </select>

                    </div>

                   <!--  <label class="col-md-2 control-label" for="texto_botao"><?php echo $RecursosCons->RecursosCons['texto_botao_label']; ?>: </label>

                    <div class="col-md-3">

                      <input type="text" class="form-control" name="texto_botao" id="texto_botao">

                    </div> -->

                  </div>

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="frm_destaques" />

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

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>

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

</body>

<!-- END BODY -->

</html>