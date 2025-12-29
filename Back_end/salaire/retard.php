<?php
    $profilPersonnel=7;
    if (isset($_POST['btnVirerIng'])) {
        
        $idSP=$_POST['idSP'];
        $datePaiement=$_POST['datePaiement'];
        $comptePaiement=$_POST['comptePaiement'];
        $montant=$_POST['montant'];
        $matricule=$_POST['matriculePersonnel'];
        $telephone=$_POST['telephone'];
        $payer=1;
    
        
        $req01 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` sp  INNER JOIN `aaa-contrat` c ON sp.idContrat=c.idContrat 
                                WHERE sp.idSP =:idSP");                                           
        $req01->execute(array('idSP'=>$idSP))  or die(print_r($req01->errorInfo())); 
        $contrat=$req01->fetch(); 
    
       // $nouveauAvance= $contrat['avanceSalaire']+$motantAvance;
       $nouveauAvance= $contrat['avanceSalaire'];
        
    
        ///////////////////////
        
        $req02 = $bdd->prepare("select * from `aaa-personnel` where idPersonnel=:idP");
        $req02->execute(array('idP' => $contrat['idPersonnel'])) or die(print_r($req02->errorInfo()));
        $personnel=$req02->fetch();
    
        /**********************************TABLE COMPTE *****************************************/
                            
                            $req2 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                            $req2->execute(array('idC' => $comptePaiement)) or die(print_r($req2->errorInfo()));
                            $compte=$req2->fetch();
                            //var_dump($compte);
                            if($compte['montantCompte']>=$montant and $montant>0){
                                /********************************DEBUT SALAIRE PERSONNEL**************************************/
                                // if ($contrat['retenu'] > 0 and $contrat['retenu']<=$motantAvance) {
                                //     $payer=1;
                                // }
                                $req2 = $bdd->prepare("UPDATE `aaa-salaire-personnel` SET `salaireNet`=:net,`aPayer`=:payer,
                                                                                        `datePaiement`=:dateP,`comptePaiement`=:compteP WHERE idSP=:idSP");
                                $req2->execute(array(
                                            'net' => $montant,
                                            'payer' => $payer,
                                            'dateP' => $datePaiement,
                                            'compteP' => $comptePaiement,
                                            'idSP' => $idSP )) or die(print_r($req2->errorInfo()));
                                $req2->closeCursor();                                                          
                                /******************************** FINSALAIRE PERSONNEL****************************************/
                                $operation='retrait';
                                $idCompte=$compte['idCompte'];
                                $compteDonateur='';
                                $description='Paiement salaire :'.$personnel['prenomPersonnel'].' '.$personnel['nomPersonnel'].' aux matricule'.$personnel['matriculePersonnel'];
                                $newMontant=$compte['montantCompte']-$montant;
    
                                $req3 = $bdd->prepare("INSERT INTO `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser,idSP) 
                                                     values (:montant,:operation,:idCompte,:numeroDestinataire,:compteDonateur,:nomClient,:dateSaisie,:dateOperation,:description,:idUser,:sp)");
                                $req3->execute(array(
                                        'montant'=>$montant,
                                        'operation'=>$operation,
                                        'idCompte'=>$idCompte,
                                        'numeroDestinataire'=>$telephone,
                                        'compteDonateur'=>$compteDonateur,
                                        'nomClient'=>$operation,
                                        'dateSaisie'=>$dateHeures,
                                        'dateOperation'=>$datePaiement,
                                        'description'=>$description,
                                        'idUser'=>$_SESSION['iduserBack'],
                                        'sp'=>$idSP
                                        ))  or die(print_r($req3->errorInfo()));   
                                $req3->closeCursor();
    
                                $req4 = $bdd->prepare("UPDATE `aaa-compte` SET `montantCompte`=:montCompt  WHERE idCompte=:idCompte");
                                $req4->execute(array(
                                            'montCompt' => $newMontant,
                                            'idCompte' => $compte['idCompte'] )) or die(print_r($req4->errorInfo()));
                                $req4->closeCursor();  
    
                              }else{
                                  var_dump('GGGGGGGGGGGG');
                              }
        /********************************TABLE COMPTE **************************************/   
    }if (isset($_POST['btnVirerAvance'])) {
            
            $idSP=$_POST['idSP'];
            $datePaiement=$_POST['datePaiement'];
            $comptePaiement=$_POST['comptePaiement'];
            $motantAvance=$_POST['motantAvance'];
            $payer=0;
            $salaireNet=0;
            $retenu=0;
            ///////////////////////
            $req01 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` sp  INNER JOIN `aaa-contrat` c ON sp.idContrat=c.idContrat 
                                    WHERE sp.idSP =:idSP");                                           
            $req01->execute(array('idSP'=>$idSP))  or die(print_r($req01->errorInfo())); 
            $contrat=$req01->fetch(); 
            ///////////////////////
            
            $nouveauAvance= $contrat['avanceSalaire']+$motantAvance;
            $retenu=$contrat['salaireNet']-$nouveauAvance;
        
            $req02 = $bdd->prepare("select * from `aaa-personnel` where idPersonnel=:idP");
            $req02->execute(array('idP' => $contrat['idPersonnel'])) or die(print_r($req02->errorInfo()));
            $personnel=$req02->fetch();
            /**********************************TABLE COMPTE *****************************************/
                                
                                $req03 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                                $req03->execute(array('idC' => $comptePaiement)) or die(print_r($req03->errorInfo()));
                                $compte=$req03->fetch();
                                //var_dump($compte);
                                if($compte['montantCompte']>=$motantAvance  and $motantAvance>0){
        
                                    //if ($contrat['retenu'] > 0 and $contrat['retenu']<=$motantAvance) {
                                    if ($retenu == 0  and $nouveauAvance>0) {
                                        $payer=1;
                                        $motantAvance=$nouveauAvance;
                                    }
                                    /********************************DEBUT SALAIRE PERSONNEL**************************************/
                                    $req1 = $bdd->prepare("UPDATE `aaa-salaire-personnel` SET `avanceSalaire`=:asa,`retenu`=:ret,`aPayer`=:payer,
                                                                                            `datePaiement`=:dateP,`comptePaiement`=:compteP WHERE idSP=:idSP");
                                    $req1->execute(array(
                                                'asa' => $nouveauAvance,
                                                'ret' => $retenu,
                                                'payer' => $payer,
                                                'dateP' => $datePaiement,
                                                'compteP' => $comptePaiement,
                                                'idSP' => $idSP )) or die(print_r($req1->errorInfo()));
                                    $req1->closeCursor();                                                          
                                    /******************************** FINSALAIRE PERSONNEL****************************************/
                                    $operation='retrait';
                                    $idCompte=$compte['idCompte'];
                                    $compteDonateur='';
                                    $description='Paiement salaire :'.$personnel['prenomPersonnel'].' '.$personnel['nomPersonnel'].' aux matricule '.$personnel['matriculePersonnel'];
                                    $newMontant=$compte['montantCompte']-$motantAvance;
        
                                    $req3 = $bdd->prepare("INSERT INTO `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser,idSP) 
                                                        values (:montant,:operation,:idCompte,:numeroDestinataire,:compteDonateur,:nomClient,:dateSaisie,:dateOperation,:description,:idUser,:sp)");
                                    $req3->execute(array(
                                            'montant'=>$motantAvance,
                                            'operation'=>$operation,
                                            'idCompte'=>$idCompte,
                                            'numeroDestinataire'=>$personnel['telPersonnel'],
                                            'compteDonateur'=>$compteDonateur,
                                            'nomClient'=>$operation,
                                            'dateSaisie'=>$dateHeures,
                                            'dateOperation'=>$datePaiement,
                                            'description'=>$description,
                                            'idUser'=>$_SESSION['iduserBack'],
                                            'sp'=>$idSP
                                            ))  or die(print_r($req3->errorInfo()));   
                                    $req3->closeCursor();
        
                                    $req4 = $bdd->prepare("UPDATE `aaa-compte` SET `montantCompte`=:montCompt  WHERE idCompte=:idCompte");
                                    $req4->execute(array(
                                                'montCompt' => $newMontant,
                                                'idCompte' => $compte['idCompte'] )) or die(print_r($req4->errorInfo()));
                                    $req4->closeCursor();  
        
                                }else{
                                    var_dump('GGGGGGGGGGGG');
                                }
            /********************************TABLE COMPTE **************************************/   
    }
?>
            <div class="modal fade" id="virementPerSPop" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                        </div>
                        <div class="modal-body">
                            <form  method="post" >
                                <div class="form-group">
                                    <h2>Voulez vous vraiment effectuer le virement</h2>
                                    <p>Montant du virement : 
                                    <span id="montantVPers" class="text-danger"> </span> FCFA
                                    </p>
                                    <p>Numéro du récepteur : <font color="red"> <?php //echo  $contrat['telPersonnel'];  ?>
                                        </font><span id="numeroRecePers" class="text-danger"> </span> FCFA
                                    </p>
                                    <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                    <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                    <p>Etat du virement : <font color="red">En Préparation</font></p>
                                    <input type="hidden" name="idSP" id="idSP_Pers" >
                                    <input type="hidden" name="montant" id="montant_Pers"  >
                                    <input type="hidden" name="matriculePersonnel" id="matricule_Pers"  >
                                    <input type="hidden" name="telephone" id="telephone_Pers"  >
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                    <input type="date" name="datePaiement" <?php echo  "value=". $dateString."" ; ?> >
                                    <span class="text-danger" ></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                        <select name="comptePaiement" id="selectvPers" >
                                            
                                        </select>
                                        <span class="text-danger" ></span>
                                </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnVirerIng" class="btn btn-primary">Effectuer le virement</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="virerAvancePersPop" tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Paiement par avance</h4>
                        </div>
                    
                        <div class="modal-body">
                            <form  method="post" >
                                <div class="form-group">
                                    <h2>Voulez vous vraiment effectuer le virement</h2>
                                    <p>Montant du virement : <span id="montantVPers_Virem" class="text-danger"> </span><font color="red"> FCFA</font> 
                                    </p>
                                    <p>Numéro du récepteur : <font color="red"> 
                                        </font><span id="numeroRecePers_Virem" class="text-danger"> </span> 
                                    </p>
                                    <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                    <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                    <p>Etat du virement : <font color="red">En Préparation</font></p>
                                    <input type="hidden" name="idSP" id="idSP_Pers_Virem" >
                                    <input type="hidden" name="montant" id="montant_Pers_Virem"  >
                                    <input type="hidden" name="matriculePersonnel" id="matricule_Pers_Virem"  >
                                    <input type="hidden" name="telephone" id="telephone_Pers_Virem"  >
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                    <input type="date" name="datePaiement" <?php echo  "value=". $dateString."" ; ?>  >
                                    <span class="text-danger" ></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                    <input type="number" name="motantAvance" id="motantViremAvancePers" >
                                    <span class="text-danger" id="montantIndispo"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                    <select name="comptePaiement" id="selectvPers_Avance" >                                                                                              
                                    
                                    </select>
                                    <span class="text-danger" ></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnVirerAvance" id="btnVirerAvancePers" class="btn btn-primary">Effectuer le virement</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    <div class="row">
        <div class="">
            <div class="card " style=" ;">
              <!-- Default panel contents
              <div class="card-header text-white bg-success">Liste du personnel</div>-->
              <div class="card-body">
                <div class="container">
                    <center>
                    <?php
                         $stmt1 = $bdd->prepare("
                         SELECT   SUM(salaireDeBase) as total FROM `aaa-contrat` c
                             INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
                             INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
                             WHERE   p.profilPersonnel = :profil AND aPayer =  :ap 
                     ");
                     
                     $stmt1->execute([
                         'ap' => 0,
                         'profil' => $profilPersonnel
                     ]);
                     $sommesSalairesPersonnel['nonPayes'] = (float)$stmt1->fetchColumn();
                     
                    ?>

                        <div class="jumbotron noImpr">
                            <p>Total Paiements Effectifs: <font color="red"><?php echo  $sommesSalairesPersonnel['nonPayes']; ?> FCFA</font></p>
                        </div>
                    </center>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#PERSONNEL">PERSONNEL</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="PERSONNEL">
                                <div class="table-responsive">                
									<label class="pull-left" for="nbEntreeRetardPers">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeRetardPers">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputRetardPers" placeholder="Rechercher...">
										
										<div id="resultsRetardPers"><!-- content will be loaded here --></div>
								</div>
                        </div>
                                
                    </div>
                </div>
            </div>
                
        </div>
    </div>

    <script type="text/javascript" src="salaire/js/scriptRetardSalaire.js"></script>
    <script type="text/javascript" src="salaire/js/scriptOperation.js"></script> 
    