
<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../connectionVitrine.php');

require('../declarationVariables.php');

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
/**Fin informations sur la date d'Aujourdhui **/
    
$operation=@htmlspecialchars($_POST["operation"]);

$idCategorie=@htmlspecialchars($_POST["idCategorie"]);
$nom=@htmlspecialchars($_POST["nom"]);
$parent=@htmlspecialchars($_POST["parent"]);
$nomCategorie=@htmlspecialchars($_POST["nomCategorie"]);

$idDesignation=@htmlspecialchars($_POST["idDesignation"]);
$designation=@htmlspecialchars($_POST["designation"]);
$prixUniteStock=@htmlspecialchars($_POST["prixUniteStock"]);
$prixUnitaire=@htmlspecialchars($_POST["prixUnitaire"]);
$prixAchat=@htmlspecialchars($_POST["prixAchat"]);


if($operation == 1){
    $sql1="SELECT * FROM `".$nomtableCategorie."` where idcategorie='".$idCategorie."' ";
    $res1=mysql_query($sql1) or die ("Select Categorie impossible 1 =>".mysql_error() );
    $categorie = mysql_fetch_array($res1) ;

    $sql2="SELECT * FROM `".$nomtableCategorie."` where idcategorie='".$categorie['categorieParent']."' ";
    $res2=mysql_query($sql2) or die ("Select Categorie impossible 2 =>".mysql_error() );
    $parent = mysql_fetch_array($res2) ;
        
    if($res1){
        $result='1<>'.$categorie['idcategorie'].'<>'.$categorie['nomcategorie'].'<>'.$categorie['categorieParent'].'<>'.$parent['nomcategorie'];
    }
    else{
        $result="0"; 
    }
    exit($result);
}
else if($operation == 2){
    $sql1="SELECT * FROM `".$nomtableCategorie."` where categorieParent IS NULL OR categorieParent=0 ORDER BY nomcategorie";
    $res1=mysql_query($sql1) or die ("Select Categorie impossible 1 =>".mysql_error() );

    $choix=array();
    while($categorie=mysql_fetch_array($res1)){
        // $sql2="SELECT * FROM `".$nomtableCategorie."` where idcategorie='".$categorie['categorieParent']."' ";
        // $res2=mysql_query($sql2);
        // $parent=mysql_fetch_array($res2);
        // if($parent['nomcategorie']!=null){
            
        //     if(in_array($parent['nomcategorie'], $choix)){
        //         // echo "Existe.";
        //     }
        //     else{
                $rows[]=$categorie['idcategorie'].'<>'.$categorie['nomcategorie'];
                $choix[] = $categorie['nomcategorie'];
        //     } 
        // }
    }
    $data= $rows;
    
    echo json_encode($data);
} 
else if($operation == 3){

    $sql1="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".$parent."' ";
    $res1=mysql_query($sql1);
    $parent=mysql_fetch_array($res1);
  
    $sql2="SELECT * FROM `".$nomtableCategorie."` where categorieParent='".$parent['idcategorie']."' ";
    $res2=mysql_query($sql2);
    
    while($categorie=mysql_fetch_array($res2)){
            $rows[]=$categorie['nomcategorie'];
    }
    $data= $rows;
    
    echo json_encode($data);
}
if($operation == 4){
   
    $sql="UPDATE `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($nom)."',prixuniteStock='".$prixUniteStock."',prixachat='".$prixAchat."' where idDesignation=".$idDesignation;
    $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

    $sql2="UPDATE `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
    //echo $sql2;
    $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

    $sql4="UPDATE `".$nomtableLigne."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
    //echo $sql2;
    $res4=@mysql_query($sql4)or die ("modification reference dans stock ".mysql_error());


    if($_SESSION['vitrine']==1){

    /********************** Début alert mise à jour **********************************/          

    $req50 = $bddV->prepare('UPDATE boutique SET upToDate = :up WHERE idBoutique = :idB');

    $req50->execute(array(

    'idB' => $_SESSION['idBoutique'],

    'up' => 1

    )) or die(print_r($req50->errorInfo()));

    $req50->closeCursor();

    /***************************** Fin alert mise à jour ****************************/ 

    }
    if ($res && $res2 && $res4) {
        # code...
        $result=1;
    } else {
        # code...
        $result=0;
    }
    

   exit($result);
}

else if($operation == 5){
    $id=@htmlspecialchars($_POST["id"]);

    $req = "SELECT * FROM `".$nomtableCategorie."` WHERE idcategorie=".$id."";
    // var_dump($id);
    $res = mysql_query($req) or die ("get categorie".mysql_error());
    $categorie = mysql_fetch_array($res);
    
    $result=$categorie['idcategorie'].'<>'.$categorie['nomcategorie'].'<>'.$categorie['categorieParent'].'<>'.$categorie['image'];
    exit($result);

}

else if($operation == 6) {

    $data = $_POST['data'];
    $id = $_POST['id'];
    $idBoutique = $_POST['idB'];
    $designation = $_POST['des'];
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

    exit($data);
}


else if($operation == 7){
    
    $sql11="SELECT * FROM `". $nomtableCategorie."` ORDER BY nomcategorie";
    // $sql11="SELECT * FROM `". $nomtableCategorie."` where categorieParent<>0 ORDER BY nomcategorie";
    // $sql11="SELECT * FROM `". $nomtableCategorie."` where categorieParent IS NULL OR categorieParent=0 ORDER BY nomcategorie";
    $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());

    $content = '<option selected ></option><option value="sans categorie">Sans catégorie</option>';
 
    while($c = mysql_fetch_row($res11)) {
        if ($nomCategorie == $c[1]) {
            # code...
            $content.='<option style="background-color:red;" disabled="" value="'.$c[1].'">'.$c[1].'</option>';
        } else {
            # code...
            $content.='<option value="'.$c[1].'">'.$c[1].'</option>';
        }
   }
    
    exit($content);

}

else if($operation == 8) {


    $categories = [];

    $query=htmlspecialchars(trim($_POST['query']));
    $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));

    $sql1="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
    $res1=mysql_query($sql1) or die ("Select designation impossible 1 =>".mysql_error() );
    $designation1 = mysql_fetch_array($res1);
    $_categories = $designation1['categorie'];
    $explodeC = explode(' | ', $_categories); 
  
  
    $sql3="SELECT * FROM `".$nomtableCategorie."` where (nomcategorie LIKE '%$query%') and categorieParent!=0 ORDER BY nomcategorie";
  
    $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
  
  
  
    while($categorie = mysql_fetch_assoc($res3)){

        if (in_array($categorie['nomcategorie'], $explodeC)) {
            
            
        }  else {

            $categories[] = $categorie['nomcategorie'];
        }
    }
  
    
  
    echo json_encode($categories);

}


else if ($operation == 9) {

    $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));
    $categorie=htmlspecialchars(trim($_POST['categorie']));

    $sql1="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
    $res1=mysql_query($sql1) or die ("Select designation impossible 1 =>".mysql_error() );
    $designation1 = mysql_fetch_array($res1) ;

    $explodedesignation1 = explode(' | ', $designation1["categorie"]);

    // if (in_array($categorie, $explodedesignation1)) {
    //     # code...
    // } else {
    //     # code...
    
    if ($designation1["categorie"]=='' || $designation1["categorie"]==null || (strtolower($designation1["categorie"])=='sans categorie') || (strtolower($designation1["categorie"])=='sans')) {
        # code...

    } else {
        # code...
        $categorie = $designation1["categorie"].' | '.$categorie;
    }
    // }    
        
    $sql="UPDATE `".$nomtableDesignation."` set categorie='".mysql_real_escape_string($categorie)."' , idUser=".$_SESSION['iduser']." where idDesignation=".$idDesignation;
    $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

    $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET categorie = :c WHERE idDesignation = :idD");

    $req20->execute(array(

        'c' => $categorie,

        'idD' => $idDesignation

    )) or die(print_r($req20->errorInfo()));

    $sql2="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
    $res2=mysql_query($sql2) or die ("Select designation impossible 2 =>".mysql_error() );
    $designation2 = mysql_fetch_array($res2) ;

    echo ($designation2["categorie"]);

}

else if($operation == 10) {
    
    $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));

    $sql2="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
    $res2=mysql_query($sql2) or die ("Select designation impossible 2 =>".mysql_error() );
    $designation = mysql_fetch_array($res2) ;



    // $explodeC = explode(" | ", $designation['categorie']);
    // $content = '';


    // $i = 1;
    // foreach ($explodeC as $c) {
    //     # code...
    //     $content.='
    //                 <div class="form-check">
    //                     <h3>
    //                         <input class="form-check-input" type="checkbox" name="check_list_categorie" value="'.$c.'" id="Chk-'.$i.'">&emsp;
    //                         <label class="form-check-label" for="Chk-'.$i.'">
    //                             '.$c.'
    //                         </label>
    //                     </h3>
    //                 </div>';

    //     $i += 1;
    // }

    exit ($designation['designation']);
}

else if($operation == 11) {
    
    $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));

    $sql="UPDATE `".$nomtableDesignation."` set categorie='sans categorie' where idDesignation=".$idDesignation;
    $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

    $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET categorie = :c WHERE idDesignation = :idD");

    $req20->execute(array(

        'c' => $newCategory,

        'idD' => $idDesignation

    )) or die(print_r($req20->errorInfo()));

    exit(1);
}

else if($operation == 12) {
    
    $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));
    $category=@$_POST['categoryNew'];
    $i = 0;

    $newCategory='';
    if (count($category)==1) {

        $newCategory = $category[0];

    }
    else if (count($category)>1) {

        $newCategory = implode(" | ", $category);

    } else {

        $newCategory = 'sans categorie';
        
        $i = 1;
    }

    $sql="UPDATE `".$nomtableDesignation."` set categorie='".$newCategory."' where idDesignation=".$idDesignation;
    $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

    $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET categorie = :c WHERE idDesignation = :idD");

    $req20->execute(array(

        'c' => $newCategory,

        'idD' => $idDesignation

    )) or die(print_r($req20->errorInfo()));

    if ($i == 1) {
        # code...
        $newCategory = 1;

    }
    

    echo ($newCategory);
}
else if ($operation == 13) {

    $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));
    $categorie=htmlspecialchars(trim($_POST['categorie']));   
        
    $sql="UPDATE `".$nomtableDesignation."` set categorie='".mysql_real_escape_string($categorie)."' where idDesignation=".$idDesignation;
    // $sql="UPDATE `".$nomtableDesignation."` set categorie='".mysql_real_escape_string($categorie)."' , idUser=".$_SESSION['iduser']." where idDesignation=".$idDesignation;
    $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

    $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET categorie = :c WHERE idDesignation = :idD");

    $req20->execute(array(

        'c' => $categorie,

        'idD' => $idDesignation

    )) or die(print_r($req20->errorInfo()));

    $sql2="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
    $res2=mysql_query($sql2) or die ("Select designation impossible 2 =>".mysql_error() );
    $designation2 = mysql_fetch_array($res2);

    echo ($designation2["categorie"]);

}
else if ($operation == 14) {

    $idDesignation=htmlspecialchars(trim($_POST['idDesignation_promo']));

    $sql2="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
    $res2=mysql_query($sql2) or die ("Select designation impossible 2 =>".mysql_error() );
    $designation2 = mysql_fetch_array($res2);

    echo ($designation2["idDesignation"].'<>'.$designation2["categorie"]);

}
else if ($operation == 15) {

    $idDesignation=htmlspecialchars(trim($_POST['idDesignation_promo']));
    $categorie=htmlspecialchars(trim($_POST['categorie']));
    $prixPromo=htmlspecialchars($_POST['prixPromo']);

    $sql11="SELECT * FROM `". $nomtableDesignation."` WHERE idDesignation=".$idDesignation;

    $res11=mysql_query($sql11);    

    $key = mysql_fetch_array($res11);    

    if ($categorie == '' || $categorie == 'Sans categorie') {

        $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET categorie = :c, prixPromo = :pp WHERE idDesignation = :idD");

    } else {
        
        $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET categorie = CONCAT(categorie, ' | ', :c), prixPromo = :pp WHERE idDesignation = :idD");

    }
    
    $req20->execute(array(

        'pp' => $prixPromo,

        'c' => 'Promo',

        'idD' => $idDesignation

    )) or die(print_r($req20->errorInfo()));

    echo ($idDesignation.'<>'.$categorie);

}