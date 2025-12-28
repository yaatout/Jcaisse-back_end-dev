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

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableDesignation."` WHERE classe=0 AND archiver<>1 ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=0 AND archiver<>1 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=0 AND archiver<>1 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

      $cols_quantite = '<input type="number" class="form-control" id="inpt_Stock_Quantite_'.$row['idDesignation'].'" min=1 value="1" required=""/>';
      $cols_prixSession = '<input type="number" class="form-control" id="inpt_Stock_PrixSession_'.$row['idDesignation'].'" style="width: 100px;"  min=1 value="'.$row['prixSession'].'" required=""/>';
      $cols_prixPublic = '<input type="number" class="form-control" id="inpt_Stock_PrixPublic_'.$row['idDesignation'].'" min=1 value="'.$row['prixPublic'].'" required=""/>';
      $cols_expiration = '<input type="date" class="form-control" id="inpt_Stock_DateExpiration_'.$row['idDesignation'].'" />';

      $cols_operations = '<button type="button" onclick="ajouter_Stock('.$row["idDesignation"].')" id="btn_AjtStock-'.$row['idDesignation'].'" class="btn btn-success">
               <i class="glyphicon glyphicon-plus"></i>
            </button>';

      $data[] = array(
         "idDesignation"=>$row['idDesignation'],
         "designation"=>$row['designation'],
         "quantite"=>$cols_quantite,
         "forme"=>$row['forme'],
         "prixSession"=>$cols_prixSession,
         "prixPublic"=> $cols_prixPublic,
         "expiration"=>$cols_expiration,
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