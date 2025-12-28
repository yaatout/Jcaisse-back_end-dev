<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');

        $resultat=" =ko"+htmlspecialchars($_POST['codeBarre']);
        $idClient=$_GET['c'];

        if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {

         $codeBarre=htmlspecialchars(trim($_POST['codeBarre']));
    	   $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
    	   $codeBrute=explode('-', $codeBarre);
    	   $idStock=$codeBrute[0];
    	   $idDesignation=$codeBrute[1];
    	   $numero=$codeBrute[2];

         $tailleTableau=count($codeBrute);

    	   $sql4="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock."";
          $res4=mysql_query($sql4) or die ("select stock impossible =>".mysql_error());
          $stock = mysql_fetch_array($res4);
          $quantiteStockCourant=$stock['quantiteStockCourant'];


            if ($quantiteStockCourant>0) {
              // mise a jour de la table stock
              $quantiteStockCourant--;
              $sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantiteStockCourant."' where idStock=".$idStock;
              $res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

              // insertion dans l'historique
             /**/ $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation."";

              $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
              $design = mysql_fetch_array($res6);
              if ($tailleTableau==3) {

                    if ($numero==100 || $numero==200 || $numero==300 || $numero==400 || $numero==500) {
                      $sql7="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne,idPagnet)
                       values('".$dateString2."','".$design['designation']."','".$stock['uniteStock']."','".$stock['prixuniteStock']."','1','0','".$stock['prixuniteStock']."','Entree','".$idPagnet."')";
                       $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                    } else {

                      $sql7="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne,idPagnet)
                       values('".$dateString2."','".$design['designation']."','".$stock['uniteStock']."','".$stock['prixunitaire']."','1','0','".$stock['prixunitaire']."','Entree','".$idPagnet."')";
                       $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                    }



              } elseif ($tailleTableau==4) {
                $sql7="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne,idPagnet)
                 values('".$dateString2."','".$design['designation']."','".$stock['uniteStock']."','".$stock['prixunitaire']."','1','0','".$stock['prixunitaire']."','Entree','".$idPagnet."')";
                 $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
              }




            $sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
            $res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
            $pagnet = mysql_fetch_array($res14);
            $totalp=$pagnet['totalp']+$stock['prixunitaire'];
            //$totalp=$Total[0];

            $sql15="UPDATE `".$nomtablePagnet."` set totalp='".$totalp."' where idPagnet=".$idPagnet;
            $res15=mysql_query($sql15) or die ("update Pagnet impossible =>".mysql_error());

              $resultat="ok";
            }

            //$resultat=$codeBarre;
        }
        $sql12="SELECT SUM(totalp) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
        $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
        $Total = mysql_fetch_array($res12) ;

        $sql16="SELECT SUM(montant) FROM versement where idClient=".$idClient."";
        $res16 = mysql_query($sql16) or die ("persoonel requête 2".mysql_error());
        $versement = mysql_fetch_array($res16);

        $bn=$Total[0]-$versement[0];
        $sql17="UPDATE `".$nomtableBon."` set montant='".$bn."', date='".$dateString2."' where idClient=".$idClient;
        $res17=mysql_query($sql17) or die ("update Pagnet impossible =>".mysql_error());

    header("Location:../bonPclient.php?c=$idClient");
     //exit($resultat);

 ?>
