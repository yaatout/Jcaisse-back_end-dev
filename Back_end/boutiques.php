<?php
/*
R�sum� :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP;
Date de cr�ation : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduserBack'])
	header('Location:index.php');
require('connection.php');
require('connectionPDO.php');
require('connectionVitrine.php');
require('declarationVariables.php');
//	$nomBoutique			=htmlentities('a&é shop');
// $nomB       			=html_entity_decode($nomBoutique);
// $nomB2       			=htmlspecialchars('a&é shop');
//    $nomB3		=mysql_real_escape_string('a&é shop');
//var_dump($nomBoutique);
//    echo '<br/>';
// var_dump($nomB);
// echo '<br/>';
// var_dump($nomB2);
//    echo '<br/>';
// var_dump($nomB3);
//    echo '<br/>*******************<br/>';
// $nomBoutique			=htmlentities('yomblÃ© & pir');
// $nomB       			=html_entity_decode('yomblÃ© & pir');
// $nomB2       			=htmlspecialchars('yomblÃ© & pir');
// $nomB3		=mysql_real_escape_string('yomblÃ© & pir');
//var_dump($nomBoutique);
//    echo '<br/>';
// var_dump($nomB);
// echo '<br/>';
// var_dump($nomB2);
//    echo '<br/>';
// var_dump($nomB3);
//    echo '<br/>*******************<br/>';
//$nomBoutique			= mysql_real_escape_string('yomblé & pir');
// $nomB       			=mysql_real_escape_string('yomblÃ© & pir');
// $nomB2       			=mysql_real_escape_string(htmlspecialchars('yomblÃ© & pir'));
// $nomB3		=mysql_real_escape_string('yombl&atilde;&copy; &amp; pir');
//var_dump($nomBoutique);
//    echo '<br/>';
// var_dump($nomB);
// echo '<br/>';
// var_dump($nomB2);
//    echo '<br/>';
// var_dump($nomB3);
//    echo '<br/>*******************<br/>';
//
/*$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";*/
/**********************/
$idStock         =@$_POST["idStock"];
$designation      =@htmlentities($_POST["designation"]);
$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
//$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];
$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/
$idStock2       =@$_GET["idStock"];
    $messageSupBoutiqueOK='';
    $messageSupBoutiqueERROR='';
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$sql17="SELECT * FROM `aaa-boutique`";
$res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
while ($b = mysql_fetch_array($res17)) {
	// var_dump($b);
	/*
	$sql1="ALTER TABLE `".$nomBoutiquePanier."` ADD IF NOT EXISTS `apayerPagnetTvaP` double NOT NULL;";
	$sql2="ALTER TABLE `".$nomBoutiquePanier."` ADD IF NOT EXISTS `apayerPagnetTvaR` double NOT NULL;";
	$sql3="ALTER TABLE `".$nomBoutiqueLigne."` ADD IF NOT EXISTS `prixtotalTvaP` double NOT NULL;";
	$sql4="ALTER TABLE `".$nomBoutiqueLigne."` ADD IF NOT EXISTS `prixtotalTvaR` double NOT NULL;";
	$res1 =@mysql_query($sql1) or die ("creation table compte impossible ".mysql_error());
	$res2 =@mysql_query($sql2) or die ("creation table mouvement impossible ".mysql_error());
	$res3 =@mysql_query($sql3) or die ("creation table operation impossible ".mysql_error());
	$res4 =@mysql_query($sql4) or die ("creation table operation impossible ".mysql_error());
	$tablePanier=$b['nomBoutique']."-pagnet";
	$tableVersement=$b['nomBoutique']."-versement";
	$tableBl=$b['nomBoutique']."-bl";
	$sqlA="ALTER TABLE `".$tablePanier."` MODIFY COLUMN paiement varchar(250);";
	$sqlB="ALTER TABLE `".$tableVersement."` MODIFY COLUMN paiement varchar(250);";
	$sqlC="ALTER TABLE `".$tableBl."` MODIFY COLUMN description varchar(250);";
	$resA1 =@mysql_query($sqlA) or die ("modification table versement 1 impossible ".mysql_error());
	$resB1 =@mysql_query($sqlB) or die ("modification table versement 2 impossible ".mysql_error());
	$resC1 =@mysql_query($sqlC) or die ("modification table versement 3 impossible ".mysql_error());
		*/
}
//OK PDO
if (isset($_POST['btnTerminerTest'])) {
	$idBoutique=$_POST['enTest'];
	$activer=1;
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET enTest=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique ))
                     or die(print_r($req3->errorInfo()));

}elseif (isset($_POST['btnDesactiverTest'])) {
	$idBoutique=$_POST['apresTest'];
	$activer=0;
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET enTest=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique ))
                     or die(print_r($req3->errorInfo()));
}
//OK PDO
if (isset($_POST['btnActiverVitrine'])) {
	
	$idBoutique=$_POST['iAVB'];
	$nomBoutique=$_POST['nBAV'];
	$type=$_POST['tyBAV'];
	$categorie=$_POST['catBAV'];
	$adresse=$_POST['adBAV'];
	$activer=1;
	$vitrine=1;
   // var_dump($_POST['tab']);
	$sql3="UPDATE `aaa-boutique` set  vitrine='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
    $req2= $bddV->prepare("INSERT INTO
                        boutique(idBoutique,nomBoutique,type,categorie,adresse,vitrine)
                        VALUES (:idBoutique, :nomBoutique, :type,:categorie,:adresse,:vitrine)
                        ") ;
    $req2->execute(array(
                         'idBoutique' => $idBoutique,
                         'nomBoutique' => $nomBoutique,
                         'type' => $type,
                         'categorie' => $categorie,
                         'adresse' => $adresse,
                         'vitrine' => $vitrine
                    ));
    $req2->closeCursor();
     $req5 = $bddV->prepare('UPDATE boutique SET vitrine = :vitrine WHERE idBoutique = :idBoutique');
     $req5->execute(array(
                          'vitrine' => $vitrine,
                          'idBoutique' => $idBoutique
                          )) or die(print_r($req5->errorInfo()));
     $req5->closeCursor();
    $nomtableDesignation=$nomBoutique."-designation";
    try {
           $req1 =$bddV->exec("CREATE TABLE IF NOT EXISTS   `".$nomtableDesignation."` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                          `idDesignation` int(11) NOT NULL,
                          `idBoutique` int(11) NOT NULL,
                          `designation` varchar(50) NOT NULL,
                          `designationJcaisse` varchar(50) NOT NULL,
                          `description` varchar(100) DEFAULT NULL,
                          `categorie` varchar(50) NOT NULL,
                          `categorieVitrine` varchar(50) NOT NULL,
                          `uniteStock` varchar(50) NOT NULL,
                          `uniteDetails` varchar(50) NOT NULL,
                          `nbreArticleUniteStock` double NOT NULL,
                          `prix` double NOT NULL,
                          `seuil` int(11) NOT NULL,
                          `prixuniteStock` double NOT NULL,
                          `codeBarreDesignation` varchar(50) NOT NULL,
                          `codeBarreuniteStock` varchar(50) NOT NULL,
                          `classe` int(11) NOT NULL,
                          `idFusion` int(11) DEFAULT NULL,
                          `image` varchar(30) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM   DEFAULT CHARSET=utf8");
        }
        catch(PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
}elseif (isset($_POST['btnDesactiverVitrine'])) {
	$idBoutique=$_POST['idboutique'];
	$nomBoutique=$_POST['nomBoutique'];
    $nomtableDesignation=$nomBoutique."-designation";
	$activer=0;
	$vitrine=0;
	$sql3="UPDATE `aaa-boutique` set  vitrine='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
    $req5 = $bddV->prepare('UPDATE boutique SET vitrine = :vitrine WHERE idBoutique = :idBoutique');
                                      $req5->execute(array(
                                      'vitrine' => $vitrine,
                                      'idBoutique' => $idBoutique
                                    )) or die(print_r($req5->errorInfo()));
                                      $req5->closeCursor();
}
//OK PDO
if (isset($_POST['btnActiverConf'])) {
	$idBoutique=$_POST['iActconfiguration'];
	$activer=1;
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET enConfiguration=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique ))  or die(print_r($req3->errorInfo()));
}elseif (isset($_POST['btnDesactiverConf'])) {
	$idBoutique=$_POST['iapresConfiguration'];
	$activer=0;
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET enConfiguration=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}
//0k PDO
if (isset($_POST['btnActiver'])) {
	$idBoutique=$_POST['iAB'];
	$activer=1;

	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET activer=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}elseif (isset($_POST['btnDesactiver'])) {
	$idBoutique=$_POST['iDB'];
	$activer=0;

	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET activer=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}elseif (isset($_POST['btnModifierBoutique'])) {
	$idBoutique					=$_POST['iM'];
	$nomBoutique				=$_POST['nomBoutique'];
	$labelB						=@htmlentities($_POST['labelB']);
	$adresseB					=@htmlentities($_POST['adresseB']);
	$type        				=@htmlentities($_POST['type']);
	$categorie					=@htmlentities($_POST['categorie']);
	/*$nomBInitial    		    =$_POST['nomBInitial'];
	$adresseBInitial 		    =$_POST['adresseBInitial'];
	$typeBInitial 	   			=$_POST['typeBInitial'];
	$categorieBInitial          =$_POST['categorieBInitial'];*/
	//echo $idBoutique;
	
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET labelB=:l,type=:t,adresseBoutique=:a,categorie=:c WHERE idBoutique=:idB ");
    $req3->execute(array( 'l' => $labelB,'t' => $type,'a' => mysql_real_escape_string($adresseB),'c' => mysql_real_escape_string($categorie),  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));

}//NOT OK PDO Down
elseif (isset($_POST['btnSupprimerBoutique'])) {
	$idBoutique				=$_POST['iS'];

	$req = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE idBoutique=:i"); 
	$req->execute(array('i' =>$idBoutique))  or die(print_r($req->errorInfo())); 
	$boutique=$req->fetch();
  
	$nomBoutique		=$boutique['nomBoutique'];
	$nomtableJournal=$nomBoutique."-journal";
	$nomtableCategorie=$nomBoutique."-categorie";
	$nomtablePage=$nomBoutique."-pagej";
	$nomtablePagnet=$nomBoutique."-pagnet";
	$nomtableLigne=$nomBoutique."-lignepj";
	$nomtableDesignation=$nomBoutique."-designation";
	$nomtableDesignationsd=$nomBoutique."-designationsd";
	$nomtableStock=$nomBoutique."-stock";
	$nomtableTotalStock=$nomBoutique."-totalstock";
	$nomtableBon=$nomBoutique."-bon";
	$nomtableClient=$nomBoutique."-client";
	$nomtableVersement=$nomBoutique."-versement";
	$nomtableCompte=$nomBoutique."-compte";
	$nomtableFournisseur=$nomBoutique."-fournisseur";
	$nomtableBl=$nomBoutique."-bl";
	$nomtableInventaire=$nomBoutique."-inventaire";
	//echo $nomtableJournal;

	try {
		$req1 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableJournal."`");
		$req2 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableCategorie."`");
		$req3 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtablePage."`");
		$req4 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtablePagnet."`");
		$req0 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableDesignationsd."`");
		$req5 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableLigne."`");
		$req6 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableDesignation."`");
		$req7 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableStock."`");
		$req8 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableBon."`");
		$req9 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableClient."`");
		$req10 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableVersement."`");
		$req11 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableTotalStock."`");
		$req12 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableCompte."`");
		$req13 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableFournisseur."`");
		$req14 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableBl."`");
		$req15 =$bdd->exec("DROP TABLE IF EXISTS `".$nomtableInventaire."`");

		
        $req17 = $bdd->prepare("DELETE FROM `aaa-acces` WHERE idBoutique=:i ");
        $req17->execute(array('i' => $idBoutique )) or die(print_r($req17->errorInfo()));

        $req19 = $bdd->prepare("DELETE FROM `aaa-boutique` WHERE idBoutique=:ib ");
        $req19->execute(array('ib' => $idBoutique )) or die(print_r($req19->errorInfo()));

		/* if($res1){
			$sql16="SELECT * FROM `aaa-acces` WHERE idBoutique=".$idBoutique;
			$res16 = mysql_query($sql16) or die ("personel requête 2".mysql_error());
			while ($acces = mysql_fetch_array($res16)) {
			  //$sql17="DELETE FROM `aaa-utilisateur` WHERE idutilisateur=".$acces['idutilisateur'];
			  //$res17=@mysql_query($sql17) or die ("suppression impossible personnel".mysql_error()); 
			  $sql18="DELETE FROM `aaa-acces` WHERE idutilisateur=".$acces['idutilisateur'];
			  $res18=@mysql_query($sql18) or die ("suppression impossible personnel".mysql_error());
		  }
		   $sql19="DELETE FROM `aaa-boutique` WHERE idBoutique=".$idBoutique;
			 $res19=@mysql_query($sql19) or die ("suppression impossible personnel".mysql_error());
		   $messageSupBoutiqueOK="Boutique Suprimée avec succée";
		}else{
			$messageSupBoutiqueERROR="La Supression du boutique n'a pas marché";
		} */

	}catch(PDOException $e) {
		echo $e->getMessage();//Remove or change message in production code
	}
	
}

if (isset($_POST['btnUpdateBoutiqueClient'])) {
	// 	$req="IF NOT EXISTS( SELECT NULL
	//             FROM INFORMATION_SCHEMA.COLUMNS
	//            WHERE table_name = 'aaa-boutique'
	//              AND table_schema = 'sdru1621_bdjournalcaisse'
	//              AND column_name = 'compte')  THEN
	//   ALTER TABLE `aaa-boutique` ADD `compte` int(11) NOT NULL default '0';
	// END IF;";
	// $resr =@mysql_query($req) or die ("creation table compte impossible 11111 ".mysql_error());
	$sql50="ALTER TABLE `aaa-boutique` ADD IF NOT EXISTS `compte` int(11) NOT NULL;";
	$res50 =@mysql_query($sql50) or die ("creation table compte impossible ".mysql_error());
	$sql17="SELECT * FROM `aaa-boutique`";
	$res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
	while ($b = mysql_fetch_array($res17)) {
		// var_dump($b)
		$nomBoutiqueC=$b['nomBoutique']."-client";
		$nomBoutiqueBL=$b['nomBoutique']."-bl";
		$nomBoutiqueV=$b['nomBoutique']."-versement";
		$sql1="ALTER TABLE `".$nomBoutiqueC."` ADD COLUMN IF NOT EXISTS `personnel` int(11) NOT NULL;";
		$sql2="ALTER TABLE `".$nomBoutiqueC."` ADD COLUMN IF NOT EXISTS `archiver` int(11) NOT NULL;";
		$sql3="ALTER TABLE `".$nomBoutiqueC."` ADD COLUMN IF NOT EXISTS `avoir` int(11) NOT NULL;";
		$sql4="ALTER TABLE `".$nomBoutiqueC."` ADD COLUMN IF NOT EXISTS `montantAvoir` double NOT NULL;";
		$sql5="ALTER TABLE `".$nomBoutiqueBL."` ADD COLUMN IF NOT EXISTS `description` varchar(200) NOT NULL;";
		$sql6="ALTER TABLE `".$nomBoutiqueV."` ADD COLUMN IF NOT EXISTS  `montantAvoir` double NOT NULL;";
		// $sql7="ALTER TABLE `".$nomBoutiqueC."` DROP IF EXISTS `numero`;";
		$sql8="ALTER TABLE `".$nomBoutiqueC."` ADD COLUMN IF NOT EXISTS `matriculePension` varchar(30) NOT NULL;";
		$sql9="ALTER TABLE `".$nomBoutiqueC."` ADD COLUMN IF NOT EXISTS `numCarnet` varchar(30) NOT NULL;";
		$sql10="ALTER TABLE `".$tableFournisseur."` ADD COLUMN IF NOT EXISTS `banqueFournisseur` varchar(50) NOT NULL;";
		$sql11="ALTER TABLE `".$tableFournisseur."` ADD COLUMN IF NOT EXISTS `numBanqueFournisseur` varchar(50) NOT NULL;";
		$res1 =@mysql_query($sql1) or die ("creation table compte impossible ".mysql_error());
		$res2 =@mysql_query($sql2) or die ("creation table mouvement impossible ".mysql_error());
		$res3 =@mysql_query($sql3) or die ("creation table mouvement impossible ".mysql_error());
		$res4 =@mysql_query($sql4) or die ("creation table mouvement impossible ".mysql_error());
		$res5 =@mysql_query($sql5) or die ("creation table operation impossible ".mysql_error());
		$res6 =@mysql_query($sql6) or die ("creation table operation impossible ".mysql_error());
		// $res7 =@mysql_query($sql7) or die ("creation table operation impossible ".mysql_error());
		$res8 =@mysql_query($sql8) or die ("creation table operation impossible ".mysql_error());
		$res9 =@mysql_query($sql9) or die ("creation table operation impossible ".mysql_error());
		$res10 =@mysql_query($sql10) or die ("banqueFournisseur ".mysql_error());
		$res11 =@mysql_query($sql11) or die ("numBanqueFournisseur ".mysql_error());
		// var_dump(111);
  }
}
//OK PDO
if (isset($_POST['btnActiverCompte'])) {
	$idBoutique=$_POST['iSc'];
	$nomBoutique=htmlentities($_POST['nbSc']);

	$req16 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE idBoutique=:i"); 
	$req16->execute(array('i' =>$idBoutique))  or die(print_r($req16->errorInfo())); 
	$boutique=$req16->fetch();
  
	$activer=1;

	
	$req0 = $bdd->prepare("UPDATE `aaa-boutique` SET compte=:act WHERE idBoutique=:idB ");
    $req0->execute(array( 'act' => $activer,  'idB' => $idBoutique ))
                     or die(print_r($req0->errorInfo()));

	$tableCompte=$boutique['nomBoutique']."-compte";
	$tableCompteMouvement=$boutique['nomBoutique']."-comptemouvement";
	$tableCompteOperation=$boutique['nomBoutique']."-compteoperation";
	$tableCompteType=$boutique['nomBoutique']."-comptetype";

	
	$reqA = $bdd->prepare("DROP TABLE IF EXISTS `".$tableCompte."` ");
	$reqA->execute() or die(print_r($reqA->errorInfo()));

	
	//$sqlB="DROP TABLE IF EXISTS `".$tableCompteMouvement."`";
	//$resB=@mysql_query($sqlB);
	//$sqlC="DROP TABLE IF EXISTS `".$tableCompteOperation."`";
	//$resC=@mysql_query($sqlC);
	//$sqlD="DROP TABLE IF EXISTS `".$tableCompteType."`";
	//$resD=@mysql_query($sqlD);
	
	try {

		$req1 =$bdd->exec("CREATE TABLE IF NOT EXISTS `".$tableCompte."` (
			`idCompte` int(11) NOT NULL AUTO_INCREMENT,
			`nomCompte` varchar(20) COLLATE utf8_bin NOT NULL,
			`typeCompte` varchar(20) COLLATE utf8_bin NOT NULL,
			`numeroCompte` varchar(50) COLLATE utf8_bin NOT NULL,
			`montantCompte` double DEFAULT NULL,
			`recevoirPayement` int(11) NOT NULL,
			`activer` tinyint(1) NOT NULL DEFAULT 1,
			PRIMARY KEY (`idCompte`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
		
		$req_A = $bdd->prepare("INSERT INTO `".$tableCompte."` (`idCompte`, `nomCompte`, `typeCompte`, `numeroCompte`, `montantCompte`, `recevoirPayement`, `activer`) values
			(1, 'Caisse', 'caisse', 'sans numero', 0, 1, 1),
			(2, 'Bon', 'bon', 'sans numero', 0, 1, 1),
			(3, 'Remise', 'remise', 'sans numero', 0, 1, 1);");
		$req_A->execute()  or die(print_r($req_A->errorInfo()));
	
		$req2 =$bdd->exec("CREATE TABLE IF NOT EXISTS `".$tableCompteMouvement."` (
			`idMouvement` int(11) NOT NULL AUTO_INCREMENT,
			`idCompte` int(11) NOT NULL,
			`operation` varchar(30) COLLATE utf8_bin NOT NULL,
			`montant` double NOT NULL,
			`numeroDestinataire` varchar(30) COLLATE utf8_bin NOT NULL,
			`description` text COLLATE utf8_bin NOT NULL,
			`dateOperation` datetime NOT NULL,
			`dateSaisie` datetime NOT NULL,
			`dateEcheance` date DEFAULT NULL,
			`dateRetrait` datetime NOT NULL DEFAULT current_timestamp(),
			`compteDonateur` varchar(30) COLLATE utf8_bin DEFAULT NULL,
			`nomClient` varchar(30) COLLATE utf8_bin DEFAULT NULL,
			`frais` int(11) NOT NULL,
			`compteDestinataire` int(11) NOT NULL,
			`mouvementLink` int(11) NOT NULL,
			`idVersement` int(11) NOT NULL,
			`annuler` tinyint(1) NOT NULL DEFAULT 0,
			`retirer` tinyint(1) NOT NULL DEFAULT 0,
			`idPR` int(11) NOT NULL DEFAULT 0,
			`idUser` int(11) NOT NULL,
			PRIMARY KEY (`idMouvement`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	
		$req3 =$bdd->exec("CREATE TABLE IF NOT EXISTS `".$tableCompteOperation."` (
			`idOperation` int(11) NOT NULL AUTO_INCREMENT,
			`typeCompte` varchar(20) COLLATE utf8_bin NOT NULL,
			`nomOperation` varchar(40) COLLATE utf8_bin NOT NULL,
			PRIMARY KEY (`idOperation`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	
		$req4 =$bdd->exec("CREATE TABLE IF NOT EXISTS `".$tableCompteType."` (
			`idType` int(11) NOT NULL AUTO_INCREMENT,
			`nomType` varchar(30) COLLATE utf8_bin NOT NULL,
			PRIMARY KEY (`idType`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	
		$tablePanier=$boutique['nomBoutique']."-pagnet";
		// SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=[Database Name] AND TABLE_NAME=[Table Name];
		$req5 =$bdd->exec("ALTER TABLE `".$tablePanier."` ADD COLUMN IF NOT EXISTS `idCompte` int(11) NOT NULL;");
	
	
		$tableVersement=$boutique['nomBoutique']."-versement";
		$req6 =$bdd->exec("ALTER TABLE `".$tableVersement."` ADD COLUMN IF NOT EXISTS `idCompte` int(11) NOT NULL;");
		$req7 =$bdd->exec("ALTER TABLE `".$tableVersement."` ADD COLUMN IF NOT EXISTS `idPagnet` int(11) NOT NULL;");
		$req8 =$bdd->exec("ALTER TABLE `".$tablePanier."` ADD COLUMN IF NOT EXISTS `avance` double NOT NULL;");
		
		$req170 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE idBoutique=:i"); 
		$req170->execute(array('i' =>$idBoutique))  or die(print_r($req170->errorInfo())); 
		$b=$req170->fetch();
	
		if ($b['type']=='Pharmacie') {
			# code...
			$tablePanierMutuelle=$boutique['nomBoutique']."-mutuellepagnet";
			// $tablePanierMutuelle=$nomBoutique."-mutuellepagnet"
			
			$req50 =$bdd->exec("ALTER TABLE `".$tablePanierMutuelle."` ADD COLUMN IF NOT EXISTS `idCompte` int(11) NOT NULL;");
			$req51 =$bdd->exec("ALTER TABLE `".$tablePanierMutuelle."` ADD COLUMN IF NOT EXISTS `avance` double NOT NULL;");
			$req52 =$bdd->exec("ALTER TABLE `".$tableCompteMouvement."` ADD COLUMN IF NOT EXISTS `idMutuellePagnet` int(11) NOT NULL;");
			$req53 =$bdd->exec("ALTER TABLE `".$tableVersement."` ADD COLUMN IF NOT EXISTS `idMutuellePagnet` int(11) NOT NULL;");
	
		}
	
	} catch (PDOException $e) {
		echo  "<br>" . $e->getMessage()."<br>";
	}
}
elseif (isset($_POST['btnDesactiverCompte'])) {
	$idBoutique=$_POST['iAc'];
	$activer=0;

	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET compte=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));

}
//OK PDO
if (isset($_POST['btnActiverMutuelle'])) {
	$idBoutique=$_POST['iSm'];
	$activer=1;
	
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET mutuelle=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}
elseif (isset($_POST['btnDesactiverMutuelle'])) {
	$idBoutique=$_POST['iAm'];
	$activer=0;

	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET mutuelle=:act WHERE idBoutique=:idB ");
    $req3->execute(array( 'act' => $activer,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}
//OK PDO
if (isset($_POST['btnReinit'])) {
	$idUser=$_POST['id'];
	$password=sha1(123456);
	$req3 = $bdd->prepare("UPDATE `aaa-utilisateur` SET motdepasse=:pas WHERE idutilisateur=:idU ");
    $req3->execute(array( 'pas' => $password,  'idU' => $idUser )) or die(print_r($req3->errorInfo()));
	?>
	<script>
		alert('Mot de passe initialisé à 123456')
	</script>
	<?php
}
//OK PDO
if (isset($_POST['btnPaimentFixe'])) {
	$idBoutique=$_POST['iPf'];
	$montant=$_POST['montant'];
	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET montantFixeHorsParametre=:m WHERE idBoutique=:idB ");
    $req3->execute(array( 'm' => $montant,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}
//OK PDO
if (isset($_POST['btnReinitPaimentFixe'])) {
	$idBoutique=$_POST['iPf'];
	$montant=0;

	$req3 = $bdd->prepare("UPDATE `aaa-boutique` SET montantFixeHorsParametre=:m WHERE idBoutique=:idB ");
    $req3->execute(array( 'm' => $montant,  'idB' => $idBoutique )) or die(print_r($req3->errorInfo()));
}
if (isset($_POST['installationBoutiqueProspectXXXX'])) {
	$idBoutique =htmlentities($_POST["boutiqueProsp"]);
	$idClient =htmlentities($_POST["clientProsp"]);
	
	$req1 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospect` where idBP=:i "); 
    $req1->execute(['i'=>$idBoutique])  or die(print_r($req1->errorInfo())); 
    $boutique = $req1->fetch();

	$req2 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospectclient` where idBPC=:ic "); 
    $req2->execute(['ic'=>$idClient])  or die(print_r($req2->errorInfo())); 
    $client = $req2->fetch();

	var_dump($boutique['nomBoutique']);
	var_dump($idClient);
	$_SESSION['nomB']=$boutique['nomBoutique'];
	$_SESSION['type']=$boutique['type'];
	$_SESSION['categorie']=$boutique['categorie'];
	
	include '../JCaisse/declarationVariables.php';
	include 'boutique/CreationBoutiqueFunctions.php';

	if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
		 createPharmaDetaillant();
	   
	}else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

		createEntrepotGrossiste();
	}else{
		createLeResteDesTypes();
		   
	}

}
//require('entetehtml.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>    
	<link rel="stylesheet" href="css/bootstrap.css"> 
	<link rel="stylesheet" href="css/datatables.min.css">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" >

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" >


	<script src="js/jquery-3.1.1.min.js"></script>
   <script type="text/javascript" src="js/jquery.mask.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<script src="js/bootstrap.js"></script>
    <script src="js/datatables.min.js"></script>
	
    <script src="js/datatables.min.js"></script>
    <script>$(document).ready( function () {$('#exemple').DataTable();} );</script>
    <script>$(document).ready( function () {$('#exemple2').DataTable();} );</script>
    <script>$(document).ready( function () {$('#exemple3').DataTable();} );</script>
   <script> $(document).ready(function () { $("#exemple3").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple4").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple5").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple6").DataTable();});</script>
   <script type="text/javascript" src="js/scriptB.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<title>JCAISSE-BACK END</title>
</head>
<body >
<?php
  require('header.php');
?>
        <div class="container-fluid" align="center">
			<div class="row" >
                <?php if($messageSupBoutiqueOK!='') { ?>
                    <div class="alert alert-success ">
                        <strong> <?php  echo $messageSupBoutiqueOK; ?>	</strong>
                    </div>
                <?php } ?>
                <?php if($messageSupBoutiqueERROR!='') { ?>
                    <div class="alert alert-danger ">
                        <strong> <?php  echo $messageSupBoutiqueERROR; ?>	</strong>
                    </div>
                <?php } ?>
            </div>
			<!-- ///////////////////////////////////////////// POP UP //////////////////////////////////////////////// -->
			<div class="modal fade" id="ActConfiguration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Configuration </h4>
						</div>
						<div class="modal-body">
							<form name="formulaireVersement" method="post" action="boutiques.php">
								<div class="form-group">
									<h2>Voulez vous vraiment terminer la configuration de cette boutique</h2>
									<input type="hidden" name="iActconfiguration" id="iActconfiguration"  >
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
									<button type="submit" name="btnActiverConf" class="btn btn-primary">Terminer la Config</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="ApresConfiguration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Configuration</h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment faire passer cette boutique en mode configuration ? </h2>
																		<input type="hidden" name="iapresConfiguration" id="iapresConfiguration" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnDesactiverConf" class="btn btn-primary">Passer en mode config</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
            <!----------------------------------------------------------->
			<!----------------------------------------------------------->
			<div class="modal fade"  id="EnTest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Terminer la phase test </h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment terminer la phase test de cette boutique</h2>
																		<input type="hidden" name="enTest" id="enTest" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnTerminerTest" class="btn btn-primary">Terminer la phase test</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
			<div class="modal fade" id="ApresTest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Activer la phase test</h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment passer cette boutique en phase test ? </h2>
																		<input type="hidden" name="apresTest" id="apresTest" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnDesactiverTest" class="btn btn-primary">Passer en phase test</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
			<!----------------------------------------------------------->
			<!----------------------------------------------------------->
			<div class="modal fade" id="SansCompte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Activer le mode compte </h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment activer le mode compte de cette boutique</h2>
																		<input type="hidden" name="iSc" id="iSc" >
																		<input type="hidden" name="nbSc" id="nbSc" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnActiverCompte" class="btn btn-primary">Activer Compte</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
			<div class="modal fade" id="AvecCompte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Desactiver le mode compte</h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment desactiver le mode compte de cette boutique</h2>
																		<input type="hidden" name="iAc" id="iAc" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnDesactiverCompte" class="btn btn-primary">Desactiver Compte</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
			<!----------------------------------------------------------->
			<!----------------------------------------------------------->
												
			<div class="modal fade" id="SansMutuelle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Activer le mode mutuelle </h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment activer le mode mutuelle de cette pharmacie</h2>
																		<input type="hidden" name="iSm" id="iSm" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnActiverMutuelle" class="btn btn-primary">Activer Mutuelle</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
			<div class="modal fade" id="AvecMutuelle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="myModalLabel">Desactiver le mode mutuelle</h4>
																</div>
																<div class="modal-body">
																	<form name="formulaireVersement" method="post" action="boutiques.php">
																	<div class="form-group">
																		<h2>Voulez vous vraiment desactiver le mode mutuelle de cette pharmacie</h2>
																		<input type="hidden" name="iAm" id="iAm" >
																	</div>
																	<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="submit" name="btnDesactiverMutuelle" class="btn btn-primary">Desactiver Mutuelle</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
			</div>
			<!----------------------------------------------------------->
			<!----------------------------------------------------------->
			<div class="modal fade" id="PaimentFixe" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				<h4 class="modal-title" id="myModalLabel">Activation</h4>
																			</div>
																			<div class="modal-body">
																				<form name="formulaireVersement" method="post" action="boutiques.php">
																				<div class="form-group">
																					<h2>Paiment à montant fixe</h2>
																					<input type="hidden" name="iPf" id="iPf" >
																					<input type="number" class="form-control" name="montant" id="mFHP">
																				</div>
																				<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																						<button type="submit" name="btnReinitPaimentFixe" class="btn btn-success">Reinitialiser</button>
																						<button type="submit" name="btnPaimentFixe" class="btn btn-primary">Enregistrer</button>
																				
																				</div>
																				</form>
																			</div>
																		</div>
																	</div>
			</div>
			<!----------------------------------------------------------->
														
			<div class="modal fade" id="ActiverB" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				<h4 class="modal-title" id="myModalLabel">Activation</h4>
																			</div>
																			<div class="modal-body">
																				<form name="formulaireVersement" method="post" action="boutiques.php">
																				<div class="form-group">
																					<h2>Voulez vous vraiment activer cette boutique</h2>
																					<input type="hidden" name="iAB" id="iAB" >
																				</div>
																				<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																						<button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
																				</div>
																				</form>
																			</div>
																		</div>
																	</div>
			</div>
			<div class="modal fade" id="DesactiverB" tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				<h4 class="modal-title" id="myModalLabel">Desactivation</h4>
																			</div>
																			<div class="modal-body">
																				<form name="formulaireVersement" method="post" action="boutiques.php">
																				<div class="form-group">
																					<h2>Voulez vous vraiment desactiver cette boutique</h2>
																					<input type="hidden" name="iDB" id="iDB" >
																				</div>
																				<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																						<button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
																				</div>
																				</form>
																			</div>
																		</div>
																	</div>
			</div>
			<!----------------------------------------------------------->
			<!----------------------------------------------------------->
			<div class="modal fade" id="ActiverVitrine" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				<h4 class="modal-title" id="myModalLabel">Activation</h4>
																			</div>
																			<div class="modal-body">
																				<form name="formulaireVersement" method="post" action="boutiques.php">
																				<div class="form-group">
																					<h2>Voulez vous vraiment activer cette Vitrine</h2>
																					<input type="hidden" name="iAVB" id="iAVB">
																					<input type="hidden" name="nBAV" id="nBAV">
																					<input type="hidden" name="adBAV" id="adBAV">
																					<input type="hidden" name="tyBAV" id="tyBAV">
																					<input type="hidden" name="catBAV"  id="catBAV">
																				</div>
																				<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																						<button type="submit" name="btnActiverVitrine" class="btn btn-primary">Activer</button>
																				</div>
																				</form>
																			</div>
																		</div>
																	</div>
			 </div>
			<div class="modal fade" id="DesactiverVitrine" tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				<h4 class="modal-title" id="myModalLabel">Desactivation</h4>
																			</div>
																			<div class="modal-body">
																				<form name="formulaireVersement" method="post" action="boutiques.php">
																				<div class="form-group">
																					<h2>Voulez vous vraiment desactiver cette Vitrine</h2>
																					<input type="hidden" name="iDVB" id="iDVB">
																					<input type="hidden" name="nBDV" id="nBDV">
																					<input type="hidden" name="adBDV" id="adBDV">
																					<input type="hidden" name="tyBDV" id="tyBDV">
																					<input type="hidden" name="catBDV"  id="catBDV">
																				</div>
																				<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																						<button type="submit" name="btnDesactiverVitrine" class="btn btn-primary">Desactiver</button>
																				</div>
																				</form>
																			</div>
																		</div>
																	</div>
			</div>

			<!----------------------------------------------------------->
			<div id="imgmodifierBoutique"   class="modal fade" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title">Formulaire pour modifier une boutique</h4>
																</div>
																<div class="modal-body">
																			<form name="formulairePersonnel" method="post" action="boutiques.php">
																				<div class="form-group row">
																					<label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
																					<div class="col-sm-5">
																					<input type="text" name="labelB" id="labelBM" class="form-control" required /> 
																					<input type="hidden" name="nomBInitial" id="nomBInitialM" />
																					<input type="hidden" name="nomBoutique" id="nomBoutiqueM" />
																					</div>
																				</div>
																				<div class="form-group row">
																					<label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
																					<div class="col-sm-5">
																					<input type="text" name="adresseB" class="form-control" id="adresseBM" required />
																					<input type="hidden" name="adresseBInitial" id="adresseBInitialM" />
																					</div>
																				</div>
																				<div class="form-group row">
																					<label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
																					<div class="col-sm-6">
																						<select name="type" id="type"> <?php
																							$sql10="SELECT * FROM `aaa-typeboutique`";
																							$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
																							while($ligne = mysql_fetch_row($res10)) {
																									if ($ligne[1]==$boutique['type']) {
																											echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
																									}else {
																											echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
																									}
																								} ?>
																						</select>
																						<input type="hidden" name="typeBInitial" id="typeBInitialM" />
																					</div>
																				</div>
																				<div class="form-group row">
																						<label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
																						<div class="col-sm-6">
																						<select name="categorie" id="categorie"> <?php
																								$sql11="SELECT * FROM `aaa-categorie`";
																								$res11=mysql_query($sql11);
																								while($ligne2 = mysql_fetch_row($res11)) {
																									if ($ligne2[1]==$boutique['categorie']) {
																										echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
																									}else {
																										// code...
																										echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
																									}
																									} ?>
																							</select>
																							<input type="hidden" name="categorieBInitial" id="categorieBInitialM" />
																						</div>
																				</div>
																					<div class="modal-footer">
																					<input type="hidden" name="iM" id="iM" />
																							<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																							<button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
																				</div>
																			</form>
																		</div>
															</div>
														</div>
			</div>
			<div id="imgsuprimerBoutique"  class="modal fade" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
																</div>
																<div class="modal-body">
																	<form role="form" class="formulaire2" name="formulaire2" method="post" action="boutiques.php">
																		<div class="form-group row">
																					<label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
																					<div class="col-sm-5">
																						<input type="text" id="labelBS" class="form-control" disabled="" />
																					</div>
																				</div>
																				<div class="form-group row">
																					<label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
																					<div class="col-sm-5">
																						<input type="text" id="adresseBS" class="form-control" disabled="" />
																					</div>
																				</div>
																				<div class="form-group row">
																					<label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
																					<div class="col-sm-6">
																						<input type="text"  id="typeS" disabled />
																					</div>
																				</div>
																				<div class="form-group row">
																						<label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
																						<div class="col-sm-6">
																							<input type="text" id="categorieS" disabled />
																						</div>
																				</div>
																		<div class="modal-footer row">
																			<input type="hidden" name="iS" id="iS" />
																			<button type="button" class="btn btn-default" >Annuler</button>
																			<button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
			</div> 
			<!----------------------------------------------------------->
			 <!---------------------- POPUP PROSPECT  ------------------->
			 <div class="modal fade" id="addProspectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">CREATION NOUVELLE ENTREPRISE </h4>
						</div>
						<div class="modal-body">
							<form name="formulaireVersement" method="post" >
								<div class="form-group">
									<label for="nomBoutique">NOM ENTREPRISE <font color="red">*</font></label>
									<input type="text" class="form-control" placeholder="Nom Entreprise" id="nomBPr" name="nomB" size="40"  required />
									<input type="hidden" id="clientProsp">
									<div class="help-block" id="helpNomB"></div>
								</div>
								<div class="form-group">
									<label for="adresse">ADRESSE <font color="red">*</font></label>
									<input type="text" class="form-control" placeholder="Adresse Entreprise" id="adresseBPr" name="adresseB" size="40"  required />
									<div class="help-block" id="helpAdresseB"></div>
								</div>
								<div class="form-group">
									<label for="adresse">TELEPHONE <font color="red">*</font></label>
									<input type="text" class="form-control" placeholder="Telephone Entreprise" id="telephoneBPr" name="telephone" size="40"  required />
									<div class="help-block" id="helpaccompagnateur"></div>
								</div>
								<div class="form-group">
									<label for="type">TYPE <font color="red">*</font></label>
									<select name="type" id="typeBPr" class="form-control">
										<?php			
											$sql = $bdd->prepare("SELECT * FROM `aaa-typeboutique`"); 
											$sql->execute()  or die(print_r($sql->errorInfo()));
											//$typeboutique =$sql->fetch();
											while($typeboutique =$sql->fetch()) {
												echo'<option value= "'.$typeboutique[1].'">'.$typeboutique[1].'</option>';
											} ?>
										</select>
									<div class="help-block" id="helpType"></div>
								</div>
								<div class="form-group">
									<label for="type">CATEGORIE <font color="red">*</font></label>
									<select name="categorie" id="categorieBPr" class="form-control"> 
										<?php
											//$sql11="SELECT * FROM `aaa-categorie`";
											$sql = $bdd->prepare("SELECT * FROM `aaa-categorie`"); 
												$sql->execute()  or die(print_r($sql->errorInfo()));
												//$typeboutique =$sql->fetch();
												while($categorie =$sql->fetch()) {
												echo'<option value= "'.$categorie[1].'">'.$categorie[1].'</option>';
											} 
										?>
									</select>
									<div class="help-block" id="helpCategorie"></div>
								</div>
								<div class="form-group">
									<label for="adresse">REGISTRE DE COMMERCE <font color="red">*</font></label>
									<input type="text" class="form-control" placeholder="Registre de commerce" id="registreComBPr" name="registreCom" size="40" required />
									<div class="help-block" id="helpaccompagnateur"></div>
								</div>
								<div class="form-group">
									<label for="adresse">NINEA <font color="red">*</font></label>
									<input type="text" class="form-control" placeholder="Ninea" id="nineaBPr" name="ninea" size="40" required />
									<div class="help-block" id="helpaccompagnateur"></div>
								</div>
								<div class="form-group">
									<label for="adresse">ACCOMPAGNATEUR <font color="red">*</font></label>
									<input type="test" class="form-control" placeholder="Matricule Accompagnateur" id="accompagnateurBPr" name="accompagnateur" size="40"  required />
									<div class="help-block" id="helpaccompagnateur"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
									<button type="button" class="boutonbasic" id="addProspectBoutique" name="inserer">CREER LA BOUTIQUE</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="addProspectUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Nouveau client prospect </h4>
						</div>
						<div class="modal-body">
							<form role="form" class="formulaire2"  method="post">
								<div class="form-group">
									<tr><td><label for="prenom">PRENOM <font color="red">*</font></label></td></tr>
									<tr><td><input type="text" class="form-control" placeholder="Votre prenom ici" id="prenomNewClient" name="prenom" size="40" value="" /></td></tr>
									<tr><td><div class="help-block" id="helpPrenom"></div></td></tr>
								</div>
								<div class="form-group">
									<tr><td><label for="nom"> NOM <font color="red">*</font></label></td></tr>
									<tr><td><input type="text" class="form-control" placeholder="Votre nom ici" id="nomNewClient" name="nom" size="40" value=""/></td></tr>
									<tr><td><div class="help-block" id="helpNom"></div></td></tr>
								</div>
								<div class="form-group">
									<tr><td><label for="nomBoutique"> NOM DE LA BOUTIQUE <font color="red">*</font></label></td></tr>
									<tr><td><input type="text" class="form-control" placeholder="Nom de la boutique" id="nomBoutiqueNewClient" name="nomBoutique" size="40" value=""/></td></tr>
									<tr><td><div class="help-block" id="helpNomBoutique"></div></td></tr>
								</div>
								<div class="form-group">
									<tr><td><label for="adresse">ADRESSE </label></td></tr>
									<tr><td><input type="text" class="form-control" placeholder="Votre adresse" id="adresseNewClient" name="adresse" size="40" value="" /></td></tr>
									<tr><td><div class="help-block" id="helpAdresse"></div></td></tr>
								</div>
								<div class="form-group">
									<tr><td><label for="portable">TEL. PORTABLE <font color="red">*</font></label></td></tr>
									<tr><td><input type="text" class="form-control" placeholder="Telephone Portable" id="telPortableNewClient" name="telPortable" size="40" value="" /></td></tr>
									<tr><td><div class="help-block" id="helpPortable"></div></td></tr>
								</div>
								<div class="form-group">
									<tr><td><label for="fixe">TEL. FIXE </label></td></tr>
									<tr><td><input type="text" class="form-control" placeholder="Telephone fixe" id="telFixeNewClient" name="telFixe" size="40" value="" /></td></tr>
									<tr><td><div class="help-block" id="helpFixe"></div></td></tr>
								</div>
								<div class="form-group">
									<tr><td><label for="email">EMAIL <font color="red">*</font></label></td></tr>
									<tr><td><input type="email" class="form-control" placeholder="Adresse email" id="emailNewClient" name="email" size="40" value="" /></td></tr>
									<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
								</div>
								<div class="modal-footer">
									<font color="red">Les champs qui ont (*) sont obligatoires</font><br/>
									<button  class="boutonbasic" id="addNewClientProspect" name="insererNewClient">S'INSCRIRE</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="detProspPop" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Activation</h4>
						</div>
						<div class="modal-body">
							<div id="boutiqueProspectDet">
								
							</div>
						</div>
					</div>
				</div>
			 </div>
			<div class="modal fade" id="popUpSupClien" tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																				<h4 class="modal-title" id="myModalLabel">Suppression Client Prospect</h4>
																			</div>
																			<div class="modal-body">
																				<form name="formulaireVersement" method="post" action="boutiques.php">
																				<div class="form-group">
																					<h2>Voulez vous vraiment supprimer le prospect :</h2>
																					<h3> <span id="prosNomPren"></span></h3>
																					<input type="hidden" id="idProsCliSup">
																					
																				</div>
																				<div class="modal-footer">
																						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																						<button type="button" onclick="spm_ClientP()" class="btn btn-primary">Supprimer</button>
																				</div>
																				</form>
																			</div>
																		</div>
																	</div>
			</div>
			
							  <!-- FIN POP UP -->
			<ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTEBOUTIQUE">LISTE DES BOUTIQUES</a></li>
              <?php if ($_SESSION['profil']!="Assistant"): ?>
				<li class=""><a data-toggle="tab" href="#PARAMETRESBOUTIQUES" onclick="ongletBParametre()">PARAMETRES DES BOUTIQUES</a></li>
            	<li class=""><a data-toggle="tab" href="#LISTECUTILISATEUR" onclick="ongletBUser()" >LISTE DES UTILISATEURS</a></li>  
            	<li class=""><a data-toggle="tab" href="#PROSPECT" onclick="ongletProspect()" >LISTE DES PROSPECTS</a></li>        
            	<li class=""><a data-toggle="tab" href="#EVENTS" onclick="ongletProspect()" >EVENEMENTS A VENIR</a></li>        
              <?php endif ?>
              </ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="LISTEBOUTIQUE">
						<?php if ($_SESSION['profil']!="Assistant" && $_SESSION['profil']!="Accompagnateur"  ): ?>
							<table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
										<th>Catalogue</th>
										<th>Stockage</th>
										<th>Opération</th>
										
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
										<th>Catalogue</th>
										<th>Stockage</th>
										<th>Opération</th>
										
									</tr>
								</tfoot>
								<tbody>
								<?php
									if($_SESSION['profil']=="SuperAdmin" OR  $_SESSION['profil']=="Assistant"){
										$sql2="SELECT * FROM `aaa-boutique`";
										$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
										while ($boutique = mysql_fetch_array($res2)) {
											$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin' order by idBoutique DESC LIMIT 1";
											//$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin' ";
											$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
											while ($acces = mysql_fetch_array($res3)) {
												$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur']." LIMIT 1";
												$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
												while ($utilisateur = mysql_fetch_array($res4)){
											?>
											<tr>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php
													$nomtableDesignation=$boutique["nomBoutique"]."-designation";
													$sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
													$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
													if($compteur = mysql_fetch_array($res))
														echo  $compteur["nbreRef"]; ?>  </td>
												<td> <?php
													if ($boutique["type"]=="Entrepot") {
														$nomtableStock=$boutique["nomBoutique"]."-entrepotstock";
													} else {
														$nomtableStock=$boutique["nomBoutique"]."-stock";
													} 
													 
													$sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant !=0";
													$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
													if($compteur = mysql_fetch_array($res))
														echo  $compteur["nbreStock"]; ?>  </td>
												<td>
													<?php if ($_SESSION['profil']!="Assistant"): ?>
														<a href="#" >
														<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" onclick="modBouti(<?php echo $boutique['idBoutique'] ; ?>)"  /></a>&nbsp;
													<?php if ($boutique["activer"]==0) { ?>
														<a href="#">
															<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" onclick="supBouti(<?php echo $boutique['idBoutique'] ; ?>)" /></a>
															<?php }else{ ?>
															<a href="#">
															<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal"   /></a>
														<?php	} ?>
														<!--      DEBUT DETAIL BOUTIQUE  -->
															<a href="" data-toggle="modal" <?php echo  "data-target=#detailB".$boutique['idBoutique'] ; ?>>
																	Detail
																	</a>
															<div class="modal fade bd-example-modal-lg" <?php echo  "id=detailB".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
																<div class="modal-dialog modal-lg" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																			<h4 class="modal-title" id="myModalLabel">Details boutique </h4>
																		</div>
																		<div class="modal-body">
																			<form name="formulaireVersement" method="post" action="boutiques.php">
																			<div class="form-group">
																				<h2>Listes des utilisateurs pour <?php echo  $boutique['labelB'] ; ?> </h2>
																					<table id="exemple2" class="display" border="1" class="table table-bordered table-striped" id="userTable">
																							<thead>
																								<tr>
																									<th>Prénom</th>
																									<th>Nom</th>
																									<th>Téléphone</th>
																									<th>Email</th> 
																									<th>Propriétaire</th>
																									<th>Gérant</th>
																									<th>Caissier</th>
																									<th>Vendeur</th>
																								</tr>
																							</thead>
																							<tfoot>
																								<tr>
																									<th>Prénom</th>
																									<th>Nom</th>
																									<th>Téléphone</th>
																									<th>Email</th>
																									<th>Propriétaire</th>
																									<th>Gérant</th>
																									<th>Caissier</th>
																									<th>Vendeur</th>
																								</tr>
																							</tfoot>
																							<tbody>
																								<?php
																									$sql4="SELECT *   FROM `aaa-acces`   WHERE idBoutique =".$boutique['idBoutique'];
																										$res4 = mysql_query($sql4) or die ("persoonel requête 4".mysql_error());
																										while ($acces = mysql_fetch_array($res4)) {
																											$sql5="SELECT *  FROM `aaa-utilisateur`  WHERE idutilisateur =".$acces['idutilisateur'];
																												if($res5 = mysql_query($sql5)) {
																													while($utilisateur = mysql_fetch_array($res5)) {
																													?>
																													<tr>
																														<td> <?php echo  $utilisateur['prenom']; ?>  </td>
																														<td> <?php echo  $utilisateur['nom']; ?>  </td>
																														<td> <?php echo  $utilisateur['telPortable']; ?>  </td>
																														<td> <?php echo  $utilisateur['email']; ?>  </td>
																														<td> <?php if ($acces['proprietaire']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['gerant']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['caissier']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['vendeur']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																													</tr>
																									<?php   }
																									}
																									}
																								?>
																							</tbody>
																					</table>







																			</div>



																			<div class="modal-footer">



																					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>







																			</div>



																			</form>



																		</div>







																	</div>



																</div>



															</div>
														<!--      FIN DETAIL BOUTIQUE  -->
													<?php endif ?>						
												</td>
											</tr>
												<?php 	}
												}
											}
									}else if($_SESSION['profil']=="Admin"){
										$sql4="SELECT * FROM `aaa-utilisateur` where idadmin=".$_SESSION['iduserBack'];
										$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
										while ($utilisateur = mysql_fetch_array($res4)){
										$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$utilisateur["matricule"]."' OR Accompagnateur='".$_SESSION['matricule']."'";
											$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
											while ($boutique = mysql_fetch_array($res2)) {
												$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
												$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
												while ($acces = mysql_fetch_array($res3)) {
													$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
													$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
													while ($utilisateur = mysql_fetch_array($res4)){
														//if($utilisateur['back']==1)
											?>
											<tr>
												<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<?php if ($boutique["activer"]==1){ ?>
															<td> Activer </td>
												<?php }else{ ?>
															<td> Désactiver </td>
												<?php } ?>
												<td>
													<a href="#" >
														<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" onclick="modBouti(<?php echo $boutique['idBoutique'] ; ?>)" /></a>&nbsp;&nbsp;
													<?php if ($boutique['activer']==0) { ?>
													<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" onclick="supBouti(<?php echo $boutique['idBoutique'] ; ?>)" /></a>
														<?php }else{ ?>
														<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
													<?php	} ?>
												</td>
												
										</tr>
											
									<?php 	}}}}
									}else if ($_SESSION['profil']=="Accompagnateur"){
											$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$_SESSION['matricule']."'";
												$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
												while ($boutique = mysql_fetch_array($res2)) {
													$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
													$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
													while ($acces = mysql_fetch_array($res3)) {
														$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
														$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
														while ($utilisateur = mysql_fetch_array($res4)){
															//if($utilisateur['back']==1)
													?>
													<tr>
														<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["datecreation"]; ?>  </td>
														<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
														<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>

														<?php if ($boutique['activer']==0) { ?>
																		<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" PPPPPPPPPPPPPPPPP  >
																		Activer</button>
																		</td>
																		<?php
																	} else { ?>
																		<td>
																		Activée</td>
																	<?php }
																	?>
													</tr>
											
											<!----------------------------------------------------------->
											
									<?php 	}}}}
									?>
								</tbody>
							</table>
						<?php else: ?>
							<table id="exemple5" class="display" border="1" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
									<!--  <th>Catalogue</th>
										<th>Stockage</th> --> 
										<th>Telephone</th> 
										<th></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
										<!-- <th>Catalogue</th>
										<th>Stockage</th> -->
										<th>Telephone</th>                         
										<th></th>                         
									</tr>
								</tfoot>
								<tbody>
								<?php
									if($_SESSION['profil']=="Assistant"){
										$sql2="SELECT * FROM `aaa-boutique`";
										$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
										while ($boutique = mysql_fetch_array($res2)) {
											$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin'";
											$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
											while ($acces = mysql_fetch_array($res3)) {
												$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur']." LIMIT 1";
												$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
												//int i=1;
												while ($utilisateur = mysql_fetch_array($res4)){
													//if($utilisateur['back']==1)
											?>
											<tr>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php echo  $boutique["telephone"]; ?>  </td>
												<!-- <td> <?php
													$nomtableDesignation=$boutique["nomBoutique"]."-designation";
													$sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
													$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
													if($compteur = mysql_fetch_array($res))
														echo  $compteur["nbreRef"]; ?>  
												</td>
												<td> <?php
													$nomtableStock=$boutique["nomBoutique"]."-stock";
													$sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant !=0";
													$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
													if($compteur = mysql_fetch_array($res))
														echo  $compteur["nbreStock"]; ?>  
												</td> -->
												<td>
														<!--      DEBUT DETAIL BOUTIQUE  -->
															<a href="" data-toggle="modal" <?php echo  "data-target=#detailB".$boutique['idBoutique'] ; ?>>
																	Detail
																	</a>
															<div class="modal fade bd-example-modal-lg" <?php echo  "id=detailB".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
																<div class="modal-dialog modal-lg" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																			<h4 class="modal-title" id="myModalLabel">Details boutique </h4>
																		</div>
																		<div class="modal-body">
																			<form name="formulaireVersement" method="post" action="boutiques.php">
																			<div class="form-group">
																				<h2>Listes des utilisateurs pour <?php echo  $boutique['labelB'] ; ?> </h2>
																					<table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
																							<thead>
																								<tr>
																									<th>Prénom</th>
																									<th>Nom</th>
																									<th>Email</th>
																									<th>Telépone</th>
																									<th>Propriétaire</th>
																									<th>Gérant</th>
																									<th>Caissier</th>
																									<th>Vendeur</th>
																								</tr>
																							</thead>
																							<tfoot>
																								<tr>
																									<th>Prénom</th>
																									<th>Nom</th>
																									<th>Email</th>
																									<th>Telépone</th>
																									<th>Propriétaire</th>
																									<th>Gérant</th>
																									<th>Caissier</th>
																									<th>Vendeur</th>
																								</tr>
																							</tfoot>
																							<tbody>
																								<?php
																									$sql4="SELECT *
																										FROM `aaa-acces`
																										WHERE idBoutique =".$boutique['idBoutique'];
																										$res4 = mysql_query($sql4) or die ("persoonel requête 4".mysql_error());
																										while ($acces = mysql_fetch_array($res4)) {
																											$sql5="SELECT *
																												FROM `aaa-utilisateur`
																												WHERE idutilisateur =".$acces['idutilisateur'];
																												if($res5 = mysql_query($sql5)) {
																													while($utilisateur = mysql_fetch_array($res5)) {
																													?>
																													<tr>
																														<td> <?php echo  $utilisateur['prenom']; ?>  </td>
																														<td> <?php echo  $utilisateur['nom']; ?>  </td>
																														<!--<td> <?php echo  $utilisateur['adresse']; ?>  </td>-->
																														<td> <?php echo  $utilisateur['email']; ?>  </td>
																														<td> <?php echo  $utilisateur['telPortable']; ?>  </td>
																														<td> <?php if ($acces['proprietaire']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['gerant']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['caissier']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['vendeur']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														</tr>
																									<?php   }
																									}
																									}
																								?>
																							</tbody>
																					</table>
																			</div>
																			<div class="modal-footer">
																					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
														<!--      FIN DETAIL BOUTIQUE  -->
												</td>
											</tr>
												<?php 	}
												}
											}
									}else if($_SESSION['profil']=="Admin"){
										$sql4="SELECT * FROM `aaa-utilisateur` where idadmin=".$_SESSION['iduserBack'];
										$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
										while ($utilisateur = mysql_fetch_array($res4)){
										$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$utilisateur["matricule"]."' OR Accompagnateur='".$_SESSION['matricule']."'";
											$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
											while ($boutique = mysql_fetch_array($res2)) {
												$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
												$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
												while ($acces = mysql_fetch_array($res3)) {
													$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
													$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
													while ($utilisateur = mysql_fetch_array($res4)){
														//if($utilisateur['back']==1)
											?>
											<tr>
												<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<?php if ($boutique["activer"]==1){ ?>
															<td> Activer </td>
												<?php }else{ ?>
															<td> Désactiver </td>
												<?php } ?>
												<td>
													<a href="#" >
														<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" onclick="modBouti(<?php echo $boutique['idBoutique'] ; ?>)" /></a>&nbsp;&nbsp;
													<?php if ($boutique['activer']==0) { ?>
													<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" onclick="supBouti(<?php echo $boutique['idBoutique'] ; ?>)" /></a>
														<?php }else{ ?>
														<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
													<?php	} ?>
												</td>
												
											</tr>
									<?php 	}}}}
									}else if ($_SESSION['profil']=="Accompagnateur"){
											$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$_SESSION['matricule']."'";
												$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
												while ($boutique = mysql_fetch_array($res2)) {
													$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
													$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
													while ($acces = mysql_fetch_array($res3)) {
														$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
														$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
														while ($utilisateur = mysql_fetch_array($res4)){
															//if($utilisateur['back']==1)
													?>
													<tr>
														<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["datecreation"]; ?>  </td>
														<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
														<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>	
														<?php if ($boutique['activer']==0) { ?>
																		<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
																		Activer</button>
																		</td>
																		<?php
																	} else { ?>
																		<td> Activée</td>
																	<?php }
																	?>
													</tr>
											
									<?php 	}}}}
									?>
								</tbody>
							</table>
						<?php endif ?>
					</div>
					<div class="tab-pane fade " id="PARAMETRESBOUTIQUES">
						<?php if ($_SESSION['profil']!="Assistant" && $_SESSION['profil']!="Accompagnateur"  ): ?>
							
							<!-- ........... -->

							<table id="exemple4" class="display" border="1" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
										<th>Mode</th>
										<th>PAIEMENT-FIXE</th>
									<!--<th>Vitrine</th>-->
										<th>Activer/Désactiver</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
										<th>Mode</th>
										<th>PAIEMENT-FIXE</th>
									<!--<th>Vitrine</th>-->
										<th>Activer/Désactiver</th>
									</tr>
								</tfoot>
								<tbody>
								<?php
									if($_SESSION['profil']=="SuperAdmin" OR  $_SESSION['profil']=="Assistant"){?>
										<!-- <div class="table-responsive">
											<div class="table-responsive"> 
													<label class="pull-left" for="nbEntreeAsAC">Nombre entrées </label>
													<select class="pull-left" id="nbEntreeAsAC">
													<optgroup>
														<option value="10">10</option>
														<option value="20">20</option>
														<option value="50">50</option>  
													</optgroup>       
													</select>
													<input class="pull-right" type="text" name="" id="searchInputAsAC" placeholder="Rechercher...">
													<div id="resultsBoutiqueAsAC"><!-- content will be loaded here --></div>
											</div>
										</div> -->
										
										<?php $sql2="SELECT * FROM `aaa-boutique`";
										$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
										while ($boutique = mysql_fetch_array($res2)) {
											$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin' order by idBoutique DESC LIMIT 1";
											//$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin' ";
											$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
											while ($acces = mysql_fetch_array($res3)) {
												$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur']." LIMIT 1";
												$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
												while ($utilisateur = mysql_fetch_array($res4)){
											?>
											<tr>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												
												<td>
													<?php if ($_SESSION['profil']!="Assistant"): ?>
														<?php if ($boutique['enConfiguration']==0) { ?>
															<a href="#">
															<span class="glyphicon glyphicon-cog" align="middle" alt="configuration"  data-toggle="modal" onclick="enConfiguration(<?php echo  $boutique['idBoutique'] ; ?> )" ></span>&nbsp;
															</a>
													<?php	}else{ ?>
																<a href="#">
																	<span class="glyphicon glyphicon-home" align="middle" alt="configuration"  data-toggle="modal" onclick="ApresConfiguration(<?php echo  $boutique['idBoutique'] ; ?>)" ></span>&nbsp;
																	</a>
													<?php	} ?>
													<?php if ($boutique['enTest']==0) { ?>
																<a href="#">
																<span class="glyphicon glyphicon-star-empty" align="middle" alt="test"  data-toggle="modal" onclick="EnTest(<?php echo  $boutique['idBoutique'] ; ?>)"></span>
																</a>
													<?php	}else{ ?>
																<a href="#">
																<span class="glyphicon glyphicon-star" align="middle" alt="configuration"  data-toggle="modal" onclick="ApresTest(<?php echo  $boutique['idBoutique'] ; ?>)" ></span>
																</a>
													<?php	} ?>
													<?php if ($boutique['compte']==0) { ?>
																<a href="#">
																<span class="glyphicon glyphicon-usd" align="middle" alt="test"  data-toggle="modal" onclick='SansCompte(<?php echo  $boutique["idBoutique"] ; ?> )' ></span>
																</a>
													<?php	}else{ ?>
																<a href="#">
																<span style="color:green;" class="glyphicon glyphicon-usd" align="middle" alt="configuration"  data-toggle="modal" onclick="AvecCompte(<?php echo $boutique['idBoutique'] ; ?>)"  ></span>
																</a>
													<?php	} ?>
														<?php if ($boutique['type']=="Pharmacie"): ?>
															<?php if ($boutique['mutuelle']==0) { ?>
																		<a href="#">
																		<span class="glyphicon glyphicon-asterisk" align="middle" alt="test"  data-toggle="modal" onclick="SansMutuelle(<?php echo  $boutique['idBoutique'] ; ?>)" ></span>
																		</a>
															<?php	}else{ ?>
																		<a href="#">
																		<span style="color:green;" class="glyphicon glyphicon-asterisk" align="middle" alt="configuration"  data-toggle="modal" onclick="AvecMutuelle(<?php echo  $boutique['idBoutique'] ; ?>)" ></span>
																		</a>
															<?php	} ?>
														<?php endif ?>
													<?php endif ?>					
												</td>
												<!--
																			<td>
																			<?php if ($boutique["vitrine"]==0) { ?>
																							<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverVitrine".$boutique['idBoutique'] ; ?> >
																							Activer</button>
																							<?php
																						} else { ?>
																							<button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverVitrine".$boutique['idBoutique'] ; ?> >
																							Desactiver</button>
																						<?php }
																						?>
																			</td>
												-->
												
													<?php if ($_SESSION['profil']!="Assistant"): ?>													
														<td>	<span> <?php  echo number_format($boutique['montantFixeHorsParametre'] , 0, ',', ' ').'' ; ?>	</span>
																<?php if ($boutique['montantFixeHorsParametre']==0) { ?>
																	<button type="button" class="btn btn-primary" class="btn btn-success" data-toggle="modal" onclick="paiemFixe(<?php echo  $boutique['idBoutique'] ; ?>)" >
																
																	Payer</button> 
																<?php } else { ?>
																	<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" onclick="paiemFixe(<?php echo  $boutique['idBoutique'] ; ?>)" >
															
																	Update </button> 
																<?php }	?>
															</button> 
															
														</td>
														<td>										
															<?php if ($boutique["activer"]==0) { ?>
																<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" onclick="activerBout(<?php echo  $boutique['idBoutique'] ; ?>)" >
																Activer</button>
																<?php
															} else { ?>
																<button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" onclick="desactiverBout(<?php echo  $boutique['idBoutique'] ; ?>)" >
																Desactiver</button>
															<?php }
															?>
														</td>
													<?php endif ?>
												
											</tr>		
												
												<?php 	}
												}
											}
									}else if($_SESSION['profil']=="Admin"){
										$sql4="SELECT * FROM `aaa-utilisateur` where idadmin=".$_SESSION['iduserBack'];
										$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
										while ($utilisateur = mysql_fetch_array($res4)){
										$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$utilisateur["matricule"]."' OR Accompagnateur='".$_SESSION['matricule']."'";
											$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
											while ($boutique = mysql_fetch_array($res2)) {
												$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
												$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
												while ($acces = mysql_fetch_array($res3)) {
													$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
													$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
													while ($utilisateur = mysql_fetch_array($res4)){
														//if($utilisateur['back']==1)
											?>
											<tr>
												<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<?php if ($boutique["activer"]==1){ ?>
															<td> Activer </td>
												<?php }else{ ?>
															<td> Désactiver </td>
												<?php } ?>
												<td>
													<a href="#" >
														<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
													<?php if ($boutique['activer']==0) { ?>
													<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
														<?php }else{ ?>
														<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
													<?php	} ?>
												</td>
												<?php 
														if ($boutique['activer']==0) { ?>
																<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
																Activer</button>
																</td>
																<?php
															} else { ?>
																<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
																Desactiver</button></td>
												<?php } ?>
										</tr>
									<?php 	}}}}
									}else if ($_SESSION['profil']=="Accompagnateur"){
											$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$_SESSION['matricule']."'";
												$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
												while ($boutique = mysql_fetch_array($res2)) {
													$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
													$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
													while ($acces = mysql_fetch_array($res3)) {
														$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
														$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
														while ($utilisateur = mysql_fetch_array($res4)){
															//if($utilisateur['back']==1)
													?>
													<tr>
														<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["datecreation"]; ?>  </td>
														<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
														<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>

														<?php if ($boutique['activer']==0) { ?>
																		<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
																		Activer</button>
																		</td>
																		<?php
																	} else { ?>
																		<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
																		Desactiver</button></td>
																	<?php }
																	?>
													</tr>
											
											<!----------------------------------------------------------->
											
									<?php 	}}}}
									?>
								</tbody>
							</table>
						<?php else: ?>
							<table id="exemple5" class="display" border="1" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
									<!--  <th>Catalogue</th>
										<th>Stockage</th> --> 
										<th>Telephone</th> 
										<th></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Nom Boutique</th>
										<th>Adresse</th>
										<th>Date de création</th>
										<th>Type & Catégorie</th>
										<!-- <th>Catalogue</th>
										<th>Stockage</th> -->
										<th>Telephone</th>                         
										<th></th>                         
									</tr>
								</tfoot>
								<tbody>
								<?php
									if($_SESSION['profil']=="Assistant"){
										$sql2="SELECT * FROM `aaa-boutique`";
										$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
										while ($boutique = mysql_fetch_array($res2)) {
											$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin'";
											$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
											while ($acces = mysql_fetch_array($res3)) {
												$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur']." LIMIT 1";
												$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
												//int i=1;
												while ($utilisateur = mysql_fetch_array($res4)){
													//if($utilisateur['back']==1)
											?>
											<tr>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php echo  $boutique["telephone"]; ?>  </td>
												<!-- <td> <?php
													$nomtableDesignation=$boutique["nomBoutique"]."-designation";
													$sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
													$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
													if($compteur = mysql_fetch_array($res))
														echo  $compteur["nbreRef"]; ?>  
												</td>
												<td> <?php
													$nomtableStock=$boutique["nomBoutique"]."-stock";
													$sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant !=0";
													$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
													if($compteur = mysql_fetch_array($res))
														echo  $compteur["nbreStock"]; ?>  
												</td> -->
												<td>
														<!--      DEBUT DETAIL BOUTIQUE  -->
															<a href="" data-toggle="modal" <?php echo  "data-target=#detailB".$boutique['idBoutique'] ; ?>>
																	Detail
																	</a>
															<div class="modal fade bd-example-modal-lg" <?php echo  "id=detailB".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
																<div class="modal-dialog modal-lg" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																			<h4 class="modal-title" id="myModalLabel">Details boutique </h4>
																		</div>
																		<div class="modal-body">
																			<form name="formulaireVersement" method="post" action="boutiques.php">
																			<div class="form-group">
																				<h2>Listes des utilisateurs pour <?php echo  $boutique['labelB'] ; ?> </h2>
																					<table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
																							<thead>
																								<tr>
																									<th>Prénom</th>
																									<th>Nom</th>
																									<th>Email</th>
																									<th>Telépone</th>
																									<th>Propriétaire</th>
																									<th>Gérant</th>
																									<th>Caissier</th>
																									<th>Vendeur</th>
																								</tr>
																							</thead>
																							<tfoot>
																								<tr>
																									<th>Prénom</th>
																									<th>Nom</th>
																									<th>Email</th>
																									<th>Telépone</th>
																									<th>Propriétaire</th>
																									<th>Gérant</th>
																									<th>Caissier</th>
																									<th>Vendeur</th>
																								</tr>
																							</tfoot>
																							<tbody>
																								<?php
																									$sql4="SELECT *
																										FROM `aaa-acces`
																										WHERE idBoutique =".$boutique['idBoutique'];
																										$res4 = mysql_query($sql4) or die ("persoonel requête 4".mysql_error());
																										while ($acces = mysql_fetch_array($res4)) {
																											$sql5="SELECT *
																												FROM `aaa-utilisateur`
																												WHERE idutilisateur =".$acces['idutilisateur'];
																												if($res5 = mysql_query($sql5)) {
																													while($utilisateur = mysql_fetch_array($res5)) {
																													?>
																													<tr>
																														<td> <?php echo  $utilisateur['prenom']; ?>  </td>
																														<td> <?php echo  $utilisateur['nom']; ?>  </td>
																														<!--<td> <?php echo  $utilisateur['adresse']; ?>  </td>-->
																														<td> <?php echo  $utilisateur['email']; ?>  </td>
																														<td> <?php echo  $utilisateur['telPortable']; ?>  </td>
																														<td> <?php if ($acces['proprietaire']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['gerant']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['caissier']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														<td> <?php if ($acces['vendeur']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
																														</tr>
																									<?php   }
																									}
																									}
																								?>
																							</tbody>
																					</table>
																			</div>
																			<div class="modal-footer">
																					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
														<!--      FIN DETAIL BOUTIQUE  -->
												</td>
											</tr>
												<?php 	}
												}
											}
									}else if($_SESSION['profil']=="Admin"){
										$sql4="SELECT * FROM `aaa-utilisateur` where idadmin=".$_SESSION['iduserBack'];
										$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
										while ($utilisateur = mysql_fetch_array($res4)){
										$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$utilisateur["matricule"]."' OR Accompagnateur='".$_SESSION['matricule']."'";
											$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
											while ($boutique = mysql_fetch_array($res2)) {
												$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
												$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
												while ($acces = mysql_fetch_array($res3)) {
													$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
													$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
													while ($utilisateur = mysql_fetch_array($res4)){
														//if($utilisateur['back']==1)
											?>
											<tr>
												<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
												<td> <?php echo  $boutique["labelB"]; ?>  </td>
												<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
												<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
												<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
												<td> <?php echo  $boutique["datecreation"]; ?>  </td>
												<?php if ($boutique["activer"]==1){ ?>
															<td> Activer </td>
												<?php }else{ ?>
															<td> Désactiver </td>
												<?php } ?>
												<td>
													<a href="#" >
														<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
													<?php if ($boutique['activer']==0) { ?>
													<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
														<?php }else{ ?>
														<a href="#">
														<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
													<?php	} ?>
												</td>
												<?php 
															if ($boutique['activer']==0) { ?>
																<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
																Activer</button>
																</td>
																<?php
															} else { ?>
																<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
																Desactiver</button></td>
												<?php } ?>
											</tr>
									<?php 	}}}}
									}else if ($_SESSION['profil']=="Accompagnateur"){
											$sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$_SESSION['matricule']."'";
												$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
												while ($boutique = mysql_fetch_array($res2)) {
													$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
													$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
													while ($acces = mysql_fetch_array($res3)) {
														$sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
														$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
														while ($utilisateur = mysql_fetch_array($res4)){
															//if($utilisateur['back']==1)
													?>
													<tr>
														<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
														<td> <?php echo  $boutique["datecreation"]; ?>  </td>
														<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
														<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>	
														<?php if ($boutique['activer']==0) { ?>
																		<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
																		Activer</button>
																		</td>
																		<?php
																	} else { ?>
																		<td> Activée</td>
																	<?php }
																	?>
													</tr>
											
									<?php 	}}}}
									?>
								</tbody>
							</table>
						<?php endif ?>
					</div>
					<div class="tab-pane fade " id="LISTECUTILISATEUR" >
						<div class="table-responsive">
							
							<!--  -->
								<div class="table-responsive"> 
										<label class="pull-left" for="nbEntreeUB">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeUB">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputUB" placeholder="Rechercher...">
										<div id="resultsProductsUB"><!-- content will be loaded here --></div>
								</div>
						</div>
					</div>
					<div class="tab-pane fade " id="PROSPECT" >
						<br>
						
						<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addProspectUser">
                            <i class="glyphicon glyphicon-plus"></i>Nouveau Client
                		</button>
						<div class="table-responsive"> 
							<label class="pull-left" for="nbEntreeProspect">Nombre entrées </label>
							<select class="pull-left" id="nbEntreeProspect">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option>  
								</optgroup>       
							</select>
							<input class="pull-right" type="text" name="" id="searchInputProspect" placeholder="Rechercher...">
							<div id="resultsProspect"><!-- content will be loaded here --></div>
						</div>
						
					</div>
					<div class="tab-pane fade " id="EVENTS" >
						<div class="table-responsive"> 
							<label class="pull-left" for="nbEntreeEvent">Nombre entrées </label>
							<select class="pull-left" id="nbEntreeEvent">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option>  
								</optgroup>       
							</select>
							<input class="pull-right" type="text" name="" id="searchInputEvent" placeholder="Rechercher...">
							<div id="resultsEvent"><!-- content will be loaded here --></div>
						</div>
					</div>
	   </div>
	</div>
</body>
</html>


