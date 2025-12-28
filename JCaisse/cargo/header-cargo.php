<script>
    if ( window.history.replaceState ) {
		
        window.history.replaceState( null, null, window.location.href );

    }

</script>

<header class="noImpr" >

	<div style="display:none;" >

		<input type="number" id="lcd_Machine" name="lcd" value="<?php echo $_SESSION['lcd']; ?>" />

	</div>

	<div class="row" style="margin-bottom:2px;margin-left:2px">
		<div class="col-sm-1 pull-center" >
		 <img class="img-thumbnail" src="images/logo.png"/>
		</div>
		<div class="col-sm-3 col-sm-offset-8 pull-center" style="border: 1px dotted black;border-radius: 7px;padding:14px" align="center" >
		<span><b><?php echo $_SESSION["labelB"]; ?></b>
		</div>
	</div>


		<nav class="navbar navbar-inverse">

			<!-- Brand and toggle get grouped for better mobile display -->

			<div class="navbar-header">

				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">

					<span class="sr-only">Toggle navigation</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

				</button>

				<a class="navbar-brand" href="#"></a>

				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				<ul class="nav navbar-nav">

					<?php

							if (($_SESSION['proprietaire']==1) || ($_SESSION['gerant']==1)){

								echo ' <li><a href="accueil.php"><b>ACCUEIL</b></a></li>';

							}

							// if ($_SESSION['proprietaire']==1) {

									// echo '<li><a href="javascript:void(0);"><b>RAPPORT</b></a></li>';


								if ($_SESSION['compte']==1 && ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1)) {

									// echo'<li class="dropdown">

									// 		<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span id="menuCompte">COMPTE</span></b><span class="caret"></span></a>

									// 		<ul class="dropdown-menu">

									// 			<li><a href="compte/opComtes.php"><span class="fa fa-" style="font-size:16px"></span> <b><span id="menuListeOp">LISTE DES OPERATIONS</span></b></a></li>

									// 			<li><a href="compte/compte.php"><span class="fa fa-" style="font-size:16px"></span> <b><span id="menuListeCpt">LISTE DES COMPTES</span></b></a></li>

									// 		</ul>

									// 	</li>';
									echo '<li><a href="compte.php"><b><span id="menuCompte">COMPTES</span></b></a></li>';


								}
									// echo '<li><a href="javascript:void(0);"><b>JOURNAL</b></a></li>';
									
									echo'<li class="dropdown">

											<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span id="menuJournal">JOURNAL</span></b><span class="caret"></span></a>

											<ul class="dropdown-menu">';
												if ($_SESSION['proprietaire']==1 || $_SESSION['caissier']==1) {
													echo '
														<li><a href="insertionLigneLight.php"><span class="fa fa-" style="font-size:16px"></span> <b><span id="menuCaisse">CAISSE</span></b></a></li>';
												}
												if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
													echo '<li><a href="historiquecaisse.php"><span class="fa fa-" style="font-size:16px"></span> <b><span id="menuHistorique">HISTORIQUE</span></b></a></li>';
												}

									echo '</ul>
										</li>';
									// echo '<li><a href="javascript:void(0);"><b><span id="menuCaisse">JOURNAL</span></b></a></li>';
									if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['caissier']==1) {
										echo '<li><a href="bon.php"><b><span id="menuClient">CLIENTS</span></b></a></li>';
										echo '<li><a href="cargo/arrivage-manager.php"><b><span id="menuArrivage">ARRIVAGES</span></b></a></li>';
									}
                                    
									echo'<li class="dropdown">

											<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span id="menuPorteur">DEPARTS</span></b><span class="caret"></span></a>

											<ul class="dropdown-menu">
                                                <li><a href="cargo/container-manager.php"><b><span id="menuContainer">CONTAINERS</span></b></a></li>
                                                <li><a href="cargo/vol-manager.php"><b><span id="menuVol">VOLS</span></b></a></li>
											</ul>

										</li>';
									
									if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1) {
										echo '<li><a href="cargo/enregistrement-manager.php"><b><span id="menuEnregistrement">ENREGISTREMENTS</span></b></a></li>';
									}
									if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
										echo '<li><a href="cargo/entrepot-manager.php"><b><span id="menuEntrepot">ENTREPOTS</span></b></a></li>';
									}
									// echo '<li><a href="cargo/catalogue-manager.php"><b>CATALOGUE</b></a></li>';
									if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
										echo '<li class="dropdown">

											<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span id="menuCatalogue">CATALOGUE</span></b><span class="caret"></span></a>

											<ul class="dropdown-menu">

												<li><a href="cargo/catalogue-manager.php"> <b><span id="menuNatBagage">NATURE BAGAGE</span></b></a></li>
												<li><a href="insertionService.php"> <b><span id="menuService">SERVICES</span></b></a></li>
												<li><a href="insertionDepense.php"> <b><span id="menuDepense">DEPENSES</span></b></a></li>

											</ul>

										</li>';
									}

							// }
					?>


				</ul>

				<ul class="nav navbar-nav navbar-right">
					<!-- <li class="dropdown">

						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span id="listeLangue">Français</span></b><span class="caret"></span></a>

						<ul class="dropdown-menu">
							<li><a onclick="changeLanguage('en')"><b> English</b></a></li>
							<li><a onclick="changeLanguage('fr')"><b>Français</b></a></li>
						</ul>

					</li> -->

					<li class="dropdown">

						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b><span id="menuConnexion">CONNEXION</span></b><span class="caret"></span></a>

						<ul class="dropdown-menu">

							<?php



									if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {

										$sqlR="SELECT *

										FROM `aaa-acces` a

										INNER JOIN `aaa-boutique` b ON b.idBoutique = a.idBoutique

										WHERE a.idutilisateur ='".$_SESSION['iduser']."' ";

										$resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());

										while ($boutique = mysql_fetch_array($resR)) {

											if($boutique['nomBoutique']==$_SESSION['nomB'] || $boutique['activer']==0){

												echo '<li ><a style="background-color:#d5d8dc;">

												<form  method="post"  >

													<input type="hidden" name="idBoutique"  value="'.$boutique['idBoutique'].'"  >

													<input type="hidden" name="idBoutique"  value="'.$boutique['idBoutique'].'"  >

													<button disabled="true" type="submit" class="btn btn-warning" name="btnChangerBoutique" >

																	<i class="glyphicon glyphicon-eye-open"></i>

													</button>

													<b>'.$boutique['labelB'].' </b>

												</form>

												</a></li>';

											}

											else{

												echo '<li >
													<div style="background-color:#d5d8dc;">

														<div class="btn-toolbar" style="margin-left:15px;" role="toolbar" aria-label="Toolbar with button groups" style="background-color:#d5d8dc;">
															<div class="btn-group me-2" role="group" aria-label="First group">
																<form method="post" action="accueil.php">
																	<a href="accueil.php?bcb='.$boutique['idBoutique'].'" type="button" class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i></a>
																</form>
															</div>
															<div class="btn-group me-2" role="group" aria-label="Second group">
																<form method="post" action="accueil.php">
																	<a href="accueil.php?bcs='.$boutique['idBoutique'].'" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-tasks"></i></a>
																</form>
															</div>
															<div class="btn-group me-2" role="group" aria-label="Third group">
																<label for="">'.$boutique['labelB'].'</label>
															</div>
														</div>
													</div>
												
												<form hidden method="post"  action="accueil.php">

													<input type="hidden" name="idBoutique"  value="'.$boutique['idBoutique'].'"  >

													<button type="submit" class="btn btn-success" name="btnChangerBoutique" >

														<i class="glyphicon glyphicon-eye-open"></i>

													</button>

													<button type="submit" class="btn btn-primary" name="btnStockBoutique" >

														<i class="glyphicon glyphicon-tasks"></i>

													</button>

													<b>'.$boutique['labelB'].' </b>

												</form>

												</li>';

											}

										}

									}



							?>

							<?php  if ($_SESSION['proprietaire']==1) {

								/*if ($_SESSION['vitrine']==1){

																echo '<li ><a href="vitrine.php"><span class="fa fa-" style="font-size:16px"></span> <b>VITRINE</b></a></li>';

																echo '<li ><a href="commande.php"><span class="fa fa-" style="font-size:16px"></span> <b>COMMANDE</b></a></li>';

														}*/

							echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';



							} ?>



							<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL ('.$_SESSION["prenom"].' '.$_SESSION["nomU"].')</b></a></li>'; ?>

							<li><a href="deconnexion.php"><font color="red"><span class="glyphicon glyphicon-log-out"></span> <b>DECONNEXION</b></font></a>

							</li>

						</ul>

					</li>

			</ul>

			</div>

		</nav>

</header>



