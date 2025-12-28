<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur:EL hadji mamadou korka
Date de modification:20-03-2020
*/
session_start();

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

require('connection.php');

require('declarationVariables.php');
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$annee.'-'.$mois.'-'.$jour;

/**********************/


if (isset($_POST['btnAjouterCataloque'])) {
	if(isset($_POST['type']) && isset($_POST['categorie'])){
		$catalogueTotal='aaa-catalogueTotal';
		$sql00="CREATE TABLE IF NOT EXISTS `".$catalogueTotal."`
		 (`id` INT NOT NULL AUTO_INCREMENT, `nom` VARCHAR(55) NOT NULL,
		 `categorie` VARCHAR(55) NOT NULL,`type` VARCHAR(55) NOT NULL,
		 `dateCatalogue` VARCHAR(15) NOT NULL, PRIMARY KEY (`id`),
	 		 UNIQUE (`nom`)) ENGINE = MYISAM";
		 $res00 =@mysql_query($sql00) or die ("creation table catalogueT impossible".mysql_error());

    $type=$_POST['type'];
    $categorie=$_POST['categorie'];
    $typeCategorie=$_POST['type']."-".$_POST['categorie'];
      $sql3="SELECT  * FROM `aaa-boutique` where type='".$type."' and  categorie='".$categorie."' ";

      if($res3 = mysql_query($sql3)){
					      	$catTypeCateg='aaa-catalogue-'.$typeCategorie;

									 $sql5="insert IGNORE into `".$catalogueTotal."`
											 (nom, type,categorie,dateCatalogue) values
											 ('".$typeCategorie."','".$type."','".$categorie."','".$dateString2."')";
									$res5=@mysql_query($sql5) or die ("insertion catalogue impossible-2".mysql_error());
      }
	  }
}

if (isset($_POST['btnSupprimer'])) {
	$id         =@$_POST["id"];
	$sql="DELETE FROM `aaa-catalogueTotal` WHERE id=".$id;
	$res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());
}

/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body >
<?php
/**************** MENU HORIZONTAL *************/

if($_SESSION['profil']=="Editeur catalogue")
  require('header-editeur.php');
 else
  require('header.php');

/**************** Ajouter une catégorie *************/

/**************** *************  *************/

echo'<div class="container">';
if($_SESSION['profil']!="Editeur catalogue")
	echo'<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPersonnel">
												<i class="glyphicon glyphicon-plus"></i>Création catalogue
			</button>';

?>  </div>
		<div class="modal fade" id="addPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
								<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="myModalLabel">Creation catalogue</h4>
										</div>
										<div class="modal-body">
												<form name="formulairePersonnel" method="post"  >
													<div class="form-group row">
															<div class="col-sm-6">
																 <div class="form-group">
																    <label for="categorie"> Type de boutique </label>
																    <select class="form-control" name="type" id="categorie2">
																		<option selected value= "Sans categorie">Sans categorie</option>
																		 <?php
																		 $sql1="SELECT * FROM `aaa-typeboutique` ";
																		 if($res1 = mysql_query($sql1)) {
																			 while($type = mysql_fetch_array($res1)) {
																				echo'<option  value= "'.$type['libelle'].'">'.$type['libelle'].'</option>';
																			  }
																			} ?>
																	</select>
																</div>
														 </div>
														 <div class="col-sm-6">
																<div class="form-group">
																    <label for="categorie"> CATEGORIE </label>

																    <select class="form-control" name="categorie" id="categorie2">
																		<option selected value= "Sans categorie">Sans categorie</option>
																		 <?php
																		 $sql2="SELECT * FROM `aaa-categorie` ";
																		 if($res2 = mysql_query($sql2)) {
																			 while($categorie = mysql_fetch_array($res2)) {
																				echo'<option  value= "'.$categorie['nomcategorie'].'">'.$categorie['nomcategorie'].'</option>';
																			}
																		} ?>
																	</select>
																</div>
														</div>
													</div>
													<div class="modal-footer row">
															<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
															<button type="submit" name="btnAjouterCataloque" class="btn btn-primary">Enregistrer</button>
													</div>
												</form>
										</div>

								</div>
						</div>
		</div>


</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/
?>
		<div class="container">
				<div class="container" align="center">
				        <ul class="nav nav-tabs">
				          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
				        </ul>
				        <div class="tab-content">
				            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
				                    <?php
									              $sql3='SELECT * from  `aaa-catalogueTotal`  order by id desc';
															 if($res3=mysql_query($sql3)){ ?>
									                   <table id="exemple" class="display" border="1" id="userTable">
																					     <thead>
																									 <tr>
																												<th>NOM</th>
																												<th>CATEGORIE</th>
																												<th>TYPE</th>
																												<th>DATE</th>
																										</tr>
																							 </thead>
																									<tfoot><tr>
																										<th>NOM</th>
																										<th>CATEGORIE</th>
																										<th>TYPE</th>
																										<th>DATE</th>
																									</tr>
																								</tfoot>
																						<?php
																						while($tab3=mysql_fetch_array($res3)){
																										echo'<tr><td>'.$tab3["nom"].'</td>
																										<td>'.$tab3["categorie"].'</td>
																										<td>'.$tab3["type"].'</td>
																										<td>'.$tab3["dateCatalogue"].'</td>'; ?>
																										<td>	<a href="#" >
																														<?php echo  "<a href=catalogueDetail.php?i=".$tab3['id']."  >Details</a>" ; ?>
																										</td>
																										<td>
																															<?php 	echo '<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab3["id"].'" /></a>'; ?>
																															<?php echo  '<div id="imgsup'.$tab3["id"].'"  class="modal fade " role="dialog">
																																	<div class="modal-dialog">
																																				<div class="modal-content">
																																					<div class="modal-header" style="padding:35px 50px;">
																																						<button type="button" class="close" data-dismiss="modal">&times;</button>
																																						<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression </b></h4>
																																					</div>
																																					<div class="modal-body" style="padding:40px 50px;">
																																							<form role="form" class="" id="form" name="formulaire2" method="post" >
																																									<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																									<div class="form-group" >
																																									Voulez vous vraiment supprimer le catalogue<br />';
																																											echo '<b>'.$tab3["nom"].'<b>
																																									</div>
																																									<div class="form-group" align="right">
																																											<input type="submit" class="boutonbasic"  name="btnSupprimer" value="SUPPRIMER  >>"/>
																																									</div>
																																							</form>
																																					</div>
																																				</div>
																																			</div>
																																	</div>';?>

																										</td>
																					<?php }
																					 ?>
																		 </table>
												 <?php } ?>
										</div>
							  </div>
				</div>
		</div>
	</body>
</html>
