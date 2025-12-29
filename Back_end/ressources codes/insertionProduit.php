<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/
session_start();
if($_SESSION['iduser']){

mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

$nomtableJournal     =$_SESSION['nomB']."-journal";
$nomtablePage        =$_SESSION['nomB']."-pagej";
$nomtablePagnet      =$_SESSION['nomB']."-pagnet";
$nomtableLigne       =$_SESSION['nomB']."-lignepj";
$nomtableDesignation =$_SESSION['nomB']."-designation";
$nomtableStock       =$_SESSION['nomB']."-stock";
/**********************/
$designation         =@htmlentities($_POST["designation"]);
$prix                =@$_POST["prix"];
$prixuniteStock      =@$_POST["prixuniteStock"];
$idDesignation       =@$_POST["idDesignation"];
$classe              =@$_POST["classe"];
$desig               =@$_POST["desig"];
$uniteStock          =@htmlentities($_POST["uniteStock"]);
$prixService         =@$_POST["prixService"];
$montantFrais        =@$_POST["montantFrais"];
$nbArticleUniteStock =@$_POST["nbArticleUniteStock"];
$modifier            =@$_POST["modifier"];
$supprimer           =@$_POST["supprimer"];
$annuler             =@$_POST["annuler"];
/***************/
$idDesignation1      =@$_POST["idDesignation"];
/*
echo $nomtableDesignation ;
echo $designation ;
echo $prix; 
echo $uniteStock;
echo $prixuniteStock; 
echo $nbArticleUniteStock;
*/
/**********************/
if(!$annuler){
if(!$modifier and !$supprimer){
  if($designation){ 
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
	//else de if($designation) : rien
}
elseif($modifier){ //if $modifier
  
	  if($classe==0){ 
       $sql="update `".$nomtableDesignation."` set designation='".$designation."',classe='".$classe.
       "',uniteStock='".$uniteStock."',nbreArticleUniteStock='".$nbArticleUniteStock."' where idDesignation=".$idDesignation;
       $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
		}else{
			 $sql="update `".$nomtableDesignation."` set designation='".$designation."',prix='".$prix."',classe='".$classe."' where idDesignation=".$idDesignation;
       $res=@mysql_query($sql)or die ("modification impossible 2".mysql_error());
			 }
}
else if ($supprimer) {
  $sql="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation1;
  $res=@mysql_query($sql) or die ("suppression impossible designa     ".mysql_error());
}


if (isset($_POST['subImport'])) {
 
  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $headers = fgetcsv($handle, 1000, ",");

    $tabDes=array(array());
    while (($data=fgetcsv($handle,1000,",")) !==FALSE) {

     $sql3="insert into `".$nomtableDesignation."`(designation,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,classe) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]')";
      $res3=@mysql_query($sql3) or die ("impossible d'importer le fichier csv");
      $tabDes[]=$data;
        
    }
   // var_dump($tabDes);
   
   
    fclose($handle);  exit;
  }

if ( $_GET['l']==7) {
  
  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $tabDes;
    while (($data=fgetcsv($handle,1000,",")) !==FALSE) {

     $tabDes[]=$data;

    }
    return  $tabDes;
    //var_dump($tabDes);
    fclose($handle);  exit;
  }

}
 
}
if(isset($_POST["Export"])){

      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=data.csv');
      $delimiter = ",";
      $output = fopen("php://output", "w");
      $fields=array('designation', 'prixUnitaire', 'uniteStock', 'prixUniteStock', 'nbreArticleUniteStock', 'classe');
      fputcsv($output,$fields, $delimiter );
     /* $query ="SELECT designation,classe,uniteStock,nbreArticleUniteStock,classe FROM `".$nomtableDesignation."` ORDER BY idDesignation DESC";
      $result = mysql_query( $query);
      if ($result ) {
        while($row = mysql_fetch_assoc($result))
          {
            $lineData = array($row['designation'], $row['uniteStock'], $row['nbreArticleUniteStock'], $row['classe']);
               fputcsv($output, $lineData,$delimiter);
          }
          fclose($output); exit;
      }*/
       fclose($output); exit;

 }
/**************** DECLARATION DES ENTETES *************/
?>
<!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
   <link rel="stylesheet" href="css/bootstrap.css"> 
   <link rel="stylesheet" href="css/datatables.min.css">
   <script src="js/jquery-3.1.1.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
   <script src="js/datatables.min.js"></script>
   <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple2").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple3").DataTable();});</script>
   <script type="text/javascript" src="prixdesignation.js"></script> 
   <script type="text/javascript" src="js/script.js"></script>
   <link rel="stylesheet" type="text/css" href="style.css">
   
   <title> SOLUTIONS</title>
</head>
<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

/**************** BOUTTON AjoutDesignation ET TFENETRE NODAL ASSOCIEE  *************/
?>
<div class="row">
    <div class="col-sm-3">
          <form class="form-inline" action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post" enctype="multipart/form-data">
              <div class="form-group">
                   <div class="col-md-8">
                       <input type="file" id="importInput" name="fileImport"  
                        data-toggle="modal" onChange="loadCSV()" >
                        <button type="submit" name="subImport" value="Importer " class="btn btn-success">Importer un catalogue</button> 
                  </div>
              </div>
          </form>
    </div>
    <button  name="subImport" value="Importer " class="btn btn-success" data-toggle="modal" data-target="#importInputForm">Importer un catalogue 2</button>
    <div id="importInputForm" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">pop</h4>
                    </div>
                    <div class="modal-body">
                      <form class="form-inline" action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post" enctype="multipart/form-data">
                         <div class="form-group">
                             <div class="col-md-8">
                                 <form class="form-inline" action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                         <div class="col-md-8">
                                             <input type="file" id="importInput222" name="fileImport"  
                                              data-toggle="modal" onChange="loadCSV()" value="0">
                                             
                                        </div>
                                    </div>
                                </form>
                               <div id="afficherCSV">
                                 
                               </div>
                            </div>
                          </div>
                    </form>
                    </div>
                </div>
            </div>
    </div>
    <div class="col-sm-3 ">
          <form class="form-horizontal" action="insertionProduit.php" method="post" name="upload_excel"
                          enctype="multipart/form-data">
                      <div class="form-group">
                                <div class="col-md-4 col-md-offset-4">
                                    <input type="submit" name="Export" class="btn btn-success" value="Générer un fichier CSV"/>
                                </div>
                       </div>
            </form>
    </div>
    <div class="col-md-offset-1 col-sm-3">
       <!-- <button type="button" class="btn btn-primary btn-success" id="AjoutDesignation">Ajouter une désignation</button> -->
        <button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#myModal1" id="AjoutDesignation">Ajouter une désignation</button>
    </div>
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
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionProduit.php">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" >
                            DESIGNATION<input type="text" class="inputbasic" placeholder="Désignation Entrée/Sortie" id="designation" name="designation" size="35" value="" />
                            <div>PRIX UNITAIRE</div><input type="text" class="inputbasic" placeholder="Prix Unitaire" id="prix" name="prix" size="35" value="" />
                          <div>UNITE STOCK</div><input type="text" class="inputbasic" placeholder="Unité du stock" id="uniteStock" name="uniteStock" size="35" value="" />
                          <div>PRIX UNITE STOCK</div><input type="text" class="inputbasic" placeholder="Prix Unite Stock" id="prixuniteStock" name="prixuniteStock" size="35" value="" />
                          <div>NOMBRE ARTICLE(S) PAR UNITE STOCK</div><input type="text" class="inputbasic" placeholder="Nombre article(s)  par unité stock" id="nbArticleUniteStock" name="nbArticleUniteStock" size="35" value="" />
                           <div class="modal-footer row">
                            <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>"></div>
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
    </div>
</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/

echo'<div class="container">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">CATALOGUE DES PRODUITS</a></li>';
echo'<li><a data-toggle="tab" href="#SERVICE">CATALOGUE DES SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">CATALOGUE DES FRAIS</a></li>';
echo'</ul><div class="tab-content">';

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr><th>DESIGNATION</th>
     <th>PRIX UNITAIRE</th>
	 <th>UNITE STOCK (US)</th>
	 <th>PRIX UNITE STOCK</th>
     <th>N. ARTICLES/US</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr><th>DESIGNATION</th>
     <th>PRIX UNITAIRE</th>
	 <th>UNITE STOCK (US)</th>
	 <th>PRIX UNITE STOCK</th>
     <th>N. ARTICLES/US</th>
     <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if(mysql_num_rows($res)){
while($tab=mysql_fetch_array($res)){
//PRODUIT
  if($tab["classe"]==0)
      echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["prix"].'</td><td align="right">'.$tab["uniteStock"].'</td><td align="right">'.$tab["prixuniteStock"].'</td><td align="right">'.$tab["nbreArticleUniteStock"].'</td>
    <td>
      <a  >
        <img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idDesignation"].'" /></a>&nbsp;&nbsp;
     <a >
        <img src="images/drop.png" align="middle" alt="modifier"  data-toggle="modal" data-target="#imgsup'.$tab["idDesignation"].'" />
     </a>
     <div id="imgsup'.$tab["idDesignation"].'" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Formulaire pour supprimer une désignation</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                       <form class="form" name="formulaire2" method="post" action="insertionProduit.php">

                          <div class="form-group ">
                            PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" checked > 
                             SERVICE <input type="radio" class="inputbasic" name="classe" value="1" >
                             FRAIS <input type="radio" class="inputbasic" name="classe" value="2" >
                           </div>
                             
                           <div class="form-group ">
                               <div>DESIGNATION</div><input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" />'.
                                '<div>UNITE STOCK</div><input class="inputbasic" name="uniteStock" size="40" value="'.$tab["uniteStock"].'" />'.
                                '
                              <div>NOMBRE ARTICLE(S) PAR UNITE STOCK</div>
                                <input class="inputbasic" name="nbArticleUniteStock" size="40" value="'.$tab["nbreArticleUniteStock"].'" />
                            </div>
                          <div class="modal-footer row">
                               <div class="col-sm-3 "> <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>
                               <div class="col-sm-3 "> <input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>'.
                               '<input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                                <input type="hidden" name="supprimer" value="1" />
                          </div>
                       </form>
                    </div>
               </div>
            </div>
    </div>
    </td></tr>
    <div id="imgmodifier'.$tab["idDesignation"].'"  class="modal fade " role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modifier désignation</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                       <form role="form" class="" name="formulaire2" method="post" action="insertionProduit.php">
                           <div class="form-group ">
                            PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" checked > 
                             SERVICE <input type="radio" class="inputbasic" name="classe" value="1" >
                             FRAIS <input type="radio" class="inputbasic" name="classe" value="2" >
                           <div>
                             
                           <div class="form-group ">
                               <div>DESIGNATION</div><input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" />'.
                                '<div>UNITE STOCK</div><input class="inputbasic" name="uniteStock" size="40" value="'.$tab["uniteStock"].'" />'.
                                '
                              <div>NOMBRE ARTICLE(S) PAR UNITE STOCK</div>
                                <input class="inputbasic" name="nbArticleUniteStock" size="40" value="'.$tab["nbreArticleUniteStock"].'" />
                            </div>
                           <div class="modal-footer ">
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/></div>
                              <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>
                              <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                             <input type="hidden" name="modifier" value="1"/>
                              
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
          ';?>
    <?php 
}
}
else{
echo'<div id="PRODUIT" class="tab-pane fade">';
echo'<div class="table-responsive"><table class="tableau3" width="80%" align="left" border="1"><thead>';
echo'<tr><td colspan="4">la liste des produits est vide </td></tr>';
}
echo'</tbody></table><br /></div></div>';

$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);
echo'<div id="SERVICE" class="tab-pane fade">';
echo'<div class="table-responsive"><table id="exemple2" class="display" width="100%" align="left" border="1">';
echo'<thead>
<tr>
	<th>DESIGNATION</th>
	<th>MONTANT FRAIS</th>
	<th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr>
	<th>DESIGNATION</th>
	<th>MONTANT FRAIS</th>
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
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" > 
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" checked> 
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" >
                          </div>
                          <div class="form-group ">
                              <div> DESIGNATION</div>
                                 <input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" />'.
                              '<div>PRIX </div>
                                <input class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" />
                           </div>
                          
                           <div class="modal-footer row">
                              <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                             <input type="hidden" name="modifier" value="1"/>
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="btnModifier" value="MODIFIER  >>"/></div>
                              <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>
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
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" > 
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" checked> 
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" >
                           </div>
                           <div class="form-group ">
                              <div>DESIGNATION</div>
                              <input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" disabled/>
                              <div>PRIX </div>
                              <input class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" disabled/>
                           </div>
                            
                            <div class="modal-footer row">
                                  <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>
                                  <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>'.
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
else{
echo'<div id="SERVICE" class="tab-pane fade">';
echo'<div class="table-responsive"><table class="tableau3" width="80%" align="left" border="1"><thead>';
echo'<tr><td colspan="3">la liste des services est vide </td></tr>';
}
echo'</tbody></table><br /></div></div>';




$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

echo'<div id="FRAIS" class="tab-pane fade">';
echo'<div class="table-responsive"><table id="exemple3" class="display" width="100%" align="left" border="1">';
echo'<thead>
	<tr>
		<th>DESIGNATION</th>
		<th>MONTANT FRAIS</th>
		<th>OPERATIONS</th>
	</tr>
</thead><tfoot>
	<tr>
		<th>DESIGNATION</th>
		<th>MONTANT FRAIS</th>
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
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" > 
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" > 
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" checked>
                          </div>
                          <div class="form-group ">
                              <div> DESIGNATION</div>
                                 <input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" />'.
                              '<div>PRIX </div>
                                <input class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" />
                           </div>
                          
                           <div class="modal-footer row">
                              <input type="hidden" name="idDesignation" value="'.$tab["idDesignation"].'"/>
                             <input type="hidden" name="modifier" value="1"/>
                             <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="btnModifier" value="MODIFIER  >>"/></div>
                              <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>
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
                              PRODUIT<input type="radio" class="inputbasic" name="classe" value="0" > 
                              SERVICE <input type="radio" class="inputbasic" name="classe" value="1" > 
                              FRAIS <input type="radio" class="inputbasic" name="classe" value="2" checked>
                           </div>
                           <div class="form-group ">
                              <div>DESIGNATION</div><input class="inputbasic" name="designation" size="40" value="'.$tab["designation"].'" disabled/>'.
                              '<div>PRIX </div><input class="inputbasic" name="prix" size="40" value="'.$tab["prix"].'" disabled/>
                           </div>
                            
                            <div class="modal-footer row">
                                  <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /></div>
                                  <div class="col-sm-3 "><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" /></div>'.
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
else{
echo'<div id="FRAIS" class="tab-pane fade">';
echo'<div class="table-responsive"><table class="tableau3" width="80%" align="left" border="1"><thead>';
echo'<tr><td colspan="3">la liste des frais est vide </td></tr>';
}
echo'</tbody></table><br /></div></div>';


echo'</section>'.
'<script>$(document).ready(function(){$("#imgmodifier").click(function(){$("#ModifierDesignationModal").modal();});});</script>'.
'<script>$(document).ready(function(){$("#imgsup").click(function(){$("#supprimerDesignationModal").modal();});});</script>'.
'</div></div></body></html>

';
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionProduit.php"</script>';
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
