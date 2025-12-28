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
   $draw = @$_POST['draw'];
   $row = @$_POST['start'];
   $rowperpage = @$_POST['length']; // Rows display per page
   $columnIndex = @$_POST['order'][0]['column']; // Column index
   $columnName = @$_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = @$_POST['order'][0]['dir']; // asc or desc
   $searchValue = @$_POST['search']['value']; // Search value

   //var_dump($draw.''.$row);

   $searchArray = array();

   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           u.nom LIKE :nom OR
           u.prenom LIKE :prenom OR
           u.jour_1 LIKE :jour_1 OR
           u.jour_2 LIKE :jour_2 OR
           u.jour_3 LIKE :jour_3  ) ";
      $searchArray = array( 
           'nom'=>"%$searchValue%",
           'prenom'=>"%$searchValue%",
           'jour_1'=>"%$searchValue%",
           'jour_2'=>"%$searchValue%",
           'jour_3'=>"%$searchValue%"
      );
   }

   if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

         $idEntrepot = @$_POST['id'];

         // Total number of records without filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d.idDesignation)) AS allcount FROM `".$nomtableDesignation."` d
         INNER JOIN `".$nomtableEntrepotStock."` s ON s.idDesignation = d.idDesignation
         INNER JOIN `".$nomtableInventaire."` i ON i.idEntrepotStock  = s.idEntrepotStock  
         WHERE d.archiver<>1 AND i.type=2024 AND s.idEntrepot='".$idEntrepot."' ");
         $stmt->execute();
         $records = $stmt->fetch(); 
         $totalRecords = $records['allcount'];

         // Total number of records with filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d.idDesignation)) AS allcount FROM `".$nomtableDesignation."` d
         INNER JOIN `".$nomtableEntrepotStock."` s ON s.idDesignation = d.idDesignation
         INNER JOIN `".$nomtableInventaire."` i ON i.idEntrepotStock  = s.idEntrepotStock  
         WHERE 1 ".$searchQuery."  AND d.archiver<>1 AND i.type=2024 AND s.idEntrepot='".$idEntrepot."' ");
         $stmt->execute($searchArray);
         $records = $stmt->fetch();
         $totalRecordwithFilter = $records['allcount'];

         // Fetch records
         $stmt = $bdd->prepare("SELECT MAX(i.idInventaire) as idInventaire, MAX(i.dateInventaire) as dateInventaire, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
         d.uniteStock as uniteStock, d.nbreArticleUniteStock as nbreArticleUniteStock, d.prixachat as prixachat, d.prixuniteStock as prixuniteStock, d.prix as prix FROM `".$nomtableDesignation."` d  
         INNER JOIN `".$nomtableEntrepotStock."` s ON s.idDesignation = d.idDesignation
         INNER JOIN `".$nomtableInventaire."` i ON i.idEntrepotStock  = s.idEntrepotStock 
         WHERE 1 ".$searchQuery." AND d.archiver<>1 AND i.type=2024 AND s.idEntrepot='".$idEntrepot."' GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

         // Bind values
         foreach ($searchArray as $key=>$search) {
            $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
         }

         $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
         $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
         $stmt->execute();
         $empRecords = $stmt->fetchAll();

   } else{
      
         // Total number of records without filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(u.idutilisateur)) AS allcount FROM `aaa-utilisateur` u
         INNER JOIN `".$nomtableStock."` s ON s.iduser = u.idutilisateur WHERE u.profil='Inventaire' ");
         $stmt->execute();
         $records = $stmt->fetch();
         $totalRecords = $records['allcount'];

         // Total number of records with filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(u.idutilisateur)) AS allcount FROM `aaa-utilisateur` u
         INNER JOIN `".$nomtableStock."` s ON s.iduser = u.idutilisateur  
         WHERE 1 ".$searchQuery." AND u.profil='Inventaire' ");
         $stmt->execute($searchArray);
         $records = $stmt->fetch();
         $totalRecordwithFilter = $records['allcount'];

         $jour1 = '2024-07-29';
         $jour2 = date('Y-m-d', strtotime($jour1. ' + 1 days'));
         $jour3 = date('Y-m-d', strtotime($jour1. ' + 2 days'));
         // Fetch records
         $stmt = $bdd->prepare("SELECT COUNT( CASE WHEN s.dateStockage='".$jour1."' THEN 1 END ) AS jour_1,
         COUNT( CASE WHEN dateStockage='".$jour2."' THEN 1 END ) AS jour_2,
         COUNT( CASE WHEN dateStockage='".$jour3."' THEN 1 END ) AS jour_3, u.idutilisateur as idutilisateur, u.nom as nom, u.prenom as prenom
         FROM `aaa-utilisateur` u 
         INNER JOIN `".$nomtableStock."` s ON s.iduser = u.idutilisateur 
         WHERE 1 ".$searchQuery." AND u.profil='Inventaire' GROUP BY u.idutilisateur ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset "); 

         // Bind values
         foreach ($searchArray as $key=>$search) {
            $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
         }

         $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
         $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
         $stmt->execute();
         $empRecords = $stmt->fetchAll();

   }  

   $data = array();

   foreach ($empRecords as $row) {  

      //$dateDebut = $row['dateMin'];
      //$dateFin = date('Y-m-d', strtotime($row['dateMin']. ' + 1 days'));

      $stmtStock = $bdd->prepare("SELECT  SUM(totalArticleStock) as quantite FROM `".$nomtableStock."` 
      WHERE idDesignation = :idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
      $stmtStock->execute(array(
         ':idDesignation' => $row['idDesignation']
      ));
      $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

      $cols_operations = '<button type="button" disabled="true" onclick="details_Personnel('.$row["idutilisateur"].')" id="btn_Personnel-'.$row['idutilisateur'].'" class="btn btn-warning">
         <i class="glyphicon glyphicon-plus"></i> Details
      </button>';

      $data[] = array(
         "idutilisateur"=> $row['idutilisateur'],
         "nom"=>$row['nom'],
         "prenom"=>$row['prenom'],
         "jour_1"=>$row['jour_1'],
         "jour_2"=>$row['jour_2'],
         "jour_3"=>$row['jour_3'],
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