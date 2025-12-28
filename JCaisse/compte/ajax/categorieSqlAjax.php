
<?php

session_start();

require('../connection.php');
require('../declarationVariables.php');

    $id=trim(@$_POST["id"]);
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

    if($operation == 'SearchCategorie_PH'){
        $idCategorie=@$_POST["idCategorie"];
        $result='';
        $sql1="SELECT * from  `".$categorieTypeCateg."` WHERE id ='".$idCategorie."' ";
        $res1=mysql_query($sql1);
  		  if(mysql_num_rows($res1)){
              $design=mysql_fetch_assoc($res1);
  			      $result=$id.'<>'.$design['nomCategorie'].'<>'.$design['categorieParent'];
          }
       exit($result);
    }
    if($operation == 'EditCategorie_PH'){
          $idCategorie=@$_POST["idCategorie"];
        $nomCategorie=@htmlspecialchars($_POST["nomCategorie"]);
        $nomCategorie_old=@htmlspecialchars($_POST["nomCategorie_old"]);
        $categorieParent=@htmlspecialchars($_POST["categorieParent"]);
        $result="1";

        $sql0="update `".$categorieTypeCateg."` set
         nomCategorie='".mysql_real_escape_string($nomCategorie)."',
        categorieParent='".mysql_real_escape_string($categorieParent)."'
        where id=".$idCategorie;
        if ($res0=mysql_query($sql0)) {
          $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE categorie ='".$nomCategorie_old."' ";
          $res1=mysql_query($sql1);
            while($catalogue =mysql_fetch_array($res1)){
              $sql2="update `".$catalogueTypeCateg."` set
               categorie='".mysql_real_escape_string($nomCategorie)."'
              where id=".$catalogue['id'];
              $res2=@mysql_query($sql2)or die ("modification impossible1 ".mysql_error());
            }
        } else {
           //die ("modification impossible1 ".mysql_error());
        }
       exit($result);
    }
    if($operation == 'DeleteCategorie_PH'){
        $idCategorie=@$_POST["idCategorie"];
        $nomCategorie=@$_POST["nomCategorie"];
        $result='1';

        $sql0="DELETE FROM `".$categorieTypeCateg."` WHERE id =".$idCategorie." ";

			  //$result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$design['codeBarreDesignation'];

      if ($res0=mysql_query($sql0)) {
        $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE categorie ='".$nomCategorie."' ";
        $res1=mysql_query($sql1);
          while($catalogue =mysql_fetch_array($res1)){
            $nomCategorie="SANS GATEGORIE";
            $sql2="update `".$catalogueTypeCateg."` set
             categorie='".mysql_real_escape_string($nomCategorie)."'
            where id=".$catalogue['id'];
            $res2=@mysql_query($sql2)or die ("modification impossible1 ".mysql_error());
          }
      } else {
         die ("modification impossible1 ".mysql_error());
      }
       exit($result);
    }
