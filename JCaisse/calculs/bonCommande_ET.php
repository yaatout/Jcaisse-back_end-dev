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
    $uniteStock=@$_POST["uniteStock"];
    $reference=@$_POST["reference"];
    $nombreArticles=@$_POST["nombreArticles"];
    $prixUniteStock=@$_POST["prixUniteStock"];
    $prixUnitaire=@$_POST["prixUnitaire"];
    $prixAchat=@$_POST["prixAchat"];
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

            if($uniteStock=='Article' || $uniteStock=='article'){
                $nombreArticles=1;
                $totalArticleStock= $nombreArticles * $quantite;
            }
            else{
                $nombreArticles=$produit['nbreArticleUniteStock'];
                $totalArticleStock= $nombreArticles * $quantite;
            }

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteCommande, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, dateExpiration, idBl, iduser)
            VALUES (:idDesignation, :designation, :quantiteCommande, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixachat, :dateExpiration, :idBl, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteCommande' => $quantite,
                ':uniteStock' => $produit['uniteStock'],
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => 0, 
                ':prixuniteStock' => $prixUniteStock,
                ':prixachat' => $prixAchat,
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
                $montant = $montant + ($bl['prixachat'] * $bl['quantiteCommande']);
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
            $rows[] = $stock['uniteStock'].' [x '.$stock['nbreArticleUniteStock'].']';
            $rows[] = $stock['prixachat'];
            $rows[] = $stock['prixachat'] * $stock['quantiteCommande'];
            $rows[] = $stock['dateStockage'];
            $rows[] = $stock['prixunitaire'];
            $rows[] = $stock['prixuniteStock'];
            $rows[] = $stock['dateExpiration'];
            $rows[] = number_format($montant, 0, ',', ' ');

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
            $rows[] = $stock['totalArticleStock'];
            $rows[] = $stock['nbreArticleUniteStock'];
            $rows[] = $stock['uniteStock'];
            $rows[] = $stock['prixachat'];		
            $rows[] = $stock['dateExpiration'];
            echo json_encode($rows);
        }
    /**Fin Details Stock**/

    /**Debut Modifier Stock**/
        if($operation=='modifier_Stock'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            $stmProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmProduit->execute(array(
                ':idDesignation' => $stock['idDesignation']
            ));
            $produit = $stmProduit->fetch(PDO::FETCH_ASSOC);

            $nombreArticles=$produit['nbreArticleUniteStock'];
            $totalArticleStock= $nombreArticles * $quantite;
    
            $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteCommande=:quantiteCommande, totalArticleStock=:totalArticleStock,
            prixachat =:prixachat, dateExpiration =:dateExpiration
            WHERE idStock = :idStock ");
            $stmtStock_Modifier->execute(array(
                ':idStock' => $idStock,
                ':quantiteCommande' => $quantite,
                ':totalArticleStock' => $totalArticleStock,
                ':prixachat' => $prixAchat,
                ':dateExpiration' => $dateExpiration
            ));

            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtBL->execute(array(
                ':idBl' => $stock['idBl']
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $montant=0;
            foreach ($bls as $bl) {
                $montant = $montant + ($bl['prixachat'] * $bl['quantiteCommande']);
            }
            
            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal, montantBl=:montantBl  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $stock['idBl'],
                ':montantTotal' => $montant,
                ':montantBl' => $montant
            ));

            $rows = array();
            $rows[] = number_format($montant, 0, ',', ' ');

            echo json_encode($rows);
        }
    /**Fin Modifier Stock**/ 

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
                $montant = $montant + ($bl['prixachat'] * $bl['totalArticleStock']);
            }
            
            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal, montantBl=:montantBl  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $stock['idBl'],
                ':montantTotal' => $montant,
                ':montantBl' => $montant
            ));

            $rows = array();
            $rows[] = number_format($montant, 0, ',', ' ');

            echo json_encode($rows);
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