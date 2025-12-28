<?php

session_start();

if(!$_SESSION['iduser']){ 

  header('Location:../index.php');

}

require('../connectionOffline.php');

require('../declarationVariables.php');

$idPanier= $_POST['idPanier'];
$identifiantP=$_POST['identifiantP'];
$datepagej= $_POST['datepagej'];
$type= $_POST['type'];
$classe= $_POST['classe'];
$heurePagnet= $_POST['heurePagnet'];
$totalp= $_POST['totalp'];
$remise= $_POST['remise'];
$apayerPagnet= $_POST['apayerPagnet'];
$restourne= $_POST['restourne'];
$versement= $_POST['versement'];
$verrouiller= $_POST['verrouiller'];
$idClient= $_POST['idClient'];
$idVitrine= $_POST['idVitrine'];
$iduser= $_POST['iduser'];
$idCompte= $_POST['idCompte'];
$avance= $_POST['avance'];
$apayerPagnetTvaP= $_POST['apayerPagnetTvaP'];
$apayerPagnetTvaR= $_POST['apayerPagnetTvaR'];
$dejaTerminer= $_POST['dejaTerminer'];
$synchronise= $_POST['synchronise'];
//$details= json_decode($_POST['details'], true);
$lignes= json_decode($_POST['lignes'], true);
//var_dump($details);
 
try {
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
  $pdo->beginTransaction();

    $sqlIdP = "SELECT * FROM `".$nomtablePagnet."` WHERE identifiantP=$identifiantP";
    $residP= $pdo->prepare($sqlIdP);

    $residP->execute();
    $searchPanier=$residP->fetch(PDO::FETCH_ASSOC);

    //print_r($searchPanier);
    $result=100;

    if($searchPanier!=NULL){
        $result=10; 
    }
    else{
        $data = [
            ':identifiantP' => $identifiantP,
            ':datepagej' => $datepagej,
            ':type' => $type,
            ':classe' => $classe,
            ':heurePagnet' => $heurePagnet,
            ':totalp' => $totalp,  
            ':remise' => $remise,
            ':apayerPagnet' => $apayerPagnet,
            ':restourne' => $restourne,
            ':versement' => $versement,
            ':verrouiller' => $verrouiller,
            ':idClient' => $idClient,
            // ':idVitrine' => $idVitrine,
            ':iduser' => $iduser,
            ':idCompte' => $idCompte,
            ':avance' => $avance, 
            // ':apayerPagnetTvaP' => $apayerPagnetTvaP,
            // ':apayerPagnetTvaR' => $apayerPagnetTvaR,                                            
            ':dejaTerminer' => $dejaTerminer,
            ':synchronise' => $synchronise,
        ];
    
        $sql = "insert into `".$nomtablePagnet."` (identifiantP,datepagej,heurePagnet,iduser,type,classe,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient,idCompte,avance,dejaTerminer,synchronise)
        values (:identifiantP,:datepagej,:heurePagnet,:iduser,:type,:classe,:totalp,:remise,:apayerPagnet,:restourne,:versement,:verrouiller,:idClient,:idCompte,:avance,:dejaTerminer,:synchronise)";

        $stmt= $pdo->prepare($sql);
        $stmt->execute($data); 

        //$sql7 = "SELECT * FROM `".$nomtablePagnet."`  order by idPagnet desc limit 1";
        $sql7 = "SELECT * FROM `".$nomtablePagnet."` WHERE identifiantP=$identifiantP";
        //var_dump($sql7);
        $res= $pdo->prepare($sql7);

        $res->execute(); 
        $panier=$res->fetch(PDO::FETCH_ASSOC);  

        if($panier!=NULL){

            $result=$panier['idPagnet'];
            foreach($lignes as $i => $ligne) {
                $data1 = [
                ':designation' => $lignes[$i]['designation'],
                ':idStock' => $lignes[$i]['idStock'],
                ':idDesignation' => $lignes[$i]['idDesignation'],
                ':unitevente' => $lignes[$i]['unitevente'],
                ':prixunitevente' => $lignes[$i]['prixunitevente'],
                ':quantite' => $lignes[$i]['quantite'],
                ':prixtotal' => $lignes[$i]['prixtotal'],
                ':idPagnet' => $panier['idPagnet'],
                ':classe' => $lignes[$i]['classe'],
                // ':synchronise' => $lignes[$i]['synchronise'],
                // ':prixtotalTvaP' => $lignes[$i]['prixtotalTvaP'],
                // ':prixtotalTvaR' => $lignes[$i]['prixtotalTvaR'],
                ];
    
                $sql = "insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)
                values (:designation,:idStock,:idDesignation,:unitevente,:prixunitevente,:quantite,:prixtotal,:idPagnet,:classe)";
    
                $res= $pdo->prepare($sql);
                $res->execute($data1);
            
                //$result="1";
                $sqlL="SELECT * FROM `".$nomtableLigne."` where idDesignation =".$lignes[$i]['idDesignation']." and idPagnet=".$panier['idPagnet']." ";
                $resL= $pdo->prepare($sqlL);
                $resL->execute();
    
                $ligneL=$resL->fetchAll(PDO::FETCH_ASSOC);  
                //var_dump($ligneL);
                if($resL->execute()){
                    foreach ($ligneL as $ligne){
                        //var_dump($ligne['designation']);
                        
                        if($ligne['classe']==0){
                
                            $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation']."";
                
                            $resS=$pdo->prepare($sqlS);
                            $resS->execute();
                
                            $designation = $resS->fetch(PDO::FETCH_ASSOC) ;
                            //var_dump($designation);
                            if($designation){
            
                                $sql_E="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ";
            
                                $res_E=$pdo->prepare($sql_E);
                                $res_E->execute();
            
                                $t_stock = $res_E->fetchAll(PDO::FETCH_ASSOC);
                            
            
                                if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
            
                                    $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
            
                                    $resD=$pdo->prepare($sqlD);
                                    $resD->execute();
            
                                    $restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];
            
                                    $stocks = $resD->fetchAll(PDO::FETCH_ASSOC);
    
            
                                    foreach ($stocks as $stock ) {
            
                                        if($restant>= 0){
            
                                            $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
            
                                            if($quantiteStockCourant > 0){
            
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
    
                                                $resS=$pdo->prepare($sqlS);
                                                $resS->execute();
            
                                            }
            
                                            else{
            
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
            
                                                $resS=$pdo->prepare($sqlS);
                                                $resS->execute();
            
                                            }
            
                                            $restant= $restant - $stock['quantiteStockCourant'] ;
            
                                        }
            
                                    }
            
                                    $stock_V=$ligne['quantite']*$designation['nbreArticleUniteStock'];
            
                                    if($t_stock[0]<$stock_V){
            
                                        if(is_numeric($t_stock[0])){
            
                                            $stock_T=$t_stock[0];
            
                                        }
            
                                        else{
            
                                            $stock_T=0;
            
                                        }
                                        $data = [
                                            ':idStock' => 0,
                                            ':idDesignation' => $designation['nbreArticleUniteStock'],
                                            ':quantite' => $stock_V,
                                            ':nbreArticleUniteStock' => $designation['nbreArticleUniteStock'],
                                            ':quantiteStockCourant' => $stock_T,
                                            ':dateInventaire' => $dateString,
                                            ':type' => 5,
                                        ];
            
                                        $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            
                                        VALUES(:idStock,:idDesignation,:quantite,:nbreArticleUniteStock,:quantiteStockCourant,:dateInventaire,:type)';
                                    
                                        $res4= $pdo->prepare($sql4);
                                        $res4->execute($data); 
            
                                    }
            
            
            
                                }
            
                                else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
            
                                    $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
            
                                    $resD= $pdo->prepare($sqlD);
                                    $resD->execute(); 
            
                                    $restant=$ligne['quantite'];
                                    $stocks = $resD->fetchAll(PDO::FETCH_ASSOC);
    
            
                                    foreach ($stocks as $stock ) {
            
                                        if($restant>= 0){
            
                                            $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
                                        
            
                                            if($quantiteStockCourant > 0){
            
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
            
                                            
                                                $resS= $pdo->prepare($sqlS);
                                                $resS->execute(); 
            
                                            }
            
                                            else{
            
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];                            
            
                                                $resS= $pdo->prepare($sqlS);
                                                $resS->execute();
                                            }
            
                                            $restant= $restant - $stock['quantiteStockCourant'] ;
            
                                        }
            
                                    }
            
                                    $stock_V=$ligne['quantite'];
            
                                    if($t_stock[0]<$stock_V){
            
                                        if(is_numeric($t_stock[0])){
            
                                            $stock_T=$t_stock[0];
                                        }
            
                                        else{
            
                                            $stock_T=0;
            
                                        }
    
                                        $data = [
                                            ':idStock' => 0,
                                            ':idDesignation' => $designation['nbreArticleUniteStock'],
                                            ':quantite' => $stock_V,
                                            ':nbreArticleUniteStock' => 1,
                                            ':quantiteStockCourant' => $stock_T,
                                            ':dateInventaire' => $dateString,
                                            ':type' => 5,
                                        ];
    
                                        $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            
                                        VALUES(:idStock,:idDesignation,:quantite,:nbreArticleUniteStock,:quantiteStockCourant,:dateInventaire,:type)';
            
                                        //$res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
                                        $res4= $pdo->prepare($sql4);
                                        $res4->execute($data); 
            
                                    }
            
            
                                }
            
                            }
                
                        }
                        $result=$ligne['numligne'];
                    
                    }
                }
                $result=$ligne['numligne'];
            }
            $result=$ligne['numligne'];  

        }else{
            $result=0;   
        }

    }

    $pdo->commit();
    echo($result);

  
}catch (PDOException $e){
  
  $pdo->rollback();
  exit('Erreur!!! '.$e->getMessage()) ;
  //var_dump($e->getMessage());
  throw $e;
}
 
                                    
?>
