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


$id         =@$_POST["id"];
$typeM          =@$_POST["typeM"];


$type          =@$_POST["type"];


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
  if($type) {
  		$sql11="SELECT * FROM `aaa-typeboutique` where libelle='".$type."'";
		$res11=mysql_query($sql11);
		if(!mysql_num_rows($res11)){
				$sql="insert into `aaa-typeboutique` (libelle) values ('".mysql_real_escape_string($type)."')";
				//echo $sql;
       			$res=@mysql_query($sql) or die ("insertion type impossible".mysql_error());
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
       $sql1="update `aaa-typeboutique` set libelle='".mysql_real_escape_string($type)."' WHERE id =".$id ;
       $res1=@mysql_query($sql1)or die ("modification impossible1 ".mysql_error());
			  // echo $sql1;
			 $sql2="update `aaa-boutique` set type='".mysql_real_escape_string($type)."' where type='".$typeM."'";
			 $res2=@mysql_query($sql2)or die ("modification type de boutique ".mysql_error());
			  // echo $sql2;
			
			 
			 
	}
else if ($supprimer) {
  $sql2="DELETE FROM `aaa-typeboutique` WHERE id =".$id ;
 // echo $sql2;
  $res2=@mysql_query($sql2) or die ("suppression impossible designation ".mysql_error());
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter un type</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'un Type</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionType.php">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" ><b>
                            TYPE <font color="red">*</font> </b><input type="text" class="inputbasic" placeholder="Entrez le nom du type" id="type" name="type" size="35" value="" required autofocus="" />

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
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">LISTE DES TYPES DE CAISSES</a></li>';
/*echo'<li><a data-toggle="tab" href="#SERVICE">LISTE DES CATEGORIES DE SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">LISTE DES CATEGORIES DE FRAIS</a></li>';*/
echo'</ul><div class="tab-content">';

$sql="select * from `aaa-typeboutique` order by id desc";
$res=mysql_query($sql);

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr>
     <th>TYPE</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr><th>TYPE</th>
    <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if(@mysql_num_rows($res)){
	while($tab=@mysql_fetch_array($res)){
	
		echo'<tr><td>'.$tab["libelle"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["id"].'" /></a>&nbsp;&nbsp;';
		  
			$sql2="select * from `aaa-boutique` where libelle='".$tab['libelle']."'";		
			$res2=mysql_query($sql2);
			
		if(!@mysql_num_rows($res2)){
				echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["id"].'" /></a>';
		 }
		 else{
			 //echo'<a ><img src="images/drop.png" align="middle" alt="supprimer" /></a>';
		 }
		 		 
		echo '<div id="imgsup'.$tab["id"].'" class="modal fade" role="dialog">
				<div class="modal-dialog">
				   <div class="modal-content">
					   <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Confirmation Suppression Type</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">

						  <table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionType.php">

                                <div class="form-group">
                                  <tr class="div-type"><td><label for="type">TYPE </label></td></tr>
                                  <tr class="div-type"><td><input type="text" class="form-control" name="type" id="type" value="'.$tab["libelle"].'" disabled=""/></td></tr>
                                  <tr class="div-type"><td><div class="help-block label label-danger" id="helpType"></div></td></tr>
                                </div>

                                
                                <tr><td align="center">
                                  <br/><br/>
                                  <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                  
								  <input type="hidden" name="id" value="'.$tab["id"].'"/>
									<input type="hidden" name="supprimer" value="1" />
                                    </td>
                                </tr>
                              </form>
                       </table>
					   
						</div>
				   </div>
				</div>
		</div>'.
		
		
		
		'<div id="imgmodifier'.$tab["id"].'"  class="modal fade " role="dialog">

			<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Modifier Catégorie</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">
							
							
							
							<table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionType.php">

                                <div class="form-group">
                                  <tr class="div-type"><td><label for="reference">TYPE <font color="red">*</font></label></td></tr>
                                  <tr class="div-type"><td><input type="text" class="form-control" name="type" id="type" value="'.$tab["libelle"].'" required autofocus="" /></td></tr>
                                  <tr class="div-type"><td><div class="help-block label label-danger" id="helptype"></div></td></tr>
                                </div>                             

								
                                <tr><td align="center">
                                  <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                  <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                                  
								  <input type="hidden" name="id" value="'.$tab["id"].'"/>
									<input type="hidden" name="typeM" value="'.$tab["libelle"].'"/>
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
echo'<script language="JavaScript">document.location="insertionType.php"</script>';
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
