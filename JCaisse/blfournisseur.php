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

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgBl'])) {
  $idBl=htmlspecialchars(trim($_POST['idBl']));
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

          move_uploaded_file($tmpName, './PiecesJointes/'.$file);

          $sql2="UPDATE `".$nomtableBl."` set image='".$file."' where idBl='".$idBl."' ";
          $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
      }
      else{
          echo "Une erreur est survenue";
      }
  }
}
/**Fin Button upload Image Bl**/


?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');
  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
?>

        <script type="text/javascript">
            function showImageBl(idBl) {
                $('#idBl_Upd_Nv').val(idBl);
                $('#input_file_Bl').val('');
                $('#imageNvBl').modal('show');
                var file = $('#imageBl'+idBl).val();
                if(file!=null && file!=''){
                    var format = file.substr(file.length - 3);
                    if(format=='pdf'){
                        document.getElementById('output_pdf_Bl').style.display = "block";
                        document.getElementById('output_image_Bl').style.display = "none";
                        document.getElementById("output_pdf_Bl").src="./PiecesJointes/"+file;
                    }
                    else{
                        document.getElementById('output_image_Bl').style.display = "block";
                        document.getElementById('output_pdf_Bl').style.display = "none";
                        document.getElementById("output_image_Bl").src="./PiecesJointes/"+file;
                    }
                }
                else{
                    document.getElementById('output_pdf_Bl').style.display = "none";
                    document.getElementById('output_image_Bl').style.display = "none";
                }
            }
            function showPreviewBl(event) {
                var file = document.getElementById('input_file_Bl').value;
                var reader = new FileReader();
                reader.onload = function()
                {
                    var format = file.substr(file.length - 3);
                    var pdf = document.getElementById('output_pdf_Bl');
                    var image = document.getElementById('output_image_Bl');
                    if(format=='pdf'){
                        document.getElementById('output_pdf_Bl').style.display = "block";
                        document.getElementById('output_image_Bl').style.display = "none";
                        pdf.src = reader.result;
                    }
                    else{
                        document.getElementById('output_image_Bl').style.display = "block";
                        document.getElementById('output_pdf_Bl').style.display = "none";
                        image.src = reader.result;
                    }
                }
                reader.readAsDataURL(event.target.files[0]);
                document.getElementById('btn_upload_Bl').style.display = "block";
            }
        </script>

<div class="container">
  <center>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajoutBl" data-dismiss="modal" >
        <i class="glyphicon glyphicon-plus"></i>Ajouter BL 
    </button>
  </center>
  <br />
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#bl">LISTER BL (Bon de Livraison)</a></li>
  </ul>
  <div class="tab-content">
    <div id="bl" class="tab-pane fade in active">
      <div class="table-responsive">
        <table id="tableBl" class="display" border="1">
          <thead>
            <tr>
                <th>Ordre</th>
                <th>Numero</th>
                <th>Fournisseur</th>
                <th>Date Arrivee</th>
                <th>Montant TTC</th>
                <th>Piece Jointe</th>
                <th>Operations</th>
            </tr>
          </thead>
        </table>

        <script type="text/javascript">
          $(document).ready(function() {
              $("#tableBl").dataTable({
                "bProcessing": true,
                "sAjaxSource": "ajax/listerBl-PharmacieAjax.php",
                "aoColumns": [
                      { mData: "0" } ,
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" },
                      { mData: "4" },
                      { mData: "5" },
                      { mData: "6" },
                    ],
                    
              });  
          });
        </script>

          <div id="ajoutBl" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ajouter BL</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <label for="ajtFournisseurBL">Fournisseur </label>
                        <select class="form-control" id="ajtFournisseurBL">
                            <option selected value= ""></option>
                            <?php
                                $sql11="SELECT * FROM `". $nomtableFournisseur."`";
                                $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                while($ligne2 = mysql_fetch_row($res11)) {
                                    echo'<option  value= "'.$ligne2['0'].'">'.$ligne2['1'].'</option>';

                                  } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="ajtNumeroBL">Numero </label>
                        <input type="text" class="inputbasic form-control" id="ajtNumeroBL" />
                      </div>
                      <div class="form-group">
                        <label for="ajtDateBL">Date </label>
                        <input type="date" class="inputbasic form-control" id="ajtDateBL" />
                      </div>
                      <div class="form-group">
                        <label for="ajtMontantBL">Montant TTC </label>
                        <input type="number" class="inputbasic form-control" id="ajtMontantBL" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_ajt_Bl" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div> 

          <div id="modifierBl" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modification BL</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <input type="hidden"  id="idBL_Mdf"  />
                        <input type="hidden"  id="ordreBL_Mdf"  />
                      </div>
                      <div class="form-group">
                        <label for="fournisseurBL_Mdf">Fournisseur </label>
                        <select class="form-control" id="fournisseurBL_Mdf">
                            <option selected ></option>
                            <?php
                                $sql11="SELECT * FROM `". $nomtableFournisseur."`";
                                $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                while($ligne2 = mysql_fetch_row($res11)) {
                                    echo'<option  value= "'.$ligne2['1'].'">'.$ligne2['1'].'</option>';

                                  } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="numeroBL_Mdf">Numero </label>
                        <input type="text" class="inputbasic form-control" id="numeroBL_Mdf" />
                      </div>
                      <div class="form-group">
                        <label for="dateBL_Mdf">Date </label>
                        <input type="date" class="inputbasic form-control" id="dateBL_Mdf" />
                      </div>
                      <div class="form-group">
                        <label for="montantBL_Mdf">Montant TTC </label>
                        <input type="number" class="inputbasic form-control" id="montantBL_Mdf" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_mdf_Bl" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>

          <div id="supprimerBl" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Suppression BL</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <input type="hidden"  id="idBL_Spm"  />
                        <input type="hidden"  id="ordreBL_Spm"  />
                      </div>
                      <div class="form-group">
                        <label for="fournisseurBL_Spm">Fournisseur </label>
                        <input type="text" disabled="true" class="inputbasic form-control" id="fournisseurBL_Spm" />
                      </div>
                      <div class="form-group">
                        <label for="numeroBL_Spm">Numero </label>
                        <input type="text" disabled="true" class="inputbasic form-control" id="numeroBL_Spm" />
                      </div>
                      <div class="form-group">
                        <label for="dateBL_Spm">Date </label>
                        <input type="date" disabled="true" class="inputbasic form-control" id="dateBL_Spm" />
                      </div>
                      <div class="form-group">
                        <label for="montantBL_Spm">Montant TTC </label>
                        <input type="number" disabled="true" class="inputbasic form-control" id="montantBL_Spm" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_spm_Bl" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>

          <div class="modal fade"  id="imageNvBl" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> BL : <b>#</b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idBl" id="idBl_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Bl" onchange="showPreviewBl(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Bl"/>
                                <iframe style="display:none;" id="output_pdf_Bl" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBl" id="btn_upload_Bl" >
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

<?php
}
?>
