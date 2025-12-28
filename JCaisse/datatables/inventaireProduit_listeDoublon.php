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

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           designation LIKE :designation OR
           codeBarreDesignation LIKE :codeBarreDesignation OR 
           uniteStock LIKE :uniteStock OR
           nbreArticleUniteStock LIKE :nbreArticleUniteStock OR
           prixuniteStock LIKE :prixuniteStock OR 
           prix LIKE :prix OR
           prixachat LIKE :prixachat ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
           'codeBarreDesignation'=>"%$searchValue%",
           'uniteStock'=>"%$searchValue%",
           'nbreArticleUniteStock'=>"%$searchValue%",
           'prixuniteStock'=>"%$searchValue%",
           'prix'=>"%$searchValue%",
           'prixachat'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d1.idDesignation)) AS allcount 
   FROM `".$nomtableDesignation."` d1
   WHERE EXISTS (
               SELECT *
               FROM `".$nomtableDesignation."` d2
               WHERE d1.idDesignation <> d2.idDesignation
               AND  ( d1.designation = d2.designation  OR ( d1.codeBarreDesignation = d2.codeBarreDesignation  AND d1.codeBarreDesignation<>'' AND d2.codeBarreDesignation<>''  )  )
                )");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d1.idDesignation)) AS allcount 
   FROM `".$nomtableDesignation."` d1
   WHERE 1 ".$searchQuery." AND  classe=0 AND EXISTS (
               SELECT *
               FROM `".$nomtableDesignation."` d2
               WHERE d1.idDesignation <> d2.idDesignation
               AND  ( d1.designation = d2.designation  OR ( d1.codeBarreDesignation = d2.codeBarreDesignation  AND d1.codeBarreDesignation<>'' AND d2.codeBarreDesignation<>''  )  )
   )  ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` d1
   WHERE 1 ".$searchQuery." AND classe=0 AND EXISTS (
               SELECT *
               FROM `".$nomtableDesignation."` d2
               WHERE d1.idDesignation <> d2.idDesignation
               AND  ( d1.designation = d2.designation  OR ( d1.codeBarreDesignation = d2.codeBarreDesignation  AND d1.codeBarreDesignation<>'' AND d2.codeBarreDesignation<>''  )  )
   )
   GROUP BY d1.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

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

      $cols_operations = '<button type="button" onclick="doublon_Produit('.$row["idDesignation"].')" id="btn_Doublon-'.$row['idDesignation'].'" class="btn btn-success">
         <i class="glyphicon glyphicon-plus"></i> Fusion
      </button>';

      $data[] = array(
         "idDesignation"=>$row['idDesignation'],
         "designation"=>$row['designation'],
         "codeBarreDesignation"=>$row['codeBarreDesignation'],
         "uniteStock"=> $row['uniteStock'].'[x '.$row['nbreArticleUniteStock'].']',
         "nbreArticleUniteStock"=> '',
         "prixuniteStock"=>$row['prixuniteStock'],
         "prix"=> $row['prix'],
         "prixachat"=> $row['prixachat'],
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