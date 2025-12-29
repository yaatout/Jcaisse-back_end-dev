<?php
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:index.php');
}
require('connection.php');

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




if($_SESSION['profil']!="SuperAdmin" AND $_SESSION['profil']!="Assistant")
    header('Location:accueil.php');



if (isset($_GET['c'])) {
    $_SESSION['compteId']=$_GET['c'];
} else if(isset($_POST['c'])){
    $_SESSION['compteId']=$_POST['c'];
}else if(!isset($_SESSION['compteId'])){
    header('Location:compte.php');
}


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
          $sqlv="select * from `aaa-compte` where idCompte=".$_SESSION['compteId']."";
          $resv=mysql_query($sqlv);
          $compte =mysql_fetch_assoc($resv);
    
        
        if($operation=='versement' OR $operation=='depot' OR $operation=='pret'){
          $newMontant=$compte['montantCompte']+$montant;
        
          $sql1="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) 
          values('".$montant."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduserBack']."')";
          $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
        
          $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
	      $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
            
        }else{
                if($compte['montantCompte']>=$montant){
                    
                $newMontant=$compte['montantCompte']-$montant;
                $sql1="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser)
                 values('".$montant."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduserBack']."')";
                $res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
                //var_dump($sql1);

               $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
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
		$operation=trim($_POST['operation']);
		$idCompte=trim($_POST['compte']);
		$idMouvement=trim($_POST['mouvement']);

          $newMontant=0;
		
          //var_dump($operation);
          $sqlv="select * from `aaa-compte` where idCompte=".$_SESSION['compteId']."";
          $resv=mysql_query($sqlv);
          $compte =mysql_fetch_assoc($resv);
    
        
        if($operation=='versement' OR $operation=='depot'){
            
            if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
             }else{
                     $messageDel='Désolé mais la somme disponible est inferieur au montant à rembourser';
            }
        
        }elseif($operation=='remboursement'){
                
                $newMontant=$compte['montantCompte']+$montant;
                    
                $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);

               $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
               $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
        
        }elseif($operation=='pret'){
                
                if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
             }else{
                     $messageDel='Désolé mais la somme disponible est inferieur au montant à rembourser';
            }
        
        }else{
                
                $newMontant=$compte['montantCompte']+$montant;
                    
                $sql="UPDATE `aaa-comptemouvement` set  annuler='1' where  idMouvement=".$idMouvement."";
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
                //var_dump($sql1);

               $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
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
          $sqlv="select * from `aaa-compte` where idCompte=".$_SESSION['compteId']."";
          $resv=mysql_query($sqlv);
          $compte =mysql_fetch_assoc($resv);
    
        
        if($operation=='versement' OR $operation=='depot'){
            
            if($compte['montantCompte']>=$montant){
                $newMontant=$compte['montantCompte']-$montant;
                
                $sql="UPDATE `aaa-comptemouvement` set  retirer=1, dateRetrait='".$dateHeures."',description='".$description."' where idMouvement=".$idMouvement."";
                //echo $sql;
	            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
        
                $sql2="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$_SESSION['compteId']."";
                $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
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
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/'.$file);
            //var_dump("upload success 2");
            $sql2="UPDATE `aaa-comptemouvement` set pJointe='".$file."' where idMouvement='".$id."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Piéce jointe**/

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">

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
                     $sqlv="select * from `aaa-compte` where idCompte=".$_SESSION['compteId']."";
                     //var_dump($sqlv);
                      $resv=mysql_query($sqlv);
                      $compte =mysql_fetch_assoc($resv);
                 ?>
               <h2>Compte <?php echo $compte['nomCompte'].' : '.number_format($compte['montantCompte'], 0, ',', ' ')." FCFA" ; ?></h2>
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
                                    
                                    $sqlDV="SELECT SUM(montant)	FROM `aaa-comptemouvement`	where idCompte=".$_SESSION['compteId']." && (operation='versement' OR operation='depot' or operation='pret') && annuler!='1' ";
                                    $resDV=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
                                    $S_facture = mysql_fetch_array($resDV);
		                            $montantDV = $S_facture[0];
                                    //var_dump($montantDV);
                                    $sqlR="SELECT SUM(montant)	FROM `aaa-comptemouvement`	where idCompte=".$_SESSION['compteId']." && (operation='retrait' or  operation='remboursement' )&&  annuler!='1'  ";
                                    $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
                                    $S_factureR = mysql_fetch_array($resR);
		                            $montantR = $S_factureR[0];
                                    
                                    $sqlVT="SELECT SUM(montant)	FROM `aaa-comptemouvement`	where idCompte=".$_SESSION['compteId']." && (operation='virement' OR operation='transfert') && annuler!='1' ";
                                    $resVT=mysql_query($sqlVT) or die ("select stock impossible =>".mysql_error());
                                    $S_factureVT = mysql_fetch_array($resVT);
		                            $montantVT = $S_factureVT[0];
                                    
                                        //if($compte['typeCompte']=='compte bancaire'){
                                        if($compte['typeCompte']=='1'){
                                             echo "<h6>Montant depot : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant retrait :  ".number_format($montantR, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant virement : ".number_format($montantVT, 0, ',', ' ')." FCFA</h6>";
                                        //}elseif($compte['typeCompte']=='compte mobile'){
                                        }elseif($compte['typeCompte']=='2'){
                                            echo "<h6>Montant depot : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant retrait : ".number_format($montantR, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant transfert : ".number_format($montantVT, 0, ',', ' ')." FCFA</h6>";
                                        }
                                        //elseif($compte['typeCompte']=='compte cheques' || $compte['typeCompte']=='caisse'){
                                        elseif($compte['typeCompte']==3 || $compte['typeCompte']==5){
                                            echo "<h6>Montant depot : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant retrait : ".number_format($montantR, 0, ',', ' ')." FCFA</h6>";
                                        //}elseif($compte['typeCompte']=='compte pret'){
                                        }elseif($compte['typeCompte']==4){
                                            echo "<h6>Montant pret : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant remboursement : ".number_format($montantR, 0, ',', ' ')." FCFA</h6>";
                                        }
                                       
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

			</div>
            <div >
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
                        <div class="modal fade" id="upPJointe" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="padding:35px 50px;">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Contrat  de : <b><span id="prCon"></span> <span id="nCon"></span></b></h4>
                                                            </div>
                                                            <form   method="post" action="compteDe.php" <?php //echo  "action=compteDe.php?c='".htmlspecialchars($_SESSION['compteId'])."'" ; ?> enctype="multipart/form-data">
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
                                $sqlC="SELECT COUNT(*) FROM `aaa-comptemouvement` where idCompte=".$_SESSION['compteId']." and annuler!='1'  ORDER BY idMouvement DESC";
                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                $nbre = mysql_fetch_array($resC) ;
                                $nbPaniers = (int) $nbre[0];
                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                            // On calcule le nombre de pages total
                            $pages = ceil($nbPaniers / $parPage);

                            $premier = ($currentPage * $parPage) - $parPage;

                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                $sqlP1="SELECT * FROM `aaa-comptemouvement` where idCompte='".$_SESSION['compteId']."'and annuler!='1' ORDER BY dateSaisie DESC LIMIT ".$premier.",".$parPage." ";
                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


                            ?>         

                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                <?php $n=$nbPaniers - (($currentPage * 10) - 10); 
                            while ($mouvement = mysql_fetch_array($resP1)) { 


                            ?>

                                    <div style="padding-top : 2px;" 
                                         <?php if($compte['typeCompte']==3){
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
                                                    Date: <?php  echo $mouvement['dateSaisie'];  ?> </span>
                                                <!-- <span class="spanDate noImpr"> 
                                                   <?php  $date = date_create($mouvement['dateSaisie']);  ?>
                                                    Date: <?php  echo   date_format($date , 'd-m-y'); ?> </span> -->
                                                <span class="spanDate noImpr">Montant: <?php echo number_format($mouvement['montant'], 0, ',', ' ')." FCFA" ; ?></span>
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
                                                         <?php if($compte['typeCompte']==3){
                                                
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
                                                                                     <input type="hidden" class="form-control" name="compte" value="<?php echo  $_SESSION['compteId']; ?>">
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
                                                        }else{?>
                                                                 
                                                                 <?php
                                                                 if ($mouvement['operation']=='depot') {?>
                                                                    <!-- <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$mouvement['idMouvement'] ; ?>>
                                                                        <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                    </button> -->
                                                                    <?php 
                                                                    }
                                                                ?>
                                                                 
                                                                <?php   //Annuler les retrait payé apartir de salaire
                                                                        if($mouvement['idSP']==null){?>
                                                                    <!-- <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$mouvement['idMouvement'] ; ?>>
                                                                        <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                    </button> -->
                                                                    <!-- <?php 
                                                                        if ($mouvement['operation']=='retrait' ) { ?>
                                                                            <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_ann_pagnet".$mouvement['idMouvement'] ; ?>>
                                                                                <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <button tabindex="1" type="submit" class="btn btn-danger pull-right" data-toggle="modal" disabled>
                                                                                <span class="glyphicon glyphicon-remove"></span>Annuler
                                                                            </button>
                                                                        <?php }
                                                                        
                                                                    ?> -->
                                                                    
                                                                <?php }?>   
                                                                <div class="modal fade" <?php echo  "id=msg_ann_pagnet".$mouvement['idMouvement'] ; ?> role="dialog">
                                                                    <div class="modal-dialog"> 
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
                                                                                     <input type="hidden" class="form-control" name="compte" value="<?php echo  $_SESSION['compteId']; ?>">
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
                                                        echo "<p><span class='label label-info'>Numero du destinataire :</span>".$mouvement['numeroDestinataire']."</p>";
                                                    }
                                
                                                    if($compte['typeCompte']==3){
                                                         echo "<p><span class='label label-danger'>Date echéance  : </span>".$mouvement['dateEcheance']."</p>";
                                                    }
                        
                                
                                                        echo  "<p><span class='label label-success'>Description   :</span>".$mouvement['description']."</p>";                                                    
                                                ?>
                                                  <?php
                                                        if ($mouvement['operation']=='retrait' ) {
                                                            if($mouvement['pJointe'] != null || $mouvement['pJointe'] != '' ){ 
                                                                $format=substr($mouvement['pJointe'], -3); ?>
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upPJPopup(<?php echo $mouvement['idMouvement']; ?>)" 	 />
                                                                            <?php }
                                                                                else { 
                                                                                    ?>
                                                                                    <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upPJPopup(<?php echo $mouvement['idMouvement']; ?>)" 	 />
                                                                                <?php } ?>
                                                                        <?php
                                                            }else{ ?>
                                                                   <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upPJPopup(<?php echo $mouvement['idMouvement']; ?>)" 	 />
                                                                    
                                                            <?php }    
                                                        }
                                                        ?>
                                            </div>
                                        </div>

                                <?php $n=$n-1;   } ?>
                                <?php if($nbPaniers >= 11){ ?>
                                    <ul class="pagination pull-right">
                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                            <a href="compteDe.php?c=<?= $_SESSION['compteId']; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                        </li>
                                        <?php for($page = 1; $page <= $pages; $page++): ?>
                                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                                <a href="compteDe.php?c=<?= $_SESSION['compteId']; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                            </li>
                                        <?php endfor ?>
                                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                            <a href="compteDe.php?c=<?= $_SESSION['compteId']; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
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
