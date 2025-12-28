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

    /**Debut calculer la valeur du Montant des clients**/
        if($operation=='clients'){
            //Somme des Pagnets Bons du Client
            $stmtBons = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c WHERE p.idClient=c.idClient AND c.archiver<>1 AND p.idClient<>0 AND p.verrouiller=1 AND (p.type=0 OR p.type=30 OR p.type=11) ");
            $stmtBons->execute();
            $bons = $stmtBons->fetch();

            //Somme des Versements du Client
            $stmtVersement = $bdd->prepare("SELECT SUM(montant) AS total FROM `".$nomtableVersement."` v, `".$nomtableClient."` c WHERE v.idClient=c.idClient AND c.archiver<>1 AND v.idClient<>0 ");
            $stmtVersement->execute();
            $versements = $stmtVersement->fetch();

            //Montant a verser du Client
            $solde = $bons['total'] - $versements['total'];

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                //Somme des Imputations du Client
                $stmtMutuelles = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$nomtableMutuellePagnet."` p, `".$nomtableClient."` c WHERE p.idClient=c.idClient AND c.archiver<>1 AND p.idClient<>0 AND p.verrouiller=1 AND (p.type=0 OR p.type=30 OR p.type=11) ");
                $stmtMutuelles->execute();
                $mutuelles = $stmtMutuelles->fetch();
       
                //Montant a verser du Client
                $solde = $solde + $mutuelles['total'];
       
            }

            $resultat = number_format(($solde * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            exit($resultat);
        }
    /**Fin calculer la valeur du Montant des clients**/

    /**Debut calculer la valeur du Montant des depots**/
        if($operation=='depots'){
            $stmtClients = $bdd->prepare("SELECT * FROM `".$nomtableClient."` WHERE archiver<>1 AND solde<0 ");
            $stmtClients->execute();
            $clients = $stmtClients->fetchAll();
            $montant = 0;
            foreach ($clients as $client) {

                $montant = $montant + $client['solde'] ;

            }
            $montant = - $montant;
            $resultat = number_format(($montant * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            exit($resultat);
        }
    /**Fin calculer la valeur du Montant des depots**/

    /**Debut calculer la valeur du Montant des dettes**/
        if($operation=='dettes'){
            $stmtClients = $bdd->prepare("SELECT * FROM `".$nomtableClient."` WHERE archiver<>1 AND solde>0 ");
            $stmtClients->execute();
            $clients = $stmtClients->fetchAll();
            $montant = 0;
            foreach ($clients as $client) {
                $montant = $montant + $client['solde'] ;
            }
            $resultat = number_format(($montant * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            exit($resultat);
        }
    /**Fin calculer la valeur du Montant des dettes**/

    /**Debut calculer la valeur du Montant du personnel**/
        if($operation=='personnels'){
            //Somme des Pagnets Bons du Client
            $stmtBons = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c WHERE p.idClient=c.idClient AND c.archiver<>1 AND c.personnel=1 AND p.idClient<>0 AND p.verrouiller=1 AND (p.type=0 OR p.type=30 OR p.type=11) ");
            $stmtBons->execute();
            $bons = $stmtBons->fetch();

            //Somme des Versements du Client
            $stmtVersement = $bdd->prepare("SELECT SUM(montant) AS total FROM `".$nomtableVersement."` v, `".$nomtableClient."` c WHERE v.idClient=c.idClient AND c.archiver<>1 AND c.personnel=1 AND v.idClient<>0 ");
            $stmtVersement->execute();
            $versements = $stmtVersement->fetch();

            //Montant a verser du Client
            $solde = $bons['total'] - $versements['total'];

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                //Somme des Imputations du Client
                $stmtMutuelles = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$nomtableMutuellePagnet."` p, `".$nomtableClient."` c WHERE p.idClient=c.idClient AND c.archiver<>1 AND c.personnel=1 AND p.idClient<>0 AND p.verrouiller=1 AND (p.type=0 OR p.type=30 OR p.type=11) ");
                $stmtMutuelles->execute();
                $mutuelles = $stmtMutuelles->fetch();
       
                //Montant a verser du Client
                $solde = $solde + $mutuelles['total'];
       
            }

            $resultat = number_format(($solde * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            exit($resultat);
        }
    /**Fin calculer la valeur du Montant du personnel**/

    /**Debut calculer la valeur du Montant des archives**/
        if($operation=='archives'){
            //Somme des Pagnets Bons du Client
            $stmtBons = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c WHERE p.idClient=c.idClient AND c.archiver=1 AND p.idClient<>0 AND p.verrouiller=1 AND (p.type=0 OR p.type=30 OR p.type=11) ");
            $stmtBons->execute();
            $bons = $stmtBons->fetch();

            //Somme des Versements du Client
            $stmtVersement = $bdd->prepare("SELECT SUM(montant) AS total FROM `".$nomtableVersement."` v, `".$nomtableClient."` c WHERE v.idClient=c.idClient AND c.archiver=1 AND v.idClient<>0 ");
            $stmtVersement->execute();
            $versements = $stmtVersement->fetch();

            //Montant a verser du Client
            $solde = $bons['total'] - $versements['total'];

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                //Somme des Imputations du Client
                $stmtMutuelles = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$nomtableMutuellePagnet."` p, `".$nomtableClient."` c WHERE p.idClient=c.idClient AND c.archiver=1 AND p.idClient<>0 AND p.verrouiller=1 AND (p.type=0 OR p.type=30 OR p.type=11) ");
                $stmtMutuelles->execute();
                $mutuelles = $stmtMutuelles->fetch();
       
                //Montant a verser du Client
                $solde = $solde + $mutuelles['total'];
       
            }

            $resultat = number_format(($solde * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            exit($resultat);
        }
    /**Fin calculer la valeur du Montant des archives**/

    /**Debut calculer la valeur du Montant des avoir**/
        if($operation=='avoirs'){
            //Somme des Pagnets Bons du Client
            $stmtClients = $bdd->prepare("SELECT * FROM `".$nomtableClient."` WHERE archiver<>1 AND avoir=1 ");
            $stmtClients->execute();
            $clients = $stmtClients->fetchAll();
            $montant = 0;
            foreach ($clients as $client) {

                $montant = $montant + $client['montantAvoir'] ;

            }
            $resultat = number_format(($montant * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            exit($resultat);

        }
    /**Fin calculer la valeur du Montant du avoir**/

}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>