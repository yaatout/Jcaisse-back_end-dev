<?php
session_start();

date_default_timezone_set('Africa/Dakar');

require('../connection.php');
require('../declarationVariables.php');

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois 
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');


$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

$messageDel='';
// var_dump($_SESSION['nomB']);

// if(!$_SESSION['iduserBack']){
// 	header('Location:index.php');
// }

// if($_SESSION['profil']!="SuperAdmin")
//     header('Location:accueil.php');

if (isset($_POST['btnEnregistrerCompte'])) {

		$nomCompte=htmlspecialchars(trim($_POST['nomCompte']));
		$typeCompte=htmlspecialchars(trim($_POST['typeCompte']));
		$numeroCompte=htmlspecialchars(trim($_POST['numeroCompte']));
		$montantCompte=htmlspecialchars(trim($_POST['montantCompte']));

		$sql1="insert into `".$nomtableCompte."` (nomCompte,typeCompte,numeroCompte,montantCompte) values('".$nomCompte."','".$typeCompte."','".$numeroCompte."','".$montantCompte."')";
		//var_dump($sql1);
  		$res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
    
    if($montantCompte>0){
        $sqlv="select * from `".$nomtableCompte."` where nomCompte='".$nomCompte."'";
        $resv=mysql_query($sqlv);
        $compte =mysql_fetch_assoc($resv);
        
        $numeroDestinataire='';
         $compteDonateur='';
        $nomClient='';
        $dateEcheance='2021-01-01';
        $operation='depot';
        $idCompte=$compte['idCompte'];
        $dateSaisie=$dateHeures;
        $description='INITIALISATION';
        if($typeCompte=='pret'){
            $operation='Compte';
        }
        
        $sql2="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('".$montantCompte."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduser']."')";
          $res2=mysql_query($sql2) or die ("insertion Cmpte impossible =>".mysql_error() );
    }
}
if (isset($_POST['btnModifierCompte'])) {
        $idCompte=htmlspecialchars(trim($_POST['idCompte']));
        $nomCompte=htmlspecialchars(trim($_POST['nomCompte']));
    if($nomCompte!=''){
		$typeCompte=htmlspecialchars(trim($_POST['typeCompte']));
		$numeroCompte=htmlspecialchars(trim($_POST['numeroCompte']));

		 $sql2="UPDATE `".$nomtableCompte."` set  nomCompte='".$nomCompte."',typeCompte='".$typeCompte."',numeroCompte='".$numeroCompte."' where  idCompte=".$idCompte."";
	      $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
    }
		
}
if (isset($_POST['btnSuprimerCompte'])) {
        $idCompte=htmlspecialchars(trim($_POST['idCompte']));
        $sqlDV="SELECT * FROM `".$nomtableComptemouvement."`	where idCompte=".$idCompte;
        $resDV=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
        $S_facture = mysql_fetch_array($resDV);
        
    if( mysql_num_rows($resDV)){
        
		 $sql2="UPDATE `".$nomtableCompte."` set  activer='0' where  idCompte=".$idCompte."";
         $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
   
		//$messageDel='Ce compte ne paut etre suprimer car il contient des valeurs';
		$messageDel='Compte desactiver avec succé';
    }else{
        $sql1="DELETE FROM `".$nomtableCompte."` WHERE idCompte=".$idCompte;
        $res1=@mysql_query($sql1) or die ("suppression impossible personnel".mysql_error()); 
        $messageDel='Compte supprimer avec succé';
    }
		
}
/**Debut Button upload Image Compte**/
if (isset($_POST['btnUploadImgCompte'])) {
    $idCompte=htmlspecialchars(trim($_POST['idCompte']));
    if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './images/'.$file);

            $sql2="UPDATE `".$nomtableCompte."` set image='".$file."' where idCompte='".$idCompte."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Compte**/

$sqlC="SELECT SUM(montantCompte)	FROM `".$nomtableCompte."`  where activer='1' and typeCompte !='compte pret' ";
$resC=mysql_query($sqlC) or die ("select stock impossible =>".mysql_error());
$S_f = mysql_fetch_array($resC);
$montantT = $S_f[0];

require('entetehtml.php');
?>

<script >
    function showImageCompte(idCompte) {
        var nom=$('#imageCompte'+idCompte).attr("data-image");
        $('#idCompte_View').text(nom);
        $('#idCompte_Upd_Nv').val(idCompte);
        $('#input_file_Compte').val('');
        $('#imageNvCompte').modal('show');
        var file = $('#imageCompte'+idCompte).val();
        if(file!=null && file!=''){
            var format = file.substr(file.length - 3);
            if(format=='pdf'){
                document.getElementById('output_pdf_Compte').style.display = "block";
                document.getElementById('output_image_Compte').style.display = "none";
                document.getElementById("output_pdf_Compte").src="./compte/images/"+file;
            }
            else{
                document.getElementById('output_image_Compte').style.display = "block";
                document.getElementById('output_pdf_Compte').style.display = "none";
                document.getElementById("output_image_Compte").src="./compte/images/"+file;
            }
        }
        else{
            document.getElementById('output_pdf_Compte').style.display = "none";
            document.getElementById('output_image_Compte').style.display = "none";
        }
    }
    function showPreviewCompte(event) {
        var file = document.getElementById('input_file_Compte').value;
        var reader = new FileReader();
        reader.onload = function()
        {
            var format = file.substr(file.length - 3);
            var pdf = document.getElementById('output_pdf_Compte');
            var image = document.getElementById('output_image_Compte');
            if(format=='pdf'){
                document.getElementById('output_pdf_Compte').style.display = "block";
                document.getElementById('output_image_Compte').style.display = "none";
                pdf.src = reader.result;
            }
            else{
                document.getElementById('output_image_Compte').style.display = "block";
                document.getElementById('output_pdf_Compte').style.display = "none";
                image.src = reader.result;
            }
        }
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('btn_upload_Compte').style.display = "block";
    }
</script>

<body>

		<?php
		  require('../header.php');
		?>
		<center>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient">
                <i class="glyphicon glyphicon-plus"></i>Ajouter compte
            </button>
        </center>

            <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau compte</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post" >                              
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="inputprenom" required="" name="nomCompte" placeholder="Le nom du compte ici...">
                                  <span class="text-danger" ></span>
                              </div>
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                 <select class="form-control" name="typeCompte" id="typeCompteS">
                                    <?php
                                    echo '<optgroup label="Compte mobile">';
                                        $sqlv="select * from `aaa-transaction` where typeTransaction = 'Transaction' ORDER BY idTransaction ASC";
                                        $resv=mysql_query($sqlv);
                                        while($operation =mysql_fetch_assoc($resv)){
                                            echo '<option value="'.$operation["nomTransaction"].'">'.$operation["nomTransaction"].'</option>';
                                        }
                                    echo '</optgroup>';
                                    echo '<optgroup label="Compte bancaire"> ';
                                        $sqlb="select * from `aaa-banque` ORDER BY idBanque ASC";
                                        $resb=mysql_query($sqlb);
                                        while($b =mysql_fetch_assoc($resb)){
                                            echo '<option value="'.$b["nom"].'">'.$b["nom"].'</option>';
                                        }
                                    echo '</optgroup>';
                                    echo '<optgroup label="Autre compte"> ';
                                        echo '<option value="compte epargne">COMPTE EPARGNE</option>';
                                        echo '<option value="compte pret">COMPTE PRÊT</option>';
                                        $sqlt="select * from `".$nomtableComptetype."` ";
                                        $rest=mysql_query($sqlt);
                                        while($t =mysql_fetch_assoc($rest)){
                                            if ($t["nomType"]!='compte bancaire' && $t["nomType"]!='compte mobile') {
                                                # code...
                                                echo '<option value="'.$t["nomType"].'">'.$t["nomType"].'</option>';
                                            }
                                        }
                                    echo '</optgroup>';
                                    ?>
                                </select>
                                  <span class="text-danger" ></span>
                              </div>
                                <div class="form-group">
                                  <label for="inputEmail3" class="control-label" id="nclB">NUMERO COMPTE  <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="numeroCompte"  name="numeroCompte" placeholder="Numero du compte" value=""> 
                                  <span class="text-danger" ></span>
                              </div> 
                                <div class="form-group">
                                  <label for="inputEmail3" class="control-label" id="icLB">INITIALISATION COMPTE  </label>
                                  <input type="number" min="0" class="form-control" id="montantCompte" value="0" name="montantCompte" placeholder="Le montant initial du compte">
                                  <span class="text-danger" ></span>
                              </div>
                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="submit" name="btnEnregistrerCompte" class="btn btn-primary">Enregistrer</button>
                               </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
                 <?php if($messageDel!=''){
                      echo '<center>
                                <div class="row">
                                    <div class="alert alert-danger">
                                        <strong>'.$messageDel.' </strong>
                                    </div>
                                </div>
                            </center>
                        ';
                }?>    
    <div class="row">
           
            <div class="container" align="center">
                
               
                <ul class="nav nav-tabs">
                  <li class="active">
                      <a data-toggle="tab" href="#LISTECLIENTS">LISTE DES COMPTES
                            <?php
							 
						 		echo  ' => Montant Total =  '.number_format(($montantT * $_SESSION['devise']),0, ',', ' ').' '.$_SESSION['symbole'];
							
						  ?>                                      
                      </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="LISTECLIENTS">
                        <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th>Nom Compte</th>
                                    <th>Type Compte</th>
                                    <th>Numero Compte</th>
                                    <th>Etat</th>
                                    <th>Operation</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                        <th>Nom Compte</th>
                                    <th>Type Compte</th>
                                    <th>Numero Compte</th>
                                    <th>Etat</th>
                                    <th>Operation</th>
                                    <th>Image</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php  
                                        // var_dump($nomtableComptetype);
                                        $sql1="SELECT * FROM `".$nomtableCompte."`  where activer='1'";
                                        $res1 = mysql_query($sql1) or die ("utilisateur requête 4".mysql_error());
                                        while ($compte = mysql_fetch_array($res1)){ ?>
                                            <tr>
                                                <td><?php echo  $compte["nomCompte"]; ?> </td>
                                                <td><?php echo  $compte["typeCompte"]; ?> </td>
                                                <td><?php echo  $compte["numeroCompte"]; ?> </td>
                                                <td><?php echo  number_format(($compte['montantCompte'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']; ?> <a   <?php echo  "href=compte/compteDe.php?c=".$compte['idCompte'] ; ?> > Details</a></td>
                                                <td> 
                                                    <img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierCompte".$compte['idCompte']; ?> />&nbsp;&nbsp;
                                                    <!-- <a href="#" disabled>
                                                            <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php //echo  "data-target=#imgsuprimerCompte".$compte['idCompte'] ;?> /></a>  -->
                                                    
                                                    <div class="modal fade" <?php echo  "id=imgmodifierCompte".$compte['idCompte']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Modifier compte</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulairePersonnel" method="post" >


                                                                        <div class="form-group">
                                                                            <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                            <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idCompte" value="'.$compte['idCompte'].'"> '; ?>
                                                                            <?php echo '<input type="text" class="form-control" id="inputprenom" name="nomCompte" value="'.$compte['nomCompte'].'"> '; ?>
                                                                            <span class="text-danger" ></span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                                                            <select name="typeCompte" >
                                                                            <?php
                                                                                $sqlv="select * from `aaa-transaction` where typeTransaction = 'Transaction' ORDER BY idTransaction ASC";
                                                                                $resv=mysql_query($sqlv);
                                                                                while($operation =mysql_fetch_assoc($resv)){
                                                                                    echo '<option value="'.$operation["nomTransaction"].'">'.$operation["nomTransaction"].'</option>';
                                                                                }
                                                                                $sqlb="select * from `aaa-banque` ORDER BY idBanque ASC";
                                                                                $resb=mysql_query($sqlb);
                                                                                while($b =mysql_fetch_assoc($resb)){
                                                                                    echo '<option value="'.$b["nom"].'">'.$b["nom"].'</option>';
                                                                                }
                                                                                $sqlt="select * from `".$nomtableComptetype."` ";
                                                                                $rest=mysql_query($sqlt);
                                                                                while($t =mysql_fetch_assoc($rest)){
                                                                                    echo '<option value="'.$t["nomType"].'">'.$t["nomType"].'</option>';
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                            <span class="text-danger" ></span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="inputEmail3" class="control-label">NUMERO COMPTE  <font color="red">*</font></label>
                                                                                <?php echo '<input type="text" class="form-control" id="inputprenom" name="numeroCompte" value="'.$compte['numeroCompte'].'"> '; ?>
                                                                            <span class="text-danger" ></span>
                                                                        </div>
                                                                        <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" name="btnModifierCompte" class="btn btn-primary">Modifier</button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal fade" <?php echo  "id=imgsuprimerCompte".$compte['idCompte']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Suprimer compte</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulairePersonnel" method="post" >


                                                                        <div class="form-group">
                                                                            <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                            <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idCompte" value="'.$compte['idCompte'].'"> '; ?>
                                                                            <?php echo '<input type="text" class="form-control" id="inputprenom" name="nomCompte" value="'.$compte['nomCompte'].'" disabled> '; ?>
                                                                            <span class="text-danger" ></span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                                                            <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idCompte" value="'.$compte['idCompte'].'"> '; ?>
                                                                            <?php echo '<input type="text" class="form-control" id="inputprenom" name="typeCompte" value="'.$compte['typeCompte'].'" disabled> '; ?>
                                                                            <span class="text-danger" ></span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="inputEmail3" class="control-label">NUMERO COMPTE  <font color="red">*</font></label>
                                                                                <?php echo '<input type="text" class="form-control" id="inputprenom" name="numeroCompte" value="'.$compte['numeroCompte'].'" disabled> '; ?>
                                                                            <span class="text-danger" ></span>
                                                                        </div>
                                                                        <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" name="btnSuprimerCompte" class="btn btn-primary">Suprimer</button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($compte['image']!=null && $compte['image']!=' '){ 
                                                            $format=substr($compte['image'], -3);
                                                            if($format=='pdf'){ 
                                                                echo '<img class="btn btn-xs" src="./images/pdf.png" align="middle" alt="apperçu" width="30" height="25" data-toggle="modal" data-target="#imageNvCompte'.$compte['idCompte'].'" onclick="showImageCompte('.$compte['idCompte'].')" />
                                                                <input style="display:none;" data-image="'.$compte['nomCompte'].'"  id="imageCompte'.$compte['idCompte'].'"  value="'.$compte['image'].'" />';
                                                            }
                                                                else { 
                                                                echo '<img class="btn btn-xs" src="./images/img.png" align="middle" alt="apperçu" width="30" height="25" data-toggle="modal" data-target="#imageNvCompte'.$compte['idCompte'].'" onclick="showImageCompte('.$compte['idCompte'].')" />
                                                                <input style="display:none;" data-image="'.$compte['nomCompte'].'" id="imageCompte'.$compte['idCompte'].'"  value="'.$compte['image'].'" />';
                                                            } 
                                                        }
                                                        else{ 
                                                            echo '<img class="btn btn-xs" src="./images/upload.png" align="middle" alt="apperçu" width="30" height="25" data-toggle="modal" data-target="#imageNvCompte'.$compte['idCompte'].'" onclick="showImageCompte('.$compte['idCompte'].')" />
                                                            <input style="display:none;" data-image="'.$compte['nomCompte'].'" id="imageCompte'.$compte['idCompte'].'"  value="'.$compte['image'].'" />';
                                                        }
                                                
                                                    ?> 
                                                </td>
                                            </tr>
                                        <?php } ?>

                            </tbody>
                        </table>

                        <div class="modal fade"  id="imageNvCompte" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Compte : <b># <span id="idCompte_View"></span></b></h4>
                                    </div>
                                    <form   method="post" enctype="multipart/form-data">
                                        <div class="modal-body" style="padding:0px 150px;">
                                            <input  type="text" style="display:none;" name="idCompte" id="idCompte_Upd_Nv" />
                                            <div class="form-group" style="text-align:center;" >
                                                <input type="file" name="file" accept="image/*" id="input_file_Compte" onchange="showPreviewCompte(event);"/><br />
                                                <img style="display:none;" width="300px" height="400px" id="output_image_Compte"/>
                                                <iframe style="display:none;" id="output_pdf_Compte" width="100%" height="100%"></iframe>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgCompte" id="btn_upload_Compte" >
                                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                                      

                    </div>
                </div>
           </div>
	</div>

</body>
</html>
