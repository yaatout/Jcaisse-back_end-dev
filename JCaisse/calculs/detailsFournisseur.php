<?php
session_start();

if(!$_SESSION['iduser']){

header('Location:../index.php');

}

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php');

try{

    $operation=@htmlspecialchars($_POST["operation"]);
    $idBon=@$_POST["idBon"];
    $idVersement=@htmlspecialchars(trim($_POST['idVersement']));

    $idFournisseur=@$_POST["idFournisseur"];
    $fournisseur=@$_POST["fournisseur"];
    $montant=@$_POST["montant"];
    $paiement=@htmlspecialchars($_POST['description']);
    $dateVersement=@htmlspecialchars($_POST['dateVersement']);
    $compte=@$_POST["compte"];

    $numero=@htmlspecialchars(trim($_POST['numero']));
    $description=@htmlspecialchars(trim($_POST['description']));
    $dateBon=@htmlspecialchars($_POST['dateBon']);
    $dateEcheance=@htmlspecialchars($_POST['dateEcheance']);

    $dateDebut=@$_POST["dateDebut"]; 
    $dateFin=@$_POST["dateFin"];
    $idCompte=@$_POST["idCompte"];

    /**Debut Details Bon Livraison et Commande**/
        if($operation=='details_Bon'){

            $stmtBon = $bdd->prepare("SELECT  * FROM `".$nomtableBl."` 
            WHERE idBl = :idBl ");
            $stmtBon->execute(array(
                ':idBl' => $idBon
            ));
            $bon = $stmtBon->fetch(PDO::FETCH_ASSOC);

            $stmtFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` 
            WHERE idFournisseur <> :idFournisseur ");
            $stmtFournisseur->execute(array(
                ':idFournisseur' => $bon['idFournisseur']
            ));
            $fournisseurs = $stmtFournisseur->fetchAll(PDO::FETCH_ASSOC);
            $data=array();
            foreach($fournisseurs as $fournisseur){
                $rows = array();
                $rows[] = $fournisseur['idFournisseur'];	
                $rows[] = $fournisseur['nomFournisseur'];	
                $data[] = $rows; 
            }

            $rows = array();
            $rows[] = $bon['idBl'];	
            $rows[] = $bon['numeroBl'];
            $rows[] = $bon['dateBl'];
            $rows[] = $bon['montantBl'];
            $rows[] = $bon['description'];
            $rows[] = $data;

            echo json_encode($rows);
        }
    /**Fin Details Bon Livraison et Commande**/

    /**Debut Ajouter Bon Commande**/
        if($operation=='ajouter_Commande'){

            $stmBon_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableBl."` 
            (idFournisseur,dateBl,heureBl,montantBl, iduser,commande)
            VALUES (:idFournisseur, :dateBl, :heureBl, :montantBl, :iduser, :commande)");
            $stmBon_Ajouter->execute(array(
                ':idFournisseur' => $idFournisseur,
                ':dateBl' => $dateString, 
                ':heureBl' => $heureString, 
                ':montantBl' => 0,
                ':commande' => 1,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idBon = $bdd->lastInsertId();

            $rows = array();
            $rows[] = $last_idBon;
            $rows[] = $dateString;	
            $rows[] = $heureString;
            $rows[] = 0;	

            echo json_encode($rows);
        }
    /**Fin Ajouter Bon Livraison***/

    /**Debut Ajouter Bon Livraison**/
        if($operation=='ajouter_Livraison'){

            $stmBon_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableBl."` 
            (idFournisseur,dateBl,heureBl,montantBl,iduser)
            VALUES (:idFournisseur, :dateBl, :heureBl, :montantBl, :iduser)");
            $stmBon_Ajouter->execute(array(
                ':idFournisseur' => $idFournisseur,
                ':dateBl' => $dateString,
                ':heureBl' => $heureString, 
                ':montantBl' => 0,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idBon = $bdd->lastInsertId();

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $last_idBon;
            $rows[] = $dateString;	
            $rows[] = $heureString;
            $rows[] = 0;
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Ajouter Bon Livraison***/

    /**Debut Terminer Bon Livraison et Bon Commande**/
        if($operation=='terminer_Bon'){

            $stmtBon_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` SET numeroBl =:numeroBl,
            montantBl=:montantBl, description =:description WHERE idBl = :idBl ");
            $stmtBon_Modifier->execute(array(
                ':idBl' => $idBon,
                ':numeroBl' => $numero,
                ':montantBl' => $montant,
                ':description' => $description
            ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $idBon;	
            $rows[] = $montant;	

            echo json_encode($rows);
          }
    /**Fin Terminer Bon Livraison et Bon Commande**/ 

    /**Debut Modifier Bon Livraison et Bon Commande**/
        if($operation=='modifier_Bon'){

            $stmtBon_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` SET numeroBl =:numeroBl, dateBl =:dateBl,
            montantBl=:montantBl, description =:description, dateBl =:dateBl, dateEcheance =:dateEcheance  WHERE idBl = :idBl ");
            $stmtBon_Modifier->execute(array(
                ':idBl' => $idBon,
                ':numeroBl' => $numero,
                ':dateBl' => $dateBon,
                ':dateEcheance' => $dateEcheance,
                ':montantBl' => $montant,
                ':description' => $description
            ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $idBon;	
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Modifier Bon Livraison et Bon Commande**/ 

    /**Debut Transferer Bon Livraison et Bon Commande**/
        if($operation=='transferer_Bon'){

            $stmtBon_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` SET idFournisseur =:idFournisseur
            WHERE idBl = :idBl ");
            $stmtBon_Modifier->execute(array(
                ':idBl' => $idBon,
                ':idFournisseur' => $fournisseur
            ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $idBon;	
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Transferer Bon Livraison et Bon Commande**/ 

    /**Debut Supprimer Bon Livraison et Bon Commande**/
        if($operation=='supprimer_Bon'){

            $stmtStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtStock_Supprimer->execute(array(':idBl' => $idBon ));

            $stmtBon_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableBl."` WHERE idBl = :idBl ");
            $stmtBon_Supprimer->execute(array(':idBl' => $idBon ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $idBon;	
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Supprimer Bon Livraison et Bon Commande**/ 

    /**Debut Lister les Stock d'un Bon de Livraison **/
        if($operation=='details_Livraison'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtStock->execute(array(
                ':idBl' => $idBon
            ));
            $lignes = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            
            if($lignes==true){
                foreach($lignes as $stock){

                    $rows = array();
                    $rows[] = $stock['idBl'];	
                    $rows[] = $stock['designation'];	
                    $rows[] = $stock['quantiteStockinitial'];

                    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                        $rows[] = $stock['forme'];
                        $rows[] = $stock['prixSession'];
                        $rows[] = $stock['prixPublic'];
                        $rows[] = $stock['prixSession'] * $stock['quantiteStockinitial'];
                        $rows[] = $stock['dateStockage'];
                        $rows[] = $stock['quantiteStockCourant'];
                    }
                    else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                        $rows[] = $stock['uniteStock'];
                        $rows[] = $stock['prixachat'];
                        $rows[] = $stock['prixachat'] * $stock['totalArticleStock'];
                        $rows[] = $stock['dateStockage'];
                        $rows[] = $stock['prixuniteStock'];
                        $rows[] = $stock['prixunitaire'];
                    }
                    else{
                        $rows[] = $stock['uniteStock'].' [x '.$stock['nbreArticleUniteStock'].']';
                        $rows[] = $stock['prixachat'];
                        $rows[] = $stock['prixachat'] * $stock['totalArticleStock'];
                        $rows[] = $stock['dateStockage'];
                        $rows[] = $stock['quantiteStockCourant'];
                        $rows[] = $stock['prixunitaire'];
                        $rows[] = $stock['prixuniteStock'];
                    }

                    $rows[] = $stock['dateExpiration'];
                    $data[] = $rows;
                
                }
            }
            else{
                $data[] = 0;
            }

            echo json_encode($data);
        }
    /**Fin Lister les Stock d'un Bon de Livraison ***/

    /**Debut Lister les Stock d'un Bon de Commande **/
        if($operation=='details_Commande'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtStock->execute(array(
                ':idBl' => $idBon
            ));
            $lignes = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            
            if($lignes==true){
                foreach($lignes as $stock){

                    $rows = array();
                    $rows[] = $stock['idBl'];		
                    $rows[] = $stock['designation'];	
                    $rows[] = $stock['quantiteCommande'];

                    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                        $rows[] = $stock['forme'];
                        $rows[] = $stock['prixSession'];
                        $rows[] = $stock['prixPublic'];
                        $rows[] = $stock['prixSession'] * $stock['totalArticleStock'];
                        $rows[] = $stock['dateStockage'];
                    }
                    else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                        $rows[] = $stock['uniteStock'];
                        $rows[] = $stock['prixachat'];
                        $rows[] = $stock['prixuniteStock'];
                        $rows[] = $stock['prixachat'] * $stock['totalArticleStock'];
                        $rows[] = $stock['dateStockage'];
                    }
                    else{
                        $rows[] = $stock['uniteStock'].' [x '.$stock['nbreArticleUniteStock'].']';
                        $rows[] = $stock['prixachat'];
                        $rows[] = $stock['prixachat'] * $stock['totalArticleStock'];
                        $rows[] = $stock['dateStockage'];
                        $rows[] = $stock['prixunitaire'];
                        $rows[] = $stock['prixuniteStock'];
                    }
                    
                    $rows[] = $stock['dateExpiration'];

                    $data[] = $rows;
                
                }
            }
            else{
                $data[] = 0;
            }

            echo json_encode($data);
        }
    /**Fin Lister les Stock d'un Bon de Commande ***/

    /**Debut Details Versement**/
        if($operation=='details_Versement'){

            $stmtVersement = $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` 
            WHERE idVersement = :idVersement ");
            $stmtVersement->execute(array(
                ':idVersement' => $idVersement
            ));
            $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $versement['idVersement'];
            $rows[] = $versement['dateVersement'];
            $rows[] = $versement['montant'];
            $rows[] = $versement['paiement'];

            echo json_encode($rows);
        }
    /**Fin Details Versement**/

    /**Debut Ajouter Versement**/
        if($operation=='ajouter_Versement'){

            $stmVersement_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableVersement."` 
            (idFournisseur,montant,dateVersement,heureVersement,idCompte, iduser) 
            VALUES (:idFournisseur, :montant, :dateVersement, :heureVersement, :idCompte, :iduser)");
            $stmVersement_Ajouter->execute(array(
                ':idFournisseur' => $idFournisseur,
                ':montant' => 0, 
                ':dateVersement' => $dateString, 
                ':heureVersement' => $heureString,
                ':idCompte' => 0,
                ':iduser' => $_SESSION['iduser'],
            ));
            $last_idVersement = $bdd->lastInsertId();

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $datas = array();
            $datas[] = $last_idVersement;
            $datas[] = $dateString;	
            $datas[] = $heureString;
            $datas[] = 0;	
            $datas[] = $montant;

            $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
            WHERE idCompte<>2 and idCompte<>3 ORDER BY idCompte ");
            $stmtCompte->execute();
            $comptes = $stmtCompte->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($comptes as $compte){
                $rows = array();
                $rows[] = $compte['idCompte'];	
                $rows[] = $compte['nomCompte'];	
                $data[] = $rows; 
            }
            $datas[] = $data;

            echo json_encode($datas);
        }
    /**Fin Ajouter Versement***/

    /**Debut Terminer Versement**/
        if($operation=='terminer_Versement'){

            try {

                $stmVersement_Modifier = $bdd->prepare("UPDATE `".$nomtableVersement."` SET  
                paiement =:paiement, montant=:montant, idCompte=:idCompte  WHERE idVersement =:idVersement ");
                $stmVersement_Modifier->execute(array(
                    ':idVersement' => $idVersement,
                    ':paiement' => $description, 
                    ':montant' => $montant, 
                    ':idCompte' => $idCompte
                ));

                if($_SESSION['compte']==1){
                    depot_Compte_Versement($idVersement);
                } 

                mise_A_jour_Fournisseur($idFournisseur);
                $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            } catch (\Throwable $th) {
                //throw $th;
            }
            
            $rows = array();
            $rows[] = $idVersement;	
            $rows[] = $montant;
            echo json_encode($rows); 

        }
    /**Fin Terminer Versement**/

    /**Debut Modifier Versement**/
        if($operation=='modifier_Versement'){

            if($_SESSION['compte']==1){
                update_Compte_Versement($idVersement,$idCompte,$montant);
            }

            $stmtVersement_Modifier = $bdd->prepare("UPDATE `".$nomtableVersement."` SET dateVersement =:dateVersement,
            montant=:montant, paiement =:paiement, idCompte=:idCompte  WHERE idVersement = :idVersement ");
            $stmtVersement_Modifier->execute(array(
                ':idVersement' => $idVersement,
                ':paiement' => $paiement,
                ':dateVersement' => $dateVersement,
                ':montant' => $montant,
                ':idCompte' => $idCompte
            ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $idVersement;	
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Modifier Versement**/ 

    /**Debut Supprimer Versement**/
        if($operation=='supprimer_Versement'){

            if($_SESSION['compte']==1){
                retrait_Compte_Versement($idVersement);
            } 

            $stmtVersement_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableVersement."` WHERE idVersement = :idVersement ");
            $stmtVersement_Supprimer->execute(array(':idVersement' => $idVersement ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $idVersement;	
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Supprimer Versement**/ 

    /**Debut Valider Commande**/
        if($operation=='valider_Commande'){

            $stmtStock = $bdd->prepare("SELECT * FROM `".$nomtableStock."` WHERE idBl=:idBl ");
            $stmtStock->execute(array(
                ':idBl' => $idBon
            ));
            $stocks = $stmtStock->fetchAll(); 

            foreach ($stocks as $stock) {

                $stmStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockinitial=quantiteCommande, quantiteStockCourant=totalArticleStock
                WHERE idStock = :idStock ");
                $stmStock_Modifier->execute(array(
                    ':idStock' => $stock['idStock']
                ));

            }

            $stmtBon_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` SET commande=0, montantBl=montantTotal WHERE idBl = :idBl ");
            $stmtBon_Modifier->execute(array(
                ':idBl' => $idBon
            ));

            mise_A_jour_Fournisseur($idFournisseur);
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);

            $rows = array();
            $rows[] = $montant;	

            echo json_encode($rows);
        }
    /**Fin Valider Commande**/
    
    /**Debut calculer la valeur du Montant du Client**/
        if($operation=='calcul_Montant'){
            $montant = calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin);
            echo json_encode($montant);
        }
    /**Fin calculer la valeur du Montant du Client**/

    /**Debut Lister les Stock d'un Bon de Commande **/
        if($operation=='detailsBon'){

            $stmtBon = $bdd->prepare("SELECT  * FROM `".$nomtableBl."` 
            WHERE idBl = :idBl ");
            $stmtBon->execute(array(
                ':idBl' => $idBon
            ));
            $bon = $stmtBon->fetch(PDO::FETCH_ASSOC);
            $datas = array();
            if($bon){
                $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
                WHERE idutilisateur=:iduser ");
                $stmtUser->execute(array(
                    ':iduser' => $bon['iduser']
                ));
                $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

                $datas[] = $bon['idBl'];
                if($bon['idFournisseur']!=0){
                    $stmtFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` 
                    WHERE idFournisseur = :idFournisseur ");
                    $stmtFournisseur->execute(array(
                        ':idFournisseur' => $bon['idFournisseur']
                    ));
                    $fournisseur = $stmtFournisseur->fetch(PDO::FETCH_ASSOC);
                    $datas[] = strtoupper($fournisseur['nomFournisseur']).'<>'.$fournisseur['adresseFournisseur'].'<>'.$fournisseur['telephoneFournisseur'];
                }
                else{ $datas[] = 0; }
                $datas[] = $bon['dateBl'];
                $datas[] = $bon['heureBl'];
                $datas[] = number_format(($bon['montantTotal'] * $_SESSION['devise']), 0, ',', ' ');

                $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ORDER BY idStock DESC ");
                $stmtStock->execute(array(
                    ':idBl' => $bon['idBl']
                ));
                $lignes = $stmtStock->fetchAll(PDO::FETCH_ASSOC);
                $data=array();
                if($lignes==true){
                    foreach($lignes as $stock){
    
                        $rows = array();
                        $rows[] = $stock['idBl'];		
                        $rows[] = $stock['designation'];	
                        $rows[] = $stock['quantiteCommande'];
    
                        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                            // $rows[] = $stock['forme'];
                            // $rows[] = $stock['prixSession'];
                            // $rows[] = $stock['prixPublic'];
                            // $rows[] = $stock['prixSession'] * $stock['totalArticleStock'];
                            // $rows[] = $stock['dateStockage'];
                        }
                        else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                            $rows[] = $stock['uniteStock'];
                            $rows[] = $stock['prixachat'];
                            $rows[] = $stock['prixachat'] * $stock['quantiteCommande'];
                        }
                        else{
                            $rows[] = $stock['uniteStock'];
                            if($stock['uniteStock']=='Article' || $stock['uniteStock']=='article'){
                                $rows[] = $stock['prixachat'];
                                $rows[] = $stock['prixachat'] * $stock['quantiteCommande'];
                            }
                            else{
                                $rows[] = $stock['prixachat'] * $stock['nbreArticleUniteStock'];
                                $rows[] = $stock['prixachat'] * $stock['nbreArticleUniteStock'] * $stock['quantiteCommande'];
                            }
                        }
    
                        $data[] = $rows;
                    
                    }
                }
                else{
                    $data[] = 0;
                }

                $datas[] = $data;
                $datas[] = strtoupper($user['prenom']);
            }

            echo json_encode($datas);

        }
    /**Fin Lister les Stock d'un Bon de Commande ***/
   
}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}

    function mise_A_jour_Fournisseur($idFournisseur){
        require('../connectionPDO.php');
        require('../declarationVariables.php');

        $stmtVersement= $bdd->prepare("SELECT  SUM(montant) as total FROM `".$nomtableVersement."`  WHERE idFournisseur = :idFournisseur ");
        $stmtVersement->execute(array(
            ':idFournisseur' => $idFournisseur
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

        $stmtBl= $bdd->prepare("SELECT  SUM(montantBl) as total FROM `".$nomtableBl."`  WHERE idFournisseur = :idFournisseur AND commande=0 ");
        $stmtBl->execute(array(
            ':idFournisseur' => $idFournisseur
        ));
        $bl = $stmtBl->fetch(PDO::FETCH_ASSOC);

        $solde = $bl['total'] - $versement['total'];
        $stmtFournisseur = $bdd->prepare("UPDATE `".$nomtableFournisseur."` SET solde=:solde WHERE idFournisseur = :idFournisseur ");
        $stmtFournisseur->execute(array(
            ':idFournisseur' => $idFournisseur,
            ':solde' => $solde
        ));
        $fournisseur = $stmtFournisseur->fetch(PDO::FETCH_ASSOC);
    }

    function calcul_Montant_Fournisseur($idFournisseur,$dateDebut,$dateFin){
        require('../connectionPDO.php');
        require('../declarationVariables.php');

        $stmtVersement= $bdd->prepare("SELECT  SUM(montant) as total FROM `".$nomtableVersement."` v  WHERE v.idFournisseur = :idFournisseur
        AND ( (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN :dateDebut AND :dateFin) or (v.dateVersement BETWEEN :dateDebut AND :dateFin) )");
        $stmtVersement->execute(array(
            ':idFournisseur' => $idFournisseur,
            ':dateDebut' => $dateDebut,
            ':dateFin' => $dateFin
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

        $stmtBl= $bdd->prepare("SELECT  SUM(montantBl) as total FROM `".$nomtableBl."` b WHERE b.idFournisseur = :idFournisseur AND b.commande=0 
        AND ( (CONCAT(CONCAT(SUBSTR(b.dateBl,7, 10),'',SUBSTR(b.dateBl,3, 4)),'',SUBSTR(b.dateBl,1, 2)) BETWEEN :dateDebut AND :dateFin)  or (b.dateBl BETWEEN :dateDebut AND :dateFin) ) ");
        $stmtBl->execute(array(
            ':idFournisseur' => $idFournisseur,
            ':dateDebut' => $dateDebut,
            ':dateFin' => $dateFin
        ));
        $bl = $stmtBl->fetch(PDO::FETCH_ASSOC);
        
        $solde = $bl['total'] - $versement['total'];
        $montant=number_format($solde, 2, ',', ' ').'<>'.number_format($bl['total'], 2, ',', ' ').'<>'.number_format($versement['total'], 2, ',', ' ');
        return $montant;
    }

    function depot_Compte_Versement($idVersement){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmtVersement= $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement = :idVersement ");
        $stmtVersement->execute(array(
            ':idVersement' => $idVersement
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
        if($versement){
            $stmCompte_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableComptemouvement."` 
            (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idUser)
            VALUES (:montant, :operation, :idCompte, :description, :dateOperation, :dateSaisie, :idVersement, :idUser)");
            $stmCompte_Ajouter->execute(array(
                ':montant' => $versement['montant'],
                ':operation' => 'retrait',
                ':idCompte' => $versement['idCompte'],
                ':description' => 'Versement Fournisseur', 
                ':dateOperation' => $dateHeures, 
                ':dateSaisie' => $dateHeures,
                ':idVersement' => $idVersement,
                ':idUser' => $_SESSION['iduser'],
            ));

            $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :montant 
            WHERE idCompte = :idCompte ");
            $stmCompte_Modifier->execute(array(
                ':idCompte' => $versement['idCompte'],
                ':montant' => $versement['montant'],
            ));
        }
    }

    function retrait_Compte_Versement($idVersement){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmtVersement= $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement = :idVersement ");
        $stmtVersement->execute(array(
            ':idVersement' => $idVersement
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
        if($versement){
            $stmCompte_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableComptemouvement."` WHERE idVersement = :idVersement ");
            $stmCompte_Supprimer->execute(array(':idVersement' => $idVersement ));

            $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :montant 
            WHERE idCompte = :idCompte ");
            $stmCompte_Modifier->execute(array(
                ':idCompte' => $versement['idCompte'],
                ':montant' => $versement['montant'],
            ));
        }
    }

    function update_Compte_Versement($idVersement,$idCompte,$montant){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmtVersement= $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement = :idVersement ");
        $stmtVersement->execute(array(
            ':idVersement' => $idVersement
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
        if($versement){
            $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :montant 
            WHERE idCompte = :idCompte ");
            $stmCompte_Modifier->execute(array(
                ':idCompte' => $versement['idCompte'],
                ':montant' => $versement['montant'],
            ));
        }

        $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableComptemouvement."` SET montant =:montant, idCompte =:idCompte
        WHERE idVersement = :idVersement ");
        $stmCompte_Modifier->execute(array(
            ':idCompte' => $idCompte,
            ':montant' => $montant,
            ':idVersement' => $idVersement,
        ));

        $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :montant 
        WHERE idCompte = :idCompte ");
        $stmCompte_Modifier->execute(array(
            ':idCompte' => $idCompte,
            ':montant' => $montant,
        ));
    }

    function apres_Update_Compte_Versement($idVersement){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmtVersement= $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement = :idVersement ");
        $stmtVersement->execute(array(
            ':idVersement' => $idVersement
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
        if($versement){

            $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableComptemouvement."` SET montant =:montant, idCompte =:idCompte
            WHERE idVersement = :idVersement ");
            $stmCompte_Modifier->execute(array(
                ':idCompte' => $versement['idCompte'],
                ':montant' => $versement['montant'],
                ':idVersement' => $idVersement,
            ));

            $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :montant 
            WHERE idCompte = :idCompte ");
            $stmCompte_Modifier->execute(array(
                ':idCompte' => $versement['idCompte'],
                ':montant' => $versement['montant'],
            ));
        }
    }

?>
