
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/bootstrap.bundle.js'></script>
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/bootstrap.bundle.min.js'></script>
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/bootstrap.js'></script>
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/bootstrap.min.js'></script>

<script src="<?php echo ROOTPATH_HTTP; ?>js/jquery-3.4.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAi4pzXIaemolBXDPx_xYjp-97HMC209GI"></script>
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/netgocio.main.min.js'></script>
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/magicscroll.js'></script>
<?php if(CAPTCHA_KEY != NULL) { ?>
  <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>" async defer></script>
<?php } ?>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
<script type="text/javascript" src='<?php echo ROOTPATH_HTTP; ?>js/workers.main.js'></script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>



<style type="text/css">
    
    #banners1 .slick-slide div, #banners1 .banners_slide{ width: 100%; height: 100%; display: block; }
    section#categorias_populares{ position: relative; }
    section#categorias_populares:before {
      content: "";
      display: block;
      width: 400px;
      height: 400px;
      background: url(../imgs/svg/leaf-logo-gray.svg);
      position: absolute;
      opacity: 0.1;
      background-size: 371px;
      top: 79px;
      left: -120px;
      background-repeat: no-repeat;
    }

    section#categorias_populares:after {
      content: "";
      display: block;
      width: 400px;
      height: 400px;
      background: url(../imgs/svg/leaf-logo-gray.svg);
      position: absolute;
      opacity: 0.1;
      background-size: 371px;
      top: 110px;
      right: -350px;
      background-repeat: no-repeat;
      z-index: -1;
      bottom: auto;
  }

</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js"></script>
<script>
 WebFont.load({
 	<?php echo FONT_SITE; ?>
  });
</script>

<?php if($_GET['env'] > 0) {
  $query_rsNotificacao = "SELECT sucesso FROM notificacoes".$extensao." WHERE id=:id";
  $rsNotificacao = DB::getInstance()->prepare($query_rsNotificacao);
  $rsNotificacao->bindParam(':id', $_GET['env'], PDO::PARAM_INT);      
  $rsNotificacao->execute();
  $row_rsNotificacao = $rsNotificacao->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsNotificacao = $rsNotificacao->rowCount();
  DB::close();
  
  $texto_env = $Recursos->Resources["formulario_contacto_msg"];
  if($row_rsNotificacao['sucesso']) {
    $texto_env = $row_rsNotificacao['sucesso'];
  }

  $msg_alertify = nl2br($texto_env);
  $msg_alertify = str_replace(array("\r\n", "\n"), "", $msg_alertify);
  ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_alert("<?php echo $msg_alertify; ?>");
    });
  </script>
<?php } ?>