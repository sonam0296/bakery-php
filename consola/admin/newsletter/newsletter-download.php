<?php include_once('../inc_pages.php'); ?>
<?php

function removeTree($rootDir){
	
    if (!is_dir($rootDir))
    {
        return false;
    }

    if (!preg_match("/\\/$/", $rootDir))
    {
        $rootDir .= '/';
    }


    $stack = array($rootDir);

    while (count($stack) > 0)
    {
        $hasDir = false;
        $dir    = end($stack);
        $dh     = opendir($dir);

        while (($file = readdir($dh)) !== false)
        {
            if ($file == '.'  ||  $file == '..')
            {
                continue;
            }

            if (is_dir($dir . $file))
            {
                $hasDir = true;
                array_push($stack, $dir . $file . '/');
            }

            else if (is_file($dir . $file))
            {
                unlink($dir . $file);
            }
        }

        closedir($dh);

        if ($hasDir == false)
        {
            array_pop($stack);
            rmdir($dir);
        }
    }

    return true;
}



$id=$_GET['id'];
$lingua=$_GET['lg'];

$pasta_imagens='newsletter/news_files/imagens/';
$ficheiro_index='newsletter/news_files/index.html';

//APAGA TUDO
@unlink($ficheiro_index);
@removeTree($pasta_imagens);
@unlink('newsletter/newsletter.zip');


//CRIA FICHEIRO E PASTA DAS IMAGENS
$fh = fopen($ficheiro_index,"w");
@chmod($ficheiro_index, 0777);
@mkdir($pasta_imagens, 0777);

//GERAR O CONTEÙDO
$news=file_get_contents(HTTP_DIR.'/consola/admin/newsletter/newsletter-edit.php?id='.$id.'&gera_imagens=1');

//ESCREVE O CONTEÚDO
fwrite($fh, $news);

fclose($fh);
	
//GERAR ZIP
$directory = 'newsletter/news_files'; //diretorio para compactar
$zipfile = 'newsletter/newsletter.zip'; // nome do zip gerado

$filenames = array();
function browse($dir) {
global $filenames;
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && is_file($dir.'/'.$file)) {
                $filenames[] = $dir.'/'.$file;
            }
            else if ($file != "." && $file != ".." && is_dir($dir.'/'.$file)) {
                browse($dir.'/'.$file);
            }
        }
        closedir($handle);
    }
    return $filenames;
}

browse($directory);
// cria zip, adiciona arquivos...
$zip = new ZipArchive();
if ($zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE) {
    exit("Não pode abrir: <$zipfile>\n");
}

foreach ($filenames as $filename) {
    //echo "Arquivo adicionado: <b>" . $filename . "<br/></b>";
    $zip->addFile($filename,$filename);
}

//echo "Total de arquivos: <b>" . $zip->numFiles . "</b>\n";
//echo "Status:" . $zip->status . "\n";

$zip->close();

header("Location: ".$zipfile);


?>