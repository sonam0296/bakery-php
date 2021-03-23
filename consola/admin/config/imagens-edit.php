<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='imagens';

if($row_rsUser['username'] != 'netg') {
  header("Location: ../index.php");
  exit();
}

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_imagens")) {
  $manter = $_POST['manter'];
  
  if($_POST['imagem1_larg'] != '' && $_POST['imagem1_alt']) {
    $imagem1 = $_POST['imagem1_larg']."x".$_POST['imagem1_alt'];

    if($_POST['imagem2_larg'] != '' && $_POST['imagem2_alt']) {
      $imagem2 = $_POST['imagem2_larg']."x".$_POST['imagem2_alt'];
    }
    else {
      $imagem2 = NULL;
    }

    if($_POST['imagem3_larg'] != '' && $_POST['imagem3_alt']) {
      $imagem3 = $_POST['imagem3_larg']."x".$_POST['imagem3_alt'];
    }
    else {
      $imagem3 = NULL;
    }

    if($_POST['imagem4_larg'] != '' && $_POST['imagem4_alt']) {
      $imagem4 = $_POST['imagem4_larg']."x".$_POST['imagem4_alt'];
    }
    else {
      $imagem4 = NULL;
    }

    $query_rsP = "UPDATE config_imagens SET imagem1=:imagem1, min_height1=:min_height1, max_width1=:max_width1, imagem2=:imagem2, min_height2=:min_height2, max_width2=:max_width2, imagem3=:imagem3, min_height3=:min_height3, max_width3=:max_width3, imagem4=:imagem4, min_height4=:min_height4, max_width4=:max_width4 WHERE id = '$id'";
    $rsP = DB::getInstance()->prepare($query_rsP);
    $rsP->bindParam(':imagem1', $imagem1, PDO::PARAM_STR, 5);
    $rsP->bindParam(':imagem2', $imagem2, PDO::PARAM_STR, 5);
    $rsP->bindParam(':imagem3', $imagem3, PDO::PARAM_STR, 5);
    $rsP->bindParam(':imagem4', $imagem4, PDO::PARAM_STR, 5);
    $rsP->bindParam(':min_height1', $_POST['min_height1'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':max_width1', $_POST['max_width1'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':min_height2', $_POST['min_height2'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':max_width2', $_POST['max_width2'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':min_height3', $_POST['min_height3'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':max_width3', $_POST['max_width3'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':min_height4', $_POST['min_height4'], PDO::PARAM_STR, 5);
    $rsP->bindParam(':max_width4', $_POST['max_width4'], PDO::PARAM_STR, 5);
    $rsP->execute();
    DB::close();

    //Gerar fill das imagens
    gerarFill($id);

    if(!$manter) {
      header("Location: imagens.php?alt=1");
    }
    else {
      header("Location: imagens-edit.php?id=".$id."&alt=1");
    }
  }
}

$query_rsP = "SELECT * FROM config_imagens WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);   
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$parts = explode("x", $row_rsP['imagem1']);
$imagem1_larg = $parts['0']; 
$imagem1_alt = $parts['1']; 

$parts = explode("x", $row_rsP['imagem2']);
$imagem2_larg = $parts['0']; 
$imagem2_alt = $parts['1']; 

$parts = explode("x", $row_rsP['imagem3']);
$imagem3_larg = $parts['0']; 
$imagem3_alt = $parts['1']; 

$parts = explode("x", $row_rsP['imagem4']);
$imagem4_larg = $parts['0']; 
$imagem4_alt = $parts['1']; 

//Obter legenda dos campos para cada área
$legenda1 = "";
$tem_imagem1 = 1;
$legenda2 = "";
$tem_imagem2 = 0;
$legenda3 = "";
$tem_imagem3 = 0;
$legenda4 = "";
$tem_imagem4 = 0;

if($row_rsP['titulo'] == 'Produtos') {
  $legenda1 = "(imagem zoom)";
  $legenda2 = "(imagem detalhe)";
  $legenda3 = "(imagem listagem)";
  $legenda4 = "(imagem pequena)";

  $tem_imagem2 = 1;
  $tem_imagem3 = 1;
  $tem_imagem4 = 1;
}
else if($row_rsP['titulo'] == 'Banners') {
  $legenda1 = "(imagem desktop)";
  $legenda2 = "(imagem mobile)";

  $tem_imagem2 = 1;
}
else if($row_rsP['titulo'] == 'Paginas') {
  $legenda1 = "(imagem topo e bloco galeria fullscreen)";
  $legenda2 = "(imagem bloco galeria sem fullscreen)";
  $legenda3 = "(imagem bloco texto/imagem)";

  $tem_imagem2 = 1;
  $tem_imagem3 = 1;
}
else if($row_rsP['titulo'] == 'Noticias') {
  $legenda1 = "(imagens galeria)";
  $legenda2 = "(imagem principal)";

  $tem_imagem2 = 1;
}
else if($row_rsP['titulo'] == 'Testemunhos') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Categorias') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Marcas') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Blog') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Equipa') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Destaques') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Catálogos') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Galerias') {
  $legenda1 = "(imagem principal)";
}
else if($row_rsP['titulo'] == 'Contactos') { 
  $legenda1 = "(fill mapa)";
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content"> 
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title"><?php echo $RecursosCons->RecursosCons['imagens']; ?> <small><?php echo $RecursosCons->RecursosCons['gestao_imagens']; ?></small></h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['configuracao']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="imagens.php"><?php echo $RecursosCons->RecursosCons['imagens']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_imagens" name="frm_imagens" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-imagem"></i><?php echo $RecursosCons->RecursosCons['imagens']; ?> - <?php echo $row_rsP["titulo"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='imagens.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>            
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>    
                  <?php if($_GET['alt'] == 1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                       <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </div>
                  <?php } ?>
                  <!-- <div class="form-group">
                    <div class="col-md-2"></div>                  
                    <div class="col-md-8">
                      <div class="clearfix margin-top-20 margin-bottom-20"> <span class="label label-danger">Nota!</span> <strong>As imagens são sempre apresentadas da maior para a mais pequena.</strong> </div>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"> <?php echo $RecursosCons->RecursosCons['nome_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['titulo']; ?>" disabled>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-8"><strong> <?php echo $RecursosCons->RecursosCons['imagem1_label']; ?></strong> <?php echo $legenda1; ?></div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="imagem1_larg"> <?php echo $RecursosCons->RecursosCons['largura']; ?>: <span class="required">*</span></label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="imagem1_larg" id="imagem1_larg" value="<?php echo $imagem1_larg; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        <span class="input-group-addon">Px</span>
                      </div>
                    </div>
                    <label class="col-md-2 control-label" for="imagem1_alt"> <?php echo $RecursosCons->RecursosCons['altura']; ?>: <span class="required">*</span></label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="imagem1_alt" id="imagem1_alt" value="<?php echo $imagem1_alt; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        <span class="input-group-addon">Px</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['largura_max']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="max_width1" id="max_width1" value="<?php echo $row_rsP['max_width1']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        <span class="input-group-addon">Px</span>
                      </div>
                    </div>
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['altura_min']; ?>: </label>
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="text" class="form-control" name="min_height1" id="min_height1" value="<?php echo $row_rsP['min_height1']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        <span class="input-group-addon">Px</span>
                      </div>
                    </div>
                  </div>
                  <?php if($tem_imagem2 == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <div class="col-md-2"></div>
                      <div class="col-md-8"><strong><?php echo $RecursosCons->RecursosCons['imagem2_label']; ?></strong> <?php echo $legenda2; ?></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="imagem2_larg"><?php echo $RecursosCons->RecursosCons['largura']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="imagem2_larg" id="imagem2_larg" value="<?php echo $imagem2_larg; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                      <label class="col-md-2 control-label" for="imagem2_alt"><?php echo $RecursosCons->RecursosCons['altura']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="imagem2_alt" id="imagem2_alt" value="<?php echo $imagem2_alt; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['largura_max']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="max_width2" id="max_width2" value="<?php echo $row_rsP['max_width2']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                      <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['altura_min']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="min_height2" id="min_height2" value="<?php echo $row_rsP['min_height2']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if($tem_imagem3 == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <div class="col-md-2"></div>
                      <div class="col-md-8"><strong><?php echo $RecursosCons->RecursosCons['imagem3_label']; ?></strong> <?php echo $legenda3; ?></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="imagem3_larg"><?php echo $RecursosCons->RecursosCons['largura']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="imagem3_larg" id="imagem3_larg" value="<?php echo $imagem3_larg; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                      <label class="col-md-2 control-label" for="imagem3_alt"><?php echo $RecursosCons->RecursosCons['altura']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="imagem3_alt" id="imagem3_alt" value="<?php echo $imagem3_alt; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['largura_max']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="max_width3" id="max_width3" value="<?php echo $row_rsP['max_width3']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                      <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['altura_min']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="min_height3" id="min_height3" value="<?php echo $row_rsP['min_height3']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if($tem_imagem4 == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <div class="col-md-2"></div>
                      <div class="col-md-8"><strong><?php echo $RecursosCons->RecursosCons['imagem4_label']; ?></strong> <?php echo $legenda4; ?></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="imagem4_larg"><?php echo $RecursosCons->RecursosCons['largura']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="imagem4_larg" id="imagem4_larg" value="<?php echo $imagem4_larg; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                      <label class="col-md-2 control-label" for="imagem4_alt"><?php echo $RecursosCons->RecursosCons['altura']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="imagem4_alt" id="imagem4_alt" value="<?php echo $imagem4_alt; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['largura_max']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="max_width4" id="max_width4" value="<?php echo $row_rsP['max_width4']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                      <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['altura_min']; ?>: </label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" name="min_height4" id="min_height4" value="<?php echo $row_rsP['min_height4']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                          <span class="input-group-addon">Px</span>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_imagens" />
          </form>
        </div>
      </div>
    </div>
    <!-- END PAGE CONTENT--> 
  </div>
</div>
<!-- END CONTENT -->
<?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<script src="imagens-validation.js"></script> 
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