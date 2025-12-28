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
           codeBarreDesignation LIKE :codeBarreDesignation) ";
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

      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

         $stmtStock = $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableStock."` 
            WHERE idDesignation=:idDesignation");
         $stmtStock->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
         $stmtStock->execute();
         $stock = $stmtStock->fetch(); 

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

         if($row['nbreArticleUniteStock']!=0 || $row['nbreArticleUniteStock']!=null){
            if($depot['quantite']!=0 && $depot['quantite']!=null){
               $cols_quantite = ($depot['quantite'] / $row['nbreArticleUniteStock']) ;
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

      }
      else {

         $stmtStock = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` 
         WHERE idDesignation=:idDesignation");
         $stmtStock->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
         $stmtStock->execute();
         $stock = $stmtStock->fetch(); 

      }

      $cols_operations = '<button type="button" onclick="ajouter_Fusion('.$row["idDesignation"].')" id="btn_AjtStock-'.$row['idDesignation'].'" class="btn btn-success">
               <i class="glyphicon glyphicon-plus"></i> Ajouter
            </button>';


      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
         $data[] = array(
            "idDesignation"=>$row['idDesignation'],
            "designation"=>$row['designation'],
            "codeBarreDesignation"=>$row['codeBarreDesignation'],
            "quantite"=>$stock['quantite'],
            "forme"=>$row['forme'],
            "tableau"=>$row['tableau'],
            "prixSession"=> $row['prixSession'],
            "prixPublic"=> $row['prixPublic'],
            "operations"=>$cols_operations
         );
      }
      else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
         $data[] = array(
            "idDesignation"=>$row['idDesignation'],
            "designation"=>$row['designation'],
            "codeBarreDesignation"=>$row['codeBarreDesignation'],
            "surdepot"=>$cols_quantite,
            "sansdepot"=>($stock['total'] - $entrepot['total']),
            "uniteStock"=>$row['uniteStock'],
            "nbreArticleUniteStock"=>$row['nbreArticleUniteStock'],
            "prixuniteStock"=>$row['prixuniteStock'],
            "prixachat"=> $row['prixachat'],
            "operations"=> $cols_operations
         );
      }
      else{
         $data[] = array(
            "idDesignation"=>$row['idDesignation'],
            "designation"=>$row['designation'],
            "codeBarreDesignation"=>$row['codeBarreDesignation'],
            "quantite"=>$stock['quantite'],
            "uniteStock"=>$row['uniteStock'],
            "nbreArticleUniteStock"=>$row['nbreArticleUniteStock'],
            "prixuniteStock"=>$row['prixuniteStock'],
            "prix"=> $row['prix'],
            "prixachat"=> $row['prixachat'],
            "operations"=>$cols_operations
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