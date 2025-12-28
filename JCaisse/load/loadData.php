<?php

session_start();

if(!$_SESSION['iduser']){

header('Location:../index.php');

}

require('../connection.php');
require('../connectionPDO.php');
require('../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);

try{

    if($operation=='1'){
        $stmtProduit = $bdd->prepare("SELECT idDesignation,designation,uniteStock,prix,prixuniteStock,codeBarreDesignation,classe FROM `".$nomtableDesignation."` WHERE archiver<>1  ");
        $stmtProduit->execute();
        $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC); 
        $data=array();
        $rows = array();
        $nbre=0;
        $cpt=1;
        foreach ($produits as $produit) {
            if($nbre==2000){
                $data[] = $rows;
                
                $rows = array();
                $rows[] = $produit;
                $nbre=0;
                $cpt++;
            }
            else{
                $rows[] = $produit;
            } 
            $nbre++;
        }
        $data[] = $rows;
        echo json_encode($data);
    }

    if($operation=='2'){
        $stmtClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` WHERE archiver<>1 ");
        $stmtClient->execute();
        $clients = $stmtClient->fetchAll(PDO::FETCH_ASSOC);
    
        $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` WHERE idCompte<>3 ");
        $stmtCompte->execute();
        $comptes = $stmtCompte->fetchAll(PDO::FETCH_ASSOC);

        $result=json_encode($clients).'<>'.json_encode($comptes);

        exit($result); 
    }



/*     $stmtPanier = $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` ORDER BY idPagnet DESC Limit 1 ");
    $stmtPanier->execute();
    $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);

    $stmtLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` ORDER BY idPagnet DESC Limit 1  ");
    $stmtLigne->execute();
    $ligne = $stmtLigne->fetch(PDO::FETCH_ASSOC); */

 


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>

