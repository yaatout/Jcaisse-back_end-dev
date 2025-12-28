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

	
	
	$sql2="SELECT * FROM `aaa-boutique` WHERE enTest =1 ";
	$res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
	while ($boutique = mysql_fetch_array($res2)) { 
		$sql3="SELECT * FROM `aaa-variablespayement` where typecaisse='".$boutique["type"]."' and categoriecaisse='".$boutique["categorie"]."'";
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
        
        $partAccompagnateur=0;
		
		if($variable = @mysql_fetch_array($res3)){
             
		//$sql3="SELECT * FROM `aaa-payement-salaire` where (datePS='".$dateString."' or datePS='".$dateString2."')  and idBoutique=".$boutique["idBoutique"];
        $sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) and idBoutique=".$boutique["idBoutique"];
		$res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
            // var_dump($sql3);
		if(@mysql_num_rows($res3)){    
            if($variable["activerMontant"]==1){
                if($etapeAccompagnement==1){
                        $partAccompagnateur=$variable["montant"]*50/100;
                    }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                        $partAccompagnateur=$variable["montant"]*20/100;
                    }
                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=1,etapeAccompagnement=".$etapeAccompagnement.",partAccompagnateur=".$partAccompagnateur." where idBoutique=".$boutique["idBoutique"];
                
                    var_dump($sql6);
                }
                else if($variable["activerPourcentage"]==1){
                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=2,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"];
                     var_dump($sql6); }
                else if($variable["activerPrix"]==1){
                    $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateString."',montantFixePayement=".$variable["montant"].",pourcentagePayement=".$variable["pourcentage"].", 	prixlignesPayement=".$variable["prixLigne"].",minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=3,etapeAccompagnement=".$etapeAccompagnement." where idBoutique=".$boutique["idBoutique"];
                    var_dump($sql6);
                  //echo $sql6.'</br>';
                }
                  $res6=mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
		}
	}
		
	}
}


if (isset($_POST['btnVirer'])) {
    
	$idUtilisateur=$_POST['idU'];
	$nom=$_POST['nom'];
	$prenom=$_POST['prenom'];
	$profil=$_POST['profil'];
    $telephone=$_POST['telephone'];
	$montant=$_POST['montant'];
	$datePaiement=$_POST['datePaiement'];
	$comptePaiement=$_POST['comptePaiement'];
	$matricule=$_POST['mat'];
	
	 
	//$sql3="INSERT INTO `aaa-salaire-personnel` ( `idUtilisateur`, `nom`, `prenom`, `profil`, `telephone`, `etape`, `montant`, `datePaiement`, `comptePaiement`) VALUES ('', '', '', '', '', '', '', '', '')";
	
	/**********************************TABLE COMPTE *****************************************/
                          $sqlv="select * from `aaa-compte` where nomCompte='".$comptePaiement."'";
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          if($compte['montantCompte']>=$montant){
                              /********************************DEBUT SALAIRE PERSONNEL**************************************/
                              
                              $sql3="INSERT INTO `aaa-salaire-personnel` ( `idUtilisateur`, `nom`, `prenom`, `profil`, `telephone`, `montant`, `datePaiement`, `comptePaiement`) VALUES ('".$idUtilisateur."', '".$nom."', '".$prenom."', '".$profil."', '".$telephone."', '".$montant."', '".$datePaiement."', '".$comptePaiement."')";
	                           $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
                              
                              /******************************** FINSALAIRE PERSONNEL****************************************/
                              $operation='transfert';
                              $idCompte=$compte['idCompte'];
                              $compteDonateur='';
                              $description='Paiement salaire :'.$matricule;
                              $newMontant=$compte['montantCompte']-$montant;

                            $sql8="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser) values('".$montant."','".$operation."','".$idCompte."','".$telephone."','".$compteDonateur."','".$nom."','".$dateHeures."','".$datePaiement."','".$description."','".$_SESSION['iduserBack']."')";
                              $res8=mysql_query($sql8) or die ("insertion Cmpte impossible =>".mysql_error() );
                              var_dump($res8);
                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";                       
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
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
    $tabIdACC =explode('-',$tabIdACC);
    $tabIdACC =array_filter($tabIdACC);
	//var_dump($tabIdACC);
	 //die($tabIdACC);
	  
	/**********************************TABLE COMPTE ******************************************/
                          $sqlv="select * from `aaa-compte` where nomCompte='".$comptePaiement."'";
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          if($compte['montantCompte']>=$montant){
                             
                              /***********************************************************************************/
                               foreach($tabIdACC as $idPS){
                                     $sql7="UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur=1 where  idPS=".$idPS."";  
                                     //var_dump($sql7);
                                     $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas 1 ".mysql_error());
                                 }
                              /***********************************************************************************/
                              $operation='transfert';
                              $idCompte=$compte['idCompte'];
                              $compteDonateur='';
                              $description='Paiement salaire :'.$matricule;
                              $newMontant=$compte['montantCompte']-$montant;

                            $sql8="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser) values('".$montant."','".$operation."','".$idCompte."','".$telephone."','".$compteDonateur."','".$nom."','".$dateHeures."','".$datePaiement."','".$description."','".$_SESSION['iduserBack']."')";
                              $res8=mysql_query($sql8) or die ("insertion Cmpte impossible =>".mysql_error() );
                              //var_dump($res8);
                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";                       
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
							$sql4="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$accompagnateur["accompagnateur"]."' and aPayementBoutique=1 and aSalaireAccompagnateur=0 order by datePS DESC LIMIT 1" ;
							$res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
							while($payement=mysql_fetch_array($res4)) {
							    $somme=$somme+$payement['partAccompagnateur'];
                                }}
                            
                            $somme2=0;
							$sql12="SELECT DISTINCT accompagnateur FROM `aaa-payement-salaire`" ;
                            $res12 = mysql_query($sql12) or die ("etape requête 4".mysql_error());
							while($accompagnateur2=mysql_fetch_array($res12)) {
							$sql42="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$accompagnateur["accompagnateur"]."' and aPayementBoutique=1 and aSalaireAccompagnateur=1 order by datePS DESC LIMIT 1" ;
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
                            if($_SESSION['profil']=="SuperAdmin"){ ?>
                                <center>
                                <div class="modal-body">
                                    <form name="formulairePayementSalaire" method="post" action="#">
                                      <div>
                                        <button type="submit" name="virementSalaire" class="btn btn-success"> Virement des Salaires </button>
                                       </div>
                                    </form>
                                </div>
                                </center>
                               <?php
                            }  ?>
                        </div>

                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PARTS DES ACCOMPAGNATEURS</a></li>
                          <li ><a data-toggle="tab" href="#LISTEING">INGENIEURS</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                                <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>Accompagnateur</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Part (FCFA)</th>
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
                                            <th>Part (FCFA)</th>
                                            <th>Date Calcul</th>
                                            <th>Virement</th>
                                            <th>Virement/Annulation</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $sql1="SELECT DISTINCT accompagnateur FROM `aaa-payement-salaire`  " ;
                                        $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                                        while($accompagnateur=mysql_fetch_array($res1)) {
                                           
                                        ?>
                                                <tr>
                                                    <td> <b><?php echo  $accompagnateur["accompagnateur"];  ?></b>  </td>
                                                    <td> <?php
                                                    $sql2="SELECT * FROM `aaa-utilisateur` where matricule='".$accompagnateur["accompagnateur"]."' ";
                                                    $res2 = mysql_query($sql2) or die ("utilisateur requête 2".mysql_error());
                                                    $utilisateur = mysql_fetch_array($res2);

                                                    echo $utilisateur['prenom']; ?>  </td>
                                                    <td> <?php echo  $utilisateur['nom'];  ?>  </td>
                                                    <td> <b>
                                                    <?php
                                                    $sql4="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$accompagnateur["accompagnateur"]."' and aSalaireAccompagnateur='0' and aPayementBoutique=1 " ;
                                                    $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                                                    $somme1=0;
                                                    $tabIdACC[]=0;
                                                    //var_dump($tabIdACC);
                                                    while($payement=mysql_fetch_array($res4)) {
                                                        if($tabIdACC[0]==0){
                                                            $tabIdACC[]=NULL;
                                                        }
                                                         $somme1=$somme1+$payement['partAccompagnateur'];
                                                         $tabIdACC[] =$payement['idPS'];
                                                        //var_dump("kkk".$payement['idPS']);
                                                    }
                                                    echo  $somme1; ?> FCFA  </b> </td>
                                                    <td> <?php

                                                    $sql4="SELECT * FROM `aaa-payement-salaire` WHERE accompagnateur ='".$accompagnateur["accompagnateur"]."' and aPayementBoutique=1 order by datePS DESC LIMIT 1" ;
                                                    $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                                                    $complementAccompagnateur=mysql_fetch_array($res4);

                                                    echo  $complementAccompagnateur['datePS']; ?>  </td>

                                                    <?php
                                                         if ($complementAccompagnateur['aSalaireAccompagnateur']==0) { ?>
                                                            <td><span>En cour...</span></td>
                                                            <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$accompagnateur['accompagnateur'] ; ?> >
                                                            Virer</button>
                                                            </td>
                                                            <?php
                                                        } else { ?>
                                                            <td><span>Effectif</span></td>
                                                            <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$accompagnateur['accompagnateur'] ; ?> >
                                                            Annuler</button></td>
                                                        <?php }
                                                         ?>
                                                    <div class="modal fade" <?php echo  "id=Activer".$accompagnateur['accompagnateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
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
                                                                                        $sqlv="select * from `aaa-compte` ";
                                                                                        $resv=mysql_query($sqlv);
                                                                                        while($operation =mysql_fetch_assoc($resv)){
                                                                                           echo '<option value="'.$operation["nomCompte"].'">'.$operation["nomCompte"].'</option>';
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
                                                    <div class="modal fade" <?php echo  "id=Desactiver".$accompagnateur['accompagnateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                            <th>Virement/Annulation</th>
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
                                            <th>Virement/Annulation</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $sql8="SELECT * FROM `aaa-contrat` where profil='Ingenieur' and dateFin >= '". $date->format('Y-m-d')."'" ;
                                        
                                        $res8 = mysql_query($sql8) or die ("etape requête 41".mysql_error());
                                        while($contrat=mysql_fetch_array($res8)) { 
                                        ?>
                                                <tr>
                                                    <td> <?php
                                                    $sql9="SELECT * FROM `aaa-utilisateur` where idUtilisateur='".$contrat["idUtilisateur"]."'";
                                                    $res9 = mysql_query($sql9) or die ("utilisateur requête 2".mysql_error());
                                                    $utilisateur = mysql_fetch_array($res9);

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
                                                    $sql10="SELECT * FROM `aaa-salaire-personnel` WHERE idUtilisateur='".$contrat["idUtilisateur"]."' and datePaiement LIKE '%".$annee."-".$mois."%' " ;
                                                    $res10 = mysql_query($sql10) or die ("etape requête 4".mysql_error());
                                                    $salairePersonnel=mysql_fetch_array($res10);
                                                    
                                                    if (!$salairePersonnel) { ?>
                                                            <td><span>En cour...</span></td>
                                                            <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$contrat['idContrat'] ; ?> >
                                                            Virer</button>
                                                            </td>
                                                            <?php
                                                        } else { ?>
                                                            <td><span>Effectif</span></td>
                                                            <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$contrat['idContrat'] ; ?> >
                                                            Annuler</button></td>
                                                        <?php }
                                                         ?>
                                                    <div class="modal fade" <?php echo  "id=Activer".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
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
                                                                         <input type="hidden" name="idU" <?php echo  "value=". $contrat['idUtilisateur']."" ; ?> >
                                                                         <input type="hidden" name="nom" <?php echo  "value=". $utilisateur['prenom']."" ; ?> >
                                                                         <input type="hidden" name="prenom" <?php echo  "value=". $utilisateur['nom']."" ; ?> >
                                                                         <input type="hidden" name="profil" <?php echo  "value=". $utilisateur['profil']."" ; ?> >
                                                                         <input type="hidden" name="telephone" <?php echo  "value=".  $utilisateur['telPortable']."" ; ?> >
                                                                         <input type="hidden" name="matricule" <?php echo  "value=".  $utilisateur['matricule']."" ; ?> >
                                                                         
                                                                          <!--<input type="hidden" name="etape" <?php echo  "value=". $accompagnateur['accompagnateur']."" ; ?> >-->
                                                                         <input type="hidden" name="montant" <?php echo  "value=". $contrat['montantSalaire']."" ; ?> >
                                                                         
                                                                          <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                              <input type="date" name="datePaiement"  >
                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                        <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Paiement par :<font color="red">*</font></label>
                                                                             <select name="comptePaiement" id="" >
                                                                                                    <?php
                                                                                                        $sqlv="select * from `aaa-compte` ";
                                                                                                        $resv=mysql_query($sqlv);
                                                                                                        while($operation =mysql_fetch_assoc($resv)){
                                                                                                           echo '<option value="'.$operation["nomCompte"].'">'.$operation["nomCompte"].'</option>';
                                                                                                        }
                                                                                                    ?>

                                                                                                </select>
                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnVirer" class="btn btn-primary">Effectuer le virement</button>
                                                                       </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" <?php echo  "id=Desactiver".$contrat['idContrat'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                                <td> <?php echo  $payement['idBoutique']; ?>  </td>
                                                                <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                                                <td> <b><?php echo  $payement['partAccompagnateur']; ?> FCFA  </b> </td>
                                                                <td> <?php echo  $payement['datePS']; ?>  </td>



                                                                <?php

                                                                     if ($payement['aSalaireAccompagnateur']==0) { ?>
                                                                        <td><span>En cour...</span></td>
                                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$payement['idBoutique'] ; ?> >
                                                                        Virer</button>
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