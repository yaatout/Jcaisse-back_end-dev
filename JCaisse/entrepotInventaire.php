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


$idEntrepot=htmlspecialchars(trim($_GET['iDS']));
$sql3="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot=".$idEntrepot."";
$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
$entrepot = mysql_fetch_assoc($res3);

$sql2="SELECT * FROM `".$nomtableEntrepotStock."` s
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE s.idEntrepot='".$idEntrepot."' AND d.classe=0 ORDER BY s.idEntrepotStock DESC";
$res2=mysql_query($sql2);
$montant=0;
while ($stock = mysql_fetch_array($res2)) {
  $sqlS="SELECT SUM(quantiteStockCourant)
  FROM `".$nomtableEntrepotStock."`
  where idDesignation ='".$stock['idDesignation']."' AND  idEntrepot ='".$idEntrepot."' ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_stock = mysql_fetch_array($resS);
  if($S_stock!=null && $S_stock!=0){
    $quantite=$S_stock[0]/ $stock['nbreArticleUniteStock'];
  }
  else{
    $quantite=0;
  }
  $montant=$montant + ($quantite * $stock['prixuniteStock']);
}


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');
   ?>

   <?php
   if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){
   
   }
   ?>
   


<?php

//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" >
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#INVENTAIRE"> Inventaire du Depot de : '.$entrepot['nomEntrepot'].' </a></li>
    <li><a data-toggle="tab" href="#INVENTAIREDETAIL"> Details Inventaire du Depot de : '.$entrepot['nomEntrepot'].' </a></li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="INVENTAIRE">';
        echo'<div class="table-responsive">
          <table id="tableStock" class="display tabStock" class="tableau3" align="left" border="1">
            <thead>
              <tr>
                <th>ORDRE</th>
                <th>REFERENCE</th>
                <th>CATEGORIE</th>
                <th>QUANTITE THEORIQUE</th>
                <th>QUANTITE PHYSIQUE</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
          </table>
        
          <script type="text/javascript">
            $(document).ready(function() {
                $("#tableStock").dataTable({
                  "bProcessing": true,
                  "sAjaxSource": "ajax/listerEntrepot-InventaireAjax.php?id='.$idEntrepot.'",
                  "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" },
                      ],
                      "dom": "Bfrtip",
                      "buttons" : [
                          "copy",
                        {
                          extend: "excel",
                          messageTop: "Inventaire du depot de : '.$entrepot['nomEntrepot'].'",
                        },
                        {
                          extend: "pdf",
                          messageTop: "Inventaire du depot de : '.$entrepot['nomEntrepot'].'",
                          messageBottom: null
                        },
                        {
                          extend: "print",
                          text: "Imprimer",
                          messageTop: "Inventaire du depot de : '.$entrepot['nomEntrepot'].'",
                        }
                      ]
                });  
            });
          </script>
        </div>
      </div>
      <div class="tab-pane fade" id="INVENTAIREDETAIL">';
      echo'<div class="table-responsive">
        <table id="tableInventaireDetails" class="display tabInventaireDetails" width="100%" border="1">
          <thead>
            <tr>
              <th>Ordre</th>
              <th>Reference</th>
              <th>Enregistrement</th>
              <th>Quantite Stock</th>
              <th>Quantite Inventaire</th>
              <th>Date Inventaire</th>
            </tr>
          </thead>
        </table>
      
        <script type="text/javascript">
          $(document).ready(function() {
              $("#tableInventaireDetails").dataTable({
                "bProcessing": true,
                "sAjaxSource": "ajax/listerStockInventaire-EntrepotAjax.php?id='.$idEntrepot.'",
                "aoColumns": [
                      { mData: "0" } ,
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" },
                      { mData: "4" },
                      { mData: "5" },
                    ]
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
