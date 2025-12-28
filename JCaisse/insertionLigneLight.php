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
require('connectionPDO.php');







require('declarationVariables.php');




/**Debut informations sur la date d'Aujourdhui **/
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateHeures=$dateString.' '.$heureString;
/**Fin informations sur la date d'Aujourdhui **/

if($mois==1){
    $annee_paie=$annee - 1;
}
else{
    $annee_paie=$annee;
}

$_SESSION['page']='vente';

        //deplacement des paniers vendus dans la table tampon vers la table ligne et panier

        // if ($_SESSION['tampon'] == 1 && ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1)) {
		// 	// var_dump($_SESSION['nomB']);

			
		// 	$sql="SELECT * FROM `".$nomtablePagnetTampon."` where synchronise=0 and verrouiller=1";
				
		// 	// var_dump($sql);
		// 	$statement = $bdd->prepare($sql);
		// 	$statement->execute();
		// 	$tamponsP = $statement->fetchAll(PDO::FETCH_ASSOC); 

		// 	$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		// 	try {
		// 		// From this point and until the transaction is being committed every change to the database can be reverted
		// 		$bdd->beginTransaction();  /**** generate barcode ****/

		// 		foreach ($tamponsP as $tp) {
		// 			// var_dump($tp);

		// 			$sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet = ".$tp['idPagnet'];
				
		// 	        // var_dump($sqlL);
		// 			$statementL = $bdd->prepare($sqlL);
		// 			$statementL->execute();
		// 			$tamponsL = $statementL->fetchAll(PDO::FETCH_ASSOC); 

		// 			$req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,remise,restourne,versement,idCompte,avance,idCompteAvance,tampon)
		// 			values (:d,:h,:u,:t,:tp,:ap,:v,:c,:r,:res,:ver,:idC,:av,:idcav,:tam)");
		// 		   // var_dump($req4);
		// 			$req4->execute(array(
		// 				'd' => $tp['datepagej'],
		// 				'h' => $tp['heurePagnet'],
		// 				'u' => $tp['iduser'],
		// 				't' => $tp['type'],
		// 				'tp' => $tp['totalp'],
		// 				'ap' => $tp['apayerPagnet'],
		// 				'v' => 1,
		// 				'c' => $tp['idClient'],
		// 				'r' => $tp['remise'],
		// 				'res' => $tp['restourne'],
		// 				'ver' => $tp['versement'],
		// 				'idC' => $tp['idCompte'],
		// 				'av' => $tp['avance'],
		// 				'idcav' => $tp['idCompteAvance'],
		// 				'tam' => 1
		// 			))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
		// 			$req4->closeCursor();
		// 			$idPagnet = $bdd->lastInsertId();
		// 			// var_dump($idPagnet);

		// 			foreach ($tamponsL as $tl) {
		// 				// var_dump($tl);
		// 				$preparedStatement = $bdd->prepare(
		// 					"insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe)
		// 					 values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
		// 				);
		// 	            // var_dump($preparedStatement);
						
		// 				$preparedStatement->execute([
		// 					'd' => $tl['designation'],
		// 					'idd' => $tl['idDesignation'],
		// 					'uv' => $tl['unitevente'],
		// 					'pu' => $tl['prixunitevente'],
		// 					'qty' => $tl['quantite'],
		// 					'p' => $tl['prixtotal'],
		// 					'idp' => $idPagnet,
		// 					'c' => $tl['classe']
		// 				]);
		// 			}
					
		// 			$sqlU="UPDATE `".$nomtablePagnetTampon."` SET synchronise=1 where idPagnet=".$tp['idPagnet'];
					
		// 			$statementU = $bdd->prepare($sqlU);
		// 			$statementU->execute();
		// 		}

		// 		// Make the changes to the database permanent
		// 		$bdd->commit();
		// 		// header("Refresh:0");
		// 	}
		// 	catch ( PDOException $e ) { 
		// 		// Failed to insert the order into the database so we rollback any changes
		// 		$bdd->rollback();
		// 		throw $e;

		// 		// echo '0';
		// 	}
    
		// }	
		
        //deplacement des paniers vendus dans le compte du client vers la table tampon
        // if ($_SESSION['tampon'] == 1 && ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1)) {
		// 	// var_dump($_SESSION['nomB']);

		// 	$sql="SELECT * FROM `".$nomtablePagnet."` where datepagej='".$dateString2."' and tampon=0 and verrouiller=1";
				
		// 	// var_dump($sql);
		// 	$statement = $bdd->prepare($sql);
		// 	$statement->execute();
		// 	$normalsP = $statement->fetchAll(PDO::FETCH_ASSOC); 
		// 	// var_dump($normalsP);
		// 	$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		// 	try {
		// 		// From this point and until the transaction is being committed every change to the database can be reverted
		// 		$bdd->beginTransaction();  /**** generate barcode ****/

		// 		foreach ($normalsP as $np) {
		// 			// var_dump($np);

		// 			$sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet = ".$np['idPagnet'];
				
		// 		// var_dump($sqlL);
		// 			$statementL = $bdd->prepare($sqlL);
		// 			$statementL->execute();
		// 			$normalsL = $statementL->fetchAll(PDO::FETCH_ASSOC); 
		// 			$synchronise=1;

		// 			// if ($np['verrouiller']==1) {
		// 			// 	$synchronise=1;
		// 			// } 
					

		// 			$req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnetTampon."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,remise,restourne,versement,idCompte,avance,idCompteAvance,synchronise)
		// 			values (:d,:h,:u,:t,:tp,:ap,:v,:c,:r,:res,:ver,:idC,:av,:idcav,:syn)");
		// 		// var_dump($req4);
		// 			$req4->execute(array(
		// 				'd' => $np['datepagej'],
		// 				'h' => $np['heurePagnet'],
		// 				'u' => $np['iduser'],
		// 				't' => $np['type'],
		// 				'tp' => $np['totalp'],
		// 				'ap' => $np['apayerPagnet'],
		// 				'v' => $np['verrouiller'],
		// 				'c' => $np['idClient'],
		// 				'r' => $np['remise'],
		// 				'res' => $np['restourne'],
		// 				'ver' => $np['versement'],
		// 				'idC' => $np['idCompte'],
		// 				'av' => $np['avance'],
		// 				'idcav' => $np['idCompteAvance'],
		// 				'syn' => $synchronise
		// 			))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
		// 			$req4->closeCursor();
		// 			$idPagnet = $bdd->lastInsertId();
		// 			// var_dump($idPagnet);

		// 			foreach ($normalsL as $nl) {
		// 				// var_dump($nl);
		// 				$preparedStatement = $bdd->prepare(
		// 					"insert into `".$nomtableLigneTampon."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe)
		// 					 values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
		// 				);
		// 		// var_dump($preparedStatement);
						
		// 				$preparedStatement->execute([
		// 					'd' => $nl['designation'],
		// 					'idd' => $nl['idDesignation'],
		// 					'uv' => $nl['unitevente'],
		// 					'pu' => $nl['prixunitevente'],
		// 					'qty' => $nl['quantite'],
		// 					'p' => $nl['prixtotal'],
		// 					'idp' => $idPagnet,
		// 					'c' => $nl['classe']
		// 				]);
		// 			}

		// 			// if ($np['verrouiller']==0) {
							
		// 			// 	$sqlD = "DELETE FROM `".$nomtablePagnet."` WHERE idPagnet=?";
		// 			// 	$stmt= $bdd->prepare($sqlD);
		// 			// 	$stmt->execute([$np['idPagnet']]);
		// 			// }
                    
		// 			$sqlU="UPDATE `".$nomtablePagnet."` SET tampon=1 where idPagnet=".$np['idPagnet'];
					
		// 			$statementU = $bdd->prepare($sqlU);
		// 			$statementU->execute();
		// 		}

		// 		// Make the changes to the database permanent
		// 		$bdd->commit();
		// 		// header("Refresh:0");
		// 	}
		// 	catch ( PDOException $e ) { 
		// 		// Failed to insert the order into the database so we rollback any changes
		// 		$bdd->rollback();
		// 		throw $e;

		// 		// echo '0';
		// 	}
    
    
		// }




        /**********************/



$numligne         =@$_POST["numligne"];



$type             =@htmlentities($_POST["type"]);



$designation      =@htmlentities($_POST["designation"]);



$unitevente		  =@$_POST["unitevente"];



$prix             =@$_POST["prix"];



$quantite         =@$_POST["quantite"];



$remise           =@$_POST["remise"];



$prixtotal        =@$_POST["prixt"];







$aliasTrans          =@$_POST["aliasTrans"];



$typeTrans          =@$_POST["typeTrans"];



$montantTrans        =@$_POST["montantTrans"];



$pagnetTrans        =@$_POST["pagnetTrans"];



$idFact          =@$_POST["idFact"];







$modifier         =@$_POST["modifier"];



$annuler          =@$_POST["annuler"];



/***************/







$numligne2       =@$_GET["numligne"];







if ($_SESSION['compte']==1) {



    $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte<>3 ORDER BY idCompte";



    $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());



    // var_dump($cpt_array);



    $cpt_array = [];



    while ($cpt = mysql_fetch_array($resPay)) {



        # code...



        $cpt_array[] = $cpt;  // var_dump($key);



    }



}







/**********************/







if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {







 require('insertionLigneLight-pharmacie.php');







} 



else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {







    require('insertionLigneLight-entrepot.php');



   



}



else{







if (isset($_POST['device'])) {



    



}











/**Debut Button Ajouter Transfert**/



    if (isset($_POST['btnSavePagnetTransfert'])) {



        $paieMois=$annee-$mois;



        $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee_paie."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";



        $res0=mysql_query($sql0);



        if(mysql_num_rows($res0)){



            if($jour > 4){



                $paie=mysql_fetch_array($res0);



                $periode_paie=explode("-", $paie['datePS']);



                $mois_periode=$periode_paie[1]; $annee_periode=$periode_paie[0];



                $periode=calendrierMois($mois_periode);



                $msg_paiement="<b><span style='color:red;'>VOUS NE POUVEZ PAS CREER UN PANIER.</span></b></br></br>



                <span style='color:green;'>VEUILLEZ CONTACTER LE PROPRIETAIRE POUR VERIFIER SI LE PAIEMENT DU MOIS DE </span><span style='color:red;'>".$periode." ".$annee_periode."</span><span style='color:green;'> DE LA PLATEFORME JCAISSE EST EFFECTUE.</span>";



            }



            else{



                $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",3,0,0,0,0,0,0,0)";



                $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());



                if($res6){



                    $reqA="SELECT * from `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && type=3 && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0 order by idPagnet desc limit 1";



                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());



                    $pagnet = mysql_fetch_assoc($resA) ;



                    



                    $idpagnet_trans=$pagnet['idPagnet'];



                    $msg_transfert="OK";



                }



            }



        }



        else{



            $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",3,0,0,0,0,0,0,0)";



            $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error());



            if($res6){



                $reqA="SELECT * from `".$nomtablePagnet."` where iduser='".$_SESSION['iduser']."' && type=3 && idClient=0 && datepagej ='".$dateString2."' && verrouiller=0 order by idPagnet desc limit 1";



                $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());



                $pagnet = mysql_fetch_assoc($resA) ;



                



                $idpagnet_trans=$pagnet['idPagnet'];



                $msg_transfert="OK";



            }



        }



    }



/**Fin Button Ajouter Transfert**/







/**Debut Button Ajouter Ligne dans un pagnet**/



    if (isset($_POST['btnEnregistrerCodeBarre'])) {







        $resultat=" =ko"+htmlspecialchars($_POST['codeBarre']);







            if (isset($_POST['codeBarre']) && isset($_POST['idPagnet'])) {



                $codeBarre=htmlspecialchars(trim($_POST['codeBarre']));



                $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));



                $codeBrute=explode('-', $codeBarre);







                $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";



                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                $pagnet = mysql_fetch_assoc($res) ;







                if(!empty($codeBrute[1]) && is_int($codeBrute[0])){



                    $classe=$codeBrute[1];



                    /**Debut de la Vente des Produits sur le Stock */



                    if($classe==1 || $classe==2){



                        $idStock=$codeBrute[0];



                        $sql0="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock;



                        $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());



                        if(mysql_num_rows($res0)){



                            $stock = mysql_fetch_assoc($res0) or die ("select stock impossible =>".mysql_error());



                            $idDesignation=$stock["idDesignation"];







                            $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' and classe=0 ";



                            $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());



                            if(mysql_num_rows($res6)){



                                $totalp=0;



                                $tailleTableau=count($codeBrute);



                                $quantiteStockCourant=$stock['quantiteStockCourant'];



                                $uniteStock=$stock['uniteStock'];







                                if($pagnet['type']==0){



                                    if ($quantiteStockCourant>0) {



                                        // insertion dans l'historique



                                        $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;



                                        $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());



                                        $design = mysql_fetch_assoc($res6);



                                        if ($tailleTableau==2) {



                                                $numero=$codeBrute[1];



                                                    if (($numero==1)&&($stock['uniteStock']!="Article")&&($stock['uniteStock']!="article")) { // PAquet, douzaine, ....



                                                        $quantiteCourant=$quantiteStockCourant-$design['nbreArticleUniteStock'];



                                                        if ($quantiteCourant>=0){



                                                            //Insertion lign



                                                            /***Debut verifier si la ligne du produit existe deja ***/



                                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                            $ligne1 = mysql_fetch_assoc($res);



                                                            /***Debut verifier si la ligne du produit existe deja ***/



                                                            if($ligne1 != null){



                                                                /***Debut verifier si le produit existe deja ***/



                                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."'  and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                                $ligne2 = mysql_fetch_assoc($res);



                                                                /***Debut verifier si le produit existe deja ***/



                                                                $existant=0;



                                                                if($ligne2 != null){



                                                                    $existant=$ligne2['quantite'];



                                                                }



                                                                $quantite = $ligne1['quantite'] + 1;



                                                                $restant = $quantiteStockCourant-($design['nbreArticleUniteStock'] * $quantite) - $existant;



                                                                if ($restant>=0){



                                                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                                                }



                                                                else{



                                                                    $msg_info="<p>IMPOSSIBLE.</br>



                                                                    </br> La quantité de ce stock est insuffisant pour la ligne.



                                                                    </br> Il vous reste  <span>".$quantiteStockCourant."</span> Unités dans le Stock.



                                                                    </p>";



                                                                }



                                                            }



                                                            else {



                                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                                $ligne = mysql_fetch_assoc($res) ;



                                                                if(mysql_num_rows($res)){



                                                                    if($ligne['classe']==0 || $ligne['classe']==1){



                                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','".$stock['uniteStock']."',".$stock['prixuniteStock'].",1,".$stock['prixuniteStock'].",".$idPagnet.",0)";



                                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 



                                                                    }



                                                                }



                                                                else{



                                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','".$stock['uniteStock']."',".$stock['prixuniteStock'].",1,".$stock['prixuniteStock'].",".$idPagnet.",0)";



                                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                }



                                                            



                                                            }







                                                        }



                                                    }



                                                    else if (($numero==1)&&(($stock['uniteStock']=="Article")||($stock['uniteStock']=="article"))) {



                                                            // Article



                                                            /***Debut verifier si la ligne du produit existe deja ***/



                                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                            $ligne1 = mysql_fetch_assoc($res);



                                                            /***Debut verifier si la ligne du produit existe deja ***/



                                                            if($ligne1 != null){



                                                                /***Debut verifier si le produit existe deja ***/



                                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                                $ligne2 = mysql_fetch_assoc($res);



                                                                /***Debut verifier si le produit existe deja ***/



                                                                $existant=0;



                                                                if($ligne2 != null){



                                                                    $existant=$ligne2['quantite']*$design['nbreArticleUniteStock'];



                                                                }



                                                                $quantite = $ligne1['quantite'] + 1;



                                                                $restant = $quantiteStockCourant- $quantite - $existant;



                                                                if ($restant>=0){



                                                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                                                }



                                                                else{



                                                                    $msg_info="<p>IMPOSSIBLE.</br>



                                                                    </br> La quantité de ce stock est insuffisant pour la ligne.



                                                                    </br> Il vous reste  <span>".$quantiteStockCourant."</span> Unités dans le Stock.



                                                                    </p>";



                                                                }



                                                                



                                                            }



                                                            else {



                                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                                $ligne = mysql_fetch_assoc($res) ;



                                                                if(mysql_num_rows($res)){



                                                                    if($ligne['classe']==0 || $ligne['classe']==1){



                                                                        $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                    }



                                                                }



                                                                else{



                                                                    $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                }



                                                                



                                                            }







                                                    }



                                                    else if ($numero==2){



                                                            /***Debut verifier si la ligne du produit existe deja ***/



                                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                            $ligne1 = mysql_fetch_assoc($res);



                                                            /***Debut verifier si la ligne du produit existe deja ***/



                                                            if($ligne1 != null){



                                                                /***Debut verifier si le produit existe deja ***/



                                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                                $ligne2 = mysql_fetch_assoc($res);



                                                                /***Debut verifier si le produit existe deja ***/



                                                                $existant=0;



                                                                if($ligne2 != null){



                                                                    $existant=$ligne2['quantite']*$design['nbreArticleUniteStock'];



                                                                }



                                                                $quantite = $ligne1['quantite'] + 1;



                                                                $restant = $quantiteStockCourant- $quantite - $existant;



                                                                if ($restant>=0){



                                                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                                                }



                                                                else{



                                                                    $msg_info="<p>IMPOSSIBLE.</br>



                                                                    </br> La quantité de ce stock est insuffisant pour la ligne.



                                                                    </br> Il vous reste  <span>".$quantiteStockCourant."</span> Unités dans le Stock.



                                                                    </p>";



                                                                }



                                                                



                                                            }



                                                            else {



                                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                                $ligne = mysql_fetch_assoc($res) ;



                                                                if(mysql_num_rows($res)){



                                                                    if($ligne['classe']==0 || $ligne['classe']==1){



                                                                        $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                    }



                                                                }



                                                                else{



                                                                    $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                }



                                                                



                                                            }







                                                }



                                        }







                                    }



                                    else{



                                        $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>



                                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";



                                    }



                                }



                                else{



                                    $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;



                                    $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());



                                    $design = mysql_fetch_assoc($res6);



                                    if ($tailleTableau==2) {



                                            $numero=$codeBrute[1];



                                                if (($numero==1)&&($stock['uniteStock']!="Article")&&($stock['uniteStock']!="article")) { // PAquet, douzaine, ....



                                                    /***Debut verifier si la ligne du produit existe deja ***/



                                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                    $ligne1 = mysql_fetch_assoc($res);



                                                    /***Debut verifier si la ligne du produit existe deja ***/



                                                    if($ligne1 != null){



                                                        $quantite = $ligne1['quantite'] + 1;



                                                        $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                        $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                                    }



                                                    else {



                                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                        $ligne = mysql_fetch_assoc($res) ;



                                                        if(mysql_num_rows($res)){



                                                            if($ligne['classe']==0){



                                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','".$stock['uniteStock']."',".$stock['prixuniteStock'].",1,".$stock['prixuniteStock'].",".$idPagnet.",0)";



                                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 



                                                            }



                                                        }



                                                        else{



                                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation,unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','".$stock['uniteStock']."',".$stock['prixuniteStock'].",1,".$stock['prixuniteStock'].",".$idPagnet.",0)";



                                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                        }



                                                    



                                                    }



                                                }



                                                else if (($numero==1)&&(($stock['uniteStock']=="Article")||($stock['uniteStock']=="article"))) {



                                                        /***Debut verifier si la ligne du produit existe deja ***/



                                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                        $ligne1 = mysql_fetch_assoc($res);



                                                        /***Debut verifier si la ligne du produit existe deja ***/



                                                        if($ligne1 != null){



                                                            $quantite = $ligne1['quantite'] + 1;



                                                            $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                                            



                                                        }



                                                        else {



                                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                            $ligne = mysql_fetch_assoc($res) ;



                                                            if(mysql_num_rows($res)){



                                                                if($ligne['classe']==0){



                                                                    $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                }



                                                            }



                                                            else{



                                                                $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                            }



                                                            



                                                        }



                                



                                                }



                                                else if ($numero==2){



                                                        /***Debut verifier si la ligne du produit existe deja ***/



                                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                        $ligne1 = mysql_fetch_assoc($res);



                                                        /***Debut verifier si la ligne du produit existe deja ***/



                                                        if($ligne1 != null){



                                                            $quantite = $ligne1['quantite'] + 1;



                                                            $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and ( unitevente='Article' or  unitevente='article') and classe=0 ";



                                                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                                            



                                                        }



                                                        else {



                                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                            $ligne = mysql_fetch_assoc($res) ;



                                                            if(mysql_num_rows($res)){



                                                                if($ligne['classe']==0){



                                                                    $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                                }



                                                            }



                                                            else{



                                                                $sql7="insert into `".$nomtableLigne."`(designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$idStock.",'".$design['idDesignation']."','article',".$stock['prixunitaire'].",1,".$stock['prixunitaire'].",".$idPagnet.",0)";



                                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                            }



                                                            



                                                        }



                                



                                            }



                                    }



                                }







                            }else{



                                $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>



                                    VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...";



                            }







                        }else{



                            $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>



                                    VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ...";



                        }



                    }



                    /**Fin de la Vente des Produits sur le Stock*/



                    /**Debut de la Vente des Services, Depenses  */



                    if($classe==3 && $pagnet['type']==0){



                        $idDesignation=$codeBrute[0];



                        $sql0="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' and  classe=1 ";



                        $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());



                        if(mysql_num_rows($res0)){



                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                            $ligne = mysql_fetch_assoc($res) ;



                            if(mysql_num_rows($res)){



                                /***Debut verifier si la ligne du produit existe deja ***/



                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$idDesignation."' and classe=1  ";



                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                $ligne1 = mysql_fetch_assoc($res);



                                /***Debut verifier si la ligne du produit existe deja ***/



                                if($ligne1 != null){



                                    $quantite = $ligne1['quantite'] + 1;



                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$idDesignation."' and classe=1  ";



                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                }



                                else {



                                    if($ligne['classe']==1 || $ligne['classe']==0){



                                        $design = mysql_fetch_assoc($res0);



                                        if($design['uniteStock']!='Transaction'){



                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,".$idDesignation.",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";



                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                        }



                                        



                                    }



                                    



                                }



                                



                            }



                            else{



                                $design = mysql_fetch_assoc($res0);



                                if($design['uniteStock']=='Transaction' || $design['uniteStock']=='Credit' ){



                                    $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";



                                    $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



                                    $transaction = mysql_fetch_assoc($resT);



                                    $image=$transaction['aliasTransaction'];



                                    $trans_alias=$transaction['aliasTransaction'];



                                    $trans_pagnet=$idPagnet;



                                    $msg_transaction="OK";



                                }



                                else{



                                    



                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,".$idDesignation.",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";



                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                }



                            }



                        }



                        else{



                            $msg_info="Erreur";



                        }



                    }



                    if($classe==4 && $pagnet['type']==0){



                        $idDesignation=$codeBrute[0];



                        $sql0="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' and classe=2 ";



                        $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());



                        if(mysql_num_rows($res0)){



                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                            $ligne = mysql_fetch_assoc($res) ;



                            if(mysql_num_rows($res)){



                                /***Debut verifier si la ligne du produit existe deja ***/



                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$idDesignation."' and classe=2  ";



                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                $ligne1 = mysql_fetch_assoc($res);



                                /***Debut verifier si la ligne du produit existe deja ***/



                                if($ligne1 != null){



                                    $quantite = $ligne1['quantite'] + 1;



                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idStock='".$idDesignation."' and classe=2  ";



                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                }



                                else {



                                    if($ligne['classe']==2){



                                        $design = mysql_fetch_assoc($res0);



                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,".$idDesignation.",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";



                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                    }



                                }



                            }



                            else{



                                $design = mysql_fetch_assoc($res0);



                                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,".$idDesignation.",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";



                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                            }



                            



                        }



                        else{



                            $msg_info="Erreur";



                        }



                    }



                    /**Fin de la Vente des Services, Depenses  */



                }



                /**Debut de la Vente des Produits sur la Designation */



                else{



                    $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$codeBarre."' or codeBarreuniteStock='".$codeBarre."' or designation='".$codeBarre."') ";



                    $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());



                    if(mysql_num_rows($res)){



                        $design = mysql_fetch_assoc($res);



                        if($design['codeBarreDesignation']==$codeBarre){



                            if($pagnet['type']==0){



                                if($design['classe']==0){



                                    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";



                                    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());



                                    $t_stock = mysql_fetch_array($res_t) ;



                                    $restant = $t_stock[0];



                                    if($restant>0){



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                            /***Debut verifier si le produit existe deja ***/



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne2 = mysql_fetch_assoc($res);



                                            /***Debut verifier si le produit existe deja ***/



                                            $existant=0;



                                            if($ligne2 != null){



                                                $existant=$ligne2['quantite']*$design['nbreArticleUniteStock'];



                                            }



                                            $quantite = $ligne1['quantite'] + 1;



                                            $reste = $restant- $quantite - $existant;



                                            if ($reste>=0){



                                                $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                            }



                                            else{



                                                $msg_info="<p>IMPOSSIBLE.</br>



                                                </br> La quantité de ce stock est insuffisant pour la ligne.



                                                </br> Il vous reste  <span>".$restant."</span> Unités dans le Stock.



                                                </p>";



                                            }



                                        }



                                        else {



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne = mysql_fetch_assoc($res) ;



                                            if(mysql_num_rows($res)){



                                                if($ligne['classe']==0){



                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 



                                                }



                                            }



                                            else{



                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                            }



                                        



                                        }



                                    }



                                    else{



                                        $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>



                                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";



                                    }



                                }



                                else{



                                    if($design['classe']==3){



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne = mysql_fetch_assoc($res) ;



                                        if(mysql_num_rows($res)){



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=3  ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne1 = mysql_fetch_assoc($res);



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            if($ligne1 != null){



                                                



                                            }



                                            else {



                                                if($ligne['classe']!=3){



                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",3)";



                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                }



                                            }



                                        }



                                        else{



                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",3)";



                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                        }



                                    }



                                    if($design['classe']==1){



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne = mysql_fetch_assoc($res) ;



                                        if(mysql_num_rows($res)){



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne1 = mysql_fetch_assoc($res);



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            if($ligne1 != null){



                                                $quantite = $ligne1['quantite'] + 1;



                                                $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";



                                                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                            }



                                            else {



                                                if($ligne['classe']==1){



                                                    if($design['uniteStock']!='Transaction'){



                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";



                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                    }



                                                    



                                                }



                                                



                                            }



                                            



                                        }



                                        else{



                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";



                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                        }



                                    }



                                    if($design['classe']==2){



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne = mysql_fetch_assoc($res) ;



                                        if(mysql_num_rows($res)){



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne1 = mysql_fetch_assoc($res);



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            if($ligne1 != null){



                                                $quantite = $ligne1['quantite'] + 1;



                                                $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";



                                                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                            }



                                            else {



                                                if($ligne['classe']==2){



                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";



                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                }



                                            }



                                        }



                                        else{



                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";



                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                        }



                                    }



                                }



                            }



                            else{



                                /***Debut verifier si la ligne du produit existe deja ***/



                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                $ligne1 = mysql_fetch_assoc($res);



                                /***Debut verifier si la ligne du produit existe deja ***/



                                if($ligne1 != null){



                                    $quantite = $ligne1['quantite'] + 1;



                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                }



                                else {



                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                    $ligne = mysql_fetch_assoc($res) ;



                                    if(mysql_num_rows($res)){



                                        if($ligne['classe']==0){



                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 



                                        }



                                    }



                                    else{



                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                    }







                                }



                            }



                        }



                        else{



                            if($design['designation']==$codeBarre){



                                if($design['classe']==0){



                                    if($pagnet['type']==0){



                                        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";



                                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());



                                        $t_stock = mysql_fetch_array($res_t) ;



                                        $restant = $t_stock[0];



                                        if($restant>0){



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne1 = mysql_fetch_assoc($res);



                                            /***Debut verifier si la ligne du produit existe deja ***/



                                            if($ligne1 != null){



                                                /***Debut verifier si le produit existe deja ***/



                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."'  and  unitevente!='Article' and  unitevente!='article' and classe=0 ";



                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                $ligne2 = mysql_fetch_assoc($res);



                                                /***Debut verifier si le produit existe deja ***/



                                                $existant=0;



                                                if($ligne2 != null){



                                                    $existant=$ligne2['quantite']*$design['nbreArticleUniteStock'];



                                                }



                                                $quantite = $ligne1['quantite'] + 1;



                                                $reste = $t_stock[0] - $quantite - $existant;



                                                if ($reste>=0){



                                                    $prixTotal=$quantite*$ligne1['prixunitevente'];



                                                    $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 



                                                }



                                                else{



                                                    $msg_info="<p>IMPOSSIBLE.</br>



                                                    </br> La quantité de ce stock est insuffisant pour la ligne.



                                                    </br> Il vous reste  <span>".$t_stock[0]."</span> Unités dans le Stock.



                                                    </p>";



                                                }                     



                                            }



                                            else {



                                                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                                $ligne = mysql_fetch_assoc($res) ;



                                                if(mysql_num_rows($res)){



                                                    if($ligne['classe']==0){



                                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 



                                                    }



                                                }



                                                else{



                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                }



                                            



                                            }



                                        }



                                        else{



                                            $msg_info="<b>LE CODE-BARRE SAISIE CORRESPOND A UN STOCK FINI (TERMINER).</b></br></br>



                                            VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ....";



                                        }



                                    }



                                    else{



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                            $quantite = $ligne1['quantite'] + 1;



                                            $prixTotal=$quantite*$ligne1['prixunitevente'];



                                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";



                                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                        }



                                        else {



                                            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                            $ligne = mysql_fetch_assoc($res) ;



                                            if(mysql_num_rows($res)){



                                                if($ligne['classe']==0){



                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 



                                                }



                                            }



                                            else{



                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";



                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                            }



    



                                        }



                                    }



                                }



                                if($design['classe']==1 && $pagnet['type']==0){



                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                    $ligne = mysql_fetch_assoc($res) ;



                                    if(mysql_num_rows($res)){



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                            $quantite = $ligne1['quantite'] + 1;



                                            $prixTotal=$quantite*$ligne1['prixunitevente'];



                                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";



                                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                        }



                                        else {



                                            if($ligne['classe']==1){



                                                if($design['uniteStock']!='Transaction'){



                                                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";



                                                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                                }



                                                



                                            }



                                            



                                        }



                                        



                                    }



                                    else{



                                        if($design['uniteStock']=='Transaction'){



                                            $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";



                                            $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



                                            $transaction = mysql_fetch_assoc($resT);



                                            $image=$transaction['aliasTransaction'];



                                            $trans_alias=$transaction['aliasTransaction'];



                                            $trans_pagnet=$idPagnet;



                                            $msg_transaction="OK";



                                        }



                                        else if($design['uniteStock']=='Credit'){



                                            $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";



                                            $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



                                            $transaction = mysql_fetch_assoc($resT);



                                            $image=$transaction['aliasTransaction'];



                                            $trans_alias=$transaction['aliasTransaction'];



                                            $trans_pagnet=$idPagnet;



                                            $msg_credit="OK";



                                        }



                                        else{



                                            



                                            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";



                                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                        }



                                    }



                                }



                                if($design['classe']==2 && $pagnet['type']==0){



                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                    $ligne = mysql_fetch_assoc($res) ;



                                    if(mysql_num_rows($res)){



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                            $quantite = $ligne1['quantite'] + 1;



                                            $prixTotal=$quantite*$ligne1['prixunitevente'];



                                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";



                                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                                        }



                                        else {



                                            if($ligne['classe']==2){



                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";



                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                            }



                                        }



                                    }



                                    else{



                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";



                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                    }



                                }



                                if($design['classe']==3 && $pagnet['type']==0){



                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                    $ligne = mysql_fetch_assoc($res) ;



                                    if(mysql_num_rows($res)){



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=3  ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                            



                                        }



                                        else {



                                            if($ligne['classe']!=3){



                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",3)";



                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                            }



                                        }



                                    }



                                    else{



                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",3)";



                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                    }



                                }



                                if($design['classe']==5 && $pagnet['type']==0){



                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                    $ligne = mysql_fetch_assoc($res) ;



                                    if(mysql_num_rows($res)){



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=5  ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                        }



                                        else {



                                            if($ligne['classe']==5){



                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";



                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                            }



                                        }



                                    }



                                    else{



                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";



                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                    }



                                }



                                if($design['classe']==7 && $pagnet['type']==0){



                                    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



                                    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                    $ligne = mysql_fetch_assoc($res) ;



                                    if(mysql_num_rows($res)){



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";



                                        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



                                        $ligne1 = mysql_fetch_assoc($res);



                                        /***Debut verifier si la ligne du produit existe deja ***/



                                        if($ligne1 != null){



                                        }



                                        else {



                                            if($ligne['classe']==7){



                                                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";



                                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                            }



                                        }



                                    }



                                    else{



                                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";



                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                                    }



                                }



                                if($design['classe']==8){



                                    $sql3="UPDATE `".$nomtablePagnet."` set type='1' where idPagnet=".$idPagnet;



                                    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



                                }



                                if($design['classe']==10){



                                    $sql3="UPDATE `".$nomtablePagnet."` set type='10' where idPagnet=".$idPagnet;



                                    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



                                }



                            }



                        }



                    }



                    else{



                        $msg_info="<b>LE CODE-BARRE SAISIE NE FIGURE PAS DANS LE STOCK.</b></br></br>



                        VEUILLEZ CONTACTER LE GERANT POUR VERIFIER LE STOCK ET LE CATALOGUE DES PRODUITS ET SERVICES ..."; 



                    }



                }



                /**Debut de la Vente des Produits sur la Designation */



            }







    }



/**Fin Button Ajouter Ligne dans un pagnet**/







/**Debut Button Ajouter Transaction dans un pagnet**/



    if (isset($_POST['btnEnregistrerTransaction'])) {



        $reqT="SELECT * from `aaa-transaction` where aliasTransaction='".$aliasTrans."'";



        $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



        $transaction = mysql_fetch_assoc($resT);







        if (($idFact != null) && ($typeTrans == 2)){



            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$transaction['nomTransaction']."',0,0,'Facture',".$montantTrans.",".$idFact.",".$montantTrans.",".$pagnetTrans.",3)";



            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );







            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', totalp=".$montantTrans.",apayerPagnet=".$montantTrans." where idPagnet=".$pagnetTrans;



            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



        }



        if (($typeTrans == 0) || ($typeTrans == 1)){



            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$transaction['nomTransaction']."',0,0,'Transaction',".$montantTrans.",".$typeTrans.",".$montantTrans.",".$pagnetTrans.",3)";



            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );







            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', totalp=".$montantTrans.",apayerPagnet=".$montantTrans." where idPagnet=".$pagnetTrans;



            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



        }



        if($typeTrans == 3){



            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$transaction['nomTransaction']."',0,0,'Credit',".$montantTrans.",".$idFact.",".$montantTrans.",".$pagnetTrans.",4)";



            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 8  =>".mysql_error() );







            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', totalp=".$montantTrans.",apayerPagnet=".$montantTrans." where idPagnet=".$pagnetTrans;



            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



        }







    }



/**Fin Button Ajouter Transaction dans un pagnet**/







/**Debut Button Terminer Pagnet**/



    if (isset($_POST['btnImprimerProforma'])) {

        //if (isset($_POST['remise']) || isset($_POST['versement'])) {



            // code...

            if ($_SESSION['tampon']==1 && $_SESSION['page']=='vente') {
                $nomtablePagnet = $nomtablePagnetTampon;
                $nomtableLigne = $nomtableLigneTampon;
            } 
            


            $idPagnet=@$_POST['idPagnet'];







            $sql="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";



            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());



            $pagnet = mysql_fetch_assoc($res) ;







            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";



            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());



            $TotalP = mysql_fetch_array($resT) ;







            $sqlTD="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." && unitevente='Transaction' && quantite=0 ";



            $resTD = mysql_query($sqlTD) or die ("persoonel requête 2".mysql_error());



            $TotalTD = mysql_fetch_array($resTD) ;







            //$Total[ = $TotalP[0] - $TotalTD[0];







            $totalp=$TotalP[0] - ($TotalTD[0] * 2);



            if(@$_POST['remise']==''){



                $remise=0;



            }



            else{



                $remise=@$_POST['remise'];



            }



            $apayerPagnet=$totalp-$remise;



            if(@$_POST['versement']==''){



                $versement=0;



                $monaie=0;



            } 



            else{



                $versement=@$_POST['versement'];



                $monaie=$versement-$apayerPagnet;



            }







            $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1', remise=".$remise.",totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",versement=".$versement.",restourne=".$monaie." where idPagnet=".$idPagnet;



            $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



    }



/**Fin Button Terminer Pagnet**/

/**Debut Button upload Image Panier**/
if (isset($_POST['btnUploadImgPanier'])) {
    $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
    if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/'.$file);

            $sql2="UPDATE `".$nomtablePagnet."` set image='".$file."' where idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Panier**/



 







require('entetehtml.php');



?>



<!-- Debut Code HTML -->



<body onLoad="">



<input type="hidden" id="typeVente" value="1"/>



    <header>



        <?php
           
            require('headerSansScore.php');
        
             $_SESSION['page']='vente';
        ?>



        <!-- Debut Container HTML -->



        <div class="container" id="venteContent"> 



            <img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">



        </div>



        <!-- Fin Container HTML -->



    </header>



</body>



<!-- Fin Code HTML -->        



    <div id="msg_info_1" class="modal fade" role="dialog">



        <div class="modal-dialog">



            <!-- Modal content-->



            <div class="modal-content">



                <div class="modal-header panel-primary">



                    <button type="button" class="close" data-dismiss="modal">&times;</button>



                    <h4 class="modal-title">Alerte</h4>



                </div>



                <div class="modal-body" id="msg_info_body">



                    



                    



                </div>



                <div class="modal-footer">



                    <button type="button" id="closeInfo" class="btn btn-primary" data-dismiss="modal">Close</button>



                </div>



            </div>



        </div>



    </div>







<!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->



<!-- Fin Code HTML -->        



    <div id="loading_gif_modal" class="modal fade" style="margin-top:20%" role="dialog">



    <!-- <div id="loading_gif_modal" class="modal fade" style="margin-top:20%" role="dialog"> -->



        <div class="modal-dialog modal-sm">



            <!-- Modal content-->



            <div class="modal-content">



                <div class="modal-body" id="msg_info_body" align="center">



                    <img src="images/loading-gif3.gif" style="height: 100px;width: 100px;margin-top:1%" alt="GIF" srcset="">                    



                </div>



            </div>



        </div>



    </div>







<!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->







    <?php



        // if(isset($msg_info)) {



        //     echo"<script type='text/javascript'>



        //                 $(window).on('load',function(){



        //                     $('#msg_info').modal('show');



        //                 });



        //             </script>";



        //     echo'<div id="msg_info" class="modal fade " role="dialog">



        //             <div class="modal-dialog">



        //                 <!-- Modal content-->



        //                 <div class="modal-content">



        //                     <div class="modal-header panel-primary">



        //                         <button type="button" class="close" data-dismiss="modal">&times;</button>



        //                         <h4 class="modal-title">Alerte</h4>



        //                     </div>



        //                     <div class="modal-body">



        //                         <p>'.$msg_info.'</p>



                                



        //                     </div>



        //                     <div class="modal-footer">



        //                         <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>



        //                     </div>



        //                     </div>



        //             </div>



        //         </div>';



                



        // }



    ?>



<!-- Fin PopUp d'Alerte sur l'ensemble de la Page -->







<!-- Debut PopUp des Transferts -->



    <?php



        if(isset($msg_transfert)) {



            echo"<script type='text/javascript'>



                $(window).on('load',function(){



                    $('#msg_transfert').modal('show');



                });



            </script>";







            echo '<div class="modal fade" tabindex="-1" id="msg_transfert" role="dialog" aria-labelledby="myModalLabel">



                <div class="modal-dialog" role="document">



                    <div class="modal-content">



                        <div class="modal-header">



                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>



                            <h4 class="modal-title" id="myModalLabel">Ajouter Transferts</h4>



                        </div>



                        <div class="modal-body">



                            <form name="formulaireVersement" method="post" >



                                <table id="exemple2" lass="table table-bordered table-responsive display"  border="1">



                                    <thead>



                                        <tr>



                                            <th>TRANSFERT</th>



                                            <th>MONTANT</th>



                                            <th>OPERATIONS</th>



                                        </tr>



                                    </thead>



                                    <tfoot>



                                        <tr>



                                            <th>TRANSFERT</th>



                                            <th>MONTANT</th>



                                            <th>OPERATIONS</th>



                                        </tr>



                                    </tfoot> ';



                                    $reqT="SELECT * from `aaa-transaction` where typeTransaction='Transaction' ORDER BY idTransaction ASC ";



                                    $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



                                    while ($transaction = mysql_fetch_array($resT)){



                                        $sql0="SELECT * FROM `".$nomtableDesignation."` where ((uniteStock='Depot' AND classe=3) || designation='Caisse Transfert') AND description=".$transaction['idTransaction'];



                                        $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());



                                        if(mysql_num_rows($res0)){



                                            $trans = mysql_fetch_array($res0);



                                            if($trans['seuil']==1){



                                                $sql1="SELECT * FROM `".$nomtableTransfert."` where idTransaction='".$transaction['idTransaction']."' ORDER BY idTransfert DESC";



                                                $res1=mysql_query($sql1) or die ("select stock impossible =>".mysql_error());



                                                $transAvant = mysql_fetch_array($res1);



                                                if($transaction['idTransaction']==0){



                                                    echo'<tr>



                                                    <td><img src="images/'.$transaction['aliasTransaction'].'.png" width="50" height="50" border="0" class="img-circle"> '.$trans["designation"].'</td>



                                                    <td><input type="number" class="form-control" name="compte-'.$transaction['idTransaction'].'" id="compte-'.$transaction['idTransaction'].'" value="'.$transAvant['compte'].'" /></td>



                                                    <td>



                                                        <button type="button" onclick="ajt_LigneTransfert('.$idpagnet_trans.','.$transaction["idTransaction"].')" id="btn_Transfert-'.$transaction['idTransaction'].'"



                                                            class="btn btn-success"><i class="glyphicon glyphicon-plus">



                                                            </i>AJOUTER



                                                        </button>&nbsp;



                                                    </td>



                                                    </tr>';



                                                }



                                                else{



                                                    echo'<tr>



                                                    <td><img src="images/'.$transaction['aliasTransaction'].'.png" width="50" height="50" border="0" class="img-circle"> '.$trans["designation"].'</td>



                                                    <td><input type="number" class="form-control" name="compte-'.$transaction['idTransaction'].'" id="compte-'.$transaction['idTransaction'].'" value="'.$transAvant['compte'].'" /></td>



                                                    <td>



                                                        <button type="button" onclick="ajt_LigneTransfert('.$idpagnet_trans.','.$transaction["idTransaction"].')" id="btn_Transfert-'.$transaction['idTransaction'].'"



                                                            class="btn btn-success"><i class="glyphicon glyphicon-plus">



                                                            </i>AJOUTER



                                                        </button>



                                                    </td>



                                                    </tr>';



                                                }



                                            }



                                        }



                                    }



                                echo '



                                </table>



                            </form>



                        </div>







                    </div>



                </div>



            </div>';      



        }



    ?>



<!-- Fin PopUp des Transferts -->







<!-- Debut PopUp des Transactions -->



    <?php



        if(isset($msg_transaction)) {



            echo"<script type='text/javascript'>



                        $(window).on('load',function(){



                            $('#msg_info').modal('show');



                        });



                    </script>";



            echo'<div id="msg_info" class="modal fade " role="dialog">



                    <div class="modal-dialog">



                        <!-- Modal content-->



                        <div class="modal-content">



                            <div class="modal-header panel-primary">



                                <button type="button" class="close" data-dismiss="modal">&times;</button>



                                <h4 class="modal-title">Transaction</h4>



                            </div>



                            <form  method="POST" >



                            <div class="modal-body">



                                <div class="row">



                                    <div class="col-xs-6">



                                        <img src="images/'.$image.'.png"  width="200" height="200" border="0" class="img-circle">



                                        <input type="hidden" name="aliasTrans" value='.$trans_alias.' >



                                        <input type="hidden" name="pagnetTrans" value='.$trans_pagnet.' >



                                    </div>



                                    <div class="col-xs-6">



                                        <div class="well">



                                                <div class="form-group">



                                                    <label for="operation" class="control-label">Operation</label>



                                                    <select name="typeTrans" id="typeTrans" class="form-control" required="">



                                                        <option value="">--------</option>



                                                        <option value="1">Depot</option>



                                                        <option value="0">Retrait</option>



                                                        <option value="2">Facture</option>



                                                    </select>



                                                    <span class="help-block"></span>



                                                </div>



                                                <div class="form-group" id="listFacture" style="display:none;">



                                                    <label for="operation" class="control-label">Facture</label>



                                                    <select  id="nomFact" name="idFact" class="form-control" >



                                                        <option value="">--------</option>



                                                         ';



                                                        $reqT="select * from `".$nomtableDesignation."` where uniteStock='Facture' and seuil=1 ";



                                                        $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



                                                        while ($facture = mysql_fetch_assoc($resT)){ 



                                                          echo '  <option value="'.$facture["description"].'">'.$facture["designation"].'</option>';



                                                        }



                                                     echo '    



                                                    </select>



                                                    <span class="help-block"></span>



                                                </div>



                                                <div class="form-group">



                                                    <label for="montant" class="control-label">Montant</label>



                                                    <input type="text" class="form-control" id="montant" name="montantTrans" value="" required="" title="Please enter your password">



                                                    <span class="help-block"></span>



                                                </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                            <div class="modal-footer">



                                <button type="submit" name="btnEnregistrerTransaction"



                                 class="btn btn-primary btnxs ">Valider</button>



                            </div>



                            </form>



                        </div>



                    </div>



                </div>';



                



        }



    ?>



<!-- Fin PopUp des Transactions -->







<!-- Debut PopUp des Credits -->



    <?php



        if(isset($msg_credit)) {



            echo"<script type='text/javascript'>



                        $(window).on('load',function(){



                            $('#msg_info').modal('show');



                        });



                    </script>";



            echo'<div id="msg_info" class="modal fade " role="dialog">



                    <div class="modal-dialog">



                        <!-- Modal content-->



                        <div class="modal-content">



                            <div class="modal-header panel-primary">



                                <button type="button" class="close" data-dismiss="modal">&times;</button>



                                <h4 class="modal-title">Vente de credit</h4>



                            </div>



                            <form  method="POST" >



                            <div class="modal-body">



                                <div class="row">



                                    <div class="col-xs-6">



                                        <img src="images/'.$image.'.png"  width="200" height="200" border="0" class="img-circle">



                                        <input type="hidden" name="aliasTrans" value='.$trans_alias.' >



                                        <input type="hidden" name="pagnetTrans" value='.$trans_pagnet.' >



                                        <input type="hidden" name="typeTrans" value="3" >



                                    </div>



                                    <div class="col-xs-6">



                                        <div class="well">



                                            <div class="form-group">



                                                <label for="operation" class="control-label">Service</label>



                                                <select  id="nomFact" name="idFact" class="form-control" required="">



                                                    <option value="">--------</option>



                                                    <option value="0">Especes</option>



                                                        ';



                                                    $reqT="select * from `".$nomtableDesignation."` where uniteStock='Transaction' and seuil=1 ";



                                                    $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());



                                                    while ($facture = mysql_fetch_assoc($resT)){ 



                                                        echo '  <option value="'.$facture["description"].'">'.$facture["designation"].'</option>';



                                                    }



                                                    echo '    



                                                </select>



                                                <span class="help-block"></span>



                                            </div>



                                            <div class="form-group">



                                                <label for="montant" class="control-label">Montant</label>



                                                <input type="text" class="form-control" id="montant" name="montantTrans" value="" required="" title="Please enter your password">



                                                <span class="help-block"></span>



                                            </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                            <div class="modal-footer">



                                <button type="submit" name="btnEnregistrerTransaction"



                                 class="btn btn-primary btnxs ">Valider</button>



                            </div>



                            </form>



                        </div>



                    </div>



                </div>';



                



        }



    ?>



<!-- Fin PopUp des Credits -->







<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->



    <div id="msg_info_js" class="modal fade " role="dialog">



            <div class="modal-dialog">



                <!-- Modal content-->



                <div class="modal-content">



                    <div class="modal-header panel-primary">



                        <button type="button" class="close" data-dismiss="modal">&times;</button>



                        <h4 class="modal-title">Alerte</h4>



                    </div>



                    <div class="modal-body">



                        <p>IMPOSSIBLE.</br>



                        </br> La quantité de ce stock est insuffisant pour la ligne.



                        </br> Il vous reste  <span id="qte_stock"></span> Unités dans le Stock.



                        </p>



                    </div>



                    <div class="modal-footer">



                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>



                    </div>



                </div>



            </div>



    </div>



<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->



<!-- Debut Message d'Alerte concernant avance > apayerpagnet -->



    <div id="msg_info_avance" class="modal fade " role="dialog">



            <div class="modal-dialog">



                <!-- Modal content-->



                <div class="modal-content">



                    <div class="modal-header panel-primary">



                        <button type="button" class="close" data-dismiss="modal">&times;</button>



                        <h4 class="modal-title">Alerte</h4>



                    </div>



                    <div class="modal-body">



                        <p> ATTENTION!</br>



                        Le montant de l'avance est suppérieur au montant du panier. <br>



                        Cette avance ne sera pas enregistrée si vous terminez le panier.



                        </p>



                    </div>



                    <div class="modal-footer">



                        <button type="button" id="closeAvance" class="btn btn-primary" data-dismiss="modal">Close</button>



                    </div>



                </div>



            </div>



    </div>



<!-- Fin Message d'Alerte concernant avance > apayerpagnet -->



 <!-- Debut Message d'Alerte concernant client required -->



 <div id="msg_info_ClientRequired" class="modal fade " role="dialog">



            <div class="modal-dialog">



                <!-- Modal content-->



                <div class="modal-content">



                    <div class="modal-header panel-primary">



                        <button type="button" class="close" data-dismiss="modal">&times;</button>



                        <h4 class="modal-title">Alerte</h4>



                    </div>



                    <div class="modal-body">



                        <p> ATTENTION!</br>

                        Vous avez choisi le compte Bon,  

                        le nom du client est obligatoire.



                        </p>



                    </div>



                    <div class="modal-footer">



                        <button type="button" id="closeAvance" class="btn btn-primary" data-dismiss="modal">Close</button>



                    </div>



                </div>



            </div>



    </div>



<!-- Fin Message d'Alerte concernant client required -->







<!-- Debut PopUp d'Alerte sur le retard de Paiement -->



    <?php



        if(isset($msg_paiement)) {



            echo"<script type='text/javascript'>



                        $(window).on('load',function(){



                            $('#msg_info').modal('show');



                        });



                    </script>";



            echo'<div id="msg_info" class="modal fade " role="dialog">



                    <div class="modal-dialog">



                        <!-- Modal content-->



                        <div class="modal-content">



                            <div class="modal-header panel-primary">



                                <button type="button" class="close" data-dismiss="modal">&times;</button>



                                <h4 class="modal-title">Alerte retard de paiement</h4>



                            </div>



                            <div class="modal-body">



                                <p>'.$msg_paiement.'</p>



                                



                            </div>



                            <div class="modal-footer">



                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>



                            </div>



                            </div>



                    </div>



                </div>';



                



        }



    ?>



<!-- Fin PopUp d'Alerte sur le retard de Paiement -->

    
    <div class="modal fade" id="selectNbMoisModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Formulaire de paiement</h4>

                </div>

                <div class="modal-body">
                    <div name="deleteContainer">
                        <input type="hidden" id="idPsModalSelectNbMois">
                        <input type="hidden" id="montantFormuleInput">
                        <div class="form-group" align="center">
                            <h3>
                                <span class="">Choisir le nombre de mois à payer</span>
                            </h3>
                            <Select id="selectNbMois" onChange="changeFormule()" class="form-control" style="background-color:gold;width: 100px;">
                                <option value="1">1</option>
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="12">12</option>
                            </Select>
                        </div><br><br><br>
                        <div class="form-group alert alert-info" id="divFormule" style="width: 250px;">
                            Montant à payer : <span id="montantFormule"></span> FCFA
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                            <button type="button" name="btnSelectNbMois" onClick="effectue_paiementWave()" class="btn btn-primary">Valider</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="paiementWave"  class="modal fade " role="dialog" align="center">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Paiement Wave </b></h4>
                </div>
                <div class="modal-body" id="bodyPayWave">
                </div>	               
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>						
            </div>
        </div>
    </div>

    <div id="paiementModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Paiement Orange Money</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="" align="center">
                        <div class="row">
                            <div class="col-md-6">
                                <br/><br/><img alt="" style="width: 90%;height: 70%;"  src="images/orange-money2.jpeg" id="imgsrc_Upd" />                               
                            </div>
                            <div class="col-md-6">
                                <br/><br/><img alt="imgQRCode 8888" id="imgQRCode" />                               
                            </div>
                        </div> 
                        <div class="modal-footer row">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



 <?php



   }



  ?>
  