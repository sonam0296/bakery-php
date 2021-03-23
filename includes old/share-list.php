<?php 
if(!$sharePos) $sharePos = "top";
?>
<div class="share_container">
  <?php if($shareSize != "small") { ?>
    <div class="share_list shareInvert">
      <div class="share-button share-opener"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30.08 31.8"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M23.81,19.27a6.19,6.19,0,0,0-4.44,1.86l-7-3.91a6.39,6.39,0,0,0,0-2.66l7-3.9a6.18,6.18,0,0,0,4.45,1.87,6.27,6.27,0,1,0-6.26-6.27,5.92,5.92,0,0,0,.15,1.34l-7,3.89a6.26,6.26,0,1,0,0,8.8l7,3.9a6.07,6.07,0,0,0-.15,1.35,6.27,6.27,0,1,0,6.26-6.27ZM21,25.53a2.78,2.78,0,1,1,2.78,2.78A2.79,2.79,0,0,1,21,25.53ZM26.59,6.27a2.78,2.78,0,1,1-2.78-2.78A2.79,2.79,0,0,1,26.59,6.27ZM9.05,15.89a2.78,2.78,0,1,1-2.78-2.78A2.79,2.79,0,0,1,9.05,15.89Z"/></g></g></svg></div>
      <?php if($shareTitulo){?><span><?php echo $shareTitulo; ?></span><?php }?>
    </div>
  <?php } else { ?>
    <div class="share_list">
      <div class="share-button share-opener"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30.08 31.8"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M23.81,19.27a6.19,6.19,0,0,0-4.44,1.86l-7-3.91a6.39,6.39,0,0,0,0-2.66l7-3.9a6.18,6.18,0,0,0,4.45,1.87,6.27,6.27,0,1,0-6.26-6.27,5.92,5.92,0,0,0,.15,1.34l-7,3.89a6.26,6.26,0,1,0,0,8.8l7,3.9a6.07,6.07,0,0,0-.15,1.35,6.27,6.27,0,1,0,6.26-6.27ZM21,25.53a2.78,2.78,0,1,1,2.78,2.78A2.79,2.79,0,0,1,21,25.53ZM26.59,6.27a2.78,2.78,0,1,1-2.78-2.78A2.79,2.79,0,0,1,26.59,6.27ZM9.05,15.89a2.78,2.78,0,1,1-2.78-2.78A2.79,2.79,0,0,1,9.05,15.89Z"/></g></g></svg></div>
      <?php if($shareTitulo){?><span><?php echo $shareTitulo; ?></span><?php }?>
    </div>
  <?php } ?>
  <div class="share_modal <?php echo $shareClass; ?> <?php echo $sharePos; ?> text-left">
    <div class="share-button share-facebook-circle" data-link="http://www.facebook.com/sharer.php?u=<?php echo $shareUrl; ?>">Facebook</div>
    <div class="share-button share-linkedin-circle" data-link="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $shareUrl; ?>&title=<?php echo $shareNome; ?>&summary=<?php echo $shareDesc; ?>&source=<?php echo NOME_SITE; ?>">LinkedIn</div>
    <div class="share-button share-twitter-circle" data-link="http://twitter.com/share?text=<?php echo NOME_SITE; ?> - <?php echo $shareNome; ?>&url=<?php echo $shareUrl; ?>">Twitter</div>
    <div class="share-button share-pinterest-circle" data-link="//pinterest.com/pin/create/link/?url=<?php echo $shareUrl; ?>&media=<?php echo $shareImg; ?>&description=<?php echo $shareDesc; ?>">Pinterest</div>
    <div class="share-button share-messenger-circle" data-link="http://www.facebook.com/dialog/send?app_id=182447472173773&amp;link=<?php echo $shareUrl; ?>&amp;redirect_uri=<?php echo $shareUrl; ?>">Messenger</div>
    <a class="share-button share-whatsapp-circle" href="whatsapp://send?text=<?php echo $shareDesc; ?>" data-action="share/whatsapp/share">WhatsApp</a>
    <a class="share-button share-mail-circle" href="mailto:?subject=<?php echo NOME_SITE; ?> - <?php echo $shareNome; ?>&body=<?php echo $shareUrl; ?>">Email</a>
  </div>
</div>

<style type="text/css">
  .share_container .share_list .share-button{ display:block; width: 20px;  }
  .share_container .share_list .share-button svg{ fill: #808080; }
  .share_container {
      position: relative;
  }
  .share_container .share_modal.bottom {
      top: 100%;
      width: 150px;
  }
  .share_list.shareInvert {
      display: flex;
  }
</style>