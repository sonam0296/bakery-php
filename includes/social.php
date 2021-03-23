<?php require_once('../Connections/connADMIN.php'); ?>
<?php

$has_facebook = 0;
$has_insta = 0;

// $query_rsHash = "SELECT * FROM hashtags WHERE visivel = '1' ORDER BY rand() LIMIT 2";
// $rsHash = DB::getInstance()->prepare($query_rsHash);
// $rsHash->execute();
// $row_rsHash = $rsHash->fetchAll(PDO::FETCH_ASSOC);
// $totalRows_rsHash = $rsHash->rowCount();
// DB::close();

// $query_rsCores = "SELECT * FROM hashtags_cores WHERE visivel = '1' ORDER BY rand() LIMIT 2";
// $rsCores = DB::getInstance()->prepare($query_rsCores);
// $rsCores->execute();
// $row_rsCores = $rsCores->fetchAll(PDO::FETCH_ASSOC);
// $totalRows_rsCores = $rsCores->rowCount();
// DB::close();

// $array_hash = array();
// if($totalRows_rsHash>0){
// 	$counter=0; 
	
// 	foreach($row_rsHash as $hash){
// 		array_push($array_hash, $hash['nome']."::;".$row_rsCores[$counter]['cor']);
// 		$counter++;
// 	}
// }
// $hash_pos = [2,7];

$query_rsRedes = "SELECT * FROM redes_sociais WHERE visivel = '1' ORDER BY ordem ASC";
$rsRedes = DB::getInstance()->query($query_rsRedes);
$row_rsRedes = $rsRedes->fetchAll();
$totalRows_rsRedes = $rsRedes->rowCount();
DB::close();	

if($totalRows_rsRedes > 0) { 
	foreach($row_rsRedes as $row_rsSocial){ 
		if($row_rsSocial['id'] == 1 && $row_rsSocial['link'] != "") {
		 	$link_facebook = $row_rsSocial['link'];
		}
		if($row_rsSocial['id'] == 2 && $row_rsSocial['link'] != "") {
		 	$link_insta = $row_rsSocial['link'];
		}
	}
}

$max = 3;

/* FACEBOOK */
if($has_facebook == 1) {
	require ROOTPATH.'fb-sdk/src/facebook.php';
	$facebook = new Facebook(array(
		'appId'  => '519217218116316',
		'secret' => '56f30c57c608519a627a175791b70f3f',
		'cookie' => true
	));

	$pageID = '298759873468998';
	$page_token = $facebook->getAccessToken();

	$imagem_capa = json_decode(file_get_contents("https://graph.facebook.com/".$pageID."?fields=cover&access_token=".$page_token)); 
	$imagem_capa = $imagem_capa->cover->source;

	$pageFeed = $facebook->api($pageID . '/feed?limit=3');
	$face_array = array();

	for($i = 0; $i <= 3; $i++) {
		$feed_face = $pageFeed['data'][$i];
		$message = $feed_face['message'];

		$imagem = "";
		$postID = $feed_face['id'];

		$object = json_decode(file_get_contents('https://graph.facebook.com/'.$postID.'?fields=object_id,link&access_token='.$page_token));

		if($object->object_id) {
			$imagem = "https://graph.facebook.com/".$object->object_id."/picture";
		}
		else {
			$imagem = $imagem_capa; 
		}
		
		$link = $link_facebook;

		if($feed_face['link']) {
			$link = $feed_face['link'];
		}
		if($object->link) {
			$link = $object->link;
		}
		
	  if($imagem) {
      $dataAno = substr($feed_face['created_time'], 0, 4);
      $dataMes = substr($feed_face['created_time'], 5, 2);
      $dataMesInt = (int) $dataMes;
      $dataDia = substr($feed_face['created_time'], 8, 2);
      $dataPub = $dataAno."-".$dataMesInt."-".$dataDia;
			$dataPub = data_hora($dataPub, $extensao, 'noticias');
		}

		$str = $imagem."###".$link."###".$message;
		array_push($face_array, $str);
	}

	$face_pos = [1,4,6];
}

/* INSTAGRAM */
if($has_insta == 1){
	$userid = "2973050735"; //netgocio
	$accessToken = "2973050735.1677ed0.2a6b029433dd4793aa93ee3a7daaecd5"; //netgocio
	$json = file_get_contents("https://api.instagram.com/v1/users/{$userid}/media/recent/?access_token={$accessToken}&count=4");
	$data = json_decode($json);

	$insta_array = array();
	foreach( $data->data as $post ) {
		$string = $post->images->standard_resolution->url."###".$post->link."###".$post->caption->text;
		array_push($insta_array, $string);
	}

	$insta_pos = [0,1,2,3,4,5];
}

if(empty($face_array) && empty($insta_array) && empty($array_hash)) {
	return false;
}
?>
<div class="slick-of3">
	<?php 
	$counter_face = 0;
	$counter_insta = 0;
  $counter_hash = 0;
	
	for($i = 1; $i <= $max; $i++) {
		$is_face = 0;
		$is_insta = 0;
		$is_hash = 0;
		$img_link = "javascript:;";
		$img_url = "";
		$hashtag = "";

		$imagem = "";
		$img_url = "";
		$img_link = "";
		$img_desc = "";
		$icon = "";
		
		if(in_array($i, $face_pos)) {
			$imagem = explode("###", $face_array[$counter_face]);
			$img_url = $imagem[0];
			$img_link = $imagem[1];
			$img_desc = $imagem[2];
			$icon = " share-facebook";

			$is_face = 1;
			$counter_face++;
		}
		else if(in_array($i, $insta_pos)) {
			$imagem = explode("###", $insta_array[$counter_insta]);
			$img_url = $imagem[0];
			$img_link = $imagem[1];
			$img_desc = $imagem[2];
			$icon = " share-instagram";

			$is_insta = 1;
			$counter_insta++;
		}
		else if(in_array($i, $hash_pos)) {
			$cor_nome = explode("::;", $array_hash[$counter_hash]);
			$hashtag = $cor_nome[0];
			$cor = $cor_nome[1];
			$icon = " visible";

			$is_hash = 1;
			$counter_hash++;
		}
				
		if($img_url || $hashtag) {
			$style = "";
			
			if($img_url) {
				$style = 'background-image:url(\''.$img_url.'\');';
			}
			else {
				$style = 'background:'.$cor.'; pointer-events:none;';
			}
			?>
			<figure class="face_divs">
				<picture class="img has_bg <?php echo $icon; ?>" style="<?php echo $style; ?>">
          <img src="<?php echo ROOTPATH_HTTP; ?>imgs/elem/face_fill.gif" width="100%" /> 
          <i class="<?php echo $icon; ?>"></i>
	           
          <figcaption class="info">
            <div class="div_100" style="height:100%">
              <div class="div_table_cell">
              	<?php if($img_desc) { 
              		$img_desc = preg_replace('/[^\p{L}\p{N}\s]/u', '', $img_desc); //remove emojis
									$img_desc = strip_tags($img_desc);

									if(strlen($img_desc) > 135) {
										$img_desc = substr($img_desc, 0, 135);
										$img_desc .= "...";
									}
									
							    $img_desc = str_replace(array("\r\n","\n","\r"),"<br>",$img_desc);
							    $img_desc = str_replace("<br><br>","<br>",$img_desc);
                	?>
                	<div class="textos"><?php echo utf8_decode($img_desc); ?></div>
                <?php } else if($hashtag) { ?>
                	<h3 class="titulos"><?php echo $hashtag; ?></h3>
								<?php } ?>
								<a class="linker" href="<?php echo $img_link; ?>" target="_blank"></a>
              </div>
            </div>
          </figcaption>
        </picture>
      </figure>
    <?php }
	} ?>
</div>
<h3 class="titulos text-right"><?php echo $Recursos->Resources['siga']; ?> <a class="uppercase" href="<?php echo $link_facebook; ?>" target="_blank">Facebook</a> / <a class="uppercase" href="<?php echo $link_insta; ?>" target="_blank">Instagram</a></h3>