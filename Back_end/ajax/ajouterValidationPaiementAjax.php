
<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
}

require('../connection.php');

require('../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString =$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;

/**Fin informations sur la date d'Aujourdhui **/

$idPS      =@$_POST["idPS"];
$operation =@$_POST["operation"];
$refTransf =@htmlspecialchars(@$_POST["refTransf"]);
$numTel    =@htmlspecialchars(@$_POST["numTel"]);

if($operation == 1){

	$sql="select * from `aaa-payement-salaire` where idPS=".$idPS;

	$res=@mysql_query($sql);
	
	$tab=@mysql_fetch_array($res);
	
	$date = new DateTime($tab["datePS"]);
	$anneePS =$date->format('Y');
	$moisPS =$date->format('m');
	$jourPS =$date->format('d');
	$dateStringPS=$anneePS.''.$moisPS;
	$periode=$moisPS.'-'.$anneePS;
	$estimationDatePaiement=$anneePS.'-0'.($moisPS+1).'-01';

	$result=$tab['idPS'].'+'.$tab['idBoutique'].'+'.$tab['montantFixePayement'].' F CFA+'.$periode.'+'.$tab['refTransfert'].'+'.$tab['telRefTransfert'];
	
	exit($result);
}

if($operation == 2){
	if (($refTransf!="")&&($numTel!="")){
	$sql="insert into `aaa-payement-reference` set refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."', datePaiement='".$dateString."', heurePaiement='".$heureString."' where idPS=".$idPS;
	$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
	
	$sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
	$res4 =@mysql_query($sql4) or die ("acces requête 4".mysql_error());
	
	if(@mysql_num_rows($res4)){
		
		$paiement =@mysql_fetch_array($res4);
		$activer=1;
		
		$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
		$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas ".mysql_error());
		
		$sql6="UPDATE `aaa-payement-reference` set  idPS=".$idPS." where refTransfertValidation='".$refTransf."'";
		$res6=@mysql_query($sql6) or die ("mise à jour validation ou pas ".mysql_error());
		
		}else{
			echo "Références transferts non conformes.";
		}
	}
	
	exit($refTransf);
}