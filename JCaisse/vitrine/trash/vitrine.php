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

// echo
//  $path =$_SERVER['DOCUMENT_ROOT'];
// var_dump($_SESSION['idBoutique']);
if(!$_SESSION['iduser']){
  header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:../accueil.php');
}

require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');

// require('update.php');


if (!empty($_POST["upload"])) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $targetPath = "uploads/" . $_FILES['userImage']['name'];
        if (move_uploaded_file($_FILES['userImage']['tmp_name'], $targetPath)) {
            $uploadedImagePath = $targetPath;
        }
    }
}

/********************************************************/
function find_p_with_position($pns,$des) {
  foreach($pns as $index => $p) {
      if(($p['designation'] == $des)){
        // if ($p['unite'] === $u) {
        //   return $index;
        // } else {
        //   // $nbr = $p['nbreArticleUniteStock'];
          return $index;
        // }
      }
  }
  return FALSE;
}

/************* get reference jcaisse ****************/
// $sqlGetRefJC="SELECT * FROM `".$nomtableDesignation."` WHERE classe = 0";
// $refJC = mysql_query($sqlGetRefJC) or die ("persoonel requête 1".mysql_error());
// $refJcaisse = mysql_fetch_assoc($refJC);
// var_dump($refJcaisse);

/************* get reference vitrine ****************/
// $sqlGetRefV = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."`");
// $sqlGetRefV->execute() or die(print_r($sqlGetRefV->errorInfo()));
// $refVitrine=$sqlGetRefV->fetchAll();
// // var_dump($refVitrine[0]);
// while ($key = mysql_fetch_array($refJC)) {
//   if (find_p_with_position($refVitrine, $key['designation']) !==FALSE) {
//       $i=find_p_with_position($refVitrine, $key['designation']);
//       // if ($key['designation'] == 'CHARGEUR SAMSUNG ORIGINAL DUAL FAST CHARGE') {
//       //     # code...
//       // var_dump($key);
//       // var_dump($refVitrine[$i]);

//       // }
//       /****** Update panier *****/
//       $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET prix = :pu, prixuniteStock = :pus, uniteStock = :us, nbreArticleUniteStock = :nbus WHERE idDesignation = :idD");
//       $req20->execute(array(
//           'pu' => $key['prix'],
//           'pus' => $key['prixuniteStock'],
//           'us' => $key['uniteStock'],
//           'nbus' => $key['nbreArticleUniteStock'],
//           'idD' => $key['idDesignation']
//       )) or die(print_r($req20->errorInfo()));

//   }else{

//       $req4 = $bddV->prepare("INSERT INTO
//       `".$nomtableDesignation."` (idDesignation,designation,designationJcaisse,categorie,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,idBoutique)
//       VALUES(:idD,:des,:desJC,:categorie,:us,:nbus,:prix,:pus,:idB)") ;
//       $req4->execute(array(
//           'idD' => $key['idDesignation'],
//           'des' => $key['designation'],
//           'desJC' =>$key['designation'],
//           'categorie' => $key['categorie'],
//           'us' => $key['uniteStock'],
//           'nbus' => $key['nbreArticleUniteStock'],
//           'prix' => $key['prix'],
//           'pus' => $key['prixuniteStock'],
//           'idB' => $_SESSION['idBoutique']
//       ))  or die(print_r($req4->errorInfo()));
//       $req4->closeCursor();
//   }
// }
/********************************************************/

// var_dump($nomtableDesignation);
/**********************/

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
  require('vitrine-pharmacie.php');
}else{
    if (isset($_POST['suppression'])) {
        if($_POST["image"]!=''){
             $localPath = "./uploads/";
             // $remotePath = "../../asbab/uploads/baniere/";
             $remotePath = "public_html/uploads/";

            unlink($localPath.$_POST['image']);
            ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
        }

        $req5 = $bddV->prepare("DELETE FROM `".$nomtableDesignation."` WHERE id=:idV ");
        $req5->execute(array('idV' => $_POST['id'] )) or die(print_r($req5->errorInfo()));
        $req5->closeCursor();
    }
    if (isset($_POST['modification'])) {

        $req6 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET designation = :d, categorieVitrine = :c, prixuniteStock = :pus, prix = :p WHERE id=:idV ");
        $req6->execute(array(
          'idV' => $_POST['id_edit'],
          'd' => $_POST['designation_editE'],
          'c' => $_POST['categorieV_edit'],
          'pus' => $_POST['prixuniteStock_edit'],
          'p' => $_POST['prix_edit'],
          )) or die(print_r($req6->errorInfo()));
        $req6->closeCursor();
    }
    if(isset($_POST["btnUploadImg"])) {
      // var_dump('fiii');
        function resizeImage($resourceType,$image_width,$image_height) {
          $resizeWidth = 240;
          $resizeHeight = 330;
          $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
          imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
          return $imageLayer;
        }
        $imageProcess = 0;
        if(is_array($_FILES)) {
            $fileName = $_FILES['file']['tmp_name'];
            $sourceProperties = getimagesize($fileName);
            $resizeFileName = time();
            $uploadPath = "./uploads/";
            $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $uploadImageType = $sourceProperties[2];
            $sourceImageWidth = $sourceProperties[0];
            $sourceImageHeight = $sourceProperties[1];

            $id         =@$_POST["id"];
            $idBoutique         =@$_POST["idBoutique"];
            $designation         =@$_POST["designation"];
            $img =    @$_POST['image'];
            $localPath = "./uploads/";
            // var_dump($img);
            // $remotePath = "../../../asbab/uploads/";
            // $remotePath = "../asbab/uploads/";
            // $remotePath = "/www/asbab/uploads/";
            $remotePath = "public_html/uploads/";

            switch ($uploadImageType) {
                case IMAGETYPE_JPEG:
                    $resourceType = imagecreatefromjpeg($fileName);
                    $targetLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                    //imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                    imagejpeg($targetLayer,$uploadPath."".$resizeFileName.'.'. $fileExt,100);
                    imagejpeg($targetLayer,$remotePath."".$resizeFileName.'.'. $fileExt,100);
                    $fileNameNew=$resizeFileName.'.'. $fileExt;
                    if ($img) {
                      unlink($uploadPath.$img);
                      unlink($remotePath.$img);
                    // imagedestroy($imageLayer);
                    // imagedestroy($resourceType);
                    }
                    $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV WHERE id=:idV ");
                    $req5->execute(array(
                          'imageV' => $fileNameNew,
                          'idV' => $id ))
                            or die(print_r($req5->errorInfo()));
                    $req5->closeCursor();

                    $req05 = $bddV->prepare("UPDATE `ligne` SET image=:imageV WHERE idBoutique=:idB and designation = :des ");
                    $req05->execute(array(
                          'imageV' => $fileNameNew,
                          'idB' => $idBoutique,
                          'des' => $designation ))
                            or die(print_r($req05->errorInfo()));
                    $req05->closeCursor();
                    /*****************************  SEND FROM SERVER TO SERVER *****************************/
                    require('./ftpConnection.php');
                    /*****************************  SEND FROM SERVER TO SERVER *****************************/
                    break;
                case IMAGETYPE_PNG:
                    $resourceType = imagecreatefrompng($fileName);
                    $targetLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                    //imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                    imagepng($targetLayer,$uploadPath."".$resizeFileName.'.'. $fileExt,9);
                    imagepng($targetLayer,$remotePath."".$resizeFileName.'.'. $fileExt,9);
                    $fileNameNew=$resizeFileName.'.'. $fileExt;
                    if ($img) {
                      unlink($uploadPath.$img);
                      unlink($remotePath.$img);
                    // imagedestroy($imageLayer);
                    // imagedestroy($resourceType);
                    }
                    $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV WHERE id=:idV ");
                    $req5->execute(array(
                          'imageV' => $fileNameNew,
                          'idV' => $id ))
                    or die(print_r($req5->errorInfo()));
                      $req5->closeCursor();

                      $req05 = $bddV->prepare("UPDATE `ligne` SET image=:imageV WHERE idBoutique=:idB and designation = :des ");
                      $req05->execute(array(
                            'imageV' => $fileNameNew,
                            'idB' => $idBoutique,
                            'des' => $designation ))
                              or die(print_r($req05->errorInfo()));
                      $req05->closeCursor();
                      /*****************************  SEND FROM SERVER TO SERVER *****************************/
                      require('./ftpConnection.php');
                      /*****************************  SEND FROM SERVER TO SERVER *****************************/
                    break;
                default:
                    $imageProcess = 0;
                    echo "Type d'image invalide!";
                    exit;
                    break;
            }
            // echo "Image Resize Successfully.";
        }
        $imageProcess = 0;
      }
    if(isset($_POST["btnUploadImgBaniere"])) {
      // var_dump(344443);
        function resizeImage($resourceType,$image_width,$image_height) {
          $resizeWidth = 620;
          $resizeHeight = 330;
          $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
          imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
          return $imageLayer;
        }
        $imageProcess = 0;
            if(is_array($_FILES)) {
                $fileName = $_FILES['file']['tmp_name'];
                $sourceProperties = getimagesize($fileName);
                $resizeFileName = time();
                $uploadPath = "./uploads/baniere/";
                $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $uploadImageType = $sourceProperties[2];
                $sourceImageWidth = $sourceProperties[0];
                $sourceImageHeight = $sourceProperties[1];

                $id         =@$_POST["id"];
                $j         =@$_POST["j"];
                $img       =@$_POST['image'];
                $baniereX="baniere".$j;

                $localPath = "./uploads/baniere/";
                // $remotePath = "../../../yaatout/uploads/baniere/";
                // $remotePath = "../asbab/uploads/baniere/";
                // $remotePath = "/www/asbab/uploads/baniere/";
                $remotePath = "public_html/uploads/baniere/";
                //echo "string".$baniereX;
                switch ($uploadImageType) {
                    case IMAGETYPE_JPEG:
                        $resourceType = imagecreatefromjpeg($fileName);
                        $targetLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagejpeg($targetLayer,$uploadPath."".$resizeFileName.'.'. $fileExt,100);
                        imagejpeg($targetLayer,$remotePath."".$resizeFileName.'.'. $fileExt,100);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        if ($img) {
                          unlink($uploadPath.$img);
                          unlink($remotePath.$img);
                          //imagedestroy($imageLayer);
                          //imagedestroy($resourceType);
                        }
                        $req5 = $bddV->prepare("UPDATE boutique SET `".$baniereX."`=:ban WHERE id=:idB ");
                        $req5->execute(array(
                              'ban' => $fileNameNew,
                              'idB' => $id ))
                                or die(print_r($req5->errorInfo()));
                        $req5->closeCursor();
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        require('./ftpConnection.php');
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        break;
                    case IMAGETYPE_PNG:
                        $resourceType = imagecreatefrompng($fileName);
                        $targetLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagepng($targetLayer,$uploadPath."".$resizeFileName.'.'. $fileExt,9);
                        imagepng($targetLayer,$remotePath."".$resizeFileName.'.'. $fileExt,9);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        if ($img) {
                          unlink($uploadPath.$img);
                          unlink($remotePath.$img);
                          //imagedestroy($imageLayer);
                          //imagedestroy($resourceType);
                        }
                        $req5 = $bddV->prepare("UPDATE boutique SET `".$baniereX."`=:ban WHERE id=:idB ");
                        $req5->execute(array(
                              'ban' => $fileNameNew,
                              'idB' => $id ))
                                or die(print_r($req5->errorInfo()));
                        $req5->closeCursor();
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        require('./ftpConnection.php');
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        break;
                    default:
                        $imageProcess = 0;
                        break;
                }
            }
          $imageProcess = 0;
      }
    if(isset($_POST["btnUploadImgLogo"])) {
      // var_dump(344443);
        function resizeImage($resourceType,$image_width,$image_height) {
          $resizeWidth = 240;
          $resizeHeight = 330;
          $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
          imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
          return $imageLayer;
        }
        $imageProcess = 0;
            if(is_array($_FILES)) {
                $fileName = $_FILES['file']['tmp_name'];
                $sourceProperties = getimagesize($fileName);
                $resizeFileName = time();
                $uploadPath = "./uploads/baniere/";
                $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $uploadImageType = $sourceProperties[2];
                $sourceImageWidth = $sourceProperties[0];
                $sourceImageHeight = $sourceProperties[1];

                $id         =@$_POST["id"];
                $j         =@$_POST["j"];
                $img       =@$_POST['image'];

                $localPath = "./uploads/baniere/";
                // $remotePath = "../../../yaatout/uploads/baniere/";
                // $remotePath = "../asbab/uploads/baniere/";
                // $remotePath = "/www/asbab/uploads/baniere/";
                $remotePath = "public_html/uploads/baniere/";
                //echo "string".$baniereX;
                switch ($uploadImageType) {
                    case IMAGETYPE_JPEG:
                        $resourceType = imagecreatefromjpeg($fileName);
                        $targetLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagejpeg($targetLayer,$uploadPath."".$resizeFileName.'.'. $fileExt,100);
                        imagejpeg($targetLayer,$remotePath."".$resizeFileName.'.'. $fileExt,100);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        if ($img) {
                          unlink($uploadPath.$img);
                          unlink($remotePath.$img);
                          //imagedestroy($imageLayer);
                          //imagedestroy($resourceType);
                        }
                        $req5 = $bddV->prepare("UPDATE boutique SET `logo`=:logo WHERE id=:idB ");
                        $req5->execute(array(
                              'logo' => $fileNameNew,
                              'idB' => $id ))
                                or die(print_r($req5->errorInfo()));
                        $req5->closeCursor();
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        require('./ftpConnection.php');
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        break;
                    case IMAGETYPE_PNG:
                        $resourceType = imagecreatefrompng($fileName);
                        $targetLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                        //imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                        imagepng($targetLayer,$uploadPath."".$resizeFileName.'.'. $fileExt,9);
                        imagepng($targetLayer,$remotePath."".$resizeFileName.'.'. $fileExt,9);
                        $fileNameNew=$resizeFileName.'.'. $fileExt;
                        if ($img) {
                          unlink($uploadPath.$img);
                          unlink($remotePath.$img);
                          //imagedestroy($imageLayer);
                          //imagedestroy($resourceType);
                        }
                        $req5 = $bddV->prepare("UPDATE boutique SET `logo`=:logo WHERE id=:idB ");
                        $req5->execute(array(
                              'logo' => $fileNameNew,
                              'idB' => $id ))
                                or die(print_r($req5->errorInfo()));
                        $req5->closeCursor();
                        /*****************************  SEND FROM SERVER TO SERVER *****************************/
                        require('./ftpConnection.php');
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
           $localPath = "./uploads/";
           // $remotePath = "../../asbab/uploads/";
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
                              'idV' => $id )) or die(print_r($req5->errorInfo()));
                              $req5->closeCursor();
            }else {
                  echo " ";
            }
      }
      if(isset($_POST["btnSupImgLogo"])) {
             $localPath = "./uploads/baniere/";
             // $remotePath = "../../asbab/uploads/";
             $remotePath = "public_html/uploads/baniere";
             if($_POST['image'] != '') {
                  if (unlink($localPath.$_POST['image'])) {
                    ftp_delete ($cnx_ftp,$remotePath.$_POST['image']);
                  }

                  $id         =@$_POST["id"];
                  $fileNameNew='';

                  $req5 = $bddV->prepare("UPDATE `boutique` SET logo=:logo WHERE id=:idV ");
                  $req5->execute(array(
                                'logo' => $fileNameNew,
                                'idV' => $id )) or die(print_r($req5->errorInfo()));
                                $req5->closeCursor();
              }else {
                    echo " ";
              }
        }
    if(isset($_POST["btnSupImgBaniere"])) {
        $j         =@$_POST["j"];
        $id         =@$_POST["id"];

        $localPath = "./uploads/baniere/";
        // $remotePath = "../../asbab/uploads/baniere/";
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

                  $req = $bddV->prepare("INSERT INTO `".$nomtableDesignation."`(designation, designationJcaisse, categorie, idBoutique, idDesignation, uniteStock,prix, prixuniteStock, codeBarreDesignation, codeBarreuniteStock, idFusion)
              VALUES(:designation,:designationJcaisse,:categorie,:idBoutique, :idDesignation, :uniteStock, :prix, :prixuniteStock, :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              //var_dump($req);
              $req->execute(array(
                            'designation' => $tab3['designation'],
                            'designationJcaisse' => $tab3['designation'],
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

      // require('../entetehtml.php');
    require('../entetehtmlVitrine.php');
    echo'
       <body >';
       require('../header.php');

    echo'<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
    <i class="glyphicon glyphicon-plus"></i>Ajouter un Produit</button></center> ';

    echo'<div class="modal fade bd-example-modal-xl" tabindex="-1" id="AjoutStockModal" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">';
      echo'<div class="modal-dialog modal-lg">';
      echo'<div class="modal-content">';
      echo'<div class="modal-header" style="padding:35px 50px;">';
      echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
      echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de produit </h4>";
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
                  <th>REFERENCE</th>
                  <th>CATEGORIE</th>
                  <th>UNITE STOCK</th>
                  <th>UNITE DETAILS</th>
                  <th>PRIX </th>
                  <th>PRIX UNITE STOCK</th>
                  <th>OPERATIONS</th>
                </tr></thead>
                <tfoot><tr>
                  <th>REFERENCE</th>
                  <th>CATEGORIE</th>
                  <th>UNITE STOCK</th>
                  <th>UNITE DETAILS</th>
                  <th>PRIX </th>
                  <th>PRIX UNITE STOCK</th>
                  <th>OPERATIONS</th>
                </tr></tfoot>';
               $j=0;
               while($tab3=mysql_fetch_array($res3)){
                 if (find_p_with_position($refVitrine, $tab3['designation']) === FALSE) {
                   // code...
                   // var_dump($tab3);
                    $j=$j+1;
                    echo'<tr><form class="form" ><td>'.$tab3["designation"].'</td>
                          <td>'.$tab3["categorie"].'</td>
                          <td>'.$tab3["uniteStock"].'</td>
                          <td>'.$tab3["prix"].'</td>
                          <td>'.$tab3["prixuniteStock"].'</td>
                          ';
                              echo'
                              <td>

                              <input type="hidden" name="designation-'.$tab3['idDesignation'].'" id="designation-'.$tab3['idDesignation'].'" value="'.$tab3["designation"].'"/>
                              <input type="hidden" name="categorie-'.$tab3['idDesignation'].'" id="categorie-'.$tab3['idDesignation'].'" value="'.$tab3['categorie'].'"/>
                              <input type="hidden" name="prixUnitaire-'.$tab3['idDesignation'].'" id="prixUnitaire-'.$tab3['idDesignation'].'" value="'.$tab3["prix"].'"/>
                              <input type="hidden" name="prixuniteStock-'.$tab3['idDesignation'].'" id="prixuniteStock-'.$tab3['idDesignation'].'" value="'.$tab3["prixuniteStock"].'" />
                              <input type="hidden" name="uniteStock-'.$tab3['idDesignation'].'" id="uniteStock-'.$tab3['idDesignation'].'" value="'.$tab3["uniteStock"].'" />
                              <input type="hidden" name="codeBarreDesignation-'.$tab3['idDesignation'].'" id="codeBarreDesignation-'.$tab3['idDesignation'].'" value="'.$tab3["codeBarreDesignation"].'" />
                              <input type="hidden" name="codeBarreuniteStock-'.$tab3['idDesignation'].'" id="codeBarreuniteStock-'.$tab3['idDesignation'].'" value="'.$tab3["codeBarreuniteStock"].'" />
                              <input type="hidden" name="idFusion-'.$tab3['idDesignation'].'" id="idFusion-'.$tab3['idDesignation'].'" value="'.$tab3["idFusion"].'" />
                                <button type="button" onclick="ajt_vitrine('.$tab3["idDesignation"].')" id="btn_ajtVitrine-'.$tab3['idDesignation'].'"
                                  class="btn btn-success "><i class="glyphicon glyphicon-plus">
                                  </i>AJOUTER
                                </button>
                              </td>
                      </form></tr>';
                    }
                }
              echo '</table><br/><center>';
              echo '</center>
                  </form><br />';
         }
      echo '</div></div></div></div></div>';?>

    <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
              <li ><a data-toggle="tab" href="#BANIERE">IMAGE BANIERE</a></li>
              <li ><a data-toggle="tab" href="#LOGO">LOGO</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
                  <div class="table-responsive">
                      <table id="tableStock" class="display tabStock" class="tableau3" align="left" border="1">
                      <thead>
                        <tr id="thStock">
                          <th>ORDRE</th>
                          <th>REFERENCE</th>
                          <th>REFERENCE E-COMMERCE</th>
                          <th>CATEGORIE</th>
                          <th>CATEGORIE VITRINE</th>
                          <th>UNITE STOCK</th>
                          <th>UNITE DETAILS</th>
                          <th>PRIX </th>
                          <th>PRIX UNITE STOCK</th>
                          <th>OPERATIONS</th>
                        </tr>
                      </thead>
                      </table>

                      <script type="text/javascript">
                        $(document).ready(function() {
                            $("#tableStock").dataTable({
                              "bProcessing": true,
                              "sAjaxSource": "vitrine/ajax/listerProduit-VitrineAjax",
                              "aoColumns": [
                                    { mData: "0" } ,
                                    { mData: "1" },
                                    { mData: "2" },
                                    { mData: "3" },
                                    { mData: "4" },
                                    { mData: "5" },
                                    { mData: "6" },
                                    { mData: "7" },
                                    { mData: "8" },
                                    { mData: "9" }
                                  ],
                            });
                        });
                      </script>

                      <!-- /****** Modification ******/ -->

                      <div id="modifierDesignation" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="padding:35px 50px;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"> </span> <b>Modification d'un Produit</b></h4>
                              </div>
                              <div class="modal-body" style="padding:40px 50px;">
                                  <form role="form" class="" id="form" name="formulaire3" method="post" action="vitrine/vitrine">
                                    <input style="display:none" type="text" name="id_edit" id="id_edit" />
                                    <!-- <input type="hidden" name="image_edit" id="image_edit" /> -->
                                    <div class="form-group">
                                      <label for="designation_edit">REFERENCE </label>
                                      <input type="text" class="form-control" name="designation_edit" id="designation_edit" disabled/>
                                    </div>
                                    <div class="form-group">
                                      <label for="designation_editE">REFERENCE E-COMMERCE</label>
                                      <input type="text" class="form-control" name="designation_editE" id="designation_editE"/>
                                    </div>
                                    <div class="form-group">
                                      <label for="categorie">CATEGORIE</label>
                                      <input type="text" class="form-control" name="categorie_edit" id="categorie_edit" disabled/>
                                    </div>
                                    <div class="form-group">
                                      <label for="categorieVitrine"> CATEGORIE VITRINE</label>
                                      <select class="form-control" name="categorieV_edit" id="categorieV_edit">
                                      <?php
                                        $getCategories = $bddV->prepare("SELECT * from categorie");
                                        $getCategories->execute() or die(print_r($getCategories->errorInfo()));
                                        $categories = $getCategories->fetchAll();
                                        foreach ($categories as $key) {
                                      ?>
                                        <option value="<?= $key['nomCategorie']; ?>"><?= $key['nomCategorie']; ?></option>
                                      <?php
                                        }
                                      ?>
                                      </select>
                                      <!-- <input type="text" class="form-control" name="categorie_edit" id="categorie_edit"/> -->
                                    </div>
                                    <div class="form-group">
                                      <label for="uniteStock"> UNITE STOCK </label>
                                      <input type="text" class="form-control" name="uniteStock_edit" id="uniteStock_edit" disabled/>
                                    </div>
                                    <div class="form-group" >
                                      <label for="prixuniteStock">PRIX UNITE STOCK</label>
                                      <input type="number" class="form-control" id="prixuniteStock_edit" name="prixuniteStock_edit" />
                                    </div>
                                    <div class="form-group" >
                                      <label for="prix">PRIX UNITAIRE</label>
                                      <input type="number" class="form-control" id="prix_edit" name="prix_edit"  />
                                    </div>
                                    <div class="form-group" align="right">
                                        <font color="red"><b>Voulez-vous modifier ce Produit ? </b></font><br /><input type="submit" class="boutonbasic" name="modification" value=" Modifier >>" />
                                        <input type="hidden" name="modifier" value="1" />
                                    </div>
                                  </form>
                              </div>
                            </div>
                        </div>
                      </div>
                      <!-- /****** Modification *****/ -->
                      <div id="supprimerDesignation" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="padding:35px 50px;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"> </span> <b>Confirmation Suppression d'un Produit</b></h4>
                              </div>
                              <div class="modal-body" style="padding:40px 50px;">
                                  <form role="form" class="" id="form" name="formulaire2" method="post" action="vitrine/vitrine">
                                    <input style="display:none" type="text" name="id" id="id_Spm" />
                                    <input type="hidden" name="image" id="img_Spm" />
                                    <div class="form-group">
                                      <label for="designation_Spm">REFERENCE </label>
                                      <input type="text" class="form-control" name="designation_Spm" id="designation_Spm" disabled=""/>
                                    </div>
                                    <div class="form-group">
                                      <label for="designation_SpmE">REFERENCE E-COMMERCE</label>
                                      <input type="text" class="form-control" name="designation_SpmE" id="designation_SpmE" disabled=""/>
                                    </div>
                                    <div class="form-group">
                                      <label for="categorie"> CATEGORIE </label>
                                      <input type="text" class="form-control" name="categorie" id="categorie_Spm" disabled=""/>
                                    </div>
                                    <div class="form-group">
                                      <label for="categorieVitrine">CATEGORIE VITRINE</label>
                                      <input type="text" class="form-control" name="categorieV_Spm" id="categorieV_Spm" disabled/>
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
                                      <input type="number" class="form-control" disabled="" id="prix_Spm" name="prix"/>
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
                                      <input style="display:none" type="text" name="idBoutique" id="idB_Upd_Nv" />
                                      <input style="display:none" type="text" name="designation" id="des_Upd_Nv" />
                                      <div class="form-group" >
                                      <br />
                                        <!-- <img src="" id="apercu_file" alt=""> -->
                                        <input type="file" name="file" accept="image/*" id="cover_image" required/>
                                      </div>
                                      <div class="form-group" align="right">
                                          <!-- <input type="submit" class="boutonbasic"  name="btnUploadImg" value="Upload >>"/> -->
                                      </div>
                                  </form>
                              </div>
                            </div>
                          </div>
                          <!-- This is the modal -->
                          <div class="modal" tabindex="-1" role="dialog" id="uploadimageModal">
                              <div class="modal-dialog" role="document" style="min-width: 700px">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Image</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          <div class="row">
                                              <div class="col-md-12 text-center">
                                                  <div id="image_demo"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-primary crop_image">Corriger et enregistrer</button>
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div id="imageExDesignation"  class="modal fade " role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>aperçu/Modification </b></h4>
                              </div>
                              <div class="modal-body" style="">
                                <img width="50%" height="30%" alt="" id="imgsrc_Upd" />
                                <form method="post" enctype="multipart/form-data">
                                    <input style="display:none" type="text" name="id" id="id_Upd_Ex" />
                                    <input style="display:none" type="text" name="image" id="img_Upd_Ex" />
                                    <input style="display:none" type="text" name="designation" id="des_Upd_Ex" />
                                    <input style="display:none" type="text" name="idBoutique" id="idB_Upd_Ex" />
                                    <div class="form-group">
                                      <br />
                                      <input type="file" name="file" accept="image/*" id="cover_image_edit"/>
                                    </div>
                                    <div class="form-group" align="right">
                                        <input type="submit" class="boutonbasic"  name="btnSupImg" value="Supprimer >>"/>
                                        <!-- <input type="submit" class="boutonbasic"  name="btnUploadImg" value="Modifier >>"/> -->
                                    </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <!-- This is the modal -->
                          <div class="modal" tabindex="-1" role="dialog" id="uploadimageModalEdit">
                              <div class="modal-dialog" role="document" style="min-width: 700px">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Image</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          <div class="row">
                                              <div class="col-md-12 text-center">
                                                  <div id="image_demo_edit"></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-primary crop_image_edit">Corriger et enregistrer</button>
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="BANIERE">
                  <?php
                       $req2 = $bddV->prepare("SELECT id,baniere1,baniere2,baniere3 from boutique where nomBoutique = :nomB ");
                       //var_dump($req2);
                       $req2->execute(array(
                              'nomB' =>  $_SESSION['nomB']
                            )) or die(print_r($req2->errorInfo()));
                        /*echo '<table id="exemple3" class="display tabVitrine" border="1">
                            <thead><tr>
                                <th>baniere1 </th>
                                <th>baniere2</th>
                                <th>baniere3</th>
                                </tr>
                              </thead>
                                <tfoot><tr>
                                  <th>baniere1 </th>
                                  <th>baniere2</th>
                                  <th>baniere3</th>
                                </tr>
                              </tfoot>';

                              $j=1;
                          while($tab3=$req2->fetch()){ echo "string";?>
                              <tr>
                                    <td>
                                        <?php if ($tab3["baniere1"]) {
                                            echo  ' <img width="628" height="472" src="./uploads/baniere/'.$tab3["baniere1"].'" /><a>
                                            <img src="images/iconfinder11.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#up'.$j.'" /></a>';
                                                                    echo  '<div id="up'.$j.'"  class="modal fade " role="dialog">
                                                                        <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header" style="">
                                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                                                </div>
                                                                                <div class="modal-body" style="">

                                                                                <form   method="post" enctype="multipart/form-data">
                                                                                    <input type="hidden" name="image" value="'.$tab3["baniere1"].'"/>
                                                                                    <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                                    <input type="hidden" name="j" value="1"/>
                                                                                    <div class="form-group" >
                                                                                    <b> <b><br />
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
                                                                        </div>';
                                        }else{
                                                  echo '<a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#ap'.$j.'" /></a>';
                                                                    echo  '<div id="ap'.$j.'"  class="modal fade " role="dialog">
                                                                        <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header" style="">
                                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                                                </div>
                                                                                <div class="modal-body" style="">
                                                                                <form   method="post" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                                    <input type="hidden" name="j" value="1"/>
                                                                                        <div class="form-group" >
                                                                                        <b> <b><br />
                                                                                          <input type="file" name="file" />
                                                                                        </div>
                                                                                        <div class="form-group" align="right">
                                                                                            <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                        </div>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if ($tab3["baniere2"]) {
                                            echo  ' <img width="628" height="472" src="./uploads/baniere/'.$tab3["baniere2"].'" />
                                            <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#upp'.$j.'" /></a>';
                                                                    echo  '<div id="upp'.$j.'"  class="modal fade " role="dialog">
                                                                        <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header" style="">
                                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                                                </div>
                                                                                <div class="modal-body" style="">

                                                                                <form   method="post" enctype="multipart/form-data">
                                                                                    <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                                    <input type="hidden" name="j" value="2"/>
                                                                                    <input type="hidden" name="image" value="'.$tab3["baniere2"].'"/>
                                                                                    <div class="form-group" >
                                                                                    <b> <b><br />
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
                                                                        </div>';
                                        }else{
                                                  echo '<a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#app'.$j.'" /></a>';
                                                                    echo  '<div id="app'.$j.'"  class="modal fade " role="dialog">
                                                                        <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header" style="">
                                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                                                </div>
                                                                                <div class="modal-body" style="">
                                                                                <form   method="post" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                                        <input type="hidden" name="j" value="2"/>
                                                                                        <div class="form-group" >
                                                                                        <b> <b><br />
                                                                                          <input type="file" name="file" />
                                                                                        </div>
                                                                                        <div class="form-group" align="right">
                                                                                            <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                        </div>';
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if ($tab3["baniere3"]) {
                                            echo  ' <img width="628" height="472" src="./uploads/baniere/'.$tab3["baniere3"].'" /><a>
                                                      <img src="images/iconfinder11.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#uppp'.$j.'" /></a>';
                                                                    echo  '<div id="uppp'.$j.'"  class="modal fade " role="dialog">
                                                                        <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header" style="">
                                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                                                </div>
                                                                                <div class="modal-body" style="">

                                                                                <form   method="post" enctype="multipart/form-data">
                                                                                    <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                                    <input type="hidden" name="j" value="3"/>
                                                                                    <input type="hidden" name="image" value="'.$tab3["baniere3"].'"/>
                                                                                    <div class="form-group" >
                                                                                    <b> <b><br />
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
                                                                        </div>';
                                        }else{
                                                  echo '<a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  data-toggle="modal" data-target="#appp'.$j.'" /></a>';
                                                                    echo  '<div id="appp'.$j.'"  class="modal fade " role="dialog">
                                                                        <div class="modal-dialog">
                                                                              <div class="modal-content">
                                                                                <div class="modal-header" style="">
                                                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Madification </b></h4>
                                                                                </div>
                                                                                <div class="modal-body" style="">
                                                                                <form   method="post" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="id" value="'.$tab3["id"].'"/>
                                                                                    <input type="hidden" name="j" value="3"/>
                                                                                        <div class="form-group" >
                                                                                        <b> <b><br />
                                                                                          <input type="file" name="file" />
                                                                                        </div>
                                                                                        <div class="form-group" align="right">
                                                                                            <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                        </div>';
                                        } ?>
                                    </td>
                              </tr>
                          <?php }
                        */
                            $tab3=$req2->fetch();
                            ?>

                            <div class="row mt-10">
                              <!-- BANIER 1 -->
                                  <?php if ($tab3["baniere1"] ): ?>
                                    <div class="col-sm-6 col-md-4">
                                      <div class="thumbnail">
                                        <?php echo '<img style="width: 50%;height: 40%;" src="vitrine/uploads/baniere/'.$tab3["baniere1"].'" />'; ?>
                                        <div class="caption">
                                          <h3>BANIERE 1</h3>
                                          <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#up1">Modifier</a>
                                            <div id="up1"  class="modal fade " role="dialog">
                                                <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div class="modal-header" style="">
                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                          <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Modification </b></h4>
                                                        </div>
                                                        <div class="modal-body" style="">
                                                          <form   method="post" enctype="multipart/form-data">
                                                              <input type="hidden" name="image" value="<?php echo $tab3["baniere1"]; ?>"/>
                                                              <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                              <input type="hidden" name="j" value="1"/>
                                                              <div class="form-group" >
                                                               <br/>
                                                                <input type="file" name="file"/>
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
                                          </p>
                                        </div>
                                      </div>
                                    </div>
                                  <?php else : ?>
                                      <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">

                                          <div class="caption">
                                            <h3>BANIERE 1 VIDE</h3>
                                            <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#ap1">Ajouter</a>
                                            <div id="ap1"  class="modal fade " role="dialog">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header" style="">
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Modification </b></h4>
                                                    </div>
                                                    <div class="modal-body" style="">
                                                        <form   method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                            <input type="hidden" name="j" value="1"/>
                                                            <div class="form-group" >
                                                              <br />
                                                              <input type="file" name="file" required/>
                                                            </div>
                                                            <div class="form-group" align="right">
                                                                <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                            </div>
                                                        </form>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            </p>
                                          </div>
                                        </div>

                                      </div>
                                  <?php endif; ?>
                              <!-- FIN BANIER 1 -->
                              <!-- BANIER 2 -->
                                  <?php if ($tab3["baniere2"] ): ?>
                                    <div class="col-sm-6 col-md-4">
                                      <div class="thumbnail">
                                        <?php echo '<img style="width: 50%;height: 40%;" src="vitrine/uploads/baniere/'.$tab3["baniere2"].'" />'; ?>
                                        <div class="caption">
                                          <h3>BANIERE 2</h3>
                                          <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#up2">Modifier</a>
                                            <div id="up2"  class="modal fade " role="dialog">
                                                <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div class="modal-header" style="">
                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                          <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Modification </b></h4>
                                                        </div>
                                                        <div class="modal-body" style="">

                                                        <form   method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="image" value="<?php echo $tab3["baniere2"]; ?>"/>
                                                            <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                            <input type="hidden" name="j" value="2"/>
                                                            <div class="form-group" >
                                                            <br />
                                                              <input type="file" name="file"/>
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
                                          </p>
                                        </div>
                                      </div>
                                    </div>
                                  <?php else : ?>
                                      <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">

                                          <div class="caption">
                                            <h3>BANIERE 2 VIDE</h3>
                                            <p>
                                              <a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#ap2">Ajouter</a>
                                              <div id="ap2"  class="modal fade " role="dialog">
                                                <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div class="modal-header" style="">
                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                          <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Modification </b></h4>
                                                        </div>
                                                        <div class="modal-body" style="">
                                                        <form   method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                            <input type="hidden" name="j" value="2"/>
                                                                <div class="form-group" >
                                                                <br />
                                                                  <input type="file" name="file" required/>
                                                                </div>
                                                                <div class="form-group" align="right">
                                                                    <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                                </div>
                                                            </form>
                                                        </div>
                                                      </div>
                                                </div>
                                              </div>
                                            </p>
                                          </div>
                                        </div>

                                      </div>
                                  <?php endif; ?>
                              <!-- FIN BANIER 2 -->
                              <!-- BANIER 3 -->
                                  <?php if ($tab3["baniere3"] ): ?>
                                    <div class="col-sm-6 col-md-4">
                                      <div class="thumbnail">
                                        <?php echo '<img style="width: 50%;height: 40%;" src="vitrine/uploads/baniere/'.$tab3["baniere3"].'" />'; ?>
                                        <div class="caption">
                                          <h3>BANIERE 3</h3>
                                          <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#up3">Modifier</a>
                                            <div id="up3"  class="modal fade " role="dialog">
                                                <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div class="modal-header" style="">
                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                          <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Modification </b></h4>
                                                        </div>
                                                        <div class="modal-body" style="">
                                                          <form   method="post" enctype="multipart/form-data">
                                                              <input type="hidden" name="image" value="<?php echo $tab3["baniere3"]; ?>"/>
                                                              <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                              <input type="hidden" name="j" value="3"/>
                                                              <div class="form-group" >
                                                              <br />
                                                                <input type="file" name="file"/>
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
                                          </p>
                                        </div>
                                      </div>
                                    </div>
                                  <?php else : ?>
                                      <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">

                                          <div class="caption">
                                            <h3>BANIERE 3 VIDE</h3>
                                            <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#ap3">Ajouter</a>
                                            <div id="ap3"  class="modal fade " role="dialog">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header" style="">
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>apperçu/Modification </b></h4>
                                                    </div>
                                                    <div class="modal-body" style="">
                                                      <form   method="post" enctype="multipart/form-data">
                                                              <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                          <input type="hidden" name="j" value="3"/>
                                                              <div class="form-group" >
                                                              <br />
                                                                <input type="file" name="file" required/>
                                                              </div>
                                                              <div class="form-group" align="right">
                                                                  <input type="submit" class="boutonbasic"  name="btnUploadImgBaniere" value="Upload >>"/>
                                                              </div>
                                                      </form>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            </p>
                                          </div>
                                        </div>

                                      </div>
                                  <?php endif; ?>
                              <!-- FIN BANIER 3 -->
                          </div>
                </div>
                <div class="tab-pane fade" id="LOGO">

                    <!-- BANIER 1 -->
                        <?php

                       $req2 = $bddV->prepare("SELECT id,logo from boutique where nomBoutique = :nomB ");
                       $req2->execute(array(
                              'nomB' =>  $_SESSION['nomB']
                            )) or die(print_r($req2->errorInfo()));
                        $tab3 = $req2->fetch();
                        // var_dump($tab3);
                        if ($tab3["logo"] ): ?>
                        <div class="col-sm-6 col-md-4">
                        </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                              <?php echo '<img style="width: 50%;height: 60%;" src="vitrine/uploads/baniere/'.$tab3["logo"].'" />'; ?>
                              <div class="caption">
                                <h3>LOGO</h3>
                                <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#editLogo">Modifier</a>
                                  <div id="editLogo"  class="modal fade " role="dialog">
                                      <div class="modal-dialog">
                                            <div class="modal-content">
                                              <div class="modal-header" style="">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Aperçu/Modification </b></h4>
                                              </div>
                                              <div class="modal-body" style="">
                                                <form   method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="image" value="<?php echo $tab3["logo"]; ?>"/>
                                                    <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                    <input type="hidden" name="j" value="1"/>
                                                    <div class="form-group" >
                                                     <br/>
                                                      <input type="file" name="file"/>
                                                    </div>
                                                    <div class="form-group" align="right">
                                                        <input type="submit" class="boutonbasic"  name="btnSupImgLogo" value="Suprimer >>"/>
                                                        <input type="submit" class="boutonbasic"  name="btnUploadImgLogo" value="Modifier >>"/>
                                                    </div>
                                                </form>
                                              </div>
                                            </div>
                                      </div>
                                  </div>
                                </p>
                              </div>
                            </div>
                          </div>
                        <?php else : ?>
                          <div class="col-sm-6 col-md-4">
                          </div>
                            <div class="col-sm-6 col-md-4">
                              <div class="thumbnail">

                                <div class="caption">
                                  <h3>PAS DE LOGO</h3>
                                  <p><a href="#" class="btn btn-primary" role="button" data-toggle="modal" data-target="#addlogo">Ajouter</a>
                                  <div id="addlogo"  class="modal fade " role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header" style="">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Aperçu/Modification </b></h4>
                                          </div>
                                          <div class="modal-body" style="">
                                              <form   method="post" enctype="multipart/form-data">
                                                  <input type="hidden" name="id" value="<?php echo $tab3["id"]; ?>"/>
                                                  <input type="hidden" name="j" value="1"/>
                                                  <div class="form-group" >
                                                    <br />
                                                    <input type="file" name="file" required/>
                                                  </div>
                                                  <div class="form-group" align="right">
                                                      <input type="submit" class="boutonbasic"  name="btnUploadImgLogo" value="Upload >>"/>
                                                  </div>
                                              </form>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                                  </p>
                                </div>
                              </div>

                            </div>
                        <?php endif; ?>
                    <!-- FIN BANIER 1 -->
                </div>
            </div>
          </div>
        <?php

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

}
?>
