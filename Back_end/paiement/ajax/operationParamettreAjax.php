<?php 

session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');
require('../../declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;

$dateHeures=$dateString.' '.$heureString;

if (isset($_POST['operation']) and $_POST['operation']=='chercherParam') {
        $idV=$_POST['i'];
        $sqla = $bdd->prepare("SELECT * FROM  `aaa-variablespayement`  WHERE idvariable=:idv");     
        $sqla->execute(array('idv'=>$idV));  
        $variable= $sqla->fetch();
        $result=$variable['idvariable'].'<>'.$variable['nomvariable'].'<>'.$variable['typecaisse'].'<>'.$variable['categoriecaisse'].'<>'.
                $variable['moyenneVolumeMin'].'<>'.$variable['moyenneVolumeMax'].'<>'.$variable['montant'].'<>'.$variable['prixLigne'].'<>'.
                $variable['pourcentage'].'<>'.$variable['minmontant'].'<>'.$variable['maxmontant'].'<>'.$variable['activerMontant'].'<>'.
                $variable['activerPourcentage'].'<>'.$variable['activerPrix'];
        exit($result);   
}
elseif (isset($_POST['btnEnregistrerVarParam'])) {

		$nomV=htmlspecialchars(trim($_POST['nomV']));
		$type=$_POST['type'];
		$categorie=$_POST['categorie'];
		$moyenneVolumeMin=$_POST['moyenneVolumeMin'];
		$moyenneVolumeMax=$_POST['moyenneVolumeMax'];
		$montantFixe=$_POST['montantFixe'];
		$pourcentage=$_POST['pourcentage'];
		$prixInsertion=$_POST['prixInsertion'];
		$montantMin=$_POST['montantMin'];
		$montantMax=$_POST['montantMax'];


		if ($nomV) {
			
			   $req6 = $bdd->prepare("insert into `aaa-variablespayement`(
								nomvariable,
								typecaisse,
								categoriecaisse,
								moyenneVolumeMin,
								moyenneVolumeMax,
								montant,
								pourcentage,
								prixLigne,
								minmontant,
								maxmontant,
								activerMontant,
								activerPourcentage,
								activerPrix) 
			   values (:nomvariable,:typecaisse,:categoriecaisse,:moyenneVolumeMin,:moyenneVolumeMax,:montant,:pourcentage,:prixLigne,:minmontant,:maxmontant,:activerMontant,:activerPourcentage,:activerPrix)");
	$req6->execute(array(
		   'nomvariable' =>$nomV,
		   'typecaisse' =>$type,
		   'categoriecaisse' =>$categorie,
		   'moyenneVolumeMin' =>$moyenneVolumeMin,
		   'moyenneVolumeMax' =>$moyenneVolumeMax,
		   'montant' =>$montantFixe,
		   'pourcentage' =>$pourcentage,
		   'prixLigne' =>$prixInsertion,
		   'minmontant' =>$montantMin,
		   'maxmontant' =>$montantMax,
		   'activerMontant' =>0,
		   'activerPourcentage' =>0,
		   'activerPrix' =>0
		   ))  or die(print_r($req6->errorInfo()));


			$message="Utilisateur ajouter avec succes";
		} else{
			$message="mot de pass different";
		}

}elseif (isset($_POST['btnModifierVarParam'])) {

		$nomV=htmlspecialchars(trim($_POST['nomV']));
		$type=$_POST['type'];
		$categorie=$_POST['categorie'];
		$moyenneVolumeMin=$_POST['moyenneVolumeMin'];
		$moyenneVolumeMax=$_POST['moyenneVolumeMax'];
		$montantFixe=$_POST['montantFixe'];
		$pourcentage=$_POST['pourcentage'];
		$prixInsertion=$_POST['prixInsertion'];
		$montantMin=$_POST['montantMin'];
		$montantMax=$_POST['montantMax'];
		$idvariable=$_POST['idvariable'];

		$nomVInitial=htmlspecialchars(trim($_POST['nomVInitial']));
		$typeInitial=$_POST['typeInitial'];
		$categorieInitial=$_POST['categorieInitial'];
		$moyenneVolumeMinInitial=$_POST['moyenneVolumeMinInitial'];
		$moyenneVolumeMaxInitial=$_POST['moyenneVolumeMaxInitial'];
		$montantFixeInitial=$_POST['montantFixeInitial'];
		$pourcentageInitial=$_POST['pourcentageInitial'];
		$prixInsertionInitial=$_POST['prixInsertionInitial'];
		$montantMinInitial=$_POST['montantMinInitial'];
		$montantMaxInitial=$_POST['montantMaxInitial'];



if(($nomV==$nomVInitial)&&($type==$typeInitial)&&($categorie==$categorieInitial)&&($moyenneVolumeMin==$moyenneVolumeMinInitial)&&($moyenneVolumeMax==$moyenneVolumeMaxInitial)&&($montantFixe==$montantFixeInitial)&&($pourcentage==$pourcentageInitial)&&($prixInsertion==$prixInsertionInitial)&&($montantMin==$montantMinInitial)&&($montantMax==$montantMaxInitial))
	echo '<script type="text/javascript"> alert("INFO : AUCUNE MODIFICATION POUR CETTE VARIABLE ...");</script>';
else{
	/* $sql3="UPDATE `aaa-variablespayement` set  `nomvariable`='".mysql_real_escape_string($nomV)."',typecaisse='".$type."',categoriecaisse='".$categorie."',
	moyenneVolumeMin=".$moyenneVolumeMin.",moyenneVolumeMax=".$moyenneVolumeMax.",montant=".$montantFixe.",
	pourcentage=".$pourcentage.",prixLigne=".$prixInsertion.",minmontant=".$montantMin.",maxmontant=".$montantMax." where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
 */
	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  nomvariable=:nv,typecaisse=:tc,categoriecaisse=:cc,
									moyenneVolumeMin=:myvMin,moyenneVolumeMax=:myvMax,montant=:mnt ,
									pourcentage=:prc,prixLigne=:pl,minmontant=:minM,maxmontant=:maxM where idvariable=:idv");
	$res6=$req6->execute(array( 'nv' => $nomV,
								'tc' => $type,
								'cc' => $categorie,
								'myvMin' => $moyenneVolumeMin,
								'myvMax' => $moyenneVolumeMax,
								'mnt' => $montantFixe,
								'prc' => $pourcentage,
								'pl' => $prixInsertion,
								'minM' => $montantMin,
								'maxM' => $montantMax,
								'idv' => $idvariable)) or die(print_r($req6->errorInfo()));

	}

}elseif (isset($_POST['btnSupprimerVariable'])) {

	$idvariable=$_POST['idvariable'];

	  $req6 = $bdd->prepare("DELETE FROM `aaa-variablespayement` where idvariable=:idv");
	  $res6=$req6->execute(array( 'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	  exit($res6);
}
if (isset($_POST['btnActiver'])) {
	$idvariable=$_POST['idvariable'];
	$activer=1;
	
	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  activerMontant=:act,activerPourcentage=:apc,activerPrix=:apx where idvariable=:idv");
	$res6=$req6->execute(array( 'act' => $activer,
						  'apc' => 0,
						  'apx' => 0,
						  'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	exit($res6);

} elseif (isset($_POST['btnDesactiver'])) {
	$idvariable=$_POST['idvariable'];
	$activer=0;

	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  activerMontant=:act,activerPourcentage=:apc,activerPrix=:apx where idvariable=:idv");
	$res6=$req6->execute(array( 'act' => $activer,
						  'apc' => 0,
						  'apx' => 0,
						  'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	exit($res6);
	
}
if (isset($_POST['btnActiver2'])) {
	$idvariable=$_POST['idvariable'];
	$activer=1;
	
	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  activerMontant=:act,activerPourcentage=:apc,activerPrix=:apx where idvariable=:idv");
	$res6=$req6->execute(array( 'act' =>0,
						  'apc' =>  $activer,
						  'apx' => 0,
						  'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	exit($res6);

} elseif (isset($_POST['btnDesactiver2'])) {
	$idvariable=$_POST['idvariable'];
	$activer=0;

	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  activerMontant=:act,activerPourcentage=:apc,activerPrix=:apx where idvariable=:idv");
	$res6=$req6->execute(array( 'act' => 0,
						  'apc' => $activer,
						  'apx' => 0,
						  'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	exit($res6);
}
if (isset($_POST['btnActiver3'])) {
	$idvariable=$_POST['idvariable'];
	$activer=1;
	
	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  activerMontant=:act,activerPourcentage=:apc,activerPrix=:apx where idvariable=:idv");
	$res6=$req6->execute(array( 'act' =>0,
						  'apc' =>  0,
						  'apx' => $activer,
						  'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	exit($res6);

} elseif (isset($_POST['btnDesactiver3'])) {
	$idvariable=$_POST['idvariable'];
	$activer=0;
	$req6 = $bdd->prepare("UPDATE `aaa-variablespayement` set  activerMontant=:act,activerPourcentage=:apc,activerPrix=:apx where idvariable=:idv");
	$res6=$req6->execute(array( 'act' =>0,
						  'apc' =>  0,
						  'apx' => $activer,
						  'idv' => $idvariable)) or die(print_r($req6->errorInfo()));
	exit($res6);
}

?>