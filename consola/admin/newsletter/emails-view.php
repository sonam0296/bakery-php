<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_mails';
$menu_sub_sel='';

$id=$_GET['id'];

$query_rsP = "SELECT * FROM newsletters_vistos WHERE email_id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsUser = "SELECT * FROM news_emails WHERE id=:id";
$rsUser = DB::getInstance()->prepare($query_rsUser);
$rsUser->bindParam(':id', $id, PDO::PARAM_INT);
$rsUser->execute();
$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);
$totalRows_rsUser = $rsUser->rowCount();

//Calcular o nº de emails enviados
$query_rsEmailsEnv = "SELECT COUNT(*) AS total FROM newsletters_vistos WHERE email_id=:id";
$rsEmailsEnv = DB::getInstance()->prepare($query_rsEmailsEnv);
$rsEmailsEnv->bindParam(':id', $id, PDO::PARAM_INT);
$rsEmailsEnv->execute();
$row_rsEmailsEnv = $rsEmailsEnv->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEmailsEnv = $rsEmailsEnv->rowCount();

//Calcular o nº de emails abertos
$query_rsEmailsAbertos = "SELECT * FROM newsletters_vistos WHERE email_id=:id";
$rsEmailsAbertos = DB::getInstance()->prepare($query_rsEmailsAbertos);
$rsEmailsAbertos->bindParam(':id', $id, PDO::PARAM_INT);
$rsEmailsAbertos->execute();
//$row_rsEmailsAbertos = $rsEmailsAbertos->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEmailsAbertos = $rsEmailsAbertos->rowCount();

$emails_abertos = 0;

while($row = $rsEmailsAbertos->fetch()) {
  $emails_abertos+=$row['vistos'];
}

//Calcular o nº de aberturas únicas
$query_rsAberturasUnicas = "SELECT COUNT(*) AS total FROM newsletters_vistos WHERE email_id=:id AND visto=1";
$rsAberturasUnicas = DB::getInstance()->prepare($query_rsAberturasUnicas);
$rsAberturasUnicas->bindParam(':id', $id, PDO::PARAM_INT);
$rsAberturasUnicas->execute();
$row_rsAberturasUnicas = $rsAberturasUnicas->fetch(PDO::FETCH_ASSOC);
$totalRows_rsAberturasUnicas = $rsAberturasUnicas->rowCount();

//Calcular média de aberturas por emails enviados
$total_enviados = $row_rsEmailsEnv['total'];
$total_vistos = $emails_abertos;

$media1 = round($total_vistos / $total_enviados, 2);

//Calcular percentagem de aberturas únicas por emails enviados
$total_vistos_unicos = $row_rsAberturasUnicas['total'];

$perc2 = round($total_vistos_unicos / $total_enviados, 2) * 100;

//Calcular média entre a data de envio e a data de abertura
$query_rsMedia = "SELECT * FROM newsletters_vistos WHERE email_id='$id'";
$rsMedia = DB::getInstance()->prepare($query_rsMedia);
$rsMedia->execute();
$totalRows_rsMedia = $rsMedia->rowCount();

$i=0;
$subtotal=0;

while($row = $rsMedia->fetch()) {
  if($row['data_visto'] != NULL) {
    $hours = round((strtotime($row['data_visto']) - strtotime($row['data_envio']))/(60*60));
    $subtotal+=$hours;
    $i++;
  }
}

$media2 = round($subtotal / $i, 2);

//Calcular a percentagem de visualizações únicas
$query_rsPerc = "SELECT * FROM newsletters_vistos WHERE email_id='$id'";
$rsPerc = DB::getInstance()->prepare($query_rsPerc);
$rsPerc->execute();
//$row_rsPerc = $rsPerc->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPerc = $rsPerc->rowCount();
DB::close();

$vistos = 0;
$vistos_unicos = 0;

while($row = $rsPerc->fetch()) {
  $vistos_unicos++;
  $vistos+=$row['vistos'];
}

$perc = round($vistos_unicos / $vistos, 3) * 100;

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
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
      <h3 class="page-title"> <?php echo $row_rsUser["email"]; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-search"></i><?php echo $RecursosCons->RecursosCons['dados']; ?></div>
              <div class="form-actions actions btn-set">
                <button type="button" name="edit" class="btn default" onClick="document.location='emails-edit.php?id=<?php echo $id; ?>'"><i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['editar']; ?></button>
                <button type="button" name="back" class="btn default" onClick="document.location='emails.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-9">
                  <div class="form-control" style="border:0; background-color:#EFEFEF"><strong><?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?></strong></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $RecursosCons->RecursosCons['total_emails']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $row_rsEmailsEnv['total']; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $row_rsEmailsEnv['abertos_label']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $emails_abertos; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $row_rsEmailsEnv['abertura_unica_label']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $row_rsAberturasUnicas['total']; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $row_rsEmailsEnv['media_aberturas_label']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $media1; ?> <?php echo $row_rsEmailsEnv['media_aberturas_info']; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $row_rsEmailsEnv['perc_aberturas_unicas']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $perc2."%"; ?> <?php echo $row_rsEmailsEnv['perc_abertuas_info']; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $row_rsEmailsEnv['media_temp_label']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $media2." hora(s)"; ?> <?php echo $row_rsEmailsEnv['media_temp_info']; ?></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row">
                <label class="col-md-3 control-label" style="text-align:right; margin-top:5px;"><strong><?php echo $row_rsEmailsEnv['racio_aberturas']; ?>:</strong></label>
                <div class="col-md-6">
                  <div class="form-control" style="border:0;"><?php echo $perc."%"; ?> <?php echo $row_rsEmailsEnv['racio_aberturas_info']; ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END PAGE CONTENT--> 
      </div>
    </div>
    <!-- END CONTENT -->
    <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
  </div>
  <!-- END CONTAINER --> 
</div>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>