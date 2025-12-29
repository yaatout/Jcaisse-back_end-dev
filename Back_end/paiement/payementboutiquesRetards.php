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

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

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
	
	
	//les boutiques en phase d'esploitation
	$sql2="SELECT * FROM `aaa-boutique` WHERE enTest =1";
	$res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
	while ($boutique = mysql_fetch_array($res2)) { 
		//volume moyenne des données de chaque boutique
		 
			$volumeMoyenne=0;
			
			$tailleCatal=0;
			$nomtableDesignation=$boutique["nomBoutique"]."-designation";
			$sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
			$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
			if($compteur = mysql_fetch_array($res))
				$tailleCatal=$compteur["nbreRef"]; 
			
			$tailleStocks=0;
			$nomtableStock=$boutique["nomBoutique"]."-stock";
			$sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=0";
			$res = mysql_query($sql) or die ("compte references requête ".mysql_error());
			if($compteur = mysql_fetch_array($res))
				$tailleStocks=$compteur["nbreStock"];
			
			$volumeMoyenne=	($tailleCatal+$tailleStocks)/2;		
	/*		
		SELECT * FROM `aaa-variablespayement` where typecaisse='Alimentaire' and categoriecaisse='Grossiste' and moyenneVolumeMin<=9.5 and moyenneVolumeMax>=9.5
		SELECT * FROM `aaa-variablespayement` where typecaisse='Multi-service' and categoriecaisse='Detaillant' and moyenneVolumeMin<=249.5 and moyenneVolumeMax>=249.5
		SELECT * FROM `aaa-variablespayement` where typecaisse='Multi-service' and categoriecaisse='Detaillant' and moyenneVolumeMin<=114 and moyenneVolumeMax>=114
		SELECT * FROM `aaa-variablespayement` where typecaisse='Pharmacie' and categoriecaisse='Detaillant' and moyenneVolumeMin<=1296 and moyenneVolumeMax>=1296
		SELECT * FROM `aaa-variablespayement` where typecaisse='Pharmacie' and categoriecaisse='Detaillant' and moyenneVolumeMin<=797 and moyenneVolumeMax>=797
	*/		
			
		//recherche des varibles de paiement de chaque boutique
		$sql3="SELECT * FROM `aaa-variablespayement` where typecaisse='".$boutique["type"]."' and categoriecaisse='".$boutique["categorie"]."' and moyenneVolumeMin<=".$volumeMoyenne." and moyenneVolumeMax>=".$volumeMoyenne;
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
		$sql3="SELECT * FROM `aaa-payement-salaire` where (datePS='".$dateString."' or datePS='".$dateString2."') and idBoutique=".$boutique["idBoutique"];
		$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
		if(!@mysql_num_rows($res3)){
			if($variable["activerMontant"]==1){
				
				/*if($etapeAccompagnement==1){
					$partAccompagnateur=$variable["montant"]*50/100;
				}else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
					$partAccompagnateur=$variable["montant"]*20/100;
				}*/
				
			    $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement) values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",1,".$etapeAccompagnement.")";
				//echo $sql6.'</br>';
				$res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
			
			}elseif($variable["activerPourcentage"]==1){
				
				$sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement) values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",2,".$etapeAccompagnement.")";
				//echo $sql6.'</br>';
				$res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
			
			}elseif($variable["activerPrix"]==1){
				
				$sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement) values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateString."',".$variable["montant"].",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",3,".$etapeAccompagnement.")";
				//echo $sql6.'</br>';
				$res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
				
			}
		}else{
			if($variable["activerMontant"]==1){
				
				/*if($etapeAccompagnement==1){
						$partAccompagnateur=$variable["montant"]*50/100;
					}else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
						$partAccompagnateur=$variable["montant"]*20/100;
					}*/
					
				$sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=1,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"];
				//echo $sql6.'</br>';
				$res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
			
			}else if($variable["activerPourcentage"]==1){
				
				$sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=2,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"];
				//echo $sql6.'</br>';
				$res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
				
			}else if($variable["activerPrix"]==1){
				
				$sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=3,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"];
				//echo $sql6.'</br>';
				$res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());  
			}
		}
					
		}
		
	}
}


if (isset($_POST['btnActiver'])) {
	$idBoutique=$_POST['idBoutique'];
	$activer=1;
	$sql3="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
	
} elseif (isset($_POST['btnDesactiver'])) {
	$idBoutique=$_POST['idBoutique'];
	$activer=0;
	$sql3="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
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
						$sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' ,refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."',
									datePaiement='".$dateString."', heurePaiement='".$heureString."', aPayementBoutique='".$activer."'  where idPS=".$idPS;
											
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
											/* $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where idPS=".$idPS;
											//var_dump($sql5);
											$res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5 ".mysql_error()); */

											$sql="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' ,refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."',
													datePaiement='".$dateString."', heurePaiement='".$heureString."', aPayementBoutique='".$activer."'  where idPS=".$idPS;
											//var_dump($sql);
											$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
							}						
					}
			}
}


require('entetehtml.php');
?>

<body>

	<?php   require('header.php');


    ?>


		<div class="row">
			<div class="">

			</div>
			<div class="">
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                  <div class="container">
                 <center>
                <?php
                    $somme=0;
                    $mois2=$mois-1;
                    $sql1="SELECT * FROM `aaa-payement-salaire`  WHERE ( datePS  NOT LIKE '%".$annee."-".$mois."%' ) and aPayementBoutique=0";
            //        $sql1="SELECT idBoutique FROM `aaa-payement-salaire` WHERE `datePS` LIKE '%".$annee."-0".$mois2."%' and aPayementBoutique=0" ;
                    $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                    while($boutiqueP=mysql_fetch_array($res1)) {
                        $sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                        $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                        while($payement=mysql_fetch_array($res4)) {
                            $somme=$somme+$payement['montantFixePayement'];
                        }
                    }
                ?>

                <div class="jumbotron noImpr">

            <!--        <h2>Mois : <?php echo "0".($mois-1)."-".$annee; ?></h2>-->
                    <h2>Mois : <?php echo "".($mois-1)."-".$annee; ?></h2>

                    <p>Total Paiements Effectifs: <font color="red"><?php echo $somme; ?> FCFA</font></p>

                </div>
                </center>
	<?php
	if($_SESSION['profil']=="SuperAdmin"){ ?>
	<center>
		<div class="modal-body">
			<form name="formulairePayementBoutiques" method="post" action="payementboutiques.php">
			  <div>
				<!--button type="submit" name="payementBoutiques" class="btn btn-success"> Recalcul des paiements </button-->
			   </div>
			</form>
		</div>
	</center>
<?php
	} ?>


            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PAIEMENTS EFFECTIFS</a></li>
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
                            $somme=0;
							$mois2=$mois-1;
//                            var_dump($mois2);
//							$sql1="SELECT idBoutique FROM `aaa-payement-salaire` WHERE `datePS` LIKE '%".$annee."-0".$mois2."%' and aPayementBoutique=0" ;
							/*$sql1="SELECT idBoutique FROM `aaa-payement-salaire` WHERE ( datePS LIKE '%".$annee."-".$mois2."%' or datePS LIKE '%".$mois2."-".$annee."%' ) and aPayementBoutique=0" ;
							echo $sql1;
                            $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
							while($boutiqueP=mysql_fetch_array($res1)) {*/		
							$sql4="SELECT * FROM `aaa-payement-salaire`  WHERE ( datePS  NOT LIKE '%".$annee."-".$mois."%' ) and aPayementBoutique=0" ;
							//$sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
							$res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
							while($payement=mysql_fetch_array($res4)) {
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
												<td><button type="button" class="btn btn-warning" class="btn btn-success" data-toggle="modal" 
                                                    		<?php echo  "data-target=#validerPaiementB".$payement['idBoutique'] ; ?> > OMoney
                                                    </button>
													<button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" 
																<?php echo  "data-target=#validerPaiementWave".$payement['idBoutique'] ; ?> >Wave
													</button>
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
                                                                <form name="formulaireVersement" method="post" >
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
                                                                <form name="formulaireVersement" method="post" >

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

                                                <div class="modal fade" <?php echo  "id=Activer".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Effectuer le paiement</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="payementboutiques.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment effectuer le paiement ? </h2>
                                                                     <p>Montant à payer : <font color="red"><?php echo $payement['montantFixePayement']; ?> FCFA</font></p>
                                                                     <p>Type de paiement : <font color="red">Orange Money</font></p>
                                                                     <p>Numéro du payeur : <font color="red">778568542</font></p>
                                                                     <p>Numéro du récepteur : <font color="red">775243594</font></p>

                                                                     <p>Date de paiement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                     <p>Heure de paiement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                     <p>Etat du paiement : <font color="red">En Préparation</font></p>
                                                                     <input type="hidden" name="idBoutique" <?php echo  "value=". $payement['idBoutique']."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Effectuer le paiement</button>
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
                                                                     <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($payement['idBoutique'])."" ; ?> >
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
							   // }
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