<?php
/*
Résum� :
Commentaire :
Version : 2.1
see also :
Auteur : Ibrahima DIOP
Date de cr�ation : 5-08-2019
Date derni�re modification :  17-10-2019
*/

session_start();

require('connection.php');

require('declarationVariables.php');

if(!$_SESSION['iduserBack'])
	header('Location:index.php');


if (isset($_POST['btnActiver'])) {
	$idBoutique=$_POST['idBoutique'];
	$activer=1;
	$sql3="UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());

} elseif (isset($_POST['btnDesactiver'])) {
	$idBoutique=$_POST['idBoutique'];
	$activer=0;
	$sql3="UPDATE `aaa-payement-salaire` set  aSalaireAccompagnateur='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
require('entetehtml.php');
?>

<body>

	<?php   require('header.php'); ?>

	<?php  
	if($_SESSION['profil']=="SuperAdmin"){ ?>
	<center>
		<div class="modal-body">
			<form name="formulairePayementSalaire" method="post" action="salaireAccompagnateurs.php">
			  <div>
				<button type="submit" name="payementSalaire" class="btn btn-success"> Recalcul des Salaires </button>
			   </div>
			</form>
		</div>
		</center>						
<?php  
	}


  ?>


		<div class="row">
			<div class="">

			</div>
			<div class="">
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                  <div class="container" align="center">

                     	<?php
                            $somme=0;
							$sql1="SELECT DISTINCT idBoutique FROM `aaa-payement-salaire` where accompagnateur='".$_SESSION['matricule']."'" ;
                            $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
							while($boutiqueP=mysql_fetch_array($res1)) {
    							$sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
    							$res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
    							while($payement=mysql_fetch_array($res4)) {
    							    $somme=$somme+$payement['partAccompagnateur'];

                                }
                            }
    ?>

    <div class="jumbotron noImpr">

        <h2>Aujourd'hui : <?php echo $dateString2; ?></h2>

        <p>Cumul des Salaires du mois en cours : <font color="red"><?php echo $somme; ?> FCFA</font></p>

    </div>

            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES PARTS DES ACCOMPAGNATEURS</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
				    <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
						<thead>
							<tr>
								<th>Accompagnateur</th>
								<th>Boutique</th>
								<th>Etape Accompagnement</th>
								<th>Part Accompagnateur</th>
								<th>Date Calcul</th>
								<th>Virement</th>
								<th>Virement/Annulation</th>
							</tr>
						</thead>
                        <tfoot>
							<tr>
								<th>Accompagnateur</th>
								<th>Boutique</th>
								<th>Etape Accompagnement</th>
								<th>Part Accompagnateur</th>
								<th>Date Calcul</th>
								<th>Virement</th>
								<th>Virement/Annulation</th>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$somme=0;
							$sql1="SELECT DISTINCT idBoutique FROM `aaa-payement-salaire` where accompagnateur='".$_SESSION['matricule']."'" ;
                            $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
							while($boutiqueP=mysql_fetch_array($res1)) {
							$sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
							$res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
							while($payement=mysql_fetch_array($res4)) {
							    $somme=$somme+$payement['partAccompagnateur'];
						?>
									<tr>

										<td> <b><?php echo  $payement['accompagnateur'];  ?></b>  </td>
										<td> <?php echo  $payement['idBoutique']; ?>  </td>
										<td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
										<td> <b><?php echo  $payement['partAccompagnateur']; ?> FCFA  </b> </td>
										<td> <?php echo  $payement['datePS']; ?>  </td>



										<?php

											 if ($payement['aSalaireAccompagnateur']==0) { ?>
												<td><span>En cour...</span></td>
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$payement['idBoutique'] ; ?> >
						                        Virer</button>
												</td>
												<?php
											} else { ?>
												<td><span>Effectif</span></td>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$payement['idBoutique'] ; ?> >
												Annuler</button></td>
											<?php }


											 ?>




										</td>




					<div class="modal fade" <?php echo  "id=Activer".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment effectuer le virement</h2>
									     <input type="hidden" name="idBoutique" <?php echo  "value=". $payement['idBoutique']."" ; ?> >
									  </div>
									  <div class="modal-footer">
				                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                            <button type="submit" name="btnActiver" class="btn btn-primary">Effectuer le virement</button>
				                       </div>
									</form>
			                    </div>

			                </div>
			            </div>
        			</div>
        			<div class="modal fade" <?php echo  "id=Desactiver".$payement['idBoutique'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Annulation</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
									  <div class="form-group">
									     <h2>Voulez vous vraiment annuler le virement</h2>
									     <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($payement['idBoutique'])."" ; ?> >
									  </div>
									  <div class="modal-footer">
				                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                            <button type="submit" name="btnDesactiver" class="btn btn-primary">Annuler le virement</button>
				                       </div>
									</form>
			                    </div>

			                </div>
			            </div>
        			</div>
		<!----------------------------------------------------------->
		<!----------------------------------------------------------->
		<!----------------------------------------------------------->


									</tr>
								<?php
								 }
							   }
							?>
						</tbody>
					</table>
				  </div>

				</div>
			</div>
             </div>

				</div>
			</div>
		</div>

</body>
</html>