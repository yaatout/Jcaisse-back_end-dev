<?php

date_default_timezone_set('Africa/Dakar');


/**Debut Button Terminer Pagnet**/
if (isset($_POST['btnImprimerFacture'])) {
	$idPanierV = @$_POST['idPanier'];

	$getPanierV = $bddV->prepare("SELECT * FROM panier WHERE idPanier = :idP");
	$getPanierV->execute(array(
		'idP' =>$idPanierV
		)) or die(print_r($getPanierV->errorInfo()));
	$panierV=$getPanierV->fetch();
	$totalp = $panierV['total'];
	
	$getLignesV = $bddV->prepare("SELECT * FROM ligne WHERE idPanier = :idP and barrer = 0");
	$getLignesV->execute(array(
		'idP' =>$idPanierV
		)) or die(print_r($getLignesV->errorInfo()));
	$lignesV=$getLignesV->fetchAll();
	
	$date = date("Y-m-d");
	$heure = date("H:i:s");

	$insertNewPanier = "INSERT INTO `".$nomtablePagnet."`(datepagej, type, heurePagnet, totalp, apayerPagnet, verrouiller, iduser, idVitrine) values (".$date.",11,".$heure.",".$totalp.",".$totalp.",1,".$_SESSION['iduser'].",".$idPanierV.")";
	$result1 = @mysql_query($insertNewPanier) or die ("insertion new panier impossible".mysql_error()) ;

	foreach ($lignesV as $key) {
		$getArticle = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE designation = :designation");
		$getArticle->execute(array(
			'designation' =>$key['designation']
			)) or die(print_r($getArticle->errorInfo()));
		$article=$getArticle->fetch();

		$insertNewLignes = "INSERT INTO `".$nomtableLigne."`(idPagnet,idDesignation, designation, unitevente, prixunitevente, quantite, prixtotal) values (".$idPagnet.",".$article['idDesignation'].",'".$key['designation']."','".$key['unite']."',".$key['prix'].",".$key['quantite'].",".$key['prixtotal'].")";
		$result2 = @mysql_query($insertNewLignes) or die ("insertion new ligne impossible".mysql_error()) ;
			
		if (($key['unite']!="Article")&&($key['unite']!="article")) {
			$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$article['idDesignation']."' ORDER BY idStock ASC ";
			$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
			$restant=$key['quantite']*$article['nbreArticleUniteStock'];
			while ($stock = mysql_fetch_assoc($resD)) {
				if($restant>= 0){
					$quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
					if($quantiteStockCourant > 0){
						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
					}
					else{
						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
					}
					$restant= $restant - $stock['quantiteStockCourant'] ;
				}
			}

		}
		else if(($key['unite']=="Article")||($key['unite']=="article")){
			$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$article['idDesignation']."' ORDER BY idStock ASC ";
			$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
			$restant=$key['quantite'];
			while ($stock = mysql_fetch_assoc($resD)) {
				if($restant>= 0){
					$quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
					if($quantiteStockCourant > 0){
						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
					}
					else{
						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
					}
					$restant= $restant - $stock['quantiteStockCourant'] ;
				}
			}

		}

	}

	// $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
	// $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
	// $pagnet = mysql_fetch_assoc($res) ;

	// $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
	// $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
	//$ligne = mysql_fetch_assoc($resL) ;

	if($pagnet['type']==0){
		while ($ligne=mysql_fetch_assoc($resL)){
			if($ligne['classe']==0){
				$sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$designation = mysql_fetch_assoc($resS) ;
				if(mysql_num_rows($resS)){
					if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
						$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
						$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
						$restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];
						while ($stock = mysql_fetch_assoc($resD)) {
							if($restant>= 0){
								$quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
								if($quantiteStockCourant > 0){
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								else{
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								$restant= $restant - $stock['quantiteStockCourant'] ;
							}
						}

					}
					else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
						$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";
						$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
						$restant=$ligne['quantite'];
						while ($stock = mysql_fetch_assoc($resD)) {
							if($restant>= 0){
								$quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
								if($quantiteStockCourant > 0){
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								else{
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								$restant= $restant - $stock['quantiteStockCourant'] ;
							}
						}

					}
				}
			}
		}
	}
	else{
		while ($ligne=mysql_fetch_assoc($resL)){
			if($ligne['classe']==0){
				$sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
				$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
				$designation = mysql_fetch_assoc($resS) ;
				if(mysql_num_rows($resS)){
					if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
						$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
						$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
						$retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];
						while ($stock = mysql_fetch_assoc($resD)) {
							if($retour>= 0){
								$quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
								if($stock['totalArticleStock'] >= $quantiteStockCourant){
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								else{
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								$retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
							}
						}

					}
					else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
						$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
						$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
						$retour=$ligne['quantite'];
						while ($stock = mysql_fetch_assoc($resD)) {
							if($retour >= 0){
								$quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
								if($stock['totalArticleStock'] >= $quantiteStockCourant){
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
								}
								else{
									$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
									$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
									
								}
								$retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
								
							}
							
						}

					}
					
				}
			}
		}
	}

	
	$sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
	$resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
	$TotalP = mysql_fetch_array($resT) ;

	$totalp=$TotalP[0];
	
	$sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1',totalp=".$totalp.",apayerPagnet=".$apayerPagnet." where idPagnet=".$idPagnet;
	$res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
}
/**Fin Button Terminer Pagnet**/