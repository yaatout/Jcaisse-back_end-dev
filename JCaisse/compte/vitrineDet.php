<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/
session_start();

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

require('connection.php');
require('connectionVitrine.php');




$idBoutique=0;

if (isset($_POST['idBoutique'])) {
	$idBoutique=$_POST['idBoutique'];
    
    $req1 = $bddV->prepare("SELECT * FROM boutique  WHERE idBoutique = :idBoutique ");
    $req1->execute(array('idBoutique' =>$idBoutique))  or die(print_r($req1->errorInfo()));
    $boutique=$req1->fetch();

}

if(isset($_SESSION['boutV']))
    $nomtableDesignation=$_SESSION['boutV']."-designation";


/**********************/
  if (isset($_POST['suppression'])) {
        if($_POST["image"]!=''){
             $localPath = "../uploads/";
             $remotePath = "public_html/uploads/";

            unlink($localPath.$_POST['image']);
            ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
        }
         
        $req5 = $bddV->prepare("DELETE FROM `".$nomtableDesignation."` WHERE id=:idV ");
        $req5->execute(array('idV' => $_POST['id'] )) or die(print_r($req5->errorInfo()));
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
                $localPath = "../uploads/";
                $remotePath = "public_html/uploads/";

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
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                         if ((!$cnx_ftp) || (!$cnx_ftp_auth))
                            {
                                 echo "";
                            }
                          else
                            {
                              // ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
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
                        imagedestroy($imageLayer);
                        imagedestroy($resourceType);
                         $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV WHERE id=:idV ");
                        $req5->execute(array(
                              'imageV' => $fileNameNew,
                              'idV' => $id ))
                        or die(print_r($req5->errorInfo()));
                          $req5->closeCursor();
                          /*****************************  SEND FROM SERVER TO SERVER *****************************/
                             if ((!$cnx_ftp) || (!$cnx_ftp_auth))  {
                                     echo "";
                              }
                              else{
                                  //                                  ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
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
                    default:
                        $imageProcess = 0;
                        break;
                }
            }
          $imageProcess = 0;
      }
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
                $baniereX="baniere".$j;

                $localPath = "../uploads/baniere/";
                $remotePath = "public_html/uploads/baniere/";
                //echo "string".$baniereX;
                switch ($uploadImageType) {
                    case IMAGETYPE_JPEG:
                        $resourceType = imagecreatefromjpeg($fileName);
                        //$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagejpeg($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        //imagedestroy($imageLayer);
                        //imagedestroy($resourceType);
                        $req5 = $bddV->prepare("UPDATE boutique SET `".$baniereX."`=:ban WHERE id=:idB ");
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
                          else{
                                //  ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
                                //echo "YES ";
                                if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $localPath.$fileNameNew, FTP_BINARY)) { 
                                             //echo "YES put";        
                                    if($_POST["image"]!=''){ 
                                        unlink($localPath.$_POST['image']);
                                        ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
                                        //echo "".$remotePath.$_POST['image']; 
                                         //echo "ftp delete ";
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
                        //imagedestroy($imageLayer);
                        //imagedestroy($resourceType);
                        $req5 = $bddV->prepare("UPDATE boutique SET `".$baniereX."`=:ban WHERE id=:idB ");
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
                              //  ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
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
                    default:
                        $imageProcess = 0;
                        break;
                }
            }
          $imageProcess = 0;
      }
      /**************** DECLARATION DES ENTETES *************/
    if(isset($_POST["btnSupImg"])) {
           $localPath = "../uploads/";
           $remotePath = "public_html/uploads/";
           if($_POST['image'] != '') {
                if (unlink($localPath.$_POST['image'])) {
                  ftp_delete ($cnx_ftp,$remotePath.$_POST['image']);
                }

                $id         =@$_POST["id"];
                $fileNameNew='';
                 
                $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV WHERE id=:idV ");
                $req5->execute(array(
                              'imageV' => $fileNameNew,
                              'idV' => $id ))
                                or die(print_r($req5->errorInfo()));

                
                $req5->closeCursor();
            }else {
                  echo " ";
            }
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
             $req5 = $bddV->prepare("UPDATE boutique SET `".$baniereX."`=:ban WHERE id=:idB ");
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

             }
      }
      if(isset($_POST["btnAjAutoProduit"])) {
          $sql3='SELECT * from  `'.$nomtableDesignation.'` where classe =0 order by idDesignation desc';
           if($res3=mysql_query($sql3)){
              while($tab3=mysql_fetch_array($res3)){

                  $req = $bddV->prepare("INSERT INTO
              `".$nomtableDesignation."`(designation, categorie, idBoutique, idDesignation, uniteStock,prix, prixuniteStock, codeBarreDesignation, codeBarreuniteStock, idFusion)
              VALUES(:designation,:categorie,:idBoutique, :idDesignation, :uniteStock, :prix, :prixuniteStock, :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              //var_dump($req);
              $req->execute(array(
                            'designation' => $tab3['designation'],
                            'categorie' => $tab3['categorie'],
                            'idBoutique' => $_SESSION['idBoutique'],
                            'idDesignation' => $tab3['idDesignation'],
                            'uniteStock' => $tab3['uniteStock'],
                            'prix' => $tab3['prix'],
                            'prixuniteStock' => $tab3['prixuniteStock'],
                            'codeBarreDesignation' => $tab3['codeBarreDesignation'],
                            'codeBarreuniteStock' => $tab3['codeBarreuniteStock'],
                            'idFusion' => $tab3['idFusion']
              ))  or die(print_r($req->errorInfo()));
              $req->closeCursor();
                }
            }
      }

if (isset($_POST['vitrineDet'])) {
    
	$idBoutique=$_POST['idB'];
    $req1 = $bddV->prepare("SELECT * FROM boutique  WHERE idBoutique = :idBoutique ");
    $req1->execute(array('idBoutique' =>$idBoutique))  or die(print_r($req1->errorInfo()));
    $boutique=$req1->fetch();
    
    $_SESSION['boutV']=$boutique['nomBoutique'];
    $_SESSION['idBoutV']=$idBoutique;
}?>
<script>
 var idBoutique = <?php echo json_encode($idBoutique); ?>;
</script>
 <?php 
require('entetehtml.php');?>
    
    <body>
      <?php require('header.php'); ?>

        <div class="container">
            <center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
            <i class="glyphicon glyphicon-plus"></i>Ajouter un Produit</button></center> 

            <div class="modal fade" id="AjoutStockModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" >
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                            <table id="tableStock0" class="display" width="100%" border="1">
                              <thead>
                                  <tr>
                                    <th>ORDRE</th>
                                    <th>REFERENCE</th>
                                    <th>CATEGORIE</th>
                                    <th>UNITE STOCK</th>
                                    <th>PRIX UNITE STOCK</th>
                                    <th>PRIX </th>
                                    <th>OPERATIONS</th>
                                  </tr>
                              </thead>
                            </table>

                            <script type="text/javascript">
                              $(document).ready(function() {
                                $("#tableStock0").dataTable({
                                "bProcessing": true,
                                "sAjaxSource": "ajax/listeProduit-VitrineAjax.php",
                                "aoColumns": [
                                    { mData: "0" } ,
                                    { mData: "1" },
                                    { mData: "2" },
                                    { mData: "3" },
                                    { mData: "4" },
                                    { mData: "5" },
                                    { mData: "6" }
                                  ],

                                });  
                              });
                            </script>
                            </div>
                        </div>
                    </div>
                 </div>
              </div>
         <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES PRODUITS</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
                  <div class="table-responsive">
                      <table id="tableStock" class="display tabStock" class="tableau3" align="left" border="1">
                      <thead>
                        <tr id="thStock">
                          <th>ORDRE</th>
                          <th>REFERENCE</th>
                          <th>CATEGORIE</th>
                          <th>UNITE STOCK</th>
                          <th>PRIX </th>
                          <th>PRIX UNITE STOCK</th>
                          <th>OPERATIONS</th>
                        </tr>
                      </thead>
                      </table>
                      <script type="text/javascript">
                           //alert(idBoutique);
                        $(document).ready(function() {
                            $("#tableStock").dataTable({
                              "bProcessing": true,
                              "sAjaxSource": "ajax/listerProduit-VitrineAjax.php",
                              "aoColumns": [
                                    { mData: "0" } ,
                                    { mData: "1" },
                                    { mData: "2" },
                                    { mData: "3" },
                                    { mData: "4" },
                                    { mData: "5" },
                                    { mData: "6" },
                                  ],
                            });  
                        });
                      </script>

                      <div id="supprimerDesignation" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="padding:35px 50px;">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title"><span class="glyphicon glyphicon-lock"> </span> <b>Confirmation Suppression d\'un Produit</b></h4>
                            </div>
                            <div class="modal-body" style="padding:40px 50px;">
                                <form role="form" class="" id="form" name="formulaire2" method="post" action="vitrine.php">
                                  <input style="display:none" type="text" name="id" id="id_Spm" />
                                  <input type="hidden" name="image" id="img_Spm" />
                                  <div class="form-group">
                                    <label for="reference">REFERENCE </label>
                                    <input type="text" class="form-control" name="designation" id="designation_Spm" disabled=""/>
                                  </div>
                                  <div class="form-group">
                                    <label for="categorie"> CATEGORIE </label>
                                    <input type="text" class="form-control" name="categorie" id="categorie_Spm" disabled=""/>
                                  </div>
                                  <div class="form-group">
                                    <label for="uniteStock"> UNITE STOCK </label>
                                    <input type="text" class="form-control" name="uniteStock" id="uniteStock_Spm" disabled=""/>
                                  </div>
                                  <div class="form-group" >
                                    <label for="prixuniteStock">PRIX UNITE STOCK</label>
                                    <input type="number" class="form-control" disabled="" id="prixuniteStock_Spm" name="prixuniteStock" />
                                  </div>
                                  <div class="form-group" >
                                    <label for="prix">PRIX UNITAIRE</label>
                                    <input type="number" class="form-control" disabled="" id="prix_Spm" name="prix"  />
                                  </div>
                                  <div class="form-group" align="right">
                                      <font color="red"><b>Voulez-vous supprimer ce Produit ? </b></font><br /><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                      <input type="hidden" name="supprimer" value="1" />
                                  </div>
                                </form>
                            </div>
                            </div>
                        </div>
                      </div>

                      <div id="imageNvDesignation"  class="modal fade " role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="padding:35px 50px;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>
                              </div>
                              <div class="modal-body" style="padding:40px 50px;">
                                  <form   method="post" enctype="multipart/form-data">
                                      <input style="display:none" type="text" name="id" id="id_Upd_Nv" />
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

                      <div id="imageExDesignation"  class="modal fade " role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                              </div>
                              <div class="modal-body" style="">
                              <img id="imgsrc_Upd" />
                              <form   method="post" enctype="multipart/form-data">
                                  <input style="display:none" type="text" name="id" id="id_Upd_Ex" />
                                  <input style="display:none" type="text" name="image" id="img_Upd_Ex" />
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
          
                  </div>
                </div>
            </div>
          </div>
        </div>
    </body>
</html>
     <?php
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