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

						$resC = mysql_query($sqlC) or die ("personnel requête 2".mysql_error());

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


	if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

		$sql_Bl="CREATE TABLE IF NOT EXISTS `".$nomtableBl."` (
			`idBl` int(11) NOT NULL AUTO_INCREMENT,
			`idFournisseur` int(11) NOT NULL,
			`numeroBl` varchar(30) NOT NULL,
			`dateBl` varchar(20) NOT NULL,
			`heureBl` varchar(10) NOT NULL,
			`montantBl` double NOT NULL,
			`iduser` int(11) NOT NULL,
			`description` varchar(250) DEFAULT NULL,
			`image` varchar(50) DEFAULT NULL,
			`dateEcheance` varchar(20) NOT NULL,
			PRIMARY KEY (`idBl`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Bl =@mysql_query($sql_Bl) or die ("creation table BL impossible -- ".mysql_error());

		$sql_Bon="CREATE TABLE IF NOT EXISTS `".$nomtableBon."` (
			`idBon` int(11) NOT NULL AUTO_INCREMENT,
			`idClient` int(11) NOT NULL,
			`montant` double NOT NULL,
			`date` varchar(15) NOT NULL,
			`heureBon` varchar(10) NOT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idBon`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Bon =@mysql_query($sql_Bon) or die ("creation table Bon impossible -- ".mysql_error());

		$sql_Categorie="CREATE TABLE IF NOT EXISTS `".$nomtableCategorie."`  (
			`idcategorie` int(11) NOT NULL AUTO_INCREMENT,
			`nomcategorie` varchar(50) NOT NULL,
			`categorieParent` int(11) DEFAULT NULL,
			PRIMARY KEY (`idcategorie`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Categorie =@mysql_query($sql_Categorie) or die ("creation table Categorie impossible -- ".mysql_error());

		$sql_Client="CREATE TABLE IF NOT EXISTS `".$nomtableClient."` (
			`idClient` int(11) NOT NULL AUTO_INCREMENT,
			`idClientVitrine` int(11) NOT NULL,
			`prenom` varchar(50) NOT NULL,
			`nom` varchar(20) NOT NULL,
			`adresse` varchar(50) NOT NULL,
			`telephone` varchar(50) NOT NULL,
			`solde` double NOT NULL,
			`activer` int(11) NOT NULL,
			`datetime` varchar(25) NOT NULL,
			`iduser` int(11) NOT NULL,
			`personnel` int(11) NOT NULL,
			`archiver` int(11) NOT NULL,
			`avoir` int(11) NOT NULL,
			`montantAvoir` double NOT NULL,
			`matriculePension` varchar(30) NOT NULL,
			`numCarnet` varchar(30) NOT NULL,
			`taux` double NOT NULL,
			`plafond` int(11) NOT NULL,
			PRIMARY KEY (`idClient`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Client =@mysql_query($sql_Client) or die ("creation table Client impossible -- ".mysql_error());

		$sql_Designation="CREATE TABLE IF NOT EXISTS `".$nomtableDesignation."` (  
			`idDesignation` int(11) NOT NULL AUTO_INCREMENT,
			`idFusion` int(11) DEFAULT NULL,
			`designation` varchar(50) NOT NULL,
			`description` varchar(100) DEFAULT NULL,
			`categorie` varchar(50) NOT NULL,
			`forme` varchar(50) NOT NULL,
			`tableau` varchar(50) NOT NULL,
			`seuil` int(11) NOT NULL,
			`prixSession` double NOT NULL DEFAULT 0,
			`prixPublic` double NOT NULL,
			`codeBarreDesignation` varchar(50) NOT NULL,
			`codeBarreuniteStock` varchar(50) NOT NULL,
			`classe` int(11) NOT NULL,
			`tva` int(11) NOT NULL,
			`image` varchar(50) NOT NULL,
			`archiver` int(1) NOT NULL,
			PRIMARY KEY (`idDesignation`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Designation =@mysql_query($sql_Designation) or die ("creation table Designation impossible -- ".mysql_error());

		$sql_Fournisseur="CREATE TABLE IF NOT EXISTS `".$nomtableFournisseur."` (
			`idFournisseur` int(11) NOT NULL AUTO_INCREMENT,
			`nomFournisseur` varchar(50) NOT NULL,
			`adresseFournisseur` varchar(50) NOT NULL,
			`telephoneFournisseur` varchar(20) NOT NULL,
			`montant` double NOT NULL,
			`banqueFournisseur` varchar(50) NOT NULL,
			`numBanqueFournisseur` varchar(50) NOT NULL,
			PRIMARY KEY (`idFournisseur`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Fournisseur =@mysql_query($sql_Fournisseur) or die ("creation table Fournisseur impossible -- ".mysql_error());

		$sql_Inventaire="CREATE TABLE IF NOT EXISTS `".$nomtableInventaire."` (
			`idInventaire` int(11) NOT NULL AUTO_INCREMENT,
			`idStock` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`quantite` double NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`dateInventaire` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
			`type` int(11) DEFAULT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idInventaire`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Inventaire =@mysql_query($sql_Inventaire) or die ("creation table Inventaire impossible -- ".mysql_error());

		$sql_Journal="CREATE TABLE IF NOT EXISTS `".$nomtableJournal."` (
			`idjournal` int(11) NOT NULL AUTO_INCREMENT,
			`mois` int(11) NOT NULL,
			`annee` int(11) NOT NULL,
			`totalVente` double NOT NULL,
			`totalVersement` double NOT NULL,
			`totalBon` double NOT NULL,
			`totalFrais` double NOT NULL,
			PRIMARY KEY (`idjournal`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Journal =@mysql_query($sql_Journal) or die ("creation table Journal impossible -- ".mysql_error());

		$sql_Mutuelle="CREATE TABLE IF NOT EXISTS `".$nomtableMutuelle."` (
			`idMutuelle` int(11) NOT NULL AUTO_INCREMENT,
			`nomMutuelle` varchar(50) DEFAULT NULL,
			`tauxMutuelle` int(11) NOT NULL,
			`adresseMutuelle` varchar(50) DEFAULT NULL,
			`telephoneMutuelle` varchar(20) DEFAULT NULL,
			PRIMARY KEY (`idMutuelle`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_Mutuelle =@mysql_query($sql_Mutuelle) or die ("creation table Mutuelle impossible -- ".mysql_error());

		$sql_MutuellePagnet="CREATE TABLE IF NOT EXISTS `".$nomtableMutuellePagnet."` (
			`idMutuellePagnet` int(11) NOT NULL AUTO_INCREMENT,
			`datepagej` varchar(15) DEFAULT NULL,
			`type` int(11) DEFAULT NULL,
			`heurePagnet` varchar(10) DEFAULT NULL,
			`totalp` double DEFAULT NULL,
			`remise` double DEFAULT NULL,
			`taux` double DEFAULT NULL,
			`apayerPagnet` double DEFAULT NULL,
			`apayerMutuelle` double DEFAULT NULL,
			`verrouiller` tinyint(1) DEFAULT NULL,
			`idClient` int(11) DEFAULT NULL,
			`idMutuelle` int(11) DEFAULT NULL,
			`adherant` varchar(250) DEFAULT NULL,
			`codeAdherant` varchar(20) DEFAULT NULL,
			`codeBeneficiaire` varchar(20) DEFAULT NULL,
			`numeroRecu` varchar(15) DEFAULT NULL,
			`dateRecu` varchar(15) DEFAULT NULL,
			`iduser` int(11) DEFAULT NULL,
			`image` varchar(50) DEFAULT NULL,
			`restourne` double NOT NULL,
			`versement` double NOT NULL,
			PRIMARY KEY (`idMutuellePagnet`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_MutuellePagnet =@mysql_query($sql_MutuellePagnet) or die ("creation table Mutuelle Pagnet impossible -- ".mysql_error());

		$sql_Ligne="CREATE TABLE IF NOT EXISTS `".$nomtableLigne."` (
			`numligne` int(11) NOT NULL AUTO_INCREMENT,
			`idPagnet` int(11) NOT NULL,
			`idMutuellePagnet` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`designation` varchar(155) NOT NULL,
			`idStock` int(11) NOT NULL,
			`forme` varchar(50) NOT NULL,
			`prixPublic` double NOT NULL,
			`quantite` int(11) NOT NULL,
			`prixtotal` double NOT NULL,
			`classe` int(1) NOT NULL,
			`prixtotalTvaP` double NOT NULL,
			`prixtotalTvaR` double NOT NULL,
			PRIMARY KEY (`numligne`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Ligne =@mysql_query($sql_Ligne) or die ("creation table Ligne impossible -- ".mysql_error());

		$sql_Page="CREATE TABLE IF NOT EXISTS `".$nomtablePage."` (
			`numpage` int(11) NOT NULL AUTO_INCREMENT,
			`datepage` varchar(15) NOT NULL,
			`totalVente` double NOT NULL,
			`totalService` double NOT NULL,
			`totalVersement` double NOT NULL,
			`totalBon` double NOT NULL,
			`totalFrais` double NOT NULL,
			`totalCaisse` double NOT NULL,
			`datetime` varchar(25) NOT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`numpage`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Page =@mysql_query($sql_Page) or die ("creation table Page impossible -- ".mysql_error());

		$sql_Pagnet="CREATE TABLE IF NOT EXISTS `".$nomtablePagnet."` (
			`idPagnet` int(11) NOT NULL AUTO_INCREMENT,
			`datepagej` varchar(15) NOT NULL,
			`type` int(11) NOT NULL,
			`heurePagnet` varchar(10) NOT NULL,
			`totalp` double NOT NULL,
			`remise` double NOT NULL,
			`apayerPagnet` double NOT NULL,
			`restourne` double NOT NULL,
			`versement` double NOT NULL,
			`paiement` varchar(250) DEFAULT NULL,
			`verrouiller` tinyint(1) NOT NULL,
			`idClient` int(11) NOT NULL,
			`iduser` int(11) NOT NULL,
			`apayerPagnetTvaP` double NOT NULL,
			`apayerPagnetTva` double NOT NULL,
			`dejaTerminer` int(11) NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			`taux` double NOT NULL,
			`ticketCopy` int(11) NOT NULL,
			PRIMARY KEY (`idPagnet`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Pagnet =@mysql_query($sql_Pagnet) or die ("creation table Pagnet impossible -- ".mysql_error());

		$sql_Stock="CREATE TABLE IF NOT EXISTS `".$nomtableStock."` (
			`idStock` int(11) NOT NULL AUTO_INCREMENT,
			`idDesignation` int(11) NOT NULL,
			`idBl` int(11) NOT NULL,
			`designation` varchar(50) NOT NULL,
			`quantiteStockinitial` double NOT NULL,
			`forme` varchar(50) NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`totalArticleStock` double NOT NULL,
			`dateStockage` varchar(15) NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`retirer` double NOT NULL,
			`dateFinStock` varchar(15) NOT NULL,
			`dateExpiration` varchar(15) NOT NULL,
			`prixSession` double NOT NULL DEFAULT 0,
			`prixPublic` double NOT NULL,
			`prixDeRevientDuStock` double NOT NULL,
			`pointControleArticle` int(11) NOT NULL DEFAULT 0,
			`pointControleUnite` int(11) NOT NULL DEFAULT 0,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idStock`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Stock =@mysql_query($sql_Stock) or die ("creation table Stock impossible -- ".mysql_error());

		$sql_TotalStock="CREATE TABLE IF NOT EXISTS `".$nomtableTotalStock."` (
			`designation` varchar(50) NOT NULL,
			`quantiteEnStocke` double NOT NULL,
			PRIMARY KEY (`designation`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_TotalStock =@mysql_query($sql_TotalStock) or die ("creation table Total Stock impossible -- ".mysql_error());

		$sql_Versement="CREATE TABLE IF NOT EXISTS `".$nomtableVersement."` (
			`idVersement` int(11) NOT NULL AUTO_INCREMENT,
			`idClient` int(11) NOT NULL,
			`idFournisseur` int(11) NOT NULL,
			`idMutuelle` int(11) NOT NULL,
			`montant` double NOT NULL,
			`paiement` varchar(250) DEFAULT NULL,
			`dateVersement` varchar(15) NOT NULL,
			`heureVersement` varchar(10) NOT NULL,
			`iduser` int(11) NOT NULL,
			`montantAvoir` double NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			PRIMARY KEY (`idVersement`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Versement =@mysql_query($sql_Versement) or die ("creation table Versement impossible -- ".mysql_error());

	}
	else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

		$sql_Bl="CREATE TABLE IF NOT EXISTS `".$nomtableBl."` (
			`idBl` int(11) NOT NULL AUTO_INCREMENT,
			`idFournisseur` int(11) NOT NULL,
			`idVoyage` int(11) NOT NULL,
			`numeroBl` varchar(30) NOT NULL,
			`montantDevise` double NOT NULL,
			`devise` varchar(20) NOT NULL,
			`valeurDevise` double NOT NULL,
			`valeurConversion` double NOT NULL,
			`dateBl` varchar(20) NOT NULL,
			`heureBl` varchar(10) NOT NULL,
			`montantBl` double NOT NULL,
			`iduser` int(11) NOT NULL,
			`description` varchar(250) DEFAULT NULL,
			`image` varchar(50) DEFAULT NULL,
			`dateEcheance` varchar(20) NOT NULL,
			PRIMARY KEY (`idBl`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Bl =@mysql_query($sql_Bl) or die ("creation table BL impossible -- ".mysql_error());

		$sql_Bon="CREATE TABLE IF NOT EXISTS `".$nomtableBon."` (
			`idBon` int(11) NOT NULL AUTO_INCREMENT,
			`idClient` int(11) NOT NULL,
			`montant` double NOT NULL,
			`date` varchar(15) NOT NULL,
			`heureBon` varchar(10) NOT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idBon`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Bon =@mysql_query($sql_Bon) or die ("creation table Bon impossible -- ".mysql_error());

		$sql_Categorie="CREATE TABLE IF NOT EXISTS `".$nomtableCategorie."`  (
			`idcategorie` int(11) NOT NULL AUTO_INCREMENT,
			`nomcategorie` varchar(50) NOT NULL,
			`categorieParent` int(11) DEFAULT NULL,
			PRIMARY KEY (`idcategorie`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Categorie =@mysql_query($sql_Categorie) or die ("creation table Categorie impossible -- ".mysql_error());

		$sql_Client="CREATE TABLE IF NOT EXISTS `".$nomtableClient."` (
			`idClient` int(11) NOT NULL AUTO_INCREMENT,
			`idClientVitrine` int(11) NOT NULL,
			`prenom` varchar(50) NOT NULL,
			`nom` varchar(20) NOT NULL,
			`adresse` varchar(50) NOT NULL,
			`telephone` varchar(50) NOT NULL,
			`solde` double NOT NULL,
			`activer` int(11) NOT NULL,
			`datetime` varchar(25) NOT NULL,
			`iduser` int(11) NOT NULL,
			`personnel` int(11) NOT NULL,
			`archiver` int(11) NOT NULL,
			`avoir` int(11) NOT NULL,
			`montantAvoir` double NOT NULL,
			`matriculePension` varchar(30) NOT NULL,
			`numCarnet` varchar(30) NOT NULL,
			`taux` double NOT NULL,
			`plafond` int(11) NOT NULL,
			PRIMARY KEY (`idClient`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Client =@mysql_query($sql_Client) or die ("creation table Client impossible -- ".mysql_error());

		$sql_Designation="CREATE TABLE IF NOT EXISTS `".$nomtableDesignation."` (  
			`idDesignation` int(11) NOT NULL AUTO_INCREMENT,
			`idFusion` int(11) DEFAULT NULL,
			`designation` varchar(50) NOT NULL,
			`description` varchar(100) DEFAULT NULL,
			`categorie` varchar(50) NOT NULL,
			`uniteStock` varchar(50) NOT NULL,
			`uniteDetails` varchar(50) NOT NULL,
			`nbreArticleUniteStock` double NOT NULL,
			`prix` double NOT NULL,
			`seuil` int(11) NOT NULL,
			`prixuniteStock` double NOT NULL,
			`prixcommercant` double NOT NULL,
			`prixachat` double NOT NULL,
			`codeBarreDesignation` varchar(50) NOT NULL,
			`codeBarreuniteStock` varchar(50) NOT NULL,
			`classe` int(11) NOT NULL,
			`tva` int(1) NOT NULL,
			`image` varchar(50) NOT NULL,
			`archiver` int(1) NOT NULL,
			PRIMARY KEY (`idDesignation`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Designation =@mysql_query($sql_Designation) or die ("creation table Designation impossible -- ".mysql_error());

		$sql_Entrepot="CREATE TABLE IF NOT EXISTS `".$nomtableEntrepot."` (
			`idEntrepot` int(11) NOT NULL AUTO_INCREMENT,
			`nomEntrepot` varchar(50) NOT NULL,
			`adresseEntrepot` varchar(100) DEFAULT NULL,
			PRIMARY KEY (`idEntrepot`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Entrepot =@mysql_query($sql_Entrepot) or die ("creation table Entrepot impossible -- ".mysql_error());

		$sql_EntrepotStock="CREATE TABLE IF NOT EXISTS `".$nomtableEntrepotStock."` (
			`idEntrepotStock` int(11) NOT NULL AUTO_INCREMENT,
			`idStock` int(11) NOT NULL,
			`idEntrepot` int(11) NOT NULL,
			`idTransfert` int(11) NOT NULL,
			`idEntrepotTransfert` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`designation` varchar(50) NOT NULL,
			`quantiteStockinitial` double NOT NULL,
			`uniteStock` varchar(50) NOT NULL,
			`uniteDetails` varchar(50) NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`totalArticleStock` double NOT NULL,
			`dateStockage` varchar(15) NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`retirer` double NOT NULL,
			`dateFinStock` varchar(15) NOT NULL,
			`dateExpiration` varchar(15) NOT NULL,
			`prixuniteStock` double NOT NULL,
			`prixunitaire` double NOT NULL,
			`prixDeRevientDuStock` double NOT NULL,
			`pointControleArticle` int(11) NOT NULL DEFAULT 0,
			`pointControleUnite` int(11) NOT NULL DEFAULT 0,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idEntrepotStock`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_EntrepotStock =@mysql_query($sql_EntrepotStock) or die ("creation table Entrepot Stock impossible -- ".mysql_error());

		$sql_EntrepotTransfert="CREATE TABLE IF NOT EXISTS `".$nomtableEntrepotTransfert."` (
			`idEntrepotTransfert` int(11) NOT NULL AUTO_INCREMENT,
			`idEntrepot` int(11) NOT NULL,
			`idDesignation` int(11) DEFAULT NULL,
			`designation` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
			`quantite` double DEFAULT NULL,
			`quantiteInitiale` double DEFAULT NULL,
			`quantiteFinale` double DEFAULT NULL,
			`dateTransfert` varchar(15) DEFAULT NULL,
			`etat1` tinyint(1) DEFAULT NULL,
			`etat2` tinyint(1) DEFAULT NULL,
			`iduser` int(11) DEFAULT NULL,
			PRIMARY KEY (`idEntrepotTransfert`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_EntrepotTransfert =@mysql_query($sql_EntrepotTransfert) or die ("creation table Entrepot Transfert impossible -- ".mysql_error());

		$sql_Facture="CREATE TABLE IF NOT EXISTS `".$nomtableFacture."` (
			`idFacture` int(11) NOT NULL AUTO_INCREMENT,
			`idPagnet` int(11) NOT NULL,
			`prenom` varchar(50) DEFAULT NULL,
			`nom` varchar(20) DEFAULT NULL,
			`adresse` varchar(50) DEFAULT NULL,
			`telephone` varchar(50) DEFAULT NULL,
			`dateFacture` varchar(10) DEFAULT NULL,
			`heureFacture` varchar(10) DEFAULT NULL,
			`iduser` int(11) DEFAULT NULL,
			PRIMARY KEY (`idFacture`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_Facture =@mysql_query($sql_Facture) or die ("creation table Fature impossible -- ".mysql_error());

		$sql_Fournisseur="CREATE TABLE IF NOT EXISTS `".$nomtableFournisseur."` (
			`idFournisseur` int(11) NOT NULL AUTO_INCREMENT,
			`nomFournisseur` varchar(50) NOT NULL,
			`adresseFournisseur` varchar(50) NOT NULL,
			`telephoneFournisseur` varchar(20) NOT NULL,
			`montant` double NOT NULL,
			`banqueFournisseur` varchar(50) NOT NULL,
			`numBanqueFournisseur` varchar(50) NOT NULL,
			PRIMARY KEY (`idFournisseur`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Fournisseur =@mysql_query($sql_Fournisseur) or die ("creation table Fournisseur impossible -- ".mysql_error());

		$sql_Frais="CREATE TABLE IF NOT EXISTS `".$nomtableFrais."` (
			`idFrais` int(11) NOT NULL AUTO_INCREMENT,
			`idVoyage` int(11) NOT NULL,
			`frais` varchar(50) DEFAULT NULL,
			`montantDevise` double DEFAULT NULL,
			`devise` varchar(20) DEFAULT NULL,
			`valeurDevise` double DEFAULT NULL,
			`valeurConversion` double NOT NULL,
			`montantFrais` double DEFAULT NULL,
			`dateFrais` varchar(20) DEFAULT NULL,
			`heureFrais` varchar(10) DEFAULT NULL,
			`idCompte` int(11) NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			PRIMARY KEY (`idFrais`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_Frais =@mysql_query($sql_Frais) or die ("creation table Frais impossible -- ".mysql_error());

		$sql_Inventaire="CREATE TABLE IF NOT EXISTS `".$nomtableInventaire."` (
			`idInventaire` int(11) NOT NULL AUTO_INCREMENT,
			`idStock` int(11) NOT NULL,
			`idEntrepotStock` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`quantite` double NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`dateInventaire` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
			`type` int(11) DEFAULT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idInventaire`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Inventaire =@mysql_query($sql_Inventaire) or die ("creation table Inventaire impossible -- ".mysql_error());

		$sql_Journal="CREATE TABLE IF NOT EXISTS `".$nomtableJournal."` (
			`idjournal` int(11) NOT NULL AUTO_INCREMENT,
			`mois` int(11) NOT NULL,
			`annee` int(11) NOT NULL,
			`totalVente` double NOT NULL,
			`totalVersement` double NOT NULL,
			`totalBon` double NOT NULL,
			`totalFrais` double NOT NULL,
			PRIMARY KEY (`idjournal`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Journal =@mysql_query($sql_Journal) or die ("creation table Journal impossible -- ".mysql_error());

		$sql_Ligne="CREATE TABLE IF NOT EXISTS `".$nomtableLigne."` (
			`numligne` int(11) NOT NULL AUTO_INCREMENT,
			`idPagnet` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`idEntrepot` int(11) NOT NULL,
			`designation` varchar(155) NOT NULL,
			`idStock` int(11) NOT NULL,
			`unitevente` varchar(155) NOT NULL,
			`prixunitevente` double NOT NULL,
			`quantite` int(11) NOT NULL,
			`prixtotal` double NOT NULL,
			`classe` int(1) NOT NULL,
			`prixtotalTvaP` double NOT NULL,
			`prixtotalTvaR` double NOT NULL,
			PRIMARY KEY (`numligne`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Ligne =@mysql_query($sql_Ligne) or die ("creation table Ligne impossible -- ".mysql_error());

		$sql_Page="CREATE TABLE IF NOT EXISTS `".$nomtablePage."` (
			`numpage` int(11) NOT NULL AUTO_INCREMENT,
			`datepage` varchar(15) NOT NULL,
			`totalVente` double NOT NULL,
			`totalService` double NOT NULL,
			`totalVersement` double NOT NULL,
			`totalBon` double NOT NULL,
			`totalFrais` double NOT NULL,
			`totalCaisse` double NOT NULL,
			`datetime` varchar(25) NOT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`numpage`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Page =@mysql_query($sql_Page) or die ("creation table Page impossible -- ".mysql_error());

		$sql_Pagnet="CREATE TABLE IF NOT EXISTS `".$nomtablePagnet."` (
			`idPagnet` int(11) NOT NULL AUTO_INCREMENT,
			`datepagej` varchar(15) NOT NULL,
			`type` int(11) NOT NULL,
			`heurePagnet` varchar(10) NOT NULL,
			`totalp` double NOT NULL,
			`remise` double NOT NULL,
			`apayerPagnet` double NOT NULL,
			`restourne` double NOT NULL,
			`versement` double NOT NULL,
			`paiement` varchar(250) DEFAULT NULL,
			`livreur` varchar(200) DEFAULT NULL,
			`verrouiller` tinyint(1) NOT NULL,
			`idClient` int(11) NOT NULL,
			`dateecheance` varchar(15) DEFAULT NULL,
			`iduser` int(11) NOT NULL,
			`idCompte` int(11) NOT NULL,
			`avance` double NOT NULL,
			`apayerPagnetTvaP` double NOT NULL,
			`apayerPagnetTvaR` double NOT NULL,
			`dejaTerminer` int(11) NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			`taux` double NOT NULL,
			`ticketCopy` int(11) NOT NULL,
			`nomClient` varchar(100) NOT NULL,
			`validerProforma` int(11) NOT NULL,
			`terminerProforma` int(11) NOT NULL,
			`nbColis` int(11) NOT NULL,
			`numContainer` varchar(30) NOT NULL,
			PRIMARY KEY (`idPagnet`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Pagnet =@mysql_query($sql_Pagnet) or die ("creation table Pagnet impossible -- ".mysql_error());

		$sql_Stock="CREATE TABLE IF NOT EXISTS `".$nomtableStock."` (
			`idStock` int(11) NOT NULL AUTO_INCREMENT,
			`idDesignation` int(11) NOT NULL,
			`idBl` int(11) NOT NULL,
			`designation` varchar(50) NOT NULL,
			`quantiteStockinitial` double NOT NULL,
			`uniteStock` varchar(50) NOT NULL,
			`uniteDetails` varchar(50) NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`totalArticleStock` double NOT NULL,
			`dateStockage` varchar(15) NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`quantiteStockTemp` double NOT NULL,
			`retirer` double NOT NULL,
			`dateFinStock` varchar(15) NOT NULL,
			`dateExpiration` varchar(15) NOT NULL,
			`prixuniteStock` double NOT NULL,
			`prixunitaire` double NOT NULL,
			`prixachat` double NOT NULL,
			`prixDeRevientDuStock` double NOT NULL,
			`pointControleArticle` int(11) NOT NULL DEFAULT 0,
			`pointControleUnite` int(11) NOT NULL DEFAULT 0,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idStock`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Stock =@mysql_query($sql_Stock) or die ("creation table Stock impossible -- ".mysql_error());

		$sql_TotalStock="CREATE TABLE IF NOT EXISTS `".$nomtableTotalStock."` (
			`designation` varchar(50) NOT NULL,
			`quantiteEnStocke` double NOT NULL,
			PRIMARY KEY (`designation`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_TotalStock =@mysql_query($sql_TotalStock) or die ("creation table Total Stock impossible -- ".mysql_error());

		$sql_Versement="CREATE TABLE IF NOT EXISTS `".$nomtableVersement."` (
			`idVersement` int(11) NOT NULL AUTO_INCREMENT,
			`idClient` int(11) NOT NULL,
			`idFournisseur` int(11) NOT NULL,
			`montant` double NOT NULL,
			`paiement` varchar(250) DEFAULT NULL,
			`dateVersement` varchar(15) NOT NULL,
			`heureVersement` varchar(10) NOT NULL,
			`iduser` int(11) NOT NULL,
			`idCompte` int(11) NOT NULL,
			`idPagnet` int(11) NOT NULL,
			`montantAvoir` double NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			PRIMARY KEY (`idVersement`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Versement =@mysql_query($sql_Versement) or die ("creation table Versement impossible -- ".mysql_error());

		$sql_Voyage="CREATE TABLE IF NOT EXISTS `".$nomtableVoyage."` (
			`idVoyage` int(11) NOT NULL AUTO_INCREMENT,
			`destination` varchar(50) DEFAULT NULL,
			`motif` varchar(50) DEFAULT NULL,
			`dateVoyage` varchar(20) DEFAULT NULL,
			`tauxRevient` double DEFAULT NULL,
			`tauxVente` double DEFAULT NULL,
			PRIMARY KEY (`idVoyage`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_Voyage =@mysql_query($sql_Voyage) or die ("creation table Voyage impossible -- ".mysql_error());
		
	}
	else{

		$sql_Bl="CREATE TABLE IF NOT EXISTS `".$nomtableBl."` (
			`idBl` int(11) NOT NULL AUTO_INCREMENT,
			`idFournisseur` int(11) NOT NULL,
			`numeroBl` varchar(30) DEFAULT NULL,
			`dateBl` varchar(20) DEFAULT NULL,
			`heureBl` varchar(10) DEFAULT NULL,
			`montantBl` double DEFAULT NULL,
			`iduser` int(11) NOT NULL,
			`description` varchar(250) DEFAULT NULL,
			`image` varchar(50) DEFAULT NULL,
			`dateEcheance` varchar(20) NOT NULL,
			PRIMARY KEY (`idBl`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_Bl =@mysql_query($sql_Bl) or die ("creation table BL impossible -- ".mysql_error());

		$sql_Bon="CREATE TABLE IF NOT EXISTS `".$nomtableBon."` (
			`idBon` int(11) NOT NULL AUTO_INCREMENT,
			`idClient` int(11) NOT NULL,
			`montant` double NOT NULL,
			`date` varchar(15) NOT NULL,
			`heureBon` varchar(10) NOT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idBon`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Bon =@mysql_query($sql_Bon) or die ("creation table Bon impossible -- ".mysql_error());

		$sql_Categorie="CREATE TABLE IF NOT EXISTS `".$nomtableCategorie."`  (
			`idcategorie` int(11) NOT NULL AUTO_INCREMENT,
			`nomcategorie` varchar(50) NOT NULL,
			`categorieParent` int(11) DEFAULT NULL,
			`image` varchar(50) NOT NULL,
			PRIMARY KEY (`idcategorie`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Categorie =@mysql_query($sql_Categorie) or die ("creation table Categorie impossible -- ".mysql_error());

		$sql_Client="CREATE TABLE IF NOT EXISTS `".$nomtableClient."` (
			`idClient` int(11) NOT NULL AUTO_INCREMENT,
			`idClientVitrine` int(11) NOT NULL,
			`prenom` varchar(50) NOT NULL,
			`nom` varchar(20) NOT NULL,
			`adresse` varchar(50) NOT NULL,
			`telephone` varchar(50) NOT NULL,
			`solde` double NOT NULL,
			`activer` int(11) NOT NULL,
			`datetime` varchar(25) NOT NULL,
			`iduser` int(11) NOT NULL,
			`personnel` int(11) NOT NULL,
			`archiver` int(11) NOT NULL,
			`avoir` int(11) NOT NULL,
			`montantAvoir` double NOT NULL,
			`matriculePension` varchar(30) NOT NULL,
			`numCarnet` varchar(30) NOT NULL,
			`taux` double NOT NULL,
			`plafond` int(11) NOT NULL,
			PRIMARY KEY (`idClient`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Client =@mysql_query($sql_Client) or die ("creation table Client impossible -- ".mysql_error());

		$sql_Designation="CREATE TABLE IF NOT EXISTS `".$nomtableDesignation."` (  
			`idDesignation` int(11) NOT NULL AUTO_INCREMENT,
			`idFusion` int(11) DEFAULT NULL,
			`designation` varchar(50) NOT NULL,
			`description` varchar(100) DEFAULT NULL,
			`categorie` varchar(200) NOT NULL,
			`uniteStock` varchar(50) NOT NULL,
			`uniteDetails` varchar(50) NOT NULL,
			`nbreArticleUniteStock` double NOT NULL,
			`prix` double NOT NULL,
			`seuil` int(11) NOT NULL,
			`prixuniteStock` double NOT NULL,
			`prixcommercant` double NOT NULL,
			`prixachat` double NOT NULL,
			`codeBarreDesignation` varchar(50) NOT NULL,
			`codeBarreuniteStock` varchar(50) NOT NULL,
			`classe` int(11) NOT NULL,
			`tva` int(1) NOT NULL,
			`image` varchar(50) NOT NULL,
			`idUser` int(11) NOT NULL,
			`archiver` int(1) NOT NULL,
			`idUserImage` int(11) NOT NULL,
			PRIMARY KEY (`idDesignation`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Designation =@mysql_query($sql_Designation) or die ("creation table Designation impossible -- ".mysql_error());

		$sql_Fournisseur="CREATE TABLE IF NOT EXISTS `".$nomtableFournisseur."` (
			`idFournisseur` int(11) NOT NULL AUTO_INCREMENT,
			`nomFournisseur` varchar(50) DEFAULT NULL,
			`adresseFournisseur` varchar(50) DEFAULT NULL,
			`telephoneFournisseur` varchar(20) DEFAULT NULL,
			`banqueFournisseur` varchar(50) NOT NULL,
			`numBanqueFournisseur` varchar(50) NOT NULL,
			PRIMARY KEY (`idFournisseur`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;";
		$res_Fournisseur =@mysql_query($sql_Fournisseur) or die ("creation table Fournisseur impossible -- ".mysql_error());

		$sql_Inventaire="CREATE TABLE IF NOT EXISTS `".$nomtableInventaire."` (
			`idInventaire` int(11) NOT NULL AUTO_INCREMENT,
			`idStock` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`quantite` double NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`dateInventaire` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
			`type` int(11) DEFAULT NULL,
			PRIMARY KEY (`idInventaire`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Inventaire =@mysql_query($sql_Inventaire) or die ("creation table Inventaire impossible -- ".mysql_error());

		$sql_Journal="CREATE TABLE IF NOT EXISTS `".$nomtableJournal."` (
			`idjournal` int(11) NOT NULL AUTO_INCREMENT,
			`mois` int(11) NOT NULL,
			`annee` int(11) NOT NULL,
			`totalVente` double NOT NULL,
			`totalVersement` double NOT NULL,
			`totalBon` double NOT NULL,
			`totalFrais` double NOT NULL,
			PRIMARY KEY (`idjournal`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Journal =@mysql_query($sql_Journal) or die ("creation table Journal impossible -- ".mysql_error());

		$sql_Ligne="CREATE TABLE IF NOT EXISTS `".$nomtableLigne."` (
			`numligne` int(11) NOT NULL AUTO_INCREMENT,
			`idPagnet` int(11) NOT NULL,
			`idDesignation` int(11) NOT NULL,
			`designation` varchar(155) NOT NULL,
			`idStock` int(11) NOT NULL,
			`unitevente` varchar(155) NOT NULL,
			`prixunitevente` double NOT NULL,
			`quantite` int(11) NOT NULL,
			`prixtotal` double NOT NULL,
			`classe` int(1) NOT NULL,
			`prixtotalTvaP` double NOT NULL,
			`prixtotalTvaR` double NOT NULL,
			PRIMARY KEY (`numligne`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Ligne =@mysql_query($sql_Ligne) or die ("creation table Ligne impossible -- ".mysql_error());

		$sql_Page="CREATE TABLE IF NOT EXISTS `".$nomtablePage."` (
			`numpage` int(11) NOT NULL AUTO_INCREMENT,
			`datepage` varchar(15) NOT NULL,
			`totalVente` double NOT NULL,
			`totalService` double NOT NULL,
			`totalVersement` double NOT NULL,
			`totalBon` double NOT NULL,
			`totalFrais` double NOT NULL,
			`totalCaisse` double NOT NULL,
			`datetime` varchar(25) NOT NULL,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`numpage`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Page =@mysql_query($sql_Page) or die ("creation table Page impossible -- ".mysql_error());

		$sql_Pagnet="CREATE TABLE IF NOT EXISTS `".$nomtablePagnet."` (
			`idPagnet` int(11) NOT NULL AUTO_INCREMENT,
			`datepagej` varchar(15) NOT NULL,
			`type` int(11) NOT NULL,
			`heurePagnet` varchar(10) NOT NULL,
			`totalp` double NOT NULL,
			`remise` double NOT NULL,
			`apayerPagnet` double NOT NULL,
			`restourne` double NOT NULL,
			`versement` double NOT NULL,
			`paiement` varchar(250) DEFAULT NULL,
			`verrouiller` tinyint(1) NOT NULL,
			`idClient` int(11) NOT NULL,
			`dateecheance` varchar(15) DEFAULT NULL,
			`idVitrine` int(11) NOT NULL,
			`iduser` int(11) NOT NULL,
			`idCompte` int(11) NOT NULL,
			`avance` double NOT NULL,
			`apayerPagnetTvaP` double NOT NULL,
			`apayerPagnetTvaR` double NOT NULL,
			`dejaTerminer` int(11) NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			`taux` double NOT NULL,
			`ticketCopy` int(11) NOT NULL,
			`numContainer` varchar(30) NOT NULL,
			PRIMARY KEY (`idPagnet`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Pagnet =@mysql_query($sql_Pagnet) or die ("creation table Pagnet impossible -- ".mysql_error());

		$sql_Stock="CREATE TABLE IF NOT EXISTS `".$nomtableStock."` (
			`idStock` int(11) NOT NULL AUTO_INCREMENT,
			`idDesignation` int(11) NOT NULL,
			`idBl` int(11) NOT NULL,
			`designation` varchar(50) NOT NULL,
			`quantiteStockinitial` double NOT NULL,
			`uniteStock` varchar(50) NOT NULL,
			`uniteDetails` varchar(50) NOT NULL,
			`nbreArticleUniteStock` int(11) NOT NULL,
			`totalArticleStock` double NOT NULL,
			`dateStockage` varchar(15) NOT NULL,
			`quantiteStockCourant` double NOT NULL,
			`quantiteStockTemp` double NOT NULL,
			`retirer` double NOT NULL,
			`dateFinStock` varchar(15) NOT NULL,
			`dateExpiration` varchar(15) NOT NULL,
			`prixuniteStock` double NOT NULL,
			`prixunitaire` double NOT NULL,
			`prixachat` double NOT NULL,
			`prixDeRevientDuStock` double NOT NULL,
			`pointControleArticle` int(11) NOT NULL DEFAULT 0,
			`pointControleUnite` int(11) NOT NULL DEFAULT 0,
			`iduser` int(11) NOT NULL,
			PRIMARY KEY (`idStock`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Stock =@mysql_query($sql_Stock) or die ("creation table Stock impossible -- ".mysql_error());

		$sql_TotalStock="CREATE TABLE IF NOT EXISTS `".$nomtableTotalStock."` (
			`designation` varchar(50) NOT NULL,
			`quantiteEnStocke` double NOT NULL,
			PRIMARY KEY (`designation`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_TotalStock =@mysql_query($sql_TotalStock) or die ("creation table Total Stock impossible -- ".mysql_error());

		$sql_Versement="CREATE TABLE IF NOT EXISTS `".$nomtableVersement."` (
			`idVersement` int(11) NOT NULL AUTO_INCREMENT,
			`idClient` int(11) NOT NULL,
			`idFournisseur` int(11) NOT NULL,
			`montant` double NOT NULL,
			`paiement` varchar(250) DEFAULT NULL,
			`dateVersement` varchar(15) NOT NULL,
			`heureVersement` varchar(10) NOT NULL,
			`iduser` int(11) NOT NULL,
			`idCompte` int(11) NOT NULL,
			`idPagnet` int(11) NOT NULL,
			`montantAvoir` double NOT NULL,
			`image` varchar(50) DEFAULT NULL,
			PRIMARY KEY (`idVersement`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
		$res_Versement =@mysql_query($sql_Versement) or die ("creation table Versement impossible -- ".mysql_error());

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



// $sqlb="select * from `".$nomtableDesignation."` where designation='Vente en especes'";

// $resb=mysql_query($sqlb);

// if(mysql_num_rows($resb)){

// }

// else{

// 	$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie) values ('Vente en especes',1,0,'Espece','0','1','10','Sans')";

// 	$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());

// }

?>

