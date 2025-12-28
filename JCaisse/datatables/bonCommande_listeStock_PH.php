<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   $idBl = @$_POST['id'];

   // Reading value
   $draw = @$_POST['draw'];
   $row = @$_POST['start'];
   $rowperpage = @$_POST['length']; // Rows display per page
   $columnIndex = @$_POST['order'][0]['column']; // Column index
   $columnName = @$_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = @$_POST['order'][0]['dir']; // asc or desc
   $searchValue = @$_POST['search']['value']; // Search value

   //var_dump($draw.''.$row);

   $searchArray = array();

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           designation LIKE :designation ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableStock."` WHERE idBl=:idBl  ");
   $stmt->bindValue(':idBl', (int)$idBl, PDO::PARAM_INT);
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableStock."` WHERE 1 ".$searchQuery." AND idBl=:idBl  ");
   $stmt->bindValue(':idBl', (int)$idBl, PDO::PARAM_INT);
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT *, quantiteCommande as quantite, totalArticleStock * prixSession as montantachat FROM `".$nomtableStock."` 
                WHERE 1 ".$searchQuery." AND idBl=:idBl ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->bindValue(':idBl', (int)$idBl, PDO::PARAM_INT);
   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {

      $stmtProduit = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation=:idDesignation ");
      $stmtProduit->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtProduit->execute();
      $produit = $stmtProduit->fetch(); 

      $cols_operations = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Stock('.$row["idStock"].')"  data-toggle="modal" data-target="#imgsup'.$row["idStock"].'" /></a>&nbsp';

      if($row['dateStockage']==$dateString){
        
         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
            "quantite"=>'<span style="color:#901E06;">'.$row['quantite'].'</span>',
            "forme"=>'<span style="color:#901E06;">'.$row['forme'],
            "prixSession"=> '<span style="color:#901E06;">'.$row['prixSession'].'</span>',
            "prixPublic"=> '<span style="color:#901E06;">'.$row['prixPublic'].'</span>',
            "montantachat"=> '<span style="color:#901E06;">'.$row['montantachat'].'</span>',
            "dateExpiration"=> '<span style="color:#901E06;">'.$row['dateExpiration'].'</span>',
            "dateStockage"=> '<span style="color:#901E06;">'.$row['dateStockage'].'</span>',
            "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
            "idDesignation"=> $row['idDesignation']
         );
      }
      else {
        
         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=> $row['designation'],
            "quantite"=> $row['quantite'],
            "forme"=> $row['forme'],
            "prixSession"=>  $row['prixSession'],
            "prixPublic"=>  $row['prixPublic'],
            "montantachat"=>  $row['montantachat'],
            "dateExpiration"=>  $row['dateExpiration'],
            "dateStockage"=>  $row['dateStockage'],
            "operations"=>  $cols_operations,
            "idDesignation"=> $row['idDesignation'] 
         );
      }



   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);