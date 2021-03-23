<!-- BEGIN FOOTER -->

<div class="page-footer">

	<div class="page-footer-inner">

		 <?php echo $RecursosCons->RecursosCons['footer_direitos']; ?> | <a href="https://webtech-evolution.com" class="animationcss">Webtech-Evolution &reg;</a>

	</div>

	<div class="scroll-to-top">

		<i class="icon-arrow-up"></i>

	</div>

</div>

<?php if(file_exists(ROOTPATH_CONSOLA."assets/global/scripts/datatable_".$lingua_consola.".js")) { ?>

	<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/scripts/datatable_en.js"></script>

<?php } else { ?>

	<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/scripts/datatable_en.js"></script>

<?php } ?>

<!-- END FOOTER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

<!-- BEGIN CORE PLUGINS -->

<!--[if lt IE 9]>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/respond.min.js"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/excanvas.min.js"></script> 

<![endif]-->

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>

<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

<!-- END CORE PLUGINS -->