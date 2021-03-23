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

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	$manter = $_POST['manter'];
	
	$id=$_POST['id'];

	$query_rsP = "SELECT * FROM metatags".$extensao." WHERE id=:id AND visivel='1'";
	$rsP = DB::getInstance()->prepare($query_rsP);
	$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);		
	$rsP->execute();
	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsP = $rsP->rowCount();
	DB::close();

	if($row_rsP['editar'] == 1) {
		$url=strtolower(verifica_nome($_POST['url']));		
			
		$query_rsProc = "SELECT * FROM metatags".$extensao." WHERE url like '$url' AND id!=:id";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->bindParam(':id', $id, PDO::PARAM_INT, 5);
		$rsProc->execute();
		$totalRows_rsProc = $rsProc->rowCount();
		DB::close();
		
		if($totalRows_rsProc>0) {
			$url=$url."-".$id;
		}	
		
		//REDIRECT 301
		if($row_rsP['url'] != $url) 
			redirectURL($row_rsP['url'], $url, substr($extensao,1));
	}
	else
		$url = $row_rsP['url'];
	
	$insertSQL = "UPDATE metatags".$extensao." SET url=:url, title=:title, description=:description, keywords=:keywords WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':url', $url, PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':title', $_POST['title'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':description', $_POST['description'], PDO::PARAM_STR, 5);	
	$rsInsert->bindParam(':keywords', $_POST['keywords'], PDO::PARAM_STR, 5);
	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);		
	$rsInsert->execute();
	DB::close();

	alteraSessions('metatags');
	
	$inserido=1;
	
	if(!$manter) header("Location: metatags.php?alt=1");
}

$query_rsP = "SELECT * FROM metatags".$extensao." WHERE id=:id AND visivel='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);		
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$menu_sel='configuracao';
$menu_sub_sel='metatags';

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
			<?php echo $RecursosCons->RecursosCons['metatags']; ?> <small><?php echo $RecursosCons->RecursosCons['gestao_metatags_titulo']; ?></small>
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
						<a href="metatags.php"><?php echo $RecursosCons->RecursosCons['metatags']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
        <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
        <?php if($totalRows_rsP>0){ ?>
        	<form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data" class="form-horizontal">
        		<input type="hidden" name="manter" id="manter" value="0">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-tag"></i><?php echo $RecursosCons->RecursosCons['metatags']; ?> - <?php echo $row_rsP['pagina']; ?>
								</div>
								<div class="actions btn-set">
									<button type="button" name="back" class="btn default" onClick="document.location='metatags.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.caract_categ.submit();"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1'; document.caract_categ.submit();"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
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
                  	<label class="col-md-2 control-label" for="url"><?php echo $RecursosCons->RecursosCons['url_label']; ?>: </label>
                  	<div class="col-md-10">
                  		<input type="text" class="form-control" name="url" id="url" value="<?php echo $row_rsP['url']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()" <?php if($row_rsP['editar'] == 0) { ?>disabled<?php } ?>>
                  	</div>
                  </div>
	                <div class="form-group">
                    <label class="col-md-2 control-label" for="title"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="title" id="title" value="<?php echo $row_rsP['title']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">
                    </div>
	                </div>
	                <div class="form-group">
                    <label class="col-md-2 control-label" for="description"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>:</label>
                    <div class="col-md-10">
                    	<textarea class="form-control" rows="5" id="description" name="description" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['description']; ?></textarea>
                    </div>
	                </div>
	                <div class="form-group">
                    <label class="col-md-2 control-label" for="keywords"><?php echo $RecursosCons->RecursosCons['palavras-chave_label']; ?>:</label>
                    <div class="col-md-10">
                    	<textarea class="form-control" rows="5" id="keywords" name="keywords" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['keywords']; ?></textarea>
                    <span class="help-block"><strong><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>:</strong> <?php echo $RecursosCons->RecursosCons['nota_palavra-chave']; ?></span>
                    </div>
	                </div>
	                <div class="form-group">
                    <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['pre-view_google_label']; ?>:</label>
                    <div class="col-md-10" style="padding:0 15px">
                    	<div style="border:1px solid #e5e5e5;min-height:50px;padding:10px" id="googlePreview"></div>
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
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
});
</script>
<script type="text/javascript">
<?php if($row_rsP['blog'] == 1){?>
meta_blog=1;
<?php }?>
document.ready=carregaPreview();
</script>
</body>
<!-- END BODY -->
</html>