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

      $stmtStock= $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableEntrepotStock."`  WHERE idDesignation=:idDesignation");
      $stmtStock->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtStock->execute();
      $stock = $stmtStock->fetch(); 

      if($stock['quantite'] == 0){
         if ($_SESSION['vitrine'] == 1 && $_SESSION['proprietaire'] == 1) {
            if ($row["image"]) {
               $cols_operations =  '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Produit('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Produit('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationD('.$row["idDesignation"].')" data-toggle="modal" data-target="#app'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
               <a class="glyphicon glyphicon-tag"id="promo'.$row["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$row["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
            }
            else{
               $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Produit('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Produit('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgND_DesignationD('.$row["idDesignation"].')" data-toggle="modal" data-target="#img'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
               <a class="glyphicon glyphicon-tag"id="promo'.$row["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$row["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
            }
         }
         else {
            $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Produit('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Produit('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>'; 
         }
      }
      else{
         if ($_SESSION['vitrine'] == 1 && $_SESSION['proprietaire'] == 1) {
            if ($row["image"]) {
               $cols_operations =  '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Produit('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationD('.$row["idDesignation"].')" data-toggle="modal" data-target="#app'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
               <a class="glyphicon glyphicon-tag"id="promo'.$row["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$row["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
            }
            else{
               $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Produit('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;
               <a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgND_DesignationD('.$row["idDesignation"].')" data-toggle="modal" data-target="#img'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
               <a class="glyphicon glyphicon-tag"id="promo'.$row["idDesignation"].'" align="middle" onclick="getDesignationPromo('.$row["idDesignation"].')" data-toggle="modal" data-target="#promo"></a>';
            }
         }
         else {
            $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Produit('.$row["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idDesignation"].'" /></a>&nbsp;'; 
         }
      }

      $cols_operations = $cols_operations.' <a href="detailsProduit.php?iDS='.$row["idDesignation"].'"><span style="color:green;">Details</span></a>';

      $data[] = array(
         "idDesignation"=>$row['idDesignation'],
         "designation"=>$row['designation'],
         "codeBarreDesignation"=>$row['codeBarreDesignation'],
         "uniteStock"=>$row['uniteStock'],
         "nbreArticleUniteStock"=>$row['nbreArticleUniteStock'],
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