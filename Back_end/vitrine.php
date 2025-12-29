<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur:EL hadji mamadou korka
Date de modification:20-03-2020
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

/**********************/
if(isset($_POST["btnUploadImgBaniere"])) {

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
							$uploadPath = "../uploads/baniere/";

							$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
							$uploadImageType = $sourceProperties[2];
							$sourceImageWidth = $sourceProperties[0];
							$sourceImageHeight = $sourceProperties[1];

							$id         =@$_POST["id"];
							$j         =@$_POST["j"];
							$imageForm         =@$_POST["image"];
							$baniereX="baniere".$j;

                            $localPath = "../uploads/baniere/";
                            $remotePath = "public_html/uploads/baniere/";
							//echo "string".$baniereX;
							switch ($uploadImageType) {
									case IMAGETYPE_JPEG:
											$resourceType = imagecreatefromjpeg($fileName);
											imagejpeg($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);
											$fileNameNew=$resizeFileName.'.'. $fileExt;
											$req5 = $bddV->prepare("UPDATE `boutique-yaatout` SET `".$baniereX."`=:ban WHERE id=:idB ");
											$req5->execute(array(
														'ban' => $fileNameNew,
														'idB' => $id ))
															or die(print_r($req5->errorInfo()));
											$req5->closeCursor();


                                          /*****************************  SEND FROM SERVER TO SERVER *****************************/

                                            if ((!$cnx_ftp) || (!$cnx_ftp_auth))
                                            {
                                              echo "";
                                            }
                                            else
                                            {
//                                                ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
                                                 echo " ";
                                                if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $localPath.$fileNameNew, FTP_BINARY)) {

                                                    if($_POST["image"]!=''){
                                                        unlink($localPath.$_POST['image']);
                                                        ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
                                                    //echo "".$remotePath.$_POST['image'];
                                                    }
                                                } else{
                                                    //var_dump($remotePath.$fileNameNew);
                                                    //var_dump($localPath.$fileNameNew);
                                                     echo "echec J";
                                                }
                                                 ftp_quit($cnx_ftp);
                                            }
                                            /*****************************  SEND FROM SERVER TO SERVER *****************************/
											break;
									case IMAGETYPE_PNG:
											$resourceType = imagecreatefrompng($fileName);
											//$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
											//imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
											imagepng($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);
											$fileNameNew=$resizeFileName.'.'. $fileExt;
											$req5 = $bddV->prepare("UPDATE `boutique-yaatout` SET `".$baniereX."`=:ban WHERE id=:idB ");
											$req5->execute(array(
														'ban' => $fileNameNew,
														'idB' => $id ))
															or die(print_r($req5->errorInfo()));
											$req5->closeCursor();
                                             /*****************************  SEND FROM SERVER TO SERVER *****************************/

                                            if ((!$cnx_ftp) || (!$cnx_ftp_auth))
                                            {
                                              echo "";
                                            }
                                            else
                                            {
//                                                ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
                                                 echo " ";
                                                if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $localPath.$fileNameNew, FTP_BINARY)) {
                                                    if($_POST["image"]!=''){
                                                        unlink($localPath.$_POST['image']);
                                                        ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
                                                        //echo "".$remotePath.$_POST['image'];
                                                    }
                                                } else{
                                                    //var_dump($remotePath.$fileNameNew);
                                                    //var_dump($localPath.$fileNameNew);
                                                     echo "echec p";
                                                }
                                                 ftp_quit($cnx_ftp);
                                            }
                                            /*****************************  SEND FROM SERVER TO SERVER *****************************/
											break;
									default:
											$imageProcess = 0;
											break;
							}
					}
				$imageProcess = 0;
		}
if(isset($_POST["btnSupImgBaniere"])) {
			$j         =@$_POST["j"];
			$id         =@$_POST["id"];
    $localPath = "../uploads/baniere/";
    $remotePath = "public_html/uploads/baniere/";

			$baniereX="baniere".$j;
				 if(unlink($localPath.$_POST['image'])) {
                     ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;

					 $fileNameNew='';
					 $req5 = $bddV->prepare("UPDATE `boutique-yaatout` SET `".$baniereX."`=:ban WHERE id=:idB ");
					 $req5->execute(array(
								 'ban' => $fileNameNew,
								 'idB' => $id ))
									 or die(print_r($req5->errorInfo()));
					 $req5->closeCursor();


							// $fileNameNew='';
							//  $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image = :image WHERE id  = :id ");
							//               $req5->execute(array(
							//               'image' => $fileNameNew,
							//               'id ' => $id
							//             )) or die(print_r($req5->errorInfo()));
							//           $req5->closeCursor();
					 }else {
                        echo '';
					 }
		}
if (isset($_POST['btnActiverVitrine'])) {
	$idBoutique=$_POST['idboutique'];
	$nomBoutique=$_POST['nomBoutique'];
	$type=$_POST['type'];
	$categorie=$_POST['categorie'];
	$adresse=$_POST['adresse'];
	$activer=1;
	$vitrine=1;

   // var_dump($_POST['tab']);

	$sql3="UPDATE `aaa-boutique` set  vitrine='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

    $req2= $bddV->prepare("INSERT INTO
                        boutique(idBoutique,nomBoutique,type,categorie,adresse,vitrine)
                        VALUES (:idBoutique, :nomBoutique, :type,:categorie,:adresse,:vitrine)
                        ") ;
    $req2->execute(array(
                         'idBoutique' => $idBoutique,
                         'nomBoutique' => $nomBoutique,
                         'type' => $type,
                         'categorie' => $categorie,
                         'adresse' => $adresse,
                         'vitrine' => $vitrine
                    ));
    $req2->closeCursor();

     $req5 = $bddV->prepare('UPDATE boutique SET vitrine = :vitrine WHERE idBoutique = :idBoutique');
     $req5->execute(array(
                          'vitrine' => $vitrine,
                          'idBoutique' => $idBoutique
                          )) or die(print_r($req5->errorInfo()));
     $req5->closeCursor();

    $nomtableDesignation=$nomBoutique."-designation";

    try {
           $req1 =$bddV->exec("CREATE TABLE IF NOT EXISTS   `".$nomtableDesignation."` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                          `idDesignation` int(11) NOT NULL,
                          `idBoutique` int(11) NOT NULL,
                          `designation` varchar(50) NOT NULL,
                          `designationJcaisse` varchar(50) NOT NULL,
                          `description` varchar(100) DEFAULT NULL,
                          `categorie` varchar(50) NOT NULL,
                          `uniteStock` varchar(50) NOT NULL,
                          `nbreArticleUniteStock` double NOT NULL,
                          `prix` double NOT NULL,
                          `seuil` int(11) NOT NULL,
                          `prixuniteStock` double NOT NULL,
                          `codeBarreDesignation` varchar(50) NOT NULL,
                          `codeBarreuniteStock` varchar(50) NOT NULL,
                          `classe` int(11) NOT NULL,
                          `idFusion` int(11) DEFAULT NULL,
                          `image` varchar(30) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM   DEFAULT CHARSET=utf8");



        }
        catch(PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }


}
elseif (isset($_POST['btnDesactiverVitrine'])) {
	$idBoutique=$_POST['idboutique'];

	$nomBoutique=$_POST['nomBoutique'];
    $nomtableDesignation=$nomBoutique."-designation";
	$activer=0;
	$vitrine=0;

	$sql3="UPDATE `aaa-boutique` set  vitrine='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());

    $req5 = $bddV->prepare('UPDATE boutique SET vitrine = :vitrine WHERE idBoutique = :idBoutique');
                                      $req5->execute(array(
                                      'vitrine' => $vitrine,
                                      'idBoutique' => $idBoutique
                                    )) or die(print_r($req5->errorInfo()));
                                      $req5->closeCursor();
}
/**************** DECLARATION DES ENTETES *************/
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
    <ul class="nav nav-tabs">
         <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
         <li class=""><a data-toggle="tab" href="#BANIERE">IMAGE BANIERE</a></li>
    </ul>
     <div class="tab-content">
         <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
             <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Nom Boutique</th>
                            <th>Adresse</th>
                            <th>Date de création</th>
                            <th>Type & Catégorie</th>
                            <th>Vitrine</th>
                            <th>Activer/Désactiver</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nom Boutique</th>
                            <th>Adresse</th>
                            <th>Date de création</th>
                            <th>Type & Catégorie</th>
                            <th>Vitrine</th>
                            <th>Activer/Désactiver</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php
                        if($_SESSION['profil']=="SuperAdmin"){

                            $sql2="SELECT * FROM `aaa-boutique`";
                            $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                            while ($boutique = mysql_fetch_array($res2)) {

                                $sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin'";
                                $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                                while ($acces = mysql_fetch_array($res3)) {
                                    $sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur']." LIMIT 1";
                                    $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                                    //int i=1;
                                    while ($utilisateur = mysql_fetch_array($res4)){
                                        //if($utilisateur['back']==1)
                                ?>
                                <tr>
                                    <td> <?php echo  $boutique["labelB"]; ?>  </td>
                                    <td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
                                    <td> <?php echo  $boutique["datecreation"]; ?>  </td>
                                    <td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
                                    <td>
                                    <?php if ($boutique["vitrine"]==0) { ?>
                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverVitrine".$boutique['idBoutique'] ; ?> >
                                                    Activer</button>

                                                    <?php
                                                } else { ?>
                                                    <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverVitrine".$boutique['idBoutique'] ; ?> >
                                                    Desactiver</button>
                                                <?php }
                                                 ?>
                                        <!----------------------------------------------------------->
                                            <div class="modal fade" <?php echo  "id=ActiverVitrine".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form name="formulaireVersement" method="post" action="vitrine.php">
                                                                          <div class="form-group">
                                                                             <h2>Voulez vous vraiment activer cette Vitrine</h2>
                                                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                                                             <?php echo '<input type="hidden" name="nomBoutique"  value="'. htmlspecialchars($boutique['nomBoutique']).'"  />' ; ?>
                                                                            <?php echo  ' <input type="hidden" name="adresse" value="'. htmlspecialchars($boutique['adresseBoutique']).'" > ' ; ?>
                                                                             <input type="hidden" name="type" <?php echo  "value=". htmlspecialchars($boutique['type'])."" ; ?> >
                                                                             <input type="hidden" name="categorie" <?php echo  "value=". htmlspecialchars($boutique['categorie'])."" ; ?> >
                                                                          </div>
                                                                          <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" name="btnActiverVitrine" class="btn btn-primary">Activer</button>
                                                                           </div>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                            <div class="modal fade" <?php echo  "id=DesactiverVitrine".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form name="formulaireVersement" method="post" action="vitrine.php">
                                                                          <div class="form-group">
                                                                             <h2>Voulez vous vraiment desactiver cette Vitrine</h2>
                                                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                                                              <input type="hidden" name="nomBoutique" <?php echo  "value=".htmlspecialchars($boutique['nomBoutique'])."" ; ?> >
                                                                             <input type="hidden" name="adresse" <?php echo  "value=". htmlspecialchars($boutique['adresseBoutique'])."" ; ?> >
                                                                             <input type="hidden" name="type" <?php echo  "value=". htmlspecialchars($boutique['type'])."" ; ?> >
                                                                             <input type="hidden" name="categorie" <?php echo  "value=". htmlspecialchars($boutique['categorie'])."" ; ?> >
                                                                          </div>
                                                                          <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" name="btnDesactiverVitrine" class="btn btn-primary">Desactiver</button>
                                                                           </div>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                        <!----------------------------------------------------------->
                                    </td>
                                     <td>
																			   <?php if ($boutique["vitrine"]==1) { ?>
																					 <a href="vitrineDet.php">
		                                         <form   method="post" action="vitrineDet.php">
																								<input type="hidden" name="idB" value="<?php echo $boutique['idBoutique']; ?>"/>
																								<div class="form-group" >
																								    <input type="submit" class="boutonbasic"  name="vitrineDet" value="Detail >>"/>
																								</div>
																		        </form>
		                                       </a>
																		 		 <?php } ?>
																		</td>
                                    <?php 	}
                                    }
                                }


                        }
                        ?>

                    </tbody>
                </table>
         </div>
        <div class="tab-pane fade " id="BANIERE">
            <?php
		          $req2 = $bddV->prepare("SELECT id,baniere1,baniere2,baniere3 from `boutique-yaatout` where nomBoutique = :nomB ");
									 //var_dump($req2);
				  $req2->execute(array('nomB' =>  'yaatout')) or die(print_r($req2->errorInfo()));
                  $tab3=$req2->fetch(); ?>
		      <div class="row mt-10">
            <!-- BANIER 1 -->
			     <?php if ($tab3["baniere1"] ): ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <?php echo '<img width="628" height="472" src="../uploads/baniere/'.$tab3["baniere1"].'" />'; ?>
                            <div class="caption">
                                <h3>BANIERE 1</h3>
                                <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#up1">Modifier</a>
                                    <div id="up1"  class="modal fade " role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                </div>
                                                <div class="modal-body" style="">
                                                    <form   method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="image" value="<?php echo $tab3["baniere1"]; ?>"/>
                                                        <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                        <input type="hidden" name="j" value="1"/>
                                                        <div class="form-group" ><br />
                                                            <input type="file" name="file" />
                                                        </div>
                                                        <div class="form-group" align="right">
                                                            <input type="submit" class="boutonbasic"  name="btnSupImgBaniere" value="Suprimer >>"/>
                                                            <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Modifier >>"/>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
			     <?php else : ?>
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
                            <div class="caption">
								<h3>BANIERE 1 VIDE</h3>
									<a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#ap1">Ajouter</a>
									<div id="ap1"  class="modal fade " role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                </div>
                                                <div class="modal-body" style="">
                                                    <form   method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                        <input type="hidden" name="j" value="1"/>
                                                        <div class="form-group" >
                                                            <br />
                                                            <input type="file" name="file" />
                                                        </div>
                                                            <div class="form-group" align="right">
                                                                <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                            </div>
                                                    </form>
                                                </div>
                                              </div>
                                          </div>
										</div>
								</div>
							</div>
                    </div>
            <?php endif; ?>
            <!-- FIN BANIER 1 -->
			<!-- BANIER 2 -->
				<?php if ($tab3["baniere2"] ): ?>
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<?php echo '<img width="628" height="472" src="../uploads/baniere/'.$tab3["baniere2"].'" />'; ?>
								<div class="caption">
									<h3>BANIERE 2</h3>
										<a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#up2">Modifier</a>
										<div id="up2"  class="modal fade " role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header" style="">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
												    </div>
													<div class="modal-body" style="">
                                                        <form   method="post" enctype="multipart/form-data">
															<input type="hidden" name="image" value="<?php echo $tab3["baniere2"]; ?>"/>
															<input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
															<input type="hidden" name="j" value="2"/>
															<div class="form-group" >
																<br />
																<input type="file" name="file" />
															</div>
															<div class="form-group" align="right">
																<input type="submit" class="boutonbasic"  name="btnSupImgBaniere" value="Suprimer >>"/>
																<input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Modifier >>"/>
															</div>
												          </form>
												      </div>
												   </div>
												</div>
											</div>
								    </div>
								</div>
							</div>
				<?php else : ?>
						<div class="col-sm-6 col-md-4">
							<div class="thumbnail">
                                <div class="caption">
									<h3>BANIERE 2 VIDE</h3>
										<a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#ap2">Ajouter</a>
										<div id="ap2"  class="modal fade " role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header" style="">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
													</div>
													<div class="modal-body" style="">
														<form   method="post" enctype="multipart/form-data">
															<input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
															<input type="hidden" name="j" value="2"/>
															<div class="form-group" >
                                                                <br />
                                                                <input type="file" name="file" />
															</div>
															<div class="form-group" align="right">
																<input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
															</div>
														  </form>
													</div>
												</div>
								            </div>
								        </div>
								</div>
				            </div>
                        </div>
				<?php endif; ?>
			<!-- FIN BANIER 2 -->
			<!-- BANIER 3 -->
				<?php if ($tab3["baniere3"] ): ?>
            <div class="col-sm-6 col-md-4">
				<div class="thumbnail">
					<?php echo '<img width="628" height="472" src="../uploads/baniere/'.$tab3["baniere3"].'" />'; ?>
						<div class="caption">
                            <h3>BANIERE 3</h3>
                            <a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#up3">Modifier</a>
                            <div id="up3"  class="modal fade " role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                        </div>
                                        <div class="modal-body" style="">
                                            <form   method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="image" value="<?php echo $tab3["baniere3"]; ?>"/>
                                                    <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                    <input type="hidden" name="j" value="3"/>
                                                    <div class="form-group" >
                                                        <br />
                                                        <input type="file" name="file" />
                                                    </div>
                                                    <div class="form-group" align="right">
                                                        <input type="submit" class="boutonbasic"  name="btnSupImgBaniere" value="Suprimer >>"/>
                                                        <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Modifier >>"/>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
				        </div>
				    </div>
				</div>
				<?php else : ?>
				    <div class="col-sm-6 col-md-4">
								<div class="thumbnail">
                                    <div class="caption">
										<h3>BANIERE 3 VIDE</h3>
										<a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#ap3">Ajouter</a>
										<div id="ap3"  class="modal fade " role="dialog">
											<div class="modal-dialog">
											     <div class="modal-content">
												    <div class="modal-header" style="">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
													</div>
													<div class="modal-body" style="">
														<form   method="post" enctype="multipart/form-data">
															<input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
															<input type="hidden" name="j" value="3"/>
															<div class="form-group" >
																<br />
																<input type="file" name="file" />
															</div>
															<div class="form-group" align="right">
																<input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
															</div>
														</form>
													</div>
												  </div>
								            </div>
								        </div>
								      </div>
								    </div>
                            </div>
				<?php endif; ?>
			<!-- FIN BANIER 3 -->
        </div>
        </div>



      </div>
</div>


</body>
</html>
