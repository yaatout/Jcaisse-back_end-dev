<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');

require('declarationVariables.php');

/**********************/
$designation         =@$_POST["designation"];
$designationAmodifier=@$_POST["designationAmodifier"];

$prix                =@$_POST["prix"];
$prixSF              =@$_POST["prixSF"];

$idDesignation       =@$_POST["idDesignation"];
$idTransaction       =@$_POST["idTransaction"];

$classe              =@$_POST["classe"];

$desig               =@$_POST["desig"];
$uniteStock          =@$_POST["uniteStock"];
$uniteDetails          =@$_POST["uniteDetails"];
$prixService         =@$_POST["prixService"];
$montantFrais        =@$_POST["montantFrais"];
$nbArticleUniteStock =@$_POST["nbArticleUniteStock"];
$seuil 				 =@$_POST["seuil"];
$prixUnitaire        =@$_POST["prix"];
$prixuniteStock      =@$_POST["prixuniteStock"];
$prixuniteDetails      =@$_POST["prixuniteDetails"];
$prixAchat        =@$_POST["prixachat"];
$codeBarre        =@$_POST["codeBarre"];

$prixSession         =@$_POST["prixSession"];
$prixPublic          =@$_POST["prixPublic"];

$forme               =@$_POST["forme"];
$tableau             =@$_POST["tableau"];

$uniteService        =@$_POST["uniteService"];

$uniteDepence        =@$_POST["uniteDepence"];
$typePrix            =@$_POST["type-prix"];


$prixSD              =@$_POST["prixSF"];

$typePourcentage     =@$_POST["type-pourcentage"];
$pourcentage         =@$_POST["pourcentage"];

$categorie2          =@$_POST["categorie2"];
$categorie1          =@$_POST["categorie1"];


$modifier            =@$_POST["modifier"];
$supprimer           =@$_POST["supprimer"];
$annuler             =@$_POST["annuler"];
/***************/
$idDesignation1      =@$_POST["idDesignation"];

if (!isset($_POST['prixSF']))
    $prixSD=0;

if (!isset($_POST['pourcentage']))
    $pourcentage=0;


if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

    require('insertionProduit-pharmacie.php');

}
else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
  require('insertionProduit-entrepot.php');
}
else{

  $sql0="SELECT * FROM `". $nomtableDesignation."` ";
  $res0=mysql_query($sql0);
  while ($produit = mysql_fetch_assoc($res0)) {
    if($produit['codeBarreuniteStock']!=null || $produit['codeBarreuniteStock']!=''){
      $x= strlen($produit['idDesignation']);
      if($x==1){
        $code=$produit['idDesignation'].'34522345'.$produit['idDesignation'];
      }
      if($x==2){
        $code=$produit['idDesignation'].'342234'.$produit['idDesignation'];
      }
      if($x==3){
        $code=$produit['idDesignation'].'3223'.$produit['idDesignation'];
      }
      if($x==4){
        $code=$produit['idDesignation'].'22'.$produit['idDesignation'];
      }
      $sql="update `".$nomtableDesignation."` set codeBarreuniteStock=".$code." where idDesignation=".$produit['idDesignation'];
      $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
    }
  }

if (isset($_POST['activerT'])) {
  $sql="update `".$nomtableDesignation."` set seuil=1 where idDesignation=".$idDesignation;
  $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
}
if (isset($_POST['desactiverT'])) {
  $sql="update `".$nomtableDesignation."` set seuil=0 where idDesignation=".$idDesignation;
  $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
}
if (isset($_POST['ajouterT'])) {
  $reqT="SELECT * from `aaa-transaction` where idTransaction='".$idTransaction."'";
  $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());
  $transaction = mysql_fetch_array($resT);

  if($transaction['typeTransaction']=='Transaction'){
    $codeDepot=$transaction['idTransaction'].'080091'.$transaction['idTransaction'];
    $codeRetrait=$transaction['idTransaction'].'910080'.$transaction['idTransaction'];
    $codeFacture=$transaction['idTransaction'].'010098'.$transaction['idTransaction'];
    $sql="insert into `".$nomtableDesignation."` (description,designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie,codeBarreDesignation)values ('".$transaction['idTransaction']."','".mysql_real_escape_string($transaction['nomTransaction'])."',3,0,'Depot',0,'1','1','".mysql_real_escape_string($categorie2)."','".$codeDepot."')";
    $res=@mysql_query($sql) or die ("insertion impossible Service".mysql_error());

    $sql1="insert into `".$nomtableDesignation."` (description,designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie,codeBarreDesignation)values ('".$transaction['idTransaction']."','".mysql_real_escape_string($transaction['nomTransaction'])."',3,0,'Retrait',0,'1','1','".mysql_real_escape_string($categorie2)."','".$codeRetrait."')";
    $res1=@mysql_query($sql1) or die ("insertion impossible Service".mysql_error());

    $sql2="insert into `".$nomtableDesignation."` (description,designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie,codeBarreDesignation)values ('".$transaction['idTransaction']."','".mysql_real_escape_string($transaction['nomTransaction'])."',3,0,'Facture',0,'1','1','".mysql_real_escape_string($categorie2)."','".$codeFacture."')";
    $res2=@mysql_query($sql2) or die ("insertion impossible Service".mysql_error());
  }
 
}  

if(!$annuler){
if(!$modifier and !$supprimer){
  if($designation){
      if($classe==0){
        $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
        $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11)){
          if ($uniteStock=="article" || $uniteStock=="Article"){
            $sql="insert into `".$nomtableDesignation."` (designation,classe,prixachat,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,codeBarreDesignation,categorie)
            values ('".mysql_real_escape_string($designation)."',0,".$prixAchat.",".$prixUnitaire.",'Article','".$prixUnitaire."','1','".mysql_real_escape_string($codeBarre)."','".mysql_real_escape_string($categorie2)."')";
            $res=@mysql_query($sql) or die ("insertion impossible Produit en Article".mysql_error());
          }
          else {
                $sql="insert into `".$nomtableDesignation."` (designation,classe,prixachat,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,codeBarreDesignation,categorie)
                values ('".mysql_real_escape_string($designation)."',0,".$prixAchat.",".$prixUnitaire.",'".mysql_real_escape_string($uniteStock)."','".$prixuniteStock."','".$nbArticleUniteStock."','".mysql_real_escape_string($codeBarre)."','".mysql_real_escape_string($categorie2)."')";
                $res=@mysql_query($sql) or die ("insertion impossible Produit en uniteStock".mysql_error());
          }
          
        }
        else{
          echo '<script type="text/javascript"> alert("ERREUR : LA REFERENCE ('.$designation.') EXISTE DEJA DANS LE CATALOGUE DES PRODUITS ...");</script>';
        }
      }
      else{
        $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
		    $res11=mysql_query($sql11);
        if(!mysql_num_rows($res11)){
          if($classe==1){
            if($uniteService=='Transaction'){
              $reqT="SELECT * from `aaa-transaction` where nomTransaction='".$designation."'";
              $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());
              $transaction = mysql_fetch_array($resT);
             
              $sql="insert into `".$nomtableDesignation."` (description,designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".$transaction['idTransaction']."','".mysql_real_escape_string($designation)."',1,0,'".$uniteService."',0,'1','10','".mysql_real_escape_string($categorie2)."')";
              $res=@mysql_query($sql) or die ("insertion impossible Service".mysql_error());
            }
            else{
              $sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".mysql_real_escape_string($designation)."',1,".$prixSD.",'".$uniteService."','".$prixSD."','1','10','".mysql_real_escape_string($categorie2)."')";
              $res=@mysql_query($sql) or die ("insertion impossible Service".mysql_error());

              $sql1="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
              $res1=mysql_query($sql1);
              $design = mysql_fetch_array($res1);
              $code=$design['idDesignation'].'11011'.$design['idDesignation'];

              $sql="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$code."' where idDesignation='".$design['idDesignation']."' ";
              $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

            }
            
            
          }
          else if($classe==2){
            $sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".mysql_real_escape_string($designation)."',2,".$prixSD.",'".$uniteDepence."','".$prixSD."','1','10','".mysql_real_escape_string($categorie2)."')";
            $res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());

            $sql1="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
            $res1=mysql_query($sql1);
            $design = mysql_fetch_array($res1);
            $code=$design['idDesignation'].'22022'.$design['idDesignation'];

            $sql="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$code."' where idDesignation='".$design['idDesignation']."' ";
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
          }
        }
        else{
          echo '<script type="text/javascript"> alert("ERREUR : LA REFERENCE ('.$designation.') EXISTE DEJA DANS LE CATALOGUE DES SERVICES OU DEPENCES ...");</script>';
        }
      }
  }
  else if($categorie1) {
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

      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { 
            $sql="update `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie2)."',classe=0,uniteStock='".mysql_real_escape_string($uniteStock)."',nbreArticleUniteStock=".$nbArticleUniteStock.",prixuniteStock=".$prixuniteStock.",uniteDetails='".mysql_real_escape_string($uniteDetails)."',prixuniteDetails=".$prixuniteDetails." where idDesignation=".$idDesignation;
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
      }
      else{
        $sql="UPDATE `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie2)."',classe=0,uniteStock='".mysql_real_escape_string($uniteStock)."',nbreArticleUniteStock=".$nbArticleUniteStock.",prix=".$prixUnitaire.",prixuniteStock=".$prixuniteStock.",prixachat=".$prixAchat." where idDesignation=".$idDesignation;
        $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
      }
      $sql2="update `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
			//echo $sql2;
      $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

	/*		$sql2="update `".$nomtableTotalStock."` set designation='".mysql_real_escape_string($designation)."' where designation='".mysql_real_escape_string($designationAmodifier)."'";
			//echo $sql2;
			$res2=@mysql_query($sql2)or die ("modification reference dans totalStock ".mysql_error());*/

    }
    else if($classe==1) {
			$sql="update `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',uniteStock='".$uniteService."',prix='".$prixSD."',prixuniteStock=".$prixSD." where idDesignation=".$idDesignation;
			$res=@mysql_query($sql)or die ("modification impossible 2".mysql_error());
    }
    else if($classe==2) {
			$sql="update `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',uniteStock='".$uniteDepence."',prix='".$prixSF."',prixuniteStock=".$prixSF." where idDesignation=".$idDesignation;
			$res=@mysql_query($sql)or die ("modification impossible 2".mysql_error());
		}
}
else if ($supprimer) {
    if($classe==1) {
            $sql="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation1;
            $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());
		}else if($classe==2) {
			$sql="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation1;
            $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());
		}else{

      $sql="DELETE FROM `".$nomtableStock."` WHERE idDesignation=".$idDesignation1;
      $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());

      $sql1="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation1;
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


      $parent=htmlspecialchars(trim($data[0]));
      $categorie=htmlspecialchars(trim($data[1]));
      
      $sql10="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($parent)."'";
      $res10=mysql_query($sql10);
      if(!mysql_num_rows($res10)){
          $sql3="insert into `".$nomtableCategorie."` (nomcategorie,categorieParent)
          values('".mysql_real_escape_string($parent)."',0)";
              //var_dump($sql);
              $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());
              
              $sql1="SELECT * FROM `".$nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($parent)."' ";
              $res1=mysql_query($sql1);
              $parent_1=mysql_fetch_array($res1);
  
              $sql2="insert into `".$nomtableCategorie."` (nomcategorie,categorieParent)
              values('".mysql_real_escape_string($categorie)."','".$parent_1['idcategorie']."')";
              $res2=@mysql_query($sql2) or die ("insertion reference CSV impossible".mysql_error());
      }
      else{
          $parent_1=mysql_fetch_array($res10);
  
          $sql2="insert into `".$nomtableCategorie."` (nomcategorie,categorieParent)
          values('".mysql_real_escape_string($categorie)."','".$parent_1['idcategorie']."')";
          $res2=@mysql_query($sql2) or die ("insertion reference CSV impossible".mysql_error());
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
      $fields=array('REFERENCE','CATEGORIE', 'UNITE-STOCK','NBRES-ARTICLES-UNITE-STOCK',
                    'PRIX-UNITAIRE', 'PRIX-UNITE-STOCK', 'PRIX-ACHAT', 'QUANTITE-STOCK', 'DATE EXPIRATION');
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

echo '<div class="row">';

echo'<center>
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
echo"<h4><span class='glyphicon glyphicon-lock'></span> <b>Ajout de Produit</b> </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

?>

<form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">

<div class="form-group">
	<label for="reference">REFERENCE <font color="red">*</font></label>
	<input type="text" class="form-control" placeholder="Nom de la reference du Produit ici..."  name="designation" id="designation" value="" required />
</div>

<div class="form-group">
    <label for="categorie"> CATEGORIE </label>

    <select class="form-control" name="categorie2" id="categorie2">
		<option selected value= "Sans categorie">Sans categorie</option>
		 <?php
			$sql11="SELECT * FROM `". $nomtableCategorie."`";
			$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
			while($ligne2 = mysql_fetch_row($res11)) {
				echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

			  } ?>
	</select>
</div>


<div class="form-group">
  <label for="uniteStock"> UNITE STOCK (U.S.)<font color="red">*</font></label>
  <select class="form-control" name="uniteStock" id="uniteStock" required>
  <option></option>
     <?php
			$sql11="SELECT * FROM `aaa-unitestock`";
			$res11=mysql_query($sql11);
			while($ligne2 = mysql_fetch_row($res11)) {
				echo'<option class="lic" value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

			  } ?>
	</select>
</div>

<script>
$('#uniteStock').on('change', function() {
  var value = $(this).val();
  if(value =='Article'){
    document.getElementById('nbArticleUniteStock').value = 1;
    document.getElementById("nbArticleUniteStock").disabled = true;
    $("#div-nbArticleUniteStock").hide();
    //document.getElementById('prixuniteStock').value = $("#prixuniteStock").value();
    $("#div-prixuniteStock").hide();
  }
  else {
    $('#nbArticleUniteStock').val(null);
    document.getElementById("nbArticleUniteStock").disabled = false;
    $("#div-nbArticleUniteStock").show();
    //document.getElementById('prixuniteStock').value = "";
    $("#div-prixuniteStock").show();
  }

  //document.getElementById("nbArticleUniteStock").disabled = true;
  //alert(value);
});
</script>
 <div class="form-group" id="div-nbArticleUniteStock">
  <label for="nbArticleUniteStock">NOMBRE ARTICLE(S) U.S. <font color="red">*</font></label>
  <td><input type="number" class="form-control" placeholder="Nombre Article Unite Stock" id="nbArticleUniteStock" name="nbArticleUniteStock" value="" required />
</div>
<div class="form-group" id="div-prixuniteStock">
  <label for="prixuniteStock">PRIX UNITE STOCK</label>
  <input type="number" class="form-control" placeholder="Prix Unite Stock" id="prixuniteStock" name="prixuniteStock" value="" />
</div>
 <div class="form-group" >
  <label for="prix">PRIX UNITAIRE</label>
  <input type="number" class="form-control" placeholder="Prix Unitaire" id="prix" name="prix" value="" />
</div>
<div class="form-group" >
  <label for="prixachat">PRIX ACHAT</label>
  <input type="number" class="form-control" placeholder="Prix Achat" id="prixachat" name="prixachat" value="" />
</div>
<div class="form-group" >
  <label for="codeBarre">CODE BARRE</label>
  <input type="text" class="form-control" placeholder="Code Barre" id="codeBarre" name="codeBarre" value="" />
</div>
<?php
echo'<div class="modal-footer" align="right"><font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
	 <input type="hidden" name="classe" value="0" />
     <input type="button" onclick="ajt_Reference()" class="boutonbasic" name="inserer" value="AJOUTER  >>">';
echo'</form><br />'.
'</div></div></div></div></div>';

?>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/

echo'<div class="container">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">REFERENCES DES PRODUITS</a></li>';
if (($_SESSION['type']=="Multi-service") && ($_SESSION['categorie']=="Detaillant")) {
  echo'<li><a data-toggle="tab" href="#TRANSFERT">REFERENCES DES TRANSFERTS</a></li>';
}
echo'<li><a data-toggle="tab" href="#SERVICE">REFERENCES DES SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">REFERENCES DES DEPENCES</a></li>';
if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {
  echo'<li id="categorieEvent"><a data-toggle="tab" href="#CATEGORIE">REFERENCES DES CATEGORIES</a></li>';
}
echo'</ul><div class="tab-content">';

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

  echo'<div id="PRODUIT" class="tab-pane fade in active">';
    echo'<div class="table-responsive">
        <table id="tableDesignation" class="display tabDesign" class="tableau3" align="left" border="1">
        <thead>
          <tr id="thDesignation">
              <th>Ordre</th>
              <th>Reference</th>
              <th>CodeBarre</th>
              <th>Unite Stock (U.S)</th>
              <th>Nombre Articles U.S</th>
              <th>Prix U.S</th>
              <th>Prix Unitaire</th>
              <th>Prix Achat</th>
              <th>Operations</th>
          </tr>
        </thead>
        </table> '; ?>

        <script type="text/javascript">
        $(document).ready(function() {
            $("#tableDesignation").dataTable({
              "bProcessing": true,
              "sAjaxSource": "ajax/listerProduitAjax.php",
              "aoColumns": [
                    { mData: "0" } ,
                    { mData: "1" },
                    { mData: "2" },
                    { mData: "3" },
                    { mData: "4" },
                    { mData: "5" },
                    { mData: "6" },
                    { mData: "7" },
                    { mData: "8" },
                  ],
                  "dom": "Bfrtip",
                  "buttons" : [
                    "copy",
                    {
                      extend: "excel",
                      messageTop: "Liste des References  ",
                    },
                    {
                      extend: "pdf",
                      messageTop: "Liste des References ",
                      messageBottom: null
                    },
                    {
                      extend: "print",
                      text: "Imprimer",
                      messageTop: "Liste des References ",
                    }
                  ]
                  
            });  
        });
        </script>

        <?php
        echo '
          <div id="modifierCodeBModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modification code barre</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form" onsubmit="return false" >
                    <input type="hidden"  name="designation" id="ordre_CB" />
                    <input type="hidden"  name="designation" id="idDesignation_CB" />
                    <div class="form-group">
                      <label for="codeBD">CodeBarre Designation </label>
                      <input type="text" class="inputbasic form-control" id="codeBarreDesignation" autofocus=""   required />
                    </div>
                    <div class="form-group ">

                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="submit" id="btn_mdf_CodeBarre" class="btn_CodeDesign boutonbasic"  value=" Enregistrer >>" /></div>
                      <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div id="modifierDesignation"  class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="padding:35px 50px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'un Produit </b></h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                      <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">
                        <div class="form-group">
                          <label for="reference">REFERENCE <font color="red">*</font></label>
                          <input type="text" class="form-control" name="designation" id="designation_Mdf" required />
                        </div>
                        <div class="form-group">
                          <label for="categorie"> CATEGORIE <font color="red">*</font></label>
                          <select class="form-control" name="categorie2" id="categorie_Mdf">
                                <option selected ></option>';

                                    $sql11="SELECT * FROM `". $nomtableCategorie."`";
                                    $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                    while($ligne2 = mysql_fetch_row($res11)) {
                                        echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                      }
                            echo'</select>
                        </div>
                        <div class="form-group">
                          <label for="uniteStock"> UNITE STOCK <font color="red">*</font></label>

                          <select class="form-control" name="uniteStock" id="uniteStock_Mdf">
                              <option selected ></option>';

                              $sql11="SELECT * FROM `aaa-unitestock`";
                              $res11=mysql_query($sql11);
                              while($ligne2 = mysql_fetch_row($res11)) {
                                  echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                }
                          echo'</select>
                        </div>
                        <div class="form-group" >
                          <label for="nbArticleUniteStock_Mdf">NOMBRES ARTICLES UNITE STOCK</label>
                          <input type="number" class="form-control" id="nbArticleUniteStock_Mdf" name="nbArticleUniteStock"  />
                        </div>
                        <div class="form-group" >
                          <label for="prixuniteStock">PRIX UNITE STOCK</label>
                          <input type="number" class="form-control" id="prixuniteStock_Mdf" name="prixuniteStock"  />
                        </div>
                        <div class="form-group" id="div-prixuniteDetails">
                          <label for="prixachat">PRIX UNITAIRE</label>
                          <input type="number" class="form-control" placeholder="Prix Unite Details" id="prix_Mdf" name="prix" />
                        </div>
                        <div class="form-group" id="div-prixuniteDetails">
                          <label for="prixachat">PRIX ACHAT</label>
                          <input type="number" class="form-control" placeholder="Prix Unite Details" id="prixachat_Mdf" name="prixachat" />
                        </div>
                        <div class="form-group" align="right">
                                              <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                                                <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                                <input type="hidden" id="idDesignation_Mdf" name="idDesignation" />
                              <input type="hidden" name="modifier" value="1"/>
                              <input type="hidden" name="designationAmodifier" />
                          </div>
                      </form>
                  </div>
                </div>
            </div>
          </div>

          <div id="supprimerDesignation" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="padding:35px 50px;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"> </span> <b>Confirmation Suppression d\'un Produit</b></h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                    <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">
                      <div class="form-group">
                        <label for="reference">REFERENCE </label>
                        <input type="text" class="form-control" name="designation" id="designation_Spm" disabled=""/>
                      </div>
                      <div class="form-group">
                        <label for="categorie"> CATEGORIE </label>

                        <select class="form-control" name="categorie2" id="categorie_Spm" disabled="">
                              <option selected ></option>';

                                  $sql11="SELECT * FROM `". $nomtableCategorie."`";
                                  $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                  while($ligne2 = mysql_fetch_row($res11)) {
                                      echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                    }
                          echo'</select>
                      </div>
                      <div class="form-group">
                        <label for="uniteStock"> UNITE STOCK </label>
                          <select class="form-control" name="uniteStock" id="uniteStock_Spm" disabled="">
                            <option selected ></option>';

                            $sql11="SELECT * FROM `aaa-unitestock`";
                            $res11=mysql_query($sql11);
                            while($ligne2 = mysql_fetch_row($res11)) {
                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                              }
                          echo'</select>
                      </div>
                      <div class="form-group" >
                        <label for="prixuniteStock">PRIX UNITE STOCK</label>
                        <input type="number" class="form-control" disabled="" id="prixuniteStock_Spm" name="prixuniteStock" />
                      </div>
                      <div class="form-group" >
                        <label for="prix">PRIX UNITAIRE</label>
                        <input type="number" class="form-control" disabled="" id="prix_Spm" name="prix"  />
                      </div>
                      <div class="form-group" >
                          <label for="prixachat">PRIX ACHAT</label>
                          <input type="number" class="form-control" disabled="" id="prixachat_Spm" name="prixachat"  />
                        </div>
                      <div class="form-group" align="right">
                          <font color="red"><b>Voulez-vous supprimer ce Produit ? </b></font><br /><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                          <input type="hidden" name="idDesignation" id="idDesignation_Spm" />
                        <input type="hidden" name="supprimer" value="1" />
                      </div>
                    </form>
                </div>
                </div>
            </div>
          </div>

    </div>
  </div>';

  if (($_SESSION['type']=="Multi-service") && ($_SESSION['categorie']=="Detaillant")) {
  $sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
  $res=mysql_query($sql);
  echo'<div id="TRANSFERT" class="tab-pane fade">';
    echo '<br /><div class="container">'; 
    ?>
    <div class="row">
      <form class="form-inline noImpr pull-right"  target="_blank" style="padding-bottom:10px;margin-right:20px;"
        method="post" action="pdfCodeBarreTransfert.php" >
        <button class="btn btn-warning " >
          <span class="glyphicon glyphicon-barcode"></span> Impression
        </button>
      </form>
    </div>
    <?php 
        echo'<div class="table-responsive"><table id="exemple" class="display" width="100%" align="left" border="1">';
        echo'<thead>
          <tr><th>ORDRE</th>
          <th>REFERENCE</th>
            <th>DEPOT</th>
            <th>RETRAIT</th>
              <th>FACTURE</th>
              <th>OPERATION</th>
          </tr>
        </thead>
        <tfoot>
          <tr><th>ORDRE</th>
          <th>REFERENCE</th>
          <th>DEPOT</th>
          <th>RETRAIT</th>
            <th>FACTURE</th>
            <th>OPERATION</th>
          </tr>
        </tfoot>
    <tbody>';

    $reqT="SELECT * from `aaa-transaction` where typeTransaction='Transaction' AND idTransaction!=0 ";
    $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());
    if(mysql_num_rows($resT)){
      $i=0;
        while($tab=mysql_fetch_array($resT)){
          $i=$i+1;
          echo'<tr><td>'.$i.'</td>';
          echo'<td><center><img src="images/'.$tab['aliasTransaction'].'.png" width="50" height="50" border="2" class="img-circle"></center> </td>';
          $sql0="SELECT * FROM `".$nomtableDesignation."` where  classe = 3 and description=".$tab['idTransaction'];
          $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());
          if(mysql_num_rows($res0)){
            $transfert=mysql_fetch_array($res0);
            $sqlD="SELECT *
            FROM `".$nomtableDesignation."`
            WHERE classe = 3 AND designation='".$transfert['designation']."' AND uniteStock='Depot'  ";
            $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
            $depot = mysql_fetch_array($resD);
            if($depot['seuil']==1){
              echo '
              <td>
                <form  method="POST" >
                  <input type="hidden" name="idDesignation" value='.$depot['idDesignation'].' >
                  <button type="submit"  name="desactiverT" class="btn btn-danger pull-right">Desactiver</button>
                </form>
              </td>
              ';
            }
            else{
              echo '
              <td>
                <form  method="POST" >
                  <input type="hidden" name="idDesignation" value='.$depot['idDesignation'].' >
                  <button type="submit"  name="activerT" class="btn btn-success pull-right">Activer</button>
                </form>
              </td>
              ';
            }

            $sqlR="SELECT *
            FROM `".$nomtableDesignation."`
            WHERE classe = 3 AND designation='".$transfert['designation']."' AND uniteStock='Retrait'  ";
            $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());
            $retrait = mysql_fetch_array($resR);
            if($retrait['seuil']==1){
              echo '
              <td>
                <form  method="POST" >
                  <input type="hidden" name="idDesignation" value='.$retrait['idDesignation'].' >
                  <button type="submit"  name="desactiverT" class="btn btn-danger pull-right">Desactiver</button>
                </form>
              </td>
              ';
            }
            else{
              echo '
              <td>
                <form  method="POST" >
                  <input type="hidden" name="idDesignation" value='.$retrait['idDesignation'].' >
                  <button type="submit"  name="activerT" class="btn btn-success pull-right">Activer</button>
                </form>
              </td>
              ';
            }
        
            $sqlF="SELECT *
            FROM `".$nomtableDesignation."`
            WHERE classe = 3 AND designation='".$transfert['designation']."' AND uniteStock='Facture'  ";
            $resF = mysql_query($sqlF) or die ("persoonel requête 2".mysql_error());
            $facture = mysql_fetch_array($resF);
            if($facture['seuil']==1){
              echo '
              <td>
                <form  method="POST" >
                  <input type="hidden" name="idDesignation" value='.$facture['idDesignation'].' >
                  <button type="submit"  name="desactiverT" class="btn btn-danger pull-right">Desactiver</button>
                </form>
              </td>
              ';
            }
            else{
              echo '
              <td>
                <form  method="POST" >
                  <input type="hidden" name="idDesignation" value='.$facture['idDesignation'].' >
                  <button type="submit"  name="activerT" class="btn btn-success pull-right">Activer</button>
                </form>
              </td>
              ';
            }
            echo '
              <td>
                  <button type="submit" disabled="true"  name="ajouterT" class="btn btn-warning pull-right">Activer</button>
              </td>
            ';
          }
          else{
            echo '
            <td><button type="submit" disabled="true"  class="btn btn-warning pull-right">Inactif</button></td>
            <td><button type="submit" disabled="true"  class="btn btn-warning pull-right">Inactif</button></td>
            <td><button type="submit" disabled="true"  class="btn btn-warning pull-right">Inactif</button></td>
            <td>
            <form  method="POST" >
              <input type="hidden" name="idTransaction" value='.$tab['idTransaction'].' >
              <button type="submit"  name="ajouterT" class="btn btn-success pull-right">Activer</button>
            </form>
            </td>
          ';
          }

        }
    }

    echo'</tbody></table></div>
    </div>
  </div>';
  }
     
  $sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
  $res=mysql_query($sql);
  echo'<div id="SERVICE" class="tab-pane fade">';
    echo '<br /><div class="container">
      <button type="button" class="btn btn-success pull-left" data-toggle="modal" data-target="#AjoutStockModal1" data-dismiss="modal" id="AjoutStock">
        <i class="glyphicon glyphicon-plus"></i>Ajout de Service </button>'; 
        
        ?>
        <div class="row">
          <form class="form-inline noImpr pull-right"  target="_blank" style="padding-bottom:10px;margin-right:20px;"
            method="post" action="pdfCodeBarreService.php" >
            <button class="btn btn-warning " >
              <span class="glyphicon glyphicon-barcode"></span> Impression
            </button>
          </form>
        </div>
        <div class="modal fade" id="AjoutStockModal1" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" >';
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Service </h4>
                <br />
              </div>
              <div class="modal-body">
              <br />
                <form name="formulaire2" id="ajouterServiceForm" method="post" action="insertionProduit.php">
                  <div class="form-group">
                    <label for="reference">REFERENCE <font color="red">*</font></label>
                    <input type="text" class="form-control" placeholder="Nom de la reference du Service ici..."  name="designation" id="designationSD" value="" required />
                  <input type="hidden" name="idDesignation" value="" id="idD" >
                    <div id="reponseSD"></div>
                  </div>

                  <div class="form-group" id="div-uniteService">
                  <label for="uniteService">UNITE SERVICE<font color="red">*</font></label>
                  <input type="text" class="form-control" placeholder="Unité du Service ici ..." id="uniteService" name="uniteService" value="" required />
                  </div>

                  <div class="form-group" id="div-prixSF">
                  <label for="prix">PRIX UNITE SERVICE<font color="red">*</font></label>
                  <input type="number" class="form-control" placeholder="Prix du Service ici ..." id="prixSF" name="prixSF" value=""  required/>
                  </div>

                  <div class="modal-footer" align="right">
                    <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                    <input type="hidden" name="classe" value="1" />
                    <input type="submit" class="boutonbasic" name="inserer" value="AJOUT SERVICE >>" />
                  </div>
                </form><br />
              </div>
            </div>
          </div>
        </div>


        <?php
        echo'<div class="table-responsive"><table id="exemple2" class="display" width="100%" align="left" border="1">';
            echo'<thead>
              <tr><th>ORDRE</th>
              <th>REFERENCE</th>
                <th>MONTANT</th>
                <th>UNITE SERVICE</th>
                  <th>OPERATIONS</th>
              </tr>
            </thead>
            <tfoot>
              <tr><th>ORDRE</th>
              <th>REFERENCE</th>
                <th>MONTANT</th>
              <th>UNITE SERVICE</th>
                <th>OPERATIONS</th>
              </tr>
            </tfoot>
        <tbody>';

        if(mysql_num_rows($res)){
          $i=0;
            while($tab=mysql_fetch_array($res)){

              if($tab["classe"]==1 && $tab['uniteStock']!='Transaction' && $tab['uniteStock']!='Facture' && $tab['uniteStock']!='Credit') {
                $i=$i+1;
                echo'<tr><td>'.$i.'</td>';
                echo'<td>'.$tab["designation"].'</td>
                      <td align="right">'.($tab["prix"] * $_SESSION['devise']).' '.$_SESSION['symbole'].'</td>
                      <td align="right">'.$tab["uniteStock"].'</td>
                <td>
                <a href="#" >
                    <img src="images/edit.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgmodifierSe'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
                <a   href="#" >
                    <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" data-target="#imgsupSe'.$tab["idDesignation"].'"/></a>
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

                                        <div class="form-group">
                                          <label for="reference">REFERENCE <font color="red">*</font></label>
                                          <input type="text" class="form-control" name="designation" id="designation" value="'.$tab["designation"].'" required />
                                        </div>

                                        <div class="form-group" id="div-uniteService">
                                          <label for="uniteService">UNITE SERVICE</label>
                                          <input type="text" class="form-control" id="uniteService" name="uniteService" value="'.$tab["uniteStock"].'" />
                                        </div>

                                        ';
                                        echo'<div class="form-group" id="div-prixSF">
                                          <label for="prix">PRIX UNITE SERVICE<font color="red">*</font></label>
                                          <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prix"].'" required />
                                        </div>';
                                        echo'

                                      <div class="modal-footer row">
                                          <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                        <input type="hidden" name="modifier" value="1"/>
                                        <input type="hidden" name="classe" value="1" />
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

                                      <div class="form-group">
                                          <label for="reference">REFERENCE <font color="red">*</font></label>
                                          <input type="text" class="form-control" name="designation" id="designation" value="'.$tab["designation"].'" disabled />
                                        </div>

                                        <div class="form-group" id="div-uniteService">
                                          <label for="uniteService">UNITE SERVICE</label>
                                          <input type="text" class="form-control" id="uniteService" name="uniteService" value="'.$tab["uniteStock"].'" disabled />
                                        </div>

                                        ';

                                        echo'<div class="form-group" id="div-prixSF">
                                          <label for="prix">PRIX UNITE SERVICE<font color="red">*</font></label>
                                          <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prix"].'" disabled />
                                        </div>

                                        ';

                                        echo'

                                        <div class="modal-footer row">
                                              <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>'.
                                              '<input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                              <input type="hidden" name="classe" value="1" />
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
        }

        echo'</tbody></table></div><br />
    </div>
  </div>';

  $sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
  $res=mysql_query($sql);
  echo'<div id="FRAIS" class="tab-pane fade">';

    echo'<br /><div class="container"><button type="button" class="btn btn-success pull-left" data-toggle="modal" data-target="#AjoutStockModal2" data-dismiss="modal" id="AjoutStock">
        <i class="glyphicon glyphicon-plus"></i>Ajout de dépence</button>';?>

        <div class="row">
        <form class="form-inline noImpr pull-right"  target="_blank" style="padding-bottom:10px;margin-right:20px;"
          method="post" action="pdfCodeBarreDepense.php" >
          <button class="btn btn-warning " >
            <span class="glyphicon glyphicon-barcode"></span> Impression
          </button>
        </form>
      </div>
      <?php
        echo'<div class="modal fade" id="AjoutStockModal2" role="dialog">';
        echo'<div class="modal-dialog">';
        echo'<div class="modal-content">';
        echo'<div class="modal-header" style="padding:35px 50px;">';
        echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
        echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de dépence </h4>";
        echo'</div>';
        echo'<div class="modal-body" style="padding:40px 50px;">';

        echo'<form role="form" class="" id="form" name="formulaire2" method="post" action="insertionProduit.php">';?>

        <div class="form-group">
          <label for="reference">REFERENCE <font color="red">*</font></label>
          <input type="text" class="form-control" placeholder="Nom de la reference de la depence ici..."  name="designation" id="designation" value="" required />
        </div>

        <div class="form-group" id="div-uniteDepence">
          <label for="uniteDepence">UNITE DEPENCE <font color="red">*</font></label>
          <input type="text" class="form-control" placeholder="Unité de la dépence ici ..." id="uniteDepence" name="uniteDepence" value=""  required/>
        </div>

        <div class="form-group" id="div-prixF">
          <label for="prix">PRIX <font color="red">*</font></label>
          <input type="number" class="form-control" placeholder="Prix de la depence ici ..." id="prixSF" name="prixSF" value="" required />
        </div>


        <!--div class="form-group" >
          <label for="pourcentage">POURCENTAGE</label>
          <input type="number" min ="0" max ="100" class="form-control" placeholder="un nombre dans [0,100]" id="pourcentage" name="pourcentage" value="100" />
        </div-->

        <?php
        echo'<div class="modal-footer" align="right"><font color="red">Les champs qui ont (*) sont obligatoires</font><br />

          <input type="hidden" name="classe" value="2" />
          <input type="submit" class="boutonbasic" name="inserer" value="AJOUT DEPENSE >>">'.


        '</div>';
        echo'</form><br />'.
        '</div></div></div></div>
        </div>';

      //echo'<br><br>';



      echo'<div class="table-responsive"><table id="exemple3" class="display" width="100%" align="left" border="1">';
      echo'<thead>
          <tr><th>ORDRE</th>
          <th>REFERENCE</th>
          <th>MONTANT</th>
          <th>UNITE DEPENSE</th>
          <th>OPERATIONS</th>
          </tr>
      </thead>
      <tfoot>
        <tr><th>ORDRE</th>
        <th>REFERENCE</th>
        <th>MONTANT</th>
        <th>UNITE DEPENSE</th>
        <th>OPERATIONS</th>
        </tr>
      </tfoot>
      <tbody>';
      //echo'<table class="tableau3" width="80%" align="left" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>CLASSE</th><th></th>';
      if(mysql_num_rows($res)){
          $i=0;
          while($tab=mysql_fetch_array($res)){

            if($tab["classe"]==2){
              $i=$i+1;
              echo'<tr><td>'.$i.'</td>';
              echo'<td>'.$tab["designation"].'</td><td align="right">'.($tab["prix"] * $_SESSION['devise']).' '.$_SESSION['symbole'].'</td><td align="right">'.$tab["uniteStock"].'</td>
              <td>
              <a href="#" >
                  <img src="images/edit.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgmodifierFr'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
              <a href="#" >
                  <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" data-target="#imgsupFr'.$tab["idDesignation"].'"/></a>
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

                                    <div class="form-group">
                                      <label for="reference">REFERENCE <font color="red">*</font></label>
                                      <input type="text" class="form-control" name="designation" id="designation" value="'.$tab["designation"].'" required />
                                    </div>

                                    <div class="form-group" id="div-uniteDepence">
                                      <label for="uniteDepence">UNITE DEPENCE <font color="red">*</font></label>
                                      <input type="text" class="form-control" id="uniteDepence" name="uniteDepence" value="'.$tab["uniteStock"].'" required/>
                                    </div>';
                                    echo'<div class="form-group" id="div-prixF">
                                      <label for="prix">PRIX <font color="red">*</font></label>
                                      <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prix"].'" required />
                                    </div>
                                  <div class="modal-footer row">
                                      <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                      <input type="hidden" name="modifier" value="1"/>
                                      <input type="hidden" name="classe" value="2" />
                                      <input type="submit" class="boutonbasic" name="btnModifier" value="MODIFIER  >>"/>
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
                                  <h4 class="modal-title">Formulaire pour supprimer une désignation de Dépence</h4>
                              </div>
                              <div class="modal-body">

                                <form class="formulaire2" name="formulaire2" method="post" action="insertionProduit.php">

                                      <div class="form-group">
                                        <label for="reference">REFERENCE <font color="red">*</font></label>
                                        <input type="text" class="form-control" name="designation" id="designation" value="'.$tab["designation"].'" disabled />
                                      </div>

                                      <div class="form-group" id="div-uniteDepence">
                                        <label for="uniteDepence">UNITE DEPENCE <font color="red">*</font></label>
                                        <input type="text" class="form-control" id="uniteDepence" name="uniteDepence" value="'.$tab["uniteStock"].'" disabled/>
                                      </div>';
                                      echo'<div class="form-group" id="div-prixF">
                                        <label for="prix">PRIX <font color="red">*</font></label>
                                        <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prix"].'" disabled />
                                      </div>


                                      <div class="modal-footer row">
                                            <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>'.
                                            '<input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                            <input type="hidden" name="classe" value="2" />
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
      }

      echo'</tbody></table><br />
    </div>
  </div>';

  if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {
    echo'<div id="CATEGORIE" class="tab-pane fade">
        <div class="table-responsive">
            <table id="tableCategorie" class="display tabCategorie" class="tableau3"  width="100%" align="left" border="1">
            <thead>
              <tr id="thDesignation">
                  <th>Ordre</th>
                  <th>Reference</th>
                  <th>Categorie</th>
                  <th>Sous Categorie</th>
                  <th>Unite Stock</th>
                  <th>Nombre Article U.S</th>
                  <th>Prix U.S</th>
                  <th>Prix </th>
                  <th>Prix Achat</th>
                  <th>Operations</th>
              </tr>
            </thead>
            </table> '; ?>

            <script type="text/javascript">
            $(document).ready(function() {
              $("#categorieEvent").click(function () {
                $("#tableCategorie").dataTable({
                  "bProcessing": true,
                  "destroy": true,
                  "sAjaxSource": "ajax/listerProduit-CategorieAjax.php",
                  "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" } ,
                        { mData: "6" },
                        { mData: "7" },
                        { mData: "8" },
                        { mData: "9" },
                      ],
                      "dom": "Bfrtip",
                      "buttons" : [
                        "copy",
                        {
                          extend: "excel",
                          messageTop: "Liste des References  ",
                        },
                        {
                          extend: "pdf",
                          messageTop: "Liste des References ",
                          messageBottom: null
                        },
                        {
                          extend: "print",
                          text: "Imprimer",
                          messageTop: "Liste des References ",
                        }
                      ]
                      
                }); 
                $.ajax({
                    url: "ajax/operationAjax_Categorie.php",
                    method: "POST",
                    data: {
                        operation: 2,
                    },
                    success: function (data) {
                        var data = JSON.parse(data);
                        var taille = data.length;
                              for( var i = 0; i<taille; i++){
                                  var tab = data[i].split('<>');
                                  name=tab[1];
                                  $('.categorieSelection').append("<option value='"+name+"'>"+name+"</option>");
                              }
                    },
                    error: function() {
                        alert("La requête 3"); },
                    dataType:"text"
                }); 
              });
            });
            </script>

            <?php
            echo '
        </div>
    </div>';
}

      /* Debut PopUp d'Alerte sur l'ensemble de la Page **/
        echo'<div id="ajt_Stock" class="modal fade " role="dialog">
          <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                  <div class="modal-header panel-primary">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Ajouter Stock</h4>
                  </div>
                  <div class="modal-body">
                      <form role="form" class="" >
                          <div class="form-group">
                              <label for="reference">REFERENCE <font color="red">*</font></label>
                              <input type="text" class="form-control" name="designation" id="designation_Stock"  disabled="true" />
                          </div>
                          <div class="form-group">
                          <label for="forme"> Unite Stock <font color="red">*</font></label>
                            <select class="form-control" name="uniteStock" id="uniteStock_Stock" required>
                              <option id="uniteStock_Stock_Option"></option> 
                              <option>Article</option> 
                            </select>
                          </div>
                          <div class="form-group" >
                          <label for="forme"> Quantite <font color="red">*</font></label>
                          <input type="text" class="form-control" name="qteInitial" id="qteInitial_Stock" required />
                          </div>
                          <div class="form-group" >
                          <label for="tableau"> Date Expiration <font color="red">*</font></label>
                          <input type="date" class="form-control" name="dateExpiration" id="dateExpiration_Stock"  required  />
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-success pull-left" style="margin-right:20px;" id="btn_trm_StockCatalogue" >
                          Terminer
                      </button>
                      <button type="button" class="btn btn-primary pull-left" style="margin-right:20px;" id="btn_ajt_StockCatalogue" >
                          Ajouter >> 
                      </button>
                      <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" >
                          Fermer
                      </button>
                  </div>
                  </div>
            </div>
        </div>';
    /** Fin PopUp d'Alerte sur l'ensemble de la Page **/


echo'</section>'.
'<script>$(document).ready(function(){$("#imgmodifier").click(function(){$("#ModifierDesignationModal").modal();});});</script>'.
'<script>$(document).ready(function(){$("#imgsup").click(function(){$("#supprimerDesignationModal").modal();});});</script>'.
'</div></div></body></html>';
}
}

?>
