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

require('declarationVariables.php');

$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;
//var_dump($dateString);
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

    //Mois Precedent

    $moisP=$mois-1;
        $anneeP=$annee;
        if($moisP<10){
            $moisP='0'.$moisP;
            if($mois=='01'){
                $moisP=12;
                $anneeP=$annee-1;
                $anneeP="$anneeP";
            }
        }
     
    $partAccompagnateur=0;
    
    /**************************************** DEBUT ******************************************************/
        //DEBUT les boutiques en phase d'esploitation sans montantFixeHorsParametre
            $sql2="SELECT * FROM `aaa-boutique` WHERE enTest =1 and activer=1 and montantFixeHorsParametre=0";
            $res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
            while ($boutique = mysql_fetch_array($res2)) {
                
                //$sql3a="SELECT * FROM `aaa-payement-salaire` where idBoutique ( datePS LIKE '%".$anneeP."-".$moisP."%' or datePS LIKE '%".$moisP."-".$anneeP."%' ) and aPayementBoutique=1 and 
                // idBoutique=".$boutique["idBoutique"];
                
                $sql3a="SELECT * FROM `aaa-payement-salaire` where idBoutique=".$boutique["idBoutique"]." ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1";
                //var_dump($sql3a);
                $res3a=mysql_query($sql3a);
                $num_rows = mysql_num_rows($res3a);
                $test=mysql_fetch_array($res3a);
                if ($num_rows==0 || $test['aPayementBoutique']==1 ) {
                    //volume moyenne des données de chaque boutique
                    $partAccompagnateur=0;
                    $etapeAccompagnement=0;
                    $volumeMoyenne=0;

                    $tailleCatal=0;
                    $nomtableDesignation=$boutique["nomBoutique"]."-designation";
                    $sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
                    $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
                    if($compteur = mysql_fetch_array($res))
                        $tailleCatal=$compteur["nbreRef"];

                    $nomtableStock="";
                    if ($boutique["type"]=="Entrepot") {
                        $nomtableStock=$boutique["nomBoutique"]."-entrepotstock";
                        
                    } else {
                        $nomtableStock=$boutique["nomBoutique"]."-stock";
                    } 
                     //var_dump($nomtableStock);   
                    $tailleStocks=0;
                    $sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=0";
                    $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
                    if($compteur = mysql_fetch_array($res))
                        $tailleStocks=$compteur["nbreStock"];
                    $volumeMoyenne=	($tailleCatal+$tailleStocks)/2;
                    //recherche des varibles de Paiement de chaque boutique
                    $sql3="SELECT * FROM `aaa-variablespayement` where typecaisse='".$boutique["type"]."' and 
                    categoriecaisse='".$boutique["categorie"]."' and moyenneVolumeMin<=".$volumeMoyenne." and moyenneVolumeMax>=".$volumeMoyenne;
                    //echo $sql3;
                    $res3 = @mysql_query($sql3) or die ("acces requête 3".mysql_error());

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
                        /* $sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
                                        and aPayementBoutique=0 and idBoutique=".$boutique["idBoutique"]; */
                        
                                        //Verification si paiement boutique
                        
                        $sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
                                        and idBoutique=".$boutique["idBoutique"];
                        //             echo '<pre>';
                        //                  var_dump($sql3);
                        //                echo '</pre>';$sql6;
                        $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                            if(@mysql_num_rows($res3)){
                                if($variable["activerMontant"]==1){
                                    if($etapeAccompagnement==1){
                                            $partAccompagnateur=$variable["montant"]*50/100;
                                    }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                        $partAccompagnateur=$variable["montant"]*20/100;
                                    }
                                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",
                                        variablePayementActiver=1,etapeAccompagnement=".$etapeAccompagnement.",partAccompagnateur=".$partAccompagnateur." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) "  ;
                                        //echo $sql6.'</br>';
                                    $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                                }else if($variable["activerPourcentage"]==1){
                                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",
                                    variablePayementActiver=2,etapeAccompagnement=".$etapeAccompagnement.",partAccompagnateur=".$partAccompagnateur." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) " ;
                                    //echo $sql6.'</br>';
                                    $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                                }else if($variable["activerPrix"]==1){
                                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",
                                    variablePayementActiver=3,etapeAccompagnement=".$etapeAccompagnement.",partAccompagnateur=".$partAccompagnateur." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) " ;
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
                                        //echo 'hhhh';
                                        //var_dump($partAccompagnateur);
                                        $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) values
                                        (".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",1,".$etapeAccompagnement.",".$partAccompagnateur.")";
                                        //echo $sql6.'</br>';
                                        //var_dump($sql6);
                                        $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());


                                    }else if($variable["activerPourcentage"]==1){
                                            //var_dump("7");
                                            $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) 
                                            values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",2,".$etapeAccompagnement.",".$partAccompagnateur.")";
                                            //echo $sql6.'</br>';
                                            $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                                    }else if($variable["activerPrix"]==1){
                                        //var_dump("8");
                                        $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) 
                                        values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",3,".$etapeAccompagnement.",".$partAccompagnateur.")";
                                        //echo $sql6.'</br>';
                                        $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                                    }

                            }
                    
                    }
                }
            }
        //FIN les boutiques en phase d'esploitation sans montantFixeHorsParametre
    /**************************************** FIN ******************************************************/
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    /**************************************** DEBUT AVEC MONTANT HORS PARAMETRE******************************************************/
    $sql1="SELECT * FROM `aaa-boutique` WHERE enTest =1 and activer=1 and montantFixeHorsParametre>0";
            $res1 = mysql_query($sql1) or die ("boutique requête 1".mysql_error());
            while ($boutique = mysql_fetch_array($res1)) {
                
                $sql3a="SELECT * FROM `aaa-payement-salaire` where idBoutique=".$boutique["idBoutique"]." ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1";
                //var_dump($sql3a);
                $res3a=mysql_query($sql3a);
                $num_rows = mysql_num_rows($res3a);
                $test=mysql_fetch_array($res3a);
                if ($num_rows==0 || $test['aPayementBoutique']==1 ) {
                    /*    le nombre de mois entre deux dates    */

                $datetime3 = new DateTime($boutique["datecreation"]);
                $annee3 =$datetime3->format('Y');
                $mois3 =$datetime3->format('m');

                $datetime4 = new DateTime($dateString);
                $annee4 =$datetime4->format('Y');
                $mois4 =$datetime4->format('m');

                $etapeAccompagnement = ($mois4-$mois3)+12*($annee4-$annee3)+1;
                /*    le nombre de mois entre deux dates    */                   
                $sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) and idBoutique=".$boutique["idBoutique"];
                $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                    if(@mysql_num_rows($res3)){
                            if($etapeAccompagnement==1){
                                    $partAccompagnateur=$boutique["montantFixeHorsParametre"]*50/100;
                                }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                    $partAccompagnateur=$boutique["montantFixeHorsParametre"]*20/100;
                                }
                            $sql7="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',
                                datePS='".$dateString."',montantFixePayement=".$boutique["montantFixeHorsParametre"].",pourcentagePayement=0, 
                                	prixlignesPayement=".$boutique["montantFixeHorsParametre"].",minmontant=".$boutique["montantFixeHorsParametre"].",
                                    maxmontant=".$boutique["montantFixeHorsParametre"].",
                                    variablePayementActiver=0,etapeAccompagnement=".$etapeAccompagnement.",
                                    partAccompagnateur=".$partAccompagnateur." where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) "  ;
                            //var_dump($sql7);
                            $res7=@mysql_query($sql7) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
                    }else{
                                if($etapeAccompagnement==1){
                                    $partAccompagnateur=$boutique["montantFixeHorsParametre"]*50/100;
                                }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                    $partAccompagnateur=$boutique["montantFixeHorsParametre"]*20/100;
                                }
                                $sql7="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
                                prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) values
                                (".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$boutique["montantFixeHorsParametre"].",0,".$boutique["montantFixeHorsParametre"].",".$boutique["montantFixeHorsParametre"].",".$boutique["montantFixeHorsParametre"].",1,".$etapeAccompagnement.",".$partAccompagnateur.")";                                
                                $res6=@mysql_query($sql7) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());                     
                    }        
            
                }
            }
    
    /**************************************** FIN AVEC MONTANT HORS PARAMETRE******************************************************/
    /******************************************************************************************************************************/

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
		//var_dump($sql2);
		$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res2)){
			$sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,avecFrais,frais) 
			values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."','".$avecFrais."','".$frais."')";
			//var_dump($sql3);
			$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas1 ".mysql_error());

                    /**********************************TABLE COMPTE *****************************************/
                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
		                  $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
                          $payementReference =mysql_fetch_assoc($res8);

                          $sqlv="select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'";
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

                          $sqlv="select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'";
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

	$dateTransfert=$_POST['dateTransfert'];
	$montantFixePayement=$_POST['montantFixePayement'];
	$montantTransfert=$_POST['montantFixePayement'];
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
    $sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";

		$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res2)){

            if (($refTransf!="")&&($numTel!="")){
                $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS,avecFrais,frais) 
                values('".$dateTransfert."',".$montantFixePayement.",'".$refTransf."','".$numTel."',".$idPS.",".$avecFrais.",".$frais.")";
                //var_dump( $sql3);
                $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas4 ".mysql_error());

                  /**********************************TABLE COMPTE *****************************************/
                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
		                  $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
                          $payementReference =mysql_fetch_assoc($res8);

                          $sqlv="select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'";
                          //var_dump($sqlv);
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          //var_dump($compte);
                          if($compte){
                          		//var_dump('3');
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransf;
                              $newMontant=$compte['montantCompte']+$montantTransfert;

                              $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR)
                               values('".$montantTransfert."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
                              //var_dump($sql6);
                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
                              //var_dump($sql7);
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                          }
                    /********************************TABLE COMPTE **************************************/

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

	$dateTransfert=$_POST['dateTransfert'];
	$montantFixePayement=$_POST['montantFixePayement'];
	$montantTransfert=$_POST['montantFixePayement'];
	$refTransf=$_POST['refTransf'];
	$numTel=$_POST['numTel'];
	
	$refTransf='Sans reference';
	$typeCompteMobile='Wave'; 
	$activer=1;
    $montantNonConforme=1;
    if(isset($_POST['refTransf'])){
    	if ($_POST['refTransf'] !='') {
    		// code...
        	$refTransf=$_POST['refTransf'];
    	}
    } 

	$sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";

	$res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
	if(!@mysql_num_rows($res2)){

					if ($refTransf!=""){
							$sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS) 
							  values('".$dateTransfert."',".$montantFixePayement.",'".$refTransf."','".$numTel."',".$idPS.")";
							  //var_dump($sql3);
							$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas4 ".mysql_error());

							/**********************************TABLE COMPTE *****************************************/
		                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
				                  $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
		                          $payementReference =mysql_fetch_assoc($res8);

		                          $sqlv="select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'";
		                          $resv=mysql_query($sqlv);
		                          $compte =mysql_fetch_assoc($resv);
		                          if($compte){
		                              $operation='depot';
		                              $idCompte=$compte['idCompte'];
		                              $description=$refTransf;
		                              $newMontant=$compte['montantCompte']+$montantTransfert;

		                              $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) 
		                              values('".$montantTransfert."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
		                              //var_dump($sql6);
		                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

		                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
		                              //var_dump($sql7);
		                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
		                          }
		                    /********************************TABLE COMPTE **************************************/

							$sql4="SELECT * FROM `aaa-payement-salaire` where idPS=".$idPS;
							$res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
							if(@mysql_num_rows($res4)){
									$paiement = mysql_fetch_array($res4);
											$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idPS=".$idPS;
											//var_dump($sql5);
											$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error());

											$sql="UPDATE `aaa-payement-salaire` set refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."',
													datePaiement='".$dateString."', heurePaiement='".$heureString."', aPayementBoutique='".$activer."'  where idPS=".$idPS;
											//var_dump($sql);
											$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
							}

							/*if(@mysql_num_rows($res4)){
								$paiement = mysql_fetch_array($res4);
								if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
									$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
									$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

									$sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
									$res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

								}else{
				                     $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." 
				                       where refTransfertValidation='".$refTransf."'";
				                    //var_dump($sql5);
								    $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
								}
							}*/
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
    $dateString=$annee.'-'.$mois.'-'.$jour;
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

      /*  $moiMM=$moiM-1;
        $anneeMM=$annee;
        if($moiMM<10){
            $moiMM='0'.$moiMM;
            if($moiM=='01'){
                $moiMM=12;
                $anneeMM=$annee-1;
                $anneeMM="$anneeMM";
            }
        }*/
        
        $moisN2=$mois;
        $somme=$mois+1;
        $anneeN2=$annee;
        if($somme>12){
            $moisN2=$somme-12;
            $anneeN2=$anneeN2+1;
          }else{
            $moisN2=$moisN2+1;
        }
        if ($moisN2<10) {
                $moisN2='0'.$moisN2;
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
                             $somme01=0;
                             $sql01="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and pPlusieursMois=0  and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )" ;
                             //var_dump($sql11);
                             $res01 = mysql_query($sql01) or die ("etape requête 4".mysql_error());
                             while($boutiqueP01=mysql_fetch_array($res01)) {
                                 $sql010="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP01["idBoutique"]." order by datePS DESC LIMIT 1" ;
                                 $res010= mysql_query($sql010) or die ("etape requête 5".mysql_error());
                                 while($payement01=mysql_fetch_array($res010)) {
                                     $somme01=$somme01+$payement01['montantFixePayement'];
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
                            ///////////////////////////////////////////////////////////////////////
		                      $somme11=0;
                              $sql11="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and pPlusieursMois=1  and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )" ;
                              var_dump($sql11);
                              $res11 = mysql_query($sql11) or die ("etape requête 4".mysql_error());
                              while($boutiqueP20=mysql_fetch_array($res11)) {
                                  $sql111="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP20["idBoutique"]." order by datePS DESC LIMIT 1" ;
                                  $res111= mysql_query($sql111) or die ("etape requête 5".mysql_error());
                                  while($payement20=mysql_fetch_array($res111)) {
                                      $somme11=$somme11+$payement20['montantFixePayement'];
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
                                $somme02=0;
                                $sql02="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and pPlusieursMois=0  and (`datePS` LIKE '%".$annee."-".$mois."%' )" ;
                                //var_dump($sql11);
                                $res02 = mysql_query($sql02) or die ("etape requête 4".mysql_error());
                                while($boutiqueP02=mysql_fetch_array($res02)) {
                                    $sql020="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP02["idBoutique"]." order by datePS DESC LIMIT 1" ;
                                    $res020= mysql_query($sql020) or die ("etape requête 5".mysql_error());
                                    while($payement02=mysql_fetch_array($res020)) {
                                        $somme02=$somme02+$payement02['montantFixePayement'];
                                    }
                                }
		                     	///////////////////////////////////////////////////////////////////////
			                      $somme30=0;
			                        //$sql30="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%' )" ;
			                        $sql30="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%' )" ;
                                    $res30 = mysql_query($sql30) or die ("etape requête 4".mysql_error());
			                        //var_dump($sql30);
			                        while($boutiqueP12=mysql_fetch_array($res30)) {
			                            $sql412="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP12["idBoutique"]." order by datePS DESC LIMIT 1" ;
			                            $res412 = mysql_query($sql412) or die ("etape requête 4".mysql_error());
			                            while($payement12=mysql_fetch_array($res412)) {
			                                $somme30=$somme30+$payement12['montantFixePayement'];
			                            }
			                        }
                                ///////////////////////////////////////////////////////////////////////
			                      $somme40=0;
                                  //$sql40="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and pPlusieursMois=1 and  (`datePS` LIKE '%".$anneeN2."-".$moisN2."%' )" ;
                                  $sql40="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and pPlusieursMois=1 and  (`datePS` LIKE  '%".$annee."-".$mois."%' )" ;
                                  $res40 = mysql_query($sql40) or die ("etape requête 4".mysql_error());
                                  //var_dump($sql30);
                                  while($boutiqueP12=mysql_fetch_array($res40)) {
                                      $sql412="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP12["idBoutique"]." order by datePS DESC LIMIT 1" ;
                                      $res412 = mysql_query($sql412) or die ("etape requête 4".mysql_error());
                                      while($payement12=mysql_fetch_array($res412)) {
                                          $somme40=$somme40+$payement12['montantFixePayement'];
                                      }
                                  }
								/*************************FIN POUR CE MOIS  ****************************/
                    ?>
                     <?php
                      if($_SESSION['profil']=="SuperAdmin" and  $annee.'-'.$mois.'-25'<=$dateString AND $dateString <= $anneeN2.'-'.$moisN2.'-05'){ ?>
                          <center>
                          <div class="row" align="center">
                                
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPersonnel">
                                                                    <i class="glyphicon glyphicon-plus"></i>Recalcule de paiement
                                </button> 
                                <div class="modal fade" id="addPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Recalcule de paiement</h4>
                                            </div>
                                            <div class="modal-body">
                                                    
                                                <form name="formulairePayementBoutiques" method="post" action="payementboutiques.php">
                                                    <h1> Voulez-vous vraiment recalculer les paiements </h1>
                                                            
                                                        
                                                    
                                                        <div class="modal-footer row">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" name="payementBoutiques" class="btn btn-success"> Confirmer</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                       

                                    
                            </center><br>
                      <?php
                        } ?>
                        <div class="jumbotron noImpr">
													<div class="row">
														 <div class="col-md-6" >
															 <h2>Mois : <?php echo $moiM."-".$anneeM; ?></h2>
                                                             <!-- <p>Paiements reçu : <font color="red"><?php echo $somme10-$somme11; ?> FCFA</font></p> -->
                                                             <p>Paiements reçu : <font color="red"><?php echo $somme01; ?> FCFA</font></p>
                                                             <p>Paiements à l'avance : <font color="red"><?php echo $somme11; ?> FCFA</font></p>
															 <p>Paiements global : <font color="red"><?php echo $somme10; ?> FCFA</font></p>
															 <p>Paiement restant : <font color="red"><?php echo $somme; ?> FCFA</font></p>
														 </div>
														 <div class="col-md-6">
															 <h2>Mois : <?php echo $mois."-".$annee; ?></h2>
                                                             <p>Paiements reçu : <font color="red"><?php echo $somme02; ?> FCFA</font></p>
                                                             <p>Paiements à l'avance : <font color="red"><?php echo $somme40; ?> FCFA</font></p>
															 <p>Paiements global : <font color="red"><?php echo $somme30; ?> FCFA</font></p>
															 <p>Paiement restant : <font color="red"><?php echo $somme20; ?> FCFA</font></p>
														 

															 <!-- <p>Total des Paiements non éffectifs : <font color="red"><?php echo $somme20; ?> FCFA</font></p>
															 <p>Total des Paiements éffectifs : <font color="red"><?php echo $somme30; ?> FCFA</font></p> -->
														 </div>
													</div>



                        </div>
                     </center>
                  <?php
                      if($_SESSION['profil']=="SuperAdmin" OR $_SESSION['profil']=="Assistant"){ ?>
                  		<center>
                            <div class="modal-body">
                                <form name="formulairePayementBoutiques" method="post" action="payementboutiques.php">
                                  <div>
                                    <button type="button" name="referenceTransfert" class="btn btn-warning" data-toggle="modal" data-target="#ReferencetransfertsOrange"> Ajout de Référence Transfert Orange </button>
                                    <button type="button" name="ReferencetransfertsWave" class="btn btn-primary" data-toggle="modal" data-target="#ReferencetransfertsWave"> Ajout de Référence Transfert Wave </button>
                                    <!-- <button type="button" name="RefTransPlusieursMois" class="btn btn-danger" data-toggle="modal" data-target="#RefTransPlusieursMois">Reference Plusieur mois </button> -->
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
                                                    <input type="text" class="form-control " name="refTransf" min="10" max="50" id="refTransf" value="" />
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
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel2" value="" />
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
                <!-- <div id="RefTransPlusieursMoisXXXXXXXX" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Envoie Référence Transfert</h4>
									</div>
									<div class="modal-body" style="padding:40px 50px;">
										<form role="form" class="" >
											<div class="form-group">
                                                <label for="dateTransfert"> Date Envoie</label>
                                                <input type="Date" class="form-control" name="dateTransfertPm" id="dateTransfertPm" />
                                            </div>
											<div class="form-group">
												<label for="refTransf">Référence du Transfert </label>
												<input type="text" class="form-control" name="refTransfPm" min="10" max="50" id="refTransfPm" placeholder="Exemple de référence valide CI200802.1100.B23993" required />
											</div>
											<div class="form-group">
												<label for="categorie"> Montant Total</label>
												<input type="text" class="form-control" name="montantFixePayementTotal" id="montantFixePayementTotalPm"  />
											</div>
											<div class="form-group">
												<label for="categorie"> Montant mensuel</label>
												<input type="text" class="form-control" name="montantFixePayementM" id="montantFixePayementM" disabled="true"  />
											</div>
											<div class="form-group">
												<label for="datePS">Nombre de Mois</label>
												<input type="number" class="form-control" name="DatePS" id="nbrMoisPm" min="2" />
											</div> 
											<div class="form-group">
												<?php 
													$sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile'" ;
					                                $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());
					                                while($op=mysql_fetch_array($resCm)) { 
						                                	echo '<input type="radio" name="operateur" value="'.$op['nomCompte'].'"  /> 
						                                		<label >'.$op['nomCompte'].'</label>';
					                                	}
												 ?>
											</div>
											<div class="form-group">
												<label for="numTel"> Numéro de Téléphone </label>
												<input type="text" class="form-control" name="numTelPm" min="9" max="15" id="numTelPm" placeholder="ici le numero qui reçoit le transfert" required />
											</div>
											<input type="button" id="btnRefTransPlusieursMois" class="boutonbasic" name="btnRefTransPlusieursMois" value="Envoyer transfert >>" />
											</div>
										</form>
									</div>
							</div>
						</div>
				</div>  -->            
				<div id="RefTransPlusieursMois" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Envoie Référence Transfert</h4>
									</div>
									<div class="modal-body" style="padding:40px 50px;">
                                            <div class="form-group  col-md-10">
                                                <label for="dateTransfert"> Boutique</label>
                                                <form name="formulaireInfo" id="formulaireInfo" method="post" action="ajax/paiementPmAjax.php">
			                                        <input type="text" class="form-control" name="nomBoutiquePm" id="nomBoutiquePm" />
			                                        <input type="hidden" class="form-control" name="operation" id="operationPm" value="4" />
			                                        <div id="reponseS"></div>
			                                	    <div id="resultatS"></div>
			                                	</form>
                                            </div>
										
										<form role="form" class="" >
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="categorie"> Montant mensuel</label>
                                                    <input type="text" class="form-control" name="montantFixePayementM" id="montantFixePayementM" disabled="true"  />
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="dateTransfert"> Date Envoie</label>
                                                    <input type="hidden" class="form-control" name="idBoutiquePm" id="idBoutiquePm" />
                                                    <input type="date" class="form-control" name="dateTransfertPm" id="dateTransfertPm"  value="<?php echo $dateString; ?>"  />
                                                    <input type="hidden" name="datePaiementB" id="datePaiementB" <?php echo  "value=".$dateString."" ; ?> />
                                                    <input type="hidden" name="heurePaiementB" id="heurePaiementB" <?php echo  "value=".$heureString."" ; ?> />                                    
                                                </div>
                                            </div>
											<div class="form-group col-md-10">
                                                    <div class="row">
                                                        <!-- <legend class="col-form-label ">Type de paiement</legend> -->
                                                        <div class="col-sm-3">
                                                        <label >Type </label>   
                                                        </div>
                                                        <div class="col-sm-6">
                                                                <div class="form-check PP">
                                                                    <input class="form-check-input ll" type="radio" name="operateur" id="idCaisse" onclick="isCaisse()" value="Caisse" checked>
                                                                    <label class="form-check-label" >
                                                                                 En espece
                                                                    </label>
                                                                </div>
                                                            <?php
                                                                $sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile' " ;
                                                                $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());
                                                                while($op=mysql_fetch_array($resCm)) { 
                                                                    echo ' <div class="form-check">
                                                                                <input class="form-check-input OOO" type="radio" name="operateur" onclick="isNotCaisse()" value="'.$op['nomCompte'].'" >
                                                                                <label class="form-check-label" for="gridRadios1">
                                                                                    '.$op['nomCompte'].'
                                                                                </label>
                                                                            </div>';

                                                                    }
                                                            ?>
                                                        </div>
                                                    </div>
                                            </div> 
											<div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="categorie"> Montant Total</label>
                                                    <input type="text" class="form-control" name="montantFixePayementTotal" id="montantFixePayementTotalPm"  />
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="datePS">Nombre de Mois</label>
                                                    <input type="text" class="form-control" name="DatePS" id="nbrMoisPm" disabled="true" />
                                                </div> 
                                            </div>
                                             
											<div class="form-group  col-md-10" style="display:none" id="divRef">
												<label for="refTransf">Référence du Transfert </label>
												<input type="text" class="form-control" name="refTransfPm" min="10" max="50" id="refTransfPm" placeholder="Exemple de référence valide CI200802.1100.B23993" required />
											</div>
											<div class="form-group  col-md-10" style="display:none" id="divNumTel">
												<label for="numTel"> Numéro de Téléphone </label>
												<input type="text" class="form-control" name="numTelPm" min="9" max="15" id="numTelPm" placeholder="ici le numero qui reçoit le transfert" required />
											</div>
											<input type="button" id="btnRefTransPlusieursMois" class="boutonbasic" name="btnRefTransPlusieursMois" value="Envoyer transfert >>" />
											</div>
										</form>
									</div>
							</div>
						</div>
				</div>  
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#PAIEMENTPRECEDANT">MOIS PRECEDANT</a></li>
                  <li class=""><a data-toggle="tab" href="#LISTEPERSONNEL">PAIEMENTS EN COURS...</a></li>
                  <li class=""><a data-toggle="tab" href="#LISTEHORS">HORS PARAMETRE</a></li>
                  <li class=""><a data-toggle="tab" href="#LISTENONC">MONTANT NON CONFORME</a></li>
                  <li class=""><a data-toggle="tab" href="#PAIEMENTVITRINE">PAYEMENT VITRINE</a></li>
                  <li class=""><a data-toggle="tab" href="#PLUSIEURMOIS">PAYEMENT PLUSIEURS MOIS</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="PAIEMENTPRECEDANT">
                        <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Téléphone</th>
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
                                    <th>Téléphone</th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Etat</th>
                                    <th>Paiement</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                    $somme=0;
                                    $sql1="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )" ;
                                    $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                                    while($payement=mysql_fetch_array($res1)) {
                                        $somme=$somme+$payement['montantFixePayement'];
                                ?>
                                        <tr>

                                            <td> <b><?php $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement['idBoutique'];
                                                          $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                                                          if ($boutique3 = mysql_fetch_array($res2))
                                                            echo  $boutique3['labelB']; if($payement['variablePayementActiver']==1)  ?> </b> </td>

                                            <td> <b><?php echo  $payement['montantFixePayement']; ?> FCFA  </b>   </td>
                                           <td> <?php ?>  
                                                 <?php
                                                    $tel1a='';
                                                    $tel2a='';
                                                    $sql0a="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique = '".$payement['idBoutique']."' and 
                                                    aPayementBoutique=1 LIMIT 1,2 ";
                                                    $res0a=mysql_query($sql0a);
                                                    $pSaa=mysql_fetch_array($res0a);
                                                    ///////////////////////////////////
                                                    $sql00a="SELECT * FROM `aaa-payement-reference` WHERE idPS = '".$pSaa['idPS']."'";
                                                    $res00a=mysql_query($sql00a);
                                                    $pReferencea=mysql_fetch_array($res00a);
                                                    echo  $pReferencea['telRefTransfertValidation'];
                                                    /*echo  $payement['accompagnateur'];*/
                                                 ?>  
                                            </td>
                                            <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                            <td> <?php echo  $payement['datePS']; ?>  </td>

                                            <?php

                                                 if ($payement['aPayementBoutique']==0) { ?>
                                                    <td><span>En cour...</span></td>
                                                    <td><button type="button" class="btn btn-warning" class="btn btn-success" data-toggle="modal" 
                                                    		<?php echo  "data-target=#validerPaiementB".$payement['idBoutique'] ; ?> > OMoney
                                                    	</button>
														<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementWave".$payement['idBoutique'] ; ?> >Wave
														</button>
														<!-- <button type="button" class="btn btn-primary" class="btn btn-primary" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementPlMois".$payement['idBoutique'] ; ?> >Plusieur M
														</button> -->
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
                                                                            <input type="date" class="form-control" name="dateTransfert"  value="<?php echo $payement['datePS']; ?>"   />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="categorie"> Montant</label>
                                                                            <input type="text" class="form-control" name="montantFixePayement" id="montantFixePayement2" 
                                                                                    <?php echo  "value=".$payement['montantFixePayement']."" ; ?>    />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="montantTransfert"> Avec frais</label>
                                                                            <input type="checkbox" name="avecFrais" id='avecFrais2' onclick="isFrais2()" />
                                                                        </div>
                                                                        <div class="form-group" style="display:none" id="text2">
                                                                            <label for="montantTransfert" id='lbF'> Frais</label>
                                                                            <input type="number" min='0' class="form-control" name="frais" id='inpF2' value="0" />
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
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="refTransf">DATE </label>
                                                                        <input type="date" class="form-control" name="dateTransfert" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Montant </label>
                                                                        <input type="text" class="form-control" name="montantFixePayement"
                                                                            <?php echo  "value=".$payement['montantFixePayement']."" ; ?>  />
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
                                                                        <button type="submit" name="btnValidationPWave" class="btn btn-primary">Validation du paiement</button>
                                                                </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=validerPaiementPlMois".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Validation de paiement Wave</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="payementboutiques.php">
                                                                    <input type="hidden" name="idPS" id="idPS_RtrB" <?php echo  "value=".$payement['idPS']."" ; ?> />
                                                                    <input type="hidden" name="idBoutiqueB" <?php echo  "value=".$payement['idBoutique']."" ; ?> />
                                                                    <input type="hidden" name="datePaiementB" <?php echo  "value=".$dateString."" ; ?> />
                                                                    <input type="hidden" name="heurePaiementB" <?php echo  "value=".$heureString."" ; ?> />
                                                                    <div class="form-group">
                                                                        <label for="dateTransfert"> Date Envoie</label>
                                                                        <input type="Date" class="form-control" name="dateTransfertPm" id="dateTransfertPm" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Référence du Transfert </label>
                                                                        <input type="text" class="form-control" name="refTransfPm" min="10" max="50" id="refTransfPmB" placeholder="Exemple de référence valide CI200802.1100.B23993" required />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant Total</label>
                                                                        <input type="text" class="form-control" name="montantFixePayementTotal" id="montantFixePayementTotalPmB"  />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant mensuel</label>
                                                                        <input type="text" class="form-control" name="montantFixePayementM" id="montantFixePayementMB" disabled="true"
                                                                            <?php echo  "value=".$payement['montantFixePayement']."" ; ?>   />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="datePS">Nombre de Mois</label>
                                                                        <!-- <input type="number" class="form-control" name="DatePS" id="nbrMoisPm" min="2" /> -->
                                                                        <input type="text" class="form-control" name="DatePS" id="nbrMoisPmB" disabled='true' />
                                                                    </div> 
                                                                    <div class="form-group">
                                                                        <?php 
                                                                            $sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile'" ;
                                                                            $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());
                                                                            while($op=mysql_fetch_array($resCm)) { 
                                                                                    echo '<input type="radio" name="operateur" value="'.$op['nomCompte'].'"  /> 
                                                                                        <label >'.$op['nomCompte'].'</label>';
                                                                                }
                                                                        ?>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="numTel"> Numéro de Téléphone </label>
                                                                        <input type="text" class="form-control" name="numTelPm" min="9" max="15" id="numTelPmB" placeholder="ici le numero qui reçoit le transfert" required />
                                                                    </div>

                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnValidationPlMoisB" class="btn btn-primary">Validation du paiement</button>
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
                                ?>
                            <!--    FIN     LES BOUTIQUES QUI ONT PAYE  MOIS PRECEDANT     -->
                              <!--    DEBUT   LES BOUTIQUES QUI ONT PAYE CE MOIS EN COURS     -->

                              <?php

                                        $sql11="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$anneeM."-".$moiM."%') " ;
                                        $dateString=$annee.'-'.$mois.'-'.$jour;
                                        $res11 = mysql_query($sql11) ;
                                        while($payement1=mysql_fetch_array($res11)) {
                                            $sqlp="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$payement1['idBoutique']." and (`datePS` LIKE '%".$anneeM."-".$moiM."%') ORDER BY `aaa-payement-salaire`.`idPS` ";
                                            $resp = mysql_query($sqlp) or die ("personel requête 2".mysql_error());
                                            while($p1=mysql_fetch_array($resp)) {
                                                if ($payement1['idPS']==$p1['idPS']) { ?>
                                                    <tr>
                                                        <td> <b><?php $sql21="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement1['idBoutique'];
                                                                            $res21 = mysql_query($sql21) or die ("personel requête 2".mysql_error());
                                                                            if ($boutique31 = mysql_fetch_array($res21))
                                                                                echo  $boutique31['labelB']; if($payement1['variablePayementActiver']==1)  ?> </b> </td>

                                                                <td> <b><?php echo  $payement1['montantFixePayement']; ?> FCFA  </b>   </td>
                                                                <td> <?php  $tel1a='';
                                                                            $tel2a='';
                                                                            $sql0a="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique = '".$payement1['idBoutique']."' and 
                                                                            aPayementBoutique=1 LIMIT 1,2 ";
                                                                            $res0a=mysql_query($sql0a);
                                                                            $pSaa=mysql_fetch_array($res0a);
                                                                            ///////////////////////////////////
                                                                            $sql00a="SELECT * FROM `aaa-payement-reference` WHERE idPS = '".$pSaa['idPS']."'";
                                                                            $res00a=mysql_query($sql00a);
                                                                            $pReferencea=mysql_fetch_array($res00a);
                                                                            echo  $pReferencea['telRefTransfertValidation'];
                                                                            ?>  
                                                     </td>
                                                                <td>Mois <?php echo  $payement1['etapeAccompagnement']; ?>  </td>
                                                                <td> <?php echo  $payement1['datePS']; 	 ?>  </td>
                                                                <td><span>Effectif</span></td>
                                                                <?php if ($payement1['datePS']<$dateString ): ?>
                                                                <td ><span class="primary">Déja payé</span></td>
                                                                <?php else: ?>
                                                                <td ><span class="primary"> Payé à l'avance</span></td>
                                                                <?php endif ?>
                                                    </tr>
                                                <?php  } else { echo "";?>

                                                <?php }

                                            }?>


                                            <?php
                                            }
                                        ?>
                                        <!--    FIN     LES BOUTIQUES QUI ONT PAYE CE MOIS EN COURS     -->
                            </tbody>
                        </table>
                      </div>
                    <div class="tab-pane fade " id="LISTEPERSONNEL">
                        <table id="exemple1" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Téléphone </th>
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
                                    <th>Téléphone </th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Etat</th>
                                    <th>Paiement</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                $somme=0;
                                $sql1="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$annee."-".$mois."%')" ;
                                $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                                
                                while($payement=mysql_fetch_array($res1)) {
                                    $somme=$somme+$payement['montantFixePayement'];
                                ?>
                                        <tr>
                                            <td> <b><?php $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement['idBoutique'];
                                                          $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                                                          if ($boutique3 = mysql_fetch_array($res2))
                                                            echo  $boutique3['labelB']; if($payement['variablePayementActiver']==1)  ?> </b> </td>

                                            <td> <b><?php echo  $payement['montantFixePayement']; ?> FCFA  </b>   </td>
                                            <td> <?php
                                                    $tel1='';
                                                    $tel2='';
                                                    $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique = '".$payement['idBoutique']."' and 
                                                    aPayementBoutique=1 LIMIT 1,2 ";
                                                    $res0=mysql_query($sql0);
                                                    $pSa=mysql_fetch_array($res0);
                                                    ///////////////////////////////////
                                                    $sql00="SELECT * FROM `aaa-payement-reference` WHERE idPS = '".$pSa['idPS']."'";
                                                    $res00=mysql_query($sql00);
                                                    $pReference=mysql_fetch_array($res00);
                                                    echo  $pReference['telRefTransfertValidation'];
                                                    /*echo  $payement['accompagnateur'];*/
                                                 ?>  
                                            </td>
                                            <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                            <td> <?php echo  $payement['datePS']; ?>  </td>

                                            <?php

                                                 if ($payement['aPayementBoutique']==0) { ?>
                                                    <td><span>En cour...</span></td>
                                                    <td><button type="button" class="btn btn-warning" class="btn btn-success" data-toggle="modal" 
                                                    		<?php echo  "data-target=#validerPaiementB".$payement['idBoutique'] ; ?> > OMoney
                                                    	</button>
														<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementWave".$payement['idBoutique'] ; ?> >Wave
														</button>
														<!-- <button type="button" class="btn btn-primary" class="btn btn-primary" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementPlMois".$payement['idBoutique'] ; ?> >Plusieur M
														</button> -->
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
                                                                            <input type="date" class="form-control" name="dateTransfert"  value="<?php echo $payement['datePS']; ?>"   />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="categorie"> Montant</label>
                                                                            <input type="text" class="form-control" name="montantFixePayement" id="montantFixePayement2" 
                                                                                    <?php echo  "value=".$payement['montantFixePayement']."" ; ?>    />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="montantTransfert"> Avec frais</label>
                                                                            <input type="checkbox" name="avecFrais" id='avecFrais2' onclick="isFrais2()" />
                                                                        </div>
                                                                        <div class="form-group" style="display:none" id="text2">
                                                                            <label for="montantTransfert" id='lbF'> Frais</label>
                                                                            <input type="number" min='0' class="form-control" name="frais" id='inpF2' value="0" />
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
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="refTransf">DATE </label>
                                                                        <input type="date" class="form-control" name="dateTransfert" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Montant </label>
                                                                        <input type="text" class="form-control" name="montantFixePayement"
                                                                            <?php echo  "value=".$payement['montantFixePayement']."" ; ?>  />
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
                                                                        <button type="submit" name="btnValidationPWave" class="btn btn-primary">Validation du paiement</button>
                                                                </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=validerPaiementPlMois".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Validation de paiement Wave</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="payementboutiques.php">
                                                                    <input type="hidden" name="idPS" id="idPS_RtrB" <?php echo  "value=".$payement['idPS']."" ; ?> />
                                                                    <input type="hidden" name="idBoutiqueB" <?php echo  "value=".$payement['idBoutique']."" ; ?> />
                                                                    <input type="hidden" name="datePaiementB" <?php echo  "value=".$dateString."" ; ?> />
                                                                    <input type="hidden" name="heurePaiementB" <?php echo  "value=".$heureString."" ; ?> />
                                                                    <div class="form-group">
                                                                        <label for="dateTransfert"> Date Envoie</label>
                                                                        <input type="Date" class="form-control" name="dateTransfertPm" id="dateTransfertPm" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Référence du Transfert </label>
                                                                        <input type="text" class="form-control" name="refTransfPm" min="10" max="50" id="refTransfPmB" placeholder="Exemple de référence valide CI200802.1100.B23993" required />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant Total</label>
                                                                        <input type="text" class="form-control" name="montantFixePayementTotal" id="montantFixePayementTotalPmB"  />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant mensuel</label>
                                                                        <input type="text" class="form-control" name="montantFixePayementM" id="montantFixePayementMB" disabled="true"
                                                                            <?php echo  "value=".$payement['montantFixePayement']."" ; ?>   />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="datePS">Nombre de Mois</label>
                                                                        <!-- <input type="number" class="form-control" name="DatePS" id="nbrMoisPm" min="2" /> -->
                                                                        <input type="text" class="form-control" name="DatePS" id="nbrMoisPmB" disabled='true' />
                                                                    </div> 
                                                                    <div class="form-group">
                                                                        <?php 
                                                                            $sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile'" ;
                                                                            $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());
                                                                            while($op=mysql_fetch_array($resCm)) { 
                                                                                    echo '<input type="radio" name="operateur" value="'.$op['nomCompte'].'"  /> 
                                                                                        <label >'.$op['nomCompte'].'</label>';
                                                                                }
                                                                        ?>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="numTel"> Numéro de Téléphone </label>
                                                                        <input type="text" class="form-control" name="numTelPm" min="9" max="15" id="numTelPmB" placeholder="ici le numero qui reçoit le transfert" required />
                                                                    </div>

                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnValidationPlMoisB" class="btn btn-primary">Validation du paiement</button>
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

                                $sql11="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%') " ;
                                $dateString=$annee.'-'.$mois.'-'.$jour;
                                $res11 = mysql_query($sql11) ;
                                while($payement1=mysql_fetch_array($res11)) {
                                    $sqlp="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$payement1['idBoutique']." and (`datePS` LIKE '%".$annee."-".$mois."%') ORDER BY `aaa-payement-salaire`.`idPS` ";
                                    $resp = mysql_query($sqlp) or die ("personel requête 2".mysql_error());
                                     while($p1=mysql_fetch_array($resp)) {
                                          if ($payement1['idPS']==$p1['idPS']) { ?>
                                             <tr>
                                                <td> <b><?php $sql21="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement1['idBoutique'];
                                                                      $res21 = mysql_query($sql21) or die ("personel requête 2".mysql_error());
                                                                      if ($boutique31 = mysql_fetch_array($res21))
                                                                        echo  $boutique31['labelB']; if($payement1['variablePayementActiver']==1)  ?> </b> </td>

                                                        <td> <b><?php echo  $payement1['montantFixePayement']; ?> FCFA  </b>   </td>
                                                        <td> <?php //echo  $payement1['accompagnateur']; 
                                                                $tel1='';
                                                                $tel2='';
                                                                $sql0a="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique = '".$payement1['idBoutique']."' and 
                                                                aPayementBoutique=1 LIMIT 1,2 ";
                                                                $res0a=mysql_query($sql0a);
                                                                $pSa=mysql_fetch_array($res0a);
                                                                ///////////////////////////////////
                                                                $sql00a="SELECT * FROM `aaa-payement-reference` WHERE idPS = '".$pSa['idPS']."'";
                                                                $res00a=mysql_query($sql00a);
                                                                $pReferencea=mysql_fetch_array($res00a);
                                                                echo  $pReferencea['telRefTransfertValidation'];
                                                            ?>  
                                                        </td>
                                                        <td>Mois <?php echo  $payement1['etapeAccompagnement']; ?>  </td>
                                                        <td> <?php echo  $payement1['datePS']; 	 ?>  </td>
                                                        <td><span>Effectif</span></td>
                                                        <?php if ($payement1['datePS']<$dateString ): ?>
                                                        <td ><span class="primary">Déja payé</span></td>
                                                        <?php else: ?>
                                                        <td ><span class="primary"> Payé à l'avance</span></td>
                                                        <?php endif ?>
                                            </tr>
                                          <?php  } else { echo "";?>

                                         <?php }

                                     }?>


                                    <?php
                                     }
                                ?>
                            <!--    FIN     LES BOUTIQUES QUI ONT PAYE CE MOIS EN COURS     -->
                            </tbody>
                        </table>
                      </div>
                    <div class="tab-pane fade " id="LISTEHORS">
                        <table id="exemple2" class="display" border="1" class="table table-bordered table-striped" id="userTable">
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
                                                                echo  $boutique31['labelB']; if($payement1['variablePayementActiver']==1)  ?> </b> </td>
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
                        <table id="exemple3" class="display" border="1" class="table table-bordered table-striped" id="userTable">
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
                                                            echo  $boutique31['labelB'];   ?> </b>
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
                    <div class="tab-pane fade " id="PAIEMENTVITRINE">
                        <table id="exemple4" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Montant Paiement</th>
                                    <th>Téléphone </th>
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
                                    <th>Téléphone </th>
                                    <th>Etape Accompagnement</th>
                                    <th>Date Calcul</th>
                                    <th>Etat</th>
                                    <th>Paiement</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                $somme=0;

                                /* $req3 = $conn->prepare("SELECT * FROM `aaa-payement-salaire`  WHERE idBoutique = :idBoutique  and profil=:profil");
											$req3->execute(array(
													'idBoutique' =>$boutique['idBoutique'],
													'profil' =>'Admin'))  or die(print_r($req3->errorInfo()));
											
								while ($acces = $req3->fetch()) {

                                } */
                                
                                $sql1="SELECT * FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$annee."-".$mois."%')" ;
                                $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                                while($payement=mysql_fetch_array($res1)) {
                                    $somme=$somme+$payement['montantFixePayement'];
                                ?>
                                        <tr>
                                            <td> <b><?php $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement['idBoutique'];
                                                          $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                                                          if ($boutique3 = mysql_fetch_array($res2))
                                                            echo  $boutique3['labelB']; if($payement['variablePayementActiver']==1)  ?> </b> </td>

                                            <td> <b><?php echo  $payement['montantFixePayement']; ?> FCFA  </b>   </td>
                                            <td> <?php
                                                    $tel1='';
                                                    $tel2='';
                                                    $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique = '".$payement['idBoutique']."' and 
                                                    aPayementBoutique=1 LIMIT 1,2 ";
                                                    $res0=mysql_query($sql0);
                                                    $pSa=mysql_fetch_array($res0);
                                                    ///////////////////////////////////
                                                    $sql00="SELECT * FROM `aaa-payement-reference` WHERE idPS = '".$pSa['idPS']."'";
                                                    $res00=mysql_query($sql00);
                                                    $pReference=mysql_fetch_array($res00);
                                                    echo  $pReference['telRefTransfertValidation'];
                                                    /*echo  $payement['accompagnateur'];*/
                                                 ?>  
                                            </td>
                                            <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                            <td> <?php echo  $payement['datePS']; ?>  </td>

                                            <?php

                                                 if ($payement['aPayementBoutique']==0) { ?>
                                                    <td><span>En cour...</span></td>
                                                    <td><button type="button" class="btn btn-warning" class="btn btn-success" data-toggle="modal" 
                                                    		<?php echo  "data-target=#validerPaiementB".$payement['idBoutique'] ; ?> > OMoney
                                                    	</button>
														<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementWave".$payement['idBoutique'] ; ?> >Wave
														</button>
														<!-- <button type="button" class="btn btn-primary" class="btn btn-primary" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementPlMois".$payement['idBoutique'] ; ?> >Plusieur M
														</button> -->
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
                                                                            <input type="date" class="form-control" name="dateTransfert"  value="<?php echo $payement['datePS']; ?>"   />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="categorie"> Montant</label>
                                                                            <input type="text" class="form-control" name="montantFixePayement" id="montantFixePayement2" 
                                                                                    <?php echo  "value=".$payement['montantFixePayement']."" ; ?>    />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="montantTransfert"> Avec frais</label>
                                                                            <input type="checkbox" name="avecFrais" id='avecFrais2' onclick="isFrais2()" />
                                                                        </div>
                                                                        <div class="form-group" style="display:none" id="text2">
                                                                            <label for="montantTransfert" id='lbF'> Frais</label>
                                                                            <input type="number" min='0' class="form-control" name="frais" id='inpF2' value="0" />
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
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="refTransf">DATE </label>
                                                                        <input type="date" class="form-control" name="dateTransfert" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Montant </label>
                                                                        <input type="text" class="form-control" name="montantFixePayement"
                                                                            <?php echo  "value=".$payement['montantFixePayement']."" ; ?>  />
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
                                                                        <button type="submit" name="btnValidationPWave" class="btn btn-primary">Validation du paiement</button>
                                                                </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=validerPaiementPlMois".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Validation de paiement Wave</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="payementboutiques.php">
                                                                    <input type="hidden" name="idPS" id="idPS_RtrB" <?php echo  "value=".$payement['idPS']."" ; ?> />
                                                                    <input type="hidden" name="idBoutiqueB" <?php echo  "value=".$payement['idBoutique']."" ; ?> />
                                                                    <input type="hidden" name="datePaiementB" <?php echo  "value=".$dateString."" ; ?> />
                                                                    <input type="hidden" name="heurePaiementB" <?php echo  "value=".$heureString."" ; ?> />
                                                                    <div class="form-group">
                                                                        <label for="dateTransfert"> Date Envoie</label>
                                                                        <input type="Date" class="form-control" name="dateTransfertPm" id="dateTransfertPm" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Référence du Transfert </label>
                                                                        <input type="text" class="form-control" name="refTransfPm" min="10" max="50" id="refTransfPmB" placeholder="Exemple de référence valide CI200802.1100.B23993" required />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant Total</label>
                                                                        <input type="text" class="form-control" name="montantFixePayementTotal" id="montantFixePayementTotalPmB"  />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="categorie"> Montant mensuel</label>
                                                                        <input type="text" class="form-control" name="montantFixePayementM" id="montantFixePayementMB" disabled="true"
                                                                            <?php echo  "value=".$payement['montantFixePayement']."" ; ?>   />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="datePS">Nombre de Mois</label>
                                                                        <!-- <input type="number" class="form-control" name="DatePS" id="nbrMoisPm" min="2" /> -->
                                                                        <input type="text" class="form-control" name="DatePS" id="nbrMoisPmB" disabled='true' />
                                                                    </div> 
                                                                    <div class="form-group">
                                                                        <?php 
                                                                            $sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile'" ;
                                                                            $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());
                                                                            while($op=mysql_fetch_array($resCm)) { 
                                                                                    echo '<input type="radio" name="operateur" value="'.$op['nomCompte'].'"  /> 
                                                                                        <label >'.$op['nomCompte'].'</label>';
                                                                                }
                                                                        ?>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="numTel"> Numéro de Téléphone </label>
                                                                        <input type="text" class="form-control" name="numTelPm" min="9" max="15" id="numTelPmB" placeholder="ici le numero qui reçoit le transfert" required />
                                                                    </div>

                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnValidationPlMoisB" class="btn btn-primary">Validation du paiement</button>
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

                            
                            <!--    FIN     LES BOUTIQUES QUI ONT PAYE CE MOIS EN COURS     -->
                            </tbody>
                        </table>
                      </div>
                    
                    <div class="tab-pane fade " id="PLUSIEURMOIS">
                            <table id="exemple5" class="display" border="1" class="table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <th>Boutique</th>
                                        <th>Montant mensuel</th>
                                        <th>Montant Total</th>
                                        <th>Date paiement multiple</th>
                                        <th>Description</th>
                                        <th>Date debut paiement</th>
                                        <th>Date fin de paiement</th>
                                        <th>Nombre de mois payé</th>
                                        <th>Nombre de mois avancé</th>
                                        <th>Nombre de mois restant</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Boutique</th>
                                        <th>Montant mensuel</th>
                                        <th>Montant Total</th>
                                        <th>Date paiement multiple</th>
                                        <th>Description</th>
                                        <th>Date debut paiement</th>
                                        <th>Date fin de paiement</th>
                                        <th>Nombre de mois payé</th>
                                        <th>Nombre de mois avancé</th>
                                        <th>Nombre de mois restant</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                        //
                                            $nbrMois=1;
                                            $dateInit=$annee.'-'.$mois.'-'.'01';
                                            $finMois = date('Y-m-d', strtotime($dateInit.' + '.$nbrMois.' month'));
                                                        //Pour se positionner sur le dernier jour du mois
                                            $finMois= date( 'Y-m-d', strtotime( $finMois . '-1 day') );
                                            //var_dump($finMois);
                                        //

                                    //les boutiques en phase d'esploitation
                                    //$sql2="SELECT * FROM `aaa-payement-pmois` GROUP BY idBoutique order by idPPM DESC";
                                    //$sql2="SELECT * FROM `aaa-payement-pmois` WHERE idPPM IN ( SELECT MAX(idPPM) FROM `aaa-payement-pmois` GROUP BY idBoutique ) and dateFin>=:finM ORDER BY idPPM DESC ";
                                    //$res2 = mysql_query($sql2) or die ("boutique requête 222 ".mysql_error());
                                    //var_dump("1");
                                    
                                    $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-pmois` WHERE dateFin>:finMois and idPPM IN ( SELECT MAX(idPPM) FROM `aaa-payement-pmois` GROUP BY idBoutique ) ORDER BY idPPM DESC "); 
                                    $req2->execute(array('finMois' =>$finMois))  or die(print_r($req2->errorInfo())); 
                                    

                                    while ($pr =$req2->fetch()) {
                                            $dateToday  = $date;
                                            $debutDate  = new DateTime($pr['dateDebut']);
                                            $finDate = new DateTime($pr['dateFin']);

                                            $firstDateRestant = $pr['dateDebut'];
                                            $secondDateRestant = $pr['dateFin'];

                                            $dateDifferenceRestant = abs(strtotime($secondDateRestant) - strtotime($firstDateRestant));

                                            $yearsRestant  = floor($dateDifferenceRestant / (365 * 60 * 60 * 24));
                                            $monthsRestant = floor(($dateDifferenceRestant - $yearsRestant * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                            $daysRestant   = floor(($dateDifferenceRestant - $yearsRestant * 365 * 60 * 60 * 24 - $monthsRestant * 30 * 60 * 60 *24) / (60 * 60 * 24));
                                            $valeur=$yearsRestant*12+$monthsRestant;
                                            if ($valeur<0) {
                                                continue;
                                            }
                                            ?>
                                            <tr>                                          
                                                <td> <b><?php 
                                                        $sql3="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$pr['idBoutique'];
                                                        $res3 = mysql_query($sql3) or die ("personel requête 2".mysql_error());
                                                          if ($boutique3 = mysql_fetch_array($res3))
                                                            echo  $boutique3['labelB'];  ?>   </b>   </td>
                                                            <td> <b><?php echo  $pr['montantMensuel']; ?>   </b>   </td>
                                                            <td> <b><?php echo  $pr['montantTotal']; ?>   </b>   </td>
                                                            <td> <?php echo  $pr['datePaiement']; ?>  </td>
                                                            <td> <?php echo  $pr['description']; ?>  </td>
                                                            <td> <?php echo  $pr['dateDebut']; ?>  </td>
                                                            <td> <?php echo  $pr['dateFin']; ?>  </td>
                                                            <td> <?php echo  $pr['nombreMois']; ?>  </td>
                                                            <td> <span style="color:green;">
                                                    <?php /*
                                                        $start = new DateTime('2020-01-01 00:00:00');
                                                        $end = new DateTime('2021-03-15 00:00:00');
                                                        $diff = $start->diff($end);
                                                        $yearsInMonths = $diff->format('%r%y') * 12;
                                                        $months = $diff->format('%r%m');
                                                        $totalMonths = $yearsInMonths + $months;*/


                                                        //$d1=$dateToday->diff($debutDate);
                                                        $diff =$debutDate->diff($dateToday);
                                                        $yearsInMonths = $diff->format('%r%y') * 12;
                                                        $months = $diff->format('%r%m');
                                                        $totalMonths = $yearsInMonths + $months;
                                                        echo $totalMonths;
                                                        
                                                      ?> 
                                                      </span> 
                                                </td>
                                                <td> <span style="color:red;"><?php 
                                                        $restant=$pr['nombreMois']- $totalMonths;
                                                        echo $restant;
                                                    ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-success" onclick="imprimerPPM(<?php echo $pr['idPPM']?>)" >Facture</button>
                                                </td>
                                            </tr>
                                        <?php
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
</div>
<script>

</script>
</body>
</html>
