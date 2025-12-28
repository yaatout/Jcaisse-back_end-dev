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
            $rows[] = $produit['forme'];
            $rows[] = $produit['tableau'];
            $rows[] = $produit['prixPublic'];
            $rows[] = $produit['prixSession'];
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

            $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Pharmacie-Detaillant` WHERE designation Like :designation Limit 15");
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
                $stmtReference = $bdd->prepare("SELECT  * FROM `aaa-catalogue-Pharmacie-Detaillant` WHERE designation = :designation OR codeBarreDesignation = :codeBarreDesignation ");
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
                    $rows[] = $reference['forme'];
                    $rows[] = $reference['tableau'];
                    $rows[] = $reference['prixPublic'];
                    $rows[] = $reference['prixSession'];
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
        if($operation=='choix_Forme'){
            $stmtForme = $bdd->prepare("SELECT  * FROM `aaa-forme-Pharmacie-Detaillant`  
            WHERE nomForme <> :nomForme ");
            $stmtForme->execute(array(
                ':nomForme' => $forme
            ));
            $formes = $stmtForme->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($formes as $forme){
                $rows = array();
                $rows[] = $forme['id'];	
                $rows[] = $forme['nomForme'];	
                $data[] = $rows; 
            }
            echo json_encode($data);
        }
    /**Fin Choix Unite Stock**/

    /**Debut Ajouter Produit**/
        if($operation=='ajouter_Produit'){

            $stmtProduit_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, categorie, forme, tableau, prixPublic, prixSession, classe, codeBarreDesignation, archiver)
            VALUES (:designation,:categorie, :forme, :tableau, :prixPublic, :prixSession, :classe, :codeBarreDesignation, :archiver)");
            $stmtProduit_Ajouter->execute(array(
                ':designation' => $reference,
                ':categorie' => $categorie, 
                ':forme' => $forme,
                ':tableau' => $tableau,
                ':prixPublic' => $prixPublic, 
                ':prixSession' => $prixSession,
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
            $rows[] = $produit['forme'];	
            $rows[] = $produit['tableau'];
            $rows[] = $produit['prixPublic'];
            $rows[] = $produit['prixSession'];
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

            $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, forme, totalArticleStock, dateStockage, quantiteStockCourant, prixPublic, prixSession, dateExpiration, iduser)
            VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixPublic, :prixSession, :dateExpiration, :iduser)");
            $stmStock_Ajouter->execute(array(
                ':idDesignation' => $idProduit,
                ':designation' => $produit['designation'],
                ':quantiteStockinitial' => $quantite,
                ':forme' => $produit['forme'], 
                ':totalArticleStock' => $quantite,
                ':dateStockage' => $dateString,
                ':quantiteStockCourant' => $quantite, 
                ':prixPublic' => $produit['prixPublic'],
                ':prixSession' => $produit['prixSession'],
                ':dateExpiration' => $dateExpiration,
                ':iduser' => $_SESSION['iduser']
            ));
            $resultat = $bdd->lastInsertId();

            echo json_encode($resultat);
        }
    /**Fin Ajouter Stock**/

    /**Debut Modifier Produit**/
       if($operation=='modifier_Produit'){

        $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , categorie =:categorie, forme =:forme,
        tableau=:tableau, prixPublic =:prixPublic, prixSession =:prixSession, codeBarreDesignation =:codeBarreDesignation
        WHERE idDesignation = :idDesignation ");
        $stmtProduit_Modifier->execute(array(
            ':idDesignation' => $idProduit,
            ':designation' => $reference,
            ':categorie' => $categorie, 
            ':forme' => $forme,
            ':tableau' => $tableau,
            ':prixPublic' => $prixPublic, 
            ':prixSession' => $prixSession,
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
            $rows[] = $produit['forme'];	
            $rows[] = $produit['tableau'];
            $rows[] = $produit['prixPublic'];
            $rows[] = $produit['prixSession'];
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


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>