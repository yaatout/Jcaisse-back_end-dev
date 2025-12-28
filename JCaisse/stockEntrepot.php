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


$idStock=htmlspecialchars(trim($_GET['iDS']));
$sql3="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock."";
$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
$stock = mysql_fetch_assoc($res3);


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
    <i class="glyphicon glyphicon-plus"></i>Ajouter Stock Entrepot</button>';
   }
   ?>
   
   <div id="AjoutStockModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ajouter Stock entrepot</h4>
          </div>
          <div class="modal-body" style="padding:40px 50px;">
            <form class="form" >
              <div class="form-group">
                <input type="hidden"  id="ajtStock" <?php echo  "value=".$idStock."" ; ?> />
              </div>
              <div class="form-group">
                <label for="ajtEntrepot">Entrepot </label>
                <select class="form-control" id="ajtEntrepot">
                    <option selected value= "">------------</option>
                    <?php
                        $sql11="SELECT * FROM `". $nomtableEntrepot."`";
                        $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                        while($ligne2 = mysql_fetch_row($res11)) {
                            echo'<option  value= "'.$ligne2['0'].'">'.$ligne2['1'].'</option>';

                          } ?>
                </select>
              </div>
              <div class="form-group">
                <label for="ajtQuantite">Quantite </label>
                <input type="text" class="inputbasic form-control" id="ajtQuantite" />
              </div>
              <div class="modal-footer row">
                <div class="col-sm-3 "> <input type="button" id="btn_ajt_StockEntrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php

//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" align="center">
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL"> '.$stock['designation'].' / Nombre Total : '.($stock['quantiteStockinitial'] * $stock['nbreArticleUniteStock']).' </a></li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
        echo'<div class="table-responsive">
          <table id="tableStock" class="display tabStock" class="tableau3" align="left" border="1">
            <thead>
              <tr>
                <th>Ordre</th>
                <th>Entrepot</th>
                <th>Reference</th>
                <th>Categorie</th>
                <th>Quantite</th>
                <th>Unite Stock</th>
                <th>Nombre Initial</th>
                <th>Restant</th>
                <th>Date Stockage</th>
                <th>Operations</th>
              </tr>
            </thead>
          </table>
        
          <script type="text/javascript">
            $(document).ready(function() {
                $("#tableStock").dataTable({
                  "bProcessing": true,
                  "sAjaxSource": "ajax/listerStockEntrepotAjax.php?id='.$idStock.'",
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
        </div>
      </div>
  </div>
</div>';
?>

          <div id="modifierStockEntrepot" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modification Stock Entrepot</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <input type="hidden"  id="idStockEntrepot_Mdf"  />
                        <input type="hidden"  id="ordreStockEntrepot_Mdf"  />
                      </div>
                      <div class="form-group">
                        <label for="idEntrepot_Mdf">Entrepot </label>
                        <select class="form-control" id="idEntrepot_Mdf">
                            <option selected></option>
                            <?php
                                $sql11="SELECT * FROM `". $nomtableEntrepot."`";
                                $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                while($ligne2 = mysql_fetch_row($res11)) {
                                    echo'<option  value= "'.$ligne2['0'].'">'.$ligne2['1'].'</option>';
                                  } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="quantite_Mdf">Quantite </label>
                        <input type="text" class="inputbasic form-control" id="quantite_Mdf" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_mdf_StockEntrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>

          <div id="supprimerStockEntrepot" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Suppression Stock Entrepot</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <input type="hidden"  id="idStockEntrepot_Spm"  />
                        <input type="hidden"  id="ordreStockEntrepot_Spm"  />
                      </div>
                      <div class="form-group">
                        <label for="idEntrepot_Spm">Entrepot </label>
                        <input type="text" class="form-control" id="idEntrepot_Spm"  disabled="true" />
                      </div>
                      <div class="form-group">
                        <label for="quantite_Spm">Quantite </label>
                        <input type="text" class="inputbasic form-control" id="quantite_Spm" disabled="true" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_spm_StockEntrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>

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
