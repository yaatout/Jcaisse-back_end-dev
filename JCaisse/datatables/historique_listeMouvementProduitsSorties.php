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
   WHERE p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.classe=0 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  GROUP BY idDesignation  ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableLigne."` l
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.classe=0 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))  GROUP BY idDesignation   ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l  
   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND l.classe=0 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  GROUP BY idDesignation  ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
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

      $stmtArticle = $bdd->prepare("SELECT SUM(quantite) AS total FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND ( l.unitevente='Article' OR l.unitevente='ARTICLE')
      AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."')) ");
      $stmtArticle->bindValue(':idDesignation', (int)$row['idDesignation'], PDO::PARAM_INT);
      $stmtArticle->execute();
      $articles = $stmtArticle->fetch();
      $total_Article = $articles['total'];

      $stmtUnite = $bdd->prepare("SELECT SUM(quantite) AS total FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND ( l.unitevente<>'Article' OR l.unitevente<>'ARTICLE')
      AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."')) ");
      $stmtUnite->bindValue(':idDesignation', (int)$row['idDesignation'], PDO::PARAM_INT);
      $stmtUnite->execute();
      $unites = $stmtUnite->fetch();
      $total_Unite = $unites['total'];

      $stmtProduit = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation=:idDesignation ");
      $stmtProduit->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtProduit->execute();
      $produit = $stmtProduit->fetch(); 

      $quantite = $total_Article + ($total_Unite * $produit['nbreArticleUniteStock']);

      $data[] = array(
         "idDesignation"=> $row['idDesignation'],
         "designation"=> $row['designation'] ,
         "quantite"=> $quantite ,         
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