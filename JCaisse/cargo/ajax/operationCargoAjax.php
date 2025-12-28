<?php

session_start();
if (!$_SESSION['iduser']) {
    header('Location:../../../index.php');
}

require('../../connection.php');

require('../../connectionPDO.php');

require('../../declarationVariables.php');

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

$msg_info = '';

/************ CBM MANAGER*****************/
if (isset($_POST['changeNumContainer'])) {

    $idPagnetContainer = $_POST['idPagnet'];
    $numContainerChanged = $_POST['numContainerChanged'];
    $idContainer = $_POST['idContainer'];

    $sql3 = "UPDATE `" . $nomtablePagnet . "` set numContainer='" . $idContainer . "' where idPagnet=" . $idPagnetContainer;

    $res3 = @mysql_query($sql3) or die("mise à jour verouillage  impossible" . mysql_error());
    // var_dump($res3);

    if ($res3) {
        # code...

        echo '1';
    } else {
        # code...

        echo '0';
    }
}

// else if (isset($_POST['cbmManager'])) {

//     $idClient = htmlspecialchars($_POST['idClient'], ENT_QUOTES);
//     $numEmplacement = htmlspecialchars($_POST['numEmplacement'], ENT_QUOTES);
//     $typeEmplacement = htmlspecialchars($_POST['emplacement'], ENT_QUOTES);
//     $qty_cbm = htmlspecialchars($_POST['qty_cbm'], ENT_QUOTES);
//     $prix_cbm = htmlspecialchars($_POST['prix_cbm'], ENT_QUOTES);
//     $natureBagages_cbm = htmlspecialchars($_POST['nature_cbm'], ENT_QUOTES);
//     $qty_bal = htmlspecialchars($_POST['qty_bal'], ENT_QUOTES);
//     $nbPcsInContainer = htmlspecialchars($_POST['nbPcsInContainer'], ENT_QUOTES);

//     $totalp=$qty_cbm*$prix_cbm+$qty_bal;

//     $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

//     try {
//         // From this point and until the transaction is being committed every change to the database can be reverted
//         $bdd->beginTransaction();   

//         if ($_SESSION['compte']==1) {
//             $compte='2';

//             $req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idCompte,idClient,numContainer,nbPcsInContainer,natureBagages)
//             values (:d,:h,:u,:t,:tp,:ap,:v,:cpt,:c,:nc,:nbP,:ntB)");
//             $req4->execute(array(
//                 'd' => $dateString2,
//                 'h' => $heureString,
//                 'u' => $_SESSION['iduser'],
//                 't' => 0,
//                 'tp' => $totalp,
//                 'ap' => $totalp,
//                 'v' => 1,
//                 'cpt' => $compte,
//                 'c' => $idClient,
//                 'nc' => $numContainer,
//                 'nbP' => $nbPcsInContainer,
//                 'ntB' => $natureBagages_cbm
//             ))  or die(print_r("Insert pagnet 1 ".$req4->errorInfo()));
//             $req4->closeCursor();

//         } else {

//             $req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,numContainer,nbPcsInContainer,natureBagages)
//             values (:d,:h,:u,:t,:tp,:ap,:v,:c,:nc,:nbP,:ntB)");
//             $req4->execute(array(
//                 'd' => $dateString2,
//                 'h' => $heureString,
//                 'u' => $_SESSION['iduser'],
//                 't' => 0,
//                 'tp' => $totalp,
//                 'ap' => $totalp,
//                 'v' => 1,
//                 'c' => $idClient,
//                 'nc' => $numContainer,
//                 'nbP' => $nbPcsInContainer,
//                 'ntB' => $natureBagages_cbm
//             ))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
//             $req4->closeCursor();
//         }
//         $idPagnet = $bdd->lastInsertId();

//         if ($qty_cbm!=0) {
//             # code...

//             $sql="SELECT * FROM `".$nomtableDesignation."` where designation='cbm'";

// 	        $statement = $bdd->prepare($sql);
//             $statement->execute();

//             $design = $statement->fetch(PDO::FETCH_ASSOC); 

//             $preparedStatementCbm = $bdd->prepare(
//                 "insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
//             );

//             $preparedStatementCbm->execute([
//                 'd' => $design['designation'],
//                 'idd' => $design['idDesignation'],
//                 'uv' => $design['uniteStock'],
//                 'pu' => $prix_cbm,
//                 'qty' => $qty_cbm,
//                 'p' => $design['prix']*$qty_cbm,
//                 'idp' => $idPagnet,
//                 'c' => 1
//             ]);
//         }

//         if ($qty_bal!=0) {
//             # code...
//             $sql="SELECT * FROM `".$nomtableDesignation."` where designation='bal'";

// 	        $statement = $bdd->prepare($sql);
//             $statement->execute();

//             $design = $statement->fetch(PDO::FETCH_ASSOC); 

//             $preparedStatementBal = $bdd->prepare(
//                 "insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
//             );

//             $preparedStatementBal->execute([
//                 'd' => $design['designation'],
//                 'idd' => $design['idDesignation'],
//                 'uv' => $design['uniteStock'],
//                 'pu' => $qty_bal,
//                 'qty' => 1,
//                 'p' => $qty_bal,
//                 'idp' => $idPagnet,
//                 'c' => 1
//             ]);
//         }

//         /**** generate barcode****/

//         $x= strlen($idPagnet);

//         if($x==1){

//             $code=$idPagnet.'34522345'.$idPagnet;

//         }

//        else if($x==2){

//             $code=$idPagnet.'342234'.$idPagnet;

//         }

//        else if($x==3){

//             $code=$idPagnet.'3223'.$idPagnet;

//         }

//         else if($x==4){

//             $code=$idPagnet.'22'.$idPagnet;

//         } else {
//             $rand = rand(100,999);
//             $code=$idPagnet.''.$rand;
//         }

//         $sqlU="UPDATE `".$nomtablePagnet."` SET codeBarrePcsInContainer='".$code."' where idPagnet=".$idPagnet;

//         $statementU = $bdd->prepare($sqlU);
//         $statementU->execute();

//         /**** generate barcode****/
//         // Make the changes to the database permanent
//         $bdd->commit();

//         echo '1';
//     }
//     catch ( PDOException $e ) { 
//         // Failed to insert the order into the database so we rollback any changes
//         $bdd->rollback();
//         throw $e;

//         // echo '0';
//     }

// }

else if (isset($_POST['enregistrementManager'])) {


    $idClient = htmlspecialchars($_POST['idClient'], ENT_QUOTES);
    $numEmplacement = htmlspecialchars($_POST['numEmplacement'], ENT_QUOTES);
    $typeEmplacement = htmlspecialchars($_POST['typeEmplacement'], ENT_QUOTES);
    $qty_cbm_fret = htmlspecialchars($_POST['qty_cbm_fret'], ENT_QUOTES);
    $destination = htmlspecialchars($_POST['destination'], ENT_QUOTES);
    // $prix_cbm_fret = htmlspecialchars($_POST['prix_cbm_fret'], ENT_QUOTES);
    $natureBagages = htmlspecialchars($_POST['nature_bagages'], ENT_QUOTES);
    // $qty_bal = htmlspecialchars($_POST['qty_bal'], ENT_QUOTES);
    $nbPcs = htmlspecialchars($_POST['nbPcs'], ENT_QUOTES);

    $idContainer = 0;
    $idAvion = 0;
    $idEntrepot = 0;
    $etat = 0;

    $dateChargement = '';
    $heureChargement = '';

    // $totalp=$qty_cbm_fret*$prix_cbm_fret;


    if ($typeEmplacement == 1) { // pour les containers
        $idContainer = $numEmplacement;
        $etat = 2;
        $dateChargement = $dateString2;
        $heureChargement = $heureString;
    } else if ($typeEmplacement == 2) { // pour les avions
        $idAvion = $numEmplacement;
        $etat = 2;
        $dateChargement = $dateString2;
        $heureChargement = $heureString;
    } else if ($typeEmplacement == 3) { // pour les dêpots
        $idEntrepot = $numEmplacement;
        $etat = 1;
    }


    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();

        $req4 = $bdd->prepare("INSERT INTO `" . $nomtableEnregistrement . "`(`dateEnregistrement`, `heureEnregistrement`, `nbPieces`, `etat`, `quantite_cbm_fret`, `destination`, `natureBagage`, `idClient`, `idEntrepot`, `idUser`)
            values (:dE,:hE,:nbP,:etat,:qty,:dest,:nat,:c,:idE,:idU)");
        $req4->execute(array(
            'dE' => $dateString2,
            'hE' => $heureString,
            'nbP' => $nbPcs,
            'etat' => $etat,
            'qty' => $qty_cbm_fret,
            'dest' => $destination,
            'nat' => $natureBagages,
            'c' => $idClient,
            'idE' => $idEntrepot,
            'idU' => $_SESSION['iduser']
        ))  or die(print_r("Insert enregistrement 1 " . $req4->errorInfo()));
        $req4->closeCursor();

        // $req4 = $bdd->prepare("INSERT INTO `".$nomtableEnregistrement."`(`dateEnregistrement`, `heureEnregistrement`, `dateChargement`, `heureChargement`, `etat`, `nbPieces`, `quantite_cbm_fret`, `prix_cbm_fret`, `natureBagage`, `idClient`, `idEntrepot`, `idContainer`, `idAvion`)
        //     values (:dE,:hE,:dC,:hC,:etat,:nbP,:qty,:prix,:nat,:c,:idE,:idC,:idA)");
        // $req4->execute(array(
        //         'dE' => $dateString2,
        //         'hE' => $heureString,
        //         'dC' => $dateChargement,
        //         'hC' => $heureChargement,
        //         'etat' => $etat,
        //         'nbP' => $nbPcs,
        //         'qty' => $qty_cbm_fret,
        //         'prix' => $prix_cbm_fret,
        //         'nat' => $natureBagages,
        //         'c' => $idClient,
        //         'idE' => $idEntrepot,
        //         'idC' => $idContainer,
        //         'idA' => $idAvion
        //     ))  or die(print_r("Insert enregistrement 1 ".$req4->errorInfo()));
        //     $req4->closeCursor();

        $idEnregistrement = $bdd->lastInsertId();


        /**** generate barcode****/

        $x = strlen($idEnregistrement);

        if ($x == 1) {

            $code = $idEnregistrement . '34522345' . $idEnregistrement;
        } else if ($x == 2) {

            $code = $idEnregistrement . '342234' . $idEnregistrement;
        } else if ($x == 3) {

            $code = $idEnregistrement . '3223' . $idEnregistrement;
        } else if ($x == 4) {

            $code = $idEnregistrement . '22' . $idEnregistrement;
        } else {
            $rand = rand(100, 999);
            $code = $idEnregistrement . '' . $rand;
        }

        if ($typeEmplacement == 1) {

            $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET dateChargement='" . $dateString2 . "', heureChargement='" . $heureChargement . "', idContainer=" . $idContainer . " where idEnregistrement=" . $idEnregistrement;

            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(print_r($statementU->errorInfo(), true));

            // $req5 = $bdd->prepare("INSERT INTO `".$nomtableChargement."`(`dateChargement`, `heureChargement`, `nbPiecesCharger`, `quantite_cbm_fret`, `prix_cbm_fret`, `natureBagage`, `codeBarre`, `idClient`, `idEnregistrement`, `idEntrepot`, `idContainer`, `idAvion`)
            //     values (:dC,:hC,:nbP,:qty,:prix,:nat,:cb,:c,:idEn,:idE,:idC,:idA)");
            // $req5->execute(array(
            //         'dC' => $dateChargement,
            //         'hC' => $heureChargement,
            //         'nbP' => $nbPcs,
            //         'qty' => $qty_cbm_fret,
            //         'prix' => $prix_cbm_fret,
            //         'nat' => $natureBagages,
            //         'cb' => $code,
            //         'c' => $idClient,
            //         'idEn' => $idEnregistrement,
            //         'idE' => $idEntrepot,
            //         'idC' => $idContainer,
            //         'idA' => $idAvion
            //     ))  or die(print_r("Insert chargement 1 ".$req5->errorInfo()));
            //     $req5->closeCursor();

            // $idChargement = $bdd->lastInsertId();
        } else if ($typeEmplacement == 2) {

            $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET dateChargement='" . $dateString2 . "', heureChargement='" . $heureChargement . "', idAvion=" . $idAvion . " where idEnregistrement=" . $idEnregistrement;

            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(print_r($statementU->errorInfo(), true));
        }


        $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET codeBarre='" . $code . "' where idEnregistrement=" . $idEnregistrement;

        $statementU = $bdd->prepare($sqlU);
        $statementU->execute();


        $sql = "SELECT * FROM `" . $nomtableEnregistrement . "` WHERE idEnregistrement=" . $idEnregistrement;
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $enregistrement = $statement->fetch(PDO::FETCH_ASSOC);

        /**** generate barcode****/
        // Make the changes to the database permanent
        $bdd->commit();

        echo json_encode($enregistrement);
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        // echo '0';
    }
} else if (isset($_POST['emplacementChange'])) {

    $emplacements = [];
    $emplacement = htmlspecialchars($_POST['emplacement']);


    $query = htmlspecialchars(trim($_POST['query']));

    $sql3 = "SELECT * FROM `" . $nomtableContainer . "` where etat=0 and (numContainer LIKE '%$query%') and retirer=0 ORDER BY idContainer";

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());


    $sql4 = "SELECT * FROM `" . $nomtableAvion . "` where etat=0 and (numVol LIKE '%$query%') and retirer=0 ORDER BY idAvion";

    $res4 = mysql_query($sql4) or die("persoonel requête 4" . mysql_error());


    $sql5 = "SELECT * FROM `" . $nomtableEntrepot . "` where (nomEntrepot LIKE '%$query%') and retirer=0 ORDER BY idEntrepot";

    $res5 = mysql_query($sql5) or die("persoonel requête 5" . mysql_error());

    if ($emplacement == 1) {


        while ($container = mysql_fetch_assoc($res3)) {

            $emplacements[] = $container['idContainer'] . " . " . $container['numContainer'];
            // $emplacements[] = $container['idContainer']." . ".$container['numContainer']." - Container";

        }
    } else if ($emplacement == 2) {

        while ($avion = mysql_fetch_assoc($res4)) {

            $emplacements[] = $avion['idAvion'] . " . " . $avion['numVol'];
            // $emplacements[] = $entrepot['idEntrepot']." . ".$entrepot['nomEntrepot']." - Entrepot";

        }
    } else if ($emplacement == 3) {

        while ($entrepot = mysql_fetch_assoc($res5)) {

            $emplacements[] = $entrepot['idEntrepot'] . " . " . $entrepot['nomEntrepot'];
            // $emplacements[] = $entrepot['idEntrepot']." . ".$entrepot['nomEntrepot']." - Entrepot";

        }
    }


    echo json_encode($emplacements);
} else if (isset($_POST['emplCharger'])) {

    $emplacements = [];
    // $emplacement=htmlspecialchars($_POST['emplacement']); 


    $query = htmlspecialchars(trim($_POST['query']));

    $sql3 = "SELECT * FROM `" . $nomtableContainer . "` where etat=0 and (numContainer LIKE '%$query%' or numBooking LIKE '%$query%') ORDER BY idContainer";

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());


    $sql4 = "SELECT * FROM `" . $nomtableAvion . "` where etat=0 and (numVol LIKE '%$query%' or numBooking LIKE '%$query%') ORDER BY idAvion";

    $res4 = mysql_query($sql4) or die("persoonel requête 3" . mysql_error());


    while ($container = mysql_fetch_assoc($res3)) {

        // $emplacements[] = $container['idContainer']." . ".$container['numContainer'];
        $emplacements[] = $container['idContainer'] . " . " . $container['numContainer'] . " --- Container";
    }

    while ($avion = mysql_fetch_assoc($res4)) {

        // $emplacements[] = $avion['idAvion']." . ".$avion['numVol'];
        $emplacements[] = $avion['idAvion'] . " . " . $avion['numVol'] . " --- Avion";
    }



    echo json_encode($emplacements);
} else if (isset($_POST['getContainer'])) {

    $idContainer = htmlspecialchars(trim($_POST['idContainer']));

    $sql3 = "SELECT * FROM `" . $nomtableContainer . "` where idContainer=" . $idContainer;

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    $container = mysql_fetch_assoc($res3);

    echo json_encode($container);
} else if (isset($_POST['confirmEditContainer'])) {

    $idContainer = htmlspecialchars(trim($_POST['idContainer']));
    $numContainer = htmlspecialchars(trim($_POST['numContainer']));
    $numBooking = htmlspecialchars(trim($_POST['numBooking']));
    $dateDepart = htmlspecialchars(trim($_POST['dateDepart']));
    $dateArrivee = htmlspecialchars(trim($_POST['dateArrivee']));

    $sql3 = "UPDATE `" . $nomtableContainer . "` SET numContainer='" . $numContainer . "', numBooking='" . $numBooking . "', dateDepart='" . $dateDepart . "', dateArrivee='" . $dateArrivee . "' where idContainer=" . $idContainer;

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['confirmDeleteContainer'])) {

    $idContainer = htmlspecialchars(trim($_POST['idContainer']));

    $sql3 = "UPDATE `" . $nomtableContainer . "` SET retirer=1 where idContainer=" . $idContainer;

    $res3 = mysql_query($sql3) or die("retirer container " . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['chargementBagages'])) {

    $idEnreg = htmlspecialchars(trim($_POST['idEnreg']));
    $idEmpl = htmlspecialchars(trim($_POST['idEmpl']));
    $numEmpl = htmlspecialchars(trim($_POST['numEmpl']));
    $typeEmpl = htmlspecialchars(trim($_POST['typeEmpl']));
    $prixChargement = htmlspecialchars(trim($_POST['prixChargement']));
    // $nbPcsCharger=htmlspecialchars(trim($_POST['nbPcsCharger']));    
    // $pcsRestant=htmlspecialchars(trim($_POST['pcsRestant']));    

    $dateChargement = $dateString2;
    $heureChargement = $heureString;


    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();

        $sql = "SELECT * FROM `" . $nomtableEnregistrement . "` WHERE idEnregistrement=" . $idEnreg;
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $enregistrement = $statement->fetch(PDO::FETCH_ASSOC);

        if (strtolower($typeEmpl) == 'container') {

            $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET etat=2, dateChargement='" . $dateString2 . "', heureChargement='" . $heureChargement . "', idContainer=" . $idEmpl . ", prix_cbm_fret=" . $prixChargement . " where idEnregistrement=" . $idEnreg;

            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(print_r($statementU->errorInfo(), true));


            // $req5 = $bdd->prepare("INSERT INTO `".$nomtableChargement."`(`dateChargement`, `heureChargement`, `nbPiecesCharger`, `quantite_cbm_fret`, `prix_cbm_fret`, `natureBagage`, `codeBarre`, `idClient`, `idEnregistrement`, `idEntrepot`, `idContainer`)
            //     values (:dC,:hC,:nbP,:qty,:prix,:nat,:cb,:c,:idEn,:idE,:idC)");
            // $req5->execute(array(
            //         'dC' => $dateChargement,
            //         'hC' => $heureChargement,
            //         'nbP' => $nbPcsCharger,
            //         'qty' => $enregistrement['quantite_cbm_fret'],
            //         'prix' => $enregistrement['prix_cbm_fret'],
            //         'nat' => $enregistrement['natureBagage'],
            //         'cb' => $enregistrement['codeBarre'],
            //         'c' => $enregistrement['idClient'],
            //         'idEn' => $idEnreg,
            //         'idE' => $enregistrement['idEntrepot'],
            //         'idC' => $idEmpl
            //     ))  or die(print_r("Insert chargement 1 ".$req5->errorInfo()));
            //     $req5->closeCursor();

        } else if (strtolower($typeEmpl) == 'avion') {

            $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET etat=2, dateChargement='" . $dateString2 . "', heureChargement='" . $heureChargement . "', idAvion=" . $idEmpl . ", prix_cbm_fret=" . $prixChargement . " where idEnregistrement=" . $idEnreg;

            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(print_r($statementU->errorInfo(), true));

            // $req5 = $bdd->prepare("INSERT INTO `".$nomtableChargement."`(`dateChargement`, `heureChargement`, `nbPiecesCharger`, `quantite_cbm_fret`, `prix_cbm_fret`, `natureBagage`, `codeBarre`, `idClient`, `idEnregistrement`, `idEntrepot`, `idAvion`)
            //     values (:dC,:hC,:nbP,:qty,:prix,:nat,:cb,:c,:idEn,:idE,:idA)");
            // $req5->execute(array(
            //         'dC' => $dateChargement,
            //         'hC' => $heureChargement,
            //         'nbP' => $nbPcsCharger,
            //         'qty' => $enregistrement['quantite_cbm_fret'],
            //         'prix' => $enregistrement['prix_cbm_fret'],
            //         'nat' => $enregistrement['natureBagage'],
            //         'cb' => $enregistrement['codeBarre'],
            //         'c' => $enregistrement['idClient'],
            //         'idEn' => $idEnreg,
            //         'idE' => $enregistrement['idEntrepot'],
            //         'idA' => $idEmpl
            //     ))  or die(print_r("Insert chargement 1 ".$req5->errorInfo()));
            //     $req5->closeCursor();

        } else {
        }

        // if ($pcsRestant == $nbPcsCharger) {

        //     $sql3="UPDATE `".$nomtableEnregistrement."` SET etat=2 where idEnregistrement=".$idEnreg;

        //     $res3 = mysql_query($sql3) or die ("change etat enregistrement ".mysql_error());
        // } else {

        //     $sql3="UPDATE `".$nomtableEnregistrement."` SET etat=3 where idEnregistrement=".$idEnreg;

        //     $res3 = mysql_query($sql3) or die ("change etat enregistrement ".mysql_error());
        // }

        /**** generate barcode****/
        // Make the changes to the database permanent
        $bdd->commit();
        echo '1';
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        // echo '0';
    }
} else if (isset($_POST['getEnregistrement'])) {

    $idEnregistrement = htmlspecialchars(trim($_POST['idEnreg']));
    // var_dump($idEnregistrement);

    $sql = "SELECT * FROM `" . $nomtableEnregistrement . "` e, `" . $nomtableClient . "` c WHERE e.idClient=c.idClient and idEnregistrement=" . $idEnregistrement;
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $enregistrement = $statement->fetch(PDO::FETCH_ASSOC);
    // var_dump($enregistrement);
    // die();
    echo json_encode($enregistrement);
} else if (isset($_POST['editEnregistrement'])) {

    $idEnregistrement = htmlspecialchars(trim($_POST['idEnreg']));
    $nb_cbm_fret = htmlspecialchars(trim($_POST['nb_cbm_fret']));
    $nb_pieces = htmlspecialchars(trim($_POST['nb_pieces']));
    $nat_bagages = htmlspecialchars(trim($_POST['nat_bagages']));

    $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET quantite_cbm_fret=" . $nb_cbm_fret . ", nbPieces='" . $nb_pieces . "', natureBagage='" . $nat_bagages . "' where idEnregistrement=" . $idEnregistrement;

    $statementU = $bdd->prepare($sqlU);
    $statementU->execute() or die(print_r($statementU->errorInfo(), true));

    if ($statementU) {
        echo '1';
        exit(1);
    } else {
        echo '0';
        exit(0);
    }
} else if (isset($_POST['delEnregistrement'])) {

    $idEnregistrement = htmlspecialchars(trim($_POST['idEnreg']));

    $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET retirer = 1 where idEnregistrement=" . $idEnregistrement;

    $statementU = $bdd->prepare($sqlU);
    $statementU->execute() or die(print_r($statementU->errorInfo(), true));

    if ($statementU) {
        echo '1';
    } else {
        echo '0';
    }
}
//else if(isset($_POST['fermerPorteur'])){

//     $id=htmlspecialchars(trim($_POST['id']));     
//     $type=htmlspecialchars(trim($_POST['type']));  

//     if ($type == 1 || $type == '1') {

//         $sqlU="UPDATE `".$nomtableContainer."` SET etat = 1 where idContainer=".$id;

//     } else if ($type == 2 || $type == '2') {
//         $sqlU="UPDATE `".$nomtableAvion."` SET etat = 1 where idAvion=".$id;

//     }

//     $statementU = $bdd->prepare($sqlU);
//     $statementU->execute() or die(print_r($statementU->errorInfo(),true)); 

//     if ($statementU) {
//         echo '1';
//     } else {
//         echo '0';
//     }
// }
else if (isset($_POST['fermerPorteur'])) {

    $id = htmlspecialchars(trim($_POST['id']));
    $type = htmlspecialchars(trim($_POST['type']));

    if ($type == 1 || $type == '1') {

        $sqlU = "UPDATE `" . $nomtableContainer . "` SET etat = 1 where idContainer=" . $id;
    } else if ($type == 2 || $type == '2') {
        $sqlU = "UPDATE `" . $nomtableAvion . "` SET etat = 1 where idAvion=" . $id;
    }

    $statementU = $bdd->prepare($sqlU);
    $statementU->execute() or die(print_r($statementU->errorInfo(), true));

    if ($statementU) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['ouvrirPorteur'])) {

    $id = htmlspecialchars(trim($_POST['id']));
    $type = htmlspecialchars(trim($_POST['type']));

    if ($type == 1 || $type == '1') {

        $sqlU = "UPDATE `" . $nomtableContainer . "` SET etat = 0 where idContainer=" . $id;
    } else if ($type == 2 || $type == '2') {
        $sqlU = "UPDATE `" . $nomtableAvion . "` SET etat = 0 where idAvion=" . $id;
    }

    $statementU = $bdd->prepare($sqlU);
    $statementU->execute() or die(print_r($statementU->errorInfo(), true));

    if ($statementU) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['arriveePorteur'])) {

    $id = htmlspecialchars(trim($_POST['id']));
    $type = htmlspecialchars(trim($_POST['type']));
    $idContainer = 0;
    $idAvion = 0;


    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();

        if ($type == 1 || $type == '1') {

            $sqlU = "UPDATE `" . $nomtableContainer . "` SET etat = 2 where idContainer=" . $id;
        } else if ($type == 2 || $type == '2') {
            $sqlU = "UPDATE `" . $nomtableAvion . "` SET etat = 2 where idAvion=" . $id;
        }

        $statementU = $bdd->prepare($sqlU);
        $statementU->execute() or die(print_r($statementU->errorInfo(), true));

        // $sql="SELECT * FROM `".$nomtableEnregistrement."` WHERE retirer=0 and idContainer=".$id;
        // $statement = $bdd->prepare($sql);
        // $statement->execute();
        // $enregistrements = $statement->fetchAll(PDO::FETCH_ASSOC); 

        // foreach ($enregistrements as $key) {

        //     $totalp=$key['quantite_cbm_fret']*$key['prix_cbm_fret'];

        //     $req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,numContainer,idAvion,nbPcsInContainer,codeBarrePcsInContainer,natureBagages)
        //     values (:d,:h,:u,:t,:tp,:ap,:v,:c,:nc,:idA,:nbP,:cb,:ntB)");
        //     $req4->execute(array(
        //         'd' => $dateString2,
        //         'h' => $heureString,
        //         'u' => $_SESSION['iduser'],
        //         't' => 0,
        //         'tp' => $totalp,
        //         'ap' => $totalp,
        //         'v' => 1,
        //         'c' => $key['idClient'],
        //         'nc' => $idContainer,
        //         'idA' => $idAvion,
        //         'nbP' => $key['nbPieces'],
        //         'cb' => $key['codeBarre'],
        //         'ntB' => $key['natureBagage']
        //     ))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
        //     $req4->closeCursor();

        //     $idPagnet = $bdd->lastInsertId();

        //     $preparedStatement = $bdd->prepare(
        //         "insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
        //     );

        //     $preparedStatement->execute([
        //         'd' => $design['designation'],
        //         'idd' => $design['idDesignation'],
        //         'uv' => $design['uniteStock'],
        //         'pu' => $key['quantite_cbm_fret'],
        //         'qty' => $key['prix_cbm_fret'],
        //         'p' => $totalp,
        //         'idp' => $idPagnet,
        //         'c' => $design['classe']
        //     ]);
        // }

        /**** generate barcode****/
        // Make the changes to the database permanent
        $bdd->commit();
        echo '1';
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        // echo '0';
    }
} else if (isset($_POST['searchByBarcode'])) {

    $codeBarre = htmlspecialchars(trim($_POST['codeBarre']));
    $type = [];
    $allData = [];

    $sql = "SELECT * FROM `" . $nomtableEnregistrement . "` WHERE retirer=0 and codeBarre=" . $codeBarre;
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $enregistrement = $statement->fetch(PDO::FETCH_ASSOC);

    $allData[] = $enregistrement;

    $sql = "SELECT * FROM `" . $nomtableClient . "` WHERE idClient=" . $enregistrement['idClient'];
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $client = $statement->fetch(PDO::FETCH_ASSOC);

    $allData[] = $client;

    if ($enregistrement['idContainer'] != 0) {

        $sql = "SELECT * FROM `" . $nomtableContainer . "` WHERE idContainer=" . $enregistrement['idContainer'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $container = $statement->fetch(PDO::FETCH_ASSOC);

        $type[] = 1; // pour container

        $allData[] = $container;
    }

    if ($enregistrement['idAvion'] != 0) {

        $sql = "SELECT * FROM `" . $nomtableAvion . "` WHERE idAvion=" . $enregistrement['idAvion'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $avion = $statement->fetch(PDO::FETCH_ASSOC);

        $type[] = 2; // pour avion

        $allData[] = $avion;
    }

    $allData[] = $type;

    echo json_encode($allData);
} else if (isset($_POST['confirmEditvol'])) {

    $idAvion = htmlspecialchars(trim($_POST['idAvion']));
    $numVol = htmlspecialchars(trim($_POST['numVol']));
    $numBooking = htmlspecialchars(trim($_POST['numBooking']));
    $nombrePieces = htmlspecialchars(trim($_POST['nombrePieces']));
    $dateDepart = htmlspecialchars(trim($_POST['dateDepart']));
    $dateArrivee = htmlspecialchars(trim($_POST['dateArrivee']));

    $sql3 = "UPDATE `" . $nomtableAvion . "` SET numVol='" . $numVol . "', numBooking='" . $numBooking . "', nombrePieces='" . $nombrePieces . "', dateDepart='" . $dateDepart . "', dateArrivee='" . $dateArrivee . "' where idAvion=" . $idAvion;
    //   var_dump($sql3);
    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['getAvion'])) {

    $idAvion = htmlspecialchars(trim($_POST['idAvion']));

    $sql3 = "SELECT * FROM `" . $nomtableAvion . "` where idAvion=" . $idAvion;

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    $avion = mysql_fetch_assoc($res3);

    echo json_encode($avion);
} else if (isset($_POST['confirmDeleteVol'])) {

    $idAvion = htmlspecialchars(trim($_POST['idAvion']));

    $sql3 = "UPDATE `" . $nomtableAvion . "` SET retirer=1 where idAvion=" . $idAvion;

    $res3 = mysql_query($sql3) or die("retirer avion " . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['autocompleteNatBagage'])) {

    $query = htmlspecialchars(trim($_POST['query']));
    $natures = [];

    $sql3 = "SELECT * FROM `" . $nomtableNature . "` where retirer=0 and (libelle LIKE '%$query%')";

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    // $res = mysql_fetch_assoc($res3);

    while ($res = mysql_fetch_assoc($res3)) {

        $natures[] = $res['idNature'] . " . " . $res['libelle'];
        // $emplacements[] = $container['idContainer']." . ".$container['numContainer']." - Container";

    }

    echo json_encode($natures);
} else if (isset($_POST['emplacementDecharge'])) {

    $emplacements = [];

    $query = htmlspecialchars(trim($_POST['query']));


    $sql5 = "SELECT * FROM `" . $nomtableEntrepot . "` where type=2 and (nomEntrepot LIKE '%$query%') and retirer=0 ORDER BY idEntrepot";

    $res5 = mysql_query($sql5) or die("persoonel requête 5" . mysql_error());


    while ($entrepot = mysql_fetch_assoc($res5)) {

        $emplacements[] = $entrepot['idEntrepot'] . " . " . $entrepot['nomEntrepot'] . " - " . $entrepot['adresseEntrepot'];
        // $emplacements[] = $entrepot['idEntrepot']." . ".$entrepot['nomEntrepot']." - Entrepot";

    }

    echo json_encode($emplacements);
} else if (isset($_POST['recuEnregistrement'])) {

    $idEnreg = htmlspecialchars(trim($_POST['idEnreg']));
    $idEntrepot = htmlspecialchars(trim($_POST['idEntrepot']));
    $idContainer = 0;
    $idAvion = 0;

    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();

        $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET idEntrepotArrive=" . $idEntrepot . ", recu=1 where idEnregistrement=" . $idEnreg;

        $statementU = $bdd->prepare($sqlU);
        $statementU->execute() or die(print_r($statementU->errorInfo(), true));

        $sql = "SELECT * FROM `" . $nomtableEnregistrement . "` WHERE idEnregistrement=" . $idEnreg;
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $key = $statement->fetch(PDO::FETCH_ASSOC);


        if ($key['idContainer'] != 0) {

            $idContainer = $key['idContainer'];

            $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` WHERE typePorteur=1"; // pour cbm classe 15

        } else if ($key['idAvion'] != 0) {
            $idAvion = $key['idAvion'];

            $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` WHERE typePorteur=2"; // pour fret classe 16
        }


        $statementD = $bdd->prepare($sqlD);
        $statementD->execute();
        $design = $statementD->fetch(PDO::FETCH_ASSOC);

        // foreach ($enregistrements as $key) {

        $totalp = $key['quantite_cbm_fret'] * $key['prix_cbm_fret'];

        $req4 = $bdd->prepare("INSERT INTO `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,numContainer,idAvion,nbPcsInContainer,codeBarrePcsInContainer,idEnregistrement,natureBagages)
            values (:d,:h,:u,:t,:tp,:ap,:v,:c,:nc,:idA,:nbP,:cb,:idE,:ntB)");
        $req4->execute(array(
            'd' => $dateString2,
            'h' => $heureString,
            'u' => $_SESSION['iduser'],
            't' => 0,
            'tp' => $totalp,
            'ap' => $totalp,
            'v' => 1,
            'c' => $key['idClient'],
            'nc' => $idContainer,
            'idA' => $idAvion,
            'nbP' => $key['nbPieces'],
            'cb' => $key['codeBarre'],
            'idE' => $key['idEnregistrement'],
            'ntB' => $key['natureBagage']
        ))  or die(print_r("Insert pagnet 2 " . $req4->errorInfo()));
        $req4->closeCursor();

        $idPagnet = $bdd->lastInsertId();

        $preparedStatement = $bdd->prepare(
            "insert into `" . $nomtableLigne . "` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
        );

        $preparedStatement->execute([
            'd' => $design['designation'],
            'idd' => $design['idDesignation'],
            'uv' => $design['uniteStock'],
            'qty' => $key['quantite_cbm_fret'],
            'pu' => $key['prix_cbm_fret'],
            'p' => $totalp,
            'idp' => $idPagnet,
            'c' => $design['classe']
        ]);
        // }

        $bdd->commit();
        echo '1';
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        echo '0';
    }
} else if (isset($_POST['getImgEnreg'])) {

    $idEnreg = htmlspecialchars(trim($_POST['idEnreg']));

    $sqlD = "SELECT * FROM `" . $nomtableEnregistrement . "` WHERE idEnregistrement=" . $idEnreg;
    $statementD = $bdd->prepare($sqlD);
    $statementD->execute();
    $enregistrement = $statementD->fetch(PDO::FETCH_ASSOC);

    echo json_encode($enregistrement);
} else if (isset($_POST['client_cbm_fret'])) {

    $clients = [];

    $query = htmlspecialchars(trim($_POST['query']));



    $sql3 = "SELECT * FROM `" . $nomtableClient . "` where (prenom LIKE '%$query%' or nom LIKE '%$query%' or adresse LIKE '%$query%') and archiver=0 and activer=1 and avoir=0 ORDER BY idClient ASC";

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());



    while ($client = mysql_fetch_assoc($res3)) {

        $clients[] = $client['idClient'] . " . " . $client['prenom'] . " " . $client['nom'] . " " . $client['adresse'];
    }


    echo json_encode($clients);
} else if (isset($_POST['porteurVersement'])) {

    $emplacements = [];
    // $emplacement=htmlspecialchars($_POST['emplacement']); 

    $query = htmlspecialchars(trim($_POST['query']));

    $sql3 = "SELECT * FROM `" . $nomtableContainer . "` where etat=2 and (numContainer LIKE '%$query%' or numBooking LIKE '%$query%') ORDER BY idContainer";

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    $sql4 = "SELECT * FROM `" . $nomtableAvion . "` where etat=2 and (numVol LIKE '%$query%' or numBooking LIKE '%$query%') ORDER BY idAvion";

    $res4 = mysql_query($sql4) or die("persoonel requête 3" . mysql_error());

    while ($container = mysql_fetch_assoc($res3)) {

        // $emplacements[] = $container['idContainer']." . ".$container['numContainer'];
        $emplacements[] = $container['idContainer'] . " . " . $container['numContainer'] . " --- Container";
    }

    while ($avion = mysql_fetch_assoc($res4)) {

        // $emplacements[] = $avion['idAvion']." . ".$avion['numVol'];
        $emplacements[] = $avion['idAvion'] . " . " . $avion['numVol'] . " --- Avion";
    }



    echo json_encode($emplacements);
} else if (isset($_POST['getEntrepot'])) {

    $idEntrepot = htmlspecialchars(trim($_POST['idEntrepot']));

    $sql3 = "SELECT * FROM `" . $nomtableEntrepot . "` where idEntrepot=" . $idEntrepot;

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    $entrepot = mysql_fetch_assoc($res3);

    echo json_encode($entrepot);
} else if (isset($_POST['confirmDeleteEntrepot'])) {

    $idEntrepot = htmlspecialchars(trim($_POST['idEntrepot']));

    $sql3 = "UPDATE `" . $nomtableEntrepot . "` SET retirer=1 where idEntrepot=" . $idEntrepot;

    $res3 = mysql_query($sql3) or die("retirer entrepot " . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['getNature'])) {

    $idNature = htmlspecialchars(trim($_POST['idNature']));

    $sql3 = "SELECT * FROM `" . $nomtableNature . "` where idNature=" . $idNature;

    $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    $nature = mysql_fetch_assoc($res3);

    echo json_encode($nature);
} else if (isset($_POST['confirmDeleteNature'])) {

    $idNature = htmlspecialchars(trim($_POST['idNature']));

    $sql3 = "UPDATE `" . $nomtableNature . "` SET retirer=1 where idNature=" . $idNature;

    $res3 = mysql_query($sql3) or die("retirer nature " . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['getImgAvion'])) {

    $idAvion = htmlspecialchars(trim($_POST['idAvion']));

    $sqlD = "SELECT * FROM `" . $nomtableAvion . "` WHERE idAvion=" . $idAvion;
    $statementD = $bdd->prepare($sqlD);
    $statementD->execute();
    $avion = $statementD->fetch(PDO::FETCH_ASSOC);

    echo json_encode($avion);
} else if (isset($_POST['getImgContainer'])) {

    $idContainer = htmlspecialchars(trim($_POST['idContainer']));

    $sqlD = "SELECT * FROM `" . $nomtableContainer . "` WHERE idContainer=" . $idContainer;
    $statementD = $bdd->prepare($sqlD);
    $statementD->execute();
    $container = $statementD->fetch(PDO::FETCH_ASSOC);

    echo json_encode($container);
} else if (isset($_POST['setchargementPrix'])) {

    $idEnreg = htmlspecialchars(trim($_POST['idEnreg']));
    $prixChargement = htmlspecialchars(trim($_POST['prixChargement']));


    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();


        $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET prix_cbm_fret=" . $prixChargement . " where idEnregistrement=" . $idEnreg;

        $statementU = $bdd->prepare($sqlU);
        $statementU->execute() or die(print_r($statementU->errorInfo(), true));


        /**** generate barcode****/
        // Make the changes to the database permanent
        $bdd->commit();
        echo '1';
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        // echo '0';
    }
} else if (isset($_POST['annulerChargement'])) {

    $idEnreg = htmlspecialchars(trim($_POST['idEnreg']));

    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();

        $sqlU = "UPDATE `" . $nomtableEnregistrement . "` SET etat=1, idContainer=0, idAvion=0 where idEnregistrement=" . $idEnreg;

        $statementU = $bdd->prepare($sqlU);
        $statementU->execute() or die(print_r($statementU->errorInfo(), true));

        $bdd->commit();
        echo '1';
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        echo '0';
    }
} else if (isset($_POST['setRemise'])) {

    $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));
    $remise = htmlspecialchars(trim($_POST['remise']));

    $sql3 = "UPDATE `" . $nomtablePagnet . "` SET remise=" . $remise . ", apayerPagnet=totalp-" . $remise . " where idPagnet=" . $idPagnet;

    $res3 = mysql_query($sql3) or die("remise panier " . mysql_error());

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
} else if (isset($_POST['setQty'])) {

    $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));
    $qty = htmlspecialchars(trim($_POST['qty']));

    $sql3 = "UPDATE `" . $nomtableLigne . "` SET quantite=" . $qty . ", prixtotal=prixunitevente*" . $qty . " where idPagnet=" . $idPagnet;

    $res3 = mysql_query($sql3) or die("quantite ligne " . mysql_error());


    $sqlD = "SELECT * FROM `" . $nomtableLigne . "` WHERE idPagnet=" . $idPagnet;
    $statementD = $bdd->prepare($sqlD);
    $statementD->execute();
    $ligne = $statementD->fetch(PDO::FETCH_ASSOC);

    $newPrice = $ligne['quantite'] * $ligne['prixunitevente'];

    $sql3 = "UPDATE `" . $nomtablePagnet . "` SET apayerPagnet=" . $newPrice . "-remise, totalp=" . $newPrice . ", restourne=-" . $newPrice . "  where idPagnet=" . $idPagnet;

    $res3 = mysql_query($sql3) or die("montant panier " . mysql_error());

    $sqlD = "SELECT * FROM `" . $nomtablePagnet . "` WHERE idPagnet=" . $idPagnet;
    $statementD = $bdd->prepare($sqlD);
    $statementD->execute();
    $panier = $statementD->fetch(PDO::FETCH_ASSOC);

    if ($panier['idEnregistrement'] != 0 && $panier['idEnregistrement']) {

        $sql3 = "UPDATE `" . $nomtableEnregistrement . "` SET quantite_cbm_fret=" . $qty . " where idEnregistrement=" . $panier['idEnregistrement'];

        $res3 = mysql_query($sql3) or die("quantite enregistrement " . mysql_error());
    }

    if ($res3) {
        echo '1';
    } else {
        echo '0';
    }
}else if (isset($_POST['listeDepense'])) {
    $depenses = [];

    $query = htmlspecialchars(trim($_POST['query']));

    $sql = "SELECT idDesignation,designation FROM `" . $nomtableDesignation . "` where classe=2 and  designation LIKE '%$query%'";

    $statement = $bdd->prepare($sql);
    $statement->execute();

    $res = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $key) {
        $depenses[] = $key['idDesignation'] . " . " . $key['designation'];
    }

    echo json_encode($depenses);
} else if (isset($_POST['validerDepense'])) {

    $idContainer = 0;
    $idAvion = 0;
    $idPorteur = htmlspecialchars(trim($_POST['idPorteur']));
    $porteur = htmlspecialchars(trim($_POST['porteur']));
    $libelle_depense = htmlspecialchars(trim($_POST['libelle_depense']));
    $description = htmlspecialchars(trim($_POST['description']));
    $montant_depense = htmlspecialchars(trim($_POST['montant_depense']));
    $compte_depense = @htmlspecialchars(trim($_POST['compte_depense']));
    $idDepense = @htmlspecialchars(trim($_POST['idDepense']));
    $designation = $libelle_depense;

    if (!$description) {
        $description = " Compte débité pour dépenses";
    } else {
        $designation = $libelle_depense . "-" . $description;
    }

    $sqlD = "SELECT * FROM `" . $nomtableDesignation . "` WHERE idDesignation=" . $idDepense;
    $statementD = $bdd->prepare($sqlD);
    $statementD->execute();
    $design = $statementD->fetch(PDO::FETCH_ASSOC);

    if ($porteur == 1) {
        $idContainer = $idPorteur;
    } else if ($porteur == 2) {
        $idAvion = $idPorteur;
    }


    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction();

        $req4 = $bdd->prepare("INSERT INTO `" . $nomtablePagnet . "` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,numContainer,idAvion,idCompte)
        values (:d,:h,:u,:t,:tp,:ap,:v,:nc,:idA,:idC)");
        $req4->execute(array(
            'd' => $dateString2,
            'h' => $heureString,
            'u' => $_SESSION['iduser'],
            't' => 0,
            'tp' => $montant_depense,
            'ap' => $montant_depense,
            'v' => 1,
            'nc' => $idContainer,
            'idA' => $idAvion,
            'idC' => $compte_depense
        ))  or die(print_r("Insert pagnet 2 " . $req4->errorInfo()));
        $req4->closeCursor();

        $idPagnet = $bdd->lastInsertId();

        $preparedStatement = $bdd->prepare(
            "insert into `" . $nomtableLigne . "` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe) values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
        );

        $preparedStatement->execute([
            'd' => $designation,
            'idd' => $design['idDesignation'],
            'uv' => $design['uniteStock'],
            'qty' => 1,
            'pu' => $montant_depense,
            'p' => $montant_depense,
            'idp' => $idPagnet,
            'c' => $design['classe']
        ]);

        if ($_SESSION['compte'] == 1) {

            $sqlU = $bdd->prepare("INSERT INTO `" . $nomtableComptemouvement . "` (idCompte, operation, montant, description, dateOperation, dateSaisie, dateRetrait, mouvementLink, idUser) VALUES 
            (:idC, :op, :mt, :des, :dO, :dS, :dR, :mvL, :idU)");

            $sqlU->execute([
                'idC' => $compte_depense,
                'op' => 'retrait',
                'mt' => $montant_depense,
                'des' => $description,
                'dO' => $dateString2,
                'dS' => $dateString2,
                'dR' => $dateString2,
                'mvL' => $idPagnet,
                'idU' => $_SESSION['iduser']
            ]);

            $sqlU = "UPDATE `" . $nomtableCompte . "` SET montantCompte=montantCompte-" . $montant_depense . " where idCompte=" . $compte_depense;

            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(file_put_contents("getCronLog.html", '<pre>' . print_r($statementU->errorInfo(), true) . '</pre><br>'));
        }

        // Make the changes to the database permanent
        $bdd->commit();
        // header("Refresh:0");
        exit(1);
    } catch (PDOException $e) {
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        exit(0);
    }
}
