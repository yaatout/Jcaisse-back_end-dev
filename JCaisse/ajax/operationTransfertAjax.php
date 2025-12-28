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

$msg_info = '';

$nomtableContainer = $_SESSION['nomB'] . "-container";
$nomtableAvion = $_SESSION['nomB'] . "-avion";
$nomtableEnregistrement = $_SESSION['nomB'] . "-enregistrement";
$nomtableChargement = $_SESSION['nomB'] . "-chargement";
$nomtableEntrepot = $_SESSION['nomB'] . "-entrepot";


if (isset($_POST['getReference'])) {


    $query = htmlspecialchars(trim($_POST['query']));

    $reference = [];

    // $sql3 = "SELECT * FROM `" . $nomtableDesignation . "` where designation LIKE '%$query%'";

    // $res3 = mysql_query($sql3) or die("persoonel requête 3" . mysql_error());

    // $reference = mysql_fetch_assoc($res3);

    $sql = "SELECT * FROM `" . $nomtableDesignation . "` WHERE designation LIKE '%$query%' and classe=0";
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $res = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $key) {
        $reference[] = $key['idDesignation'] . " . " . $key['designation'];
    }

    echo json_encode($reference);
} else if (isset($_POST['getStockDispo'])) {
    $idDesignation = htmlspecialchars(trim($_POST['idDesignation']));

    $sql = "SELECT SUM(quantiteStockCourant/nbreArticleUniteStock) as totalStock, idEntrepot FROM `" . $nomtableEntrepotStock . "` WHERE idDesignation=" . $idDesignation . " GROUP BY idEntrepot";

    $statement = $bdd->prepare($sql);
    $statement->execute();
    $res = $statement->fetchAll(PDO::FETCH_ASSOC);

    $text = [];

    foreach ($res as $key) {
        $sql = "SELECT nomEntrepot FROM `" . $nomtableEntrepot . "` WHERE idEntrepot=" . $key['idEntrepot'] . "";
        // var_dump($sql);
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $entrepot = $statement->fetch();
        // var_dump($entrepot);
        if (!$entrepot['nomEntrepot']) {
            $entrepot['nomEntrepot'] = "NEANT";
        }

        $text[] =  $entrepot['nomEntrepot'] . " ==> " . $key['totalStock'];
    }

    echo json_encode($text);
} else if (isset($_POST['getEntrepot'])) {
    // $idDesignation = htmlspecialchars(trim($_POST['idDesignation']));

    $sql = "SELECT * FROM `" . $nomtableEntrepot . "`";
    // var_dump($sql);
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $entrepot = $statement->fetchAll();

    $entrepots = [];

    foreach ($entrepot as $key) {

        $entrepots[] =  $key['idEntrepot'] . " ==> " . $key['nomEntrepot'];
    }

    echo json_encode($entrepots);
} else if (isset($_POST['validateTransfert'])) {
    $designation = htmlspecialchars(trim($_POST['designation']));
    $idDesignation = htmlspecialchars(trim($_POST['idDesignation']));
    $depotOrigine = htmlspecialchars(trim($_POST['depotOrigine']));
    $depotDestination = htmlspecialchars(trim($_POST['depotDestination']));
    $depotDestinationQte =
        htmlspecialchars(trim($_POST['depotDestinationQte']));
    $depotDestinationQteInit = htmlspecialchars(trim($_POST['depotDestinationQte']));


    $depotOrigineName = htmlspecialchars(trim($_POST['depotOrigineName']));
    $depotDestinationName = htmlspecialchars(trim($_POST['depotDestinationName']));


    $sql = "SELECT * FROM `" . $nomtableEntrepotStock . "` where quantiteStockCourant>0 and idEntrepot = " . $depotOrigine . " and idDesignation = " . $idDesignation;
    // var_dump($sql);
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $data = $statement->fetchAll();
    $cpt = 0;

    foreach ($data as $key) {

        if ($key['quantiteStockCourant'] >= $depotDestinationQte) {
            $cpt = 1;

            $req4 = $bdd->prepare("INSERT INTO `" . $nomtableEntrepotStock . "` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, heureStockage, quantiteStockCourant)
            values (:idS,:idE,:idDes,:idTr,:design,:qtI,:us,:nbrA,:tA,:dS,:hS,:qtC)");
            $req4->execute(array(
                'idS' => $key["idStock"],
                'idE' => $depotDestination,
                'idDes' => $idDesignation,
                'idTr' => $key["idEntrepotStock"],
                'design' => $designation,
                'qtI' => $depotDestinationQte,
                'us' => $key["uniteStock"],
                'nbrA' => $key["nbreArticleUniteStock"],
                'tA' => $key["nbreArticleUniteStock"] * $depotDestinationQte,
                'dS' => $dateString,
                'hS' => $heureString,
                'qtC' => $key["nbreArticleUniteStock"] * $depotDestinationQte
            ))  or die(print_r($req4->errorInfo()));
            $req4->closeCursor();


            $sqlU = "UPDATE `" . $nomtableEntrepotStock . "` SET quantiteStockCourant=quantiteStockCourant-" . ($key["nbreArticleUniteStock"] * $depotDestinationQte) . " where idEntrepotStock=" . $key["idEntrepotStock"];

            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(print_r($statementU->errorInfo(), true));

            break 1;
        }
    }

    if ($cpt == 0) {

        foreach ($data as $key) {

            if ($depotDestinationQte > 0) {



                if ($depotDestinationQte > ($key["quantiteStockCourant"] / $key["nbreArticleUniteStock"])) {

                    $req4 = $bdd->prepare("INSERT INTO `" . $nomtableEntrepotStock . "` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, heureStockage, quantiteStockCourant)
                    values (:idS,:idE,:idDes,:idTr,:design,:qtI,:us,:nbrA,:tA,:dS,:hS,:qtC)");
                    $req4->execute(array(
                        'idS' => $key["idStock"],
                        'idE' => $depotDestination,
                        'idDes' => $idDesignation,
                        'idTr' => $key["idEntrepotStock"],
                        'design' => $designation,
                        'qtI' => $key["quantiteStockCourant"] / $key["nbreArticleUniteStock"],
                        'us' => $key["uniteStock"],
                        'nbrA' => $key["nbreArticleUniteStock"],
                        'tA' => $key["quantiteStockCourant"] * $key["nbreArticleUniteStock"],
                        'dS' => $dateString,
                        'hS' => $heureString,
                        'qtC' => $key["quantiteStockCourant"] * $key["nbreArticleUniteStock"]
                    ))  or die(print_r($req4->errorInfo()));
                    $req4->closeCursor();

                    $sqlU = "UPDATE `" . $nomtableEntrepotStock . "` SET quantiteStockCourant=0 where idEntrepotStock=" . $key["idEntrepotStock"];
                } else {

                    $req4 = $bdd->prepare("INSERT INTO `" . $nomtableEntrepotStock . "` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, heureStockage, quantiteStockCourant)
                    values (:idS,:idE,:idDes,:idTr,:design,:qtI,:us,:nbrA,:tA,:dS,:hS,:qtC)");
                    $req4->execute(array(
                        'idS' => $key["idStock"],
                        'idE' => $depotDestination,
                        'idDes' => $idDesignation,
                        'idTr' => $key["idEntrepotStock"],
                        'design' => $designation,
                        'qtI' => $depotDestinationQte,
                        'us' => $key["uniteStock"],
                        'nbrA' => $key["nbreArticleUniteStock"],
                        'tA' => $depotDestinationQte * $key["nbreArticleUniteStock"],
                        'dS' => $dateString,
                        'hS' => $heureString,
                        'qtC' => $depotDestinationQte * $key["nbreArticleUniteStock"]
                    ))  or die(print_r($req4->errorInfo()));
                    $req4->closeCursor();

                    $sqlU = "UPDATE `" . $nomtableEntrepotStock . "` SET quantiteStockCourant=quantiteStockCourant-" . ($key["nbreArticleUniteStock"] * $depotDestinationQte) . " where idEntrepotStock=" . $key["idEntrepotStock"];
                }

                $statementU = $bdd->prepare($sqlU);
                $statementU->execute() or die(print_r($statementU->errorInfo(), true));
                $depotDestinationQte = $depotDestinationQte - ($key["quantiteStockCourant"] / $key["nbreArticleUniteStock"]);
            }
        }
    }

    // var_dump($data);

    $response = $designation . " ==> " . "------ ==> " . $depotOrigineName . " ==> " . $dateString . " " . $heureString . " ==> " . $depotDestinationName . " ==> " . $depotDestinationQteInit . " ==> -------" . " ==> ---------";

    echo $response;


    // echo json_encode($entrepots);
}
