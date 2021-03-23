<?php include_once('../inc_pages.php'); ?>

<?php   
  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $row_rsLinguas = $rsLinguas->fetchAll();
  $totalRows_rsLinguas = $rsLinguas->rowCount();

  DB::close();
?>

<?php 
    
    foreach ($row_rsLinguas as $linguas) {

        $query_rsCatMae = "SELECT id, nome, cat_mae FROM l_categorias_".$linguas['sufixo']." WHERE 1=1";
        $rsCatMae = DB::getInstance()->prepare($query_rsCatMae);
        $rsCatMae->execute();
        $row_rsCatMae = $rsCatMae->fetchAll(PDO::FETCH_ASSOC);
        $totalRows_rsCatMae = $rsCatMae->rowCount();   

        foreach ($row_rsCatMae as $key => $value) {
            if ($value['cat_mae'] != 0 && !empty($value['cat_mae'])) {
                $query_rsProc = "SELECT nome FROM l_categorias_".$linguas['sufixo']." WHERE id=:id";
                $rsProc = DB::getInstance()->prepare($query_rsProc);
                $rsProc->bindParam(':id', $value['cat_mae'], PDO::PARAM_INT);
                $rsProc->execute();
                $row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
                $cate_name = $row_rsProc['nome'];
                $id = $value['id'];
                $insertSQL = "UPDATE l_categorias_".$linguas['sufixo']." SET cate_mae_name='".$cate_name."' WHERE id='".$id."'";
                $rsInsert = DB::getInstance()->prepare($insertSQL);
                // $rsInsert->bindParam(':nome', $row_rsProc['nome'], PDO::PARAM_STR, 5);
                // $rsInsert->bindParam(':id', $value['id'], PDO::PARAM_INT);  
                echo "<pre>";
                 print_r ($rsInsert);
                 echo "</pre>"; 
                $rsInsert->execute();
            } 
        }
        
    }
?>