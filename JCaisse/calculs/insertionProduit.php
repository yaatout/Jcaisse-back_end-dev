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

    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];

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
        if($operation=='choix_Reference'){

            $data = [];

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE classe=0 AND designation Like :designation Limit 15");
            $stmtProduit->execute(array(
                'designation'=>"%$query%",
            ));
            $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC);

            foreach($produits as $produit){
                $data[] = '1<>'.$produit['designation'];
            }

            if($_SESSION['type']=="Divers"){
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Divers-Detaillant` WHERE designation Like :designation Limit 15");
                $stmtReference->execute(array(
                    'designation'=>"%$query%",
                ));
                $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
            }
            else if($_SESSION['type']=="Multi-service"){
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Multi-service-Detaillant` WHERE designation Like :designation Limit 15");
                $stmtReference->execute(array(
                    'designation'=>"%$query%",
                ));
                $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
            }
            else if($_SESSION['type']=="Electronique"){
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Electronique-Detaillant` WHERE designation Like :designation Limit 15");
                $stmtReference->execute(array(
                    'designation'=>"%$query%",
                ));
                $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
            }
            else if($_SESSION['type']=="Habillement"){
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Habillement-Detaillant` WHERE designation Like :designation Limit 15");
                $stmtReference->execute(array(
                    'designation'=>"%$query%",
                ));
                $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
            }
            else {
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Superette-Detaillant` WHERE designation Like :designation Limit 15");
                $stmtReference->execute(array(
                    'designation'=>"%$query%",
                ));
                $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
            }

            foreach($references as $reference){
                $data[] = '0<>'.$reference['designation'];
            }

            echo json_encode($data);
        }
    /**Fin Choix Reference**/

    /**Debut Details Reference**/
        if($operation=='details_Reference'){

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

                if($_SESSION['type']=="Divers"){
                    $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Divers-Detaillant` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
                    $stmtReference->execute(array(
                        ':designation' => $reference,
                        ':codeBarreDesignation' => $codeBarre
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC); 
                }
                else if($_SESSION['type']=="Multi-service"){
                    $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Multi-service-Detaillant` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
                    $stmtReference->execute(array(
                        ':designation' => $reference,
                        ':codeBarreDesignation' => $codeBarre
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC); 
                }
                else if($_SESSION['type']=="Electronique"){
                    $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Electronique-Detaillant` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
                    $stmtReference->execute(array(
                        ':designation' => $reference,
                        ':codeBarreDesignation' => $codeBarre
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);   
                }
                else if($_SESSION['type']=="Habillement"){
                    $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Habillement-Detaillant` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
                    $stmtReference->execute(array(
                        ':designation' => $reference,
                        ':codeBarreDesignation' => $codeBarre
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);    
                }
                else {
                    $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Superette-Detaillant` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
                    $stmtReference->execute(array(
                        ':designation' => $reference,
                        ':codeBarreDesignation' => $codeBarre
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);
                }

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
    /**Fin Details Reference**/

    /**Debut Choix Categorie**/
        if($operation=='choix_Categorie'){
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
        if($operation=='choix_UniteStock'){
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
        if($operation=='ajouter_Produit'){

            if($uniteStock=='Article' || $uniteStock=='article'){
                $prixUniteStock = $prixUnitaire;
                $nombreArticles = 1;
            }

            $stmtProduit_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, categorie, uniteStock, nbreArticleUniteStock, prixuniteStock, prix, prixachat,classe, codeBarreDesignation, archiver)
            VALUES (:designation,:categorie, :uniteStock, :nbreArticleUniteStock, :prixuniteStock, :prix, :prixachat, :classe, :codeBarreDesignation, :archiver)");
            $stmtProduit_Ajouter->execute(array(
                ':designation' => $reference,
                ':categorie' => $categorie, 
                ':uniteStock' => $uniteStock,
                ':nbreArticleUniteStock' => $nombreArticles,
                ':prixuniteStock' => $prixUniteStock, 
                ':prix' => $prixUnitaire,
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

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixunitaire, prixachat, dateExpiration, iduser)
            VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :dateExpiration, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteStockinitial' => $quantite,
                ':uniteStock' => $uniteStock, 
                ':nbreArticleUniteStock' => $nombreArticles,
                ':totalArticleStock' => $totalArticleStock,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => $totalArticleStock, 
                ':prixuniteStock' => $produit['prixuniteStock'],
                ':prixunitaire' => $produit['prix'],
                ':prixachat' => $produit['prixachat'],
                ':dateExpiration' => $dateExpiration,
                ':iduser' => $_SESSION['iduser']
            ));
            $resultat = $bdd->lastInsertId();

            echo json_encode($resultat);
        }
    /**Fin Ajouter Stock**/

    /**Debut Modifier Produit**/
       if($operation=='modifier_Produit'){

        if($uniteStock=='Article' || $uniteStock=='article'){
            $prixUniteStock = $prixUnitaire;
            $nombreArticles = 1;
        }

        $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , categorie =:categorie, uniteStock =:uniteStock,
        nbreArticleUniteStock=:nbreArticleUniteStock, prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
        WHERE idDesignation = :idDesignation ");
        $stmtProduit_Modifier->execute(array(
            ':idDesignation' => $idProduit,
            ':designation' => $reference,
            ':categorie' => $categorie, 
            ':uniteStock' => $uniteStock,
            ':nbreArticleUniteStock' => $nombreArticles,
            ':prixuniteStock' => $prixUniteStock, 
            ':prix' => $prixUnitaire,
            ':prixachat' => $prixAchat,
            ':codeBarreDesignation' => $codeBarre
        ));

        $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET designation =:designation
        WHERE idDesignation = :idDesignation ");
        $stmtStock_Modifier->execute(array(
            ':idDesignation' => $idProduit,
            ':designation' => $reference
        ));

        $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
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
    /**Fin Modifier Produit**/ 

    /**Debut Supprimer Produit**/
        if($operation=='supprimer_Produit'){

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

            echo json_encode(1);
        }
    /**Fin Supprimer Produit**/ 

    /**Debut Archiver Produit**/
       if($operation=='desarchiver_Produit'){

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

    /**Debut calculer la valeur du Montant du Produit**/
        if($operation=='produit'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);
            
            $montantVente = 0; $montantBon = 0; $quantiteSorties=0;  $quantiteEntrees=0;

            $stmtLigne = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l
            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
            WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtLigne->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtLigne->execute();
            $lignes = $stmtLigne->fetchAll();
            foreach ($lignes as $ligne) {
                if($ligne['unitevente']=='Article' || $ligne['unitevente']=='article' ){
                    $quantiteSorties = $quantiteSorties + $ligne['quantite'];
                }
                else {
                    $quantiteSorties = $quantiteSorties + ($ligne['quantite'] * $produit['nbreArticleUniteStock']);
                }

                if($ligne['idClient']!=0){
                    $montantBon = $montantBon + $ligne['prixtotal'];
                }
                else{
                    $montantVente = $montantVente + $ligne['prixtotal'];
                }
            }

            $stmtStock= $bdd->prepare("SELECT * FROM `".$nomtableStock."` 
            WHERE idDesignation=:idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
            $stmtStock->bindValue(':idDesignation', (int)$idProduit, PDO::PARAM_INT);
            $stmtStock->execute();
            $stocks = $stmtStock->fetchAll();
            foreach ($stocks as $stock) {
                $quantiteEntrees = $quantiteEntrees + $stock['totalArticleStock'];
            }

            $rows = array();
            $rows[] = number_format(($quantiteEntrees * $_SESSION['devise']), 0, ',', ' ');	
            $rows[] = number_format(($quantiteSorties * $_SESSION['devise']), 0, ',', ' ');
            $rows[] = number_format(($montantVente * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($montantBon * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'];

            echo json_encode($rows);
        }
    /**Fin calculer la valeur du Montant du Produit**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>