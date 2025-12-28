<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   $idDesignation = @$_POST['id'];
   $dateDebut = @$_POST['dateDebut'];
   $dateFin = @$_POST['dateFin'];

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
           dateInventaire LIKE :dateInventaire OR
           quantite LIKE :quantite OR
           quantiteStockCourant LIKE :quantiteStockCourant OR 
           type LIKE :type ) ";
      $searchArray = array( 
           'dateInventaire'=>"%$searchValue%",
           'quantite'=>"%$searchValue%",
           'quantiteStockCourant'=>"%$searchValue%",
           'type'=>"%$searchValue%",
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableInventaire."` WHERE idDesignation=:idDesignation AND (dateInventaire  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableInventaire."` WHERE 1 ".$searchQuery." AND idDesignation=:idDesignation AND (dateInventaire  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableInventaire."` WHERE 1 ".$searchQuery."  AND idDesignation=:idDesignation AND (dateInventaire  BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {

      $stmtStock = $bdd->prepare("SELECT * FROM `".$nomtableStock."` WHERE idStock=:idStock ");
      $stmtStock->bindValue(':idStock', $row['idStock'], PDO::PARAM_INT);
      $stmtStock->execute();
      $stock = $stmtStock->fetch(); 

      if($row['iduser']!=0){
         $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
         $stmtUser->bindValue(':idutilisateur', $row['iduser'], PDO::PARAM_INT);
         $stmtUser->execute();
         $user = $stmtUser->fetch(); 
         $cols_personnel = strtoupper($user["prenom"]);
      }
      else{
         if($row['idUser']!=0){
            $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
            $stmtUser->bindValue(':idutilisateur', $row['idUser'], PDO::PARAM_INT);
            $stmtUser->execute();
            $user = $stmtUser->fetch(); 
            $cols_personnel = strtoupper($user["prenom"]);
         }
         else {
            $cols_personnel = "";
         }
      }

      if($row['quantite'] > $row['quantiteStockCourant'] || $row['quantite'] < $row['quantiteStockCourant']){
         if($row['quantite'] > $row['quantiteStockCourant']){
            $cols_quantite = '<span style="color:green;"> + '.($row['quantite'] - $row['quantiteStockCourant']).'</span>';
          }
          else if ($row['quantite'] < $row['quantiteStockCourant']) {
            $cols_quantite = '<span style="color:red;"> - '.($row['quantiteStockCourant'] - $row['quantite']).'</span>';
          }	
      }
      else{
         if($row['quantite'] > $row['quantiteStockCourant']){
            $cols_quantite = '<span style="color:green;"> + '.($row['quantite'] - $row['quantiteStockCourant']).'</span>';
          }
          else if ($row['quantite'] < $row['quantiteStockCourant']) {
            $cols_quantite = '<span style="color:red;"> - '.($row['quantiteStockCourant'] - $row['quantite']).'</span>';
          }
      }

      // if($row['quantite'] > $row['quantiteStockCourant']){
      //    $cols_quantite = '<span style="color:green;">  + '.($row['quantite'] - $row['quantiteStockCourant']).'</span>';
      // }
      // else if ($row['quantite'] < $row['quantiteStockCourant']) {
      //    $cols_quantite = '<span style="color:red;">  - '.($row['quantiteStockCourant'] - $row['quantite']).'</span>';
      // }	

      if($row['type']==0){
         $cols_type = 'NORMAL';
       }
       else if($row['type']==1){
         $cols_type = 'RETIRER';
       }
       else if($row['type']==2){
         $cols_type = 'MODIFICATION';
       }
       else if($row['type']==3){
         $cols_type = 'RETOURNER';
       }
       else if($row['type']==5){
         $cols_type = 'FORCER';
       }
       else if($row['type']==2024){
         $cols_type = 'ANNUEL';
       }
       else{
         $cols_type = 'PAS SPECIFIE';
       }
      

      if($row['dateInventaire']==$dateString){

         $data[] = array(
            "idInventaire"=> $row['idInventaire'],
            "dateInventaire"=>'<span style="color:#901E06;">'.$row['dateInventaire'].'</span>',
            "heureInventaire"=>'<span style="color:#901E06;">'.$row['heureInventaire'].'</span>',
            "quantite"=>'<span style="color:#901E06;">'.$cols_quantite.'</span>',
            "quantiteStockCourant"=>'<span style="color:#901E06;">'.$row['quantiteStockCourant'].'</span>',
            "dateStockage"=>'<span style="color:#901E06;">'.$stock['dateStockage'].'</span>',
            "type"=>'<span style="color:#901E06;">'.$cols_type.'</span>',
            "personnel"=>'<span style="color:#901E06;">'.$cols_personnel.'</span>',
            "idDesignation"=> $row['idDesignation']
         );
      }
      else {

         $data[] = array(
            "idInventaire"=> $row['idInventaire'],
            "dateInventaire"=> $row['dateInventaire'],
            "heureInventaire"=> $row['heureInventaire'],
            "quantite"=> $cols_quantite,
            "quantiteStockCourant"=> $row['quantiteStockCourant'],
            "dateStockage"=> $stock['dateStockage'],
            "type"=> $cols_type,
            "personnel"=> $cols_personnel,
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