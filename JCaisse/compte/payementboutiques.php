<?php
/*
Résum� :
Commentaire :
Version : 2.1
see also :
Auteur : Ibrahima DIOP
Date de cr�ation : 5-08-2019
Date derni�re modification :  17-10-2019
*/

session_start();

require('connection.php');

require('../declarationVariables.php');

// if(!$_SESSION['iduserBack'])
// 	header('Location:index.php');


$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;

$dateHeures=$dateString.' '.$heureString;

if (isset($_POST['payementBoutiques'])) {
	//$date = new DateTime('25-02-2011');
	$date = new DateTime();
	//R�cup�ration de l'ann�e
	$annee =$date->format('Y');
	//R�cup�ration du mois
	$mois =$date->format('m');
	//R�cup�ration du jours
	$jour =$date->format('d');
		$heureString=$date->format('H:i:s');

	$dateString=$annee.'-'.$mois.'-'.$jour;
	$dateString2=$jour.'-'.$mois.'-'.$annee;
	//var_dump($dateString2);

	//les boutiques en phase d'esploitation
	$sql2="SELECT * FROM `aaa-boutique` WHERE enTest =1";
	$res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
    //var_dump("1");
	while ($boutique = mysql_fetch_array($res2)) {
		//volume moyenne des données de chaque boutique

			$volumeMoyenne=0;

			$tailleCatal=0;
			$nomtableDesignation=$boutique["nomBoutique"]."-designation";
			$sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
			$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
			if($compteur = mysql_fetch_array($res))
				$tailleCatal=$compteur["nbreRef"];
                //var_dump("2");

			$tailleStocks=0;
			$nomtableStock=$boutique["nomBoutique"]."-stock";
			$sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=0";
			$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
			if($compteur = mysql_fetch_array($res))
				$tailleStocks=$compteur["nbreStock"];
			 //var_dump("3");
			$volumeMoyenne=	($tailleCatal+$tailleStocks)/2;


		//recherche des varibles de Paiement de chaque boutique
		$sql3="SELECT * FROM `aaa-variablespayement` where typecaisse='".$boutique["type"]."' and categoriecaisse='".$boutique["categorie"]."' and moyenneVolumeMin<=".$volumeMoyenne." and moyenneVolumeMax>=".$volumeMoyenne;
		//echo $sql3;
		$res3 = @mysql_query($sql3) or die ("acces requête 3".mysql_error());

		//        echo '<pre>';
		//		  var_dump($sql3);
		//        echo '</pre>';
		/*    le nombre de mois entre deux dates    */

		$datetime1 = new DateTime($boutique["datecreation"]);
		$annee1 =$datetime1->format('Y');
		$mois1 =$datetime1->format('m');

		$datetime2 = new DateTime($dateString);
		$annee2 =$datetime2->format('Y');
		$mois2 =$datetime2->format('m');

		$etapeAccompagnement = ($mois2-$mois1)+12*($annee2-$annee1)+1;




	/*    le nombre de mois entre deux dates    */


		if($variable = @mysql_fetch_array($res3)){
            //var_dump("4");
		$sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) and idBoutique=".$boutique["idBoutique"];
		//             echo '<pre>';
		//                  var_dump($sql3);
		//                echo '</pre>';
		$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
            if(@mysql_num_rows($res3)){

                 //var_dump("9");
                if($variable["activerMontant"]==1){
    				//var_dump("10");
                    if($etapeAccompagnement==1){
                            $partAccompagnateur=$variable["montant"]*50/100;
                        }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                            $partAccompagnateur=$variable["montant"]*20/100;
                        }

                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=1,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) "  ;
                    //echo $sql6.'</br>';
                    $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
                    //var_dump($sql6);

                }else if($variable["activerPourcentage"]==1){
    				//var_dump("11");
                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=2,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) " ;
                    //echo $sql6.'</br>';
                    $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                }else if($variable["activerPrix"]==1){
    				//var_dump("12");
                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=3,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) " ;
                    //echo $sql6.'</br>';
                    $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
                }

            }else{

                    //var_dump("5");
                    if($variable["activerMontant"]==1){
        				//var_dump("6");
                        if($etapeAccompagnement==1){
                            $partAccompagnateur=$variable["montant"]*50/100;
                        }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                            $partAccompagnateur=$variable["montant"]*20/100;
                        }

                        $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement) values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",1,".$etapeAccompagnement.")";
                        //echo $sql6.'</br>';
                        $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                          //var_dump($sql6);

                    }elseif($variable["activerPourcentage"]==1){
                            //var_dump("7");
                            $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement) values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",2,".$etapeAccompagnement.")";
                            //echo $sql6.'</br>';
                            $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                    }elseif($variable["activerPrix"]==1){
        				//var_dump("8");
                        $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement) values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",3,".$etapeAccompagnement.")";
                        //echo $sql6.'</br>';
                        $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                    }

            }

		}

	}
}

if (isset($_POST['btnReferencetransfertsOrange'])) {

	$dateTransfert=$_POST['dateTransfert'];
	$montantTransfert=$_POST['montantTransfert'];
	$refTransf=$_POST['refTransf'];
	$numTel=$_POST['numTel'];
	$typeCompteMobile='Orange Money';
	$avecFrais=0;
	$frais=0;
    if(isset($_POST['avecFrais'])){
        $avecFrais=1;
    }
    if(isset($_POST['frais'])){
        $frais=$_POST['frais'];
    }
	$activer=1;
    $montantNonConforme=1;
	if (($refTransf!="")&&($numTel!="")){
		$sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";
		//var_dump('1');
		$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res2)){
			$sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,avecFrais,frais) values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."','".$avecFrais."','".$frais."')";
			//var_dump('2');
			$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas1 ".mysql_error());

                    /**********************************TABLE COMPTE *****************************************/
                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
		                  $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
                          $payementReference =mysql_fetch_assoc($res8);

                          $sqlv="select * from `aaa-compte` where nomCompte=$typeCompteMobile";
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          //var_dump($compte);
                          if($compte){
                          		//var_dump('3');
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransf;
                              $newMontant=$compte['montantCompte']+$montantTransfert;

                              $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) values('".$montantTransfert."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
                              //var_dump($sql6);
                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
                              //var_dump($sql7);
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                          }
                    /********************************TABLE COMPTE **************************************/

			$sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
			$res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
			if(@mysql_num_rows($res4)){
				//var_dump('4');
				$paiement = mysql_fetch_array($res4);
				if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
					//var_dump('5');
					$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
					$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

					$sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
					$res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

				}else{
					//var_dump('6');
                     $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                    //var_dump($sql5);
				    $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
				}
			}

		}
	}
}

if (isset($_POST['btnReferencetransfertsWave'])) {
	$dateTransfert=$_POST['dateTransfert'];
	$montantTransfert=$_POST['montantTransfert'];
	$refTransf='Sans reference';
	$numTel=$_POST['numTel'];
	$typeCompteMobile='Wave'; 
	$activer=1;
    $montantNonConforme=1;
    if(isset($_POST['refTransf'])){
    	if ($_POST['refTransf'] !='') {
    		// code...
        	$refTransf=$_POST['refTransf'];
    	}
    }
	if (($refTransf!="")&&($numTel!="")){
		$sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";

		$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res2)){
			$sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation) 
			values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."')";
			//var_dump($sql3);
			$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas wave ".mysql_error());

                    /**********************************TABLE COMPTE *****************************************/
                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
		                  $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
                          $payementReference =mysql_fetch_assoc($res8);

                          $sqlv="select * from `aaa-compte` where nomCompte='".$typeCompteMobile."'";
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          if($compte){
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransf;
                              $newMontant=$compte['montantCompte']+$montantTransfert;

                              $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) values('".$montantTransfert."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
                              //var_dump($sql6);
                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
                              //var_dump($sql7);
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                          }
                    /********************************TABLE COMPTE **************************************/

			$sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
			$res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
			if(@mysql_num_rows($res4)){
				$paiement = mysql_fetch_array($res4);
				if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
					$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
					$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

					$sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
					$res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

				}else{
                     $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                    //var_dump($sql5);
				    $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
				}
			}

		}
	}
}


if (isset($_POST['btnValidationP'])) {

	$idPS=$_POST['idPS'];
	$idBoutique=$_POST['idBoutique'];

	$datePaiement=$_POST['datePaiement'];
	$montantFixePayement=$_POST['montantFixePayement'];
	$refTransf=$_POST['refTransf'];
	$numTel=$_POST['numTel'];

	$activer=1;
	$montantNonConforme=1;
    $sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";

		$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res2)){

            if (($refTransf!="")&&($numTel!="")){
                $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS) values('".$dateString."',".$montantFixePayement.",'".$refTransf."','".$numTel."',".$idPS.")";
                $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas4 ".mysql_error());

                $sql4="SELECT * FROM `aaa-payement-salaire` where idPS=".$idPS;
                $res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
                if(@mysql_num_rows($res4)){
                    $paiement = mysql_fetch_array($res4);
                    if ($paiement['montantFixePayement']==$montantFixePayement){
                        $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idPS=".$idPS;
                        $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error());
                    }else{
                        $sql5="UPDATE `aaa-payement-reference` set  montantNonConforme='".$montantNonConforme."' where idPS=".$idPS;
                        $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error());
                    }
                }
            }
        }
}
if (isset($_POST['btnValidationPWave'])) {

	$idPS=$_POST['idPS'];
	$idBoutique=$_POST['idBoutique'];

	$datePaiement=$_POST['datePaiement'];
	$montantFixePayement=$_POST['montantFixePayement'];
	//$refTransf=$_POST['refTransf'];
	$refTransf="Sans reference".$idPS;
	//$numTel=$_POST['numTel'];
	$numTel="0000000";

	$activer=1;
	$montantNonConforme=1;

	$sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";

	$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
	if(!@mysql_num_rows($res2)){

					if ($refTransf!=""){
							$sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS) values('".$dateString."',".$montantFixePayement.",'".$refTransf."','".$numTel."',".$idPS.")";
							$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas4 ".mysql_error());

							$sql4="SELECT * FROM `aaa-payement-salaire` where idPS=".$idPS;
							$res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
							if(@mysql_num_rows($res4)){
									$paiement = mysql_fetch_array($res4);
											$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idPS=".$idPS;
											$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error());

											$sql="UPDATE `aaa-payement-salaire` set refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."',
													datePaiement='".$dateString."', heurePaiement='".$heureString."', aPayementBoutique='".$activer."'  where idPS=".$idPS;
											$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
							}
					}
			}
    /*$sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";

		$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res2)){

            if (($refTransf!="")&&($numTel!="")){
                $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS) values('".$dateString."',".$montantFixePayement.",'".$refTransf."','".$numTel."',".$idPS.")";
                $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas4 ".mysql_error());

                $sql4="SELECT * FROM `aaa-payement-salaire` where idPS=".$idPS;
                $res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
                if(@mysql_num_rows($res4)){
                    $paiement = mysql_fetch_array($res4);
                    if ($paiement['montantFixePayement']==$montantFixePayement){
                        $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idPS=".$idPS;
                        $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error());
                    }else{
                        $sql5="UPDATE `aaa-payement-reference` set  montantNonConforme='".$montantNonConforme."' where idPS=".$idPS;
                        $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error());
                    }
                }
            }
        }*/
}
elseif (isset($_POST['btnDesactiver'])) {
	$idPS=$_POST['idPS'];
	$idBoutique=$_POST['idBoutique'];
	$datePaiement=$_POST['datePaiement'];
	$heurePaiement=$_POST['heurePaiement'];
	$refTransf=$_POST['refTransf'];
	$numTel=$_POST['numTel'];

	$activer=0;
	$sql3="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."',datePaiement='".$datePaiement."',heurePaiement='".$heurePaiement."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas6 ".mysql_error());
}


require('entetehtml.php');
?>

<body>

	<?php   require('header.php');

        $moiM=$mois-1;
        $anneeM=$annee;
        if($moiM<10){
            $moiM='0'.$moiM;
            if($mois=='01'){
                $moiM=12;
                $anneeM=$annee-1;
                $anneeM="$anneeM";
            }
        }
    ?>


<div class="row">
	<div class="">
	   <div class="card " style=" ">
			<!-- Default panel contents
			<div class="card-header text-white bg-success">Liste du personnel</div>-->
			<div class="card-body">
              <div class="container">
                 <center>
                    <?php

										/*************************DEBUT POUR MOIS PASSE ****************************/
                        $somme=0;
                        $sql0="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )" ;
                        $res0 = mysql_query($sql0) or die ("etape requête 4".mysql_error());
                        while($boutiqueP=mysql_fetch_array($res0)) {
                            $sql01="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                            $res01 = mysql_query($sql01) or die ("etape requête 4".mysql_error());
                            while($payement=mysql_fetch_array($res01)) {
                                $somme=$somme+$payement['montantFixePayement'];
                            }
                        }
                     ///////////////////////////////////////////////////////////////////////
	                      $somme10=0;
	                        $sql10="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )" ;
	                        $res10 = mysql_query($sql10) or die ("etape requête 4".mysql_error());
                        while($boutiqueP12=mysql_fetch_array($res10)) {
                            $sql412="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP12["idBoutique"]." order by datePS DESC LIMIT 1" ;
                            $res412 = mysql_query($sql412) or die ("etape requête 4".mysql_error());
                            while($payement12=mysql_fetch_array($res412)) {
                                $somme10=$somme10+$payement12['montantFixePayement'];
                            }
                        }
												/*************************FIN POUR MOIS PASSE ****************************/

												/*************************DEBUT POUR CE MOIS  ****************************/
		                        $somme20=0;
		                        $sql20="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$annee."-".$mois."%' )" ;
		                        $res20 = mysql_query($sql20) or die ("etape requête 4".mysql_error());
		                        while($boutiqueP=mysql_fetch_array($res20)) {
		                            $sql21="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
		                            $res21 = mysql_query($sql21) or die ("etape requête 4".mysql_error());
		                            while($payement=mysql_fetch_array($res21)) {
		                                $somme20=$somme20+$payement['montantFixePayement'];
		                            }
		                        }
		                     ///////////////////////////////////////////////////////////////////////
			                      $somme30=0;
			                        $sql30="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%' )" ;
			                        $res30 = mysql_query($sql30) or die ("etape requête 4".mysql_error());
		                        while($boutiqueP12=mysql_fetch_array($res30)) {
		                            $sql412="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP12["idBoutique"]." order by datePS DESC LIMIT 1" ;
		                            $res412 = mysql_query($sql412) or die ("etape requête 4".mysql_error());
		                            while($payement12=mysql_fetch_array($res412)) {
		                                $somme30=$somme30+$payement12['montantFixePayement'];
		                            }
		                        }
														/*************************FIN POUR CE MOIS  ****************************/
                    ?>
                     <?php
                      if($_SESSION['profil']=="SuperAdmin"){ ?>
                          <center>
                                    <div class="modal-body">
                                        <form name="formulairePayementBoutiques" method="post" action="payementboutiques.php">
                                          <div>
                                            <button type="submit" name="payementBoutiques" class="btn btn-success"> Recalcul des Payements </button>

                                           </div>
                                        </form>

                                    </div>
                            </center>
                      <?php
                        } ?>
                        <div class="jumbotron noImpr">
													<div class="row">
														 <div class="col-md-6">
															 <h2>Mois : <?php echo $moiM."-".$anneeM; ?></h2>

															 <p>Total des Paiements non éffectifs : <font color="red"><?php echo $somme; ?> FCFA</font></p>
															 <p>Total des Paiements éffectifs : <font color="red"><?php echo $somme10; ?> FCFA</font></p>
														 </div>
														 <div class="col-md-6">
															 <h2>Mois : <?php echo $mois."-".$annee; ?></h2>

															 <p>Total des Paiements non éffectifs : <font color="red"><?php echo $somme20; ?> FCFA</font></p>
															 <p>Total des Paiements éffectifs : <font color="red"><?php echo $somme30; ?> FCFA</font></p>
														 </div>
													</div>



                        </div>
                     </center>
                  <?php
                      if($_SESSION['profil']=="SuperAdmin"){ ?>
                  <center>
                            <div class="modal-body">
                                <form name="formulairePayementBoutiques" method="post" action="payementboutiques.php">
                                  <div>
                                    <button type="button" name="referenceTransfert" class="btn btn-warning" data-toggle="modal" data-target="#ReferencetransfertsOrange"> Ajout de Référence Transfert Orange </button>
                                    <button type="button" name="ReferencetransfertsWave" class="btn btn-primary" data-toggle="modal" data-target="#ReferencetransfertsWave"> Ajout de Référence Transfert Wave </button>
                                    <button type="button" name="RefTransPlusieursMois" class="btn btn-danger" data-toggle="modal" data-target="#RefTransPlusieursMois">Reference Plusieur mois </button>
                                   </div>
                                </form>
                            </div>
                        </center>
                  <?php
                  } ?>

                <div class="modal fade" id="ReferencetransfertsOrange" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Envoie de référence de paiement</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="payementboutiques.php">
  																							<div class="form-group">
                                                    <label for="dateTransfert"> Date Envoie</label>
                                                    <input type="Date" class="form-control" name="dateTransfert" id="dateTransfert" value=""  />
                                                </div>
                                                <div class="form-group">
                                                    <label for="montantTransfert"> Montant</label>
                                                    <input type="number" class="form-control" name="montantTransfert" id="montantTransfert" min="0" value=""  />
                                                </div>
                                                 <div class="form-group">
                                                    <label for="montantTransfert"> Avec frais</label>
                                                    <input type="checkbox" name="avecFrais" id='avecFrais' onclick="isFrais()" />
                                                </div>
                                                <div class="form-group" style="display:none" id="text">
                                                    <label for="montantTransfert" id='lbF'> Frais</label>
                                                    <input type="number" min='0' class="form-control" name="frais" id='inpF' value="0" />
                                                </div>

                                                <div class="form-group">
                                                    <label for="refTransf">Référence du Transfert </label>
                                                    <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf" value="" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="numTel"> Numéro de Téléphone </label>
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" />
                                                </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnReferencetransfertsOrange" class="btn btn-primary">Envoyer Référence Transfert</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                 </div>
                <div class="modal fade" id="ReferencetransfertsWave" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Envoie de référence de paiement Wave</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="payementboutiques.php">
  																							<div class="form-group">
                                                    <label for="dateTransfert"> Date Envoie</label>
                                                    <input type="Date" class="form-control" name="dateTransfert" id="dateTransfert" value=""  />
                                                </div>
                                                <div class="form-group">
                                                    <label for="montantTransfert"> Montant</label>
                                                    <input type="number" class="form-control" name="montantTransfert" id="montantTransfert" min="0" value=""  />
                                                </div>
                                                <div class="form-group">
                                                    <label for="refTransf">Référence du Transfert </label>
                                                    <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf" value="" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="numTel"> Numéro de Téléphone </label>
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" />
                                                </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnReferencetransfertsWave" class="btn btn-primary">Envoyer Référence Transfert</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                </div>
                <div class="modal fade" id="RefTransPlusieursMois" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Envoie de référence plusieurs mois</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="payementboutiques.php">
  												<div class="form-group">
                                                    <label for="dateTransfert"> Date Envoie</label>
                                                    <input type="Date" class="form-control" name="dateTransfert" id="dateTransfert" value=""  />
                                                </div>
                                               	<div class="form-group">
                                                    <label for="refTransf">Référence du Transfert </label>
                                                    <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransfPm" value="" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="numTel"> Numéro de Téléphone </label>
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="montantTransfert"> Montant</label>
                                                    <input type="number" class="form-control" name="montantTransfert" id="montantTransfertPm" min="0" value=""  />
                                                </div>
                                                 <div class="form-group">
                                                    <label for="montantTransfert"> Nombre de mois</label>
                                                    <input type="number" class="form-control" name="nbrMois" id="nbrMoisPm" min="1" value=""  />
                                                </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnRefTransPlusieursMois" class="btn btn-primary">Envoyer Référence Transfert</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                </div>
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PAIEMENTS EN COURS...</a></li>
                  <li class=""><a data-toggle="tab" href="#LISTEHORS">LISTE DES HORS PARAMETRE PAIEMENTS</a></li>
                  <li class=""><a data-toggle="tab" href="#LISTENONC">LISTE DES PAIEMENTS A MONTANT NON CONFORME</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                        <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Accompagnateur</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Etat</th>
                                    <th>Paiement</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Accompagnateur</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Etat</th>
                                    <th>Paiement</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php


                                $somme=0;
                                $sql1="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$annee."-".$mois."%' or `datePS` LIKE '%".$anneeM."-".$moiM."%')" ;
                                //echo $sql1;
                                $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
//                                while($boutiqueP=mysql_fetch_array($res1)) {
//                                $sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
//                                $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                                while($payement=mysql_fetch_array($res1)) {
                                    $somme=$somme+$payement['montantFixePayement'];
                            ?>
                                        <tr>

                                            <td> <b><?php $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement['idBoutique'];
                                                          $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                                                          if ($boutique3 = mysql_fetch_array($res2))
                                                            echo  $boutique3['nomBoutique']; if($payement['variablePayementActiver']==1)  ?> </b> </td>

                                            <td> <b><?php echo  $payement['montantFixePayement']; ?> FCFA  </b>   </td>
                                            <td> <?php echo  $payement['accompagnateur']; ?>  </td>
                                            <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                            <td> <?php echo  $payement['datePS']; ?>  </td>

                                            <?php

                                                 if ($payement['aPayementBoutique']==0) { ?>
                                                    <td><span>En cour...</span></td>
                                                    <td><button type="button" class="btn btn-warning" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#validerPaiementB".$payement['idBoutique'] ; ?> >
                                                    OMoney</button>
																										<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#validerPaiementWave".$payement['idBoutique'] ; ?> >
                                                    Wave</button>
                                                    </td>
                                                    <?php
                                                } else { ?>
                                                    <td><span>Effectif</span></td>
                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$payement['idBoutique'] ; ?> >
                                                    Annuler</button></td>
                                                <?php }


                                                 ?>

                        <div class="modal fade" <?php echo  "id=validerPaiementB".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Validation de paiement</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="payementboutiques.php">

                                            <input type="hidden" name="idPS" id="idPS_Rtr" <?php echo  "value=".$payement['idPS']."" ; ?> />
                                            <input type="hidden" name="idBoutique" <?php echo  "value=".$payement['idBoutique']."" ; ?> />
                                             <input type="hidden" name="datePaiement" <?php echo  "value=".$dateString."" ; ?> />
                                             <input type="hidden" name="heurePaiement" <?php echo  "value=".$heureString."" ; ?> />

                                                <div class="form-group">
                                                    <label for="datePS"> Mois</label>
                                                    <input type="text" class="form-control" name="DatePS" id="datePS" value="<?php echo $payement['datePS']; ?>"  disabled="true" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="categorie"> Montant</label>
                                                    <input type="text" class="form-control" name="montantFixePayement" id="montantFixePayement"    />
                                                </div>

                                                <div class="form-group">
                                                    <label for="refTransf">Référence du Transfert </label>
                                                    <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf" value="" autofocus="" required />
                                                    <!--  <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransfXX" value="" autofocus="" required pattern="[A-Z]{2}\d{6}+\.\d{4}+\.\d{5}"  />-->
                                                </div>
                                                <div class="form-group">
                                                    <label for="numTel"> Numéro de Téléphone </label>
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" required />
                                                </div>

                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnValidationP" class="btn btn-primary">Validation du paiement</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

						<div class="modal fade" <?php echo  "id=validerPaiementWave".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Validation de paiement Wave</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="payementboutiques.php">

                                            <input type="hidden" name="idPS" id="idPS_Rtr" <?php echo  "value=".$payement['idPS']."" ; ?> />
                                            <input type="hidden" name="idBoutique" <?php echo  "value=".$payement['idBoutique']."" ; ?> />
                                             <input type="hidden" name="datePaiement" <?php echo  "value=".$dateString."" ; ?> />
                                             <input type="hidden" name="heurePaiement" <?php echo  "value=".$heureString."" ; ?> />
                                             <input type="hidden" name="montantFixePayement" <?php echo  "value=".$payement['montantFixePayement']."" ; ?> />

                                                <!-- <div class="form-group">
                                                    <label for="numTel"> Numéro de Téléphone </label>
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" required />
                                                </div> -->
																								Confirmation

                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnValidationPWave" class="btn btn-primary">Validation du paiement</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

												<div class="modal fade" <?php echo  "id=Desactiver".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Annuler le paiement</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="payementboutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment annuler le paiement</h2>
                                             <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($payement['idBoutique'])."" ; ?> />
                                             <input type="hidden" name="datePaiement" <?php echo  "value=".$dateString."" ; ?> />
                                             <input type="hidden" name="heurePaiement" <?php echo  "value=".$heureString."" ; ?> />
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnDesactiver" class="btn btn-primary">Annuler le paiement</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!----------------------------------------------------------->
                        <!----------------------------------------------------------->
                        <!----------------------------------------------------------->


                                        </tr>
                                    <?php
                                     }

                                //}
                                ?>

                            <!--    DEBUT   LES BOUTIQUES QUI ONT PAYE CE MOIS EN COURS     -->

                            <?php

                                $sql11="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%' or `datePS` LIKE '%".$anneeM."-".$moiM."%') " ;
                                //echo $sql11;

                                /*if($res11 = mysql_query($sql11)){
                                    while($boutiqueP1=mysql_fetch_array($res11)) {
                                $sql41="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP1["idBoutique"]." order by datePS DESC LIMIT 1" ;*/
                                $res11 = mysql_query($sql11) ;
                                while($payement1=mysql_fetch_array($res11)) {
//                                    $somme1=$somme1+$payement1['montantFixePayement'];
                                    $sqlp="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$payement1['idBoutique']." ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1";
                                    //echo $sqlp;
                                    $resp = mysql_query($sqlp) or die ("personel requête 2".mysql_error());
                                     while($p1=mysql_fetch_array($resp)) {

                                          if ($payement1['idPS']==$p1['idPS']) { ?>
                                             <tr>
                                                <td> <b><?php $sql21="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement1['idBoutique'];
                                                                      $res21 = mysql_query($sql21) or die ("personel requête 2".mysql_error());
                                                                      if ($boutique31 = mysql_fetch_array($res21))
                                                                        echo  $boutique31['nomBoutique']; if($payement1['variablePayementActiver']==1)  ?> </b> </td>

                                                        <td> <b><?php echo  $payement1['montantFixePayement']; ?> FCFA  </b>   </td>
                                                        <td> <?php echo  $payement1['accompagnateur']; ?>  </td>
                                                        <td>Mois <?php echo  $payement1['etapeAccompagnement']; ?>  </td>
                                                        <td> <?php echo  $payement1['datePS']; ?>  </td>
                                                        <td><span>Effectif</span></td>
                                                        <td ><span class="primary">Déja payé</span></td>
                                            </tr>
                                          <?php  } else { ?>

                                         <?php }

                                     }?>


                                    <?php
                                     }
                               // }
                                //}


                                ?>
                            <!--    FIN     LES BOUTIQUES QUI ONT PAYE CE MOIS EN COURS     -->
                            </tbody>
                        </table>
                      </div>
                     <div class="tab-pane fade " id="LISTEHORS">
                        <table id="exemple3" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Accompagnateur</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Paiement</th>
                                    <th>Opérations</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Accompagnateur</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Paiement</th>
                                    <th>Opérations</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                //les boutiques en phase d'esploitation
                                $sql2="SELECT * FROM `aaa-boutique` WHERE enTest =1";
                                $res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
                                //var_dump("1");
                                while ($boutique = mysql_fetch_array($res2)) {
                                    //volume moyenne des données de chaque boutique

                                        $volumeMoyenne=0;

                                        $tailleCatal=0;
                                        $nomtableDesignation=$boutique["nomBoutique"]."-designation";
                                        $sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
                                        $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
                                        if($compteur = mysql_fetch_array($res))
                                            $tailleCatal=$compteur["nbreRef"];
                                            //var_dump("2");

                                        $tailleStocks=0;
                                        $nomtableStock=$boutique["nomBoutique"]."-stock";
                                        $sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=0";
                                        $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
                                        if($compteur = mysql_fetch_array($res))
                                            $tailleStocks=$compteur["nbreStock"];
                                         //var_dump("3");
                                        $volumeMoyenne=	($tailleCatal+$tailleStocks)/2;


                                    //recherche des varibles de Paiement de chaque boutique
                                    $sql3="SELECT * FROM `aaa-variablespayement` where typecaisse='".$boutique["type"]."' and categoriecaisse='".$boutique["categorie"]."' and moyenneVolumeMax<".$volumeMoyenne;
                                    //echo $sql3;
                                    $res3 = @mysql_query($sql3) or die ("acces requête 3".mysql_error());

                                    $datetime11 = new DateTime($boutique['datecreation']);
                                    $annee11 =$datetime11->format('Y');
                                    $mois11 =$datetime11->format('m');

                                    $dateString=$annee.'-'.$mois.'-'.$jour;
                                    //var_dump($dateString);
                                    $datetime21 = new DateTime($dateString);
                                    $annee21 =$datetime21->format('Y');
                                    $mois21 =$datetime21->format('m');

                                    $etapeAccompagnement = ($mois21-$mois11)+12*($annee21-$annee11)+1;
                                    /*    le nombre de mois entre deux dates    */

                                    if($variable = @mysql_fetch_array($res3)){
                                        $sql4="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) and idBoutique=".$boutique["idBoutique"]." and aPayementBoutique=0";
                                        $res4 = mysql_query($sql4) or die ("acces requête 3".mysql_error());
                                        if(mysql_num_rows($res4)){
                                             while($payement1=mysql_fetch_array($res4)) {
                                            ?>
                                            <tr>
                                                <td> <b><?php $sql21="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement1['idBoutique'];
                                                              $res21 = mysql_query($sql21) or die ("personel requête 2".mysql_error());
                                                              if ($boutique31 = mysql_fetch_array($res21))
                                                                echo  $boutique31['nomBoutique']; if($payement1['variablePayementActiver']==1)  ?> </b> </td>
                                                <td> <b><?php echo  $payement1['montantFixePayement']; ?> FCFA  </b>   </td>
                                                <td> <?php echo  $payement1['accompagnateur']; ?>  </td>
                                                <td>Mois <?php echo  $payement1['etapeAccompagnement']; ?>  </td>
                                                <td> <?php echo  $payement1['datePS']; ?>  </td>
                                                 <?php

                                                 if ($payement1['aPayementBoutique']==0) { ?>
                                                    <td><span>En cour...</span></td>
                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer2".$payement1['idBoutique'] ; ?> >
                                                    Payer</button>
                                                    </td>
                                                    <?php
                                                } else { ?>
                                                    <td><span>Effectif</span></td>
                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver2".$payement1['idBoutique'] ; ?> >
                                                    Annuler</button></td>
                                                <?php }
                                                 ?>
                                                 <div class="modal fade" <?php echo  "id=Activer2".$payement1['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Validation de paiement</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form name="formulaireVersement" method="post" action="payementboutiques.php">

                                                                <input type="hidden" name="idPS" id="idPS_Rtr" <?php echo  "value=".$payement1['idPS']."" ; ?> />
                                                                <input type="hidden" name="idBoutique" <?php echo  "value=".$payement1['idBoutique']."" ; ?> />
                                                                 <input type="hidden" name="datePaiement" <?php echo  "value=".$dateString."" ; ?> />
                                                                 <input type="hidden" name="heurePaiement" <?php echo  "value=".$heureString."" ; ?> />

                                                                    <div class="form-group">
                                                                        <label for="datePS"> Mois</label>
                                                                        <input type="text" class="form-control" name="DatePS" id="datePS" value="<?php echo $payement1['datePS']; ?>"  disabled="true" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant</label>
                                                                        <input type="text" class="form-control" name="montantFixePayement" id="montantFixePayement" value="<?php echo $payement1['montantFixePayement']; ?>"  disabled="true" />
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="refTransf">Référence du Transfert </label>
                                                                        <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf" value="" autofocus="" required />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="numTel"> Numéro de Téléphone </label>
                                                                        <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" required />
                                                                    </div>

                                                              <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                    <button type="submit" name="btnActiver" class="btn btn-primary">Validation du paiement</button>
                                                               </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=Desactiver2".$payement1['idBoutique'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Annuler le paiement</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="payementboutiques.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment annuler le paiement</h2>
                                                                     <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($payement1['idBoutique'])."" ; ?> />
                                                                     <input type="hidden" name="datePaiement" <?php echo  "value=".$dateString."" ; ?> />
                                                                     <input type="hidden" name="heurePaiement" <?php echo  "value=".$heureString."" ; ?> />
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Annuler le paiement</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                            </tr>
                                            <?php
                                             }
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                      </div>
                    <div class="tab-pane fade " id="LISTENONC">
                        <table id="exemple2" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Montant Paiement ADMIN</th>
                                    <th>Accompagnateur</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Motif</th>
                                    <th>Opérations</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Montant Paiement ADMIN</th>
                                    <th>Accompagnateur</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Motif</th>
                                    <th>Opérations</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                //les boutiques en phase d'esploitation
                                $sql2="SELECT * FROM `aaa-payement-reference` where montantNonConforme=1";
                                $res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
                                //var_dump("1");
                                while ($pr = mysql_fetch_array($res2)) {
                                    $sql3="SELECT * FROM `aaa-payement-salaire` where idPS=".$pr['idPS']." ";
                                    $res3 = mysql_query($sql3) or die ("boutique requête 2".mysql_error());
                                    while($ps = mysql_fetch_array($res3)){
                                        ?>
                                        <tr>

                                            <td> <b><?php $sql21="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$ps['idBoutique'];
                                                          $res21 = mysql_query($sql21) or die ("personel requête 2".mysql_error());
                                                          if ($boutique31 = mysql_fetch_array($res21))
                                                            echo  $boutique31['nomBoutique'];   ?> </b>
                                            </td>

                                            <td> <b><?php echo  $ps['montantFixePayement']; ?> FCFA  </b>   </td>
                                            <td> <b><?php echo  $pr['montant']; ?> FCFA  </b>   </td>
                                            <td> <?php echo  $ps['accompagnateur']; ?>  </td>
                                            <td>Mois <?php echo  $ps['etapeAccompagnement']; ?>  </td>
                                            <td> <?php echo  $ps['datePS']; ?>  </td>
                                            <td><span>Montant non conforme</span></td>
                                            <td><span><button type="button" name="" class="btn btn-danger">Verifier</button></span></td>
                                        </tr>
                                    <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                      </div>
                  </div>
			  </div>
             </div>

        </div>
    </div>
</div>

</body>
</html>
