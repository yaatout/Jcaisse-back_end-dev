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
         designation LIKE :designation ) ";
      $searchArray = array( 
         'designation'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  GROUP BY idDesignation  ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))  GROUP BY idDesignation   ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT *,SUM(prixtotal) AS montant FROM `".$nomtableLigne."` l  
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  GROUP BY idDesignation  ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
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

      $data[] = array(
         "idDesignation"=> $row['idDesignation'],
         "designation"=> $row['designation'] ,
         "montant"=> $row['montant']       
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