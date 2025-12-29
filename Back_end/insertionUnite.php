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
require('connectionPDO.php');
require('declarationVariables.php');

/**********************/


$idUniteStock         =@$_POST["idUniteStock"];

$nomUniteStock          =@$_POST["nomUniteStock"];

$nomUniteStockM          =@$_POST["nomUniteStockM"];


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
  if($nomUniteStock) {
      // Contrôle côté serveur
      $nomUniteStock = trim($nomUniteStock);
      
      if(empty($nomUniteStock)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de stock ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteStock) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de stock doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteStock) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de stock ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si l'unité de stock existe déjà
  		$sql11="SELECT * FROM `aaa-unitestock` where nomUniteStock=:nomUniteStock";
		$req11 = $bdd->prepare($sql11);
		$req11->execute(array('nomUniteStock' => $nomUniteStock));
		if($req11->rowCount() == 0){
				$sql="insert into `aaa-unitestock` (nomUniteStock) values (:nomUniteStock)";
				$req = $bdd->prepare($sql);
				$req->execute(array('nomUniteStock' => $nomUniteStock)) or die(print_r($req->errorInfo()));
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script>alert("Cette unité de stock existe déjà.");</script>';
		echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
elseif($modifier){ 
      // Contrôle côté serveur pour la modification
      $nomUniteStock = trim($nomUniteStock);
      
      if(empty($nomUniteStock)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de stock ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteStock) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de stock doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteStock) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de stock ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si une autre unité avec le même nom existe déjà (sauf celle qu'on modifie)
      $sql11="SELECT * FROM `aaa-unitestock` where nomUniteStock=:nomUniteStock AND idUniteStock !=:idUniteStock";
      $req11 = $bdd->prepare($sql11);
      $req11->execute(array('nomUniteStock' => $nomUniteStock, 'idUniteStock' => $idUniteStock));
      if($req11->rowCount() > 0){
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Cette unité de stock existe déjà.");</script>';
          echo'<script language="JavaScript">document.location="insertionUnite.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
       try {
           $sql1="update `aaa-unitestock` set nomUniteStock=:nomUniteStock WHERE idUniteStock =:idUniteStock";
           $req1 = $bdd->prepare($sql1);
           $req1->execute(array(
               'nomUniteStock' => $nomUniteStock,
               'idUniteStock' => $idUniteStock
           )) or die(print_r($req1->errorInfo()));
       } catch(PDOException $e) {
           die("Erreur lors de la modification de l'unité de stock : " . $e->getMessage());
       }
}
else if ($supprimer) {
    try {
        $sql2="DELETE FROM `aaa-unitestock` WHERE idUniteStock =:idUniteStock";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(array('idUniteStock' => $idUniteStock)) or die(print_r($req2->errorInfo()));
    } catch(PDOException $e) {
        die("Erreur lors de la suppression de l'unité de stock : " . $e->getMessage());
    }
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter une unité de stock</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'une unité de stock</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionUnite.php" onsubmit="return validerUnite();">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" >
                             <div class="form-group row">
                                <label for="nomUniteStock" class="col-sm-4 col-form-label">UNITE DE STOCK <font color="red">*</font></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control inputbasic" placeholder="Entrez le nom de l'unité de stock" id="nomUniteStock" name="nomUniteStock" size="35" value="" required autofocus="" />
                                    <div id="erreurUnite" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
                                </div>
                             </div>

                           <div >
						   <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                            <input type="submit" class="boutonbasic" name="inserer" value="AJOUTER  >>">
                            
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    <script>
    function validerUnite() {
        var unite = document.getElementById('nomUniteStock').value.trim();
        var erreurUnite = document.getElementById('erreurUnite');
        
        // Réinitialiser les messages d'erreur
        erreurUnite.style.display = 'none';
        erreurUnite.innerHTML = '';
        
        // Vérifier l'unité
        if(unite === '') {
            erreurUnite.innerHTML = 'Le nom de l\'unité de stock ne peut pas être vide.';
            erreurUnite.style.display = 'block';
            document.getElementById('nomUniteStock').focus();
            return false;
        }
        
        if(unite.length < 2) {
            erreurUnite.innerHTML = 'Le nom de l\'unité de stock doit contenir au moins 2 caractères.';
            erreurUnite.style.display = 'block';
            document.getElementById('nomUniteStock').focus();
            return false;
        }
        
        if(unite.length > 50) {
            erreurUnite.innerHTML = 'Le nom de l\'unité de stock ne peut pas dépasser 50 caractères.';
            erreurUnite.style.display = 'block';
            document.getElementById('nomUniteStock').focus();
            return false;
        }
        
        return true;
    }
    
    function validerUniteModifier() {
        var unite = document.getElementById('nomUniteStockModifier').value.trim();
        var erreurUniteModifier = document.getElementById('erreurUniteModifier');
        
        // Réinitialiser les messages d'erreur
        erreurUniteModifier.style.display = 'none';
        erreurUniteModifier.innerHTML = '';
        
        // Vérifier l'unité
        if(unite === '') {
            erreurUniteModifier.innerHTML = 'Le nom de l\'unité de stock ne peut pas être vide.';
            erreurUniteModifier.style.display = 'block';
            document.getElementById('nomUniteStockModifier').focus();
            return false;
        }
        
        if(unite.length < 2) {
            erreurUniteModifier.innerHTML = 'Le nom de l\'unité de stock doit contenir au moins 2 caractères.';
            erreurUniteModifier.style.display = 'block';
            document.getElementById('nomUniteStockModifier').focus();
            return false;
        }
        
        if(unite.length > 50) {
            erreurUniteModifier.innerHTML = 'Le nom de l\'unité de stock ne peut pas dépasser 50 caractères.';
            erreurUniteModifier.style.display = 'block';
            document.getElementById('nomUniteStockModifier').focus();
            return false;
        }
        
        return true;
    }
    
    // Effacer les messages d'erreur lorsque l'utilisateur tape
    document.getElementById('nomUniteStock').addEventListener('input', function() {
        document.getElementById('erreurUnite').style.display = 'none';
    });
    
    // Pour le formulaire de modification (créé dynamiquement)
    document.addEventListener('input', function(e) {
        if(e.target.id === 'nomUniteStockModifier') {
            document.getElementById('erreurUniteModifier').style.display = 'none';
        }
    });
    </script>


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

$sql="select * from `aaa-unitestock` order by idUniteStock desc";
$req = $bdd->prepare($sql);
$req->execute();
$res = $req;

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr>
     <th>UNITE DE STOCK</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr><th>UNITE DE STOCK</th>
    <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if($res->rowCount() > 0){
	while($tab=$res->fetch(PDO::FETCH_ASSOC)){
	
		echo'<tr><td>'.$tab["nomUniteStock"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idUniteStock"].'" /></a>&nbsp;&nbsp;';
		  
			/*$sql2="select * from `unitestock` where nomUniteStock='".$tab['nomUniteStock']."'";		
			$req2 = $bdd->prepare($sql2);
			$req2->execute(array('nomUniteStock' => $tab['nomUniteStock']));
			
		if($req2->rowCount() == 0){*/
				echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idUniteStock"].'" /></a>';
		/* }
		 else{
			 //echo'<a ><img src="images/drop.png" align="middle" alt="supprimer" /></a>';
		 }*/
		 		 
		echo '<div id="imgsup'.$tab["idUniteStock"].'" class="modal fade" role="dialog">
				<div class="modal-dialog">
				   <div class="modal-content">
					   <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Confirmation Suppression Unite Stock</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">

						  <table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionUnite.php">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="categorie">UNITE STOCK </label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="nomUniteStock" id="nomUniteStock" value="'.$tab["nomUniteStock"].'" disabled=""/></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div>

                                
                                <tr><td align="center">
                                  <br/><br/>
                                  <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                  
								  <input type="hidden" name="idUniteStock" value="'.$tab["idUniteStock"].'"/>
									<input type="hidden" name="supprimer" value="1" />
                                    </td>
                                </tr>
                              </form>
                       </table>
					   
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
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionUnite.php" onsubmit="return validerUniteModifier();">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="reference">UNITE STOCK <font color="red">*</font></label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="nomUniteStock" id="nomUniteStockModifier" value="'.$tab["nomUniteStock"].'" required autofocus="" /></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="erreurUniteModifier"></div></td></tr>
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
