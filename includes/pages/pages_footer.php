<?php 
if(!$query_rsMetatags) $query_rsMetatags = "SELECT * FROM metatags".$extensao." WHERE id = :id";
$rsMetatags = DB::getInstance()->prepare($query_rsMetatags);
$rsMetatags->bindParam(':id', $meta_id, PDO::PARAM_INT); 
$rsMetatags->execute();
$row_rsMetatags = $rsMetatags->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMetatags = $rsMetatags->rowCount();
DB::close();

$title = $row_rsMetatags["title"];
$description = $row_rsMetatags["description"];
$keywords = $row_rsMetatags["keywords"];

if($countLang > 1) {
  $share_url = str_replace("_", "", $extensao)."/".$row_rsMetatags['url'];
}
else {
  $share_url = $row_rsMetatags['url'];
}
if(!$title) {
  $title = $Recursos->Resources["pag_title"];
}
?>

<input type="hidden" name="menu_sel" id="menu_sel" value="<?php echo $menu_sel; ?>" />
<input type="hidden" name="meta_url" id="meta_url" value="<?php echo ROOTPATH_HTTP.$share_url; ?>" />
<input type="hidden" name="meta_tit" id="meta_tit" value="<?php echo $title; ?>" />
<input type="hidden" name="meta_desc" id="meta_desc" value="<?php echo $description; ?>" />
<input type="hidden" name="meta_key" id="meta_key" value="<?php echo $keywords; ?>" />