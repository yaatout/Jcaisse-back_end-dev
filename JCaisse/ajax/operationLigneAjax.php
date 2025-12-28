<?php

session_start();
if (!$_SESSION['iduser']) {
    header('Location:../index.php');
}

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php');


$beforeTime = '00:00:00';
$afterTime = '06:00:00';

// var_dump(date('d-m-Y',strtotime("-1 days")));

if ($_SESSION['Pays'] == 'Canada') {
    $date = new DateTime();
    $timezone = new DateTimeZone('Canada/Eastern');
} else {
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString = $date->format('H:i:s');

if ($heureString >= $beforeTime && $heureString < $afterTime) {
    // var_dump ('is between');
    $date = new DateTime(date('d-m-Y', strtotime("-1 days")));
}

// $date->setTimezone($timezone);
$annee = $date->format('Y');
$mois = $date->format('m');
$jour = $date->format('d');
$dateString = $annee . '-' . $mois . '-' . $jour;
$dateString2 = $jour . '-' . $mois . '-' . $annee;
$dateHeures = $dateString . ' ' . $heureString;

$operation = $_POST['operation'];


$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=" . $_SESSION['iduser'];
$resU = mysql_query($sqlU) or die("persoonel requête 2" . mysql_error());
$user = mysql_fetch_array($resU);

// $iduser = $user['idutilisateur'];

if ($operation == 1) {
    # code...

    $numLigne = $_POST['numLigne'];

    $sqlUpdateLigne = $bdd->prepare('UPDATE `' . $nomtableLigne . '` SET depotConfirm = 1 WHERE numLigne = :numLigne');
    $sqlUpdateLigne->execute(array(
        'numLigne' => $numLigne
    )) or die(print_r('Update ligne ' . $sqlUpdateLigne->errorInfo()));
    $sqlUpdateLigne->closeCursor();

    exit(1);
} else if ($operation == 2) {
    # code...

    $numLigne = $_POST['numLigne'];

    $sqlUpdateLigne = $bdd->prepare('UPDATE `' . $nomtableLigne . '` SET depotConfirm = 0 WHERE numLigne = :numLigne');
    $sqlUpdateLigne->execute(array(
        'numLigne' => $numLigne
    )) or die(print_r('Update ligne ' . $sqlUpdateLigne->errorInfo()));
    $sqlUpdateLigne->closeCursor();

    exit(1);
} else if ($operation == 3) {

    $idPanier = $_POST['idPanier'];
    $nbColis = $_POST['nbColis'];
    $size = 0;

    $sqlGetLigne1 = $bdd->prepare("SELECT * FROM `" . $nomtableLigne . "` where depotConfirm=0 and idPagnet = " . $idPanier . " and idEntrepot = " . $user['idEntrepot']);
    $sqlGetLigne1->execute() or die(print_r($sqlGetLigne1->errorInfo()));
    $items = $sqlGetLigne1->fetchAll();

    $size = count($items);

    if ($size == 0) {
        $sql4 = "UPDATE `" . $nomtablePagnet . "` SET nbColis=" . $nbColis . " where idPagnet=" . $idPanier;

        $res4 = @mysql_query($sql4) or die("mise à jour verouillage  impossible 1--- " . mysql_error());
        // var_dump($size);
    }
    echo ($size);
} else if ($operation == 4) {

    $idPanier = $_POST['idPanier'];
    $size = 0;

    $sqlGetLigne1 = $bdd->prepare("SELECT * FROM `" . $nomtableLigne . "` where depotConfirm=0 and idPagnet = " . $idPanier);
    $sqlGetLigne1->execute() or die(print_r($sqlGetLigne1->errorInfo()));
    $items = $sqlGetLigne1->fetchAll();

    $size = count($items);

    if ($size == 0) {
        # code...
        $sql4 = "UPDATE `" . $nomtablePagnet . "` SET terminerProforma=1 where idPagnet=" . $idPanier;

        $res4 = @mysql_query($sql4) or die("mise à jour verouillage  impossible 1--- " . mysql_error());
    }
    // var_dump($size);
    echo ($size);
} else if ($operation == 5) {

    $idPanier = $_POST['idPanier'];
    # code...
    $sql4 = "UPDATE `" . $nomtablePagnet . "` SET terminerProforma=0 where idPagnet=" . $idPanier;

    $res4 = @mysql_query($sql4) or die("mise à jour verouillage  impossible 1--- " . mysql_error());

    // var_dump($size);
    echo (1);
} else if ($operation == 6) {

    $idPanier = $_POST['idPanier'];
    $numLigne = $_POST['numLigne'];
    $quantite = $_POST['quantite'];

    $sql40 = "UPDATE `" . $nomtableLigne . "` SET quantite=" . $quantite . ", prixtotal=(prixunitevente*" . $quantite . ") where numLigne=" . $numLigne;

    $res40 = @mysql_query($sql40) or die("mise à jour verouillage  impossible 1--- " . mysql_error());


    $sqlGetLigne1 = $bdd->prepare("SELECT sum(`quantite`*`prixunitevente`) FROM `" . $nomtableLigne . "` where idPagnet = " . $idPanier);
    $sqlGetLigne1->execute() or die(print_r($sqlGetLigne1->errorInfo()));
    $items = $sqlGetLigne1->fetch();
    $totalPagnet = $items[0];

    // var_dump($totalPagnet);


    $sql4 = "UPDATE `" . $nomtablePagnet . "` SET totalp=" . $totalPagnet . ", apayerPagnet=(" . $totalPagnet . "-remise) where idPagnet=" . $idPanier;

    $res4 = @mysql_query($sql4) or die("mise à jour verouillage  impossible 1--- " . mysql_error());



    echo (1);
} else if ($operation == 7) {

    $idDesignation = $_POST['idDesignation'];
    $qtyRayon = $_POST['qtyRayon'];

    $sql4 = "UPDATE `" . $nomtableStock . "` SET quantiteStockTemp=quantiteStockTemp+" . $qtyRayon . " where idDesignation=" . $idDesignation;

    $res4 = @mysql_query($sql4) or die("mise à jour quantiteStockTemp  impossible 1--- " . mysql_error());

    echo (1);
} else if ($operation == 8) {

    $idDesignation = $_POST['idDesignation'];
    $prixUniteStock = $_POST['prixUniteStock'];
    $prixUnitaire = $_POST['prixUnitaire'];
    $prixAchat = $_POST['prixAchat'];
    $qtyDepot = $_POST['qtyDepot'];
    $qtyRayonInv = $_POST['qtyRayonInv'];

    $result = "0";

    // $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'";
    // $res=mysql_query($sql);
    // $sommeStock=mysql_fetch_array($res);

    // $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;

    // $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
    // $resS01s=mysql_query($sql01s);
    // $stock=mysql_fetch_array($resS01s);
    // $idLastS=$stock['idStock'];

    $sql2 = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation='" . $idDesignation . "'";
    $res2 = mysql_query($sql2);
    $design = mysql_fetch_array($res2);

    if ($qtyDepot != 0 && $qtyDepot != null) {

        $quantiteInventaire = $qtyDepot;
        // if ($quantiteStockCourantTotal > 0) { 

        //     $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];
        //     // $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];quantiteStockTemp


        //     $sqlS0="UPDATE `".$nomtableStock."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantiteStockTemp=quantiteStockTemp+".$totalArticleStock.", quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idStock=".$idLastS;

        //     $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

        //     $sqlSI="UPDATE `".$nomtableInventaire."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantite=quantite+".$quantiteInventaire." where idStock=".$idLastS;

        //     $resSI=mysql_query($sqlSI) or die ("update nomtableInventaire impossible =>".mysql_error());

        //     if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

        //     } else {

        //         $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
        //         $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());

        //     }

        //     $result = "1";

        // } else {

        $totalArticleStock = $quantiteInventaire;
        // $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];

        if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {

            $sql01 = 'INSERT INTO `' . $nomtableStock . '`(idDesignation,designation,quantiteStockinitial,uniteStock,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, quantiteStockTemp) VALUES(' . $idDesignation . ',"' . mysql_real_escape_string($design['designation']) . '",' . $quantiteInventaire . ',"' . mysql_real_escape_string($design['uniteStock']) . '",' . $design['prixPublic'] . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",' . $totalArticleStock . ',' . $totalArticleStock . ')';

            $res01 = @mysql_query($sql01) or die("insertion stock 2 impossible" . mysql_error());
        } else {

            $sql01 = 'INSERT INTO `' . $nomtableStock . '`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, quantiteStockTemp) VALUES(' . $idDesignation . ',"' . mysql_real_escape_string($design['designation']) . '",' . $quantiteInventaire . ',"' . mysql_real_escape_string($design['uniteStock']) . '",' . $design['prix'] . ',' . $design['prixuniteStock'] . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",' . $totalArticleStock . ',' . $totalArticleStock . ')';

            $res01 = @mysql_query($sql01) or die("insertion stock 2 impossible" . mysql_error());


            $sql0 = "UPDATE `" . $nomtableDesignation . "` set prixuniteStock='" . $prixUniteStock . "', prix='" . $prixUnitaire . "', prixachat='" . $prixAchat . "' WHERE idDesignation='" . $idDesignation . "' ";
            $res0 = mysql_query($sql0) or die("update prixuniteStock impossible =>" . mysql_error());
        }

        $sql01s = "SELECT * FROM `" . $nomtableStock . "` where idDesignation='" . $idDesignation . "' ORDER BY idStock DESC LIMIT 1";
        $resS01s = mysql_query($sql01s);
        $stock01s = mysql_fetch_array($resS01s);
        $idLastS = $stock01s['idStock'];

        $sql4 = 'INSERT INTO `' . $nomtableInventaire . '` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            VALUES(' . $idLastS . ',' . $design['idDesignation'] . ',' . $quantiteInventaire . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",0)';
        $res4 = mysql_query($sql4) or die("insertion stock 2 impossible" . mysql_error());

        // $result = $qtyDepot." qtyDepot";
        $result = "1";
        // }


    } else if ($qtyRayonInv != 0 && $qtyRayonInv != null) {

        $quantiteInventaire = $qtyRayonInv;
        // if ($quantiteStockCourantTotal > 0) { 

        //     $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];
        //     // $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];quantiteStockTemp


        //     $sqlS0="UPDATE `".$nomtableStock."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantiteStockTemp=quantiteStockTemp+".$totalArticleStock.", quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idStock=".$idLastS;

        //     $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

        //     $sqlSI="UPDATE `".$nomtableInventaire."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantite=quantite+".$quantiteInventaire." where idStock=".$idLastS;

        //     $resSI=mysql_query($sqlSI) or die ("update nomtableInventaire impossible =>".mysql_error());

        //     if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

        //     } else {

        //         $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
        //         $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());

        //     }

        //     $result = "1";

        // } else {

        $totalArticleStock = $quantiteInventaire;
        // $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];

        if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {

            $sql01 = 'INSERT INTO `' . $nomtableStock . '`(idDesignation,designation,quantiteStockinitial,uniteStock,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, quantiteStockTemp) VALUES(' . $idDesignation . ',"' . mysql_real_escape_string($design['designation']) . '",' . $quantiteInventaire . ',"' . mysql_real_escape_string($design['uniteStock']) . '",' . $design['prixPublic'] . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",' . $totalArticleStock . ',' . $totalArticleStock . ')';

            $res01 = @mysql_query($sql01) or die("insertion stock 2 impossible" . mysql_error());
        } else {

            $sql01 = 'INSERT INTO `' . $nomtableStock . '`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, quantiteStockTemp) VALUES(' . $idDesignation . ',"' . mysql_real_escape_string($design['designation']) . '",' . $quantiteInventaire . ',"' . mysql_real_escape_string($design['uniteStock']) . '",' . $design['prix'] . ',' . $design['prixuniteStock'] . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",' . $totalArticleStock . ',' . $totalArticleStock . ')';

            $res01 = @mysql_query($sql01) or die("insertion stock 2 impossible" . mysql_error());


            $sql0 = "UPDATE `" . $nomtableDesignation . "` set prixuniteStock='" . $prixUniteStock . "', prix='" . $prixUnitaire . "', prixachat='" . $prixAchat . "' WHERE idDesignation='" . $idDesignation . "' ";
            $res0 = mysql_query($sql0) or die("update prixuniteStock impossible =>" . mysql_error());
        }

        $sqlS0 = "UPDATE `" . $nomtableStock . "` set quantiteStockTemp=quantiteStockTemp+" . $totalArticleStock . " where idDesignation=" . $idDesignation;

        $resS0 = mysql_query($sqlS0) or die("update quantiteStockCourant impossible =>" . mysql_error());

        $sql01s = "SELECT * FROM `" . $nomtableStock . "` where idDesignation='" . $idDesignation . "' ORDER BY idStock DESC LIMIT 1";
        $resS01s = mysql_query($sql01s);
        $stock01s = mysql_fetch_array($resS01s);
        $idLastS = $stock01s['idStock'];

        $sql4 = 'INSERT INTO `' . $nomtableInventaire . '` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            VALUES(' . $idLastS . ',' . $design['idDesignation'] . ',' . $quantiteInventaire . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",0)';
        $res4 = mysql_query($sql4) or die("insertion stock 2 impossible" . mysql_error());


        // $result = $sql01;
        $result = "1";
        // }


    } else {

        $quantiteInventaire = 0;
    }



    echo $result;
} else if ($operation == 9) {

    $idDesignation = $_POST['idDesignation'];
    $prixUniteStock = @$_POST['prixUniteStock'];
    $prixUnitaire = @$_POST['prixUnitaire'];
    $prixAchat = @$_POST['prixAchat'];
    $qtyReelle = $_POST['qtyReelle'];

    $result = "0";
    $quantiteInventaire = $qtyReelle;

    $sql2 = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation='" . $idDesignation . "'";
    $res2 = mysql_query($sql2);
    $design = mysql_fetch_array($res2);

    $sql20 = "SELECT * FROM `" . $nomtableStock . "` where idDesignation='" . $idDesignation . "'";
    $res20 = mysql_query($sql20);
    $stock = mysql_fetch_array($res20);

    $sqlS0 = "UPDATE `" . $nomtableStock . "` set quantiteStockCourant=0, quantiteStockTemp=0 where idDesignation=" . $idDesignation;

    $resS0 = mysql_query($sqlS0) or die("update quantiteStockCourant impossible =>" . mysql_error());


    $totalArticleStock = $quantiteInventaire;

    if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {

        $sql01 = 'INSERT INTO `' . $nomtableStock . '`(idDesignation,designation,quantiteStockinitial,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, quantiteStockTemp) VALUES(' . $idDesignation . ',"' . mysql_real_escape_string($design['designation']) . '",' . $quantiteInventaire . ',' . $design['prixPublic'] . ',' . $stock['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",' . $totalArticleStock . ',' . $totalArticleStock . ')';

        $res01 = @mysql_query($sql01) or die("insertion stock 2 impossible" . mysql_error());
    } else {

        $sql01 = 'INSERT INTO `' . $nomtableStock . '`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant, quantiteStockTemp) VALUES(' . $idDesignation . ',"' . mysql_real_escape_string($design['designation']) . '",' . $quantiteInventaire . ',"' . mysql_real_escape_string($design['uniteStock']) . '",' . $design['prix'] . ',' . $design['prixuniteStock'] . ',' . $design['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",' . $totalArticleStock . ',' . $totalArticleStock . ')';

        $res01 = @mysql_query($sql01) or die("insertion stock 2 impossible" . mysql_error());


        $sql0 = "UPDATE `" . $nomtableDesignation . "` set prixuniteStock='" . $prixUniteStock . "', prix='" . $prixUnitaire . "', prixachat='" . $prixAchat . "' WHERE idDesignation='" . $idDesignation . "' ";
        $res0 = mysql_query($sql0) or die("update prixuniteStock impossible =>" . mysql_error());
    }

    $sqlS0 = "UPDATE `" . $nomtableStock . "` set quantiteStockTemp=" . $totalArticleStock . " where idDesignation=" . $idDesignation;

    $resS0 = mysql_query($sqlS0) or die("update quantiteStockCourant impossible =>" . mysql_error());

    $sql01s = "SELECT * FROM `" . $nomtableStock . "` where idDesignation='" . $idDesignation . "' ORDER BY idStock DESC LIMIT 1";
    $resS01s = mysql_query($sql01s);
    $stock01s = mysql_fetch_array($resS01s);
    $idLastS = $stock01s['idStock'];

    $sql4 = 'INSERT INTO `' . $nomtableInventaire . '` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
    VALUES(' . $idLastS . ',' . $design['idDesignation'] . ',' . $quantiteInventaire . ',' . $stock['nbreArticleUniteStock'] . ',' . $totalArticleStock . ',"' . $dateString . '",0)';
    $res4 = mysql_query($sql4) or die("insertion stock 2 impossible" . mysql_error());

    $result = 1;

    echo $result;
} else if ($operation == 10) {

    $idPanier = $_POST['idPanier'];

    $sql4 = "UPDATE `" . $nomtablePagnet . "` SET terminerProforma=1, validerProforma=1 where idPagnet=" . $idPanier;

    $res4 = @mysql_query($sql4) or die("mise à jour verouillage  impossible 1--- " . mysql_error());

    if ($res4) {
        echo '1';
    } else {
        echo '2';
    }
}