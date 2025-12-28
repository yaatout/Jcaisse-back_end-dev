
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


    if($operation == 'SearchForme_PH'){
        $idForme=@$_POST["idForme"];
        $result='';
        $sql1="SELECT * from  `".$formeTypeCategPharmacie."` WHERE id ='".$idForme."' ";
        $res1=mysql_query($sql1);
  		if(mysql_num_rows($res1)){
              $design=mysql_fetch_assoc($res1);
  			$result=$id.'<>'.$design['nomForme'];
          }
       exit($result);
    }
    if($operation == 'EditForme_PH'){
        $idForme=@$_POST["idForme"];
        $nomForme=@htmlspecialchars($_POST["nomForme"]);
        $nomForme_old=@htmlspecialchars($_POST["nomForme_old"]);
        $result="1";
        $che="8";

        $sql0="update `".$formeTypeCategPharmacie."` set
         nomForme='".mysql_real_escape_string($nomForme)."'
        where id=".$idForme;
        if ($res0=mysql_query($sql0)) {
          $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE forme ='".$nomForme_old."' ";
          $res1=mysql_query($sql1);
            while($catalogue =mysql_fetch_array($res1)){
              $sql2="UPDATE `".$catalogueTypeCateg."` SET
               forme='".mysql_real_escape_string($nomForme)."'
              WHERE id=".$catalogue['id'];
              $che=$che.$sql2;
              $res2=mysql_query($sql2)or die ("modification impossible1 ".mysql_error());
            }
        } else {
          die ("modification impossible1 ".mysql_error());
        }

       exit($result);
    }
    if($operation == 'DeleteForme_PH'){
          $idForme=@$_POST["idForme"];
          $nomForme=@htmlspecialchars($_POST["nomForme"]);
          $result='1';

            $sql0="DELETE FROM `".$formeTypeCategPharmacie."` WHERE id =".$idForme." ";
            if ($res0=mysql_query($sql0)) {
              $sql1="SELECT * from  `".$catalogueTypeCateg."` WHERE forme ='".$nomForme."' ";
              $res1=mysql_query($sql1);
                while($catalogue =mysql_fetch_array($res1)){
                  $nomForme="SANS FORME";
                  $sql2="update `".$catalogueTypeCateg."` set
                   forme='".mysql_real_escape_string($nomForme)."'
                  where id=".$catalogue['id'];
                  $res2=@mysql_query($sql2)or die ("modification impossible1 ".mysql_error());
                }
            } else {
              die ("modification impossible1 ".mysql_error());
            }

       exit($result);
    }
