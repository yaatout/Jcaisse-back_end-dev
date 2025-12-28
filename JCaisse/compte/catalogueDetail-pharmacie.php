<?php
	/*
	R�sum�:
	Commentaire:
	version:1.1
	Auteur:EL hadji mamadou korka
	Date de modification:25-03-2020
	*/
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


	$formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
	$tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
	$forme=NULL;
	$tableau=NULL;
	$catCatParent=NULL;
	/*************************************/
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

												$sql0="CREATE TABLE IF NOT EXISTS `".$catalogueTypeCateg."`
														(`id` INT NOT NULL AUTO_INCREMENT,
															`designation` VARCHAR(50) NOT NULL,
	 													 `idDesignation` INT NOT NULL,
	 													 `idBoutique` INT NOT NULL,
														 `idFusion` INT NOT NULL,
															`description` VARCHAR(100),
															`categorie` VARCHAR(50) NOT NULL,
															`categorieParent` VARCHAR(155) NOT NULL,
															`forme` VARCHAR(50) NOT NULL,
															`tableau` VARCHAR(50) NOT NULL,
															`seuil` INT NOT NULL,
															`prixSession` REAL NOT NULL DEFAULT '0',
															 `prixPublic` REAL NOT NULL,
															 `codeBarreDesignation` VARCHAR(50) NOT NULL,
															 `codeBarreuniteStock` VARCHAR(50) NOT NULL,
                               `noArt` INT NOT NULL,
															 `codCIP` VARCHAR(30) NOT NULL,
															 PRIMARY KEY (`id`)) ENGINE = MYISAM";

											$res0 =@mysql_query($sql0) or die ("creation table catalogue impossible Pharmacie  ".mysql_error());


											 $sql14="CREATE TABLE IF NOT EXISTS `".$categorieTypeCateg."`
				 												 (`id` INT NOT NULL AUTO_INCREMENT,
				 													 `nomCategorie` VARCHAR(155) NOT NULL,
				 													 `categorieParent` VARCHAR(155) NOT NULL,
																	 UNIQUE (nomCategorie),
				 												  PRIMARY KEY (`id`)) ENGINE = MYISAM";
				 							 $res14 =@mysql_query($sql14) ;

											 $sql18="CREATE TABLE IF NOT EXISTS `".$formeTypeCategPharmacie."`
				 												 (`id` INT NOT NULL AUTO_INCREMENT,
				 													 `nomForme` VARCHAR(155) NOT NULL,
																	 UNIQUE (nomForme),
				 												  PRIMARY KEY (`id`)) ENGINE = MYISAM";
				 							 $res18 =@mysql_query($sql18) ;

											 // $sql17="CREATE TABLE IF NOT EXISTS `".$tableauTypeCategPharmacie."`
				 								// 				 (`id` INT NOT NULL AUTO_INCREMENT,
				 								// 					 `nomTableau` VARCHAR(155) NOT NULL,
												// 					 UNIQUE (nomTableau),
				 								// 				  PRIMARY KEY (`id`)) ENGINE = MYISAM";
				 							 // $res17 =@mysql_query($sql17) ;

							while($boutique = mysql_fetch_array($res3)) {

								 $nomtableDesignation=$boutique['nomBoutique']."-designation";
								 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
								 $sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0";

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
																(`designation` ,idDesignation,idBoutique ,`idFusion` ,`categorie` ,categorieParent,`forme` ,`tableau`,`prixSession` ,`prixPublic` ,`codeBarreDesignation` ,`codeBarreuniteStock` ,`classe`) values
																('".$des['designation']."','".$des['idDesignation']."','".$boutique['idBoutique']."','".$iD."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['forme']."','".$des['tableau']."',
																 '".$des['prixSession']."','".$des['prixPublic']."','"
																.$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."','".$des['classe']."')";
																$forme[] =$des['forme'];
																$tableau[] =$des['tableau'];
													 $res1=@mysql_query($sql1) or die ("modification impossible1 ".mysql_error());

													 $sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($iD)."'
		 											where idDesignation=".$des['idDesignation'];
		 									     //var_dump($sql);
		 											$res21=@mysql_query($sql21) or die ("modification impossible1 ".mysql_error());

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

											 			$res16=@mysql_query($sql16) ;
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

										/***********************  Forme pharmacie   ***********************/
											// Supprimer les doublons
	  										$forme = array_unique($forme);
	  										$tableau = array_unique($tableau);
											//	$formeTypeCategPharmacie
											//$tableauTypeCategPharmacie
												foreach($forme as $element)
													{
														$sql18="insert into `".$formeTypeCategPharmacie."`
															 (nomForme) values
															 ('".$element."')";

															 $res18=@mysql_query($sql18);
													}


										/********************* Tableau pharmacie ************************/
										// foreach($tableau as $element)
										// 	{
										// 		$sql19="insert into `".$formeTypeCategPharmacie."`
										// 			 (nomForme) values
										// 			 ('".$element."')";
										//
										// 			 $res19=@mysql_query($sql19);
										// 	}

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

	      $sql3="SELECT  * FROM `aaa-boutique` where (type='".$type."' and  categorie='".$categorie."') or (type='Centre de sante' and  categorie='Detaillant') ";
	      if($res3 = mysql_query($sql3)){
							while($boutique = mysql_fetch_array($res3)) {
								 $nomtableDesignation=$boutique['nomBoutique']."-designation";
								 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
								 $sql4="SELECT * FROM `".$nomtableDesignation."` where categorie !='Sans' and classe=0 and (idFusion=0 OR idFusion IS NULL)";

									 if ($res4 = mysql_query($sql4)) {
										 while ($des=mysql_fetch_array($res4)) {
											 /*****************************************/
											  $iD=$iD+1;
												//var_dump($iD);
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
													/* $sql20="SELECT id from  `".$catalogueTypeCateg."`  ORDER BY id DESC LIMIT 0,1";
													$res20=mysql_query($sql20);
													$tab20=mysql_fetch_array($res20);
													//echo "string".$tab20['id'];*/
														 $sql1="insert into `".$catalogueTypeCateg."`
																(`designation`  ,`categorie` ,`idFusion` ,categorieParent,`forme` ,`tableau`,`prixSession` ,`prixPublic` ,`codeBarreDesignation` ,`codeBarreuniteStock` ,`classe`) values
																('".$des['designation']."','".$des['categorie']."','".$iD."','".$tab7['nomcategorie']."','".$des['forme']."','".$des['tableau']."',
																 '".$des['prixSession']."','".$des['prixPublic']."','"
																.$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."','".$des['classe']."')";
																$forme[] =$des['forme'];
																$tableau[] =$des['tableau'];
													 $res1=@mysql_query($sql1) ;

													 	$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($iD)."'
													 					where idDesignation=".$des['idDesignation'];
													 //var_dump($sql);
													 $res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());

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

											 			$res16=@mysql_query($sql16) ;
											}
										}*/
										if ($catCatParent) {
 										 foreach($catCatParent as $cle => $element) {
 													 //echo '[' . $cle . '] vaut ' . $element . '<br />';
 													 $sql16="INSERT INTO `".$categorieTypeCateg."`
 															 (nomCategorie,categorieParent) values
 															 ('".$cle."','".$element."')";
 															 $res16=@mysql_query($sql16);
 												 }
 									 }
										/***********************  Forme pharmacie   ***********************/
											// Supprimer les doublons

	  									//	$tableau = array_unique($tableau);
											//	$formeTypeCategPharmacie
											//$tableauTypeCategPharmacie

											if ($forme) {
												$forme = array_unique($forme);
												foreach($forme as $element)
													{
														$sql18="INSERT INTO `".$formeTypeCategPharmacie."`
															 (nomForme) values
															 ('".$element."')";

															 $res18=@mysql_query($sql18);
													}
											}



										/********************* Tableau pharmacie ************************/
										// foreach($tableau as $element)
										// 	{
										// 		$sql19="insert into `".$formeTypeCategPharmacie."`
										// 			 (nomForme) values
										// 			 ('".$element."')";
										//
										// 			 $res19=@mysql_query($sql19);
										// 	}

							 }

	      }


	}
	if (isset($_POST['btnUpdateCodeBarreCata'])) {
		$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where (codeBarreDesignation='' or codeBarreDesignation='NULL') ";
		if($res1 = mysql_query($sql1)){
					while($catalogue = mysql_fetch_array($res1)) {
						//var_dump($catalogue['designation']);
						$sql3="SELECT  * FROM `aaa-boutique` where (type='".$type."' or type='Centre de sante') and  categorie='Detaillant' ";
            //var_dump($sql3);
            	if($res3 = mysql_query($sql3)){
										while($boutique = mysql_fetch_array($res3)) {
                      //var_dump($boutique['nomBoutique']);echo '<br/>';
                      //$nomB=htmlentities($boutique['nomBoutique']);
            				$nomtableDesignation=$boutique['nomBoutique']."-designation";
											 $sql4="SELECT * FROM `".$nomtableDesignation."` where idFusion='".$catalogue['idFusion']."'  and codeBarreDesignation !='' ";
												 if ($res4 = mysql_query($sql4)) {
													 while ($des=mysql_fetch_array($res4)) {
																	$sql5="UPDATE `".$catalogueTypeCateg."` set codeBarreDesignation='".$des['codeBarreDesignation']."',codeBarreuniteStock='".$des['codeBarreuniteStock']."' where id=".$catalogue['id'];
																 //var_dump($des['designation']);
																 //echo "nomBoutique=".$boutique['nomBoutique'].'design='.$des['designation'].'  desCata='.$catalogue['designation'].' idDesignation='.$des['idDesignation'].' codeBarre='.$des['codeBarreDesignation'].' id='.$catalogue['id'].'<br/>';
																$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error());
													 }
												 }
										 }
									 }
							}
					}
	}
	if (isset($_POST['btnUpdateCodeBarrePharma'])) {
		$sql3="SELECT  * FROM `aaa-boutique` where (type='".$type."' or type='Centre de sante') and  categorie='Detaillant'  ";
		//var_dump($sql3);
		if($res3 = mysql_query($sql3)){
			while($boutique = mysql_fetch_array($res3)) {

          //var_dump($boutique['nomBoutique']); echo '<br/>';
          // $nomB=htmlentities($boutique['nomBoutique']);
				$nomtableDesignation=$boutique['nomBoutique']."-designation";
				$sql4="SELECT * FROM `".$nomtableDesignation."` where  (codeBarreDesignation='' or codeBarreDesignation='NULL') ";
				if ($res4 = mysql_query($sql4)) {
					while ($des=mysql_fetch_array($res4)) {
            ///echo "====".$nomtableDesignation.'<br/>';
						//$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where idFusion='".$des['idFusion']."'  and codeBarreDesignation !='' ";
						$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where designation='".$des['designation']."'  and codeBarreDesignation !='' ";
						if($res1 = mysql_query($sql1)){
							while($catalogue = mysql_fetch_array($res1)) {
								$sql5="UPDATE `".$nomtableDesignation."` SET codeBarreDesignation='".$catalogue['codeBarreDesignation']."',codeBarreuniteStock='".$catalogue['codeBarreuniteStock']."' where idDesignation=".$des['idDesignation'];
								$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error());
							}
						}
					}
				}
			}
		}
    ////
    // $sql3="SELECT  * FROM `aaa-boutique` where  (type='Centre de sante' and  categorie='Detaillant')  ";
		// //var_dump($sql3);
		// if($res3 = mysql_query($sql3)){
		// 	while($boutique = mysql_fetch_array($res3)) {
		// 		$nomtableDesignation=$boutique['nomBoutique']."-designation";
		// 		$sql4="SELECT * FROM `".$nomtableDesignation."` where  codeBarreDesignation ='' ";
		// 		if ($res4 = mysql_query($sql4)) {
		// 			while ($des=mysql_fetch_array($res4)) {
		// 				$sql1="SELECT  * FROM `".$catalogueTypeCateg."` where idFusion='".$des['idFusion']."'  and codeBarreDesignation !='' ";
		// 				if($res1 = mysql_query($sql1)){
		// 					while($catalogue = mysql_fetch_array($res1)) {
		// 						$sql5="UPDATE `".$nomtableDesignation."` SET codeBarreDesignation='".$catalogue['codeBarreDesignation']."',codeBarreuniteStock='".$catalogue['codeBarreuniteStock']."' where idDesignation=".$des['idDesignation'];
		// 						$res5=@mysql_query($sql5)or die ("modification impossible1 ".mysql_error());
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }

	}
if (isset($_POST['btnModifier'])) {

		$id         =@$_POST["id"];
		$designation       =@$_POST["designation"];
		$categorie        =@$_POST["categorie2"];
		$codeBarreDesignation      =@$_POST["codeBarreDesignation"];
		$codeBarreuniteStock      =@$_POST["codeBarreuniteStock"];
		$catalogueTypeCateg     =@$_POST["tab"];

		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		codeBarreDesignation='".$codeBarreDesignation."' ,
		codeBarreuniteStock='".$codeBarreuniteStock."'
		where id=".$id;
     //var_dump($sql);
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
		$idCT         =@$_POST["idCT"];

		$idFusion         =null;
		$idDesignation    =null;
		$tabIdFusionDoub  =null;
  	$tabDoublon       =NULL;
		//$tabDoublon =explode('-',$tabDoublon);

    $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$id."'";
  	$res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  	$cat = mysql_fetch_assoc($res13);

    $idFusion=$cat['idFusion'];
    $designation=$cat['designation'];

    $maxIdFusion=$idFusion;

    if ($idFusion==0) {
        $sql6="SELECT MAX(idFusion) FROM `".$catalogueTypeCateg."`
    					WHERE designation ='".$cat['designation']."' && categorie ='".$cat['categorie']."' && forme ='".$cat['forme']."'
    					&& tableau ='".$cat['tableau']."' && prixSession ='".$cat['prixSession']."' && prixPublic ='".$cat['prixPublic']."'
               && codeBarreDesignation ='".$cat['codeBarreDesignation']."' and id !=".$id."";
		if ($res6=mysql_query($sql6)) {
          $t6=mysql_fetch_array($res6);
          $maxIdFusion=$t6[0];
          //var_dump($maxIdFusion);
           $sql21="update `".$catalogueTypeCateg."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
           where id=".$id;
           $res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
      }

    }
      $sql="SELECT * FROM `".$catalogueTypeCateg."`
    					WHERE designation ='".$cat['designation']."' && categorie ='".$cat['categorie']."' && forme ='".$cat['forme']."'
    					&& tableau ='".$cat['tableau']."' && prixSession ='".$cat['prixSession']."' && prixPublic ='".$cat['prixPublic']."'
               && codeBarreDesignation ='".$cat['codeBarreDesignation']."' and id !=".$id."";
				  if($res=mysql_query($sql)) {
					while($t=mysql_fetch_array($res)){
							$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
									INNER JOIN `aaa-boutique` B
									ON A.idBoutique = B.idBoutique
									WHERE A.idFusion =".$t['idFusion'];
									$res2=mysql_query($sql2);
                   $t2=mysql_fetch_array($res2) ;
                    if ($t2['nomBoutique'] =='' or $t2['nomBoutique'] =='NULL' ) {
                      //echo "string1";
                      $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
                      // echo "string1 =".$sql1;
    							    $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
                      // if ($cat['codeBarreDesignation']='' and $t['codeBarreDesignation']!='') {
                      //     $sql21="update `".$catalogueTypeCateg."` set codeBarreDesignation='".$t['codeBarreDesignation']."'
                      //     where id=".$id;
                      //     $res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
                      // }

                    } else {
                      //echo "string2";
    									$nomtableDesignation=$t2['nomBoutique']."-designation";
    									$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
                      //echo "string2 =".$sql1;
    							     $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
    									$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
    									where idDesignation=".$t['idDesignation'];
                      //var_dump($sql21);
    									$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
                  }

						}
					}



	}
if (isset($_POST['btnFusion'])) {
		$idD         =@$_POST["id"];
		$idCT         =@$_POST["idCT"];

		// $designation    =@$_POST["designation"];
		$categorie      =@$_POST["categorie"];
		$forme          =@$_POST["forme"];
		$tableau 				=@$_POST["tableau"];
		$prixSession    =@$_POST["prixSession"];
		$prixPublic     =@$_POST["prixPublic"];
		$codeBarreDesignation     ="null";
    if (isset($_POST["codeBarreDesignation"])) {
      if ($_POST["codeBarreDesignation"]!=null) {
          $codeBarreDesignation     =@$_POST["codeBarreDesignation"];
      }
    }
    //
    $sql0="SELECT * from  `aaa-catalogueTotal`  where id=".$idCT;
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
      $typeCategorie=$tab0['type']."-".$tab0['categorie'];
  	  $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;

      $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
  	  $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  	  $tab3 = mysql_fetch_assoc($res13);

		  $idFusion     =  $tab3["idFusion"] ;
      $designation    =$tab3["designation"];
      $maxIdFusion=$idFusion;
		// $tabIdFusionF     =  $_POST["tabIdFusionF"] ;
		// $tabIdFusionF =explode('-',$tabIdFusionF);
		// $maxIdFusion=0;

		// if ($tabIdFusionF!=NULL) {
		// 	$maxIdFusion = max($tabIdFusionF);
		// }
		/*$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		forme='".mysql_real_escape_string($forme)."',
		tableau='".$tableau."',
		prixSession=".$prixSession.",
		idFusion=".$maxIdFusion.",
		prixPublic=".$prixPublic."
		where id=".$id;*/
    if ($idFusion==0) {
      $sql6="SELECT max(idFusion) FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
  		OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
  		codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$idD."'";
			if ($res6=mysql_query($sql6)) {
          $t6=mysql_fetch_array($res6);
          $maxIdFusion=$t6[0];
          // $sql21="update `".$catalogueTypeCateg."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
          // where id=".$t['id'];
          // $res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
      }

    }
		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		forme='".mysql_real_escape_string($forme)."',
		tableau='".$tableau."',
		prixSession=".$prixSession.",
		idFusion=".$maxIdFusion.",
		prixPublic=".$prixPublic.",
		codeBarreDesignation=".$codeBarreDesignation."
		where id=".$idD;

    // echo "<br/>".$sql;

		$res=@mysql_query($sql)or die ("modification impossibleAAA ".mysql_error());

		$sql3="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
		OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
		codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$idD."'";
    // var_dump($sql);
    // echo "<br/>".$sql3;
          if (	$res3=mysql_query($sql3)) {
    				while($t=mysql_fetch_array($res3)){
    					$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
    					INNER JOIN `aaa-boutique` B
    					ON A.idBoutique = B.idBoutique
    					WHERE A.idFusion =".$t["idFusion"];
              // echo "<br/>".$sql2;
    					$res2=mysql_query($sql2);
    					$t2=mysql_fetch_array($res2);
              if ($t2['nomBoutique'] =='' or $t2['nomBoutique'] =='NULL' ) {
                $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
              }
              else {
                $nomtableDesignation=$t2['nomBoutique']."-designation";

      					$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
      						// echo "<br/>".$sql1;
                // var_dump($sql1);

      					$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
      					where idDesignation=".$t['idDesignation'];
      					$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
            }

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
			//$categorieTypeCateg     =@$_POST["tab"];

			$sql="update `".$categorieTypeCateg."` set
			 nomCategorie='".mysql_real_escape_string($nomCategorie)."',
			categorieParent='".mysql_real_escape_string($categorieParent)."'
			where id=".$id;
      //var_dump($sql);
			$res=@mysql_query($sql)or die ("modification cqtegorie ".mysql_error());
		}

if (isset($_POST['btnSupprimerCategorie'])) {
				$id         =@$_POST["id"];
				$sql="DELETE FROM `".$categorieTypeCateg."` WHERE id=".$id;
				$res=@mysql_query($sql) or die ("suppression impossible categorie".mysql_error());
			}

//////////////////////////

if (isset($_POST['btnModifierForme'])) {
						$id         =@$_POST["id"];
						$nomForme       =@$_POST["nomForme"];
						$formeTypeCategPharmacie     =@$_POST["tab"];

						$sql="update `".$formeTypeCategPharmacie."` set
						 nomForme='".mysql_real_escape_string($nomForme)."'
						where id=".$id;
						$res=@mysql_query($sql)or die ("modification cqtegorie ".mysql_error());
					}

if (isset($_POST['btnSupprimerForme'])) {
				$id         =@$_POST["id"];
				$formeTypeCategPharmacie     =@$_POST["tab"];
				$sql="DELETE FROM `".$formeTypeCategPharmacie."` WHERE id=".$id;
				$res=@mysql_query($sql) or die ("suppression impossible categorie".mysql_error());
		}
if (isset($_POST['subImport1'])) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $headers = fgetcsv($handle, 1000, ";");


    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

        $noArt=htmlspecialchars(trim($data[0]));
        $codCIP=htmlspecialchars(trim($data[1]));
        $codeEAN=htmlspecialchars(trim($data[2]));
        $reference=htmlspecialchars(trim($data[3]));
        $categorie=htmlspecialchars(trim($data[4]));
        $forme=htmlspecialchars(trim($data[5]));
        $tableau=htmlspecialchars(trim($data[6]));
        $prixSession=$data[7];
        $prixPublic=$data[8];
        if ($categorie='' or $categorie=' ') {
          $categorie="SANS GATEGORIE";
        }
        if ($forme='' or $forme=' ') {
          $forme="SANS FORME";
        }
        if ($tableau='' or $tableau=' ') {
          $tableau="SANS TABLEAU";
        }
        if ($prixPublic=0 or $prixPublic=' ') {
          $prixPublic=0;
        }

        $idDesignation=0;
        /*$sql10="SELECT * FROM `".$catalogueTypeCateg."` where designation='".mysql_real_escape_string($reference)."'";
        $res10=mysql_query($sql10);
        if(!mysql_num_rows($res10)){
           $sql3="insert into `".$catalogueTypeCateg."` (designation,categorie,forme,tableau,prixSession,prixPublic,idDesignation,idBoutique,idFusion,categorieParent,seuil,codeBarreDesignation,codeBarreuniteStock,classe,image)
           values ('".mysql_real_escape_string($reference)."','".mysql_real_escape_string($categorie)."','".mysql_real_escape_string($forme)."',
                    '".mysql_real_escape_string($tableau)."',".$prixSession.",".$prixPublic.",0,0,0,'',0,'','',0,'')";
             //var_dump($sql3);
            $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible 1=".mysql_error());
        }*/

        $sql3="insert into `".$catalogueTypeCateg."`
          (noArt,codCIP,designation,categorie,forme,tableau,prixSession,prixPublic,idDesignation,idBoutique,
          idFusion,categorieParent,seuil,codeBarreDesignation,codeBarreuniteStock,classe,image)
       values ('".$noArt."','".mysql_real_escape_string($codCIP)."','".mysql_real_escape_string($reference)."','".mysql_real_escape_string($categorie)."','".mysql_real_escape_string($forme)."',
              '".mysql_real_escape_string($tableau)."',".$prixSession.",".$prixPublic.",0,0,0,'',0,'".$codeEAN."','',0,'')";
             //var_dump($sql3);
            $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible 1=".mysql_error());


        $sql12="SELECT * FROM `".$categorieTypeCateg."`  where nomcategorie='".mysql_real_escape_string($categorie)."'";
        $res12=mysql_query($sql12);
        if(!mysql_num_rows($res12))
                if($categorie) {
                $sql="insert into `".$categorieTypeCateg."`  (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";
                $res=@mysql_query($sql) or die ("insertion categorie CSV impossible 2 =".mysql_error());
        }
    }
    fclose($handle);
  }
}
if(isset($_POST["Export"])){
      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=catalogue-pharmacie.csv');
      $delimiter = ";";
      $output = fopen("php://output", "w");
      //$fields=array('DESIGNATION','CATEGORIE','FORME','TABLEAU','PRIX SESSION','PRIX PUBLIC');
      $fields=array('NOART','CODCIP','CODEAN','DESIGNATION','CATEGORIE','FORME','TABLEAU','PRIX SESSION','PRIX PUBLIC');
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
  			 //$typeCategorie=$tab0['type']."-".$tab0['categorie'];
  			 //$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
  			 $sql3="SELECT * from  `".$catalogueTypeCateg."` ";
  			 $res3=mysql_query($sql3); ?>
         <div>
           <?php if ($res3): ?>
             <form name="formulairePersonnel"   method="post" >
               <input type="hidden" name="" value="">
               <button type="submit" class="btn btn-primary" name="btnUpdateCatalogue">
                                   <i class="glyphicon glyphicon-plus"></i>Mettre à jour le catalogue
               </button>
               <button type="submit" class="btn btn-warning" name="btnUpdateCodeBarreCata">
                                   <i class="glyphicon glyphicon-plus"></i>Mettre à jour Code Barre
               </button>
               <button type="submit" class="btn btn-danger" name="btnUpdateCodeBarrePharma">
                                   <i class="glyphicon glyphicon-plus"></i>update Code Barre Pharma
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
		<h2>Catalogue : <?php echo $tab0['nom']; ?>  </h2>
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
</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/
?>
		<div class="container">
			<div class="container" align="center">
				        <ul class="nav nav-tabs">
				          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL" >REFERENCE</a></li>
									<li><a data-toggle="tab" href="#CATEGORIE" onclick="tabCategClick( <?php echo $_GET['i'] ?>)">CATEGORIE</a></li>
									<li><a data-toggle="tab" href="#FORME"  onclick="tabFormeClick(<?php echo $_GET['i'] ?>)">FORME</a></li>
									<li><a data-toggle="tab" href="#DOUBLON" onclick="tabDoubClick(<?php echo $_GET['i'] ?>)">DOUBLON</a></li>
									<li><a data-toggle="tab" href="#FUSION" onclick="tabFusClick(<?php echo $_GET['i'] ?>)">FUSION</a></li>
				        </ul>
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


				        <div class="tab-content">
				            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
				               <?php
                          if($res3){ ?>
                            <div id="modifierDesignation_PH"  class="modal fade " role="dialog">
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
																				<label for="designation_Mdf">REFERENCE <font color="red">*</font></label>
																				<input type="text" class="form-control" id="designation_Mdf"  required />
																			</div>
																			<div class="form-group">
																				<label for="categorie_Mdf"> CATEGORIE <font color="red">*</font></label>
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
																				<label for="forme_Mdf"> FORME <font color="red">*</font></label>
																				<select class="form-control" id="forme_Mdf"  >
																						<option selected ></option>
																						<?php $sql11="SELECT * from  `".$formeTypeCategPharmacie."` ";
																							$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
																							while($ligne2 = mysql_fetch_array($res11)) {
																								echo'<option  value= "'.$ligne2['nomForme'].'">'.$ligne2['nomForme'].'</option>';
																							}
																						?>
																				</select>
																			</div>
																			<div class="form-group">
																				<label for="tableau_Mdf"> TABLEAU <font color="red">*</font></label>
																				<select class="form-control" id="tableau_Mdf"  >
																						<option selected ></option>
																						<option>SANS</option>
																						<option>A</option>
																						<option>C</option>
																				</select>
																			</div>
																			<div class="form-group" >
																				<label for="prixSession_Mdf">PRIX SESSION <font color="red">*</font> </label>
																				<input type="number" class="form-control"   id="prixSession_Mdf"  required />
																			</div>
																			<div class="form-group" >
																				<label for="prixPublic_Mdf">PRIX PUBLIC </label>
																				<input type="number" class="form-control"   id="prixPublic_Mdf"  />
																			</div>
																			<div class="form-group" >
																			<font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
																				<input type="button" class="boutonbasic"  id="btnModifierDesignation_PH" value="MODIFIER  >>"/>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>

														<div id="supprimerDesignation_PH"  class="modal fade " role="dialog">
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
																				<label for="forme_Spm"> FORME : </label>
																				<span id="forme_Spm"></span>
																			</div>
																			<div class="form-group" >
																				<label for="tableau_Spm"> TABLEAU : </label>
																				<span id="tableau_Spm"></span>
																			</div>
																			<div class="form-group" >
																				<label for="prixSession_Spm">PRIX SESSION : </label>
																				<span id="prixSession_Spm"></span>
																			</div>
																			<div class="form-group" >
																				<label for="prixPublic_Spm">PRIX PUBLIC : </label>
																				<span id="prixPublic_Spm"></span>
																			</div>
																				<div class="form-group" align="right">
																				<input type="button" class="boutonbasic"  id="btnSupprimerDesignation_PH" value="SUPPRIMER  >>"/>
																				</div>
																			</form>
																	</div>
																</div>
															</div>
														</div>

														<div id="codeBRDesignation_PH"  class="modal fade " role="dialog">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header" style="padding:35px 50px;">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Code barre d\'un Produit </b></h4>
																	</div>
																	<div class="modal-body" style="padding:40px 50px;">
																		<form role="form" >
                                      <input type="hidden" id="idCT_Cbr" />
                                      <input type="hidden" id="idDesignation_Cbr" />
                                      <input type="hidden" id="ordre_Cbr" />
																			<div class="form-group">
																				<label for="designation_Cbarre">REFERENCE : </label>
																				<span  id="designation_Cbarre"></span>
																			</div>
																			<div class="form-group">
																				<label for="categorie_Cbarre"> CATEGORIE : </label>
																				<span id="categorie_Cbarre"></span>
																			</div>
																			<div class="form-group">
																				<label for="forme_Cbarre"> FORME : </label>
																				<span id="forme_Cbarre"></span>
																			</div>
																			<div class="form-group" >
																				<label for="tableau_Cbarre"> TABLEAU : </label>
																				<span id="tableau_Cbarre"></span>
																			</div>
																			<!-- <div class="form-group" >
																				<label for="valeur_Cbarre">CODE BARRE : </label>
																				<span id="valeur_Cbarre"></span>
																			</div> -->
                                      <div class="form-group">
                                        <label for="codeBD">CodeBarre Designation </label>
                                        <input type="text" class="inputbasic form-control" id="valeur_Cbarre" autofocus=""  size="20" required />
                                      </div>
                                      <div class="form-group" align="right">
                                      <input type="button" class="boutonbasic"  id="btnModifierCBarre_PH" value="MODIFIER  >>"/>
                                      </div>
																		</form>
																	</div>
																</div>
															</div>
														</div>

                            <table id="tableDesignation" class="display" border="1" >
                                <thead>
                                    <tr>
                                      <th>REFERENCE</th>
                                      <th>IDFUSION</th>
                                      <th>CATEGORIE</th>
                                      <th>FORME</th>
                                      <th>TABLEAU</th>
                                      <th>PRIX SESSION</th>
                                      <th>PRIX PUBLIC</th>
                                      <th>OPERATION</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                  <th>REFERENCE</th>
                                  <th>IDFUSION</th>
                                  <th>CATEGORIE</th>
                                  <th>FORME</th>
                                  <th>TABLEAU</th>
                                  <th>PRIX SESSION</th>
                                  <th>PRIX PUBLIC</th>
                                  <th>OPERATION</th>
                                </tr>
                              </tfoot>
                            </table>
														<script type="text/javascript">
															$(document).ready(function() {

                                if ($.fn.DataTable.isDataTable('#tableDesignation')) {
                                      $('#tableDesignation').DataTable().destroy();
                                  }

																$("#tableDesignation").dataTable({
																"bProcessing": true,
																"sAjaxSource": "ajax/catalogueDetailsProduits_PHAjax.php?operation=details&id=<?php echo $tab0['id']; ?>",
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
                              <!--
  														<script type="text/javascript">
  															$(document).ready(function() {

  															});
  														</script> -->
                              <div id="modifierCategorie_PH"  class="modal fade " role="dialog">
																	<div class="modal-dialog">
																			<div class="modal-content">
																					<div class="modal-header" style="padding:35px 50px;">
																							<button type="button" class="close" data-dismiss="modal">&times;</button>
																							<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification categorie </b></h4>
																					</div>
																				<div class="modal-body" style="padding:40px 50px;">
																						<form role="form" class=""   name="formulaire2" method="post" >
																								<input type="hidden" name="id" id="idCate_Mdf" />
																								<input type="hidden" name="ordre_Mdf" id="ordreC_Mdf" />
																								<input type="hidden" name="idCataT_Mdf" id="idCataT_Mdf" />
																								<div class="form-group">
																										<label for="reference">CATEGORIE <font color="red">*</font></label>
																										<input type="text" class="form-control" name="nomCategorie" id="nomCategorie_Mdf"   value="" required />
																										<input type="hidden" class="form-control" name="nomCategorie_old" id="nomCategorie_old"   value="" />
																								</div>
																								<div class="form-group">
																										<label for="reference">CATEGORIE PARENT <font color="red">*</font></label>
																										<input type="text" class="form-control" name="categorieParent" id="categorieParent_Mdf"  value=""  />
																								</div>

																								<div class="form-group" align="right">
																								 <br />
																								     <input type="button" class="boutonbasic"  id="btnModifierCategorie_PH" value="MODIFIER  >>"/>
								                                 </div>
							                              </form>
																			   </div>
																			</div>
																	</div>
															</div>
                              <div id="supprimerCategorie_PH"  class="modal fade " role="dialog">
																	<div class="modal-dialog">
																			<div class="modal-content">
																					<div class="modal-header" style="padding:35px 50px;">
																							<button type="button" class="close" data-dismiss="modal">&times;</button>
																							<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification categorie </b></h4>
																					</div>
																				<div class="modal-body" style="padding:40px 50px;">
																						<form role="form" class=""   name="formulaire2" method="post" >
																								<input type="hidden" name="id" id="idCate_Spm" />
																								<input type="hidden" name="ordre_Mdf" id="ordreC_Spm" />
																								<input type="hidden" name="idCataT_Mdf" id="idCataT_Spm" />
																								<div class="form-group">
																										<label for="reference">CATEGORIE <font color="red">*</font></label>
																										<input type="text" class="form-control" name="nomCategorie" id="nomCategorie_Spm"   value="" disabled />
																								</div>
																								<div class="form-group">
																										<label for="reference">CATEGORIE PARENT <font color="red">*</font></label>
																										<input type="text" class="form-control" name="categorieParent" id="categorieParent_Spm"  value="" disabled />
																								</div>

																								<div class="form-group" align="right">
																								 <br />
																								     <input type="button" class="boutonbasic"  id="btnSupprimererCategorie_PH" value="SUPPRIMER  >>"/>
								                                 </div>
							                              </form>
																			   </div>
																			</div>
																	</div>
															</div>
												 <?php } ?>
										</div>
										<div class="tab-pane fade " id="FORME">
				                    <?php
															   $sql15="SELECT * from  `".$formeTypeCategPharmacie."` ";

															 if($res15=mysql_query($sql15)){ ?>
									                   	<table id="tableForme" class="display" border="1" width="100%" align="left">
															<thead>
																<tr>
																	<th>ORDRE</th>
																	<th>NOM FORME</th>
																	<th>OPERATION</th>
																</tr>
															</thead>
															<tfoot>
																<tr>
																	<th>ORDRE</th>
																	<th>NOM FORME</th>
																	<th>OPERATION</th>
																</tr>
															</tfoot>
														</table>

  														<!-- <script type="text/javascript">
  															$(document).ready(function() {

  															});
  														</script> -->
                            <div id="modifierForme_PH"  class="modal fade " role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding:35px 50px;">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification categorie </b></h4>
                                        </div>
                                      <div class="modal-body" style="padding:40px 50px;">
                                          <form role="form" class=""   name="formulaire2" method="post" >
                                              <input type="hidden" name="id" id="idForme_Mdf" />
                                              <input type="hidden" name="ordre_Mdf" id="ordreF_Mdf" />
                                              <input type="hidden" name="idCataT_Mdf" id="idCataTF_Mdf" />
                                              <div class="form-group">
                                                  <label for="reference">FORME <font color="red">*</font></label>
                                                  <input type="text" class="form-control" name="nomForme" id="nomForme_Mdf"   value="" required />
                                                  <input type="hidden" class="form-control" name="nomForme_old" id="nomForme_old"   value=""  />
                                              </div>
                                              <div class="form-group" align="right">
                                               <br />
                                                   <input type="button" class="boutonbasic"  id="btnModifierForme_PH" value="MODIFIER  >>"/>
                                               </div>
                                          </form>
                                       </div>
                                    </div>
                                </div>
                            </div>
                            <div id="supprimerForme_PH"  class="modal fade " role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding:35px 50px;">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification categorie </b></h4>
                                        </div>
                                      <div class="modal-body" style="padding:40px 50px;">
                                          <form role="form" class=""   name="formulaire2" method="post" >
                                              <input type="hidden" name="id" id="idForme_Spm" />
                                              <input type="hidden" name="ordre_Spm" id="ordreF_Spm" />
                                              <input type="hidden" name="idCataT_Spm" id="idCataTF_Spm" />
                                              <div class="form-group">
                                                  <label for="reference">FORME <font color="red">*</font></label>
                                                  <input type="text" class="form-control" name="nomForme" id="nomForme_Spm"   value="" disabled />
                                              </div>
                                              <div class="form-group" align="right">
                                               <br />
                                                   <input type="button" class="boutonbasic"  id="btnSupprimerForme_PH" value="SUPPRIMER  >>"/>
                                               </div>
                                          </form>
                                       </div>
                                    </div>
                                </div>
                            </div>
												 <?php } ?>
										</div>
                    <div class="tab-pane fade " id="DOUBLON">
                      <?php
                         if($res3){ ?>
                           
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
																<?php echo 'onclick="eli_tousDoub_PH('.$_GET['i'].')" '; ?> >Enregistrer</button>
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
	                                             <input type="hidden" name="idCT" id="idCT" value="" />
	                                             <table id="tableDo" class="display " border="1">
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
	                                                   <input type="submit" class="boutonbasic"  name="btnEliminerDoublons" value="Eliminer>>"/>
	                                               </div>
	                                           </form><br />
	                                        </div>
	                                   </div>
	                               </div>
	                           </div>
	                        <!-- Fin Popup pour doublon -->
	                        
                           <table id="tableDoublon" class="display" border="1" >
                               <thead>
                                   <tr>
                                     <th>REFERENCE</th>
                                     <th>IDFUSION</th>
                                     <th>CATEGORIE</th>
                                     <th>FORME</th>
                                     <th>TABLEAU</th>
                                     <th>PRIX SESSION</th>
                                     <th>PRIX PUBLIC</th>
                                     <th>OPERATION</th>
                                   </tr>
                               </thead>
                               <tfoot>
                               <tr>
                                 <th>REFERENCE</th>
                                 <th>IDFUSION</th>
                                 <th>CATEGORIE</th>
                                 <th>FORME</th>
                                 <th>TABLEAU</th>
                                 <th>PRIX SESSION</th>
                                 <th>PRIX PUBLIC</th>
                                 <th>OPERATION</th>
                               </tr>
                             </tfoot>
                           </table>
                            <!-- <script type="text/javascript">
                              $(document).ready(function() {

                              });
                            </script> -->
                       <?php } ?>
										</div>
                    <div class="tab-pane fade " id="FUSION">

                      <?php
                         if($res3){ ?>



                             <div id="fu"  class="modal fade bd-example-modal-xl" tabindex="-1"   role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                           <div class="modal-dialog modal-lg">
                                               <div class="modal-content">
                                                   <div class="modal-header" style="padding:35px 50px;">
                                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                       <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Fusion catalogue </b></h4>
                                                   </div>
                                                   <div class="modal-body" >
                                                     <form id="commentForm" method="post" >
                                                      <input type="hidden" name="id" id="idF"/>
                                                      <input type="hidden" name="idCT" id="idCTF"/>
                                                      <input type="hidden" name="designation" />
                                                      <input type="hidden" class="form-control" id="categoriePH" name="categorie" >
                                                      <input type="hidden" class="form-control" id="formPH" name="forme"  >
                                                      <input type="hidden" class="form-control" id="tableauPH" name="tableau" aria-describedby="inputGroupPrepend" >
                                                      <input type="hidden" class="form-control" id="prixSessionPH" aria-describedby="inputGroupPrepend" name="prixSession"  required>
                                                      <input type="hidden" class="form-control" id="prixPublicPH" aria-describedby="inputGroupPrepend" name="prixPublic"  required>
                                                      <input type="hidden" class="form-control" id="codeBarreDesignationPH" aria-describedby="inputGroupPrepend" name="codeBarreDesignation"  >
                                                           <table  id="tableFu" class="display " border="1">
                                                             <thead >
                                                                 <tr>
                                                                   <th>REFERENCE</th>
                                                                   <th>CATEGORIE</th>
                                                                   <th>FORME</th>
                                                                   <th>TABLEAU</th>
                                                                   <th>PRIX SESSION</th>
                                                                   <th>PRIX PUBLIC</th>
                                                                   <th>CODE BARRE</th>
                                                                 </tr>
                                                               </thead>
                                                               <tfoot >
                                                                 <tr>
                                                                   <th>REFERENCE</th>
                                                                   <th>CATEGORIE</th>
                                                                   <th>FORME</th>
                                                                   <th>TABLEAU</th>
                                                                   <th>PRIX SESSION</th>
                                                                   <th>PRIX PUBLIC</th>
                                                                   <th>CODE BARRE</th>
                                                                 </tr>
                                                               </tfoot>
                                                               <tbody>


                                                               </tbody>

                                                           </table>

                                                           <div class="form-group" align="left">
                                                               <input type="submit" class="btn btn-primary btn-sm" id="btnFusion" name="btnFusion" value="Fusionner>>"/>
                                                           </div>
                                                    </form>
                                                   </div>

                                              </div>
                                         </div>
                             </div>


                           <table id="tableFusion" class="display" border="1" >
                               <thead>
                                   <tr>
                                     <th>REFERENCE</th>
                                     <th>IDFUSION</th>
                                     <th>CATEGORIE</th>
                                     <th>FORME</th>
                                     <th>TABLEAU</th>
                                     <th>PRIX SESSION</th>
                                     <th>PRIX PUBLIC</th>
                                     <th>OPERATION</th>
                                   </tr>
                               </thead>
                               <tfoot>
                               <tr>
                                 <th>REFERENCE</th>
                                 <th>IDFUSION</th>
                                 <th>CATEGORIE</th>
                                 <th>FORME</th>
                                 <th>TABLEAU</th>
                                 <th>PRIX SESSION</th>
                                 <th>PRIX PUBLIC</th>
                                 <th>OPERATION</th>
                               </tr>
                             </tfoot>
                           </table>
                              <!-- <script type="text/javascript">
                                $(document).ready(function() {

                                });
                              </script> -->
                       <?php } ?>
										</div>
							  </div>
				</div>
		</div>
	</body>
</html>
