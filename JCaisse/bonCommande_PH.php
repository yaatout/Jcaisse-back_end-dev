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


$id=htmlspecialchars(trim($_GET['id']));
$sql3="SELECT * FROM `".$nomtableBl."` where idBl=".$id."";
$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
$bl = mysql_fetch_assoc($res3);
/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 


?>

<body >
   <?php require('header.php'); ?>

   <input id="inpt_BL_id" type="hidden" value="<?= $id?>" />

  <div class="container">

  <div class="col-sm-4 pull-left" >
    <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="detailsFournisseur.php?iDS=<?php echo $bl['idFournisseur']; ?>" > Retour </a>
  </div>

  <div class="jumbotron noImpr">

    <h2>Bon de Commande => Numero : <?php echo $bl['numeroBl']; ?>  du <?php echo $bl['dateBl']; ?> </h2>

    <div class="panel-group">

        <div class="panel" style="background:#cecbcb;">

            <div class="panel-heading" >

                <h4 class="panel-title">

                <a data-toggle="collapse" href="#collapse1">Montant Total : <span id="montantServices"></span>  </a>

                </h4>

            </div>

        </div>

    </div>

  </div>


    <center> 
      <button type="button" class="btn btn-success" id="btn_lister_Produit">
        <i class="glyphicon glyphicon-plus"></i>Ajouter un Produit
      </button>
    </center> 

    <div class="modal fade" id="lister_Produit" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>
            </div>
            <div class="modal-body" >
                <div class="table-responsive">                
                  <table id="listeProduits" class="display tabStock" class="tableau3" width="100%" border="1">
                    <thead>
                        <tr>
                            <th>IdDesignation</th>
                            <th>Reference</th>
                            <th>Quantite</th>
                            <th>Forme</th>
                            <th>Prix_Session</th>
                            <th>Prix_Public</th>
                            <th>Expiration</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                  </table>
                </div>
            </div> 
        </div>
      </div>
    </div>

    <div id="supprimer_Stock" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Stock</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Stock_idProduit"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_Reference">Reference : </label>
                        <span id="span_spm_Stock_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_Quantite">Quantite : </label>
                        <span id="span_spm_Stock_Quantite" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_Forme">Forme : </label>
                        <span id="span_spm_Stock_Forme" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_PrixSession">Prix Session : </label>
                        <span id="span_spm_Stock_PrixSession" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_PrixPublic">Prix Public : </label>
                        <span id="span_spm_Stock_PrixPublic" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_DateExpiration">Date Expiration : </label>
                        <span id="span_spm_Stock_DateExpiration" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Stock" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

    <div id="transferer_Stock" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Transferer Stock</h4>
            </div>
            <form class="form" id="form_transferer_Stock" style="padding:30px 30px;" >
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_trsf_Stock_idProduit"  >
                </div>
                <div class="form-group">
                    <label for="inpt_trsf_Stock_Reference">Reference </label>
                    <input type="text" disabled="true" class="form-control" id="inpt_trsf_Stock_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_trsf_Stock_Quantite">Quantite</label>
                    <input type="number" disabled="true"  class="form-control" id="inpt_trsf_Stock_Quantite" />
                </div>
                <div class="form-group">
                    <label for="inpt_trsf_Stock_Forme">Forme</label>
                    <input type="text" disabled="true"  class="form-control" id="inpt_trsf_Stock_Forme"  />
                </div>
                <div class="form-group">
                    <label for="slct_trsf_Stock_Fournisseur">Fournisseur </label>
                    <select class="form-control" onchange="choix_BL_Fournisseur(this.value)" id="slct_trsf_Stock_Fournisseur">
                    </select>
                </div>
                <div class="form-group">
                    <label for="slct_trsf_Stock_BL"> Numero BL / Date Arrivee </label>
                    <select class="form-control"  id="slct_trsf_Stock_BL">
                    </select>
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_transferer_Stock" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Transferer</span> </button>
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
 
  <div class="container">
    <br />
    <ul class="nav nav-tabs">
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active">     
            <div class="table-responsive">  

                <table id="listeStock" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdStock</th>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Quantite</th>
                          <th>Forme</th>
                          <th>Prix Session</th>
                          <th>Prix Public</th>
                          <th>Montant Achat</th>
                          <th>Date Enregistre</th>
                          <th>Date Expiration</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>

            </div>
        </div>
    </div>
  </div>

</body>
</html>

  <script type="text/javascript" src="scripts/bonCommande_PH.js"></script>
