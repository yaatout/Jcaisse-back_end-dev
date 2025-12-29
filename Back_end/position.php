<?php
    session_start();

    require('connection.php');
    require('connectionPDO.php');
    require('declarationVariables.php');
    
    if(!$_SESSION['iduserBack'])
        header('Location:index.php');
    if (isset($_POST['AddPosition'])) {
        # code...
        $idBoutique=$_POST['i'];
        $latitude=$_POST['latitude'];
        $longitude=$_POST['longitude'];
        //var_dump($idBoutique);
        
        // Vérifier si une position existe déjà pour cette boutique
        $reqCheck = $bdd->prepare("SELECT * FROM `aaa-locations` WHERE idBoutique=:idB"); 
        $reqCheck->execute(array('idB'=>$idBoutique)) or die(print_r($reqCheck->errorInfo())); 
        if ($reqCheck->fetch()) {
            // Une position existe déjà, afficher un message d'erreur et rediriger
            // echo "<script>alert('Une position existe déjà pour cette boutique. Utilisez le bouton de modification pour la mettre à jour.');</script>";
            // echo "<script>window.location.href='position.php';</script>";
            
            $req1 = $bdd->prepare("update `aaa-locations` set lat=:la, lng=:ln where idBoutique=:idB"); 
            $req1->execute(array(
                            'la'=>$latitude,
                            'ln'=>$longitude,
                            'idB'=>$idBoutique
                            )) or die(print_r($req1->errorInfo())); 
                exit;
            }
        
        $req1 = $bdd->prepare("select * from `aaa-boutique` where idBoutique=:idB"); 
        $req1->execute(array('idB'=>$idBoutique)) or die(print_r($req1->errorInfo())); 
        $boutique=$req1->fetch();
        //var_dump($boutique);
        $req3 = $bdd->prepare("insert into `aaa-locations` (idBoutique,lat,lng,description) values (:idB,:la,:ln,:des) "); 
        $req3->execute(array(
                        'idB'=>$idBoutique,
                        'la'=>$latitude,
                        'ln'=>$longitude,
                        'des'=>$boutique['labelB']
                        )) or die(print_r($req3->errorInfo())); 
        // echo 'cccc';

    }
    if (isset($_POST['UpdatePosition'])) {
        $idPosition=$_POST['idPosition'];
        $idBoutique=$_POST['i'];
        $latitude=$_POST['latitude'];
        $longitude=$_POST['longitude'];

        $req1 = $bdd->prepare("update `aaa-locations` set lat=:la, lng=:ln where id=:idPos and idBoutique=:idB"); 
        $req1->execute(array(
                        'la'=>$latitude,
                        'ln'=>$longitude,
                        'idPos'=>$idPosition,
                        'idB'=>$idBoutique
                        )) or die(print_r($req1->errorInfo())); 

    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>    
	<!-- <link rel="stylesheet" href="css/bootstrap.css">  -->
	<!-- <link rel="stylesheet" href="css/datatables.min.css"> -->
	<script src="js/jquery-3.1.1.min.js"></script>
   <script type="text/javascript" src="js/jquery.mask.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<script src="js/bootstrap.js"></script>
    <script src="js/datatables.min.js"></script>
    <script>$(document).ready( function () {$('#exemple').DataTable();} );</script>
    <script>$(document).ready( function () {$('#exemple2').DataTable();} );</script>
    <script>$(document).ready( function () {$('#exemple3').DataTable();} );</script>
   <script> $(document).ready(function () { $("#exemple3").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple4").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple5").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple6").DataTable();});</script>
   <script type="text/javascript" src="js/scriptB.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<title>JCAISSE-BACK END</title>
</head>
<body>
<?php
  require('header.php');
?>
    <div class="container-fluid">
                                <div class="modal fade" id="addPosition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">ADD positiont</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form  method="post" >
                                                    <h3 id='titrePosition'></h3>
                                                    <input type="hidden" id='i' name="i">
                                                <div class="form-group">
                                                    <label > Latitude</label>
                                                    <input class="form-control" name="latitude" id="latitude" type="text" />
                                                </div>
                                                 <div class="form-group">
                                                    <label > Longitude</label>
                                                    <input class="form-control" name="longitude" id="longitude" type="text" />
                                                </div>
                                                        <div class="modal-footer row">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" name="AddPosition" class="btn btn-success"> Confirmer</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="editPosition" tabindex="-1" role="dialog" aria-labelledby="myModalLabelEdit">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabelEdit">EDIT positiont</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form  method="post" >
                                                    <h3 id='titrePositionEdit'></h3>
                                                    <input type="hidden" id='idPosition' name="idPosition">
                                                    <input type="hidden" id='iEdit' name="i">
                                                <div class="form-group">
                                                    <label > Latitude</label>
                                                    <input class="form-control" name="latitude" id="latitudeEdit" type="text" />
                                                </div>
                                                 <div class="form-group">
                                                    <label > Longitude</label>
                                                    <input class="form-control" name="longitude" id="longitudeEdit" type="text" />
                                                </div>
                                                        <div class="modal-footer row">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" name="UpdatePosition" class="btn btn-warning"> Mettre à jour</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th>Nom Boutique</th>
				<th>Adresse</th>
				<th>Date de création</th>
				<th>Type</th>
				<th>Opération</th>
			</tr>
		</thead>
        <?php
            $req1 = $bdd->prepare("select * from `aaa-boutique` "); 
            $req1->execute()  or die(print_r($req1->errorInfo())); 
            while ($boutique=$req1->fetch()) {?>
                <tr>
                    <td> <?php echo $boutique['labelB']; ?> </td>
                    <td> <?php echo $boutique['adresseBoutique']; ?> </td>
                    <td> <?php echo $boutique['datecreation']; ?> </td>
                    <td> <?php echo $boutique['type']; ?> </td>
                    <td>
                        <?php
                             $req2 = $bdd->prepare("select * from `aaa-locations` where idBoutique=:idB "); 
                             $req2->execute(array('idB'=>$boutique['idBoutique']))  or die(print_r($req2->errorInfo())); 
                             if ($position=$req2->fetch()) {
                                echo '<button onclick="editPosition('.$position['id'].','.$boutique['idBoutique'].',\''.$position['lat'].'\',\''.$position['lng'].'\',\''.$boutique['labelB'].'\')" class="btn btn-warning btn-sm">
                                    <i class="glyphicon glyphicon-edit"></i> Modifier position
                                </button>';
                             }else {?>
                                <button onclick="addPosition( <?php echo $boutique['idBoutique'] ?>,'<?php echo $boutique['labelB'] ?>')" class="btn btn-primary btn-sm">
                                    <i class="glyphicon glyphicon-map-marker"></i> Ajouter position
                                </button>
                            <?php }
                        ?>
                    </td>
                </tr>
        <?php }?>
    </div>
</body>
</html>