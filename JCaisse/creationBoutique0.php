<?php
/*
Résumé : Ce code permet la création d'une boutique.
Commentaire : Ce code contient un formulaire récupérant l'ensemble des informations (nom, adresse et type) sur la boutique créer.
Il insére ces informations dans la table boutique avec un numéro de boutique attribué automatiquement.
Puis insert une ligne dans la table gerant l'information pour dire que l'utilisateur connecté créer cette boutique et est son administrateur.
De plus pour la gestion de chaque boutique ce code créer des tables commençant toutes par le nom de la boutique suivi de :
-journal, -pagej, -pagnet, -lignepj, -designation, -stock pour garder les journaux, les pages de journaux, les pagnets, les lignes de pages de journaux, les désignations et les stocks.
Version : 2.0
Auteur : Ibrahima DIOP,EL hadji mamadou korka
Date de création : 10/04/2016
Date derniére modification : 19/04/2016; 15-04-2018
*/


session_start();
if($_SESSION['iduser']){

require('connection.php');

/**********************/

$nomB           =@htmlentities($_POST["nomB"]);
$labelB         =@htmlentities($_POST["nomB"]);
$adresseB       =@htmlentities($_POST["adresseB"]);
$telephone       =@htmlentities($_POST["telephone"]);
$type     	    =@htmlentities($_POST["type"]);
$categorie      =@htmlentities($_POST["categorie"]);
$registreCom       =@htmlentities($_POST["registreCom"]);
$ninea       =@htmlentities($_POST["ninea"]);
$accompagnateur      =@htmlentities($_POST["accompagnateur"]);

$annuler        =@$_POST["annuler"];
date_default_timezone_set('UTC');
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;

$dateString2=$jour.'-'.$mois.'-'.$annee;

/***************/



	if(!$annuler){
		if ((!$nomB) || (!$adresseB) || (!$type) || (!$categorie)){ 
		
		require('entetehtml.php');
		
		?>
            <body onLoad="process()">
            <header>
               <table width="98%" align="center" border="0"><tr><td>
               <center><img src="images/logojcaisse2.png"></center><nav><ul><li></li>
               </ul></nav></header>
               <center>
               <div class="row">
                  <div class="col-gl-4 col-md-4 col-ms-6 col-sx-12 col-lg-offset-4 col-md-offset-4 col-ms-offset-3">
                     <div class="panel panel-primary">
                        <div class="panel-heading"><h3 class="panel-title">Formulaire de création de Caisse</h3></div>
                            <div class="panel-body">
                            
			  				<table align="center" border="0">
               
               <section> <?php
			   echo'<form class="formulaire2" name="formulaire2" method="post" action="creationBoutique.php">
				
					<div class="form-group">
						<tr><td><label for="nomBoutique">NOM ENTREPRISE <font color="red">*</font></label></td></tr>
						<tr><td><input type="text" class="form-control" placeholder="Nom Entreprise" id="nomB" name="nomB" size="40" value="'.$nomB.'" required /></td></tr>
						<tr><td><div class="help-block" id="helpNomB"></div></td></tr>
					</div>

					<div class="form-group">
						<tr><td><label for="adresse">ADRESSE <font color="red">*</font></label></td></tr>
						<tr><td><input type="text" class="form-control" placeholder="Adresse Entreprise" id="adresseB" name="adresseB" size="40" value="'.$adresseB.'" required /></td></tr>
						<tr><td><div class="help-block" id="helpAdresseB"></div></td></tr>
					</div>

					<div class="form-group">
						<tr><td><label for="adresse">TELEPHONE <font color="red">*</font></label></td></tr>
						<tr><td><input type="text" class="form-control" placeholder="Telephone Entreprise" id="telephone" name="telephone" size="40"  required /></td></tr>
						<tr><td><div class="help-block" id="helpaccompagnateur"></div></td></tr>
					</div>

					<div class="form-group">
						<tr><td><label for="type">TYPE <font color="red">*</font></label></td></tr>
						<tr><td><select name="type" id="type" class="form-control">';
								$sql10="SELECT * FROM `aaa-typeboutique`";
								//echo $sql10;
								$res10=mysql_query($sql10);
								
								while($ligne = mysql_fetch_row($res10)) {
								echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
									} ?>
							</select></td></tr>
                    	<tr><td><div class="help-block" id="helpType"></div></td></tr>
					</div>
                    
					<div class="form-group">
						<tr><td><label for="type">CATEGORIE <font color="red">*</font></label></td></tr>
						<tr><td><select name="categorie" id="categorie" class="form-control"> <?php
								$sql11="SELECT * FROM `aaa-categorie`";
								//echo $sql11;
								$res11=mysql_query($sql11);
								while($ligne2 = mysql_fetch_row($res11)) {
								echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
									} ?>
							</select></td></tr>
                    	<tr><td><div class="help-block" id="helpCategorie"></div></td></tr>
					</div>

					<div class="form-group">
						<tr><td><label for="adresse">REGISTRE DE COMMERCE <font color="red">*</font></label></td></tr>
						<tr><td><input type="text" class="form-control" placeholder="Registre de commerce" id="registreCom" name="registreCom" size="40" required /></td></tr>
						<tr><td><div class="help-block" id="helpaccompagnateur"></div></td></tr>
					</div>

					<div class="form-group">
						<tr><td><label for="adresse">NINEA <font color="red">*</font></label></td></tr>
						<tr><td><input type="text" class="form-control" placeholder="Ninea" id="ninea" name="ninea" size="40" required /></td></tr>
						<tr><td><div class="help-block" id="helpaccompagnateur"></div></td></tr>
					</div>
					
					<div class="form-group">
						<tr><td><label for="adresse">ACCOMPAGNATEUR <font color="red">*</font></label></td></tr>
						<tr><td><input type="password" class="form-control" placeholder="Matricule Accompagnateur" id="accompagnateur" name="accompagnateur" size="40" <?php echo 'value="'.$accompagnateur.'"'; ?> required /></td></tr>
						<tr><td><div class="help-block" id="helpaccompagnateur"></div></td></tr>
					</div>
					
                    <div class="form-group">
						<tr><td><input class="form-check-input" type="checkbox" id="gridCheckProprietaire" name="proprietaire" required="">
						 J'accepte les <a href="#">Conditions d'utilisation</a> et la <a href="#">Politique de confidentialité</a> de Yaatout SARL.
						</td></tr>
					</div>
					
					<div class="form-group">
						<tr><td align="center">
						<font color="red">Les champs qui ont (*) sont obligatoires</font><br/>
						<button type="submit" class="boutonbasic" id="inserer" name="inserer">CREER LA CAISSE</button>
						</td></tr>
					</div>
				</form></table></div></div></div></div></div></center>
			</body>
			</html> <?php
		}
		else{
				$_SESSION['nomB']=$nomB;
				$_SESSION['labelB']=$nomB;
			    $_SESSION['adresseB'] =$adresseB;
			    $_SESSION['type']  =$type;
			    $_SESSION['categorie']  =$categorie;
				$_SESSION['dateCB']  =$dateString;
				$_SESSION['accompagnateur']  =$accompagnateur;
				
//echo $_SESSION['accompagnateur'].'<br/>';

				$sql30="select * from `aaa-boutique` where nomBoutique='".$_SESSION['nomB']."'";
//echo 'sql30-1 :'.$sql30.'<br/>';				
				$res30=@mysql_query($sql30);
			    if(@mysql_num_rows($res30)==true){
					echo'<!DOCTYPE html>';
					echo'<html>';
					echo'<head>';
					echo'<script type="text/javascript" src="alertBoutiqueExiste.js"></script>';
					echo'<script language="JavaScript">document.location="creationBoutique.php"</script>';
					echo'</head>';
					echo'</html>';
				}else{
				$sql30="select * from `aaa-utilisateur` where matricule='".$accompagnateur."'";
//echo 'sql30 :'.$sql30.'<br/>';				
				$res30=@mysql_query($sql30);
					
			    if(@mysql_num_rows($res30)==true){
	 			$sql1="insert into `aaa-boutique` (nomBoutique,labelB,adresseBoutique,telephone,Pays,type,categorie,datecreation,RegistreCom,Ninea,Accompagnateur) values('".mysql_real_escape_string($_SESSION['nomB'])."','".mysql_real_escape_string($_SESSION['nomB'])."','".mysql_real_escape_string($_SESSION['adresseB'])."','".$telephone."','Senegal','".$_SESSION['type']."','".$_SESSION['categorie']."','".$dateString."','".$registreCom."','".$ninea."','".$accompagnateur."')";
//echo 'sql1 :'.$sql1.'<br/>';	
			   	$res1=@mysql_query($sql1) or die ("insertion boutique impossible-2".mysql_error());

				$sql8="select * from `aaa-boutique` where nomBoutique='".mysql_real_escape_string($_SESSION['nomB'])."'";
//echo 'sql8 :'.$sql8.'<br/>';	
				$res8=@mysql_query($sql8);
			    if(@mysql_num_rows($res8)){
					 if($tab8=@mysql_fetch_array($res8)){
						$_SESSION['idBoutique']=$tab8['idBoutique'];
						$sqlC="SELECT * FROM `aaa-acces` where  idutilisateur='".$_SESSION['iduser']."' AND idBoutique=0 ";
						$resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
						$nbre = mysql_fetch_array($resC) ;
						if($nbre){
							$sql9="UPDATE `aaa-acces` set idBoutique=".$_SESSION['idBoutique']." where idutilisateur=".$_SESSION['iduser'];
							$res9=@mysql_query($sql9) or die ("insertion acces impossible");
						}
						else{
							$sql3="INSERT INTO `aaa-acces` (idutilisateur,idBoutique,proprietaire,gerant,caissier,activer,profil) VALUES (".$_SESSION['iduser'].",".$_SESSION['idBoutique'].",1,1,1,1,'Admin')";
							$req3=@mysql_query($sql3) or die ("insertion dans acces impossible");
						}
					}
			    }

				$sql10="UPDATE `aaa-utilisateur` set creationB=1 where idutilisateur=".$_SESSION['iduser'];
//echo 'sql10 :'.$sql10.'<br/>';					
			    $res10=@mysql_query($sql10) or die ("insertion acces impossible");
				
				}else{
					echo '<script type="text/javascript"> alert("ERREUR : LE MATRICULE DE L\'ACCOMPAGNATEUR SAISIE EST INCONNU ...");</script>';
					echo'<!DOCTYPE html>';
					echo'<html>';
					echo'<head>';
					echo'<script language="JavaScript">document.location="creationBoutique.php"</script>';
					echo'</head>';
					echo'</html>';
				}

require('declarationVariables.php');


$sql0="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableJournal."` (`idjournal` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`mois` INT NOT NULL ,`annee` INT NOT NULL ,`totalVente` double NOT NULL,`totalVersement` double NOT NULL,`totalBon` double NOT NULL,`totalFrais` double NOT NULL) ENGINE = MYISAM ;";

$sql1="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableCategorie."` (`idcategorie` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`nomcategorie` VARCHAR(50) NOT NULL,`categorieParent` int(11) DEFAULT NULL) ENGINE = MYISAM ;";

$sql2="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtablePage."` (`numpage` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`datepage` VARCHAR(15) NOT NULL ,`totalVente` double NOT NULL,`totalService` double NOT NULL,`totalVersement` double NOT NULL,`totalBon` double NOT NULL,`totalFrais` double NOT NULL,`totalCaisse` double NOT NULL,`datetime` VARCHAR(25) NOT NULL,`iduser` INT NOT NULL) ENGINE = MYISAM ;";

$sql3="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtablePagnet."` (`idPagnet` INT NOT NULL AUTO_INCREMENT ,`datepagej` VARCHAR(15) NOT NULL ,`type` INT NOT NULL,`heurePagnet` VARCHAR(10) NOT NULL ,`totalp` REAL NOT NULL ,`remise` REAL NOT NULL,`apayerPagnet` REAL NOT NULL,`restourne` REAL NOT NULL,`versement` REAL NOT NULL,`paiement` VARCHAR(25) NOT NULL,`verrouiller` BOOLEAN NOT NULL,`idClient` INT NOT NULL,`iduser` INT NOT NULL,PRIMARY KEY (`idPagnet`)) ENGINE = MYISAM;";

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

    $sql4="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableLigne."` (`numligne` INT NOT NULL AUTO_INCREMENT,`idPagnet` INT NOT NULL,`idMutuellePagnet` INT NOT NULL,`idDesignation` INT NOT NULL,`designation` VARCHAR(155) NOT NULL,`idStock` INT NOT NULL,`forme` VARCHAR(50) NOT NULL,`prixPublic` REAL NOT NULL,`quantite` INT NOT NULL,`prixtotal` REAL NOT NULL,`classe` INT(1) NOT NULL, PRIMARY KEY (`numligne`)) ENGINE = MYISAM";

    $sql5="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableDesignation."` (`idDesignation` INT NOT NULL AUTO_INCREMENT,`idFusion` INT NULL ,`designation` VARCHAR(50) NOT NULL,`description` VARCHAR(100),`categorie` VARCHAR(50) NOT NULL,`forme` VARCHAR(50) NOT NULL,`tableau` VARCHAR(50) NOT NULL,`seuil` INT NOT NULL,`prixSession` REAL NOT NULL DEFAULT '0', `prixPublic` REAL NOT NULL,`codeBarreDesignation` VARCHAR(50) NOT NULL,`codeBarreuniteStock` VARCHAR(50) NOT NULL,`classe` INT NOT NULL,`tva` INT NOT NULL, PRIMARY KEY (`idDesignation`)) ENGINE = MYISAM";

    $sql6="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableStock."` (
    `idStock` INT NOT NULL AUTO_INCREMENT ,
    `idDesignation` INT NOT NULL,`idBl` INT NOT NULL,
	`designation` VARCHAR(50) NOT NULL,`quantiteStockinitial` REAL NOT NULL ,`forme` VARCHAR(50) NOT NULL ,`nbreArticleUniteStock` INT NOT NULL ,`totalArticleStock` REAL NOT NULL ,`dateStockage` VARCHAR(15) NOT NULL ,`quantiteStockCourant` REAL NOT NULL ,`retirer` REAL NOT NULL ,`dateFinStock` VARCHAR(15) NOT NULL ,`dateExpiration` VARCHAR(15) NOT NULL , `prixSession` DOUBLE NOT NULL DEFAULT '0' , `prixPublic` DOUBLE NOT NULL, `prixDeRevientDuStock` DOUBLE NOT NULL, `pointControleArticle` int(11) NOT NULL DEFAULT '0', `pointControleUnite` int(11) NOT NULL DEFAULT '0',`iduser` int(11) NOT NULL,PRIMARY KEY (`idStock`)) ENGINE = MYISAM ;";
	
	$sql10="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableVersement."` (`idVersement` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`idClient` INT NOT NULL,`idFournisseur` INT NOT NULL,`idMutuelle` INT NOT NULL,`montant` DOUBLE NOT NULL,`paiement` VARCHAR(25) NOT NULL,`dateVersement` VARCHAR(15) NOT NULL,`heureVersement` VARCHAR(10) NOT NULL,`iduser` INT NOT NULL) ENGINE = MYISAM ;";
	
	$sql14="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableInventaire."` (`idInventaire` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`idStock` int(11) NOT NULL,`idDesignation` int(11) NOT NULL,`quantite` double NOT NULL,`quantiteStockCourant` double NOT NULL,`nbreArticleUniteStock` int(11) NOT NULL,`dateInventaire` varchar(10) COLLATE utf8_bin NOT NULL,`type` int(11) DEFAULT NULL,`iduser` int(11) NOT NULL) ENGINE=MyISAM ";

	$sql18="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableMutuelle."` (`idMutuelle` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`nomMutuelle` varchar(50) NOT NULL,`tauxMutuelle` int(11) NOT NULL,`adresseMutuelle` varchar(50) NOT NULL,`telephoneMutuelle` varchar(20) NOT NULL,,PRIMARY KEY (`idMutuelle`)) ENGINE = MYISAM ;";

	$sql19="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableMutuellePagnet."` (`idMutuellePagnet` INT NOT NULL AUTO_INCREMENT ,`datepagej` VARCHAR(15) NOT NULL ,`type` INT NOT NULL,`heurePagnet` VARCHAR(10) NOT NULL ,`totalp` REAL NOT NULL ,`remise` REAL NOT NULL,`taux` REAL NOT NULL,`apayerPagnet` REAL NOT NULL,`apayerMutuelle` REAL NOT NULL,`verrouiller` BOOLEAN NOT NULL,`idClient` INT NOT NULL,`idMutuelle` INT NOT NULL,`adherant` VARCHAR(250) NOT NULL,`codeAdherant` VARCHAR(20) NOT NULL,`codeBeneficiaire` VARCHAR(20) NOT NULL,`numeroRecu` VARCHAR(20) NOT NULL,`dateRecu` VARCHAR(20) NOT NULL,`iduser` INT NOT NULL,PRIMARY KEY (`idMutuellePagnet`)) ENGINE = MYISAM;";

}
else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

	$sql4="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableLigne."` (`numligne` INT NOT NULL AUTO_INCREMENT,`idPagnet` INT NOT NULL,`idDesignation` INT NOT NULL ,`idEntrepot` INT NOT NULL ,`designation` VARCHAR(155) NOT NULL,`idStock` INT NOT NULL,`unitevente` VARCHAR(155) NOT NULL,`prixunitevente` REAL NOT NULL,`quantite` INT NOT NULL,`prixtotal` REAL NOT NULL,`classe` INT(1) NOT NULL, PRIMARY KEY (`numligne`)) ENGINE = MYISAM";

	$sql5="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableDesignation."` (`idDesignation` INT NOT NULL AUTO_INCREMENT,`idFusion` INT NULL ,`designation` VARCHAR(50) NOT NULL,`description` VARCHAR(100),`categorie` VARCHAR(50) NOT NULL,`uniteStock` VARCHAR(50) NOT NULL,`uniteDetails` VARCHAR(50) NOT NULL,`nbreArticleUniteStock` REAL NOT NULL,`prix` REAL NOT NULL,`seuil` INT NOT NULL, `prixuniteStock` REAL NOT NULL, `prixachat` REAL NOT NULL,`codeBarreDesignation` VARCHAR(50) NOT NULL,`codeBarreuniteStock` VARCHAR(50) NOT NULL,`classe` INT NOT NULL, PRIMARY KEY (`idDesignation`)) ENGINE = MYISAM";

    $sql6="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableStock."` (
    `idStock` INT NOT NULL AUTO_INCREMENT ,
    `idDesignation` INT NOT NULL,`idBl` INT NOT NULL,
	`designation` VARCHAR(50) NOT NULL,`quantiteStockinitial` REAL NOT NULL ,`uniteStock` VARCHAR(50) NOT NULL ,`uniteDetails` VARCHAR(50) NOT NULL,`nbreArticleUniteStock` INT NOT NULL ,`totalArticleStock` REAL NOT NULL ,`dateStockage` VARCHAR(15) NOT NULL ,`quantiteStockCourant` REAL NOT NULL ,`retirer` REAL NOT NULL ,`dateFinStock` VARCHAR(15) NOT NULL ,`dateExpiration` VARCHAR(15) NOT NULL , `prixuniteStock` DOUBLE NOT NULL , `prixunitaire` DOUBLE NOT NULL,`prixachat` DOUBLE NOT NULL, `prixDeRevientDuStock` DOUBLE NOT NULL, `pointControleArticle` int(11) NOT NULL DEFAULT '0', `pointControleUnite` int(11) NOT NULL DEFAULT '0',`iduser` int(11) NOT NULL,PRIMARY KEY (`idStock`)) ENGINE = MYISAM ;";
	
	$sql10="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableVersement."` (`idVersement` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`idClient` INT NOT NULL,`idFournisseur` INT NOT NULL,`montant` DOUBLE NOT NULL,`paiement` VARCHAR(25) NOT NULL,`dateVersement` VARCHAR(15) NOT NULL,`heureVersement` VARCHAR(10) NOT NULL,`iduser` INT NOT NULL) ENGINE = MYISAM ;";
	
	$sql12="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableEntrepot."` (`idEntrepot` INT NOT NULL AUTO_INCREMENT,`nomEntrepot` VARCHAR(50) NOT NULL,`adresseEntrepot` VARCHAR(100), PRIMARY KEY (`idEntrepot`)) ENGINE = MYISAM";

	$sql13="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableEntrepotStock."` (
	`idEntrepotStock` INT NOT NULL AUTO_INCREMENT ,`idStock` INT NOT NULL,`idEntrepot` INT NOT NULL,`idTransfert` INT NOT NULL,
	`idDesignation` INT NOT NULL,
	`designation` VARCHAR(50) NOT NULL,`quantiteStockinitial` REAL NOT NULL ,`uniteStock` VARCHAR(50) NOT NULL ,`uniteDetails` VARCHAR(50) NOT NULL,`nbreArticleUniteStock` INT NOT NULL ,`totalArticleStock` REAL NOT NULL ,`dateStockage` VARCHAR(15) NOT NULL ,`quantiteStockCourant` REAL NOT NULL ,`retirer` REAL NOT NULL ,`dateFinStock` VARCHAR(15) NOT NULL ,`dateExpiration` VARCHAR(15) NOT NULL , `prixuniteStock` DOUBLE NOT NULL , `prixunitaire` DOUBLE NOT NULL, `prixDeRevientDuStock` DOUBLE NOT NULL, `pointControleArticle` int(11) NOT NULL DEFAULT '0', `pointControleUnite` int(11) NOT NULL DEFAULT '0',`iduser` int(11) NOT NULL,PRIMARY KEY (`idEntrepotStock`)) ENGINE = MYISAM ;";
	
	$sql14="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableInventaire."` (`idInventaire` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`idStock` int(11) NOT NULL,`idEntrepotStock` int(11) NOT NULL,`idDesignation` int(11) NOT NULL,`quantite` double NOT NULL,`quantiteStockCourant` double NOT NULL,`nbreArticleUniteStock` int(11) NOT NULL,`dateInventaire` varchar(10) COLLATE utf8_bin NOT NULL,`type` int(11) DEFAULT NULL,`iduser` int(11) NOT NULL) ENGINE=MyISAM ";

}
else{

    $sql4="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableLigne."` (`numligne` INT NOT NULL AUTO_INCREMENT,`idPagnet` INT NOT NULL,`idDesignation` INT NOT NULL ,`designation` VARCHAR(155) NOT NULL,`idStock` INT NOT NULL,`unitevente` VARCHAR(155) NOT NULL,`prixunitevente` REAL NOT NULL,`quantite` INT NOT NULL,`prixtotal` REAL NOT NULL,`classe` INT(1) NOT NULL, PRIMARY KEY (`numligne`)) ENGINE = MYISAM";

    $sql5="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableDesignation."` (`idDesignation` INT NOT NULL AUTO_INCREMENT,`idFusion` INT NULL ,`designation` VARCHAR(50) NOT NULL,`description` VARCHAR(100),`categorie` VARCHAR(50) NOT NULL,`uniteStock` VARCHAR(50) NOT NULL,`uniteDetails` VARCHAR(50) NOT NULL,`nbreArticleUniteStock` REAL NOT NULL,`prix` REAL NOT NULL,`seuil` INT NOT NULL, `prixuniteStock` REAL NOT NULL, `prixachat` REAL NOT NULL,`codeBarreDesignation` VARCHAR(50) NOT NULL,`codeBarreuniteStock` VARCHAR(50) NOT NULL,`classe` INT NOT NULL, PRIMARY KEY (`idDesignation`)) ENGINE = MYISAM";

    $sql6="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableStock."` (
    `idStock` INT NOT NULL AUTO_INCREMENT ,
    `idDesignation` INT NOT NULL,`idBl` INT NOT NULL,
	`designation` VARCHAR(50) NOT NULL,`quantiteStockinitial` REAL NOT NULL ,`uniteStock` VARCHAR(50) NOT NULL ,`uniteDetails` VARCHAR(50) NOT NULL,`nbreArticleUniteStock` INT NOT NULL ,`totalArticleStock` REAL NOT NULL ,`dateStockage` VARCHAR(15) NOT NULL ,`quantiteStockCourant` REAL NOT NULL ,`retirer` REAL NOT NULL ,`dateFinStock` VARCHAR(15) NOT NULL ,`dateExpiration` VARCHAR(15) NOT NULL , `prixuniteStock` DOUBLE NOT NULL , `prixunitaire` DOUBLE NOT NULL,`prixachat` DOUBLE NOT NULL, `prixDeRevientDuStock` DOUBLE NOT NULL, `pointControleArticle` int(11) NOT NULL DEFAULT '0', `pointControleUnite` int(11) NOT NULL DEFAULT '0',`iduser` int(11) NOT NULL,PRIMARY KEY (`idStock`)) ENGINE = MYISAM ;";
	
	if (($_SESSION['type']=="Multi-service") && ($_SESSION['categorie']=="Detaillant")) {

		$sql15="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableTransfert."` (`idTransfert` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`idPagnet` int(11) NOT NULL,`idTransaction` int(11) NOT NULL,`nomTransfert` varchar(50) COLLATE utf8_bin NOT NULL,`compte` double NOT NULL,`caisse` double NOT NULL,`montantAvant` double NOT NULL,`dateTransfert` varchar(10) COLLATE utf8_bin NOT NULL,`heureTransfert` varchar(10) COLLATE utf8_bin NOT NULL) ENGINE=MyISAM;";
	
	}

	$sql14="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableInventaire."` (`idInventaire` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`idStock` int(11) NOT NULL,`idDesignation` int(11) NOT NULL,`quantite` double NOT NULL,`quantiteStockCourant` double NOT NULL,`nbreArticleUniteStock` int(11) NOT NULL,`dateInventaire` varchar(10) COLLATE utf8_bin NOT NULL,`type` int(11) DEFAULT NULL,`iduser` int(11) NOT NULL) ENGINE=MyISAM;";

	$sql10="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableVersement."` (`idVersement` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`idClient` INT NOT NULL,`idFournisseur` INT NOT NULL,`montant` DOUBLE NOT NULL,`paiement` VARCHAR(25) NOT NULL,`dateVersement` VARCHAR(15) NOT NULL,`heureVersement` VARCHAR(10) NOT NULL,`iduser` INT NOT NULL) ENGINE = MYISAM ;";
	
}

$sql7="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableTotalStock."` (`designation` VARCHAR(50) NOT NULL,`quantiteEnStocke` REAL NOT NULL, PRIMARY KEY (`designation`)) ENGINE = MYISAM ;";

$sql8="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableClient."` (`idClient` INT NOT NULL AUTO_INCREMENT ,`prenom` VARCHAR(50) NOT NULL,`nom` VARCHAR(20) NOT NULL ,`adresse` VARCHAR(50) NOT NULL,`telephone` VARCHAR(50) NOT NULL,`solde` double NOT NULL, `activer` INT NOT NULL,`datetime` VARCHAR(25) NOT NULL,`iduser` INT NOT NULL, PRIMARY KEY (`idClient`)) ENGINE = MYISAM ;";

$sql9="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableBon."` (`idBon` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`idClient` INT NOT NULL,`montant` DOUBLE NOT NULL ,`date` VARCHAR(15) NOT NULL,`heureBon` VARCHAR(10) NOT NULL,`iduser` INT NOT NULL) ENGINE = MYISAM ;";

$sql11="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableCompte."` (`idCompte` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`idTransaction` INT NOT NULL,`nomCompte` VARCHAR( 50 ) NOT NULL ,`montantInitial` double NOT NULL,`totalDepot` double NOT NULL,`totalRetrait` double NOT NULL,`montantCourant` double NOT NULL,`dateCompte` VARCHAR(15) NOT NULL) ENGINE = MYISAM ;";

$sql16="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableBl."` (`idBl` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`idFournisseur` INT NOT NULL,  `numeroBl` varchar(30) NOT NULL,`dateBl` varchar(20) NOT NULL,`heureBl` varchar(10) NOT NULL,`montantBl` double NOT NULL,`iduser` int(11) NOT NULL) ENGINE = MYISAM ;";

$sql17="CREATE TABLE IF NOT EXISTS `diib8761_bdjcaisse`.`".$nomtableFournisseur."` (`idFournisseur` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`nomFournisseur` varchar(50) NOT NULL,`adresseFournisseur` varchar(50) NOT NULL,`telephoneFournisseur` varchar(20) NOT NULL, `montant` double NOT NULL) ENGINE = MYISAM ;";


$res0 =@mysql_query($sql0) or die ("creation table journal impossible");
$res1 =@mysql_query($sql1) or die ("creation table categorie impossible");
$res2 =@mysql_query($sql2) or die ("creation table page impossible");
$res3 =@mysql_query($sql3) or die ("creation table pagnet impossible");
$res4 =@mysql_query($sql4) or die ("creation table ligne impossible");
$res5 =@mysql_query($sql5) or die ("creation table designation impossible");
$res6 =@mysql_query($sql6) or die ("creation table stock impossible");
$res7 =@mysql_query($sql7) or die ("creation table totalStock impossible");
$res8 =@mysql_query($sql8) or die ("creation table client impossible");
$res9 =@mysql_query($sql9) or die ("creation table bon impossible");
$res10=@mysql_query($sql10) or die ("creation table versement impossible");
$res11=@mysql_query($sql11) or die ("creation table designation service et depence impossible");
if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
	$res12=@mysql_query($sql12) or die ("creation table Entrepot impossible");
	$res13=@mysql_query($sql13) or die ("creation table Stock Entrepot impossible");
}
$res14=@mysql_query($sql14) or die ("creation table Inventaire impossible");
if (($_SESSION['type']=="Multi-service") && ($_SESSION['categorie']=="Detaillant")) {
	$res15=@mysql_query($sql15) or die ("creation table Transfert impossible");
}
$res16=@mysql_query($sql16) or die ("creation table Bl impossible");
$res17=@mysql_query($sql17) or die ("creation table fournisseur impossible");
if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
	$res18=@mysql_query($sql16) or die ("creation table Mutuelle impossible");
	$res19=@mysql_query($sql17) or die ("creation table Pagnet Mutuelle impossible");	
}


				echo'<!DOCTYPE html>';
			    echo'<html>';
			    echo'<head>';
				echo'<script type="text/javascript" src="alertActivation3.js"></script>';
			    echo'<script language="JavaScript">document.location="../index.php"</script>';
			    echo'</head>';
			    echo'</html>';
		}
		
	}	
		
		
		
	}
	else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script language="JavaScript">document.location="creationBoutique.php"</script>';
		echo'</head>';
		echo'</html>';
	}

}
else{
	echo'<!DOCTYPE html>';
	echo'<html>';
	echo'<head>';
	echo'<script language="JavaScript">document.location="../index.php"</script>';
	echo'</head>';
	echo'</html>';
}
?>
