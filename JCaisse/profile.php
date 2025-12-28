<?php

session_start();



require('connection.php');



require('declarationVariables.php');



if(!$_SESSION['iduser']){

	header('Location:../index.php');

}elseif (isset($_POST['btnModifierProfile'])) {



		$nom        =@htmlentities($_POST['nom']);

		$prenom     =@htmlentities($_POST['prenom']);

		$adresse    =@htmlentities($_POST['adresse']);

		$email      =@htmlentities($_POST['email']);

		$telPortable=@htmlentities($_POST['telPortable']);

		$telFixe    =@htmlentities($_POST['telFixe']);





		$nomInitial    		   =$_POST['nomInitial'];

		$prenomInitial 		   =$_POST['prenomInitial'];

		$adresseUInitial 	   =$_POST['adresseUInitial'];

		$emailInitial          =$_POST['emailInitial'];

		$telPortableInitial    =$_POST['telPortableInitial'];

		$telFixeInitial 	   =$_POST['telFixeInitial'];

		

		

		if(($nom==$nomInitial)&&($prenom==$prenomInitial)&&($email==$emailInitial)&&($adresse==$adresseUInitial)&&($telPortable==$telPortableInitial)&&($telFixe==$telFixeInitial)){

	echo '<script type="text/javascript"> alert("INFO : AUCUNE MODIFICATION SUR VOTRE PROFIL ...");</script>';

		}else{





			 $sql9="UPDATE `aaa-utilisateur` set nom='".$nom."',prenom='".$prenom."',adresse='".mysql_real_escape_string($adresse)."',email='".$email."',telPortable='".$telPortable."',telFixe='".$telFixe."' where idutilisateur=".$_SESSION['iduser'];

		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");



		     /*******************************************

		     	UPDATE de l'utilisateur dans la session

		     ********************************************/

		     	if ($res9) {

		         	 $_SESSION['prenom']   = $prenom;

		         	 $_SESSION['nomU']     = $nom;

		        	 $_SESSION['adresseU'] = $adresse;

		         	 $_SESSION['email']    = $email;

		         	 $_SESSION['telPortable']= $telPortable;

		         	 $_SESSION['telFixe']    = $telFixe;

		     	}

			$message="Utilisateur Modifier avec succes";

			}

}else if (isset($_POST['btnModifierBoutique'])) {







		$labelB=@htmlentities($_POST['labelB']);

		$adresseB=@htmlentities($_POST['adresseB']);

		$registreCB=@htmlentities($_POST['registreCB']);

		$nineaB=@htmlentities($_POST['nineaB']);

		$descriptionB=@htmlentities($_POST['descriptionB']);

		$nomBInitial    		    =$_POST['nomBInitial'];

		$adresseBInitial 		    =$_POST['adresseBInitial'];

		$registreCBInitial    		    =$_POST['registreCBInitial'];

		$nineaBInitial 		    =$_POST['nineaBInitial'];

		$descriptionBInitial 		    =$_POST['descriptionBInitial'];





if(($labelB==$nomBInitial) && ($adresseB==$adresseBInitial) && ($registreCB==$registreCBInitial) && ($nineaB==$nineaBInitial)  && ($descriptionB==$descriptionBInitial)){

	echo '<script type="text/javascript"> alert("INFO : AUCUNE MODIFICATION POUR VOTRE ENTREPRISE ...");</script>';

		}else{



			 $sql9="UPDATE `aaa-boutique` set labelB='".mysql_real_escape_string($labelB)."',adresseBoutique='".mysql_real_escape_string($adresseB)."',RegistreCom='".mysql_real_escape_string($registreCB)."',Ninea='".mysql_real_escape_string($nineaB)."', description='".mysql_real_escape_string($descriptionB)."'
			 where idBoutique=".$_SESSION['idBoutique'];

		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");



		     /*******************************************

		     	UPDATE de la boutique dans la session

		     ********************************************/

         	$_SESSION['labelB']     = $labelB;

			$_SESSION['adresseB']   = $adresseB;

			$_SESSION['RegistreCom'] =$registreCB;

			$_SESSION['Ninea']  =$nineaB;

			$_SESSION['descriptionB']  =$descriptionB;

		}

} else if (isset($_POST['btnModifierPassword'])) {



		$oldMotdepasse=@htmlentities($_POST['oldMotdepasse']);

		$motdepasse1=@htmlentities($_POST['motdepasse1']);

		$motdepasse2=@htmlentities($_POST['motdepasse2']);



		$sql="select motdepasse from `aaa-utilisateur` where idutilisateur=".$_SESSION['iduser'];

		$res=mysql_query($sql)or die ("select de impossible-2");

		$ligne = mysql_fetch_array($res);

		$motdepasse=$ligne['motdepasse'];



		$oldMotdepasse=sha1($oldMotdepasse);



		if ($oldMotdepasse==$motdepasse) {



				if ($motdepasse1==$motdepasse2) {

					$sql9="UPDATE `aaa-utilisateur` set motdepasse='".sha1($motdepasse1)."' where idutilisateur=".$_SESSION['iduser'];

		     		$res9=@mysql_query($sql9) or die ("mise à jour acces impossible");



					echo '<script type="text/javascript"> alert("CONFIRMATION : VOTRE MOT DE PASSE EST MIS A JOUR. VEUILLEZ VOUS RECONNECTEZ ...");</script>';

					session_destroy();

					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="../index.php"</script>';

					echo'</head>';

					echo'</html>';

				} else {

					echo '<script type="text/javascript"> alert("ERREUR : Les deux Nouveaux Mots de passe ne correspondent pas ...");</script>';

				}

		}else{

			echo '<script type="text/javascript"> alert("ERREUR : Mot de passe courant fourni est incorrecte ...");</script>';

		}

 }else if (isset($_POST['btnModifierImprimante'])) {



        $imprimante  =@htmlentities($_POST['imprimante']);

		$etiquette  =@htmlentities($_POST['etiquette']);



        $imprimanteInitial      =$_SESSION['imprimante'];

		$etiquetteInitial 	   =$_SESSION['etiquette'];











		if(($etiquette==$etiquetteInitial)&&($imprimante==$etiquetteInitial)){

	echo '<script type="text/javascript"> alert("INFO : AUCUNE MODIFICATION POUR VOTRE IMPRIMANTE ...");</script>';

		}else{



			 $sql9="UPDATE `aaa-boutique` set imprimante='".mysql_real_escape_string($imprimante)."',etiquette='".mysql_real_escape_string($etiquette)."' where idBoutique=".$_SESSION['idBoutique'];

		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");



		     /*******************************************

		     	UPDATE de la boutique dans la session

		     ********************************************/

           $_SESSION['imprimante']   = $imprimante;

           $_SESSION['etiquette']    = $etiquette;

		}

}

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgBoutique'])) {
    $idBoutique=htmlspecialchars(trim($_POST['idBoutique']));
    if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './logo/'.$file);

            $sql2="UPDATE `aaa-boutique` set image='".$file."' where idBoutique='".$idBoutique."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());

			$_SESSION['imageB']=$file;
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



		<?php

		  require('header.php');

		?>

		<script>
			function showImageBoutique(idBoutique) {
				var nom=$('#imageBoutique'+idBoutique).attr("data-image");
				$('#idBoutique_View').text(nom);
				$('#idBoutique_Upd_Nv').val(idBoutique);
				$('#input_file_Boutique').val('');
				$('#imageNvBoutique').modal('show');
				var file = $('#imageBoutique'+idBoutique).val();
				if(file!=null && file!=''){
					var format = file.substr(file.length - 3);
					if(format=='pdf'){
						document.getElementById('output_pdf_Boutique').style.display = "block";
						document.getElementById('output_image_Boutique').style.display = "none";
						document.getElementById("output_pdf_Boutique").src="./logo/"+file;
					}
					else{
						document.getElementById('output_image_Boutique').style.display = "block";
						document.getElementById('output_pdf_Boutique').style.display = "none";
						document.getElementById("output_image_Boutique").src="./logo/"+file;
					}
				}
				else{
					document.getElementById('output_pdf_Boutique').style.display = "none";
					document.getElementById('output_image_Boutique').style.display = "none";
				}
			}
			function showPreviewBoutique(event) {
				var file = document.getElementById('input_file_Boutique').value;
				var reader = new FileReader();
				reader.onload = function()
				{
					var format = file.substr(file.length - 3);
					var pdf = document.getElementById('output_pdf_Boutique');
					var image = document.getElementById('output_image_Boutique');
					if(format=='pdf'){
						document.getElementById('output_pdf_Boutique').style.display = "block";
						document.getElementById('output_image_Boutique').style.display = "none";
						pdf.src = reader.result;
					}
					else{
						document.getElementById('output_image_Boutique').style.display = "block";
						document.getElementById('output_pdf_Boutique').style.display = "none";
						image.src = reader.result;
					}
				}
				reader.readAsDataURL(event.target.files[0]);
				document.getElementById('btn_upload_Boutique').style.display = "block";
			}
		</script>

	<div class="container">



		<div>

			<div class="row">

                <div class="col-lg-5  limitation">

					<h3>UTILISATEUR </h3><br/>

	            	   <div class="form-group row">

					      <label for="inputEmail3" class="col-sm-4 col-form-label">PRENOM </label>

					      <div class="col-sm-7">

							<?php echo  '<input type="text" class="form-control"  disabled="" value="'. mysql_real_escape_string($_SESSION['prenom']).'"  >'; ?>

					      </div>

					  </div>

					  <div class="form-group row">

					      <label for="inputEmail3" class="col-sm-4 col-form-label">NOM </label>

					      <div class="col-sm-7">

					      	 <?php echo  '<input type="text" class="form-control"  disabled="" value="'.mysql_real_escape_string($_SESSION['nomU']).'" >';?>

					      </div>

					  </div>

					  <div class="form-group row">

						    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE</label>

						    <div class="col-sm-7">

						      <input type="text" class="form-control" disabled="" <?php echo  'value="'.$_SESSION["adresseU"].'"' ; ?>>

						    </div>

					  </div>

					  <div class="form-group row">

						    <label for="inputEmail3" class="col-sm-4 col-form-label">EMAIL </label>

						    <div class="col-sm-7">

						      <?php echo  '<input type="email" class="form-control" disabled="" value="'. $_SESSION['email'].'" > ';?>

						    </div>

					  </div>

					  <div class="form-group row">

						    <label for="inputEmail3" class="col-sm-4 col-form-label">NUMERO PORTABLE </label>

						    <div class="col-sm-7">

						     <?php echo  '<input type="tel" class="form-control"  disabled="" value="'. $_SESSION['telPortable'].'" >';?>

						    </div>

					  </div>

					  <div class="form-group row">

						    <label for="inputEmail3" class="col-sm-4 col-form-label">NUMERO FIXE</label>

						    <div class="col-sm-7">

						      <?php echo  '<input type="tel" name="telFixe" class="form-control"  disabled="" value="'. $_SESSION['telFixe'].'" >';?>

						    </div>

					  </div>

					  <div class="form-group row">

						    <label for="inputEmail3" class="col-sm-4 col-form-label">Vous étes :</label>

						    <div class="col-sm-7">

						       <?php if ($_SESSION['proprietaire']==1) {    echo '+Proprietaire ';   } ?>

							    <?php if ($_SESSION['gerant']==1) {    echo '+Gerant ';   } ?>

							    <?php if ($_SESSION['caissier']==1) {    echo '+Caissier ';   } ?>

						    </div>

					  </div>

					  <div class="modal-footer">

                            <button type="button" class="btn btn-success pull-left" data-toggle="modal" data-target="#modPersonnel">

                        			<i class="glyphicon glyphicon-plus"></i>Modifier votre Profil

   							</button>

   							<button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#modPassword">

                        			<i class="glyphicon glyphicon-lock"></i>Modifier votre Mot de passe

   							</button>

                      </div>

					  <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {?>
						<br/>
							<form  method="post" action="impressionEtiquette.php" >
									<button type="submit" class="btn btn-success noImpr" >
													<i class="glyphicon glyphicon-plus"></i>Impression Etiquette
									</button>
							</form>
						<br/>
					  <?php } ?>

				</div>



				

<!-- mot de passe -->

				<div class="modal fade" id="modPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

		            <div class="modal-dialog" role="document">

		                <div class="modal-content">

		                    <div class="modal-header">

		                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

		                        <h4 class="modal-title" id="myModalLabel">Modification de votre Mot de passe</h4>

		                    </div>

		                    <div class="modal-body">

		                    	<form name="formulairePersonnel" method="post" action="profile.php">

		                    		<div class="form-group row">

								    	<label for="inputPassword3" class="col-sm-4 col-form-label">Ancien Mot de passe<font color="red">*</font></label>

								    	<div class="col-sm-6">

								      		<input type="password" class="form-control" required value="" name="oldMotdepasse" >

								    	</div>

								    </div>

								    <div class="form-group row">

								    	<label for="inputPassword3" class="col-sm-4 col-form-label">Nouveau Mot de passe<font color="red">*</font></label>

								    	<div class="col-sm-6">

								      		<input type="password" class="form-control" id="inputPassword" required name="motdepasse1">

								    	</div>

								    </div>

								    <div class="form-group row">

								    	<label for="inputPassword3" class="col-sm-4 col-form-label">Confirmer Mot de passe<font color="red">*</font></label>

								    	<div class="col-sm-6">

								      		<input type="password" class="form-control" id="inputPassword2" required="" name="motdepasse2" >

								    	</div>

								    </div>



									<div class="modal-footer">

				                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

				                        <button type="submit" name="btnModifierPassword" class="btn btn-primary">Enregistrer</button>

				                    </div>

								</form>



		                    </div>



		                </div>

		            </div>

        		</div>

<!-- mot de passe fin -->

				<?php if ($_SESSION['proprietaire']==1) {?>

				<div class="col-lg-offset-1 col-md-6 limitation">
							<h3 >ENTREPRISE COMMERCIALE</h3><br/>

							<div class="form-group row">

							    <label for="inputEmail3" class="col-sm-3 col-form-label">NOM </label>

							    <div class="col-sm-6">

							    	<?php echo '<input type="text" class="form-control" disabled="" value="'.$_SESSION['labelB'].'" >';?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">ADRESSE</label>

								<div class="col-sm-6">

									<?php echo  '<input type="text" class="form-control"  disabled="" value="'. $_SESSION['adresseB'].'" >';?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">Pays</label>

								<div class="col-sm-6">

									<?php echo  '<input type="text" class="form-control"  disabled="" value="'. $_SESSION['Pays'].'" >';?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">TYPE</label>

								<div class="col-sm-6">

									<?php echo  '<input type="text" class="form-control" disabled="" value="'.$_SESSION['type'] .'">' ; ?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">CATEGORIE</label>

								<div class="col-sm-6">

									<input type="text" class="form-control" disabled="" <?php echo  "value=".$_SESSION['categorie']."" ; ?>>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">REGISTRE DE COMMERCE</label>

								<div class="col-sm-6">

									<?php echo  '<input type="text" disabled="" class="form-control"   value="'. $_SESSION['RegistreCom'].'" required />';?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">NINEA</label>

								<div class="col-sm-6">

									<?php echo  '<input type="text" disabled="" class="form-control"   value="'. $_SESSION['Ninea'].'" required />';?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">DESCRIPTION</label>

								<div class="col-sm-6">

									<?php echo  '<textarea type="text" disabled="" class="form-control" required >'. $_SESSION['descriptionB'].'</textarea>';?>

								</div>

							</div>

							<div class="form-group row">

								<label for="inputEmail3" class="col-sm-3 col-form-label">Image</label>

								<div class="col-sm-6">

									<?php
										if($_SESSION['imageB']!=null && $_SESSION['imageB']!=' '){
											echo '<img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBoutique'.$_SESSION['idBoutique'].'"  onclick="showImageBoutique('.$_SESSION['idBoutique'].')" 	 />
											<input style="display:none;" data-image="'.$_SESSION['labelB'].'"  id="imageBoutique'.$_SESSION['idBoutique'].'"  value="'.$_SESSION['imageB'].'" />';
										}
										else { 
											echo '<img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBoutique'.$_SESSION['idBoutique'].'"  onclick="showImageBoutique('.$_SESSION['idBoutique'].')" 	 />
											<input style="display:none;" data-image="'.$_SESSION['labelB'].'"  id="imageBoutique'.$_SESSION['idBoutique'].'"  value="'.$_SESSION['imageB'].'" />';
										}
									?>

								</div>

							</div>

							<div class="modal-footer">

								<button type="button" class="btn btn-success pull-left" data-toggle="modal" data-target="#modBoutique">

						                        			<i class="glyphicon glyphicon-plus"></i>Modifier votre Entreprise

						   		</button>

								   <form  method="post" action="creationBoutique.php" >

										<button type="submit" class="btn btn-success pull-right" >

														<i class="glyphicon glyphicon-plus"></i>Nouvelle Entreprise

										</button>

									</form>

							</div>

				</div>  

				<div class="modal fade" id="modBoutique" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

		            <div class="modal-dialog" role="document">

		                <div class="modal-content">

		                    <div class="modal-header">

		                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

		                        <h4 class="modal-title" id="myModalLabel">Modification de votre Entreprise</h4>

		                    </div>

		                    <div class="modal-body">

		                    	<form name="formulairePersonnel" method="post" action="profile.php">



					            	<div class="form-group row">

									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>

									    <div class="col-sm-5">

									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$_SESSION['labelB'].'" required /> '; ?>



										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$_SESSION['labelB']."" ; ?> />

							    		</div>

						    		</div>

								    <div class="form-group row">

									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>

									    <div class="col-sm-5">

									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $_SESSION['adresseB'].'" required />';?>



										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$_SESSION['adresseB']."" ; ?> />

									    </div>

								    </div>



									<div class="form-group row">

									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>

									    <div class="col-sm-6">

										    <select class="form-control" name="type" id="type" disabled="disabled"> <?php

												$sql10="SELECT * FROM `aaa-typeboutique`";

												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());

												while($ligne = mysql_fetch_row($res10)) {



														if ($ligne[1]==$_SESSION['type']) {

																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';

														}else {

															// code...

																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';

														}

													} ?>

											</select>

											<input type="hidden" name="typeBInitial" <?php echo  "value=".$_SESSION['type']."" ; ?> />

										</div>

							   	    </div>

									<div class="form-group row">

										<label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>

										<div class="col-sm-6">

											<select class="form-control" name="categorie" id="categorie" disabled="disabled"> <?php

												$sql11="SELECT * FROM `aaa-categorie`";

												$res11=mysql_query($sql11);

												while($ligne2 = mysql_fetch_row($res11)) {

													if ($ligne2[1]==$_SESSION['categorie']) {

														echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

													}else {

														// code...

														echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

													}



													} ?>

											</select>



											<input type="hidden" name="categorieBInitial" <?php echo  "value=".$_SESSION['categorie']."" ; ?> />

											</div>

									</div>

									<div class="form-group row">

									    <label for="inputEmail3" class="col-sm-4 col-form-label">REGISTRE DE COMMERCE <font color="red">*</font></label>

									    <div class="col-sm-5">

									      <?php echo  '<input type="text" name="registreCB" class="form-control" value="'.$_SESSION['RegistreCom'].'" required /> '; ?>



										  <input type="hidden" name="registreCBInitial" <?php echo  "value=".$_SESSION['RegistreCom']."" ; ?> />

							    		</div>

						    		</div>

								    <div class="form-group row">

									    <label for="inputEmail3" class="col-sm-4 col-form-label">NINEA <font color="red">*</font></label>

									    <div class="col-sm-5">

									      <?php echo  '<input type="text" name="nineaB" class="form-control"   value="'. $_SESSION['Ninea'].'" required />';?>



										  <input type="hidden" name="nineaBInitial" <?php echo  "value=".$_SESSION['Ninea']."" ; ?> />

									    </div>

								    </div>


									<div class="form-group row">

										<label for="inputEmail3" class="col-sm-4 col-form-label">DESCRIPTION <font color="red">*</font></label>

										<div class="col-sm-5">

										<?php echo  '<textarea name="descriptionB" class="form-control">'.$_SESSION['descriptionB'].'</textarea> ';?>
										<input type="hidden" name="descriptionBInitial" <?php echo  "value=".$_SESSION['descriptionB']."" ; ?> />

										</div>

									</div>

									    <div class="modal-footer">

				                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

				                                <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>

				                       </div>

								</form>



		                    </div>



		                </div>

		            </div>

        		</div>

				<div class="modal fade"  id="imageNvBoutique" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Boutique : <b> <span id="idBoutique_View"></span></b></h4>
							</div>
							<form   method="post" enctype="multipart/form-data">
								<div class="modal-body" style="padding:40px 50px;">
									<input  type="text" style="display:none;" name="idBoutique" id="idBoutique_Upd_Nv" />
									<div class="form-group" style="text-align:center;" >
										<input type="file" name="file" accept="image/*" id="input_file_Boutique" onchange="showPreviewBoutique(event);"/><br />
										<img style="display:none;" width="100px" height="100px" id="output_image_Boutique"/>
										<iframe style="display:none;" id="output_pdf_Boutique" width="100%" height="500px"></iframe>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBoutique" id="btn_upload_Boutique" >
										<span class="glyphicon glyphicon-upload"></span> Enregistrer
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<?php } ?>



			</div>			

		</div>

			<div class="modal fade" id="modPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

	            <div class="modal-dialog" role="document">

	                <div class="modal-content">

	                    <div class="modal-header">

	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

	                        <h4 class="modal-title" id="myModalLabel">Modification de votre Profil</h4>

	                    </div>

	                    <div class="modal-body">

	                    	<form name="formulairePersonnel" method="post" action="profile.php">



				            	  <div class="form-group row">

								      <label for="inputEmail3" class="col-sm-4 col-form-label">PRENOM <font color="red">*</font></label>

								      <div class="col-sm-6">

										<?php echo  '<input type="text" class="form-control" name="prenom"   value="'. $_SESSION['prenom'].'"  required />'; ?>

										<input type="hidden" name="prenomInitial" <?php echo  "value=".$_SESSION['prenom']."" ; ?> />

								      </div>

								  </div>

								  <div class="form-group row">

								      <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>

								      <div class="col-sm-6">

								      	 <?php echo  '<input type="text" class="form-control"  name="nom" value="'. $_SESSION['nomU'].'" required />';?>

										 <input type="hidden" name="nomInitial" <?php echo  "value=".$_SESSION['nomU']."" ; ?> />

								      </div>

								  </div>



								 <div class="form-group row">

								    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE </label>

								    <div class="col-sm-6">

								       <?php echo  '<input type="text" class="form-control"  name="adresse"   value="'. $_SESSION['adresseU'].'" >';?>

									   <input type="hidden" name="adresseUInitial" <?php echo  "value=".$_SESSION['adresseU']."" ; ?> />

								    </div>

								  </div>

								  <div class="form-group row">

								    <label for="inputEmail3" class="col-sm-4 col-form-label">EMAIL <font color="red">*</font></label>

								    <div class="col-sm-6">

								      <?php echo  '<input type="email" class="form-control"  name="email"   value="'. $_SESSION['email'].'" required />';?>

									   <input type="hidden" name="emailInitial" <?php echo  "value=".$_SESSION['email']."" ; ?> />

								    </div>

								  </div>

								  <div class="form-group row">

								    <label for="inputEmail3" class="col-sm-4 col-form-label">NUMERO PORTABLE <font color="red">*</font></label>

								    <div class="col-sm-6">

								      <?php echo  '<input type="tel" class="form-control"  name="telPortable"   value="'. $_SESSION['telPortable'].'" required />';?>

									  <input type="hidden" name="telPortableInitial" <?php echo  "value=".$_SESSION['telPortable']."" ; ?> />

								    </div>

					  			</div>

							  <div class="form-group row">

								    <label for="inputEmail3" class="col-sm-4 col-form-label">NUMERO FIXE</label>

								    <div class="col-sm-6">

								      <?php echo  '<input type="tel" class="form-control"  name="telFixe"   value="'. $_SESSION['telFixe'].'" >';?>

									  <input type="hidden" name="telFixeInitial" <?php echo  "value=".$_SESSION['telFixe']."" ; ?> />

								    </div>

							  </div>

								 	<div class="modal-footer">

			                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

			                                <button type="submit" name="btnModifierProfile" class="btn btn-primary">Enregistrer</button>

			                       </div>

							</form>



	                    </div>



	                </div>

	            </div>

        	</div>

	</div>

</body>

</html>

