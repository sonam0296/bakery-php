<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='users_perfil';
$menu_sub_sel='';

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?> page-sidebar-closed-hide-logo page-container-bg-solid">
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
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['perfil']; ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="perfil.php"><?php echo $RecursosCons->RecursosCons['entrada_label']; ?></a>
					</li>
				</ul>				
			</div>
			<h3 class="page-title">
			<?php echo $RecursosCons->RecursosCons['meu_perfil']; ?> <small><?php echo $RecursosCons->RecursosCons['entrada_label']; ?></small>
			</h3>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row margin-top-20">
				<div class="col-md-12">
					<!-- BEGIN PROFILE SIDEBAR -->
					<div class="profile-sidebar">
						<!-- PORTLET MAIN -->
						<div class="portlet light profile-sidebar-portlet">
							<!-- SIDEBAR USERPIC -->
							<div class="profile-userpic">
                            	<?php if($row_rsUser['imagem1']!="" && file_exists("../../imgs/user/".$row_rsUser['imagem1'])){ ?>
								<img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/user/<?php echo $row_rsUser['imagem1']; ?>" class="img-responsive" width="150" alt="">
                                <?php } ?>
							</div>
							<!-- END SIDEBAR USERPIC -->
							<!-- SIDEBAR USER TITLE -->
							<div class="profile-usertitle">
								<div class="profile-usertitle-name">
									 <?php echo $nome_mostra; ?>
								</div>
								<div class="profile-usertitle-job">
									 <?php echo $RecursosCons->RecursosCons['funcao_user']; ?>
								</div>
							</div>
							<!-- END SIDEBAR USER TITLE -->
							<!-- SIDEBAR MENU -->
							<div class="profile-usermenu">
								<ul class="nav">
									<li class="active">
										<a href="perfil.php">
										<i class="icon-home"></i>
										<?php echo $RecursosCons->RecursosCons['entrada_label']; ?></a>
									</li>
									<li>
										<a href="perfil-alterar.php">
										<i class="icon-settings"></i>
										<?php echo $RecursosCons->RecursosCons['definicoes_conta']; ?></a>
									</li>
									<li>
										<a href="calendario.php">
										<i class="icon-calendar"></i>
										<?php echo $RecursosCons->RecursosCons['calendario_label']; ?> </a>
									</li>
									<li>
										<a href="mensagens.php">
										<i class="icon-envelope-open"></i>
										<?php echo $RecursosCons->RecursosCons['mensagens']; ?> </a>
									</li>
									<li>
										<a href="tarefas.php">
										<i class="icon-rocket"></i>
										<?php echo $RecursosCons->RecursosCons['tarefas_label']; ?> </a>
									</li>
								</ul>
							</div>
							<!-- END MENU -->
						</div>
					</div>
					<!-- END BEGIN PROFILE SIDEBAR -->
					<!-- BEGIN PROFILE CONTENT -->
					<div class="profile-content">
						<div class="row">
							<div class="col-md-6">
								<!-- BEGIN PORTLET -->
								<div class="portlet light ">
									<div class="portlet-title">
										<div class="caption caption-md">
											<i class="icon-bar-chart theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold uppercase"><?php echo $RecursosCons->RecursosCons['ultimas_msgs']; ?></span>
										</div>										
									</div>
									<div class="portlet-body">										
										<div class="table-scrollable table-scrollable-borderless">
											<?php echo $RecursosCons->RecursosCons['info_noticias']; ?>
										</div>
									</div>
								</div>
								<!-- END PORTLET -->
							</div>
							<div class="col-md-6">
								<!-- BEGIN PORTLET -->
								<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption caption-md">
											<i class="icon-globe theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold uppercase"><?php echo $RecursosCons->RecursosCons['tarefas_label']; ?></span>
										</div>
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_1_1" data-toggle="tab">
												<?php echo $RecursosCons->RecursosCons['tab_abertas']; ?> </a>
											</li>
											<li>
												<a href="#tab_1_2" data-toggle="tab">
												<?php echo $RecursosCons->RecursosCons['tab_fechadas']; ?> </a>
											</li>
										</ul>
									</div>
									<div class="portlet-body">
										<!--BEGIN TABS-->
										<div class="tab-content">
											<div class="tab-pane active" id="tab_1_1">
												<div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
													<?php echo $RecursosCons->RecursosCons['tarefas_pendentes']; ?> 
												</div>
											</div>
											<div class="tab-pane" id="tab_1_2">
												<div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">								<?php echo $RecursosCons->RecursosCons['tarefas_fechadas']; ?> 
												</div>
											</div>
										</div>
										<!--END TABS-->
									</div>
								</div>
								<!-- END PORTLET -->
							</div>
						</div>						
					</div>
					<!-- END PROFILE CONTENT -->
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
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/profile.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   Profile.init(); // init page demo
});
</script>
</body>
<!-- END BODY -->
</html>