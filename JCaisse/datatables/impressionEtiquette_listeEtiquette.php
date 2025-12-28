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
           codeBarreDesignation LIKE :codeBarreDesignation ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
           'codeBarreDesignation'=>"%$searchValue%"
      );
   }

   $totalRecords = count($_SESSION['etiquettes']);
   $totalRecordwithFilter = count($_SESSION['etiquettes']);
   $empRecords = $_SESSION['etiquettes'];

   $data = array();

   foreach ($empRecords as $produit => $row) {

      $cols_operations = '<a> <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Produit('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>'; 
        

      $data[] = array(
         "idDesignation"=> $row['idDesignation'],
         "designation"=>$row['designation'],
         "unite"=>$row['unite'],
         "quantite"=>$row['quantite'],
         "prix"=> $row['prix'],
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