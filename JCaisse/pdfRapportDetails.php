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

require('fpdf/fpdf.php');
if (isset($_POST['dateImp'])) {

	$dateString2=htmlspecialchars(trim($_POST['dateImp']));
	$debutAnnee=htmlspecialchars(trim($_POST['debutAnnee']));
  $finAnnee=htmlspecialchars(trim($_POST['finAnnee']));
	$debutAnneeConvert = strtotime($debutAnnee);
	$finAnneeConvert = strtotime($finAnnee);
	// var_dump($debutAnneeConvert);
setlocale(LC_ALL, 'fr_FR', 'fra_FRA');
// echo 'Nous sommes le '.strftime('%A %d %B %Y', time());
	$dateD=strftime('%d %B %Y', $debutAnneeConvert);
	$dateF=strftime('%d %B %Y', $finAnneeConvert);


// /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
//     $sqlP1="SELECT DISTINCT p.idPagnet
//     FROM `".$nomtablePagnet."` p
//     INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
//     INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
//     WHERE (l.classe = 0 or l.classe = 1)  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0  ORDER BY p.idPagnet ASC";
//     $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
// /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/
//
// /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
//     $sqlC="SELECT COUNT(DISTINCT l.numligne)
//     FROM `".$nomtableLigne."` l
//     INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//     WHERE (l.classe = 0 or l.classe = 1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0   ORDER BY l.numligne ASC ";
//     $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
//     $nbre = mysql_fetch_array($resC) ;
// /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/
//
// /**Debut requete pour Calculer la Somme des Ventes d'Aujourd'hui  **/
//     $sqlV="SELECT DISTINCT p.idPagnet
//     FROM `".$nomtablePagnet."` p
//     INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
//     WHERE (l.classe=0 || l.classe=1) && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0   ORDER BY p.idPagnet DESC";
//     $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
//     $T_ventes = 0 ;
//     $S_ventes = 0;
//     $T_remises = 0 ;
//     $S_remises = 0;
//     while ($pagnet = mysql_fetch_array($resV)) {
//         $sqlS="SELECT SUM(apayerPagnet)
//         FROM `".$nomtablePagnet."`
//         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
//         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
//         $S_ventes = mysql_fetch_array($resS);
//         $T_ventes = $S_ventes[0] + $T_ventes;
//
//         $sqlS="SELECT SUM(remise)
//         FROM `".$nomtablePagnet."`
//         where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && idPagnet='".$pagnet['idPagnet']."' && type=0   ORDER BY idPagnet DESC";
//         $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
//         $S_remises = mysql_fetch_array($resS);
//         $T_remises = $S_remises[0] + $T_remises;
//     }
/**Fin requete pour Calculer la Somme des Ventes d'Aujourd'hui **/

		/**Debut Entrees */
				$som_EntreesPU=0;
				$som_EntreesPA=0;
				$som_EntreesPU2=0;
				$som_EntreesPA2=0;
				$sqlE="SELECT * FROM `".$nomtableStock."` s
				LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
				WHERE d.classe=0 AND s.dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY s.idStock DESC";
				$resE=mysql_query($sqlE);
				$produitsE=array();
				while($stockE=mysql_fetch_array($resE)){
						if(in_array($stockE['idDesignation'], $produitsE)){
								// echo "Existe.";
						}
						else{
								if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
										$sqlS="SELECT SUM(quantiteStockinitial)
										FROM `".$nomtableStock."`
										where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
										$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
										$S_stock = mysql_fetch_array($resS);

										$som_EntreesPU=$som_EntreesPU + ($stockE['prixPublic'] * $S_stock[0]);
										$som_EntreesPA=$som_EntreesPA + ($stockE['prixSession'] * $S_stock[0]);

										$som_EntreesPU2=$som_EntreesPU2 + ($stockE['prixPublic'] * $S_stock[0]);
										$som_EntreesPA2=$som_EntreesPA2 + ($stockE['prixSession'] * $S_stock[0]);
								}
								else{
										$sqlS="SELECT SUM(totalArticleStock)
										FROM `".$nomtableStock."`
										where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
										$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
										$S_stock = mysql_fetch_array($resS);

										if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
												$som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));
												$som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));

												$som_EntreesPU2=$som_EntreesPU2 + ($stockE['prix'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));
												$som_EntreesPA2=$som_EntreesPA2 + ($stockE['prixachat'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));
													 }
										else{
												$som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * $S_stock[0]);
												$som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * $S_stock[0]);

												$som_EntreesPU2=$som_EntreesPU2 + ($stockE['prix'] * $S_stock[0]);
												$som_EntreesPA2=$som_EntreesPA2 + ($stockE['prixachat'] * $S_stock[0]);
										}
								}
								$produitsE[] = $stockE['idDesignation'];
						}
				}
		/**Fin Entrees */
		/**Debut Sorties */
						$som_Sorties=0;
						$remise=0;
						$remiseP= array();

						$sqlS="SELECT *
						FROM  `".$nomtableLigne."` l
						INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
						WHERE l.classe = 0  && p.idClient !=0 || p.idClient =0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
						$resS = mysql_query($sqlS) or die ("persoonel requête 2".mysql_error());
						$resS=mysql_query($sqlS);
						$produitsS=array();

						while($stockS=mysql_fetch_array($resS)){
								if(in_array($stockS['idPagnet'], $remiseP)){
										// echo "Existe.";
								}else {
									$remise += $stockS['remise'];
									$remiseP[] = $stockS['idPagnet'];
								}

								if(in_array($stockS['idDesignation'], $produitsS)){
										// echo "Existe.";
								}
								else{
										$sqlT="SELECT SUM(prixtotal)
										FROM `".$nomtableLigne."` l
										INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
										INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
										where p.idClient !=0 || p.idClient =0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockS["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
										$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
										$prixTotal = mysql_fetch_array($resT);
										$som_Sorties=$som_Sorties + $prixTotal[0];
										$produitsS[] = $stockS['idDesignation'];
								}
						}
						// $som_Sorties = $som_Sorties - $remise;
		/**Fin Sorties */
		 /**Debut SortiesVente */
				$som_SortiesVente=0;
				$som_SortiesVente2=0;
				$remiseV=0;
				$remisePV= array();

				$sqlSV="SELECT *
				FROM  `".$nomtableLigne."` l
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				WHERE l.classe = 0  && p.idClient =0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
				$resSV = mysql_query($sqlSV) or die ("persoonel requête 2".mysql_error());
				$resSV=mysql_query($sqlSV);
				$produitsSV=array();
				while($stockS=mysql_fetch_array($resSV)){
				//exit($stockS['idPagnet'].'==='.$stockS['remise']);
						if(in_array($stockS['idPagnet'], $remisePV)){
								// echo "Existe.";
						}else {
							$remiseV = $remiseV + $stockS['remise'];
							$remisePV[] = $stockS['idPagnet'];
						}
						if(in_array($stockS['idDesignation'], $produitsSV)){
								// echo "Existe.";
						}
						else{
								$sqlT="SELECT SUM(prixtotal)
								FROM `".$nomtableLigne."` l
								INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
								INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
								where p.idClient =0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockS["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
								$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
								$prixTotal = mysql_fetch_array($resT);
								$som_SortiesVente=$som_SortiesVente + $prixTotal[0];
								$som_SortiesVente2=$som_SortiesVente2 + $prixTotal[0];
								$produitsSV[] = $stockS['idDesignation'];
								}
						}
					$som_SortiesVente = $som_SortiesVente - $remiseV;
					$som_SortiesVente2 = $som_SortiesVente2 - $remiseV;
		/**Fin SortiesVente */
		/**Debut SortiesBon */
			 $som_SortiesBon=0;
			 $som_SortiesBon2=0;
			 $remiseB = 0;
			 $remisePB = array();
			 $sqlSB="SELECT *
			 FROM  `".$nomtableLigne."` l
			 INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			 WHERE l.classe = 0  && p.idClient !=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
			 $resSB = mysql_query($sqlSB) or die ("persoonel requête 2".mysql_error());
			 $resSB=mysql_query($sqlSB);
			 $produitsSB=array();
			 while($stockSB=mysql_fetch_array($resSB)){
				if(in_array($stockSB['idPagnet'], $remisePB)){
						 // echo "Existe.";
				 }else {
				 // }
				//  if(in_array($stockSB['idDesignation'], $produitsSB)){
							// echo "Existe.";
				//  }
				//  else{
						//$sqlT="SELECT SUM(prixtotal)
						//FROM `".$nomtableLigne."` l
						//INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
						//INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
						//where p.idClient !=0 && l.classe=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockSB["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
						//$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
						//$prixTotal = mysql_fetch_array($resT);
						//$som_SortiesBon=$som_SortiesBon + $prixTotal[0];
						//$som_SortiesBon2=$som_SortiesBon2 + $prixTotal[0];
						//$produitsSB[] = $stockSB['idDesignation'];

						$som_SortiesBon=$som_SortiesBon + $stockSB['apayerPagnet'];
						$som_SortiesBon2=$som_SortiesBon2 + $stockSB['apayerPagnet'];

						// $remiseB = $remiseB + $stockSB['remise'];
						$remisePB[] = $stockSB['idPagnet'];
				}
		 }
		 $som_SortiesBon=$som_SortiesBon - $remiseB;
		 $som_SortiesBon2=$som_SortiesBon2 - $remiseB;
		/**Fin SortiesBon */
		/**Debut SortiesE */
				$som_SortiesE=0;
				$som_SortiesE2=0;
				$sqlS="SELECT *
				FROM  `".$nomtableLigne."` l
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				WHERE l.classe = 0  && p.idClient =0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
				$resS = mysql_query($sqlS) or die ("persoonel requête 2".mysql_error());
				$resS=mysql_query($sqlS);
				$produitsS=array();
				while($stockS=mysql_fetch_array($resS)){
						if(in_array($stockS['idDesignation'], $produitsS)){
								// echo "Existe.";
						}
						else{
								$sqlT="SELECT SUM(prixtotal)
								FROM `".$nomtableLigne."` l
								INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
								INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
								where p.idClient =0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockS["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
								$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
								$prixTotal = mysql_fetch_array($resT);
								$som_SortiesE=$som_SortiesE + $prixTotal[0];
								$som_SortiesE2=$som_SortiesE2 + $prixTotal[0];
								$produitsS[] = $stockS['idDesignation'];
						}
				}
		/**Fin SortiesE */
		/**Debut SortiesRetirer */
				$plusR=0;
				$moinsR=0;
				$plusR2=0;
				$moinsR2=0;
				$sql1="SELECT * from `".$nomtableInventaire."` i
				INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
				WHERE i.TYPE=1 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
				$res1=mysql_query($sql1);
				while ($inventaire = mysql_fetch_array($res1)) {
						if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
								if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
								$plusR = $plusR + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
								$plusR2 = $plusR2 + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
								}
								else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
								$moinsR = $moinsR + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
								$moinsR2 = $moinsR2 + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
								}
						}
				}
		/**Fin SortiesRetirer */
		/**Debut SortiesEx */
				$plusE=0;
				$moinsE=0;
				$plusE2=0;
				$moinsE2=0;
				$sql1="SELECT *from `".$nomtableInventaire."` i
				INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
				WHERE i.TYPE=1 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
				$res1=mysql_query($sql1);
				while ($inventaire = mysql_fetch_array($res1)) {
						if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
								if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
								$plusE = $plusE + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
								$plusE2 = $plusE2 + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
								}
								else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
								$moinsE = $moinsE + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
								$moinsE2 = $moinsE2 + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
								}
						}
				}

		/**Fin SortiesEx */
		/**Debut SortiesRetourner */
				$plusRe=0;
				$moinsRe=0;
				$plusRe2=0;
				$moinsRe2=0;
				$sql1="SELECT *from `".$nomtableInventaire."` i
				INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
				WHERE i.TYPE=3 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
				$res1=mysql_query($sql1);
				while ($inventaire = mysql_fetch_array($res1)) {
						if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
								if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
								$plusRe = $plusRe + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
								$plusRe2 = $plusRe2 + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
								}
								else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
								$moinsRe = $moinsRe + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
								$moinsRe2 = $moinsRe2 + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
								}
						}
				}
		/**Fin SortiesRetourner */
		/**Debut ClientsVersements */
				$som_Clients=0;
				$som_Clients2=0;
				$sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."`
				WHERE idClient!=0 && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
				$resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());
				$totalV = mysql_fetch_array($resV);
				$som_Clients=$som_Clients + $totalV[0];
				$som_Clients2=$som_Clients2 + $totalV[0];
			/**Fin ClientsVersements */
			/**Debut ClientsVersements restant */
				$SommeMontantAverser=0;
				$sql2="SELECT * FROM `".$nomtableClient."`";
				$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
				while ($client = mysql_fetch_array($res2)) {
					$sql12="SELECT montant FROM `".$nomtableBon."` where idClient=".$client['idClient']." ";
					$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
					$montantBon = mysql_fetch_array($res12) ;
					$SommeMontantAverser+=$montantBon[0]-$client['solde'];
				}
			/**Fin ClientsVersements restant */
			/**Debut ClientsBonsEspece */
				$som_ClientsBE=0;
				$som_ClientsBE2=0;
				$sqlBE="SELECT SUM(prixtotal)FROM  `".$nomtableLigne."` l
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				WHERE l.classe = 6  && p.idClient !=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
				$resBE=mysql_query($sqlBE) or die ("select stock impossible =>".mysql_error());
				$totalBE = mysql_fetch_array($resBE);
				$som_ClientsBE=$som_ClientsBE + $totalBE[0];
				$som_ClientsBE2=$som_ClientsBE2 + $totalBE[0];
			/**Fin ClientsBonsEspece */
			/**Debut FournisseursBons */
				$som_Fournisseurs=0;
				$som_Fournisseurs2=0;
				$sqlB="SELECT SUM(montantBl) FROM `".$nomtableBl."`
				WHERE idFournisseur!=0 && CONCAT(CONCAT(SUBSTR(dateBl,7, 10),'',SUBSTR(dateBl,3, 4)),'',SUBSTR(dateBl,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
				$resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
				$totalB = mysql_fetch_array($resB);
				$som_Fournisseurs=$som_Fournisseurs + $totalB[0];
				$som_Fournisseurs2=$som_Fournisseurs2 + $totalB[0];
		/**Fin FournisseursBons */
		/**Debut FournisseursV */
				$som_FournisseursV=0;
				$som_FournisseursV2=0;
				$sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."`
				WHERE idFournisseur!=0 && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'";
				$resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());
				$totalV = mysql_fetch_array($resV);
				$som_FournisseursV=$som_FournisseursV + $totalV[0];
				$som_FournisseursV2=$som_FournisseursV2 + $totalV[0];
		/**Fin FournisseursV */
		/**Debut restant fournisseur */
				$restant_Fournisseurs = 0;
				$restant_Fournisseurs = $som_Fournisseurs - $som_FournisseursV;//dans la periode

					$sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur!=0  ";
					$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
					$TotalBF = mysql_fetch_array($res12) ;

					$sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur!=0 ";
					$res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
					$TotalVF = mysql_fetch_array($res13) ;

					$restant_FournisseursD=$TotalBF[0] - $TotalVF[0];//depuis le debut
		/**Fin restant fournisseur */
		/**Debut Services */
				$som_Services=0;
				$som_Services2=0;
				$sqlSV="SELECT *
				FROM  `".$nomtableLigne."` l
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				WHERE l.classe = 1  && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
				$resSV = mysql_query($sqlSV) or die ("persoonel requête 2".mysql_error());
				$resSV=mysql_query($sqlSV);
				$produitsSV=array();
				while($stockSV=mysql_fetch_array($resSV)){
						if(in_array($stockSV['idDesignation'], $produitsSV)){
								// echo "Existe.";
						}
						else{
								$sqlT="SELECT SUM(prixtotal)
								FROM `".$nomtableLigne."` l
								INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
								INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
								where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockSV["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
								$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
								$prixTotal = mysql_fetch_array($resT);
								$som_Services=$som_Services + $prixTotal[0];
								$som_Services2=$som_Services2 + $prixTotal[0];
								$produitsSV[] = $stockSV['idDesignation'];
						}
				}
		/**Fin Services */
		/**Debut Depenses */
				$som_Depenses=0;
				$som_Depenses2=0;
				$sqlD="SELECT *
				FROM  `".$nomtableLigne."` l
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				WHERE l.classe = 2  && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
				$resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
				$resD=mysql_query($sqlD);
				$produitsD=array();
				while($stockD=mysql_fetch_array($resD)){
						if(in_array($stockD['idDesignation'], $produitsD)){
								// echo "Existe.";
						}
					 else{
								$sqlT="SELECT SUM(prixtotal)
								FROM `".$nomtableLigne."` l
								INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
								INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
								where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockD["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
								$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
								$prixTotal = mysql_fetch_array($resT);
								$som_Depenses=$som_Depenses + $prixTotal[0];
								$som_Depenses2=$som_Depenses2 + $prixTotal[0];
								$produitsD[] = $stockD['idDesignation'];
						}
				}
		/**Fin Depenses */

		/** Debut montant stock actuel */
				 $sql2="SELECT d.prix,s.quantiteStockCourant,s.idStock,d.prixachat FROM `".$nomtableStock."` s
				LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
				WHERE d.classe=0 ORDER BY s.idStock DESC";
				$res2=mysql_query($sql2);
				$montantPA=0;
				$montantPU=0;
				while ($stock = mysql_fetch_array($res2)) {
					if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){
						$montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixachat']);
						$montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prix']);
					}
				}
		/** Fin montant stock actuel */

		$result=number_format($som_EntreesPA, 0, ',', ' ').'<>'.number_format($som_EntreesPU, 0, ',', ' ').'<>'.number_format($som_SortiesVente, 0, ',', ' ')
		.'<>'.number_format($som_Clients, 0, ',', ' ').'<>'.number_format($som_Fournisseurs, 0, ',', ' ').'<>'.number_format($som_Services, 0, ',', ' ')
		.'<>'.number_format($som_Depenses, 0, ',', ' ').'<>'.number_format($som_SortiesVente, 0, ',', ' ').'<>'.number_format($som_SortiesBon, 0, ',', ' ')
		.'<>'.number_format(($moinsR - $plusR), 0, ',', ' ').'<>'.number_format(($moinsE - $plusE), 0, ',', ' ').'<>'.number_format($som_ClientsBE, 0, ',', ' ')
		.'<>'.number_format($som_FournisseursV, 0, ',', ' ').'<>'.number_format($som_EntreesPA2, 0, ',', ' ').'<>'.number_format($som_EntreesPU2, 0, ',', ' ').'<>'.number_format($som_SortiesVente2, 0, ',', ' ')
		.'<>'.number_format($som_SortiesBon2, 0, ',', ' ').'<>'.number_format(($moinsR2 - $plusR2), 0, ',', ' ').'<>'.number_format(($moinsE2 - $plusE2), 0, ',', ' ')
		.'<>'.number_format(($moinsRe2 - $plusRe2), 0, ',', ' ').'<>'.number_format($som_Clients2, 0, ',', ' ').'<>'.number_format($som_ClientsBE2, 0, ',', ' ')
		.'<>'.number_format($som_Fournisseurs2, 0, ',', ' ').'<>'.number_format($som_FournisseursV2, 0, ',', ' ').'<>'.number_format($som_Services2, 0, ',', ' ')
		.'<>'.number_format($som_Depenses2, 0, ',', ' ').'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2), 0, ',', ' ').'<>'.number_format(($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ')
		.'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ').'<>'.number_format(($som_SortiesBon2+$som_ClientsBE2), 0, ',', ' ')
		.'<>'.number_format(($som_EntreesPA2+$som_EntreesPU2), 0, ',', ' ').'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2)+$SommeMontantAverser+$montantPU-$restant_FournisseursD, 0, ',', ' ').'<>'.number_format($SommeMontantAverser, 0, ',', ' ').'<>'.number_format($montantPU, 0, ',', ' ').'<>'.number_format($montantPA, 0, ',', ' ').'<>'.number_format($restant_Fournisseurs, 0, ',', ' ')
		.'<>'.number_format($restant_FournisseursD, 0, ',', ' ');
		// exit($result);

//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 40;

//Set Row Height
$row_height = 6;

$y_axis=40;

//Set maximum rows per page
$max = 35;
$i = 0;
$p=1;
$c=0;

// $nb_page=ceil($nbre[0]/$max);
$image='';
if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $image='pharmacie.png';
}
else{
    $image='shop.png';
}
$pdf-> SetFont("Arial","B",10);
// $pdf-> Image('images/'.$image,5,5,20,20);

$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

$champ_date = date_create($dateString2);
$annee = date_format($champ_date, 'Y');
$num_fact = "Rapport global de la caisse" ;
$num_fact2 = "du " . $dateD. " au ". $dateF ;
$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 10, "DF");
$pdf->SetY(20 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 5, utf8_decode($num_fact), 0, 0, 'C');
$pdf->Ln(); $pdf->SetFont( "Arial", "I", 12 ); $pdf->SetTextColor(100,100,100); $pdf->Cell( 0, 5, utf8_decode($num_fact2), 0, 0, 'C');

// observations
// $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetY(9) ; $pdf->Cell(0, 0, $_SESSION["labelB"].'dhudhwjdncji nhhcuhcuhc bwhbbjhuw dbwgbjuh', 0,0, "C");
$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");
// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
// $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
// $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

// $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->Cell($pdf->GetStringWidth("Ventes"), 0, 'Ventes', 0, "L");

// if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    //print column titles
    $pdf->SetFillColor(178,220,238);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'ENTREES EN ARGENT',1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_Clients2+$som_SortiesVente2+$som_Services2, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Versements clients',1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_Clients2, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Ventes',1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_SortiesVente2, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Services',1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_Services, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Produits retournés'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($moinsRe2 - $plusRe2, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(178,220,238);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'SORTIES EN ARGENT',1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_Depenses2+$som_FournisseursV+$som_ClientsBE2, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Dépenses'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_Depenses, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Versements fournisseurs',1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_FournisseursV, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Bon en espèces'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($som_ClientsBE, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(247,235,194);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'RESTANT CAISSE',1,0,'L',1);
    $pdf->Cell(60,6,number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(178,220,238);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'MONTANT A RECOUVRIR',1,0,'L',1);
    $pdf->Cell(60,6,number_format($SommeMontantAverser, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Bons (en produits + en epèces) non payés'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($SommeMontantAverser, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(178,220,238);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'VALEUR STOCKS COURANTS',1,0,'L',1);
    $pdf->Cell(60,6,'',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Valeurs stock (PA)',1,0,'L',1);
    $pdf->Cell(60,6,number_format($montantPA, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Valeurs stock (PU)',1,0,'L',1);
    $pdf->Cell(60,6,number_format($montantPU, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(178,220,238);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'PATRIMOINE DE L\'ENTREPRISE',1,0,'L',1);
    $pdf->Cell(60,6,number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2)+$SommeMontantAverser+$montantPU-$restant_FournisseursD, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Valeurs stock (PU)',1,0,'L',1);
    $pdf->Cell(60,6,number_format($montantPU, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Montant à recouvrir'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($SommeMontantAverser, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(241,246,249);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,'Restant caisse',1,0,'L',1);
    $pdf->Cell(60,6,number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(229,205,205);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Bl non payés'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($restant_FournisseursD, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

    $pdf->SetFillColor(229,205,205);
    $pdf->SetFont('Arial','',8);
    $pdf->SetY($y_axis);
    $pdf->SetX(20);
    $pdf->Cell(110,6,utf8_decode('Produits retirés'),1,0,'L',1);
    $pdf->Cell(60,6,number_format($moinsR - $plusR, 0, ',', ' ').' FCFA',1,0,'L',1);

    $pdf->SetY(-15);
    $pdf->SetFont('Arial','I',8);
    $pdf->SetTextColor(128);
    $pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$y_axis_initial = 45;
		//Add page for entrees stock
		$sql="SELECT * FROM `".$nomtableStock."` s
		LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
		WHERE s.dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY s.dateStockage DESC";
		$res=mysql_query($sql);
		if (mysql_num_rows($res) != 0) {
			// code...
		$y_axis = 45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des entrées par stock" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "VALEUR STOCK (PA) = ".number_format($som_EntreesPA, 0, ',', ' ')."FCFA <=> VALEUR STOCK (PU) = ".number_format($som_EntreesPU, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
	    //print column titles
	    $pdf->SetFillColor(192);
	    $pdf->SetFont('Arial','B',7);
	    $pdf->SetY($y_axis_initial);
	    $pdf->SetX(5);
	    $pdf->Cell(22,6,'DATE ENTREE',1,0,'L',1);
	    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
	    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'FORME',1,0,'L',1);
			$pdf->Cell(20,6,'TABLEAU',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX SESSION',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX PUBLIC',1,0,'L',1);

		}else{
			//print column titles
			$pdf->SetFillColor(192);
			$pdf->SetFont('Arial','B',7);
			$pdf->SetY($y_axis_initial);
			$pdf->SetX(5);
			$pdf->Cell(22,6,'DATE ENTREE',1,0,'L',1);
			$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'UNITE STOCK',1,0,'L',1);
			$pdf->Cell(20,6,'NBR ART U.S',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX ACHAT (FCFA)',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);

		}
		// $y_axis = $y_axis + $row_height;

		// echo mysql_num_rows($res);
		// $data=array();
		// $produits=array();
		// $i=5;
		while($stock=mysql_fetch_array($res)){

		  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
		  $res1=mysql_query($sql1);
		  $designation=mysql_fetch_array($res1);
			$y_axis = $y_axis + $row_height;

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des entrées par stock" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "VALEUR STOCK (PA) = ".number_format($som_EntreesPA, 0, ',', ' ')."FCFA <=> VALEUR STOCK (PU) = ".number_format($som_EntreesPU, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

					if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(5);
			    $pdf->Cell(22,6,'DATE ENTREES',1,0,'L',1);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'FORME',1,0,'L',1);
			    $pdf->Cell(20,6,'TABLEAU',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX SESSION',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX PUBLIC',1,0,'L',1);

				}else{
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(5);
			    $pdf->Cell(22,6,'DATE ENTREES',1,0,'L',1);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE STOCK',1,0,'L',1);
			    $pdf->Cell(20,6,'NBR ART U.S',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX ACHAT (FCFA)',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
				}
          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

      if($c%2==0){
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(255,255,255);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(5);
          $pdf->SetFont('Arial','',7);
          if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

						$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
						if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
							$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
						}
						else{
							$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
						}
						$pdf->Cell(22,6,utf8_decode($designation['forme']),1,0,'L',1);
						$pdf->Cell(20,6,number_format($designation['tableau'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(26,6,number_format($designation['prixSession'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($stock['prixPublic'], 0, ',', ' '),1,0,'L',1);

          } else{
							$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
		          if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
		            if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
									$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
		            }
		            else{
									$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
		            }
							} else{
									$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
							}
							$pdf->Cell(22,6,utf8_decode(strtoupper($designation['uniteStock'])),1,0,'L',1);
							$pdf->Cell(20,6,number_format($designation['nbreArticleUniteStock'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($designation['prixachat'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
          }

      }
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(5);
          $pdf->SetFont('Arial','',7);
          if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

							$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
							if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
								$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
							}
							else{
								$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
							}
							$pdf->Cell(22,6,utf8_decode($designation['forme']),1,0,'L',1);
							$pdf->Cell(20,6,number_format($designation['tableau'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($designation['prixSession'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prixPublic'], 0, ',', ' '),1,0,'L',1);
          }
          else{

							$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
		          if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
		            if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
									$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
		            }
		            else{
									$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
		            }
							} else{
									$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
							}
							$pdf->Cell(22,6,utf8_decode(strtoupper($designation['uniteStock'])),1,0,'L',1);
							$pdf->Cell(20,6,number_format($designation['nbreArticleUniteStock'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($designation['prixachat'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
          }

		    }

		    //Go to next row
		    // $y_axis = $y_axis + $row_height;
		    $i = $i + 1;
				$c = $c + 1;
			}

			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for entrees produits
		$sql="SELECT * FROM `".$nomtableStock."` s
		LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
		WHERE s.dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY s.dateStockage DESC";
		$res=mysql_query($sql);
		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des entrées par produit" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "VALEUR STOCK (PA) = ".number_format($som_EntreesPA, 0, ',', ' ')."FCFA <=> VALEUR STOCK (PU) = ".number_format($som_EntreesPU, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
	    //print column titles
	    $pdf->SetFillColor(192);
	    $pdf->SetFont('Arial','B',7);
	    $pdf->SetY($y_axis_initial);
	    $pdf->SetX(5);
	    $pdf->Cell(22,6,'DATE ENTREE',1,0,'L',1);
	    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
	    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'FORME',1,0,'L',1);
			$pdf->Cell(20,6,'TABLEAU',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX SESSION',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX PUBLIC',1,0,'L',1);

		}else{
			//print column titles
			$pdf->SetFillColor(192);
			$pdf->SetFont('Arial','B',7);
			$pdf->SetY($y_axis_initial);
			$pdf->SetX(5);
			$pdf->Cell(22,6,'DATE ENTREE',1,0,'L',1);
			$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'UNITE STOCK',1,0,'L',1);
			$pdf->Cell(20,6,'NBR ART U.S',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX ACHAT (FCFA)',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);

		}
		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

			$sql1="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
			$res1=mysql_query($sql1);
			$designation=mysql_fetch_array($res1);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des entrées par produit" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "VALEUR STOCK (PA) = ".number_format($som_EntreesPA, 0, ',', ' ')."FCFA <=> VALEUR STOCK (PU) = ".number_format($som_EntreesPU, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

					if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(5);
			    $pdf->Cell(22,6,'DATE ENTREES',1,0,'L',1);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'FORME',1,0,'L',1);
			    $pdf->Cell(20,6,'TABLEAU',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX SESSION',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX PUBLIC',1,0,'L',1);

				}else{
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(5);
			    $pdf->Cell(22,6,'DATE ENTREES',1,0,'L',1);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE STOCK',1,0,'L',1);
			    $pdf->Cell(20,6,'NBR ART U.S',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX ACHAT (FCFA)',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
				}
          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

			if(in_array($designation['idDesignation'], $produits)){
				// echo "Existe.";
			}
			else{
		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(5);
		        $pdf->SetFont('Arial','',7);

						if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
							$sqlS="SELECT SUM(quantiteStockinitial)
							FROM `".$nomtableStock."`
							where idDesignation ='".$stock['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
							$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
							$S_stock = mysql_fetch_array($resS);

							$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
							if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
								$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
							}
							else{
								$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
							}
							$pdf->Cell(22,6,utf8_decode($designation['forme']),1,0,'L',1);
							$pdf->Cell(20,6,number_format($designation['tableau'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($designation['prixSession'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prixPublic'], 0, ',', ' '),1,0,'L',1);

						}else{
			        $sqlS="SELECT SUM(quantiteStockinitial)
			        FROM `".$nomtableStock."`
			        where idDesignation ='".$stock['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
			        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
			        $S_stock = mysql_fetch_array($resS);

							$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
							if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
								if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
									$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
								}
								else{
									$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
								}
							} else{
									$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
							}
							$pdf->Cell(22,6,utf8_decode(strtoupper($designation['uniteStock'])),1,0,'L',1);
							$pdf->Cell(20,6,number_format($designation['nbreArticleUniteStock'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($designation['prixachat'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
						}
      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(5);
          $pdf->SetFont('Arial','',7);

        	if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
						$sqlS="SELECT SUM(quantiteStockinitial)
						FROM `".$nomtableStock."`
						where idDesignation ='".$stock['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
						$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
						$S_stock = mysql_fetch_array($resS);

						$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
						if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
							$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
						}
						else{
							$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
						}
						$pdf->Cell(22,6,utf8_decode($designation['forme']),1,0,'L',1);
						$pdf->Cell(20,6,number_format($designation['tableau'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(26,6,number_format($designation['prixSession'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($stock['prixPublic'], 0, ',', ' '),1,0,'L',1);
          }
        else{
						$sqlS="SELECT SUM(quantiteStockinitial)
						FROM `".$nomtableStock."`
						where idDesignation ='".$stock['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
						$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
						$S_stock = mysql_fetch_array($resS);

						$pdf->Cell(22,6,$stock['dateStockage'],1,0,'L',1);
						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
	          if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
	            if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
								$pdf->Cell(20,6,number_format(round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']), 0, ',', ' '),1),1,0,'L',1);
	            }
	            else{
								$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
	            }
						} else{
								$pdf->Cell(20,6,number_format($stock['quantiteStockinitial'], 0, ',', ' '),1,0,'L',1);
						}
						$pdf->Cell(22,6,utf8_decode(strtoupper($designation['uniteStock'])),1,0,'L',1);
						$pdf->Cell(20,6,number_format($designation['nbreArticleUniteStock'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(26,6,number_format($designation['prixachat'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
          }
				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;

	    }

		    //Go to next row

				// $c = $c + 1;
				$produits[] = $designation['idDesignation'];
			}

			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for ventes panier

		$sql="SELECT *
		FROM  `".$nomtablePagnet."` p
		WHERE p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des ventes par panier" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');


		$valeur = "MONTANT TOTAL DES VENTES = ".number_format($som_SortiesVente, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(25);
		$pdf->Cell(30,6,'DATE DU PANIER',1,0,'L',1);
		$pdf->Cell(50,6,'TOTAL DU PANIER',1,0,'L',1);
		$pdf->Cell(30,6,'REMISE',1,0,'L',1);
		$pdf->Cell(50,6,'TOTAL A PAYER',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($p=mysql_fetch_array($res)){

			  $sql2="SELECT *
			  FROM  `".$nomtableLigne."` l
			  WHERE l.classe = 0 && l.idPagnet = ".$p['idPagnet']." ORDER BY l.numligne DESC ";
			  $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
			  $l=mysql_fetch_array($res2);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des ventes par panier" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES VENTES = ".number_format($som_SortiesVente, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(25);
					$pdf->Cell(30,6,'DATE DU PANIER',1,0,'L',1);
					$pdf->Cell(50,6,'TOTAL DU PANIER',1,0,'L',1);
					$pdf->Cell(30,6,'REMISE',1,0,'L',1);
					$pdf->Cell(50,6,'TOTAL A PAYER',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

			if(empty($l)){
				// echo "Existe.";
			}
			else{
		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(25);
		        $pdf->SetFont('Arial','',7);

						$pdf->Cell(30,6,$p['datepagej'],1,0,'L',1);
						$pdf->Cell(50,6,number_format($p['totalp'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($p['remise'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(50,6,number_format($p['apayerPagnet'], 0, ',', ' '),1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(25);
          $pdf->SetFont('Arial','',7);

					$pdf->Cell(30,6,$p['datepagej'],1,0,'L',1);
					$pdf->Cell(50,6,number_format($p['totalp'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(30,6,number_format($p['remise'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(50,6,number_format($p['apayerPagnet'], 0, ',', ' '),1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;

	    }

		    //Go to next row

				// $c = $c + 1;
				// $produits[] = $designation['idDesignation'];
			}

			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for ventes produits

		$sql="SELECT *
		FROM  `".$nomtableLigne."` l
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		WHERE l.classe = 0 && p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des ventes par produit" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES VENTES = ".number_format($som_SortiesVente, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
	    //print column titles
	    $pdf->SetFillColor(192);
	    $pdf->SetFont('Arial','B',7);
	    $pdf->SetY($y_axis_initial);
	    $pdf->SetX(25);
			$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);

		}else{
			//print column titles
			$pdf->SetFillColor(192);
			$pdf->SetFont('Arial','B',7);
			$pdf->SetY($y_axis_initial);
			$pdf->SetX(25);
			$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);

		}
		$y_axis = $y_axis + $row_height;

		$sql="SELECT *
		FROM  `".$nomtableLigne."` l
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		WHERE l.classe = 0 && p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		$qte_S = 0;
		$qte_A = 0;
		while($stock=mysql_fetch_array($res)){

			$sql1="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
			$res1=mysql_query($sql1);
			$designation=mysql_fetch_array($res1);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des ventes par produit" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES VENTES = ".number_format($som_SortiesVente, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

					if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(25);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIE (FCFA)',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);

				}else{
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(25);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIE (FCFA)',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);
				}
          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		  if ($designation['designation'] == "") {

		  } else {

		  $sqlS="SELECT SUM(prixtotal)
		  FROM `".$nomtableLigne."` l
		  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
		  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		  where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnneeConvert."' AND '".$finAnnee."'  ";
		  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
		  $prixTotal = mysql_fetch_array($resS);
		    // code...
			if(in_array($designation['idDesignation'], $produits)){
				// echo "Existe.";
			}
			else{
		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(25);
		        $pdf->SetFont('Arial','',7);

						if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

				      $sqlQ="SELECT SUM(quantite)
				      FROM `".$nomtableLigne."` l
				      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				      where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
				      $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
				      $qte = mysql_fetch_array($resQ);

							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
							$pdf->Cell(20,6,utf8_decode(strtoupper($designation['forme'])),1,0,'L',1);
							$pdf->Cell(22,6,number_format(($qte[0]), 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);

						}else{
				      $sqlQA="SELECT SUM(quantite) as qa
				      FROM `".$nomtableLigne."` l
				      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				      where p.idClient=0 &&  p.verrouiller=1 && l.unitevente!='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
				      $resQA=mysql_query($sqlQA) or die ("select stock impossible =>".mysql_error());
				      $qte_A_fecth = mysql_fetch_array($resQA);
				      $qte_A = $qte_A_fecth['qa'];

				      $sqlQS="SELECT SUM(quantite) as qs
				      FROM `".$nomtableLigne."` l
				      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				      where p.idClient=0 && p.verrouiller=1 && l.unitevente='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
				      $resQS=mysql_query($sqlQS) or die ("select stock impossible =>".mysql_error());
				      $qte_S_fecth = mysql_fetch_array($resQS);
				      $qte_S = $qte_S_fecth['qs'];

							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);

			        if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {

								$pdf->Cell(20,6,number_format($qte_S, 0, ',', ' '),1,0,'L',1);
			        }
			        else{

								$pdf->Cell(20,6,number_format(($qte_A + ($qte_S * $designation['nbreArticleUniteStock'])), 0, ',', ' '),1,0,'L',1);

			        }

							$pdf->Cell(22,6,utf8_decode(strtoupper($stock['unitevente'])),1,0,'L',1);

			        if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
								$pdf->Cell(30,6,number_format($designation['prixuniteStock'], 0, ',', ' '),1,0,'L',1);
			        }
			        else{
								$pdf->Cell(30,6,number_format($designation['prix'], 0, ',', ' '),1,0,'L',1);

			        }
							$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
						}
      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(25);
          $pdf->SetFont('Arial','',7);

        	if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

			      $sqlQ="SELECT SUM(quantite)
			      FROM `".$nomtableLigne."` l
			      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
			      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			      where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
			      $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
			      $qte = mysql_fetch_array($resQ);

						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
						$pdf->Cell(20,6,utf8_decode(strtoupper($designation['forme'])),1,0,'L',1);
						$pdf->Cell(22,6,number_format(($qte[0]), 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
          }
        else{
			      $sqlQA="SELECT SUM(quantite) as qa
			      FROM `".$nomtableLigne."` l
			      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
			      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			      where p.idClient=0 &&  p.verrouiller=1 && l.unitevente!='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
			      $resQA=mysql_query($sqlQA) or die ("select stock impossible =>".mysql_error());
			      $qte_A_fecth = mysql_fetch_array($resQA);
			      $qte_A = $qte_A_fecth['qa'];

			      $sqlQS="SELECT SUM(quantite) as qs
			      FROM `".$nomtableLigne."` l
			      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
			      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			      where p.idClient=0 && p.verrouiller=1 && l.unitevente='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
			      $resQS=mysql_query($sqlQS) or die ("select stock impossible =>".mysql_error());
			      $qte_S_fecth = mysql_fetch_array($resQS);
			      $qte_S = $qte_S_fecth['qs'];

						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);

						if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {

							$pdf->Cell(20,6,number_format($qte_S, 0, ',', ' '),1,0,'L',1);
						}
						else{

							$pdf->Cell(20,6,number_format(($qte_A + ($qte_S * $designation['nbreArticleUniteStock'])), 0, ',', ' '),1,0,'L',1);

						}

						$pdf->Cell(22,6,utf8_decode(strtoupper($stock['unitevente'])),1,0,'L',1);

						if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
							$pdf->Cell(30,6,number_format($designation['prixuniteStock'], 0, ',', ' '),1,0,'L',1);
						}
						else{
							$pdf->Cell(30,6,number_format($designation['prix'], 0, ',', ' '),1,0,'L',1);

						}
						$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
          }
				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
				$produits[] = $designation['idDesignation'];

	    }

		    //Go to next row

				// $c = $c + 1;
			}
		}

			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for bon panier

		$sqlS="SELECT *
		FROM `".$nomtableLigne."` l
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		where p.idClient!=0 &&  p.verrouiller=1 && p.type=0 && l.classe=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
		$res=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des bons par panier" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES BONS = ".number_format($som_SortiesBon, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(25);
		$pdf->Cell(30,6,'DATE DU PANIER',1,0,'L',1);
		$pdf->Cell(50,6,'TOTAL DU PANIER',1,0,'L',1);
		$pdf->Cell(30,6,'REMISE',1,0,'L',1);
		$pdf->Cell(50,6,'TOTAL A PAYER',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($p=mysql_fetch_array($res)){

			  $sql2="SELECT *
			  FROM  `".$nomtableLigne."` l
			  WHERE l.classe = 0 && l.idPagnet = ".$p['idPagnet']." ORDER BY l.numligne DESC ";
			  $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
			  $l=mysql_fetch_array($res2);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des bons par panier" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES BONS = ".number_format($som_SortiesBon, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(25);
					$pdf->Cell(30,6,'DATE DU PANIER',1,0,'L',1);
					$pdf->Cell(50,6,'TOTAL DU PANIER',1,0,'L',1);
					$pdf->Cell(30,6,'REMISE',1,0,'L',1);
					$pdf->Cell(50,6,'TOTAL A PAYER',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

			if(in_array($p['idPagnet'], $produits)){
				// echo "Existe.";
			}
			else{
		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(25);
		        $pdf->SetFont('Arial','',7);

						$pdf->Cell(30,6,$p['datepagej'],1,0,'L',1);
						$pdf->Cell(50,6,number_format($p['totalp'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($p['remise'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(50,6,number_format($p['apayerPagnet'], 0, ',', ' '),1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(25);
          $pdf->SetFont('Arial','',7);

					$pdf->Cell(30,6,$p['datepagej'],1,0,'L',1);
					$pdf->Cell(50,6,number_format($p['totalp'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(30,6,number_format($p['remise'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(50,6,number_format($p['apayerPagnet'], 0, ',', ' '),1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
		    $produits[]=$p['idPagnet'];

	    }

		    //Go to next row

				// $c = $c + 1;
			}

			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for bon produits

		$sql="SELECT *
		FROM  `".$nomtableLigne."` l
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		WHERE l.classe = 0 && p.idClient!=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
		if (mysql_num_rows($res) != 0) {
			// code...
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des bons par produit" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES BONS = ".number_format($som_SortiesBon, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
	    //print column titles
	    $pdf->SetFillColor(192);
	    $pdf->SetFont('Arial','B',7);
	    $pdf->SetY($y_axis_initial);
	    $pdf->SetX(25);
			$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);

		}else{
			//print column titles
			$pdf->SetFillColor(192);
			$pdf->SetFont('Arial','B',7);
			$pdf->SetY($y_axis_initial);
			$pdf->SetX(25);
			$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			$pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
			$pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);

		}
		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		$qte_S = 0;
		$qte_A = 0;
		while($stock=mysql_fetch_array($res)){

			$sql1="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
			$res1=mysql_query($sql1);
			$designation=mysql_fetch_array($res1);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des bons par produit" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES BONS = ".number_format($som_SortiesBon, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

					if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(25);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIE (FCFA)',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);

				}else{
			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(25);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE VENTE',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIE (FCFA)',1,0,'L',1);
			    $pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);
				}
          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		  if ($designation['designation'] == "") {

		  } else {

		  $sqlS="SELECT SUM(prixtotal)
		  FROM `".$nomtableLigne."` l
		  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
		  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		  where p.idClient!=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnneeConvert."' AND '".$finAnnee."'  ";
		  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
		  $prixTotal = mysql_fetch_array($resS);
		    // code...
			if(in_array($designation['idDesignation'], $produits)){
				// echo "Existe.";
			}
			else{
		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(25);
		        $pdf->SetFont('Arial','',7);

						if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

				      $sqlQ="SELECT SUM(quantite)
				      FROM `".$nomtableLigne."` l
				      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				      where p.idClient!=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
				      $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
				      $qte = mysql_fetch_array($resQ);

							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
							$pdf->Cell(20,6,utf8_decode(strtoupper($designation['forme'])),1,0,'L',1);
							$pdf->Cell(22,6,number_format(($qte[0]), 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
							$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);

						}else{
				      $sqlQA="SELECT SUM(quantite) as qa
				      FROM `".$nomtableLigne."` l
				      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				      where p.idClient!=0 &&  p.verrouiller=1 && l.unitevente!='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
				      $resQA=mysql_query($sqlQA) or die ("select stock impossible =>".mysql_error());
				      $qte_A_fecth = mysql_fetch_array($resQA);
				      $qte_A = $qte_A_fecth['qa'];

				      $sqlQS="SELECT SUM(quantite) as qs
				      FROM `".$nomtableLigne."` l
				      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
				      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				      where p.idClient!=0 && p.verrouiller=1 && l.unitevente='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
				      $resQS=mysql_query($sqlQS) or die ("select stock impossible =>".mysql_error());
				      $qte_S_fecth = mysql_fetch_array($resQS);
				      $qte_S = $qte_S_fecth['qs'];

							$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);

			        if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {

								$pdf->Cell(20,6,number_format($qte_S, 0, ',', ' '),1,0,'L',1);
			        }
			        else{

								$pdf->Cell(20,6,number_format(($qte_A + ($qte_S * $designation['nbreArticleUniteStock'])), 0, ',', ' '),1,0,'L',1);

			        }

							$pdf->Cell(22,6,utf8_decode(strtoupper($stock['unitevente'])),1,0,'L',1);

			        if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
								$pdf->Cell(30,6,number_format($designation['prixuniteStock'], 0, ',', ' '),1,0,'L',1);
			        }
			        else{
								$pdf->Cell(30,6,number_format($designation['prix'], 0, ',', ' '),1,0,'L',1);

			        }
							$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
						}
      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(25);
          $pdf->SetFont('Arial','',7);

        	if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

			      $sqlQ="SELECT SUM(quantite)
			      FROM `".$nomtableLigne."` l
			      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
			      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			      where p.idClient!=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
			      $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
			      $qte = mysql_fetch_array($resQ);

						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
						$pdf->Cell(20,6,utf8_decode(strtoupper($designation['forme'])),1,0,'L',1);
						$pdf->Cell(22,6,number_format(($qte[0]), 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(30,6,number_format($stock['prix'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
          }
        else{
			      $sqlQA="SELECT SUM(quantite) as qa
			      FROM `".$nomtableLigne."` l
			      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
			      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			      where p.idClient!=0 &&  p.verrouiller=1 && l.unitevente!='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
			      $resQA=mysql_query($sqlQA) or die ("select stock impossible =>".mysql_error());
			      $qte_A_fecth = mysql_fetch_array($resQA);
			      $qte_A = $qte_A_fecth['qa'];

			      $sqlQS="SELECT SUM(quantite) as qs
			      FROM `".$nomtableLigne."` l
			      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
			      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			      where p.idClient!=0 && p.verrouiller=1 && l.unitevente='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
			      $resQS=mysql_query($sqlQS) or die ("select stock impossible =>".mysql_error());
			      $qte_S_fecth = mysql_fetch_array($resQS);
			      $qte_S = $qte_S_fecth['qs'];

						$pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);

						if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {

							$pdf->Cell(20,6,number_format($qte_S, 0, ',', ' '),1,0,'L',1);
						}
						else{

							$pdf->Cell(20,6,number_format(($qte_A + ($qte_S * $designation['nbreArticleUniteStock'])), 0, ',', ' '),1,0,'L',1);

						}

						$pdf->Cell(22,6,utf8_decode(strtoupper($stock['unitevente'])),1,0,'L',1);

						if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
							$pdf->Cell(30,6,number_format($designation['prixuniteStock'], 0, ',', ' '),1,0,'L',1);
						}
						else{
							$pdf->Cell(30,6,number_format($designation['prix'], 0, ',', ' '),1,0,'L',1);

						}
						$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
          }
				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
				$produits[] = $designation['idDesignation'];

	    }

		    //Go to next row

				// $c = $c + 1;
			}
		}

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
	}
		//Add page for retires

		$sql="SELECT *from `".$nomtableInventaire."` i
		INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
		WHERE i.TYPE=3 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
		$res=mysql_query($sql);

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$num_fact2 = "Détails des produits retirés" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES PRODUITS RETIRES = ".number_format(($moinsR - $plusR), 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',7);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(10);
    $pdf->Cell(30,6,'DATE INVENTAIRE',1,0,'L',1);
    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
    $pdf->Cell(25,6,'DATE STOCKAGE',1,0,'L',1);
    $pdf->Cell(25,6,'QUANTITE STOCK',1,0,'L',1);
    $pdf->Cell(30,6,'QUANTITE INVENTAIRE',1,0,'L',1);
		$pdf->Cell(26,6,'TYPE',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $res=mysql_query($sql);

		$data=array();
		$produits=array();
		$i=0;
		while($inventaire=mysql_fetch_array($res)){

		  $sql4="SELECT * FROM `". $nomtableStock."`s where s.idStock='".$inventaire['idStock']."' ";
		  $res4=mysql_query($sql4);
		  $stock=mysql_fetch_array($res4);

		  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' ";
		  $resN=mysql_query($sqlN);
		  $designation = mysql_fetch_array($resN);

		  $date1 = strtotime($dateString);
		  $date2 = strtotime($inventaire['dateInventaire']);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des produits retirés" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES PRODUITS RETIRES = ".number_format(($moinsR - $plusR), 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(10);
			    $pdf->Cell(30,6,'DATE INVENTAIRE',1,0,'L',1);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(25,6,'DATE STOCKAGE',1,0,'L',1);
			    $pdf->Cell(25,6,'QUANTITE STOCK',1,0,'L',1);
			    $pdf->Cell(30,6,'QUANTITE INVENTAIRE',1,0,'L',1);
					$pdf->Cell(26,6,'TYPE',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		  if(in_array($designation['idDesignation'], $produits)){
		    // echo "Existe.";
		  }
		  else{
				if ($c%2==0) {
					// code...
	        $pdf->SetY($y_axis);
	        $pdf->SetFillColor(255,255,255);
	        //$pdf->SetTextColor(255,255,255);
	        $pdf->SetX(10);
	        $pdf->SetFont('Arial','',7);

			    $pdf->Cell(30,6,$inventaire['dateInventaire'],1,0,'L',1);
			    $pdf->Cell(60,6,utf8_decode(strtoupper($stock['designation'])),1,0,'L',1);
			    $pdf->Cell(25,6,$stock['dateStockage'],1,0,'L',1);
			    $pdf->Cell(25,6,$inventaire['quantiteStockCourant'],1,0,'L',1);

					if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
						$pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
					}
					else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {

				    $pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
					}

					if($inventaire['type']==0){
						$pdf->Cell(26,6,'NORMAL',1,0,'L',1);
					}
					else if($inventaire['type']==1){
						$pdf->Cell(26,6,'EXPIRATION',1,0,'L',1);
					}
					else if($inventaire['type']==2){
						$pdf->Cell(26,6,'MODIFICATION',1,0,'L',1);
					}
					else if($inventaire['type']==3){
						$pdf->Cell(26,6,'RETIRER',1,0,'L',1);
										}
				} else {
					// code...
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(10);
          $pdf->SetFont('Arial','',7);

			    $pdf->Cell(30,6,$inventaire['dateInventaire'],1,0,'L',1);
			    $pdf->Cell(60,6,utf8_decode(strtoupper($stock['designation'])),1,0,'L',1);
			    $pdf->Cell(25,6,$stock['dateStockage'],1,0,'L',1);
			    $pdf->Cell(25,6,$inventaire['quantiteStockCourant'],1,0,'L',1);

					if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
						$pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
					}
					else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {

				    $pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
					}

					if($inventaire['type']==0){
						$pdf->Cell(26,6,'NORMAL',1,0,'L',1);
					}
					else if($inventaire['type']==1){
						$pdf->Cell(26,6,'EXPIRATION',1,0,'L',1);
					}
					else if($inventaire['type']==2){
						$pdf->Cell(26,6,'MODIFICATION',1,0,'L',1);
					}
					else if($inventaire['type']==3){
						$pdf->Cell(26,6,'RETIRER',1,0,'L',1);
					}
				}
		  }

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
				$produits[] = $designation['idDesignation'];

		}
		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
	}
		//Add page for expires

		$sql="SELECT *from `".$nomtableInventaire."` i
		INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
		WHERE i.TYPE=1 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
		$res=mysql_query($sql);

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$num_fact2 = "Détails des produits expirés" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES PRODUITS EXPIRES = ".number_format(($moinsR - $plusR), 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',7);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(10);
    $pdf->Cell(30,6,'DATE INVENTAIRE',1,0,'L',1);
    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
    $pdf->Cell(25,6,'DATE STOCKAGE',1,0,'L',1);
    $pdf->Cell(25,6,'QUANTITE STOCK',1,0,'L',1);
    $pdf->Cell(30,6,'QUANTITE INVENTAIRE',1,0,'L',1);
		$pdf->Cell(26,6,'TYPE',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $res=mysql_query($sql);

		$data=array();
		$produits=array();
		$i=0;
		while($inventaire=mysql_fetch_array($res)){

		  $sql4="SELECT * FROM `". $nomtableStock."`s where s.idStock='".$inventaire['idStock']."' ";
		  $res4=mysql_query($sql4);
		  $stock=mysql_fetch_array($res4);

		  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' ";
		  $resN=mysql_query($sqlN);
		  $designation = mysql_fetch_array($resN);

		  $date1 = strtotime($dateString);
		  $date2 = strtotime($inventaire['dateInventaire']);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des produits expirés" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES PRODUITS EXPIRES = ".number_format(($moinsR - $plusR), 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(10);
			    $pdf->Cell(30,6,'DATE INVENTAIRE',1,0,'L',1);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(25,6,'DATE STOCKAGE',1,0,'L',1);
			    $pdf->Cell(25,6,'QUANTITE STOCK',1,0,'L',1);
			    $pdf->Cell(30,6,'QUANTITE INVENTAIRE',1,0,'L',1);
					$pdf->Cell(26,6,'TYPE',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		  if(in_array($designation['idDesignation'], $produits)){
		    // echo "Existe.";
		  }
		  else{
				if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){

					if ($c%2==0) {
						// code...
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(10);
		        $pdf->SetFont('Arial','',7);

				    $pdf->Cell(30,6,$inventaire['dateInventaire'],1,0,'L',1);
				    $pdf->Cell(60,6,utf8_decode(strtoupper($stock['designation'])),1,0,'L',1);
				    $pdf->Cell(25,6,$stock['dateStockage'],1,0,'L',1);
				    $pdf->Cell(25,6,$inventaire['quantiteStockCourant'],1,0,'L',1);

						if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
							$pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
						}
						else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {

					    $pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
						}

						if($inventaire['type']==0){
							$pdf->Cell(26,6,'NORMAL',1,0,'L',1);
						}
						else if($inventaire['type']==1){
							$pdf->Cell(26,6,'EXPIRATION',1,0,'L',1);
						}
						else if($inventaire['type']==2){
							$pdf->Cell(26,6,'MODIFICATION',1,0,'L',1);
						}
						else if($inventaire['type']==3){
							$pdf->Cell(26,6,'RETIRER',1,0,'L',1);
											}
					} else {
						// code...
	          $pdf->SetY($y_axis);
	          $pdf->SetFillColor(200,210,230);
	          //$pdf->SetTextColor(255,255,255);
	          $pdf->SetX(10);
	          $pdf->SetFont('Arial','',7);

				    $pdf->Cell(30,6,$inventaire['dateInventaire'],1,0,'L',1);
				    $pdf->Cell(60,6,utf8_decode(strtoupper($stock['designation'])),1,0,'L',1);
				    $pdf->Cell(25,6,$stock['dateStockage'],1,0,'L',1);
				    $pdf->Cell(25,6,$inventaire['quantiteStockCourant'],1,0,'L',1);

						if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
							$pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
						}
						else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {

					    $pdf->Cell(30,6,'-'.($inventaire['quantiteStockCourant'] - $inventaire['quantite']),1,0,'L',1);
						}

						if($inventaire['type']==0){
							$pdf->Cell(26,6,'NORMAL',1,0,'L',1);
						}
						else if($inventaire['type']==1){
							$pdf->Cell(26,6,'EXPIRATION',1,0,'L',1);
						}
						else if($inventaire['type']==2){
							$pdf->Cell(26,6,'MODIFICATION',1,0,'L',1);
						}
						else if($inventaire['type']==3){
							$pdf->Cell(26,6,'RETIRER',1,0,'L',1);
						}
					}

					$i = $i + 1;
			    $y_axis = $y_axis + $row_height;
					$c = $c + 1;
					$produits[] = $designation['idDesignation'];
				}
		  }
		}
		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
	}
		//Add page for clients bon especes

		$sql="SELECT *
		FROM  `".$nomtableLigne."` l
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
		WHERE l.classe = 6  && p.idClient !=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des bons espèces des clients" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES BONS DES CLIENTS = ".number_format($som_ClientsBE, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(30);
		$pdf->Cell(60,6,'NOM COMPLET DU CLIENT',1,0,'L',1);
		$pdf->Cell(40,6,'MONTANT DU BON (FCFA)',1,0,'L',1);
		$pdf->Cell(50,6,'DATE DU BON',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

	    $sqlN="select * from `".$nomtableClient."` where idClient='".$stock["idClient"]."'";
	    $resN=mysql_query($sqlN);
	    $client = mysql_fetch_array($resN);
	    $date1 = strtotime($dateString);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des bons en espèces des clients" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES BONS DES CLIENTS = ".number_format($som_ClientsBE, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(30);
					$pdf->Cell(60,6,'NOM COMPLET DU CLIENT',1,0,'L',1);
					$pdf->Cell(40,6,'MONTANT DU BON (FCFA)',1,0,'L',1);
					$pdf->Cell(50,6,'DATE DU BON',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(30);
		        $pdf->SetFont('Arial','',7);

						$pdf->Cell(60,6,utf8_decode(strtoupper($client['prenom'])).' '.utf8_decode(strtoupper($client['nom'])),1,0,'L',1);
						$pdf->Cell(40,6,number_format($stock['apayerPagnet'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(50,6,$stock['datepagej'].' '.$stock['heurePagnet'],1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(30);
          $pdf->SetFont('Arial','',7);

					$pdf->Cell(60,6,utf8_decode(strtoupper($client['prenom'])).' '.utf8_decode(strtoupper($client['nom'])),1,0,'L',1);
					$pdf->Cell(40,6,number_format($stock['apayerPagnet'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(50,6,$stock['datepagej'].' '.$stock['heurePagnet'],1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
		    // $produits[]=$p['idPagnet'];
		    //Go to next row

				// $c = $c + 1;
			}
			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for versements clients

		$sql="SELECT *
		FROM  `".$nomtableVersement."`
		WHERE idClient!=0 && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY idVersement DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

	if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des versements des clients" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES VERSEMENTS DES CLIENTS = ".number_format($som_Clients, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(15);
		$pdf->Cell(60,6,'NOM COMPLET DU CLIENT',1,0,'L',1);
		$pdf->Cell(40,6,'MONTANT DU BON (FCFA)',1,0,'L',1);
		$pdf->Cell(40,6,'MODE DE PAIEMENT',1,0,'L',1);
		$pdf->Cell(40,6,'DATE DU BON',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

			$sqlN="select * from `".$nomtableClient."` where idClient='".$stock["idClient"]."'";
		  $resN=mysql_query($sqlN);
		  $client = mysql_fetch_array($resN);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des versements des clients" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES VERSEMENTS DES CLIENTS = ".number_format($som_Clients, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(15);
					$pdf->Cell(60,6,'NOM COMPLET DU CLIENT',1,0,'L',1);
					$pdf->Cell(40,6,'MONTANT DU VERSEMENT (FCFA)',1,0,'L',1);
					$pdf->Cell(40,6,'MODE DE PAIEMENT',1,0,'L',1);
					$pdf->Cell(40,6,'DATE DU VERSEMENT',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(15);
		        $pdf->SetFont('Arial','',7);

						$pdf->Cell(60,6,utf8_decode(strtoupper($client['prenom'])).' '.utf8_decode(strtoupper($client['nom'])),1,0,'L',1);
						$pdf->Cell(40,6,number_format($stock['montant'], 0, ',', ' '),1,0,'L',1);
						if($stock['paiement']!=null){
							$pdf->Cell(40,6,strtoupper($stock['paiement']),1,0,'L',1);
				    }
				    else{
							$pdf->Cell(40,6,'ESPECES',1,0,'L',1);
				    }
						$pdf->Cell(40,6,$stock['dateVersement'].' '.$stock['heureVersement'],1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(15);
          $pdf->SetFont('Arial','',7);

					$pdf->Cell(60,6,utf8_decode(strtoupper($client['prenom'])).' '.utf8_decode(strtoupper($client['nom'])),1,0,'L',1);
					$pdf->Cell(40,6,number_format($stock['montant'], 0, ',', ' '),1,0,'L',1);
					if($stock['paiement']!=null){
						$pdf->Cell(40,6,strtoupper($stock['paiement']),1,0,'L',1);
			    }
			    else{
						$pdf->Cell(40,6,'ESPECES',1,0,'L',1);
			    }
					$pdf->Cell(40,6,$stock['dateVersement'].' '.$stock['heureVersement'],1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
		    // $produits[]=$p['idPagnet'];

		    //Go to next row

				// $c = $c + 1;
			}
			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for fournisseurs bl

		$sql="SELECT * FROM `".$nomtableBl."`
		WHERE idFournisseur!=0 AND CONCAT(CONCAT(SUBSTR(dateBl,7, 10),'',SUBSTR(dateBl,3, 4)),'',SUBSTR(dateBl,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY idBl DESC";
		$res=mysql_query($sql);

	if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des bons de livraison des fournisseurs" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES BL  = ".number_format($som_Fournisseurs, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(20);
		$pdf->Cell(60,6,'NOM DU FOURNISSEUR',1,0,'L',1);
		$pdf->Cell(30,6,'NUMERO DU BL',1,0,'L',1);
		$pdf->Cell(40,6,'MONTANT DU BL (FCFA)',1,0,'L',1);
		$pdf->Cell(40,6,'DATE DU BL',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

		  $sqlN="select * from `".$nomtableFournisseur."` where idFournisseur='".$stock["idFournisseur"]."'";
		  $resN=mysql_query($sqlN);
		  $fournisseur = mysql_fetch_array($resN);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des bons en espèces des clients" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES BL  = ".number_format($som_Fournisseurs, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
					$pdf->SetX(20);
					$pdf->Cell(60,6,'NOM DU FOURNISSEUR',1,0,'L',1);
					$pdf->Cell(30,6,'NUMERO DU BL',1,0,'L',1);
					$pdf->Cell(40,6,'MONTANT DU BL (FCFA)',1,0,'L',1);
					$pdf->Cell(40,6,'DATE DU BL',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(20);
		        $pdf->SetFont('Arial','',7);

						$pdf->Cell(60,6,utf8_decode(strtoupper($fournisseur['nomFournisseur'])),1,0,'L',1);
						$pdf->Cell(30,6,strtoupper($stock['numeroBl']),1,0,'L',1);
						$pdf->Cell(40,6,number_format($stock['montantBl'], 0, ',', ' '),1,0,'L',1);
						$pdf->Cell(40,6,$stock['dateBl'].' '.$stock['heureBl'],1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(20);
          $pdf->SetFont('Arial','',7);

					$pdf->Cell(60,6,utf8_decode(strtoupper($fournisseur['nomFournisseur'])),1,0,'L',1);
					$pdf->Cell(30,6,strtoupper($stock['numeroBl']),1,0,'L',1);
					$pdf->Cell(40,6,number_format($stock['montantBl'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(40,6,$stock['dateBl'].' '.$stock['heureBl'],1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
			}
			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for fournisseurs versements

		$sql="SELECT *
		FROM  `".$nomtableVersement."`
		WHERE idFournisseur!=0 && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY idVersement DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

	if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des versements des fournisseurs" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES VERSEMENTS FOURNISSEURS = ".number_format($som_FournisseursV, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(15);
		$pdf->Cell(60,6,'NOM COMPLET DU CLIENT',1,0,'L',1);
		$pdf->Cell(40,6,'MONTANT DU BON (FCFA)',1,0,'L',1);
		$pdf->Cell(40,6,'MODE DE PAIEMENT',1,0,'L',1);
		$pdf->Cell(40,6,'DATE DU BON',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		$sql="SELECT *
		FROM  `".$nomtableVersement."`
		WHERE idFournisseur!=0 && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY idVersement DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		// $data=array();
		$produits=array();
		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

		  $sqlN="select * from `".$nomtableFournisseur."` where idFournisseur='".$stock["idFournisseur"]."'";
		  $resN=mysql_query($sqlN);
		  $fournisseur = mysql_fetch_array($resN);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des versements des fournisseurs" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES VERSEMENTS FOURNISSEURS = ".number_format($som_FournisseursV, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(15);
					$pdf->Cell(60,6,'NOM DU FOURNISSEUR',1,0,'L',1);
					$pdf->Cell(40,6,'MONTANT DU VERSEMENT (FCFA)',1,0,'L',1);
					$pdf->Cell(40,6,'MODE DE VERSEMENT',1,0,'L',1);
					$pdf->Cell(40,6,'DATE DU VERSEMENT',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

		    if($c%2==0){
		        $pdf->SetY($y_axis);
		        $pdf->SetFillColor(255,255,255);
		        //$pdf->SetTextColor(255,255,255);
		        $pdf->SetX(15);
		        $pdf->SetFont('Arial','',7);

						$pdf->Cell(60,6,utf8_decode(strtoupper($client['prenom'])).' '.utf8_decode(strtoupper($client['nom'])),1,0,'L',1);
						$pdf->Cell(40,6,number_format($stock['montant'], 0, ',', ' '),1,0,'L',1);
						if($stock['paiement']!=null){
							$pdf->Cell(40,6,strtoupper($stock['paiement']),1,0,'L',1);
				    }
				    else{
							$pdf->Cell(40,6,'ESPECES',1,0,'L',1);
				    }
						$pdf->Cell(40,6,$stock['dateVersement'].' '.$stock['heureVersement'],1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(15);
          $pdf->SetFont('Arial','',7);

					$pdf->Cell(60,6,strtoupper($client['prenom']).' '.strtoupper($client['nom']),1,0,'L',1);
					$pdf->Cell(40,6,number_format($stock['montant'], 0, ',', ' '),1,0,'L',1);
					if($stock['paiement']!=null){
						$pdf->Cell(40,6,strtoupper($stock['paiement']),1,0,'L',1);
			    }
			    else{
						$pdf->Cell(40,6,'ESPECES',1,0,'L',1);
			    }
					$pdf->Cell(40,6,$stock['dateVersement'].' '.$stock['heureVersement'],1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
		    // $produits[]=$p['idPagnet'];

		    //Go to next row

				// $c = $c + 1;
			}
			$pdf->SetY(-15);
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(128);
			$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
		}
		//Add page for services

		$sql="SELECT *
		FROM  `".$nomtableLigne."` l
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		WHERE l.classe = 1 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des services" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES SERVICES = ".number_format($som_Services, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(15);
		$pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
		$pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
		$pdf->Cell(22,6,'UNITE SERVICE',1,0,'L',1);
		$pdf->Cell(30,6,'PRIX UNITAIRE (FCFA)',1,0,'L',1);
		$pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);
		$pdf->Cell(26,6,'DATE',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

		  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
		  $res1=mysql_query($sql1);
		  $designation=mysql_fetch_array($res1);

		  $sqlQ="SELECT quantite
		  FROM `".$nomtableLigne."` l
		  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
		  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		  where  l.classe = 1 && l.numligne='".$stock['numligne']."' && p.idClient=0 &&  p.verrouiller=1 && p.type=0 &&  CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
		  $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
		  $S_stock = mysql_fetch_array($resQ);

		  $sqlS="SELECT prixtotal
		  FROM `".$nomtableLigne."` l
		  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
		  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		  where  l.classe = 1 && l.numligne='".$stock['numligne']."' && p.idClient=0 &&  p.verrouiller=1 && p.type=0 &&  CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
		  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
		  $prixTotal = mysql_fetch_array($resS);

      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des services" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES SERVICES = ".number_format($som_Services, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
			    $pdf->SetX(15);
			    $pdf->Cell(60,6,'REFERENCE',1,0,'L',1);
			    $pdf->Cell(20,6,'QUANTITE',1,0,'L',1);
			    $pdf->Cell(22,6,'UNITE SERVICE',1,0,'L',1);
			    $pdf->Cell(30,6,'PRIX UNITAIE (FCFA)',1,0,'L',1);
					$pdf->Cell(26,6,'PRIX TOTAL (FCFA)',1,0,'L',1);
			    $pdf->Cell(26,6,'DATE',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

	    if($c%2==0){
	        $pdf->SetY($y_axis);
	        $pdf->SetFillColor(255,255,255);
	        //$pdf->SetTextColor(255,255,255);
	        $pdf->SetX(15);
	        $pdf->SetFont('Arial','',7);
			    $pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
			    $pdf->Cell(20,6,number_format($S_stock[0], 0, ',', ' '),1,0,'L',1);
			    $pdf->Cell(22,6,utf8_decode(strtoupper($stock['unitevente'])),1,0,'L',1);
			    $pdf->Cell(30,6,number_format($stock['prixunitevente'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
			    $pdf->Cell(26,6,$stock['datepagej'].' '.$stock['heurePagnet'],1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(15);
          $pdf->SetFont('Arial','',7);
			    $pdf->Cell(60,6,utf8_decode(strtoupper($designation['designation'])),1,0,'L',1);
			    $pdf->Cell(20,6,number_format($S_stock[0], 0, ',', ' '),1,0,'L',1);
			    $pdf->Cell(22,6,utf8_decode(strtoupper($stock['unitevente'])),1,0,'L',1);
			    $pdf->Cell(30,6,number_format($stock['prixunitevente'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(26,6,number_format($prixTotal[0], 0, ',', ' '),1,0,'L',1);
			    $pdf->Cell(26,6,$stock['datepagej'].' '.$stock['heurePagnet'],1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
				// $produits[] = $designation['idDesignation'];

		    //Go to next row

				// $c = $c + 1;
		}
		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
	}
		//Add page for depenses

		$sql="SELECT *
		FROM `".$nomtableLigne."` l
		INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
		INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
		WHERE l.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
		$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

		if (mysql_num_rows($res) != 0) {
		$y_axis=45;
		$pdf->AddPage();
		$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');

		$num_fact2 = "Détails des dépenses" ;
		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
		$pdf->SetY(20 ); $pdf->SetTextColor(0,0,0); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

		$valeur = "MONTANT TOTAL DES DEPENSES = ".number_format($som_Depenses, 0, ',', ' ')."FCFA" ;
		$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

		$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
		$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

		//print column titles
		$pdf->SetFillColor(192);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY($y_axis_initial);
		$pdf->SetX(10);
		$pdf->Cell(130,6,'REFERENCE',1,0,'L',1);
		$pdf->Cell(32,6,'PRIX TOTAL (FCFA)',1,0,'L',1);
		$pdf->Cell(27,6,'DATE',1,0,'L',1);

		$y_axis = $y_axis + $row_height;


		$i=0;
		$c=0;
		while($stock=mysql_fetch_array($res)){

		  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' ";
		  $resN=mysql_query($sqlN);
		  $designation = mysql_fetch_array($resN);


      //If the current row is the last one, create new page and print column title
      if ($i == $max) {
          $pdf->AddPage();

					$pdf->SetXY(120, 5); $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell( 160, 8, $pdf->PageNo(), 0, 0, 'C');

					$num_fact2 = "Détails des dépenses" ;
					$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 20, 150, 8, "DF");
					$pdf->SetY(20); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 8, utf8_decode($num_fact2), 0, 0, 'C');

					$valeur = "MONTANT TOTAL DES DEPENSES = ".number_format($som_Depenses, 0, ',', ' ')."FCFA" ;
					$pdf->SetY(35); $pdf->SetTextColor(192,57,57);$pdf->SetFont( "Arial", "U", 10 ); $pdf->Cell( 0, 8, utf8_decode($valeur), 0, 0, 'C');

					$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 20, 9 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 11 ) ; $pdf->Cell(22, 4, $_SESSION["adresseB"], 0, "L");
					$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 4, $_SESSION["telBoutique"], 0, "L");

          $y_axis=45;

			    //print column titles
			    $pdf->SetFillColor(192);
			    $pdf->SetFont('Arial','B',7);
			    $pdf->SetY($y_axis_initial);
					$pdf->SetX(10);
					$pdf->Cell(130,6,'REFERENCE',1,0,'L',1);
					$pdf->Cell(32,6,'PRIX TOTAL (FCFA)',1,0,'L',1);
					$pdf->Cell(27,6,'DATE',1,0,'L',1);

          //Go to next row
					$y_axis = $y_axis + $row_height;
          //Set $i variable to 0 (first row)
          $i = 0;

      }

	    if($c%2==0){
	        $pdf->SetY($y_axis);
	        $pdf->SetFillColor(255,255,255);
	        //$pdf->SetTextColor(255,255,255);
	        $pdf->SetX(10);
	        $pdf->SetFont('Arial','',7);
			    $pdf->Cell(130,6,utf8_decode(strtoupper($stock[3])),1,0,'L',1);
			    $pdf->Cell(32,6,number_format($stock['prixtotal'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(27,6,$stock['datepagej'].' '.$stock['heurePagnet'],1,0,'L',1);

      	}
      else{
          $pdf->SetY($y_axis);
          $pdf->SetFillColor(200,210,230);
          //$pdf->SetTextColor(255,255,255);
          $pdf->SetX(10);
          $pdf->SetFont('Arial','',7);
			    $pdf->Cell(130,6,utf8_decode(strtoupper($stock[3])),1,0,'L',1);
			    $pdf->Cell(32,6,number_format($stock['prixtotal'], 0, ',', ' '),1,0,'L',1);
					$pdf->Cell(27,6,$stock['datepagej'].' '.$stock['heurePagnet'],1,0,'L',1);

				}

				$i = $i + 1;
		    $y_axis = $y_axis + $row_height;
				$c = $c + 1;
				// $produits[] = $designation['idDesignation'];

		    //Go to next row

				// $c = $c + 1;
		}
		$pdf->SetY(-15);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
		$pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
	}
		$pdf->SetY(-10);
		$pdf->SetFont('Arial','I',8);
		$pdf->SetTextColor(128);
    $pdf->Cell(0,10,iconv("UTF-8", "ISO-8859-1","©JCaisse"),0,0,'C');

		$pdf->Output("I", '1');

}

?>
