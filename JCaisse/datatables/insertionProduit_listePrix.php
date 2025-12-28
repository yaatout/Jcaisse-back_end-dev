<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

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
           prix LIKE :prix ) ";
      $searchArray = array( 
           'prix'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(prix)) AS allcount FROM `".$nomtableDesignation."` WHERE classe=0 ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(prix)) AS allcount FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=0 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT prix, COUNT(*) AS nombreFois  FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=0 GROUP BY prix ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

      $cols_operations ='<td style="width:25%">          
            <form action="etiquette.php" method="post" target="_blank">
            <input type="hidden" name="prixUnitaire" value="'.$row['prix'].'">
            <input type="hidden" name="nombreFois" value="'.$row['nombreFois'].'">
            <button type="submit" class="btn btn-primary" onclick="imprimer_Prix('.$row["prix"].','.$row['nombreFois'].')">
            <span style="color:white" class="glyphicon glyphicon-print"></span> Imprimer
            </button>
            </form>
      </td>';
      
      $data[] = array(
         "designation"=>$row['prix'],
         "quantite"=> $row['nombreFois'],
         "operations"=> $cols_operations,
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