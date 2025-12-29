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
$messageDel='';

if($_SESSION['profil']!="SuperAdmin" AND $_SESSION['profil']!="Assistant")
    header('Location:admin-map.php');

if (isset($_POST['btnEnregistrerCompte'])) {

		$nomCompte=htmlspecialchars(trim($_POST['nomCompte']));
		$typeCompte=htmlspecialchars(trim($_POST['typeCompte']));
		$numeroCompte=htmlspecialchars(trim($_POST['numeroCompte']));
		$montantCompte=htmlspecialchars(trim($_POST['montantCompte']));

		// $sql1="insert into `aaa-compte` (nomCompte,typeCompte,numeroCompte,montantCompte) values('".$nomCompte."','".$typeCompte."','".$numeroCompte."','".$montantCompte."')";
		// //var_dump($sql1);
  		// $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
    
          $req6 = $bdd->prepare("insert into `aaa-compte` (nomCompte,typeCompte,numeroCompte,montantCompte)
          values(:nomC,:typ,:numC,:montant)");
            $req6->execute(array(
            'nomC' =>$nomCompte,
            'typ' =>$typeCompte,
            'numC' =>$numeroCompte,
            'montant' =>$montantCompte
            ))  or die(print_r($req6->errorInfo()));

    if($montantCompte>0){
        // $sqlv="select * from `aaa-compte` where nomCompte='".$nomCompte."'";
        // $resv=mysql_query($sqlv);
        // $compte =mysql_fetch_assoc($resv);
        
        $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE nomCompte=:in"); 
        $req1->execute(array('in' =>$nomCompte))  or die(print_r($req1->errorInfo())); 
        $compte=$req1->fetch(); 

        $numeroDestinataire='';
         $compteDonateur='';
        $nomClient='';
        $dateEcheance='2021-01-01';
        $operation='depot';
        $idCompte=$compte['idCompte'];
        $dateSaisie=$dateHeures;
        $description='INITIALISATION';
        if($typeCompte=='pret'){
            $operation='versement';
        }
        
        $sql2="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values
        ('".$montantCompte."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduserBack']."')";
          $res2=mysql_query($sql2) or die ("insertion Cmpte impossible =>".mysql_error() );
        
          $req6 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,
                                    dateEcheance,dateSaisie,dateOperation,description,idUser)
          values(:mont,:op,:idC,:numD,:compDon,:nomClt,:dateEch,:dateSai,:dateOp,:descr,:idUsr)");
            $req6->execute(array(
            'mont' =>$montantCompte,
            'op' =>$operation,
            'idC' =>$idCompte,
            'numD' =>$numeroDestinataire,
            'compDon' =>$compteDonateur,
            'nomClt' =>$nomClient,
            'dateEch' =>$dateEcheance,
            'dateSai' =>$dateHeures,
            'dateOp' =>$dateSaisie,
            'descr' =>$description,
            'idUsr' =>$_SESSION['iduserBack']
            ))  or die(print_r($req6->errorInfo()));
    }
}



?>

<div>
    <center>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient">
                                    <i class="glyphicon glyphicon-plus"></i>Ajouter compte
        </button>
    </center>


            <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau compte</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post" >

                              
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="inputprenom" required="" name="nomCompte" placeholder="Le nom du compte ici...">
                                  <span class="text-danger" ></span>
                              </div>
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                 <select name="typeCompte" id="typeCompteS" >
                                                        <?php
                                                            // $sqlv="select * from `aaa-comptetype` ";
                                                            // $resv=mysql_query($sqlv);
                                                            // while($operation =mysql_fetch_assoc($resv)){
                                                            //    echo '<option value="'.$operation["idType"].'">'.$operation["nomType"].'</option>';
                                                            // }

                                                            $req111 = $bdd->prepare("SELECT * FROM `aaa-comptetype` "); 
                                                            $req111->execute()  or die(print_r($req111->errorInfo())); 
                                                            //$compte=$req1->fetch(); 
                                                            while($operation =$req111->fetch()){
                                                               echo '<option value="'.$operation["idType"].'">'.$operation["nomType"].'</option>';
                                                            }
                                                        ?>
                                     
                                                    </select>
                                  <span class="text-danger" ></span>
                              </div>
                                <div class="form-group">
                                  <label for="inputEmail3" class="control-label" id="nclB">NUMERO COMPTE  <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="numeroCompte"  name="numeroCompte" placeholder="Numero du compte" value=""> 
                                  <span class="text-danger" ></span>
                              </div> 
                                <div class="form-group">
                                  <label for="inputEmail3" class="control-label" id="icLB">INITIALISATION COMPTE  </label>
                                  <input type="number" min="0" class="form-control" id="montantCompte" value="0" name="montantCompte" placeholder="Le montant initial du compte">
                                  <span class="text-danger" ></span>
                              </div>
                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="btnEnregistrerCompte" class="btn btn-primary">Enregistrer</button>
                               </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
                 <?php if($messageDel!=''){
                      echo '<center>
                                <div class="row">
                                    <div class="alert alert-danger">
                                        <strong>'.$messageDel.' </strong>
                                    </div>
                                </div>
                            </center>
                        ';
                }?>    
    <div class="row">
           
            <div class="container" align="center">
                <div class="modal fade" id="imgmodifierCompte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Modifier compte</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form name="formulairePersonnel" method="post" >


                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                                      <?php echo '<input type="hidden" class="form-control" id="inputIModH" name="idCompte" > '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputnomMod" name="nomCompte" > '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                                                                     <select name="typeCompte" id="selectMod" >
                                                                                                            <?php
                                                                                                                // echo '<option selected value="'.$compte["typeCompte"].'">'.$compte["typeCompte"].'</option>';
                                                                                                                // $sqlv="select * from `aaa-comptetype` ";
                                                                                                                // $resv=mysql_query($sqlv);

                                                                                                                $req1 = $bdd->prepare("SELECT * FROM `aaa-comptetype` "); 
                                                                                                                $req1->execute()  or die(print_r($req1->errorInfo())); 
                                                                                                                //$compte=$req1->fetch(); 
                                                                                                                while($operation =$req1->fetch()){
                                                                                                                   echo '<option value="'.$operation["idType"].'">'.$operation["nomType"].'</option>';
                                                                                                                }
                                                                                                            ?>
                                                                                                        </select>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                    <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NUMERO COMPTE  <font color="red">*</font></label>
                                                                                         <!-- <?php echo '<input type="text" class="form-control" id="inputprenom" name="numeroCompte" value="'.$compte['numeroCompte'].'"> '; ?> -->
                                                                                         <input type="text" class="form-control" id="numComMod" name="numeroCompte" > 
                                                                                      
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                            <button type="button" name="btnModifierCompte" id="btnModifierCompte" class="btn btn-primary">Modifier</button>
                                                                                   </div>
                                                                                </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                </div>
                <div class="modal fade" id="imgsuprimerCompte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Suprimer compte</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form name="formulairePersonnel" method="post" >


                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                                      <?php echo '<input type="hidden" class="form-control" id="idCSup" name="idCompte" > '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputnomCSup" name="nomCompte"  disabled> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputTypCSup" name="typeCompte"  disabled> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                    <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NUMERO COMPTE  <font color="red">*</font></label>
                                                                                         <?php echo '<input type="text" class="form-control" id="inputNumCSup" name="numeroCompte"  disabled> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                            <button type="button" name="btnSuprimerCompte"  id="btnSuprimerCompte" class="btn btn-primary">Suprimer</button>
                                                                                   </div>
                                                                                </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                <ul class="nav nav-tabs">
                  <li class="active">
                      <a data-toggle="tab" href="#LISTECLIENTS">LISTE DES COMPTES
                            <?php
							     if ($_SESSION['profil']=="SuperAdmin") {
                                        // $sqlC="SELECT SUM(montantCompte)    FROM `aaa-compte`  where activer='1' and typeCompte !='compte pret' ";
                                        // $resC=mysql_query($sqlC) or die ("select stock impossible =>".mysql_error());
                                        // $S_f = mysql_fetch_array($resC);
                                        // $montantT = $S_f[0];

                                        $sql01 = $bdd->prepare("SELECT sum(montantCompte) as total FROM `aaa-compte`  where activer=:a and typeCompte !=:c"); 
                                        $sql01->execute(array('a' =>1,  'c' =>'compte pret' ))  or die(print_r($sql01->errorInfo()));                                                         
                                        $total =$sql01->fetch();
                                        $montantT=$total['total'];
        

						 		        echo  ' => Montant Total =  '.number_format($montantT,0, ',', ' ').' FCFA ';
                                 } elseif ($_SESSION['profil']=="Assistant") {
                                    // $sqlC="SELECT SUM(montantCompte)    FROM `aaa-compte`  where activer='1' and typeCompte ='compte mobile' ";
                                    // $resC=mysql_query($sqlC) or die ("select stock impossible =>".mysql_error());
                                    // $S_f = mysql_fetch_array($resC);
                                    // $montantT = $S_f[0];

                                    $sql01 = $bdd->prepare("SELECT sum(montantCompte) as total FROM `aaa-compte`  where activer=:a and typeCompte !=:c"); 
                                    $sql01->execute(array('a' =>1,  'c' =>'compte mobile' ))  or die(print_r($sql01->errorInfo()));                                                         
                                    $total =$sql01->fetch();
                                    $montantT=$total['total'];
                                    echo  ' => Montant Total =  '.number_format($montantT,0, ',', ' ').' FCFA ';
                                 
                                 } 
                                 
							
						  ?>                                      
                      </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="LISTECLIENTS">
                    <table id="exemple" class="display" border="1" class="table table-bordered   table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Nom Compte</th>
                                                <th>Type Compte</th>
                                                <th>Numero Compte</th>
                                                <th>Etat</th>
                                                <th>Operation</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                 <th>Nom Compte</th>
                                                <th>Type Compte</th>
                                                <th>Numero Compte</th>
                                                <th>Etat</th>
                                                <th>Operation</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php  
                                                if ($_SESSION['profil']=="SuperAdmin" ) {
                                                    // $sql1="SELECT * FROM `aaa-compte`  where activer='1'" ;

                                                    $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE activer=:in"); 
                                                    $req1->execute(array('in' =>1))  or die(print_r($req1->errorInfo())); 
                                                    $comptes=$req1->fetchAll(); 
                                                } elseif($_SESSION['profil']=="Assistant") {
                                                    //$sql1="SELECT * FROM `aaa-compte`  where activer='1' and typeCompte='compte mobile'" ;

                                                    $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE activer=:in  and typeCompte=:tc"); 
                                                    $req1->execute(array('in' =>1,'tc'=>'compte mobile'))  or die(print_r($req1->errorInfo())); 
                                                    $comptes=$req1->fetchAll(); 
                                                }
                                                
                                                

                                                    //$res1 = mysql_query($sql1) or die ("utilisateur requête 4".mysql_error());
                                                    foreach ($comptes as $compte){ ?>
                                                        <tr>
                                                            <td><?php echo  $compte["nomCompte"]; ?> </td>
                                                            <td><?php 
                                                                // $sql0="SELECT * FROM `aaa-comptetype` WHERE idType ='".$compte["typeCompte"]."' ";
                                                                // $res0=mysql_query($sql0);
                                                                // $type=mysql_fetch_array($res0);
                                                                 
                                                                $req1 = $bdd->prepare("SELECT * FROM `aaa-comptetype` WHERE idType =:it"); 
                                                                $req1->execute(array ('it'=>$compte["typeCompte"]))  or die(print_r($req1->errorInfo())); 
                                                                //$compte=$req1->fetch(); 
                                                                $type =$req1->fetch();

                                                                echo  $type["nomType"]; 
                                                            
                                                                ?> 
                                                            </td>
                                                            <td><?php echo  $compte["numeroCompte"]; ?> </td>
                                                            <td><?php echo  number_format($compte['montantCompte'], 0, ',', ' ')." FCFA"; ?> <a   <?php echo  "href=compte/?p=detCmp&c=".$compte['idCompte'] ; ?> > Details</a></td>
                                                            <td> <a>
                                                                <!-- <img src="images/edit.png"  align="middle" alt="modifier" onClick="popModCom(<?php echo  $compte["idCompte"]; ?> )"  data-toggle="modal" <?php echo  "data-target=#imgmodifierCompte".$compte['idCompte']; ?> /></a>&nbsp;&nbsp; -->
                                                                <img src="images/edit.png"  align="middle" alt="modifier" onClick="popModCom(<?php echo  $compte["idCompte"]; ?> )"   /></a>&nbsp;&nbsp;
                                                                <a>
                                                                    <img src="images/drop.png" align="middle" alt="supprimer" onClick="popSupCom(<?php echo  $compte["idCompte"]; ?> )" /></a> 
                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                        </tbody>
                                    </table>
                    </div>
                </div>
           </div>
	</div>
</div>

<script type="text/javascript" src="compte/js/scriptCompte.js"></script> -->