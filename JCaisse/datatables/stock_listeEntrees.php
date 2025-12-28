<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   $idDesignation = @$_POST['id'];
   $dateDebut = @$_POST['dateDebut'];
   $dateFin = @$_POST['dateFin'];

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
           dateStockage LIKE :dateStockage OR
           quantiteStockinitial LIKE :quantiteStockinitial OR
           quantiteStockCourant LIKE :quantiteStockCourant OR 
           uniteStock LIKE :uniteStock OR 
           prixuniteStock LIKE :prixuniteStock OR
           prixunitaire LIKE :prixunitaire OR 
           prixachat LIKE :prixachat OR
           dateExpiration LIKE :dateExpiration ) ";
      $searchArray = array( 
           'dateStockage'=>"%$searchValue%",
           'quantiteStockinitial'=>"%$searchValue%",
           'quantiteStockCourant'=>"%$searchValue%",
           'uniteStock'=>"%$searchValue%",
           'prixuniteStock'=>"%$searchValue%",
           'prixunitaire'=>"%$searchValue%",
           'prixachat'=>"%$searchValue%",
           'dateExpiration'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableStock."` WHERE idDesignation=:idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableStock."` WHERE 1 ".$searchQuery." AND idDesignation=:idDesignation  AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."')   ");
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableStock."` WHERE 1 ".$searchQuery."  AND idDesignation=:idDesignation  AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."')  ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {

      $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
      $stmtUser->bindValue(':idutilisateur', $row['iduser'], PDO::PARAM_INT);
      $stmtUser->execute();
      $user = $stmtUser->fetch(); 

      $cols_personnel = strtoupper($user["prenom"]);
      
      if($row['totalArticleStock']==$row['quantiteStockCourant']){
         if($_SESSION['proprietaire']==1){ 
             $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Stock('.$row["idStock"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idStock"].'" /></a>&nbsp;
             <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Stock('.$row["idStock"].')"  data-toggle="modal" data-target="#imgsup'.$row["idStock"].'" /></a>&nbsp
             <a onclick="stock_Retirer('.$row["idStock"].')" data-toggle="modal" ><span style="color:red" class="glyphicon glyphicon-open"></span></a>&nbsp
             <a onclick="stock_Retourner('.$row["idStock"].')" data-toggle="modal" ><span style="color:blue" class="glyphicon glyphicon-export"></span></a>&nbsp
             <a onclick="imprimer_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
             <a onclick="imprimerCodeBarre_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
         }
         else{
             if($dateString==$row['dateStockage'] && $_SESSION['caisse']==1){
               $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Stock('.$row["idStock"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$row["idStock"].'" /></a>&nbsp;
               <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Stock('.$row["idStock"].')"  data-toggle="modal" data-target="#imgsup'.$row["idStock"].'" /></a>
               <a onclick="imprimer_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
               <a onclick="imprimerCodeBarre_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
             }
             else{
               $cols_operations = '<a onclick="imprimer_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp';
             }
         }  
      }
      else{
         if($_SESSION['proprietaire']==1){
             $cols_operations = '<a onclick="stock_Retirer('.$row["idStock"].')" data-toggle="modal" ><span style="color:red" class="glyphicon glyphicon-open"></span></a>&nbsp
             <a onclick="stock_Retourner('.$row["idStock"].')" data-toggle="modal" ><span style="color:blue" class="glyphicon glyphicon-export"></span></a>&nbsp
             <a onclick="imprimer_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
             <a onclick="imprimerCodeBarre_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
         }
         else{
             $cols_operations = '<a onclick="imprimer_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
             <a onclick="imprimerCodeBarre_Stock('.$row["idStock"].')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
         }
          
      }

      if($row['dateStockage']==$dateString){
         //$cols_operations =  '<a href="stock.php?iDS='.$produit["idDesignation"].'"><span style="color:green;">Details</span>';

         $data[] = array(
            "idStock"=> $row['idStock'],
            "dateStockage"=>'<span style="color:#901E06;">'.$row['dateStockage'].'</span>',
            "quantiteStockinitial"=>'<span style="color:#901E06;">'.$row['quantiteStockinitial'].'</span>',
            "quantiteStockCourant"=>'<span style="color:#901E06;">'.$row['quantiteStockCourant'].'</span>',
            "uniteStock"=>'<span style="color:#901E06;">'.$row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].']</span>',
            "prixuniteStock"=>'<span style="color:#901E06;">'.$row['prixuniteStock'].'</span>',
            "prixunitaire"=> '<span style="color:#901E06;">'.$row['prixunitaire'].'</span>',
            "prixachat"=> '<span style="color:#901E06;">'.$row['prixachat'].'</span>',
            "dateExpiration"=> '<span style="color:#901E06;">'.$row['dateExpiration'].'</span>',
            "personnel"=> '<span style="color:#901E06;">'.$cols_personnel.'</span>',
            "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
            "idDesignation"=> $row['idDesignation']
         );
      }
      else {
        // $cols_operations =  '<a href="stock.php?iDS='.$produit["idDesignation"].'"><span style="color:green;">Details</span></a>';

         $data[] = array(
            "idStock"=> $row['idStock'],
            "dateStockage"=> $row['dateStockage'] ,
            "quantiteStockinitial"=> $row['quantiteStockinitial'] ,
            "quantiteStockCourant"=> $row['quantiteStockCourant'] ,
            "uniteStock"=> $row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].'] ' ,
            "prixuniteStock"=> $row['prixuniteStock'] ,
            "prixunitaire"=>  $row['prixunitaire'] ,
            "prixachat"=>  $row['prixachat'] ,
            "dateExpiration"=> $row['dateExpiration'] ,
            "personnel"=> $cols_personnel,
            "operations"=>  $cols_operations ,
            "idDesignation"=> $row['idDesignation']
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