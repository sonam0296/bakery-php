<?php include_once('../inc_pages.php'); ?>

<?php 



$menu_sel='banners';

$menu_sub_sel='banners_h';



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_banners_h")) {

  if($_POST['nome']!='') {

    $insertSQL = "SELECT MAX(id) FROM banners_h_en";

    $rsInsert = DB::getInstance()->prepare($insertSQL);

    $rsInsert->execute();

    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

    

    $id = $row_rsInsert["MAX(id)"]+1;

    

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

    $rsLinguas->execute();

    $totalRows_rsLinguas = $rsLinguas->rowCount();

    

    $datai = NULL;

    if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];

    $dataf = NULL;

    if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];

    

    while($row_rsLinguas = $rsLinguas->fetch()) {

      $insertSQL = "INSERT INTO banners_h_".$row_rsLinguas["sufixo"]." (id, tipo, datai, dataf, nome, titulo, subtitulo, link_class, link, texto_link, target, video, text_alignv, text_alignh, slide_duration) VALUES (:id, :tipo, :datai, :dataf, :nome, :titulo, :subtitulo, :link_class, :link, :texto_link, :target, :video, :text_alignv, :text_alignh, :slide_duration)";

      $rsInsert = DB::getInstance()->prepare($insertSQL);

      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 

      $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT); 

      $rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR, 5);  

      $rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);  

      $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':link_class', $_POST['link_class'], PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':link', $_POST['link'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':texto_link', $_POST['texto_link'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':subtitulo', $_POST['subtitulo'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':target', $_POST['target'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':video', $_POST['video'], PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':text_alignv', $_POST['text_alignv'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':text_alignh', $_POST['text_alignh'], PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':slide_duration', $_POST['slide_duration'], PDO::PARAM_INT); 

      $rsInsert->execute();

    }



    DB::close();



    alteraSessions('banners');

    

    if($_POST['tipo'] == 1) {

      header("Location: slideshow-edit.php?id=".$id."&ins=1");

    }

    else {

      header("Location: slideshow.php?ins=1");

    }

  }

}



?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<style type="text/css">

  .div_video {

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

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['banner_page_title']; ?> <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="slideshow.php"><?php echo $RecursosCons->RecursosCons['banner_page_title']; ?></a> <i class="fa fa-angle-right"></i> </li>

          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a> </li>

        </ul>

      </div>

      <!-- END PAGE HEADER--> 

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">

          <form id="frm_banners_h" name="frm_banners_h" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['banner_novo_registo']; ?> </div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='slideshow.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>

                </div>

              </div>

              <div class="portlet-body">

                <div class="form-body">

                  <div class="alert alert-danger display-hide">

                    <button class="close" data-close="alert"></button>

                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?></div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: </label>

                    <div class="col-md-3">

                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                        <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $_POST['datai']; ?>">

                        <span class="input-group-btn">

                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                        </span> 

                      </div>

                    </div>

                    <label class="col-md-2 control-label" for="dataf"><?php echo $RecursosCons->RecursosCons['data_fim_label']; ?>: </label>

                    <div class="col-md-3">

                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                        <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo $_POST['dataf']; ?>">

                        <span class="input-group-btn">

                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                        </span> 

                      </div>

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span> </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control" name="tipo" id="tipo">

                        <option value="1"><?php echo $RecursosCons->RecursosCons['tipo_banner_1']; ?></option>

                        <option value="2"><?php echo $RecursosCons->RecursosCons['tipo_banner_2']; ?></option>

                      </select>

                      <p class="help-block"><i><?php echo $RecursosCons->RecursosCons['tipo_banner_help']; ?></i></p>

                    </div>

                  </div>



                  <!-- <div class="form-group">

                    <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['slide_duration_label']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control" name="slide_duration" id="slide_duration">

                      <?php

                          $i = 1; 

                          while ($i <= 10) { ?>

                            <option value="<?php echo $i*1000; ?>"><?php echo $i; ?> sec</option>

                          <?php 

                          $i++;

                        } 

                      ?>

                      </select>

                      <p class="help-block"><i><?php echo $RecursosCons->RecursosCons['slide_duration_help']; ?></i></p>

                    </div>

                  </div> -->

                  

                  <div class="form-group div_imagem">

                    <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $_POST['titulo']; ?>">

                    </div>

                  </div>

                  <div class="form-group div_imagem">

                    <label class="col-md-2 control-label" for="subtitulo"><?php echo $RecursosCons->RecursosCons['subtitulo_label']; ?>: </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="subtitulo" id="subtitulo" value="<?php echo $_POST['subtitulo']; ?>">

                    </div>

                  </div>

                  <div class="form-group div_imagem">

                    <label class="col-md-2 control-label" for="link"><?php echo $RecursosCons->RecursosCons['link_label']; ?>: </label>

                    <div class="col-md-8">

                      <input type="text" class="form-control" name="link" id="link" value="<?php echo $_POST['link']; ?>">

                    </div>

                  </div>

                  <div class="form-group div_imagem">

                    <label class="col-md-2 control-label" for="target"><?php echo $RecursosCons->RecursosCons['target_link']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control" name="target" id="target">

                        <option value="_blank"><?php echo $RecursosCons->RecursosCons['opt_nova_janela']; ?></option>

                        <option value="_parent"><?php echo $RecursosCons->RecursosCons['opt_mesma-janela']; ?></option>

                      </select>

                    </div>

                    <label class="col-md-2 control-label" for="texto_link"><?php echo $RecursosCons->RecursosCons['texto_link']; ?>: </label>

                    <div class="col-md-3">

                      <input type="text" class="form-control" name="texto_link" id="texto_link" value="<?php echo $_POST['texto_link']; ?>">

                      <p class="help-block"><i><?php echo $RecursosCons->RecursosCons['texto_link_help']; ?></i></p>

                    </div>

                  </div>

                  <div class="form-group div_imagem">

                    <label class="col-md-2 control-label" for="text_alignh"><?php echo $RecursosCons->RecursosCons['alinhar_texto_horizontal_label']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control" name="text_alignh" id="text_alignh">

                        <option value="left"><?php echo $RecursosCons->RecursosCons['opt_esquerda']; ?></option>

                        <option value="center"><?php echo $RecursosCons->RecursosCons['opt_centro']; ?></option>

                        <option value="right"><?php echo $RecursosCons->RecursosCons['opt_direita']; ?></option>

                      </select>

                    </div>

                    <label class="col-md-2 control-label" for="text_alignv"><?php echo $RecursosCons->RecursosCons['alinhar_texto_vertical_label']; ?>: </label>

                    <div class="col-md-3">

                      <select class="form-control" name="text_alignv" id="text_alignv">

                        <option value="top"><?php echo $RecursosCons->RecursosCons['opt_topo']; ?></option>

                        <option value="middle"><?php echo $RecursosCons->RecursosCons['opt_meio']; ?></option>

                        <option value="bottom"><?php echo $RecursosCons->RecursosCons['opt_baixo']; ?></option>

                      </select>

                    </div>

                  </div>

                  <div class="form-group div_video">

                    <label class="col-md-2 control-label" for="video"><?php echo $RecursosCons->RecursosCons['video_label']; ?>: </label>

                    <div class="col-md-8">

                      <textarea class="form-control" name="video" id="video" rows="2"><?php echo $_POST['video']; ?></textarea>

                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['help_block_video']; ?></p>

                    </div>

                  </div>

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="frm_banners_h" />

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

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<!-- BEGIN PAGE LEVEL SCRIPTS --> 

<script src="slideshow-validation.js"></script> 

<!-- END PAGE LEVEL SCRIPTS --> 

<script>

jQuery(document).ready(function() {    

  Metronic.init(); // init metronic core components

  Layout.init(); // init current layout

  QuickSidebar.init(); // init quick sidebar

  Demo.init(); // init demo features

  Form.init();



  $('#tipo').on('change', function() {

    if($(this).val() == 1) {

      $('.div_imagem').css('display', 'block');

      $('.div_video').css('display', 'none');

    }

    else {

      $('.div_imagem').css('display', 'none');

      $('.div_video').css('display', 'block');

    }

  });



  $('#link_class').trigger('change');

});



function previewBtn(class_btn) {

  $.post("slideshow-rpc.php", {op: "preview_btn", class: class_btn}, function(data) {

    $('#btn_preview').html(data);

  });

}

</script> 

</body>

<!-- END BODY -->

</html>