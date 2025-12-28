<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');
 
if ($_SESSION['tampon'] == 1 && $_SESSION['page']=='vente') {
    require('modifierLigneAjax-tampon.php');
} else {
   // code...
    $operation=@htmlspecialchars($_POST["operation"]);
    $idPagnet=@htmlspecialchars($_POST["idPagnet"]);
    $numligne=@htmlspecialchars($_POST["numLigne"]);
    $quantite=@htmlspecialchars($_POST["quantite"]);
    $prix=@htmlspecialchars($_POST["prix"]);
    $remise=@htmlspecialchars($_POST["remise"]);
    $idEntrepot=@htmlspecialchars($_POST["idEntrepot"]);
    $idTransaction=@htmlspecialchars($_POST["idTransaction"]);

    $devise=@htmlspecialchars($_POST["devise"]);
    $taux=@htmlspecialchars($_POST["taux"]);
    $idClient=@htmlspecialchars($_POST["idClient"]);
    
   if($operation == 0){

      $unitevente=@htmlspecialchars($_POST["unitevente"]);

      $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      $ligne = mysql_fetch_assoc($res) ;  
      
      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
      $t_stock = mysql_fetch_array($res_t) ;
  
      $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
      $designation = mysql_fetch_array($resD);

      $reste=0;
  
      if(($unitevente!='Article')&&($unitevente!='article')){
        $quantiteCourant=$t_stock[0] - ($designation['nbreArticleUniteStock'] * $ligne['quantite']);
        $reste=$quantiteCourant;
        if($quantiteCourant >= 0){
          $prixunitevente=$designation['prixuniteStock'];
          $prixTotal=$ligne['quantite']*$prixunitevente;
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
        }
      }
      else if(($unitevente=='Article')||($unitevente=='article')){
        $quantiteCourant=$t_stock[0] - $ligne['quantite'];
        $reste=$quantiteCourant;
        if($quantiteCourant >= 0){
          $prixunitevente=$designation['prix'];
          $prixTotal=$ligne['quantite']*$prixunitevente;
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
        }
      }

      $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      $ligne1 = mysql_fetch_assoc($res) ;
  
      exit($ligne['quantite'].'§'.$ligne1['prixunitevente'].'§'.$reste.'§'.$t_stock[0]);
   } 
   else if($operation == 30){

      $unitevente=@htmlspecialchars($_POST["unitevente"]);

      $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      $ligne = mysql_fetch_assoc($res) ;  
      
      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
      $t_stock = mysql_fetch_array($res_t) ;

      $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
      $designation = mysql_fetch_array($resD);

      if(($unitevente!='Article')&&($unitevente!='article')){
          $prixunitevente=$designation['prixuniteStock'];
          $prixTotal=$ligne['quantite']*$prixunitevente;
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
      }
      else if(($unitevente=='Article')||($unitevente=='article')){
          $prixunitevente=$designation['prix'];
          $prixTotal=$ligne['quantite']*$prixunitevente;
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
      }

      $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      $ligne1 = mysql_fetch_assoc($res) ;

      exit($ligne['quantite'].'§'.$ligne1['prixunitevente']);
   } 
   else if($operation == 1){
      $result="0";

      $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      $ligne = mysql_fetch_assoc($res) ; 

      $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
      $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
      $stock = mysql_fetch_assoc($resS) ;

      $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];
      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
      $designation = mysql_fetch_array($resD);

      if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){
        $quantiteCourant=$stock['quantiteStockCourant'] - ($designation['nbreArticleUniteStock'] * $quantite);
        if($quantiteCourant >= 0){
          $prixTotal=$quantite*$ligne['prixunitevente'];

          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
        $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        $Total = mysql_fetch_array($res2) ;
        $result='1<>'.$Total[0].'<>'.$quantite.'<>'.$ligne['prixunitevente'];
      }
      else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
        $quantiteCourant=$stock['quantiteStockCourant'] - $quantite;
        if($quantiteCourant >= 0){
          $prixTotal=$quantite*$ligne['prixunitevente'];

          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
        $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        $Total = mysql_fetch_array($res2) ;
        $result='1<>'.$Total[0].'<>'.$quantite.'<>'.$ligne['prixunitevente'];
      }

      exit($result);

  
   }
   else if($operation == 2){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);
    
    $prixTotal=$prix*$ligne['quantite'];
    if($_SESSION['Pays']=='Canada' && $designation['tva']==1){ 
      $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];
      $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];
      $sql1="update `".$nomtableLigne."` set prixunitevente=".$prix.",prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where numligne='".$numligne."'";
      $res1=@mysql_query($sql1)or die ("modification impossible");/**/
    }
    else{
      $sql1="update `".$nomtableLigne."` set prixunitevente=".$prix.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
      $res1=@mysql_query($sql1)or die ("modification impossible");/**/
    }
 
    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    if($_SESSION['Pays']=='Canada'){ 
      $sql3="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());
      $TotalTvaP = mysql_fetch_array($res3) ;
  
      $sql4="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
      $TotalTvaR = mysql_fetch_array($res4) ;
  
      $totalP=$Total[0] + $TotalTvaP[0] + $TotalTvaR[0];
    }
    else{
      $totalP=$Total[0];
    }

    $result='1<>'.$Total[0].'<>'.$ligne['quantite'].'<>'.$prix.'<>'.$totalP;

    exit($result);
   }
   else if($operation == 3){

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    $sql0="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($res0) ;

    if($_SESSION['Pays']=='Canada'){ 
      $sql2="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $TotalTvaP = mysql_fetch_array($res2) ;

      $sql3="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());
      $TotalTvaR = mysql_fetch_array($res3) ;

      $totalP=$Total[0] + $TotalTvaP[0] + $TotalTvaR[0];
    }
    else{
      $totalP=$Total[0];
    }

    $result=$Total[0].'<>'.$totalP.'<>'.$pagnet['taux'];

    exit($result);
   }
   else if($operation == 4){
    
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stock = mysql_fetch_assoc($resS) ;

    $sqlQte="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and numligne!='".$numligne."' and idStock='".$ligne['idStock']."' ";
    $resQte = mysql_query($sqlQte) or die ("persoonel requête 2".mysql_error());
    $produitQte = mysql_fetch_assoc($resQte) ;

    $quantitePagnetQte='0-0';

    if( $produitQte != null){
          if(($produitQte['unitevente']!="Article")&&($produitQte['unitevente']!="article")){
            $quantitePagnetQte=$produitQte['quantite'].'-'.$stock['nbreArticleUniteStock'];
          }
          else if(($produitQte['unitevente']=="Article")||($produitQte['unitevente']=="article")){
            $quantitePagnetQte=$produitQte['quantite'].'-1';
          }
    }

    exit($quantitePagnetQte);
     
   }
   else if($operation == 5){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stock = mysql_fetch_assoc($resS) ;

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    $quantiteCourant=$stock['quantiteStockCourant'] - $quantite;
      if($quantiteCourant >= 0){
        $prixTotal=$quantite*$ligne['prixPublic'];

        $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");/**/
      }
    
    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    exit($Total[0]);

   } 
   else if($operation == 14){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stock = mysql_fetch_assoc($resS) ;

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){
      $quantiteCourant=$stock['quantiteStockinitial'] - ($designation['nbreArticleUniteStock'] * $quantite);
      if($quantiteCourant >= 0){
        $prixTotal=$quantite*$ligne['prixunitevente'];

        $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");/**/
      }
    }
    else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
      $quantiteCourant=$stock['quantiteStockinitial'] - $quantite;
      if($quantiteCourant >= 0){
        $prixTotal=$quantite*$ligne['prixunitevente'];

        $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");/**/
      }
    }
    


    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    exit($Total[0]);


   } 
   else if($operation == 7){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $prixTotal=$prix*$ligne['quantite'];
    $sql1="update `".$nomtableLigne."` set prixPublic=".$prix.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
    $res1=@mysql_query($sql1)or die ("modification impossible");/**/

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    $sql0="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($res0) ;

    $result=$Total[0].'<>'.$pagnet['taux'];

    exit($result);
   }
   else if($operation == 8){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $prixTotal=$quantite*$ligne['prixunitevente'];
    $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
    $res1=@mysql_query($sql1)or die ("modification impossible");/**/

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    exit($Total[0]);
   }
   else if($operation == 9){
    $uniteStock=@htmlspecialchars($_POST["uniteStock"]);
    
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $design = mysql_fetch_assoc($resS) ;

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    /*$sqlQte="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and numligne!='".$numligne."' and idStock='".$ligne['idStock']."' ";
    $resQte = mysql_query($sqlQte) or die ("persoonel requête 2".mysql_error());
    $produitQte = mysql_fetch_assoc($resQte) ;*/

    $sqlQte="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and numligne!='".$numligne."' and idDesignation='".$ligne['idDesignation']."' ";
    $resQte = mysql_query($sqlQte) or die ("persoonel requête 2".mysql_error());
    $produitQte = mysql_fetch_assoc($resQte) ;

    $quantitePagnetQte='0-0-'.$t_stock[0].'-'.$design['nbreArticleUniteStock'];

    if( $produitQte != null){
          if(($uniteStock!="Article")&&($uniteStock!="article")){
            $quantitePagnetQte=$produitQte['quantite'].'-'.$t_stock[0];
          }
          else if(($uniteStock=="Article")||($uniteStock=="article")){
            $quantitePagnetQte=$produitQte['quantite'].'-1';
          }
    }

    exit($quantitePagnetQte);
     
   }
   else if($operation == 10){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 
    $result="0";
    /*$sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stock = mysql_fetch_assoc($resS) ;*/

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;
    //$restant = $t_stock[0];

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    if(($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")){
      $quantiteCourant=$t_stock[0] - ($designation['nbreArticleUniteStock'] * $quantite);
      //if($quantiteCourant >= 0){
        $prixTotal=$quantite*$ligne['prixunitevente'];
        if($_SESSION['Pays']=='Canada'  && $designation['tva']==1){ 
          $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];
          $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];
          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
        else{
          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
      //}
      $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $Total = mysql_fetch_array($res2) ;

      if($_SESSION['Pays']=='Canada'){ 
        $sql3="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());
        $TotalTvaP = mysql_fetch_array($res3) ;
    
        $sql4="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
        $TotalTvaR = mysql_fetch_array($res4) ;

        $totalP=$Total[0] + $TotalTvaP[0] + $TotalTvaR[0];
      }
      else{
        $totalP=$Total[0];
      }

      $result='1<>'.$Total[0].'<>'.$quantite.'<>'.$ligne['prixunitevente'].'<>'.$totalP;
    }
    else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
      $quantiteCourant=$t_stock[0] - $quantite;
      //if($quantiteCourant >= 0){
        $prixTotal=$quantite*$ligne['prixunitevente'];
        if($_SESSION['Pays']=='Canada' && $designation['tva']==1){ 
          $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];
          $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];
          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
        else{
          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
      //}
      $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $Total = mysql_fetch_array($res2) ;

      if($_SESSION['Pays']=='Canada'){
        $sql3="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());
        $TotalTvaP = mysql_fetch_array($res3) ;
    
        $sql4="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
        $TotalTvaR = mysql_fetch_array($res4) ;
    
        $totalP=$Total[0] + $TotalTvaP[0] + $TotalTvaR[0];
      }
      else{
        $totalP=$Total[0];
      }

      $result='1<>'.$Total[0].'<>'.$quantite.'<>'.$ligne['prixunitevente'].'<>'.$totalP;
    }
    
    exit($result);
   }
   else if($operation == 11){
    
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    $sqlQte="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and numligne!='".$numligne."' and idDesignation='".$ligne['idDesignation']."' ";
    $resQte = mysql_query($sqlQte) or die ("persoonel requête 2".mysql_error());
    $produitQte = mysql_fetch_assoc($resQte) ;

    $quantitePagnetQte=$t_stock[0];

    $result=$quantitePagnetQte.'<>'.$ligne['designation'].'<>'.$numligne.'<>'.$idPagnet;

    exit($result);
     
   }
   else if($operation == 12){
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;
    //$restant = $t_stock[0];

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    $quantiteCourant=$t_stock[0] - $quantite;
    if($quantiteCourant >= 0){
      $prixTotal=$quantite*$ligne['prixPublic'];
      $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixTotal=".$prixTotal." where numligne='".$numligne."'";
      $res1=@mysql_query($sql1)or die ("");/**/
    }

    
    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    $sql0="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($res0) ;

    $result=$Total[0].'<>'.$pagnet['taux'];

    exit($result);


   }
   else if($operation == 120){
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;
    //$restant = $t_stock[0];

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    $quantiteCourant=$t_stock[0] - $quantite;
    if($quantiteCourant >= 0){
      $prixTotal=$quantite*$ligne['prixPublic'];
      $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixTotal=".$prixTotal." where numligne='".$numligne."'";
      $res1=@mysql_query($sql1)or die ("");/**/
    }

    
    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;
    $result=$Total[0].'<>'.$designation['designation'];
    exit($result);


   }
   else if($operation == 13){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $prixTotal=$quantite*$ligne['prixPublic'];
    $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
    $res1=@mysql_query($sql1)or die ("modification impossible");/**/

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    exit($Total[0]);
   }
   else if($operation == 20){

    $sql="select * from `aaa-devise`  where Devise='".$_SESSION['Pays']."'";
    $res=mysql_query($sql);
    if($tab=mysql_fetch_array($res)){
      $_SESSION['devise']=$tab[$devise];
    }

    $sql="select * from `aaa-devise`  where Devise='".$devise."'";
    $res=mysql_query($sql);
    if($tab=mysql_fetch_array($res)){
      $_SESSION['symbole']=$tab['Symbole'];
    }
    //$_SESSION['deviseF']=$devise;
    //exit($_SESSION['devise']);
   } 
   else if($operation == 150){

    $unitevente=@htmlspecialchars($_POST["unitevente"]);

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    $reste=0;
    $init="0";

    if(($unitevente!='Article')&&($unitevente!='article')){
      $quantiteCourant=$t_stock[0] - ($designation['nbreArticleUniteStock'] * $ligne['quantite']);
      $reste=$quantiteCourant;
      //if($quantiteCourant >= 0){
        $prixunitevente=$designation['prixuniteStock'];
        $prixTotal=$ligne['quantite']*$prixunitevente;
        if($_SESSION['Pays']=='Canada' && $designation['tva']==1){ 
          $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];
          $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
          $init='1';
        }
        else{
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
          $init='1';
        }
      //}
    }
    else if(($unitevente=='Article')||($unitevente=='article')){
      $quantiteCourant=$t_stock[0] - $ligne['quantite'];
      $reste=$quantiteCourant;
      //if($quantiteCourant >= 0){
        $prixunitevente=$designation['prix'];
        $prixTotal=$ligne['quantite']*$prixunitevente;
        if($_SESSION['Pays']=='Canada' && $designation['tva']==1){ 
          $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];
          $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
          $init='1';
        }
        else{
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
          $init='1';
        }
      //}
    }

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne='".$numligne."' ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne1 = mysql_fetch_assoc($res) ;

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    if($_SESSION['Pays']=='Canada'){ 
      $sql3="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res3 = mysql_query($sql3) or die ("persoonel requête 2".mysql_error());
      $TotalTvaP = mysql_fetch_array($res3) ;

      $sql4="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
      $res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
      $TotalTvaR = mysql_fetch_array($res4) ;

      $totalP=$Total[0] + $TotalTvaP[0] + $TotalTvaR[0];
    }
    else{
      $totalP=$Total[0];
    }

    $result=$ligne1['prixunitevente'].'§'.$Total[0].'§'.$init.'§'.$ligne1['quantite'].'§'.$totalP;

    exit($result);
   } 
   else if($operation == 15){
    
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $design = mysql_fetch_assoc($resS) ;

    
    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$ligne['idPagnet']."";
    $resP = mysql_query($sqlP) or die ("requete panier ".mysql_error());
    $panier = mysql_fetch_assoc($resP) ; 

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
    where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    $result='0-0';
    if ($panier['type']==10) {
    } else {
      if($design['uniteStock']==$ligne['unitevente']){ 
        $nbre=$quantite * $design['nbreArticleUniteStock'];
        $result=$t_stock[0].'-'.$nbre; 
      }
      else{
        $result=$t_stock[0].'-'.$quantite;
      }
    }
    

    exit($result);
     
   }
   else if($operation == 16){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    /*$sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stock = mysql_fetch_assoc($resS) ;*/
    
    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$ligne['idPagnet']."";
    $resP = mysql_query($sqlP) or die ("requete panier ".mysql_error());
    $panier = mysql_fetch_assoc($resP) ; 

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
     where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;
    //$restant = $t_stock[0];

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    if ($panier['type']==10) {
      if($designation['uniteStock']==$ligne['unitevente']){
        $quantiteCourant=$t_stock[0] - ($designation['nbreArticleUniteStock'] * $quantite);
        $prixTotal=$quantite*$ligne['prixunitevente'];

        $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");/**/
      }
      else{
        $quantiteCourant=$t_stock[0] - $quantite;
        $prixTotal=$quantite*$ligne['prixunitevente'];

        $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");/**/
      }
    } else {
      if($designation['uniteStock']==$ligne['unitevente']){
        $quantiteCourant=$t_stock[0] - ($designation['nbreArticleUniteStock'] * $quantite);
        if($quantiteCourant >= 0){
          $prixTotal=$quantite*$ligne['prixunitevente'];

          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        } 
      }
      else{
        $quantiteCourant=$t_stock[0] - $quantite;
        if($quantiteCourant >= 0){
          $prixTotal=$quantite*$ligne['prixunitevente'];

          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }
      }
    }
    

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    exit($Total[0]);


   }
   else if($operation == 17){

    $unitevente=@htmlspecialchars($_POST["unitevente"]);

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 1".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` 
    where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    $reste=0;
    $result=0;

    if($designation['uniteStock']==$unitevente){
      $quantiteCourant=$t_stock[0] - ($designation['nbreArticleUniteStock'] * $ligne['quantite']);
      $reste=$quantiteCourant;
      // var_dump("1 ".$quantiteCourant);
      if($quantiteCourant >= 0){
        // var_dump("if 1 ".$quantiteCourant);
        $prixunitevente=$designation['prixuniteStock'];
        $prixTotal=$ligne['quantite']*$prixunitevente;
        $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");
      }  else {
          
        // var_dump("else 1 ".$quantiteCourant);
        $result="1";
      }
    }
    else{
      if($quantite==2){
        $demi=$designation['nbreArticleUniteStock']/2;
        $quantiteCourant=$t_stock[0] - ($ligne['quantite'] * $demi);
        $reste=$quantiteCourant;
        // var_dump("2 ".$quantiteCourant);
        if($quantiteCourant >= 0){
          // var_dump("if 2 ".$quantiteCourant);
          $prixunitevente=$designation['prixuniteStock']/2;
          $prixTotal=$ligne['quantite']*$prixunitevente;
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
        } else {
          
          // var_dump("else 2 ".$quantiteCourant);
          $result="1";
        }
      }
      else{
        $quantiteCourant=$t_stock[0] - $ligne['quantite'];
        $reste=$quantiteCourant;
        // var_dump("3 ".$quantiteCourant);        
        if($quantiteCourant >= 0){
          
          // var_dump("if 3 ".$quantiteCourant);
          $prixunitevente= number_format(($designation['prixuniteStock'] / $designation['nbreArticleUniteStock']), 0, '', '');
          $prixTotal=$ligne['quantite']*$prixunitevente;
          $sql1="update `".$nomtableLigne."` set unitevente='".$unitevente."',prixunitevente=".$prixunitevente.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");
        } else {
          
          // var_dump("else 3 ".$quantiteCourant);
          $result="1";
        }
      }

    }

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne='".$numligne."' ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne1 = mysql_fetch_assoc($res) ;

    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 3".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    if ($result=="1") {
      
      $result="1__".$ligne1['unitevente'].'§'.$Total[0].'§'.$designation['nbreArticleUniteStock'];
    } else {
      
      $result="0__".$ligne1['prixunitevente'].'§'.$Total[0].'§'.$designation['nbreArticleUniteStock'];
    }
    

    exit($result);
   }
   else if($operation == 18){

    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` 
    where idDesignation=".$ligne['idDesignation']." AND idEntrepot='".$idEntrepot."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD);

    $result=0;
    if($designation['uniteStock']==$ligne['unitevente']){
      $nbre=$ligne['quantite'] * $designation['nbreArticleUniteStock'];
      $result=$t_stock[0].'<>'.$nbre.'<>'.$ligne['idEntrepot']; 
    }
    else{
      $result=$t_stock[0].'<>'.$ligne['quantite'].'<>'.$ligne['idEntrepot'];
    }

    exit($result);

   }
   else if($operation == 19){
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 
    $result='0'; 
    if($ligne!=null){
      $sql1="UPDATE `".$nomtableLigne."` set idEntrepot='".$idEntrepot."' where numligne='".$numligne."'";
      $res1=@mysql_query($sql1)or die ("");
      $result='1';
    }
    exit($result);
   }
   else if($operation == 21){

    $compte=@htmlspecialchars($_POST["compte"]);
    $caisse=@htmlspecialchars($_POST["caisse"]);
    $montantAvant=0;
    $result="0";

    $reqA="SELECT * from `".$nomtablePagnet."` where idPagnet='".$idPagnet."' ";
    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error()); 
    $panier = mysql_fetch_array($resA);
    if($panier!=null){
      $sql0="SELECT * FROM `".$nomtableDesignation."` where description=".$idTransaction;
      $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());
      $trans = mysql_fetch_array($res0);
      $dateDetails=explode("-", $panier['datepagej']);
      $jour=$dateDetails[2].'-'.$dateDetails[1].'-'.$dateDetails[0];

      $sql1="SELECT * FROM `".$nomtableTransfert."` where dateTransfert='".$jour."' AND idTransaction='".$idTransaction."' ORDER BY idTransfert ASC";
      $res1=mysql_query($sql1) or die ("select stock impossible =>".mysql_error());
      $transDebut = mysql_fetch_array($res1);
      if($transDebut!=null){
        $montantAvant=$transDebut['compte'];
        $sql7="INSERT into `".$nomtableTransfert."` (idPagnet, idTransaction, nomTransfert, compte, caisse, dateTransfert, heureTransfert,montantAvant)
        values(".$idPagnet.",".$idTransaction.",'".$trans['designation']."','".$compte."',0,'".$jour."','".$heureString."','".$montantAvant."')";
        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

      }
      else{
        $montantAvant=$compte;
        $sql7="INSERT into `".$nomtableTransfert."` (idPagnet, idTransaction, nomTransfert, compte, caisse, dateTransfert, heureTransfert,montantAvant)
        values(".$idPagnet.",".$idTransaction.",'".$trans['designation']."','".$compte."',0,'".$jour."','".$heureString."','".$montantAvant."')";
        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
        $result="1";
      }

    } 

    $sql2="SELECT * FROM `".$nomtableTransfert."` where idPagnet='".$idPagnet."' ";
    $res2=mysql_query($sql2) or die ("select stock impossible =>".mysql_error());
    $somInitiale=0;
    $somFinale=0;
    while ($pagnet = mysql_fetch_assoc($res2)) {
      $somInitiale=$somInitiale + $pagnet['montantAvant'];
      $somFinale=$somFinale + $pagnet['compte'];
    }

    $commission=$somFinale - $somInitiale;

    $sql1="UPDATE `".$nomtablePagnet."` set totalp='".$commission."',apayerPagnet='".$commission."',verrouiller=1 where idPagnet='".$idPagnet."' ";
    $res1=@mysql_query($sql1)or die ("modification impossible");
    if($res1){ 
      $rapport=$compte - $montantAvant;
      $result='1<>'.$trans['designation'].'<>'.$montantAvant.'<>'.$compte.'<>'.$rapport.'<>'.$commission;
    }

    exit($result);
   }
   else if($operation == 22){
    $especes=@htmlspecialchars($_POST["especes"]);
    $result='0';
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $sql1="UPDATE `".$nomtableLigne."` set unitevente='".$especes."' where numligne='".$numligne."'";
    $res1=@mysql_query($sql1)or die ("modification impossible");/**/
    if($res1){
      $result='1';
    }

    exit($result);
   }
   else if($operation == 23){
    $designation=@htmlspecialchars($_POST["designation"]);
    $result='0';
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    
    $sql1="UPDATE `".$nomtableLigne."` set designation='".$designation."' where numligne='".$numligne."'";
    $res1=@mysql_query($sql1)or die ("modification impossible");/**/
    if($res1){
      $result='1';
    }

    exit($result);
   }
   else if($operation == 24){
    $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ;  
    $reponse="0";
    $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());
    if($resMP){
      $pagnet = mysql_fetch_assoc($resMP) ;

      $prixTotal=$prix*$ligne['quantite'];
      $sql1="update `".$nomtableLigne."` set prixPublic=".$prix.",prixtotal=".$prixTotal." where numligne='".$numligne."'";
      $res1=@mysql_query($sql1)or die ("modification impossible");

      $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $Total = mysql_fetch_array($res2) ;

      $reponse="1<>".$pagnet['taux']."<>".$Total[0];
    }

    exit($reponse);
   }
   else if($operation == 25){
    $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 

    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
    $t_stock = mysql_fetch_array($res_t) ;

    $sqlQte="SELECT * FROM `".$nomtableLigne."` where idMutuellePagnet='".$idMutuellePagnet."' and numligne!='".$numligne."' and idDesignation='".$ligne['idDesignation']."' ";
    $resQte = mysql_query($sqlQte) or die ("persoonel requête 2".mysql_error());
    $produitQte = mysql_fetch_assoc($resQte) ;

    $quantitePagnetQte=$t_stock[0];

    $result=$quantitePagnetQte.'<>'.$ligne['designation'].'<>'.$numligne.'<>'.$idMutuellePagnet;

    exit($result);
     
   }
   else if($operation == 26){
    $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 
    $reponse="0";
    $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());
    if($resMP){
      $pagnet = mysql_fetch_assoc($resMP) ;

      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
      $t_stock = mysql_fetch_array($res_t) ;
      //$restant = $t_stock[0];

      $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
      $designation = mysql_fetch_array($resD);

      $quantiteCourant=$t_stock[0] - $quantite;
      if($quantiteCourant >= 0){
        $prixTotal=$quantite*$ligne['prixPublic'];
        $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixTotal=".$prixTotal." where numligne='".$numligne."'";
        $res1=@mysql_query($sql1)or die ("");/**/
      }

      $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $Total = mysql_fetch_array($res2) ;

      $reponse="1<>".$pagnet['taux']."<>".$Total[0];
    }

    exit($reponse);

   }
   else if($operation == 27){
    $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);
    $sql="SELECT * FROM `".$nomtableLigne."` where numligne=".$numligne."";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    $ligne = mysql_fetch_assoc($res) ; 
    $result="0";
    $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());
    if($resMP){
        $pagnet = mysql_fetch_assoc($resMP) ;
        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
        $t_stock = mysql_fetch_array($res_t) ;
        //$restant = $t_stock[0];

        $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
        $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
        $designation = mysql_fetch_array($resD);

        $quantiteCourant=$t_stock[0] - $quantite;
        if($quantiteCourant >= 0){
          $prixTotal=$quantite*$ligne['prixPublic'];
          $sql1="update `".$nomtableLigne."` set quantite=".$quantite.",prixTotal=".$prixTotal." where numligne='".$numligne."'";
          $res1=@mysql_query($sql1)or die ("");/**/
        }

        
        $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        $Total = mysql_fetch_array($res2) ;
        $result='1<>'.$pagnet['taux'].'<>'.$Total[0].'<>'.$designation['designation'];
    }
    exit($result);


   }
   else if($operation == 28){
    $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);
    $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $Total = mysql_fetch_array($res2) ;

    exit($Total[0]);
   }
   else if($operation == 29){
    $idMutuelle=@$_POST['idMutuelle'];
    $idMutuellePagnet=@$_POST['idMutuellePagnet'];
    $reponse="0";
    $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());
    if($resMP){
      $sqlM="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$idMutuelle." ";
      $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
      $mutuelle=mysql_fetch_array($resM);
  
      $sql3="UPDATE `".$nomtableMutuellePagnet."` set idMutuelle=".$idMutuelle.",taux=".$mutuelle['tauxMutuelle']." WHERE idMutuellePagnet='".$idMutuellePagnet."'";
      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

      $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $Total = mysql_fetch_array($res2) ;

      $reponse="1<>".$mutuelle['tauxMutuelle']."<>".$Total[0]."<>".$mutuelle['taux1']."<>".$mutuelle['taux2']."<>".$mutuelle['taux3']."<>".$mutuelle['taux4']."<>".$idMutuelle."<>".$idMutuellePagnet;

      
    }
    exit($reponse);
  }
  else if($operation == 31){

    $sql3="UPDATE `".$nomtablePagnet."` SET taux='".$taux."' WHERE idPagnet='".$idPagnet."' ";
    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
    
    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
    $TotalT = mysql_fetch_array($resT) ;

    $sql0="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($res0) ;

    $result=$TotalT[0].'<>'.$pagnet['taux'];

    exit($result);
     
  }
  else if($operation == 32){

    $sql3="UPDATE `".$nomtableClient."` SET taux='".$taux."' WHERE idClient='".$idClient."' ";
    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
    
    $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient." ";
    $res2=mysql_query($sql2) or die ("insertion client impossible =>".mysql_error() );
    $client = mysql_fetch_array($res2) ;

    $result='1<>'.$client['taux'];

    exit($result);
     
  }
  else if($operation == 33){
    $tauxMutuelle=@$_POST['tauxMutuelle'];
    $idMutuelle=@$_POST['idMutuelle'];
    $idMutuellePagnet=@$_POST['idMutuellePagnet'];
    $reponse="0";
    $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());
    if($resMP){
      $sqlM="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$idMutuelle." ";
      $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
      $mutuelle=mysql_fetch_array($resM);

      if($mutuelle['tauxMutuelle']==$tauxMutuelle || $mutuelle['taux1']==$tauxMutuelle || $mutuelle['taux2']==$tauxMutuelle || $mutuelle['taux3']==$tauxMutuelle || $mutuelle['taux4']==$tauxMutuelle){
        $sql3="UPDATE `".$nomtableMutuellePagnet."` set idMutuelle=".$idMutuelle.",taux=".$tauxMutuelle." WHERE idMutuellePagnet='".$idMutuellePagnet."'";
        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
      }
  
      $sql2="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idMutuellePagnet=".$idMutuellePagnet." ";
      $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
      $Total = mysql_fetch_array($res2) ;

      $reponse="1<>".$tauxMutuelle."<>".$Total[0];
    }
    exit($reponse);
  }
}

?>
