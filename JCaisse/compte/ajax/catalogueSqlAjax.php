
<?php

session_start();

require('../connection.php');
require('../declarationVariables.php');

    $id=@$_POST["id"];
    $sql0="SELECT * from  `aaa-catalogueTotal`  where id=".$id;
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

    $operation=@htmlspecialchars($_POST["operation"]);

    if($operation == 'SearchProduit'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id ='".$idDesignation."' ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
			$result=$design['id'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prix'].'<>'.$design['prixuniteStock'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }
    if($operation == 'SearchProduit_PH'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id ='".$idDesignation."' ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
			$result=$design['id'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }
    if($operation == 'EditProduit'){
        $idDesignation=@$_POST["idDesignation"];
        $designation=@htmlspecialchars($_POST["designation"]);
        $categorie=@htmlspecialchars($_POST["categorie"]);
        $uniteStock=@htmlspecialchars($_POST["uniteStock"]);
        $nbreArticleUS=@htmlspecialchars($_POST["nbreArticleUS"]);
        $prix=@htmlspecialchars($_POST["prix"]);
        $prixUS=@htmlspecialchars($_POST["prixUS"]);
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $sql="UPDATE `".$catalogueTypeCateg."` SET designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie)."',uniteStock='".mysql_real_escape_string($uniteStock)."',nbreArticleUniteStock='".$nbreArticleUS."',prix='".$prix."',prixuniteStock='".$prixUS."' WHERE id=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			$result="1";
        }
       exit($result);
    }
    if($operation == 'EditProduit_PH'){
        $idDesignation=@$_POST["idDesignation"];
        $designation=@htmlspecialchars($_POST["designation"]);
        $categorie=@htmlspecialchars($_POST["categorie"]);
        $forme=@htmlspecialchars($_POST["forme"]);
        $tableau=@htmlspecialchars($_POST["tableau"]);
        $prixSession=@htmlspecialchars($_POST["prixSession"]);
        $prixPublic=@htmlspecialchars($_POST["prixPublic"]);
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
        $res1=mysql_query($sql1);
		      if(mysql_num_rows($res1)){
            $sql="UPDATE `".$catalogueTypeCateg."` SET designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie)."',forme='".mysql_real_escape_string($forme)."',tableau='".$tableau."',prixSession='".$prixSession."',prixPublic='".$prixPublic."' WHERE id=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			         $result="1";
        }
       exit($result);
    }
    if($operation == 'EditCodeBarre_PH'){
        $idDesignation=@$_POST["idDesignation"];

        $codeBarreDesignation=@htmlspecialchars($_POST["codeBarreDesignation"]);
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
        $res1=mysql_query($sql1);
		      if(mysql_num_rows($res1)){
            $sql="UPDATE `".$catalogueTypeCateg."` SET codeBarreDesignation='".mysql_real_escape_string($codeBarreDesignation)."' WHERE id=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			         $result="1";
        }
       exit($result);
    }
    if($operation == 'DeleteProduit'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
            $sql="DELETE FROM `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			$result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prix'].'<>'.$design['prixuniteStock'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }
    if($operation == 'DeleteProduit_PH'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
            $sql="DELETE FROM `".$catalogueTypeCateg."` WHERE id =".$idDesignation." ";
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			$result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }
