
<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');

    $date = new DateTime();
	$annee =$date->format('Y');
	$mois =$date->format('m');
	$jour =$date->format('d');
    $dateString2=$annee.'-'.$mois.'-'.$jour;

    $operation=@htmlspecialchars($_POST["operation"]);
    $idDesignation=@htmlspecialchars($_POST["idDesignation"]);
    $idFusion=@htmlspecialchars($_POST["idFusion"]);
    $categorie=@htmlspecialchars($_POST["categorie"]);
    $designation=@htmlspecialchars($_POST["designation"]);
    $nbreArticleUniteStock=@htmlspecialchars($_POST["nbreArticleUniteStock"]);
    $uniteStock=@htmlspecialchars($_POST["uniteStock"]);
    $dateExpiration=@htmlspecialchars($_POST["dateExpiration"]);

    $prixUniteStock=@htmlspecialchars($_POST["prixUniteStock"]);
    $prixUnitaire=@htmlspecialchars($_POST["prixUnitaire"]);
    $prixAchat=@htmlspecialchars($_POST["prixAchat"]);
    $prixCommercant=@htmlspecialchars($_POST["prixCommercant"]);

    $prixSession=@htmlspecialchars($_POST["prixSession"]);
    $prixPublic=@htmlspecialchars($_POST["prixPublic"]);
    $codeBarreDesignation=@htmlspecialchars($_POST["codeBarreDesignation"]);
    $codeBarreuniteStock=@htmlspecialchars($_POST["codeBarreuniteStock"]);
    $forme=@htmlspecialchars($_POST["forme"]);
    $tableau=@htmlspecialchars($_POST["tableau"]);


    if($operation == 1){
                
                $result='0';
                $sql10="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
                $res10=mysql_query($sql10);
                if(!mysql_num_rows($res10)){
                    $sql1='INSERT INTO `'.$nomtableDesignation.'`
                    (designation,categorie,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,prixachat,codeBarreDesignation,classe,prixcommercant) VALUES
                    ("'.mysql_real_escape_string($designation).'","'.$categorie.'","'.mysql_real_escape_string($uniteStock).'",
                    '.$nbreArticleUniteStock.','.$prixUnitaire.',"'.$prixUniteStock.'","'.$prixAchat.'","'.$codeBarreDesignation.'",0,"'.$prixCommercant.'")';
                    $result='$sql1 ='.$sql1;
                    $res1=@mysql_query($sql1) or die ("insertion stock 3 impossible".mysql_error()) ;


                      $sql11="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($categorie)."'";
                  		$res11=mysql_query($sql11);
                  		if(!mysql_num_rows($res11))
                  				if($categorie) {
                  				$sql="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";
                  				$res=@mysql_query($sql) or die ("insertion categorie ajax impossible".mysql_error());
                          exit($sql);
                  		  }

                    if ($res1) {
                      $result='yes';
                    } else {
                      $result='noooooooo' ;
                    }
                }
                exit($result);


    }
    elseif ($operation == 2) {
      $sql10="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
      $res10=mysql_query($sql10);
      $result='0';
      if(!mysql_num_rows($res10)){
        $sql1='INSERT INTO `'.$nomtableDesignation.'`
        (  `designation` ,`categorie` ,`forme` ,`tableau` ,`prixSession` ,  `prixPublic` ,`classe`, `idFusion`, `codeBarreDesignation`,`tva`,`seuil`) VALUES
        ("'.mysql_real_escape_string($designation).'","'.$categorie.'","'.mysql_real_escape_string($forme).'",
        "'.$tableau.'",'.$prixSession.','.$prixPublic.',0,"'.$idFusion.'","'.$codeBarreDesignation.'",0,0)';

        $result='$sql1 ='.$sql1;
        $res1=@mysql_query($sql1) or die ("insertion stock 3 impossible".mysql_error()) ;

        $sql11="SELECT * FROM `".$nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($categorie)."'";
        $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11))
            if($categorie) {
            $sql="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";
            $test="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";
            $res=@mysql_query($sql) or die ("insertion categorie ajax impossible".mysql_error());
            exit($test);
          }
        if ($res1) {
          $result='1';
        } else {
          $result='0' ;
        }
      }
      exit($result);
    }
    elseif($operation == 3){
      $result="0";
      $sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';
      $res=mysql_query($sql);
      if($design=mysql_fetch_array($res)) {
              $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];
              $categorie=$design["categorie"];
              $uniteStock='Article';
                  $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
                  $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
                  if($res1){
                      $result="1";
                  }
             

          }
        exit($result);
      }