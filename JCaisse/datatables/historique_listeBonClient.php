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
         idPagnet LIKE :idPagnet OR 
         heurePagnet LIKE :heurePagnet OR 
         prixtotal LIKE :prixtotal OR
         designation LIKE :designation  ) ";
      $searchArray = array( 
         'idPagnet'=>"%$searchValue%",
         'heurePagnet'=>"%$searchValue%",
         'prixtotal'=>"%$searchValue%",
         'designation'=>"%$searchValue%"
      );
   }

   if (($_SESSION['mutuelle']==1) AND ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
      // Total number of records without filtering
      $stmt = $bdd->prepare("SELECT COUNT(codePanier) AS allcount FROM
         (
            SELECT CONCAT(l.numligne,'+1') as codePanier FROM `".$nomtableLigne."` l 
            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
            WHERE p.verrouiller=1 AND p.idClient<>0 AND (p.type=0 || p.type=30) AND (l.classe=0 || l.classe=1) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )
         UNION ALL
            SELECT CONCAT(i.numligne,'+2') as codeMutuelle FROM `".$nomtableLigne."` i
            INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = i.idMutuellePagnet
            WHERE m.verrouiller=1 AND m.idClient<>0 AND (m.type=0 || m.type=30)  AND ((CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) ='".$dateJour."') or (m.datepagej ='".$dateJour."') )
         ) 
         AS a ");
      $stmt->execute();
      $records = $stmt->fetch();
      $totalRecords = $records['allcount'];

      // Total number of records with filtering
      $stmt = $bdd->prepare("SELECT COUNT(codePanier) AS allcount FROM
      (
         SELECT CONCAT(l.numligne,'+1') as codePanier, p.idPagnet, p.idClient, p.iduser, p.heurePagnet, p.taux, l.designation, l.quantite, l.forme, l.prixPublic, l.prixtotal FROM `".$nomtableLigne."` l 
         INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
         WHERE p.verrouiller=1 AND p.idClient<>0 AND (p.type=0 || p.type=30) AND (l.classe=0 || l.classe=1) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )
      UNION ALL
         SELECT CONCAT(i.numligne,'+2') as codeMutuelle, m.idMutuellePagnet, m.idClient, m.iduser, m.heurePagnet, m.taux, i.designation, i.quantite, i.forme, i.prixPublic, i.prixtotal FROM `".$nomtableLigne."` i
         INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = i.idMutuellePagnet
         WHERE m.verrouiller=1 AND m.idClient<>0 AND (m.type=0 || m.type=30)  AND ((CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) ='".$dateJour."') or (m.datepagej ='".$dateJour."') )
      ) 
      AS a WHERE 1 ".$searchQuery." ");
      $stmt->execute($searchArray);
      $records = $stmt->fetch();
      $totalRecordwithFilter = $records['allcount'];

      $stmt = $bdd->prepare("SELECT *,codePanier FROM
      (
         SELECT CONCAT(l.numligne,'+1') as codePanier, p.idPagnet, p.idClient, p.iduser, p.heurePagnet, p.taux, l.designation, l.quantite, l.forme, l.prixPublic, l.prixtotal FROM `".$nomtableLigne."` l 
         INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
         WHERE p.verrouiller=1 AND p.idClient<>0 AND (p.type=0 || p.type=30) AND (l.classe=0 || l.classe=1) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )
      UNION ALL
         SELECT CONCAT(i.numligne,'+2') as codeMutuelle, m.idMutuellePagnet, m.idClient, m.iduser, m.heurePagnet, m.taux, i.designation, i.quantite, i.forme, i.prixPublic, i.prixtotal FROM `".$nomtableLigne."` i
         INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = i.idMutuellePagnet
         WHERE m.verrouiller=1 AND m.idClient<>0 AND (m.type=0 || m.type=30) AND ((CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) ='".$dateJour."') or (m.datepagej ='".$dateJour."') )
      ) 
      AS a WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");
      foreach ($searchArray as $key=>$search) {
         $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
      }
      $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
      $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
      $stmt->execute();
      $empRecords = $stmt->fetchAll();
   }
   else {

      // Total number of records without filtering
      $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE p.verrouiller=1 AND p.idClient<>0 AND (p.type=0 || p.type=30) AND (l.classe=0 || l.classe=1) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  ");
      $stmt->execute();
      $records = $stmt->fetch();
      $totalRecords = $records['allcount'];

      // Total number of records with filtering
      $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND p.idClient<>0 AND (p.type=0 || p.type=30) AND (l.classe=0 || l.classe=1) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))   ");
      $stmt->execute($searchArray);
      $records = $stmt->fetch();
      $totalRecordwithFilter = $records['allcount'];

      // Fetch records
      $stmt = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l  
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND p.idClient<>0 AND (p.type=0 || p.type=30) AND (l.classe=0 || l.classe=1) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
      // Bind values
      foreach ($searchArray as $key=>$search) {
         $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
      }
      $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
      $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
      $stmt->execute();
      $empRecords = $stmt->fetchAll();

   }

   $data = array();

   foreach ($empRecords as $row) {

      $stmtClient = $bdd->prepare("SELECT * FROM `".$nomtableClient."` WHERE idClient=:idClient ");
      $stmtClient->bindValue(':idClient', $row['idClient'], PDO::PARAM_INT);
      $stmtClient->execute();
      $client = $stmtClient->fetch(); 
      $cols_client = strtoupper($client["nom"].' '.$client["prenom"]);

      $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
      $stmtUser->bindValue(':idutilisateur', $row['iduser'], PDO::PARAM_INT);
      $stmtUser->execute();
      $user = $stmtUser->fetch(); 
      $cols_personnel = strtoupper($user["prenom"]);

      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

         if ($_SESSION['mutuelle']==1){
            $type = explode("+", $row['codePanier']);

            if($type[1]==1){
               $data[] = array(
                  "heurePagnet"=> $row['heurePagnet'] ,
                  "designation"=> $row['designation'] ,
                  "quantite"=> $row['quantite'] ,
                  "unitevente"=> $row['forme'] ,
                  "prixunitevente"=> $row['prixPublic'] ,
                  "prixtotal"=>  $row['prixtotal'] ,
                  "idPagnet"=> '#'.$row['idPagnet'] ,
                  "client"=>  $cols_client,
                  "personnel"=> $cols_personnel 
               );
            }
            else if($type[1]==2){
               $data[] = array(
                  "heurePagnet"=> $row['heurePagnet'] ,
                  "designation"=> $row['designation'] ,
                  "quantite"=> $row['quantite'] ,
                  "unitevente"=> $row['forme'] ,
                  "prixunitevente"=> $row['prixPublic'].' (-'.$row['taux'].'%)' ,
                  "prixtotal"=>  $row['prixtotal'] - (($row['prixtotal'] * $row['taux']) / 100) ,
                  "idPagnet"=> '#'.$row['idPagnet'] ,
                  "client"=>  $cols_client,
                  "personnel"=> $cols_personnel 
               );
            }

         }
         else {
            $data[] = array(
               "heurePagnet"=> $row['heurePagnet'] ,
               "designation"=> $row['designation'] ,
               "quantite"=> $row['quantite'] ,
               "unitevente"=> $row['forme'] ,
               "prixunitevente"=> $row['prixPublic'] ,
               "prixtotal"=>  $row['prixtotal'] ,
               "idPagnet"=> '#'.$row['idPagnet'] ,
               "client"=>  $cols_client,
               "personnel"=> $cols_personnel 
            );
         } 
      }
      else {
         $data[] = array(
            "heurePagnet"=> $row['heurePagnet'] ,
            "designation"=> $row['designation'] ,
            "quantite"=> $row['quantite'] ,
            "unitevente"=> $row['unitevente'] ,
            "prixunitevente"=> $row['prixunitevente'] ,
            "prixtotal"=>  $row['prixtotal'] ,
            "idPagnet"=> '#'.$row['idPagnet'] ,
            "client"=>  $cols_client,
            "personnel"=> $cols_personnel 
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