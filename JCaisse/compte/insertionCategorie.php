<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/
session_start();
if($_SESSION['iduserBack']){

require('connection.php');

require('declarationVariables.php');

/**********************/
$designation         =@$_POST["designation"];
//var_dump($designation);
$prix                =@$_POST["prix"];
$prixSF              =@$_POST["prixSF"];
//var_dump($prix);

$idDesignation       =@$_POST["idDesignation"];
$idcategorie         =@$_POST["idcategorie"];
$categorieM          =@$_POST["categorieM"];
$classe              =@$_POST["classe"];
//var_dump($classe);
$desig               =@$_POST["desig"];
$uniteStock          =@$_POST["uniteStock"];
$prixService         =@$_POST["prixService"];
$montantFrais        =@$_POST["montantFrais"];
$nbArticleUniteStock =@$_POST["nbArticleUniteStock"];
$seuil 				 =@$_POST["seuil"];
$prixUnitaire        =@$_POST["prix"];
$prixuniteStock      =@$_POST["prixuniteStock"];
$codeBarreDesignation =@$_POST["codeBarreDesignation"];
$codeBarreuniteStock =@$_POST["codeBarreuniteStock"];

$categorie          =@$_POST["categorie"];
$categorie1          =@$_POST["categorie1"];


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
  if($categorie1) {
  		$sql11="SELECT * FROM `aaa-categorie` where nomcategorie='".$categorie1."'";
		$res11=mysql_query($sql11);
		if(!@mysql_num_rows($res11)){
				$sql="insert into `aaa-categorie` (nomcategorie) values ('".mysql_real_escape_string($categorie1)."')";
				//echo $sql;
       			$res=@mysql_query($sql) or die ("insertion categorie 1 impossible".mysql_error());
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script type="text/javascript" src="alertCategorie.js"></script>';
		echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
elseif($modifier){ 
       $sql1="update `aaa-categorie` set nomcategorie='".mysql_real_escape_string($categorie)."' WHERE idcategorie =".$idcategorie ;
       $res1=@mysql_query($sql1)or die ("modification impossible1 ".mysql_error());
			  // echo $sql1;
			 $sql2="update `aaa-boutique` set categorie='".mysql_real_escape_string($categorie)."' where categorie='".$categorieM."'";
			 $res2=@mysql_query($sql2)or die ("modification Categorie dans Reference ".mysql_error());
			  // echo $sql2;
			
			 
			 
	}
else if ($supprimer) {
  $sql2="DELETE FROM `aaa-categorie` WHERE idcategorie =".$idcategorie ;
 // echo $sql2;
  $res2=@mysql_query($sql2) or die ("suppression impossible designa     ".mysql_error());
}



/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

/**************** Ajouter une catégorie *************/

/**************** *************  *************/
?>
<div class="container">
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter une catégorie</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'une Catégorie</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionCategorie.php">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" ><b>
                            NON CATEGORIE <font color="red">*</font> </b><input type="text" class="inputbasic" placeholder="Entrez le nom de la catégorie" id="categorie1" name="categorie1" size="35" value="" required autofocus="" />

                           <div >
						   <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                            <input type="submit" class="boutonbasic" name="inserer" value="AJOUTER  >>">
                            
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>


<?php
/**************** Ajouter une catégorie  - - FIN *************/

/**************** *************  *************/
?>


</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/

echo'<div class="container">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">LISTE DES CATEGORIES DE CAISSES</a></li>';
/*echo'<li><a data-toggle="tab" href="#SERVICE">LISTE DES CATEGORIES DE SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">LISTE DES CATEGORIES DE FRAIS</a></li>';*/
echo'</ul><div class="tab-content">';

$sql="select * from `aaa-categorie` order by idcategorie desc";
$res=mysql_query($sql);

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr>
     <th>CATEGORIE</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr><th>CATEGORIE</th>
    <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if(mysql_num_rows($res)==true){
	while($tab=mysql_fetch_array($res)){
	
		echo'<tr><td>'.$tab["nomcategorie"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idcategorie"].'" /></a>&nbsp;&nbsp;';
		  
			$sql2="select * from `aaa-categorie` where categorie='".$tab['nomcategorie']."'";		
			$res2=mysql_query($sql2);
			
		if(@mysql_num_rows($res2)==false){
				echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idcategorie"].'" /></a>';
		 }
		 else{
			 //echo'<a ><img src="images/drop.png" align="middle" alt="supprimer" /></a>';
		 }
		 		 
		echo '<div id="imgsup'.$tab["idcategorie"].'" class="modal fade" role="dialog">
				<div class="modal-dialog">
				   <div class="modal-content">
					   <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Confirmation Suppression Categorie</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">

						  <table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionCategorie.php">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="categorie">CATEGORIE </label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="categorie" id="categorie" value="'.$tab["nomcategorie"].'" disabled=""/></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div>

                                
                                <tr><td align="center">
                                  <br/><br/>
                                  <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                  
								  <input type="hidden" name="idcategorie" value="'.$tab["idcategorie"].'"/>
									<input type="hidden" name="supprimer" value="1" />
                                    </td>
                                </tr>
                              </form>
                       </table>
					   
						</div>
				   </div>
				</div>
		</div>'.
		
		
		
		'<div id="imgmodifier'.$tab["idcategorie"].'"  class="modal fade " role="dialog">

			<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Modifier Catégorie</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">
							
							
							
							<table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionCategorie.php">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="reference">CATEGORIE <font color="red">*</font></label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="categorie" id="categorie" value="'.$tab["nomcategorie"].'" required autofocus="" /></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div>                             

								
                                <tr><td align="center">
                                  <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                  <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                                  
								  <input type="hidden" name="idcategorie" value="'.$tab["idcategorie"].'"/>
									<input type="hidden" name="categorieM" value="'.$tab["nomcategorie"].'"/>
								 <input type="hidden" name="modifier" value="1"/>
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


		
echo'</tbody></table><br /></div></div>';


echo'</section>'.
'<script>$(document).ready(function(){$("#imgmodifier").click(function(){$("#ModifierDesignationModal").modal();});});</script>'.
'<script>$(document).ready(function(){$("#imgsup").click(function(){$("#supprimerDesignationModal").modal();});});</script>'.
'</div></div></div></body></html>';
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
echo'</head>';
echo'</html>';
}
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
?>
