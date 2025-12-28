<?php
/*
R�sum� :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de cr�ation : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();

if($_SESSION['iduserBack']){

require('connection.php');

/*$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";*/

/**********************/
$idStock         =@$_POST["idStock"];

$designation      =@htmlentities($_POST["designation"]);
$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
//$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];


$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$idStock2       =@$_GET["idStock"];
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$jour.'-'.$mois.'-'.$annee;

if (isset($_POST['btnActiver'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=1;
	$sql3="UPDATE `boutique` set  activer='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}elseif (isset($_POST['btnDesactiver'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=0;
	$sql3="UPDATE `boutique` set  activer='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}elseif (isset($_POST['btnModifierBoutique'])) {

	$idBoutique					=$_POST['idBoutique'];
	$nomBoutique				=$_POST['nomBoutique'];
	
	$labelB						=@htmlentities($_POST['labelB']);
	$adresseB					=@htmlentities($_POST['adresseB']);
	$type        				=@htmlentities($_POST['type']);
	$categorie					=@htmlentities($_POST['categorie']);
	
	/*$nomBInitial    		    =$_POST['nomBInitial'];
	$adresseBInitial 		    =$_POST['adresseBInitial'];
	$typeBInitial 	   			=$_POST['typeBInitial'];
	$categorieBInitial          =$_POST['categorieBInitial'];*/
	
	//echo $idBoutique;
	
	$sql3="UPDATE `boutique` set  `labelB`='".mysql_real_escape_string($labelB)."',type='".$type."',adresseBoutique='".mysql_real_escape_string($adresseB)."',categorie='".mysql_real_escape_string($categorie)."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

}elseif (isset($_POST['btnSupprimerBoutique'])) {

	$idBoutique					=$_POST['idBoutique'];
	$nomBoutique				=$_POST['nomBoutique'];
 
 /*$labelB						=@htmlentities($_POST['labelB']);
	$adresseB					=@htmlentities($_POST['adresseB']);
	$type        				=@htmlentities($_POST['type']);
	$categorie					=@htmlentities($_POST['categorie']);
	
	$nomBInitial    		    =$_POST['nomBInitial'];
	$adresseBInitial 		    =$_POST['adresseBInitial'];
	$typeBInitial 	   			=$_POST['typeBInitial'];
	$categorieBInitial          =$_POST['categorieBInitial'];*/
	
	//echo $idBoutique;
	
	
	$sql2="SELECT * FROM `acces` WHERE idBoutique=".$idBoutique;
	$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
	while ($acces = mysql_fetch_array($res2)) { 
		$sql="DELETE FROM `utilisateur` WHERE idutilisateur=".$acces['idutilisateur'];
		$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
		
		$sql="DELETE FROM `acces` WHERE idutilisateur=".$acces['idutilisateur'];
		$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	}
	
	$sql="DELETE FROM `boutique` WHERE idBoutique=".$idBoutique;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());

	

	$nomtableJournal=$nomBoutique."-journal";
	$nomtableCategorie=$nomBoutique."-categorie";
	$nomtablePage=$nomBoutique."-pagej";
	$nomtablePagnet=$nomBoutique."-pagnet";
	$nomtableLigne=$nomBoutique."-lignepj";
	$nomtableDesignation=$nomBoutique."-designation";
	$nomtableStock=$nomBoutique."-stock";
	$nomtableTotalStock=$nomBoutique."-totalstock";
	$nomtableBon=$nomBoutique."-bon";
	$nomtableClient=$nomBoutique."-client";
	$nomtableVersement=$nomBoutique."-versement";

	//echo $nomtableJournal;

	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableJournal."`";
	//echo $sql;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableCategorie."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtablePage."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtablePagnet."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableLigne."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableDesignation."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableStock."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableBon."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableClient."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableVersement."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `bdjournalcaisse`.`".$nomtableTotalStock."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());	


/*
	
	
	//echo $nomtableJournal;
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableJournal."`";
	//echo $sql;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableCategorie."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtablePage."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtablePagnet."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableLigne."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableDesignation."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableStock."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableBon."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableClient."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableVersement."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".$nomtableTotalStock."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());	

		*/
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/datatables.min.js"></script>
    <script>$(document).ready( function () {$('#exemple').DataTable();} );</script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<title>JCAISSE-BACK END</title>
</head>



<body onLoad="process()">
<?php 
  require('header.php');
?>
        <div class="container" align="center">
            <ul class="nav nav-tabs"> 
              <li class="active"><a data-toggle="tab" href="#LISTECLIENTS">LISTE DES BOUTIQUES</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTECLIENTS">	
	        <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
				
				<thead>
					<tr>
						<th>Nom Boutique</th>
						<th>Label</th>
						<th>Type & Catégorie</th>
						<th>Adresse</th>
						<th>Propriétaire & Numéro téléphone</th>
                        <th>Date de création</th>
						<th>Opération</th>
						<th>Activer/Désactiver</th>
					</tr>
				</thead>
                <tfoot>
					<tr>
						<th>Nom Boutique</th>
						<th>Label</th>
						<th>Type & Catégorie</th>
						<th>Adresse</th>
						<th>Propriétaire & Numéro téléphone</th>
                        <th>Date de création</th>
						<th>Opération</th>
						<th>Activer/Désactiver</th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
						if($_SESSION['profil']=="SuperAdmin"){
						
						$sql2="SELECT * FROM `boutique`";
						$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
						while ($boutique = mysql_fetch_array($res2)) { 
                            $sql3="SELECT * FROM `acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
						    $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
						    while ($acces = mysql_fetch_array($res3)) {
								$sql4="SELECT * FROM `utilisateur` where idutilisateur=".$acces['idutilisateur'];
								$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
								while ($utilisateur = mysql_fetch_array($res4)){  
									//if($utilisateur['back']==1)
							?>
							<tr>
								<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
								<td> <?php echo  $boutique["labelB"]; ?>  </td>
								<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
								<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
								<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
                                <td> <?php echo  $boutique["datecreation"]; ?>  </td>
								
								
								
								
								
								
								<td>
									<a href="#" >
        								<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
									<?php if ($boutique['activer']==0) { ?>
									<a href="#">
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
										<?php }else{ ?>
										
										<a href="#">
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
									<?php	} ?>

								</td>
								
	
								<?php if ($boutique['activer']==0) { ?>
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
						                        Activer</button>
												</td>
												<?php 
											} else { ?>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
												Desactiver</button></td>
											<?php }
											 ?>
								
							</tr>
					<div class="modal fade" <?php echo  "id=Activer".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="accueil.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment activer cette boutique</h2>
									     <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
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
        			<div class="modal fade" <?php echo  "id=Desactiver".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="accueil.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment desactiver cette boutique</h2>
									     <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
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
        <div <?php echo  "id=imgmodifierBoutique".$boutique['idBoutique']."" ; ?>   class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier une boutique</h4>
					</div>
					<div class="modal-body">
		                    	<form name="formulairePersonnel" method="post" action="accueil.php">

					            	<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" required /> '; ?>
										  
										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
										  <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" required />';?>
										  
										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
									    </div>
								    </div>

									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
									    <div class="col-sm-6">
										    <select name="type" id="type"> <?php
												$sql10="SELECT * FROM `typeboutique`";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) {

														if ($ligne[1]==$boutique['type']) {
																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}else {
															// code...
																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}
													} ?>
											</select>
											<input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie"> <?php
													$sql11="SELECT * FROM `categorie`";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) {
														if ($ligne2[1]==$boutique['categorie']) {
															echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}else {
															// code...
															echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}

														} ?>
												</select>
												
												<input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
												
									   		 </div>
									  </div>

									    <div class="modal-footer">
										<input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
				                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                                <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
				                       </div>
								</form>

		                    </div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
		<div <?php echo  "id=imgsuprimerBoutique".$boutique['idBoutique']."" ; ?>  class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="accueil.php">
						    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" disabled="" /> '; ?>
										  
										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
										  <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" disabled="" />';?>
										  
										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
									    </div>
								    </div>

									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
									    <div class="col-sm-6">
										    <select name="type" id="type" disabled=""> <?php
												$sql10="SELECT * FROM `typeboutique`";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) {

														if ($ligne[1]==$boutique['type']) {
																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}else {
															// code...
																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}
													} ?>
											</select>
											<input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie" disabled=""> <?php
													$sql11="SELECT * FROM `categorie`";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) {
														if ($ligne2[1]==$boutique['categorie']) {
															echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}else {
															// code...
															echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}

														} ?>
												</select>
												
												<input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
									   		 </div>
									  </div>

						    <div class="modal-footer row">
							<input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
						        <button type="button" class="btn btn-default" >Annuler</button>
								<button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
	
	
	
	
	
	
					<?php 	}}}
					
					
					}else if($_SESSION['profil']=="Admin"){
					
					$sql4="SELECT * FROM `utilisateur` where idadmin=".$_SESSION['iduserBack'];
					
					$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
					while ($utilisateur = mysql_fetch_array($res4)){ 
					$sql2="SELECT * FROM `boutique` where Accompagnateur='".$utilisateur["matricule"]."' OR Accompagnateur='".$_SESSION['matricule']."'";
						$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
						while ($boutique = mysql_fetch_array($res2)) { 
                            $sql3="SELECT * FROM `acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
						    $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
						    while ($acces = mysql_fetch_array($res3)) {
								$sql4="SELECT * FROM `utilisateur` where idutilisateur=".$acces['idutilisateur'];
								$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
								while ($utilisateur = mysql_fetch_array($res4)){  
									//if($utilisateur['back']==1)
							?>
							<tr>
								<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
								<td> <?php echo  $boutique["labelB"]; ?>  </td>
								<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
								<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
								<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
                                <td> <?php echo  $boutique["datecreation"]; ?>  </td>
								
								
								
								
								
								
								<td>
									<a href="#" >
        								<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
									<?php if ($boutique['activer']==0) { ?>
									<a href="#">
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
										<?php }else{ ?>
										
										<a href="#">
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
									<?php	} ?>

								</td>
								
	
								<?php if ($boutique['activer']==0) { ?>
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
						                        Activer</button>
												</td>
												<?php 
											} else { ?>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
												Desactiver</button></td>
											<?php }
											 ?>
								
							</tr>
					<div class="modal fade" <?php echo  "id=Activer".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="accueil.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment activer cette boutique</h2>
									     <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
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
        			<div class="modal fade" <?php echo  "id=Desactiver".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="accueil.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment desactiver cette boutique</h2>
									     <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
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
        <div <?php echo  "id=imgmodifierBoutique".$boutique['idBoutique']."" ; ?>   class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier une boutique</h4>
					</div>
					<div class="modal-body">
		                    	<form name="formulairePersonnel" method="post" action="accueil.php">

					            	<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" required /> '; ?>
										  
										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
										  <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" required />';?>
										  
										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
									    </div>
								    </div>

									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
									    <div class="col-sm-6">
										    <select name="type" id="type"> <?php
												$sql10="SELECT * FROM `typeboutique`";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) {

														if ($ligne[1]==$boutique['type']) {
																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}else {
															// code...
																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}
													} ?>
											</select>
											<input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie"> <?php
													$sql11="SELECT * FROM `categorie`";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) {
														if ($ligne2[1]==$boutique['categorie']) {
															echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}else {
															// code...
															echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}

														} ?>
												</select>
												
												<input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
												
									   		 </div>
									  </div>

									    <div class="modal-footer">
										<input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
				                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                                <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
				                       </div>
								</form>

		                    </div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
		<div <?php echo  "id=imgsuprimerBoutique".$boutique['idBoutique']."" ; ?>  class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="accueil.php">
						    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" disabled="" /> '; ?>
										  
										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
										  <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" disabled="" />';?>
										  
										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
									    </div>
								    </div>

									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
									    <div class="col-sm-6">
										    <select name="type" id="type" disabled=""> <?php
												$sql10="SELECT * FROM `typeboutique`";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) {

														if ($ligne[1]==$boutique['type']) {
																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}else {
															// code...
																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}
													} ?>
											</select>
											<input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie" disabled=""> <?php
													$sql11="SELECT * FROM `categorie`";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) {
														if ($ligne2[1]==$boutique['categorie']) {
															echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}else {
															// code...
															echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}

														} ?>
												</select>
												
												<input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
									   		 </div>
									  </div>

						    <div class="modal-footer row">
							<input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
						        <button type="button" class="btn btn-default" >Annuler</button>
								<button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
	
	
	
	
	
	
					<?php 	}}}}
					
					
					
					
					}else if ($_SESSION['profil']=="Accompagnateur"){
					
					
					$sql2="SELECT * FROM `boutique` where Accompagnateur='".$_SESSION['matricule']."'";
						$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
						while ($boutique = mysql_fetch_array($res2)) { 
                            $sql3="SELECT * FROM `acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
						    $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
						    while ($acces = mysql_fetch_array($res3)) {
								$sql4="SELECT * FROM `utilisateur` where idutilisateur=".$acces['idutilisateur'];
								$res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
								while ($utilisateur = mysql_fetch_array($res4)){  
									//if($utilisateur['back']==1)
							?>
							<tr>
								<td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
								<td> <?php echo  $boutique["labelB"]; ?>  </td>
								<td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
								<td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
								<td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
                                <td> <?php echo  $boutique["datecreation"]; ?>  </td>
								
								
								
								
								
								
								<td>
									<a href="#" >
        								<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
									<?php if ($boutique['activer']==0) { ?>
									<a href="#">
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
										<?php }else{ ?>
										
										<a href="#">
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
									<?php	} ?>

								</td>
								
	
								<?php if ($boutique['activer']==0) { ?>
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
						                        Activer</button>
												</td>
												<?php 
											} else { ?>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
												Desactiver</button></td>
											<?php }
											 ?>
								
							</tr>
					<div class="modal fade" <?php echo  "id=Activer".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="accueil.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment activer cette boutique</h2>
									     <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
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
        			<div class="modal fade" <?php echo  "id=Desactiver".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="accueil.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment desactiver cette boutique</h2>
									     <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
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
        <div <?php echo  "id=imgmodifierBoutique".$boutique['idBoutique']."" ; ?>   class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier une boutique</h4>
					</div>
					<div class="modal-body">
		                    	<form name="formulairePersonnel" method="post" action="accueil.php">

					            	<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" required /> '; ?>
										  
										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
										  <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" required />';?>
										  
										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
									    </div>
								    </div>

									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
									    <div class="col-sm-6">
										    <select name="type" id="type"> <?php
												$sql10="SELECT * FROM `typeboutique`";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) {

														if ($ligne[1]==$boutique['type']) {
																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}else {
															// code...
																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}
													} ?>
											</select>
											<input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie"> <?php
													$sql11="SELECT * FROM `categorie`";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) {
														if ($ligne2[1]==$boutique['categorie']) {
															echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}else {
															// code...
															echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}

														} ?>
												</select>
												
												<input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
												
									   		 </div>
									  </div>

									    <div class="modal-footer">
										<input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
				                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                                <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
				                       </div>
								</form>

		                    </div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
		<div <?php echo  "id=imgsuprimerBoutique".$boutique['idBoutique']."" ; ?>  class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="accueil.php">
						    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" disabled="" /> '; ?>
										  
										  <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
										  <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
									    <div class="col-sm-5">
									      <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" disabled="" />';?>
										  
										  <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
									    </div>
								    </div>

									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
									    <div class="col-sm-6">
										    <select name="type" id="type" disabled=""> <?php
												$sql10="SELECT * FROM `typeboutique`";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) {

														if ($ligne[1]==$boutique['type']) {
																echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}else {
															// code...
																echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
														}
													} ?>
											</select>
											<input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie" disabled=""> <?php
													$sql11="SELECT * FROM `categorie`";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) {
														if ($ligne2[1]==$boutique['categorie']) {
															echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}else {
															// code...
															echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
														}

														} ?>
												</select>
												
												<input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
									   		 </div>
									  </div>

						    <div class="modal-footer row">
							<input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
						        <button type="button" class="btn btn-default" >Annuler</button>
								<button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
	
	
	
	
	
	
					<?php 	}}}}
					
					?>
					
				</tbody>
			</table>
        </div>
	</div>
	</div>


</div>
</body>
</html> <?php
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
