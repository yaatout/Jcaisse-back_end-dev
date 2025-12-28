<?php

session_start();

if(!$_SESSION['iduser']){

header('Location:../index.php');

}

require('../connection.php');

require('../connectionPDO.php');

require('../connectionVitrine.php');

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
    $depot=@$_POST["depot"];
    $dateExpiration=@$_POST["dateExpiration"];

    /**Debut Details Produit**/
        if($operation=='details_Produit'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];
            $rows[] = $produit['categorie'];
            $rows[] = $produit['uniteStock'];
            $rows[] = $produit['nbreArticleUniteStock'];
            $rows[] = $produit['prixuniteStock'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['prixachat'];
            $rows[] = $produit['codeBarreDesignation'];
        
            echo json_encode($rows);
        }
    /**Fin Details Produit**/

    /**Debut Choix Reference**/
        else if($operation=='choix_Reference'){

            $data = [];

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE classe=0 AND designation Like :designation Limit 15");
            $stmtProduit->execute(array(
                'designation'=>"%$query%",
            ));
            $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC);

            foreach($produits as $produit){
                $data[] = '1<>'.$produit['designation'];
            }

            $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Entrepot-Grossiste` WHERE designation Like :designation Limit 15");
            $stmtReference->execute(array(
                'designation'=>"%$query%",
            ));
            $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);

            foreach($references as $reference){
                $data[] = '0<>'.$reference['designation'];
            }

            echo json_encode($data);
        }
    /**Fin Choix Reference**/

    /**Debut Details Produit**/
        else if($operation=='details_Reference'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
            $stmtProduit->execute(array(
                ':designation' => $reference,
                ':codeBarreDesignation' => $codeBarre
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            if($produit){
                $rows = array();
                $rows[] = $produit['idDesignation'];	
                $rows[] = $produit['designation'];
                $rows[] = $produit['codeBarreDesignation'];
                echo json_encode($rows);
            }
            else {
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Entrepot-Grossiste` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
                $stmtReference->execute(array(
                    ':designation' => $reference,
                    ':codeBarreDesignation' => $codeBarre
                ));
                $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

                if($reference){
                    $rows = array();
                    $rows[] = $reference['idFusion'];	
                    $rows[] = $reference['designation'];
                    $rows[] = $reference['categorie'];
                    $rows[] = $reference['uniteStock'];
                    $rows[] = $reference['nbreArticleUniteStock'];
                    $rows[] = $reference['prixuniteStock'];
                    $rows[] = $reference['prix'];
                    $rows[] = $reference['prixAchat'];
                    $rows[] = $reference['codeBarreDesignation'];
                
                    echo json_encode($rows);
                }
                else {
                    echo json_encode(0);
                }
    
            }

        }
    /**Fin Details Produit**/

    /**Debut Choix Categorie**/
        else if($operation=='choix_Categorie'){
            $stmtCategorie = $bdd->prepare("SELECT  * FROM `".$nomtableCategorie."` 
            WHERE nomcategorie <> :categorie ");
            $stmtCategorie->execute(array(
                ':categorie' => $categorie
            ));
            $categories = $stmtCategorie->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($categories as $categorie){
                $rows = array();
                $rows[] = $categorie['idcategorie'];	
                $rows[] = $categorie['nomcategorie'];	
                $data[] = $rows; 
            }
            echo json_encode($data);
        }
    /**Fin Choix Categorie**/

    /**Debut Choix Unite Stock**/
        else if($operation=='choix_UniteStock'){
            $stmtUniteStock = $bdd->prepare("SELECT  * FROM `aaa-unitestock`  
            WHERE nomUniteStock <> :uniteStock ");
            $stmtUniteStock->execute(array(
                ':uniteStock' => $uniteStock
            ));
            $unites = $stmtUniteStock->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($unites as $unite){
                $rows = array();
                $rows[] = $unite['idUniteStock'];	
                $rows[] = $unite['nomUniteStock'];	
                $data[] = $rows; 
            }
            echo json_encode($data);
        }
    /**Fin Choix Unite Stock**/

    /**Debut Ajouter Produit**/
        else if($operation=='ajouter_Produit'){

            if($uniteStock=='Article' || $uniteStock=='article' || strtolower($uniteStock)=='article') {
                //$prixUniteStock = $prixUnitaire;
                $nombreArticles = 1;
            }

            $stmtProduit_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, categorie, uniteStock, nbreArticleUniteStock, prixuniteStock, prix, prixachat,classe, codeBarreDesignation, archiver)
            VALUES (:designation,:categorie, :uniteStock, :nbreArticleUniteStock, :prixuniteStock, :prixunitaire, :prixachat, :classe, :codeBarreDesignation, :archiver)");
            $stmtProduit_Ajouter->execute(array(
                ':designation' => $reference,
                ':categorie' => $categorie, 
                ':uniteStock' => $uniteStock,
                ':nbreArticleUniteStock' => $nombreArticles,
                ':prixuniteStock' => $prixUniteStock,
                ':prixunitaire' => $prixUnitaire,
                ':prixachat' => $prixAchat,
                ':codeBarreDesignation' => $codeBarre,
                ':classe' => 0,
                ':archiver' => 0,
            ));
            $last_idProduit = $bdd->lastInsertId();

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $last_idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            if ($_SESSION['vitrine']==1) {
                
                $stmtProduit_AjouterVitrine = $bddV->prepare("INSERT INTO `".$nomtableDesignation."` (idDesignation,idBoutique, designation, designationJcaisse, categorie, uniteStock, nbreArticleUniteStock, prixuniteStock,classe)
                VALUES (:idDesignation,:idBoutique,:designation,:designationJ,:categorie, :uniteStock, :nbreArticleUniteStock, :prixuniteStock, :classe)");
                $stmtProduit_AjouterVitrine->execute(array(
                    ':idDesignation' => $produit['idDesignation'],
                    ':idBoutique' => $_SESSION['idBoutique'],
                    ':designation' => $reference,
                    ':designationJ' => $reference,
                    ':categorie' => $categorie, 
                    ':uniteStock' => $uniteStock,
                    ':nbreArticleUniteStock' => $nombreArticles,
                    ':prixuniteStock' => $prixUniteStock,
                    ':classe' => 0
                ));
            }
            
            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['categorie'];
            $rows[] = $produit['uniteStock'];	
            $rows[] = $produit['nbreArticleUniteStock'];
            $rows[] = $produit['prixuniteStock'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['prixachat'];
            $rows[] = $produit['codeBarreDesignation'];

            echo json_encode($rows);
        }
    /**Fin Ajouter Produit**/

    /**Debut Ajouter Stock**/
        else if($operation=='ajouter_Stock'){

            $resultat = 0;

            $stmProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmProduit->fetch(PDO::FETCH_ASSOC);

            if ($uniteStock=='Article' || $uniteStock=='article' || strtolower($uniteStock)=='article') {
                $nombreArticles=1;
                $totalArticleStock= $nombreArticles * $quantite;
            }
            else {
                $nombreArticles=$produit['nbreArticleUniteStock'];
                $totalArticleStock= $nombreArticles * $quantite;
            }

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, dateExpiration, iduser)
            VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixachat, :dateExpiration, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteStockinitial' => $quantite,
                ':uniteStock' => $produit['uniteStock'], 
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => $totalArticleStock, 
                ':prixuniteStock' => $produit['prixuniteStock'],
                ':prixachat' => $produit['prixachat'],
                ':dateExpiration' => $dateExpiration,
                ':iduser' => $_SESSION['iduser']
            ));
            $resultat = $bdd->lastInsertId();

            if ($depot!=null || $depot!=0) {
                
            // var_dump("depot : ".$depot);
                $stmEtStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableEntrepotStock."` (idStock,idEntrepot,idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, dateExpiration, iduser)
                VALUES (:idStock,:idEntrepot,:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :dateExpiration, :iduser)");
                $stmEtStock_Ajouter->execute(array(
                    ':idStock' => $resultat,
                    ':idEntrepot' => $depot,
                    ':idDesignation' => $idProduit,
                    ':designation' => $produit['designation'],
                    ':quantiteStockinitial' => $quantite,
                    ':uniteStock' => $produit['uniteStock'], 
                    ':nbreArticleUniteStock' => $nombreArticles,
                    ':totalArticleStock' => $totalArticleStock,
                    ':dateStockage' => $dateString,
                    ':quantiteStockCourant' => $totalArticleStock, 
                    ':prixuniteStock' => $produit['prixuniteStock'],
                    ':dateExpiration' => $dateExpiration,
                    ':iduser' => $_SESSION['iduser']
                )) or die(print_r($stmEtStock_Ajouter->errorInfo()));
                
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant =:quantiteStockCourant 
                WHERE idStock = :idStock ");
                $stmtStock_Modifier->execute(array(
                    ':idStock' => $resultat,
                    ':quantiteStockCourant' => 0
                ));
            }


            echo json_encode($resultat);
        }
    /**Fin Ajouter Stock**/

    /**Debut Modifier Produit**/
       else if($operation=='modifier_Produit'){

        if($uniteStock=='Article' || $uniteStock=='article' || strtolower($uniteStock)=='article'){
            //$prixUniteStock = $prixUnitaire;
            $nombreArticles = 1;
        }

        $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , categorie =:categorie, uniteStock =:uniteStock,
        nbreArticleUniteStock=:nbreArticleUniteStock, prixuniteStock =:prixuniteStock, prix =:prixunitaire, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
        WHERE idDesignation = :idDesignation ");
        $stmtProduit_Modifier->execute(array(
            ':idDesignation' => $idProduit,
            ':designation' => $reference,
            ':categorie' => $categorie, 
            ':uniteStock' => $uniteStock,
            ':nbreArticleUniteStock' => $nombreArticles,
            ':prixuniteStock' => $prixUniteStock, 
            ':prixunitaire' => $prixUnitaire,
            ':prixachat' => $prixAchat,
            ':codeBarreDesignation' => $codeBarre
        ));

        $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET designation =:designation
        WHERE idDesignation = :idDesignation ");
        $stmtStock_Modifier->execute(array(
            ':idDesignation' => $idProduit,
            ':designation' => $reference
        ));

        $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET designation =:designation
        WHERE idDesignation = :idDesignation ");
        $stmtEntrepotStock_Modifier->execute(array(
            ':idDesignation' => $idProduit,
            ':designation' => $reference
        ));

        $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
        $stmtProduit->execute(array(
            ':idDesignation' => $idProduit
        ));
        $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);
        
            if ($_SESSION['vitrine']==1) {
                
                $stmtProduit_ModifierVitrine = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation, designationJcaisse =:designationJ, categorie =:categorie, uniteStock =:uniteStock,
                nbreArticleUniteStock=:nbreArticleUniteStock, prixuniteStock =:prixuniteStock
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_ModifierVitrine->execute(array(
                    ':idDesignation' => $idProduit,
                    ':designation' => $reference,
                    ':designationJ' => $reference,
                    ':categorie' => $categorie, 
                    ':uniteStock' => $uniteStock,
                    ':nbreArticleUniteStock' => $nombreArticles,
                    ':prixuniteStock' => $prixUniteStock
                ));
            }
        
            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['categorie'];
            $rows[] = $produit['uniteStock'];	
            $rows[] = $produit['nbreArticleUniteStock'];
            $rows[] = $produit['prixuniteStock'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['prixachat'];
            $rows[] = $produit['codeBarreDesignation'];

        echo json_encode($rows);
      }
    /**Fin Modifier Produit**/ 

    /**Debut Supprimer Produit**/
        else if($operation=='supprimer_Produit'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` WHERE idDesignation = :idDesignation Limit 1 ");
            $stmtPanier->execute(array( ':idDesignation' => $idProduit ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
      
            if($panier){
                $stmtProduit_Archiver = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver = :archiver
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Archiver->execute(array(
                    ':idDesignation' => $idProduit,
                    ':archiver' => 1
                ));

            }
            else {
                $stmtStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock_Supprimer->execute(array(':idDesignation' => $idProduit ));

                $stmtProduit_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                $stmtProduit_Supprimer->execute(array(':idDesignation' => $idProduit ));
            }

            if ($_SESSION['vitrine']==1) {
                
                $stmtProduit_Supprimer = $bddV->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                $stmtProduit_Supprimer->execute(array(':idDesignation' => $idProduit ));

            }

            echo json_encode(1);
        }
    /**Fin Supprimer Produit**/ 

    /**Debut Archiver Produit**/
       else if($operation=='desarchiver_Produit'){

            $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver = :archiver
            WHERE idDesignation = :idDesignation ");
            $stmtProduit_Modifier->execute(array(
                ':idDesignation' => $idProduit,
                ':archiver' => 0
            ));

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);	

            echo json_encode($idProduit);
        }
    /**Fin Archiver Produit**/ 

    /**Debut Choix depot**/
        else if($operation=='choix_Depot'){
            $stmtDepot = $bdd->prepare("SELECT  * FROM `".$nomtableEntrepot."`");
            $stmtDepot->execute();
            $depots = $stmtDepot->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($depots as $depot){
                $rows = array();
                $rows[] = $depot['idEntrepot'];	
                $rows[] = $depot['nomEntrepot'];	
                $data[] = $rows; 
            }
            echo json_encode($data);
        }
    /**Fin Choix depot**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>