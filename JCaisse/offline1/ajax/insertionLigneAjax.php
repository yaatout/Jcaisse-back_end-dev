<?php

session_start();

if(!$_SESSION['iduser']){ 

  header('Location:../index.php');

}

require('../connectionOffline.php');

require('../declarationVariables.php');

$idPagnet= $_POST['idPagnet'];
//$identifiantL=$_POST['identifiantL'];

$lignes= json_decode($_POST['lignesL'], true);
//nbreLignes= $_POST['nbreLignes'];
//var_dump($details);
//print_r($idPagnet);
 
try {
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
    $pdo->beginTransaction();
    $sqlP = "SELECT * FROM `".$nomtablePagnet."` WHERE idPagnet=$idPagnet";
    //var_dump($sqlP);
    $resP= $pdo->prepare($sqlP);

    $resP->execute();
    $panier=$resP->fetch(PDO::FETCH_ASSOC);
    //print_r($panier);
    foreach($lignes as $i => $ligne) {
        // $sqlIdL = "SELECT * FROM `".$nomtableLigne."` WHERE identifiantL=".$lignes[$i]['identifiantL']."";
        // //var_dump($sqlIdL);
        // $residL= $pdo->prepare($sqlIdL);

        // $residL->execute();
        // $searchLigne=$residL->fetch(PDO::FETCH_ASSOC);

        // print_r($searchLigne);

        // if($searchLigne){
        //     $result="0"; 
        //     //exit($result);
        // }
        // else{
            //$result=$panier['idPagnet'];
            
            $data = [
                ':identifiantL' => $lignes[$i]['identifiantL'],
                ':designation' => $lignes[$i]['designation'],
                ':idStock' => $lignes[$i]['idStock'],
                ':idDesignation' => $lignes[$i]['idDesignation'],
                ':unitevente' => $lignes[$i]['unitevente'],
                ':prixunitevente' => $lignes[$i]['prixunitevente'],
                ':quantite' => $lignes[$i]['quantite'],
                ':prixtotal' => $lignes[$i]['prixtotal'],
                ':idPagnet' => $panier['idPagnet'],
                ':classe' => $lignes[$i]['classe'],
                ':synchronise' => $lignes[$i]['synchronise'],
                
                ':prixtotalTvaP' => $lignes[$i]['prixtotalTvaP'],
                ':prixtotalTvaR' => $lignes[$i]['prixtotalTvaR'],
            ];
    
            $sql = "insert into `".$nomtableLigne."` (identifiantL, designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe,synchronise,prixtotalTvaP,prixtotalTvaR)
            values (:identifiantL,:designation,:idStock,:idDesignation,:unitevente,:prixunitevente,:quantite,:prixtotal,:idPagnet,:classe,:synchronise,:prixtotalTvaP,:prixtotalTvaR)";

            $res= $pdo->prepare($sql);
            $res->execute($data);
        
            //$result="1";
            // $sqlL="SELECT * FROM `".$nomtableLigne."` where idDesignation =".$lignes[$i]['idDesignation']." and idPagnet=".$panier['idPagnet']."";
            // $resL= $pdo->prepare($sqlL);
            // $resL->execute();

            // $ligneL=$resL->fetchAll(PDO::FETCH_ASSOC);  
            // //print_r($ligneL);
            // if($resL->execute()){
            //     foreach ($ligneL as $ligne){
            //         //var_dump($ligne['designation']);
                    
            //         if($ligne['classe']==0){
            
            //             $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation']."";
            
            //             $resS=$pdo->prepare($sqlS);
            //             $resS->execute();
            
            //             $designation = $resS->fetch(PDO::FETCH_ASSOC) ;
            //             //var_dump($designation);
            //             if($designation){
        
            //                 $sql_E="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ";
        
            //                 $res_E=$pdo->prepare($sql_E);
            //                 $res_E->execute();
        
            //                 $t_stock = $res_E->fetchAll(PDO::FETCH_ASSOC);
                        
        
            //                 if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
        
            //                     $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
        
            //                     $resD=$pdo->prepare($sqlD);
            //                     $resD->execute();
        
            //                     $restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];
        
            //                     $stocks = $resD->fetchAll(PDO::FETCH_ASSOC);

        
            //                     foreach ($stocks as $stock ) {
        
            //                         if($restant>= 0){
        
            //                             $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
        
            //                             if($quantiteStockCourant > 0){
        
            //                                 $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

            //                                 $resS=$pdo->prepare($sqlS);
            //                                 $resS->execute();
        
            //                             }
        
            //                             else{
        
            //                                 $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
        
            //                                 $resS=$pdo->prepare($sqlS);
            //                                 $resS->execute();
        
            //                             }
        
            //                             $restant= $restant - $stock['quantiteStockCourant'] ;
        
            //                         }
        
            //                     }
        
            //                     $stock_V=$ligne['quantite']*$designation['nbreArticleUniteStock'];
        
            //                     if($t_stock[0]<$stock_V){
        
            //                         if(is_numeric($t_stock[0])){
        
            //                             $stock_T=$t_stock[0];
        
            //                         }
        
            //                         else{
        
            //                             $stock_T=0;
        
            //                         }
            //                         $data = [
            //                             ':idStock' => 0,
            //                             ':idDesignation' => $designation['nbreArticleUniteStock'],
            //                             ':quantite' => $stock_V,
            //                             ':nbreArticleUniteStock' => $designation['nbreArticleUniteStock'],
            //                             ':quantiteStockCourant' => $stock_T,
            //                             ':dateInventaire' => $dateString,
            //                             ':type' => 5,
            //                         ];
        
            //                         $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
        
            //                         VALUES(:idStock,:idDesignation,:quantite,:nbreArticleUniteStock,:quantiteStockCourant,:dateInventaire,:type)';
                                
            //                         $res4= $pdo->prepare($sql4);
            //                         $res4->execute($data); 
        
            //                     }
        
        
        
            //                 }
        
            //                 else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
        
            //                     $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
        
            //                     $resD= $pdo->prepare($sqlD);
            //                     $resD->execute(); 
        
            //                     $restant=$ligne['quantite'];
            //                     $stocks = $resD->fetchAll(PDO::FETCH_ASSOC);

        
            //                     foreach ($stocks as $stock ) {
        
            //                         if($restant>= 0){
        
            //                             $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
                                    
        
            //                             if($quantiteStockCourant > 0){
        
            //                                 $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
        
                                        
            //                                 $resS= $pdo->prepare($sqlS);
            //                                 $resS->execute(); 
        
            //                             }
        
            //                             else{
        
            //                                 $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];                            
        
            //                                 $resS= $pdo->prepare($sqlS);
            //                                 $resS->execute();
            //                             }
        
            //                             $restant= $restant - $stock['quantiteStockCourant'] ;
        
            //                         }
        
            //                     }
        
            //                     $stock_V=$ligne['quantite'];
        
            //                     if($t_stock[0]<$stock_V){
        
            //                         if(is_numeric($t_stock[0])){
        
            //                             $stock_T=$t_stock[0];
            //                         }
        
            //                         else{
        
            //                             $stock_T=0;
        
            //                         }

            //                         $data = [
            //                             ':idStock' => 0,
            //                             ':idDesignation' => $designation['nbreArticleUniteStock'],
            //                             ':quantite' => $stock_V,
            //                             ':nbreArticleUniteStock' => 1,
            //                             ':quantiteStockCourant' => $stock_T,
            //                             ':dateInventaire' => $dateString,
            //                             ':type' => 5,
            //                         ];

            //                         $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
        
            //                         VALUES(:idStock,:idDesignation,:quantite,:nbreArticleUniteStock,:quantiteStockCourant,:dateInventaire,:type)';
        
            //                         //$res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
            //                         $res4= $pdo->prepare($sql4);
            //                         $res4->execute($data); 
        
            //                     }
        
        
            //                 }
        
            //             }
            
            //         }
            //         //$result=$ligne['numligne'];
                
            //     }
            // }
            
            $sqlNbrL = "SELECT count(*) FROM `".$nomtableLigne."` WHERE idPagnet=".$panier['idPagnet'].""; 
            $reSNbrL = $pdo->prepare($sqlNbrL); 
            $reSNbrL->execute(); 
            $number_of_rows = $reSNbrL->fetchColumn();
            $result=$number_of_rows;
            
                //exit($result);
                
        //}

    }     
    
    
    $pdo->commit();
    exit($result);

  
}catch (PDOException $e){
  
  $pdo->rollback();
  exit('Erreur!!! '.$e->getMessage()) ;
  //var_dump($e->getMessage());
  throw $e;
}
 
                                    
?>
