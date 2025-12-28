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
           d.designation LIKE :designation OR
           d.codeBarreDesignation LIKE :codeBarreDesignation OR 
           d.uniteStock LIKE :uniteStock OR
           d.prixuniteStock LIKE :prixuniteStock OR 
           d.prix LIKE :prix OR
           d.prixachat LIKE :prixachat ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
           'codeBarreDesignation'=>"%$searchValue%",
           'uniteStock'=>"%$searchValue%",
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

   if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

         $idEntrepot = @$_POST['id'];

         // Total number of records without filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(s.idDesignation)) AS allcount FROM `".$nomtableEntrepotStock."` s
         INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation WHERE d.archiver<>1 AND s.idEntrepot='".$idEntrepot."'
         AND s.idDesignation NOT IN (
         SELECT s.idDesignation FROM `".$nomtableEntrepotStock."` s
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024 AND s.idEntrepot='".$idEntrepot."'
         )  ");
         $stmt->execute();
         $records = $stmt->fetch();
         $totalRecords = $records['allcount'];

         // Total number of records with filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(s.idDesignation)) AS allcount FROM `".$nomtableEntrepotStock."` s
         INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation  
         WHERE 1 ".$searchQuery." AND  d.archiver<>1 AND s.idEntrepot='".$idEntrepot."' AND s.idDesignation NOT IN (
         SELECT s.idDesignation FROM `".$nomtableEntrepotStock."` s
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024 AND s.idEntrepot='".$idEntrepot."'
         )");
         $stmt->execute($searchArray);
         $records = $stmt->fetch();
         $totalRecordwithFilter = $records['allcount'];

         // Fetch records
         $stmt = $bdd->prepare("SELECT MAX(s.idStock) as idStock, MAX(s.dateStockage) as dateStockage, s.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
         d.uniteStock as uniteStock, d.nbreArticleUniteStock as nbreArticleUniteStock, d.prixachat as prixachat, d.prixuniteStock as prixuniteStock, d.prix as prix FROM `".$nomtableEntrepotStock."` s
         INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation   
         WHERE 1 ".$searchQuery." AND d.archiver<>1 AND s.idEntrepot='".$idEntrepot."' AND s.idDesignation NOT IN (
         SELECT s.idDesignation FROM `".$nomtableEntrepotStock."` s
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024 AND s.idEntrepot='".$idEntrepot."'
         ) GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");
         // Bind values
         foreach ($searchArray as $key=>$search) {
            $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
         }

         $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
         $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
         $stmt->execute();
         $empRecords = $stmt->fetchAll();

   }
   else{

         // Total number of records without filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(s.idDesignation)) AS allcount FROM `".$nomtableStock."` s
         INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation WHERE d.archiver<>1
         AND s.idDesignation NOT IN (
         SELECT s.idDesignation FROM `".$nomtableStock."` s
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024
         )  ");
         $stmt->execute();
         $records = $stmt->fetch();
         $totalRecords = $records['allcount'];

         // Total number of records with filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(s.idDesignation)) AS allcount FROM `".$nomtableStock."` s
         INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation  
         WHERE 1 ".$searchQuery." AND  d.archiver<>1 AND s.idDesignation NOT IN (
         SELECT s.idDesignation FROM `".$nomtableStock."` s
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024
         )");
         $stmt->execute($searchArray);
         $records = $stmt->fetch();
         $totalRecordwithFilter = $records['allcount'];

         if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            // Fetch records
            $stmt = $bdd->prepare("SELECT MAX(s.idStock) as idStock, MAX(s.dateStockage) as dateStockage, s.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
            d.forme as forme, d.tableau as tableau, d.prixPublic as prixPublic, d.prixSession as prixSession FROM `".$nomtableStock."` s
            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation   
            WHERE 1 ".$searchQuery." AND d.archiver<>1 AND s.idDesignation NOT IN (
            SELECT s.idDesignation FROM `".$nomtableStock."` s
            INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024
            ) GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");
            // Bind values
            foreach ($searchArray as $key=>$search) {
               $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
            }

            $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
            $stmt->execute();
            $empRecords = $stmt->fetchAll();

         }
         else{

            // Fetch records
            $stmt = $bdd->prepare("SELECT MAX(s.idStock) as idStock, MAX(s.dateStockage) as dateStockage, s.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
            d.uniteStock as uniteStock, d.nbreArticleUniteStock as nbreArticleUniteStock, d.prixachat as prixachat, d.prixuniteStock as prixuniteStock, d.prix as prix FROM `".$nomtableStock."` s
            INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation   
            WHERE 1 ".$searchQuery." AND d.archiver<>1 AND s.idDesignation NOT IN (
            SELECT s.idDesignation FROM `".$nomtableStock."` s
            INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = s.idDesignation WHERE i.type=2024
            ) GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");
            // Bind values
            foreach ($searchArray as $key=>$search) {
               $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
            }

            $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
            $stmt->execute();
            $empRecords = $stmt->fetchAll();

         }  

   }  

   $data = array();

   foreach ($empRecords as $row) {

      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

         $cols_quantite= '<input type="number" class="form-control" id="inpt_NonInventaire_Quantite_'.$row['idDesignation'].'" min=1  required=""/>';
         $cols_codeBarre = $row['codeBarreDesignation'];
         $cols_forme = $row['forme'];
         $cols_tableau = $row['tableau'];
         $cols_prixPublic = '<input type="number" class="form-control" id="inpt_NonInventaire_PrixPublic_'.$row['idDesignation'].'" style="width: 100px;"  min=1 value="'.$row['prixPublic'].'" required=""/>';
         $cols_prixSession = '<input type="number" class="form-control" id="inpt_NonInventaire_PrixSession_'.$row['idDesignation'].'" min=1 value="'.$row['prixSession'].'" style="width: 100px;" required=""/>';
   
         $cols_operations = '<button type="button" onclick="inventaire_Produit('.$row["idDesignation"].')" id="btn_NonInventaire-'.$row['idDesignation'].'" class="btn btn-success">
                  <i class="glyphicon glyphicon-plus"></i> Corriger
               </button>';
   
         if($row['dateStockage']==$dateString){
           
            $data[] = array(
               "idStock"=> $row['idStock'],
               "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
               "codeBarreDesignation"=>'<span style="color:#901E06;">'.$cols_codeBarre.'</span>',
               "forme"=>'<span style="color:#901E06;">'.$cols_forme.'</span>',
               "tableau"=>'<span style="color:#901E06;">'.$cols_tableau.'</span>',
               "prixPublic"=> '<span style="color:#901E06;">'.$cols_prixPublic.'</span>',
               "prixSession"=> '<span style="color:#901E06;">'.$cols_prixSession.'</span>',
               "quantite"=>'<span style="color:#901E06;">'.$cols_quantite.'</span>',
               "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
               "idDesignation"=> $row['idDesignation']
            );
         }
         else {
           
            $data[] = array(
               "idStock"=> $row['idStock'],
               "designation"=>$row['designation'],
               "codeBarreDesignation"=>$cols_codeBarre,
               "forme"=>$cols_forme,
               "tableau"=>$cols_tableau,
               "prixPublic"=> $cols_prixPublic,
               "prixSession"=> $cols_prixSession,
               "quantite"=>$cols_quantite,
               "operations"=> $cols_operations,
               "idDesignation"=>$row['idDesignation']
            );
         }

      }
      else{

         $stmtDepot = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableEntrepotStock."` 
            WHERE idDesignation=:idDesignation AND idEntrepot='".$idEntrepot."' ");
         $stmtDepot->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
         $stmtDepot->execute();
         $depot = $stmtDepot->fetch(); 

         $cols_quantite= $depot['quantite'];
         $cols_codeBarre = $row['codeBarreDesignation'];
         $cols_uniteStock = $row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].'] ' ;
         $cols_prixuniteStock = $row['prixuniteStock'];
         $cols_prixunitaire = $row['prix'];
         $cols_prixachat = $row['prixachat'];
   
         $cols_operations = '<button type="button" onclick="inventaire_Produit('.$row["idDesignation"].')" id="btn_NonInventaire-'.$row['idDesignation'].'" class="btn btn-success">
                  <i class="glyphicon glyphicon-plus"></i> Corriger
               </button>';
   
         if($row['dateStockage']==$dateString){
           
            $data[] = array(
               "idStock"=> $row['idStock'],
               "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
               "codeBarreDesignation"=>'<span style="color:#901E06;">'.$cols_codeBarre.'</span>',
               "uniteStock"=>'<span style="color:#901E06;">'.$cols_uniteStock.'</span>',
               "prixuniteStock"=>'<span style="color:#901E06;">'.$cols_prixuniteStock.'</span>',
               "prixunitaire"=> '<span style="color:#901E06;">'.$cols_prixunitaire.'</span>',
               "prixachat"=> '<span style="color:#901E06;">'.$cols_prixachat.'</span>',
               "quantite"=>'<span style="color:#901E06;">'.$cols_quantite.'</span>',
               "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
               "idDesignation"=> $row['idDesignation']
            );
         }
         else {
           
            $data[] = array(
               "idStock"=> $row['idStock'],
               "designation"=>$row['designation'],
               "codeBarreDesignation"=>$cols_codeBarre,
               "uniteStock"=>$cols_uniteStock,
               "prixuniteStock"=>$cols_prixuniteStock,
               "prixunitaire"=> $cols_prixunitaire,
               "prixachat"=> $cols_prixachat,
               "quantite"=>$cols_quantite,
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