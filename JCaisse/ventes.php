<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

date_default_timezone_set('Africa/Dakar');

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');


?>

<?php require('entetehtml.php'); ?>

<body>
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

  
  if (($_SESSION['proprietaire']==0 && $_SESSION['gerant']==0 && $_SESSION['gestionnaire']==0) && ($_SESSION['vendeur']==1 || $_SESSION['caissier']==1)) {
    echo '<input  style="display:none" type="number" id="inpt_Type_Vente_Local" value="1" />';
  }
  else{
    echo '<input  style="display:none" type="number" id="inpt_Type_Vente_Local" value="0" />';
  }
  
 
?>

<div class="container">

    <div class="jumbotron">
        <div class="col-sm-4 pull-right" >
            <form name="searchDesignationForm" id="searchDesignationForm" method="post" >

                <div class="form-group" >

                    <input type="text" class="form-control" placeholder="Recherche Prix..." id="designationInfo" name="designation" autocomplete="off"  size="100"/>


                </div>

            </form>

        </div>
        <h2>Journal de caisse du : <?php echo $dateString2; ?></h2>
    </div>


    <form  class="pull-right form-inline" id="searchProdForm" method="post" name="searchProdForm" >
        <div class="form-group">
            <img src="images/loading-gif3.gif" class="img-load-search form-group" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 
        </div> 
    </form>
    <div class="pull-right">
        <input type="text" size="45" class="form-control" name="produit" placeholder="Rechercher produit vendu..."  id="inpt_Search_ListerVentes" autocomplete="off" />
    </div>   

    <?php  

        $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

        $res0=mysqli_query($conn,$sql0);
        $ps = mysqli_fetch_array($res0) ;
        $idPS = @$ps['idPS'];
        $montantFixePayement = @$ps['montantFixePayement'];

        if(mysqli_num_rows($res0)){

            if($jour > 0){

                if($jour > 4){
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button disabled="true" type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()" name="btnSavePagnetVente" id="btn_Ajouter_Panier">
                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                            </button>
                        </form>
                    '; 
                } 
                else{
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()" name="btnSavePagnetVente" id="btn_Ajouter_Panier">
                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                            </button>
                        </form>
                    ';
                }

                echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='selectNbMoisPaiement(".$idPS.",".$montantFixePayement.")' style='text-decoration:underline;'>Payer <img src='images/Wave.png' width='25' height='25'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a hidden href='#' onclick='effectue_paiement(".$idPS.")' style='text-decoration:underline;'><img src='images/Orange.png' width='25' height='25'></a></h6>";

                echo '<br>';

            }

            else{

                echo ' 
                    <form name="formulairePagnet" method="post" >
                        <button type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()" name="btnSavePagnetVente" id="btn_Ajouter_Panier">
                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                            <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                        </button>
                    </form>
                ';

                echo '<br>';

            }

        }
        else{

            echo ' 
                <form name="formulairePagnet" method="post" >
                    <button type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()" name="btnSavePagnetVente" id="btn_Ajouter_Panier">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                    </button>
                </form>
            ';

            echo '<br>';

        }										

    ?>

    <div class="table-responsive">
            <img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
            <div id="listerVentes"><!-- content will be loaded here --></div>
    </div>

    <div id="action_Ligne" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id='spn_hd_action_Ligne'></span></h4>
                </div>
                  <div class="modal-body">
                        <input type="hidden" id="inpt_retourner_Ligne_numligne" />
                        <input type="hidden" id="inpt_retourner_Ligne_idPagnet" />
                        <span id='spn_bd_action_Ligne'></span>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_retourner_Ligne" class="btn btn-danger pull-left"><span id='btn_action_Ligne'></span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div id="action_Panier" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id='spn_hd_action_Panier'></span></h4>
                </div>
                  <div class="modal-body">
                        <input type="hidden" id="inpt_retourner_Panier" />
                        <span id='spn_bd_action_Panier'></span>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_retourner_Panier" class="btn btn-danger pull-left"><span id='btn_action_Panier'></span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div id="facture_Panier" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Informations du Client</h4>
                </div>
                  <div class="modal-body" style="padding:40px 50px;">
                        <input type="hidden" id="inpt_id_Facture" />
                        <div class="row">
                            <label for="inpt_client_Facture">Client </label>
                            <input type="text" class="form-control" id="inpt_client_Facture"  />
                        </div>  
                        <div class="row">
                            <label for="inpt_adresse_Facture">Adresse </label>
                            <input type="text" class="form-control" id="inpt_adresse_Facture"  />
                        </div>  
                        <div class="row">
                            <label for="inpt_telephone_Facture">Telephone </label>
                            <input type="text" class="form-control" id="inpt_telephone_Facture"  />
                        </div>  
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_facture_Panier" class="btn btn-success pull-left">Facture</button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>


</div>

<script type="text/javascript" src="./scripts/ventes.js"></script>

<script type="text/javascript" src="./load/loadData.js"></script>
