<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='redes_sociais';

$inserido=0;
$erro_password=0;
$tab_sel=0;

$editFormAction = $_SERVER['PHP_SELF'];
if(isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	$query_rsP = "SELECT * FROM redes_sociais WHERE visivel='1' ORDER BY ordem ASC, id ASC";
	$rsP = DB::getInstance()->query($query_rsP);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();
	
	if($totalRows_rsP > 0) {
		while($row_rsP = $rsP->fetch()) {
			$id=$row_rsP['id'];
			
			$link=$_POST['link_'.$id];
			$mostra_topo = 0;
			if(isset($_POST['mostra_topo_'.$id])) $mostra_topo=1;	
		
			$insertSQL = "UPDATE redes_sociais SET link=:link, mostra_topo=:mostra_topo WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':link', $link, PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':mostra_topo', $mostra_topo, PDO::PARAM_INT);	
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);	
			$rsInsert->execute();
			DB::close();
		}
	}

	alteraSessions('redes');
	
	$inserido=1;	
}

$query_rsP = "SELECT * FROM redes_sociais WHERE visivel='1' ORDER BY ordem ASC, id ASC";
$rsP = DB::getInstance()->query($query_rsP);
$totalRows_rsP = $rsP->rowCount();
DB::close();

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
			<h3 class="page-title">
			<?php echo $RecursosCons->RecursosCons['redes_sociais']; ?><small> <?php echo $RecursosCons->RecursosCons['link_redes_sociais']; ?></small>
			</h3>
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
						<a href="redes_sociais.php"><?php echo $RecursosCons->RecursosCons['redes_sociais']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
          <?php if($totalRows_rsP>0) { ?>
        	<form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data" class="form-horizontal">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-desktop"></i><?php echo $RecursosCons->RecursosCons['redes_sociais']; ?>
								</div>
								<div class="actions btn-set">
									<button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
									<button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>						
								</div>
							</div>
							<div class="portlet-body"> 
                <div class="form-body">
                  <div class="alert alert-success<?php if($inserido!=1) echo " display-hide"; ?>">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['alt_dados']; ?>
                  </div>
                  <?php while($row_rsP = $rsP->fetch()) { ?>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="link_<?php echo $row_rsP['id']; ?>"><?php echo $row_rsP['nome']; ?>:</label>
                        <div class="col-md-4">
                          <input type="text" class="form-control" name="link_<?php echo $row_rsP['id']; ?>" id="email_<?php echo $link_['id']; ?>" value="<?php echo $row_rsP['link']; ?>">
                        </div>
                        <!-- <div class="col-md-2">
                          <label class="control-label"><input type="checkbox" class="form-control" name="mostra_topo_<?php echo $row_rsP['id']; ?>" value="1" <?php if($row_rsP['mostra_topo'] == 1) { ?>checked<?php } ?>>&nbsp;Mostra topo</label>
                        </div> -->
                    </div>
                  <?php } ?>   
                </div>			
							</div>
						</div>
            <input type="hidden" name="MM_insert" value="alterar" />
					</form>
          <?php } ?>    
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	</div>
	<!-- END CONTENT -->
    <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
});
</script>
</body>
<!-- END BODY -->
</html>