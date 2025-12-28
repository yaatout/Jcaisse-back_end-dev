<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   $idBl = @$_POST['id'];

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
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableStock."` WHERE idBl=:idBl  ");
   $stmt->bindValue(':idBl', (int)$idBl, PDO::PARAM_INT);
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableStock."` WHERE 1 ".$searchQuery." AND idBl=:idBl  ");
   $stmt->bindValue(':idBl', (int)$idBl, PDO::PARAM_INT);
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT *, quantiteStockCourant as restant, quantiteStockinitial as initial, totalArticleStock * prixachat as montantachat FROM `".$nomtableStock."` 
                WHERE 1 ".$searchQuery." AND idBl=:idBl ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->bindValue(':idBl', (int)$idBl, PDO::PARAM_INT);
   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {

      $stmtProduit = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation=:idDesignation ");
      $stmtProduit->bindValue(':idDesignation', $row['idDesignation'], PDO::PARAM_INT);
      $stmtProduit->execute();
      $produit = $stmtProduit->fetch(); 

      $stmtStock= $bdd->prepare("SELECT * FROM `".$nomtableEntrepotStock."`  WHERE idStock=:idStock limit 1");
      $stmtStock->bindValue(':idStock', $row['idStock'], PDO::PARAM_INT);
      $stmtStock->execute();
      $stock = $stmtStock->fetch(); 
      if($stock!=null){
         $stmtDepot = $bdd->prepare("SELECT SUM(quantiteStockCourant) as total FROM `".$nomtableEntrepotStock."` 
               WHERE idStock=:idStock");
         $stmtDepot->bindValue(':idStock', $row['idStock'], PDO::PARAM_INT);
         $stmtDepot->execute();
         $depot = $stmtDepot->fetch(); 
         if($depot['total']==$row['totalArticleStock']){
            $cols_quantite = $row['initial'];
            $cols_prixAchat = '<input type="number" class="form-control" id="inpt_mdf_Stock_PrixAchat_'.$row['idStock'].'" min=1 style="width: 100px;" value="'.$row['prixachat'].'"/>';
            $cols_expiration = '<input type="date" class="form-control" id="inpt_mdf_Stock_DateExpiration_'.$row['idStock'].'" value="'.$row['dateExpiration'].'" />';
            $cols_montant = '<span id="inpt_Stock_Montant_'.$row['idStock'].'">'.$row['montantachat'].'</span>';
   
            $cols_operations = '<a onclick="transferer_Stock('.$row["idStock"].')" data-toggle="modal" class="btn btn-warning btn-xs glyphicon glyphicon-transfer" ></a>&nbsp
               <button type="button" onclick="modifier_Stock('.$row["idStock"].')" id="btn_modifier_Stock-'.$row['idStock'].'" class="btn btn-success btn-xs">
               <i class="glyphicon glyphicon-pencil"></i>
            </button>';
         }
         else{
            $cols_quantite = $row['initial'];
            $cols_prixAchat = $row['prixachat'];
            $cols_expiration = $row['dateExpiration'];
            $cols_montant = $row['montantachat'];
   
            $cols_operations = '<a onclick="transferer_Stock('.$row["idStock"].')" data-toggle="modal" class="btn btn-warning btn-xs glyphicon glyphicon-transfer" ></a>&nbsp';
         }
      }
      else{
         $cols_quantite = '<input type="number" class="form-control" id="inpt_mdf_Stock_Quantite_'.$row['idStock'].'" min=1 style="width: 70px;" value="'.$row['initial'].'"/>';
         $cols_prixAchat = '<input type="number" class="form-control" id="inpt_mdf_Stock_PrixAchat_'.$row['idStock'].'" min=1 style="width: 100px;" value="'.$row['prixachat'].'"/>';
         $cols_expiration = '<input type="date" class="form-control" id="inpt_mdf_Stock_DateExpiration_'.$row['idStock'].'" value="'.$row['dateExpiration'].'" />';
         $cols_montant = '<span id="inpt_Stock_Montant_'.$row['idStock'].'">'.$row['montantachat'].'</span>';

         $cols_operations = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Stock('.$row["idStock"].')"  data-toggle="modal" data-target="#imgsup'.$row["idStock"].'" /></a>&nbsp
            <a onclick="transferer_Stock('.$row["idStock"].')" data-toggle="modal" class="btn btn-warning btn-xs glyphicon glyphicon-transfer" ></a>&nbsp
            <button type="button" onclick="modifier_Stock('.$row["idStock"].')" id="btn_modifier_Stock-'.$row['idStock'].'" class="btn btn-success btn-xs">
            <i class="glyphicon glyphicon-pencil"></i>
         </button>';
      }

      if($row['dateStockage']==$dateString){
        
         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=>'<span style="color:#901E06;">'.$row['designation'].'</span>',
            "initial"=>'<span style="color:#901E06;">'.$cols_quantite.'</span>',
            "uniteStock"=>'<span style="color:#901E06;">'.$row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].']</span>',
            "prixachat"=> '<span style="color:#901E06;">'.$cols_prixAchat.'</span>',
            "montantachat"=> '<span style="color:#901E06;">'.$cols_montant.'</span>',
            "dateExpiration"=> '<span style="color:#901E06;">'.$cols_expiration.'</span>',
            "prixuniteStock"=>'<span style="color:#901E06;">'.$row['prixuniteStock'].'</span>',
            "dateStockage"=> '<span style="color:#901E06;">'.$row['dateStockage'].'</span>',
            "operations"=> '<span style="color:#901E06;">'.$cols_operations.'</span>',
            "idDesignation"=> $row['idDesignation']
         );
      }
      else {
        
         $data[] = array(
            "idStock"=> $row['idStock'],
            "designation"=> $row['designation'],
            "initial"=> $cols_quantite,
            "uniteStock"=> $row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].']',
            "prixachat"=>  $cols_prixAchat,
            "montantachat"=>  $cols_montant,
            "dateExpiration"=>  $cols_expiration,
            "prixuniteStock"=> $row['prixuniteStock'],
            "dateStockage"=>  $row['dateStockage'],
            "operations"=>  $cols_operations,
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