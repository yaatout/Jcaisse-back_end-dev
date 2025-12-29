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
                    // $somme=0;
                    // $mois2=$mois-1;
                    // $sql1="SELECT * FROM `aaa-payement-salaire`  WHERE ( datePS  NOT LIKE '%".$annee."-".$mois."%' ) and aPayementBoutique=0";
                    // //$sql1="SELECT idBoutique FROM `aaa-payement-salaire` WHERE `datePS` LIKE '%".$annee."-0".$mois2."%' and aPayementBoutique=0" ;
                    // $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                    // while($boutiqueP=mysql_fetch_array($res1)) {
                    //     $sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                    //     $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                    //     while($payement=mysql_fetch_array($res4)) {
                    //         $somme=$somme+$payement['montantFixePayement'];
                    //     }
                    // }


                    $sql30 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE ( datePS  NOT LIKE '%".$annee."-".$mois."%' ) and aPayementBoutique=:a"); 
                                $sql30->execute(array('a' =>0 ))  or die(print_r($sql30->errorInfo()));                                                         
                                $total =$sql30->fetch();

                ?>

                <div class="jumbotron noImpr">

            <!--        <h2>Mois : <?php echo "0".($mois-1)."-".$annee; ?></h2>-->
                    <h2>Mois : <?php echo "".($mois-1)."-".$annee; ?></h2>

                    <!-- <p>Total Paiements Effectifs: <font color="red"><?php echo $somme; ?> FCFA</font></p> -->
                    <p>Total Paiements Effectifs 22222: <font color="red"><?php echo $total['total']; ?> FCFA</font></p>

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
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#PAIEMENTRETARD">MOIS PRECEDANT</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="PAIEMENTRETARD" >
				<div class="table-responsive"> 
						<label class="pull-left" for="nbEntreRetard">Nombre entrées </label>
						<select class="pull-left" id="nbEntreRetard">
						<optgroup>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option>  
						</optgroup>       
					</select>
					<input class="pull-right" type="text" name="" id="searchInputRetard" placeholder="Rechercher...">
					<div id="resultsRetard"><!-- content will be loaded here --></div>
				</div>
		    </div>
		</div>
    
<script type="text/javascript" src="paiement/js/scriptPaiement.js"></script>
<script type="text/javascript" src="paiement/js/scriptPaiementRetard.js"></script>