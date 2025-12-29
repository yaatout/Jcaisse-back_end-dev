<?php
	/*
	R�sum�:
	Commentaire:
	version:1.1
	Auteur:EL hadji mamadou korka
	Date de modification:25-03-2020
	*/
	session_start();

	if(!$_SESSION['iduserBack'])
		header('Location:index.php');

	require('connectionPDO.php');
	require('connectionVitrine.php');

	require('declarationVariables.php');
	$date = new DateTime();
	$timezone = new DateTimeZone('Africa/Dakar');
	$date->setTimezone($timezone);
	//R�cup�ration de l'ann�e
	$annee =$date->format('Y');
	//R�cup�ration du mois
	$mois =$date->format('m');
	//R�cup�ration du jours
	$jour =$date->format('d');
	$heureString=$date->format('H:i:s');
	$dateString=$annee.'-'.$mois.'-'.$jour;
	$dateString2=$annee.'-'.$mois.'-'.$jour;

	$catalogueTotal='';
	$type='';
	$categorie='';
	$typeCategorie='';
	$catalogueTypeCateg='';
	$doublons=NULL;
	$fusions=NULL;
	$catCatParent=NULL;

	$tabTest=NULL;
	/*************************************/
	
	  try {
		$sql0="SELECT * from  `aaa-catalogueTotal`  where id=:id";
		 $req0 = $bdd->prepare($sql0);
		 $req0->execute(array('id' => $_GET['i']));
		$tab0 = $req0->fetch(PDO::FETCH_ASSOC);
		
		 if ($tab0) {
		 	$catalogueTotal='aaa-catalogueTotal';
			$type=$tab0['type'];
	    $categorie=$tab0['categorie'];
	    $typeCategorie=$type."-".$categorie;
			$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
			$categorieTypeCateg='aaa-categorie-'.$typeCategorie;
			$formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
			$tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
		} else {
			$tab0=0;
		}
	} catch(PDOException $e) {
		die("Erreur lors de la récupération du catalogue : " . $e->getMessage());
	}

	/*************************************/
	if (($type=="Pharmacie") && ($categorie=="Detaillant")) {

	    require('catalogueDetail-pharmacie.php');

	}else{
		
		if (isset($_POST['btnGenererCatalogue'])) {

			try {
				$sql20="SELECT MAX(idFusion) as max_id from  `".$catalogueTypeCateg."` ";
				$req20 = $bdd->query($sql20);
				$tab20 = $req20->fetch(PDO::FETCH_ASSOC);
				$iD = isset($tab20['max_id']) ? $tab20['max_id'] : 0;
			} catch(PDOException $e) {
				$iD = 0;
			}
			try {
				$sql3="SELECT * FROM `aaa-boutique` where type = :type and categorie = :categorie";
				$req3 = $bdd->prepare($sql3);
				$req3->execute(array(
					'type' => $type,
					'categorie' => $categorie
				));
				
				if($req3->rowCount() > 0) {
								/*$sql0="CREATE TABLE IF NOT EXISTS `".$catalogueTypeCateg."`
										 (`id` INT NOT NULL AUTO_INCREMENT,
											 `designation` VARCHAR(155) NOT NULL,
											 `idDesignation` INT NOT NULL,
											 `idBoutique` INT NOT NULL,
											 `categorie` VARCHAR(155) NOT NULL,
											 `categorieParent` VARCHAR(155) NOT NULL,
										 `uniteStock` VARCHAR(155) NOT NULL,
										 `prix` REAL NOT NULL,`nbreArticleUniteStock` INT NOT NULL,
										 `prixuniteStock` REAL NOT NULL,
										 `codeBarreDesignation` VARCHAR(155) NOT NULL,
										 `codeBarreuniteStock` VARCHAR(155) NOT NULL,
										 `image` VARCHAR(155) NOT NULL,
										 UNIQUE (`designation`),
										  PRIMARY KEY (`id`)) ENGINE = MYISAM";*/
							try {
								$sql0="CREATE TABLE IF NOT EXISTS `".$catalogueTypeCateg."`
										 (`id` INT NOT NULL AUTO_INCREMENT,
											 `designation` VARCHAR(155) NOT NULL,
											 `idDesignation` INT NOT NULL,
											 `idBoutique` INT NOT NULL,
											 `idFusion` INT NOT NULL,
											 `categorie` VARCHAR(155) NOT NULL,
											 `categorieParent` VARCHAR(155) NOT NULL,
											`uniteStock` VARCHAR(155) NOT NULL,
											`prix` REAL NOT NULL,`nbreArticleUniteStock` INT NOT NULL,
											`prixuniteStock` REAL NOT NULL,
											`prixAchat` REAL NOT NULL,
											`codeBarreDesignation` VARCHAR(155) NOT NULL,
											`codeBarreuniteStock` VARCHAR(155) NOT NULL,
											`classe` INT NOT NULL,
											`image` VARCHAR(155) ,
											PRIMARY KEY (`id`)) ENGINE = MYISAM";
								$bdd->exec($sql0);

								$sql14="CREATE TABLE IF NOT EXISTS `".$categorieTypeCateg."`
												  (`id` INT NOT NULL AUTO_INCREMENT,
													  `nomCategorie` VARCHAR(155) NOT NULL,
													  `categorieParent` VARCHAR(155) ,
													  `image` VARCHAR(155) ,
													  UNIQUE (nomCategorie),
												   PRIMARY KEY (`id`)) ENGINE = MYISAM";
								$bdd->exec($sql14);
							} catch(PDOException $e) {
								die("Erreur lors de la création des tables : " . $e->getMessage());
							}

								while($boutique = $req3->fetch(PDO::FETCH_ASSOC)) {
									 $nomtableDesignation=$boutique['nomBoutique']."-designation";
									 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
									 //$sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0";
									 try {
										$sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0 ";
										$req4 = $bdd->query($sql4);
										
										while($des = $req4->fetch(PDO::FETCH_ASSOC)) {
											/*****************************************/
											$iD=$iD+1;
											
											$sql6="SELECT * from `".$nomtableCategorie."` where nomcategorie = :nomcategorie";
											$req6 = $bdd->prepare($sql6);
											$req6->execute(array('nomcategorie' => $des['categorie']));
											$tab6 = $req6->fetch(PDO::FETCH_ASSOC);

											$sql7="SELECT nomcategorie from `".$nomtableCategorie."` where idcategorie = :idcategorie";
											$req7 = $bdd->prepare($sql7);
											$req7->execute(array('idcategorie' => $tab6['categorieParent']));
											$tab7 = $req7->fetch(PDO::FETCH_ASSOC);
											
											/*****************  CATEGORIE************************/
											$catCatParent[$des['categorie']] = $tab7['nomcategorie'];
											/******************************************/
											
											$sql1="INSERT INTO `".$catalogueTypeCateg."`
													(designation,idDesignation,idBoutique,idFusion,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,codeBarreDesignation,codeBarreuniteStock,`classe`) values
													(:designation,:idDesignation,:idBoutique,:idFusion,:categorie,:categorieParent,:uniteStock,:prix,:nbreArticleUniteStock,:prixuniteStock,:codeBarreDesignation,:codeBarreuniteStock,:classe)";
											
											$req1 = $bdd->prepare($sql1);
											$req1->execute(array(
												'designation' => $des['designation'],
												'idDesignation' => $des['idDesignation'],
												'idBoutique' => $boutique['idBoutique'],
												'idFusion' => $iD,
												'categorie' => $des['categorie'],
												'categorieParent' => $tab7['nomcategorie'],
												'uniteStock' => $des['uniteStock'],
												'prix' => $des['prix'],
												'nbreArticleUniteStock' => $des['nbreArticleUniteStock'],
												'prixuniteStock' => $des['prixuniteStock'],
												'codeBarreDesignation' => $des['codeBarreDesignation'],
												'codeBarreuniteStock' => $des['codeBarreuniteStock'],
												'classe' => $des['classe']
											));
											
											$sql21="UPDATE `".$nomtableDesignation."` set idFusion = :idFusion where idDesignation = :idDesignation";
											$req21 = $bdd->prepare($sql21);
											$req21->execute(array(
												'idFusion' => $iD,
												'idDesignation' => $des['idDesignation']
											));
										}
									} catch(PDOException $e) {
										die("Erreur dans la boucle principale : " . $e->getMessage());
									}

										 /********************** aaa-categorie-ddd-dddd ******************/

										 /*$boutiqueCategorie=$boutique['nomBoutique']."-categorie";
										 $sql15="SELECT * FROM `".$boutiqueCategorie."` ";
										 if ($res15 = mysql_query($sql15)) {
												while ($cat=mysql_fetch_array($res15)) {
													$sql16="insert into `".$categorieTypeCateg."`
															(idCategorie,nomCategorie,categorieParent,idBoutique) values
															('".$cat['idcategorie']."','".$cat['nomcategorie']."','".$cat['categorieParent']."','".$boutique['idBoutique']."')";

															 $res16=@mysql_query($sql16);
												}
											}*/
												 if ($catCatParent) {
													 foreach($catCatParent as $cle => $element) {
    //echo '[' . $cle . '] vaut ' . $element . '<br />';
    $sql16="INSERT INTO `".$categorieTypeCateg."` (nomCategorie,categorieParent) VALUES (:cle, :element)";
    $req16 = $bdd->prepare($sql16);
    $req16->execute(array(
        'cle' => $cle,
        'element' => $element
    ));
}
												 }
								}

			   }
			} catch(PDOException $e) {
				$iD = 0;
			}


		}
		if (isset($_POST['btnUpdateCatalogue'])) {
			try {
				$sql20="SELECT MAX(idFusion) as max_id from `".$catalogueTypeCateg."` ";
				$req20 = $bdd->query($sql20);
				$tab20 = $req20->fetch(PDO::FETCH_ASSOC);
				$iD = isset($tab20['max_id']) ? $tab20['max_id'] : 0;
			} catch(PDOException $e) {
				$iD = 0;
			}

			try {
				$sql3="SELECT * FROM `aaa-boutique` where type = :type and categorie = :categorie";
				$req3 = $bdd->prepare($sql3);
				$req3->execute(array(
					'type' => $type,
					'categorie' => $categorie
				));
				
				while($boutique = $req3->fetch(PDO::FETCH_ASSOC)) {
					$nomtableDesignation=$boutique['nomBoutique']."-designation";
					$nomtableCategorie = $boutique['nomBoutique']."-categorie";
					
					try {
						$sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0 and (idFusion=0 OR idFusion IS NULL)";
						$req4 = $bdd->query($sql4);
						
						while($des = $req4->fetch(PDO::FETCH_ASSOC)) {
							/*****************************************/
							$iD=$iD+1;
							
							$sql6="SELECT * from `".$nomtableCategorie."` where nomcategorie = :nomcategorie";
							$req6 = $bdd->prepare($sql6);
							$req6->execute(array('nomcategorie' => $des['categorie']));
							$tab6 = $req6->fetch(PDO::FETCH_ASSOC);

							$sql7="SELECT nomcategorie from `".$nomtableCategorie."` where idcategorie = :idcategorie";
							$req7 = $bdd->prepare($sql7);
							$req7->execute(array('idcategorie' => $tab6['categorieParent']));
							$tab7 = $req7->fetch(PDO::FETCH_ASSOC);
							
							/*****************  CATEGORIE************************/
							$catCatParent[$des['categorie']] = $tab7['nomcategorie'];
							/******************************************/
							
							$designationTmp=trim($des['designation']);
							$sql1="INSERT INTO `".$catalogueTypeCateg."`
									(designation,idDesignation,idBoutique,idFusion,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,prixAchat,codeBarreDesignation,codeBarreuniteStock,`classe`) values
									(:designation,:idDesignation,:idBoutique,:idFusion,:categorie,:categorieParent,:uniteStock,:prix,:nbreArticleUniteStock,:prixuniteStock,:prixAchat,:codeBarreDesignation,:codeBarreuniteStock,:classe)";
							
							$req1 = $bdd->prepare($sql1);
							$req1->execute(array(
								'designation' => $designationTmp,
								'idDesignation' => $des['idDesignation'],
								'idBoutique' => $boutique['idBoutique'],
								'idFusion' => $iD,
								'categorie' => $des['categorie'],
								'categorieParent' => $tab7['nomcategorie'],
								'uniteStock' => $des['uniteStock'],
								'prix' => $des['prix'],
								'nbreArticleUniteStock' => $des['nbreArticleUniteStock'],
								'prixuniteStock' => $des['prixuniteStock'],
								'prixAchat' => $des['prixachat'],
								'codeBarreDesignation' => $des['codeBarreDesignation'],
								'codeBarreuniteStock' => $des['codeBarreuniteStock'],
								'classe' => $des['classe']
							));
							
							$sql21="UPDATE `".$nomtableDesignation."` set idFusion = :idFusion where idDesignation = :idDesignation";
							$req21 = $bdd->prepare($sql21);
							$req21->execute(array(
								'idFusion' => $iD,
								'idDesignation' => $des['idDesignation']
							));
						}
					} catch(PDOException $e) {
						die("Erreur dans la boucle de mise à jour : " . $e->getMessage());
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour du catalogue : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateCodeBarreCata'])) {
			try {
				$sql1="SELECT * FROM `".$catalogueTypeCateg."` where codeBarreDesignation = '' ";
				$req1 = $bdd->query($sql1);
				
				while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
					$sql3="SELECT * FROM `aaa-boutique` where type = :type and categorie = :categorie";
					$req3 = $bdd->prepare($sql3);
					$req3->execute(array(
						'type' => $type,
						'categorie' => $categorie
					));
					
					while($boutique = $req3->fetch(PDO::FETCH_ASSOC)) {
						$nomtableDesignation=$boutique['nomBoutique']."-designation";
						$nomtableCategorie = $boutique['nomBoutique']."-categorie";
						
						$sql4="SELECT * FROM `".$nomtableDesignation."` where idFusion = :idFusion and codeBarreDesignation != '' ";
						$req4 = $bdd->prepare($sql4);
						$req4->execute(array('idFusion' => $catalogue['idFusion']));
						
						while($des = $req4->fetch(PDO::FETCH_ASSOC)) {
							$sql5="UPDATE `".$catalogueTypeCateg."` 
								   set codeBarreDesignation = :codeBarreDesignation, 
									   codeBarreuniteStock = :codeBarreuniteStock 
								   where id = :id";
							
							$req5 = $bdd->prepare($sql5);
							$req5->execute(array(
								'codeBarreDesignation' => $des['codeBarreDesignation'],
								'codeBarreuniteStock' => $des['codeBarreuniteStock'],
								'id' => $catalogue['id']
							));
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des codes barres : " . $e->getMessage());
			}
		}if (isset($_POST['btnUpdatePrix'])) {
			try {
				$sql1="SELECT * FROM `".$catalogueTypeCateg."` where prix = 0 ";
				$req1 = $bdd->query($sql1);
				
				while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
					$sql3="SELECT * FROM `aaa-boutique` where type = :type and categorie = :categorie";
					$req3 = $bdd->prepare($sql3);
					$req3->execute(array(
						'type' => $type,
						'categorie' => $categorie
					));
					
					while($boutique = $req3->fetch(PDO::FETCH_ASSOC)) {
						$nomtableDesignation=$boutique['nomBoutique']."-designation";
						$nomtableCategorie = $boutique['nomBoutique']."-categorie";
						
						$sql4="SELECT * FROM `".$nomtableDesignation."` where idFusion = :idFusion";
						$req4 = $bdd->prepare($sql4);
						$req4->execute(array('idFusion' => $catalogue['idFusion']));
						
						while($des = $req4->fetch(PDO::FETCH_ASSOC)) {
							$sql5="UPDATE `".$catalogueTypeCateg."` 
								   set prix = :prix, 
									   prixuniteStock = :prixuniteStock, 
									   prixAchat = :prixAchat 
								   where id = :id";
							
							$req5 = $bdd->prepare($sql5);
							$req5->execute(array(
								'prix' => $des['prix'],
								'prixuniteStock' => $des['prixuniteStock'],
								'prixAchat' => $des['prixachat'],
								'id' => $catalogue['id']
							));
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des prix : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateCodeBarreBou'])) {
			try {
				$sql3="SELECT * FROM `aaa-boutique` where type = :type and categorie = :categorie";
				$req3 = $bdd->prepare($sql3);
				$req3->execute(array(
					'type' => $type,
					'categorie' => $categorie
				));
				
				while($boutique = $req3->fetch(PDO::FETCH_ASSOC)) {
					$nomtableDesignation=$boutique['nomBoutique']."-designation";
					
					$sql4="SELECT * FROM `".$nomtableDesignation."` where codeBarreDesignation = '' ";
					$req4 = $bdd->query($sql4);
					
					while($des = $req4->fetch(PDO::FETCH_ASSOC)) {
						$sql1="SELECT * FROM `".$catalogueTypeCateg."` where idFusion = :idFusion and codeBarreDesignation != '' ";
						$req1 = $bdd->prepare($sql1);
						$req1->execute(array('idFusion' => $des['idFusion']));
						
						while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
							$sql5="UPDATE `".$nomtableDesignation."` 
								   set codeBarreDesignation = :codeBarreDesignation, 
									   codeBarreuniteStock = :codeBarreuniteStock 
								   where idDesignation = :idDesignation";
							
							$req5 = $bdd->prepare($sql5);
							$req5->execute(array(
								'codeBarreDesignation' => $catalogue['codeBarreDesignation'],
								'codeBarreuniteStock' => $catalogue['codeBarreuniteStock'],
								'idDesignation' => $des['idDesignation']
							));
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des codes barres boutique : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateImage'])) {
			try {
				$sql1="SELECT * FROM `".$catalogueTypeCateg."` where image = '' ";
				$req1 = $bdd->query($sql1);
				
				while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
					$req2 = $bddV->prepare("SELECT * FROM boutique WHERE type = :type and categorie = :categorie ");
					$req2->execute(array('type' => $type, 'categorie' => $categorie));
					
					while($boutique = $req2->fetch()) {
						$nomtableDesignation = $boutique['nomBoutique']."-designation";
						
						$req3 = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE designation = :designation ");
						$req3->execute(array('designation' => $catalogue['designation']));
						
						while($design = $req3->fetch()) {
							$from = '../JCaisse/vitrine/uploads/'.$design['image'];
							$to = 'uploads/'.$design['image'];
							
							if ($design['image'] != '') {
								if (file_exists($from)) {
									if (!copy($from, $to)) {
										echo "";
									} else {
										$sql5="UPDATE `".$catalogueTypeCateg."` SET image = :image WHERE id = :id";
										$req5 = $bdd->prepare($sql5);
										$req5->execute(array(
											'image' => $design['image'],
											'id' => $catalogue['id']
										));
									}
								}
							}
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des images : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateImageByCodeBarre'])) {
			try {
				$sql1="SELECT * FROM `".$catalogueTypeCateg."` WHERE image = '' AND codeBarreDesignation != '' ";
				$req1 = $bdd->query($sql1);
				
				while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
					$req2 = $bddV->prepare("SELECT * FROM boutique WHERE type = :type AND categorie = :categorie ");
					$req2->execute(array('type' => $type, 'categorie' => $categorie));
					
					while($boutique = $req2->fetch()) {
						$nomtableDesignation = $boutique['nomBoutique']."-designation";
						
						$req3 = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE codeBarreDesignation = :codeBarreDesignation ");
						$req3->execute(array('codeBarreDesignation' => $catalogue['codeBarreDesignation']));
						
						while($design = $req3->fetch()) {
							$from = '../JCaisse/vitrine/uploads/'.$design['image'];
							$to = 'uploads/'.$design['image'];
							
							if ($design['image'] != '') {
								if (file_exists($from)) {
									if (!copy($from, $to)) {
										echo "";
									} else {
										$sql5="UPDATE `".$catalogueTypeCateg."` SET image = :image WHERE id = :id";
										$req5 = $bdd->prepare($sql5);
										$req5->execute(array(
											'image' => $design['image'],
											'id' => $catalogue['id']
										));
									}
								}
							}
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des images par code barre : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateImageToBoutiqueByDes'])) {
			try {
				$sql2="SELECT * FROM `aaa-boutique` WHERE type = :type AND categorie = :categorie AND vitrine = 1";
				$req2 = $bdd->prepare($sql2);
				$req2->execute(array('type' => $type, 'categorie' => $categorie));
				
				while($boutique = $req2->fetch(PDO::FETCH_ASSOC)) {
					$nomtableDesignation = $boutique['nomBoutique']."-designation";
					
					$sql3="SELECT * FROM `".$nomtableDesignation."` WHERE image = '' OR image IS NULL";
					$req3 = $bdd->query($sql3);
					
					while($design = $req3->fetch(PDO::FETCH_ASSOC)) {
						$sql1="SELECT * FROM `".$catalogueTypeCateg."` WHERE (image != '' OR image IS NOT NULL) AND designation = :designation";
						$req1 = $bdd->prepare($sql1);
						$req1->execute(array('designation' => $design['designation']));
						
						while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
							$from = 'uploads/'.$catalogue['image'];
							$to = '../JCaisse/vitrine/uploads/'.$catalogue['image'];
							
							if ($catalogue['image'] != '') {
								if (file_exists($from)) {
									if (!copy($from, $to)) {
										echo "";
									} else {
										$sql5="UPDATE `".$nomtableDesignation."` SET image = :image WHERE idDesignation = :idDesignation";
										$req5 = $bdd->prepare($sql5);
										$req5->execute(array(
											'image' => $catalogue['image'],
											'idDesignation' => $design['idDesignation']
										));
										
										$req50 = $bddV->prepare('UPDATE boutique SET upToDate = :up WHERE idBoutique = :idB');
										$req50->execute(array(
											'idB' => $boutique['idBoutique'],
											'up' => 1
										));
									}
								}
							}
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des images vers boutique par désignation : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateImageToBoutiqueByCBar'])) {
			try {
				$sql2="SELECT * FROM `aaa-boutique` WHERE type = :type AND categorie = :categorie AND vitrine = 1";
				$req2 = $bdd->prepare($sql2);
				$req2->execute(array('type' => $type, 'categorie' => $categorie));
				
				while($boutique = $req2->fetch(PDO::FETCH_ASSOC)) {
					$nomtableDesignation = $boutique['nomBoutique']."-designation";
					
					$sql3="SELECT * FROM `".$nomtableDesignation."` WHERE (image = '' OR image IS NULL) AND codeBarreDesignation != '' ";
					$req3 = $bdd->query($sql3);
					
					while($design = $req3->fetch(PDO::FETCH_ASSOC)) {
						$sql1="SELECT * FROM `".$catalogueTypeCateg."` WHERE (image != '' OR image IS NOT NULL) AND codeBarreDesignation = :codeBarreDesignation";
						$req1 = $bdd->prepare($sql1);
						$req1->execute(array('codeBarreDesignation' => $design['codeBarreDesignation']));
						
						while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
							$from = 'uploads/'.$catalogue['image'];
							$to = '../JCaisse/vitrine/uploads/'.$catalogue['image'];
							
							if ($catalogue['image'] != '') {
								if (file_exists($from)) {
									if (!copy($from, $to)) {
										echo "";
									} else {
										$sql5="UPDATE `".$nomtableDesignation."` SET image = :image WHERE idDesignation = :idDesignation";
										$req5 = $bdd->prepare($sql5);
										$req5->execute(array(
											'image' => $catalogue['image'],
											'idDesignation' => $design['idDesignation']
										));
										
										$req50 = $bddV->prepare('UPDATE boutique SET upToDate = :up WHERE idBoutique = :idB');
										$req50->execute(array(
											'idB' => $boutique['idBoutique'],
											'up' => 1
										));
									}
								}
							}
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des images vers boutique par code barre : " . $e->getMessage());
			}
		}
		if (isset($_POST['btnUpdateImageToVitrineByCBar'])) {
			try {
				$req2 = $bddV->prepare("SELECT * FROM boutique WHERE type = :type AND categorie = :categorie AND vitrine = :vitrine");
				$req2->execute(array('type' => $type, 'categorie' => $categorie, 'vitrine' => 1));
				
				while($boutique = $req2->fetch()) {
					$nomtableDesignation = $boutique['nomBoutique']."-designation";
					
					$req3 = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE (image = :image1 OR image = :image2) AND codeBarreDesignation != :codeBarre");
					$req3->execute(array('image1' => '', 'image2' => 'null', 'codeBarre' => ''));
					
					while($design = $req3->fetch()) {
						$sql1="SELECT * FROM `".$catalogueTypeCateg."` WHERE codeBarreDesignation = :codeBarreDesignation AND (image != '' OR image IS NOT NULL)";
						$req1 = $bdd->prepare($sql1);
						$req1->execute(array('codeBarreDesignation' => $design['codeBarreDesignation']));
						
						while($catalogue = $req1->fetch(PDO::FETCH_ASSOC)) {
							$from = 'uploads/'.$catalogue['image'];
							$to = '../JCaisse/vitrine/uploads/'.$catalogue['image'];
							
							if ($catalogue['image'] != '') {
								if (file_exists($from)) {
									if (!copy($from, $to)) {
										echo "";
									} else {
										$req50 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image = :image WHERE id = :id");
										$req50->execute(array(
											'image' => $catalogue['image'],
											'id' => $design['id']
										));
									}
								}
							}
						}
					}
				}
			} catch(PDOException $e) {
				die("Erreur lors de la mise à jour des images vers vitrine par code barre : " . $e->getMessage());
			}
		}
if (isset($_POST['btnModifier'])) {
		try {
			$id = $_POST["id"];
			$designation = $_POST["designation"];
			$uniteStock = $_POST["uniteStock"];
			$nbArticleUniteStock = $_POST["nbArticleUniteStock"];
			$prixUnitaire = $_POST["prix"];
			$categorie2 = $_POST["categorie2"];
			$prixuniteStock = $_POST["prixuniteStock"];
			$codeBarreDesignation = $_POST["codeBarreDesignation"];
			$codeBarreuniteStock = $_POST["codeBarreuniteStock"];
			$catalogueTypeCateg = $_POST["tab"];

			$sql="UPDATE `".$catalogueTypeCateg."` 
					SET designation = :designation,
						uniteStock = :uniteStock,
						categorie = :categorie,
						nbreArticleUniteStock = :nbArticleUniteStock,
						prix = :prix,
						prixuniteStock = :prixuniteStock,
						codeBarreDesignation = :codeBarreDesignation,
						codeBarreuniteStock = :codeBarreuniteStock
					WHERE id = :id";
			
			$req = $bdd->prepare($sql);
			$req->execute(array(
				'designation' => $designation,
				'uniteStock' => $uniteStock,
				'categorie' => $categorie2,
				'nbArticleUniteStock' => $nbArticleUniteStock,
				'prix' => $prixUnitaire,
				'prixuniteStock' => $prixuniteStock,
				'codeBarreDesignation' => $codeBarreDesignation,
				'codeBarreuniteStock' => $codeBarreuniteStock,
				'id' => $id
			));
		} catch(PDOException $e) {
			die("Erreur lors de la modification : " . $e->getMessage());
		}
	}
if (isset($_POST['btnSupprimer'])) {
		try {
			$id = $_POST["id"];
			$catalogueTypeCateg = $_POST["tab"];
			
			$sql="DELETE FROM `".$catalogueTypeCateg."` WHERE id = :id";
			$req = $bdd->prepare($sql);
			$req->execute(array('id' => $id));
		} catch(PDOException $e) {
			die("Erreur lors de la suppression : " . $e->getMessage());
		}
	}
if (isset($_POST['btnEliminerDoublons'])) {
		try {
			$id = $_POST["id"];
			$designation = $_POST["designation"];
			$idFusion = $_POST["idFusion"];
			$tabIdFusionDoub = $_POST["tabIdFusionDoub"];
			$tabIdFusionDoub = explode('-', $tabIdFusionDoub);
			$maxIdFusion = $idFusion;

			if ($tabIdFusionDoub != NULL) {
				$maxIdFusion = max($tabIdFusionDoub);
			}
			
			$sql="SELECT * FROM `".$catalogueTypeCateg."` WHERE designation = :designation AND id != :id";
			$req = $bdd->prepare($sql);
			$req->execute(array('designation' => $designation, 'id' => $id));
			
			while($t = $req->fetch(PDO::FETCH_ASSOC)) {
				$sql2="SELECT idFusion, nomBoutique FROM `".$catalogueTypeCateg."` A
						INNER JOIN `aaa-boutique` B ON A.idBoutique = B.idBoutique
						WHERE A.idFusion = :idFusion";
				
				$req2 = $bdd->prepare($sql2);
				$req2->execute(array('idFusion' => $t['idFusion']));
				$t2 = $req2->fetch(PDO::FETCH_ASSOC);
				
				$nomtableDesignation = $t2['nomBoutique']."-designation";

				$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id = :id";
				$req1 = $bdd->prepare($sql1);
				$req1->execute(array('id' => $t["id"]));

				$sql21="UPDATE `".$nomtableDesignation."` SET idFusion = :maxIdFusion WHERE idDesignation = :idDesignation";
				$req21 = $bdd->prepare($sql21);
				$req21->execute(array(
					'maxIdFusion' => $maxIdFusion,
					'idDesignation' => $t['idDesignation']
				));
			}
		} catch(PDOException $e) {
			die("Erreur lors de l'élimination des doublons : " . $e->getMessage());
		}
	}
if (isset($_POST['btnFusion'])) {
		try {
			$id = $_POST["id"];
			$catalogueTypeCateg = $_POST["tab"];
			$designation = $_POST["designation"];
			$uniteStock = $_POST["uniteStock"];
			$nbArticleUniteStock = $_POST["nbreArticleUniteStock"];
			$prixUnitaire = $_POST["prix"];
			$categorie = $_POST["categorie"];
			$prixuniteStock = $_POST["prixuniteStock"];
			$idFusion = $_POST["idFusion"];
			$tabIdFusionF = $_POST["tabIdFusionF"];
			$tabIdFusionF = explode('-', $tabIdFusionF);
			$maxIdFusion = 0;

			if ($tabIdFusionF != NULL) {
				$maxIdFusion = max($tabIdFusionF);
			}

			$sql="UPDATE `".$catalogueTypeCateg."` 
					SET designation = :designation,
						uniteStock = :uniteStock,
						categorie = :categorie,
						nbreArticleUniteStock = :nbreArticleUniteStock,
						prix = :prix,
						idFusion = :idFusion,
						prixuniteStock = :prixuniteStock
					WHERE id = :id";
			
			$req = $bdd->prepare($sql);
			$req->execute(array(
				'designation' => $designation,
				'uniteStock' => $uniteStock,
				'categorie' => $categorie,
				'nbArticleUniteStock' => $nbArticleUniteStock,
				'prix' => $prixUnitaire,
				'idFusion' => $idFusion,
				'prixuniteStock' => $prixuniteStock,
				'id' => $id
			));

			$sql="SELECT * FROM `".$catalogueTypeCateg."` WHERE designation = :designation AND id != :id";
			$req = $bdd->prepare($sql);
			$req->execute(array('designation' => $designation, 'id' => $id));
			
			while($t = $req->fetch(PDO::FETCH_ASSOC)) {
				$sql2="SELECT idFusion, nomBoutique FROM `".$catalogueTypeCateg."` A
						INNER JOIN `aaa-boutique` B ON A.idBoutique = B.idBoutique
						WHERE A.idFusion = :idFusion";
				
				$req2 = $bdd->prepare($sql2);
				$req2->execute(array('idFusion' => $t["idFusion"]));
				$t2 = $req2->fetch(PDO::FETCH_ASSOC);
				
				$nomtableDesignation = $t2['nomBoutique']."-designation";

				$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id = :id";
				$req1 = $bdd->prepare($sql1);
				$req1->execute(array('id' => $t["id"]));

				$sql21="UPDATE `".$nomtableDesignation."` SET idFusion = :idFusion WHERE idDesignation = :idDesignation";
				$req21 = $bdd->prepare($sql21);
				$req21->execute(array(
					'idFusion' => $idFusion,
					'idDesignation' => $t['idDesignation']
				));
			}
		} catch(PDOException $e) {
			die("Erreur lors de la fusion : " . $e->getMessage());
		}
	}
if(isset($_POST["btnUploadImg"])) {
		try {
			function resizeImage($resourceType,$image_width,$image_height) {
				$resizeWidth = 150;
				$resizeHeight = 150;
				$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
				imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
				return $imageLayer;
			}

			$imageProcess = 0;
			if(is_array($_FILES)) {
				$fileName = $_FILES['file']['tmp_name'];
				$sourceProperties = getimagesize($fileName);
				$resizeFileName = time();
				$uploadPath = "uploads/";
				$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$uploadImageType = $sourceProperties[2];
				$sourceImageWidth = $sourceProperties[0];
				$sourceImageHeight = $sourceProperties[1];

				$id = $_POST["idD"];
				$catalogueTypeCateg = $_POST["tab"];
				
				switch ($uploadImageType) {
					case IMAGETYPE_JPEG:
						$resourceType = imagecreatefromjpeg($fileName);
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
						$fileNameNew=$resizeFileName.'.'. $fileExt;
						imagedestroy($imageLayer);
						imagedestroy($resourceType);
						
						$sql="UPDATE `".$catalogueTypeCateg."` SET image = :image WHERE id = :id";
						$req = $bdd->prepare($sql);
						$req->execute(array('image' => $fileNameNew, 'id' => $id));
						break;
					case IMAGETYPE_PNG:
						$resourceType = imagecreatefrompng($fileName);
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
						$fileNameNew=$resizeFileName.'.'. $fileExt;
						imagedestroy($imageLayer);
						imagedestroy($resourceType);
						
						$sql="UPDATE `".$catalogueTypeCateg."` SET image = :image WHERE id = :id";
						$req = $bdd->prepare($sql);
						$req->execute(array('image' => $fileNameNew, 'id' => $id));
						break;
					default:
						$imageProcess = 0;
						break;
				}
			}
		} catch(PDOException $e) {
			die("Erreur lors de l'upload d'image : " . $e->getMessage());
		}
	//}
		$fileError=$_FILES['file']['error'];
		$fileType=$_FILES['file']['type'];

		$fileExt=explode('.',$fileName);
		$fileActualExt=strtolower(end($fileExt));

		$allowed= array('jpg','jpeg','png' );

		if (in_array($fileActualExt,$allowed)) {
				if ($fileError===0) {
						if ($fileSize < 5000000) {
							$fileNameNew=uniqid('',true).".".$fileActualExt;
							$fileDestination='uploads/'.$fileNameNew;
							move_uploaded_file($fileTmpName,$fileDestination);

							// Save to database
							$id = $_POST["id"];
							$catalogueTypeCateg = $_POST["tab"];
							$sql="UPDATE `".$catalogueTypeCateg."` SET image = :image WHERE id = :id";
							$req = $bdd->prepare($sql);
							$req->execute(array('image' => $fileNameNew, 'id' => $id));
							// Save to database
						} else {
								echo " 	<script type='text/javascript'> alert('taille trop grande')	</script>";
						}

				} else {
						//echo "erreur lors de l'upload ";
						echo "	<script type='text/javascript'> alert('erreur lors de lupload') </script>";
				}

		} else {
						echo "	<script type='text/javascript'> alert('type non pris en charge')</script>";
		}
	}
	/**************** DECLARATION DES ENTETES *************/
if(isset($_POST["btnSupImg"])) {
	try {
		if(unlink("uploads/".$_POST['image'])) {
			$id = $_POST["id"];
			$fileNameNew = '';
			$sql="UPDATE `".$catalogueTypeCateg."` SET image = :image WHERE id = :id";
			$req = $bdd->prepare($sql);
			$req->execute(array('image' => $fileNameNew, 'id' => $id));
		}
	} catch(PDOException $e) {
		die("Erreur lors de la suppression d'image : " . $e->getMessage());
	}
}
if(isset($_POST["btnUploadImgCategorie"])) {
		try {
			function resizeImage($resourceType,$image_width,$image_height) {
				$resizeWidth = 150;
				$resizeHeight = 150;
				$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
				imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
				return $imageLayer;
			}

			$imageProcess = 0;
			if(is_array($_FILES)) {
				$fileName = $_FILES['file']['tmp_name'];
				$sourceProperties = getimagesize($fileName);
				$resizeFileName = time();
				$uploadPath = "uploads/categorie/";
				$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$uploadImageType = $sourceProperties[2];
				$sourceImageWidth = $sourceProperties[0];
				$sourceImageHeight = $sourceProperties[1];

				$idCategorie = $_POST["idCategorie"];
				
				switch ($uploadImageType) {
					case IMAGETYPE_JPEG:
						$resourceType = imagecreatefromjpeg($fileName);
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
						$fileNameNew=$resizeFileName.'.'. $fileExt;
						imagedestroy($imageLayer);
						imagedestroy($resourceType);
						
						$sql="UPDATE `".$categorieTypeCateg."` SET image = :image WHERE id = :id";
						$req = $bdd->prepare($sql);
						$req->execute(array('image' => $fileNameNew, 'id' => $idCategorie));
						break;
					case IMAGETYPE_PNG:
						$resourceType = imagecreatefrompng($fileName);
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
						$fileNameNew=$resizeFileName.'.'. $fileExt;
						imagedestroy($imageLayer);
						imagedestroy($resourceType);
						
						$sql="UPDATE `".$categorieTypeCateg."` SET image = :image WHERE id = :id";
						$req = $bdd->prepare($sql);
						$req->execute(array('image' => $fileNameNew, 'id' => $idCategorie));
						break;
					default:
						$imageProcess = 0;
						break;
				}
			}
		} catch(PDOException $e) {
			die("Erreur lors de l'upload d'image de catégorie : " . $e->getMessage());
		}
	}
if(isset($_POST["btnSupImgCategorie"])) {
	try {
		if(unlink("uploads/categorie/".$_POST['image'])) {
			$idCategorie = $_POST["idCategorie"];
			$fileNameNew = '';
			$sql="UPDATE `".$categorieTypeCateg."` SET image = :image WHERE id = :id";
			$req = $bdd->prepare($sql);
			$req->execute(array('image' => $fileNameNew, 'id' => $idCategorie));
		}
	} catch(PDOException $e) {
		die("Erreur lors de la suppression d'image de catégorie : " . $e->getMessage());
	}
}
/*
if (isset($_POST['btnModifierCategorie'])) {
			$id         =@$_POST["id"];
			$nomCategorie       =@$_POST["nomCategorie"];
			$categorieParent          =@$_POST["categorieParent"];
			$categorieTypeCateg     =@$_POST["tab"];

			$sql="update `".$categorieTypeCateg."` set
			 nomCategorie='".mysql_real_escape_string($nomCategorie)."',
			categorieParent='".mysql_real_escape_string($categorieParent)."'
			where id=".$id;

			$res=@mysql_query($sql)or die ("modification cqtegorie ".mysql_error());
		}

if (isset($_POST['btnSupprimerCategorie'])) {
				$id         =@$_POST["id"];
				$categorieRemplacant         =@$_POST["categorieRemplacant"];

				$sql="DELETE FROM `".$categorieTypeCateg."` WHERE id=".$id;
				$res=@mysql_query($sql) or die ("suppression impossible categorie".mysql_error());
			}*/
if (isset($_POST['btnAjouterCategorie'])) {
		try {
			$categorie = $_POST["categorie"];
			$categorieParent = $_POST["categorieParent"];
			
			$sql3="INSERT INTO `".$categorieTypeCateg."`(nomCategorie, categorieParent) VALUES (:nomCategorie, :categorieParent)";
			$req3 = $bdd->prepare($sql3);
			$req3->execute(array(
				'nomCategorie' => $categorie,
				'categorieParent' => $categorieParent
			));
		} catch(PDOException $e) {
			die("Erreur lors de l'ajout de catégorie : " . $e->getMessage());
		}
	}          
if (isset($_POST['subImport1'])) {
		try {
			$fname = $_FILES['fileImport']['name'];
			if ($_FILES["fileImport"]["size"] > 0) {
				$fileName = $_FILES['fileImport']['tmp_name'];
				$handle = fopen($fileName, "r");
				$headers = fgetcsv($handle, 1000, ";");

				while (($data = fgetcsv($handle, 1000, ";")) != FALSE) {
					$reference = htmlspecialchars(trim($data[0]));
					$categorie = htmlspecialchars(trim($data[1]));
					$uniteS = htmlspecialchars(trim($data[2]));
					$nbreAuniteS = $data[3];
					$prixU = $data[4];
					$prixUS = $data[5];
					$quantite = $data[6];
					
					// Insert into catalogue
					$sql3 = "INSERT INTO `".$catalogueTypeCateg."`(designation, uniteStock, nbreArticleUniteStock, prix, prixuniteStock, categorie, classe)
								VALUES (:designation, :uniteStock, :nbreArticleUniteStock, :prix, :prixuniteStock, :categorie, 0)";
					
					$req3 = $bdd->prepare($sql3);
					$req3->execute(array(
						'designation' => $reference,
						'uniteStock' => $uniteS,
						'nbreArticleUniteStock' => $nbreAuniteS,
						'prix' => $prixU,
						'prixuniteStock' => $prixUS,
						'categorie' => $categorie
					));

					// Check if category exists, insert if not
					$sql12 = "SELECT * FROM `".$categorieTypeCateg."` WHERE nomcategorie = :nomcategorie";
					$req12 = $bdd->prepare($sql12);
					$req12->execute(array('nomcategorie' => $categorie));
					
					if(!$req12->fetch()) {
						if($categorie) {
							$sql = "INSERT INTO `".$categorieTypeCateg."` (nomcategorie) VALUES (:nomcategorie)";
							$req = $bdd->prepare($sql);
							$req->execute(array('nomcategorie' => $categorie));
						}
					}
				}
				fclose($handle);
			}
		} catch(PDOException $e) {
			die("Erreur lors de l'import CSV : " . $e->getMessage());
		}
	}

	if (isset($_GET['l']) && $_GET['l']==7) {

	  $fname=$_FILES['fileImport']['name'];
	  if ($_FILES["fileImport"]["size"] > 0) {
	    $fileName=$_FILES['fileImport']['tmp_name'];
	    $handle=fopen($fileName,"r");
	    $tabDes;
	    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

	     $tabDes[]=$data;

	    }
	    //return  $tabDes;
	    //var_dump($tabDes);
	    fclose($handle);

	  }

	}
//}
if(isset($_POST["Export"])){
      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=data-Reference.csv');
      $delimiter = ";";
      $output = fopen("php://output", "w");
      $fields=array('REFERENCE','CATEGORIE', 'UNITE-STOCK','NBRES-ARTICLES-UNITE-STOCK',
                    'PRIX-UNITAIRE', 'PRIX-UNITE-STOCK', 'QUANTITE-STOCK');
      fputcsv($output,$fields, $delimiter );
      fclose($output); exit;

 }

?>

<?php //require('enteteCatalogue.php');
		 require('entetehtml.php');
 ?>

<body >
<?php
/**************** MENU HORIZONTAL *************/
 if($_SESSION['profil']=="Editeur catalogue")
  require('header-editeur.php');
 else
  require('header.php');

/**************** Ajouter une catégorie *************/

/**************** *************  *************/
?>
<div class="container">
	<div class="jumbotron noImpr">
		<div class="row" align="center">
			<?php
			 // $sql3='SELECT * from  `aaa-catalogue-alimentaire-detaillant`  order by id desc';
			 // $sql3="SELECT * from  `catalogueTotal`  where id=".$_GET['id']."";
			 $typeCategorie=$tab0['type']."-".$tab0['categorie'];
			 $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
			 $sql3="SELECT * FROM `".$catalogueTypeCateg."`";
			 $req3 = $bdd->query($sql3); ?>
			<div class='row '>
				<?php if ($req3->rowCount() > 0): ?>
					<form name="formulairePersonnel"   method="post" >
						<div class='row'>
							
								<input type="hidden" name="" value="">
								<button type="submit" class="btn btn-success" name="btnUpdateCatalogue">
											<i class="glyphicon glyphicon-plus"></i>Mettre à jour le catalogue
								</button>
							
							
								<button type="submit" class="btn btn-warning" name="btnUpdateCodeBarreCata">
											<i class="glyphicon glyphicon-plus"></i>Mettre à jour Code Barre
								</button>
							
							
								<button type="submit" class="btn btn-danger" name="btnUpdateCodeBarreBou">
										<i class="glyphicon glyphicon-plus"></i>update Code Barre Bout
								</button>
							
							
								<button type="submit" class="btn btn-primary" name="btnUpdatePrix">
										<i class="glyphicon glyphicon-plus"></i>update Price
								</button>
							
						</div>
						<div class='row'  style="margin: 10px;">
							
								<button type="submit" class="btn btn-success" name="btnUpdateImage">
										<i class="glyphicon glyphicon-plus"></i>update image by design
								</button>
							
							
							
								<button type="submit" class="btn btn-danger" name="btnUpdateImageToBoutiqueByDes">
										<i class="glyphicon glyphicon-plus"></i>update image boutique
								</button>
							  
						</div>
						<div class='row'  style="margin: 10px;">
							
								<button type="submit" class="btn btn-success" name="btnUpdateImageByCodeBarre">
										<i class="glyphicon glyphicon-plus"></i>update image by barre-code
								</button>
							
							
								<button type="submit" class="btn btn-danger" name="btnUpdateImageToBoutiqueByCBar">
										<i class="glyphicon glyphicon-plus"></i>update image boutique by C-B
								</button>

								<button type="submit" class="btn btn-danger" name="btnUpdateImageToVitrineByCBar">
										<i class="glyphicon glyphicon-plus"></i>update image to Vitrine by C-B
								</button>
							
						</div>
						</form>
				<?php else: ?>
						<form name="formulairePersonnel"   method="post" >
							<input type="hidden" name="" value="">
							<button type="submit" class="btn btn-success" name="btnGenererCatalogue">
									<i class="glyphicon glyphicon-plus"></i>Initialiser catalogue
							</button>
						</form>
				<?php endif; ?>
			</div>
		</div>
		<?php
			$sql00="SELECT COUNT(*) as total_row FROM `".$catalogueTypeCateg."`";
			$req00 = $bdd->query($sql00);
			$total_rows = $req00->fetch(PDO::FETCH_ASSOC);

			$sql01="SELECT COUNT(*) as total_row FROM `".$catalogueTypeCateg."` WHERE image != ''";
			$req01 = $bdd->query($sql01);
			$total_img = $req01->fetch(PDO::FETCH_ASSOC);
		?>
		<h2>Catalogue : <?php echo $tab0['nom']; echo'<div class="row" align="center"><span id="" style="margin-top: -20px;margin-right: -15px;border-radius: 50%;background: red;color: white;"
															 class="badge bg-warning">'.$total_img['total_row'].' Images sur'.$total_rows['total_row'].'</span></div>'; ?>  

		</h2>
		<form class="form-horizontal" method="post" name="upload_excel"  enctype="multipart/form-data">
                <input type="submit" name="Export" class="btn btn-success" value="Générer CSV catalogue"/>
        </form>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        <form class="form-inline pull-right"  method="post" enctype="multipart/form-data">
            <input type="file" id="importInput" name="fileImport"
                                data-toggle="modal" onChange="loadCSV()" required>
            <button type="submit" name="subImport1" value="Importer " class="btn btn-success">Importer un catalogue</button>
        </form>

	</div>
	<?php
				/*$sql16="SELECT s.id, t . * FROM `".$catalogueTypeCateg."` s	JOIN
					(	SELECT designation, categorie, COUNT( * ) AS qty
					FROM `".$catalogueTypeCateg."`
					GROUP BY designation, categorie
					HAVING COUNT( * ) >=1
					)t ON s.designation = t.designation
					AND s.categorie <> t.categorie";
					$req16 = $bdd->query($sql16);
					if($req16) {
						while($ligne16 = $req16->fetch(PDO::FETCH_ASSOC)) {
							$fusions[] = $ligne16['id'];
						}
					}
						$req='SELECT s.id, t . * FROM `aaa-catalogue-pharmacie-detaillant` s JOIN (SELECT *, COUNT( * ) AS qty FROM `aaa-catalogue-pharmacie-detaillant` GROUP BY designation HAVING COUNT( * ) >=1 )t ON s.designation = t.designation AND (s.categorie <> t.categorie OR s.`forme`<>t.`forme` OR s.`tableau` <> t.`tableau` OR s.`prixSession` <> t.`prixSession` OR s.`prixPublic` <> t.`prixPublic` OR s.`codeBarreDesignation` <> t.`codeBarreDesignation` )';
						$req='SELECT COUNT( DISTINCT s.designation) as total_row, t . * FROM `aaa-catalogue-pharmacie-detaillant` s 
						JOIN (SELECT *, COUNT( * ) AS qty FROM `aaa-catalogue-pharmacie-detaillant`
						   GROUP BY designation HAVING COUNT( * ) >=1 )t ON s.designation = t.designation AND
							(s.categorie <> t.categorie OR s.`forme`<>t.`forme` OR s.`tableau` <> t.`tableau` 
							OR s.`prixSession` <> t.`prixSession` OR s.`prixPublic` <> t.`prixPublic`
							 OR s.`codeBarreDesignation` <> t.`codeBarreDesignation` ) ORDER BY `t`.`designation` ASC';*/
	 ?>

	
</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/
?>
		<div class="container-fluid">
			<div class="" align="center">
				        <ul class="nav nav-tabs">
				          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">REFERENCE</a></li>
						  <li><a data-toggle="tab" href="#CATEGORIE">CATEGORIE</a></li>
						  <li><a data-toggle="tab" href="#DOUBLON" onclick="newTabDoubClickBout(<?php echo $_GET['i'] ?>)">DOUBLON</a></li>
						  <li><a data-toggle="tab" href="#FUSION" onclick="newTabFusClickB(<?php echo $_GET['i'] ?>)">FUSION</a></li>
									
				        </ul>
				        <div class="tab-content">
				            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
																			
											<!-- <div id="modifierDesignation"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header" style="padding:35px 50px;">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'un Produit </b></h4>
														</div>
														<div class="modal-body" style="padding:40px 50px;">
															<form role="form" >
																<input type="hidden" id="id_Mdf" />
																<input type="hidden" id="idDesignation_Mdf" />
																<input type="hidden" id="ordre_Mdf" />
																<div class="form-group">
																	<label for="reference">REFERENCE <font color="red">*</font></label>
																	<input type="text" class="form-control" id="designation_Mdf"  required />
																</div>
																<div class="form-group">
																	<label for="categorie"> CATEGORIE <font color="red">*</font></label>
																	<select class="form-control" id="categorie_Mdf"  >
																		<option selected ></option>
																			<?php 
																			$sql11="SELECT * FROM `".$categorieTypeCateg."`";
																			$req11 = $bdd->query($sql11);
																			while($ligne2 = $req11->fetch(PDO::FETCH_ASSOC)) {
																				echo'<option  value= "'.$ligne2['nomCategorie'].'">'.$ligne2['nomCategorie'].'</option>';
																			}
																			?>
																	</select>
																</div>
																<div class="form-group">
																	<label for="uniteStock"> UNITE STOCK <font color="red">*</font></label>
																	<select class="form-control" id="uniteStock_Mdf"  >
																			<option selected ></option>
																			<?php 
																			$sql11="SELECT * FROM `aaa-unitestock`";
																			$req11 = $bdd->query($sql11);
																			while($ligne2 = $req11->fetch()) {
																				echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
																			}
																			?>
																	</select>
																</div>
																<div class="form-group" >
																	<label for="nbArticleUniteStock">NOMBRE ARTICLE(S) PAR UNITE STOCK <font color="red">*</font> </label>
																	<input type="number" class="form-control"   id="nbArticleUniteStock_Mdf"  required />
																</div>
																<div class="form-group" >
																	<label for="prix">PRIX UNITAIRE </label>
																	<input type="number" class="form-control"   id="prix_Mdf"  />
																</div>
																<div class="form-group" >
																	<label for="prixuniteStock">PRIX UNITE STOCK</label>
																	<input type="number" class="form-control"   id="prixuniteStock_Mdf"  />
																</div>
																	<div class="form-group" align="right">
																<font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
																	<input type="button" class="boutonbasic"  id="btnModifierDesignation" value="MODIFIER  >>"/>
																	</div>
																</form>
														</div>
													</div>
												</div>
												</div>
 											-->
											<div id="supprimerDesignation"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header" style="padding:35px 50px;">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression d\'un Produit </b></h4>
														</div>
														<div class="modal-body" style="padding:40px 50px;">
															<form role="form" >
																<input type="hidden" id="id_Spm" />
																<input type="hidden" id="idDesignation_Spm" />
																<input type="hidden" id="ordre_Spm" />
																<div class="form-group">
																	<label for="reference">REFERENCE : </label>
																	<span  id="designation_Spm"></span>
																</div>
																<div class="form-group">
																	<label for="categorie"> CATEGORIE : </label>
																	<span id="categorie_Spm"></span>
																</div>
																<div class="form-group">
																	<label for="uniteStock"> UNITE STOCK : </label>
																	<span id="uniteStock_Spm"></span>
																</div>
																<div class="form-group" >
																	<label for="nbArticleUniteStock">NOMBRE ARTICLE(S) PAR UNITE STOCK : </label>
																	<span id="nbArticleUniteStock_Spm"></span>
																</div>
																<div class="form-group" >
																	<label for="prix">PRIX UNITAIRE : </label>
																	<span id="prix_Spm"></span>
																</div>
																<div class="form-group" >
																	<label for="prixuniteStock">PRIX UNITE STOCK : </label>
																	<span id="prixuniteStock_Spm"></span>
																</div>
																	<div class="form-group" align="right">
																	<input type="button" class="boutonbasic"  id="btnSupprimerDesignation" value="SUPPRIMER  >>"/>
																	</div>
																</form>
														</div>
													</div>
												</div>
											</div>

											<div id="imageNDesignationC"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
													<div class="modal-header" style="padding:35px 50px;">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>
													</div>
													<div class="modal-body" style="padding:40px 50px;">
														<form   method="post" enctype="multipart/form-data" >
															<input type="hidden" name="id" value=" <?php echo $_GET['i'];?>" >
															<input type="hidden" id="idDN" name="idD" value="" >
															<input style="display:none" type="text" name="id" id="id_Upd_NvC" />
															<div class="form-group" >
															<br />
																<input type="file" name="file" />
															</div>
															<div class="form-group" align="right">
																<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Upload >>"/>
															</div>
														</form>
													</div>
													</div>
												</div>
											</div>

											<div id="imageEDesignationC"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
													<div class="modal-header" style="">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
													</div>
													<div class="modal-body" style="">
													<img  width="50%" height="30%" alt="" id="imgsrc_UpdC" />
													<form   method="post" enctype="multipart/form-data">														
														<input type="hidden" id="idDE" name="idD" value="" >
														<input  type="hidden" name="id" id="id_Upd_ExC" />
														<input  type="hidden" name="image" id="img_Upd_ExC" />
														<div class="form-group" >
														<br />
															<input type="file" name="file" />
														</div>
														<div class="form-group" align="right">
															<input type="submit" class="boutonbasic"  name="btnSupImg" value="Suprimer >>"/>
															<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Modifier >>"/>
														</div>
													</form>
													</div>
													</div>
												</div>
											</div>
											<div class="table-responsive">
												<label class="pull-left" for="nbEntreeBO">Nombre entrées </label>
												<select class="pull-left" id="nbEntreeBO">
												<optgroup>
													<option value="10">10</option>
													<option value="20">20</option>
													<option value="50">50</option> 
												</optgroup>       
												</select>
												<input class="pull-right" type="text" name="" id="searchInputBO" placeholder="Rechercher...">
												<input type="hidden" id="idCDBO" value="<?php echo $tab0['id']; ?>">
												<div id="resultsProductsBO"></div>
											</div>  											
								
							</div>
							<div class="tab-pane fade " id="CATEGORIE">
										<!-- Popup pour  eliminer tout  doublon -->
										
										<!-- Popup pour  eliminer tout  doublon -->
										<button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter une catégorie</button>
										<div id="categorieModal" class="modal fade " role="dialog">

											<div class="modal-dialog">

												<div class="modal-content">

													<div class="modal-header">

														<button type="button" class="close" data-dismiss="modal">&times;</button>

														<h4 class="modal-title">Ajout d'une Catégorie</h4>

													</div>

													<div class="modal-body">

													<form role="form" class=" formulaire2" name="formulaire2" method="post" >

															<b>NOM CATEGORIE <font color="red">*</font> </b>
															<input type="text" class="form-control" placeholder="Entrez le nom de la catégorie" id="categorie1" name="categorie" size="35" value="" required autofocus="" />

															<b>CATEGORIE PARENT <font color="red">*</font> </b>
															<?php 
															$selC="";
															echo '<select class="form-control" name="categorieParent" >
																<option selected value= "0">Sans Parent</option>
																<option   value= "" ></option>';
																$sql111="SELECT * FROM `".$categorieTypeCateg."`";
																$req111 = $bdd->query($sql111);
																while($op = $req111->fetch(PDO::FETCH_ASSOC)){
																	echo "<option  value='".$op["nomCategorie"]."' > ".$op["nomCategorie"].'</option>';
																}
															echo '</select></span></td>'; ?>

															</br>

														<div align='right'>

															<font color="red" >Les champs qui ont (*) sont obligatoires</font><br />

															<input type="submit" class="boutonbasic" name="btnAjouterCategorie" value="AJOUTER  >>">

															

														</div>

														</form>

													</div>

												</div>

											</div>

										</div>
											<div id="imageNCategorieC"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
													<div class="modal-header" style="padding:35px 50px;">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>
													</div>
													<div class="modal-body" style="padding:40px 50px;">
														<form   method="post" enctype="multipart/form-data" >
															<input type="hidden" name="id" value=" <?php echo $_GET['i'];?>" >
															<input type="hidden" id="idCategN" name="idCategorie" value="" >
															<input style="display:none" type="text" name="id" id="id_Upd_NvCateg" />
															<div class="form-group" >
															<br />
																<input type="file" name="file" />
															</div>
															<div class="form-group" align="right">
																<input type="submit" class="boutonbasic"  name="btnUploadImgCategorie" value="Upload >>"/>
															</div>
														</form>
													</div>
													</div>
												</div>
											</div>

											<div id="imageECategorieC"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
													<div class="modal-header" style="">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
													</div>
													<div class="modal-body" style="">
													<img  width="50%" height="30%" alt="" id="imgsrc_UpdCategorie" />
													<form   method="post" enctype="multipart/form-data">														
														<input type="hidden" id="idCategorie" name="idCategorie" >
														<input  type="hidden" name="id" id="idCatalog" />
														<input  type="hidden" name="image" id="img_Upd_ExCategorie" />
														<div class="form-group" >
														<br />
															<input type="file" name="file" />
														</div>
														<div class="form-group" align="right">
															<input type="submit" class="boutonbasic"  name="btnSupImgCategorie" value="Suprimer >>"/>
															<input type="submit" class="boutonbasic"  name="btnUploadImgCategorie" value="Modifier >>"/>
														</div>
													</form>
													</div>
													</div>
												</div>
											</div>
											<div id="supprimerCategorie"  class="modal fade " role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header" style="padding:35px 50px;">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression d\'un Produit </b></h4>
														</div>
														<div class="modal-body" style="padding:40px 50px;">
															<form role="form" action="#">
																<input type="hidden" id="id_SpmCatetyp" />
																<input type="hidden" id="idCategorie_Spm" />
																<input type="hidden" id="ordre_SpmCateg" />
																
																<div class="form-group">
																	<label for="categorie"> CATEGORIE : </label>
																	<span id="categorie_SpmB"></span>
																</div>
																<div class="form-group">
																	<label for="uniteStock"> CATEGORIE PARENT : </label>
																	<span id="categorieP_SpmB"></span>
																</div>
																<div class="form-group">
																	<label for="uniteStock"> NOM CATEGORIE DES PRODUITS CONCERNES : </label>
																	<?php
																		$selC='<td><span style="color:blue;" >
																			<select class="form-control"  name="categorieRemplacant" id="categorieRempl">
																				<option   value= "SANS CATEGORIE" >SANS CATEGORIE</option>';
																				$sql111="SELECT * FROM `".$categorieTypeCateg."` ORDER BY `nomCategorie` ASC";
																				$req111 = $bdd->query($sql111);
																				while($op = $req111->fetch(PDO::FETCH_ASSOC)){
																					$selC=$selC."<option  value='".$op["nomCategorie"]."' > ".$op["nomCategorie"].'</option>';
																				}
																			$selC=$selC.'</select></span></td>';
																			echo $selC;
																		?>
																	</div>
																	<div class="form-group" align="right">
																	<input type="button" class="boutonbasic"  id="btnSupprimerCategorie" value="SUPPRIMER  >>"/>
																	</div>
																</form>
														</div>
													</div>
												</div>
											</div>

											<div class="table-responsive">
												<label class="pull-left" for="nbEntreeBC">Nombre entrées </label>
												<select class="pull-left" id="nbEntreeBC">
												<optgroup>
													<option value="10">10</option>
													<option value="20">20</option>
													<option value="50">50</option> 
												</optgroup>       
												</select>
												<input class="pull-right" type="text" name="" id="searchInputBC" placeholder="Rechercher...">
												<input type="hidden" id="idCDCat" value="<?php echo $tab0['id']; ?>">
												<div id="resultsProductsBC"></div>
											</div> 
										<!-- Fin Popup pour eliminer tout doublon -->
				                   				<!--  <?php
												$sql15="SELECT * from  `".$categorieTypeCateg."` ";
												if($res15=mysql_query($sql15)){ ?>
									                   	<table id="tableCategorie" class="display" border="1" width="100%" align="left"  >
															<thead>
																<tr>
																	<th>ORDRE</th>
																	<th>CATEGORIE</th>
																	<th>CATEGORIE PARENT</th>
																	<th>OPERATION</th>
																</tr>
															</thead>
															<tfoot>
																<tr>
																	<th>ORDRE</th>
																	<th>CATEGORIE</th>
																	<th>CATEGORIE PARENT</th>
																	<th>OPERATION</th>
																</tr>
															</tfoot>
														</table>

														 <script type="text/javascript">
															$(document).ready(function() {
																$("#tableCategorie").dataTable({
																"bProcessing": true,
																"sAjaxSource": "ajax/catalogueDetailsCategoriesAjax.php?id=<?php echo $tab0['id']; ?>",
																"aoColumns": [
																		{ mData: "0" } ,
																		{ mData: "1" },
																		{ mData: "2" },
																		{ mData: "3" }
																	],

																});
															});
														</script>
												<?php } ?> -->
							</div>
							<div class="tab-pane fade " id="DOUBLON">
							
								
									<!-- Popup pour  eliminer tout  doublon -->
										
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#elToutDoub"> Eliminer tous doublon</button>
											
										<div id="elToutDoub"  class="modal fade" tabindex="-1"   role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header" style="padding:35px 50px;">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Eliminer doublons </b></h4>
													</div>
													<div class="modal-body" style="padding:40px 50px;">
															<form name="formulairePersonnel" method="post"  >
																<h4>Voulez-vous vraiment eliminer tous les doublons</h4>
																<div class="modal-footer row">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
																		<button type="button" name="btnAjouterCataloque" class="btn btn-primary"
																			<?php echo 'onclick="eli_tousDoub_B('.$_GET['i'].')" '; ?> >Enregistrer</button>
																</div>
															</form>    
													</div>
												</div>
											</div>
										</div>
									<!-- Fin Popup pour eliminer tout doublon -->

										<!-- Popup pour doublon -->
										<div id="popD"  class="modal fade bd-example-modal-xl" tabindex="-1"   role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header" style="padding:35px 50px;">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Eliminer doublons </b></h4>
													</div>
													<div class="modal-body" style="padding:40px 50px;">
														<form name="formulaireInitialStock" method="post" >
															<input type="hidden" name="id" id="idDo" value="">
															<input type="hidden" name="ordre" id="ordreTableDo" value="" />
															<input type="hidden" name="idCT" id="idCT" value="" />
															
															<table id="tableDo"  border="1">
															<thead><tr>
																		<th>REFERENCE</th>
																		<th>FUSION</th>
																		<th>CATEGORIE</th>
																		<th>FORME</th>
																		<th>TABLEAU</th>
																		<th>PRIX SESSION</th>
																		<th>PRIX PUBLIC</th>
																	</tr>
															</thead>
															<tfoot><tr>
																	<th>REFERENCE</th>
																	<th>FUSION</th>
																	<th>CATEGORIE</th>
																	<th>FORME</th>
																	<th>TABLEAU</th>
																	<th>PRIX SESSION</th>
																	<th>PRIX PUBLIC</th>
																	</tr>
															</tfoot>
															</table>
															<script type="text/javascript">
															/*  $(document).ready(function() {
																$("#tableDo").dataTable({
																retrieve: true,
																paging: false
																});
															});*/
															</script>
															<!--
															<input type="hidden" name="idFusion" value="'.$tab3["idFusion"].'"/>
															<input type="hidden" name="idDesignation" value="'.$tab3["idDesignation"].'"/>
															<input type="hidden" name="tabIdFusionDoub" value="'.implode('-',$tabIdFusionDoub).'"/>
															<input type="hidden" name="tabDoublon" value="'.implode('-',$tabDoublon).'"/>
															<input type="hidden" name="designation" value="'.$tab3["designation"].'"/>
															<input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/> -->
															<div class="form-group" align="right">
																<!-- <input type="submit" class="boutonbasic"  name="btnEliminerDoublons" value="Eliminer>>"/> -->
																<?php echo  '<button type="button" id="btnElTabDo" onclick="eli_Doub_B()">Eliminer</button>  '; ?>
															</div>
														</form><br />
														</div>
												</div>
											</div>
										</div>
										<!-- Fin Popup pour doublon -->
										
									
									<div class="table-responsive">                
										<label class="pull-left" for="nbEntreeBD">Nombre entrées </label>
											<select class="pull-left" id="nbEntreeBD">
											<optgroup>
												<option value="10">10</option>
												<option value="20">20</option>
												<option value="50">50</option>  
											</optgroup>       
											</select>
											<input class="pull-right" type="text" name="" id="searchInputBD" placeholder="Rechercher...">
											<input type="hidden" id="idCDD" value="<?php echo $tab0['id']; ?>">
											<div id="resultsProductsBD"><!-- content will be loaded here --></div>
									</div> 
									
							
							</div>
							<div class="tab-pane fade " id="FUSION">
								<div id="fuB"  class="modal fade bd-example-modal-xl" tabindex="-1"   role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header" style="padding:35px 50px;">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Fusion catalogue </b></h4>
													</div>
													<div class="modal-body" >
														<form id="commentFormB" method="post" >
														<input type="hidden" name="id" id="idFB"/>
														<input type="hidden" name="idCT" id="idCTFB"/>
														<input type="hidden" name="designation" />
														<input type="hidden" class="form-control" id="categorieFB" name="categorie" >
														<input type="hidden" class="form-control" id="uniteStockFB" name="uniteStock"  >
														<input type="hidden" class="form-control" id="nbreArticleUniteStockFB" name="nbreArticleUniteStock" aria-describedby="inputGroupPrepend" >
														<input type="hidden" class="form-control" id="prixuniteStockFB" aria-describedby="inputGroupPrepend" name="prixuniteStock"  required>
														<input type="hidden" class="form-control" id="prixFB" aria-describedby="inputGroupPrepend" name="prix"  required>
														<input type="hidden" class="form-control" id="prixAchatFB" aria-describedby="inputGroupPrepend" name="prixAchat"  required>
														<input type="hidden" class="form-control" id="codeBarreDesignationFB" aria-describedby="inputGroupPrepend" name="codeBarreDesignation"  >
															<table  id="tableFuB" class="display " border="1">
																<thead >
																	<tr>
																		<th>DESIGNATION</th>
																		<th>CATEGORIE</th>
																		<th>UNITE STOCK</th>
																		<th>ARTICLE PAR UNITE DE STOCK</th>
																		<th>PRIX UNITE STOCK</th>
																		<th>PRIX</th>
																		<th>PRIX ACHAT</th>
																		<th>CODE BARRE</th>
																	</tr>
																</thead>
																<tfoot >
																	<tr>
																	<th>DESIGNATION</th>
																		<th>CATEGORIE</th>
																		<th>UNITE STOCK</th>
																		<th>ARTICLE PAR UNITE DE STOCK</th>
																		<th>PRIX UNITE STOCK</th>
																		<th>PRIX</th>
																		<th>PRIX ACHAT</th>
																		<th>CODE BARRE</th>
																	</tr>
																</tfoot>
																<tbody>


																</tbody>

															</table>
														</form>
														<div class="form-group" align="left">
																<input type="submit" class="btn btn-primary btn-sm" id="btnFusionB"  onclick="fusioner_B()" name="btnFusion" value="Fusionner>>"/>
														</div>
													</div>

												</div>
											</div>
								</div>
								

								<div class="table-responsive">                
									<label class="pull-left" for="nbEntreeBF">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeBF">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputBF" placeholder="Rechercher...">
										<input type="hidden" id="idCDBF" value="<?php echo $tab0['id']; ?>">
										<div id="resultsProductsPBF"><!-- content will be loaded here --></div>
								</div>
							</div>
						</div>
			</div>
		</div>
			
	</body>
</html>
<?php } ?>
