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
         heurePagnet LIKE :heurePagnet OR 
         prixtotal LIKE :prixtotal OR
         designation LIKE :designation  ) ";
      $searchArray = array( 
         'heurePagnet'=>"%$searchValue%",
         'prixtotal'=>"%$searchValue%",
         'designation'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtableMutuellePagnet."` p ON p.idMutuellePagnet = l.idMutuellePagnet
   WHERE p.verrouiller=1 AND p.idClient=0  AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))   ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtableMutuellePagnet."` p ON p.idMutuellePagnet = l.idMutuellePagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND p.idClient=0 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))   ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l  
   INNER JOIN `".$nomtableMutuellePagnet."` p ON p.idMutuellePagnet = l.idMutuellePagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND p.idClient=0 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))  ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
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

      $stmtCompte = $bdd->prepare("SELECT * FROM `".$nomtableCompte."` WHERE idCompte=:idCompte ");
      $stmtCompte->bindValue(':idCompte', $row['idCompte'], PDO::PARAM_INT);
      $stmtCompte->execute();
      $compte = $stmtCompte->fetch(); 
      $cols_compte = strtoupper($compte["nomCompte"]);

      $stmtMutuelle = $bdd->prepare("SELECT * FROM `".$nomtableMutuelle."` WHERE idMutuelle=:idMutuelle ");
      $stmtMutuelle->bindValue(':idMutuelle', $row['idMutuelle'], PDO::PARAM_INT);
      $stmtMutuelle->execute();
      $mutuelle = $stmtMutuelle->fetch(); 
      $cols_mutuelle = strtoupper($mutuelle["nomMutuelle"]);

      $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
      $stmtUser->bindValue(':idutilisateur', $row['iduser'], PDO::PARAM_INT);
      $stmtUser->execute();
      $user = $stmtUser->fetch(); 
      $cols_personnel = strtoupper($user["prenom"]);

      $data[] = array(
         "heurePagnet"=> $row['heurePagnet'] ,
         "designation"=> $row['designation'] ,
         "quantite"=> $row['quantite'] ,
         "forme"=> $row['forme'] ,
         "prixPublic"=> $row['prixPublic'].' (-'.$row['taux'].'%)' ,
         "prixtotal"=>  $row['prixtotal'] - (($row['prixtotal'] * $row['taux']) / 100) ,
         "idMutuellePagnet"=> '#'.$row['idMutuellePagnet'] ,
         "mutuelle"=> $cols_mutuelle.' '.$row['taux'].'%',
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