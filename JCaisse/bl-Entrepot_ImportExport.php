<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

/**********************/
$idStock          =@$_POST["idStock"];
$designation      =@htmlspecialchars(trim($_POST["designation"]));
$categorie2       =@htmlentities($_POST["categorie2"]);
$idDesignation    =@$_POST["idDesignation"];

$stock            =@$_POST["stock"];

$prixSession         =@$_POST["prixSession"];
$prixPublic     =@$_POST["prixPublic"];

$forme        =@$_POST["forme"];
$tableau      =@$_POST["tableau"];

$prixDeRevientDuStock     =@$_POST["prixDeRevientDuStock"];

$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];

$insererStock     =@$_POST["insererStock"];
$supprimer        =@$_POST["supprimer"];
$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
$insererDesignation   =@$_POST["insererDesignation"];

$idBl=htmlspecialchars(trim($_GET['id']));
$sql3="SELECT * FROM `".$nomtableBl."` where idBl=".$idBl."";
$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
$bl = mysql_fetch_assoc($res3);




/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');
   ?>

   <?php
   if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){
    echo'<div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
    <i class="glyphicon glyphicon-plus"></i>Ajouter Stock BL</button>';
   }

echo'<div class="modal fade" id="AjoutStockModal" role="dialog">';
echo'<div class="modal-dialog modal-lg">';
  echo'<div class="modal-content">';
    echo'<div class="modal-header" >';
    echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
    echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>";
      echo'</div>
        <div class="modal-body">';
          echo'
            <div class="table-responsive">
              <table id="tableStock0" class="display" width="100%" border="1">
                <thead>
                <tr id="thStock">
                  <th>Reference</th>
                  <th>Unite Stock (US)</th>
                  <th>Quantite</th>
                  <th>Prix(Achat)</th>
                  <th>Expiration</th>
                  <th>Operations</th> 
                </tr>
                </thead>
              </table>
            
              <script type="text/javascript">
                $(document).ready(function() {
                  $("#AjoutStock").click(function(){
                    $("#tableStock0").dataTable({
                    "bProcessing": true,
                    "sAjaxSource": "ajax/listeBl-Entrepot_ImportExportAjax.php?id='.$bl["idBl"].'",
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "5" },
                        { mData: "6" },
                      ],
                      
                    }); 
                  }); 
                });
              </script>
            </div>
          ';
          echo '
        </div>
      </div>
    </div>
  </div>
</div>';

/*****************************/




//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" align="center">
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL"> Numero BL : '.$bl['numeroBl'].'</a></li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
        echo'<div class="table-responsive">
          <table id="tableStock" class="display tabStock" class="tableau3" align="left" border="1">
            <thead>
              <tr id="thStock">
                <th>Ordre</th>
                <th>Reference</th>
                <th>Unite Stock (US)</th>
                <th>Initial</th>
                <th>Reste</th>
                <th>Prix Achat</th>
                <th>Prix US</th>
                <th>Enregistrement</th>
                <th>Expiration</th>
                <th>Operations</th> 
              </tr>
            </thead>
          </table>
        
          <script type="text/javascript">
            $(document).ready(function() {
                $("#tableStock").dataTable({
                  "bProcessing": true,
                  "sAjaxSource": "ajax/listerStockBl-EntrepotAjax.php?id='.$bl["idBl"].'",
                  "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" },
                        { mData: "6" },
                        { mData: "7" },
                        { mData: "8" },
                        { mData: "9" }
                      ],
                      
                });  
            });
          </script>
      
            <div id="modifierStockModal0" class="modal fade " role="dialog">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Modifier Stock</h4>
                      </div>
                      <div class="modal-body" style="padding:40px 50px;">
                          <form role="form" class="" >
                              <input type="hidden"  name="designation" id="ordre_Mdf0"  />
                              <input type="hidden"  name="designation" id="idStock_Mdf0"  />
                              <input type="hidden"  name="designation" id="forme_Mdf0"  />
                              <input type="hidden"  name="designation" id="dateStockage_Mdf0"  />
                              <div class="form-group">
                                  <label for="reference">REFERENCE <font color="red">*</font></label>
                                  <input type="text" class="form-control" name="designation" id="designation_Mdf0"  disabled="true" />
                              </div>
                              <div class="form-group" >
                                <label for="forme"> Quantite Initiale <font color="red">*</font></label>
                                <input type="text" class="form-control" name="qteInitiale" id="qteInitiale_Mdf0" />
                              </div>
                              <div class="form-group" >
                                <label for="prixSession">PRIX SESSION <font color="red">*</font> </label>
                                <input type="number" class="form-control" id="prixSession_Mdf0"  name="prixSession" required />
                              </div>
                              <div class="form-group" >
                                <label for="prixPublic">PRIX PUBLIC <font color="red">*</font></label>
                                <input type="number" class="form-control" id="prixPublic_Mdf0"  name="prixPublic" required />
                              </div>
                              <div class="form-group" >
                                <label for="tableau"> Date Expiration <font color="red">*</font></label>
                                <input type="date" class="form-control" name="dateExpiration" id="dateExpiration_Mdf0" required  />
                              </div>';
                              echo '<div align="right">
                                <br />
                                <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                                <input type="button" id="btn_mdf_Stock_P0BL" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
            </div> 

            <div id="modifierStockModal" class="modal fade " role="dialog">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Modifier Stock</h4>
                      </div>
                      <div class="modal-body" style="padding:40px 50px;">
                          <form role="form" class="" >
                              <input type="hidden"  name="designation" id="ordre_Mdf"  />
                              <input type="hidden"  name="designation" id="idStock_Mdf"  />
                              <input type="hidden"  name="designation" id="forme_Mdf"  />
                              <input type="hidden"  name="designation" id="dateStockage_Mdf"  />
                              <div class="form-group">
                                  <label for="reference">REFERENCE <font color="red">*</font></label>
                                  <input type="text" class="form-control" name="designation" id="designation_Mdf"  disabled="true" />
                              </div>
                              <div class="form-group" >
                                <label for="forme"> Quantite Initiale <font color="red">*</font></label>
                                <input type="text" class="form-control" name="qteInitiale" id="qteInitiale_Mdf" disabled="true" />
                              </div>
                              <div class="form-group" >
                                <label for="forme"> Quantite Restante<font color="red">*</font></label>
                                <input type="text" class="form-control" name="qteReste" id="qteReste_Mdf" required />
                              </div>
                              <div class="form-group" >
                                <label for="prixSession">PRIX SESSION <font color="red">*</font> </label>
                                <input type="number" class="form-control" id="prixSession_Mdf"  name="prixSession" required />
                              </div>
                              <div class="form-group" >
                                <label for="prixPublic">PRIX PUBLIC <font color="red">*</font></label>
                                <input type="number" class="form-control" id="prixPublic_Mdf"  name="prixPublic" required />
                              </div>
                              <div class="form-group" >
                                <label for="tableau"> Date Expiration <font color="red">*</font></label>
                                <input type="date" class="form-control" name="dateExpiration" id="dateExpiration_Mdf" required  />
                              </div>';
                              echo '<div align="right">
                                <br />
                                <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                                <input type="button" id="btn_mdf_Stock_PBL" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
            </div>
      
            <div id="supprimerStockModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirmation Suppression Stock</h4>
                        </div>
                        <div class="modal-body" style="padding:40px 50px;">
                            <form role="form" class="" >
                                <input type="hidden" name="designation" id="ordre_Spm" />
                                <input type="hidden" name="designation" id="idStock_Spm" />
                                <input type="hidden"  name="designation" id="forme_Spm" />
                                <input type="hidden"  name="designation" id="dateStockage_Spm" />
                                <div class="form-group">
                                  <label for="reference">REFERENCE </label>
                                  <input type="text" class="form-control" name="designation" id="designation_Spm"  disabled=""/>
                                </div>
                                <div class="form-group">
                                  <label for="categorie"> QUANTITE INITIALE </label>
                                  <input type="text" class="form-control" name="qteInitial" id="qteInitial_Spm"  disabled="true" />
                                </div>
                                <div class="form-group">
                                  <label for="categorie"> QUANTITE RESTANTE </label>
                                  <input type="text" class="form-control" name="qteReste" id="qteReste_Spm"  disabled="true" />
                                </div>
                                <div class="form-group">
                                  <label for="categorie"> DATE EXPIRATION </label>
                                  <input type="text" class="form-control" name="dateExpiration" id="dateExpiration_Spm"  disabled="true" />
                                </div> ';
                                echo'<div align="right"> <br/>
                                <font color="red"><b>Voulez-vous supprimer ce produit ?</b></font><br />
                                  <input type="button" id="btn_spm_Stock_PBL" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                                </div>
                            </form>
                        </div>
                  </div>
                </div>
            </div>

            <div id="transfererStockModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Confirmation Transfert Stock</h4>
                      </div>
                      <div class="modal-body" style="padding:40px 50px;">
                          <form role="form" class="" >
                              <input type="hidden" name="designation" id="ordre_Trf" />
                              <input type="hidden" name="designation" id="idStock_Trf" />
                              <div class="form-group">
                                <label for="reference">REFERENCE </label>
                                <input type="text" class="form-control" name="designation" id="designation_Trf"  disabled=""/>
                              </div>
                              <div class="form-group">
                                <label for="uniteStock"> Unite Stock </label>
                                <input type="text" class="form-control" name="uniteStock" id="uniteStock_Trf"  disabled="true" />
                              </div>
                              <div class="form-group">
                                <label for="categorie"> Fournisseur </label>
                                <select class="form-control" name="idBl"  id="idBl_Trf">
                                      <option selected ></option>';
                                      $sql11="SELECT * FROM `". $nomtableBl."` order by idBl desc";
                                      $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                      while($bl = mysql_fetch_array($res11)) {
                                        $sql1="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$bl['idFournisseur']."' ";
                                        $res1=mysql_query($sql1);
                                        $fourn=mysql_fetch_array($res1);
                                          echo'<option  value= "'.$bl['idBl'].'">'.$fourn['nomFournisseur'].' / '.$bl['numeroBl'].' / '.$bl['dateBl'].'</option>';
                                        }
                                  echo'
                                </select>
                              </div>
                              <div align="right"> <br/>
                              <font color="red"><b>Voulez-vous transferer ce produit ?</b></font><br />
                                <input type="button" id="btn_trf_Stock_Bl" class="boutonbasic" name="transferer" value=" Transferer >>" />
                              </div>
                          </form>
                      </div>
                </div>
              </div>
            </div>
      
        </div>
      </div>
  </div>
</div>';
?>

<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_info_js" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alerte</h4>
            </div>
            <div class="modal-body">
                <p>PROBLEME CONNECTION INTERNET .</br>
                </br> L'operation sur ce stock a échoué. Veuillez reessayer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

<?php
echo'</body></html>';


?>
