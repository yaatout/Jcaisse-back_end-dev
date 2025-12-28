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

   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           d.designation LIKE :designation OR
           d.codeBarreDesignation LIKE :codeBarreDesignation  ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
           'codeBarreDesignation'=>"%$searchValue%"
      );
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
         $stmt = $bdd->prepare("SELECT MAX(i.idInventaire) as idInventaire, MAX(i.dateInventaire) as dateInventaire, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
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
               $stmt = $bdd->prepare("SELECT MAX(i.idInventaire) as idInventaire, MAX(i.dateInventaire) as dateInventaire, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
               d.forme as forme, d.tableau as tableau, d.prixPublic as prixPublic, d.prixSession as prixSession FROM `".$nomtableDesignation."` d  
               INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation 
               WHERE 1 ".$searchQuery." AND i.type=2024  GROUP BY d.idDesignation ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset ");

         }
         else{

               // Fetch records
               $stmt = $bdd->prepare("SELECT MAX(i.idInventaire) as idInventaire, MAX(i.dateInventaire) as dateInventaire, d.idDesignation, d.designation, d.codeBarreDesignation as codeBarreDesignation, 
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

      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
         
         $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableEntrepotStock."` WHERE idDesignation = :idDesignation AND idEntrepot='".$idEntrepot."' ");
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
        
         $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
         $stmtStock->execute(array(
            ':idDesignation' => $row['idDesignation']
         ));
         $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

         $quantite = $stock['quantite'];

      }  


      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

         $cols_inventaire= '<input type="number" class="form-control" id="inpt_Inventaire_Quantite_'.$row['idDesignation'].'" min=1  required=""/>';
         $cols_quantite = '<span id="span_Inventaire_Quantite_'.$row['idDesignation'].'">'.$quantite.'</span>';
         $cols_codeBarre = $row['codeBarreDesignation'];
         $cols_forme = $row['forme'];
         $cols_tableau = $row['tableau'];
         $cols_prixPublic= '<input type="number" class="form-control" id="inpt_Inventaire_PrixPublic_'.$row['idDesignation'].'" min=1 value="'.$row['prixPublic'].'" style="width: 100px;" required=""/>';
         $cols_prixSession = '<input type="number" class="form-control" id="inpt_Inventaire_PrixSession_'.$row['idDesignation'].'" min=1 value="'.$row['prixSession'].'" style="width: 100px;" required=""/>';
   
         $cols_operations = '<button type="button" onclick="ajouter_Stock('.$row["idDesignation"].')" id="btn_Inventaire-'.$row['idDesignation'].'" class="btn btn-success">
                  <i class="glyphicon glyphicon-plus"></i> Ajouter
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

         $cols_inventaire= '<input type="number" class="form-control" id="inpt_Inventaire_Quantite_'.$row['idDesignation'].'" min=1  required=""/>';
         $cols_quantite = '<span id="span_Inventaire_Quantite_'.$row['idDesignation'].'">'.$quantite.'</span>';
         $cols_codeBarre = $row['codeBarreDesignation'];
         $cols_uniteStock = $row['uniteStock'].' [x '.$row['nbreArticleUniteStock'].'] ' ;

         //$cols_prixuniteStock = $row['prixuniteStock'];
         //$cols_prixunitaire = $row['prix'];
         //$cols_prixachat = $row['prixachat'];

         $cols_prixuniteStock = '<input type="number" class="form-control" id="inpt_Inventaire_PrixUniteStock_'.$row['idDesignation'].'" style="width: 100px;"  min=1 value="'.$row['prixuniteStock'].'" required=""/>';
         $cols_prixunitaire = '<input type="number" class="form-control" id="inpt_Inventaire_PrixUnitaire_'.$row['idDesignation'].'" min=1 value="'.$row['prix'].'" style="width: 100px;" required=""/>';
         $cols_prixachat = '<input type="number" class="form-control" id="inpt_Inventaire_PrixAchat_'.$row['idDesignation'].'" min=1 value="'.$row['prixachat'].'" style="width: 100px;" required=""/>';
   
         $cols_operations = '<button type="button" onclick="ajouter_Stock('.$row["idDesignation"].')" id="btn_Inventaire-'.$row['idDesignation'].'" class="btn btn-success">
                  <i class="glyphicon glyphicon-plus"></i> Ajouter
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