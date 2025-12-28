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
    $idProduit=@$_POST["idProduit"];
    $categorie=@$_POST["categorie"];
    $forme=@$_POST["forme"];
    $reference=@$_POST["reference"];
    $prixSession=@$_POST["prixSession"];
    $prixPublic=@$_POST["prixPublic"];
    $codeBarre=@$_POST["codeBarre"];
    $query=@htmlspecialchars(trim($_POST['query'])); 

    $quantite=@$_POST["quantite"];
    $dateExpiration=@$_POST["dateExpiration"];
    $idFournisseur=@$_POST["idFournisseur"];
    $idBl=@$_POST["idBl"];

    $idStock=@$_POST["idStock"];


    /**Debut Ajouter Stock**/
        if($operation=='ajouter_Stock'){

            $resultat = 0;

            $stmProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmProduit->fetch(PDO::FETCH_ASSOC);

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteCommande, forme, totalArticleStock, dateStockage, quantiteStockCourant, prixSession, prixPublic, dateExpiration, idBl, iduser)
            VALUES (:idDesignation, :designation, :quantiteCommande, :forme, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixSession, :prixPublic, :dateExpiration, :idBl, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteCommande' => $quantite,
                ':forme' => $produit['forme'], 
                ':totalArticleStock' => $quantite,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => 0, 
                ':prixSession' => $prixSession,
                ':prixPublic' => $prixPublic,
                ':dateExpiration' => $dateExpiration,
                ':idBl' => $idBl,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idStock = $bdd->lastInsertId();

            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtBL->execute(array(
                ':idBl' => $idBl
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $montant=0;
            foreach ($bls as $bl) {
                $montant = $montant + ($bl['prixSession'] * $bl['quantiteCommande']);
            }

            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal, montantBl=:montantBl  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $idBl,
                ':montantTotal' => $montant,
                ':montantBl' => $montant
            ));

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $last_idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
            
            $rows = array();
            $rows[] = $stock['idStock'];	
            $rows[] = $stock['designation'];	
            $rows[] = $stock['quantiteCommande'];
            $rows[] = $stock['forme'];
            $rows[] = $stock['prixSession'];
            $rows[] = $stock['prixPublic'];
            $rows[] = $stock['prixSession'] * $stock['quantiteCommande'];
            $rows[] = $stock['dateStockage'];
            $rows[] = $stock['dateExpiration'];

            echo json_encode($rows);
        }
    /**Fin Ajouter Stock**/

    /**Debut calculer la valeur du Montant du Stock**/
        if($operation=='stock'){


        }
    /**Fin calculer la valeur du Montant du Stock**/

    /**Debut Details Stock**/
        if($operation=='details_Stock'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
            WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            $rows = array();	
            $rows[] = $stock['designation'];
            $rows[] = $stock['quantiteCommande'];
            $rows[] = $stock['forme'];
            $rows[] = $stock['prixSession'];
            $rows[] = $stock['prixPublic'];		
            $rows[] = $stock['dateExpiration'];
            echo json_encode($rows);
        }
    /**Fin Details Stock**/

    /**Debut Supprimer Stock**/
        if($operation=='supprimer_Stock'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            $stmtStock = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idStock=:idStock ");
            $stmtStock->execute(array(':idStock' => $idStock ));

            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtBL->execute(array(
                ':idBl' => $stock['idBl']
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $montant=0;
            foreach ($bls as $bl) {
                $montant = $montant + ($bl['prixSession'] * $bl['quantiteCommande']);
            }
            
            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal, montantBl=:montantBl  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $stock['idBl'],
                ':montantTotal' => $montant,
                ':montantBl' => $montant
            ));

            echo json_encode(1);
        }
    /**Fin Supprimer Stock**/ 

    /**Debut Transferer Stock**/
       if($operation=='transferer_Stock'){

            $stmtStock = $bdd->prepare("UPDATE `".$nomtableStock."` SET idBl = :idBl
            WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idBl' => $idBl,
                ':idStock' => $idStock
            ));	

            echo json_encode($idStock);
        }
    /**Fin Transferer Stock**/ 

    /**Debut Choix Fournisseur**/
        if($operation=='choix_Fournisseur'){
            $stmtFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` ");
            $stmtFournisseur->execute();
            $fournisseurs = $stmtFournisseur->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($fournisseurs as $fournisseur){
                $rows = array();
                $rows[] = $fournisseur['idFournisseur'];	
                $rows[] = $fournisseur['nomFournisseur'];	
                $data[] = $rows; 
            }
            echo json_encode($data);
        }
    /**Fin Choix Fournisseur**/

    /**Debut Choix BL Fournisseur**/
        if($operation=='choix_BL_Fournisseur'){
            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableBl."` WHERE idFournisseur =:idFournisseur  ORDER BY idBl DESC");
            $stmtBL->execute(array(
                ':idFournisseur' => $idFournisseur
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($bls as $bl){
                $rows = array();
                $rows[] = $bl['idBl'];	
                $rows[] = $bl['numeroBl'];
                $rows[] = $bl['dateBl'];	
                $data[] = $rows; 
            }
            echo json_encode($data);
        }
    /**Fin Choix BL Fournisseur**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>