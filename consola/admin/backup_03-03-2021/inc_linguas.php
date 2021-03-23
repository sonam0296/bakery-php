<?php
$query_rsLg = "SELECT * FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC";
$rsLg = DB::getInstance()->query($query_rsLg);
$totalRows_rsLg = $rsLg->rowCount();
DB::close();

$linguas_paramt_url=$_SERVER['QUERY_STRING'];

if($totalRows_rsLg>0){

$query_rsLg2 = "SELECT * FROM linguas WHERE visivel='1' ORDER BY ordem ASC, id ASC";
$rsLg2 = DB::getInstance()->query($query_rsLg2);
$totalRows_rsLg2 = $rsLg2->rowCount();
DB::close();


while($row_rsLg2 = $rsLg2->fetch()) {
	
	$linguas_paramt_url=str_replace("&lg=".$row_rsLg['id'],"",$linguas_paramt_url);
	
	$linguas_paramt_url=str_replace("lg=".$row_rsLg['id'],"",$linguas_paramt_url);
	
}


if($linguas_paramt_url!=''){
	$linguas_url=$_SERVER['PHP_SELF'].'?'.$linguas_paramt_url.'&';
}else{
	$linguas_url=$_SERVER['PHP_SELF'].'?'.$linguas_paramt_url;	
}

?>
<div style="width:100%;text-align:right;padding-bottom:15px">
	<div class="btn-group">
		<?php while($row_rsLg = $rsLg->fetch()) { ?>
		<button type="button" class="btn linguas btn-default font_uppercase <?php if($lg_extensao==$row_rsLg['sufixo']) echo "active"; ?>" onClick="window.location='<?php echo $linguas_url; ?>lg=<?php echo $row_rsLg['id']; ?>'"><?php echo $row_rsLg['sufixo']; ?></button>
		<?php } ?>
	</div>
</div>
<?php } ?>