<?php
/*
Résumé :
Commentaire :
Version : 2.1
see also :
Auteur : EL hadji mamadou korka
Date de création : 18-05-2018
Date derniére modification :  19-05-2018
*/
require('entetehtml.php');


if (isset($_POST['supprimer'])) {

      unlink("../uploads/".$_POST['image']);
          $req5 = $bddV->prepare("DELETE FROM `".$nomtableDesignation."` WHERE id=:idV ");
                      $req5->execute(array(
                                        'idV' => $_POST['id']
                                      )) or die(print_r($req5->errorInfo()));
                    $req5->closeCursor();
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
                $uploadPath = "../uploads/";
                $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $uploadImageType = $sourceProperties[2];
                $sourceImageWidth = $sourceProperties[0];
                $sourceImageHeight = $sourceProperties[1];

                $id         =@$_POST["id"];
                switch ($uploadImageType) {
                    case IMAGETYPE_JPEG:
                        $resourceType = imagecreatefromjpeg($fileName);
                        //$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagejpeg($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        imagedestroy($imageLayer);
                        imagedestroy($resourceType);
                        $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV WHERE id=:idV ");
                        $req5->execute(array(
                              'imageV' => $fileNameNew,
                              'idV' => $id ))
                                or die(print_r($req5->errorInfo()));
                        $req5->closeCursor();
                        break;
                    case IMAGETYPE_PNG:
                        $resourceType = imagecreatefrompng($fileName);
                        //$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagepng($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        imagedestroy($imageLayer);
                        imagedestroy($resourceType);
                         $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV WHERE id=:idV ");
                        $req5->execute(array(
                              'imageV' => $fileNameNew,
                              'idV' => $id ))
                        or die(print_r($req5->errorInfo()));
                          $req5->closeCursor();
                        break;
                    default:
                        $imageProcess = 0;
                        break;
                }
            }
          $imageProcess = 0;
      }
      /**************** DECLARATION DES ENTETES *************/
    if(isset($_POST["btnSupImg"])) {
           if(unlink("../uploads/".$_POST['image'])) {
                $id         =@$_POST["id"];
                $fileNameNew='';
                 $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image = :image WHERE id  = :id ");
                              $req5->execute(array(
                              'image' => $fileNameNew,
                              'id ' => $id
                            )) or die(print_r($req5->errorInfo()));
                          $req5->closeCursor();
             }else {

             }
      }
    if(isset($_POST["btnAjAutoProduit"])) {
          $sql3='SELECT * from  `'.$nomtableDesignation.'` where classe =0 order by idDesignation desc';
           if($res3=mysql_query($sql3)){
              while($tab3=mysql_fetch_array($res3)){

                   $req = $bddV->prepare("INSERT INTO
              `".$nomtableDesignation."`(designation, categorie, idBoutique, idDesignation, forme,tableau, prixPublic,
               codeBarreDesignation,codeBarreuniteStock,idFusion)
              VALUES(:designation,:categorie,:idBoutique, :idDesignation, :forme, :tableau, :prixPublic,
               :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              $req->execute(array(
                            'designation' => $tab3['designation'],
                            'categorie' => $tab3['categorie'],
                            'idBoutique' => $_SESSION['idBoutique'],
                            'idDesignation' => $tab3['idDesignation'],
                            'forme' => $tab3['forme'],
                            'tableau' => $tab3['tableau'],
                            'prixPublic' => $tab3['prixPublic'],
                            'codeBarreDesignation' => $tab3['codeBarreDesignation'],
                            'codeBarreuniteStock' => $tab3['codeBarreuniteStock'],
                            'idFusion' => $tab3['idFusion']
              ))  or die(print_r($req->errorInfo()));
              $req->closeCursor();
                }
            }
     }
    require('entetehtml.php');
    echo'
       <body >';
       require('header.php');




    echo'<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
    <i class="glyphicon glyphicon-plus"></i>Ajouter un Stock</button></center> ';

    echo'<div class="modal fade bd-example-modal-xl" tabindex="-1" id="AjoutStockModal" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">';
    echo'<div class="modal-dialog modal-lg">';
    echo'<div class="modal-content">';
    echo'<div class="modal-header" style="padding:35px 50px;">';
    echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
    echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Produit </h4>";
    echo'</div>';
    echo'<div class="modal-body" >
                    <div class="row" align="center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=#Activer >
                                              Ajouter Produit automatique
                        </button>
                            <div class="modal fade" id="Activer" tabindex="-1" role="dialog"       aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                            </div>
                                            <div class="modal-body">
                                              <form name="formulaireVersement" method="post" >
                                                <div class="form-group">
                                                   <h2>Voulez vous vraiment Ajouter tous les produits dans votre vitrine</h2>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" name="btnAjAutoProduit" class="btn btn-primary">Ajouter tous</button>
                                                </div>
                                              </form>
                                            </div>
                                    </div>
                            </div>
                    </div>
                </div>
              ';

      $sql3='SELECT * from  `'.$nomtableDesignation.'` where classe =0 order by idDesignation desc';
          if($res3=mysql_query($sql3)){
              echo'<form name="formulaireInitialStock" method="post" action="gestionStock.php"><table id="exemple2" lass="table table-bordered table-responsive display"  border="1"><thead><tr>
                  <th>Reference</th>
		          <th>Categorie</th>
		          <th>Forme</th>
		          <th>Tableau</th>
		          <th>Prix Public</th>
                  <th>OPERATIONS</th>
                </tr></thead>
                <tfoot><tr>
                  <th>Reference</th>
		          <th>Categorie</th>
		          <th>Forme</th>
		          <th>Tableau</th>
		          <th>Prix Public</th>
                  <th>OPERATIONS</th>
                </tr></tfoot>';
               $j=0;
               while($tab3=mysql_fetch_array($res3)){
                    $j=$j+1;
                    echo'<tr><form class="form" ><td>'.$tab3["designation"].'</td>
                          <td>'.$tab3["categorie"].'</td>
                          <td>'.$tab3["forme"].'</td>
                          <td>'.$tab3["tableau"].'</td>
                          <td>'.$tab3["prixPublic"].'</td>
                          ';
                              echo'
                              <td>

                              <input type="hidden" name="designation-'.$tab3['idDesignation'].'" id="designation-'.$tab3['idDesignation'].'" value="'.$tab3["designation"].'"/>
                              <input type="hidden" name="categorie-'.$tab3['idDesignation'].'" id="categorie-'.$tab3['idDesignation'].'" value="'.$tab3['categorie'].'"/>
                              <input type="hidden" name="forme-'.$tab3['idDesignation'].'" id="forme-'.$tab3['idDesignation'].'" value="'.$tab3["forme"].'"/>
                              <input type="hidden" name="tableau-'.$tab3['idDesignation'].'" id="tableau-'.$tab3['idDesignation'].'" value="'.$tab3["tableau"].'" />
                              <input type="hidden" name="prixPublic-'.$tab3['idDesignation'].'" id="prixPublic-'.$tab3['idDesignation'].'" value="'.$tab3["prixPublic"].'" />
                                <input type="hidden" name="codeBarreDesignation-'.$tab3['idDesignation'].'" id="codeBarreDesignation-'.$tab3['idDesignation'].'" value="'.$tab3["codeBarreDesignation"].'" />
                              <input type="hidden" name="codeBarreuniteStock-'.$tab3['idDesignation'].'" id="codeBarreuniteStock-'.$tab3['idDesignation'].'" value="'.$tab3["codeBarreuniteStock"].'" />
                              <input type="hidden" name="idFusion-'.$tab3['idDesignation'].'" id="idFusion-'.$tab3['idDesignation'].'" value="'.$tab3["idFusion"].'" />
                               <button type="button" onclick="ajt_vitrinePH('.$tab3["idDesignation"].')" id="btn_ajtVitrine-'.$tab3['idDesignation'].'"
                                  class="btn btn-success "><i class="glyphicon glyphicon-plus">
                                  </i>AJOUTER
                                </button>
                              </td>
                      </form></tr>';
            }
            echo '</table><br/><center>';
            echo '</center>
                </form><br />';
              }
      echo '</div></div></div></div></div>';

    echo'<div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
                       $req2 = $bddV->prepare("SELECT * from `".$nomtableDesignation."` ");
                       $req2->execute() or die(print_r($req2->errorInfo()));
                       if ($req2) {
                            echo'<table id="exemple" class="display tabVitrine" border="1">
                                <thead><tr>
                                  <th>ORDRE</th>
                                    <th>Reference</th>
            							          <th>Categorie</th>
            							          <th>Forme</th>
            							          <th>Tableau</th>
            							          <th>Prix Public</th>
                                    <th>OPERATIONS</th>
                                    </tr>
                                  </thead>
                                   <tfoot><tr>
                                     <th>ORDRE</th>
                                      <th>Reference</th>
              							          <th>Categorie</th>
              							          <th>Forme</th>
              							          <th>Tableau</th>
              							          <th>Prix Public</th>
                                      <th>OPERATIONS</th>
                                    </tr>
                                  </tfoot>';
                           $i=0;
                          while($tab3=$req2->fetch()){
                              $i=$i+1;
                              if ($i==1)  {
                                echo '
                                  <tr style="color:blue;"><td>'.$i.'</td>
                                  <td>'.$tab3["designation"].'</td>
                                   <td>'.$tab3["categorie"].'</td>
      		                          <td>'.$tab3["forme"].'</td>
      		                          <td>'.$tab3["tableau"].'</td>
      		                          <td>'.$tab3["prixPublic"].'</td>
                                  <td><a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab3["id"].'" /></a>
                                      <div id="imgsup'.$tab3["id"].'" class="modal fade" role="dialog">
                                          <div class="modal-dialog">
                                             <div class="modal-content">
                                               <div class="modal-header" style="padding:35px 50px;">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"> </span> <b>Confirmation Suppression d\'un Produit</b></h4>

                                              </div>
                                              <div class="modal-body" style="padding:40px 50px;">


                                                              <form role="form" class="" id="form" name="formulaire2" method="post" action="vitrine.php">

                                                                  <div class="form-group">
                                                                    <label for="reference">REFERENCE </label>
                                                                    <input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["designation"].'" disabled=""/>
                                                                  </div>

                                                                  <div class="form-group">
                                                                    <label for="categorie"> CATEGORIE </label>
                                                                    <input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["categorie"].'" disabled=""/>

                                                                  </div>
                                                                  <div class="form-group">
                                                                    <label for="uniteStock"> Forme </label>
                                                                      <input type="text" class="form-control" name="forme" id="forme" value="'.$tab3["forme"].'" disabled=""/>
                                                                  </div>

                                                                  <div class="form-group" >
                                                                    <label for="prix">Tableau </label>
                                                                    <input type="number" class="form-control" disabled="" id="tableau" name="tableau" value="'.$tab3["tableau"].'" />
                                                                  </div>

                                                                  <div class="form-group" >
                                                                    <label for="prixuniteStock">Prix Public</label>
                                                                    <input type="number" class="form-control" disabled="" id="prixPublic" name="prixPublic" value="'.$tab3["prixPublic"].'" />
                                                                  </div>';

                                                                  echo'<div class="form-group" align="right">
                                                                      <font color="red"><b>Voulez-vous supprimer ce Produit ? </b></font><br /><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                                                        <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                        <input type="hidden" name="image" value="'.$tab3["image"].'"/>
                                                                      <input type="hidden" name="supprimer" value="1" /></div>

                                                                </form>

                                              </div>
                                             </div>
                                          </div>
                                      </div>'; ?>
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
                                                                              <img src="../uploads/'.$tab3["image"].'" />
                                                                              <form   method="post" enctype="multipart/form-data">
                                                                                  <input type="hidden" name="id" value="'.$tab3["id"].'"/>
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

                                      ?> <?php echo '
                                  </td> </form>
                                  </tr>';
                              } else {
                                 echo '<tr><form class="form" >
                                  <tr><td>'.$i.'</td>
                                  <td>'.$tab3["designation"].'</td>
                                  <td>'.$tab3["categorie"].'</td>
                                  <td>'.$tab3["forme"].'</td>
                                  <td>'.$tab3["tableau"].'</td>
                                  <td>'.$tab3["prixPublic"].'</td>
                                  <td><a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab3["id"].'" /></a>
                                      <div id="imgsup'.$tab3["id"].'" class="modal fade" role="dialog">
                                          <div class="modal-dialog">
                                             <div class="modal-content">
                                               <div class="modal-header" style="padding:35px 50px;">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"> </span> <b>Confirmation Suppression d\'un Produit</b></h4>

                                              </div>
                                              <div class="modal-body" style="padding:40px 50px;">


                                                              <form role="form" class="" id="form" name="formulaire2" method="post" action="vitrine.php">

                                                                  <div class="form-group">
                                                                    <label for="reference">REFERENCE </label>
                                                                    <input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["designation"].'" disabled=""/>
                                                                  </div>

                                                                  <div class="form-group">
                                                                    <label for="categorie"> CATEGORIE </label>
                                                                    <input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["categorie"].'" disabled=""/>

                                                                  </div>
                                                                  <div class="form-group">
                                                                    <label for="uniteStock"> UNITE STOCK </label>
                                                                      <input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["uniteStock"].'" disabled=""/>
                                                                  </div>

                                                                  <div class="form-group" >
                                                                    <label for="prix">PRIX UNITAIRE </label>
                                                                    <input type="number" class="form-control" disabled="" id="prix" name="prix" value="'.$tab3["prix"].'" />
                                                                  </div>

                                                                  <div class="form-group" >
                                                                    <label for="prixuniteStock">PRIX UNITE STOCK</label>
                                                                    <input type="number" class="form-control" disabled="" id="prixuniteStock" name="prixuniteStock" value="'.$tab3["prixuniteStock"].'" />
                                                                  </div>';

                                                                  echo'<div class="form-group" align="right">
                                                                      <font color="red"><b>Voulez-vous supprimer ce Produit ? </b></font><br /><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                                                        <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                        <input type="hidden" name="image" value="'.$tab3["image"].'"/>
                                                                      <input type="hidden" name="supprimer" value="1" /></div>

                                                                </form>

                                              </div>
                                             </div>
                                          </div>
                                      </div> '; ?>
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
                                                                              <img src="../uploads/'.$tab3["image"].'" />
                                                                              <form   method="post" enctype="multipart/form-data">
                                                                                  <input type="hidden" name="id" value="'.$tab3["id"].'"/>
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

                                      ?> <?php echo '
                                  </td> </form>
                                  </tr>';

                              }


                        }
                       echo ' </table>';
                      }
                echo'</div>

            </div>
          </div>';

    echo'</body></html>';

        /* Debut PopUp d'Alerte sur l'ensemble de la Page **/
        if(isset($msg_info)) {
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
                                <h4 class="modal-title">Alerte</h4>
                            </div>
                            <div class="modal-body">
                                <p>'.$msg_info.'</p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                    </div>
                </div>';

        }
        /** Fin PopUp d'Alerte sur l'ensemble de la Page **/
?>