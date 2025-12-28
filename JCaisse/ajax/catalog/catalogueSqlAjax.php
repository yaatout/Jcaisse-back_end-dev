
<?php

session_start();

require('../../connection.php');
require('../../declarationVariables.php');

  
    $operation=@htmlspecialchars($_POST["operation"]);

    if ($operation=="image") {
        $idD = @$_POST["idD"];
        $sql13="SELECT * from  `".$nomtableDesignation."` where idDesignation='".$idD."'";
        //var_dump($sql13);
        $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
        $design = mysql_fetch_assoc($res13);
      
        $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];
        exit($result);
         
      }
    if($operation == 'SearchProduit'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation ='".$idDesignation."' ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
			$result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prix'].'<>'.$design['prixuniteStock'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }   
    if($operation == 'EditProduit_B'){
        $idDesignation=@$_POST["idDesignation"];
        $designation=@htmlspecialchars($_POST["designation"]);
        $categorie=@htmlspecialchars($_POST["categorie"]);
        $uniteStock=@htmlspecialchars($_POST["uniteStock"]);
        $nbreArticleUS=@htmlspecialchars($_POST["nbreArticleUS"]);
        $prix=@htmlspecialchars($_POST["prix"]);
        $prixUS=@htmlspecialchars($_POST["prixUS"]);
        $codeBarreDesignation=@htmlspecialchars($_POST["codeBarreDesignation"]);
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $sql="UPDATE `".$nomtableDesignation."` SET designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie)."'
            ,uniteStock='".mysql_real_escape_string($uniteStock)."'
            ,nbreArticleUniteStock='".$nbreArticleUS."',prix='".$prix."'
            ,codeBarreDesignation='".$codeBarreDesignation."'
            ,prixuniteStock='".$prixUS."' WHERE idDesignation=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			$result="1";
        }
       exit($result);
    }
    if($operation == 'DeleteProduit'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
            $sql="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			$result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prix'].'<>'.$design['prixuniteStock'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }
    if($operation == 'SearchProduit_PH'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation ='".$idDesignation."' ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
			$result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$design['codeBarreDesignation'];
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
        $codeBarreDesignation=@htmlspecialchars($_POST["codeBarreDesignation"]);
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
        var_dump($sql1);
        $res1=mysql_query($sql1);
		      if(mysql_num_rows($res1)){
            $sql="UPDATE `".$nomtableDesignation."` SET designation='".mysql_real_escape_string($designation)."',
            categorie='".mysql_real_escape_string($categorie)."',
            forme='".mysql_real_escape_string($forme)."',tableau='".$tableau."',codeBarreDesignation='".$codeBarreDesignation."',
            prixSession='".$prixSession."',prixPublic='".$prixPublic."' WHERE idDesignation=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			         $result="1";
        }
       exit($result);
    }
    if($operation == 'EditCodeBarre_PH'){
        $idDesignation=@$_POST["idDesignation"];

        $codeBarreDesignation=@htmlspecialchars($_POST["codeBarreDesignation"]);
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
        $res1=mysql_query($sql1);
		      if(mysql_num_rows($res1)){
            $sql="UPDATE `".$nomtableDesignation."` SET codeBarreDesignation='".mysql_real_escape_string($codeBarreDesignation)."' WHERE idDesignation=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			         $result="1";
        }
       exit($result);
    }
    
    if($operation == 'DeleteProduit_PH'){
        $idDesignation=@$_POST["idDesignation"];
        $result='';
        $sql1="SELECT * from  `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
        $res1=mysql_query($sql1);
		if(mysql_num_rows($res1)){
            $design=mysql_fetch_assoc($res1);
            $sql="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation =".$idDesignation." ";
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
			$result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$design['codeBarreDesignation'];
        }
       exit($result);
    }
