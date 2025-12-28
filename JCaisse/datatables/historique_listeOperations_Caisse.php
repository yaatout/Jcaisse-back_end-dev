<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   $dateJour = @$_POST['dateJour'];

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
           idutilisateur LIKE :idutilisateur  ) ";
      $searchArray = array( 
           'idutilisateur'=>"%$searchValue%"
      );
   }

   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT userPanier) AS allcount
   FROM
   (SELECT DISTINCT(p.iduser) as userPanier FROM `".$nomtablePagnet."` p  
   WHERE p.verrouiller=1  AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )
   UNION ALL
   SELECT DISTINCT(v.iduser) as userVersement FROM `".$nomtableVersement."` v
   WHERE ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) ='".$dateJour."') or (v.dateVersement  ='".$dateJour."'))
   ) AS a ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT userPanier) AS allcount
   FROM
   (SELECT DISTINCT(p.iduser) as userPanier FROM `".$nomtablePagnet."` p  
   WHERE p.verrouiller=1  AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )
   UNION ALL
   SELECT DISTINCT(v.iduser) as userVersement FROM `".$nomtableVersement."` v
   WHERE ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) ='".$dateJour."') or (v.dateVersement  ='".$dateJour."'))
   ) AS a WHERE 1 ".$searchQuery." ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   $stmt = $bdd->prepare("SELECT DISTINCT userPanier as idutilisateur
   FROM
   (SELECT DISTINCT(p.iduser) as userPanier FROM `".$nomtablePagnet."` p  
   WHERE p.verrouiller=1  AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )
   UNION ALL
   SELECT DISTINCT(v.iduser) as userVersement FROM `".$nomtableVersement."` v
   WHERE ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) ='".$dateJour."') or (v.dateVersement  ='".$dateJour."'))
   ) AS a WHERE 1 ".$searchQuery." LIMIT :limit,:offset ");
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }
   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $empRecords = $stmt->fetchAll();

   $data = array();

   foreach ($empRecords as $row) {

      $approvisionnement=0;
      $retrait=0;
      $ventes_Caisses=0;
      $depenses_Caisses=0;
      $versement_Clients_Caisse=0;
      $versement_Fournisseurs_Caisse=0;
      $ventes_Mobiles=0;
      $depenses_Mobiles=0;
      $versement_Clients_Mobile=0;
      $versement_Fournisseurs_Mobile=0;
      $bons_En_Espesces=0;
      $remise_Ventes=0;
      $mutuelle_Panier_A_Payer = 0;

      $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
      $stmtUser->bindValue(':idutilisateur', $row['idutilisateur'], PDO::PARAM_INT);
      $stmtUser->execute();
      $user = $stmtUser->fetch(); 
      $cols_personnel = strtoupper($user["prenom"]); 

 
      $stmtLigne= $bdd->prepare("SELECT p.idClient, l.prixtotal, l.classe, p.idCompte FROM `".$nomtableLigne."` l 
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE p.iduser=:idutilisateur AND (p.idCompte=1 || p.idCompte=0) AND p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  ");
      $stmtLigne->bindValue(':idutilisateur', $row['idutilisateur'], PDO::PARAM_INT);
      $stmtLigne->execute();
      $lignes = $stmtLigne->fetchAll();
      foreach ($lignes as $ligne) {
            if($ligne['idClient']!=0){
               //Somme des Bons en Especes
               if ($ligne['classe']==6){
                  $bons_En_Espesces = $bons_En_Espesces + $ligne['prixtotal'];
               }
               
            }
            else{
               //Somme des Ventes Produits
               if($ligne['classe']==0 || $ligne['classe']==1){
                  $ventes_Caisses = $ventes_Caisses + $ligne['prixtotal'];
               }
               else if ($ligne['classe']==2){
                  $depenses_Caisses = $depenses_Caisses + $ligne['prixtotal'];
               }
               //Somme des Approvisionnements
               else if ($ligne['classe']==5){
                  $approvisionnement = $approvisionnement + $ligne['prixtotal'];
               }
               //Somme des Retraits 
               else if ($ligne['classe']==7){
                  $retrait = $retrait + $ligne['prixtotal'];
               }
            }
      }

      // Total number of records without filtering
      $stmtVentes= $bdd->prepare("SELECT p.remise FROM `".$nomtablePagnet."` p
      WHERE p.iduser=:idutilisateur AND (p.idCompte=1 || p.idCompte=0) AND  p.idClient=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') ) ");
      $stmtVentes->bindValue(':idutilisateur', $row['idutilisateur'], PDO::PARAM_INT);
      $stmtVentes->execute();
      $ventes = $stmtVentes->fetchAll();
      foreach ($ventes as $vente) {
            $remise_Ventes = $remise_Ventes + $vente['remise'];
      }

      // Total number of records without filtering
      $stmtVersement= $bdd->prepare("SELECT * FROM `".$nomtableVersement."` v
      WHERE v.iduser=:idutilisateur AND (v.idCompte=1 || v.idCompte=0) AND ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))  ='".$dateJour."') or (v.dateVersement  ='".$dateJour."') )  ");
      $stmtVersement->bindValue(':idutilisateur', $row['idutilisateur'], PDO::PARAM_INT);
      $stmtVersement->execute();
      $versements = $stmtVersement->fetchAll();
      foreach ($versements as $versement) {
            if($versement['idClient']!=0){
               $versement_Clients_Caisse = $versement_Clients_Caisse + $versement['montant'];
            }
            else if($versement['idFournisseur']!=0){
               $versement_Fournisseurs_Caisse = $versement_Fournisseurs_Caisse + $versement['montant'];
            }
      }


      if (($_SESSION['mutuelle']==1) AND ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

         $stmtMutuelle= $bdd->prepare("SELECT * FROM `".$nomtableMutuellePagnet."` p
         WHERE p.iduser=:idutilisateur AND (p.idCompte=1 || p.idCompte=0)  AND p.verrouiller=1 AND p.idClient=0 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."'))  ");
         $stmtMutuelle->bindValue(':idutilisateur', $row['idutilisateur'], PDO::PARAM_INT);
         $stmtMutuelle->execute();
         $mutuelles = $stmtMutuelle->fetchAll();
         foreach ($mutuelles as $mutuelle) {
            $mutuelle_Panier_A_Payer = $mutuelle_Panier_A_Payer + $mutuelle['apayerPagnet'];
         }

      }

      $total = ($approvisionnement + $ventes_Caisses + $versement_Clients_Caisse + $mutuelle_Panier_A_Payer ) - 
      ($retrait + $depenses_Caisses + $versement_Fournisseurs_Caisse + $bons_En_Espesces + $remise_Ventes) ;

      $data[] = array(
         "personnel"=> $cols_personnel,
         "approvisionnement"=>  number_format(($approvisionnement * $_SESSION['devise']), 0, ',', ' '),
         "retrait"=>  number_format(($retrait * $_SESSION['devise']), 0, ',', ' '),
         "vente"=>  number_format((($ventes_Caisses + $mutuelle_Panier_A_Payer) * $_SESSION['devise']), 0, ',', ' '),
         "depense"=>  number_format(($depenses_Caisses * $_SESSION['devise']), 0, ',', ' '),
         "versementClient"=>  number_format(($versement_Clients_Caisse * $_SESSION['devise']), 0, ',', ' '),
         "versementFournisseur"=>  number_format(($versement_Fournisseurs_Caisse * $_SESSION['devise']), 0, ',', ' '),
         "bonEspeces"=>  number_format(($bons_En_Espesces * $_SESSION['devise']), 0, ',', ' '),
         "remise"=>  number_format(($remise_Ventes * $_SESSION['devise']), 0, ',', ' '),
         "total"=>  number_format(($total * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']
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