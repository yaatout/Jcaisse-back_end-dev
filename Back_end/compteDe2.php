<?php
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:index.php');
}
require('connection.php');
require('connectionPDO.php');

///echo phpinfo();
/**********************/

$messageDel='';

/**********************/
/**Debut informations sur la date d'Aujourdhui **/
 
/**Fin informations sur la date d'Aujourdhui **/
///echo phpinfo();
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
//$idFournisseur=htmlspecialchars(trim($_GET['iDS']));

if (isset($_GET['c'])) {
    $_SESSION['compteId']=$_GET['c'];
} else if(isset($_POST['c'])){
    $_SESSION['compteId']=$_POST['c'];
}else if(!isset($_SESSION['compteId'])){
    header('Location:compte.php');
}


$req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
$req1->execute(array('in' =>$_SESSION['compteId']))  or die(print_r($req1->errorInfo())); 
$compte=$req1->fetch();                     
//var_dump($compte);
if(isset($_GET['debut']) && isset($_GET['fin'])){
    $dateDebut=@htmlspecialchars($_GET["debut"]);
    $dateFin=@htmlspecialchars($_GET["fin"]);
}
else {
    $dateDebut=date('Y-m-d', strtotime('-1 years'));
    $dateFin=$dateString;
}



if($_SESSION['profil']!="SuperAdmin" AND $_SESSION['profil']!="Assistant")
    header('Location:accueil.php');






if (isset($_POST['btnEnregistrerMouvement'])) {
        
		$montant=htmlspecialchars(trim($_POST['montant']));
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
          
            // EN PDO////////
            $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
            $req1->execute(array('in' =>$_SESSION['compteId']))  or die(print_r($req1->errorInfo())); 
            $compte=$req1->fetch(); 

        if($operation=='versement' OR $operation=='depot' OR $operation=='pret'){
          $newMontant=$compte['montantCompte']+$montant;
        
        //   $sql1="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) 
        //   values('".$montant."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduserBack']."')";
        //   $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
            ///EN PDO 

            $req1 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser)
                                                            values(:mont,:op,:idC,:num,:compt,:cli,:dateEc,:dateSai,:dateOper,:desc,:idUs)");
                            $req1->execute(array(
                                                  'mont' =>$montant,
                                                  'op' =>$operation,
                                                  'idC' =>$idCompte,
                                                  'num' =>$numeroDestinataire,
                                                  'compt' =>$compteDonateur,
                                                  'cli' =>$nomClient,
                                                  'dateEc' =>$dateEcheance,
                                                  'dateSai' =>$dateHeures,
                                                  'dateOper' =>$dateSaisie,
                                                  'desc' =>$description,
                                                  'idUs' =>$_SESSION['iduserBack']
                                                  ))  or die(print_r($req1->errorInfo()));


            // $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
            // $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
            //EN PDO
            
            $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
            $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          

        }else{
                if($compte['montantCompte']>=$montant){
                    
                $newMontant=$compte['montantCompte']-$montant;
                
                // $sql1="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser)
                //  values('".$montant."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduserBack']."')";
                // $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
                //var_dump($sql1);
                    //EN PDO
                $req1 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser)
                    values(:mont,:op,:idC,:num,:compt,:cli,:dateEc,:dateSai,:dateOper,:desc,:idUs)");
                $req1->execute(array(
                        'mont' =>$montant,
                        'op' =>$operation,
                        'idC' =>$idCompte,
                        'num' =>$numeroDestinataire,
                        'compt' =>$compteDonateur,
                        'cli' =>$nomClient,
                        'dateEc' =>$dateEcheance,
                        'dateSai' =>$dateHeures,
                        'dateOper' =>$dateSaisie,
                        'desc' =>$description,
                        'idUs' =>$_SESSION['iduserBack']
                        ))  or die(print_r($req1->errorInfo()));

            //    $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
            //    $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

               
                $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
                $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          
                    
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
		$operation=trim($_POST['operation']);
		$idCompte=trim($_POST['compte']);
		$idMouvement=trim($_POST['mouvement']);

          $newMontant=0;
		
          //var_dump($operation);
        //   $sqlv="select * from `aaa-compte` where idCompte=".$_SESSION['compteId']."";
        //   $resv=mysql_query($sqlv);
        //   $compte =mysql_fetch_assoc($resv);

          $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
          $req1->execute(array('in' =>$_SESSION['compteId']))  or die(print_r($req1->errorInfo())); 
          $compte=$req1->fetch(); 

        if($operation=='versement' OR $operation=='depot'){
            
            if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                // $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            // $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());

                $req2 = $bdd->prepare("UPDATE `aaa-comptemouvement` set  annuler=:a where  idMouvement=:i");
                $req2->execute(array( 'a' => 1,'i' => $idMouvement )) or die(print_r($req2->errorInfo()));
          

        
                // $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
                // $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

                $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
                $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          

             }else{
                     $messageDel='Désolé mais la somme disponible est inferieur au montant à rembourser';
            }
        
        }elseif($operation=='remboursement'){
                
                $newMontant=$compte['montantCompte']+$montant;
                    
                // $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            // $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);
                $req1 = $bdd->prepare("UPDATE `aaa-comptemouvement` set  annuler=:a where  idMouvement=:i");
                $req1->execute(array( 'a' => 1,'i' => $idMouvement )) or die(print_r($req1->errorInfo()));
          

            //    $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
            //    $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

                $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
                $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          
        
        }elseif($operation=='pret'){
                
                if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                // $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            // $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                // $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
                // $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

                $req = $bdd->prepare("UPDATE `aaa-comptemouvement` set  annuler=:a where  idMouvement=:i");
                $req->execute(array( 'a' => 1,'i' => $idMouvement )) or die(print_r($req->errorInfo())); 
                
                $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
                $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          
             }else{
                     $messageDel='Désolé mais la somme disponible est inferieur au montant à rembourser';
            }
        
        }else{
                
                $newMontant=$compte['montantCompte']+$montant;
                    
            //     $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	        //     $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
            //     //var_dump($sql1);

            //    $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
            //    $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

               $req = $bdd->prepare("UPDATE `aaa-comptemouvement` set  annuler=:a where  idMouvement=:i");
                $req->execute(array( 'a' => 1,'i' => $idMouvement )) or die(print_r($req->errorInfo())); 
                
                $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
                $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          
           
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
        //   $sqlv="select * from `aaa-compte` where idCompte=".$_SESSION['compteId']."";
        //   $resv=mysql_query($sqlv);
        //   $compte =mysql_fetch_assoc($resv);
    
          $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
          $req1->execute(array('in' =>$_SESSION['compteId']))  or die(print_r($req1->errorInfo())); 
          $compte=$req1->fetch(); 
        
        if($operation=='versement' OR $operation=='depot'){
            
            if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                // $sql="UPDATE `aaa-comptemouvement` set  retirer=1, dateRetrait='".$dateHeures."',description='".$description."' where idMouvement=".$idMouvement."";
                // //echo $sql;
	            // $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());

                $req = $bdd->prepare("UPDATE `aaa-comptemouvement` set  retirer=:r , dateRetrait=:dr ,description=:desc where  idMouvement=:i");
                $req->execute(array( 'r' => 1,'dr' => $dateHeures,'desc' => $description,'i' => $idMouvement )) or die(print_r($req->errorInfo()));        
        
                // $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
                // $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

                $req2 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:m where  idCompte=:id");
                $req2->execute(array( 'm' => $newMontant,'id' => $_SESSION['compteId'] )) or die(print_r($req2->errorInfo()));
          
             }else{
                     $messageDel='';
            }
        
        }
        /*else{
                
                $newMontant=$compte['montantCompte']+$montant;
                    
                $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);

               $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
               $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
           
        }*/
        
}
/**Debut Button upload Piéce jointe**/
if (isset($_POST['btnUploadPieceJ'])) {
    $id=htmlspecialchars(trim($_POST['idMv']));
    //echo "dans 1re if <br>";
    //var_dump($_FILES['file']);
    if(isset($_FILES['file'])){
        //echo "dans 2eme if <br>";
        //var_dump($id);
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));
        //var_dump($extension);
        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 1000000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/'.$file);
            //var_dump("upload success 2");


            // $sql2="UPDATE `aaa-comptemouvement` set pJointe='".$file."' where idMouvement='".$id."' ";
            // $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());

            // En PDO
            $req2 = $bdd->prepare("UPDATE `aaa-comptemouvement` set pJointe=:p where idMouvement=:m ");
            $req2->execute(array( 'p' => $file,'m' => $id )) or die(print_r($req2->errorInfo()));
          
        }
        else{
            echo "Une erreur est survenue: Peut étre que la taille maw dépassé ";
        }
    }
}

//////////////////////////////////////////




?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/datatables.min.css">
	<script src="js/datatables.min.js"></script>
    <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JCAISSE-BACK END</title>
</head>
<body>

	<?php
	   require('header.php');
	?>
            
    <div class="container" >

        <!-- <input id="inpt_C_id" type="hidden" value="<?= $_SESSION['compteId']?>" /> -->
        <input id="inpt_C_dateDebut" type="hidden" value="<?= $dateDebut?>" />
        <input id="inpt_C_dateFin" type="hidden" value="<?= $dateFin?>" />

        <script type="text/javascript">
            $(function() {
                    id = <?php echo json_encode($_SESSION['compteId']); ?>;
                    dateDebut = <?php echo json_encode($dateDebut); ?>;
                    dateFin = <?php echo json_encode($dateFin); ?>;
                    tabDebut=dateDebut.split('-');
                    tabFin=dateFin.split('-');
                    var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];
                    var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];
                    function cb(start, end) {
                        var debut=start.format('YYYY-MM-DD');
                        var fin=end.format('YYYY-MM-DD');
                        window.location.href = "compteDe.php?c="+id+"&&debut="+debut+"&&fin="+fin;
                    }
                    $('#reportrange').daterangepicker({
                        startDate: start,
                        endDate: end,
                        locale: {
                            format: 'DD/MM/YYYY',
                            separator: ' - ',
                            applyLabel: 'Valider',
                            cancelLabel: 'Annuler',
                            fromLabel: 'De',
                            toLabel: 'à',
                            customRangeLabel: 'Choisir',
                            daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve','Sa'],
                            monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'November', 'Decembre'],
                            firstDay: 1
                        },
                        ranges: {
                            'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Une Semaine': [moment().subtract(6, 'days'), moment()],
                            'Un Mois': [moment().subtract(30, 'days'), moment()],
                            'Ce mois ci': [moment().startOf('month'), moment()],
                            'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                    }, cb);
                    cb(start, end);
                    });
        </script>

        <div class="col-sm-4 pull-left" >
            <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="compte.php" > Retour </a>
        </div>

        <input type="hidden" id="l" <?php echo "value=".$_SESSION['compteId'].""; ?> >
        
        <div class="jumbotron noImpr" id="resumeOperation" onload="afficerTableau()">
           
                      
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
            
                <div >
                <div class="modal fade" id="upPJointe" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="padding:35px 50px;">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Contrat  de : <b><span id="prCon"></span> <span id="nCon"></span></b></h4>
                                                            </div>
                                                            <form   method="post" action="compteDe2.php" <?php //echo  "action=compteDe.php?c='".htmlspecialchars($_SESSION['compteId'])."'" ; ?> enctype="multipart/form-data">
                                                                <div class="modal-body" style="padding:40px 50px;">
                                                                    <input  type="hidden"  name="idMv" id="idMv"  />
                                                                    <input  type="hidden"  name="c" <?php echo  "value='".$_SESSION['compteId']."' " ; ?>  />
                                                                    <div class="form-group" style="text-align:center;" >
                                                                        <div id='noUpload' ><br />                                                                                 
                                                                        </div>
                                                                        <div  id='yesUpload' >
                                                                                                                                                     
                                                                        </div>                                                                   
                                                                    </div> 
                                                                </div>
                                                                <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-success pull-left" name="btnUploadPieceJ"  >
                                                                            <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                        </button>
                                                                        <div id='yesBoutonTelecharger' >
                                                                           
                                                                        </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                    </div> 
                        <?php  //if($compte['typeCompte']=='compte bancaire' || $compte['typeCompte']=='compte mobile' || $compte['typeCompte']=='caisse'){
                                if($compte['typeCompte']==1 || $compte['typeCompte']==2 || $compte['typeCompte']==5){
                                                if($compte['typeCompte']==1){  ?>
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
                                                                            <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
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
                                                                            <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
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
                                                                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
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
                                                            <?php if($compte['typeCompte'] !=5){//COMPTE banque seulement  ?>
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
                                                                                        <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
                                                                                        <input type="hidden" class="form-control"  value="transfert" name="operation" >
                                                                                        <span class="text-danger" ></span>
                                                                                    </div> 
                                                                                    <div class="form-group">
                                                                                        <label for="inputEmail3" class="control-label">Numero Telephone du destinataire <font color="red">*</font></label>
                                                                                        <input type="tel" class="form-control"   name="numeroDestinataire" required placeholder="Numero Telephone du destinataire">

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
                                                            <?php } ?>
                                                            
                                                <?php  } ?>
                                                
                                                        <button type="button" class="btn btn-danger noImpr  pull-right" data-toggle="modal" data-target="#retrait">
                                                                <i class="glyphicon glyphicon-plus"></i>Retrait
                                                        </button> <br>
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
                                                                        <input type="hidden" class="form-control" name="compte" value="<?php echo  $_SESSION['compteId']; ?>">
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
                        <?php }elseif($compte['typeCompte']==3){//COMPTE CHEQUE  ?>
                                                
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
                                                                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
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
                                                                                                                    
                                                                                                                    /* $sqlv="select * from `aaa-banque` ";
                                                                                                                    $resv=mysql_query($sqlv);
                                                                                                                    while($operation =mysql_fetch_assoc($resv)){
                                                                                                                    echo '<option value="'.$operation["nom"].'">'.$operation["nom"].'</option>';
                                                                                                                    } */
                                                                                                                    $sqlv = $bdd->prepare("SELECT * from `aaa-banque`"); 
                                                                                                                    $sqlv->execute()  or die(print_r($sqlv->errorInfo()));                                                         
                                                                                                                    $operations =$sqlv->fetchAll();
                                                                                                                    foreach($op as $operations){
                                                                                                                        echo '<option value="'.$op["nom"].'">'.$op["nom"].'</option>';
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

                        <?php }elseif($compte['typeCompte']==4){//COMPTE PRET  ?>
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
                                                                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
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
                                                                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_SESSION['compteId']; ?>">
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
                        <?php } ?>                           
                </div>
                            <br>
                            <?php if($compte['typeCompte']==3 OR $compte['typeCompte']==4){

                                echo "<br>";
                                }
            
                            ?>
                            
                    
            <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->
                <style >
                    /* Firefox */
                    input[type=number] {
                        -moz-appearance: textfield;
                    }
                    /* Chrome */
                    input::-webkit-inner-spin-button,
                    input::-webkit-outer-spin-button {
                        -webkit-appearance: none;
                        margin:0;
                    }
                    /* Opéra*/
                    input::-o-inner-spin-button,
                    input::-o-outer-spin-button {
                        -o-appearance: none;
                        margin:0
                    }
                </style>
            <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

            

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
            <div class="table-responsive">
               <div id="listerOperations"><!-- content will be loaded here --></div>
            </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->
                    
    </div>
    
<script type="text/javascript" src="./js/scriptCompte.js"></script>    
</body>
</html>
