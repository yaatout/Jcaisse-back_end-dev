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


   if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      // Search
      $searchQuery = " ";
      if($searchValue != ''){
         $searchQuery = " AND (
            designation LIKE :designation OR
            prixPublic LIKE :prixPublic OR
            forme LIKE :forme ) ";
         $searchArray = array( 
            'designation'=>"%$searchValue%",
            'prixPublic'=>"%$searchValue%",
            'forme'=>"%$searchValue%"
         );
      }
   }
   else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
      // Search
      $searchQuery = " ";
      if($searchValue != ''){
         $searchQuery = " AND (
              designation LIKE :designation OR
              prixuniteStock LIKE :prixuniteStock OR
              uniteStock LIKE :uniteStock ) ";
         $searchArray = array( 
              'designation'=>"%$searchValue%",
              'prixuniteStock'=>"%$searchValue%",
              'uniteStock'=>"%$searchValue%"
         );
      }
   } 
   else {
      // Search
      $searchQuery = " ";
      if($searchValue != ''){
         $searchQuery = " AND (
            designation LIKE :designation OR
            prix LIKE :prix OR
            uniteStock LIKE :uniteStock) ";
         $searchArray = array( 
            'designation'=>"%$searchValue%",
            'prix'=>"%$searchValue%",
            'uniteStock'=>"%$searchValue%"
         );
      }
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableDesignation."` WHERE classe=2 ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=2 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      // Fetch records
      $stmt = $bdd->prepare("SELECT idDesignation, designation, forme as unite, prixPublic as prix FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=2 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   }
   else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
      // Fetch records
      $stmt = $bdd->prepare("SELECT idDesignation, designation, uniteStock as unite, prixuniteStock as prix FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=2 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   }
   else{
      // Fetch records
      $stmt = $bdd->prepare("SELECT idDesignation, designation, uniteStock as unite, prix as prix FROM `".$nomtableDesignation."` WHERE 1 ".$searchQuery." AND  classe=2 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   }

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

      $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` WHERE idDesignation = :idDesignation Limit 1 ");
      $stmtPanier->execute(array( ':idDesignation' => $row['idDesignation'] ));
      $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);

      if($panier){
         $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Depense('.$row["idDesignation"].')" data-toggle="modal"/></a>&nbsp';   
      }
      else {
         $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Depense('.$row["idDesignation"].')" data-toggle="modal"/></a>&nbsp
         <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Depense('.$row["idDesignation"].')" data-toggle="modal" /></a>&nbsp';   
      }

      $cols_operations = $cols_operations.' <a href="detailsDepense.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span></a>';
    
      $data[] = array(
         "idDesignation"=>$row['idDesignation'],
         "designation"=>$row['designation'],
         "unite"=>$row['unite'],
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