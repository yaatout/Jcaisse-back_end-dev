<?php

session_save_path($_SERVER['DOCUMENT_ROOT']."/../sessions");

date_default_timezone_set('Africa/Dakar');

$sql3="select * from `aaa-boutique` where idBoutique=".$_SESSION['idBoutique'];
$res3=@mysql_query($sql3);
if($tab3=@mysql_fetch_array($res3)){
	$_SESSION['Pays'] = $tab3["Pays"];
}

if($_SESSION['devise']=='0' || $_SESSION['devise']==null){
	$sql="select * from `aaa-devise`  where Devise='".$_SESSION['Pays']."'";
	$res=mysql_query($sql);
	if($tabD=mysql_fetch_array($res)){
		$_SESSION['symbole']=$tabD['Symbole'];
		$_SESSION['devise']=1;
	}
}


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
$nomtableCompte =$_SESSION['nomB']."-compte";
$nomtableRayon =$_SESSION['nomB']."-Rayon";

$nomtableBl =$_SESSION['nomB']."-bl";
$nomtableFournisseur =$_SESSION['nomB']."-fournisseur";
$nomtableEntrepot =$_SESSION['nomB']."-entrepot";
$nomtableEntrepotStock =$_SESSION['nomB']."-entrepotstock";
$nomtableInventaire =$_SESSION['nomB']."-inventaire";
$nomtableTransfert =$_SESSION['nomB']."-transfert";
$nomtableVoyage =$_SESSION['nomB']."-voyage";
$nomtableFrais =$_SESSION['nomB']."-frais";

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

//$date = new DateTime('25-02-2011');
//$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;

$dateStringMA=$annee.''.$mois;

$dateString2=$jour.'-'.$mois.'-'.$annee;
?>
