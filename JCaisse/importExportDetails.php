<?php

session_start();

if(!$_SESSION['iduser']){

	header('Location:../index.php');

    }

    

require('connection.php');

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

    $dateHeures=$dateString.' '.$heureString;

/**Fin informations sur la date d'Aujourdhui **/



$idVoyage=htmlspecialchars(trim($_GET['iDS']));

$sql0="SELECT * FROM `".$nomtableVoyage."` where idVoyage=".$idVoyage."";

$res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());

$voyage = mysql_fetch_assoc($res0);



if ($_SESSION['compte']==1) {

    /***Debut compte qui reçoit paiement ***/

    $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte <> 2 and idCompte<>3 ORDER BY idCompte";

    $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());



    $cpt_array = [];

    while ($cpt = mysql_fetch_array($resPay)) {

        # code...

        $cpt_array[] = $cpt;  // var_dump($key);

    }

}

/**Debut Button Terminer BL**/

if (isset($_POST['btnEnregistrerBl'])) {

    if($_SESSION['importExp']==1){

        $numero=htmlspecialchars(trim($_POST['numeroBl']));

        $fournisseur=htmlspecialchars($_POST['fournisseur']);

        $dateBl=htmlspecialchars(trim($_POST['dateBl']));

        $montantDevise=htmlspecialchars(trim($_POST['montantDevise']));

        $devise=htmlspecialchars($_POST['devise']);

        $valeurDevise=htmlspecialchars(trim($_POST['valeurDevise']));

        $valeurConversion=htmlspecialchars(trim($_POST['valeurConversion']));

        $montantBl=($montantDevise / $valeurDevise) * $valeurConversion;

        $sql1='INSERT INTO `'.$nomtableBl.'` (idFournisseur,idVoyage,numeroBl,dateBl,heureBl,montantBl,montantDevise,devise,valeurDevise,valeurConversion) 

        VALUES ("'.$fournisseur.'","'.$idVoyage.'","'.$numero.'","'.$dateBl.'","'.$heureString.'","'.$montantBl.'","'.$montantDevise.'","'.$devise.'","'.$valeurDevise.'","'.$valeurConversion.'")';

        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

        updateTauxRevient($idVoyage,$nomtableBl,$nomtableFrais,$nomtableVoyage);

    }

}

/**Fin Button Terminer BL**/



/**Debut Button Annuler BL**/

if (isset($_POST['btnAnnulerBl'])) {

    $idBl=htmlspecialchars(trim($_POST['idBl']));

    //on fait la suppression de cette Versement

    $sql3="DELETE FROM `".$nomtableBl."` where idBl=".$idBl;

    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    updateTauxRevient($idVoyage,$nomtableBl,$nomtableFrais,$nomtableVoyage);

}

/**Fin Button Annuler BL**/



/**Debut Button Terminer Frais**/

if (isset($_POST['btnEnregistrerFrais'])) {

    if($_SESSION['importExp']==1){

        $frais=htmlspecialchars(trim($_POST['frais']));

        $dateFrais=htmlspecialchars(trim($_POST['dateFrais']));

        $montantDevise=htmlspecialchars(trim($_POST['montantDevise']));

        $devise=htmlspecialchars($_POST['devise']);

        $valeurDevise=htmlspecialchars(trim($_POST['valeurDevise']));

        $valeurConversion=htmlspecialchars(trim($_POST['valeurConversion']));

        $montantFrais=($montantDevise / $valeurDevise) * $valeurConversion;

        $sql1='INSERT INTO `'.$nomtableFrais.'` (idVoyage,frais,dateFrais,heureFrais,montantFrais,montantDevise,devise,valeurDevise,valeurConversion) 

        VALUES ("'.$idVoyage.'","'.$frais.'","'.$dateFrais.'","'.$heureString.'","'.$montantFrais.'","'.$montantDevise.'","'.$devise.'","'.$valeurDevise.'","'.$valeurConversion.'")';

        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

        updateTauxRevient($idVoyage,$nomtableBl,$nomtableFrais,$nomtableVoyage);



        if ($_SESSION['compte']==1) {

            if (isset($_POST['compte'])) {

                $idCompteDebiter=htmlspecialchars(trim($_POST['compte']));

                # code...

                $operation='retrait';

                // $idCompte='2';

                $description="Frais import-export";



                $sql100="select * from `".$nomtableFrais."` ORDER BY idFrais DESC LIMIT 1";

                $res100=mysql_query($sql100);

                $idFFetch = mysql_fetch_array($res100) ;

                $idFrais = $idFFetch['idFrais'];

                // var_dump($idCompte);

                // $description=$refTransf;

                // $newMontant=$compte['montantCompte']+$montantTransfert;



                $sql8="UPDATE `".$nomtableFrais."` set idCompte=".$idCompteDebiter." where  idFrais=".$idFrais;

                $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

    

                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montantFrais." where  idCompte=".$idCompteDebiter;

                //var_dump($sql7);

                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

    

                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idFrais,idUser) values(".$montantFrais.",'".$operation."',".$idCompteDebiter.",'".$description."','".$dateHeures."','".$dateHeures."',".$idFrais.",".$_SESSION['iduser'].")";

                //var_dump($sql6);

                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

            }

        }

    }

}

/**Fin Button Terminer Frais**/



/**Debut Button Annuler Frais**/

if (isset($_POST['btnAnnulerFrais'])) {

    $idFrais=htmlspecialchars(trim($_POST['idFrais']));

    //on fait la suppression de cette Versement

    

    if ($_SESSION['compte']==1) {            



        $sqlGetComptePay2="SELECT * FROM `".$nomtableFrais."` where idFrais=".$idFrais;

        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());

        $f = mysql_fetch_array($resPay2);

        $idCompte = $f['idCompte'];

        $montantFrais = $f['montantFrais'];



        $sql70="UPDATE `".$nomtableCompte."` set  montantCompte=montantCompte+".$montantFrais." where  idCompte='".$idCompte."'";

        $res70=@mysql_query($sql70) or die ("test 1 pour activer ou pas ".mysql_error());

        

        //on fait la suppression des mouvements

        $sql8="DELETE FROM `".$nomtableComptemouvement."` where idFrais=".$idFrais;

        $res8=@mysql_query($sql8) or die ("mise à jour client  impossible".mysql_error());

    }

    $sql3="DELETE FROM `".$nomtableFrais."` where idFrais=".$idFrais;

    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

    updateTauxRevient($idVoyage,$nomtableBl,$nomtableFrais,$nomtableVoyage);

}

/**Fin Button Annuler Frais**/



/**Debut Button Rafraichir Taux Revient**/

if (isset($_POST['btnRafraichirRevient'])) {

    $tauxRevient=htmlspecialchars(trim($_POST['tauxRevient']));

    $sqlS="UPDATE `".$nomtableVoyage."` set tauxRevient='".$tauxRevient."' WHERE idVoyage=".$idVoyage." ";

    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



    $sqlV="SELECT * FROM `".$nomtableVoyage."` where idVoyage=".$idVoyage."";

    $resV = mysql_query($sqlV) or die ("persoonel requête 3".mysql_error());

    $voyage = mysql_fetch_assoc($resV);



    $sqlT1="SELECT * FROM `".$nomtableBl."` where idVoyage='".$idVoyage."'  ";

    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

    while ($bl=mysql_fetch_assoc($resT1)){

        $sql0="SELECT * FROM `".$nomtableStock."` where idBl=".$bl['idBl']."";

        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());

        while ($stock=mysql_fetch_assoc($res0)){

            $prixRevient=($tauxRevient * $stock['prixachat']) + $stock['prixachat'];

            $prixVente=round(($voyage['tauxVente'] * $prixRevient) + $prixRevient);

    

            $sqlS="UPDATE `".$nomtableStock."` set prixuniteStock=".$prixVente." WHERE idStock=".$stock['idStock']." ";

            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



            $sqlD="UPDATE `".$nomtableDesignation."` set prixachat=".$stock['prixachat'].",prixuniteStock=".$prixVente." WHERE idDesignation=".$stock['idDesignation']." ";

            $resD=mysql_query($sqlD) or die ("update quantiteStockCourant impossible =>".mysql_error());

        }

    }

}

/**Fin Button Rafraichir Taux Revient**/



/**Debut Button Rafraichir Taux vente**/

if (isset($_POST['btnRafraichirVente'])) {

    $tauxVente=htmlspecialchars(trim($_POST['tauxVente']));

    $sqlS="UPDATE `".$nomtableVoyage."` set tauxVente=".$tauxVente." WHERE idVoyage=".$idVoyage." ";

    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



    $sqlV="SELECT * FROM `".$nomtableVoyage."` where idVoyage=".$idVoyage."";

    $resV = mysql_query($sqlV) or die ("persoonel requête 3".mysql_error());

    $voyage = mysql_fetch_assoc($resV);



    $sqlT1="SELECT * FROM `".$nomtableBl."` where idVoyage='".$idVoyage."'  ";

    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

    while ($bl=mysql_fetch_assoc($resT1)){

        $sql0="SELECT * FROM `".$nomtableStock."` where idBl=".$bl['idBl']."";

        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());

        while ($stock=mysql_fetch_assoc($res0)){

            $prixRevient=($voyage['tauxRevient'] * $stock['prixachat']) + $stock['prixachat'];

            $prixVente=round(($tauxVente * $prixRevient) + $prixRevient);

    

            $sqlS="UPDATE `".$nomtableStock."` set prixuniteStock=".$prixVente." WHERE idStock=".$stock['idStock']." ";

            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



            $sqlD="UPDATE `".$nomtableDesignation."` set prixachat=".$stock['prixachat'].",prixuniteStock=".$prixVente." WHERE idDesignation=".$stock['idDesignation']." ";

            $resD=mysql_query($sqlD) or die ("update quantiteStockCourant impossible =>".mysql_error());

        }

    }

}

/**Fin Button Rafraichir Taux vente**/

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

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgFrais'])) {
    $idFrais=htmlspecialchars(trim($_POST['idFrais']));
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

            $sql2="UPDATE `".$nomtableFrais."` set image='".$file."' where idFrais='".$idFrais."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Bl**/



require('entetehtml.php');

?>

<!-- Debut Code HTML -->

<body onLoad="">

<header>

    <?php require('header.php'); ?>

    <!-- Debut Container HTML -->

    <div class="container" >

        <?php

            $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idVoyage=".$idVoyage." ";

            $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

            $TotalB = mysql_fetch_array($res12) ; 



            $sql13="SELECT SUM(montantFrais) FROM `".$nomtableFrais."` where idVoyage=".$idVoyage." ";

            $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

            $TotalF = mysql_fetch_array($res13) ;

            

            if($TotalB[0]!=0 || $TotalB[0]!=null){

                $TauxRevient=$TotalF[0]/$TotalB[0]; 

            }

            else{

                $TauxRevient=0;

            }





        ?>



        <div class="jumbotron noImpr">

            <div class="col-sm-2 pull-right" >

                <form name="formulaireInfo" id="formulaireInfo" method="post" action="ajax/designationInfo.php">

                        <div class="form-group" >

                            <input type="text" class="form-control" name="designation" size="100"/>

                        </div>

                </form>

            </div>

            <h2>Les Operations du voyage : <?php echo strtoupper($voyage['destination']).' du '.$voyage['dateVoyage']; ?> </h2>

            <div class="panel-group">

                <div class="panel" style="background:#cecbcb;">

                    <div class="panel-heading" >

                        <h4 class="panel-title">

                        <a data-toggle="collapse" href="#collapse1">

                        Total Frais : <?php echo number_format($TotalF[0], 0, ',', ' ').' F CFA'; ?> <=>

                        Total BL : <?php echo number_format($TotalB[0], 0, ',', ' ').' F CFA'; ?> <=>

                        Taux Revient : <?php echo number_format($TauxRevient, 2, ',', ' '); ?>

                        </a>

                        </h4>

                    </div>

                    <div id="collapse1" class="panel-collapse collapse">

                        <div class="panel-heading" style="margin-left:2%;">

                            <h4>Total des Frais : <?php echo number_format($TotalF[0], 0, ',', ' '); ?></h4>

                            <h4>Total des BL : <?php echo number_format($TotalB[0], 0, ',', ' '); ?></h4>

                        </div>

                    </div>

                </div>

            </div>

            <form class="form-inline pull-right noImpr"  style="margin-right:20px;"

                method="post" action="importExportDetails.php?iDS=<?= $idVoyage; ?>" >

                <input style="margin-right:5px;" size="10%" type="text" name="tauxVente" class="inputbasic form-control"  <?php echo "value=".number_format($voyage['tauxVente'], 2, '.', ' ')."" ; ?> >

                <button type="submit" name="btnRafraichirVente" class="btn btn-info  pull-right" style="margin-right:20px;" >

                    <span class="glyphicon glyphicon-refresh"></span> % Vente

                </button>

            </form>

            <form class="form-inline pull-left noImpr" style="margin-left:20px;"

                method="post" action="importExportDetails.php?iDS=<?= $idVoyage; ?>" >

                <input style="margin-left:5px;" size="10%" type="text" name="tauxRevient" class="inputbasic form-control"  <?php echo "value=".number_format($voyage['tauxRevient'], 2, '.', ' ')."" ; ?> >

                <button type="submit" name="btnRafraichirRevient"  class="btn btn-primary  pull-left" style="margin-left:20px;" >

                    <span class="glyphicon glyphicon-refresh"></span> % Revient

                </button>

            </form>

        </div>

        <!--*******************************Debut Rechercher Produit****************************************-->

            <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addBl">

                <i class="glyphicon glyphicon-plus"></i> Ajouter BL

            </button>

            <div id="addBl" class="modal fade" role="dialog">

              <div class="modal-dialog">

                <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Ajouter BL</h4>

                  </div>

                  <div class="modal-body" style="padding:40px 50px;">

                    <form name="formulaireVersement" method="post" <?php echo  "action=importExportDetails.php?iDS=". $idVoyage."" ; ?>>

                      <div class="form-group">

                      <label for="fournisseur">Fournisseur</label>

                        <select class="form-control" name="fournisseur" >

                            <option selected ></option>

                            <?php

                                $sql1="SELECT * FROM `". $nomtableFournisseur."` ORDER BY idFournisseur DESC";

                                $res1=mysql_query($sql1);

                                while($fourn = mysql_fetch_array($res1)) {

                                    echo'<option  value= "'.$fourn['idFournisseur'].'">'.$fourn['nomFournisseur'].'</option>';

                                }

                            ?>

                        </select>

                      </div>

                      <div class="form-group">

                        <label for="numeroBl">Numero BL</label>

                        <input type="text" class="inputbasic form-control" name="numeroBl" />

                      </div>

                      <div class="form-group">

                        <label for="dateBl">Date </label>

                        <input type="date" class="inputbasic form-control" name="dateBl" />

                      </div>

                      <div class="form-group">

                        <label for="montantDevise">Montant</label>

                        <input type="number" class="inputbasic form-control" name="montantDevise" />

                      </div>

                      <div class="form-group">

                            <label for="devise">Devise</label>

                            <input type="text" class="inputbasic form-control" name="devise" />

                      </div>

                      <div class="form-group">

                            <label for="valeurDevise">Valeur Devise</label>

                            <input type="text" class="inputbasic form-control" name="valeurDevise" />

                      </div>

                      <div class="form-group">

                            <label for="valeurConversion">Valeur Conversion</label>

                            <input type="text" class="inputbasic form-control" name="valeurConversion" />

                      </div>

                      <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                        <button type="submit" name="btnEnregistrerBl" class="btn btn-primary">Enregistrer</button>

                      </div>

                    </form>

                  </div>

                </div>

              </div>

            </div> 

        <!--*******************************Fin Rechercher Produit****************************************-->

        <!--*******************************Debut Ajouter Versement****************************************-->

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addFrais">

                <i class="glyphicon glyphicon-plus"></i> Ajouter Frais

            </button><br><br>

            <div id="addFrais" class="modal fade" role="dialog">

              <div class="modal-dialog">

                <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Ajouter Frais</h4>

                  </div>

                  <div class="modal-body" style="padding:40px 50px;">

                    <form name="formulaireVersement" method="post" <?php echo  "action=importExportDetails.php?iDS=". $idVoyage."" ; ?>>

                      <div class="form-group">

                        <label for="frais">Nom Frais </label>

                        <input type="text" class="inputbasic form-control" name="frais" />

                      </div>

                      <div class="form-group">

                        <label for="dateFrais">Date </label>

                        <input type="date" class="inputbasic form-control" name="dateFrais" />

                      </div>

                      <div class="form-group">

                        <label for="montantDevise">Montant</label>

                        <input type="number" class="inputbasic form-control" name="montantDevise" />

                      </div>

                      <div class="form-group">

                            <label for="devise">Devise</label>

                            <input type="text" class="inputbasic form-control" name="devise" />

                      </div>

                      <div class="form-group">

                            <label for="valeurDevise">Valeur Devise</label>

                            <input type="text" class="inputbasic form-control" name="valeurDevise" />

                      </div>

                      <div class="form-group">

                            <label for="valeurConversion">Valeur Conversion</label>

                            <input type="text" class="inputbasic form-control" name="valeurConversion" />

                      </div>                                                                                     

                    <?php if ($_SESSION['compte']==1) { ?>   

                    <select class="form-control compte" name="compte"  <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>

                        <!-- <option value="caisse">Caisse</option> -->

                        <?php

                            foreach ($cpt_array as $key) { ?>

                                <option value="<?= $key['idCompte'];?>"><?= $key['nomCompte'];?></option>

                            <?php } ?>

                    </select>

                    <?php } ?>

                      <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                        <button type="submit" name="btnEnregistrerFrais" class="btn btn-primary">Enregistrer</button>

                      </div>

                    </form>

                  </div>

                </div>

              </div>

            </div> 

        <!--*******************************Fin Ajouter Versement****************************************-->





            

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
                function showPreviewBl(event,idBl) {
                    var file = document.getElementById('input_file_Bl'+idBl).value;
                    var reader = new FileReader();
                    reader.onload = function()
                    {
                        var format = file.substr(file.length - 3);
                        var pdf = document.getElementById('output_pdf_Bl'+idBl);
                        var image = document.getElementById('output_image_Bl'+idBl);
                        if(format=='pdf'){
                            document.getElementById('output_pdf_Bl'+idBl).style.display = "block";
                            document.getElementById('output_image_Bl'+idBl).style.display = "none";
                            pdf.src = reader.result;
                        }
                        else{
                            document.getElementById('output_image_Bl'+idBl).style.display = "block";
                            document.getElementById('output_pdf_Bl'+idBl).style.display = "none";
                            image.src = reader.result;
                        }
                    }
                    reader.readAsDataURL(event.target.files[0]);
                    document.getElementById('btn_upload_Bl'+idBl).style.display = "block";
                }
                function showPreviewFrais(event,idFrais) {
                    var file = document.getElementById('input_file_Frais'+idFrais).value;
                    var reader = new FileReader();
                    reader.onload = function()
                    {
                        var format = file.substr(file.length - 3);
                        var pdf = document.getElementById('output_pdf_Frais'+idFrais);
                        var image = document.getElementById('output_image_Frais'+idFrais);
                        if(format=='pdf'){
                            document.getElementById('output_pdf_Frais'+idFrais).style.display = "block";
                            document.getElementById('output_image_Frais'+idFrais).style.display = "none";
                            pdf.src = reader.result;
                        }
                        else{
                            document.getElementById('output_image_Frais'+idFrais).style.display = "block";
                            document.getElementById('output_pdf_Frais'+idFrais).style.display = "none";
                            image.src = reader.result;
                        }
                    }
                    reader.readAsDataURL(event.target.files[0]);
                    document.getElementById('btn_upload_Frais'+idFrais).style.display = "block";
                }

            </script>

        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->



        <!-- Debut de l'Accordion pour Tout les Paniers -->

        <div class="panel-group" id="accordion">



            <?php

            // On détermine sur quelle page on se trouve

            if(isset($_GET['page']) && !empty($_GET['page'])){

                $currentPage = (int) strip_tags($_GET['page']);

            }else{

                $currentPage = 1;

            }

            // On détermine le nombre d'articles par page

            $parPage = 10;



            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/

                $sqlC="SELECT

                    COUNT(*) AS total

                FROM

                (SELECT b.idVoyage FROM `".$nomtableBl."` b where b.idVoyage='".$idVoyage."'

                UNION ALL

                SELECT f.idVoyage FROM `".$nomtableFrais."` f where f.idVoyage='".$idVoyage."'

                ) AS a ";

                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());

                $nbre = mysql_fetch_array($resC) ;

                $nbPaniers = (int) $nbre['total'];

            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/



            // On calcule le nombre de pages total

            $pages = ceil($nbPaniers / $parPage);

                            

            $premier = ($currentPage * $parPage) - $parPage;



            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/

                $sqlP1="SELECT *,CONCAT(dateBl,'',heureBl) AS dateHeure

                FROM

                (SELECT b.idVoyage,b.idBl,b.dateBl,b.heureBl FROM `".$nomtableBl."` b where b.idVoyage='".$idVoyage."' 

                UNION 

                SELECT f.idVoyage,f.idFrais,f.dateFrais,f.heureFrais FROM `".$nomtableFrais."` f where f.idVoyage='".$idVoyage."'

                ) AS a ORDER BY dateHeure DESC LIMIT ".$premier.",".$parPage." ";

                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/



            /**Debut requete pour Rechercher le dernier Panier Ajouter  **/

                $reqA="SELECT dateBl

                FROM

                (SELECT b.idVoyage,b.idBl,b.dateBl,b.heureBl FROM `".$nomtableBl."` b where b.idVoyage='".$idVoyage."' 

                UNION 

                SELECT f.idVoyage,f.idfrais,f.datefrais,f.heurefrais FROM `".$nomtableFrais."` f where f.idVoyage='".$idVoyage."'

                ) AS a ORDER BY dateBl DESC LIMIT 1";

                $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());

            /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ ?>         





            <!-- Debut Boucle while concernant les Paniers Vendus -->

            <?php $n=$nbPaniers - (($currentPage * 10) - 10); 

                while ($bons = mysql_fetch_assoc($resP1)) {   ?>

                    <?php	$idmax=mysql_result($resA,0); ?>



                        <?php

                            $sqlT1="SELECT * FROM `".$nomtableBl."` where idBl='".$bons['idBl']."' AND dateBl='".$bons['dateBl']."' AND idVoyage='".$idVoyage."' ";

                            $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                            $bl = mysql_fetch_assoc($resT1);

                            if($bl!=null){

                                $sql1="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$bl['idFournisseur']."' ";

                                $res1=mysql_query($sql1);

                                $fourn = mysql_fetch_array($res1);

                                ?>

                                <div style="padding-top : 2px;" class="panel panel-success">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bl".$bl['idBl']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> BL 

                                            <span class="spanDate noImpr"> Date: <?php echo $bl['dateBl']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php

                                                if($bl['heureBl']!=null){

                                                echo $bl['heureBl'];

                                                }

                                                else{

                                                    echo "00:00:00";

                                                } 

                                                ?>

                                            </span>

                                            <span class="spanDate noImpr">Montant: <?php echo number_format(($bl['montantBl'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole']; ?></span>

                                            <span class="spanDate noImpr"> FN : <?php echo $fourn['nomFournisseur']; ?></span>

                                            <span class="spanDate noImpr"> Numero : <?php echo $bl['numeroBl']; ?></span>

                                        </a>

                                        </h4>

                                    </div>

                                    <div class="panel-collapse collapse " <?php echo  "id=bl".$bl['idBl']."" ; ?> >

                                        <div class="panel-body" >

                                            <?php 

                                                $sql2="SELECT * from `".$nomtableStock."` where idBl='".$bl["idBl"]."' ";

                                                $res2=mysql_query($sql2);

                                                if (mysql_num_rows($res2)){?>

                                                    <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl-Entrepot_ImportExport.php?id=".$bl['idBl']."" ; ?>  >

                                                        <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >

                                                        <button type="submit" class="btn btn-warning pull-right" >

                                                        <span class="glyphicon glyphicon-folder-open"></span> Details

                                                        </button> 

                                                    </form>

                                                    <table class="table table-bordered" style="margin-top:40px;">

                                                        <thead class="noImpr">

                                                            <tr>

                                                                <th>BL</th>

                                                                <th>Montant</th>

                                                                <th>Devise</th>

                                                                <th>Valeur Devise</th>

                                                                <th>Valeur Conversion</th>

                                                                <th>Montant CFA</th>

                                                                <th>Piece Jointe</th>

                                                            </tr>

                                                        </thead>

                                                        <tbody>
                                                                <tr>

                                                                    <td><?php echo strtoupper($bl['numeroBl']); ?></td>

                                                                    <td><?php echo $bl['montantDevise']; ?></td>

                                                                    <td><?php echo strtoupper($bl['devise']); ?></td>

                                                                    <td><?php echo $bl['valeurDevise']; ?></td>

                                                                    <td><?php echo $bl['valeurConversion']; ?></td>

                                                                    <td><?php echo $bl['montantBl']; ?></td>

                                                                    <td>
                                                                        <?php if($bl['image']!=null && $bl['image']!=' '){
                                                                            $format=substr($bl['image'], -3); ?>
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        <?php }
                                                                            else { 
                                                                        ?>
                                                                            <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                        <?php } ?>
                                                                    </td>

                                                                </tr> 

                                                        </tbody>

                                                    </table>

                                                    <table class="table ">

                                                        <thead class="noImpr">

                                                            <tr>

                                                                <th>Référence</th>

                                                                <th>Unite Stock (US)</th>

                                                                <th>Quantite</th>

                                                                <th>Prix Achat</th>

                                                                <th>Prix US</th>

                                                                <th>Enregistrement</th>

                                                                <th>Expiration</th>

                                                            </tr>

                                                        </thead>

                                                        <tbody>

                                                            <?php 

                                                                while ($stock = mysql_fetch_assoc($res2)) { echo '

                                                                    <tr>

                                                                        <td>'.strtoupper($stock['designation']).'</td>

                                                                        <td>'.strtoupper($stock['uniteStock']).'</td>

                                                                        <td>'.$stock['quantiteStockinitial'].'</td>

                                                                        <td>'.$stock['prixachat'].'</td>

                                                                        <td>'.$stock['prixuniteStock'].'</td>

                                                                        <td>'.$stock['dateStockage'].'</td>

                                                                        <td>'.$stock['dateExpiration'].'</td>

                                                                    </tr> ';

                                                                }

                                                            ?>

                                                        </tbody>

                                                    </table>

                                                <?php }

                                                else {  ?>

                                                    <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_bl".$bl['idBl'] ; ?>	 >

                                                        <span class="glyphicon glyphicon-remove"></span>Annuler

                                                    </button>

                                                    <?php

                                                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { ?>

                                                        <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl-Entrepot_ImportExport.php?id=".$bl['idBl']."" ; ?>  >

                                                            <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >

                                                            

                                                            <button type="submit" class="btn btn-warning pull-right" >

                                                            <span class="glyphicon glyphicon-folder-open"></span> Details

                                                            </button>

                                                        </form>

                                                    <?php }

                                                    else  { ?>

                                                        <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >

                                                            <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >

                                                            

                                                            <button type="submit" class="btn btn-warning pull-right" >

                                                            <span class="glyphicon glyphicon-folder-open"></span> Details

                                                            </button>

                                                        </form>

                                                    <?php }?>

                                                    <table class="table table-bordered" style="margin-top:40px;">

                                                        <thead class="noImpr">

                                                            <tr>

                                                                <th>BL</th>

                                                                <th>Montant</th>

                                                                <th>Devise</th>

                                                                <th>Valeur Devise</th>

                                                                <th>Valeur Conversion</th>

                                                                <th>Montant CFA</th>

                                                                <th>Piece Jointe</th>

                                                            </tr>

                                                        </thead>

                                                        <tbody>

                                                                <tr>
                                                                    <td><?php echo strtoupper($bl['numeroBl']); ?></td>

                                                                    <td><?php echo $bl['montantDevise']; ?></td>

                                                                    <td><?php echo strtoupper($bl['devise']); ?></td>

                                                                    <td><?php echo $bl['valeurDevise']; ?></td>

                                                                    <td><?php echo $bl['valeurConversion']; ?></td>

                                                                    <td><?php echo $bl['montantBl']; ?></td>

                                                                    <td>
                                                                        <?php if($bl['image']!=null && $bl['image']!=' '){
                                                                            $format=substr($bl['image'], -3); ?>
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        <?php }
                                                                            else { 
                                                                        ?>
                                                                            <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                        <?php } ?>
                                                                    </td>

                                                                </tr> 

                                                        </tbody>

                                                    </table>

                                                <?php }?>

                                            

                                            <div class="modal fade" <?php echo  "id=msg_anl_bl".$bl['idBl'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Confirmation</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                            <div class="modal-body">

                                                                <p><?php echo "Voulez-vous annuler le Bon de Livraison numéro : <b>".$bl['numeroBl']."<b>" ; ?></p>

                                                                <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?>>

                                                                <input type="hidden" name="montantB" id="montantBl"  <?php echo  "value=".$bl['montantBl']."" ; ?>>

                                                            </div>

                                                            <div class="modal-footer">

                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                <button type="submit" name="btnAnnulerBl" class="btn btn-success">Confirmer</button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal fade" <?php echo  "id=imageNvBl".$bl['idBl'] ; ?> role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="padding:35px 50px;">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> BL : <b><?php echo  $bl['numeroBl'] ; ?></b></h4>
                                                        </div>
                                                        <form   method="post" enctype="multipart/form-data">
                                                            <div class="modal-body" style="padding:40px 50px;">
                                                                <input  type="text" style="display:none" name="idBl" id="idBl_Upd_Nv" <?php echo  "value=".$bl['idBl']."" ; ?> />
                                                                <div class="form-group" style="text-align:center;" >
                                                                    <?php 
                                                                        if($bl['image']!=null && $bl['image']!=' '){ 
                                                                            $format=substr($bl['image'], -3);
                                                                            ?>
                                                                                <input type="file" name="file" value="<?php echo  $bl['image']; ?>" accept="image/*" id="input_file_Bl<?php echo  $bl['idBl']; ?>" onchange="showPreviewBl(event,<?php echo  $bl['idBl']; ?>);"/><br />
                                                                                <?php if($format=='pdf'){ ?>
                                                                                    <iframe id="output_pdf_Bl<?php echo  $bl['idBl']; ?>" src="./PiecesJointes/<?php echo  $bl['image']; ?>" width="100%" height="500px"></iframe>
                                                                                    <img style="display:none;" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                                <?php }
                                                                                else { ?>
                                                                                    <img  src="./PiecesJointes/<?php echo  $bl['image']; ?>" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                                    <iframe id="output_pdf_Bl<?php echo  $bl['idBl'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                                <?php } ?>
                                                                            <?php 
                                                                        }
                                                                        else{ ?>
                                                                            <input type="file" name="file" accept="image/*" id="input_file_Bl<?php echo  $bl['idBl']; ?>" id="cover_image" onchange="showPreviewBl(event,<?php echo  $bl['idBl']; ?>);"/><br />
                                                                            <img  style="display:none;" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                            <iframe id="output_pdf_Bl<?php echo  $bl['idBl'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                    <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBl" id="btn_upload_Bl<?php echo  $bl['idBl']; ?>" >
                                                                        <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                    </button>
                                                                    <?php if($bl['image']!=null && $bl['image']!=' '){ ?>
                                                                        <!-- <button type="submit" class="btn btn-primary pull-right" name="btnDownloadImg" >
                                                                        <span class="glyphicon glyphicon-download"></span> Telecharger
                                                                    </button> -->
                                                                    <?php } ?>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                    </div>

                                </div>

                            <?php

                            }



                            $sqlT2="SELECT * FROM `".$nomtableFrais."` where idFrais='".$bons['idBl']."' AND dateFrais='".$bons['dateBl']."' AND idVoyage='".$idVoyage."' ";

                            $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());

                            $frais = mysql_fetch_assoc($resT2);

                            if($frais!=null){?>

                                <div style="padding-top : 2px;" class="panel panel-warning">

                                    <div class="panel-heading">

                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#frais".$frais['idFrais']."" ; ?>  class="panel-title expand">

                                        <div class="right-arrow pull-right">+</div>

                                        <a href="#"> Frais 

                                            <span class="spanDate noImpr"> </span>

                                            <span class="spanDate noImpr"> Date: <?php echo $frais['dateFrais']; ?> </span>

                                            <span class="spanDate noImpr">Heure: <?php echo $frais['heureFrais']; ?></span>

                                            <span class="spanDate noImpr">Montant: <?php echo number_format(($frais['montantFrais'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole']; ?></span>

                                            <span class="spanDate noImpr"> Frais : <?php echo $frais['frais']; ?></span>

                                        </a>

                                        </h4>

                                    </div>

                                    <div class="panel-collapse collapse " <?php echo  "id=frais".$frais['idFrais']."" ; ?> >

                                        <div class="panel-body" >

                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_frais".$frais['idFrais'] ; ?>	 >

                                                <span class="glyphicon glyphicon-remove"></span>Annuler

                                            </button>

                                            

                                            <div class="modal fade" <?php echo  "id=msg_anl_frais".$frais['idFrais'] ; ?> role="dialog">

                                                <div class="modal-dialog">

                                                    <!-- Modal content-->

                                                    <div class="modal-content">

                                                        <div class="modal-header panel-primary">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            <h4 class="modal-title">Confirmation</h4>

                                                        </div>

                                                        <form class="form-inline noImpr"  id="factForm" method="post"  >

                                                            <div class="modal-body">

                                                                <p><?php echo "Voulez-vous annuler le frais numéro <b>".$n."<b>" ; ?></p>

                                                                <input type="hidden" name="idFrais" id="idFrais"  <?php echo  "value=".$frais['idFrais']."" ; ?>>

                                                                <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$frais['montantFrais']."" ; ?>>

                                                            </div>

                                                            <div class="modal-footer">

                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                                                <button type="submit" name="btnAnnulerFrais" class="btn btn-success">Confirmer</button>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </div>

                                            </div>



                                            <table class="table table-bordered" style="margin-top:40px;">

                                                <thead class="noImpr">

                                                    <tr>

                                                        <th>Frais</th>

                                                        <th>Montant</th>

                                                        <th>Devise</th>

                                                        <th>Valeur Devise</th>

                                                        <th>Valeur Conversion</th>

                                                        <th>Montant CFA</th>

                                                        <th>Piece Jointe</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                        <tr>

                                                            <td><?php echo strtoupper($frais['frais']); ?></td>

                                                            <td><?php echo $frais['montantDevise']; ?></td>

                                                            <td><?php echo strtoupper($frais['devise']); ?></td>

                                                            <td><?php echo $frais['valeurDevise']; ?></td>

                                                            <td><?php echo $frais['valeurConversion']; ?></td>

                                                            <td><?php echo $frais['montantFrais']; ?></td>

                                                            <td>
                                                                <?php if($frais['image']!=null && $frais['image']!=' '){
                                                                        $format=substr($frais['image'], -3); ?>
                                                                        <?php if($format=='pdf'){ ?>
                                                                            <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvFrais".$frais['idFrais'] ; ?> onclick="imageUpFrais(<?php echo $frais['idFrais']; ?>,<?php echo $frais['image']; ?>)" 	 />
                                                                        <?php }
                                                                            else { 
                                                                        ?>
                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvFrais".$frais['idFrais'] ; ?> onclick="imageUpFrais(<?php echo $frais['idFrais']; ?>,<?php echo $frais['image']; ?>)" 	 />
                                                                        <?php } ?>
                                                                    <?php }
                                                                        else { 
                                                                    ?>
                                                                    <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvFrais".$frais['idFrais'] ; ?> onclick="imageUpFrais(<?php echo $frais['idFrais']; ?>,<?php echo $frais['image']; ?>)" 	 />
                                                                <?php } ?>
                                                            </td>

                                                        </tr> 

                                                    

                                                </tbody>

                                            </table>

                                            

                                        </div>

                                    </div>

                                </div>

                                <div class="modal fade" <?php echo  "id=imageNvFrais".$frais['idFrais'] ; ?> role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header" style="padding:35px 50px;">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Frais : <b><?php echo  $frais['idFrais'] ; ?></b></h4>
                                            </div>
                                            <form   method="post" enctype="multipart/form-data">
                                                <div class="modal-body" style="padding:40px 50px;">
                                                    <input  type="text" style="display:none" name="idFrais" id="idFrais_Upd_Nv" <?php echo  "value=".$frais['idFrais']."" ; ?> />
                                                    <div class="form-group" style="text-align:center;" >
                                                        <?php 
                                                            if($frais['image']!=null && $frais['image']!=' '){ 
                                                                $format=substr($frais['image'], -3);
                                                                ?>
                                                                    <input type="file" name="file" value="<?php echo  $frais['image']; ?>" accept="image/*" id="input_file_Frais<?php echo  $frais['idFrais']; ?>" onchange="showPreviewFrais(event,<?php echo  $frais['idFrais']; ?>);"/><br />
                                                                    <?php if($format=='pdf'){ ?>
                                                                        <iframe id="output_pdf_Frais<?php echo  $frais['idFrais']; ?>" src="./PiecesJointes/<?php echo  $frais['image']; ?>" width="100%" height="500px"></iframe>
                                                                        <img style="display:none;" width="500px" height="500px" id="output_image_Frais<?php echo  $frais['idFrais']; ?>"/>
                                                                    <?php }
                                                                    else { ?>
                                                                        <img  src="./PiecesJointes/<?php echo  $frais['image']; ?>" width="500px" height="500px" id="output_image_Frais<?php echo  $frais['idFrais']; ?>"/>
                                                                        <iframe id="output_pdf_Frais<?php echo  $frais['idFrais'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                    <?php } ?>
                                                                <?php 
                                                            }
                                                            else{ ?>
                                                                <input type="file" name="file" accept="image/*" id="input_file_Frais<?php echo  $frais['idFrais']; ?>" id="cover_image" onchange="showPreviewFrais(event,<?php echo  $frais['idFrais']; ?>);"/><br />
                                                                <img  style="display:none;" width="500px" height="500px" id="output_image_Frais<?php echo  $frais['idFrais']; ?>"/>
                                                                <iframe id="output_pdf_Frais<?php echo  $frais['idFrais'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                            <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgFrais" id="btn_upload_Frais<?php echo  $frais['idFrais']; ?>" >
                                                            <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                        </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php

                            }

                    ?>

                <?php $n=$n-1;   } ?>

                <?php if($nbPaniers >= 11){ ?>

                    <ul class="pagination pull-right">

                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->

                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">

                            <a href="importExportDetails.php?iDS=<?= $idVoyage; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                        </li>

                        <?php for($page = 1; $page <= $pages; $page++): ?>

                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->

                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                                <a href="importExportDetails.php?iDS=<?= $idVoyage; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>

                            </li>

                        <?php endfor ?>

                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->

                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">

                            <a href="importExportDetails.php?iDS=<?= $idVoyage; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                        </li>

                    </ul>

                <?php } ?>

            <!-- Fin Boucle while concernant les Paniers Vendus  -->



        </div>

        <!-- Fin de l'Accordion pour Tout les Paniers -->



        <?php /*****************************/

        echo''.

        '<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

        ?>



        <!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->

            <?php

                if(isset($msg_info)) {

                echo"<script type='text/javascript'>

                            $(window).on('load',function(){

                                $('#msg_info').modal('show');

                            });

                        </script>";

                echo'<div id="msg_info" class="modal fade " role="dialog">

                            <div class="modal-dialog">

                                <!-- Modal content-->

                                <div class="modal-content">

                                    <div class="modal-header panel-primary">

                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        <h4 class="modal-title">Alerte</h4>

                                    </div>

                                    <div class="modal-body">

                                        <p>'.$msg_info.'</p>

                                        

                                    </div>

                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                                    </div>

                                    </div>

                            </div>

                        </div>';

                        

                }

            ?>

        <!-- Fin PopUp d'Alerte sur l'ensemble de la Page -->



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

                                <p>IMPOSSIBLE.</br>

                                </br> La quantité de ce stock est insuffisant pour la ligne.

                                </br> Il vous reste  <span id="qte_stock"></span> Unités dans le Stock.

                                </p>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                            </div>

                            </div>

                    </div>

            </div>

        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->



    </div>

    <!-- Fin Container HTML -->

</header>

</body>

<!-- Fin Code HTML -->



<?php

function updateTauxRevient($idVoyage,$nomtableBl,$nomtableFrais,$nomtableVoyage){

    $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idVoyage=".$idVoyage." ";

    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

    $TotalB = mysql_fetch_array($res12) ; 



    $sql13="SELECT SUM(montantFrais) FROM `".$nomtableFrais."` where idVoyage=".$idVoyage." ";

    $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

    $TotalF = mysql_fetch_array($res13) ;



    if($TotalB[0]!=0 || $TotalB[0]!=null){

        $TauxRevient=$TotalF[0]/$TotalB[0];

    }

    else{

        $TauxRevient=0;

    }



    $sqlS="UPDATE `".$nomtableVoyage."` set tauxRevient=".$TauxRevient." WHERE idVoyage=".$idVoyage." ";

    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

}



?>



