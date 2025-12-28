<?php
session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
/*
Résumé : Ce code permet d'inserer une ligne (une entrée ou une sortie) dans le journal d'une boutique.
Commentaire : Ce code contient un formulaire récupérant l'ensemble des informations (typeligne, designation,prix unitaire,quantite,remise,prix total) sur une de journal de la boutique.
Pour facilité le remplissage de ce formulaire ce code est associé avec du code AJAX (JavaScript:verificationdesignation.js et PHP:verificationdesig.php),
qui vérifie le champ désignation si il est vide et si il existe ou il est absent de la base de données et qui compléte les champs : prix unitaire et prix total.
Il insère ces informations dans la table commençant par le nom de la boutique et suivi de : -lignepj. Pour cela ce code à partir de la date courrante regarde si pour cette ligne y'a une page déja créer sinon il le crée et regarde aussi si pour cette page de la date courrante si le mois et l'année ya un journal déjà créer sinon il le crée.
Ainsi de façon automatique le code pour une ligne donnée le relie avec une page et un journal si ils existent. sinon le les créent avant de les associer avec cette nouvelle ligne.
Ce code permet d'afficher la liste des lignes (des entrées ou des sorties) du journal d'une boutique pour la date courrante et de modifier et de supprimer une ligne de la liste des lignes du journal.
Version : 2.0
see also : modifierLigne.php et supprimerLigne.php
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/
require('connection.php');

require('declarationVariables.php');


/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente		  =@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/

if (isset($_POST['btnSavePagnetVente'])) {

    $date = new DateTime();
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $dateString2=$jour.'-'.$mois.'-'.$annee;

	$sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;
    $res1=mysql_query($sql1);

    if(mysql_num_rows($res1)){
    	 $sql2="select * from `".$nomtablePage."` where datepage='".$dateString2."'";
         $res2=mysql_query($sql2);

	   if(mysql_num_rows($res2)){
	          $sql6="insert into `".$nomtablePagnet."` (datepagej) values('".$dateString2."')";
              $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());
    	 }
    	 else{
    	     $sql1="insert into `".$nomtablePage."` (datepage) values('".$dateString2."')";
             $res1=@mysql_query($sql1) or die ("insertion page journal impossible-1".mysql_error());

			 $sql6="insert into `".$nomtablePagnet."` (datepagej) values('".$dateString2."')";
             $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );
    	 }
	}
	else{
	   $sql1="insert into `".$nomtableJournal."` (mois,annee) values(".$mois.",".$annee.")";
	   $res1=@mysql_query($sql1) or die ("insertion journal impossible-1".mysql_error());

	   $sql2="insert into `".$nomtablePage."` (datepage) values('".$dateString2."')";
	   $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2".mysql_error());

	   $sql6="insert into `".$nomtablePagnet."` (datepagej) values('".$dateString2."')";
	   $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );
	}
}
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


		 $totalp=0;
		 $tailleTableau=count($codeBrute);


		 $sql4="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock;
		 $res4=mysql_query($sql4) or die ("select stock impossible =>".mysql_error());
		 //echo $sql4;
		 $stock = mysql_fetch_array($res4) or die ("select stock impossible =>".mysql_error());
		 $quantiteStockCourant=$stock['quantiteStockCourant'];
		 $uniteStock=$stock['uniteStock'];


		if ($quantiteStockCourant>0) {
			 // insertion dans l'historique
		$sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
		$res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
		$design = mysql_fetch_array($res6);
		if ($tailleTableau==3) {
				$numero=$codeBrute[2];
					if ( $numero==2) { // PAquet, douzaine, ....
                        $quantiteCourant=$quantiteStockCourant-$design['nbreArticleUniteStock'];
						if ($quantiteCourant>=0){
							  //Insertion ligne
							$sql7="insert into `".$nomtableLigne."` (designation, idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet)values('".$design['designation']."',".$idStock.",'".$stock['uniteStock']."',".$stock['prixuniteStock'].",1,".$stock['prixuniteStock'].",".$idPagnet.")";
                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                            //UPdate stock
							$sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$idStock;
							$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());


							$sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
							$res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
							$pagnet = mysql_fetch_array($res14);
							$totalp=$pagnet['totalp']+$stock['prixuniteStock'];

							}

			 		 }
                else if ($numero==1 ) {  // Article
					//Insertation ligne
                    $sql7="insert into `".$nomtableLigne."`(designation, idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet)values('".$design['designation']."',".$idStock.",'article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.")";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    $quantiteCourant=$quantiteStockCourant-1;
                    //Upadate Stock
					$sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$idStock;
					$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    $sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
					$res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
					$pagnet = mysql_fetch_array($res14);
					$totalp=$pagnet['totalp']+$stock['prixunitaire'];
					}
			}
		elseif ($tailleTableau==4) {
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet)values('".$design['designation']."',".$idStock.",'Article','".$stock['prixunitaire']."','1','".$stock['prixunitaire']."','".$idPagnet."')";

					$res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                    //Update stock
					$quantiteCourant=$quantiteStockCourant-1;
					$sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$idStock;
					$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());
					$sql14="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
					$res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
					$pagnet = mysql_fetch_array($res14);
					$totalp=$pagnet['totalp']+$stock['prixunitaire'];
		}
		//$totalp=$Total[0];
		$sql15="UPDATE `".$nomtablePagnet."` set totalp=".$totalp.",apayerPagnet=".$totalp." where idPagnet=".$idPagnet;
		$res15=mysql_query($sql15) or die ("update Pagnet impossible =>".mysql_error());
		$resultat="ok";
		}
		if ($tailleTableau==2) {
			$idDesignation=$codeBrute[0];
			$numero=$codeBrute[1];
			$sql17="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
		    $res17=mysql_query($sql17) or die ("select stock impossible =>".mysql_error());
			$design = mysql_fetch_array($res17) ;
			//var_dump($sql17);
				//echo "taille 2 pour les frais =".$prix['prix'];

				$sql16="insert into `".$nomtableLigne."` (designation,prixunitevente,quantite,prixtotal,idPagnet)
				values('".$design['designation']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.")";
				$res16=mysql_query($sql16) or die ("insertion pagnier impossible 16  =>".mysql_error() );

				$sql18="SELECT totalp FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet;
				$res18=mysql_query($sql18) or die ("select stock impossible =>".mysql_error());
				$pagnet = mysql_fetch_array($res18);
				$totalp=$pagnet['totalp']+$design['prix'];

			//$totalp=$Total[0];
			$sql15="UPDATE `".$nomtablePagnet."` set totalp=".$totalp.",apayerPagnet=".$totalp." where idPagnet=".$idPagnet;
			$res15=mysql_query($sql15) or die ("update Pagnet impossible =>".mysql_error());
		}


	}



}
if (isset($_POST['btnImprimerFacture'])) {
//exit('nice insert');

			if (isset($_POST['rms']) && isset($_POST['aPP'])) {
				// code...
				$remise=@$_POST['rms'];
				$apayerPagnet=@$_POST['aPP'];
				$totalp=@$_POST['ttp'];

				$newTotal=$totalp-$remise;
				$monaie=$apayerPagnet-$newTotal;

				$sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1, totalp=".$totalp.",remise=".$remise.",apayerPagnet=".$apayerPagnet." where idPagnet=".@$_POST['idPagnet'];
				$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
				die($sql3);

			}else {
				// code...
				//$sql3="UPDATE `".$nomtablePagnet."` set verrouiller=1 where idPagnet=".$_POST['idPagnet'];
		    //$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

  }}

if (isset($_POST['btnImprimerFacture'])) {
	if(isset($_POST['idPagnet'])){
		$sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".@$_POST['idPagnet'];
	  $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
	}

  }


if (isset($_POST['btnAnnulerPagnet']) || isset($_POST['btnRetournerPagnet'])) {

	$idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

	//selection de toutes les lignes du pagnet à annuler ou à retourner
	$sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet;
	$res8 = mysql_query($sql8) or die ("personel requête 2".mysql_error());

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
			$sql4="SELECT  nbreArticleUniteStock,quantiteStockCourant FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
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
			$sql5="update `".$nomtableStock."` set quantiteStockCourant=".$qtite." where idStock=".$idStock;
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());
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
			$qtite=$stock['quantiteStockCourant']+$quantite*$stock['nbreArticleUniteStock'];
			$sql5="update `".$nomtableStock."` set quantiteStockCourant=".$qtite." where idStock=".$idStock;
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());
		   }

	/***************************************UPDATE PAGNET DU REMISE,Apayer**********************************************/


	$newPrix=$totalp-($quantite*$prixunitevente);

	$sql19="SELECT remise,versement FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
	$res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
	$pagnet = mysql_fetch_array($res19) ;

	$apayerPagnet=$newPrix-$pagnet['remise'];
	$restourne=$pagnet['versement']-$apayerPagnet;

	$sql16="update `".$nomtablePagnet."` set totalp=".$newPrix.",apayerPagnet=".$apayerPagnet.",
										restourne=".$restourne." where idPagnet='".$idPagnet."'";
	$res16=mysql_query($sql16) or die ("update Pagnet impossible =>".mysql_error());

  }

require('entetehtml.php');

?>

<body onLoad=""><header>

<?php require('header.php');

echo'<div class="container" >';

$sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=0 && datepagej ='".$dateString2."' ORDER BY idPagnet DESC";
$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
$total=0;
while ($pagnet0 = mysql_fetch_array($res2))
	$total+=$pagnet0['apayerPagnet'];
				?>

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
			<h2>Journal de caisse du : <?php echo $dateString2; ?></h2>

			<p>Total des ventes : <?php echo $total; ?> FCFA</p>

		</div>
  <form name="formulairePagnet" method="post" >
      <button type="submit" class="btn btn-success noImpr" name="btnSavePagnetVente">
                      <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
      </button><br><br>
  </form>
<div id="monaccordeon" class="panel-group ">
              <?php

			  $date = new DateTime();
				$annee =$date->format('Y');
				$mois =$date->format('m');
				$jour =$date->format('d');
				$dateString2=$jour.'-'.$mois.'-'.$annee;

            	//$sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=0 ORDER BY idPagnet DESC";
			    $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=0 && datepagej ='".$dateString2."' ORDER BY idPagnet DESC";
                $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
                  while ($pagnet = mysql_fetch_array($res2)) {   ?>


                    <div class="panel panel-info" <?php echo  "id=panel".$pagnet['idPagnet']."" ; ?>>
                      <div class="panel-heading">

                        <a class="accordion-toggle " <?php echo  "href=#item".$pagnet['idPagnet']."" ; ?>  onclick='display("<?php echo  $pagnet['idPagnet'] ; ?>");'
                          dataparent="#monaccordeon"  data-toggle="collapse"
                          > Pagnet <?php echo $pagnet['idPagnet']; ?>

                        </a>  <span class="spanDate noImpr"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                           <span class="spanTotal noImpr">Total panier: <?php echo $pagnet['apayerPagnet']; ?> </span>


                      </div>
                      <div   <?php echo  "id=item".$pagnet['idPagnet']."" ; ?>
                      <?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php }
                           else { ?> class="panel-collapse collapse " <?php } ?>  >
                        <div class="panel-body" >
                          <?php
                            if ($pagnet['verrouiller']==0) {  ?>
                              <form  class="form-inline pull-left noImpr" id="ajouterProdForm" method="post" >
                                <input type="text" class="inputbasic" name="codeBarre" id="codeBarreLigne" autofocus="" >
                                <input type="hidden" name="idPagnet" id="idPagnet"
                                 <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                               <button type="submit" name="btnEnregistrerCodeBarre"
                                id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs " ><span class="glyphicon glyphicon-plus" ></span>
                              Ajouter</button><div id="reponseS"></div>
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
														<form class="form-inline pull-right noImpr" style="margin-right:20px;" id="factForm" method="post"  >
																						<input type="hidden" name="idPagnet"   <?php echo  "value=".$pagnet['idPagnet']."" ; ?>>
																						<input type="hidden" name="totalp" <?php echo  "id=totalp".$pagnet['idPagnet']."" ; ?> <?php echo  "value=".$pagnet['totalp']."" ; ?> >

																						<button type="submit" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?>
																							 class="btn btn-success pull-right" data-toggle="modal"
																							onclick='remiseB("<?php echo  $pagnet['idPagnet'] ; ?>");' >
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

																									 <button type="submit" name="btnImprimerFacture" <?php echo  "id=btnImprimerFacture".$pagnet['idPagnet']."" ; ?>
																											class="btn btn-success pull-right" data-toggle="modal"
																										  onclick="factureB(<?php echo  $pagnet['idPagnet']; ?>);"  >
																											<span class="glyphicon glyphicon-lock"></span>Facture
																									 </button>
														</form> -->
														<!-- <form class="form-inline pull-right noImpr" style="margin-right:20px;"
														method="post" action="barcodeFacture.php" >
														 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
																					<button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFacture">
																								 <span class="glyphicon glyphicon-lock"></span>Facture 2
																					</button>
														</form> -->
														<button class="btn btn-success  pull-right" style="margin-right:20px;"
															onclick="document.getElementById('barcode').submit();">Facture</button><br>
													<form class="form-inline pull-right noImpr" id="barcode"  target="_blank" style="margin-right:20px;"
													method="post" action="barcodeFacture.php" >
													 <input type="hidden" name="idPagnet" id="idPagnet"  <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >

													</form>

													<?php endif; ?>
							 <div  class="divFacture" style="display:none;">
								 <?php if ($pagnet['verrouiller']==1): ?>
									 <?php echo  '********************************************* <br/>'; ?>
									 <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
									 <?php echo  '*********************************************'; ?>
								 <?php endif; ?>
							 </div>

                          <table class="table ">
                            <thead class="noImpr">
                              <tr>
																<th></td>
                                <th>Designation</th>
                                <th>Quantite</th>
                                <th>Unite vente</th>
                                <th>Prix unite vente</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php /**/ $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";
                                              $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                              while ($ligne = mysql_fetch_array($res8)) {?>
                                                  <tr>
													<td ><input class="numligne" type="hidden" name="numligne" <?php echo  "id=numligne".$pagnet['idPagnet'].""; ?>
																											 <?php echo  "value=".$ligne['numligne']."" ; ?> ></td>
										            <td class="designation"><?php echo $ligne['designation']; ?></td>
										            <td> <?php if ($pagnet['verrouiller']==0): ?>
													        <input class="quantite" size="3" type="text" <?php echo  "id=quantite".$ligne['numligne'].""; ?>  <?php echo  "value=".$ligne['quantite'].""; ?>
																														 		onclick="masLigne(<?php echo $ligne['numligne']  ; ?>, <?php echo $pagnet['idPagnet'] ?> );" >
												        <?php else: ?>
															<?php echo  $ligne['quantite']; ?>  <span class="factureFois"></span>
												        <?php endif; ?>
													</td>
										            <td class="unitevente "><?php echo $ligne['unitevente']; ?> </td>

													<td class="prixunitevente" <?php echo  "id=prixunitevente".$ligne['numligne'].""; ?> ><?php echo $ligne['prixunitevente']; ?></td>
													<td><input size="3" type="hidden" <?php echo  "id=quantiteOld".$ligne['numligne'].""; ?>
																					  <?php echo  "value=".$ligne['quantite']."" ; ?> >
													</td>
                                                    <td>
																									<form class="form-inline pull-right" id="factForm" 	method="post"  <?php echo  "action=insertionLigne.php" ; ?> >
																													 <input type="hidden" name="idPagnet"
																													 <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >


																													<input type="hidden" name="designation"
																														 <?php echo  "value=".$ligne['designation'].""; ?> >

																													<input type="hidden" name="numligne"
																														 <?php echo  "value=".$ligne['numligne'].""; ?> >

																													<input type="hidden" name="idStock"
																														 <?php echo  "value=".$ligne['idStock'].""; ?> >

																													<input type="hidden" name="unitevente"
																														 <?php echo  "value=".$ligne['unitevente'].""; ?> >

                                                                                                                  <input type="hidden" name="quantite"
																														 <?php echo  "value=".$ligne['quantite'].""; ?> >

																													<input type="hidden" name="prixunitevente"
																														 <?php echo  "value=".$ligne['prixunitevente'].""; ?> >

																													<input type="hidden" name="prixtotal"
																														 <?php echo  "value=".$ligne['prixtotal'].""; ?> >

																													<input type="hidden" name="totalp"
																														 <?php echo  "value=".$pagnet['totalp'].""; ?> >

																												 <button type="submit"  name="btnRetour"  class="btn btn-warning pull-right noImpr" data-toggle="modal" >
																														Retour
																												 </button>
																											 </form>
																										</td>
                                                  </tr>
                                              <?php  }  ?>

                            </tbody>
                          </table>
						    <div>
									<div>
										<?php if ($pagnet['verrouiller']==1): ?>
										<?php echo  '********************************************* <br/>'; ?>
										<?php echo  'TOTAL : '.$pagnet['totalp'].'<br/>'; ?>
										<?php endif; ?>
									</div>
									<div>
										<?php if ($pagnet['verrouiller']==0): ?>
											<input class="noImpr" size="7" type="text" placeholder="remise" name="remise" <?php echo  "id=remise".$pagnet['idPagnet'].""; ?>
											disabled >
											<?php elseif($pagnet['remise']!=0): ?>
											<?php  echo 'Remise :'. $pagnet['remise'].'<br/>';?>
											<?php endif; ?>
									</div>
									<div>
										<?php if ($pagnet['verrouiller']==1): ?>
										<?php echo  '<b>Net à payer : '.$pagnet['apayerPagnet'].'</b><br/>'; ?>
										<?php endif; ?>
									</div>
									<div>
										<?php if ($pagnet['verrouiller']==0): ?>
											<!--<input size="7" type="text" placeholder="Espéce " name="apayerPagnet" <?php echo  "id=apayerPagnet".$pagnet['idPagnet'].""; ?>> -->
											<input class="noImpr" size="7" type="text" placeholder="Espéce"
														 name="versement" <?php echo  "id=versement".$pagnet['idPagnet'].""; ?> disabled>
											<?php else: ?>
											<?php echo  '<h5>Espèces : '.$pagnet['versement'].'</h5>'; ?>
										<?php endif; ?>
									</div>
									<div>
										<?php if ($pagnet['verrouiller']==1): ?>
											<?php echo  '<h5><b>Rendu : '.$pagnet['restourne'].'</b></h5>'; ?>
											<?php endif; ?>
									</div>
									<div class="divFacture" style="display:none;">
										<?php echo  '********************************************* <br/>'; ?>
										Bon <?php echo $pagnet['idPagnet']; ?>
										<span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
										<?php echo  '<br/>********************************************* '; ?>
										A BIENTOT !
									</div>
							</div>
                        </div>
                      </div>
                    </div>
              <?php   } ?>
</div>
<?php /*****************************/
echo'</table><br /></div>';
echo''.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';
?>
