<?php

require('declarationVariables.php');
//require('connectionVitrine.php');

echo '
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
';

$hier = new DateTime('-1 day');
$datehier = $hier->format('Y-m-d');
$datehier2 = $hier->format('d-m-Y');

$_SESSION['page'] = 'autre';

if (isset($_GET['bcb'])) {
	// if (isset($_POST['btnChangerBoutique'])) {

	$idBoutique = $_GET['bcb'];
	// $idBoutique=@$_POST["idBoutique"];
	$_SESSION['idBoutique'] = $idBoutique;
	$sql3 = "select * from `aaa-boutique` where idBoutique=" . $_SESSION['idBoutique'];
	$res3 = @mysql_query($sql3);
	if ($tab3 = @mysql_fetch_array($res3)) {
		$_SESSION['nomB'] = $tab3["nomBoutique"];
		$_SESSION['labelB'] = $tab3["labelB"];
		$_SESSION['descriptionB'] = $tab3["description"];
		$_SESSION['imageB']  = $tab3["image"];
		$_SESSION['adresseB'] = $tab3["adresseBoutique"];
		$_SESSION['type'] = $tab3["type"];
		$_SESSION['categorie'] = $tab3["categorie"];
		$_SESSION['dateCB']  = $tab3["datecreation"];
		$_SESSION['activerB']  = $tab3["activer"];
		$_SESSION['caisse'] = $tab3['caisse'];
		$_SESSION['telBoutique'] = $tab3['telephone'];
		$_SESSION['RegistreCom'] = $tab3["RegistreCom"];
		$_SESSION['Ninea']  = $tab3["Ninea"];
		$_SESSION['enConfiguration']  = $tab3["enConfiguration"];
		$_SESSION['vitrine']  = $tab3["vitrine"];
		$_SESSION['importExp'] = $tab3["importExp"];
		$_SESSION['mutuelle'] = $tab3["mutuelle"];
		$_SESSION['compte']  = $tab3["compte"];
		$_SESSION['venterapide']  = $tab3["venterapide"];
		$_SESSION['venterapideEt']  = $tab3["venterapideEt"];
		$_SESSION['infoSup']  = $tab3["infoSup"];
		$_SESSION['configPrix']  = $tab3["configPrix"];
		$_SESSION['listeRemiseClient']  = $tab3["listeRemiseClient"];
		$_SESSION['editionPanier']  = $tab3["editionPanier"];
		$_SESSION['stockForcer'] = $tab3["stockForcer"];
		$_SESSION['versementAccess'] = $tab3["versementAccess"];
		$_SESSION['btnRapportConfig'] = $tab3["btnRapportConfig"];
		$_SESSION['caissierAccess'] = $tab3["caissierAccess"];
		$_SESSION['caissierNoAccess'] = $tab3["caissierNoAccess"];
		$_SESSION['caissierAccessDepot'] = $tab3["caissierAccessDepot"];
		$_SESSION['printAfterEndCart'] = $tab3["printAfterEndCart"];
		$_SESSION['cbm'] = $tab3["cbm"];
		$_SESSION['head_Boutique'] = 1;
		$_SESSION['head_Vitrine'] = 0;
		$_SESSION['Pays'] = $tab3["Pays"];
		$_SESSION['tvaP']  = $tab3["tvaP"];
		$_SESSION['tvaR']  = $tab3["tvaR"];
		$_SESSION['depotAvoir']  = $tab3["depotAvoir"];
		$_SESSION['venteImage']  = $tab3["venteImage"];
		$_SESSION['hotel']  = $tab3["hotel"];
		$_SESSION['bien']  = $tab3["bien"];
		$_SESSION['V1.10'] = $tab3["V1.10"];
		$_SESSION['tampon']  = $tab3["tampon"];

		if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {

			$sql = "select * from `aaa-utilisateur` where idutilisateur='" . $_SESSION['iduser'] . "' ";
			$res = mysql_query($sql);
			if ($tab = mysql_fetch_array($res)) {

				if ($tab["idEntrepot"] != 0 && $tab["idEntrepot"] != null) {

					$_SESSION['entrepot'] = $tab["idEntrepot"];
				} else {
					$_SESSION['entrepot'] = 0;
				}

				if ($tab3["Collecteur"] != 0 && $tab3["Collecteur"] != null) {

					$_SESSION['Collecteur'] = $tab3["Collecteur"];
				} else {

					$_SESSION['Collecteur'] = 0;
				}
			}
		}
		$sql = "select * from `aaa-devise` where Devise='" . $tab3['Pays'] . "'";
		$res = mysql_query($sql);
		if ($tabD = mysql_fetch_array($res)) {
			$_SESSION['symbole'] = $tabD['Symbole'];
			$_SESSION['devise'] = 1;
		}

		echo '
		<script type="text/javascript">
			window.location.href = "accueil.php";
		</script>
		';
	}
}
if (isset($_GET['bcs'])) {
	// if (isset($_POST['btnStockBoutique'])) {

	$idBoutique = $_GET['bcs'];
	// $idBoutique=@$_POST["idBoutique"];
	$_SESSION['idBoutique'] = $idBoutique;
	$sql3 = "select * from `aaa-boutique` where idBoutique=" . $_SESSION['idBoutique'];
	$res3 = @mysql_query($sql3);
	if ($tab3 = @mysql_fetch_array($res3)) {
		$_SESSION['nomB'] = $tab3["nomBoutique"];
		$_SESSION['labelB'] = $tab3["labelB"];
		$_SESSION['descriptionB'] = $tab3["description"];
		$_SESSION['imageB']  = $tab3["image"];
		$_SESSION['adresseB'] = $tab3["adresseBoutique"];
		$_SESSION['type'] = $tab3["type"];
		$_SESSION['categorie'] = $tab3["categorie"];
		$_SESSION['dateCB']  = $tab3["datecreation"];
		$_SESSION['activerB']  = $tab3["activer"];
		$_SESSION['caisse'] = $tab3['caisse'];
		$_SESSION['telBoutique'] = $tab3['telephone'];
		$_SESSION['RegistreCom'] = $tab3["RegistreCom"];
		$_SESSION['Ninea']  = $tab3["Ninea"];
		$_SESSION['enConfiguration']  = $tab3["enConfiguration"];
		$_SESSION['vitrine']  = $tab3["vitrine"];
		$_SESSION['importExp'] = $tab3["importExp"];
		$_SESSION['mutuelle'] = $tab3["mutuelle"];
		$_SESSION['compte']  = $tab3["compte"];
		$_SESSION['versementAccess'] = $tab3["versementAccess"];
		$_SESSION['btnRapportConfig'] = $tab3["btnRapportConfig"];
		$_SESSION['caissierAccess'] = $tab3["caissierAccess"];
		$_SESSION['head_Boutique'] = 0;
		$_SESSION['head_Vitrine'] = 1;
		$_SESSION['Pays'] = $tab3["Pays"];
		$_SESSION['tvaP']  = $tab3["tvaP"];
		$_SESSION['tvaR']  = $tab3["tvaR"];
		$_SESSION['depotAvoir']  = $tab3["depotAvoir"];
		$_SESSION['venteImage']  = $tab3["venteImage"];
		$_SESSION['hotel']  = $tab3["hotel"];
		$_SESSION['bien']  = $tab3["bien"];
		$_SESSION['V1.10'] = $tab3["V1.10"];
		$_SESSION['tampon']  = $tab3["tampon"];

		if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {
			$sql = "select * from `aaa-utilisateur` where idutilisateur='" . $_SESSION['iduser'] . "' ";
			$res = mysql_query($sql);
			if ($tab = mysql_fetch_array($res)) {

				if ($tab["idEntrepot"] != 0 && $tab["idEntrepot"] != null) {

					$_SESSION['entrepot'] = $tab["idEntrepot"];
				} else {
					$_SESSION['entrepot'] = 0;
				}

				if ($tab3["Collecteur"] != 0 && $tab3["Collecteur"] != null) {

					$_SESSION['Collecteur'] = $tab3["Collecteur"];
				} else {

					$_SESSION['Collecteur'] = 0;
				}
			}
		}
		$sql = "select * from `aaa-devise`  where Devise='" . $tab3['Pays'] . "'";
		$res = mysql_query($sql);
		if ($tabD = mysql_fetch_array($res)) {
			$_SESSION['symbole'] = $tabD['Symbole'];
			$_SESSION['devise'] = 1;
		}
		echo '
		<script type="text/javascript">
			window.location.href = "gestionStock.php";
		</script>
		';
	}
}

// $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=0 && datepagej ='".$dateString2."' ORDER BY idPagnet DESC";
// $res2 = mysql_query($sql2) or die ("persoonel requ�te 2".mysql_error());
// $total=0;
// while ($pagnet0 = mysql_fetch_array($res2)){
// 	$total+=$pagnet0['apayerPagnet'];
// }

// $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=0 && datepagej ='".$dateString2."' ORDER BY idPagnet DESC";
// $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
// $total=0;
// while ($pagnet0 = mysql_fetch_array($res2))
// $total+=$pagnet0['apayerPagnet'];

// $sqlV="SELECT DISTINCT p.idPagnet
// 	FROM `".$nomtablePagnet."` p
// 	INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
// 	WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  && p.type=0  ORDER BY p.idPagnet DESC";
// $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
// $T_ventes = 0 ;
// $S_ventes = 0;
// while ($pagnet = mysql_fetch_array($resV)) {
// 	$sqlS="SELECT SUM(apayerPagnet)
// 	FROM `".$nomtablePagnet."`
// 	where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
// 	$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
// 	$S_ventes = mysql_fetch_array($resS);
// 	$T_ventes = $S_ventes[0] + $T_ventes;
// }

// $sqlVH="SELECT DISTINCT p.idPagnet
// 	FROM `".$nomtablePagnet."` p
// 	INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
// 	WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$datehier2."'  && p.type=0  ORDER BY p.idPagnet DESC";
// $resVH = mysql_query($sqlVH) or die ("persoonel requête 2".mysql_error());
// $T_ventesH = 0 ;
// $S_ventesH = 0;
// while ($pagnet = mysql_fetch_array($resVH)) {
// 	$sqlS="SELECT SUM(apayerPagnet)
// 	FROM `".$nomtablePagnet."`
// 	where idClient=0 &&  verrouiller=1 && datepagej ='".$datehier2."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
// 	$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
// 	$S_ventesH = mysql_fetch_array($resS);
// 	$T_ventesH = $S_ventesH[0] + $T_ventesH;
// }

// $sqlP="SELECT * from `".$nomtablePage."` where datepage='".$dateString."' ";
// $resP=mysql_query($sqlP);
// if(mysql_num_rows($resP)){
// 	$sqlA="UPDATE `".$nomtablePage."` set totalVente='".$T_ventes."' WHERE datepage='".$dateString."' ";
// 	$resA=mysql_query($sqlA)or die ("modification page impossible");
// }


if ($_SESSION['cbm'] == 1) {
	require('cargo/header-cargo.php');
} else {


?>

	<header class="noImpr">
		<div style="display:none;">
			<input type="number" id="lcd_Machine" name="lcd" value="<?php echo $_SESSION['lcd']; ?>" />
		</div>
		<div class="row" style="margin-bottom:2px;margin-left:2px">
			<div class="col-sm-1 pull-center">
				<img class="img-thumbnail" src="images/logo.png" />
			</div>
			<div class="col-sm-3 col-sm-offset-8 pull-center" style="border: 1px dotted black;border-radius: 7px;padding:14px" align="center">
				<span><b><?php echo $_SESSION["labelB"]; ?></b>
			</div>
		</div>
		<?php if ($_SESSION['caisse'] == 0 && ($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) { ?>
			<nav class="navbar navbar-custom">
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
						<li>
							<a href="accueil.php"><b>ACCUEIL</b></a>
						</li>
						<?php
						if ($_SESSION['type'] == "Superette") {
							echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
						} else {

							echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
						}

						if (($_SESSION['proprietaire'] == 1) || ($_SESSION['gerant'] == 1)) {
							if ($_SESSION['compte'] == 1) {
								echo '<li><a href="compte.php"> <b>COMPTES</b></a></li>';
							}
						}
						echo ' <li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CAISSE</b><span class="caret"></span></a>
								<ul class="dropdown-menu">';
						if (($_SESSION['caissier'] == 1) || ($_SESSION['vendeur'] == 1)) {
							// if($_SESSION['offline']==1 && $_SESSION['offlineUser']==1){
							// 	echo'<script language="JavaScript">document.location="offline/insertionLigneLight.html"</script>';
							// }else{
							echo '<li><a href="insertionLigneLight.php"><b>VENTES</b></a></li>';

							if ($_SESSION['venteImage'] == 1) {
								echo ' <li><a href="venteParImage.php"><b>VENTE PAR IMAGE</b></a></li>';
							}
							//}
						}
						if ($_SESSION['proprietaire'] == 1) {
							echo ' <li><a href="historiquecaisse.php"><b>JOURNAUX</b></a></li>';
							echo ' <li><a href="historique.php"><b>HISTORIQUE</b></a></li>';
						} else if ($_SESSION['gerant'] == 1) {
							echo ' <li><a href="historique.php"><b>HISTORIQUE</b></a></li>';
						}

						if ($_SESSION['proprietaire'] == 1) {
							echo '<li><a href="actualiserVenteP.php"><b>ACTUALISER</b></a></li>';
						}
						echo ' </ul>
							</li>';
						?>
						<?php if (($_SESSION['caissier'] == 1) || ($_SESSION['vendeur'] == 1)) {
							echo '<li><a href="bon.php"><b>CLIENTS</b></a></li>';
						}
						if ($_SESSION['mutuelle'] == 1) {
							echo ' <li><a href="mutuelle.php"><b>MUTUELLES</b></a></li>';
						}
						echo ' <li><a href="fournisseur.php"><b>FOURNISSEURS</b></a></li>';
						?>
						<?php if ($_SESSION['gerant'] == 1) {
							echo '<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>STOCK</b><span class="caret"></span></a>
								<ul class="dropdown-menu">';
							echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>
										<li><a href="bonsLivraisons.php"><b>BL</b></a></li>';
							echo ' </ul></li>' .
								' <li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CATALOGUE</b><span class="caret"></span></a>
								<ul class="dropdown-menu">
								<li><a href="insertionProduit.php"><b>PRODUITS</b></a></li>
								<li><a href="insertionService.php"><b>SERVICES</b></a></li>
								<li><a href="insertionDepense.php"><b>DEPENSES</b></a></li>
								<li><a href="insertionCategorie.php"><b>CATEGORIES</b></a></li>';
							if ($_SESSION['enConfiguration'] == 0) {
								echo '<li><a href="initialisationCatalogue.php"><b>INITIALISATION</b></a></li>';
							}
							echo ' </ul></li>';
							if ($_SESSION['hotel'] == 1) {

								echo '<li class="dropdown">

										<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>BIENS</b><span class="caret"></span></a>

										<ul class="dropdown-menu">

											<li><a href="insertionChambre.php"><span class="fa fa-" style="font-size:16px"></span> <b>CHAMBRES</b></a></li>

											<li><a href="reservation.php"><span class="fa fa-" style="font-size:16px"></span> <b>RESERVATIONS</b></a></li>

										</ul>

									</li>';
							}
							if ($_SESSION['bien'] == 1) {

								echo '<li class="dropdown">

										<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>LOCATIONS</b><span class="caret"></span></a>

										<ul class="dropdown-menu">

											<li><a href="insertionBien.php"><span class="fa fa-" style="font-size:16px"></span> <b>BIENS</b></a></li>

											<li><a href="reservation.php"><span class="fa fa-" style="font-size:16px"></span> <b>RESERVATIONS</b></a></li>

										</ul>

									</li>';
							}
						} ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">

						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CONNEXION</b><span class="caret"></span></a>
							<ul class="dropdown-menu">


								<?php if (($_SESSION['profil'] == 'Admin') || ($_SESSION['profil'] == 'SuperAdmin')) {
									if ($_SESSION['vitrine'] == 1) {
										echo '<li ><a href="vitrine.php"><span class="fa fa-" style="font-size:16px"></span> <b>VITRINE</b></a></li>';
										echo '<li ><a href="commande.php"><span class="fa fa-" style="font-size:16px"></span> <b>COMMANDE</b></a></li>';
									}
									echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';
									// echo '<li ><a href="boutique.php">BOUTIQUE</a></li>';
								} else {
									if ($_SESSION['hotel'] == 1 && $_SESSION['gerant'] == 1) {
										echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';
									}
								}

								?>

								<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL (' . $_SESSION["prenom"] . ' ' . $_SESSION["nomU"] . ')</b></a></li>'; ?>
								<li><a href="deconnexion.php">
										<font color="red"><span class="glyphicon glyphicon-log-out"></span> <b>DECONNEXION</b>
										</font>
									</a></li>
							</ul>
					</ul>
				</div>
			</nav>
		<?php } else if (($_SESSION['Collecteur'] == 1) && ($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) { ?>

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

						if (($_SESSION['proprietaire'] == 1) || ($_SESSION['gerant'] == 1)) {

							echo ' <li><a href="accueil.php"><b>ACCUEIL</b></a></li>';
						}

						if ($_SESSION['proprietaire'] == 1) {

							if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {


								echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
							} else {

								if ($_SESSION['type'] == "Superette") {
									echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
								} else {

									echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
								}
							}

							if ($_SESSION['compte'] == 1) {

								echo '<li><a href="compte.php"> <b>COMPTES</b></a></li>';
							}
						}



						echo ' <li class="dropdown">';

						echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>JOURNAL</b><span class="caret"></span></a>';


						echo '<ul class="dropdown-menu">';

						if ($_SESSION['caissier'] == 1 || $_SESSION['vendeur'] == 1) {

							echo '<li><a href="insertionLigneLight.php"><b>CAISSE</b></a></li>';

							if ($_SESSION['venteImage'] == 1) {
								echo ' <li><a href="venteParImage.php"><b>VENTE PAR IMAGE</b></a></li>';
							}
						}

						if ($_SESSION['proprietaire'] == 1) {

							echo ' <li><a href="historiquecaisse.php"><b>HISTORIQUE 1</b></a></li>';
							echo ' <li><a href="historique.php"><b>HISTORIQUE 2</b></a></li>';
							echo '<li><a href="actualiserVente.php"><b>ACTUALISER</b></a></li>';
						} else if ($_SESSION['gerant'] == 1) {
							echo ' <li><a href="historique.php"><b>HISTORIQUE</b></a></li>';
						}


						echo '     </ul>

						</li>';

						?>



						<?php if ($_SESSION['vendeur'] == 1 || ($_SESSION['caissier'] == 1) || $_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {

							echo '<li><a href="bon.php"><b>FINANCIERS</b></a></li>';
						}

						if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1 || $_SESSION['caissierAccess'] == 1) {

							echo ' <li><a href="fournisseur.php"><b>COLLECTEURS</b></a></li>';
						}

						?>

						<?php if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {

							echo '<li class="dropdown">

							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>STOCK</b><span class="caret"></span></a>

							<ul class="dropdown-menu">';

							echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';

							if ($_SESSION['importExp'] == 1) {

								echo ' <li><a href="transfertStock.php"><b>TRANSFERT STOCK</b></a></li>';
								echo ' <li><a href="proformaClient.php"><b>FACTURE PROFORMA</b></a></li>';
							}

							echo '<li><a href="entrepot.php"><b>ENTREPOT STOCK</b></a></li>';

							echo ' </ul></li>' .

								' <li class="dropdown">

							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CATALOGUE</b><span class="caret"></span></a>

							<ul class="dropdown-menu">

							<li><a href="insertionProduit.php"><b>PRODUITS</b></a></li>
							<li><a href="insertionService.php"><b>SERVICES</b></a></li>
							<li><a href="insertionDepense.php"><b>DEPENSES</b></a></li>
							<li><a href="insertionCategorie.php"><b>CATEGORIES</b></a></li>';

							if ($_SESSION['enConfiguration'] == 0) {

								echo '<li><a href="initialisationCatalogue.php"><b>INITIALISATION</b></a></li>';
							}

							echo ' </ul></li>';
						} else if ($_SESSION['gestionnaire'] == 1) {

							echo '<li class="dropdown">

				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>STOCK</b><span class="caret"></span>';
							if (mysql_num_rows($resPro)) {
								echo '<span id="intro" style="margin-top: -20px;margin-right: -15px;background: green;color: white;" class="badge bg-success glyphicon glyphicon-ok intro"> </span>';
							}
							echo '</a>';

							echo '<ul class="dropdown-menu">';
							echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';

							echo '<li><a href="entrepot.php"><b>ENTREPOT STOCK</b></a></li>';
							echo ' </ul></li>';

							echo '<li class="dropdown">

				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CATALOGUE</b><span class="caret"></span></a>

				<ul class="dropdown-menu">
				
				<li><a href="insertionProduit.php"><b>PRODUITS</b></a></li>
				<li><a href="insertionService.php"><b>SERVICES</b></a></li>
				<li><a href="insertionDepense.php"><b>DEPENSES</b></a></li>
				<li><a href="insertionCategorie.php"><b>CATEGORIES</b></a></li>';

							echo '<li><a href="insertionCategorie.php"><b>CATEGORIES</b></a></li>';

							if ($_SESSION['enConfiguration'] == 0) {

								echo '<li><a href="initialisationCatalogue.php"><b>INITIALISATION</b></a></li>';
							}

							echo ' </ul></li>';
						}

						// else if ($_SESSION['vitrine']==1) {

						// 	echo '<li><a href="vitrine/tbVitrine"><b>TABLEAU DE BORD</b></a></li>';

						// 	echo '<li><a href="vitrine/vitrine"><b>VITRINE</b></a></li>';

						// 	echo '<li><a href="vitrine/commande"><b>COMMANDES</b></a></li>';

						// 	echo '<li><a href="vitrine/clientVisiteur"><b>CLIENTS</b></a></li>';

						// }

						?>

					</ul>

					<ul class="nav navbar-nav navbar-right">

						<?php if (($_SESSION['profil'] == 'Admin') || ($_SESSION['profil'] == 'SuperAdmin') || ($_SESSION['proprietaire'] == 1) || ($_SESSION['gestionnaire'] == 1) || ($_SESSION['vitrineU'] == 1)) {

							if ($_SESSION['vitrine'] == 1) {



								$req10 = $bddV->prepare("SELECT * FROM panier p

								INNER JOIN ligne l ON l.idPanier = p.idPanier

								WHERE l.idBoutique =:idBoutique

								AND p.finaliser=1

								AND p.confirmer=0

								AND p.livrer=0

								AND p.annuler=0

								AND p.retourner=0

								GROUP BY p.idPanier DESC");

								$req10->execute(array(

									'idBoutique' => $_SESSION['idBoutique']

								)) or die(print_r($req10->errorInfo()));

								$commandeEnCours = $req10->rowCount();



								$req2 = $bddV->prepare("SELECT upToDate from boutique where idBoutique = :idB ");

								//var_dump($req2);

								$req2->execute(array(

									'idB' =>  $_SESSION['idBoutique']

								)) or die(print_r($req2->errorInfo()));

								$b = $req2->fetch();



								if (($b['upToDate'] == 1) || ($commandeEnCours != 0)) {

									# code...

									echo '<li class="dropdown">

											<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>E-COMMERCE</b>

											<span id="intro" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: gold;position: absolute;" class="badge bg-warning intro">N</span>

											<span class="caret"></span></a>

											<ul class="dropdown-menu">

												<li hidden><a href="vitrine/tbVitrine"><span class="fa fa-" style="font-size:16px"></span> <b>TABLEAU DE BORD</b></a></li>
												<li><a href="https://www.yaatout.org/visualisation.php?boutique=' . $_SESSION['nomB'] . '" target="_blank"><span class="fa fa-" style="font-size:16px"></span><b>VISUALISATION</b></a></li>

												<li><a href="vitrine/vitrine"><span class="fa fa-" style="font-size:16px"></span> <b>VITRINE</b>';

									if ($b['upToDate'] == 1) {

										# code...

										echo '<span id="intro" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: red;color: white;position: absolute;" class="badge bg-warning intro">V</span>';
									}

									echo '</a></li>

												<li><a href="vitrine/commande"><span class="fa fa-" style="font-size:16px"></span> <b>COMMANDES</b>';

									if ($commandeEnCours != 0) {

										# code...

										echo '<span id="intro" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: green;position: absolute;" class="badge bg-warning intro">C</span>';
									}

									echo '</a></li>

												<li><a href="vitrine/clientVisiteur"><span class="fa fa-" style="font-size:16px"></span> <b>CLIENTS</b></a></li>

											</ul>

										</li>';
								} else {

									# code...

									echo '<li class="dropdown">

											<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>E-COMMERCE</b>

											<span class="caret"></span></a>

											<ul class="dropdown-menu">

												<li hidden><a href="vitrine/tbVitrine"><span class="fa fa-" style="font-size:16px"></span> <b>TABLEAU DE BORD</b></a></li>
												<li><a href="https://www.yaatout.org/visualisation.php?boutique=' . $_SESSION['nomB'] . '" target="_blank"><span class="fa fa-" style="font-size:16px"></span> <b>VISUALISATION</b></a></li>

												<li><a href="vitrine/vitrine"><span class="fa fa-" style="font-size:16px"></span> <b>VITRINE</b>

												</a></li>

												<li><a href="vitrine/commande"><span class="fa fa-" style="font-size:16px"></span> <b>COMMANDES</b></a></li>

												<li><a href="vitrine/clientVisiteur"><span class="fa fa-" style="font-size:16px"></span> <b>CLIENTS</b></a></li>

											</ul>

										</li>';
								}
							}
						} ?>

						<li class="dropdown">

							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CONNEXION</b><span class="caret"></span></a>

							<ul class="dropdown-menu">

								<?php



								if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {

									$sqlR = "SELECT *

									FROM `aaa-acces` a

									INNER JOIN `aaa-boutique` b ON b.idBoutique = a.idBoutique

									WHERE a.idutilisateur ='" . $_SESSION['iduser'] . "' ";

									$resR = mysql_query($sqlR) or die("persoonel requête 2" . mysql_error());

									while ($boutique = mysql_fetch_array($resR)) {

										if ($boutique['nomBoutique'] == $_SESSION['nomB'] || $boutique['activer'] == 0) {

											echo '<li ><a style="background-color:#d5d8dc;">

											<form  method="post"  >

												<input type="hidden" name="idBoutique"  value="' . $boutique['idBoutique'] . '"  >

												<input type="hidden" name="idBoutique"  value="' . $boutique['idBoutique'] . '"  >

												<button disabled="true" type="submit" class="btn btn-warning" name="btnChangerBoutique" >

																<i class="glyphicon glyphicon-eye-open"></i>

												</button>

												<b>' . $boutique['labelB'] . ' </b>

											</form>

											</a></li>';
										} else {

											echo '<li >
												<div style="background-color:#d5d8dc;">

													<div class="btn-toolbar" style="margin-left:15px;" role="toolbar" aria-label="Toolbar with button groups" style="background-color:#d5d8dc;">
														<div class="btn-group me-2" role="group" aria-label="First group">
															<form method="post" action="accueil.php">
																<a href="accueil.php?bcb=' . $boutique['idBoutique'] . '" type="button" class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i></a>
															</form>
														</div>
														<div class="btn-group me-2" role="group" aria-label="Second group">
															<form method="post" action="accueil.php">
																<a href="accueil.php?bcs=' . $boutique['idBoutique'] . '" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-tasks"></i></a>
															</form>
														</div>
														<div class="btn-group me-2" role="group" aria-label="Third group">
															<label for="">' . $boutique['labelB'] . '</label>
														</div>
													</div>
												</div>
											
											<form hidden method="post"  action="accueil.php">

												<input type="hidden" name="idBoutique"  value="' . $boutique['idBoutique'] . '"  >

												<button type="submit" class="btn btn-success" name="btnChangerBoutique" >

													<i class="glyphicon glyphicon-eye-open"></i>

												</button>

												<button type="submit" class="btn btn-primary" name="btnStockBoutique" >

													<i class="glyphicon glyphicon-tasks"></i>

												</button>

												<b>' . $boutique['labelB'] . ' </b>

											</form>

											</li>';
										}
									}
								}



								?>

								<?php if ($_SESSION['proprietaire'] == 1) {

									/*if ($_SESSION['vitrine']==1){

															echo '<li ><a href="vitrine.php"><span class="fa fa-" style="font-size:16px"></span> <b>VITRINE</b></a></li>';

															echo '<li ><a href="commande.php"><span class="fa fa-" style="font-size:16px"></span> <b>COMMANDE</b></a></li>';

													}*/

									echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';
								} ?>



								<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL (' . $_SESSION["prenom"] . ' ' . $_SESSION["nomU"] . ')</b></a></li>'; ?>

								<li><a href="deconnexion.php">
										<font color="red"><span class="glyphicon glyphicon-log-out"></span> <b>DECONNEXION</b>
										</font>
									</a>

								</li>

							</ul>

						</li>

					</ul>

				</div>

			</nav>

		<?php } else {  ?>
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
						if (($_SESSION['proprietaire'] == 1) || ($_SESSION['gerant'] == 1)) {
							if ($_SESSION['idBoutique'] == 194 && $_SESSION['proprietaire'] != 1) {
							} else {
								echo ' <li><a href="accueil.php"><b>ACCUEIL</b></a></li>';
							}
						}
						if ($_SESSION['proprietaire'] == 1) {
							if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {
							} else {
								if ($_SESSION['type'] == "Superette") {
									echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
								} else {

									echo '<li><a href="rapport.php"><b>RAPPORT</b></a></li>';
								}
							}
							if ($_SESSION['compte'] == 1) {
								echo '<li><a href="compte.php"> <b>COMPTES</b></a></li>';
							}
						}

						echo ' <li class="dropdown">';
						if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste") && ($_SESSION['importExp'] == 1)) {
							if (($_SESSION['gerant'] == 1) || ($_SESSION['caissier'] == 1)) {
								echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CAISSE</b><span class="caret"></span></a>';
							}
						} else {
							echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CAISSE</b><span class="caret"></span></a>';
						}
						echo '<ul class="dropdown-menu">';
						if ($_SESSION['caissier'] == 1) {
							// if($_SESSION['offline']==1 && $_SESSION['offlineUser']==1){
							// 	echo'<script language="JavaScript">document.location="offline/insertionLigneLight.html"</script>';
							// }else{
							echo '<li><a href="insertionLigneLight.php"><b>VENTES</b></a></li>';

							if ($_SESSION['venteImage'] == 1) {
								echo ' <li><a href="venteParImage.php"><b>VENTE PAR IMAGE</b></a></li>';
							}
							if ($_SESSION['V1.10'] == 1) {
								echo ' <li><a href="vente.php"><b>CAISSE</b></a></li>';
							}

							//}
						}
						if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {
							if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {
								echo ' <li><a href="historiquecaisse.php"><b>JOURNAUX</b></a></li>';
								echo ' <li><a href="historique.php"><b>HISTORIQUE</b></a></li>';
							}
						} else {
							if ($_SESSION['proprietaire'] == 1) {
								echo ' <li><a href="historiquecaisse.php"><b>JOURNAUX</b></a></li>';
								echo ' <li><a href="historique.php"><b>HISTORIQUE</b></a></li>';
							} else if ($_SESSION['gerant'] == 1) {
								echo ' <li><a href="historique.php"><b>HISTORIQUE</b></a></li>';
							}
						}
						if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {
							if ($_SESSION['proprietaire'] == 1) {
								echo '<li><a href="actualiserVenteP.php"><b>ACTUALISER</b></a></li>';
							}
						} else if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {
							if ($_SESSION['proprietaire'] == 1) {
								echo '<li><a href="actualiserVenteET.php"><b>ACTUALISER</b></a></li>';
							}
						} else {
							if ($_SESSION['proprietaire'] == 1) {
								echo '<li><a href="actualiserVente.php"><b>ACTUALISER</b></a></li>';
							}
						}


						echo '     </ul>
							</li>';
						?>

						<?php if ($_SESSION['caissier'] == 1 || $_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {
							echo '<li><a href="bon.php"><b>CLIENTS</b></a></li>';
							if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {
								if ($_SESSION['proprietaire'] == 0 && $_SESSION['gerant'] == 0 && $_SESSION['importExp'] == 1) {
									echo ' <li><a href="transfert-entrepot.php"><b>TRANSFERTS</b></a></li>';
									echo ' <li><a href="proformaClient.php"><b>FACTURE PROFORMA</b></a></li>';
								}
								if ($_SESSION['caissier'] == 1 && $_SESSION['proprietaire'] == 0 && $_SESSION['gerant'] == 0 && $_SESSION['gestionnaire'] == 0) {
									echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';
								}
							}
						}
						if ($_SESSION['proprietaire'] == 1 && ($_SESSION['mutuelle'] == 1 && $_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {
							echo ' <li><a href="mutuelle.php"><b>MUTUELLES</b></a></li>';
						}
						if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1 || $_SESSION['caissierAccess'] == 1) {
							echo ' <li><a href="fournisseur.php"><b>FOURNISSEURS</b></a></li>';
						}
						?>
						<?php if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {
							echo '<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>STOCK</b><span class="caret"></span></a>
								<ul class="dropdown-menu">';
							if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {
								echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>
										<li><a href="bonsLivraisons.php"><b>BL</b></a></li>';
							} else if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {
								echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';
								if ($_SESSION['importExp'] == 1) {
									echo ' <li><a href="transfertStock.php"><b>TRANSFERT STOCK</b></a></li>';
									echo ' <li><a href="proformaClient.php"><b>FACTURE PROFORMA</b></a></li>';
								}
								echo '<li><a href="entrepot.php"><b>ENTREPOT STOCK</b></a></li>';
							} else {
								echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';
								if ($_SESSION['proprietaire'] == 1) {
									echo '<li><a href="inventaireStockPartielle.php"><b>INVENTAIRE PARTIELLE</b></a></li>
										<li><a href="#"><b>INVENTAIRE ANNUEL</b></a></li>
										>';
								}
							}
							echo ' </ul></li>' .
								' <li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CATALOGUE</b><span class="caret"></span></a>
								<ul class="dropdown-menu">
								<li><a href="insertionProduit.php"><b>PRODUITS</b></a></li>
								<li><a href="insertionService.php"><b>SERVICES</b></a></li>
								<li><a href="insertionDepense.php"><b>DEPENSES</b></a></li>
								<li><a href="insertionCategorie.php"><b>CATEGORIES</b></a></li>';
							if ($_SESSION['enConfiguration'] == 0) {
								echo '<li><a href="initialisationCatalogue.php"><b>INITIALISATION</b></a></li>';
							}
							echo ' </ul></li>';
							if ($_SESSION['hotel'] == 1) {

								echo '<li class="dropdown">

										<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>BIENS</b><span class="caret"></span></a>

										<ul class="dropdown-menu">

											<li><a href="insertionChambre.php"><span class="fa fa-" style="font-size:16px"></span> <b>CHAMBRES</b></a></li>

											<li><a href="reservation.php"><span class="fa fa-" style="font-size:16px"></span> <b>RESERVATIONS</b></a></li>

										</ul>

									</li>';
							}
							if ($_SESSION['bien'] == 1) {

								echo '<li class="dropdown">

										<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>LOCATIONS</b><span class="caret"></span></a>

										<ul class="dropdown-menu">

											<li><a href="insertionBien.php"><span class="fa fa-" style="font-size:16px"></span> <b>BIENS</b></a></li>

											<li><a href="reservation.php"><span class="fa fa-" style="font-size:16px"></span> <b>RESERVATIONS</b></a></li>

										</ul>

									</li>';
							}
						} else if ($_SESSION['gestionnaire'] == 1) {
							echo '<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>STOCK</b><span class="caret"></span></a>
					<ul class="dropdown-menu">';
							if (($_SESSION['type'] == "Pharmacie") && ($_SESSION['categorie'] == "Detaillant")) {
								echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>
							<li><a href="bonsLivraisons.php"><b>BL</b></a></li>';
							} else if (($_SESSION['type'] == "Entrepot") && ($_SESSION['categorie'] == "Grossiste")) {
								echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';
								if ($_SESSION['importExp'] == 1) {
									echo ' <li><a href="transfertStock.php"><b>TRANSFERT STOCK</b></a></li>';
									echo ' <li><a href="proformaClient.php"><b>FACTURE PROFORMA</b></a></li>';
								}
								echo '<li><a href="entrepot.php"><b>ENTREPOT STOCK</b></a></li>';
							} else {

								echo '<li class="dropdown">

						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>STOCK</b><span class="caret"></span></a>
	
						<ul class="dropdown-menu">';
								echo '<li><a href="gestionStock.php"><b>GESTION STOCK</b></a></li>';
								if ($_SESSION['proprietaire'] == 1 || $_SESSION['gestionnaire'] == 1) {
									echo '<li><a href="inventaireStockPartielle.php"><b>INVENTAIRE PARTIELLE</b></a></li>
								<li><a href="#"><b>INVENTAIRE ANNUEL</b></a></li>
								';
								}
								echo ' </ul></li>';
							}
							echo ' </ul></li>' .
								' <li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CATALOGUE</b><span class="caret"></span></a>
					<ul class="dropdown-menu">
					<li><a href="insertionProduit.php"><b>PRODUITS</b></a></li>
					<li><a href="insertionService.php"><b>SERVICES</b></a></li>
					<li><a href="insertionDepense.php"><b>DEPENSES</b></a></li>
					<li><a href="insertionCategorie.php"><b>CATEGORIES</b></a></li>';
							if ($_SESSION['enConfiguration'] == 0) {
								echo '<li><a href="initialisationCatalogue.php"><b>INITIALISATION</b></a></li>';
							}
							echo ' </ul></li>';
							if ($_SESSION['hotel'] == 1) {

								echo '<li class="dropdown">

							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>BIENS</b><span class="caret"></span></a>

							<ul class="dropdown-menu">

								<li><a href="insertionChambre.php"><span class="fa fa-" style="font-size:16px"></span> <b>CHAMBRES</b></a></li>

								<li><a href="reservation.php"><span class="fa fa-" style="font-size:16px"></span> <b>RESERVATIONS</b></a></li>

							</ul>

						</li>';
							}
							if ($_SESSION['bien'] == 1) {

								echo '<li class="dropdown">

							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>LOCATIONS</b><span class="caret"></span></a>

							<ul class="dropdown-menu">

								<li><a href="insertionBien.php"><span class="fa fa-" style="font-size:16px"></span> <b>BIENS</b></a></li>

								<li><a href="reservation.php"><span class="fa fa-" style="font-size:16px"></span> <b>RESERVATIONS</b></a></li>

							</ul>

						</li>';
							}
						}
						// else if ($_SESSION['vitrine']==1) {
						// 	echo '<li><a href="vitrine/tbVitrine"><b>TABLEAU DE BORD</b></a></li>';
						// 	echo '<li><a href="vitrine/vitrine"><b>VITRINE</b></a></li>';
						// 	echo '<li><a href="vitrine/commande"><b>COMMANDES</b></a></li>';
						// 	echo '<li><a href="vitrine/clientVisiteur"><b>CLIENTS</b></a></li>';
						// }
						?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if (($_SESSION['profil'] == 'Admin') || ($_SESSION['profil'] == 'SuperAdmin') || ($_SESSION['proprietaire'] == 1)) {
						} ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><b>CONNEXION</b><span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?php

								if ($_SESSION['proprietaire'] == 1 || $_SESSION['gerant'] == 1) {
									$sqlR = "SELECT *
										FROM `aaa-acces` a
										INNER JOIN `aaa-boutique` b ON b.idBoutique = a.idBoutique
										WHERE a.idutilisateur ='" . $_SESSION['iduser'] . "' ";
									$resR = mysql_query($sqlR) or die("persoonel requête 2" . mysql_error());
									while ($boutique = mysql_fetch_array($resR)) {
										if ($boutique['nomBoutique'] == $_SESSION['nomB'] || $boutique['activer'] == 0) {
											echo '<li ><a style="background-color:#d5d8dc;">
												<form  method="post"  >
													<input type="hidden" name="idBoutique"  value="' . $boutique['idBoutique'] . '"  >
													<input type="hidden" name="idBoutique"  value="' . $boutique['idBoutique'] . '"  >
													<button disabled="true" type="submit" class="btn btn-warning" name="btnChangerBoutique" >
																	<i class="glyphicon glyphicon-eye-open"></i>
													</button>
													<b>' . $boutique['labelB'] . ' </b>
												</form>
												</a></li>';
										} else {
											echo '<li >
												
													<div style="background-color:#d5d8dc;">

														<div class="btn-toolbar" style="margin-left:15px;" role="toolbar" aria-label="Toolbar with button groups" style="background-color:#d5d8dc;">
															<div class="btn-group me-2" role="group" aria-label="First group">
																<form method="post" action="accueil.php">
																	<a href="accueil.php?bcb=' . $boutique['idBoutique'] . '" type="button" class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i></a>
																</form>
															</div>
															<div class="btn-group me-2" role="group" aria-label="Second group">
																<form method="post" action="accueil.php">
																	<a href="accueil.php?bcs=' . $boutique['idBoutique'] . '" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-tasks"></i></a>
																</form>
															</div>
															<div class="btn-group me-2" role="group" aria-label="Third group">
																<label for="">' . $boutique['labelB'] . '</label>
															</div>
														</div>
													</div>
												<a hidden style="background-color:#d5d8dc;">
												<form  method="post"  >
													<input type="hidden" name="idBoutique"  value="' . $boutique['idBoutique'] . '"  >
													<button type="submit" class="btn btn-success" name="btnChangerBoutique" >
																	<i class="glyphicon glyphicon-eye-open"></i>
													</button>

													<button type="submit" class="btn btn-primary" name="btnStockBoutique" >
																	<i class="glyphicon glyphicon-tasks"></i>
													</button>
													<b>' . $boutique['labelB'] . ' </b>
												</form>
												</a></li>';
										}
									}
								}

								?>
								<?php if ($_SESSION['proprietaire'] == 1) {
									/*if ($_SESSION['vitrine']==1){
																echo '<li ><a href="vitrine.php"><span class="fa fa-" style="font-size:16px"></span> <b>VITRINE</b></a></li>';
																echo '<li ><a href="commande.php"><span class="fa fa-" style="font-size:16px"></span> <b>COMMANDE</b></a></li>';
														}*/
									echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';
								} else {
									if ($_SESSION['hotel'] == 1 && $_SESSION['gerant'] == 1) {
										echo '<li ><a href="personnel.php"><span class="fa fa-group" style="font-size:16px"></span> <b>PERSONNEL</b></a></li>';
									}
								} ?>

								<?php echo '<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <b>PROFIL (' . $_SESSION["prenom"] . ' ' . $_SESSION["nomU"] . ')</b></a></li>'; ?>
								<li><a href="deconnexion.php">
										<font color="red"><span class="glyphicon glyphicon-log-out"></span> <b>DECONNEXION</b>
										</font>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		<?php } ?>
	</header>


<?php } ?>