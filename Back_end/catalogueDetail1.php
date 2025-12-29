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

	require('connection.php');
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
	   $sql0="SELECT * from  `aaa-catalogueTotal`  where id='".$_GET['i']."'";
		 $res0=mysql_query($sql0);
		 if ($res0) {
		 	// code...
			$tab0=mysql_fetch_array($res0);
			$catalogueTotal='aaa-catalogueTotal';
			$type=$tab0['type'];
	    $categorie=$tab0['categorie'];
	    $typeCategorie=$tab0['type']."-".$tab0['categorie'];
			$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
			$categorieTypeCateg='aaa-categorie-'.$typeCategorie;
			$formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
			$tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
		} else {
			// code...
			$tab0=0;
		}

	/*************************************/
	if (($type=="Pharmacie") && ($categorie=="Detaillant")) {

	    require('catalogueDetail-pharmacie.php');

	}else{
		if (isset($_POST['btnGenererCatalogue'])) {

			$sql20="SELECT MAX(idFusion) from  `".$catalogueTypeCateg."` ";
			$res20=mysql_query($sql20);
			$tab20="";
			$iD=0;
			if ($res20) {
				$tab20=mysql_fetch_array($res20);
				//echo "string=".$tab20['id'];
				$iD=$tab20[0];
			}
			  $sql3="SELECT  * FROM `aaa-boutique` where type='".$type."' and  categorie='".$categorie."' ";
			  if($res3 = mysql_query($sql3)){
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
													 `prixAchat ` REAL NOT NULL,
													 `codeBarreDesignation` VARCHAR(155) NOT NULL,
													 `codeBarreuniteStock` VARCHAR(155) NOT NULL,
													 `classe` INT NOT NULL,
													 `image` VARCHAR(155) NOT NULL,
													  PRIMARY KEY (`id`)) ENGINE = MYISAM";
							  $res0 =@mysql_query($sql0) or die ("creation table catalogue impossible".mysql_error());

								 $sql14="CREATE TABLE IF NOT EXISTS `".$categorieTypeCateg."`
													  (`id` INT NOT NULL AUTO_INCREMENT,
														  `nomCategorie` VARCHAR(155) NOT NULL,
														  `categorieParent` VARCHAR(155) NOT NULL,
														 UNIQUE (nomCategorie),
													   PRIMARY KEY (`id`)) ENGINE = MYISAM";
								  $res14 =@mysql_query($sql14) ;

								while($boutique = mysql_fetch_array($res3)) {
									 $nomtableDesignation=$boutique['nomBoutique']."-designation";
									 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
									 //$sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0";
									 $sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans'  and classe=0 ";
									 //var_dump($nomtableDesignation);
										 if ($res4 = mysql_query($sql4)) {
											 while ($des=mysql_fetch_array($res4)) {
												 /*****************************************/
												 $iD=$iD+1;
												 $sql6="SELECT * from  `".$nomtableCategorie."`  where nomcategorie='".$des['categorie'] ."'";
												 $res6=mysql_query($sql6);
												 $tab6=mysql_fetch_array($res6);

												 $sql7="SELECT nomcategorie from  `".$nomtableCategorie."`  where idcategorie='".$tab6['categorieParent'] ."'";
												$res7=mysql_query($sql7);
												$tab7=mysql_fetch_array($res7);
												/*****************  CATEGORIE************************/
												$catCatParent[$des['categorie']] = $tab7['nomcategorie'];
												 /******************************************/
												/* $sql1="insert IGNORE into `".$catalogueTypeCateg."`
														 (designation,idDesignation,idBoutique,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,codeBarreDesignation,codeBarreuniteStock) values
														 ('".$des['designation']."','".$des['idDesignation']."','".$boutique['idBoutique']."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['uniteStock']."','".$des['prix']."','".$des['nbreArticleUniteStock']."','"
														 .$des['prixuniteStock']."','".$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."')";*/
												 $sql1="insert into `".$catalogueTypeCateg."`
														 (designation,idDesignation,idBoutique,idFusion,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,codeBarreDesignation,codeBarreuniteStock,`classe`) values
														 ('".$des['designation']."','".$des['idDesignation']."','".$boutique['idBoutique']."','".$iD."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['uniteStock']."','".$des['prix']."','".$des['nbreArticleUniteStock']."','"
														 .$des['prixuniteStock']."','".$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."','".$des['classe']."')";
												$res1=@mysql_query($sql1) ;
												//var_dump($sql1);
												$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($iD)."'
												where idDesignation=".$des['idDesignation'];
											 //var_dump($sql21);
												$res21=@mysql_query($sql21) or die ("modification impossible12 ".mysql_error());


											 }
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
																 $sql16="insert into `".$categorieTypeCateg."`
																		 (nomCategorie,categorieParent) values
																		 ('".$cle."','".$element."')";
																		 $res16=@mysql_query($sql16);
															 }
												 }
								 }

			  }


		}
		if (isset($_POST['btnUpdateCatalogue'])) {
						// ou faire un selectMax de idFusion

					$sql20="SELECT MAX(idFusion) from  `".$catalogueTypeCateg."` ";
					$res20=mysql_query($sql20);
					$iD=0;
					if ($res20) {
						$tab20=mysql_fetch_array($res20);
						//echo "string=".$tab20['id'];
						$iD=$tab20[0];
					}

			  $sql3="SELECT  * FROM `aaa-boutique` where type='".$type."' and  categorie='".$categorie."' ";
			  
			  if($res3 = mysql_query($sql3)){
								while($boutique = mysql_fetch_array($res3)) {
									  //var_dump($boutique);
									 $nomtableDesignation=$boutique['nomBoutique']."-designation";
									 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
									 $sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0 and (idFusion=0 OR idFusion =NULL)";
									 

										 if ($res4 = mysql_query($sql4)) {

											 while ($des=mysql_fetch_array($res4)) {
												 /*****************************************/
												 $iD=$iD+1;
												 $sql6="SELECT * from  `".$nomtableCategorie."`  where nomcategorie='".$des['categorie'] ."'";
												 $res6=mysql_query($sql6);
												 $tab6=mysql_fetch_array($res6);

												 $sql7="SELECT nomcategorie from  `".$nomtableCategorie."`  where idcategorie='".$tab6['categorieParent'] ."'";
												$res7=mysql_query($sql7);
												$tab7=mysql_fetch_array($res7);
												/*****************  CATEGORIE************************/
												$catCatParent[$des['categorie']] = $tab7['nomcategorie'];
												 /******************************************/
												/* $sql1="insert IGNORE into `".$catalogueTypeCateg."`
														 (designation,idDesignation,idBoutique,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,codeBarreDesignation,codeBarreuniteStock) values
														 ('".$des['designation']."','".$des['idDesignation']."','".$boutique['idBoutique']."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['uniteStock']."','".$des['prix']."','".$des['nbreArticleUniteStock']."','"
														 .$des['prixuniteStock']."','".$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."')";*/

												 $sql1="INSERT INTO `".$catalogueTypeCateg."`
														 (designation,idDesignation,idBoutique,idFusion,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,prixAchat,codeBarreDesignation,codeBarreuniteStock,`classe`) values
														 ('".$des['designation']."','".$des['idDesignation']."','".$boutique['idBoutique']."','".$iD."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['uniteStock']."','".$des['prix']."','".$des['nbreArticleUniteStock']."','"
														 .$des['prixuniteStock']."','".$des['prixachat']."','".$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."','".$des['classe']."')"; 
												$res1=@mysql_query($sql1) ;
												//var_dump($sql1);
												$sql21="UPDATE `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($iD)."'
												where idDesignation=".$des['idDesignation'];
											 //var_dump($sql);
												$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());/**/


											 }
										 }

										 /********************** aaa-categorie-ddd-dddd ******************/
										 /*$boutiqueCategorie=$boutique['nomBoutique']."-categorie";
										 $sql15="SELECT * FROM `".$boutiqueCategorie."` ";
										 if ($res15 = mysql_query($sql15)) {
											  while ($cat=mysql_fetch_array($res15)) {
												  // $sql16="insert into `".$categorieTypeCateg."`
													 // 	 (idCategorie,nomCategorie,categorieParent) values
													 // 	 ('".$cat['idcategorie']."','".$cat['nomcategorie']."','".$cat['categorieParent']."')";
												  //
													 // 	 $res16=@mysql_query($sql16);
														 // $catCatParent[$cat['idcategorie']] = $cat['nomcategorie'];
											  }
										  }
										  //var_dump($catCatParent);
										  var_dump($catCatParent);
										 echo "string";*/
										 if ($catCatParent) {
											 foreach($catCatParent as $cle => $element) {
														 //echo '[' . $cle . '] vaut ' . $element . '<br />';
														 $sql16="INSERT INTO `".$categorieTypeCateg."`
																 (nomCategorie,categorieParent) values
																 ('".$cle."','".$element."')";
																 $res16=@mysql_query($sql16);
													 }
										 }

										  /*****************FIN aaa-categorie-ddd-dddd *********************/
								 }

			  }


		}
		if (isset($_POST['btnUpdateCodeBarreCata'])) {
			$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where codeBarreDesignation='' ";
			if($res1 = mysql_query($sql1)){
						while($catalogue = mysql_fetch_array($res1)) {
							//var_dump($catalogue['designation']);
							$sql3="SELECT  * FROM `aaa-boutique` where type='".$type."' and  categorie='".$categorie."' ";
								if($res3 = mysql_query($sql3)){
											while($boutique = mysql_fetch_array($res3)) {
												 $nomtableDesignation=$boutique['nomBoutique']."-designation";
												 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
												 $sql4="SELECT * FROM `".$nomtableDesignation."` where idFusion='".$catalogue['idFusion']."'  and codeBarreDesignation !='' ";
													 if ($res4 = mysql_query($sql4)) {
														 while ($des=mysql_fetch_array($res4)) {
																		$sql5="UPDATE `".$catalogueTypeCateg."` set codeBarreDesignation='".$des['codeBarreDesignation']."',codeBarreuniteStock='".$des['codeBarreuniteStock']."' where id=".$catalogue['id'];
																	$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error());
														 }
													 }
											 }
										 }
								}
						}
		}if (isset($_POST['btnUpdatePrix'])) {
			$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where prix=0 ";
			if($res1 = mysql_query($sql1)){
						while($catalogue = mysql_fetch_array($res1)) {
							//var_dump($catalogue['designation']);
							$sql3="SELECT  * FROM `aaa-boutique` where type='".$type."' and  categorie='".$categorie."' ";
								if($res3 = mysql_query($sql3)){
											while($boutique = mysql_fetch_array($res3)) {
												 $nomtableDesignation=$boutique['nomBoutique']."-designation";
												 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
												 $sql4="SELECT * FROM `".$nomtableDesignation."` where idFusion='".$catalogue['idFusion']."' ";
												 //var_dump($sql4);
													 if ($res4 = mysql_query($sql4)) {
														 while ($des=mysql_fetch_array($res4)) {
														 	//var_dump($des);
																$sql5="UPDATE `".$catalogueTypeCateg."` set prix ='".$des['prix']."',prixuniteStock ='".$des['prixuniteStock']."',prixAchat ='".$des['prixachat']."' where id=".$catalogue['id'];
																$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error()); 	
															//var_dump($sql5); 
														 }
													 }
											 }
										 }
								}
						}
		}
		if (isset($_POST['btnUpdateCodeBarreBou'])) {
			$sql3="SELECT  * FROM `aaa-boutique` where type='".$type."' and  categorie='".$categorie."' ";
			//var_dump($sql3);
				if($res3 = mysql_query($sql3)){
							while($boutique = mysql_fetch_array($res3)) {
								 $nomtableDesignation=$boutique['nomBoutique']."-designation";
								 $sql4="SELECT * FROM `".$nomtableDesignation."` where  codeBarreDesignation ='' ";
									 if ($res4 = mysql_query($sql4)) {
										 while ($des=mysql_fetch_array($res4)) {
											 $sql1="SELECT  * FROM `".$catalogueTypeCateg."` where idFusion='".$des['idFusion']."'  and codeBarreDesignation !='' ";
											 if($res1 = mysql_query($sql1)){
														while($catalogue = mysql_fetch_array($res1)) {
																	$sql5="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$catalogue['codeBarreDesignation']."' ,codeBarreuniteStock='".$catalogue['codeBarreuniteStock']."' where idDesignation=".$des['idDesignation'];
																$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error());
												}
										}
							 }
					 }
				 }
			} 
		}
		if (isset($_POST['btnUpdateImage'])) {
			$sql1="SELECT  * FROM `".$catalogueTypeCateg."` ";
			//$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where image='' ";
			if($res1 = mysql_query($sql1)){
						while($catalogue = mysql_fetch_array($res1)) {
							$req2 = $bddV->prepare("SELECT * FROM boutique  WHERE type=:t and categorie=:c");
							$req2->execute(array('t' =>$type,'c' =>$categorie))
							  or die(print_r($req2->errorInfo()));
											while($boutique=$req2->fetch()) {
												$nomtableDesignation=$boutique['nomBoutique']."-designation";
												$req3 = $bddV->prepare("SELECT * from `".$nomtableDesignation."` where designation=:d ");
												$req3->execute(array('d' =>$catalogue['designation'])) or die(print_r($req2->errorInfo()));
												if ($req3) {
													while($design=$req3->fetch()){
														
														$from='../JCaisse/uploads/'.$design['image'];
														$to='uploads/'.$design['image'];
														if (file_exists($from)) {
														/* 	if (!copy($from, $to)) {
																echo "La copie $from du fichier a échoué...\n";
															}
 														*/
															$sql5="UPDATE `".$catalogueTypeCateg."` set image='".$design['image']."' where id=".$catalogue['id'];		
															$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error());

														}
													}

												}
											 }
									
								}
						}
		}
if (isset($_POST['btnModifier'])) {

		$id         =@$_POST["id"];
		$designation       =@$_POST["designation"];
		$uniteStock          =@$_POST["uniteStock"];
		$nbArticleUniteStock =@$_POST["nbArticleUniteStock"];
		$prixUnitaire        =@$_POST["prix"];
		$categorie2        =@$_POST["categorie2"];
		$prixuniteStock      =@$_POST["prixuniteStock"];
		$codeBarreDesignation      =@$_POST["codeBarreDesignation"];
		$codeBarreuniteStock      =@$_POST["codeBarreuniteStock"];
		$catalogueTypeCateg     =@$_POST["tab"];

		/*$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		uniteStock='".mysql_real_escape_string($uniteStock)."',
		categorie='".mysql_real_escape_string($categorie2)."',
		nbreArticleUniteStock=".$nbArticleUniteStock.",
		prix=".$prixUnitaire.",
		prixuniteStock=".$prixuniteStock." ,
		codeBarreDesignation=".$codeBarreDesignation." ,
		codeBarreuniteStock=".$codeBarreuniteStock."
		where id=".$id;*/
		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		uniteStock='".mysql_real_escape_string($uniteStock)."',
		categorie='".mysql_real_escape_string($categorie2)."',
		nbreArticleUniteStock=".$nbArticleUniteStock.",
		prix=".$prixUnitaire.",
		prixuniteStock=".$prixuniteStock."
		where id=".$id;
		$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
	}
if (isset($_POST['btnSupprimer'])) {
		$id         =@$_POST["id"];
		$catalogueTypeCateg     =@$_POST["tab"];
		$sql="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$id;
		$res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());
	}
if (isset($_POST['btnEliminerDoublons'])) {
		$id         =@$_POST["id"];
		$designation         =@$_POST["designation"];
		$idFusion         =@$_POST["idFusion"];
		$tabIdFusionDoub         =  $_POST["tabIdFusionDoub"] ;
		$tabIdFusionDoub =explode('-',$tabIdFusionDoub);
		$maxIdFusion=$idFusion;

		if ($tabIdFusionDoub!=NULL) {
			$maxIdFusion = max($tabIdFusionDoub);
		}
		$sql="SELECT * from  `".$catalogueTypeCateg."` where designation='".$designation."' and id !='".$id."'";
			if (	$res=mysql_query($sql)) {
				while($t=mysql_fetch_array($res)){

					//$sql2="SELECT nomBoutique from  `".$catalogueTypeCateg."` where id=".$t["id"];
					$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
					INNER JOIN `aaa-boutique` B
					ON A.idBoutique = B.idBoutique
					WHERE A.idFusion =".$t['idFusion'];

					$res2=mysql_query($sql2);
					$t2=mysql_fetch_array($res2);
					//$table=$t2['nomBoutique'];
					$nomtableDesignation=$t2['nomBoutique']."-designation";

					$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
			 		$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());

					$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
					where idDesignation=".$t['idDesignation'];
					 //var_dump($sql);
					$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
				 }
			}
				//print_r($tabIdFusionDoub);
				//$maxIdFusion = max($tabIdFusionDoub);
		/**/
	}
if (isset($_POST['btnFusion'])) {
		$id         =@$_POST["id"];
		$catalogueTypeCateg     =@$_POST["tab"];

		$designation         =@$_POST["designation"];
		$uniteStock          =@$_POST["uniteStock"];
		$nbArticleUniteStock =@$_POST["nbreArticleUniteStock"];
		$prixUnitaire        =@$_POST["prix"];
		$categorie        =@$_POST["categorie"];
		$prixuniteStock      =@$_POST["prixuniteStock"];

		$idFusion     =  $_POST["idFusion"] ;
		$tabIdFusionF     =  $_POST["tabIdFusionF"] ;
		$tabIdFusionF =explode('-',$tabIdFusionF);
		$maxIdFusion=0;

		if ($tabIdFusionDoub!=NULL) {
			$maxIdFusion = max($tabIdFusionF);
		}

		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		uniteStock='".mysql_real_escape_string($uniteStock)."',
		categorie='".mysql_real_escape_string($categorie)."',
		nbreArticleUniteStock=".$nbArticleUniteStock.",
		prix=".$prixUnitaire.",
		idFusion=".$idFusion.",
		prixuniteStock=".$prixuniteStock."
		where id=".$id;
		//echo "sql ==".$sql;
		$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

		$sql="SELECT * from  `".$catalogueTypeCateg."` where designation='".$designation."' and id !='".$id."'";
			if (	$res=mysql_query($sql)) {
				while($t=mysql_fetch_array($res)){
					 /*$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
				 		$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
						// choix entre MAX ou MIN
						$sql21="update `".$table."` set idFusion='".mysql_real_escape_string($id)."'
						where idDesignation=".$t['idDesignation'];
						 //var_dump($sql);
						$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
						*/
						$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
						INNER JOIN `aaa-boutique` B
						ON A.idBoutique = B.idBoutique
						WHERE A.idFusion =".$t["idFusion"];

						$res2=mysql_query($sql2);
						$t2=mysql_fetch_array($res2);
						//$table=$t2['nomBoutique'];
						$nomtableDesignation=$t2['nomBoutique']."-designation";

						$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
						$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());

						$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($idFusion)."'
						where idDesignation=".$t['idDesignation'];
						 //var_dump($sql);
						$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
				 }
			}
		/**/
	}
if(isset($_POST["btnUploadImg"])) {

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

						$id         =@$_POST["id"];
						$catalogueTypeCateg     =@$_POST["tab"];
						switch ($uploadImageType) {
								case IMAGETYPE_JPEG:
										$resourceType = imagecreatefromjpeg($fileName);
										$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
										imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
										$fileNameNew=$resizeFileName.'.'. $fileExt;
										imagedestroy($imageLayer);
										imagedestroy($resourceType);
										$sql="update `".$catalogueTypeCateg."` set image='".mysql_real_escape_string($fileNameNew)."' where id='".$id."'";
										$res=@mysql_query($sql) or die ("upload impossible1 ".mysql_error());
										break;
								case IMAGETYPE_PNG:
										$resourceType = imagecreatefrompng($fileName);
										$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
										imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
										$fileNameNew=$resizeFileName.'.'. $fileExt;
										imagedestroy($imageLayer);
										imagedestroy($resourceType);
										$sql="update `".$catalogueTypeCateg."` set image='".mysql_real_escape_string($fileNameNew)."' where id='".$id."'";
										$res=@mysql_query($sql) or die ("upload impossible1 ".mysql_error());
										break;
								default:
										$imageProcess = 0;
										break;
						}
					//	move_uploaded_file($file, $uploadPath.$resizeFileName. ".". $fileExt);
				/*	$fileNameNew=uniqid('',true).".".$fileActualExt;
						move_uploaded_file($fileName, $uploadPath.$resizeFileName. ".". $fileExt);
						// Save to database
						$id         =@$_POST["id"];
						$catalogueTypeCateg     =@$_POST["tab"];
						$sql="update `".$catalogueTypeCateg."` set image='".mysql_real_escape_string($fileNameNew)."' where id='".$id."'";
						//echo $sql;
						$res=@mysql_query($sql) or die ("upload impossible1 ".mysql_error());
						// Save to database
						$imageProcess = 1;*/

				}


			$imageProcess = 0;

		/*$file=$_FILES['file'];

		$fileName=$_FILES['file']['name'];
		$fileTmpName=$_FILES['file']['tmp_name'];
		$fileSize=$_FILES['file']['size'];
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
							$id         =@$_POST["id"];
							$catalogueTypeCateg     =@$_POST["tab"];
							$sql="update `".$catalogueTypeCateg."` set image='".mysql_real_escape_string($fileNameNew)."' where id='".$id."'";
							//echo $sql;
							$res=@mysql_query($sql)or die ("upload impossible1 ".mysql_error());
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
		} */
	}
	/**************** DECLARATION DES ENTETES *************/
if(isset($_POST["btnSupImg"])) {
			 if(unlink("uploads/".$_POST['image'])) {
						$id         =@$_POST["id"];
						$catalogueTypeCateg     =@$_POST["tab"];
						$fileNameNew='';
						$sql="update `".$catalogueTypeCateg."` set image='".$fileNameNew."' where id='".$id."'";
						$res=@mysql_query($sql) or die ("upload impossible1 ".mysql_error());
				 }else {

				 }



	}





	/**************** DECLARATION DES ENTETES *************/

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
				$sql="DELETE FROM `".$categorieTypeCateg."` WHERE id=".$id;
				$res=@mysql_query($sql) or die ("suppression impossible categorie".mysql_error());
			}
if (isset($_POST['subImport1'])) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $headers = fgetcsv($handle, 1000, ";");


    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {


		$reference=htmlspecialchars(trim($data[0]));
		$categorie=htmlspecialchars(trim($data[1]));
		$uniteS=htmlspecialchars(trim($data[2]));
		$nbreAuniteS=$data[3];
		$prixU=$data[4];
		$prixUS=$data[5];
    //$prixA=$data[5];
    $quantite=$data[6];
		//$classe=$data[6];
	/*	$sql10="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";
		$res10=mysql_query($sql10);
		if(!mysql_num_rows($res10)){
		   $sql3="insert into `".$nomtableDesignation."`(designation,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,prixachat,categorie,classe)
		   values('".mysql_real_escape_string($reference)."','".mysql_real_escape_string($uniteS)."',".$nbreAuniteS.",".$prixU.",".$prixUS.",".$prixA.",'".mysql_real_escape_string($categorie)."',0)";
			 //var_dump($sql);
			$res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());
    }
    if($quantite!=null || $quantite!=''){
      $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";
      $res11=mysql_query($sql11);
      if(mysql_num_rows($res11)){
        $produit=mysql_fetch_array($res11);
        $totalArticleStock=$quantite;
        $sql3='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 
        VALUES('.$produit['idDesignation'].',"'.mysql_real_escape_string($produit['designation']).'",'.$quantite.',"'.mysql_real_escape_string($produit['uniteStock']).'",'.$prixU.','.$prixUS.','.$nbreAuniteS.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$expiration.'","'.$_SESSION['iduser'].'")';
        $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());
      }
    }*/
    $sql3="insert into `".$catalogueTypeCateg."`(designation,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,categorie,classe)
		   values('".mysql_real_escape_string($reference)."','".mysql_real_escape_string($uniteS)."',".$nbreAuniteS.",".$prixU.",".$prixUS.",'".mysql_real_escape_string($categorie)."',0)";
			 //var_dump($sql);
			$res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());

		$sql12="SELECT * FROM `". $categorieTypeCateg."` where nomcategorie='".mysql_real_escape_string($categorie)."'";
		$res12=mysql_query($sql12);
		if(!mysql_num_rows($res12))
				if($categorie) {
				$sql="insert into `".$categorieTypeCateg."` (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";
				$res=@mysql_query($sql) or die ("insertion categorie CSV impossible".mysql_error());
		  }
    }
  
    fclose($handle);
	 
  }

	if ( $_GET['l']==7) {

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
}
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

<?php require('entetehtml.php'); ?>

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
			 $sql3="SELECT * from  `".$catalogueTypeCateg."` ";
			$res3=mysql_query($sql3); ?>
			<div>
				<?php if ($res3): ?>
					<form name="formulairePersonnel"   method="post" >
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
						<button type="submit" class="btn btn-success" name="btnUpdateImage">
								<i class="glyphicon glyphicon-plus"></i>update image
						</button>
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
		<h2>Catalogue : <?php echo $tab0['nom']; ?></h2>
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
			/**************************************************************************/
			/*SELECT `id` , `idDesignation` , designation, categorie, COUNT( * )
				FROM `aaa-catalogue-alimentaire-detaillant`
				GROUP BY designation, categorie
				HAVING COUNT( * ) >1
				LIMIT 0 , 30*/
				/*uniiquement ceux qui ont des Doublons
				$sql9="SELECT `id` 	FROM `".$catalogueTypeCateg."`
									GROUP BY designation, categorie
									HAVING COUNT( * ) >1
									LIMIT 0 , 30";

					if($res9 = mysql_query($sql9)){
						while($ligne = mysql_fetch_array($res9)) {
								$doublons[] =$ligne['id'];
							}
					}*/
				/********************************************************************/
				/* Tous ceux qui ont des Doublons*/
				$sql9="SELECT a.* FROM `".$catalogueTypeCateg."` a JOIN
						(SELECT designation, categorie, COUNT(*) FROM `".$catalogueTypeCateg."`
						 GROUP BY designation, categorie HAVING count(*) > 1 )
						b ON a.designation = b.designation AND a.categorie = b.categorie ORDER BY a.designation";
						if($res9 = mysql_query($sql9)){
							while($ligne = mysql_fetch_array($res9)) {
									$doublons[] =$ligne['id'];
								}
						}

				$sql16="SELECT s.id, t . * FROM `".$catalogueTypeCateg."` s	JOIN
					(	SELECT designation, categorie, COUNT( * ) AS qty
					FROM `".$catalogueTypeCateg."`
					GROUP BY designation, categorie
					HAVING COUNT( * ) >=1
					)t ON s.designation = t.designation
					AND s.categorie <> t.categorie";
						if($res16 = mysql_query($sql16)){
							while($ligne16 = mysql_fetch_array($res16)) {
									$fusions[] =$ligne16['id'];
								}
						}

	 ?>

	
</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/
?>
		<div class="container">
			<div class="container" align="center">
				        <ul class="nav nav-tabs">
				          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">REFERENCE</a></li>
									<li><a data-toggle="tab" href="#CATEGORIE">CATEGORIE</a></li>
				        </ul>


				        <div class="tab-content">
				            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
								<?php
									if($res3){ ?>
											<table id="tableDesignation" class="display" border="1" id="userTable">
												<thead>
													<tr>
														<th>ORDRE</th>
														<th>DESIGNATION</th>
														<th>CATEGORIE</th>
														<th>UNITE STOCK</th>
														<th>ARTICLE PAR UNITE DE STOCK</th>
														<th>PRIX</th>
														<th>PRIX UNITE STOCK</th>
														<th>OPERATION</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>ORDRE</th>
														<th>DESIGNATION</th>
														<th>CATEGORIE</th>
														<th>UNITE STOCK</th>
														<th>ARTICLE PAR UNITE DE STOCK</th>
														<th>PRIX</th>
														<th>PRIX UNITE STOCK</th>
														<th>OPERATION</th>
													</tr>
												</tfoot>
											</table>		

											<div id="modifierDesignation"  class="modal fade " role="dialog">
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
																			<?php $sql11="SELECT * FROM `". $categorieTypeCateg."`";
																				$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
																				while($ligne2 = mysql_fetch_array($res11)) {
																					echo'<option  value= "'.$ligne2['nomCategorie'].'">'.$ligne2['nomCategorie'].'</option>';
																				}
																			?>
																	</select>
																</div>
																<div class="form-group">
																	<label for="uniteStock"> UNITE STOCK <font color="red">*</font></label>
																	<select class="form-control" id="uniteStock_Mdf"  >
																			<option selected ></option>
																			<?php $sql11="SELECT * FROM `aaa-unitestock`";
																				$res11=mysql_query($sql11);
																				while($ligne2 = mysql_fetch_row($res11)) {
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
														<form   method="post" enctype="multipart/form-data">
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
													<img alt="" id="imgsrc_UpdC" />
													<form   method="post" enctype="multipart/form-data">
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
											<!-- <div class="table-responsive">
												<label class="pull-left" for="nbEntreeB">Nombre entrées </label>
												<select class="pull-left" id="nbEntreeB">
												<optgroup>
													<option value="10">10</option>
													<option value="20">20</option>
													<option value="50">50</option> 
												</optgroup>       
												</select>
												<input class="pull-right" type="text" name="" id="searchInputB" placeholder="Rechercher...">
												<div id="resultsProductsB"></div>
											</div>  -->
											<script type="text/javascript">
												$(document).ready(function() {
													$("#tableDesignation").dataTable({
													"bProcessing": true,
													"sAjaxSource": "ajax/catalogueDetailsProduitsAjax.php?id=<?php echo $tab0['id']; ?>",
													"aoColumns": [
															{ mData: "0" } ,
															{ mData: "1" },
															{ mData: "2" },
															{ mData: "3" },
															{ mData: "4" },
															{ mData: "5" },
															{ mData: "6" },
															{ mData: "7" }
														],

													});
												});
											</script>
								<?php } ?>
							</div>
										<div class="tab-pane fade " id="CATEGORIE">
				                    <?php
															   $sql15="SELECT * from  `".$categorieTypeCateg."` ";

															 if($res15=mysql_query($sql15)){ ?>
									                   	<table id="tableCategorie" class="display" border="1" width="100%" align="left" >
															<thead>
																<tr>
																	<th>CATEGORIE</th>
																	<th>CATEGORIE PARENT</th>
																	<th>OPERATION</th>
																</tr>
															</thead>
															<tfoot>
																<tr>
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
																		{ mData: "2" }
																	],

																});
															});
														</script>
												 <?php } ?>
										</div>
							  </div>
				</div>
		</div>
	</body>
</html>
<?php } ?>
