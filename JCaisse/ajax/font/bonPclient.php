<?php
session_start();
if(!$_SESSION['iduser']){
	header('Location:../index.php');
	}
require('connection.php');

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
$dateString2=$annee.'-'.$mois.'-'.$jour;

$nomtableClient=$_SESSION['nomB']."-client";
$nomtableVersement=$_SESSION['nomB']."-versement";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableBon=$_SESSION['nomB']."-bon";



	$idClient=htmlspecialchars(trim($_GET['c']));
	$sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";
	$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
	$client = mysql_fetch_array($res3);

	/*$sql9="SELECT * FROM `".$nomtablePagnet."` where idClient=".$idClient."";
    $res9=mysql_query($sql9) or die ("select nomtablePagnet impossible =>".mysql_error());
    $pagnet = mysql_fetch_array($res14);
    $totalp=$pagnet['totalp']+$stock['prixunitaire'];*/


if (isset($_POST['btnEnregistrerCodeBarre'])) {
	$date = new DateTime();
	$annee =$date->format('Y');
	$mois =$date->format('m');
	$jour =$date->format('d');
	$dateString2=$annee.'-'.$mois.'-'.$jour;
	$resultat=" =ko"+htmlspecialchars($_POST['codeBarre']);

	if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {
		$codeBarre=htmlspecialchars(trim($_POST['codeBarre']));
		$idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
		$codeBrute=explode('-', $codeBarre);
		$idStock=$codeBrute[0];
		$idDesignation=$codeBrute[1];
		//$numero=$codeBrute[2];

		$totalp=0;
		$tailleTableau=count($codeBrute);


				if ($tailleTableau!=2){
					$sql4="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock."";
							$res4=mysql_query($sql4) or die ("select stock impossible =>".mysql_error());
							$stock = mysql_fetch_array($res4);
							$quantiteStockCourant=$stock['quantiteStockCourant'];
					if ($quantiteStockCourant>0) {
					  $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation."";

						$res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
						$design = mysql_fetch_array($res6);
						if ($tailleTableau==3) {
							$numero=$codeBrute[2];
							if ( $numero==2) { //Paquet ,douzaine, caisse
			                    $quantiteCourant=$quantiteStockCourant-$design['nbreArticleUniteStock'];
								if ($quantiteCourant>=0){
									 $sql7="insert into `".$nomtableLigne."` (designation, idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet)
																		 values('".$design['designation']."',".$idStock.",'".$stock['uniteStock']."',".$stock['prixuniteStock'].",'1',".$stock['prixuniteStock'].",'".$idPagnet."')";
									$res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

									$sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantiteCourant."' where idStock=".$idStock;
									$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

									$sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
									$res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
									$pagnet = mysql_fetch_array($res14);
									$totalp=$pagnet['totalp']+$stock['prixuniteStock'];
									}


								}
			                else if ($numero==1) { //ARTic
														$sql7="insert into `".$nomtableLigne."` (designation, idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet)
																								 values('".$design['designation']."',".$idStock.",'article','".$stock['prixunitaire']."','1','".$stock['prixunitaire']."','".$idPagnet."')";
														$res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
														 //Upadate Stock
														 var_dump($quantiteStockCourant);
														$quantiteCourant=$quantiteStockCourant-1;
														var_dump($quantiteCourant);
														 $sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantiteCourant."' where idStock=".$idStock;
														 $res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

														$sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
														$res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
														$pagnet = mysql_fetch_array($res14);
														$totalp=$pagnet['totalp']+$stock['prixunitaire'];
								       }
						}
						elseif ($tailleTableau==4) {
								$sql7="insert into `".$nomtableLigne."` (designation, idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet)
																		 values('".$design['designation']."',".$idStock.",'article','".$stock['prixunitaire']."','1','".$stock['prixunitaire']."','".$idPagnet."')";
								$res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

								//Upadate Stock
								var_dump($quantiteStockCourant);
							 $quantiteCourant=$quantiteStockCourant-1;
							 var_dump($quantiteCourant);
								 $sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantiteCourant."' where idStock=".$idStock;
								 $res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

								$sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
								$res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
								$pagnet = mysql_fetch_array($res14);
								$totalp=$pagnet['totalp']+$stock['prixunitaire'];
							}
														//$totalp=$Total[0];
						$sql15="UPDATE `".$nomtablePagnet."` set totalp=".$totalp.",apayerPagnet=".$totalp." where idPagnet=".$idPagnet;
						$res15=mysql_query($sql15) or die ("update Pagnet impossible =>".mysql_error());
						$resultat="ok";
					}
				}

			if ($tailleTableau==2)
			{
				$idDesignation=$codeBrute[0];
				$numero=$codeBrute[1];
				$sql17="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
			    $res17=mysql_query($sql17) or die ("select stock impossible 1 =>".mysql_error());
				$design = mysql_fetch_array($res17) ;

					$sql16="insert into `".$nomtableLigne."` (designation,prixunitevente,quantite,prixtotal,idPagnet)
					values('".$design['designation']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.")";

					$res16=mysql_query($sql16) or die ("insertion pagnier impossible 16  =>".mysql_error() );

					$sql18="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;

					$res18=mysql_query($sql18) or die ("select stock impossible 2 =>".mysql_error());
					$pagnet = mysql_fetch_array($res18);
					$totalp=$pagnet['totalp']+$design['prix'];

				//$totalp=$Total[0];
				$sql15="UPDATE `".$nomtablePagnet."` set totalp=".$totalp.",apayerPagnet=".$totalp." where idPagnet=".$idPagnet;
				$res15=mysql_query($sql15) or die ("update Pagnet impossible =>".mysql_error());
			}

										//$resultat=$codeBarre;
	}
	$sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
	$res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
	$Total = mysql_fetch_array($res18) ;
	$sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString2."' where idClient=".$idClient;
	$res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

}

if (isset($_POST['btnSavePagnet'])) {

	 $sql13='SELECT * from  `'.$nomtablePagnet.'` order by idPagnet desc LIMIT 0,1';
	 $res13=mysql_query($sql13) or die ("select pagnier impossible =>".mysql_error() );
	 $pagnier = mysql_fetch_array($res13);
	 if($pagnier){
		$sql14="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".$pagnier['idPagnet'];
        $res14=mysql_query($sql14) or die ("update pagnier impossible save pagnet =>".mysql_error());

	 }

		$date = new DateTime();
		$annee =$date->format('Y');
		$mois =$date->format('m');
		$jour =$date->format('d');
		$dateString2=$jour.'-'.$mois.'-'.$annee;
		$type='Bon';
		$sql9="insert into `".$nomtablePagnet."` (datepagej,idClient) values('".$dateString2."','".$idClient."')";
  		$res9=mysql_query($sql9) or die ("insertion pagnier impossible =>".mysql_error() );
	}

if (isset($_POST['btnImprimerFacture'])) {

    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".$_POST['idPagnet'];
    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
  }

if (isset($_POST['btnRetour'])) {

	$numligne=$_POST['numligne'];
	$idStock=$_POST['idStock'];
	$designation=$_POST['designation'];
	$idPagnet=$_POST['idPagnet'];
	$quantite=$_POST['quantite'];
	$unitevente=$_POST['unitevente'];
	$prixunitevente=$_POST['prixunitevente'];
	$prixtotal=$_POST['prixtotal'];
	$totalp=$_POST['totalp'];
	if ($unitevente=="Article" || $unitevente=="article") {  //si l'unité est Article

			//on fait la suppression de cette ligne dans la table ligne
			$sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
			$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

			//on cherche la quantité de stock en cour dans son stock d'origine
			$sql4="SELECT quantiteStockCourant FROM `".$nomtableStock."` where idStock=".$idStock;
			$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
			$stock = mysql_fetch_array($res4);

			//la quatité du stock est remis à jour
			$qtite=$stock['quantiteStockCourant']+$quantite;
			var_dump($qtite);
			$sql5="update `".$nomtableStock."` set quantiteStockCourant=".$qtite." where idStock=".$idStock;
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());
			echo $stock['quantiteStockCourant'];
		 }
	else{//si unité n'est pas Article, mais paquet ou caisse

			//on fait la suppression de cette ligne dans la table ligne
			$sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$numligne;
			$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

			///on cherche la quantité de stock en cour dans son stock d'origine
			$sql4="SELECT nbreArticleUniteStock,quantiteStockCourant FROM `".$nomtableStock."` where idStock=".$idStock;
			$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
			$stock = mysql_fetch_array($res4);

			//on cherche le nombre d'articles dans l'uniteStock de de stock en cour dans son stock d'origine
			$sql6="SELECT * FROM `".$nomtableDesignation."` where designation='".$designation."'";
			$res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
			$design = mysql_fetch_array($res6);

			//la quatité du stock est remis à jour
			$qtite=$stock['quantiteStockCourant']+($quantite*$stock['nbreArticleUniteStock']);
			$sql5="update `".$nomtableStock."` set quantiteStockCourant=".$qtite." where idStock=".$idStock;
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

		   }

	/***************************************UPDATE PAGNET DU REMISE,Apayer**********************************************/


	$newPrix=$totalp-($quantite*$prixunitevente);

	$sql19="SELECT remise,versement,apayerPagnet FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
	$res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
	$pagnet = mysql_fetch_array($res19) ;



	if($pagnet['remise'] >= $newPrix){
		$remise=0;
		$sql16="update`".$nomtablePagnet."` set totalp=".$newPrix.",remise=".$remise.",apayerPagnet=".$newPrix." where idPagnet='".$idPagnet."'";
		$res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());
		var_dump($sql16);
	}else{
		$apayerPagnet=$newPrix-$pagnet['remise'];
		$sql16="update`".$nomtablePagnet."` set totalp=".$newPrix.",apayerPagnet=".$apayerPagnet." where idPagnet=".$idPagnet."";
		$res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());
	}
	//$restourne=$pagnet['versement']-$apayerPagnet;

	/*$sql16="update `".$nomtablePagnet."` set totalp=".$newPrix.",apayerPagnet=".$apayerPagnet.",
										restourne=".$restourne." where idPagnet='".$idPagnet."'";*/

	// $apayerPagnet=$pagnet['apayerPagnet'];
	// $sql16="update`".$nomtablePagnet."` set totalp=".$newPrix.",apayerPagnet=".$apayerPagnet." where idPagnet=".$idPagnet;
	// $res16=mysql_query($sql16) or die ("update Pagnet impossible kk =>".mysql_error());



	/************************************** UPDATE BON Et DU REMISE******************************************/
	$sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
	$res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
	$Total = mysql_fetch_array($res18) ;
	$sql20="UPDATE `".$nomtableBon."` set montant=".$Total[0].", date='".$dateString2."' where idClient=".$idClient;
	$res17=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
  }

if (isset($_POST['btnAnnulerPagnet']) || isset($_POST['btnRetournerPagnet'])) {

	$idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

	//selection de toutes les lignes du pagnet à annuler ou à retourner
	$sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet;
	$res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());

	while ($ligne = mysql_fetch_array($res8)) { //pour chaque ligne

		if ($ligne['unitevente']=="Article" || $ligne['unitevente']=="article") {  //si l'unité est Article

			//on fait la suppression de cette ligne dans la table ligne
			$sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne'];
			$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

			//on cherche la quantité de stock en cour dans son stock d'origine
			$sql4="SELECT quantiteStockCourant FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
			$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
			$stock = mysql_fetch_array($res4);

			//la quatité du stock est remis à jour
			$qtite=$stock['quantiteStockCourant']+$ligne['quantite'];
			$sql5="update `".$nomtableStock."` set quantiteStockCourant=".$qtite." where idStock=".$ligne['idStock'];
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

		 }else{//si unité n'est pas Article, mais paquet ou caisse

			//on fait la suppression de cette ligne dans la table ligne
			$sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne'];
			$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

			///on cherche la quantité de stock en cour dans son stock d'origine
			$sql4="SELECT nbreArticleUniteStock,quantiteStockCourant FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
			$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
			$stock = mysql_fetch_array($res4);


			//la quatité du stock est remis à jour
			$qtite=$stock['quantiteStockCourant']+$ligne['quantite']*$stock['nbreArticleUniteStock'];
			$sql5="update `".$nomtableStock."` set quantiteStockCourant='".$qtite."' where idStock=".$ligne['idStock'];
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());
		   }
	}
	// suppression du pagnet aprés su^ppression de ses lignes
	$sql9="DELETE FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
	$res9=@mysql_query($sql9) or die ("mise à jour client  impossible".mysql_error());

	/************************************** UPDATE BON ******************************************/
	$sql10="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient;
	$res10 = mysql_query($sql10) or die ("persoonel requête 2".mysql_error());

	$Total = mysql_fetch_array($res10) ;


	$sql20="UPDATE `".$nomtableBon."` set montant='".$Total[0]."', date='".$dateString2."' where idClient=".$idClient;
	$res20=mysql_query($sql20) or die ("update Bon impossible =>".mysql_error());
}

$Total=0;


require('entetehtml.php');
?>

<body>
		<?php
		  require('header.php');
		?>

		<div class="container">
			<div class="jumbotron noImpr">
				<div class="col-sm-2 pull-right" >
					<form name="formulaireInfo" id="formulaireInfo" method="post" action="ajax/designationInfo.php">
							<div class="form-group" >
								<input type="text" class="form-control" placeholder="la Référence ici..." id="designationInfo" name="designation" />
								<div id="reponseS"></div>
								<div id="resultatS"></div>

							</div>
					</form>
				</div>
			  <h2>Les paniers du client : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h2>
			  <?php $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
					$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
									$Total = mysql_fetch_array($res12) ; ?>
			  <p>Total des paniers : <?php echo $Total[0]; ?></p>
			</div>



   			<div class="modal fade" id="addBon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title" id="myModalLabel">Ajout un Panier</h4>
	                    </div>
	                    <div class="modal-body">
	                    	<form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
							            	  <h3>Voulez vous vraiment ajouter un nouveau panier</h3>
											  <div class="modal-footer">
						                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						                            <button type="submit" name="btnEnregistrerPagnet" class="btn btn-primary">Ajouter</button>
						                       </div>
											</form>
				                 <!--   <form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
					            	  <div class="form-group">
									      <label for="inputEmail3" class="control-label">code barre</label>
									      <input type="text" class="inputbasic" id="codeBarre" name="codeBarre" value = "" size="35" />
									      <span class="text-danger" ></span>
									  </div>
									  <div class="modal-footer">
				                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                            <button type="submit" name="btnEnregistrerPagnet" class="btn btn-primary">Enregistrer</button>
				                       </div>
									</form>-->
	                    </div>

	                </div>
	            </div>
        </div>
        	<!--<div class="modal fade" id="addBon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title" id="myModalLabel">Ajout un Pagnet</h4>
	                    </div>
	                    <div class="modal-body">
		                    <form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
			            	  <div class="form-group">
							      <label for="inputEmail3" class="control-label">Date</label>
							      <input type="date" class="inputbasic" id="datePagnet" name="datePagnet" value = "" size="35" />
							      <span class="text-danger" ></span>
							  </div>

							  <div class="form-group">
							      <label for="inputEmail3" class="control-label">Type</label>
							      <input type="text" class="form-control" id="inputprenom" name="type"  value="bon">
							      <span class="text-danger" ></span>
							  </div>
							  <div class="modal-footer">
		                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		                            <button type="submit" name="btnEnregistrerPagnet" class="btn btn-primary">Enregistrer</button>
		                       </div>
							</form>
	                    </div>

	                </div>
	            </div>
        	</div> -->

	        <div class="row">

	        		<form name="formulaireAjouterPagn" role="formulaireAjouterPagnet" id="formulaireAjouterPagnet" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
	        			<button type="submit" class="btn btn-success noImpr" name="btnSavePagnet" >
									<input type="hidden" id="idPagnetClientActiver" value="<?php echo $client['activer']; ?>">
	                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
	   						</button>
								<b><span id="helpActiver" class="help-block label label-danger"></span></b>
								<br><br>
	   				<!--
	   					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBon" name="btnEnregistrerPagnet">
	                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Pagnet
	   				</button><br><br>
	   				-->
	        		</form>

	        <div id="monaccordeon" class="panel-group ">
							<?php
								$sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=".$idClient." ORDER BY idPagnet DESC";
								$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());

									while ($pagnet = mysql_fetch_array($res2)) {  ?>
										<div class="panel panel-info " <?php echo  "id=panel".$pagnet['idPagnet']."" ; ?>>
											<div class="panel-heading">

												<a class="accordion-toggle " <?php echo  "href=#item".$pagnet['idPagnet']."" ; ?> onclick='display("<?php echo  $pagnet['idPagnet'] ; ?>");'

													dataparent="#monaccordeon"  data-toggle="collapse"
											  	>
												<span class="noImpr"> Bon <?php echo $pagnet['idPagnet']; ?></span>
												</a>
													 <span class="spanTotal noImpr">Total panier: <?php echo $pagnet['apayerPagnet']; ?> </span>


											</div>
											<div  <?php echo  "id=item".$pagnet['idPagnet']."" ; ?>
											<?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php }  else { ?> class="panel-collapse collapse " <?php } ?> >
												<div class="panel-body" >
													<?php
														if ($pagnet['verrouiller']==0) {  ?>
															<form  class="form-inline pull-left" id="bonForm" method="post"  >
														  <input type="text" style="width:150px" class="input-sm
															form-control" placeholder="ajouter nouvelle entrée" name="codeBarre" autofocus="" value="" id="codeBarrePagnet@@@">
															<input type="hidden" name="idPagnet" id="idPagnet"
															 <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
															<!-- <button type="submit" name="btnEnregistrerCodeBarreAjax"
															  id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs" ><span class="glyphicon glyphicon-plus" ></span>
															Ajouter</button> -->
															<button type="submit" name="btnEnregistrerCodeBarre"
															  id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs noImpr" ><span class="glyphicon glyphicon-plus" ></span>
															Ajouter</button>
															</form>

														 <?php
													 }
													 ?>
													 <?php if ($pagnet['verrouiller']==0): ?>
														 <form class="form-inline noImpr"  id="factForm" method="post"  >
																						 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>
																								<button type="submit" name="btnAnnulerPagnet"	 class="btn btn-danger pull-right" 	 >
																									 <span class="glyphicon glyphicon-remove"></span>Annuler
																								</button>
															</form>
														 <form class="form-inline pull-right noImpr" style="margin-right:20px;" id="factForm" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?> >
																	 <input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>
																	<input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> <?php echo  "value=".$pagnet['totalp']."" ; ?> >


																	<button type="submit" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?>
																		 class="btn btn-success pull-right" data-toggle="modal"
																		onclick='remiseBClient(<?php echo  $pagnet['idPagnet'] ; ?>,<?php echo  $idClient; ?>);' >
																		 <span class="glyphicon glyphicon-lock"></span>Terminer
																	</button>
															</form>

													 <?php else: ?>
														 <form class="form-inline noImpr" id="factForm" method="post">
															 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>


																	<button type="submit" name="btnRetournerPagnet"	 class="btn btn-danger pull-right" 	 >
																		 <span class="glyphicon glyphicon-remove"></span>Retourner
																	</button>
															</form>
																 <!-- <form class="form-inline pull-right noImpr" style="margin-right:20px;" id="factForm" method="post">
																	 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
																	 <input type="hidden" name="totalp" <?php echo  "value=".$pagnet['totalp']."" ; ?> <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> >
																								 <input type="hidden" name="etatFacture" <?php echo  "id=etatFacture".$pagnet['idPagnet']."" ; ?>
																								 <?php if ($pagnet['verrouiller']==0)  { ?>		 value="0" <?php }	else { ?>	value="1" 	<?php } ?> >
																							 	<button type="submit" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?>
																											 class="btn btn-success pull-right" data-toggle="modal"
																										 onclick="factureB(<?php echo  $pagnet['idPagnet']; ?>);" >
																											 <span class="glyphicon glyphicon-lock"></span>Facture
																										</button>
																	</form> -->
																	<!-- <form class="form-inline pull-right noImpr" style="margin-right:20px;"
																	method="post" action="barcodeFacture.php" >
 																	 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
 																							 	<button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFacture">
 																											 <span class="glyphicon glyphicon-lock"></span>Facture
 																								</button>
 																	</form> -->
																		<button class="btn btn-success  pull-right" style="margin-right:20px;"
																		 onclick="document.getElementById('barcode').submit();">Facture</button><br>
																	<form class="form-inline pull-right noImpr" id="barcode"  target="_blank" style="margin-right:20px;"
																	method="post" action="barcodeFacture.php" >
 																	 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

 																	</form>


													 <?php endif; ?>

													<div class="divFacture" style="display:none;">
														<?php if ($pagnet['verrouiller']==1): ?>
															<?php echo  '********************************************* <br/>'; ?>
															<?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
															<?php echo  '*********************************************'; ?>
														<?php endif; ?>
													</div>
													<table class="table " <?php echo  "id=tabBonClient".$pagnet['idPagnet']."" ; ?> >
														<thead class="noImpr">
															<tr>
																<th></th>
																<th>Designation</th>
																<th>Quantité</th>
																<th>Unité vente</th>
                                                                <th>Prix </th>
															</tr>
														</thead>
													<!--	<tfoot>
															<tr>
																<th></td>
																<th>Designation</th>
																<th>Unité vente</th>
																<th>Prix unite vente</th>
																<th>Quantité</th>
															</tr>
														</tfoot> -->
														<tbody>
																<?php $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne DESC";
                        									    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                          										while ($ligne = mysql_fetch_array($res8)) { ?>
										                              <tr>
										                                <td ><input class="numligne" type="hidden" name="numligne"
																											<?php echo  "id=numligne".$ligne['numligne'].""; ?>
																											 <?php echo  "value=".$ligne['numligne']."" ; ?> ></td>
										                                <td class="designation"><?php echo $ligne['designation']; ?></td>

										                                <td>
																				<?php if ($pagnet['verrouiller']==0): ?>
													                                <input class="quantite noImpr" size="3" type="text" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
																												onclick="masLigne(<?php echo $ligne['numligne']  ; ?>, <?php echo $pagnet['idPagnet'] ?> );" >
												                                <?php else: ?>
		 																				<?php echo  $ligne['quantite']; ?> <span class="factureFois" ></span>
												                                 <?php endif; ?>
																		</td>
																		 <td class="unitevente " <?php echo  "id=unitevente".$ligne['numligne'].""; ?>  ><?php echo $ligne['unitevente']; ?></td>
																		<td class="prixunitevente" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?> ><?php echo $ligne['prixunitevente']; ?></td>

																		<td ><input size="3" type="hidden"
																												<?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
																												 <?php echo  "value=".$ligne['quantite']."" ; ?> >
																		</td>
										                                <td>
										                                	<form class="form-inline pull-right noImpr" id="factForm" 	method="post"  <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?> >
															                            <input type="hidden" name="idPagnet" <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
																													<input type="hidden" name="idClientF" id="idClientF" <?php echo  "value=".$idClient."" ; ?> >
															                            <input type="hidden" name="numligne"  <?php echo  "value=".$ligne['numligne'].""; ?> >
															                            <input type="hidden" name="designation"  <?php echo  "value=".$ligne['designation'].""; ?> >
															                            <input type="hidden" name="unitevente"  <?php echo  "value=".$ligne['unitevente'].""; ?> >
															                            <input type="hidden" name="idStock" <?php echo  "value=".$ligne['idStock'].""; ?> >
															                            <input type="hidden" name="quantite" <?php echo  "value=".$ligne['quantite'].""; ?> >
															                            <input type="hidden" name="prixunitevente" <?php echo  "value=".$ligne['prixunitevente'].""; ?> >
															                            <input type="hidden" name="prixtotal"  <?php echo  "value=".$ligne['prixtotal'].""; ?> >
															                            <input type="hidden" name="totalp" <?php echo  "value=".$pagnet['totalp'].""; ?> >

													                               <button type="submit"  name="btnRetour"  class="btn btn-warning pull-right noImpr" data-toggle="modal" >
													                                  Retour
													                               </button>
													                          </form>
										                                </td>
										                              </tr>

                            								  <?php   } ?>



														</tbody>
													</table>
													<div >
														<div >
															<?php if ($pagnet['verrouiller']==1): ?>
																<?php echo  '********************************************* <br/>'; ?>
																<?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
															<?php endif; ?>
														</div>
														<div>
															<?php if ($pagnet['verrouiller']==0): ?>
																<input class="noImpr" size="7" type="text" placeholder="remise" name="remise" <?php echo  "id=remise".$pagnet['idPagnet'].""; ?>>
															<?php elseif($pagnet['remise']!=0): ?>
																<?php  echo '<b>Remise :'. $pagnet['remise'].'</b><br/>';?>
															<?php endif; ?>
														</div>
														<div>
															<?php if ($pagnet['verrouiller']==1): ?>
															<?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
															<?php endif; ?>
														</div>
														<div class="divFacture" style="display:none;">
															<?php echo  '********************************************* <br/>'; ?>
															N° <?php echo $pagnet['idPagnet']; ?><?php echo "-".$idClient ; ?>
															<span class="spanDate"> <?php echo $pagnet['datepagej']; ?> </span>
															<?php echo  '<br/>********************************************* '; ?>
                              <div  align="center"> A BIENTOT !</div>
														</div>
													</div>
												</div>
											</div>
										</div>
																<script type="text/javascript">
																	/*	$(document).ready(function() {
																			 var idtabBonClient="#tabBonClient"+<?php echo  $pagnet['idPagnet'] ; ?>;

																				$(idtabBonClient).DataTable();
																			});*/
																</script>
							<?php 	}?>

					</div>


		</div>

</body>
</html>
