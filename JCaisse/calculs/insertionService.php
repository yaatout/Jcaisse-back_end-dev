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
    $idService=@$_POST["idService"];
    $reference=@$_POST["reference"];
    $unite=@$_POST["unite"];
    $prix=@$_POST["prix"];

    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];

    /**Debut Details Service**/
        if($operation=='details_Service'){

            $stmtService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE idDesignation = :idDesignation ");
            $stmtService->execute(array(
                ':idDesignation' => $idService
            ));
            $service = $stmtService->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['forme'];
                $rows[] = $service['prixPublic'];	
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['uniteStock'];
                $rows[] = $service['prixuniteStock'];	
            } 
            else {
                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['uniteStock'];
                $rows[] = $service['prix'];	
            }

            echo json_encode($rows);
        }
    /**Fin Details Service**/

    /**Debut Ajouter Service**/
        if($operation=='ajouter_Service'){

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

                $stmService_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, forme, prixPublic, classe)
                VALUES (:designation, :forme, :prixPublic, :classe)");
                $stmService_Ajouter->execute(array(
                    ':designation' => $reference,
                    ':forme' => $unite,
                    ':prixPublic' => $prix, 
                    ':classe' => 1, 
                ));
                $last_idService = $bdd->lastInsertId();
    
                $stmService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmService->execute(array(
                    ':idDesignation' => $last_idService
                ));
                $service = $stmService->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['forme'];
                $rows[] = $service['prixPublic'];	
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                $stmService_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, uniteStock, prixuniteStock, prix, classe)
                VALUES (:designation, :uniteStock, :prixuniteStock, :prix, :classe)");
                $stmService_Ajouter->execute(array(
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prixuniteStock' => $prix, 
                    ':prix' => $prix, 
                    ':classe' => 1, 
                ));
                $last_idService = $bdd->lastInsertId();
    
                $stmService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmService->execute(array(
                    ':idDesignation' => $last_idService
                ));
                $service = $stmService->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['uniteStock'];
                $rows[] = $service['prixuniteStock'];	
            } 
            else {

                $stmService_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, uniteStock, prix, classe)
                VALUES (:designation, :uniteStock, :prix, :classe)");
                $stmService_Ajouter->execute(array(
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prix' => $prix, 
                    ':classe' => 1, 
                ));
                $last_idService = $bdd->lastInsertId();
    
                $stmService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmService->execute(array(
                    ':idDesignation' => $last_idService
                ));
                $service = $stmService->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['uniteStock'];
                $rows[] = $service['prix'];	
            }

            echo json_encode($rows);
        }
    /**Fin Ajouter Service**/

    /**Debut Modifier Service**/
        if($operation=='modifier_Service'){

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

                $stmtService_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation=:designation, forme=:forme, prixPublic=:prixPublic    
                WHERE idDesignation = :idDesignation ");
                $stmtService_Modifier->execute(array(
                    ':idDesignation' => $idService,
                    ':designation' => $reference,
                    ':forme' => $unite,
                    ':prixPublic' => $prix,
                ));
    
                $stmService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmService->execute(array(
                    ':idDesignation' => $idService
                ));
                $service = $stmService->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['forme'];
                $rows[] = $service['prixPublic'];	
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                $stmtService_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation=:designation, uniteStock=:uniteStock, prixuniteStock=:prixuniteStock, prix=:prix    
                WHERE idDesignation = :idDesignation ");
                $stmtService_Modifier->execute(array(
                    ':idDesignation' => $idService,
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prixuniteStock' => $prix,
                    ':prix' => $prix,
                ));
    
                $stmService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmService->execute(array(
                    ':idDesignation' => $idService
                ));
                $service = $stmService->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['uniteStock'];
                $rows[] = $service['prixuniteStock'];	
            } 
            else {

                $stmtService_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation=:designation, uniteStock=:uniteStock, prix=:prix    
                WHERE idDesignation = :idDesignation ");
                $stmtService_Modifier->execute(array(
                    ':idDesignation' => $idService,
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prix' => $prix,
                ));
    
                $stmService = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmService->execute(array(
                    ':idDesignation' => $idService
                ));
                $service = $stmService->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $service['idDesignation'];	
                $rows[] = $service['designation'];
                $rows[] = $service['uniteStock'];
                $rows[] = $service['prix'];	
            }

            echo json_encode($rows);

        }
    /**Fin Modifier Service**/ 

    /**Debut Supprimer Service**/
        if($operation=='supprimer_Service'){

            $stmService_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=:idDesignation ");
            $stmService_Supprimer->execute(array(':idDesignation' => $idService ));

            echo json_encode(0);
        }
    /**Fin Supprimer Service**/

    /**Debut calculer la valeur du Montant du Service**/
        if($operation=='service'){

            $service = 0;

            $stmtLigne = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
            WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtLigne->bindValue(':idDesignation', (int)$idService, PDO::PARAM_INT);
            $stmtLigne->execute();
            $lignes = $stmtLigne->fetchAll();
            foreach ($lignes as $ligne) {
                $service = $service + $ligne['prixtotal'];
            }
	
            $service = number_format(($service * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            echo json_encode($service);
        }
    /**Fin calculer la valeur du Montant du Service**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>