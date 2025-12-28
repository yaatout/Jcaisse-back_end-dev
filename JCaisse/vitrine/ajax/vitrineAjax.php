
<?php

session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduser']){
  header('Location:../../../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:../../accueil.php');
}

require('../../connection.php');
require('../../connectionVitrine.php');

require('../../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/ /**************************************************/
    function find_p_with_position($pns,$des,$unite) {
        foreach($pns as $index => $p) {
            if(($p['designation'] == $des) and ($p['unite'] == $unite)){
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

    $operation=@htmlspecialchars($_POST["operation"]);

    if (isset($_POST["operation"]) and $operation == 1){
        $id=@htmlspecialchars($_POST["id"]);

        $req = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE id=:idV ");
        $req->execute(array('idV' => $id )) or die(print_r($req->errorInfo()));
        $design=$req->fetch();

        $result=$design['id'].'<>'.$design['designationJcaisse'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['categorieVitrine'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'];
        $result .= '<>'.$design['image'].'<>'.$design['idBoutique'].'<>'.$design['uniteDetails'];
        exit($result);

    }
    else if (isset($_POST["operation"]) and $operation == 2){
        $idArticle=@htmlspecialchars($_POST["idArticle"]);
        $idPanier=@htmlspecialchars($_POST["idPanier"]);
        // $qtRetourner=@htmlspecialchars($_POST["qtRetourner"]);
        // $qtCommander=@htmlspecialchars($_POST["qtCommander"]);

        // if($qtCommander === $qtRetourner){
        //     $req = $bddV->prepare("DELETE FROM `ligne` WHERE idArticle = :idA and idPanier = :idP ");
        //     $req->execute(array(
        //         'idP' => $idPanier,
        //         'idA' => $idArticle
        //      )) or die(print_r($req->errorInfo()));
        // }else{

        $req01 = $bddV->prepare("SELECT * FROM `ligne` WHERE idArticle=:idA");
        $req01->execute(array('idA' => $idArticle )) or die(print_r($req01->errorInfo()));
        $ligne=$req01->fetch();
        $prixLigne = $ligne['prixTotal'];

        $req2 = $bddV->prepare("UPDATE `panier` SET total = total - :pl WHERE idPanier = :idP");
        $req2->execute(array(
            'pl' => $prixLigne,
            'idP' => $idPanier
        )) or die(print_r($req2->errorInfo()));

        // $req = $bddV->prepare("DELETE FROM `ligne` WHERE idArticle = :idA and idPanier = :idP");
        $req = $bddV->prepare("UPDATE `ligne` SET barrer = 1 WHERE idArticle = :idA and idPanier = :idP ");
        $req->execute(array(
            'idP' => $idPanier,
            'idA' => $idArticle
        )) or die(print_r($req->errorInfo()));
        // }
        $req0 = $bddV->prepare("SELECT * FROM `ligne` WHERE barrer=0 and idPanier=:idP");
        $req0->execute(array('idP' => $idPanier )) or die(print_r($req0->errorInfo()));
        $lignes=$req0->fetchAll();

        if (empty($lignes)) {
            $req2 = $bddV->prepare("DELETE FROM `panier` WHERE idPanier = :idP");
            $req2->execute(array(
                'idP' => $idPanier
            )) or die(print_r($req2->errorInfo()));
        }

        exit("OK");

    }

    else if (isset($_POST["operation"]) and $operation == 3){
        $result = [];
        $designationJC = [];
        $reqDesV = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."`");
        $reqDesV->execute() or die(print_r($reqDesV->errorInfo()));
        $designV=$reqDesV->fetchAll();

        // $reqDesJC = $bdd->prepare("SELECT * FROM `".$nomtableStock."` where quantiteStockCourant <> 0");
        // $reqDesJC->execute() or die(print_r($reqDesJC->errorInfo()));
        // $resultJC=$reqDesJC->fetchAll();

        $sql1="SELECT designation, SUM(quantiteStockCourant) as stockTotal FROM `".$nomtableStock."` GROUP BY idDesignation";
        $res1=mysql_query($sql1) or die ("select stock impossible =>".mysql_error());
        // $resultJC = mysql_fetch_array($res1);
        while($resultJC=mysql_fetch_array($res1)){
            if ($resultJC['stockTotal'] !== '0') {
              // code...
              $designationJC[] = $resultJC['designation'];
            }
        }

        foreach ($designV as $key) {
            if (in_array($key['designation'], $designationJC)) {
                $result[] = $key['designation'];
            }
        }
        echo json_encode($result);

    }
    else if (isset($_POST["operation"]) and $operation == 4){
        $idA = $_POST["idA"];
        $idP = $_POST["idP"];

        $sqlGetLigne = $bddV->prepare("SELECT * FROM ligne where idArticle = :idA and idPanier = :idP");
        $sqlGetLigne->execute(array(
            'idA' => $idA,
            'idP' => $idP,
            )) or die(print_r($sqlGetLigne->errorInfo()));
        $ligne=$sqlGetLigne->fetch();

        echo json_encode($ligne);
    }
    else if (isset($_POST["operation"]) and $operation == 5){
        $oldIdA = $_POST["oldIdA"];
        $idP = $_POST["idP"];
        $newRef = $_POST["newRef"];
        $oldRef = $_POST["oldRef"];
        $newQuantite = $_POST["newQuantite"];
        $newUnite = $_POST["newUnite"];        //

        $sqlGetOldLigne = $bddV->prepare("SELECT * FROM ligne where idArticle = :oldIdA and idPanier = :idP");
        $sqlGetOldLigne->execute(array(
            'oldIdA' => $oldIdA,
            'idP' => $idP
            )) or die(print_r($sqlGetOldLigne->errorInfo()));
        $oldLigne=$sqlGetOldLigne->fetch();

        $oldLigneTotal = $oldLigne['prixTotal'];
        $oldLigneQt = $oldLigne['quantite'];

        if (((int) $oldLigneQt == (int) $newQuantite) and ($oldLigne['updateQuantite'] != '1')) {
            $upQt = 0;
        } else {
            $upQt = 1;
        }

        $sqlGetDesignation = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` where designation = :design");
        $sqlGetDesignation->execute(array(
            'design' => $newRef
            )) or die(print_r($sqlGetDesignation->errorInfo()));
        $design=$sqlGetDesignation->fetch();

        $nbr = $design['nbreArticleUniteStock'];
        $prixuniteStock = $design['prixuniteStock'];
        $prixUnitaire = $design['prix'];
        $oldUnite = $oldLigne['unite'];

        if ($oldRef === $newRef) {
            if ($oldUnite !== $newUnite) {
                if ($newUnite !== "Article" and $newUnite !== "article" and $nbr !== '1') {
                    $prix = $design['prixuniteStock'];
                } else{
                    $prix = $design['prix'];
                    // $newUnite = 'article';
                }
            } else{
                $prix = $oldLigne['prix'];
            }

            $newTotal = $prix * $newQuantite;

            $req = $bddV->prepare("UPDATE `ligne` SET quantite = :newQt, unite = :newUnite, prix = :prix, prixTotal = :prixTotal, updateQuantite = :upQt WHERE idArticle = :oldIdA and idPanier = :idP ");
            $req->execute(array(
                'idP' => $idP,
                'newQt' => $newQuantite,
                'upQt' => $upQt,
                'newUnite' => $newUnite,
                'prix' => $prix,
                'prixTotal' => $newTotal,
                'oldIdA' => $oldIdA
            )) or die(print_r($req->errorInfo()));
            /****** Update panier *****/
            $req2 = $bddV->prepare("UPDATE panier SET total = total -  :oldT + :newT WHERE idPanier = :idP");
            $req2->execute(array(
                'oldT' => $oldLigneTotal,
                'newT' => $newTotal,
                'idP' => $idP
            )) or die(print_r($req2->errorInfo()));
        } else {
            if ($newUnite === "article") {
                $newTotal = $design['prix'] * $newQuantite;
                $prix = $design['prix'];
            } else {
                $newTotal = $design['prixuniteStock'] * $newQuantite;
                $prix = $design['prixuniteStock'];
            }

            /******** Insert new ligne *********/
            $req4 = $bddV->prepare('INSERT INTO
            ligne(idBoutique,idDesignation,designation, prix,quantite,unite,prixTotal,image,idPanier,inPanier)
            VALUES(:idBoutique,:idDesignation,:designation,:prix, :quantite, :unite, :prixTotal,:image,:idPanier,:inPanier)') ;
            $req4->execute(array(
            'idBoutique' => $design['idBoutique'],
            'idDesignation' => $design['idDesignation'],
            'designation' => $design['designation'],
            'prix' =>$prix,
            'quantite' => $newQuantite,
            'unite' => $newUnite,
            'prixTotal' => $newTotal,
            'image' => $design['image'],
            'idPanier' => $idP,
            'inPanier' => 1
            ))  or die(print_r($req4->errorInfo()));
            $req4->closeCursor();
            /****** Update old ligne *****/
            $req = $bddV->prepare("UPDATE `ligne` SET barrer=1 WHERE idArticle = :oldIdA and idPanier = :idP ");
            $req->execute(array(
                'idP' => $idP,
                'oldIdA' => $oldIdA
            )) or die(print_r($req->errorInfo()));

            /****** Update panier *****/
            $req2 = $bddV->prepare("UPDATE panier SET total = total -  :oldT + :newT WHERE idPanier = :idP");
            $req2->execute(array(
                'oldT' => $oldLigneTotal,
                'newT' => $newTotal,
                'idP' => $idP
            )) or die(print_r($req2->errorInfo()));
        }

        exit('ok');
    }
    else if (isset($_POST["operation"]) and $operation == 6){
        $designation = $_POST["designation"];

        $reqDes = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` where designation = :des");
        $reqDes->execute(array(
            'des' => $designation
        )) or die(print_r($reqDesV->errorInfo()));
        $design=$reqDes->fetch();
        $uniteStock = $design['uniteStock'];
        $nbr = $design['nbreArticleUniteStock'];

        exit($nbr.'<>'.$uniteStock);
    }
    else if (isset($_POST["operation"]) and $operation == 7){
        $idPanier = $_POST["idPanier"];
        $ref = $_POST["ref"];
        $quantite = $_POST["quantite"];
        $unite = $_POST["unite"];

        $sqlGetAricle = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` where designation = :des");
        $sqlGetAricle->execute(array(
            'des' => $ref
        )) or die(print_r($sqlGetAricle->errorInfo()));
        $article=$sqlGetAricle->fetch();

        $sqlGetLignes = $bddV->prepare("SELECT * FROM ligne where idPanier = :idP and barrer=0");
        $sqlGetLignes->execute(array(
            'idP' => $idPanier
        )) or die(print_r($sqlGetLignes->errorInfo()));
        $lignes=$sqlGetLignes->fetchAll();

        if (find_p_with_position($lignes, $ref, $unite) !==FALSE) {
            $i=find_p_with_position($lignes, $ref, $unite);

            $idArticle = $lignes[$i]['idArticle'];
            $oldQt = $lignes[$i]['quantite'];
            $qtTotal = $oldQt + $quantite;

            $subtotal = $lignes[$i]['prix'] * $quantite;
            /****** Update panier *****/
            $req20 = $bddV->prepare("UPDATE ligne SET prixTotal = prixTotal + :subtotal, quantite = :quantite WHERE idArticle = :idA");
            $req20->execute(array(
                'subtotal' => $subtotal,
                'quantite' => $qtTotal,
                'idA' => $idArticle
            )) or die(print_r($req20->errorInfo()));

        }else{

            if ($unite === "article") {
                $subtotal = $article['prix'] * $quantite;
                $prix = $article['prix'];
            } else {
                $subtotal = $article['prixuniteStock'] * $quantite;
                $prix = $article['prixuniteStock'];
            }

            $req4 = $bddV->prepare('INSERT INTO
            ligne(idBoutique,idDesignation,designation, prix,quantite,prixTotal,image,idPanier,unite,inPanier)
            VALUES(:idBoutique,:idDesignation,:designation,:prix, :quantite, :prixTotal,:image,:idPanier,:unite,:inPanier)') ;
            $req4->execute(array(
                'idBoutique' => $article['idBoutique'],
                'idDesignation' => $article['idDesignation'],
                'designation' => $ref,
                'prix' =>$prix,
                'quantite' => $quantite,
                'unite' => $unite,
                'prixTotal' => $subtotal,
                'image' => $article['image'],
                'idPanier' => $idPanier,
                'inPanier' => 1
            ))  or die(print_r($req4->errorInfo()));
            $req4->closeCursor();
        }
        /****** Update panier *****/
        $req2 = $bddV->prepare("UPDATE panier SET total = total +  :total WHERE idPanier = :idP");
        $req2->execute(array(
            'total' => $subtotal,
            'idP' => $idPanier
        )) or die(print_r($req2->errorInfo()));

        exit('1');
    }

// else if (isset($_POST["operation"]) and ($operation == 12)){
    // 	$idPanierV = $_POST['idPanier'];

    // 	$getPanierV = $bddV->prepare("SELECT * FROM panier WHERE idPanier = :idP");
    // 	$getPanierV->execute(array(
    // 		'idP' =>$idPanierV
    // 		)) or die(print_r($getPanierV->errorInfo()));
    // 	$panierV=$getPanierV->fetch();
    // 	$totalp = $panierV['total'];

    // 	$getLignesV = $bddV->prepare("SELECT * FROM ligne WHERE barrer=0 and idPanier = :idP");
    // 	$getLignesV->execute(array(
    // 		'idP' =>$idPanierV
    // 		)) or die(print_r($getLignesV->errorInfo()));
    // 	$lignesV=$getLignesV->fetchAll();

    // 	$date = date("d-m-Y");
    // 	$heure = date("H:i:s");

    // 	$insertNewPanier = "INSERT INTO `".$nomtablePagnet."`(datepagej, type, heurePagnet, totalp, apayerPagnet, verrouiller, iduser, idVitrine) values ('".$date."',11,'".$heure."',".$totalp.",".$totalp.",1,".$_SESSION['iduser'].",".$idPanierV.")";
    // 	$result1 = @mysql_query($insertNewPanier) or die ("insertion new panier impossible".mysql_error()) ;

    // 	$sql0="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPanierV." ";
    // 	$res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    // 	$pagnet = mysql_fetch_assoc($res0) ;
    //     $idPagnet = $pagnet['idPagnet'];

    // 	foreach ($lignesV as $key) {
    // 		$getArticle = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDes");
    // 		$getArticle->execute(array(
    // 			'idDes' =>$key['idDesignation']
    // 			)) or die(print_r($getArticle->errorInfo()));
    // 		$article=$getArticle->fetch();

    // 		$insertNewLignes = "INSERT INTO `".$nomtableLigne."`(idPagnet,idDesignation, designation, unitevente, prixunitevente, quantite, prixtotal, classe) values (".$idPagnet.",".$article['idDesignation'].",'".$key['designation']."','".$key['unite']."',".$key['prix'].",".$key['quantite'].",".$key['prixTotal'].", 0)";
    // 		$result2 = @mysql_query($insertNewLignes) or die ("insertion new ligne impossible".mysql_error()) ;

    // 		if (($key['unite']!="Article")&&($key['unite']!="article"&&($key['unite']!=""))) {
    // 			$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$article['idDesignation']."' ORDER BY idStock ASC ";
    // 			$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    // 			$restant=$key['quantite']*$article['nbreArticleUniteStock'];
    // 			while ($stock = mysql_fetch_assoc($resD)) {
    // 				if($restant>= 0){
    // 					$quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
    // 					if($quantiteStockCourant > 0){
    // 						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
    // 						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    // 					}
    // 					else{
    // 						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
    // 						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    // 					}
    // 					$restant= $restant - $stock['quantiteStockCourant'] ;
    // 				}
    // 			}

    // 		} else if(($key['unite']=="Article")||($key['unite']=="article"||($key['unite']==""))){
    // 			$sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$article['idDesignation']."' ORDER BY idStock ASC ";
    // 			$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    // 			$restant=$key['quantite'];
    // 			while ($stock = mysql_fetch_assoc($resD)) {
    // 				if($restant>= 0){
    // 					$quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
    // 					if($quantiteStockCourant > 0){
    // 						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
    // 						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    // 					}
    // 					else{
    // 						$sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
    // 						$resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    // 					}
    // 					$restant= $restant - $stock['quantiteStockCourant'] ;
    // 				}
    // 			}
    // 		}
    // 	}

    //     $confirmer=1;
    //     $req1 = $bddV->prepare("UPDATE panier SET
    //                   confirmer=:confirmer,
    //                   dateConfirmer= NOW()
    //                   WHERE idPanier=:idPanier ");
    //     $req1->execute(array(
    //                     'confirmer' => $confirmer,
    //                     'idPanier' => $idPanierV )) or die(print_r($req1->errorInfo()));
    //     $req1->closeCursor();

    //     exit('ok');
// }

  else if (isset($_POST["op"]) and $_POST["op"] == 'croppe') {

    $data = $_POST['data'];
    $id = $_POST['id'];
    // $idBoutique = $_SESSION['idBoutique'];
      
    $getproduit = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE id = :id");
    $getproduit->execute(array(
        'id' =>$id
        )) or die(print_r($getproduit->errorInfo()));
    $produit=$getproduit->fetch();
    $designation = $produit['designation'];
    $idBoutique = $produit['idBoutique'];

      $img = @$_POST['img'];
      $uploadPath = "../uploads/";
      // $remotePath = "../../../../asbab/uploads/";
      // $remotePath = "../asbab/uploads/";
      // $remotePath = "/www/asbab/uploads/";
      $remotePath = "public_html/uploads/";

      $data1 = explode(';', $data);
      $data2 = explode(',', $data1[1]);

      $data = base64_decode($data2[1]);

      $fileNameNew = time().'.png';
      file_put_contents($uploadPath."".$fileNameNew, $data);

      $targetLayer = imagecreatefrompng($uploadPath."".$fileNameNew);
      unlink($uploadPath."".$fileNameNew);
      // imagepng($targetLayer,$imageName,9);

      imagepng($targetLayer,$uploadPath."".$fileNameNew,9);
      imagepng($targetLayer,$remotePath."".$fileNameNew,9);
      if ($img) {
        unlink($uploadPath.$img);
        unlink($remotePath.$img);
      // imagedestroy($imageLayer);
      // imagedestroy($resourceType);
      }
      // echo '<img src="'.$imageName.'" alt="" class="img-thumbnail">';
      $req5 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET image=:imageV, idUserImage=:ui WHERE id=:idV ");
      $req5->execute(array(
            'imageV' => $fileNameNew,
            'ui' => $_SESSION['iduser'],
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
      // require('../ftpConnection');

          if ((!$cnx_ftp) || (!$cnx_ftp_auth)) {
              // echo "";
              }
              else
              {
                  // ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);
                  // echo " ";
                  if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $uploadPath.$fileNameNew, FTP_BINARY)) {
                  if($_POST["image"]!=''){
                  unlink($uploadPath.$_POST['image']);
                  ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;
                  //echo "".$remotePath.$_POST['image'];
              }
              } else{
                  //var_dump($remotePath.$fileNameNew);
                  //var_dump($localPath.$fileNameNew);
                  // echo "echec J";
              }
              ftp_quit($cnx_ftp);
          }
      /*****************************  SEND FROM SERVER TO SERVER *****************************/

      exit($data);
  }

  else if (isset($_POST["operation"]) and ($operation == "deleteImg")) {

    $localPath = "../uploads/";

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

        $result = 1;

    }else {

          $result = 0;

    }
    echo $result;

}

else if (isset($_POST["operation"]) and ($operation == 12)){
	$idPanierV = $_POST['idPanier'];
    // var_dump($idPanierV);

	$getPanierV = $bddV->prepare("SELECT * FROM panier WHERE idPanier = :idP");
	$getPanierV->execute(array(
		'idP' =>$idPanierV
		)) or die(print_r($getPanierV->errorInfo()));
	$panierV=$getPanierV->fetch();
	$totalp = $panierV['total'];
	$idClientV = $panierV['idClient'];

	$getLignesV = $bddV->prepare("SELECT * FROM ligne WHERE barrer=0 and idPanier = :idP");
	$getLignesV->execute(array(
		'idP' =>$idPanierV
		)) or die(print_r($getLignesV->errorInfo()));
	$lignesV=$getLignesV->fetchAll();

	$date = date("d-m-Y");
	$heure = date("H:i:s");

	$sqlC="SELECT * FROM `".$nomtableClient."` where idClientVitrine=".$idClientV." ";
	$resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
	$client = mysql_fetch_assoc($resC);
    // var_dump($client);

    if ($client == false) {
        
        $getClient = $bddV->prepare("SELECT * FROM client WHERE idClient = :idC");
        $getClient->execute(array(
            'idC' =>$idClientV
            )) or die(print_r($getClient->errorInfo()));
        $clientV=$getClient->fetch();
        
	    $nom = $clientV['nom'];
	    $prenom = $clientV['prenom'];
	    $adresse = $clientV['adresse'];
	    $telephone = $clientV['telephone'];
        
        $sql1="insert into `".$nomtableClient."` (nom,prenom,adresse,telephone,activer,idClientVitrine,iduser) values 
        ('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$telephone."',1,'".$idClientV."','".$_SESSION['iduser']."')";
        //var_dump($sql1);
        $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error());

        $sql1="SELECT * FROM `".$nomtableClient."` ORDER BY idClient desc limit 1 ";
        $res1=mysql_query($sql1) or die ("select client impossible =>".mysql_error());
        $client = mysql_fetch_array($res1) ;
        // $idClientJCaisse = $client['idClient'];
    } 
    // else {
        
    // }

    $idClientJCaisse = $client['idClient'];

	$insertNewPanier = "INSERT INTO `".$nomtablePagnet."`(datepagej, type, idClient, heurePagnet, totalp, apayerPagnet, verrouiller, iduser, idVitrine) values ('".$date."',11,'".$idClientJCaisse."','".$heure."',".$totalp.",".$totalp.",1,".$_SESSION['iduser'].",".$idPanierV.")";
	$result1 = @mysql_query($insertNewPanier) or die ("insertion new panier impossible".mysql_error()) ;

	$sql0="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPanierV." ";
	$res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
	$pagnet = mysql_fetch_assoc($res0) ;
    $idPagnet = $pagnet['idPagnet'];

	foreach ($lignesV as $key) {
		$getArticle = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDes");
		$getArticle->execute(array(
			'idDes' =>$key['idDesignation']
			)) or die(print_r($getArticle->errorInfo()));
		$article=$getArticle->fetch();

		$insertNewLignes = "INSERT INTO `".$nomtableLigne."`(idPagnet,idDesignation, designation, unitevente, prixunitevente, quantite, prixtotal, classe) values (".$idPagnet.",".$article['idDesignation'].",'".$key['designation']."','".$key['unite']."',".$key['prix'].",".$key['quantite'].",".$key['prixTotal'].", 0)";
		$result2 = @mysql_query($insertNewLignes) or die ("insertion new ligne impossible".mysql_error()) ;

		if (($key['unite']!="Article")&&($key['unite']!="article"&&($key['unite']!=""))) {
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

		} else if(($key['unite']=="Article")||($key['unite']=="article"||($key['unite']==""))){
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

    $confirmer=1;
    $req1 = $bddV->prepare("UPDATE panier SET
                  confirmer=:confirmer,
                  dateConfirmer= NOW()
                  WHERE idPanier=:idPanier ");
    $req1->execute(array(
                    'confirmer' => $confirmer,
                    'idPanier' => $idPanierV )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit('ok');
}


else if (isset($_POST["operation"]) and ($operation == 13)){
    // var_dump($_POST["operation"]);
    $idPanier=$_POST['idPanier'];
        // $confirmer=1;
    $expedition=1;
    $req1 = $bddV->prepare("UPDATE panier SET
                            expedition=:expedition,
                            dateExpedition= NOW()
                            WHERE idPanier=:idPanier ");
    $req1->execute(array(
                        'expedition' => $expedition,
                        'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();
    exit('1');
}

else if (isset($_POST["operation"]) and ($operation == 14)){
    // if (isset($_POST['btnLivrerCommande'])) {
    $idPagnet=$_POST['idPanier'];
    $livrer=1;
        
    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP);
    // $montant=htmlspecialchars(trim($_POST['montant']));

    $paiement="Paiement vente en ligne du panier n° ".$pagnet['idPagnet'];
    $idClient=$pagnet['idClient'];
    $montant=$pagnet['apayerPagnet'];
    
    $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";
    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
    $client = mysql_fetch_assoc($res3);

    // if($_SESSION['proprietaire']==1){

    //     $dateActualiser=htmlspecialchars(trim($_POST['dateActualiser']));

    //     if($dateActualiser!=null){

    //         $dateVersement=$dateActualiser;

    //     }

    //     else{

    //         $dateVersement=$dateString2;

    //     }

    // }

    // else{

    $dateVersement=$dateString2;

    // }

    $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montant,dateVersement,heureVersement,iduser) values(".$idClient.",'".$paiement."',".$montant.",'".$dateVersement."','".$heureString."',".$_SESSION['iduser'].")";

    $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

    $solde=$client['solde']-$montant;

    $sql3="UPDATE `".$nomtableClient."` set solde=".$solde." where idClient=".$idClient;

    $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

    $sql20="UPDATE `".$nomtableBon."` set montant=".$solde.", date='".$dateString."' where idClient=".$idClient;

    $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

    $req1 = $bddV->prepare("UPDATE panier SET
                livrer=:livrer,
                dateLivrer= NOW()
                WHERE idPanier=:idPanier ");
    $req1->execute(array(
                        'livrer' => $livrer,
                        'idPanier' => $idPagnet )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit('1');
}
else if (isset($_POST["operation"]) and ($operation == 15)){
    $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
    // $retourner=1;
    $confirmer=0;

    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;

    $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    //$ligne = mysql_fetch_assoc($resL) ;

    if($pagnet['type']==11){
        while ($ligne=mysql_fetch_assoc($resL)){
            if($ligne['idStock']!=0 && $ligne['classe']==0){
                $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                if(mysql_num_rows($resS)){
                    $stock = mysql_fetch_assoc($resS) or die ("select stock impossible =>".mysql_error());
                    $idDesignation=$stock["idDesignation"];
                    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                    $designation = mysql_fetch_assoc($resD);
                    if(mysql_num_rows($resD)){
                        $quantiteStockCourant=$stock['quantiteStockCourant'];
                        $uniteStock=$stock['uniteStock'];
                        if (($ligne['unitevente']!="Article") && ($ligne['unitevente']!="article")) {
                            $quantiteCourant=$quantiteStockCourant + ($designation['nbreArticleUniteStock'] * $ligne['quantite']);
                            if($quantiteStockCourant >= 0){
                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                //on fait la suppression de cette ligne dans la table ligne
                                $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                                $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
                            }
                        }
                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                            $quantiteCourant=$quantiteStockCourant + $ligne['quantite'];
                            if($quantiteStockCourant >= 0){
                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                //on fait la suppression de cette ligne dans la table ligne
                                $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                                $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
                            }
                        }

                    }
                }
            }
            else{
                if($ligne['classe']==0){
                    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $designation = mysql_fetch_assoc($resS) ;
                        if(mysql_num_rows($resS)){
                            if (($ligne['unitevente']!="Article") && ($ligne['unitevente']!="article")) {
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
    }
    
    $sql3="DELETE FROM `".$nomtablePagnet."` where idVitrine=".$idPagnet."  ";
    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    $sql3="DELETE FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']."  ";
    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    $req1 = $bddV->prepare("UPDATE panier SET
          confirmer=:confirmer
          WHERE idPanier=:idPanier ");
    //var_dump($req1);
    $req1->execute(array(
                        'confirmer' => $confirmer,
                        'idPanier' => $idPagnet )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit('1');
}
else if (isset($_POST["operation"]) and ($operation == 16)){
    $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
    $retourner=1;
    $confirmer=0;

    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;

    $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    //$ligne = mysql_fetch_assoc($resL) ;

    if($pagnet['type']==11){
        while ($ligne=mysql_fetch_assoc($resL)){
            if($ligne['idStock']!=0 && $ligne['classe']==0){
                $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                if(mysql_num_rows($resS)){
                    $stock = mysql_fetch_assoc($resS) or die ("select stock impossible =>".mysql_error());
                    $idDesignation=$stock["idDesignation"];
                    $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                    $designation = mysql_fetch_assoc($resD);
                    if(mysql_num_rows($resD)){
                        $quantiteStockCourant=$stock['quantiteStockCourant'];
                        $uniteStock=$stock['uniteStock'];
                        if (($ligne['unitevente']!="Article") && ($ligne['unitevente']!="article")) {
                            $quantiteCourant=$quantiteStockCourant + ($designation['nbreArticleUniteStock'] * $ligne['quantite']);
                            if($quantiteStockCourant >= 0){
                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                //on fait la suppression de cette ligne dans la table ligne
                                $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                                $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
                            }
                        }
                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                            $quantiteCourant=$quantiteStockCourant + $ligne['quantite'];
                            if($quantiteStockCourant >= 0){
                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                //on fait la suppression de cette ligne dans la table ligne
                                $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                                $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
                            }
                        }

                    }
                }
            }
            else{
                if($ligne['classe']==0){
                    $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $designation = mysql_fetch_assoc($resS) ;
                        if(mysql_num_rows($resS)){
                            if (($ligne['unitevente']!="Article") && ($ligne['unitevente']!="article")) {
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
    }
    $req1 = $bddV->prepare("UPDATE panier SET
          retourner=:retourner ,
          confirmer=:confirmer,
          dateRetourner=NOW()
          WHERE idPanier=:idPanier ");
    //var_dump($req1);
    $req1->execute(array(
                        'retourner' => $retourner,
                        'confirmer' => $confirmer,
                        'idPanier' => $idPagnet )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit('1');
}
else if (isset($_POST["operation"]) and ($operation == 17)){
    $idPanier=$_POST['idPanier'];
    $annuler=1;
    $confirmer=0;
    $req1 = $bddV->prepare("UPDATE panier SET
        annuler=:annuler,
        confirmer=:confirmer,
        dateAnnuler=NOW()
        WHERE idPanier=:idPanier ");
    $req1->execute(array(
        'confirmer' => $confirmer,
        'annuler' => $annuler,
        'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit('1');
}