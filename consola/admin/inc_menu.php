	<div class="page-sidebar-wrapper">
	
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<?php
			$query_rsUser = "SELECT * FROM acesso WHERE acesso.username='$username' AND id='$id_user'";

			$rsUser = DB::getInstance()->prepare($query_rsUser);

			$rsUser->execute();

			$row_rsUser = $rsUser->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsUser = $rsUser->rowCount();

			DB::close();

			$store  = $row_rsUser["store_name"];
			 ?>
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<!-- END SIDEBAR TOGGLER BUTTON -->
				</li>			
				<li<?php if($menu_sel=="dashboard"){ ?> class="active"<?php } ?> style="margin-top:20px;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>dashboard5.php">
					<i class="icon-home"></i>
					<span class="title"><?php echo $store; ?></span>
					<?php if($menu_sel=="dashboard"){ ?><span class="selected"></span><?php } ?>
					</a>
				</li>	
               <?php if($store == 'ADMIN') { ?>

        		<li style="display: none;" class="heading">
					<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['institucional']; ?></h3>
				</li>
        		<li style="display: none;" <?php if($menu_sel=="configuracao"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
          			<i class="icon-settings"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_config']; ?></span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="links"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/config-edit.php">
							<i class="icon-settings"></i>
							<?php echo $RecursosCons->RecursosCons['menu_site']; ?></a>
						</li>
						<?php if($row_rsUser['username'] == 'netg') { ?>
							<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="imagens"){ ?> class="active"<?php } ?>>
								<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/imagens.php">
								<i class="icon-picture"></i>
								<?php echo $RecursosCons->RecursosCons['menu_imagens']; ?></a>
							</li>
							<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="sessions"){ ?> class="active"<?php } ?>>
								<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/sessions.php">
								<i class="fa fa-code"></i>
								<?php echo $RecursosCons->RecursosCons['menu_sessions']; ?></a>
							</li>
						<?php } ?>
						<?php if(CARRINHO_PONTOS == 1 || CARRINHO_SALDO == 1 || CARRINHO_CODIGOS == 1 || CARRINHO_EMBRULHO == 1 || CARRINHO_CONVIDAR == 1) { ?>
							<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="ecommerce"){ ?> class="active"<?php } ?>>
								<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/config-edit-ecommerce.php">
								<i class="icon-basket-loaded"></i>
								<?php echo $RecursosCons->RecursosCons['menu_ecommerce']; ?></a>
							</li>
						<?php } ?>
						<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="metatags"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/metatags.php">
							<i class="icon-tag"></i>
							<?php echo $RecursosCons->RecursosCons['menu_gestao_metatags']; ?></a>
						</li>
						<li<?php if($menu_sel=="configuracao" && ($menu_sub_sel=="contactos" || $menu_sub_sel=="imagem")){ ?> class="open active"<?php } ?>>
							<a href="javascript:;">
		          			<i class="icon-envelope-open"></i>
							<span class="title"><?php echo $RecursosCons->RecursosCons['menu_contactos']; ?></span>
							<span class="arrow "></span>
							</a>
							<ul class="sub-menu">
								<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="imagem"){ ?> class="active"<?php } ?>>
									<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/contactos-imagem.php">
									<i class="icon-picture"></i>
									<?php echo $RecursosCons->RecursosCons['imagem']; ?></a>
								</li>
		            	<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="contactos"){ ?> class="active"<?php } ?>>
									<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/contactos.php">
									<i class="icon-envelope-open"></i>
									<?php echo $RecursosCons->RecursosCons['menu_contactos']; ?></a>
								</li>
							</ul>
						</li>


						<li<?php if($menu_sel=="configuracao" && ($menu_sub_sel=="pickup" || $menu_sub_sel=="pickupdate")){ ?> class="open active"<?php } ?>>
							<a href="javascript:;">
		          			<i class="icon-envelope-open"></i>
							<span class="title">PickUp Module</span>
							<span class="arrow "></span>
							</a>
							<ul class="sub-menu">
								<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="pickup"){ ?> class="active"<?php } ?>>
									<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/pickup.php">
									<i class="icon-envelope-open"></i>
									<?php echo $RecursosCons->RecursosCons['pickup']; ?></a>
								</li>
								<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="pickupdate"){ ?> class="active"<?php } ?>>
									<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/pickupdate.php">
									<i class="icon-envelope-open"></i>
									Add PickUp Event</a>
								</li>
								<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="pickuplist"){ ?> class="active"<?php } ?>>
									<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/pickupshow.php">
									<i class="icon-envelope-open"></i>
									PickUp List</a>
								</li>
							</ul>
						</li>



						


						<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="notificacao"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/notificacao.php">
							<i class="icon-envelope"></i>
							<?php echo $RecursosCons->RecursosCons['menu_notificacao']; ?></a>
						</li>
						<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="redes_sociais"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/redes_sociais.php">
							<i class="icon-screen-desktop"></i>
							<?php echo $RecursosCons->RecursosCons['menu_redes_sociais']; ?></a>
						</li>
						<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="gerar_sitemap"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/gerar_sitemap.php">
							<i class="icon-settings"></i>
							<?php echo $RecursosCons->RecursosCons['menu_gerar_xml']; ?></a>
						</li>
						<li<?php if($menu_sel=="configuracao" && $menu_sub_sel=="redirecionamentos_301"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>config/redirecionamentos_301.php">
							<i class="icon-directions"></i>
							<?php echo $RecursosCons->RecursosCons['menu_redirecionamentos']; ?></a>
						</li>
					</ul>
				</li>
				
				<li style="display: none;" <?php if($menu_sel=="paginas"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
      					<i class="icon-docs"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_paginas']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="paginas" && $menu_sub_sel=="paginas_fixas"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>paginas/paginas.php?fixo=1">
							<i class="icon-doc"></i>
							<?php echo $RecursosCons->RecursosCons['menu_pag_fixas']; ?></a>
						</li>
            		<li<?php if($menu_sel=="paginas" && $menu_sub_sel=="paginas_outras"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>paginas/paginas.php?fixo=0">
							<i class="icon-doc"></i>
							<?php echo $RecursosCons->RecursosCons['menu_pag_outras']; ?></a>
						</li>
					</ul>
				</li>
				 

				 
					
				<li style="display: none;" <?php if($menu_sel=="banners"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
          				<i class="icon-picture"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_banners']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="banners" && $menu_sub_sel=="popups"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>slideshow-popups/slideshow-popups.php">
							<i class="icon-grid"></i>
							<?php echo $RecursosCons->RecursosCons['menu_popups']; ?></a>
						</li>
						<li<?php if($menu_sel=="banners" && $menu_sub_sel=="homepage"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>slideshow/slideshow.php">
							<i class="icon-home"></i>
							<?php echo $RecursosCons->RecursosCons['menu_banners']; ?></a>
						</li>
					</ul>
				</li>
				
				<li style="display: none;"<?php if($menu_sel=="homepage" || $menu_sel=="destaques"){ ?> class="open active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>homepage/homepage.php">
          				<i class="icon-home"></i>
						<span class="title"><?php echo "Home Page"; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if($menu_sel=="homepage" && $menu_sub_sel ==''){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>homepage/homepage.php"><i class="icon-docs"></i>
								<span class="title">Textos</span>
							</a>
						</li>
						<li <?php if($menu_sel == "homepage" && $menu_sub_sel =='image'){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>homepage/homepage-image.php"><i class="icon-docs"></i>
								<span class="title"><?php echo $RecursosCons->RecursosCons['imagelabel_homepage']; ?></span>
							</a>
						</li>
						<li <?php if($menu_sel=="destaques" ){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>destaques/destaques.php"><i class="icon-star"></i>
								<span class="title"><?php echo $RecursosCons->RecursosCons['menu_destaques']; ?></span>
							</a>
						</li>
					</ul>
				</li>
				
				<!-- <li <?php if($menu_sel=="destaques"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>destaques/destaques.php"><i class="icon-star"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_destaques']; ?></span>
					</a>
				</li> -->
				<li style="display: none;" <?php if($menu_sel=="noticias"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>noticias/noticias.php">
          				<i class="icon-docs"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_noticias']; ?></span>
					</a>
				</li>
				<li style="display: none;" <?php if($menu_sel=="online_faqs"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
        			<i class="icon-question"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['consultorio_online']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li style="display: none;"<?php if($menu_sel=="online_faqs" && $menu_sub_sel == "online_content"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs/faqs-content.php">
							<i class="icon-picture"></i>
							<?php echo $RecursosCons->RecursosCons['tab_faqcontent']; ?></a>
						</li> 
						<!-- <li<?php if($menu_sel=="faqs" && $menu_sub_sel=="imagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs/imagem.php">
							<i class="icon-picture"></i>
							<?php echo $RecursosCons->RecursosCons['tab_imagem']; ?></a>
						</li>  
						<li<?php if($menu_sel=="faqs" && $menu_sub_sel=="categorias"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs/categorias.php">
							<i class="icon-folder-alt"></i>
							<?php echo $RecursosCons->RecursosCons['menu_categorias']; ?></a>
						</li> -->
						<li<?php if($menu_sel=="online_faqs" && $menu_sub_sel=="online_lista"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs/faqs.php">
							<i class="icon-doc"></i>
							<?php echo $RecursosCons->RecursosCons['menu_listagem']; ?></a>
						</li>
					</ul>
       			 </li>

       			 	<li style="display: none;" <?php if($menu_sel=="faqs"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
        			<i class="icon-question"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['faqs']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="faqs" && $menu_sub_sel == "content"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs2/faqs-content.php">
							<i class="icon-picture"></i>
							<?php echo $RecursosCons->RecursosCons['tab_faqcontent']; ?></a>
						</li> 
						<li<?php if($menu_sel=="faqs" && $menu_sub_sel=="imagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs2/imagem.php">
							<i class="icon-picture"></i>
							<?php echo $RecursosCons->RecursosCons['tab_imagem']; ?></a>
						</li>  
						<li<?php if($menu_sel=="faqs" && $menu_sub_sel=="categorias"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs2/categorias.php">
							<i class="icon-folder-alt"></i>
							<?php echo $RecursosCons->RecursosCons['menu_categorias']; ?></a>
						</li>
						<li<?php if($menu_sel=="faqs" && $menu_sub_sel=="lista"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>faqs2/faqs.php">
							<i class="icon-doc"></i>
							<?php echo $RecursosCons->RecursosCons['menu_listagem']; ?></a>
						</li>
					</ul>
       			 </li>
        
				<li class="heading">
					<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['menu_catalogo_ecommerce']; ?></h3>
				</li>

				<!-- Start || Add Code Vishal Prajapati -->

				<li<?php if($menu_sel=="supplier"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>supplier/supplier.php">
          			<i class="icon-book-open"></i>
					<span class="title">supplier</span>
					</a>
				</li>

				<!-- End  -->

    			<li<?php if($menu_sel=="ec_produtos_produtos"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>produtos/produtos.php">
          			<i class="icon-book-open"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_produtos']; ?></span>
					</a>
				</li>
				
				<li style="display: none;" <?php if($menu_sel=="ec_produtos_marcas"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>marcas/marcas.php">
	        		<i class="icon-folder-alt"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['marcas']; ?></span>
					</a>
				</li>
				
				<li<?php if($menu_sel=="ec_produtos_categorias"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>categorias/categorias.php">
	       			 <i class="icon-folder-alt"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_categorias']; ?></span>
					</a>
				</li>

				<li<?php if($menu_sel=="store"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>store/store.php">
	       			 <i class="icon-folder-alt"></i>
					<span class="title">Store Locater</span>
					</a>
				</li>
				
				<li<?php if($menu_sel=="ec_produtos_filtros"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
          			<i class="icon-magnifier"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_filtros']; ?></span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="ec_produtos_filtros" && $menu_sub_sel=="categorias"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>filtros/filt_categorias.php">
							<i class="icon-folder-alt"></i>
							<?php echo $RecursosCons->RecursosCons['menu_categorias']; ?></a>
						</li>
						<li<?php if($menu_sel=="ec_produtos_filtros" && $menu_sub_sel=="opcoes"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>filtros/filt_opcoes.php">
							<i class="icon-magnifier"></i>
							<?php echo $RecursosCons->RecursosCons['menu_op_filtros']; ?></a>
						</li>
					</ul>
				</li>
				<li<?php if($menu_sel=="ec_produtos_caracteristicas"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
        			<i class="icon-calendar"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_caracteristicas']; ?></span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="ec_produtos_caracteristicas" && $menu_sub_sel=="categorias"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>caracteristicas/caract_categorias.php">
							<i class="icon-folder-alt"></i>
							<?php echo $RecursosCons->RecursosCons['menu_categorias']; ?></a>
						</li>
						<li<?php if($menu_sel=="ec_produtos_caracteristicas" && $menu_sub_sel=="opcoes"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>caracteristicas/caract_opcoes.php">
							<i class="icon-calendar"></i>
							<?php echo $RecursosCons->RecursosCons['menu_op_filtros']; ?></a>
						</li>
					</ul>
				</li>
				<li<?php if($menu_sel=="promocoes"){ ?> class="open active"<?php } ?> style="display: none;">
					<a href="javascript:;">
          			<i class="fa fa-percent"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_promocoes']; ?></span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="promocoes" && $menu_sub_sel=="texto"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>promocoes/texto.php">
							<i class="icon-doc"></i>
							<?php echo $RecursosCons->RecursosCons['menu_promocoes_texto']; ?></a>
						</li>
            			<li<?php if($menu_sel=="promocoes" && $menu_sub_sel=="listagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>promocoes/promocoes.php">
							<i class="icon-list"></i>
							<?php echo $RecursosCons->RecursosCons['menu_listagem']; ?></a>
						</li>
					</ul>
				</li>
				<?php } ?>

				<li class="heading">
					<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['menu_loja_online']; ?></h3>
				</li>
				<li<?php if($menu_sel=="encomendas"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
	        		<i class="icon-basket"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_encomendas']; ?></span>
					</a>
				</li>
				<?php if($store == "admin") { ?>
				<li style="display: none;" <?php if($menu_sel=="portes"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
          				<i class="icon-globe"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_portes']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="portes" && $menu_sub_sel=="met_pagamento"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>portes/p_met_pagamento.php">
							<i class="icon-credit-card"></i>
							<?php echo $RecursosCons->RecursosCons['menu_met_pagamento']; ?></a>
						</li>
						<li<?php if($menu_sel=="portes" && $menu_sub_sel=="met_envio"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>portes/p_met_envio.php">
							<i class="icon-plane"></i>
							<?php echo $RecursosCons->RecursosCons['menu_met_envio']; ?></a>
						</li>
						<li<?php if($menu_sel=="portes" && $menu_sub_sel=="tab_transportadoras"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>portes/p_tab_transportadoras.php">
							<i class="icon-docs"></i>
							<?php echo $RecursosCons->RecursosCons['menu_transportadoras']; ?></a>
						</li>
						<li<?php if($menu_sel=="portes" && $menu_sub_sel=="zona_portes"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>portes/p_zona_portes.php">
							<i class="icon-calculator"></i>
							<?php echo $RecursosCons->RecursosCons['menu_zona_portes']; ?></a>
						</li>
						<li<?php if($menu_sel=="portes" && $menu_sub_sel=="paises"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>portes/p_paises.php">
							<i class="icon-map"></i>
							<?php echo $RecursosCons->RecursosCons['menu_paises']; ?></a>
						</li>
						<li<?php if($menu_sel=="portes" && $menu_sub_sel=="portes_gratis"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>portes/p_portes_gratis.php">
							<i class="icon-ban"></i>
							<?php echo $RecursosCons->RecursosCons['menu_portes_gratis']; ?></a>
						</li>
					</ul>
				</li>
				
				<li style="display: none;" <?php if($menu_sel=="notificacoes"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>textos_notificacoes/notificacoes.php">
          				<i class="fa fa-info"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_texto_notifi']; ?></span>
					</a>
				</li>
				<li<?php if($menu_sel=="carrinho"){ ?> class="open active"<?php } ?> style="display: none;">
					<a href="javascript:;">
	        			<i class="fa fa-shopping-cart"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_carrinho']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="carrinho" && $menu_sub_sel=="listagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>carrinho/carrinho.php">
							<i class="icon-list"></i>
							<?php echo $RecursosCons->RecursosCons['menu_carrinho']; ?></a>
						</li>
						<li<?php if($menu_sel=="carrinho" && $menu_sub_sel=="enviados"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>carrinho/listagem.php">
							<i class="icon-envelope-open"></i>
							Enviados</a>
						</li>
					</ul>
				</li>
				
				<li class="heading">
					<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['menu_outros']; ?></h3>
				</li>
       			 <li<?php if($menu_sel=="clientes"){ ?> class="open active"<?php } ?>>
					<a href="javascript:;">
          				<i class="icon-users"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_clientes']; ?></span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						 <li<?php if($menu_sel=="clientes" && $menu_sub_sel=="roll"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>roll/roll.php">
							<i class="icon-list"></i>
							Add Roll</a>
						</li>
						<li<?php if($menu_sel=="clientes" && $menu_sub_sel=="branch"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>branch/branch.php">
							<i class="icon-list"></i>
							Branch List</a>
						</li>
						<li<?php if($menu_sel=="clientes" && $menu_sub_sel=="textos"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/textos.php">
							<i class="icon-envelope"></i>
							<?php echo $RecursosCons->RecursosCons['menu_email']; ?></a>
						</li>
           				 <li<?php if($menu_sel=="clientes" && $menu_sub_sel=="listagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/clientes.php">
							<i class="icon-list"></i>
							<?php echo $RecursosCons->RecursosCons['menu_listagem']; ?></a>
						</li>
					</ul>
				</li>


				<li<?php if($menu_sel=="outros_clientes_login"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/login.php">
          				<i class="icon-doc"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_clientes_login']; ?></span>
					</a>
				</li>

				<li<?php if($menu_sel=="outros_clientes_blocos"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/blocos.php">
          				<i class="icon-grid"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_clientes_blocos']; ?></span>
					</a>
				</li>

				<li<?php if($menu_sel=="outros_clientes"){ ?> class="open active"<?php } ?> style="display: none;">
					<a href="javascript:;">
          			<i class="icon-users"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_pedidos_remocao']; ?></span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="outros_clientes" && $menu_sub_sel=="remocao_texto"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/clientes_remocao_texto.php">
							<i class="icon-envelope"></i>
							<?php echo $RecursosCons->RecursosCons['menu_email']; ?></a>
						</li>
            			<li<?php if($menu_sel=="outros_clientes" && $menu_sub_sel=="elimina"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/clientes_remocao.php">
							<i class="icon-list"></i>
							<?php echo $RecursosCons->RecursosCons['menu_listagem']; ?></a>
						</li>
					</ul>
				</li>
				
				<li<?php if($menu_sel=="tickets"){ ?> class="open active"<?php } ?> style="display: none;">
					<a href="javascript:;">
          				<i class="icon-earphones-alt"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_tickets']; ?></span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="tickets" && $menu_sub_sel=="tipos"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>tickets/tipos.php">
							<i class="icon-folder-alt"></i>
							<?php echo $RecursosCons->RecursosCons['menu_tipos']; ?></a>
						</li>
            			<li<?php if($menu_sel=="tickets" && $menu_sub_sel=="listagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>tickets/tickets.php">
							<i class="icon-list"></i>
							<?php echo $RecursosCons->RecursosCons['menu_listagem']; ?></a>
						</li>
					</ul>
				</li>
				
				<!-- <li<?php if($menu_sel=="outros_promo"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>cod_prom/cod-promo.php">
          			<i class="fa fa-gift"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['codigos_promocionais']; ?></span>
					</a>
				</li> -->
				<li<?php if($menu_sel=="outros_promo"){ ?> class="open active"<?php } ?> style="display: none;">
					<a href="javascript:;">
          			<i class="icon-present"></i>
					<span class="title">Vales de Desconto</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if($menu_sel=="outros_promo" && $menu_sub_sel=="listagem"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>cod_prom/cod-promo.php">
							<i class="icon-list"></i>
							Listagem</a>
						</li>
						<li<?php if($menu_sel=="outros_promo" && $menu_sub_sel=="tipos"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>cod_prom/tipos.php">
							<i class="icon-folder"></i>
							Tipos</a>
						</li>
						<li<?php if($menu_sel=="outros_promo" && $menu_sub_sel=="enviados"){ ?> class="active"<?php } ?>>
							<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>cod_prom/enviados.php">
							<i class="icon-envelope-open"></i>
							Enviados</a>
						</li>
					</ul>
				</li>
				
				<li class="heading" style="display: none;">
					<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['menu_newsletter']; ?></h3>
				</li>
				
				<li<?php if($menu_sel=="newsletter_lista"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/listas.php">
          				<i class="icon-folder"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_lista_correios']; ?></span>
					</a>
				</li>
        		<li<?php if($menu_sel=="newsletter_mails"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/emails.php">
          			<i class="icon-envelope"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_emails']; ?></span>
					</a>
				</li>
    			<li<?php if($menu_sel=="newsletter_conteudos"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/conteudos.php">
          				<i class="icon-grid"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['conteudos']; ?></span>
					</a>
				</li>
        		<li<?php if($menu_sel=="newsletter_newsletters"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/newsletter.php">
          			<i class="icon-envelope-letter"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_newsletter']; ?></span>
					</a>
				</li>
				<li<?php if($menu_sel=="newsletter_estatisticas"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/estatisticas.php">
          			<i class="icon-bar-chart"></i>
					<span class="title">Estatísticas</span>
					</a>
				</li>
				<?php if($totalRows_rsConfigNews > 0 && $row_rsConfigNews['mostra_tipo'] == 1){ ?>
					<li<?php if($menu_sel=="newsletter_mailgun_bounces"){ ?> class="active"<?php } ?> style="display: none;">
						<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/mailgun-bounces.php">
	          			<i class="icon-action-undo"></i>
						<span class="title">Mailgun Bounces</span>
						</a>
					</li>
				<?php } ?>
				<li<?php if($menu_sel=="newsletter_config"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/config.php">
          				<i class="icon-settings"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_configs']; ?></span>
					</a>
				</li>
        		<li<?php if($menu_sel=="newsletter_logs"){ ?> class="active"<?php } ?> style="display: none;">
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>newsletter/newsletter-logs.php">
          			<i class="icon-docs"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['logs']; ?></span>
					</a>
				</li>
				
				<?php if(in_array($_SERVER['HTTP_HOST'], $array_servidor) && !strstr($_SERVER['REQUEST_URI'],"/proposta/")) { ?> 
					<li class="heading">
						<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['menu_estatisticas']; ?></h3>
					</li>
	        		<li<?php if($menu_sel=="visitas"){ ?> class="active"<?php } ?>>
						<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>estatisticas/visitas.php">
	          			<i class="icon-bar-chart"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_visitas']; ?></span>
						</a>
					</li>
					<li<?php if($menu_sel=="vendas"){ ?> class="active"<?php } ?>>
						<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>estatisticas/vendas.php">
	          			<i class="icon-basket"></i>
						<span class="title"><?php echo $RecursosCons->RecursosCons['menu_vendas']; ?></span>
						</a>
					</li>
				<?php } ?>

			<?php } ?>
				<li class="heading">
					<h3 class="uppercase" style="color: #FFFFFF;"><?php echo $RecursosCons->RecursosCons['menu_outras']; ?></h3>
				</li>
        		<li<?php if($menu_sel=="utilizadores"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>user/utilizadores.php">
          			<i class="icon-users"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_users']; ?></span>
					</a>
				</li>
        		<li<?php if($menu_sel=="users_perfil"){ ?> class="active"<?php } ?>>
					<a href="<?php echo ROOTPATH_HTTP_ADMIN; ?>user/perfil-alterar.php">
          			<i class="icon-user"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_meu_perfil']; ?></span>
					</a>
				</li>
        		<li>
					<a href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>logout2.php">
      				<i class="icon-key"></i>
					<span class="title"><?php echo $RecursosCons->RecursosCons['menu_logout']; ?></span>
					</a>
				</li>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->