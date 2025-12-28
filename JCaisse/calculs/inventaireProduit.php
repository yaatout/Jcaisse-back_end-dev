<?php

session_start();

if(!$_SESSION['iduser']){

header('Location:../JCaisse/index.php');

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

    $stock=@$_POST["stock"];
    $prixUniteStock=@$_POST["prixUniteStock"];
    $prixUnitaire=@$_POST["prixUnitaire"];
    $prixAchat=@$_POST["prixAchat"];

    $forme=@$_POST["forme"];
    $tableau=@$_POST["tableau"];
    $prixSession=@$_POST["prixSession"];
    $prixPublic=@$_POST["prixPublic"]; 

    $idEntrepot=@$_POST["idEntrepot"];

    /**Debut Doublon Designation**/
        if($operation=='doublon_Designation'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $reference = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            $stmtDoublon = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE designation = :designation ");
            $stmtDoublon->execute(array(
                ':designation' => $reference['designation']
            ));
            $produits = $stmtDoublon->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($produits as $produit){

                $stmtProduit = $bdd->prepare("SELECT  MAX(dateStockage) as dateStockage FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation limit 1 ");
                $stmtProduit->execute(array(
                    ':idDesignation' => $produit['idDesignation']
                ));
                $stock = $stmtProduit->fetch(PDO::FETCH_ASSOC);

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['forme'];
                    $rows[] = $produit['tableau'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixPublic_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixPublic'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixSession_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixSession'].'" style="width: 90px;" required=""/>';
                    $rows[] = $stock['dateStockage'];
                    $rows[]  = '<button type="button" onclick="fusion_Designation('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-success btn-sm btn_Liste_Fusion">
                            <i class="glyphicon glyphicon-check"></i> 
                        </button>
                        <button type="button" onclick="supprimer_Designation('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-danger btn-sm btn_Liste_Fusion">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>';
                    $data[] = $rows; 

                }
                else{

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['uniteStock'].' [x '.$produit['nbreArticleUniteStock'].'] ' ;
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixUniteStock_'.$produit['idDesignation'].'" style="width: 100px;"  min=1 value="'.$produit['prixuniteStock'].'" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixUnitaire_'.$produit['idDesignation'].'" min=1 value="'.$produit['prix'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixAchat_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixachat'].'" style="width: 90px;" required=""/>';
                    $rows[] = $stock['dateStockage'];
                    $rows[]  = '<button type="button" onclick="fusion_Designation('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-success btn-sm btn_Liste_Fusion">
                            <i class="glyphicon glyphicon-check"></i> 
                        </button>
                        <button type="button" onclick="supprimer_Designation('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-danger btn-sm btn_Liste_Fusion">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>';
                    $data[] = $rows; 

                } 

            }
            
            echo json_encode($data);
        }
    /**Fin Doublon Designation**/

    /**Debut Doublon Code barre**/
        if($operation=='doublon_CodeBarre'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $reference = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            $stmtDoublon = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE ( codeBarreDesignation = :codeBarreDesignation AND codeBarreDesignation<>''  ) ");
            $stmtDoublon->execute(array(
                ':codeBarreDesignation' => $reference['codeBarreDesignation']
            ));
            $produits = $stmtDoublon->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($produits as $produit){

                $stmtProduit = $bdd->prepare("SELECT  MAX(dateStockage) as dateStockage FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation limit 1 ");
                $stmtProduit->execute(array(
                    ':idDesignation' => $produit['idDesignation']
                ));
                $stock = $stmtProduit->fetch(PDO::FETCH_ASSOC);

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['forme'];
                    $rows[] = $produit['tableau'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixPublic_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixPublic'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixSession_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixSession'].'" style="width: 90px;" required=""/>';
                    $rows[] = $stock['dateStockage'];
                    $rows[]  = '<button type="button" onclick="fusion_CodeBarre('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-success btn-sm btn_Liste_Fusion">
                        <i class="glyphicon glyphicon-check"></i> 
                    </button>
                    <button type="button" onclick="supprimer_CodeBarre('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-danger btn-sm btn_Liste_Fusion">
                        <i class="glyphicon glyphicon-remove"></i>
                    </button>';
                    $data[] = $rows; 

                }
                else{

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['uniteStock'].' [x '.$produit['nbreArticleUniteStock'].'] ' ;
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixUniteStock_'.$produit['idDesignation'].'" style="width: 100px;"  min=1 value="'.$produit['prixuniteStock'].'" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixUnitaire_'.$produit['idDesignation'].'" min=1 value="'.$produit['prix'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixAchat_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixachat'].'" style="width: 90px;" required=""/>';
                    $rows[] = $stock['dateStockage'];
                    $rows[]  = '<button type="button" onclick="fusion_CodeBarre('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-success btn-sm btn_Liste_Fusion">
                        <i class="glyphicon glyphicon-check"></i> 
                    </button>
                    <button type="button" onclick="supprimer_CodeBarre('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-danger btn-sm btn_Liste_Fusion">
                        <i class="glyphicon glyphicon-remove"></i>
                    </button>';
                    $data[] = $rows; 

                } 

            }
            
            echo json_encode($data);
        }
    /**Fin Doublon Code barre**/

    /**Debut Fusion Designation**/
        if($operation=='fusion_Designation'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $reference = $stmtProduit->fetch(PDO::FETCH_ASSOC);
            
            $stmtDoublon = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE designation = :designation ");
            $stmtDoublon->execute(array(
                ':designation' => $reference['designation']
            ));
            $produits = $stmtDoublon->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($produits as $rows => $row) {

                if($row['idDesignation']!=$idProduit){

                    $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                    $stmtStock_Modifier->execute(array(
                        ':nouveau' => $idProduit,
                        ':ancien' => $row['idDesignation']
                    ));
            
                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                        $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                        $stmtEntrepotStock_Modifier->execute(array(
                            ':nouveau' => $idProduit,
                            ':ancien' => $row['idDesignation']
                        ));
            
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

                }     

            }
            
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=0, prixSession =:prixSession, prixPublic =:prixPublic, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':codeBarreDesignation' => $codeBarre,
                    ':prixSession' => $prixSession, 
                    ':prixPublic' => $prixPublic
                ));
            
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=0, prixuniteStock =:prixuniteStock, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));
            
            }
            else{
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archive=0, prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock, 
                    ':prix' => $prixUnitaire,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));

              
            }
            
            echo json_encode($reference);
        }
    /**Fin Fusion Designation**/

    /**Debut Fusion CodeBarre**/
        if($operation=='fusion_CodeBarre'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $reference = $stmtProduit->fetch(PDO::FETCH_ASSOC);
            
            $stmtDoublon = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE codeBarreDesignation = :codeBarreDesignation AND codeBarreDesignation<>'' ");
            $stmtDoublon->execute(array(
                ':codeBarreDesignation' => $reference['codeBarreDesignation']
            ));
            $produits = $stmtDoublon->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($produits as $rows => $row) {

                if($row['idDesignation']!=$idProduit){

                    $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                    $stmtStock_Modifier->execute(array(
                        ':nouveau' => $idProduit,
                        ':ancien' => $row['idDesignation']
                    ));
            
                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                        $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET idDesignation =:nouveau WHERE idDesignation = :ancien ");
                        $stmtEntrepotStock_Modifier->execute(array(
                            ':nouveau' => $idProduit,
                            ':ancien' => $row['idDesignation']
                        ));
            
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

                }     

            }
            
            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=0, prixSession =:prixSession, prixPublic =:prixPublic, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':codeBarreDesignation' => $codeBarre,
                    ':prixSession' => $prixSession, 
                    ':prixPublic' => $prixPublic
                ));
            
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=0, prixuniteStock =:prixuniteStock, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));
            
            }
            else{
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archive=0, prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock, 
                    ':prix' => $prixUnitaire,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));

              
            }
            
            echo json_encode($reference);
        }
    /**Fin Fusion CodeBarre**/

    /**Debut Supprimer Designation**/
        if($operation=='supprimer_Designation'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $reference = $stmtProduit->fetch(PDO::FETCH_ASSOC);
            
            $stmtDoublon = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE designation = :designation ");
            $stmtDoublon->execute(array(
                ':designation' => $reference['designation']
            ));
            $produits = $stmtDoublon->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($produits as $rows => $row) {
            
                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    $stmtEntrepotStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableEntrepotStock."` WHERE idDesignation = :idDesignation ");
                    $stmtEntrepotStock_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));
        
                }
        
                $stmtStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));

                $stmtInventaire_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableInventaire."` WHERE idDesignation = :idDesignation ");
                $stmtInventaire_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));

                $stmtLigne_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigne."` WHERE idDesignation = :idDesignation ");
                $stmtLigne_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));

                $stmtProduit_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                $stmtProduit_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));    

            }
            
            echo json_encode($reference);
        }
    /**Fin Supprimer Designation**/

    /**Debut Supprimer CodeBarre**/
        if($operation=='supprimer_CodeBarre'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $reference = $stmtProduit->fetch(PDO::FETCH_ASSOC);
            
            $stmtDoublon = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE codeBarreDesignation = :codeBarreDesignation AND codeBarreDesignation<>'' ");
            $stmtDoublon->execute(array(
                ':codeBarreDesignation' => $reference['codeBarreDesignation']
            ));
            $produits = $stmtDoublon->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($produits as $rows => $row) {
            
                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    $stmtEntrepotStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableEntrepotStock."` WHERE idDesignation = :idDesignation ");
                    $stmtEntrepotStock_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));
        
                }
        
                $stmtStock_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));

                $stmtInventaire_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableInventaire."` WHERE idDesignation = :idDesignation ");
                $stmtInventaire_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));

                $stmtLigne_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigne."` WHERE idDesignation = :idDesignation ");
                $stmtLigne_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));

                $stmtProduit_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
                $stmtProduit_Supprimer->execute(array(':idDesignation' => $row['idDesignation'] ));    

            }
            
            echo json_encode($reference);
        }
    /**Fin Supprimer CodeBarre**/

    /**Debut Inventaire Stock**/
        if($operation=='inventaire_Stock'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET prixPublic=:prixPublic, prixSession =:prixSession
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixPublic' => $prixPublic, 
                    ':prixSession' => $prixSession
                ));

                $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock->execute(array(
                    ':idDesignation' => $idProduit
                ));
                $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
                if($stock!=0){
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => $stock['quantite'] , 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } else{
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => 0, 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } 

        
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){     
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, forme, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixPublic, prixSession, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixPublic, :prixSession, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
                        ':quantiteStockinitial' => $quantite,
                        ':forme' => $produit['forme'] , 
                        ':nbreArticleUniteStock' => 0,
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
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=0, prixuniteStock =:prixuniteStock, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));

                $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableEntrepotStock."` WHERE idDesignation = :idDesignation AND idEntrepot =:idEntrepot AND quantiteStockCourant>0 ");
                $stmtStock->execute(array(
                    ':idDesignation' => $idProduit,
                    ':idEntrepot' => $idEntrepot
                ));
                $stock = $stmtStock->fetchAll(PDO::FETCH_ASSOC);
                if($stock!=null){
                    foreach ($stock as $rows => $row) {
                        $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, idEntrepotStock, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                        VALUES (:idDesignation, :idEntrepotStock, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                        $stmStock_Inventaire->execute(array(
                            ':idDesignation' => $idProduit,
                            ':idEntrepotStock' => $row['idEntrepotStock'] ,
                            ':quantite' => 0,
                            ':nbreArticleUniteStock' => 1,
                            ':quantiteStockCourant' => $row['quantiteStockCourant'] , 
                            ':dateInventaire' => $dateString, 
                            ':heureInventaire' => $heureString, 
                            ':type' => 2024,
                            ':iduser' => $_SESSION['iduser']
                        ));
                    }
                } 

                $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET quantiteStockCourant=0
                WHERE idDesignation = :idDesignation AND  idEntrepot =:idEntrepot ");
                $stmtEntrepotStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':idEntrepot' => $idEntrepot
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $nombreArticles=$produit['nbreArticleUniteStock'];
                    $totalArticleStock= $nombreArticles * $quantite;
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
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
                    $last_idStock = $bdd->lastInsertId();

                    $stmStock_Entrepot = $bdd->prepare("INSERT INTO `".$nomtableEntrepotStock."` (idStock,idEntrepot,idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, iduser)
                    VALUES (:idStock,:idEntrepot,:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :iduser)");
                    $stmStock_Entrepot->execute(array(
                        ':idStock' => $last_idStock,
                        ':idEntrepot' => $idEntrepot,
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'],
                        ':quantiteStockinitial' => $quantite,
                        ':uniteStock' => $produit['uniteStock'],
                        ':nbreArticleUniteStock' => $nombreArticles,
                        ':totalArticleStock' => $totalArticleStock,
                        ':dateStockage' => $dateString,
                        ':quantiteStockCourant' => $totalArticleStock, 
                        ':prixuniteStock' => $prixUniteStock,
                        ':iduser' => $_SESSION['iduser']
                    ));
                    $last_idEntrepotStock = $bdd->lastInsertId();


                    if($stock==null){
                        $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, idEntrepotStock, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                        VALUES (:idDesignation, :idEntrepotStock, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                        $stmStock_Inventaire->execute(array(
                            ':idDesignation' => $idProduit,
                            ':idEntrepotStock' => $last_idEntrepotStock ,
                            ':quantite' => 0,
                            ':nbreArticleUniteStock' => 1,
                            ':quantiteStockCourant' => 0, 
                            ':dateInventaire' => $dateString, 
                            ':heureInventaire' => $heureString, 
                            ':type' => 2024,
                            ':iduser' => $_SESSION['iduser']
                        ));
                    } 

                }
            }
            else{
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock, 
                    ':prix' => $prixUnitaire,
                    ':prixachat' => $prixAchat
                ));

                $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock->execute(array(
                    ':idDesignation' => $idProduit
                ));
                $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
                if($stock!=0){
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => $stock['quantite'] , 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } else{
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => 0, 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } 

        
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixunitaire, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
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

            echo json_encode($idEntrepot);
        }
    /**Fin Inventaire Stock**/

    /**Debut Ajouter Stock**/
        if($operation=='ajouter_Stock'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET prixPublic=:prixPublic, prixSession =:prixSession
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixPublic' => $prixPublic,
                    ':prixSession' => $prixSession
                ));
    
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, forme, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixPublic, prixSession, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixPublic, :prixSession, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
                        ':quantiteStockinitial' => $quantite,
                        ':forme' => $produit['forme'], 
                        ':nbreArticleUniteStock' => 0,
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
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET  prixuniteStock =:prixuniteStock, prixachat =:prixachat
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock,
                    ':prixachat' => $prixAchat
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){

                    $nombreArticles=$produit['nbreArticleUniteStock'];
                    $totalArticleStock= $nombreArticles * $quantite;

                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
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
                    $last_idStock = $bdd->lastInsertId();

                    $stmStock_Entrepot = $bdd->prepare("INSERT INTO `".$nomtableEntrepotStock."` (idStock,idEntrepot,idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, iduser)
                    VALUES (:idStock,:idEntrepot,:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :iduser)");
                    $stmStock_Entrepot->execute(array(
                        ':idStock' => $last_idStock,
                        ':idEntrepot' => $idEntrepot,
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'],
                        ':quantiteStockinitial' => $quantite,
                        ':uniteStock' => $produit['uniteStock'],
                        ':nbreArticleUniteStock' => $nombreArticles,
                        ':totalArticleStock' => $totalArticleStock,
                        ':dateStockage' => $dateString,
                        ':quantiteStockCourant' => $totalArticleStock, 
                        ':prixuniteStock' => $prixUniteStock,
                        ':iduser' => $_SESSION['iduser']
                    ));
                }
            }
            else{
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock, 
                    ':prix' => $prixUnitaire,
                    ':prixachat' => $prixAchat
                ));
    
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixunitaire, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
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

            echo json_encode(1);
        }
    /**Fin Ajouter Stock**/

    /**Debut Inventaire Produit**/
        if($operation=='inventaire_Produit'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET prixPublic =:prixPublic, prixSession =:prixSession
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixPublic' => $prixPublic, 
                    ':prixSession' => $prixSession
                ));

                $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock->execute(array(
                    ':idDesignation' => $idProduit
                ));
                $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
                if($stock!=0){
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => $stock['quantite'] , 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } else{
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => 0, 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } 
        
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, forme, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixPublic, prixSession, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixPublic, :prixSession, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
                        ':quantiteStockinitial' => $quantite,
                        ':forme' => $produit['forme'] , 
                        ':nbreArticleUniteStock' => 0,
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
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=0, prixuniteStock =:prixuniteStock, prixachat =:prixachat, codeBarreDesignation =:codeBarreDesignation
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock,
                    ':prixachat' => $prixAchat,
                    ':codeBarreDesignation' => $codeBarre
                ));

                $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableEntrepotStock."` WHERE idDesignation = :idDesignation AND idEntrepot =:idEntrepot AND quantiteStockCourant>0 ");
                $stmtStock->execute(array(
                    ':idDesignation' => $idProduit,
                    ':idEntrepot' => $idEntrepot
                ));
                $stock = $stmtStock->fetchAll(PDO::FETCH_ASSOC);
                if($stock!=null){
                    foreach ($stock as $rows => $row) {
                        $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, idEntrepotStock, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                        VALUES (:idDesignation, :idEntrepotStock, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                        $stmStock_Inventaire->execute(array(
                            ':idDesignation' => $idProduit,
                            ':idEntrepotStock' => $row['idEntrepotStock'] ,
                            ':quantite' => 0,
                            ':nbreArticleUniteStock' => 1,
                            ':quantiteStockCourant' => $row['quantiteStockCourant'] , 
                            ':dateInventaire' => $dateString, 
                            ':heureInventaire' => $heureString, 
                            ':type' => 2024,
                            ':iduser' => $_SESSION['iduser']
                        ));
                    }
                }  

                $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET quantiteStockCourant=0
                WHERE idDesignation = :idDesignation AND  idEntrepot =:idEntrepot ");
                $stmtEntrepotStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':idEntrepot' => $idEntrepot
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $nombreArticles=$produit['nbreArticleUniteStock'];
                    $totalArticleStock= $nombreArticles * $quantite;
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
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
                    $last_idStock = $bdd->lastInsertId();

                    $stmStock_Entrepot = $bdd->prepare("INSERT INTO `".$nomtableEntrepotStock."` (idStock,idEntrepot,idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, iduser)
                    VALUES (:idStock,:idEntrepot,:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant, :prixuniteStock, :iduser)");
                    $stmStock_Entrepot->execute(array(
                        ':idStock' => $last_idStock,
                        ':idEntrepot' => $idEntrepot,
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'],
                        ':quantiteStockinitial' => $quantite,
                        ':uniteStock' => $produit['uniteStock'],
                        ':nbreArticleUniteStock' => $nombreArticles,
                        ':totalArticleStock' => $totalArticleStock,
                        ':dateStockage' => $dateString,
                        ':quantiteStockCourant' => $totalArticleStock, 
                        ':prixuniteStock' => $prixUniteStock,
                        ':iduser' => $_SESSION['iduser']
                    ));
                    $last_idEntrepotStock = $bdd->lastInsertId();

                    if($stock==null){
                        $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, idEntrepotStock, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                        VALUES (:idDesignation, :idEntrepotStock, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                        $stmStock_Inventaire->execute(array(
                            ':idDesignation' => $idProduit,
                            ':idEntrepotStock' => $last_idEntrepotStock ,
                            ':quantite' => 0,
                            ':nbreArticleUniteStock' => 1,
                            ':quantiteStockCourant' => 0, 
                            ':dateInventaire' => $dateString, 
                            ':heureInventaire' => $heureString, 
                            ':type' => 2024,
                            ':iduser' => $_SESSION['iduser']
                        ));
                    }
                }
            }
            else{
                $stmtProduit_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET prixuniteStock =:prixuniteStock, prix =:prix, prixachat =:prixachat
                WHERE idDesignation = :idDesignation ");
                $stmtProduit_Modifier->execute(array(
                    ':idDesignation' => $idProduit,
                    ':prixuniteStock' => $prixUniteStock, 
                    ':prix' => $prixUnitaire,
                    ':prixachat' => $prixAchat
                ));

                $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                $stmtStock->execute(array(
                    ':idDesignation' => $idProduit
                ));
                $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
                if($stock!=0){
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => $stock['quantite'] , 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } else{
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $idProduit,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => 0, 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
                } 
        
                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=0
                WHERE idDesignation = :idDesignation ");
                $stmtStock_Modifier->execute(array(
                    ':idDesignation' => $idProduit
                ));
    
                if($quantite!=0 && $quantite!='' && $quantite!=null){
                    $stmStock_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation, designation, quantiteStockinitial, uniteStock, nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, prixuniteStock, prixunitaire, prixachat, iduser)
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :uniteStock, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $produit['designation'] ,
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

            echo json_encode(1);
        }
    /**Fin Inventaire Produit**/

    /**Debut Details Inventaire**/
        if($operation=='details_Inventaire'){

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDesignation ");
            $stmtProduit->execute(array(
                ':idDesignation' => $idProduit
            ));
            $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                $stmtInventaire = $bdd->prepare("SELECT i.quantiteStockCourant, i.dateInventaire, i.heureInventaire, i.idUser FROM `".$nomtableInventaire."` i
                INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepotStock = i.idEntrepotStock
                WHERE i.idDesignation = :idDesignation AND i.type=2024 AND s.idEntrepot='".$idEntrepot."' ");
                $stmtInventaire->execute(array(
                   ':idDesignation' => $idProduit
                ));
                $inventaires = $stmtInventaire->fetchAll(PDO::FETCH_ASSOC);

                $datas=array();
                $data_Inventaire=array();
                $data_Stock=array();
                foreach($inventaires as $inventaire){

                    $personnel = ($inventaire['iduser']!=null)?$inventaire['iduser']:$inventaire['idUser'];

                    $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
                    $stmtUser->bindValue(':idutilisateur', $personnel , PDO::PARAM_INT);
                    $stmtUser->execute();
                    $user_Inventaire = $stmtUser->fetch(); 

                    if($produit['nbreArticleUniteStock']!=0 && $produit['nbreArticleUniteStock']!=null){
                        $quantite_i = $inventaire['quantiteStockCourant']/$produit['nbreArticleUniteStock'];
                    }
                    else {
                        $quantite_i = $inventaire['quantiteStockCourant'];
                    }

                    $rows_Inventaire = array();
                    $rows_Inventaire[] = $produit['idDesignation'];	
                    $rows_Inventaire[] = $produit['designation'];
                    $rows_Inventaire[] = $quantite_i;	
                    $rows_Inventaire[] = $inventaire['dateInventaire'].' '.$inventaire['heureInventaire'];
                    //$rows_Inventaire[] = $inventaire['dateInventaire'];
                    $rows_Inventaire[] = strtoupper($user_Inventaire["prenom"]);
                    $data_Inventaire[] = $rows_Inventaire; 

                    $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableEntrepotStock."` 
                    WHERE idDesignation = :idDesignation AND dateStockage =:dateStockage AND idEntrepot='".$idEntrepot."' ");
                    $stmtStock->execute(array(
                       ':idDesignation' => $idProduit,
                       ':dateStockage' => $inventaire['dateInventaire']
                    ));
                    $stocks = $stmtStock->fetchAll(PDO::FETCH_ASSOC);
                    foreach($stocks as $stock){ 

                        $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
                        $stmtUser->bindValue(':idutilisateur', $stock['iduser'], PDO::PARAM_INT);
                        $stmtUser->execute();
                        $user_Stock = $stmtUser->fetch(); 

                        if($produit['nbreArticleUniteStock']!=0 && $produit['nbreArticleUniteStock']!=null){
                            $quantite_s = $stock['totalArticleStock']/$produit['nbreArticleUniteStock'];
                        }
                        else {
                            $quantite_s = $stock['totalArticleStock'];
                        }
    
                        $rows_Stock = array();
                        $rows_Stock[] = $produit['idDesignation'];	
                        $rows_Stock[] = $produit['designation'];
                        $rows_Stock[] = $quantite_s;	
                        $rows_Stock[] = $stock['dateStockage'];
                        $rows_Stock[] = strtoupper($user_Stock["prenom"]);
                        $data_Stock[] = $rows_Stock; 

                    } 

                } 
               
                $datas[] = $data_Inventaire; 
                $datas[] = $data_Stock;
       
            }
            else{

                $stmtInventaire = $bdd->prepare("SELECT * FROM `".$nomtableInventaire."` 
                WHERE idDesignation = :idDesignation AND type=2024 ");
                $stmtInventaire->execute(array(
                   ':idDesignation' => $idProduit
                ));
                $inventaires = $stmtInventaire->fetchAll(PDO::FETCH_ASSOC);

                $datas=array();
                $data_Inventaire=array();
                $data_Stock=array();
                foreach($inventaires as $inventaire){

                    $personnel = ($inventaire['iduser']!=null)?$inventaire['iduser']:$inventaire['idUser'];

                    $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
                    $stmtUser->bindValue(':idutilisateur', $personnel , PDO::PARAM_INT);
                    $stmtUser->execute();
                    $user_Inventaire = $stmtUser->fetch(); 

                    $rows_Inventaire = array();
                    $rows_Inventaire[] = $produit['idDesignation'];	
                    $rows_Inventaire[] = $produit['designation'];
                    $rows_Inventaire[] = $inventaire['quantiteStockCourant'];	
                    $rows_Inventaire[] = $inventaire['dateInventaire'].' '.$inventaire['heureInventaire'];
                    //$rows_Inventaire[] = $inventaire['dateInventaire'];
                    $rows_Inventaire[] = strtoupper($user_Inventaire["prenom"]);
                    $data_Inventaire[] = $rows_Inventaire; 

                    $dateDebut = $inventaire['dateInventaire'];
                    $dateFin = date('Y-m-d', strtotime($inventaire['dateInventaire']. ' + 1 days'));

                    $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
                    WHERE idDesignation = :idDesignation AND (dateStockage  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
                    $stmtStock->execute(array(
                       ':idDesignation' => $idProduit
                    ));
                    $stocks = $stmtStock->fetchAll(PDO::FETCH_ASSOC);
                    foreach($stocks as $stock){ 

                        $stmtUser = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur=:idutilisateur ");
                        $stmtUser->bindValue(':idutilisateur', $stock['iduser'], PDO::PARAM_INT);
                        $stmtUser->execute();
                        $user_Stock = $stmtUser->fetch(); 
    
                        $rows_Stock = array();
                        $rows_Stock[] = $produit['idDesignation'];	
                        $rows_Stock[] = $produit['designation'];
                        $rows_Stock[] = $stock['totalArticleStock'];	
                        $rows_Stock[] = $stock['dateStockage'];
                        $rows_Stock[] = strtoupper($user_Stock["prenom"]);
                        $data_Stock[] = $rows_Stock; 

                    } 

                } 
               
                $datas[] = $data_Inventaire; 
                $datas[] = $data_Stock; 
            }  

            echo json_encode($datas);
        }
    /**Fin Details Inventaire**/

    /**Debut Vider Stock Produit Non Inventaire**/
        if($operation=='vider_Stock'){

            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $stmt = $bdd->prepare("SELECT DISTINCT(d.idDesignation) as idDesignation, SUM(s.quantiteStockCourant) as quantite, MAX(idEntrepotStock) as idEntrepotStock FROM `".$nomtableDesignation."` d
                INNER JOIN `".$nomtableEntrepotStock."` s ON s.idDesignation = d.idDesignation WHERE d.archiver<>1 AND d.classe=0 AND s.idEntrepot='".$idEntrepot."'
                AND s.idDesignation NOT IN (
                SELECT s.idDesignation FROM `".$nomtableEntrepotStock."` s
                INNER JOIN `".$nomtableInventaire."` i ON i.idEntrepotStock = s.idEntrepotStock WHERE i.type=2024 AND s.idEntrepot='".$idEntrepot."'
                ) GROUP BY d.idDesignation ");
                $stmt->execute();
                $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                $stmt = $bdd->prepare("SELECT d.idDesignation FROM `".$nomtableDesignation."` d
                WHERE d.archiver<>1 AND d.classe=0 AND d.idDesignation NOT IN (
                SELECT d.idDesignation FROM `".$nomtableDesignation."` d
                INNER JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation WHERE i.type=2024
                )  ");
                $stmt->execute();
                $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }  
            
            foreach ($produits as $produit) {

                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    
                    $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, idEntrepotStock, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                    VALUES (:idDesignation, :idEntrepotStock, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                    $stmStock_Inventaire->execute(array(
                        ':idDesignation' => $produit['idDesignation'],
                        ':idEntrepotStock' => $produit['idEntrepotStock'] ,
                        ':quantite' => 0,
                        ':nbreArticleUniteStock' => 1,
                        ':quantiteStockCourant' => $produit['quantite'] , 
                        ':dateInventaire' => $dateString, 
                        ':heureInventaire' => $heureString, 
                        ':type' => 2024,
                        ':iduser' => $_SESSION['iduser']
                    ));
    
                    $stmtEntrepotStock_Modifier = $bdd->prepare("UPDATE `".$nomtableEntrepotStock."` SET quantiteStockCourant=0
                    WHERE idDesignation = :idDesignation AND idEntrepot =:idEntrepot");
                    $stmtEntrepotStock_Modifier->execute(array(
                        ':idDesignation' => $produit['idDesignation'],
                        ':idEntrepot' => $idEntrepot
                    ));

                }
                else{
    
                    $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` 
                    WHERE idDesignation = :idDesignation ");
                    $stmtStock->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);
                    if($stock['quantite']!=null){
                        $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                        VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                        $stmStock_Inventaire->execute(array(
                            ':idDesignation' => $produit['idDesignation'],
                            ':quantite' => 0,
                            ':nbreArticleUniteStock' => 1,
                            ':quantiteStockCourant' => $stock['quantite'] , 
                            ':dateInventaire' => $dateString, 
                            ':heureInventaire' => $heureString, 
                            ':type' => 2024,
                            ':iduser' => $_SESSION['iduser']
                        ));
                    } else{
                        $stmStock_Inventaire = $bdd->prepare("INSERT INTO `".$nomtableInventaire."` (idDesignation, quantite, nbreArticleUniteStock, quantiteStockCourant, dateInventaire, heureInventaire, type, iduser)
                        VALUES (:idDesignation, :quantite, :nbreArticleUniteStock, :quantiteStockCourant, :dateInventaire, :heureInventaire, :type, :iduser)");
                        $stmStock_Inventaire->execute(array(
                            ':idDesignation' => $produit['idDesignation'],
                            ':quantite' => 0,
                            ':nbreArticleUniteStock' => 1,
                            ':quantiteStockCourant' => 0, 
                            ':dateInventaire' => $dateString, 
                            ':heureInventaire' => $heureString, 
                            ':type' => 2024,
                            ':iduser' => $_SESSION['iduser']
                        ));

                        $stmtDesignation_Modifier = $bdd->prepare("UPDATE `".$nomtableDesignation."` SET archiver=1
                        WHERE idDesignation = :idDesignation ");
                        $stmtDesignation_Modifier->execute(array(
                            ':idDesignation' => $produit['idDesignation']
                        ));
                    } 
            
                    $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=0
                    WHERE idDesignation = :idDesignation ");
                    $stmtStock_Modifier->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));

                }

            } 
            echo json_encode(1);

        }
    /**Fin Vider Stock Produit Non Inventaire**/


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>