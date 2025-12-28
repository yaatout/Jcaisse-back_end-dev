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

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$datehier = date('d-m-Y', strtotime('-1 days'));
$datehier_Y = date('Y-m-d', strtotime('-1 days'));

$id=@$_GET['iDS'];
$sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$id."' ";
$res=mysql_query($sql);
$design=mysql_fetch_array($res);

$sqlD="SELECT SUM(quantiteStockinitial)
FROM `".$nomtableStock."`
where idDesignation ='".$id."'  ";
$resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
$D_stock = mysql_fetch_array($resD);

$sqlF="SELECT SUM(quantite)
FROM  `".$nomtableLigne."` l
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
where p.verrouiller=1 && p.type=0 && l.idDesignation='".$id."' ";
$resF=mysql_query($sqlF) or die ("select stock impossible =>".mysql_error());
$F_stock = mysql_fetch_array($resF);

$sqlS="SELECT SUM(quantiteStockCourant)
FROM `".$nomtableEntrepotStock."`
where idDesignation ='".$id."'  ";
$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
$S_stock = mysql_fetch_array($resS);

$sqlI="SELECT SUM(quantiteStockinitial)
FROM `".$nomtableStock."`
where idDesignation ='".$id."'  ";
$resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
$I_stock = mysql_fetch_array($resI);

$sqlE="SELECT SUM(quantiteStockinitial)
FROM `".$nomtableEntrepotStock."`
where idDesignation ='".$id."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
$resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
$E_stock = mysql_fetch_array($resE);

$sql1="SELECT i.idInventaire,i.idDesignation,i.quantite,i.quantiteStockCourant,i.nbreArticleUniteStock,d.prixachat from `".$nomtableInventaire."` i
INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation
where i.idDesignation='".$id."' order by i.idInventaire desc";
$res1=mysql_query($sql1);
$plus=0;
$moins=0;
while ($inventaire = mysql_fetch_array($res1)) {
  if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
    if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
      $plus = $plus + ((($inventaire['quantite'] - $inventaire['quantiteStockCourant']) / $inventaire['nbreArticleUniteStock']) * $inventaire['prixachat']);
    }
    else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
      $moins = $moins + ((($inventaire['quantiteStockCourant'] - $inventaire['quantite']) / $inventaire['nbreArticleUniteStock']) * $inventaire['prixachat']);
    }
  }
}


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');
echo'<div class="container" >';
?>
<div class="jumbotron">
  <div class="col-sm-3 pull-right" >

<script type="text/javascript">
$(document).ready(function() {
        var id = <?php echo json_encode($id); ?>;
        /**Debut Lister les Entrees de Stock */
          $("#tableStockEntrees").dataTable({
            "bProcessing": true,
            "destroy": true,
            "sAjaxSource": "ajax/listerStockEntrees-EntrepotAjax.php?id="+id,
            "aoColumns": [
                  { mData: "0" } ,
                  { mData: "1" },
                  { mData: "2" },
                  { mData: "3" },
                  { mData: "4" },
                  { mData: "5" },
                  { mData: "6" },
                  { mData: "7" },
                ],
                
          }); 
        /**Fin Lister les Entrees de Stock */
        /**Debut Lister le Stock dans les Entrepots */
          $("#tableStockEntrepot").dataTable({
            "bProcessing": true,
            "destroy": true,
            "sAjaxSource": "ajax/listerStockEntrepotAjax.php?id="+id,
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
        /**Fin Lister le Stock dans les Entrepots */
        /**Debut Lister les Sorties de Stock */
          $("#tableStockSorties").dataTable({
            "bProcessing": true,
            "destroy": true,
            "sAjaxSource": "ajax/listerStockSorties-EntrepotAjax.php?id="+id,
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
                  { mData: "9" },
                ],
          });
        /**Fin Lister les Sorties de Stock */
        /**Debut Lister les Inventaires de Stock*/
          $("#tableStockInventaire").dataTable({
            "bProcessing": true,
            "destroy": true,
            "sAjaxSource": "ajax/listerProduitInventaire-EntrepotAjax.php?id="+id,
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
        /**Fin Lister les Inventaires de Stock*/
      });
</script>
  
  </div>
  <h2><?php echo $design['designation']; ?></h2>
      <div class="panel-group">
          <div class="panel" style="background:#cecbcb;">
              <div class="panel-heading">
                  <h4 class="panel-title">
                  <a data-toggle="collapse" href="#collapse1">Total Stock Depot : 
                   <?php 
                    if($S_stock[0]!=0 && $S_stock[0]!=null){
                      echo ($S_stock[0]/$design['nbreArticleUniteStock']).' '.$design['uniteStock'];
                    }
                    else{
                      echo '0 '.$design['uniteStock'];
                    }
                    
                   ?> 
                   <=>  Total Stock Sans Depot : <?php echo ($I_stock[0]-$E_stock[0]).' '.$design['uniteStock']; ?> </a>
                  </h4>
              </div>
              <div id="collapse1" class="panel-collapse collapse">
                  <div class="panel-heading" style="margin-left:2%;">
                      <?php 
                            $sqlES="SELECT * from `".$nomtableEntrepot."` order by idEntrepot desc";
                            $resES=mysql_query($sqlES);
                            while($entrepot=mysql_fetch_array($resES)){
                              $sqlE="SELECT SUM(quantiteStockCourant) 
                              FROM `".$nomtableEntrepotStock."`
                              where idDesignation ='".$id."' AND idEntrepot='".$entrepot['idEntrepot']."' ";
                              $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
                              $E_stock = mysql_fetch_array($resE);
                              if($E_stock[0]!=null && $E_stock[0]!=0){
                                echo '<h5> '.$entrepot['nomEntrepot'].' : '.($E_stock[0]/$design['nbreArticleUniteStock']).' '.$design['uniteStock'].'</h5>';
                              }
                            }
                            if($_SESSION['proprietaire']==1){ 
                                echo '<h5> VALEUR INVENTAIRE = '.number_format(($moins - $plus), 2, ',', ' ').' FCFA </h5>';
                            }                    
                        ?>
                            
                  </div>
              </div>
          </div>
      </div>
      

      <?php 
          if ($_SESSION["infoSup"]==1) {
      ?>
      <div class="row" align="center">
        <input type="date" name="" id="date1">&ensp;&ensp;
        <input type="date" name="" id="date2">&ensp;&ensp;
        <button class="btn btn-info btn-sm" id="btnQtyByPeriod" onclick="sumQtyByPeriod(<?= $id; ?>)" type="button">Valider</button>
      </div>
      <div id="sumQtyByPeriod" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header panel-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Total vente du <span id="spandate1"></span> au <span id="spandate2"></span></h3>
                </div>
                <div class="modal-body" id="sumQtyByPeriod_body">
                    <div class="form-group col-md-6">
                        <h4 class="col-form-label" for="">Quantité totale : </h4>                        
                    </div>
                    <div class="form-group col-md-6">
                        <h4 class="col-form-label" id="labelTotal" for=""></h4>                        
                    </div>                                                                                        
                <!-- </div>
                <div class="modal-footer"> -->
                    <button type="button" id="closeInfo" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
      </div>
      <?php 
        } 
      ?>
</div>
<?php
  echo'
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#STOCKENTREES">LISTE DES ENTREES DE STOCK</a></li>
    <li><a data-toggle="tab" href="#STOCKDEPOT">LISTE DU STOCK DANS LES DEPOTS</a></li>
    <li><a data-toggle="tab" href="#STOCKSORTIES">LISTE DES SORTIES DE STOCK</a></li>
    <li><a data-toggle="tab" href="#STOCKINVENTAIRE">LISTE DES INVENTAIRES DE STOCK</a></li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="STOCKENTREES">
        <div class="table-responsive">
          <table id="tableStockEntrees" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
              <tr>
                <th>Ordre</th>
                <th>Enregistrement</th>
                <th>Initial</th>
                <th>Unite Stock (US)</th>
                <th>Prix (US)</th>
                <th>Expiration</th>
                <th>Personnel</th>
                <th>Operations</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="STOCKDEPOT">
        <div class="table-responsive">
          <table id="tableStockEntrepot" class="display tabStockEntrees" width="100%" border="1">
            <thead>
              <tr>
                <th>Ordre</th>
                <th>Depot</th>
                <th>Transfert</th>
                <th>Initial</th>
                <th>Restant</th>
                <th>Unite Stock</th>
                <th>Prix Unite Stock</th>
                <th>Enregistrement</th>
                <th>Expiration</th>
                <th>Operations</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="STOCKSORTIES">
        <div class="table-responsive">
          <table id="tableStockSorties" class="display tabStockSorties" width="100%" border="1">
            <thead>
              <tr>
                <th>Ordre</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Quantite</th>
                <th>Unite Vente</th>
                <th>Prix Unite Vente</th>
                <th>Prix Total</th>
                <th>Depot</th>
                <th>Facture</th>
                <th>Client</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="STOCKINVENTAIRE">
        <div class="table-responsive">
          <table id="tableStockInventaire" class="display tabStockInventaire" width="100%" border="1">
            <thead>
              <tr>
                <th>Ordre</th>
                <th>Depot</th>
                <th>Enregistrement</th>
                <th>Quantite Stock</th>
                <th>Quantite Inventaire</th>
                <th>Type Inventaire</th>
                <th>Date Inventaire</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </div>
</div>';
?>

    <div id="ajoutStockModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ajouter Stock entrepot</h4>
          </div>
          <div class="modal-body" style="padding:40px 50px;">
            <form class="form" >
              <div class="form-group">
                <input type="hidden"  id="ajtStock"  />
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
                <label for="ajtQuantite">Quantite Unite Stock </label>
                <input type="text" class="inputbasic form-control" id="ajtQuantite" />
              </div>
              <div class="modal-footer row">
                <div class="col-sm-3 "> <input type="button" id="btn_ajt_StockEntrepot" class="btn_CodeDesign_P boutonbasic btn_disabled_after_click"  value=" Enregistrer >>" /></div>
                <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

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
                  <select class="form-control" id="idEntrepot_Mdf" disabled="true">
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
                  <label for="quantite_Mdf">Quantite Unite stock</label>
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
                  <label for="quantite_Spm">Quantite Unite Stock</label>
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
                      <div class="form-group">
                          <label for="reference">REFERENCE <font color="red">*</font></label>
                          <input type="text" class="form-control" name="designation" id="designation_Mdf"  disabled="true" />
                      </div>
                      <div class="form-group" >
                        <label for="dateStockage"> Date Enregistrement <font color="red">*</font></label>
                        <input type="date" class="form-control" name="dateStockage" id="dateStockage_Mdf" disabled="true"  />
                      </div>
                      <div class="form-group" >
                        <label for="prixus_Mdf"> Prix (US)<font color="red">*</font></label>
                        <input type="text" class="form-control" name="prixus" id="prixus_Mdf"  />
                      </div>
                      <div class="form-group" >
                        <label for="forme"> Quantite<font color="red">*</font></label>
                        <input type="text" class="form-control" name="qteInitial" id="qteInitiale_Mdf"  />
                      </div>
                      <div class="form-group" >
                        <label for="dateExpiration"> Date Expiration <font color="red">*</font></label>
                        <input type="date" class="form-control" name="dateExpiration" id="dateExpiration_Mdf" required  />
                      </div>
                      <div align="right">
                        <br />
                        <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                        <input type="button" id="btn_mdf_Stock_ET" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
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
                        <div class="form-group">
                          <label for="reference">REFERENCE </label>
                          <input type="text" class="form-control" name="designation" id="designation_Spm"  disabled=""/>
                        </div>
                        <div class="form-group">
                          <label for="dateStockage"> Date Enregistrement </label>
                          <input type="text" class="form-control" name="dateStockage" id="dateStockage_Spm"  disabled="true" />
                        </div>
                        <div class="form-group">
                          <label for="categorie"> Quantite</label>
                          <input type="text" class="form-control" name="qteInitial" id="qteInitial_Spm"  disabled="true" />
                        </div>
                        <div class="form-group">
                          <label for="categorie"> Date Expiration </label>
                          <input type="text" class="form-control" name="dateExpiration" id="dateExpiration_Spm"  disabled="true" />
                        </div>
                        <div align="right"> <br/>
                        <font color="red"><b>Voulez-vous supprimer ce produit ?</b></font><br />
                          <input type="button" id="btn_spm_Stock_ET" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
                        </div>
                    </form>
                </div>
          </div>
        </div>
    </div>

    <div id="transfertStockModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Transfert Stock Entrepot</h4>
          </div>
          <div class="modal-body" style="padding:40px 50px;">
            <form class="form" >
              <div class="form-group">
                <input type="hidden"  id="transfertStock"  />
              </div>
              <div class="form-group">
                <label for="tftEntrepot">Entrepot </label>
                <select class="form-control" id="tftEntrepot">
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
                <label for="tftQuantite">Quantite Unite Stock </label>
                <input type="text" class="inputbasic form-control" id="tftQuantite" />
              </div>
              <div class="modal-footer row">
                <div class="col-sm-3 "> <input type="button" id="btn_tft_StockEntrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
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

<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_info_jsET" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alerte</h4>
            </div>
            <div class="modal-body">
                <p>IMPOSSIBLE .</br>
                </br> La quantite restante de ce Stock est insuffisante.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_info_jsDP" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alerte</h4>
            </div>
            <div class="modal-body">
                <p>IMPOSSIBLE .</br>
                </br> Ce transfert ne peut pas avoir lieu.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->


    <div id="imprimerCodeBarreStockModal" class="modal fade " role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Imprimer Code Barre</h4>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                  <form role="form" id="barcode" method="post" action="barcode.php" target="_blank" >
                      <input type="hidden" name="operation" value="2"  />
                      <input type="hidden" name="ordre" id="ordreCB_Imp"  />
                      <input type="hidden" name="idStock" id="idStockCB_Imp"  />
                      <input type="hidden" name="designation" id="designationCB_Imp"  />
                      <input type="hidden" name="prixUnitaire" id="prixUnitaireCB_Imp"  />
                      <input type="hidden" name="codeBarreuniteStock" id="codeBarreuniteStockCB_Imp"  />
                      <div class="form-group" >
                        <label for="dateStockage"> Date Enregistrement <font color="red">*</font></label>
                        <input type="date" class="form-control" name="dateStockage" id="dateStockageCB_Imp" disabled="true"  />
                      </div>
                      <div class="form-group" >
                        <label for="qteReste"> Quantite a Imprimer<font color="red">*</font></label>
                        <input type="text" class="form-control" name="quantite" id="qteCB_Imp"  />
                      </div>
                      <div align="right">
                        <br />
                        <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                        <input onclick="document.getElementById('barcode').submit();"  class="boutonbasic"  name="btnModifier" value="IMPRIMER  >>"/>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>

<?php
echo'</body></html>';


?>
