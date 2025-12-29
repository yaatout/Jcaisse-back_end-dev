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
      // Contrôle côté serveur
      $deviseSC = trim($deviseSC);
      $deviseSF = trim($deviseSF);
      $deviseCF = trim($deviseCF);
      
      if(empty($deviseSC) || !is_numeric($deviseSC) || $deviseSC <= 0) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le taux Sénégal/Canada doit être un nombre positif.");</script>';
          echo'<script language="JavaScript">document.location="insertionDevise.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(empty($deviseSF) || !is_numeric($deviseSF) || $deviseSF <= 0) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le taux Sénégal/France doit être un nombre positif.");</script>';
          echo'<script language="JavaScript">document.location="insertionDevise.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      if(empty($deviseCF) || !is_numeric($deviseCF) || $deviseCF <= 0) {
          echo'<!DOCTYPE html>';
          echo'<html>';
          echo'<head>';
          echo'<script>alert("Le taux Canada/France doit être un nombre positif.");</script>';
          echo'<script language="JavaScript">document.location="insertionDevise.php"</script>';
          echo'</head>';
          echo'</html>';
          exit;
      }
      
      // Vérification si les devises existent déjà pour cette date
  		$sql11="SELECT * FROM `aaa-devise` where dateAjout=:dateAjout";
		$req11 = $bdd->prepare($sql11);
		$req11->execute(array('dateAjout' => $dateString2));
		if($req11->rowCount() == 0){
				$sql1="insert into `aaa-devise` (Devise,Senegal,Canada,France,Symbole,dateAjout) values (:devise1,:senegal1,:canada1,:france1,:symbole1,:dateAjout)";
				$req1 = $bdd->prepare($sql1);
                $sql2="insert into `aaa-devise` (Devise,Senegal,Canada,France,Symbole,dateAjout) values (:devise2,:senegal2,:canada2,:france2,:symbole2,:dateAjout)";
				$req2 = $bdd->prepare($sql2);
                $sql3="insert into `aaa-devise` (Devise,Senegal,Canada,France,Symbole,dateAjout) values (:devise3,:senegal3,:canada3,:france3,:symbole3,:dateAjout)";
				$req3 = $bdd->prepare($sql3);
                
                $req1->execute(array(
                    'devise1' => 'Senegal',
                    'senegal1' => $deviseSS,
                    'canada1' => $deviseSC,
                    'france1' => $deviseSF,
                    'symbole1' => 'F CFA',
                    'dateAjout' => $dateString2
                )) or die(print_r($req1->errorInfo()));
                
                $req2->execute(array(
                    'devise2' => 'Canada',
                    'senegal2' => $deviseCS,
                    'canada2' => $deviseCC,
                    'france2' => $deviseCF,
                    'symbole2' => '$',
                    'dateAjout' => $dateString2
                )) or die(print_r($req2->errorInfo()));
                
                $req3->execute(array(
                    'devise3' => 'France',
                    'senegal3' => $deviseFS,
                    'canada3' => $deviseFC,
                    'france3' => $deviseFF,
                    'symbole3' => '£',
                    'dateAjout' => $dateString2
                )) or die(print_r($req3->errorInfo()));
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script>alert("Les devises pour cette date existent déjà.");</script>';
		echo'<script language="JavaScript">document.location="insertionDevise.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
elseif($modifier){ 
       try {
           $sql1="update `aaa-devise` set Devise=:devise,Senegal=:senegal,Canada=:canada,France=:france,Symbole=:symbole WHERE id =:id";
           $req1 = $bdd->prepare($sql1);
           $req1->execute(array(
               'devise' => $devise,
               'senegal' => $senegal,
               'canada' => $canada,
               'france' => $france,
               'symbole' => $symbole,
               'id' => $id
           )) or die(print_r($req1->errorInfo()));
       } catch(PDOException $e) {
           die("Erreur lors de la modification de la devise : " . $e->getMessage());
       }
}
else if ($supprimer) {
    try {
        $sql2="DELETE FROM `aaa-devise` WHERE id =:id";
        $req2 = $bdd->prepare($sql2);
        $req2->execute(array('id' => $id)) or die(print_r($req2->errorInfo()));
    } catch(PDOException $e) {
        die("Erreur lors de la suppression de la devise : " . $e->getMessage());
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
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajout de Devises </button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout Devises du <?php echo $dateString2; ?>  </h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionDevise.php" onsubmit="return validerDevises();">
                             <input type="hidden" class="inputbasic" id="dateAjout" name="dateAjout" value="<?php echo $dateString2; ?> " ><b>
                            Sénégal/Sénégal <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSS" name="deviseSS" size="35" value="1" disabled="disabled">
                            <div id="erreurSC" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
                            Sénégal/Canada  <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSC" name="deviseSC" size="35" value="" required="required">
                            <div id="erreurSF" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
                            Sénégal/France  <font color="red">*</font> </b><input type="text" class="inputbasic" id="deviseSF" name="deviseSF" size="35" value="" required="required">
                            <div id="erreurCF" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
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

    <script>
    function validerDevises() {
        var deviseSC = document.getElementById('deviseSC').value.trim();
        var deviseSF = document.getElementById('deviseSF').value.trim();
        var deviseCF = document.getElementById('deviseCF').value.trim();
        var erreurSC = document.getElementById('erreurSC');
        var erreurSF = document.getElementById('erreurSF');
        var erreurCF = document.getElementById('erreurCF');
        
        // Réinitialiser les messages d'erreur
        erreurSC.style.display = 'none';
        erreurSC.innerHTML = '';
        erreurSF.style.display = 'none';
        erreurSF.innerHTML = '';
        erreurCF.style.display = 'none';
        erreurCF.innerHTML = '';
        
        // Vérifier le taux Sénégal/Canada
        if(deviseSC === '') {
            erreurSC.innerHTML = 'Le taux Sénégal/Canada ne peut pas être vide.';
            erreurSC.style.display = 'block';
            document.getElementById('deviseSC').focus();
            return false;
        }
        
        if(!isNumeric(deviseSC) || parseFloat(deviseSC) <= 0) {
            erreurSC.innerHTML = 'Le taux Sénégal/Canada doit être un nombre positif.';
            erreurSC.style.display = 'block';
            document.getElementById('deviseSC').focus();
            return false;
        }
        
        // Vérifier le taux Sénégal/France
        if(deviseSF === '') {
            erreurSF.innerHTML = 'Le taux Sénégal/France ne peut pas être vide.';
            erreurSF.style.display = 'block';
            document.getElementById('deviseSF').focus();
            return false;
        }
        
        if(!isNumeric(deviseSF) || parseFloat(deviseSF) <= 0) {
            erreurSF.innerHTML = 'Le taux Sénégal/France doit être un nombre positif.';
            erreurSF.style.display = 'block';
            document.getElementById('deviseSF').focus();
            return false;
        }
        
        // Vérifier le taux Canada/France
        if(deviseCF === '') {
            erreurCF.innerHTML = 'Le taux Canada/France ne peut pas être vide.';
            erreurCF.style.display = 'block';
            document.getElementById('deviseCF').focus();
            return false;
        }
        
        if(!isNumeric(deviseCF) || parseFloat(deviseCF) <= 0) {
            erreurCF.innerHTML = 'Le taux Canada/France doit être un nombre positif.';
            erreurCF.style.display = 'block';
            document.getElementById('deviseCF').focus();
            return false;
        }
        
        return true;
    }
    
    // Fonction pour vérifier si une valeur est numérique
    function isNumeric(value) {
        return !isNaN(value) && value.trim() !== '' && isFinite(value);
    }
    
    // Effacer les messages d'erreur lorsque l'utilisateur tape
    document.getElementById('deviseSC').addEventListener('input', function() {
        document.getElementById('erreurSC').style.display = 'none';
    });
    
    document.getElementById('deviseSF').addEventListener('input', function() {
        document.getElementById('erreurSF').style.display = 'none';
    });
    
    document.getElementById('deviseCF').addEventListener('input', function() {
        document.getElementById('erreurCF').style.display = 'none';
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

$sql="select * from `aaa-devise` where dateAjout=:dateAjout order by id ASC";
$req = $bdd->prepare($sql);
$req->execute(array('dateAjout' => $dateString2));
$res = $req;

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

if($res->rowCount() > 0){
	while($tab=$res->fetch(PDO::FETCH_ASSOC)){
	
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
