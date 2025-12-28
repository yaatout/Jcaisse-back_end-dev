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
$deviseSC          =@$_POST["deviseSC"];

$deviseSS         =1;

$deviseCC         =1;

$deviseFF         =1;


$deviseSC          =@$_POST["deviseSC"];

$deviseSF          =@$_POST["deviseSF"];

$deviseCF          =@$_POST["deviseCF"];

if (($deviseSC!=0) and ($deviseSF!=0) and ($deviseCF!=0)){

    $deviseCS          =1/$deviseSC ;

    $deviseFS          =1/$deviseSF ;

    $deviseFC          =1/$deviseCF;

}



$modifier            =@$_POST["modifier"];
$supprimer           =@$_POST["supprimer"];
$annuler             =@$_POST["annuler"];

/***************/



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
  if($deviseSC) {
  		$sql11="SELECT * FROM `aaa-devise` where dateAjout='".$dateString2."'";
		$res11=mysql_query($sql11);
		if(!@mysql_num_rows($res11)){
				$sql1="insert into `aaa-devise` (Devise,Senegal,Canada,France,Symbole,dateAjout) values ('Senegal','".$deviseSS."','".$deviseSC."','".$deviseSF."','F CFA','".$dateString2."')";
				//echo $sql1;
                $sql2="insert into `aaa-devise` (Devise,Senegal,Canada,France,Symbole,dateAjout) values ('Canada','".$deviseCS."','".$deviseCC."','".$deviseCF."','$','".$dateString2."')";
				//echo $sql2;
                $sql3="insert into `aaa-devise` (Devise,Senegal,Canada,France,Symbole,dateAjout) values ('France','".$deviseFS."','".$deviseFC."','".$deviseFF."','£','".$dateString2."')";
				//echo $sql3;
       			$res1=@mysql_query($sql1) or die ("insertion unite stock impossible".mysql_error());
                $res2=@mysql_query($sql2) or die ("insertion unite stock impossible".mysql_error());
                $res3=@mysql_query($sql3) or die ("insertion unite stock impossible".mysql_error());
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		//echo'<script type="text/javascript" src="alertDevise.js"></script>';
		echo'<script language="JavaScript">document.location="insertionDevise.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
elseif($modifier){ 
       $sql1="update `aaa-unitestock` set nomUniteStock='".mysql_real_escape_string($nomUniteStock)."' WHERE idUniteStock =".$idUniteStock ;
       $res1=@mysql_query($sql1)or die ("modification impossible1 ".mysql_error());
			/*  // echo $sql1;
			 $sql2="update AS tablesDesignation LIKE `%-designation` set uniteStock='".mysql_real_escape_string($nomUniteStock)."' where uniteStock='".$nomUniteStockM."'";
			 
			   //echo $sql2;
			$res2=@mysql_query($sql2)or die ("modification Categorie dans Reference ".mysql_error());
			  // echo $sql2;
			*/
			 
			 
	}
else if ($supprimer) {
  $sql2="DELETE FROM `aaa-unitestock` WHERE idUniteStock =".$idUniteStock ;
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajout de Devises </button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout Devises du <?php echo $dateString2; ?>  </h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionDevise.php">
                             <input type="hidden" class="inputbasic" id="dateAjout" name="dateAjout" value="<?php echo $dateString2; ?> " ><b>
                            Sénégal/Sénégal <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSS" name="deviseSS" size="35" value="1" disabled="disabled">
                            Sénégal/Canada  <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSC" name="deviseSC" size="35" value="" required="required">
                            Sénégal/France  <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSF" name="deviseSF" size="35" value="" required="required">
                            Canada/France  <input type="text" class="inputbasic" id="deviseCF" name="deviseCF" size="35" value="" required="required">

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
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">LISTE DES UNITES DE STOCKS</a></li>';
/*echo'<li><a data-toggle="tab" href="#SERVICE">LISTE DES CATEGORIES DE SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">LISTE DES CATEGORIES DE FRAIS</a></li>';*/
echo'</ul><div class="tab-content">';

$sql="select * from `aaa-devise` where dateAjout='".$dateString2."' order by id ASC ;";
//echo  $sql;
$res=mysql_query($sql);

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr>
     <th>ID</th>
     <th>Devise</th>
     <th>Sénégal</th>
     <th>Canada</th>
     <th>France</th>
     <th>Symbole</th>
     <th>Date</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr>
     <th>ID</th>
     <th>Devise</th>
     <th>Sénégal</th>
     <th>Canada</th>
     <th>France</th>
     <th>Symbole</th>
     <th>Date</th>
     <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if(@mysql_num_rows($res)){
	while($tab=@mysql_fetch_array($res)){
	
		echo'<tr><td>'.$tab["id"].'</td><td>'.$tab["Devise"].'</td><td>'.$tab["Senegal"].'</td><td>'.$tab["Canada"].'</td><td>'.$tab["France"].'</td><td>'.$tab["Symbole"].'</td><td>'.$tab["dateAjout"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["id"].'" /></a>&nbsp;&nbsp;';
		  
			/*$sql2="select * from `unitestock` where nomUniteStock='".$tab['nomUniteStock']."'";		
			$res2=mysql_query($sql2);
			
		if(!@mysql_num_rows($res2)){*/
				echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["id"].'" /></a>';
		/* }
		 else{
			 //echo'<a ><img src="images/drop.png" align="middle" alt="supprimer" /></a>';
		 }*/
		/*
		echo '<div id="imgsup'.$tab["id"].'" class="modal fade" role="dialog">
				<div class="modal-dialog">
				   <div class="modal-content">
					   <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Confirmation Suppression Unite Stock</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">





                        <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionDevise.php">
                             <input type="hidden" class="inputbasic" id="dateAjout" name="dateAjout" value="<?php echo $dateString2; ?> " ><b>
                            Sénégal/Sénégal <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSS" name="deviseSS" size="35" value="1" disabled="disabled">
                            Sénégal/Canada  <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSC" name="deviseSC" size="35" value="'.$tab["nomUniteStock"].'" disabled=""/>
                            Sénégal/France  <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSF" name="deviseSF" size="35" value="'.$tab["nomUniteStock"].'" disabled=""/>
                            Canada/France  <input type="text" class="inputbasic" id="deviseCF" name="deviseCF" size="35" value="" required="required">

                           <div >
    						   <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                <input type="hidden" name="id" value="'.$tab["id"].'"/>
                				<input type="hidden" name="supprimer" value="1" />
                           </div>
                        </form>


						</div>
				   </div>
				</div>
		</div>'.



		'<div id="imgmodifier'.$tab["idUniteStock"].'"  class="modal fade " role="dialog">

			<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Modifier Catégorie</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">
							
							
							
							<table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionUnite.php">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="reference">UNITE STOCK <font color="red">*</font></label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="nomUniteStock" id="nomUniteStock" value="'.$tab["nomUniteStock"].'" required autofocus="" /></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div>                             

								
                                <tr><td align="center">
                                  <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                  <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                                  
								  <input type="hidden" name="idUniteStock" value="'.$tab["idUniteStock"].'"/>
									<input type="hidden" name="nomUniteStockM" value="'.$tab["nomUniteStock"].'"/>
								 <input type="hidden" name="modifier" value="1"/>
                                    </td>
                                </tr>
                              </form>
                       </table>
						
						</div>
					</div>
				</div>
		
				
		</div> ';*/?>
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
echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
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
