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
           d.designation LIKE :designation OR
           d.codeBarreDesignation LIKE :codeBarreDesignation OR 
           d.uniteStock LIKE :uniteStock OR
           d.nbreArticleUniteStock LIKE :nbreArticleUniteStock OR
           d.prixuniteStock LIKE :prixuniteStock OR 
           d.prix LIKE :prix OR
           d.prixachat LIKE :prixachat ) ";
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
   /*
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount, max(s.idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation, d.codeBarreDesignation, d.uniteStock, d.nbreArticleUniteStock
                FROM `".$nomtableStock."` s LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) GROUP BY s.idDesignation ORDER BY s.idStock DESC ");
   $stmt->execute();
   */

   // Total number of records with filtering
   /*
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount,max(s.idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation, d.codeBarreDesignation, d.uniteStock, d.nbreArticleUniteStock
   FROM `".$nomtableStock."` s LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
   WHERE 1 ".$searchQuery." AND d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) GROUP BY s.idDesignation ORDER BY s.idStock DESC ");
   $stmt->execute($searchArray);
   */

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(s.idDesignation)) AS allcount FROM `".$nomtableStock."` s
   INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation WHERE d.archiver<>1  ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(s.idDesignation)) AS allcount FROM `".$nomtableStock."` s
   INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation  
   WHERE 1 ".$searchQuery." AND  d.archiver<>1 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT MAX(s.idStock) as idStock, SUM(s.quantiteStockCourant) as quantite, MAX(s.dateStockage) as dateStockage, s.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation,
   d.uniteStock as uniteStock, d.nbreArticleUniteStock as nbreArticleUniteStock, d.prixuniteStock as prixuniteStock, d.prix as prix, d.prixachat as prixachat FROM `".$nomtableStock."` s
   INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation   
   WHERE 1 ".$searchQuery." AND d.archiver<>1 GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

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

      //Somme des Pagnets Bons du Client
      $stmtInventaire = $bdd->prepare("SELECT COUNT(idDesignation) as total FROM `".$nomtableInventaire."` 
      WHERE type=2024 AND idDesignation=:idDesignation ");
      $stmtInventaire->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtInventaire->execute();
      $inventaire = $stmtInventaire->fetch(); 
      //'<span style="color:#901E06;">'.$row['idStock'].'<span>',

      if($row['dateStockage']==$dateString){
         $cols_operations =  '<a href="stock.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span>';

         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
            "codeBarreDesignation"=>'<span style="color:#901E06;">'.$row['codeBarreDesignation'].'</span>',
            "quantite"=>'<span style="color:#901E06;">'.$row['quantite'].'</span>',
            "uniteStock"=>'<span style="color:#901E06;">'.$row['uniteStock'].'</span>',
            "nbreArticleUniteStock"=>'<span style="color:#901E06;">'.$row['nbreArticleUniteStock'].'</span>',
            "prixuniteStock"=>'<span style="color:#901E06;">'.$row['prixuniteStock'].'</span>',
            "prixunitaire"=> '<span style="color:#901E06;">'.$row['prix'].'</span>',
            "prixachat"=> '<span style="color:#901E06;">'.$row['prixachat'].'</span>',
            "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
            "idDesignation"=> $row['idDesignation']
         );
      }
      else {
         $cols_operations =  '<a href="stock.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span></a>';

         if($inventaire['total'] > 0){
            $data[] = array(
               "idStock"=> $row['idStock'],
               "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
               "codeBarreDesignation"=>'<span style="color:#901E06;">'.$row['codeBarreDesignation'].'</span>',
               "quantite"=>'<span style="color:#901E06;">'.$row['quantite'].'</span>',
               "uniteStock"=>'<span style="color:#901E06;">'.$row['uniteStock'].'</span>',
               "nbreArticleUniteStock"=>'<span style="color:#901E06;">'.$row['nbreArticleUniteStock'].'</span>',
               "prixuniteStock"=>'<span style="color:#901E06;">'.$row['prixuniteStock'].'</span>',
               "prixunitaire"=> '<span style="color:#901E06;">'.$row['prix'].'</span>',
               "prixachat"=> '<span style="color:#901E06;">'.$row['prixachat'].'</span>',
               "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
               "idDesignation"=> $row['idDesignation']
            );
         }
         else {
            $data[] = array(
               "idStock"=> $row['idStock'],
               "designation"=>$row['designation'],
               "codeBarreDesignation"=>$row['codeBarreDesignation'],
               "quantite"=>$row['quantite'],
               "uniteStock"=>$row['uniteStock'],
               "nbreArticleUniteStock"=>$row['nbreArticleUniteStock'],
               "prixuniteStock"=>$row['prixuniteStock'],
               "prixunitaire"=> $row['prix'],
               "prixachat"=> $row['prixachat'],
               "operations"=> $cols_operations,
               "idDesignation"=>$row['idDesignation']
            );
         }

      }



   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);