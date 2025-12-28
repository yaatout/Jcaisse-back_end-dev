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

            $stmtSomme = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` 
            WHERE idDesignation=".$idProduit." GROUP BY idDesignation ");
            $stmtSomme->execute();
            $stock = $stmtSomme->fetch(); 

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

            $stock_Anc = $stock['quantite'] * $produit['prixachat'];
            $stock_Nv = $totalArticleStock * $prixAchat;
            $cump = round(( $stock_Anc + $stock_Nv ) / ($stock['quantite'] + $totalArticleStock)) ;

            $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET  prixachat =:prixachat
            WHERE idDesignation = :idDesignation ");
            $stmtProduit_Modifier->execute(array(
                ':idDesignation' => $idProduit,
                ':prixachat' => $cump
            ));

            $stmtStock= $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."`  WHERE idDesignation=:idDesignation");
            $stmtStock->bindValue(':idDesignation', $idProduit, PDO::PARAM_INT);
            $stmtStock->execute();
            $stock = $stmtStock->fetch(); 

            
            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['codeBarreDesignation'];
            $rows[] = $stock['quantite'];
            $rows[] = $produit['uniteStock'];	
            $rows[] = $produit['nbreArticleUniteStock'];
            $rows[] = $produit['prixuniteStock'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['prixachat'];


            echo json_encode($rows);
        }
    /**Fin Ajouter Stock**/

    /**Debut calculer la valeur du Montant du Stock**/
        if($operation=='stock'){

            $total_prix_Achat = 0;
            $total_prix_Unitaire = 0;

            $stmtStock = $bdd->prepare("SELECT s.quantiteStockCourant, d.prix, d.prixachat FROM `".$nomtableStock."` s
            LEFT JOIN  `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
            WHERE d.classe=0 AND s.quantiteStockCourant<>0 AND d.archiver<>1 ");
            $stmtStock->execute();
            $stocks = $stmtStock->fetchAll();
            foreach ($stocks as $stock) {
                    $total_prix_Achat = $total_prix_Achat + ($stock['quantiteStockCourant'] * $stock['prixachat']);
                    $total_prix_Unitaire = $total_prix_Unitaire + ($stock['quantiteStockCourant'] * $stock['prix']);
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