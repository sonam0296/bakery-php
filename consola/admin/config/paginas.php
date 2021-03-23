<?php include_once('../inc_pages.php'); ?>
<?php 

$id=$_GET['id'];

$inserido=0;
$erro_password=0;
$tab_sel=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	
	$id=$_POST['id'];
	
	$insertSQL = "UPDATE textos".$extensao." SET texto=:texto WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);			
	$rsInsert->execute();
	DB::close();
	
	$inserido=1;
		
}


$query_rsP = "SELECT * FROM textos".$extensao." WHERE id=:id AND visivel='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);		
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();


$menu_sel='paginas';
$menu_sub_sel='paginas_'.$row_rsP['id'];

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
			<?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $RecursosCons->RecursosCons['conteudo_website']; ?></small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['paginas']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="paginas.php?id=<?php echo $row_rsP['id']; ?>"><?php echo $row_rsP['nome']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
                
                <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
                
                <?php if($totalRows_rsP>0){ ?>
                	<form action="<?php echo $editFormAction; ?>" method="POST" id="paginas" role="form" enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" name="id_pagina" id="id_pagina" value="<?php echo $row_rsP["id"]; ?>">
                        <div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-file"></i><?php echo $row_rsP['nome']; ?>
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
                                    	                                        
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['conteudo_label']; ?>:</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="texto" id="texto" style="resize:none;height:250px"><?php echo $row_rsP['texto']; ?></textarea>
                                            </div>
                                        </div>
                                    
                                </div>		
										
							</div>
						</div>
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script>
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   FormValidation.init(); // init quick sidebar
});
</script>
<script type="text/javascript">
CKEDITOR.replace('texto',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	height: '600px',
	toolbar : "Full"
	
});
CKEDITOR.replace('sub_titulo',
{
	toolbar : "Basic"
	
});
</script>
</body>
<!-- END BODY -->
</html>