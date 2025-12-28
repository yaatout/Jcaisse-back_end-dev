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
    $idFournisseur=@$_POST["idFournisseur"];
    $nom=@$_POST["nom"];
    $adresse=@$_POST["adresse"];
    $telephone=@$_POST["telephone"];
    $banque=@$_POST["banque"];
    $numBanque=@$_POST["numBanque"];

    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];

    /**Debut Details Fournisseur**/
        if($operation=='details_Fournisseur'){

            $stmtFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` 
            WHERE idFournisseur = :idFournisseur ");
            $stmtFournisseur->execute(array(
                ':idFournisseur' => $idFournisseur
            ));
            $fournisseur = $stmtFournisseur->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $fournisseur['idFournisseur'];	
            $rows[] = $fournisseur['nomFournisseur'];
            $rows[] = $fournisseur['adresseFournisseur'];
            $rows[] = $fournisseur['telephoneFournisseur'];	
            $rows[] = $fournisseur['banqueFournisseur'];
            $rows[] = $fournisseur['numBanqueFournisseur'];	

            echo json_encode($rows);
        }
    /**Fin Details Fournisseur**/

    /**Debut Ajouter Fournisseur**/
        if($operation=='ajouter_Fournisseur'){

            $stmFournisseur_Ajouter= $bdd->prepare("INSERT INTO `".$nomtableFournisseur."` (nomFournisseur, adresseFournisseur, telephoneFournisseur, banqueFournisseur, numBanqueFournisseur)
            VALUES (:nomFournisseur, :adresseFournisseur, :telephoneFournisseur, :banqueFournisseur, :numBanqueFournisseur)");
            $stmFournisseur_Ajouter->execute(array(
                ':nomFournisseur' => $nom,
                ':adresseFournisseur' => $adresse, 
                ':telephoneFournisseur' => $telephone, 
                ':banqueFournisseur' => $banque, 
                ':numBanqueFournisseur' => $numBanque
            ));
            $last_idFournisseur = $bdd->lastInsertId();

            $stmFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` 
            WHERE idFournisseur = :idFournisseur ");
            $stmFournisseur->execute(array(
                ':idFournisseur' => $last_idFournisseur
            ));
            $fournisseur = $stmFournisseur->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $fournisseur['idFournisseur'];	
            $rows[] = $fournisseur['nomFournisseur'];
            $rows[] = $fournisseur['adresseFournisseur'];
            $rows[] = $fournisseur['telephoneFournisseur'];	
            $rows[] = $fournisseur['banqueFournisseur'];
            $rows[] = $fournisseur['numBanqueFournisseur'];	

            echo json_encode($rows);
        }
    /**Fin Ajouter Fournisseur**/

    /**Debut Modifier Fournisseur**/
        if($operation=='modifier_Fournisseur'){

            $stmtFournisseur_Modifier = $bdd->prepare("UPDATE `".$nomtableFournisseur."` 
            SET nomFournisseur=:nomFournisseur, adresseFournisseur=:adresseFournisseur, telephoneFournisseur=:telephoneFournisseur, banqueFournisseur=:banqueFournisseur, numBanqueFournisseur=:numBanqueFournisseur     
            WHERE idFournisseur = :idFournisseur ");
            $stmtFournisseur_Modifier->execute(array(
                ':idFournisseur' => $idFournisseur,
                ':nomFournisseur' => $nom,
                ':adresseFournisseur' => $adresse,
                ':telephoneFournisseur' => $telephone,
                ':banqueFournisseur' => $banque,
                ':numBanqueFournisseur' => $numBanque
            ));

            $stmFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` 
            WHERE idFournisseur = :idFournisseur ");
            $stmFournisseur->execute(array(
                ':idFournisseur' => $idFournisseur
            ));
            $fournisseur = $stmFournisseur->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $fournisseur['idFournisseur'];	
            $rows[] = $fournisseur['nomFournisseur'];
            $rows[] = $fournisseur['adresseFournisseur'];
            $rows[] = $fournisseur['telephoneFournisseur'];	
            $rows[] = $fournisseur['banqueFournisseur'];
            $rows[] = $fournisseur['numBanqueFournisseur'];		

            echo json_encode($rows);

        }
    /**Fin Modifier Fournisseur**/ 

    /**Debut Supprimer Fournisseur**/
        if($operation=='supprimer_Fournisseur'){

            $stmFournisseur_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableFournisseur."` WHERE idFournisseur=:idFournisseur ");
            $stmFournisseur_Supprimer->execute(array(':idFournisseur' => $idFournisseur ));

            echo json_encode(0);
        }
    /**Fin Supprimer Fournisseur**/

    /**Debut calculer la valeur du Montant de la Fournisseur**/
        if($operation=='montant'){

            $total_Bons = 0;
            $total_Versements = 0;

            //Somme des Pagnets Bons du Client
            $stmtBL = $bdd->prepare("SELECT * FROM `".$nomtableBl."` WHERE commande!=1 ");
            $stmtBL->execute();
            $bons = $stmtBL->fetchAll();
            foreach ($bons as $bon) {
                $total_Bons = $total_Bons + $bon['montantBl'];
            }

            //Somme des Versements du Client
            $stmtVersement = $bdd->prepare("SELECT * FROM `".$nomtableVersement."` WHERE idFournisseur!=0 AND idClient=0 ");
            $stmtVersement->execute();
            $versements = $stmtVersement->fetchAll();
            foreach ($versements as $versement) {
                $total_Versements = $total_Versements + $versement['montant'];
            }

            $solde = $total_Bons - $total_Versements;

            $rows = array();
            $rows[] = number_format(($solde * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	

            echo json_encode($rows);

        }
    /**Fin calculer la valeur du Montant de la Fournisseur**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>