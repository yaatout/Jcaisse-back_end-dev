
<?php

session_start();

date_default_timezone_set('Africa/Dakar');

if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/
    
$operation=@htmlspecialchars($_POST["operation"]);
$query=@htmlspecialchars($_POST["query"]);
$action=@htmlspecialchars($_POST["action"]);

$idStock=@htmlspecialchars($_POST["idStock"]);

$designation=@htmlspecialchars($_POST["designation"]);
$categorie=@htmlspecialchars($_POST["categorie"]);
$tableau=@htmlspecialchars($_POST["tableau"]);
$forme=@htmlspecialchars($_POST["forme"]);

$operation=@htmlspecialchars($_POST["operation"]);
$idDesignation=@htmlspecialchars($_POST["idDesignation"]);
$quantite=@htmlspecialchars($_POST["quantite"]);
$codeBarre=@htmlspecialchars($_POST["codeBarre"]);

$prixUniteStock=@htmlspecialchars($_POST["prixuniteStock"]);
$prix=@htmlspecialchars($_POST["prix"]);
$prixAchat=@htmlspecialchars($_POST["prixachat"]);

$prixSession=@$_POST["prixSession"];
$prixPublic=@$_POST["prixPublic"];

$archiver=@htmlspecialchars($_POST["archiver"]);

if ($operation == 1) {

    $result="0";
    $quantiteInventaire=$quantite;
        
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
    $res1=mysql_query($sql1);
    $produit=mysql_fetch_array($res1);

    $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'";
    $res=mysql_query($sql);
    $sommeStock=mysql_fetch_array($res);
            
    $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;

    $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idDesignation=".$idDesignation;
    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
    if (($prixSession != $produit['prixSession']) || ($prixPublic != $produit['prixPublic'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set prixSession='".$prixSession."', prixPublic='".$prixPublic."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    } 

    if (($codeBarre != null) && ($codeBarre != '') && ($codeBarre != $produit['codeBarreDesignation'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$codeBarre."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    }

    if (($categorie != null) && ($categorie != '') && ($categorie != $produit['categorie'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set categorie='".$categorie."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    }

    if (($forme != null) && ($forme != '') && ($forme != $produit['forme'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set forme='".$forme."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    }

    $sql2='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($produit["designation"]).'",'.$quantiteInventaire.','.$produit['prixPublic'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';
    $res2=@mysql_query($sql2) or die ("insertion stock 1 impossible".mysql_error()) ; 

    $sql3="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' AND dateStockage='".$dateString."' AND iduser='".$_SESSION['iduser']."' ORDER BY idStock DESC ";
    $res3=mysql_query($sql3);
    $stock=mysql_fetch_array($res3);

    $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,idStock,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
    VALUES ('.$produit['idDesignation'].','.$stock['idStock'].','.$quantiteInventaire.',1,'.$quantiteStockCourantTotal.',"'.$dateString.'",10)';
    $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

    exit($result);

}
else if ($operation == 2) {

    $result="0";
    $quantiteInventaire=$quantite;
        
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
    $res1=mysql_query($sql1);
    $produit=mysql_fetch_array($res1);

    $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'";
    $res=mysql_query($sql);
    $sommeStock=mysql_fetch_array($res);
            
    $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;

    $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idDesignation=".$idDesignation;
    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

    if (($prixAchat != $produit['prixachat']) || ($prix != $produit['prix'])  || ($prixUniteStock != $produit['prixuniteStock'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set prixachat='".$prixAchat."', prix='".$prix."', prixuniteStock='".$prixUniteStock."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    } 

    if (($codeBarre != null) && ($codeBarre != '') && ($codeBarre != $produit['codeBarreDesignation'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$codeBarre."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    }

    if (($categorie != null) && ($categorie != '') && ($categorie != $produit['categorie'])) {
        $sqlS="UPDATE `".$nomtableDesignation."` set categorie='".$categorie."' where idDesignation=".$idDesignation;
        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    }

    $sql2='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($produit["designation"]).'",'.$quantiteInventaire.',"Article",'.$produit['prix'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';
    $res2=@mysql_query($sql2) or die ("insertion stock 1 impossible".mysql_error()) ; 

    $sql3="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' AND dateStockage='".$dateString."' AND iduser='".$_SESSION['iduser']."' ORDER BY idStock DESC ";
    $res3=mysql_query($sql3);
    $stock=mysql_fetch_array($res3);

    $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,idStock,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
    VALUES ('.$produit['idDesignation'].','.$stock['idStock'].','.$quantiteInventaire.','.$produit['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",10)';
    $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



    exit($result);

}
else if ($operation == 3) {
    $result="0";

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
    $res1=mysql_query($sql1);
    
    $designation=mysql_fetch_array($res1);
    if($designation!=null){
        $sqlA="UPDATE `".$nomtableDesignation."` set archiver=1 WHERE idDesignation=".$idDesignation;
        $resA=mysql_query($sqlA) or die ("update quantiteStockCourant impossible =>".mysql_error());
        $result="1";
    }
    exit($result);

}
else if ($operation == 4) {
    $result="0";
    
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
    $res1=mysql_query($sql1);
    
    $designation=mysql_fetch_array($res1);
    if($designation!=null){
       $result="1";
    }


    exit($result);

}
else if ($operation == 5) {
    $result="0";
    $archiver=@htmlspecialchars($_POST["archiver"]);

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
    $res1=mysql_query($sql1);
    
    $designation=mysql_fetch_array($res1);
    if($designation!=null){
        $sqlA="UPDATE `".$nomtableDesignation."` set archiver='".$archiver."' WHERE idDesignation=".$idDesignation;
        $resA=mysql_query($sqlA) or die ("update quantiteStockCourant impossible =>".mysql_error());
        $result="1";
    }


    exit($result);

}

