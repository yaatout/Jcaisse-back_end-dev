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
require('connectionPDO.php');

require('declarationVariables.php');
//$date = new DateTime('25-02-2011');
    $date = new DateTime();
//var_dump($date);
        //R�cup�ration de l'ann�e
        $annee =$date->format('Y');
        //R�cup�ration du mois
        $mois =$date->format('m');
        //R�cup�ration du jours
        $jour =$date->format('d');
            $heureString=$date->format('H:i:s');
        $dateString=$annee.'-'.$mois.'-'.$jour;
        $dateString2=$jour.'-'.$mois.'-'.$annee;
        $dateHeures=$dateString.' '.$heureString;
   

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

if (isset($_POST['payementSalaire'])) {
    $date = new DateTime();
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;	

    $aPayer=0;

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

    $nouveauAvance= $contrat['avanceSalaire']+$motantAvance;
    

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
	$nom=$_POST['nom'];
	$prenom=$_POST['prenom'];
	$profil=$_POST['profil'];
    $telephone=$_POST['telephone'];
	$montant=$_POST['montant'];
	$datePaiement=$_POST['datePaiement'];
	$comptePaiement=$_POST['comptePaiement'];
	$matricule=$_POST['mat'];
    $tabIdACC     =  $_POST["tabIdACC"] ;
    $payer=1;
    //var_dump($tabIdACC);
    $tabIdACC =explode('-',$tabIdACC);
    $tabIdACC =array_filter($tabIdACC);
	//var_dump($tabIdACC);
	// die($tabIdACC);
	  
	/**********************************TABLE COMPTE ******************************************/
                            $req2 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                            $req2->execute(array('idC' => $comptePaiement)) or die(print_r($req2->errorInfo()));
                            $compte=$req2->fetch();
                          if($compte['montantCompte']>=$montant){
                             
                              /********************************DEBUT SALAIRE PERSONNEL**************************************/
                              
                              $sql3="INSERT INTO `aaa-salaire-personnel` ( `idUtilisateur`, `nom`, `prenom`, `profil`, `telephone`, `montant`,
                                                                             `datePaiement`, `comptePaiement`) 
                              VALUES ('".$idUtilisateur."', '".$nom."', '".$prenom."', '".$profil."', '".$telephone."', '".$montant."',
                                                                             '".$datePaiement."', '".$compte['idCompte']."')";
                               
                              ////nomCompte à idCompte                                              die($sql3);
	                           $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
                              

                               $req3 = $bdd->prepare("INSERT INTO `aaa-salaire-personnel` (salaireDeBase,aPayer,datePaiement,accompagnateur) 
                                                                values (:et,:sb,:aP,:dP,:acc) ");
                                $req3->execute(array(
                                        'sb'=>$montant,
                                        'sb'=>$aPayer,
                                        'sb'=>$datePaiement,
                                        'aP'=>$aPayer
                                        ))  or die(print_r($req3->errorInfo()));   
                                $req3->closeCursor();  
                              /******************************** FINSALAIRE PERSONNEL****************************************/

                              /***********************************************************************************/
                               foreach($tabIdACC as $idPS){
                                     $sql7="UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur=1 where  idPS=".$idPS."";  
                                     //var_dump($sql7);
                                     $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas 1 ".mysql_error());
                                 }
                              /***********************************************************************************/
                              $operation='retrait';
                              $idCompte=$compte['idCompte'];
                              $compteDonateur='';
                              $description='Paiement salaire de :'.$matricule.' ';
                              $newMontant=$compte['montantCompte']-$montant;
                            $sql8="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser) 
                             values('".$montant."','".$operation."','".$idCompte."','".$telephone."','".$compteDonateur."','".$nom."','".$dateHeures."','".$datePaiement."','".$description."','".$_SESSION['iduserBack']."')";
                              $res8=mysql_query($sql8) or die ("insertion Cmpte impossible =>".mysql_error() );
                              //var_dump($res8);
                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";  
                              //var_dump($sql7);                     
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas 2".mysql_error());
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
require('entetehtml.php');
?>

<body>
	
	<?php   require('header.php'); ?>

	<?php
	if($_SESSION['profil']=="SuperAdmin"){ ?>
        <center>
            <div class="modal-body">
                <form name="formulairePayementSalaire" method="post" action="salaireAccompagnateurs.php">
                  <div>
                    <button type="submit" name="payementSalaire" class="btn btn-success"> Recalcul des Salaires </button>
                    <!-- <button class="btn btn-danger">Recalcul des Salaires fonctionnalité Desactiver</button> -->
                   </div>
                </form>
            </div>
		</center>
<?php
	}

    echo'<div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#InventaireModal" data-dismiss="modal" id="InventaireStock">
<i class="glyphicon glyphicon-plus"></i>Recherche du Salaire d\'un Accompagnateur</button>';

echo'<div class="modal fade" id="InventaireModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Recherche du Salaire d'un Accompagnateur </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0">'.
'<form role="form" class="" name="formulaire2" id="InventaireStockForm" method="post" action="salaireAccompagnateurs.php">';

echo'<div class="form-group" >'.
	'<tr class="reference">';
		echo'<td><input type="text" class="form-control" placeholder="Matricule de l\'Accompagnateur" id="matricule" name="matricule" required value="" /></td></tr>';

echo'<tr><td colspan="2" align="center"><div class="modal-footer">'.
  '<input type="submit" class="boutonbasic" name="envoyer" value="RECHERCHER" />';


echo'</td></tr>'.
'</div>';
echo'</form></table><br /><br />'.
'</div></div></div></div></div>';
if(!@$_POST["matricule"]){
    ?>
		<div class="row">
			
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                    <div class="container" align="center"> <br/>
                      <?php
							$somme=0;
							$sql1="SELECT DISTINCT accompagnateur FROM `aaa-payement-salaire`" ;
                            $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
							while($accompagnateur=mysql_fetch_array($res1)) {
							$sql4="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$accompagnateur["accompagnateur"]."'
                             and aPayementBoutique=1 and aSalaireAccompagnateur=0 order by datePS DESC LIMIT 1" ;
							$res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
							while($payement=mysql_fetch_array($res4)) {
							    $somme=$somme+$payement['partAccompagnateur'];
                                }}
                            
                            $somme2=0;
							$sql12="SELECT DISTINCT accompagnateur FROM `aaa-payement-salaire`" ;
                            $res12 = mysql_query($sql12) or die ("etape requête 4".mysql_error());
							while($accompagnateur2=mysql_fetch_array($res12)) {
							$sql42="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$accompagnateur["accompagnateur"]."'
                             and aPayementBoutique=1 and aSalaireAccompagnateur=1 order by datePS DESC LIMIT 1" ;
							$res42 = mysql_query($sql42) or die ("etape requête 4".mysql_error());
							while($payement2=mysql_fetch_array($res4)) {
							    $somme2=$somme2+$payement2['partAccompagnateur'];

                                }}
						?>

                        <div class="jumbotron noImpr">

                            <h2>Aujourd'hui : <?php echo $dateString2; ?></h2>

                            <p>Cumul des Salaires du mois en cours : <font color="red"><?php echo $somme; ?> FCFA</font></p>
                            <p>Cumul des Salaires du payés : <font color="red"><?php echo $somme2; ?> FCFA</font></p>
                            <?php
                            if($_SESSION['profil']=="SuperAdmin" || $_SESSION['profil']=="Assistant"){ ?>
                                <center>
                                <div class="modal-body">
                                    <form name="formulairePayementSalaire" method="post" action="#">
                                      <div>
                                        <!-- <button type="submit" name="virementSalaire" class="btn btn-success"> Virement des Salaires </button> -->
                                        <button class="btn btn-danger">  Virement des Salaires Button desactivé</button>
                                       </div>
                                    </form>
                                </div>
                                </center>
                               <?php
                            }  ?>
                        </div>

                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DU PERSONNEL</a></li>
                          <li ><a data-toggle="tab" href="#LISTEACCOMPAGNATEUR">LISTE DES PARTS DES ACCOMPAGNATEURS</a></li>
                          <li ><a data-toggle="tab" href="#LISTEING">INGENIEURS</a></li>
                          <li ><a data-toggle="tab" href="#LISTEEDICATA">EDITEUR CATALOGUE</a></li>
                          <li ><a data-toggle="tab" href="#LISTEASSIST">ASSISTANT5(E)</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                                <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>PERSONNEL</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Salaire de base (FCFA)</th>
                                            <th>Salaire de net (FCFA)</th>
                                            <th>Avance (FCFA)</th>
                                            <th>Restant (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>PERSONNEL</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Salaire de base (FCFA)</th>
                                            <th>Salaire de net (FCFA)</th>
                                            <th>Avance (FCFA)</th>
                                            <th>Restant (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            // PERS=7                     `profilPersonnel` = '7'    
                                            //$req1 = $bdd->prepare("SELECT * FROM `aaa-contrat` WHERE dateDebut<=:debut and dateFin>=:fin and idPersonnel IS NOT NULL");
                                            //$req1->execute(array('debut' =>$date->format('Y-m-d'), 'fin' =>$date->format('Y-m-d')))  or die(print_r($req1->errorInfo()));
                                            $req1 = $bdd->prepare("SELECT * FROM `aaa-contrat` c 
                                                                            INNER JOIN `aaa-personnel` p ON c.idPersonnel=p.idPersonnel 
                                                                            WHERE p.profilPersonnel =:pr and c.dateDebut<=:debut and c.dateFin>=:fin");
                                            
                                            $req1->execute(array('debut' =>$date->format('Y-m-d'),
                                                                'fin' =>$date->format('Y-m-d'),
                                                                'pr'=>7))  or die(print_r($req1->errorInfo()));   
                                            
                                                                // var_dump($req1);
                                                                // echo $date->format('Y-m-d');
                                            while ($contrat=$req1->fetch()) { 
                                                $req2 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` WHERE idContrat=:idC and (dateCalcul like :anneeMois or dateCalcul like :moisAnnee) ");                                                
                                                $req2->execute(array('idC' =>$contrat['idContrat'],
                                                                    'anneeMois' =>'%'.$annee."-".$mois.'%',
                                                                    'moisAnnee' =>'%'.$mois."-".$annee.'%')) or die(print_r($req2->errorInfo()));    
                                                // $req2 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` WHERE idContrat=:idC and (dateCalcul like :anneeMois or dateCalcul like :moisAnnee) or (idContrat=:idC and dateCalcul=ce mois) order by aPayer ");                                                
                                                // $req2->execute(array('idC' =>$contrat['idContrat'])) or die(print_r($req2->errorInfo()));                       
                                                while($salaire=$req2->fetch()) {  ?>                                                
                                                    <tr>
                                                        <td> 
                                                            <?php
                                                        // $sql9="SELECT * FROM `aaa-personnel` where idPersonnel='".$contrat["idPersonnel"]."'";
                                                        // $res9 = mysql_query($sql9) or die ("utilisateur requête 2".mysql_error());
                                                        // $personnel = mysql_fetch_array($res9);
                                                        //var_dump($salaire);
                                                        // // var_dump($contrat['idContrat']);
                                                        // var_dump($salaire['idSP']);
                                                        echo $contrat['matriculePersonnel']; ?>  </td>
                                                        <td> <?php echo  $contrat['prenomPersonnel'];  ?>  </td>
                                                        <td> <?php echo  $contrat['nomPersonnel'];   ?>  </td>
                                                        <td> <?php echo  $salaire['salaireDeBase'];  ?>  </td>
                                                        <td> <?php echo  $salaire['salaireNet'];  ?>  </td>
                                                        <td> <?php echo  $salaire['avanceSalaire'];  ?>  </td>
                                                        <td> <?php echo  $salaire['retenu'];  ?>  
                                                        </td>
                                                        <td> <?php echo  $contrat['dateDebut'];  ?>  </td>
                                                        <td> <?php echo  $contrat['dateFin'];  ?>  </td>
                                                        <td> <?php echo  $salaire['etape']; ?> </td>
                                                        <?php 
                                                        if ($salaire['aPayer']==0) { ?>
                                                                <td><span>En cour...</span></td>
                                                                <td>
                                                                <!-- <?php /* if ($jour>=25 && $jour<=15 ) { */ ?> -->
                                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverI".$contrat['idContrat'] ; ?> >
                                                                        Virer
                                                                    </button>
                                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#AvanceI".$contrat['idContrat'] ; ?> >
                                                                        Payer avance
                                                                    </button>
                                                                <!-- <?php /* } else {  */?>
                                                                    <p>..</p>
                                                                <?php /* } */  ?> -->
                                                                    
                                                                </td>
                                                                <?php
                                                        } else { ?>
                                                                <td><span>Effectif</span></td>
                                                                <td>
                                                                    <!-- <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI9999".$contrat['idContrat'] ; ?> >
                                                                        Annuler</button> 
                                                                    -->
                                                                </td>
                                                            <?php }
                                                            ?>
                                                        <div class="modal fade" <?php echo  "id=ActiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form  method="post" action="salaireAccompagnateurs.php">
                                                                        <div class="form-group">
                                                                            <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                            <p>Montant du virement : <font color="red"><?php echo $salaire['salaireNet']-$salaire['avanceSalaire']; ?> FCFA</font></p>
                                                                            <p>Numéro du récepteur : <font color="red"> <?php echo  $contrat['telPersonnel'];  ?></font></p>
                                                                            <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                            <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                            <p>Etat du virement : <font color="red">En Préparation</font></p>
                                                                            <input type="hidden" name="idSP" <?php echo  "value=". $salaire['idSP']."" ; ?> >
                                                                            <input type="hidden" name="montant" <?php echo  "value=". intval($salaire['salaireNet']-$salaire['avanceSalaire'])."" ; ?> >
                                                                            <input type="hidden" name="matriculePersonnel" <?php echo  "value=". $contrat['matriculePersonnel']."" ; ?> >
                                                                            <input type="hidden" name="telephone" <?php echo  "value=". $contrat['telPersonnel']."" ; ?> >
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                <input type="date" name="datePaiement"  >
                                                                                <span class="text-danger" ></span>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                                <select name="comptePaiement" id="" >
                                                                                                        <?php
                                                                                                            //$sqlv="select * from `aaa-compte` ";
                                                                                                            $sqlv="select * from `aaa-compte` where (`typeCompte`=2 OR `typeCompte`=5) and `montantCompte` >= '".$contrat['montantSalaire']."' ";
                                                                                            
                                                                                                            $resv=mysql_query($sqlv);
                                                                                                            while($operation =mysql_fetch_assoc($resv)){
                                                                                                            echo '<option value="'.$operation["idCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                                            }
                                                                                                        ?>

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
                                                        <div class="modal fade" <?php echo  "id=DesactiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                                            <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($accompagnateur['accompagnateur'])."" ; ?> >
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
                                                        <div class="modal fade" <?php echo  "id=AvanceI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Paiement par avance</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                    <form  method="post" action="salaireAccompagnateurs.php">
                                                                        <div class="form-group">
                                                                            <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                            <p>Montant du virement : <font color="red"><?php echo $salaire['salaireNet']-$salaire['avanceSalaire']; ?> FCFA</font></p>
                                                                            <p>Numéro du récepteur : <font color="red"> <?php echo  $contrat['telPersonnel'];  ?></font></p>
                                                                            <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                            <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                            <p>Etat du virement : <font color="red">En Préparation</font></p>
                                                                            <input type="hidden" name="idSP" <?php echo  "value=". $salaire['idSP']."" ; ?> >
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                <input type="date" name="datePaiement"  >
                                                                                <span class="text-danger" ></span>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                                <input type="number" name="motantAvance"  >
                                                                                <span class="text-danger" ></span>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                                <select name="comptePaiement" id="" >
                                                                                                        <?php
                                                                                                            //$sqlv="select * from `aaa-compte` ";
                                                                                                            $sqlv="select * from `aaa-compte` where (`typeCompte`=2 OR `typeCompte`=5) and `montantCompte` >= '".intval($salaire['salaireDeBase']-$salaire['avanceSalaire'])."' ";
                                                                                            
                                                                                                            $resv=mysql_query($sqlv);
                                                                                                            while($operation =mysql_fetch_assoc($resv)){
                                                                                                            echo '<option value="'.$operation["idCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                                            }
                                                                                                        ?>

                                                                                                    </select>
                                                                                <span class="text-danger" ></span>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                    <button type="submit" name="btnVirerAvance" class="btn btn-primary">Effectuer le virement</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </tr><?php 
                                                }
                                                $req2->closeCursor(); 
                                            }
                                            $req1->closeCursor();
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="LISTEACCOMPAGNATEUR">
                                <table id="exemple2" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>Accompagnateur</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part attendu(FCFA)</th>
                                            <th>Part actuel(FCFA)</th>
                                            <th>Date Calcul</th>
                                            <th>Virement</th>
                                            <th>Virement/Annulation</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Accompagnateur</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part attendu(FCFA)</th>
                                            <th>Part actuel(FCFA)</th>
                                            <th>Date Calcul</th>
                                            <th>Virement</th>
                                            <th>Virement/Annulation</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $sql2="SELECT * FROM `aaa-utilisateur` where profil='Accompagnateur' ";
                                            $res2 = mysql_query($sql2) or die ("utilisateur requête 2".mysql_error());
                                            while($utilisateur = mysql_fetch_array($res2)) { ?>
                                                <tr>
                                                    <td> <b><?php echo  $utilisateur["matricule"];  ?></b>  </td>
                                                    <td> <b><?php echo  $utilisateur["prenom"];  ?></b>  </td>
                                                    <td> <b><?php echo  $utilisateur["nom"];  ?></b>  </td>
                                                    <td> 
                                                        <b><?php 
                                                            $sql4="SELECT SUM(partAccompagnateur) AS value_sum  FROM `aaa-payement-salaire` WHERE accompagnateur ='".$utilisateur["matricule"]."' and aSalaireAccompagnateur='0'  " ;
                                                            $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error()); 
                                                            $payementAtt=mysql_fetch_array($res4);
                                                            echo  $payementAtt["value_sum"]; 
                                                        ?></b>  
                                                    </td>
                                                    <td> <b><?php 
                                                                /* 
                                                                $sql5="SELECT SUM(partAccompagnateur) AS value_sum  FROM `aaa-payement-salaire` WHERE accompagnateur ='".$utilisateur["matricule"]."' and aSalaireAccompagnateur='0' and aPayementBoutique=1 " ;
                                                                $res5 = mysql_query($sql5) or die ("etape requête 4".mysql_error()); 
                                                                $payementTot=mysql_fetch_array($res5);
                                                                echo  $payementTot["value_sum"];   
                                                                */
                                                                $sql3="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$utilisateur["matricule"]."' and aSalaireAccompagnateur='0' and aPayementBoutique=1 " ;
                                                                $res3 = mysql_query($sql3) or die ("etape requête 4".mysql_error());
                                                                $somme1=0;
                                                                $tabIdACC[]=null;
                                                                while($payement=mysql_fetch_array($res3)) {
                                                                    if($tabIdACC[0]==0){
                                                                        $tabIdACC[]=NULL;
                                                                    }
                                                                     $somme1=$somme1+$payement['partAccompagnateur'];
                                                                     $tabIdACC[] =$payement['idPS'];
                                                                }
                                                                echo  $somme1; ?>
                                                                   <button class="btn btn-danger" class="btn btn-success" data-toggle="modal"  <?php echo  "data-target=#detail".$utilisateur["idutilisateur"] ; ?> >detail</button>
                                                            </b>  
                                                    </td>
                                                    <td> <b><?php 
                                                                     $sql6="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$utilisateur["matricule"]."' and aPayementBoutique=1 order by datePS DESC LIMIT 1" ;
                                                                     $res6 = mysql_query($sql6) or die ("etape requête 4".mysql_error());
                                                                     $complementAccompagnateur=mysql_fetch_array($res6);
                 
                                                                     echo  $complementAccompagnateur['datePS'];
                                                              ?>
                                                        </b>  
                                                    </td>
                                                    <?php
                                                         if ($complementAccompagnateur['aSalaireAccompagnateur']==0) { ?>
                                                            <td><span>En cour...</span></td>
                                                            <td>
                                                            <?php if ($complementAccompagnateur['aPayementBoutique']==0) { ?> 
                                                                <p>Non paiement boutique </p>   
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$utilisateur["idutilisateur"] ; ?> >
                                                                Virer</button>
                                                            <?php } ?>
                                                                
                                                            </td>
                                                            <?php
                                                        } else { ?>
                                                            <td><span>Effectif</span></td>
                                                            <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$utilisateur["idutilisateur"] ; ?> >
                                                            Annuler</button></td>
                                                        <?php }
                                                         ?>
                                                         <div class="modal fade" <?php echo  "id=Activer".$utilisateur["idutilisateur"] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
                                                                            <div class="form-group">
                                                                                <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                                <p>Montant du virement : <font color="red"><?php echo $somme1; ?> FCFA</font></p>
                                                                                <p>Numéro du payeur : <font color="red">775243594</font></p>
                                                                                <p>Numéro du récepteur : <font color="red"> <?php echo  $utilisateur['telPortable'];  ?></font></p>
                                                                                <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                                <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                                <p>Etat du virement : <font color="red">En Préparation</font></p>
                                                                                <input type="hidden" name="idU" <?php echo  "value=". $utilisateur['idutilisateur']."" ; ?> >
                                                                                <input type="hidden" name="nom" <?php echo  "value=". $utilisateur['prenom']."" ; ?> >
                                                                                <input type="hidden" name="prenom" <?php echo  "value=". $utilisateur['nom']."" ; ?> >
                                                                                <input type="hidden" name="profil" <?php echo  "value=". $utilisateur['profil']."" ; ?> >
                                                                                <input type="hidden" name="telephone" <?php echo  "value=".  $utilisateur['telPortable']."" ; ?> >
                                                                                <input type="hidden" name="mat" <?php echo  "value=".  $utilisateur['matricule']."" ; ?> >

                                                                                <!--<input type="hidden" name="etape" <?php echo  "value=". $accompagnateur['accompagnateur']."" ; ?> >-->
                                                                                <input type="hidden" name="montant" <?php echo  "value=". $somme1."" ; ?> >
                                                                                <?php echo '<input type="hidden" name="tabIdACC" value="'.implode('-',$tabIdACC).'"/> ';?>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                <input type="date" name="datePaiement"  >
                                                                                <span class="text-danger" ></span>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                                <select name="comptePaiement" id="" >
                                                                                        <?php
                                                                                            $sqlv="select * from `aaa-compte` where `typeCompte`=2 OR `typeCompte`=5 and `montantCompte` >= $somme1 ";
                                                                                            //var_dump( $sqlv);
                                                                                            //echo $sqlv ;*/
                                                                                            $resv=mysql_query($sqlv);
                                                                                            while($operation =mysql_fetch_assoc($resv)){
                                                                                            echo '<option value="'.$operation["idCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                            }
                                                                                        ?>
                                                                                </select>
                                                                                <span class="text-danger" ></span>
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
                                                        <div class="modal fade" <?php echo  "id=Desactiver".$utilisateur["idutilisateur"] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                                            <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($accompagnateur['accompagnateur'])."" ; ?> >
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
                                                        <div class="modal fade" <?php echo  "id=detail".$utilisateur["idutilisateur"] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Boutique non payement</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                            
                                                                            <?php
                                                                                $sql7="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$utilisateur["matricule"]."' and aSalaireAccompagnateur='0' and aPayementBoutique=0 " ;
                                                                                $res7 = mysql_query($sql7) or die ("etape requête 4".mysql_error());
                                                                                $tabIdACC[]=null;
                                                                                while($payement=mysql_fetch_array($res7)) { 

                                                                                    $sql8="SELECT * FROM `aaa-boutique` where idBoutique='".$payement["idBoutique"]."'";
                                                                                    $res8 = mysql_query($sql8) or die ("personel requête 2".mysql_error());
                                                                                    $boutique = mysql_fetch_array($res8);

                                                                                    ?>
                                                                                   <div> 
                                                                                        <span><b><?php echo $boutique['labelB']; ?></b></span>
                                                                                        <span><b><?php echo $payement['datePS']; ?></b></span>
                                                                                        <span>Pas encore</span>
                                                                                   </div>
                                                                                   <hr>
                                                                            <?php } ?>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php unset($tabIdACC);   ?>
                                                </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="LISTEING">
                                <table id="exemple2" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>INGENIEUR</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>INGENIEUR</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            // Ingen=5                         
                                            $req1 = $bdd->prepare("SELECT * FROM `aaa-contrat` WHERE dateDebut<=:debut and dateFin>=:fin and idPersonnel IS NOT NULL");
                                            $req1->execute(array('debut' =>$date->format('Y-m-d'),
                                                                'fin' =>$date->format('Y-m-d')))  or die(print_r($req1->errorInfo()));   
                                            
                                            while ($contrat=$req1->fetch()) { 
                                                $req2 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` WHERE idContrat=:idC and (dateCalcul like :anneeMois or dateCalcul like :moisAnnee)");                                                
                                                $req2->execute(array('idC' =>$contrat['idContrat'],
                                                                    'anneeMois' =>'%'.$annee."-".$mois.'%',
                                                                    'moisAnnee' =>'%'.$mois."-".$annee.'%')) or die(print_r($req2->errorInfo()));         
                                                while($salaire=$req2->fetch()) {  ?>                                                
                                                    <tr>
                                                        <td> <?php
                                                        $sql9="SELECT * FROM `aaa-personnel` where idPersonnel='".$contrat["idPersonnel"]."'";
                                                        $res9 = mysql_query($sql9) or die ("utilisateur requête 2".mysql_error());
                                                        $personnel = mysql_fetch_array($res9);

                                                        echo $personnel['matriculePersonnel']; ?>  </td>
                                                        <td> <?php echo  $personnel['prenomPersonnel'];  ?>  </td>
                                                        <td> <?php echo  $personnel['nomPersonnel'];   ?>  </td>
                                                        <td> <?php echo  $contrat['montantSalaire'];  ?>  </td>
                                                        <td> <?php echo  $contrat['dateDebut'];  ?>  </td>
                                                        <td> <?php echo  $contrat['dateFin'];  ?>  </td>
                                                        <td> <?php echo  $salaire['etape']; ?> </td>
                                                        <?php 
                                                        if ($salaire['aPayer']==0) { ?>
                                                                <td><span>En cour...</span></td>
                                                                <td>
                                                                <!-- <?php /* if ($jour>=25 && $jour<=15 ) { */ ?> -->
                                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverI".$contrat['idContrat'] ; ?> >
                                                                        Virer
                                                                    </button>
                                                                <!-- <?php /* } else {  */?>
                                                                    <p>..</p>
                                                                <?php /* } */  ?> -->
                                                                    
                                                                </td>
                                                                <?php
                                                            } else { ?>
                                                                <td><span>Effectif</span></td>
                                                                <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$contrat['idContrat'] ; ?> >
                                                                Annuler</button></td>
                                                            <?php }
                                                            ?>
                                                        <div class="modal fade" <?php echo  "id=ActiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form  method="post" action="salaireAccompagnateurs.php">
                                                                        <div class="form-group">
                                                                            <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                            <p>Montant du virement : <font color="red"><?php echo $contrat['montantSalaire']; ?> FCFA</font></p>
                                                                            <p>Numéro du récepteur : <font color="red"> <?php echo  $personnel['telPersonnel'];  ?></font></p>
                                                                            <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                            <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                            <p>Etat du virement : <font color="red">En Préparation</font></p>
                                                                            <input type="hidden" name="idSP" <?php echo  "value=". $salaire['idSP']."" ; ?> >
                                                                            <input type="hidden" name="montant" <?php echo  "value=". $salaire['salaireDeBase']."" ; ?> >
                                                                            <input type="hidden" name="matriculePersonnel" <?php echo  "value=". $personnel['matriculePersonnel']."" ; ?> >
                                                                            <input type="hidden" name="telephone" <?php echo  "value=". $personnel['telPersonnel']."" ; ?> >
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                <input type="date" name="datePaiement"  >
                                                                                <span class="text-danger" ></span>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                                <select name="comptePaiement" id="" >
                                                                                                        <?php
                                                                                                            //$sqlv="select * from `aaa-compte` ";
                                                                                                            $sqlv="select * from `aaa-compte` where (`typeCompte`=2 OR `typeCompte`=5) and `montantCompte` >= '".$contrat['montantSalaire']."' ";
                                                                                            
                                                                                                            $resv=mysql_query($sqlv);
                                                                                                            while($operation =mysql_fetch_assoc($resv)){
                                                                                                            echo '<option value="'.$operation["idCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                                            }
                                                                                                        ?>

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
                                                        <div class="modal fade" <?php echo  "id=DesactiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                                            <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($accompagnateur['accompagnateur'])."" ; ?> >
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
                                                    </tr><?php 
                                                }
                                                $req2->closeCursor(); 
                                            }
                                            $req1->closeCursor();
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="LISTEEDICATA">
                                <table id="exemple3" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>Editeur catalogue</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Editeur catalogue</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $req8 = $bdd->prepare("SELECT * FROM `aaa-contrat` a 
                                                                        INNER JOIN `aaa-utilisateur` u ON a.idUtilisateur = u.idUtilisateur 
                                                                        WHERE (u.profil=:p and dateFin >=:d)");
                                            $req8->execute(array('p' =>'Editeur catalogue',
                                                                'd' =>$date->format('Y-m-d')))  or die(print_r($req8->errorInfo()));   
                                        while($contrat=$req8->fetch()) { 
                                        ?>
                                                <tr>
                                                    <td> <?php

                                                    $req9 = $bdd->prepare("SELECT * FROM `aaa-utilisateur` where idUtilisateur=:u");
                                                    $req9->execute(array('u' =>$contrat["idUtilisateur"]))  or die(print_r($req9->errorInfo())); 
                                                    $utilisateur=$req9->fetch();

                                                    echo $utilisateur['matricule']; ?>  </td>
                                                    <td> <?php echo  $utilisateur['prenom'];  ?>  </td>
                                                    <td> <?php echo  $utilisateur['nom'];   ?>  </td>
                                                    <td> <?php echo  $contrat['montantSalaire'];  ?>  </td>
                                                    <td> <?php echo  $contrat['dateDebut'];  ?>  </td>
                                                    <td> <?php echo  $contrat['dateFin'];  ?>  </td>
                                                    <td> <?php 
                                                            $dateD= new DateTime("".$contrat['dateDebut']."");
                                                            $dateF= new DateTime("".$annee.'-'.$mois.'-'.$jour."");
                                                            $intvl = $dateD->diff($dateF);
                                                            echo  $intvl->m;  
                                                    //var_dump($dateD);
                                                    //var_dump($dateF);
                                                        ?>
                                                    </td>
                                                    <?php 
                                                    $req10 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` WHERE idContrat=:idC and (dateCalcul like :anneeMois or dateCalcul like :moisAnnee)");                                                
                                                    $req10->execute(array('idC' =>$contrat['idContrat'],
                                                                        'anneeMois' =>'%'.$annee."-".$mois.'%',
                                                                        'moisAnnee' =>'%'.$mois."-".$annee.'%')) or die(print_r($req10->errorInfo()));                                                            
                                                    $salaire=$req10->fetch();

                                                    if ($salaire['aPayer']==0) { ?>
                                                            <td><span>En cour...</span></td>
                                                            <td>
                                                                
                                                                <?php /* if ($jour>=25 && $jour<=15 ) { */ ?>
                                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverI".$contrat['idContrat'] ; ?> >
                                                                        Virer
                                                                    </button>
                                                                 <!-- <?php /* } else { */ ?>
                                                                    <p>..</p>
                                                                <?php /* }  */ ?>  -->
                                                            </td>
                                                            <?php
                                                        } else { ?>
                                                            <td><span>Effectif</span></td>
                                                            <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$contrat['idContrat'] ; ?> >
                                                            Annuler</button></td>
                                                        <?php }
                                                         ?>
                                                    <div class="modal fade" <?php echo  "id=ActiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form  method="post" action="salaireAccompagnateurs.php">
                                                                      <div class="form-group">
                                                                         <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                         <p>Montant du virement : <font color="red"><?php echo $contrat['montantSalaire']; ?> FCFA</font></p>
                                                                         <p>Numéro du payeur : <font color="red">775243594</font></p>
                                                                         <p>Numéro du récepteur : <font color="red"> <?php echo  $utilisateur['telPortable'];  ?></font></p>
                                                                         <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                         <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                         <p>Etat du virement : <font color="red">En Préparation</font></p>


                                                                         <input type="hidden" name="idSP" <?php echo  "value=". $salaire['idSP']."" ; ?> >
                                                                         <input type="hidden" name="telephone" <?php echo  "value=".  $utilisateur['telPortable']."" ; ?> >
                                                                         <input type="hidden" name="mat" <?php echo  "value=".  $utilisateur['matricule']."" ; ?> >
                                                                         
                                                                          <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                              <input type="date" name="datePaiement"  >
                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                        <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                             <select name="comptePaiement" id="" >
                                                                                                    <?php
                                                                                                        //$sqlv="select * from `aaa-compte` ";
                                                                                                        $sqlv="select * from `aaa-compte` where (`typeCompte`=2 OR `typeCompte`=5) and `montantCompte` >= '".$contrat['montantSalaire']."' ";
                                                                                        
                                                                                                        $resv=mysql_query($sqlv);
                                                                                                        while($operation =mysql_fetch_assoc($resv)){
                                                                                                           echo '<option value="'.$operation["idCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                                        }
                                                                                                    ?>

                                                                                                </select>
                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnVirerNoIng" class="btn btn-primary">Effectuer le virement</button>
                                                                       </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" <?php echo  "id=DesactiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                                         <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($accompagnateur['accompagnateur'])."" ; ?> >
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
                                                    <!----------------------------------------------------------->
                                                    <!----------------------------------------------------------->
                                                    <!----------------------------------------------------------->
                                                </tr>
                                            <?php
                                             }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="LISTEASSIST">
                                <table id="exemple4" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>ASSISTANT</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ASSISTANT</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
                                            <th>Date Debut</th>
                                            <th>Date Fin</th>
                                            <th>Etape</th>
                                            <th>Virement</th>
                                            <th>Opération</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $req8 = $bdd->prepare("SELECT * FROM `aaa-contrat` a 
                                                                        INNER JOIN `aaa-utilisateur` u ON a.idUtilisateur = u.idUtilisateur 
                                                                        WHERE (u.profil=:p and dateFin >=:d)");
                                            $req8->execute(array('p' =>'Assistant',
                                                                'd' =>$date->format('Y-m-d')))  or die(print_r($req8->errorInfo()));   
                                        while($contrat=$req8->fetch()) { 
                                        ?>
                                                <tr>
                                                    <td> <?php

                                                    $req9 = $bdd->prepare("SELECT * FROM `aaa-utilisateur` where idUtilisateur=:u");
                                                    $req9->execute(array('u' =>$contrat["idUtilisateur"]))  or die(print_r($req9->errorInfo())); 
                                                    $utilisateur=$req9->fetch();

                                                    echo $utilisateur['matricule']; ?>  </td>
                                                    <td> <?php echo  $utilisateur['prenom'];  ?>  </td>
                                                    <td> <?php echo  $utilisateur['nom'];   ?>  </td>
                                                    <td> <?php echo  $contrat['montantSalaire'];  ?>  </td>
                                                    <td> <?php echo  $contrat['dateDebut'];  ?>  </td>
                                                    <td> <?php echo  $contrat['dateFin'];  ?>  </td>
                                                    <td> <?php 
                                                            $dateD= new DateTime("".$contrat['dateDebut']."");
                                                            $dateF= new DateTime("".$annee.'-'.$mois.'-'.$jour."");
                                                            $intvl = $dateD->diff($dateF);
                                                            echo  $intvl->m;  
                                                    //var_dump($dateD);
                                                    //var_dump($dateF);
                                                        ?>
                                                    </td>
                                                    <?php 
                                                    $req10 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` WHERE idContrat=:idC and (dateCalcul like :anneeMois or dateCalcul like :moisAnnee)");                                                
                                                    $req10->execute(array('idC' =>$contrat['idContrat'],
                                                                        'anneeMois' =>'%'.$annee."-".$mois.'%',
                                                                        'moisAnnee' =>'%'.$mois."-".$annee.'%')) or die(print_r($req10->errorInfo()));                                                            
                                                    $salaire=$req10->fetch();

                                                    if ($salaire['aPayer']==0) { ?>
                                                            <td><span>En cour...</span></td>
                                                            <td>
                                                                
                                                                <?php /* if ($jour>=25 && $jour<=15 ) { */ ?>
                                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverI".$contrat['idContrat'] ; ?> >
                                                                        Virer
                                                                    </button>
                                                                 <!-- <?php /* } else { */ ?>
                                                                    <p>..</p>
                                                                <?php /* }  */ ?>  -->
                                                            </td>
                                                            <?php
                                                        } else { ?>
                                                            <td><span>Effectif</span></td>
                                                            <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$contrat['idContrat'] ; ?> >
                                                            Annuler</button></td>
                                                        <?php }
                                                         ?>
                                                    <div class="modal fade" <?php echo  "id=ActiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form  method="post" action="salaireAccompagnateurs.php">
                                                                      <div class="form-group">
                                                                         <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                         <p>Montant du virement : <font color="red"><?php echo $contrat['montantSalaire']; ?> FCFA</font></p>
                                                                         <p>Numéro du payeur : <font color="red">775243594</font></p>
                                                                         <p>Numéro du récepteur : <font color="red"> <?php echo  $utilisateur['telPortable'];  ?></font></p>
                                                                         <p>Date de virement : <font color="red"><?php echo $dateString2 ; ?></font></p>
                                                                         <p>Heure de virement : <font color="red"><?php echo $heureString ; ?></font></p>
                                                                         <p>Etat du virement : <font color="red">En Préparation</font></p>


                                                                         <input type="hidden" name="idSP" <?php echo  "value=". $salaire['idSP']."" ; ?> >
                                                                         <input type="hidden" name="telephone" <?php echo  "value=".  $utilisateur['telPortable']."" ; ?> >
                                                                         <input type="hidden" name="mat" <?php echo  "value=".  $utilisateur['matricule']."" ; ?> >
                                                                         
                                                                          <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                              <input type="date" name="datePaiement"  >
                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                        <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                             <select name="comptePaiement" id="" >
                                                                                                    <?php
                                                                                                        //$sqlv="select * from `aaa-compte` ";
                                                                                                        $sqlv="select * from `aaa-compte` where (`typeCompte`=2 OR `typeCompte`=5) and `montantCompte` >= '".$contrat['montantSalaire']."' ";
                                                                                        
                                                                                                        $resv=mysql_query($sqlv);
                                                                                                        while($operation =mysql_fetch_assoc($resv)){
                                                                                                           echo '<option value="'.$operation["idCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                                        }
                                                                                                    ?>

                                                                                                </select>
                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnVirerNoIng" class="btn btn-primary">Effectuer le virement</button>
                                                                       </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" <?php echo  "id=DesactiverI".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                                         <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($accompagnateur['accompagnateur'])."" ; ?> >
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
                                                    <!----------------------------------------------------------->
                                                    <!----------------------------------------------------------->
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
                  </div>
				</div>
			
		</div>
        	<?php
}else{

            $matricule=@$_POST["matricule"];
                     ?>
        <div class="row">
			
			<div class="">
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                      <div class="container" align="center"> <br/>

                            <?php
                                $somme=0;
                                $sql1="SELECT DISTINCT idBoutique FROM `aaa-payement-salaire` where accompagnateur='".$matricule."'" ;
                                $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                                while($boutiqueP=mysql_fetch_array($res1)) {
                                    $sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                                    $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                                    while($payement=mysql_fetch_array($res4)) {
                                        $somme=$somme+$payement['partAccompagnateur'];

                                    }
                                }
                            ?>

                            <div class="jumbotron noImpr">

                                <h2>Aujourd'hui : <?php echo $dateString2; ?></h2>

                                <p>Salaire du mois en cours : <font color="red"><?php echo $somme; ?> FCFA</font></p>

                            </div>
                                <ul class="nav nav-tabs">
                                      <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">DETAIL DU SALAIRE DE L'ACCOMPAGNATEUR <?php echo $matricule; ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                                            <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                                <thead>
                                                    <tr>
                                                        <th>Accompagnateur</th>
                                                        <th>Boutique</th>
                                                        <th>Etape Accompagnement</th>
                                                        <th>Part Accompagnateur</th>
                                                        <th>Date Calcul</th>
                                                        <th>Virement</th>
                                                        <th>Virement/Annulation</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Accompagnateur</th>
                                                        <th>Boutique</th>
                                                        <th>Etape Accompagnement</th>
                                                        <th>Part Accompagnateur</th>
                                                        <th>Date Calcul</th>
                                                        <th>Virement</th>
                                                        <th>Virement/Annulation</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php
                                                    $somme=0;
                                                    $sql1="SELECT DISTINCT idBoutique FROM `aaa-payement-salaire` where accompagnateur='".$matricule."'" ;
                                                    $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                                                    while($boutiqueP=mysql_fetch_array($res1)) {
                                                    $sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                                                    $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                                                    while($payement=mysql_fetch_array($res4)) {
                                                        $somme=$somme+$payement['partAccompagnateur'];
                                                ?>
                                                            <tr>

                                                                <td> <b><?php echo  $payement['accompagnateur'];  ?></b>  </td>
                                                                <td> 
                                                                    <?php $sql3="SELECT * FROM `aaa-boutique` where idboutique=".$payement['idBoutique']."";
                                                                    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
                                                                    $bout = mysql_fetch_array($res3);
                                                                            echo  $bout['nomBoutique']; 
                                                                    ?>  
                                                                </td>
                                                                <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                                                <td> <b><?php echo  $payement['partAccompagnateur']; ?> FCFA  </b> </td>
                                                                <td> <?php echo  $payement['datePS']; ?>  </td>



                                                                <?php

                                                                     if ($payement['aSalaireAccompagnateur']==0) { ?>
                                                                        <td><span>En cour...</span></td>
                                                                        <td>
                                                                            <?php if ($payement['aPayementBoutique']==0) { ?> 
                                                                                <p>Non paiement boutique </p>   
                                                                            <?php } else { ?>
                                                                                <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$payement['idBoutique']  ; ?> >
                                                                                Virer</button>
                                                                            <?php } ?>

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
                                                            <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
                                                              <div class="form-group">
                                                                 <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                 <input type="hidden" name="idBoutique" <?php echo  "value=". $payement['idBoutique']."" ; ?> >
                                                              </div>
                                                              <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                    <button type="submit" name="btnActiver" class="btn btn-primary">Effectuer le virement</button>
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
                                                            <h4 class="modal-title" id="myModalLabel">Annulation</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
                                                              <div class="form-group">
                                                                 <h2>Voulez vous vraiment annuler le virement</h2>
                                                                 <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($payement['idBoutique'])."" ; ?> >
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
                                <!----------------------------------------------------------->
                                <!----------------------------------------------------------->
                                <!----------------------------------------------------------->


                                                            </tr>
                                                        <?php
                                                         }
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



	   <?php }
							?>
</body>
</html>