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

   if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      // Search
      $searchQuery = " ";
      if($searchValue != ''){
         $searchQuery = " AND (
            d.designation LIKE :designation OR
            d.codeBarreDesignation LIKE :codeBarreDesignation OR 
            d.forme LIKE :forme OR
            d.tableau LIKE :tableau OR 
            d.prixPublic LIKE :prixPublic OR
            d.prixSession LIKE :prixSession ) ";
         $searchArray = array( 
            'designation'=>"%$searchValue%",
            'codeBarreDesignation'=>"%$searchValue%",
            'forme'=>"%$searchValue%",
            'tableau'=>"%$searchValue%",
            'prixpublic'=>"%$searchValue%",
            'prixSession'=>"%$searchValue%"
         );
      }
   }
   else{
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
         $stmt = $bdd->prepare("SELECT SUM(i.quantiteStockCourant) as quantite, MAX(i.idInventaire) as idInventaire, MIN(i.dateInventaire) as dateMin, MAX(i.dateInventaire) as dateMax, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
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
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d.idDesignation)) AS allcount FROM `".$nomtableDesignation."` d
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation WHERE i.type=2024  ");
         $stmt->execute();
         $records = $stmt->fetch();
         $totalRecords = $records['allcount'];

         // Total number of records with filtering
         $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(d.idDesignation)) AS allcount FROM `".$nomtableDesignation."` d
         INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation  
         WHERE 1 ".$searchQuery." AND  i.type=2024 ");
         $stmt->execute($searchArray);
         $records = $stmt->fetch();
         $totalRecordwithFilter = $records['allcount'];

         if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

               // Fetch records
               $stmt = $bdd->prepare("SELECT SUM(i.quantiteStockCourant) as quantite, MAX(i.idInventaire) as idInventaire, MIN(i.dateInventaire) as dateMin, MAX(i.dateInventaire) as dateMax, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
               d.forme as forme, d.tableau as tableau, d.prixPublic as prixPublic, d.prixSession as prixSession FROM `".$nomtableDesignation."` d  
               INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation 
               WHERE 1 ".$searchQuery." AND i.type=2024  GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

         }
         else{

               // Fetch records
               $stmt = $bdd->prepare("SELECT SUM(i.quantiteStockCourant) as quantite, MAX(i.idInventaire) as idInventaire, MIN(i.dateInventaire) as dateMin, MAX(i.dateInventaire) as dateMax, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
               d.uniteStock as uniteStock, d.nbreArticleUniteStock as nbreArticleUniteStock, d.prixachat as prixachat, d.prixuniteStock as prixuniteStock, d.prix as prix FROM `".$nomtableDesignation."` d  
               INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation 
               WHERE 1 ".$searchQuery." AND i.type=2024  GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

         }   

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

      
      $dateDebut = $row['dateMin'];
      $dateFin = date('Y-m-d', strtotime($row['dateMin']. ' + 1 days'));

      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
         
         $stmtStock = $bdd->prepare("SELECT  SUM(totalArticleStock) as quantite FROM `".$nomtableEntrepotStock."` 
         WHERE idDesignation = :idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') AND idEntrepot='".$idEntrepot."' ");
         $stmtStock->execute(array(
            ':idDesignation' => $row['idDesignation']
         ));
         $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

         if($row['nbreArticleUniteStock']!=0 && $row['nbreArticleUniteStock']!=null){
            $quantite = $stock['quantite']/$row['nbreArticleUniteStock'];
         }
         else {
            $quantite = $stock['quantite'];
         }

      }
      else{
        
         $stmtStock = $bdd->prepare("SELECT  SUM(totalArticleStock) as quantite FROM `".$nomtableStock."` 
         WHERE idDesignation = :idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
         $stmtStock->execute(array(
            ':idDesignation' => $row['idDesignation']
         ));
         $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

         $quantite = $stock['quantite'];

      }  


      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

         $cols_inventaire= $quantite;
         $cols_quantite = $row['quantite'];
         $cols_codeBarre = $row['codeBarreDesignation'];
         $cols_forme = $row['forme'];
         $cols_tableau = $row['tableau'];
         $cols_prixPublic= $row['prixPublic'];
         $cols_prixSession = $row['prixSession'];
   
         $cols_operations = '<button type="button" onclick="details_Inventaire('.$row["idDesignation"].')" id="btn_Inventaire-'.$row['idDesignation'].'" class="btn btn-warning">
                  <i class="glyphicon glyphicon-plus"></i> Details
               </button>';
   
         if($row['dateInventaire']==$dateString){
   
            $data[] = array(
               "idStock"=> $row['idDesignation'],
               "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
               "codeBarreDesignation"=>'<span style="color:#901E06;">'.$cols_codeBarre.'</span>',
               "forme"=>'<span style="color:#901E06;">'.$cols_forme.'</span>',
               "tableau"=>'<span style="color:#901E06;">'.$cols_tableau.'</span>',
               "prixPublic"=> '<span style="color:#901E06;">'.$cols_prixPublic.'</span>',
               "prixSession"=> '<span style="color:#901E06;">'.$cols_prixSession.'</span>',
               "quantite"=>'<span style="color:#901E06;">'.$cols_quantite.'</span>',
               "inventaire"=>'<span style="color:#901E06;">'.$cols_inventaire.'</span>',
               "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
               "idDesignation"=> $row['idInventaire']
            );
         }
         else {
   
            $data[] = array(
               "idStock"=> $row['idDesignation'],
               "designation"=>$row['designation'],
               "codeBarreDesignation"=>$cols_codeBarre,
               "forme"=>$cols_forme,
               "tableau"=>$cols_tableau,
               "prixPublic"=> $cols_prixPublic,
               "prixSession"=> $cols_prixSession,
               "quantite"=>$cols_quantite,
               "inventaire"=>$cols_inventaire,
               "operations"=> $cols_operations,
               "idDesignation"=>$row['idInventaire']
            );
         }
      }
      else{

         $cols_inventaire= $quantite;
         $cols_quantite = $row['quantite'];
         $cols_codeBarre = $row['codeBarreDesignation'];
         $cols_uniteStock = $row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].'] ' ;
         $cols_prixuniteStock = $row['prixuniteStock'];
         $cols_prixunitaire = $row['prix'];
         $cols_prixachat = $row['prixachat'];
   
         $cols_operations = '<button type="button" onclick="details_Inventaire('.$row["idDesignation"].')" id="btn_Inventaire-'.$row['idDesignation'].'" class="btn btn-warning">
                  <i class="glyphicon glyphicon-plus"></i> Details
               </button>';
   
         if($row['dateInventaire']==$dateString){
   
            $data[] = array(
               "idStock"=> $row['idDesignation'],
               "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
               "codeBarreDesignation"=>'<span style="color:#901E06;">'.$cols_codeBarre.'</span>',
               "uniteStock"=>'<span style="color:#901E06;">'.$cols_uniteStock.'</span>',
               "prixuniteStock"=>'<span style="color:#901E06;">'.$cols_prixuniteStock.'</span>',
               "prixunitaire"=> '<span style="color:#901E06;">'.$cols_prixunitaire.'</span>',
               "prixachat"=> '<span style="color:#901E06;">'.$cols_prixachat.'</span>',
               "quantite"=>'<span style="color:#901E06;">'.$cols_quantite.'</span>',
               "inventaire"=>'<span style="color:#901E06;">'.$cols_inventaire.'</span>',
               "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
               "idDesignation"=> $row['idInventaire']
            );
         }
         else {
   
            $data[] = array(
               "idStock"=> $row['idDesignation'],
               "designation"=>$row['designation'],
               "codeBarreDesignation"=>$cols_codeBarre,
               "uniteStock"=>$cols_uniteStock,
               "prixuniteStock"=>$cols_prixuniteStock,
               "prixunitaire"=> $cols_prixunitaire,
               "prixachat"=> $cols_prixachat,
               "quantite"=>$cols_quantite,
               "inventaire"=>$cols_inventaire,
               "operations"=> $cols_operations,
               "idDesignation"=>$row['idInventaire']
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