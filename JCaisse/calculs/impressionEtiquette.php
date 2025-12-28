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

    /**Debut Ajouter Stock**/
        if($operation=='ajouter_Produit'){

            $resultat = 0;


            $stmProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmProduit->fetch(PDO::FETCH_ASSOC);

            if($uniteStock=='Article' || $uniteStock=='article'){
                $prix= $prixUnitaire;
            }
            else{
                $prix= $prixUniteStock;
            }

            $rows = array();
            $rows['idDesignation'] = $idProduit;	
            $rows['designation'] = $produit['designation'];	
            $rows['unite'] = $uniteStock;
            $rows['quantite'] = $quantite;	
            $rows['prix'] = $prix;

            $_SESSION['etiquettes'][] = $rows;

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

            $etiquettes = $_SESSION['etiquettes'];

            $i=find_p_with_position($_SESSION['etiquettes'], $idProduit);
                     
            unset($_SESSION['etiquettes'][$i]);  


            echo json_encode($idProduit);
        }
    /**Fin Supprimer Stock**/



}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>