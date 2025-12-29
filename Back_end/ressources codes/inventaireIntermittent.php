<?php 
session_start();
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;
$nomtableClient=$_SESSION['nomB']."-client";
$nomtableBon=$_SESSION['nomB']."-bon";

if(!$_SESSION['iduser']){
	header('Location:index.php');
}

if (isset($_POST['btnEnregistrerClient'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$telephone=htmlspecialchars(trim($_POST['telephone']));
		$adresse=htmlspecialchars(trim($_POST['adresse']));
		
		$sql1="insert into `".$nomtableClient."` (nom,prenom,adresse,telephone) values('".$nom."','".$prenom."','".$adresse."','".$telephone."')";
  		$res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
		$message="Client ajouter avec succes";


		$sql2="SELECT * FROM `".$nomtableClient."`  ORDER BY idClient DESC LIMIT 0,1";
		$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
		$clt = mysql_fetch_array($res2);

		$sql3="insert into `".$nomtableBon."` (idClient,date) values('".$clt['idClient']."','".$dateString."')";
  		$res3=mysql_query($sql3) or die ("insertion client impossible =>".mysql_error() );
		
}elseif (isset($_POST['btnModifierClient'])) {

		$idClient=$_POST['idClient'];
		$nom=$_POST['nom'];
		$prenom=$_POST['prenom'];
		$adresse=$_POST['adresse'];
		$telephone=$_POST['telephone'];
		$sql3="UPDATE `".$nomtableClient."` set  `nom`='".$nom."',prenom='".$prenom."',adresse='".$adresse."',telephone='".$telephone."' where idClient=".$idClient;
		  $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
		  
}elseif (isset($_POST['btnSupprimerClient'])) {

	$idClient=$_POST['idClient'];
	$sql="DELETE FROM `".$nomtableClient."` WHERE idClient=".$idClient;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());

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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
<body>
	
		<?php 
		  require('header.php');
		?>
		<div class="container">
   		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter client
   		</button>
	
	
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
						      <label for="inputEmail3" class="control-label">PRENOM</label>					    
						      <input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">NOM</label>					    
						      <input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre nom">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">Adresse</label>					    
						      <input type="text" class="form-control" id="inputprenom" name="adresse" placeholder="Adresse">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">Telephone</label>					    
						      <input type="text" class="form-control" id="inputprenom" name="telephone" placeholder="Telephone">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="form-group">
						      <label for="inputEmail3" class="control-label">Type</label>					    
						      <input type="text" class="form-control" id="inputprenom" name="type" placeholder="Type">
						      <span class="text-danger" ></span>
						  </div>
						  <div class="modal-footer">
	                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	                                <button type="submit" name="btnEnregistrerClient" class="btn btn-primary">Enregistrer</button>
	                       </div>
						</form>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
        	
	        <table class="table table-bordered table-striped table-condensed">
				<caption>
				<h4>Les client</h4>
				</caption>
				<thead>
					<tr>
						<th>Prenom</th>
						<th>nom</th>
						<th>Adresse</th>
						<th>Numero telephone</th>
						
						
                        <th>Bon </th>
                        <th>Versement </th>
                        <th>Montant à verser </th>
						<th>Operation</th>
						<th>Actver/Desactiver</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						
						$sql2="SELECT * FROM `".$nomtableClient;
						$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
						while ($client = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $client['prenom']; ?>  </td>
								<td> <?php echo  $client['nom']; ?>  </td>
								<td> <?php echo  $client['adresse']; ?>  </td>
								<td> <?php echo  $client['telephone']; ?>  </td>
								<?php //<td> <?php echo  $client['solde']; </td> //<th>Solde </th> ?>  
								
									
								<td align="right">	
									 <?php  
									$sql12="SELECT montant FROM `".$nomtableBon."` where idClient=".$client['idClient']." ";
								    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
								    $Total = mysql_fetch_array($res12) ; 
								    echo $Total[0];  ?>
        							<a   <?php echo  "href=bonPclient.php?c=".$client['idClient'] ; ?> > Details</a>
								</td>
                          
								<td align="right"> 
									
        							<?php echo  $client['solde']; ?> <a <?php echo  "href=versement.php?c=".$client['idClient'] ; ?> > Details </a>
								</td>
                                   
								      <td align="right"> <?php echo $Total[0]-$client['solde'];  ?> </td>
                                <td> 
									<a href="#" >
        								<img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierCli".$client['idClient'] ; ?> /></a>&nbsp;&nbsp;
									<a   href="#" >
										<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerCli".$client['idClient'] ; ?> /></a>
        									
								</td> 
								<?php if ($client['activer']==0) { ?>
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
									     <input type="hidden" name="idClient" <?php echo  "value=". htmlspecialchars($client['idClient'])."" ; ?> >
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
									     <input type="hidden" name="idClient" <?php echo  "value=". htmlspecialchars($client['idClient'])."" ; ?> >
									  </div>
									  <div class="modal-footer">
				                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                            <button type="submit" name="btnDesactiver" class="btn btn-primary">Activer</button>
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
						    <input type="hidden" name="idClient" <?php echo  "value=". htmlspecialchars($client['idClient'])."" ; ?>>
							<label for="inputEmail3" class="control-label">PRENOM</label>					    
							<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  <?php echo  "value=". htmlspecialchars($client['prenom'])."" ; ?> >
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM</label>
								<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre nom"  <?php echo  "value=". htmlspecialchars($client['nom'])."" ; ?> >
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">ADRESSE</label>					    
								<input type="text" class="form-control" id="inputprenom" name="adresse" placeholder="adresse"  <?php echo  "value=". htmlspecialchars($client['adresse'])."" ; ?> >
								<span class="text-danger" ></span>
							</div> 
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">TELEPHONE</label>					    
								<input type="text" class="form-control" id="inputprenom" name="telephone" placeholder="telephone"  <?php echo  "value=". htmlspecialchars($client['telephone'])."" ; ?> >
								<span class="text-danger" ></span>
							</div> 
							
						    <div class="modal-footer row">
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
						    <input type="hidden" name="idClient" <?php echo  "value=". htmlspecialchars($client['idClient'])."" ; ?>>
							<label for="inputEmail3" class="control-label">PRENOM</label>					    
							<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  <?php echo  "value=". htmlspecialchars($client['prenom'])."" ; ?> disabled >
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM</label>
								<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre nom"  <?php echo  "value=". htmlspecialchars($client['nom'])."" ; ?> disabled >
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">ADRESSE</label>					    
								<input type="text" class="form-control" id="inputprenom" name="adresse" placeholder="adresse"  <?php echo  "value=". htmlspecialchars($client['adresse'])."" ; ?> disabled >
								<span class="text-danger" ></span>
							</div> 
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">TELEPHONE</label>					    
								<input type="text" class="form-control" id="inputprenom" name="telephone" placeholder="telephone"  <?php echo  "value=". htmlspecialchars($client['telephone'])."" ; ?> disabled >
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
					<?php 	}
					?>
					
				</tbody>
			</table>
        </div>
		
	</div>
     
</body>
</html>