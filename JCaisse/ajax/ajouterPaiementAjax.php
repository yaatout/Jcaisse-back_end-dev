
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
	$qrCode="";
	
	if ($tab['montantFixePayement']) {
		

		$client_id = '7214d4ac-db73-4ff8-ae5d-f7b69c54000b';
		// $client_id = 'd94f170f-3f61-4871-bd56-e4bfd97f2ecb';
		$client_secret = 'ae77d6e2-f323-4ef1-a62d-a5e78d3cf243';
		// $client_secret = '1c5d3406-3fc6-40ec-8c4d-4c160414f72b';
	
		$url = "https://api.orange-sonatel.com/oauth/token";
	
		$body = 'client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=client_credentials';
	
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL,$url);
		curl_setopt($c, CURLOPT_POST, true);
		// curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($c, CURLOPT_POSTFIELDS, $body);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec($c);
		// echo $page;
	
		$response = json_decode($page, true);
	
		$my_token = $response['access_token'];
		// var_dump($response['access_token']);
		// var_dump("2/".$response.access_token);
	
		if(curl_errno($c)) {
		  $error_msg = curl_error($c);
		  echo $error_msg;
		}
		curl_close($c);

		$authorization = "Authorization: Bearer ".$my_token;

		
		$urlQR = "https://api.orange-sonatel.com/api/eWallet/v4/qrcode";

		// $metadata=json_encode([]);
		// var_dump($metadata);
	
		$dataQR = [
					"amount" => [
						"unit" => "XOF",
						"value" => $tab['montantFixePayement']
					],
					"callbackCancelUrl" => "https://jcaisse.org/JCaisse/wave_error.php",
					"callbackSuccessUrl" => "https://jcaisse.org/JCaisse/wave_success.php",					
					"code" => 493691,
					"metadata" => [
						"idB" => $tab['idBoutique'],
						"idPS" => $tab['idPS']
					],
					"name" => "Yaatout",
					"validity" => 60000
				];
		
		// var_dump(json_encode($dataQR));
		
		$cQR = curl_init($urlQR);
		// curl_setopt($cQR, CURLOPT_URL,$urlQR);
		curl_setopt($cQR, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cQR, CURLOPT_POST, true);
		curl_setopt($cQR, CURLOPT_POSTFIELDS, json_encode($dataQR));
		curl_setopt($cQR, CURLOPT_HTTPHEADER, [
			$authorization,
			'Content-Type: application/json'
		]);
		$pageQR = curl_exec($cQR);
	
		$responseQR = json_decode($pageQR, true);
		// var_dump($responseQR);
	
		if(curl_errno($cQR)) {
			$error_msg = curl_error($cQR);
			// echo $error_msg;
		}
		curl_close($cQR);
	
		$qrCode = $responseQR['qrCode'];
	}

	$result=$tab['idPS'].'<<>>'.$tab['idBoutique'].'<<>>'.$tab['montantFixePayement'].' '.$_SESSION['symbole'].'<<>>'.$periode.'<<>>'.$tab['refTransfert'].'<<>>'.$tab['telRefTransfert'].'<<>>'.$qrCode;
	//$result=$idPS.'+'.$operation;
	exit($result);
}

if($operation == 2) {
	if (($refTransf!="")&&($numTel!="")){
	$sql="update `aaa-payement-salaire` set refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."', datePaiement='".$dateString."', heurePaiement='".$heureString."' where idPS=".$idPS;
	$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
	
	$sql4="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";
	$res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
	
	if(@mysql_num_rows($res4)){ 
		
		$paiement = mysql_fetch_array($res4);
		$activer=1;
		
		$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idPS=".$idPS;
		$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas ".mysql_error());
		
		$sql6="UPDATE `aaa-payement-reference` set  idPS=".$idPS." where refTransfertValidation='".$refTransf."'";
		$res6=@mysql_query($sql6) or die ("mise à jour validation ou pas ".mysql_error());
		
		}else{
			//echo "Références transferts non conformes.";
		}
	}
	
	exit($refTransf);
}