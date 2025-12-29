<?php 
/*
R�sum� : Ce code permet la cr�ation d'une boutique.
Commentaire : Ce code contient un formulaire r�cup�rant l'ensemble des informations (nom, adresse et type) sur la boutique � cr�er.
Il ins�re ces informations dans la table boutique avec un num�ro de boutique attribu� automatiquement. 
Puis insert une ligne dans la table gerant l'information pour dire que l'utilisateur connect� � cr�er cette boutique et est son administrateur.
De plus pour la gestion de chaque boutique ce code cr�er des tables commen�ant toutes par le nom de la boutique suivi de :
-journal, -pagej, -pagnet, -lignepj, -designation, -stock pour garder les journaux, les pages de journaux, les pagnets, les lignes de pages de journaux, les d�signations et les stocks.
Version : 2.0
Auteur : Ibrahima DIOP,EL hadji mamadou korka
Date de cr�ation : 10/04/2016
Date derni�re modification : 19/04/2016; 15-04-2018
*/


session_start();
if($_SESSION['iduser']){

mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/

$nomB     =@htmlentities($_POST["nomB"]);
$adresseB =@htmlentities($_POST["adresseB"]);
$type     =@htmlentities($_POST["type"]);
$categorie     =@htmlentities($_POST["categorie"]);

$annuler          =@$_POST["annuler"];

/***************/

//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;

	if(!$annuler){
		if(!$nomB){ ?>
			
            
            <!DOCTYPE html>
            <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <script type="text/javascript" src="prixdesignation.js"></script>
                <link rel="stylesheet" type="text/css" href="style.css">
                    <title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
            </head>
            <body onload="process()">
            <header>
               <table width="98%" align="center" border="0"><tr><td>
               <h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="index.php">Accueil</a></li><li><a href="#">Journal de caisse</a></li>
               <li><a href="#">Historique de caisse</a></li><li><a href="#">Gestion Stock</a></li><li><a href="#">Catalogue des Services/Produits</a></li>
               </ul></nav></header>			   
               <section>
			   <article><h3> FORMULAIRE DE CREATION DE BOUTIQUE </h3><div id="corp1"><table width="70%" align="center" border="0">
				<form class="formulaire2" name="formulaire2" method="post" action="creationBoutique.php">
					<tr><td>NOM BOUTIQUE : </td> <td><input type="text" class="inputbasic" id="nomB" name="nomB" size="40" value="" /></td></tr>
					<tr><td>ADRESSE : </td><td> <input type="text" class="inputbasic" id="adresseB" name="adresseB" size="40" value="" /></td></tr>
					<tr><td>TYPE : </td><td> 
							<select name="type" id="type"> <?php 
								$sql10="SELECT * FROM typeBoutique";
								$res10=mysql_query($sql10);
								while($ligne = mysql_fetch_row($res10)) { 
								echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';				
									} ?>
							</select>
					<tr>
					<tr><td>CATEGORIE : </td><td> 
							<select name="categorie" id="categorie"> <?php 
								$sql11="SELECT * FROM categorie";
								$res11=mysql_query($sql11);
								while($ligne2 = mysql_fetch_row($res11)) { 
								echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';				
									} ?>
							</select>
					<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="CREER >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>
					<tr><td colspan="2"><div id="apresEntre"></div></td></tr>
				</form></table></div><br /></article>
			</section>
			</body>
			</html> <?php 
		}
		else{
				$_SESSION['nomB']=$nomB;
			    $_SESSION['adresseB'] =$adresseB;
			    $_SESSION['type']  =$type;
			    $_SESSION['categorie']  =$categorie;
		 	    $_SESSION['dateCB']  =$dateString; 

	 			$sql1="insert into boutique (nomBoutique,adresseBoutique,type,categorie,datecreation) values('".$_SESSION['nomB']."','".$_SESSION['adresseB']."','".$_SESSION['type']."','".$_SESSION['categorie']."','".$dateString."')";

			   	$res1=@mysql_query($sql1) or die ("insertion journal impossible-2".mysql_error());
				
				$sql8="select * from boutique where nomBoutique='".$_SESSION['nomB']."'";
				$res8=mysql_query($sql8);
			    if(mysql_num_rows($res8)){
			   		if($tab8=mysql_fetch_array($res8)){
						    $_SESSION['idBoutique']=$tab8['idBoutique'];
			    			$sql9="UPDATE acces set idBoutique=".$_SESSION['idBoutique']." where idutilisateur=".$_SESSION['iduser'];
			           		$res9=@mysql_query($sql9) or die ("insertion acces impossible");
				 		}
			    }
				
				$sql10="UPDATE utilisateur set creationB=1 where idutilisateur=".$_SESSION['iduser'];
			    $res10=@mysql_query($sql10) or die ("insertion acces impossible");
				
				
				$nomtableJournal=$_SESSION['nomB']."-journal";
				$nomtablePage=$_SESSION['nomB']."-pagej";
				$nomtablePagnet=$_SESSION['nomB']."-pagnet";
				$nomtableLigne=$_SESSION['nomB']."-lignepj";
				$nomtableDesignation=$_SESSION['nomB']."-designation";
				$nomtableStock=$_SESSION['nomB']."-stock";
				$nomtableTotalStock=$_SESSION['nomB']."-totalstock";
				$nomtableBon=$_SESSION['nomB']."-bon";
				$nomtableClient=$_SESSION['nomB']."-client";
				$nomtableVersement=$_SESSION['nomB']."-versement";
				 
$sql1="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableJournal."` (`idjournal` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`mois` INT NOT NULL ,`annee` INT NOT NULL ,`tentrees` REAL NOT NULL ,`tsorties` REAL NOT NULL) ENGINE = MYISAM ;";

$sql2="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtablePage."` (`numpage` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`datepage` VARCHAR(100) NOT NULL ,`tentreesp` REAL NOT NULL ,`tsortiesp` REAL NOT NULL) ENGINE = MYISAM ;";
						 
$sql3="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtablePagnet."` (`idpagnet` INT NOT NULL AUTO_INCREMENT ,`mois` INT NOT NULL ,`annee` INT NOT NULL ,`tentrees` REAL NOT NULL ,`tsorties` REAL NOT NULL, `verrouiller` BOOLEAN NOT NULL, PRIMARY KEY (`idpagnet`)) ENGINE = MYISAM;";
						 
$sql4="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableLigne."` (`numligne` INT NOT NULL AUTO_INCREMENT,`datepage` VARCHAR(100) NOT NULL,`designation` VARCHAR(155) NOT NULL,`unitevente` REAL NOT NULL,`prixunitevente` REAL NOT NULL,`quantite` INT NOT NULL,`remise` REAL NOT NULL,`prixtotal` REAL NOT NULL,`typeligne` VARCHAR(15) NOT NULL, PRIMARY KEY (`numligne`)) ENGINE = MYISAM";
						 
$sql5="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableDesignation."` (`idDesignation` INT NOT NULL AUTO_INCREMENT,`designation` VARCHAR(155) NOT NULL,`uniteStock` VARCHAR(155) NOT NULL,`nbreArticleUniteStock` REAL NOT NULL,`prix` REAL NOT NULL,`prixuniteStock` REAL NOT NULL,`classe` INT NOT NULL, PRIMARY KEY (`idDesignation`)) ENGINE = MYISAM"; 
			 
$sql6="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableStock."` (`idStock` INT NOT NULL AUTO_INCREMENT ,`idDesignation` INT NOT NULL,`designation` VARCHAR(155) NOT NULL,`quantiteStockinitial` REAL NOT NULL ,`uniteStock` VARCHAR(100) NOT NULL ,`nbreArticleUniteStock` INT NOT NULL ,`totalArticleStock` REAL NOT NULL ,`dateStockage` VARCHAR(50) NOT NULL ,`quantiteStockCourant` REAL NOT NULL ,`dateFinStock` VARCHAR(50) NOT NULL ,`dateExpiration` VARCHAR(50) NOT NULL , `prixuniteStock` DOUBLE NOT NULL , `prixunitaire` DOUBLE NOT NULL, PRIMARY KEY (`idStock`)) ENGINE = MYISAM ;";		 		

$sql7="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableTotalStock."` (`designation` VARCHAR(155) NOT NULL,`quantiteEnStocke` REAL NOT NULL ,`dateExpiration` VARCHAR(50) NOT NULL, PRIMARY KEY (`designation`)) ENGINE = MYISAM ;";	

$sql13="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableClient."` (`idClient` INT NOT NULL AUTO_INCREMENT ,`prenom` VARCHAR(50) NOT NULL,`nom` VARCHAR(20) NOT NULL ,`adresse` VARCHAR(50) NOT NULL,`telephone` VARCHAR(50) NOT NULL,`type` VARCHAR(50) NOT NULL, `activer` BOOLEAN NOT NULL, PRIMARY KEY (`idClient`)) ENGINE = MYISAM ;";

$sql12="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableBon."` (`idBon` INT NOT NULL AUTO_INCREMENT,`idClient` INT NOT NULL,`montant` DOUBLE NOT NULL ,`date` VARCHAR(50) NOT NULL,`echeance` VARCHAR(50) NOT NULL, PRIMARY KEY (`idBon`)) ENGINE = MYISAM ;";	
				
$sql14="CREATE TABLE IF NOT EXISTS `bdjournalcaisse`.`".$nomtableVersement."` (`idVersement` INT NOT NULL AUTO_INCREMENT,`idClient` INT NOT NULL,`montant` DOUBLE NOT NULL,`dateVersement` VARCHAR(100) NOT NULL, PRIMARY KEY (`idVersement`)) ENGINE = MYISAM ;";	

$res1 =@mysql_query($sql1) or die ("creation table journal impossible");
$res2 =@mysql_query($sql2) or die ("creation table page impossible");

$res4 =@mysql_query($sql4) or die ("creation table ligne impossible");
$res5 =@mysql_query($sql5) or die ("creation table designation impossible");
$res6 =@mysql_query($sql6) or die ("creation table stock impossible");	 	 	 	 
$res7 =@mysql_query($sql7) or die ("creation table totalStock impossible");	
$res12=@mysql_query($sql12) or die ("creation table bon impossible");	
$res13=@mysql_query($sql13) or die ("creation table client impossible");	
$res14=@mysql_query($sql14) or die ("creation table versement impossible");	
$res3 =@mysql_query($sql3) or die ("creation table pagnet impossible");

/*
$sql8="select * from boutique where nomBoutique='".$_SESSION['nomB']."'";
$res8=mysql_query($sql8);
			    if(mysql_num_rows($res8)){
			   		if($tab8=mysql_fetch_array($res8)){
						    $_SESSION['idBoutique']=$tab8['idBoutique'];
			    			$sql9="UPDATE acces set idBoutique='".$_SESSION['idBoutique']."' where idutilisateur=".$_SESSION['iduser'];
			           		$res9=@mysql_query($sql9) or die ("insertion acces impossible");
				 		}
			    }
*/			    
				echo'<!DOCTYPE html>';
			    echo'<html>';
			    echo'<head>';
			    echo'<script language="JavaScript">document.location="insertionLigne.php"</script>';
			    echo'</head>';
			    echo'</html>';
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
	echo'<script language="JavaScript">document.location="index.php"</script>';
	echo'</head>';
	echo'</html>';
}
?>