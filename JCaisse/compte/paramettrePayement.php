<?php
/*
Résum� :
Commentaire :
Version : 2.1
see also :
Auteur : EL hadji mamadou korka
Date de création : 15-04-2018
Date derniére modification :  15-04-2018
*/

session_start();

require('connection.php');

require('declarationVariables.php');

if(!$_SESSION['iduserBack'])
	header('Location:index.php');
elseif (isset($_POST['btnEnregistrerPersonnel'])) {

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
			 $sql1="insert into `aaa-variablespayement` (nomvariable,typecaisse,categoriecaisse,moyenneVolumeMin,moyenneVolumeMax,montant,pourcentage,prixLigne,minmontant,maxmontant,activerMontant,activerPourcentage,activerPrix) values('".mysql_real_escape_string($nomV)."','".$type."','".$categorie."',".$moyenneVolumeMin.",".$moyenneVolumeMax.",".$montantFixe.",".$pourcentage.",".$prixInsertion.",".$montantMin.",".$montantMax.",0,0,0)";
			 //echo $sql1;
  			 $res1=mysql_query($sql1) or die ("insertion etape impossible =>".mysql_error() );


			$message="Utilisateur ajouter avec succes";
		} else{
			$message="mot de pass different";
		}

}elseif (isset($_POST['btnModifierPersonnel'])) {

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
	$sql3="UPDATE `aaa-variablespayement` set  `nomvariable`='".mysql_real_escape_string($nomV)."',typecaisse='".$type."',categoriecaisse='".$categorie."',moyenneVolumeMin=".$moyenneVolumeMin.",moyenneVolumeMax=".$moyenneVolumeMax.",montant=".$montantFixe.",pourcentage=".$pourcentage.",prixLigne=".$prixInsertion.",minmontant=".$montantMin.",maxmontant=".$montantMax." where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());

	}

}elseif (isset($_POST['btnSupprimerPersonnel'])) {

	$idvariable=$_POST['idvariable'];

	$sql="DELETE FROM `aaa-variablespayement` WHERE idvariable=".$idvariable;
  	$res=@mysql_query($sql) or die ("suppression impossible etape     ".mysql_error());

}
if (isset($_POST['btnActiver'])) {
	$idvariable=$_POST['idvariable'];
	$activer=1;
	$sql3="UPDATE `aaa-variablespayement` set  activerMontant=".$activer.",activerPourcentage=0,activerPrix=0 where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());

} elseif (isset($_POST['btnDesactiver'])) {
	$idvariable=$_POST['idvariable'];
	$activer=0;
	$sql3="UPDATE `aaa-variablespayement` set  activerMontant='".$activer."' where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
if (isset($_POST['btnActiver2'])) {
	$idvariable=$_POST['idvariable'];
	$activer2=1;
	$sql3="UPDATE `aaa-variablespayement` set  activerMontant=0,activerPourcentage=".$activer2.",activerPrix=0 where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());

} elseif (isset($_POST['btnDesactiver2'])) {
	$idvariable=$_POST['idvariable'];
	$activer2=0;
	$sql3="UPDATE `aaa-variablespayement` set  activerPourcentage='".$activer2."' where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
if (isset($_POST['btnActiver3'])) {
	$idvariable=$_POST['idvariable'];
	$activer3=1;
	$sql3="UPDATE `aaa-variablespayement` set  activerMontant=0,activerPourcentage=0,activerPrix=".$activer3." where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());

} elseif (isset($_POST['btnDesactiver3'])) {
	$idvariable=$_POST['idvariable'];
	$activer3=0;
	$sql3="UPDATE `aaa-variablespayement` set  activerPrix='".$activer3."' where idvariable=".$idvariable;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
require('entetehtml.php');
?>

<body>

	<?php   require('header.php'); ?>
<div class="container-fluid">
	<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addParamettre">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un paramettre de payement
   		</button>
	</div>
						  <div class="modal fade" id="addParamettre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					            <div class="modal-dialog" role="document">
					                <div class="modal-content">
					                    <div class="modal-header">
					                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					                        <h4 class="modal-title" id="myModalLabel">Ajout d'un paramettre de payement</h4>
					                    </div>
					                    <div class="modal-body">
							                    <form name="formulaireParamettre" method="post" action="paramettrePayement.php">
																				<div class="form-group">
																			      <label for="inputnomV" class="control-label">NOM VARIABLE DE PAYEMENT <font color="red">*</font></label>
																			      <input type="text" class="form-control" id="nomV" name="nomV" placeholder="Le nom de la variable ici..." required="">
																			      <span class="text-danger" ></span>
																			  </div>

																			  <div class="form-group">
																					<label for="type">TYPE DE CAISSE<font color="red">*</font></label>
																					<select name="type" id="type" class="form-control">
																					<?php
																						$sql10="SELECT * FROM `aaa-typeboutique`";
																						echo $sql10;
																						$res10=mysql_query($sql10);

																						while($ligne = mysql_fetch_row($res10)) {
																						echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
																							} ?>
																					</select>
														              <div class="help-block" id="helpType"></div>
																			  </div>

																				<div class="form-group">
																				<label for="type">CATEGORIE DE CAISSE<font color="red">*</font></label>
																				<select name="categorie" id="categorie" class="form-control"> <?php
																						$sql11="SELECT * FROM `aaa-categorie`";
																						echo $sql11;
																						$res11=mysql_query($sql11);
																						while($ligne2 = mysql_fetch_row($res11)) {
																						echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
																							} ?>
																					</select>
														              <div class="help-block" id="helpCategorie"></div>
																			</div>

																			  <div class="form-group">
																			      <label for="inputMoyenne" class="control-label">VOLUME DONNEES MIN<font color="red">*</font></label>
																			      <input type="number" class="form-control" id="moyenneVolumeMin" name="moyenneVolumeMin" placeholder="Moyenne du volume des donnees min" required="">
																			      <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			      <label for="inputMoyenne" class="control-label">VOLUME DONNEES MAX <font color="red">*</font></label>
																			      <input type="number" class="form-control" id="moyenneVolumeMax" name="moyenneVolumeMax" placeholder="Moyenne du volume des donnees max" required="">
																			      <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			      <label for="inputMontant" class="control-label">MONTANT PAYEMENT FIXE <font color="red">*</font></label>
																			      <input type="number" class="form-control" id="montantFixe" name="montantFixe" placeholder="montant payement fixe en FCFA" required="">
																			      <span class="text-danger" ></span>
																			  </div>

																			 <div class="form-group">
																			    <label for="inputPourcentage" class="control-label">POURCENTAGE SUR VENTES<font color="red">*</font></label>
																			    <input type="number" class="form-control" id="pourcentage" name="pourcentage" placeholder="Le pourcentage en % " required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			<div class="form-group">
																			    <label for="inputPrixInsertion" class="control-label">PRIX INSERTION D'UNE LIGNE <font color="red">*</font></label>
																			    <input type="number" class="form-control" id="prixInsertion" name="prixInsertion" placeholder="Le prix d'une ligne en FCFA" required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			    <label for="inputMontantMin" class="control-label">MONTANT PAYEMENT MIN <font color="red">*</font></label>
																			    <input type="number" class="form-control" id="montantMin" name="montantMin" placeholder="Le montant payement min en FCFA" required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			    <label for="inputMontantMax" class="control-label">MONTANT PAYEMENT MAX <font color="red">*</font></label>
																			    <input type="number" class="form-control" id="montantMax" name="montantMax" placeholder="Le montant payement max en FCFA" required="">
																			    <span class="text-danger" ></span>
																			  </div>

																			  <div class="modal-footer">
																			  <font color="red">Les champs qui ont (*) sont obligatoires</font>
														                                </br><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
														                                <button type="submit" name="btnEnregistrerPersonnel" class="btn btn-primary">Enregistrer</button>
														            </div>
												  				 </form>
					                   </div>

					                </div>
					            </div>
					    </div>

				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->


                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PARAMETTRES DE PAYEMENTS DES CAISSES</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                                <table id="exemple" class="display" border="1" class="table table-bordered table-striped" >
                                    <thead>
                                        <tr>
                                            <th>Nom Variable</th>
                                            <th>Type Caisse</th>
                                            <th>Categorie Caisse</th>
                                            <th>Volume Donnees Min</th>
                                            <th>Volume Donnees Max</th>
                                            <th colspan="2">Montant Fixe (FCFA)</th>
                                            <th colspan="2">Pourcentage sur Ventes (%)</th>
                                            <th colspan="2">Prix insertion ligne (FCFA)</th>
                                            <th>Montant Min (FCFA)</th>
                                            <th>Montant Max (FCFA)</th>
                                            <th>Opérations</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nom Variable</th>
                                            <th>Type Caisse</th>
                                            <th>Categorie Caisse</th>
                                            <th>Volume Donnees Min</th>
                                            <th>Volume Donnees Max</th>
                                            <th colspan="2">Montant Fixe (FCFA)</th>
                                            <th colspan="2">Pourcentage sur Ventes (%)</th>
                                            <th colspan="2">Prix insertion ligne (FCFA)</th>
                                            <th>Montant Min (FCFA)</th>
                                            <th>Montant Max (FCFA)</th>
                                            <th>Opérations</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $sql4="SELECT * FROM `aaa-variablespayement`" ;
                                        $res4 = mysql_query($sql4) or die ("variable requête 4".mysql_error());
                                        while($variable=mysql_fetch_array($res4)) {
                                    ?>
                                                <tr>
                                                    <td> <?php echo  $variable['nomvariable']; ?>  </td>
                                                    <td> <?php echo  $variable['typecaisse']; ?> </td>
                                                    <td> <?php echo  $variable['categoriecaisse']; ?>  </td>
                                                    <td> <?php echo  $variable['moyenneVolumeMin']; ?>  </td>
                                                    <td> <?php echo  $variable['moyenneVolumeMax']; ?>  </td>
                                                    <td> <?php echo  $variable['montant']; ?></td>
                                                    <td>

                                                        <?php if ($variable['activerMontant']==0) { ?>
                                                                <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target='#Activer".$variable['idvariable']."'" ; ?> >
                                                                Activer</button>
                                                                <?php
                                                            } else { ?>
                                                            <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target='#Desactiver".$variable['idvariable']."'" ; ?> >
                                                            Desactiver</button>
                                                        <?php }?>
                                                    </td>
                                                    <td> <?php echo  $variable['pourcentage']; ?>% </td>
                                                    <td>

                                                    <?php

                                                    if ($variable['activerPourcentage']==0) { ?>

                                                        <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target='#Activer2".$variable['idvariable']."'" ; ?> >
                                                            Activer</button>
                                                            <?php
                                                        } else { ?>
                                                            <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target='#Desactiver2".$variable['idvariable']."'" ; ?> >
                                                            Desactiver</button>
                                                        <?php }


                                                         ?>
                                                    </td>
                                                    <td> <?php echo  $variable['prixLigne'];   ?> </td>
                                                    <td>

                                                    <?php

                                                    if ($variable['activerPrix']==0) { ?>

                                                            <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target='#Activer3".$variable['idvariable']."'" ; ?> >
                                                            Activer</button>
                                                            <?php
                                                        } else { ?>
                                                            <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target='#Desactiver3".$variable['idvariable']."'" ; ?> >
                                                            Desactiver</button>
                                                        <?php }


                                                         ?>
                                                    </td>
                                                    <td> <?php echo  $variable['minmontant']; ?>  </td>
                                                    <td> <?php echo  $variable['maxmontant']; ?>  </td>
                                                    <td>
                                                    <?php echo'<a href="#"><img src="images/edit.png" data-target="#imgmodifierPer'.$variable['idvariable'].'" align="middle" alt="modifier"  data-toggle="modal" /></a>';

                                                        ?>

                                                            <a   href="#" >
                                                                <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target='#imgsuprimerPer".$variable['idvariable']."'" ; ?> /></a>


                                                    </td>




                                <div class="modal fade" <?php echo  "id='Activer".$variable['idvariable']."'" ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" action="paramettrePayement.php">
                                                  <div class="form-group">
                                                     <h2>Voulez vous vraiment activer le montant fixe</h2>
                                                     <input type="hidden" name="idvariable" <?php echo  "value=". htmlspecialchars($variable['idvariable'])."" ; ?> >
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                   </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" <?php echo  "id='Desactiver".$variable['idvariable']."'" ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" action="paramettrePayement.php">
                                                  <div class="form-group">
                                                     <h2>Voulez vous vraiment desactiver le montant fixe</h2>
                                                     <input type="hidden" name="idvariable" <?php echo  "value=". htmlspecialchars($variable['idvariable'])."" ; ?> >
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                   </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                    <!--                						 ACTIVATION 2 ------------------------------->
                                <div class="modal fade" <?php echo  "id='Activer2".$variable['idvariable']."'" ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" action="paramettrePayement.php">
                                                  <div class="form-group">
                                                     <h2>Voulez vous vraiment activer le pourcentage sur ventes</h2>
                                                     <input type="hidden" name="idvariable" <?php echo  "value=". htmlspecialchars($variable['idvariable'])."" ; ?> >
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" name="btnActiver2" class="btn btn-primary">Activer</button>
                                                   </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" <?php echo  "id='Desactiver2".$variable['idvariable']."'" ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" action="paramettrePayement.php">
                                                  <div class="form-group">
                                                     <h2>Voulez vous vraiment desactiver le pourcentage sur ventes</h2>
                                                     <input type="hidden" name="idvariable" <?php echo  "value=". htmlspecialchars($variable['idvariable'])."" ; ?> >
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" name="btnDesactiver2" class="btn btn-primary">Desactiver</button>
                                                   </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                    <!---                          ACTIVATION 3 -------------------------------->
                                <div class="modal fade" <?php echo  "id='Activer3".$variable['idvariable']."'" ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" action="paramettrePayement.php">
                                                  <div class="form-group">
                                                     <h2>Voulez vous vraiment activer le prix insertion ligne</h2>
                                                     <input type="hidden" name="idvariable" <?php echo  "value=". htmlspecialchars($variable['idvariable'])."" ; ?> >
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" name="btnActiver3" class="btn btn-primary">Activer</button>
                                                   </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" <?php echo  "id='Desactiver3".$variable['idvariable']."'" ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" action="paramettrePayement.php">
                                                  <div class="form-group">
                                                     <h2>Voulez vous vraiment desactiver le prix insertion ligne</h2>
                                                     <input type="hidden" name="idvariable" <?php echo  "value=". htmlspecialchars($variable['idvariable'])."" ; ?> >
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" name="btnDesactiver3" class="btn btn-primary">Desactiver</button>
                                                   </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>		<!----------------------------------------------------------->
                                <div <?php echo  "id='imgmodifierPer".$variable['idvariable']."'" ; ?>   class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Formulaire pour modifier un paramettre de Payement</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaire2" method="post" action="paramettrePayement.php">

                                                    <input type="hidden" name="idvariable" <?php echo 'value="'.$variable['idvariable'].'"' ; ?> />


                                              <div class="form-group">
                                                  <label for="inputnomV" class="control-label">NOM VARIABLE DE PAYEMENT <font color="red">*</font></label>
                                                  <input type="text" class="form-control" id="nomV" name="nomV" <?php echo  "value=".$variable['nomvariable']."" ; ?> >
                                                <input type="hidden" name="nomVInitial" <?php echo  "value=".$variable['nomvariable']."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>



                                              <div class="form-group">
                                                <label for="type">TYPE DE CAISSE<font color="red">*</font></label>
                                                    <select name="type" id="type" class="form-control">
                                                        <?php
                                                        $sql10="SELECT * FROM `aaa-typeboutique`";
                                                        //echo $sql10;
                                                        $res10=mysql_query($sql10);
                                                        echo'<option selected="" value= "'.$variable["typecaisse"].'">'.$variable["typecaisse"].'</option>';
                                                        while($ligne = mysql_fetch_row($res10)) {
                                                            if ($ligne[1]!=$variable["typecaisse"])
                                                                echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            } ?>
                                                    </select><input type="hidden" name="typeInitial" <?php echo  "value=".$variable["typecaisse"]."" ; ?> />
                                                <div class="help-block" id="helpType"></div>
                                               </div>

                                            <div class="form-group">
                                                <label for="type">CATEGORIE DE CAISSE<font color="red">*</font></label>
                                                <select name="categorie" id="categorie" class="form-control"> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        //echo $sql11;
                                                        $res11=mysql_query($sql11);
                                                        echo'<option selected="" value= "'.$variable["categoriecaisse"].'">'.$variable["categoriecaisse"].'</option>';
                                                        while($ligne = mysql_fetch_row($res11)) {
                                                            if ($ligne[1]!=$variable["categoriecaisse"])
                                                                echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            } ?>

                                                    </select><input type="hidden" name="categorieInitial" <?php echo  "value=".$variable["categoriecaisse"]."" ; ?> />
                                                <div class="help-block" id="helpCategorie"></div>
                                            </div>

                                            <div class="form-group">
                                                  <label for="inputMoyenne" class="control-label">MOYENNE DU VOLUME DONNEES MIN<font color="red">*</font></label>
                                                  <input type="number" class="form-control" id="moyenneVolumeMin" name="moyenneVolumeMin" <?php echo  "value=".$variable["moyenneVolumeMin"]."" ; ?> />
                                                  <input type="hidden" name="moyenneVolumeMinInitial" <?php echo  "value=".$variable['moyenneVolumeMin']."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>

                                            <div class="form-group">
                                                  <label for="inputMoyenne" class="control-label">MOYENNE DU VOLUME DONNEES MAX<font color="red">*</font></label>
                                                  <input type="number" class="form-control" id="moyenneVolumeMax" name="moyenneVolumeMax" <?php echo  "value=".$variable["moyenneVolumeMax"]."" ; ?> />
                                                  <input type="hidden" name="moyenneVolumeMaxInitial" <?php echo  "value=".$variable['moyenneVolumeMax']."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>


                                              <div class="form-group">
                                                  <label for="inputMontant" class="control-label">MONTANT PAYEMENT FIXE <font color="red">*</font></label>
                                                  <input type="number" class="form-control" id="montantFixe" name="montantFixe" <?php echo  "value=".$variable['montant']."" ; ?> />
                                                <input type="hidden" name="montantFixeInitial" <?php echo  "value=".$variable['montant']."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>

                                             <div class="form-group">
                                                <label for="inputPourcentage" class="control-label">POURCENTAGE SUR VENTES<font color="red">*</font></label>
                                                <input type="number" class="form-control" id="pourcentage" name="pourcentage" <?php echo  "value=".$variable['pourcentage']."" ; ?> />
                                                <input type="hidden" name="pourcentageInitial" <?php echo  "value=".$variable['pourcentage']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>

                                            <div class="form-group">
                                                <label for="inputPrixInsertion" class="control-label">PRIX INSERTION D'UNE LIGNE <font color="red">*</font></label>
                                                <input type="number" class="form-control" id="prixInsertion" name="prixInsertion" <?php echo  "value=".$variable['prixLigne']."" ; ?> />
                                                <input type="hidden" name="prixInsertionInitial" <?php echo  "value=".$variable['prixLigne']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>


                                              <div class="form-group">
                                                <label for="inputMontantMin" class="control-label">MONTANT PAYEMENT MIN <font color="red">*</font></label>
                                                <input type="number" class="form-control" id="montantMin" name="montantMin" <?php echo  "value=".$variable['minmontant']."" ; ?> />
                                                <input type="hidden" name="montantMinInitial" <?php echo  "value=".$variable['minmontant']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                <label for="inputMontantMax" class="control-label">MONTANT PAYEMENT MAX <font color="red">*</font></label>
                                                <input type="number" class="form-control" id="montantMax" name="montantMax" <?php echo  "value=".$variable['maxmontant']."" ; ?> />
                                                <input type="hidden" name="montantMaxInitial" <?php echo  "value=".$variable['maxmontant']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>

                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>

                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!----------------------------------------------------------->
                                <div <?php echo  "id='imgsuprimerPer".$variable['idvariable']."'" ; ?>  class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Supprimer un paramettre de payement</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="paramettrePayement.php">
                                                    <input type="hidden" name="idvariable" <?php echo  "value=".$variable['idvariable']."" ; ?> />

                                              <div class="form-group">
                                                  <label for="inputnomV" class="control-label">NOM VARIABLE DE PAYEMENT <font color="red">*</font></label>
                                                  <input type="text" class="form-control" id="nomV" name="nomV" <?php echo  "value=".$variable['nomvariable']."" ; ?> disabled="">
                                                <input type="hidden" name="nomVInitial" <?php echo  "value=".$variable['nomvariable']."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                  <label for="type" class="control-label">TYPE DE CAISSE<font color="red">*</font></label>
                                                  <input type="text" class="form-control" id="type" name="type" <?php echo  "value=".$variable["typecaisse"]."" ; ?> disabled="">
                                                <input type="hidden" name="typeInitial" <?php echo  "value=".$variable["typecaisse"]."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                  <label for="type" class="control-label">TYPE DE CAISSE<font color="red">*</font></label>
                                                  <input type="text" class="form-control" id="categorie" name="categorie" <?php echo  "value=".$variable["categoriecaisse"]."" ; ?> disabled="">
                                                <input type="hidden" name="categorieInitial" <?php echo  "value=".$variable["categoriecaisse"]."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                  <label for="inputMoyenne" class="control-label">MOYENNE DU VOLUME DONNEES MIN<font color="red">*</font></label>
                                                  <input type="number" class="form-control" id="moyenneVolumeMinInitial" name="moyenneVolumeMinInitial" <?php echo  "value=".$variable["moyenneVolumeMin"]."" ; ?> disabled="" />
                                                  <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                  <label for="inputMoyenne" class="control-label">MOYENNE DU VOLUME DONNEES MAX<font color="red">*</font></label>
                                                  <input type="number" class="form-control" id="moyenneVolumeMaxInitial" name="moyenneVolumeMaxInitial" <?php echo  "value=".$variable["moyenneVolumeMax"]."" ; ?> disabled="" />
                                                  <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                  <label for="inputMontant" class="control-label">MONTANT PAYEMENT FIXE <font color="red">*</font></label>
                                                  <input type="number" class="form-control" id="montantFixe" name="montantFixe" <?php echo  "value=".$variable['montant']."" ; ?> disabled="" />
                                                <input type="hidden" name="montantFixeInitial" <?php echo  "value=".$variable['montant']."" ; ?> />
                                                  <span class="text-danger" ></span>
                                              </div>

                                             <div class="form-group">
                                                <label for="inputPourcentage" class="control-label">POURCENTAGE SUR VENTES<font color="red">*</font></label>
                                                <input type="number" class="form-control" id="pourcentage" name="pourcentage" <?php echo  "value=".$variable['pourcentage']."" ; ?> disabled="" />
                                                <input type="hidden" name="pourcentageInitial" <?php echo  "value=".$variable['pourcentage']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>

                                            <div class="form-group">
                                                <label for="inputPrixInsertion" class="control-label">PRIX INSERTION D'UNE LIGNE <font color="red">*</font></label>
                                                <input type="number" class="form-control" id="prixInsertion" name="prixInsertion" <?php echo  "value=".$variable['prixLigne']."" ; ?> disabled="" />
                                                <input type="hidden" name="prixInsertionInitial" <?php echo  "value=".$variable['prixLigne']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>


                                              <div class="form-group">
                                                <label for="inputMontantMin" class="control-label">MONTANT PAYEMENT MIN <font color="red">*</font></label>
                                                <input type="number" class="form-control" id="montantMin" name="montantMin" <?php echo  "value=".$variable['minmontant']."" ; ?> disabled="" />
                                                <input type="hidden" name="montantMinInitial" <?php echo  "value=".$variable['minmontant']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>

                                              <div class="form-group">
                                                <label for="inputMontantMax" class="control-label">MONTANT PAYEMENT MAX <font color="red">*</font></label>
                                                <input type="number" class="form-control" id="montantMax" name="montantMax" <?php echo  "value=".$variable['maxmontant']."" ; ?> disabled="" />
                                                <input type="hidden" name="montantMaxInitial" <?php echo  "value=".$variable['maxmontant']."" ; ?> />
                                                <span class="text-danger" ></span>
                                              </div>

                                                    <div class="modal-footer row">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" name="btnSupprimerPersonnel" class="btn btn-primary">Supprimer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!----------------------------------------------------------->
                                                </tr>
                                            <?php
                                             }

                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>

			</div>
</body>
</html>
