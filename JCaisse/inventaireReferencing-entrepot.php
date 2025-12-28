<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

date_default_timezone_set('Africa/Dakar');


// var_dump($_SESSION['idBoutique']);

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');
require('connectionPDO.php');

require('declarationVariables.php');


$beforeTime = '00:00:00';
$afterTime = '06:00:00';

    // var_dump(date('d-m-Y',strtotime("-1 days")));

if($_SESSION['Pays']=='Canada'){  
	$date = new DateTime();
	$timezone = new DateTimeZone('Canada/Eastern');
}
else{
	$date = new DateTime();
	$timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString=$date->format('H:i:s');

// if ($heureString >= $beforeTime && $heureString < $afterTime) {
//    	// var_dump ('is between');
// 	$date = new DateTime (date('d-m-Y',strtotime("-1 days")));
// }

// $date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateHeures=$dateString.' '.$heureString;

$uploaded=0;


$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=".$_SESSION['iduser'];
$resU = mysql_query($sqlU) or die ("persoonel requête 2".mysql_error());
$user = mysql_fetch_array($resU);

$iduser = $user['idutilisateur'];

$finDefault = $annee.'-'.$mois.'-'.$jour;
$dateFin = new DateTime (date('d-m-Y',strtotime("-30 days")));
$dateFin->setTimezone($timezone);
$anneeF =$dateFin->format('Y');
$moisF =$dateFin->format('m');
$jourF =$dateFin->format('d');
$debutDefault=$anneeF.'-'.$moisF.'-'.$jourF;



?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');


?>

    <div class="container">
    
        <div class="col-lg-2" align="center">
          <!-- <form class="form-inline" method="POST" action="#">
            <div class="form-group mx-sm-3 mb-2">
              <input type="date" class="form-control" name="dateSelected" id="input_dateDebut" value="<?= $debutDefault?>" required>
              <input type="date" class="form-control" name="dateSelected" id="input_dateFin" value="<?= $finDefault?>" required>
            </div>
            <button type="button" id="dateSelectedValidate" class="btn btn-primary mb-2"><i class="glyphicon glyphicon-ok"></i></button>
          </form> -->
          <label for=""><b>Choisir un depot</b> </label>
          <select name="depotChoice" id="depotChoice" class="form-control form-control-warning">
            <option value=""></option>
            <?php 
                $reqDp="SELECT * from  `".$nomtableEntrepot."`";

                $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());

                while ($depot = mysql_fetch_assoc($resDp)) {

                    echo '<option value="'.$depot["idEntrepot"].'">'.$depot["nomEntrepot"].'</option>';

                }
    


             ?>            
          </select><br><br>
        </div>

          <div class="container-fluid">
            <table class="table table-bordered table-responsive" id="tabInventaire">
                <thead>
                    <tr>
                        <th>REFERENCE</th>
                        <th>CATEGORIE</th>
                        <th>UNITE-STOCK</th>
                        <th>PRIX-UNITAIRE</th>
                        <th>QUANTITE-STOCK</th>
                        <th>OPERATION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <input type="hidden" id="inputInvHidden" class="form-control">
                        <td><input type="text" id="referenceInv" class="form-control" autocomplete="off"></td>
                        <td><input type="text" id="categorieInv" class="form-control"></td>
                        <td align="center">Pièce</td>
                        <td><input type="number" id="prixInv" class="form-control"></td>
                        <td><input type="number" id="quantiteInv" class="form-control"></td>
                        <td align="center"><button type="button" class="btn btn-primary" id="btnAddInv" onClick="validerInv()"><span class="glyphicon glyphicon-ok"></span></button></td>
                    </tr>
                </tbody>
            </table>
          </div>      
    </div>