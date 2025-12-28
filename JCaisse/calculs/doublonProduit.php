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

    $uniteStock=@$_POST["uniteStock"];
    $nombreArticles=@$_POST["nombreArticleUS"];
    $prixUniteStock=@$_POST["prixUniteStock"];
    $prixUnitaire=@$_POST["prixUnitaire"];
    $prixAchat=@$_POST["prixAchat"];

    $forme=@$_POST["forme"];
    $tableau=@$_POST["tableau"];
    $prixSession=@$_POST["prixSession"];
    $prixPublic=@$_POST["prixPublic"];


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

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                    $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                    $stmtStock->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['forme'];
                    $rows[] = $produit['tableau'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixPublic_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixPublic'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixSession_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixSession'].'" style="width: 90px;" required=""/>';
                    $rows[] = '<span id="span_Designation_Quantite_'.$produit['idDesignation'].'">'.$stock['quantite'].'</span>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_Quantite_'.$produit['idDesignation'].'" style="width: 90px;"  min=1  required=""/>';
                    $rows[]  = '<button type="button" onclick="fusion_Designation('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-success btn-sm btn_Liste_Fusion">
                            <i class="glyphicon glyphicon-check"></i> 
                        </button>
                        <button type="button" onclick="supprimer_Designation('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-danger btn-sm btn_Liste_Fusion">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>';
                    $data[] = $rows; 

                }
                else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    $stmtProduit = $bdd->prepare("SELECT  MAX(dateStockage) as dateStockage FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation limit 1 ");
                    $stmtProduit->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtProduit->fetch(PDO::FETCH_ASSOC);

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
                else{

                    $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                    $stmtStock->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['uniteStock'].' [x '.$produit['nbreArticleUniteStock'].'] ' ;
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixUniteStock_'.$produit['idDesignation'].'" style="width: 100px;"  min=1 value="'.$produit['prixuniteStock'].'" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixUnitaire_'.$produit['idDesignation'].'" min=1 value="'.$produit['prix'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_PrixAchat_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixachat'].'" style="width: 90px;" required=""/>';
                    $rows[] = '<span id="span_Designation_Quantite_'.$produit['idDesignation'].'">'.$stock['quantite'].'</span>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_Designation_Quantite_'.$produit['idDesignation'].'" style="width: 90px;"  min=1  required=""/>';
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

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                    $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                    $stmtStock->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['forme'];
                    $rows[] = $produit['tableau'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixPublic_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixPublic'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixSession_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixSession'].'" style="width: 90px;" required=""/>';
                    $rows[] = '<span id="span_CodeBarre_Quantite_'.$produit['idDesignation'].'">'.$stock['quantite'].'</span>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_Quantite_'.$produit['idDesignation'].'" style="width: 90px;"  min=1  required=""/>';
                    $rows[]  = '<button type="button" onclick="fusion_CodeBarre('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-success btn-sm btn_Liste_Fusion">
                        <i class="glyphicon glyphicon-check"></i> 
                    </button>
                    <button type="button" onclick="supprimer_CodeBarre('.$produit["idDesignation"].')" id="btn_Valider-'.$produit['idDesignation'].'" class="btn btn-danger btn-sm btn_Liste_Fusion">
                        <i class="glyphicon glyphicon-remove"></i>
                    </button>';
                    $data[] = $rows; 

                }
                else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    $stmtProduit = $bdd->prepare("SELECT  MAX(dateStockage) as dateStockage FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation limit 1 ");
                    $stmtProduit->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtProduit->fetch(PDO::FETCH_ASSOC);

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
                else{

                    $stmtStock = $bdd->prepare("SELECT  SUM(quantiteStockCourant) as quantite FROM `".$nomtableStock."` WHERE idDesignation = :idDesignation ");
                    $stmtStock->execute(array(
                        ':idDesignation' => $produit['idDesignation']
                    ));
                    $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

                    $rows = array();
                    $rows[] = $produit['idDesignation'];	
                    $rows[] = $produit['designation'];
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_CodeBarre_'.$produit['idDesignation'].'" style="width: 130px;"  min=1 value="'.$produit['codeBarreDesignation'].'" required=""/>';
                    $rows[] = $produit['uniteStock'].' [x '.$produit['nbreArticleUniteStock'].'] ' ;
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixUniteStock_'.$produit['idDesignation'].'" style="width: 100px;"  min=1 value="'.$produit['prixuniteStock'].'" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixUnitaire_'.$produit['idDesignation'].'" min=1 value="'.$produit['prix'].'" style="width: 100px;" required=""/>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_PrixAchat_'.$produit['idDesignation'].'" min=1 value="'.$produit['prixachat'].'" style="width: 90px;" required=""/>';
                    $rows[] = '<span id="span_CodeBarre_Quantite_'.$produit['idDesignation'].'">'.$stock['quantite'].'</span>';
                    $rows[] = '<input type="number" class="form-control" id="inpt_CodeBarre_Quantite_'.$produit['idDesignation'].'" style="width: 90px;"  min=1  required=""/>';
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
                        ':type' => 20,
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
                        ':designation' => $reference['designation'] ,
                        ':quantiteStockinitial' => $quantite,
                        ':forme' => $reference['forme'] , 
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
                        ':type' => 20,
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
                    VALUES (:idDesignation, :designation, :quantiteStockinitial, :forme, :nbreArticleUniteStock, :totalArticleStock, :dateStockage, :quantiteStockCourant,:prixuniteStock, :prixunitaire, :prixachat, :iduser)");
                    $stmStock_Ajouter->execute(array(
                        ':idDesignation' => $idProduit,
                        ':designation' => $reference['designation'] ,
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
                        ':type' => 20,
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
                        ':designation' => $reference['designation'] ,
                        ':quantiteStockinitial' => $quantite,
                        ':forme' => $reference['forme'] , 
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
                        ':type' => 20,
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
                        ':designation' => $reference['designation'] ,
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


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>