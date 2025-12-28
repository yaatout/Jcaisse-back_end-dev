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


	/**************************************** */
		/**Debut ClientsVersements */
			$som_Clients=0;
			$sqlVC="SELECT SUM(montant) FROM `".$nomtableVersement."` 
			where (idClient!=0 AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."')) or (idClient!=0 AND  (dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."'))";
			$resVC = mysql_query($sqlVC) or die ("persoonel requête 2".mysql_error());
			$totalVC = mysql_fetch_array($resVC) ;
			$som_Clients=$totalVC[0];
		/**Fin ClientsVersements */
		/**Debut SortiesVente */
				$som_SortiesVente=0;
				$sqlV="SELECT *
				FROM `".$nomtableDesignation."` d
				INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
				$resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
				while ($pagnet = mysql_fetch_array($resV)) {
					$som_SortiesVente=$som_SortiesVente + $pagnet['prixtotal'];
				}
		/**Fin SortiesVente */
		/**Debut Services */
			$som_Services=0;
			$sqlSv="SELECT *
				FROM `".$nomtableDesignation."` d
				INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
				INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
				WHERE l.classe = 1 && p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC  ";
			$resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());
			while ($pagnet = mysql_fetch_array($resSv)) {
				$som_Services = $som_Services + $pagnet['prixtotal'];
			}
		/**Fin Services */
		/**Debut SortiesRetournés */
			$sql1="SELECT *from `".$nomtableInventaire."` i
			INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
			WHERE i.TYPE=3 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
			$res1=mysql_query($sql1);
			$som_SortiesRetournes=0;
			while ($inventaire = mysql_fetch_array($res1)) {
				if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
					if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
						if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){
							$som_SortiesRetournes = $som_SortiesRetournes + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prixPublic']);
						}
						else{
							$som_SortiesRetournes = $som_SortiesRetournes + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
						}    
					}
				}
			}
		
		/**Fin SortiesRetournés */
	/**************************************** */

	/**************************************** */
		/**Debut Depenses */
			$som_Depenses=0;
			$sqlD="SELECT DISTINCT p.idPagnet
				FROM `".$nomtablePagnet."` p
				INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
				WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC";
			$resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
			while ($pagnet = mysql_fetch_array($resD)) {
				$sqlS="SELECT SUM(apayerPagnet)
				FROM `".$nomtablePagnet."`
				where idClient=0 &&  verrouiller=1 && idPagnet='".$pagnet['idPagnet']."' && type=0  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$S_depenses = mysql_fetch_array($resS);
				$som_Depenses = $S_depenses[0] + $som_Depenses;
			}

		/**Fin Depenses */
		/**Debut FournisseursV */
			$som_FournisseursV=0;
			$sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."`
			WHERE idFournisseur!=0 && dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
			$resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());
			$totalV = mysql_fetch_array($resV);
			$som_FournisseursV=$totalV[0];
		/**Fin FournisseursV */
		/**Debut ClientsBonsEspece */
			$som_ClientsBE=0;
			$sqlBE="SELECT DISTINCT p.idPagnet
				FROM `".$nomtablePagnet."` p
				INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
				WHERE l.classe=6 && p.idClient!=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";
			$resBE = mysql_query($sqlBE) or die ("persoonel requête 2".mysql_error());
			while ($pagnet = mysql_fetch_array($resBE)) {
				$sqlS="SELECT SUM(apayerPagnet)
				FROM `".$nomtablePagnet."`
				where idClient!=0 &&  verrouiller=1 && idPagnet='".$pagnet['idPagnet']."' && type=0  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$S_bonEsp = mysql_fetch_array($resS);
				$som_ClientsBE = $S_bonEsp[0] + $som_ClientsBE;
			}
		/**Fin ClientsBonsEspece */
		/**Debut Remises Vente*/
			$remiseTotalVente=0;
			$sqlRm="SELECT DISTINCT p.idPagnet
				FROM `".$nomtablePagnet."` p
				INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
				WHERE (l.classe=0 || l.classe=1)  && p.idClient=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";
			$resRm = mysql_query($sqlRm) or die ("persoonel requête 2".mysql_error());
			while ($pagnet = mysql_fetch_array($resRm)) {
				$sqlS="SELECT SUM(remise)
				FROM `".$nomtablePagnet."`
				where verrouiller=1  && idClient=0 && type=0 && idPagnet='".$pagnet['idPagnet']."'  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$S_remises0 = mysql_fetch_array($resS);
				$remiseTotalVente = $S_remises0[0] + $remiseTotalVente;
			}
		/**Fin Remises Vente*/
	/**************************************** */

	/**************************************** */
		/**Debut SortiesBon */
			$som_SortiesBon=0;
			$sqlBP="SELECT DISTINCT p.idPagnet
				FROM `".$nomtablePagnet."` p
				INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
				WHERE (l.classe!=6 && l.classe!=9) && p.idClient!=0  && p.verrouiller=1 && (p.type=0 || p.type=30) && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";
			$resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());
			while ($pagnet = mysql_fetch_array($resBP)) {
				$sqlS="SELECT SUM(apayerPagnet)
				FROM `".$nomtablePagnet."`
				where idClient!=0 &&  verrouiller=1 && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30) && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY idPagnet DESC";
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$S_bonP = mysql_fetch_array($resS);
				$som_SortiesBon = $S_bonP[0] + $som_SortiesBon;
			}
		/**Fin SortiesBon */  
		/**Debut SortiesBon */ 
			$sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient!=0 AND verrouiller=1 AND (type=0 || type=30) ";
			$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
			$TotalB = mysql_fetch_array($res12) ;
		
			$sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0  ";
			$res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
			$TotalV = mysql_fetch_array($res13) ;
		
			$montantARecouvrir=$TotalB[0] - $TotalV[0]; 
		/**Fin SortiesBon */
	/**************************************** */

	/**************************************** */
		/** Debut montant stock actuel */
			if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
				$sql2="SELECT s.prixSession,s.prixPublic,s.quantiteStockCourant,s.idStock FROM `".$nomtableStock."` s
				LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
				WHERE d.classe=0 ORDER BY s.idStock DESC";
				$res2=mysql_query($sql2);
				$montantPA=0;
				$montantPU=0;
				while ($stock = mysql_fetch_array($res2)) {
					if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){
						$montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixSession']);
						$montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prixPublic']);
					}
				}
			}
			else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
				$sql2="SELECT s.quantiteStockCourant,s.nbreArticleUniteStock,i.prixuniteStock,d.prixuniteStock as ps,d.prixachat FROM `".$nomtableEntrepotStock."` s
				LEFT JOIN `".$nomtableStock."` i ON i.idStock = s.idStock
				LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
				WHERE d.classe=0 ORDER BY s.idEntrepotStock DESC";
				$res2=mysql_query($sql2);
				$montantPA=0;
				$montantPU=0;
				while ($stock = mysql_fetch_array($res2)) {
				if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){
					$quantite=$stock['quantiteStockCourant'] / $stock['nbreArticleUniteStock'];
				}
				else{
					$quantite=0;
				}
				$montantPA=$montantPA + ($quantite * $stock['prixachat']);
				$montantPU=$montantPU + ($quantite * $stock['ps']);
				}
			}
			else{
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
			}
		/** Fin montant stock actuel */
	/**************************************** */

	/**************************************** */
		/**Debut Entrees */
			$som_EntreesPU=0;
			$som_EntreesPA=0;
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
					}
					else{
						$sqlS="SELECT SUM(totalArticleStock)
						FROM `".$nomtableStock."`
						where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";
						$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
						$S_stock = mysql_fetch_array($resS);

						if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

							$sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stockE["idDesignation"]."' ";
							$resN=mysql_query($sqlN);
							$designation = mysql_fetch_array($resN);

							$som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));
							$som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));
						}
						else{
							$som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * $S_stock[0]);
							$som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * $S_stock[0]);
						}
					}
					$produitsE[] = $stockE['idDesignation'];
				}
			}
		/**Fin Entrees */
		/**Debut SortiesVente */
			$som_SortiesVente=0;
			$sqlSV="SELECT *
			FROM  `".$nomtableLigne."` l 
			INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			WHERE l.classe = 0  && p.idClient =0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";
			$resSV = mysql_query($sqlSV) or die ("persoonel requête 2".mysql_error());
			$resSV=mysql_query($sqlSV);
			$produitsSV=array();
			while($stockS=mysql_fetch_array($resSV)){
				if(in_array($stockS['idDesignation'], $produitsSV)){
					// echo "Existe.";
				}
				else{                    
					$sqlT="SELECT SUM(prixtotal)
					FROM `".$nomtableLigne."` l
					INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
					INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
					where p.idClient =0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockS["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'";
					$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
					$prixTotal = mysql_fetch_array($resT);
					$som_SortiesVente=$som_SortiesVente + $prixTotal[0];
					$produitsSV[] = $stockS['idDesignation'];
					}
				}
		/**Fin SortiesVente */
		/**Debut SortiesBon */
			$som_SortiesBon=0;
			$sqlSB="SELECT *
			FROM  `".$nomtableLigne."` l 
			INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
			WHERE l.classe = 0  && p.idClient !=0  && p.verrouiller=1 && (p.type=0 || p.type=30) && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC";
			$resSB = mysql_query($sqlSB) or die ("persoonel requête 2".mysql_error());
			$resSB=mysql_query($sqlSB);
			$produitsSB=array();
			while($stockSB=mysql_fetch_array($resSB)){
				if(in_array($stockSB['idDesignation'], $produitsSB)){
					// echo "Existe.";
				}
				else{                    
					$sqlT="SELECT SUM(prixtotal)
					FROM `".$nomtableLigne."` l
					INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
					INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
					where p.idClient !=0 &&  p.verrouiller=1 && (p.type=0 || p.type=30) && d.idDesignation='".$stockSB["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
					$resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());
					$prixTotal = mysql_fetch_array($resT);
					$som_SortiesBon=$som_SortiesBon + $prixTotal[0];
					$produitsSB[] = $stockSB['idDesignation'];
				}
			}
		/**Fin SortiesBon */
		/**Debut SortiesRetire */           
			$sql1="SELECT *from `".$nomtableInventaire."` i
			INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
			WHERE i.TYPE=1 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";
			$res1=mysql_query($sql1);
			$som_SortiesRetire=0;
			while ($inventaire = mysql_fetch_array($res1)) {
				if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
					if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
						if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){
							$som_SortiesRetire = $som_SortiesRetire + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prixPublic']);
						}
						else{
							$som_SortiesRetire = $som_SortiesRetire + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);  
						}  
					}
				}
			}  
		/**Fin SortiesRetire */
		/**Debut FournisseursBons */
			$som_Fournisseurs=0;
			$sqlB="SELECT SUM(montantBl) FROM `".$nomtableBl."` 
			WHERE idFournisseur!=0 &&  dateBl BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";
			$resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
			$totalFB = mysql_fetch_array($resB);
			$som_Fournisseurs=$som_Fournisseurs + $totalFB[0];
		/**Fin FournisseursBons */
		/**Debut Remises Client*/
			$remiseTotalClient=0;
			$sqlRm="SELECT DISTINCT p.idPagnet
				FROM `".$nomtablePagnet."` p
				INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
				WHERE (l.classe=0 || l.classe=1)  && p.idClient!=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";
			$resRm = mysql_query($sqlRm) or die ("persoonel requête 2".mysql_error());
			while ($pagnet = mysql_fetch_array($resRm)) {
				$sqlS="SELECT SUM(remise)
				FROM `".$nomtablePagnet."`
				where verrouiller=1  && idClient!=0 && (type=0 || type=30) && idPagnet='".$pagnet['idPagnet']."'  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$S_remises0 = mysql_fetch_array($resS);
				$remiseTotalClient = $S_remises0[0] + $remiseTotalClient;
			}
		/**Fin Remises Vente*/
		            /**Debut Benefice Vente*/
					if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
						$som_Benefice=0;
					}
					else{
						$sqlBN="SELECT *
						FROM `".$nomtableDesignation."` d
						INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
						INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
						WHERE l.classe = 0 && p.verrouiller=1 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' && p.type=0 ";
						$resBN = mysql_query($sqlBN) or die ("persoonel requête 2".mysql_error());
						$T_prixAchat_US = 0;
						$T_prixAchat_UN = 0;
						$T_prixAchat_DM = 0;
						$T_prixventes = 0;
						while ($pagnet = mysql_fetch_array($resBN)) {
							if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
								if ($pagnet['unitevente']==$pagnet['uniteStock']) {
									$T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite']);
								}
								else if ($pagnet['unitevente']=="Demi Gros") {
									$T_prixAchat_DM = $T_prixAchat_DM + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);
								}
								else{
									$T_prixAchat_UN = $T_prixAchat_UN + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);
									
								}
								$T_prixventes=$T_prixventes + $pagnet['prixtotal'];
							}
							else{
								if (($pagnet['unitevente']!="Article")&&($pagnet['unitevente']!="article")) {
									$T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite'] * $pagnet['nbreArticleUniteStock']);
								}
								else if(($pagnet['unitevente']=="Article")||($pagnet['unitevente']=="article")){
									$T_prixAchat_UN = $T_prixAchat_UN + ($pagnet['prixachat'] * $pagnet['quantite']);
								}
								$T_prixventes=$T_prixventes + $pagnet['prixtotal'];
							}
						}
						$T_prixAchat=$T_prixAchat_US + $T_prixAchat_UN + $T_prixAchat_DM;
						$som_Benefice=$T_prixventes - $T_prixAchat;
					}
				/**Fin Benefice Vente
	/**************************************** */

	/**************************************** */    
    
		$T_mutuelleVente = 0;

		$S_mutuelleVente = 0;

		$T_mutuelleB = 0;

		$S_mutuelleB = 0;

		if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {   

			$sqlMB="SELECT DISTINCT m.idMutuellePagnet

				FROM `".$nomtableMutuellePagnet."` m

				INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

				WHERE (l.classe!=6 && l.classe!=9) && m.verrouiller=1 && (m.type=0 || m.type=30) && CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY m.idMutuellePagnet DESC";

			$resMB = mysql_query($sqlMB) or die ("persoonel requête 2".mysql_error());

			while ($mutuelle = mysql_fetch_array($resMB)) {

				$sqlS="SELECT SUM(apayerMutuelle)

				FROM `".$nomtableMutuellePagnet."`

				where  verrouiller=1 && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";

				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

				$S_mutuelleB = mysql_fetch_array($resS);

				$T_mutuelleB = $S_mutuelleB[0] + $T_mutuelleB;

			}

			$sqlMV="SELECT DISTINCT m.idMutuellePagnet

				FROM `".$nomtableMutuellePagnet."` m

				INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

				WHERE (l.classe!=6 && l.classe!=9) && m.verrouiller=1 && (m.type=0 || m.type=30) && CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY m.idMutuellePagnet DESC";

			$resMV = mysql_query($sqlMV) or die ("persoonel requête 2".mysql_error());

			while ($mutuelle = mysql_fetch_array($resMV)) {

				$sqlS="SELECT SUM(apayerPagnet)

				FROM `".$nomtableMutuellePagnet."`

				where  verrouiller=1 && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";

				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

				$S_mutuelleVente = mysql_fetch_array($resS);

				$T_mutuelleVente = $S_mutuelleVente[0] + $T_mutuelleVente;

			}
			
			// $montantARecouvrir = $montantARecouvrir + $T_mutuelleB;

		}

	/**************************************** */
	

            /**Debut approvisionnement caisse */
                
			$sqlApp="SELECT *

			FROM `".$nomtableDesignation."` d

			INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

			INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

			WHERE l.classe = 5 && p.idClient=0 && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') ) ORDER BY p.idPagnet DESC  ";

			$resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());
			
			$T_App = 0;

			// $S_App2 = 20;

			while ($pagnet = mysql_fetch_array($resApp)) {

				$T_App=$T_App + $pagnet['prixtotal'];
				// $S_App2=15;

			}
		/**Fin approvisionnement caisse */
		
            /**Debut retrait caisse */
                
			$sqlRetraitC="SELECT *

			FROM `".$nomtableDesignation."` d

			INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

			INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

			WHERE l.classe = 7 && p.idClient=0 && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') ) ORDER BY p.idPagnet DESC  ";

			$resRetraitC = mysql_query($sqlRetraitC) or die ("persoonel requête 2".mysql_error());
			
			$T_RetraitCaisse = 0;

			// $S_App2 = 20;

			while ($pagnet = mysql_fetch_array($resRetraitC)) {

				$T_RetraitCaisse=$T_RetraitCaisse + $pagnet['prixtotal'];
				// $S_App2=15;

			}
		/**Fin retrait caisse */


	$totalEntrees= $som_Clients + $som_SortiesVente + $som_Services + $T_App;
	$totalSorties= $som_Depenses + $som_FournisseursV + $som_ClientsBE + $remiseTotalVente + $T_RetraitCaisse;
	$restantCaisse= $totalEntrees - $totalSorties;
	$patrimoine = $montantPU + $montantARecouvrir + $restantCaisse + $T_mutuelleB;
	$totalFournisseur=$som_Fournisseurs - $som_FournisseursV;

	//Create new pdf file
	$pdf=new FPDF();

	//Disable automatic page break
	$pdf->SetAutoPageBreak(false);

	//Add first page
	$pdf->AddPage();

	//set initial y axis position per page
	$y_axis_initial = 50;

	//Set Row Height
	$row_height = 7;

	$y_axis=50;

	//Set maximum rows per page
	$max = 35;
	$i = 0;
	$p=1;

	// $nb_page=ceil($nbre[0]/$max);
	$image='';
	if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
		$image='pharmacie.png';
	}
	else{
		$image='shop.png';
	}
	$pdf-> SetFont("Arial","B",10);
	$pdf-> Image('images/'.$image,5,5,18,18);

	$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 );

	

	$champ_date = date_create($dateString2);
	$annee = date_format($champ_date, 'Y');
	$num_fact = "Rapport global de la caisse" ;
	$num_fact2 = "du " . $dateD. " au ". $dateF ;
	$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(30, 38, 150, 10, "DF");
	$pdf->SetY(38 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 0, 5, utf8_decode($num_fact), 0, 0, 'C');
	$pdf->Ln(); $pdf->SetFont( "Arial", "I", 12 ); $pdf->SetTextColor(100,100,100); $pdf->Cell( 0, 5, utf8_decode($num_fact2), 0, 0, 'C');

	// observations
	// $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetY(9) ; $pdf->Cell(0, 0, $_SESSION["labelB"].'dhudhwjdncji nhhcuhcuhc bwhbbjhuw dbwgbjuh', 0,0, "C");
	$pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 25, 12 ); $pdf->SetTextColor(0,0,0); $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, utf8_decode(html_entity_decode($_SESSION["labelB"])), 0, "L");
	// $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 14 ) ; $pdf->Cell(22, 6, $_SESSION["adresseB"], 0, "L");
	// $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 20, 17 ) ; $pdf->Cell(22, 6, $_SESSION["telBoutique"], 0, "L");
	// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
	$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
	$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, utf8_decode('Téléphone : '.$_SESSION["telBoutique"]), 0, "L");

	// if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
	//print column titles
	$pdf->SetFillColor(100,270,100);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis_initial);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'ENTREES EN ARGENT',1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_Clients+$som_SortiesVente+$T_mutuelleVente+$som_Services+$T_App, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Approvisionnement caisse',1,0,'L',1);
	$pdf->Cell(60,8,number_format($T_App, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Versements clients',1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_Clients, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Ventes',1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_SortiesVente, 0, ',', ' ').' FCFA',1,0,'L',1);

	if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {   

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Ventes Imputation',1,0,'L',1);
	$pdf->Cell(60,8,number_format($T_mutuelleVente, 0, ',', ' ').' FCFA',1,0,'L',1);

	}

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Services',1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_Services, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Produits retournés'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_SortiesRetournes, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(240,150,150);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'SORTIES EN ARGENT',1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_Depenses+$som_FournisseursV+$som_ClientsBE+$remiseTotalVente, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Retrait caisse',1,0,'L',1);
	$pdf->Cell(60,8,number_format($T_RetraitCaisse, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(246,220,220);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Dépenses'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_Depenses, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(246,220,220);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Versements fournisseurs',1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_FournisseursV, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(246,220,220);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Bon en espèces'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_ClientsBE, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(246,220,220);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Remises Ventes'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($remiseTotalVente, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(239,246,41);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'RESTANT CAISSE',1,0,'L',1);
	$pdf->Cell(60,8,number_format($restantCaisse, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + (2 * $row_height);

	$pdf->SetFillColor(100,270,100);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'MONTANT A RECOUVRIR',1,0,'L',1);
	$pdf->Cell(60,8,number_format($montantARecouvrir + $T_mutuelleB, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Bons (en produits + en epèces) non payés'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($montantARecouvrir, 0, ',', ' ').' FCFA',1,0,'L',1);

	$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Bons mutuelles'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($T_mutuelleB, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(100,270,100);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'VALEUR STOCK COURANT',1,0,'L',1);
	$pdf->Cell(60,8,'',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Valeur stock (PA)',1,0,'L',1);
	$pdf->Cell(60,8,number_format($montantPA, 0, ',', ' ').' FCFA',1,0,'L',1);

	$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Valeur stock (PU)',1,0,'L',1);
	$pdf->Cell(60,8,number_format($montantPU, 0, ',', ' ').' FCFA',1,0,'L',1);
	
	$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(100,270,100);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'PATRIMOINE DE L\'ENTREPRISE',1,0,'L',1);
	$pdf->Cell(60,8,number_format($patrimoine, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Valeur stock (PU)',1,0,'L',1);
	$pdf->Cell(60,8,number_format($montantPU, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(205,247,201);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Montant à recouvrir'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($montantARecouvrir + $T_mutuelleB, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(244,249,95);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,'Restant caisse',1,0,'L',1);
	$pdf->Cell(60,8,number_format($restantCaisse, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(246,220,220);
	$pdf->SetFont('Arial','',9);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('Bon de livraison (BL) non payés'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($totalFournisseur, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + (2 * $row_height);

	$pdf->SetFillColor(240,150,150);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($y_axis);
	$pdf->SetX(20);
	$pdf->Cell(110,8,utf8_decode('PRODUITS RETIRES'),1,0,'L',1);
	$pdf->Cell(60,8,number_format($som_SortiesRetire, 0, ',', ' ').' FCFA',1,0,'L',1);

		$y_axis = $y_axis + $row_height;

	$pdf->SetFillColor(240,150,150);
	$pdf->SetFont('Arial','B',10);
		$pdf->SetY($y_axis);
		$pdf->SetX(20);
		$pdf->Cell(110,8,utf8_decode('REMISES CLIENTS'),1,0,'L',1);
		$pdf->Cell(60,8,number_format($remiseTotalClient, 0, ',', ' ').' FCFA',1,0,'L',1);

	$y_axis = $y_axis + (2 * $row_height);

	$pdf->SetFillColor(205,247,300);
	$pdf->SetFont('Arial','B',10);
		$pdf->SetY($y_axis);
		$pdf->SetX(20);
		$pdf->Cell(110,8,utf8_decode('BENEFICE'),1,0,'L',1);
		$pdf->Cell(60,8,number_format($som_Benefice, 0, ',', ' ').' FCFA',1,0,'L',1);

	$y_axis = $y_axis + (5 + $row_height);

	$sqlU="select * from `aaa-utilisateur` where idutilisateur='".$_SESSION['iduser']."' ";
	$resU=mysql_query($sqlU);
	$user=mysql_fetch_array($resU);
	$pdf->SetFont( "Arial", "B", 8 ); 
	$pdf->SetXY( 8, $y_axis) ; 
	$pdf->MultiCell(75, 4, 'Fait par : '.$user['prenom'].' '.strtoupper($user['nom']), 0, "L");



	$pdf->SetY(-15);
	$pdf->SetFont('Arial','I',9);
	$pdf->SetTextColor(128);
	$pdf->Cell(0,10,iconv("UTF-8", "ISO-8859-1","© JCaisse "),0,0,'C');
	$pdf->SetY(-15);
	$pdf->SetFont('Arial','I',9);
	$pdf->SetTextColor(128);
	$pdf->Cell(0,20,iconv("UTF-8", "ISO-8859-1","Contact : 77 477 98 98 "),0,0,'C');
	


		$pdf->Output("I", '1');

}

?>
