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
      // Contrôle côté serveur
      $categorie1 = trim($categorie1);
      if(empty($categorie1)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la catégorie ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($categorie1) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la catégorie doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($categorie1) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la catégorie ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si la catégorie existe déjà
  		$sql11="SELECT * FROM `aaa-categorie` where nomcategorie=:nomCategorie";
		$req11 = $bdd->prepare($sql11);
		$req11->execute(array('nomCategorie' => $categorie1));
		if($req11->rowCount() == 0){
				$sql="insert into `aaa-categorie` (nomcategorie) values (:nomCategorie)";
				$req = $bdd->prepare($sql);
				$req->execute(array('nomCategorie' => $categorie1)) or die(print_r($req->errorInfo()));
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
      // Contrôle côté serveur pour la modification
      $categorie = trim($categorie);
      
      if(empty($categorie)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la catégorie ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($categorie) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la catégorie doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($categorie) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de la catégorie ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si une autre catégorie avec le même nom existe déjà (sauf celle qu'on modifie)
      $sql11="SELECT * FROM `aaa-categorie` where nomcategorie=:nomCategorie AND idcategorie !=:idCategorie";
      $req11 = $bdd->prepare($sql11);
      $req11->execute(array('nomCategorie' => $categorie, 'idCategorie' => $idcategorie));
      if($req11->rowCount() > 0){
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Cette catégorie existe déjà.");</script>';
          echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
       try {
           $sql1="update `aaa-categorie` set nomcategorie=:nomCategorie WHERE idcategorie =:idCategorie";
           $req1 = $bdd->prepare($sql1);
           $req1->execute(array(
               'nomCategorie' => $categorie,
               'idCategorie' => $idcategorie
           )) or die(print_r($req1->errorInfo()));
           
           $sql2="update `aaa-boutique` set categorie=:nouvelleCategorie where categorie=:ancienneCategorie";
           $req2 = $bdd->prepare($sql2);
           $req2->execute(array(
               'nouvelleCategorie' => $categorie,
               'ancienneCategorie' => $categorieM
           )) or die(print_r($req2->errorInfo()));
       } catch(PDOException $e) {
           die("Erreur lors de la modification de la catégorie : " . $e->getMessage());
       }
}
else if ($supprimer) {
    try {
        $sql2="DELETE FROM `aaa-categorie` WHERE idcategorie =:idCategorie";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(array(
            'idCategorie' => $idcategorie
        )) or die(print_r($req2->errorInfo()));
    } catch(PDOException $e) {
        die("Erreur lors de la suppression de la catégorie : " . $e->getMessage());
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter une catégorie</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'une Catégorie</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionCategorie.php" onsubmit="return validerCategorie();">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" ><b>
                            NON CATEGORIE <font color="red">*</font> </b><input type="text" class="inputbasic" placeholder="Entrez le nom de la catégorie" id="categorie1" name="categorie1" size="35" value="" required autofocus="" />
                            <div id="erreurCategorie" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>

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
    function validerCategorie() {
        var categorie = document.getElementById('categorie1').value.trim();
        var erreurDiv = document.getElementById('erreurCategorie');
        
        // Réinitialiser le message d'erreur
        erreurDiv.style.display = 'none';
        erreurDiv.innerHTML = '';
        
        // Vérifier si le champ est vide
        if(categorie === '') {
            erreurDiv.innerHTML = 'Le nom de la catégorie ne peut pas être vide.';
            erreurDiv.style.display = 'block';
            document.getElementById('categorie1').focus();
            return false;
        }
        
        // Vérifier la longueur minimale
        if(categorie.length < 2) {
            erreurDiv.innerHTML = 'Le nom de la catégorie doit contenir au moins 2 caractères.';
            erreurDiv.style.display = 'block';
            document.getElementById('categorie1').focus();
            return false;
        }
        
        // Vérifier la longueur maximale
        if(categorie.length > 50) {
            erreurDiv.innerHTML = 'Le nom de la catégorie ne peut pas dépasser 50 caractères.';
            erreurDiv.style.display = 'block';
            document.getElementById('categorie1').focus();
            return false;
        }
        
        // Vérifier les caractères autorisés (lettres, chiffres, espaces, tirets)
        var regex = /^[a-zA-Z0-9\s\-]+$/;
        if(!regex.test(categorie)) {
            erreurDiv.innerHTML = 'Le nom de la catégorie ne peut contenir que des lettres, chiffres, espaces et tirets.';
            erreurDiv.style.display = 'block';
            document.getElementById('categorie1').focus();
            return false;
        }
        
        return true;
    }
    
    // Effacer le message d'erreur lorsque l'utilisateur tape
    document.getElementById('categorie1').addEventListener('input', function() {
        document.getElementById('erreurCategorie').style.display = 'none';
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

if($res->rowCount() > 0){
	while($tab=$res->fetch(PDO::FETCH_ASSOC)){
	
		echo'<tr><td>'.$tab["nomcategorie"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idcategorie"].'" /></a>&nbsp;&nbsp;';
		  
			$sql2="select * from `aaa-categorie` where categorie=:categorie";		
			$req2 = $bdd->prepare($sql2);
			$req2->execute(array('categorie' => $tab['nomcategorie']));
			
		if($req2->rowCount() == 0){
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
