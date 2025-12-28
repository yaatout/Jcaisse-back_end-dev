<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');

require('declarationVariables.php');

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
          $operation='versement';
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

?>

<?php require('entetehtml.php'); ?>

<body>
<?php  require('header.php'); ?>

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
                document.getElementById("output_pdf_Compte").src="images/"+file;
            }
            else{
                document.getElementById('output_image_Compte').style.display = "block";
                document.getElementById('output_pdf_Compte').style.display = "none";
                document.getElementById("output_image_Compte").src="images/"+file;
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

<div class="container">

  <ul class="nav nav-tabs">
      <li class="active">
          <a data-toggle="tab" href="#LISTECOMPTES">LISTE DES COMPTES </a>
      </li>
  </ul>
  <div class="tab-content">
      <div id="LISTECOMPTES" class="tab-pane fade in active">
        <br />
        <center>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajouter_Compte" data-dismiss="modal" >
                <i class="glyphicon glyphicon-plus"></i>Ajout Compte 
            </button>
        </center>
        <div class="table-responsive">

          <table id="listeComptes" class="display tabCompte" width="100%" border="1">
            <thead>
              <tr>
                  <th>Ordre</th>
                  <th>Nom</th>
                  <th>Type</th>
                  <th>Numero</th>
                  <th>Montant</th>
                  <th>Operations</th>
                  <th>Image</th>
              </tr>
            </thead>
          </table>

        </div>
      </div>
  </div>
</div>

    <div class="modal fade" id="ajouter_Compte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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

    <div id="modifier_Compte" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modification Compte</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <form class="form"  >
                <div class="form-group">
                  <input type="hidden"  id="inpt_mdf_Compte_idCompte"  />
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Compte_Nom">Nom </label>
                  <input type="text" class="form-control" id="inpt_mdf_Compte_Nom"  />
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Compte_Adresse">Adresse </label>
                  <input type="text" class="form-control" id="inpt_mdf_Compte_Adresse" />
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Compte_Telephone">Telephone </label>
                  <input type="text" class="form-control" id="inpt_mdf_Compte_Telephone" />
                </div>
                <div class="form-group">
                  <label for="slct_mdf_Compte_Banque">Banque </label>
                  <select class="form-control" id="slct_mdf_Compte_Banque">
                    <?php
                    echo '<optgroup label="Compte bancaire"> ';
                        $sqlb="select * from `aaa-banque` ORDER BY idBanque ASC";
                        $resb=mysql_query($sqlb);
                        while($b =mysql_fetch_assoc($resb)){
                            echo '<option value="'.$b["nom"].'">'.$b["nom"].'</option>';
                        }
                    echo '</optgroup>';
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Compte_NumBanque">Numero compte bancaire </label>
                  <input type="text" class="form-control" id="inpt_mdf_Compte_NumBanque" />
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_modifier_Compte" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
                  </div>
                  <div class="col-sm-6 "> 
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>

    <div id="supprimer_Compte" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Suppression Compte</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <form class="form"  >
                <div class="form-group">
                  <input type="hidden"  id="inpt_spm_Compte_idCompte"  />
                </div>
                <div class="form-group">
                  <label for="span_spm_Compte_Nom">Nom : </label>
                  <span id="span_spm_Compte_Nom" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Compte_Adresse">Adresse : </label>
                  <span id="span_spm_Compte_Adresse" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Compte_Telephone">Telephone : </label>
                  <span id="span_spm_Compte_Telephone" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Compte_Banque">Banque : </label>
                  <span id="span_spm_Compte_Banque" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Compte_NumBanque">Numero compte bancaire : </label>
                  <span id="span_spm_Compte_NumBanque" ></span>
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_supprimer_Compte" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
                  </div>
                  <div class="col-sm-6 "> 
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>

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

<script type="text/javascript" src="scripts/compte.js"></script>

<?php

?>
