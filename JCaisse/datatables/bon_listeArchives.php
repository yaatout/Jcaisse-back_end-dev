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
   $draw = $_POST['draw'];
   $row = $_POST['start'];
   $rowperpage = $_POST['length']; // Rows display per page
   $columnIndex = $_POST['order'][0]['column']; // Column index
   $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
   $searchValue = $_POST['search']['value']; // Search value

   //var_dump($draw.''.$row);

   $searchArray = array();

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           nom LIKE :nom OR
           prenom LIKE :prenom OR 
           adresse LIKE :adresse OR
           telephone LIKE :telephone ) ";
      $searchArray = array( 
           'nom'=>"%$searchValue%",
           'prenom'=>"%$searchValue%",
           'adresse'=>"%$searchValue%",
           'telephone'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableClient."` WHERE archiver=1 ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableClient."` WHERE 1 ".$searchQuery." AND archiver=1 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableClient."` WHERE 1 ".$searchQuery." AND archiver=1 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }

   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $clients = $stmt->fetchAll();

   $data = array();

   foreach ($clients as $client) {

      //Montant a verser du Client
      $solde = $client['solde'];

      //Colonne Montant a verser
      if($solde>=0){
         $cols_montant = '<td><span class="alert-danger">'.number_format($solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$client['idClient'].'>Details </a></span></td>';
      }
      else{
         $cols_montant =  '<td> <span class="alert-success">'.number_format($solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$client['idClient'].'>Details </a></span></td>';
      }

      //Colonne Operations
      if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
         $cols_operations = '<td><button type="button" onclick="relancer_Client('.$client["idClient"].')" id="btn_relancer-'.$client['idClient'].'" class="btn btn-success btn-xs" > Relancer</button>&nbsp</td>';
      }
      else{
         $cols_operations = '';
      }

      $data[] = array(
         "nom"=>$client['nom'],
         "prenom"=>$client['prenom'],
         "adresse"=>$client['adresse'],
         "telephone"=>$client['telephone'],
         "montant"=> $cols_montant,
         "operations"=>$cols_operations
      );

      //Mise a jour du solde de tout les clients archives
      $stmtBons = $bdd->prepare("UPDATE  `".$nomtableClient."` SET solde=:solde WHERE idClient=:idClient ");
      $stmtBons->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
      $stmtBons->bindValue(':solde', $solde);
      $stmtBons->execute();
   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);