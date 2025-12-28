<?php
session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

if (isset($_POST['btnEnregistrerClient'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$telephone=htmlspecialchars(trim($_POST['telephone']));
		$adresse=htmlspecialchars(trim($_POST['adresse']));
		$solde=0;
		$activer=0;

		$sql1="insert into `".$nomtableClient."` (nom,prenom,adresse,telephone,solde,activer,iduser) values('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$telephone."','".$solde."','".$activer."','".$_SESSION['iduser']."')";
		//var_dump($sql1);
  		$res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
		$message="Client ajouter avec succes";


		$sql2="SELECT * FROM `".$nomtableClient."`  ORDER BY idClient DESC LIMIT 0,1";
		$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
		$clt = mysql_fetch_array($res2);

		$sql3="insert into `".$nomtableBon."` (idClient,date,heureBon,iduser) values('".$clt['idClient']."','".$dateString."','".$heureString."',".$_SESSION['iduser'].")";
  		$res3=mysql_query($sql3) or die ("insertion client impossible =>".mysql_error());

}elseif (isset($_POST['btnModifierClient'])) {

		$idClient=$_POST['idClient'];
		$nom=$_POST['nom'];
		$prenom=$_POST['prenom'];
		$adresse=$_POST['adresse'];
		$telephone=$_POST['telephone'];
		$sql3="UPDATE `".$nomtableClient."` set  `nom`='".$nom."',prenom='".$prenom."',adresse='".mysql_real_escape_string($adresse)."',telephone='".$telephone."' where idClient=".$idClient;
		  $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

}elseif (isset($_POST['btnSupprimerClient'])) {

	$idClient=$_POST['idClient'];
	$sql="DELETE FROM `".$nomtableClient."` WHERE idClient=".$idClient;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());

}
elseif (isset($_POST['btnSupprimerDefClient'])) {

	$idClient=$_POST['idClient'];

	$sql1="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'";
	$res1 = mysql_query($sql1) or die ("persoonel requête 2".mysql_error());
	while ($panier = mysql_fetch_array($res1)) {

		$sql="DELETE FROM `".$nomtableLigne."` WHERE idPagnet='".$panier['idPagnet']."' ";
		$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	}

	$sql2="DELETE FROM `".$nomtablePagnet."` where idClient='".$idClient."'";
	$res2=@mysql_query($sql2) or die ("suppression impossible personnel".mysql_error());

	$sql3="DELETE FROM `".$nomtableVersement."` where idClient='".$idClient."'";
	$res3=@mysql_query($sql3) or die ("suppression impossible personnel".mysql_error());

	$sql4="DELETE FROM `".$nomtableClient."` WHERE idClient=".$idClient;
  	$res4=@mysql_query($sql4) or die ("suppression impossible personnel".mysql_error());

}
if (isset($_POST['btnActiver'])) {
	$idClient=$_POST['idClient'];
	$activer=1;
	$sql3="UPDATE `".$nomtableClient."` set  activer='".$activer."' where idClient=".$idClient;
		  $res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
} elseif (isset($_POST['btnDesactiver'])) {
	$idClient=$_POST['idClient'];
	$activer=0;
	$sql3="UPDATE `".$nomtableClient."` set  activer='".$activer."' where idClient=".$idClient;
		  $res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}


$SommeMontantAverser=0;
$sql2="SELECT * FROM `".$nomtableClient."`";
$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
while ($client = mysql_fetch_array($res2)) {
	$sql12="SELECT montant FROM `".$nomtableBon."` where idClient=".$client['idClient']." ";
	$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
	$montantBon = mysql_fetch_array($res12) ;
	$SommeMontantAverser+=$montantBon[0]-$client['solde'];
}

require('entetehtml.php');
?>

<body>

		<?php
		  require('header.php');
		?>
		<div class="container"><center>
   		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter client
   		</button></center>


		<div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Ajout un Client</h4>
                    </div>
                    <div class="modal-body">
	                    <form name="formulairePersonnel" method="post" action="bon.php">

		            	  <div class="form-group">
						      <label for="inputEmail3" class="control-label">PRENOM <font color="red">*</font></label>
						      <input type="text" class="form-control" id="inputprenom" name="prenom" required="" placeholder="Le prenom du client ici...">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
						      <input type="text" class="form-control" id="inputprenom" required="" name="nom" placeholder="Le nom du client ici...">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">ADRESSE<font color="red">*</font></label>
						      <input type="text" class="form-control" id="inputprenom" name="adresse" required="" placeholder="Adresse du client ici...">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">TELEPHONE<font color="red">*</font></label>
						      <input type="text" class="form-control" id="inputprenom" required="" name="telephone" placeholder="Telephone du client ici...">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                </br>
	                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	                                <button type="submit" name="btnEnregistrerClient" class="btn btn-primary">Enregistrer</button>
	                       </div>
						</form>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
        <div class="container" align="center">
            <ul class="nav nav-tabs">
				<li class="active">
					<a data-toggle="tab" href="#LISTECLIENTS">LISTE DES CLIENTS 
						<?php
							if($_SESSION['proprietaire']==1){ 
						 		echo  ' => Valeur Montant à Verser =  '.number_format($SommeMontantAverser, 2, ',', ' ').' FCFA ';
							}
						  ?>
					</a>
				</li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTECLIENTS">
	        <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">

				<thead>
					<tr>
						<th>Prénom</th>
						<th>Nom</th>
						<th>Adresse</th>
						<th>Numéro téléphone</th>
                        <th>Montant à verser </th>
						<th>Opération</th>
						<th>Activer/Désactiver</th>
					</tr>
				</thead>
                <tfoot>
					<tr>
						<th>Prénom</th>
						<th>Nom</th>
						<th>Adresse</th>
						<th>Numéro téléphone</th>
                        <th>Montant à verser </th>
						<th>Opération</th>
						<th>Activer/Désactiver</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
						$sql2="SELECT * FROM `".$nomtableClient."`";
						$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
						while ($client = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $client['prenom']; ?>  </td>
								<td> <?php echo  $client['nom']; ?>  </td>
								<td> <?php echo  $client['adresse']; ?>  </td>
								<td> <?php echo  $client['telephone']; ?>  </td>
								<?php //<td> <?php echo  $client['solde']; </td> //<th>Solde </th> ?>


									<?php 
									$sql12="SELECT montant FROM `".$nomtableBon."` where idClient=".$client['idClient']." ";
								    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
									$montantBon = mysql_fetch_array($res12) ;

									$sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$client['idClient']." AND verrouiller=1 AND type=0 ";
									$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
									$TotalB = mysql_fetch_array($res12) ;
									$sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$client['idClient']."  ";
									$res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
									$TotalV = mysql_fetch_array($res13) ;

									$montantAverser=$TotalB[0] - $TotalV[0];
									
								
										if($montantAverser>=0){?>
											<td align="right" class="alert alert-danger"> 
											<?php echo ($montantAverser * $_SESSION['devise']).' '.$_SESSION['symbole']; ?> 
											<a   <?php echo  "href=bonPclient.php?c=".$client['idClient'] ; ?> > Details</a>
											</td>
									<?php }
										else{ ?>
											<td align="right" class="alert alert-success"> 
											<?php echo ($montantAverser * $_SESSION['devise']).' '.$_SESSION['symbole']; ?> 
											<a   <?php echo  "href=bonPclient.php?c=".$client['idClient'] ; ?> > Details</a>
											</td>
									<?php	} ?>
                                <td>
									<a href="#" >
        								<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierCli".$client['idClient'] ; ?> /></a>&nbsp;&nbsp;
									<?php if (($client['activer']==0)&& ($montantAverser==0)) { ?>
									<a   href="#" >
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerCli".$client['idClient'] ; ?> /></a>&nbsp;&nbsp;
										
									<?php	} ?>

								</td>
								<?php 
									if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){
								     if ($client['activer']==0) { ?>
												<!--<td><span>Desactiver</span></td>-->
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$client['idClient'] ; ?> >
						                        Activer</button>
												</td>
												<?php
											} else { ?>
												<!--<td><span>Activer</span></td>-->
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$client['idClient'] ; ?> >
												Desactiver</button></td>
									<?php }
										}
										else {
											if ($client['activer']==0) {
									?>
												<td><button type="button" disabled="true" class="btn btn-success" class="btn btn-success" data-toggle="modal"  >
						                        Activer</button>
												</td>
												<?php
											} else { ?>
												<!--<td><span>Activer</span></td>-->
												<td><button type="button" disabled="true" class="btn btn-danger" class="btn btn-success" data-toggle="modal"  >
												Desactiver</button></td>
									<?php }
									}
									?>
							</tr>
					<div class="modal fade" <?php echo  "id=Activer".$client['idClient'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="bon.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment activer ce client</h2>
									     <input type="hidden" name="idClient" <?php echo  "value=".htmlspecialchars($client['idClient'])."" ; ?> >
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
        			<div class="modal fade" <?php echo  "id=Desactiver".$client['idClient'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="bon.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment desactiver ce client</h2>
									     <input type="hidden" name="idClient" <?php echo  "value=".htmlspecialchars($client['idClient'])."" ; ?> >
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
        <div <?php echo  "id=imgmodifierCli".$client['idClient']."" ; ?>   class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier un client</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="bon.php">
						  <?php  echo  '<input type="hidden" name="idClient"    value="'. htmlspecialchars($client['idClient']).'" >'; ?>
							<label for="inputEmail3" class="control-label">PRENOM <font color="red">*</font></label>
							<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom" required=""  value="'. htmlspecialchars($client['prenom']).'">' ; ?>
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre nom" required="" value="'. htmlspecialchars($client['nom']).'" >' ; ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">ADRESSE <font color="red">*</font></label>
								<?php  echo '<input type="text" required="" class="form-control" id="inputprenom" name="adresse" placeholder="adresse" value="'. htmlspecialchars($client['adresse']).'">' ; ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">TELEPHONE <font color="red">*</font></label>
								<input type="text" class="form-control" required="" id="inputprenom" name="telephone" <?php echo "value='".htmlspecialchars($client['telephone'])."'" ; ?> >
								<span class="text-danger" ></span>
							</div>

						    <div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                </br>
						        <button type="button" class="btn btn-default" >Annuler</button>
								<button type="submit" name="btnModifierClient" class="btn btn-primary">Enregistrer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
		<div <?php echo  "id=imgsuprimerCli".$client['idClient']."" ; ?>  class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour supprimer un client</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="bon.php">
						    <?php  echo  '<input type="hidden" name="idClient" value="'. htmlspecialchars($client['idClient']).'" >'; ?>
							<label for="inputEmail3" class="control-label">PRENOM</label>
							<?php  echo '<input type="text" class="form-control" id="inputprenom" name="prenom" value="'. htmlspecialchars($client['prenom']).'" disabled >' ; ?>
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM</label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="nom" value="'. htmlspecialchars($client['nom']).'" disabled >' ; ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">ADRESSE</label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="adresse" value="'. htmlspecialchars($client['adresse']).'" disabled >' ; ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">TELEPHONE</label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="telephone"  value="'. htmlspecialchars($client['telephone']).'" disabled >' ; ?>
								<span class="text-danger" ></span>
							</div>

						    <div class="modal-footer row">
						        <button type="button" class="btn btn-default" >Annuler</button>
								<button type="submit" name="btnSupprimerClient" class="btn btn-primary">Supprimer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
	<div <?php echo  "id=suprimerCli".$client['idClient']."" ; ?>  class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour supprimer un client</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="bon.php">
						    <?php  echo  '<input type="hidden" name="idClient" value="'. htmlspecialchars($client['idClient']).'" >'; ?>
							<label for="inputEmail3" class="control-label">PRENOM</label>
							<?php  echo '<input type="text" class="form-control" id="inputprenom" name="prenom" value="'. htmlspecialchars($client['prenom']).'" disabled >' ; ?>
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM</label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="nom" value="'. htmlspecialchars($client['nom']).'" disabled >' ; ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">ADRESSE</label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="adresse" value="'. htmlspecialchars($client['adresse']).'" disabled >' ; ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">TELEPHONE</label>
								<?php  echo  '<input type="text" class="form-control" id="inputprenom" name="telephone"  value="'. htmlspecialchars($client['telephone']).'" disabled >' ; ?>
								<span class="text-danger" ></span>
							</div>

						    <div class="modal-footer row">
						        <button type="button" class="btn btn-default" >Annuler</button>
								<button type="submit" name="btnSupprimerDefClient" class="btn btn-primary">Supprimer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
					<?php 	}
					?>

				</tbody>
			</table>
        </div>
	</div>
	</div>
	</div>
	</div>

</body>
</html>
