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
    $idBon=@$_POST["idBon"];

    $idStock=@$_POST["idStock"];

    $stmtBon = $bdd->prepare("SELECT  * FROM `".$nomtableBl."` WHERE idBl = :idBl ");
    $stmtBon->execute(array(
        ':idBl' => $idBl
    ));
    $bl = $stmtBon->fetch(PDO::FETCH_ASSOC);
    $montantBl=$bl['montantBl'];

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

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixunitaire, prixachat, dateExpiration, idBl, iduser)
            VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :dateExpiration, :idBl, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteStockinitial' => $quantite,
                ':uniteStock' => $uniteStock, 
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => $totalArticleStock, 
                ':prixuniteStock' => $prixUniteStock,
                ':prixunitaire' => $prixUnitaire,
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

            $calcul=0;
            foreach ($bls as $bl) {
                $calcul = $calcul + ($bl['prixachat'] * $bl['totalArticleStock']);
            }

            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $idBl,
                ':montantTotal' => $calcul
            ));

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $last_idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
            
            $rows = array();
            $rows[] = $stock['idStock'];	
            $rows[] = $stock['designation'];	
            $rows[] = $stock['quantiteStockinitial'];
            $rows[] = $stock['uniteStock'].' [x '.$stock['nbreArticleUniteStock'].']';
            $rows[] = $stock['prixachat'];
            $rows[] = $stock['prixachat'] * $stock['totalArticleStock'];
            $rows[] = $stock['dateStockage'];
            $rows[] = $stock['quantiteStockCourant'];
            $rows[] = $stock['prixunitaire'];
            $rows[] = $stock['prixuniteStock'];
            $rows[] = $stock['dateExpiration'];
            $rows[] = number_format($calcul, 0, ',', ' ');
            $rows[] = number_format($montantBl, 0, ',', ' ');
            $rows[] = number_format(($montantBl - $calcul), 0, ',', ' ');

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

            if($uniteStock=='Article' || $uniteStock=='article'){
                $nombreArticles=1;
                $totalArticleStock= $nombreArticles * $quantite;
            }
            else{
                $nombreArticles=$produit['nbreArticleUniteStock'];
                $totalArticleStock= $nombreArticles * $quantite;
            }
    
            $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockinitial=:quantiteStockinitial, totalArticleStock=:totalArticleStock, quantiteStockCourant=:quantiteStockCourant,
            uniteStock =:uniteStock, nbreArticleUniteStock=:nbreArticleUniteStock, prixachat =:prixachat, dateExpiration =:dateExpiration
            WHERE idStock = :idStock ");
            $stmtStock_Modifier->execute(array(
                ':idStock' => $idStock,
                ':quantiteStockinitial' => $quantite,
                ':uniteStock' => $uniteStock, 
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':quantiteStockCourant' => $totalArticleStock, 
                ':prixachat' => $prixAchat,
                ':dateExpiration' => $dateExpiration
            ));

            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtBL->execute(array(
                ':idBl' => $stock['idBl']
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $calcul=0;
            foreach ($bls as $bl) {
                $calcul = $calcul + ($bl['prixachat'] * $bl['totalArticleStock']);
            }
            
            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $stock['idBl'],
                ':montantTotal' => $calcul
            ));

            $rows = array();
            $rows[] = number_format($calcul, 0, ',', ' ');
            $rows[] = number_format($montantBl, 0, ',', ' ');
            $rows[] = number_format(($montantBl - $calcul), 0, ',', ' ');

            echo json_encode($rows);

        }
    /**Fin Modifier Stock**/ 

    /**Debut Supprimer Stock**/
        if($operation=='supprimer_Stock'){

            $stmtStock = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idStock=:idStock ");
            $stmtStock->execute(array(':idStock' => $idStock ));

            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl ");
            $stmtBL->execute(array(
                ':idBl' => $idBl
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $calcul=0;
            foreach ($bls as $bl) {
                $calcul = $calcul + ($bl['prixachat'] * $bl['totalArticleStock']);
            }
            
            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $idBl,
                ':montantTotal' => $calcul
            ));

            $rows = array();
            $rows[] = number_format($calcul, 0, ',', ' ');
            $rows[] = number_format($montantBl, 0, ',', ' ');
            $rows[] = number_format(($montantBl - $calcul), 0, ',', ' ');

            echo json_encode($rows);
        }
    /**Fin Supprimer Stock**/ 

    /**Debut Transferer Stock**/
       if($operation=='transferer_Stock'){

            $stmtStock = $bdd->prepare("UPDATE `".$nomtableStock."` SET idBl = :idBl
            WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idBl' => $idBon,
                ':idStock' => $idStock
            ));	

            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` WHERE idBl = :idBl1 OR idBl = :idBl2 ");
            $stmtBL->execute(array(
                ':idBl1' => $idBl,
                ':idBl2' => $idBon
            ));
            $bls = $stmtBL->fetchAll(PDO::FETCH_ASSOC);

            $montant1=0; $montant2=0;
            foreach ($bls as $bl) {
                if($bl['idBl']==$idBl){
                    $montant1 = $montant1 + ($bl['prixachat'] * $bl['totalArticleStock']);
                }
                else if($bl['idBl']==$idBon){
                    $montant2 = $montant2 + ($bl['prixachat'] * $bl['totalArticleStock']);
                }
            }

            $stmtBon_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal  WHERE idBl = :idBl ");
            $stmtBon_Modifier->execute(array(
                ':idBl' => $idBon,
                ':montantTotal' => $montant2
            ));
            
            $stmtBL_Modifier = $bdd->prepare("UPDATE `".$nomtableBl."` 
            SET montantTotal=:montantTotal  WHERE idBl = :idBl ");
            $stmtBL_Modifier->execute(array(
                ':idBl' => $idBl,
                ':montantTotal' => $montant1
            ));

            $rows = array();
            $rows[] = number_format($montant1, 0, ',', ' ');
            $rows[] = number_format($montantBl, 0, ',', ' ');
            $rows[] = number_format(($montantBl - $montant1), 0, ',', ' ');

            echo json_encode($rows);
        }
    /**Fin Transferer Stock**/ 

    /**Debut Choix Fournisseur**/
        if($operation=='choix_Fournisseur'){
            $stmtFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` ORDER BY idFournisseur DESC ");
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