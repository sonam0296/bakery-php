<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='newsletter_logs';
$menu_sub_sel='';

$id=$_GET['id'];

$query_rsP = "SELECT * FROM newsletters_logs ORDER BY data DESC";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
//$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
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
      <h3 class="page-title"> Newsletter <small>logs</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption"> </div>
              <div class="form-actions actions btn-set">
                <button type="button" name="back" class="btn default" onClick="document.location='newsletter-historico.php?id=<?php echo $row_rsNews["id"]; ?>'"><i class="fa fa-angle-left"></i> Voltar</button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-12">
                  <?php if($totalRows_rsP>0){?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <?php $cor_cor="#EDEDED"; while($row_rsP = $rsP->fetch()) { if($cor_cor=="#EDEDED") $cor_cor="#CCCCCC"; else $cor_cor="#EDEDED"; ?>
                    <tr>
                      <td align="left" valign="middle" height="35" bgcolor="<?php echo $cor_cor; ?>" style="padding:0 15px;"><?php
                            $mensagem=$row_rsP['data']." » ";
                            if($row_rsP['utilizador']) $mensagem.="<strong>".$row_rsP['utilizador']."</strong> ";
                            $mensagem.=$row_rsP['que_fez']." » newsletter <strong>".$row_rsP['newsletter']."</strong> ";
                            if($row_rsP['listas']) $mensagem.=" para a(s) lista(s) ".$row_rsP['listas'].".";
                            
                            echo $mensagem; ?></td>
                    </tr>
                    <?php }?>
                  </table>
                  <?php }else{?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="100" align="center" valign="middle"><strong>Sem hist&oacute;rico gerado.</strong></td>
                    </tr>
                  </table>
                  <?php } ?>
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