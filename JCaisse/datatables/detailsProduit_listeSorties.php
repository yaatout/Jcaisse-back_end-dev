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
           dateJour LIKE :dateJour OR
           heurePagnet LIKE :heurePagnet OR 
           quantite LIKE :quantite OR 
           unitevente LIKE :unitevente OR
           prixunitevente LIKE :prixunitevente OR 
           prixtotal LIKE :prixtotal OR
           facture LIKE :facture ) ";
      $searchArray = array( 
           'dateJour'=>"%$searchValue%",
           'heurePagnet'=>"%$searchValue%",
           'quantite'=>"%$searchValue%",
           'unitevente'=>"%$searchValue%",
           'prixunitevente'=>"%$searchValue%",
           'prixtotal'=>"%$searchValue%",
           'facture'=>"%$searchValue%",
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT *,CONCAT(CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)),'',p.heurePagnet) as idLigne,
      CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) as dateJour, p.idPagnet as facture
     FROM `".$nomtableLigne."` l  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
   $i=1;

   foreach ($empRecords as $row) {

      if($row['idClient']!=0){
         $stmtClient = $bdd->prepare("SELECT * FROM `".$nomtableClient."` WHERE idClient=:idClient ");
         $stmtClient->bindValue(':idClient', $row['idClient'], PDO::PARAM_INT);
         $stmtClient->execute();
         $client = $stmtClient->fetch(); 
         $cols_client = strtoupper($client["nom"].' '.$client["prenom"]);
      }
      else{
         $cols_client = "INCONNU";
      }

      $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
      $stmtUser->bindValue(':idutilisateur', $row['iduser'], PDO::PARAM_INT);
      $stmtUser->execute();
      $user = $stmtUser->fetch(); 
      $cols_personnel = strtoupper($user["prenom"]);


      if($row['datepagej']==$dateString){
        
         $data[] = array(
            "idLigne"=>'<span style="color:#901E06;">'.$i.'</span>',
            "dateJour"=>'<span style="color:#901E06;">'.$row['dateJour'].'</span>',
            "heurePagnet"=>'<span style="color:#901E06;">'.$row['heurePagnet'].'</span>',
            "quantite"=>'<span style="color:#901E06;">'.$row['quantite'].'</span>',
            "unitevente"=>'<span style="color:#901E06;">'.$row['unitevente'].'</span>',
            "prixunitevente"=>'<span style="color:#901E06;">'.$row['prixunitevente'].'</span>',
            "prixtotal"=> '<span style="color:#901E06;">'.$row['prixtotal'].'</span>',
            "facture"=> '<span style="color:#901E06;"> #'.$row['idPagnet'].'</span>',
            "client"=> '<span style="color:#901E06;">'.$cols_client.'</span>',
            "personnel"=> '<span style="color:#901E06;">'.$cols_personnel.'</span>',
            "idDesignation"=> $row['idDesignation']
         );
      }
      else {
        
         $data[] = array(
            "idLigne"=> $i,
            "dateJour"=> $row['dateJour'] ,
            "heurePagnet"=> $row['heurePagnet'] ,
            "quantite"=> $row['quantite'] ,
            "unitevente"=> $row['unitevente'] ,
            "prixunitevente"=> $row['prixunitevente'] ,
            "prixtotal"=>  $row['prixtotal'] ,
            "facture"=> '#'.$row['idPagnet'] ,
            "client"=> $cols_client,
            "personnel"=> $cols_personnel ,
            "idDesignation"=> $row['idDesignation']
         );
      }

      $i++;

   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);