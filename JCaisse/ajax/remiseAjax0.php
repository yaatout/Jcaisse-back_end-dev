<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');


	$idPagnet=$_POST['prm'];
	$idClient=@$_POST['idClient'];
	$sql1="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
	$res1=mysql_query($sql1) or die ("select stock impossible =>".mysql_error());
	$pagnet = mysql_fetch_array($res1);
	
	$totalp=$pagnet['totalp'];
	$remise=$pagnet['remise'];
	$versement=$pagnet['versement'];

  if (isset($_POST['btnImprimerFacture'])) {

			
			//

			if (isset($_POST['rms']) && isset($_POST['aPP'])) {
				// code...
				

				/*$totalp=$_POST['ttp'];*/
				$remise=$_POST['rms'];
				$versement=$_POST['aPP'];
				
				if($remise>=$totalp){
					$apayerPagnet=$totalp;
					$restourne=$versement-$apayerPagnet;
					$remise=0;
					$sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1, remise=".$remise.",apayerPagnet=".$apayerPagnet.",restourne=".$restourne.",versement=".$versement." where idPagnet=".$idPagnet;
					$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
					die($sql3);
				}else{
					$apayerPagnet=$totalp-$remise;
					$restourne=$versement-$apayerPagnet;

					$sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1, remise=".$remise.",apayerPagnet=".$apayerPagnet.",restourne=".$restourne.",versement=".$versement." where idPagnet=".$idPagnet;
					$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
					die($sql3);
				}

				

			}else {
				// code...
                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1,apayerPagnet=".$totalp." where idPagnet=".$_POST['idPagnet'];
		        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
				die($sql3);
			}
			
			$sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
			$res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
			$Total = mysql_fetch_array($res18) ;
			$sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString2."' where idClient=".$idClient;
			$res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
      exit('nice');
  } 
  if (isset($_POST['btnImprimerFacturePagnet'])) {

			
			//

			if (isset($_POST['rms'])) {
				// code...
				/*
					$totalp=$_POST['ttp'];*/
					$idPagnet=$_POST['prm'];
					$remise=$_POST['rms'];
				

				$apayerPagnet=$totalp-$remise;

				$sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1, remise=".$remise.",apayerPagnet=".$apayerPagnet." where idPagnet=".$idPagnet;
		    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
				die($sql3);

			}else {
				// code...
                $sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1,apayerPagnet=".$totalp." where idPagnet=".$_POST['idPagnet'];
		        $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
				die($sql3);

			}
      exit('nice');
  }
  if (isset($_POST['verrouiller'])) {
    // code...
    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".$_POST['prm'];
      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
      die('verrouiller succes');
  }

?>
