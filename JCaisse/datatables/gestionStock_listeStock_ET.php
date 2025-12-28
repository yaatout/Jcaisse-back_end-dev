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
           designation LIKE :designation ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%"
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
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(idDesignation)) AS allcount FROM `".$nomtableStock."` ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(idDesignation)) AS allcount FROM `".$nomtableStock."` WHERE 1 ".$searchQuery." ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT MAX(idStock) as idStock, SUM(quantiteStockinitial) as total, MAX(dateStockage) as dateStockage, idDesignation, designation FROM `".$nomtableStock."` 
                WHERE 1 ".$searchQuery." GROUP BY idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

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
      $stmtProduit = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation=:idDesignation ");
      $stmtProduit->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtProduit->execute();
      $produit = $stmtProduit->fetch(); 
      //'<span style="color:#901E06;">'.$row['idStock'].'<span>',
      if ($_SESSION['idBoutique']==194) {
         $produit['prixachat'] = $produit['prixachat']*$produit['nbreArticleUniteStock'];
      }

      $stmtDepot = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableEntrepotStock."` 
         WHERE idDesignation=:idDesignation");
      $stmtDepot->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtDepot->execute();
      $depot = $stmtDepot->fetch(); 

      $stmtEntrepot = $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableEntrepotStock."` 
        WHERE idDesignation=:idDesignation AND (idTransfert=0 OR idTransfert IS NULL) ");
      $stmtEntrepot->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtEntrepot->execute();
      $entrepot = $stmtEntrepot->fetch(); 

      if($produit['nbreArticleUniteStock']!=0 || $produit['nbreArticleUniteStock']!=null){
         if($depot['quantite']!=0 && $depot['quantite']!=null){
            $cols_quantite = ($depot['quantite'] / $produit['nbreArticleUniteStock']) ;
         }
         else{
            $cols_quantite = 0;
         }
       }
       else{
         if($depot['quantite']!=0 && $depot['quantite']!=null){
            $cols_quantite = $depot['quantite'];
         }
         else{
            $cols_quantite = 0;
         }
       }


      if($row['dateStockage']==$dateString){
         $cols_operations =  '<a href="stock-Entrepot.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span>';

         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=>'<span style="color:#901E06;">'.$row['designation'].'<span>',
            "surdepot"=>'<span style="color:#901E06;">'.$cols_quantite.'<span>',
            "sansdepot"=>'<span style="color:#901E06;">'.($row['total'] - $entrepot['total']).'<span>',
            "uniteStock"=>'<span style="color:#901E06;">'.$produit['uniteStock'].'<span>',
            "nbreArticleUniteStock"=>'<span style="color:#901E06;">'.$produit['nbreArticleUniteStock'].'<span>',
            "prixuniteStock"=>'<span style="color:#901E06;">'.$produit['prixuniteStock'].'<span>',
            "prixachat"=> '<span style="color:#901E06;">'.$produit['prixachat'].'<span>',
            "operations"=> '<span style="color:#901E06;">'.$cols_operations.'<span>',
            "idDesignation"=> '<span style="color:#901E06;">'.$row['idDesignation'].'<span>'
         );
      }
      else {
         $cols_operations =  '<a href="stock-Entrepot.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span>';

         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=>$row['designation'],
            "surdepot"=>$cols_quantite,
            "sansdepot"=>($row['total'] - $entrepot['total']),
            "uniteStock"=>$produit['uniteStock'],
            "nbreArticleUniteStock"=>$produit['nbreArticleUniteStock'],
            "prixuniteStock"=>$produit['prixuniteStock'],
            "prixachat"=> $produit['prixachat'],
            "operations"=> $cols_operations,
            "idDesignation"=>$row['idDesignation'],
         );
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