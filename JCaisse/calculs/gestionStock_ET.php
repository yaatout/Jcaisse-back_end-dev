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
    $entrepot=@$_POST["entrepot"];
    $dateExpiration=@$_POST["dateExpiration"];
    $idFournisseur=@$_POST["idFournisseur"];
    $idBl=@$_POST["idBl"];

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

    /**Debut Choix BL**/
        if($operation=='choix_BL'){
            $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableBl."` ORDER BY idBl DESC");
            $stmtBL->execute();
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
    /**Fin Choix BL**/

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

            $stmtStock= $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableStock."`  WHERE idDesignation=:idDesignation");
            $stmtStock->bindValue(':idDesignation', $idProduit, PDO::PARAM_INT);
            $stmtStock->execute();
            $stock_I = $stmtStock->fetch(); 

            $stmtDepot = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableEntrepotStock."` 
                WHERE idDesignation=:idDesignation");
            $stmtDepot->bindValue(':idDesignation', $produit['idDesignation'], PDO::PARAM_INT);
            $stmtDepot->execute();
            $depot_C = $stmtDepot->fetch(); 
    
            $stmtEntrepot = $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableEntrepotStock."` 
            WHERE idDesignation=:idDesignation AND (idTransfert=0 OR idTransfert IS NULL) ");
            $stmtEntrepot->bindValue(':idDesignation', $produit['idDesignation'], PDO::PARAM_INT);
            $stmtEntrepot->execute();
            $entrepot_I = $stmtEntrepot->fetch(); 

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, dateExpiration, idBl, iduser)
            VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :prixachat, :dateExpiration, :idBl, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteStockinitial' => $quantite,
                ':uniteStock' => $produit['uniteStock'],
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => $totalArticleStock, 
                ':prixuniteStock' => $prixUniteStock,
                ':prixachat' => $prixAchat,
                ':dateExpiration' => $dateExpiration,
                ':idBl' => $idBl,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idStock = $bdd->lastInsertId();

            if($entrepot!=0 && $entrepot!=null){
                $stmStock_Entrepot = $bdd->prepare("INSERT INTO `".$nomtableEntrepotStock."` (idStock,idEntrepot,idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, dateExpiration, iduser)
                VALUES (:idStock,:idEntrepot,:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :dateExpiration, :iduser)");
                $stmStock_Entrepot->execute(array(
                    ':idStock' => $last_idStock,
                    ':idEntrepot' => $entrepot,
                    ':idDesignation' => $idProduit,
                    ':designation' => $produit['designation'],
                    ':quantiteStockinitial' => $quantite,
                    ':uniteStock' => $produit['uniteStock'],
                    ':nbreArticleUniteStock' => $nombreArticles,
                    ':totalArticleStock' => $totalArticleStock,
                    ':dateStockage' => $dateString,
                    ':quantiteStockCourant' => $totalArticleStock, 
                    ':prixuniteStock' => $prixUniteStock,
                    ':dateExpiration' => $dateExpiration,
                    ':iduser' => $_SESSION['iduser']
                ));
            }

            if ($_SESSION['idBoutique']==194) {
                
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET  prixachat =:prixachat, prixuniteStock=:pus, prix=:pu
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixachat' => $prixAchat/$produit['nbreArticleUniteStock'],
                    ':pus' => $prixUniteStock,
                    ':pu' => $prixUniteStock/$produit['nbreArticleUniteStock']
                ));
                
            } else {
                $qte_stock = $depot_C['quantite'] + ($stock_I['total'] - $entrepot_I['total']);
                $stock_Anc = $qte_stock * $produit['prixachat'];
                $stock_Nv = $totalArticleStock * $prixAchat;
                $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock + $totalArticleStock)) ;

                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET  prixachat =:prixachat
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixachat' => $cump
                ));
                
            }

            $stmtStock= $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableStock."`  WHERE idDesignation=:idDesignation");
            $stmtStock->bindValue(':idDesignation', $idProduit, PDO::PARAM_INT);
            $stmtStock->execute();
            $stock = $stmtStock->fetch(); 

            $stmtDepot = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableEntrepotStock."` 
                WHERE idDesignation=:idDesignation");
            $stmtDepot->bindValue(':idDesignation', $produit['idDesignation'], PDO::PARAM_INT);
            $stmtDepot->execute();
            $depot = $stmtDepot->fetch(); 
    
            $stmtEntrepot = $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableEntrepotStock."` 
            WHERE idDesignation=:idDesignation AND (idTransfert=0 OR idTransfert IS NULL) ");
            $stmtEntrepot->bindValue(':idDesignation', $produit['idDesignation'], PDO::PARAM_INT);
            $stmtEntrepot->execute();
            $entrepot = $stmtEntrepot->fetch(); 
    
            if($produit['nbreArticleUniteStock']!=0 || $produit['nbreArticleUniteStock']!=null){
                if($depot['quantite']!=0 && $depot['quantite']!=null){
                $cols_quantite = ($depot['quantite'] / $produit['nbreArticleUniteStock']) ;
                }
                else{
                $cols_quantite = 0;
                }
            }
            else{
                if($depot['quantite']!=0 && $depot['quantite']!=null){
                $cols_quantite = $depot['quantite'];
                }
                else{
                $cols_quantite = 0;
                }
            }

            
            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $stock['total'] - $entrepot['total'];
            $rows[] = $cols_quantite;
            $rows[] = $produit['uniteStock'];	
            $rows[] = $produit['nbreArticleUniteStock'];
            $rows[] = $produit['prixuniteStock'];
            $rows[] = $produit['prixachat'];


            echo json_encode($rows);
        }
    /**Fin Ajouter Stock**/

    /**Debut calculer la valeur du Montant du Stock**/
        if($operation=='stock'){

            $total_prix_Achat = 0;
            $total_prix_Unitaire = 0;

            $stmtStock = $bdd->prepare("SELECT s.quantiteStockCourant,d.nbreArticleUniteStock,d.prixuniteStock,d.prixuniteStock,d.prixachat FROM `".$nomtableEntrepotStock."` s
            LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
            WHERE d.classe=0 ORDER BY s.idEntrepotStock DESC ");
            $stmtStock->execute();
            $stocks = $stmtStock->fetchAll();
            foreach ($stocks as $stock) {
                if($stock['quantiteStockCourant']!=null AND $stock['quantiteStockCourant']!=0 AND $stock['quantiteStockCourant']>0){
                    $quantite=$stock['quantiteStockCourant'] / $stock['nbreArticleUniteStock'];
                }
                else{
                    $quantite=0;
                }
                $total_prix_Achat = $total_prix_Achat + ($quantite * $stock['prixachat']);
                $total_prix_Unitaire = $total_prix_Unitaire + ($quantite * $stock['prixuniteStock']);
            }

            $rows = array();
            $rows[] = number_format(($total_prix_Achat * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($total_prix_Unitaire * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	

            echo json_encode($rows);
        }
    /**Fin calculer la valeur du Montant du Stock**/



}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>