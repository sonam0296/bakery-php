<?php if(!$_COOKIE['allowCookies'] || $_COOKIE['allowCookies'] != 1) { ?>
  <div class="div_cookies">
  	<div class="row align-middle">
    	<div class="small-12 medium-7 large-9 column">
        	<div class="cookies_texto"><?php echo $Recursos->Resources["cookies_txt"]; ?></div>
        </div>
        <div class="small-12 medium-5 large-3 column">
        	<a class="cookies_btn" href="javascript:void(null);" onclick="allowCookies();"><?php echo $Recursos->Resources["cookies_ok"]; ?></a><!--
        	--><a class="cookies_btn" href="<?php echo ROOTPATH_HTTP_LANG.$pagCookies['url']; ?>" target="_blank"><?php echo $Recursos->Resources["cookies_mais"]; ?></a>
        </div>        
    </div>
  </div>
<?php } ?>