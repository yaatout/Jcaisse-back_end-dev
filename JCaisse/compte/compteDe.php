<?php
session_start();

date_default_timezone_set('Africa/Dakar');

require('../connection.php');
require('../declarationVariables.php');
/**********************/
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
$dateHeures=$dateString.' '.$heureString;
$messageDel='';

// var_dump($_SESSION['nomB']);
// $nomtableCompte = $_SESSION['nomB'].'-compte';
/***Debut compte qui reçoit paiement ***/
$sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";
$resPay = mysql_query($sqlGetComptePay) or die ("compte  request ".mysql_error());

$compteTransaction=array();
$compteBancaire=array();
$compteType=array();

$sqlv="select * from `aaa-transaction` where typeTransaction = 'Transaction' ORDER BY nomTransaction ASC";
$resv=mysql_query($sqlv);
while($operation =mysql_fetch_assoc($resv)){
    $compteTransaction[]=$operation["nomTransaction"];
}
$sqlb="select * from `aaa-banque` ORDER BY nom";
$resb=mysql_query($sqlb);
while($b =mysql_fetch_assoc($resb)){
    $compteBancaire[]=$b["nom"];
}
// var_dump($compteBancaire);
$sqlt="select * from `".$nomtableComptetype."` ORDER BY nomType ASC ";
$rest=mysql_query($sqlt);
while($t =mysql_fetch_assoc($rest)){
    $compteType[]=$t["nomType"];
}
// if(!$_SESSION['iduserBack']){
// 	header('Location:index.php');
// }
// if($_SESSION['profil']!="SuperAdmin")
//     header('Location:accueil.php');

if (isset($_POST['btnEnregistrerMouvement'])) {
        
		$montant=htmlspecialchars(trim($_POST['montant']));
		$idCompteDest='';
        if(isset ($_POST['compteDest'])){
            $idCompteDest=htmlspecialchars(trim($_POST['compteDest']));
        }
		$avecFrais= '';
        if(isset ($_POST['avecFrais'])){
            $avecFrais=htmlspecialchars(trim($_POST['avecFrais']));
        }
		$montantFrais= 0;
        if(isset ($_POST['montantFrais'])){
            $montantFrais=htmlspecialchars(trim($_POST['montantFrais']));
        }
        $numeroDestinataire='';
            if(isset ($_POST['numeroDestinataire'])){
            $numeroDestinataire=htmlspecialchars(trim($_POST['numeroDestinataire']));
            }
        $compteDonateur='';
            if(isset ($_POST['compteDonateur'])){
              $compteDonateur=htmlspecialchars(trim($_POST['compteDonateur']));
            }
        $nomClient='';
            if(isset ($_POST['nomClient'])){
              $nomClient=htmlspecialchars(trim($_POST['nomClient']));
            }
        $dateEcheance='2021-01-01';
        if(isset ($_POST['dateEcheance'])){
		$dateEcheance=htmlspecialchars(trim($_POST['dateEcheance']));
        }
		$operation=trim($_POST['operation']);
		$idCompte=trim($_POST['compte']);
		$description=htmlspecialchars(trim($_POST['description']));
		$dateSaisie=$dateHeures;
            if(isset ($_POST['dateSaisie'])){
              $dateSaisie=htmlspecialchars(trim($_POST['dateSaisie']));
            }

          $newMontant=0;
		
          //var_dump($operation);
          $sqlv="select * from `".$nomtableCompte."` where idCompte=".$_GET['c']."";
          $resv=mysql_query($sqlv);
          $compte =mysql_fetch_assoc($resv);
    
        if($operation=='versement' OR $operation=='depot' OR $operation=='pret'){
          $newMontant=$compte['montantCompte']+$montant;
        // var_dump(1);
          $sql1="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('".$montant."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduser']."')";
          $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
        
          $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
	      $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
            
        }else if($operation=='transfert'){
            
            if($compte['montantCompte']>=$montant){
                
                if ($idCompteDest != '') {
                    
                    $newMontant=$compte['montantCompte']-$montant-$montantFrais;
                    $sql1="insert into `".$nomtableComptemouvement."` (montant,frais,operation,idCompte,compteDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('".$montant."','".$montantFrais."','".$operation."',".$idCompte.",".$idCompteDest.",'".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduser']."')";
                    $res1=mysql_query($sql1) or die ("insertion transfert impossible =>".mysql_error() );
                    //var_dump($sql1);
                                                            
                    $sqlGetMouv="SELECT * FROM `".$nomtableComptemouvement."` ORDER BY idMouvement DESC LIMIT 1";
                    $resMv = mysql_query($sqlGetMouv) or die ("persoonel requête 2".mysql_error());
                    $mouvementLink= mysql_fetch_array($resMv);
    
                    $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
                    $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                
                    $description2="Trasfert reçu d un autre compte local";
                    $sql1_0="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,compteDonateur,dateEcheance,dateSaisie,dateOperation,mouvementLink,description,idUser) values('".$montant."','depot',".$idCompteDest.",".$idCompte.",'".$dateEcheance."','".$dateHeures."','".$dateSaisie."',".$mouvementLink['idMouvement'].",'".$description2."','".$_SESSION['iduser']."')";
                    $res1_0=mysql_query($sql1_0) or die ("insertion Cmpte impossible =>".mysql_error() );
                   
                    $sql2_0="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + '".$montant."' where  idCompte=".$idCompteDest."";
                    $res2_0=@mysql_query($sql2_0) or die ("mise à jour compte dest pour activer ou pas ".mysql_error());
                } else {

                    $newMontant=$compte['montantCompte']-$montant-$montantFrais;
                    $sql1="insert into `".$nomtableComptemouvement."` (montant,frais,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('".$montant."','".$montantFrais."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduser']."')";
                    $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
                    //var_dump($sql1);

                    $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
                    $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                }
                    
            }else{
                $messageDel='';
                echo " <script> 
                                
                        alert('Le montant que vous voulez retirer est superieur à la solde du compte');
                    </script>";
            }
              
        }else{
            // var_dump(2);

            if($compte['montantCompte']>=$montant){
                    
                $newMontant=$compte['montantCompte']-$montant;
                $sql1="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('".$montant."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduser']."')";
                $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
                //var_dump($sql1);

               $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
               $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                    
            }else{
                $messageDel='';
                echo " <script> 
                                
                        alert('Le montant que vous voulez retirer est superieur à la solde du compte');
                    </script>";
                }
        }
        
}
if (isset($_POST['btnAnnulerMouvement'])) {

		$montant=htmlspecialchars(trim($_POST['montant']));
		$frais=htmlspecialchars(trim($_POST['frais']));
		$operation=trim($_POST['operation']);
		$idCompte=trim($_POST['compte']);
		$idMouvement=trim($_POST['mouvement']);
		$compteDest=trim($_POST['compteDest']);

        $newMontant=0;
    
        //var_dump($operation);
        $sqlv="select * from `".$nomtableCompte."` where idCompte=".$_GET['c']."";
        $resv=mysql_query($sqlv);
        $compte =mysql_fetch_assoc($resv);    
        
        if($operation=='versement' OR $operation=='depot'){
            
            if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
             }else{
                     $messageDel='Désolé mais la somme disponible est inferieur au montant à rembourser';
            }
        
        }else if($operation=='remboursement'){
                
                $newMontant=$compte['montantCompte']+$montant;
                    
                $sql="UPDATE `".$nomtableComptemouvement."`` set annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);

               $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
               $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
        
        }else if($operation=='pret'){
                
                if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
             }else{
                     $messageDel='Désolé mais la somme disponible est inferieur au montant à rembourser';
            }
        
        }else if($operation=='transfert'){
                
                $newMontant=$compte['montantCompte']+$montant+$frais;
                    
                $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idMouvement=".$idMouvement."";
                $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);
                    
                $sql0="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  mouvementLink=".$idMouvement."";
                $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

                $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

                $sql2_0="UPDATE `".$nomtableCompte."` set montantCompte= montantCompte - '".$montant."' where  idCompte=".$compteDest."";
                $res2_0=@mysql_query($sql2_0) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
           
        }else{
                
            $newMontant=$compte['montantCompte']+$montant;
                
            $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idMouvement=".$idMouvement."";
            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
            //var_dump($sql1);

           $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
           $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
       
    }
        
}
if (isset($_POST['btnRetirerCheque'])) {

		$montant=htmlspecialchars(trim($_POST['montant']));
		$operation=trim($_POST['operation']);
		$idCompte=trim($_POST['compte']);
		$idMouvement=trim($_POST['mouvement']);
		$description=trim($_POST['description']);

          $newMontant=0;
		
          //var_dump($operation);
          $sqlv="select * from `".$nomtableCompte."` where idCompte=".$_GET['c']."";
          $resv=mysql_query($sqlv);
          $compte =mysql_fetch_assoc($resv);
    
        
        if($operation=='versement' OR $operation=='depot'){
            
            if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                $sql="UPDATE `".$nomtableComptemouvement."` set  retirer=1, dateRetrait='".$dateHeures."',description='".$description."' where idMouvement=".$idMouvement."";
                //echo $sql;
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
             }else{
                     $messageDel='';
            }
        
        }
        /*else{
                
                $newMontant=$compte['montantCompte']+$montant;
                    
                $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);

               $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
               $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
           
        }*/
        
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- <base href="http://localhost:81/Projects/jcaisse_save/JCaisse/"> -->
    <base href="https://jcaisse.org/JCaisse/"> 
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="compte/js/script.js"></script>
    <link rel="stylesheet" href="css/datatables.min.css">
	<script src="js/datatables.min.js"></script>
    <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JCAISSE</title>
</head>
<body>

	<?php
	   require('../header.php');
	?>
        
	 <div class="container" >
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
           <div class="jumbotron">
				
                <?php 
                     $sqlv="select * from `".$nomtableCompte."` where idCompte=".$_GET['c']."";
                      $resv=mysql_query($sqlv);
                      $compte =mysql_fetch_assoc($resv);
                 ?>
               <h2>Compte <?php echo $compte['nomCompte'].' : '.number_format(($compte['montantCompte'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'] ; ?></h2>
				<div class="panel-group">
                        <div class="panel" style="background:#cecbcb;">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">  Liste operation  </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-heading" style="margin-left:2%;">
                                    <?php                                     
                                    $sqlDV="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."`	where idCompte=".$_GET['c']." && (operation='versement' OR operation='depot' or operation='pret') && annuler!='1' ";
                                    $resDV=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
                                    $S_facture = mysql_fetch_array($resDV);
		                            $montantDV = $S_facture[0];
                                    
                                    $sqlR="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."`	where idCompte=".$_GET['c']." && (operation='retrait' or  operation='remboursement' )&&  annuler!='1'  ";
                                    $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
                                    $S_factureR = mysql_fetch_array($resR);
		                            $montantR = $S_factureR[0];
                                    
                                    $sqlVT="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."`	where idCompte=".$_GET['c']." && (operation='virement' OR operation='transfert') && annuler!='1' ";
                                    $resVT=mysql_query($sqlVT) or die ("select stock impossible =>".mysql_error());
                                    $S_factureVT = mysql_fetch_array($resVT);
		                            $montantVT = $S_factureVT[0];
                                    
                                        if($compte['typeCompte']=='compte bancaire'){
                                             echo "<h6>Montant depot : ".number_format(($montantDV * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant retrait :  ".number_format(($montantR * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant virement : ".number_format(($montantVT * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>";
                                        }elseif($compte['typeCompte']=='compte mobile'){
                                            echo "<h6>Montant depot : ".number_format(($montantDV * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant retrait : ".number_format(($montantR * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant transfert : ".number_format(($montantVT * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>";
                                        }
                                        elseif($compte['typeCompte']=='compte cheques'){
                                            echo "<h6>Montant depot : ".number_format(($montantDV * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant retrait : ".number_format(($montantR * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>";
                                        }elseif($compte['typeCompte']=='compte pret'){
                                            echo "<h6>Montant pret : ".number_format(($montantDV * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant remboursement : ".number_format(($montantR * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>";
                                        }
                                       
                                    ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div>
                    <?php  
                    // var_dump($compte['typeCompte']);
                        if(in_array($compte['typeCompte'], $compteBancaire) || in_array($compte['typeCompte'], $compteTransaction) || $compte['typeCompte']=='compte tiers' || $compte['typeCompte']=='compte epargne' || strtolower($compte['typeCompte'])=='caisse'){
                                            if(in_array($compte['typeCompte'], $compteBancaire)){  ?>
                                                 <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#versement">
                                                    <i class="glyphicon glyphicon-plus"></i>Versement
                                                </button>
                                                  <div class="modal fade" id="versement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Ajout un nouveau Versement</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulairePersonnel" method="post">
                                                                      <div class="form-group">
                                                                          <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                          <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                          <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                          <span class="text-danger" ></span>
                                                                      </div> 
                                                                      <div class="form-group">

                                                                          <input type="hidden" class="form-control"  value="versement" name="operation" >
                                                                          <span class="text-danger" ></span>
                                                                      </div>
                                                                      <div class="form-group">
                                                                          <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                          <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                          <span class="text-danger" ></span>
                                                                      </div>
                                                                        <div class="form-group">
                                                                          <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                          <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                          <span class="text-danger" ></span>
                                                                      </div>
                                                                      <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                       </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                  </div>
                                                <button type="button" class="btn btn-primary noImpr col-sm-offset-4" data-toggle="modal" data-target="#virement">
                                                            <i class="glyphicon glyphicon-plus"></i>Virement
                                                </button> 
                                                     <div class="modal fade" id="virement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Nouveau virement</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulairePersonnel" method="post">
                                                                      <div class="form-group">
                                                                          <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                          <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                          <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                          <span class="text-danger" ></span>
                                                                      </div> 
                                                                       <div class="form-group">
                                                                              <label for="inputEmail3" class="control-label">Numero compte du destinataire <font color="red">*</font></label>
                                                                              <input type="tel" class="form-control"   name="numeroDestinataire" required placeholder="Numero Compte">

                                                                              <span class="text-danger" ></span>
                                                                          </div>
                                                                      <div class="form-group">

                                                                          <input type="hidden" class="form-control"  value="virement" name="operation" >
                                                                          <span class="text-danger" ></span>
                                                                      </div>
                                                                      <div class="form-group">
                                                                          <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                          <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                          <span class="text-danger" ></span>
                                                                      </div>
                                                                        <div class="form-group">
                                                                          <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                          <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                          <span class="text-danger" ></span>
                                                                      </div>
                                                                      <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                       </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php  }
                                            else{  ?>

                                                       <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#depot">
                                                            <i class="glyphicon glyphicon-plus"></i>Depot
                                                        </button>
                                                            <div class="modal fade" id="depot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau depot</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form name="formulairePersonnel" method="post">
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                                  <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                                  <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                                  <span class="text-danger" ></span>
                                                                              </div> 
                                                                              <div class="form-group">

                                                                                  <input type="hidden" class="form-control"  value="depot" name="operation" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                  <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                                <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                                  <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                               </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                          </div>

                                                        <button type="button" class="btn btn-primary noImpr col-sm-offset-5" data-toggle="modal" data-target="#Transfert">
                                                            <i class="glyphicon glyphicon-plus"></i>Transfert
                                                        </button>
                                                        <div class="modal fade" id="Transfert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title" id="myModalLabel">Faire un transfert</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form name="formulairePersonnel" method="post">
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                                  <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                                  <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                                  <input type="hidden" class="form-control" value="transfert" name="operation" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div> 
                                                                              <div class="form-group"> 
                                                                                <div class="form-check form-check-inline  myRadio">
                                                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="entreCompte" value="option1" checked>
                                                                                    <label class="form-check-label" for="entreCompte">Entre mes comptes</label>
                                                                                    <input class="form-check-input" style="margin-left: 50px;" type="radio" name="inlineRadioOptions" id="autreCompte" value="option2">
                                                                                    <label class="form-check-label" for="autreCompte">Vers un autre compte</label>
                                                                                </div>
                                                                              </div> 
                                                                              <div class="form-group" id="divCompteDest">  
                                                                                  <label for="inputEmail3" class="control-label">Compte destinataire <font color="red">*</font></label>                                                      
                                                                                    <select class="form-control compte" id="compteDest" name="compteDest" required>
                                                                                        <!-- <option value="caisse">Caisse</option> -->
                                                                                        <?php
                                                                                    while ($cpt=mysql_fetch_array($resPay)) { ?>
                                                                                            <option value="<?= $cpt['idCompte'] ; ?>"><?= $cpt['nomCompte'] ; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                    <span class="text-danger" ></span>
                                                                              </div> 
                                                                              <div class="form-group" id="divNumDest" style="display: none;">
                                                                                  <label for="inputEmail3" class="control-label">Numero Téléphone du destinataire <font color="red">*</font></label>
                                                                                  <input type="tel" class="form-control" id="numeroDestinataire" name="numeroDestinataire" placeholder="Numero Telephone du destinataire">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>  
                                                                              <div class="form-group"> 
                                                                                <div class="form-check form-check-inline  myRadio">
                                                                                    <input class="form-check-input" style="margin-left: 50px;" type="checkbox" name="avecFrais" id="avecFrais" value="option3">
                                                                                    <label class="form-check-label" for="avecFrais">Avec frais</label>
                                                                                </div>
                                                                              </div> 
                                                                              <div class="form-group" id="divMontantFrais" style="display: none;">
                                                                                  <label for="inputEmail3" class="control-label">Montant frais <font color="red">*</font></label>
                                                                                  <input type="tel" class="form-control" id="montantFrais" name="montantFrais" placeholder="Montant frais">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                  <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                                <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                                  <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Description">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                    <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                               </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                          </div>

                                               <?php  } ?>
                                            
                                                    <button type="button" class="btn btn-danger noImpr  pull-right" data-toggle="modal" data-target="#retrait">
                                                            <i class="glyphicon glyphicon-plus"></i>Retrait
                                                    </button> 
                                                     <div class="modal fade" id="retrait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Faire un retrait</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulairePersonnel" method="post">
                                                                  <div class="form-group">
                                                                      <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                      <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                      <input type="hidden" class="form-control" name="compte" value="<?php echo  $_GET['c']; ?>">
                                                                      <span class="text-danger" ></span>
                                                                  </div> 
                                                                  <div class="form-group">
                                                                     
                                                                      <input type="hidden" class="form-control"  value="retrait" name="operation" >
                                                                      <span class="text-danger" ></span>
                                                                  </div>
                                                                  <div class="form-group">
                                                                      <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                      <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                      <span class="text-danger" ></span>
                                                                  </div>
                                                                    <div class="form-group">
                                                                      <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                      <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                      <span class="text-danger" ></span>
                                                                  </div>
                                                                  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                              </div>    
                                       <?php }elseif($compte['typeCompte']=='compte cheques'){//COMPTE CHEQUE  ?>
                                            
                                            <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#depotC">
                                                            <i class="glyphicon glyphicon-plus"></i>Depot
                                                        
                                            </button>
                                            <div class="modal fade" id="depotC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau compte</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form name="formulairePersonnel" method="post">
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                                  <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                                  <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                                  <span class="text-danger" ></span>
                                                                              </div> 
                                                                              <div class="form-group">
                                                                                  <input type="hidden" class="form-control"  value="depot" name="operation" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                                <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Banque<font color="red">*</font></label>
                                                                                    <select name="compteDonateur" >
                                                                                                            <?php
                                                                                                                
                                                                                                                $sqlv="select * from `aaa-banque` ";
                                                                                                                $resv=mysql_query($sqlv);
                                                                                                                while($operation =mysql_fetch_assoc($resv)){
                                                                                                                   echo '<option value="'.$operation["nom"].'">'.$operation["nom"].'</option>';
                                                                                                                }
                                                                                                            ?>
                                                                                                        </select>
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                               <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Nom client <font color="red">*</font></label>
                                                                                  <input type="test" min="1" class="form-control" id="inputprenom" required="" name="nomClient" >
                                                                                 
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Date echéance<font color="red">*</font></label>
                                                                                  <input type="date" class="form-control" id="inputprenom" required="" name="dateEcheance" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                                <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                                  <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                               </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                          </div>

                                        <?php }elseif($compte['typeCompte']=='compte pret'){//COMPTE CHEQUE  ?>
                                            
                                            <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#depotPret">
                                                            <i class="glyphicon glyphicon-plus"></i>Nouveau pret
                                                        
                                            </button>
                                             <div class="modal fade" id="depotPret" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau pret</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form name="formulairePersonnel" method="post">
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                                  <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                                  <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                                  <span class="text-danger" ></span>
                                                                              </div> 
                                                                              <div class="form-group">

                                                                                  <input type="hidden" class="form-control"  value="pret" name="operation" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                  <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                                <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                                  <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                               </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                          </div>
                         
                                            <button align="right" type="button" class="btn btn-danger noImpr  pull-right" data-toggle="modal" data-target="#depotRemb">
                                                            <i class="glyphicon glyphicon-plus"></i>Remboursement
                                                        
                                            </button>
                                             <div class="modal fade" id="depotRemb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau remboursement</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form name="formulairePersonnel" method="post">
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Montant <font color="red">*</font></label>
                                                                                  <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant" >
                                                                                  <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                                                                  <span class="text-danger" ></span>
                                                                              </div> 
                                                                              <div class="form-group">

                                                                                  <input type="hidden" class="form-control"  value="remboursement" name="operation" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                                                                  <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie" >
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                                <div class="form-group">
                                                                                  <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                                  <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Numero du compte">
                                                                                  <span class="text-danger" ></span>
                                                                              </div>
                                                                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                                                               </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                              </div>
                                        <?php }
                    ?>
                                             
                                       
                       </div>
                        <br>
                        <?php if($compte['typeCompte']=='compte cheques' OR $compte['typeCompte']=='compte pret'){

                            echo "<br>";
                            }
         
                        ?>
                         
                   
                        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
                        <script >
                            $(function() {
                                $(".expand").on( "click", function() {
                                    // $(this).next().slideToggle(200);
                                    $expand = $(this).find(">:first-child");

                                    if($expand.text() == "+") {
                                    $expand.text("-");
                                    } else {
                                    $expand.text("+");
                                    }
                                });
                            });
                        </script>
                        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

                        <!-- Debut de l'Accordion pour Tout les Paniers -->
                        <div class="panel-group mt-5" id="accordion">

                            <?php
                            /**Debut informations sur la date d'Aujourdhui **/
                                $date = new DateTime();
                                $annee =$date->format('Y');
                                $mois =$date->format('m');
                                $jour =$date->format('d');
                                $heureString=$date->format('H:i:s');
                                $dateString2=$jour.'-'.$mois.'-'.$annee;
                            /**Fin informations sur la date d'Aujourdhui **/

                            // On détermine sur quelle page on se trouve
                            if(isset($_GET['page']) && !empty($_GET['page'])){
                                $currentPage = (int) strip_tags($_GET['page']);
                            }else{
                                $currentPage = 1;
                            }
                            // On détermine le nombre d'articles par page
                            $parPage = 10;

                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                $sqlC="SELECT COUNT(*) FROM `".$nomtableComptemouvement."` where idCompte=".$_GET['c']." and annuler!='1'  ORDER BY idMouvement DESC";
                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                $nbre = mysql_fetch_array($resC) ;
                                $nbPaniers = (int) $nbre[0];
                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                            // On calcule le nombre de pages total
                            $pages = ceil($nbPaniers / $parPage);

                            $premier = ($currentPage * $parPage) - $parPage;

                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                $sqlP1="SELECT * FROM `".$nomtableComptemouvement."` where idCompte=".$_GET['c']." and annuler!='1' ORDER BY dateSaisie DESC LIMIT ".$premier.",".$parPage." ";
                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


                            ?>         

                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                <?php $n=$nbPaniers - (($currentPage * 10) - 10); 
                            while ($mouvement = mysql_fetch_array($resP1)) { 


                            ?>

                                    <div style="padding-top : 2px;" 
                                         <?php if($compte['typeCompte']=='compte cheques'){
                                                if($mouvement['retirer']==0){
                                                    echo ' class="panel panel-success "';
                                                } else{
                                                     echo ' class="panel panel-default"';
                                                }
                                            }else{
                                                if($mouvement['operation']=='versement' or $mouvement['operation']=='depot' || $mouvement['operation']=='pret' ){  
                                                    echo ' class="panel panel-success "';
                                                } elseif($mouvement['operation']=='retrait' or $mouvement['operation']=='remboursement' ){
                                                     echo ' class="panel panel-danger"';
                                                }else{
                                                     echo ' class="panel panel-primary"';
                                                }
                                            }
                                
                                            
                                         ?>
                                        >
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$mouvement['idMouvement']."" ; ?>  class="panel-title expand">
                                            <div class="right-arrow pull-right">+</div>
                                            <a href="#"> Operation <?php echo $n; ?>
                                                <span class="spanDate noImpr"> </span>
                                                <span class="spanDate noImpr"> 
                                                   <?php // $date = date_create($mouvement['dateSaisie']);  ?>
                                                    Date: <?php  echo $mouvement['dateSaisie'];  //date_format($date , 'd-m-y'); ?> </span>
                                                <span class="spanDate noImpr">Montant: <?php echo number_format(($mouvement['montant'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'] ; ?></span>
                                                <span class="spanDate noImpr"> Type: <?php echo $mouvement['operation']; ?> </span>
                                                 <?php 
                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$mouvement['idUser']."' ";
                                                    $res=mysql_query($sql);
                                                    $user=mysql_fetch_array($res);
                                                ?>
                                                <span class="spanDate noImpr">  #<?php echo substr(strtoupper($user['prenom']),0,3); ?></span>
                                            </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse " <?php echo  "id=versement".$mouvement['idMouvement']."" ; ?> >
                                            <div class="panel-body" >
                                                <!--*******************************Debut Annuler Pagnet****************************************-->
                                                         <?php if($compte['typeCompte']=='compte cheques'){
                                                
                                                             if($mouvement['retirer']==0){
                                                            ?>
                                                                <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnetR".$mouvement['idMouvement'] ; ?>>
                                                                        <span class="glyphicon glyphicon-remove"></span>Retire
                                                                </button>

                                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnetR".$mouvement['idMouvement'] ; ?> role="dialog">
                                                                    <div class="modal-dialog">
                                                                        <!-- Modal content-->
                                                                        <div class="modal-content">
                                                                            <div class="modal-header panel-primary">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Confirmation</h4>
                                                                            </div>
                                                                            <form class="form-inline noImpr"   method="post"  >
                                                                                <div class="modal-body">
                                                                                    <p><?php echo "Confirmez la retrait du cheque de :  <b> ".$mouvement['montant']."</b>" ; ?></p>
                                                                                    <input type="hidden" name="mouvement" id="mouvement"  <?php echo  "value='".$mouvement['idMouvement']."' " ; ?>>
                                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value='".$mouvement['montant']."' " ; ?>>
                                                                                    <input type="hidden" name="operation"   <?php echo  "value='".$mouvement['operation']."' " ; ?> >
                                                                                     <input type="hidden" class="form-control" name="compte" value="<?php echo  $_GET['c']; ?>">
                                                                                     <div class="form-group">
                                                                                          <label for="inputEmail3" class="control-label">Description <font color="red">*</font></label>
                                                                                          
                                                                                          <input type="text" name="description" placeholder="Description">
                                                                                          <span class="text-danger" ></span>
                                                                                      </div> 
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="btnRetirerCheque" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                           <?php }
                                                        }else{
                                                            ?>
                                                                 <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$mouvement['idMouvement'] ; ?> <?= ($mouvement['mouvementLink']=='0' || $mouvement['idVersement']=='0') ? '' :'disabled' ;?>>
                                                                    <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                </button>

                                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnet".$mouvement['idMouvement'] ; ?> role="dialog">
                                                                    <div class="modal-dialog">
                                                                        <!-- Modal content-->
                                                                        <div class="modal-content">
                                                                            <div class="modal-header panel-primary">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Confirmation</h4>
                                                                            </div>
                                                                            <form class="form-inline noImpr"   method="post"  >
                                                                                <div class="modal-body">
                                                                                    <p><?php echo "Voulez-vous annuler cette operation:  <b>".$mouvement['operation']."  de ".$mouvement['montant']."</b>" ; ?></p>
                                                                                    <input type="hidden" name="mouvement" id="mouvement"  <?php echo  "value='".$mouvement['idMouvement']."' " ; ?>>
                                                                                    <input type="hidden" name="montant" id="montant"  <?php echo  "value='".$mouvement['montant']."' " ; ?>>
                                                                                    <input type="hidden" name="operation"   <?php echo  "value='".$mouvement['operation']."' " ; ?> >
                                                                                    <input type="hidden" name="frais"   <?php echo  "value='".$mouvement['frais']."' " ; ?> >
                                                                                    <input type="hidden" name="compteDest"   <?php echo  "value='".$mouvement['compteDestinataire']."' " ; ?> >
                                                                                     <input type="hidden" class="form-control" name="compte" value="<?php echo  $_GET['c']; ?>">
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="btnAnnulerMouvement" class="btn btn-success">Confirmer</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                           <?php }?>
                                                                
                                                <!--*******************************Fin Annuler Pagnet****************************************-->
                                                <?php 
                                                    if($mouvement['operation']=='transfert' || $mouvement['operation']=='virement'){
                                                        if ($mouvement['compteDestinataire']) {
                                                            
                                                            $sqlGetCompte="SELECT * FROM `".$nomtableCompte."` where idCompte=".$mouvement['compteDestinataire'];
                                                            $resCompte = mysql_query($sqlGetCompte) or die ("persoonel requête 2".mysql_error());
                                                            $cpt= mysql_fetch_array($resCompte);

                                                            echo "<p><span class='label label-info'>Compte destinataire :</span>".$cpt['nomCompte']."</p>";
                                                            # code...
                                                        } else {
                                                            echo "<p><span class='label label-info'>Numero du destinataire :</span>".$mouvement['numeroDestinataire']."</p>";
                                                            # code...
                                                        }
                                                        
                                                    }
                                
                                                    if($compte['typeCompte']=='compte cheques'){
                                                         echo "<p><span class='label label-danger'>Date echéance  : </span>".$mouvement['dateEcheance']."</p>";
                                                    }
                                                    if($mouvement['frais']!=='0'){
                                                        echo "<p><span class='label label-warning'>Avec frais  : </span>".number_format(($mouvement['frais']  * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole']."</p>";
                                                   }

                                                    if ($mouvement['compteDonateur']) {                                                            
                                                        $sqlGetCompteD="SELECT * FROM `".$nomtableCompte."` where idCompte=".$mouvement['compteDonateur'];
                                                        $resCompteD = mysql_query($sqlGetCompteD) or die ("persoonel requête 2".mysql_error());
                                                        $cptD= mysql_fetch_array($resCompteD);

                                                        if ($cptD) {
                                                            # code...                                                                
                                                            echo  "<p><span class='label label-success'>Description : </span>Transfert reçu du compte => ".$cptD['nomCompte']."</p>";
                                                        } else {
                                                            # code...
                                                            echo  "<p><span class='label label-success'>Description :</span>".$mouvement['description']."</p>";
                                                        }
                                                    } else {
                                                        echo  "<p><span class='label label-success'>Description :</span>".$mouvement['description']."</p>";                                                            # code...
                                                    }
                                                        
                                                    
                                                ?>
                                                
                                                
                                            </div>
                                        </div>

                                <?php $n=$n-1;   } ?>
                                <?php if($nbPaniers >= 11){ ?>
                                    <ul class="pagination pull-right">
                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                            <a href="compte/compteDe.php?c=<?= $_GET['c']; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                        </li>
                                        <?php for($page = 1; $page <= $pages; $page++): ?>
                                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                                <a href="compte/compteDe.php?c=<?= $_GET['c']; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                            </li>
                                        <?php endfor ?>
                                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                            <a href="compte/compteDe.php?c=<?= $_GET['c']; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                        </div>
                        <!-- Fin de l'Accordion pour Tout les Paniers -->

            
              </div>
    </div>    
</body>
</html>
