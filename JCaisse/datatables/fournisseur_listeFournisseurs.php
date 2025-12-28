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
           nomFournisseur LIKE :nomFournisseur OR
           adresseFournisseur LIKE :adresseFournisseur OR 
           banqueFournisseur LIKE :banqueFournisseur OR
           telephoneFournisseur LIKE :telephoneFournisseur ) ";
      $searchArray = array( 
           'nomFournisseur'=>"%$searchValue%",
           'adresseFournisseur'=>"%$searchValue%",
           'banqueFournisseur'=>"%$searchValue%",
           'telephoneFournisseur'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableFournisseur."` ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableFournisseur."` WHERE 1 ".$searchQuery." ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableFournisseur."` WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }

   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $fournisseurs = $stmt->fetchAll();

   $data = array();
   $i = 0;

   foreach ($fournisseurs as $fournisseur) {

      $total_Bons = 0;
      $total_Versements = 0;

      //Somme des Pagnets Bons du Client
      $stmtBL = $bdd->prepare("SELECT * FROM `".$nomtableBl."` WHERE idFournisseur=:idFournisseur AND commande!=1 ");
      $stmtBL->bindValue(':idFournisseur', $fournisseur['idFournisseur'], PDO::PARAM_INT);
      $stmtBL->execute();
      $bons = $stmtBL->fetchAll();
      foreach ($bons as $bon) {
            $total_Bons = $total_Bons + $bon['montantBl'];

            // $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            // $stmtBL->execute(array(
            //     ':idBl' => $bon['idBl']
            // ));
            // $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            // if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
            //    $montant=0;
            //    foreach ($bls as $bl) {
            //       $montant = $montant + ($bl['prixSession'] * $bl['quantiteStockinitial']);
            //    }
            // }
            // else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
            //    $montant=0;
            //    foreach ($bls as $bl) {
            //       $montant = $montant + ($bl['prixachat'] * $bl['quantiteStockinitial']);
            //    }
            // }
            // else{
            //    $montant=0;
            //    foreach ($bls as $bl) {
            //        $montant = $montant + ($bl['prixachat'] * $bl['totalArticleStock']);
            //    }
            // }
            
            // $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            // SET montantTotal=:montantTotal  WHERE idBl = :idBl ");
            // $stmtBL_Modifier->execute(array(
            //     ':idBl' => $bon['idBl'],
            //     ':montantTotal' => $montant
            // )); 
      }

      //Somme des Versements du Client
      $stmtVersement = $bdd->prepare("SELECT * FROM `".$nomtableVersement."` WHERE idFournisseur=:idFournisseur AND  idClient=0  ");
      $stmtVersement->bindValue(':idFournisseur', $fournisseur['idFournisseur'], PDO::PARAM_INT);
      $stmtVersement->execute();
      $versements = $stmtVersement->fetchAll();
      foreach ($versements as $versement) {
            $total_Versements = $total_Versements + $versement['montant'];
      }

      //Montant a verser du Client
      $solde = $total_Bons - $total_Versements;

      //$solde = $fournisseur['solde'];

      //Colonne Montant a verser
      if($solde>=0){
         $cols_montant = '<td><span class="alert-danger">'.number_format($solde, 0, ',', ' ').' FCFA <a href=detailsFournisseur.php?iDS='.$fournisseur["idFournisseur"].'>Details </a></span></td>';
      }
      else{
         $cols_montant =  '<td> <span class="alert-success">'.number_format($solde, 0, ',', ' ').' FCFA <a href=detailsFournisseur.php?iDS='.$fournisseur["idFournisseur"].'>Details </a></span></td>';
      }

      if($total_Bons>0 || $total_Versements>0){
         $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Fournisseur('.$fournisseur["idFournisseur"].')"  /></a>'; 
      }
      else{
         $cols_operations = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Fournisseur('.$fournisseur["idFournisseur"].')"  /></a>&nbsp;
           <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Fournisseur('.$fournisseur["idFournisseur"].')"  /></a>'; 
      }


      $data[] = array(
         "idFournisseur"=>$fournisseur['idFournisseur'],
         "nomFournisseur"=>$fournisseur['nomFournisseur'],
         "adresseFournisseur"=>$fournisseur['adresseFournisseur'],
         "telephoneFournisseur"=>$fournisseur['telephoneFournisseur'],
         "banqueFournisseur"=>$fournisseur['banqueFournisseur'],
         "numBanqueFournisseur"=>$fournisseur['numBanqueFournisseur'],
         "montant"=> $cols_montant,
         "operations"=>$cols_operations,
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