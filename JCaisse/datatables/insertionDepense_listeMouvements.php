<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../JCaisse/index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   // Reading value
   $draw = $_POST['draw'];
   $row = $_POST['start'];
   $rowperpage = $_POST['length']; // Rows display per page
   $columnIndex = $_POST['order'][0]['column']; // Column index
   $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
   $searchValue = $_POST['search']['value']; // Search value

   //var_dump($draw.''.$row);

   $dateDebut = @$_POST['dateDebut'];
   $dateFin = @$_POST['dateFin'];

   $searchArray = array();

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           d.designation LIKE :designation ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d.idDesignation)) AS allcount FROM `".$nomtableDesignation."` d
   INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation 
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
   WHERE d.classe=2 AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d.idDesignation)) AS allcount FROM `".$nomtableDesignation."` d
   INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation 
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
   WHERE 1 ".$searchQuery." AND d.classe=2 AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT SUM(l.prixtotal) AS montant, SUM(l.quantite) AS quantite, d.idDesignation, d.designation FROM `".$nomtableDesignation."` d
   INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation 
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
   WHERE 1 ".$searchQuery." AND d.classe=2 AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')
   GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

      $cols_operations = '<a href="detailsDepense.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span></a>';
    
      $data[] = array(
         "idDesignation"=>$row['idDesignation'],
         "designation"=>$row['designation'],
         "quantite"=>$row['quantite'],
         "montant"=> $row['montant'],
         "operations"=>$cols_operations
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