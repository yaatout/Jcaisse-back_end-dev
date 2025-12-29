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
      // Contrôle côté serveur
      $type = trim($type);
      
      if(empty($type)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du type ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($type) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du type doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($type) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du type ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si le type existe déjà
  		$sql11="SELECT * FROM `aaa-typeboutique` where libelle=:libelle";
		$req11 = $bdd->prepare($sql11);
		$req11->execute(array('libelle' => $type));
		if($req11->rowCount() == 0){
				$sql="insert into `aaa-typeboutique` (libelle) values (:libelle)";
				$req = $bdd->prepare($sql);
				$req->execute(array('libelle' => $type)) or die(print_r($req->errorInfo()));
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script>alert("Ce type existe déjà.");</script>';
		echo'<script language="JavaScript">document.location="insertionType.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
elseif($modifier){ 
      // Contrôle côté serveur pour la modification
      $type = trim($type);
      
      if(empty($type)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du type ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($type) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du type doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($type) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom du type ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si un autre type avec le même nom existe déjà (sauf celui qu'on modifie)
      $sql11="SELECT * FROM `aaa-typeboutique` where libelle=:libelle AND id !=:id";
      $req11 = $bdd->prepare($sql11);
      $req11->execute(array('libelle' => $type, 'id' => $id));
      if($req11->rowCount() > 0){
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Ce type existe déjà.");</script>';
          echo'<script language="JavaScript">document.location="insertionType.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
       try {
           $sql1="update `aaa-typeboutique` set libelle=:libelle WHERE id =:id";
           $req1 = $bdd->prepare($sql1);
           $req1->execute(array(
               'libelle' => $type,
               'id' => $id
           )) or die(print_r($req1->errorInfo()));
           
           $sql2="update `aaa-boutique` set type=:nouveauType where type=:ancienType";
           $req2 = $bdd->prepare($sql2);
           $req2->execute(array(
               'nouveauType' => $type,
               'ancienType' => $typeM
           )) or die(print_r($req2->errorInfo()));
       } catch(PDOException $e) {
           die("Erreur lors de la modification du type : " . $e->getMessage());
       }
}
else if ($supprimer) {
    try {
        $sql2="DELETE FROM `aaa-typeboutique` WHERE id =:id";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(array('id' => $id)) or die(print_r($req2->errorInfo()));
    } catch(PDOException $e) {
        die("Erreur lors de la suppression du type : " . $e->getMessage());
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter un type</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'un Type</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionType.php" onsubmit="return validerType();">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" >
                             <div class="form-group row">
                                <label for="type" class="col-sm-4 col-form-label">TYPE <font color="red">*</font></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control inputbasic" placeholder="Entrez le nom du type" id="type" name="type" size="35" value="" required autofocus="" />
                                    <div id="erreurType" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
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
    function validerType() {
        var type = document.getElementById('type').value.trim();
        var erreurType = document.getElementById('erreurType');
        
        // Réinitialiser les messages d'erreur
        erreurType.style.display = 'none';
        erreurType.innerHTML = '';
        
        // Vérifier le type
        if(type === '') {
            erreurType.innerHTML = 'Le nom du type ne peut pas être vide.';
            erreurType.style.display = 'block';
            document.getElementById('type').focus();
            return false;
        }
        
        if(type.length < 2) {
            erreurType.innerHTML = 'Le nom du type doit contenir au moins 2 caractères.';
            erreurType.style.display = 'block';
            document.getElementById('type').focus();
            return false;
        }
        
        if(type.length > 50) {
            erreurType.innerHTML = 'Le nom du type ne peut pas dépasser 50 caractères.';
            erreurType.style.display = 'block';
            document.getElementById('type').focus();
            return false;
        }
        
        return true;
    }
    
    function validerTypeModifier() {
        var type = document.getElementById('typeModifier').value.trim();
        var erreurTypeModifier = document.getElementById('erreurTypeModifier');
        
        // Réinitialiser les messages d'erreur
        erreurTypeModifier.style.display = 'none';
        erreurTypeModifier.innerHTML = '';
        
        // Vérifier le type
        if(type === '') {
            erreurTypeModifier.innerHTML = 'Le nom du type ne peut pas être vide.';
            erreurTypeModifier.style.display = 'block';
            document.getElementById('typeModifier').focus();
            return false;
        }
        
        if(type.length < 2) {
            erreurTypeModifier.innerHTML = 'Le nom du type doit contenir au moins 2 caractères.';
            erreurTypeModifier.style.display = 'block';
            document.getElementById('typeModifier').focus();
            return false;
        }
        
        if(type.length > 50) {
            erreurTypeModifier.innerHTML = 'Le nom du type ne peut pas dépasser 50 caractères.';
            erreurTypeModifier.style.display = 'block';
            document.getElementById('typeModifier').focus();
            return false;
        }
        
        return true;
    }
    
    // Effacer les messages d'erreur lorsque l'utilisateur tape
    document.getElementById('type').addEventListener('input', function() {
        document.getElementById('erreurType').style.display = 'none';
    });
    
    // Pour le formulaire de modification (créé dynamiquement)
    document.addEventListener('input', function(e) {
        if(e.target.id === 'typeModifier') {
            document.getElementById('erreurTypeModifier').style.display = 'none';
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
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">LISTE DES TYPES DE CAISSES</a></li>';
/*echo'<li><a data-toggle="tab" href="#SERVICE">LISTE DES CATEGORIES DE SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">LISTE DES CATEGORIES DE FRAIS</a></li>';*/
echo'</ul><div class="tab-content">';

$sql="select * from `aaa-typeboutique` order by id desc";
$req = $bdd->prepare($sql);
$req->execute();
$res = $req;

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

if($res->rowCount() > 0){
	while($tab=$res->fetch(PDO::FETCH_ASSOC)){
	
		echo'<tr><td>'.$tab["libelle"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["id"].'" /></a>&nbsp;&nbsp;';
		  
			$sql2="select * from `aaa-boutique` where libelle=:libelle";		
			$req2 = $bdd->prepare($sql2);
			$req2->execute(array('libelle' => $tab['libelle']));
			
		if($req2->rowCount() == 0){
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
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionType.php" onsubmit="return validerTypeModifier();">

                                <div class="form-group">
                                  <tr class="div-type"><td><label for="reference">TYPE <font color="red">*</font></label></td></tr>
                                  <tr class="div-type"><td><input type="text" class="form-control" name="type" id="typeModifier" value="'.$tab["libelle"].'" required autofocus="" /></td></tr>
                                  <tr class="div-type"><td><div class="help-block label label-danger" id="erreurTypeModifier"></div></td></tr>
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
