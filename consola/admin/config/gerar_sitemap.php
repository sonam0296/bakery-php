<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='gerar_sitemap';

$query_rsP = "SELECT * FROM sitemap WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<script type="text/javascript">
function gera_sitemap() {
	document.getElementById('div_submit').style.display='none';
	document.getElementById('loading').style.display='block';
	
	$.post('gerar_sitemap_rpc.php', {op:"gera_sitemap"}, function(data) {
		document.getElementById('loading').style.display='none';
		document.getElementById('div_submit').style.display='block';

		window.location = 'gerar_sitemap.php?suc=1';
	});
}
</script>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title"><?php echo $RecursosCons->RecursosCons['sitemap']; ?> <small><?php echo $RecursosCons->RecursosCons['gera_ficheiro']; ?></small></h3>
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
						<a href="gerar_sitemap.php"><?php echo $RecursosCons->RecursosCons['sitemap']; ?></a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
      <?php if(isset($_GET['suc']) && $_GET['suc'] == 1) { ?>
      	<div class="alert alert-success display-show">
          <button class="close" data-close="alert"></button>
          <span><?php echo $RecursosCons->RecursosCons['sitemap_suc']; ?></span>
      	</div>
      <?php } ?>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-gear"></i> <?php echo $RecursosCons->RecursosCons['gera_sitemap']; ?> 
							</div>
							<div class="actions btn-set">
								<div id="div_submit" style="display: block;">
									<button class="btn green" onClick="gera_sitemap();"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['gera_sitemap']; ?> </button>
								</div>
							</div>
						</div>
						<div class="portlet-body" style="text-align: center; padding-top: 20px; font-size: 14px; line-height: 30px;">     
							<?php if($row_rsP['links'] > 0 && file_exists(ROOTPATH.'sitemap/sitemap.xml')) { ?>
                <?php echo $RecursosCons->RecursosCons['msg_sitemap_gerado_pt1']; ?> <strong><?php echo $row_rsP['data']; ?></strong> <?php echo $RecursosCons->RecursosCons['msg_sitemap_gerado_pt2']; ?> <strong><?php echo $row_rsP['links']; ?></strong> <?php echo $RecursosCons->RecursosCons['msg_sitemap_gerado_pt3']; ?>
                <br />
                <a href="<?php echo $row_rsP['url']; ?>sitemap/sitemap.xml" target="_blank" style="color:#000000;"><?php echo $row_rsP['url']; ?>sitemap/sitemap.xml</a>
              <?php }
              else { ?>
                <?php echo $RecursosCons->RecursosCons['msg_sitemap_nao_criado']; ?>
              <?php } ?>              
              <div id="loading" style="display: none; width: 100%; text-align: center; padding-top: 30px;">
              	<div style="margin: auto;"><img src="../../imgs/loading.gif" width="54" height="55" /></div>
              	<div style="margin: auto; font-size: 14px;"><?php echo $RecursosCons->RecursosCons['loading_gera_sitemap']; ?></div>
          		</div>
						</div>
					</div>
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