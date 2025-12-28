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
    $idStock=@$_POST["idStock"];
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
            $rows[] = $stock['uniteStock'];	
            $rows[] = $stock['totalArticleStock'];	
            $rows[] = $stock['quantiteStockCourant'];	
            $rows[] = $stock['prixuniteStock'];
            $rows[] = $stock['prixunitaire'];
            $rows[] = $stock['prixachat'];	
            $rows[] = $stock['dateExpiration'];
            $rows[] = $produit['uniteStock'].'<>'.$produit['nbreArticleUniteStock'];

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

            $stmtEntrees = $bdd->prepare("SELECT SUM(totalArticleStock) AS total FROM `".$nomtableStock."` WHERE idDesignation=:idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtEntrees->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtEntrees->execute();
            $entrees = $stmtEntrees->fetch();
        
            $stmtLigne = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
            WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtLigne->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtLigne->execute();
            $lignes = $stmtLigne->fetchAll();
            $sorties=0;
            foreach ($lignes as $ligne) {
                if($ligne['unitevente']=='article' || $ligne['unitevente']=='Article'){
                    $sorties = $sorties + $ligne['quantite'];
                }
                else{
                    $sorties = $sorties + ($ligne['quantite'] * $produit['nbreArticleUniteStock']);
                }
            }
            
            $resultat=number_format($entrees['total'], 0, ',', ' ').'<>'.number_format($sorties, 0, ',', ' ');

            echo json_encode($resultat);
        }
    /**Fin calculer la valeur du Montant du Stock**/

    /**Debut Modifier Stock**/
        if($operation=='modifier_Stock'){
            $unite_nbre=explode("<>", $uniteStock);
            $unite=$unite_nbre[0];
            if($unite=='Article' || $unite=='article'){
                $nombreArticles=1;
                $totalArticleStock= $nombreArticles * $quantite;
            }
            else{
                $nombreArticles=$unite_nbre[1];
                $totalArticleStock= $nombreArticles * $quantite;
            }
    
            $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockinitial=:quantiteStockinitial, totalArticleStock=:totalArticleStock, quantiteStockCourant=:quantiteStockCourant,
            uniteStock =:uniteStock, nbreArticleUniteStock=:nbreArticleUniteStock, prixuniteStock =:prixuniteStock, prixunitaire =:prixunitaire, 
            prixachat =:prixachat, dateExpiration =:dateExpiration
            WHERE idStock = :idStock ");
            $stmtStock_Modifier->execute(array(
                ':idStock' => $idStock,
                ':quantiteStockinitial' => $quantite,
                ':uniteStock' => $unite, 
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':quantiteStockCourant' => $totalArticleStock, 
                ':prixuniteStock' => $prixUniteStock,
                ':prixunitaire' => $prixUnitaire,
                ':prixachat' => $prixAchat,
                ':dateExpiration' => $dateExpiration,
            ));

            $rows = array();
            $rows[] = $idStock;	
            $rows[] = $totalArticleStock;
            $rows[] = $unite.' [x '.$nombreArticles.']';	

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
                ':nbreArticleUniteStock' => $stock['nbreArticleUniteStock'], 
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
                ':nbreArticleUniteStock' => $stock['nbreArticleUniteStock'], 
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