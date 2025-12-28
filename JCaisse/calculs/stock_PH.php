<?php

session_start();

if(!$_SESSION['iduser']){

header('Location:../JCaisse/index.php');

}

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php');

try{


    $operation=@htmlspecialchars($_POST["operation"]);
    $idProduit=@$_POST["idProduit"];
    $idStock=@$_POST["idStock"];
    $forme=@$_POST["forme"];
    $reference=@$_POST["reference"];
    $prixPublic=@$_POST["prixPublic"];
    $prixSession=@$_POST["prixSession"];
    $codeBarre=@$_POST["codeBarre"];
    $query=@htmlspecialchars(trim($_POST['query'])); 

    $quantite=@$_POST["quantite"];
    $dateExpiration=@$_POST["dateExpiration"];
    $idBl=@$_POST["idBl"];

    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];


    /**Debut Details Stock**/
        if($operation=='details_Stock'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
            WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $stock['idDesignation']
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $stock['idStock'];	
            $rows[] = $stock['dateStockage'];
            $rows[] = $stock['quantiteStockinitial'];
            $rows[] = $stock['forme'];	
            $rows[] = $stock['quantiteStockCourant'];	
            $rows[] = $stock['prixPublic'];
            $rows[] = $stock['prixSession'];
            $rows[] = $stock['dateExpiration'];

            echo json_encode($rows);
        }
    /**Fin Details Stock**/

    /**Debut calculer la valeur du Montant du Stock**/
        if($operation=='calcul_Quantite'){

            $stmProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmProduit->fetch(PDO::FETCH_ASSOC);

            $stmtEntrees = $bdd->prepare("SELECT SUM(quantiteStockinitial) AS total FROM `".$nomtableStock."` WHERE idDesignation=:idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtEntrees->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtEntrees->execute();
            $entrees = $stmtEntrees->fetch();
        
            $ventes=0;

            $stmtSorties = $bdd->prepare("SELECT SUM(l.quantite) AS total FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
            WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtSorties->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtSorties->execute();
            $sorties = $stmtSorties->fetch();

            $stmtImputations = $bdd->prepare("SELECT SUM(l.quantite) AS total FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtableMutuellePagnet."` p ON p.idMutuellePagnet = l.idMutuellePagnet
            WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtImputations->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtImputations->execute();
            $imputations = $stmtImputations->fetch();

            $ventes = $sorties['total'] + $imputations['total'];
            
            $resultat=number_format($entrees['total'], 0, ',', ' ').'<>'.number_format($ventes, 0, ',', ' ');

            echo json_encode($resultat);
        }
    /**Fin calculer la valeur du Montant du Stock**/

    /**Debut Modifier Stock**/
        if($operation=='modifier_Stock'){
            
            $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockinitial=:quantiteStockinitial, totalArticleStock=:totalArticleStock, quantiteStockCourant=:quantiteStockCourant,
            forme =:forme, prixPublic =:prixPublic, prixSession =:prixSession, dateExpiration =:dateExpiration
            WHERE idStock = :idStock ");
            $stmtStock_Modifier->execute(array(
                ':idStock' => $idStock,
                ':quantiteStockinitial' => $quantite,
                ':forme' => $forme, 
                ':totalArticleStock' => $quantite,
                ':quantiteStockCourant' => $quantite, 
                ':prixPublic' => $prixPublic,
                ':prixSession' => $prixSession,
                ':dateExpiration' => $dateExpiration,
            ));

            $rows = array();
            $rows[] = $idStock;	
            $rows[] = $quantite;
            $rows[] = $forme;	

            echo json_encode($rows);
        }
    /**Fin Modifier Stock**/ 

    /**Debut Retirer Stock**/
        if($operation=='retirer_Stock'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
            WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            $quantiteStockCourant = $stock['quantiteStockCourant'] - $quantite;

            $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` 
            SET quantiteStockCourant=:quantiteStockCourant  WHERE idStock = :idStock ");
            $stmtStock_Modifier->execute(array(
                ':idStock' => $idStock,
                ':quantiteStockCourant' => $quantiteStockCourant,
            ));	

            $stmInventaire_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` 
            (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,heureInventaire,type,idUser) VALUES
            (:idStock,:idDesignation,:quantite,:nbreArticleUniteStock,:quantiteStockCourant,:dateInventaire,:heureInventaire,:type,:idUser)");
            $stmInventaire_Ajouter->execute(array(
                ':idStock' => $idStock,
                ':idDesignation' => $stock['idDesignation'],
                ':quantite' => $quantiteStockCourant, 
                ':nbreArticleUniteStock' => 1, 
                ':quantiteStockCourant' => $stock['quantiteStockCourant'],
                ':dateInventaire' => $dateString,
                ':heureInventaire' => $heureString,
                ':type' => 1,
                ':idUser' => $_SESSION['iduser'],
            ));

            echo json_encode(1);

        }
    /**Fin Retirer Stock**/ 

    /**Debut Retourner Stock**/
        if($operation=='retourner_Stock'){

            $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
            WHERE idStock = :idStock ");
            $stmtStock->execute(array(
                ':idStock' => $idStock
            ));
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            $quantiteStockCourant = $stock['quantiteStockCourant'] - $quantite;

            $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` 
            SET quantiteStockCourant=:quantiteStockCourant  WHERE idStock = :idStock ");
            $stmtStock_Modifier->execute(array(
                ':idStock' => $idStock,
                ':quantiteStockCourant' => $quantiteStockCourant,
            ));	

            $stmInventaire_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` 
            (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,heureInventaire,type,idUser) VALUES
            (:idStock,:idDesignation,:quantite,:nbreArticleUniteStock,:quantiteStockCourant,:dateInventaire,:heureInventaire,:type,:idUser)");
            $stmInventaire_Ajouter->execute(array(
                ':idStock' => $idStock,
                ':idDesignation' => $stock['idDesignation'],
                ':quantite' => $quantiteStockCourant, 
                ':nbreArticleUniteStock' => 1, 
                ':quantiteStockCourant' => $stock['quantiteStockCourant'],
                ':dateInventaire' => $dateString,
                ':heureInventaire' => $heureString,
                ':type' => 3,
                ':idUser' => $_SESSION['iduser'],
            ));


            echo json_encode(1);

        }
    /**Fin Retourner Stock**/ 

    /**Debut Supprimer Stock**/
        if($operation=='supprimer_Stock'){

            $stmStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idStock=:idStock ");
            $stmStock_Supprimer->execute(array(':idStock' => $idStock ));

            echo json_encode(0);
        }
    /**Fin Supprimer Stock**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}

?>