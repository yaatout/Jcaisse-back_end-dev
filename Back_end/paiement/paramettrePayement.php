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
							                    <form name="formulaireParamettre" id="addParamettreForm" method="post" >
																				<div class="form-group">
																			      <label  class="control-label">NOM VARIABLE DE PAYEMENT <font color="red">*</font></label>
																			      <input type="text" class="form-control" id="nomV" name="nomV" placeholder="Le nom de la variable ici..." required="">
																			      <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																					<label >TYPE DE CAISSE<font color="red">*</font></label>
																					<select name="type" id="type" class="form-control">
                                                                                        <?php
                                                                                        $req10 = $bdd->prepare("SELECT * FROM `aaa-typeboutique` "); 
                                                                                        $req10->execute()  or die(print_r($req10->errorInfo())); 
                                                                                                                                
                                                                                        while($ligne = $req10->fetch()) {
                                                                                            echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                                                        } ?>
																					</select>
														              <div class="help-block" id="helpType"></div>
																			  </div>
																				<div class="form-group">
																				<label  >CATEGORIE DE CAISSE<font color="red">*</font></label>
																				<select name="categorie" id="categorie" class="form-control"> 
                                                                                    <?php
                                                                                        $req11 = $bdd->prepare("SELECT * FROM `aaa-categorie` "); 
                                                                                        $req11->execute()  or die(print_r($req11->errorInfo())); 
                                                                                        
																						while($ligne2 = $req11->fetch()) {
																						    echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
																						} ?>
																					</select>
														              <div class="help-block" id="helpCategorie"></div>
																			</div>
																			  <div class="form-group">
																			      <label  class="control-label">VOLUME DONNEES MIN<font color="red">*</font></label>
																			      <input type="number" class="form-control" id="moyenneVolumeMin" name="moyenneVolumeMin" placeholder="Moyenne du volume des donnees min" required="">
																			      <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			      <label   class="control-label">VOLUME DONNEES MAX <font color="red">*</font></label>
																			      <input type="number" class="form-control" id="moyenneVolumeMax" name="moyenneVolumeMax" placeholder="Moyenne du volume des donnees max" required="">
																			      <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			      <label   class="control-label">MONTANT PAYEMENT FIXE <font color="red">*</font></label>
																			      <input type="number" class="form-control" id="montantFixe" name="montantFixe" placeholder="montant payement fixe en FCFA" required="">
																			      <span class="text-danger" ></span>
																			  </div>
																			 <div class="form-group">
																			    <label   class="control-label">POURCENTAGE SUR VENTES<font color="red">*</font></label>
																			    <input type="number" class="form-control" id="pourcentage" name="pourcentage" placeholder="Le pourcentage en % " required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			<div class="form-group">
																			    <label   class="control-label">PRIX INSERTION D'UNE LIGNE <font color="red">*</font></label>
																			    <input type="number" class="form-control" id="prixInsertion" name="prixInsertion" placeholder="Le prix d'une ligne en FCFA" required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			    <label  class="control-label">MONTANT PAYEMENT MIN <font color="red">*</font></label>
																			    <input type="number" class="form-control" id="montantMin" name="montantMin" placeholder="Le montant payement min en FCFA" required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			  <div class="form-group">
																			    <label   class="control-label">MONTANT PAYEMENT MAX <font color="red">*</font></label>
																			    <input type="number" class="form-control" id="montantMax" name="montantMax" placeholder="Le montant payement max en FCFA" required="">
																			    <span class="text-danger" ></span>
																			  </div>
																			    <div class="modal-footer">
																			  		<font color="red">Les champs qui ont (*) sont obligatoires</font>
														                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
														                            <button type="button" name="btnEnregistrerVarParam" id="btnEnregistrerVarParam" class="btn btn-primary">Enregistrer</button>
														            			</div>
												  				 </form>
					                   </div>

					                </div>
					            </div>
	</div>

												<!----------------------------------------------------------->
												<div class="modal fade" id='imgmodifierVar' tabindex="-1" role="dialog" >
                                                      <div class="modal-dialog">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                  <h4 class="modal-title">Formulaire pour modifier un paramettre de Payement</h4>
                                                              </div>
                                                              <div class="modal-body">
                                                                  <form name="formulaire2" method="post" id="modParamettreForm">

                                                                      <input type="hidden" id="idvariableMod"  />
																		<div class="form-group">
																			<label   class="control-label">NOM VARIABLE DE PAYEMENT <font color="red">*</font></label>
																			<input type="text" class="form-control"   name="nomV" id="nomVMod"  >
																		<input type="hidden" name="nomVInitial" id="nomVInitialMod"  />
																			<span class="text-danger" ></span>
																		</div>



																		<div class="form-group">
																		<label for="type">TYPE DE CAISSE<font color="red">*</font></label>
																			<select name="type"  id="typeMod" class="form-control">
																			<?php
                                                                                        $req10 = $bdd->prepare("SELECT * FROM `aaa-typeboutique` "); 
                                                                                        $req10->execute()  or die(print_r($req10->errorInfo())); 
                                                                                        
                                                                                        
                                                                                        while($ligne = $req10->fetch()) {
                                                                                            echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                                                        } ?>
																			</select><input type="hidden" name="typeInitial" id="typeInitialMod"  />
																		<div class="help-block"  ></div>
																		</div>

																	<div class="form-group">
																		<label for="type">CATEGORIE DE CAISSE<font color="red">*</font></label>
																		<select name="categorie" id="categorieMod" class="form-control"> 
                                                                        <?php
                                                                                        $req11 = $bdd->prepare("SELECT * FROM `aaa-categorie` "); 
                                                                                        $req11->execute()  or die(print_r($req11->errorInfo())); 
                                                                                        
																						while($ligne2 = $req11->fetch()) {
																						    echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
																						} ?>
																			</select><input type="hidden" name="categorieInitial" id="categorieInitialMod" />
																		<div class="help-block"  ></div>
																	</div>

																	<div class="form-group">
																			<label   class="control-label">MOYENNE DU VOLUME DONNEES MIN<font color="red">*</font></label>
																			<input type="number" class="form-control"   name="moyenneVolumeMin" id="moyenneVolumeMinMod"  />
																			<input type="hidden" name="moyenneVolumeMinInitial"  id="moyenneVolumeMinInitialMod" />
																			<span class="text-danger" ></span>
																		</div>

																	<div class="form-group">
																			<label   class="control-label">MOYENNE DU VOLUME DONNEES MAX<font color="red">*</font></label>
																			<input type="number" class="form-control"   name="moyenneVolumeMax" id="moyenneVolumeMaxMod"  />
																			<input type="hidden" name="moyenneVolumeMaxInitial" id="moyenneVolumeMaxInitialMod" />
																			<span class="text-danger" ></span>
																		</div>


																		<div class="form-group">
																			<label   class="control-label">MONTANT PAYEMENT FIXE <font color="red">*</font></label>
																			<input type="number" class="form-control"   name="montantFixe" id="montantFixeMod" />
																		<input type="hidden" name="montantFixeInitial" id="montantFixeInitialMod" />
																			<span class="text-danger" ></span>
																		</div>

																	<div class="form-group">
																		<label   class="control-label">POURCENTAGE SUR VENTES<font color="red">*</font></label>
																		<input type="number" class="form-control"   name="pourcentage" id="pourcentageMod"  />
																		<input type="hidden" name="pourcentageInitial" id="pourcentageInitialMod" />
																		<span class="text-danger" ></span>
																		</div>

																	<div class="form-group">
																		<label   class="control-label">PRIX INSERTION D'UNE LIGNE <font color="red">*</font></label>
																		<input type="number" class="form-control"   name="prixInsertion"  id="prixInsertionMod"  />
																		<input type="hidden" name="prixInsertionInitial" id="prixInsertionInitialMod" />
																		<span class="text-danger" ></span>
																		</div>


																		<div class="form-group">
																		<label  class="control-label">MONTANT PAYEMENT MIN <font color="red">*</font></label>
																		<input type="number" class="form-control"   name="montantMin"  id="montantMinMod"  />
																		<input type="hidden" name="montantMinInitial"  id="montantMinInitialMod"  />
																		<span class="text-danger" ></span>
																		</div>

																		<div class="form-group">
																		<label   class="control-label">MONTANT PAYEMENT MAX <font color="red">*</font></label>
																		<input type="number" class="form-control"   name="montantMax"  id="montantMaxMod"  />
																		<input type="hidden" name="montantMaxInitial"  name="montantMaxInitialMod" id="montantMaxInitialMod" />
																		<span class="text-danger" ></span>
																		</div>

                                                                      <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>

                                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                          <button type="button" id="btnModifierVarParam" class="btn btn-primary">Enregistrer</button>
                                                                      </div>
                                                                  </form>
                                                              </div>
                                                          </div>
                                                      </div>
                                                </div>
												<!----------------------------------------------------------->
												<div id='imgsuprimerPer' class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                  <h4 class="modal-title">Supprimer un paramettre de payement</h4>
                                                              </div>
                                                              <div class="modal-body">
                                                                  <form role="form" class="formulaire2"   method="post" action="paramettrePayement.php">
                                                                      <input type="hidden" name="idvariable" id="idvariableSup"/>

                                                                <div class="form-group">
                                                                    <label   class="control-label">NOM VARIABLE DE PAYEMENT <font color="red">*</font></label>
                                                                    <input type="text" class="form-control"   name="nomV" id="nomVInitialSup"  disabled="">
                                                                    <span class="text-danger" ></span>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="type" class="control-label">TYPE DE CAISSE<font color="red">*</font></label>
                                                                    <input type="text" class="form-control"   name="type" id="typeInitialSup"  disabled="">
                                                                    <span class="text-danger" ></span>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="type" class="control-label">TYPE DE CAISSE<font color="red">*</font></label>
                                                                    <input type="text" class="form-control"   name="categorie" id="categorieInitialSup" disabled="">
                                                                  
                                                                    <span class="text-danger" ></span>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label   class="control-label">MOYENNE DU VOLUME DONNEES MIN<font color="red">*</font></label>
                                                                    <input type="number" class="form-control" id="moyenneVolumeMinInitialSup" disabled="" />
                                                                    <span class="text-danger" ></span>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label   class="control-label">MOYENNE DU VOLUME DONNEES MAX<font color="red">*</font></label>
                                                                    <input type="number" class="form-control" id="moyenneVolumeMaxInitialSup" disabled="" />
                                                                    <span class="text-danger" ></span>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label   class="control-label">MONTANT PAYEMENT FIXE <font color="red">*</font></label>
                                                                    <input type="number" class="form-control"   name="montantFixe" id="montantFixeInitialSup" disabled="" /> 
                                                                    <span class="text-danger" ></span>
                                                                </div>

                                                              <div class="form-group">
                                                                  <label   class="control-label">POURCENTAGE SUR VENTES<font color="red">*</font></label>
                                                                  <input type="number" class="form-control"   name="pourcentage" id="pourcentageInitialSup" disabled="" /> 
                                                                  <span class="text-danger" ></span>
                                                                </div>

                                                              <div class="form-group">
                                                                  <label   class="control-label">PRIX INSERTION D'UNE LIGNE <font color="red">*</font></label>
                                                                  <input type="number" class="form-control"  name="prixInsertion"  id="prixInsertionInitialSup" disabled="" /> 
                                                                  <span class="text-danger" ></span>
                                                                </div>


                                                                <div class="form-group">
                                                                  <label   class="control-label">MONTANT PAYEMENT MIN <font color="red">*</font></label>
                                                                  <input type="number" class="form-control"   name="montantMin"  id="montantMinInitialSup" disabled="" /> 
                                                                  <span class="text-danger" ></span>
                                                                </div>

                                                                <div class="form-group">
                                                                  <label  class="control-label">MONTANT PAYEMENT MAX <font color="red">*</font></label>
                                                                  <input type="number" class="form-control"   name="montantMax"  id="montantMaxInitialSup" disabled="" /> 
                                                                  <span class="text-danger" ></span>
                                                                </div>

                                                                      <div class="modal-footer row">
                                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                          <button type="submit" id="btnSupprimerVariable"  class="btn btn-primary">Supprimer</button>
                                                                      </div>
                                                                  </form>
                                                              </div>
                                                          </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
												<!---------------------------ACTIVATION 1------------------------------->
												<div class="modal fade" id='Activer' tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"  >Activation</h4>
                                                              </div>
                                                              <div class="modal-body">
                                                                  <form name="formulaireVersement" id="formActMontFix" method="post" >
                                                                    <div class="form-group">
                                                                      <h2>Voulez vous vraiment activer le montant fixe</h2>
                                                                      <input type="hidden" name="i" id="i" >
																	  <input type="hidden" name="btnActiver" id="btnActiver">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                          <button type="button" name="btnActiver" id="btnActiverMontF" value="1" class="btn btn-primary">Activer</button>
                                                                    </div>
                                                                  </form>
                                                              </div>
                                                          </div>
                                                  </div>
                                                </div>
												<div class="modal fade" id='Desactiver' tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                      <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"  >Desactivation</h4>
                                                              </div>
                                                              <div class="modal-body">
                                                                  <form  method="post"  id="formDesactMontFix">
                                                                    <div class="form-group">
                                                                      <h2>Voulez vous vraiment desactiver le montant fixe</h2>
                                                                      <input type="hidden" name="i" id="ib" >
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                          <button type="button" value='1' name="btnDesactiver"  id="btnDesactiverMontF" class="btn btn-primary">Desactiver</button>
                                                                    </div>
                                                                  </form>
                                                              </div>

                                                          </div>
                                                      </div>
                                                </div>
												<!---------------------------ACTIVATION 2 ------------------------------->
												<div class="modal fade" id='Activer2' tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                      <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"  >Activation</h4>
                                                              </div>
                                                              <div class="modal-body">
                                                                  <form   method="post"  id="formActMontPourc" method="post">
                                                                    <div class="form-group">
                                                                      <h2>Voulez vous vraiment activer le pourcentage sur ventes</h2>
																	  <input type="hidden" name="i2a" id="i2a" >
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>  
																		<button type="button" name="btnActiver" id="btnActiver2" value="1" class="btn btn-primary">Activer</button>
                                                                    </div>
                                                                  </form>
                                                              </div>

                                                          </div>
                                                      </div>
                                                </div>
                                                <div class="modal fade" id='Desactiver2' tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                      <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title" >Desactivation</h4>
                                                              </div>
                                                              <div class="modal-body">
															  	<form  method="post"  id="formDesactMontPourc">
                                                                    <div class="form-group">
                                                                      <h2>Voulez vous vraiment desactiver le montant fixe</h2>
                                                                      <input type="hidden" name="i2b" id="i2b" >
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                          <button type="button" value='1' name="btnDesactiver2"  id="btnDesactiver2" class="btn btn-primary">Desactiver</button>
                                                                    </div>
                                                                </form>
                                                              </div>

                                                          </div>
                                                      </div>
                                                </div>
												<!-------------------------- ACTIVATION 3 -------------------------------->
												<div class="modal fade" id='Activer3' tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                      <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"  >Activation</h4>
                                                              </div>
                                                              <div class="modal-body">
															  	  <form   method="post"  id="formActMontLigne" method="post">
                                                                    <div class="form-group">
                                                                      <h2>Voulez vous vraiment activer le pourcentage sur ventes</h2>
																	  <input type="hidden" name="i3a" id="i3a" >
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>  
																		<button type="button" name="btnActiver" id="btnActiver3" value="1" class="btn btn-primary">Activer</button>
                                                                    </div>
                                                                  </form>
                                                              </div>
                                                          </div>
                                                      </div>
                                                </div>
                                                <div class="modal fade" id='Desactiver3' tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                      <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                              <div class="modal-header">
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"  >Desactivation</h4>
                                                              </div>
                                                              <div class="modal-body">
															  		<form  method="post"  id="formDesactMontLigne">
																		<div class="form-group">
																		<h2>Voulez vous vraiment desactiver le montant fixe</h2>
																		<input type="hidden" name="i3b" id="i3b" >
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																			<button type="button" value='1' name="btnDesactiver3"  id="btnDesactiver3" class="btn btn-primary">Desactiver</button>
																		</div>
                                                                	</form>
                                                              </div>

                                                          </div>
                                                      </div>
                                                </div>	

	<ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PARAMETTRES DE PAYEMENTS DES CAISSES</a></li>
    </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
								<div class="table-responsive"> 
											<label class="pull-left" for="nbEntreParam">Nombre entrées </label>
											<select class="pull-left" id="nbEntreParam">
											<optgroup>
												<option value="10">10</option>
												<option value="20">20</option>
												<option value="50">50</option>  
											</optgroup>       
											</select>
											<input class="pull-right" type="text" name="" id="searchInputParamPaiem" placeholder="Rechercher...">
											<div id="resultsParamPaiem"><!-- content will be loaded here --></div>
								</div>
															
                                
                            </div>

                        </div>
</div>


<script type="text/javascript" src="paiement/js/scriptParametre.js"></script>