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
    $jourAnnee=@htmlspecialchars($_POST["dateJour"]);
    $idPagnet=@$_POST["idPagnet"];

    /**Debut calculer les Operations du Jour**/
        if($operation=='details'){

            $bons_Clients = 0;    $total_bons_Clients = 0;   
            $ventes_Produits = 0;   $ventes_Mobiles = 0;
            $ventes_Services = 0;
            $depenses = 0;
            $approvisionnement = 0;
            $bons_En_Espesces = 0;
            $retrait = 0;
            $bons_Initialisation = 0;
            $remise_Ventes = 0;             
            $versement_Clients_Caisse = 0;  $versement_Clients_Mobile = 0;    $total_versement_Clients = 0;
            $versement_Fournisseurs = 0;    $versement_Mutuelles = 0;
            $bl_Fournisseur = 0;
            $mutuelle_Panier_A_Payer = 0;   $mutuelle_Bon_A_Payer = 0;     $mutuelle_Mutuelle_A_Payer = 0; 

            // Total number of records without filtering
            $stmtLigne= $bdd->prepare("SELECT p.idClient, l.prixtotal, l.classe, p.idCompte FROM `".$nomtableLigne."` l 
                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
            WHERE p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') )  ");
            $stmtLigne->execute();
            $lignes = $stmtLigne->fetchAll();
            foreach ($lignes as $ligne) {
                if($ligne['idClient']!=0){
                    if($ligne['classe']==0 || $ligne['classe']==1){
                        $bons_Clients = $bons_Clients + $ligne['prixtotal'];
                    }
                    //Somme des Bons en Especes
                    else if ($ligne['classe']==6){
                        $bons_En_Espesces = $bons_En_Espesces + $ligne['prixtotal'];
                    }
                    //Somme des Bon Initialisation 
                    else if ($ligne['classe']==9){
                        $bons_Initialisation = $bons_Initialisation + $ligne['prixtotal'];
                    }
                }
                else{
                    //Somme des Ventes Produits
                    if($ligne['classe']==0){
                        if($ligne['idCompte']<>0 && $ligne['idCompte']<>1 && $ligne['idCompte']<>2 && $ligne['idCompte']<>3 && $ligne['idCompte']<>1000){
                            $ventes_Mobiles = $ventes_Mobiles + $ligne['prixtotal'];
                        }
                        else{
                            $ventes_Produits = $ventes_Produits + $ligne['prixtotal'];
                        }
                    }
                    else if ($ligne['classe']==1){
                        $ventes_Services = $ventes_Services + $ligne['prixtotal'];
                    }
                    //Somme des Depenses
                    else if ($ligne['classe']==2){
                        $depenses = $depenses + $ligne['prixtotal'];
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
            /* 

            */

            // Total number of records without filtering
            $stmtVentes= $bdd->prepare("SELECT p.remise FROM `".$nomtablePagnet."` p
            WHERE p.idClient=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') ) ");
            $stmtVentes->execute();
            $ventes = $stmtVentes->fetchAll();
            foreach ($ventes as $vente) {
                $remise_Ventes = $remise_Ventes + $vente['remise'];
            }

            // Total number of records without filtering
            $stmtVersement= $bdd->prepare("SELECT * FROM `".$nomtableVersement."` v
            WHERE ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))  ='".$jourAnnee."') or (v.dateVersement  ='".$jourAnnee."') )  ");
            $stmtVersement->execute();
            $versements = $stmtVersement->fetchAll();
            foreach ($versements as $versement) {
                if($versement['idClient']!=0){
                    if($versement['idCompte']<>0 && $versement['idCompte']<>1 && $versement['idCompte']<>2 && $versement['idCompte']<>3 && $versement['idCompte']<>1000){
                        if($versement['montant']!=0){
                            $versement_Clients_Mobile = $versement_Clients_Mobile + $versement['montant'];
                        }
                        else {
                            $versement_Clients_Mobile = $versement_Clients_Mobile + $versement['montantAvoir'];
                        }
                    }
                    else{
                        if($versement['montant']!=0){
                            $versement_Clients_Caisse = $versement_Clients_Caisse + $versement['montant'];
                        }
                        else {
                            $versement_Clients_Caisse = $versement_Clients_Caisse + $versement['montantAvoir'];
                        }
                    }
                }
                else if($versement['idFournisseur']!=0){  
                    $versement_Fournisseurs = $versement_Fournisseurs + $versement['montant'];
                }
                if (($_SESSION['mutuelle']==1) AND ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                    if($versement['idMutuelle']!=0){  
                        $versement_Mutuelles = $versement_Mutuelles + $versement['montant'];
                    }
                }
            }

            if (($_SESSION['mutuelle']==1) AND ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

                $stmtMutuelle= $bdd->prepare("SELECT * FROM `".$nomtableMutuellePagnet."` p
                WHERE p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."'))  ");
                $stmtMutuelle->execute();
                $mutuelles = $stmtMutuelle->fetchAll();
                foreach ($mutuelles as $mutuelle) {
                    if($mutuelle['idClient']!=0){
                        $mutuelle_Bon_A_Payer = $mutuelle_Bon_A_Payer + $mutuelle['apayerPagnet'];
                    }
                    else {
                        $mutuelle_Panier_A_Payer = $mutuelle_Panier_A_Payer + $mutuelle['apayerPagnet'];
                    }
                    $mutuelle_Mutuelle_A_Payer = $mutuelle_Mutuelle_A_Payer + $mutuelle['apayerMutuelle'];
                }

            }

            $versement_Clients = ($versement_Clients_Caisse + $versement_Clients_Mobile);
            $total_Operations= ($approvisionnement + $ventes_Produits + $ventes_Mobiles + $ventes_Services + $versement_Clients + $mutuelle_Panier_A_Payer) - 
            ($retrait + $depenses + $bons_En_Espesces + $versement_Fournisseurs + $remise_Ventes);

            $rows = array();
            $rows[] = number_format(($approvisionnement * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($retrait * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($ventes_Produits * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($ventes_Mobiles * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($ventes_Services * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($depenses * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($bons_En_Espesces * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($versement_Clients * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($versement_Fournisseurs * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($remise_Ventes * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($total_Operations * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format((($bons_Clients + $mutuelle_Bon_A_Payer) * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];

            $rows[] = number_format(($mutuelle_Panier_A_Payer * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($versement_Mutuelles * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($mutuelle_Mutuelle_A_Payer * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];

            echo json_encode($rows);

        }
    /**Fin calculer les Operations du Jour**/

    /**Debut calculer les Benefices**/
        if($operation=='benefices'){

            $prixAchat_Clients = 0;         $prixUnitaire_Clients = 0;
            $prixAchat_Ventes = 0;          $prixUnitaire_Ventes = 0;

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtLigne= $bdd->prepare("SELECT p.idClient, l.quantite, d.prixSession, l.prixtotal  FROM  `".$nomtableLigne."` l
                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
                WHERE l.classe=0 AND p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') )  ");
                $stmtLigne->execute();
                $lignes = $stmtLigne->fetchAll();
                foreach ($lignes as $ligne) {
                    if($ligne['idClient']!=0){
                        if($ligne['prixtotal'] >= ($ligne['prixSession'] * $ligne['quantite'])){
                            $prixAchat_Clients = $prixAchat_Clients + ($ligne['quantite'] * $ligne['prixSession']);
                            $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                        }
                    }
                    else{
                        if($ligne['prixtotal'] >= ($ligne['prixSession'] * $ligne['quantite'])){
                            $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['quantite'] * $ligne['prixSession']);
                            $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                        }
                    }
                }
            }
            else {
                $stmtLigne= $bdd->prepare("SELECT l.numligne,p.idClient, l.quantite, l.prixunitevente, l.unitevente, d.uniteStock, d.nbreArticleUniteStock, d.prixachat, l.prixtotal FROM  `".$nomtableLigne."` l
                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
                WHERE l.classe=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') )  ");
                $stmtLigne->execute();
                $lignes = $stmtLigne->fetchAll();
                $i=0;
                foreach ($lignes as $ligne) {

                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                        if($ligne['idClient']!=0){
                            if ($ligne['unitevente']==$ligne['uniteStock']) {
                                if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                    $prixAchat_Clients = $prixAchat_Clients + ($ligne['prixachat'] * $ligne['quantite']);
                                    $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                }
                            }
                            else if ($ligne['unitevente']=="Demi Gros") {
                                if($ligne['prixtotal'] >= (($ligne['prixachat'] / 2) * $ligne['quantite'])){
                                    $prixAchat_Clients = $prixAchat_Clients + ( ($ligne['prixachat'] / 2) * $ligne['quantite']);
                                    $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                }
                            }
                            else{
                                if($ligne['prixtotal'] >= (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                    $prixAchat_Clients = $prixAchat_Clients + (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                    $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                }
                            } 
                        }
                        else{
                            if ($ligne['unitevente']==$ligne['uniteStock']) {
                                if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                    $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['prixachat'] * $ligne['quantite']);
                                    $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                }
                            }
                            else if ($ligne['unitevente']=="Demi Gros") {
                                if($ligne['prixtotal']>=(($ligne['prixachat'] / 2) * $ligne['quantite'])){
                                    $prixAchat_Ventes = $prixAchat_Ventes + ( ($ligne['prixachat'] / 2) * $ligne['quantite']);
                                    $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                }
                            }
                            else{
                                if($ligne['prixtotal'] >= (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                    $prixAchat_Ventes = $prixAchat_Ventes + (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                    $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                }
                            }
                        }

                        
                    }
                    else{
                        if($ligne['idClient']!=0){
                            if (($ligne['unitevente']!="Article") AND ($ligne['unitevente']!="article")) {
                                if($ligne['prixtotal'] >= (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                    $prixAchat_Clients = $prixAchat_Clients + (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                    $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                }
                            }
                            else{
                                if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                    $prixAchat_Clients = $prixAchat_Clients + ($ligne['prixachat'] * $ligne['quantite']);   
                                    $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                }
                            } 
                        }
                        else{
                            if (($ligne['unitevente']!="Article") AND ($ligne['unitevente']!="article")) {
                                if($ligne['prixtotal'] >= (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                    $prixAchat_Ventes = $prixAchat_Ventes + (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                    $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                }
                            }
                            else{
                                if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                    $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['prixachat'] * $ligne['quantite']);
                                    $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                }
                            }
                        }
                    }

                }
            }

            $benefices_Vente = ($prixUnitaire_Ventes - $prixAchat_Ventes);
            $benefices_Client = ($prixUnitaire_Clients - $prixAchat_Clients);
            $benefices = $benefices_Vente + $benefices_Client;

            $rows = array();
            $rows[] = number_format(($benefices_Vente * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($benefices_Client * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($benefices * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            
            echo json_encode($rows);
        }
    /**Fin calculer les Benefices**/

    /**Debut Lister les Lignes d'un Panier **/
        if($operation=='lignes'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);

            if($panier){
                $stmtLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE idPagnet = :idPagnet ");
                $stmtLigne->execute(array(
                    ':idPagnet' => $idPagnet
                ));
                $lignes = $stmtLigne->fetchAll(PDO::FETCH_ASSOC);
    
                $data=array();
                
                if($lignes==true){
                    foreach($lignes as $ligne){

                        $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                        WHERE idDesignation =:idDesignation");
                        $stmtReference->execute(array(
                            ':idDesignation' => $ligne['idDesignation']
                        ));
                        $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

                        $rows = array();
                        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                            $rows[] = $ligne['idPagnet'];	
                            $rows[] = $ligne['numligne'];	
                            $rows[] = $ligne['designation'];
                            $rows[] = $ligne['quantite'];
                            $rows[] = $ligne['forme'];
                            $rows[] = $ligne['prixPublic'];
                            $rows[] = $ligne['prixtotal'];
                            $rows[] = $panier['verrouiller'];
                        }
                        else {
                            $rows[] = $ligne['idPagnet'];	
                            $rows[] = $ligne['numligne'];	
                            $rows[] = $ligne['designation'];
                            $rows[] = $ligne['quantite'];
                            $rows[] = $ligne['unitevente'];
                            $rows[] = $ligne['prixunitevente'];
                            $rows[] = $ligne['prixtotal'];
                            $rows[] = $panier['verrouiller'];
                        }

                        $data[] = $rows;
                    
                    }
                }
                else{
                    $data[] = 0;
                }
            }
            else {
                $data[] = 0;
            }

            echo json_encode($data);
        }
    /**Fin Lister les Lignes d'un Panier ***/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>