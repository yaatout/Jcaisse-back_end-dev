<?php
	/*
	R�sum�:
	Commentaire:
	version:1.1
	Auteur:EL hadji mamadou korka
	Date de modification:25-03-2020
	*/
	$formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
	$tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
	$forme=NULL;
	$tableau=NULL;
	/*************************************/
	if (isset($_POST['btnGenererCatalogue'])) {

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
															`designation` VARCHAR(50) NOT NULL,
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
															 `classe` INT NOT NULL,
															 `image` VARCHAR(155) NOT NULL,
															 PRIMARY KEY (`id`)) ENGINE = MYISAM";

											$res0 =@mysql_query($sql0) or die ("creation table catalogue impossible Pharmacie  ".mysql_error());


											 $sql14="CREATE TABLE IF NOT EXISTS `".$categorieTypeCateg."`
				 												 (`id` INT NOT NULL AUTO_INCREMENT,
				 													 `idCategorie` INT NOT NULL,
				 													 `nomCategorie` VARCHAR(155) NOT NULL,
				 													 `categorieParent` VARCHAR(155) NOT NULL,
																	 UNIQUE (nomCategorie),
																	 `idBoutique` INT NOT NULL,
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
								 $table=$boutique['nomBoutique']."-designation";
								 $nomtableCategorie   = $boutique['nomBoutique']."-categorie";
								 $sql4="SELECT * FROM `".$table."` where categorie !='Sans'";

									 if ($res4 = mysql_query($sql4)) {
										 while ($des=mysql_fetch_array($res4)) {
											 /*****************************************/

											 $sql6="SELECT * from  `".$nomtableCategorie."`  where nomcategorie='".$des['categorie'] ."'";
											 $res6=mysql_query($sql6);
											 $tab6=mysql_fetch_array($res6);

											 $sql7="SELECT nomcategorie from  `".$nomtableCategorie."`  where idcategorie='".$tab6['categorieParent'] ."'";
											$res7=mysql_query($sql7);
											$tab7=mysql_fetch_array($res7);
											 /******************************************/
											/* $sql1="insert IGNORE into `".$catalogueTypeCateg."`
													 (designation,idDesignation,idBoutique,categorie,categorieParent,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,codeBarreDesignation,codeBarreuniteStock) values
													 ('".$des['designation']."','".$des['idDesignation']."','".$boutique['idBoutique']."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['uniteStock']."','".$des['prix']."','".$des['nbreArticleUniteStock']."','"
													 .$des['prixuniteStock']."','".$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."')";*/

														 $sql1="insert into `".$catalogueTypeCateg."`
																(`designation`  ,`categorie` ,categorieParent,`forme` ,`tableau`,`prixSession` ,`prixPublic` ,`codeBarreDesignation` ,`codeBarreuniteStock` ,`classe`) values
																('".$des['designation']."','".$des['categorie']."','".$tab7['nomcategorie']."','".$des['forme']."','".$des['tableau']."',
																 '".$des['prixSession']."','".$des['prixPublic']."','"
																.$des['codeBarreDesignation']."','".$des['codeBarreuniteStock']."','".$des['classe']."')";
																$forme[] =$des['forme'];
																$tableau[] =$des['tableau'];
													 $res1=@mysql_query($sql1) or die ("insertion catalogue impossible-5p".mysql_error());

										 }
									 }

									 /********************** aaa-categorie-ddd-dddd ******************/

									 $boutiqueCategorie=$boutique['nomBoutique']."-categorie";
									 $sql15="SELECT * FROM `".$boutiqueCategorie."` ";
									 if ($res15 = mysql_query($sql15)) {
											while ($cat=mysql_fetch_array($res15)) {
												$sql16="insert into `".$categorieTypeCateg."`
														(idCategorie,nomCategorie,categorieParent,idBoutique) values
														('".$cat['idcategorie']."','".$cat['nomcategorie']."','".$cat['categorieParent']."','".$boutique['idBoutique']."')";

											 			$res16=@mysql_query($sql16) ;
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
		$designation         =@$_POST["designation"];
		$sql="SELECT * from  `".$catalogueTypeCateg."` where designation='".$designation."' and id !='".$id."'";
			if (	$res=mysql_query($sql)) {
				while($t=mysql_fetch_array($res)){
					 $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
			 		$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
				 }
			}
		/**/
	}
if (isset($_POST['btnFusion'])) {
		$id         =@$_POST["id"];
		$catalogueTypeCateg     =@$_POST["tab"];

		$designation    =@$_POST["designation"];
		$categorie      =@$_POST["categorie"];
		$forme          =@$_POST["forme"];
		$tableau 				=@$_POST["tableau"];
		$prixSession    =@$_POST["prixSession"];
		$prixPublic     =@$_POST["prixPublic"];

		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		forme='".mysql_real_escape_string($forme)."',
		tableau='".$tableau."',
		prixSession=".$prixSession.",
		prixPublic=".$prixPublic."
		where id=".$id;
		$res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

		$sql="SELECT * from  `".$catalogueTypeCateg."` where designation='".$designation."' and id !='".$id."'";
			if (	$res=mysql_query($sql)) {
				while($t=mysql_fetch_array($res)){
					 $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
			 		$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
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
		<div class="col-sm-2 pull-right" >
			<form name="formulaireInfo" id="formulaireInfo" method="post" action="ajax/designationInfo.php">
					<div class="form-group" >
					</div>
			</form>
		</div>
		<h2>Catalogue : <?php echo $tab0['nom']; ?>  </h2>


	</div>
	<div class="row" align="center">
		<form name="formulairePersonnel"   method="post" >
<input type="hidden" name="" value="">
			<button type="submit" class="btn btn-success" name="btnGenererCatalogue">
													<i class="glyphicon glyphicon-plus"></i>Generer un catalogue
				</button>
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
				          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">REFERENCE</a></li>
									<li><a data-toggle="tab" href="#CATEGORIE">CATEGORIE</a></li>
									<li><a data-toggle="tab" href="#FORME">FORME</a></li>
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
									             // $sql3='SELECT * from  `aaa-catalogue-alimentaire-detaillant`  order by id desc';
															 // $sql3="SELECT * from  `catalogueTotal`  where id=".$_GET['id']."";
															 $typeCategorie=$tab0['type']."-".$tab0['categorie'];
													 	 	 $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
															    $sql3="SELECT * from  `".$catalogueTypeCateg."` ";

															 if($res3=mysql_query($sql3)){ ?>
									                   <table id="exemple" class="display" border="1" id="userTable">
																					     <thead>
																									 <tr>
																										 <th>REFERENCE</th>
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
 																								     <th>CATEGORIE</th>
 																								     <th>FORME</th>
 																								     <th>TABLEAU</th>
 																								     <th>PRIX SESSION</th>
 																								     <th>PRIX PUBLIC</th>
																											<th>OPERATION</th>
																										</tr>
																									</tfoot>
																						<?php
																						while($tab3=mysql_fetch_array($res3)){
																										echo'<tr><td>'.$tab3["designation"].'</td>
																										<td>'.$tab3["categorie"].'</td>
																										<td>'.$tab3["forme"].'</td>
																										<td>'.$tab3["tableau"].'</td>
																										<td>'.$tab3["prixSession"].'</td>
																										<td>'.$tab3["prixPublic"].'</td>'; ?>
																										<td>
																													<?php 	echo '<a><img src="images/edit.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgmodifier'.$tab3["id"].'" /></a>';  ?>
																												<?php 	echo '<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab3["id"].'" /></a>';  ?>

																												<?php echo  '<div id="imgmodifier'.$tab3["id"].'"  class="modal fade " role="dialog">
																													<div class="modal-dialog">
																																<div class="modal-content">
																																	<div class="modal-header" style="padding:35px 50px;">
																																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																																		<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'un Produit </b></h4>
																																	</div>
																																	<div class="modal-body" style="padding:40px 50px;">
																											                            <form role="form" class=""   name="formulaire2" method="post" >
																																									<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																									<input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/>
																											                                <div class="form-group">
																											                                  <label for="reference">REFERENCE <font color="red">*</font></label>
																											                                  <input type="text" class="form-control" name="designation"   value="'.$tab3["designation"].'" required />
																											                                </div>
																																											<div class="form-group">
																											                                  <label for="categorie"> CATEGORIE <font color="red">*</font></label>
																											                                  <select class="form-control" name="categorie2"  >
																											                                        <option selected value= "'.$tab3["categorie"].'">'.$tab3["categorie"].'</option>';

																											                                            $sql11="SELECT * FROM `". $categorieTypeCateg."`";
																											                                            $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
																											                                            while($ligne2 = mysql_fetch_array($res11)) {
																											                                                echo'<option  value= "'.$ligne2['nomCategorie'].'">'.$ligne2['nomCategorie'].'</option>';

																											                                              }
																											                                    echo'</select>
																											                                </div>

																																											<div class="form-group">
																																		                    <label for="codeBD">CodeBarre Designation </label>
																																		                    <input type="text" class="form-control" name="codeBarreDesignation"  value="'.$tab3["codeBarreDesignation"].'"  />
																																		                  </div>
																																		                  <div class="form-group">
																																		                    <label for="codeBU">CodeBarre Unite</label>
																																		                    <input type="text" class="form-control" name="codeBarreuniteStock"  value="'.$tab3["codeBarreuniteStock"].'"  />
																																		                  </div>'
																																											;	?>
																																									<?php																									                                echo '<div class="form-group" align="right">
																											                                <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
																											                                  <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>

																											                                 </div>
																											                              </form>
																																	</div>
																																</div>
																															</div>
																													</div>';?>
																													<!--  -->
																													<!-- SUPPRIMER -->
																												<?php echo  '<div id="imgsup'.$tab3["id"].'"  class="modal fade " role="dialog">
																														<div class="modal-dialog">
																																	<div class="modal-content">
																																		<div class="modal-header" style="padding:35px 50px;">
																																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																																			<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression </b></h4>
																																		</div>
																																		<div class="modal-body" style="padding:40px 50px;">
																																										<form role="form" class=""   name="formulaire2" method="post" >
																																										<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																										<input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/>
																																												<div class="form-group">
																																													<label for="reference">REFERENCE <font color="red">*</font></label>
																																													<input type="text" class="form-control" name="designation"   value="'.$tab3["designation"].'" disabled="" />
																																												</div>
																																												<div class="form-group">
																																													<label for="reference">CATEGORIE <font color="red">*</font></label>
																																													<input type="text" class="form-control" name="categorie"  value="'.$tab3["categorie"].'" disabled="" />
																																												</div>


																																												<div class="form-group">
																																													<label for="codeBD">CodeBarre Designation </label>
																																													<input type="text" class="form-control" name="codeBarreDesignation"  value="'.$tab3["codeBarreDesignation"].'" disabled />
																																												</div>
																																												<div class="form-group">
																																													<label for="codeBU">CodeBarre Unite</label>
																																													<input type="text" class="form-control" name="codeBarreuniteStock"  value="'.$tab3["codeBarreuniteStock"].'" disabled />
																																												</div>
																																												';	?>
																																										<?php																									                                echo '<div class="form-group" align="right">
																																												<font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
																																													<input type="submit" class="boutonbasic"  name="btnSupprimer" value="SUPPRIMER  >>"/>

																																												 </div>
																																											</form>
																																		</div>
																																	</div>
																																</div>
																														</div>';?>
																													<!--  -->

																											<?php 	 ?>
																											<?php
																														if ($tab3["image"]) {
																															echo '<a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#app'.$tab3["id"].'" /></a>';
																															echo  '<div id="app'.$tab3["id"].'"  class="modal fade " role="dialog">
																																	<div class="modal-dialog">
																																				<div class="modal-content">
																																					<div class="modal-header" style="">
																																						<button type="button" class="close" data-dismiss="modal">&times;</button>
																																						<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
																																					</div>
																																					<div class="modal-body" style="">
																																					<img src="uploads/'.$tab3["image"].'" />
																																					<form   method="post" enctype="multipart/form-data">
																																							<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																							<input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/>
																																							<input type="hidden" name="image" value="'.$tab3["image"].'"/>
																																							<div class="form-group" >
																																							<b> <b><br />
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
																																	</div>';
																														}
																														else {
																															echo '<a><img src="images/iconfinder9.png" align="middle" alt="img"  data-toggle="modal" data-target="#img'.$tab3["id"].'" /></a>';
																															echo  '<div id="img'.$tab3["id"].'"  class="modal fade " role="dialog">
																																	<div class="modal-dialog">
																																				<div class="modal-content">
																																					<div class="modal-header" style="padding:35px 50px;">
																																						<button type="button" class="close" data-dismiss="modal">&times;</button>
																																						<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>
																																					</div>
																																					<div class="modal-body" style="padding:40px 50px;">
																																							<form   method="post" enctype="multipart/form-data">
																																									<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																									<input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/>
																																									<div class="form-group" >
																																									<b> <b><br />
																																										<input type="file" name="file" />
																																									</div>
																																									<div class="form-group" align="right">
																																											<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Upload >>"/>
																																									</div>
																																							</form>
																																					</div>
																																				</div>
																																			</div>
																																	</div>';
																														}

																											 ?>

	 																													<?php if (!is_null($doublons)) {
																															if (in_array($tab3["id"],$doublons)) {
	 																															echo '<a>  <button type="button" id="idtest" name="button" data-toggle="modal" data-target="#do'.$tab3["id"].'">Doublon</button></a>';

																																																														echo  '<div id="do'.$tab3["id"].'"  class="modal fade bd-example-modal-xl" tabindex="-1"   role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
																																																																<div class="modal-dialog modal-lg">
																																																																			<div class="modal-content">
																																																																				<div class="modal-header" style="padding:35px 50px;">
																																																																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																																																																					<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Eliminer doublons </b></h4>
																																																																				</div>
																																																																				<div class="modal-body" style="padding:40px 50px;">
																																																																				<form name="formulaireInitialStock" method="post" ><table id="exemple2" class="display" border="1"><thead><tr>
																																																																							<th>REFERENCE</th>
																																																																						 <th>CATEGORIE</th>
																																																																						 <th>FORME</th>
																																																																						 <th>TABLEAU</th>
																																																																						 <th>PRIX SESSION</th>
																																																																						 <th>PRIX PUBLIC</th>
																																																																				</tr></thead>';
																																																																			$sql13="SELECT * from  `".$catalogueTypeCateg."` where designation='".$tab3["designation"]."'";
																																																																				if (	$res13=mysql_query($sql13)) {
																																																																					while($t=mysql_fetch_array($res13)){
																																																					 																	 echo'<tr> <td>'.$t["designation"].'</td>
																																																					 																	 <td>'.$t["categorie"].'</td>
																																																					 																	 <td>'.$t["forme"].'</td>
																																																					 																	 <td>'.$t["tableau"].'</td>
																																																					 																	 <td>'.$t["prixSession"].'</td>
																																																					 																	 <td>'.$t["prixPublic"].'</td>
																																																					 																	 </tr>';
																																																					 																 }
																																																																				}
																																																																				echo'

																																																																				<tfoot><tr>
																																																																				<th>REFERENCE</th>
																																																																			 <th>CATEGORIE</th>
																																																																			 <th>FORME</th>
																																																																			 <th>TABLEAU</th>
																																																																			 <th>PRIX SESSION</th>
																																																																			 <th>PRIX PUBLIC</th>
																																																																					</tr></tfoot>';
																																																																		 	echo '	</table>
																																																																			<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																																																			<input type="hidden" name="designation" value="'.$tab3["designation"].'"/>
																																																																							<input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/>
																																																																							<div class="form-group" align="right">
																																																																									<input type="submit" class="boutonbasic"  name="btnEliminerDoublons" value="Eliminer>>"/>
																																																																							</div>
																																																																					  </form><br />
																																																																				</div>
																																																																			</div>
																																																																		</div>
																																																																</div>';
																															}

	 																													}
																														if (!is_null($fusions)) {
																															if (in_array($tab3["id"],$fusions)) {
																																echo '<a>  <button type="button" name="button" data-toggle="modal" data-target="#fu'.$tab3["id"].'">Fusion</button></a>';
																																																														echo  '<div id="fu'.$tab3["id"].'"  class="modal fade bd-example-modal-lg" tabindex="-1"  role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
																																																																<div class="modal-dialog modal-lg">
																																																																			<div class="modal-content">
																																																																				<div class="modal-header" >
																																																																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																																																																					<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Fusionner references </b></h4>
																																																																				</div>

																																																																				<div class="modal-body" style="padding:40px 50px;">

																																																																				<table  class="table table-dark  table-striped" id="exemple3">
																																																																					<thead class=" ">
																																																																						<tr>
																																																																						<th>REFERENCE</th>
																																																																					 <th>CATEGORIE</th>
																																																																					 <th>FORME</th>
																																																																					 <th>TABLEAU</th>
																																																																					 <th>PRIX SESSION</th>
																																																																					 <th>PRIX PUBLIC</th>
																																																																						</tr>
																																																																					</thead>  <tbody>';
																																																																			$sql13="SELECT * from  `".$catalogueTypeCateg."` where designation='".$tab3["designation"]."'";
																																																																				if (	$res13=mysql_query($sql13)) {
																																																																					while($t=mysql_fetch_array($res13)){
																																																																						 echo'<tr><form class="form"  method="post" >
																																																																						 <td> <input type="checkbox" class="rad" name="ph" value="'.$t["designation"].'" id="designation-'.$t['id'].'" /> <label >'.$t["designation"].'</label></td>
																																																							                               <td> <input type="checkbox" class="rad" name="ph" value="'.$t["categorie"].'" id="categorie-'.$t['id'].'" /> <label >'.$t["categorie"].'</label></td>
																																																							                               <td> <input type="checkbox" class="rad" name="ph" value="'.$t["forme"].'" id="forme-'.$t['id'].'" /> <label >'.$t["forme"].'</label></td>
																																																							                               <td> <input type="checkbox" class="rad" name="ph" value="'.$t["tableau"].'" id="tableau-'.$t['id'].'" /> <label >'.$t["tableau"].'</label></td>
																																																							                               <td> <input type="checkbox" class="rad" name="ph" value="'.$t["prixSession"].'" id="prixSession-'.$t['id'].'" /> <label >'.$t["prixSession"].'</label></td>
																																																							                               <td> <input type="checkbox" class="rad" name="ph" value="'.$t["prixPublic"].'" id="prixPublic-'.$t['id'].'" /> <label >'.$t["prixPublic"].'</label></td>
																																																																						 ';
																																																																							echo'
																																																							 							 						</form></tr>';
																																																																					 }
																																																																				}
																																																																			echo '	  </tbody></table>
																																																																			<div class="alert alert-success" style="margin:0px;padding:0px;" role="alert">
																																																																				<form id="commentForm" method="post">
																																																																						<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																																																					 <input type="hidden" name="designation" value="'.$tab3["designation"].'"/>
																																																																					 <input type="hidden" name="tab" value="'.$catalogueTypeCateg.'"/>
																																																																					<div class="form-row">
																																																																						<div class="col-md-2 1">
																																																																							<label for="validationCustom01">Designation</label>
																																																																							<input type="text" class="form-control" id="designationPH" name="designation" required>
																																																																						</div>
																																																																						<div class="col-md-2  >
																																																																							<label for="validationCustom02">Categorie</label>
																																																																							<input type="text" class="form-control" id="categoriePH" name="categorie" required>
																																																																						</div>
																																																																						<div class="col-md-2  ">
																																																																							<label for="validationCustomUsername">Forme</label>
																																																																							<div class="input-group">
																																																																								<input type="text" class="form-control" id="formPH" name="form"  required>
																																																																							</div>
																																																																						</div>
																																																																						<div class="col-md-2  ">
																																																																							<label for="validationCustomUsername">Tableau</label>
																																																																							<div class="input-group">
																																																																								<input type="text" class="form-control" id="tableauPH" name="tableau" aria-describedby="inputGroupPrepend"  required>
																																																																							</div>
																																																																						</div>
																																																																						<div class="col-md-2  ">
																																																																							<label for="validationCustomUsername">Prix Session</label>
																																																																							<div class="input-group">
																																																																								<input type="text" class="form-control" id="prixSessionPH" aria-describedby="inputGroupPrepend" name="prixSession"  required>
																																																																							</div>
																																																																						</div>
																																																																						<div class="col-md-2  ">
																																																																							<label for="validationCustomUsername">Prix Publique</label>
																																																																							<div class="input-group">
																																																																								<input type="text" class="form-control" id="prixPublicPH" aria-describedby="inputGroupPrepend" name="prixPublic"  required>

																																																																							</div>
																																																																						</div>
																																																																								<hr> <button class="btn btn-primary btn-sm" id="btnFusion" name="btnFusion" type="submit">Fusionner</button>
																																																																							</div>
																																																																						</form>
																																																																					</div>


																																																																				</div>
																																																																			</div>
																																																																		</div>
																																																																</div>';

																																}
																														}
 ?>
																										</td>
																							</tr>
																					<?php  }
																					?>
																		 </table>
												 <?php } ?>
										</div>
										<div class="tab-pane fade " id="CATEGORIE">
				                    <?php
															   $sql15="SELECT * from  `".$categorieTypeCateg."` ";

															 if($res15=mysql_query($sql15)){ ?>
									                   <table id="exemple2" class="display" border="1" >
																					     <thead>
																									 <tr>
																												<th>CATEGORIE</th>
																												<th>CATEGORIE PARENT</th>
																												<th>BOUTIQUE</th>
																												<th>OPERATION</th>
																									</tr>
																							 </thead>
																									<tfoot>
																										<tr>
																											<th>CATEGORIE</th>
																											<th>CATEGORIE PARENT</th>
																											<th>BOUTIQUE</th>
																											<th>OPERATION</th>
																										</tr>
																									</tfoot>
																						<?php
																						while($tab4=mysql_fetch_array($res15)){
																										echo'<tr><td>'.$tab4["nomCategorie"].'</td>
																										<td>'.$tab4["categorieParent"].'</td>
																										<td>'.$tab4["idBoutique"].'</td> '; ?>
																										<td>
																													<?php 	echo '<a><img src="images/edit.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgmodifier'.$tab4["id"].'" /></a>';  ?>
																												<?php 	echo '<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab4["id"].'" /></a>';  ?>

																												<?php echo  '<div id="imgmodifier'.$tab4["id"].'"  class="modal fade " role="dialog">
																													<div class="modal-dialog">
																																<div class="modal-content">
																																	<div class="modal-header" style="padding:35px 50px;">
																																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																																		<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'un Produit </b></h4>
																																	</div>
																																	<div class="modal-body" style="padding:40px 50px;">
																											                            <form role="form" class=""   name="formulaire2" method="post" >
																																											<input type="hidden" name="id" value="'.$tab4["id"].'"/>
																																											<input type="hidden" name="tab" value="'.$categorieTypeCateg.'"/>
																																											<div class="form-group">
																																												<label for="reference">CATEGORIE <font color="red">*</font></label>
																																												<input type="text" class="form-control" name="nomCategorie"   value="'.$tab4["nomCategorie"].'" required />
																																											</div>
																																											<div class="form-group">
																																												<label for="reference">CATEGORIE PARENT <font color="red">*</font></label>
																																												<input type="text" class="form-control" name="categorieParent"   value="'.$tab4["categorieParent"].'" required />
																																											</div>
																											                                 '
																																											;	?>
																																									<?php	 echo '<div class="form-group" align="right">
																											                                 <br />
																											                                  <input type="submit" class="boutonbasic"  name="btnModifierCategorie" value="MODIFIER  >>"/>

																											                                 </div>
																											                              </form>
																																	</div>
																																</div>
																															</div>
																													</div>';?>
																													<!--  -->
																													<!-- SUPPRIMER -->
																												<?php echo  '<div id="imgsup'.$tab4["id"].'"  class="modal fade " role="dialog">
																														<div class="modal-dialog">
																																	<div class="modal-content">
																																		<div class="modal-header" style="padding:35px 50px;">
																																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																																			<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression </b></h4>
																																		</div>
																																		<div class="modal-body" style="padding:40px 50px;">
																																										<form role="form" class=""   name="formulaire2" method="post" >
																																												<input type="hidden" name="id" value="'.$tab4["id"].'"/>
																																												<div class="form-group">
																																													<label for="reference">CATEGORIE <font color="red">*</font></label>
																																													<input type="text" class="form-control" name="nomCategorie"   value="'.$tab4["nomCategorie"].'" disabled />
																																												</div>
																																												<div class="form-group">
																																													<label for="reference">CATEGORIE PARENT <font color="red">*</font></label>
																																													<input type="text" class="form-control" name="categorieParent"   value="'.$tab4["categorieParent"].'" disabled />
																																												</div>
																																												';	?>
																																										<?php																									                                echo '<div class="form-group" align="right">
																																												<font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
																																													<input type="submit" class="boutonbasic"  name="btnSupprimerCategorie" value="SUPPRIMER  >>"/>

																																												 </div>
																																											</form>
																																		</div>
																																	</div>
																																</div>
																														</div>';?>
																													<!--  -->

																										</td>
																							</tr>
																					<?php  }
																					?>
																		 </table>
												 <?php } ?>
										</div>
										<div class="tab-pane fade " id="FORME">
				                    <?php
															   $sql15="SELECT * from  `".$formeTypeCategPharmacie."` ";

															 if($res15=mysql_query($sql15)){ ?>
									                   <table id="exemple3" class="display" border="1">
																					     <thead>
																									 <tr>
																												<th>NOM FORME</th>
																												<th>OPERATION</th>
																									</tr>
																							 </thead>
																									<tfoot>
																										<tr>
																											<th>NOM FORME</th>
																											<th>OPERATION</th>
																										</tr>
																									</tfoot>
																						<?php
																						while($tab5=mysql_fetch_array($res15)){
																										echo'<tr><td>'.$tab5["nomForme"].'</td> '; ?>
																										<td>
																													<?php 	echo '<a><img src="images/edit.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgmodifierF'.$tab5["id"].'" /></a>';  ?>
																												<?php 	echo '<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsupF'.$tab5["id"].'" /></a>';  ?>

																												<?php echo  '<div id="imgmodifierF'.$tab5["id"].'"  class="modal fade " role="dialog">
																													<div class="modal-dialog">
																																<div class="modal-content">
																																	<div class="modal-header" style="padding:35px 50px;">
																																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																																		<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'un Produit </b></h4>
																																	</div>
																																	<div class="modal-body" style="padding:40px 50px;">
																											                            <form role="form" class=""   name="formulaire2" method="post" >
																																											<input type="hidden" name="id" value="'.$tab5["id"].'"/>
																																											<input type="hidden" name="tab" value="'.$categorieTypeCateg.'"/>
																																											<div class="form-group">
																																												<label for="reference">CATEGORIE <font color="red">*</font></label>
																																												<input type="text" class="form-control" name="nomCategorie"   value="'.$tab5["nomForme"].'" required />
																																											</div>
																											                                 '
																																											;	?>
																																									<?php	 echo '<div class="form-group" align="right">
																											                                 <br />
																											                                  <input type="submit" class="boutonbasic"  name="btnModifierCategorie" value="MODIFIER  >>"/>

																											                                 </div>
																											                              </form>
																																	</div>
																																</div>
																															</div>
																													</div>';?>
																													<!--  -->
																													<!-- SUPPRIMER -->
																												<?php echo  '<div id="imgsupF'.$tab5["id"].'"  class="modal fade " role="dialog">
																														<div class="modal-dialog">
																																	<div class="modal-content">
																																		<div class="modal-header" style="padding:35px 50px;">
																																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																																			<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression </b></h4>
																																		</div>
																																		<div class="modal-body" style="padding:40px 50px;">
																																										<form role="form" class=""   name="formulaire2" method="post" >
																																												<input type="hidden" name="id" value="'.$tab5["id"].'"/>
																																												<div class="form-group">
																																													<label for="reference">CATEGORIE <font color="red">*</font></label>
																																													<input type="text" class="form-control" name="nomForme"   value="'.$tab5["nomForme"].'" disabled />
																																												</div>
																																												';	?>
																																										<?php																									                                echo '<div class="form-group" align="right">
																																												<font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
																																													<input type="submit" class="boutonbasic"  name="btnSupprimerCategorie" value="SUPPRIMER  >>"/>

																																												 </div>
																																											</form>
																																		</div>
																																	</div>
																																</div>
																														</div>';?>
																													<!--  -->

																										</td>
																							</tr>
																					<?php  }
																					?>
																		 </table>
												 <?php } ?>
										</div>
							  </div>
				</div>
		</div>
	</body>
</html>
