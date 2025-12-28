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
           nomCompte LIKE :nomCompte OR
           typeCompte LIKE :typeCompte OR 
           numeroCompte LIKE :numeroCompte) ";
      $searchArray = array( 
           'nomCompte'=>"%$searchValue%",
           'typeCompte'=>"%$searchValue%",
           'numeroCompte'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableCompte."` AND idCompte<>1000 ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableCompte."` WHERE 1 ".$searchQuery." AND idCompte<>1000 ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableCompte."` WHERE 1 ".$searchQuery." AND idCompte<>1000 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }

   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $comptes = $stmt->fetchAll();

   $data = array();
   $i = 0;

   foreach ($comptes as $compte) {

      if($_SESSION['idBoutique']==206){
         $stmtMouvement = $bdd->prepare("SELECT * FROM `".$nomtableComptemouvement."` WHERE idCompte=:idCompte AND annuler<>1 ");
         $stmtMouvement->bindValue(':idCompte', $compte['idCompte'], PDO::PARAM_INT);
         $stmtMouvement->execute();
         $mouvements = $stmtMouvement->fetchAll();
         $retrait=0; $depot=0;
         foreach ($mouvements as $mouvement) {
            if($mouvement['operation']=='retrait'){
               $retrait = $retrait + $mouvement['montant'];
            }
            else {
               $depot = $depot + $mouvement['montant'];
            }
         }

         $montant = $depot - $retrait ;
         $stmtBons = $bdd->prepare("UPDATE  `".$nomtableCompte."` SET montantCompte=:montantCompte WHERE idCompte=:idCompte ");
         $stmtBons->bindValue(':idCompte', $compte['idCompte'], PDO::PARAM_INT);
         $stmtBons->bindValue(':montantCompte', $montant);
         $stmtBons->execute();
      }
      else {
         $montant = $compte['montantCompte'];
      }

      //Montant a verser du Client
      
      //Colonne Montant a verser
      if($montant>=0){
         $cols_montant = '<td><span class="alert-success">'.number_format($montant, 0, ',', ' ').' FCFA <a href=detailsCompte.php?c='.$compte["idCompte"].'>Details </a></span></td>';
      }
      else{
         $cols_montant =  '<td> <span class="alert-danger">'.number_format($montant, 0, ',', ' ').' FCFA <a href=detailsCompte.php?c='.$compte["idCompte"].'>Details </a></span></td>';
      }

      if($compte['montantCompte']!=null){
         $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Compte('.$compte["idCompte"].')"  /></a>'; 
      }
      else{
         $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Compte('.$compte["idCompte"].')"  /></a>&nbsp;
           <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Compte('.$compte["idCompte"].')"  /></a>'; 
      }

      if($compte['image']!=null && $compte['image']!=' '){ 
         $format=substr($compte['image'], -3);
         if($format=='pdf'){ 
            $cols_image= '<img class="btn btn-xs" src="./images/pdf.png" align="middle" alt="apperçu" width="30" height="25" data-toggle="modal" data-target="#imageNvCompte'.$compte['idCompte'].'" onclick="showImageCompte('.$compte['idCompte'].')" />
             <input style="display:none;" data-image="'.$compte['nomCompte'].'"  id="imageCompte'.$compte['idCompte'].'"  value="'.$compte['image'].'" />';
         }
             else { 
               $cols_image= '<img class="btn btn-xs" src="./images/img.png" align="middle" alt="apperçu" width="30" height="25" data-toggle="modal" data-target="#imageNvCompte'.$compte['idCompte'].'" onclick="showImageCompte('.$compte['idCompte'].')" />
             <input style="display:none;" data-image="'.$compte['nomCompte'].'" id="imageCompte'.$compte['idCompte'].'"  value="'.$compte['image'].'" />';
         } 
     }
     else{ 
      $cols_image= '<img class="btn btn-xs" src="./images/upload.png" align="middle" alt="apperçu" width="30" height="25" data-toggle="modal" data-target="#imageNvCompte'.$compte['idCompte'].'" onclick="showImageCompte('.$compte['idCompte'].')" />
         <input style="display:none;" data-image="'.$compte['nomCompte'].'" id="imageCompte'.$compte['idCompte'].'"  value="'.$compte['image'].'" />';
     }

      $data[] = array(
         "idCompte"=>$compte['idCompte'],
         "nomCompte"=>$compte['nomCompte'],
         "typeCompte"=>$compte['typeCompte'],
         "numeroCompte"=>$compte['numeroCompte'],
         "montantCompte"=> $cols_montant,
         "operations"=>$cols_operations,
         "image"=>$cols_image,
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