<?php



session_save_path($_SERVER['DOCUMENT_ROOT']."/../sessions");

date_default_timezone_set('Africa/Dakar');

// session_start();



$nomtableJournal=$_SESSION['nomB']."-journal";

$nomtableCategorie   =$_SESSION['nomB']."-categorie";

$nomtablePage=$_SESSION['nomB']."-pagej";

$nomtablePagnet=$_SESSION['nomB']."-pagnet";

$nomtableLigne=$_SESSION['nomB']."-lignepj";

$nomtableDesignation=$_SESSION['nomB']."-designation";

$nomtableStock=$_SESSION['nomB']."-stock";



$nomtableComposant=$_SESSION['nomB']."-composant";

$nomtableStockComposant=$_SESSION['nomB']."-stockComposant";

$nomtableComposition=$_SESSION['nomB']."-composition";



$nomtableTotalStock=$_SESSION['nomB']."-totalstock";

$nomtableBon=$_SESSION['nomB']."-bon";

$nomtableClient=$_SESSION['nomB']."-client";

$nomtableVersement=$_SESSION['nomB']."-versement";

$nomtableInventaire1=$_SESSION['nomB']."-inventairePermanant";

$nomtableInventaire2=$_SESSION['nomB']."-inventaireIntermittent";

$nomtableDesignationSD =$_SESSION['nomB']."-designationsd";

$nomtableRayon =$_SESSION['nomB']."-Rayon";



$nomtableBl =$_SESSION['nomB']."-bl";

$nomtableFournisseur =$_SESSION['nomB']."-fournisseur";

$nomtableEntrepot =$_SESSION['nomB']."-entrepot";

$nomtableEntrepotStock =$_SESSION['nomB']."-entrepotstock";

$nomtableEntrepotTransfert =$_SESSION['nomB']."-entrepottransfert";

$nomtableInventaire =$_SESSION['nomB']."-inventaire";

$nomtableTransfert =$_SESSION['nomB']."-transfert";

$nomtableVoyage =$_SESSION['nomB']."-voyage";

$nomtableFrais =$_SESSION['nomB']."-frais";

$nomtableFacture =$_SESSION['nomB']."-facture";

$nomtableMutuelle =$_SESSION['nomB']."-mutuelle";

$nomtableMutuelleBon =$_SESSION['nomB']."-mutuelleBon";

$nomtableMutuellePagnet =$_SESSION['nomB']."-mutuellepagnet";

$nomtableStructure =$_SESSION['nomB']."-structure";

$nomtableStructureClient =$_SESSION['nomB']."-structureclient";

$nomtableStructurePagnet =$_SESSION['nomB']."-structurepagnet";



$nomtableCompte =$_SESSION['nomB']."-compte";

$nomtableComptemouvement =$_SESSION['nomB']."-comptemouvement";

$nomtableCompteoperation =$_SESSION['nomB']."-compteoperation";

$nomtableComptetype =$_SESSION['nomB']."-comptetype";



/**Debut informations sur la date d'Aujourdhui **/

// if($_SESSION['Pays']=='Canada'){ 

// 	$date = new DateTime();

// 	$timezone = new DateTimeZone('Canada/Eastern');

// }

//else{

	$date = new DateTime();

	$timezone = new DateTimeZone('Africa/Dakar');

//}

$date->setTimezone($timezone);

$annee =$date->format('Y');

$mois =$date->format('m');

$jour =$date->format('d');

$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;

$dateString2=$jour.'-'.$mois.'-'.$annee;

$dateHeures=$dateString.' '.$heureString;

$dateStringMA=$annee.''.$mois;

/**Fin informations sur la date d'Aujourdhui **/

?>

