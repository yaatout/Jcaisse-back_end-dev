<?php
session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduser']){
	header('Location:../index.php');
    }
    
require('connection.php');
require('connectionPDO.php');
require('declarationVariables.php');

/**********************/
/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/

$idReservation=htmlspecialchars(trim($_GET['id']));
  
$sql0="SELECT * FROM `".$nomtableReservation."` where idReservation=".$idReservation."";
$res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
$reservation = mysql_fetch_assoc($res0);

if ($_SESSION['compte']==1) {
    # code...
    /***Debut compte qui fait le paiement ***/
    $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";
    $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());
}

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgBon'])) {
    $idBl=htmlspecialchars(trim($_POST['idBon']));
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

/**Debut Button upload Image Versement**/
if (isset($_POST['btnUploadImgVersement'])) {
    $idVersement=htmlspecialchars(trim($_POST['idVersement']));
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

            $sql2="UPDATE `".$nomtableVersement."` set image='".$file."' where idVersement='".$idVersement."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Versement**/

require('entetehtml.php');
?>
<!-- Debut Code HTML -->
<body>
    <?php require('header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container" >

    <input id="inpt_Chambre_id" type="hidden" value="<?= $idReservation?>" />
       
    <div class="col-sm-4 pull-left" >
     <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="reservation.php" > Retour </a>
    </div>

    <div class="jumbotron noImpr">
        <h2>Les Details de la reservation de : <?php echo strtoupper($reservation['nom']).'  '.$reservation['prenom']; ?> </h2>
        <h4>
            <?php
                   echo 'Adresse : '.$reservation['adresse']; echo ' - ';
                   echo 'Telephone : '.$reservation['telephone']; echo ' - ';
                   echo 'Pays : '.$reservation['pays'] ;  echo '<br/>';
                   echo 'Date reservation : '.$reservation['dateReservation'].' a '.$reservation['heureReservation'] ;  
            ?>
        </h4>
        <div class="panel-group">
            <div class="panel" style="background:#cecbcb;">
                <div class="panel-heading" >
                    <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse1">Montant à verser : <span id="montantReservation"></span>  </a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse">
                    <div class="panel-heading" style="margin-left:2%;">
                        <h4>Total des Chambres : <span id="montantChambres"></span></h4>
                        <h4>Total des Pensions : <span id="montantPensions"></span></h4>
                        <h4>Total des Versements : <span id="montantVersements"></span></h4>
                    </div>
                </div>
            </div>
        </div>
        <form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"
            method="post" action="pdfOperationsFournisseur.php" >
            <input type="hidden" name="idReservation" id="idReservation"  <?php echo  "value=".$idReservation."" ; ?> >
            <button  class="btn btn-primary  pull-left" style="margin-left:20px;" >
                <span class="glyphicon glyphicon-print"></span> Impression Relevé
            </button>
        </form>
    </div>

        <!--*******************************Debut Bouttons****************************************-->
            <button type="button" class="btn btn-success noImpr pull-right"  data-toggle="modal" data-target="#ajouter_Versement">
                <i class="glyphicon glyphicon-plus"></i> Versement
            </button>

            <button type="button" style="margin-right:50px" class="btn btn-success pull-left" data-toggle="modal" data-target="#ajouter_Chambre">
                <i class="glyphicon glyphicon-plus"></i> Ajouter Chambre
            </button>

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ajouter_Pension">
                <i class="glyphicon glyphicon-plus"></i> Ajouter Pension
            </button>
        <!--*******************************Debut Bouttons****************************************-->


        <div id="ajouter_Pension" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajouter Pension</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                        <form name="formulaireCommande">
                            <div class="form-group">
                                <label for="slct_ajt_Pension_Reference">Pension </label>
                                <select onchange="choix_pension(this.value)" id="slct_ajt_Pension_Reference" class="form-control">
                                    <option>----------</option>
                                    <option value='Demi Pension<>15500' >Demi Pension </option>
                                    <option value='Pension Complete<>28500' >Pension complete</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Pension_Prix">Prix Pension</label>
                                <input type="number" class="inputbasic form-control" id="inpt_ajt_Pension_Prix" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Pension_Date">Date</label>
                                <input class="inputbasic form-control" type='date' size='11' maxLength='10' id="inpt_ajt_Pension_Date"  required=""/>
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Pension_Personne">Nombre de Personne(s)</label>
                                <input type="number" class="inputbasic form-control" id="inpt_ajt_Pension_Personne" value="1" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Pension_Description" class="control-label">Description</label>
                                <textarea  type="textarea" class="form-control" id="inpt_ajt_Pension_Description" placeholder="Description" ></textarea>
                                <span class="text-danger" ></span>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="button" id="btn_ajouter_Pension" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        <div id="ajouter_Chambre" class="modal fade" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ajouter Chambre</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" id="form_ajouter_Chambre"  >
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_Reference">Chambre </label>
                            <input type="text" class="inputbasic form-control inpt_ajt_Chambre_Reference"  id="inpt_ajt_Chambre_Reference" />
                        </div>
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_Prix">Prix</label>
                            <input type="number" class="form-control" id="inpt_ajt_Chambre_Prix"  />
                        </div>
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_DateArrivee">Date Arrivee </label>
                            <input class="inputbasic form-control" type='datetime-local' size='11' maxLength='10' id="inpt_ajt_Chambre_DateArrivee"  required=""/>
                        </div>
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_DateDepart">Date Depart </label>
                            <input class="inputbasic form-control" type='datetime-local' size='11' maxLength='10' id="inpt_ajt_Chambre_DateDepart"  required=""/>
                        </div>
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_Moins4">Age 0-4</label>
                            <input type="number" class="inputbasic form-control" id="inpt_ajt_Chambre_Moins4" value="0"/>
                        </div>
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_Moins12">Age 4-12</label>
                            <input type="number" class="inputbasic form-control" id="inpt_ajt_Chambre_Moins12" value="0"/>
                        </div>
                        <div class="form-group">
                            <label for="inpt_ajt_Chambre_Plus12">Adulte</label>
                            <input type="number" class="inputbasic form-control" id="inpt_ajt_Chambre_Plus12" value="0"/>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="button" id="btn_ajouter_Chambre" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div> 

        <div id="ajouter_Versement" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Nouveau versement</h4>
                    </div>
                    <div class="modal-body">
                        <form name="formulaireVersement">
                            <div class="form-group">
                                <label for="inpt_ajt_Versement_Description" class="control-label">Description</label>
                                <textarea  type="textarea" class="form-control" id="inpt_ajt_Versement_Description" name="typeVersement" placeholder="Description" ></textarea>
                                <span class="text-danger" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Versement_Montant" class="control-label">Montant</label>
                                <input type="number" class="form-control" id="inpt_ajt_Versement_Montant" name="montant" placeholder="Montant" required="">
                                <span class="text-danger" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Versement_Date" class="control-label">Date (jj-mm-aaaa)</label>
                                <input type="date" size='11' maxLength='10' id="inpt_ajt_Versement_Date"  class="form-control" required="">
                                <span class="text-danger" ></span>
                            </div>                                
                            <?php if ($_SESSION['compte']==1) { ?>
                                <select class="form-control compte" name="compte"  id="inpt_ajt_Versement_Compte">
                                    <!-- <option value="caisse">Caisse</option> -->
                                    <?php
                                        while ($cpt=mysql_fetch_array($resPay)) { ?>
                                            <option value="<?= $cpt['idCompte'] ; ?>"><?= $cpt['nomCompte'] ; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="button" id="btn_ajouter_Versement" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div id="supprimer_Chambre" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Supprimer Chambre</h4>
                    </div>
                    <div class="modal-body">
                        <form >
                        <div class="modal-body">
                            <div class="form-group">					    
                            <input type="hidden" class="form-control" id="inpt_spm_Chambre_numligne"  >
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Chambre_Reference">Chambre : </label>
                                <span id="inpt_spm_Chambre_Reference" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Chambre_Prix">Prix : </label>
                                <span id="inpt_spm_Chambre_Prix" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Chambre_DateArrivee">Date Arrivee : </label>
                                <span id="inpt_spm_Chambre_DateArrivee" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Chambre_DateDepart">Date Depart : </label>
                                <span id="inpt_spm_Chambre_DateDepart" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Chambre_Prixtotal">Montant : </label>
                                <span id="inpt_spm_Chambre_Prixtotal" ></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-6 "> 
                            <button type="button" id="btn_supprimer_Chambre" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

        <div id="supprimer_Pension" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Supprimer Pension</h4>
                    </div>
                    <div class="modal-body">
                        <form >
                        <div class="modal-body">
                            <div class="form-group">					    
                            <input type="hidden" class="form-control" id="inpt_spm_Pension_numligne"  >
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Pension_Reference">Pension : </label>
                                <span id="inpt_spm_Pension_Reference" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Pension_Prix">Prix : </label>
                                <span id="inpt_spm_Pension_Prix" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Pension_Date">Date : </label>
                                <span id="inpt_spm_Pension_Date" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Pension_Personne">Personne(s) : </label>
                                <span id="inpt_spm_Pension_Personne" ></span>
                            </div>
                            <div class="form-group">
                                <label for="inpt_spm_Pension_Prixtotal">Montant : </label>
                                <span id="inpt_spm_Pension_Prixtotal" ></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-6 "> 
                            <button type="button" id="btn_supprimer_Pension" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

        <div id="supprimer_Versement" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Supprimer Versement</h4>
                    </div>
                    <div class="modal-body">
                        <form >
                        <div class="modal-body">
                            <div class="form-group">					    
                                <input type="hidden" class="form-control" id="inpt_spm_Versement_idVersement"  >
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Versement_Numero">Numero : </label>
                                <span id="span_spm_Versement_Numero" ></span>
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Versement_Date">Date : </label>
                                <span id="span_spm_Versement_Date" ></span>
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Versement_Montant">Montant : </label>
                                <span id="span_spm_Versement_Montant" ></span>
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Versement_Description">Description : </label>
                                <span id="span_spm_Versement_Description" ></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-6 "> 
                            <button type="button" id="btn_supprimer_Versement" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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
            
        <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->
            <style >
                /* Firefox */
                input[type=number] {
                    -moz-appearance: textfield;
                }
                /* Chrome */
                input::-webkit-inner-spin-button,
                input::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin:0;
                }
                /* Opéra*/
                input::-o-inner-spin-button,
                input::-o-outer-spin-button {
                    -o-appearance: none;
                    margin:0
                }
            </style>
        <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
            <script >
                $(function() {
                    $(".expand").on( "click", function() {
                        // $(this).next().slideToggle(200);
                        $expand = $(this).find(">:first-child");

                        if($expand.text() == "+") {
                        $expand.text("-");
                        } else {
                        $expand.text("+");
                        }
                    });
                });
            </script>
        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->


        <!-- Debut de l'Accordion pour Tout les Paniers -->
        <div class="table-responsive">
            <div id="listerOperations"><!-- content will be loaded here --></div>
        </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->

        <div class="modal fade"  id="imageNvBon" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Bon : <b> <span id="idBon_View"></span></b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idBon" id="idBon_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Bon" onchange="showPreviewBon(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Bon"/>
                                <iframe style="display:none;" id="output_pdf_Bon" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBon" id="btn_upload_Bon" >
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade"  id="imageNvVersement" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Versement : <b> # <span id="idVersement_View"></span></b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idVersement" id="idVersement_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Versement" onchange="showPreviewVersement(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Versement"/>
                                <iframe style="display:none;" id="output_pdf_Versement" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgVersement" id="btn_upload_Versement" >
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                        <div class="modal-body">
                            <p></p>
                            <input type="hidden" name="idBl" id="idBl" />
                            <input type="hidden" name="montantB" id="montantBl"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="btnAnnulerBl" class="btn btn-success">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id=""  role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                        <div class="modal-body">
                            <p><?php echo "Voulez-vous annuler le versement numéro <b>".$n."<b>" ; ?></p>
                            <input type="hidden" name="idVersement" id="idVersement"  />
                            <input type="hidden" name="montant" id="montant" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- Fin Container HTML -->
</header>
</body>
<!-- Fin Code HTML -->

<script type="text/javascript" src="./scripts/detailsReservation.js"></script>
