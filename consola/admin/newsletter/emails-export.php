<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='newsletter_mails';
$menu_sub_sel='';

$inserido=0;
$erro=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_exporta")) {
  $where="";
  if(isset($_POST['estado'])) {
    foreach($_POST['estado'] as $estado) {
      if(sizeof($_POST['estado']) < 2) {
        if($estado == 1) $where.=" AND news_emails.visivel='1'";
        else $where.=" AND news_emails.visivel='0'";
      } else $where.=" AND (news_emails.visivel='1' OR news_emails.visivel='0')";
    }
  }
  else {
    $erro = 2;
    header("location: emails-export.php?erro=2");
  }
  
  if(isset($_POST['aceita'])) {
    foreach($_POST['aceita'] as $aceita) {
      if(sizeof($_POST['aceita']) < 2) {
        if($aceita == 1) $where.=" AND news_emails.aceita='1'";
        else $where.=" AND (news_emails.aceita='0' OR news_emails.aceita IS NULL)";
      } else $where.=" AND (news_emails.aceita='1' OR (news_emails.aceita='0' OR news_emails.aceita IS NULL))";
    }
  }

  $array_lista="(";
  if(isset($_POST['listas'])) {
    foreach($_POST['listas'] as $lista) {
      if($array_lista=="(") $array_lista.=$lista;
      else $array_lista.=",".$lista;
    }
  }
  $array_lista.=")";
  
  if($where!="") {
    @unlink("data/emails.xlsx");
    
    date_default_timezone_set('Europe/London');
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

    /** Include PHPExcel */
    require_once '../Classes/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', utf8_encode($RecursosCons->RecursosCons['cli_email']))
                ->setCellValue('B1', utf8_encode($RecursosCons->RecursosCons['data_registo']))
                ->setCellValue('C1', utf8_encode($RecursosCons->RecursosCons['consentimento']))
                ->setCellValue('D1', utf8_encode($RecursosCons->RecursosCons['origem']))
                ->setCellValue('E1', utf8_encode($RecursosCons->RecursosCons['data_remocao']))
                ->setCellValue('F1', utf8_encode($RecursosCons->RecursosCons['origem_remocao']));

    if($array_lista!="()") {
      $query_rsEma = "SELECT news_emails.* FROM news_emails, news_emails_listas WHERE news_emails_listas.email=news_emails.id ".$where." AND news_emails_listas.lista IN ".$array_lista." GROUP BY news_emails.id ORDER BY news_emails.data DESC";
      $rsEma = DB::getInstance()->query($query_rsEma);
      $rsEma->execute();
      $totalRows_rsEma = $rsEma->rowCount();
      DB::close();
    }
    else {
      $query_rsEma = "SELECT * FROM news_emails WHERE news_emails.id!='0' ".$where." ORDER BY news_emails.data DESC";
      $rsEma = DB::getInstance()->query($query_rsEma);
      $rsEma->execute();
      $totalRows_rsEma = $rsEma->rowCount();
      DB::close();
    }

    $i = 2;
    if($totalRows_rsEma > 0) {  
      while($row_rsEma = $rsEma->fetch()) {
        $email = utf8_encode($row_rsEma['email']);
        $data_insc = utf8_encode($row_rsEma['data']);
        
        $aceita = utf8_encode("Sim");
        if($row_rsEma['aceita'] == 0) $aceita = utf8_encode("Não");
        
        $origem = utf8_encode($row_rsEma['origem']);
        $data_rem = utf8_encode($row_rsEma['data_remocao']);
        $origem_rem = utf8_encode($row_rsEma['origem_remocao']);

        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, $email)
          ->setCellValue('B'.$i, $data_insc)
          ->setCellValue('C'.$i, $aceita)
          ->setCellValue('D'.$i, $origem)
          ->setCellValue('E'.$i, $data_rem)
          ->setCellValue('F'.$i, $origem_rem);
        $i++;
      }
      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $objPHPExcel->setActiveSheetIndex(0);

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('data/emails.xlsx');

      $inserido = 1;
    }
    else {
      $erro=1;  
    } 
  }
}

$query_rsListas = "SELECT * FROM news_listas ORDER BY ordem ASC";
$rsListas = DB::getInstance()->prepare($query_rsListas);	
$rsListas->execute();
$totalRows_rsListas = $rsListas->rowCount();

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['news_page_title_emails']; ?> <small><?php echo $RecursosCons->RecursosCons['exportar']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['newsletters']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="#"><?php echo $RecursosCons->RecursosCons['exportar_emails']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_exporta" name="frm_exporta" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i> <?php echo $RecursosCons->RecursosCons['exportar']; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='emails.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['exportar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <?php if($inserido == 1) { ?>
                  <div class="alert alert-success">
                    <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['email_export_suc']; ?> </div>
                  <?php } ?>
                  <?php if($erro == 1) { ?>
                  <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['email_not_found_error']; ?>  </div>
                  <?php } ?>
                  <?php if($_GET['erro'] == 2) { ?>
                  <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['selec_estado']; ?>  </div>
                  <?php } ?>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-md-4 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['estado_enc_label']; ?> : <span class="required"> * </span></label>
                      <div class="col-md-6">
                        <div id="div_listas" class="form-control height-auto">
                          <div class="scroller" style="height:275px;" data-always-visible="1">
                            <ul class="list-unstyled">
                              <li>
                                <label>
                                  <input type="checkbox" name="estado[]" value="1">
                                  <?php echo $RecursosCons->RecursosCons['opt_ativos']; ?></label>
                              </li>
                              <li>
                                <label>
                                  <input type="checkbox" name="estado[]" value="2">
                                  <?php echo $RecursosCons->RecursosCons['opt_inativos']; ?></label>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['lista_label']; ?>: </label>
                    <div class="col-md-6">
                      <div id="div_listas" class="form-control height-auto">
                        <div class="scroller" style="height:275px;" data-always-visible="1">
                          <ul class="list-unstyled">
                            <?php if($totalRows_rsListas >0){ ?>
                            <?php while($row_rsListas = $rsListas->fetch()) { ?>
                            <li>
                              <label>
                                <input type="checkbox" name="listas[]" value="<?php echo $row_rsListas['id']; ?>">
                                <?php echo $row_rsListas['nome']; ?></label>
                            </li>
                            <?php } ?>
                            <?php } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_exporta" />
          </form>
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
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
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
function sucesso(){
	
	document.location = 'emails-export-rpc.php?op=exporta_mails';
}

<?php if($inserido==1){?>
	sucesso();
<?php }?>

</script>
</body>
<!-- END BODY -->
</html>