<?php 
/*
Résumé : Ce code permet d'inserer une ligne (une entrée ou une sortie) dans le journal d'une boutique.
Commentaire : Ce code contient un formulaire récupérant l'ensemble des informations (typeligne, designation,prix unitaire,quantite,remise,prix total) sur une de journal de la boutique.
Pour facilité le remplissage de ce formulaire ce code est associé avec du code AJAX (JavaScript:verificationdesignation.js et PHP:verificationdesig.php), 
qui vérifie le champ désignation si il est vide et si il existe ou il est absent de la base de données et qui compléte les champs : prix unitaire et prix total.
Il insère ces informations dans la table commençant par le nom de la boutique et suivi de : -lignepj. Pour cela ce code à partir de la date courrante regarde si pour cette ligne y'a une page déja créer sinon il le crée et regarde aussi si pour cette page de la date courrante si le mois et l'année ya un journal déjà créer sinon il le crée. 
Ainsi de façon automatique le code pour une ligne donnée le relie avec une page et un journal si ils existent. sinon le les créent avant de les associer avec cette nouvelle ligne.
Ce code permet d'afficher la liste des lignes (des entrées ou des sorties) du journal d'une boutique pour la date courrante et de modifier et de supprimer une ligne de la liste des lignes du journal.
Version : 2.0
see also : modifierLigne.php et supprimerLigne.php
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/
session_start();
if($_SESSION['iduser']){
	if (!$_SESSION['nomB']){
	  echo'<!DOCTYPE html>';
    echo'<html>';
    echo'<head>';
    echo'<script language="JavaScript">document.location="creationBoutique.php"</script>';
    echo'</head>';
    echo'</html>';
	}
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";

/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente				=@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;


       
$sql="select * from `".$nomtableStock."` where designation='".$designation."'";
$res=mysql_query($sql);
$nombreTotal=0;
if(mysql_num_rows($res)){
   if($tab=mysql_fetch_array($res))
  	 if($unitevente=="article")
  	   $nbreArticleUniteStock=1;
  	 else
       $nbreArticleUniteStock=$tab["nbreArticleUniteStock"];
  		 
       $nombreTotal=$nbreArticleUniteStock*$quantite;
}

$sql="select * from `".$nomtableTotalStock."` where designation='".$designation."'";
$res=mysql_query($sql);
$newquantiteEnStocke=0;
if(mysql_num_rows($res)){
   if($tab=mysql_fetch_array($res))
     $quantiteEnStocke=$tab["quantiteEnStocke"];
     $newquantiteEnStocke=$quantiteEnStocke-$nombreTotal;
}

if(!$annuler){
if(!$modifier){
  if($designation){
    if($newquantiteEnStocke>=0){
    $sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;
    $res1=mysql_query($sql1);
    if(mysql_num_rows($res1)){
    	 $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";
       $res2=mysql_query($sql2);
       if(mysql_num_rows($res2)){
    	     $sql="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
    			 //echo $sql;
           $res=@mysql_query($sql) or die ("insertion ligne journal impossible-0");
    	 }
    	 else{
    	     $sql1="insert into `".$nomtablePage."` (datepage,tentreesp,tsortiesp) values('".$dateString."',0,0)";
           $res1=@mysql_query($sql1) or die ("insertion page journal impossible-1");
    			 
    	     $sql2="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
    		   $res2=@mysql_query($sql2) or die ("insertion ligne journal impossible-1");
    	 }
    }
    else{
       $sql1="insert into `".$nomtableJournal."` (mois,annee) values(".$mois.",".$annee.")";
       $res1=@mysql_query($sql1) or die ("insertion journal impossible-2");
    	 
       $sql2="insert into `".$nomtablePage."` (datepage,tentreesp,tsortiesp) values('".$dateString."',0,0)";
       $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2");
    	 
       $sql3="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
       $res3=@mysql_query($sql3) or die ("insertion ligne journal impossible-2");
    }
    $rep=0; $qtc=0;
    if($newquantiteEnStocke>=0){
    	$sql="select * from `".$nomtableStock."` where designation='".$designation."'";
      $res=mysql_query($sql);
      if(mysql_num_rows($res))
         while(($tab=mysql_fetch_array($res))&&($rep==0)){
      	   $qtc=$tab["quantiteStockCourant"];
        	 if($nombreTotal<$qtc){
        		 $qtc=$qtc-$nombreTotal;
      			 $rep=1;
      			 $sql1="update `".$nomtableStock."` set quantiteStockCourant=".$qtc." where idStock=".$tab["idStock"];
             //echo $sql1;
    				 $res1=@mysql_query($sql1)or die ("modification impossible");
      			 }
      		 else if($nombreTotal==$qtc){
      		   $qtc=0;
      		 	 $rep=1;
      			 $sql2="update `".$nomtableStock."` set quantiteStockCourant=".$qtc.",dateFinStock='".$dateString."' where idStock=".$tab["idStock"];
             //echo $sql2;
    				 $res2=@mysql_query($sql2)or die ("modification impossible");			 
      		 }
        	 else if($nombreTotal>$qtc){
      		   $qtc=0;
        		 $nombreTotal=$nombreTotal-$qtc;
      			 $sql3="update `".$nomtableStock."` set quantiteStockCourant=".$qtc.",dateFinStock='".$dateString."' where idStock=".$tab["idStock"];
             //echo $sql3;
    				 $res3=@mysql_query($sql3)or die ("modification impossible");			 
      		 }
        }
    }

    /*
    echo $quantite;
    echo $unitevente;
    echo $designation;
    echo $nbreArticleUniteStock;
    echo $nombreTotal;
    */

      if($newquantiteEnStocke>=0){
          $sql="update `".$nomtableTotalStock."` set quantiteEnStocke=".$newquantiteEnStocke." where designation='".$designation."'";
          $res=@mysql_query($sql)or die ("modification impossible");
      }
      if($newquantiteEnStocke==0){
        $sql="DELETE FROM `".$nomtableTotalStock."` where designation='".$designation."'";
        $res=@mysql_query($sql) or die ("suppression impossible");
        }
    }else{
      echo'<script language="JavaScript">alert("Insertion impossible : Stock faible");</script>';
      }
 }
}
else{
  if($numligne){
  $sql="update `".$nomtableLigne."` set typeligne='".$type."',designation='".$designation."',unitevente='".$unitevente."',prixunitevente='".$prix."',quantite='".$quantite."',remise='".$remise."',prixtotal=".$prixtotal." where numligne=".$numligne;
  $res=@mysql_query($sql)or die ("modification impossible");
  }
}
if (isset($_POST['btnSavePagnetVente'])) {
    $date = new DateTime();
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $dateString2=$jour.'-'.$mois.'-'.$annee;
    $sql6="insert into `".$nomtablePagnet."` (datepagej) values('".$dateString2."')";
      $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );
  }
  if (isset($_POST['btnImprimerFacture'])) {
    
    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".$_POST['idPagnet'];
      $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
  }
/**********************/?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
   <link rel="stylesheet" href="css/bootstrap.css"> 
   <script src="js/jquery-3.1.1.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
   <script type="text/javascript" src="js/script.js" ></script>
   <link rel="stylesheet" type="text/css" href="style.css">
   <title> SOLUTIONS</title>
</head>
<body onload="process()"><header>
<?php require('header.php');

echo'<section><div class="container" align="center">';

/*********************************************************************
echo'<form class="formulaire2" id="ajouterProdForm" name="formulaire2" method="post" >
        <input type="text" class="inputbasic" name="codeBarreLigne" id="codeBarreLigne" autofocus="" >
        <div id="reponseS"></div>
       </form>';*/
/**********************************************************************/?>
  <form name="formulairePagnet" method="post" >
      <button type="submit" class="btn btn-success" name="btnSavePagnetVente">
                      <i class="glyphicon glyphicon-plus"></i>Ajouter un Pagnet
      </button><br><br>
  </form>
<div id="monaccordeon" class="panel-group ">
              <?php     
            /**/    $sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=0 ORDER BY idPagnet DESC";
                $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
                  while ($pagnet = mysql_fetch_array($res2)) { ?>
                    <div class="panel panel-info">
                      <div class="panel-heading">
                        <h3 class="panel-title ">
                        <a class="accordion-toggle " <?php echo  "href=#item".$pagnet['idPagnet']."" ; ?>
                          dataparent="#monaccordeon"  data-toggle="collapse"   
                          > Pagnet <?php echo $pagnet['idPagnet']; ?>
                          
                        </a>  <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
                           <span class="spanTotal">Total pagnet: <?php echo $pagnet['totalp']; ?> </span>
                        </h3>
                      </div>
                      <div   <?php echo  "id=item".$pagnet['idPagnet']."" ; ?> 
                      <?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php } 
                           else { ?> class="panel-collapse collapse " <?php } ?>  >
                        <div class="panel-body" >  
                          <?php 
                            if ($pagnet['verrouiller']==0) {  ?>
                              <form  class="form-inline pull-left" id="ajouterProdForm" method="post" action="ajax/vendreLigneAjax.php">
                                <input type="text" class="inputbasic" name="codeBarreLigne" id="codeBarreLigne" autofocus="" >
                                <input type="hidden" name="idPagnet" id="idPagnet" 
                                 <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                               <button type="submit" name="btnEnregistrerCodeBarreAjax" 
                                id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs" ><span class="glyphicon glyphicon-plus" ></span>
                              Ajouter</button><div id="reponseS"></div>
                              </form>
                              <?php
                            }
                           ?>
                          <form class="form-inline pull-right" id="factForm" method="post"     action="insertionLigne.php" >
                            <input type="hidden" name="idPagnet" id="idPagnet" 
                               <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
                               <button type="submit"  name="btnImprimerFacture"  class="btn btn-success pull-right" data-toggle="modal" >
                                  <span class="glyphicon glyphicon-lock"></span>Facture
                               </button>
                          </form>
                          <table class="table ">
                            <thead>
                              <tr>
                                <th>Designation</th>
                                <th>Unité vente</th>
                                <th>Prix unite vente</th>
                                <th>Quantité</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php /**/ $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']."";
                                              $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                                              while ($ligne = mysql_fetch_array($res8)) {?>
                                                  <tr>
                                                     <td><?php echo $ligne['designation']; ?></td>
                                                    <td><?php echo $ligne['unitevente']; ?></td>
                                                    <td><?php echo $ligne['prixunitevente']; ?></td>
                                                    <td><?php echo $ligne['quantite']; ?></td>
                                                    <td><button class="btn btn-warning">Retour</button></td>
                                                  </tr>
                                              <?php  }  ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

              <?php   } ?>
                  
          </div>

<?php /*****************************/
$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;
$sql='SELECT * from  `'.$nomtableLigne.'` where datepage="'.$dateString.'" and idPagnet=0 order by numligne desc';
$res=mysql_query($sql) or die ("produit non ajouter a la vente".mysql_error());
echo'<div class="container"><ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">JOURNAL DE CAISSE</a></li>';
echo'</ul><div class="tab-content">';

if(mysql_num_rows($res)){
echo'<table class="tableau2" width="80%" align="left" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th><th></th>';
while($tab=mysql_fetch_array($res)){
echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["quantite"].'</td><td align="right">'.$tab["unitevente"].'</td><td align="right">'.$tab["prixunitevente"].'</td><td align="right">'.$tab["remise"].'</td><td align="right">'.$tab["prixtotal"].'</td><td>'.$tab["typeligne"].'</td><td><a href="modifierLigne.php?numligne='.$tab["numligne"].'"><img src="images/edit.png" align="middle" alt="modifier"/></a>&nbsp;&nbsp;<a href="supprimerLigne.php?numligne='.$tab["numligne"].'">'.
'<img src="images/drop.png" align="middle" alt="supprimer"/></a></td></tr>';
if ($tab["typeligne"]=="Entree")
    $somme1+=$tab["prixtotal"];
else
    $somme2+=$tab["prixtotal"];
}
$sommeT=$somme1-$somme2;
echo'<tr bgcolor="#c0c0c0"><td colspan="2" align="right"><b>TOTAL ENTREE='.$somme1.'</b></td><td colspan="3" align="right"><b>TOTAL SORTIE='.$somme2.'</b></td><td colspan="3" align="right"><b>TOTAL ='.$sommeT.'</b></td></tr>';

$sql="update `".$nomtablePage."` set tentreesp=".$somme1.",tsortiesp=".$somme2." where datepage='".$dateString."'";
$res=@mysql_query($sql)or die ("modification impossible");

}else{
echo'<table class="tableau2" width="80%" align="left" border="1"><th>DESIGNATION</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th>';
echo'<tr><td colspan="8">Journal de caisse de la date du '.$dateString.' est pour le moment vide </td></tr>';
}
echo'</table><br /></div>';

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionLigne.php"</script>';
echo'</head>';
echo'</html>';
}
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