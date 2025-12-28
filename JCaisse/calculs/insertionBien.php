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
    $idBien=@$_POST["idBien"];
    $type=@$_POST["type"];
    $reference=@$_POST["reference"];
    $prix=@$_POST["prix"];
    $description=@$_POST["description"];
    $numero=@$_POST["numero"];

    /**Debut Details Produit**/
        if($operation=='details_Bien'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idBien
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['codeBarreDesignation'];
            $rows[] = $produit['type'];	
            $rows[] = $produit['prix'];
            $rows[] = $produit['description'];

            echo json_encode($rows);
        }
    /**Fin Details Produit**/

    /**Debut Ajouter Produit**/
        if($operation=='ajouter_Bien'){

            $stmtProduit_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation, type, prix, description, classe, codeBarreDesignation, archiver)
            VALUES (:designation, :type, :prix, :description, :classe, :codeBarreDesignation, :archiver)");
            $stmtProduit_Ajouter->execute(array(
                ':designation' => $reference,
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
            $rows[] = $produit['prix'];
            $rows[] = $produit['description'];

            echo json_encode($rows);
        }
    /**Fin Ajouter Produit**/

    /**Debut Modifier Produit**/
       if($operation=='modifier_Bien'){

        $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET designation =:designation, type =:type,
        prix =:prix, description =:description, codeBarreDesignation =:codeBarreDesignation
        WHERE idDesignation = :idDesignation ");
        $stmtProduit_Modifier->execute(array(
            ':idDesignation' => $idBien,
            ':designation' => $reference,
            ':type' => $type,
            ':prix' => $prix,
            ':description' => $description,
            ':codeBarreDesignation' => $numero
        ));

        $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
        $stmtProduit->execute(array(
            ':idDesignation' => $idBien
        ));
        $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);
        
            $rows = array();
            $rows[] = $produit['idDesignation'];	
            $rows[] = $produit['designation'];	
            $rows[] = $produit['codeBarreDesignation'];
            $rows[] = $produit['type'];	
            $rows[] = $produit['prix'];
            $rows[] = $produit['description'];

        echo json_encode($rows);
      }
    /**Fin Modifier Produit**/ 

    /**Debut Supprimer Produit**/
        if($operation=='supprimer_Bien'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` WHERE idDesignation = :idDesignation Limit 1 ");
            $stmtPanier->execute(array( ':idDesignation' => $idBien ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
      
            if($panier){
                $stmtProduit_Archiver = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver = :archiver
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Archiver->execute(array(
                    ':idDesignation' => $idBien,
                    ':archiver' => 1
                ));
            }
            else {
                $stmtProduit_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                $stmtProduit_Supprimer->execute(array(':idDesignation' => $idBien ));
            }

            echo json_encode(1);
        }
    /**Fin Supprimer Produit**/ 

    /**Debut Archiver Produit**/
       if($operation=='desarchiver_Bien'){

            $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver = :archiver
            WHERE idDesignation = :idDesignation ");
            $stmtProduit_Modifier->execute(array(
                ':idDesignation' => $idBien,
                ':archiver' => 0
            ));

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idBien
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);	

            echo json_encode($idBien);
        }
    /**Fin Archiver Produit**/ 


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>