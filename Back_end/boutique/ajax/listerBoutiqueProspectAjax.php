<?php

session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');


$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;

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


$operation=@$_POST['operation'];

if ($operation=="lister" ) {
    $idBP=$_POST['i'];
    $idClient=$_POST['client'];

    $req1 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospect` where idBP=:i "); 
    $req1->execute(['i'=>$idBP])  or die(print_r($req1->errorInfo())); 
    $boutiqueProspec = $req1->fetch();

    $selType='<td><span style="" >
      <select class="form-control" id="boutiqueProspType-'.$idBP.'" disabled>
          <option  selected value="'.$boutiqueProspec["type"].'" >'.$boutiqueProspec['type'].'</option>';
          
          $sql = $bdd->prepare("SELECT * FROM `aaa-typeboutique`"); 
											$sql->execute()  or die(print_r($sql->errorInfo()));
											while($typeboutique =$sql->fetch()) {
												 $selType=$selType."<option value= '".$typeboutique[1]."'>".$typeboutique[1].'</option>';
											} 
      $selType=$selType.'</select></span></td>';

    $selCategorie='<td><span style="" >
      <select class="form-control" id="boutiqueCategorie-'.$idBP.'" disabled>
          <option  selected value="'.$boutiqueProspec["categorie"].'" >'.$boutiqueProspec['categorie'].'</option>';
          
      $sql = $bdd->prepare("SELECT * FROM `aaa-categorie`"); 
											$sql->execute()  or die(print_r($sql->errorInfo()));
											while($categorieBoutique =$sql->fetch()) {
												 $selCategorie=$selCategorie."<option value= '".$categorieBoutique[1]."'>".$categorieBoutique[1].'</option>';
											} 
    $selCategorie=$selCategorie.'</select></span></td>'; ?>
           <form name="formulaireVersement" method="post" >
								<div class="form-group">
									<label for="nomBoutique">NOM ENTREPRISE <font color="red">*</font></label>
									<?php echo '<input type="text"  class="form-control" name="boutiqueProsp" id="boutiqueProspNom-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['nomBoutique'].'" disabled />'; ?>
									<?php echo '<input type="hidden"  class="form-control" name="clientProsp" id="clientProsp-'.$boutiqueProspec['idBP'].'" min=1 value="'.$idClient.'" disabled />'; ?>
									<?php echo '<input type="hidden"  class="form-control" name="boutiqueProsp" id="boutiqueProsp-'.$boutiqueProspec['idBP'].'" min=1 value="'.$idBP.'" disabled />'; ?>
									
								</div>
								<div class="form-group">
									<label for="adresse">ADRESSE <font color="red">*</font></label>
									<?php echo '<input type="text" class="form-control" id="boutiqueProspAdre-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['adresseBoutique'].'"  disabled/>'; ?>
								</div>
								<div class="form-group">
									<label for="adresse">TELEPHONE <font color="red">*</font></label>
									<?php echo'<input type="text"  class="form-control" id="boutiqueProspTel-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['telephone'].'"  disabled/>';?>
								</div>
								<div class="form-group">
									<label for="type">TYPE <font color="red">*</font></label>
									<?php echo  $selType; ?>
								</div>
								<div class="form-group">
									<label for="type">CATEGORIE <font color="red">*</font></label>
									<?php
                      echo  $selCategorie;
										?>
								</div>
								<div class="form-group">
									<label for="adresse">REGISTRE DE COMMERCE <font color="red">*</font></label>
									<?php
                      echo  '<input type="text" class="form-control" id="boutiqueProspDatecreation-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['datecreation'].'" disabled />';
									?>
								</div>
								<div class="form-group">
									<label for="adresse">NINEA <font color="red">*</font></label>
									<?php
                      echo  '<input type="text" class="form-control" id="boutiqueProspRegistreCom-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['RegistreCom'].'" disabled />';
										?>
								</div>
                <div class="form-group">
									<label for="adresse">DATE DE CREATION <font color="red">*</font></label>
									<?php
                      echo  '<input type="text" class="form-control" id="boutiqueProspDatecreation-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['datecreation'].'" disabled />';
									?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                  <?php 
                      //var_dump($boutiqueProspec);
                      if( $boutiqueProspec['installer'] == 0 ){?>
                        <button type="button" class="boutonbasic" id="installationBoutiqueProspect" name="installationBoutiqueProspect" onclick="installerBoutiqueProsp(<?= $idClient.','.$idBP?>)">INSTALLER LA BOUTIQUE</button>
                      <?php }?>
									</div>
			    </form>
        
    <?php // echo '<table class="table table-striped contents display tabDesign tableau3" border="1">';
      // echo '<thead>
      //         <tr>
      //           <th>NomBoutique</th>
			// 					<th>AdresseBoutique</th>
			// 					<th>Téléphone</th>
			// 					<th>Type</th>
			// 					<th>Categorie</th>
			// 					<th>Evenement</th>
			// 					<th>Opération</th>
      //         </tr>
      //       </thead>';
      //           echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" >
      //           <span style="color:blue;"><input type="text"  class="form-control" id="boutiqueProspNom-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['nomBoutique'].'" disabled /></span></td>';
      //           echo  '<td><span style="color:blue;"><input type="text" class="form-control" id="boutiqueProspAdre-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['adresseBoutique'].'"  disabled/></span></td>';
      //           echo  '<td><span style="color:blue;"><input type="text"  class="form-control" id="boutiqueProspTel-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['telephone'].'"  disabled/></span></td>';
      //           echo  $selType;
      //           echo  $selCategorie;
      //           echo  '<td><span style="color:blue;"><input type="text" class="form-control" id="boutiqueProspDatecreation-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['datecreation'].'" disabled /></span></td>';
      //           echo  '<td><span style="color:blue;"><input type="text" class="form-control" id="boutiqueProspRegistreCom-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['RegistreCom'].'" disabled /></span></td>';
      //           echo  '<td><span style="color:blue;"><input type="text" class="form-control" id="boutiqueProspNinea-'.$boutiqueProspec['idBP'].'" min=1 value="'.$boutiqueProspec['Ninea'].'" disabled /></span></td>';
      //         ?>
                <!-- <td>  
                  <?php
                      ///////////////////////////////////
                        if ($client['idBoutiqueProspect']!=NULL) {
                          $sql00a = $bdd->prepare("SELECT * FROM `aaa-boutique-prospect` WHERE idBP =:b ");     
                          $sql00a->execute(array('b'=>$client['idBoutiqueProspect']));  
                          $boutique = $sql00a->fetch();
                          //echo  $pReferencea['telRefTransfertValidation'];
                          echo $boutique['nomBoutique'];
                          echo '<br><a href="#" onclick="DetailsBoutProspectPopPup('.$client['idBPC'].')" >Détails</a>';
                          /*echo  $payement['accompagnateur'];*/
                        } else { ?>
                          <button type="button" class="btn btn-primary"   
                            onclick="addBoutiqueProspectPopPup(<?php echo $client['idBPC'] ?>)" >
                              <i class="glyphicon glyphicon-plus"></i>Nouvelle entreprise
                          </button>
                        <?php }
                          ?>  
                </td>
                <td >
                  <div id="tdClientPost<?php echo $client['idBPC'] ?>">
                    <button type="button" class="btn btn-primary" 
                      onclick="activerInput(<?php echo $client['idBPC'] ?>)" id="btnActModCP<?php echo $client['idBPC'] ?>">
                                   <i class="glyphicon glyphicon-pencil"></i>Modifier
                    </button> 
                  </div>
                  <a onclick="spm_ClientP(<?=$client['idBPC']?>)" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                </td>
              </tr> -->
            <?php
          // $i++;
          // $cpt++;    
}

?>
