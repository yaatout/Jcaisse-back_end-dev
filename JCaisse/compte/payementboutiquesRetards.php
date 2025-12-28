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
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$payement['idBoutique'] ; ?> >
						                        Payer</button>
												</td>
												<?php 
											} else { ?>
												<td><span>Effectif</span></td>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$payement['idBoutique'] ; ?> >
												Annuler</button></td>
											<?php }
											
											
											 ?>
														

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