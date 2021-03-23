<?php include_once('pages_head.php');

geraSessions('noticias');
$row_rsNoticias = $GLOBALS['divs_noticias'];

$query_rsImagem = "SELECT noticias FROM imagens_topo";
$rsImagem = DB::getInstance()->query($query_rsImagem);
$row_rsImagem = $rsImagem->fetch(PDO::FETCH_ASSOC);
$totalRows_rsImagem = $rsImagem->rowCount();
DB::close();

$query_allnews = "SELECT * FROM noticias".$extensao;
$newsall = DB::getInstance()->query($query_allnews);
$get_newsall = $newsall->fetchAll(PDO::FETCH_ASSOC);
$totalRows_newsall = $newsall->rowCount();
DB::close();
 
//pre($get_newsall);   
$alltags = array();

function str_replace_first($from, $to, $content)
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}


foreach ($get_newsall as $key => $value) {
     
 
   if($value['tags']!=NULL){
   //  $tags = str_replace(" ","",$value['tags']);
     $tags = str_replace_first('', ' ', $value['tags']); 

      $explode_tag = explode(',', $tags);
   }else{
    $explode_tag = array();
   }
   $alltags[] = $explode_tag;
}
$alltags = $alltags;
//pre($alltags);
function convert_multi_array($arrays)
{
    $imploded = array();
    foreach($arrays as $array) {
        $imploded[] = implode(',', $array);
    }
    return implode(",", $imploded);
}



$alltags_final = convert_multi_array($alltags);
//pre($alltags_final);

$exp_t = explode(',', $alltags_final);
$all_tags_array = array_unique($exp_t);


$menu_sel="noticias";

$get_lang = explode('_', $extensao);
$lang = $get_lang[1];
//pre($_GET);

$query2 = "SELECT * FROM noticias".$extensao;
if( (isset($_GET['year']) && $_GET['year']!='') && ( (!isset($_GET['tag']) && $_GET['tag']=='') || (isset($_GET['tag']) && $_GET['tag']=='') ) ){
    $y = $_GET['year'];
    $query2.= " WHERE data LIKE '%$y%' ";
   // echo $query2; 
    // pre($all_news);
}
else if((isset($_GET['tag']) && $_GET['tag']!='') && ((!isset($_GET['year']) && $_GET['year']=='') || (isset($_GET['year']) && $_GET['year']=='') )){
    $tag = $_GET['tag'];
    $query2.= " WHERE tags LIKE '%$tag%' ";
   // echo $query2; 
    // pre($all_news);
}
else if(isset($_GET['year']) && $_GET['year']!='' && isset($_GET['tag']) && $_GET['tag']!=''){
    $y = $_GET['year'];
    $tag = $_GET['tag'];
    $query2.= " WHERE data LIKE '%$y%' AND tags LIKE '%$tag%' ";
   // echo $query2; 
    // pre($all_news);
}


 //echo $query2;
$getnews = DB::getInstance()->query($query2);
$all_news = $getnews->fetchAll(PDO::FETCH_ASSOC);
$totalRows_news = $getnews->rowCount();
DB::close();
?>
<style type="text/css">
    .the_news_item{
        width: 33.33%;
        float: left;
    }
</style>
<main class="page-load noticias">

    <!-- <div class="div_100 banners banner_contactos has_bg " style="background: #E2E2E2; height: 18rem;">
        <div class="div_absolute" style="padding:0">    
            <div class="row align-middle" style="height: 100%;">
                <div class="column small-12">
                    <div class="banner_content text-center" style="max-width: unset;">
                        <h1 class="titulos show" style="color: #000"><?php echo $Recursos->Resources["noticias"]; ?></h1>
                    </div>
                </div>
            </div> 
        </div> 
    </div> -->

	<nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
            <div class="column">
                <ul class="breadcrumbs">
                	<li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
                    <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="true"><?php echo $Recursos->Resources["home"]; ?></a></li>
                    <li>
                         <span><?php echo $Recursos->Resources["noticias"]; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="notice_tital">
        <div class="main-heading-wrap">
            <h3 class="titulos uppercase bold"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $Recursos->Resources["nutra_news"];?></font></font></h3>
        </div>        
    </div><!-- /.notice_tital -->
    <div class="the_news_filter"> 
        <div class="row">
            <?php
                $news_year = array();
                foreach($row_rsNoticias as $noticias){
                   // pre($noticias);
                    $get_date1 = explode("-", $noticias['info']['data']);
                    $year1 = $get_date1[0];
                    

                    $news_year[] = $year1;
                }
              //  pre($news_year); 
            ?>
            <label>Filtrar Por:</label>
            <form method="GET" id="news_filter_form">
                <div class="notice_filter_box">
                    <select name="year" class="news_filter_select">
                        <option value="">Ano</option> 
                        <?php
                        $news_year = array_unique($news_year);
                        foreach ($news_year as $key => $get_year) {
                            if($get_year==$_GET['year']){
                                $selected = "selected='selected'";
                            }else{
                                $selected = '';
                            }
                            echo "<option ".$selected." value=".$get_year.">".$get_year."</option>";
                        }
                        ?>
                    </select>
                </div><!-- /.notice_filter_box -->
                <div class="notice_filter_box">
                    <select name="tag" class="news_filter_select">
                        <option value="">Tags</option> 
                        <?php
                        
                        foreach ($all_tags_array as $key => $tag) {
                            if($tag==$_GET['tag']){
                                $selected = "selected='selected'";
                            }else{
                                $selected = '';
                            }
                            echo "<option ".$selected." value=".$tag.">".$tag."</option>";
                        }
                        ?>
                    </select>
                </div><!-- /.notice_filter_box -->
            </form>
        </div><!-- /.row -->
    </div>
    <div class="blog_block">
        <div class="row">
            <div class="column">
            <?php if(!empty($all_news)){ ?>
                <div class="noticias_cont text-left">                           
                    <?php foreach($all_news as $noticias){
                        // if($noticias['info']){
                        //     $noticias = $noticias['info'];
                        // }

                    //$img = ROOTPATH_HTTP."imgs/elem/geral.svg";
                    $img = "elem/geral.svg";
                    if($noticias['imagem1'] && file_exists(ROOTPATH.'imgs/noticias/'.$noticias['imagem1'])){
                        //$img = ROOTPATH_HTTP."imgs/noticias/".$noticias['imagem1'];
                        $img = "noticias/".$noticias['imagem1'];
                    }
                    
                    $get_date = explode("-", $noticias['data']);
                    $year = $get_date[0];
                    $month = $get_date[1];
                    $day = $get_date[2]; 

                    $final_date= $day.'-'.$month.'-'.$year;
                  //  pre($noticias);
                    ?>

                    <!--
                    --><article class="noticias_divs the_news_item col-md-4" id="noticia<?php echo $noticias['id']; ?>">
                        <figure>
                            <picture class="img has_bg has_mask icon-mais lazy" data-src="<?php echo $img; ?>">
                                <?php echo getFill('noticias', 2); ?> 
                            </picture>                              
                            <figcaption class="info text-left">
                                <div class="blog_box_date">
                                    <strong><?php echo $final_date; ?></strong><br>
                                    <strong><?php echo ucfirst($noticias['tags']); ?></strong>
                                </div><!-- /.blog_box_date -->
                                <h5 class="list_tit"><?php echo $noticias['nome']; ?></h5>
                                <div class="textos"><?php echo str_text($noticias['resumo'], 300); ?></div>
                                <button class="button"><?php echo $Recursos->Resources["saiba_mais"];?></button>

                                <a href="<?php echo ROOTPATH_HTTP.$lang; ?>/noticias-detalhe.php?id=<?php echo $noticias['id']; ?>" class="linker"></a>


                                <!-- <a href="<?php //echo $noticias['url']; ?>" class="linker" data-ajaxurl="<?php //echo ROOTPATH_HTTP; ?>includes/pages/noticias-detalhe.php" data-ajaxTax="<?php // echo $noticias['id']; ?>" data-remote="true" data-pagetrans="noticias-detail" data-detail="1"></a> -->
                            </figcaption>
                        </figure>
                    </article><!--
                    --><?php } ?>
                </div>
            
            <?php }else{ ?>
                <h6 class="sem_prods"><?php echo $Recursos->Resources["sem_produtos"]; ?></h6>
            <?php } ?>
            </div>
        </div>       
    </div><!-- /.blog_block -->   
</main>


<?php include_once('pages_footer.php'); ?>
