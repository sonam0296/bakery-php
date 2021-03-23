<?php 

/*********************************************************************************************************
Generic function to uploadImages

params: 
  $postImage = previous image if defined
  $fileImage = $FILES['img']
  $postRemove = value of $_POST['img_remover']
  $contentTable = name of the table for the content to which the image is being uploaded
  $contentId = id of the content to which the image is being uploaded
  $contentField = name of the field where to put the image name
  $langOption = option for save to one or all languages
  $currentLang = current language extension (eg: "_pt", "_en")
  $imgsFolder = name of the folder in site/images/, where to put the images
  $imagesSizes = array with image sizes define previous by function getFillSize(title, image_field);

*********************************************************************************************************/

# FALTA VER COMO FAZER PARA CARREGAR IMAGENS PARA MAIS QUE UM CAMPO
# ie, $contentField passa a ser array ??

# ver abaixo como passar o $imageSizes para passar array com vários tamanhos


function uploadImages($postImage,$fileImage,$postRemove,$contentTable,$contentId,$contentField,$langOption,$currentLang,$imgsFolder,$imagesSizes,$ordem){
  $allowedExtensions = array("png","svg","gif","jpg","jpeg");
  $rem = 0;
  $opcao = $langOption;
  $imgs_dir = "../../../imgs/".$imgsFolder."/";

  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $row_rsLinguas = $rsLinguas->fetchAll();
  $totalRows_rsLinguas = $rsLinguas->rowCount();

  #echo $postImage." ".$postRemove." ".$contentField." ".$contentTable.$currentLang." ".$contentId;
  #exit;

  if(isset($postRemove) && $postRemove==1){
    if($opcao == 1) {
      $insertSQL = "DELETE FROM ".$contentTable.$currentLang." WHERE id=:id";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':id', $contentId, PDO::PARAM_INT, 5);  
      $rsInsert->execute();

      $r = 0;
      //Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
      if($r == 0)
        @unlink($imgs_dir.$postImage);
    }
    else if($opcao == 2) {
      foreach($row_rsLinguas as $linguas) {   
        $query_rsSelect = "SELECT ".$contentField." FROM ".$contentTable."_".$linguas['sufixo']." WHERE id=:id";
        $rsSelect = DB::getInstance()->prepare($query_rsSelect);
        $rsSelect->bindParam(':id', $contentId, PDO::PARAM_INT);
        $rsSelect->execute();
        $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

        @unlink($imgs_dir.$row_rsSelect[$contentField]);

        $insertSQL = "DELETE FROM ".$contentTable.$currentLang." WHERE id=:id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $contentId, PDO::PARAM_INT); 
        $rsInsert->execute();
      }
    }
    $rem = 1;
  }

  $ins = 0;

  if($fileImage['name']!=''){ 
    //Verificar o formato do ficheiro
    $ext = strtolower(pathinfo($fileImage['name'], PATHINFO_EXTENSION));

    if(!in_array($ext,$allowedExtensions)){
      return json_encode(array("erro" => 1, "erro_msg" => "homepage_bloco_image_invalid_extension"));
    }
    else {

      $ins = 1; 
      require_once("../resize_image.php");

      $imagem="";   
      $contaimg = 1; 

      $nome_img=verifica_nome($fileImage['name']);
      $id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
      $imagem = $id_file."_".$nome_img;
        
      if (is_uploaded_file($fileImage['tmp_name'])) {
        if(!move_uploaded_file($fileImage['tmp_name'],"$imgs_dir$imagem")){
          return json_encode(array("erro" => 1, "erro_msg" => "homepage_bloco_image_not_uploaded"));
        }
      }
  
      //IMAGEM
      if($fileImage['name']!='') {
        if($imagem!="" && file_exists($imgs_dir.$imagem)){
          
          $sizes=getimagesize($imgs_dir.$imagem);
          $imageW=$sizes[0];
          $imageH=$sizes[1];


          #$imagesSize com apenas um valor de tamanhos_imagens
          $maxW=$imagesSizes['0'];
          $maxH=$imagesSizes['1'];

          $sizes=getimagesize($imgs_dir.$imagem);

          $imageW=$sizes[0];
          $imageH=$sizes[1];

          if($imageW>$maxW || $imageH>$maxH){
            $img1=new Resize($imgs_dir, $imagem, $imagem, $maxW, $maxH);
            $img1->resize_image();
          }
        }   

        if($postImage){
          @unlink($imgs_dir.$postImage);
        }

        if($ext != 'svg')
          compressImage($imgs_dir.$imagem, $imgs_dir.$imagem);

        if($contentId!=""){
          //Inserir apenas na língua atual
          if($opcao == 1) {
            $insertSQL = "UPDATE ".$contentTable.$currentLang." SET ".$contentField."=:imagem WHERE id=:id";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':id', $contentId, PDO::PARAM_INT, 5);
            $rsInsert->execute();
          }
          //Inserir para todas as línguas
          else if($opcao == 2) {
            foreach($row_rsLinguas as $linguas) {   
              $insertSQL = "UPDATE ".$contentTable."_".$linguas["sufixo"]." SET ".$contentField."=:imagem WHERE id=:id";
              $rsInsert = DB::getInstance()->prepare($insertSQL);
              $rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':id', $contentId, PDO::PARAM_INT, 5);    
              $rsInsert->execute();
            }
          }
        }
        else{
          if($opcao == 1) {
            $insertSQL = "INSERT INTO ".$contentTable.$currentLang." (".$contentField.",ordem,slider) VALUES (:imagem,:ordem,'1')";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_STR, 5);
            $rsInsert->execute();
          }
          else if($opcao == 2) {
            foreach($row_rsLinguas as $linguas) { 
              $insertSQL = "INSERT INTO ".$contentTable."_".$linguas["sufixo"]." (".$contentField.",ordem,slider) VALUES (:imagem,:ordem,'1')";
              $rsInsert = DB::getInstance()->prepare($insertSQL);
              $rsInsert->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT, 5);
              $rsInsert->execute();
            }
          }
        }
      }
    }
  }
  return json_encode(array("erro" => 0, "erro_msg" => ""));
}

?>