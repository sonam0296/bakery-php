<?php include_once('pages_head.php');
$menu_sel = "404";
?>
<main class="page-load page-404">
	<div class="mainDiv has_bg has-overlay" style="background-image: url('<?php echo ROOTPATH_HTTP; ?>imgs/404/bg.jpg');">
    <div class="div_100" style="height: 100%;">
      <div class="div_table_cell text-center">
        <div class="container_404">
          <div class="row collapse text-center align-middle">
            <div class="column info">
              <div class="div_100 logo_404"><img src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/logo.svg" width="100%" /></div>
              <h1 class="elements_animated top">404</h1>
              <h3 class="elements_animated left"><?php echo $Recursos->Resources["pag_404"]; ?></h3>
              <a class="button invert" href="<?php if($is_blog == 0) echo ROOTPATH_HTTP; else echo ROOTPATH_HTTP_BLOG; ?>"><?php echo $Recursos->Resources["voltar_site"]; ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>