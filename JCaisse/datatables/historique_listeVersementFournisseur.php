<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   $dateJour = @$_POST['dateJour'];

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
           heureVersement LIKE :heureVersement OR 
           montant LIKE :montant ) ";
      $searchArray = array( 
           'heureVersement'=>"%$searchValue%",
           'montant'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableVersement."` v
   WHERE idFournisseur<>0 AND ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) ='".$dateJour."') or (v.dateVersement  ='".$dateJour."'))  ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableVersement."` v
   WHERE  1 ".$searchQuery." AND idFournisseur<>0 AND ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) ='".$dateJour."') or (v.dateVersement  ='".$dateJour."'))  ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableVersement."` v
   WHERE 1 ".$searchQuery." AND idFournisseur<>0 AND ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) ='".$dateJour."') or (v.dateVersement  ='".$dateJour."')) ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {

      if($row['idCompte']!=0){
         $stmtCompte = $bdd->prepare("SELECT * FROM `".$nomtableCompte."` WHERE idCompte=:idCompte ");
         $stmtCompte->bindValue(':idCompte', $row['idCompte'], PDO::PARAM_INT);
         $stmtCompte->execute();
         $compte = $stmtCompte->fetch(); 
         $cols_compte = strtoupper($compte["nomCompte"]);
      }
      else {
         $cols_compte = "CAISSE";
      }

      $stmtFournisseur = $bdd->prepare("SELECT * FROM `".$nomtableFournisseur."` WHERE idFournisseur=:idFournisseur ");
      $stmtFournisseur->bindValue(':idFournisseur', $row['idFournisseur'], PDO::PARAM_INT);
      $stmtFournisseur->execute();
      $fournisseur = $stmtFournisseur->fetch(); 
      $cols_fournisseur= strtoupper($fournisseur["nomFournisseur"]);

      $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
      $stmtUser->bindValue(':idutilisateur', $row['iduser'], PDO::PARAM_INT);
      $stmtUser->execute();
      $user = $stmtUser->fetch(); 
      $cols_personnel = strtoupper($user["prenom"]);

      $data[] = array(
         "heureVersement"=> $row['heureVersement'] ,
         "paiement"=> $row['paiement'] ,
         "montant"=>  $row['montant'] ,
         "idVersement"=> '#'.$row['idVersement'] ,
         "fournisseur"=>  $cols_fournisseur,
         "compte"=> $cols_compte,
         "personnel"=> $cols_personnel 
      );

   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);