<?php
/*$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtableCategorie   =$_SESSION['nomB']."-categorie";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";
$nomtableBon=$_SESSION['nomB']."-bon";
$nomtableClient=$_SESSION['nomB']."-client";
$nomtableVersement=$_SESSION['nomB']."-versement";
$nomtableInventaire1=$_SESSION['nomB']."-inventairePermanant";
$nomtableInventaire2=$_SESSION['nomB']."-inventaireIntermittent";*/

//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;

$dateString2=$jour.'-'.$mois.'-'.$annee;

$dateStringMA=$annee.''.$mois;

$dateComplet=$date->format('d-m-Y ࠈ:i:s');
?>
