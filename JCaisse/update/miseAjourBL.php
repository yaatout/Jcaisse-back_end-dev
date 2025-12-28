<?php
 
 session_start();

   require('../connection.php');
   require('../connectionPDO.php');
   require('../declarationVariables.php');


   $idPagnet=3420;

   $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";

   $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

   $pagnet = mysql_fetch_assoc($resP) ;



   $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC ";

   $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

   //$ligne = mysql_fetch_assoc($resL) ;


   if($pagnet['type']==0 || $pagnet['type']==30){

       while ($ligne=mysql_fetch_assoc($resL)){

         //echo $ligne['numligne'].' <br/>';
/*  
          if($ligne['numligne']<9628){
            echo $ligne['numligne'].' <br/>';
           if($ligne['classe']==0){

               $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

               $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

               $designation = mysql_fetch_assoc($resS) ;

                   if(mysql_num_rows($resS)){

                       if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {

                           $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                           $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                           $retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                           
                           $qtyVendu=$ligne['quantite']*$designation['nbreArticleUniteStock'];

                           while ($stock = mysql_fetch_assoc($resD)) {

                               if($retour>= 0){

                                   $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                   if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                   }

                                   else{

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                   }

                                   $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                               }

                           }



                       }

                       else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                           $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                           $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                           $retour=$ligne['quantite'];
                           
                           $qtyVendu=$ligne['quantite'];

                           while ($stock = mysql_fetch_assoc($resD)) {

                               if($retour >= 0){

                                   $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                   if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                   }

                                   else{

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                   

                                   }

                                   $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                   

                               }

                               

                           }



                       }
                       

                   }

           }
         } 

        if($ligne['numligne']>9628){
            echo $ligne['numligne'].' <br/>';

            if($ligne['classe']==0){

               $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

               $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

               $designation = mysql_fetch_assoc($resS) ;

                  if(mysql_num_rows($resS)){

                     $sql_E="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                     $res_E=mysql_query($sql_E) or die ("select stock impossible =>".mysql_error());

                     $t_stock = mysql_fetch_array($res_E) ;

                     if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {

                           $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                           $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                           $restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                           
                           $qtyVendu=$ligne['quantite']*$designation['nbreArticleUniteStock'];

                           while ($stock = mysql_fetch_assoc($resD)) {

                              if($restant>= 0){

                                 $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                 if($quantiteStockCourant > 0){

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                 }

                                 else{

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                 }

                                 $restant= $restant - $stock['quantiteStockCourant'] ;

                              }

                           }


                     }

                     else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                           $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                           $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                           $restant=$ligne['quantite'];
                           
                           $qtyVendu=$ligne['quantite'];

                           while ($stock = mysql_fetch_assoc($resD)) {

                              if($restant>= 0){

                                 $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                 if($quantiteStockCourant > 0){

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                 }

                                 else{

                                       $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];

                                       $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                 }

                                 $restant= $restant - $stock['quantiteStockCourant'] ;

                              }

                           }


                     }

                  }

            }
         } */

       }

   }


