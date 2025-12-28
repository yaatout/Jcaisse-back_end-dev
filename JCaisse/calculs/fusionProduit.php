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
    $designation=@$_POST["designation"];
    $codeBarre=@$_POST["codeBarre"];
    $quantite=@$_POST["quantite"];

    $uniteStock=@$_POST["uniteStock"];
    $nombreArticles=@$_POST["nombreArticleUS"];
    $prixUniteStock=@$_POST["prixUniteStock"];
    $prixUnitaire=@$_POST["prixUnitaire"];
    $prixAchat=@$_POST["prixAchat"];

    $forme=@$_POST["forme"];
    $tableau=@$_POST["tableau"];
    $prixSession=@$_POST["prixSession"];
    $prixPublic=@$_POST["prixPublic"];

  
    /**Debut Ajouter Stock**/
        if($operation=='ajouter_Produit'){

            $resultat = 0;


            $stmProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmProduit->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                $stmtStock = $bdd->prepare("SELECT SUM(quantiteStockinitial) as total FROM `".$nomtableStock."` 
                   WHERE idDesignation=:idDesignation");
                $stmtStock->bindValue(':idDesignation', $produit['idDesignation'], PDO::PARAM_INT);
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
       
             }
             else {
       
                $stmtStock = $bdd->prepare("SELECT SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` 
                WHERE idDesignation=:idDesignation");
                $stmtStock->bindValue(':idDesignation', $produit['idDesignation'], PDO::PARAM_INT);
                $stmtStock->execute();
                $stock = $stmtStock->fetch(); 
       
             }
       
             if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $rows = array();
                $rows['type'] = 2;
                $rows['idDesignation'] = $produit['idDesignation'];	
                $rows['designation'] = $produit['designation'];	
                $rows['codeBarreDesignation'] = $produit['codeBarreDesignation'];
                $rows['quantite'] = $stock['quantite'];	
                $rows['forme'] = $produit['forme'];
                $rows['tableau'] = $produit['tableau'];	
                $rows['prixSession'] = $produit['prixSession'];
                $rows['prixPublic'] = $produit['prixPublic'];	

             }
             else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $rows = array();
                $rows['type'] = 1;
                $rows['idDesignation'] = $produit['idDesignation'];	
                $rows['designation'] = $produit['designation'];	
                $rows['codeBarreDesignation'] = $produit['codeBarreDesignation'];
                $rows['surdepot'] = $cols_quantite;	
                $rows['sansdepot'] = ($stock['total'] - $entrepot['total']);	
                $rows['uniteStock'] = $produit['uniteStock'];
                $rows['nbreArticleUniteStock'] = $produit['nbreArticleUniteStock'];	
                $rows['prixuniteStock'] = $produit['prixuniteStock'];
                $rows['prixachat'] = $produit['prixachat'];
             }
             else{
                $rows = array();
                $rows['type'] = 0;
                $rows['idDesignation'] = $produit['idDesignation'];	
                $rows['designation'] = $produit['designation'];	
                $rows['codeBarreDesignation'] = $produit['codeBarreDesignation'];
                $rows['quantite'] = $stock['quantite'];		
                $rows['uniteStock'] = $produit['uniteStock'];
                $rows['nbreArticleUniteStock'] = $produit['nbreArticleUniteStock'];	
                $rows['prixuniteStock'] = $produit['prixuniteStock'];
                $rows['prix'] = $produit['prix'];
                $rows['prixachat'] = $produit['prixachat'];

             }

            $_SESSION['fusions'][] = $rows;

            echo json_encode($rows);
        }
    /**Fin Ajouter Stock**/

    /**Debut Supprimer Stock**/
        if($operation=='supprimer_Produit'){

            function find_p_with_position($pns,$des) {
                foreach($pns as $index => $p) {
                    if(($p['idDesignation'] == $des)){
                        return $index;
                    }
                } 
                return FALSE;
            }

            $i=find_p_with_position($_SESSION['fusions'], $idProduit);
                     
            unset($_SESSION['fusions'][$i]);  

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $resultat=2;
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $resultat=1;
            }
            else{
                $resultat=0;
            }

            echo json_encode($resultat);
        }
    /**Fin Supprimer Stock**/

    /**Debut Supprimer Stock**/
        if($operation=='fusion_Produit'){

            function find_p_with_position($pns,$des) {
                foreach($pns as $index => $p) {
                    if(($p['idDesignation'] == $des)){
                        return $index;
                    }
                } 
                return FALSE;
            }

            $produits = $_SESSION['fusions'];

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);
         
            foreach ($produits as $rows => $row) {
                if($row['idDesignation']!=$idProduit){

                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                        $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                        $stmtStock_Modifier->execute(array(
                            ':nouveau' => $idProduit,
                            ':ancien' => $row['idDesignation']
                        ));

                        $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET idDesignation =:nouveau, quantiteStockCourant=0 WHERE idDesignation = :ancien ");
                        $stmtEntrepotStock_Modifier->execute(array(
                            ':nouveau' => $idProduit,
                            ':ancien' => $row['idDesignation']
                        ));

                        if (($row['surdepot']!=0 && $row['surdepot']!='' && $row['surdepot']!=null) &&  ($row['sansdepot']!=0 && $row['sansdepot']!='' && $row['sansdepot']!=null)){
                            $total=$row['surdepot'] + $row['sansdepot'];
                            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, type, iduser)
                            VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :type, :iduser)");
                            $stmStock_Ajouter->execute(array(
                                ':idDesignation' => $idProduit,
                                ':quantite' => 0,
                                ':nbreArticleUniteStock' => 1,
                                ':quantiteStockCourant' => $total, 
                                ':dateInventaire' => $dateString,
                                ':type' => 20,
                                ':iduser' => $_SESSION['iduser']
                            ));
                        }

                    }
                    else{
                        $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET idDesignation =:nouveau, quantiteStockCourant=0 WHERE idDesignation = :ancien ");
                        $stmtStock_Modifier->execute(array(
                            ':nouveau' => $idProduit,
                            ':ancien' => $row['idDesignation']
                        ));

                        if($row['quantite']!=0 && $row['quantite']!='' && $row['quantite']!=null){
                            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, type, iduser)
                            VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :type, :iduser)");
                            $stmStock_Ajouter->execute(array(
                                ':idDesignation' => $idProduit,
                                ':quantite' => 0,
                                ':nbreArticleUniteStock' => 1,
                                ':quantiteStockCourant' => $row['quantite'], 
                                ':dateInventaire' => $dateString,
                                ':type' => 20,
                                ':iduser' => $_SESSION['iduser']
                            ));
                        }

                    }
                    
                    $stmtInventaire_Modifier = $bdd->prepare("UPDATE `".$nomtableInventaire."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                    $stmtInventaire_Modifier->execute(array(
                        ':nouveau' => $idProduit,
                        ':ancien' => $row['idDesignation']
                    ));

                    $stmtLigne_Modifier = $bdd->prepare("UPDATE `".$nomtableLigne."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                    $stmtLigne_Modifier->execute(array(
                        ':nouveau' => $idProduit,
                        ':ancien' => $row['idDesignation']
                    ));
    
                    $stmtProduit_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                    $stmtProduit_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));
    
                    $i=find_p_with_position($_SESSION['fusions'], $row['idDesignation']);
                         
                    unset($_SESSION['fusions'][$i]); 
                }
            }

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , forme =:forme,
                tableau=:tableau, prixSession =:prixSession, prixPublic =:prixPublic, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation,
                    ':codeBarreDesignation' => $codeBarre,
                    ':forme' => $forme,
                    ':tableau' => $tableau,
                    ':prixSession' => $prixSession, 
                    ':prixPublic' => $prixPublic
                ));
        
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET designation =:designation, quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation
                ));

                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, forme, totalArticleStock, dateStockage, quantiteStockCourant, prixPublic, prixSession,iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixPublic, :prixSession, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $designation,
                        ':quantiteStockinitial' => $quantite,
                        ':forme' => $forme, 
                        ':totalArticleStock' => $quantite,
                        ':dateStockage' => $dateString,
                        ':quantiteStockCourant' => $quantite, 
                        ':prixPublic' => $prixPublic,
                        ':prixSession' => $prixSession,
                        ':iduser' => $_SESSION['iduser']
                    ));
                }
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , uniteStock =:uniteStock,
                nbreArticleUniteStock=:nbreArticleUniteStock, prixuniteStock =:prixuniteStock, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation,
                    ':uniteStock' => $uniteStock,
                    ':nbreArticleUniteStock' => $nombreArticles,
                    ':prixuniteStock' => $prixUniteStock,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));

                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET designation =:designation
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation
                ));

                $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET designation =:designation, quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtEntrepotStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $totalArticleStock = $quantite * $nombreArticles;
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $designation,
                        ':quantiteStockinitial' => $quantite,
                        ':uniteStock' => $produit['uniteStock'],
                        ':nbreArticleUniteStock' => $nombreArticles,
                        ':totalArticleStock' => $totalArticleStock,
                        ':dateStockage' => $dateString,
                        ':quantiteStockCourant' => $totalArticleStock, 
                        ':prixuniteStock' => $prixUniteStock,
                        ':prixachat' => $prixAchat,
                        ':iduser' => $_SESSION['iduser']
                    ));
                }
            }
            else{
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , uniteStock =:uniteStock,
                nbreArticleUniteStock=:nbreArticleUniteStock, prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation,
                    ':uniteStock' => $uniteStock,
                    ':nbreArticleUniteStock' => $nombreArticles,
                    ':prixuniteStock' => $prixUniteStock, 
                    ':prix' => $prixUnitaire,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));
        
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET designation =:designation, quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $designation
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixunitaire, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $designation,
                        ':quantiteStockinitial' => $quantite,
                        ':uniteStock' => 'Article', 
                        ':nbreArticleUniteStock' => 1,
                        ':totalArticleStock' => $quantite,
                        ':dateStockage' => $dateString,
                        ':quantiteStockCourant' => $quantite, 
                        ':prixuniteStock' => $prixUniteStock,
                        ':prixunitaire' => $prixUnitaire,
                        ':prixachat' => $prixAchat,
                        ':iduser' => $_SESSION['iduser']
                    ));
                }
            }

            $i=find_p_with_position($_SESSION['fusions'], $idProduit);
                         
            unset($_SESSION['fusions'][$i]); 

            echo json_encode(1);
        }
    /**Fin Supprimer Stock**/



}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>