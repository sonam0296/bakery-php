<?php
				$notif_from = $get_act["4"];
				$now = date("Y-m-d H:i:s");

				$diff = round(abs(strtotime($now) - strtotime($notif_from)) / 60, 0);

				$time = $diff." min(s)";

				if($diff > 59) {
					$hour = floor($diff / 60);
					$min = $diff % 60;

					$time = $hour.":".$min." h";
				}

				if($hour != null && $hour > 5) {
					$time = "~ ".$hour." h";
				}

				if($hour != null && $hour > 23) {
					$days = floor($hour / 24);

					$time = "~ ".$days." dia(s)";
				}
			?>

			<!--BEGIN HEADER -->
			<style type="text/css">

				.fa-cart-plus, .fa-ticket, .fa-user-plus {
					font-size: 13px;
					margin-right: 2px;
					margin-left: 0;
				}

				.tooltip {
					width: 150px;
				}

				.dropdown-menu > li > h3 {
					font-size: 15px !important;
				}

				.dropdown-toggle-linguas{
					width: 90px;
					text-align: right;
					color:#c6cfda;
					text-transform: uppercase;
				}
				.dropdown-linguas{
					min-width: 100px !important;
					width: 100px !important;
				}
				.flag-linguas{
					background-repeat: no-repeat !important;
					background-position: 15% 50% !important;
					background-size: 24px 15px !important;
				}
				.li-linguas{
					text-transform: uppercase;
					text-align: right;
				}
			</style>
<div class="page-header -i navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>admin/index.php">
			<img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/logo.png" alt="logo" class="logo-default" style="max-width: 130px;"/>
			</a>
			<div class="menu-toggler sidebar-toggler hide">
			</div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<?php if($consolaLG_count > 1) { ?>
					<li class="dropdown dropdown-user">
						<a href="javascript:;" class="dropdown-toggle dropdown-toggle-linguas flag-linguas" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="background-image: url('<?php echo ROOTPATH_HTTP_CONSOLA;?>imgs/linguas/<?php echo $row_rsUser['lingua'];?>.png');" title="<?php echo $RecursosCons->RecursosCons['selec_idioma_back_txt']; ?>"> <?php echo $row_rsUser['lingua'];?> <i id="angle-down-linguas" class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-default dropdown-linguas">
							<?php foreach ($row_rsconsolaLG as $value) { ?>
								<li class="li-linguas" id="li-<?php echo $value['sufixo']; ?>">
									<a href="javascript:void(null)" onClick="changeLG_login('<?php echo $value['sufixo']; ?>');" class="flag-linguas" style="background-image: url('<?php echo ROOTPATH_HTTP_CONSOLA;?>imgs/linguas/<?php echo $value['sufixo']; ?>.png');"> <?php echo $value['sufixo']; ?></a>
								</li> 
							<?php } ?>
						</ul>
					</li>
				<?php } ?><li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="icon-bell"></i>
						<span id="n_notif" class="badge badge-default">
							<?php echo $get_act["0"]+$get_act["1"]+$get_act["2"]+$get_act["3"]["n_enc"]+$get_act["3"]["n_tick"]+$get_act["3"]["n_cli"]; ?>  
						</span>
						</a>
						<ul class="dropdown-menu">
							<li class="external">
								<h3><span id="n_notif2" class="bold"><?php echo $get_act["0"]+$get_act["1"]+$get_act["2"]+$get_act["3"]["n_enc"]+$get_act["3"]["n_tick"]+$get_act["3"]["n_cli"]; ?></span> notificações novas</h3>
								<i id="clear-notify" style="float: right; cursor: pointer" class="fa fa-times-circle fa-lg" title="<?php echo $RecursosCons->RecursosCons['Limpar_notificacoes']; ?>"></i>
							</li>
							<li>
								<ul class="dropdown-menu-list scroller" style="height: 165px;" data-handle-color="#637283">
									<li>
										<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
										<span class="time"><?php echo $time; ?></span>
										<span class="details">
										<span class="label label-sm label-icon label-success">
										<i class="fa fa-cart-plus"></i>
										</span>
										<strong id="n_enc"><?php echo $get_act["0"]+$get_act["3"]["n_enc"]; ?></strong> <?php echo $RecursosCons->RecursosCons['notif_encomendas_novas']; ?> </span>
										</a>
									</li>
									<li>
										<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>tickets/tickets.php">
										<span class="time"><?php echo $time; ?></span>
										<span class="details">
										<span class="label label-sm label-icon label-success">
										<i class="fa fa-ticket"></i>
										</span>
										<strong id="n_tick"><?php echo $get_act["1"]+$get_act["3"]["n_tick"]; ?></strong> <?php echo $RecursosCons->RecursosCons['notif_tickets_novos']; ?> </span>
										</a>
									</li>
									<li>
										<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/clientes.php">
										<span class="time"><?php echo $time; ?></span>
										<span class="details">
										<span class="label label-sm label-icon label-success">
										<i class="fa fa-user-plus"></i>
										</span>
										<strong id="n_cli"><?php echo $get_act["2"]+$get_act["3"]["n_cli"]; ?></strong> <?php echo $RecursosCons->RecursosCons['notif_registos_novos']; ?> </span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li><li class="dropdown dropdown-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" class="img-circle" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/user/<?php echo $row_rsUser["imagem1"]; ?>" width="29"/>
					<span class="username username-hide-on-mobile">
					<?php echo $nome_mostra; ?> </span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>user/perfil-alterar.php">
							<i class="icon-user"></i> <?php echo $RecursosCons->RecursosCons['alterar_perfil_menu']; ?></a>
						</li>
						<li class="divider">
						</li>                        
						<li>
							<a href="javascript:void(null)" onClick="logout(2, '<?php echo ROOTPATH_HTTP_CONSOLA; ?>')">
							<i class="icon-lock"></i> <?php echo $RecursosCons->RecursosCons['bloquar_sessao_menu']; ?> </a>
						</li>
						<li>
							<a href="javascript:void(null)" onClick="logout(1, '<?php echo ROOTPATH_HTTP_CONSOLA; ?>')">
							<i class="icon-key"></i> <?php echo $RecursosCons->RecursosCons['terminar_sessao_menu']; ?> </a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<!--<li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="javascript:;" class="dropdown-toggle">
					<i class="icon-logout"></i>
					</a>
				</li>-->
				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
	$(document).ready(function() {
		$("#clear-notify").click(function() {
			$.ajax({
		    type: "POST",
		    url: "<?php echo ROOTPATH_HTTP_ADMIN."activity-rpc.php"; ?>",
		    data: {opt: "reset_activity"},
		    success: function (data) {
		    	$("#n_notif").text("0");
		    	$("#n_notif2").text("0");
		    	$("#n_enc").text("0");
		    	$("#n_tick").text("0");
		    	$("#n_cli").text("0");
		    	$(".time").text("0 min(s)");
	      }
			});
		});

    $("#clear-notify").tooltip();
		$("#li-<?php echo $row_rsUser['lingua'];?>").css('display','none');
		if((<?php echo $consolaLG_count; ?> > 1)){
			$(".dropdown-linguas").css("opacity", "1");
			$("#angle-down-linguas").css("opacity", "1");
		}
		else{
			$(".dropdown-linguas").css("opacity", "0");
			$("#angle-down-linguas").css("opacity", "0");
		}
	});

	function changeLG(lng){	
		$.post("<?php echo ROOTPATH_HTTP_ADMIN; ?>rpc.php",{op_lg:'changeLG', lg:lng}, function(data){
			$("#li-"+data).css('display','none');
			window.location.reload(true);
		});
	}
</script>