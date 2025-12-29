<?php
/*
Résum� :
Commentaire :
Version : 2.1
see also :
Auteur : EL hadji mamadou korka
Date de cr�ation : 15-04-2018
Date derni�re modification :  15-04-2018
*/

session_start();

require('connection.php');
require('connectionPDO.php');

require('declarationVariables.php');

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

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

if (isset($_POST['btnEnregistrerPersonnel'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$email=htmlspecialchars(trim($_POST['email']));
		$telPortable=htmlspecialchars(trim($_POST['telPortable']));
		$matricule='';
        $idProfil    =$_POST['profil'];

        $req1=$bdd->prepare("SELECT * FROM `aaa-profil`  WHERE idProfil=:idP");  
        $req1->execute(array('idP' =>$idProfil))  or die(print_r($req1->errorInfo())); 
        $profil=$req1->fetch();

            $matricule="PER";
        
        
		if ($email) {
            $req1 = $bdd->prepare("insert into `aaa-personnel` (nomPersonnel,prenomPersonnel,emailPersonnel,profilPersonnel,telPersonnel,matriculePersonnel,created_at) values(:n,:pre,:e,:pro,:t,:m,:c)");
            $req1->execute(array(
                                'n' =>$nom,
                                'pre' =>$prenom,
                                'e' =>$email,
                                'pro' =>$idProfil,
                                't' =>$telPortable,
                                'm' =>$matricule,
                                'c' =>$dateString
                                ))  or die(print_r($req1->errorInfo()));

             $req2 = $bdd->prepare("SELECT * FROM `aaa-personnel` ORDER BY idPersonnel DESC LIMIT 0,1");     
             $req2->execute();       
  			 if ($ligne=$req2->fetch()){
					$idPersonnel = $ligne[0];
					$i=1000+$idPersonnel;
					$matricule=$matricule.$i;

                    $req3 = $bdd->prepare("update `aaa-personnel` set matriculePersonnel=:m where idPersonnel=:idP");
                    $req3->execute(array(
                            'm' => $matricule,
                            'idP' => $idPersonnel ))
                              or die(print_r($req3->errorInfo()));
                      $req3->closeCursor();

  			 } else {
  			 	echo "requette 2 sur le dernier user";
  			 }
            $req2->closeCursor();
			$message="Utilisateur ajouter avec succes";
            $req1->closeCursor();
		} else{
			$message="mot de pass different";
		}
	
}
elseif (isset($_POST['btnModifierPersonnel'])) {

    $idPersonnel =$_POST['idPersonnel'];

    $nom=$_POST['nom'];
    $prenom=$_POST['prenom'];
    $email=$_POST['email'];
    $telPortable =$_POST['telPortable'];
    
    $req3 = $bdd->prepare("update `aaa-personnel` set nomPersonnel=:n,prenomPersonnel=:p,emailPersonnel=:e,telPersonnel=:t where idPersonnel=:idP");
    $req3->execute(array(   'n' => $nom,
                            'p' => $prenom,
                            'e' => $email,
                            't' => $telPortable,
                            'idP' => $idPersonnel ))
                              or die(print_r($req3->errorInfo()));
    $req3->closeCursor();        
    
}
if (isset($_POST['btnEnregistrerUser'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$motdepasse=sha1($nom);
		$email=htmlspecialchars(trim($_POST['email']));
		$telPortable=htmlspecialchars(trim($_POST['telPortable']));
		//$email=$nom.'@'.$nom;
		$matricule='';
		/****************
		$adresse=htmlspecialchars(trim($_POST['adresse']));
		$email=htmlspecialchars(trim($_POST['email']));
		$motdepasse1=htmlspecialchars(trim($_POST['motdepasse1']));
		$motdepasse2=htmlspecialchars(trim($_POST['motdepasse2']));
		***************/
        $profil    =$_POST['profil'];
        if($profil=='Accompagnateur'){
            $matricule="AC";
        }elseif($profil=='SuperAdmin'){
            $matricule="SA";
        }elseif($profil=='Admin'){
            $matricule="AD";
        }
        elseif($profil=='Editeur catalogue'){
            $matricule="EC";
        }
        elseif($profil=='Assistant'){
            $matricule="AS";
        }elseif($profil=='Personnel'){
            $matricule="PE";
        }
        //var_dump($matricule);
		$proprietaire='';
		$gerant='';
		$caissier='';
        
		if ($email) {
			 try {
				 $sql1="insert into `aaa-utilisateur` (nom,prenom,email,profil,telPortable,motdepasse,dateinscription,back,idadmin) values
             (:nom,:prenom,:email,:profil,:telPortable,:motdepasse,:dateInscription,1,:idadmin)";
				 $req1 = $bdd->prepare($sql1);
				 $req1->execute(array(
					 'nom' => $nom,
					 'prenom' => $prenom,
					 'email' => $email,
					 'profil' => $profil,
					 'telPortable' => $telPortable,
					 'motdepasse' => $motdepasse,
					 'dateInscription' => $dateString,
					 'idadmin' => $_SESSION['iduserBack']
				 )) or die(print_r($req1->errorInfo()));

				 $sql2="SELECT * FROM `aaa-utilisateur` ORDER BY idutilisateur DESC LIMIT 0,1";
				 $req2 = $bdd->prepare($sql2);
				 $req2->execute() or die(print_r($req2->errorInfo()));
				 $utilisateur = $req2->fetch(PDO::FETCH_ASSOC);
				 
				 if ($utilisateur) {
					 $idUtilisateur = $utilisateur['idutilisateur'];
					 $i=1000+$idUtilisateur;
					 $matricule=$matricule.$i;
					 
					 $sql3="update `aaa-utilisateur` set matricule=:matricule where idutilisateur=:idUtilisateur";
					 $req3 = $bdd->prepare($sql3);
					 $req3->execute(array(
						 'matricule' => $matricule,
						 'idUtilisateur' => $idUtilisateur
					 )) or die(print_r($req3->errorInfo()));
				 } else {
					 echo "requete 2 sur le dernier user";
				 }
				 $message="Utilisateur ajouter avec succes";
			 } catch(PDOException $e) {
				 die("Erreur lors de l'insertion utilisateur : " . $e->getMessage());
			 }
		} else{
			$message="mot de pass different";
		}
	
}elseif (isset($_POST['btnModifierUser'])) {

		$idutilisateur=$_POST['idutilisateur'];

		$nom=$_POST['nom'];
		$prenom=$_POST['prenom'];
		$email=$_POST['email'];
		$proprietaire=0;
		$gerant=0;
		$caissier=0;
		$nomInitial    =$_POST['nomInitial'];
		$prenomInitial =$_POST['prenomInitial'];
		$emailInitial  =$_POST['emailInitial'];
		$profil    =$_POST['profil'];
		$profilInitial =$_POST['profilInitial'];
		$telPortable =$_POST['telPortable'];
		$telPortableInitial =$_POST['telPortableInitial'];
		
		
		
		
	if(($nom==$nomInitial)&&($prenom==$prenomInitial)&&($email==$emailInitial)&&($profil==$profilInitial)&&($telPortable==$telPortableInitial)){
	echo '<script type="text/javascript"> alert("INFO : AUCUNE MODIFICATION POUR CE PERSONNEL ...");</script>';
		}else{
		
		 

		  $sql3="UPDATE `aaa-utilisateur` set  `nom`='".$nom."',prenom='".$prenom."',email='".$email."',telPortable='".$telPortable."',profil='".$profil."' where idutilisateur=".$idutilisateur;
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
			
		}
	
}elseif (isset($_POST['btnSupprimerUser'])) {
	$idutilisateur=$_POST['idutilisateur'];

	$sql="DELETE FROM `aaa-utilisateur` WHERE idutilisateur=".$idutilisateur;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel     ".mysql_error());

  	
}
if (isset($_POST['btnCreerContrat'])) { 
    if (isset($_POST['idutilisateur'])) {
        $idutilisateur=$_POST['idutilisateur'];
        $montantSalaire=$_POST['montantSalaire'];
        //$profil=$_POST['profil'];
        $dateDebut=$_POST['dateDebut'];
        $dateFin=$_POST['dateFin'];
        $sql1="insert into `aaa-contrat` (idutilisateur,montantSalaire,dateDebut,dateFin) values('".$idutilisateur."','".$montantSalaire."','".$dateDebut."','".$dateFin."')";
        $res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );
    } else if (isset($_POST['ip'])) {
        $idPersonnel=$_POST['ip'];
        $montantSalaire=$_POST['montantSalaire'];
        $dateDebut=$_POST['dateDebut'];
        $dateFin=$_POST['dateFin'];

        $req2=$bdd->prepare("INSERT INTO `aaa-contrat` (idPersonnel,montantSalaire,dateDebut,dateFin) 
                                                 values (:idPersonnel,:montantSalaire,:dateDebut,:dateFin)");
        $req2->execute(array(
                            'idPersonnel'=>$idPersonnel,
                            'montantSalaire'=>$montantSalaire,
                            'dateDebut'=>$dateDebut,
                            'dateFin'=>$dateFin
                            ))  or die(print_r($req2->errorInfo())); 
    } 	
}
if (isset($_POST['btnModContrat'])) { 
    
        $idContrat=$_POST['ic'];
        $montantSalaire=$_POST['montantSalaire'];
        $dateDebut=$_POST['dateDebut'];
        $dateFin=$_POST['dateFin'];

        $req2=$bdd->prepare("UPDATE `aaa-contrat` set montantSalaire=:mont,
                 dateDebut=:db,dateFin=:df where idContrat=:idC ");
        $req2->execute(array(
                            'idC'=>$idContrat,
                            'mont'=>$montantSalaire,
                            'db'=>$dateDebut,
                            'df'=>$dateFin
                            ))  or die(print_r($req2->errorInfo())); 
    	
}
if (isset($_POST['btnAnnulerContrat'])) {
    
        $idContrat=$_POST['ic'];
        $annuler=1;
        
        $req2=$bdd->prepare("UPDATE `aaa-contrat` set annuler=:ann where idContrat=:idC");
        $req2->execute(array( 'ann'=>$annuler,'idC'=>$idContrat))  or die(print_r($req2->errorInfo())); 
    
}
if (isset($_POST['btnActiver'])) {
    if (isset($_POST['idutilisateur'])) {
        $idutilisateur=$_POST['idutilisateur'];
        $activer=1;
        
        $req3=$bdd->prepare("UPDATE `aaa-utilisateur` set activer=:act where idutilisateur=:id ");
        $req3->execute(array(
                            'act'=>$activer,
                            'id'=>$idutilisateur
                            ))  or die(print_r($req3->errorInfo())); 

    } else { 
        $idPersonnel=$_POST['idPersonnel'];
        $activer=1;
        
        $req3=$bdd->prepare("UPDATE `aaa-personnel` set activer=:act where idPersonnel=:id ");
        $req3->execute(array(
                            'act'=>$activer,
                            'id'=>$idPersonnel
                            ))  or die(print_r($req3->errorInfo())); 
    }
    
	
} elseif (isset($_POST['btnDesactiver'])) {
    if (isset($_POST['idutilisateur'])) {
        $idutilisateur=$_POST['idutilisateur'];
        $activer=0;
        $sql3="UPDATE `aaa-utilisateur` set  activer='".$activer."' where idutilisateur=".$idutilisateur;
        $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
    } else {
        $idPersonnel=$_POST['idPersonnel'];
        $activer=0;
        $sql3="UPDATE `aaa-personnel` set  activer='".$activer."' where idPersonnel=".$idPersonnel;
		$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
    }
}

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadContrat'])) {
    $idContrat=htmlspecialchars(trim($_POST['idC']));
    /* var_dump($idContrat);
    print_r($_FILES['fileUp']); */ 
    if(isset($_FILES['fileUp'])){
        $tmpName = $_FILES['fileUp']['tmp_name'];
        $name = $_FILES['fileUp']['name'];
        $size = $_FILES['fileUp']['size'];
        $error = $_FILES['fileUp']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['PDF','pdf'];
        $maxSize = 400000;
        /* var_dump($name );
        var_dump($extension );
        var_dump($tmpName );  */
        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, 'PiecesJointes/'.$file);

            $sql2="UPDATE `aaa-contrat` set image='".$file."' where idContrat='".$idContrat."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Bl**/
require('entetehtml.php');
?>

<body>
	
	<?php   require('header.php'); ?>
<div class="container">
	<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUser">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un utilisateur
   		</button>
           <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addPersonnel">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un personnel
   		</button>
	</div>&nbsp;&nbsp; 
	<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Ajout un Utilisateur</h4>
                    </div>
                    <div class="modal-body">
                    <form name="formulairePersonnel" method="post" action="personnel.php">

	            	  <div class="form-group">
					      <label for="inputEmail3" class="control-label">PRENOM <font color="red">*</font></label>
					      <input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom" required="">
					      <span class="text-danger" ></span>
					  </div>
					  <div class="form-group">
					      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
					      <input type="text" class="form-control" id="inputNom" name="nom" placeholder="Votre nom" required="">
					      <span class="text-danger" ></span>
					  </div>
					 <div class="form-group">
					    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
					    <input type="text" class="form-control" id="inputEmail" name="telPortable" placeholder="Votre numero de téléphone" >
					    <span class="text-danger" ></span>
					  </div>
                      <div class="form-group">
					    <label for="inputEmail3" class="control-label">Email <font color="red">*</font></label>
					    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Votre email" required="">
					    <span class="text-danger" ></span>
					  </div>
			  <?php   if ($_SESSION['profil']=="SuperAdmin") { ?>
					  <div class="form-group row">
					    <div class="col-sm-2"><label for="inputEmail3" class="control-label">Profil <font color="red">*</font></label></div>
                        <div class="col-sm-6">
						        <select name="profil" id="profil"> <?php
                                 if ($_SESSION['profil']=="SuperAdmin") {
                                    $sql0="SELECT * FROM `aaa-profil` where nomProfil !='Ingenieur'";
                                    $res0= mysql_query($sql0) or die ("utilisateur requête 2".mysql_error());
                                    
                                    while ($profil = mysql_fetch_array($res0)) {
                                        echo"<option selected value= '".$profil['nomProfil']."'>".$profil['nomProfil']."</option>";
                                    }	
                                 }else if ($_SESSION['profil']=="Admin") {
								    echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                 }
										 ?>
    							</select>
    							<input type="hidden" name="profilInitial" <?php echo  'value="'.$_SESSION['profil'].'"' ; ?> />
    						</div>
    			   	    </div>


                        <!--
                        <div class="col-sm-10">
					      	<div class="form-check">
						        <input class="form-check-input" type="checkbox" id="gridCheckProprietaire" name="proprietaire">
						        <label class="form-check-label" for="gridCheckProprietaire">
						          SuperAdmin
						        </label>
					      	</div>
						  	<div class="form-check">
							    <input class="form-check-input" type="checkbox" id="gridCheckGerant" name="gerant">
							    <label class="form-check-label" for="gridCheckGerant">
							       Admin
							     </label>
							      </div>
						    <div class="form-check">
						        <input class="form-check-input" type="checkbox" id="gridCheckCaissier" name="caissier" required="">
						        <label class="form-check-label" for="gridCheckCaissier">
						          Accompagnateur
						        </label>
						    </div>
                            <div class="form-check">
						        <input class="form-check-input" type="checkbox" id="gridCheckEditeur" name="editeur" required="">
						        <label class="form-check-label" for="gridCheckEditeur">
						          Editeur catalogue
						        </label>
						    </div>
						</div>
                        -->
					  
                       <?php } else if ($_SESSION['profil']=="Admin") { ?>
                        <div class="form-group row">
					    <div class="col-sm-2"><label for="inputEmail3" class="control-label">Profil <font color="red">*</font></label></div>
					    <div class="col-sm-10">

						    <div class="form-check">
						        <input class="form-check-input" type="checkbox" id="gridCheckCaissier" name="caissier" required="">
						        <label class="form-check-label" for="gridCheckCaissier">
						          Accompagnateur
						        </label>
						    </div>
						</div>
					  </div>

                        <?php }   ?>
					  <div class="modal-footer">
					  <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                <br><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="submit" name="btnEnregistrerUser" class="btn btn-primary">Enregistrer</button>
                       </div>
					</form>
                    </div>

                </div>
            </div>
    </div>
    <div class="modal fade" id="addPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Ajout un Personnel</h4>
                    </div>
                    <div class="modal-body">
                    <form name="formulairePersonnel" method="post" action="personnel.php">

	            	  <div class="form-group">
					      <label for="inputEmail3" class="control-label">PRENOM <font color="red">*</font></label>
					      <input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom" required="">
					      <span class="text-danger" ></span>
					  </div>
					  <div class="form-group">
					      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
					      <input type="text" class="form-control"id="inputNom" name="nom" placeholder="Votre nom" required="">
					      <span class="text-danger" ></span>
					  </div>
					 <div class="form-group">
					    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
					    <input type="text" class="form-control" id="inputEmail" name="telPortable" placeholder="Votre numero de téléphone" >
					    <span class="text-danger" ></span>
					  </div>
                      <div class="form-group">
					    <label for="inputEmail3" class="control-label">Email <font color="red">*</font></label>
					    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Votre email" required="">
					    <span class="text-danger" ></span>
					  </div>
			  <?php   if ($_SESSION['profil']=="SuperAdmin") { ?>
					  <div class="form-group row">
					    <div class="col-sm-2"><label for="inputEmail3" class="control-label">Profil <font color="red">*</font></label></div>
                        <div class="col-sm-6">
						        <select name="profil" id="profil"> <?php
                                 if ($_SESSION['profil']=="SuperAdmin") {
                                    //$sql0="SELECT * FROM `aaa-profil` where nomProfil like '%ingenieur%'";
                                    $sql0="SELECT * FROM `aaa-profil` where nomProfil like '%Personnel%'";
                                    $res0= mysql_query($sql0) or die ("utilisateur requête 2".mysql_error());
                                    
                                    while ($profil = mysql_fetch_array($res0)) {
                                        echo"<option selected value= '".$profil['idProfil']."'>".$profil['nomProfil']."</option>";
                                    }	
                                 }
										 ?>
    							</select>
    							<input type="hidden" name="profilInitial" <?php echo  'value="'.$_SESSION['profil'].'"' ; ?> />
    						</div>
    			   	    </div>
					  
                       <?php } ?>
					  <div class="modal-footer">
					  <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                <br><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="submit" name="btnEnregistrerPersonnel" class="btn btn-primary">Enregistrer</button>
                       </div>
					</form>
                    </div>

                </div>
            </div>
    </div>
		<div class="row">
			<div class="">
				
			</div>
			<div class="">
				<div class="card mt-4" style="">
                                                <div class="modal fade" id="creerContrat" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Contrat</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <input type="hidden" name="idutilisateur" id="idUCcontrat" >
                                                                     <input type="hidden" name="profil"  id="profilUCcontrat">
                                                                  </div>
                                                                  <div class="form-group">
                                                                       <label for="inputEmail3" class="control-label">Montant Salair <font color="red">*</font></label>
                                                                         <input type="number" min="0" class="form-control" id="inputprenom" name="montantSalaire" > 
                                                                  </div>
                                                                    <div class="form-group">
                                                                       <label for="inputEmail3" class="control-label">Date de debut <font color="red">*</font></label>
                                                                         <input type="date" class="form-control" id="inputprenom" name="dateDebut" > 
                                                                  </div>
                                                                    <div class="form-group">
                                                                       <label for="inputEmail3" class="control-label">Date de fin <font color="red">*</font></label>
                                                                         <input type="date" class="form-control" id="inputprenom" name="dateFin" > 
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnCreerContrat" class="btn btn-primary">Créer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="voirContrat" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Contrat de : 
                                                                        <span id="prenomUCont">  </span>
                                                                        <span id="nomUCont"> </span>
                                                                    </h2>
                                                                     <h3>Profil : <span id="profilUCont">  </span></h3>
                                                                     <h3>Montant salaire : <span id="montantUCont">  </span></h3>
                                                                     <h3>Date debut : <span id="debutUCont">  </span> </h3>
                                                                     <h3>Date fin : <span id="finUCont">  </span> </h3>
                                                                      
                                                                     
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="upNvContrat" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="padding:35px 50px;">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Contrat  de : <b><span id="prCon"></span> <span id="nCon"></span></b></h4>
                                                            </div>
                                                            <form   method="post" action="personnel.php" enctype="multipart/form-data">
                                                                <div class="modal-body" style="padding:40px 50px;">
                                                                    <input  type="text" style="display:none" name="idC" id="idCont_Upd_Nv"  />
                                                                    <div class="form-group" style="text-align:center;" >
                                                                        <div id='noUpload' ><br />                                                                                 
                                                                        </div>
                                                                        <div  id='yesUpload' >
                                                                                                                                                     
                                                                        </div>                                                                   
                                                                    </div> 
                                                                </div>
                                                                <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-success pull-left" name="btnUploadContrat"  >
                                                                            <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                        </button>
                                                                        <div id='yesBoutonTelecharger' >
                                                                           
                                                                        </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <!-- /////////////////////// DEBUT PERSONNEL INGENIEUR///////////////////////// -->
                                                    <div class="modal fade" id="creerContratPersonnel" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Contrat</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulaireVersement" method="post" action="personnel.php">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="ip" id="ip" >
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Montant Salair <font color="red">*</font></label>
                                                                            <input type="number" min="0" class="form-control" id="inputprenom" name="montantSalaire" > 
                                                                    </div>
                                                                        <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Date de debut <font color="red">*</font></label>
                                                                            <input type="date" class="form-control" id="inputprenom" name="dateDebut" > 
                                                                    </div>
                                                                        <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Date de fin <font color="red">*</font></label>
                                                                            <input type="date" class="form-control" id="inputprenom" name="dateFin" > 
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnCreerContrat" class="btn btn-primary">Créer</button>
                                                                    </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="modContratPersonnel" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Modifier Contrat</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulaireVersement" method="post" action="personnel.php">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="ic" id="ipContMod" >
                                                                        <h2>Contrat de : 
                                                                            <span id="pContMod">  </span>
                                                                            <span id="nContMod"> </span>
                                                                        </h2>
                                                                        <h3>Profil : <span id="profContMod">  </span></h3>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Montant Salair <font color="red">*</font></label>
                                                                            <input type="number" min="0" class="form-control" id="montContMod" name="montantSalaire" > 
                                                                    </div>
                                                                        <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Date de debut <font color="red">*</font></label>
                                                                            <input type="date" class="form-control" id="dDebutContMod" name="dateDebut" > 
                                                                    </div>
                                                                        <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Date de fin <font color="red">*</font></label>
                                                                            <input type="date" class="form-control" id="dFinContMod" name="dateFin" > 
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnModContrat" class="btn btn-primary">Modifier</button>
                                                                    </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="annulerContrat" tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Annulation de contrat</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulaireVersement" method="post" action="personnel.php">
                                                                    <div class="form-group">
                                                                        <h2>Voulez vous vraiment annuler ce contrat</h2>
                                                                        <input type="hidden" name="ic" id="iAnnCont">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnAnnulerContrat" class="btn btn-primary">Annulver</button>
                                                                    </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- /////////////////////// FIN PERSONNEL///////////////////////// -->

                                                <!-- <div class="modal fade" <?php echo  "id=imageNvBl".$bl['idBl'] ; ?> role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding:35px 50px;">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> BL : <b><?php echo  $bl['numeroBl'] ; ?></b></h4>
                                                    </div>
                                                    <form   method="post" enctype="multipart/form-data">
                                                        <div class="modal-body" style="padding:40px 50px;">
                                                            <input  type="text" style="display:none" name="idBl" id="idBl_Upd_Nv" <?php echo  "value=".$bl['idBl']."" ; ?> />
                                                            <div class="form-group" style="text-align:center;" >
                                                            <?php 
                                                                    if($bl['image']!=null && $bl['image']!=' '){ 
                                                                        $format=substr($bl['image'], -3);
                                                                        ?>
                                                                            <input type="file" name="file" value="<?php echo  $bl['image']; ?>" accept="image/*" id="input_file_Bl<?php echo  $bl['idBl']; ?>" onchange="showPreviewBl(event,<?php echo  $bl['idBl']; ?>);"/><br />
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <iframe id="output_pdf_Bl<?php echo  $bl['idBl']; ?>" src="./PiecesJointes/<?php echo  $bl['image']; ?>" width="100%" height="500px"></iframe>
                                                                                <img style="display:none;" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                            <?php }
                                                                            else { ?>
                                                                                <img  src="./PiecesJointes/<?php echo  $bl['image']; ?>" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                                <iframe id="output_pdf_Bl<?php echo  $bl['idBl'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                            <?php } ?>
                                                                        <?php 
                                                                    }
                                                                    else{ ?>
                                                                        <input type="file" name="file" accept="image/*" id="input_file_Bl<?php echo  $bl['idBl']; ?>" id="cover_image" onchange="showPreviewBl(event,<?php echo  $bl['idBl']; ?>);"/><br />
                                                                        <img  style="display:none;" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                        <iframe id="output_pdf_Bl<?php echo  $bl['idBl'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                                <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBl" id="btn_upload_Bl<?php echo  $bl['idBl']; ?>" >
                                                                    <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                </button>
                                                                <?php //if($bl['image']!=null && $bl['image']!=' '){ ?>
                                                                    <button type="submit" class="btn btn-primary pull-right" name="btnDownloadImg" >
                                                                    <span class="glyphicon glyphicon-download"></span> Telecharger
                                                                </button>
                                                                <?php //} ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>  -->
                                                
				  <div class="card-body">
                  <div class="container" align="center">
                    <ul class="nav nav-tabs"> 
                      <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PERSONNELS</a></li>
                      <li class=""><a data-toggle="tab" href="#LISTEACCOMPAGNATEUR">LISTE DES ACCOMPAGNATEURS</a></li>
                      <li class=""><a data-toggle="tab" href="#LISTEINGENIEUR">LISTE DES INGENIEURS</a></li>
                      <!-- <li class=""><a data-toggle="tab" href="#EDITEURS">EDITEURS CATALOGUE</a></li> -->
                      <li class=""><a data-toggle="tab" href="#ASSISTANT">ASSISTANT(E)</a></li>
                      <li class=""><a data-toggle="tab" href="#EDITEUR">EDITEUR CATALOGUE</a></li>
                      <li class=""><a data-toggle="tab" href="#ADMIN">ADMIN</a></li>
                      <li class=""><a data-toggle="tab" href="#SUPERADMIN">SUPER ADMIN</a></li>
                    </ul>
                    <div class="tab-content">
                            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                                <table id="exemple2" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>profil</th>
                                                <th>Contrat</th>
                                                <th>Piéce jointe</th>
                                                <th>Opérations</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                    </thead>	
                                    <tfoot>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>
                                                <th>Contrat</th>
                                                <th>Piece jointe</th>
                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                    </tfoot>	
                                    <tbody>
                                            <?php 
                                            if($_SESSION['profil']=="SuperAdmin"){
                                                $req9 = $bdd->prepare("SELECT * FROM `aaa-personnel`  WHERE profilPersonnel=:in");                                                     
                                            }else if($_SESSION['profil']=="Admin"){
                                                $req9=$bdd->prepare("SELECT * FROM `aaa-personnel`  WHERE profilPersonnel=:in");
                                            }   
                                                $req9->execute(array('in' =>7))  or die(print_r($req9->errorInfo())); 
                                                while ($user=$req9->fetch()){ 
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo  $user['prenomPersonnel']; ?>  </td>
                                                            <td> <?php echo  $user['nomPersonnel']; ?>  </td>
                                                            <td> <?php echo  $user['matriculePersonnel']; ?>  </td>
                                                            <td> <?php echo  $user['emailPersonnel']; ?>  </td>
                                                            <td> <?php 

                                                               $date1=$user['created_at'];

                                                                $date2 = new DateTime($date1);
                                                                //R�cup�ration de l'ann�e
                                                                $annee =$date2->format('Y');
                                                                //R�cup�ration du mois
                                                                $mois =$date2->format('m');
                                                                //R�cup�ration du jours
                                                                $jour =$date2->format('d');
                                                                $date=$jour.'-'.$mois.'-'.$annee;
                                                                echo  $date; ?> 
                                                            </td>
                                                            <td> <?php 
                                                                    $req10 = $bdd->prepare("SELECT * FROM `aaa-profil`  WHERE idProfil=:in"); 
                                                                    $req10->execute(array('in' =>$user['profilPersonnel']))  or die(print_r($req10->errorInfo())); 
                                                                    $profil=$req10->fetch();
                                                                    echo $profil['nomProfil']; 
                                                                    ?>  
                                                            </td>
                                                            
                                                            <?php if($_SESSION['profil']=="SuperAdmin") { ?> 
                                                                <td> <?php
                                                                        $req11 = $bdd->prepare("SELECT * FROM `aaa-contrat` WHERE idPersonnel=:iP order by idContrat Desc"); 
                                                                        $req11->execute(array('iP' =>$user['idPersonnel']))  or die(print_r($req11->errorInfo())); 
                                                                        $contrat=$req11->fetch();
                                                                    if ($user['activer']==1) {
                                                                        
                                                                        if($contrat==true && $contrat['annuler']==0 ){ ?>
                                                                            <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                        Voir</button>
                                                                            <?php $now=new DateTime("now"); 
                                                                                        $pass= new DateTime($contrat['dateFin']);
                                                                                        //var_dump($now);
                                                                                        //var_dump($pass);
                                                                                            if($pass <$now) {  ?>
                                                                                                <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idPersonnel'] ?>)"    >
                                                                                                    Renouveler
                                                                                            </button>
                                                                                        <?php } ?>
                                                                            <button type="button" class="glyphicon glyphicon-pencil btn btn-success" class="btn btn-success"  onclick="voirContratToMod(<?php echo $contrat['idContrat'] ?>)"    >
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger"  class="btn btn-warning" data-toggle="modal"  onclick="annulContratPop(<?php echo $contrat['idContrat'] ?>)" >
                                                                        Annuler</button>
                                                                        <?php }else{ ?>
                                                                            <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idPersonnel'] ?> )" >
                                                                            creer</button>
                                                                        <?php }
                                                                    } else {
                                                                        echo 'Pas activeted';
                                                                    }
                                                                    
                                                                    
                                                                        ?>
                                                                          
                                                                    </td>
                                                                    <td>
                                                                        <?php if($contrat && $contrat['annuler']==0 ){?>
                                                                            <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                        <?php } ?>
                                                                                <?php }
                                                                                    else { 
                                                                                ?>
                                                                                    <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        
                                                                    </td>
                                                                    <?php echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerI'.$user["idPersonnel"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                                    //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                                    if ($user["activer"]==0) { ?>
                                                                        <td><span>Desactiver</span></td>
                                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal"  <?php echo  "data-target=#ActiverI".$user['idPersonnel'] ; ?> >
                                                                        Activer</button>
                                                                        </td>
                                                                        <?php 
                                                                    } else { ?>
                                                                        <td><span>Activer</span></td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger"  class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$user['idPersonnel'] ; ?> >
                                                                        Desactiver</button>
                                                                        
                                                                        </td>
                                                                    <?php }


                                                                }else{ ?>
                                                                        <td> <?php 
                                                                                $sqlv="select * from `aaa-contrat` where idPersonnel=".$user['idPersonnel']." order by idContrat Desc";
                                                                                $resv=mysql_query($sqlv);
                                                                                $contrat = mysql_fetch_array($resv);
                                                                                if ($user['activer']==1) {
                                                                                    if($contrat){ ?>
                                                                                        <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                        Voir</button>
                                                                                        <?php $now=new DateTime("now"); 
                                                                                        $pass= new DateTime($contrat['dateFin']);
                                                                                            if($pass < $now) {  ?>
                                                                                                <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idPersonnel'] ?>  )" >
                                                                                                    Renouveler
                                                                                                </button>
                                                                                        <?php } ?>
                                                                                        
                                                                                    <?php }else{ ?>
                                                                                        <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idPersonnel'] ?>)"   >
                                                                                        creer</button>
                                                                                    <?php }
                                                                                } else {
                                                                                    echo 'Not activated';
                                                                                }
                                                                                
                                                                                
                                                                    ?>  
                                                                </td>
                                                                <td>  
                                                                    <?php if($contrat){?>
                                                                        <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                            <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td><a href="#" >
                                                                <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerI".$user['idPersonnel'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                                <?php if($user['activer']==0){ ?>

                                                                <a href="#" >
                                                                    <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerI".$user['idPersonnel'] ; ?> /></a> </td>

                                                                    <?php }else{

                                                                    //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'

                                                                    } ?>


                                                                <?php if ($user['activer']==0) { ?>
                                                                    <td><span>Desactiver</span></td>
                                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverI".$user['idutilisateur'] ; ?> >
                                                                    Activer</button>
                                                                    </td>
                                                                    <?php 
                                                                } else { ?>
                                                                    <td><span>Activer</span></td>
                                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$user['idutilisateur'] ; ?> >
                                                                    Desactiver</button></td>
                                                                <?php }
                                                        }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverI".$user['idPersonnel'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idPersonnel" <?php echo  "value=". htmlspecialchars($user['idPersonnel'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=DesactiverI".$user['idPersonnel'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idPersonnel" <?php echo  "value=". htmlspecialchars($user['idPersonnel'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerI".$user['idPersonnel']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idPersonnel" <?php echo  "value=". htmlspecialchars($user['idPersonnel'])."" ; ?> />


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $user['prenomPersonnel'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$user['prenomPersonnel']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $user['nomPersonnel'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$user['nomPersonnel']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $user['emailPersonnel'].'"  >'; ?>
                                                                        <input type="hidden" name="emailInitial" <?php echo  "value=".$user['emailPersonnel']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div> 
                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                        <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$user['telPersonnel']."" ; ?> >
                                                                        <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$user['telPersonnel']."" ; ?> >
                                                                        <span class="text-danger" ></span>
                                                                    </div>



                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                            <div class="col-sm-6">
                                                                                <select name="profil" id="profil"> <?php
                                                                                    if ($_SESSION['profil']=="SuperAdmin") {
                                                                                        //$sql0="SELECT * FROM `aaa-profil` where nomProfil like '%ingenieur%'";
                                                                                        $sql0="SELECT * FROM `aaa-profil` where nomProfil like '%Personnel%'";
                                                                                        $res0= mysql_query($sql0) or die ("utilisateur requête 2".mysql_error());
                                                                                        
                                                                                        while ($profil = mysql_fetch_array($res0)) {
                                                                                            echo"<option selected value= '".$profil['idProfil']."'>".$profil['nomProfil']."</option>";
                                                                                        }	
                                                                                    }
                                                                                        ?>
                                                                                </select>
                                                                                <!-- <select name="profil" id="profil"> <?php
                                                                                    echo'<option selected value="5">Ingenieur</option>';
                                                                                        ?>
                                                                                </select> -->

                                                                                
                                                                                <input type="hidden" name="profilInitial" <?php echo  'value="'.$_SESSION['profil'].'"' ; ?> />
                                                                            </div>
                                                                    </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerI".$user['idPersonnel']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idPersonnel"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenomPersonnel'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nomPersonnel'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $user['emailPersonnel'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                         
                                                                            echo'<option selected value="5">Ingenieur</option>';
                                                                        
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profilPersonnel'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                             </tr>
                                            <?php }
                                             ?>

                                    </tbody>
                                </table>
                          </div>
                          <div class="tab-pane fade " id="LISTEACCOMPAGNATEUR">
                            <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                <thead>
                                    <tr>
                                        <th>Prénom</th>
                                        <th>Nom</th>
                                    <!--	<th>Adresse</th> -->
                                        <th>Matricule</th>
                                        <th>Email</th>
                                        <th>Date inscription</th>
                                        <th>profil</th>

                                        <th>Opérations</th>
                                        <th>Etat</th>
                                        <th>Activer/Désactiver</th>

                                    </tr>
                                </thead>	
                                <tfoot>
                                    <tr>
                                        <th>Prénom</th>
                                        <th>Nom</th>
                                        <th>Matricule</th>
                                    <!--	<th>Adresse</th> -->
                                        <th>Email</th>
                                        <th>Date inscription</th>
                                        <th>Profil</th>

                                        <th>Opération</th>
                                        <th>Etat</th>
                                        <th>Activer/Désactiver</th>

                                    </tr>
                                </tfoot>			
                                <tbody>
                                    <?php 

                                    /*$sql4="SELECT idutilisateur,profil,activer
                                     FROM acces  
                                    WHERE profil ='Admin' OR profil ='Accompagnateur' ";
                                    $res4 = mysql_query($sql4) or die ("persoonel requête 4".mysql_error());
                                    while ($acces = mysql_fetch_array($res4)) {
                                        */
                                        if($_SESSION['profil']=="SuperAdmin"){
                                        $sql5="SELECT *
                                         FROM `aaa-utilisateur`  
                                        WHERE back =1 and profil ='Accompagnateur'";
                                        }else if($_SESSION['profil']=="Admin"){
                                        $sql5="SELECT *
                                         FROM `aaa-utilisateur`  
                                        WHERE back =1 and profil ='Accompagnateur' AND idadmin=".$_SESSION['iduserBack'];
                                        }
                                        if($res5 = mysql_query($sql5)) {
                                        while($utilisateur = mysql_fetch_array($res5)) { 
                                            ?>
                                            <tr>
                                                <td> <?php echo  $utilisateur['prenom']; ?>  </td>
                                                <td> <?php echo  $utilisateur['nom']; ?>  </td>
                                                <td> <?php echo  $utilisateur['matricule']; ?>  </td>
                                                <!--<td> <?php echo  $utilisateur['adresse']; ?>  </td>-->
                                                <td> <?php echo  $utilisateur['email']; ?>  </td>
                                                <td> <?php 

                                                  $date1=$utilisateur['dateinscription'];

                                                  $date2 = new DateTime($date1);
                                                    //R�cup�ration de l'ann�e
                                                    $annee =$date2->format('Y');
                                                    //R�cup�ration du mois
                                                    $mois =$date2->format('m');
                                                    //R�cup�ration du jours
                                                    $jour =$date2->format('d');
                                                $date=$jour.'-'.$mois.'-'.$annee;


                                                echo  $date; ?>  </td>
                                                <td> <?php echo $utilisateur['profil']; ?>  </td>

                                                <?php if($utilisateur['profil']=="SuperAdmin") {
                                                    echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPer'.$utilisateur["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                    //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                    if ($utilisateur["activer"]==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#Activer".$utilisateur['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" disabled="" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$utilisateur['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }


                                                    }else{ ?>

                                                    <td><a href="#" >
                                                    <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPer".$utilisateur['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                    <?php if($utilisateur['activer']==0){ ?>

                                                    <a href="#" >
                                                        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPer".$utilisateur['idutilisateur'] ; ?> /></a> </td>

                                                        <?php }else{

                                                        //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'

                                                         } ?>


                                                    <?php if ($utilisateur['activer']==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$utilisateur['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$utilisateur['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }



                                                    }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=Activer".$utilisateur['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($utilisateur['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=Desactiver".$utilisateur['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($utilisateur['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPer".$utilisateur['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($utilisateur['idutilisateur'])."" ; ?>>


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $utilisateur['prenom'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$utilisateur['prenom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $utilisateur['nom'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$utilisateur['nom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                <div class="form-group ">
                                                                    <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                    <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $utilisateur['email'].'"  >'; ?>
                                                                    <input type="hidden" name="emailInitial" <?php echo  "value=".$utilisateur['email']."" ; ?> />
                                                                    <span class="text-danger" ></span>
                                                                </div> 
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                    <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$utilisateur['telPortable']."" ; ?> >
                                                                    <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$utilisateur['telPortable']."" ; ?> >
                                                                    <span class="text-danger" ></span>
                                                                </div>
                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil"> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                            //echo'<option  value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                            echo'<option  value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        //echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }else{
                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$utilisateur['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierUser" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPer".$utilisateur['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($utilisateur["idutilisateur"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$utilisateur['prenom'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$utilisateur['nom'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $utilisateur['email'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" > <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                            //echo'<option  value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                            echo'<option  value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                                    }else{

                                                                                    echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$utilisateur['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                            </tr>
                                        <?php		   
                                         }
                                        } else {
                                        echo "Erreur de requête de base de données.".mysql_error();
                                        }
                                    //}

                                    ?>		
                                </tbody>			
                            </table>
                          </div>
                          <div class="tab-pane fade " id="LISTEINGENIEUR">
                                <table id="exemple2" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>profil</th>
                                                <th>Contrat</th>
                                                <th>Piéce jointe</th>
                                                <th>Opérations</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </thead>	
                                        <tfoot>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>
                                                <th>Contrat</th>
                                                <th>Piece jointe</th>
                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </tfoot>	
                                        <tbody>
                                            <?php 
                                            if($_SESSION['profil']=="SuperAdmin"){
                                                $req9 = $bdd->prepare("SELECT * FROM `aaa-personnel`  WHERE profilPersonnel=:in");                                                     
                                            }else if($_SESSION['profil']=="Admin"){
                                                $req9=$bdd->prepare("SELECT * FROM `aaa-personnel`  WHERE profilPersonnel=:in");
                                            }   
                                                $req9->execute(array('in' =>5))  or die(print_r($req9->errorInfo())); 
                                                while ($user=$req9->fetch()){ 
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo  $user['prenomPersonnel']; ?>  </td>
                                                            <td> <?php echo  $user['nomPersonnel']; ?>  </td>
                                                            <td> <?php echo  $user['matriculePersonnel']; ?>  </td>
                                                            <td> <?php echo  $user['emailPersonnel']; ?>  </td>
                                                            <td> <?php 

                                                               $date1=$user['created_at'];

                                                                $date2 = new DateTime($date1);
                                                                //R�cup�ration de l'ann�e
                                                                $annee =$date2->format('Y');
                                                                //R�cup�ration du mois
                                                                $mois =$date2->format('m');
                                                                //R�cup�ration du jours
                                                                $jour =$date2->format('d');
                                                                $date=$jour.'-'.$mois.'-'.$annee;
                                                                echo  $date; ?> 
                                                            </td>
                                                            <td> <?php 
                                                                    $req10 = $bdd->prepare("SELECT * FROM `aaa-profil`  WHERE idProfil=:in"); 
                                                                    $req10->execute(array('in' =>5))  or die(print_r($req10->errorInfo())); 
                                                                    $profil=$req10->fetch();
                                                                    echo $profil['nomProfil']; 
                                                                    ?>  
                                                            </td>
                                                            
                                                            <?php if($_SESSION['profil']=="SuperAdmin") { ?> 
                                                                <td> <?php
                                                                        $req11 = $bdd->prepare("SELECT * FROM `aaa-contrat` WHERE idPersonnel=:iP order by idContrat Desc"); 
                                                                        $req11->execute(array('iP' =>$user['idPersonnel']))  or die(print_r($req11->errorInfo())); 
                                                                        $contrat=$req11->fetch();
                                                                    if ($user['activer']==1) {
                                                                        
                                                                        if($contrat==true){ ?>
                                                                            <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                        Voir</button>
                                                                            <?php $now=new DateTime("now"); 
                                                                                        $pass= new DateTime($contrat['dateFin']);
                                                                                        //var_dump($now);
                                                                                        //var_dump($pass);
                                                                                            if($pass <$now) {  ?>
                                                                                                <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="creerContratP(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                                    Renouveler
                                                                                            </button>
                                                                            <?php } ?>
                                                                            
                                                                        <?php }else{ ?>
                                                                            <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idPersonnel'] ?> )" >
                                                                            creer</button>
                                                                        <?php }
                                                                    } else {
                                                                        echo 'Pas activeted';
                                                                    }
                                                                    
                                                                    
                                                                        ?>
                                                                          
                                                                    </td>
                                                                    <td>
                                                                        <?php if($contrat){?>
                                                                            <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                        <?php } ?>
                                                                                <?php }
                                                                                    else { 
                                                                                ?>
                                                                                    <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        
                                                                    </td>
                                                                    <?php echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerI'.$user["idPersonnel"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                                    //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                                    if ($user["activer"]==0) { ?>
                                                                        <td><span>Desactiver</span></td>
                                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal"  <?php echo  "data-target=#ActiverI".$user['idPersonnel'] ; ?> >
                                                                        Activer</button>
                                                                        </td>
                                                                        <?php 
                                                                    } else { ?>
                                                                        <td><span>Activer</span></td>
                                                                        <td><button type="button" class="btn btn-danger"  class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$user['idPersonnel'] ; ?> >
                                                                        Desactiver</button></td>
                                                                    <?php }


                                                                }else{ ?>
                                                                        <td> <?php 
                                                                                $sqlv="select * from `aaa-contrat` where idPersonnel=".$user['idPersonnel']." order by idContrat Desc";
                                                                                $resv=mysql_query($sqlv);
                                                                                $contrat = mysql_fetch_array($resv);
                                                                                if ($user['activer']==1) {
                                                                                    if($contrat){ ?>
                                                                                        <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                        Voir</button>
                                                                                        <?php $now=new DateTime("now"); 
                                                                                        $pass= new DateTime($contrat['dateFin']);
                                                                                            if($pass < $now) {  ?>
                                                                                                <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idutilisateur'] ?>  )" >
                                                                                                    Renouveler
                                                                                                </button>
                                                                                        <?php } ?>
                                                                                    <?php }else{ ?>
                                                                                        <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContratP(<?php echo $user['idutilisateur'] ?>)"   >
                                                                                        creer</button>
                                                                                    <?php }
                                                                                } else {
                                                                                    echo 'Not activated';
                                                                                }
                                                                                
                                                                                
                                                                    ?>  
                                                                </td>
                                                                <td>  
                                                                    <?php if($contrat){?>
                                                                        <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                            <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td><a href="#" >
                                                                <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerI".$user['idPersonnel'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                                <?php if($user['activer']==0){ ?>

                                                                <a href="#" >
                                                                    <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerI".$user['idPersonnel'] ; ?> /></a> </td>

                                                                    <?php }else{

                                                                    //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'

                                                                    } ?>


                                                                <?php if ($user['activer']==0) { ?>
                                                                    <td><span>Desactiver</span></td>
                                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverI".$user['idutilisateur'] ; ?> >
                                                                    Activer</button>
                                                                    </td>
                                                                    <?php 
                                                                } else { ?>
                                                                    <td><span>Activer</span></td>
                                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$user['idutilisateur'] ; ?> >
                                                                    Desactiver</button></td>
                                                                <?php }
                                                        }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverI".$user['idPersonnel'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idPersonnel" <?php echo  "value=". htmlspecialchars($user['idPersonnel'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=DesactiverI".$user['idPersonnel'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idPersonnel" <?php echo  "value=". htmlspecialchars($user['idPersonnel'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerI".$user['idPersonnel']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idPersonnel" <?php echo  "value=". htmlspecialchars($user['idPersonnel'])."" ; ?> />


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $user['prenomPersonnel'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$user['prenomPersonnel']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $user['nomPersonnel'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$user['nomPersonnel']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                <div class="form-group ">
                                                                    <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                    <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $user['emailPersonnel'].'"  >'; ?>
                                                                    <input type="hidden" name="emailInitial" <?php echo  "value=".$user['emailPersonnel']."" ; ?> />
                                                                    <span class="text-danger" ></span>
                                                                </div> 
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                    <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$user['telPersonnel']."" ; ?> >
                                                                    <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$user['telPersonnel']."" ; ?> >
                                                                    <span class="text-danger" ></span>
                                                                </div>



                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil"> <?php
                                                                         
                                                                            echo'<option selected value="5">Ingenieur</option>';
                                                                         
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profilPersonnel'].'"' ; ?> />
                                                                                </div>
                                                                            </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierUser" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerI".$user['idPersonnel']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idPersonnel"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenomPersonnel'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nomPersonnel'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $user['emailPersonnel'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                         
                                                                            echo'<option selected value="5">Ingenieur</option>';
                                                                        
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profilPersonnel'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                             </tr>
                                            <?php }
                                             ?>

                                        </tbody>
                                    </table>
                          </div>
                          <div class="tab-pane fade " id="ASSISTANT">
                                <table id="exemple3" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>profil</th>
                                                <th>Contrat</th>
                                                <th>Piece jointe</th>
                                                <th>Opérations</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </thead>	
                                        <tfoot>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>
                                                <th>Contrat</th>
                                                <th>Piece jointe</th>
                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </tfoot>	
                                        <tbody>
                                            <?php 
                                                /* if($_SESSION['profil']=="SuperAdmin"){
                                           			 $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Editeur catalogue'";
		                                        }else if($_SESSION['profil']=="Admin"){
		                                             $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Editeur catalogue' AND idadmin=".$_SESSION['iduserBack'];
		                                        }   */ 
		                                         if($_SESSION['profil']=="SuperAdmin"){
			                                            $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Assistant'";
			                                        }else if($_SESSION['profil']=="Admin"){
			                                             $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Assistant' AND idadmin=".$_SESSION['iduserBack'];
			                                        }    
                                          
                                             if($res10 = mysql_query($sql10)) {
                                                while ($user = mysql_fetch_array($res10)){ 
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo  $user['prenom']; ?>  </td>
                                                            <td> <?php echo  $user['nom']; ?>  </td>
                                                            <td> <?php echo  $user['matricule']; ?>  </td>
                                                            <td> <?php echo  $user['email']; ?>  </td>
                                                            <td> <?php 

                                                              $date1=$user['dateinscription'];

                                                              $date2 = new DateTime($date1);
                                                                //R�cup�ration de l'ann�e
                                                                $annee =$date2->format('Y');
                                                                //R�cup�ration du mois
                                                                $mois =$date2->format('m');
                                                                //R�cup�ration du jours
                                                                $jour =$date2->format('d');
                                                            $date=$jour.'-'.$mois.'-'.$annee;
                                                            echo  $date; ?>  </td>
                                                            <td> <?php echo $user['profil']; ?>  </td>
                                                            <?php if($user['profil']=="SuperAdmin") { ?> 
                                                                <td> <?php 
                                                                        $sqlv="select * from `aaa-contrat` where idUtilisateur=".$user['idutilisateur']."";
                                                                        $resv=mysql_query($sqlv);
                                                                        $contrat = mysql_fetch_array($resv);

                                                                        if ($user['activer']==1) {
                                                                            if($contrat  ){ ?>
                                                                                <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                 VoirXXXX</button>
                                                                                <?php $now=new DateTime("now"); 
                                                                                            $pass= new DateTime($contrat['dateFin']);
                                                                                            //var_dump($now);
                                                                                            //var_dump($pass);
                                                                                                if($pass <$now) {  ?>
                                                                                                    <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>  )" >
                                                                                                        Renouveler
                                                                                                    </button>
                                                                                <?php } ?>
                                                                                
                                                                            <?php }else{ ?>
                                                                                <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>)" >
                                                                                creer</button>
                                                                            <?php }
                                                                        } else {
                                                                            echo 'Pas activer';
                                                                        }
                                                                        ?>  
                                                                    </td>
                                                                    <td>
                                                                        <?php if($contrat){?>
                                                                            <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                            <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                        <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php
                                                                
                                                                echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerA'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                                //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                                if ($user["activer"]==0) { ?>
                                                                    <td><span>Desactiver</span></td>
                                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#ActiverE".$user['idutilisateur'] ; ?> >
                                                                    Activer</button>
                                                                    </td>
                                                                    <?php 
                                                                } else { ?>
                                                                    <td><span>Activer</span></td>
                                                                    <td><button type="button" class="btn btn-danger" disabled="" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverE".$user['idutilisateur'] ; ?> >
                                                                    Desactiver</button></td>
                                                                <?php }
                                                             }else{ ?>
                                                                <td> <?php 
                                                                        $sqlv="select * from `aaa-contrat` where idUtilisateur=".$user['idutilisateur']."";
                                                                        $resv=mysql_query($sqlv);
                                                                        $contrat = mysql_fetch_array($resv);

                                                                        if ($user["activer"]==1) {
                                                                            if($contrat){ ?>
                                                                                <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                 Voir</button>
                                                                                <?php $now=new DateTime("now"); 
                                                                                $pass= new DateTime($contrat['dateFin']);
                                                                                    if($pass < $now) {  ?>
                                                                                        <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>  )" >
                                                                                                    Renouveler
                                                                                                </button>
                                                                                <?php } ?>
                                                                            <?php }else{ ?>
                                                                                <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>)"  >
                                                                                creer</button>
                                                                            <?php }
                                                                        } else {
                                                                            echo 'Not activated';
                                                                        }
                                                                    ?>  
                                                                </td>
                                                                <td>
                                                                    <?php if($contrat){?>
                                                                        <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                            <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                        <?php } ?>
                                                                        <?php } ?>
                                                                </td>
                                                                <td><a href="#" >
                                                                <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerA".$user['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 
                                                                <?php if($user['activer']==0){ ?>
                                                                <a href="#" >
                                                                    <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerA".$user['idutilisateur'] ; ?> /></a> </td>
                                                                    <?php }else{
                                                                    //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'
                                                                    } ?>
                                                                <?php if ($user['activer']==0) { ?>
                                                                    <td><span>Desactiver</span></td>
                                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverA".$user['idutilisateur'] ; ?> >
                                                                    Activer</button>
                                                                    </td>
                                                                    <?php 
                                                                } else { ?>
                                                                    <td><span>Activer</span></td>
                                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverA".$user['idutilisateur'] ; ?> >
                                                                    Desactiver</button></td>
                                                                <?php } 
                                                            }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverA".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=DesactiverA".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerA".$user['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?>>


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $user['prenom'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$user['prenom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $user['nom'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$user['nom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                <div class="form-group ">
                                                                    <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                    <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $user['email'].'"  >'; ?>
                                                                    <input type="hidden" name="emailInitial" <?php echo  "value=".$user['email']."" ; ?> />
                                                                    <span class="text-danger" ></span>
                                                                </div> 
                                                                 <div class="form-group">
                                                                    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                    <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <span class="text-danger" ></span>
                                                                </div>
                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil"> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                            //echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                            echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        //echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }else{

                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierUser" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerA".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idutilisateur"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenom'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nom'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $user['email'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                        if ($_SESSION['profil']=="SuperAdmin") {
                                                                             if ($utilisateur['profil']=="Admin"){
                                                                                            //echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                            echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                       // echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }else{
                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                                        </tr>
                                                    <?php }
                                             }?>

                                        </tbody>
                                    </table>
                          </div>
                          <div class="tab-pane fade " id="EDITEUR">
                                <table id="exemple6" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>
                                                <th>Contrat</th>
                                                <th>Piece jointe</th>
                                                <th>Opérations</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </thead>	
                                        <tfoot>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>
                                                <th>Contrat</th>
                                                <th>Piece jointe</th>
                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </tfoot>	
                                        <tbody>
                                            <?php 
                                                /* if($_SESSION['profil']=="SuperAdmin"){
                                           			 $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Editeur catalogue'";
		                                        }else if($_SESSION['profil']=="Admin"){
		                                             $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Editeur catalogue' AND idadmin=".$_SESSION['iduserBack'];
		                                        }   */ 
		                                         if($_SESSION['profil']=="SuperAdmin"){
			                                            $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Editeur catalogue'";
			                                        }else if($_SESSION['profil']=="Admin"){
			                                             $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Editeur catalogue' AND idadmin=".$_SESSION['iduserBack'];
			                                        }    
                                          
                                             if($res10 = mysql_query($sql10)) {
                                                while ($user = mysql_fetch_array($res10)){ 
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo  $user['prenom']; ?>  </td>
                                                            <td> <?php echo  $user['nom']; ?>  </td>
                                                            <td> <?php echo  $user['matricule']; ?>  </td>
                                                            <td> <?php echo  $user['email']; ?>  </td>
                                                            <td> <?php 

                                                              $date1=$user['dateinscription'];

                                                              $date2 = new DateTime($date1);
                                                                //R�cup�ration de l'ann�e
                                                                $annee =$date2->format('Y');
                                                                //R�cup�ration du mois
                                                                $mois =$date2->format('m');
                                                                //R�cup�ration du jours
                                                                $jour =$date2->format('d');
                                                            $date=$jour.'-'.$mois.'-'.$annee;
                                                            echo  $date; ?>  </td>
                                                            <td> <?php echo $user['profil']; ?>  
                                                            </td>
                                                            <?php if($user['profil']=="SuperAdmin") {  
                                                                ?> 
                                                                <td> <?php 
                                                                        $sqlv="select * from `aaa-contrat` where idUtilisateur=".$user['idutilisateur']."";
                                                                        $resv=mysql_query($sqlv);
                                                                        $contrat = mysql_fetch_array($resv);
                                                                        if ($user['activer']==1) {
                                                                            if($contrat  ){ ?>
                                                                                <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                                     Voir</button>
                                                                                <?php $now=new DateTime("now"); 
                                                                                            $pass= new DateTime($contrat['dateFin']);
                                                                                            //var_dump($now);
                                                                                            //var_dump($pass);
                                                                                                if($pass <$now) {  ?>
                                                                                                    <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>  )" >
                                                                                                        Renouveler
                                                                                                    </button>
                                                                                <?php } ?>
                                                                                
                                                                            <?php }else{ ?>
                                                                                <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>)" >
                                                                                creer</button>
                                                                            <?php }
                                                                        } else {
                                                                            echo 'Pas activer';
                                                                        }
                                                                        ?>  
                                                                    </td>
                                                                    <td>
                                                                        <?php if($contrat){?>
                                                                            <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                            <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                        <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php
                                                            echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerEC'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                            //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                            if ($user["activer"]==0) { ?>
                                                                <td><span>Desactiver</span></td>
                                                                <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#ActiverEC".$user['idutilisateur'] ; ?> >
                                                                Activer</button>
                                                                </td>
                                                                <?php 
                                                            } else { ?>
                                                                <td><span>Activer</span></td>
                                                                <td><button type="button" class="btn btn-danger" disabled="" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverEC".$user['idutilisateur'] ; ?> >
                                                                Desactiver</button></td>
                                                            <?php }


                                                    }else{ ?>
                                                        <td> <?php 
                                                                $sqlv="select * from `aaa-contrat` where idUtilisateur=".$user['idutilisateur']."";
                                                                $resv=mysql_query($sqlv);
                                                                $contrat = mysql_fetch_array($resv);
                                                                if ($user["activer"]==1) {
                                                                    if($contrat){ ?>
                                                                        <button type="button" class="btn btn-primary" class="btn btn-success"  onclick="voirContrat(<?php echo $contrat['idContrat'] ?>)"    >
                                                                            Voir</button>
                                                                        <?php $now=new DateTime("now"); 
                                                                        $pass= new DateTime($contrat['dateFin']);
                                                                            if($pass < $now) {  ?>
                                                                                <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>  )" >
                                                                                                    Renouveler
                                                                                                </button>
                                                                        <?php } ?>
                                                                    <?php }else{ ?>
                                                                        <button type="button" class="btn btn-success" class="btn btn-success"  onclick="creerContrat(<?php echo $user['idutilisateur'] ?>)" >
                                                                            creer</button>
                                                                    <?php }
                                                                } else {
                                                                    echo 'Not activated';
                                                                }
                                                            ?>  
                                                        </td>
                                                        <td>
                                                                 <?php if($contrat){?>
                                                                        <?php if($contrat['image']!=null && $contrat['image']!=' '){
                                                                                $format=substr($contrat['image'], -3); ?>
                                                                                    <?php if($format=='pdf'){ ?>
                                                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                    <?php }
                                                                                        else { 
                                                                                            ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                                            <?php } ?>
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upContratPopup(<?php echo $contrat['idContrat']; ?>)" 	 />
                                                                        <?php } ?>
                                                                        <?php } ?>
                                                        </td>
                                                    <td><a href="#" >
                                                    <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerEC".$user['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                    <?php if($user['activer']==0){ ?>
                                                    <a href="#" >
                                                        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerEC".$user['idutilisateur'] ; ?> /></a> </td>
                                                    <?php }else{
                                                        //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'
                                                         } ?>
                                                    <?php if ($user['activer']==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverEC".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverEC".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }
                                                    }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverEC".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=DesactiverEC".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerEC".$user['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?>>


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $user['prenom'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$user['prenom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $user['nom'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$user['nom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                <div class="form-group ">
                                                                    <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                    <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $user['email'].'"  >'; ?>
                                                                    <input type="hidden" name="emailInitial" <?php echo  "value=".$user['email']."" ; ?> />
                                                                    <span class="text-danger" ></span>
                                                                </div> 
                                                                 <div class="form-group">
                                                                    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                    <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <span class="text-danger" ></span>
                                                                </div>
                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil"> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                            //echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                            echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        //echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }else{

                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierUser" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerEC".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idutilisateur"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenom'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nom'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $user['email'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                        if ($_SESSION['profil']=="SuperAdmin") {
                                                                             if ($utilisateur['profil']=="Admin"){
                                                                                            //echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                            echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                       // echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }else{
                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Assistant">Assistant</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                                        </tr>
                                                    <?php }
                                             }?>

                                        </tbody>
                                    </table>
                          </div>
                          <div class="tab-pane fade " id="ADMIN">
                                <table id="exemple4" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>profil</th>

                                                <th>Opérations</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </thead>	
                                        <tfoot>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>

                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </tfoot>	
                                        <tbody>
                                            <?php 
                                                 if($_SESSION['profil']=="SuperAdmin"){
                                            $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Admin'";
                                        }else if($_SESSION['profil']=="Admin"){
                                             $sql10="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Admin' AND idadmin=".$_SESSION['iduserBack'];
                                        }    
                                             if($res10 = mysql_query($sql10)) {
                                                while ($user = mysql_fetch_array($res10)){ 
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo  $user['prenom']; ?>  </td>
                                                            <td> <?php echo  $user['nom']; ?>  </td>
                                                            <td> <?php echo  $user['matricule']; ?>  </td>
                                                            <td> <?php echo  $user['email']; ?>  </td>
                                                            <td> <?php 

                                                              $date1=$user['dateinscription'];

                                                              $date2 = new DateTime($date1);
                                                                //R�cup�ration de l'ann�e
                                                                $annee =$date2->format('Y');
                                                                //R�cup�ration du mois
                                                                $mois =$date2->format('m');
                                                                //R�cup�ration du jours
                                                                $jour =$date2->format('d');
                                                            $date=$jour.'-'.$mois.'-'.$annee;


                                                            echo  $date; ?>  </td>
                                                            <td> <?php echo $user['profil']; ?>  </td>
                                                            <?php if($user['profil']=="SuperAdmin") {
                                                    echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerA'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                    //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                    if ($user["activer"]==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#ActiverA".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" disabled="" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverA".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }


                                                    }else{ ?>

                                                    <td><a href="#" >
                                                    <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerA".$user['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                    <?php if($user['activer']==0){ ?>

                                                    <a href="#" >
                                                        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerA".$user['idutilisateur'] ; ?> /></a> </td>

                                                        <?php }else{

                                                        //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'

                                                         } ?>


                                                    <?php if ($user['activer']==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverA".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverA".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }



                                                    }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverA".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=DesactiverA".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerA".$user['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?>>


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $user['prenom'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$user['prenom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $user['nom'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$user['nom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                <div class="form-group ">
                                                                    <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                    <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $user['email'].'"  >'; ?>
                                                                    <input type="hidden" name="emailInitial" <?php echo  "value=".$user['email']."" ; ?> />
                                                                    <span class="text-danger" ></span>
                                                                </div> 
                                                                 <div class="form-group">
                                                                    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                    <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <span class="text-danger" ></span>
                                                                </div>
                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil"> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                        //echo'<option  value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                        echo'<option  value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        //echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }else{
                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option  value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option  value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierUser" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerA".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idutilisateur"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenom'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nom'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $user['email'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                        //echo'<option  value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                        echo'<option  value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option>';
                                                                                    }
                                                                            } ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                                        </tr>
                                                    <?php }
                                             }?>

                                        </tbody>
                                    </table>
                          </div>
                          <div class="tab-pane fade " id="SUPERADMIN">
                                <table id="exemple5" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Matricule</th>
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>profil</th>

                                                <th>Opérations</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </thead>	
                                        <tfoot>
                                            <tr>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Matricule</th>
                                            <!--	<th>Adresse</th> -->
                                                <th>Email</th>
                                                <th>Date inscription</th>
                                                <th>Profil</th>

                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </tfoot>	
                                        <tbody>
                                            <?php 
                                                 if($_SESSION['profil']=="SuperAdmin"){
                                            $sql11="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='SuperAdmin'";
                                        }else if($_SESSION['profil']=="Admin"){
                                             $sql11="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='SuperAdmin' AND idadmin=".$_SESSION['iduserBack'];
                                        }    
                                             if($res11 = mysql_query($sql11)) {
                                                while ($user = mysql_fetch_array($res11)){ 
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo  $user['prenom']; ?>  </td>
                                                            <td> <?php echo  $user['nom']; ?>  </td>
                                                            <td> <?php echo  $user['matricule']; ?>  </td>
                                                            <td> <?php echo  $user['email']; ?>  </td>
                                                            <td> <?php 

                                                              $date1=$user['dateinscription'];

                                                              $date2 = new DateTime($date1);
                                                                //R�cup�ration de l'ann�e
                                                                $annee =$date2->format('Y');
                                                                //R�cup�ration du mois
                                                                $mois =$date2->format('m');
                                                                //R�cup�ration du jours
                                                                $jour =$date2->format('d');
                                                            $date=$jour.'-'.$mois.'-'.$annee;


                                                            echo  $date; ?>  </td>
                                                            <td> <?php echo $user['profil']; ?>  </td>
                                                            <?php if($user['profil']=="SuperAdmin") {
                                                    echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerS'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a>';

                                                   echo' &nbsp;&nbsp; <a href="#"  data-target=#upDateProfil'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal">Update</a></td>';
                                                    //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                    if ($user["activer"]==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal"  <?php echo  "data-target=#ActiverS".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger"  class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverS".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }


                                                    }else{ ?>

                                                    <td><a href="#" >
                                                    <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerS".$user['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; AAA

                                                    <?php if($user['activer']==0){ ?>

                                                    <a href="#" >
                                                        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerS".$user['idutilisateur'] ; ?> /></a> BBB </td>

                                                        <?php }else{

                                                        echo 'CCC<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>';

                                                         } ?>
                                                    <?php if ($user['activer']==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#ActiverS".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#DesactiverS".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }



                                                    }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverS".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment activer ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" <?php echo  "id=DesactiverS".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Voulez vous vraiment desactiver ce personnel</h2>
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerS".$user['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?>>


                                                                    <div class="form-group">
                                                                        <label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Votre prenom"  value="'. $user['prenom'].'"  >'; ?>
                                                                        <input type="hidden" name="prenomInitial" <?php echo  "value=".$user['prenom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" required="" placeholder="Votre prenom"  value="'. $user['nom'].'"  >'; ?>
                                                                        <input type="hidden" name="nomInitial" <?php echo  "value=".$user['nom']."" ; ?> />
                                                                        <span class="text-danger" ></span>
                                                                    </div>

                                                                <div class="form-group ">
                                                                    <label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
                                                                    <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" required="" placeholder="Email"  value="'. $user['email'].'"  >'; ?>
                                                                    <input type="hidden" name="emailInitial" <?php echo  "value=".$user['email']."" ; ?> />
                                                                    <span class="text-danger" ></span>
                                                                </div> 
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="control-label">Téléphone <font color="red">*</font></label>
                                                                    <input type="text" class="form-control" id="inputEmail" name="telPortable" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <input type="hidden" class="form-control" id="inputEmail" name="telPortableInitial" <?php echo  "value=".$user['telPortable']."" ; ?> >
                                                                    <span class="text-danger" ></span>
                                                                </div>



                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil"> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                      //echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                      echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                       //echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                       echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }else{
                                                                                    	//echo'<option selected value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option selected value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>

                                                                    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                        <br>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnModifierUser" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerS".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Supprimer un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idutilisateur"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenom'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nom'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">EMAIL</label>					    
                                                                        <?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $user['email'].'" disabled >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div> 


                                                                    <div class="form-group ">
                                                                        <div class=""><label for="inputEmail3" class="control-label">Profil<font color="red">*</font></label></div> <br>

                                                                        <div class="col-sm-6">
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                        //echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                        echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        //echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }else{
                                                                                    	//echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    	echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Assistant">Assistant(e)</option';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Accompagnateur">Accompagnateur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
                                                                            </div>


                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerUser" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!------------------------>
                                               <!----------------------------------------------------------->
                                                <div <?php echo  "id=upDateProfil".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Update un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
                                                                    <input type="hidden" name="idutilisateur" <?php echo  'value="'. htmlspecialchars($user["idutilisateur"]).'"' ; ?> >
                                                                    <label for="inputEmail3" class="control-label">PRENOM</label>					    
                                                                    <?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'.$user['prenom'].'" disabled >'; ?>
                                                                    <span class="text-danger" ></span>
                                                                    <div class="form-group ">
                                                                        <label for="inputEmail3" class="control-label">NOM</label>
                                                                        <?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'.$user['nom'].'"  >'; ?>
                                                                        <span class="text-danger" ></span>
                                                                    </div>
                                                                    
                                                                    <div class="modal-footer row">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                                        <button type="submit" name="btnSupprimerPersonnel" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!----------------------------------------------------------->
                                                        </tr>
                                                    <?php }
                                             }?>

                                        </tbody>
                                    </table>
                          </div>
                   
                   
                      </div>

                  </div>
             </div>
					
				</div>
			</div>
		</div>
</div>
		
</body>
</html>