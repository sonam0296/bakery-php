<?php if(!empty($GLOBALS['divs_banners'])) { ?>
  <div class="div_100 banners fullscreen">
    <div class="div_100 h-100">      
      <div class="div_table_cells">
        <?php $banner_slide_tras = get_banner_slide_tras(); ?>
        <?php 
        // echo "<pre>";
        // print_r ($GLOBALS['divs_banners']);
        // echo "</pre>";
        ?>
        <div id="banners1" data-time="<?php echo $banner_slide_tras; ?>" class="slick-banners full_height">
          <?php foreach($GLOBALS['divs_banners'] as $row) { ?>
            <?php if(($row['d_imagem_full'] && file_exists(ROOTPATH.'imgs/banners/'.$row['d_imagem_full'])) || $row['video'] || $row['id_video'] > 0) { 
              // echo "<pre>";
              // print_r ($row);
              // echo "</pre>";
              $color = "";
              $color2 = "";
              if($row['cor1']) {
                $color = $row['cor1'];
              }
              if($row['cor2']) {
                $color2 = $row['cor2'];
              }

              $mask = "";
              if ($row['m_d_radio']==1) {
                if($row['mascara1'] == 1) {
                  $mask .= " has_mask"; 
                }
                if($row['mascara2'] == 1) {
                  $mask .= " mobile_has_mask"; 
                }
              }

              $mask_image = "";
              if($row['mascara1'] == 1) {
                $mask_image .= " has_mask"; 
              }
              if($row['mascara2'] == 1) {
                $mask_image .= " mobile_has_mask"; 
              }

              $alignH = "center"; 
              $alignV = "center";
              $alignH2 = "center"; 
              $alignV2 = "center";

              if($row['align_h1']) {
                $alignH = $row['align_h1']; 
              }
              if($row['align_v1']) {
                $alignV = $row['align_v1'];
              }

              if($row['align_h2']) {
                $alignH2 = $row['align_h2']; 
              }
              if($row['align_v2']) {
                $alignV2 = $row['align_v2'];
              }

              $text_align ="";
              $text_align2 = "";
              if($row['text_alignv']) {
                $text_align .= " align-".$row['text_alignv']; 
              }
              if($row['text_alignh']) {
                $text_align .= " align-".$row['text_alignh'];
                $text_align2 .= " medium-text-".$row['text_alignh'];
              }

              $align = "align_".$alignH."_".$alignV;
              $align2 = "align_".$alignH2."_".$alignV2;

              $img_banners = "";

              if ($row['m_d_radio']==2) {
                if($row['m_imagem_full'] && file_exists(ROOTPATH.'imgs/banners/'.$row['m_imagem_full'])) {
                  $img_banners = ROOTPATH_HTTP."imgs/banners/".$row['m_imagem_full'];
                }
                else if($row['d_imagem_full'] && file_exists(ROOTPATH.'imgs/banners/'.$row['d_imagem_full'])) {
                  $img_banners = ROOTPATH_HTTP."imgs/banners/".$row['d_imagem_full'];
                }
              } else {
                if($row['imagem2'] && file_exists(ROOTPATH.'imgs/banners/'.$row['m_imagem_full'])) {
                  $img_banners = ROOTPATH_HTTP."imgs/banners/".$row['m_imagem_full'];
                }
                else if($row['imagem1'] && file_exists(ROOTPATH.'imgs/banners/'.$row['d_imagem_full'])) {
                  $img_banners = ROOTPATH_HTTP."imgs/banners/".$row['d_imagem_full'];
                }
              }

              

              $thumb = ROOTPATH_HTTP."imgs/elem/video.png";
              if($row['imagem3'] && file_exists(ROOTPATH."imgs/banners/".$row['imagem3'])) {
                $thumb = ROOTPATH_HTTP."imgs/banners/".$row['imagem3'];
              }
              ?>
              <?php if(($row['d_imagem_full'] && file_exists(ROOTPATH.'imgs/banners/'.$row['d_imagem_full']) ) ) { ?>
              <?php 
                $is_vid1 = strrchr($row['d_imagem_full'], '.');
                $bg_color = '';
                 if($row['bg_color']) {
                 $bg_color = "background-color:".$row['bg_color'];
                }
              ?>
              <?php 
                  if (!empty($row['slide_duration']) && $row['slide_duration'] != 0): 
                    $slide_duration = $row['slide_duration'];
                  else:
                    //default duration
                    $slide_duration = 4000;
                  endif 
                ?>
              <?php if ($is_vid1 != '.mp4'){  //IMAGEM DESKTOP E MOBILE ?>
                <div class="banners_slide slide_item has_bg<?php echo $mask; ?> <?php echo ($row['m_d_radio']==1) ? 'full-banner-display' : 'banner-modal-display'; ?>" data-thumb="<?php echo $thumb; ?>" data-time="<?php echo $slide_duration; ?>" bg-srcset="<?php echo $img_banners; ?> 950w <?php echo $align2; ?>, <?php echo ROOTPATH_HTTP; ?>imgs/banners/<?php echo $row['d_imagem_full']; ?> <?php echo $align; ?>" style="position:relative; <?php echo $bg_color; ?>" id="slide<?php echo $row['id']; ?>">
                  <?php if (!empty($row['imagem2'])): ?>
                  <?php endif ?>

             <?php } else if($is_vid1 == ".mp4"){ //O VIDEO ENTRA NESTE ?>
                <div class="banners_video slide_item has_bg <?php echo $mask; ?>" data-time="<?php echo $slide_duration; ?>" style="position:relative;" id="slide<?php echo $row['id']; ?>">
                  <div class="div_100 wrapper_video_player">

                    <?php if ($row['imagem1'] != $row['imagem2']){ //O VIDEO MOBILE DIFERENTE DO DESKTOP
                      $is_vid2 = strrchr($row['imagem2'], '.');
                      if($is_vid2 == ".mp4"){ ?>
                        <div class="video_cont hide-for-medium">
                          <video id="video1" class="video_player" autobuffer="autobuffer" loop muted="muted"> <?php /* controls */ ?>
                          <source src="<?php echo ROOTPATH_HTTP."imgs/banners/".$row['imagem2']; ?>" type="video/mp4">
                          </video>
                        </div> 
                     <?php } else{ //O IMAGEM MOBILE COM VIDEO DESKTOP ?>
                        <div class="image_cont has_bg hide-for-medium" style="background-image: url(<?php echo ROOTPATH_HTTP."imgs/banners/".$row['imagem2'];?>); background-position-x:<?php echo $alignH2; ?>; background-position-y:<?php echo $alignV2; ?>">
                            <img src="<?php echo ROOTPATH_HTTP."imgs/banners/".$row['imagem2'];?>" alt="">
                        </div>
                      <?php }
                     } ?>
                     
                    <div class="video_cont <?php if ($row['imagem1'] != $row['imagem2']){ echo 'show-for-medium'; }?>">
                      <video id="video1" class="video_player" autobuffer="autobuffer" loop muted="muted"> <?php /* controls */ ?>
                      <source src="<?php echo ROOTPATH_HTTP."imgs/banners/".$row['imagem1']; ?>" type="video/mp4">
                      </video>
                    </div> 
                  </div> 
                <?php } ?>


                  <!--IF full nao precisa ter isto -->
                  <?php /*<div class="div_100 show-for-medium">
                    <?php echo getFill('banners'); ?>
                  </div>
                  <div class="div_100 hide-for-medium">
                    <?php echo getFill('banners', 2); ?>
                  </div>*/ ?>
                  <div class="banner_cont text-right">
                    <div class="<?php echo $text_align.$text_align2; ?> h-100 text-center">
                      <div class="column small-12 banner-main-wrapper">
                       <?php if (!empty($row['imagem1'])): ?>
                          <div class="desktop-bananer">
                            <?php if ($row['m_d_radio']==2): ?>
                              
                            
                              <div class="inner-wrap">

                                <?php 
                                  //Getting the Mask Color
                                  if($row['bg_color']=="#E5F8E9") {
                                      $maskColor = 'Green';
                                  } elseif($row['bg_color']=="#FAF5E4") {
                                      $maskColor = 'Yellow';
                                  } else {
                                    $maskColor = 'Pink';
                                  }
                                ?>
                                <div class="bnmask mask<?php echo $maskColor;?>"> 
                                </div>
                                <div class="img-wrap">
                                  <img src="<?php echo ROOTPATH_HTTP."imgs/banners/".$row['imagem1'];?>" alt="">
                                </div>
                                <!-- <div class="mark-bottom">
                                    <?php //echo file_get_contents(ROOTPATH."imgs/elem/left-blank2.svg"); ?>
                                </div> -->
                              </div>
                              <?php endif ?>
                          </div>
                        <?php endif ?>                                   
                        <div class="banner_content mr<?php echo $maskColor;?>">
                          <?php if ($row['m_d_radio']==2): ?>
                            <div class="corner-leaf"></div>
                          <?php endif ?>
                          <?php if($row['titulo']) { ?>
                            <h1 class="titulos show-for-medium"<?php if($color) echo ' style="color:'.$color.'"';?>><?php echo $row['titulo']; ?></h1>
                            <h1 class="titulos hide-for-medium"<?php if($color2) echo ' style="color:'.$color2.'"';?>><?php echo $row['titulo']; ?></h1>
                          <?php } ?>
                          <?php if($row['subtitulo']) { ?>
                            <h2 class="show-for-medium"<?php if($color) echo ' style="color:'.$color.'"';?>><?php echo str_text($row['subtitulo'], 124); ?></h2>
                            <h2 class="hide-for-medium"<?php if($color2) echo ' style="color:'.$color2.'"';?>><?php echo str_text($row['subtitulo'], 124); ?></h2>
                          <?php } ?>
                          <?php if($row['link']) { ?>
                            <?php if($row['target'] != "_video") { ?>
                              <?php if($row['texto_link'] && $row['texto_link'] != "") { ?>
                                <?php echo text_link($row['link'], $row['target'], $row['texto_link'], "button ".$row['link_class']); ?>
                              <?php } ?>
                            <?php } else { ?>
                              <a href="javascript:;" onclick="carregaVideoBanner('<?php echo $row['link']; ?>');"><?php echo $texto_link; ?></a>
                            <?php } ?>
                          <?php } ?>            
                          <?php if($row['campanha'] == 1) { ?>
                            <p class="list_txt text-center medium-text-left"><?php echo campanhaValida($row['datai'], $row['dataf']); ?></p>
                          <?php } ?>                            
                        </div>
                      </div>
                    </div>
                    <?php if($row['link']) { ?>
                     <a href="<?php echo $row['link']; ?>" target="<?php echo $row['target']; ?>" class="linker"></a>
                    <?php } ?>
                  </div> 

                </div>
              <?php } else if($row['video']) {
                $video = $row['video'];
                $class = "";

                if(strstr($video, "youtube") || strstr($video, "youtu.be")) {
                  $class = " youtube full";
                }
                else if(strstr($video, "vimeo")) {
                  $class = " vimeo full";
                }
                else{
                  $class = "iframe";
                }
                
                ?>
                <div data-thumb="<?php echo $thumb; ?>" style="position:relative;">
                  <div class="div_100 show-for-medium">
                    <?php echo getFill('banners'); ?>
                  </div>
                  <div class="div_100 hide-for-medium">
                    <?php echo getFill('banners', 2); ?>
                  </div>
                  <?php if($class == "iframe") { ?>
                    <iframe src="<?php echo $video; ?>" allowfullscreen width="854" height="480" frameborder="0"></iframe>
                  <?php } else { ?>
                    <div class="video_frame absolute<?php echo $class; ?>" data-vid="<?php echo $video; ?>"></div>
                  <?php } ?>  
                </div>                          
              <?php } ?>
            <?php } ?> 
          <?php } ?> 
        </div>
      </div>
    </div>
    <div id="modalFull" ntgmodal ntgmodal-size="large">
      <div ntgmodal-content>
        <button class="close-button" data-close aria-label="Close reveal" type="button" onclick="carregaVideoBanner('');">
          <span aria-hidden="true">&times;</span>
        </button>                            
        <div id="video_container"></div>
      </div>
    </div>
  </div>   
<?php } ?>

<style type="text/css">
  
  .banner-full-display{ background-color: none; background-image: :   }

</style>