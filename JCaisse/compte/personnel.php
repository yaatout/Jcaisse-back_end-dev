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

require('declarationVariables.php');

if(!$_SESSION['iduserBack'])
	header('Location:index.php');
elseif (isset($_POST['btnEnregistrerPersonnel'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$motdepasse=sha1($nom);
		$email=htmlspecialchars(trim($_POST['email']));
		$telPortable=htmlspecialchars(trim($_POST['telPortable']));
		//$email=$nom.'@'.$nom;

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
        }elseif($profil=='Editeur catalogue'){
            $matricule="EC";
        }elseif($profil=='Ingenieur'){
            $matricule="IN";
        }
		$proprietaire='';
		$gerant='';
		$caissier='';
        
		if ($email) {
			 $sql1="insert into `aaa-utilisateur` (nom,prenom,email,profil,telPortable,motdepasse,dateinscription,back,idadmin) values('".$nom."','".$prenom."','".$email."','".$profil."','".$telPortable."','".$motdepasse."','".$dateString."',1,".$_SESSION['iduserBack'].")";
  			 $res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );

			 $sql2="SELECT * FROM `aaa-utilisateur` ORDER BY idutilisateur DESC LIMIT 0,1";
  			 if ($res2=mysql_query($sql2)){
					$ligne = mysql_fetch_row($res2);
					$idUtilisateur = $ligne[0];
					$i=1000+$idUtilisateur;
					$matricule=$matricule.$i;
					$sql3="update `aaa-utilisateur` set matricule='".$matricule."' where idutilisateur=".$idUtilisateur;
  					$req3=@mysql_query($sql3) or die ("modification matricule impossible");

  			 } else {
  			 	echo "requette 2 sur le dernier user";
  			 }
			$message="Utilisateur ajouter avec succes";
		} else{
			$message="mot de pass different";
		}
	
}elseif (isset($_POST['btnModifierPersonnel'])) {

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
	
}elseif (isset($_POST['btnSupprimerPersonnel'])) {
	$idutilisateur=$_POST['idutilisateur'];

	$sql="DELETE FROM `aaa-utilisateur` WHERE idutilisateur=".$idutilisateur;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel     ".mysql_error());

  	
}
if (isset($_POST['btnCreerContrat'])) {
    
	$idutilisateur=$_POST['idutilisateur'];
	$montantSalaire=$_POST['montantSalaire'];
	$profil=$_POST['profil'];
	$dateDebut=$_POST['dateDebut'];
	$dateFin=$_POST['dateFin'];

	 $sql1="insert into `aaa-contrat` (idutilisateur,montantSalaire,profil,dateDebut,dateFin) values('".$idutilisateur."','".$montantSalaire."','".$profil."','".$dateDebut."','".$dateFin."')";
    $res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );

  	
}
if (isset($_POST['btnActiver'])) {
	$idutilisateur=$_POST['idutilisateur'];
	$activer=1;
	$sql3="UPDATE `aaa-utilisateur` set  activer='".$activer."' where idutilisateur=".$idutilisateur;
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
} elseif (isset($_POST['btnDesactiver'])) {
	$idutilisateur=$_POST['idutilisateur'];
	$activer=0;
	$sql3="UPDATE `aaa-utilisateur` set  activer='".$activer."' where idutilisateur=".$idutilisateur;
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
require('entetehtml.php');
?>

<body>
	
	<?php   require('header.php'); ?>
<div class="container">
	<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPersonnel">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un membre
   		</button>
	</div>&nbsp;&nbsp; 
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
									if($user['profil']=="SuperAdmin"){
													echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option>
                                                    <option value= "Editeur catalogue">Editeur catalogue</option>
                                                    <option value= "Ingenieur">Editeur catalogue</option>';
											}else if ($user['profil']=="Admin"){
												echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>
                                                    <option value= "Ingenieur">Ingenieur</option>';
											}else{

											 echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>
                                                    <option value= "Ingenieur">Ingenieur</option>';
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
				  
				  <div class="card-body">
                  <div class="container" align="center">
                    <ul class="nav nav-tabs"> 
                      <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES ACCOMPAGNATEURS</a></li>
                      <li class=""><a data-toggle="tab" href="#LISTEINGENIEUR">LISTE DES INGENIEURS</a></li>
                      <li class=""><a data-toggle="tab" href="#EDITEURS">EDITEURS CATALOGUE</a></li>
                      <li class=""><a data-toggle="tab" href="#ADMIN">ADMIN</a></li>
                      <li class=""><a data-toggle="tab" href="#SUPERADMIN">SUPER ADMIN</a></li>
                    </ul>
                    <div class="tab-content">
                          <div class="tab-pane fade in active" id="LISTEPERSONNEL">
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
                                                                                            echo'<option  value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    }else{

                                                                                    echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
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
                                                                        <button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
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
                                                                         <select name="profil" id="profil" disabled=""> <?php
                                                                         if ($_SESSION['profil']=="SuperAdmin") {
                                                                            if($utilisateur['profil']=="SuperAdmin"){
                                                                                            echo'<option  value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
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
                                                                        <button type="submit" name="btnSupprimerPersonnel" class="btn btn-primary">Supprimer</button>
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

                                                <th>Opération</th>
                                                <th>Etat</th>
                                                <th>Activer/Désactiver</th>

                                            </tr>
                                        </tfoot>	
                                        <tbody>
                                            <?php 
                                                 if($_SESSION['profil']=="SuperAdmin"){
                                            $sql9="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Ingenieur'";
                                        }else if($_SESSION['profil']=="Admin"){
                                             $sql9="SELECT * FROM `aaa-utilisateur`  WHERE back =1 and profil='Ingenieur' AND idadmin=".$_SESSION['iduserBack'];
                                        }    
                                             if($res9 = mysql_query($sql9)) {
                                                while ($user = mysql_fetch_array($res9)){ 
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
                                                                    if($contrat){ ?>
                                                                        <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#voir".$user['idutilisateur'] ; ?> >
                                                                        Voir</button>
                                                                    <?php }else{ ?>
                                                                        <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#ActiverCR".$user['idutilisateur'] ; ?> >
                                                                        creer</button>
                                                                    <?php }
                                                                ?>  
                                                            </td>
                                                    <?php echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerI'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

                                                    //echo' &nbsp;&nbsp; <a href="#"> <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a></td>';

                                                    if ($user["activer"]==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" disabled="" <?php echo  "data-target=#ActiverI".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" disabled="" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }


                                                    }else{ ?>
                                                            <td> <?php 
                                                                    $sqlv="select * from `aaa-contrat` where idUtilisateur=".$user['idutilisateur']."";
                                                                    $resv=mysql_query($sqlv);
                                                                    $contrat = mysql_fetch_array($resv);
                                                                    if($contrat){ ?>
                                                                        <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal"  <?php echo  "data-target=#voir".$user['idutilisateur'] ; ?> >
                                                                        Voir</button>
                                                                    <?php }else{ ?>
                                                                        <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal"  <?php echo  "data-target=#ActiverCR".$user['idutilisateur'] ; ?> >
                                                                        creer</button>
                                                                    <?php }
                                                                ?>  
                                                            </td>
                                                    <td><a href="#" >
                                                    <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerI".$user['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                    <?php if($user['activer']==0){ ?>

                                                    <a href="#" >
                                                        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerI".$user['idutilisateur'] ; ?> /></a> </td>

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
                                                <div class="modal fade" <?php echo  "id=ActiverI".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
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
                                                <div class="modal fade" <?php echo  "id=DesactiverI".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                <!-- CONTRAT -->
                                                <div class="modal fade" <?php echo  "id=ActiverCR".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Contrat</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> >
                                                                     <input type="hidden" name="profil" <?php echo  "value=". htmlspecialchars($user['profil'])."" ; ?> >
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
                                                 <div class="modal fade" <?php echo  "id=voir".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="personnel.php">
                                                                  <div class="form-group">
                                                                     <h2>Contrat de : <?php echo $user['prenom'] ; ?> </h2>
                                                                     <h3>Montant salaire : <?php echo $contrat['montantSalaire'] ; ?> </h3>
                                                                     <h3>Date debut : <?php echo $contrat['dateDebut'] ; ?> </h3>
                                                                     <h3>Date fin : <?php echo $contrat['dateFin'] ; ?> </h3>
                                                                      
                                                                     
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        
                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->
                                                <!----------------------------------------------------------->								
                                                <div <?php echo  "id=imgmodifierPerI".$user['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Formulaire pour modifier un personnel</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaire2" method="post" action="personnel.php">

                                                                    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($user['idutilisateur'])."" ; ?> />


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
                                                                            if($user['profil']=="SuperAdmin"){
                                                                                            echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                                    }else if ($user['profil']=="Admin"){
                                                                                        echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                                    }else{

                                                                                    echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
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
                                                <div <?php echo  "id=imgsuprimerPerI".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
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
                                                                            if($user['profil']=="SuperAdmin"){
                                                                                            echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                                    }else if ($user['profil']=="Admin"){
                                                                                        echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                                    }else{

                                                                                    echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                                    }
                                                                         }else if ($_SESSION['profil']=="Admin") {
                                                                            echo'<option selected value= "Ingenieur">Ingenieur</option>';
                                                                         }
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
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
                          <div class="tab-pane fade " id="EDITEURS">
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
                                                            <td> <?php echo $user['profil']; ?>  </td>
                                                            <?php if($user['profil']=="SuperAdmin") {
                                                    echo'<td> <a href="#"><img src="images/edit.png" data-target=#imgmodifierPerE'.$user["idutilisateur"].' align="middle" alt="modifier"  data-toggle="modal" /></a> </td>';

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

                                                    <td><a href="#" >
                                                    <img src="images/edit.png" <?php echo  "data-target=#imgmodifierPerE".$user['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp; 

                                                    <?php if($user['activer']==0){ ?>

                                                    <a href="#" >
                                                        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPerE".$user['idutilisateur'] ; ?> /></a> </td>

                                                        <?php }else{

                                                        //echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'

                                                         } ?>


                                                    <?php if ($user['activer']==0) { ?>
                                                        <td><span>Desactiver</span></td>
                                                        <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverE".$user['idutilisateur'] ; ?> >
                                                        Activer</button>
                                                        </td>
                                                        <?php 
                                                    } else { ?>
                                                        <td><span>Activer</span></td>
                                                        <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverE".$user['idutilisateur'] ; ?> >
                                                        Desactiver</button></td>
                                                    <?php }



                                                    }
                                                     ?>
                                                <div class="modal fade" <?php echo  "id=ActiverE".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
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
                                                <div class="modal fade" <?php echo  "id=DesactiverE".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
                                                <div <?php echo  "id=imgmodifierPerE".$user['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
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
                                                                                            echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    }else{

                                                                                    echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
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
                                                                        <button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!----------------------------------------------------------->
                                                <div <?php echo  "id=imgsuprimerPerE".$user['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
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
                                                                                            echo'<option value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    }else{

                                                                                    echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option selected value= "Editeur catalogue">Editeur catalogue</option';
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
                                                                                            echo'<option  value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    }else{

                                                                                    echo'<option value="SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option  value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
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
                                                                        <button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
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
                                                                                            echo'<option  value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }
                                                                                    }
                                                                         
                                                                                 ?>
                                                                                    </select>
                                                                                    <input type="hidden" name="profilInitial" <?php echo  'value="'.$user['profil'].'"' ; ?> />
                                                                                </div>
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
                                                                                            echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    }else{

                                                                                    echo'<option selected value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
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
                                                                        <button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
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
                                                                                            echo'<option selected value= "SuperAdmin">SuperAdmin</option><option value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option>';
                                                                                    }else if ($utilisateur['profil']=="Admin"){
                                                                                        echo'<option value= "SuperAdmin">SuperAdmin</option><option selected value= "Admin">Admin</option><option value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
                                                                                    }else{

                                                                                    echo'<option value="SuperAdmin">SuperAdmin</option><option  value= "Admin">Admin</option><option selected value= "Accompagnateur">Accompagnateur</option><option value= "Editeur catalogue">Editeur catalogue</option';
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
                                                                        <button type="submit" name="btnSupprimerPersonnel" class="btn btn-primary">Supprimer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                               <!------------------------
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