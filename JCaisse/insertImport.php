<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/ 
var_dump($_SESSION['idBoutique']);
$idDesignation =@$_POST["idDesignation"];
$prixuniteStock      =@$_POST["prixuniteStock"];
$uniteStock      =@$_POST["uniteStock"];
$prixachat      =@$_POST["prixachat"];

if (isset($_POST['btnConfPrixByCategory'])) {
  $ConfPrix =@$_POST["ConfPrix"];
  $confCategory =@$_POST["confCategory"];

  $sql="UPDATE `".$nomtableDesignation."` set prixuniteStock=prixachat+(prixachat*".$ConfPrix."/100) WHERE categorie='".$confCategory."'";
  $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

}
 

if(!$annuler){
  if(!$modifier and !$supprimer){
    if($designation){
        if($classe==0){
          $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";
          $res11=mysql_query($sql11);
          if(!mysql_num_rows($res11)){
            $sql="insert into `".$nomtableDesignation."` (designation,classe,uniteStock,prixuniteStock,nbreArticleUniteStock,uniteDetails,prixachat,codeBarreDesignation,categorie)values ('".mysql_real_escape_string($designation)."',0,'".mysql_real_escape_string($uniteStock)."','".$prixuniteStock."','".$nbArticleUniteStock."','".mysql_real_escape_string($uniteDetails)."','".$prixachat."','".mysql_real_escape_string($codeBarre)."','".mysql_real_escape_string($categorie2)."')";
            $res=@mysql_query($sql) or die ("insertion impossible Produit en uniteStock".mysql_error());
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
              }
              
              
            }
            else if($classe==2){
              $sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".mysql_real_escape_string($designation)."',2,".$prixSD.",'".$uniteDepence."','".$prixSD."','1','10','".mysql_real_escape_string($categorie2)."')";
              $res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
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
 
              $sql="UPDATE `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie2)."',uniteStock='".mysql_real_escape_string($uniteStock)."',prixuniteStock='".$prixuniteStock."' WHERE idDesignation=".$idDesignation;
              $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

              $sql2="update `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
              //echo $sql2;
              $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

              $sql3="update `".$nomtableEntrepotStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
              //echo $sql2;
              $res3=@mysql_query($sql3)or die ("modification reference dans stock ".mysql_error());

              $sql4="update `".$nomtableLigne."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;
              //echo $sql2;
              $res4=@mysql_query($sql4)or die ("modification reference dans stock ".mysql_error());

              
  }
  else if ($supprimer) {

      $sql="DELETE FROM `".$nomtableStock."` WHERE idDesignation=".$idDesignation;
      $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());

      $sql1="DELETE FROM `".$nomtableEntrepotStock."` WHERE idDesignation=".$idDesignation;
      $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());

      $sql2="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation;
      $res2=@mysql_query($sql2) or die ("suppression impossible designation".mysql_error());

  }
}
if (isset($_POST['subImport1'])) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $headers = fgetcsv($handle, 1000, ";");


    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

      $data = array_map("utf8_encode", $data);

      $reference=htmlspecialchars(trim($data[0]));
      $categorie=htmlspecialchars(trim($data[1]));
      $uniteS=htmlspecialchars(trim($data[2]));
      $nbreAuniteS=$data[3];
      $prixU=$data[4];
      $prixUS=$data[5];
      $prixA=$data[6];
      $quantite=$data[7];
      $expiration=$data[8];
      $depot=$data[9];
      //$classe=$data[6];
      $sql10="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";
      $res10=mysql_query($sql10);
      if(!mysql_num_rows($res10)){
        $sql3="insert into `".$nomtableDesignation."`(designation,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,prixachat,categorie,classe)
        values('".mysql_real_escape_string($reference)."','".mysql_real_escape_string($uniteS)."',".$nbreAuniteS.",".$prixU.",".$prixUS.",".$prixA.",'".mysql_real_escape_string($categorie)."',0)";
        //var_dump($sql);
        $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());
      }
      if($quantite!=null || $quantite!=''){
        $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";
        $res11=mysql_query($sql11);
        if(mysql_num_rows($res11)){
          $produit=mysql_fetch_array($res11);
          $totalArticleStock=$quantite*$nbreAuniteS;
          $sql3='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 
          VALUES('.$produit['idDesignation'].',"'.mysql_real_escape_string($produit['designation']).'",'.$quantite.',"'.mysql_real_escape_string($produit['uniteStock']).'",'.$prixUS.','.$nbreAuniteS.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$expiration.'","'.$_SESSION['iduser'].'")';
          $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());

          if($depot!=null || $depot!=''){
            $sql13="SELECT * FROM `". $nomtableStock."` where designation='".mysql_real_escape_string($reference)."'";
            $res13=mysql_query($sql13);
            if(mysql_num_rows($res13)){
              $stock=mysql_fetch_array($res13);
              $sql4='INSERT INTO `'.$nomtableEntrepotStock.'`(idDesignation,idStock,idEntrepot,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 
              VALUES('.$produit['idDesignation'].','.$stock['idStock'].',"'.mysql_real_escape_string($depot).'","'.mysql_real_escape_string($produit['designation']).'",'.$quantite.',"'.mysql_real_escape_string($produit['uniteStock']).'",'.$prixUS.','.$nbreAuniteS.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$expiration.'","'.$_SESSION['iduser'].'")';
              $res4=@mysql_query($sql4) or die ("insertion reference CSV impossible".mysql_error());
            }
          }

        }
      }
      $sql12="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($categorie)."'";
      $res12=mysql_query($sql12);
      if(!mysql_num_rows($res12))
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
      $fields=array('REFERENCE','CATEGORIE', 'UNITE-STOCK','NBRES-ARTICLES-UNITE-STOCK',
                    'PRIX-UNITAIRE', 'PRIX-UNITE-STOCK', 'PRIX-ACHAT', 'QUANTITE-STOCK', 'DATE EXPIRATION', 'ENTREPOT');
      fputcsv($output,$fields, $delimiter );
      fclose($output); exit;

}


/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body>
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

/**************** BOUTTON AjoutDesignation ET TFENETRE NODAL ASSOCIEE  *************/

echo '<div class="row">';
if ($_SESSION['enConfiguration']==0){
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
}else{
	echo'<center><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
			<i class="glyphicon glyphicon-plus"></i>Ajout de Produit dans le catalogue</button></center>';
}

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
<div class="form-group">
  <label for="uniteDetails"> UNITE DETAILS (U.D.)<font color="red">*</font></label>
  <select class="form-control" name="uniteDetails" id="uniteDetails" required>
    <option></option>
    <?php
			$sql11="SELECT * FROM `aaa-uniteDetail`";
			$res11=mysql_query($sql11);
			while($ligne2 = mysql_fetch_row($res11)) {
				echo'<option class="lic" value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

			  } ?>
	</select>
</div>

<div class="form-group" id="div-nbArticleUniteStock">
  <label for="nbArticleUniteStock">NOMBRE ARTICLE(S) U.S. <font color="red">*</font></label>
  <td><input type="number" class="form-control" placeholder="Nombre Article de l'Unite Stock" id="nbArticleUniteStock" name="nbArticleUniteStock" value="" required />
</div>

<div class="form-group" id="div-prixuniteStock">
  <label for="prixuniteStock">PRIX UNITE STOCK VENTE</label>
  <input type="number" class="form-control" placeholder="Prix Unite Stock" id="prixuniteStock" name="prixuniteStock" value="" />
</div>

<div class="form-group" id="div-prixuniteDetails">
  <label for="prixachat">PRIX UNITE STOCK ACHAT</label>
  <input type="number" class="form-control" placeholder="Prix Achat" id="prixachat" name="prixachat" value="" />
</div>
<?php
echo'<div class="modal-footer" align="right"><font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
	 <input type="hidden" name="classe" value="0" />
     <input type="button" onclick="ajt_Reference_E()" class="boutonbasic" name="inserer" value="AJOUTER  >>">';
echo'</form><br />'.
'</div></div></div></div></div>';

?>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/

if ($_SESSION['configPrix']==1) {

  echo'<div class="container">
    <form class="form" method="post" align="center" >
    
      <div class="form-group">
        <label for="ConfPrix">Calibrage prix</label>
      </div>
      <div class="form-group">
      <label for="ConfPrix">Calibrage prix</label>&nbsp;&nbsp;
        <select class="" width="100px" height="15px" id="ConfPrix" name="ConfPrix" required>
          <option></option>
          <optgroup label="Pourcentage de vente">
              <option value="5">5 %</option>
              <option value="10">10 %</option>
              <option value="15">15 %</option> 
              <option value="20">20 %</option> 
              <option value="25">25 %</option> 
              <option value="30">30 %</option> 
          </optgroup>       
        </select>    
        &nbsp;&nbsp;   
        <label for="ConfPrix">Catégorie</label>&nbsp;&nbsp;
        <select class="" name="confCategory" id="confCategory" required>
          <option></option>';
            $sql11="SELECT * FROM `".$nomtableCategorie."` order by nomCategorie";
            $res11=mysql_query($sql11);
            while($c = mysql_fetch_row($res11)) {
              echo'<option class="lic" value= "'.$c[1].'">'.strtoupper($c[1]).'</option>';
            }

      echo '</select>&nbsp;&nbsp;
        <button type="submit" name="btnConfPrixByCategory" class="btn btn-primary btn-xs glyphicon glyphicon-ok"> Valider</button>
      </div>
    </form>
  </div>';
}

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
  echo'<div class="table-responsive">
    <label class="pull-left" for="nbEntreeEt">Nombre entrées </label>
    <select class="pull-left" id="nbEntreeEt">
    <optgroup>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="50">50</option> 
    </optgroup>       
    </select>
    <input class="pull-right" type="text" name="" id="searchInputEt" placeholder="Rechercher...">
    <div id="resultsProductsE"><!-- content will be loaded here --></div>

    <div id="modifierDesignation"  class="modal fade " role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'un Produit </b></h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
                <form role="form" class="" method="post" >
                  <input type="hidden" class="form-control" name="ordre" id="ordre_Mdf" required />
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
                    <label for="prixuniteStock">PRIX UNITE STOCK VENTE</label>
                    <input type="number" class="form-control" id="prixuniteStock_Mdf" name="prixuniteStock"  />
                  </div>
                  <div class="form-group" id="div-prixuniteDetails">

                    <label for="prixachat">PRIX UNITAIRE</label>

                    <input type="number" class="form-control" step="0.01" placeholder="Prix Unite Details" id="prix_Mdf" name="prix" />

                  </div>
                  <div class="form-group" id="div-prixuniteDetails">
                    <label for="prixachat">PRIX UNITE STOCK ACHAT</label>
                    <input type="number" class="form-control" placeholder="Prix Unite Details" id="prixachat_Mdf" name="prixachat" />
                  </div>
                  <div class="form-group" align="right">
                                        <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                                          <input type="button" id="btn_mdf_Reference_ET" class="boutonbasic"   name="btnModifier" value="MODIFIER  >>"/>
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
                    <label for="prixuniteStock">PRIX UNITE STOCK VENTE</label>
                    <input type="number" class="form-control" disabled="" id="prixuniteStock_Spm" name="prixuniteStock" />
                  </div>
                  <div class="form-group" >
                    <label for="prixachat">PRIX UNITE STOCK ACHAT</label>
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

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);
echo'<div id="SERVICE" class="tab-pane fade">';
echo '<br /><div class="container">
<center><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal1" data-dismiss="modal" id="AjoutStock">
<i class="glyphicon glyphicon-plus"></i>Ajout de Service </button></center>'; ?>

<div class="modal fade" id="AjoutStockModal1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
			<div class="modal-header" >';
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Service </h4>
			</div>
        <br>
		<div class="bs-example bs-example-tabs">
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#service" data-toggle="tab">Services</a></li>
			</ul>
		</div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
			<div class="tab-pane fade active in" id="service">
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
      <div class="modal-footer">
       <center>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </center>
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

      if($tab["classe"]==1) {
        $i=$i+1;
        echo'<tr><td>'.$i.'</td>';
        echo'<td>'.$tab["designation"].'</td>
              <td align="right">'.$tab["prixuniteStock"].'</td>
              <td align="right">'.$tab["uniteStock"].'</td>
        <td>
        <a href="#" >
            <img src="images/edit.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgmodifierSe'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
        <a   href="#" >
            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" data-target="#imgsupSe'.$tab["idDesignation"].'"/></a>
            <a href="codeBarreSerFr.php?idD='.$tab["idDesignation"].'">Détails</a>
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
                                  <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prixuniteStock"].'" required />
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
                                  <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prixuniteStock"].'" disabled />
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

echo'</tbody></table></div><br /></div></div>';

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

echo'<div id="FRAIS" class="tab-pane fade">';



echo'<br /><div class="container"><center><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal2" data-dismiss="modal" id="AjoutStock">
<i class="glyphicon glyphicon-plus"></i>Ajout de dépence</button></center>';




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
'</div></div></div></div></div>';

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
        echo'<td>'.$tab["designation"].'</td><td align="right">'.$tab["prixuniteStock"].'</td><td align="right">'.$tab["uniteStock"].'</td>
        <td>
        <a href="#" >
            <img src="images/edit.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgmodifierFr'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
        <a href="#" >
            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" data-target="#imgsupFr'.$tab["idDesignation"].'"/></a>
            <a href="codeBarreSerFr.php?idD='.$tab["idDesignation"].'">Détails</a>
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
                                <input type="text" class="form-control" id="uniteDepence" name="uniteStock" value="'.$tab["uniteStock"].'" required/>
                              </div>';
                              echo'<div class="form-group" id="div-prixF">
                                <label for="prix">PRIX <font color="red">*</font></label>
                                <input type="number" class="form-control" id="prixSF" name="prixuniteStock" value="'.$tab["prixuniteStock"].'" required />
                              </div>
                            <div class="modal-footer row">
                                <input type="hidden" name="prixachat" value="0"/>
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
                                  <input type="number" class="form-control" id="prixSF" name="prixSF" value="'.$tab["prixuniteStock"].'" disabled />
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

echo'</tbody></table><br /></div></div>';

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
                  <button type="button" class="btn btn-success pull-left" style="margin-right:20px;" id="btn_trm_StockCatalogue_Et">
                      Terminer
                  </button>
                  <button type="button" class="btn btn-primary pull-left" style="margin-right:20px;" id="btn_ajt_StockCatalogue_Et">
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

?>