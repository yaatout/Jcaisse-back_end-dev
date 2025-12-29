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
$idStock         =@$_POST["idStock"];

$designation      =@htmlentities($_POST["designation"]);
$idDesignation      =@htmlentities($_POST["idDesignation"]);
$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
$prixuniteStock       =@$_POST["prixuniteStock"];
$prixunitaire       =@$_POST["prixunitaire"];
$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];

$insererStock    =@$_POST["insererStock"];
$supprimer    =@$_POST["supprimer"];
$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
$insererDesignation   =@$_POST["insererDesignation"];

/***************/

$idStock2       =@$_GET["idStock"];
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');

$dateString=$jour.'-'.$mois.'-'.$annee;



if(!$annuler){
  if(!$modifier){
      if($insererStock){

          $nombreArticle=0;

          $sql="select * from `".$nomtableDesignation."` where designation='".$designation."'";
          $res=mysql_query($sql);
          if(mysql_num_rows($res))
          	 if($tab=mysql_fetch_array($res))
                 $nombreArticle  = $tab["nbreArticleUniteStock"];
          if ($uniteStock=="article")
            $total=$stock;
          else
            $total=$stock*$nombreArticle;
			
          	$sql1="INSERT INTO `".$nomtableStock."` (idDesignation, designation,quantiteStockinitial,uniteStock,prixuniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES(".$idDesignation.",'".$designation."',".$stock.",'".$uniteStock."',".$prixuniteStock.",".$prixunitaire.",".$nombreArticle.",".$total.",'".$dateString."',".$total.",'".$dateExpiration."')";
            $res1=@mysql_query($sql1) or die ("insertion stock impossible".mysql_error()) ;
            
          	$sql="select * from `".$nomtableTotalStock."` where designation='".$designation."'";
            $res=mysql_query($sql);
            if(mysql_num_rows($res)){
            if($tab=mysql_fetch_array($res)){
          	 $totalstock=$tab["quantiteEnStocke"]+$total;
          	 
          	 $date = new DateTime($tab["dateExpiration"]);
              $annee =$date->format('Y');
              $mois =$date->format('m');
              $jour =$date->format('d');
              $dateString2=$jour.'-'.$mois.'-'.$annee;
          		
          	 $sql="update `".$nomtableTotalStock."` set quantiteEnStocke=".$totalstock.",dateExpiration='".$dateString2."' where designation='".$designation."'";
             $res=@mysql_query($sql)or die ("modification impossible");
          	 }
          	 }else{
          	 $sql1="INSERT INTO `".$nomtableTotalStock."` (`designation`, `quantiteEnStocke`, `dateExpiration`) VALUES('".$designation."',".$total.",'".$dateExpiration."')";
             $res1=@mysql_query($sql1) or die ("insertion stock impossible");
          	 }
        }
   }
  else{
    if($idStock){

      $total=$stock*$nombreArticle;
      $sql="update `".$nomtableStock."` set designation='".$designation."',quantiteStockinitial='".$stock."',uniteStock='".$uniteStock."',prixuniteStock='".$prixuniteStock."',prixunitaire='".$prixunitaire."',nbreArticleUniteStock='".$nombreArticle."',totalArticleStock='".$total."',dateExpiration='".$dateExpiration."' where idStock=".$idStock;
      $res=@mysql_query($sql)or die ("modification impossible");

        $sql2="select totalArticleStock from `".$nomtableStock."` where designation='".$designation."'";
        $res2=mysql_query($sql2);
      	$stocktotalmaj=0;
        if(mysql_num_rows($res2))
      	while($tab2=mysql_fetch_array($res2))
          $stocktotalmaj+=$tab2["totalArticleStock"];

        $sql="update `".$nomtableTotalStock."` set quantiteEnStocke=".$stocktotalmaj." where designation='".$designation."'";
        $res=@mysql_query($sql)or die ("modification impossible");    	 
    }
  }
  if ($supprimer ) {
    $sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock;
    $res=@mysql_query($sql) or die ("suppression impossible");
  }

   if($insererDesignation){ 
      $designation         =@htmlentities($_POST["designation"]);
      $prix                =@$_POST["prix"];
      $prixuniteStock      =@$_POST["prixuniteStock"];
      $classe              =@$_POST["classe"];
      $desig               =@$_POST["desig"];
      $uniteStock          =@htmlentities($_POST["uniteStock"]);
      $prixService         =@$_POST["prixService"];
      $montantFrais        =@$_POST["montantFrais"];
      $nbArticleUniteStock =@$_POST["nbArticleUniteStock"];
      if($classe==0){
        $sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock) values ('".$designation."',0,".$prix.",'".$uniteStock."',".$prixuniteStock.",".$nbArticleUniteStock.")";
        
        $res=@mysql_query($sql) or die ("insertion impossible 1111");
      }else if($classe==1){
        $sql="insert into `".$nomtableDesignation."` (designation,classe,prix) values('".$designation."',1,".$prix.")";
        $res=@mysql_query($sql) or die ("insertion impossible");
      }else if($classe==2){
        $sql="insert into `".$nomtableDesignation."` (designation,classe,prix) values('".$designation."',2,".$prix.")";
        $res=@mysql_query($sql) or die ("insertion impossible");
      }
  }
/*
if (isset($_POST['rechercheS'])) {
  $reponse="<ul><li>aucune donnee trouvé</li></ul>";
 $query=htmlspecialchars($_POST['q']);
 var_dump($query);
  $reqS="SELECT * from ".$nomtableDesignation." where designation LIKE '%".$query."%'";
  $resS=mysql_query($reqS);var_dump($query);
  echo " <script type='text/javascript'> alert('".$reqS."');</script> ";
   if($resS){
      $reponse="<ul>";
        while ($data=mysql_fetch_array($res)) {
          $reponse.="<li>".$data['designation']."</li>";
        }
      $reponse.="</ul>";
   }
  exit($reponse);
}*/
/**********************/


echo'<!DOCTYPE html><html><head>'.
'<meta charset="utf-8">
   <link rel="stylesheet" href="css/bootstrap.css"> 
   <link rel="stylesheet" href="css/datatables.min.css">
   <script src="js/jquery-3.1.1.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
   <script src="js/datatables.min.js"></script>';
   echo'<script>$(document).ready( function () { $("#exemple").DataTable(); } );</script>';
   echo'<script type="text/javascript" src="js/script.js"></script>
   <link rel="stylesheet" type="text/css" href="style.css">
   <title> SOLUTIONS</title></head>
   <body >';
   require('header.php');
   


echo'<div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" id="AjoutStock">
<i class="glyphicon glyphicon-plus"></i>Ajouter un Stock</button>';
//echo'<div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient"><i class="glyphicon glyphicon-plus"></i>Ajouter client</button>';
		
echo'<div class="modal fade" id="AjoutStockModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Formulaire d'ajout de Stock </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0"><form class="formulaire2" id="ajouterStockForm" name="formulaire2" method="post" action="ajouterStock.php">'.
'<tr><td>
DESIGNATION</td><td><input type="text" class="inputbasic" id="designationStock" name="designation" size="35" value="" /><button  type="button" class="btn btn-success "  data-toggle="modal" data-target="#myModal1" data-dismiss="modal">ajouter</button> <span id="missIdD"></span>
<input type="hidden" name="idDesignation" value="" id="idD" required="">
</td></tr><br>
<tr><td>&nbsp; </td><td><div id="reponseS"></div></td></tr>'.
'<tr><td>QUANTITE A STOCKER</td><td><input type="text" class="inputbasic" id="stock" name="stock" size="35" value=""  /></td></tr>'.
'<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></td></tr>'.
'<tr><td>Prix Unitaire</td><td><input type="text" class="inputbasic" id="stock" name="prixunitaire" size="35" value=""  /></td></tr>'.
'<tr><td>Prix par unité de stock</td><td><input type="text" class="inputbasic" id="stock" name="prixuniteStock" size="35" value=""  /></td></tr>'.
'<tr><td>DATE EXPIRATION</td><td><input type="date" class="inputbasic" id="dateExpiration" name="dateExpiration" value = "" size="35" /></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="insererStock" id="envoyerStock" value="ENVOYER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table><br />'.
'</div></div></div></div></div>';

/*****************************/
echo ' 
    
    <div id="myModal1" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Type de designation</h4>
                    </div>
                    <div class="modal-body">
                        <button type="button" class="btn btn-success "  data-toggle="modal" data-target="#produitModal" data-dismiss="modal">Produit </button>
                        <button type="button" data-toggle="modal" data-target="#serviceModal" data-dismiss="modal" class="btn btn-success " >Service </button>
                        <button type="button" data-toggle="modal" data-target="#fraisModal" data-dismiss="modal" class="btn btn-success " >Frais </button>
                    </div>

                </div>

            </div>
    </div>
    <div id="produitModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout designation de produit</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="ajouterStock.php">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" >
                            DESIGNATION<input type="text" class="inputbasic" placeholder="Désignation Entrée/Sortie" id="designation" name="designation" size="35" value="" />
                            <div>PRIX UNITAIRE</div><input type="text" class="inputbasic" placeholder="Prix Unitaire" id="prix" name="prix" size="35" value="" />
                          <div>UNITE STOCK</div><input type="text" class="inputbasic" placeholder="Unité du stock" id="uniteStock" name="uniteStock" size="35" value="" />
                          <div>PRIX UNITE STOCK</div><input type="text" class="inputbasic" placeholder="Prix Unite Stock" id="prixuniteStock" name="prixuniteStock" size="35" value="" />
                          <div>NOMBRE ARTICLE(S) PAR UNITE STOCK</div><input type="text" class="inputbasic" placeholder="Nombre article(s)  par unité stock" id="nbArticleUniteStock" name="nbArticleUniteStock" size="35" value="" />
                           <div class="modal-footer row">
                            <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="insererDesignation" value="ENVOYER  >>"></div>
                            <div class="col-sm-1"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    <div id="serviceModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout designation Servive</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">
                          <input type="hidden" class="inputbasic" id="classe" name="classe" value="1">
                          
                            DESIGNATION<input type="text" class="inputbasic" placeholder="Désignation Entrée/Sortie" id="designation" name="designation" size="35" value="" />
                          Prix service<input class="inputbasic" name="prix" size="40" value="" />
                         <!-- Prix service<input class="inputbasic" name="prixService" size="40" value="" />-->
                           <div class="modal-footer row">
                            <dic class="col-sm-3 "><input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>"></dic>
                            <dic class="col-sm-1"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></dic>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    <div id="fraisModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout designation Frais</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">
                          <input type="hidden" class="inputbasic" id="classe" name="classe" value="2">
                          
                            DESIGNATION<input type="text" class="inputbasic" placeholder="Désignation Entrée/Sortie" id="designation" name="designation" size="35" value="" />
                          Montant frais<input class="inputbasic" name="prix" size="40" value="" />
                           <div class="modal-footer row">
                            <dic class="col-sm-3 "><input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>"></dic>
                            <dic class="col-sm-1"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></dic>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>';
/*****************************/

echo'<div class="container" align="center">
        <ul class="nav nav-tabs"> 
          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
                   $sql3='SELECT * from  `'.$nomtableStock.'` order by idStock desc';
                    if($res3=mysql_query($sql3)){
                    echo'<table id="exemple" class="display" border="1"><thead><tr>
						<th>DESIGNATION</th>
						<th>QUANTITE</th>
						<th>UNITE STOCK</th>
						<th>NOMBRE INITIAL</th>
						<th>RESTANT</th>
						<th>EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></thead>
					<tfoot><tr>
						<th>DESIGNATION</th>
						<th>QUANTITE</th>
						<th>UNITE STOCK</th>
						<th>NOMBRE INITIAL</th>
						<th>RESTANT</th>
						<th>EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></tfoot>';
                      while($tab3=mysql_fetch_array($res3)){
                              echo'<tr><td>'.$tab3["designation"].'</td>
                              <td>'.$tab3["quantiteStockinitial"].'</td>
                              <td>'.$tab3["uniteStock"].'</td>
							  <td>'.$tab3["totalArticleStock"].'</td>
							  <td>'.$tab3["quantiteStockCourant"].'</td>
                              <td>'.$tab3["dateExpiration"].'</td>
                              <td><a href="#">
                              <img src="images/edit.png" align="middle" alt="modifier" data-toggle="modal" data-target="#imgmodifier'.$tab3["idStock"].'" /></a>&nbsp;&nbsp;
                              <a href="#">'.
                              '<img src="images/drop.png" align="middle" alt="supprimer" data-toggle="modal" data-target="#imgsup'.$tab3["idStock"].'" /></a>
                              <a href="codeBarreStock.php?iDS='.$tab3["idStock"].'&iDD='.$tab3["idDesignation"].'">'.
                              'Details</a></td></tr>
      <div id="imgmodifier'.$tab3["idStock"].'"  class="modal fade " role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modifier stock</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                       <form role="form" class="" name="formulaire2" method="post" action="ajouterStock.php">
                           
                           <div class="form-group ">
                               <div>DESIGNATION</div><input class="inputbasic" name="designation" size="40" value="'.$tab3["designation"].'" />
                               <input type="hidden" name="idDesignation" value="" id="idD">'.
                                '<div>QUANTITE A STOCKER</div><input type="text" class="inputbasic" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'"  />

                              <div>UNITE STOCK</div><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT>
                                  <div>Prix Unitaire</div><input type="text" class="inputbasic" name="prixunitaire" size="35" value="'.$tab3["prixuniteStock"].'"  />
                                  <div>Prix par unité de stock</div><<input type="text" class="inputbasic" name="prixuniteStock" size="35" value="'.$tab3["prixunitaire"].'"  />
                               <div>DATE EXPIRATION</div><input type="date" class="inputbasic" id="dateExpiration" name="dateExpiration" value="" size="35" />
                            </div>
                           <div class="modal-footer ">
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/></div>
                              <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>
                              <input type="hidden" name="idStock" value="'.$tab3["idStock"].'"/>
                             <input type="hidden" name="modifier" value="1"/>
                              
                           </div>
                        </form>
                    </div>
                </div>
            </div>
      </div>
      <div id="imgsup'.$tab3["idStock"].'"  class="modal fade " role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Supprimer stock</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                       <form role="form" class="" name="formulaire2" method="post" action="ajouterStock.php">
                           
                           <div class="form-group ">
                               <div>DESIGNATION</div><input class="inputbasic" name="designation" size="40" value="'.$tab3["designation"].'" disabled=""/>
                               <input type="hidden" name="idDesignation" value="" id="idD">'.
                                '<div>QUANTITE A STOCKER</div><input type="text" class="inputbasic" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" disabled="" />

                              <div>UNITE STOCK</div><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT>
                                  <div>Prix Unitaire</div><input type="text" class="inputbasic" name="prixunitaire" size="35" value="'.$tab3["prixuniteStock"].'"  disabled=""/>
                                  <div>Prix par unité de stock</div><<input type="text" class="inputbasic" name="prixuniteStock" size="35" value="'.$tab3["prixunitaire"].'" disabled="" />
                               <div>DATE EXPIRATION</div><input type="text" class="inputbasic" id="dateExpiration" name="dateExpiration" value="'.$tab3["dateExpiration"].'" size="35" disabled=""/>
                            </div>
                           <div class="modal-footer ">
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic"  name="btnSupprimer" value="SUPPRIMER  >>"/></div>
                              <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>
                              <input type="hidden" name="idStock" value="'.$tab3["idStock"].'"/>
                             <input type="hidden" name="supprimer" value="1" />
                              
                           </div>
                        </form>
                    </div>
                </div>
            </div>
      </div>
                              ';

                      }
                    }
                    else{
                      echo'<table class="tableau2" width="80%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE STOCK</th><th>NOMBRE ARTICLE/US</th><th>DATE EXPIRATION</th><th></th>';
                      echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide</td></tr> ';
                       
                       }
                    echo'</table><br />
            </div>
            
        </div>
      </div>';

echo'<script>$(document).ready(function(){$("#AjoutStock").click(function(){$("#AjoutStockModal").modal();});});</script>'.
'</body></html>';
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