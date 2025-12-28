<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/
if(!$annuler){
if(!$modifier and !$supprimer){
  if($designation){
        $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
        $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11)){
             if($classe==0){

                $sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,prixPublic,forme,tableau,categorie)values ('".mysql_real_escape_string($designation)."',0,".$prixSession.",".$prixPublic.",'".mysql_real_escape_string($forme)."','".mysql_real_escape_string($tableau)."','".mysql_real_escape_string($categorie2)."')";
                $res=@mysql_query($sql) or die ("insertion impossible Produit en Article".mysql_error());
            }else if($classe==1){
              $sql="insert into `".$nomtableDesignation."` (designation,classe,prix) values('".mysql_real_escape_string($designation)."',1,".$prixSF.")";
              //echo ($sql);
              $res=@mysql_query($sql) or die ("insertion impossible Service".mysql_error());
            }else if($classe==2){
              $sql="insert into `".$nomtableDesignation."` (designation,classe,prix) values('".mysql_real_escape_string($designation)."',2,".$prixSF.")";
             // echo ($sql);
              $res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
            }
        }else{
        echo '<script type="text/javascript"> alert("ERREUR : LA REFERENCE ('.$designation.') EXISTE DEJA DANS LE CATALOGUE DES PRODUITS ...");</script>';
        }

  }else if($categorie1) {
  		$sql11="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".$categorie1."'";
        $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11)){
                $sql="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie1)."')";
       			$res=@mysql_query($sql) or die ("insertion categorie 1 impossible".mysql_error());
          }else{
        echo'<!DOCTYPE html>';
        echo'<html>';
        echo'<head>';
        echo'<script type="text/javascript" src="alertCategorie.js"></script>';
        echo'<script language="JavaScript">document.location="insertionProduit.php"</script>';
        echo'</head>';
        echo'</html>';
        }

    }
}
else if($modifier){ //if $modifier

      if($classe==0){
       $sql="update `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie2)."',classe=0,forme='".mysql_real_escape_string($forme)."',tableau='".mysql_real_escape_string($tableau)."',prixSession=".$prixSession.",prixPublic=".$prixPublic." where idDesignation=".$idDesignation;
       $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

            $sql2="update `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
            //echo $sql2;
            $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

            $sql2="update `".$nomtableTotalStock."` set designation='".mysql_real_escape_string($designation)."' where designation='".mysql_real_escape_string($designationAmodifier)."'";
            //echo $sql2;
            $res2=@mysql_query($sql2)or die ("modification reference dans totalStock ".mysql_error());

        }else{
            $sql="update `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',prix='".$prix."',classe='".$classe."' where idDesignation=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible 2".mysql_error());
             }
}
else if ($supprimer) {
  $sql="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation1;
  $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());
    $sql11="SELECT * FROM `". $nomtableTotalStock."` where designation='".$designation."'";
    $res11=mysql_query($sql11);
    if(mysql_num_rows($res11)){
  	$sql1="DELETE FROM `".$nomtableTotalStock."` WHERE designation=".mysql_real_escape_string($designation);
    $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
    }
}


if (isset($_POST['subImport1'])) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $headers = fgetcsv($handle, 1000, ";");


    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {


        $reference=htmlspecialchars(trim($data[0]));
        $categorie=htmlspecialchars(trim($data[1]));
        $forme=htmlspecialchars(trim($data[2]));
        $tableau=htmlspecialchars(trim($data[3]));
        $prixSession=$data[4];
        $prixPublic=$data[5];
        //$classe=$data[6];
        $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";
        $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11)){
           $sql3="insert into `".$nomtableDesignation."`(designation,forme,tableau,prixSession,prixPublic,categorie,classe)
           values('".mysql_real_escape_string($reference)."','".mysql_real_escape_string($forme)."','".mysql_real_escape_string($tableau)."',".$prixSession.",".$prixPublic.",'".mysql_real_escape_string($categorie)."',0)";
             //var_dump($sql);
            $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());
        }
        $sql11="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($categorie)."'";
        $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11))
                if($categorie) {
                $sql="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";
                $res=@mysql_query($sql) or die ("insertion categorie CSV impossible".mysql_error());
          }



    }
    fclose($handle);
    echo'<!DOCTYPE html>';
    echo'<html>';
    echo'<head>';
    //echo'<script type="text/javascript">alert("les references qui existe deja ne sont pas importes");</script>'
    echo'<script language="JavaScript">document.location="insertionProduit.php"</script>';
    echo'</head>';
    echo'</html>';
  }

if ( $_GET['l']==7) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $tabDes;
    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

     $tabDes[]=$data;

    }
    //return  $tabDes;
    //var_dump($tabDes);
    fclose($handle);

  }

}

}
if(isset($_POST["Export"])){

      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=data-Reference.csv');
      $delimiter = ";";
      $output = fopen("php://output", "w");
      $fields=array('REFERENCE','CATEGORIE','FORME','TABLEAU','PRIX SESSION','PRIX PUBLIC');
      fputcsv($output,$fields, $delimiter );
      fclose($output); exit;

 }
 if(isset($_POST["modifierCodeBarrePr"])){

    $codeBarreDesignation=htmlspecialchars(trim($_POST['codeBarreDesignation']));
    $codeBarreuniteStock=htmlspecialchars(trim($_POST['codeBarreuniteStock']));
    $sql="update `".$nomtableDesignation."` set codeBarreDesignation='".$codeBarreDesignation.
        "',codeBarreuniteStock='".$codeBarreuniteStock."' where idDesignation=".$idDesignation;
        //var_dump($sql);
    $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());


 }
/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

/**************** BOUTTON AjoutDesignation ET TFENETRE NODAL ASSOCIEE  *************/

echo '<div class="row">
<center>
    <table border="0"><tr><td>
          <form class="form-horizontal" action="insertionProduit.php" method="post" name="upload_excel"
                          enctype="multipart/form-data">

                                    <input type="submit" name="Export" class="btn btn-success" value="Générer le modéle CSV d\'Importation d\'un catalogue"/>';
   ?>
            </form>
    </td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>




          <form class="form-inline" action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post" enctype="multipart/form-data">

                       <input type="file" id="importInput" name="fileImport"
                        data-toggle="modal" onChange="loadCSV()" required>
                        <button type="submit" name="subImport1" value="Importer " class="btn btn-success">Importer un catalogue</button>

          </form>
  </td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
            <i class="glyphicon glyphicon-plus"></i>Ajout de Produit dans le catalogue</button>
</td></tr></table>
</center>

<?php


echo'<div class="modal fade" id="AjoutStockModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Produit </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0">
<form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">';?>

<div class="form-group">
    <tr class="div-reference"><td><label for="reference">REFERENCE <font color="red">*</font></label></td></tr>
    <tr class="div-reference"><td><input type="text" class="form-control" placeholder="Nom de la reference du Produit ici..."  name="designation" id="designation" value="" required /></td></tr>
    <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
</div>

<div class="form-group">
  <tr class="div-categorie"><td><label for="categorie"> CATEGORIE </label></td></tr>

  <tr class="div-categorie"><td>
    <select class="form-control" name="categorie2" id="categorie2">
        <option selected value= "Sans categorie">Sans categorie</option>
         <?php
            $sql11="SELECT * FROM `". $nomtableCategorie."`";
            $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
            while($ligne2 = mysql_fetch_row($res11)) {
                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

              } ?>
    </select>
  </td></tr>
  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
</div>

<div class="form-group" >
  <tr class="div-forme"><td><label for="forme"> FORME <font color="red">*</font></label></td></tr>
  <tr class="div-forme"><td><input type="text" class="form-control" placeholder="la forme" id="forme" name="forme" value="" required /></td></tr>
  <tr class="div-forme"><td><div class="help-block label label-danger" id="helpForme"></div></td></tr>
</div>

<div class="form-group" >
  <tr class="div-tableau"><td><label for="tableau"> TABLEAU <font color="red">*</font></label></td></tr>
  <tr class="div-tableau"><td><input type="text" class="form-control" placeholder="le tableau" id="tableau" name="tableau" value="" required /></td></tr>
  <tr class="div-tableau"><td><div class="help-block label label-danger" id="helpTableau"></div></td></tr>
</div>

<div class="form-group" >
  <tr class="div-prix"><td><label for="prix">PRIX SESSION <font color="red">*</font></label></td></tr>
  <tr class="div-prix"><td><input type="number" required="" class="form-control" placeholder="Prix Session" id="prixSession" name="prixSession" value="" /></td></tr>
  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixSession"></div></td></tr>
</div>


<div class="form-group" >
  <tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX PUBLIC <font color="red">*</font></label></td></tr>
  <tr class="div-prixuniteStock"><td><input type="number" required="" class="form-control" placeholder="Prix public" id="prixPublic" name="prixPublic" value="" /></td></tr>
  <tr class="div-prixuniteStock"><td><div class="help-block label label-danger" id="helpPrixUniteStock"></div></td></tr>
</div>
<!--
<div class="form-group" >
  <tr class="div-seuilStock"><td><label for="seuilStock">SEUIL DU STOCK</label></td></tr>
  <tr class="div-seuilStock"><td><input type="number" class="form-control" placeholder="Seuil du stock" id="seuil" name="seuil" value="10" disabled /></td></tr>
  <tr class="div-seuilStock"><td><div class="help-block label label-danger" id="helpSeuilStock"></div></td></tr>
</div>

<div class="form-group">
  <tr class="div-codebarre"><td><label for="codeBarre">CODE A BARRE PRODUIT</label></td></tr>
  <tr class="div-codebarre"><td><input type="text" class="form-control" placeholder="Code à barre du Produit ici..." id="codeBarreDesignation" name="codeBarreDesignation" value="" /></td></tr>
  <tr class="div-codebarre"><td><div class="help-block label label-danger" id="helpCodeBarre"></div></td></tr>
</div>


<div class="form-group">
  <tr class="div-codebarreuniteStock"><td><label for="codeBarreuniteStock">CODE A BARRE UNITE STOCK </label></td></tr>
  <tr class="div-codebarreuniteStock"><td><input type="text" class="form-control" placeholder="Code à barre de l'unité stock ici..."  id="codeBarreuniteStock" name="codeBarreuniteStock" value="" /></td></tr>
  <tr class="div-codebarreuniteStock"><td><div class="help-block label label-danger" id="helpCodeBarreuniteStock"></div></td></tr>
</div>

-->

<?php
echo'<tr><td colspan="2" align="center"><div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font><br />
    <input type="hidden" name="classe" value="0" />
  <input type="submit" class="boutonbasic" name="inserer" value="AJOUTER  >>">'.


'</td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr></div>';
echo'</form></table><br />'.
'</div></div></div></div></div>';

?>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/

echo'<div class="container">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">REFERENCES DES PRODUITS</a></li>';
echo'<li><a data-toggle="tab" href="#SERVICE">REFERENCES DES SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">REFERENCES DES DEPENCES</a></li>';
echo'</ul><div class="tab-content">';

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr><th>REFERENCE</th>
     <th>CATEGORIE</th>
     <th>FORME</th>
     <th>TABLEAU</th>
     <th>PRIX SESSION</th>
     <th>PRIX PUBLIC</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr><th>REFERENCE</th>
     <th>CATEGORIE</th>
     <th>FORME</th>
     <th>TABLEAU</th>
     <th>PRIX SESSION</th>
     <th>PRIX PUBLIC</th>
     <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if(mysql_num_rows($res)){
    while($tab=mysql_fetch_array($res)){
    //PRODUIT
      if($tab["classe"]==0){
          echo'<tr><td>'.$tab["designation"].'</td><td>'.$tab["categorie"].'</td><td>'.$tab["forme"].'</td><td>'.$tab["tableau"].'</td><td align="right">'.$tab["prixSession"].'</td><td align="right">'.$tab["prixPublic"].'</td>';
          echo '<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;';

        $sql2="select * from `".$nomtableStock."` where idDesignation=".$tab['idDesignation'];
        //echo $sql2;
        $res2=mysql_query($sql2);
        if($tab2=mysql_fetch_array($res2)){
            if ($tab2["quantiteStockCourant"]==0){
                echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idDesignation"].'" /></a>';
         }
         else{
             //echo'<a><img src="images/drop.png" align="middle" alt="supprimer"/></a>';
         }
         }else{
             echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idDesignation"].'" /></a>';
         }

         /*
         $sql2="select * from `".$nomtableTotalStock."` where designation=".$tab['designation'];
        $res2=mysql_query($sql2);
        if(mysql_num_rows($res2)){
          while($tab2=mysql_fetch_array($res2))
            if ($tab2["quantiteEnStocke"]>0){
                 echo'<a >
                    <img src="images/drop.png" align="middle" alt="supprimer" />
                 </a>';
            }
             else{
                 echo'<a >
                    <img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idDesignation"].'" />
                 </a>';
             }
         }else{
          echo'<a >
                    <img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idDesignation"].'" />
                 </a>';
         }
         */

        // echo'<a data-toggle="modal" data-target="#codeBP'.$tab["idDesignation"].'"><span class="glyphicon glyphicon-user"></span>';

        echo'</a>
         <div id="codeBP'.$tab["idDesignation"].'" class="modal fade" role="dialog">
                <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modification code barre</h4>
                        </div>
                        <div class="modal-body" style="padding:40px 50px;">
                           <form class="form" name="saveCodBarre" method="post" action="insertionProduit.php">
                               <div class="form-group ">
                                   <div>CodeBarUnitaire</div><input class="inputbasic" name="codeBarreDesignation" size="40" value="'.$tab["codeBarreDesignation"].'" />'.
                                    '<div>Code par Unité de stock de stock</div><input class="inputbasic" name="codeBarreuniteStock" size="40" value="'.$tab["codeBarreuniteStock"].'" />'.

                                '</div>
                              <div class="modal-footer row">
                                   <div class="col-sm-3 "> <input type="submit" class="boutonbasic" name="modifierCodeBarrePr" value=" Enregistrer >>" /></div>
                                   <div class="col-sm-3 "> <input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>'.
                                   '<input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>

                              </div>
                           </form>
                        </div>
                   </div>
                </div>
        </div>'.
        '<div id="imgsup'.$tab["idDesignation"].'" class="modal fade" role="dialog">
                <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirmation Suppression Référence</h4>
                        </div>
                        <div class="modal-body" style="padding:40px 50px;">

                          <table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">

                                <div class="form-group">
                                  <tr class="div-reference"><td><label for="reference">REFERENCE </label></td></tr>
                                  <tr class="div-reference"><td><input type="text" class="form-control" name="designation" id="designation" value="'.$tab["designation"].'" disabled=""/></td></tr>
                                  <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
                                </div>

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="categorie"> CATEGORIE </label></td></tr>

                                  <tr class="div-categorie"><td>
                                    <select class="form-control" name="categorie2" id="categorie2" disabled="">
                                        <option selected value= "'.$tab["categorie"].'">'.$tab["categorie"].'</option>';

                                            $sql11="SELECT * FROM `". $nomtableCategorie."`";
                                            $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div> ';
                               /* <div class="form-group">
                                  <tr class="div-uniteStock"><td><label for="uniteStock"> UNITE STOCK </label></td></tr>

                                  <tr class="div-uniteStock"><td>
                                    <select class="form-control" name="uniteStock" id="uniteStock" disabled="">
                                            <option selected value= "'.$tab["uniteStock"].'">'.$tab["uniteStock"].'</option>';

                                            $sql11="SELECT * FROM `aaa-unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-uniteStock"><td><div class="help-block label label-danger" id="helpUniteStock"></div></td></tr>
                                </div>



                                <div class="form-group" >
                                  <tr class="div-nbArticleUniteStock"><td><label for="nbArticleUniteStock">NOMBRE ARTICLE(S) PAR UNITE STOCK </label></td></tr>
                                  <tr class="div-nbArticleUniteStock"><td><input type="number" class="form-control" id="nbArticleUniteStock" disabled="" name="nbArticleUniteStock" value="'.$tab["nbreArticleUniteStock"].'" /></td></tr>
                                  <tr class="div-nbArticleUniteStock"><td><div class="help-block label label-danger" id="helpNbArticleUniteStock"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-seuilStock"><td><label for="seuilStock">SEUIL DU STOCK</label></td></tr>
                                  <tr class="div-seuilStock"><td><input type="number" class="form-control" disabled="" id="seuil" name="seuil" value="'.$tab["seuil"].'" /></td></tr>
                                  <tr class="div-seuilStock"><td><div class="help-block label label-danger" id="helpSeuilStock"></div></td></tr>
                                </div>


                                <div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">PRIX UNITAIRE </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" disabled="" id="prix" name="prix" value="'.$tab["prix"].'" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX UNITE STOCK</label></td></tr>
                                  <tr class="div-prixuniteStock"><td><input type="number" class="form-control" disabled="" id="prixuniteStock" name="prixuniteStock" value="'.$tab["prixuniteStock"].'" /></td></tr>
                                  <tr class="div-prixuniteStock"><td><div class="help-block label label-danger" id="helpPrixUniteStock"></div></td></tr>
                                </div>

                                <div class="form-group">
                                  <tr class="div-codebarre"><td><label for="codeBarre">CODE A BARRE PRODUIT</label></td></tr>
                                  <tr class="div-codebarre"><td><input type="text" class="form-control" disabled="" id="codeBarreDesignation" name="codeBarreDesignation" value="'.$tab["codeBarreDesignation"].'" /></td></tr>
                                  <tr class="div-codebarre"><td><div class="help-block label label-danger" id="helpCodeBarre"></div></td></tr>
                                </div>


                                <div class="form-group">
                                  <tr class="div-codebarreuniteStock"><td><label for="codeBarreuniteStock">CODE A BARRE UNITE STOCK </label></td></tr>
                                  <tr class="div-codebarreuniteStock"><td><input type="text" disabled="" class="form-control" id="codeBarreuniteStock" name="codeBarreuniteStock" value="'.$tab["codeBarreuniteStock"].'" /></td></tr>
                                  <tr class="div-codebarreuniteStock"><td><div class="help-block label label-danger" id="helpCodeBarreuniteStock"></div></td></tr>
                                </div>  */


                                echo'<tr><td align="center">
                                  <br/><br/>
                                  <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                  <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                    <input type="hidden" name="supprimer" value="1" />
                                    </td>
                                </tr>
                              </form>
                       </table>

                        </div>
                   </div>
                </div>
        </div>'.



        '<div id="imgmodifier'.$tab["idDesignation"].'"  class="modal fade " role="dialog">

            <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modifier Référence</h4>
                        </div>
                        <div class="modal-body" style="padding:40px 50px;">



                            <table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">



                                <div class="form-group">
                                    <tr class="div-reference"><td><label for="reference">REFERENCE <font color="red">*</font></label></td></tr>
                                    <tr class="div-reference"><td><input type="text" class="form-control" placeholder="Nom de la reference du Produit ici..."  name="designation" id="designation" value="'.$tab["designation"].'" required /></td></tr>
                                    <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
                                </div>

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="categorie"> CATEGORIE </label></td></tr>

                                  <tr class="div-categorie"><td>
                                    <select class="form-control" name="categorie2" id="categorie2">
                                        <option selected value= "'.$tab["categorie"].'">'.$tab["categorie"].'</option>';

                                            $sql11="SELECT * FROM `". $nomtableCategorie."`";
                                            $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-forme"><td><label for="forme"> FORME <font color="red">*</font></label></td></tr>
                                  <tr class="div-forme"><td>
                                     <select class="form-control" name="forme" id="forme">
                                            <option selected value= "'.$tab["forme"].'">'.$tab["forme"].'</option>';

                                            $sql11="SELECT * FROM `aaa-unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-forme"><td><div class="help-block label label-danger" id="helpForme"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-tableau"><td><label for="tableau"> TABLEAU <font color="red">*</font></label></td></tr>
                                  <tr class="div-tableau"><td>
                                    <select class="form-control" name="tableau" id="tableau">
                                            <option selected value= "'.$tab["tableau"].'">'.$tab["tableau"].'</option>';

                                            $sql11="SELECT * FROM `aaa-unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-tableau"><td><div class="help-block label label-danger" id="helpTableau"></div></td></tr>
                                </div>';

                                echo'<div class="form-group" >
                                  <tr class="div-prixSession"><td><label for="prixSession">PRIX SESSION </label></td></tr>
                                  <tr class="div-prixSession"><td><input type="number" class="form-control" id="prixSession" name="prixSession" value="'.$tab["prixSession"].'" /></td></tr>
                                  <tr class="div-prixSession"><td><div class="help-block label label-danger" id="helpprixSession"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixPublic"><td><label for="prixPublic">PRIX PUBLIC </label></td></tr>
                                  <tr class="div-prixPublic"><td><input type="number" class="form-control" id="prixPublic" name="prixPublic" value="'.$tab["prixPublic"].'" /></td></tr>
                                  <tr class="div-prixPublic"><td><div class="help-block label label-danger" id="helpprixPublic"></div></td></tr>
                                </div>';
    ?>

                                <!-- <div class="form-group">
                                  <tr class="div-codebarre"><td><label for="codeBarre">CODE A BARRE PRODUIT</label></td></tr>
                                  <tr class="div-codebarre"><td><input type="text" class="form-control" id="codeBarreDesignation" name="codeBarreDesignation" value="'.$tab["codeBarreDesignation"].'" /></td></tr>
                                  <tr class="div-codebarre"><td><div class="help-block label label-danger" id="helpCodeBarre"></div></td></tr>
                                </div>


                                <div class="form-group">
                                  <tr class="div-codebarreuniteStock"><td><label for="codeBarreuniteStock">CODE A BARRE UNITE STOCK </label></td></tr>
                                  <tr class="div-codebarreuniteStock"><td><input type="text" class="form-control" id="codeBarreuniteStock" name="codeBarreuniteStock" value="'.$tab["codeBarreuniteStock"].'" /></td></tr>
                                  <tr class="div-codebarreuniteStock"><td><div class="help-block label label-danger" id="helpCodeBarreuniteStock"></div></td></tr>
                                </div>
-->
<?php



                                echo '<tr><td align="center">
                                  <br />
                                              <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                  <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                                  <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                  <input type="hidden" name="classe" value="0" />
                                 <input type="hidden" name="modifier" value="1"/>
                                 <input type="hidden" name="designationAmodifier" value="'.$tab["designation"].'"/>
                                    </td>
                                </tr>
                              </form>
                       </table>


















                        </div>
                    </div>
                </div>







        </div> ';?>
        <?php
        echo'</td></tr>';
        }
    }
}


echo'</tbody></table><br /></div></div>';

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);
echo'<div id="SERVICE" class="tab-pane fade">';




echo '<br /><div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal1" data-dismiss="modal" id="AjoutStock">
<i class="glyphicon glyphicon-plus"></i>Ajout de Service </button>';



echo'<div class="modal fade" id="AjoutStockModal1" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Service </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0">
<form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">';?>

<div class="form-group">
    <tr class="div-reference"><td><label for="reference">REFERENCE <font color="red">*</font></label></td></tr>
    <tr class="div-reference"><td><input type="text" class="form-control" placeholder="Nom de la reference du Service ici..."  name="designation" id="designation" value="" required /></td></tr>
    <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
</div>

<div class="form-group" >
  <tr class="div-prix"><td><label for="prix">PRIX <font color="red">*</font></label></td></tr>
  <tr class="div-prix"><td><input type="number" class="form-control" placeholder="Prix du Service ici ..." id="prixSF" name="prixSF" value="" required /></td></tr>
  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
</div>


<div class="form-group" >
  <tr class="div-pourcentage"><td><label for="pourcentage">POURCENTAGE</label></td></tr>
  <tr class="div-pourcentage"><td><input type="number" min ="0" max ="100" class="form-control" placeholder="un nombre dans [0,100]" id="pourcentage" name="pourcentage" value="100" /></td></tr>
  <tr class="div-pourcentage"><td><div class="help-block label label-danger" id="helppourcentage"></div></td></tr>
</div>

<?php
echo'<tr><td colspan="2" align="center"><div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font><br />
    <input type="hidden" name="classe" value="1" />
  <input type="submit" class="boutonbasic" name="inserer" value="AJOUT SERVICE >>">'.


'</td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr></div>';
echo'</form></table><br />'.
'</div></div></div></div></div>';


//echo '<br><br>';






echo'<div class="table-responsive"><table id="exemple2" class="display" width="100%" align="left" border="1">';
echo'<thead>
<tr>
    <th>REFERENCE</th>
    <th>MONTANT SERVICE</th>
    <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr>
    <th>REFERENCE</th>
    <th>MONTANT SERVICE</th>
    <th>OPERATIONS</th>
</tr>
</tfoot><tbody>';

if(mysql_num_rows($res)){
while($tab=mysql_fetch_array($res)){
//SERVICE
  if($tab["classe"]==1)
      echo'<tr><td>'.$tab["designation"].'</td><td>'.$tab["prix"].'</td>
    <td>
    <a href="#" >
        <img src="images/edit.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgmodifierSe'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
    <a   href="#" >
        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" data-target="#imgsupSe'.$tab["idDesignation"].'"/></a>
        <a href="codeBarreSerFr.php?idD='.$tab["idDesignation"].'" class="btn btn-primary">Codes barres</a>
        </td></tr>

      <div id="imgmodifierSe'.$tab["idDesignation"].'" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modifier designation Servive</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">
                          <div class="form-group ">
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" disabled="">
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" checked disabled="">
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" disabled="">
                          </div>
                          <div class="form-group ">
                              <div> REFERENCE <font color="red">*</font></div>
                                 <input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" required />'.
                              '<div>PRIX <font color="red">*</font></div>
                                <input type="number" class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" required />
                           </div>

                           <div class="modal-footer row">
                              <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                             <input type="hidden" name="modifier" value="1"/>
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="btnModifier" value="MODIFIER  >>"/></div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

     <div id="imgsupSe'.$tab["idDesignation"].'" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Formulaire pour supprimer une désignation de service</h4>
                    </div>
                    <div class="modal-body">

                       <form class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">

                           <div class="form-group ">
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" disabled="">
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" checked disabled="">
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" disabled="">
                           </div>
                           <div class="form-group ">
                              <div>REFERENCE </div>
                              <input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" disabled/>
                              <div>PRIX </div>
                              <input type="number" class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" disabled/>
                           </div>

                            <div class="modal-footer row">
                                  <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>'.
                                  '<input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
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

echo'</tbody></table><br /></div></div>';




$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

echo'<div id="FRAIS" class="tab-pane fade">';



echo'<br /><div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal2" data-dismiss="modal" id="AjoutStock">
<i class="glyphicon glyphicon-plus"></i>Ajout de dépence</button>';




echo'<div class="modal fade" id="AjoutStockModal2" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de dépence </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0">
<form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">';?>

<div class="form-group">
    <tr class="div-reference"><td><label for="reference">REFERENCE <font color="red">*</font></label></td></tr>
    <tr class="div-reference"><td><input type="text" class="form-control" placeholder="Nom de la reference de la depence ici..."  name="designation" id="designation" value="" required /></td></tr>
    <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
</div>

<div class="form-group" >
  <tr class="div-prix"><td><label for="prix">PRIX <font color="red">*</font></label></td></tr>
  <tr class="div-prix"><td><input type="number" class="form-control" placeholder="Prix de la depence ici ..." id="prixSF" name="prixSF" value="" required /></td></tr>
  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
</div>


<div class="form-group" >
  <tr class="div-pourcentage"><td><label for="pourcentage">POURCENTAGE</label></td></tr>
  <tr class="div-pourcentage"><td><input type="number" min ="0" max ="100" class="form-control" placeholder="un nombre dans [0,100]" id="pourcentage" name="pourcentage" value="100" /></td></tr>
  <tr class="div-pourcentage"><td><div class="help-block label label-danger" id="helppourcentage"></div></td></tr>
</div>

<?php
echo'<tr><td colspan="2" align="center"><div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font><br />

    <input type="hidden" name="classe" value="2" />
  <input type="submit" class="boutonbasic" name="inserer" value="AJOUT DEPENSE >>">'.


'</td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr></div>';
echo'</form></table><br />'.
'</div></div></div></div></div>';

//echo'<br><br>';



echo'<div class="table-responsive"><table id="exemple3" class="display" width="100%" align="left" border="1">';
echo'<thead>
    <tr>
        <th>REFERENCE</th>
        <th>MONTANT DEPENCE</th>
        <th>OPERATIONS</th>
    </tr>
</thead><tfoot>
    <tr>
        <th>REFERENCE</th>
        <th>MONTANT DEPENCE</th>
        <th>OPERATIONS</th>
    </tr>
</tfoot><tbody>';
//echo'<table class="tableau3" width="80%" align="left" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>CLASSE</th><th></th>';
if(mysql_num_rows($res)){

while($tab=mysql_fetch_array($res)){
//AUTRE
  if($tab["classe"]==2)
      echo'<tr><td>'.$tab["designation"].'</td><td>'.$tab["prix"].'</td><td>
    <a href="#" >
        <img src="images/edit.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgmodifierFr'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
    <a href="#" >
        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" data-target="#imgsupFr'.$tab["idDesignation"].'"/></a>
        <a href="codeBarreSerFr.php?idD='.$tab["idDesignation"].'" class="btn btn-primary">Codes barres</a>
        </td></tr>

         <div id="imgmodifierFr'.$tab["idDesignation"].'" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modifier designation Frais</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">
                          <div class="form-group ">
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" disabled="">
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" disabled="">
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" checked disabled="">
                          </div>
                          <div class="form-group ">
                              <div> REFERENCE <font color="red">*</font></div>
                                 <input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" required />'.
                              '<div>PRIX <font color="red">*</font></div>
                                <input type="number" class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" required />
                           </div>

                           <div class="modal-footer row">
                              <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                             <input type="hidden" name="modifier" value="1"/>
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="btnModifier" value="MODIFIER  >>"/></div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="imgsupFr'.$tab["idDesignation"].'" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Formulaire pour supprimer une désignation de Frais</h4>
                    </div>
                    <div class="modal-body">

                       <form class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">

                           <div class="form-group ">
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" disabled="" >
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" disabled="" >
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" checked disabled="">
                           </div>
                           <div class="form-group ">
                              <div>REFERENCE</div><input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" disabled/>'.
                              '<div>PRIX </div><input type="number" class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" disabled/>
                           </div>

                            <div class="modal-footer row">
                                  <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>'.
                                  '<input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
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

echo'</tbody></table><br/></div></div>';


echo'</section>'.
'<script>$(document).ready(function(){$("#imgmodifier").click(function(){$("#ModifierDesignationModal").modal();});});</script>'.
'<script>$(document).ready(function(){$("#imgsup").click(function(){$("#supprimerDesignationModal").modal();});});</script>'.
'</div></div></body></html>';
}
?>
