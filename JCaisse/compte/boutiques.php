<?php
/*
R�sum� :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; 
Date de cr�ation : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();

if($_SESSION['iduserBack']){

require('connection.php');
require('connectionVitrine.php');


require('declarationVariables.php');

//	$nomBoutique			=htmlentities('a&é shop');
// $nomB       			=html_entity_decode($nomBoutique);
// $nomB2       			=htmlspecialchars('a&é shop');
//    $nomB3		=mysql_real_escape_string('a&é shop');
//var_dump($nomBoutique);
//    echo '<br/>'; 
// var_dump($nomB);
// echo '<br/>'; 
// var_dump($nomB2);
//    echo '<br/>'; 
// var_dump($nomB3);
//    echo '<br/>*******************<br/>';
// $nomBoutique			=htmlentities('yomblÃ© & pir');
// $nomB       			=html_entity_decode('yomblÃ© & pir');
// $nomB2       			=htmlspecialchars('yomblÃ© & pir');
// $nomB3		=mysql_real_escape_string('yomblÃ© & pir');
//var_dump($nomBoutique);
//    echo '<br/>'; 
// var_dump($nomB);
// echo '<br/>'; 
// var_dump($nomB2);
//    echo '<br/>'; 
// var_dump($nomB3);
//    echo '<br/>*******************<br/>'; 
//$nomBoutique			= mysql_real_escape_string('yomblé & pir');
// $nomB       			=mysql_real_escape_string('yomblÃ© & pir');
// $nomB2       			=mysql_real_escape_string(htmlspecialchars('yomblÃ© & pir'));
// $nomB3		=mysql_real_escape_string('yombl&atilde;&copy; &amp; pir');
//var_dump($nomBoutique);
//    echo '<br/>'; 
// var_dump($nomB);
// echo '<br/>'; 
// var_dump($nomB2);
//    echo '<br/>'; 
// var_dump($nomB3);
//    echo '<br/>*******************<br/>';
//    
/*$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";*/

/**********************/
$idStock         =@$_POST["idStock"];

$designation      =@htmlentities($_POST["designation"]);
$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
//$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];


$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$idStock2       =@$_GET["idStock"];
    $messageSupBoutiqueOK='';
    $messageSupBoutiqueERROR='';
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;

if (isset($_POST['btnTerminerTest'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=1;
	$sql3="UPDATE `aaa-boutique` set  enTest='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}elseif (isset($_POST['btnDesactiverTest'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=0;
	$sql3="UPDATE `aaa-boutique` set  enTest='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}
if (isset($_POST['btnActiverVitrine'])) {
	$idBoutique=$_POST['idboutique'];
	$nomBoutique=$_POST['nomBoutique'];
	$type=$_POST['type'];
	$categorie=$_POST['categorie'];
	$adresse=$_POST['adresse'];
	$activer=1;
	$vitrine=1;
    
   // var_dump($_POST['tab']);
    
	$sql3="UPDATE `aaa-boutique` set  vitrine='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
    
    $req2= $bddV->prepare("INSERT INTO
                        boutique(idBoutique,nomBoutique,type,categorie,adresse,vitrine)
                        VALUES (:idBoutique, :nomBoutique, :type,:categorie,:adresse,:vitrine)
                        ") ;
    $req2->execute(array(
                         'idBoutique' => $idBoutique,
                         'nomBoutique' => $nomBoutique,
                         'type' => $type,
                         'categorie' => $categorie,
                         'adresse' => $adresse,
                         'vitrine' => $vitrine
                    ));
    $req2->closeCursor();
    
     $req5 = $bddV->prepare('UPDATE boutique SET vitrine = :vitrine WHERE idBoutique = :idBoutique');
     $req5->execute(array(
                          'vitrine' => $vitrine,
                          'idBoutique' => $idBoutique
                          )) or die(print_r($req5->errorInfo()));
     $req5->closeCursor();
    
    $nomtableDesignation=$nomBoutique."-designation";
    
    try {
           $req1 =$bddV->exec("CREATE TABLE IF NOT EXISTS   `".$nomtableDesignation."` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                          `idDesignation` int(11) NOT NULL,
                          `idBoutique` int(11) NOT NULL,
                          `designation` varchar(50) NOT NULL,
                          `designationJcaisse` varchar(50) NOT NULL,
                          `description` varchar(100) DEFAULT NULL,
                          `categorie` varchar(50) NOT NULL,
                          `uniteStock` varchar(50) NOT NULL,
                          `nbreArticleUniteStock` double NOT NULL,
                          `prix` double NOT NULL,
                          `seuil` int(11) NOT NULL,
                          `prixuniteStock` double NOT NULL,
                          `codeBarreDesignation` varchar(50) NOT NULL,
                          `codeBarreuniteStock` varchar(50) NOT NULL,
                          `classe` int(11) NOT NULL,
                          `idFusion` int(11) DEFAULT NULL,
                          `image` varchar(30) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM   DEFAULT CHARSET=utf8");
        
                 
        
        }
        catch(PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
        
    
}elseif (isset($_POST['btnDesactiverVitrine'])) {
	$idBoutique=$_POST['idboutique'];
    
	$nomBoutique=$_POST['nomBoutique'];
    $nomtableDesignation=$nomBoutique."-designation";
	$activer=0;
	$vitrine=0;
    
	$sql3="UPDATE `aaa-boutique` set  vitrine='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
    
    $req5 = $bddV->prepare('UPDATE boutique SET vitrine = :vitrine WHERE idBoutique = :idBoutique');
                                      $req5->execute(array(
                                      'vitrine' => $vitrine,
                                      'idBoutique' => $idBoutique
                                    )) or die(print_r($req5->errorInfo()));
                                      $req5->closeCursor();
}

if (isset($_POST['btnActiverConf'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=1;
	$sql3="UPDATE `aaa-boutique` set  enConfiguration='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}elseif (isset($_POST['btnDesactiverConf'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=0;
	$sql3="UPDATE `aaa-boutique` set  enConfiguration='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}

if (isset($_POST['btnActiver'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=1;
	$sql3="UPDATE `aaa-boutique` set  activer='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}elseif (isset($_POST['btnDesactiver'])) {
	$idBoutique=$_POST['idboutique'];
	$activer=0;
	$sql3="UPDATE `aaa-boutique` set  activer='".$activer."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}elseif (isset($_POST['btnModifierBoutique'])) {

	$idBoutique					=$_POST['idBoutique'];
	$nomBoutique				=$_POST['nomBoutique'];
	
	$labelB						=@htmlentities($_POST['labelB']);
	$adresseB					=@htmlentities($_POST['adresseB']);
	$type        				=@htmlentities($_POST['type']);
	$categorie					=@htmlentities($_POST['categorie']);
	
	/*$nomBInitial    		    =$_POST['nomBInitial'];
	$adresseBInitial 		    =$_POST['adresseBInitial'];
	$typeBInitial 	   			=$_POST['typeBInitial'];
	$categorieBInitial          =$_POST['categorieBInitial'];*/
	
	//echo $idBoutique;
	
	$sql3="UPDATE `aaa-boutique` set  `labelB`='".mysql_real_escape_string($labelB)."',type='".$type."',adresseBoutique='".mysql_real_escape_string($adresseB)."',categorie='".mysql_real_escape_string($categorie)."' where idBoutique=".$idBoutique;
	$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

}elseif (isset($_POST['btnSupprimerBoutique'])) {

	$idBoutique				=$_POST['idBoutique'];
	$nomBoutique		=htmlentities($_POST['nomBoutique']);    
//	$nomBoutique		=htmlentities('yomblé & pir');    
//    $nomB  			=html_entity_decode('yombl&Atilde;');
//	$nomB2		=mysql_real_escape_string('yomblé & pir');
//	$nomB3		=htmlspecialchars($_POST['nomBoutique']);
// 
//    var_dump($nomBoutique);
//    echo '<br/>'; 
//     var_dump($nomB); 
//        echo '<br/>'; 
//     var_dump($nomB2);
//    echo '<br/>'; 
//     var_dump($nomB3);
    
 /*$labelB						=@htmlentities($_POST['labelB']);
	$adresseB					=@htmlentities($_POST['adresseB']);
	$type        				=@htmlentities($_POST['type']);
	$categorie					=@htmlentities($_POST['categorie']);
	
	$nomBInitial    		    =$_POST['nomBInitial'];
	$adresseBInitial 		    =$_POST['adresseBInitial'];
	$typeBInitial 	   			=$_POST['typeBInitial'];
	$categorieBInitial          =$_POST['categorieBInitial'];*/
	
	//echo $idBoutique;
	
	
	
	

	$nomtableJournal=$nomBoutique."-journal";
	$nomtableCategorie=$nomBoutique."-categorie";
	$nomtablePage=$nomBoutique."-pagej";
	$nomtablePagnet=$nomBoutique."-pagnet";
	$nomtableLigne=$nomBoutique."-lignepj";
	$nomtableDesignation=$nomBoutique."-designation";
	$nomtableDesignationsd=$nomBoutique."-designationsd";
	$nomtableStock=$nomBoutique."-stock";
	$nomtableTotalStock=$nomBoutique."-totalstock";
	$nomtableBon=$nomBoutique."-bon";
	$nomtableClient=$nomBoutique."-client";
	$nomtableVersement=$nomBoutique."-versement";
    
	$nomtableCompte=$nomBoutique."-compte";
	$nomtableFournisseur=$nomBoutique."-fournisseur";
	$nomtableBl=$nomBoutique."-bl";
	$nomtableInventaire=$nomBoutique."-inventaire";

	//echo $nomtableJournal;

	$sql1="DROP TABLE  `".$nomtableJournal."`";
	//echo $sql;
  	$res1=@mysql_query($sql1);
	
	$sql2="DROP TABLE IF EXISTS `".$nomtableCategorie."`";
  	$res2=@mysql_query($sql2);
	
	$sql3="DROP TABLE IF EXISTS `".$nomtablePage."`";
  	$res3=@mysql_query($sql3);
	
	$sql4="DROP TABLE IF EXISTS `".$nomtablePagnet."`";
  	$res4=@mysql_query($sql4);
    
	$sql0="DROP TABLE IF EXISTS .`".$nomtableDesignationsd."`";
  	$res0=@mysql_query($sql0);
	
	$sql5="DROP TABLE IF EXISTS `".$nomtableLigne."`";
  	$res5=@mysql_query($sql5);
	
	$sql6="DROP TABLE IF EXISTS `".$nomtableDesignation."`";
  	$res6=@mysql_query($sql6);
	
	$sql7="DROP TABLE IF EXISTS `".$nomtableStock."`";
  	$res7=@mysql_query($sql7);
	
	$sql8="DROP TABLE IF EXISTS `".$nomtableBon."`";
  	$res8=@mysql_query($sql8);
	
	$sql9="DROP TABLE IF EXISTS `".$nomtableClient."`";
  	$res9=@mysql_query($sql9);
	
	$sql10="DROP TABLE IF EXISTS `".$nomtableVersement."`";
  	$res10=@mysql_query($sql10);
	
	$sql11="DROP TABLE IF EXISTS `".$nomtableTotalStock."`";
  	$res11=@mysql_query($sql11);	
    
    $sql12="DROP TABLE IF EXISTS `".$nomtableCompte."`";
  	$res12=@mysql_query($sql12);	
    
    $sql13="DROP TABLE IF EXISTS `".$nomtableFournisseur."`";
  	$res13=@mysql_query($sql13);	

    $sql14="DROP TABLE IF EXISTS `".$nomtableBl."`";
  	$res14=@mysql_query($sql14);	
    
    $sql15="DROP TABLE IF EXISTS `".$nomtableInventaire."`";
  	$res15=@mysql_query($sql15);	

	if($res1){
          $sql16="SELECT * FROM `aaa-acces` WHERE idBoutique=".$idBoutique;
    	  $res16 = mysql_query($sql16) or die ("personel requête 2".mysql_error());
    	  while ($acces = mysql_fetch_array($res16)) { 
    		/*$sql17="DELETE FROM `aaa-utilisateur` WHERE idutilisateur=".$acces['idutilisateur'];
    		$res17=@mysql_query($sql17) or die ("suppression impossible personnel".mysql_error()); */
    		$sql18="DELETE FROM `aaa-acces` WHERE idutilisateur=".$acces['idutilisateur'];
    		$res18=@mysql_query($sql18) or die ("suppression impossible personnel".mysql_error());     
    	}
    	 $sql19="DELETE FROM `aaa-boutique` WHERE idBoutique=".$idBoutique;
  	     $res19=@mysql_query($sql19) or die ("suppression impossible personnel".mysql_error());
        
         $messageSupBoutiqueOK="Boutique Suprimée avec succée";
    }else{
        $messageSupBoutiqueERROR="La Supression du boutique n'a pas marché";
    }
	 
  

     
    
	/*
	//echo $nomtableJournal;
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableJournal)."`";
	//echo $sql;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableCategorie)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtablePage)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtablePagnet)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableLigne)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableDesignation)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableStock)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableBon)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableClient)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableVersement)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());
	
	$sql="DROP TABLE IF EXISTS `yatoutshxpsn`.`".mysql_real_escape_string($nomtableTotalStock)."`";
  	$res=@mysql_query($sql) or die ("suppression impossible personnel".mysql_error());	

	
*/

}



?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/datatables.min.js"></script>
    <script>$(document).ready( function () {$('#exemple').DataTable();} );</script>
    <script>$(document).ready( function () {$('#exemple2').DataTable();} );</script>
    <script>$(document).ready( function () {$('#exemple3').DataTable();} );</script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<title>JCAISSE-BACK END</title>
</head>



<body onLoad="process()">
<?php 
  require('header.php');
?>
    
        
        <div class="container" align="center">
			<div class="row" >
                <?php if($messageSupBoutiqueOK!='') { ?>
                    <div class="alert alert-success ">
                        <strong> <?php  echo $messageSupBoutiqueOK; ?>	</strong> 
                    </div>
                <?php } ?>
                <?php if($messageSupBoutiqueERROR!='') { ?>
                    <div class="alert alert-danger ">
                        <strong> <?php  echo $messageSupBoutiqueERROR; ?>	</strong> 
                    </div>
                <?php } ?>
            </div>				
								
            <ul class="nav nav-tabs"> 
              <li class="active"><a data-toggle="tab" href="#LISTEBOUTIQUE">LISTE DES BOUTIQUES</a></li>
                
              <li class=""><a data-toggle="tab" href="#LISTECUTILISATEUR">LISTE DES UTILISATEURS</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTEBOUTIQUE">	
                <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Nom Boutique</th>
                            <th>Adresse</th>
                            <th>Date de création</th>
                            <th>Type & Catégorie</th>
                            <th>Catalogue</th>
                            <th>Stockage</th>
                            <th>Opération</th>
                            <th>Mode</th>
<!--                            <th>Vitrine</th>-->
                            <th>Activer/Désactiver</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nom Boutique</th>
                            <th>Adresse</th>
                            <th>Date de création</th>
                            <th>Type & Catégorie</th>
                            <th>Catalogue</th>
                            <th>Stockage</th>
                            <th>Opération</th>
                            <th>Mode</th>
<!--                            <th>Vitrine</th>-->
                            <th>Activer/Désactiver</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php 
                        if($_SESSION['profil']=="SuperAdmin"){

                            $sql2="SELECT * FROM `aaa-boutique`";
                            $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                            while ($boutique = mysql_fetch_array($res2)) {
                                  
                                $sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin'";
                                $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                                while ($acces = mysql_fetch_array($res3)) {
                                    $sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur']." LIMIT 1";
                                    $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                                    //int i=1;
                                    while ($utilisateur = mysql_fetch_array($res4)){
                                        //if($utilisateur['back']==1)
                                ?>
                                <tr>
                                    <td> <?php echo  $boutique["labelB"]; ?>  </td>
                                    <td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
                                    <td> <?php echo  $boutique["datecreation"]; ?>  </td>
                                    <td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>

                                    <td> <?php 
                                        $nomtableDesignation=$boutique["nomBoutique"]."-designation";
                                        $sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
                                        $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
                                        if($compteur = mysql_fetch_array($res))
                                            echo  $compteur["nbreRef"]; ?>  </td>

                                    <td> <?php 
                                        $nomtableStock=$boutique["nomBoutique"]."-stock";
                                        $sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant !=0";
                                        $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
                                        if($compteur = mysql_fetch_array($res))
                                            echo  $compteur["nbreStock"]; ?>  </td>


                                    <td>
                                        <a href="#" >
                                            <img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;
                                        <?php if ($boutique["activer"]==0) { ?>

                                            <a href="#">
                                                <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
                                                <?php }else{ ?>

                                                <a href="#">
                                                <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal"   /></a>
                                                
                                             <?php	} ?>
                                             <!--      DEBUT DETAIL BOUTIQUE  -->
                                                <a href="" data-toggle="modal" <?php echo  "data-target=#detailB".$boutique['idBoutique'] ; ?>>
                                                          Detail
                                                        </a>
                                                <div class="modal fade bd-example-modal-lg" <?php echo  "id=detailB".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Details boutique </h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form name="formulaireVersement" method="post" action="boutiques.php">
                                                                  <div class="form-group">
                                                                     <h2>Listes des utilisateurs pour <?php echo  $boutique['labelB'] ; ?> </h2>

                                                                         <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Prénom</th>
                                                                                        <th>Nom</th>
                                                                                        <th>Email</th>
                                                                                        <th>Propriétaire</th>
                                                                                        <th>Gérant</th>
                                                                                        <th>Caissier</th>
                                                                                        <th>Vendeur</th>

                                                                                    </tr>
                                                                                </thead>	
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th>Prénom</th>
                                                                                        <th>Nom</th>
                                                                                        <th>Email</th>
                                                                                        <th>Propriétaire</th>
                                                                                        <th>Gérant</th>
                                                                                        <th>Caissier</th>
                                                                                        <th>Vendeur</th>

                                                                                    </tr>
                                                                                </tfoot>			
                                                                                <tbody> 
                                                                                    <?php
                                                                                        $sql4="SELECT *
                                                                                             FROM `aaa-acces`
                                                                                            WHERE idBoutique =".$boutique['idBoutique'];
                                                                                            $res4 = mysql_query($sql4) or die ("persoonel requête 4".mysql_error());
                                                                                            while ($acces = mysql_fetch_array($res4)) {
                                                                                                $sql5="SELECT *
                                                                                                     FROM `aaa-utilisateur`  
                                                                                                    WHERE idutilisateur =".$acces['idutilisateur'];
                                                                                                    if($res5 = mysql_query($sql5)) {
                                                                                                        while($utilisateur = mysql_fetch_array($res5)) { 
                                                                                                        ?>
                                                                                                        <tr>
                                                                                                            <td> <?php echo  $utilisateur['prenom']; ?>  </td>
                                                                                                            <td> <?php echo  $utilisateur['nom']; ?>  </td>
                                                                                                            <!--<td> <?php echo  $utilisateur['adresse']; ?>  </td>-->
                                                                                                            <td> <?php echo  $utilisateur['email']; ?>  </td>

                                                                                                            <td> <?php if ($acces['proprietaire']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                            <td> <?php if ($acces['gerant']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                            <td> <?php if ($acces['caissier']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                            <td> <?php if ($acces['vendeur']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                            </tr>
                                                                                         <?php   }
                                                                                        }
                                                                                        }
                                                                                    ?>
                                                                                </tbody>			
                                                                           </table>

                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                                                   </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            <!--      FIN DETAIL BOUTIQUE  -->            
                                    </td>
                                    <td>
                                        <?php if ($boutique['enConfiguration']==0) { ?>	
                                                <a href="#">
                                                  <span class="glyphicon glyphicon-cog" align="middle" alt="configuration"  data-toggle="modal" <?php echo  "data-target=#EnConfiguration".$boutique['idBoutique'] ; ?> ></span>&nbsp;
                                                </a>
                                            <?php	}else{ ?>
                                                    <a href="#">
                                                          <span class="glyphicon glyphicon-home" align="middle" alt="configuration"  data-toggle="modal" <?php echo  "data-target=#ApresConfiguration".$boutique['idBoutique'] ; ?> ></span>&nbsp;
                                                        </a>
                                                    <?php	} ?>
                                        <?php if ($boutique['enTest']==0) { ?>	
                                                    <a href="#">
                                                      <span class="glyphicon glyphicon-star-empty" align="middle" alt="test"  data-toggle="modal" <?php echo  "data-target=#EnTest".$boutique['idBoutique'] ; ?> ></span>
                                                    </a>
                                        <?php	}else{ ?>
                                                    <a href="#">
                                                      <span class="glyphicon glyphicon-star" align="middle" alt="configuration"  data-toggle="modal" <?php echo  "data-target=#ApresTest".$boutique['idBoutique'] ; ?> ></span>
                                                    </a>
                                         <?php	} ?>
                                        <!----------------------------------------------------------->				
                                        <div class="modal fade" <?php echo  "id=EnConfiguration".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Configuration </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                                          <div class="form-group">
                                                             <h2>Voulez vous vraiment terminer la configuration de cette boutique</h2>
                                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                                          </div>
                                                          <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                <button type="submit" name="btnActiverConf" class="btn btn-primary">Terminer la Config</button>
                                                           </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" <?php echo  "id=ApresConfiguration".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Configuration</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                                          <div class="form-group">
                                                             <h2>Voulez vous vraiment faire passer cette boutique en mode configuration ? </h2>
                                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                                          </div>
                                                          <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                <button type="submit" name="btnDesactiverConf" class="btn btn-primary">Passer en mode config</button>
                                                           </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    <!----------------------------------------------------------->
                                    <!----------------------------------------------------------->				
                                        <div class="modal fade" <?php echo  "id=EnTest".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Terminer la phase test </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                                          <div class="form-group">
                                                             <h2>Voulez vous vraiment terminer la phase test de cette boutique</h2>
                                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                                          </div>
                                                          <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                <button type="submit" name="btnTerminerTest" class="btn btn-primary">Terminer la phase test</button>
                                                           </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" <?php echo  "id=ApresTest".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Activer la phase test</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment passer cette boutique en phase test ? </h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnDesactiverTest" class="btn btn-primary">Passer en phase test</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                                    <!----------------------------------------------------------->
                                         
                                    </td>
<!--
                                    <td>
                                    <?php if ($boutique["vitrine"]==0) { ?>
                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#ActiverVitrine".$boutique['idBoutique'] ; ?> >
                                                    Activer</button>

                                                    <?php 
                                                } else { ?>
                                                    <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverVitrine".$boutique['idBoutique'] ; ?> >
                                                    Desactiver</button>
                                                <?php }
                                                 ?>
                                    </td>
-->

                                    <td>
                                    <?php if ($boutique["activer"]==0) { ?>
                                                    <button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
                                                    Activer</button>

                                                    <?php 
                                                } else { ?>
                                                    <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
                                                    Desactiver</button>
                                                <?php }
                                                 ?>
                                    </td>
                                </tr>

        	
        <!----------------------------------------------------------->
                        <div class="modal fade" <?php echo  "id=Activer".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment activer cette boutique</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" <?php echo  "id=Desactiver".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment desactiver cette boutique</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
        <!----------------------------------------------------------->	
        <!----------------------------------------------------------->	
                        <div class="modal fade" <?php echo  "id=ActiverVitrine".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment activer cette Vitrine</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >  
                                             <?php echo '<input type="hidden" name="nomBoutique"  value="'. htmlspecialchars($boutique['nomBoutique']).'"  />' ; ?>
                                            <?php echo  ' <input type="hidden" name="adresse" value="'. htmlspecialchars($boutique['adresseBoutique']).'" > ' ; ?> 
                                             <input type="hidden" name="type" <?php echo  "value=". htmlspecialchars($boutique['type'])."" ; ?> >  
                                             <input type="hidden" name="categorie" <?php echo  "value=". htmlspecialchars($boutique['categorie'])."" ; ?> >  
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnActiverVitrine" class="btn btn-primary">Activer</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" <?php echo  "id=DesactiverVitrine".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment desactiver cette Vitrine</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> > 
                                              <input type="hidden" name="nomBoutique" <?php echo  "value=".htmlspecialchars($boutique['nomBoutique'])."" ; ?> >  
                                             <input type="hidden" name="adresse" <?php echo  "value=". htmlspecialchars($boutique['adresseBoutique'])."" ; ?> >  
                                             <input type="hidden" name="type" <?php echo  "value=". htmlspecialchars($boutique['type'])."" ; ?> >  
                                             <input type="hidden" name="categorie" <?php echo  "value=". htmlspecialchars($boutique['categorie'])."" ; ?> >  
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnDesactiverVitrine" class="btn btn-primary">Desactiver</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>


        <!----------------------------------------------------------->
            <div <?php echo  "id=imgmodifierBoutique".$boutique['idBoutique']."" ; ?>   class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Formulaire pour modifier une boutique</h4>
                        </div>
                        <div class="modal-body">
                                    <form name="formulairePersonnel" method="post" action="boutiques.php">

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" required /> '; ?>

                                              <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
                                              <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" required />';?>

                                              <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                            <div class="col-sm-6">
                                                <select name="type" id="type"> <?php
                                                    $sql10="SELECT * FROM `aaa-typeboutique`";
                                                    $res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
                                                    while($ligne = mysql_fetch_row($res10)) {

                                                            if ($ligne[1]==$boutique['type']) {
                                                                    echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }else {
                                                                // code...
                                                                    echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }
                                                        } ?>
                                                </select>
                                                <input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
                                                <div class="col-sm-6">
                                                  <select name="categorie" id="categorie"> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        $res11=mysql_query($sql11);
                                                        while($ligne2 = mysql_fetch_row($res11)) {
                                                            if ($ligne2[1]==$boutique['categorie']) {
                                                                echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }else {
                                                                // code...
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }

                                                            } ?>
                                                    </select>

                                                    <input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />

                                                 </div>
                                          </div>

                                            <div class="modal-footer">
                                            <input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
                                           </div>
                                    </form>

                                </div>
                    </div>
                </div>
            </div>
        <!----------------------------------------------------------->
            <div <?php echo  "id=imgsuprimerBoutique".$boutique['idBoutique']."" ; ?>  class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" class="formulaire2" name="formulaire2" method="post" action="boutiques.php">
                                <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" disabled="" /> '; ?>

                                              

                                               <?php echo  '<input type="hidden" name="nomBInitial" value="'.htmlentities($boutique['labelB']).'"  />'; ?>
                                               <?php echo  '<input type="hidden" name="nomBoutique" value="'.$boutique['nomBoutique'].'" />'; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" disabled="" />';?>

                                              <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                            <div class="col-sm-6">
                                                <select name="type" id="type" disabled=""> <?php
                                                    $sql10="SELECT * FROM `aaa-typeboutique`";
                                                    $res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
                                                    while($ligne = mysql_fetch_row($res10)) {

                                                            if ($ligne[1]==$boutique['type']) {
                                                                    echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }else {
                                                                // code...
                                                                    echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }
                                                        } ?>
                                                </select>
                                                <input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
                                                <div class="col-sm-6">
                                                  <select name="categorie" id="categorie" disabled=""> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        $res11=mysql_query($sql11);
                                                        while($ligne2 = mysql_fetch_row($res11)) {
                                                            if ($ligne2[1]==$boutique['categorie']) {
                                                                echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }else {
                                                                // code...
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }

                                                            } ?>
                                                    </select>

                                                    <input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
                                                 </div>
                                          </div>

                                <div class="modal-footer row">
                                <input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
                                    <button type="button" class="btn btn-default" >Annuler</button>
                                    <button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!----------------------------------------------------------->






                                    <?php 	}
                                    }
                                }


                        }else if($_SESSION['profil']=="Admin"){

                        $sql4="SELECT * FROM `aaa-utilisateur` where idadmin=".$_SESSION['iduserBack'];

                        $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                        while ($utilisateur = mysql_fetch_array($res4)){ 
                        $sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$utilisateur["matricule"]."' OR Accompagnateur='".$_SESSION['matricule']."'";
                            $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                            while ($boutique = mysql_fetch_array($res2)) { 
                                $sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
                                $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                                while ($acces = mysql_fetch_array($res3)) {
                                    $sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
                                    $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                                    while ($utilisateur = mysql_fetch_array($res4)){  
                                        //if($utilisateur['back']==1)
                                ?>
                                <tr>
                                    <td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
                                    <td> <?php echo  $boutique["labelB"]; ?>  </td>
                                    <td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
                                    <td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
                                    <td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
                                    <td> <?php echo  $boutique["datecreation"]; ?>  </td>
                                    <?php if ($boutique["activer"]==1){ ?> 
                                                <td> Activer </td>
                                    <?php }else{ ?> 			
                                                <td> Désactiver </td>
                                    <?php } ?> 
                                    <td>
                                        <a href="#" >
                                            <img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
                                        <?php if ($boutique['activer']==0) { ?>
                                        <a href="#">
                                            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
                                            <?php }else{ ?>

                                            <a href="#">
                                            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
                                        <?php	} ?>

                                        
                                    </td>


                                    <?php if ($boutique['activer']==0) { ?>
                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
                                                    Activer</button>
                                                    </td>
                                                    <?php 
                                                } else { ?>
                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
                                                    Desactiver</button></td>
                                                <?php }
                                                 ?>

                                </tr>
                        <div class="modal fade" <?php echo  "id=Activer".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment activer cette boutique</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" <?php echo  "id=Desactiver".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment desactiver cette boutique</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
        <!----------------------------------------------------------->




        <!----------------------------------------------------------->
            <div <?php echo  "id=imgmodifierBoutique".$boutique['idBoutique']."" ; ?>   class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Formulaire pour modifier une boutique</h4>
                        </div>
                        <div class="modal-body">
                                    <form name="formulairePersonnel" method="post" action="boutiques.php">

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" required /> '; ?>

                                              <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
                                              <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" required />';?>

                                              <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                            <div class="col-sm-6">
                                                <select name="type" id="type"> <?php
                                                    $sql10="SELECT * FROM `aaa-typeboutique`";
                                                    $res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
                                                    while($ligne = mysql_fetch_row($res10)) {

                                                            if ($ligne[1]==$boutique['type']) {
                                                                    echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }else {
                                                                // code...
                                                                    echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }
                                                        } ?>
                                                </select>
                                                <input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
                                                <div class="col-sm-6">
                                                  <select name="categorie" id="categorie"> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        $res11=mysql_query($sql11);
                                                        while($ligne2 = mysql_fetch_row($res11)) {
                                                            if ($ligne2[1]==$boutique['categorie']) {
                                                                echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }else {
                                                                // code...
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }

                                                            } ?>
                                                    </select>

                                                    <input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />

                                                 </div>
                                          </div>

                                            <div class="modal-footer">
                                            <input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
                                           </div>
                                    </form>

                                </div>
                    </div>
                </div>
            </div>
        <!----------------------------------------------------------->
            <div <?php echo  "id=imgsuprimerBoutique".$boutique['idBoutique']."" ; ?>  class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" class="formulaire2" name="formulaire2" method="post" action="boutiques.php">
                                <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" disabled="" /> '; ?>

                                              
                                                
                                              <?php echo  '<input type="hidden" name="nomBoutique" value="'.$boutique['nomBoutique'].'"/>' ; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" disabled="" />';?>

                                              <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                            <div class="col-sm-6">
                                                <select name="type" id="type" disabled=""> <?php
                                                    $sql10="SELECT * FROM `aaa-typeboutique`";
                                                    $res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
                                                    while($ligne = mysql_fetch_row($res10)) {

                                                            if ($ligne[1]==$boutique['type']) {
                                                                    echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }else {
                                                                // code...
                                                                    echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }
                                                        } ?>
                                                </select>
                                                <input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
                                                <div class="col-sm-6">
                                                  <select name="categorie" id="categorie" disabled=""> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        $res11=mysql_query($sql11);
                                                        while($ligne2 = mysql_fetch_row($res11)) {
                                                            if ($ligne2[1]==$boutique['categorie']) {
                                                                echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }else {
                                                                // code...
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }

                                                            } ?>
                                                    </select>

                                                    <input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
                                                 </div>
                                          </div>

                                <div class="modal-footer row">
                                <input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
                                    <button type="button" class="btn btn-default" >Annuler</button>
                                    <button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!----------------------------------------------------------->






                        <?php 	}}}}




                        }else if ($_SESSION['profil']=="Accompagnateur"){


                        $sql2="SELECT * FROM `aaa-boutique` where Accompagnateur='".$_SESSION['matricule']."'";
                            $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                            while ($boutique = mysql_fetch_array($res2)) { 
                                $sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']."&& profil='Admin'";
                                $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                                while ($acces = mysql_fetch_array($res3)) {
                                    $sql4="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$acces['idutilisateur'];
                                    $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                                    while ($utilisateur = mysql_fetch_array($res4)){  
                                        //if($utilisateur['back']==1)
                                ?>
                                <tr>
                                    <td> <?php echo  $boutique["nomBoutique"]; ?>  </td>
                                    <td> <?php echo  $boutique["labelB"]; ?>  </td>
                                    <td> <?php echo  $boutique["type"].' / '.$boutique["categorie"]; ?>  </td>
                                    <td> <?php echo  $boutique["adresseBoutique"]; ?>  </td>
                                    <td> <?php echo  $utilisateur["prenom"].' '.$utilisateur["nom"].' ('.$utilisateur["telPortable"].')'; ?>  </td>
                                    <td> <?php echo  $boutique["datecreation"]; ?>  </td>
                                    <td>
                                        <a href="#" >
                                            <img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierBoutique".$boutique['idBoutique'] ; ?> /></a>&nbsp;&nbsp;
                                        <?php if ($boutique['activer']==0) { ?>
                                        <a href="#">
                                            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerBoutique".$boutique['idBoutique'] ; ?> /></a>
                                            <?php }else{ ?>

                                            <a href="#">
                                            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>
                                        <?php	} ?>

                                    </td>


                                    <?php if ($boutique['activer']==0) { ?>
                                                    <td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$boutique['idBoutique'] ; ?> >
                                                    Activer</button>
                                                    </td>
                                                    <?php 
                                                } else { ?>
                                                    <td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$boutique['idBoutique'] ; ?> >
                                                    Desactiver</button></td>
                                                <?php }
                                                 ?>

                                </tr>
                        <div class="modal fade" <?php echo  "id=Activer".$boutique['idBoutique'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Activation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment activer cette boutique</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" <?php echo  "id=Desactiver".$boutique['idBoutique']; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Desactivation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" action="boutiques.php">
                                          <div class="form-group">
                                             <h2>Voulez vous vraiment desactiver cette boutique</h2>
                                             <input type="hidden" name="idboutique" <?php echo  "value=". htmlspecialchars($boutique['idBoutique'])."" ; ?> >
                                          </div>
                                          <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
                                           </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
        <!----------------------------------------------------------->




        <!----------------------------------------------------------->
            <div <?php echo  "id=imgmodifierBoutique".$boutique['idBoutique']."" ; ?>   class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Formulaire pour modifier une boutique</h4>
                        </div>
                        <div class="modal-body">
                                    <form name="formulairePersonnel" method="post" action="boutiques.php">

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" required /> '; ?>

                                              <input type="hidden" name="nomBInitial" <?php echo  "value=".$boutique['labelB']."" ; ?> />
                                              <input type="hidden" name="nomBoutique" <?php echo  "value=".$boutique['nomBoutique']."" ; ?> />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" required />';?>

                                              <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                            <div class="col-sm-6">
                                                <select name="type" id="type"> <?php
                                                    $sql10="SELECT * FROM `aaa-typeboutique`";
                                                    $res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
                                                    while($ligne = mysql_fetch_row($res10)) {

                                                            if ($ligne[1]==$boutique['type']) {
                                                                    echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }else {
                                                                // code...
                                                                    echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }
                                                        } ?>
                                                </select>
                                                <input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
                                                <div class="col-sm-6">
                                                  <select name="categorie" id="categorie"> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        $res11=mysql_query($sql11);
                                                        while($ligne2 = mysql_fetch_row($res11)) {
                                                            if ($ligne2[1]==$boutique['categorie']) {
                                                                echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }else {
                                                                // code...
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }

                                                            } ?>
                                                    </select>

                                                    <input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />

                                                 </div>
                                          </div>

                                            <div class="modal-footer">
                                            <input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
                                           </div>
                                    </form>

                                </div>
                    </div>
                </div>
            </div>
        <!----------------------------------------------------------->
            <div <?php echo  "id=imgsuprimerBoutique".$boutique['idBoutique']."" ; ?>  class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Formulaire pour supprimer une boutique</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" class="formulaire2" name="formulaire2" method="post" action="boutiques.php">
                                <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">NOM <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="labelB" class="form-control" value="'.$boutique['labelB'].'" disabled="" /> '; ?>

                                               <?php echo  '<input type="hidden" name="nomBInitial" value="'.$boutique['labelB'].'"  />'; ?>
                                               <?php echo  '<input type="hidden" name="nomBoutique" value="'.$boutique['nomBoutique'].'" />'; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE <font color="red">*</font></label>
                                            <div class="col-sm-5">
                                              <?php echo  '<input type="text" name="adresseB" class="form-control"   value="'. $boutique['adresseBoutique'].'" disabled="" />';?>

                                              <input type="hidden" name="adresseBInitial" <?php echo  "value=".$boutique['adresseBoutique']."" ; ?> />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                            <div class="col-sm-6">
                                                <select name="type" id="type" disabled=""> <?php
                                                    $sql10="SELECT * FROM `aaa-typeboutique`";
                                                    $res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
                                                    while($ligne = mysql_fetch_row($res10)) {

                                                            if ($ligne[1]==$boutique['type']) {
                                                                    echo'<option selected value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }else {
                                                                // code...
                                                                    echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';
                                                            }
                                                        } ?>
                                                </select>
                                                <input type="hidden" name="typeBInitial" <?php echo  "value=".$boutique['type']."" ; ?> />
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE<font color="red">*</font></label>
                                                <div class="col-sm-6">
                                                  <select name="categorie" id="categorie" disabled=""> <?php
                                                        $sql11="SELECT * FROM `aaa-categorie`";
                                                        $res11=mysql_query($sql11);
                                                        while($ligne2 = mysql_fetch_row($res11)) {
                                                            if ($ligne2[1]==$boutique['categorie']) {
                                                                echo'<option selected value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }else {
                                                                // code...
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                                            }

                                                            } ?>
                                                    </select>

                                                    <input type="hidden" name="categorieBInitial" <?php echo  "value=".$boutique['categorie']."" ; ?> />
                                                 </div>
                                          </div>

                                <div class="modal-footer row">
                                <input type="hidden" name="idBoutique" <?php echo  "value=".$boutique['idBoutique']."" ; ?> />
                                    <button type="button" class="btn btn-default" >Annuler</button>
                                    <button type="submit" name="btnSupprimerBoutique" class="btn btn-primary">Supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!----------------------------------------------------------->






                        <?php 	}}}}

                        ?>

                    </tbody>
                </table> 
            </div>
            <div class="tab-pane fade  " id="LISTECUTILISATEUR" >
                <div class="table-responsive">
                     <table id="exemple3" class="display" width="100%" align="left" border="1">
                            <thead>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th> </th>
                                    <!--
                                    <th>Propriétaire</th>
                                    <th>Gérant</th>
                                    <th>Caissier</th>
                                    <th>Vendeur</th>
                                    <th>Boutique</th>
                                    -->

                                </tr>
                            </thead>	
                            <tfoot>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th> </th>
                                    <!--
                                    <th>Propriétaire</th>
                                    <th>Gérant</th>
                                    <th>Caissier</th>
                                    <th>Vendeur</th>
                                    <th>Boutique</th>
                                    -->
                                </tr>
                            </tfoot>			
                            <tbody>
                                 <?php 
                            if($_SESSION['profil']=="SuperAdmin"){

                                  
                                /*$sql4="SELECT DISTINCT idutilisateur,nom,prenom,email  FROM `aaa-utilisateur` ";
                                 $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                                        //int i=1;
                                 while ($utilisateur = mysql_fetch_array($res4)){
                                     //$sql3="SELECT * FROM `aaa-acces` where idBoutique=".$boutique['idBoutique']." and profil='Admin'";
                                    $sql3="SELECT * FROM `aaa-acces` where  idBoutique!=0 and  idutilisateur=".$utilisateur['idutilisateur']."";
                                    $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                                     while ($acces = mysql_fetch_array($res3)) { 
                                      ?>
                                         <tr>
                                            <td> <?php echo  $utilisateur['idutilisateur']."-"; ?><?php echo  $utilisateur['prenom']; ?>  </td>
                                            <td> <?php echo  $utilisateur['nom']; ?>  </td>
                                            <!--<td> <?php echo  $utilisateur['adresse']; ?>  </td>-->
                                            <td> <?php echo  $utilisateur['email']; ?>  </td>
                                            <td> <?php if ($acces['proprietaire']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                            <td> <?php if ($acces['gerant']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                            <td> <?php if ($acces['caissier']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                            <td> <?php if ($acces['vendeur']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                             <!--  <td> <?php  echo $boutique['labelB']; ?>  </td>-->
                                            <td> 
                                                 <?php	   
                                                 
                                                     ///
                                                     $sql2="SELECT * FROM `aaa-boutique` where idBoutique=".$acces['idBoutique']." ";
                                                         $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                                                         while ($boutique = mysql_fetch_array($res2)) { 
                                                               echo $boutique['labelB'];  
                                                              }
                                                       
                                                ?>	

                                             </td>
                                          </tr>
                                         
                                        <?php	    
                                      }
                                    } */
                                
                                //$sql4="SELECT DISTINCT u.idutilisateur,nom,prenom,email,idBoutique,proprietaire,gerant,caissier,vendeur from `aaa-utilisateur` u INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur where a.idBoutique != 0  and (proprietaire=1 or gerant=1 or caissier=1 or vendeur=1) "; 
                                $sql4="SELECT DISTINCT u.idutilisateur from `aaa-utilisateur` u INNER JOIN `aaa-acces` a ON a.idutilisateur =u.idutilisateur where a.idBoutique != 0  and (proprietaire=1 or gerant=1 or caissier=1 or vendeur=1) ";
                                 $res4 = mysql_query($sql4) or die ("utilisateur requête 4".mysql_error());
                                 while ($utilisateur = mysql_fetch_array($res4)){
                                     
                                     $sql5="SELECT * from `aaa-utilisateur` where idutilisateur =".$utilisateur['idutilisateur']."";
                                     $res5 = mysql_query($sql5) or die ("utilisateur requête 5".mysql_error());
                                      while ($user = mysql_fetch_array($res5)){
                                           ?>
                                         <tr>
                                             <!--  <td> <?php echo  $user['idutilisateur']."-"; ?><?php echo  $user['prenom']; ?>  </td>-->
                                            <td> <?php echo  $user['prenom']; ?>  </td>
                                            <td> <?php echo  $user['nom']; ?>  </td>
                                            <td> <?php echo  $user['email']; ?>  </td>
                                            <td> 
                                            <!--  DEBUT DETAIL BOUTIQUE -->
                                            <a href="" data-toggle="modal" <?php echo  "data-target=#detailU".$user['idutilisateur'] ; ?>>
                                                      Detail
                                                    </a>
                                            <div class="modal fade bd-example-modal-lg" <?php echo  "id=detailU".$user['idutilisateur'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Details User </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                              <div class="form-group">
                                                                 <h2>Listes des boutiques pour l'utilisateurs <?php echo  $user['prenom'] ; ?> </h2>

                                                                     <table  class="table  table-bordered table-striped table-condensed" border="1" class="table table-bordered table-striped" id="userTable">
                                                                           
                                                                                <tr>
                                                                                    <th>Nom boutique</th>
                                                                                    <th>Propriétaire</th>
                                                                                    <th>Gérant</th>
                                                                                    <th>Caissier</th>
                                                                                    <th>Vendeur</th>

                                                                                </tr>
                                                                            
                                                                           	
                                                                           
                                                                                <?php	
                                          
                                                                                     $sql2="SELECT * from `aaa-boutique` b INNER JOIN `aaa-acces` a ON a.idBoutique =b.idBoutique where a.idutilisateur =".$user['idutilisateur']." ";
                                                                                         $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
                                                                                         while ($boutique = mysql_fetch_array($res2)) { ?>
                                                                                             <tr>  <td> <?php echo $boutique['labelB']; ?>  </td>  
                                                                                                 <td> <?php if ($boutique['proprietaire']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                 <td> <?php if ($boutique['gerant']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                    <td> <?php if ($boutique['caissier']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td>
                                                                                                    <td> <?php if ($boutique['vendeur']) echo '<b>OUI</b>'; else echo 'NON'; ?>  </td> 
                                                                                            </tr> 
                                                                                        <?php
                                                                                        } 
                                                                                ?>	
                                                                           			
                                                                       </table>

                                                              </div>
                                                              <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                                                               </div>
                                                            
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!--  FIN DETAIL BOUTIQUE   -->
                                             </td>
                                          </tr>
                                         
                                        <?php	
                                      }
                                     
                                         
                                 }
                                
                            }else {
                                        echo "Vide.".mysql_error();
                             }


                                ?>		
                            </tbody>			
                        </table>
                </div>
             </div>
	   </div>
	</div>


</body>
</html> <?php
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="index.php"</script>';
echo'</head>';
echo'</html>';
}
?>
