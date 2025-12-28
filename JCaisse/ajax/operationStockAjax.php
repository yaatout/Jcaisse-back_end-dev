
<?php

session_start();

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
$idEntrepotStock=@htmlspecialchars($_POST["idEntrepotStock"]);
// $designation=@htmlspecialchars($_POST["designation"]);
// $idDesignation=@htmlspecialchars($_POST["idDesignation"]);
// $idBC=@htmlspecialchars($_POST["idBC"]);
// $idComposantBC=@htmlspecialchars($_POST["idComposant"]);
// $quantite=@htmlspecialchars($_POST["quantite"]);
// $uniteCommande=@htmlspecialchars($_POST["uniteCommande"]);
// $prixAchat=@htmlspecialchars($_POST["prixAchat"]);
// $dateExpiration=@htmlspecialchars($_POST["dateExpiration"]);

 if($operation == 1) {
    
    $sql="SELECT * FROM `". $nomtableEntrepot."` ORDER BY nomEntrepot";
    $resE=mysql_query($sql);

    $rows=["0<>Sans depot"];

    while ($e=mysql_fetch_array($resE)) {
        # code...trepot['idEntrepot'].'<>'.$entrepot['nomEntrepot']
        $rows[]=$e['idEntrepot'].'<>'.$e['nomEntrepot'];
    }

    echo json_encode($rows);

} else if($operation == 2) {
    
    $sql="SELECT * FROM `". $nomtableEntrepot."` ORDER BY nomEntrepot";
    $resE=mysql_query($sql);

    $rows=["0<>-----------"];

    while ($e=mysql_fetch_array($resE)) {
        # code...trepot['idEntrepot'].'<>'.$entrepot['nomEntrepot']
        $rows[]=$e['idEntrepot'].'<>'.$e['nomEntrepot'];
    }

    echo json_encode($rows);

} else if($operation == 3) {

    $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."' ";
    $res=mysql_query($sql);
    $stock=mysql_fetch_array($res);

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $design=mysql_fetch_array($res1);
    
    $sql2="SELECT * FROM `". $nomtableEntrepot."` WHERE idEntrepot<>'".$stock['idEntrepot']."' ORDER BY nomEntrepot";
    $res2=mysql_query($sql2);

    $sql3="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";
    $res3=mysql_query($sql3);
    $depot=mysql_fetch_array($res3);

    while ($e=mysql_fetch_array($res2)) {
        # code...trepot['idEntrepot'].'<>'.$entrepot['nomEntrepot']
        if($depot!=null){
            $rows[]=$e['idEntrepot'].'<>'.$e['nomEntrepot'].'<>'.$stock['designation'].'<>'.$stock['dateStockage'].'<>'.$depot['nomEntrepot'].'<>'.$stock['quantiteStockinitial'].'<>'.($stock['quantiteStockCourant'] / $design['nbreArticleUniteStock']);
        }
        else{
            $rows[]=$e['idEntrepot'].'<>'.$e['nomEntrepot'].'<>'.$stock['designation'].'<>'.$stock['dateStockage'].'<>NEANT<>'.$stock['quantiteStockinitial'].'<>'.($stock['quantiteStockCourant'] / $design['nbreArticleUniteStock']);
        }

    }

    echo json_encode($rows);

} else if($operation == 4) {

    $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."' ";
    $res=mysql_query($sql);
    $stock=mysql_fetch_array($res);

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $design=mysql_fetch_array($res1);
    
    $sql2="SELECT * FROM `". $nomtableEntrepot."` WHERE idEntrepot<>'".$stock['idEntrepot']."' ORDER BY nomEntrepot";
    $res2=mysql_query($sql2);

    $sql3="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";
    $res3=mysql_query($sql3);
    $depot=mysql_fetch_array($res3);

    $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` 
    where quantiteStockCourant<>0 AND idDesignation ='".$stock['idDesignation']."' AND idEntrepot='".$stock['idEntrepot']."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);

    while ($e=mysql_fetch_array($res2)) {
        # code...trepot['idEntrepot'].'<>'.$entrepot['nomEntrepot']
        if($depot!=null){
            $rows[]=$e['idEntrepot'].'<>'.$e['nomEntrepot'].'<>'.$stock['designation'].'<>'.$depot['nomEntrepot'].'<>'.($S_stock[0]/$design['nbreArticleUniteStock']).'<>'.$stock['idDesignation'].'<>'.$stock['idEntrepot'];
        }
        else{
            $rows[]=$e['idEntrepot'].'<>'.$e['nomEntrepot'].'<>'.$stock['designation'].'<>NEANT<>'.($S_stock[0]/$design['nbreArticleUniteStock']).'<>'.$stock['idDesignation'].'<>'.$stock['idEntrepot'];
        }

    }

    echo json_encode($rows);

}