<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/
session_start();
if($_SESSION['iduserBack']){

require('connection.php');
require('connectionPDO.php');
require('declarationVariables.php');

/**********************/
$idTransaction         =@$_POST["idTransaction"];

$nomTransaction         =@$_POST["nomTransaction"];

$aliasTransaction          =@$_POST["aliasTransaction"];

$typeTransaction          =@$_POST["typeTransaction"];


$modifier            =@$_POST["modifier"];
$supprimer           =@$_POST["supprimer"];
$annuler             =@$_POST["annuler"];
/***************/



/*
echo $nomtableDesignation ;
echo $designation ;
echo $prix;
echo $uniteStock;
echo $prixuniteStock;
echo $nbArticleUniteStock;
*/
/**********************/
if(!$annuler){
if(!$modifier and !$supprimer){
  if($nomTransaction) {
      // Contrôle côté serveur
      $nomTransaction = trim($nomTransaction);
      $aliasTransaction = trim($aliasTransaction);
      
      if(empty($nomTransaction)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la transaction ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomTransaction) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la transaction doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomTransaction) > 100) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la transaction ne peut pas dépasser 100 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(!empty($aliasTransaction) && strlen($aliasTransaction) > 100) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("L\'alias ne peut pas dépasser 100 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si la transaction existe déjà
  		$sql11="SELECT * FROM `aaa-transaction` where nomTransaction=:nomTransaction";
		$req11 = $bdd->prepare($sql11);
		$req11->execute(array('nomTransaction' => $nomTransaction));
		if($req11->rowCount() == 0){
		    if($aliasTransaction){
				$sql="insert into `aaa-transaction` (nomTransaction,aliasTransaction,typeTransaction) values (:nomTransaction,:aliasTransaction,:typeTransaction)";
				$req = $bdd->prepare($sql);
				$req->execute(array(
                    'nomTransaction' => $nomTransaction,
                    'aliasTransaction' => $aliasTransaction,
                    'typeTransaction' => $typeTransaction
                )) or die(print_r($req->errorInfo()));
                }else{
                $sql="insert into `aaa-transaction` (nomTransaction,aliasTransaction,typeTransaction) values (:nomTransaction,:nomTransaction,:typeTransaction)";
				$req = $bdd->prepare($sql);
				$req->execute(array(
                    'nomTransaction' => $nomTransaction,
                    'typeTransaction' => $typeTransaction
                )) or die(print_r($req->errorInfo()));
                }
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script>alert("Cette transaction existe déjà.");</script>';
		echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
}
elseif($modifier){
      // Contrôle côté serveur pour la modification
      $nomTransaction = trim($nomTransaction);
      $aliasTransaction = trim($aliasTransaction);
      
      if(empty($nomTransaction)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du crédit ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomTransaction) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du crédit doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomTransaction) > 100) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du crédit ne peut pas dépasser 100 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(!empty($aliasTransaction) && strlen($aliasTransaction) > 100) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("L\'alias ne peut pas dépasser 100 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si une autre transaction avec le même nom existe déjà (sauf celle qu'on modifie)
      $sql11="SELECT * FROM `aaa-transaction` where nomTransaction=:nomTransaction AND idTransaction !=:idTransaction";
      $req11 = $bdd->prepare($sql11);
      $req11->execute(array('nomTransaction' => $nomTransaction, 'idTransaction' => $idTransaction));
      if($req11->rowCount() > 0){
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Ce crédit existe déjà.");</script>';
          echo'<script language="JavaScript">document.location="insertionCredit.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
       try {
           $sql1="update `aaa-transaction` set nomTransaction=:nomTransaction,aliasTransaction=:aliasTransaction,typeTransaction=:typeTransaction WHERE idTransaction =:idTransaction";
           $req1 = $bdd->prepare($sql1);
           $req1->execute(array(
               'nomTransaction' => $nomTransaction,
               'aliasTransaction' => $aliasTransaction,
               'typeTransaction' => $typeTransaction,
               'idTransaction' => $idTransaction
           )) or die(print_r($req1->errorInfo()));
       } catch(PDOException $e) {
           die("Erreur lors de la modification du crédit : " . $e->getMessage());
       }
}
else if ($supprimer) {
    try {
        $sql2="DELETE FROM `aaa-transaction` WHERE idTransaction =:idTransaction";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(array('idTransaction' => $idTransaction)) or die(print_r($req2->errorInfo()));
    } catch(PDOException $e) {
        die("Erreur lors de la suppression de la transaction : " . $e->getMessage());
    }
}

if(isset($_POST["btnUploadImg"])) {

		function resizeImage($resourceType,$image_width,$image_height) {
			$resizeWidth = 150;
			$resizeHeight = 150;
			$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
			imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
			return $imageLayer;
		}

		$imageProcess = 0;
				if(is_array($_FILES)) {

						$fileName = $_FILES['file']['tmp_name'];
						$sourceProperties = getimagesize($fileName);
						$resizeFileName = time();
						$uploadPath = "images/";
						$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
						$uploadImageType = $sourceProperties[2];
						$sourceImageWidth = $sourceProperties[0];
						$sourceImageHeight = $sourceProperties[1];

						$id         =@$_POST["idTransaction"];
						//$catTypeCateg     =@$_POST["tab"];
						switch ($uploadImageType) {
								case IMAGETYPE_JPEG:
										$resourceType = imagecreatefromjpeg($fileName);
										$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
										imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
										$fileNameNew=$resizeFileName.'.'. $fileExt;
										imagedestroy($imageLayer);
										imagedestroy($resourceType);
										$sql="update `aaa-transaction` set image=:image where idTransaction=:idTransaction";
										$req = $bdd->prepare($sql);
										$req->execute(array(
											'image' => $fileNameNew,
											'idTransaction' => $id
										)) or die(print_r($req->errorInfo()));
										break;
								case IMAGETYPE_PNG:
										$resourceType = imagecreatefrompng($fileName);
										$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
										imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
										$fileNameNew=$resizeFileName.'.'. $fileExt;
										imagedestroy($imageLayer);
										imagedestroy($resourceType);
										$sql="update `aaa-transaction` set image=:image where idTransaction=:idTransaction";
										$req = $bdd->prepare($sql);
										$req->execute(array(
											'image' => $fileNameNew,
											'idTransaction' => $id
										)) or die(print_r($req->errorInfo()));
										break;
								default:
										$imageProcess = 0;
										break;
						}

			$imageProcess = 0;
       }
       }

if(isset($_POST["btnSupImg"])) {


			 if(unlink("images/".$_POST['image'])) {

						$id         =@$_POST["idTransaction"];
						//$catTypeCateg     =@$_POST["tab"];
						$fileNameNew='';
						$sql="update `aaa-transaction` set image=:image where idTransaction=:idTransaction";
$req = $bdd->prepare($sql);
$req->execute(array(
	'image' => $fileNameNew,
	'idTransaction' => $id
)) or die(print_r($req->errorInfo()));

				 }else {

				 }



	}

/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

/**************** Ajouter une Credit *************/

/**************** *************  *************/
?>
<div class="container">


        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter un Credit </button></center>




    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'un Credit </h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionCredit.php" onsubmit="return validerCredit();">
                             <input type="hidden" class="inputbasic" id="dateAjout" name="dateAjout" value="<?php echo $dateString2; ?> " >

							     <div class="form-group row">
									    <label for="NOM" class="col-sm-4 col-form-label"> Nom <font color="red">* </font>:</label>
							            <div class="col-sm-5">
											<input type="text" class="inputbasic" id="nomTransaction" name="nomTransaction" size="35" value="" required="required">
							            </div>
							     </div>
							     <div id="erreurNom" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>

								<div class="form-group row">
											<label for="ALIAS" class="col-sm-4 col-form-label">Alias :</label>
											<div class="col-sm-5">
												  <input type="text" class="inputbasic" id="aliasTransaction" name="aliasTransaction" size="35" value="">
											</div>
								</div>
								<div id="erreurAlias" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
							    <div class="form-group row">
							                 <div class="col-sm-6">
                                                 <input type="hidden" class="inputbasic" id="aliasTransaction" name="typeTransaction" size="35" value="Credit">
											</div>
								</div>

								<div class="modal-footer">
										<font color="red">Les champs qui ont (*) sont obligatoires</font>
										</br><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
										<button type="submit" name="inserer" class="btn btn-primary">Enregistrer</button>
							   </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    <script>
    function validerCredit() {
        var nom = document.getElementById('nomTransaction').value.trim();
        var alias = document.getElementById('aliasTransaction').value.trim();
        var erreurNom = document.getElementById('erreurNom');
        var erreurAlias = document.getElementById('erreurAlias');
        
        // Réinitialiser les messages d'erreur
        erreurNom.style.display = 'none';
        erreurNom.innerHTML = '';
        erreurAlias.style.display = 'none';
        erreurAlias.innerHTML = '';
        
        // Vérifier si le champ nom est vide
        if(nom === '') {
            erreurNom.innerHTML = 'Le nom du crédit ne peut pas être vide.';
            erreurNom.style.display = 'block';
            document.getElementById('nomTransaction').focus();
            return false;
        }
        
        // Vérifier la longueur minimale du nom
        if(nom.length < 2) {
            erreurNom.innerHTML = 'Le nom du crédit doit contenir au moins 2 caractères.';
            erreurNom.style.display = 'block';
            document.getElementById('nomTransaction').focus();
            return false;
        }
        
        // Vérifier la longueur maximale du nom
        if(nom.length > 100) {
            erreurNom.innerHTML = 'Le nom du crédit ne peut pas dépasser 100 caractères.';
            erreurNom.style.display = 'block';
            document.getElementById('nomTransaction').focus();
            return false;
        }
        
        // Vérifier la longueur maximale de l'alias
        if(alias.length > 100) {
            erreurAlias.innerHTML = 'L\'alias ne peut pas dépasser 100 caractères.';
            erreurAlias.style.display = 'block';
            document.getElementById('aliasTransaction').focus();
            return false;
        }
        
        // Vérifier les caractères autorisés (lettres, chiffres, espaces, tirets)
        var regex = /^[a-zA-Z0-9\s\-]+$/;
        if(!regex.test(nom)) {
            erreurNom.innerHTML = 'Le nom du crédit ne peut contenir que des lettres, chiffres, espaces et tirets.';
            erreurNom.style.display = 'block';
            document.getElementById('nomTransaction').focus();
            return false;
        }
        
        if(alias !== '' && !regex.test(alias)) {
            erreurAlias.innerHTML = 'L\'alias ne peut contenir que des lettres, chiffres, espaces et tirets.';
            erreurAlias.style.display = 'block';
            document.getElementById('aliasTransaction').focus();
            return false;
        }
        
        return true;
    }
    
    // Effacer les messages d'erreur lorsque l'utilisateur tape
    document.getElementById('nomTransaction').addEventListener('input', function() {
        document.getElementById('erreurNom').style.display = 'none';
    });
    
    document.getElementById('aliasTransaction').addEventListener('input', function() {
        document.getElementById('erreurAlias').style.display = 'none';
    });
    </script>

<?php
/**************** Ajouter une Credit  - - FIN *************/

/**************** *************  *************/
?>


</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/

echo'<div class="container">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">LISTE DES CREDITS </a></li>';
/*echo'<li><a data-toggle="tab" href="#SERVICE">LISTE DES CATEGORIES DE SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">LISTE DES CATEGORIES DE FRAIS</a></li>';*/
echo'</ul><div class="tab-content">';

$sql="select * from `aaa-transaction` where typeTransaction='Credit' order by idTransaction ASC";
$req = $bdd->prepare($sql);
$req->execute();
$res = $req;

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr>
     <th>ID</th>
     <th>NOM CREDIT</th>
     <th>ALIAS</th>
	 <th>TYPE</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr>
     <th>ID</th>
     <th>NOM CREDIT</th>
     <th>ALIAS</th>
	 <th>TYPE</th>
     <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if($res->rowCount() > 0){
	while($tab=$res->fetch(PDO::FETCH_ASSOC)){

		echo'<tr><td>'.$tab["idTransaction"].'</td><td>'.$tab["nomTransaction"].'</td><td>'.$tab["aliasTransaction"].'</td><td>'.$tab["typeTransaction"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idTransaction"].'" /></a>&nbsp;&nbsp;';
		echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idTransaction"].'" /></a>';

		echo '<div id="imgsup'.$tab["idTransaction"].'" class="modal fade" role="dialog">
				<div class="modal-dialog">
				   <div class="modal-content">
					   <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Confirmation Suppression Credit </h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">


                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionCredit.php">


									<div class="form-group row">
									    <label for="NOM" class="col-sm-4 col-form-label"> Nom <font color="red">* </font>:</label>
							            <div class="col-sm-5">
											<input type="text" class="inputbasic" id="nomTransaction" name="nomTransaction" size="35" value="'.$tab["nomTransaction"].'" disabled="" />
							            </div>
							     </div>

								<div class="form-group row">
											<label for="ALIAS" class="col-sm-4 col-form-label">Alias :</label>
											<div class="col-sm-5">
												  <input type="text" class="inputbasic" id="aliasTransaction" name="aliasTransaction" size="35" value="'.$tab["aliasTransaction"].'" disabled="" />
											</div>
								</div>
							    <div class="form-group row">
							                 <div class="col-sm-6">
                                                <input type="hidden" class="inputbasic" id="aliasTransaction" name="typeTransaction" size="35" value="Credit">
											</div>
								</div>

								<div class="modal-footer">
										<font color="red">Les champs qui ont (*) sont obligatoires</font>
										</br><input type="hidden" name="idTransaction" value="'.$tab["idTransaction"].'"/>
										<input type="hidden" name="supprimer" value="1" />
										<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
										<button type="submit" name="suppression" class="btn btn-danger">SUPPRIMER</button>
							   </div>

                            </form>

						</div>
				   </div>
				</div>
		</div>'.



		'<div id="imgmodifier'.$tab["idTransaction"].'"  class="modal fade " role="dialog">

			<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Modifier Credit</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">


							  <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionCredit.php">


									<div class="form-group row">
									    <label for="NOM" class="col-sm-4 col-form-label"> Nom <font color="red">* </font>:</label>
							            <div class="col-sm-5">
											<input type="text" class="inputbasic" id="nomTransaction" name="nomTransaction" size="35" value="'.$tab["nomTransaction"].'" required />
							            </div>
							     </div>

								<div class="form-group row">
											<label for="ALIAS" class="col-sm-4 col-form-label">Alias :</label>
											<div class="col-sm-5">
												  <input type="text" class="inputbasic" id="aliasTransaction" name="aliasTransaction" size="35" value="'.$tab["aliasTransaction"].'" required />
											</div>
								</div>
							    <div class="form-group row">
							                 <div class="col-sm-6">
                                                <input type="hidden" class="inputbasic" id="aliasTransaction" name="typeTransaction" size="35" value="Credit">
											</div>
								</div>

								<div class="modal-footer">

										<font color="red">Les champs qui ont (*) sont obligatoires</font>
										</br><input type="hidden" name="idTransaction" value="'.$tab["idTransaction"].'"/>
										<input type="hidden" name="modifier" value="1"/>
										<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
										<button type="submit" name="btnModifier" class="btn btn-danger">MODIFIER</button>
							   </div>

                            </form>



						</div>
					</div>
				</div>


		</div> ';?>
        	<?php
		if ($tab["image"]) {
			echo '<a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#app'.$tab["idTransaction"].'" /></a>';
			echo  '<div id="app'.$tab["idTransaction"].'"  class="modal fade " role="dialog">
					<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header" style="">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
									</div>
									<div class="modal-body" style="">
									<img src="images/'.$tab["image"].'" />
									<form   method="post" enctype="multipart/form-data">
											<input type="hidden" name="idTransaction" value="'.$tab["idTransaction"].'"/>

											<input type="hidden" name="image" value="'.$tab["image"].'"/>
											<div class="form-group" >
											<b> <b><br />
												<input type="file" name="file" />
											</div>
											<div class="form-group" align="right">
													<input type="submit" class="boutonbasic"  name="btnSupImg" value="Suprimer >>"/>
													<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Modifier >>"/>
											</div>
									</form>
									</div>
								</div>
							</div>
					</div>';
		}
		else {
			echo '<a><img src="images/iconfinder9.png" align="middle" alt="img"  data-toggle="modal" data-target="#img'.$tab["idTransaction"].'" /></a>';
			echo  '<div id="img'.$tab["idTransaction"].'"  class="modal fade " role="dialog">
					<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header" style="padding:35px 50px;">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>
									</div>
									<div class="modal-body" style="padding:40px 50px;">
											<form   method="post" enctype="multipart/form-data">
													<input type="hidden" name="idTransaction" value="'.$tab["idTransaction"].'"/>

													<div class="form-group" >
													<b> <b><br />
														<input type="file" name="file" />
													</div>
													<div class="form-group" align="right">
															<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Upload >>"/>
													</div>
											</form>
									</div>
								</div>
							</div>
					</div>';
		}

?>
		<?php
		echo'</td></tr>';
		}
	}



echo'</tbody></table><br /></div></div>';


echo'</section>'.
'<script>$(document).ready(function(){$("#imgmodifier").click(function(){$("#ModifierDesignationModal").modal();});});</script>'.
'<script>$(document).ready(function(){$("#imgsup").click(function(){$("#supprimerDesignationModal").modal();});});</script>'.
'</div></div></div></body></html>';
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
echo'</head>';
echo'</html>';
}
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="index.php"</script>';
echo'</head>';
echo'</html>';
}
?>
