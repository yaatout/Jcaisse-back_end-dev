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

 // Inclusion des fonctions de gestion des salaires
 var_dump($dateString);
 require 'functions/salaire_functions.php';
 var_dump($dateString2);
 require 'functions/one_functions.php';
 var_dump($dateString);
if (isset($_POST['payementSalaire'])) {
        $date = new DateTime();
        $annee =$date->format('Y');
        $mois =$date->format('m');
        $jour =$date->format('d');
        $heureString=$date->format('H:i:s');
        $dateString=$annee.'-'.$mois.'-'.$jour;
        $dateString2=$jour.'-'.$mois.'-'.$annee;	
    
        $aPayer=0;
    
        
        //var_dump($dateString2);     

        $req1 = $bdd->prepare("SELECT * FROM `aaa-contrat` WHERE dateDebut<=:debut and dateFin>=:fin and annuler=:an");
        $req1->execute(array('debut' =>$date->format('Y-m-d'),'fin' =>$date->format('Y-m-d'),'an'=>0))  or die(print_r($req1->errorInfo()));
        while ($contrat=$req1->fetch()) { 	       
            
            $dateD= new DateTime("".$contrat['dateDebut']."");
            $dateF= new DateTime("".$annee.'-'.$mois.'-'.$jour."");
            $intvl = $dateD->diff($dateF);
            $etape=$intvl->m;  
            
            $req2 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` WHERE idContrat=:idC and (dateCalcul like :anneeMois or dateCalcul like :moisAnnee)");
            $req2->execute(array('idC' =>$contrat['idContrat'],
                                         'anneeMois' =>'%'.$annee."-".$mois.'%',
                                         'moisAnnee' =>'%'.$mois."-".$annee.'%'))  or die(print_r($req2->errorInfo()));
            $salaire=$req2->fetch();
            //var_dump($salaire);
            if ($salaire) {
                
            } else {
                    /* $req3 = $bdd->prepare("SELECT * FROM `aaa-personnel` WHERE idPersonnel=:idP ");
                    $req3->execute(array('idP'=>$contrat['idPersonnel']))  or die(print_r($req3->errorInfo()));
                    $personnel=$req3->fetch(); */
                    $req3 = $bdd->prepare("INSERT INTO `aaa-salaire-personnel` (etape,idContrat,salaireDeBase,salaireNet,aPayer) 
                                                                    values (:et,:idC,:sb,:sn,:aP) ");
                    $req3->execute(array(
                            'et'=>$etape,
                            'idC'=>$contrat['idContrat'],
                            'sb'=>$contrat['montantSalaire'],
                            'sn'=>$contrat['montantSalaire'],
                            'aP'=>$aPayer
                            ))  or die(print_r($req3->errorInfo()));   
                    $req3->closeCursor();   
                    //var_dump($dateString);     
            }
            $req2->closeCursor(); 
        }
        $req1->closeCursor();
}
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
if (isset($_POST['btnVirerNoIng'])) {
        
        $idSP=$_POST['idSP'];
        $datePaiement=$_POST['datePaiement'];
        $comptePaiement=$_POST['comptePaiement'];
        $montant=$_POST['montant'];
        $matricule=$_POST['mat'];
        $telephone=$_POST['telephone'];
        $payer=1;
        /**********************************TABLE COMPTE *****************************************/
                            
                            $req2 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                            $req2->execute(array('idC' => $comptePaiement)) or die(print_r($req2->errorInfo()));
                            $compte=$req2->fetch();
                            //var_dump($compte);
                            if($compte['montantCompte']>=$montant){
                                /********************************DEBUT SALAIRE PERSONNEL**************************************/
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
                                $description='Paiement salaire :'.$matricule;
                                $newMontant=$compte['montantCompte']-$montant;
    
                                $req3 = $bdd->prepare("INSERT INTO `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser) 
                                                     values (:montant,:operation,:idCompte,:numeroDestinataire,:compteDonateur,:nomClient,:dateSaisie,:dateOperation,:description,:idUser)");
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
                                        'idUser'=>$_SESSION['iduserBack']
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
if (isset($_POST['btnVirerACC'])) {
        $idUtilisateur=$_POST['idU'];
        $user=recupererUtilisateursById($bdd,$idUtilisateur);
        //var_dump($idUtilisateur);

        $prenom=$user['prenom'];
        $nom=$user['nom'];
        $profil=$user['profil'];
        $telephone=$user['telPortable'];
        $matricule=$user['matricule'];

        $montant=$_POST['montant'];
        $datePaiement=$_POST['datePaiement'];
        $comptePaiement=$_POST['comptePaiement'];
        $payer=1;
       
        /**********************************TABLE COMPTE ******************************************/
                                $req2 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                                $req2->execute(array('idC' => $comptePaiement)) or die(print_r($req2->errorInfo()));
                                $compte=$req2->fetch();
                              if($compte['montantCompte']>=$montant){
                                 
                                  /********************************DEBUT SALAIRE PERSONNEL**************************************/
                               
                                    $req3 = $bdd->prepare("INSERT INTO `aaa-salaire-personnel` (salaireDeBase,aPayer,datePaiement,comptePaiement,accompagnateur) 
                                        values (:sb,:aP,:dP,:compte,:acc) ");
                                    $req3->execute(array(
                                            'sb'=>$montant,
                                            'aP'=>$payer,
                                            'dP'=>$datePaiement,
                                            'compte'=>$comptePaiement,
                                            'acc'=>$matricule
                                            ))  or die(print_r($req3->errorInfo()));   
                                    $req3->closeCursor();    
                                  /******************************** FINSALAIRE PERSONNEL****************************************/
    
                                  /***********************************************************************************/
                                    
                                    $sql2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` 
                                            WHERE accompagnateur =:acc 
                                            and partAccompagnateur>:part
                                            and aSalaireAccompagnateur=:asa 
                                            and aPayementBoutique=:ap "); 
                                    $sql2->execute(array('acc' =>$matricule,'part'=>0,'asa' =>0,'ap'=>1))  
                                                or die(print_r($sql2->errorInfo())); 
                                    $payements=$sql2->fetchAll() ;

                                  foreach($payements as $ps){
                                        // var_dump($ps);
                                        $req4 = $bdd->prepare("UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur=:asa where  idPS=:id");
                                        $req4->execute(array(
                                                        'asa' => 1,
                                                        'id' => $ps['idPS'] )) or die(print_r($req4->errorInfo()));
                                        $req4->closeCursor(); 
                                     }
                                  /***********************************************************************************/
                                  $operation='retrait';
                                  $idCompte=$compte['idCompte'];
                                  $compteDonateur='';
                                  $description="Paiement salaire accompagnateur :$prenom $nom au matricule ".$matricule.' ';
                                  $newMontant=$compte['montantCompte']-$montant;

                                
                                $req8 = $bdd->prepare("INSERT INTO `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser) 
                                    values (:montant,:operation,:idCompte,:numeroDestinataire,:compteDonateur,:nomClient,:dateSaisie,:dateOperation,:description,:idUser)");
                                $req8->execute(array(
                                        'montant'=>$montant,
                                        'operation'=>$operation,
                                        'idCompte'=>$idCompte,
                                        'numeroDestinataire'=>$telephone,
                                        'compteDonateur'=>$compteDonateur,
                                        'nomClient'=>$operation,
                                        'dateSaisie'=>$dateHeures,
                                        'dateOperation'=>$datePaiement,
                                        'description'=>$description,
                                        'idUser'=>$_SESSION['iduserBack']
                                        ))  or die(print_r($req8->errorInfo()));   
                                $req8->closeCursor();

                                $req9 = $bdd->prepare("UPDATE `aaa-compte` SET `montantCompte`=:montCompt  WHERE idCompte=:idCompte");
                                $req9->execute(array(
                                            'montCompt' => $newMontant,
                                            'idCompte' => $compte['idCompte'] )) or die(print_r($req9->errorInfo()));
                                $req9->closeCursor();  

                              }else{
                                  var_dump('GGGGGGGGGGGG');  
                              }
        /********************************TABLE COMPTE *******************************************/   
} 
if (isset($_POST['btnActiver'])) {
        $idBoutique=$_POST['idBoutique'];
        $activer=1;
        $sql3="UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur='".$activer."' where idBoutique=".$idBoutique;
        $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
        
} elseif (isset($_POST['btnDesactiver'])) {
        $idBoutique=$_POST['idBoutique'];
        $activer=0;
        $sql3="UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur='".$activer."' where idBoutique=".$idBoutique;
        $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}

?>

<div>
    <?php 
        if($_SESSION['profil']=="SuperAdmin"){ ?>
            <center>
                        <button type="submit" name="" class="btn btn-success" data-toggle="modal" data-target="#recalc">
                        <i class="glyphicon glyphicon-plus"></i>Recalcul des Salaires 
                        </button> 
                        <br><br>                   
            </center>
    <?php } ?>
            <center>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#rechModal">
                                            <i class="glyphicon glyphicon-plus"></i>Recherche du Salaire d'un Accompagnateur
                </button>
                <br><br>
            </center>
            <div class="modal fade" id="recalc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Recalcule </h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post" >
                            <h2>Voulez-vous effectuez le recalcul des salaires de ce mois ?</h2>
                              <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="payementSalaire" class="btn btn-primary">Enregistrer</button>
                               </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>            
            <div class="modal fade" id="rechModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="padding:35px 50px;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4><span class='glyphicon glyphicon-lock'></span> Recherche du Salaire d'un Accompagnateur </h4>
                        </div>
                        <div class="modal-body" style="padding:40px 50px;">
                            <table width="100%" align="center" border="0">
                                <form role="form" class="" name="formulaire2" id="InventaireStockForm" method="post" >
                                    <div class="form-group" >
                                        <input type="text" class="form-control" placeholder="Matricule de l\'Accompagnateur" id="matricule" name="matricule" required value="" />                        
                                    </div>
                                    <div class="modal-footer">
                                       <input type="submit" class="boutonbasic" name="envoyer" value="RECHERCHER" />
                                    </div>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                       
			<div class="modal fade" id="detailSalAccPop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Boutique non payement</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="detailSalaireAccDiv">
                                        </div>
                                    </div>
                               </div>
                            </div>
            </div>
            <!-- Debut ACCOMPAGNATEUR -->
            <div class="modal fade" id="virerAccPop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form  method="post" method="post" >
                                            <div class="form-group">
                                                
                                                        <h2>Virement pour <span id="infoVacc"></span></h2>
                                                    <p >Montant du virement : <span id="montantVAcc" class="text-danger"> </span> FCFA</p>
                                                    <p>Numéro du payeur : <font color="red">775243594</font></p>
                                                    <p>Numéro du récepteur : <font color="red" id="numeroVAcc"> </font></p>
                                                    <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                    <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                    <p>Etat du virement : <font color="red">En Préparation</font></p>
                                                    <input type="hidden" name="idU" id="iUVAcc" >
                                                    <input type="hidden" name="montant" id="montantUVAcc" >
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                    <input type="date" name="datePaiement" >
                                                    <span class="text-danger" ></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                    <select name="comptePaiement" id="selectvAcc" >
                                                       
                                                    </select>
                                                    <span class="text-danger" ></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnVirerACC" class="btn btn-primary">Effectuer le virement</button>
                                            </div>
                                        </form>
                                    </div>
                               </div>
                            </div>
            </div>
            <div class="modal fade" id="annulerVAcc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Annulation</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
                                <div class="form-group">
                                    <h2>Voulez vous vraiment annuler le virement</h2>
                                    <!-- <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($accompagnateur['accompagnateur'])."" ; ?> > -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnDesactiver" class="btn btn-primary">Annuler le virement</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIN ACCOMPAGNATEURS -->
            <!-- DEBUT PERSONNEL -->
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
        
            
<?php 
    if(!isset($_POST["matricule"])){ 
        require 'all.php';
    }
    else{ 
        require 'one.php';
    }
?> 
    
</div>

<script type="text/javascript" src="salaire/js/scriptSalaire.js"></script>
<script type="text/javascript" src="salaire/js/scriptOperation.js"></script> 