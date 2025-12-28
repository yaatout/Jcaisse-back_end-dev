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
    $idDepense=@$_POST["idDepense"];
    $reference=@$_POST["reference"];
    $unite=@$_POST["unite"];
    $prix=@$_POST["prix"];

    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];

    /**Debut Details Depense**/
        if($operation=='details_Depense'){

            $stmtDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE idDesignation = :idDesignation ");
            $stmtDepense->execute(array(
                ':idDesignation' => $idDepense
            ));
            $depense = $stmtDepense->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['forme'];
                $rows[] = $depense['prixPublic'];	
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['uniteStock'];
                $rows[] = $depense['prixuniteStock'];	
            } 
            else {
                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['uniteStock'];
                $rows[] = $depense['prix'];	
            }

            echo json_encode($rows);
        }
    /**Fin Details Depense**/

    /**Debut Ajouter Depense**/
        if($operation=='ajouter_Depense'){

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

                $stmDepense_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, forme, prixPublic, classe)
                VALUES (:designation, :forme, :prixPublic, :classe)");
                $stmDepense_Ajouter->execute(array(
                    ':designation' => $reference,
                    ':forme' => $unite,
                    ':prixPublic' => $prix, 
                    ':classe' => 2, 
                ));
                $last_idDepense = $bdd->lastInsertId();
    
                $stmDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmDepense->execute(array(
                    ':idDesignation' => $last_idDepense
                ));
                $depense = $stmDepense->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['forme'];
                $rows[] = $depense['prixPublic'];	
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                $stmDepense_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, uniteStock, prixuniteStock, classe)
                VALUES (:designation, :uniteStock, :prixuniteStock, :classe)");
                $stmDepense_Ajouter->execute(array(
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prixuniteStock' => $prix, 
                    ':classe' => 2, 
                ));
                $last_idDepense = $bdd->lastInsertId();
    
                $stmDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmDepense->execute(array(
                    ':idDesignation' => $last_idDepense
                ));
                $depense = $stmDepense->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['uniteStock'];
                $rows[] = $depense['prixuniteStock'];	
            } 
            else {

                $stmDepense_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, uniteStock, prix, classe)
                VALUES (:designation, :uniteStock, :prix, :classe)");
                $stmDepense_Ajouter->execute(array(
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prix' => $prix, 
                    ':classe' => 2, 
                ));
                $last_idDepense = $bdd->lastInsertId();
    
                $stmDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmDepense->execute(array(
                    ':idDesignation' => $last_idDepense
                ));
                $depense = $stmDepense->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['uniteStock'];
                $rows[] = $depense['prix'];	
            }

            echo json_encode($rows);
        }
    /**Fin Ajouter Depense**/

    /**Debut Modifier Depense**/
        if($operation=='modifier_Depense'){

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

                $stmtDepense_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation=:designation, forme=:forme, prixPublic=:prixPublic    
                WHERE idDesignation = :idDesignation ");
                $stmtDepense_Modifier->execute(array(
                    ':idDesignation' => $idDepense,
                    ':designation' => $reference,
                    ':forme' => $unite,
                    ':prixPublic' => $prix,
                ));
    
                $stmDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmDepense->execute(array(
                    ':idDesignation' => $idDepense
                ));
                $depense = $stmDepense->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['forme'];
                $rows[] = $depense['prixPublic'];	
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                $stmtDepense_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation=:designation, uniteStock=:uniteStock, prixuniteStock=:prixuniteStock    
                WHERE idDesignation = :idDesignation ");
                $stmtDepense_Modifier->execute(array(
                    ':idDesignation' => $idDepense,
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prixuniteStock' => $prix,
                ));
    
                $stmDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmDepense->execute(array(
                    ':idDesignation' => $idDepense
                ));
                $depense = $stmDepense->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['uniteStock'];
                $rows[] = $depense['prixuniteStock'];	
            } 
            else {

                $stmtDepense_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation=:designation, uniteStock=:uniteStock, prix=:prix    
                WHERE idDesignation = :idDesignation ");
                $stmtDepense_Modifier->execute(array(
                    ':idDesignation' => $idDepense,
                    ':designation' => $reference,
                    ':uniteStock' => $unite,
                    ':prix' => $prix,
                ));
    
                $stmDepense = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                WHERE idDesignation = :idDesignation ");
                $stmDepense->execute(array(
                    ':idDesignation' => $idDepense
                ));
                $depense = $stmDepense->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $depense['idDesignation'];	
                $rows[] = $depense['designation'];
                $rows[] = $depense['uniteStock'];
                $rows[] = $depense['prix'];	
            }

            echo json_encode($rows);

        }
    /**Fin Modifier Depense**/ 

    /**Debut Supprimer Depense**/
        if($operation=='supprimer_Depense'){

            $stmDepense_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=:idDesignation ");
            $stmDepense_Supprimer->execute(array(':idDesignation' => $idDepense ));

            echo json_encode(0);
        }
    /**Fin Supprimer Depense**/

    /**Debut calculer la valeur du Montant de la Depense**/
        if($operation=='depense'){

            $depense = 0;

            $stmtLigne = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
            WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtLigne->bindValue(':idDesignation', (int)$idDepense, PDO::PARAM_INT);
            $stmtLigne->execute();
            $lignes = $stmtLigne->fetchAll();
            foreach ($lignes as $ligne) {
                $depense = $depense + $ligne['prixtotal'];
            }
	
            $depense = number_format(($depense * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            echo json_encode($depense);
        }
    /**Fin calculer la valeur du Montant de la Depense**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>