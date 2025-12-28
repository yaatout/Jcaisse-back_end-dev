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
           designation LIKE :designation OR
           codeBarreDesignation LIKE :codeBarreDesignation OR 
           prix LIKE :prix OR 
           description LIKE :description ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
           'codeBarreDesignation'=>"%$searchValue%",
           'prix'=>"%$searchValue%",
           'description'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableDesignation."` WHERE classe=50 AND archiver<>1 ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=50 AND archiver<>1 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=50 AND archiver<>1 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

      $stmtStock= $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."`  WHERE idDesignation=:idDesignation");
      $stmtStock->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtStock->execute();
      $stock = $stmtStock->fetch(); 

      $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Chambre('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Chambre('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>'; 

      $cols_operations = $cols_operations.' <a href="detailsChambre.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span></a>';

      $data[] = array(
         "idDesignation"=>$row['idDesignation'],
         "designation"=>$row['designation'],
         "codeBarreDesignation"=>$row['codeBarreDesignation'],
         "type"=>$row['type'],
         "categorie"=>$row['categorie'],
         "prix"=>$row['prix'],
         "description"=> $row['description'],
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