<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

$id=$_GET['id'];

$query_rsP = "SELECT * FROM newsletters WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

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
      <h3 class="page-title"> <?php echo $row_rsP["titulo"]; ?> <small>descarregar</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="newsletter.php">Newsletters</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-search"></i>Newsletters - <?php echo $row_rsP["titulo"]; ?> </div>
              <div class="form-actions actions btn-set">
                <button type="button" name="back" class="btn default" onClick="document.location='newsletter.php?id=<?php echo $row_rsP["id"]; ?>'"><i class="fa fa-angle-left"></i> Voltar</button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">
                <label class="col-md-2 control-label" for="texto" style="text-align:right; margin-top:5px;"><strong>Seleccionar tudo:</strong></label>
                <div class="col-md-4">
                  <div class="form-control" style="border:0;"><span class="label label-danger">Nota:</span> <span>Para Copiar fa&ccedil;a 'Ctrl + C' ou a tecla direita do rato</span><br />
                    <br />
                    <textarea name="texto" id="texto" class="cx-1" style="width:100%; height:340px;" readonly="readonly" onClick="selectCode()"><?php echo file_get_contents(HTTP_DIR.'/consola/admin/newsletter/newsletter-edit.php?id='.$row_rsP['id'].'&gera_imagens=0'); ?></textarea>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row"><button class="btn blue" onClick="window.open('newsletter-edit.php?id=<?php echo $row_rsP['id']; ?>&fin=1','_blank')" title="Ver news"><i class="fa fa-search"></i></span></button></div>
                  <div class="clearfix"></div>
                  <div class="row" style="margin-top:10px;"><button class="btn green" onClick="document.location='newsletter-download.php?id=<?php echo $row_rsP['id']; ?>'" title="Fazer download"><i class="fa fa-download"></i></span></button></div>
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

function selectCode(){
	if(document.getElementById('texto').value.length>0){
        document.getElementById('texto').focus(); 
        document.getElementById('texto').select();
    } 
} 
</script>
</body>
<!-- END BODY -->
</html>