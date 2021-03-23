<?php include_once('../inc_pages.php'); ?>

<?php

        $query_rs_Check = "SELECT * FROM l_pecas_en";
        $rsProcCheck = DB::getInstance()->prepare($query_rs_Check);
        $rsProcCheck->execute();
        $row_rsCheck = $rsProcCheck->fetchAll(PDO::FETCH_ASSOC);

      foreach ($row_rsCheck as  $value) {
        # code...

          $insertSQL = "INSERT INTO l_pecas_store (product_id, b_name_pro, phone) VALUES (:max_id, '9' , 'null')";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':max_id', $value["id"], PDO::PARAM_INT); 
         // $rsInsert->bindParam(':b_name', $namemulti, PDO::PARAM_STR, 5); 
         // $rsInsert->bindParam(':phone', $phonee, PDO::PARAM_STR, 5);   
          $rsInsert->execute(); 
      
           DB::close();

        }
?>