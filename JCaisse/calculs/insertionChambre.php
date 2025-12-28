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
    $idChambre=@$_POST["idChambre"];
    $categorie=@$_POST["categorie"];
    $type=@$_POST["type"];
    $reference=@$_POST["reference"];
    $prix=@$_POST["prix"];
    $description=@$_POST["description"];
    $numero=@$_POST["numero"];

    /**Debut Details Produit**/
        if($operation=='details_Chambre'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idChambre
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['codeBarreDesignation'];
            $rows[] = $produit['type'];	
            $rows[] = $produit['categorie'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['description'];

            echo json_encode($rows);
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

    /**Debut Ajouter Produit**/
        if($operation=='ajouter_Chambre'){

            $stmtProduit_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, categorie, type, prix, description, classe, codeBarreDesignation, archiver)
            VALUES (:designation,:categorie, :type, :prix, :description, :classe, :codeBarreDesignation, :archiver)");
            $stmtProduit_Ajouter->execute(array(
                ':designation' => $reference,
                ':categorie' => $categorie, 
                ':type' => $type,
                ':prix' => $prix,
                ':description' => $description,
                ':codeBarreDesignation' => $numero,
                ':classe' => 50,
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
            $rows[] = $produit['codeBarreDesignation'];
            $rows[] = $produit['type'];	
            $rows[] = $produit['categorie'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['description'];

            echo json_encode($rows);
        }
    /**Fin Ajouter Produit**/

    /**Debut Modifier Produit**/
       if($operation=='modifier_Chambre'){

        $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation , categorie =:categorie, type =:type,
        prix =:prix, description =:description, codeBarreDesignation =:codeBarreDesignation
        WHERE idDesignation = :idDesignation ");
        $stmtProduit_Modifier->execute(array(
            ':idDesignation' => $idChambre,
            ':designation' => $reference,
            ':categorie' => $categorie, 
            ':type' => $type,
            ':prix' => $prix,
            ':description' => $description,
            ':codeBarreDesignation' => $numero
        ));

        $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
        $stmtProduit->execute(array(
            ':idDesignation' => $idChambre
        ));
        $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);
        
            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['codeBarreDesignation'];
            $rows[] = $produit['type'];	
            $rows[] = $produit['categorie'];
            $rows[] = $produit['prix'];
            $rows[] = $produit['description'];

        echo json_encode($rows);
      }
    /**Fin Modifier Produit**/ 

    /**Debut Supprimer Produit**/
        if($operation=='supprimer_Chambre'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` WHERE idDesignation = :idDesignation Limit 1 ");
            $stmtPanier->execute(array( ':idDesignation' => $idChambre ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
      
            if($panier){
                $stmtProduit_Archiver = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver = :archiver
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Archiver->execute(array(
                    ':idDesignation' => $idChambre,
                    ':archiver' => 1
                ));
            }
            else {
                $stmtProduit_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                $stmtProduit_Supprimer->execute(array(':idDesignation' => $idChambre ));
            }

            echo json_encode(1);
        }
    /**Fin Supprimer Produit**/ 

    /**Debut Archiver Produit**/
       if($operation=='desarchiver_Chambre'){

            $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver = :archiver
            WHERE idDesignation = :idDesignation ");
            $stmtProduit_Modifier->execute(array(
                ':idDesignation' => $idChambre,
                ':archiver' => 0
            ));

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idChambre
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);	

            echo json_encode($idChambre);
        }
    /**Fin Archiver Produit**/ 


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>