<?php
/*
*/
//echo 'dans    ';

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

?>

<div>

	<?php   
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
                                $somme01=0;
                                $sql01 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and pPlusieursMois=:p   and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )"); 
                                $sql01->execute(array('a' =>1,  'p' =>0 ))  or die(print_r($sql01->errorInfo()));                                                         
                                $total =$sql01->fetch();
                                $somme01=$somme01+$total['total'];

                            ///////////////////////////////////////////////////////////////////////
		                        $somme11=0;
                                $sql11 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and pPlusieursMois!=:p   and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )"); 
                                
                                $sql11->execute(array('a' =>1,  'p' =>0 ))  or die(print_r($sql11->errorInfo()));                                                         
                                $total =$sql11->fetch();
                                $somme11=$somme11+$total['total'];

                            ///////////////////////////////////////////////////////////////////////
		                      $somme10=0;

                              $sql10 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )"); 
                              $sql10->execute(array('a' =>1 ))  or die(print_r($sql10->errorInfo()));                                                         
                              $total =$sql10->fetch();
                              $somme10=$somme10+$total['total'];
                          
	                        ///////////////////////////////////////////////////////////////////////
                             $somme=0;

                              $sql0 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )"); 
                              $sql0->execute(array('a' =>0 ))  or die(print_r($sql0->errorInfo()));                                                         
                              $total =$sql0->fetch();
                              $somme=$somme+$total['total'];
                            
							/*************************FIN POUR MOIS PASSE ****************************/

							/*************************DEBUT POUR CE MOIS  ****************************/
                            $somme02=0;
                                $sql02 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and pPlusieursMois=:p   and (`datePS` LIKE '%".$annee."-".$mois."%' )"); 
                                $sql02->execute(array('a' =>1,  'p' =>0 ))  or die(print_r($sql02->errorInfo()));                                                         
                                $total =$sql02->fetch();
                                $somme02=$somme02+$total['total'];

                            ///////////////////////////////////////////////////////////////////////
		                        $somme40=0;
                                    $sql40 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and pPlusieursMois!=:p   and (`datePS` LIKE '%".$annee."-".$mois."%' )"); 
                                    $sql40->execute(array('a' =>1,  'p' =>0 ))  or die(print_r($sql40->errorInfo()));                                                         
                                    $total =$sql40->fetch();
                                    $somme40=$somme40+$total['total'];

                            ///////////////////////////////////////////////////////////////////////
		                      $somme30=0;

                                $sql30 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and (`datePS` LIKE '%".$annee."-".$mois."%' )"); 
                                $sql30->execute(array('a' =>1 ))  or die(print_r($sql30->errorInfo()));                                                         
                                $total =$sql30->fetch();
                                $somme30=$somme30+$total['total'];
                          
	                        ///////////////////////////////////////////////////////////////////////
                             $somme20=0;

                              $sql20 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and (`datePS` LIKE '%".$annee."-".$mois."%' )"); 
                              $sql20->execute(array('a' =>0 ))  or die(print_r($sql20->errorInfo()));                                                         
                              $total =$sql20->fetch();
                              $somme20=$somme20+$total['total'];
		                    /*************************FIN POUR CE MOIS  ****************************/
                    ?>
                     <?php
                        if($_SESSION['profil']=="SuperAdmin" and  $annee.'-'.$mois.'-25'<=$dateString AND $dateString <= $anneeN2.'-'.$moisN2.'-05'){ 
                        ?>
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
                                                    
                                                <form name="formulairePayementBoutiques" method="post" >
                                                    <h1> Voulez-vous vraiment recalculer les paiements </h1>
                                                        <div class="modal-footer row">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" name="payementBoutiquesX9" id="btnRecalcule" class="btn btn-success"> Confirmer</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            </center><br>
                      <?php } ?>
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
                                        <form name="formulaireVersement" id="formulaireVersementOM" method="POST">
                                            <input type="hidden" name="operation" id="operation" value="versementPaiOM"  />
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
                                                <button type="button" name="btnReferencetransfertsOrange99999999999" id="btnReferencetransfertsOrange" class="btn btn-primary">Envoyer Référence Transfert</button>
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
                                        <form name="formulaireVersement" id="formulaireVersementWav" method="post" >
                                                <input type="hidden" name="operation"  value="versementPaiWav"  />
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
                                                    <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransfw" value="" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="numTel"> Numéro de Téléphone </label>
                                                    <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel2" value="" />
                                                </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="button" name="btnReferencetransfertsWave" id="btnReferencetransfertsWave" class="btn btn-primary">Envoyer Référence Transfert</button>
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
													// $sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile'" ;
					                                // $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());
					                                // while($op=mysql_fetch_array($resCm)) { 
						                            //     	echo '<input type="radio" name="operateur" value="'.$op['nomCompte'].'"  /> 
						                            //     		<label >'.$op['nomCompte'].'</label>';
					                                // 	}
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
                                                                // $sqlCm="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile' " ;
                                                                // $resCm = mysql_query($sqlCm) or die ("etape requête 4".mysql_error());

                                                                $sqlv = $bdd->prepare("SELECT * from `aaa-compte` WHERE typeCompte=:tc");     
                                                                $sqlv->execute(array('tc'=>'compte mobile'))  or die(print_r($sqlv->errorInfo()));  
                                                                //$compte= $sqlv->fetch();

                                                                while($op= $sqlv->fetch()) { 
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
                <div class="modal fade" id="validerPaiementOMPop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Validation de paiement Orange Money</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                               <form name="validerPaiementOMForm"  id="validerPaiementOMForm" method="POST" >
                                                                        <input type="hidden" name="idPS" id="idPS_Rtr"  />
                                                                        <input type="hidden" name="operation" id="operation" value="validationPaiOM"  />
                                                                   
                                                                        <div class="form-group">
                                                                            <label for="datePS"> Mois</label>
                                                                            <input type="date" class="form-control" name="dateTransOMValidation"  id="dateTransOMValidation" />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="categorie"> Montant</label>
                                                                            <input type="text" class="form-control" name="montantFPaiementOMValidation" id="montantFPaiementOMValidation"/>
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
                                                                            <input type="text" class="form-control" name="refTransfOMValidation" min="10" max="50" id="refTransfOMValidation" value=""  required />
                                                                            <!--  <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransfXX" value="" autofocus="" required pattern="[A-Z]{2}\d{6}+\.\d{4}+\.\d{5}"  />-->
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="numTel"> Numéro de Téléphone </label>
                                                                            <input type="text" class="form-control" name="numTelOMValidation" min="9" max="15" id="numTelOMValidation" value="" required />
                                                                        </div>

                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="button" name="btnValidationP" onClick="savePaiementOM()" class="btn btn-primary">Validation du paiement</button>
                                                                </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                </div>

                <div class="modal fade" id="validerPaiementWavePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Validation de paiement Wave</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement"  id="validerPaiementWavForm" method="POST"> 
                                                                    <input type="hidden" name="idPS" id="i_w" /> 
                                                                    <input type="hidden" name="operation" id="operation" value="validationPaiWav"  />
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="refTransf">DATE </label>
                                                                        <input type="date" class="form-control" name="dateTransfert" id="dateTransfertWavValid" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Montant </label>
                                                                        <input type="text" class="form-control" name="montantFixePayement" id="montantFixePayementWavValid"  />
                                                                    </div>                                             
                                                                    <div class="form-group">
                                                                        <label for="refTransf">Référence du Transfert </label>
                                                                        <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransfWavValid" value="" />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="numTel"> Numéro de Téléphone </label>
                                                                        <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTelWavValidation" value="" />
                                                                    </div>

                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="button" name="btnValidationPWave999" onClick="savePaiementWave()"  class="btn btn-primary">Validation du paiement</button>
                                                                </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                </div>
                <!-- <div class="modal fade" <?php echo  "id=Desactiver".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                </div> -->
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#PAIEMENTPRECEDANT">MOIS PRECEDANT</a></li>
                  <li class=""><a data-toggle="tab" href="#MOISENCOURS" onClick="pEnCours()">PAIEMENTS EN COURS...</a></li>
                  <!-- <li class=""><a data-toggle="tab" href="#LISTEHORS" onClick="pHors()">HORS PARAMETRE</a></li> -->
                  <li class=""><a data-toggle="tab" href="#PLUSIEURMOIS" onClick="pPlusMois()">PAYEMENT PLUSIEURS MOIS</a></li>
                  <li class=""><a data-toggle="tab" href="#LISTEALERTE" >ABSENTE DE CE MOIS</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="PAIEMENTPRECEDANT" >
						<div class="table-responsive">
							<!--  -->
								<div class="table-responsive"> 
										<label class="pull-left" for="nbEntrePrec">Nombre entrées </label>
										<select class="pull-left" id="nbEntrePrec">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputPrec" placeholder="Rechercher...">
										<div id="resultsPrec"><!-- content will be loaded here --></div>
								</div>
						</div>
					</div>
                    <div class="tab-pane fade" id="MOISENCOURS" >
                        <div class="table-responsive">
								<div class="table-responsive"> 
										<label class="pull-left" for="nbEntreEnCours">Nombre entrées </label>
										<select class="pull-left" id="nbEntreEnCours">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputEnCours" placeholder="Rechercher...">
										<div id="resultsEnCours"><!-- content will be loaded here --></div>
								</div>
						</div>
                    </div>                    
                    
                    <div class="tab-pane fade" id="PLUSIEURMOIS" >
                        <div class="table-responsive">
								<div class="table-responsive"> 
										<label class="pull-left" for="nbEntrePlusMois">Nombre entrées </label>
										<select class="pull-left" id="nbEntrePlusMois">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputPlusMois" placeholder="Rechercher...">
										<div id="resultsPlusMois"><!-- content will be loaded here --></div>
								</div>
						</div>
                    </div>
                    <div class="tab-pane fade " id="LISTEALERTE">
                        <table id="exemple4" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Type & Catégorie</th>
                                    <th>Valeur stockage</th>  
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Boutique</th>
                                    <th>Type & Catégorie</th>
                                    <th>Valeur stockage</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                    //les boutiques en phase d'esploitation
                                    $sql2 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE enTest =:e and activer=:a"); 
                                    $sql2->execute(array('e' =>1,'a' =>1))  or die(print_r($sql2->errorInfo()));  
                                    
                                    while ($boutiqueH =$sql2->fetch()) {
                                        if ($jour>=24 ) {
                                            $sqa = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` where 
                                                ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
                                                     and idBoutique=:b and aPayementBoutique=:ap");
                                            $sqa->execute(array('b' =>$boutiqueH["idBoutique"],'ap'=>1))  or die(print_r($sqa->errorInfo())); 
                                            $rea=$sqa->fetch();                                 
                                        } elseif ($jour>=1 || $jour<24) {
                                            $sqa = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` where (idBoutique=:b and aPayementBoutique=:ap) and
                                                ( datePS LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%' ) 
                                                     ");   
                                            $sqa->execute(array('b' =>$boutiqueH["idBoutique"],'ap'=>1))  or die(print_r($sqa->errorInfo())); 
                                            $rea=$sqa->fetch(); 
                                                     //echo 'jour < 24'.$anneeM."-".$moiM;                                   
                                        }
                                        
                                        
                                        /*$num_rowsH= count($rea);
                                        
                                        var_dump($num_rowsH);
                                        var_dump($rea);*/
                                        //var_dump($boutiqueH["idBoutique"]);
                                        

                                        if ($rea== false) { 
                                            /*******************************DEBUT CALCUL **************************************/
                                            $volumeMoyenne=0;

                                            $tailleCatal=0;
                                            $nomtableDesignation=$boutiqueH["nomBoutique"]."-designation";

                                            
                                            $sql = $bdd->prepare("SELECT count(*)  as nbreRef FROM `". $nomtableDesignation."` where classe=:c"); 
                                            $sql->execute(array('c' =>0))  or die(print_r($sql->errorInfo())); 
                                            
                                            
                                            if($compteur = $sql->fetch())
                                                $tailleCatal=$compteur["nbreRef"];

                                            $nomtableStock="";
                                            if ($boutiqueH["type"]=="Entrepot") {
                                                $nomtableStock=$boutiqueH["nomBoutique"]."-entrepotstock";
                                                
                                            } else {
                                                $nomtableStock=$boutiqueH["nomBoutique"]."-stock";
                                            } 
                                            //var_dump($nomtableStock);   
                                            $tailleStocks=0;

                                            $sql = $bdd->prepare("SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=:q"); 
                                            $sql->execute(array('q' =>0))  or die(print_r($sql->errorInfo())); 


                                            if($compteur =$sql->fetch())
                                                $tailleStocks=$compteur["nbreStock"];

                                            $volumeMoyenne=($tailleCatal+$tailleStocks)/2;
                                            
                                            /*******************************FIN CALCUL ****************************************/
                                            ?> 
                                            <tr>
                                                <td> <b>  <?php echo  $boutiqueH['labelB'];?> </b> </td>
                                                <td> <?php echo  $boutiqueH["type"].' / '.$boutiqueH["categorie"]; ?>
                                                <td> <b><?php echo  $volumeMoyenne; ?>   </b>   </td> 
                                            </tr>

                                        <?php }
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

</div>


<script type="text/javascript" src="paiement/js/scriptPaiement.js"></script>