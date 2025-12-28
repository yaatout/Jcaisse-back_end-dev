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
    $reference=@$_POST["reference"];
    $categorie=@$_POST["categorie"];
    $forme=@$_POST["forme"];
    $tableau=@$_POST["tableau"];
    $prixPublic=@$_POST["prixPublic"];
    $prixSession=@$_POST["prixSession"];
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

            $stmtSomme = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` 
            WHERE idDesignation=".$idProduit." GROUP BY idDesignation ");
            $stmtSomme->execute();
            $stock = $stmtSomme->fetch(); 

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, forme, totalArticleStock, dateStockage, quantiteStockCourant, prixPublic, prixSession, dateExpiration, idBl, iduser)
            VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixPublic, :prixSession, :dateExpiration, :idBl, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteStockinitial' => $quantite,
                ':forme' => $produit['forme'], 
                ':totalArticleStock' => $quantite,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => $quantite, 
                ':prixPublic' => $prixPublic,
                ':prixSession' => $prixSession,
                ':dateExpiration' => $dateExpiration,
                ':idBl' => $idBl,
                ':iduser' => $_SESSION['iduser']
            ));

            $stock_Anc = $stock['quantite'] * $produit['prixSession'];
            $stock_Nv = $quantite * $prixSession;
            $cump = round(( $stock_Anc + $stock_Nv ) / ($stock['quantite'] + $quantite)) ;

            $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET  prixSession =:prixSession
            WHERE idDesignation = :idDesignation ");
            $stmtProduit_Modifier->execute(array(
                ':idDesignation' => $idProduit,
                ':prixSession' => $cump
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
            $rows[] = $produit['forme'];	
            $rows[] = $produit['prixPublic'];
            $rows[] = $produit['prixSession'];

            echo json_encode($rows);
        }
    /**Fin Ajouter Stock**/

    /**Debut calculer la valeur du Montant du Stock**/
        if($operation=='stock'){

            $total_prixSession = 0;
            $total_prixPublic = 0;

            $stmtStock = $bdd->prepare("SELECT s.quantiteStockCourant, d.prixSession, d.prixPublic FROM `".$nomtableStock."` s
                LEFT JOIN  `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
                WHERE d.classe=0 AND s.quantiteStockCourant<>0 ");
            $stmtStock->execute();
            $stocks = $stmtStock->fetchAll();
            foreach ($stocks as $stock) {
                $total_prixSession = $total_prixSession + ($stock['quantiteStockCourant'] * $stock['prixSession']);
                $total_prixPublic = $total_prixPublic + ($stock['quantiteStockCourant'] * $stock['prixPublic']);
            }

            $rows = array();
            $rows[] = number_format(($total_prixSession * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($total_prixPublic * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	

            echo json_encode($rows);
        }
    /**Fin calculer la valeur du Montant du Stock**/



}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>