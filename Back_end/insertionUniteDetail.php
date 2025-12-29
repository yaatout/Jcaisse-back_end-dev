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

$idUniteDetail         =@$_POST["idUniteDetail"];

$nomUniteDetail          =@$_POST["nomUniteDetail"];

$nomUniteDetailM          =@$_POST["nomUniteDetailM"];


$modifier            =@$_POST["modifier"];
$supprimer           =@$_POST["supprimer"];
$annuler             =@$_POST["annuler"];
/***************/


/*
echo $nomtableDesignation ;
echo $designation ;
echo $prix;
echo $uniteDetail;
echo $prixuniteDetail;
echo $nbArticleUniteDetail;
*/
/**********************/
if(!$annuler){
if(!$modifier and !$supprimer){
  if($nomUniteDetail) {
      // Contrôle côté serveur
      $nomUniteDetail = trim($nomUniteDetail);
      
      if(empty($nomUniteDetail)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de détail ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteDetail) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de détail doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteDetail) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de détail ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si l'unité de détail existe déjà
  		$sql11="SELECT * FROM `aaa-uniteDetail` where nomUniteDetail=:nomUniteDetail";
		$req11 = $bdd->prepare($sql11);
		$req11->execute(array('nomUniteDetail' => $nomUniteDetail));
		if($req11->rowCount() == 0){
				$sql="insert into `aaa-uniteDetail` (nomUniteDetail) values (:nomUniteDetail)";
				$req = $bdd->prepare($sql);
				$req->execute(array('nomUniteDetail' => $nomUniteDetail)) or die(print_r($req->errorInfo()));
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script>alert("Cette unité de détail existe déjà.");</script>';
		echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
elseif($modifier){ 
      // Contrôle côté serveur pour la modification
      $nomUniteDetail = trim($nomUniteDetail);
      
      if(empty($nomUniteDetail)) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de détail ne peut pas être vide.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteDetail) < 2) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de détail doit contenir au moins 2 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(strlen($nomUniteDetail) > 50) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le nom de l\'unité de détail ne peut pas dépasser 50 caractères.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si une autre unité avec le même nom existe déjà (sauf celle qu'on modifie)
      $sql11="SELECT * FROM `aaa-uniteDetail` where nomUniteDetail=:nomUniteDetail AND idUniteDetail !=:idUniteDetail";
      $req11 = $bdd->prepare($sql11);
      $req11->execute(array('nomUniteDetail' => $nomUniteDetail, 'idUniteDetail' => $idUniteDetail));
      if($req11->rowCount() > 0){
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Cette unité de détail existe déjà.");</script>';
          echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
       try {
           $sql1="update `aaa-uniteDetail` set nomUniteDetail=:nomUniteDetail WHERE idUniteDetail =:idUniteDetail";
           $req1 = $bdd->prepare($sql1);
           $req1->execute(array(
               'nomUniteDetail' => $nomUniteDetail,
               'idUniteDetail' => $idUniteDetail
           )) or die(print_r($req1->errorInfo()));
       } catch(PDOException $e) {
           die("Erreur lors de la modification de l'unité de détail : " . $e->getMessage());
       }
}
else if ($supprimer) {
    try {
        $sql2="DELETE FROM `aaa-uniteDetail` WHERE idUniteDetail =:idUniteDetail";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(array('idUniteDetail' => $idUniteDetail)) or die(print_r($req2->errorInfo()));
    } catch(PDOException $e) {
        die("Erreur lors de la suppression de l'unité de détail : " . $e->getMessage());
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter une unité de Detail</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'une unité de Detail</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionUniteDetail.php" onsubmit="return validerUniteDetail();">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" >
                             <div class="form-group row">
                                <label for="nomUniteDetail" class="col-sm-4 col-form-label">UNITE DE DETAIL <font color="red">*</font></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control inputbasic" placeholder="Entrez le nom de l'unité de détail" id="nomUniteDetail" name="nomUniteDetail" size="35" value="" required autofocus="" />
                                    <div id="erreurUniteDetail" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
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
    function validerUniteDetail() {
        var uniteDetail = document.getElementById('nomUniteDetail').value.trim();
        var erreurUniteDetail = document.getElementById('erreurUniteDetail');
        
        // Réinitialiser les messages d'erreur
        erreurUniteDetail.style.display = 'none';
        erreurUniteDetail.innerHTML = '';
        
        // Vérifier l'unité de détail
        if(uniteDetail === '') {
            erreurUniteDetail.innerHTML = 'Le nom de l\'unité de détail ne peut pas être vide.';
            erreurUniteDetail.style.display = 'block';
            document.getElementById('nomUniteDetail').focus();
            return false;
        }
        
        if(uniteDetail.length < 2) {
            erreurUniteDetail.innerHTML = 'Le nom de l\'unité de détail doit contenir au moins 2 caractères.';
            erreurUniteDetail.style.display = 'block';
            document.getElementById('nomUniteDetail').focus();
            return false;
        }
        
        if(uniteDetail.length > 50) {
            erreurUniteDetail.innerHTML = 'Le nom de l\'unité de détail ne peut pas dépasser 50 caractères.';
            erreurUniteDetail.style.display = 'block';
            document.getElementById('nomUniteDetail').focus();
            return false;
        }
        
        return true;
    }
    
    function validerUniteDetailModifier() {
        var uniteDetail = document.getElementById('nomUniteDetailModifier').value.trim();
        var erreurUniteDetailModifier = document.getElementById('erreurUniteDetailModifier');
        
        // Réinitialiser les messages d'erreur
        erreurUniteDetailModifier.style.display = 'none';
        erreurUniteDetailModifier.innerHTML = '';
        
        // Vérifier l'unité de détail
        if(uniteDetail === '') {
            erreurUniteDetailModifier.innerHTML = 'Le nom de l\'unité de détail ne peut pas être vide.';
            erreurUniteDetailModifier.style.display = 'block';
            document.getElementById('nomUniteDetailModifier').focus();
            return false;
        }
        
        if(uniteDetail.length < 2) {
            erreurUniteDetailModifier.innerHTML = 'Le nom de l\'unité de détail doit contenir au moins 2 caractères.';
            erreurUniteDetailModifier.style.display = 'block';
            document.getElementById('nomUniteDetailModifier').focus();
            return false;
        }
        
        if(uniteDetail.length > 50) {
            erreurUniteDetailModifier.innerHTML = 'Le nom de l\'unité de détail ne peut pas dépasser 50 caractères.';
            erreurUniteDetailModifier.style.display = 'block';
            document.getElementById('nomUniteDetailModifier').focus();
            return false;
        }
        
        return true;
    }
    
    // Effacer les messages d'erreur lorsque l'utilisateur tape
    document.getElementById('nomUniteDetail').addEventListener('input', function() {
        document.getElementById('erreurUniteDetail').style.display = 'none';
    });
    
    // Pour le formulaire de modification (créé dynamiquement)
    document.addEventListener('input', function(e) {
        if(e.target.id === 'nomUniteDetailModifier') {
            document.getElementById('erreurUniteDetailModifier').style.display = 'none';
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
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">LISTE DES UNITES DE DetailS</a></li>';
/*echo'<li><a data-toggle="tab" href="#SERVICE">LISTE DES CATEGORIES DE SERVICES</a></li>';
echo'<li><a data-toggle="tab" href="#FRAIS">LISTE DES CATEGORIES DE FRAIS</a></li>';*/
echo'</ul><div class="tab-content">';

$sql="select * from `aaa-uniteDetail` order by idUniteDetail desc";
$req = $bdd->prepare($sql);
$req->execute();
$res = $req;

echo'<div id="PRODUIT" class="tab-pane fade in active">';
echo'<div class="table-responsive"><table id="exemple" class="display" class="tableau3" align="left" border="1"><thead>'.
'<tr>
     <th>UNITE DE Detail</th>
     <th>OPERATIONS</th>
</tr>
</thead>
<tfoot>
<tr><th>UNITE DE Detail</th>
    <th>OPERATIONS</th>
</tr>
</tfoot>
<tbody>';

if($res->rowCount() > 0){
	while($tab=$res->fetch(PDO::FETCH_ASSOC)){
	
		echo'<tr><td>'.$tab["nomUniteDetail"].'</td>';
		echo'<td><a><img src="images/edit.png" align="middle" alt="modifier" id="" data-toggle="modal" data-target="#imgmodifier'.$tab["idUniteDetail"].'" /></a>&nbsp;&nbsp;';
		  
			/*$sql2="select * from `uniteDetail` where nomUniteDetail='".$tab['nomUniteDetail']."'";		
			$req2 = $bdd->prepare($sql2);
			$req2->execute(array('nomUniteDetail' => $tab['nomUniteDetail']));
			
		if($req2->rowCount() == 0){*/
				echo'<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab["idUniteDetail"].'" /></a>';
		/* }
		 else{
			 //echo'<a ><img src="images/drop.png" align="middle" alt="supprimer" /></a>';
		 }*/
		 		 
		echo '<div id="imgsup'.$tab["idUniteDetail"].'" class="modal fade" role="dialog">
				<div class="modal-dialog">
				   <div class="modal-content">
					   <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Confirmation Suppression Unite Detail</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">

						  <table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionUniteDetail.php">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="categorie">UNITE Detail </label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="nomUniteDetail" id="nomUniteDetail" value="'.$tab["nomUniteDetail"].'" disabled=""/></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                </div>

                                
                                <tr><td align="center">
                                  <br/><br/>
                                  <input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                  
								  <input type="hidden" name="idUniteDetail" value="'.$tab["idUniteDetail"].'"/>
									<input type="hidden" name="supprimer" value="1" />
                                    </td>
                                </tr>
                              </form>
                       </table>
					   
						</div>
				   </div>
				</div>
		</div>'.
		
		
		
		'<div id="imgmodifier'.$tab["idUniteDetail"].'"  class="modal fade " role="dialog">

			<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Modifier Catégorie</h4>
						</div>
						<div class="modal-body" style="padding:40px 50px;">
							
							
							
							<table align="center" border="0">
                            <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionUniteDetail.php" onsubmit="return validerUniteDetailModifier();">

                                <div class="form-group">
                                  <tr class="div-categorie"><td><label for="reference">UNITE Detail <font color="red">*</font></label></td></tr>
                                  <tr class="div-categorie"><td><input type="text" class="form-control" name="nomUniteDetail" id="nomUniteDetailModifier" value="'.$tab["nomUniteDetail"].'" required autofocus="" /></td></tr>
                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="erreurUniteDetailModifier"></div></td></tr>
                                </div>                             

								
                                <tr><td align="center">
                                  <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                  <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                                  
								  <input type="hidden" name="idUniteDetail" value="'.$tab["idUniteDetail"].'"/>
									<input type="hidden" name="nomUniteDetailM" value="'.$tab["nomUniteDetail"].'"/>
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
echo'<script language="JavaScript">document.location="insertionUniteDetail.php"</script>';
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
